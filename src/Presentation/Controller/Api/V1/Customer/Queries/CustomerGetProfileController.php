<?php

namespace App\Presentation\Controller\Api\V1\Customer\Queries;

use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Customer\Output\CustomerProfile;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: 'Customer')]
#[Route('/api/v1/customer')]
class CustomerGetProfileController extends ApiController
{
    /**
     * Get authorized user profile
     *
     * Returns profile.
     */
    #[OA\Response(response: 200, description: 'Customer Profile', content: new OA\JsonContent(ref: new Model(type: CustomerProfile::class)))]
    #[OA\Response(ref: "#/components/responses/AuthenticationException", response: 401)]
    #[OA\Response(ref: "#/components/responses/AuthorizationException", response: 403)]
    #[OA\Response(ref: "#/components/responses/NotFound", response: 404)]
    #[OA\Response(ref: "#/components/responses/UnexpectedException", response: 500)]
    #[Route('/profile', name: RouteName::CUSTOMER_PROFILE, methods: ['GET'], priority: 1010)]
    public function getProfile(): JsonResponse
    {
        return $this->respond(CustomerProfile::of($this->getUser()));
    }
}
