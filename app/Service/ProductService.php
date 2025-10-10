<?php

namespace App\Service;

use App\Http\Requests\AddProductRequest;
use App\Http\Requests\EditProductRequest;
use App\Models\Product;
use App\Repository\ProductRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    public function editProduct(EditProductRequest $request): Product
    {
        $updateData = $this->prepareProductData($request);
        return $this->productRepository->editProduct($request->id, $updateData);
    }

    public function addProduct(AddProductRequest $request): Product
    {
        $productData = $this->prepareProductData($request);
        return $this->productRepository->create($productData);
    }

    public function deleteProduct(int $id): bool
    {
        return $this->productRepository->delete($id);
    }

    private function prepareProductData(EditProductRequest|AddProductRequest $request): array
    {
        $data = [
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'category_id' => $request->category_id,
        ];

        $this->processImageIfProvided($request, $data);

        return $data;
    }

    private function processImageIfProvided($request, array &$data): void
    {
        if ($request->image !== null) {
            $imagePath = $request->image;

            if (str_starts_with($request->image, 'data:image/')) {
                $imagePath = $this->saveBase64Image($request->image);
            }

            $data['image'] = $imagePath;
        }
    }

    private function saveBase64Image(string $base64Image): string
    {
        // Extract image data and extension
        $imageData = explode(',', $base64Image);
        $imageInfo = explode(';', $imageData[0]);
        $imageExtension = explode('/', $imageInfo[0])[1];

        // Generate unique filename
        $filename = Str::uuid() . '.' . $imageExtension;

        // Decode base64 data
        $decodedImage = base64_decode($imageData[1]);

        // Save to public/images directory
        $path = 'images/' . $filename;
        Storage::disk('public')->put($path, $decodedImage, 'public');

        // Return only the filename
        return $filename;
    }
}
