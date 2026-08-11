<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 20, 1000);
        $tax = round($subtotal * 0.10, 2);
        $shipping = fake()->randomElement([0, 4.99, 9.99]);
        $discount = fake()->boolean(20) ? round($subtotal * 0.10, 2) : 0;

        return [
            'order_number' => 'ORD-' . now()->format('Y') . '-' . Str::upper(Str::random(8)),
            'user_id' => User::factory(),
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'tax_amount' => $tax,
            'shipping_amount' => $shipping,
            'total_amount' => round($subtotal + $tax + $shipping - $discount, 2),
            'currency' => 'USD',
            'coupon_code' => null,
            'status' => fake()->randomElement(['pending', 'processing', 'shipped', 'delivered', 'cancelled']),
            'payment_status' => 'paid',
            'payment_method' => fake()->randomElement(['stripe', 'razorpay', 'paypal', 'cod']),
            'shipping_address_id' => null,
            'billing_address_id' => null,
            'notes' => null,
            'gateway_charge_id' => null,
            'placed_at' => now(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);
    }
}
