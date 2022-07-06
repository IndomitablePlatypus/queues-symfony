<?php

namespace App\Tests\Helpers;

use JsonException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\ExpectationFailedException;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Throwable;

trait ResponseTestTrait
{
    protected mixed $jsonResponse;

    protected UrlGeneratorInterface $urlGenerator;

    protected function urlGenerator(): UrlGeneratorInterface
    {
        return $this->urlGenerator ?? $this->urlGenerator = $this->container->get(UrlGeneratorInterface::class);
    }

    protected function generateURL(string $name, array $parameters = []): string
    {
        return $this->urlGenerator()->generate($name, $parameters);
    }

    protected function request(string $method, string $name, array $routeArgs = [], array $params = []): Crawler
    {
        $this->jsonResponse = null;
        if (!empty($this->token)) {
            $this->client->setServerParameter('HTTP_AUTHORIZATION', 'Bearer ' . $this->token);
        }

        return $this->client->request(strtoupper($method), $this->generateURL($name, $routeArgs), $params);
    }

    protected function jsonResponse(): array|string
    {
        try {
            return $this->jsonResponse ?? $this->jsonResponse = json_decode($this->client->getResponse()->getContent(), true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new ExpectationFailedException('Valid json expected: ' . $exception->getMessage());
        }
    }

    protected function responseValue(string $key): mixed
    {
        return $this->jsonResponse()[$key];
    }

    public function assertJsonResponseContainsKeys(array $expected): void
    {
        try {
            foreach ($expected as $key) {
                Assert::assertArrayHasKey($key, $this->jsonResponse());
            }
        } catch (ExpectationFailedException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            throw new ExpectationFailedException('Unexpected exception: ' . $exception->getMessage());
        }
    }

    public function assertJsonResponseContainsKey(mixed $expected): void
    {
        $this->assertJsonResponseContainsKeys([$expected]);
    }

    public function assertResponseCode(int $code): void
    {
        static::assertResponseStatusCodeSame($code);
    }

    public function assertNotFound(): void
    {
        static::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function assertResponseSucceeded(): void
    {
        static::assertResponseIsSuccessful();
    }


}
