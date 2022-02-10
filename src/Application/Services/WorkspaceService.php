<?php

namespace App\Application\Services;

use App\Application\Contracts\GenericIdInterface;
use App\Domain\Contracts\KeeperRepositoryInterface;
use App\Domain\Contracts\WorkspaceRepositoryInterface;
use App\Domain\Dto\WorkspaceProfile;
use App\Domain\Entity\User;
use App\Domain\Entity\Workspace;

class WorkspaceService
{
    public function __construct(
        protected KeeperRepositoryInterface $keeperRepository,
        protected WorkspaceRepositoryInterface $workspaceRepository,
    ) {
    }

    public function add(User $keeper, GenericIdInterface $workspaceId, WorkspaceProfile $profile): Workspace
    {
        return $this->workspaceRepository->persist(
            $keeper
                ->addWorkspace($workspaceId, $profile)
        );
    }

    public function changeProfile(User $keeper, GenericIdInterface $workspaceId, WorkspaceProfile $profile): Workspace
    {
        return $this->workspaceRepository->persist(
            $keeper
                ->getWorkspace($workspaceId)
                ->setProfile($profile)
        );
    }
}
