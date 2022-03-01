<?php

namespace App\Presentation\Controller\Api\V1\Collaboration\Commands\Collaboration\Fire;

use App\Application\Services\CollaborationService;
use App\Config\Routing\RouteName;
use App\Infrastructure\Support\GuidBasedImmutableId;
use App\Presentation\Controller\Api\V1\ApiController;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Tag(name="Business")
 * @OA\Tag(name="Collaboration")
 */
#[Route('/api/v1/workspace/{workspaceId}/collaboration')]
class CollaboratorFireController extends ApiController
{
    /**
     * @OA\Response(
     *     response=200,
     *     description="Fires the collaborator from the team. User with the given id will no longer be able to work in the workspace. Requires the current user to be the owner of the workspace.",
     *     @OA\MediaType(
     *         mediaType="json",
     *         @OA\Schema(type="string", format="uuid", description="Collaborator Id", nullable=false)
     *     )
     * )
     */
    #[Route('/fire/{collaboratorId}', name: RouteName::FIRE_COLLABORATOR, methods: ['POST'])]
    public function fire(
        Request $request,
        CollaborationService $collaborationService,
    ): JsonResponse {
        return $this->respond(
            (string) $collaborationService->fire(
                $this->getUser(),
                GuidBasedImmutableId::of($request->attributes->get('workspaceId')),
                GuidBasedImmutableId::of($request->attributes->get('collaboratorId')),
            )
        );
    }
}
