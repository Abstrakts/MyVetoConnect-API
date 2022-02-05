<?php

namespace App\Repository;

use App\Entity\Sheets;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Sheets|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sheets|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sheets[]    findAll()
 * @method Sheets[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SheetsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sheets::class);
    }

    // /**
    //  * @return Sheets[] Returns an array of Sheets objects
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
    public function findOneBySomeField($value): ?Sheets
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
