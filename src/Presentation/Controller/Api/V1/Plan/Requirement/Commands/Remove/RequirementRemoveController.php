<?php

namespace App\Presentation\Controller\Api\V1\Plan\Requirement\Commands\Remove;

use App\Application\Services\PlanService;
use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Plan\Output\BusinessPlan;
use App\Presentation\Controller\Api\V1\Plan\Requirement\Commands\Change\Input\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[Route('/api/v1/workspace/{workspaceId}/plan/{planId}/requirement/{requirementId}')]
class RequirementRemoveController extends ApiController
{
    #[Route('', name: RouteName::REMOVE_PLAN_REQUIREMENT, methods: ['DELETE'])]
    public function remove(
        Request $request,
        PlanService $planService,
        ConstraintViolationListInterface $validationErrors,
    ): JsonResponse {
        $this->validate($validationErrors);

        return $this->respond(BusinessPlan::of(
            $planService->removeRequirement(
                $this->getUser(),
                $request->getWorkspaceId(),
                $request->getPlanId(),
                $request->getRequirementId(),
            )->getPlan()
        ));
    }
}
