<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class OrderStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'status', 'comment', 'causer_type', 'causer_id'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function causer(): MorphTo
    {
        return $this->morphTo();
    }
}
