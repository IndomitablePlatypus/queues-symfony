<?php

namespace App\Application\Services;

use App\Domain\Contracts\CustomerRepositoryInterface;
use App\Domain\Contracts\TokenRepositoryInterface;
use App\Domain\Entity\Token;
use App\Domain\Entity\User;
use App\Infrastructure\Support\GuidBasedImmutableId;

class CustomerService
{
    public function __construct(
        protected CustomerRepositoryInterface $customerRepository,
        protected TokenRepositoryInterface $tokenRepository,
    ) {
    }

    public function register(string $identity, string $name, string $password, string $deviceName): Token
    {
        $customer = $this->customerRepository->persistUnique(
            (new User())
                ->setId(GuidBasedImmutableId::make())
                ->setUsername($identity)
                ->setName($name)
                ->setPassword($password)
        );

        return $this->tokenRepository->setToken($customer, $deviceName);
    }

    public function getToken(string $identity, string $password, string $deviceName): Token
    {
        $customer = $this->customerRepository->findByCredentialsOrFail($identity, $password);

        return $this->tokenRepository->setToken($customer, $deviceName);
    }
}
