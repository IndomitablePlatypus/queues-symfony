<?php

namespace App\Domain\Contracts;

use App\Domain\Entity\Plan;

interface PlanRepositoryInterface
{
    public function persist(Plan $plan): Plan;
}
