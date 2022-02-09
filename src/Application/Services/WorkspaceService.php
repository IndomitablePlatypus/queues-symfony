<?php

namespace App\Application\Services;

use App\Domain\Contracts\KeeperRepositoryInterface;
use App\Domain\Contracts\WorkspaceRepositoryInterface;
use App\Domain\Dto\WorkspaceProfile;
use App\Domain\Entity\Workspace;
use App\Infrastructure\Support\GuidBasedImmutableId;

class WorkspaceService
{
    public function __construct(
        protected KeeperRepositoryInterface $keeperRepository,
        protected WorkspaceRepositoryInterface $workspaceRepository,
    ) {
    }

    public function add(string $keeperId, string $name, string $description, string $address): Workspace
    {
        $keeper = $this->keeperRepository->take(GuidBasedImmutableId::of($keeperId));
        return $this->workspaceRepository->persist(
            $keeper->addWorkspace(
                GuidBasedImmutableId::make(),
                WorkspaceProfile::of($name, $description, $address),
            )
        );
    }

    public function changeProfile(string $workspaceId, string $name, string $description, string $address): Workspace
    {
        return $this->workspaceRepository->persist(
            $this
                ->workspaceRepository
                ->take(GuidBasedImmutableId::of($workspaceId))
                ->setProfile(WorkspaceProfile::of($name, $description, $address))
        );
    }
}
