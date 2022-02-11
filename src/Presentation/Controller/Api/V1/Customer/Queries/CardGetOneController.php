<?php

namespace App\Presentation\Controller\Api\V1\Customer\Queries;

use App\Config\Routing\RouteName;
use App\Infrastructure\Support\GuidBasedImmutableId;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Customer\Output\IssuedCard;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/customer')]
class CardGetOneController extends ApiController
{
    #[Route('/card/{cardId}', name: RouteName::GET_CARD, methods: ['GET'])]
    public function add(Request $request): JsonResponse
    {
        return $this->respond(IssuedCard::of(
            $this
                ->getUser()
                ->getCard(GuidBasedImmutableId::of($request->attributes->get('cardId')))
        ));
    }
}
