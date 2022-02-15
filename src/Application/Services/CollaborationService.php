<?php

namespace App\Application\Services;

use App\Application\Contracts\GenericIdInterface;
use App\Domain\Contracts\CustomerRepositoryInterface;
use App\Domain\Contracts\InviteRepositoryInterface;
use App\Domain\Contracts\RelationRepositoryInterface;
use App\Domain\Contracts\WorkspaceRepositoryInterface;
use App\Domain\Dto\RelationType;
use App\Domain\Entity\Invite;
use App\Domain\Entity\User;
use App\Domain\Messages\EstablishRelation;
use App\Infrastructure\Exceptions\LogicException;
use Symfony\Component\Messenger\MessageBusInterface;

class CollaborationService
{
    public function __construct(
        protected InviteRepositoryInterface $inviteRepository,
        protected CustomerRepositoryInterface $collaboratorRepository,
        protected WorkspaceRepositoryInterface $workspaceRepository,
        protected RelationRepositoryInterface $relationRepository,
        protected MessageBusInterface $messageBus,
    ) {
    }

    public function proposeInvite(
        User $keeper,
        GenericIdInterface $workspaceId,
    ): Invite {
        return $this->inviteRepository->persist(
            $keeper
                ->getWorkspace($workspaceId)
                ->invite()
        );
    }

    public function acceptInvite(
        User $collaborator,
        GenericIdInterface $inviteId,
    ): User {
        $invite = $this->inviteRepository->take($inviteId);
        $workspace = $this->workspaceRepository->take($invite->getWorkspaceId());

        if ($workspace->getKeeperId()->equals($collaborator->getId())) {
            throw new LogicException("Thou shalt not accept thine invite");
        }

        $this->messageBus->dispatch(
            EstablishRelation::of(
                $collaborator->getId(),
                $invite->getWorkspaceId(),
                RelationType::MEMBER(),
            )
        );

        $this->inviteRepository->delete($inviteId);
        return $collaborator;
    }
}
