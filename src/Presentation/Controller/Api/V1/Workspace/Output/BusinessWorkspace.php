<?php

namespace App\Presentation\Controller\Api\V1\Workspace\Output;

use App\Domain\Entity\Workspace;
use App\Infrastructure\Support\ArrayPresenterTrait;
use JsonSerializable;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: "BusinessWorkspace",
    description: "Business workspace",
    required: [
        "workspaceId",
        "keeperId",
        "name",
        "description",
        "address",
    ],
)]
class BusinessWorkspace implements JsonSerializable
{
    use ArrayPresenterTrait;

    public function __construct(
        #[Property(description: "Workspace Id", format: "uuid", nullable: false)]
        public string $workspaceId,

        #[Property(description: "Keeper Id", format: "uuid", nullable: false)]
        public string $keeperId,

        #[Property(description: "Workspace (business) name", example: "Coffee shop", nullable: false)]
        public string $name,

        #[Property(description: "Workspace (business) description", example: "The best coffee shop out there", nullable: false)]
        public string $description,

        #[Property(description: "Workspace (business) address", example: "Mapping street 17, Longitude county, Liberland", nullable: false)]
        public string $address,
    ) {
    }

    public static function of(Workspace $workspace): static
    {
        return new static(
            (string) $workspace->getId(),
            (string) $workspace->getKeeperId(),
            $workspace->getProfile()->name,
            $workspace->getProfile()->description,
            $workspace->getProfile()->address,
        );
    }

    public function jsonSerialize(): array
    {
        return $this->_toArray(publicOnly: true);
    }

}
