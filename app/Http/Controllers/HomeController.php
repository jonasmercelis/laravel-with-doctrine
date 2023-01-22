<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

final class HomeController extends AbstractController
{
    public function index(): Response
    {
        return new Response("Ho ho how");
    }
}
