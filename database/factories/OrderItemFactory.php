<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 5);
        $unitPrice = fake()->randomFloat(2, 5, 500);
        $lineTotal = round($unitPrice * $quantity, 2);
        $commissionRate = fake()->randomElement([5, 8, 10, 12, 15]);
        $commissionAmount = round($lineTotal * $commissionRate / 100, 2);

        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'variant_id' => null,
            'vendor_id' => Vendor::factory(),
            'product_name' => fake()->words(3, true),
            'sku' => strtoupper(fake()->bothify('SKU-####-????')),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'line_total' => $lineTotal,
            'tax_amount' => round($lineTotal * 0.10, 2),
            'discount_amount' => 0,
            'commission_rate' => $commissionRate,
            'commission_amount' => $commissionAmount,
            'vendor_earning' => round($lineTotal - $commissionAmount, 2),
            'refunded_quantity' => 0,
        ];
    }
}
