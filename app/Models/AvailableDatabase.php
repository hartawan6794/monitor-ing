<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AvailableDatabase extends Model
{
  use HasFactory;
  use SoftDeletes;

  protected $connection = 'central';

  protected $table = 'available_databases';

  protected $fillable = [
    'user_id',
    'server_id',
    'db_name',
    'description',
    'package_type',
    'expired_at'
  ];

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }

  public function server()
  {
    return $this->belongsTo(AuthorizedServer::class, 'server_id', 'id');
  }
}