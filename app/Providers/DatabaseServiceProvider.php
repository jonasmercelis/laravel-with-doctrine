<?php declare(strict_types=1);

namespace App\Providers;

use App\Infrastructure\Database\DatabaseService;
use App\Infrastructure\Database\DatabaseServiceInterface;
use Illuminate\Foundation\Application;
use Illuminate\Support\Env;
use Illuminate\Support\ServiceProvider;

final class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     */
    public function register(): void
    {
        $this->app->singleton(
            DatabaseServiceInterface::class,
            function () {

                return new DatabaseService(
                    host: Env::get('DB_HOST'),
                    username: Env::get('DB_USERNAME'),
                    password: Env::get('DB_PASSWORD'),
                    port: (int)Env::get('DB_PORT'),
                    database: Env::get('DB_DATABASE'),
                    connection: Env::get('DB_CONNECTION')
                );
            }
        );
    }

    /**
     * Bootstrap services.
     *
     */
    public function boot(): void
    { }
}
