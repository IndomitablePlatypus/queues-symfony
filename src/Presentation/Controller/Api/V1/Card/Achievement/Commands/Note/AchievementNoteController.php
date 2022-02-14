<?php

namespace App\Presentation\Controller\Api\V1\Card\Achievement\Commands\Note;

use App\Application\Services\CardService;
use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Card\Achievement\Commands\Note\Input\Request;
use App\Presentation\Controller\Api\V1\Card\Output\BusinessCard;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[Route('/api/v1/workspace/{workspaceId}/card/{cardId}/achievement')]
class AchievementNoteController extends ApiController
{
    #[Route('', name: RouteName::NOTE_ACHIEVEMENT, methods: ['POST'])]
    public function add(
        Request $request,
        CardService $cardService,
        ConstraintViolationListInterface $validationErrors
    ): JsonResponse {
        $this->validate($validationErrors);

        return $this->respond(BusinessCard::of(
            $cardService->noteAchievement(
                $this->getUser(),
                $request->getWorkspaceId(),
                $request->getCardId(),
                $request->getAchievementId(),
                $request->getDescription(),
            )
        ));
    }
}
