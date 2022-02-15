<?php

namespace App\Application\Services;

use App\Application\Contracts\GenericIdInterface;
use App\Domain\Contracts\CustomerRepositoryInterface;
use App\Domain\Contracts\InviteRepositoryInterface;
use App\Domain\Contracts\RelationRepositoryInterface;
use App\Domain\Contracts\WorkspaceRepositoryInterface;
use App\Domain\Entity\Invite;
use App\Domain\Entity\User;

class CollaborationService
{
    public function __construct(
        protected InviteRepositoryInterface $inviteRepository,
        protected CustomerRepositoryInterface $collaboratorRepository,
        protected WorkspaceRepositoryInterface $workspaceRepository,
        protected RelationRepositoryInterface $relationRepository,
    ) {
    }

    public function proposeInvite(
        User $keeper,
        GenericIdInterface $workspaceId,
    ): Invite
    {
        return $this->inviteRepository->persist(
            $keeper
                ->getWorkspace($workspaceId)
                ->invite()
        );
    }
}
