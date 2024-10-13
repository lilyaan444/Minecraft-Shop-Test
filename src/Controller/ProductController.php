<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product')]
class ProductController extends AbstractController
{
    // ... autres méthodes ...

    #[Route('/{id}/add', name: 'app_product_add_to_cart', methods: ['POST'])]
    public function addToCart(Product $product, CartService $cartService): Response
    {
        $cartService->add($product->getId());

        $this->addFlash('success', 'Product added to cart!');

        return $this->redirectToRoute('app_product_index');
    }
}