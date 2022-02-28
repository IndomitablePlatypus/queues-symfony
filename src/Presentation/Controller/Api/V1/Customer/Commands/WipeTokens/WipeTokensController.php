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
    #[OA\Response(
        response: 200,
        description: "Removes all existing access tokens for the current user. (I.e. logout)",
        content: new OA\MediaType(
            mediaType: "json",
            schema: new OA\Schema(
                description: "Customer Id",
                type: "string",
                format: "uuid",
                nullable: false,
            )
        )
    )]
    #[Route('/wipe-tokens', name: RouteName::CLEAR_TOKENS, methods: ['GET'])]
    public function register(MessageBusInterface $messageBus): JsonResponse
    {
        $userId = $this->getUser()->getId();
        $messageBus->dispatch(ClearTokens::of($userId));
        return $this->respond((string) $userId);
    }
}
