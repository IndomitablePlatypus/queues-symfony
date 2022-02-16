<?php

namespace App\Presentation\Controller\Api\V1\Collaboration\Commands\Collaboration\Fire;

use App\Application\Services\CollaborationService;
use App\Config\Routing\RouteName;
use App\Infrastructure\Support\GuidBasedImmutableId;
use App\Presentation\Controller\Api\V1\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/workspace/{workspaceId}/collaboration')]
class CollaboratorFireController extends ApiController
{
    #[Route('/fire/{collaboratorId}', name: RouteName::FIRE_COLLABORATOR, methods: ['POST'])]
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
