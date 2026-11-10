<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Carbon\Carbon;

class Procedimiento extends Model
{
  protected $fillable = ['id', 'seccion', 'subseccion', 'codigo', 'descripcion', 'genero', 't_nivel1', 't_nivel2', 't_nivel3', 'convenio', 'estado'];
  public function GetProcedimientoByCodigo($id)
  {
    $datos = DB::table('procedimientos')
      ->select('procedimientos.*')
      ->where('procedimientos.codigo', $id)
      ->get();
    return $datos;
  }

  public static function GetProcedimientoById($id)
  {
    $procedimiento = Procedimiento::find($id);
    return $procedimiento;
  }

  /*public function getProcedimientoByNombre($buscar) {

    $cad = "select *
from procedimientos
where codigo||descripcion ilike '%".$buscar."%'
order By descripcion asc
limit 50 ";
        
        $data = DB::select($cad);
        return $data;
		
    }
	*/

  function getProcedimientos($limit, $pagina)
  {

    $cad = "Select * From procedimientos Order By id limit 100";
    $data = DB::select($cad);
    return $data;
  }

  public function procedimiento_yaregistrada($codigo)
  {
    $datos = DB::table('procedimientos')
      ->where('procedimientos.codigo', '=', $codigo)
      ->select('procedimientos.*')
      ->first();
    return $datos;
  }

  public function getProcedimientoByNombre($buscar)
  {

    $cad = "select *
from procedimientos
where codigo||descripcion ilike '%" . $buscar . "%'  and estado = 1
order By descripcion asc
limit 50 ";

    $data = DB::select($cad);
    return $data;
  }


  public function getProcedimientoByConvenio($buscar, $nivel, $genero, $tipo)
  {
    $nivel = $nivel + 1;
    $cad = "select *
from procedimientos
where codigo||descripcion ilike '%" . $buscar . "%' and convenio=1  and estado=1 and ( genero=" . $genero . " or genero=3 ) and 1=1 and nivel<" . $nivel . " ";


    /*switch ($tipo) { //1: 
      case 1: //todo menos lab ni radio
        $cad .= "and codigo_seccion not IN ('1.5','1.4') ";
        break;
      case 2: //laboratorio
        $cad .= "and codigo_seccion='1.5' ";
        break;
      case 3: //radiografia
        $cad .= "and codigo_seccion='1.4' ";
        break;
    }
*/
    /*switch ($nivel) { //1: 
      case 1: //todo menos lab ni radio
        //$query.="t_nivel1 > 0";
        break;
      case 2: //laboratorio
        $cad .= "and t_nivel1 > 0 ";
        break;
      case 3: //radiografia
        $cad .= "and t_nivel2 > 0 ";
        break;
    }*/

    $cad .= " Order by codigo asc
                  limit 50 ";

    //echo ($cad);

    $data = DB::select($cad);
    return $data;
  }

  public function getProcedimientoByConvenioEnfermeria($buscar, $nivel, $genero, $tipo)
  {
    $nivel = $nivel + 1;
    $cad = "select *
from procedimientos
where codigo||descripcion ilike '%" . $buscar . "%' and convenio=1  and estado=1 and estado_enfermeria=1";

    $cad .= " Order by codigo asc
                  limit 50 ";

    $data = DB::select($cad);
    return $data;
  }
  public function getProcedimientoByConvenioEmergencia($buscar, $nivel, $genero, $tipo)
  {
    $nivel = $nivel + 1;
    $cad = "select *
from procedimientos
where codigo||descripcion ilike '%" . $buscar . "%' and convenio=1 and estado=1 and estado_emergencia=1 and ( genero=" . $genero . " or genero=3 ) and 1=1 and nivel<" . $nivel . " ";


    switch ($tipo) { //1: 
      case 1: //todo menos lab ni radio
        $cad .= "and codigo_seccion not IN ('1.5','1.4') ";
        break;
      case 2: //laboratorio
        $cad .= "and codigo_seccion='1.5' ";
        break;
      case 3: //radiografia
        $cad .= "and codigo_seccion='1.4' ";
        break;
    }

    switch ($nivel) { //1: 
      case 1: //todo menos lab ni radio
        //$query.="t_nivel1 > 0";
        break;
      case 2: //laboratorio
        $cad .= "and t_nivel1 > 0 ";
        break;
      case 3: //radiografia
        $cad .= "and t_nivel2 > 0 ";
        break;
    }

    $cad .= " Order by codigo asc
                  limit 50 ";

    //echo ($cad);

    $data = DB::select($cad);
    return $data;
  }
  public function GetProcedimientosByIdPrestacion($id_prestacion)
  {
    $datos = DB::table('prestacion_procedimientos')->where('id_prestacion', '=', $id_prestacion)->get();
    return $datos;
  }

