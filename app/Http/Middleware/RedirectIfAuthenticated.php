<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Symfony\Component\HttpFoundation\Response;

final class RedirectIfAuthenticated
{
    private readonly AuthManager $auth;
    private readonly Redirector $redirector;

    public function __construct(AuthManager $auth, Redirector $redirector)
    {
        $this->auth = $auth;
        $this->redirector = $redirector;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response) $next
     * @param  Guard[]  ...$guards
     * @return Response
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                return $this->redirector->to(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
