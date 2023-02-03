<?php declare(strict_types=1);

namespace Business\Publishing;

use App\Dto\StoreArticleDto;
use App\Entities\Article;
use App\Repository\Contracts\ArticleRepositoryInterface;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\Str;

final readonly class PublishingService implements PublishingServiceInterface
{
    private ArticleRepositoryInterface $articleRepository;
    private UserRepository $userRepository;
    private EntityManagerInterface $em;

    public function __construct(
        ArticleRepositoryInterface $articleRepository,
        UserRepository $userRepository,
        EntityManagerInterface $em
    )
    {
        $this->articleRepository = $articleRepository;
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    public function getAllArticles(): Collection
    {
        return $this->articleRepository->findAll();
    }

    /** @inheritDoc */
    public function createArticle(StoreArticleDto $storeArticleDto): Article
    {
        // Generate the slug.
        $slug = $this->generateSlugRandomPostfix($storeArticleDto->title);

        $authenticatedUserId = $this->userRepository->find($storeArticleDto->authUserIdentifier);

        $article = new Article();
        $article->setTitle($storeArticleDto->title);
        $article->setSlug($slug);
        $article->setText($storeArticleDto->text);

        // Set the authenticated user as the author
        $article->setAuthor($authenticatedUserId);

        // Persist
        $this->articleRepository->insertArticle($article);
        $this->em->flush();

        return $article;
    }

    /**
     * Generates a slug with a 6 characters random postfix.
     * The postfix is to mitigate the change on collisions.
     * (A slug is a URL/SEO friendly string.)
     * @see https://en.wikipedia.org/wiki/Clean_URL#Slug
     */
    private function generateSlugRandomPostfix(string $text): string
    {
        return Str::slug($text) . '-' . Str::random(6);
    }
}
