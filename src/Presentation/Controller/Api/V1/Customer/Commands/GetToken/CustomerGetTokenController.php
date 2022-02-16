<?php

namespace App\Presentation\Controller\Api\V1\Customer\Commands\GetToken;

use App\Application\Services\CustomerService;
use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Customer\Commands\GetToken\Input\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[Route('/api/v1/customer')]
class CustomerGetTokenController extends ApiController
{
    #[Route('/get-token', name: RouteName::GET_TOKEN, methods: ['POST'])]
    public function getToken(
        Request $request,
        CustomerService $customerService,
        ConstraintViolationListInterface $validationErrors,
    ): JsonResponse {
        $this->validate($validationErrors);

        return $this->respond(
            $customerService
                ->getToken(
                    $request->identity,
                    $request->password,
                    $request->deviceName,
                )
                ->getPlainTextToken()
        );
    }
}
