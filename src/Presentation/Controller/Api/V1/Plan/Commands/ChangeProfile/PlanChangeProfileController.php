<?php

namespace App\Presentation\Controller\Api\V1\Plan\Commands\ChangeProfile;

use App\Application\Services\PlanService;
use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Plan\Commands\ChangeProfile\Input\Request;
use App\Presentation\Controller\Api\V1\Plan\Output\BusinessPlan;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[Route('/api/v1/workspace/{workspaceId}/plan')]
class PlanChangeProfileController extends ApiController
{
    #[Route('/{planId}/profile', name: RouteName::CHANGE_PLAN_PROFILE, methods: ['PUT'])]
    public function add(
        Request $request,
        PlanService $planService,
        ConstraintViolationListInterface $validationErrors
    ): JsonResponse {
        $this->validate($validationErrors);

        return $this->respond(BusinessPlan::of(
            $planService->changeProfile(
                $this->getUser(),
                $request->getWorkspaceId(),
                $request->getPlanId(),
                $request->getProfile(),
            )
        ));
    }
}
