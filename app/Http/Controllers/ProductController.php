<?php
namespace App\Http\Controllers;

use App\Http\Resources\AllProductsResource;
use App\Repository\ProductRepository;

class ProductController extends Controller {
    public function __construct(private ProductRepository $productRepository)
    {

    }

    public function all()
    {
        return $this->successJson(
            AllProductsResource::collection($this->productRepository->all())
        );
    }
}
