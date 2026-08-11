<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'status',
        'role',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    public function vendor(): HasOne
    {
        return $this->hasOne(Vendor::class);
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class)->latestOfMany();
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function oauthAccounts(): HasMany
    {
        return $this->hasMany(OauthAccount::class);
    }

    public function coupons(): BelongsToMany
    {
        return $this->belongsToMany(Coupon::class)->withPivot('usage_count');
    }

    public function getShopNameAttribute(): string
    {
        return $this->vendor?->store_name ?? '';
    }

    public function getStoreLogoAttribute(): ?string
    {
        return $this->vendor?->logo;
    }

    public function getStoreAddressAttribute(): string
    {
        return $this->vendor?->address ?? '';
    }

    public function getStoreBannerAttribute(): ?string
    {
        return $this->vendor?->banner;
    }

    public function getStoreDescriptionAttribute(): string
    {
        return $this->vendor?->description ?? '';
    }

    public function getStoreCityAttribute(): string
    {
        return $this->vendor?->city ?? '';
    }

    public function getStoreStateAttribute(): string
    {
        return $this->vendor?->state ?? '';
    }

    public function getStorePostalCodeAttribute(): string
    {
        return $this->vendor?->postal_code ?? '';
    }

    public function getStoreCountryAttribute(): string
    {
        return $this->vendor?->country ?? '';
    }

    public function getSellerPlanAttribute(): string
    {
        $subscription = $this->subscriptions()->latest('id')->first();

        if ($subscription?->planTier?->plan) {
            return $subscription->planTier->plan->name;
        }

        return 'Free';
    }

    public function currentPlan(): ?Plan
    {
        $subscription = $this->subscriptions()
            ->whereIn('status', ['active', 'trialing'])
            ->latest('id')
            ->first();

        if ($subscription?->planTier?->plan) {
            return $subscription->planTier->plan;
        }

        return Plan::where('slug', 'free')->first();
    }
}
