<?php

namespace App\Presentation\Controller\Api\V1\Collaboration\Commands\Invite\Propose;

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
class InviteProposeController extends ApiController
{
    /**
     * @OA\Response(
     *     response=200,
     *     description="Accepts an invitation to collaborate. Authorizes user to work in the current workspace.",
     *     @OA\MediaType(
     *         mediaType="json",
     *         @OA\Schema(type="string", format="uuid", description="Invite Id", nullable=false)
     *     )
     * )
     */
    #[Route('', name: RouteName::PROPOSE_INVITE, methods: ['POST'])]
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
