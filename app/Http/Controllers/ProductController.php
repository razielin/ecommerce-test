<?php
namespace App\Http\Controllers;

use App\Http\Requests\EditProductRequest;
use App\Http\Resources\AllProductsResource;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\ProductService;

class ProductController extends Controller {
    public function __construct(
        private ProductRepository $productRepository,
        private CategoryRepository $categoryRepository,
        private ProductService $productService,
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

    public function editProduct(EditProductRequest $request)
    {
        $product = $this->productService->editProduct($request);

        return $this->successJson(new AllProductsResource($product));
    }
}
