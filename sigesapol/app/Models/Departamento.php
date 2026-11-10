<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Departamento extends Model {
  protected $table = 'departamentos';
  
  public function ShowAll() {
    $data = DB::table('departamentos')
        ->select('departamentos.*')
        ->orderby('descripcion')
        ->get();
    return $data;
  }

  function getObtenerId_by_descripcion($descripcion_departamento)
  {
    $datos = DB::table('departamentos')
      ->select('departamentos.*')
      ->where('departamentos.descripcion', $descripcion_departamento)
      ->get();
    return $datos;
  }
}