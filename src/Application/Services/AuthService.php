<?php

namespace App\Application\Services;

use App\Domain\Contracts\TokenRepositoryInterface;
use App\Domain\Entity\User;
use App\Infrastructure\Repository\UserRepository;

class AuthService
{
    public function __construct(
        private UserRepository $userRepository,
        private TokenRepositoryInterface $tokenRepository,
    ) {
    }

    public function getUserByToken(string $plainTextToken): User
    {
        $token = $this->tokenRepository->getToken($plainTextToken);
        return $this->userRepository->getById($token->getUserId());
    }
}
