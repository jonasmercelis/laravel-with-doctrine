<?php declare(strict_types=1);

namespace App\Repository;

use App\Entities\Article;
use App\Repository\Contracts\ArticleRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use InvalidArgumentException;
use Symfony\Component\Uid\Uuid;

final readonly class ArticleRepository implements ArticleRepositoryInterface
{
    private EntityManagerInterface $em;
    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(Article::class);
    }

    /** @inheritDoc */
    public function find(Uuid $id): ?Article
    {
        return $this->repository->find($id);
    }

    /** @inheritDoc */
    public function findOneBy(array $criteria, ?array $orderBy = null): ?Article
    {
        return $this->repository->findOneBy($criteria, $orderBy);
    }

    /** @inheritDoc */
    public function findByUuidString(string $uuid): ?Article
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

    /** @inheritDoc */
    public function findBySlug(string $slug): ?Article
    {
        $query = $this->em->createQuery('
            SELECT article
            FROM App\Entities\Article article
            WHERE article.slug = :slug
        ');
        $query->setParameter('slug', $slug);
        try {
            return $query->getSingleResult();
        } catch (NonUniqueResultException|NoResultException) {

            return null;
        }
    }

    public function insertArticle(Article $article): void
    {
        $this->em->persist($article);
    }
}
