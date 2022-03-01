<?php

namespace App\Presentation\Controller\Api\V1\Collaboration\Commands\Invite\Discard;

use App\Application\Services\CollaborationService;
use App\Config\Routing\RouteName;
use App\Infrastructure\Support\GuidBasedImmutableId;
use App\Presentation\Controller\Api\V1\ApiController;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Tag(name="Business")
 * @OA\Tag(name="Collaboration")
 */
#[Route('/api/v1/workspace/{workspaceId}/collaboration/invite')]
class InviteDiscardController extends ApiController
{
    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns id of the new invite to collaborate on the workspace. Requires user to be the owner of the current workspace.",
     *     @OA\MediaType(
     *         mediaType="json",
     *         @OA\Schema(type="string", format="uuid", description="Keeper Id", nullable=false)
     *     )
     * )
     */
    #[Route('/{inviteId}/discard', name: RouteName::DISCARD_INVITE, methods: ['DELETE'])]
    public function discard(
        Request $request,
        CollaborationService $collaborationService,
    ): JsonResponse {
        return $this->respond(
            (string) $collaborationService->discardInvite(
                $this->getUser(),
                GuidBasedImmutableId::of($request->attributes->get('workspaceId')),
            )->getId()
        );
    }
}
