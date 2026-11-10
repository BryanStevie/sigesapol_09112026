<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Violencia extends Model
{

  public function ShowViolencia()
  {
    $cad = "select id, descripcion from violencia Order By descripcion asc ";
    $data = DB::select($cad);
    return $data;
  }

  public function showTipo_violencia($id_violencia)
  {
    $cad = "select * from tipo_violencia where id_violencia=" . $id_violencia . " Order By descripcion asc ";
    $data = DB::select($cad);
    return $data;
  }
}