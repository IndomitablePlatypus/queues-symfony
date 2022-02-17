<?php

namespace App\Application\Services;

use App\Domain\Contracts\CustomerRepositoryInterface;
use App\Domain\Contracts\TokenRepositoryInterface;
use App\Domain\Entity\Token;
use App\Domain\Entity\User;
use App\Domain\Messages\ClearTokens;
use App\Infrastructure\Support\GuidBasedImmutableId;
use Symfony\Component\Messenger\MessageBusInterface;

class CustomerService
{
    public function __construct(
        protected CustomerRepositoryInterface $customerRepository,
        protected TokenRepositoryInterface $tokenRepository,
        protected MessageBusInterface $messageBus,
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

        return $this->tokenRepository->newToken($customer, $deviceName);
    }

    public function getToken(string $identity, string $password, string $deviceName): Token
    {
        $customer = $this->customerRepository->findByCredentialsOrFail($identity, $password);

        $token = $this->tokenRepository->newToken($customer, $deviceName);

        $this->messageBus->dispatch(ClearTokens::of($customer->getId(), $deviceName));

        return $token;
    }
}
