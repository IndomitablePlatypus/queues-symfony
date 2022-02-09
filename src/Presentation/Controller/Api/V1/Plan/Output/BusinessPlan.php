<?php

namespace App\Presentation\Controller\Api\V1\Plan\Output;

use App\Domain\Entity\Plan;
use JsonSerializable;

class BusinessPlan implements JsonSerializable
{
    public function __construct(protected Plan $plan)
    {
    }

    public static function of(Plan $plan): static
    {
        return new static($plan);
    }

    public function jsonSerialize(): array
    {
        return [
            'planId' => (string) $this->plan->getId(),
            'workspaceId' => (string) $this->plan->getWorkspaceId(),
            'name' => $this->plan->getProfile()->name,
            'description' => $this->plan->getProfile()->description,
            'isLaunched' => $this->plan->getLaunchedAt() !== null,
            'isStopped' => $this->plan->getStoppedAt() !== null,
            'isArchived' => $this->plan->getArchivedAt() !== null,
            'expirationDate' => $this->plan->getExpirationDate(),
        ];
    }

}
