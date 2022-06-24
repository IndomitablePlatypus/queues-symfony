<?php

namespace App\Presentation\Controller\Api\V1\Workspace\Queries;

use App\Config\Routing\RouteName;
use App\Domain\Contracts\CollaboratingWorkspaceRepositoryInterface;
use App\Infrastructure\Support\GuidBasedImmutableId;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Workspace\Output\BusinessWorkspace;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: 'Business')]
#[OA\Tag(name: 'Workspace')]
#[Route('/api/v1/workspace')]
class WorkspaceGetController extends ApiController
{
    /**
     * Get a workspace
     *
     * Returns workspace where the current user is a collaborator.
     * Requires user to be authorized to work in this workspace.
     */
    #[OA\PathParameter(name: 'workspaceId', description: 'Workspace GUID', schema: new OA\Schema(type: 'string', format: 'uuid', example: '41c8613d-6ae2-41ad-841a-ffd06a116961'))]
    #[OA\Response(response: 200, description: 'Business Workspace', content: new OA\JsonContent(ref: new Model(type: BusinessWorkspace::class)))]
    #[OA\Response(ref: "#/components/responses/AuthenticationException", response: 401)]
    #[OA\Response(ref: "#/components/responses/AuthorizationException", response: 403)]
    #[OA\Response(ref: "#/components/responses/NotFound", response: 404)]
    #[OA\Response(ref: "#/components/responses/UnexpectedException", response: 500)]
    #[Route('/{workspaceId}', name: RouteName::GET_WORKSPACE, methods: ['GET'], priority: 1115)]
    public function getWorkspace(
        Request $request,
        CollaboratingWorkspaceRepositoryInterface $collaboratingWorkspaceRepository,
    ): JsonResponse {
        return $this->respond(BusinessWorkspace::of(
            $collaboratingWorkspaceRepository
                ->getCollaboratingWorkspace(
                    $this->getUser()->getId(),
                    GuidBasedImmutableId::of($request->attributes->get('workspaceId')),
                )
        ));
    }
}
