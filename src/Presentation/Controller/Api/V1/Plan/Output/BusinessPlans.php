<?php

namespace App\Presentation\Controller\Api\V1\Plan\Output;

use App\Domain\Entity\Plan;
use JsonSerializable;

class BusinessPlans implements JsonSerializable
{
    /** @var Plan[] */
    protected array $plans;

    public function __construct(array $plans)
    {
        $this->plans = $plans;
    }

    public static function of(Plan ...$plans): static
    {
        return new static($plans);
    }

    public function jsonSerialize(): array
    {
        return [
            ...array_map(fn($plan) => BusinessPlan::of($plan)->jsonSerialize(), $this->plans),
        ];
    }

}
