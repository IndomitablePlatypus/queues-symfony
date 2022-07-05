<?php

namespace App\Tests;

use App\Domain\Contracts\TokenRepositoryInterface;
use App\Domain\Entity\User;
use App\Tests\Helpers\RepositoriesTrait;
use App\Tests\Helpers\RequestTestTrait;
use App\Tests\Helpers\ResponseTestTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class BaseScenarioTest extends WebTestCase
{
    use RequestTestTrait, ResponseTestTrait, RepositoriesTrait;

    protected KernelBrowser $client;

    protected ContainerInterface $container;

    protected string $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->container = static::getContainer();
    }

    protected function tokenize(User $user): void
    {
        $token = $this->container->get(TokenRepositoryInterface::class)->newToken($user, static::class);
        $this->token = $token->getPlainTextToken();
    }
}
