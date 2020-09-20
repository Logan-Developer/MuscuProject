<?php

namespace App\Repository;

use App\Entity\ContactRequests;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ContactRequests|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactRequests|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactRequests[]    findAll()
 * @method ContactRequests[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRequestsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactRequests::class);
    }

    // /**
    //  * @return ContactRequests[] Returns an array of ContactRequests objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ContactRequests
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
