<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Coupon>
 */
class CouponFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->unique()->bothify('COUPON-####')),
            'type' => fake()->randomElement(['fixed', 'percent', 'free_shipping']),
            'value' => fake()->randomFloat(2, 5, 50),
            'max_uses' => fake()->numberBetween(10, 500),
            'used_count' => 0,
            'min_order_amount' => fake()->optional()->randomFloat(2, 20, 100),
            'starts_at' => now(),
            'expires_at' => now()->addDays(fake()->numberBetween(7, 90)),
            'is_active' => true,
        ];
    }
}
