<?php

namespace App\Repository;

use App\Entity\Book;
use App\Search\Book\BookSearchCriteria;
use App\Search\Book\BookSortableColumn;
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
     * @return list<Book>
     */
    public function search(BookSearchCriteria $criteria): array
    {
        $qb = $this->createQueryBuilder('book');

        if ($criteria->q !== null) {
            $qb = $qb
                ->andWhere($qb->expr()->like('book.title', ':q'))
                ->setParameter('q', "%{$criteria->q}%")
            ;
        }

        if (
            $criteria->author !== null
            || $criteria->sort === BookSortableColumn::Author
        ) {
            $qb = $qb
                ->innerJoin('book.authors', 'author')
            ;
        }

        if ($criteria->author !== null) {
            $qb = $qb
                ->andWhere($qb->expr()->like('author.name', ':author'))
                ->setParameter('author', "%{$criteria->author}%")
            ;
        }

        if ($criteria->genre !== null) {
            $qb = $qb
                ->innerJoin('book.genres', 'genre')
                ->andWhere($qb->expr()->like('genre.name', ':genre'))
                ->setParameter('genre', "%{$criteria->genre}%")
            ;
        }

        if ($criteria->available !== null) {
            $qb = $qb
                ->andWhere($qb->expr()->eq('book.available', ':available'))
                ->setParameter('available', $criteria->available)
            ;
        }

        if ($criteria->sort !== null) {
            $qb = $qb
                ->orderBy($criteria->sort->queryColumn(), $criteria->direction)
            ;
        }

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
