<?php

namespace App\Infrastructure\Repository;

use App\Application\Contracts\GenericIdInterface;
use App\Domain\Contracts\InviteRepositoryInterface;
use App\Domain\Entity\Invite;
use App\Infrastructure\Exceptions\NotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class InviteRepository extends ServiceEntityRepository implements InviteRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invite::class);
    }

    public function persist(Invite $invite): Invite
    {
        $this->_em->persist($invite);
        $this->_em->flush();
        return $invite;
    }

    public function delete(GenericIdInterface $inviteId): void
    {
        $query = $this->_em->createQuery("
            DELETE App\Domain\Entity\Invite i 
            WHERE i.id = :inviteId
        ")->setParameter('inviteId', $inviteId);
        $query->execute();
    }

    public function take(GenericIdInterface $inviteId): Invite
    {
        /** @var Invite $invite */
        $invite = $this->find((string) $inviteId);
        return $invite ?? throw new NotFoundException("Invite $inviteId not found");
    }
}
