<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

final class BookController extends AbstractController
{
    #[Route(
        path: '/books/{slug}',
        name: 'app_book_show',
        requirements: [
            'slug' => Requirement::ASCII_SLUG,
        ],
        methods: ['GET'],
        priority: -1,
    )]
    public function show(string $slug): Response
    {
        return new Response("Slug: {$slug}");
    }

    #[Route(
        path: '/books/new',
        name: 'app_book_new',
        methods: ['GET'],
    )]
    public function new(): Response
    {
        return new Response('new');
    }
}
