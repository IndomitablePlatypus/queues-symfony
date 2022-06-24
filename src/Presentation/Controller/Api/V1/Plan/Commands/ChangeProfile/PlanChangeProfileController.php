<?php

namespace App\Presentation\Controller\Api\V1\Plan\Commands\ChangeProfile;

use App\Application\Services\PlanService;
use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Plan\Commands\ChangeProfile\Input\ChangeProfileRequest;
use App\Presentation\Controller\Api\V1\Plan\Input\PlanProfile;
use App\Presentation\Controller\Api\V1\Plan\Output\BusinessPlan;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[OA\Tag(name: 'Business')]
#[OA\Tag(name: 'Plan')]
#[Route('/api/v1/workspace/{workspaceId}/plan')]
class PlanChangeProfileController extends ApiController
{
    /**
     * Change plan profile
     *
     * Changes plan profile.
     * Requires user to be authorized to work in the current workspace.
     */
    #[OA\PathParameter(name: 'workspaceId', description: 'Workspace GUID', schema: new OA\Schema(type: 'string', format: 'uuid', example: '41c8613d-6ae2-41ad-841a-ffd06a116961'))]
    #[OA\PathParameter(name: 'planId', description: 'Plan GUID', schema: new OA\Schema(type: 'string', format: 'uuid', example: '41c8613d-6ae2-41ad-841a-ffd06a116961'))]
    #[OA\RequestBody(description: 'Change plan profile request', content: new OA\JsonContent(ref: new Model(type: PlanProfile::class)))]
    #[OA\Response(response: 200, description: 'Business plan', content: new OA\JsonContent(ref: new Model(type: BusinessPlan::class)))]
    #[OA\Response(ref: "#/components/responses/AuthenticationException", response: 401)]
    #[OA\Response(ref: "#/components/responses/AuthorizationException", response: 403)]
    #[OA\Response(ref: "#/components/responses/NotFound", response: 404)]
    #[OA\Response(ref: "#/components/responses/ValidationError", response: 422)]
    #[OA\Response(ref: "#/components/responses/UnexpectedException", response: 500)]
    #[Route('/{planId}/profile', name: RouteName::CHANGE_PLAN_PROFILE, methods: ['PUT'], priority: 535)]
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
