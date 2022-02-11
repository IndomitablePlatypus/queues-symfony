<?php

namespace App\Presentation\Controller\Api\V1\Plan\Queries;

use App\Config\Routing\RouteName;
use App\Infrastructure\Support\GuidBasedImmutableId;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Plan\Output\BusinessPlans;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/workspace/{workspaceId}/plan')]
class PlanGetController extends ApiController
{
    #[Route('', name: RouteName::GET_PLANS, methods: ['GET'])]
    public function add(Request $request): JsonResponse
    {
        return $this->respond(BusinessPlans::of(
            ...$this
            ->getUser()
            ->getWorkspace(GuidBasedImmutableId::of($request->attributes->get('workspaceId')))
            ->getPlans()
            ->toArray()
        ));
    }
}
