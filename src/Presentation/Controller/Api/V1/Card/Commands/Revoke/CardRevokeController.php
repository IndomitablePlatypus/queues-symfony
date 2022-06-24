<?php

namespace App\Presentation\Controller\Api\V1\Card\Commands\Revoke;

use App\Application\Services\CardService;
use App\Config\Routing\RouteName;
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
class CardRevokeController extends ApiController
{
    /**
     * Revoke card
     *
     * Marks card as revoked, meaning the owner cannot use and even see it anymore.
     * Requires user to be authorized to work in the current workspace.
     */
    #[OA\PathParameter(name: 'workspaceId', description: 'Workspace GUID', schema: new OA\Schema(type: 'string', format: 'uuid', example: '41c8613d-6ae2-41ad-841a-ffd06a116961'),)]
    #[OA\PathParameter(name: 'cardId', description: 'Card GUID', schema: new OA\Schema(type: 'string', format: 'uuid', example: '41c8613d-6ae2-41ad-841a-ffd06a116961'),)]
    #[OA\Response(response: 200, description: 'Business card', content: new OA\JsonContent( ref: new Model(type: BusinessCard::class)))]
    #[OA\Response(ref: "#/components/responses/DomainException", response: 400)]
    #[OA\Response(ref: "#/components/responses/AuthenticationException", response: 401)]
    #[OA\Response(ref: "#/components/responses/AuthorizationException", response: 403)]
    #[OA\Response(ref: "#/components/responses/NotFound", response: 404)]
    #[OA\Response(ref: "#/components/responses/UnexpectedException", response: 500)]
    #[Route('/revoke', name: RouteName::REVOKE_CARD, methods: ['PUT'], priority: 90)]
    public function revoke(
        Request $request,
        CardService $cardService,
    ): JsonResponse {
        return $this->respond(BusinessCard::of(
            $cardService->revoke(
                $this->getUser(),
                GuidBasedImmutableId::of($request->attributes->get('workspaceId')),
                GuidBasedImmutableId::of($request->attributes->get('cardId')),
            )
        ));
    }
}
