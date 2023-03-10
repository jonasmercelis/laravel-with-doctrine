<?php
declare(strict_types=1);

namespace App\Console;

use App\Console\Commands\CreateTestUser;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

final class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    { }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    { }

    protected $commands = [
        CreateTestUser::class
    ];
}
