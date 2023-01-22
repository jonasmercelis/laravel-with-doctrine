<?php
declare(strict_types=1);

namespace App\Providers;

use Illuminate\Broadcasting\Broadcasters\Broadcaster;
use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Support\ServiceProvider;

final class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     */
    public function boot(BroadcastManager $broadcastManager, Broadcaster $broadcaster): void
    {
        $broadcastManager->routes();

        // TODO: Complete implementation once Doctrine is setup.
        $broadcaster->channel('User.{id}', function () {

            return true;
        });
    }
}
