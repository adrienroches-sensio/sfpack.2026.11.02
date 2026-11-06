<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Loan;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Loan>
 */
class LoanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Loan::class);
    }

    /**
     * @return list<Loan>
     */
    public function listAllForUser(User $user): array
    {
        $qb = $this->createQueryBuilder('loan');

        $qb
            ->andWhere($qb->expr()->eq('loan.user', ':user'))
            ->setParameter('user', $user)
            ->orderBy('loan.dueDate', \SortDirection::Descending)
        ;

        return $qb->getQuery()->getResult();
    }

    public function searchOneByBook(Book $book): Loan|null
    {
        $qb = $this->createQueryBuilder('loan');

        $qb
            ->andWhere($qb->expr()->eq('loan.book', ':book'))
            ->setParameter('book', $book)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }

//    /**
//     * @return Loan[] Returns an array of Loan objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;

//    }
//    public function findOneBySomeField($value): ?Loan
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
