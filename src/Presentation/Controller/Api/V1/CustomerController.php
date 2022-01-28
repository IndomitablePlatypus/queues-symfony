<?php

namespace App\Presentation\Controller\Api\V1;

use App\Config\Routing\RouteName;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/customer')]
class CustomerController extends ApiController
{
    #[Route('/register', name: RouteName::REGISTER)]
    public function register(): JsonResponse
    {
        return $this->respond('!');
    }
}
