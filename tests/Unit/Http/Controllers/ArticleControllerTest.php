<?php declare(strict_types=1);

namespace Tests\Unit\Http\Controllers;

use App\Http\Controllers\ArticleController;
use App\Repository\ArticleRepository;
use App\Repository\ArticleRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use League\CommonMark\ConverterInterface;
use Mockery;
use PHPUnit\Framework\TestCase;


final class ArticleControllerTest extends TestCase
{
    /**
     * @test
     * @return void
     * @see ArticleController
     */
    public function testThatSutCanBeInstantiated(): void
    {
        // ===== Arrange =====
        $mockedEntityManager = Mockery::mock(EntityManagerInterface::class);
        $mockedMarkDownConverter = Mockery::mock(ConverterInterface::class);
        $mockedArticleRepository = Mockery::mock(ArticleRepositoryInterface::class);

        // ===== Act =====
        $sut = new ArticleController(
            $mockedEntityManager,
            $mockedMarkDownConverter,
            $mockedArticleRepository
        );

        // ===== Assert =====
        $this->assertInstanceOf(ArticleController::class, $sut);
    }
}
