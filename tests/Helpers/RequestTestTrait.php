<?php

namespace App\Tests\Helpers;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

trait RequestTestTrait
{
    protected UrlGeneratorInterface $urlGenerator;

    protected function urlGenerator(): UrlGeneratorInterface
    {
        return $this->urlGenerator ?? $this->urlGenerator = $this->container->get(UrlGeneratorInterface::class);
    }

    protected function generateURL(string $name, array $parameters = []): string
    {
        return $this->urlGenerator()->generate($name, $parameters);
    }

    protected function rGet(string $route, array $routeArgs = []): void
    {
        $this->request('get', $route, $routeArgs);
    }

    protected function rPost(string $route, array $routeArgs = [], array $params = []): void
    {
        $this->request('post', $route, $routeArgs, $params);
    }

    protected function rPut(string $route, array $routeArgs = [], array $params = []): void
    {
        $this->request('put', $route, $routeArgs, $params);
    }

    protected function rDelete(string $route, array $routeArgs = [], array $params = []): void
    {
        $this->request('delete', $route, $routeArgs, $params);
    }

    protected function request(string $method, string $name, array $routeArgs = [], array $params = []): Crawler
    {
        if (!empty($this->token)) {
            $this->client->setServerParameter('HTTP_AUTHORIZATION', 'Bearer ' . $this->token);
        }

        return $this->client->request(strtoupper($method), $this->generateURL($name, $routeArgs), $params);
    }
}
