<?php

namespace App\Presentation\Controller\Api\V1\Customer\Commands\GetToken;

use App\Application\Services\CustomerService;
use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Customer\Commands\GetToken\Input\Request;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[OA\Tag("Customer")]
#[Route('/api/v1/customer')]
class CustomerGetTokenController extends ApiController
{
    #[OA\Response(
        response: 200,
        description: "Returns new API user token (for basic bearer auth). Requires identity, password and device name.",
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
