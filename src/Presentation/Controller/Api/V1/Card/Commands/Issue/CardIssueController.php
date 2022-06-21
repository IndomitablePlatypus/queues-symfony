<?php

namespace App\Presentation\Controller\Api\V1\Card\Commands\Issue;

use App\Application\Services\CardService;
use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Card\Commands\Issue\Input\IssueCardRequest;
use App\Presentation\Controller\Api\V1\Card\Commands\Issue\Input\NewCard;
use App\Presentation\Controller\Api\V1\Card\Output\BusinessCard;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[OA\Tag(name: 'Business')]
#[OA\Tag(name: 'Card')]
#[Route('/api/v1/workspace/{workspaceId}/card')]
class CardIssueController extends ApiController
{
    /**
     * Issue card
     *
     * Issues card for a plan to a customer.
     * Requires user to be authorized to work in the current workspace.
     */
    #[OA\PathParameter(name: 'workspaceId', description: 'Workspace GUID', schema: new OA\Schema(type: 'string', format: 'uuid', example: '41c8613d-6ae2-41ad-841a-ffd06a116961'),)]
    #[OA\RequestBody(content: new OA\JsonContent(ref: new Model(type: NewCard::class)))]
    #[OA\Response(response: 200, description: 'Business card', content: new OA\JsonContent(ref: new Model(type: BusinessCard::class)))]
    #[OA\Response(ref: "#/components/responses/DomainException", response: 400)]
    #[OA\Response(ref: "#/components/responses/AuthenticationException", response: 401)]
    #[OA\Response(ref: "#/components/responses/AuthorizationException", response: 403)]
    #[OA\Response(ref: "#/components/responses/NotFound", response: 404)]
    #[OA\Response(ref: "#/components/responses/ValidationError", response: 422)]
    #[OA\Response(ref: "#/components/responses/UnexpectedException", response: 500)]
    #[Route('', name: RouteName::ISSUE_CARD, methods: ['POST'], priority: 110)]
    public function issue(
        IssueCardRequest $request,
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
