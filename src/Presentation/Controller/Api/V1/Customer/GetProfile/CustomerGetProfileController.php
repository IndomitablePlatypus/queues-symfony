<?php

namespace App\Presentation\Controller\Api\V1\Customer\GetProfile;

use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/customer')]
class CustomerGetProfileController extends ApiController
{
    #[Route('/profile', name: RouteName::CUSTOMER_PROFILE, methods: ['GET'])]
    public function getProfile(): JsonResponse
    {
        return $this->respond($this->getUser()?->profile());
    }
}
