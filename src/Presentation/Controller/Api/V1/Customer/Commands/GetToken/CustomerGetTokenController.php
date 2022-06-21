<?php

namespace App\Presentation\Controller\Api\V1\Customer\Commands\GetToken;

use App\Application\Services\CustomerService;
use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Customer\Commands\GetToken\Input\GetCustomerAccessToken;
use App\Presentation\Controller\Api\V1\Customer\Commands\GetToken\Input\GetTokenRequest;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[OA\Tag("Customer")]
#[Route('/api/v1/customer')]
class CustomerGetTokenController extends ApiController
{
    /**
     * Get user token
     *
     * Returns new API user token (for basic bearer auth). Requires identity, password and device name.
     */
    #[OA\RequestBody(content: new OA\JsonContent(ref: new Model(type: GetCustomerAccessToken::class)))]
    #[OA\Response(
        response: 200,
        description: 'Access token',
        content: new OA\JsonContent(
            description: 'API Access Bearer Token',
            type: 'string',
            example: '9|eigK2WNOHtJEOKtgcXD6m2NIaDFVcIMDfCMrsKii',
            nullable: false,
        )
    )]
    #[OA\Response(ref: "#/components/responses/AuthenticationException", response: 401)]
    #[OA\Response(ref: "#/components/responses/ValidationError", response: 422)]
    #[OA\Response(ref: "#/components/responses/UnexpectedException", response: 500)]
    #[Route('/get-token', name: RouteName::GET_TOKEN, methods: ['POST'], priority: 1035)]
    public function getToken(
        GetTokenRequest $request,
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
