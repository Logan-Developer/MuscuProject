<?php

namespace App\Repository;

use App\Entity\HeadingPages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HeadingPages|null find($id, $lockMode = null, $lockVersion = null)
 * @method HeadingPages|null findOneBy(array $criteria, array $orderBy = null)
 * @method HeadingPages[]    findAll()
 * @method HeadingPages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HeadingPagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HeadingPages::class);
    }

    // /**
    //  * @return HeadingPages[] Returns an array of HeadingPages objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HeadingPages
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
