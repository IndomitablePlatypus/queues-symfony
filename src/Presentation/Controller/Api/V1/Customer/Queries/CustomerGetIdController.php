<?php

namespace App\Presentation\Controller\Api\V1\Customer\Queries;

use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Tag(name="Customer")
 */
#[Route('/api/v1/customer')]
class CustomerGetIdController extends ApiController
{
    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns id of the authenticated user.",
     *     @OA\JsonContent(type="string", format="uuid", description="Current customer Id")
     * )
     */
    #[Route('/id', name: RouteName::CUSTOMER_ID, methods: ['GET'])]
    public function getId(): JsonResponse
    {
        return $this->respond((string) $this->getUser()?->getId());
    }
}
