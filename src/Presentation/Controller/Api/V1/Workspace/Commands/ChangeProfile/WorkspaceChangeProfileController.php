<?php

namespace App\Presentation\Controller\Api\V1\Workspace\Commands\ChangeProfile;

use App\Application\Services\WorkspaceService;
use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Workspace\Commands\ChangeProfile\Input\Request;
use App\Presentation\Controller\Api\V1\Workspace\Output\BusinessWorkspace;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @OA\Tag(name="Business")
 * @OA\Tag(name="Workspace")
 */
#[Route('/api/v1/workspace')]
class WorkspaceChangeProfileController extends ApiController
{
    /**
     * @OA\Response(
     *     response=200,
     *     description="Changes the current workspace description. Requires user to be the owner of the current workspace..",
     *     @Model(type=BusinessWorkspace::class)
     * )
     */
    #[Route('/{workspaceId}/profile', name: RouteName::CHANGE_PROFILE, methods: ['PUT'])]
    public function changeProfile(
        Request $request,
        WorkspaceService $workspaceService,
        ConstraintViolationListInterface $validationErrors,
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
