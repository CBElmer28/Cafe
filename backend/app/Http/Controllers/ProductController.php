<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Traits\ApiResponse;
use App\Exceptions\BusinessException;
use App\Exceptions\SystemException;
use Exception;

class ProductController extends Controller
{
    use ApiResponse;

    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        try {
            $data = $this->productService->getAll();
            return $this->success($data, 200);
        } catch (SystemException $e) {
            return $this->error($e->getMessage(), $e->getCode() ?: 500);
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode() ?: 400);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function show(int $id)
    {
        try {
            $data = $this->productService->getById($id);
            return $this->success($data, 200);
        } catch (SystemException $e) {
            return $this->error($e->getMessage(), $e->getCode() ?: 500);
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode() ?: 400);
        } catch (Exception $e) {
            return $this->error('product_not_found', 404);
        }
    }
}
