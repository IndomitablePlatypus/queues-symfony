<?php

namespace App\Presentation\Controller\Api\V1\Customer\Commands\WipeTokens;

use App\Application\Services\CustomerService;
use App\Config\Routing\RouteName;
use App\Domain\Messages\ClearTokens;
use App\Presentation\Controller\Api\V1\ApiController;
use App\Presentation\Controller\Api\V1\Customer\Commands\Register\Input\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[Route('/api/v1/customer')]
class WipeTokensController extends ApiController
{
    #[Route('/wipe-tokens', name: RouteName::CLEAR_TOKENS, methods: ['GET'])]
    public function register(MessageBusInterface $messageBus): JsonResponse {
        $userId = $this->getUser()->getId();
        $messageBus->dispatch(ClearTokens::of($userId));
        return $this->respond((string) $userId);
    }
}
