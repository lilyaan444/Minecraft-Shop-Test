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



    #[Route('/{id}/add', name: 'app_product_add_to_cart', methods: ['POST'])]
    public function addToCart(Product $product, CartService $cartService): Response
    {
        $cartService->add($product->getId());

        $this->addFlash('success', 'Product added to cart!');

        return $this->redirectToRoute('app_product_index');
    }

    #[Route('/product', name: 'app_product_index')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll(); 
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }
}