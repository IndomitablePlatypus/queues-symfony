<?php

namespace App\Presentation\Controller\Api\V1\Customer\Register;

use App\Application\Services\CustomerService;
use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Customer\Register\Input\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[Route('/api/v1/customer')]
class CustomerRegisterController extends ApiController
{
    #[Route('/register', name: RouteName::REGISTER)]
    public function register(
        Request $request,
        CustomerService $customerService,
        ConstraintViolationListInterface $validationErrors
    ): JsonResponse
    {
        $this->validate($validationErrors);

        return $this->respond(
            $customerService
                ->register(
                    $request->phone,
                    $request->name,
                    $request->password
                )
        );
    }
}
