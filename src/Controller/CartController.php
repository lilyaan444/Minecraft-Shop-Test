<?php

namespace App\Controller;

use App\Form\ShippingType;
use App\Service\CartService;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public function add(int $id, CartService $cartService): Response
    {
        $cartService->add($id);

        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/remove/{id}', name: 'app_cart_remove', methods: ['POST'])]
    public function remove(int $id, CartService $cartService): Response
    {
        $cartService->remove($id);

        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/clear', name: 'app_cart_clear', methods: ['POST'])]
    public function clear(CartService $cartService): Response
    {
        $cartService->clear();

        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/checkout', name: 'app_cart_checkout', methods: ['GET', 'POST'])]
    public function checkout(Request $request, CartService $cartService, OrderService $orderService): Response
    {
        $form = $this->createForm(ShippingType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shippingInfo = $form->getData();
            $order = $orderService->createOrder($this->getUser(), $cartService->getFullCart(), $shippingInfo);
            $cartService->clear();

            return $this->redirectToRoute('app_cart_confirmation', ['id' => $order->getId()]);
        }

        return $this->render('cart/checkout.html.twig', [
            'cart' => $cartService->getFullCart(),
            'total' => $cartService->getTotal(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/confirmation/{id}', name: 'app_cart_confirmation', methods: ['GET'])]
    public function confirmation(int $id, OrderService $orderService): Response
    {
        $order = $orderService->getOrder($id);

        if (!$order || $order->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException('Order not found');
        }

        return $this->render('cart/confirmation.html.twig', [
            'order' => $order,
        ]);
    }
}