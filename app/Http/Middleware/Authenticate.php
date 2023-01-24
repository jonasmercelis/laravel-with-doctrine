<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\Request;

final class Authenticate extends Middleware
{
    private readonly UrlGenerator $urlGenerator;

    public function __construct(AuthFactory $authFactory, UrlGenerator $urlGenerator)
    {
        parent::__construct($authFactory);
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  Request  $request
     * @return string|null
     */
    protected function redirectTo($request): ?string
    {
        if (! $request->expectsJson()) {

            return $this->urlGenerator->route('login');
        }

        return null;
    }
}
