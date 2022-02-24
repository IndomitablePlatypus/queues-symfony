<?php

namespace App\Infrastructure\Repository;

use App\Application\Contracts\GenericIdInterface;
use App\Domain\Contracts\TokenRepositoryInterface;
use App\Domain\Entity\Token;
use App\Domain\Entity\User;
use App\Infrastructure\Exceptions\AuthenticationFailedException;
use App\Infrastructure\Exceptions\NotFoundException;
use App\Infrastructure\Exceptions\ParameterAssertionException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Throwable;

class TokenRepository extends ServiceEntityRepository implements TokenRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Token::class);
    }

    public function getToken(string $plainTextToken): Token
    {
        $token = $this->getTokenSafe($this->getIdFromPlainTextToken($plainTextToken));
        if (password_verify($this->getTokenStringFromPlainTextToken($plainTextToken), $token->getToken())) {
            return $token;
        }
        throw new NotFoundException('Unknown token');
    }

    public function newToken(User $user, string $name): Token
    {
        $token = Token::create($user->getId(), $name);
        $this->_em->persist($token);
        $this->_em->flush();
        return $token;
    }

    public function deleteOldTokens(GenericIdInterface $userId, string $tokenName): void
    {
        $tokenId = $this->findOneBy([
            'userId' => $userId,
            'name' => $tokenName,
        ], ['createdAt' => 'DESC'])?->getId();

        if ($tokenId === null) {
            return;
        }

        $this->_em->createQuery("
            DELETE App\Domain\Entity\Token t
            WHERE t.userId = :userId
            AND t.name = :tokenName
            AND NOT t.id = :id
        ")->execute([
            'userId' => $userId,
            'tokenName' => $tokenName,
            'id' => $tokenId,
        ]);
    }

    public function deleteAllTokens(GenericIdInterface $userId): void
    {
        $this->_em->createQuery("
            DELETE App\Domain\Entity\Token t
            WHERE t.userId = :id
        ")->execute([
            'id' => $userId,
        ]);
    }

    protected function getIdFromPlainTextToken(string $plainTextToken): int
    {
        $parts = explode(Token::TOKEN_SEPARATOR, $plainTextToken);
        $id = (int) array_shift($parts);
        if ($id <= 0) {
            throw new ParameterAssertionException('Invalid token format');
        }
        return $id;
    }

    protected function getTokenStringFromPlainTextToken(string $plainTextToken): string
    {
        $parts = explode(Token::TOKEN_SEPARATOR, $plainTextToken);
        $token = array_pop($parts);
        if (empty($token)) {
            throw new ParameterAssertionException('Invalid token format');
        }
        return $token;
    }

    protected function getTokenSafe(int $id): Token
    {
        try {
            return $this->find($id) ?? throw new AuthenticationFailedException("Unknown token");
        } catch (AuthenticationFailedException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            throw new AuthenticationFailedException(message: "Invalid token format", previous: $exception);
        }
    }

}
