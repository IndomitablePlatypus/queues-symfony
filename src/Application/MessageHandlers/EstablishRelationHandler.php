<?php

namespace App\Application\MessageHandlers;

use App\Domain\Contracts\RelationRepositoryInterface;
use App\Domain\Entity\Relation;
use App\Domain\Messages\EstablishRelation;
use App\Infrastructure\Support\GuidBasedImmutableId;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class EstablishRelationHandler implements MessageHandlerInterface
{
    public function __construct(protected RelationRepositoryInterface $relationRepository)
    {
    }

    public function __invoke(EstablishRelation $message)
    {
        $this->relationRepository->establish(
            Relation::create(
                GuidBasedImmutableId::make(),
                $message->collaboratorId,
                $message->workspaceId,
                $message->relationType,
            )
        );
    }
}
