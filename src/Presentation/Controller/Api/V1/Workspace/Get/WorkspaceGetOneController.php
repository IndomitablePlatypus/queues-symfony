<?php

namespace App\Presentation\Controller\Api\V1\Workspace\Get;

use App\Config\Routing\RouteName;
use App\Infrastructure\Support\GuidBasedImmutableId;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Workspace\Output\BusinessWorkspace;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/workspace')]
class WorkspaceGetOneController extends ApiController
{
    #[Route('/{workspaceId}', name: RouteName::GET_WORKSPACE, methods: ['GET'])]
    public function add(Request $request): JsonResponse
    {
        return $this->respond(BusinessWorkspace::of(
            $this->getUser()->getWorkspace(
                GuidBasedImmutableId::of($request->attributes->get('workspaceId'))
            ))
        );
    }
}
