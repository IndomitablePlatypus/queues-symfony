<?php

namespace App\Domain\Entity;

use App\Infrastructure\Exceptions\LogicException;
use App\Infrastructure\Repository\TokenRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: TokenRepository::class)]
#[ORM\Table(name: '`tokens`')]
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

    public static function create(string $userId, string $name): static
    {
        return (new static(static::generateTokenString()))
            ->setUserId($userId)
            ->setName($name);
    }

    public function __construct(private string $tokenString)
    {
        $this->token = password_hash($this->tokenString, PASSWORD_DEFAULT);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): static
    {
        $this->userId = $userId;
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

    protected static function generateTokenString(int $length = 40): string
    {
        $string = '';
        while (($len = strlen($string)) < $length) {
            $size = $length - $len;
            $bytes = random_bytes($size);
            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }
        return $string;
    }
}
