<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private $session;
    private $productRepository;

    public function __construct(RequestStack $requestStack, ProductRepository $productRepository)
    {
        $this->session = $requestStack->getSession();
        $this->productRepository = $productRepository;
    }

    public function add(int $id): void
    {
        $product = $this->productRepository->find($id);
        if (!$product || $product->getStatus() === 'unavailable') {
            throw new \Exception('This product is unavailable.');
        }

        $cart = $this->session->get('cart', []);
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $this->session->set('cart', $cart);
    }

    public function update(int $id, int $quantity): void
    {
        $cart = $this->session->get('cart', []);
        if ($quantity > 0) {
            $cart[$id] = $quantity;
        } else {
            unset($cart[$id]);
        }
        $this->session->set('cart', $cart);
    }

    public function remove(int $id): void
    {
        $cart = $this->session->get('cart', []);
        unset($cart[$id]);
        $this->session->set('cart', $cart);
    }

    public function clear(): void
    {
        $this->session->remove('cart');
    }

    public function getFullCart(): array
    {
        $cart = $this->session->get('cart', []);
        $cartData = [];

        foreach ($cart as $id => $quantity) {
            $product = $this->productRepository->find($id);
            if ($product) {
                $cartData[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
            }
        }

        return $cartData;
    }

    public function getTotal(): float
    {
        $total = 0;
        foreach ($this->getFullCart() as $item) {
            $total += $item['product']->getPrice() * $item['quantity'];
        }
        return $total;
    }

    public function getCount(): int
    {
        $count = 0;
        foreach ($this->session->get('cart', []) as $quantity) {
            $count += $quantity;
        }
        return $count;
    }
}
