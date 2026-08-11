<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookCall extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'gateway',
        'event_id',
        'payload',
        'headers',
        'status',
        'error',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'headers' => 'array',
            'processed_at' => 'datetime',
        ];
    }
}
