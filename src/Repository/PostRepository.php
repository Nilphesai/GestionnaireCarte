<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    //    /**
    //     * @return Post[] Returns an array of Post objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Post
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findPosts(){
        $em = $this->getEntityManager();
        $sub = $em->createQueryBuilder();
        $qb = $sub;
        // sélectionner tous les Posts
        $qb->select('s')
            ->from('App\Entity\Post', 's');

        // renvoyer le résultat
        $query = $qb->getQuery();
        return $query->getResult();
    }
/*
    public function findPostsByTopic(int $id){
        $em = $this->getEntityManager();
        $sub = $em->createQueryBuilder();
        $qb = $sub;
        // sélectionner tous les Posts
        $qb->select('s')
            ->from('App\Entity\Post', 's')
            ->where('s.Topic = :id ')
            ->setParameter('id', $id)
            ->orderBy('s.createdAt', 'ASC');

        // renvoyer le résultat
        $query = $qb->getQuery();
        return $query->getResult();
    }
*/
    public function findByExampleField(int $value): array
        {
            return $this->createQueryBuilder('u')
                ->andWhere('u.Topic = :val')
                ->setParameter('val', $value)
                ->orderBy('u.id', 'ASC')
                ->setMaxResults(10)
                ->getQuery()
                ->getResult()
            ;
        }

}
