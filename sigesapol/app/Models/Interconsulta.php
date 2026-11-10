<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class Interconsulta extends Model
{
  protected $table = 'interconsultas';
  public $timestamps = false;
  protected $fillable = [
    'id',
    'id_prestacion',
    'id_ext',
    'id_asegurado',
    'id_medico_tratante',
    'id_tipo_atencion',
    'id_servicio',
    'fecha_atencion',
    'id_medico_solicitante',
    'personal_salud_interconsulta',
    'motivo_interconsulta',
    'resumen_interconsulta',
    'codigo_upss',
    'descripcion_upss',
    'turno',
    'id_estado_reg',
    'fecha_firma_interconsulta',
    'ruta_interconsulta',
    'evaluacion',
    'sugerencias',
    'fecha_rpta_atencion',
    'fecha_rpta_firma_interconsulta',
    'ruta_rpta_interconsulta',
    'created_at',
    'id_establecimiento',
    'flag',
    'updated_at',
    'deleted_at',
    'flag_rpta',
    'id_evaluacion',
    'cod_siteds',
    'updated_us_cod_siteds',
    'updated_at_cod_siteds',
    'pre_quirurgico',
    'deleted_us',
    'id_interconsulta'
  ];
    /*public static function RegistrarInterconsulta($id_prestacion, $id_asegurado, $id_tipo_atencion, $fecha_atencion, $id_medico, $id_establecimiento, $inter_servicio, $personal_turno,$personal_salud,$motivo_interconsulta)
    {
        $cad = "Select sp_insert_interconsulta_new(?,?,?,?,?,?,?,?,?,?)";
        //$data = DB::select($cad, array($id_prestacion, $id_asegurado, $id_tipo_atencion, $fecha_atencion, $id_medico,  $id_establecimiento, $p_interconsulta));
        $data = DB::select($cad, array($id_prestacion, $id_asegurado, $id_tipo_atencion, $fecha_atencion, $id_medico,  $id_establecimiento, $inter_servicio, $personal_turno,$personal_salud,$motivo_interconsulta));
        return $data[0]->sp_insert_interconsulta_new;
    }*/

    public static function RegistrarInterconsulta($id_prestacion, $id_asegurado, $id_tipo_atencion, $fecha_atencion, $id_medico, $id_establecimiento, $p_interconsulta)
    {
        $cad = "Select sp_insert_interconsulta_new2(?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_prestacion, $id_asegurado, $id_tipo_atencion, $fecha_atencion, $id_medico,  $id_establecimiento, $p_interconsulta));
        //dd( $cad, array($id_prestacion, $id_asegurado, $id_tipo_atencion, $fecha_atencion, $id_medico,  $id_establecimiento, $p_interconsulta));
        return $data[0]->sp_insert_interconsulta_new2;
    }

    
    public static function RegistrarInterconsultaEvaluacion($id_prestacion, $id_asegurado, $id_tipo_atencion, $fecha_atencion, $id_medico, $id_establecimiento, $p_interconsulta,$id_evaluacion)
    {
        $cad = "Select sp_insert_interconsulta_new_evaluacion2(?,?,?,?,?,?,?,?)";
        //$data = DB::select($cad, array($id_prestacion, $id_asegurado, $id_tipo_atencion, $fecha_atencion, $id_medico,  $id_establecimiento, $p_interconsulta));
        $data = DB::select($cad, array($id_prestacion, $id_asegurado, $id_tipo_atencion, $fecha_atencion, $id_medico,  $id_establecimiento, $p_interconsulta,$id_evaluacion));
        return $data[0]->sp_insert_interconsulta_new_evaluacion2;
    }

    public static function RegistrarInterconsultaRpta($id_prestacion, $id_evolucion, $p_procedimiento, $p_diagnostico, $id_interconsulta, $fecha_atencion, $rpta_interconsulta, $id_user, $id_medico_rpta)
    {
      $cad = "Select sp_insert_rpta_interconsulta(?,?,?,?,?,?,?,?,?)";
      $data = DB::select($cad, array($id_prestacion, $id_evolucion, $p_procedimiento, $p_diagnostico, $id_interconsulta, $fecha_atencion, $rpta_interconsulta, $id_user, $id_medico_rpta));
      return $data[0]->sp_insert_rpta_interconsulta;
    }
    
    public function listar_interconsulta_ajax($p)
  	{
    	return $this->readFunctionPostgres('sp_listar_interconsultas', $p);
  	}

    public function listar_interconsulta_hospitalizacion_ajax($p)
  	{
    	return $this->readFunctionPostgres('sp_listar_interconsultas_hospitalizacion', $p);
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

    public function GetInterconsultaByIdPrestacion($id)
    {
      $cad = "select 
              t1.id,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t2.tipo_doc_ident,
              t7.nombre tipo_atencion,t1.id_tipo_atencion,
              t2.nro_doc_ident,to_char(t1.created_at , 'YYYY-MM-DD') dia,to_char(t1.created_at , 'HH:MI AM') hora,
              t3.nombre establecimiento,t8.id_servicio, t8.descripcion_upss,
              t6.nombre servicio,
              t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,t5.colegiatura as colegiatura_medico,
              tr.nombre medico_nombre_tr,tr.paterno medico_paterno_tr,tr.materno medico_materno_tr,tr.colegiatura as colegiatura_medico_tr,
              t1.id_estado_reg, t8.codigo_upss codigo_interconsulta,
              t8.motivo_interconsulta, t8.resumen_interconsulta, t8.fecha_firma_interconsulta, t8.id as id_interconsulta, t8.created_at,
              t8.is_estable, t8.has_profesional_asignado, t8.personal_coordinacion,t8.flag
              FROM prestaciones t1
              inner join interconsultas t8 on t8.id_prestacion = t1.id 
              inner join asegurados t2 on t8.id_asegurado = t2.id 
              inner join establecimientos t3 on t8.id_establecimiento=t3.id
              inner join consultorios t6 on t8.id_servicio=t6.id
              inner join medicos t5 on t8.id_medico_solicitante=t5.id
              inner join tipo_atenciones t7 on t1.id_tipo_atencion=t7.id
              left join medicos tr on t8.personal_salud_interconsulta=tr.id
              WHERE t8.id_prestacion=".$id."
              And t1.id_estado_reg = 1
              And t8.id_evaluacion = 0
              ORDER BY t8.created_at asc, t8.id asc";
      $data = DB::select($cad);
      return $data;
    }
    public function GetInterconsultaById($id_prestacion, $id_interconsulta)
    {
      $cad = "select 
              tr.dni documento_tr, tr.nombre medico_nombre_tr,tr.paterno medico_paterno_tr,tr.materno medico_materno_tr,tr.colegiatura as colegiatura_medico_tr,tr.rne tr_rne,
              t1.id, t2.id as asegurado_id, t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t2.tipo_doc_ident,
              t1.id_estado_reg, t8.codigo_upss codigo_interconsulta,t8.descripcion_upss,t1.id_tipo_atencion,
              t2.nro_doc_ident,to_char(t1.created_at , 'YYYY-MM-DD') dia,to_char(t1.created_at , 'HH:MI AM') hora,t2.sexo, t2.fecha_nac,t2.grado,t8.id_establecimiento, t8.id_servicio,
              t3.nombre establecimiento,
              t4.nombre profesion,
              t5.dni, t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,t5.colegiatura as colegiatura_medico,t5.rne t5_rne,
              t6.nombre servicio,
              t7.nombre tipo_atencion,
              t8.motivo_interconsulta, t8.resumen_interconsulta, t8.fecha_firma_interconsulta, t8.id as id_interconsulta, t8.created_at,t8.evaluacion,t8.fecha_rpta_atencion,
              t9.nombre especializacion,
              t12.parentesco,
              t8.cod_siteds,
              t8.fecha_atencion, 
              t8.updated_us_cod_siteds,
              t8.updated_at_cod_siteds,
              t8.personal_salud_interconsulta as id_medico_rpta,
              t8.is_estable
              FROM prestaciones t1
              inner join interconsultas t8 on t8.id_prestacion = t1.id 
              inner join asegurados t2 on t8.id_asegurado = t2.id 
              inner join establecimientos t3 on t8.id_establecimiento=t3.id
              inner join consultorios t6 on t8.id_servicio=t6.id
              inner join medicos t5 on t8.id_medico_solicitante=t5.id
              inner join tipo_atenciones t7 on t1.id_tipo_atencion=t7.id
              left join medicos tr on t8.personal_salud_interconsulta=tr.id
              left join citas t12 on t1.id_cita=t12.id
              left join especializaciones t9 on tr.id_especializacion=t9.id
              left join profesiones t4 on tr.id_profesion=t4.id
              WHERE t8.id_prestacion=".$id_prestacion." 
              AND t8.id=".$id_interconsulta."
              And t1.id_estado_reg = 1
	            And t8.id_evaluacion = 0
              ORDER BY t8.created_at desc";
              
              $data = DB::select($cad);
              return $data[0];
    }
    public function GetInterconsultaByIdPrest($id_prestacion) {
        $datos = Interconsulta::where('id_prestacion', '=', $id_prestacion)->where('id_estado_reg', '<>', 0)->get();
        return $datos;
    }

    public function GetInterconsulta($id_interconsulta) {
        $datos = Interconsulta::where('id', '=', $id_interconsulta)->where('id_estado_reg', '<>', 0)->get();
        return $datos[0];
    }

    public function GetBuscaInterconsultaById($id_interconsulta) {

        $cad = "select * from interconsultas
                where id = ".$id_interconsulta; 

        $data = DB::select($cad);
        return $data[0];
    }

    public function ActualizaIdEvaluacion($id, $id_evaluacion) {

        $datos = Interconsulta::where('id', $id)->first();
        $datos->id_evaluacion = $id_evaluacion;
        $datos->save();
        
        return true;
    
    }

    public function GetInterconsultaByIdEvaluacion($id_prestacion, $id_evaluacion,$id_interconsulta)
    {
      $cad = "select 
              tr.dni documento_tr, tr.nombre medico_nombre_tr,tr.paterno medico_paterno_tr,tr.materno medico_materno_tr,tr.colegiatura as colegiatura_medico_tr,tr.rne tr_rne,
              t1.id, t2.id as asegurado_id, t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t2.tipo_doc_ident,
              t1.id_estado_reg, t8.codigo_upss codigo_interconsulta,t8.descripcion_upss,
              t2.nro_doc_ident,to_char(t1.created_at , 'YYYY-MM-DD') dia,to_char(t1.created_at , 'HH:MI AM') hora,t2.sexo, t2.fecha_nac,t2.grado,t8.id_servicio,t8.id_establecimiento,
              t3.nombre establecimiento,t1.id_tipo_atencion,
              t4.nombre profesion,
              t5.dni, t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,t5.colegiatura as colegiatura_medico,t5.rne t5_rne,
              t6.nombre servicio,
              t7.nombre tipo_atencion,
              t8.motivo_interconsulta, t8.resumen_interconsulta, t8.fecha_firma_interconsulta, t8.id as id_interconsulta, t8.created_at,t8.evaluacion,t8.fecha_rpta_atencion,
              t9.nombre especializacion,
              t12.parentesco,
              t8.cod_siteds,
              t8.fecha_atencion, 
              t8.updated_us_cod_siteds,
              t8.updated_at_cod_siteds,
              t8.personal_salud_interconsulta as id_medico_rpta,
              t8.is_estable
              FROM prestaciones t1
              inner join interconsultas t8 on t8.id_prestacion = t1.id 
              inner join asegurados t2 on t8.id_asegurado = t2.id 
              inner join establecimientos t3 on t8.id_establecimiento=t3.id
              inner join consultorios t6 on t8.id_servicio=t6.id
              inner join medicos t5 on t8.id_medico_solicitante=t5.id
              inner join tipo_atenciones t7 on t1.id_tipo_atencion=t7.id
              left join medicos tr on t8.personal_salud_interconsulta=tr.id
              left join citas t12 on t1.id_cita=t12.id
              left join especializaciones t9 on tr.id_especializacion=t9.id
              left join profesiones t4 on tr.id_profesion=t4.id
              WHERE t8.id_prestacion=".$id_prestacion." and t8.id=".$id_interconsulta." and t8.id_evaluacion=".$id_evaluacion."
              And t1.id_estado_reg = 1
              ORDER BY t8.created_at desc";
      
      $data = DB::select($cad);
      return $data[0];
    }

    public function GetInterconsultaByIdEvaluacion2($id_prestacion,$id_evaluacion)
    {
      $cad = "select 
              t1.id,t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t2.tipo_doc_ident,t7.nombre tipo_atencion,
              t2.nro_doc_ident,to_char(t1.created_at , 'YYYY-MM-DD') dia,to_char(t1.created_at , 'HH:MI AM') hora,t3.nombre establecimiento,t6.nombre servicio,t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,t1.id_estado_reg, t8.motivo_interconsulta, t8.codigo_upss codigo_interconsulta,t8.descripcion_upss,t5.colegiatura as colegiatura_medico,
              t5.nombre medico_nombre_tr,t5.paterno medico_paterno_tr,t5.materno medico_materno_tr,t5.colegiatura as colegiatura_medico_tr, t8.id_servicio, t8.id id_interconsulta, t8.fecha_atencion, t8.is_estable, t8.has_profesional_asignado, t8.personal_coordinacion, t8.fecha_rpta_atencion
              FROM prestaciones t1
              inner join interconsultas t8 on t8.id_prestacion = t1.id 
              inner join asegurados t2 on t8.id_asegurado = t2.id 
              inner join establecimientos t3 on t8.id_establecimiento=t3.id
              inner join consultorios t6 on t8.id_servicio=t6.id
              left join medicos t5 on t8.personal_salud_interconsulta=t5.id
              inner join tipo_atenciones t7 on t1.id_tipo_atencion=t7.id
              WHERE t8.id_prestacion=".$id_prestacion." and t8.id_evaluacion=".$id_evaluacion."
              And t1.id_estado_reg = 1 and t8.id_estado_reg = 1
              ORDER BY t8.created_at asc, t8.id asc";
      $data = DB::select($cad);
      //if(count($data)>0):
      //  return $data[0];
      //else:
        return $data;
      //endif; 
    }

    public function GetInterconsultaByIdPrestacionEvaluacion($id_prestacion,$id_evaluacion) {
      $datos = Interconsulta::where('id_prestacion', '=', $id_prestacion)->where('id_evaluacion', '=', $id_evaluacion)->where('id_estado_reg', '<>', 0)->get();

      return $datos;
  }

  public function GetDiagnosticoByIdInterconsulta($id)
  {

    $cad = "select distinct t1.id_diagnostico,coalesce(t1.id_tipo_diagnostico,0)id_tipo_diagnostico,codigo,
            (case when codigo='Z21.X' or codigo ilike 'B20%' Then '' else nombre end)nombre, id_clasificacion_diagnosticos
            from receta_diagnosticos t1
            inner join diagnosticos t2 on t1.id_diagnostico=t2.id
            where t1.id_interconsulta=" . $id . "
            and t1.estado=1";
    $data = DB::select($cad);
    return $data;
  }

  public function GetExamenInterconsultaByDniPrestacion($dni_beneficiario) {
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
                where t1.id_estado_reg = 1 and t4.descripcion = 'INTERCONSULTA' and t2.nro_doc_ident = '" . $dni_beneficiario . "'";
        $cad .= " order By t1.id desc";
        $data = DB::select($cad);
        return $data;
    }
  public function GetExamenInterconsultaRpta($dni_beneficiario)
    {
      $cad = "select 
              i.id_tipo_atencion,i.id_evaluacion, a.nro_doc_ident , a.grado, i.id_prestacion, to_char(i.fecha_atencion, 'DD/MM/YYYY') fecha_atencion, CASE WHEN (i.id_evaluacion > 0) THEN (SELECT to_char(e.created_at, 'HH24:MI:SS') FROM evaluaciones e WHERE i.id_evaluacion = e.id) ELSE (SELECT to_char(p2.created_at, 'HH24:MI:SS') FROM prestaciones p2 WHERE i.id_prestacion = p2.id) END hora_atencion,
              to_char(i.fecha_rpta_atencion, 'DD/MM/YYYY') fecha_rpta_atencion, to_char(i.fecha_rpta_atencion, 'HH24:MI:SS') hora_rpta_atencion,ta.nombre tipo_atencion, m1.nombre nombre_medico, m1.paterno paterno_medico, m1.materno materno_medico,m2.nombre nombre_medico2, m2.paterno paterno_medico2, m2.materno materno_medico2, c.nombre nombre_consultorio, i.id_establecimiento,  a.nombre, a.paterno, a.materno, i.id_estado_reg, i.id, a.sexo, to_char(a.fecha_nac::date,'DD/MM/YYYY')fecha_nac,date_part('year',age(to_date(to_char(a.fecha_nac::date,'DD/MM/YYYY'),'DD/MM/YYYY'))) edadpaciente, to_char(i.created_at , 'YYYY-MM-DD') dia,tp.descripcion parentesco, i.flag_rpta, i.id_evaluacion, i.id_prestacion
              from interconsultas as i
              left join asegurados a on i.id_asegurado=a.id
              left join medicos m1 on i.id_medico_solicitante=m1.id
              left join medicos m2 on i.personal_salud_interconsulta=m2.id
              left join tipo_atenciones ta on i.id_tipo_atencion=ta.id
              left join consultorios c on i.id_servicio=c.id
              left join tipo_beneficiarios tp on a.id_tipo_beneficiario=tp.id
              WHERE i.id_estado_reg = 1 and a.nro_doc_ident = '" . $dni_beneficiario . "'";
        $cad .= " order By i.id desc";
              
      $data = DB::select($cad);
      return $data;
    }

    public function GetInterconsultasPendientesByIdMedicoTratante($id_medico_interconsulta) {
      $cad = "select
                i.id,
                c.nombre servicio_solicitante,
                sc.descripcion consultorio_atencion,
                to_char(i.fecha_atencion, 'DD/MM/YYYY') fecha_solicitud,
                CASE WHEN (i.id_evaluacion > 0) THEN (SELECT to_char(e.created_at, 'HH24:MI:SS') FROM evaluaciones e WHERE i.id_evaluacion = e.id)
                ELSE (SELECT to_char(p2.created_at, 'HH24:MI:SS') FROM prestaciones p2 WHERE i.id_prestacion = p2.id) END hora_solicitud,
                m.paterno||' '||m.materno||', '||m.nombre medico_solicitante,
                a.nro_doc_ident ndocumento_paciente,
                a.paterno||' '||a.materno||',  '||a.nombre paciente,
                c2.nombre servicio_interconsulta,
                t.descripcion nombre_area,
                c4.nombre cuarto,
                c3.nombre cama
            from interconsultas i inner join prestaciones p on i.id_prestacion = p.id 
                inner join sub_consultorios sc on p.id_sub_consultorio = sc.id 
                inner join consultorios c on sc.id_consultorio = c.id
                inner join medicos m on i.id_medico_solicitante = m.id
                inner join asegurados a on i.id_asegurado = a.id
                inner join consultorios c2 on i.id_servicio = c2.id
                INNER JOIN emergencias e2 ON i.id_ext = e2.id
                INNER JOIN triajes t ON e2.ubica_topico = t.id
                INNER JOIN camas c3 ON e2.cama_estado = c3.id 
                INNER JOIN cuartos c4 ON c3.id_cuarto = c4.id
            where i.personal_salud_interconsulta = $id_medico_interconsulta 
                AND i.fecha_rpta_atencion IS NULL
                AND i.fecha_atencion BETWEEN now() - INTERVAL '2 days' AND now()
                AND i.id_tipo_atencion = 2
            order by i.fecha_atencion, i.id;";
  
      $data = DB::select($cad);
      return $data;
    }

    public function GetInterconsultasPendientesByIdServicio($id_establecimiento, $id_servicio) {

      $fechaInicio = Carbon::now()->subDays(2)->format('Y-m-d H:i:s');
      $fechaFin = Carbon::now()->format('Y-m-d H:i:s');
      $data = null;

      if ($id_servicio > 0):
        $data = DB::connection('pgsql_reportes')->table('interconsultas as i')
          ->select('i.id_tipo_atencion', DB::raw('count(i.id) as cantidad'))
          ->where('i.created_at', '>=', $fechaInicio)
          ->where('i.created_at', '<=', $fechaFin)
          ->where('i.id_establecimiento', $id_establecimiento)
          ->where('i.id_servicio', $id_servicio)
          ->where('i.flag', '<>', 0)
          ->whereNull('i.fecha_rpta_atencion')
          ->where('i.id_estado_reg', 1)
          ->groupBy('i.id_tipo_atencion')
          ->orderBy('i.id_tipo_atencion')
          ->get();
      else:
        $data = DB::connection('pgsql_reportes')->table('interconsultas as i')
          ->select('i.id_tipo_atencion', DB::raw('count(i.id) as cantidad'))
          ->where('i.created_at', '>=', $fechaInicio)
          ->where('i.created_at', '<=', $fechaFin)
          ->where('i.id_establecimiento', $id_establecimiento)
          ->where('i.flag', '<>', 0)
          ->whereNull('i.fecha_rpta_atencion')
          ->where('i.id_estado_reg', 1)
          ->groupBy('i.id_tipo_atencion')
          ->orderBy('i.id_tipo_atencion')
          ->get();
      endif;
      return $data;
    }

    public static function getConsultorioByPrestacionId($prestacionId)
    {
      $interconsultas = DB::table('interconsultas as i')
        ->select(
            'i.id as id_interconsultas',
            'i.id_prestacion',
            'c.id as id_consultorio',
            'c.nombre as consultorio_nombre',
            DB::raw('i.fecha_atencion::date as fecha_atencion'),
            DB::raw('i.fecha_atencion::time as hora_atencion'),
            DB::raw('i.fecha_rpta_atencion::date as fecha_respuesta'),
            DB::raw('i.fecha_rpta_atencion::time as hora_respuesta')
        )
        ->join('emergencias as e', 'i.id_ext', '=', 'e.id')
        ->join('consultorios as c', 'i.id_servicio', '=', 'c.id')
        ->where('i.id_prestacion', $prestacionId)
        ->get();
        
      return $interconsultas;
    }

    public function RegistrarInterconsultaCentroQx($id_intervencion_cqx,$id_user,$id_prestacion, $id_asegurado, $id_tipo_atencion, $fecha_atencion, $id_medico, $id_establecimiento, $p_interconsulta)
    {
        $cad = "Select sp_insert_interconsulta_cita_cqx(?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_intervencion_cqx, $id_user, $id_prestacion, $id_asegurado, $id_tipo_atencion, $fecha_atencion, $id_medico,  $id_establecimiento, $p_interconsulta));
        return $data[0]->sp_insert_interconsulta_cita_cqx;
    }

    public function GetInterconsultaPrequirurgicaByIdPrestacion($id_prestacion)
    {
      $results = DB::table('interconsultas as i')
        ->join('consultorios as c', 'i.id_servicio', '=', 'c.id')
        ->select('i.id', 'c.nombre as servicio', 'i.motivo_interconsulta')
        ->where('i.id_prestacion', $id_prestacion)
        ->where('i.pre_quirurgico', true)
        ->get();
      
      return $results;
    }

    /**
     * Funcion para ver la informacion de una interconsulta activa, solo considerando el ID
     * y no otros filtros como el id_evaluacion o el id_prestacion.
     * 
     */
    public function GetInterconsultaById2($id_interconsulta)
    {
      $cad = "select 
              tr.dni as documento_tr, tr.nombre medico_nombre_tr,tr.paterno medico_paterno_tr,tr.materno medico_materno_tr,tr.colegiatura as colegiatura_medico_tr,tr.rne tr_rne,
              t1.id, t2.id as asegurado_id, t2.nombre asegurado_nombre,t2.paterno asegurado_paterno,t2.materno asegurado_materno,t2.tipo_doc_ident,
              t1.id_estado_reg, t8.codigo_upss codigo_interconsulta,t8.descripcion_upss,t1.id_tipo_atencion,
              t2.nro_doc_ident,to_char(t1.created_at , 'YYYY-MM-DD') dia,to_char(t1.created_at , 'HH:MI AM') hora,t2.sexo, t2.fecha_nac,t2.grado,t8.id_establecimiento, t8.id_servicio,
              t3.nombre establecimiento,
              t4.nombre profesion,
              t5.dni, t5.nombre medico_nombre,t5.paterno medico_paterno,t5.materno medico_materno,t5.colegiatura as colegiatura_medico,t5.rne t5_rne,
              t6.nombre servicio,
              t7.nombre tipo_atencion,
              t8.motivo_interconsulta, t8.resumen_interconsulta, t8.fecha_firma_interconsulta, t8.id as id_interconsulta, t8.created_at,t8.evaluacion,t8.fecha_rpta_atencion,
              t9.nombre especializacion,
              t12.parentesco,
              t8.cod_siteds, 
              t8.updated_us_cod_siteds,
              t8.updated_at_cod_siteds,
              t8.personal_salud_interconsulta as id_medico_rpta,
              t8.fecha_atencion,
              t8.is_estable
              FROM prestaciones t1
              inner join interconsultas t8 on t8.id_prestacion = t1.id 
              inner join asegurados t2 on t8.id_asegurado = t2.id 
              inner join establecimientos t3 on t8.id_establecimiento=t3.id
              inner join consultorios t6 on t8.id_servicio=t6.id
              inner join medicos t5 on t8.id_medico_solicitante=t5.id
              inner join tipo_atenciones t7 on t1.id_tipo_atencion=t7.id
              left join medicos tr on t8.personal_salud_interconsulta=tr.id
              left join citas t12 on t1.id_cita=t12.id
              left join especializaciones t9 on tr.id_especializacion=t9.id
              left join profesiones t4 on tr.id_profesion=t4.id
              WHERE t8.id=".$id_interconsulta."
              And t1.id_estado_reg = 1
              ORDER BY t8.created_at desc";
              
      $data = DB::select($cad);
      return $data[0];
    }
    
    /**
     * Funcion para obtener todas las interconsultas sin joins de una evaluacion/evolucion
     */
    public function GetAllInterconsultaSimpleByIdEvaluacion($id_evaluacion) {
      $datos = Interconsulta::where('id_evaluacion', '=', $id_evaluacion)->where('id_estado_reg', '<>', 0)->get();
      return $datos;
    }

    /**
     * Funcion para obtener todas las interconsultas sin joins de una prestacion
     */
    public function GetAllInterconsultaSimpleByIdPrestacion($id_prestacion) {
      $datos = Interconsulta::where('id_prestacion', '=', $id_prestacion)->where('id_estado_reg', '<>', 0)->get();
      return $datos;
    }
    public function cargarUpsses()
    {
        // Consultar los registros de la tabla `upsses`
        $result = DB::table('upsses')
            ->whereIn('codigo', ['241100', '240200'])  // Filtrar por los códigos solicitados
            ->where('estado', '1')                     // Filtrar por estado
            ->get(['codigo','descripcion_upss']);      // Seleccionar solo las columnas necesarias
    
        // Retornar los datos en formato JSON
        return response()->json($result);
    }
    public function cargarUpsDIAG(){
      $result1 = DB::table('upsses')
        ->whereIn('codigo', ['080000', '080100', '080200', '080300', '080400', '080503', '080600', '080700', '080800', '080900','140000','180000']) 
        ->orderBy('codigo', 'asc') 
        ->get(['codigo', 'descripcion_upss']); 

      return response()->json($result1);
    }

    public function GetDocumentosPendientesFirmarInterconsultaRespuesta($id_interconsulta)
    {
      $query_string = "with tbl as (
        select id, id_prestacion, to_char(fecha_firma,'YYYY-MM-DD HH24:MI:SS') fecha_firma, flag, 'recetas' tipo 
        from receta_vales rv where id_interconsulta = {$id_interconsulta}
        union all
        select id, id_prestacion, fecha_firma, flag, 'laboratorio' tipo 
        from laboratorios l where id_interconsulta = {$id_interconsulta}
        union all
        select id, id_prestacion, fecha_firma, flag, 'radiologias' tipo 
        from radiologias r where id_interconsulta = {$id_interconsulta}
        union all
        select id, id_prestacion,fecha_rpta_firma_interconsulta as fecha_firma, flag_rpta as flag, 'interconsulta2' tipo 
        from interconsultas irpta where id = {$id_interconsulta}
      ) 
      select * from tbl
      where flag = 0";
      return DB::select($query_string);
    }
    public function GetInterconsultaByIdPrestacionEvaluaciondsf($id_prestacion, $id_evaluacion) {
      $datos = Interconsulta::select('id as id_interconsulta', '*')
          ->where('id_prestacion', $id_prestacion)
          ->where('id_evaluacion', $id_evaluacion)
          ->where('id_estado_reg', '<>', 0)
          ->get();
      return $datos;
    }
  
}