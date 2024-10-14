<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\User;
use App\Entity\Address;
use Doctrine\ORM\EntityManagerInterface;

class OrderService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createOrder(User $user, array $cartItems, array $shippingInfo): Order
    {
        $order = new Order();
        $order->setUser($user);
        $order->setReference(uniqid());
        $order->setStatus('pending');

        $shippingAddress = new Address();
        $shippingAddress->setUser($user);
        $shippingAddress->setStreet($shippingInfo['address']);
        $shippingAddress->setCity($shippingInfo['city']);
        $shippingAddress->setPostalCode($shippingInfo['postalCode']);
        $shippingAddress->setCountry($shippingInfo['country']);

        $order->setShippingAddress($shippingAddress);

        foreach ($cartItems as $item) {
            $orderItem = new OrderItem();
            $orderItem->setProduct($item['product']);
            $orderItem->setQuantity($item['quantity']);
            $orderItem->setProductPrice($item['product']->getPrice());
            $order->addOrderItem($orderItem);

            // Update product stock
            $product = $item['product'];
            $product->setStock($product->getStock() - $item['quantity']);
        }

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order;
    }

    public function getOrder(int $id): ?Order
    {
        return $this->entityManager->getRepository(Order::class)->find($id);
    }
}