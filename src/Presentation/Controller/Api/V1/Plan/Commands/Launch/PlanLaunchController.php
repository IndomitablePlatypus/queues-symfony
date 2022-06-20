<?php

namespace App\Presentation\Controller\Api\V1\Plan\Commands\Launch;

use App\Application\Services\PlanService;
use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Plan\Commands\Launch\Input\LaunchPlanRequest;
use App\Presentation\Controller\Api\V1\Plan\Output\BusinessPlan;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[Route('/api/v1/workspace/{workspaceId}/plan')]
class PlanLaunchController extends ApiController
{
    #[Route('/{planId}/launch', name: RouteName::LAUNCH_PLAN, methods: ['PUT'])]
    public function changeProfile(
        LaunchPlanRequest $request,
        PlanService $planService,
        ConstraintViolationListInterface $validationErrors,
    ): JsonResponse {
        $this->validate($validationErrors);

        return $this->respond(BusinessPlan::of(
            $planService->launch(
                $this->getUser(),
                $request->getWorkspaceId(),
                $request->getPlanId(),
                $request->getExpirationDate(),
            )
        ));
    }
}
