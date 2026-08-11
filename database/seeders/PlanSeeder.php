<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\Plan;
use App\Models\PlanTier;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $features = collect([
            'Products limit',
            'Transactions',
            'API access',
            'Priority support',
            'Custom reports',
            'Analytics',
        ])->map(fn (string $name) => Feature::firstOrCreate(['name' => $name, 'slug' => str()->slug($name)]));

        $software = Plan::factory()->create([
            'name' => 'SellMarket Suite',
            'slug' => 'sellmarket-suite',
            'description' => 'Admin-sold tiered subscription software suite.',
        ]);

        $tiers = [
            ['name' => 'Basic', 'price' => 29, 'sort' => 1],
            ['name' => 'Pro', 'price' => 79, 'sort' => 2],
            ['name' => 'Enterprise', 'price' => 199, 'sort' => 3],
        ];

        foreach ($tiers as $i => $tierData) {
            $tier = PlanTier::factory()->create([
                'plan_id' => $software->id,
                'name' => $tierData['name'],
                'slug' => str()->slug($tierData['name']),
                'price' => $tierData['price'],
                'billing_period' => 'monthly',
                'trial_days' => 14,
                'sort_order' => $tierData['sort'],
            ]);

            foreach ($features as $feature) {
                $tier->features()->attach($feature->id, [
                    'value' => $i === 2 ? 'unlimited' : (string) ($i + 1) * 100,
                    'is_included' => true,
                ]);
            }
        }

        Plan::firstOrCreate(
            ['slug' => 'free'],
            [
                'name' => 'Free',
                'description' => "Start selling with a basic storefront. No product listing, order management, and store page.",
                'type' => 'subscription',
                'status' => 'active',
                'price' => 0,
                'duration_days' => 30,
                'product_limit' => 20,
                'purchase_enabled' => false,
            ]
        );

        Plan::firstOrCreate(
            ['slug' => 'basic'],
            [
                'name' => 'Basic',
                'description' => "For growing sellers. Up to 100 products, product reviews, and coupon management.",
                'type' => 'subscription',
                'status' => 'active',
                'price' => 299,
                'duration_days' => 30,
                'product_limit' => 100,
            ]
        );

        Plan::firstOrCreate(
            ['slug' => 'pro'],
            [
                'name' => 'Pro',
                'description' => "Best for established stores. Unlimited products, advanced reports, and priority support.",
                'type' => 'subscription',
                'status' => 'active',
                'price' => 499,
                'duration_days' => 30,
                'product_limit' => 0,
            ]
        );
    }
}
