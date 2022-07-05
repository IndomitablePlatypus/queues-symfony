<?php

namespace App\Presentation\Controller\Api\V1\Workspace\Queries;

use App\Config\Routing\RouteName;
use App\Domain\Contracts\CollaboratingWorkspaceRepositoryInterface;
use App\Infrastructure\Support\GuidBasedImmutableId;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Workspace\Output\BusinessWorkspace;
use App\Presentation\Controller\Api\V1\Workspace\Output\BusinessWorkspaces;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: 'Business')]
#[OA\Tag(name: 'Workspace')]
#[Route('/api/v1/workspace')]
class WorkspacesGetController extends ApiController
{
    /**
     * Get a workspace
     *
     * Returns workspace where the current user is a collaborator.
     * Requires user to be authorized to work in this workspace.
     */
    #[OA\Response(
        response: 200,
        description: 'List all workspaces of the current collaborator',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: BusinessWorkspace::class))
        ),
    )]
    #[OA\Response(ref: "#/components/responses/AuthenticationException", response: 401)]
    #[OA\Response(ref: "#/components/responses/UnexpectedException", response: 500)]
    #[Route('', name: RouteName::GET_WORKSPACES, methods: ['GET'], priority: 1120)]
    public function getWorkspaces(CollaboratingWorkspaceRepositoryInterface $collaboratingWorkspaceRepository): JsonResponse
    {
        return $this->respond(BusinessWorkspaces::of(
            ...$collaboratingWorkspaceRepository
            ->getCollaboratingWorkspaces($this->getUser()->getId())
        ));
    }
}
