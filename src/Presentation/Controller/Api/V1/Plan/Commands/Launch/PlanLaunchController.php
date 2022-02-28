<?php

namespace App\Presentation\Controller\Api\V1\Plan\Commands\Launch;

use App\Application\Services\PlanService;
use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Plan\Commands\Launch\Input\Request;
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
class PlanLaunchController extends ApiController
{
    /**
     * @OA\Response(
     *     response=200,
     *     description="Launches a plan to activity. Requires an expiration date for aut expiration. Can be relaunched with a new date. Requires user to be authorized to work in the current workspace.",
     *     @Model(type=BusinessPlan::class)
     * )
     */
    #[Route('/{planId}/launch', name: RouteName::LAUNCH_PLAN, methods: ['PUT'])]
    public function changeProfile(
        Request $request,
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
