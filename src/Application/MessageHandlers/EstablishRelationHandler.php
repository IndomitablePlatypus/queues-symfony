<?php

namespace App\Application\MessageHandlers;

use App\Domain\Contracts\CustomerRepositoryInterface;
use App\Domain\Contracts\RelationRepositoryInterface;
use App\Domain\Contracts\WorkspaceRepositoryInterface;
use App\Domain\Messages\EstablishRelation;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class EstablishRelationHandler implements MessageHandlerInterface
{
    public function __construct(
        protected CustomerRepositoryInterface $collaboratorRepository,
        protected WorkspaceRepositoryInterface $workspaceRepository,
        protected RelationRepositoryInterface $relationRepository,
    ) {
    }

    public function __invoke(EstablishRelation $message)
    {
        $collaborator = $this->collaboratorRepository->take($message->collaboratorId);
        $workspace = $this->workspaceRepository->take($message->workspaceId);
        $this->relationRepository->establish(
            $workspace->relateTo($collaborator, $message->relationType)
        );
    }
}
