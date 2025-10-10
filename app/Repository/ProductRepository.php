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
}
