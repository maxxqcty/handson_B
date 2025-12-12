<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    private OrderRepository $orderRepository;
    private ProductRepository $productRepository;

    public function __construct(OrderRepository $orderRepository, ProductRepository $productRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        $orders = $this->orderRepository->findAll();
        $products = $this->productRepository->findAll();

        /*
        |--------------------------------------------------------------------------
        | 1. ORDERS TODAY  (No createdAt field → always 0)
        |--------------------------------------------------------------------------
        */
        $ordersToday = 0;

        /*
        |--------------------------------------------------------------------------
        | 2. TOTAL CUSTOMERS (unique names)
        |--------------------------------------------------------------------------
        */
        $customerNames = array_map(fn($order) => $order->getCustomerName(), $orders);
        $totalCustomers = count(array_unique($customerNames));

        /*
        |--------------------------------------------------------------------------
        | 3. TOTAL REVENUE (sum of totals)
        |--------------------------------------------------------------------------
        */
        $totalRevenue = array_reduce($orders, function ($sum, $order) {
            return $sum + (float) $order->getTotal();
        }, 0);

        /*
        |--------------------------------------------------------------------------
        | 4. PRODUCTS IN STOCK (sum of stock values)
        |--------------------------------------------------------------------------
        */
        $productsInStock = array_reduce($products, function ($sum, $product) {
            return $sum + (int) $product->getStock();
        }, 0);

        /*
        |--------------------------------------------------------------------------
        | 5. RECENT ORDERS (Last 5)
        |--------------------------------------------------------------------------
        */
        $recentOrders = $this->orderRepository->findBy([], ['id' => 'DESC'], 5);

        return $this->render('dashboard/index.html.twig', [
            'stats' => [
                'ordersToday'     => $ordersToday,
                'totalCustomers'  => $totalCustomers,
                'totalRevenue'    => $totalRevenue,
                'productsInStock' => $productsInStock,
            ],
            'orders' => $recentOrders,
        ]);
    }
}
