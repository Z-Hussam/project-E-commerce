<?php

namespace App\Repository;

use App\Classes\Search;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }




    /**
     * Summary of getFilterdProducts
     * @return Product[]
     */
    public function getFilterdProducts(Search $search)
    {
        $query = $this->createQueryBuilder('product')
        ->innerJoin('product.category', 'category')
        ->select('product', 'category');


        if (!empty($search->categories)) {
            $query = $query->andWhere('category.id IN (:categories)')
            ->setParameter('categories', $search->categories);
        }
        if (!empty($search->string)) {
            $query = $query
                ->andWhere('product.name LIKE :string')
                ->setParameter('string', "%{$search->string}%");
        }
        if (!empty($search->max && $search->min)) {
            $query = $query
                ->andWhere('product.price BETWEEN :mi AND :ma ')
                ->setParameter('mi', $search->min)
                ->setParameter('ma', $search->max);
        }
        switch ($search->filter) {
            case 'prix_ASC':
                $query = $query->orderBy('product.price', 'ASC');
                break;
            case 'prix_DESC':
                $query = $query->orderBy('product.price', 'DESC');
                break;
            case 'nom_ASC':
                $query = $query->orderBy('product.name', 'ASC');
                break;
            case 'nom_DESC':
                $query = $query->orderBy('product.name', 'DESC');
                break;
            default:
                $query = $query->orderBy('product.price ', 'ASC');
                break;
        }




        return $query->getQuery()->getResult();
    }

    /**
     * Rêquete qui nous permet de récuperer les produits en fonction de la recherch de l'utilisateur 
     * @return Product[]
     */
    public function findWithSearche(Search $search)
    {
       
        $query = $this->createQueryBuilder('p')
            ->select('c', 'p')
            
            ->join('p.category', 'c');
        if (!empty($search->categories)) {
            $query = $query
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->categories);
        }
        if (!empty($search->string)) {
            $query = $query
                ->andWhere('p.name LIKE :string')
                ->setParameter('string', "%{$search->string}%");
        }

        return $query->getQuery()->getResult();
    }

    // public function findWithPrice($price)
    // {
    //     $query = $this->createQueryBuilder('p')
    //         ->select('p')
    //         ->where('p.price BETWEEN (:min AND :max)')
    //         ->setParameter();
    //     return $query->getQuery()->getResult();
    // }

    //    /**
    //     * @return Product[] Returns an array of Product objects
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

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
