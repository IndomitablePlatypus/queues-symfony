<?php

namespace App\Presentation\Controller\Api\V1\Customer\GetId;

use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/customer')]
class CustomerGetIdController extends ApiController
{
    #[Route('/get-id', name: RouteName::CUSTOMER_ID, methods: ['GET'])]
    public function getId(): JsonResponse
    {
        return $this->respond((string) $this->getUser()?->getId());
    }
}
