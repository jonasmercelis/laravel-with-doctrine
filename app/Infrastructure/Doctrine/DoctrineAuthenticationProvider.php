<?php declare(strict_types=1);

namespace App\Infrastructure\Doctrine;

use App\Entities\User;
use App\Repository\Contracts\UserRepositoryInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionException;

final readonly class DoctrineAuthenticationProvider implements UserProvider
{
    private Hasher $hasher;
    private EntityManagerInterface $em;
    private string $entity;
    private UserRepository $userRepository;

    public function __construct(
        Hasher $hasher,
        EntityManagerInterface $em,
        string $entity,
        UserRepositoryInterface $userRepository
    )
    {
        $this->hasher = $hasher;
        $this->em = $em;
        $this->entity = $entity;
        $this->userRepository = $userRepository;
    }

    /**
     * @inheritDoc
     */
    public function retrieveById($identifier): ?User
    {
        return $this->userRepository->find($identifier);
    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function retrieveByToken($identifier, $token): ?User
    {
        return $this->userRepository->findOneBy([
            $this->getEntity()->getAuthIdentifierName() => $identifier,
            $this->getEntity()->getRememberTokenName()  => $token
        ]);
    }

    /**
     * @inheritDoc
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        $user->setRememberToken($token);
        $this->em->persist($user);
        $this->em->flush($user);
    }

    /**
     * @inheritDoc
     */
    public function retrieveByCredentials(array $credentials): ?\App\Entities\User
    {
        $criteria = [];
        foreach ($credentials as $key => $value) {

            if (!Str::contains($key, 'password')) {

                $criteria[$key] = $value;
            }
        }

        return $this->userRepository->findOneBy($criteria);
    }

    /**
     * @inheritDoc
     */
    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        return $this->hasher->check($credentials['password'], $user->getAuthPassword());
    }

    /**
     * @throws ReflectionException
     */
    private function getEntity(): Authenticatable
    {
        $reflection = new ReflectionClass($this->entity);
        return $reflection->newInstanceWithoutConstructor();
    }
}
