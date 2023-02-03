<?php declare(strict_types=1);

namespace Tests\Unit\Http\Controllers;

use App\Http\Controllers\HomeController;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

/** @test */
final class HomeControllerTest extends TestCase
{
    /**
     * @test
     * Test that the home controller returns a correct response.
     * @see HomeController
     */
    public function testThatIndexReturnsView(): void
    {
        // ===== Arrange =====
        $mockedView = Mockery::mock(View::class);
        $mockedViewFactory = Mockery::mock(Factory::class);
        $mockedViewFactory->shouldReceive('make')
            ->once()
            ->withArgs(['pages.home.index'])
            ->andReturn($mockedView);
        $sut = new HomeController($mockedViewFactory);

        // ===== Act =====
        $response = $sut->index();

        // ===== Assert =====
        $this->assertNotNull($response);
        $this->assertInstanceOf(expected: View::class, actual: $response);
    }
}
