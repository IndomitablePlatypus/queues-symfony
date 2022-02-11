<?php

namespace App\Presentation\Controller\Api\V1\Plan\Commands\Requirement\Add;

use App\Application\Services\PlanService;
use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Plan\Commands\Requirement\Add\Input\Request;
use App\Presentation\Controller\Api\V1\Plan\Output\BusinessPlan;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[Route('/api/v1/workspace/{workspaceId}/plan/{planId}/requirement')]
class RequirementAddController extends ApiController
{
    #[Route('', name: RouteName::ADD_PLAN_REQUIREMENT, methods: ['POST'])]
    public function add(
        Request $request,
        PlanService $planService,
        ConstraintViolationListInterface $validationErrors
    ): JsonResponse {
        $this->validate($validationErrors);

        return $this->respond(BusinessPlan::of(
            $planService->addRequirement(
                $this->getUser(),
                $request->getWorkspaceId(),
                $request->getPlanId(),
                $request->getRequirementId(),
                $request->getDescription(),
            )->getPlan()
        ));
    }
}
