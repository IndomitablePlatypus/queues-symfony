<?php

namespace App\Presentation\Controller\Api\V1\Collaboration\Commands\Collaboration\Fire;

use App\Application\Services\CollaborationService;
use App\Config\Routing\RouteName;
use App\Infrastructure\Support\GuidBasedImmutableId;
use App\Presentation\Controller\Api\V1\ApiController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: 'Business')]
#[OA\Tag(name: 'Collaboration')]
#[Route('/api/v1/workspace/{workspaceId}/collaboration')]
class CollaboratorFireController extends ApiController
{
    /**
     * Remove collaborator
     *
     * Fires the collaborator from the team. User with the given id will no longer be able to work in the workspace.
     * Requires the current user to be the owner of the workspace.
     */
    #[OA\PathParameter(name: 'workspaceId', description: 'Workspace GUID', schema: new OA\Schema(type: 'string', format: 'uuid', example: '41c8613d-6ae2-41ad-841a-ffd06a116961'),)]
    #[OA\PathParameter(name: 'collaboratorId', description: 'Collaborator GUID', schema: new OA\Schema(type: 'string', format: 'uuid', example: '41c8613d-6ae2-41ad-841a-ffd06a116961'),)]
    #[OA\Response(
        response: 200,
        description: 'Collaborator Id',
        content: new OA\JsonContent(
            description: 'Collaborator Id',
            type: 'string',
            example: '41c8613d-6ae2-41ad-841a-ffd06a116961',
            nullable: false,
        )
    )]
    #[OA\Response(ref: "#/components/responses/DomainException", response: 400)]
    #[OA\Response(ref: "#/components/responses/AuthenticationException", response: 401)]
    #[OA\Response(ref: "#/components/responses/AuthorizationException", response: 403)]
    #[OA\Response(ref: "#/components/responses/NotFound", response: 404)]
    #[OA\Response(ref: "#/components/responses/UnexpectedException", response: 500)]
    #[Route('/fire/{collaboratorId}', name: RouteName::FIRE_COLLABORATOR, methods: ['POST'], priority: 305)]
    public function fire(
        Request $request,
        CollaborationService $collaborationService,
    ): JsonResponse {
        return $this->respond(
            (string) $collaborationService->fire(
                $this->getUser(),
                GuidBasedImmutableId::of($request->attributes->get('workspaceId')),
                GuidBasedImmutableId::of($request->attributes->get('collaboratorId')),
            )
        );
    }
}
