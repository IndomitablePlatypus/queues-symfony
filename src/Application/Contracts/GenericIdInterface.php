<?php

namespace App\Application\Contracts;

use JsonSerializable;
use Stringable;

interface GenericIdInterface extends JsonSerializable, Stringable
{
    public static function make(): static;

    public static function makeValue(): string;

    public static function of(string $id): static;

    public function equals(GenericIdInterface $id): bool;

    public function is(string $id): bool;

    public function jsonSerialize();

    public function __toString(): string;
}
