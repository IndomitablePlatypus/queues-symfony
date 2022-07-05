<?php

namespace App\Tests\Helpers;

use App\Infrastructure\Repository\UserRepository;

trait RepositoriesTrait
{
    protected UserRepository $userRepository;

    public function getUserRepository(): UserRepository
    {
        return $this->userRepository ?? $this->userRepository = $this->container->get(UserRepository::class);
    }
}
