<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchLog extends Model
{
    use HasFactory;

    protected $fillable = ['query', 'results_count', 'ip_address'];

    protected function casts(): array
    {
        return ['results_count' => 'integer'];
    }
}
