<?php
namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Http\Resources\AllProductsResource;
use App\DTO\CreateOrderDTO;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\OrderService;

class ProductController extends Controller {
    public function __construct(
        private ProductRepository $productRepository,
        private CategoryRepository $categoryRepository,
        private OrderService $orderService
    )
    {

    }

    public function all()
    {
        return $this->successJson(
            AllProductsResource::collection($this->productRepository->all())
        );
    }

    public function categories()
    {
        return $this->successJson($this->categoryRepository->all());
    }

    public function createNewOrder(CreateOrderRequest $request)
    {
        $order = $this->orderService->createOrder(CreateOrderDTO::fromRequest($request));

        return $this->successJson($order);
    }
}
