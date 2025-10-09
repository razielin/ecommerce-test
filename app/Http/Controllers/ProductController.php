<?php
namespace App\Http\Controllers;

use App\Http\Resources\AllProductsResource;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;

class ProductController extends Controller {
    public function __construct(
        private ProductRepository $productRepository,
        private CategoryRepository $categoryRepository,
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

    
}
