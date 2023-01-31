<?php declare(strict_types=1);

namespace App\Providers;

use App\Infrastructure\Doctrine\DoctrineService;
use App\Infrastructure\Doctrine\DoctrineServiceInterface;
use Clockwork\Clockwork;
use Clockwork\DataSource\DoctrineDataSource;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

final class DoctrineServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        // Register the Doctrine service
        $this->app->singleton(DoctrineServiceInterface::class, DoctrineService::class);

        // Register the entity manager
        $this->app->singleton(EntityManagerInterface::class, function (Application $app) {

            /** @var DoctrineServiceInterface $doctrineService */
            $doctrineService = $app->make(DoctrineServiceInterface::class);
            return $doctrineService->getEntityManager();
        });
    }

    /**
     * Bootstrap services.
     *
     * @throws BindingResolutionException
     */
    public function boot(DoctrineServiceInterface $doctrineService, Application $app, Clockwork $clockwork): void
    {
        $doctrineService->initialize();

        /** @var EntityManagerInterface $em */
        $em = $app->make(EntityManagerInterface::class);

        if ($app->environment() !== 'prod') {

            $clockwork->addDataSource(
                new DoctrineDataSource($em)
            );
        }
    }
}
