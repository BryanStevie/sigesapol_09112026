<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class trama_productos extends Model
{
	protected $table = 'trama_productos';
	protected $primaryKey = 'id';
  public $timestamps = false;
	protected $fillable = [
		'renipress',
		'numero_lote_fups',
		'numero_fups',
		'id_cie10',
		'id_prod_med',
		'cantidad_prescrita',
		'cantidad_entregada',
		'precio',
		'valorizacion',
		'correlativo',
		'id_trama_prestacion',
		'id_producto_recetas',
		'tipo_producto',
		'estado',
		'actualizado',
		'historico',
		'created_us',
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