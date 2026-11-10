<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class TipoViolencia extends Model
{

  public function ShowTipoViolencia()
  {
    $cad = "select id_tipo_violencia, descripcion from tipo_violencia Order By descripcion asc ";
    $data = DB::select($cad);
    return $data;
  }


  public function BuscarTipoViolenciaIdViolencia($id_violencia)
  {
    $cad = "select id_tipo_violencia, descripcion from tipo_violencia where id_violencia = {$id_violencia} Order By descripcion asc";
    $data = DB::select($cad);
    return $data;
  }
}