<?php

namespace App\Presentation\Controller\Api\V1\Card\Output;

use App\Domain\Entity\Card;
use App\Infrastructure\Support\ArrayPresenterTrait;
use JsonSerializable;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;

class BusinessCard implements JsonSerializable
{
    use ArrayPresenterTrait;

    public function __construct(
        #[Property(description: "Card Id", format: "uuid", nullable: false)]
        public string $cardId,

        #[Property(description: "Plan Id", format: "uuid", nullable: false)]
        public string $planId,

        #[Property(description: "Customer Id", format: "uuid", nullable: false)]
        public string $customerId,

        #[Property(description: "Whether the card has been issued", example: true, nullable: false)]
        public bool $isIssued,

        #[Property(description: "Whether all requirements to receive a bonus are satisfied", example: true, nullable: false)]
        public bool $isSatisfied,

        #[Property(description: "Whether customer has received the bonus for this card", example: false, nullable: false)]
        public bool $isCompleted,

        #[Property(description: "Whether the card has been revoked", example: false, nullable: false)]
        public bool $isRevoked,

        #[Property(description: "Whether the card has been blocked", example: false, nullable: false)]
        public bool $isBlocked,

        #[Property(
            description: "Achieved requirements",
            type: "array",
            items: new Items(
                required: ["achievementId", "description"],
                properties: [
                    new Property(property: "achievementId", description: "Achievement Id = corresponding requirement id", type: "string", format: "uuid", nullable: false),
                    new Property(property: "description", description: "Requirement description", type: "string", example: "Buy a cup of lungo", nullable: false),
                ],
                type: "object",
            ),
            nullable: false,
        )]
        public array $achievements,

        #[Property(
            description: "All requirements",
            type: "array",
            items: new Items(
                required: ["requirementId", "description"],
                properties: [
                    new Property(property: "requirementId", description: "Requirement id", type: "string", format: "uuid", nullable: false),
                    new Property(property: "description", description: "Requirement description", type: "string", example: "Buy a cup of lungo", nullable: false),
                ],
                type: "object",
            ),
            nullable: false,
        )]
        public array $requirements,
    ) {
    }

    public static function of(Card $card): static
    {
        return new static(
            (string) $card->getId(),
            (string) $card->getPlanId(),
            (string) $card->getCustomerId(),
            $card->isIssued(),
            $card->isSatisfied(),
            $card->isCompleted(),
            $card->isRevoked(),
            $card->isBlocked(),
            $card->getAchievements(),
            $card->getRequirements(),
        );
    }

    public function jsonSerialize(): array
    {
        return $this->_toArray(publicOnly: true);
    }

}
