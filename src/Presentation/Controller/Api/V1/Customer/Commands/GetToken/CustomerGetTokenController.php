<?php

namespace App\Presentation\Controller\Api\V1\Customer\Commands\GetToken;

use App\Application\Services\CustomerService;
use App\Config\Routing\RouteName;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Customer\Commands\GetToken\Input\Request;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[Route('/api/v1/customer')]
class CustomerGetTokenController extends ApiController
{
    // mediaType: 'application/x-www-form-urlencoded"',
    //         description: 'Get customer API access token for the specific device. <br> With each request new token is generated. Old ones are invalidated shortly after. <br> *Tokens on other customer devices remain unaffected.',
    //#[OA\RequestBody(
    //    request: 'GetTokenRequest',
    //    description: 'Get customer API access token for the specific device. <br> With each request new token is generated. Old ones are invalidated shortly after. <br> *Tokens on other customer devices remain unaffected.',
    //    required: true,
    //    content: new OA\JsonContent(
    //        ref: Request::class
    //    ),
    //)]

    /**
     * Get user token
     *
     * Returns new API user token (for basic bearer auth). Requires identity, password and device name.
     */
    #[OA\RequestBody(
        content: new OA\JsonContent(
            ref: new Model(type: Request::class),
        ),
    )]
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
    #[OA\Tag(name: 'customer')]
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
