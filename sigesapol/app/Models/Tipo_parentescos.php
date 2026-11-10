<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Tipo_parentescos extends Model
{ 
  public function getTipo_parentescos()
  {
    $cad = "select * from tipo_parentescos where estado='ACTIVO' Order By id asc";
    $data = DB::select($cad);
    return $data;
  }
  public function getTipo_parentescos_by_id($id_tipo_parentescos)
  {
    $cad = "select * from tipo_parentescos where estado='ACTIVO' and id=" . $id_tipo_parentescos . " Order By id asc";
    $data = DB::select($cad);
    return $data;
  }

}
