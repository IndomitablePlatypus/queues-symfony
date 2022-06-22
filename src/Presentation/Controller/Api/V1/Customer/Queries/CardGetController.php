<?php

namespace App\Presentation\Controller\Api\V1\Customer\Queries;

use App\Config\Routing\RouteName;
use App\Infrastructure\Support\GuidBasedImmutableId;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Customer\Output\IssuedCard;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: 'Customer')]
#[Route('/api/v1/customer')]
class CardGetController extends ApiController
{
    /**
     * User card
     *
     * Returns an active card, owned by the current user, by its id.
     */
    #[OA\PathParameter(name: 'cardId', description: 'Card GUID', schema: new OA\Schema(type: 'string', format: 'uuid', example: '41c8613d-6ae2-41ad-841a-ffd06a116961'),)]
    #[OA\Response(response: 200, description: 'Issued card', content: new OA\JsonContent(ref: new Model(type: IssuedCard::class)))]
    #[OA\Response(ref: "#/components/responses/AuthenticationException", response: 401)]
    #[OA\Response(ref: "#/components/responses/NotFound", response: 404)]
    #[OA\Response(ref: "#/components/responses/UnexpectedException", response: 500)]
    #[Route('/card/{cardId}', name: RouteName::CUSTOMER_CARD, methods: ['GET'], priority: 1000)]
    public function GetCard(Request $request): JsonResponse
    {
        return $this->respond(IssuedCard::of(
            $this
                ->getUser()
                ->getCard(GuidBasedImmutableId::of($request->attributes->get('cardId')))
        ));
    }
}
