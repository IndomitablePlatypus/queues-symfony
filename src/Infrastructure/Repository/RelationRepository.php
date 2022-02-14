<?php

namespace App\Infrastructure\Repository;

use App\Application\Contracts\GenericIdInterface;
use App\Domain\Contracts\RelationRepositoryInterface;
use App\Domain\Dto\RelationType;
use App\Domain\Entity\Relation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RelationRepository extends ServiceEntityRepository implements RelationRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Relation::class);
    }

    public function establish(Relation $relation): Relation
    {
        $this->_em->persist($relation);
        $this->_em->flush();
        return $relation;
    }

    public function cease(GenericIdInterface $collaboratorId, GenericIdInterface $workspaceId, RelationType $relationType): void
    {
        $query = $this->_em->createQuery("
            DELETE App\Domain\Entity\Relation r 
            WHERE r.collaborator.id = :collaboratorId
            AND r.workspace.id = :workspaceId
            AND r.relationType = :relationType
        ")
            ->setParameter('collaboratorId', $collaboratorId)
            ->setParameter('workspaceId', $workspaceId)
            ->setParameter('relationType', (string) $relationType);
        $query->execute();
    }
}
