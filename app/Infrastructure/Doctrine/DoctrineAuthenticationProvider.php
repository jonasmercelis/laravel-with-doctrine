<?php declare(strict_types=1);

namespace App\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionException;

final class DoctrineAuthenticationProvider implements UserProvider
{
    private readonly Hasher $hasher;
    private readonly EntityManagerInterface $em;
    private readonly string $entity;

    public function __construct(Hasher $hasher, EntityManagerInterface $em, string $entity)
    {
        $this->hasher = $hasher;
        $this->em = $em;
        $this->entity = $entity;
    }

    /**
     * @inheritDoc
     */
    public function retrieveById($identifier)
    {
        return $this->getRepository()->find($identifier);
    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function retrieveByToken($identifier, $token)
    {
        return $this->getRepository()->findOneBy([
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
    public function retrieveByCredentials(array $credentials): ?Authenticatable
    {
        $criteria = [];
        foreach ($credentials as $key => $value) {

            if (!Str::contains($key, 'password')) {

                $criteria[$key] = $value;
            }
        }

        return  $this->getRepository()->findOneBy($criteria);
    }

    /**
     * @inheritDoc
     */
    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        return $this->hasher->check($credentials['password'], $user->getAuthPassword());
    }

    private function getRepository(): EntityRepository
    {
        return $this->em->getRepository($this->entity);
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
