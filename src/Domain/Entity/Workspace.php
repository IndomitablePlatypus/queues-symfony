<?php

namespace App\Domain\Entity;

use App\Application\Contracts\GenericIdInterface;
use App\Domain\Dto\PlanProfile;
use App\Domain\Dto\RelationType;
use App\Domain\Dto\WorkspaceProfile;
use App\Infrastructure\Exceptions\NotFoundException;
use App\Infrastructure\Repository\WorkspaceRepository;
use App\Infrastructure\Support\ArrayPresenterTrait;
use App\Infrastructure\Support\CarbonWrap;
use App\Infrastructure\Support\GuidBasedImmutableId;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: WorkspaceRepository::class)]
#[ORM\Table(name: '`workspaces`')]
#[ORM\Index(fields: ["addedAt"])]
#[ORM\HasLifecycleCallbacks]
class Workspace
{
    use TimestampableEntity, ArrayPresenterTrait, CarbonWrap;

    private function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'uuid')]
        private UuidInterface $id,

        #[ORM\ManyToOne(targetEntity: "User", inversedBy: "workspaces")]
        #[ORM\JoinColumn(name: "keeper_id", referencedColumnName: "id")]
        private User $keeper,

        #[ORM\Column(type: Types::JSON, options: ["jsonb" => true])]
        private array $profile,

        #[ORM\Column(type: Types::DATETIME_MUTABLE)]
        private DateTime $addedAt,

        #[ORM\OneToMany(mappedBy: "workspace", targetEntity: "Plan")]
        private Collection $plans,

        #[ORM\OneToMany(mappedBy: "workspace", targetEntity: "Relation")]
        private Collection $relations,
    ) {
    }

    public static function create(
        GenericIdInterface $workspaceId,
        User $keeper,
        WorkspaceProfile $profile,
    ): static {
        return (new static(
            Uuid::fromString((string) $workspaceId),
            $keeper,
            $profile->toArray(),
            self::now(),
            new ArrayCollection(),
            new ArrayCollection(),
        ));
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

    public function getKeeperId(): GenericIdInterface
    {
        return $this->keeper->getId();
    }

    public function getProfile(): WorkspaceProfile
    {
        return WorkspaceProfile::fromArray($this->profile);
    }

    public function setProfile(WorkspaceProfile $profile): self
    {
        $this->profile = $profile->toArray();
        return $this;
    }

    public function getPlans(): Collection
    {
        return $this->plans;
    }

    public function getPlan(GenericIdInterface $planId): Plan
    {
        $plan = $this
            ->plans
            ->filter(fn($plan) => $planId->equals($plan->getId()))
            ->first();

        return $plan instanceof Plan
            ? $plan
            : throw new NotFoundException("Plan $planId not found");
    }

    public function addPlan(GenericIdInterface $planId, PlanProfile $planProfile): Plan
    {
        return $this->plans[] = Plan::create($planId, $this, $planProfile);
    }

    public function getCard(GenericIdInterface $cardId): Card
    {
        /** @var Plan $plan */
        foreach ($this->getPlans() as $plan) {
            $card = $plan->getCard($cardId);
            if ($card !== null) {
                return $card;
            }
        }
        throw new NotFoundException("Card $card not found");
    }

    public function relateTo(User $collaborator, RelationType $relationType): Relation
    {
        $relation = Relation::create(
            GuidBasedImmutableId::make(),
            $collaborator,
            $this,
            $relationType,
        );
        $this->relations[] = $relation;
        return $relation;
    }

    public function getRelations(): Collection
    {
        return $this->relations;
    }

    public function invite(): Invite
    {
        return Invite::create(
            GuidBasedImmutableId::make(),
            $this->getId(),
        );
    }
}
