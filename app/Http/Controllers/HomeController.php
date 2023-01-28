<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

final class HomeController extends AbstractController
{
    public function index(): View
    {
        return view('pages.home.index');
    }
}
