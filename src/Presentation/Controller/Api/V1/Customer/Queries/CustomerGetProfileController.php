<?php

namespace App\Presentation\Controller\Api\V1\Customer\Queries;

use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Customer\Output\Profile;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Tag(name="Customer")
 */
#[Route('/api/v1/customer')]
class CustomerGetProfileController extends ApiController
{
    /**
     * @OA\Response(
     *     response=200,
     *     description="Get card",
     *     @Model(type=Profile::class)
     * )
     */
    #[Route('/profile', name: RouteName::CUSTOMER_PROFILE, methods: ['GET'])]
    public function getProfile(): JsonResponse
    {
        return $this->respond(Profile::of($this->getUser()));
    }
}
