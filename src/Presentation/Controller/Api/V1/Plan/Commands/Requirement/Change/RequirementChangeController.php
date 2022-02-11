<?php

namespace App\Presentation\Controller\Api\V1\Plan\Commands\Requirement\Change;

use App\Application\Services\PlanService;
use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Plan\Commands\Requirement\Change\Input\Request;
use App\Presentation\Controller\Api\V1\Plan\Output\BusinessPlan;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[Route('/api/v1/workspace/{workspaceId}/plan/{planId}/requirement/{requirementId}')]
class RequirementChangeController extends ApiController
{
    #[Route('', name: RouteName::CHANGE_PLAN_REQUIREMENT, methods: ['PUT'])]
    public function add(
        Request $request,
        PlanService $planService,
        ConstraintViolationListInterface $validationErrors
    ): JsonResponse {
        $this->validate($validationErrors);

        return $this->respond(BusinessPlan::of(
            $planService->changeRequirement(
                $this->getUser(),
                $request->getWorkspaceId(),
                $request->getPlanId(),
                $request->getRequirementId(),
                $request->getDescription(),
            )->getPlan()
        ));
    }
}
