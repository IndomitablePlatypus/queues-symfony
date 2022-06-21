<?php

namespace App\Presentation\Controller\Api\V1\Collaboration\Commands\Invite\Accept;

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
#[Route('/api/v1/workspace/{workspaceId}/collaboration/invite')]
class InviteAcceptController extends ApiController
{
    /**
     * Accept invite
     *
     * Accepts an invitation to collaborate. Authorizes user to work in the current workspace.
     */
    #[OA\PathParameter(name: 'workspaceId', description: 'Workspace GUID', schema: new OA\Schema(type: 'string', format: 'uuid', example: '41c8613d-6ae2-41ad-841a-ffd06a116961'),)]
    #[OA\PathParameter(name: 'inviteId', description: 'Invite GUID', schema: new OA\Schema(type: 'string', format: 'uuid', example: '41c8613d-6ae2-41ad-841a-ffd06a116961'),)]
    #[OA\Response(
        response: 200,
        description: 'Invite Id',
        content: new OA\JsonContent(
            description: 'Invite Id',
            type: 'string',
            example: '41c8613d-6ae2-41ad-841a-ffd06a116961',
            nullable: false,
        )
    )]
    #[OA\Response(ref: "#/components/responses/DomainException", response: 400)]
    #[OA\Response(ref: "#/components/responses/AuthenticationException", response: 401)]
    #[OA\Response(ref: "#/components/responses/NotFound", response: 404)]
    #[OA\Response(ref: "#/components/responses/UnexpectedException", response: 500)]
    #[Route('/{inviteId}/accept', name: RouteName::ACCEPT_INVITE, methods: ['PUT'], priority: 315)]
    public function accept(
        Request $request,
        CollaborationService $collaborationService,
    ): JsonResponse {
        return $this->respond(
            (string) $collaborationService->acceptInvite(
                $this->getUser(),
                GuidBasedImmutableId::of($request->attributes->get('inviteId')),
            )->getId()
        );
    }
}
