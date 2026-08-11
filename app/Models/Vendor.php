<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'store_name',
        'slug',
        'description',
        'logo',
        'banner',
        'status',
        'commission_rate',
        'commission_type',
        'tax_number',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'phone',
        'rating_avg',
        'total_sales',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'commission_rate' => 'decimal:2',
            'rating_avg' => 'decimal:2',
            'total_sales' => 'decimal:2',
            'approved_at' => 'datetime',
        ];
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved')
            ->whereHas('user', fn (Builder $q) => $q->where('role', 'seller')->where('status', 'approved'));
    }

    public function scopeHasActivePlan(Builder $query): Builder
    {
        return $query->where(function (Builder $q) {
            $q->whereDoesntHave('user.subscriptions')
                ->orWhereHas('user.subscriptions', function (Builder $q2) {
                    $q2->whereIn('status', ['active', 'trialing'])
                        ->where(fn (Builder $q3) => $q3->whereNull('current_period_end')->orWhere('current_period_end', '>', now()));
                });
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(VendorDocument::class);
    }

    public function paymentMethods(): HasMany
    {
        return $this->hasMany(VendorPaymentMethod::class);
    }

    public function settings(): HasMany
    {
        return $this->hasMany(VendorSetting::class);
    }

    public function ledgerEntries(): HasMany
    {
        return $this->hasMany(VendorLedger::class);
    }

    public function payoutRequests(): HasMany
    {
        return $this->hasMany(PayoutRequest::class);
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function getStoreHoursAttribute(): array
    {
        $setting = $this->settings()->where('key', 'store_hours')->value('value');
        $data = $setting ? json_decode($setting, true) : null;

        return is_array($data) ? $data : [];
    }

    public function isOpenNow(): bool
    {
        $day = (int) date('w');
        $hours = $this->store_hours;

        if (! isset($hours[$day]) || ! empty($hours[$day]['is_closed'])) {
            return false;
        }

        $open = $hours[$day]['open'] ?? null;
        $close = $hours[$day]['close'] ?? null;

        if (! $open || ! $close) {
            return false;
        }

        $now = date('H:i');

        return $now >= $open && $now <= $close;
    }
}
