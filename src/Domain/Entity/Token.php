<?php

namespace App\Domain\Entity;

use App\Application\Contracts\GenericIdInterface;
use App\Infrastructure\Exceptions\LogicException;
use App\Infrastructure\Repository\TokenRepository;
use App\Infrastructure\Support\GuidBasedImmutableId;
use App\Infrastructure\Support\StringHelper;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: TokenRepository::class)]
#[ORM\Table(name: '`tokens`')]
#[ORM\Index(fields: ["userId"])]
#[ORM\Index(fields: ["name"])]
#[ORM\HasLifecycleCallbacks]
class Token
{
    use TimestampableEntity;

    public const TOKEN_SEPARATOR = '|';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $userId;

    #[ORM\Column(type: 'string', length: 255)]
    private string $token;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    private function __construct(private string $tokenString)
    {
        $this->token = password_hash($this->tokenString, PASSWORD_DEFAULT);
    }

    public static function create(GenericIdInterface $userId, string $name): static
    {
        return (new static(StringHelper::random(40)))
            ->setUserId($userId)
            ->setName($name);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUserId(): GenericIdInterface
    {
        return GuidBasedImmutableId::of($this->userId);
    }

    public function setUserId(GenericIdInterface $userId): static
    {
        $this->userId = (string) $userId;
        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getPlainTextToken(): string
    {
        if (!isset($this->id)) {
            throw new LogicException('Unable to obtain token');
        }
        return implode(static::TOKEN_SEPARATOR, [$this->getId(), $this->tokenString]);
    }
}
