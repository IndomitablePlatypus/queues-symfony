<?php

namespace App\Application\Services;

use App\Application\Contracts\GenericIdInterface;
use App\Domain\Contracts\CollaboratingWorkspaceRepositoryInterface;
use App\Domain\Contracts\PlanRepositoryInterface;
use App\Domain\Contracts\RequirementRepositoryInterface;
use App\Domain\Contracts\WorkspaceRepositoryInterface;
use App\Domain\Dto\PlanProfile;
use App\Domain\Entity\Plan;
use App\Domain\Entity\Requirement;
use App\Domain\Entity\User;
use Carbon\Carbon;

class PlanService
{
    public function __construct(
        protected WorkspaceRepositoryInterface $workspaceRepository,
        protected CollaboratingWorkspaceRepositoryInterface $collaboratingWorkspaceRepository,
        protected PlanRepositoryInterface $planRepository,
        protected RequirementRepositoryInterface $requirementRepository,
    ) {
    }

    public function add(
        User $collaborator,
        GenericIdInterface $workspaceId,
        GenericIdInterface $planId,
        PlanProfile $profile,
    ): Plan {
        return $this->planRepository->persist(
            $this->collaboratingWorkspaceRepository
                ->getCollaboratingWorkspace($collaborator->getId(), $workspaceId)
                ->addPlan($planId, $profile)
        );
    }

    public function changeProfile(
        User $collaborator,
        GenericIdInterface $workspaceId,
        GenericIdInterface $planId,
        PlanProfile $profile,
    ): Plan {
        return $this->planRepository->persist(
            $this->getPlan($collaborator->getId(), $workspaceId, $planId)
                ->setProfile($profile)
        );
    }

    public function launch(
        User $collaborator,
        GenericIdInterface $workspaceId,
        GenericIdInterface $planId,
        Carbon $expirationDate,
    ): Plan {
        return $this->planRepository->persist(
            $this->getPlan($collaborator->getId(), $workspaceId, $planId)
                ->launch($expirationDate)
        );
    }

    public function stop(
        User $collaborator,
        GenericIdInterface $workspaceId,
        GenericIdInterface $planId,
    ): Plan {
        return $this->planRepository->persist(
            $this->getPlan($collaborator->getId(), $workspaceId, $planId)
                ->stop()
        );
    }

    public function archive(
        User $collaborator,
        GenericIdInterface $workspaceId,
        GenericIdInterface $planId,
    ): Plan {
        return $this->planRepository->persist(
            $this->getPlan($collaborator->getId(), $workspaceId, $planId)
                ->archive()
        );
    }

    public function addRequirement(
        User $collaborator,
        GenericIdInterface $workspaceId,
        GenericIdInterface $planId,
        GenericIdInterface $requirementId,
        string $description,
    ): Requirement {
        return $this->requirementRepository->persist(
            $this->getPlan($collaborator->getId(), $workspaceId, $planId)
                ->addRequirement($requirementId, $description)
        );
    }

    public function changeRequirement(
        User $collaborator,
        GenericIdInterface $workspaceId,
        GenericIdInterface $planId,
        GenericIdInterface $requirementId,
        string $description,
    ): Requirement {
        return $this->requirementRepository->persist(
            $this->getPlan($collaborator->getId(), $workspaceId, $planId)
                ->getRequirement($requirementId)
                ->setDescription($description)
        );
    }

    public function removeRequirement(
        User $collaborator,
        GenericIdInterface $workspaceId,
        GenericIdInterface $planId,
        GenericIdInterface $requirementId,
    ): Requirement {
        return $this->requirementRepository->persist(
            $this->getPlan($collaborator->getId(), $workspaceId, $planId)
                ->getRequirement($requirementId)
                ->remove()
        );
    }

    protected function getPlan(
        GenericIdInterface $collaboratorId,
        GenericIdInterface $workspaceId,
        GenericIdInterface $planId,
    ): Plan {
        return $this->collaboratingWorkspaceRepository
            ->getCollaboratingWorkspace($collaboratorId, $workspaceId)
            ->getPlan($planId);
    }

}
