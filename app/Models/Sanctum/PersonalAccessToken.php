<?php

namespace App\Models\Sanctum;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    /**
     * Memaksa model ini untuk SELALU menggunakan database Master (Central).
     * Dengan begini, token tetap bisa ditemukan meskipun koneksi default sedang di-switch.
     */
    protected $connection = 'central';
}
