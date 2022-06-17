<?php

namespace App\Presentation\Controller\Api\V1\Customer\Output;

use App\Domain\Entity\Workspace;
use JsonSerializable;

class CustomerWorkspaces implements JsonSerializable
{
    /** @var CustomerWorkspaces[] */
    protected array $customerWorkspaces;

    public function __construct(CustomerWorkspace ...$customerWorkspaces)
    {
        $this->customerWorkspaces = $customerWorkspaces;
    }

    public static function of(Workspace ...$workspaces): static
    {
        return new static(
            ...array_map(fn($workspace) => CustomerWorkspace::of($workspace), $workspaces),
        );
    }

    public function jsonSerialize(): array
    {
        return [
            ...array_map(fn($workspace) => $workspace->jsonSerialize(), $this->customerWorkspaces),
        ];
    }
}
