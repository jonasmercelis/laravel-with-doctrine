<?php
declare(strict_types=1);

namespace App\Providers;

use App\Entities\User;
use App\Infrastructure\Doctrine\DoctrineAuthenticationProvider;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

final class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @param AuthManager $authManager
     * @return void
     */
    public function boot(AuthManager $authManager): void
    {
        $this->registerPolicies();

        $authManager->provider('doctrine', function (Application $app) {

            return new DoctrineAuthenticationProvider(
                $app->make(Hasher::class),
                $app->make(EntityManagerInterface::class),
                entity: User::class
            );
        });
    }
}
