<?php

namespace App\Presentation\Controller\Api\V1\Plan\Output;

use App\Domain\Entity\Plan;
use App\Infrastructure\Support\ArrayPresenterTrait;
use DateTime;
use JsonSerializable;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: "BusinessPlan",
    description: "Business plan",
    required: [
        "planId",
        "workspaceId",
        "name",
        "description",
        "isLaunched",
        "isStopped",
        "isArchived",
        "expirationDate",
        "requirements",
    ],
)]
class BusinessPlan implements JsonSerializable
{
    use ArrayPresenterTrait;

    public function __construct(
        #[Property(description: "Plan Id", format: "uuid", example: '41c8613d-6ae2-41ad-841a-ffd06a116961', nullable: false)]
        public string $planId,

        #[Property(description: "Workspace Id", format: "uuid", example: '41c8613d-6ae2-41ad-841a-ffd06a116961', nullable: false)]
        public string $workspaceId,

        #[Property(description: "Plan name", example: "Americano 1/8", nullable: false)]
        public string $name,

        #[Property(description: "Plan description", example: "Get a free americano for 8 cups of cappuccino", nullable: false)]
        public string $description,

        #[Property(description: "Whether the plan has been launched", example: true, nullable: false)]
        public bool $isLaunched,

        #[Property(description: "Whether the plan has been stopped", example: false, nullable: false)]
        public bool $isStopped,

        #[Property(description: "Whether the plan has been archived", example: false, nullable: false)]
        public bool $isArchived,

        #[Property(description: "Plan expiration date", nullable: true)]
        public ?DateTime $expirationDate,

        #[Property(
            description: "Plan requirements",
            type: "array",
            items: new Items(
                required: ["requirementId", "description"],
                properties: [
                    new Property(property: "requirementId", description: "Requirement id", type: "string", format: "uuid", example: '41c8613d-6ae2-41ad-841a-ffd06a116961', nullable: false),
                    new Property(property: "description", description: "Requirement description", type: "string", example: "Buy a cup of lungo", nullable: false),
                ],
                type: "object",
            ),
            nullable: false,
        )]
        public array $requirements,

    ) {
    }

    public static function of(Plan $plan): static
    {
        return new static(
            (string) $plan->getId(),
            (string) $plan->getWorkspaceId(),
            $plan->getProfile()->name,
            $plan->getProfile()->description,
            $plan->getLaunchedAt() !== null,
            $plan->getStoppedAt() !== null,
            $plan->getArchivedAt() !== null,
            $plan->getExpirationDate()?->toDateTime(),
            $plan->getCompactRequirements()->toArray(),
        );
    }

    public function jsonSerialize(): array
    {
        return $this->_toArray(publicOnly: true);
    }

}
