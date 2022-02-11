<?php

namespace App\Infrastructure\Repository;

use App\Application\Contracts\GenericIdInterface;
use App\Domain\Contracts\WorkspaceRepositoryInterface;
use App\Domain\Entity\Card;
use App\Domain\Entity\Workspace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class WorkspaceRepository extends ServiceEntityRepository implements WorkspaceRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Workspace::class);
    }

    public function persist(Workspace $workspace): Workspace
    {
        $this->_em->persist($workspace);
        $this->_em->flush();
        return $workspace;
    }
}
