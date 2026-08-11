<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'name' => fake()->randomElement(['Small', 'Medium', 'Large', 'Black', 'White', 'Red']),
            'sku' => strtoupper(fake()->unique()->bothify('VAR-####-????')),
            'price' => fake()->randomFloat(2, 5, 999),
            'compare_at_price' => null,
            'stock_quantity' => fake()->numberBetween(0, 200),
            'barcode' => fake()->ean13(),
            'status' => 'active',
        ];
    }
}
