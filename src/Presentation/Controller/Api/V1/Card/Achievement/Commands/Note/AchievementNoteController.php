<?php

namespace App\Presentation\Controller\Api\V1\Card\Achievement\Commands\Note;

use App\Application\Services\CardService;
use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Card\Achievement\Commands\Note\Input\NoteAchievementRequest;
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
#[Route('/api/v1/workspace/{workspaceId}/card/{cardId}/achievement')]
class AchievementNoteController extends ApiController
{
    /**
     * @OA\Response(
     *     response=200,
     *     description="Marks one of the Plan requirements as achieved in the customer card. Card will be marked as satisfied shortly after the last requirement is marked. Meaning
     *     the card owner is now legible for the bonus. Requires user to be authorized to work in the current workspace.",
     *     @Model(type=BusinessCard::class)
     * )
     */
    #[Route('', name: RouteName::NOTE_ACHIEVEMENT, methods: ['POST'])]
    public function note(
        NoteAchievementRequest $request,
        CardService $cardService,
        ConstraintViolationListInterface $validationErrors,
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
