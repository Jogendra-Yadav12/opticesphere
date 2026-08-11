<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorPaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'type',
        'details',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'details' => 'array',
            'is_default' => 'boolean',
        ];
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
