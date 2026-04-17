<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatabaseAccessKey extends Model
{
    protected $connection = 'central';

    protected $fillable = ['user_id', 'available_database_id', 'access_key', 'expires_at'];

    protected $casts = ['expires_at' => 'datetime'];

    public function availableDatabase()
    {
        return $this->belongsTo(AvailableDatabase::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Cek apakah key masih valid (belum expired).
     */
    public function isValid(): bool
    {
        return $this->expires_at->isFuture();
    }
}
