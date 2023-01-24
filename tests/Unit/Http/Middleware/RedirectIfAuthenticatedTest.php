<?php declare(strict_types=1);

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

final class RedirectIfAuthenticatedTest extends TestCase
{
    /** @test */
    public function test_InitializeCorrectly(): void
    {
        // ===== Arrange =====
        $mockedAuthManager = Mockery::mock(AuthManager::class);
        $mockedRedirector = Mockery::mock(Redirector::class);

        // ===== Act =====
        $sut = new RedirectIfAuthenticated($mockedAuthManager, $mockedRedirector);

        // ===== Assert =====
        $this->assertInstanceOf(expected: RedirectIfAuthenticated::class, actual: $sut);
    }

    /**
     * @test
     * @dataProvider provideRedirectIfAuthenticatedTestData
     */
    public function test_ShouldRedirectIfAuthenticated(bool $isAuthenticated): void
    {
        // ===== Arrange =====
        $mockedAuthManager = Mockery::mock(AuthManager::class);
        $mockedRedirector = Mockery::mock(Redirector::class);
        $mockedRequest = Mockery::mock(Request::class);
        $mockedGuard = Mockery::mock(Guard::class);
        $guards = [$mockedGuard];

        $mockedAuthManager->shouldReceive('guard')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf();

        $mockedAuthManager->shouldReceive('check')
            ->once()
            ->withNoArgs()
            ->andReturn($isAuthenticated);

        $mockedRedirector->shouldReceive('to')
            ->withAnyArgs()
            ->andReturn(new RedirectResponse("dummyResponse"));

        $sut = new RedirectIfAuthenticated($mockedAuthManager, $mockedRedirector);

        // ===== Act =====
        $result = $sut->handle($mockedRequest, function () {

            return new Response("dummyContent");
        }, $guards);

        // ===== Assert =====
        $this->assertNotNull($result);
        $this->assertInstanceOf(expected: Response::class, actual: $result);

        // If authenticated, we expect a redirect so the response should be an instance of redirector.
        if ($isAuthenticated) {

            $this->assertInstanceOf(RedirectResponse::class, $result);
        } else {

            $this->assertNotInstanceOf(RedirectResponse::class, $result);
        }
    }

    /**
     * @return array[]
     */
    private function provideRedirectIfAuthenticatedTestData(): array
    {
        return [
            [true], // Authenticated, expect redirection.
            [false] // Not authenticated, no redirection.
        ];
    }
}
