<?php

namespace App\Repository;

use App\Entity\Start;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Start|null find($id, $lockMode = null, $lockVersion = null)
 * @method Start|null findOneBy(array $criteria, array $orderBy = null)
 * @method Start[]    findAll()
 * @method Start[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StartRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Start::class);
    }

    // /**
    //  * @return Start[] Returns an array of Start objects
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
    public function findOneBySomeField($value): ?Start
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
