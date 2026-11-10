<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Referencia_procedimiento extends Model
{

    public static function registrarReferencia($id_prestacion, $id_asegurado, $id_tipo_atencion, $fecha_atencion, $id_medico, $id_establecimiento_referencia, $id_uci,$id_uci_cv,$id_ambulancia,$id_ambulancia_ref,$id_tipo_ambulancia,$id_pasaje,$id_acompanante,$id_situacion,$observacion_referencia,$id_establecimiento)
    {
        $cad = "Select sp_insert_referencia(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_prestacion, $id_asegurado, $id_tipo_atencion, $id_medico, $id_establecimiento_referencia, $id_uci,$id_uci_cv,$id_ambulancia,$id_ambulancia_ref,$id_tipo_ambulancia,$id_pasaje,$id_acompanante,$id_situacion,$observacion_referencia,$id_establecimiento));
        return $data[0]->sp_insert_referencia;
    }

    public static function registrarefcref($id_prestacion, $id_asegurado, $id_tipo_atencion, $id_establecimiento, $id_sub_consultorio,$observaciones, $fecha_atencion, $id_medico,$estado)
    {
        $cad = "Select sp_insert_registrarefcref(?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_prestacion, $id_asegurado, $id_tipo_atencion, $id_establecimiento, $id_sub_consultorio,$observaciones,$fecha_atencion,$id_medico,$estado));
        return $data[0]->sp_insert_registrarefcref;
    }

    public function buscar_asegurado_pasiente($p,$ipress)
  	{
    	$cad = "
      select a.id,a.nro_doc_ident,a.tipo_doc_ident,a.cip,a.nombre,a.paterno,a.materno,a.sexo,a.fecha_nac,ah.nro_historia,a.tipo_sangre,a.telefono,a.correo,
      ah.id_establecimiento,d.descripcion as distrito ,p.descripcion as provincia ,d2.descripcion as departamento,a.grado,a.id_tipo_beneficiario ,tb.descripcion as parentesco 
      from asegurados a
      inner join asegurado_historias ah on ah.id_asegurado = a.id 
       left join distritos d on a.id_distrito = d.id_distrito
       left join provincias p on p.id_provincia = a.id_provincia 
       left join departamentos d2 on d2.id_departamento = a.id_departamento 
       left join tipo_beneficiarios tb on tb.id = a.id_tipo_beneficiario 
      where a.id = '$p' and ah.id_establecimiento = '$ipress' and a.id_estado_reg = '1' and ah.id_estado_reg = '1'
      ";
      $data = DB::select($cad);
      $data = $data[0];
    	return $data;
    }

    public function listar_referencia_ajax($p)
  	{
    	return $this->readFunctionPostgres('sp_listar_referencias', $p);
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

  public function GetReferenciaByIdPrestacion($id)
    {
      $cad = "select 
      t8.id as id_referencia,
      t8.id_prestacion,
      t8.id_asegurado,
      t8.id_tipo_atencion,
      t8.fecha_atencion,
      t8.id_medico_referencia,
      t8.id_establecimiento,
      t8.observacion_referencia,
      t8.fecha_firma_referencia,
      t8.ruta_referencia,
      t8.origen_referencia,
      t8.clasificacion_preliminar,
      t8.ups,
      t8.id_servicio,
      t8.recomendaciones,
      t8.condicion,
      t8.id_medico_contrareferencia,
      t8.fecha_firma_contrareferencia,
      t8.ruta_contrareferencia,
      t8.id_estado_reg,
      t8.flag,
      t8.id_evaluacion,
              t1.id,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t2.tipo_doc_ident,t7.nombre tipo_atencion,
              t2.nro_doc_ident,to_char(t1.created_at , 'YYYY-MM-DD') dia,to_char(t1.created_at , 'HH:MI AM') hora,t3.nombre establecimiento,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,t1.id_estado_reg
              FROM prestaciones t1
              left join referencias t8 on t8.id_prestacion = t1.id 
              inner join asegurados t2 on t8.id_asegurado = t2.id 
              inner join establecimientos t3 on t8.id_establecimiento=t3.id
              inner join medicos t5 on t8.id_medico_referencia=t5.id
              inner join tipo_atenciones t7 on t1.id_tipo_atencion=t7.id
              WHERE t8.id_prestacion=".$id."
              And t1.id_estado_reg = 1
              ORDER BY t8.created_at desc";
      $data = DB::select($cad);

       return $data[0];
    }

  public function GetReferenciaById($id_prestacion, $id_referencia)
    {
      $cad = "select 
              t8.*, t1.id,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t2.tipo_doc_ident,t7.nombre tipo_atencion,
              t2.nro_doc_ident,to_char(t1.created_at , 'YYYY-MM-DD') dia,to_char(t1.created_at , 'HH:MI AM') hora,t3.nombre establecimiento,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,t1.id_estado_reg
              FROM prestaciones t1
              inner join referencias t8 on t8.id_prestacion = t1.id 
              inner join asegurados t2 on t8.id_asegurado = t2.id 
              inner join establecimientos t3 on t8.id_establecimiento=t3.id
              inner join medicos t5 on t8.id_medico_referencia=t5.id
              inner join tipo_atenciones t7 on t1.id_tipo_atencion=t7.id
              WHERE t8.id_prestacion=".$id_prestacion." 
               AND t8.id=".$id_referencia."
              And t1.id_estado_reg = 1
              ORDER BY t8.created_at desc";
      $data = DB::select($cad);
      return $data[0];
    }

    public function GetReferenciaByIdPrest($id_prestacion)
    {
      $datos = Referencia::where('id_prestacion', '=', $id_prestacion)->first();
      return $datos;
    }

    public function GetBuscaReferenciaById($id_referencia) {

        $cad = "select * from referencias
                where id = ".$id_referencia; 

        $data = DB::select($cad);
        return $data[0];
    }

    public function ActualizaIdEvolucion($id, $id_evolucion) {

      $datos = Referencia::where('id', $id)->first();
      $datos->id_evolucion = $id_evolucion;
      $datos->save();
      
      return true;
  
  }

    public function busca_ipress_x_servicio($id_servicio) {

      $cad = " 
      select distinct e.id,e.codigo,e.nombre as nombre_ipress,e.nivel as nivel_ipres,c.nombre as nombre_servicio,c.id as id_servicio 
      from establecimientos e 
      inner join sub_consultorios sc on e.id = sc.id_establecimiento 
      inner join consultorios c on c.id = sc.id_consultorio 
      where e.nivel != 0 and e.codigo is not null and c.estado is not null and c.id not in  ('204','255','256','118','242','254','253') and
      c.id=".$id_servicio." order by e.nivel asc
      ";
      $data = DB::select($cad);

return $data;
    
    }

    public function GetReferenciaByIdPrestacionEvaluacion($id_prestacion, $id_evaluacion)
    {
      $cad = "select 
              t8.*, t1.id,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t2.tipo_doc_ident,t7.nombre tipo_atencion,
              t2.nro_doc_ident,to_char(t1.created_at , 'YYYY-MM-DD') dia,to_char(t1.created_at , 'HH:MI AM') hora,t3.nombre establecimiento,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,t1.id_estado_reg
              FROM prestaciones t1
              left join referencias t8 on t8.id_prestacion = t1.id 
              inner join asegurados t2 on t8.id_asegurado = t2.id 
              inner join establecimientos t3 on t8.id_establecimiento=t3.id
              inner join medicos t5 on t8.id_medico_referencia=t5.id
              inner join tipo_atenciones t7 on t1.id_tipo_atencion=t7.id
              WHERE t8.id_prestacion=".$id_prestacion." and t8.id_evaluacion=".$id_evaluacion."
              And t1.id_estado_reg = 1
              ORDER BY t8.created_at desc";
      $data = DB::select($cad);

       return $data[0];
    }

    public function GetReferenciaByIdEvaluacion($id_evaluacion)
    {
      $datos = Referencia::where('id_evaluacion', '=', $id_evaluacion)->first();
      return $datos;
    }

    public function GetMostrarReferenciaByIdPrestacion($id)
    {
      $cad = "select 
      t8.id as id_referencia,
      t8.id_prestacion,
      t8.id_asegurado,
      t8.id_tipo_atencion,
      t8.fecha_atencion,
      t8.id_medico_referencia,
      t8.id_establecimiento,
      t8.id_uci,
      t8.id_uci_cv,
      t8.id_ambulancia,
      t8.id_ambulancia_ref,
      t8.id_tipo_ambulancia,
      t8.id_pasaje,
      t8.id_acompanante,
      t8.id_situacion,
      t8.observacion_referencia,
      t8.fecha_firma_referencia,
      t8.ruta_referencia,
      t8.origen_referencia,
      t8.clasificacion_preliminar,
      t8.ups,
      t8.id_servicio,
      t8.recomendaciones,
      t8.condicion,
      t8.id_medico_contrareferencia,
      t8.fecha_firma_contrareferencia,
      t8.ruta_contrareferencia,
      t8.id_estado_reg,
      t8.flag,
      t8.id_evaluacion,
              t1.id,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t2.tipo_doc_ident,t7.nombre tipo_atencion,
              t2.nro_doc_ident,to_char(t1.created_at , 'YYYY-MM-DD') dia,to_char(t1.created_at , 'HH:MI AM') hora,t3.nombre establecimiento,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,t1.id_estado_reg
              FROM prestaciones t1
              left join referencias t8 on t8.id_prestacion = t1.id 
              inner join asegurados t2 on t8.id_asegurado = t2.id 
              inner join establecimientos t3 on t8.id_establecimiento=t3.id
              inner join medicos t5 on t8.id_medico_referencia=t5.id
              inner join tipo_atenciones t7 on t1.id_tipo_atencion=t7.id
              WHERE t8.id_prestacion=".$id."
              And t1.id_estado_reg = 1
              ORDER BY t8.created_at desc";
      $data = DB::select($cad);

       return $data[0];
    }
}
