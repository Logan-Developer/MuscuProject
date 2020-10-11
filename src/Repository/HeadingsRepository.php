<?php

namespace App\Repository;

use App\Entity\Headings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Headings|null find($id, $lockMode = null, $lockVersion = null)
 * @method Headings|null findOneBy(array $criteria, array $orderBy = null)
 * @method Headings[]    findAll()
 * @method Headings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HeadingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Headings::class);
    }

    // /**
    //  * @return Headings[] Returns an array of Headings objects
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
    public function findOneBySomeField($value): ?Headings
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
