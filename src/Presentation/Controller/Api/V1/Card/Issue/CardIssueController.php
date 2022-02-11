<?php

namespace App\Presentation\Controller\Api\V1\Card\Issue;

use App\Application\Services\CardService;
use App\Application\Services\PlanService;
use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Card\Issue\Input\Request;
use App\Presentation\Controller\Api\V1\Card\Output\BusinessCard;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[Route('/api/v1/workspace/{workspaceId}/card')]
class CardIssueController extends ApiController
{
    #[Route('', name: RouteName::ISSUE_CARD, methods: ['POST'])]
    public function add(
        Request $request,
        CardService $cardService,
        ConstraintViolationListInterface $validationErrors
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
