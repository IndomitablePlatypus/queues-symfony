<?php

namespace App\Infrastructure\Repository;

use App\Domain\Contracts\RequirementRepositoryInterface;
use App\Domain\Entity\Requirement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RequirementRepository extends ServiceEntityRepository implements RequirementRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Requirement::class);
    }

    public function persist(Requirement $requirement): Requirement
    {
        $this->_em->persist($requirement);
        $this->_em->flush();
        return $requirement;
    }
}
