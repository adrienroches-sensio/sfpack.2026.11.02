<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BookController extends AbstractController
{
    #[Route(
        path: '/books/{slug}',
        name: 'app_book_show'
    )]
    public function show(string $slug): Response
    {
        return new Response("Slug: {$slug}");
    }

    #[Route(
        path: '/books/new',
        name: 'app_book_new'
    )]
    public function new(): Response
    {
        return new Response('new');
    }
}
