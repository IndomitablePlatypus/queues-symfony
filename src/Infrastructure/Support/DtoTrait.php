<?php

namespace App\Infrastructure\Support;

use JetBrains\PhpStorm\Pure;

trait DtoTrait
{
    use ArrayPresenterTrait;

    #[Pure]
    public static function fromArray(array $values): static
    {
        return new static(...$values);
    }

    public static function fromString(string $values): static
    {
        return new static(...json_decode($values, true, flags: JSON_THROW_ON_ERROR));
    }

    public function toArray(): array
    {
        return $this->_toArray(true, true, true);
    }
}
