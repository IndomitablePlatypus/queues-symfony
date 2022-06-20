<?php

namespace App\Presentation\Controller\Api\V1\Plan\Commands\ChangeProfile;

use App\Application\Services\PlanService;
use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Plan\Commands\ChangeProfile\Input\ChangeProfileRequest;
use App\Presentation\Controller\Api\V1\Plan\Output\BusinessPlan;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @OA\Tag(name="Business")
 * @OA\Tag(name="Plan")
 */
#[Route('/api/v1/workspace/{workspaceId}/plan')]
class PlanChangeProfileController extends ApiController
{
    /**
     * Change plan profile
     *
     * @OA\Response(
     *     response=200,
     *     description="Changes plan profile. Requires user to be authorized to work in the current workspace.",
     *     @Model(type=BusinessPlan::class)
     * )
     */
    #[Route('/{planId}/profile', name: RouteName::CHANGE_PLAN_PROFILE, methods: ['PUT'])]
    public function changeProfile(
        ChangeProfileRequest $request,
        PlanService $planService,
        ConstraintViolationListInterface $validationErrors,
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
