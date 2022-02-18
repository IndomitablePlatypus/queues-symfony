<?php

namespace App\Domain\Contracts;

use App\Application\Contracts\GenericIdInterface;
use App\Domain\Entity\Token;
use App\Domain\Entity\User;

interface TokenRepositoryInterface
{
    public function getToken(string $plainTextToken): Token;

    public function newToken(User $user, string $name): Token;

    public function deleteOldTokens(GenericIdInterface $userId, string $tokenName): void;

    public function deleteAllTokens(GenericIdInterface $userId): void;
}
