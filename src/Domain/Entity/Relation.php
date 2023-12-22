<?php

namespace App\Domain\Entity;

use App\Application\Contracts\GenericIdInterface;
use App\Domain\Dto\RelationType;
use App\Infrastructure\Repository\RelationRepository;
use App\Infrastructure\Support\ArrayPresenterTrait;
use App\Infrastructure\Support\CarbonWrap;
use App\Infrastructure\Support\GuidBasedImmutableId;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: RelationRepository::class)]
#[ORM\Table(name: '`relations`')]
#[ORM\Index(fields: ["collaboratorId"])]
#[ORM\Index(fields: ["workspaceId"])]
#[ORM\Index(fields: ["establishedAt"])]
#[ORM\HasLifecycleCallbacks]
class Relation
{
    use TimestampableEntity, ArrayPresenterTrait, CarbonWrap;

    private function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'uuid')]
        private UuidInterface $id,

        #[ORM\ManyToOne(targetEntity: "User", inversedBy: "relations")]
        #[ORM\JoinColumn(name: "collaborator_id", referencedColumnName: "id")]
        private User $collaborator,

        #[ORM\ManyToOne(targetEntity: "Workspace", inversedBy: "relations")]
        #[ORM\JoinColumn(name: "workspace_id", referencedColumnName: "id")]
        private Workspace $workspace,

        #[ORM\Column(type: Types::STRING)]
        private string $relationType,

        #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
        private ?DateTime $establishedAt = null,
    ) {
    }

    public static function create(
        GenericIdInterface $relationId,
        User $collaborator,
        Workspace $workspace,
        RelationType $relationType,
    ): static {
        return new self(
            Uuid::fromString((string) $relationId),
            $collaborator,
            $workspace,
            (string) $relationType,
            self::now(),
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

    public function getCollaborator(): User
    {
        return $this->collaborator;
    }

    public function getWorkspace(): Workspace
    {
        return $this->workspace;
    }

    public function getType(): RelationType
    {
        return RelationType::of($this->relationType);
    }
}
