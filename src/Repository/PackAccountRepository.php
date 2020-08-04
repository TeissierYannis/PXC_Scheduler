<?php

namespace App\Repository;

use App\Entity\PackAccount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PackAccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method PackAccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method PackAccount[]    findAll()
 * @method PackAccount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PackAccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PackAccount::class);
    }

    // /**
    //  * @return PackAccount[] Returns an array of PackAccount objects
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
    public function findOneBySomeField($value): ?PackAccount
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
