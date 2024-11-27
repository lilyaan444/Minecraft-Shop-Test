<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Repository\ProductRepository;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\OrderRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

#[Route('/order')]
class OrderController extends AbstractController
{
    #[Route('/create', name: 'app_order_create', methods: ['GET', 'POST'])]
    public function create(CartService $cartService, ProductRepository $productRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'You must be logged in to place an order.');
            return $this->redirectToRoute('app_login');
        }

        $cartItems = $cartService->getFullCart();
        if (empty($cartItems)) {
            $this->addFlash('error', 'Your cart is empty.');
            return $this->redirectToRoute('app_cart_index');
        }

        $order = new Order();
        $order->setUser($user);
        $order->setReference(uniqid());

        foreach ($cartItems as $item) {
            $product = $item['product'];
            if ($product->getStatus() === 'unavailable') {
                $this->addFlash('error', 'One or more products are unavailable.');
                return $this->redirectToRoute('app_cart_index');
            }

            $orderItem = new OrderItem();
            $orderItem->setProduct($product);
            $orderItem->setQuantity($item['quantity']);
            $orderItem->setProductPrice($product->getPrice());
            $order->addOrderItem($orderItem);

            // Update product stock
            $product = $item['product'];
            $product->setStock($product->getStock() - $item['quantity']);
        }

        $entityManager->persist($order);
        $entityManager->flush();

        $cartService->clear();
        $this->addFlash('success', 'Your order has been placed successfully!');

        return $this->redirectToRoute('app_order_confirmation', ['reference' => $order->getReference()]);
    }

    #[Route('/confirmation/{reference}', name: 'app_order_confirmation', methods: ['GET'])]
    public function confirmation(string $reference, OrderRepository $orderRepository): Response
    {
        $order = $orderRepository->findOneBy(['reference' => $reference]);

        if (!$order || $order->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('order/confirmation.html.twig', [
            'order' => $order
        ]);
    }
}
