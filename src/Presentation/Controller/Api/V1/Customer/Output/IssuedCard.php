<?php

namespace App\Presentation\Controller\Api\V1\Customer\Output;

use App\Domain\Entity\Card;
use JsonSerializable;

class IssuedCard implements JsonSerializable
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
            'workspaceName' => $this->card->getPlan()->getWorkspace()->getProfile()->name,
            'workspaceAddress' => $this->card->getPlan()->getWorkspace()->getProfile()->description,
            'customerId' => (string) $this->card->getCustomerId(),
            'description' => $this->card->getDescription(),
            'satisfied' => $this->card->isSatisfied(),
            'completed' => $this->card->isCompleted(),
            'blocked' => $this->card->isBlocked(),
            'achievements' => $this->card->getAchievements(),
            'requirements' => $this->card->getRequirements(),
        ];
    }

}
