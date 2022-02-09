<?php

namespace App\Domain\Entity;

use App\Application\Contracts\GenericIdInterface;
use App\Domain\Dto\PlanProfile;
use App\Domain\Dto\WorkspaceProfile;
use App\Infrastructure\Repository\WorkspaceRepository;
use App\Infrastructure\Support\ArrayPresenterTrait;
use App\Infrastructure\Support\GuidBasedImmutableId;
use Carbon\Carbon;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: WorkspaceRepository::class)]
#[ORM\Table(name: '`workspaces`')]
#[ORM\Index(fields: ["keeperId"])]
#[ORM\Index(fields: ["addedAt"])]
#[ORM\HasLifecycleCallbacks]
class Workspace
{
    use TimestampableEntity, ArrayPresenterTrait;

    private function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'uuid')]
        private UuidInterface $id,

        #[ORM\Column(type: 'uuid')]
        private UuidInterface $keeperId,

        #[ORM\Column(type: Types::JSON, options: ["jsonb" => true])]
        private array $profile,

        #[ORM\Column(type: Types::DATETIME_MUTABLE)]
        private DateTime $addedAt,

        #[ORM\OneToMany(mappedBy: "workspace", targetEntity: "Plan")]
        private ArrayCollection $plans,
    ) {
    }

    public static function create(
        GenericIdInterface $workspaceId,
        GenericIdInterface $keeperId,
        WorkspaceProfile $profile,
    ): static {
        return new static(
            Uuid::fromString((string) $workspaceId),
            Uuid::fromString((string) $keeperId),
            $profile->toArray(),
            Carbon::now()->toDateTime(),
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

    public function getKeeperId(): GenericIdInterface
    {
        return GuidBasedImmutableId::of((string) $this->keeperId);
    }

    public function setKeeperId(GenericIdInterface $keeperId): self
    {
        $this->keeperId = Uuid::fromString((string) $keeperId);
        return $this;
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

    public function getPlans(): ArrayCollection
    {
        return $this->plans;
    }

    public function addPlan(GenericIdInterface $planId, PlanProfile $planProfile): Plan
    {
        return $this->plans[] = Plan::create($planId, $this->getId(), $planProfile);
    }
}
