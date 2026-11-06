<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\LoanRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LoanController extends AbstractController
{
    #[Route(
        path: '/loans',
        name: 'app_loan_index',
        methods: ['GET'],
    )]
    public function index(LoanRepository $loanRepository): Response
    {
        $loans = $loanRepository->listAll();

        return $this->render('loan/index.html.twig', [
            'loans' => $loans,
        ]);
    }
}
