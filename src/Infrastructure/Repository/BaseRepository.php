<?php

namespace App\Infrastructure\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;

class BaseRepository
{
    protected EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @throws ORMException
     */
    protected function reopenIfNeed(): void
    {
        if ($this->em->isOpen()) {
            return;
        }
        $this->em = EntityManager::create(
            $this->em->getConnection(),
            $this->em->getConfiguration()
        );
    }

}
