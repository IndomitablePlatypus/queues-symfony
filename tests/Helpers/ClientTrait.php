<?php

namespace App\Tests\Helpers;

use App\Domain\Entity\User;
use JsonException;
use PHPUnit\Framework\ExpectationFailedException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

trait ClientTrait
{
    protected static KernelBrowser $client;

    protected static function init(): void
    {
        static::$client = static::createClient();
    }
    protected static function urlGenerator(): UrlGeneratorInterface
    {
        static $urlGenerator;
        return $urlGenerator ?? $urlGenerator = static::getContainer()->get(UrlGeneratorInterface::class);
    }

    protected static function generateURL(string $name, array $parameters = []): string
    {
        return static::urlGenerator()->generate($name, $parameters);
    }

    protected static function rGet(string $route, array $pathParams = []): void
    {
        static::$client->request('GET', static::generateURL($route, $pathParams));
    }

    protected static function rPost(string $route, array $pathParams = [], array $params = []): void
    {
        static::$client->request('POST', static::generateURL($route, $pathParams), $params);
    }

    protected static function withUser(User $user): void
    {
        static::$client->loginUser($user);
    }

    protected static function json(): array
    {
        static $json;
        try {
            return $json ?? $json = json_decode(static::$client->getResponse()->getContent(), true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new ExpectationFailedException('Valid json expected: ' . $exception->getMessage());
        }
    }

    protected static function val(string $key): mixed
    {
        return static::json()[$key];

    }
}
