<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $connection = 'central';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'photo',
        'provisioning_status',
        'role',
    ];

    /** Valid provisioning statuses */
    public const PROVISIONING_STATUSES = [
        'unregistered' => ['label' => 'Belum Berlangganan', 'color' => '#f59e0b', 'icon' => 'ri-close-circle-line'],
        'pending'      => ['label' => 'Menunggu Koneksi DB', 'color' => '#6366f1', 'icon' => 'ri-loader-3-line'],
        'provisioned'  => ['label' => 'Aktif & Terhubung',  'color' => '#10b981', 'icon' => 'ri-shield-check-line'],
    ];

    // ── RBAC Helpers ──
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    /** User sudah bayar DAN minimal 1 DB terhubung */
    public function isProvisioned(): bool
    {
        return $this->provisioning_status === 'provisioned';
    }

    /** User sudah bayar tapi belum ada DB terhubung (menunggu setup admin) */
    public function isPendingProvisioning(): bool
    {
        return $this->provisioning_status === 'pending';
    }

    /**
     * Ubah provisioning_status user.
     * Hanya menerima nilai yang valid dari PROVISIONING_STATUSES.
     */
    public function setProvisioningStatus(string $status): bool
    {
        if (!array_key_exists($status, self::PROVISIONING_STATUSES)) {
            return false;
        }

        return $this->update(['provisioning_status' => $status]);
    }

    /** Label teks untuk status saat ini */
    public function provisioningLabel(): string
    {
        return self::PROVISIONING_STATUSES[$this->provisioning_status]['label'] ?? 'Unknown';
    }

    /** Warna hex untuk status saat ini */
    public function provisioningColor(): string
    {
        return self::PROVISIONING_STATUSES[$this->provisioning_status]['color'] ?? '#94a3b8';
    }

    public function availableDatabases()
    {
        return $this->hasMany(AvailableDatabase::class, 'user_id', 'id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'user_id');
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class, 'user_id')
            ->where('status', 'active')
            ->latest();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
