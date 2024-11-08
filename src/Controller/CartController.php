<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cart')]
class CartController extends AbstractController
{
    #[Route('/', name: 'app_cart_index', methods: ['GET'])]
    public function index(CartService $cartService): Response
    {
        return $this->render('cart/index.html.twig', [
            'items' => $cartService->getFullCart(),
            'total' => $cartService->getTotal(),
        ]);
    }

    #[Route('/add/{id}', name: 'app_cart_add', methods: ['POST'])]
    public function add(int $id, Request $request, CartService $cartService): Response
    {
        $cartService->add($id);

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse([
                'total' => $cartService->getTotal(),
                'count' => $cartService->getCount()
            ]);
        }

        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/update/{id}', name: 'app_cart_update', methods: ['POST'])]
    public function update(int $id, Request $request, CartService $cartService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $cartService->update($id, $data['quantity']);

        return new JsonResponse([
            'total' => $cartService->getTotal(),
            'count' => $cartService->getCount()
        ]);
    }

    #[Route('/remove/{id}', name: 'app_cart_remove', methods: ['POST'])]
    public function remove(int $id, Request $request, CartService $cartService): Response
    {
        $cartService->remove($id);

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse([
                'total' => $cartService->getTotal(),
                'count' => $cartService->getCount()
            ]);
        }

        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/checkout', name: 'app_cart_checkout', methods: ['POST'])]
    public function checkout(CartService $cartService, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $order = new Order();
        $order->setUser($user);
        $order->setReference(uniqid());
        $order->setStatus('completed');

        $cartItems = $cartService->getFullCart();
        foreach ($cartItems as $item) {
            $orderItem = new OrderItem();
            $orderItem->setProduct($item['product']);
            $orderItem->setQuantity($item['quantity']);
            $orderItem->setProductPrice($item['product']->getPrice());
            $order->addOrderItem($orderItem);

            // Update stock
            $product = $item['product'];
            $product->setStock($product->getStock() - $item['quantity']);
        }

        $entityManager->persist($order);
        $entityManager->flush();

        $cartService->clear();

        $this->addFlash('success', 'Your order has been placed successfully!');
        return $this->redirectToRoute('app_order_confirmation', ['reference' => $order->getReference()]);
    }
}