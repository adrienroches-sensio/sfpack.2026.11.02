<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

final class AuthorController extends AbstractController
{
    #[Route(
        path: '/authors',
        name: 'app_author_index',
        methods: ['GET'],
    )]
    public function index(AuthorRepository $authorRepository): Response
    {
        $authors = $authorRepository->findAll();

        return $this->render('author/index.html.twig', [
            'authors' => $authors,
        ]);
    }

    #[Route(
        path: '/authors/{id}',
        name: 'app_author_show',
        requirements: [
            'id' => Requirement::DIGITS,
        ],
        methods: ['GET'],
    )]
    public function show(AuthorRepository $authorRepository, int $id): Response
    {
        $author = $authorRepository->find($id);

        return $this->render('author/show.html.twig', [
            'author' => $author,
        ]);
    }
}
