<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Provincia extends Model{
  protected $table = 'provincias';

  public function ShowAllByIdDepartamento($id_departamento) {
    $data = DB::table('provincias')
          ->select('provincias.*')
          ->where('provincias.id_departamento', $id_departamento)
          ->orderby('descripcion')
          ->get();
    return $data;
  }
 
  public function getObtenerId_by_descripcion_and_idDepartamento($descripcion_provincia,$id_departamento)
  {
    $data = DB::table('provincias')
      ->select('provincias.*')
      ->where('provincias.descripcion', $descripcion_provincia)
      ->where('provincias.id_departamento', $id_departamento)
      ->get();
      
    return $data;
  }
}