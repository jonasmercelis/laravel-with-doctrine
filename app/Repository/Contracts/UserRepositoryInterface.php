<?php declare(strict_types=1);

namespace App\Repository\Contracts;

use App\Entities\User;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;

interface UserRepositoryInterface
{
    /**
     * Find a user by its UUID identifier.
     */
    public function find(Uuid $id): ?User;

    /**
     * Find one user by optional criteria.
     */
    public function findOneBy(array $criteria, ?array $orderBy = null): ?User;

    /**
     * Find a user by the string UUID representation.
     */
    public function findByUuidString(string $uuid): ?User;

    /**
     * Find all users.
     * @return Collection<User>
     */
    public function findAll(): Collection;
}
