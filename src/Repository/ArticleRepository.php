<?php

namespace App\Repository;

use App\Entity\Article;
use App\Data\SearchData;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }


    /**
     * Récupère les articles en lien avec une recherche
     * @return Product[]
     */
    public function findSearch(SearchData $search): array
    {
        $query = $this
        ->createQueryBuilder('p');
            
        
        if (!empty($search->categories))
        {
            $query
                ->where("p.category = :category")
                ->setParameter("category", $search->categories);
        } else {
            $query  
                ->orderBy('p.category');
        }
        
        return $query->getQuery()->getResult();
    }

}
