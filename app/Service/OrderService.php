<?php
namespace App\Service;

use App\DTO\CreateOrderDTO;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createOrder(CreateOrderDTO $orderDTO): Order
    {
        return DB::transaction(function () use ($orderDTO) {
            $order = new Order([
                'client_name' => $orderDTO->client_name,
                'client_phone' => $orderDTO->client_phone,
                'client_address' => $orderDTO->client_address,
                'comment' => (string) ($orderDTO->comment ?? ''),
            ]);
            $order->save();

            $totalAmount = 0.0;

            foreach ($orderDTO->order_items as $item) {
                $product = Product::query()->findOrFail($item['product_id']);
                $quantity = (int) $item['quantity'];

                $lineAmount = (float) $product->price * $quantity;
                $totalAmount += $lineAmount;

                $orderItem = new OrderItem([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                ]);
                $orderItem->save();
            }

            // Persist computed order amount
            $order->amount = round($totalAmount, 2);
            $order->save();

            return $order->load(['items', 'products']);
        });
    }
}
