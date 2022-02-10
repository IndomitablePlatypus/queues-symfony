<?php

namespace App\Domain\Contracts;

use App\Application\Contracts\GenericIdInterface;
use App\Domain\Entity\Workspace;

interface WorkspaceRepositoryInterface
{
    public function persist(Workspace $workspace): Workspace;

    public function take($keeper, GenericIdInterface $workspaceId): Workspace;

}
