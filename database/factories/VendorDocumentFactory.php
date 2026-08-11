<?php

namespace Database\Factories;

use App\Models\VendorDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VendorDocument>
 */
class VendorDocumentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(['business_license', 'id_proof', 'tax_certificate', 'bank_proof']),
            'file_path' => 'documents/' . fake()->uuid() . '.pdf',
            'status' => 'approved',
            'notes' => null,
            'reviewed_at' => now(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'reviewed_at' => null,
        ]);
    }
}
