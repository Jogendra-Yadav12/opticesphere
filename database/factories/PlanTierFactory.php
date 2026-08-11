<?php

namespace Database\Factories;

use App\Models\Plan;
use App\Models\PlanTier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PlanTier>
 */
class PlanTierFactory extends Factory
{
    public function definition(): array
    {
        return [
            'plan_id' => Plan::factory(),
            'name' => fake()->randomElement(['Basic', 'Pro', 'Enterprise']),
            'slug' => fake()->unique()->slug(),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 9, 499),
            'billing_period' => fake()->randomElement(['monthly', 'yearly']),
            'trial_days' => fake()->randomElement([0, 7, 14]),
            'sort_order' => 0,
            'is_active' => true,
        ];
    }
}
