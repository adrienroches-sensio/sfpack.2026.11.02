<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function array_key_exists;
use function Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @return list<Book>
     */
    public function listAll(): array
    {
        return $this->findAll();
    }

    /**
     * @param array{
     *     "q"?: string,
     *     "author"?: string,
     *     "available"?: bool,
     * } $criteria
     *
     * @return list<Book>
     */
    public function search(array $criteria): array
    {
        $qb = $this->createQueryBuilder('book');

        if (array_key_exists('q', $criteria) === true) {
            $qb = $qb
                ->andWhere($qb->expr()->like('book.title', ':q'))
                ->setParameter('q', "%{$criteria['q']}%")
            ;
        }

        if (array_key_exists('author', $criteria) === true) {
            $qb = $qb
                ->innerJoin('book.authors', 'author')
                ->andWhere($qb->expr()->like('author.name', ':author'))
                ->setParameter('author', "%{$criteria['author']}%")
            ;
        }

        if (array_key_exists('available', $criteria) === true) {
            $qb = $qb
                ->andWhere($qb->expr()->eq('book.available', ':available'))
                ->setParameter('available', $criteria['available'])
            ;
        }

        $qb = $qb
            ->orderBy('book.title', \SortDirection::Ascending)
        ;

        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
