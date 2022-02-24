<?php

namespace App\Application\MessageHandlers;

use App\Domain\Contracts\CardRepositoryInterface;
use App\Domain\Contracts\PlanRepositoryInterface;
use App\Domain\Entity\Card;
use App\Domain\Messages\RequirementsChanged;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class RequirementsChangedHandler implements MessageHandlerInterface
{
    public function __construct(
        protected PlanRepositoryInterface $planRepository,
        protected CardRepositoryInterface $cardRepository,
    ) {
    }

    public function __invoke(RequirementsChanged $message)
    {
        $plan = $this->planRepository->take($message->planId);

        $cards = $plan->getCards();
        /** @var Card $card */
        foreach ($cards as $card) {
            $this->cardRepository->persist(
                $card->acceptRequirements($plan->getCompactRequirements()->toArray())
            );
        }
    }
}
