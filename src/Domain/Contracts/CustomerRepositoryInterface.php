<?php

namespace App\Domain\Contracts;

use App\Domain\Entity\User;

interface CustomerRepositoryInterface
{
    public function persistUnique(User $user): User;

    public function findByCredentialsOrFail(string $identity, string $password): User;
}
