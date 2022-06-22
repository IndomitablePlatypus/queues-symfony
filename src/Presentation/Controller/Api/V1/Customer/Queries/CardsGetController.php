<?php

namespace App\Presentation\Controller\Api\V1\Customer\Queries;

use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Customer\Output\IssuedCard;
use App\Presentation\Controller\Api\V1\Customer\Output\IssuedCards;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: 'Customer')]
#[Route('/api/v1/customer')]
class CardsGetController extends ApiController
{
    /**
     * User cards
     *
     * Returns all active cards for the current user.
     */
    #[OA\Response(
        response: 200,
        description: 'List of all workspaces',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: IssuedCard::class))
        )
    )]
    #[OA\Response(ref: "#/components/responses/AuthenticationException", response: 401)]
    #[OA\Response(ref: "#/components/responses/UnexpectedException", response: 500)]
    #[Route('/card', name: RouteName::CUSTOMER_CARDS, methods: ['GET'], priority: 1005)]
    public function getCards(): JsonResponse
    {
        return $this->respond(IssuedCards::of(
            ...$this
            ->getUser()
            ->getCards()
            ->toArray()
        ));
    }
}