  public static function RegistrarProcedimiento($id_user,  $id_prestacion, $id_establecimiento, $fecha_atencion, $p_procedimiento, $tipo, $id_evaluacion, $id_farmacia = 0)
  {
    $cad = "Select sp_insert_procedimiento(?,?,?,?,?,?,?,?)";
    $data = DB::select($cad, array($id_user, $id_prestacion, $id_establecimiento, $fecha_atencion, $p_procedimiento, $tipo, $id_evaluacion, $id_farmacia));
    return $data[0]->sp_insert_procedimiento;
  }

  public static function RegistrarProcedimientoxDiagnostico($idprestacionxDiagProc,  $idprocedimiento, $iddiagnostico, $estado)
  {
    $cad = "select * FROM sp_diagnosticoxprocedimiento(?,?,?,?)";
    $data = DB::select($cad, array($idprestacionxDiagProc, $idprocedimiento, $iddiagnostico, $estado));
    return $data[0]->sp_diagnosticoxprocedimiento;
  }

  public static function RegistrarProcedimientoxDiagnosticoEvaluacion($idprestacionxDiagProc,  $idprocedimiento, $iddiagnostico, $estado, $idevaluacion)
  {
    $cad = "select * FROM sp_diagnosticoxprocedimientoxevaluacion(?,?,?,?,?)";
    $data = DB::select($cad, array($idprestacionxDiagProc, $idprocedimiento, $iddiagnostico, $estado, $idevaluacion));
    return $data[0]->sp_diagnosticoxprocedimientoxevaluacion;
  }
  public static function RegistrarProcedimientoxDiagnosticoInterconsulta($idprestacionxDiagProc,  $idprocedimiento, $iddiagnostico, $estado, $idinterconsulta)
  {
    $cad = "select * FROM sp_diagnosticoxprocedimientoxinterconsulta(?,?,?,?,?)";
    $data = DB::select($cad, array($idprestacionxDiagProc, $idprocedimiento, $iddiagnostico, $estado, $idinterconsulta));
    return $data[0]->sp_diagnosticoxprocedimientoxinterconsulta;
  }
  public function listar_prestacion_ajax($p)
  {
    return $this->readFunctionPostgres('sp_listar_procedimientos', $p);
  }

  public function listar_procedimientos_ajax($p)
  {
    return $this->readFunctionPostgres('sp_listar_procedimientos', $p);
  }
  public function listar_procedimientos_ajaxv2($p)
  {
    return $this->readFunctionPostgres('sp_listar_procedimientosv3', $p);
  }

