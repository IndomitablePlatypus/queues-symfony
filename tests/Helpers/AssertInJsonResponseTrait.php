<?php

namespace App\Tests\Helpers;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\ExpectationFailedException;
use Throwable;

trait AssertInJsonResponseTrait
{
    public static function assertJsonResponseContainsKeys(array $expected): void
    {
        try {
            $data = static::json();
            foreach ($expected as $key) {
                Assert::assertArrayHasKey($key, $data);
            }
        } catch (ExpectationFailedException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            throw new ExpectationFailedException('Unexpected exception: ' . $exception->getMessage());
        }
    }

    public static function assertJsonResponseContainsKey(mixed $expected): void
    {
        self::assertJsonResponseContainsKeys([$expected]);
    }
}
