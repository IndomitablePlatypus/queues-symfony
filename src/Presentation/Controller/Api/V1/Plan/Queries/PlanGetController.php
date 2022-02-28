<?php

namespace App\Presentation\Controller\Api\V1\Plan\Queries;

use App\Config\Routing\RouteName;
use App\Domain\Contracts\CollaboratingWorkspaceRepositoryInterface;
use App\Infrastructure\Support\GuidBasedImmutableId;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Plan\Output\BusinessPlan;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Tag(name="Business")
 * @OA\Tag(name="Plan")
 */
#[Route('/api/v1/workspace/{workspaceId}/plan')]
class PlanGetController extends ApiController
{
    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns a plans in the current workspace by id. Requires user to be authorized to work in the current workspace.",
     *     @Model(type=BusinessPlan::class)
     * )
     */
    #[Route('/{planId}', name: RouteName::GET_PLAN, methods: ['GET'])]
    public function getPlan(
        Request $request,
        CollaboratingWorkspaceRepositoryInterface $collaboratingWorkspaceRepository,
    ): JsonResponse {
        return $this->respond(BusinessPlan::of(
            $collaboratingWorkspaceRepository
                ->getCollaboratingWorkspace(
                    $this->getUser()->getId(),
                    GuidBasedImmutableId::of($request->attributes->get('workspaceId')),
                )
                ->getPlan(GuidBasedImmutableId::of($request->attributes->get('planId')))
        ));
    }
}
