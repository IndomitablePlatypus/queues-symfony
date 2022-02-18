<?php

namespace App\Presentation\Controller\Api\V1\Card\Output;

use App\Domain\Entity\Card;
use JsonSerializable;

class BusinessCard implements JsonSerializable
{
    public function __construct(protected Card $card)
    {
    }

    public static function of(Card $card): static
    {
        return new static($card);
    }

    public function jsonSerialize(): array
    {
        return [
            'cardId' => (string) $this->card->getId(),
            'planId' => (string) $this->card->getPlanId(),
            'customerId' => (string) $this->card->getCustomerId(),
            'isIssued' => $this->card->isIssued(),
            'isSatisfied' => $this->card->isSatisfied(),
            'isCompleted' => $this->card->isCompleted(),
            'isRevoked' => $this->card->isRevoked(),
            'isBlocked' => $this->card->isBlocked(),
            'achievements' => $this->card->getAchievements(),
            'requirements' => $this->card->getRequirements(),
        ];
    }

}
