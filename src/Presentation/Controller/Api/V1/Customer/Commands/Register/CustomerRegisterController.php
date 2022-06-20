<?php

namespace App\Presentation\Controller\Api\V1\Customer\Commands\Register;

use App\Application\Services\CustomerService;
use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Customer\Commands\Register\Input\RegisterCustomerRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[Route('/api/v1/customer')]
class CustomerRegisterController extends ApiController
{
    #[Route('/register', name: RouteName::REGISTER, methods: ['POST'])]
    public function register(
        RegisterCustomerRequest $request,
        CustomerService $customerService,
        ConstraintViolationListInterface $validationErrors,
    ): JsonResponse {
        $this->validate($validationErrors);

        return $this->respond(
            $customerService
                ->register(
                    $request->phone,
                    $request->name,
                    $request->password,
                    $request->deviceName,
                )
                ->getPlainTextToken()
        );
    }
}
