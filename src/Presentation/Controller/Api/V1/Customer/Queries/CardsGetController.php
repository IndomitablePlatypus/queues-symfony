<?php

namespace App\Presentation\Controller\Api\V1\Customer\Queries;

use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Customer\Output\IssuedCard;
use App\Presentation\Controller\Api\V1\Customer\Output\IssuedCards;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Tag(name="Customer")
 */
#[Route('/api/v1/customer')]
class CardsGetController extends ApiController
{
    /**
     * @OA\Response(
     *     response=200,
     *     description="Get cards",
     *     @OA\JsonContent(
     *          type="array",
     *          description="All of the customer's issued cards",
     *          @OA\Items(ref=@Model(type=IssuedCard::class))
     *     )
     * )
     */
    #[Route('/card', name: RouteName::CUSTOMER_CARDS, methods: ['GET'])]
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
