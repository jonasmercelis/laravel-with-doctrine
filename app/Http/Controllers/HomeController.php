<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;

final class HomeController extends AbstractController
{
    private readonly ViewFactory $view;
    public function __construct(ViewFactory $view)
    {
        $this->view = $view;
    }

    public function index(): View
    {
        return $this->view->make('pages.home.index');
    }
}
