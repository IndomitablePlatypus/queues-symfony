<?php

namespace App\Infrastructure\Support;

trait ShortClassNameTrait
{
    public static function shortName(): string
    {
        return substr(strrchr('\\' . static::class, '\\'), 1);
    }
}
