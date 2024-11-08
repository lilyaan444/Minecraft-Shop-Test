<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; // This line was added
use App\Repository\OrderRepository; // Add this line
use App\Repository\CategoryRepository; 

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin_dashboard')]
    public function dashboard(ProductRepository $productRepository, OrderRepository $orderRepository, CategoryRepository $categoryRepository): Response
    {
        // Nombre total de produits par catégorie

        // Les 5 dernières commandes
        $recentOrders = $orderRepository->findRecentOrders(5);

        // Ratio de disponibilité des produits
        $productStatus = $productRepository->getProductStatusRatio();

        // Montant total des ventes par mois (à implémenter)
        $monthlySales = []; // Vous devrez implémenter cette logique

        return $this->render('admin/dashboard.html.twig', [
            'recentOrders' => $recentOrders,
            'productStatus' => $productStatus,
            'monthlySales' => $monthlySales,
        ]);
    }


     #[Route('/products', name: 'app_admin_products')]
    public function listProducts(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        return $this->render('admin/products.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/products/new', name: 'app_admin_product_new', methods: ['GET', 'POST'])]
    public function newProduct(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Product created successfully.');
            return $this->redirectToRoute('app_admin_products');
        }

        return $this->render('admin/product_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/products/{id}/edit', name: 'app_admin_product_edit', methods: ['GET', 'POST'])]
    public function editProduct(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Product updated successfully.');
            return $this->redirectToRoute('app_admin_products');
        }

        return $this->render('admin/product_form.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
        ]);
    }

    #[Route('/products/{id}/delete', name: 'app_admin_product_delete', methods: ['POST'])]
    public function deleteProduct(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
            $this->addFlash('success', 'Product deleted successfully.');
        }

        return $this->redirectToRoute('app_admin_products');
    }
}