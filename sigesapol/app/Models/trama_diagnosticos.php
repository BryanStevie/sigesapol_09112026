<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class trama_diagnosticos extends Model
{
	protected $table = 'trama_diagnosticos';
	protected $primaryKey = 'id';
  public $timestamps = false;
	protected $fillable = [
		'renipress',
		'numero_lote_fups',
		'numero_fups',
		'id_cie10',
		'id_identificador_diag',
		'id_tipo_diag',
		'id_trama_prestacion',
		'id_diagnostico',
		'tipo_diag',
		'estado',
		'updated_at',
		'created_at',
		'created_us',
		'updated_us',
		'actualizado',
		'historico',
	];
  
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