<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'code',
        'type',
        'value',
        'max_uses',
        'used_count',
        'min_order_amount',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'max_uses' => 'integer',
            'used_count' => 'integer',
            'min_order_amount' => 'decimal:2',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('usage_count');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function isScopedToVendor(): bool
    {
        return $this->vendor_id !== null;
    }

    public function appliesToItem($item): bool
    {
        return ! $this->isScopedToVendor() || ($item->product?->vendor_id ?? $item->vendor_id) === (int) $this->vendor_id;
    }

    public function getDiscountTypeAttribute(): string
    {
        return match ($this->type) {
            'fixed' => 'fixed',
            'free_shipping' => 'fixed',
            default => 'percentage',
        };
    }

    public function getDiscountValueAttribute(): string
    {
        return (string) $this->value;
    }

    public function isValid(float $subtotal): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }

        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) {
            return false;
        }

        if ($this->min_order_amount !== null && $subtotal < (float) $this->min_order_amount) {
            return false;
        }

        return true;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'fixed') {
            return min((float) $this->value, $subtotal);
        }

        return round($subtotal * ((float) $this->value / 100), 2);
    }
}
