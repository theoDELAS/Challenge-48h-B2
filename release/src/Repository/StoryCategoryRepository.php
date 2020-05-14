<?php

namespace App\Repository;

use App\Entity\StoryCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StoryCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method StoryCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method StoryCategory[]    findAll()
 * @method StoryCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StoryCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StoryCategory::class);
    }

    // /**
    //  * @return StoryCategory[] Returns an array of StoryCategory objects
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
    public function findOneBySomeField($value): ?StoryCategory
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
