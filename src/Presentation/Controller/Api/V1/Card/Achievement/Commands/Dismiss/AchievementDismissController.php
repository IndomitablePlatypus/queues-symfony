<?php

namespace App\Presentation\Controller\Api\V1\Card\Achievement\Commands\Dismiss;

use App\Application\Services\CardService;
use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Card\Achievement\Commands\Dismiss\Input\DismissAchievementRequest;
use App\Presentation\Controller\Api\V1\Card\Output\BusinessCard;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @OA\Tag(name="Business")
 * @OA\Tag(name="Card")
 */
#[Route('/api/v1/workspace/{workspaceId}/card/{cardId}/achievement/{achievementId}')]
class AchievementDismissController extends ApiController
{
    /**
     * @OA\Response(
     *     response=200,
     *     description="Removes achievement and removes satisfaction mark from the card if necessary. Can only be done until the card owner received their bonus. Requires user to
     *     be authorized to work in the current workspace.",
     *     @Model(type=BusinessCard::class)
     * )
     */
    #[Route('', name: RouteName::DISMISS_ACHIEVEMENT, methods: ['DELETE'])]
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
