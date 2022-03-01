<?php

namespace App\Presentation\Controller\Api\V1\Workspace\Commands\Add;

use App\Application\Services\WorkspaceService;
use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Workspace\Commands\Add\Input\Request;
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
class WorkspaceAddController extends ApiController
{
    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns the newly created workspace where the current user is an owner.",
     *     @Model(type=BusinessWorkspace::class)
     * )
     */
    #[Route('', name: RouteName::ADD_WORKSPACE, methods: ['POST'])]
    public function add(
        Request $request,
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
