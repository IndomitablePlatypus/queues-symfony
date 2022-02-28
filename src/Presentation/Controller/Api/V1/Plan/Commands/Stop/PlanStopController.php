<?php

namespace App\Presentation\Controller\Api\V1\Plan\Commands\Stop;

use App\Application\Services\PlanService;
use App\Config\Routing\RouteName;
use App\Infrastructure\Support\GuidBasedImmutableId;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Plan\Output\BusinessPlan;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Tag(name="Business")
 * @OA\Tag(name="Plan")
 */
#[Route('/api/v1/workspace/{workspaceId}/plan')]
class PlanStopController extends ApiController
{
    /**
     * @OA\Response(
     *     response=200,
     *     description="Stops a plan from active state. Requires user to be authorized to work in the current workspace.",
     *     @Model(type=BusinessPlan::class)
     * )
     */
    #[Route('/{planId}/stop', name: RouteName::STOP_PLAN, methods: ['PUT'])]
    public function changeProfile(
        Request $request,
        PlanService $planService,
    ): JsonResponse {
        return $this->respond(BusinessPlan::of(
            $planService->stop(
                $this->getUser(),
                GuidBasedImmutableId::of($request->attributes->get('workspaceId')),
                GuidBasedImmutableId::of($request->attributes->get('planId')),
            )
        ));
    }
}
