<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Form\BookType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BookController extends AbstractController
{
    #[Route(
        path: '/admin/books/new',
        name: 'admin_book_new',
        methods: ['GET', 'POST'],
    )]
    #[Route(
        path: '/admin/books/{id}/edit',
        name: 'admin_book_edit',
        methods: ['GET', 'POST'],
    )]
    public function new(Request $request, Book|null $book = null): Response
    {
        $isCreate = null === $book;
        $book ??= new Book();

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
        }

        return $this->render('admin/book/new.html.twig', [
            'book_form' => $form,
            'is_create' => $isCreate,
        ]);
    }
}
