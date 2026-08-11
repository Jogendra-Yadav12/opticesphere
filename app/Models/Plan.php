<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'type', 'status', 'price', 'duration_days', 'product_limit', 'purchase_enabled'];

    protected function casts(): array
    {
        return [
            'purchase_enabled' => 'boolean',
        ];
    }

    public function tiers(): HasMany
    {
        return $this->hasMany(PlanTier::class);
    }
}
