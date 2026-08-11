<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyRate extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['base_currency', 'target_currency', 'rate'];

    protected function casts(): array
    {
        return ['rate' => 'decimal:6'];
    }
}
