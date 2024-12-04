<?php

namespace App\Repository;

use App\Entity\Deck;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Deck>
 */
class DeckRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Deck::class);
    }

    //    /**
    //     * @return Deck[] Returns an array of Deck objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Deck
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findDecks(){
        $em = $this->getEntityManager();
        $sub = $em->createQueryBuilder();

        $qb = $sub;
        // sélectionner tous les deck
        $qb->select('s')
            ->from('App\Entity\Deck', 's')
            ->setMaxResults(6);

        // renvoyer le résultat
        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function findDecksById(int $id){
        $em = $this->getEntityManager();
        $sub = $em->createQueryBuilder();

        $qb = $sub;
        // sélectionner tous les deck avec id
        $qb->select('s')
            ->from('App\Entity\Deck', 's')
            ->where('s.id = :id ')
            ->setParameter('id', $id);

        // renvoyer le résultat
        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function findDecksByUser(int $user_id){
        $em = $this->getEntityManager();
        $sub = $em->createQueryBuilder();

        $qb = $sub;
        // sélectionner tous les deck de l'utilisateur
        $qb->select('s')
            ->from('App\Entity\Deck', 's')
            ->where('s.user = :user ')
            ->setParameter('user', $user_id);

        // renvoyer le résultat
        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function findTopDeckbyQtt() {
        $em = $this->getEntityManager();
        $sub = $em->createQueryBuilder();

        $qb = $sub;
        // sélectionner les 6 deck avec le plus de cartes différentes
        $qb->select('c.id, COUNT(s.deck) as deckCount')
            ->from('App\Entity\DeckCard', 's')
            ->join('s.deck', 'c') 
            ->groupBy('c.id') 
            ->orderBy('deckCount', 'DESC')
            ->setMaxResults(6);

        // renvoyer le résultat
        $query = $qb->getQuery();
        return $query->getResult();

    }

}
