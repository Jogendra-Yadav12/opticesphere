<?php

namespace Database\Factories;

use App\Models\ShippingMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ShippingMethod>
 */
class ShippingMethodFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->randomElement(['Standard', 'Express', 'Overnight']);

        return [
            'name' => $name,
            'slug' => strtolower($name),
            'description' => fake()->sentence(),
            'base_cost' => fake()->randomFloat(2, 0, 10),
            'cost_per_kg' => fake()->randomFloat(2, 0.5, 3),
            'estimated_days_min' => fake()->numberBetween(1, 3),
            'estimated_days_max' => fake()->numberBetween(4, 10),
            'is_active' => true,
        ];
    }
}
