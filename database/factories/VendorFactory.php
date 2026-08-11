<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Vendor>
 */
class VendorFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->company();

        return [
            'user_id' => User::factory(),
            'store_name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(),
            'logo' => null,
            'banner' => null,
            'status' => 'approved',
            'commission_rate' => fake()->randomElement([5, 8, 10, 12, 15]),
            'commission_type' => 'percentage',
            'tax_number' => fake()->numerify('#########'),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'postal_code' => fake()->postcode(),
            'country' => fake()->country(),
            'phone' => fake()->numerify('+############'),
            'rating_avg' => fake()->randomFloat(2, 3, 5),
            'total_sales' => 0,
            'approved_at' => now(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'approved_at' => null,
        ]);
    }
}
