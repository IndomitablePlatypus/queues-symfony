<?php

namespace App\Presentation\Controller\Api\V1\Plan\Queries;

use App\Config\Routing\RouteName;
use App\Domain\Contracts\CollaboratingWorkspaceRepositoryInterface;
use App\Infrastructure\Support\GuidBasedImmutableId;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Plan\Output\BusinessPlan;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/workspace/{workspaceId}/plan')]
class PlanGetOneController extends ApiController
{
    #[Route('/{planId}', name: RouteName::GET_PLAN, methods: ['GET'])]
    public function add(Request $request, CollaboratingWorkspaceRepositoryInterface $collaboratingWorkspaceRepository): JsonResponse
    {
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
