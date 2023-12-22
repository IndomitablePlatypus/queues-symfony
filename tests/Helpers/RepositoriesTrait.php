<?php

namespace App\Tests\Helpers;

use App\Domain\Contracts\RelationRepositoryInterface;
use App\Domain\Contracts\WorkspaceRepositoryInterface;
use App\Infrastructure\Repository\UserRepository;

trait RepositoriesTrait
{
    protected UserRepository $userRepository;

    protected WorkspaceRepositoryInterface $workspaceRepository;

    protected RelationRepositoryInterface $relationRepository;

    public function getUserRepository(): UserRepository
    {
        return $this->userRepository ?? $this->userRepository = $this->container->get(UserRepository::class);
    }

    public function getWorkspaceRepository(): WorkspaceRepositoryInterface
    {
        return $this->workspaceRepository
            ?? $this->workspaceRepository = $this->container->get(WorkspaceRepositoryInterface::class);
    }

    public function getRelationRepository(): RelationRepositoryInterface
    {
        return $this->relationRepository
            ?? $this->relationRepository = $this->container->get(RelationRepositoryInterface::class);
    }
}
