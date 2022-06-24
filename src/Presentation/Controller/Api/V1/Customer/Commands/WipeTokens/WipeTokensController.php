<?php

namespace App\Presentation\Controller\Api\V1\Customer\Commands\WipeTokens;

use App\Config\Routing\RouteName;
use App\Domain\Messages\ClearTokens;
use App\Presentation\Controller\Api\V1\ApiController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag("Customer")]
#[Route('/api/v1/customer')]
class WipeTokensController extends ApiController
{
    /**
     * Clear user tokens
     *
     * Removes all existing access tokens for the current user. (I.e. logout)
     */
    #[OA\Response(
        response: 200,
        description: "All tokens successfully cleared",
        content: new OA\JsonContent(
            description: "Customer Id",
            type: "string",
            example: "41c8613d-6ae2-41ad-841a-ffd06a116961",
            nullable: false,
        )
    )]
    #[OA\Response(ref: "#/components/responses/AuthenticationException", response: 401)]
    #[OA\Response(ref: "#/components/responses/UnexpectedException", response: 500)]
    #[Route('/wipe-tokens', name: RouteName::CLEAR_TOKENS, methods: ['GET'], priority: 1025)]
    public function register(MessageBusInterface $messageBus): JsonResponse
    {
        $userId = $this->getUser()->getId();
        $messageBus->dispatch(ClearTokens::of($userId));
        return $this->respond((string) $userId);
    }
}
