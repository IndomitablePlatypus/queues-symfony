<?php

namespace App\Presentation\Controller\Api\V1\Plan\Commands\Stop;

use App\Application\Services\PlanService;
use App\Config\Routing\RouteName;
use App\Infrastructure\Support\GuidBasedImmutableId;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Plan\Output\BusinessPlan;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: 'Business')]
#[OA\Tag(name: 'Plan')]
#[Route('/api/v1/workspace/{workspaceId}/plan')]
class PlanStopController extends ApiController
{
    /**
     * Stop a plan
     *
     * Stops a plan from active state.
     * Requires user to be authorized to work in the current workspace.
     */
    #[OA\PathParameter(name: 'workspaceId', description: 'Workspace GUID', schema: new OA\Schema(type: 'string', format: 'uuid', example: '41c8613d-6ae2-41ad-841a-ffd06a116961'))]
    #[OA\PathParameter(name: 'planId', description: 'Plan GUID', schema: new OA\Schema(type: 'string', format: 'uuid', example: '41c8613d-6ae2-41ad-841a-ffd06a116961'))]
    #[OA\Response(response: 200, description: 'Business plan', content: new OA\JsonContent(ref: new Model(type: BusinessPlan::class)))]
    #[OA\Response(ref: "#/components/responses/AuthenticationException", response: 401)]
    #[OA\Response(ref: "#/components/responses/AuthorizationException", response: 403)]
    #[OA\Response(ref: "#/components/responses/NotFound", response: 404)]
    #[OA\Response(ref: "#/components/responses/UnexpectedException", response: 500)]
    #[Route('/{planId}/stop', name: RouteName::STOP_PLAN, methods: ['PUT'], priority: 525)]
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
