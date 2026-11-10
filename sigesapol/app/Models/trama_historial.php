<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class trama_historial extends Model
{
	public $timestamps = false;
	protected $table = 'trama_historial';
  
    public function listar_referencia_ajax($p)
  	{
    	return $this->readFunctionPostgres('sp_listar_referencias', $p);
  	}

    public function listar_auditoria_ajax($p)
  	{
    	return $this->readFunctionPostgres('sp_listar_auditoria_jhon', $p);
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