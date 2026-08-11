<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'vendor_id',
        'rate_type',
        'rate',
        'amount',
        'status',
        'settled_at',
    ];

    protected function casts(): array
    {
        return [
            'rate' => 'decimal:2',
            'amount' => 'decimal:2',
            'settled_at' => 'datetime',
        ];
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
