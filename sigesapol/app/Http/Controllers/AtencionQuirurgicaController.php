<?php

namespace App\Http\Controllers;

use App\Models\CqxAtencione;
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
use App\Models\Receta_vale;
use App\Models\Cita;
use App\Models\Prestaciones;
use App\Models\User;
use App\Models\Consultorio;
use App\Models\Medico;
use App\Models\Kardexe;
use App\Models\Producto;
use App\Models\Medico_consultorio;
use App\Models\Hospitalizaciones;
use App\Models\Stock_farmacia;
use App\Models\Asegurado;
use App\Models\Gestacion;
use App\Models\Evaluacione;
use App\Models\Establecimiento;
use App\Models\Admision_enfermeria;
use App\Models\Paciente;
use App\Models\Asegurado_historia;
use App\Models\Tipo_grado;
use App\Models\Triaje;
use App\Models\Distrito;
use App\Models\Tipo_parentescos;
use App\Models\Emergencia;
use SoapClient;
use App\Models\Upss;
use App\Models\Cama;
use App\Models\HospitalizacionAntecedentes;
use App\Models\Producto_receta;
use App\Models\SubConsultorio;
use App\Models\CqTipoProfesionalInterviene;
use App\Models\Referencia;
use App\Models\HospitalizacionPostqx;
use App\Models\ProgramacionQuirurgica;
use App\Models\Puerperio;
use App\Models\Nati;
use App\Models\Oftalmologia;
use App\Models\IntraOperatorio;
use App\Models\CqxProcedimiento;
use App\Models\Gastroenterologia;
use App\Models\CqxTipoProfesional;
use App\Models\CqxProfesional;
use App\Models\CqxIntervencion;
use App\Models\CqxDia;
use App\Models\Cups_pnp;
use App\Models\Evaluacion;
use App\Models\CambioCama;
use App\Models\Receta_diagnostico;
use App\Models\Hospitalizacione;
use App\Http\Requests\AltaFormRequest;
use App\Models\Destino;
use App\Models\IntervencionTerapeutica;
use App\Models\TipoAtencione;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Archive7z\Archive7z;


class AtencionQuirurgicaController extends Controller
{
  function __construct()
    {
        $this->middleware('auth'); 
    } 
 
  public function lista_pendiente()
  {
      if (!Auth::check()):
          return redirect('/auth/login');
      endif;

      $id_user = Auth::user()->id;
      $id_farmacia = Auth::user()->id_farmacia;
      $id_establecimiento = Auth::user()->id_establecimiento;

      $date = Carbon::now();
      $fechaHasta = $date->format('d-m-Y');
      $fechaDesdex = $date->format('d-m-Y');
      $fechaDesdey = strtotime('-1 day', strtotime($fechaDesdex));
      $fechaDesde = date('d-m-Y', $fechaDesdey);
      
      $dni_beneficiario = '';
      $nombre_beneficiario = '';
      $estado = 0;

      /*==========SECCION DE UPSS HOSPITALIZACION==========*/
      $model_upss = new \App\Models\Upss();
      $upss = $model_upss->ShowUPSS_by_Hospitalizacion();
      /*==========SECCION DE UPSS HOSPITALIZACION==========*/
      $hola=1;
      return view('frontend.centro_quirurgico.all_pendientes2', compact('fechaDesde', 'fechaHasta', 'dni_beneficiario', 'nombre_beneficiario', 'estado', 'upss', 'id_user', 'id_establecimiento', 'id_farmacia','hola'));
      
  }

  public function pre_anestesiologo()
  {
      if (!Auth::check()):
          return redirect('/auth/login');
      endif;

      return view('frontend.centro_quirurgico.pre_anestesiologo');
  }

  public function post_atencion()
  {
      if (!Auth::check()):
          return redirect('/auth/login');
      endif;

      return view('frontend.centro_quirurgico.post_atencion');
  }

  public function registrar_intraoperario()
  {
      if (!Auth::check()):
          return redirect('/auth/login');
      endif;

      return view('frontend.centro_quirurgico.form_intraoperario'/*,compact()*/);

  }
  
  public function registro_seguridad()
  {
      if (!Auth::check()):
          return redirect('/auth/login');
      endif;

      return view('frontend.centro_quirurgico.form_registro_seguridad'/*,compact()*/);

  }

  public function instrumentos_quirurgicos()
  {
      if (!Auth::check()):
          return redirect('/auth/login');
      endif;

      return view('frontend.centro_quirurgico.form_instrumentos'/*,compact()*/);
  }

  public function listar_prestacion_intervencionqx_ajax(Request $request)
  {
      $model_citas = new Cita();
      $p[] = $request->fecha_ini;
      $p[] = $request->fecha_fin;
      $p[] = $request->dni_beneficiario;
      $p[] = $request->nombre_beneficiario;
      $p[] = (isset($request->id_servicio) && $request->id_servicio != "") ? $request->id_servicio : 0;
      $p[] = $request->estado;
      $p[] = $request->id_tipo_atencion;
      $p[] = Auth::user()->id_establecimiento;
      $p[] = $request->NumeroPagina;
      $p[] = $request->NumeroRegistros;
      $data = $model_citas->listar_prestacion_intervencionqx_ajax($p);
      $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;
      $result["PageStart"] = $request->NumeroPagina;
      $result["pageSize"] = $request->NumeroRegistros;
      $result["SearchText"] = "";
      $result["ShowChildren"] = true;
      $result["iTotalRecords"] = $iTotalDisplayRecords;
      $result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
      $result["aaData"] = $data;
      echo json_encode($result);
  }

  public function formulario_cita_riesgo_quirurgico($id_prestacion, $id_servicio){
      if (!Auth::check()) :
        return redirect('/auth/login');
      endif;
    
      $model_kardex = new Kardexe();
      $model_usuario = new User;
    
      $fechaServidor = $model_kardex->getFechaServidor();
      $user = $model_usuario->GetById(Auth::user()->id);
      $flagemergenciareferencia = $user->flagemergenciareferencia;
    
      $model_prestacion = new Prestaciones();
      $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);

      $model_consultorio = new Consultorio();
      $servicio = $model_consultorio->GetNombreById($id_servicio);
    
