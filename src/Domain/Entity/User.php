<?php

namespace App\Domain\Entity;

use App\Application\Contracts\GenericIdInterface;
use App\Domain\Dto\WorkspaceProfile;
use App\Infrastructure\Exceptions\NotFoundException;
use App\Infrastructure\Repository\UserRepository;
use App\Infrastructure\Support\GuidBasedImmutableId;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JetBrains\PhpStorm\ArrayShape;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Domain\Entity\Relation;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`users`')]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private UuidInterface $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $username;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\OneToMany(mappedBy: "keeper", targetEntity: "Workspace")]
    private Collection $workspaces;

    #[ORM\OneToMany(mappedBy: "customer", targetEntity: "Card")]
    private Collection $cards;

    #[ORM\OneToMany(mappedBy: "collaborator", targetEntity: "Relation")]
    private Collection $relations;

    public function getId(): GenericIdInterface
    {
        return GuidBasedImmutableId::of((string) $this->id);
    }

    public function setId(GenericIdInterface $id): self
    {
        $this->id = Uuid::fromString((string) $id);
        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    #[ArrayShape(['profileId' => "string", 'name' => "string", 'phone' => "string"])]
    public function profile(): array
    {
        return [
            'profileId' => (string) $this->id,
            'name' => $this->name,
            'identity' => $this->username,
        ];
    }

    public function getWorkspaces(): Collection
    {
        return $this->workspaces;
    }

    public function getWorkspace(GenericIdInterface $workspaceId): Workspace
    {
        $workspace = $this->workspaces->matching(
            Criteria::create()->where(Criteria::expr()?->eq('id', (string) $workspaceId))
        )->first();
        return $workspace instanceof Workspace
            ? $workspace
            : throw new NotFoundException("Workspace $workspaceId not found");
    }

    public function addWorkspace(GenericIdInterface $workspaceId, WorkspaceProfile $workspaceProfile): Workspace
    {
        return $this->workspaces[] = Workspace::create($workspaceId, $this, $workspaceProfile);
    }

    public function getCards(): Collection
    {
        return $this
            ->cards
            ->matching(Criteria::create()
                ->andWhere(Criteria::expr()?->eq('revokedAt', null))
            );
    }

    public function getCard(GenericIdInterface $cardId): Card
    {
        $card = $this
            ->cards
            ->matching(Criteria::create()
                ->where(Criteria::expr()?->eq('id', (string) $cardId))
                ->andWhere(Criteria::expr()?->eq('revokedAt', null))
            );
        return $card instanceof Card
            ? $card
            : throw new NotFoundException("Card $cardId not found");
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
