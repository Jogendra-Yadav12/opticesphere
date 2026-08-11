<?php

namespace Database\Factories;

use App\Models\PlanTier;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subscription>
 */
class SubscriptionFactory extends Factory
{
    public function definition(): array
    {
        $period = fake()->randomElement(['monthly', 'yearly']);

        return [
            'user_id' => User::factory(),
            'plan_tier_id' => PlanTier::factory(),
            'status' => fake()->randomElement(['active', 'trialing', 'past_due', 'cancelled']),
            'current_period_start' => now(),
            'current_period_end' => now()->addMonths($period === 'yearly' ? 12 : 1),
            'trial_ends_at' => fake()->boolean(30) ? now()->addDays(7) : null,
            'ends_at' => null,
            'cancel_at_period_end' => fake()->boolean(10),
            'gateway' => 'stripe',
            'gateway_subscription_id' => 'sub_' . fake()->unique()->bothify('####'),
            'gateway_customer_id' => 'cus_' . fake()->unique()->bothify('####'),
            'price' => fake()->randomFloat(2, 9, 499),
            'billing_period' => $period,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }
}
