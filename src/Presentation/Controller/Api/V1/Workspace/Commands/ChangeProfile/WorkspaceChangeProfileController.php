<?php

namespace App\Presentation\Controller\Api\V1\Workspace\Commands\ChangeProfile;

use App\Application\Services\WorkspaceService;
use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Workspace\Commands\ChangeProfile\Input\ChangeWorkspaceProfileRequest;
use App\Presentation\Controller\Api\V1\Workspace\Input\WorkspaceProfile;
use App\Presentation\Controller\Api\V1\Workspace\Output\BusinessWorkspace;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[OA\Tag(name: 'Business')]
#[OA\Tag(name: 'Workspace')]
#[Route('/api/v1/workspace')]
class WorkspaceChangeProfileController extends ApiController
{
    /**
     * Change workspace description
     *
     * Changes the current workspace description.
     * Requires user to be the owner of the current workspace.
     */
    #[OA\PathParameter(name: 'workspaceId', description: 'Workspace GUID', schema: new OA\Schema(type: 'string', format: 'uuid', example: '41c8613d-6ae2-41ad-841a-ffd06a116961'))]
    #[OA\RequestBody(description: 'Change workspace profile request', content: new OA\JsonContent(ref: new Model(type: WorkspaceProfile::class)))]
    #[OA\Response(response: 200, description: 'Business Workspace', content: new OA\JsonContent(ref: new Model(type: BusinessWorkspace::class)))]
    #[OA\Response(ref: "#/components/responses/AuthenticationException", response: 401)]
    #[OA\Response(ref: "#/components/responses/AuthorizationException", response: 403)]
    #[OA\Response(ref: "#/components/responses/ValidationError", response: 422)]
    #[OA\Response(ref: "#/components/responses/UnexpectedException", response: 500)]
    #[Route('/{workspaceId}/profile', name: RouteName::CHANGE_PROFILE, methods: ['PUT'], priority: 1105)]
    public function changeProfile(
        ChangeWorkspaceProfileRequest $request,
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
