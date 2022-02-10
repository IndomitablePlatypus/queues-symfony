<?php

namespace App\Application\Services;

use App\Application\Contracts\GenericIdInterface;
use App\Domain\Contracts\PlanRepositoryInterface;
use App\Domain\Contracts\RequirementRepositoryInterface;
use App\Domain\Contracts\WorkspaceRepositoryInterface;
use App\Domain\Dto\PlanProfile;
use App\Domain\Entity\Plan;
use App\Domain\Entity\Requirement;
use App\Domain\Entity\User;

class PlanService
{
    public function __construct(
        protected WorkspaceRepositoryInterface $workspaceRepository,
        protected PlanRepositoryInterface $planRepository,
        protected RequirementRepositoryInterface $requirementRepository,
    ) {
    }

    public function add(User $keeper, GenericIdInterface $workspaceId, GenericIdInterface $planId, PlanProfile $profile): Plan
    {
        return $this->planRepository->persist(
            $keeper
                ->getWorkspace($workspaceId)
                ->addPlan($planId, $profile)
        );
    }

    public function changeProfile(User $keeper, GenericIdInterface $workspaceId, GenericIdInterface $planId, PlanProfile $profile): Plan
    {
        return $this->planRepository->persist(
            $keeper
                ->getWorkspace($workspaceId)
                ->getPlan($planId)
                ->setProfile($profile)
        );
    }

    public function addRequirement(
        User $keeper,
        GenericIdInterface $workspaceId,
        GenericIdInterface $planId,
        GenericIdInterface $requirementId,
        string $description
    ): Requirement {
        return $this->requirementRepository->persist(
            $keeper
                ->getWorkspace($workspaceId)
                ->getPlan($planId)
                ->addRequirement($requirementId, $description)
        );
    }

    public function changeRequirement(
        User $keeper,
        GenericIdInterface $workspaceId,
        GenericIdInterface $planId,
        GenericIdInterface $requirementId,
        string $description
    ): Requirement {
        return $this->requirementRepository->persist(
            $keeper
                ->getWorkspace($workspaceId)
                ->getPlan($planId)
                ->getRequirement($requirementId)
                ->setDescription($description)
        );
    }

    public function removeRequirement(
        User $keeper,
        GenericIdInterface $workspaceId,
        GenericIdInterface $planId,
        GenericIdInterface $requirementId,
    ): Requirement {
        return $this->requirementRepository->persist(
            $keeper
                ->getWorkspace($workspaceId)
                ->getPlan($planId)
                ->getRequirement($requirementId)
                ->remove()
        );
    }

}
