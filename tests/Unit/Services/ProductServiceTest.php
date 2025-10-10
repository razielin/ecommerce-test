<?php

namespace Tests\Unit\Services;

use App\Http\Requests\AddProductRequest;
use App\Http\Requests\EditProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Repository\ProductRepository;
use App\Service\ProductService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProductService $productService;
    private ProductRepository $productRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productRepository = new ProductRepository();
        $this->productService = new ProductService($this->productRepository);

        // Fake the storage disk for testing
        Storage::fake('public');
    }

    public function test_edit_product_without_image()
    {
        // Arrange
        $category = Category::create(['name' => 'Test Category']);
        $product = Product::create([
            'name' => 'Original Product',
            'price' => 10.00,
            'description' => 'Original Description',
            'category_id' => $category->id,
        ]);

        $request = Mockery::mock(EditProductRequest::class);
        $request->id = $product->id;
        $request->name = 'Updated Product';
        $request->price = 15.50;
        $request->description = 'Updated Description';
        $request->category_id = $category->id;
        $request->image = null;

        // Act
        $result = $this->productService->editProduct($request);

        // Assert
        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals('Updated Product', $result->name);
        $this->assertEquals(15.50, $result->price);
        $this->assertEquals('Updated Description', $result->description);
        $this->assertEquals($category->id, $result->category_id);
        $this->assertEquals($product->image, $result->image); // Image should remain unchanged
    }

    public function test_edit_product_with_base64_image()
    {
        // Arrange
        $category = Category::create(['name' => 'Test Category']);
        $product = Product::create([
            'name' => 'Original Product',
            'price' => 10.00,
            'description' => 'Original Description',
            'category_id' => $category->id,
        ]);

        $base64Image = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==';

        $request = Mockery::mock(EditProductRequest::class);
        $request->id = $product->id;
        $request->name = 'Updated Product';
        $request->price = 15.50;
        $request->description = 'Updated Description';
        $request->category_id = $category->id;
        $request->image = $base64Image;

        // Act
        $result = $this->productService->editProduct($request);

        // Assert
        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals('Updated Product', $result->name);
        $this->assertNotEquals($product->image, $result->image); // Image should be updated
        $this->assertStringEndsWith('.png', $result->image); // Should be a PNG file

        // Verify file was stored (the method returns only filename, not full path)
        Storage::disk('public')->assertExists('images/' . $result->image);
    }

    public function test_edit_product_with_existing_image_path()
    {
        // Arrange
        $category = Category::create(['name' => 'Test Category']);
        $product = Product::create([
            'name' => 'Original Product',
            'price' => 10.00,
            'description' => 'Original Description',
            'category_id' => $category->id,
            'image' => 'existing-image.jpg',
        ]);

        $request = Mockery::mock(EditProductRequest::class);
        $request->id = $product->id;
        $request->name = 'Updated Product';
        $request->price = 15.50;
        $request->description = 'Updated Description';
        $request->category_id = $category->id;
        $request->image = 'new-image.jpg';

        // Act
        $result = $this->productService->editProduct($request);

        // Assert
        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals('new-image.jpg', $result->image);
    }

    public function test_add_product_without_image()
    {
        // Arrange
        $category = Category::create(['name' => 'Test Category']);

        $request = Mockery::mock(AddProductRequest::class);
        $request->name = 'New Product';
        $request->price = 25.00;
        $request->description = 'New Description';
        $request->category_id = $category->id;
        $request->image = null;

        // Act
        $result = $this->productService->addProduct($request);

        // Assert
        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals('New Product', $result->name);
        $this->assertEquals(25.00, $result->price);
        $this->assertEquals('New Description', $result->description);
        $this->assertEquals($category->id, $result->category_id);
        $this->assertNull($result->image);
    }

    public function test_add_product_with_base64_image()
    {
        // Arrange
        $category = Category::create(['name' => 'Test Category']);

        $base64Image = 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/2wBDAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwA/8A';

        $request = Mockery::mock(AddProductRequest::class);
        $request->name = 'New Product';
        $request->price = 25.00;
        $request->description = 'New Description';
        $request->category_id = $category->id;
        $request->image = $base64Image;

        // Act
        $result = $this->productService->addProduct($request);

        // Assert
        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals('New Product', $result->name);
        $this->assertStringEndsWith('.jpeg', $result->image); // Should be a JPEG file

        // Verify file was stored (the method returns only filename, not full path)
        Storage::disk('public')->assertExists('images/' . $result->image);
    }

    public function test_delete_product()
    {
        // Arrange
        $category = Category::create(['name' => 'Test Category']);
        $product = Product::create([
            'name' => 'Product to Delete',
            'price' => 10.00,
            'description' => 'Description',
            'category_id' => $category->id,
        ]);

        // Act
        $result = $this->productService->deleteProduct($product->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_delete_nonexistent_product_throws_exception()
    {
        // Act & Assert
        $this->expectException(ModelNotFoundException::class);
        $this->productService->deleteProduct(999);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
