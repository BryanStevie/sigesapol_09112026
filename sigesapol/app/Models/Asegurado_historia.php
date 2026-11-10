<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Asegurado_historia extends Model
{
    protected $table = 'asegurado_historias';
    protected $primaryKey = 'id';

    protected $fillable = [
      'id_asegurado',
      'id_establecimiento',
      'nro_historia',
      'id_estado_reg',
      'created_at',
      'created_us',
    ]; 

}
