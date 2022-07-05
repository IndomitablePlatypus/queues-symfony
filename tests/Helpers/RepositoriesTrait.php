<?php

namespace App\Tests\Helpers;

use App\Domain\Contracts\WorkspaceRepositoryInterface;
use App\Infrastructure\Repository\UserRepository;

trait RepositoriesTrait
{
    protected UserRepository $userRepository;

    protected WorkspaceRepositoryInterface $workspaceRepository;

    public function getUserRepository(): UserRepository
    {
        return $this->userRepository ?? $this->userRepository = $this->container->get(UserRepository::class);
    }

    public function getWorkspaceRepository(): WorkspaceRepositoryInterface
    {
        return $this->workspaceRepository
            ?? $this->workspaceRepository = $this->container->get(WorkspaceRepositoryInterface::class);
    }
}
