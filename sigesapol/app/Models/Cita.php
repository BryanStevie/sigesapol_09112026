<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class Cita extends Model
{
  protected $table = 'citas';
  public $timestamps = false;
  protected $primaryKey = 'id'; 
  protected $fillable = [
      'id_establecimiento',
      'id_asegurado',
      'id_titular',
      'parentesco',
      'grado',
      'correo',
      'id_sub_consultorio',
      'id_medico',
      'fecha_hora',
      'id_parent_cita',
      'id_estado_cita',
      'id_estado_reg',
      'id_servicio',
      'created_at',
      'created_us',
      'updated_us',
      'deleted_us',
      'observacion',
      'estado_presencia',
      'fecha_presencia',
      'id_estado_tv',
      'fecha_llamada',
      'orden_llamada',
      'estado_llamada',
      'cantidad_llamada',
      'id_tipo_cita',
      'comentario',
      'estado_triaje',
      'indicadorfinal',
      'orden_atencion',
      'referido',
      'estado_dia_paciente',
      'adicional',
      'origen_doc',
      'numero_doc',
      'establecimiento_referencia',
      'obs_registro_cita',
      'servicio_referencia',
      'tipo_doc',
      'ano',
      'codigo_renipres',
      'codigo_siteds',
      'updated_at_cod_siteds',
      'updated_us_cod_siteds',
      'id_referencia',
      'flag_cita_laboratorio',
      'id_cita_lab',
      'estado_asistencia_paciente',
      'obs_asistencia_paciente',
      'id_cita_orden_procedencia',
      'id_cita_result_procedencia',
      'flag_cita_laboratorio_ce',
      'id_cita_laboratorio_ce',
      'id_pp',
      'flag_cita_radiologia',
      'flag_cita_radiologia_ce',
      'id_cita_radiologia_ce',
      'id_cita_rad',
      'motivo_anulacion_atencion',
      'id_user_anulacion_atencion',
      'flag_pp'  
  ];

	function getNroCita($id_establecimiento){
		$cad = "select extract(year from now())||LPAD(((count(*)+1)::text),5,'0')nro_cita 
from citas T1
inner join farmacias T2 on T1.id_farmacia = T2.id 
where T2.id_establecimiento = ".$id_establecimiento." and T1.estado = 1 
		/*and fecha_cita = now()::date*/ ";
		$data = DB::select($cad);
        return $data[0]->nro_cita;
	}
    //
	/*
   	public function GetAdmisionByNroAdmision($nro_admision,$id_establecimiento) {
        
        $cad = "SELECT T1.*,T2.nombre consultorio,(case when turno='M' then 'MA&Ntilde;ANA' when turno='T' then 'TARDE' when turno='N' then 'NOCHE' end)turnos
,T3.nombre,T3.paterno,T3.materno,T3.dni,T3.colegiatura
,coalesce((SELECT count(1) FROM receta_vales WHERE id_admision = T1.id AND estado=1),0)actual_receta
FROM admisiones T1
inner join consultorios T2 on T1.id_consultorio = T2.id
inner join medicos T3 on T1.id_medico = T3.id
inner join farmacias T4 on T1.id_farmacia = T4.id 
where T1.nro_admision = '".$nro_admision."'";
		
		$cad .= " AND T4.id_establecimiento=".$id_establecimiento;
		
        $data = DB::select($cad);
        return $data[0];
    }
    */
	
	public function GetCitaByIdFarmaciaAndDni($id_establecimiento,$dni) {
        
         $cad = "SELECT T1.*
/*,coalesce(T1.cantidad_receta,1)cantidad_receta*/
/*,coalesce((SELECT count(1) FROM receta_vales WHERE id_admision = T1.id AND estado=1),0)actual_receta*/
,T2.nombre consultorio,(case when turno='M' then 'MA&Ntilde;ANA' when turno='T' then 'TARDE' when turno='N' then 'NOCHE' when turno='MD' then 'MADRUGADA' end)turnos
,T3.nombre,T3.paterno,T3.materno,T3.dni,T3.colegiatura
FROM citas T1
inner join consultorios T2 on T1.id_consultorio = T2.id
inner join medicos T3 on T1.id_medico = T3.id 
inner join farmacias T4 on T1.id_farmacia = T4.id 
where T1.dni_beneficiario = '".$dni."'
and T4.id_establecimiento = ".$id_establecimiento."
and T1.id not in (select id_cita from admisiones where id_cita is not null)";
		
		//$cad .= " AND coalesce(T1.cantidad_receta,1) > coalesce((SELECT count(1) FROM receta_vales WHERE id_admision = T1.id AND estado=1),0)";
		$cad .= " ORDER BY T1.id desc ";
		//echo $cad;
        $data = DB::select($cad);
        return $data;
    }
	
	
	public function ExisteCita($dni_beneficiario,$fecha_cita,$tipo_cita,$id_consultorio,$turno,$id_medico) {
        $data = Cita::where('dni_beneficiario', '=', $dni_beneficiario)->where('fecha_cita', '=', $fecha_cita)->where('tipo_cita', '=', $tipo_cita)->where('id_consultorio', '=', $id_consultorio)->where('turno', '=', $turno)->where('id_medico', '=', $id_medico)->first();
        return isset($data) ? true : false;
    }
	
/*	public function registrarCita($id_cita,$id_ipress,$id_servicio,$id_medico,$id_user,$fecha_hora,$tipo_doc_ident,$nro_doc_ident,$nombre,$paterno,$materno,$parentesco,$nro_historia,$grado,$fecha_nac,$sexo,$cip,$tipodoc_titular,$nrodoctitular,$nombre_titular,$paterno_titular,$materno_titular,$id_parent_cita,$id_estado_cita,$id_estado_reg,$correo="",$domicilio="",$telefono="",$telefono2="",$tipo_cita, $opcion, $referido) {		
		//print_r(array($id_cita,$id_ipress,$id_servicio,$id_medico,$id_user,$fecha_hora,$tipo_doc_ident,$nro_doc_ident,$nombre,$paterno,$materno,$parentesco,$nro_historia,$grado,$id_titular,$id_parent_cita,$id_estado_cita,$id_estado_reg));exit();
		$cad = "Select sp_insert_cita(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$data = DB::select($cad, array($id_cita,$id_ipress,$id_servicio,$id_medico,$id_user,$fecha_hora,$tipo_doc_ident,$nro_doc_ident,$nombre,$paterno,$materno,$parentesco,$nro_historia,$grado,$fecha_nac,$sexo,$cip,$tipodoc_titular,$nrodoctitular,$nombre_titular,$paterno_titular,$materno_titular,$id_parent_cita,$id_estado_cita,$id_estado_reg,$correo,$domicilio,$telefono,$telefono2,$tipo_cita,$opcion, $referido));
        return $data[0]->sp_insert_cita;
    }
*/
	/* public function registrarCita($id_cita,$id_ipress,$id_servicio,$id_medico,$id_user,$fecha_hora,$tipo_doc_ident,$nro_doc_ident,$nombre,$paterno,$materno,$parentesco,$nro_historia,$grado,$fecha_nac,$sexo,$cip,$tipodoc_titular,$nrodoctitular,$nombre_titular,$paterno_titular,$materno_titular,$id_parent_cita,$id_estado_cita,$id_estado_reg,$correo="",$domicilio="",$telefono="",$telefono2="",$tipo_cita, $opcion, $referido, $adicional, $nombre_establecimiento, $servicio_referencia, $tipo_doc, $numero_doc, $ano, $origen_doc, $codigo_renipres, $obs_registro_cita) {		
		//print_r(array($id_cita,$id_ipress,$id_servicio,$id_medico,$id_user,$fecha_hora,$tipo_doc_ident,$nro_doc_ident,$nombre,$paterno,$materno,$parentesco,$nro_historia,$grado,$id_titular,$id_parent_cita,$id_estado_cita,$id_estado_reg));exit();
		$cad = "Select sp_insert_cita(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"; // 41 parametros
		$data = DB::select($cad, array($id_cita,$id_ipress,$id_servicio,$id_medico,$id_user,$fecha_hora,$tipo_doc_ident,$nro_doc_ident,$nombre,$paterno,$materno,$parentesco,$nro_historia,$grado,$fecha_nac,$sexo,$cip,$tipodoc_titular,$nrodoctitular,$nombre_titular,$paterno_titular,$materno_titular,$id_parent_cita,$id_estado_cita,$id_estado_reg,$correo,$domicilio,$telefono,$telefono2,$tipo_cita,$opcion, $referido, $adicional, $nombre_establecimiento, $servicio_referencia, $tipo_doc, $numero_doc, $ano, $origen_doc, $codigo_renipres, $obs_registro_cita));
        return $data[0]->sp_insert_cita;
    } */
    public function registrarCita($id_cita,$id_ipress,$id_servicio,$id_medico,$id_user,$fecha_hora,$tipo_doc_ident,$nro_doc_ident,$nombre,$paterno,$materno,$parentesco,$nro_historia,$grado,$fecha_nac,$sexo,$cip,$tipodoc_titular,$nrodoctitular,$nombre_titular,$paterno_titular,$materno_titular,$id_parent_cita,$id_estado_cita,$id_estado_reg,$correo="",$domicilio="",$telefono="",$telefono2="",$tipo_cita, $opcion, $referido, $adicional, $nombre_establecimiento, $servicio_referencia, $tipo_doc, $numero_doc, $ano, $origen_doc, $codigo_renipres, $obs_registro_cita) {		
      
      $data = null;
      try {
        DB::transaction(function () use (&$data,$id_cita,$id_ipress,$id_servicio,$id_medico,
                                        $id_user,$fecha_hora,$tipo_doc_ident,$nro_doc_ident,
                                        $nombre,$paterno,$materno,$parentesco,$nro_historia,
                                        $grado,$fecha_nac,$sexo,$cip,$tipodoc_titular,$nrodoctitular,
                                        $nombre_titular,$paterno_titular,$materno_titular,$id_parent_cita,
                                        $id_estado_cita,$id_estado_reg,$correo,$domicilio,$telefono,$telefono2,
                                        $tipo_cita, $opcion, $referido, $adicional, $nombre_establecimiento, 
                                        $servicio_referencia, $tipo_doc, $numero_doc, $ano, $origen_doc, $codigo_renipres, $obs_registro_cita) {
        $data = DB::select('Select sp_insert_cita(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', array(
                  $id_cita,$id_ipress,$id_servicio,$id_medico,
                  $id_user,$fecha_hora,$tipo_doc_ident,$nro_doc_ident,
                  $nombre,$paterno,$materno,$parentesco,$nro_historia,
                  $grado,$fecha_nac,$sexo,$cip,$tipodoc_titular,$nrodoctitular,
                  $nombre_titular,$paterno_titular,$materno_titular,$id_parent_cita,
                  $id_estado_cita,$id_estado_reg,$correo,$domicilio,$telefono,$telefono2,
                  $tipo_cita, $opcion, $referido, $adicional, $nombre_establecimiento,
                  $servicio_referencia, $tipo_doc, $numero_doc, $ano, $origen_doc, $codigo_renipres, $obs_registro_cita
                )); // 41 params
        });
      } catch (\Exception $e) {
          return response()->json(['status' => 'error', 'message' => 'Error al registrar la cita: ' . $e->getMessage()]);
      }
      return $data[0]->sp_insert_cita;
    }
    public function registrarCitaTriaje($id_cita,$id_ipress,$id_servicio,$id_medico,$id_user,$fecha_hora,$tipo_doc_ident,$nro_doc_ident,$nombre,
    $paterno,$materno,$parentesco,$nro_historia,$grado,$fecha_nac,$sexo,$cip,$tipodoc_titular,$nrodoctitular,$nombre_titular,$paterno_titular,
    $materno_titular,$id_parent_cita,$id_estado_cita,$id_estado_reg,$correo="",$domicilio="",$telefono="",$telefono2="",$tipo_cita, $opcion) {		
      //print_r(array($id_cita,$id_ipress,$id_servicio,$id_medico,$id_user,$fecha_hora,$tipo_doc_ident,$nro_doc_ident,$nombre,$paterno,$materno,$parentesco,$nro_historia,$grado,$id_titular,$id_parent_cita,$id_estado_cita,$id_estado_reg));exit();
      $cad = "Select sp_insert_cita_car(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
      $data = DB::select($cad, array($id_cita,$id_ipress,$id_servicio,$id_medico,$id_user,$fecha_hora,$tipo_doc_ident,$nro_doc_ident,$nombre,$paterno,
              $materno,$parentesco,$nro_historia,$grado,$fecha_nac,$sexo,$cip,$tipodoc_titular,$nrodoctitular,$nombre_titular,
              $paterno_titular,$materno_titular,$id_parent_cita,$id_estado_cita,$id_estado_reg,$correo,$domicilio,$telefono,$telefono2,$tipo_cita,
              $opcion)); 
      return $data[0]->sp_insert_cita_car;
    }


	public function GetCitasById($id) { 
		
		if($id > 0){
        $cad = "select t1.id_asegurado,t1.id_referencia,t2.cip asegurado_cip,t11.descripcion_corta,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,nro_historia,t2.sexo,t1.parentesco,t1.grado,t2.fecha_nac,t2.tipo_doc_ident,t2.grado, t2.telefono, t2.telefono2, t2.domicilio, t2.situacion_paciente,
t2.nro_doc_ident,to_char(t1.fecha_hora, 'YYYY-MM-DD') dia,to_char(t1.fecha_hora, 'HH:MI AM') hora, t1.fecha_hora, t1.id_establecimiento,t3.nombre establecimiento,t4.id_consultorio id_servicio,t1.id_sub_consultorio,t10.nombre servicio,t4.descripcion consultorio,t1.id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno
,dni,colegiatura,rne,/*t6.nombre servicio,*/t7.nombre especializacion,t8.nombre profesion,t1.correo, t1.id_tipo_cita, t1.cantidad_llamada, t1.id_estado_cita,t1.codigo_siteds, 
t4.id_consultorio as id_consultorio_servicio, t2.situacion_paciente, t2.discapacidad, t2.discapacidad_carnet , t2.nro_carnet_conadis , t2.color_carnet_conadis,
EXTRACT(YEAR FROM AGE( NOW()::DATE, TO_CHAR(TO_DATE(t2.fecha_nac, 'DD/MM/YYYY'), 'YYYY-MM-DD')::DATE)) asegurado_edad, t3.codigo as codigo_ipress, TO_CHAR(t1.updated_at_cod_siteds, 'DD/MM/YYYY') as fecha_actualizacion_siteds, t2.id_tipo_doc_ident as id_tipo_doc_ident
,t1.flag_pp, t1.flag_cita_laboratorio, t1.id_cita_lab as id_cita_reevaluacion
FROM citas t1
inner join asegurados t2 on t1.id_asegurado = t2.id 
inner join asegurado_historias t9 on t1.id_asegurado = t9.id_asegurado And t1.id_establecimiento = t9.id_establecimiento 
inner join establecimientos t3 on t1.id_establecimiento=t3.id
inner join sub_consultorios t4 on t1.id_sub_consultorio=t4.id
inner join consultorios t10 on t4.id_consultorio=t10.id
left join medicos t5 on t1.id_medico=t5.id
left join especializaciones t7 on t5.id_especializacion=t7.id
left join profesiones t8 on t5.id_profesion=t8.id
left join tipo_sangres t11 on  t1.id_asegurado = t11.id
Where t1.id=".$id;
		//echo $cad;
        $data = DB::select($cad);
        return $data[0];
		}
    }
	
	public function GetCitasConsultorioById($id) {
		
        if($id > 0){
          $cad = "select t1.id as id_cita,t1.id_referencia,t1.id_tipo_cita, t1.id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,
                  nro_historia,(case when t2.sexo='M' then 'MASCULINO' when 'F' then 'FEMENINO' end)sexo,t1.parentesco,t1.grado,fecha_nac,tipo_doc_ident, t2.nro_doc_ident,
                  to_char(t1.fecha_hora, 'YYYY-MM-DD') dia,to_char(t1.fecha_hora, 'HH:MI AM') hora,t1.id_establecimiento,t3.nombre establecimiento,t1.id_sub_consultorio,t4.id as id_consultorio,
                  t4.nombre servicio,id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno, t5.dni,t5.colegiatura,t5.rne,'' especializacion,
                  '' profesion,t1.correo,t10.descripcion consultorio,t2.telefono,t1.correo,r2.codigo_rad,r2.descripcion_corta
                  ,t1.flag_pp, us.paterno as user_lab_paterno, us.materno user_lab_materno, us.name user_lab_nombre
                  FROM citas t1
                  inner join asegurados t2 on t1.id_asegurado = t2.id 
                  inner join asegurado_historias t9 on t1.id_asegurado = t9.id_asegurado And t1.id_establecimiento = t9.id_establecimiento 
                  inner join establecimientos t3 on t1.id_establecimiento=t3.id
                  inner join sub_consultorios t10 on t1.id_sub_consultorio=t10.id
                  inner join consultorios t4 on t10.id_consultorio=t4.id
                  left join medicos t5 on t1.id_medico=t5.id
                  left join prestacion_procedimientos pp ON t1.id = pp.id_citas_multiples_rad 
                  left join radnivelatenciones r2 ON pp.id_rua = r2.id 
                  inner join users us on t1.created_us = us.id
                  --inner join consultorios t6 on t1.id_servicio=t6.id
                  --left join especializaciones t7 on t5.id_especializacion=t7.id
                  --left join profesiones t8 on t5.id_profesion=t8.id
                  Where t1.id=".$id;
          //echo $cad;
          $data = DB::select($cad);
          return $data[0];
      }
    }
	
	
	public function GetCitasByIdCita($id){
        $datos=DB::table('citas')
				->where('citas.id','=',$id)
                ->select('citas.*')
                ->first();
        return $datos;
    }
	
	public function AllCitaConfirmaPacienteFecha($fecha, $dni, $paciente, $medico, $estado) {
        $fechaComoEntero = strtotime($fecha);
        $anio = date("Y", $fechaComoEntero);
        $mes = date("m", $fechaComoEntero);
        $dia = date("d", $fechaComoEntero);
		/*
        $cad = "SELECT C.*, A.nombre, A.paterno, A.materno, A.nro_doc_ident, A.tipo_doc_ident, to_char(C.fecha_hora, 'HH:MI AM') horario
                FROM citas C inner join asegurados A on C.id_asegurado = A.id
                WHERE A.nro_doc_ident like '%" . $dni . "%'
                and A.paterno  || ' ' || A.materno  || ' ' || A.nombre like '%" . $paciente . "%'
                and C.id_estado_cita = '" . $estado . "'
                and C.id_medico = '" . $medico . "'
                and date_part('year', C.fecha_hora) = '" . $anio . "'
                and date_part('month', C.fecha_hora) = '" . $mes . "'
                and date_part('day', C.fecha_hora) = '" . $dia . "'
                ";
        $cad .= " ORDER BY C.fecha_hora";
		*/
		$cad = "select 
t1.id,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,tipo_doc_ident,
t2.nro_doc_ident,to_char(t1.fecha_hora, 'YYYY-MM-DD') dia,to_char(t1.fecha_hora, 'HH:MI AM') hora,t3.nombre establecimiento,t7.nombre servicio,t4.descripcion consultorio,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,
t1.id_estado_cita,t1.id_estado_reg,t6.nro_historia,t1.id_asegurado,t6.id id_asegurado_historia 
FROM citas t1
inner join asegurados t2 on t1.id_asegurado = t2.id 
left join asegurado_historias t6 on t1.id_asegurado = t6.id_asegurado And t1.id_establecimiento = t6.id_establecimiento 
inner join establecimientos t3 on t1.id_establecimiento=t3.id
inner join sub_consultorios t4 on t1.id_sub_consultorio=t4.id
inner join consultorios t7 on t4.id_consultorio=t7.id
left join medicos t5 on t1.id_medico=t5.id
Where 1=1 ";
		if($estado!=5)$cad .= " And t1.id_estado_reg=1 ";
		else $cad .= " And t1.id_estado_reg=0 ";
		if($estado > 0)$cad .= "and t1.id_estado_cita = '" . $estado . "'";
		if($medico > 0)$cad .= "and t1.id_medico = '" . $medico . "'";//validar deacuerdo al rol medico o historia
		if($dni != "")$cad .= "and t2.nro_doc_ident = '" . $dni . "'";
		if($paciente != "")$cad .= "and t2.paterno  || ' ' || t2.materno  || ' ' || t2.nombre like '%" . $paciente . "%'";
		if($fecha != "")$cad .= "and to_char(t1.fecha_hora, 'DD-MM-YYYY') = '" . $fecha . "'";
		$cad .= "ORDER BY t1.fecha_hora";
		//echo $cad;
        $data = DB::select($cad);
        return $data;
    }

  public function GetListaCitasReporte($p){
    return $this->readFunctionPostgres('sp_cita_reporte',$p);
  }

	public function listar_cita_ajax($p){
		return $this->readFunctionPostgres('sp_cita_paginado',$p);
	}
  public function listar_gestantes($p){
		return $this->readFunctionPostgres('sp_salud_maternaListado',$p);
	}
  public function salud_maternareporte($p){
		return $this->readFunctionPostgres('sp_salud_maternareporte',$p);
	}
	public function listar_cita_ajaxTriaje($p){
		return $this->readFunctionPostgres2('sp_cita_paginados_triajes',$p);
		//return $this->readFunctionPostgres2('sp_cita_paginadosnumeros',$p);
	}
	public function listar_prestacion_ajax($p){
		return $this->readFunctionPostgres('sp_prestacion_paginado',$p);
	}

  public function listar_prestacion_ajax_nuevo($p){
		return $this->readFunctionPostgres('sp_prestacion_paginado_nuevo',$p);
	}

  public function listar_prestacion_modificado_detallado($p) {
		return $this->readFunctionPostgres('sp_listar_prestacion_modificado_detallado',$p);
	}

  public function listar_prestacion_diagnostico_modificado_detallado($p) {
		return $this->readFunctionPostgres('sp_listar_prestacion_diagnostico_modificado_detallado',$p);
	}

  public function listar_procedimiento_atenciones_detallado($p){
		return $this->readFunctionPostgres('sp_procedimiento_atenciones_detallado',$p);
	}
  public function buscar_servicio_x_establecimiento($p){
   
		$cad = "SELECT  
   distinct (sc.id_consultorio ) as id,
  	c.nombre
    from sub_consultorios sc
    inner join consultorios c on c.id = sc.id_consultorio
    where sc.id_establecimiento  = '$p'";
     $data = DB::select($cad);
    return $data;
	}
	
    public function GetCountCitaConfirmaPacienteFecha($fecha, $dni, $paciente, $medico, $estado) {
        $fechaComoEntero = strtotime($fecha);
        $anio = date("Y", $fechaComoEntero);
        $mes = date("m", $fechaComoEntero);
        $dia = date("d", $fechaComoEntero);
        $cad = "SELECT count(1) cantidad
                FROM citas t1
inner join asegurados t2 on t1.id_asegurado = t2.id 
inner join establecimientos t3 on t1.id_establecimiento=t3.id
inner join sub_consultorios t4 on t1.id_sub_consultorio=t4.id
left join medicos t5 on t1.id_medico=t5.id
Where 1=1 ";
		if($estado!=5)$cad .= " And t1.id_estado_reg=1 ";
		else $cad .= " And t1.id_estado_reg=0 ";
		if($estado > 0)$cad .= "and t1.id_estado_cita = '" . $estado . "'";
		if($medico > 0)$cad .= "and t1.id_medico = '" . $medico . "'";
		if($dni != "")$cad .= "and t2.nro_doc_ident = '" . $dni . "'";
		if($paciente != "")$cad .= "and t2.paterno  || ' ' || t2.materno  || ' ' || t2.nombre like '%" . $paciente . "%'";
		if($fecha != "")$cad .= "and to_char(t1.fecha_hora, 'DD-MM-YYYY') = '" . $fecha . "'";
        $data = DB::select($cad);
        return $data[0]->cantidad;
    }
	
	public function AllCitaConfirmaAtencionFecha($fecha, $dni, $paciente, $medico, $estado) {
        $fechaComoEntero = strtotime($fecha);
        $anio = date("Y", $fechaComoEntero);
        $mes = date("m", $fechaComoEntero);
        $dia = date("d", $fechaComoEntero);
		
        $cad = "SELECT t1.id, t1.id_asegurado,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t10.nro_historia,(case when sexo='M' then 'MASCULINO' when 'F' then 'FEMENINO' end)sexo,parentesco,t9.grado,fecha_nac,
                        tipo_doc_ident,t2.nro_doc_ident,t1.id_establecimiento,t3.nombre establecimiento,t1.id_sub_consultorio,t12.nombre servicio,t4.descripcion consultorio,t1.id_medico,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,dni,colegiatura,rne,/*t6.nombre servicio,*/t7.nombre especializacion,t8.nombre profesion, to_char(t1.created_at, 'HH:MI AM') horario,to_char(t1.fecha_atencion, 'DD/MM/YYYY') fecha_atencion,ruta_prestacion,t1.condicion, 
				--ruta_receta
				(select array_agg(rv.ruta_receta) from receta_vales rv where rv.id_prestacion=t1.id and rv.estado<>0)ruta_receta
                FROM prestaciones t1
                    inner join asegurados t2 on t1.id_asegurado = t2.id 
					inner join asegurado_historias t10 on t1.id_asegurado = t10.id_asegurado And t1.id_establecimiento = t10.id_establecimiento 
                    inner join establecimientos t3 on t1.id_establecimiento=t3.id
                    inner join sub_consultorios t4 on t1.id_sub_consultorio=t4.id
					inner join consultorios t12 on t4.id_consultorio=t12.id
                    inner join medicos t5 on t1.id_medico=t5.id
                    left join especializaciones t7 on t5.id_especializacion=t7.id
                    left join profesiones t8 on t5.id_profesion=t8.id
					left join citas t9 on t1.id_cita=t9.id And t9.id_estado_reg=1
					--left join receta_vales t11 on t1.id=t11.id_prestacion  
                WHERE 1=1 ";
				
				//if($estado > 0)$cad .= "and t1.id_estado_cita = '" . $estado . "'";
				if($medico > 0)$cad .= "and t1.id_medico = '" . $medico . "'";
				if($dni != "")$cad .= "and t2.nro_doc_ident = '" . $dni . "'";
				if($paciente != "")$cad .= "and t2.paterno  || ' ' || t2.materno  || ' ' || t2.nombre like '%" . $paciente . "%'";
				if($fecha != "")$cad .= "and to_char(t1.fecha_atencion, 'DD-MM-YYYY') = '" . $fecha . "'";
				/*
				"t2.nro_doc_ident like '%" . $dni . "%'
                and t2.paterno || ' ' || t2.materno  || ' ' || t2.nombre like '%" . $paciente . "%'
                and t1.id_estado_reg = '" . $estado . "'
                and t1.id_medico = '" . $medico . "'
                and date_part('year', t1.fecha_atencion) = '" . $anio . "'
                and date_part('month', t1.fecha_atencion) = '" . $mes . "'
                and date_part('day', t1.fecha_atencion) = '" . $dia . "'
                ";
				*/
        $cad .= " ORDER BY t1.fecha_atencion";
		//echo $cad;
		/*
		$cad = "select 
t1.id,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,tipo_doc_ident,
t2.nro_doc_ident,to_char(t1.fecha_hora, 'YYYY-MM-DD') dia,to_char(t1.fecha_hora, 'HH:MI AM') hora,t3.nombre establecimiento,t4.nombre servicio,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno
FROM citas t1
inner join asegurados t2 on t1.id_asegurado = t2.id 
inner join establecimientos t3 on t1.id_establecimiento=t3.id
inner join consultorios t4 on t1.id_servicio=t4.id
inner join medicos t5 on t1.id_medico=t5.id
Where 1=1 ";
		if($estado > 0)$cad .= "and t1.id_estado_cita = '" . $estado . "'";
		if($medico > 0)$cad .= "and t1.id_medico = '" . $medico . "'";
		if($dni != "")$cad .= "and t2.nro_doc_ident = '" . $dni . "'";
		if($paciente != "")$cad .= "and t2.paterno  || ' ' || t2.materno  || ' ' || t2.nombre like '%" . $paciente . "%'";
		if($fecha != "")$cad .= "and to_char(t1.fecha_hora, 'DD-MM-YYYY') = '" . $fecha . "'";
		$cad .= "ORDER BY t1.fecha_hora";
		*/
		
        $data = DB::select($cad);
        return $data;
    }
    public function GetCountCitaConfirmaAtencionFecha($fecha, $dni, $paciente, $medico, $estado) {
        $fechaComoEntero = strtotime($fecha);
        $anio = date("Y", $fechaComoEntero);
        $mes = date("m", $fechaComoEntero);
        $dia = date("d", $fechaComoEntero);
        $cad = "SELECT count(1) cantidad
                FROM prestaciones t1
                    inner join asegurados t2 on t1.id_asegurado = t2.id 
					inner join asegurado_historias t10 on t1.id_asegurado = t10.id_asegurado And t1.id_establecimiento = t10.id_establecimiento 
                    inner join establecimientos t3 on t1.id_establecimiento=t3.id
                    inner join sub_consultorios t4 on t1.id_sub_consultorio=t4.id
                    inner join medicos t5 on t1.id_medico=t5.id
                    --inner join consultorios t6 on t1.id_servicio=t6.id
                    left join especializaciones t7 on t5.id_especializacion=t7.id
                    left join profesiones t8 on t5.id_profesion=t8.id
					left join citas t9 on t1.id_cita=t9.id And t9.id_estado_reg=1
					--left join receta_vales t11 on t1.id=t11.id_prestacion
                WHERE 1=1 ";
				
			//if($estado > 0)$cad .= "and t1.id_estado_cita = '" . $estado . "'";
			if($medico > 0)$cad .= "and t1.id_medico = '" . $medico . "'";
			if($dni != "")$cad .= "and t2.nro_doc_ident = '" . $dni . "'";
			if($paciente != "")$cad .= "and t2.paterno  || ' ' || t2.materno  || ' ' || t2.nombre like '%" . $paciente . "%'";
			if($fecha != "")$cad .= "and to_char(t1.fecha_atencion, 'DD-MM-YYYY') = '" . $fecha . "'";
			
        $data = DB::select($cad);
        return $data[0]->cantidad;
    }
	
	public function GetCitaByAseguradoAndEstablecimiento($tipo_doc_ident,$nro_doc_ident,$id_establecimiento) {
		if($tipo_doc_ident == 1)$tipo_doc_ident="DNI";
        $cad = "select T1.* from citas T1
		inner join asegurados T2 on T1.id_asegurado = T2.id 
		where tipo_doc_ident='".$tipo_doc_ident."'
		and nro_doc_ident = '".$nro_doc_ident."'
		and T1.id_estado_reg=1 and T1.id_estado_cita!=4 and T1.id_establecimiento=".$id_establecimiento;
        $data = DB::select($cad);
        return $data;
    }
	
	public function GetServicioCitaByAseguradoAndEstablecimiento($tipo_doc_ident,$nro_doc_ident,$id_establecimiento) {
		if ($tipo_doc_ident == 1)
      $tipo_doc_ident="DNI";
        $cad = "select distinct * from (
      select T3.id,T3.nombre from citas T1
      inner join asegurados T2 on T1.id_asegurado = T2.id 
      inner join sub_consultorios T4 on T1.id_sub_consultorio = T4.id
      inner join consultorios T3 on T4.id_consultorio = T3.id
      where tipo_doc_ident='".$tipo_doc_ident."'
      and nro_doc_ident = '".$nro_doc_ident."'
      and T1.id_estado_reg=1 and T1.id_estado_cita!=4 and T1.id_establecimiento=".$id_establecimiento."
      and T3.parent=2
      union all
      select id,nombre from consultorios where estado=4
      )R order by nombre Asc";
    $data = DB::select($cad);
    return $data;
  }

  public function GetServicioEstablecimiento($id_establecimiento) {
    	$cad = "select co.id, co.nombre from consultorio_farmacias c2 
			inner join consultorios co on co.id = c2.id_consultorio 
			inner join farmacias f on c2.id_farmacia = f.id 
			inner join establecimientos e2 on f.id_establecimiento = e2.id
			where e2.id= '".$id_establecimiento."' and co.estado  = 4 
			group by co.id,co.nombre 
			order by nombre asc";
 		$data = DB::select($cad);
        return $data;
  }
	
	public function GetServicioCitaGeneralByAseguradoAndEstablecimiento($tipo_doc_ident,$nro_doc_ident,$id_establecimiento) {
		if($tipo_doc_ident == 1)$tipo_doc_ident="DNI";
        $cad = "
		select id,nombre from consultorios where parent=1
		and id not in(
		select T3.id from citas T1
		inner join asegurados T2 on T1.id_asegurado = T2.id 
		inner join sub_consultorios T4 on T1.id_sub_consultorio = T4.id
		inner join consultorios T3 on T4.id_consultorio = T3.id
		where tipo_doc_ident='".$tipo_doc_ident."'
		and nro_doc_ident = '".$nro_doc_ident."'
		and T1.id_estado_reg=1 and T1.id_estado_cita!=4 and T1.id_establecimiento=".$id_establecimiento."
		and T3.parent=1
		)";
        $data = DB::select($cad);
        return $data;
    }
	
	
    public function readFunctionPostgres($function, $parameters = null){
	
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
      $closeCursor = DB::select("CLOSE ref_cursor;");
      return $data;
   }	


   public function GetFeriados($anio, $mes = '')
   {
     $p[] = $anio;
     return $this->readFunctionPostgres('sp_get_feriados',$p);
   }

   public function ValidaProgramacionCita($id_user, $id_establecimiento, $id_servicio, $fecha)
   {
    $data = DB::select("select sp_valida_programacion_cita({$id_user},{$id_establecimiento},{$id_servicio},'{$fecha}') as validacion");

    if (is_object($data[0]) && strpos($data[0]->validacion, 'proxima cita') > 1) {
      $data[0]->validacion = '1';
    }

    return $data[0];
   }
		
   public function GetCitasMedicasDelMes($nro_documento, $year, $month) {
    $query = '
      SELECT TO_CHAR(xc.fecha_hora, \'YYYY-MM\') AS fecha_cita, TO_CHAR(MAX(xc.fecha_hora), \'YYYY-MM-DD\') AS ultima_cita, COUNT(xc.fecha_hora) AS total
      FROM citas xc
      INNER JOIN asegurados xa on xa.id = xc.id_asegurado 
      WHERE xc.id_estado_cita = 1 
        AND  xa.nro_doc_ident = \''.$nro_documento.'\' 
        AND EXTRACT(year from xc.fecha_hora) = ' . $year. ' 
        AND EXTRACT(month from xc.fecha_hora) = ' . $month . ' 
      GROUP BY TO_CHAR(xc.fecha_hora, \'YYYY-MM\')
    ';
    return DB::select($query);
  }  

  public function ValidarCitasEnRango($nro_documento, $id_establecimiento, $id_consultorio, $fecha_inferior, $fecha_superior) 
  {
    $result_consultorio = (new \App\Models\Consultorio())->find($id_consultorio);
    if (empty($result_consultorio) || $result_consultorio->flag_intervalo_cita == 1) {
      return null;
    }
    $query = '
      SELECT TO_CHAR( fecha_hora, \'YYYY-MM-DD\' ) AS fecha_hora, xa.paterno||\' \'||xa.materno||\' \'||xa.nombre AS paciente, tm.paterno||\' \'||tm.materno||\' \'||tm.nombre AS medico, tc.nombre AS servicio 
      FROM citas AS xc
      INNER JOIN asegurados AS xa ON xa.ID = xc.id_asegurado 
      INNER JOIN medicos AS tm ON tm.id = xc.id_medico 
      INNER JOIN sub_consultorios AS tsc ON tsc.id = xc.id_sub_consultorio 
      INNER JOIN consultorios AS tc ON tc.id = tsc.id_consultorio 
      WHERE	xc.id_estado_cita = 1 
        AND xc.id_establecimiento = ' . (int)$id_establecimiento . '  
        AND xa.nro_doc_ident = \'' . $nro_documento . '\' 
        AND TO_CHAR(fecha_hora, \'YYYY-MM-DD\') BETWEEN \'' . $fecha_inferior . '\' AND \'' . $fecha_superior . '\'';
    $result = DB::select($query);
    return empty($result) ? null : $result[0];
  } 

  public function ExisteCitaAllIpress($nro_documento, $fecha, $id_establecimiento, $id_consultorio) {
     $query = '
      SELECT TO_CHAR(xc.fecha_hora, \'YYYY-MM-DD\') AS fecha_cita, xc.id_establecimiento, sc.id_consultorio
      FROM citas xc 
      INNER JOIN sub_consultorios AS sc ON sc.id = xc.id_sub_consultorio
      INNER JOIN asegurados xa on xa.id = xc.id_asegurado 
      WHERE TO_CHAR(xc.fecha_hora, \'YYYY-MM-DD\') = \'' . $fecha . '\' 
        AND sc.id_consultorio = ' . $id_consultorio . ' 
        AND xa.nro_doc_ident = \'' . $nro_documento .'\' 
        AND xc.id_estado_cita = 1 
        -- AND xc.id_establecimiento = ' . $id_establecimiento . '
    ';
    //dd($query);
    $result = DB::select($query);
    return empty($result) ? false : true; 

    // $result = DB::table('citas as xc')
    //     ->join('sub_consultorios as sc', 'sc.id', '=', 'xc.id_sub_consultorio')
    //     ->join('asegurados as xa', 'xa.id', '=', 'xc.id_asegurado')
    //     ->where('xc.id_estado_cita', 1)
    //     ->where('xa.nro_doc_ident', $nro_documento)
    //     ->whereDate('xc.fecha_hora', '=', $fecha)
    //     ->where('sc.id_consultorio', $id_consultorio)
    //     ->selectRaw("TO_CHAR(xc.fecha_hora, 'YYYY-MM-DD') AS fecha_cita, xc.id_establecimiento, sc.id_consultorio")
    //     ->first();
    // return empty($result) ? false : true;
  } 

  public static function GetTipoDeAtension() {
    $aData = [
      ['id' => 1, 'nombre' => 'AMBULATORIO'],
      ['id' => 2, 'nombre' => 'EMERGENCIA'],
      ['id' => 3, 'nombre' => 'HOSPITALIZACION'],
      ['id' => 4, 'nombre' => 'CENTRO QUIRURGICO']
    ];
    return $aData;
  }
  
  public static function GetTipoDeAtensionNombre($idTipoAtencion) {
    $aTipoAtencion = Cita::GetTipoDeAtension();
    foreach ($aTipoAtencion as $item) {
      if ($item['id'] == $idTipoAtencion) {
        return $item['nombre'];
      }
    }
    return '';
  }

  public function tv_llamados($p){
		return $this->readFunctionPostgres('sp_tv_llamados', $p);
	}
  
  public function listar_cita_historia_ajax($p){
		return $this->readFunctionPostgres('sp_cita_historia',$p);
	}

  public function GetServicioEstablecimientoEspecifico($id_establecimiento, $id_servicio) {
    $cad = "select co.id, co.nombre from consultorio_farmacias c2 
    inner join consultorios co on co.id = c2.id_consultorio 
    inner join farmacias f on c2.id_farmacia = f.id 
    inner join establecimientos e2 on f.id_establecimiento = e2.id
    where e2.id= '".$id_establecimiento."' and co.estado  = 4 and co.id = '".$id_servicio."'
    group by co.id,co.nombre 
    order by nombre asc";
   $data = DB::select($cad);
      return $data;
}
public function listar_cita_ajax_orden($p){
  return $this->readFunctionPostgres3('sp_cita_paginado_orden_atencion',$p);
}

 public function readFunctionPostgres2($function, $parameters = null){

  $_parameters = '';
  if (count($parameters) > 0) {
      $_parameters = implode("','", $parameters);
      $_parameters = "'" . $_parameters . "',";
  }
$data = DB::select("BEGIN;");
$cad = "select " . $function . "(" . $_parameters . "'ref_cursor2');";
$data = DB::select($cad);
$cad = "FETCH ALL IN ref_cursor2;";
$data = DB::select($cad);
$closeCursor = DB::select("CLOSE ref_cursor2;");
  return $data;
}

public function readFunctionPostgres3($function, $parameters = null){
	
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

public function citadoOrdenDeAtencionNotNull($id_cita,$fecha_hora,$id_sub_consultorio,$id_medico){
  $cad = "select c.* 
          from citas_llamados c
          inner join asegurados t2 on c.id_asegurado = t2.id 
          left join asegurado_historias t6 on c.id_asegurado = t6.id_asegurado And c.id_establecimiento = t6.id_establecimiento And t6.id_estado_reg<>'0' 
          inner join establecimientos t3 on c.id_establecimiento=t3.id
          inner join sub_consultorios t4 on c.id_sub_consultorio=t4.id
          inner join consultorios t7 on t4.id_consultorio=t7.id
          left join medicos t5 on c.id_medico=t5.id
          left join tipo_cita tc on tc.id = c.id_tipo_cita 
          inner join users t8 on c.created_us=t8.id 
          where c.fecha_hora > '".$fecha_hora."' 
          and c.id_cita<> ".$id_cita." 
          and c.orden_atencion is not null 
          and c.id_estado_cita=1 
          And c.id_sub_consultorio = ".$id_sub_consultorio."
          And c.id_medico =".$id_medico."
          and cast(fecha_hora  as date) = cast(now() as date) limit 1";
	$data = DB::select($cad);
  return $data;
}
public function GetCitasEstado($fecha_hora, $sub_consultorio) { 
    
      $cad = "select t1.estado_dia_paciente,a.nro_doc_ident , a.nombre , a.paterno , a.materno , r2.codigo_rad,r2.descripcion_corta examen ,pp.id
              FROM citas t1
              inner join asegurados a ON t1.id_asegurado = a.id 
              left join prestacion_procedimientos pp ON t1.id = pp.id_citas_multiples_rad 
              left join radnivelatenciones r2 ON pp.id_rua = r2.id 
              Where t1.id_sub_consultorio=".$sub_consultorio;
      $cad.=" And t1.fecha_hora='".$fecha_hora."'";
  //dd($cad);
      $data = DB::select($cad);
      //dd($data);
      return $data;

      
}

public function GetNumeroPaciente2($id_medico, $fecha_hora, $sub_consultorio) { 
  $fecha_atencion_inicial=$fecha_hora.' 00:00:00.000'; 
	$fecha_atencion_fin=$fecha_hora.' 23:59:00.000'; 
		
  $cad = "select count(*) total_paciente, min(fecha_hora) fecha_hora_ini , max(fecha_hora) fecha_hora_fin 
          FROM citas t1
          Where t1.id_estado_reg =1 and t1.id_sub_consultorio=".$sub_consultorio;
  $cad.=" And t1.id_medico=".$id_medico;
  $cad.=" And t1.fecha_hora>='".$fecha_atencion_inicial."'";
  $cad.=" And t1.fecha_hora<='".$fecha_atencion_fin."'";
  $data = DB::select($cad);
  print_r($cad);
  return $data[0];  
}

public function GetNumeroPaciente($id_medico, $fecha_hora, $sub_consultorio) { 
  $fecha_atencion_inicial=$fecha_hora.' 00:00:00.000'; 
	$fecha_atencion_fin=$fecha_hora.' 23:59:00.000'; 
		
  $cad = "select count(*) adicional, min(fecha_hora) fecha_hora_ini , max(fecha_hora) fecha_hora_fin 
          FROM citas t1
          Where t1.id_estado_reg =1 and t1.adicional=1 and t1.id_sub_consultorio=".$sub_consultorio;
 // $cad.=" And t1.id_medico=".$id_medico;
  $cad.=" And t1.fecha_hora>='".$fecha_atencion_inicial."'";
  $cad.=" And t1.fecha_hora<='".$fecha_atencion_fin."'";
  $data = DB::select($cad);
  
  return $data[0];
  /*$data = DB::table('citas')
    ->selectRaw('COUNT(*) AS adicional, MIN(fecha_hora) AS fecha_hora_ini, MAX(fecha_hora) AS fecha_hora_fin')
    ->where('id_estado_reg', 1)
    ->where('adicional', 1)
    ->where('id_sub_consultorio', $sub_consultorio)
    ->where('fecha_hora', '>=', $fecha_atencion_inicial)
    ->where('fecha_hora', '<=', $fecha_atencion_fin)
    ->first();

  return $data;*/
  
}

  public function obtenerConsultorioPorIdCita($id_cita){
    $cad = "select c.*, c2.id as id_servicio_sc, c2.nombre as nom_servicio 
              from citas c 
              inner join sub_consultorios sc on c.id_sub_consultorio = sc.id 
              inner join consultorios c2 on sc.id_consultorio = c2.id
              where c.id = ".$id_cita." limit 1";
    $data = DB::select($cad);
    return $data;
  }

  public function listar_cita_ajax_sin_paginas($p){
		return $this->readFunctionPostgres2('sp_cita_sin_paginado',$p);
	}

  public function registrarCitaReferencia($id_cita,$id_ipress,$id_servicio,$id_medico,$id_user,$fecha_hora,$tipo_doc_ident,$nro_doc_ident,$nombre,$paterno,$materno,$parentesco,$nro_historia,$grado,$fecha_nac,$sexo,$cip,$tipodoc_titular,$nrodoctitular,$nombre_titular,$paterno_titular,$materno_titular,$id_parent_cita,$id_estado_cita,$id_estado_reg,$correo="",$domicilio="",$telefono="",$telefono2="",$tipo_cita, $opcion, $referido, $adicional, $nombre_establecimiento, $servicio_referencia, $tipo_doc, $numero_doc, $ano, $origen_doc, $codigo_renipres, $obs_registro_cita, $id_referencia) {		
		//print_r(array($id_cita,$id_ipress,$id_servicio,$id_medico,$id_user,$fecha_hora,$tipo_doc_ident,$nro_doc_ident,$nombre,$paterno,$materno,$parentesco,$nro_historia,$grado,$id_titular,$id_parent_cita,$id_estado_cita,$id_estado_reg));exit();
		$cad = "Select sp_insert_cita_referencia(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$data = DB::select($cad, array($id_cita,$id_ipress,$id_servicio,$id_medico,$id_user,$fecha_hora,$tipo_doc_ident,$nro_doc_ident,$nombre,$paterno,$materno,$parentesco,$nro_historia,$grado,$fecha_nac,$sexo,$cip,$tipodoc_titular,$nrodoctitular,$nombre_titular,$paterno_titular,$materno_titular,$id_parent_cita,$id_estado_cita,$id_estado_reg,$correo,$domicilio,$telefono,$telefono2,$tipo_cita,$opcion, $referido, $adicional, $nombre_establecimiento, $servicio_referencia, $tipo_doc, $numero_doc, $ano, $origen_doc, $codigo_renipres, $obs_registro_cita, $id_referencia));
        return $data[0]->sp_insert_cita_referencia;
    }

	public function validar_dato_SitedsAmbulatorio($cod_siteds)
	  {
	    $query_string = "SELECT count(id) as cantidad_siteds from citas where codigo_siteds = '{$cod_siteds}'";
	    $data = DB::SELECT($query_string);
	    if ($data != null || count($data) > 0){
	      return $data[0];
	    }else{
	      return $data;
	    }; 
	  }
  
    public function GetConsultorioByIdCitaLab($id){
      $datos=DB::table('citas')
              ->join('sub_consultorios', 'sub_consultorios.id', '=', 'citas.id_sub_consultorio')
              ->where('citas.id','=',$id)
              ->select('sub_consultorios.id_consultorio')
              ->first();
      return $datos;
    }

    public function listar_cita_programado_callcenter($p){
      return $this->readFunctionPostgres('sp_cita_programado_callcenter',$p);
    }

    public function obtenerTotalCitas($id_consultorio, $fecha)
    {
      $cad = "Select sp_obtener_total_citas(?,?)";
      $data = DB::select($cad, array($id_consultorio, $fecha));
      return $data[0]->sp_obtener_total_citas;

      /*$total_citas = DB::table('citas as c')
            ->join('sub_consultorios as sc', 'sc.id', '=', 'c.id_sub_consultorio')
            ->join('consultorios as c2', 'c2.id', '=', 'sc.id_consultorio')
            ->where('c2.id', '=', $id_consultorio)
            ->where('c.id_estado_cita', '=', 1)
            ->where('c.id_estado_reg', '=', 1)
            ->where('c.fecha_hora', '>=', $fecha)
            ->where('c.fecha_hora', '<', DB::raw("DATE_ADD('$fecha', INTERVAL 1 DAY)"))
            ->count();
      return $total_citas;*/
    }

    public function busca_Siteds_Atencion($dni_beneficiario,$fecha_atencion)
    {
      $query_string = "SELECT codigo_siteds from citas 
      inner join asegurados on asegurados.id = citas.id_asegurado
      where asegurados.nro_doc_ident = '{$dni_beneficiario}' and citas.fecha_hora >= '{$fecha_atencion} :00:00' And citas.fecha_hora <= '{$fecha_atencion} :23:59' And citas.id_estado_reg = 1 And citas.codigo_siteds != '' ";
      
      $data = DB::SELECT($query_string);

      if ($data != null || count($data) > 0){
        return $data[0];
      }else{
        return $data;
      }; 
    }


    public function obtenerCodigosSitedsTemporales() {
      $sql = "select 
                c.id cita_id, 
                c.id_asegurado, 
                c.id_establecimiento, 
                c.codigo_siteds,
                p.id_tipo_atencion, 
                c2.id as id_consultorio
              from citas c
              left join prestaciones p on c.id = p.id_cita 
              inner join sub_consultorios sc on c.id_sub_consultorio = sc.id 
              inner join consultorios c2 on sc.id_consultorio = c2.id
              where 
                c.codigo_siteds is not null and 
                RIGHT(c.codigo_siteds, 1) = 'T' 
              order by c.id desc 
              limit 10;";

      return DB::SELECT($sql);
    }

    public function listar_prestacion_intervencionqx_ajax($p) {
      return $this->readFunctionPostgres('sp_prestacion_intervencionqx_paginado',$p);
    }


    public function ordenarFechacita($ids){
      if(count($ids)== 0){
        return [];
      }
      $string = implode(",", $ids);
      $sql = "    SELECT 
              cl.id_cita,
              cl.fecha_presencia,
              cl.fecha_llamada,
              cl.orden_atencion,
              c.fecha_hora AS fecha_cita
          FROM citas_llamados cl
          INNER JOIN citas c ON c.id = cl.id_cita 
          WHERE cl.id_cita in ($string)
          ORDER BY ABS(EXTRACT(EPOCH FROM (c.fecha_hora - NOW())))";

    return DB::SELECT($sql);
    }

    // public function listar_prestacion_intervencionqx_ajax_v2($p) {
    //   return $this->readFunctionPostgres('sp_prestacion_intervencionqx_paginado_v2',$p);
    // }
    public function listar_prestacion_intervencionqx_ajax_v3($p) {
      return $this->readFunctionPostgres('sp_prestacion_intervencionqx_paginado_v4',$p);
    }

    public function registrarCita_new($id_cita,$id_ipress,$id_servicio,$id_medico,$id_user,$fecha_hora,$tipo_doc_ident,$nro_doc_ident,$nombre,$paterno,$materno,$parentesco,$nro_historia,$grado,$fecha_nac,$sexo,$cip,$tipodoc_titular,$nrodoctitular,$nombre_titular,$paterno_titular,$materno_titular,$id_parent_cita,$id_estado_cita,$id_estado_reg,$correo="",$domicilio="",$telefono="",$telefono2="",$tipo_cita, $opcion, $referido, $adicional, $nombre_establecimiento, $servicio_referencia, $tipo_doc, $numero_doc, $ano, $origen_doc, $codigo_renipres, $obs_registro_cita,$situacion_titular) {		
      $data = null;
      try {
        DB::transaction(function () use (&$data,$id_cita,$id_ipress,$id_servicio,$id_medico,$id_user,$fecha_hora,$tipo_doc_ident,$nro_doc_ident,$nombre,$paterno,$materno,$parentesco,$nro_historia,$grado,$fecha_nac,$sexo,$cip,$tipodoc_titular,$nrodoctitular, $nombre_titular,$paterno_titular,$materno_titular,$id_parent_cita,$id_estado_cita,$id_estado_reg,$correo,$domicilio,$telefono,$telefono2,$tipo_cita, $opcion, $referido, $adicional, $nombre_establecimiento,$servicio_referencia, $tipo_doc, $numero_doc, $ano, $origen_doc, $codigo_renipres, $obs_registro_cita,$situacion_titular) {
          $data = DB::select('Select sp_insert_cita_1(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', array($id_cita,$id_ipress,$id_servicio,$id_medico,$id_user,$fecha_hora,$tipo_doc_ident,$nro_doc_ident,$nombre,$paterno,$materno,$parentesco,$nro_historia,$grado,$fecha_nac,$sexo,$cip,$tipodoc_titular,$nrodoctitular,$nombre_titular,$paterno_titular,$materno_titular,$id_parent_cita,$id_estado_cita,$id_estado_reg,$correo,$domicilio,$telefono,$telefono2,$tipo_cita, $opcion, $referido, $adicional, $nombre_establecimiento,$servicio_referencia, $tipo_doc, $numero_doc, $ano, $origen_doc, $codigo_renipres, $obs_registro_cita,$situacion_titular)); // 42 params
        });
      } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => 'Error al registrar la cita: ' . $e->getMessage()]);
      }
      return $data[0]->sp_insert_cita_1;
    }
}
