<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logeo extends Model
{
  protected $table = 'logeos';
  public $timestamps = false;
  protected $primaryKey = 'id'; 
  protected $fillable = [
      'id_user', 
      'ip_address',
      'agent',
      'device_name',
      'platform',
      'browser',
      'login_status',
      'login_at',
      'logout_at',
      'created_at',
      'updated_at'
  ];
}
