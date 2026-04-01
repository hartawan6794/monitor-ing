<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AvailableDatabase extends Model
{
  use HasFactory;
  use SoftDeletes;
  protected $table = 'available_databases';

  protected $fillable = [
    'server_id',
    'db_name',
    'description',
    'expired_at'
  ];

  public function server()
  {
    return $this->belongsTo(AuthorizedServer::class, 'server_id', 'id');
  }
}