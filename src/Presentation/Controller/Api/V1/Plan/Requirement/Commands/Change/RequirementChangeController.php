<?php

namespace App\Presentation\Controller\Api\V1\Plan\Requirement\Commands\Change;

use App\Application\Services\PlanService;
use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Plan\Output\BusinessPlan;
use App\Presentation\Controller\Api\V1\Plan\Requirement\Commands\Change\Input\Request;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @OA\Tag(name="Business")
 * @OA\Tag(name="Plan")
 */
#[Route('/api/v1/workspace/{workspaceId}/plan/{planId}/requirement/{requirementId}')]
class RequirementChangeController extends ApiController
{
    /**
     * @OA\Response(
     *     response=200,
     *     description="Changes the requirement description. Description changes are propagated to the relevant cards. Requires user to be authorized to work in the current workspace.",
     *     @Model(type=BusinessPlan::class)
     * )
     */
    #[Route('', name: RouteName::CHANGE_PLAN_REQUIREMENT, methods: ['PUT'])]
    public function change(
        Request $request,
        PlanService $planService,
        ConstraintViolationListInterface $validationErrors,
    ): JsonResponse {
        $this->validate($validationErrors);

        return $this->respond(BusinessPlan::of(
            $planService->changeRequirement(
                $this->getUser(),
                $request->getWorkspaceId(),
                $request->getPlanId(),
                $request->getRequirementId(),
                $request->getDescription(),
            )->getPlan()
        ));
    }
}
