<?php

namespace App\Application\MessageHandlers;

use App\Domain\Contracts\RelationRepositoryInterface;
use App\Domain\Messages\CeaseRelation;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CeaseRelationHandler implements MessageHandlerInterface
{
    public function __construct(protected RelationRepositoryInterface $relationRepository)
    {
    }

    public function __invoke(CeaseRelation $message)
    {
        $this->relationRepository->cease(
            $message->collaboratorId,
            $message->workspaceId,
            $message->relationType,
        );
    }
}
