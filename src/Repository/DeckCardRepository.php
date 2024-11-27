<?php

namespace App\Repository;

use App\Entity\DeckCard;
use App\Entity\Deck;
use App\Entity\Card;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\GroupBy;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DeckCard>
 */
class DeckCardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeckCard::class);
    }

    //    /**
    //     * @return DeckCard[] Returns an array of DeckCard objects
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

    //    public function findOneBySomeField($value): ?DeckCard
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findDeckCards(){
        $em = $this->getEntityManager();
        $sub = $em->createQueryBuilder();

        $qb = $sub;
        // sélectionner tous les deckCard (toutes les cartes liée à un deck)
        $qb->select('s')
            ->from('App\Entity\DeckCard', 's');

        // renvoyer le résultat
        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function findDeckCardsByDeck(Deck $deck){
        $em = $this->getEntityManager();
        $sub = $em->createQueryBuilder();

        $qb = $sub;
        // sélectionner tous les deckCards avec deck_id entrée en paramètre
        $qb->select('s')
            ->from('App\Entity\DeckCard', 's')
            ->where('s.deck = :deck ')
            ->setParameter('deck', $deck);

        // renvoyer le résultat
        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function findDeckCardsByCard(Card $card){
        $em = $this->getEntityManager();
        $sub = $em->createQueryBuilder();

        $qb = $sub;
        // sélectionner tous les deckCards avec card_id entrée en paramètre
        $qb->select('s')
            ->from('App\Entity\DeckCard', 's')
            ->where('s.card = :card ')
            ->setParameter('card', $card);

        // renvoyer le résultat
        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function findDeckCardsByCardAndDeck(Card $card, Deck $deck){
        $em = $this->getEntityManager();
        $sub = $em->createQueryBuilder();

        $qb = $sub;
        // sélectionner tous les deckCards avec card_id ET deck_id entrée en paramètre
        $qb->select('s')
            ->from('App\Entity\DeckCard', 's')
            ->where('s.card = :card ')
            ->andwhere('s.deck = :deck')
            ->setParameter('card', $card)
            ->setParameter('deck', $deck);

        // renvoyer le résultat
        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function findTopDeckCardsbyQttInAllDeck(){
        $em = $this->getEntityManager();
        $sub = $em->createQueryBuilder();

        $qb = $sub;
        // sélectionner les 6 deckCard des 6 cartes est les plus utilisés 
        $qb->select('c.id, COUNT(s.card) as cardCount')
            ->from('App\Entity\DeckCard', 's')
            ->join('s.card', 'c') 
            ->groupBy('c.id') 
            ->orderBy('cardCount', 'DESC')
            ->setMaxResults(6);

        // renvoyer le résultat
        $query = $qb->getQuery();
        return $query->getResult();
    }


}
