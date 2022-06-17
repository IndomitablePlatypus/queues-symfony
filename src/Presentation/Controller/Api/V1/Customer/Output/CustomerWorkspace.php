<?php

namespace App\Presentation\Controller\Api\V1\Customer\Output;

use App\Domain\Entity\Workspace;
use App\Infrastructure\Support\ArrayPresenterTrait;
use JsonSerializable;
use OpenApi\Attributes as OA;

#[OA\Schema(
    required: ['workspaceId', 'name', 'description', 'description']
)]
class CustomerWorkspace implements JsonSerializable
{
    use ArrayPresenterTrait;

    public function __construct(
        #[OA\Property(description: 'Workspace Id', format: 'uuid', nullable: false)]
        public string $workspaceId,

        #[OA\Property(description: 'Workspace (business) name', example: 'Coffee shop', nullable: false)]
        public string $name,

        #[OA\Property(description: 'Workspace (business) description', example: 'The best coffee shop', nullable: false)]
        public string $description,

        #[OA\Property(description: 'Workspace (business) address', example: '40193 Luther Road Suite 303 Goldenchester, PA 40395', nullable: false)]
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
        return $this->_toArray(publicOnly: true, ownOnly: true);
    }
}
