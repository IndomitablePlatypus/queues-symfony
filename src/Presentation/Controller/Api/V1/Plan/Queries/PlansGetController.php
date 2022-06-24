<?php

namespace App\Presentation\Controller\Api\V1\Plan\Queries;

use App\Config\Routing\RouteName;
use App\Domain\Contracts\CollaboratingWorkspaceRepositoryInterface;
use App\Infrastructure\Support\GuidBasedImmutableId;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Plan\Output\BusinessPlan;
use App\Presentation\Controller\Api\V1\Plan\Output\BusinessPlans;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: 'Business')]
#[OA\Tag(name: 'Plan')]
#[Route('/api/v1/workspace/{workspaceId}/plan')]
class PlansGetController extends ApiController
{
    /**
     * Get plans
     *
     * Returns all plans in the current workspace.
     * Requires user to be authorized to work in the current workspace.
     */
    #[OA\PathParameter(name: 'workspaceId', description: 'Workspace GUID', schema: new OA\Schema(type: 'string', format: 'uuid', example: '41c8613d-6ae2-41ad-841a-ffd06a116961'))]
    #[OA\Response(
        response: 200,
        description: 'List all plans for the workspace',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: BusinessPlan::class))
        ),
    )]
    #[OA\Response(ref: "#/components/responses/AuthenticationException", response: 401)]
    #[OA\Response(ref: "#/components/responses/AuthorizationException", response: 403)]
    #[OA\Response(ref: "#/components/responses/NotFound", response: 404)]
    #[OA\Response(ref: "#/components/responses/UnexpectedException", response: 500)]
    #[Route('', name: RouteName::GET_PLANS, methods: ['GET'], priority: 550)]
    public function getPlans(
        Request $request,
        CollaboratingWorkspaceRepositoryInterface $collaboratingWorkspaceRepository,
    ): JsonResponse {
        return $this->respond(BusinessPlans::of(
            ...$collaboratingWorkspaceRepository
            ->getCollaboratingWorkspace(
                $this->getUser()->getId(),
                GuidBasedImmutableId::of($request->attributes->get('workspaceId')),
            )
            ->getPlans()
            ->toArray()
        ));
    }
}
