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
        GenericIdInterface $customerId,
    ): Card {
        return $this->cardRepository->persist(
            $keeper
                ->getWorkspace($workspaceId)
                ->getPlan($planId)
                ->addCard($cardId, $this->customerRepository->take($customerId))
        );
    }

    public function complete(User $keeper, GenericIdInterface $workspaceId, GenericIdInterface $cardId): Card
    {
        return $this->cardRepository->persist(
            $keeper
                ->getWorkspace($workspaceId)
                ->getCard($cardId)
                ->complete()
        );
    }

    public function block(User $keeper, GenericIdInterface $workspaceId, GenericIdInterface $cardId): Card
    {
        return $this->cardRepository->persist(
            $keeper
                ->getWorkspace($workspaceId)
                ->getCard($cardId)
                ->block()
        );
    }

    public function unblock(User $keeper, GenericIdInterface $workspaceId, GenericIdInterface $cardId): Card
    {
        return $this->cardRepository->persist(
            $keeper
                ->getWorkspace($workspaceId)
                ->getCard($cardId)
                ->unblock()
        );
    }

    public function revoke(User $keeper, GenericIdInterface $workspaceId, GenericIdInterface $cardId): Card
    {
        return $this->cardRepository->persist(
            $keeper
                ->getWorkspace($workspaceId)
                ->getCard($cardId)
                ->revoke()
        );
    }

    public function noteAchievement(
        User $keeper,
        GenericIdInterface $workspaceId,
        GenericIdInterface $cardId,
        GenericIdInterface $achievementId,
        string $description,
    ): Card {
        return $this->cardRepository->persist(
            $keeper
                ->getWorkspace($workspaceId)
                ->getCard($cardId)
                ->noteAchievement($achievementId, $description)
        );
    }

    public function dismissAchievement(
        User $keeper,
        GenericIdInterface $workspaceId,
        GenericIdInterface $cardId,
        GenericIdInterface $achievementId,
    ): Card {
        return $this->cardRepository->persist(
            $keeper
                ->getWorkspace($workspaceId)
                ->getCard($cardId)
                ->dismissAchievement($achievementId)
        );
    }

}
