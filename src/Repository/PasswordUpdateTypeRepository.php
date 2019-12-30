<?php

namespace App\Repository;

use App\Entity\PasswordUpdateType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PasswordUpdateType|null find($id, $lockMode = null, $lockVersion = null)
 * @method PasswordUpdateType|null findOneBy(array $criteria, array $orderBy = null)
 * @method PasswordUpdateType[]    findAll()
 * @method PasswordUpdateType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PasswordUpdateTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PasswordUpdateType::class);
    }

    // /**
    //  * @return PasswordUpdateType[] Returns an array of PasswordUpdateType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PasswordUpdateType
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
