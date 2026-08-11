<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class NotificationPreference extends Model
{
    use HasFactory;

    protected $fillable = ['notifiable_type', 'notifiable_id', 'event', 'channel', 'enabled'];

    protected function casts(): array
    {
        return ['enabled' => 'boolean'];
    }

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }
}
