<?php

namespace App\Infrastructure\Repository;

use App\Application\Contracts\GenericIdInterface;
use App\Domain\Contracts\WorkspaceRepositoryInterface;
use App\Domain\Entity\Workspace;
use App\Infrastructure\Exceptions\NotFoundException;
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

    public function take(GenericIdInterface $workspaceId): Workspace
    {
        /** @var Workspace $workspace */
        $workspace = $this->find($workspaceId);
        return $workspace ?? throw new NotFoundException("Workspace $workspaceId not found");
    }

}
