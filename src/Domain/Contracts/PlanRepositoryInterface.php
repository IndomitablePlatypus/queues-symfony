<?php

namespace App\Domain\Contracts;

use App\Application\Contracts\GenericIdInterface;
use App\Domain\Entity\Plan;

interface PlanRepositoryInterface
{
    public function persist(Plan $plan): Plan;

    public function take(GenericIdInterface $planId): Plan;

}
