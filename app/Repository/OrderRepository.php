<?php
namespace App\Repository;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository
{
    /**
     * Get all orders with related items and products.
     */
    public function all(): Collection
    {
        return Order::query()
            ->with(['items', 'products'])
            ->get();
    }
}


