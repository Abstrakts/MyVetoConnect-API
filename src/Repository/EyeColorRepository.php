<?php

namespace App\Repository;

use App\Entity\EyeColor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method EyeColor|null find($id, $lockMode = null, $lockVersion = null)
 * @method EyeColor|null findOneBy(array $criteria, array $orderBy = null)
 * @method EyeColor[]    findAll()
 * @method EyeColor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EyeColorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EyeColor::class);
    }

    // /**
    //  * @return EyeColor[] Returns an array of EyeColor objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EyeColor
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
