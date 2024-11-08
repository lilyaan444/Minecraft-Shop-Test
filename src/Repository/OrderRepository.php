<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function save(Order $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Order $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Order[] Returns an array of Order objects
     */
    public function findRecentOrders($limit = 5): array
    {
        return $this->createQueryBuilder('o')
            ->orderBy('o.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }
   public function getMonthlySales(int $months): array
{
    $date = new \DateTime();
    $date->modify("-$months months");

    $orders = $this->createQueryBuilder('o')
        ->where('o.createdAt >= :date')
        ->setParameter('date', $date)
        ->getQuery()
        ->getResult();

    $monthlySales = [];
    $totalSales = 0; // Variable to store the total sales amount

    foreach ($orders as $order) {
        $month = $order->getCreatedAt()->format('m'); // Get the month as a number
        $totalAmount = $order->getTotal(); // Assuming you have a method to get the total amount

        if (!isset($monthlySales[$month])) {
            $monthlySales[$month] = 0;
        }
        $monthlySales[$month] += $totalAmount;
        $totalSales += $totalAmount; // Add the total amount of the order to the total sales
    }

    // Fill in missing months with 0 sales
    for ($i = 1; $i <= $months; $i++) {
        $monthKey = str_pad($i, 2, '0', STR_PAD_LEFT); // Format month as '01', '02', etc.
        if (!isset($monthlySales[$monthKey])) {
            $monthlySales[$monthKey] = 0;
        }
    }

    ksort($monthlySales); // Sort by month

    // Add the total sales sum to the return array
    $monthlySales['total'] = $totalSales;

    return $monthlySales;
}

     
}