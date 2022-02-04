<?php

namespace App\Domain\Dto;

use App\Infrastructure\Support\DtoTrait;

class WorkspaceProfile
{
    use DtoTrait;

    public function __construct(
        public string $name,
        public string $description,
        public string $address,
    ) {
    }

    public static function of(string $name, string $description, string $address): static
    {
        return new static ($name, $description, $address);
    }

}
