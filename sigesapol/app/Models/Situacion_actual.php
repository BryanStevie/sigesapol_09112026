<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Situacion_actual extends Model 
{
  protected $table = 'situacion_personal';
  
  public function ShowSituacionActual () {
    $cad = "select id, descripcion from situacion_personal where id_estado_reg='1' order by descripcion asc";
    $data = DB::select($cad);
    return $data;
  }
}