      return view('frontend.centro_quirurgico.form_cita_riesgo_quirurgico', compact('fechaServidor', 'flagemergenciareferencia', 'prestacion', 'servicio'));
  }
  public function hoja_recuperacion()
  {
      if (!Auth::check()):
          return redirect('/auth/login');
      endif;

      return view('frontend.centro_quirurgico.form_recuperacion');
  }

 
  public function modal_atencion_quirurgica($id_cita,$id_intervencion,$id_tipo_atencion)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    $id_farmacia = Auth::user()->id_farmacia;
    $id_establecimiento=Auth::user()->id_establecimiento;
    $last_gestacion=[];

    $model_consultorio = new Consultorio;
    $model_cita = new Cita;
    $model_receta = new Receta_vale;
    $model_prestacion = new Prestaciones;
    $model_farma = new farmacia;
    $model_kardex = new Kardexe;
    $date = Carbon::now();

    $cita = null;
    $prestacion1 = null; 
    $prestacion = null; 
    if(!empty($id_cita) && $id_cita != "null"){
      $cita = $model_cita->GetCitasById($id_cita);
      $prestacion1 = $model_prestacion->GetPrestacionByIdCita($id_cita); 
      $prestacion = $model_prestacion->GetPrestacionById($prestacion1->id); 
    }
    
    //$fechaServidor = $model_kardex->getFechaServidor();
    //$hora = $date->format('H:i');
    $fechaServ = \Carbon\Carbon::now(); 
    $fechaServidor = $fechaServ->format('Y-m-d H:i:s');
  
    $model_farma = new Farmacia();
    $farmacia = $model_farma->GetFarmaByIdfarma($id_farmacia);
    $id_ipress = $farmacia->id_establecimiento;

    //$model_producto = new Producto;
    $model_establecimiento = new \App\Models\Establecimiento;
    $establecimiento = $model_establecimiento->GetById($id_ipress);
    //$establecimientos = $model_establecimiento->ShowAllEstablecimientos();
  
    // $asegurado = Asegurado::select('id as id_asegurado', 'nro_doc_ident')
    // ->where('id', $cita->id_asegurado)
    // ->first();

    // $titular = Asegurado::select('tipo_doc_ident','dni_titular', 'nombre', 'paterno', 'materno', 'cip', 'grado', 'situacion_paciente', 'telefono')
    // ->where('id', $cita -> id_asegurado)->first();
      
    // if ($id_hospitalizacion != 'null'){
    //   $acompaniante = DB::table('hospitalizaciones as h')
    //       ->leftJoin('tipo_parentescos as t', 't.id', '=', 'h.id')
    //       ->select('t.descripcion', 'h.tipo_doc_acompaniante', 'h.nro_doc_acompaniante', 'h.nombre_acompaniante', 'h.telefono_acompaniante')
    //       ->where('h.id', $id_hospitalizacion)->first();
    // }else{
    //     $acompaniante = 'null';
    // }
    $hospitalizacion="";
    $model_intervencion = new CqxIntervencion();
    $datos_intervencion = $model_intervencion::select(
      'cqx_intervenciones.id_prestacion',
      'cqx_intervenciones.id_hospitalizacion',
      'cs.nombre as sala',
      'cqx_intervenciones.id_consultorio',
      'cqx_intervenciones.id_asegurado',
      'cqx_intervenciones.es_nuevo_admision_hospitalizacion',
      'cqx_intervenciones.id_interconsulta')
    ->leftJoin('cq_salas as cs', 'cqx_intervenciones.id_sala_intervencion', '=', 'cs.id')
    ->where('cqx_intervenciones.id',$id_intervencion)->first();
   
    $titular = Asegurado::select('asegurados.tipo_doc_ident','asegurados.dni_titular','asegurados.tipo_doc_titular','asegurados.nombre_titular','asegurados.materno_titular','asegurados.paterno_titular', 'asegurados.nombre','nro_doc_ident',
    'asegurados.paterno', 'asegurados.materno', 'asegurados.cip', 'asegurados.grado', 'asegurados.situacion_paciente', 'asegurados.telefono','asegurados.fecha_nac','asegurados.cip','asegurados.domicilio',
    DB::raw("EXTRACT(YEAR FROM AGE(CURRENT_DATE, TO_DATE(asegurados.fecha_nac, 'DD/MM/YYYY'))) AS edadafiliado"),'ts.descripcion_corta as tipo_sangre','asegurados.sexo','asegurados.id_tipo_doc_ident')
    ->leftJoin('tipo_sangres as ts', 'id_tipo_sangre', '=', 'ts.id')
    ->where('asegurados.id', $datos_intervencion->id_asegurado)->first();
 
    $id_hospitalizacion = !empty($datos_intervencion->id_hospitalizacion)?$datos_intervencion->id_hospitalizacion:0;
    if (!empty($id_hospitalizacion)){
      $hospitalizacion = DB::table('hospitalizaciones as h')
          ->leftJoin('tipo_parentescos as t', 't.id', '=', 'h.id_tipo_acompaniante')
          ->leftJoin('camas as c', 'h.id_cama', '=', 'c.id')
          ->leftJoin('consultorios as c2', 'h.id_servicio_destino', '=', 'c2.id')
          ->leftJoin('medicos as med', 'h.id_medico', '=', 'med.id')
          ->leftJoin('profesiones as pro', 'med.id_profesion', '=', 'pro.id')
          ->leftJoin('establecimientos as e', 'h.id_establecimiento', '=', 'e.id')
          ->select('h.*','e.nombre as establecimiento','e.id as id_establecimiento','med.dni as dni_medico','med.paterno as paterno_medico','med.materno as materno_medico','med.nombre as nombre_medico','pro.nombre as profesion','med.colegiatura','t.descripcion','c2.id as id_servicio','c2.nombre as servicio_destino','c.nombre as cama', 'h.tipo_doc_acompaniante', 'h.nro_doc_acompaniante', 'h.nombre_acompaniante', 'h.telefono_acompaniante')
          ->where('h.id', $id_hospitalizacion)->first();
    }
    // else{
    //   return response()->json([
    //     'success' => false,
    //     'message' => 'El paciente no esta hospitalizado'
    //   ], 422); 
    // }
    // if(isset($prestacion1->id_gestacion)){
    //   $salud_materna = DB::table('gestaciones as g')
    //   ->leftjoin('prestaciones as p', 'g.id', '=', 'p.id_gestacion')
    //   ->select('p.id_gestacion','g.id','g.fech_ult_regla','g.fech_prob_parto','g.semanas_gestacion','g.num_controles','g.duda')
    //   ->where('id_gestacion', $prestacion1 ->id_gestacion)->first();
    
    //   $info_referencia = DB::table('referencias as r')
    //   ->join('prestaciones as p', 'p.id', '=', 'r.id_prestacion')
    //   ->join('establecimientos as e', 'e.id', '=', 'r.id_establecimiento')
    //   ->join('consultorios as c', 'c.id', '=', 'r.id_servicio')
    //   ->join('procedimientos_diagnoticos as pd', 'p.id', '=', 'pd.id_prestacion')
    //   ->join('diagnosticos as d', 'pd.id_diagnosticos', '=', 'd.id')
    //   ->select(
    //       'r.id as id_referencia',
    //       'r.id_prestacion as id_prestacion_referencia',
    //       'e.nombre as nombre_establecimiento_referencia',
    //       'c.nombre as nombre_servicio_referencia',
    //       'r.fecha_atencion as fecha_referencia',
    //       'r.observacion_referencia',
    //       DB::raw("d.codigo || ' ' || d.nombre as codigo_nombre_diagnostico")
    //   )
    //   ->where('id_gestacion', $prestacion1->id_gestacion)
    //   ->first();

    //   $last_gestacion = $model_prestacion -> getLastGestacionByDniAseguradoForFormAtencion($asegurado->nro_doc_ident);
    // }
    // TRAER DATA DE GESTACION:
    $prestacion_for_find_gestacion = null;
    if(!empty($prestacion1->id)){
      $prestacion_for_find_gestacion = Prestaciones::find($prestacion1->id);
    }
    $gestacion = null;
    // if (isset($prestacion_for_find_gestacion->id_gestacion)) {
    //   $gestacion = Gestacion::find($prestacion_for_find_gestacion->id_gestacion);
    //   error_log("GESTACION DE PRESTACION CITA: ".(string)$gestacion->id);
    // }
    // Fin insercion se gestacion
    $puerperio = null;
    $emergencia  = null;
    if(!empty($prestacion_for_find_gestacion->id_ext)){
      $emergencia = Emergencia::find($prestacion_for_find_gestacion->id_ext);
      if (isset($emergencia) && isset($emergencia->id_puerperio)) {
        $puerperio = Puerperio::find($emergencia->id_puerperio);
      }
    }
    $natis = null;
    if (isset($puerperio)) {
      $natis = Nati::where("id_puerperio", $puerperio->id)->get();
    }
  
    // $last_gestacion = $model_prestacion -> getLastGestacionByDniAseguradoForFormAtencion($asegurado->nro_doc_ident);
    //dd($last_gestacion);
  
    $tipo_atencion = $model_farma->GetFarmaciaTipoAtencionByIdFarmacia($id_farmacia);
    if ($id_ipress == 1) {
        $nivel = 2;
    } else {
        $nivel = $establecimiento[0]->nivel;
    }
  
    
    //$id_tipo_atencione=$prestacion1->id_tipo_atencion;
    $id_tipo_atencione=3;
    //$id_consultorio=8;
    //$producto = $model_producto->getProductosByNivelAndIdServicio($nivel, $hospitalizacion->id_servicio_destino, $id_farmacia);
    //$consultorio = $model_consultorio->ShowConsultorios($id_farmacia);
    $farmacias = $model_farma->GetFarmaciaByIdEstablecimiento($farmacia->id_establecimiento);
    //CUANDO VIENE DE INTERCONSULTAS//
    //$datos_intervencion
    $datos_interconsulta=null;
    if(!empty($datos_intervencion->id_interconsulta)){
      $datos_interconsulta = \App\Models\Interconsulta::where('id',$datos_intervencion->id_interconsulta)->where('id_prestacion',$datos_intervencion->id_prestacion)->first();
    }
    if(!empty($datos_interconsulta)){
      $id_servicio = $datos_interconsulta->id_servicio;
    }else{
      if($id_tipo_atencion==1 || $id_tipo_atencion==3 || $datos_intervencion->es_nuevo_admision_hospitalizacion){
        // $id_servicio=isset($hospitalizacion->id_servicio_destino)?$hospitalizacion->id_servicio_destino:$datos_intervencion->id_consultorio;
        $id_servicio=isset($hospitalizacion->id_servicio_encargado)?$hospitalizacion->id_servicio_encargado:$datos_intervencion->id_consultorio;
      }else if($id_tipo_atencion==2){
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
            'ho.fecha_derivacion'
        )
        ->where('p.id', $datos_intervencion->id_prestacion)
        ->first();
        $id_servicio=isset($datos_servicio->id_servicio)?$datos_servicio->id_servicio:0;
      }
    }
    $model_consultorio = new Consultorio();
    $servicio = $model_consultorio->GetNombreById($id_servicio);
    $codigo_upss = null;
    if($id_tipo_atencion==1 && !$datos_intervencion->es_nuevo_admision_hospitalizacion){
      $hospitalizacion_orden = \App\Models\HospitalizacionOrden::where('id_ext', $id_intervencion)->first();
      if ($hospitalizacion_orden) {
          $codigo_upss = \App\Models\Upss::select('id', 'codigo', 'descripcion_upss')
              ->where('id', $hospitalizacion_orden->id_upss_responsable)
              ->get();
      } else {
          return response()->json([
              'success' => false,
              'message' => 'No existe una orden de hospitalización porque aún no se ha generado la atención de programación correspondiente.'
          ], 422);
      }
    }else{
      $codigo_upss = $model_consultorio->ShowCodigoUpssIdTipoAtencion($id_servicio,$id_tipo_atencione);
    }
    
    
    
    if(count($codigo_upss)==0){
      // return redirect('/cq_lista_pendiente/')->withInput()->withErrors(array('Debe registrar un upss..!'));
      return response()->json([
        'success' => false,
        'message' => 'Debe registrar un upss..!'
      ], 422); 
    }

    //$receta = $model_receta->GetDatosRecetasByIdPrestacion($id_intervencion);

    // Cantidad de atenciones en el Establecimiento o IPRESS
    // $cant_atenciones_ipress = $model_prestacion->GetCountAtencionesPorAnioActual($cita->nro_doc_ident, $farmacia->id_establecimiento, $id_tipo_atencione);
  
    // if ($cant_atenciones_ipress->atenciones > 0):
    //   $ipress_bene = 'CONTINUADOR';
    // else:
    //   $cant_atenciones_ipress2 = $model_prestacion->GetCountAtencionesActual($cita->nro_doc_ident, $farmacia->id_establecimiento, $id_tipo_atencione);
    //   if ($cant_atenciones_ipress2->atenciones == 0):
    //     $ipress_bene = 'NUEVO';
    //   else:
    //     $ipress_bene = 'REINGRESO';
    //   endif;
    // endif;
  
    // Cantidad de atenciones en el Servicio o UPSS
    // $cant_atenciones_servicio = $model_prestacion->GetCountAtencionesPorAnioActual($cita->nro_doc_ident, $farmacia->id_establecimiento, $id_tipo_atencione, $cita->id_sub_consultorio);
  
    // if ($cant_atenciones_servicio->atenciones > 0):
    //   $upss_bene = 'CONTINUADOR';
    // else:
    //   $cant_atenciones_servicio2 = $model_prestacion->GetCountAtencionesActual($cita->nro_doc_ident, $farmacia->id_establecimiento, $id_tipo_atencione, $cita->id_sub_consultorio);
    //   if ($cant_atenciones_servicio2->atenciones == 0):
    //     $upss_bene = 'NUEVO';
    //   else:
    //     $upss_bene = 'REINGRESO';
    //   endif;
    // endif;
  
    // if($id_tipo_atencione==1){
    //   if($cita->id_tipo_cita!=''){
    //     $modalidad = $cita->id_tipo_cita;
    //   }
    //   else{
    //     $modalidad = 0;
    //   }
    // }
    // else{
    //   $modalidad =0;
    // }
    $modalidad=1;
    //$aEscala = $this->formulario_atencion_escalas();
  
    try {
      $client = new SoapClient(env('WI_SALUDPOL_URL') . "/ws/WS_AfiliadoSP_Serv.php?wsdl", array('login' => env('WI_SALUDPOL_USERNAME'), 'password' => env('WI_SALUDPOL_PASSWORD')));
      $beneficiario = $client->getAseguradoValidate($titular->tipo_doc_ident, $titular->nro_doc_ident);
      if (empty($beneficiario)) {
          return response()->json([
              'success' => false,
              'message' => 'No se encontró información del asegurado.'
          ], 404);
      }
      
    } catch (\SoapFault $e) {
        return response()->json([
            'success' => false,
            'message' => 'No se pudo conectar con el servicio de SALUDPOL. Verifique su conexión a internet o intente más tarde.',
            'error' => $e->getMessage(),
        ], 503);
    
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Ocurrió un error inesperado al consultar el servicio de SALUDPOL.',
            'error' => $e->getMessage(),
        ], 500);
    }
  
    if (is_object($beneficiario)) :
      $flag = 1 ;
    else:
      $flag = 0 ;
    endif;
    
    if(isset($cita->id_referencia)){
      $model_referencia = new \App\Models\Referencia;
      $referencia = $model_referencia->GetBuscaReferenciaById($cita->id_referencia);
      $model_prestacionreferncia = new \App\Models\Prestaciones;
      $diagnostico_prestacion_referencia = $model_prestacionreferncia->GetDiagnosticoByIdPrestacion($referencia->id_prestacion);
      $procedimientos_refencia = $model_referencia->buscar_procedimiento_referencia($cita->id_referencia);
      $servicios_ipress_origen = $model_referencia->busca_servicio_por_ipres_origen_referencia($referencia->id_establecimiento);
    
    }else{
      $diagnostico_prestacion_referencia = "";
      $referencia = "";
      $procedimientos_refencia="";
      $servicios_ipress_origen="";
    }
  
    // $tipoPartosSql = "SELECT * FROM tipo_partos";
    // $tipoPartos = DB::select($tipoPartosSql);
  
    // $tipoDocsNatiSql = "SELECT * FROM tipo_doc_nati";
    // $tipoDocsNati = DB::select($tipoDocsNatiSql);
  
    $codigo = null; 
    $codigo_siteds = null;
    $fecha_atencion = null;
    if(!empty($cita)){
      $fecha_atencion = date('d-m-Y', strtotime($cita->fecha_hora));
      $codigo = $model_cita->busca_Siteds_Atencion($cita->nro_doc_ident,$fecha_atencion);
    }
  
    // switch ($prestacion1->id_tipo_atencion) {
    //   case '1': //CONSULTA EXTERNA
    //       $prestacion = $model_prestacion->GetPrestacionById($prestacion1->id);
    //       $referencia = $prestacion->referido;
          
    //       break;
          
    //   case '2': //EMERGENCIA
    //       $prestacion = $model_prestacion->GetPrestacionEmergenciaRadiologiaById([$prestacion1->id]);
    //       $referencia = isset($prestacion->id_motivo_ingreso)?$prestacion->id_motivo_ingreso:'';
          
    //       break;
    // }
    // if(isset($prestacion->id)){
    //   if($prestacion->id>1910698){
    //     $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionydiag($prestacion1->id, 1);
    //   }
    // }else{
    //   $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacion($prestacion1->id, 1);
    // }
  
    //$diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($prestacion1->id);
    $id_ipress= Auth::user()->id_establecimiento;
  
    $model_cqx_dia = new CqxDia();
    $cqx_dias = $model_cqx_dia->GetAllActivos();

    $model_tipo_profesionalInterviene = new CqxTipoProfesional();
    $tipo_profesional_interviene_cirujano = $model_tipo_profesionalInterviene::select('id', 'descripcion')->where('estado', 1)->where('flag_profesional',1)->get();
    
    $model_intraoperatorio = new IntraOperatorio;
    $cirujanos = $model_intraoperatorio->profesiones(0,$id_intervencion);

    $model_intervencion = new CqxIntervencion();
    $intervencion = $model_intervencion->GetById($id_intervencion);

    //$flag_atencion_qx = $model_intervencion->UpdateFlagAtencionQX($id_intervencion_quirurgica);

    $model_cq_procedimiento = new CqxProcedimiento();
    $procedimientos = $model_cq_procedimiento->GetByIdIntervencionQuirugica($id_intervencion);
    $procedimientos_intervencion = $model_intraoperatorio->procedimientos_intervencion($id_intervencion);//PROCEDIMIENTOS DE INTERVENCION

    $intraoperatorio = $this->anestesiaIdIntervencion($id_intervencion);
    //dd($intraoperatorio);
    $tipo_anestesia = DB::table('cqx_tipo_anestesia')
    ->select('id', 'descripcion')
    ->get();

    $model_destino = new Destino();
    $destinos = $model_destino->TipoDestino();

    $modelcama = new Cama;
    $model_emergencia = new \App\Models\Emergencia;
    $model_hospitalizacion = new Hospitalizacione;
    //$hospitalizacion = null;
    $dato_cama = null;
    $cuartos = [];
    $camas = [];

    $flag_clinica_dia=!empty($hospitalizacion->flag_clinica_dia)?$hospitalizacion->flag_clinica_dia:0;

    //if($id_hospitalizacion != 'null'){
    //$hospitalizacion = $model_hospitalizacion::find( $id_hospitalizacion);

    if(!empty($hospitalizacion)) {
      if($hospitalizacion->flag_clinica_dia!=1){
        if(!empty($hospitalizacion->id_cama)){
          $dato_cama = $model_emergencia->GetObtenerCama($hospitalizacion->id_cama);
          $camas = $modelcama->ShowAllCamasXIpressYCuartoYTipoatencion($hospitalizacion->id_establecimiento,$dato_cama->id_cuarto,2);
        }
      }
      $cuartos = $modelcama->ShowAllCuartosXIpress2($hospitalizacion->id_establecimiento,2);
    }
    //}

    $upss_model = new Upss();
    $listUpss = $upss_model->ShowUPSS_by_Hospitalizacion();

    $model_medicos = new Medico;
    $medico = $model_medicos->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni, Auth::user()->id_establecimiento);
    
    if(!empty($codigo)){
      $codigo_siteds = $codigo->codigo_siteds;
    }

    $fechaEgreso = $model_kardex->getFechaServidor();
    $horaEgreso = $model_kardex->getHoraServidor();

    if($id_hospitalizacion != 'null'){
      $datos_hospitalizacion = $model_hospitalizacion->GetDiasEstanciaByIdHospitalizacion($id_hospitalizacion);
      $dia_estancia = $datos_hospitalizacion ? $datos_hospitalizacion->dia_estancia : null;
    }else{
      $dia_estancia = 'null';
    }

    $upss="SELECT 
    concat(u.descripcion_upss,' / ',u.codigo ) as nombre,
    u.id as id_upss,
    u.codigo as codigo_upss,
    c.id as id_consultorio,
    c.nombre as nombre_consultorio
    from consultorios c 
    inner join upsses u on u.id_consultorio = c.id 
    where u.id_tipo_atencion = '3' and u.estado = '1' and u.estado = '1' and u.descripcion_upss like 'H%' order by nombre asc";
    $upss= DB::select($upss);

    //∫$servicios_concurrencia = $model_consultorio->ShowAllConsultoriosSubConsultorioAll($id_establecimiento);
    //$consulta_externa = $model_consultorio->ShowConsultorioByIdIpressAndIdTipoAtencion($id_establecimiento,1);
    //$establecimientos_traslado = $model_establecimiento->GetEstablecimientosNivel1Y2();
    $model_tipo_cirugia = new \App\Models\CqxTipoAnestesia();
    $tipo_cirugias = $model_tipo_cirugia->GetAllTipoCirugiasActivos();

    $puede_atennder_servicios_cq = DB::table('medico_consultorios as mc')
                            ->join('consultorios as c', 'mc.id_consultorio', '=', 'c.id')
                            ->where('mc.id_establecimiento', $id_ipress)
                            ->where('mc.id_medico', $medico->id)
                            ->where('mc.estado', 1)
                            ->where('c.id', $id_servicio)
                            ->exists();

    $pabellones = $modelcama->GetAllPabellones($id_ipress, 'HOSPITALIZACION');
    $model_cq_sala = new \App\Models\CqSalas();
    $cq_salas = $model_cq_sala->GetAllActivos();
    $flag_api_sp = DB::table('config_options')->where('id', 5)->value('api_sp_activated'); // si es true debería utilizarse el API de SALUDPOL , caso contrario, la ws de SALUDPOL
    return view('frontend.centro_quirurgico.atencion_quirurgica.atencion_quirurgica', compact('flag_api_sp','upss','fechaEgreso', 'horaEgreso','dia_estancia',
    'flag_atencion_qx','listUpss','hora','cuartos','camas','dato_cama','hospitalizacion','destinos','tipo_anestesia', 'intraoperatorio','intervencion','id_intevencion_quirurgica','cqx_dias','id_intervencion','procedimientos_intervencion','cirujanos','tipo_profesional_interviene_cirujano','procedimientos','info_referencia','titular','aEscala','servicios','servicios_ipress_origen','diagnostico_prestacion_referencia', 'referencia','procedimientos_refencia','fechaServidor', 'codigo_upss', 'cita', 'consultorio', 'id_cita', 'tipo_atencion', 'farmacias', 'nivel','id_tipo_atencione','ipress_bene','upss_bene','modalidad','flag','prestacion', 'tipoPartos', 'tipoDocsNati', 'last_gestacion','codigo_siteds','diagnostico','via_administracion','puede_atennder_servicios_cq','gestacion','gestacion','puerperio','natis','flag_clinica_dia','id_servicio','servicio','id_tipo_atencion','datos_intervencion','tipo_cirugias','pabellones','datos_interconsulta','cq_salas'));
  }

  public function anestesiaIdIntervencion($id_intervencion)
 {
    $intraoperatorio = Intraoperatorio::from('cqx_intraoperatorios as i')
    ->leftJoin('cqx_tipo_anestesia as ta', 'ta.id', '=', 'i.id_cqx_tipo_anestesia')
    ->select(
        'ta.id',
        'ta.descripcion',
        'i.id as id_intraoperatorio',
        'i.anestesia_inicio',
        'i.anestesia_termino',
        'i.cirugia_inicio',
        'i.cirugia_termino',
        'i.tipo_real',
        'i.inicio_paciente',
        'i.salida_paciente',
        'i.hora_llegada_urpa',
        'i.complicaciones',
        'i.anestesia_tipo_id'
    )
    ->where('i.id_intervencion', $id_intervencion)
    ->first();

    return $intraoperatorio; 
  }
 

  public function medicosByEspecialidadConsultorio($nombre,$id_especializacion="")
    {
      if (!Auth::check()) {
        return redirect('/auth/login');
      }
      $model_intraoperatorio = new IntraOperatorio;
      $id_ipress= Auth::user()->id_establecimiento;
      $medicos = $model_intraoperatorio->medicosByEspecialidadConsultorio($id_especializacion , $nombre, $id_ipress);
      $array = array();
      foreach ($medicos as $leer) {
        $array[] = array( 
          "value" => $leer->dni . " - " . $leer->paterno ." ". $leer->materno." " .$leer->nombre,
          "label" => $leer->dni . " - " . $leer->paterno ." ". $leer->materno." " .$leer->nombre,
          "id" => $leer->id,
        );
      }
      return json_encode($array);
  }
  
  public function motivoAnulacionCirujano(Request $request){
    $profesional = CqxProfesional::find((int)$request['id_medico']);
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

  private function formulario_atencion_escalas() 
  {
    $aPuntaleLetras = ['', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];
    $model_reseta_vale = new \App\Models\Receta_vale();
    $escala = $model_reseta_vale->GetEscalas();
    $escala_opciones = $model_reseta_vale->GetEscalaOpciones();
    $aEscala = [];
    foreach ($escala as $item) {
      $aEscala[$item->id]['nombre'] = $item->nombre;
      $aEscala[$item->id]['tipo'] = $item->tipo;
    }
    foreach ($escala_opciones as $item) {
      if ($item->id_parent == 0) {
        $aEscala[$item->id_escala]['item'][$item->id]['nombre'] = $item->nombre;
      } else {
        $puntaje = null;
        if ($aEscala[$item->id_escala]['tipo'] == 3) {
          $puntaje = isset($aPuntaleLetras[$item->puntaje]) ? $aPuntaleLetras[$item->puntaje] : '--';
        } else {
          $puntaje = $item->puntaje;
        }
        $aEscala[$item->id_escala]['item'][$item->id_parent]['item'][] = [
          'nombre' => $item->nombre,
          'puntaje' => $puntaje
        ];
      }
    }
    return $aEscala;
  }

  public function registro_prestacion_transaction(Request $request)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    DB::beginTransaction();
    try {
      //dd($request->all());
          $id_prestacion=$request->id_prestacion;
          $prestacion_unico = DB::table('prestaciones as pre')->where('pre.flag_intervencion_quirurgica','=',true)->where('pre.id_intervencion','=',$request->id_intervencion_quirurgica)->select('pre.*')->first();
          $id_prestacion = !empty($prestacion_unico)  ? $prestacion_unico->id  : $request->id_prestacion ; 
          //VERIFICA SI EXISTE LA ATENCION Y ACTUALIZA EL FLAG DE ATENDIDO
          $existe_atencion = \App\Models\CqxAtencione::where('id_cqx_intervencion', $request->id_intervencion_quirurgica)->exists();
          if($existe_atencion){
            DB::update('UPDATE cqx_intervenciones SET flag_atencion_qx = 1 WHERE id = ?',[$request->id_intervencion_quirurgica]);
            DB::commit(); 
            return response()->json(['success' => true,'existe_atencion'=>true]);
          }

          $flag_ref_cref = 0;
          //error_log($request->referencia_activo . ": resultado de activacion de referencia");

          if($request->referencia_activo == 'false' ){
            $flag_ref_cref = 1;
          }
          if($request->contrareferencia_activo == 'false' ){
            $flag_ref_cref = 2;
          }

          $model_prestacion = new \App\Models\Prestaciones;

          $p_diagnostico = "";
          $p_diagnostico .= "{";
          if (isset($request->id_diagnostico)) :
            foreach ($request->id_diagnostico as $key => $value) :
              if ($request->id_diagnostico[$key] != "") {
                $p_diagnostico .= "{";
                $p_diagnostico .= $request->id_diagnostico[$key] . ",";
                $p_diagnostico .= (isset($request->id_tipo_diagnostico[$key]) && $request->id_tipo_diagnostico[$key] > 0) ? $request->id_tipo_diagnostico[$key] . "," : "0,";
                $p_diagnostico .= (isset($request->id_categoria_diagnostico[$key]) && $request->id_categoria_diagnostico[$key] > 0) ? $request->id_categoria_diagnostico[$key] . "," : "0,";
                $p_diagnostico .= $request->id_intervencion_quirurgica;
                $p_diagnostico .= "},";
              }
            endforeach;
            if (strlen($p_diagnostico) > 1) $p_diagnostico = substr($p_diagnostico, 0, -1);
          endif;

          $p_diagnostico .= "}";
          $p_procedimiento = "";
          $p_procedimiento .= "{";
          if (isset($request->id_procedimiento)) :
            foreach ($request->id_procedimiento as $key => $value) :
              if ($request->id_procedimiento[$key] != "") {
                $p_procedimiento .= "{";
                $p_procedimiento .= $request->id_procedimiento[$key] . ",";
                $p_procedimiento .= (isset($request->procedimiento_cantidad[$key]) && $request->procedimiento_cantidad[$key] > 0) ? $request->procedimiento_cantidad[$key] : "0";
                $p_procedimiento .= "},";
              }
            endforeach;
            if (strlen($p_procedimiento) > 1) $p_procedimiento = substr($p_procedimiento, 0, -1);
          endif;
          $p_procedimiento .= "}";
          $fecha_alta = "";
          $id_motivo_alta = 0;
          $id_estado_reg = 1;
          $observacion = "";
          $id_receta_vale = 0;
          $id_anestesiologo = 0;
          $obs_registro = "";
          $id_receta_vale_bus = 0;
          $id_admision = 0;
          $tipo_via = "";
          $sub_via = "";
          $final_via = "";
          $nro_guia = "";
          $interconsulta = "";
          $interconsulta_dias = 0;
          $nro_receta = "";
          $digitos_adicionales = "";
          $id_tipo_num = 0;
          $id_servicio_derivar = (isset($request->id_servicio_derivar)) ? $request->id_servicio_derivar : 0;
          $fecha_expedicion =  (isset($request->fecha_expedicion)) ? $request->fecha_expedicion : "";
          $anamnesis = (isset($request->anamnesis)) ? $request->anamnesis : "";
          $examen_fisico = (isset($request->examen_fisico)) ? $request->examen_fisico : "";
          $aspectos_generales = (isset($request->aspectos_generales)) ? $request->aspectos_generales : "";
          $estado_receta = 2;
          $condicion = (isset($request->condicion)) ? $request->condicion : 0;
          $cantidadReceta = (isset($request->cantidadReceta) && $request->cantidadReceta > 0) ? $request->cantidadReceta : 1;
          $id_asegurado   = $request->id_asegurado;
          $receta_prescrita  =  0; ;$receta_prescrita_postqx=0;
         
          // if($request->id_tipo_atencion==1){
          //   $id_cita= $request->id_cita;
          //   $id_emergencia = 0;
          //   $id_hospitalizacion = 0;
          // }
          // else
          // {
          //   $request->id_modalidad = 1;
          //   if($request->id_tipo_atencion==2){
          //     $id_emergencia = $request->id_cita;
          //     $id_hospitalizacion = 0;
          //     $id_cita= 0;
          //   }
          //   else
          //   {
          //     $id_hospitalizacion = $request->id_cita;
          //     $id_cita= 0; 
          //     $id_emergencia = 0;
          //   }
          // }
          $id_emergencia = 0;
          $id_hospitalizacion = !empty($request->id_hospitalizacion)?$request->id_hospitalizacion:null;
          $id_cita= 0;
          

          $p_producto = "";
          $p_producto .= "{";
          if (isset($request->id_producto)) :
            $receta_prescrita=1;
            foreach ($request->id_producto as $key => $value) :
              if ($request->id_producto[$key] != "") {
                $p_producto .= "{";
                $p_producto .= $request->id_producto[$key] . ",";
                $p_producto .= $request->tiempo_periodo[$key] . ",";
                $p_producto .= $request->nro_med_solictados[$key] . ",";
                $p_producto .= (isset($request->nro_med_entregados[$key]) && $request->nro_med_entregados[$key] != "") ? $request->nro_med_entregados[$key] : 0;
                $p_producto .= ",";
                $p_producto .= (isset($request->nro_stocks_almacen[$key]) && $request->nro_stocks_almacen[$key] != "") ? $request->nro_stocks_almacen[$key] : 0;
                $p_producto .= ",";
                $p_producto .= (isset($request->nro_stocks_establecimiento[$key]) && $request->nro_stocks_establecimiento[$key] != "") ? $request->nro_stocks_establecimiento[$key] : 0;
                $p_producto .= ",";
                $p_producto .= (isset($request->nro_stocks[$key]) && $request->nro_stocks[$key] != "") ? $request->nro_stocks[$key] : 0;
                $p_producto .= ",";
                $p_producto .= (isset($request->posologia[$key]) && $request->posologia[$key] != "") ? str_replace(",", "###", $request->posologia[$key]) : "-";
                $p_producto .= ",";
                $p_producto .= (isset($request->miSelectProd[$key]) && $request->miSelectProd[$key] != "") ? $request->miSelectProd[$key] : 0;
                $p_producto .= ",";
                $p_producto .=(isset($request->id_via_administracion[$key]) && $request->id_via_administracion[$key] != "") ? $request->id_via_administracion[$key] : 0;
                $p_producto .= "},";
              }
            endforeach;
            if (strlen($p_producto) > 1) $p_producto = substr($p_producto, 0, -1);
          endif;

          $p_producto .= "}";
          $p_producto_postqx = "";
          $p_producto_postqx .= "{";
          if (isset($request->id_producto2)) ://RECETA POSTQUIRURGICA
            $receta_prescrita_postqx=1;
            foreach ($request->id_producto2 as $key => $value) :
              if ($request->id_producto2[$key] != "") {
                $p_producto_postqx .= "{";
                $p_producto_postqx .= $request->id_producto2[$key] . ",";
                $p_producto_postqx .= $request->tiempo_periodo2[$key] . ",";
                $p_producto_postqx .= $request->nro_med_solictados2[$key] . ",";
                $p_producto_postqx .= (isset($request->nro_med_entregados2[$key]) && $request->nro_med_entregados2[$key] != "") ? $request->nro_med_entregados2[$key] : 0;
                $p_producto_postqx .= ",";
                $p_producto_postqx .= (isset($request->nro_stocks_almacen2[$key]) && $request->nro_stocks_almacen2[$key] != "") ? $request->nro_stocks_almacen2[$key] : 0;
                $p_producto_postqx .= ",";
                $p_producto_postqx .= (isset($request->nro_stocks_establecimiento2[$key]) && $request->nro_stocks_establecimiento2[$key] != "") ? $request->nro_stocks_establecimiento2[$key] : 0;
                $p_producto_postqx .= ",";
                $p_producto_postqx .= (isset($request->nro_stocks2[$key]) && $request->nro_stocks2[$key] != "") ? $request->nro_stocks2[$key] : 0;
                $p_producto_postqx .= ",";
                $p_producto_postqx .= (isset($request->posologia2[$key]) && $request->posologia2[$key] != "") ? str_replace(",", "###", $request->posologia2[$key]) : "-";
                $p_producto_postqx .= ",";
                $p_producto_postqx .= (isset($request->miSelectProd2[$key]) && $request->miSelectProd2[$key] != "") ? $request->miSelectProd2[$key] : 0;
                $p_producto_postqx .= ",";
                $p_producto_postqx .=(isset($request->id_via_administracion2[$key]) && $request->id_via_administracion2[$key] != "") ? $request->id_via_administracion2[$key] : 0;
                $p_producto_postqx .= "},";
              }
            endforeach;
            if (strlen($p_producto_postqx) > 1) $p_producto_postqx = substr($p_producto_postqx, 0, -1);
          endif;

          $p_producto_postqx .= "}";
          // $ipress_bene    = $request->ipress_bene;
          // $upss_bene        = $request->id_upss_encargado_atencion;
          $id_farmacia_receta        =  ($request->id_farmacia!="") ? $request->id_farmacia : 0;
          $id_farmacia_receta_postQ        =  ($request->id_farmacia_receta_postqx!="") ? $request->id_farmacia_receta_postqx : 0;
          $fecha_expedicion_postQ =  (isset($request->fecha_expedicion_postqx)) ? $request->fecha_expedicion_postqx : "";

          if (Auth::user()->tipo_registro == 3) $id_receta_vale_bus = 1;

          //$id_sub_consultorio=0;
          //$id_sub_consultorio=$request->id_sub_consultorio;

          // if($request->id_modalidad==2){
          //   $medico_consultorio = new Medico_consultorio();
          //   $sub_consultorio_medico = $medico_consultorio->GetSubConsultorioByIdEstablecimientoAndIdConsultorio( $request->id_establecimiento, $request->id_servicio, $request->id_tipo_atencion);  
          //   $id_sub_consultorio =$sub_consultorio_medico[0]->id;
          // }
          // else
          // {

          //  if($request->id_tipo_atencion==2){
          //     $id_sub_consultorio=545;
          //   }
          //   else{
          //     if($request->id_tipo_atencion==3){
          //       $id_sub_consultorio=551;
          //     }
          //     else{
          //       $id_sub_consultorio=$request->id_sub_consultorio;
          //     }
          //   }
          // }
          
          $model_cita = new \App\Models\Cita;
          $model_kardex = new Kardexe();
          $id_cita = 0;
          $fecha_cita = $model_kardex->getFechaServidor();
          $correo = "";$domicilio = "";$telefono = ""; $telefono2 = ""; $tipo_cita = $request->id_modalidad; $adicional = 1 ;

          $id_user = Auth::user()->id;
          $model_medico = new \App\Models\Medico();
          $model_usuario = new User;
          $usuario_actual = $model_usuario->GetById($id_user);
          $medico = $model_medico->medico_yaregistrado($usuario_actual->dni, Auth::user()->id_establecimiento);

          $resultado = DB::table('sub_consultorios')
          ->where('id_consultorio', $request->id_servicio)
          ->where('id_establecimiento', Auth::user()->id_establecimiento)
          ->where('id_estado_reg', 1)
          ->orderBy('id', 'asc')
          ->value('id');

          $id_cita = $model_cita->registrarCita($id_cita, $request->id_establecimiento, $resultado, $medico->id, $request->id_user, $fecha_cita, $request->tipodoc_beneficiario, $request->nrodocafiliado, $request->nombre_beneficiario, $request->paterno_beneficiario, $request->materno_beneficiario, $request->tipo_beneficiario, $request->nro_historia, $request->grado, $request->fecnacafiliado, $request->nomsexo, $request->cip_beneficiario, $request->tipodoc_titular, $request->nrodoctitular, $request->nombre_titular, $request->paterno_titular, $request->materno_titular, NULL, 4, 1, $correo,$domicilio,$telefono,$telefono2,$tipo_cita,2,'NO',0,'','','','','','','','');
          $cita = $model_cita->GetCitasByIdCita($id_cita);
          $id_asegurado = $cita->id_asegurado;
          
      //     if ($id_cita == 0) {
      //       $model_cita = new \App\Models\Cita;
      //       $model_kardex = new Kardexe();
      //       $id_cita = 0;
      //       $fecha_cita = $model_kardex->getFechaServidor();
      //       $correo = "";$domicilio = "";$telefono = ""; $telefono2 = ""; $tipo_cita = $request->id_modalidad; $adicional = 1 ;
      // // }}}     $id_cita = $model_cita->registrarCita($id_cita, $request->id_establecimiento, $id_sub_consultorio, $request->id_medico, $request->id_user, $fecha_cita, $request->tipodoc_beneficiario, $request->nrodocafiliado, $request->nombre_beneficiario, $request->paterno_beneficiario, $request->materno_beneficiario, $request->tipo_beneficiario, $request->nro_historia, $request->grado, $request->fecnacafiliado, $request->nomsexo, $request->cip_beneficiario, $request->tipodoc_titular, $request->nrodoctitular, $request->nombre_titular, $request->paterno_titular, $request->materno_titular, NULL, 1, 1, $correo,$domicilio,$telefono,$telefono2,$tipo_cita,2,'NO');
      //       $id_cita = $model_cita->registrarCita($id_cita, $request->id_establecimiento, $id_sub_consultorio, $request->id_medico, $request->id_user, $fecha_cita, $request->tipodoc_beneficiario, $request->nrodocafiliado, $request->nombre_beneficiario, $request->paterno_beneficiario, $request->materno_beneficiario, $request->tipo_beneficiario, $request->nro_historia, $request->grado, $request->fecnacafiliado, $request->nomsexo, $request->cip_beneficiario, $request->tipodoc_titular, $request->nrodoctitular, $request->nombre_titular, $request->paterno_titular, $request->materno_titular, NULL, 1, 1, $correo,$domicilio,$telefono,$telefono2,$tipo_cita,2,'NO',0,'','','','','','','','');
      //       $cita = $model_cita->GetCitasByIdCita($id_cita);
      //       $id_asegurado = $cita->id_asegurado;
      //     }
      //     else
      //     { $adicional=0;
      //       if($request->id_tipo_atencion==2){
      //         $model_emergencia = new \App\Models\Emergencia;
      //         $update=$model_emergencia->ActualizaEstadoEmergencia($id_emergencia,3);
      //       }
      //       else{
      //         if($request->id_tipo_atencion==3){
      //           $model_hospitalizacion = new \App\Models\Hospitalizacione;
      //           $update_hospi=$model_hospitalizacion->ActualizaEstadoHospitalizacion($id_hospitalizacion,4);
      //         }
      //       }  
      //     }

      //     $registrar_cita = $request->registrar_cita == "false" ? 1 : 0;

      // EMBARAZO
          // Informacion de la gestacion o puerpuerio:
          // 1. Valdacion de sexo, solo se permite este campo para el sexo femenino
          $paciente = Asegurado::find($id_asegurado);
          if ($request->esta_gestando == "true" && $paciente->sexo == "M") {
            //Session::flash('flash_message', 'No se pudo registrar la Prestacion, el paciente debe ser del sexo femenino!');
           // return redirect('/all_citas_medico_paciente');//CORREGIR
           return response()->json(['success' => false,'msg'=>'No se pudo registrar la Prestacion, el paciente debe ser del sexo femenino!']);
          }
          // 2. Validacion para que el medico solo sea ginecologo:
          // DNI DEL MEDICO
          // $dniMedico = $request->dniMedico;
          // $model_medico = new \App\Models\Medico;
          // $medico = $model_medico -> GetMedicoByDni($dniMedico);
          //$medico = $model_medico -> GetActiveMedicoByDNI($dniMedico);
          $model_medicos = new Medico;
          $medico = $model_medicos->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni, Auth::user()->id_establecimiento);
          if (isset($medico) && $request->esta_gestando == "true" && ($medico->id_especializacion != 2 && $medico->id_especializacion != 31)) { // 2 y 31 para gineco y obstetricia
            // Session::flash('flash_message', 'No se pudo registrar la Prestacion, debe ser Ginecologo u Obstetra!');
            // return redirect('/all_citas_medico_paciente');//CORREGIR
            return response()->json(['success' => false,'msg'=>'No se pudo registrar la Prestacion, debe ser Ginecologo u Obstetra!']);
          }

          /*********************************Embarazo************************************/
          // GESTACION
          // $gestacion = $request->esta_gestando == "true" ? true : false;
          // if ($gestacion == true) {
          
          //   $id_prestacion = $model_prestacion->registrarPrestacion($id_cita, $id_asegurado, $request->id_tipo_atencion, $request->id_establecimiento, $id_sub_consultorio, $request->fecha_atencion, $fecha_alta, $request->id_medico, $id_motivo_alta, $id_estado_reg, $observacion, $id_receta_vale, $id_farmacia_receta, $request->id_user, $nro_receta, $digitos_adicionales, $id_anestesiologo, $obs_registro, $id_receta_vale_bus, $id_admision, $tipo_via, $sub_via, $final_via, $nro_guia, $interconsulta, $interconsulta_dias, $fecha_expedicion, $id_servicio_derivar, $id_tipo_num, $anamnesis, $examen_fisico, $aspectos_generales, $estado_receta, $condicion, $cantidadReceta, $p_procedimiento, $p_producto, $p_diagnostico, $ipress_bene, $upss_bene,$receta_prescrita, $request->id_modalidad, $request->nomsexo, $request->fecnacafiliado, $request->codigo_upss,NULL,NULL,$id_prestacion,$flag_ref_cref,$request->id_hospitalizacion);
          //   $paciente = Asegurado::find($id_asegurado);
          //   $paciente->estadios_salud_materna = '2';
          //   $paciente->update_estadios_salud_materna = Carbon::now();
          //   $paciente->id_prestacion_estadios_salud_materna = $id_prestacion;
          //   $paciente->id_secuencia_estadios_salud_materna = '1';
          //   $paciente->save();
          //   $prestacion = Prestaciones::find($id_prestacion);
          //   $prestacion -> id_gestacion = $model_gestacion -> id;
          //   $prestacion -> save();
          // } else {
            // if(empty($request->id_hospitalizacion)){
            //    $codigo_upss = null;
            // }else{
            //   $codigo_upss = $request->codigo_upss;
            // } 
    
          $model_farmacia = new Farmacia;      
          $farmacia = $model_farmacia->GetFarmaByIdfarma($id_farmacia_receta);

          // Cantidad de atenciones en el Establecimiento o IPRESS
          $cant_atenciones_ipress = $model_prestacion->GetCountAtencionesPorAnioActual($paciente->nro_doc_ident, $farmacia->id_establecimiento, $request->id_tipo_atencion);
          if ($cant_atenciones_ipress->atenciones > 0):
            $ipress_bene = 'CONTINUADOR';
          else:
            $cant_atenciones_ipress2 = $model_prestacion->GetCountAtencionesActual($paciente->nro_doc_ident, $farmacia->id_establecimiento, $request->id_tipo_atencion);
            if ($cant_atenciones_ipress2->atenciones == 0):
              $ipress_bene = 'NUEVO';
            else:
              $ipress_bene = 'REINGRESO';
            endif;
          endif;

          // Cantidad de atenciones en el Servicio o UPSS
          $cant_atenciones_servicio = $model_prestacion->GetCountAtencionesPorAnioActual($paciente->nro_doc_ident, $farmacia->id_establecimiento, $request->id_tipo_atencion, $request->id_sub_consultorio);

          if ($cant_atenciones_servicio->atenciones > 0):
            $upss_bene = 'CONTINUADOR';
          else:
            $cant_atenciones_servicio2 = $model_prestacion->GetCountAtencionesActual($paciente->nro_doc_ident, $farmacia->id_establecimiento, $request->id_tipo_atencion, $cita->id_sub_consultorio);
            if ($cant_atenciones_servicio2->atenciones == 0):
              $upss_bene = 'NUEVO';
            else:
              $upss_bene = 'REINGRESO';
            endif;
          endif;
          
            $codigo_upss = $request->codigo_upss;
            $fecha_atencion=Carbon::parse($request->fecha_hora_atencion)->format('Y-m-d H:i');
            $v_id_prestacion = $model_prestacion->registrarPrestacion_cqx($id_cita, $id_asegurado, $request->id_tipo_atencion, $request->id_establecimiento, $request->id_servicio, $fecha_atencion, $fecha_alta, $medico->id, $id_motivo_alta, $id_estado_reg, $observacion, $id_receta_vale, $id_farmacia_receta, $request->id_user, $nro_receta, $digitos_adicionales, $id_anestesiologo, $obs_registro, $id_receta_vale_bus, $id_admision, $tipo_via, $sub_via, $final_via, $nro_guia, $interconsulta, $interconsulta_dias, $fecha_expedicion, $id_servicio_derivar, $id_tipo_num, $anamnesis, $examen_fisico, $aspectos_generales, $estado_receta, $condicion, $cantidadReceta, $p_procedimiento, $p_producto,$p_producto_postqx, $id_farmacia_receta_postQ,$fecha_expedicion_postQ,$p_diagnostico, $ipress_bene, $upss_bene,$receta_prescrita,$receta_prescrita_postqx, $request->id_modalidad, $request->nomsexo, $request->fecnacafiliado, $codigo_upss,NULL,NULL,$id_prestacion,$flag_ref_cref,!empty($request->id_hospitalizacion)?$request->id_hospitalizacion:null);

            if( !empty($v_id_prestacion) && $v_id_prestacion >  0){
                $id_prestacion = $v_id_prestacion;
                if (empty($prestacion_unico) || $v_id_prestacion != $prestacion_unico->id) {
                    Log::info(
                        "Se registró la prestación con ID: " . $id_prestacion . " para la intervención quirúrgica con ID: " . $request->id_intervencion_quirurgica . " (NO GENERA ID ÚNICO DE PRESTACIÓN)"
                    );
                }

            }else{
               return response()->json([
                    'success' => false,
                    'message' => 'No se realizó el registro de la prestación, por favor intente nuevamente o contacte con el administrador del sistema.'
                ], 422);
            } 
            //}
          //dd($request);
          /*********************************Fin Embarazo************************************/
          //$id_prestacion = $model_prestacion->registrarPrestacion($id_cita, $id_asegurado, $request->id_tipo_atencion, $request->id_establecimiento, $id_sub_consultorio, $request->fecha_atencion, $fecha_alta, $request->id_medico, $id_motivo_alta, $id_estado_reg, $observacion, $id_receta_vale, $id_farmacia_receta, $request->id_user, $nro_receta, $digitos_adicionales, $id_anestesiologo, $obs_registro, $id_receta_vale_bus, $id_admision, $tipo_via, $sub_via, $final_via, $nro_guia, $interconsulta, $interconsulta_dias, $fecha_expedicion, $id_servicio_derivar, $id_tipo_num, $anamnesis, $examen_fisico, $aspectos_generales, $estado_receta, $condicion, $cantidadReceta, $p_procedimiento, $p_producto, $p_diagnostico, $ipress_bene, $upss_bene,$receta_prescrita, $request->id_modalidad, $request->nomsexo, $request->fecnacafiliado, $request->codigo_upss,$adicional);

          /*****************************Otros datos**************************************************************/
          $temperatura                = $request->temperatura != '' ? $request->temperatura : 0;
          $sa02_porcentaje            = $request->saturacion != '' ? $request->saturacion : 0;
          $presion_arterial_x         = $request->presion_arterial_x != '' ? $request->presion_arterial_x : 0;
          $presion_arterial_y         = $request->presion_arterial_y != '' ? $request->presion_arterial_y : 0;
          $talla_cm                   = $request->talla != '' ? $request->talla : 0;
          $tamano_cintura_cm          = $request->tamanio_cintura != '' ? $request->tamanio_cintura : 0;
          $frecuencia_respiratoria    = $request->frecuencia_respiratoria != '' ? $request->frecuencia_respiratoria: 0;
          $peso_kg                    = $request->peso != '' ? $request->peso: 0;
          $frecuencia_cardiaca_fetal  = $request->frec_cardiaca_fetal != '' ? $request->frec_cardiaca_fetal : 0;
          $frecuencia_cardiaca        = $request->frec_cardiaca != '' ? $request->frec_cardiaca : 0;
          $fi02                       = $request->oxigenacion != '' ? $request->oxigenacion : 0;

          $estado = 1;
          $model_prestacion->adicionarDatosPrestacion($id_prestacion, $temperatura, $sa02_porcentaje, $presion_arterial_x, $presion_arterial_y, $talla_cm, $tamano_cintura_cm, $frecuencia_respiratoria, $peso_kg, $frecuencia_cardiaca_fetal, $frecuencia_cardiaca, $fi02, $request->motivo_visita, $request->tiempo_enfermedad,null,null, $request->examen_clinico, $request->examen_auxiliar, $request->tratamiento_auxiliar, $request->plan_trabajo,$request->antecedente,$request->antecedente_familiar);

          $date = Carbon::now();
          $hora=$date->toTimeString(); 

          if($hora>'01:30' & $hora<'07:31'):
            /*echo*/ $turno='MD';
          else:
            if($hora>'07:30' & $hora<'13:31'):
              /*echo*/ $turno='M';
            else:
              if($hora>'13:30' & $hora<'19:31'):
                /*echo*/ $turno='T';
              else:
                if($hora>'19:30' & $hora<='23:59'):
                  /*echo*/ $turno='N';
                else:
                  if($hora>='00:00' & $hora<='01:31'):
                    /*echo*/ $turno='N';
                  endif;
                endif;
              endif;
            endif;
          endif;

          // if (isset($request->rq_cardiologia)) {
          //   $model_riesgo_qx = new \App\Models\Riesgo_qx();
          //   $res= $model_riesgo_qx->RegistrarRiesgoQx($id_prestacion, $request->id_user);
          // }

          // $model_interconsulta = new \App\Models\Interconsulta;
          // if (isset($request->id_servicio_interconsulta_preqx)) :
          //   $p_interconsulta = "";
          //   $p_interconsulta .= "{";
          //   foreach ($request->id_servicio_interconsulta_preqx as $key => $value) :
          //     if ($request->id_servicio_interconsulta_preqx[$key] != "") {
          //       $p_interconsulta .= "{";
          //       $p_interconsulta .= $request->id_servicio_interconsulta_preqx[$key] . ",";
          //       $p_interconsulta .= $turno . ",";
          //       $p_interconsulta .= $request->motivo_interconsulta_preqx[$key] ;
          //       $p_interconsulta .= "},";
          //     }
          //   endforeach;
          //   if (strlen($p_interconsulta) > 1) $p_interconsulta = substr($p_interconsulta, 0, -1);
          //   $p_interconsulta .= "}"; 

          //   if($request->id_servicio_interconsulta_preqx[0]!=""):
          //     $model_interconsulta = new \App\Models\Interconsulta;
          //     $id_interconsulta = $model_interconsulta->RegistrarInterconsultaCentroQx($id_prestacion, $id_asegurado, $request->id_tipo_atencion, $request->fecha_atencion, $request->id_medico, $request->id_establecimiento, $p_interconsulta);
          //   endif;
          
          // endif;

          // Si la prestacion proviene de una solicitud de intervencion quirurgica
          // if (intval($request->id_prestacion_solicita_intervencion_qx) > 0):
          //   $observacion = isset($request->motivo_obs_intervencion_qx) ? $request->motivo_obs_intervencion_qx : null;
          //   $updated = $model_riesgo_qx->RegistrarAtencionRiesgoQx($id_cita, $id_prestacion, $observacion);
          // endif;

          //dd($request);
          /****************Actualiza Atencion Emergencia*********************************************/
          // if($id_emergencia!=0):
          //     $model_emergencia = new \App\Models\Emergencia;
          //     $update=$model_emergencia->ActualizaEstadoEmergencia($id_emergencia,3);

          //     $updateid=$model_prestacion->AgregarIdExt($id_prestacion,$id_emergencia);
          // endif;
          /*********************************Procedimiento************************************/
          //$model_procedimiento = new \App\Models\Procedimiento;

          //insertar procedimientos x diagnosticos
          if(isset($request->id_procedimiento)):
            if(isset($request->miSelect)):
              $arrayprocedimientos=$request->id_procedimiento;
              $arraydiagnosticos=$request->miSelect;
              for($iterador=0;$iterador<count($arrayprocedimientos);$iterador++){
        
                $model_procedimiento_metodos = new \App\Models\Procedimiento;
                $idprocedimiento= $arrayprocedimientos[$iterador];
              
                $iddiagnostico = $arraydiagnosticos[$iterador];
            
                $idprestacionxDiagProc = $id_prestacion;
                $mensajeSucess = $model_procedimiento_metodos->RegistrarProcedimientoxDiagnostico($idprestacionxDiagProc,$idprocedimiento,$iddiagnostico,1);
                //PROCEDIMIENTOS POST CQX 
                $model_cq_procedimiento = new \App\Models\CqxProcedimiento();
                $model_cq_procedimiento->RegistrarProcedimientoPreQx($request->id_intervencion_quirurgica, $idprocedimiento, Auth::user()->id, 0,2,false);
                  
              }

            endif;
          endif;
          //DATOS DE EXAMEN DE PATOLOGIA
          if (intval($request->tiene_examen_pat) === 1 && !empty($request->id_procedimiento_examen_patologia) && is_array($request->id_procedimiento_examen_patologia)):
              foreach($request->id_procedimiento_examen_patologia as $key=>$id_procedimiento){
                //PROCEDIMIENTOS POST CQX 
                $model_cq_procedimiento = new \App\Models\CqxProcedimiento();
                $model_cq_procedimiento->RegistrarProcedimientoPreQx($request->id_intervencion_quirurgica, $id_procedimiento, Auth::user()->id, 0,2,true);
              }
          endif;

          // $model_referencia = new \App\Models\Referencia;
          // if($flag_ref_cref  == 1):

          //   $model_referencia = new \App\Models\Referencia;
          //   $model_referencia = $model_referencia->registrarefcref($id_prestacion, $id_asegurado, $request->id_tipo_atencion, $request->id_establecimiento, $request->servicio_referencia, $request->texto_referencia, $request->fecha_atencion, $request->id_medico,$estado);
        
          //   $model_actuliza_estado = new \App\Models\Actualizacion_estado;
          //   $model_actuliza_estado->estado_antiguo = 0;
          //   $model_actuliza_estado->estado_nuevo = 1;
          //   $model_actuliza_estado->id_referencia = $model_referencia;
          //   $model_actuliza_estado->motivo_actualizacion = "Se guardo el Servicio al cual se debe Referir";
          //   $model_actuliza_estado->created_us = Auth::user()->id;
          //   $model_actuliza_estado->flag_estado_reg = 1;
          //   $model_actuliza_estado->save();
          //   endif;

          //  if($flag_ref_cref == 2):
          //   $datos_referencia = Referencia::find($request->id_referencia_inicial);
          //   $model_actuliza_estado = new \App\Models\Actualizacion_estado;
          //   $model_actuliza_estado->estado_antiguo = $datos_referencia->estado_proceso;
          //   $model_actuliza_estado->estado_nuevo = 6;
          //   $model_actuliza_estado->id_referencia = $datos_referencia->id;
          //   $model_actuliza_estado->motivo_actualizacion = "Se guardo el Servicio al cual se debe Contra Referir";
          //   $model_actuliza_estado->created_us = Auth::user()->id;
          //   $model_actuliza_estado->flag_estado_reg = 1;
          //   $model_actuliza_estado->save();

          //   $datos_referencia->estado_proceso = 6;
          //   $datos_referencia->id_servicio_contrareferencia = $request->servicio_contrareferencia;
          //   $datos_referencia->observacion_contrareferencia = $request->texto_contrareferencia;
          //   $datos_referencia->id_medico_contrareferencia =  $request->id_medico;
          //   $datos_referencia->id_prestacion_contrareferencia = $id_prestacion;    
          //   $datos_referencia->fecha_atencion_cotrareferencia = $request->fecha_atencion;  
          //   $datos_referencia->save();
          //   endif;

          /****************Actualiza Atencion Emergencia*********************************************/
          // if($id_emergencia!=0):
          //     $model_emergencia = new \App\Models\Emergencia;
          //     $update=$model_emergencia->ActualizaEstadoEmergencia($id_emergencia,3);

          //     $updateid=$model_prestacion->AgregarIdExt($id_prestacion,$id_emergencia);

          // endif;

          /****************Actualiza Atencion Hopitalizacion*********************************************/
          // if($id_hospitalizacion!=0):
          //     $model_hospitalizacion = new \App\Models\Hospitalizacione;
          //     $update_hopi=$model_hospitalizacion->ActualizaEstadoHospitalizacion($id_hospitalizacion,3);

          //     $updateid=$model_prestacion->AgregarIdExt($id_prestacion,$id_hospitalizacion);

          // endif;

          $model_cqx_atenciones = new \App\Models\CqxAtencione();

          $allazgo_operatorio = !empty($request->allazgo_operatorio) ? $this->limpiarTextarea($request->allazgo_operatorio) : '';
          $pieza_operatorio = !empty($request->pieza_operatorio) ? $this->limpiarTextarea($request->pieza_operatorio) : '';
          $examen_patologico = !empty($request->examen_patologico) ? $this->limpiarTextarea($request->examen_patologico) : '';
          $examen_bacteriologico = !empty($request->examen_bacteriologico) ? $this->limpiarTextarea($request->examen_bacteriologico) : '';
          $complicacion_medica = !empty($request->complicacion_medica) ? $this->limpiarTextarea($request->complicacion_medica) : '';
          $descripcion_procedimiento = !empty($request->descripcion_procedimiento) ? $this->limpiarTextarea($request->descripcion_procedimiento): '';
          //$registrar_destino =  !empty($request->registrar_destino)? (int) $request->registrar_destino: 0;
          $registrar_destino = ($request->registrar_destino !== null && $request->registrar_destino !== '') ? $request->registrar_destino : (!empty($request->id_tipo_egreso) ? 1 : 0);
          $codigo_siteds =  !empty($request->codigo_siteds) ? $request->codigo_siteds :'';
          $id_servicio =  !empty($request->id_servicio) ? $request->id_servicio :null;

          $tiene_hallazgo=!empty($request->tiene_hallazgo) && $request->tiene_hallazgo == 1 ? true : false;
          $tiene_pieza=!empty($request->tiene_pieza) && $request->tiene_pieza == 1 ? true : false;
          $tiene_examen_bact=!empty($request->tiene_examen_bact) && $request->tiene_examen_bact == 1 ? true : false;
          $tiene_examen_pat=!empty($request->tiene_examen_pat) && $request->tiene_examen_pat == 1 ? true : false;
          $tiene_complicacion_medica=!empty($request->tiene_complicacion_medica) && $request->tiene_complicacion_medica == 1 ? true : false;
          $tiene_descripcion_procedimiento=!empty($request->tiene_descripcion_procedimiento) && $request->tiene_descripcion_procedimiento == 1 ? true : false;
          $egreso_resumen = !empty($request->egreso_resumen) ? $this->limpiarTextarea($request->egreso_resumen): '';
          $egreso_examen_clinico = !empty($request->egreso_examen_clinico) ? $this->limpiarTextarea($request->egreso_examen_clinico): '';
          $egreso_examen_auxiliar = !empty($request->egreso_examen_auxiliar) ? $this->limpiarTextarea($request->egreso_examen_auxiliar): '';
          $egreso_evolucion = !empty($request->egreso_evolucion) ? $this->limpiarTextarea($request->egreso_evolucion): '';
          $egreso_tratamiento = !empty($request->egreso_tratamiento) ? $this->limpiarTextarea($request->egreso_tratamiento): '';
          $egreso_procedimiento = !empty($request->egreso_procedimiento) ? $this->limpiarTextarea($request->egreso_procedimiento): '';
          $egreso_complicacion = !empty($request->egreso_complicacion) ? $this->limpiarTextarea($request->egreso_complicacion): '';
          $egreso_medidas_generales = !empty($request->egreso_medidas_generales) ? $this->limpiarTextarea($request->egreso_medidas_generales): '';
          $egreso_medicamentos_prescritos = !empty($request->egreso_medicamentos_prescritos) ? $this->limpiarTextarea($request->egreso_medicamentos_prescritos): '';
          $egreso_pronostico = !empty($request->egreso_pronostico) ? $this->limpiarTextarea($request->egreso_pronostico): '';

          // $hora_inicio_operatorio = !empty($request->hora_inicio_operatorio) ? $request->hora_inicio_operatorio: null;
          // $hora_fin_operatorio = !empty($request->hora_fin_operatorio) ? $request->hora_fin_operatorio: null;
          // $hora_duracion_operatorio = !empty($request->hora_duracion_operatorio) ? $request->hora_duracion_operatorio: null;
          // Hora de inicio del quirófano
          if (!empty($request->hora_inicio_operatorio)) {
              $hora_inicio_operatorio = $request->hora_inicio_operatorio;
          } elseif (!empty($request->cirugia_inicio2)) {
              $hora_inicio_operatorio = $request->cirugia_inicio2;
          } else {
              $hora_inicio_operatorio = null; 
          }
         
          if (!empty($request->hora_fin_operatorio)) {
              $hora_fin_operatorio = $request->hora_fin_operatorio;
          } elseif (!empty($request->cirugia_termino2)) {
              $hora_fin_operatorio = $request->cirugia_termino2;
          } else {
              $hora_fin_operatorio = null;
          }
          
          $minutos = 0;
          // Si viene 'tipo_real' en formato HH:MM y no es '00:00'
          if (!empty($request->tipo_real2) && $request->tipo_real2 != '00:00') {
              $partes = explode(':', $request->tipo_real2);
              if (count($partes) === 2) {
                  $h = (int)$partes[0];
                  $m = (int)$partes[1];
                  $minutos = ($h * 60) + $m;
              }
          } elseif (!empty($request->hora_duracion_operatorio)) {
              $minutos = (int)$request->hora_duracion_operatorio;
          }
          $hora_duracion_operatorio = $minutos;

          $model_cqx_atenciones->RegistrarHistorialAtencionQuirurgica(
            $request->id_intervencion_quirurgica, 
            !empty($request->id_hospitalizacion)?$request->id_hospitalizacion:null, 
            $id_prestacion, 
            Auth::user()->id, 
            Carbon::now(),
            $allazgo_operatorio,
            $pieza_operatorio,
            $examen_patologico,
            $examen_bacteriologico,
            $registrar_destino,
            $codigo_siteds,
            $id_servicio,
            $tiene_hallazgo,
            $tiene_pieza,
            $tiene_examen_bact,
            $tiene_examen_pat,
            $complicacion_medica,
            $tiene_complicacion_medica,
            $descripcion_procedimiento,
            $tiene_descripcion_procedimiento,
            $codigo_upss,
            $request->id_clasificacion_cirugia,
            $egreso_resumen,
            $egreso_examen_clinico,
            $egreso_examen_auxiliar,
            $egreso_evolucion,
            $egreso_tratamiento,
            $egreso_procedimiento,
            $egreso_complicacion,
            $egreso_medidas_generales,
            $egreso_medicamentos_prescritos,
            $egreso_pronostico,
            $hora_inicio_operatorio,
            $hora_fin_operatorio,
            $hora_duracion_operatorio 
          );
            /***************CUPS**************************/
            //   switch($request->id_tipo_atencion){
            //   case 1 : $corte_adm = 'CE_00001'; $id_tipo_atencion = 1; break;
            //   case 2 : $corte_adm = 'EM_00001'; $id_tipo_atencion = 2; break;
            //   case 3 : $corte_adm = 'HO_00001'; $id_tipo_atencion = 3; break;
            //   case 4 : $corte_adm = 'CQ_00001'; $id_tipo_atencion = 4; break;
            //   case 5 : $corte_adm = 'CE_00001'; $id_tipo_atencion = 1; break;
            //   case 6 : $corte_adm = 'HO_00001'; $id_tipo_atencion = 2; break;
            //   case 6 : $corte_adm = 'HO_00001'; $id_tipo_atencion = 2; break;
            //   case 7 : $corte_adm = 'CE_00001'; $id_tipo_atencion = 1; break;
            //   case 8 : $corte_adm = 'HO_00001'; $id_tipo_atencion = 2; break;
            // }
            $corte_adm = 'HO_00001';

            $date = Carbon::now(); 
            $fechaActual = $date->format('d-m-Y');
            $horaActual = $date->format('H:i:s');

            $objEstablecimiento= Establecimiento::where('id', '=', Auth::user()->id_establecimiento)->first(); //CODIGO ESTABLECIMIENTO
            // $objCita= Cita::where('id', '=', $id_cita)->first(); //CODIGO ESTABLECIMIENTO
            // $objCita->codigo_siteds = $request->codigo_siteds;
            // $objCita->save();
            $objPrestacion= Prestaciones::where('id', '=', $id_prestacion)->first(); //CODIGO ESTABLECIMIENTO
            $objCups_pnp = new \App\Models\Cups_pnp;
            $objCups_pnp->codigo_ipress =$objEstablecimiento->codigo;
            $objCups_pnp->año = substr($date->format('Y'), 2, 4); 
            $objCups_pnp->intramural_extramural = 'I'; 
            $objCups_pnp->no_referidos_referidos = 0; 
            // if($objCita->referido=='NO'){
            //   $objCups_pnp->no_referidos_referidos = 0; 
            // }else{
              // $objCups_pnp->no_referidos_referidos = 1; 
            //}

            $objCups_pnp->tipo_atencion = 3; 
            $objCups_pnp->id_ext = $id_prestacion; 
            $objCups_pnp->corte_adm = $corte_adm; 
            $objCups_pnp->fecha_ejecucion_atencion = $fechaActual;  
            $objCups_pnp->save();

          ///AGREGAMOS CUPS PNP PARA LA TABLA DE EMERGENCIA AGREGAMOS CUPS PNP PARA LA TABLA DE EMERGENCIA/// 
            $valor_cups_pnp=($objCups_pnp->codigo_ipress."-".$objCups_pnp->año ."-". $objCups_pnp->intramural_extramural 
            ."-". $objCups_pnp->no_referidos_referidos ."-". $objCups_pnp->tipo_atencion ."-". str_pad($id_prestacion, 9, '0', STR_PAD_LEFT)) ;
            
            $objPrestacion->cups_pnp = $valor_cups_pnp;
            $objPrestacion->save();

          /*********************************************************************/
          // if($request->id_servicio == 13 && $request->id_establecimiento == 76 && $request->od_pio!=''){
          //   $this->agregarOftalmologia($request, $id_prestacion);
          // }
          /********************************************************************/
          $id_intervencion_quirurgica = $request->id_intervencion_quirurgica;
          $id_user = Auth::user()->id;

          //dd($request->tipo_profesional_cirujano);

          if (isset($request->id_cirujano)) :
            $tipo_personal = 0; 
            $model_cq_profesional = new \App\Models\CqxProfesional;
            foreach ($request->id_cirujano as $key => $value) :
              if ($request->id_cirujano[$key] != "") {
                $p_id_medico = $request->id_cirujano[$key];
                $p_id_medico_cargo = (isset($request->tipo_profesional_cirujano[$key]) && $request->tipo_profesional_cirujano[$key] > 0 ? $request->tipo_profesional_cirujano[$key] : 0);
                $response = $model_cq_profesional->RegistrarProfesionalesPreQx($id_intervencion_quirurgica, $p_id_medico, $p_id_medico_cargo, $id_user, $tipo_personal, 0);
              }
            endforeach;
          endif;

          $model_intraoperatorio = new IntraOperatorio();
          $intervencion = $request->id_intervencion_quirurgica;
          if($model_intraoperatorio::where('id_intervencion',$intervencion)->exists()){
            $data_intra = [
              'anestesia_inicio' => $request->anestesia_inicio2 ?: null,
              'anestesia_termino' => $request->anestesia_termino2 ?: null,
              'cirugia_inicio'  => null,
              'cirugia_termino' => null,
              'tipo_real'       => null,
              // 'cirugia_inicio' => $request->cirugia_inicio2 ?: null,
              // 'cirugia_termino' => $request->cirugia_termino2 ?: null,
              'hora_llegada_urpa' => $request->hora_llegada_urpa2 ?: null,
              //'tipo_real' => $request->tipo_real2 ?: null,
              'salida_paciente' => $request->salida_paciente2 ?: null,
              'complicaciones' => $request->complicaciones ?: null,
              'id_cqx_tipo_anestesia' => $request->id_cqx_tipo_anestesia2 ?: null,
              'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
              'updated_us' => Auth::user()->id
            ];
            //dd($data_intra);
            $actualizar = $model_intraoperatorio->actualizar($data_intra, $intervencion);
          }
          //$this->registrar_alta_medica_hospitalizacion_AQ($request);

          
          // $fecha_hora_inicio = Carbon::createFromFormat('d-m-Y H:i', $request->fecha_inicio . ' ' . $request->appt)->format('Y-m-d H:i:s');
          // dd($fecha_hora_inicio);
          $model_intervencion = CqxIntervencion::find($request->id_intervencion_quirurgica);
          /****************Actualiza Atencion Hopitalizacion*********************************************/
          if(!empty($request->registrar_destino) && (int) $request->registrar_destino === 1){
            if(!empty($request->id_tipo_egreso) && (int) $request->id_tipo_egreso === 2){//DESTINO HOSPITALIZACION
              $estado_ubicacion = "";
              if ($request->id_cama_actual == $request->id_cama_destino):
                $estado_ubicacion = "1";
              elseif ($request->id_pabellon_actual == $request->id_pabellon_destino):
                $estado_ubicacion = "2";
              else:
                $estado_ubicacion = "3";
              endif;

              $fecha_hora_inicio = Carbon::parse($request->fecha_inicio)->format('d-m-Y');
              $model_hospitalizacion = new \App\Models\Hospitalizacione;
              $hospitalizacion = \App\Models\Hospitalizacione::find($id_hospitalizacion);
              if ($hospitalizacion && $hospitalizacion->id_cama_estado === null) {
                $model_hospitalizacion->ActualizaEstadoHospitalizacion($id_hospitalizacion,1);
              }
              
              $id_condicion_alta = (isset($request->hospitalizacion_id_condicion_alta)) ? $request->hospitalizacion_id_condicion_alta : '';
              DB::update('UPDATE hospitalizaciones SET id_tipo_egreso = ?,id_condicion_alta = ? WHERE id = ?', [(int)$request->id_tipo_egreso, $id_condicion_alta, $id_hospitalizacion]);

              $model_cambio_cama = new CambioCama();
              $model_cambio_cama->RegistrarCamaAtencionQxAQ(
                $request->id_hospitalizacion, 
                $request->id_cama_actual, 
                $estado_ubicacion, 
                $fecha_hora_inicio, 
                $request->id_cama_destino, 
                $id_prestacion, 
                0, 
                Carbon::now(),
                $request->id_upss_encargado_atencion,
                $request->id_servicio,
                $request->id_upss_encargado,
                $request->id_servicio_encargado
              );
              /**********ACTUALIZACION DE NUEVOS CAMPOS*/
              $cama = \App\Models\Cama::find($request->id_cama_destino);
              $consultorio = \App\Models\Consultorio::find($request->id_servicio_encargado);
              $piso = \App\Models\Piso::where('id_pabellon', $request->id_pabellon_destino)->first();

              $hospitalizacion->id_pabellon = $request->id_pabellon_destino ?: null;
              $hospitalizacion->id_piso = $piso ? $piso->id : null;
              $hospitalizacion->id_consultorio_hosp = $request->id_servicio_encargado ?: null;
              $hospitalizacion->nombre_consultorio = $consultorio ? $consultorio->nombre : null;
              $hospitalizacion->id_cuarto = $cama ? $cama->id_cuarto : null;
              $hospitalizacion->id_consultorio = $request->id_servicio_encargado ?: null;
              $hospitalizacion->save();

              //NUEVOS CAMPOS EN CQX_INTERVENCIONES YA NO USARA PARA UNA ALTA DE HOSPITALIZACION
              $model_intervencion->id_tipo_egreso=(int)$request->id_tipo_egreso;
              $model_intervencion->id_condicion_alta=$id_condicion_alta;
              //FIN CAMPOS EN CQX_INTERVENCIONES YA NO USARA PARA UAN ALTA DE HOSPITALIZACION 
              $model_intervencion->save();
              /*****************************************/
              
            }else if(!empty($request->id_tipo_egreso) && (int) $request->id_tipo_egreso === 1){//DESTINO DOMICILIARIA
              $model_hospitalizacion = new \App\Models\Hospitalizacione;
              $date = Carbon::now();
              $fecha_salida = $date->format('d/m/Y h:i A');
              $model_hospitalizacion->ActualizaEstadoHospitalizacion($id_hospitalizacion,6);
              //ACTUALIZA LOS DATOS DE DOMICILIARIA EN LA TABLA HOSPITALIZACIONES
              // $fechaEgreso = Carbon::createFromFormat('d-m-Y', $request->fecha_egreso)->format('Y-m-d H:i:s.u'); 
              $fechaEgreso = Carbon::parse($request->fecha_egreso)->endOfDay()->format('Y-m-d H:i:s');
              // $horaEgreso = Carbon::createFromFormat('H:i:s', $request->hora_egreso)->format('H:i:s.u');
              $horaEgreso = Carbon::parse($request->hora_egreso);
              $model_hospitalizacion->actualizarDatosDomiciliaria($id_hospitalizacion,(int)$request->id_tipo_egreso,$request->id_condicion_alta,$fechaEgreso,$horaEgreso, Auth::user()->id);
              //$model_intervencion = new \App\Models\CqxIntervencion;
              //$model_intervencion->RegistrarAltaCqAQ($request->id_intervencion_quirurgica, $request->id_cama_destino, $request->id_medico, Carbon::now());
              DB::update('UPDATE hospitalizaciones SET id_condicion_alta = ?,fecha_salida = ? WHERE id = ?', [$request->id_condicion_alta,$fecha_salida,$id_hospitalizacion]);

            
              //NUEVOS CAMPOS EN CQX_INTERVENCIONES YA NO USARA PARA UNA ALTA DE HOSPITALIZACION
              $model_intervencion->id_tipo_egreso=(int)$request->id_tipo_egreso;
              $model_intervencion->id_condicion_alta=$request->id_condicion_alta;
              //FIN CAMPOS EN CQX_INTERVENCIONES YA NO USARA PARA UAN ALTA DE HOSPITALIZACION 
              $model_intervencion->save();
            }
          }
          if ($model_intervencion) {
              $model_intervencion->flag_atencion_qx = 1;
              $model_intervencion->id_tipo_atencion_origen = $request->id_tipo_atencion_origen;
              if ((int)$request->id_tipo_egreso == 1 || (int)$request->id_tipo_egreso == 2) {
                  $model_intervencion->flag = 4;
              }
              $model_intervencion->emergencia_tipo_cirugia=$request->id_tipo_cirugia;
              $fecha_intervencion = Carbon::parse($request->fecha_intervencion)->format('Y-m-d H:i:s');
              $model_intervencion->fecha_intervencion=$fecha_intervencion;
              
              $model_intervencion->id_sala_intervencion = !empty($request->id_sala_intervencion)?$request->id_sala_intervencion:null;
              $model_intervencion->id_tipo_anestesia = !empty($request->id_tipo_anestesia)?$request->id_tipo_anestesia:null;
              $model_intervencion->save();
          }
          //DB::update('UPDATE cqx_intervenciones SET flag_atencion_qx = 1 WHERE id = ?', [$request->id_intervencion_quirurgica]);
          // if (isset($request->id_procedimiento_intervencion)) :
          //   $model_cq_procedimiento = new \App\Models\CqxProcedimiento();
          //   foreach ($request->id_procedimiento_intervencion as $key => $value) :
          //     if ($request->id_procedimiento_intervencion[$key] != "") {
          //       $p_id_procedimiento = $request->id_procedimiento_intervencion[$key];
          //       $model_cq_procedimiento->RegistrarProcedimientoPreQx($request->id_intervencion_quirurgica, $p_id_procedimiento, Auth::user()->id, $id_cita,2);
          //     }
          //   endforeach;
          // endif;
    
          DB::commit(); 
          return response()->json(['success' => true,'id_user'=>$request->id_user,'id_prestacion'=>$id_prestacion,'id_intervencion_quirurgica'=>$id_intervencion_quirurgica,'id_cita'=>$id_cita,'id_hospitalizacion'=>$id_hospitalizacion]);
      } catch (\Exception $e) {
          DB::rollback();
          Log::error('Error al querer guardar datos de Atencion Quirurgica', [
              'message' => $e->getMessage(),
              'code' => $e->getCode(),
              'file' => $e->getFile(),
              'line' => $e->getLine(),
              'trace' => $e->getTraceAsString(),
          ]);
          return response()->json([
              'success' => false, 'msg' => 'No se pudo guardar la atención quirúrgica.'
          ], 500);
    } catch (\Throwable $th) {
        DB::rollback();
        throw $th;
    }
    // if ($id_prestacion == 0) {
    //   Session::flash('flash_message', 'No se pudo registrar la Prestacion, hubo un problema con la conexión de internet de su establecimiento PNP, vuelva a intentarlo nuevamente');
    //   return redirect('/cq_lista_pendiente');
    // } else {
    //   Session::flash('flash_message', 'Prestación exitosa');
    //   return redirect('/observar_prestacion/' . $request->id_user . '/' . $id_prestacion.'/'.$request->id_intervencion_quirurgica);
    // } 
  }
  
  public function registro_receta_prestacion(Request $request)
  {
    //dd($request);
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    
    $id_prestacion = $request->id_prestacion;
    $id_intervencion = $request->id_intervencion;
    $p_detalle = "";
    $p_detalle .= "{";
    if (isset($request->id_producto)) :
      foreach ($request->id_producto as $key => $value) :
        $p_detalle .= "{";
        $p_detalle .= $request->id_producto[$key] . ",";
        $p_detalle .= $request->tiempo_periodo[$key] . ",";
        $p_detalle .= $request->nro_med_solictados[$key] . ",";
        $p_detalle .= (isset($request->nro_med_entregados[$key]) && $request->nro_med_entregados[$key] != "") ? $request->nro_med_entregados[$key] : 0;
        $p_detalle .= ",";
        $p_detalle .= (isset($request->nro_stocks_almacen[$key]) && $request->nro_stocks_almacen[$key] != "") ? $request->nro_stocks_almacen[$key] : 0;
        $p_detalle .= ",";
        $p_detalle .= (isset($request->nro_stocks_establecimiento[$key]) && $request->nro_stocks_establecimiento[$key] != "") ? $request->nro_stocks_establecimiento[$key] : 0;
        $p_detalle .= ",";
        $p_detalle .= (isset($request->nro_stocks[$key]) && $request->nro_stocks[$key] != "") ? $request->nro_stocks[$key] : 0;
        $p_detalle .= ",";
        $p_detalle .= (isset($request->posologia[$key]) && $request->posologia[$key] != "") ? str_replace(",", "###", $request->posologia[$key]) : "-";
        $p_detalle .= ",";
        $p_detalle .= (isset($request->miSelectProd[$key]) && $request->miSelectProd[$key] != "") ? $request->miSelectProd[$key] : 0;
        $p_detalle .= ",";
        $p_detalle .=(isset($request->id_via_administracion[$key]) && $request->id_via_administracion[$key] != "") ? $request->id_via_administracion[$key] : 0;
        $p_detalle .= "},";
      endforeach;
      $p_detalle = substr($p_detalle, 0, -1);
    endif;
    $p_detalle .= "}";
    $model_prestaciones = new Prestaciones();
    $receta = new Receta_Vale();
    $diagnosticos = $model_prestaciones->GetDiagnosticoByIdPrestacion($id_prestacion);
    $p_diagnostico = "";
    $p_diagnostico .= "{";
    foreach ($diagnosticos as $row) :
      $p_diagnostico .= "{";
      $p_diagnostico .= $row->id_diagnostico . ",";
      //$p_diagnostico.=$row->id_tipo_diagnostico;
      $p_diagnostico .= ($row->id_tipo_diagnostico > 0) ? $row->id_tipo_diagnostico : "0";
      $p_diagnostico .= "},";
    endforeach;
    $p_diagnostico = substr($p_diagnostico, 0, -1);
    $p_diagnostico .= "}";

    $id_receta_vale = 0;
    $id_anestesiologo = 0;
    $obs_registro = "";
    $id_receta_vale_bus = 0;
    $id_admision = 0;
    $tipo_via = "";
    $sub_via = "";
    $final_via = "";
    $nro_guia = "";
    $interconsulta = "";
    $interconsulta_dias = 0;
    $nro_receta = "";
    $digitos_adicionales = "";
    $id_tipo_num = 0;
    $estado_receta = 2;
    $prestacion = Prestaciones::find($id_prestacion);
    $subconsultorio = SubConsultorio::find($prestacion->id_sub_consultorio);
    $cita = Cita::find($prestacion->id_cita);
    $asegurado = Asegurado::find($cita->id_asegurado);
    $titular = Asegurado::find($cita->id_titular);
    //$historia = Asegurado_historia::find($cita->id_asegurado);
    $historia = Asegurado_historia::where('id_asegurado',$cita->id_asegurado)->where('id_establecimiento',$cita->id_establecimiento)->first();

    //echo $p_diagnostico;exit();
    //print_r($prestacion);exit();

    $id_medico = $prestacion->id_medico;
    $id_tiporeceta = $prestacion->id_tipo_atencion;
    $codigo_upss = $prestacion->codigo_upss;
    $fecha_atencion = $request->fecha_expedicion;
    $id_consultorio = $subconsultorio->id_consultorio;
    $grado = $cita->grado;
    $tipo_beneficiario = $cita->parentesco;

    $dni_beneficiario = $asegurado->nro_doc_ident;
    $cip_beneficiario = $asegurado->cip;
    $tipodoc_beneficiario = $asegurado->tipo_doc_ident;
    $nombre_beneficiario = $asegurado->nombre;
    $paterno_beneficiario = $asegurado->paterno;
    $materno_beneficiario = $asegurado->materno;
    $sexo = $asegurado->sexo;
    $fecha_nacimiento = $asegurado->fecha_nac;

    $dni_titular = isset($titular->nro_doc_ident) ? $titular->nro_doc_ident : "";
    $tipdoc_titular = isset($titular->tipo_doc_ident) ? $titular->tipo_doc_ident : "";
    $nombre_titular = isset($titular->nombre) ? $titular->nombre : "";
    $paterno_titular = isset($titular->paterno) ? $titular->paterno: "";
    $materno_titular = isset($titular->materno) ? $titular->materno: "";

    $nro_historia = isset($historia->nro_historia) ? $historia->nro_historia : "";
    $hora = date('H');
    //echo $hora;exit();
    if ($hora > 6 && $hora < 13) $turno = "M";
    elseif ($hora >= 13 && $hora < 19) $turno = "T";
    elseif ($hora >= 19 && $hora <= 24) $turno = "N";
    else $turno = "MD";
    //echo $turno;exit();

    /*********************************************************************/
    $model_procedimiento = new \App\Models\Procedimiento;
    if (isset($request->id_examen_lab)) :
      $p_laboratorio = "";
      $p_laboratorio .= "{";
      foreach ($request->id_examen_lab as $key => $value) :
        if ($request->id_examen_lab[$key] != "") {
          $p_laboratorio .= "{";
          $p_laboratorio .= $request->id_examen_lab[$key] . ",";
          $p_laboratorio .= (isset($request->examen_lab_observacion[$key]) && $request->examen_lab_observacion[$key] !="") ? $request->examen_lab_observacion[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->examen_lab_cantidad[$key]) && $request->examen_lab_cantidad[$key] > 0) ? $request->examen_lab_cantidad[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->miSelectLab[$key]) && $request->miSelectLab[$key] !="") ? $request->miSelectLab[$key] : "0";
          $p_laboratorio .= "},";
        }
      endforeach;
      if (strlen($p_laboratorio) > 1) $p_laboratorio = substr($p_laboratorio, 0, -1);
      $p_laboratorio .= "}";
      if($request->id_examen_lab[0]!=""):
        $id_procedimiento_lab = $model_procedimiento->RegistrarProcedimiento($request->id_user, $id_prestacion, $request->id_establecimiento, $request->fecha_expedicion, $p_laboratorio, 2, 0);
      endif;
    endif;
    if (isset($request->id_examen_rad)) :
      $p_radiologia = "";
      $p_radiologia .= "{";
      foreach ($request->id_examen_rad as $key => $value) :
        if ($request->id_examen_rad[$key] != "") {
          $p_radiologia .= "{";
          $p_radiologia .= $request->id_examen_rad[$key] . ",";
          $p_radiologia .= (isset($request->examen_rad_cantidad[$key]) && $request->examen_rad_cantidad[$key] > 0) ? $request->examen_rad_cantidad[$key] : "0";
          $p_radiologia .= ",";
          $p_radiologia .= (isset($request->examen_rad_observacion[$key]) && $request->examen_rad_observacion[$key] !="") ? $request->examen_rad_observacion[$key] : "0";
          $p_radiologia .= ",";
          $p_radiologia .= (isset($request->id_comparativo[$key]) && $request->id_comparativo[$key] !="") ? $request->id_comparativo[$key] : "0";
          $p_radiologia .= ",";
          $p_radiologia .= (isset($request->id_lado[$key]) && $request->id_lado[$key] !="") ? $request->id_lado[$key] : "0";
          $p_radiologia .= ",";
          $p_radiologia .= (isset($request->miSelectRad[$key]) && $request->miSelectRad[$key] !="") ? $request->miSelectRad[$key] : "0";
          $p_radiologia .= "},";
        }
      endforeach;
      if (strlen($p_radiologia) > 1) $p_radiologia = substr($p_radiologia, 0, -1);
      $p_radiologia .= "}";
      if($request->id_examen_rad[0]!=""):
        $id_procedimiento_rad = $model_procedimiento->RegistrarProcedimiento($request->id_user, $id_prestacion, $request->id_establecimiento, $request->fecha_expedicion, $p_radiologia, 3, 0);
      endif;
    endif;

    /***************************************************************************/
    $model_interconsulta = new \App\Models\Interconsulta;
    
    if (isset($request->id_servicio_interconsulta)) :
      $p_interconsulta = "";
      $p_interconsulta .= "{";
      foreach ($request->id_servicio_interconsulta as $key => $value) :
        if ($request->id_servicio_interconsulta[$key] != "") {
          $p_interconsulta .= "{";
          $p_interconsulta .= $request->id_servicio_interconsulta[$key] . ",";
          $p_interconsulta .= $request->nombre_servicio_interconsulta[$key] . ",";
          $p_interconsulta .= $request->motivo_interconsulta[$key] . ",";
          $p_interconsulta .= $request->resumen_interconsulta[$key] ;
          $p_interconsulta .= "},";
        }
      endforeach;
      if (strlen($p_interconsulta) > 1) $p_interconsulta = substr($p_interconsulta, 0, -1);
      $p_interconsulta .= "}"; 

      if($request->id_servicio_interconsulta[0]!=""):
        $id_servicio_interconsulta = $model_interconsulta->RegistrarInterconsulta($id_prestacion, $asegurado->id, $prestacion->id_tipo_atencion, $prestacion->fecha_atencion, $prestacion->id_medico, $prestacion->id_establecimiento, $p_interconsulta);
      endif;
    endif;
  
    $model_referencia = new \App\Models\Referencia;

    if (isset($request->id_establecimiento_referencia)) :
      if($request->id_establecimiento_referencia!=""):
        if($request->id_uci==2){
          $id_uci_cv=0;
        }
        else{
          $id_uci_cv=$request->id_uci_cv;
        }
        if($request->id_ambulancia==2){
          $id_ambulancia_ref=0;$id_tipo_ambulancia=0;
        }
        else{
          $id_ambulancia_ref=$request->id_ambulancia_ref;$id_tipo_ambulancia=$request->id_tipo_ambulancia;
        }

        $id_referencia = $model_referencia->RegistrarReferencia($id_prestacion, $asegurado->id, $prestacion->id_tipo_atencion, $prestacion->fecha_atencion, $prestacion->id_medico, $request->id_establecimiento_referencia, $request->id_uci,$id_uci_cv,$request->id_ambulancia,$id_ambulancia_ref,$id_tipo_ambulancia,$request->id_pasaje,$request->id_acompanante,$request->id_situacion,$request->observacion_referencia,$request->id_establecimiento);

        if($prestacion->id_ext!=0):
          $model_emergencia = new \App\Models\Emergencia;
          $update=$model_emergencia->ActualizaEstado($id_emergencia,4);
        endif;
        
      endif;
    endif;

    /***************************************************************************/

     //============ Medicamentos Editados o eliminados ============
     for ($i=0; $i < count($request->id_producto_receta_editar) ; $i++) {
      switch ($request->accionRowProductoAnestesia[$i]) {
        case 'edit':
          //dd('Entro en Edit');
          $rowaffect2 = DB::table('producto_recetas')
          ->where('producto_recetas.id', '=', $request->id_producto_receta_editar[$i])
          ->update([
          'cantidad_prescrita' => $request->cantidad_prescrita_editar[$i],
          'tiempo_periodo' => $request->tiempo_periodo_editar[$i],
          'posologia' => $request->posologia_editar[$i],
          'id_via_administracion' => $request->id_via_administracion[$i]
          ]);
          break;
        
        case 'delete':  //Validar el cambio de estado a cero
          //dd('Entro en Delete');
          $rowaffect2 = DB::table('producto_recetas')
          ->where('producto_recetas.id', '=', $request->id_producto_receta_editar[$i])
          ->update([
          'estado' => 0
          ]);
          break;
      }
      
    }
    
    if (isset($request->id_producto)) :
	$id_receta_vale = $receta->postRegistrarRecetaValeNuevoConDiagnostico($id_receta_vale, $request->id_farmacia_receta, $request->id_user, $id_consultorio, $nro_receta, $digitos_adicionales, $dni_beneficiario, $cip_beneficiario, $tipodoc_beneficiario, $nombre_beneficiario, $paterno_beneficiario, $materno_beneficiario, $tipo_beneficiario, $dni_titular, $tipdoc_titular, $nombre_titular, $paterno_titular, $materno_titular, $request->fecha_expedicion, $fecha_atencion, $nro_historia, $grado, $id_medico, $id_anestesiologo, $id_tiporeceta, $turno, $obs_registro, $id_receta_vale_bus, $id_admision, $tipo_via, $sub_via, $final_via, $nro_guia, $interconsulta, $interconsulta_dias, $id_prestacion, $id_tipo_num, $estado_receta, $p_detalle, $p_diagnostico, $sexo, $fecha_nacimiento, $codigo_upss);      
  //$id_receta_vale = $receta->postRegistrarRecetaVale($id_receta_vale, $request->id_farmacia_receta, $request->id_user, $id_consultorio, $nro_receta, $digitos_adicionales, $dni_beneficiario, $cip_beneficiario, $tipodoc_beneficiario, $nombre_beneficiario, $paterno_beneficiario, $materno_beneficiario, $tipo_beneficiario, $dni_titular, $tipdoc_titular, $nombre_titular, $paterno_titular, $materno_titular, $request->fecha_expedicion, $fecha_atencion, $nro_historia, $grado, $id_medico, $id_anestesiologo, $id_tiporeceta, $turno, $obs_registro, $id_receta_vale_bus, $id_admision, $tipo_via, $sub_via, $final_via, $nro_guia, $interconsulta, $interconsulta_dias, $id_prestacion, $id_tipo_num, $estado_receta, $p_detalle, $p_diagnostico, $sexo, $fecha_nacimiento, $codigo_upss);
    else :
      $id_receta_vale = 1;
    endif;

    if ($id_receta_vale > 0) {
      if ($request->id_sub_consultorio != 976) {//telecon
        Session::flash('flash_message', 'Prestación exitosa');
        return redirect('/observar_prestacion/' . $request->id_user . '/' . $id_prestacion . '/' . $id_intervencion);  
      } else {
        Session::flash('flash_message', 'Prestación exitosa');
        return redirect('/observar_prestacion_teleconsulta/' . $request->id_user . '/' . $id_prestacion);
      }
    }
  }

  // public function registrar_alta_medica_hospitalizacion_AQ(Request $request){
  // if (!Auth::check()) :
  //   return redirect('/auth/login');
  // endif; 

  // $date = Carbon::now();
  // $fechaActual = $date->format('d-m-Y');
  // $horaActual = $date->format('H:i:s');
  // $fecha_cierre = $request->fecha_egreso;
  // $hora_cierre = $request->hora_egreso;
  // $fecha_cierre = date('Y-m-d', strtotime($fecha_cierre));
  // $date = $fecha_cierre;
  // $fechaActual = $fecha_cierre;
  // $horaActual = $hora_cierre;
  // $id_user =  Auth::user()->id;
  // $id_hospitalizacion = $request->id_hospitalizacion;

  // $model_emergencia = Hospitalizacione::where('id', '=', $request->id_hospitalizacion)->first();
  // $emergencia_prestacion = Prestaciones::where('id_ext', '=', $request->id_hospitalizacion)->where('id_tipo_atencion', '=', 3)->first();
  // //dd($emergencia_prestacion);

  // $existeevolucion = "SELECT count(e.id)  from hospitalizaciones e
  //                     inner join evaluaciones e2 on e2.id_emergencia = e.id
  //                     where e.id = '$request->id_hospitalizacion' and e2.id_tipo_atencion = 3 and e2.id_estado_reg <> 0";
  // $existeevolucion = DB::select($existeevolucion);
  // $existeevolucion = $existeevolucion[0]->count;

  // if($existeevolucion == 0){
  //   $fecha_hora_ingreso=$model_emergencia->fecha_atencion;
  //   $id_evaluacion = 0;
  // }else{
  //   $datoevalucion ="SELECT e2.* from hospitalizaciones e
  //   inner join evaluaciones e2 on e2.id_emergencia = e.id
  //   where e.id = '$request->id_hospitalizacion' and e2.id_tipo_atencion = 3 and e2.id_estado_reg <> 0 order by e2.created_at desc";
  //   $datoevalucion = DB::select($datoevalucion);
  //   $fecha_hora_ingreso = $datoevalucion[0]->created_at;
  //   $id_evaluacion = $datoevalucion[0]->id;
  // }

  // $unido = ($fecha_cierre . " " . $hora_cierre);
  // $resulta=((strtotime($unido))-(strtotime($fecha_hora_ingreso)))/86400;

  // if($resulta <= 0){
  //   Session::flash('flash_message', 'NO SE REGISTRO EL ALTA - LA FECHA DE ALTA INGRESADA '.$unido.' ES MENOR A LA FECHA DE LA ULTIMA ATENCION '.$fecha_hora_ingreso.' - FORMATO DE FECHA 24 HORAS, MODIFIQUE LA FECHA DE ALTA');
  //   return redirect('/atencion_quirurgica/'. $id_user . '/' . $request->id_hospitalizacion); 
  // }

  // $resulta_hora=((strtotime($unido))-(strtotime($fecha_hora_ingreso)))/43200;
  // $codigo_cpms = "";
  // $emergencia_model = new \App\Models\Emergencia;

  // /**********Calcula estancia*************/
  // $inicio= ($model_emergencia->fechaingreso);
  // $final= ($date);
  // $inicios= strtotime($model_emergencia->fecha_ingreso);
  // $fecha_alta=explode(" ",$date);
  // $hora_alta=explode(" ",$horaActual);
  // $fecha_finals=$fecha_alta[0].' '.$hora_alta[0];
  // $finals= strtotime($fecha_finals);
  // $resulta=($finals-$inicios)/86400;
  // $resulta=round($resulta);

  // if($resulta == "0"){
  //   $dia_estancia = '1';
  // }else{
  //   $dia_estancia=$resulta;
  // }

  // $update_alta=$emergencia_model->InsertaEstancia($request->id_hospitalizacion,$emergencia_prestacion->id,$id_evaluacion,3,$codigo_cpms,$model_emergencia->fecha_atencion,$id_user,$dia_estancia); 

  // $model_emergencia->cpms_alta = $codigo_cpms;
  // $model_emergencia->fecha_salida = $date;
  // $model_emergencia->hora_egreso = $horaActual;
  // $model_emergencia->fecha_alta_medica = $date;
  // $model_emergencia->hora_alta_medica = $horaActual;
  // $model_emergencia->id_tipo_egreso = $request->id_tipo_egreso;
  // $model_emergencia->flag_egreso = '1';
  // $model_emergencia->estado = 6;
  // $model_emergencia->id_medico_alta = $id_user;
  // $model_emergencia->egreso_resumen = $request->egreso_resumen;
  // $model_emergencia->egreso_examen_clinico = $request->egreso_examen_clinico;
  // $model_emergencia->egreso_examen_auxiliar = $request->egreso_examen_auxiliar;
  // $model_emergencia->egreso_evolucion = $request->egreso_evolucion;
  // $model_emergencia->egreso_tratamiento = $request->egreso_tratamiento;
  // $model_emergencia->egreso_procedimiento = $request->egreso_procedimiento;
  // $model_emergencia->egreso_complicacion = $request->egreso_complicacion;
  // $model_emergencia->egreso_medidas_generales = $request->egreso_medidas_generales;
  // $model_emergencia->egreso_medicamentos_prescritos = $request->egreso_medicamentos_prescritos;
  // $model_emergencia->egreso_pronostico = $request->egreso_pronostico;

  // if($request->id_tipo_egreso == '1'){//alta
  //   $model_emergencia->id_condicion_alta = $request->id_condicion_alta;
  //   $model_emergencia->id_establecimiento_traslado = "0";
  //   $model_emergencia->obs_traslado = '';
  //   $model_emergencia->especialida_destino = "0";
  //   $model_emergencia->especialidad_destino_otro = "0";
  //   $model_emergencia->condicion_traslado = "0";
  //   $model_emergencia->necropsia = "0";
  //   $model_emergencia->causas_muerte = "0";
  //   $model_emergencia->observacion_egreso = '';

  //   // Registro de los servicios de concurrencia
  //   if ($request->id_servicio_concurrencia != null):
  //     foreach ($request->id_servicio_concurrencia as $key => $value):
  //       $model_hospitalizacion_concurrencia = new App\Models\HospitalizacionConcurrencia;
  //       $model_hospitalizacion_concurrencia->id_hospitalizacion = $request->id_hospitalizacion;
  //       $model_hospitalizacion_concurrencia->id_servicio = $request->id_servicio_concurrencia[$key];
  //       $model_hospitalizacion_concurrencia->id_estado_reg = 1;
  //       $model_hospitalizacion_concurrencia->created_us = Auth::user()->id;
  //       $model_hospitalizacion_concurrencia->save();
  //     endforeach;
  //   endif;

  // }else if($request->id_tipo_egreso == '3'){//traslado
  //   $model_emergencia->id_condicion_alta = "3";
  //   $model_emergencia->id_establecimiento_traslado = $request->id_establecimiento_traslado;
  //   $model_emergencia->necropsia = "0";
  //   $model_emergencia->causas_muerte = "0";
  //   $model_emergencia->observacion_egreso = '';
  //   $model_emergencia->id_tipo_traslado = $request->id_tipo_traslado;
  // }else if($request->id_tipo_egreso == '4'){//fallecido
  //   $model_emergencia->id_condicion_alta = "6";
  //   $model_emergencia->id_establecimiento_traslado = "0";
  //   $model_emergencia->obs_traslado = '';
  //   $model_emergencia->especialida_destino = "0";
  //   $model_emergencia->especialidad_destino_otro = "0";
  //   $model_emergencia->condicion_traslado = "0";
  //   $model_emergencia->necropsia = $request->necropsia;
  //   $model_emergencia->causas_muerte = $request->necropsia == "SI" ? $request->causas_muerte : "";
  //   $model_emergencia->observacion_egreso = '';
  //   $model_emergencia->fecha_defuncion = $request->fecha_defuncion;
  //   $model_emergencia->hora_defuncion = $request->hora_defuncion;
  // }else if($request->id_tipo_egreso == '7'){//retiro voluntario
  //   $model_emergencia->id_condicion_alta = "4";
  //   $model_emergencia->id_establecimiento_traslado = "0";
  //   $model_emergencia->obs_traslado = '';
  //   $model_emergencia->especialida_destino = "0";
  //   $model_emergencia->especialidad_destino_otro = "0";
  //   $model_emergencia->condicion_traslado = "0";
  //   $model_emergencia->necropsia = "0";
  //   $model_emergencia->causas_muerte = "0";
  //   $model_emergencia->observacion_egreso = $request->obs_retiro;
  // }else if($request->id_tipo_egreso == '6'){//fuga
  //   $model_emergencia->id_condicion_alta = "5";
  //   $model_emergencia->id_establecimiento_traslado = "0";
  //   $model_emergencia->obs_traslado = '';
  //   $model_emergencia->especialida_destino = "0";
  //   $model_emergencia->especialidad_destino_otro = "0";
  //   $model_emergencia->condicion_traslado = "0";
  //   $model_emergencia->necropsia = "0";
  //   $model_emergencia->causas_muerte = "0";
  //   $model_emergencia->observacion_egreso = $request->obs_fuga;
  // }

  // if(isset($model_emergencia->id_cama_estado)){
  //   $model_cama = Cama::where('id', '=', $model_emergencia->id_cama_estado)->first();
  //   $model_cama->estado = 0; // 1 - No disponible (Estos datos son los que van a ir a produccion)
  //   $model_cama->situacion = '1'; // '5' - En Desinfeccion (Estos datos son los que van a ir a produccion)
  //   $model_cama->save();
  // }

  // $model_emergencia->cod_siteds_susalud = $request->codigo_siteds;
  // $model_emergencia->updated_at_cod_siteds = Carbon::now();
  // $model_emergencia->updated_us_cod_siteds = Auth::user()->id;
  // $model_emergencia->save(); 

  // $model_trama = new \App\Models\Hospitalizacione;
  // $enviado_dato = $model_trama->enviartrama($id_hospitalizacion);

  // Session::flash('flash_message', 'Alta Medica exitosa');
  //   return redirect('/all_atenciones_by_hospitalizacion/');
  // }

  public function AQ_buscar_dia_estancia_ajax(Request $request){ 
    $id_asegurado = $request->id_asegurado;
    $fecha_egreso = $request->fecha_egreso;
    $hora_egreso = $request->hora_egreso;
    $model_hospitalizacione= new Hospitalizacione();
    $datos_estancia = $model_hospitalizacione->GetDiasEstancia($id_asegurado,$fecha_egreso,$hora_egreso);
    return json_encode($datos_estancia);    
  }

 public function invalidar_receta_digital_quirurgica(Request $request)
 {

   if (!Auth::check()) :
     return redirect('/auth/login');
   endif;

   $id_receta = $request->id_receta;
   $id_user = $request->id_user;
   $id_prestacion = $request->id_prestacion;
   $id_intervencion = $request->id_intervencion;
   $id_farmacia = $request->id_farmacia;
   $receta = Receta_vale::find($id_receta);
   if ($receta->estado == 1) {
     Session::flash('flash_message', 'La Receta ha sido dispensada ya no se puede anular');
   } else {
     //----------------------------------------------------------------------
     $model_farmacia = new Farmacia;
     $model_stock_farmacia = new Stock_farmacia;
     $model_receta_devuelta = new \App\Models\Receta_devuelta;
     //----------------------------------------------------------------------        
     $farmacia = $model_farmacia->GetFarmaByIdfarma($id_farmacia);
     $nombre_farmacia = $farmacia->nombre;
     //----------------------------------------------------------------------            

     $existe_receta_devuelta = DB::table('receta_devueltas')
       ->where('id_receta_vale', $id_receta)
       ->where('id_farmacia', $id_farmacia)->first();

     if ($existe_receta_devuelta == false) {

       $devolver = new \App\Models\Receta_devuelta();
       //$devolver->numero_movimiento = $nro_receta;
       $devolver->id_receta_vale = $id_receta;
       $date = Carbon::now();
       $fecha = $date->format('d-m-Y');
       $devolver->fecha_devolucion = $fecha;
       $devolver->id_farmacia = $id_farmacia;
       $devolver->id_user = $id_user;
       $devolver->motivo = $request->motivo;
       $devolver->save();
    
       //$receta = Receta_vale::where('id', '=', $id_receta)->where('id_farmacia', '=', $id_farmacia)->first();
       $receta->estado = 0; //Receta devuelta o anulada
       $receta->save();
       Session::flash('flash_message', 'Receta anulada');
     }
   }

   $prestacion = Prestaciones::find($id_prestacion);
    
   if($prestacion->id_tipo_atencion == 3){
     return redirect('/observar_prestacion/' . $id_user . '/' . $id_prestacion . '/' . $id_intervencion);  
  }
 }
 
 public function observar_prestacion($id_user, $id_prestacion, $id_intervencion){
  if (!Auth::check()) :
    return redirect('/auth/login');
  endif;

  if (Auth::user()->tipo_registro == 1) {
    $modelfarma = new Farmacia;
    $model_receta = new Receta_vale;
    $model_producto = new Producto;
    $model_productos_receta = new Producto_receta;
    $model_establecimiento = new \App\Models\Establecimiento;
    $model_prestacion = new \App\Models\Prestaciones;
    $model_gestacion = new \App\Models\Gestacion;
    $model_laboratorio = new \App\Models\Laboratorio;
    $model_radiologia = new \App\Models\Radiologia;
    $model_interconsulta = new \App\Models\Interconsulta;
    $model_referencia = new \App\Models\Referencia;
    $model_kardex = new Kardexe();

    $farmacia = $modelfarma->GetFarmaByUser($id_user);
    $id_farmacia = $farmacia->id;
    $flag_receta_dias = $farmacia->flag_receta_dias;

    $fechaServidor = $model_kardex->getFechaServidor();
    $date = Carbon::now();
    $fecha_actual = $date->format('d-m-Y');

//      $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
    $prestacion = $model_gestacion->GetPrestacionById($id_prestacion);
    //dd($id_prestacion);

    $receta = $model_receta->GetDatosRecetasByIdPrestacion($id_prestacion);
    $prestaciones = $model_prestacion->GetPrestacionById($id_prestacion);
    $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
    // Inicio Centro Quirurgico
    $model_cqx_cita = new \App\Models\CqxCita;
    $riesgos_qx = $model_cqx_cita->GetRiesgos($id_prestacion);
    $interconsultas_pre_qx = $model_interconsulta->GetInterconsultaPrequirurgicaByIdPrestacion($id_prestacion);
    $data_cqx_cita = $model_cqx_cita->GetCitaByIdPrestacion($id_prestacion);
    $cqx_cita_profesionales = null;
    $cqx_cita_procedimientos = null;
    if ($data_cqx_cita != null) {
      $model_cqx_profesional = new \App\Models\CqxProfesional();
      $cqx_cita_profesionales = $model_cqx_profesional->GetByIdCqxCita($data_cqx_cita->id);
      $model_cqx_procedimiento = new \App\Models\CqxProcedimiento();
      $cqx_cita_procedimientos = $model_cqx_procedimiento->GetByIdCqxCita($data_cqx_cita->id);
    }
    // Fin Centro Quirurgico
    $diagnosticos = \App\Models\Diagnostico::where('estado',1)
    ->whereRaw('LENGTH(codigo) > 4') 
    ->get();

    if($id_prestacion>1910698):
      $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionydiag($id_prestacion, 1);
    else:
      $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 1);
    endif;
    $procedimientos = \App\Models\Procedimiento::where('estado',1)
    ->get();
    // TRAER DATA DE GESTACION:
    $prestacion_for_find_gestacion = Prestaciones::find($prestacion->id);
    $gestacion = null;
    if (isset($prestacion_for_find_gestacion->id_gestacion)) {
      $gestacion = Gestacion::find($prestacion_for_find_gestacion->id_gestacion);
      error_log("GESTACION DE PRESTACION CITA: ".(string)$gestacion->id);
    }
    // Fin insercion se gestacion

    
    $puerperio = null;
    $emergencia = Emergencia::find($prestacion_for_find_gestacion->id_ext);
    if (isset($emergencia) && isset($emergencia->id_puerperio)) {
      $puerperio = Puerperio::find($emergencia->id_puerperio);
    }

    $natis = null;
    if (isset($puerperio)) {
      $natis = Nati::where("id_puerperio", $puerperio->id)->get();
    }

    //$laboratorio = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 2);
    $laboratorio = $model_laboratorio->GetLaboratorioByIdPrestacion($id_prestacion);
    if (empty($laboratorio)) {
      $flag_laboratorio = "0";
      $flag_firmar_laboratorio = "";
    } else {
      $flag_laboratorio = "1";
      $flag_firmar_laboratorio = $laboratorio[0]->fecha_firma;
    }
    //$radiologia = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 3);
    $radiologia = $model_radiologia->GetRadiologiaByIdPrestacion($id_prestacion);
    if (count($radiologia)>0) {
      $flag_radiologia = "1";
      $flag_firmar_radiologia = $radiologia[0]->fecha_firma;
    } else {
      $flag_radiologia = "0";
      $flag_firmar_radiologia = "";
    }
    $pendiente_firma = $model_prestacion->GetDocumentosPendintesFirmarPrestacionId($id_prestacion);
    $flag_pendinte_firma = (count($pendiente_firma) > 0) ? 1 : 0;

    $id_ipress = $farmacia->id_establecimiento;
    $establecimiento = $model_establecimiento->GetById($id_ipress);
    if ($id_ipress == 1) {
      $nivel = 2;
    } else {
      $nivel = $establecimiento[0]->nivel;
    }
      $referencia = "";
      $dato_texto_obs = "";
      $dato_servicio_ref = "";

    if($prestaciones->flag_ref_cref == '1'){
      $referencia = $model_referencia->GetReferenciaByIdPrestacion($id_prestacion);
      $servicio_referencia  = "SELECT * FROM consultorios where id = '$referencia->id_servicio'";
      $servicio_referencia =  DB::select($servicio_referencia);
      $servicio_referencia = $servicio_referencia[0];

      $dato_texto_obs = $referencia->observacion_referencia;
      $dato_servicio_ref = $servicio_referencia->nombre;
    }
    
    if($prestaciones->flag_ref_cref == '2'){
      $referencia = $model_referencia->GetContraReferenciaByIdPrestacion($id_prestacion);
      $servicio_referencia  = "SELECT * FROM consultorios where id = '$referencia->id_servicio_contrareferencia'";
      $servicio_referencia =  DB::select($servicio_referencia);
      $servicio_referencia = $servicio_referencia[0];

      $dato_texto_obs = $referencia->observacion_contrareferencia;
      $dato_servicio_ref = $servicio_referencia->nombre;
    }

    //Si tiene atenciones en hospitalizacion o emergencia
    // if($prestacion->id_ext!=0):
    //   if($prestacion->tipo_atencion=='EMERGENCIA'):
    //     $model_emergencia = new \App\Models\Emergencia;
    //     $obtenerdato = $model_emergencia->GetObtenerServicio($prestacion->id_ext);
    //     $prestacion->servicio= $obtenerdato->servicio;
    //     $prestacion->consultorio= $obtenerdato->consultorio;

    //     $producto = $model_producto->getProductosByNivelAndIdServicio($nivel, 76, $id_farmacia); //76 -> servicio de hopitalizacion 
    //   else:
    //     //if($prestacion->id_tipo_atencion=3){
    //     if($prestacion->tipo_atencion=='HOSPITALIZACION'){
    //       $model_hospitalizacion = new \App\Models\Hospitalizacione;
    //       $obtenerdatoh = $model_hospitalizacion->GetObtenerDatos($prestacion->id_ext);
    //       $prestacion->servicio= $obtenerdatoh->servicio;
    //       $prestacion->consultorio= $obtenerdatoh->consultorio;
    //       $prestacion->pabellon= $obtenerdatoh->pabellon;
    //       $prestacion->piso= $obtenerdatoh->piso;
    //       $prestacion->cuarto= $obtenerdatoh->cuarto;
    //       $prestacion->cama= $obtenerdatoh->cama;
        
    //     }

    //     $producto = $model_producto->getProductosByNivelAndIdServicio($nivel, $prestacion->id_consultorio, $id_farmacia);
    //   endif; 
    // else:
    //   if($prestacion->tipo_atencion=='HOSPITALIZACION'){
    //     $prestacion->servicio= 'HOSPITALIZACION';
    //     $prestacion->consultorio= 'C1';
    //     $prestacion->pabellon= '';
    //     $prestacion->piso= '';
    //     $prestacion->cuarto= '';
    //     $prestacion->cama= '';
    //   }
    // endif;
  
    $model_cita = new \App\Models\Cita;
    $cita = $model_cita->GetCitasById($prestacion->id_cita);
 
    if($cita->id_referencia != 0){
      $model_referencia = new \App\Models\Referencia;
      $referencias = $model_referencia->GetBuscaReferenciaById($cita->id_referencia);
      $model_prestacionreferncia = new \App\Models\Prestaciones;
      $diagnostico_prestacion_referencia = $model_prestacionreferncia->GetDiagnosticoByIdPrestacion($referencias->id_prestacion);
      $procedimientos_refencia = $model_referencia->buscar_procedimiento_referencia($cita->id_referencia);
    }else{
      $diagnostico_prestacion_referencia = "";
      $referencias = "";
      $procedimientos_refencia="";
    }

    $producto = $model_producto->getProductosByNivelAndIdServicio($nivel, $prestacion->id_consultorio, $id_farmacia);
    
    $farmacias = $modelfarma->GetFarmaciaByIdEstablecimiento($id_ipress);
    $establecimientos = $model_establecimiento->ShowAllEstablecimientos();
    $interconsultas = $model_interconsulta->GetInterconsultaByIdPrestacion($id_prestacion);
    $referencia = $model_referencia->GetReferenciaByIdPrest($id_prestacion);
      
    $datos_oftalmo = null;

    if ($prestacion->id_consultorio == 13 &&  $prestacion->id_establecimiento==76) {
      //----------- INICIO OFTALMOLOGÌA -----------
      $datos_oftalmo = Oftalmologia::where('prestacion_id', '=', $id_prestacion)->first();
      //----------- FIN OFTALMOLOGÌA -----------
    }

    $datos_gastro = null;

    if ($prestacion->id_consultorio == 102 &&  $prestacion->id_establecimiento==76) {
      //----------- INICIO OFTALMOLOGÌA -----------
      $datos_gastro = Gastroenterologia::where('prestacion_id', '=', $id_prestacion)->first();
      //----------- FIN OFTALMOLOGÌA -----------
    }

    $model_cqx_dia = new CqxDia();
    $cqx_dias = $model_cqx_dia->GetAllActivos();

    $model_intraoperatorio = new IntraOperatorio;
    $cirujanos = $model_intraoperatorio->profesiones_activos(0,$id_intervencion);//CIRUJANOS

    $model_intervencion = new CqxIntervencion;
    $intervencion = $model_intervencion->GetById($id_intervencion);

    $asegurado = new Asegurado;
    $model_cq_procedimiento = new CqxProcedimiento;
    $procedimientos = $model_cq_procedimiento->GetByIdIntervencionQuirugica($id_intervencion);
    $procedimientos_intervencion = $model_intraoperatorio->procedimientos_intervencion($id_intervencion);
    
    $intraoperatorio = $this->anestesiaIdIntervencion($id_intervencion);
    
    $tipo_anestesia = DB::table('cqx_tipo_anestesia')
    ->select('id', 'descripcion')
    ->get();

    $via_administracion = DB::table('via_administracion')
    ->select()
    ->get();

    return view('frontend.centro_quirurgico.atencion_quirurgica.observar_prestacion', compact('cqx_dias','intraoperatorio','tipo_anestesia','intervencion','procedimientos_intervencion','cirujanos','farmacia','dato_texto_obs','cita','diagnostico_prestacion_referencia','procedimientos_refencia','referencias' ,'dato_servicio_ref','prestacion', 'prestaciones','diagnostico', 'procedimiento', 'medicamentos', 'id_prestacion', 'fechaServidor', 'receta', 'producto', 'farmacias', 'nivel', 'laboratorio', 'radiologia', 'flag_pendinte_firma','establecimientos','interconsultas','referencia','diagnosticos','procedimientos', 'gestacion', 'flag_laboratorio', 'flag_firmar_laboratorio','datos_oftalmo', 'puerperio', 'natis','flag_radiologia','flag_firmar_radiologia','riesgos_qx', 'interconsultas_pre_qx','datos_gastro', 'data_cqx_cita', 'cqx_cita_profesionales', 'cqx_cita_procedimientos','id_intervencion','via_administracion'));
  }
  else
  {
    return redirect('/dashboard')->withInput()->withErrors(array('No tiene el perfil de médico..!'));
  }
 }
 
