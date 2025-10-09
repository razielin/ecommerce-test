<?php
namespace App\DTO;

use App\Http\Requests\CreateOrderRequest;
use App\Models\OrderItem;

class CreateOrderDTO {
    public $client_name;
    public $client_phone;
    public $client_address;
    public $comment;
    /**
     * @var OrderItem[]
     */
    public $order_items = [];

    public function addOrderItem(int $product_id, int $quantity)
    {
        $item = new OrderItem([
            'product_id' => $product_id,
            'quantity' => $quantity,
        ]);
        $this->order_items[] = $item;
    }

    /**
     * Build DTO from validated CreateOrderRequest.
     */
    public static function fromRequest(CreateOrderRequest $request): self
    {
        $data = $request->validated();

        $dto = new self();
        $dto->client_name = $data['client_name'];
        $dto->client_phone = $data['client_phone'];
        $dto->client_address = $data['client_address'];
        $dto->comment = $data['comment'] ?? '';

        foreach ($data['order_items'] as $item) {
            $dto->addOrderItem((int) $item['product_id'], (int) $item['quantity']);
        }

        return $dto;
    }
}
