<?php
namespace App\Repository;

use App\Models\Product;

class ProductRepository
{
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return Product::all();
    }

    public function editProduct(int $id, array $data): Product
    {
        $product = Product::findOrFail($id);
        
        $product->update($data);
        
        return $product;
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function delete(int $id): bool
    {
        $product = Product::findOrFail($id);
        return $product->delete();
    }
}
