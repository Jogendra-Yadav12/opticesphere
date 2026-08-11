<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'two_factor_secret',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'two_factor_secret',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'two_factor_secret' => 'encrypted',
            'status' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function approvedProducts(): HasMany
    {
        return $this->hasMany(Product::class, 'approved_by');
    }

    public function reviewedDocuments(): HasMany
    {
        return $this->hasMany(VendorDocument::class, 'reviewed_by');
    }

    public function processedPayouts(): HasMany
    {
        return $this->hasMany(PayoutRequest::class, 'processed_by');
    }

    public function processedRefunds(): HasMany
    {
        return $this->hasMany(Refund::class, 'processed_by');
    }

    public function assignedTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class, 'assigned_to');
    }

    public function blogPosts(): HasMany
    {
        return $this->hasMany(BlogPost::class, 'admin_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'admin_id');
    }
}
