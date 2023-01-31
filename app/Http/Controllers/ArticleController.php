<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Entities\Article;
use App\Repository\ArticleRepository;
use App\Repository\ArticleRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use League\CommonMark\ConverterInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ArticleController extends AbstractController
{
    private readonly EntityManagerInterface $em;
    private readonly ConverterInterface $mdConverter;
    private readonly ArticleRepositoryInterface $articleRepository;

    public function __construct(
        EntityManagerInterface $em,
        ConverterInterface $mdConverter,
        ArticleRepositoryInterface $articleRepository
    )
    {
        $this->em = $em;
        $this->mdConverter = $mdConverter;
        $this->articleRepository = $articleRepository;
    }

    public function index(): View
    {
        $articles = new ArrayCollection($this->articleRepository->findAll());
        return view('pages.articles.index', compact('articles'));
    }

    public function create(): View
    {
        return view('pages.articles.create');
    }

    public function store(Request $request): Response
    {
        $validated = $request->validate([
            'title' => ['required', 'min:3'],
            'text' => [''],
        ]);

        // Create slug
        $slug = Str::slug($validated['title']) . '-' . Str::random(6);

        $article = new Article();
        $article->setSlug($slug);
        $article->setTitle($validated['title']);
        $article->setText($validated['text']);
        $article->setAuthor($request->user());

        $this->em->persist($article);
        $this->em->flush($article);
        return \response()->redirectToRoute('articles.edit', ['slug' => $article->getSlug()]);
    }

    public function show(string $slug): View
    {
        /** @var Article $article */
        $article = $this->articleRepository->findBySlug($slug) ?? throw new NotFoundHttpException();
        $convertedHtml = is_null($article->getText()) ? null : $this->mdConverter->convert($article->getText());
        return \view('pages.articles.show', compact('article', 'convertedHtml'));
    }

    public function edit(string $slug): View
    {
        /** @var Article $article */
        $article = $this->articleRepository->findBySlug($slug);
        return \view('pages.articles.edit', compact('article'));
    }

    public function update(string $id, Request $request): Response
    {
        $validated = $request->validate([
            'title' => ['required', 'min:3'],
            'text' => [''],
        ]);

        $article = $this->articleRepository->findByUuidString($id) ?? throw new NotFoundHttpException();

        $article->setTitle($validated['title']);
        $article->setText($validated['text']);
        $this->em->persist($article);
        $this->em->flush($article);

        return \response()->redirectToRoute('articles.show', ['slug' => $article->getSlug()]);
    }
}
