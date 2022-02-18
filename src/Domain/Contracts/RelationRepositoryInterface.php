<?php

namespace App\Domain\Contracts;

use App\Application\Contracts\GenericIdInterface;
use App\Domain\Dto\RelationType;
use App\Domain\Entity\Relation;

interface RelationRepositoryInterface
{
    public function establish(Relation $relation): Relation;

    public function cease(GenericIdInterface $collaboratorId, GenericIdInterface $workspaceId, RelationType $relationType): void;
}
