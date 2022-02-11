<?php

namespace App\Presentation\Controller\Api\V1\Card\Commands\Revoke;

use App\Application\Services\CardService;
use App\Config\Routing\RouteName;
use App\Infrastructure\Support\GuidBasedImmutableId;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Card\Output\BusinessCard;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/workspace/{workspaceId}/card/{cardId}')]
class CardRevokeController extends ApiController
{
    #[Route('/revoke', name: RouteName::REVOKE_CARD, methods: ['PUT'])]
    public function add(
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
