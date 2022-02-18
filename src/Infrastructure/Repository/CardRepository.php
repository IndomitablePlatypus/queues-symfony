<?php

namespace App\Infrastructure\Repository;

use App\Domain\Contracts\CardRepositoryInterface;
use App\Domain\Entity\Card;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CardRepository extends ServiceEntityRepository implements CardRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Card::class);
    }

    public function persist(Card $card): Card
    {
        $this->_em->persist($card);
        $this->_em->flush();
        return $card;
    }
}
