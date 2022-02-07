<?php

namespace App\Presentation\Controller\Api\V1\Workspace\Add;

use App\Application\Services\WorkspaceService;
use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Workspace\Add\Input\Request;
use App\Presentation\Controller\Api\V1\Workspace\Output\BusinessWorkspace;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[Route('/api/v1/workspace')]
class WorkspaceAddController extends ApiController
{
    #[Route('', name: RouteName::ADD_WORKSPACE, methods: ['POST'])]
    public function add(
        Request $request,
        WorkspaceService $workspaceService,
        ConstraintViolationListInterface $validationErrors
    ): JsonResponse {
        $this->validate($validationErrors);

        return $this->respond(BusinessWorkspace::of(
            $workspaceService->add(
                (string) $this->getUser()?->getId(),
                $request->name,
                $request->description,
                $request->address,
            )
        ));
    }
}
