<?php declare(strict_types=1);

namespace App\Providers;

use App\Entities\Article;
use App\Repository\ArticleRepository;
use App\Repository\ArticleRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

final class EntityRepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(ArticleRepositoryInterface::class, function (Application $app) {

            /** @var EntityManagerInterface $em */
            $em = $app->get(EntityManagerInterface::class);
            return new ArticleRepository(
                $em,
                $em->getClassMetadata(Article::class)
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    { }
}
