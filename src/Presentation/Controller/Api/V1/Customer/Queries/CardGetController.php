<?php

namespace App\Presentation\Controller\Api\V1\Customer\Queries;

use App\Config\Routing\RouteName;
use App\Infrastructure\Support\GuidBasedImmutableId;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Customer\Output\IssuedCard;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Tag(name="Customer")
 */
#[Route('/api/v1/customer')]
class CardGetController extends ApiController
{
    /**
     * @OA\Response(
     *     response=200,
     *     description="Get card",
     *     @Model(type=IssuedCard::class)
     * )
     */
    #[Route('/card/{cardId}', name: RouteName::CUSTOMER_CARD, methods: ['GET'])]
    public function GetCard(Request $request): JsonResponse
    {
        return $this->respond(IssuedCard::of(
            $this
                ->getUser()
                ->getCard(GuidBasedImmutableId::of($request->attributes->get('cardId')))
        ));
    }
}
