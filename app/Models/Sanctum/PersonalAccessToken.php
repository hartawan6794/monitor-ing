<?php

namespace App\Models\Sanctum;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    /**
     * Konfirmasi bahwa model ini selalu menggunakan koneksi 'central'
     * (Database Master) untuk menyimpan dan mencari token.
     */
    protected $connection = 'central';
}
