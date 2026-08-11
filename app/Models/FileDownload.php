<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FileDownload extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'downloadable_id', 'order_id', 'ip_address'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function downloadable(): BelongsTo
    {
        return $this->belongsTo(Downloadable::class);
    }
}
