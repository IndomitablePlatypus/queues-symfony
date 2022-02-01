<?php

namespace App\Domain\Contracts;

use App\Domain\Entity\Token;
use App\Domain\Entity\User;

interface TokenRepositoryInterface
{
    public function getToken(string $plainTextToken): Token;

    public function setToken(User $user, string $name): Token;
}
