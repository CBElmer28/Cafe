<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Exceptions\BusinessException;
use App\Exceptions\SystemException;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderService
{
    private OrderRepository $orderRepository;
    private ProductRepository $productRepository;

    public function __construct(OrderRepository $orderRepository, ProductRepository $productRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
    }

    public function getAll(): array
    {
        try {
            return $this->orderRepository->all();
        } catch (Exception $e) {
            throw new SystemException('database_error', 500);
        }
    }

    public function getById(int $id): array
    {
        $order = $this->orderRepository->find($id);
        if (!$order) {
            throw new BusinessException('order_not_found', 404);
        }
        return $order->toArray();
    }

    public function getByEmail(string $email): array
    {
        try {
            return $this->orderRepository->getByCustomerEmail($email);
        } catch (Exception $e) {
            throw new SystemException('database_error', 500);
        }
    }

    public function getByUserId(int $userId): array
    {
        try {
            return $this->orderRepository->getByUserId($userId);
        } catch (Exception $e) {
            throw new SystemException('database_error', 500);
        }
    }

    public function checkout(array $data): array
    {
        try {
            return DB::transaction(function () use ($data) {
                $total = 0;
                $itemsToCreate = [];

                foreach ($data['items'] as $item) {
                    $product = $this->productRepository->find($item['product_id']);
                    
                    if (!$product) {
                        throw new BusinessException('product_not_found', 404);
                    }

                    if ($product->stock < $item['quantity']) {
                        throw new BusinessException('out_of_stock', 409);
                    }

                    // Descontar stock
                    $this->productRepository->updateStock($product->id, -$item['quantity']);

                    $itemTotal = $product->price * $item['quantity'];
                    $total += $itemTotal;

                    $itemsToCreate[] = [
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                    ];
                }

                $orderData = [
                    'user_id' => $data['user_id'] ?? null,
                    'customer_name' => $data['customer_name'],
                    'customer_email' => $data['customer_email'],
                    'delivery_address' => $data['delivery_address'],
                    'total' => $total,
                    'status' => 'paid', // Checkout simulado marcado como pagado
                ];

                $order = $this->orderRepository->createWithItems($orderData, $itemsToCreate);
                return $order->toArray();
            });
        } catch (BusinessException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new SystemException('database_error', 500);
        }
    }
}