public function detalleAtencionQuirurgica($id_intervencion, $id_prestacion , $id_cita, $id_hospitalizacion)
{

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    $id_establecimiento=Auth::user()->id_establecimiento;
    
    $modelfarma = new Farmacia;
    $model_receta = new Receta_vale;
    $model_producto = new Producto;
    $model_productos_receta = new Producto_receta;
    $model_establecimiento = new \App\Models\Establecimiento;
    $model_prestacion = new \App\Models\Prestaciones;
    $model_interconsulta = new \App\Models\Interconsulta;
    $modal_cita = new \App\Models\Cita;
    $model_kardex = new Kardexe();
    $model_atencione = new CqxAtencione();

    
    $farmacia = $modelfarma->GetFarmaByUser($id_user);
    $fechaServidor = $model_kardex->getFechaServidor();
    $date = Carbon::now();
    $fecha_actual = $date->format('d-m-Y');


    $model_intervencion = new CqxIntervencion();
    $datos_intervencion = $model_intervencion::select(
        'cqx_intervenciones.id_tipo_egreso',
        'cqx_intervenciones.id_condicion_alta',
        'cqx_intervenciones.id_prestacion',
        'ate.id_prestacion as id_prestacion_atqx',
        'cqx_intervenciones.id_hospitalizacion',
        'cs.nombre as sala',
        'cqx_intervenciones.id_consultorio',
        'ci.id as id_cita',
        'ate.hora_inicio_operatorio',
        'ate.hora_fin_operatorio',
        'ate.hora_duracion_operatorio')
      ->leftJoin('cq_salas as cs', 'cqx_intervenciones.id_sala_intervencion', '=', 'cs.id')
      ->leftJoin('cqx_atenciones as ate', 'cqx_intervenciones.id', '=', 'ate.id_cqx_intervencion')
      ->leftJoin('prestaciones as p', 'ate.id_prestacion', '=', 'p.id')
      ->leftJoin('citas as ci', 'p.id_cita', '=', 'ci.id')
      ->where('cqx_intervenciones.id',$id_intervencion)->first();
    $cita =  $modal_cita->GetCitasById($datos_intervencion->id_cita);

    $titular = Asegurado::select('tipo_doc_ident','dni_titular', 'nombre', 'paterno', 'materno', 'cip', 'grado', 'situacion_paciente', 'telefono')
    ->where('id', $cita->id_asegurado)->first();

    if (!empty($datos_intervencion) && !empty($datos_intervencion->id_hospitalizacion) && $id_hospitalizacion=="null") {
      $id_hospitalizacion=!empty($datos_intervencion->id_hospitalizacion)?$datos_intervencion->id_hospitalizacion:0;
    }
    
    if ($id_hospitalizacion != 'null'){
        $acompaniante = DB::table('hospitalizaciones as h')
            ->leftJoin('tipo_parentescos as t', 't.id', '=', 'h.id')
            ->select('t.descripcion', 'h.tipo_doc_acompaniante', 'h.nro_doc_acompaniante', 'h.nombre_acompaniante', 'h.telefono_acompaniante')
            ->where('h.id', $id_hospitalizacion)->first();
    }else{
        $acompaniante = null;
    }
  
    $ultimoRegistro = CqxAtencione::where('id_cqx_intervencion', $id_intervencion)
      ->orderBy('created_at', 'desc')
      ->first();
    
    $atencion = $model_atencione->GetDatosByIdIntervencion_cqx($id_intervencion);
    $id_atencion=$atencion->id;
    
    //dd($id_intervencion);
    $receta = $model_receta->GetDatosRecetasByIdPrestacion($ultimoRegistro->id_prestacion);
    $prestacion = $model_prestacion->GetPrestacionById($ultimoRegistro->id_prestacion);
    $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($ultimoRegistro->id_prestacion);

    //if($id_prestacion>1910698):
    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionydiag($ultimoRegistro->id_prestacion, 1);
    //else:
      //$procedimiento = $model_prestacion->GetProcedimientosByIdPrestacion($ultimoRegistro->id_prestacion, 1);
    //endif;
    //$procedimientos = \App\Models\Procedimiento::where('estado',1)->get();

    $id_tipo_atencione = 3;
    $model_cqx_cita = new \App\Models\CqxCita;
    // $riesgos_qx = $model_cqx_cita->GetRiesgos($atencion->id_prestacion);
    $interconsultas_pre_qx = $model_interconsulta->GetInterconsultaPrequirurgicaByIdPrestacion($ultimoRegistro->id_prestacion);
    $data_cqx_cita = $model_cqx_cita->GetCitaByIdPrestacion($atencion->id_prestacion);
    $cqx_cita_profesionales = null;
    $cqx_cita_procedimientos = null;
    if ($data_cqx_cita != null) {
      $model_cqx_profesional = new \App\Models\CqxProfesional();
      $cqx_cita_profesionales = $model_cqx_profesional->GetByIdCqxCita($data_cqx_cita->id);
      $model_cqx_procedimiento = new \App\Models\CqxProcedimiento();
      $cqx_cita_procedimientos = $model_cqx_procedimiento->GetByIdCqxCita($data_cqx_cita->id);
    }

    $model_cqx_dia = new CqxDia();
    $cqx_dias = $model_cqx_dia->GetAllActivos();

    $model_intraoperatorio = new IntraOperatorio;
    $cirujanos = $model_intraoperatorio->profesiones_activos(0,$id_intervencion);//CIRUJANOS

    $model_intervencion = new CqxIntervencion;
    $intervencion = $model_intervencion->GetById($id_intervencion);
    
    $asegurado = new Asegurado;
    $model_cq_procedimiento = new CqxProcedimiento;
    $procedimientos = $model_cq_procedimiento->GetByIdIntervencionQuirugica($id_intervencion);
    $procedimientos_intervencion = $model_intraoperatorio->procedimientos_intervencion($id_intervencion);
    $procedimientos_examen_patologia = $model_intraoperatorio->procedimientos_intervencion_examen_pat($id_intervencion,$asegurado->id_asegurado);
    
    $intraoperatorio = $this->anestesiaIdIntervencion($id_intervencion);

    $tipo_anestesia = DB::table('cqx_tipo_anestesia')
    ->select('id', 'descripcion')
    ->get();

    $via_administracion = DB::table('via_administracion')
    ->select()
    ->get();

    $model_consultorio = new Consultorio;
    $modelcama = new Cama;
    $model_emergencia = new \App\Models\Emergencia;
    $model_hospitalizacion = new Hospitalizacione;
    $hospitalizacion = null;
    $dato_cama = null;
    $cuartos = [];
    $camas = [];

    
    if($id_hospitalizacion != 'null'){
      if (isset($id_hospitalizacion)){
        $hospitalizacion = DB::table('hospitalizaciones as h')
            ->leftJoin('tipo_parentescos as t', 't.id', '=', 'h.id_tipo_acompaniante')
            ->leftJoin('camas as c', 'h.id_cama', '=', 'c.id')
            ->leftJoin('consultorios as c2', 'h.id_servicio_destino', '=', 'c2.id')
            ->select('h.*','t.descripcion','c2.id as id_servicio','c2.nombre as servicio_destino','c.nombre as cama','t.descripcion', 'h.tipo_doc_acompaniante', 'h.nro_doc_acompaniante', 'h.nombre_acompaniante', 'h.telefono_acompaniante')
            ->where('h.id', $id_hospitalizacion)->first();
      }

      if(isset($hospitalizacion->id_cama_estado)){
        $dato_cama = $model_emergencia->GetObtenerCama($hospitalizacion->id_cama_estado);
        $camas = $modelcama->ShowAllCamasXIpressYCuartoYTipoatencion($hospitalizacion->id_establecimiento,$dato_cama->id_cuarto,2);
      }
      $cuartos = $modelcama->ShowAllCuartosXIpress2($hospitalizacion->id_establecimiento,2);
    }

    

    $model_medicos = new Medico;
    $medico = $model_medicos->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni, Auth::user()->id_establecimiento);
    if (!$medico) abort(422, 'El usuario no está registrado como médico en este establecimiento.');
    $medico_id_actual = (int) $medico->id;
    
    $fechaEgreso = $model_kardex->getFechaServidor();
    $horaEgreso = $model_kardex->getHoraServidor();

    if($id_hospitalizacion != 'null'){
      $datos_hospitalizacion = $model_hospitalizacion->GetDiasEstanciaByIdHospitalizacion($id_hospitalizacion);
      $dia_estancia = $datos_hospitalizacion ? $datos_hospitalizacion->dia_estancia : null;
    }else{
      $dia_estancia = 'null';
    }

    $upss="SELECT 
    concat(u.descripcion_upss,' / ',u.codigo ) as nombre,
    u.id as id_upss,
    u.codigo as codigo_upss,
    c.id as id_consultorio,
    c.nombre as nombre_consultorio
    from consultorios c 
    inner join upsses u on u.id_consultorio = c.id 
    where u.id_tipo_atencion = '3' and u.estado = '1' and u.estado = '1' and u.descripcion_upss like 'H%' order by nombre asc";
    $upss= DB::select($upss);

    $servicios_concurrencia = $model_consultorio->ShowAllConsultoriosSubConsultorioAll($id_establecimiento);
    $consulta_externa = $model_consultorio->ShowConsultorioByIdIpressAndIdTipoAtencion($id_establecimiento,1);
    $establecimientos_traslado = $model_establecimiento->GetEstablecimientosNivel1Y2();

    $id_farmacia = Auth::user()->id_farmacia;
    $id_establecimiento=Auth::user()->id_establecimiento;
    $model_farma = new Farmacia();
    $farmacia = $model_farma->GetFarmaByIdfarma($id_farmacia);
    $id_ipress = $farmacia->id_establecimiento;
    $model_establecimiento = new \App\Models\Establecimiento;
    $establecimiento = $model_establecimiento->GetById($id_ipress);
    if ($id_ipress == 1) {
        $nivel = 2;
    } else {
        $nivel = $establecimiento[0]->nivel;
    }
    
    

    $id_servicio=null;
    $datos_servicio=null;
    //PRESTACION INICIAL PARA EMERGENCIA
    $datos_upss_ambulatorio="";
    $atencion_emergencia= 0;
    $atencion_emergencia = $model_prestacion::select('id_tipo_atencion')->where('id',$atencion->id_prestacion_inicial)->first();
    if(!empty($atencion_emergencia) && $atencion_emergencia->id_tipo_atencion==2){//SI VIENE DE EMRGENCIA
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
          'ho.id_upss_traslado',
          'ho.id_sala',
          'ho.id_establecimiento',
          'ho.fecha_derivacion'
      )
      ->where('p.id',$atencion->id_prestacion_inicial)
      ->first();
      $id_servicio=isset($datos_servicio->id_servicio)?$datos_servicio->id_servicio:0;
    }else{
      $id_servicio=isset($hospitalizacion->id_servicio_encargado)?$hospitalizacion->id_servicio_encargado:$datos_intervencion->id_consultorio;
      $datos_upss_ambulatorio = DB::table('prestaciones as p')
            ->join('upsses as up', 'p.codigo_upss', '=', 'up.codigo')
            ->join('consultorios as c', 'up.id_consultorio', '=', 'c.id')
            ->select('up.codigo','up.descripcion_upss')
            ->where('p.id', $datos_intervencion->id_prestacion_atqx)->first();
    }
    $model_consultorio = new Consultorio();
    $servicio = $model_consultorio->GetNombreById($id_servicio);

    $id_servicio_encargado = !empty($hospitalizacion->id_servicio_encargado)?$hospitalizacion->id_servicio_encargado:$id_servicio;
    
    $puede_atennder_servicios_cq = DB::table('medico_consultorios as mc')
                            ->join('consultorios as c', 'mc.id_consultorio', '=', 'c.id')
                            ->where('mc.id_establecimiento', $id_ipress)
                            ->where('mc.id_medico', $medico->id)
                            ->where('mc.estado', 1)
                            ->where('c.id',$id_servicio_encargado)
                            ->exists();

    $farmacias = $model_farma->GetFarmaciaByIdEstablecimiento($farmacia->id_establecimiento);
    $id_prestacion_atq = $ultimoRegistro->id_prestacion;
    $registrar_destino = $ultimoRegistro->registrar_destino;

    // TRAER DATA DE GESTACION:
    $id_presta= (!empty($id_prestacion) && $id_prestacion != "null")?$id_prestacion:$id_prestacion_atq;
    $prestacion_for_find_gestacion = Prestaciones::find($id_presta);
    $gestacion = null;
    if (isset($prestacion_for_find_gestacion->id_gestacion)) {
      $gestacion = Gestacion::find($prestacion_for_find_gestacion->id_gestacion);
      error_log("GESTACION DE PRESTACION CITA: ".(string)$gestacion->id);
    }
    // Fin insercion se gestacion
    $puerperio = null;
    $emergencia = Emergencia::find($prestacion_for_find_gestacion->id_ext);
    if (isset($emergencia) && isset($emergencia->id_puerperio)) {
      $puerperio = Puerperio::find($emergencia->id_puerperio);
    }
    $natis = null;
    if (isset($puerperio)) {
      $natis = Nati::where("id_puerperio", $puerperio->id)->get();
    }
    $model_tipo_cirugia = new \App\Models\CqxTipoAnestesia();
    $tipo_cirugias = $model_tipo_cirugia->GetAllTipoCirugiasActivos();
    $pabellones = $modelcama->GetAllPabellones($id_ipress, 'HOSPITALIZACION');
    $flag_api_sp = DB::table('config_options')->where('id', 5)->value('api_sp_activated'); // si es true debería utilizarse el API de SALUDPOL , caso contrario, la ws de SALUDPOL

    $is_habilitado_firma_electronica = $establecimiento[0]->flag_firma_electronica;
    
    return view('frontend.centro_quirurgico.atencion_quirurgica.detalle_atencion_quirurgica', compact('flag_api_sp','upss','fechaEgreso', 'horaEgreso','dia_estancia','servicios_concurrencia','consulta_externa','establecimientos_traslado','hospitalizacion',
      'ultimoRegistro','id_prestacion','prestacion','modalidad','id_tipo_atencione','cqx_dias','intraoperatorio','tipo_anestesia','intervencion','procedimientos_intervencion','cirujanos','farmacia','cita','diagnostico', 'procedimiento','fechaServidor', 'receta', 'diagnosticos','procedimientos','data_cqx_cita', 'cqx_cita_profesionales', 'cqx_cita_procedimientos','id_intervencion','via_administracion','titular','acompaniante','dato_cama','cuartos','camas','nivel','puede_atennder_servicios_cq','id_ipress','farmacias','id_prestacion_atq','registrar_destino','gestacion','puerperio','natis','id_atencion','id_servicio_encargado','id_hospitalizacion','datos_servicio','atencion_emergencia','datos_intervencion','servicio','datos_upss_ambulatorio','atencion','procedimientos_examen_patologia','tipo_cirugias','pabellones', 'is_habilitado_firma_electronica', 'medico_id_actual'));
    
  //}
  
 }

