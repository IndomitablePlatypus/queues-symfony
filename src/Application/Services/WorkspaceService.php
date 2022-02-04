<?php

namespace App\Application\Services;

use App\Domain\Contracts\WorkspaceRepositoryInterface;
use App\Domain\Dto\WorkspaceProfile;
use App\Domain\Entity\Workspace;
use App\Infrastructure\Support\GuidBasedImmutableId;

class WorkspaceService
{
    public function __construct(
        protected WorkspaceRepositoryInterface $workspaceRepository,
    ) {
    }

    public function add(string $keeperId, string $name, string $description, string $address): Workspace
    {
        $workspace = Workspace::create(
            GuidBasedImmutableId::make(),
            GuidBasedImmutableId::of($keeperId),
            WorkspaceProfile::of($name, $description, $address),
        );
        return $this->workspaceRepository->persist($workspace);
    }
}
