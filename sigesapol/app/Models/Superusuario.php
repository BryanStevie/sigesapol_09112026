<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Superusuario extends Model {

    public function ShowPrioridad(){
    	$cad = "select id, codigo, descripcion as prioridad  from prioridades f where estado = 'N' Order By id asc";
        $data = DB::select($cad);
        return $data;
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

    public function listar_usuarios_modificados_ajax($p)
    {
      return $this->readFunctionPostgres('listar_usuarios_modificados_ajax', $p);
    }
}