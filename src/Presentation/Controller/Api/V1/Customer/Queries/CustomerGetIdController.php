<?php

namespace App\Presentation\Controller\Api\V1\Customer\Queries;

use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: 'Customer')]
#[Route('/api/v1/customer')]
class CustomerGetIdController extends ApiController
{
    /**
     * Get authorized user id
     *
     * Returns id of the authenticated user.
     */
    #[OA\Response(
        response: 200,
        description: 'Customer Id',
        content: new OA\JsonContent(
            description: 'Current customer Id',
            type: 'string',
            example: '41c8613d-6ae2-41ad-841a-ffd06a116961',
            nullable: false,
        )
    )]
    #[OA\Response(ref: "#/components/responses/AuthenticationException", response: 401)]
    #[OA\Response(ref: "#/components/responses/AuthorizationException", response: 403)]
    #[OA\Response(ref: "#/components/responses/UnexpectedException", response: 500)]
    #[Route('/id', name: RouteName::CUSTOMER_ID, methods: ['GET'], priority: 1015)]
    public function getId(): JsonResponse
    {
        return $this->respond((string) $this->getUser()?->getId());
    }
}
