<?php

namespace App\Repository;

use App\Entity\StudyLevel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StudyLevel|null find($id, $lockMode = null, $lockVersion = null)
 * @method StudyLevel|null findOneBy(array $criteria, array $orderBy = null)
 * @method StudyLevel[]    findAll()
 * @method StudyLevel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudyLevelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudyLevel::class);
    }

    // /**
    //  * @return StudyLevel[] Returns an array of StudyLevel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StudyLevel
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
