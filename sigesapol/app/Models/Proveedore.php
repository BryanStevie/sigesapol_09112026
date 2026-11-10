<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Proveedore extends Model {

    function getProveedor() {
        $cad = "Select * From proveedores Where estado=1 Order By id";
        $data = DB::select($cad);
        return $data;
    }

    function getTodosProveedor() {
        $cad = "Select * From proveedores Order By id";
        $data = DB::select($cad);
        return $data;
    }

    public function medico_yaregistrado($ruc){
        $datos=DB::table('proveedores') 
                ->where('proveedores.ruc_proveedor','=',$ruc)
                ->select('proveedores.*')
                ->first();
        return $datos;                      
    }
	
	function getProveedorActivo() {
        $cad = "Select id, nombre_proveedor as nombre From proveedores Where estado=1 Order By id";
        $data = DB::select($cad);
        return $data;
    }

    public function listar_proveedores_ajax($p)
  {
    return $this->readFunctionPostgres('sp_listar_proveedores', $p);
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