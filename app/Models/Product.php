<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'brand_id',
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'price',
        'compare_at_price',
        'special_price',
        'cost_price',
        'stock_quantity',
        'low_stock_threshold',
        'weight',
        'height',
        'width',
        'length',
        'product_type',
        'status',
        'approval_status',
        'is_featured',
        'is_taxable',
        'meta_title',
        'meta_description',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_at_price' => 'decimal:2',
            'special_price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'weight' => 'decimal:2',
            'height' => 'decimal:2',
            'width' => 'decimal:2',
            'length' => 'decimal:2',
            'stock_quantity' => 'integer',
            'low_stock_threshold' => 'integer',
            'is_featured' => 'boolean',
            'is_taxable' => 'boolean',
            'approved_at' => 'datetime',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active')->where('approval_status', 'approved');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query
            ->where('status', 'active')
            ->where('approval_status', 'approved')
            ->whereHas('vendor', function (Builder $q) {
                $q->where('status', 'approved')
                    ->where(function (Builder $q2) {
                        $q2->whereHas('user', fn (Builder $q3) => $q3->where('role', 'admin'))
                            ->orWhereHas('user', function (Builder $q3) {
                                $q3->where('role', 'seller')
                                    ->where('status', 'approved')
                                    ->where(function (Builder $q4) {
                                        $q4->whereDoesntHave('subscriptions')
                                            ->orWhereHas('subscriptions', function (Builder $q5) {
                                                $q5->whereIn('status', ['active', 'trialing'])
                                                    ->where(fn (Builder $q6) => $q6->whereNull('current_period_end')->orWhere('current_period_end', '>', now()));
                                            });
                                    });
                            });
                    });
            })
            ->withinPlanLimit();
    }

    public function scopeWithinPlanLimit(Builder $query): Builder
    {
        $planLimitSql = '
            COALESCE(
                CASE WHEN (
                    SELECT u.role FROM users u
                    WHERE u.id = (SELECT v.user_id FROM vendors v WHERE v.id = products.vendor_id)
                ) = \'admin\' THEN 0 ELSE NULL END,
                (
                    SELECT plans.product_limit
                    FROM subscriptions
                    JOIN plan_tiers ON plan_tiers.id = subscriptions.plan_tier_id
                    JOIN plans ON plans.id = plan_tiers.plan_id
                    WHERE subscriptions.user_id = (
                        SELECT v.user_id FROM vendors v WHERE v.id = products.vendor_id
                    )
                    AND subscriptions.status IN (\'active\', \'trialing\')
                    AND (subscriptions.current_period_end IS NULL OR subscriptions.current_period_end > NOW())
                    ORDER BY subscriptions.id DESC
                    LIMIT 1
                ),
                (
                    SELECT product_limit FROM plans WHERE slug = \'free\' AND status = \'active\' LIMIT 1
                ),
                0
            )';

        return $query->where(function (Builder $q) use ($planLimitSql) {
            $q->whereRaw('('.$planLimitSql.') = 0')
                ->orWhereRaw('(
                    SELECT COUNT(*)
                    FROM products AS p2
                    WHERE p2.vendor_id = products.vendor_id
                      AND p2.status = \'active\'
                      AND p2.approval_status = \'approved\'
                      AND (
                          p2.is_featured > products.is_featured
                          OR (p2.is_featured = products.is_featured AND p2.id <= products.id)
                      )
                ) <= ('.$planLimitSql.')');
        });
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public static function nonPurchasableVendorIds(): array
    {
        return Vendor::query()
            ->whereHas('user', fn (Builder $q) => $q->where('role', 'seller'))
            ->whereHas('user.subscriptions', function (Builder $q) {
                $q->whereIn('status', ['active', 'trialing'])
                    ->where(fn (Builder $q2) => $q2->whereNull('current_period_end')->orWhere('current_period_end', '>', now()))
                    ->whereHas('planTier.plan', fn (Builder $q3) => $q3->where('purchase_enabled', false));
            })
            ->pluck('id')
            ->all();
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(AttributeValue::class, 'product_attribute_value', 'product_id', 'attribute_value_id')
            ->withPivot('price_adjustment');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage(): HasMany
    {
        return $this->hasMany(ProductImage::class)->where('is_primary', true);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function downloadables(): HasMany
    {
        return $this->hasMany(Downloadable::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getImageAttribute(): ?string
    {
        $primary = $this->images()->where('is_primary', true)->value('path');

        return $primary ?? $this->images()->value('path');
    }

    public function getStockAttribute(): int
    {
        return (int) $this->stock_quantity;
    }

    public function getCategoryAttribute(): ?Category
    {
        return $this->categories()->first();
    }

    public function getCategoryIdAttribute(): ?int
    {
        return $this->categories()->value('category_id');
    }
}
