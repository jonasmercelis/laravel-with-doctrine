<?php declare(strict_types=1);

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\Authenticate;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\Request;
use Mockery;
use PHPUnit\Framework\TestCase;

final class AuthenticateTest extends TestCase
{
    public function test_InitializeCorrectly(): void
    {
        // ===== Arrange =====
        $mockedAuthFactory = Mockery::mock(AuthFactory::class);
        $mockedUrlGenerator = Mockery::mock(UrlGenerator::class);

        // ===== Act =====
        $sut = new Authenticate($mockedAuthFactory, $mockedUrlGenerator);

        // ===== Assert =====
        $this->assertInstanceOf(expected: Authenticate::class, actual: $sut);
    }

    /**
     * @test
     * @dataProvider provideRedirectExpectationData
     */
    public function test_NoRedirectionWhenRequestExpectsJson(bool $expectsJson, ?string $redirectToValue): void
    {
        // ===== Arrange =====
        $mockedUrlGenerator = Mockery::mock(UrlGenerator::class);
        $mockedAuthFactory = Mockery::mock(AuthFactory::class);
        $mockedRequest = Mockery::mock(Request::class);
        $mockedGuard = Mockery::mock(Guard::class);
        $guards = [$mockedGuard];

        $mockedUrlGenerator->shouldReceive('route')
            ->withArgs(['login'])
            ->andReturn($redirectToValue);

        $mockedAuthFactory->shouldReceive('guard')
            ->withAnyArgs()
            ->andReturnSelf();

        $mockedAuthFactory->shouldReceive('check')
            ->withNoArgs()
            ->andReturnFalse();

        // When JSON is expected,
        // ..authenticationException should not include a redirect to route.
        $mockedRequest->shouldReceive('expectsJson')
            ->withNoArgs()
            ->andReturn($expectsJson);

        $sut = new Authenticate($mockedAuthFactory, $mockedUrlGenerator);

        // ===== Act =====
        $exception = null;
        try {
            $sut->handle($mockedRequest, function (Mockery\MockInterface $mock) { }, $guards);
        } catch (AuthenticationException $e) {

            $exception = $e;
        }

        // ===== Assert =====
        $this->assertNotNull($exception);
        $this->assertInstanceOf(AuthenticationException::class, $exception);

        // Expect null
        $this->assertEquals(expected: $redirectToValue, actual: $exception->redirectTo());
    }

    private function provideRedirectExpectationData(): array
    {
        return [
            [true, null], // Expects JSON, no redirection.
            [false, 'dummyRoute'] // No Json expected, redirection.
        ];
    }
}
