<?php

namespace App\Presentation\Controller\Api\V1\Customer\Queries;

use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Customer\Output\IssuedCards;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/customer')]
class CardsGetController extends ApiController
{
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
