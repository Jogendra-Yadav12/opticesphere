<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ShippingZone extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'countries'];

    protected function casts(): array
    {
        return ['countries' => 'array'];
    }

    public function methods(): BelongsToMany
    {
        return $this->belongsToMany(ShippingMethod::class, 'shipping_method_zone')->withPivot('cost');
    }
}
