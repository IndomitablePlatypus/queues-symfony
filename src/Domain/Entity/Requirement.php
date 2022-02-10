<?php

namespace App\Domain\Entity;

use App\Application\Contracts\GenericIdInterface;
use App\Infrastructure\Repository\RequirementRepository;
use App\Infrastructure\Support\ArrayPresenterTrait;
use App\Infrastructure\Support\CarbonWrap;
use App\Infrastructure\Support\GuidBasedImmutableId;
use Carbon\Carbon;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: RequirementRepository::class)]
#[ORM\Table(name: '`requirements`')]
#[ORM\Index(fields: ["planId"])]
#[ORM\Index(fields: ["addedAt"])]
#[ORM\Index(fields: ["removedAt"])]
#[ORM\HasLifecycleCallbacks]
class Requirement
{
    use TimestampableEntity, ArrayPresenterTrait, CarbonWrap;

    private function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'uuid')]
        private UuidInterface $id,

        #[ORM\ManyToOne(targetEntity: "Plan", inversedBy: "requirements")]
        #[ORM\JoinColumn(name: "plan_id", referencedColumnName: "id")]
        private Plan $plan,

        #[ORM\Column(type: Types::TEXT)]
        private string $description,

        #[ORM\Column(type: Types::DATETIME_MUTABLE)]
        private DateTime $addedAt,

        #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
        private ?DateTime $removedAt = null,
    ) {
    }

    public static function create(
        GenericIdInterface $requirementId,
        Plan $plan,
        string $description,
    ): static {
        return new self(
            Uuid::fromString((string) $requirementId),
            $plan,
            $description,
            self::now(),
        );
    }

    public function getPlan(): Plan
    {
        return $this->plan;
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function remove(): static
    {
        $this->removedAt = Carbon::now();
        return $this;
    }

    public function toCompactArray(): array
    {
        return [
            'requirementId' => (string) $this->getId(),
            'description' => $this->getDescription(),
        ];
    }

}
