<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function findByField($value, $field, $limit, $orderBy, $order)
    {
        return $this->createQueryBuilder('e')
            ->andWhere("e.$field = :value")
            ->setParameter('value', $value)
            ->orderBy("e.$orderBy", $order)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getMembers($event, $user = [])
    {
        $event = $this->find($event);
        $event->getMembers()->removeElement($user);

        return array_filter($event->getMembers()->map(function ($member) {
            return $member->getUuid();
        })->getValues());
    }

    public function getEndsEvents()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->lte('date', date_create(date('Y-m-d H:i:00'))));
        return $this->matching($criteria)->getValues();

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
    */

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
