<?php

namespace App\Application\Services;

use App\Application\Contracts\GenericIdInterface;
use App\Domain\Contracts\CardRepositoryInterface;
use App\Domain\Contracts\CustomerRepositoryInterface;
use App\Domain\Contracts\PlanRepositoryInterface;
use App\Domain\Contracts\WorkspaceRepositoryInterface;
use App\Domain\Entity\Card;
use App\Domain\Entity\User;

class CardService
{
    public function __construct(
        protected CustomerRepositoryInterface $customerRepository,
        protected WorkspaceRepositoryInterface $workspaceRepository,
        protected PlanRepositoryInterface $planRepository,
        protected CardRepositoryInterface $cardRepository,
    ) {
    }

    public function issue(
        User $keeper,
        GenericIdInterface $workspaceId,
        GenericIdInterface $planId,
        GenericIdInterface $cardId,
        GenericIdInterface $customerId
    ): Card {
        return $this->cardRepository->persist(
            $keeper
                ->getWorkspace($workspaceId)
                ->getPlan($planId)
                ->addCard($cardId, $this->customerRepository->take($customerId))
        );
    }
}
