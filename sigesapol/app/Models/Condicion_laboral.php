<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Condicion_laboral extends Model 
{
  protected $table = 'condicion_laboral';

  public function ShowCondicionActual () {
    $cad = "select id, descripcion from condicion_laboral where id_estado_reg='1' order by descripcion asc";
    $data = DB::select($cad);
    return $data;
  }
}