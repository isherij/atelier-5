<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 *
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }


    public function orderbyusername(){

        return $this->createQueryBuilder('a')
            ->orderBy('a.username','DESC')
            ->getQuery()
            ->getResult();

    }




    public function sarchwithalph(){

        return $this->createQueryBuilder('a')
            ->where('a.username like :username')
            ->setParameter('username','t%')
            ->getQuery()
            ->getResult();
            
    }


    public function showWithAlphabetADR_MAIL(){

        return $this->createQueryBuilder('a')
            ->orderBy('a.email','ASC')
            ->getQuery()
            ->getResult();
            
    }
    public function MinMax($min, $max)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT a
            FROM App\Entity\Author a
            WHERE a.nbbooks BETWEEN :min AND :max
        ');
        $query->setParameter('min', $min);
        $query->setParameter('max', $max);
        return $query->getResult();
    }

    public function RomoveAuthorsAvecZeroBooks()
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery('
            DELETE FROM App\Entity\Author a
            WHERE a.nbbooks = 0
        ');
        return $query->execute();
    }

//    /**
//     * @return Author[] Returns an array of Author objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Author
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
