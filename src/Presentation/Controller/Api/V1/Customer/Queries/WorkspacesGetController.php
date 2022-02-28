<?php

namespace App\Presentation\Controller\Api\V1\Customer\Queries;

use App\Config\Routing\RouteName;
use App\Domain\Contracts\WorkspaceRepositoryInterface;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Customer\Output\CustomerWorkspace;
use App\Presentation\Controller\Api\V1\Customer\Output\CustomerWorkspaces;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(name="Customer")
 */
#[Route('/api/v1/customer/workspaces')]
class WorkspacesGetController extends ApiController
{
    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns all workspaces",
     *     @OA\JsonContent(
     *          type="array",
     *          description="List of all workspaces",
     *          @OA\Items(ref=@Model(type=CustomerWorkspace::class))
     *     )
     * )
     */
    #[Route('', name: RouteName::CUSTOMER_WORKSPACES, methods: ['GET'])]
    public function getWorkspaces(WorkspaceRepositoryInterface $workspaceRepository): JsonResponse
    {
        return $this->respond(CustomerWorkspaces::of(...$workspaceRepository->takeAll()));
    }
}
