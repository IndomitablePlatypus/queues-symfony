<?php

namespace App\Infrastructure\Repository;

use App\Application\Contracts\GenericIdInterface;
use App\Domain\Contracts\CustomerRepositoryInterface;
use App\Domain\Entity\User;
use App\Infrastructure\Exceptions\AuthenticationFailedException;
use App\Infrastructure\Exceptions\NotFoundException;
use App\Infrastructure\Exceptions\UserExistsException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository
    extends
    ServiceEntityRepository
    implements
    PasswordUpgraderInterface,
    CustomerRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function persistUnique(User $user): User
    {
        try {
            $this->_em->persist($user);
            $this->_em->flush();
            return $user;
        } catch (UniqueConstraintViolationException $exception) {
            throw new UserExistsException('User with given identity already exists');
        }
    }

    public function findByCredentialsOrFail(string $identity, string $password): User
    {
        try {
            /** @var User $user */
            $user = $this->createQueryBuilder('u')
                ->andWhere('u.username = :identity')
                ->setParameter('identity', $identity)
                ->getQuery()
                ->getOneOrNullResult();
            if (password_verify($password, $user->getPassword())) {
                return $user;
            }
            throw new AuthenticationFailedException('Unknown credentials');
        } catch (NonUniqueResultException) {
            throw new AuthenticationFailedException('Unknown credentials');
        }
    }

    public function take(GenericIdInterface $customerId): User
    {
        return $this->getById($customerId);
    }

    public function getById(GenericIdInterface $id): User
    {
        return $this->find($id) ?? throw new NotFoundException("User $id not found");
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
