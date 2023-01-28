<?php declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Auth\AuthManager;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Cache\RateLimiter;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

final class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string']
        ];
    }

    /**
     * @throws BindingResolutionException
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! $this->authManager()->attempt(
            credentials: $this->only('email', 'password'),
            remember: $this->boolean('remember'))
        ) {

            $this->rateLimiter()->hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => trans('auth.failed')
            ]);
        }

        $this->rateLimiter()->clear($this->throttleKey());
    }

    /**
     * @throws BindingResolutionException
     * @throws ValidationException
     */
    private function ensureIsNotRateLimited(): void
    {
        if (! $this->rateLimiter()->tooManyAttempts($this->throttleKey(), maxAttempts: 5)) {

            return;
        }

        // Dispatch a new lockout event.
        $this->events()->dispatch(new Lockout($this));

        $seconds = $this->rateLimiter()->availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60)
            ])
        ]);
    }

    private function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')).'|'.$this->ip());
    }

    /**
     * @throws BindingResolutionException
     */
    private function rateLimiter(): RateLimiter
    {
        return Container::getInstance()->make(RateLimiter::class);
    }

    /**
     * @throws BindingResolutionException
     */
    private function authManager(): AuthManager
    {
        return Container::getInstance()->make(AuthManager::class);
    }

    /**
     * @throws BindingResolutionException
     */
    private function events(): EventDispatcher
    {
        return app()->make(EventDispatcher::class);
    }
}
