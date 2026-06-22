<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Exceptions\BusinessException;
use App\Exceptions\SystemException;
use Exception;

class ProductService
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAll(): array
    {
        try {
            return $this->productRepository->all();
        } catch (Exception $e) {
            throw new SystemException('database_error', 500);
        }
    }

    public function getActivos(): array
    {
        try {
            return $this->productRepository->getActivos();
        } catch (Exception $e) {
            throw new SystemException('database_error', 500);
        }
    }

    public function getById(int $id): array
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw new BusinessException('product_not_found', 404);
        }
        return $product->toArray();
    }

    public function store(array $data): array
    {
        try {
            $product = $this->productRepository->create($data);
            return $product->toArray();
        } catch (Exception $e) {
            throw new SystemException('database_error', 500);
        }
    }

    public function update(int $id, array $data): bool
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw new BusinessException('product_not_found', 404);
        }

        try {
            return $this->productRepository->update($id, $data);
        } catch (Exception $e) {
            throw new SystemException('database_error', 500);
        }
    }

    public function delete(int $id): bool
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw new BusinessException('product_not_found', 404);
        }

        try {
            return $this->productRepository->delete($id);
        } catch (Exception $e) {
            throw new SystemException('database_error', 500);
        }
    }
}
