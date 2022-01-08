<?php

namespace App\Repository;

use App\Entity\Mentoring;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Mentoring|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mentoring|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mentoring[]    findAll()
 * @method Mentoring[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MentoringRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mentoring::class);
    }

    // /**
    //  * @return Mentoring[] Returns an array of Mentoring objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Mentoring
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
