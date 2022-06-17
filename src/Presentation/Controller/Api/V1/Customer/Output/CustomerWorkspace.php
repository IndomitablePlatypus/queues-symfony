<?php

namespace App\Presentation\Controller\Api\V1\Customer\Output;

use App\Domain\Entity\Workspace;
use App\Infrastructure\Support\ArrayPresenterTrait;
use JsonSerializable;
use OpenApi\Attributes as OA;

class CustomerWorkspace implements JsonSerializable
{
    use ArrayPresenterTrait;

    public function __construct(
        #[OA\Property(description: 'Workspace Id', format: 'uuid', nullable: false)]
        public string $workspaceId,
        public string $name,
        public string $description,
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
