<?php

declare(strict_types=1);

namespace App\Repository\adapters;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ports\ProductRepository;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrineProductRepository extends ServiceEntityRepository implements ProductRepository
{
    const FIND_LIMIT = 5;
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }
    
    public function findWithoutDiscountApplied(int $findOffset, ?string $categoryNameFilter = NULL, int $priceOriginalLessThanFilter = 0): ?array
    {
        $productQueryBuilder = $this->createQueryBuilder('p')
            ->select('p')
            ->addSelect('MAX(ProductPromotion.discountPercentage)')
            ->addSelect('MAX(CategoryPromotion.discountPercentage)')
            ->leftJoin('p.promotions', 'ProductPromotion')
            ->leftJoin('p.categories', 'c')
            ->leftJoin('c.promotions', 'CategoryPromotion')
            ->groupBy('p.id')
            ->setFirstResult($findOffset)
            ->setMaxResults(self::FIND_LIMIT)
        ;
        
        if (isset($categoryNameFilter))
            $productQueryBuilder = $productQueryBuilder->andWhere('c.name LIKE :categoryName')
                ->setParameter('categoryName', $categoryNameFilter)
            ;
        if (!empty($priceOriginalLessThanFilter))
            $productQueryBuilder = $productQueryBuilder->andWhere('p.price.original <= :priceOriginalLessThan')
                ->setParameter('priceOriginalLessThan', $priceOriginalLessThanFilter, \PDO::PARAM_INT)
            ;
        
        return $productQueryBuilder->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
