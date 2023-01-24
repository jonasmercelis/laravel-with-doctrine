<?php declare(strict_types=1);

namespace Tests\Unit\Http\Controllers;

use App\Http\Controllers\HomeController;
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
    public function testThatIndexReturnsExpectedResponse(): void
    {
        // ===== Arrange =====
        $sut = new HomeController();

        // ===== Act =====
        $response = $sut->index();

        // ===== Assert =====
        $this->assertNotNull($response);
        $this->assertInstanceOf(expected: Response::class, actual: $response);
        $this->assertEquals(expected: 200, actual: $response->getStatusCode());
    }
}
