<?php

namespace App\Application\Services;

use App\Domain\Contracts\PlanRepositoryInterface;
use App\Domain\Contracts\WorkspaceRepositoryInterface;
use App\Domain\Dto\PlanProfile;
use App\Domain\Dto\WorkspaceProfile;
use App\Domain\Entity\Plan;
use App\Infrastructure\Support\GuidBasedImmutableId;

class PlanService
{
    public function __construct(
        protected WorkspaceRepositoryInterface $workspaceRepository,
        protected PlanRepositoryInterface $planRepository,
    ) {
    }

    public function add(string $workspaceId, string $name, string $description): Plan
    {
        $workspace = $this->workspaceRepository->take(GuidBasedImmutableId::of($workspaceId));
        return $this->planRepository->persist(
            $workspace
                ->addPlan(
                    GuidBasedImmutableId::make(),
                    PlanProfile::of($name, $description),
                )
        );
    }

    public function changeProfile(string $workspaceId, string $planId, string $name, string $description): Plan
    {

    }
}
