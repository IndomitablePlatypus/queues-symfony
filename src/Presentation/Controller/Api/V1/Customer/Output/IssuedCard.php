<?php

namespace App\Presentation\Controller\Api\V1\Customer\Output;

use App\Domain\Entity\Card;
use JsonSerializable;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: "IssuedCard",
    required: [
        "cardId",
        "workspaceName",
        "workspaceAddress",
        "customerId",
        "description",
        "satisfied",
        "completed",
        "blocked",
        "achievements",
        "requirements",
    ],
)]
class IssuedCard implements JsonSerializable
{
    public function __construct(
        #[Property(description: "Card Id", format: "uuid", example: "56afe1ba-5795-482e-9da7-066d0323b8dd", nullable: false)]
        public string $cardId,

        #[Property(description: "Workspace (business) name", example: "Coffee shop", nullable: false)]
        public string $workspaceName,

        #[Property(description: "Workspace (business) address", example: "Mapping street 17, Longitude county, Liberland", nullable: false)]
        public string $workspaceAddress,

        #[Property(description: "Customer Id", format: "uuid", example: "00095068-01fa-405c-be2e-64d776279f9e", nullable: false)]
        public string $customerId,

        #[Property(description: "Card (plan) description", example: "Get a free americano for 8 cups of cappuccino", nullable: false)]
        public string $description,

        #[Property(description: "Whether all requirements to receive a bonus are satisfied", example: true, nullable: false)]
        public bool $satisfied,

        #[Property(description: "Whether customer has received the bonus for this card", example: false, nullable: false)]
        public bool $completed,

        #[Property(description: "Whether the card has been blocked", example: false, nullable: false)]
        public bool $blocked,

        #[Property(
            description: "Achieved requirements",
            type: "array",
            items: new Items(
                type: "string",
                example: "",
            ),
            example: [],
            nullable: false,
        )]
        public array $achievements,

        #[Property(
            description: "All requirements",
            type: "array",
            items: new Items(
                type: "string",
                example: "",
            ),
            example: [],
            nullable: false,
        )]
        public array $requirements,
    ) {
    }

    public static function of(Card $card): static
    {
        return new static(
            (string) $card->getId(),
            $card->getPlan()->getWorkspace()->getProfile()->name,
            $card->getPlan()->getWorkspace()->getProfile()->description,
            (string) $card->getCustomerId(),
            $card->getDescription(),
            $card->isSatisfied(),
            $card->isCompleted(),
            $card->isBlocked(),
            $card->getAchievements(),
            $card->getRequirements(),
        );
    }

    public function jsonSerialize(): array
    {
        return [
            $this->cardId,
            $this->workspaceName,
            $this->workspaceAddress,
            $this->customerId,
            $this->description,
            $this->satisfied,
            $this->completed,
            $this->blocked,
            $this->achievements,
            $this->requirements,
        ];
    }

}
