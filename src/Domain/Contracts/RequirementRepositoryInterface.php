<?php

namespace App\Domain\Contracts;

use App\Domain\Entity\Requirement;

interface RequirementRepositoryInterface
{
    public function persist(Requirement $requirement): Requirement;
}
