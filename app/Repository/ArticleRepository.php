<?php declare(strict_types=1);

namespace App\Repository;

use App\Entities\Article;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use InvalidArgumentException;
use Symfony\Component\Uid\Uuid;

final class ArticleRepository extends EntityRepository implements ArticleRepositoryInterface
{
    /** @inheritDoc */
    public function findByUuidString(string $uuid): ?Article
    {
        try {
            // 'new Uuid' will throw an exception if the given uuid is not valid.
            return $this->find(new Uuid($uuid));
        } catch (InvalidArgumentException) {

            return null;
        }
    }

    /** @inheritDoc */
    public function findBySlug(string $slug): ?Article
    {
        $query = $this->_em->createQuery('
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
}
