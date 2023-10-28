<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }


    public function SearchBookWithRef($username){

        return $this->createQueryBuilder('b')
            ->where('b.ref like :ref')
            ->setParameter('ref',$username)
            ->getQuery()
            ->getResult();
            
    }

    public function orderbyAuthorName(){

        return $this->createQueryBuilder('b')
            ->orderBy('b.Author','ASC')
            ->getQuery()
            ->getResult();

    }

    public function findBooksPublishedBefore2023WhereAuthorsWhoHaveMoreThan35Books(){

        return $this->createQueryBuilder('b')
            ->join('b.Author', 'a')
            ->where('b.PublicationDate < :date')
            ->andWhere('a.nbbooks > 35')
            ->setParameter('date','2023-01-01')
            ->getQuery()
            ->getResult();

    }

    // public function updateCategoryForWilliamShakespeare($newCategory)
    // {
    //     return $this->createQueryBuilder('b')
    //         ->join('b.Author', 'a')
    //         ->set('b.Category', ':newCategory')
    //         ->where('a.username = :authorName')
    //         ->setParameter('newCategory', $newCategory)
    //         ->setParameter('authorName', 'William Shakespear')
    //         ->getQuery()
    //         ->execute();
    // }
    
    public function TheTotalSumOfScienceFictionBooks()
{
    $em = $this->getEntityManager();
    $query = $em->createQuery('
        SELECT COUNT(b.ref)
        FROM App\Entity\Book b
        WHERE b.Category = :Category
    ')
        ->setParameter('Category', 'Science-Fiction');

    return $query->getSingleScalarResult();
}

public function findBooksPublishedBetweenTwoDates()
{
    $em = $this->getEntityManager();

    $query = $em->createQuery('
        SELECT b
        FROM App\Entity\Book b
        WHERE b.PublicationDate BETWEEN :FirstDate AND :SecondDate
    ')
        ->setParameter('FirstDate', '2021-01-01')
        ->setParameter('SecondDate', '2022-12-31');

    return $query->getResult();
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
