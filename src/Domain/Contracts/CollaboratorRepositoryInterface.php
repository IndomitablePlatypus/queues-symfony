<?php

namespace App\Domain\Contracts;

use App\Application\Contracts\GenericIdInterface;
use App\Domain\Entity\User;

interface CollaboratorRepositoryInterface
{
    public function getCollaborator(GenericIdInterface $collaboratorId): User;
}
