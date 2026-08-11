<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsageRecord extends Model
{
    use HasFactory;

    protected $fillable = ['subscription_id', 'metric', 'quantity', 'recorded_on'];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'recorded_on' => 'date',
        ];
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }
}
