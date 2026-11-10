<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;


class Punto extends Model
{
    
  protected $table = 'puntos';

  public function getPuntosAdmision($id_atencion_admision, $tipo_registro, $id_tipo_atenciones) {
    $data = Punto::where('id_enfermeria',$id_atencion_admision)->where('id_tipo_atenciones',$id_tipo_atenciones)->where('tipo_registro',$tipo_registro)->get();
    return $data;
  }
}