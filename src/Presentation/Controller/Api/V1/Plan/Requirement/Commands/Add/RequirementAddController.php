<?php

namespace App\Presentation\Controller\Api\V1\Plan\Requirement\Commands\Add;

use App\Application\Services\PlanService;
use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Plan\Input\PlanProfile;
use App\Presentation\Controller\Api\V1\Plan\Input\RequirementProfile;
use App\Presentation\Controller\Api\V1\Plan\Output\BusinessPlan;
use App\Presentation\Controller\Api\V1\Plan\Requirement\Commands\Add\Input\AddRequirementRequest;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[OA\Tag(name: 'Business')]
#[OA\Tag(name: 'Plan')]
#[Route('/api/v1/workspace/{workspaceId}/plan/{planId}/requirement')]
class RequirementAddController extends ApiController
{
    /**
     * Add plan requirement
     *
     * Adds a new requirement to the plan. Requirement changes are propagated to the relevant cards.
     * Requires user to be authorized to work in the current workspace.
     */
    #[OA\PathParameter(name: 'workspaceId', description: 'Workspace GUID', schema: new OA\Schema(type: 'string', format: 'uuid', example: '41c8613d-6ae2-41ad-841a-ffd06a116961'))]
    #[OA\PathParameter(name: 'planId', description: 'Plan GUID', schema: new OA\Schema(type: 'string', format: 'uuid', example: '41c8613d-6ae2-41ad-841a-ffd06a116961'))]
    #[OA\RequestBody(description: 'Add requirement request', content: new OA\JsonContent(ref: new Model(type: RequirementProfile::class)))]
    #[OA\Response(response: 200, description: 'Business plan', content: new OA\JsonContent(ref: new Model(type: BusinessPlan::class)))]
    #[OA\Response(ref: "#/components/responses/DomainException", response: 400)]
    #[OA\Response(ref: "#/components/responses/AuthenticationException", response: 401)]
    #[OA\Response(ref: "#/components/responses/AuthorizationException", response: 403)]
    #[OA\Response(ref: "#/components/responses/NotFound", response: 404)]
    #[OA\Response(ref: "#/components/responses/ValidationError", response: 422)]
    #[OA\Response(ref: "#/components/responses/UnexpectedException", response: 500)]
    #[Route('', name: RouteName::ADD_PLAN_REQUIREMENT, methods: ['POST'], priority: 515)]
    public function add(
        AddRequirementRequest $request,
        PlanService $planService,
        ConstraintViolationListInterface $validationErrors,
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
