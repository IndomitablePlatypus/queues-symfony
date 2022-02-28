<?php

namespace App\Presentation\Controller\Api\V1\Customer\Output;

use App\Domain\Entity\Workspace;
use JsonSerializable;

class CustomerWorkspaces implements JsonSerializable
{
    /** @var Workspace[] */
    protected array $workspaces;

    public function __construct(array $workspaces)
    {
        $this->workspaces = $workspaces;
    }

    public static function of(Workspace ...$workspaces): static
    {
        return new static($workspaces);
    }

    public function jsonSerialize(): array
    {
        return [
            ...array_map(fn($workspace) => CustomerWorkspace::of($workspace)->jsonSerialize(), $this->workspaces),
        ];
    }
}
