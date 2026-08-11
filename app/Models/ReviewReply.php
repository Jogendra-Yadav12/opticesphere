<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ReviewReply extends Model
{
    use HasFactory;

    protected $fillable = ['review_id', 'replier_type', 'replier_id', 'body'];

    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class);
    }

    public function replier(): MorphTo
    {
        return $this->morphTo();
    }
}
