<?php

namespace App\Presentation\Controller\Api\V1\Workspace\Get;

use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Workspace\Output\BusinessWorkspaces;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/workspace')]
class WorkspaceGetController extends ApiController
{
    #[Route('', name: RouteName::GET_WORKSPACES, methods: ['GET'])]
    public function add(): JsonResponse
    {
        return $this->respond(BusinessWorkspaces::of(
            ...$this->getUser()->getWorkspaces()->toArray()
        ));
    }
}
