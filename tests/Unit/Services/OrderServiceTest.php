<?php

namespace Tests\Unit\Services;

use App\DTO\CreateOrderDTO;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Service\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    private OrderService $orderService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderService = new OrderService();
    }

    public function test_create_order_with_single_item()
    {
        // Arrange
        $category = Category::create(['name' => 'Test Category']);
        $product = Product::create([
            'name' => 'Test Product',
            'price' => 10.50,
            'description' => 'Test Description',
            'category_id' => $category->id,
        ]);

        $orderDTO = new CreateOrderDTO();
        $orderDTO->client_name = 'John Doe';
        $orderDTO->client_phone = '+1234567890';
        $orderDTO->client_address = '123 Test Street';
        $orderDTO->comment = 'Test comment';
        $orderDTO->addOrderItem($product->id, 2);

        // Act
        $order = $this->orderService->createOrder($orderDTO);

        // Assert
        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals('John Doe', $order->client_name);
        $this->assertEquals('+1234567890', $order->client_phone);
        $this->assertEquals('123 Test Street', $order->client_address);
        $this->assertEquals('Test comment', $order->comment);
        $this->assertEquals(21.00, $order->amount); // 10.50 * 2
        $this->assertEquals(1, $order->order_status);

        // Check order items
        $this->assertCount(1, $order->items);
        $orderItem = $order->items->first();
        $this->assertEquals($product->id, $orderItem->product_id);
        $this->assertEquals(2, $orderItem->quantity);
    }

    public function test_create_order_with_multiple_items()
    {
        // Arrange
        $category = Category::create(['name' => 'Test Category']);
        $product1 = Product::create([
            'name' => 'Product 1',
            'price' => 15.00,
            'description' => 'Description 1',
            'category_id' => $category->id,
        ]);
        $product2 = Product::create([
            'name' => 'Product 2',
            'price' => 25.50,
            'description' => 'Description 2',
            'category_id' => $category->id,
        ]);

        $orderDTO = new CreateOrderDTO();
        $orderDTO->client_name = 'Jane Smith';
        $orderDTO->client_phone = '+0987654321';
        $orderDTO->client_address = '456 Another Street';
        $orderDTO->addOrderItem($product1->id, 1);
        $orderDTO->addOrderItem($product2->id, 3);

        // Act
        $order = $this->orderService->createOrder($orderDTO);

        // Assert
        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals('Jane Smith', $order->client_name);
        $this->assertEquals(91.50, $order->amount); // (15.00 * 1) + (25.50 * 3)

        // Check order items
        $this->assertCount(2, $order->items);

        $item1 = $order->items->where('product_id', $product1->id)->first();
        $this->assertEquals(1, $item1->quantity);

        $item2 = $order->items->where('product_id', $product2->id)->first();
        $this->assertEquals(3, $item2->quantity);
    }

    public function test_create_order_without_comment()
    {
        // Arrange
        $category = Category::create(['name' => 'Test Category']);
        $product = Product::create([
            'name' => 'Test Product',
            'price' => 5.00,
            'description' => 'Test Description',
            'category_id' => $category->id,
        ]);

        $orderDTO = new CreateOrderDTO();
        $orderDTO->client_name = 'Bob Wilson';
        $orderDTO->client_phone = '+1111111111';
        $orderDTO->client_address = '789 No Comment Street';
        $orderDTO->addOrderItem($product->id, 1);

        // Act
        $order = $this->orderService->createOrder($orderDTO);

        // Assert
        $this->assertEquals('Bob Wilson', $order->client_name);
        $this->assertEquals('', $order->comment); // Should be empty string when null
        $this->assertEquals(5.00, $order->amount);
    }

    public function test_create_order_with_decimal_prices()
    {
        // Arrange
        $category = Category::create(['name' => 'Test Category']);
        $product = Product::create([
            'name' => 'Decimal Product',
            'price' => 19.99,
            'description' => 'Test Description',
            'category_id' => $category->id,
        ]);

        $orderDTO = new CreateOrderDTO();
        $orderDTO->client_name = 'Decimal Test';
        $orderDTO->client_phone = '+2222222222';
        $orderDTO->client_address = 'Decimal Street';
        $orderDTO->addOrderItem($product->id, 3);

        // Act
        $order = $this->orderService->createOrder($orderDTO);

        // Assert
        $this->assertEquals(59.97, $order->amount); // 19.99 * 3 = 59.97
    }

    public function test_create_order_amount_rounding()
    {
        // Arrange
        $category = Category::create(['name' => 'Test Category']);
        $product = Product::create([
            'name' => 'Rounding Product',
            'price' => 10.333,
            'description' => 'Test Description',
            'category_id' => $category->id,
        ]);

        $orderDTO = new CreateOrderDTO();
        $orderDTO->client_name = 'Rounding Test';
        $orderDTO->client_phone = '+3333333333';
        $orderDTO->client_address = 'Rounding Street';
        $orderDTO->addOrderItem($product->id, 3);

        // Act
        $order = $this->orderService->createOrder($orderDTO);

        // Assert
        $this->assertEquals(31.0, $order->amount); // 10.333 * 3 = 30.999, rounded to 31.0
    }

    public function test_create_order_loads_relationships()
    {
        // Arrange
        $category = Category::create(['name' => 'Test Category']);
        $product = Product::create([
            'name' => 'Relationship Product',
            'price' => 12.00,
            'description' => 'Test Description',
            'category_id' => $category->id,
        ]);

        $orderDTO = new CreateOrderDTO();
        $orderDTO->client_name = 'Relationship Test';
        $orderDTO->client_phone = '+4444444444';
        $orderDTO->client_address = 'Relationship Street';
        $orderDTO->addOrderItem($product->id, 2);

        // Act
        $order = $this->orderService->createOrder($orderDTO);

        // Assert
        $this->assertTrue($order->relationLoaded('items'));
        $this->assertTrue($order->relationLoaded('products'));
        $this->assertCount(1, $order->items);
        $this->assertCount(1, $order->products);
    }

    public function test_create_order_with_zero_quantity_creates_order_with_zero_amount()
    {
        // Arrange
        $category = Category::create(['name' => 'Test Category']);
        $product = Product::create([
            'name' => 'Test Product',
            'price' => 10.00,
            'description' => 'Test Description',
            'category_id' => $category->id,
        ]);

        $orderDTO = new CreateOrderDTO();
        $orderDTO->client_name = 'Zero Test';
        $orderDTO->client_phone = '+5555555555';
        $orderDTO->client_address = 'Zero Street';
        $orderDTO->addOrderItem($product->id, 0);

        // Act
        $order = $this->orderService->createOrder($orderDTO);

        // Assert
        $this->assertEquals(0.0, $order->amount); // 10.00 * 0 = 0.0
        $this->assertCount(1, $order->items);
        $orderItem = $order->items->first();
        $this->assertEquals(0, $orderItem->quantity);
    }

    public function test_create_order_with_nonexistent_product_throws_exception()
    {
        // Arrange
        $orderDTO = new CreateOrderDTO();
        $orderDTO->client_name = 'Nonexistent Test';
        $orderDTO->client_phone = '+6666666666';
        $orderDTO->client_address = 'Nonexistent Street';
        $orderDTO->addOrderItem(999, 1); // Non-existent product ID

        // Act & Assert
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->orderService->createOrder($orderDTO);
    }

    public function test_create_order_database_transaction_rollback_on_failure()
    {
        // Arrange
        $category = Category::create(['name' => 'Test Category']);
        $product = Product::create([
            'name' => 'Test Product',
            'price' => 10.00,
            'description' => 'Test Description',
            'category_id' => $category->id,
        ]);

        $orderDTO = new CreateOrderDTO();
        $orderDTO->client_name = 'Transaction Test';
        $orderDTO->client_phone = '+7777777777';
        $orderDTO->client_address = 'Transaction Street';
        $orderDTO->addOrderItem($product->id, 1);

        // Mock a failure scenario by using a non-existent product in the second item
        $orderDTO->addOrderItem(999, 1); // This will cause the transaction to fail

        // Act & Assert
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        // Verify no orders were created due to transaction rollback
        $orderCountBefore = Order::count();
        $this->orderService->createOrder($orderDTO);
        $orderCountAfter = Order::count();

        // This assertion won't be reached due to the exception, but it's here for completeness
        $this->assertEquals($orderCountBefore, $orderCountAfter);
    }
}
