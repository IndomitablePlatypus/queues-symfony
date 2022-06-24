<?php

namespace App\Presentation\Controller\Api\V1\Workspace\Commands\Add;

use App\Application\Services\WorkspaceService;
use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Plan\Output\BusinessPlan;
use App\Presentation\Controller\Api\V1\Workspace\Commands\Add\Input\AddWorkspaceRequest;
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
class WorkspaceAddController extends ApiController
{
    /**
     * Add a new workspace
     *
     * Returns the newly created workspace where the current user is an owner.
     */
    #[OA\RequestBody(description: 'Add workspace request', content: new OA\JsonContent(ref: new Model(type: WorkspaceProfile::class)))]
    #[OA\Response(response: 200, description: 'Business Workspace', content: new OA\JsonContent(ref: new Model(type: BusinessWorkspace::class)))]
    #[OA\Response(ref: "#/components/responses/AuthenticationException", response: 401)]
    #[OA\Response(ref: "#/components/responses/ValidationError", response: 422)]
    #[OA\Response(ref: "#/components/responses/UnexpectedException", response: 500)]
    #[Route('', name: RouteName::ADD_WORKSPACE, methods: ['POST'], priority: 1110)]
    public function add(
        AddWorkspaceRequest $request,
        WorkspaceService $workspaceService,
        ConstraintViolationListInterface $validationErrors,
    ): JsonResponse {
        $this->validate($validationErrors);

        return $this->respond(BusinessWorkspace::of(
            $workspaceService->add(
                $this->getUser(),
                $request->getWorkspaceId(),
                $request->getProfile(),
            )
        ));
    }
}
