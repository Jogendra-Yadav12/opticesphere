<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::factory()->count(20)->create();
        $vendors = \App\Models\Vendor::approved()->get();

        Order::factory()->count(150)->create([
            'user_id' => $users->random()->id,
        ])->each(function (Order $order) use ($vendors) {
            OrderItem::factory()->count(rand(1, 4))->create([
                'order_id' => $order->id,
                'vendor_id' => $vendors->random()->id,
            ]);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => $order->status,
                'comment' => 'Order seeded',
            ]);
        });
    }
}
