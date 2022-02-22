<?php

namespace App\Application\MessageHandlers;

use App\Domain\Contracts\CardRepositoryInterface;
use App\Domain\Contracts\PlanRepositoryInterface;
use App\Domain\Entity\Card;
use App\Domain\Messages\RequirementChanged;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class RequirementChangedHandler implements MessageHandlerInterface
{
    public function __construct(
        protected PlanRepositoryInterface $planRepository,
        protected CardRepositoryInterface $cardRepository,
    ) {
    }

    public function __invoke(RequirementChanged $message)
    {
        $plan = $this->planRepository->take($message->planId);

        $requirementId = $message->requirementId;
        $description = $message->description;

        $cards = $plan->getCards();
        /** @var Card $card */
        foreach ($cards as $card) {
            $this->cardRepository->persist(
                $card->fixRequirementDescription($requirementId, $description)
            );
        }
    }
}
