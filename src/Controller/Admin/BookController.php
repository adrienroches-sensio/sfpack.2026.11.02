<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
final class BookController extends AbstractController
{
    #[Route(
        path: '/admin/books',
        name: 'admin_book_index',
        methods: ['GET']
    )]
    public function index(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->listAll();

        return $this->render('admin/book/index.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route(
        path: '/admin/books/new',
        name: 'admin_book_new',
        methods: ['GET', 'POST'],
    )]
    #[Route(
        path: '/admin/books/{id}/edit',
        name: 'admin_book_edit',
        requirements: [
            'id' => Requirement::DIGITS,
        ],
        methods: ['GET', 'POST'],
    )]
    public function new(Request $request, EntityManagerInterface $em, Book|null $book = null): Response
    {
        $isCreate = null === $book;
        $book ??= new Book();

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
        }

        return $this->render('admin/book/new.html.twig', [
            'book_form' => $form,
            'is_create' => $isCreate,
        ]);
    }
}
