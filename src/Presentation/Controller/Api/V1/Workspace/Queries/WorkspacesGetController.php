<?php

namespace App\Presentation\Controller\Api\V1\Workspace\Queries;

use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Workspace\Output\BusinessWorkspaces;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/workspace')]
class WorkspacesGetController extends ApiController
{
    #[Route('', name: RouteName::GET_WORKSPACES, methods: ['GET'])]
    public function getWorkspaces(): JsonResponse
    {
        return $this->respond(BusinessWorkspaces::of(
            ...$this->getUser()->getWorkspaces()->toArray()
        ));
    }
}
