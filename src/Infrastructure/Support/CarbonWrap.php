<?php

namespace App\Infrastructure\Support;

use Carbon\Carbon;
use DateTime;

trait CarbonWrap
{
    private static function carbonOrNull(?DateTime $dateTime): ?Carbon
    {
        return $dateTime === null ? null : Carbon::instance($dateTime);
    }

    private static function now(): DateTime
    {
        return Carbon::now()->toDateTime();
    }

    private static function dateOf(string $date): DateTime
    {
        return (new Carbon($date))->toDateTime();
    }
}
