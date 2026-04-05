<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorizedServer extends Model
{
  use HasFactory;
  protected $table = 'authorized_servers';

  protected $fillable = [
    'ip_address',
    'server_name',
    'username',
    'password',
    'port',
    'is_active',
  ];

  public function availableDatabases()
  {
    return $this->hasMany(AvailableDatabase::class, 'server_id', 'id');
  }
}