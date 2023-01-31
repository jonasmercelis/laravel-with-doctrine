<?php declare(strict_types=1);

namespace App\Repository;

use App\Entities\Article;

interface ArticleRepositoryInterface
{
    /**
     * Find an Article by its UUID string.
     * If the UUID string is not valid, Null will be returned.
     * If the Entity is not found by its ID, null will be returned.
     */
    public function findByUuidString(string $uuid): ?Article;

    /**
     * Find an Article by its slug.
     * If no one is found, null will be returned.
     */
    public function findBySlug(string $slug): ?Article;
}
