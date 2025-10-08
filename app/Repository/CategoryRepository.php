<?php
namespace App\Repository;

use App\Models\Category;

class CategoryRepository
{
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return Category::all();
    }
}
