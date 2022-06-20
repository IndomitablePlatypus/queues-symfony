<?php

namespace App\Presentation\Controller\Api\V1\Card\Achievement\Commands\Dismiss;

use App\Application\Services\CardService;
use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Card\Achievement\Commands\Dismiss\Input\DismissAchievementRequest;
use App\Presentation\Controller\Api\V1\Card\Output\BusinessCard;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[Route('/api/v1/workspace/{workspaceId}/card/{cardId}/achievement/{achievementId}')]
class AchievementDismissController extends ApiController
{
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
