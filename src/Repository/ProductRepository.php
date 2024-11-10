<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function save(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Product[] Returns an array of Product objects
     */
    public function findByCategory($category): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.category = :val')
            ->setParameter('val', $category)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneByName($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.name = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function getProductStatusRatio(): array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p.status, COUNT(p.id) as count')
            ->groupBy('p.status');

        $result = $qb->getQuery()->getResult();

        $total = array_sum(array_column($result, 'count'));
        $ratio = [];

        foreach ($result as $item) {
            $ratio[$item['status']] = round(($item['count'] / $total) * 100, 2);
        }

        return $ratio;
    }

public function countProductsByCategory(): array
{
    return $this->createQueryBuilder('p')
        ->select('c.name AS category, COUNT(p.id) AS productCount')
        ->join('p.category', 'c') // Assurez-vous que 'p.category' est correct
        ->groupBy('c.id')
        ->getQuery()
        ->getResult();
}

public function searchByName(string $query): array
{
    return $this->createQueryBuilder('p')
        ->where('LOWER(p.name) LIKE LOWER(:query)')
        ->setParameter('query', '%' . $query . '%')
        ->orderBy('p.name', 'ASC')
        ->setMaxResults(10)
        ->getQuery()
        ->getResult();
}


}