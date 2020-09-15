<?php

namespace App\Repository;

use App\Entity\DemandesContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DemandesContact|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemandesContact|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemandesContact[]    findAll()
 * @method DemandesContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandesContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemandesContact::class);
    }

    // /**
    //  * @return DemandesContact[] Returns an array of DemandesContact objects
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
    public function findOneBySomeField($value): ?DemandesContact
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
