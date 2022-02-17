<?php

namespace App\Application\MessageHandlers;

use App\Domain\Contracts\TokenRepositoryInterface;
use App\Domain\Messages\ClearTokens;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ClearTokensHandler implements MessageHandlerInterface
{
    public function __construct(protected TokenRepositoryInterface $tokenRepository)
    {
    }

    public function __invoke(ClearTokens $message)
    {
        $message->tokenName
            ? $this->tokenRepository->deleteOldTokens($message->userId, $message->tokenName)
            : $this->tokenRepository->deleteAllTokens($message->userId);
    }
}
