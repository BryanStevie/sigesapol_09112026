<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use DB;
use App;
use Illuminate\Support\Facades\Route;
use Auth;
use Carbon\Carbon;
use App\Models\Farmacia;
use App\Models\CqTipoProfesionalInterviene;
use App\Models\CqxTipoProfesional;
use App\Models\CqxTipoAnestesia;
use App\Models\IntraOperatorio;
use App\Models\Establecimiento;
use App\Models\CqxProfesional;
use App\Models\CqxDiagnosticoNic;
use App\Models\Upss;
use App\Models\Hospitalizacione;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Archive7z\Archive7z;
use App\Models\CqxIntervencion;
use App\Models\Cita;
use Exception;

class IntraOperatorioController extends Controller
{
  function __construct()
  {
    $this->middleware('auth');
  }

  public function registrar_intraoperario_info_ajax(Request $request)
  {
    try {
      $modelfarma = new Farmacia;
      $model_establecimiento = new Establecimiento;
      $model_tipo_profesionalInterviene = new CqxTipoProfesional();
      $model_tipo_anestesia = new CqxTipoAnestesia();
      $model_intraoperatorio = new IntraOperatorio;
      $model_diagnosticos_enfermeria = new CqxDiagnosticoNic();
      $model_siteds = new \App\Models\Siteds;

      $asegurado = $request['asegurado']; // Datos generales
      $farmacia = $modelfarma->GetFarmaByUser(Auth::user()->id);
      $id_ipress = $farmacia->id_establecimiento;
      $establecimiento = $model_establecimiento->GetById($id_ipress);
      $nivel = $id_ipress == 1 ? 2 : $establecimiento[0]->nivel;
      $ipress = $establecimiento[0];
      $tipo_profesional_interviene_cirujano = $model_tipo_profesionalInterviene::select('id', 'descripcion')->where('estado', 1)->where('flag_profesional', 1)->get();
      $tipo_profesional_interviene_anestesiologo = $model_tipo_profesionalInterviene::select('id', 'descripcion')->where('estado', 1)->where('flag_profesional', 2)->get();
      $tipo_instrumentista = $model_tipo_profesionalInterviene::select('id', 'descripcion')->where('estado', 1)->where('flag_profesional', 3)->get();
      $tipo_anestesia = $model_tipo_anestesia::select('id', 'descripcion')->where('estado', 1)->get();

      if (!empty($asegurado['id_prestacion'])) {
        $diagnosticos = $model_intraoperatorio->diagnosticos_pacientes_prestacion($asegurado['id_prestacion'], $asegurado['id_evolucion']);
      } else {
        $diagnosticos = DB::table('receta_diagnosticos as rd')
          ->leftJoin('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
          ->leftJoin('tipo_diagnosticos as td', 'td.id', '=', 'rd.id_tipo_diagnostico')
          ->select('d.nombre', 'd.codigo', 'td.descripcion as tipo')
          ->where('rd.id_intervencion', '=', $asegurado['id_intervencion'])
          ->get();
      }
      $cirujanos = $model_intraoperatorio->profesiones(0, $asegurado['id_intervencion']); //CIRUJANOS
      $anetesiologos = $model_intraoperatorio->profesiones(1, $asegurado['id_intervencion']); //ANESTESIOLOGOS
      $intervencion_enfermeria = $model_diagnosticos_enfermeria->intervencion_enfermeria();
      $diagnosticos_enfermeria = $model_diagnosticos_enfermeria::select('id', 'descripcion')->where('flag', 0)->where('estado', 1)->get();
      $procedimientos_intervencion = $model_intraoperatorio->procedimientos_intervencion($asegurado['id_intervencion']); //PROCEDIMIENTOS DE INTERVENCION

      $hospitalizacion = "";
      $model_intervencion = new CqxIntervencion();
      $datos_intervencion = $model_intervencion::select(
        'cqx_intervenciones.id_prestacion',
        'cqx_intervenciones.id_hospitalizacion',
        'cs.nombre as sala',
        'cqx_intervenciones.id_consultorio',
        'cqx_intervenciones.id_tipo_anestesia',
        'a.cip',
        'a.fecha_nac',
        'a.grado',
        'a.situacion_paciente',
        'a.sexo',
        'a.domicilio',
        'a.nro_doc_ident',
        'a.tipo_doc_ident',
        DB::raw("EXTRACT(YEAR FROM AGE(CURRENT_DATE, TO_DATE(a.fecha_nac, 'DD/MM/YYYY'))) AS edadafiliado"),
        'ts.descripcion_corta as tipo_sangre',
        'es_nuevo_admision_hospitalizacion',
        'es_traslado_hospitalizacion_acqx',
        'cqx_intervenciones.id_consultorio as id_servicio',
        'cqx_intervenciones.id_interconsulta'
      )
        ->leftJoin('cq_salas as cs', 'cqx_intervenciones.id_sala_intervencion', '=', 'cs.id')
        ->leftJoin('asegurados as a', 'cqx_intervenciones.id_asegurado', '=', 'a.id')
        ->leftJoin('tipo_sangres as ts', 'a.id_tipo_sangre', '=', 'ts.id')
        ->where('cqx_intervenciones.id', $asegurado['id_intervencion'])->first();

      $citas = DB::table('citas as c')
        ->join('prestaciones as p', 'c.id', '=', 'p.id_cita')
        ->join('establecimientos as e', 'p.id_establecimiento', '=', 'e.id')
        ->where('p.id', $datos_intervencion->id_prestacion)->select('c.parentesco', 'e.codigo as codigo_ipress', 'e.nombre as establecimiento')->first();

      $asegurado['codigo_ipress'] = !empty($citas->codigo_ipress) ? $citas->codigo_ipress : '';
      $asegurado['establecimiento'] = !empty($citas->establecimiento) ? $citas->establecimiento : '';
      $asegurado['parentesco'] = !empty($citas->parentesco) ? $citas->parentesco : '';
      $asegurado['edadafiliado'] = $datos_intervencion->edadafiliado;
      $asegurado['cip'] = $datos_intervencion->cip;
      $asegurado['fecha_nac'] = $datos_intervencion->fecha_nac;
      $asegurado['grado_asegurado'] = $datos_intervencion->grado;
      $asegurado['situacion_paciente'] = $datos_intervencion->situacion_paciente;
      $asegurado['sexo'] = $datos_intervencion->sexo;
      $asegurado['tipo_sangre'] = $datos_intervencion->tipo_sangre;
      $asegurado['domicilio'] = $datos_intervencion->domicilio;
      $asegurado['nro_doc_ident'] = $datos_intervencion->nro_doc_ident;
      $asegurado['tipo_doc_ident'] = $datos_intervencion->tipo_doc_ident;

      $id_hospitalizacion = !empty($datos_intervencion->id_hospitalizacion) ? $datos_intervencion->id_hospitalizacion : 0;
      if (!empty($id_hospitalizacion)) {
        $hospitalizacion = DB::table('hospitalizaciones as h')
          ->leftJoin('establecimientos as e', 'h.id_establecimiento', '=', 'e.id')
          ->leftJoin('tipo_parentescos as t', 't.id', '=', 'h.id_tipo_acompaniante')
          ->leftJoin('camas as c', 'h.id_cama', '=', 'c.id')
          ->leftJoin('upsses as u', 'h.id_upss_origen', '=', 'u.id')
          ->leftJoin('consultorios as c2', 'h.id_servicio_destino', '=', 'c2.id')
          ->select(
            'h.*',
            't.descripcion',
            'c2.id as id_servicio',
            'c2.nombre as servicio_destino',
            'c.nombre as cama',
            'h.tipo_doc_acompaniante',
            'h.nro_doc_acompaniante',
            'h.nombre_acompaniante',
            'h.telefono_acompaniante',
            'u.codigo as codigo_upss',
            'u.descripcion_upss',
            'e.nombre as establecimiento',
            'e.codigo as codigo_ipress'
          )
          ->where('h.id', $id_hospitalizacion)->first();
        $asegurado['parentesco'] = !empty($hospitalizacion->descripcion) ? $hospitalizacion->descripcion : '';
        $asegurado['codigo_ipress'] = !empty($hospitalizacion->codigo_ipress) ? $hospitalizacion->codigo_ipress : '';
        $asegurado['establecimiento'] = !empty($hospitalizacion->establecimiento) ? $hospitalizacion->establecimiento : '';
        $id_servicio = !empty($datos_intervencion->id_consultorio) ? $datos_intervencion->id_consultorio : (isset($hospitalizacion->id_servicio_encargado) ? $hospitalizacion->id_servicio_encargado : '');
      }

      $datos_upss_ambulatorio = "";
      if ($asegurado['id_tipo_atencion'] == "1" || $datos_intervencion->es_nuevo_admision_hospitalizacion || $datos_intervencion->es_traslado_hospitalizacion_acqx) { //AMBULATORIO - es_nuevo_admision_hospitalizacion viene de hospi nuevo admision clinica de dia origen ambulatorio
        $id_servicio = !empty($datos_intervencion->id_consultorio) ? $datos_intervencion->id_consultorio : (isset($hospitalizacion->id_servicio_encargado) ? $hospitalizacion->id_servicio_encargado : '');
        if (!$datos_intervencion->es_nuevo_admision_hospitalizacion) {
          $datos_upss_ambulatorio = DB::table('prestaciones as p')
            ->join('upsses as up', 'p.codigo_upss', '=', 'up.codigo')
            ->join('consultorios as c', 'up.id_consultorio', '=', 'c.id')
            ->select('up.codigo', 'up.descripcion_upss')
            ->where('p.id', $datos_intervencion->id_prestacion)->first();
        }
      } else if ($asegurado['id_tipo_atencion'] == "2") {
        $datos_servicio = DB::table('hospitalizacion_ordenes as ho')
          ->join('prestaciones as p', 'ho.id_ext', '=', 'p.id_ext')
          ->join('upsses as u', 'ho.id_upss_traslado', '=', 'u.id')
          ->join('consultorios as c', 'u.id_consultorio', '=', 'c.id')
          ->select(
            'c.id as id_servicio',
            'u.descripcion_upss',
            'u.codigo',
            'c.nombre as sericio',
            'ho.id_upss_responsable',
            'ho.id_sala',
            'ho.id_establecimiento',
            'ho.fecha_derivacion',
            'c.nombre as servicio'
          )
          ->where('p.id', $datos_intervencion->id_prestacion)
          ->first();
        $id_servicio = isset($datos_servicio->id_servicio) ? $datos_servicio->id_servicio : 0;
      }
      $model_consultorio = new \App\Models\Consultorio();
      $idservicio = !empty($id_servicio) ? $id_servicio : $asegurado['id_servicio'];
      $servicio = $model_consultorio->GetNombreById($idservicio);

      ///////////////////////COD SITEDS/////////////////
      $id_atencion = isset($asegurado['id_hospitalizacion']) ? $asegurado['id_hospitalizacion'] : '';
      $id_tipo_atencion = 3; //ID DEL TIPO DE ATENCION
      $tipo_atencion_ultima = "";
      $codigo_siteds = "";
      if (!empty($id_atencion)) {
        $tipo_atencion_ultima = $model_intraoperatorio->getUltimaAtencionXAtencion($id_atencion, $id_tipo_atencion);
      }
      if (!empty($tipo_atencion_ultima)) {
        $codigo_siteds = $model_siteds->getUltimoCodigoSitedsEnElDia($id_atencion, 3, $tipo_atencion_ultima->tipo_atencion_ultima);
      }
      ///////////////////////FIN COD SITEDS/////////////////

      return response()->json([
        'success' => true,
        'message' => 'Datos obtenidos correctamente.',
        'data' => [
          'asegurado' => $asegurado,
          'nivel' => $nivel,
          'tipo_profesional_interviene_cirujano' => $tipo_profesional_interviene_cirujano,
          'tipo_profesional_interviene_anestesiologo' => $tipo_profesional_interviene_anestesiologo,
          'tipo_instrumentista' => $tipo_instrumentista,
          'tipo_anestesia' => $tipo_anestesia,
          'diagnosticos' => $diagnosticos,
          'cirujanos' => $cirujanos,
          'anetesiologos' => $anetesiologos,
          'diagnosticos_enfermeria' => $diagnosticos_enfermeria,
          'intervencion_enfermeria' => $intervencion_enfermeria,
          'procedimientos_intervencion' => $procedimientos_intervencion,
          'id_ipress' => $id_ipress,
          'id_atencion' => $id_atencion,
          'upps' => isset($upps) ? $upps : null,
          'servicio' => $servicio,
          'ipress' => $ipress,
          'datos_servicio' => isset($datos_servicio) ? $datos_servicio : null,
          'datos_upss_ambulatorio' => $datos_upss_ambulatorio,
          'hospitalizacion' => $hospitalizacion,
          'datos_intervencion' => $datos_intervencion
        ]
      ]);
    } catch (Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Ocurrió un error al obtener la información.',
        'error' => config('app.debug') ? $e->getMessage() : null
      ], 500);
    }
  }

  public function registrar_intraoperario(Request $request)
  {
    if (!Auth::check()) {
      return redirect('/auth/login');
    }
    //return view('frontend.centro_quirurgico.intraoperatorio.detalle_intraoperario', compact('intraoperatorio'));
    $modelfarma = new Farmacia;
    $model_establecimiento = new Establecimiento;
    $model_tipo_profesionalInterviene = new CqxTipoProfesional();
    $model_tipo_anestesia = new CqxTipoAnestesia();
    $model_intraoperatorio = new IntraOperatorio;
    $model_diagnosticos_enfermeria = new CqxDiagnosticoNic();
    $model_siteds = new \App\Models\Siteds;

    $asegurado = $request['asegurado']; // Datos generales
    $farmacia = $modelfarma->GetFarmaByUser(Auth::user()->id);
    $id_ipress = $farmacia->id_establecimiento;
    $establecimiento = $model_establecimiento->GetById($id_ipress);
    $nivel = $id_ipress == 1 ? 2 : $establecimiento[0]->nivel;
    $ipress = $establecimiento[0];

    $tipo_profesional_interviene_cirujano = $model_tipo_profesionalInterviene::select('id', 'descripcion')->where('estado', 1)->where('flag_profesional', 1)->get();
    $tipo_profesional_interviene_anestesiologo = $model_tipo_profesionalInterviene::select('id', 'descripcion')->where('estado', 1)->where('flag_profesional', 2)->get();
    $tipo_instrumentista = $model_tipo_profesionalInterviene::select('id', 'descripcion')->where('estado', 1)->where('flag_profesional', 3)->get();
    $tipo_anestesia = $model_tipo_anestesia::select('id', 'descripcion')->where('estado', 1)->get();
    if (!empty($asegurado['id_prestacion'])) {
      $diagnosticos = $model_intraoperatorio->diagnosticos_pacientes_prestacion($asegurado['id_prestacion'], $asegurado['id_evolucion']);
    } else {
      $diagnosticos = DB::table('receta_diagnosticos as rd')
        ->leftJoin('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
        ->leftJoin('tipo_diagnosticos as td', 'td.id', '=', 'rd.id_tipo_diagnostico')
        ->select('d.nombre', 'd.codigo', 'td.descripcion as tipo')
        ->where('rd.id_intervencion', '=', $asegurado['id_intervencion'])
        ->get();
    }
    $cirujanos = $model_intraoperatorio->profesiones(0, $asegurado['id_intervencion']); //CIRUJANOS
    $anetesiologos = $model_intraoperatorio->profesiones(1, $asegurado['id_intervencion']); //ANESTESIOLOGOS
    $intervencion_enfermeria = $model_diagnosticos_enfermeria->intervencion_enfermeria();
    $diagnosticos_enfermeria = $model_diagnosticos_enfermeria::select('id', 'descripcion')->where('flag', 0)->where('estado', 1)->get();
    $procedimientos_intervencion = $model_intraoperatorio->procedimientos_intervencion($asegurado['id_intervencion']); //PROCEDIMIENTOS DE INTERVENCION
    //$upps=Upss::select('codigo','descripcion_upss')->where('codigo','040000')->first();
    ///////////////////////COD SITEDS/////////////////
    $id_atencion = isset($asegurado['id_hospitalizacion']) ? $asegurado['id_hospitalizacion'] : '';
    //$nombre_servicio = !empty($asegurado['servicio_hospitalizacion']) ? $asegurado['servicio_hospitalizacion'] : $asegurado['servicio'];
    $id_tipo_atencion = 3; //ID DEL TIPO DE ATENCION
    $tipo_atencion_ultima = "";
    $codigo_siteds = "";
    if (!empty($id_atencion)) {
      $tipo_atencion_ultima = $model_intraoperatorio->getUltimaAtencionXAtencion($id_atencion, $id_tipo_atencion);
    }
    if (!empty($tipo_atencion_ultima)) {
      $codigo_siteds = $model_siteds->getUltimoCodigoSitedsEnElDia($id_atencion, 3, $tipo_atencion_ultima->tipo_atencion_ultima);
    }
    ///////////////////////FIN COD SITEDS/////////////////
    // if(!empty($asegurado['id_prestacion'])){
    //   $prestacion_tipo_atencion = DB::table('prestaciones')->select('prestaciones.id_tipo_atencion')->where('prestaciones.id','=',$asegurado['id_prestacion'])->first();
    // }

    $hospitalizacion = "";
    $model_intervencion = new CqxIntervencion();
    $datos_intervencion = $model_intervencion::select(
      'cqx_intervenciones.id_prestacion',
      'cqx_intervenciones.id_hospitalizacion',
      'cs.nombre as sala',
      'cqx_intervenciones.id_consultorio',
      'cqx_intervenciones.id_tipo_anestesia',
      'a.cip',
      'a.fecha_nac',
      'a.grado',
      'a.situacion_paciente',
      'a.sexo',
      'a.domicilio',
      'a.nro_doc_ident',
      'a.tipo_doc_ident',
      DB::raw("EXTRACT(YEAR FROM AGE(CURRENT_DATE, TO_DATE(a.fecha_nac, 'DD/MM/YYYY'))) AS edadafiliado"),
      'ts.descripcion_corta as tipo_sangre',
      'es_nuevo_admision_hospitalizacion',
      'es_traslado_hospitalizacion_acqx',
      'cqx_intervenciones.id_consultorio as id_servicio',
      'cqx_intervenciones.id_interconsulta'
    )
      ->leftJoin('cq_salas as cs', 'cqx_intervenciones.id_sala_intervencion', '=', 'cs.id')
      ->leftJoin('asegurados as a', 'cqx_intervenciones.id_asegurado', '=', 'a.id')
      ->leftJoin('tipo_sangres as ts', 'a.id_tipo_sangre', '=', 'ts.id')
      ->where('cqx_intervenciones.id', $asegurado['id_intervencion'])->first();

    $citas = DB::table('citas as c')
      ->join('prestaciones as p', 'c.id', '=', 'p.id_cita')
      ->join('establecimientos as e', 'p.id_establecimiento', '=', 'e.id')
      ->where('p.id', $datos_intervencion->id_prestacion)->select('c.parentesco', 'e.codigo as codigo_ipress', 'e.nombre as establecimiento')->first();


    $asegurado['codigo_ipress'] = !empty($citas->codigo_ipress) ? $citas->codigo_ipress : '';
    $asegurado['establecimiento'] = !empty($citas->establecimiento) ? $citas->establecimiento : '';
    $asegurado['parentesco'] = !empty($citas->parentesco) ? $citas->parentesco : '';
    $asegurado['edadafiliado'] = $datos_intervencion->edadafiliado;
    $asegurado['cip'] = $datos_intervencion->cip;
    $asegurado['fecha_nac'] = $datos_intervencion->fecha_nac;
    $asegurado['grado_asegurado'] = $datos_intervencion->grado;
    $asegurado['situacion_paciente'] = $datos_intervencion->situacion_paciente;
    $asegurado['sexo'] = $datos_intervencion->sexo;
    $asegurado['tipo_sangre'] = $datos_intervencion->tipo_sangre;
    $asegurado['domicilio'] = $datos_intervencion->domicilio;
    $asegurado['nro_doc_ident'] = $datos_intervencion->nro_doc_ident;
    $asegurado['tipo_doc_ident'] = $datos_intervencion->tipo_doc_ident;


    $id_hospitalizacion = !empty($datos_intervencion->id_hospitalizacion) ? $datos_intervencion->id_hospitalizacion : 0;
    if (!empty($id_hospitalizacion)) {
      $hospitalizacion = DB::table('hospitalizaciones as h')
        ->leftJoin('establecimientos as e', 'h.id_establecimiento', '=', 'e.id')
        ->leftJoin('tipo_parentescos as t', 't.id', '=', 'h.id_tipo_acompaniante')
        ->leftJoin('camas as c', 'h.id_cama', '=', 'c.id')
        ->leftJoin('upsses as u', 'h.id_upss_origen', '=', 'u.id')
        ->leftJoin('consultorios as c2', 'h.id_servicio_destino', '=', 'c2.id')
        ->select(
          'h.*',
          't.descripcion',
          'c2.id as id_servicio',
          'c2.nombre as servicio_destino',
          'c.nombre as cama',
          'h.tipo_doc_acompaniante',
          'h.nro_doc_acompaniante',
          'h.nombre_acompaniante',
          'h.telefono_acompaniante',
          'u.codigo as codigo_upss',
          'u.descripcion_upss',
          'e.nombre as establecimiento',
          'e.codigo as codigo_ipress'
        )
        ->where('h.id', $id_hospitalizacion)->first();
      $asegurado['parentesco'] = !empty($hospitalizacion->descripcion) ? $hospitalizacion->descripcion : '';
      $asegurado['codigo_ipress'] = !empty($hospitalizacion->codigo_ipress) ? $hospitalizacion->codigo_ipress : '';
      $asegurado['establecimiento'] = !empty($hospitalizacion->establecimiento) ? $hospitalizacion->establecimiento : '';
      $id_servicio = !empty($datos_intervencion->id_consultorio) ? $datos_intervencion->id_consultorio : (isset($hospitalizacion->id_servicio_encargado) ? $hospitalizacion->id_servicio_encargado : '');
    }
    $datos_upss_ambulatorio = "";
    if ($asegurado['id_tipo_atencion'] == "1" || $datos_intervencion->es_nuevo_admision_hospitalizacion || $datos_intervencion->es_traslado_hospitalizacion_acqx) { //AMBULATORIO - es_nuevo_admision_hospitalizacion viene de hospi nuevo admision clinica de dia origen ambulatorio

      $id_servicio = !empty($datos_intervencion->id_consultorio) ? $datos_intervencion->id_consultorio : (isset($hospitalizacion->id_servicio_encargado) ? $hospitalizacion->id_servicio_encargado : '');

      if (!$datos_intervencion->es_nuevo_admision_hospitalizacion) {
        $datos_upss_ambulatorio = DB::table('prestaciones as p')
          ->join('upsses as up', 'p.codigo_upss', '=', 'up.codigo')
          ->join('consultorios as c', 'up.id_consultorio', '=', 'c.id')
          ->select('up.codigo', 'up.descripcion_upss')
          ->where('p.id', $datos_intervencion->id_prestacion)->first();
      }
    } else if ($asegurado['id_tipo_atencion'] == "2") {
      $datos_servicio = DB::table('hospitalizacion_ordenes as ho')
        ->join('prestaciones as p', 'ho.id_ext', '=', 'p.id_ext')
        ->join('upsses as u', 'ho.id_upss_traslado', '=', 'u.id')
        ->join('consultorios as c', 'u.id_consultorio', '=', 'c.id')
        ->select(
          'c.id as id_servicio',
          'u.descripcion_upss',
          'u.codigo',
          'c.nombre as sericio',
          'ho.id_upss_responsable',
          'ho.id_sala',
          'ho.id_establecimiento',
          'ho.fecha_derivacion',
          'c.nombre as servicio'
        )
        ->where('p.id', $datos_intervencion->id_prestacion)
        ->first();
      $id_servicio = isset($datos_servicio->id_servicio) ? $datos_servicio->id_servicio : 0;
    }
    $model_consultorio = new \App\Models\Consultorio();
    $idservicio = !empty($id_servicio) ? $id_servicio : $asegurado['id_servicio'];
    $servicio = $model_consultorio->GetNombreById($idservicio);
    return view('frontend.centro_quirurgico.intraoperatorio.form_intraoperario', compact('asegurado', 'nivel', 'tipo_profesional_interviene_cirujano', 'tipo_profesional_interviene_anestesiologo', 'tipo_instrumentista', 'tipo_anestesia', 'diagnosticos', 'cirujanos', 'anetesiologos', 'diagnosticos_enfermeria', 'intervencion_enfermeria', 'procedimientos_intervencion', 'codigo_siteds', 'id_ipress', 'upps', 'id_atencion', 'servicio', 'ipress', 'datos_servicio', 'datos_upss_ambulatorio', 'hospitalizacion', 'datos_intervencion'));
  }
  public function guardar_intraoperario(Request $request)
  {
    if (!Auth::check()) {
      return redirect('/auth/login');
    }
    // return response()->json(['success' => true,'id_intraoperatorio'=>1]);
    DB::beginTransaction();
    try {
      // dd($request['enfermeriauno']);
      $rules = [
        // 'cod_siteds_susalud' => 'required',
        'fecha_registro' => 'required|date', // Campo requerido y debe ser una fecha válida
        'hora_registro' => 'required|date_format:H:i', // Campo requerido con formato de hora específico (HH:MM)
        'recepcion_paciente_descripcion' => ['required', 'regex:/^[^\s][a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/', 'max:255'],
        'recepcion_paciente_tiempo' => ['required', 'regex:/^([01][0-9]|2[0-3]):([0-5][0-9])$/'],
        'anestesia_inicio' => ['required', 'regex:/^([01][0-9]|2[0-3]):([0-5][0-9])$/'],
        'cirugia_inicio' => ['required', 'regex:/^([01][0-9]|2[0-3]):([0-5][0-9])$/'],
        // 'inicio_paciente' => ['required', 'regex:/^([01][0-9]|2[0-3]):([0-5][0-9])$/'],
        // 'hora_llegada_urpa' => ['required', 'regex:/^([01][0-9]|2[0-3]):([0-5][0-9])$/'],
        // 'tipo_anestesia' => ['required'],
        // 'anestesia_termino' => ['required', 'regex:/^([01][0-9]|2[0-3]):([0-5][0-9])$/'],
        // 'cirugia_termino' => ['required', 'regex:/^([01][0-9]|2[0-3]):([0-5][0-9])$/'],
        // 'tipo_real' => ['required'],
        'salida_paciente' => ['required', 'regex:/^([01][0-9]|2[0-3]):([0-5][0-9])$/'],
        // 'nombre_cirujano' =>'required|array',
        // 'tipo_profesional_cirujano' => 'required|array',
        // 'nombre_anestesiologo' =>'required|array',
        // 'tipo_profesional_anestesiologo' =>'required|array',
        // 'nombre_enfermeria_instrumentista' =>'required|array',
        // 'tipo_instrumentista' =>'required|array',
        // 'nombre_procedimiento' => 'required|array',
        // 'procedimiento_cantidad' =>'required|array', 
        // 'miSelect' =>'required|array',
        // 'diagnostico_estado' => 'required|array|min:1',
        // 'nic_estado' => 'required|array',
        // 'balance_ingreso' => 'required|array',
        // 'balance_egreso' => 'required|array',
        // 'tareapeutica' => 'required|array',
        // 'enfermeriauno' => 'required|array',
        // 'enfermeriados' => 'required|array',
        // 'enfermeriatres' => 'required|array',  
        'anestesia_termino' => ['required', 'regex:/^([01][0-9]|2[0-3]):([0-5][0-9])$/'],
        'cirugia_termino' => ['required', 'regex:/^([01][0-9]|2[0-3]):([0-5][0-9])$/'],
      ];

      $messages = [
        'recepcion_paciente_descripcion.required' => 'El campo "recepción del paciente" es obligatorio.',
        'recepcion_paciente_descripcion.regex' => 'El campo "recepción del paciente" no puede comenzar con un espacio y solo puede contener letras y espacios.',
        'recepcion_paciente_descripcion.max' => 'El campo "recepción del paciente" longitud máximo 255.',
        'recepcion_paciente_tiempo.required' => 'El campo "tiempo de la recepción del paciente" es obligatorio.',
        // 'cod_siteds_susalud.required' => 'El campo "COD.SITEDS SUSALUD" es obligatorio.',
      ];
      // $nombre_cirujano = $request->input('nombre_cirujano', []);
      // foreach ($nombre_cirujano as $key => $value) {
      //   $rules['nombre_cirujano.' . $key] = 'required';
      // }
      // $tipo_profesional_cirujano = $request->input('tipo_profesional_cirujano', []);
      // foreach ($tipo_profesional_cirujano as $key => $value) {
      //   $rules['tipo_profesional_cirujano.' . $key] = 'required';
      // }
      // $nombre_anestesiologo = $request->input('nombre_anestesiologo', []);
      // foreach ($nombre_anestesiologo as $key => $value) {
      //   $rules['nombre_anestesiologo.' . $key] = 'required';
      // }
      // $tipo_profesional_anestesiologo = $request->input('tipo_profesional_anestesiologo', []);
      // foreach ($tipo_profesional_anestesiologo as $key => $value) {
      //   $rules['tipo_profesional_anestesiologo.' . $key] = 'required';
      // }
      // $nombre_enfermeria_instrumentista = $request->input('nombre_enfermeria_instrumentista', []);
      // foreach ($nombre_enfermeria_instrumentista as $key => $value) {
      //   $rules['nombre_enfermeria_instrumentista.' . $key] = 'required';
      // }
      // $tipo_instrumentista = $request->input('tipo_instrumentista', []);
      // foreach ($tipo_instrumentista as $key => $value) {
      //   $rules['tipo_instrumentista.' . $key] = 'required';
      // }
      // $nombre_procedimiento = $request->input('nombre_procedimiento', []);
      // foreach ($nombre_procedimiento as $key => $value) {
      //   $rules['nombre_procedimiento.' . $key] = 'required';
      // }
      // $procedimiento_cantidad = $request->input('procedimiento_cantidad', []);
      // foreach ($procedimiento_cantidad as $key => $value) {
      //   $rules['procedimiento_cantidad.' . $key] = 'required';
      // }
      // $miSelect = $request->input('miSelect', []);
      // foreach ($miSelect as $key => $value) {
      //   $rules['miSelect.' . $key] = 'required';
      // }
      // $nic_estado = $request->input('nic_estado', []);
      // foreach ($nic_estado as $padre_id => $hijos) {
      //   $rules['nic_estado.' . $padre_id] = 'required|array|min:1';
      // }
      // $enfermeriauno = $request->input('enfermeriauno', []);
      // foreach ($enfermeriauno as $key => $value) {
      //   $rules['enfermeriauno.' . $key] = 'required';
      // }
      // $enfermeriados = $request->input('enfermeriados', []);
      // foreach ($enfermeriados as $key => $value) {
      //   $rules['enfermeriados.' . $key] = 'required';
      // }
      // $enfermeriatres = $request->input('enfermeriatres', []);
      // foreach ($enfermeriatres as $key => $value) {
      //   $rules['enfermeriatres.' . $key] = 'required';
      // }

      // $balance_ingreso = $request->input('balance_ingreso', []);
      // foreach ($balance_ingreso as $key => $value) {
      //   $rules['balance_ingreso.' . $key] = 'required';
      // }
      // $balance_egreso = $request->input('balance_egreso', []);
      // foreach ($balance_egreso as $key => $value) {
      //   $rules['balance_egreso.' . $key] = 'required';
      // }
      // $tareapeutica = $request->input('tareapeutica', []);
      // foreach ($tareapeutica as $key => $value) {
      //   $rules['tareapeutica.' . $key] = 'required';
      // }

      $validator = Validator::make($request->all(), $rules, $messages);
      if ($validator->fails()) {
        return response()->json([
          'success' => false,
          'errors' => $validator->errors(),
        ]);
      }
      $modeloIntraOperatorio = new IntraOperatorio();
      $id_intraoperatorio = $modeloIntraOperatorio->guardar($request->all(), Auth::user()->id);
      DB::commit();
      return response()->json(['success' => true, 'id_intraoperatorio' => $id_intraoperatorio[0]->sp_insertar_intraoperatorio]);
    } catch (\Exception $e) {
      DB::rollback();
      Log::error('Error al querer guardar datos de INTRAOPERATORIO', [
        'message' => $e->getMessage(),
        'code' => $e->getCode(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString(),
      ]);
    } catch (\Throwable $th) {
      DB::rollback();
      throw $th;
    }
  }

  public function guardar_intraoperario_v2(Request $request)
  {
    DB::beginTransaction();

    try {
      $rules = [
        'fecha_registro' => 'required|date',
        'hora_registro' => 'required|date_format:H:i',
        'recepcion_paciente_descripcion' => ['required', 'regex:/^[^\s][a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/', 'max:255'],
        'recepcion_paciente_tiempo' => ['required', 'regex:/^([01][0-9]|2[0-3]):([0-5][0-9])$/'],
        'anestesia_inicio' => ['required', 'regex:/^([01][0-9]|2[0-3]):([0-5][0-9])$/'],
        'cirugia_inicio' => ['required', 'regex:/^([01][0-9]|2[0-3]):([0-5][0-9])$/'],
        'salida_paciente' => ['required', 'regex:/^([01][0-9]|2[0-3]):([0-5][0-9])$/'],
        'anestesia_termino' => ['required', 'regex:/^([01][0-9]|2[0-3]):([0-5][0-9])$/'],
        'cirugia_termino' => ['required', 'regex:/^([01][0-9]|2[0-3]):([0-5][0-9])$/'],
      ];

      $messages = [
        'recepcion_paciente_descripcion.required' => 'El campo "recepción del paciente" es obligatorio.',
        'recepcion_paciente_descripcion.regex' => 'El campo "recepción del paciente" no puede comenzar con un espacio y solo puede contener letras y espacios.',
        'recepcion_paciente_descripcion.max' => 'El campo "recepción del paciente" longitud máximo 255.',
        'recepcion_paciente_tiempo.required' => 'El campo "tiempo de la recepción del paciente" es obligatorio.',
      ];

      $validator = Validator::make($request->all(), $rules, $messages);

      if ($validator->fails()) {
        DB::rollBack();

        return response()->json([
          'success' => false,
          'type' => 'validation',
          'message' => 'Existen errores de validación.',
          'errors' => $validator->errors(),
        ], 422);
      }

      $modeloIntraOperatorio = new IntraOperatorio();

      $id_intraoperatorio = $modeloIntraOperatorio->guardar(
        $request->all(),
        Auth::user()->id
      );

      DB::commit();

      return response()->json([
        'success' => true,
        'type' => 'success',
        'message' => 'Registro guardado correctamente.',
        'errors' => [],
        'id_intraoperatorio' => $id_intraoperatorio[0]->sp_insertar_intraoperatorio,
      ]);
    } catch (\Throwable $e) {

      DB::rollBack();

      Log::error('Error al querer guardar datos de INTRAOPERATORIO', [
        'message' => $e->getMessage(),
        'code' => $e->getCode(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString(),
      ]);

      return response()->json([
        'success' => false,
        'type' => 'exception',
        'message' => config('app.debug')
          ? $e->getMessage()
          : 'Ha ocurrido un error interno al guardar la información.',
        'errors' => [],
      ], 500);
    }
  }

  public function medicosByEspecialidadConsultorio($nombre, $id_especializacion = "")
  {
    if (!Auth::check()) {
      return redirect('/auth/login');
    }
    $model_intraoperatorio = new IntraOperatorio;
    $id_ipress = Auth::user()->id_establecimiento;
    $medicos = $model_intraoperatorio->medicosByEspecialidadConsultorio($id_especializacion, $nombre, $id_ipress);
    $array = array();
    foreach ($medicos as $leer) {
      $array[] = array(
        "value" => $leer->dni . " - " . $leer->paterno . " " . $leer->materno . " " . $leer->nombre,
        "label" => $leer->dni . " - " . $leer->paterno . " " . $leer->materno . " " . $leer->nombre,
        "id" => $leer->id,
        "colegiatura" => $leer->colegiatura,
        "profesion" => $leer->profesion_nombre,

      );
    }
    return json_encode($array);
  }
  public function motivoAnulacionCirujano(Request $request)
  {
    $profesional = CqxProfesional::find((int) $request['id_medico']);
    if (!empty($profesional)) {
      $profesional->estado = 3;
      $profesional->motivo_anulacion = $request['anulacionMotivo'];
      $profesional->deleted_us = Auth::user()->id;
      $profesional->deleted_at = Carbon::now();
      $profesional->save();
    } else {
      return response()->json(['mensaje' => 'Profesional no encontrado'], 404);
    }
    return json_encode(1);
  }

  public function detalleIntraoperatorio($id_intraoperatorio)
  {
    if (!Auth::check()) {
      return redirect('/auth/login');
    }
    $intraoperatorio = new IntraOperatorio();

    $data = $intraoperatorio->datos_para_firma($id_intraoperatorio);
    if (!$data) {
      return response()->json(['success' => false, 'msg' => 'No hay datos en intraoperatorio por el id:' . $id_intraoperatorio]);
    }
    $datos_paciente = $intraoperatorio->datos_paciente($data->id_intervencion);
    if (!$datos_paciente) {
      return response()->json(['success' => false, 'msg' => 'No hay datos del paciente por el id_intervencion' . $data->id_intervencion]);
    }
    $diagnosticos_enfermeria = $intraoperatorio->diagnosticos_enfermeria($data->id);
    $intervencion_enfermeria = $intraoperatorio->intervencion_enfermeria($data->id);
    $balance_fluidos_enfermeria = $intraoperatorio->balance_fluidos_enfermeria($id_intraoperatorio);
    $recuento_gasas_enfermeria = $intraoperatorio->recuento_gasas_enfermeria($id_intraoperatorio);

    $cirujanos = $intraoperatorio->profesiones_by_id_io(0, $data->id_intervencion); //CIRUJANOS
    $anestesiologos = $intraoperatorio->profesiones_by_id_io(1, $data->id_intervencion); //ANESTESIOLOGOS
    $instrumentistas = $intraoperatorio->profesiones_intrumentista(2, $id_intraoperatorio); //ANESTESIOLOGOS

    return view('frontend.centro_quirurgico.intraoperatorio.detalle_intraoperario', compact('data', 'intraoperatorio', 'diagnosticos_enfermeria', 'intervencion_enfermeria', 'datos_paciente', 'balance_fluidos_enfermeria', 'recuento_gasas_enfermeria', 'cirujanos', 'anestesiologos', 'instrumentistas'));
  }

  public function detalleIntraoperatorio_ajax($id_intraoperatorio)
  {
    $intraoperatorio = new IntraOperatorio();
    $data = $intraoperatorio->datos_para_firma($id_intraoperatorio);

    if (!$data) {
      return response()->json(['success' => false, 'msg' => 'No hay datos en intraoperatorio por el id:' . $id_intraoperatorio]);
    }

    $datos_paciente = $intraoperatorio->datos_paciente($data->id_intervencion);

    if (!$datos_paciente) {
      return response()->json(['success' => false, 'msg' => 'No hay datos del paciente por el id_intervencion' . $data->id_intervencion]);
    }

    $diagnosticos_enfermeria = $intraoperatorio->diagnosticos_enfermeria($data->id);
    $intervencion_enfermeria = $intraoperatorio->intervencion_enfermeria($data->id);

    $balance_fluidos_enfermeria = $intraoperatorio->balance_fluidos_enfermeria($id_intraoperatorio);
    $recuento_gasas_enfermeria = $intraoperatorio->recuento_gasas_enfermeria($id_intraoperatorio);
    $instrumentistas = $intraoperatorio->profesiones_intrumentista(2, $id_intraoperatorio); //ANESTESIOLOGOS

    $cirujanos = $intraoperatorio->profesiones_by_id_io_v2(0, $data->id_intervencion); //CIRUJANOS
    $anestesiologos = $intraoperatorio->profesiones_by_id_io_v2(1, $data->id_intervencion); //ANESTESIOLOGOS

    return response()->json([
      'success' => true,
      'data' => [
        'data' => $data,
        'intraoperatorio' => $intraoperatorio,
        'diagnosticos_enfermeria' => $diagnosticos_enfermeria,
        'intervencion_enfermeria' => $intervencion_enfermeria,
        'datos_paciente' => $datos_paciente,
        'balance_fluidos_enfermeria' => $balance_fluidos_enfermeria,
        'recuento_gasas_enfermeria' => $recuento_gasas_enfermeria,
        'cirujanos' => $cirujanos,
        'anestesiologos' => $anestesiologos,
        'instrumentistas' => $instrumentistas,
        'user_status_token' => Auth::user()->status_token,
      ]
    ]);
  }

  public function getIntraoperatorioByIdIntervencion($id_intervencion)
  {
    if (!Auth::check()) {
      return redirect('/auth/login');
    }

    $intraoperatorio = new IntraOperatorio();
    $esta_admisionado = false;
    $model_intervencion = new CqxIntervencion();
    $id_hospitalizacion = $model_intervencion::select('id_hospitalizacion')->where('id', $id_intervencion)->first();
    if (!empty($id_hospitalizacion->id_hospitalizacion)) {
      $esta_admisionado = true;
    }

    $id_intraoperatorio = "";
    $flag_firma = "";
    $resultado = $intraoperatorio::where('id_intervencion', $id_intervencion)->where("estado", "!=", 0)->first();
    if (!empty($resultado)) {
      $id_intraoperatorio = $resultado->id;
      $flag_firma = $resultado->flag_firma;
    }
    return response()->json([
      'success' => true,
      'id_intraoperatorio' => $id_intraoperatorio,
      'esta_admisionado' => $esta_admisionado,
      'flag_firma' => $flag_firma
    ]);
  }

  /******************FIRMA*****************/
  public function intraoperatorioPDFfirmar($id_intraoperatorio)
  {
    $resultado = array();
    try {
      $path = public_path('tmp' . DIRECTORY_SEPARATOR . 'csv');
      $path_zip = public_path('tmp' . DIRECTORY_SEPARATOR . 'zip');

      if (!is_dir($path)) {
        mkdir($path);
      }
      if (!is_dir($path_zip)) {
        mkdir($path_zip);
        mkdir(public_path('tmp' . DIRECTORY_SEPARATOR . 'zip' . DIRECTORY_SEPARATOR . 'firmados'));
        mkdir(public_path('tmp' . DIRECTORY_SEPARATOR . 'zip' . DIRECTORY_SEPARATOR . 'sin_firma'));
      }
      $csv = "";
      if (Auth::check()):
        $csv .= Auth::user()->id;
      endif;

      $csv .= "_" . date("YmdHis");
      $csv .= "_intraoperatorio";
      Excel::create($csv, function ($excel) use ($id_intraoperatorio) {
        $excel->sheet('GENERAL', function ($sheet) use ($id_intraoperatorio) {
          $variable = [];
          $this->generarPdfParaFirmaV2($id_intraoperatorio);
          array_push($variable, array(url() . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR . 'sin_firma' . DIRECTORY_SEPARATOR . 'intraoperatorio_' . $id_intraoperatorio . '.pdf', url() . DIRECTORY_SEPARATOR . 'upload_firma' . DIRECTORY_SEPARATOR . '?name=cf_intraoperatorio_' . $id_intraoperatorio . '.pdf&id_tipo=intraoperatorio&id_resol=' . $id_intraoperatorio, 'cf_intraoperatorio_' . $id_intraoperatorio . '.pdf', '0', '45', '350', '70', 'Fecha de Firma: <DATE> Firmante: <SIGNER>', url() . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'escudo.jpg', '1', '7'));
          $sheet->fromArray($variable, null, '', false, false);
        });
      })->store('csv', public_path('tmp' . DIRECTORY_SEPARATOR . 'csv'));
      $obj = new Archive7z(public_path('tmp' . DIRECTORY_SEPARATOR . 'zip' . DIRECTORY_SEPARATOR . 'sin_firma' . DIRECTORY_SEPARATOR . $csv . '.7z'));
      $obj->addEntry(public_path(DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR . 'sin_firma' . DIRECTORY_SEPARATOR . 'intraoperatorio_' . $id_intraoperatorio . '.pdf'));

      $resultado["csv"] = $csv;
      $resultado["sw"] = true;
    } catch (\Exception $e) {
      Log::error('ERROR EN INTRAOPERATORIO FIRMA PDF: ' . $e->getMessage(), [
        'exception' => $e,
        'trace' => $e->getTraceAsString(),
      ]);
    }
    return response()->json($resultado);
  }

  public function generarPdfParaFirma($id_intraoperatorio)
  {
    $path = public_path('tmp/pdf');
    if (!is_dir($path)) {
      mkdir($path);
    }
    $path = public_path('tmp/pdf/sin_firma/');
    if (!is_dir($path)) {
      mkdir($path);
    }
    /*************AQUI LO DE INTRAOPERATORIO*************/
    $intraoperatorio = new IntraOperatorio();
    $data = $intraoperatorio->datos_para_firma($id_intraoperatorio);
    if (!$data) {
      return response()->json(['success' => false, 'msg' => 'No hay datos en intraoperatorio por el id:' . $id_intraoperatorio]);
    }
    $datos_paciente = $intraoperatorio->datos_paciente($data->id_intervencion);
    if (!$datos_paciente) {
      return response()->json(['success' => false, 'msg' => 'No hay datos del paciente por el id_intervencion' . $data->id_intervencion]);
    }
    $codigo_cups = "";
    $hopitalizacion = Hospitalizacione::select('hospitalizaciones.id', 'hospitalizaciones.cups_pnp')
      ->join('cqx_intervenciones', 'hospitalizaciones.id', '=', 'cqx_intervenciones.id_hospitalizacion')
      ->where('cqx_intervenciones.id', $data->id_intervencion)
      ->first();
    if (!empty($hopitalizacion->cups_pnp)) {
      $codigo_cups = $hopitalizacion->cups_pnp;
    }
    $diagnosticos_enfermeria = $intraoperatorio->diagnosticos_enfermeria($data->id);
    $intervencion_enfermeria = $intraoperatorio->intervencion_enfermeria($data->id);
    $balance_fluidos_enfermeria = $intraoperatorio->balance_fluidos_enfermeria($id_intraoperatorio);
    $recuento_gasas_enfermeria = $intraoperatorio->recuento_gasas_enfermeria($id_intraoperatorio);

    $cirujanos = $intraoperatorio->profesiones_by_id_io(0, $data->id_intervencion); //CIRUJANOS
    $anestesiologos = $intraoperatorio->profesiones_by_id_io(1, $data->id_intervencion); //ANESTESIOLOGOS
    $instrumentistas = $intraoperatorio->profesiones_intrumentista(2, $id_intraoperatorio); //ANESTESIOLOGOS

    $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/" . date("Y/m/d") . "/cf_intraoperatorio_" . $id_intraoperatorio . ".pdf";
    $codigo_qr = QrCode::format('png')->size(150)->generate("https://sigesapol.dirsapolpnp.gob.pe/" . date("Y/m/d") . "/cf_intraoperatorio_" . $id_intraoperatorio . ".pdf");
    $pdf = \PDF::loadView(
      'frontend.centro_quirurgico.intraoperatorio.pdf_intraoperatorio_firmado',
      compact(
        'url_descarga',
        'codigo_qr',
        'data',
        'diagnosticos_enfermeria',
        'intervencion_enfermeria',
        'datos_paciente',
        'balance_fluidos_enfermeria',
        'recuento_gasas_enfermeria',
        'cirujanos',
        'anestesiologos',
        'instrumentistas',
        'codigo_cups'
      )
    );
    $pdf->getDomPDF()->set_option("enable_php", true);
    $pdf->setPaper('A4', 'portrait');
    file_put_contents($path . "intraoperatorio_" . $id_intraoperatorio . ".pdf", $pdf->output());
  }
  public function generarPdfParaFirmaV2($id_intraoperatorio)
  {
    $path = public_path('tmp/pdf');
    if (!is_dir($path)) {
      mkdir($path);
    }
    $path = public_path('tmp/pdf/sin_firma/');
    if (!is_dir($path)) {
      mkdir($path);
    }
    $intraoperatorio = new IntraOperatorio();

    $data = $intraoperatorio->datos_para_firma($id_intraoperatorio);
    if (!$data) {
      return response()->json(['success' => false, 'msg' => 'No hay datos en intraoperatorio por el id:' . $id_intraoperatorio]);
    }
    $datos_paciente = $intraoperatorio->datos_paciente($data->id_intervencion);
    if (!$datos_paciente) {
      return response()->json(['success' => false, 'msg' => 'No hay datos del paciente por el id_intervencion' . $data->id_intervencion]);
    }
    $diagnosticos_enfermeria = $intraoperatorio->diagnosticos_enfermeria($data->id);
    $intervencion_enfermeria = $intraoperatorio->intervencion_enfermeria($data->id);
    $balance_fluidos_enfermeria = $intraoperatorio->balance_fluidos_enfermeria($id_intraoperatorio);
    $recuento_gasas_enfermeria = $intraoperatorio->recuento_gasas_enfermeria($id_intraoperatorio);

    $cirujanos = $intraoperatorio->profesiones_by_id_io(0, $data->id_intervencion); //CIRUJANOS
    $anestesiologos = $intraoperatorio->profesiones_by_id_io(1, $data->id_intervencion); //ANESTESIOLOGOS
    $instrumentistas = $intraoperatorio->profesiones_intrumentista(2, $id_intraoperatorio); //ANESTESIOLOGOS
    $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/" . date("Y/m/d") . "/cf_intraoperatorio_" . $id_intraoperatorio . ".pdf";
    $codigo_qr = QrCode::format('png')->size(150)->generate("https://sigesapol.dirsapolpnp.gob.pe/" . date("Y/m/d") . "/cf_intraoperatorio_" . $id_intraoperatorio . ".pdf");
    $pdf = \PDF::loadView('frontend.centro_quirurgico.intraoperatorio.pdf_intraoperatorio_firmado_v3', compact(
      'data',
      'intraoperatorio',
      'diagnosticos_enfermeria',
      'intervencion_enfermeria',
      'datos_paciente',
      'balance_fluidos_enfermeria',
      'recuento_gasas_enfermeria',
      'cirujanos',
      'anestesiologos',
      'instrumentistas',
      'url_descarga',
      'codigo_qr'
    ));

    $pdf->getDomPDF()->set_option("enable_php", true);
    $pdf->setPaper('A4', 'portrait');
    file_put_contents($path . "intraoperatorio_" . $id_intraoperatorio . ".pdf", $pdf->output());
  }
  public function postArguments(Request $request)
  {
    $SERVER_PATH = env('REFIRMA_SERVER_PATH');
    $protocol = (env('REFIRMA_PROTOCOL') == 'http') ? "T" : "S";

    if ($request->type == "W") {
      $param = '{
                      "app":"pcx",
                      "mode":"lot-p",
                      "clientId":"' . env('REFIRMA_CLIENT_ID') . '",
                      "clientSecret":"' . env('REFIRMA_CLIENT_SECRET') . '",
                      "idFile":"MyForm",
                      "protocol":"' . $protocol . '",
                      "type":"W",
                      "fileDownloadUrl":"' . $SERVER_PATH . '/tmp/zip/sin_firma/' . $request->documentName . '",
                      "fileDownloadLogoUrl":"' . $SERVER_PATH . '/images/iLogo1.png",
                      "fileDownloadStampUrl":"' . $SERVER_PATH . '/images/logo_sanidad_firma.png",
                      "fileUploadUrl":"' . $SERVER_PATH . '/refirma/upload",
                      "contentFile":"' . $request->documentName . '",
                      "reason":"Soy el autor del documento",
                      "isSignatureVisible":"true",
                      "stampAppearanceId":"0",
                      "pageNumber":"0",
                      "posx":"20",
                      "posy":"5",
                      "fontSize":"7",
                      "dcfilter": ".*FIR.*|.*FAU.*",
                      "signatureLevel":"0",
                      "maxFileSize":"5242880"
                  }';
      echo base64_encode($param);
    }
  }
  public function actualizarFirmaConToken(Request $request)
  {
    $id = intval($request->id_resol);
    $intraoperatorio = IntraOperatorio::where('id', $id)->first();
    if ($intraoperatorio->fecha_firma == "") {
      $intraoperatorio->fecha_firma = date("d-m-Y H:i:s");
      $intraoperatorio->ruta_firma = date("Y/m/d") . "/cf_intraoperatorio_" . $intraoperatorio->id . ".pdf";
      $intraoperatorio->flag_firma = 1;
      $intraoperatorio->save();
    }
    return "ok";
  }
  public function actualizarFirmaSinToken(Request $request)
  {
    $ok = false;
    $id = intval($request->id_resol);
    $intraoperatorio = IntraOperatorio::where('id', $id)->first();
    if ($intraoperatorio->fecha_firma == "") {
      $intraoperatorio->fecha_firma = date("d-m-Y H:i:s");
      $intraoperatorio->ruta_firma = date("Y/m/d") . "/cf_intraoperatorio_" . $intraoperatorio->id . ".pdf";
      $intraoperatorio->flag_firma = 2;
      $intraoperatorio->save();
      $ok = true;
    }
    echo $ok;
  }

  public function pdf_intraoperatorio($id_intraoperatorio)
  {
    if (!Auth::check()):
      return redirect('/auth/login');
    endif;

    $intraoperatorio = new IntraOperatorio();
    $data = $intraoperatorio->datos_para_firma($id_intraoperatorio);
    if (!$data) {
      return response()->json(['success' => false, 'msg' => 'No hay datos en intraoperatorio por el id:' . $id_intraoperatorio]);
    }
    $datos_paciente = $intraoperatorio->datos_paciente($data->id_intervencion);
    if (!$datos_paciente) {
      return response()->json(['success' => false, 'msg' => 'No hay datos del paciente por el id_intervencion' . $data->id_intervencion]);
    }
    $codigo_cups = "";
    $hopitalizacion = Hospitalizacione::select('hospitalizaciones.id', 'hospitalizaciones.cups_pnp')
      ->join('cqx_intervenciones', 'hospitalizaciones.id', '=', 'cqx_intervenciones.id_hospitalizacion')
      ->where('cqx_intervenciones.id', $data->id_intervencion)
      ->first();
    if (!empty($hopitalizacion->cups_pnp)) {
      $codigo_cups = $hopitalizacion->cups_pnp;
    }
    $diagnosticos_enfermeria = $intraoperatorio->diagnosticos_enfermeria($data->id);
    $intervencion_enfermeria = $intraoperatorio->intervencion_enfermeria($data->id);
    $balance_fluidos_enfermeria = $intraoperatorio->balance_fluidos_enfermeria($id_intraoperatorio);
    $recuento_gasas_enfermeria = $intraoperatorio->recuento_gasas_enfermeria($id_intraoperatorio);

    $cirujanos = $intraoperatorio->profesiones_by_id_io(0, $data->id_intervencion); //CIRUJANOS
    $anestesiologos = $intraoperatorio->profesiones_by_id_io(1, $data->id_intervencion); //ANESTESIOLOGOS
    $instrumentistas = $intraoperatorio->profesiones_intrumentista(2, $id_intraoperatorio); //ANESTESIOLOGOS

    $pdf = \PDF::loadView(
      'frontend.centro_quirurgico.intraoperatorio.pdf_intraoperatorio',
      compact(
        'codigo_cups',
        'data',
        'diagnosticos_enfermeria',
        'intervencion_enfermeria',
        'datos_paciente',
        'balance_fluidos_enfermeria',
        'recuento_gasas_enfermeria',
        'cirujanos',
        'anestesiologos',
        'instrumentistas'
      )
    );
    $pdf->getDomPDF()->set_option("enable_php", true);
    $pdf->setPaper('A4', 'portrait');
    return $pdf->stream('intraoperatorio.pdf');
  }

  public function editarIntraOperatorio($id_intraoperatorio)
  {
    $model_tipo_profesionalInterviene = new CqxTipoProfesional();
    $model_tipo_anestesia = new CqxTipoAnestesia();
    $intraoperatorio = new IntraOperatorio();
    $modelfarma = new Farmacia;
    $model_establecimiento = new Establecimiento;

    $intra_operatorio = $intraoperatorio::find($id_intraoperatorio);
    if (empty($intra_operatorio)) {
      return response()->json([
        'success' => false,
        'data' => 'No se encontró la hoja de recuperación con el ID especificado. '
      ]);
    }

    $datos_paciente = $intraoperatorio->datos_form($intra_operatorio->id_intervencion);
    if (!$datos_paciente) {
      return response()->json(['success' => false, 'msg' => 'No hay datos del paciente por el id_intervencion' . $intra_operatorio->id_intervencion]);
    }

    $cirujanos = $intraoperatorio->profesiones(0, $intra_operatorio->id_intervencion); //CIRUJANOS
    $anestesiologos = $intraoperatorio->profesiones(1, $intra_operatorio->id_intervencion); //ANESTESIOLOGOS
    $instrumentistas = $intraoperatorio->profesiones(2, $intra_operatorio->id_intervencion); //INSTRUMENTISTAS
    $procedimientos_intervencion = $intraoperatorio->procedimientos_intervencion($intra_operatorio->id_intervencion);
    $procedimientos_intervencion_intraope = $intraoperatorio->procedimientos_intervencion_intraope($id_intraoperatorio);
    $diagnosticos_enfermeria = $intraoperatorio->diagnosticos_enfermeria($id_intraoperatorio);
    $intervencion_enfermeria = $intraoperatorio->intervencion_enfermeria($id_intraoperatorio);
    $balance_fluidos_enfermeria = $intraoperatorio->balance_fluidos_enfermeria($id_intraoperatorio);
    $recuento_gasas_enfermeria = $intraoperatorio->recuento_gasas_enfermeria($id_intraoperatorio);

    /***************************************************/
    $tipo_profesional_interviene_cirujano = $model_tipo_profesionalInterviene::select('id', 'descripcion')->where('estado', 1)->where('flag_profesional', 1)->get();
    $tipo_profesional_interviene_anestesiologo = $model_tipo_profesionalInterviene::select('id', 'descripcion')->where('estado', 1)->where('flag_profesional', 2)->get();
    $tipo_instrumentista = $model_tipo_profesionalInterviene::select('id', 'descripcion')->where('estado', 1)->where('flag_profesional', 3)->get();
    $tipo_anestesia = $model_tipo_anestesia::select('id', 'descripcion')->where('estado', 1)->get();

    $diagnosticos = $intraoperatorio->diagnosticos_pacientes_prestacion($datos_paciente->id_prestacion, $datos_paciente->id_evolucion);
    $farmacia = $modelfarma->GetFarmaByUser(Auth::user()->id);
    $id_ipress = $farmacia->id_establecimiento;
    $establecimiento = $model_establecimiento->GetById($id_ipress);
    $nivel = $id_ipress == 1 ? 2 : $establecimiento[0]->nivel;

    return view('frontend.centro_quirurgico.intraoperatorio.editar_intraoperario', compact('datos_paciente', 'intraoperatorio', 'diagnosticos_enfermeria', 'intervencion_enfermeria', 'balance_fluidos_enfermeria', 'recuento_gasas_enfermeria', 'cirujanos', 'anestesiologos', 'instrumentistas', 'tipo_profesional_interviene_cirujano', 'tipo_profesional_interviene_anestesiologo', 'tipo_instrumentista', 'tipo_anestesia', 'diagnosticos', 'procedimientos_intervencion', 'intra_operatorio', 'nivel', 'procedimientos_intervencion_intraope'));
  }

  public function editarIntraOperatorio_ajax($id_intraoperatorio)
  {
    $model_tipo_profesionalInterviene = new CqxTipoProfesional();
    $model_tipo_anestesia = new CqxTipoAnestesia();
    $intraoperatorio = new IntraOperatorio();
    $modelfarma = new Farmacia;
    $model_establecimiento = new Establecimiento;

    $intra_operatorio = $intraoperatorio::find($id_intraoperatorio);
    if (empty($intra_operatorio)) {
      return response()->json([
        'success' => false,
        'data' => 'No se encontró la hoja de recuperación con el ID especificado. '
      ]);
    }

    $datos_paciente = $intraoperatorio->datos_form($intra_operatorio->id_intervencion);
    if (!$datos_paciente) {
      return response()->json(['success' => false, 'msg' => 'No hay datos del paciente por el id_intervencion' . $intra_operatorio->id_intervencion]);
    }

    $cirujanos = $intraoperatorio->profesiones(0, $intra_operatorio->id_intervencion); //CIRUJANOS
    $anestesiologos = $intraoperatorio->profesiones(1, $intra_operatorio->id_intervencion); //ANESTESIOLOGOS
    $instrumentistas =  $intraoperatorio->profesiones(2, $intra_operatorio->id_intervencion); //INSTRUMENTISTAS
    $procedimientos_intervencion = $intraoperatorio->procedimientos_intervencion($intra_operatorio->id_intervencion);
    $procedimientos_intervencion_intraope = $intraoperatorio->procedimientos_intervencion_intraope($id_intraoperatorio);
    $diagnosticos_enfermeria = $intraoperatorio->diagnosticos_enfermeria($id_intraoperatorio);
    $intervencion_enfermeria = $intraoperatorio->intervencion_enfermeria($id_intraoperatorio);
    $balance_fluidos_enfermeria = $intraoperatorio->balance_fluidos_enfermeria($id_intraoperatorio);
    $recuento_gasas_enfermeria = $intraoperatorio->recuento_gasas_enfermeria($id_intraoperatorio);

    /***************************************************/
    $tipo_profesional_interviene_cirujano = $model_tipo_profesionalInterviene::select('id', 'descripcion')->where('estado', 1)->where('flag_profesional', 1)->get();
    $tipo_profesional_interviene_anestesiologo = $model_tipo_profesionalInterviene::select('id', 'descripcion')->where('estado', 1)->where('flag_profesional', 2)->get();
    $tipo_instrumentista = $model_tipo_profesionalInterviene::select('id', 'descripcion')->where('estado', 1)->where('flag_profesional', 3)->get();
    $tipo_anestesia = $model_tipo_anestesia::select('id', 'descripcion')->where('estado', 1)->get();

    $diagnosticos = $intraoperatorio->diagnosticos_pacientes_prestacion($datos_paciente->id_prestacion, $datos_paciente->id_evolucion);
    $farmacia = $modelfarma->GetFarmaByUser(Auth::user()->id);
    $id_ipress = $farmacia->id_establecimiento;
    $establecimiento = $model_establecimiento->GetById($id_ipress);
    $nivel = $id_ipress == 1 ? 2 : $establecimiento[0]->nivel;

    return response()->json([
      'success' => true,
      'data' => [
        'datos_paciente' => $datos_paciente,
        'intra_operatorio' => $intra_operatorio,
        'cirujanos' => $cirujanos,
        'anestesiologos' => $anestesiologos,
        'instrumentistas' => $instrumentistas,
        'procedimientos_intervencion' => $procedimientos_intervencion,
        'procedimientos_intervencion_intraope' => $procedimientos_intervencion_intraope,
        'diagnosticos_enfermeria' => $diagnosticos_enfermeria,
        'intervencion_enfermeria' => $intervencion_enfermeria,
        'balance_fluidos_enfermeria' => $balance_fluidos_enfermeria,
        'recuento_gasas_enfermeria' => $recuento_gasas_enfermeria,

        'tipo_profesional_interviene_cirujano' => $tipo_profesional_interviene_cirujano,
        'tipo_profesional_interviene_anestesiologo' => $tipo_profesional_interviene_anestesiologo,
        'tipo_instrumentista' => $tipo_instrumentista,
        'tipo_anestesia' => $tipo_anestesia,

        'diagnosticos' => $diagnosticos,
        'nivel' => $nivel
      ]
    ]);
  }

  public function updateIntraOperatorio(Request $request)
  {
    if (!Auth::check()) {
      return redirect('/auth/login');
    }
    DB::beginTransaction();
    try {
      //dd($request->all());
      $rules = [];
      $messages = [];


      // Validaciones condicionales según los indicadores
      // $rules['cama'] = ['required'];
      if ($request->input('tiene_tratamiento') === 'true') {
        $rules['descripcion_tratamiento'] = ['required'];
      }
      if ($request->input('tiene_analisis') === 'true') {
        $rules['descripcion_analisis'] = 'required';
        $rules['fecha_analisis'] = 'required';
        $rules['cvc_analisis'] = 'required';
        $rules['sf_analisis'] = 'required';
        $rules['la_analisis'] = 'required';
        $rules['lpp_analisis'] = 'required';
        $messages['descripcion_analisis.required'] = 'El campo "Descripcion" es obligatorio cuando tiene_analisis es verdadero.';
        $messages['sf_analisis.required'] = 'El campo "SF análisis" es obligatorio cuando tiene_analisis es verdadero.';
        $messages['la_analisis.required'] = 'El campo "LA análisis" es obligatorio cuando tiene_analisis es verdadero.';
        $messages['lpp_analisis.required'] = 'El campo "LPP análisis" es obligatorio cuando tiene_analisis es verdadero.';
      }
      if ($request->input('tiene_diagnostico_enfermeria') === 'true') {
        $rules['campo1_diagnostico_enfermeria'] = 'required';
        $rules['campo2_diagnostico_enfermeria'] = 'required';
        $rules['campo3_diagnostico_enfermeria'] = 'required';
      }

      if ($request->input('tiene_balance_hidrico') === 'true') {
        $rules['balance_hidrico_campo'] = 'required';
        $messages['balance_hidrico_campo.required'] = 'El campo "balance hídrico" es obligatorio cuando tiene_balance_hidrico es verdadero.';
      }
      // Validaciones de arrays
      // $rules['nombre_procedimiento'] = 'required|array';
      // $rules['procedimiento_cantidad'] = 'required|array';
      // $rules['miSelect'] = 'required|array';


      $accionRow = $request->input('accionRowProcedimiento', []);


      foreach ($accionRow as $key => $accion) {
        if (in_array($accion, ['nuevo'])) {
          $rules["id_procedimiento.$key"] = 'required';
          $messages["id_procedimiento.$key.required"] = "El campo nombre del procedimiento en la fila $key es obligatorio.";


          $rules["procedimiento_cantidad.$key"] = 'required';
          $messages["procedimiento_cantidad.$key.required"] = "La cantidad en la fila $key es obligatoria.";


          $rules["miSelect.$key"] = 'required';
          $messages["miSelect.$key.required"] = "Debe seleccionar un diagnóstico para la fila $key.";
        } else if (in_array($accion, ['edit'])) {
          $rules["procedimiento_cantidad.$key"] = 'required';
          $messages["procedimiento_cantidad.$key.required"] = "La cantidad en la fila $key es obligatoria.";


          $rules["miSelect.$key"] = 'required';
          $messages["miSelect.$key.required"] = "Debe seleccionar un diagnóstico para la fila $key.";
        }
      }


      // Realizar la validación
      $validator = Validator::make($request->all(), $rules, $messages);


      if ($validator->fails()) {
        return response()->json([
          'success' => false,
          'errors' => $validator->errors(),
        ]);
      }

      $modeloIntraOpe = new IntraOperatorio();
      $modeloIntraOpe->actualizacionGeneral($request->all(), Auth::user()->id);
      DB::commit();
      return response()->json(['success' => true, 'id_intraoperatorio' => $request->id_intraoperatorio]);
    } catch (\Exception $e) {
      DB::rollback();
      Log::error('Error al querer actualizar los datos de Intra Operatorio', [
        'message' => $e->getMessage(),
        'code' => $e->getCode(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString(),
      ]);
    } catch (\Throwable $th) {
      DB::rollback();
      throw $th;
    }
  }

  public function updateIntraOperatorioV2(Request $request)
  {
    if (!Auth::check()) {
      return response()->json([
        'success' => false,
        'message' => 'La sesión ha expirado.',
        'redirect' => url('/auth/login')
      ], 401);
    }

    DB::beginTransaction();

    try {

      $rules = [];
      $messages = [];

      // Validaciones condicionales
      if ($request->input('tiene_tratamiento') === 'true') {
        $rules['descripcion_tratamiento'] = ['required'];
      }

      if ($request->input('tiene_analisis') === 'true') {
        $rules['descripcion_analisis'] = 'required';
        $rules['fecha_analisis'] = 'required';
        $rules['cvc_analisis'] = 'required';
        $rules['sf_analisis'] = 'required';
        $rules['la_analisis'] = 'required';
        $rules['lpp_analisis'] = 'required';

        $messages['descripcion_analisis.required'] = 'El campo "Descripción" es obligatorio cuando tiene_analisis es verdadero.';
        $messages['sf_analisis.required'] = 'El campo "SF análisis" es obligatorio cuando tiene_analisis es verdadero.';
        $messages['la_analisis.required'] = 'El campo "LA análisis" es obligatorio cuando tiene_analisis es verdadero.';
        $messages['lpp_analisis.required'] = 'El campo "LPP análisis" es obligatorio cuando tiene_analisis es verdadero.';
      }

      if ($request->input('tiene_diagnostico_enfermeria') === 'true') {
        $rules['campo1_diagnostico_enfermeria'] = 'required';
        $rules['campo2_diagnostico_enfermeria'] = 'required';
        $rules['campo3_diagnostico_enfermeria'] = 'required';
      }

      if ($request->input('tiene_balance_hidrico') === 'true') {
        $rules['balance_hidrico_campo'] = 'required';
        $messages['balance_hidrico_campo.required'] = 'El campo "balance hídrico" es obligatorio cuando tiene_balance_hidrico es verdadero.';
      }

      $accionRow = $request->input('accionRowProcedimiento', []);

      foreach ($accionRow as $key => $accion) {

        if ($accion == 'nuevo') {

          $rules["id_procedimiento.$key"] = 'required';
          $messages["id_procedimiento.$key.required"] = "El procedimiento en la fila " . ($key + 1) . " es obligatorio.";

          $rules["procedimiento_cantidad.$key"] = 'required';
          $messages["procedimiento_cantidad.$key.required"] = "La cantidad en la fila " . ($key + 1) . " es obligatoria.";

          $rules["miSelect.$key"] = 'required';
          $messages["miSelect.$key.required"] = "Debe seleccionar un diagnóstico en la fila " . ($key + 1);
        } elseif ($accion == 'edit') {

          $rules["procedimiento_cantidad.$key"] = 'required';
          $messages["procedimiento_cantidad.$key.required"] = "La cantidad en la fila " . ($key + 1) . " es obligatoria.";

          $rules["miSelect.$key"] = 'required';
          $messages["miSelect.$key.required"] = "Debe seleccionar un diagnóstico en la fila " . ($key + 1);
        }
      }

      $validator = Validator::make($request->all(), $rules, $messages);

      if ($validator->fails()) {

        DB::rollBack();

        return response()->json([
          'success' => false,
          'message' => 'Errores de validación.',
          'errors' => $validator->errors()
        ], 422);
      }

      $modeloIntraOpe = new IntraOperatorio();
      $modeloIntraOpe->actualizacionGeneral($request->all(), Auth::user()->id);

      DB::commit();

      return response()->json([
        'success' => true,
        'message' => 'Registro actualizado correctamente.',
        'id_intraoperatorio' => $request->id_intraoperatorio
      ]);
    } catch (\Throwable $e) {

      DB::rollBack();

      Log::error('Error al actualizar Intra Operatorio', [
        'message' => $e->getMessage(),
        'code'    => $e->getCode(),
        'file'    => $e->getFile(),
        'line'    => $e->getLine(),
        'trace'   => $e->getTraceAsString(),
      ]);

      return response()->json([
        'success' => false,
        'message' => 'Ocurrió un error interno al actualizar el registro.'
      ], 500);
    }
  }

  public function anularIntraOperatorio(Request $request)
  {
    try {
      $registro = IntraOperatorio::find($request->intraoperatorio_id);
      $intervencion_id = $registro->id_intervencion;

      if (!$registro) {
        return response()->json([
          'success' => false,
          'message' => 'No se encontró el registro.'
        ], 404);
      }

      $hayProfesionales = CqxProfesional::where('id_cqx_intervenciones', $intervencion_id)
        ->where('id_intraoperatorio', $registro->id)
        ->exists();

      if ($hayProfesionales) {
        CqxProfesional::where('id_cqx_intervenciones', $intervencion_id)
          ->where('id_intraoperatorio', $registro->id)
          ->where('estado', '!=', 0)
          ->update(array(
            'estado' => 4
          ));
      }

      $registro->estado = 0;
      $registro->save();

      return response()->json([
        'success' => true,
        'message' => 'El registro fue anulado correctamente.',
        'data' => $registro
      ], 200);

    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Ocurrió un error al anular el registro.',
        'error' => $e->getMessage()
      ], 500);
    }
  }
}