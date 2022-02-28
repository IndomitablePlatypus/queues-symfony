<?php

namespace App\Presentation\Controller\Api\V1\Plan\Queries;

use App\Config\Routing\RouteName;
use App\Domain\Contracts\CollaboratingWorkspaceRepositoryInterface;
use App\Infrastructure\Support\GuidBasedImmutableId;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Plan\Output\BusinessPlan;
use App\Presentation\Controller\Api\V1\Plan\Output\BusinessPlans;
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
class PlansGetController extends ApiController
{
    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns all plans in the current workspace. Requires user to be authorized to work in the current workspace.",
     *     @OA\JsonContent(
     *          type="array",
     *          description="List all plans for the workspace",
     *          @OA\Items(ref=@Model(type=BusinessPlan::class))
     *     )
     * )
     */
    #[Route('', name: RouteName::GET_PLANS, methods: ['GET'])]
    public function getPlans(
        Request $request,
        CollaboratingWorkspaceRepositoryInterface $collaboratingWorkspaceRepository,
    ): JsonResponse {
        return $this->respond(BusinessPlans::of(
            ...$collaboratingWorkspaceRepository
            ->getCollaboratingWorkspace(
                $this->getUser()->getId(),
                GuidBasedImmutableId::of($request->attributes->get('workspaceId')),
            )
            ->getPlans()
            ->toArray()
        ));
    }
}
