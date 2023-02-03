<?php declare(strict_types=1);

namespace Business\Publishing;

use App\Dto\StoreArticleDto;
use App\Entities\Article;
use Doctrine\Common\Collections\Collection;

interface PublishingServiceInterface
{
    /**
     * Get all articles.
     * @return Collection<Article>
     */
    public function getAllArticles(): Collection;

    /** Processes a new request to create an article. */
    public function createArticle(StoreArticleDto $storeArticleDto): Article;
}
