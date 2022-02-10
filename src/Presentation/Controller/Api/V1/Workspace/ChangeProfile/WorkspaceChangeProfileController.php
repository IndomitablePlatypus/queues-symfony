<?php

namespace App\Presentation\Controller\Api\V1\Workspace\ChangeProfile;

use App\Application\Services\WorkspaceService;
use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Workspace\ChangeProfile\Input\Request;
use App\Presentation\Controller\Api\V1\Workspace\Output\BusinessWorkspace;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[Route('/api/v1/workspace')]
class WorkspaceChangeProfileController extends ApiController
{
    #[Route('/{workspaceId}/profile', name: RouteName::CHANGE_PROFILE, methods: ['PUT'])]
    public function add(
        Request $request,
        WorkspaceService $workspaceService,
        ConstraintViolationListInterface $validationErrors
    ): JsonResponse {
        $this->validate($validationErrors);

        return $this->respond(BusinessWorkspace::of(
            $workspaceService->changeProfile(
                $this->getUser(),
                $request->getWorkspaceId(),
                $request->getProfile(),
            )
        ));
    }
}