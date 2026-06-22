<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{
    public function all(): array
    {
        return Product::all()->toArray();
    }

    public function find(int $id): ?object
    {
        return Product::find($id);
    }

    public function create(array $data): object
    {
        return Product::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $product = Product::find($id);
        if ($product) {
            return $product->update($data);
        }
        return false;
    }

    public function delete(int $id): bool
    {
        $product = Product::find($id);
        if ($product) {
            return $product->delete();
        }
        return false;
    }

    public function getActivos(): array
    {
        // Productos con stock disponible
        return Product::where('stock', '>', 0)->get()->toArray();
    }

    public function updateStock(int $id, int $quantity): bool
    {
        $product = Product::find($id);
        if ($product) {
            $product->stock = $product->stock + $quantity;
            return $product->save();
        }
        return false;
    }
}
