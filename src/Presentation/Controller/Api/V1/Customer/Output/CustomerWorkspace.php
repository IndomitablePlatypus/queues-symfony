<?php

namespace App\Presentation\Controller\Api\V1\Customer\Output;

use App\Domain\Entity\Workspace;
use App\Infrastructure\Support\ArrayPresenterTrait;
use JsonSerializable;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: "CustomerWorkspace",
    description: "Customer Workspace",
    required: [
        "workspaceId",
        "name",
        "description",
        "address",
    ],
)] class CustomerWorkspace implements JsonSerializable
{
    use ArrayPresenterTrait;

    public function __construct(
        #[Property(description: "Workspace Id", format: "uuid", nullable: false)]
        public string $cardId,

        #[Property(description: "Workspace (business) name", example: "Café!", nullable: false)]
        public string $name,

        #[Property(description: "Workspace (business) description", example: "The greatest Café of all times", nullable: false)]
        public string $description,

        #[Property(description: "Workspace (business) address", example: "Mapping street 17, Longitude county, Liberland", nullable: false)]
        public string $address,
    ) {
    }

    public static function of(Workspace $workspace): static
    {
        return new static(
            (string) $workspace->getId(),
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
