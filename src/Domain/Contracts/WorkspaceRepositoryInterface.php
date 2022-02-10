<?php

namespace App\Domain\Contracts;

use App\Domain\Entity\Workspace;

interface WorkspaceRepositoryInterface
{
    public function persist(Workspace $workspace): Workspace;
}
