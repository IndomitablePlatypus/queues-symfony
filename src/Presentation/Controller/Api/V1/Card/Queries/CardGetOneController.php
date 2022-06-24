<?php

namespace App\Presentation\Controller\Api\V1\Card\Queries;

use App\Config\Routing\RouteName;
use App\Domain\Contracts\CollaboratingWorkspaceRepositoryInterface;
use App\Infrastructure\Support\GuidBasedImmutableId;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Card\Output\BusinessCard;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: 'Business')]
#[OA\Tag(name: 'Card')]
#[Route('/api/v1/workspace/{workspaceId}/card/{cardId}')]
class CardGetOneController extends ApiController
{
    /**
     * Get card
     *
     * Returns card by card id if it is issued in the current workspace.
     * Requires user to be authorized to work in the current workspace.
     */
    #[OA\PathParameter(name: 'workspaceId', description: 'Workspace GUID', schema: new OA\Schema(type: 'string', format: 'uuid', example: '41c8613d-6ae2-41ad-841a-ffd06a116961'),)]
    #[OA\PathParameter(name: 'cardId', description: 'Card GUID', schema: new OA\Schema(type: 'string', format: 'uuid', example: '41c8613d-6ae2-41ad-841a-ffd06a116961'),)]
    #[OA\Response(response: 200, description: 'Business card', content: new OA\JsonContent( ref: new Model(type: BusinessCard::class)))]
    #[OA\Response(ref: "#/components/responses/AuthenticationException", response: 401)]
    #[OA\Response(ref: "#/components/responses/AuthorizationException", response: 403)]
    #[OA\Response(ref: "#/components/responses/NotFound", response: 404)]
    #[OA\Response(ref: "#/components/responses/UnexpectedException", response: 500)]
    #[Route('', name: RouteName::GET_CARD, methods: ['GET'], priority: 100)]
    public function getCard(
        Request $request,
        CollaboratingWorkspaceRepositoryInterface $collaboratingWorkspaceRepository,
    ): JsonResponse {
        return $this->respond(BusinessCard::of(
            $collaboratingWorkspaceRepository
                ->getCollaboratingWorkspace(
                    $this->getUser()->getId(),
                    GuidBasedImmutableId::of($request->attributes->get('workspaceId')),
                )
                ->getCard(GuidBasedImmutableId::of($request->attributes->get('cardId')))
        ));
    }
}
