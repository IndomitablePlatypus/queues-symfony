<?php

namespace App\Presentation\Controller\Api\V1\Workspace\Queries;

use App\Config\Routing\RouteName;
use App\Domain\Contracts\CollaboratingWorkspaceRepositoryInterface;
use App\Infrastructure\Support\GuidBasedImmutableId;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Workspace\Output\BusinessWorkspace;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/workspace')]
class WorkspaceGetController extends ApiController
{
    #[Route('/{workspaceId}', name: RouteName::GET_WORKSPACE, methods: ['GET'])]
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