  public function getAllProcedimientosPaginado($request)
  {
    $query = Procedimiento::query();

    if ($request->p_search) {
        $query->where(function($q) use ($request) {
        $q->where('codigo', 'like', '%' . $request->p_search . '%')
          ->orWhere('descripcion', 'like', '%' . $request->p_search . '%');
      });
    }

    // Verificamos que sea correspondiente al tarifario
    $query->where('tarifario', '=', 1);

    /* if ($request->p_seccion) {
        $query->where('seccion', 'like', '%' . $request->p_seccion . '%');
    }

    if ($request->p_subseccion) {
        $query->where('subseccion', 'like', '%' . $request->p_subseccion . '%');
    }

    if ($request->p_descripcion) {
        $query->where('descripcion', 'like', '%' . $request->p_descripcion . '%');
    }

    if ($request->p_genero) {
        $query->where('genero', 'like', '%' . $request->p_genero . '%');
    }

    if ($request->p_convenio) {
        $query->where('convenio', 'like', '%' . $request->p_convenio . '%');
    }

    if ($request->p_estado) {
        $query->where('estado', 'like', '%' . $request->p_estado . '%');
    } */

    $page = $request->p_pagina ? $request->p_pagina : 1;
    $procedimientos = $query->paginate($request->p_limit, ['*'], 'page', $page);
    return $procedimientos;
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

  public function procedimientosxestado()
  {
    $cad = "select id,codigo,descripcion from procedimientos where estado=1";
    $data = DB::select($cad);
    return $data;
  }

  public function getProcedimientosLab()
  {

    $cad = "select * from obtener_procedimientos_laboratorio()";
    $data = DB::select($cad);
    return $data;
  }
  public function procedimientosxprestacion($idprestacionEnvio)
  {
    $cad = "select id_procedimientos
          from procedimientos_diagnoticos
          where id_prestacion=" . $idprestacionEnvio;
    $data = DB::select($cad);
    return $data;
  }

  public static function RegistrarProcedimientoInterconsulta($id_user,  $id_prestacion, $id_establecimiento, $fecha_atencion, $p_procedimiento, $tipo, $id_interconsulta)
  {
    $cad = "Select sp_insert_procedimiento_interconsulta(?,?,?,?,?,?,?)";
    $data = DB::select($cad, array($id_user, $id_prestacion, $id_establecimiento, $fecha_atencion, $p_procedimiento, $tipo, $id_interconsulta));
    return $data[0]->sp_insert_procedimiento_interconsulta;
  }

  public static function RegistrarProcedimiento2($id_user,  $id_prestacion, $id_establecimiento, $fecha_atencion, $p_procedimiento, $tipo, $id_evaluacion, $id_farmacia)
  {
    $cad = "Select sp_insert_procedimiento_2(?,?,?,?,?,?,?,?)";
    $data = DB::select($cad, array($id_user, $id_prestacion, $id_establecimiento, $fecha_atencion, $p_procedimiento, $tipo, $id_evaluacion, $id_farmacia));
    return $data[0]->sp_insert_procedimiento_2;
  }

  /* Solo Hospitalizacion 03MAR2026 */
  public static function RegistrarProcedimiento2_Hospitalizacion($id_user,  $id_prestacion, $id_establecimiento, $fecha_atencion, $p_procedimiento, $tipo, $id_evaluacion, $id_farmacia, $observacion)
  {
    $cad = "Select sp_insert_procedimiento_2(?,?,?,?,?,?,?,?,?)";
    $data = DB::select($cad, array($id_user, $id_prestacion, $id_establecimiento, $fecha_atencion, $p_procedimiento, $tipo, $id_evaluacion, $id_farmacia, $observacion));
    return $data[0]->sp_insert_procedimiento_2;
  }

  public static function RegistrarLaboratorioOrden2($id_user, $id_prestacion, $id_establecimiento, $fecha_atencion, $p_procedimiento, $tipo, $id_evaluacion, $id_farmacia, $id_interconsulta, $id_tipo_atencion, $id_servicio, $id_area, $id_cama, $id_medico, $id_asegurado)
  {
    $cad = "Select sp_insert_laboratorio_orden2(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $data = DB::select($cad, array($id_user, $id_prestacion, $id_establecimiento, $fecha_atencion, $p_procedimiento, $tipo, $id_evaluacion, $id_farmacia, $id_interconsulta, $id_tipo_atencion, $id_servicio, $id_area, $id_cama, $id_medico, $id_asegurado));
    return $data[0]->sp_insert_laboratorio_orden2;
  }

  /* Solo Hospitalizacion 03MAR2026 */
  public static function RegistrarLaboratorioOrden2_Hospitalizacion($id_user, $id_prestacion, $id_establecimiento, $fecha_atencion, $p_procedimiento, $tipo, $id_evaluacion, $id_farmacia, $id_interconsulta, $id_tipo_atencion, $id_servicio, $id_area, $id_cama, $id_medico, $id_asegurado, $observacion)
  {
    $cad = "Select sp_insert_laboratorio_orden2(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"; // 16 parametros
    $data = DB::select($cad, array($id_user, $id_prestacion, $id_establecimiento, $fecha_atencion, $p_procedimiento, $tipo, $id_evaluacion, $id_farmacia, $id_interconsulta, $id_tipo_atencion, $id_servicio, $id_area, $id_cama, $id_medico, $id_asegurado, $observacion));
    return $data[0]->sp_insert_laboratorio_orden2;
  }

  /*public static function RegistrarLaboratorioOrden2($id_user, $id_prestacion, $id_establecimiento, $fecha_atencion, $p_procedimiento, $tipo, $id_evaluacion, $id_farmacia, $id_interconsulta, $id_tipo_atencion, $id_servicio, $id_area, $id_cama, $id_medico, $id_asegurado, $observacion)
  {
    $cad = "Select sp_insert_laboratorio_orden2(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"; // 16 parametros
    $data = DB::select($cad, array($id_user, $id_prestacion, $id_establecimiento, $fecha_atencion, $p_procedimiento, $tipo, $id_evaluacion, $id_farmacia, $id_interconsulta, $id_tipo_atencion, $id_servicio, $id_area, $id_cama, $id_medico, $id_asegurado, $observacion));
    return $data[0]->sp_insert_laboratorio_orden2;
  }*/
  public static function RegistrarLaboratorioOrden3($id_user, $id_prestacion, $id_establecimiento, $fecha_atencion, $p_procedimiento, $has_observacion_unica, $id_evaluacion, $id_farmacia, $id_interconsulta, $id_tipo_atencion, $id_servicio, $id_area, $id_cama, $id_medico, $id_asegurado, $observacion, $flag_cq, $id_hora_toma_muestra)
  {
    $cad = "Select sp_insert_laboratorio_orden2(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"; // 18 parametros
    $data = DB::select($cad, array($id_user, $id_prestacion, $id_establecimiento, $fecha_atencion, $p_procedimiento, $has_observacion_unica, $id_evaluacion, $id_farmacia, $id_interconsulta, $id_tipo_atencion, $id_servicio, $id_area, $id_cama, $id_medico, $id_asegurado, $observacion, $flag_cq, $id_hora_toma_muestra));
    return $data[0]->sp_insert_laboratorio_orden2;
  }

  /**
   * Copia de RegistrarProcedimiento2 considerando id_interconsulta
   */
  public static function RegistrarLaboratorioOrden($id_user, $id_prestacion, $id_establecimiento, $fecha_atencion, $p_procedimiento, $tipo, $id_evaluacion, $id_farmacia, $id_interconsulta)
  {
    $cad = "Select sp_insert_laboratorio_orden(?,?,?,?,?,?,?,?,?)";
    $data = DB::select($cad, array($id_user, $id_prestacion, $id_establecimiento, $fecha_atencion, $p_procedimiento, $tipo, $id_evaluacion, $id_farmacia, $id_interconsulta));
    return $data[0]->sp_insert_laboratorio_orden;
  }

  public static function searchByTipoProcedimientoAndText($tipo_procedimiento, $text)
  {
    $datos = DB::table('procedimientos')
      ->where('tipo_procedimiento', $tipo_procedimiento)
      ->where(function ($query) use ($text) {
        $query->where('codigo', 'ilike', '%' . $text . '%')
          ->orWhere('descripcion', 'ilike', '%' . $text . '%');
      })
      ->get();
    return $datos;
  }

  public function getProcedimientosRad()
  {

    $cad = "select * from obtener_procedimientos_radiologia()";
    $data = DB::select($cad);
    return $data;
  }

  public static function RegistrarProcedimientoImagenes($id_user,  $id_prestacion, $id_establecimiento, $fecha_atencion, $p_procedimiento, $tipo, $id_evaluacion, $id_farmacia)
  {
    $cad = "Select sp_insert_procedimiento_imagenes(?,?,?,?,?,?,?,?)";
    $data = DB::select($cad, array($id_user, $id_prestacion, $id_establecimiento, $fecha_atencion, $p_procedimiento, $tipo, $id_evaluacion, $id_farmacia));
    return $data[0]->sp_insert_procedimiento_imagenes;
  }

  /**
   * Copia de RegistrarProcedimientoImagenes considerando id_interconsulta
   */
  public static function RegistrarRadiologiaOrden($id_user,  $id_prestacion, $id_establecimiento, $fecha_atencion, $p_procedimiento, $tipo, $id_evaluacion, $id_farmacia, $id_interconsulta)
  {
    $cad = "Select sp_insert_radiologia_orden(?,?,?,?,?,?,?,?,?)";
    $data = DB::select($cad, array($id_user, $id_prestacion, $id_establecimiento, $fecha_atencion, $p_procedimiento, $tipo, $id_evaluacion, $id_farmacia, $id_interconsulta));
    return $data[0]->sp_insert_radiologia_orden;
  }

  // public static function RegistrarProcedimientoImagenes_hsp($id_user,  $id_prestacion, $id_establecimiento, $fecha_atencion, $p_procedimiento, $tipo = 3, $id_evaluacion, $id_farmacia)
  // {
  //   $cad = "Select sp_insert_procedimiento_imagenes_hsp(?,?,?,?,?,?,?,?)";
  //   $data = DB::select($cad, array($id_user, $id_prestacion, $id_establecimiento, $fecha_atencion, $p_procedimiento, $tipo, $id_evaluacion, $id_farmacia));
  //   return $data[0]->sp_insert_procedimiento_imagenes_hsp;
  // }

  public function getServicioPorIdProcedimiento($id_procedimiento)
  {
    $datos = DB::table('procedimiento_servicios')
      ->select('procedimiento_servicios.*')
      ->where('procedimiento_servicios.id_procedimiento', '=', $id_procedimiento)
      ->where('procedimiento_servicios.estado', '=', 1)
      ->get();
    return $datos;
    }

  public function getProcedimientoByConvenioTipoAtencion($buscar, $nivel, $genero, $tipoAtencionId)
  {
    $nivel = $nivel + 1;
    $cad = "select * from procedimientos where codigo||descripcion ilike '%" . $buscar . "%' and convenio=1  and estado=1 and ( genero=" . $genero . " or genero=3 ) and 1=1 and nivel<" . $nivel . " ";
    switch ($tipoAtencionId) {
      case 1: // AMBULATORIO
        //$query.="";
        break;
      case 2: // EMERGENCIA
        $cad .= "and codigo not in ('99213', '99201', '99202', '99203', '99204', '99211', '99212', '99215', '99215.02') ";
        break;
      case 3: // HOSPITALIZACION
        $cad .= "and codigo not in ('99213', '99201', '99202', '99203', '99204', '99211', '99212', '99215', '99215.02') ";
        break;
    }
    $cad .= " Order by codigo asc limit 50 ";

    $data = DB::select($cad);
    return $data;
  }

  /**
   * Obtener procedimientos de Prestacion ($id_prestacion y $id_evaluacion = 0)
   * Obtener procedimientos de Evaluacion (Emergencia) o Evolucion (Hospitalizacion) ($id_prestacion y $id_evaluacion)
   * 
   * Reemplazar Prestaciones -> GetProcedimientosByIdPrestacionydiagEvaluacion($id, $tipo, $id_evaluacion)
   * 
   * No es confiable por no traer la cantidad de "prestacion_procedimientos"
   */
  public function GetProcedimientosPrestacionOrEvaluacion($id_prestacion, $id_evaluacion)
  {
    $data = DB::table('procedimientos_diagnoticos as pd')
      ->join('procedimientos as p', 'pd.id_procedimientos', '=', 'p.id')
      ->join('diagnosticos as d', 'pd.id_diagnosticos', '=', 'd.id')
      ->where('pd.id_prestacion', $id_prestacion)
      ->where('pd.id_evaluacion', $id_evaluacion)
      ->where('pd.estado', 1)
      ->where('pd.id_interconsulta', 0)
      ->whereNull('pd.id_evolucion')
      ->whereNull('pd.id_intraoperatorio')
      ->whereNull('pd.id_hoja_recuperacion')
      ->where('pd.id_consejeria', 0)
      ->select(
        'p.codigo as codigo_procedimiento',
        'p.descripcion as nombre_procedimiento',
        DB::raw('1::int2 as cantidad'),
        'd.codigo as codigo_diagnostico'
      )
      ->get();


    return $data;
  }

  /**
   * Obtener procedimientos por ID Orden de Imagenes o Radiologia
   */
  public function GetProcedimientosByIdRadiologia($id_radiologia)
  {
    $data = DB::table('prestacion_procedimientos as t1')
      ->join('procedimientos as t2', 't1.id_procedimiento', '=', 't2.id')
      ->leftJoin('diagnosticos as t3', 't1.id_diagnostico', '=', 't3.id')
      ->join('radnivelatenciones as rna', 't1.id_rua', '=', 'rna.id')
      ->where('t1.id_lab_rad', $id_radiologia)
      ->where('t1.tipo_procedimiento', 3)
      ->where('t1.id_estado_reg', 1)
      ->orderBy('t1.id', 'asc')
      ->select(
        't1.id_lab_rad as id_radiologia',
        't2.codigo as examen_codigo_original',
        't2.descripcion as examen_nombre_original',
        't1.cantidad',
        't1.observacion',
        't3.codigo as diagnostico_codigo',
        't3.nombre as diagnostico_nombre',
        't1.comparativo_radio',
        't1.lado_radio',
        't1.radiologia_portatil',
        'rna.codigo_rad as examen_codigo_personalizado',
        'rna.descripcion_corta as examen_nombre_personalizado'
      )
      ->get();

    return $data;
  }
  public function procedimientosExamenPatologiaCqx(){
    $cad = "select id,codigo,descripcion from procedimientos where codigo_seccion='1.2' and estado=1";
    $data = DB::select($cad);
    return $data; 
  }

  /**
   * Obtiene los procedimientos de una prestaci�n (Emergencia) filtrados por evaluaci�n,
   * interconsulta y estado. Une datos de procedimientos y diagn�sticos relacionados.
   *
   * @param  int  $id_prestacion  ID de la prestaci�n a consultar.
   * @return  \Illuminate\Support\Collection  Lista de procedimientos encontrados.
   */
  public function GetProcedimientosAtencionEmergencia($id_prestacion)
  {
    $result = DB::table('prestacion_procedimientos as t1')
      ->select(
          't1.id as cod',
          't2.codigo',
          't2.descripcion as nombre',
          't1.cantidad',
          't3.id_diagnosticos as id_diagnostico',
          't1.id_procedimiento',
          't1.tiempo',
          't1.enfermeria',
          't1.id_consejeria as pp_id_consejeria',
          't3.id_consejeria as pd_id_consejeria',
          't1.id_evaluacion'
      )
      ->join('procedimientos as t2', 't1.id_procedimiento', '=', 't2.id')
      ->join('procedimientos_diagnoticos as t3', function($join) {
        $join->on('t3.id_prestacion', '=', 't1.id_prestacion')
          ->on('t3.id_procedimientos', '=', 't1.id_procedimiento');
      })
      ->where('t1.id_prestacion', $id_prestacion)
      ->where('t1.id_evaluacion', 0)
      ->where('t1.tipo_procedimiento', 1)
      ->where('t1.id_interconsulta', 0)
      ->where('t1.id_consejeria', 0)
      ->whereRaw('COALESCE(t1.enfermeria, 0) = 0')
      ->where('t1.id_estado_reg', 1)
      ->where('t3.id_evaluacion', 0)
      ->where('t3.id_interconsulta', 0)
      ->where('t3.estado', 1)
      ->orderBy('t1.id', 'asc')
      ->get();

    return $result;
  }

  public function GetProcedimientosHospitalizacionEvolucion($id_evolucion)
  {
    $data = DB::table('prestacion_procedimientos as pp')
      ->join('procedimientos as p', 'pp.id_procedimiento', '=', 'p.id')
      ->join('diagnosticos as d', 'pp.id_diagnostico', '=', 'd.id')
      ->select(
          'pp.id as id_prestacion_procedimiento',
          'pp.id_prestacion',
          'pp.id_evaluacion',
          'p.codigo as codigo_procedimiento',
          'p.descripcion as descripcion_procedimiento',
          'pp.cantidad',
          'd.codigo as codigo_diagnostico',
          'pp.id_diagnostico',
          'pp.id_procedimiento'
      )
      ->where('pp.id_evaluacion', $id_evolucion)
      ->where('pp.id_estado_reg', 1)
      ->where('pp.tipo_procedimiento', 1)
      ->where('pp.id_consejeria', 0)
      ->orderBy('pp.id')
      ->get();

    return $data;
  }
}