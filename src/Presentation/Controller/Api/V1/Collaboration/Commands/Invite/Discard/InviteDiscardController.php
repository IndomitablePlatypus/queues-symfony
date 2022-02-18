<?php

namespace App\Presentation\Controller\Api\V1\Collaboration\Commands\Invite\Discard;

use App\Application\Services\CollaborationService;
use App\Config\Routing\RouteName;
use App\Infrastructure\Support\GuidBasedImmutableId;
use App\Presentation\Controller\Api\V1\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/workspace/{workspaceId}/collaboration/invite')]
class InviteDiscardController extends ApiController
{
    #[Route('/{inviteId}/accept', name: RouteName::DISCARD_INVITE, methods: ['DELETE'])]
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
