<?php

namespace App\Presentation\Controller\Api\V1\Workspace\Output;

use App\Domain\Entity\Workspace;
use JsonSerializable;

class BusinessWorkspace implements JsonSerializable
{
    public function __construct(protected Workspace $workspace)
    {
    }

    public static function of(Workspace $workspace): static
    {
        return new static($workspace);
    }

    public function jsonSerialize(): array
    {
        return [
            'workspaceId' => (string) $this->workspace->getId(),
            'keeperId' => (string) $this->workspace->getKeeperId(),
            'name' => $this->workspace->getProfile()->name,
            'description' => $this->workspace->getProfile()->description,
            'address' => $this->workspace->getProfile()->address,
        ];
    }

}
