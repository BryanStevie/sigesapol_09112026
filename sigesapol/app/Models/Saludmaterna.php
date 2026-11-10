<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class SaludMaterna extends Model
{
	
 	public function listar_salud_materna($p){
		return $this->readFunctionPostgres('sp_salud_materna_g_p',$p);
	}
  
	public function listar_salud_materna_puerpera($p){
		return $this->readFunctionPostgres('sp_salud_materna_p',$p);
	}

	public function listar_gestantes_individual_vista($p){
		return $this->readFunctionPostgres('sp_gestantes_individual_vista',$p);
	}

	public function readFunctionPostgres($function, $parameters = null){
	
		$_parameters = '';
		if (count($parameters) > 0) {
			$_parameters = implode("','", $parameters);
			$_parameters = "'" . $_parameters . "',";
		}
	  $data = DB::select("BEGIN;");
	  $cad = "select " . $function . "(" . $_parameters . "'ref_cursor3');";
	  $data = DB::select($cad);
	  $cad = "FETCH ALL IN ref_cursor3;";
	  $data = DB::select($cad);
	  $closeCursor = DB::select("CLOSE ref_cursor3;");
		return $data;
	}

	public function salud_materna_gestante_reporte($p){
		return $this->readFunctionPostgres('salud_materna_gestante_reporte',$p);
	}

	public function salud_materna_puerperios_reporte($p){
		return $this->readFunctionPostgres('salud_materna_puerperios_reporte',$p);
	}

	public function GetAtencionesBYSaludMaternaByIdAsegurado($id_asegurado)
	{
	  $cad = "select 
			  t1.id,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t2.tipo_doc_ident,t7.nombre tipo_atencion,
			  t2.nro_doc_ident,to_char(t1.created_at , 'YYYY-MM-DD') dia,to_char(t1.created_at , 'HH:MI AM') hora,t3.nombre establecimiento,t6.nombre servicio,t4.descripcion consultorio,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,t1.id_estado_reg
			  FROM prestaciones t1
			  inner join asegurados t2 on t1.id_asegurado = t2.id 
			  inner join establecimientos t3 on t1.id_establecimiento=t3.id
			  inner join sub_consultorios t4 on t1.id_sub_consultorio=t4.id
			  inner join consultorios t6 on t4.id_consultorio=t6.id
			  inner join medicos t5 on t1.id_medico=t5.id
			  inner join tipo_atenciones t7 on t1.id_tipo_atencion=t7.id
			  WHERE t2.id = ".$id_asegurado."
			  And t1.id_estado_reg = 1 and t6.id in('76','77','131')
			  ORDER BY t1.created_at desc";
	  $data = DB::select($cad);
	  return $data;
	}
}
