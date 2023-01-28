<?php declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AbstractController;
use App\Http\Requests\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\RedirectResponse;

final class AuthenticatedSessionController extends AbstractController
{
    private readonly AuthManager $auth;
    public function __construct(AuthManager $auth)
    {
        $this->auth = $auth;
    }

    public function create(): View
    {
        return view('pages.auth.login');
    }

    /**
     * @throws BindingResolutionException
     * @throws ValidationException
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();
        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
