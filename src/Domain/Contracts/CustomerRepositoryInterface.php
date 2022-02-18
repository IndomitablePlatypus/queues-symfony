<?php

namespace App\Domain\Contracts;

use App\Application\Contracts\GenericIdInterface;
use App\Domain\Entity\User;

interface CustomerRepositoryInterface
{
    public function persistUnique(User $user): User;

    public function findByCredentialsOrFail(string $identity, string $password): User;

    public function take(GenericIdInterface $customerId): User;
}
