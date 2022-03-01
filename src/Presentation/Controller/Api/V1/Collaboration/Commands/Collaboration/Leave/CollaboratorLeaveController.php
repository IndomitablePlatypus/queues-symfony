<?php

namespace App\Presentation\Controller\Api\V1\Collaboration\Commands\Collaboration\Leave;

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
class CollaboratorLeaveController extends ApiController
{
    /**
     * @OA\Response(
     *     response=200,
     *     description="Rescinds the user ability collaborate in the current workspace. Requires user to be authorized to work in the current workspace. Requires user to NOT be
     *     the owner of it.",
     *     @OA\MediaType(
     *         mediaType="json",
     *         @OA\Schema(type="string", format="uuid", description="Collaborator Id", nullable=false)
     *     )
     * )
     */
    #[Route('/leave', name: RouteName::LEAVE_RELATION, methods: ['POST'])]
    public function leave(
        Request $request,
        CollaborationService $collaborationService,
    ): JsonResponse {
        return $this->respond(
            (string) $collaborationService->leave(
                $this->getUser(),
                GuidBasedImmutableId::of($request->attributes->get('workspaceId')),
            )
        );
    }
}
