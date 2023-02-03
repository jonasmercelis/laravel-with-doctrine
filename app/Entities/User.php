<?php declare(strict_types=1);

namespace App\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Illuminate\Contracts\Auth\Authenticatable;
use Stringable;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User implements Authenticatable, Stringable
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    #[ORM\Column(name: 'email', type: 'string', length: 180, unique: true)]
    private string $email;

    #[ORM\Column(name: 'password', type: 'string', nullable: true)]
    private ?string $password = null;

    #[ORM\Column(name: 'remember_token', type: 'string', nullable: true)]
    private ?string $rememberToken = null;

    /** @var Collection<int, Article> */
    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Article::class)]
    private Collection $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /** @inheritDoc */
    public function getAuthIdentifierName(): string
    {
        return 'id';
    }

    /** @inheritDoc */
    public function getAuthIdentifier(): ?Uuid
    {
        return $this->id;
    }

    /** @inheritDoc */
    public function getAuthPassword(): ?string
    {
        return $this->password;
    }

    /** @inheritDoc */
    public function getRememberToken(): ?string
    {
        return $this->rememberToken;
    }

    /** @inheritDoc */
    public function setRememberToken($value): void
    {
        $this->rememberToken = $value;
    }

    /** @inheritDoc */
    public function getRememberTokenName(): string
    {
        return 'rememberToken';
    }

    public function setArticles(Collection $articles): void
    {
        $this->articles = $articles;
    }

    /**
     * @return Collection<Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    /** @inheritDoc */
    public function __toString(): string
    {
        return "ID: $this->id. email: $this->email";
    }
}
