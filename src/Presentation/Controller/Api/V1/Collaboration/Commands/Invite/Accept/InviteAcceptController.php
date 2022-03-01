<?php

namespace App\Presentation\Controller\Api\V1\Collaboration\Commands\Invite\Accept;

use App\Application\Services\CollaborationService;
use App\Config\Routing\RouteName;
use App\Infrastructure\Support\GuidBasedImmutableId;
use App\Presentation\Controller\Api\V1\ApiController;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Tag(name="Business")
 * @OA\Tag(name="Collaboration")
 */
#[Route('/api/v1/workspace/{workspaceId}/collaboration/invite')]
class InviteAcceptController extends ApiController
{
    /**
     * @OA\Response(
     *     response=200,
     *     description="Accepts an invitation to collaborate. Authorizes user to work in the current workspace.",
     *     @OA\MediaType(
     *         mediaType="json",
     *         @OA\Schema(type="string", format="uuid", description="Collaborator Id", nullable=false)
     *     )
     * )
     */
    #[Route('/{inviteId}/accept', name: RouteName::ACCEPT_INVITE, methods: ['PUT'])]
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
