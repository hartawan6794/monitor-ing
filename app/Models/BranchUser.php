<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class BranchUser extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * Gunakan koneksi 'mysql' yang akan di-switch secara dinamis oleh middleware.
     * JANGAN gunakan koneksi 'central' karena tabel users ada di DB cabang.
     */
    protected $connection = 'mysql';

    /**
     * Primary Key configuration for CHAR(16) - ID berupa string seperti 'admin'
     */
    protected $table = 'users';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false; // Database cabang menggunakan updatetimestamp alami

    /**
     * The attributes that are mass assignable.
     * Sesuai struktur tabel users di database cabang:
     * id, userpassword, name, description, usercreate, isactive, useredit, updatetimestamp
     */
    protected $fillable = [
        'id',
        'userpassword',
        'name',
        'description',
        'usercreate',
        'isactive',
        'useredit'
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'userpassword',
    ];

    /**
     * Karena database cabang tidak menggunakan kolom password default Laravel,
     * kita tidak perlu password casting standar.
     */
}
