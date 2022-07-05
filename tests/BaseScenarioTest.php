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
    use ResponseTestTrait, RepositoriesTrait;

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

    protected function rGet(string $route, array $routeArgs = []): array
    {
        $this->request('get', $route, $routeArgs);
        return $this->jsonResponse();
    }

    protected function rPost(string $route, array $routeArgs = [], array $params = []): array
    {
        $this->request('post', $route, $routeArgs, $params);
        return $this->jsonResponse();
    }

    protected function rPut(string $route, array $routeArgs = [], array $params = []): array
    {
        $this->request('put', $route, $routeArgs, $params);
        return $this->jsonResponse();
    }

    protected function rDelete(string $route, array $routeArgs = [], array $params = []): array
    {
        $this->request('delete', $route, $routeArgs, $params);
        return $this->jsonResponse();
    }

}
