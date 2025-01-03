<?php

namespace App\Repository;

use App\Entity\Card;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Card>
 */
class CardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Card::class);
    }

    //    /**
    //     * @return Card[] Returns an array of Card objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Card
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    /*
    public function findCardByDeck(int $idDeck): ?Card
    {
        $cd = $this->getEntityManager();
        $sub = $em->createQueryBuilder();

        $qb = $sub;
        $qb->select('c')
            ->from('App\Entity\Card', 'c')
            ->leftJoin('c.refCard', 'cd')
            ->where('cd.deck_card = :id' );

        $sub = $em->createQueryBuilder();

        $sub->select('c.name')
            ->from('App\Entity\Deck', 'dk')
            ->where($sub->expr()->In('dk.id', $qb->getDQL()))
            ->setParameter('refCard',$refCard);

    }
    */
    public function findCardByRefCard(int $refCard)
    {
        $cd = $this->getEntityManager();
        $sub = $cd->createQueryBuilder();

        $qb = $sub;
        // sélectionner la Carte dont la refCard est passé en paramètre
        $qb->select('s')
            ->from('App\Entity\Card', 's')
            ->where('s.refCard = :refCard ')
            ->setParameter('refCard', $refCard);

        // renvoyer le résultat
        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function findCardById(int $id)
    {
        $cd = $this->getEntityManager();
        $sub = $cd->createQueryBuilder();

        $qb = $sub;
        // sélectionner la Carte dont la refCard est passé en paramètre
        $qb->select('s')
            ->from('App\Entity\Card', 's')
            ->where('s.id = :id ')
            ->setParameter('id', $id);

        // renvoyer le résultat
        $query = $qb->getQuery();
        return $query->getResult();
    }
}
