<?php

namespace Database\Factories;

use App\Models\PlanTier;
use App\Models\Subscription;
use App\Models\SubscriptionItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SubscriptionItem>
 */
class SubscriptionItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'subscription_id' => Subscription::factory(),
            'plan_tier_id' => PlanTier::factory(),
            'quantity' => 1,
        ];
    }
}
