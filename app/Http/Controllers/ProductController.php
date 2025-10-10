<?php
namespace App\Http\Controllers;

use App\Http\Requests\EditProductRequest;
use App\Http\Resources\AllProductsResource;
use App\Models\Product;
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

    public function editProduct(EditProductRequest $request)
    {
        $product = $this->productRepository->editProduct($request->id, [
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $request->image,
            'category_id' => $request->category_id,
        ]);

        return $this->successJson($product);
    }
}
