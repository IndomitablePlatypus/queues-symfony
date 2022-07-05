<?php

namespace App\Tests\Helpers;

use JsonException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\ExpectationFailedException;
use Throwable;

trait ResponseTestTrait
{
    protected array $jsonResponse;

    protected function jsonResponse(): array
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

}
