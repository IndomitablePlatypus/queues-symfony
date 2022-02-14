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

        #[ORM\Column(type: 'uuid')]
        private UuidInterface $collaboratorId,

        #[ORM\Column(type: 'uuid')]
        private UuidInterface $workspaceId,

        #[ORM\Column(type: Types::STRING)]
        private string $relationType,

        #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
        private ?DateTime $establishedAt = null,
    ) {
    }

    public static function create(
        GenericIdInterface $relationId,
        GenericIdInterface $collaboratorId,
        GenericIdInterface $workspaceId,
        RelationType $relationType,
    ): static {
        return new self(
            Uuid::fromString((string) $relationId),
            Uuid::fromString((string) $collaboratorId),
            Uuid::fromString((string) $workspaceId),
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

    public function getCollaboratorId(): GenericIdInterface
    {
        return GuidBasedImmutableId::of($this->collaboratorId);
    }

    public function getWorkspaceId(): GenericIdInterface
    {
        return GuidBasedImmutableId::of($this->workspaceId);
    }
}
