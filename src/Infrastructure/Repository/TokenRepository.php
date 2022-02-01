<?php

namespace App\Infrastructure\Repository;

use App\Domain\Contracts\TokenRepositoryInterface;
use App\Domain\Entity\Token;
use App\Domain\Entity\User;
use App\Infrastructure\Exceptions\AuthenticationFailedException;
use App\Infrastructure\Exceptions\ParameterAssertionException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TokenRepository extends ServiceEntityRepository implements TokenRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Token::class);
    }

    public function getToken(string $plainTextToken): Token
    {
        /** @var Token $token */
        $token = $this->find($this->getIdFromPlainTextToken($plainTextToken));
        if (password_verify($this->getTokenStringFromPlainTextToken($plainTextToken), $token->getToken())) {
            return $token;
        }
        throw new AuthenticationFailedException('Unknown token');
    }

    public function setToken(User $user, string $name): Token
    {
        $token = Token::create($user->getId(), $name);
        $this->_em->persist($token);
        $this->_em->flush();
        return $token;
    }

    protected function getIdFromPlainTextToken(string $plainTextToken): int
    {
        $parts = explode(Token::TOKEN_SEPARATOR, $plainTextToken);
        $id = array_shift($parts);
        if (!is_int($id)) {
            throw new ParameterAssertionException('Invalid token format');
        }
        return $id;
    }

    protected function getTokenStringFromPlainTextToken(string $plainTextToken): int
    {
        $parts = explode(Token::TOKEN_SEPARATOR, $plainTextToken);
        $token = array_pop($parts);
        if (empty($token)) {
            throw new ParameterAssertionException('Invalid token format');
        }
        return $token;
    }

}
