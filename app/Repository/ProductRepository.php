<?php
namespace App\Repository;

use App\Models\Product;

class ProductRepository
{
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return Product::all();
    }
}
