<?php

namespace App\Repositories;

use App\Interfaces\OrderRepositoryInterface;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class OrderRepository implements OrderRepositoryInterface
{
    public function all(): array
    {
        return Order::with('items.product')->get()->toArray();
    }

    public function find(int $id): ?object
    {
        return Order::with('items.product')->find($id);
    }

    public function create(array $data): object
    {
        return Order::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $order = Order::find($id);
        if ($order) {
            return $order->update($data);
        }
        return false;
    }

    public function delete(int $id): bool
    {
        $order = Order::find($id);
        if ($order) {
            return $order->delete();
        }
        return false;
    }

    public function createWithItems(array $orderData, array $items): object
    {
        return DB::transaction(function () use ($orderData, $items) {
            $order = Order::create($orderData);
            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }
            return $order->load('items.product');
        });
    }

    public function getByCustomerEmail(string $email): array
    {
        return Order::with('items.product')
            ->where('customer_email', $email)
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    public function getByUserId(int $userId): array
    {
        return Order::with('items.product')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }
}
