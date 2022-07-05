<?php

namespace App\Domain\Contracts;

use App\Application\Contracts\GenericIdInterface;
use App\Domain\Entity\Workspace;

interface CollaboratingWorkspaceRepositoryInterface
{
    public function getCollaboratingWorkspace(GenericIdInterface $collaboratorId, GenericIdInterface $workspaceId): Workspace;

    public function getCollaboratingWorkspaces(GenericIdInterface $collaboratorId): array;
}
