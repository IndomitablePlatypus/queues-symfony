<?php

namespace App\Application\Services;

use App\Application\Contracts\GenericIdInterface;
use App\Domain\Contracts\WorkspaceRepositoryInterface;
use App\Domain\Dto\RelationType;
use App\Domain\Dto\WorkspaceProfile;
use App\Domain\Entity\User;
use App\Domain\Entity\Workspace;
use App\Domain\Messages\EstablishRelation;
use Symfony\Component\Messenger\MessageBusInterface;

class WorkspaceService
{
    public function __construct(
        protected WorkspaceRepositoryInterface $workspaceRepository,
        protected MessageBusInterface $messageBus,
    ) {
    }

    public function add(User $keeper, GenericIdInterface $workspaceId, WorkspaceProfile $profile): Workspace
    {
        $workspace = $this->workspaceRepository->persist(
            $keeper
                ->addWorkspace($workspaceId, $profile)
        );
        $this->messageBus->dispatch(EstablishRelation::of($keeper->getId(), $workspaceId, RelationType::KEEPER()));
        return $workspace;
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
