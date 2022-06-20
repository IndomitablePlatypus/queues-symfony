<?php

namespace App\Presentation\Controller\Api\V1\Customer\Commands\Register;

use App\Application\Services\CustomerService;
use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Customer\Commands\Register\Input\RegisterCustomerRequest;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[OA\Tag("Customer")]
#[Route('/api/v1/customer')]
class CustomerRegisterController extends ApiController
{
    #[OA\Response(
        response: 200,
        description: "Registers new user with email OR phone, password, device name (for token). Returns new auth token.",
        content: new OA\MediaType(
            mediaType: "json",
            schema: new OA\Schema(
                description: "API Access Bearer Token",
                type: "string",
                example: "9|eigK2WNOHtJEOKtgcXD6m2NIaDFVcIMDfCMrsKii",
                nullable: false,
            )
        )
    )]
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
