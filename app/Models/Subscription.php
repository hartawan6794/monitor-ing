<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $connection = 'central';

    protected $fillable = [
        'user_id',
        'pricing_plan_id',
        'starts_at',
        'expires_at',
        'status',
        'last_reminded_at',
        'remind_count',
        'notes',
    ];

    protected $casts = [
        'starts_at' => 'date',
        'expires_at' => 'date',
        'last_reminded_at' => 'datetime',
    ];

    // ── RELASI ──

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pricingPlan()
    {
        return $this->belongsTo(PricingPlan::class);
    }

    // ── SCOPES ──

    /**
     * Langganan yang akan expired dalam X hari ke depan.
     */
    public function scopeExpiringWithin($query, int $days)
    {
        return $query->where('status', 'active')
            ->whereBetween('expires_at', [now()->toDateString(), now()->addDays($days)->toDateString()]);
    }

    /**
     * Langganan yang sudah melewati tanggal expired.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'active')
            ->where('expires_at', '<', now()->toDateString());
    }

    // ── HELPERS ──

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->expires_at->gte(now());
    }

    public function daysUntilExpiry(): int
    {
        return (int) now()->startOfDay()->diffInDays($this->expires_at, false);
    }
}
