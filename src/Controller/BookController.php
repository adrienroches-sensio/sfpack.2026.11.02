<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

final class BookController extends AbstractController
{
    #[Route(
        path: '/books',
        name: 'app_book_index',
        methods: ['GET'],
    )]
    public function index(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->listAll();

        return $this->render('book/index.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route(
        path: '/books/{id}',
        name: 'app_book_show',
        requirements: [
            'id' => Requirement::DIGITS,
        ],
        methods: ['GET'],
        priority: -1,
    )]
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }
}
