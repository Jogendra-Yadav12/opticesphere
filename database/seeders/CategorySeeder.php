<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $parents = [
            'Electronics',
            'Fashion',
            'Home & Garden',
            'Sports & Outdoors',
            'Toys & Games',
            'Health & Beauty',
        ];

        $childrenByParent = [
            'Electronics' => ['Phones', 'Laptops', 'Accessories', 'Audio'],
            'Fashion' => ['Men', 'Women', 'Kids', 'Footwear'],
            'Home & Garden' => ['Furniture', 'Kitchen', 'Decor', 'Garden'],
            'Sports & Outdoors' => ['Fitness', 'Camping', 'Cycling', 'Team Sports'],
            'Toys & Games' => ['Action Figures', 'Board Games', 'Puzzles', 'Building Sets'],
            'Health & Beauty' => ['Skincare', 'Haircare', 'Makeup', 'Wellness'],
        ];

        foreach ($parents as $parentName) {
            $parent = Category::factory()->create(['name' => $parentName]);

            foreach ($childrenByParent[$parentName] as $childName) {
                Category::factory()->child($parent)->create(['name' => $childName]);
            }
        }

        Brand::factory()->count(15)->create();
    }
}
