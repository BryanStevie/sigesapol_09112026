<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;


class Balance_hidrico extends Model
{
    
  protected $table = 'balance_hidricos';

  public function datosPacienteBH($id_enfermeria){
    $bh_datos_paciente = DB::select('SELECT * FROM sp_get_cabecera_enfermeria(?)', [$id_enfermeria]);
    return $bh_datos_paciente;
  }

  public function datosPacienteBH_sa($id_enfermeria){
    $bh_datos_paciente = DB::select('SELECT * FROM sp_get_cabecera_enfermeria_sa(?)', [$id_enfermeria]);
    return $bh_datos_paciente;
  }

  public function datosPacienteBHHospi($id_enfermeria){
    $bh_datos_paciente = DB::select('SELECT * FROM sp_get_cabecera_hospitalizacion_enfermeria(?)', [$id_enfermeria]);
    return $bh_datos_paciente;
  }
   
}