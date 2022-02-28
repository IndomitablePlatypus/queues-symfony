<?php

namespace App\Presentation\Controller\Api\V1\Card\Commands\Block;

use App\Application\Services\CardService;
use App\Config\Routing\RouteName;
use App\Infrastructure\Support\GuidBasedImmutableId;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Card\Output\BusinessCard;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Tag(name="Business")
 * @OA\Tag(name="Card")
 */
#[Route('/api/v1/workspace/{workspaceId}/card/{cardId}')]
class CardBlockController extends ApiController
{
    /**
     * @OA\Response(
     *     response=200,
     *     description="Marks card as blocked, meaning the owner cannot use it temporarily until it's unblocked. Requires user to be authorized to work in the current workspace.",
     *     @Model(type=BusinessCard::class)
     * )
     */
    #[Route('/block', name: RouteName::BLOCK_CARD, methods: ['PUT'])]
    public function block(
        Request $request,
        CardService $cardService,
    ): JsonResponse {
        return $this->respond(BusinessCard::of(
            $cardService->block(
                $this->getUser(),
                GuidBasedImmutableId::of($request->attributes->get('workspaceId')),
                GuidBasedImmutableId::of($request->attributes->get('cardId')),
            )
        ));
    }
}
