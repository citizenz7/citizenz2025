<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    //    /**
    //     * @return Article[] Returns an array of Article objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Article
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function search($title)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.title LIKE :title')
            ->andWhere('a.isActive = true')
            ->setParameter('title', '%' . $title . '%')
            ->getQuery()
            ->execute();
    }

    public function findArticles()
    {
        $qb = $this->createQueryBuilder('p');
        //$qb->where('p.status=1');
        return $qb->getQuery(); // WITHOUT ->getResult(); !!
    }

    public function AllActiveArticles(): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.isActive = true')
            ->getQuery()
            ->getResult()
        ;
    }

    public function totalViews(): array
    {
        return $this->createQueryBuilder('v')
            ->select('SUM(v.views) AS totalViews')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findMostLikedArticles(int $limit = 3): array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.likes', 'l')
            ->groupBy('a.id')
            ->orderBy('COUNT(l.id)', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
