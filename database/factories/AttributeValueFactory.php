<?php

namespace Database\Factories;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AttributeValue>
 */
class AttributeValueFactory extends Factory
{
    public function definition(): array
    {
        return [
            'attribute_id' => Attribute::factory(),
            'value' => fake()->randomElement(['Red', 'Blue', 'Green', 'Small', 'Medium', 'Large']),
            'color_code' => null,
            'sort_order' => 0,
        ];
    }
}
