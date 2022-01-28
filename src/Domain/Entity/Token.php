<?php

namespace App\Domain\Entity;

use App\Infrastructure\Repository\TokenRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: TokenRepository::class)]
#[ORM\Table(name: '`tokens`')]
#[ORM\HasLifecycleCallbacks]
class Token
{
    use TimestampableEntity;

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

    public function getId(): ?string
    {
        return $this->id;
    }

    public static function create( string $name): static
    {
        $token = new static();
    }
}
