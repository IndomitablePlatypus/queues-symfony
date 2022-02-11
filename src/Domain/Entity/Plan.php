<?php

namespace App\Domain\Entity;

use App\Application\Contracts\GenericIdInterface;
use App\Domain\Dto\PlanProfile;
use App\Infrastructure\Exceptions\LogicException;
use App\Infrastructure\Exceptions\NotFoundException;
use App\Infrastructure\Exceptions\ParameterAssertionException;
use App\Infrastructure\Repository\PlanRepository;
use App\Infrastructure\Support\ArrayPresenterTrait;
use App\Infrastructure\Support\CarbonWrap;
use App\Infrastructure\Support\GuidBasedImmutableId;
use Carbon\Carbon;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Throwable;

#[ORM\Entity(repositoryClass: PlanRepository::class)]
#[ORM\Table(name: '`plans`')]
#[ORM\Index(fields: ["workspaceId"])]
#[ORM\Index(fields: ["addedAt"])]
#[ORM\Index(fields: ["launchedAt"])]
#[ORM\Index(fields: ["stoppedAt"])]
#[ORM\Index(fields: ["archivedAt"])]
#[ORM\Index(fields: ["expirationDate"])]
#[ORM\HasLifecycleCallbacks]
class Plan
{
    use TimestampableEntity, ArrayPresenterTrait, CarbonWrap;

    private function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'uuid')]
        private UuidInterface $id,

        #[ORM\ManyToOne(targetEntity: "Workspace", inversedBy: "plans")]
        #[ORM\JoinColumn(name: "workspace_id", referencedColumnName: "id")]
        private Workspace $workspace,

        #[ORM\Column(type: Types::JSON, options: ["jsonb" => true])]
        private array $profile,

        #[ORM\Column(type: Types::DATETIME_MUTABLE)]
        private DateTime $addedAt,

        #[ORM\OneToMany(mappedBy: "plan", targetEntity: "Requirement")]
        private Collection $requirements,

        #[ORM\OneToMany(mappedBy: "plan", targetEntity: "Card")]
        private Collection $cards,

        #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
        private ?DateTime $launchedAt = null,

        #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
        private ?DateTime $stoppedAt = null,

        #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
        private ?DateTime $archivedAt = null,

        #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
        private ?DateTime $expirationDate = null,
    ) {
    }

    public static function create(
        GenericIdInterface $planId,
        Workspace $workspace,
        PlanProfile $profile,
    ): static {
        return new self(
            Uuid::fromString((string) $planId),
            $workspace,
            $profile->toArray(),
            self::now(),
            new ArrayCollection(),
            new ArrayCollection(),
        );
    }

    public function getId(): GenericIdInterface
    {
        return GuidBasedImmutableId::of((string) $this->id);
    }

    public function setId(GenericIdInterface $id): self
    {
        $this->id = Uuid::fromString((string) $id);
        return $this;
    }

    public function getWorkspaceId(): GenericIdInterface
    {
        return $this->workspace->getId();
    }

    public function getWorkspace(): Workspace
    {
        return $this->workspace;
    }

    public function getProfile(): PlanProfile
    {
        return PlanProfile::fromArray($this->profile);
    }

    public function setProfile(PlanProfile $profile): self
    {
        $this->profile = $profile->toArray();
        return $this;
    }

    public function getLaunchedAt(): ?Carbon
    {
        return self::carbonOrNull($this->launchedAt);
    }

    public function getStoppedAt(): ?Carbon
    {
        return self::carbonOrNull($this->stoppedAt);
    }

    public function getArchivedAt(): ?Carbon
    {
        return self::carbonOrNull($this->archivedAt);
    }

    public function getExpirationDate(): ?Carbon
    {
        return self::carbonOrNull($this->expirationDate);
    }

    public function launch(string $expirationDate): self
    {
        if ($this->archivedAt) {
            throw new LogicException('Cannot launch archived plan');
        }
        try {
            $this->expirationDate = self::dateOf($expirationDate);
        } catch (Throwable) {
            throw new ParameterAssertionException("Expiration date should be a valid date");
        }

        $this->launchedAt = self::now();
        $this->stoppedAt = null;
        return $this;
    }

    public function stop(): self
    {
        if ($this->archivedAt) {
            throw new LogicException('Cannot stop archived plan');
        }
        $this->stoppedAt = self::now();
        $this->launchedAt = null;
        $this->expirationDate = null;
        return $this;
    }

    public function archive(): self
    {
        $this->archivedAt = self::now();
        return $this;
    }

    public function getCompactRequirements(): Collection
    {
        return $this
            ->requirements
            ->matching(Criteria::create()->where(Criteria::expr()?->eq('removedAt', null)))
            ->map(fn(Requirement $requirement) => $requirement->toCompactArray());
    }

    public function getRequirement(GenericIdInterface $requirementId): Requirement
    {
        $requirement = $this->requirements->matching(
            Criteria::create()
                ->where(Criteria::expr()?->eq('id', (string) $requirementId))
                ->andWhere(Criteria::expr()?->eq('removedAt', null))
        )->first();
        return $requirement instanceof Requirement
            ? $requirement
            : throw new NotFoundException("Requirement $requirementId not found");
    }

    public function addRequirement(GenericIdInterface $requirementId, string $description): Requirement
    {
        return $this->requirements[] = Requirement::create($requirementId, $this, $description);
    }

    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function getCard(GenericIdInterface $cardId): ?Card
    {
        $card = $this->cards->matching(
            Criteria::create()
                ->where(Criteria::expr()?->eq('id', (string) $cardId))
        )->first();
        return $card ?: null;
    }

    public function addCard(GenericIdInterface $cardId, User $customer): Card
    {
        return $this->cards[] = Card::create($cardId, $this, $customer);
    }

}
