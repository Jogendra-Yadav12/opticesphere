<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description'];

    public function planTiers(): BelongsToMany
    {
        return $this->belongsToMany(PlanTier::class, 'plan_tier_feature')->withPivot('value', 'is_included');
    }
}
