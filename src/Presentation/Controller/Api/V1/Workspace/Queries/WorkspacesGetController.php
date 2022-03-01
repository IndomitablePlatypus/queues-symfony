<?php

namespace App\Presentation\Controller\Api\V1\Workspace\Queries;

use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Workspace\Output\BusinessWorkspace;
use App\Presentation\Controller\Api\V1\Workspace\Output\BusinessWorkspaces;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Tag(name="Business")
 * @OA\Tag(name="Workspace")
 */
#[Route('/api/v1/workspace')]
class WorkspacesGetController extends ApiController
{
    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns all workspaces where the current user is a collaborator.",
     *     @OA\JsonContent(
     *          type="array",
     *          description="List all workspaces of the current collaborator",
     *          @OA\Items(ref=@Model(type=BusinessWorkspace::class))
     *     )
     * )
     */
    #[Route('', name: RouteName::GET_WORKSPACES, methods: ['GET'])]
    public function getWorkspaces(): JsonResponse
    {
        return $this->respond(BusinessWorkspaces::of(
            ...$this->getUser()->getWorkspaces()->toArray()
        ));
    }
}
