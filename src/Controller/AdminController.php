<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Entity\Order;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin_dashboard')]
    public function dashboard(
        ProductRepository $productRepository,
        UserRepository $userRepository,
        OrderRepository $orderRepository
    ): Response {

        // Nombre total de produits par catégorie
        $productsByCategory = $productRepository->countProductsByCategory();

        // Les 5 dernières commandes
        $recentOrders = $orderRepository->findRecentOrders(5);

        // Ratio de disponibilité des produits
        $productStatus = $productRepository->getProductStatusRatio();

        // Montant total des ventes par mois sur les 12 derniers mois
        $monthlySales = $orderRepository->getMonthlySales(12);

        return $this->render('admin/dashboard.html.twig', [
            'total_products' => $productRepository->count([]),
            'total_users' => $userRepository->count([]),
            'total_orders' => $orderRepository->count([]),
            'recent_orders' => $orderRepository->findRecentOrders(5),
            'productsByCategory' => $productsByCategory,
            'recentOrders' => $recentOrders,
            'productStatus' => $productStatus,
            'monthlySales' => $monthlySales,
        ]);

    }

    #[Route('/products', name: 'app_admin_products')]
    public function products(ProductRepository $productRepository): Response
    {
        return $this->render('admin/products.html.twig', [
            'products' => $productRepository->findAll()
        ]);
    }

    #[Route('/products/new', name: 'app_admin_product_new', methods: ['GET', 'POST'])]
    public function newProduct(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Product created successfully!');
            return $this->redirectToRoute('app_admin_products');
        }

        return $this->render('admin/product_form.html.twig', [
            'form' => $form->createView(),
            'title' => 'New Product'
        ]);
    }

    #[Route('/product/{id}/edit', name: 'app_admin_product_edit', methods: ['GET', 'POST'])]
    public function editProduct(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Product updated successfully!');
            return $this->redirectToRoute('app_admin_products');
        }

        return $this->render('admin/product_form.html.twig', [
            'form' => $form->createView(),
            'title' => 'Edit Product'
        ]);
    }

    #[Route('/users', name: 'app_admin_users')]
    public function users(UserRepository $userRepository): Response
    {
        return $this->render('admin/users.html.twig', [
            'users' => $userRepository->findAll()
        ]);
    }

    #[Route('/orders', name: 'app_admin_orders')]
    public function orders(OrderRepository $orderRepository): Response
    {
        return $this->render('admin/orders.html.twig', [
            'orders' => $orderRepository->findBy([], ['createdAt' => 'DESC'])
        ]);
    }

    #[Route('/product/{id}/delete', name: 'app_admin_product_delete', methods: ['POST'])]
    public function deleteProduct(Request $request, Product $product, EntityManagerInterface $entityManager, OrderRepository $orderRepository): Response
    {
        // Vérifiez si le produit est associé à une commande
        if ($orderRepository->count(['orderItems.product' => $product]) > 0) {
            $this->addFlash('error', 'Cannot delete the product as it is part of an existing order.');
            return $this->redirectToRoute('app_admin_products');
        }

        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();

            $this->addFlash('success', 'Product deleted successfully!');
        }

        return $this->redirectToRoute('app_admin_products');
    }
}
