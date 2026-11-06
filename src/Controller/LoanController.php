<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Loan;
use App\Entity\User;
use App\Loan\LoanManager;
use App\Repository\LoanRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED')]
final class LoanController extends AbstractController
{
    #[Route(
        path: '/loans',
        name: 'app_loan_index',
        methods: ['GET'],
    )]
    public function index(LoanRepository $loanRepository, #[CurrentUser] User $user): Response
    {
        $loans = $loanRepository->listAllForUser($user);

        return $this->render('loan/index.html.twig', [
            'loans' => $loans,
        ]);
    }

    #[Route(
        path: '/loans/new/{id}',
        name: 'app_loan_new',
        methods: ['POST'],
    )]
    public function new(LoanManager $loanManager, #[CurrentUser] User $user, Book $book): Response
    {
        $loanManager->createLoan($user, $book);

        return $this->redirectToRoute('app_loan_index');
    }

    #[Route(
        path: '/loans/{id}/return',
        name: 'app_loan_return',
        methods: ['POST']
    )]
    public function return(Loan $loan, LoanManager $manager): Response
    {
        if ($loan->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You can only return your own loans.');
        }

        $manager->returnLoan($loan);

        return $this->redirectToRoute('app_loan_index');
    }
}
