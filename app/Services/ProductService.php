<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public function create(array $data): Product
    {
        $product = Product::create([
            'title_en'       => $data['title_en'] ?? null,
            'title_id'       => $data['title_id'] ?? null,
            'description_en' => $data['description_en'] ?? null,
            'description_id' => $data['description_id'] ?? null,
            'features_en'    => $data['features_en'] ?? null,
            'features_id'    => $data['features_id'] ?? null,
        ]);

        return $product;
    }

    public function update(Product $product, array $data): Product
    {
        $product->update([
            'title_en'       => $data['title_en'] ?? null,
            'title_id'       => $data['title_id'] ?? null,
            'description_en' => $data['description_en'] ?? null,
            'description_id' => $data['description_id'] ?? null,
            'features_en'    => $data['features_en'] ?? null,
            'features_id'    => $data['features_id'] ?? null,
        ]);

        return $product;
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }
}