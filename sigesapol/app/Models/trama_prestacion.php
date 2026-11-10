<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class trama_prestacion extends Model
{
	protected $table = 'trama_prestacion';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $fillable = [
		'codigo_renipress',
		'numero_lote',
		'numero_correlativo',
		'procedencia_prestacion',
		'ipress_referencia_fups',
		'hoja_referencia_fups',
		'tipo_doc_identidad',
		'paciente_nro_doc_identidad',
		'paciente_apellido_paterno',
		'paciente_apellido_materno',
		'paciente_nombres',
		'id_hist_clinica',
		'id_tipo_cobert',
		'id_upss_fups',
		'fecha_atencion_fups',
		'fecha_ingreso_fups',
		'fecha_inicio_adm',
		'fecha_cierre_adm_fups',
		'fecha_egreso_fups',
		'renipress_vincula_fups',
		'num_lote_vincula_fups',
		'num_vincula_fups',
		'estado_paci_mujer_fups',
		'fecha_probable_parto_fups',
		'fecha_parto_fups',
		'id_destino_aseg_fups',
		'ipress_destino_fups',
		'num_hoja_destino_fups',
		'fecha_fallece',
		'numero_acreditacion_siteds',
		'id_tipodoc_ident',
		'numdoc_ident',
		'doc_ape_paterno',
		'doc_ape_materno',
		'doc_nombres',
		'id_tipo_prof',
		'num_coleg',
		'id_especialidad_prof',
		'num_rne',
		'observacion_fups',
		'ruc_ipress',
		'id_cups',
		'comentario',
		'respuesta',
		'observaciones',
		'id_estado',
		'id_saludpol',
		'comentario_saludpol',
		'id_prestacion',
		'fech_alta_atenc',
		'actualizacon_trama',
		'lab_consulta_ws',
		'ima_consulta_ws',
		'id_cpt',
		'flag_estancia',
		'updated_as',
		'hora_atencion_prest',
		'id_lugar_atencion',
		'id_tipo_ingreso',
		'id_beneficio',
		'id_sexo',
		'asegurado_fecha_nacimiento',
		'hora_ingreso',
		'fecha_corte_admin',
		'ipress_corte_admin',
		'lote_corte_admin',
		'num_correlativo_corte_admin',
		'hora_alta',
		'codigo_autorizacion',
		'id_punto_digitacion',
		'id_hospitalizacion',
		'estado_validacion',
	];
  
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

	public function obtener_trama_prestacion($id_trama)
	{
		$result = DB::table('trama_prestacion')->where('id', $id_trama)->first();
		return $result;
	}
}