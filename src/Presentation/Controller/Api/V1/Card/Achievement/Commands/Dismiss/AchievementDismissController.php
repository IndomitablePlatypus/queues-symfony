<?php

namespace App\Presentation\Controller\Api\V1\Card\Achievement\Commands\Dismiss;

use App\Application\Services\CardService;
use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Card\Achievement\Commands\Dismiss\Input\DismissAchievementRequest;
use App\Presentation\Controller\Api\V1\Card\Output\BusinessCard;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[OA\Tag(name: 'Business')]
#[OA\Tag(name: 'Card')]
#[Route('/api/v1/workspace/{workspaceId}/card/{cardId}/achievement/{achievementId}')]
class AchievementDismissController extends ApiController
{
    /**
     * Dismiss achievement from the card
     *
     * Removes achievement and removes satisfaction mark from the card if necessary.
     * Can only be done until the card owner received their bonus.
     * Requires user to be authorized to work in the current workspace.
     */
    #[OA\PathParameter(name: 'workspaceId', description: 'Workspace GUID', schema: new OA\Schema(type: 'string', format: 'uuid', example: '41c8613d-6ae2-41ad-841a-ffd06a116961'),)]
    #[OA\PathParameter(name: 'cardId', description: 'Card GUID', schema: new OA\Schema(type: 'string', format: 'uuid', example: '41c8613d-6ae2-41ad-841a-ffd06a116961'),)]
    #[OA\PathParameter(name: 'achievementId', description: 'Achievement GUID', schema: new OA\Schema(type: 'string', format: 'uuid', example: '41c8613d-6ae2-41ad-841a-ffd06a116961'),)]
    #[OA\Response(response: 200, description: 'Business card', content: new OA\JsonContent( ref: new Model(type: BusinessCard::class)))]
    #[OA\Response(ref: "#/components/responses/AuthenticationException", response: 401)]
    #[OA\Response(ref: "#/components/responses/ValidationError", response: 422)]
    #[OA\Response(ref: "#/components/responses/UnexpectedException", response: 500)]
    #[Route('', name: RouteName::DISMISS_ACHIEVEMENT, methods: ['DELETE'], priority: 70)]
    public function dismiss(
        DismissAchievementRequest $request,
        CardService $cardService,
        ConstraintViolationListInterface $validationErrors,
    ): JsonResponse {
        $this->validate($validationErrors);

        return $this->respond(BusinessCard::of(
            $cardService->dismissAchievement(
                $this->getUser(),
                $request->getWorkspaceId(),
                $request->getCardId(),
                $request->getAchievementId(),
            )
        ));
    }
}
