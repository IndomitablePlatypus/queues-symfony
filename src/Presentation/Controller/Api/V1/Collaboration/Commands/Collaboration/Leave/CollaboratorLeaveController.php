<?php

namespace App\Presentation\Controller\Api\V1\Collaboration\Commands\Collaboration\Leave;

use App\Application\Services\CollaborationService;
use App\Config\Routing\RouteName;
use App\Infrastructure\Support\GuidBasedImmutableId;
use App\Presentation\Controller\Api\V1\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/workspace/{workspaceId}/collaboration')]
class CollaboratorLeaveController extends ApiController
{
    #[Route('/leave', name: RouteName::LEAVE_RELATION, methods: ['POST'])]
    public function leave(
        Request $request,
        CollaborationService $collaborationService,
    ): JsonResponse {
        return $this->respond(
            (string) $collaborationService->leave(
                $this->getUser(),
                GuidBasedImmutableId::of($request->attributes->get('workspaceId')),
            )
        );
    }
}
