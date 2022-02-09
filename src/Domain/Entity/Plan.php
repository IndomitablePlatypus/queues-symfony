<?php

namespace App\Domain\Entity;

use App\Application\Contracts\GenericIdInterface;
use App\Domain\Dto\PlanProfile;
use App\Infrastructure\Exceptions\LogicException;
use App\Infrastructure\Exceptions\ParameterAssertionException;
use App\Infrastructure\Repository\PlanRepository;
use App\Infrastructure\Support\ArrayPresenterTrait;
use App\Infrastructure\Support\GuidBasedImmutableId;
use Carbon\Carbon;
use DateTime;
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
    use TimestampableEntity, ArrayPresenterTrait;

    #[ORM\ManyToOne(targetEntity: "Workspace", inversedBy: "plans")]
    #[ORM\JoinColumn(name: "workspace_id", referencedColumnName: "id")]
    private Workspace $workspace;

    private function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'uuid')]
        private UuidInterface $id,

        #[ORM\Column(type: 'uuid')]
        private UuidInterface $workspaceId,

        #[ORM\Column(type: Types::JSON, options: ["jsonb" => true])]
        private array $profile,

        #[ORM\Column(type: Types::DATETIME_MUTABLE)]
        private DateTime $addedAt,

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
        GenericIdInterface $workspaceId,
        PlanProfile $profile,
    ): static {
        return new static(
            Uuid::fromString((string) $planId),
            Uuid::fromString((string) $workspaceId),
            $profile->toArray(),
            Carbon::now()->toDateTime(),
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
        return GuidBasedImmutableId::of((string) $this->workspaceId);
    }

    public function setWorkspaceId(GenericIdInterface $workspaceId): self
    {
        $this->workspaceId = Uuid::fromString((string) $workspaceId);
        return $this;
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
        return Carbon::instance($this->launchedAt);
    }

    public function getStoppedAt(): ?Carbon
    {
        return Carbon::instance($this->stoppedAt);
    }

    public function getArchivedAt(): ?Carbon
    {
        return Carbon::instance($this->archivedAt);
    }

    public function getExpitationDate(): ?Carbon
    {
        return Carbon::instance($this->expirationDate);
    }

    public function launch(string $expirationDate): static
    {
        if ($this->archivedAt) {
            throw new LogicException('Cannot launch archived plan');
        }
        try {
            $this->expirationDate = new Carbon($expirationDate);
        } catch (Throwable) {
            throw new ParameterAssertionException("Expiration date should be a valid date");
        }

        $this->launchedAt = Carbon::now();
        $this->stoppedAt = null;
        return $this;
    }

    public function stop(): static
    {
        if ($this->archivedAt) {
            throw new LogicException('Cannot stop archived plan');
        }
        $this->stoppedAt = Carbon::now();
        $this->launchedAt = null;
        $this->expirationDate = null;
        return $this;
    }

    public function archive(): static
    {
        $this->archivedAt = Carbon::now();
        return $this;
    }

}
