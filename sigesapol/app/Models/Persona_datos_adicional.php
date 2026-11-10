<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Persona_datos_adicional extends Model
{
  protected $table = 'persona_datos_adicionales';
   
  public function persona_datos_Adicionales($id_asegurado)
  {   
	 $cad = "select ase.id, ase.id_tipo_doc_ident, ase.tipo_doc_ident, ase.nro_doc_ident, ase.nombre, ase.paterno, ase.materno, ase.sexo, ase.fecha_nac,
			ase.telefono, ase.domicilio, ase.correo, ase.id_asegurado_titular, ase.id_tipo_beneficiario,
			adi.cip, adi.id_situacion, adi.id_unidad, adi.id_grado, adi.id_categoria, adi.fecha_inicio_servicio,
			tg.descripcion_corta grado, s.descripcion situacion, tc.descripcion categoria, up.descripcion_larga 
			from asegurados ase
			left join persona_datos_adicionales adi on adi.id_asegurado= ase.id
			inner join titular_grados tg on adi.id_grado = tg.id
			inner join situaciones s on adi.id_situacion = s.id
			inner join titular_categorias tc on adi.id_categoria = tc.id
			inner join unidadpagos up on adi.id_unidad = up.id 
			where ase.id_estado_reg='1' and adi.estado='1'  ";
		if(trim($id_asegurado)!=""){
			$cad .= " and  adi.id_asegurado='$id_asegurado' ";
		}		
    $data = DB::select($cad);  	
	if(count($data)==0){
		return $data;
	}else{
		return $data[0];
	}
  }

  public function obtener_existencia_historia($id_asegurado){ 
	$cad = "select * from asegurado_historias where id_asegurado = '$id_asegurado'";		
	$data = DB::select($cad);
	if(count($data)==0){
		return $data;
	}else{
		return $data[0];
	}
  }
  public function busqueda_by_cip($cip)
  {   
	 $cad = "select ase.id, ase.id_tipo_doc_ident, ase.tipo_doc_ident, ase.nro_doc_ident, ase.nombre, ase.paterno, ase.materno, ase.sexo, ase.fecha_nac,
			ase.telefono, ase.domicilio, ase.correo, ase.id_asegurado_titular, ase.id_tipo_beneficiario,
			adi.cip, adi.id_situacion, adi.id_unidad, adi.id_grado, adi.id_categoria, adi.fecha_inicio_servicio
			from asegurados ase
			inner join persona_datos_adicionales adi on adi.id_asegurado= ase.id						
			where ase.id_estado_reg='1' and adi.estado='1' and adi.cip = '$cip'";		
    $data = DB::select($cad);
	if(count($data)==0){
		return $data;
	}else{
		return $data[0];
	}
  }

	public function consulta_titular($p) {
		return $this->readFunctionPostgres('sp_consulta_titular', $p);
	}

	public function consulta_carga_familiar($p) {
		return $this->readFunctionPostgres('sp_consulta_carga_familiar', $p);
	}

	public function readFunctionPostgres($function, $parameters = null) {
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