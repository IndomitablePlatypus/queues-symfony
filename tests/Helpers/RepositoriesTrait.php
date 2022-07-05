<?php

namespace App\Tests\Helpers;

use App\Infrastructure\Repository\UserRepository;

trait RepositoriesTrait
{
    public static function getUserRepository(): UserRepository
    {
        return static::getContainer()->get(UserRepository::class);
    }
}
