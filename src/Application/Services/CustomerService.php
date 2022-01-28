<?php

namespace App\Application\Services;

use App\Domain\Contracts\CustomerRepositoryInterface;
use App\Domain\Entity\User;
use App\Infrastructure\Support\GuidBasedImmutableId;

class CustomerService
{
    public function __construct(private CustomerRepositoryInterface $customerRepository)
    {
    }

    public function register(string $identity, string $name, string $password): User
    {
        $this->customerRepository->persistUnique(
            (new User())
                ->setId(GuidBasedImmutableId::make())
                ->setUsername($identity)
                ->setName($name)
                ->setPassword($password)
        );
    }
}
