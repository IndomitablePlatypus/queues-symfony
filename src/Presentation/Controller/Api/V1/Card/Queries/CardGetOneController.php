<?php

namespace App\Presentation\Controller\Api\V1\Card\Queries;

use App\Config\Routing\RouteName;
use App\Infrastructure\Support\GuidBasedImmutableId;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Card\Output\BusinessCard;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/workspace/{workspaceId}/card/{cardId}')]
class CardGetOneController extends ApiController
{
    #[Route('', name: RouteName::GET_CARD, methods: ['GET'])]
    public function add(Request $request): JsonResponse
    {
        return $this->respond(BusinessCard::of(
            $this
                ->getUser()
                ->getWorkspace(GuidBasedImmutableId::of($request->attributes->get('workspaceId')))
                ->getCard(GuidBasedImmutableId::of($request->attributes->get('cardId')))
        ));
    }
}
