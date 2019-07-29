<?php

namespace App\Repository;

use App\Entity\Overrides;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Overrides|null find($id, $lockMode = null, $lockVersion = null)
 * @method Overrides|null findOneBy(array $criteria, array $orderBy = null)
 * @method Overrides[]    findAll()
 * @method Overrides[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OverridesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Overrides::class);
    }

    // /**
    //  * @return Overrides[] Returns an array of Overrides objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Overrides
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
