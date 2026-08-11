<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Banner;
use App\Models\Coupon;
use App\Models\PaymentMethod;
use App\Models\Setting;
use App\Models\ShippingMethod;
use App\Models\Subscription;
use App\Models\SubscriptionItem;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            CategorySeeder::class,
            VendorSeeder::class,
            ProductSeeder::class,
            PlanSeeder::class,
            OrderSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@sellmarket.test',
            'role' => 'admin',
            'status' => 'active',
        ]);

        $customer = User::factory()->create([
            'name' => 'Demo Customer',
            'email' => 'customer@sellmarket.test',
        ]);

        Address::factory()->count(2)->create(['user_id' => $customer->id]);

        Subscription::factory()->active()->count(3)->create(['user_id' => $customer->id])->each(function (Subscription $subscription) {
            SubscriptionItem::factory()->create([
                'subscription_id' => $subscription->id,
                'plan_tier_id' => $subscription->plan_tier_id,
            ]);
        });

        Coupon::factory()->count(10)->create();

        $shippingMethods = [
            ['name' => 'Standard', 'code' => 'standard', 'slug' => 'standard', 'base_cost' => 49, 'cost_per_kg' => 10, 'estimated_days_min' => 3, 'estimated_days_max' => 7, 'description' => 'Standard delivery across all pin codes.', 'settings' => ['note' => 'Usually delivered within a week.']],
            ['name' => 'Express', 'code' => 'express', 'slug' => 'express', 'base_cost' => 99, 'cost_per_kg' => 20, 'estimated_days_min' => 1, 'estimated_days_max' => 2, 'description' => 'Fast delivery for urgent orders.', 'settings' => ['note' => 'Metro cities only.']],
            ['name' => 'Overnight', 'code' => 'overnight', 'slug' => 'overnight', 'base_cost' => 199, 'cost_per_kg' => 30, 'estimated_days_min' => 1, 'estimated_days_max' => 1, 'description' => 'Next day delivery.', 'settings' => ['note' => 'Select locations only.']],
        ];

        foreach ($shippingMethods as $method) {
            ShippingMethod::updateOrCreate(['code' => $method['code']], array_merge($method, ['is_active' => true]));
        }

        $settings = [
            'store_name' => 'SellMarket Multi-Vendor Store',
            'contact_email' => 'support@sellmarket.test',
            'support_phone' => '+1 (800) 123-4567',
            'store_logo' => '',
            'global_currency' => 'INR',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], [
                'value' => $value,
                'group' => 'general',
                'type' => $key === 'store_logo' ? 'image' : 'text',
            ]);
        }

        $banners = [
            ['title' => 'Welcome to SellMarket', 'subtitle' => 'The best deals across categories', 'link' => 'shop'],
            ['title' => 'New Season, New Styles', 'subtitle' => 'Shop the latest collection', 'link' => 'shop'],
            ['title' => 'Huge Savings This Week', 'subtitle' => 'Limited time offers', 'link' => 'shop'],
        ];

        foreach ($banners as $i => $banner) {
            Banner::create(array_merge($banner, [
                'image' => '',
                'position' => 'hero',
                'sort_order' => $i + 1,
                'is_active' => true,
            ]));
        }

        $paymentMethods = [
            ['name' => 'Cash on Delivery', 'code' => 'cod', 'description' => 'Pay in cash when your order arrives.'],
            ['name' => 'Razorpay', 'code' => 'razorpay', 'description' => 'Pay securely via Razorpay (UPI, cards, wallets).'],
            ['name' => 'PayPal', 'code' => 'paypal', 'description' => 'Pay securely using your PayPal account.'],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::updateOrCreate(['code' => $method['code']], array_merge($method, [
                'credentials' => null,
                'is_active' => $method['code'] === 'cod',
            ]));
        }
    }
}
