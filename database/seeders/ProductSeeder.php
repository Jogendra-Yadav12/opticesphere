<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $vendors = Vendor::approved()->get();
        $categories = Category::whereNotNull('parent_id')->get();

        Product::factory()->count(15)->create(['vendor_id' => $vendors->random()->id])->each(function (Product $product) use ($categories) {
            $product->categories()->attach($categories->random(rand(1, 3))->pluck('id'));
        });

        Product::factory()->count(3)->variable()->create(['vendor_id' => $vendors->random()->id])->each(function (Product $product) use ($categories) {
            $product->categories()->attach($categories->random(rand(1, 2))->pluck('id'));

            ProductVariant::factory()->count(rand(2, 4))->create([
                'product_id' => $product->id,
            ]);
        });

        Product::factory()->count(2)->digital()->create(['vendor_id' => $vendors->random()->id]);

        Product::query()->inRandomOrder()->limit(20)->get()->each(function (Product $product) {
            Review::factory()->count(rand(1, 2))->create([
                'reviewable_type' => Product::class,
                'reviewable_id' => $product->id,
            ]);
        });
    }
}
