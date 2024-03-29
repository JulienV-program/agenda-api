<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\Start;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * @return Event[] Return an array of Event object
     */
    public function findAllOrdered(){
        return $this->createQueryBuilder('e')
            ->leftJoin('e.start', 's')
            ->orderBy('s.dateTime')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Event[]
     */
    public function findByDay(\DateTime $day, \DateTime $end) {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.start', 's')
            ->leftJoin('e.end', 'd')
            ->andWhere('s.dateTime >= :day')
            ->andWhere('d.dateTime <= :end')
            ->orderBy('s.dateTime')
            ->setParameter('day', $day)
            ->setParameter('end', $end)
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return Event[] Returns an array of Event objects
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



    /*
    public function findOneBySomeField($value): ?Event
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
