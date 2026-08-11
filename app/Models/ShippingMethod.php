<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'slug',
        'description',
        'base_cost',
        'cost_per_kg',
        'estimated_days_min',
        'estimated_days_max',
        'is_active',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'base_cost' => 'decimal:2',
            'cost_per_kg' => 'decimal:2',
            'estimated_days_min' => 'integer',
            'estimated_days_max' => 'integer',
            'is_active' => 'boolean',
            'settings' => 'array',
        ];
    }

    public function getCostAttribute(): string
    {
        return (string) $this->base_cost;
    }

    public function zones(): BelongsToMany
    {
        return $this->belongsToMany(ShippingZone::class, 'shipping_method_zone')->withPivot('cost');
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }
}
