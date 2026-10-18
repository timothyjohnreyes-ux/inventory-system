<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Database\Factories\CategoryFactory;
use Database\Factories\ProductFactory;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    /**
     * Seed an inventory catalog with products assigned to their matching categories.
     */
    public function run(): void
    {
        $categoryIds = [];

        foreach (CategoryFactory::catalog() as $categoryName => $description) {
            $category = Category::query()->updateOrCreate(
                ['name' => $categoryName],
                ['description' => $description],
            );

            $categoryIds[$categoryName] = $category->id;
        }

        foreach (ProductFactory::catalog() as $catalogItem) {
            $product = Product::query()->firstOrNew(['name' => $catalogItem['name']]);

            if (! $product->exists) {
                $product->sku = $this->generateUniqueSku();
                $product->quantity = fake()->numberBetween(5, 100);
            }

            $product->category_id = $categoryIds[$catalogItem['category']];
            $product->price = $catalogItem['price'];
            $product->description = $catalogItem['description'];
            $product->save();
        }
    }

    private function generateUniqueSku(): string
    {
        do {
            $sku = fake()->unique()->numerify('SKU-######');
        } while (Product::query()->where('sku', $sku)->exists());

        return $sku;
    }
}
