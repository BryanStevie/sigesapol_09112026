<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Dependencia extends Model {

    function getDependencia($opcion) {
        
        $cad = "Select id, nombre_dependencia as nombre From dependencias where estado=1  and opcion!='".$opcion."' Order By id";    

        $data = DB::select($cad);
        return $data;
    }
    
    function getTodasDependencias() {
        $cad = "Select id, nombre_dependencia as nombre, opcion, estado From dependencias Order By id";
        $data = DB::select($cad);
        return $data;
    }

    public function dependencia_yaregistrada($nombre){
        $datos=DB::table('dependencias') 
                ->where('dependencias.nombre_dependencia','=',$nombre)
                ->select('dependencias.*')
                ->first();
        return $datos;                      
    }

    public function listar_dependencias_ajax($p)
  {
    return $this->readFunctionPostgres('sp_listar_dependencias', $p);
  }

  public function readFunctionPostgres($function, $parameters = null)
  {

    $_parameters = '';
    if (count($parameters) > 0) {
      $_parameters = implode("','", $parameters);
      $_parameters = "'" . $_parameters . "',";
    }
    $data = DB::select("BEGIN;");
    $cad = "select " . $function . "(" . $_parameters . "'ref_cursor');";

    $data = DB::select($cad);
    $cad = "FETCH ALL IN ref_cursor;";
    $data = DB::select($cad);



    return $data;
  }


}