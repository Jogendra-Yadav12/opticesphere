<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Downloadable extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'file_path',
        'size',
        'download_limit',
        'download_count',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'download_limit' => 'integer',
            'download_count' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function downloads(): HasMany
    {
        return $this->hasMany(FileDownload::class);
    }
}
