<?php

namespace App\Repository;

use App\Entity\End;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method End|null find($id, $lockMode = null, $lockVersion = null)
 * @method End|null findOneBy(array $criteria, array $orderBy = null)
 * @method End[]    findAll()
 * @method End[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EndRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, End::class);
    }

    // /**
    //  * @return End[] Returns an array of End objects
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
    public function findOneBySomeField($value): ?End
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
