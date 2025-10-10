<?php

namespace App\Service;

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
        $updateData = [
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'category_id' => $request->category_id,
        ];

        // Only process image if it's provided
        if ($request->image !== null) {
            $imagePath = $request->image;

            // If image is base64, save it to file
            if (str_starts_with($request->image, 'data:image/')) {
                $imagePath = $this->saveBase64Image($request->image);
            }

            $updateData['image'] = $imagePath;
        }

        return $this->productRepository->editProduct($request->id, $updateData);
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
