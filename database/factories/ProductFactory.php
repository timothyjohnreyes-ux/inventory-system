<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * @var list<array{name: string, category: string, description: string, price: int}>
     */
    private const PRODUCTS = [
        [
            'name' => 'Wireless Mouse',
            'category' => 'Computer Accessories',
            'description' => 'Wireless optical mouse for desktop and laptop computers.',
            'price' => 450,
        ],
        [
            'name' => 'Mechanical Keyboard',
            'category' => 'Computer Accessories',
            'description' => 'Mechanical keyboard for typing on a desktop or laptop computer.',
            'price' => 1500,
        ],
        [
            'name' => 'USB-C Cable',
            'category' => 'Computer Accessories',
            'description' => 'USB-C cable for charging and data transfer with compatible devices.',
            'price' => 250,
        ],
        [
            'name' => 'HDMI Cable',
            'category' => 'Computer Accessories',
            'description' => 'HDMI cable for connecting a computer or media device to a display.',
            'price' => 350,
        ],
        [
            'name' => 'Laptop Stand',
            'category' => 'Computer Accessories',
            'description' => 'Stand that supports a laptop at a more comfortable viewing height.',
            'price' => 800,
        ],
        [
            'name' => 'USB Flash Drive',
            'category' => 'Computer Accessories',
            'description' => 'Portable USB drive for storing and transferring files.',
            'price' => 350,
        ],
        [
            'name' => 'Wireless Keyboard',
            'category' => 'Computer Accessories',
            'description' => 'Wireless keyboard for desktop and laptop computers.',
            'price' => 900,
        ],
        [
            'name' => 'Computer Headset',
            'category' => 'Computer Accessories',
            'description' => 'Computer headset with a microphone for calls and audio.',
            'price' => 700,
        ],
        [
            'name' => 'Webcam',
            'category' => 'Computer Accessories',
            'description' => 'USB webcam for video calls and online meetings.',
            'price' => 1200,
        ],
        [
            'name' => 'Power Strip',
            'category' => 'Electronics',
            'description' => 'Multi-outlet power strip for compatible electronic equipment.',
            'price' => 500,
        ],
        [
            'name' => 'A4 Bond Paper',
            'category' => 'Office Supplies',
            'description' => 'A4 paper for printing, copying, and everyday office documents.',
            'price' => 250,
        ],
        [
            'name' => 'Ballpen',
            'category' => 'Office Supplies',
            'description' => 'Ballpoint pen for writing and taking notes.',
            'price' => 10,
        ],
        [
            'name' => 'Notebook',
            'category' => 'Office Supplies',
            'description' => 'Ruled notebook for notes and record keeping.',
            'price' => 50,
        ],
        [
            'name' => 'Printer Ink',
            'category' => 'Printer Supplies',
            'description' => 'Ink cartridge for a compatible office printer.',
            'price' => 650,
        ],
        [
            'name' => 'Packing Tape',
            'category' => 'Packaging Materials',
            'description' => 'Adhesive packing tape for sealing cartons and parcels.',
            'price' => 50,
        ],
        [
            'name' => 'Bubble Wrap',
            'category' => 'Packaging Materials',
            'description' => 'Protective bubble wrap for cushioning items during packing.',
            'price' => 150,
        ],
        [
            'name' => 'Safety Gloves',
            'category' => 'Safety Equipment',
            'description' => 'Protective gloves for general workplace handling tasks.',
            'price' => 120,
        ],
        [
            'name' => 'Safety Helmet',
            'category' => 'Safety Equipment',
            'description' => 'Protective helmet for workplace head protection.',
            'price' => 650,
        ],
        [
            'name' => 'Office Chair',
            'category' => 'Furniture',
            'description' => 'Office chair for desk and workstation seating.',
            'price' => 3500,
        ],
        [
            'name' => 'Storage Box',
            'category' => 'Storage Equipment',
            'description' => 'Reusable storage box for organizing supplies and inventory.',
            'price' => 300,
        ],
        [
            'name' => 'Multipurpose Cleaner',
            'category' => 'Cleaning Supplies',
            'description' => 'Multipurpose cleaner for washable office surfaces.',
            'price' => 180,
        ],
        [
            'name' => 'Screwdriver Set',
            'category' => 'Tools',
            'description' => 'Hand screwdriver set for basic fastening and repair tasks.',
            'price' => 350,
        ],
        [
            'name' => 'Printer',
            'category' => 'Office Equipment',
            'description' => 'Office printer for producing paper documents.',
            'price' => 7000,
        ],
    ];

    /**
     * @return list<array{name: string, category: string, description: string, price: int}>
     */
    public static function catalog(): array
    {
        return self::PRODUCTS;
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $productIndex = fake()->unique()->randomElement(array_keys(self::PRODUCTS));
        $product = self::PRODUCTS[$productIndex];
        $category = Category::query()->firstOrCreate(
            ['name' => $product['category']],
            ['description' => CategoryFactory::catalog()[$product['category']]],
        );

        return [
            'category_id' => $category->id,
            'name' => $product['name'],
            'sku' => fake()->unique()->numerify('SKU-######'),
            'quantity' => fake()->numberBetween(5, 100),
            'price' => $product['price'],
            'description' => $product['description'],
        ];
    }
}