//  public function postArguments(Request $request)
//  {
//    $SERVER_PATH = env('REFIRMA_SERVER_PATH');
//    $protocol = (env('REFIRMA_PROTOCOL') == 'http') ? "T" : "S";
   
//    if ($request->type == "W") {
//      $param = '{
//                    "app":"pcx",
//                    "mode":"lot-p",
//                    "clientId":"' . env('REFIRMA_CLIENT_ID') . '",
//                    "clientSecret":"' . env('REFIRMA_CLIENT_SECRET') . '",
//                    "idFile":"MyForm",
//                    "protocol":"' . $protocol . '",
//                    "type":"W",
//                    "fileDownloadUrl":"' . $SERVER_PATH . '/tmp/zip/sin_firma/' . $request->documentName . '",
//                    "fileDownloadLogoUrl":"' . $SERVER_PATH . '/images/iLogo1.png",
//                    "fileDownloadStampUrl":"' . $SERVER_PATH . '/images/logo_sanidad_firma.png",
//                    "fileUploadUrl":"' . $SERVER_PATH . '/refirma/upload",
//                    "contentFile":"' . $request->documentName . '",
//                    "reason":"Soy el autor del documento",
//                    "isSignatureVisible":"true",
//                    "stampAppearanceId":"0",
//                    "pageNumber":"0",
//                    "posx":"47",
//                    "posy":"50",
//                    "fontSize":"7",
//                    "dcfilter": ".*FIR.*|.*FAU.*",
//                    "signatureLevel":"0",
//                    "maxFileSize":"5242880"
//                }';
//      echo  base64_encode($param);
//    }
//  }

 public function pdf_atencion_quirurgica_sin_firma($id_atencion){
  if (!Auth::check()) :
    return redirect('/auth/login');
  endif;
  
  $id_user = Auth::user()->id;
  
  if (Auth::user()->tipo_registro == 1) {

    $model_prestacion = new Prestaciones;
    $model_receta = new Receta_vale;
    $model_intraoperatorio = new IntraOperatorio;
    $model_intervencion = new CqxIntervencion();
    $model_emergencia = new Emergencia();
    $model_hospitalizacion = new Hospitalizacione();
    
    $tipo_egreso="";
    $condicion_alta="";
    $cama="";
    $servicio_destino="";
    $grado=null;
    $persona_datos_adicionales=null;
    $titular=null;

    $cqx_atencion = DB::table('cqx_atenciones')
      ->leftJoin('hospitalizaciones', 'cqx_atenciones.id_hospitalizacion', '=', 'hospitalizaciones.id')
      ->leftJoin('medicos', 'hospitalizaciones.id', '=', 'hospitalizaciones.id')
      ->leftJoin('consultorios', 'hospitalizaciones.id_servicio_encargado', '=', 'consultorios.id')
      ->select(
          'consultorios.nombre as servicio',
          'cqx_atenciones.complicacion_medica',
          'cqx_atenciones.id_cqx_intervencion',
          'cqx_atenciones.id_prestacion',
          'cqx_atenciones.id_hospitalizacion',
          'cqx_atenciones.allazgo_operatorio',
          'cqx_atenciones.examen_bacteriologico',
          'cqx_atenciones.examen_patologico',
          'hospitalizaciones.id_tipo_egreso',
          'hospitalizaciones.id',
          'hospitalizaciones.id_cama_estado',
          'hospitalizaciones.id_condicion_alta',
          'consultorios.nombre as servicio_destino',
          DB::raw("CASE hospitalizaciones.id_condicion_alta
                      WHEN 1 THEN 'MEJORADO'
                      WHEN 2 THEN 'CURADO'
                      WHEN 3 THEN 'TRASLADO'
                      ELSE 'OTRO'
                  END AS condicion_alta")
      )
      ->where('cqx_atenciones.id', $id_atencion)
      ->first();
    if(isset($cqx_atencion->condicion_alta)){
      $condicion_alta=$cqx_atencion->condicion_alta;
      if($cqx_atencion->id_tipo_egreso==1){//Domiciliaria
        $tipo_egreso="DOMICILIARIA";
      }else{
        $tipo_egreso="HOSPITALIZADO";
        $servicio_destino=$cqx_atencion->servicio_destino;
        $cama = DB::table('cambio_camas') 
        ->join('camas', 'cambio_camas.id_cama', '=', 'camas.id')
        ->select('cambio_camas.id','camas.nombre')
        ->where('cambio_camas.id_prestacion', '=', $cqx_atencion->id_prestacion)
        ->orderBy('cambio_camas.id', 'desc')
        ->first();
      }
    }
   
    $prestacion = DB::table('prestaciones')
      ->join('cqx_atenciones as ca', 'prestaciones.id', '=', 'ca.id_prestacion')
      ->leftJoin('citas as c', 'prestaciones.id_cita', '=', 'c.id')
      ->leftJoin('medicos as m', 'prestaciones.id_medico', '=', 'm.id')
      ->leftJoin('profesiones as p', 'm.id_profesion', '=', 'p.id')
      ->leftJoin('asegurados', 'prestaciones.id_asegurado', '=', 'asegurados.id')
      ->leftJoin('establecimientos as e', 'prestaciones.id_establecimiento', '=', 'e.id')
      ->leftJoin('asegurado_historias as ah', function ($join) {
          $join->on('asegurados.id', '=', 'ah.id_asegurado')
                ->on('e.id', '=', 'ah.id_establecimiento'); // Se agrega la condición aquí
      })
      ->leftJoin('tipo_beneficiarios as tb', 'asegurados.id_tipo_beneficiario', '=', 'tb.id')
      ->leftJoin('distritos', 'prestaciones.id_asegurado', '=', 'distritos.id_distrito')
      ->leftJoin('provincias', 'distritos.id_provincia', '=', 'provincias.id_provincia')
      ->leftJoin('departamentos', 'provincias.id_departamento', '=', 'departamentos.id_departamento')
      ->leftJoin('distritos as distrito_nacimiento', 'asegurados.id_distrito_nacimiento', '=', 'distrito_nacimiento.id_distrito')
      ->leftJoin('provincias as provincia_nacimiento', 'distrito_nacimiento.id_provincia', '=', 'provincia_nacimiento.id_provincia')
      ->leftJoin('departamentos as departamento_nacimiento', 'provincia_nacimiento.id_departamento', '=', 'departamento_nacimiento.id_departamento')
      ->leftJoin('distritos as distrito_procedencia', 'asegurados.id_distrito_procedencia', '=', 'distrito_procedencia.id_distrito')
      ->leftJoin('provincias as provincia_procedencia', 'distrito_procedencia.id_provincia', '=', 'provincia_procedencia.id_provincia')
      ->leftJoin('departamentos as departamento_procedencia', 'provincia_procedencia.id_departamento', '=', 'departamento_procedencia.id_departamento')
      ->leftJoin('razas', 'asegurados.id_raza', '=', 'razas.id')
      ->leftJoin('estado_civiles', 'asegurados.id_estado_civil', '=', 'estado_civiles.id')
      ->leftJoin('grado_instrucciones', 'asegurados.id_grado_instruccion', '=', 'grado_instrucciones.id')
      ->leftJoin('ocupaciones', 'asegurados.id_ocupacion', '=', 'ocupaciones.id')
      ->leftJoin('religiones', 'asegurados.id_religion', '=', 'religiones.id')
      ->leftJoin('tipo_sangres as ts','asegurados.id_tipo_sangre','=','ts.id')
      ->leftJoin('sub_consultorios as sc','prestaciones.id_sub_consultorio','=','sc.id')
      ->leftJoin('consultorios as co','sc.id_consultorio','=','co.id')
      ->select(
          'ca.codigo_siteds as codigo_siteds_cqx',
          'ca.id_hospitalizacion',
          'asegurados.*', 
          'distritos.descripcion as distrito', 
          'provincias.descripcion as provincia', 
          'departamentos.descripcion as departamento', 
          'razas.nombre as etnia',
          'estado_civiles.nombre as estado_civil', 
          'grado_instrucciones.nombre as grado_instruccion', 
          'ocupaciones.nombre as ocupacion', 
          'religiones.nombre as religion',
          'distrito_nacimiento.descripcion as distrito_nacimiento2', 
          'provincia_nacimiento.descripcion as provincia_nacimiento2', 
          'departamento_nacimiento.descripcion as departamento_nacimiento2',
          'distrito_procedencia.descripcion as distrito_procedencia2', 
          'provincia_procedencia.descripcion as provincia_procedencia2', 
          'departamento_procedencia.descripcion as departamento_procedencia2',
          DB::raw("date_part('year', age(to_date(to_char(asegurados.fecha_nac::date, 'DD/MM/YYYY'), 'DD/MM/YYYY'))) as edadpaciente"),
          'prestaciones.*',
          'prestaciones.cups_pnp as codigo_cups',
          DB::raw("to_char(prestaciones.created_at, 'HH:MI AM') hora_ingreso"),
          DB::raw("to_char(prestaciones.fecha_atencion, 'DD/MM/YYYY') fecha_ingreso"),
          'm.paterno as medico_paterno', 
          'm.materno as medico_materno', 
          'm.nombre as medico_nombre', 
          'm.colegiatura as profesional_colegiatura',
          'p.nombre as profesional_profesion',
          'ah.nro_historia',
          'tb.descripcion as tipo_beneficiario',
          'c.codigo_siteds',
          'prestaciones.cups_pnp',
          'ts.descripcion_corta as grupo_sangre',
          'co.nombre as servicio',
          'sc.descripcion as consultorio'
      )
      ->where('asegurados.id_estado_reg', 1)
      ->where('prestaciones.id', $cqx_atencion->id_prestacion)
      ->where('prestaciones.id_tipo_atencion', 3)
      ->where('prestaciones.id_estado_reg', 1)
      ->first();   
    
    $recetas = DB::table('receta_vales')
        ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
        ->select('receta_vales.*', 'farmacias.nombre as farmacia')
        ->where('receta_vales.id_prestacion', '=', $cqx_atencion->id_prestacion)
        ->where('receta_vales.estado','<>', 0)
        ->orderBy('receta_vales.id', 'asc')
        ->get();
    
    $receta_productos = DB::table('receta_vales')
        ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
        ->join('productos', 'producto_recetas.id_producto', '=', 'productos.id')
        ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
        ->leftJoin('farmacias', 'producto_recetas.id_farmacia', '=', 'farmacias.id')
        ->leftJoin('via_administracion', 'producto_recetas.id_via_administracion', '=', 'via_administracion.id')
        ->where('receta_vales.id_prestacion', $cqx_atencion->id_prestacion)
        ->where('receta_vales.estado','<>',0)
        ->where('producto_recetas.estado','<>',0)
        ->select(
            'receta_vales.id as id_receta',
            'producto_recetas.id as id_producto_receta',
            'productos.codigo',
            'productos.nombre',
            'productos.id as id_producto',
            'productos.presentacion_comercial as presentacion',
            'producto_recetas.*',
            'unidades.abreviatura',
            'farmacias.nombre as farmacia',
            'via_administracion.descripcion as via'
        )
        ->orderBy('receta_vales.id', 'asc')
        ->orderBy('producto_recetas.id', 'asc')
        ->get();
    
    $intraoperatorio = $this->anestesiaIdIntervencion($cqx_atencion->id_cqx_intervencion);
    $cirujanos = $model_intraoperatorio->profesiones(0,$cqx_atencion->id_cqx_intervencion);//CIRUJANOS
    $anestesiologos = $model_intraoperatorio->profesiones(1,$cqx_atencion->id_cqx_intervencion);//ANESTESIOLOGOS
    $instrumentistas = $model_intraoperatorio->profesiones_intrumentista(2, !empty($intraoperatorio->id_intraoperatorio) && $intraoperatorio->id_intraoperatorio?$intraoperatorio->id_intraoperatorio:0);//INSTRUMENTISTAS
    if ($prestacion->id_asegurado_titular != null || $prestacion->id_asegurado_titular > 0) {
        $titular = DB::table('asegurados')
            ->select('asegurados.*')
            ->where('id',$prestacion->id_asegurado_titular)
            ->where('id_estado_reg', 1)
            ->first();
        
        if ($titular != null) {
            $persona_datos_adicionales = DB::table('persona_datos_adicionales')
                ->select('persona_datos_adicionales.*')
                ->where('id_asegurado',$titular->id)
                ->first();

            if ($persona_datos_adicionales != null) {
                $grado = DB::table('titular_grados')
                ->select('titular_grados.*')
                ->where('id',$persona_datos_adicionales->id_grado)
                ->first();
            }
        }
    }
    $intervencion = $model_intervencion->GetById($cqx_atencion->id_cqx_intervencion);
    $datos = $model_hospitalizacion->get_reporte_operatorio_cqx($cqx_atencion->id_cqx_intervencion);

    $pdf = \PDF::loadView('frontend.centro_quirurgico.atencion_quirurgica.pdf_atencion_quirurgica', 
    compact('cqx_atencion','tipo_egreso','condicion_alta','cama','servicio_destino','datos','intervencion','intraoperatorio','cirujanos','anestesiologos','instrumentistas','prestacion','recetas','receta_productos','ordenes_laboratorio','orden_lab_examenes','ordenes_imagenologia','orden_img_examenes','titular','grado','persona_datos_adicionales','alergias'));
    $pdf->getDomPDF()->set_option("enable_php", true);
    $pdf->setPaper('A4', 'portrait');
    return $pdf->stream('atencion_quirurgica.pdf'); 
  }
  
 }

 public function atencion_quirurgica_adicional(Request $request){
    if (!Auth::check()) :
        return redirect('/auth/login');
    endif; 
    //dd($request->all());
    DB::beginTransaction();
    try {
        $model_intervencion = new CqxIntervencion();
        $datos_intervencion = $model_intervencion::find($request->id_intervencion);
        if (!empty($datos_intervencion) && !empty($datos_intervencion->id_hospitalizacion) && empty($request->id_hospitalizacion)) {
            return response()->json([
                'admsionado_hospitalizacion' => true,
                'msg' => 'El paciente fue admitido. Proceda a registrar los datos de destino.',
                'id_hospitalizacion' => $datos_intervencion->id_hospitalizacion
            ]);
        }
        $prestacion_unico = DB::table('prestaciones as pre')->where('pre.flag_intervencion_quirurgica','=',true)->where('pre.id_intervencion','=',$request->id_intervencion)
                                   ->select('pre.*')->first(); 
        $id_prestacion = !empty($prestacion_unico)  ? $prestacion_unico->id  : $request->id_prestacion_atq ; 

        $firma_receta_existente=0;
        $recetas = $request->input('firma_receta_existente');
        if (isset($recetas)):
          foreach ($recetas as $key => $valorFirma) {
            if ($valorFirma == "1") {
              $firma_receta_existente=$key;
            }
          }
        endif;
        $id_receta=0;
        ////////////////////////AGREGANDO LOS DIAGNOSTICOS//////////////////////////////////////
        $model_cqxanestesia = new \App\Models\CqxAnestesia;//RE UTILIZANDO FUNCIONES DE ANESTESIA
        $p_diagnostico = "";
        $p_diagnostico .= "{";
        if (isset($request->id_diagnostico)):
          foreach ($request->id_diagnostico as $key => $value) :
            if ($request->id_diagnostico[$key] != "") {
              $p_diagnostico .= "{";
              $p_diagnostico .= $request->id_diagnostico[$key] . ",";
              $p_diagnostico .= (isset($request->id_tipo_diagnostico[$key]) && $request->id_tipo_diagnostico[$key] > 0) ? $request->id_tipo_diagnostico[$key]. "," : "0";
              $p_diagnostico .= (isset($request->id_categoria_diagnostico[$key]) && $request->id_categoria_diagnostico[$key] > 0) ? $request->id_categoria_diagnostico[$key] . "," : "0,";
              $p_diagnostico .= $request->id_intervencion;
              $p_diagnostico .= "},";
            }
          endforeach;
          if (strlen($p_diagnostico) > 1) $p_diagnostico = substr($p_diagnostico, 0, -1);
        endif;
        $p_diagnostico .= "}";
        if (isset($request->id_diagnostico)):
          $res_diagnostico = $model_cqxanestesia->addDiagnosticoAdicional($id_prestacion, $p_diagnostico, $request->id_user);
          Log::info('Agregando Diagnosticos Adicionales', ['addDiagnosticoAdicional' => $res_diagnostico]);
        endif;
        
        //////////////////////////////////////////////////////////////
        
        /////////////////// Procedimientos Adicionales/////////////////
        $p_procedimiento = "";
        $p_procedimiento .= "{";
        if (isset($request->id_procedimiento)) :
          foreach ($request->id_procedimiento as $key => $value) :
            if ($request->id_procedimiento[$key] != "") {
              $p_procedimiento .= "{";
              $p_procedimiento .= $request->id_procedimiento[$key] . ",";
              $p_procedimiento .= (isset($request->procedimiento_cantidad[$key]) && $request->procedimiento_cantidad[$key] > 0) ? $request->procedimiento_cantidad[$key] : "0";
              $p_procedimiento .= "},";
            }
          endforeach;
          if (strlen($p_procedimiento) > 1) $p_procedimiento = substr($p_procedimiento, 0, -1);
        endif;
        $p_procedimiento .= "}";
        if (isset($request->id_procedimiento)) :
          $res_procedimiento = $model_cqxanestesia->addProcedimientoAdicional($id_prestacion, $p_procedimiento, $request->id_user);
          Log::info('Agregando Procedimientos Adicionales', ['addProcedimientoAdicional' => $res_procedimiento]);
        endif;
        
        ////////////insertar procedimientos x diagnosticos de adicionales//////////////
        if(isset($request->id_procedimiento)):
          if(isset($request->miSelect)):
            $arrayprocedimientosadicional=$request->id_procedimiento;
            $arraydiagnosticosadicional=$request->miSelect;
            for($iterador=0;$iterador<count($arrayprocedimientosadicional);$iterador++){
      
              $model_procedimiento_metodos = new \App\Models\Procedimiento;
              $idprocedimientoadicional= $arrayprocedimientosadicional[$iterador];
              $iddiagnosticoadicional = $arraydiagnosticosadicional[$iterador];
              $idprestacionxDiagProcAdicional = $id_prestacion;
              $mensajeSucessAdicional = $model_procedimiento_metodos->RegistrarProcedimientoxDiagnostico($idprestacionxDiagProcAdicional,$idprocedimientoadicional,$iddiagnosticoadicional,1);  
              Log::info('Agregando Procedimientos Diagnosticos Adicionales', ['RegistrarProcedimientoxDiagnostico' => $mensajeSucessAdicional]);
              //PROCEDIMIENTOS POST CQX 
              $model_cq_procedimiento = new \App\Models\CqxProcedimiento();
              $model_cq_procedimiento->RegistrarProcedimientoPreQx($request->id_intervencion, $idprocedimientoadicional, Auth::user()->id, 0,2,false);
          }

          endif;
        endif;
        /////////////////////////////////////////////////////////////////////
        
        //////////////////Medicamentos Editados o eliminados///////////////////
        for ($i=0; $i < count($request->id_producto_receta_editar) ; $i++) {
          $accion = isset($request->accionRowProducto[$i]) ? $request->accionRowProducto[$i] : null;
          switch ($accion) {
            case 'edit':
              DB::table('producto_recetas')
              ->where('producto_recetas.id', '=', $request->id_producto_receta_editar[$i])
              ->update([
              'cantidad_prescrita' => $request->cantidad_prescrita_editar[$i],
              'tiempo_periodo' => $request->tiempo_periodo_editar[$i],
              'posologia' => $request->posologia_editar[$i],
              'id_via_administracion' => $request->id_via_administracion_editar[$i]
              ]);
              break;
            
            case 'delete':  
              DB::table('producto_recetas')
              ->where('producto_recetas.id', '=', $request->id_producto_receta_editar[$i])
              ->update([
              'estado' => 0
              ]);
              break;
          } 
        }

        /////////////////////////AGREGAR NUEVOS MEDICAMENTOS DE UNA RECETA EXISTENTE //////////////////////
        if ($request->id_producto != null ) {
          $p_producto = "";
          $p_producto .= "{";
          if (!empty($request->id_producto) && is_array($request->id_producto)) :
            foreach ($request->id_producto as $key => $value) :
              if ($request->id_producto[$key] != "") {
                $p_producto .= "{";
                $p_producto .= $request->id_producto[$key] . ",";
                $p_producto .= $request->tiempo_periodo[$key] . ",";
                $p_producto .= $request->nro_med_solictados[$key] . ",";
                $p_producto .= (isset($request->nro_med_entregados[$key]) && $request->nro_med_entregados[$key] != "") ? $request->nro_med_entregados[$key] : 0;
                $p_producto .= ",";
                $p_producto .= (isset($request->nro_stocks_almacen[$key]) && $request->nro_stocks_almacen[$key] != "") ? $request->nro_stocks_almacen[$key] : 0;
                $p_producto .= ",";
                $p_producto .= (isset($request->nro_stocks_establecimiento[$key]) && $request->nro_stocks_establecimiento[$key] != "") ? $request->nro_stocks_establecimiento[$key] : 0;
                $p_producto .= ",";
                $p_producto .= (isset($request->nro_stocks[$key]) && $request->nro_stocks[$key] != "") ? $request->nro_stocks[$key] : 0;
                $p_producto .= ",";
                $p_producto .= (isset($request->posologia[$key]) && $request->posologia[$key] != "") ? str_replace(",", "###", $request->posologia[$key]) : "-";
                $p_producto .= ",";
                $p_producto .= (isset($request->miSelectProd[$key]) && $request->miSelectProd[$key] != "") ? $request->miSelectProd[$key] : 0;
                $p_producto .= ",";
                $p_producto .=(isset($request->id_via_administracion[$key]) && $request->id_via_administracion[$key] != "") ? $request->id_via_administracion[$key] : 0;
                $p_producto .= "},";
              }
            endforeach;
            if (strlen($p_producto) > 1) $p_producto = substr($p_producto, 0, -1);
          endif;
          $p_producto .= "}";
         
          if(isset($firma_receta_existente)){
            $id_receta=$model_cqxanestesia->agregarProductoRecetas($request->id_receta_vale_editar,$p_producto);
            DB::commit(); 
            return response()->json(['success' => true,'id_receta'=>$id_receta]);
          }else{
            $model_cqxanestesia->agregarProductoRecetas($request->id_receta_vale_editar,$p_producto);
          }
        }
        ////================== AGREGAR NUEVOS MEDICAMENTOS DE UNA RECETA NUEVA =======================
        if ( $request->id_producto_adicional != null ) {
          $p_producto_adicional = "";
          $p_producto_adicional .= "{";
          if (!empty($request->id_producto_adicional) && is_array($request->id_producto_adicional)) :
            foreach ($request->id_producto_adicional as $key => $value) :
              if ($request->id_producto_adicional[$key] != "") {
                $p_producto_adicional .= "{";
                $p_producto_adicional .= $request->id_producto_adicional[$key] . ",";
                $p_producto_adicional .= $request->tiempo_periodo_adicional[$key] . ",";
                $p_producto_adicional .= $request->nro_med_solictados_atencion_adicional[$key] . ",";
                $p_producto_adicional .= (isset($request->nro_med_entregados_adicional[$key]) && $request->nro_med_entregados_adicional[$key] != "") ? $request->nro_med_entregados_adicional[$key] : 0;
                $p_producto_adicional .= ",";
                $p_producto_adicional .= (isset($request->nro_stocks_almacen_adicional[$key]) && $request->nro_stocks_almacen_adicional[$key] != "") ? $request->nro_stocks_almacen_adicional[$key] : 0;
                $p_producto_adicional .= ",";
                $p_producto_adicional .= (isset($request->nro_stocks_establecimiento_adicional[$key]) && $request->nro_stocks_establecimiento_adicional[$key] != "") ? $request->nro_stocks_establecimiento_adicional[$key] : 0;
                $p_producto_adicional .= ",";
                $p_producto_adicional .= (isset($request->nro_stocks_adicional[$key]) && $request->nro_stocks_adicional[$key] != "") ? $request->nro_stocks_adicional[$key] : 0;
                $p_producto_adicional .= ",";
                $p_producto_adicional .= (isset($request->posologiaAdicional[$key]) && $request->posologiaAdicional[$key] != "") ? str_replace(",", "###", $request->posologiaAdicional[$key]) : "-";
                $p_producto_adicional .= ",";
                $p_producto_adicional .= (isset($request->miSelectProd[$key]) && $request->miSelectProd[$key] != "") ? $request->miSelectProd[$key] : 0;
                $p_producto_adicional .= ",";
                $p_producto_adicional .=(isset($request->id_via_administracion_adicional[$key]) && $request->id_via_administracion_adicional[$key] != "") ? $request->id_via_administracion_adicional[$key] : 0;
                $p_producto_adicional .= "},";
              }
            endforeach;
            if (strlen($p_producto_adicional) > 1) $p_producto_adicional = substr($p_producto_adicional, 0, -1);
          endif;
          $p_producto_adicional .= "}";
          $id_farmacia_receta        =  ($request->id_farmacia!="") ? $request->id_farmacia : 0;
          $id_receta_vale_bus = 0;
          if (Auth::user()->tipo_registro == 3) $id_receta_vale_bus = 1;
          
          $hora = date('H');
          if ($hora > 6 && $hora < 13) $turno = "M";
          elseif ($hora >= 13 && $hora < 19) $turno = "T";
          elseif ($hora >= 19 && $hora <= 24) $turno = "N";
          else $turno = "MD";

          $model_prestacion = new \App\Models\Prestaciones;



          $prestacion = $model_prestacion->GetPrestacionById($request->id_prestacion_atq); 
          
          $data=array(
            'p_id_receta_vale'=>0, 
            'p_id_farmacia'=>$id_farmacia_receta, 
            'p_id_user'=> Auth::user()->id,
            'p_id_consultorio'=> $request->id_servicio_encargado,
            'p_nro_receta'=>'', 
            'p_digitos_adicionales'=>'', 
            'p_dni_beneficiario'=>$prestacion->nro_doc_ident, 
            'p_cip_beneficiario'=>$prestacion->cip, 
            'p_tipdoc_beneficiario'=>$prestacion->tipo_doc_ident, 
            'p_nombre_beneficiario'=>$prestacion->asegurado_nombre, 
            'p_paterno_beneficiario'=>$prestacion->asegurado_paterno, 
            'p_materno_beneficiario'=>$prestacion->asegurado_materno, 
            'p_tipo_beneficiario'=>$prestacion->parentesco, 
            'p_dni_titular'=>$prestacion->nrodoctitular, 
            'p_tipdoc_titular'=>$prestacion->tipodoc_titular, 
            'p_nombre_titular'=>$prestacion->nombre_titular, 
            'p_paterno_titular'=>$prestacion->paterno_titular, 
            'p_materno_titular'=>$prestacion->materno_titular, 
            'p_fecha_expedicion'=>(isset($request->fecha_expedicion)) ? $request->fecha_expedicion : '', 
            'p_fecha_atencion'=>Carbon::parse($request->fecha_hora_atencion)->format('Y-m-d'),
            'p_nro_historia'=>$prestacion->nro_historia, 
            'p_grado'=>$prestacion->grado, 
            'p_id_medico'=>$request->medico_id_actual, 
            'p_tipo_receta'=>$prestacion->id_tipo_atencion, 
            'p_turno'=>$turno, 
            'p_obs_registro'=>'',
            'p_id_receta_vale_bus'=>$id_receta_vale_bus, 
            'p_id_admision'=>0, 
            'p_tipo_via'=>'', 
            'p_sub_via'=>'', 
            'p_final_via'=>'', 
            'p_nro_guia'=>'', 
            'p_interconsulta'=>'', 
            'p_interconsulta_dias'=>0, 
            'p_id_prestacion'=>$id_prestacion, 
            'p_id_tipo_num'=>0, 
            'p_estado'=>2, //estado de la receta
            'p_detalle'=>$p_producto_adicional,  
            'p_sexo'=>$prestacion->sexo,
            'p_fecha_nacimiento'=>$prestacion->fecha_nac, 
            'p_codigo_upss'=>$request->codigo_upss_atencioncqx
          );
          if($request->firma_receta_adicional==1){
            $id_receta=$model_prestacion->registrarRecetaValeAtencionDetalle_cqx($data);
            DB::commit();
            return response()->json(['success' => true,'id_receta'=>$id_receta]);
          }else{
            $id_receta=$model_prestacion->registrarRecetaValeAtencionDetalle_cqx($data);
          }
        }
        /********************************RECETA ADICIONAL POSTQUIRURGICA****************************/

        if (!empty($request->id_farmacia_postqx) && in_array((int)$request->id_farmacia_postqx, [128, 22, 107], true)  &&  !empty($request->id_producto_adicional_pq)) {
          $idRecetaPostQx = $this->receta_post_quirurgica_adicional($request); 
          if ($idRecetaPostQx > 0) {
                $recetaPostQx = Receta_vale::find($idRecetaPostQx);
                if ($recetaPostQx) {
                    $recetaPostQx->flg_post_quirurgico = 1;
                    $recetaPostQx->save();
                }else{
                    Log::error('Error al registrar la receta post quirúrgica adicional: Receta no encontrada', ['idRecetaPostQx' => $idRecetaPostQx]);
                }
          }
        }
        // if(empty($request->registrar_destino)){
        //   return response()->json(['destino' => false,'msg'=>'Debe de registrar los datos de destino']);
        // }
        ///=====================OTROS DATOS====================
        $registrar_destino = !empty($request->registrar_destino) ? $request->registrar_destino :null;
        $CqxAtencion = new \App\Models\CqxAtencione();
        $atencion = $CqxAtencion::where('id_cqx_intervencion', $request->id_intervencion)->first();

        if(empty($atencion->id_hospitalizacion)){
        $atencion->id_hospitalizacion = !empty($request->id_hospitalizacion)?$request->id_hospitalizacion:null;
        }
        if(!empty($request->registrar_destino) && (int)$request->registrar_destino===1){
          if(!empty($request->id_tipo_egreso) && (int)$request->id_tipo_egreso===2){//DESTINO HOSPITALIZACION
            $estado_ubicacion = "";
            if ($request->id_cama_actual == $request->id_cama_destino):
              $estado_ubicacion = "1";
            elseif ($request->id_pabellon_actual == $request->id_pabellon_destino):
              $estado_ubicacion = "2";
            else:
              $estado_ubicacion = "3";
            endif;

            $model_hospitalizacion = new \App\Models\Hospitalizacione;
            $hospitalizacion = \App\Models\Hospitalizacione::find($request->id_hospitalizacion);
            if ($hospitalizacion && $hospitalizacion->id_cama_estado === null) {
              $model_hospitalizacion->ActualizaEstadoHospitalizacion($request->id_hospitalizacion,1);
            }
           // $date = Carbon::now();
            //$fecha_salida = Carbon::now()->format('d/m/Y h:i A');

            $fecha_hora_inicio = Carbon::parse($request->fecha_inicio)->format('d-m-Y');
            $id_condicion_alta = (isset($request->hospitalizacion_id_condicion_alta)) ? $request->hospitalizacion_id_condicion_alta : '';
            DB::update('UPDATE hospitalizaciones SET id_tipo_egreso = ?,id_condicion_alta = ?, id_cama = ? WHERE id = ?', [(int)$request->id_tipo_egreso, $id_condicion_alta, $request->id_cama_destino, $request->id_hospitalizacion]);

            $model_cambio_cama = new CambioCama();
            $model_cambio_cama->RegistrarCamaAtencionQxAQ(
              $request->id_hospitalizacion, 
              $request->id_cama_destino, 
              $estado_ubicacion, 
              $fecha_hora_inicio, 
              $request->id_cama_destino, 
              $id_prestacion, 
              0, 
              Carbon::now(),
              $request->id_area_destino,
              !empty($request->id_servicio)?$request->id_servicio:$request->id_servicio_destino,
              $request->id_area_destino,
              $request->id_servicio_encargado
            );
            //ACTUALIZAR EL ESTADO A ALTA
            $datos_intervencion->flag=4;
            $datos_intervencion->save();

            /**********ACTUALIZACION DE NUEVOS CAMPOS**************/
            $cama = \App\Models\Cama::find($request->id_cama_destino);
            $consultorio = \App\Models\Consultorio::find($request->id_servicio_encargado);
            $piso = \App\Models\Piso::where('id_pabellon', $request->id_pabellon_destino)->first();

            $hospitalizacion->id_pabellon = $request->id_pabellon_destino ?: null;
            $hospitalizacion->id_piso = $piso ? $piso->id : null;
            $hospitalizacion->id_consultorio_hosp = $request->id_servicio_encargado ?: null;
            $hospitalizacion->nombre_consultorio = $consultorio ? $consultorio->nombre : null;
            $hospitalizacion->id_cuarto = $cama ? $cama->id_cuarto : null;
            $hospitalizacion->id_consultorio = $request->id_servicio_encargado ?: null;
            $hospitalizacion->save();
            /*****************************************/
            //NUEVOS CAMPOS EN CQX_INTERVENCIONES YA NO USARA PARA UNA ALTA DE HOSPITALIZACION
            $datos_intervencion->id_tipo_egreso=(int)$request->id_tipo_egreso;
            $datos_intervencion->id_condicion_alta=$id_condicion_alta;
            //FIN CAMPOS EN CQX_INTERVENCIONES YA NO USARA PARA UAN ALTA DE HOSPITALIZACION 
            
          }else if(!empty($request->id_tipo_egreso) && (int)$request->id_tipo_egreso===1){//DESTINO DOMICILIARIA
            $model_hospitalizacion = new \App\Models\Hospitalizacione;
            $model_hospitalizacion->ActualizaEstadoHospitalizacion($request->id_hospitalizacion,6);
            //ACTUALIZA LOS DATOS DE DOMICILIARIA EN LA TABLA HOSPITALIZACIONES
            // $fechaEgreso = Carbon::createFromFormat('d-m-Y', $request->fecha_egreso)->format('Y-m-d H:i:s.u');
            $fechaEgreso = Carbon::parse($request->fecha_egreso)->endOfDay()->format('Y-m-d H:i:s');
            // $horaEgreso = Carbon::createFromFormat('H:i:s', $request->hora_egreso)->format('H:i:s.u');
            $horaEgreso = Carbon::parse($request->hora_egreso);
            $model_hospitalizacion->actualizarDatosDomiciliaria($request->id_hospitalizacion, (int)$request->id_tipo_egreso, $request->id_condicion_alta, $fechaEgreso, $horaEgreso, Auth::user()->id);
            //ACTUALIZAR EL ESTADO A ALTA
            $datos_intervencion->flag=4;
            $datos_intervencion->save();
            //NUEVOS CAMPOS EN CQX_INTERVENCIONES YA NO USARA PARA UNA ALTA DE HOSPITALIZACION
            $datos_intervencion->id_tipo_egreso=(int)$request->id_tipo_egreso;
            $datos_intervencion->id_condicion_alta=$request->id_condicion_alta;
            //FIN CAMPOS EN CQX_INTERVENCIONES YA NO USARA PARA UAN ALTA DE HOSPITALIZACION 
          }
          $atencion->id_servicio=$request->id_servicio_destino;
        }
        $fecha_intervencion = Carbon::parse($request->fecha_intervencion)->format('Y-m-d H:i:s');
        $datos_intervencion->emergencia_tipo_cirugia=$request->id_tipo_cirugia;
        $datos_intervencion->fecha_intervencion=$fecha_intervencion;
        $datos_intervencion->save();
        
        $atencion->registrar_destino = ($registrar_destino !== null && $registrar_destino !== '') ? $registrar_destino : (!empty($request->id_tipo_egreso) ? 1 : 0);
        $atencion->updated_us = Auth::user()->id;
        $atencion->updated_at = Carbon::now(); 
        $atencion->tiene_descripcion_procedimiento=!empty($request->tiene_descripcion_procedimiento) && $request->tiene_descripcion_procedimiento == 1 ? true : false;
        $atencion->tiene_hallazgo=!empty($request->tiene_hallazgo) && $request->tiene_hallazgo == 1 ? true : false;
        $atencion->tiene_pieza=!empty($request->tiene_pieza) && $request->tiene_pieza == 1 ? true : false;
        $atencion->tiene_examen_bact=!empty($request->tiene_examen_bact) && $request->tiene_examen_bact == 1 ? true : false;
        $atencion->tiene_examen_pat=!empty($request->tiene_examen_pat) && $request->tiene_examen_pat == 1 ? true : false;
        $atencion->tiene_complicacion_medica=!empty($request->tiene_complicacion_medica) && $request->tiene_complicacion_medica == 1 ? true : false;

        $atencion->allazgo_operatorio = (!empty($request->allazgo_operatorio) && $request->tiene_hallazgo == 1)
            ? $this->limpiarTextarea($request->allazgo_operatorio)
            : '';

        $atencion->complicacion_medica = (!empty($request->tiene_complicacion_medica) && $request->tiene_complicacion_medica == 1)
            ? $this->limpiarTextarea($request->complicacion_medica)
            : '';

        $atencion->pieza_operatorio = (!empty($request->tiene_pieza) && $request->tiene_pieza == 1)
            ? $this->limpiarTextarea($request->pieza_operatorio)
            : '';

        $atencion->examen_bacteriologico = (!empty($request->tiene_examen_bact) && $request->tiene_examen_bact == 1)
            ? $this->limpiarTextarea($request->examen_bacteriologico)
            : '';

        $atencion->examen_patologico = (!empty($request->tiene_examen_pat) && $request->tiene_examen_pat == 1)
            ? $this->limpiarTextarea($request->examen_patologico)
            : '';
        
        $atencion->descripcion_procedimiento = (!empty($request->tiene_descripcion_procedimiento) && $request->tiene_descripcion_procedimiento == 1)
            ? $this->limpiarTextarea($request->descripcion_procedimiento)
            : '';
        $atencion->egreso_resumen = !empty($request->egreso_resumen) ? $this->limpiarTextarea($request->egreso_resumen): '';
        $atencion->egreso_examen_clinico = !empty($request->egreso_examen_clinico) ? $this->limpiarTextarea($request->egreso_examen_clinico): '';
        $atencion->egreso_examen_auxiliar = !empty($request->egreso_examen_auxiliar) ? $this->limpiarTextarea($request->egreso_examen_auxiliar): '';
        $atencion->egreso_evolucion =!empty($request->egreso_evolucion) ? $this->limpiarTextarea($request->egreso_evolucion): '';
        $atencion->egreso_tratamiento =  !empty($request->egreso_tratamiento) ? $this->limpiarTextarea($request->egreso_tratamiento): '';
        $atencion->egreso_procedimiento = !empty($request->egreso_procedimiento) ? $this->limpiarTextarea($request->egreso_procedimiento): '';
        $atencion->egreso_complicacion = !empty($request->egreso_complicacion) ? $this->limpiarTextarea($request->egreso_complicacion): '';
        $atencion->egreso_medidas_generales = !empty($request->egreso_medidas_generales) ? $this->limpiarTextarea($request->egreso_medidas_generales): '';
        $atencion->egreso_medicamentos_prescritos = !empty($request->egreso_medicamentos_prescritos) ? $this->limpiarTextarea($request->egreso_medicamentos_prescritos): '';
        $atencion->egreso_pronostico =!empty($request->egreso_pronostico) ? $this->limpiarTextarea($request->egreso_pronostico): '';    
        $atencion->id_clasificacion_cirugia=$request->id_clasificacion_cirugia;
        // $atencion->hora_inicio_operatorio = !empty($request->hora_inicio_operatorio) ? $request->hora_inicio_operatorio: null;
        if (!empty($request->hora_inicio_operatorio)) {
            $atencion->hora_inicio_operatorio = $request->hora_inicio_operatorio;
        } elseif (!empty($request->cirugia_inicio2)) {
            $atencion->hora_inicio_operatorio = $request->cirugia_inicio2;
        } else {
            $atencion->hora_inicio_operatorio = null; 
        }
        // $atencion->hora_fin_operatorio = !empty($request->hora_fin_operatorio) ? $request->hora_fin_operatorio: null;
        if (!empty($request->hora_fin_operatorio)) {
            $atencion->hora_fin_operatorio = $request->hora_fin_operatorio;
        } elseif (!empty($request->cirugia_termino2)) {
            $atencion->hora_fin_operatorio = $request->cirugia_termino2;
        } else {
            $atencion->hora_fin_operatorio = null; // O un valor por defecto
        }
        $minutos = 0;
         if (!empty($request->tipo_real) && $request->tipo_real!='00:00' && empty($request->hora_duracion_operatorio)) {
            $hora = $request->tipo_real;
            $partes = explode(':', $hora);

            if (count($partes) == 2) {
                $h = (int)$partes[0];
                $m = (int)$partes[1];
                $minutos = ($h * 60) + $m;
            }
        }else if (!empty($request->hora_duracion_operatorio)) {
            $minutos = (int)$request->hora_duracion_operatorio;
        }
        $atencion->hora_duracion_operatorio =  $minutos;
        $atencion->save();
        
        $fechahora = Carbon::parse($request->fecha_hora_atencion)->format('Y-m-d H:i:s');
        $prestacion = Prestaciones::find($id_prestacion);
        $prestacion->fecha_atencion = $fechahora;
        $prestacion->save();

        $model_intraoperatorio = new IntraOperatorio();
        $intervencion = $request->id_intervencion;
        if($model_intraoperatorio::where('id_intervencion',$intervencion)->exists()){
          $data_intra = [
            'anestesia_inicio' => $request->anestesia_inicio2 ?$request->anestesia_inicio2: null,
            'anestesia_termino' => $request->anestesia_termino2 ?$request->anestesia_termino2: null,
            'cirugia_inicio'  => null,
            'cirugia_termino' => null,
            'tipo_real'       => null,
            'hora_llegada_urpa' => $request->hora_llegada_urpa2 ?$request->hora_llegada_urpa2: null,
            'salida_paciente' => $request->salida_paciente2 ?$request->salida_paciente2: null,
            'complicaciones' => !empty($request->complicaciones) && $request->complicacion=='si'?$request->complicaciones: '',
            'id_cqx_tipo_anestesia' => $request->id_cqx_tipo_anestesia2 ?$request->id_cqx_tipo_anestesia2: null,
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_us' => Auth::user()->id
          ];
          $model_intraoperatorio->actualizar($data_intra, $intervencion);
        }

        // if(empty($request->id_hospitalizacion)){
        //   $modelo_prestacion = new \App\Models\Prestaciones();
        //   $prestacion = $modelo_prestacion::find($atencion->id_prestacion);
        //   if(!empty($prestacion->codigo_upss)){
        //     $prestacion->codigo_upss=null;//lo ponemos null para que permita admisionar en hospitalizacion
        //   }
        //   $prestacion->save();
        // }
        //DATOS DE EXAMEN DE PATOLOGIA
        if (intval($request->tiene_examen_pat) === 1 && !empty($request->id_procedimiento_examen_patologia) && is_array($request->id_procedimiento_examen_patologia)):
            foreach($request->id_procedimiento_examen_patologia as $key=>$id_procedimiento){
              //PROCEDIMIENTOS POST CQX 
              $model_cq_procedimiento = new \App\Models\CqxProcedimiento();
              $model_cq_procedimiento->RegistrarProcedimientoPreQx($request->id_intervencion, $id_procedimiento, Auth::user()->id, 0,2,true);
            }
        endif;
       
        DB::commit(); 
        if(!empty($request->antesdefirmar_guadardatos) && $request->antesdefirmar_guadardatos == 1){
          return response()->json(['success' => true, 'firmar'=>'ok']);
        }
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        DB::rollback();
        Log::error('Error al querer guardar datos de Atencion Quirurgica Detalle', [
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
  function receta_post_quirurgica($model_cqxanestesia, $request)
  {
    if ($request->id_producto_pq != null) {
      $prestacion_unico = DB::table('prestaciones as pre')->where('pre.flag_intervencion_quirurgica','=',true)->where('pre.id_intervencion','=',$request->id_intervencion)
                                   ->select('pre.*')->first(); 
      $id_prestacion = !empty($prestacion_unico)  ? $prestacion_unico->id  : $request->id_prestacion_atq ; 
      
      $p_producto = "";
      $p_producto .= "{";
      if (!empty($request->id_producto_pq) && is_array($request->id_producto_pq)) :
        foreach ($request->id_producto_pq as $key => $value) :
          if ($request->id_producto_pq[$key] != "") {
            $p_producto .= "{";
            $p_producto .= $request->id_producto_pq[$key] . ",";
            $p_producto .= $request->tiempo_periodo_pq[$key] . ",";
            $p_producto .= $request->nro_med_solictados_pq[$key] . ",";
            $p_producto .= (isset($request->nro_med_entregados_pq[$key]) && $request->nro_med_entregados_pq[$key] != "") ? $request->nro_med_entregados_pq[$key] : 0;
            $p_producto .= ",";
            $p_producto .= (isset($request->nro_stocks_almacen_pq[$key]) && $request->nro_stocks_almacen_pq[$key] != "") ? $request->nro_stocks_almacen_pq[$key] : 0;
            $p_producto .= ",";
            $p_producto .= (isset($request->nro_stocks_establecimiento_pq[$key]) && $request->nro_stocks_establecimiento_pq[$key] != "") ? $request->nro_stocks_establecimiento_pq[$key] : 0;
            $p_producto .= ",";
            $p_producto .= (isset($request->nro_stocks_pq[$key]) && $request->nro_stocks_pq[$key] != "") ? $request->nro_stocks_pq[$key] : 0;
            $p_producto .= ",";
            $p_producto .= (isset($request->posologia_pq[$key]) && $request->posologia_pq[$key] != "") ? str_replace(",", "###", $request->posologia_pq[$key]) : "-";
            $p_producto .= ",";
            $p_producto .= (isset($request->miSelectProdPQ[$key]) && $request->miSelectProdPQ[$key] != "") ? $request->miSelectProdPQ[$key] : 0;
            $p_producto .= ",";
            $p_producto .= (isset($request->id_via_administracion_pq[$key]) && $request->id_via_administracion_pq[$key] != "") ? $request->id_via_administracion_pq[$key] : 0;
            $p_producto .= "},";
          }
        endforeach;
        if (strlen($p_producto) > 1) $p_producto = substr($p_producto, 0, -1);
      endif;
      $p_producto .= "}";

      if (isset($firma_receta_existente)) {
        $id_receta = $model_cqxanestesia->agregarProductoRecetas($request->id_receta_vale_editar, $p_producto);
        DB::commit();
        return response()->json(['success' => true, 'id_receta' => $id_receta]);
      } else {
        $model_cqxanestesia->agregarProductoRecetas($request->id_receta_vale_editar, $p_producto);
      }
    }
    ////================== AGREGAR NUEVOS MEDICAMENTOS DE UNA RECETA NUEVA =======================
    if ($request->id_producto_adicional_pq != null) {
      $p_producto_adicional_pq = "";
      $p_producto_adicional_pq .= "{";
      if (!empty($request->id_producto_adicional_pq) && is_array($request->id_producto_adicional_pq)) :
        foreach ($request->id_producto_adicional_pq as $key => $value) :
          if ($request->id_producto_adicional_pq[$key] != "") {
            $p_producto_adicional_pq .= "{";
            $p_producto_adicional_pq .= $request->id_producto_adicional_pq[$key] . ",";
            $p_producto_adicional_pq .= $request->tiempo_periodo_adicional_pq[$key] . ",";
            $p_producto_adicional_pq .= $request->nro_med_solictados_atencion_adicional_pq[$key] . ",";
            $p_producto_adicional_pq .= (isset($request->nro_med_entregados_adicional_pq[$key]) && $request->nro_med_entregados_adicional_pq[$key] != "") ? $request->nro_med_entregados_adicional_pq[$key] : 0;
            $p_producto_adicional_pq .= ",";
            $p_producto_adicional_pq .= (isset($request->nro_stocks_almacen_adicional_pq[$key]) && $request->nro_stocks_almacen_adicional_pq[$key] != "") ? $request->nro_stocks_almacen_adicional_pq[$key] : 0;
            $p_producto_adicional_pq .= ",";
            $p_producto_adicional_pq .= (isset($request->nro_stocks_establecimiento_adicional_pq[$key]) && $request->nro_stocks_establecimiento_adicional_pq[$key] != "") ? $request->nro_stocks_establecimiento_adicional_pq[$key] : 0;
            $p_producto_adicional_pq .= ",";
            $p_producto_adicional_pq .= (isset($request->nro_stocks_adicional_pq[$key]) && $request->nro_stocks_adicional_pq[$key] != "") ? $request->nro_stocks_adicional_pq[$key] : 0;
            $p_producto_adicional_pq .= ",";
            $p_producto_adicional_pq .= (isset($request->posologiaAdicional_pq[$key]) && $request->posologiaAdicional_pq[$key] != "") ? str_replace(",", "###", $request->posologiaAdicional_pq[$key]) : "-";
            $p_producto_adicional_pq .= ",";
            $p_producto_adicional_pq .= (isset($request->miSelectProd_pq[$key]) && $request->miSelectProd_pq[$key] != "") ? $request->miSelectProd_pq[$key] : 0;
            $p_producto_adicional_pq .= ",";
            $p_producto_adicional_pq .= (isset($request->id_via_administracion_adicional_pq[$key]) && $request->id_via_administracion_adicional_pq[$key] != "") ? $request->id_via_administracion_adicional_pq[$key] : 0;
            $p_producto_adicional_pq .= "},";
          }
        endforeach;
        if (strlen($p_producto_adicional_pq) > 1) $p_producto_adicional_pq = substr($p_producto_adicional_pq, 0, -1);
      endif;
      $p_producto_adicional_pq .= "}";
      $id_farmacia_receta_pq        =  ($request->id_farmacia_postqx != "") ? $request->id_farmacia_postqx : 0;
      $id_receta_vale_bus = 0;
      if (Auth::user()->tipo_registro == 3) $id_receta_vale_bus = 1;

      $hora = date('H');
      if ($hora > 6 && $hora < 13) $turno = "M";
      elseif ($hora >= 13 && $hora < 19) $turno = "T";
      elseif ($hora >= 19 && $hora <= 24) $turno = "N";
      else $turno = "MD";

      $model_prestacion = new \App\Models\Prestaciones;
      $prestacion = $model_prestacion->GetPrestacionById($request->id_prestacion_atq);
      $data = array(
        'p_id_receta_vale' => 0,
        'p_id_farmacia' => $id_farmacia_receta_pq,
        'p_id_user' => Auth::user()->id,
        'p_id_consultorio' => $request->id_servicio_encargado,
        'p_nro_receta' => '',
        'p_digitos_adicionales' => '',
        'p_dni_beneficiario' => $prestacion->nro_doc_ident,
        'p_cip_beneficiario' => $prestacion->cip,
        'p_tipdoc_beneficiario' => $prestacion->tipo_doc_ident,
        'p_nombre_beneficiario' => $prestacion->asegurado_nombre,
        'p_paterno_beneficiario' => $prestacion->asegurado_paterno,
        'p_materno_beneficiario' => $prestacion->asegurado_materno,
        'p_tipo_beneficiario' => $prestacion->parentesco,
        'p_dni_titular' => $prestacion->nrodoctitular,
        'p_tipdoc_titular' => $prestacion->tipodoc_titular,
        'p_nombre_titular' => $prestacion->nombre_titular,
        'p_paterno_titular' => $prestacion->paterno_titular,
        'p_materno_titular' => $prestacion->materno_titular,
        'p_fecha_expedicion' => (isset($request->fecha_expedicion)) ? $request->fecha_expedicion : '',
        'p_fecha_atencion' => Carbon::parse($request->fecha_hora_atencion)->format('Y-m-d'),
        'p_nro_historia' => $prestacion->nro_historia,
        'p_grado' => $prestacion->grado,
        'p_id_medico' => $prestacion->id_medico,
        'p_tipo_receta' => $prestacion->id_tipo_atencion,
        'p_turno' => $turno,
        'p_obs_registro' => '',
        'p_id_receta_vale_bus' => $id_receta_vale_bus,
        'p_id_admision' => 0,
        'p_tipo_via' => '',
        'p_sub_via' => '',
        'p_final_via' => '',
        'p_nro_guia' => '',
        'p_interconsulta' => '',
        'p_interconsulta_dias' => 0,
        'p_id_prestacion' => $id_prestacion,
        'p_id_tipo_num' => 0,
        'p_estado' => 2, //estado de la receta
        'p_detalle' => $p_producto_adicional_pq,
        'p_sexo' => $prestacion->sexo,
        'p_fecha_nacimiento' => $prestacion->fecha_nac,
        'p_codigo_upss' => $request->codigo_upss_atencioncqx
      );
      if ($request->firma_receta_adicional == 1) {
        $id_receta = $model_prestacion->registrarRecetaValeAtencionDetalle_cqx($data);
        DB::commit();
        return response()->json(['success' => true, 'id_receta' => $id_receta]);
      } else {
        $id_receta = $model_prestacion->registrarRecetaValeAtencionDetalle_cqx($data);
      }
    }
  }
  function receta_post_quirurgica_adicional($request)
  {
    $prestacion_unico = DB::table('prestaciones as pre')->where('pre.flag_intervencion_quirurgica','=',true)->where('pre.id_intervencion','=',$request->id_intervencion)
                                   ->select('pre.*')->first(); 
    $id_prestacion = !empty($prestacion_unico)  ? $prestacion_unico->id  : $request->id_prestacion_atq ; 

    $p_producto_adicional_pq = "";
    $p_producto_adicional_pq .= "{";
    if (!empty($request->id_producto_adicional_pq) && is_array($request->id_producto_adicional_pq)) :
      foreach ($request->id_producto_adicional_pq as $key => $value) :
        if ($request->id_producto_adicional_pq[$key] != "") {
          $p_producto_adicional_pq .= "{";
          $p_producto_adicional_pq .= $request->id_producto_adicional_pq[$key] . ",";
          $p_producto_adicional_pq .= $request->tiempo_periodo_adicional_pq[$key] . ",";
          $p_producto_adicional_pq .= $request->nro_med_solictados_atencion_adicional_pq[$key] . ",";
          $p_producto_adicional_pq .= (isset($request->nro_med_entregados_adicional_pq[$key]) && $request->nro_med_entregados_adicional_pq[$key] != "") ? $request->nro_med_entregados_adicional_pq[$key] : 0;
          $p_producto_adicional_pq .= ",";
          $p_producto_adicional_pq .= (isset($request->nro_stocks_almacen_adicional_pq[$key]) && $request->nro_stocks_almacen_adicional_pq[$key] != "") ? $request->nro_stocks_almacen_adicional_pq[$key] : 0;
          $p_producto_adicional_pq .= ",";
          $p_producto_adicional_pq .= (isset($request->nro_stocks_establecimiento_adicional_pq[$key]) && $request->nro_stocks_establecimiento_adicional_pq[$key] != "") ? $request->nro_stocks_establecimiento_adicional_pq[$key] : 0;
          $p_producto_adicional_pq .= ",";
          $p_producto_adicional_pq .= (isset($request->nro_stocks_adicional_pq[$key]) && $request->nro_stocks_adicional_pq[$key] != "") ? $request->nro_stocks_adicional_pq[$key] : 0;
          $p_producto_adicional_pq .= ",";
          $p_producto_adicional_pq .= (isset($request->posologiaAdicional_pq[$key]) && $request->posologiaAdicional_pq[$key] != "") ? str_replace(",", "###", $request->posologiaAdicional_pq[$key]) : "-";
          $p_producto_adicional_pq .= ",";
          $p_producto_adicional_pq .= (isset($request->miSelectProdAdicional_pq[$key]) && $request->miSelectProdAdicional_pq[$key] != "") ? $request->miSelectProdAdicional_pq[$key] : 0;
          $p_producto_adicional_pq .= ",";
          $p_producto_adicional_pq .= (isset($request->id_via_administracion_adicional_pq[$key]) && $request->id_via_administracion_adicional_pq[$key] != "") ? $request->id_via_administracion_adicional_pq[$key] : 0;
          $p_producto_adicional_pq .= "},";
        }
      endforeach;
      if (strlen($p_producto_adicional_pq) > 1) $p_producto_adicional_pq = substr($p_producto_adicional_pq, 0, -1);
    endif;
    $p_producto_adicional_pq .= "}";
    //$id_farmacia_receta_pq        =  ($request->id_farmacia!="") ? $request->id_farmacia : 0;
    $id_farmacia_receta_pq        =  ($request->id_farmacia_postqx != "") ? $request->id_farmacia_postqx : 0;
    $id_receta_vale_bus = 0;
    if (Auth::user()->tipo_registro == 3) $id_receta_vale_bus = 1;

    $hora = date('H');
    if ($hora > 6 && $hora < 13) $turno = "M";
    elseif ($hora >= 13 && $hora < 19) $turno = "T";
    elseif ($hora >= 19 && $hora <= 24) $turno = "N";
    else $turno = "MD";

    $model_prestacion = new \App\Models\Prestaciones;
    $prestacion = $model_prestacion->GetPrestacionById($request->id_prestacion_atq);
    $model_medicos = new Medico;
    $medico = $model_medicos->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni, Auth::user()->id_establecimiento);
    $id_medico_pq = (!empty($medico) && isset($medico->id)) ? $medico->id : $prestacion->id_medico;
    $fecha_expedicion_pq = !empty($request->fecha_expedicion_postqx) ? Carbon::parse($request->fecha_expedicion_postqx)->format('d-m-Y') : ((isset($request->fecha_expedicion)) ? $request->fecha_expedicion : '');
    $data = array(
      'p_id_receta_vale' => 0,
      'p_id_farmacia' => $id_farmacia_receta_pq,
      'p_id_user' => Auth::user()->id,
      'p_id_consultorio' => $request->id_servicio_encargado,
      'p_nro_receta' => '',
      'p_digitos_adicionales' => '',
      'p_dni_beneficiario' => $prestacion->nro_doc_ident,
      'p_cip_beneficiario' => $prestacion->cip,
      'p_tipdoc_beneficiario' => $prestacion->tipo_doc_ident,
      'p_nombre_beneficiario' => $prestacion->asegurado_nombre,
      'p_paterno_beneficiario' => $prestacion->asegurado_paterno,
      'p_materno_beneficiario' => $prestacion->asegurado_materno,
      'p_tipo_beneficiario' => $prestacion->parentesco,
      'p_dni_titular' => $prestacion->nrodoctitular,
      'p_tipdoc_titular' => $prestacion->tipodoc_titular,
      'p_nombre_titular' => $prestacion->nombre_titular,
      'p_paterno_titular' => $prestacion->paterno_titular,
      'p_materno_titular' => $prestacion->materno_titular,
      'p_fecha_expedicion' => $fecha_expedicion_pq,
      'p_fecha_atencion' => Carbon::parse($request->fecha_hora_atencion)->format('Y-m-d'),
      'p_nro_historia' => $prestacion->nro_historia,
      'p_grado' => $prestacion->grado,
      'p_id_medico' => $id_medico_pq,
      'p_tipo_receta' => $prestacion->id_tipo_atencion,
      'p_turno' => $turno,
      'p_obs_registro' => '',
      'p_id_receta_vale_bus' => $id_receta_vale_bus,
      'p_id_admision' => 0,
      'p_tipo_via' => '',
      'p_sub_via' => '',
      'p_final_via' => '',
      'p_nro_guia' => '',
      'p_interconsulta' => '',
      'p_interconsulta_dias' => 0,
      'p_id_prestacion' => $id_prestacion,
      'p_id_tipo_num' => 0,
      'p_estado' => 2, //estado de la receta
      'p_detalle' => $p_producto_adicional_pq,
      'p_sexo' => $prestacion->sexo,
      'p_fecha_nacimiento' => $prestacion->fecha_nac,
      'p_codigo_upss' => $request->codigo_upss_atencioncqx
    );
    return $model_prestacion->registrarRecetaValeAtencionDetalle_cqx($data);
  }
  /******************FIRMA ATENCION QUIRURGICA*****************/
  public function atencionPDFfirmar($id_atencion){
    $resultado=array();
    try{
      $path = public_path('tmp'.DIRECTORY_SEPARATOR.'csv');
      $path_zip = public_path('tmp'.DIRECTORY_SEPARATOR.'zip');
    
      if (!is_dir($path)) {
          mkdir($path);
      }
      if (!is_dir($path_zip)) {
          mkdir($path_zip);
          mkdir(public_path('tmp'.DIRECTORY_SEPARATOR.'zip'.DIRECTORY_SEPARATOR.'firmados'));
          mkdir(public_path('tmp'.DIRECTORY_SEPARATOR.'zip'.DIRECTORY_SEPARATOR.'sin_firma'));
      } 
      $csv = "";
      if (Auth::check()):
          $csv .= Auth::user()->id;
      endif;

      $csv .= "_".date("YmdHis");
      $csv.= "_atencion_quirurgica";
      Excel::create($csv, function($excel) use($id_atencion){
          $excel->sheet('GENERAL', function($sheet) use($id_atencion) {
              $variable = [];
              //ATENCION QUIRURGICA
              $this->atencionGenerarPdfParaFirma($id_atencion);
              array_push($variable, array(url().DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'sin_firma'.DIRECTORY_SEPARATOR.'atencion_quirurgica_'.$id_atencion.'.pdf', url().DIRECTORY_SEPARATOR.'upload_firma'.DIRECTORY_SEPARATOR.'?name=cf_atencion_quirurgica_'.$id_atencion.'.pdf&id_tipo=atencion_quirurgica&id_resol='.$id_atencion, 'cf_atencion_quirurgica_'.$id_atencion.'.pdf','0','45','350','70','Fecha de Firma: <DATE> Firmante: <SIGNER>',url().DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'escudo.jpg','1','7'));

              //PAPELETA DE GRESO
              //$model_hospitalizacion = new Hospitalizacione();
              // $cqx_atencion = DB::table('cqx_atenciones')
              // ->select(
              //     'cqx_atenciones.id_hospitalizacion',
              //     'cqx_atenciones.id_prestacion',
              //     'cqx_atenciones.id_cqx_intervencion',
              //     'cqx_atenciones.id'
              // )
              // ->where('cqx_atenciones.id', $id_atencion)
              // ->first();
              $cqx_atencion = DB::table('cqx_atenciones as ate')
              ->select(
                  'ate.id_hospitalizacion',
                  'ate.id_prestacion',
                  'ate.id_cqx_intervencion',
                  'ate.id',
                  'ci.id_tipo_atencion_origen'
              )
              ->join('cqx_intervenciones as ci', 'ate.id_cqx_intervencion', '=', 'ci.id')
              ->where('ate.id', $id_atencion)
              ->first();
              $origen = !empty($cqx_atencion)? $cqx_atencion->id_tipo_atencion_origen: null;
              
              $es_domiciliaria = \App\Models\Hospitalizacione::where('id', $cqx_atencion->id_hospitalizacion)->where('id_tipo_egreso',1)->exists();
              if($es_domiciliaria){
                $this->genera_hospitalizacion_papeleta_egreso($cqx_atencion->id_hospitalizacion, true);
                array_push($variable, array(url().'/tmp/pdf/sin_firma/hospitalizacion-papeleta-egreso_'.$cqx_atencion->id_hospitalizacion.'.pdf', url().'/upload_firma/?name=cf_hospitalizacion-papeleta-egreso_'.$cqx_atencion->id_hospitalizacion.'.pdf&id_tipo=prestacion&id_resol='.$cqx_atencion->id_hospitalizacion, 'cf_hospitalizacion-papeleta-egreso_'.$cqx_atencion->id_hospitalizacion.'.pdf','0','45','350','70','Fecha de Firma: <DATE> Firmante: <SIGNER>',url().'/images/escudo.jpg','1','7'));
                if ($origen !== null && ((int)$origen === 1 || (int)$origen === 2)) {
                  $this->genera_atencion_quirurgico_egreso($cqx_atencion->id_cqx_intervencion, true);
                  array_push($variable, array(url().'/tmp/pdf/sin_firma/atencion-quirurgico-epicrisis_'.$cqx_atencion->id_cqx_intervencion.'.pdf', url().'/upload_firma/?name=cf_atencion-quirurgico-epicrisis_'.$cqx_atencion->id_cqx_intervencion.'.pdf&id_tipo=prestacion&id_resol='.$cqx_atencion->id_cqx_intervencion, 'cf_atencion-quirurgico-epicrisis_'.$cqx_atencion->id_cqx_intervencion.'.pdf','0','45','350','70','Fecha de Firma: <DATE> Firmante: <SIGNER>',url().'/images/escudo.jpg','1','7'));
                }
              }
              //FIN PAPELETA DE EGRESO

              //REPORTE OPRATORIO
              $this->genera_reporte_operatorio_firmado($cqx_atencion->id_cqx_intervencion);
              array_push($variable, array(url().'/tmp/pdf/sin_firma/reporte_operatorio_'.$cqx_atencion->id_cqx_intervencion.'.pdf', url().'/upload_firma/?name=cf_reporte_operatorio_'.$cqx_atencion->id_cqx_intervencion.'.pdf&id_tipo=reporte_operatorio&id_resol='.$cqx_atencion->id_cqx_intervencion, 'cf_reporte_operatorio_'.$cqx_atencion->id_cqx_intervencion.'.pdf','0','425','300','70','Fecha de Firma: <DATE> Firmante: <SIGNER>',url().'/images/escudo.jpg','1','7'));
              //FIN REPORTE OPERATORIO

              //RECETA
              $recetas = DB::table('receta_vales')
                  ->where('receta_vales.id_prestacion', $cqx_atencion->id_prestacion)
                  ->where('receta_vales.flag', 0)
                  ->get();
              if (count($recetas) > 0) {
                foreach($recetas as $receta){
                  $this->recetaGenerarPdfParaFirma($receta->id);
                  array_push($variable, array(url().DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'sin_firma'.DIRECTORY_SEPARATOR.'receta_atencion_quirurgica_'.$receta->id.'.pdf', url().DIRECTORY_SEPARATOR.'upload_firma'.DIRECTORY_SEPARATOR.'?name=cf_receta_atencion_quirurgica_'.$receta->id.'.pdf&id_tipo=receta_atencion_quirurgica&id_resol='.$receta->id, 'cf_receta_atencion_quirurgica_'.$receta->id.'.pdf','0','45','350','70','Fecha de Firma: <DATE> Firmante: <SIGNER>',url().DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'escudo.jpg','1','7'));
                }
              }
              //FIN RECETA

              $sheet->fromArray($variable, null, '', false, false);
          });
        })->store('csv',public_path('tmp'.DIRECTORY_SEPARATOR.'csv'));
        $obj = new Archive7z(public_path('tmp'.DIRECTORY_SEPARATOR.'zip'.DIRECTORY_SEPARATOR.'sin_firma'.DIRECTORY_SEPARATOR.$csv.'.7z'));
        $obj->addEntry(public_path(DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'sin_firma'.DIRECTORY_SEPARATOR.'atencion_quirurgica_'.$id_atencion.'.pdf'));
        

        // $cqx_atencion = DB::table('cqx_atenciones')
        // ->select(
        //     'cqx_atenciones.id_hospitalizacion',
        //     'cqx_atenciones.id_prestacion',
        //     'cqx_atenciones.id',
        //     'cqx_atenciones.id_cqx_intervencion'
        // )
        // ->where('cqx_atenciones.id', $id_atencion)
        // ->first();
        $cqx_atencion = DB::table('cqx_atenciones as ate')
            ->select(
                'ate.id_hospitalizacion',
                'ate.id_prestacion',
                'ate.id_cqx_intervencion',
                'ate.id',
                'ci.id_tipo_atencion_origen'
            )
            ->join('cqx_intervenciones as ci', 'ate.id_cqx_intervencion', '=', 'ci.id')
            ->where('ate.id', $id_atencion)
            ->first();
        $origen = !empty($cqx_atencion)? $cqx_atencion->id_tipo_atencion_origen: null;
        $es_domiciliaria = \App\Models\Hospitalizacione::where('id', $cqx_atencion->id_hospitalizacion)->where('id_tipo_egreso',1)->exists();
        if($es_domiciliaria){
          $obj->addEntry(public_path("/tmp/pdf/sin_firma/hospitalizacion-papeleta-egreso_{$cqx_atencion->id_hospitalizacion}.pdf")); 
          if ($origen !== null && ($origen === 1 || $origen === 2)) {
            $obj->addEntry(public_path("/tmp/pdf/sin_firma/atencion-quirurgico-epicrisis_{$cqx_atencion->id_cqx_intervencion}.pdf"));
          }
        }

        $obj->addEntry(public_path("/tmp/pdf/sin_firma/reporte_operatorio_{$cqx_atencion->id_cqx_intervencion}.pdf")); 
        
        $recetas = DB::table('receta_vales')
                  ->where('receta_vales.id_prestacion', $cqx_atencion->id_prestacion)
                  ->where('receta_vales.flag', 0)
                  ->get();
        if (count($recetas) > 0) {
          foreach($recetas as $receta){
            $obj->addEntry(public_path(DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'sin_firma'.DIRECTORY_SEPARATOR.'receta_atencion_quirurgica_'.$receta->id.'.pdf')); 
          }
        }

      $resultado["csv"]=$csv;
      $resultado["sw"]=true;
      
    }catch(\Exception $e) { 
      Log::error('ERROR EN ATENCION QUIRURGICA FIRMA PDF: ' . $e->getMessage(), [
        'exception' => $e,
        'trace' => $e->getTraceAsString(),
      ]);
      $resultado["sw"] = false;
      $resultado["msg"] = "Error al generar PDF para firma: " . $e->getMessage();
    }
    return response()->json($resultado); 
  }
  public function atencionGenerarPdfParaFirma($id_atencion){
    try{
      $path = public_path('tmp/pdf');
      if (!is_dir($path)) {
        mkdir($path);
      }
      $path = public_path('tmp/pdf/sin_firma/');
      if (!is_dir($path)) {
        mkdir($path);
      }

      $model_prestacion = new Prestaciones;
      $model_receta = new Receta_vale;
      $model_intraoperatorio = new IntraOperatorio;
      $model_intervencion = new CqxIntervencion();
      $model_emergencia = new Emergencia();
      $model_hospitalizacion = new Hospitalizacione();
      
      $tipo_egreso="";
      $condicion_alta="";
      $cama="";
      $servicio_destino="";
      $grado=null;
      $persona_datos_adicionales=null;
      $titular=null;

      $cqx_atencion = DB::table('cqx_atenciones')
        ->leftJoin('hospitalizaciones', 'cqx_atenciones.id_hospitalizacion', '=', 'hospitalizaciones.id')
        ->leftJoin('medicos', 'hospitalizaciones.id', '=', 'hospitalizaciones.id')
        ->leftJoin('consultorios', 'hospitalizaciones.id_servicio_encargado', '=', 'consultorios.id')
        ->select(
            'cqx_atenciones.complicacion_medica',
            'cqx_atenciones.id_cqx_intervencion',
            'cqx_atenciones.id_prestacion',
            'cqx_atenciones.id_hospitalizacion',
            'cqx_atenciones.allazgo_operatorio',
            'cqx_atenciones.examen_bacteriologico',
            'cqx_atenciones.examen_patologico',
            'hospitalizaciones.id_tipo_egreso',
            'hospitalizaciones.id',
            'hospitalizaciones.id_cama_estado',
            'hospitalizaciones.id_condicion_alta',
            'consultorios.nombre as servicio_destino',
            DB::raw("CASE hospitalizaciones.id_condicion_alta
                        WHEN 1 THEN 'MEJORADO'
                        WHEN 2 THEN 'CURADO'
                        WHEN 3 THEN 'TRASLADO'
                        ELSE 'OTRO'
                    END AS condicion_alta")
        )
        ->where('cqx_atenciones.id', $id_atencion)
        ->first();
      if(isset($cqx_atencion->condicion_alta)){
        $condicion_alta=$cqx_atencion->condicion_alta;
        if($cqx_atencion->id_tipo_egreso==1){//Domiciliaria
          $tipo_egreso="DOMICILIARIA";
      }else{
          $tipo_egreso="HOSPITALIZADO";
          $servicio_destino=$cqx_atencion->servicio_destino;
          $cama = DB::table('cambio_camas') 
          ->join('camas', 'cambio_camas.id_cama', '=', 'camas.id')
          ->select('cambio_camas.id','camas.nombre')
          ->where('cambio_camas.id_prestacion', '=', $cqx_atencion->id_prestacion)
          ->orderBy('cambio_camas.id', 'desc')
          ->first();
        }
      }
    
      $prestacion = DB::table('prestaciones')
        ->join('cqx_atenciones as ca', 'prestaciones.id', '=', 'ca.id_prestacion')
        ->leftJoin('citas as c', 'prestaciones.id_cita', '=', 'c.id')
        ->leftJoin('medicos as m', 'prestaciones.id_medico', '=', 'm.id')
        ->leftJoin('profesiones as p', 'm.id_profesion', '=', 'p.id')
        ->leftJoin('asegurados', 'prestaciones.id_asegurado', '=', 'asegurados.id')
        ->leftJoin('establecimientos as e', 'prestaciones.id_establecimiento', '=', 'e.id')
        ->leftJoin('asegurado_historias as ah', function ($join) {
            $join->on('asegurados.id', '=', 'ah.id_asegurado')
                  ->on('e.id', '=', 'ah.id_establecimiento'); // Se agrega la condición aquí
        })
        ->leftJoin('tipo_beneficiarios as tb', 'asegurados.id_tipo_beneficiario', '=', 'tb.id')
        ->leftJoin('distritos', 'prestaciones.id_asegurado', '=', 'distritos.id_distrito')
        ->leftJoin('provincias', 'distritos.id_provincia', '=', 'provincias.id_provincia')
        ->leftJoin('departamentos', 'provincias.id_departamento', '=', 'departamentos.id_departamento')
        ->leftJoin('distritos as distrito_nacimiento', 'asegurados.id_distrito_nacimiento', '=', 'distrito_nacimiento.id_distrito')
        ->leftJoin('provincias as provincia_nacimiento', 'distrito_nacimiento.id_provincia', '=', 'provincia_nacimiento.id_provincia')
        ->leftJoin('departamentos as departamento_nacimiento', 'provincia_nacimiento.id_departamento', '=', 'departamento_nacimiento.id_departamento')
        ->leftJoin('distritos as distrito_procedencia', 'asegurados.id_distrito_procedencia', '=', 'distrito_procedencia.id_distrito')
        ->leftJoin('provincias as provincia_procedencia', 'distrito_procedencia.id_provincia', '=', 'provincia_procedencia.id_provincia')
        ->leftJoin('departamentos as departamento_procedencia', 'provincia_procedencia.id_departamento', '=', 'departamento_procedencia.id_departamento')
        ->leftJoin('razas', 'asegurados.id_raza', '=', 'razas.id')
        ->leftJoin('estado_civiles', 'asegurados.id_estado_civil', '=', 'estado_civiles.id')
        ->leftJoin('grado_instrucciones', 'asegurados.id_grado_instruccion', '=', 'grado_instrucciones.id')
        ->leftJoin('ocupaciones', 'asegurados.id_ocupacion', '=', 'ocupaciones.id')
        ->leftJoin('religiones', 'asegurados.id_religion', '=', 'religiones.id')
        ->leftJoin('tipo_sangres as ts','asegurados.id_tipo_sangre','=','ts.id')
        ->leftJoin('sub_consultorios as sc','prestaciones.id_sub_consultorio','=','sc.id')
        ->leftJoin('consultorios as co','sc.id_consultorio','=','co.id')
        ->select(
            'ca.codigo_siteds as codigo_siteds_cqx',
            'ca.id_hospitalizacion',
            'asegurados.*', 
            'distritos.descripcion as distrito', 
            'provincias.descripcion as provincia', 
            'departamentos.descripcion as departamento', 
            'razas.nombre as etnia',
            'estado_civiles.nombre as estado_civil', 
            'grado_instrucciones.nombre as grado_instruccion', 
            'ocupaciones.nombre as ocupacion', 
            'religiones.nombre as religion',
            'distrito_nacimiento.descripcion as distrito_nacimiento2', 
            'provincia_nacimiento.descripcion as provincia_nacimiento2', 
            'departamento_nacimiento.descripcion as departamento_nacimiento2',
            'distrito_procedencia.descripcion as distrito_procedencia2', 
            'provincia_procedencia.descripcion as provincia_procedencia2', 
            'departamento_procedencia.descripcion as departamento_procedencia2',
            DB::raw("date_part('year', age(to_date(to_char(asegurados.fecha_nac::date, 'DD/MM/YYYY'), 'DD/MM/YYYY'))) as edadpaciente"),
            'prestaciones.*',
            'prestaciones.cups_pnp as codigo_cups',
            DB::raw("to_char(prestaciones.created_at, 'HH:MI AM') hora_ingreso"),
            DB::raw("to_char(prestaciones.fecha_atencion, 'DD/MM/YYYY') fecha_ingreso"),
            'm.paterno as medico_paterno', 
            'm.materno as medico_materno', 
            'm.nombre as medico_nombre', 
            'm.colegiatura as profesional_colegiatura',
            'p.nombre as profesional_profesion',
            'ah.nro_historia',
            'tb.descripcion as tipo_beneficiario',
            'c.codigo_siteds',
            'prestaciones.cups_pnp',
            'ts.descripcion_corta as grupo_sangre',
            'co.nombre as servicio',
            'sc.descripcion as consultorio'
        )
        ->where('asegurados.id_estado_reg', 1)
        ->where('prestaciones.id', $cqx_atencion->id_prestacion)
        ->where('prestaciones.id_tipo_atencion', 3)
        ->where('prestaciones.id_estado_reg', 1)
        ->first();   
      
      $recetas = DB::table('receta_vales')
          ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
          ->select('receta_vales.*', 'farmacias.nombre as farmacia')
          ->where('receta_vales.id_prestacion', '=', $cqx_atencion->id_prestacion)
          ->where('receta_vales.estado','<>', 0)
          // ->where('receta_vales.flag','<>',1)
          ->orderBy('receta_vales.id', 'asc')
          ->get();
      
      $receta_productos = DB::table('receta_vales')
          ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
          ->join('productos', 'producto_recetas.id_producto', '=', 'productos.id')
          ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
          ->leftJoin('farmacias', 'producto_recetas.id_farmacia', '=', 'farmacias.id')
          ->leftJoin('via_administracion', 'producto_recetas.id_via_administracion', '=', 'via_administracion.id')
          ->where('receta_vales.id_prestacion', $cqx_atencion->id_prestacion)
          ->where('receta_vales.estado','<>',0)
          ->where('producto_recetas.estado','<>',0)
          ->select(
              'receta_vales.id as id_receta',
              'producto_recetas.id as id_producto_receta',
              'productos.codigo',
              'productos.nombre',
              'productos.id as id_producto',
              'productos.presentacion_comercial as presentacion',
              'producto_recetas.*',
              'unidades.abreviatura',
              'farmacias.nombre as farmacia',
              'via_administracion.descripcion as via'
          )
          ->orderBy('receta_vales.id', 'asc')
          ->orderBy('producto_recetas.id', 'asc')
          ->get();
      
      $cirujanos = $model_intraoperatorio->profesiones(0,$cqx_atencion->id_cqx_intervencion);//CIRUJANOS
      $anestesiologos = $model_intraoperatorio->profesiones(1,$cqx_atencion->id_cqx_intervencion);//ANESTESIOLOGOS
      $instrumentistas="";
      $intraoperatorio = $this->anestesiaIdIntervencion($cqx_atencion->id_cqx_intervencion);
      if (!empty($intraoperatorio) && !empty($intraoperatorio->id_intraoperatorio)) {
        $instrumentistas = $model_intraoperatorio->profesiones_intrumentista(2, $intraoperatorio->id_intraoperatorio); // INSTRUMENTISTAS
      }
      
      if ($prestacion->id_asegurado_titular != null || $prestacion->id_asegurado_titular > 0) {
          $titular = DB::table('asegurados')
              ->select('asegurados.*')
              ->where('id',$prestacion->id_asegurado_titular)
              ->where('id_estado_reg', 1)
              ->first();
          
          if ($titular != null) {
              $persona_datos_adicionales = DB::table('persona_datos_adicionales')
                  ->select('persona_datos_adicionales.*')
                  ->where('id_asegurado',$titular->id)
                  ->first();

              if ($persona_datos_adicionales != null) {
                  $grado = DB::table('titular_grados')
                  ->select('titular_grados.*')
                  ->where('id',$persona_datos_adicionales->id_grado)
                  ->first();
              }
          }
      }
      $grado = isset($grado) ? $grado : null;
      $intervencion = $model_intervencion->GetById($cqx_atencion->id_cqx_intervencion);
      $datos = $model_hospitalizacion->get_reporte_operatorio_cqx($cqx_atencion->id_cqx_intervencion);
      // $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/".date("Y/m/d")."/cf_receta_atencion_quirurgica_".$id_atencion.".pdf";
      // $codigo_qr = QrCode::format('png')->size(150)->generate("https://sigesapol.dirsapolpnp.gob.pe/".date("Y/m/d")."/cf_atencion_quirurgica_".$id_atencion.".pdf");
      
      $pdf = \PDF::loadView('frontend.centro_quirurgico.atencion_quirurgica.pdf_atencion_quirurgica_firmado', 
      compact('cqx_atencion','tipo_egreso','condicion_alta','cama','servicio_destino','datos','intervencion','intraoperatorio','cirujanos','anestesiologos','instrumentistas','prestacion','recetas','receta_productos','titular','grado','persona_datos_adicionales'));
      $pdf->getDomPDF()->set_option("enable_php", true);
      $pdf->setPaper('A4', 'portrait');
      file_put_contents($path."atencion_quirurgica_".$id_atencion.".pdf", $pdf->output());
    }catch(\Exception $e) { 
      Log::error('ERROR EN ATENCION QUIRURGICA FIRMA PDF: ' . $e->getMessage(), [
        'exception' => $e,
        'trace' => $e->getTraceAsString(),
      ]);
      $resultado["sw"] = false;
      $resultado["msg"] = "Error al generar PDF para firma: " . $e->getMessage();
    }
  }
  public function atencionPostArguments(Request $request)
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
                    "posy":"10",
                    "fontSize":"7",
                    "dcfilter": ".*FIR.*|.*FAU.*",
                    "signatureLevel":"0",
                    "maxFileSize":"5242880"
                }';
      echo  base64_encode($param);
    }
  }
  public function atencionActualizarFirmaConToken(Request $request){
    // $model_cqx_atenciones = new \App\Models\CqxAtencione();
    // $id = intval($request->id_resol_atencion_cqx);
    // $cqx_atenciones = $model_cqx_atenciones::find($id);
    // if($cqx_atenciones->fecha_firma == ""){
    //     $cqx_atenciones->fecha_firma = date("d-m-Y H:i:s");
    //     $cqx_atenciones->ruta_firma = date("Y/m/d")."/cf_atencion_quirurgica_".$cqx_atenciones->id.".pdf";
    //     $cqx_atenciones->flag_firma = 1;
    //     $cqx_atenciones->save();
    // }
    // return "ok";
    try {
        $id = intval($request->id_resol_atencion_cqx);
        $cqx_atenciones = \App\Models\CqxAtencione::find($id);
        if ($cqx_atenciones && ($cqx_atenciones->fecha_firma === null || $cqx_atenciones->fecha_firma === '')) {
            $cqx_atenciones->fecha_firma = date("d-m-Y H:i:s");
            $cqx_atenciones->ruta_firma = date("Y/m/d")."/cf_atencion_quirurgica_".$cqx_atenciones->id.".pdf";
            $cqx_atenciones->flag_firma = 1;
            $cqx_atenciones->save();
        }
        $cqx_intervencion = \App\Models\CqxIntervencion::find($cqx_atenciones->id_cqx_intervencion);
        $origen = !empty($cqx_intervencion)? $cqx_intervencion->id_tipo_atencion_origen: null;
        //$id_tipo_egreso = !empty($cqx_intervencion)? $cqx_intervencion->id_tipo_egreso: null;
        if ($cqx_intervencion && ($cqx_intervencion->fecha_firma_reporte_operatorio === null || $cqx_intervencion->fecha_firma_reporte_operatorio === '')) {
            $cqx_intervencion->fecha_firma_reporte_operatorio = date("d-m-Y H:i:s");
            $cqx_intervencion->ruta_firma_reporte_operatorio = date("Y/m/d")."/cf_reporte_operatorio_".$cqx_atenciones->id_cqx_intervencion.".pdf";
            $cqx_intervencion->flag_firma_reporte_operatorio = 1;
            $cqx_intervencion->save();
        }
         $dato_intervencion = \App\Models\CqxAtencione::join('cqx_intervenciones', 'cqx_intervenciones.id', '=', 'cqx_atenciones.id_cqx_intervencion')
        ->where('cqx_atenciones.id', $id)
        ->select('cqx_intervenciones.id_hospitalizacion')
        ->first();

        if ($dato_intervencion && $dato_intervencion->id_hospitalizacion) {
            $alta_pac = \App\Models\Hospitalizacione::where('id', $dato_intervencion->id_hospitalizacion)->first();
            
            if ($alta_pac->fecha_firma_alta_medica == "") {
                $alta_pac->fecha_firma_alta_medica = date("d-m-Y H:i:s");
                $alta_pac->ruta_papeleta_egreso = date("Y/m/d") . "/cf_hospitalizacion-papeleta-egreso_" . $dato_intervencion->id_hospitalizacion . ".pdf";
                $alta_pac->flag_alta_medica = 1;
                $alta_pac->save();
            }
            if ($alta_pac->ruta_epicrisis == ""  && $alta_pac->id_tipo_egreso!==null && (int)$alta_pac->id_tipo_egreso==1 && $origen !== null && ((int)$origen === 1 || (int)$origen === 2)) {
                $alta_pac->ruta_epicrisis = date("Y/m/d") . "/cf_atencion-quirurgico-epicrisis_" . $cqx_atenciones->id_cqx_intervencion . ".pdf";
                $alta_pac->save();
            }
        
            $recetas = \App\Models\Receta_vale::where('id_prestacion', $cqx_atenciones->id_prestacion)->get();
            foreach($recetas as $receta){
                if($receta->fecha_firma == ""){
                    $receta->fecha_firma = date("d-m-Y H:i:s");
                    $receta->ruta_receta = date("Y/m/d") . "/cf_receta_atencion_quirurgica_" . $receta->id. ".pdf";
                    $receta->flag = 1;
                    $receta->save();
                }
            }
        }

        return response()->json([
          'status' => 'ok'
        ]);
    } catch (\Throwable $e) {
        Log::error('Error al querer Actualizar la Atencion Firma Con Token', [
              'message' => $e->getMessage(),
              'code' => $e->getCode(),
              'file' => $e->getFile(),
              'line' => $e->getLine(),
              'trace' => $e->getTraceAsString(),
          ]);
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 400);
    }
  }
  
  /******************FIN FIRMA ATENCION QUIRURGICA*****************/

  /******************ATENCION QUIRURGICA SIN FIRMA*****************/
   public function atencionActualizarFirmaSinToken(Request $request){
    $model_cqx_atenciones = new \App\Models\CqxAtencione();
    $id = intval($request->id_resol_atencion_cqx);

    $cqx_atenciones = $model_cqx_atenciones::find($id);
    if($cqx_atenciones->fecha_firma == ""){
        $cqx_atenciones->fecha_firma = date("d-m-Y H:i:s");
        $cqx_atenciones->ruta_firma = date("Y/m/d")."/cf_atencion_quirurgica_".$cqx_atenciones->id.".pdf";
        $cqx_atenciones->flag_firma = 2;
        $cqx_atenciones->save();
    }
    return "ok";
  }
  /******************FIN ATENCION QUIRURGICA SIN FIRMA*****************/

  /******************FIRMA RECETA ATENCION QUIRURGICA*****************/
  public function recetaPDFfirmar($id_receta){
    $resultado=array();
    try{
      $path = public_path('tmp'.DIRECTORY_SEPARATOR.'csv');
      $path_zip = public_path('tmp'.DIRECTORY_SEPARATOR.'zip');
    
      if (!is_dir($path)) {
          mkdir($path);
      }
      if (!is_dir($path_zip)) {
          mkdir($path_zip);
          mkdir(public_path('tmp'.DIRECTORY_SEPARATOR.'zip'.DIRECTORY_SEPARATOR.'firmados'));
          mkdir(public_path('tmp'.DIRECTORY_SEPARATOR.'zip'.DIRECTORY_SEPARATOR.'sin_firma'));
      } 
      $csv = "";
      if (Auth::check()):
          $csv .= Auth::user()->id;
      endif;

      $csv .= "_".date("YmdHis");
      $csv.= "_receta_atencion_quirurgica";
      Excel::create($csv, function($excel) use($id_receta){
          $excel->sheet('GENERAL', function($sheet) use($id_receta) {
              $variable = [];
              $this->recetaGenerarPdfParaFirma($id_receta);
              array_push($variable, array(url().DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'sin_firma'.DIRECTORY_SEPARATOR.'receta_atencion_quirurgica_'.$id_receta.'.pdf', url().DIRECTORY_SEPARATOR.'upload_firma'.DIRECTORY_SEPARATOR.'?name=cf_receta_atencion_quirurgica_'.$id_receta.'.pdf&id_tipo=receta_atencion_quirurgica&id_resol='.$id_receta, 'cf_receta_atencion_quirurgica_'.$id_receta.'.pdf','0','45','350','70','Fecha de Firma: <DATE> Firmante: <SIGNER>',url().DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'escudo.jpg','1','7'));                             
              $sheet->fromArray($variable, null, '', false, false);
          });
        })->store('csv',public_path('tmp'.DIRECTORY_SEPARATOR.'csv'));
        $obj = new Archive7z(public_path('tmp'.DIRECTORY_SEPARATOR.'zip'.DIRECTORY_SEPARATOR.'sin_firma'.DIRECTORY_SEPARATOR.$csv.'.7z'));
        $obj->addEntry(public_path(DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'sin_firma'.DIRECTORY_SEPARATOR.'receta_atencion_quirurgica_'.$id_receta.'.pdf')); 
        
      $resultado["csv"]=$csv;
      $resultado["sw"]=true;
      
    }catch(\Exception $e) { 
      Log::error('ERROR EN ATENCION QUIRURGICA  FIRMA RECETA PDF: ' . $e->getMessage(), [
        'exception' => $e,
        'trace' => $e->getTraceAsString(),
      ]);
      $resultado["sw"] = false;
      $resultado["msg"] = "Error al generar PDF para firma: " . $e->getMessage();
    }
    return response()->json($resultado); 
  }
  public function recetaGenerarPdfParaFirma($id_receta){
    $path = public_path('tmp/pdf');
    if (!is_dir($path)) {
      mkdir($path);
    }
    $path = public_path('tmp/pdf/sin_firma/');
    if (!is_dir($path)) {
      mkdir($path);
    }
    $model_receta = new \App\Models\Receta_vale;
    $model_farmacia = new Farmacia();
    $model_receta = new Receta_vale;
    $model_productos_receta = new Producto_receta;
    //--------------------------------------------  
    
    $receta = $model_receta->ShowAllById($id_receta);
    $receta = $receta[0];
    $dni_beneficiario = $receta->dni_beneficiario;
    $establecimiento = $model_farmacia->GetEstablecimientoPNP($receta->id_farmacia);
    $medicamentos = $model_productos_receta->ShowMedicinasById_receta_vale($id_receta);
    
    $productos = $model_receta->ProductosRecetaByIdV3($id_receta);
    $model_receta_diagnostico = new Receta_diagnostico();
    $diagnosticos = $model_receta_diagnostico->DiagnosticosByIdReceta($id_receta);
    
    if ($receta->tipo_receta=='EMERGENCIA'):
        if($establecimiento->id==76):
            $model_emergencia = new \App\Models\Emergencia;
            $obtenerdato = $model_emergencia->GetObtenerServicioReceta($receta->id_prestacion);
            if($obtenerdato->id_prioridad != 4 ):
                $receta->servicio= $obtenerdato->servicio;
                $receta->consultorio= $obtenerdato->consultorio;
            else:
                $receta->servicio= "IV - PATOLOGÍA AGUDA COMÚN";
                $receta->consultorio= "CONSULTORIO DE ATENCIÓN RÁPIDA (CAR)";
            endif;
        endif;
    endif;

    // $carpeta = date("Y")."/".date("m")."/".date("d");
    // $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/".date("Y/m/d")."/cf_receta_atencion_quirurgica_".$id_receta.".pdf";
    // $codigo_qr = QrCode::format('png')->size(150)->generate("https://sigesapol.dirsapolpnp.gob.pe/".date("Y/m/d")."/cf_receta_atencion_quirurgica_".$id_receta.".pdf");
    
    $pdf = \PDF::loadView('frontend.centro_quirurgico.atencion_quirurgica.pdf_receta_vale_firmado', 
    compact('medicamentos','receta','establecimiento','productos','diagnosticos'));
    $pdf->getDomPDF()->set_option("enable_php", true);
    $pdf->setPaper('A4', 'portrait');
    file_put_contents($path."receta_atencion_quirurgica_".$id_receta.".pdf", $pdf->output());
  }
  public function recetaPostArguments(Request $request)
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
      echo  base64_encode($param);
    }
  }
  public function recetaActualizarFirmaConToken(Request $request){
    // $id = intval($request->id_resol_receta);
    // $receta = \App\Models\Receta_vale::where('id', '=', $id)->first();
    // if($receta->fecha_firma == ""){
    //   $receta->fecha_firma = date("d-m-Y H:i:s");
    //   $receta->ruta_receta = date("Y/m/d")."/cf_receta_atencion_quirurgica_".$id.".pdf";
    //   $receta->flag = 1;
    //   $receta->save();
    // }
    // return "ok";
    try {
        $id = intval($request->id_resol_receta);
        $receta = \App\Models\Receta_vale::where('id', '=', $id)->first();
        if ($receta && ($receta->fecha_firma === null || $receta->fecha_firma === '')) {
            $receta->fecha_firma = date("d-m-Y H:i:s");
            $receta->ruta_receta = date("Y/m/d") . "/cf_receta_atencion_quirurgica_" . $id . ".pdf";
            $receta->flag = 1;
            $receta->save();
        }
        return response()->json([
          'status' => 'ok'
        ]);
    } catch (\Throwable $e) {
        Log::error('Error al querer Actualizar Firma Con Token Receta', [
              'message' => $e->getMessage(),
              'code' => $e->getCode(),
              'file' => $e->getFile(),
              'line' => $e->getLine(),
              'trace' => $e->getTraceAsString(),
          ]);
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 400);
    }
  }
  /******************FIN FIRMA RECETA ATENCION QUIRURGICA*****************/
  public function atencion_invalidar_receta_digital(Request $request)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $id_receta = $request->id_receta;
    $id_user = $request->id_user;
    $id_farmacia = $request->id_farmacia;
    $receta = Receta_vale::find($id_receta);
    if ($receta->estado == 1) {
       return response()->json(['success' => false,'msg'=>'La Receta ha sido dispensada ya no se puede anular']);
    } else {      
      $existe_receta_devuelta = DB::table('receta_devueltas')->where('id_receta_vale', $id_receta)->where('id_farmacia', $id_farmacia)->exists();
      if ($existe_receta_devuelta == false) {
        $devolver = new \App\Models\Receta_devuelta();
        $devolver->id_receta_vale = $id_receta;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $devolver->fecha_devolucion = $fecha;
        $devolver->id_farmacia = $id_farmacia;
        $devolver->id_user = $id_user;
        $devolver->motivo = $request->motivo;
        $devolver->save();
      
        $receta->estado = 0; 
        $receta->save();
        return response()->json(['success' => true,'msg'=>'La Receta ha sido anulada']);
      }
    }
  }

  public function genera_hospitalizacion_papeleta_egreso($id_hospitalizacion, $guarda = false){
      $path = public_path('tmp/pdf');
      if (!is_dir($path)) {
        mkdir($path);
      }
      $path = public_path('tmp/pdf/sin_firma/');
      if (!is_dir($path)) {
        mkdir($path);
      }

      $model_hospitalizacion = new Hospitalizacione();
      $hospitalizacion = $model_hospitalizacion->getDatosPapeletaEgreso($id_hospitalizacion);

      $date = Carbon::now();
      $fechaActual = $date->format('d/m/Y');
      $horaActual = $date->format('H:i:s');

      $dias_estancia = 0;
      $fecha_ingreso = Carbon::createFromFormat('d/m/Y', $hospitalizacion->fecha_ingreso);

      /* CALCULAR ESTANCIA EN HOSPITAL EN BASE A ALTA MEDICA*/

      $fecha_alta_administrativa = Carbon::createFromFormat('d/m/Y', $hospitalizacion->fecha_alta_medica);
      $dias_calculados = $fecha_alta_administrativa->diffInDays($fecha_ingreso);
      
      $dias_estancia = $dias_calculados + 1;

      $user_actual = Auth::user();
          
      $asegurado = DB::table('asegurados')
          ->leftJoin('distritos', 'asegurados.id_distrito','=','distritos.id_distrito')
          ->leftJoin('provincias', 'distritos.id_provincia','=','provincias.id_provincia')
          ->leftJoin('departamentos', 'provincias.id_departamento','=','departamentos.id_departamento')
          ->leftJoin('distritos as distrito_nacimiento', 'asegurados.id_distrito_nacimiento','=','distrito_nacimiento.id_distrito')
          ->leftJoin('provincias as provincia_nacimiento', 'distrito_nacimiento.id_provincia','=','provincia_nacimiento.id_provincia')
          ->leftJoin('departamentos as departamento_nacimiento', 'provincia_nacimiento.id_departamento','=','departamento_nacimiento.id_departamento')
          ->leftJoin('distritos as distrito_procedencia', 'asegurados.id_distrito_procedencia','=','distrito_procedencia.id_distrito')
          ->leftJoin('provincias as provincia_procedencia', 'distrito_procedencia.id_provincia','=','provincia_procedencia.id_provincia')
          ->leftJoin('departamentos as departamento_procedencia', 'provincia_procedencia.id_departamento','=','departamento_procedencia.id_departamento')
          ->leftJoin('razas', 'asegurados.id_raza','=','razas.id')
          ->leftJoin('estado_civiles', 'asegurados.id_estado_civil','=','estado_civiles.id')
          ->leftJoin('grado_instrucciones', 'asegurados.id_grado_instruccion','=','grado_instrucciones.id')
          ->leftJoin('ocupaciones', 'asegurados.id_ocupacion','=','ocupaciones.id')
          ->leftJoin('religiones', 'asegurados.id_religion','=','religiones.id')
          ->select('asegurados.*', 'distritos.descripcion as distrito', 'provincias.descripcion as provincia', 'departamentos.descripcion as departamento', 'razas.nombre as etnia',
              'estado_civiles.nombre as estado_civil', 'grado_instrucciones.nombre as grado_instruccion', 'ocupaciones.nombre as ocupacion', 'religiones.nombre as religion',
              'distrito_nacimiento.descripcion as distrito_nacimiento2', 'provincia_nacimiento.descripcion as provincia_nacimiento2', 'departamento_nacimiento.descripcion as departamento_nacimiento2',
              'distrito_procedencia.descripcion as distrito_procedencia2', 'provincia_procedencia.descripcion as provincia_procedencia2', 'departamento_procedencia.descripcion as departamento_procedencia2')
          ->where('asegurados.id',$hospitalizacion->id_asegurado)
          ->where('asegurados.id_estado_reg', 1)
          ->first();

      $titular = null;
      $persona_datos_adicionales = null;
      $grado = null;

      if ($asegurado->id_asegurado_titular != null || $asegurado->id_asegurado_titular > 0) {
          $titular = DB::table('asegurados')
              ->select('asegurados.*')
              ->where('id',$asegurado->id_asegurado_titular)
              ->where('id_estado_reg', 1)
              ->first();
          
          if ($titular != null) {
              $persona_datos_adicionales = DB::table('persona_datos_adicionales')
                  ->select('persona_datos_adicionales.*')
                  ->where('id_asegurado',$titular->id)
                  ->first();
              if ($persona_datos_adicionales != null) {
                  $grado = DB::table('titular_grados')
                  ->select('titular_grados.*')
                  ->where('id',$persona_datos_adicionales->id_grado)
                  ->first();
              }
          }
      }
      $concurrencias = DB::table('hospitalizacion_concurrencias as hc')
              ->join('consultorios as c', 'c.id', '=', 'hc.id_servicio')
              ->select('hc.id', 'hc.id_hospitalizacion', 'hc.id_servicio', 'c.nombre')
              ->where('hc.id_hospitalizacion', $id_hospitalizacion)
              ->get();
      
      $cqx_atencion = DB::table('cqx_atenciones')->select('cqx_atenciones.id_prestacion')->where('cqx_atenciones.id_hospitalizacion', $id_hospitalizacion)->first();  
      $model_prestacion = new \App\Models\Prestaciones; 
      $diagnosticos = $model_prestacion->GetDiagnosticoByIdPrestacionHospitalizacion($cqx_atencion->id_prestacion);

      $pdf = \PDF::loadView('pdf.hospitalizacion_papeleta_egreso',
              [
                  'asegurado' => $asegurado,
                  'concurrencias' => $concurrencias,
                  'dias_estancia' => $dias_estancia,
                  'fechaActual' => $fechaActual,
                  'grado' => $grado,
                  'horaActual' => $horaActual,
                  'hospitalizacion'=> $hospitalizacion,
                  'persona_datos_adicionales' => $persona_datos_adicionales,
                  'titular' => $titular,
                  'user_actual' => $user_actual,
                  'diagnosticos'=>$diagnosticos
              ]
      );

      $pdf->getDomPDF()->set_option("enable_php", true);
      if ($guarda){
          Log::info("genera_hospitalizacion_papeleta_egreso", ["guardar" => true]);
          file_put_contents($path."hospitalizacion-papeleta-egreso_{$id_hospitalizacion}.pdf", $pdf->output());
      } else {
          Log::info("genera_hospitalizacion_papeleta_egreso", ["guardar" => false]);
          return $pdf->stream("papeleta_egreso_$id_hospitalizacion.pdf");
      }
  }
  public function genera_atencion_quirurgico_egreso($id_intervencion, $guarda = false){
    $path = public_path('tmp/pdf');
    if (!is_dir($path)) {
      mkdir($path);
    }
    $path = public_path('tmp/pdf/sin_firma/');
    if (!is_dir($path)) {
      mkdir($path);
    }

    $epicrisis = DB::table('cqx_atenciones as a')
    ->join('cqx_intervenciones as i', 'a.id_cqx_intervencion', '=', 'i.id')
    ->select('a.*', 'i.id')
    ->where('i.id', $id_intervencion)
    ->first();
    
    $model_hospitalizacion = new Hospitalizacione();
    $hospitalizacion = $model_hospitalizacion->getHospitalizacionByIdCqx($epicrisis->id_hospitalizacion);

    $dias_estancia = 0;
    $fecha_ingreso = Carbon::createFromFormat('Y-m-d H:i:s', $hospitalizacion->fecha_ingreso);
    $fecha_alta_medica = Carbon::createFromFormat('Y-m-d H:i:s', $hospitalizacion->fecha_alta_medica);
    $diferencia_dias = $fecha_alta_medica->diffInDays($fecha_ingreso);
    $dia_estancia = $diferencia_dias + 1;
    
    $servicios_concurrencia = null;
    if ($hospitalizacion->id_tipo_egreso == 1) {
        $servicios_concurrencia = $model_hospitalizacion->getServiciosConcurrenciaByIdHospitalizacion($epicrisis->id_hospitalizacion);
    }

    $model_establecimiento = new Establecimiento;
    $establecimiento = $model_establecimiento->GetById(Auth::user()->id_establecimiento);
    $nombre_establecimiento = $establecimiento[0]->nombre;

    $model_prestacion = new \App\Models\Prestaciones;
    $diagnosticos_egreso = $model_prestacion->GetDiagnosticoByIdPrestacion($epicrisis->id_prestacion);

    $pdf = \PDF::loadView('pdf.atencion_quirurgica_epicrisis_firmado',
        [
            'hospitalizacion' => $hospitalizacion,
            'dia_estancia' => $dia_estancia,
            'servicios_concurrencia' => $servicios_concurrencia,
            'nombre_establecimiento' => $nombre_establecimiento,
            'epicrisis' => $epicrisis,
            'diagnosticos_egreso' => $diagnosticos_egreso
        ]
      );

      $pdf->getDomPDF()->set_option("enable_php", true);
      if ($guarda){
          Log::info("atencion_quirurgica_epicrisis_firma", ["guardar" => true]);
          file_put_contents($path."atencion-quirurgico-epicrisis_{$id_intervencion}.pdf", $pdf->output());
      } else {
          Log::info("atencion_quirurgica_epicrisis_firma", ["guardar" => false]);
          return $pdf->stream("epicrisis_egreso_$id_intervencion.pdf");
      }
  }
   public function genera_reporte_operatorio_firmado($id_intervencion_cqx){
      $path = public_path('tmp/pdf');
      if (!is_dir($path)) {
        mkdir($path);
      }
      $path = public_path('tmp/pdf/sin_firma/');
      if (!is_dir($path)) {
        mkdir($path);
      }

      $id_farmacia = Auth::user()->id_farmacia;
      $model_establecimiento = new \App\Models\Establecimiento;
      $model_farma = new Farmacia();
      $farmacia = $model_farma->GetFarmaByIdfarma($id_farmacia);
      $id_ipress = $farmacia->id_establecimiento;
      $establecimiento = $model_establecimiento->GetById($id_ipress);
      

      $model_hospitalizacion = new Hospitalizacione();
      $datos = $model_hospitalizacion->get_reporte_operatorio_cqx($id_intervencion_cqx);
      $establecimiento= !empty($establecimiento[0]->nombre)?$establecimiento[0]->nombre:'';
      $pdf = \PDF::loadView('pdf.hospitalizacion_reporte_operatorio_cq_firmado',compact('datos','establecimiento'));
      $pdf->getDomPDF()->set_option("enable_php", true);
      $pdf->setPaper('A4', 'portrait');
      file_put_contents($path."reporte_operatorio_".$id_intervencion_cqx.".pdf", $pdf->output());
  }

  function limpiarTextarea($valor) {
    $valor = trim($valor); // elimina espacios en blanco innecesarios
    $valor = preg_replace('/\r\n|\r|\n/', "\n", $valor); // normaliza saltos de línea
    $valor = preg_replace('/[ \t]+/', ' ', $valor); // elimina espacios y tabs duplicados
    return $valor;
  }
  public function verificarAtq(Request $request)
  {
    $existe = CqxAtencione::where([
      'id_cqx_intervencion' => $request->idIntervencion,
    ])->exists();

    return response()->json([
      'existe' => $existe
    ]);
  }
}
