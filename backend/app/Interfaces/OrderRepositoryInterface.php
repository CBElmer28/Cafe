<?php

namespace App\Interfaces;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    public function createWithItems(array $orderData, array $items): object;
    public function getByCustomerEmail(string $email): array;
    public function getByUserId(int $userId): array;
}
