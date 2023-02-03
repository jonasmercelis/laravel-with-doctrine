<?php
declare(strict_types=1);

namespace App\Providers;

use Business\Publishing\PublishingService;
use Business\Publishing\PublishingServiceInterface;
use Illuminate\Support\ServiceProvider;
use League\CommonMark\ConverterInterface;
use League\CommonMark\GithubFlavoredMarkdownConverter;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(ConverterInterface::class, function () {

            return new GithubFlavoredMarkdownConverter([
                'html_input' => 'strip',
                'allow_unsafe_links' => false,
            ]);
        });

        $this->app->singleton(PublishingServiceInterface::class, PublishingService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
