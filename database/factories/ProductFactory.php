<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'vendor_id' => Vendor::factory(),
            'brand_id' => Brand::factory(),
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'sku' => strtoupper(fake()->unique()->bothify('SKU-####-????')),
            'short_description' => fake()->sentence(),
            'description' => fake()->paragraphs(3, true),
            'price' => fake()->randomFloat(2, 5, 999),
            'compare_at_price' => fake()->optional(0.3)->randomFloat(2, 1000, 1999),
            'cost_price' => fake()->randomFloat(2, 1, 800),
            'stock_quantity' => fake()->numberBetween(0, 500),
            'low_stock_threshold' => 5,
            'weight' => fake()->randomFloat(2, 0.1, 50),
            'height' => null,
            'width' => null,
            'length' => null,
            'product_type' => 'simple',
            'status' => 'active',
            'approval_status' => 'approved',
            'is_featured' => fake()->boolean(20),
            'is_taxable' => true,
            'meta_title' => null,
            'meta_description' => null,
            'approved_at' => now(),
        ];
    }

    public function variable(): static
    {
        return $this->state(fn (array $attributes) => [
            'product_type' => 'variable',
        ]);
    }

    public function digital(): static
    {
        return $this->state(fn (array $attributes) => [
            'product_type' => 'digital',
            'stock_quantity' => 999,
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }
}
