<?php

namespace App\Presentation\Controller\Api\V1\Customer\Commands\Register;

use App\Application\Services\CustomerService;
use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Customer\Commands\Register\Input\RegisterCustomerRequest;
use App\Presentation\Controller\Api\V1\Customer\Input\RegisterRequest;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[OA\Tag("Customer")]
#[Route('/api/v1/customer')]
class CustomerRegisterController extends ApiController
{
    /**
     * Register user
     *
     * Registers new user with email OR phone, password, device name (for token). Returns new auth token.
     */
    #[OA\RequestBody(content: new OA\JsonContent(ref: new Model(type: RegisterRequest::class)))]
    #[OA\Response(
        response: 200,
        description: "Access Bearer Token",
        content: new OA\JsonContent(
            description: "API Access Bearer Token",
            type: "string",
            example: "9|eigK2WNOHtJEOKtgcXD6m2NIaDFVcIMDfCMrsKii",
            nullable: false,
        )
    )]
    #[OA\Response(ref: "#/components/responses/DomainException", response: 400)]
    #[OA\Response(ref: "#/components/responses/ValidationError", response: 422)]
    #[OA\Response(ref: "#/components/responses/UnexpectedException", response: 500)]
    #[Route('/register', name: RouteName::REGISTER, methods: ['POST'], priority: 1030)]
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
