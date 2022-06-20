<?php

namespace App\Presentation\Controller\Api\V1\Card\Commands\Issue;

use App\Application\Services\CardService;
use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Card\Commands\Issue\Input\IsuueCardRequest;
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
#[Route('/api/v1/workspace/{workspaceId}/card')]
class CardIssueController extends ApiController
{
    /**
     * @OA\Response(
     *     response=200,
     *     description="Issues card for a plan to a customer. Requires user to be authorized to work in the current workspace.",
     *     @Model(type=BusinessCard::class)
     * )
     */
    #[Route('', name: RouteName::ISSUE_CARD, methods: ['POST'])]
    public function issue(
        IsuueCardRequest $request,
        CardService $cardService,
        ConstraintViolationListInterface $validationErrors,
    ): JsonResponse {
        $this->validate($validationErrors);

        return $this->respond(BusinessCard::of(
            $cardService->issue(
                $this->getUser(),
                $request->getWorkspaceId(),
                $request->getPlanId(),
                $request->getCardId(),
                $request->getCustomerId(),
            )
        ));
    }
}
