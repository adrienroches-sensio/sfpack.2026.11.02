<?php

declare(strict_types=1);

namespace App\Loan;

use App\Entity\Book;
use App\Entity\Loan;
use App\Entity\User;
use App\Loan\Event\LoanCreatedEvent;
use App\Repository\LoanRepository;
use DateInterval;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use RuntimeException;

final class LoanManager
{
    private const DEFAULT_DURATION = 'P14D'; // 14 days

    public function __construct(
        private EntityManagerInterface $em,
        private LoanRepository $loanRepository,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function createLoan(User $user, Book $book): Loan
    {
        if (!$book->isAvailable()) {
            throw new RuntimeException('Book is not available for loan.');
        }

        $loan = $this->loanRepository->searchOneByBook($book);

        if ($loan !== null) {
            if ($user === $loan->getUser()) {
                // log: trying to loan the same book to the same person

                return $loan;
            }

            throw new RuntimeException('Book is already loaned to someone else.');
        }

        $loan = new Loan();
        $loan->setUser($user);
        $loan->setBook($book);
        $loan->setLoanDate(new DateTimeImmutable());
        $loan->setDueDate((new DateTimeImmutable())->add(new DateInterval(self::DEFAULT_DURATION)));
        $loan->setStatus(LoanStatus::Active);
        $this->em->persist($loan);

        $book->setAvailable(false);
        $this->em->persist($book);

        $this->em->flush();

        $this->eventDispatcher->dispatch(new LoanCreatedEvent($loan));

        return $loan;
    }

    public function returnLoan(Loan $loan): void
    {
        $loan->setReturnDate(new DateTimeImmutable());
        $loan->setStatus(LoanStatus::Returned);
        $this->em->persist($loan);

        $loan->getBook()->setAvailable(true);
        $this->em->persist($loan->getBook());

        $this->em->flush();
    }
}
