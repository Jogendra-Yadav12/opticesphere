<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'variant_id',
        'selected_attributes',
        'quantity',
        'unit_price',
        'line_total',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price' => 'decimal:2',
            'line_total' => 'decimal:2',
            'selected_attributes' => 'array',
        ];
    }

    public function getSelectedOptionsAttribute(): array
    {
        if ($this->variant && $this->variant->attributeValues->isNotEmpty()) {
            return $this->variant->attributeValues->map(fn ($v) => [
                'name' => $v->attribute->name ?? 'Option',
                'value' => $v->value,
            ])->all();
        }

        if (! empty($this->selected_attributes)) {
            $map = $this->product->attributeValues->keyBy('id');

            return collect($this->selected_attributes)->map(function ($id) use ($map) {
                $value = $map->get((int) $id);

                return $value ? [
                    'name' => $value->attribute->name ?? 'Option',
                    'value' => $value->value,
                ] : null;
            })->filter()->values()->all();
        }

        return [];
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
