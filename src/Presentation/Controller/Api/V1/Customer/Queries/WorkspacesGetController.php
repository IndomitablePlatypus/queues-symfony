<?php

namespace App\Presentation\Controller\Api\V1\Customer\Queries;

use App\Config\Routing\RouteName;
use App\Domain\Contracts\WorkspaceRepositoryInterface;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Customer\Output\CustomerWorkspace;
use App\Presentation\Controller\Api\V1\Customer\Output\CustomerWorkspaces;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: 'Customer')]
#[Route('/api/v1/customer/workspaces')]
class WorkspacesGetController extends ApiController
{
    /**
     * Workspaces
     *
     * Returns all workspaces
     */
    #[OA\Response(
        response: 200,
        description: 'List of all workspaces',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: CustomerWorkspace::class))
        )
    )]
    #[OA\Response(
        ref: "#/components/responses/UnexpectedException",
        response: 500,
    )]
    #[Route('', name: RouteName::CUSTOMER_WORKSPACES, methods: ['GET'])]
    public function getWorkspaces(WorkspaceRepositoryInterface $workspaceRepository): JsonResponse
    {
        return $this->respond(CustomerWorkspaces::of(...$workspaceRepository->takeAll()));
    }
}
