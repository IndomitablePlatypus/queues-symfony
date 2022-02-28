<?php

namespace App\Presentation\Controller\Api\V1\Card\Queries;

use App\Config\Routing\RouteName;
use App\Domain\Contracts\CollaboratingWorkspaceRepositoryInterface;
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
class CardGetOneController extends ApiController
{
    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns card by card id if it is issued in the current workspace. Requires user to be authorized to work in the current workspace.",
     *     @Model(type=BusinessCard::class)
     * )
     */
    #[Route('', name: RouteName::GET_CARD, methods: ['GET'])]
    public function getCard(
        Request $request,
        CollaboratingWorkspaceRepositoryInterface $collaboratingWorkspaceRepository,
    ): JsonResponse {
        return $this->respond(BusinessCard::of(
            $collaboratingWorkspaceRepository
                ->getCollaboratingWorkspace(
                    $this->getUser()->getId(),
                    GuidBasedImmutableId::of($request->attributes->get('workspaceId')),
                )
                ->getCard(GuidBasedImmutableId::of($request->attributes->get('cardId')))
        ));
    }
}
