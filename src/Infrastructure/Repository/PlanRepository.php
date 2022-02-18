<?php

namespace App\Infrastructure\Repository;

use App\Domain\Contracts\PlanRepositoryInterface;
use App\Domain\Entity\Plan;
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

}
