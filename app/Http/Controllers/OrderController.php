<?php
namespace App\Http\Controllers;

use App\DTO\CreateOrderDTO;
use App\Http\Requests\CreateOrderRequest;
use App\Service\OrderService;
use App\Repository\OrderRepository;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService,
        private OrderRepository $orderRepository
    )
    {

    }

    public function createNewOrder(CreateOrderRequest $request)
    {
        $order = $this->orderService->createOrder(CreateOrderDTO::fromRequest($request));

        return $this->successJson($order);
    }

    public function all()
    {
        return $this->successJson($this->orderRepository->all());
    }
}


