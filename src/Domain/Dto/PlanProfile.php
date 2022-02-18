<?php

namespace App\Domain\Dto;

use App\Infrastructure\Support\DtoTrait;

class PlanProfile
{
    use DtoTrait;

    public function __construct(
        public string $name,
        public string $description,
    ) {
    }

    public static function of(string $name, string $description): static
    {
        return new static ($name, $description);
    }

}
