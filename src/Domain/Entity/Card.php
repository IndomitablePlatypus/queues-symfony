<?php

namespace App\Domain\Entity;

use App\Application\Contracts\GenericIdInterface;
use App\Infrastructure\Exceptions\LogicException;
use App\Infrastructure\Repository\CardRepository;
use App\Infrastructure\Support\ArrayPresenterTrait;
use App\Infrastructure\Support\CarbonWrap;
use App\Infrastructure\Support\GuidBasedImmutableId;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: CardRepository::class)]
#[ORM\Table(name: '`cards`')]
#[ORM\Index(fields: ["planId"])]
#[ORM\Index(fields: ["customerId"])]
#[ORM\Index(fields: ["issuedAt"])]
#[ORM\Index(fields: ["satisfiedAt"])]
#[ORM\Index(fields: ["completedAt"])]
#[ORM\Index(fields: ["revokedAt"])]
#[ORM\Index(fields: ["blockedAt"])]
#[ORM\HasLifecycleCallbacks]
class Card
{
    use TimestampableEntity, ArrayPresenterTrait, CarbonWrap;

    private function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'uuid')]
        private UuidInterface $id,

        #[ORM\ManyToOne(targetEntity: "Plan", inversedBy: "cards")]
        #[ORM\JoinColumn(name: "plan_id", referencedColumnName: "id")]
        private Plan $plan,

        #[ORM\ManyToOne(targetEntity: "User", inversedBy: "cards")]
        #[ORM\JoinColumn(name: "customer_id", referencedColumnName: "id")]
        private User $customer,

        #[ORM\Column(type: Types::TEXT)]
        private string $description,

        #[ORM\Column(type: Types::DATETIME_MUTABLE)]
        private DateTime $issuedAt,

        #[ORM\Column(type: Types::JSON, options: ["jsonb" => true])]
        private array $achievements,

        #[ORM\Column(type: Types::JSON, options: ["jsonb" => true])]
        private array $requirements,

        #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
        private ?DateTime $satisfiedAt = null,

        #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
        private ?DateTime $completedAt = null,

        #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
        private ?DateTime $revokedAt = null,

        #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
        private ?DateTime $blockedAt = null,
    ) {
    }

    public static function create(
        GenericIdInterface $cardId,
        Plan $plan,
        User $customer,
    ): static {
        return (new self(
            Uuid::fromString((string) $cardId),
            $plan,
            $customer,
            $plan->getProfile()->description,
            self::now(),
            [],
            [],
        ))->acceptRequirements($plan->getCompactRequirements()->toArray());
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

    public function getPlanId(): GenericIdInterface
    {
        return $this->plan->getId();
    }

    public function getPlan(): Plan
    {
        return $this->plan;
    }

    public function getCustomerId(): GenericIdInterface
    {
        return $this->customer->getId();
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getAchievements(): array
    {
        return $this->achievements;
    }

    public function getRequirements(): array
    {
        return $this->requirements;
    }

    public function isIssued(): bool
    {
        return $this->issuedAt !== null;
    }

    public function isSatisfied(): bool
    {
        return $this->satisfiedAt !== null;
    }

    public function isCompleted(): bool
    {
        return $this->completedAt !== null;
    }

    public function isRevoked(): bool
    {
        return $this->revokedAt !== null;
    }

    public function isBlocked(): bool
    {
        return $this->blockedAt !== null;
    }

    public function complete(): self
    {
        if ($this->isCompleted() || $this->isRevoked() || $this->isBlocked()) {
            throw new LogicException('Invalid card state');
        }

        $this->completedAt = self::now();
        return $this;
    }

    public function revoke(): self
    {
        if ($this->isRevoked() || $this->isCompleted()) {
            throw new LogicException('Invalid card state');
        }

        $this->revokedAt = self::now();
        return $this;
    }

    public function block(): self
    {
        if ($this->isBlocked() || $this->isCompleted() || $this->isRevoked()) {
            throw new LogicException('Invalid card state');
        }

        $this->blockedAt = self::now();
        return $this;
    }

    public function unblock(): self
    {
        if (!$this->isBlocked() || $this->isRevoked()) {
            throw new LogicException('Invalid card state');
        }

        $this->blockedAt = null;
        return $this;
    }

    public function noteAchievement(string $id, string $description): self
    {
        if ($this->isSatisfied() || $this->isCompleted() || $this->isBlocked() || $this->isRevoked()) {
            throw new LogicException('Invalid card state');
        }
        $achievements = $this->achievements ?? [];
        $achievements[] = ['achievementId' => $id, 'description' => $description];
        $this->achievements = $achievements;
        return $this->tryToSatisfy();
    }

    public function dismissAchievement(string $id): self
    {
        if ($this->isCompleted() || $this->isBlocked() || $this->isRevoked()) {
            throw new LogicException('Invalid card state');
        }
        $achievements = $this->achievements ?? [];
        foreach ($achievements as $key => $achievement) {
            if ($achievement['achievementId'] === $id) {
                unset($achievements[$key]);
            }
        }
        $this->achievements = $achievements;
        return $this->tryToWithdrawSatisfaction();
    }

    public function fixRequirementDescription(string $id, string $description): self
    {
        $achievements = $this->achievements ?? [];
        foreach ($achievements as $key => $achievement) {
            if ($achievement['achievementId'] === $id) {
                $achievements[$key]['description'] = $description;
            }
        }
        $this->achievements = $achievements;

        $requirements = $this->requirements ?? [];
        foreach ($requirements as $key => $requirement) {
            if ($requirement['requirementId'] === $id) {
                $requirements[$key]['description'] = $description;
            }
        }
        $this->requirements = $requirements;
        return $this;
    }

    public function acceptRequirements(array $requirements): self
    {
        $this->requirements = $requirements;
        return $this->tryToSatisfy();
    }

    private function tryToSatisfy(): static
    {
        $requirements = $this->requirements;
        foreach ($this->achievements as $achievement) {
            foreach ($requirements as $key => $requirement) {
                if ($requirement['requirementId'] === $achievement['achievementId']) {
                    unset($requirements[$key]);
                }
            }
        }
        if (empty($requirements)) {
            $this->satisfiedAt = self::now();
        }
        return $this;
    }

    private function tryToWithdrawSatisfaction(): self
    {
        if (!$this->isSatisfied()) {
            return $this;
        }

        $requirements = $this->requirements;
        foreach ($this->achievements as $achievement) {
            foreach ($requirements as $key => $requirement) {
                if ($requirement['requirementId'] === $achievement['achievementId']) {
                    unset($requirements[$key]);
                }
            }
        }
        if (!empty($requirements)) {
            $this->satisfiedAt = null;
        }

        return $this;
    }
}
