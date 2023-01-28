<?php declare(strict_types=1);

namespace App\Entities;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Illuminate\Contracts\Auth\Authenticatable;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
class User implements Authenticatable, \Stringable
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'email', type: 'string', length: 180, unique: true)]
    private string $email;

    #[ORM\Column(name: 'password', type: 'string', nullable: true)]
    private ?string $password = null;

    #[ORM\Column(name: 'remember_token', type: 'string', nullable: true)]
    private ?string $rememberToken = null;

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
    public function getAuthIdentifier(): ?int
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

    /** @inheritDoc */
    public function __toString(): string
    {
        return "ID: $this->id. email: $this->email";
    }
}
