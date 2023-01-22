<?php
declare(strict_types=1);

namespace App\Providers;

use App\Http\Controllers\HomeController;
use Illuminate\Cache\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;

final class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    private readonly RateLimiter $rateLimiter;

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        // Resolve rateLimiter from container.
        $this->rateLimiter = $this->app->make(RateLimiter::class);

        $this->configureRateLimiting();

        $this->routes(function (Router $router) {
            $router->middleware('api')
                ->prefix('api')
                ->group(fn(Router $router) => $this->mapApiRoutes($router));

            $router->middleware('web')
                ->group(fn(Router $router) => $this->mapWebRoutes($router));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting(): void
    {
        // Create a rate limiter for key 'api'
        // It will be defined by IP alone, or, by the combination of user id and IP address if available.
        // TODO Make sure this doesn't conflict with the entity access of Doctrine,
        // given that entity properties MUST be protected or private.
        // This is probably solved by using the designated getter method.
        $this->rateLimiter->for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    private function mapWebRoutes(Router $router): void
    {
        $router->get('/', [HomeController::class, 'index']);
    }

    private function mapApiRoutes(Router $router): void
    {
        $router->middleware('auth:sanctum')->group(function (Router $router) {

            $router->get('/user', function (Request $request) {

                return $request->user();
            });
        });
    }
}
