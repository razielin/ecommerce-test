<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $client_name
 * @property string $client_phone
 * @property string $client_address
 * @property string|null $comment
 * @property array<int, array{product_id:int, quantity:int}> $order_items
 */
class CreateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "client_name" => 'required|string|max:255',
            "client_phone" => 'required|string|max:255',
            "client_address" => 'required|string|max:255',
            "comment" => 'string',
            "order_items" => "required|array",
            "order_items.*.product_id" => "required|integer",
            "order_items.*.quantity" => "required|integer",
        ];
    }
}
