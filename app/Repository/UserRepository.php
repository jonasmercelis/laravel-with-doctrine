<?php declare(strict_types=1);

namespace App\Repository;

use App\Entities\User;
use App\Repository\Contracts\UserRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use InvalidArgumentException;
use Symfony\Component\Uid\Uuid;

final readonly class UserRepository implements UserRepositoryInterface
{
    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(User::class);
    }

    /** @inheritDoc */
    public function find(Uuid $id): ?User
    {
        return $this->repository->find($id);
    }

    /** @inheritDoc */
    public function findOneBy(array $criteria, ?array $orderBy = null): ?User
    {
        return $this->repository->findOneBy($criteria, $orderBy);
    }

    /** @inheritDoc */
    public function findByUuidString(string $uuid): ?User
    {
        try {
            // 'new Uuid' will throw an exception if the given uuid is not valid.
            return $this->repository->find(new Uuid($uuid));
        } catch (InvalidArgumentException) {

            return null;
        }
    }

    /** @inheritDoc */
    public function findAll(): Collection
    {
        return new ArrayCollection($this->repository->findAll());
    }
}
