<?php

namespace App\Tests\DataFixtures;

use Faker\Factory;
use Faker\Generator;

trait FakerTrait
{
    private Generator $faker;

    protected function faker(): Generator
    {
        return $this->faker ?? $this->faker = Factory::create("en_GB");
    }


}
