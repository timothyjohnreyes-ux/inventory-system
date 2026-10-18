<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * @var array<string, string>
     */
    private const CATEGORIES = [
        'Electronics' => 'Electronic devices and electrical accessories used in the workplace.',
        'Computer Accessories' => 'Peripherals and accessories used with desktop and laptop computers.',
        'Office Supplies' => 'Paper and writing materials used for daily office work.',
        'Cleaning Supplies' => 'Products and tools used to clean office surfaces and equipment.',
        'Packaging Materials' => 'Materials used to protect and seal items for storage or shipping.',
        'Safety Equipment' => 'Protective equipment used to reduce common workplace hazards.',
        'Furniture' => 'Furniture used to equip offices and work areas.',
        'Tools' => 'Hand tools used for basic maintenance and repair tasks.',
        'Printer Supplies' => 'Consumables used by office printers.',
        'Storage Equipment' => 'Containers and equipment used to organize or store inventory.',
        'Office Equipment' => 'Machines and equipment used for office operations.',
    ];

    /**
     * @return array<string, string>
     */
    public static function catalog(): array
    {
        return self::CATEGORIES;
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categoryName = fake()->unique()->randomElement(array_keys(self::CATEGORIES));

        return [
            'name' => $categoryName,
            'description' => self::CATEGORIES[$categoryName],
        ];
    }
}
