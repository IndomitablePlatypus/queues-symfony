<?php

namespace App\Infrastructure\Repository;

use App\Application\Contracts\GenericIdInterface;
use App\Domain\Contracts\PlanRepositoryInterface;
use App\Domain\Entity\Plan;
use App\Infrastructure\Exceptions\NotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PlanRepository extends ServiceEntityRepository implements PlanRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Plan::class);
    }

    public function persist(Plan $plan): Plan
    {
        $this->_em->persist($plan);
        $this->_em->flush();
        return $plan;
    }

    public function take(GenericIdInterface $planId): Plan
    {
        /** @var Plan $plan */
        $plan = $this->find((string) $planId);
        return $plan ?? throw new NotFoundException("Plan $planId not found");
    }

}
