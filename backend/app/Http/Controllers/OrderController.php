<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderService;
use App\Traits\ApiResponse;
use App\Exceptions\BusinessException;
use App\Exceptions\SystemException;
use Illuminate\Http\Request;
use Exception;

class OrderController extends Controller
{
    use ApiResponse;

    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        try {
            $data = $this->orderService->getAll();
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
            $data = $this->orderService->getById($id);
            return $this->success($data, 200);
        } catch (SystemException $e) {
            return $this->error($e->getMessage(), $e->getCode() ?: 500);
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode() ?: 400);
        } catch (Exception $e) {
            return $this->error('order_not_found', 404);
        }
    }

    public function store(StoreOrderRequest $request)
    {
        try {
            $data = $request->validated();
            if (auth()->check()) {
                $data['user_id'] = auth()->id();
            }
            $order = $this->orderService->checkout($data);
            return $this->success($order, 201);
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode() ?: 409);
        } catch (SystemException $e) {
            return $this->error($e->getMessage(), $e->getCode() ?: 500);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function myOrders()
    {
        try {
            $userId = auth()->id();
            $data = $this->orderService->getByUserId($userId);
            return $this->success($data, 200);
        } catch (SystemException $e) {
            return $this->error($e->getMessage(), $e->getCode() ?: 500);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function track(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        try {
            $data = $this->orderService->getByEmail($request->input('email'));
            return $this->success($data, 200);
        } catch (SystemException $e) {
            return $this->error($e->getMessage(), $e->getCode() ?: 500);
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), $e->getCode() ?: 400);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}
