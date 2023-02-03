<?php declare(strict_types=1);

namespace App\Repository\Contracts;

use App\Entities\Article;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;

interface ArticleRepositoryInterface
{
    /**
     * Find one article based on their identifier.
     * @param Uuid $id
     * @return Article|null
     */
    public function find(Uuid $id): ?Article;

    /**
     * @return Collection<Article>
     */
    public function findAll(): Collection;

    /** Find an article by specified criteria. */
    public function findOneBy(array $criteria, ?array $orderBy = null): ?Article;

    /**
     * Find an article based on the UUID identifier.
     */
    public function findByUuidString(string $uuid): ?Article;

    /** Find an article based on their slug. */
    public function findBySlug(string $slug): ?Article;

    /** Inserts an article entity. */
    public function insertArticle(Article $article): void;
}
