<?php

namespace App\Presentation\Controller\Api\V1\Collaboration\Commands\Invite\Propose;

use App\Application\Services\CollaborationService;
use App\Config\Routing\RouteName;
use App\Infrastructure\Support\GuidBasedImmutableId;
use App\Presentation\Controller\Api\V1\ApiController;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: 'Business')]
#[OA\Tag(name: 'Collaboration')]
#[Route('/api/v1/workspace/{workspaceId}/collaboration/invite')]
class InviteProposeController extends ApiController
{
    /**
     * Propose invite
     *
     * Returns id of the new invite to collaborate on the workspace.
     * Requires user to be the owner of the current workspace.
     */
    #[OA\PathParameter(name: 'workspaceId', description: 'Workspace GUID', schema: new OA\Schema(type: 'string', format: 'uuid', example: '41c8613d-6ae2-41ad-841a-ffd06a116961'),)]
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
    #[OA\Response(ref: "#/components/responses/AuthenticationException", response: 401)]
    #[OA\Response(ref: "#/components/responses/AuthorizationException", response: 403)]
    #[OA\Response(ref: "#/components/responses/NotFound", response: 404)]
    #[OA\Response(ref: "#/components/responses/UnexpectedException", response: 500)]
    #[Route('', name: RouteName::PROPOSE_INVITE, methods: ['POST'], priority: 315)]
    public function propose(
        Request $request,
        CollaborationService $collaborationService,
    ): JsonResponse {
        return $this->respond(
            (string) $collaborationService->proposeInvite(
                $this->getUser(),
                GuidBasedImmutableId::of($request->attributes->get('workspaceId')),
            )->getId()
        );
    }
}
