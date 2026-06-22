<?php

namespace App\Interfaces;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function getActivos(): array;
    public function updateStock(int $id, int $quantity): bool;
}
