<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use SoapClient;
use Session;
use DB;
use App;
use App\Http\Requests\EvaluacionEmergenciaFormRequest;
use Illuminate\Support\Facades\Route;
use Auth;
use Carbon\Carbon;
use App\Models\Farmacia;
use App\Models\Receta_vale;
use App\Models\Cita;
use App\Models\Prestaciones;
use App\Models\User;
use App\Models\Consultorio;
use App\Models\Beneficiario;
use App\Models\Emergencia;
use App\Models\Forma_ingreso;
use App\Models\Estado_ingreso;
use App\Models\Distrito;
use App\Models\Establecimiento;
use App\Models\Tipo_parentescos;
use App\Models\Kardexe;
use App\Models\Categoria;
use App\Models\Violencia;
use App\Models\Comisaria;
use App\Models\Situacion;
use App\Models\Unidadpago;
use App\Models\Diagnostico;
use App\Models\Tipo_beneficiario;
use App\Models\Triaje;
use App\Models\TipoViolencia;
use App\Models\Medico_turno;
use App\Models\Medico;
use App\Models\Producto;
use App\Models\Evolucion;
use App\Models\SubConsultorio;
use App\Models\Asegurado;
use App\Models\Asegurado_historia;
use App\Models\Evaluacion;
use App\Models\Referencia;
use App\Models\Producto_receta;
use App\Models\TipoAtencione;
use App\Models\Hospitalizacione;
use App\Models\Medico_consultorio;
use App\Models\Evaluacione;
use App\Models\Gestacion;
use App\Models\Laboratorio;
use App\Models\Prestacion_eventos;
use App\Models\Puerperio;
use App\Models\Radiologia;
use Illuminate\Support\Facades\Log;
use stdClass;

class EvaluacionController extends Controller
{ 
  function __construct()
  {
      $this->middleware('auth'); 
  }
  
  public function all_pacientes_evaluaciones_by_emergencia($id_user = 0)
    {
      $model_medico = new \App\Models\Medico();
      $model_usuario = new User;
      if (!Auth::check()) :
        return redirect('/auth/login');
      else:
        if(Auth::user()->actualizo_passw==0):
          return redirect('auth/password/change');
        endif;
      endif;

      $ruta = Route::getCurrentRoute()->getPath();
      if (count($ruta = explode("/", $ruta)) > 1);
      $ruta = $ruta[0];
      if ($ruta == "listar_triaje_emergencia_ajax");
      $ruta = "all_pacientes_evaluaciones_by_emergencia";
      $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
      $permiso = $menu[0];
      if ($permiso->lectura == "")
        return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
      if (Auth::user()->tipo_registro == 1) {
      $id_user = Auth::user()->id;
      $usuario_actual = $model_usuario->GetById($id_user);
      $flagemergenciareferencia = $usuario_actual->flaghistoria;


      $date = Carbon::now();
      $fechaHasta = $date->format('d-m-Y');
      $fechaDesdex = $date->format('d-m-Y');
      $fechaDesdey = strtotime('-30 day', strtotime($fechaDesdex));
      $fechaDesde = date('d-m-Y', $fechaDesdey);

      //====================
      $model_farma = new Farmacia();
      $farmacia = $model_farma->GetFarmaByUser($id_user);
      $id_farmacia = $farmacia->id;
      $modo_validacion = $farmacia->modo_validacion;

      $dni_beneficiario = '';
      $nombre_beneficiario = '';
      $estado = 3;
      $opcion = 'evaluacion';

      $model_emergencias = new Emergencia;
      $opcionesprioridad = $model_emergencias->listarPrioridades(); 
    
      
      return view('frontend.admision.emergencia.all_pacientes_evaluaciones_by_emergencia', compact('opcionesprioridad','farmacia', 'fechaDesde', 'fechaHasta', 'dni_beneficiario', 'nombre_beneficiario', 'estado','opcion','permiso'));
    }
      else{
        return redirect('/dashboard')->withInput()->withErrors(array('No tiene el perfil de médico..!'));
      }
    }

  public function formulario_evaluacion_emergencia($id_emergencia)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $modelfarma = new \App\Models\Farmacia;
    $model_receta = new \App\Models\Receta_vale;
    $model_producto = new \App\Models\Producto;
    $model_productos_receta = new \App\Models\Producto_receta;
    $model_establecimiento = new \App\Models\Establecimiento;
    $model_prestacion = new \App\Models\Prestaciones;
    $model_laboratorio = new \App\Models\Laboratorio;
    $model_radiologia = new \App\Models\Radiologia;
    $model_interconsulta = new \App\Models\Interconsulta;
    $model_referencia = new \App\Models\Referencia;
    $model_emergencia = new  \App\Models\Emergencia;
    $model_triaje = new  \App\Models\Triaje;
    $model_upss = new \App\Models\Upss;
    $model_kardex = new Kardexe();
    $model_medicos = new Medico;
    $model_producto = new Producto;
    $model_tipo_parentesco = new Tipo_parentescos;
    $modelcama= new \App\Models\Cama();
    $medico = $model_medicos->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni,Auth::user()->id_establecimiento );
    
    //verifico si existe el id en emergencia
    $verificar_existe = Emergencia::where('id', $id_emergencia)->where('estado', 3)->first();
    if(is_object($verificar_existe)):

      $buscar_ultima_atencion = Prestaciones::where('id_ext', $id_emergencia)->where('id_tipo_atencion', 2)->where('id_estado_reg', 1)->orderBy('id', 'DESC')->first();
      //verifico si existe prestacion
      if(is_object($buscar_ultima_atencion)):
        $id_prestacion =  $buscar_ultima_atencion->id;
        
      
        $farmacia = $modelfarma->GetFarmaByUser(Auth::user()->id);
        $triaje = $model_emergencia->GetCargarEmergencia($id_emergencia, 3);
        
        $fechaServidor = $model_kardex->getFechaServidor();
        $horaServidor = $model_kardex->getHoraServidor();
        $date = Carbon::now();
        $fecha_actual = $date->format('d-m-Y');
        $hora = $date->format('H:i');
        
        $id_ipress = $farmacia->id_establecimiento;
          $establecimiento = $model_establecimiento->GetById($id_ipress);
          if ($id_ipress == 1) {
            $nivel = 2;
            $id_farmacia = Auth::user()->id_farmacia;
          } else {
            if($id_ipress==76){
              $id_farmacia = 19;
            }
            else{
              $id_farmacia = Auth::user()->id_farmacia;
            }
            $nivel = $establecimiento[0]->nivel;
          }
        
        $farmacias = $modelfarma->GetFarmaciaById($id_farmacia);
        $establecimientos = $model_establecimiento->ShowAllEstablecimientos();
        $estado=3;
        $id_tipo_atencion=2;
        $datos_evaluacion = $model_emergencia->GetBuscaEmergenciaEvaluacion($id_emergencia, $medico->id, 3);
        $tipo_parentesco_obj = $model_tipo_parentesco->getTipo_parentescos_by_id($datos_evaluacion->id_tipo_parentesco); 
        $tipo_parentesco=$tipo_parentesco_obj[0]->descripcion;
        //ultimo sited
        $codigo_siteds = $model_emergencia->getSitedsValidForEvaluacion($id_emergencia);
        
        //ultima atencion evaluacion
        //$ultima_atencion_evaluacion = DB::table('evaluaciones')
        //      ->where('id_emergencia', $id_emergencia)
        //      ->where('id_prestacion', $id_prestacion)
        //      ->orderby('id', 'desc')
        //      ->first();
        
        //if(count($ultima_atencion_evaluacion)>0):
        //  $buscar_ultima_atencion = DB::table('evaluaciones')
        //      ->where('id_emergencia', $id_emergencia)
        //      ->orderby('id', 'desc')
        //      ->first();
        //endif;
        $id_principal = $id_emergencia;
        $model_consultorio = new Consultorio;
        $establecimiento_servicios = $model_consultorio->GetServiciosPorEstablecimiento($id_ipress);
        $producto = $model_producto->getProductosByNivelAndIdServicio($nivel, $id_ipress, $id_farmacia);
        $opcionesprioridad = $model_emergencia->listarPrioridades();
        $topicosprioridad = $model_triaje->ShowTriaje($datos_evaluacion->ubica_prioridad); 
        
        $flag = 0 ;
        $id_tipo_atencione=2;
        
        $codigo_triaje = $model_triaje->getConsultorioPorIdTriaje_TODO($datos_evaluacion->id_triaje);
                if ($codigo_triaje[0]->prioridad !== 4) {
                  if ($codigo_triaje[0]->id_ups == '' && $datos_evaluacion->id_triaje==12) {
                    $codigo_upss = '230600';
                    $descripcion_upss ='UNIDAD DE VIGILANCIA INTENSIVA (UVI)';
                  }else{
                    $r_upss = $model_upss->upss_by_id($codigo_triaje[0]->id_ups,2);
                    $codigo_upss = $r_upss->codigo == '' ? 'S/N' : $r_upss->codigo;
                    $descripcion_upss = $r_upss->descripcion_upss == '' ? '-' : $r_upss->descripcion_upss;
                  }
                } else {
                  $r_upss = '230200';
                  $codigo_upss = $r_upss;
                }

        $model_cama = new \App\Models\Cama;
        // $model_emergencia = new \App\Models\Emergencia;
        $obtenerdato = $model_emergencia->GetObtenerServicio($id_emergencia);
        
        $dato_cama = $model_emergencia->GetObtenerCama($obtenerdato->cama);
        $totalcamas = $model_cama->GetCamaActualyVacias($obtenerdato->cama,'EMERGENCIA',$id_ipress);
        
        $id_cama_actual = $obtenerdato->cama;

        /***************************************************** */
        $idprestacionEnvio = $id_prestacion;

        $arraydiagnosticos =[];
      
        $model_procedimiento_metodos = new \App\Models\Procedimiento;
      
        $listaDiagnosticos = DB::table('receta_diagnosticos')
                  ->select('id_diagnostico')
                  ->where('id_prestacion', '=', $idprestacionEnvio)
                  ->where('id_interconsulta', '=',0)
                  ->where('estado', '=',1)
                  ->get();
          
      
          if(count($listaDiagnosticos)>0){
            for($i=0;$i<count($listaDiagnosticos);$i++){
              $idactual = $listaDiagnosticos[$i]->id_diagnostico;
              $diagnostico = DB::table('diagnosticos')
              ->select('id','codigo','nombre')
              ->where('id', $idactual)
              ->first();
              $arraydiagnosticos[]=$diagnostico;
            } 
          }
          $model_prestaciones = new Prestaciones();
          $arraydeprocedimientos = $model_prestaciones->GetDiagnosticoByIdPrestacion($idprestacionEnvio);

          $flag_no_encontrado = 0;
          $model_asegurado= new Asegurado();
          if($datos_evaluacion->id_asegurado_titular!=null || $datos_evaluacion->id_asegurado_titular>0):
            $dato_titular = $model_asegurado->Busca_datos_titular($datos_evaluacion->id_asegurado_titular);
          else:
            $flag_no_encontrado = 1;
          endif;

          //dd($arraydeprocedimientos);
        /******************************************************** */
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

          if($verificar_existe->id_referencia != 0){
            $model_referencia = new \App\Models\Referencia;
            $referencias = $model_referencia->GetBuscaReferenciaById($verificar_existe->id_referencia);
            $model_prestacionreferncia = new \App\Models\Prestaciones;
            $diagnostico_prestacion_referencia = $model_prestacionreferncia->GetDiagnosticoByIdPrestacion($referencias->id_prestacion);
            $medico_referencia = DB::table('medicos')
            ->select('medicos.*')
            ->where('id', $referencias->id_medico_referencia)
            ->first();
            $nombre_medico_referencia = $medico_referencia->paterno. ' '. $medico_referencia->materno. ','.$medico_referencia->nombre;
            $procedimientos_refencia = $model_referencia->buscar_procedimiento_referencia($verificar_existe->id_referencia);
            $servicios_ipress_origen = $model_referencia->busca_servicio_por_ipres_origen_referencia($referencias->id_establecimiento);
          
          }else{
            $diagnostico_prestacion_referencia = "";
            $referencias = "";
            $procedimientos_refencia="";
            $servicios_ipress_origen="";
            $nombre_medico_referencia="";
          }

             // Verificar si el usuario es medico de profesion
      
        $is_profesion_medico = false;
        if (count($medico) > 0) {
          switch ($medico->id_profesion) {
            case 20: case 4: case 51: case 52: case 1: $is_profesion_medico = true; break;
          }
          if (!$is_profesion_medico && (intval($nivel) == 1 || intval($nivel) == 2) && in_array(intval($medico->id_profesion), [12, 2, 5])) {
            $is_profesion_medico = true;
          }
        }

        $eventosMarcados = Prestacion_eventos::where('id_prestacion', $id_prestacion)
        ->where('estado', 1)
        ->lists('id_evento')
        ->toArray();
        $gestacion = Gestacion::where("id", $verificar_existe->id_gestacion)->first();
        $puerperio = Puerperio::where("id", $verificar_existe->id_puerperio)->first();
        $cita = $model_emergencia->GetBuscaEmergencia($id_emergencia, $medico->id, $verificar_existe->estado);
        $tipoPartosSql = "SELECT * FROM tipo_partos";
        $tipoPartos = DB::select($tipoPartosSql);
        $tipoDocsNatiSql = "SELECT * FROM tipo_doc_nati";
        $tipoDocsNati = DB::select($tipoDocsNatiSql);

        $saludpolMock = filter_var(env('WI_SALUDPOL_MOCK', false), FILTER_VALIDATE_BOOLEAN);

        if ($saludpolMock || $cita->nro_doc_ident == '70702158') {
            // Sin acceso al WS (entorno local): se da por validado
            $flag = 0;
            $errorWS = 1;
        }
        else
        {
            if($cita->tipo_doc_ident=='DNI'){
              $tipo_doc_ident = '1';
            }else if($cita->tipo_doc_ident=='CE'){
              $tipo_doc_ident = '2';
            }else if($cita->tipo_doc_ident=='PAS'){
              $tipo_doc_ident = '3';
            }else if($cita->tipo_doc_ident=='DIE'){
              $tipo_doc_ident = '4';
            }

            $client = new SoapClient(
                env('WI_SALUDPOL_URL') . "/ws/WS_AfiliadoSP_Serv.php?wsdl",
                array('login' => env('WI_SALUDPOL_USERNAME'), 'password' => env('WI_SALUDPOL_PASSWORD'))
            );

            $beneficiario = $client->getAseguradoValidate($tipo_doc_ident, $cita->nro_doc_ident);

            if (is_object($beneficiario)) :
              $flag = 0 ;
              $errorWS = 1 ;
            else:
              $flag = 1 ;
              $errorWS = 0 ;
            endif;
        }

        // Datos del Alta medica
        $datos_emergencia = $model_emergencia->GetEmergenciaByIdAseguradoEmergencia($verificar_existe->id_asegurado, $id_emergencia);
        $dia_estancia = $datos_emergencia->dia_estancia;

        $consulta_externa = $model_consultorio->ShowConsultorioByIdIpressAndIdTipoAtencion($farmacia->id_establecimiento, 1);
        $establecimiento_model = new Establecimiento();
        $establecimientos_traslado = $establecimiento_model->ShowAll();
        
        /*$upss="SELECT 
          concat(u.descripcion_upss,' / ',u.codigo ) as nombre,
          u.id as id_upss,
          u.codigo as codigo_upss,
          c.id as id_consultorio,
          c.nombre as nombre_consultorio
          from consultorios c 
          inner join upsses u on u.id_consultorio = c.id 
          where u.id_tipo_atencion = '3' and u.estado = '1' and u.estado = '1' and u.descripcion_upss like 'H%' order by nombre asc";
        $upss= DB::select($upss);*/

        $pabellones = $modelcama->GetAllPabellones(Auth::user()->id_establecimiento, 'HOSPITALIZACION');

        $model_cqx_tipo_profesional = new \App\Models\CqxTipoProfesional();
        $cqx_tipo_profesionales = $model_cqx_tipo_profesional->GetAllActivos();
        $model_tipo_cirugia = new \App\Models\CqxTipoAnestesia();
        $tipo_cirugias = $model_tipo_cirugia->GetAllTipoCirugiasActivos();
        // Fin Datos del Alta medica
                  
        if ($errorWS > 0) {
          return view('frontend.evaluaciones.form_evaluacion', compact( 'arraydeprocedimientos','establecimiento','id_prestacion','eventosMarcados','tipoPartos','tipoDocsNati','cita','gestacion','puerperio','verificar_existe','diagnostico_prestacion_referencia','referencias','procedimientos_refencia','servicios_ipress_origen','nombre_medico_referencia', 'fechaServidor', 'horaServidor', 'nivel', 'farmacias','establecimientos','triaje','producto','id_principal','estado','datos_evaluacion','id_tipo_atencion','establecimiento_servicios','id_farmacia','opcionesprioridad','topicosprioridad','totalcamas','flag','id_tipo_atencione','codigo_upss','id_emergencia','dato_cama','id_cama_actual','hora','dato_titular','turno','buscar_ultima_atencion','flag_no_encontrado','codigo_siteds','is_profesion_medico','consulta_externa','establecimientos_traslado', 'dia_estancia', /*'upss',*/'tipo_parentesco', 'pabellones', 'cqx_tipo_profesionales', 'tipo_cirugias', 'id_ipress'));
        } else {
          return redirect('/all_pacientes_evaluaciones_by_emergencia')->withInput()->withErrors(array('El paciente ya no esta asegurado, por consiguiente no tiene derecho de atención, realizar los tramites correspondiente con SALUDPOL..!'));
        }
        
      else:
        return redirect('/formulario_atencion_emergencia/'.$id_emergencia)->withInput()->withErrors(array('Primero debe ingresar una atencion, para poder registrar su evaluacion del paciente  ..!'));
      endif;
    else:
      return redirect('/all_pacientes_evaluaciones_by_emergencia/')->withInput()->withErrors(array('Elemento no encontrado ..!'));
    endif; 
  }
  public function modal_historial_evaluaciones($id_emergencia, $estado, $id_tipo_atencion)
  {   $model_evaluaciones = new \App\Models\Evaluacione;
      
      if($id_tipo_atencion==2):
        $evaluaciones = $model_evaluaciones->GetEvaluacionesByPacienteEmergencia($id_emergencia, $estado);
      else:
        $evaluaciones = $model_evaluaciones->GetEvolucionesByPacienteHospitalizacion($id_emergencia, $estado);
      endif;

      return view('frontend.evaluaciones.modal_historial_evaluaciones', compact('evaluaciones','id_tipo_atencion'));
  }

  public function modal_historial_evaluaciones_atenciones($id_emergencia, $id_tipo_atencion)
  {
    //$medico_prestaciones = new \App\Models\Prestaciones;
    //$atenciones = $medico_prestaciones->GetAtencionesByDni($dni_beneficiario);
    $model_evaluaciones = new \App\Models\Evaluacione;
    if ($id_tipo_atencion == 3) {
      $atenciones = $model_evaluaciones->GetAtencionesByEvaluacionesHospi($id_emergencia);
    } elseif ($id_tipo_atencion == 2) {
      $atenciones = $model_evaluaciones->GetAtencionesByIdExt($id_emergencia, $id_tipo_atencion);
    }
    //dd($atenciones);
    return view('frontend.evaluaciones.modal_historial_atenciones', compact('atenciones', 'id_tipo_atencion'));
  }

  public function mostrar_evaluacion($id_evaluacion, $id_tipo_atencion)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $model_evaluacion = new Evaluacione();
    $evaluacion = $model_evaluacion->GetEvaluacion($id_evaluacion);
    $flag_bloqueo_atencion = $evaluacion ? DB::table('asegurados')->where('id', $evaluacion->id_asegurado)->value('flag_bloqueo_atencion') : null;
    if ($flag_bloqueo_atencion !== null && intval($flag_bloqueo_atencion) === 1) :
      return response()->json(['message' => 'No puede ver la atención de este paciente.'], 403);
    endif;

    $model_establecimiento = new \App\Models\Establecimiento;
    $model_kardex = new Kardexe();
    $model_medico = new Medico();

    $fechaServidor = $model_kardex->getFechaServidor();
    $date = Carbon::now();
    $fecha_actual = $date->format('d-m-Y');
    
    $medico = $model_medico->MostrarMedico($evaluacion->id_medico);
    
    $establecimiento = $model_establecimiento->GetById(Auth::user()->id_establecimiento);

    if ($id_tipo_atencion == 2)
      $verificar = Emergencia::where('id', $evaluacion->id_emergencia)->where('estado', 3)->first();
    else
      $verificar = Hospitalizacione::where('id', $evaluacion->id_emergencia)->where('estado', 3)->first();

      if(is_object($verificar)):
        if ($id_tipo_atencion == 2) {
          $model_emergencia = new Emergencia;
          $paciente = $model_emergencia->GetBuscaEmergencia( $evaluacion->id_emergencia,$evaluacion->id_medico, 3);
        } else {
          $paciente = $verificar;
        }
    endif;

    /*********************************************/
    $model_emergencia = new \App\Models\Emergencia;
    $modelfarma = new \App\Models\Farmacia;
    $model_prestacion = new \App\Models\Prestaciones;
    $model_laboratorio = new \App\Models\Laboratorio;
    $model_radiologia = new \App\Models\Radiologia;
    $model_interconsulta = new \App\Models\Interconsulta;
    $model_receta = new Receta_vale;
    
    $obtenerdato = null;
    if ($id_tipo_atencion == 2) {
          $obtenerdato = $model_evaluacion->GetObtenerServicioEvaluacion($evaluacion->id);

          $evaluacion->servicio= $obtenerdato->servicio;
          $evaluacion->consultorio= $obtenerdato->consultorio;
    } elseif ($id_tipo_atencion == 3) {
      $obtenerdato = $model_evaluacion->GetObtenerServicioEvaluacion($evaluacion->id, $id_tipo_atencion);
      $evaluacion->servicio = $obtenerdato->servicio_sala;
      $evaluacion->consultorio = $obtenerdato->cuarto;
    }
          $id_ipress = Auth::user()->id_establecimiento;
          $farmacias = $modelfarma->GetFarmaciaByIdEstablecimiento($id_ipress);
          $establecimientos = $model_establecimiento->ShowAllEstablecimientos();
          $diagnostico_prestaciones = $model_evaluacion->GetDiagnosticoByIdEvaluacion($id_evaluacion);
          $procedimiento_evaluacion = $model_evaluacion->GetProcedimientosByIdEvaluacion($id_evaluacion, 1);
          $datoadicional = $model_emergencia->GetObtenerCama($obtenerdato->cama);
          $laboratorio = $model_laboratorio->GetLaboratorioByIdPrestacionEvaluacion($evaluacion->id_prestacion,$id_evaluacion);
          $radiologia = $model_radiologia->GetRadiologiaByIdPrestacionEvaluacion($evaluacion->id_prestacion,$id_evaluacion);
          //$interconsulta= $model_interconsulta->GetInterconsultaByIdPrest($evaluacion->id_prestacion);
          
          $interconsultas = $model_interconsulta->GetInterconsultaByIdEvaluacion2($evaluacion->id_prestacion,$id_evaluacion);
          //endif;
          $receta = $model_receta->GetDatosRecetasByIdEvaluacion($id_evaluacion);

    return view('frontend.evaluaciones.mostrar_evaluacion', compact('farmacias','receta','laboratorio','radiologia','interconsultas','diagnostico_prestaciones','procedimiento_evaluacion','evaluacion', 'paciente','fechaServidor', 'nivel','medico','datoadicional'));
  }

  public function mostrar_evaluacion_emergencia($id_evaluacion, $id_tipo_atencion)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $model_evaluacion = new Evaluacione();
    $evaluacion = $model_evaluacion->GetEvaluacion($id_evaluacion);
    $flag_bloqueo_atencion = $evaluacion ? DB::table('asegurados')->where('id', $evaluacion->id_asegurado)->value('flag_bloqueo_atencion') : null;
    if ($flag_bloqueo_atencion !== null && intval($flag_bloqueo_atencion) === 1) :
      return response()->json(['message' => 'No puede ver la atención de este paciente.'], 403);
    endif;

    $model_establecimiento = new \App\Models\Establecimiento;
    $model_kardex = new Kardexe();
    $model_medico = new Medico();
    $fechaServidor = $model_kardex->getFechaServidor();
    $date = Carbon::now();
    $fecha_actual = $date->format('d-m-Y');
    
    $medico = $model_medico->MostrarMedico($evaluacion->id_medico);
    
    $establecimiento = $model_establecimiento->GetById(Auth::user()->id_establecimiento);

    if ($id_tipo_atencion == 2)
      $verificar = Emergencia::where('id', $evaluacion->id_emergencia)->where('estado', 3)->first();
    else
      $verificar = Hospitalizacione::where('id', $evaluacion->id_emergencia)->where('estado', 3)->first();

      if(is_object($verificar)):
        if ($id_tipo_atencion == 2) {
          $model_emergencia = new Emergencia;
          $paciente = $model_emergencia->GetBuscaEmergencia( $evaluacion->id_emergencia,$evaluacion->id_medico, 3);
        } else {
          $paciente = $verificar;
        }
    endif;

    if ($id_tipo_atencion == 2) {
        $model_emergencia = new Emergencia;
        $paciente_his = $model_emergencia->GetBuscaEmergencia2( $evaluacion->id_emergencia,$evaluacion->id_medico);
    }
    
    /*********************************************/
    $model_emergencia = new \App\Models\Emergencia;
    $modelfarma = new \App\Models\Farmacia;
    $model_prestacion = new \App\Models\Prestaciones;
    $model_laboratorio = new \App\Models\Laboratorio;
    $model_radiologia = new \App\Models\Radiologia;
    $model_interconsulta = new \App\Models\Interconsulta;
    $model_receta = new Receta_vale;
    
    $obtenerdato = null;
    if ($id_tipo_atencion == 2) {
          $obtenerdato = $model_evaluacion->GetObtenerServicioEvaluacion($evaluacion->id);

          $evaluacion->servicio= $obtenerdato->servicio;
          $evaluacion->consultorio= $obtenerdato->consultorio;
    } elseif ($id_tipo_atencion == 3) {
      $obtenerdato = $model_evaluacion->GetObtenerServicioEvaluacion($evaluacion->id, $id_tipo_atencion);
      $evaluacion->servicio = $obtenerdato->servicio_sala;
      $evaluacion->consultorio = $obtenerdato->cuarto;
    }
          $id_ipress = Auth::user()->id_establecimiento;
          $farmacias = $modelfarma->GetFarmaciaByIdEstablecimiento($id_ipress);
          $establecimientos = $model_establecimiento->ShowAllEstablecimientos();
          $diagnostico_prestaciones = $model_evaluacion->GetDiagnosticoByIdEvaluacion($id_evaluacion);
          $procedimiento_evaluacion = $model_evaluacion->GetProcedimientosByIdEvaluacion($id_evaluacion, 1);
          $datoadicional = $model_emergencia->GetObtenerCama($obtenerdato->cama);
          $laboratorio = $model_laboratorio->GetLaboratorioByIdPrestacionEvaluacion($evaluacion->id_prestacion,$id_evaluacion);
          $radiologia = $model_radiologia->GetRadiologiaByIdPrestacionEvaluacion($evaluacion->id_prestacion,$id_evaluacion);
          //$interconsulta= $model_interconsulta->GetInterconsultaByIdPrest($evaluacion->id_prestacion);
          
          $interconsultas = $model_interconsulta->GetInterconsultaByIdEvaluacion2($evaluacion->id_prestacion,$id_evaluacion);
          //endif;
          $receta = $model_receta->GetDatosRecetasByIdEvaluacion($id_evaluacion);

    return view('frontend.evaluaciones.mostrar_evaluacion_emergencia', compact('farmacias','receta','laboratorio','radiologia','interconsultas','diagnostico_prestaciones','procedimiento_evaluacion','evaluacion', 'paciente','paciente_his','fechaServidor', 'nivel','medico','datoadicional'));
  }

  public function ver_evaluacion($id_user,$id_evaluacion)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $modelfarma = new Farmacia;
    $model_receta = new Receta_vale;
    $model_producto = new Producto;
    $model_productos_receta = new Producto_receta;
    $model_establecimiento = new \App\Models\Establecimiento;
    $model_prestacion = new \App\Models\Prestaciones;
    $model_laboratorio = new \App\Models\Laboratorio;
    $model_radiologia = new \App\Models\Radiologia;
    $model_interconsulta = new \App\Models\Interconsulta;
    $model_referencia = new \App\Models\Referencia;
    $model_evaluacion = new \App\Models\Evaluacione;
    $model_tipo_parentesco = new \App\Models\Tipo_parentescos;
    $model_kardex = new Kardexe();

    $farmacia = $modelfarma->GetFarmaByUser($id_user);
    $id_farmacia = $farmacia->id;
    $flag_receta_dias = $farmacia->flag_receta_dias;

    $fechaServidor = $model_kardex->getFechaServidor();
    $date = Carbon::now();
    $fecha_actual = $date->format('d-m-Y');

    // Deberia ser "GetEvaluacionByIdEvaluacion()":
    $evaluacion = $model_evaluacion->GetEvaluacionByIdEmergencia($id_evaluacion);
    
    $model_emergencias = new Emergencia;
    $paciente = $model_emergencias->GetBuscaPacienteEmergenciaEvaluacionX( $evaluacion->id_emergencia);
    $verificar_existe = Emergencia::where('id', $evaluacion->id_emergencia)->where('estado', '>',2)->first();
    
    
      //verifico si existe idemergencia
      if(count($evaluacion)>0):

          $id_prestacion =  $evaluacion->id_prestacion;

          $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
          $emergencia = Emergencia::find($prestacion->id_ext);
          $id_principal = $emergencia->id;
          $gestacion = null;
          $puerperio = null;
          if (isset($emergencia -> id_gestacion)) {
            $gestacion = Gestacion::find($emergencia -> id_gestacion);
            error_log('HAY GESTACION EN EVALUACION: '.(string)$gestacion);
          }
          if (isset($emergencia -> id_puerperio)) {
            $puerperio = Puerperio::find($emergencia -> id_puerperio);
            error_log('HAY PUERPERIO EN EVALUACION: '.(string)$puerperio);
            $natis = $puerperio->natis;
            error_log('sus natis: '.(string)$natis);
          }
          $tipo_parentesco_obj = $model_tipo_parentesco->getTipo_parentescos_by_id($emergencia->id_tipo_parentesco); 
          $tipo_parentesco=$tipo_parentesco_obj[0]->descripcion;
          $receta = $model_receta->GetDatosRecetasByIdEvaluacion($id_evaluacion);
          $diagnostico_atencion = $model_prestacion->GetDiagnosticoByIdPrestacionEmergencia($id_prestacion);
          $procedimiento_atencion = $model_prestacion->GetProcedimientosByIdPrestacionEmergencia($id_prestacion,1);
          $receta_alta = $model_receta->GetDatosRecetasByIdEvaluacionAlta($id_evaluacion);
           
          $laboratorio = $model_laboratorio->GetLaboratorioByIdPrestacionEvaluacion($id_prestacion,$id_evaluacion);
          if (count($laboratorio) > 0) {
            $flag_laboratorio = "1";
            $flag_firmar_laboratorio = $laboratorio[0]->fecha_firma;
          } else {
            $flag_laboratorio = "0";
            $flag_firmar_laboratorio = "";
          }
          $radiologia = $model_radiologia->GetRadiologiaByIdPrestacionEvaluacion($id_prestacion,$id_evaluacion);
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
            $id_farmacia = Auth::user()->id_farmacia;
          } else {
            if($id_ipress==76){
              $id_farmacia = 19;
            }
            else{
              $id_farmacia = Auth::user()->id_farmacia;
            }
            $nivel = $establecimiento[0]->nivel;
          }


          $model_emergencia = new \App\Models\Emergencia;
          $obtenerdato = $model_emergencia->GetObtenerServicio($prestacion->id_ext);

          $prestacion->servicio= $obtenerdato->servicio;
          $prestacion->consultorio= $obtenerdato->consultorio;

          $producto = $model_producto->getProductosByNivelAndIdServicio($nivel, $establecimiento[0]->id, $id_farmacia); //76 -> servicio de hopitalizacion 
          
          //$farmacias = $modelfarma->GetFarmaciaByIdEstablecimiento($id_ipress);
          $farmacias = $modelfarma->GetFarmaciaById($id_farmacia);
          $establecimientos = $model_establecimiento->ShowAllEstablecimientos();
          $diagnostico_evaluacion = $model_evaluacion->GetDiagnosticoByIdEvaluacion($id_evaluacion);
          $diagnostico_egreso = $model_prestacion->GetDiagnosticoByIdEgresoAlta($id_prestacion);
          //$procedimiento = $model_evaluacion->GetProcedimientosByIdEvaluacion($id_evaluacion, 1);
          $procedimiento_evaluacion = $model_prestacion->GetProcedimientosByIdPrestacionydiagEvaluacion($id_prestacion, 1, $id_evaluacion);
          
          $datoadicional = $model_emergencia->GetObtenerCama($obtenerdato->cama);
//          $interconsultas = $model_interconsulta->GetInterconsultaByIdEvaluacion($id_prestacion,$id_evaluacion);
          $interconsultas = $model_interconsulta->GetInterconsultaByIdEvaluacion2($id_prestacion,$id_evaluacion);
          
          $referencia = $model_referencia->GetReferenciaByIdPrest($id_prestacion);

          $ruta = "all_pacientes_evaluaciones_by_emergencia";
          $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
          $permiso = $menu[0];

          $flag_no_encontrado = 0;
          $model_asegurado= new Asegurado();
          if($prestacion->id_asegurado_titular!=null || $prestacion->id_asegurado_titular>0):
            $dato_titular = $model_asegurado->Busca_datos_titular($prestacion->id_asegurado_titular);
          else:
            $flag_no_encontrado = 1;
          endif;

          $date = Carbon::now();
          $hora=$date->toTimeString(); 

          if($hora>'00:00' & $hora<'07:31'):
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
                //else:
                  //if($hora>='00:00' & $hora<='01:31'):
                    /*echo*/ //$turno='N';
                  //endif;
                endif;
              endif;
            endif;
          endif;

          //============== Ordenes de Imagenes invalidados por Imagenes ============================
          $ordenImagenesAnulada = $model_radiologia->cpmsAnuladosEvaluaciones(2,$id_prestacion,$id_evaluacion);
          //dd($ordenImagenesAnulada);
          $orden = '';
          $ordenes = [];
          if ( count($ordenImagenesAnulada) > 0 ) {
              foreach ($ordenImagenesAnulada as $key) {
              if ( $orden !== $key->id_radiologia ) {
                  $orden = $key->id_radiologia;

                  $obj = new stdClass();
                  $obj->id_radiologia = $key->id_radiologia;
                  $obj->obs_examen_invalidado = $key->obs_examen_invalidado;
                  $obj->fecha_orden_invalidado = $key->fecha_orden_invalidado;
                  $obj->usuario = $key->name . ', ' . $key->paterno; 

                  $ordenes[] = $obj;
              }
              }
              //dd($ordenes);

              foreach ($ordenes as $key1) {
              $key1->cpms = [];
              foreach ($ordenImagenesAnulada as $key2) {
                  //dd($key1->id_radiologia);
                  if ($key1->id_radiologia == $key2->id_radiologia) {

                      $obj = new stdClass();
                      $obj->id_pp = $key2->id;
                      $obj->codigo_rad = $key2->codigo_rad;
                      $obj->descripcion_corta = $key2->descripcion_corta;
                      $obj->diagnostico = $key2->diagnostico;

                      $key1->cpms[] = $obj;
                      }  
                  }  
              }
          }
          //dd($ordenes);
          //========================================================================================
          // Verificar si el usuario es medico de profesion
          $model_medico = new Medico();
          $medico = $model_medico->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni,Auth::user()->id_establecimiento );
          $is_profesion_medico = false;
          if (count($medico) > 0) {
            switch ($medico->id_profesion) {
              case 20: case 4: case 51: case 52: case 1: $is_profesion_medico = true; break;
            }
          }

          $model_consultorio = new Consultorio;
          $establecimiento_servicios = $model_consultorio->GetServiciosPorEstablecimiento($id_ipress);
          
          $eventosMarcados = Prestacion_eventos::where('id_prestacion', $id_prestacion)
          ->where('estado', 1)
          ->lists('id_evento')
          ->toArray();

          $establecimiento_traslado = null;
          if (!empty($verificar_existe->origen_alta) && intval($verificar_existe->origen_alta) == 3):
            
            if ($verificar_existe->id_tipo_egres_med == 3) {
              $establecimiento_model = new Establecimiento();
              $establecimiento_traslado = $establecimiento_model->GetByIdEstablecimiento($emergencia->id_establecimiento_traslado);
            }

            $dato_traslado = null;
            $dato_traslado = $model_emergencia->GetVerUbicacionTraslado2($verificar_existe->id);
          endif;

          // Validar campo de habilitacion de firma electronica
            $is_habilitado_firma_electronica = $establecimiento[0]->flag_firma_electronica;

            $paciente_conducido_por = $emergencia->nombre_conducido;

        $pabellon = null;
        $upss_alta = null;
        $diagnosti= array();

        if($id_ipress==76){

          if (!empty($emergencia->origen_alta) && intval($emergencia->origen_alta) === 3) {
            $pabellon = DB::table('pabellones')
                        ->where('pabellones.id', $emergencia->id_pabellon_ubicacion)
                        ->first();

            $upss_alta = DB::table('upsses')
                        ->where('upsses.id', $emergencia->id_upss_alta)
                        ->first();

            $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$emergencia->id_diag_cab'";
            $diagnos = DB::select($diagnos);
            $diagnos = $diagnos[0];
            $diagnos->cabecera = "PRINCIPAL";
            $diagnos->tipo = "DEFINITIVO";
            $diagnosti[0]=$diagnos;

            if($emergencia->id_diag_cuer1>0){

                $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$emergencia->id_diag_cuer1'";
                $diagnos = DB::select($diagnos);
                $diagnos = $diagnos[0];
                $diagnos->cabecera = "SECUNDARIO";
                $diagnos->tipo = "DEFINITIVO";
                $diagnosti[1]=$diagnos;
            }elseif($emergencia->id_diag_cuer2>0){

                $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$emergencia->id_diag_cuer2'";
                $diagnos = DB::select($diagnos);
                $diagnos = $diagnos[2];
                $diagnos->cabecera = "SECUNDARIO";
                $diagnos->tipo = "DEFINITIVO";
                $diagnosti[2]=$diagnos;
            }
          }

        }
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
          return view('frontend.evaluaciones.ver_evaluacion', compact('diagnostico_egreso','tipo_parentesco','farmacia', 'prestacion', 'verificar_existe', 'eventosMarcados', 'diagnostico_atencion','diagnostico_evaluacion', 'procedimiento_atencion', 'procedimiento_evaluacion', 'medicamentos', 'id_prestacion', 'fechaServidor', 'receta', 'receta_alta', 'producto', 'farmacias', 'nivel', 'laboratorio', 'radiologia', 'flag_pendinte_firma','establecimientos','referencia','evaluacion','paciente','id_evaluacion','procedimiento','estado','datoadicional','interconsultas','permiso','dato_titular','flag_no_encontrado','turno', 'gestacion', 'puerperio', 'natis', 'flag_laboratorio', 'flag_firmar_laboratorio','flag_radiologia','flag_firmar_radiologia','ordenImagenesAnulada','ordenes','is_profesion_medico','establecimiento_servicios','obtenerdato', 'dato_traslado', 'establecimiento','is_habilitado_firma_electronica', 'paciente_conducido_por','id_principal','establecimiento_traslado', 'pabellon', 'upss_alta', 'diagnosti','id_farmacia'));
      else:
         return redirect('/all_pacientes_evaluaciones_by_emergencia/')->withInput()->withErrors(array('Evaluacion no encontrado ..!'));
      endif;
      
  }

  public function registrar_evaluacion_adicional(Request $request)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $search = array('+', ',','"','/', '{', '}');
    $replace = array('\+', '\,', '\"', '\/', '\{', '\}');

    $id_prestacion = $request->id_prestacion;
    $id_evaluacion = $request->id_evaluacion;
      $p_producto = "";
    $p_producto .= "{";
    if (isset($request->id_producto)) :
      if($request->id_producto[0]!="ru='undefined'"):
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
            $p_producto .= $request->id_via_administracion[$key];
            $p_producto .= "},";
          }
        endforeach;
        if (strlen($p_producto) > 1) $p_producto = substr($p_producto, 0, -1);
      endif;    
    endif;
    $p_producto .= "}";

      $model_prestaciones = new Prestaciones();
      $receta = new Receta_Vale();
      $diagnosticos = $model_prestaciones->GetDiagnosticoByIdPrestacionEmergencia($id_prestacion);
      $p_diagnostico = "";
      $p_diagnostico .= "{";
      foreach ($diagnosticos as $row) :
        $p_diagnostico .= "{";
        $p_diagnostico .= $row->id_diagnostico . ",";
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
      
      $id_medico = $prestacion->id_medico;
      $id_tiporeceta = $prestacion->id_tipo_atencion;
      $fecha_atencion = $request->fecha_atencion; 
      $id_consultorio = $subconsultorio->id_consultorio;
      $grado = $cita->grado;
      $tipo_beneficiario = $cita->parentesco;

      $dni_beneficiario = $asegurado->nro_doc_ident;
      $cip_beneficiario = $asegurado->cip;
      $tipodoc_beneficiario = $asegurado->tipo_doc_ident;
      $nombre_beneficiario = $asegurado->nombre;
      $paterno_beneficiario = $asegurado->paterno;
      $materno_beneficiario = $asegurado->materno;

      $dni_titular = $titular->nro_doc_ident;
      $tipdoc_titular = $titular->tipo_doc_ident;
      $nombre_titular = $titular->nombre;
      $paterno_titular = $titular->paterno;
      $materno_titular = $titular->materno;

      $nro_historia = isset($historia->nro_historia) ? $historia->nro_historia : "";
      $hora = date('H');
      
      if ($hora > 6 && $hora < 13) $turno = "M";
      elseif ($hora >= 13 && $hora < 19) $turno = "T";
      elseif ($hora >= 19 && $hora <= 24) $turno = "N";
      else $turno = "MD";
      /*********************************************************************/
      $model_procedimiento = new \App\Models\Procedimiento;
      //Laboratorio
      if (isset($request->id_examen_lab)) :
        $p_laboratorio = "";
        $p_laboratorio .= "{";
        foreach ($request->id_examen_lab as $key => $value) :
          if ($request->id_examen_lab[$key] != "") {
            $p_laboratorio .= "{";
            $p_laboratorio .= $request->id_examen_lab[$key] . ",";
            if (intval($request->id_nivel) === 3):
              $p_laboratorio .= "0";
            else:
            $p_laboratorio .= (isset($request->examen_lab_observacion[$key]) && $request->examen_lab_observacion[$key] !="") ? str_replace($search, $replace, $request->examen_lab_observacion[$key]) : "0";
            endif;
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->examen_lab_cantidad[$key]) && $request->examen_lab_cantidad[$key] > 0) ? $request->examen_lab_cantidad[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->miSelectLab[$key]) && $request->miSelectLab[$key] !="") ? $request->miSelectLab[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->descripcion_tipo_muestra[$key]) && $request->descripcion_tipo_muestra[$key] !="") ? $request->descripcion_tipo_muestra[$key] : "0";
            /** EXAMEN PARA HEMOCULTIVO PARA ADULTO */
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_fecha_tiempo_enfermedad[$key]) && $request->des_adulto_fecha_tiempo_enfermedad[$key] !="") ? $request->des_adulto_fecha_tiempo_enfermedad[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_respuesta_ocular[$key]) && $request->des_adulto_respuesta_ocular[$key] !="") ? $request->des_adulto_respuesta_ocular[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_respuesta_verbal[$key]) && $request->des_adulto_respuesta_verbal[$key] !="") ? $request->des_adulto_respuesta_verbal[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_respuesta_motora[$key]) && $request->des_adulto_respuesta_motora[$key] !="") ? $request->des_adulto_respuesta_motora[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_total_escala_glasgow[$key]) && $request->des_adulto_total_escala_glasgow[$key] !="") ? $request->des_adulto_total_escala_glasgow[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_presion_arterial_x[$key]) && $request->des_adulto_presion_arterial_x[$key] !="") ? $request->des_adulto_presion_arterial_x[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_presion_arterial_y[$key]) && $request->des_adulto_presion_arterial_y[$key] !="") ? $request->des_adulto_presion_arterial_y[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_frecuencia_cardiaca[$key]) && $request->des_adulto_frecuencia_cardiaca[$key] !="") ? $request->des_adulto_frecuencia_cardiaca[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_frecuencia_respiratorio[$key]) && $request->des_adulto_frecuencia_respiratorio[$key] !="") ? $request->des_adulto_frecuencia_respiratorio[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_temperatura[$key]) && $request->des_adulto_temperatura[$key] !="") ? $request->des_adulto_temperatura[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_creatinina[$key]) && $request->des_adulto_creatinina[$key] !="") ? $request->des_adulto_creatinina[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_diuresis[$key]) && $request->des_adulto_diuresis[$key] !="") ? $request->des_adulto_diuresis[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_procalcitonina[$key]) && $request->des_adulto_procalcitonina[$key] !="") ? $request->des_adulto_procalcitonina[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_pcr[$key]) && $request->des_adulto_pcr[$key] !="") ? $request->des_adulto_pcr[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_fiO2[$key]) && $request->des_adulto_fiO2[$key] !="") ? $request->des_adulto_fiO2[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_leucocitos[$key]) && $request->des_adulto_leucocitos[$key] !="") ? $request->des_adulto_leucocitos[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_plaquetas[$key]) && $request->des_adulto_plaquetas[$key] !="") ? $request->des_adulto_plaquetas[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_bilirrubina[$key]) && $request->des_adulto_bilirrubina[$key] !="") ? $request->des_adulto_bilirrubina[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_ima[$key]) && $request->des_adulto_ima[$key] !="") ? $request->des_adulto_ima[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_icc[$key]) && $request->des_adulto_icc[$key] !="") ? $request->des_adulto_icc[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_evp[$key]) && $request->des_adulto_evp[$key] !="") ? $request->des_adulto_evp[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_ecv[$key]) && $request->des_adulto_ecv[$key] !="") ? $request->des_adulto_ecv[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_demencia[$key]) && $request->des_adulto_demencia[$key] !="") ? $request->des_adulto_demencia[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_dm[$key]) && $request->des_adulto_dm[$key] !="") ? $request->des_adulto_dm[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_diabetes_lesion_organica[$key]) && $request->des_adulto_diabetes_lesion_organica[$key] !="") ? $request->des_adulto_diabetes_lesion_organica[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_hemiplejia[$key]) && $request->des_adulto_hemiplejia[$key] !="") ? $request->des_adulto_hemiplejia[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_epoc[$key]) && $request->des_adulto_epoc[$key] !="") ? $request->des_adulto_epoc[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_patologia_tejido_conectivo[$key]) && $request->des_adulto_patologia_tejido_conectivo[$key] !="") ? $request->des_adulto_patologia_tejido_conectivo[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_enfermedad_ulcerosa[$key]) && $request->des_adulto_enfermedad_ulcerosa[$key] !="") ? $request->des_adulto_enfermedad_ulcerosa[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_patologia_renal_moderada_o_grave[$key]) && $request->des_adulto_patologia_renal_moderada_o_grave[$key] !="") ? $request->des_adulto_patologia_renal_moderada_o_grave[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_neoplasias[$key]) && $request->des_adulto_neoplasias[$key] !="") ? $request->des_adulto_neoplasias[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_neoplasias_especifique[$key]) && $request->des_adulto_neoplasias_especifique[$key] !="") ? $request->des_adulto_neoplasias_especifique[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_leucemias[$key]) && $request->des_adulto_leucemias[$key] !="") ? $request->des_adulto_leucemias[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_leucemias_especifique[$key]) && $request->des_adulto_leucemias_especifique[$key] !="") ? $request->des_adulto_leucemias_especifique[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_linfomas_malignos[$key]) && $request->des_adulto_linfomas_malignos[$key] !="") ? $request->des_adulto_linfomas_malignos[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_metastasis_colida[$key]) && $request->des_adulto_metastasis_colida[$key] !="") ? $request->des_adulto_metastasis_colida[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_sida[$key]) && $request->des_adulto_sida[$key] !="") ? $request->des_adulto_sida[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_antecedentes_patologicos_otros_1[$key]) && $request->des_adulto_antecedentes_patologicos_otros_1[$key] !="") ? $request->des_adulto_antecedentes_patologicos_otros_1[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_antecedentes_patologicos_otros_1_especifique[$key]) && $request->des_adulto_antecedentes_patologicos_otros_1_especifique[$key] !="") ? $request->des_adulto_antecedentes_patologicos_otros_1_especifique[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_antecedentes_patologicos_otros_2[$key]) && $request->des_adulto_antecedentes_patologicos_otros_2[$key] !="") ? $request->des_adulto_antecedentes_patologicos_otros_2[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_antecedentes_patologicos_otros_2_especifique[$key]) && $request->des_adulto_antecedentes_patologicos_otros_2_especifique[$key] !="") ? $request->des_adulto_antecedentes_patologicos_otros_2_especifique[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_terapia_antimicrobiana[$key]) && $request->des_adulto_terapia_antimicrobiana[$key] !="") ? $request->des_adulto_terapia_antimicrobiana[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_frasco_1[$key]) && $request->des_adulto_frasco_1[$key] !="") ? $request->des_adulto_frasco_1[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_frasco_1_opcion_1[$key]) && $request->des_adulto_frasco_1_opcion_1[$key] !="") ? $request->des_adulto_frasco_1_opcion_1[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_frasco_1_opcion_2[$key]) && $request->des_adulto_frasco_1_opcion_2[$key] !="") ? $request->des_adulto_frasco_1_opcion_2[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_frasco_2[$key]) && $request->des_adulto_frasco_2[$key] !="") ? $request->des_adulto_frasco_2[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_frasco_2_opcion_1[$key]) && $request->des_adulto_frasco_2_opcion_1[$key] !="") ? $request->des_adulto_frasco_2_opcion_1[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_frasco_2_opcion_2[$key]) && $request->des_adulto_frasco_2_opcion_2[$key] !="") ? $request->des_adulto_frasco_2_opcion_2[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_frasco_3[$key]) && $request->des_adulto_frasco_3[$key] !="") ? $request->des_adulto_frasco_3[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_frasco_3_opcion_1[$key]) && $request->des_adulto_frasco_3_opcion_1[$key] !="") ? $request->des_adulto_frasco_3_opcion_1[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_frasco_3_opcion_2[$key]) && $request->des_adulto_frasco_3_opcion_2[$key] !="") ? $request->des_adulto_frasco_3_opcion_2[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_hemocultivo_seguimiento_y_vigilancia[$key]) && $request->des_adulto_hemocultivo_seguimiento_y_vigilancia[$key] !="") ? $request->des_adulto_hemocultivo_seguimiento_y_vigilancia[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_fecha_primer_resultado_hemocultivo[$key]) && $request->des_adulto_fecha_primer_resultado_hemocultivo[$key] !="") ? $request->des_adulto_fecha_primer_resultado_hemocultivo[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->flag_solicitud_examen_especializados_hemocultivo_adulto[$key]) && $request->flag_solicitud_examen_especializados_hemocultivo_adulto[$key] !="") ? $request->flag_solicitud_examen_especializados_hemocultivo_adulto[$key] : "0";
            /******************************************************* EXAMEN PARA HEMOCULTIVO PARA NEONATO Y PEDRIATRÍA *******************************************************/
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->descripcion_examen[$key]) && $request->descripcion_examen[$key] !="") ? $request->descripcion_examen[$key] : "0"; /** NOMBRE O DESCRIPCIÓN DEL EXAMEN ESPECIALIZADO */
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_fecha_tiempo_enfermedad[$key]) && $request->des_pedriatria_fecha_tiempo_enfermedad[$key] !="") ? $request->des_pedriatria_fecha_tiempo_enfermedad[$key] : "0";
            /** EDAD GESTIONAL **/
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_postermino[$key]) && $request->des_pedriatria_postermino[$key] !="") ? $request->des_pedriatria_postermino[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_termino[$key]) && $request->des_pedriatria_termino[$key] !="") ? $request->des_pedriatria_termino[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_prematuro_leve[$key]) && $request->des_pedriatria_prematuro_leve[$key] !="") ? $request->des_pedriatria_prematuro_leve[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_prematuro_moderado[$key]) && $request->des_pedriatria_prematuro_moderado[$key] !="") ? $request->des_pedriatria_prematuro_moderado[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_prematuro_extremo[$key]) && $request->des_pedriatria_prematuro_extremo[$key] !="") ? $request->des_pedriatria_prematuro_extremo[$key] : "0";
            /** APGAR **/
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_apariencia[$key]) && $request->des_pedriatria_apariencia[$key] !="") ? $request->des_pedriatria_apariencia[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_pulso[$key]) && $request->des_pedriatria_pulso[$key] !="") ? $request->des_pedriatria_pulso[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_gesto[$key]) && $request->des_pedriatria_gesto[$key] !="") ? $request->des_pedriatria_gesto[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_actividad_respiratoria[$key]) && $request->des_pedriatria_actividad_respiratoria[$key] !="") ? $request->des_pedriatria_actividad_respiratoria[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_total_apgar[$key]) && $request->des_pedriatria_total_apgar[$key] !="") ? $request->des_pedriatria_total_apgar[$key] : "0";
            /** ESCALA DE GLASGOW **/
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_respuesta_ocular[$key]) && $request->des_pedriatria_respuesta_ocular[$key] !="") ? $request->des_pedriatria_respuesta_ocular[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_respuesta_verbal[$key]) && $request->des_pedriatria_respuesta_verbal[$key] !="") ? $request->des_pedriatria_respuesta_verbal[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_respuesta_motora[$key]) && $request->des_pedriatria_respuesta_motora[$key] !="") ? $request->des_pedriatria_respuesta_motora[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_total_escala_glasgow[$key]) && $request->des_pedriatria_total_escala_glasgow[$key] !="") ? $request->des_pedriatria_total_escala_glasgow[$key] : "0";
            /** SIGNOS VITALES **/
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_presion_arterial_x[$key]) && $request->des_pedriatria_presion_arterial_x[$key] !="") ? $request->des_pedriatria_presion_arterial_x[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_presion_arterial_y[$key]) && $request->des_pedriatria_presion_arterial_y[$key] !="") ? $request->des_pedriatria_presion_arterial_y[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_frecuencia_cardiaca[$key]) && $request->des_pedriatria_frecuencia_cardiaca[$key] !="") ? $request->des_pedriatria_frecuencia_cardiaca[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_frecuencia_respiratorio[$key]) && $request->des_pedriatria_frecuencia_respiratorio[$key] !="") ? $request->des_pedriatria_frecuencia_respiratorio[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_temperatura[$key]) && $request->des_pedriatria_temperatura[$key] !="") ? $request->des_pedriatria_temperatura[$key] : "0";
            /** EXÁMENES DE LABORATORIO **/
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_creatinina[$key]) && $request->des_pedriatria_creatinina[$key] !="") ? $request->des_pedriatria_creatinina[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_diuresis[$key]) && $request->des_pedriatria_diuresis[$key] !="") ? $request->des_pedriatria_diuresis[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_procalcitonina[$key]) && $request->des_pedriatria_procalcitonina[$key] !="") ? $request->des_pedriatria_procalcitonina[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_pcr[$key]) && $request->des_pedriatria_pcr[$key] !="") ? $request->des_pedriatria_pcr[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_fiO2[$key]) && $request->des_pedriatria_fiO2[$key] !="") ? $request->des_pedriatria_fiO2[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_leucocitos[$key]) && $request->des_pedriatria_leucocitos[$key] !="") ? $request->des_pedriatria_leucocitos[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_plaquetas[$key]) && $request->des_pedriatria_plaquetas[$key] !="") ? $request->des_pedriatria_plaquetas[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_bilirrubina[$key]) && $request->des_pedriatria_bilirrubina[$key] !="") ? $request->des_pedriatria_bilirrubina[$key] : "0";
            
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_glucosa[$key]) && $request->des_pedriatria_glucosa[$key] !="") ? $request->des_pedriatria_glucosa[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_alt[$key]) && $request->des_pedriatria_alt[$key] !="") ? $request->des_pedriatria_alt[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_vcg[$key]) && $request->des_pedriatria_vcg[$key] !="") ? $request->des_pedriatria_vcg[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_tp[$key]) && $request->des_pedriatria_tp[$key] !="") ? $request->des_pedriatria_tp[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_sodio[$key]) && $request->des_pedriatria_sodio[$key] !="") ? $request->des_pedriatria_sodio[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_potasio[$key]) && $request->des_pedriatria_potasio[$key] !="") ? $request->des_pedriatria_potasio[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_calcio[$key]) && $request->des_pedriatria_calcio[$key] !="") ? $request->des_pedriatria_calcio[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_pco2[$key]) && $request->des_pedriatria_pco2[$key] !="") ? $request->des_pedriatria_pco2[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_tpta[$key]) && $request->des_pedriatria_tpta[$key] !="") ? $request->des_pedriatria_tpta[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_examen_laboratorio_otros[$key]) && $request->des_pedriatria_examen_laboratorio_otros[$key] !="") ? $request->des_pedriatria_examen_laboratorio_otros[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_examen_laboratorio_otros_especifique[$key]) && $request->des_pedriatria_examen_laboratorio_otros_especifique[$key] !="") ? $request->des_pedriatria_examen_laboratorio_otros_especifique[$key] : "0";
            /** TERAPIA ANTIMICROBIANA **/
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_terapia_antimicrobiana[$key]) && $request->des_pedriatria_terapia_antimicrobiana[$key] !="") ? $request->des_pedriatria_terapia_antimicrobiana[$key] : "0";
            /** SOLICITUD DE FRASCO DE HEMOCULTIVO **/
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_frasco_1[$key]) && $request->des_pedriatria_frasco_1[$key] !="") ? $request->des_pedriatria_frasco_1[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_frasco_1_opcion_1[$key]) && $request->des_pedriatria_frasco_1_opcion_1[$key] !="") ? $request->des_pedriatria_frasco_1_opcion_1[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_frasco_1_opcion_2[$key]) && $request->des_pedriatria_frasco_1_opcion_2[$key] !="") ? $request->des_pedriatria_frasco_1_opcion_2[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_frasco_2[$key]) && $request->des_pedriatria_frasco_2[$key] !="") ? $request->des_pedriatria_frasco_2[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_frasco_2_opcion_1[$key]) && $request->des_pedriatria_frasco_2_opcion_1[$key] !="") ? $request->des_pedriatria_frasco_2_opcion_1[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_frasco_2_opcion_2[$key]) && $request->des_pedriatria_frasco_2_opcion_2[$key] !="") ? $request->des_pedriatria_frasco_2_opcion_2[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_frasco_3[$key]) && $request->des_pedriatria_frasco_3[$key] !="") ? $request->des_pedriatria_frasco_3[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_frasco_3_opcion_1[$key]) && $request->des_pedriatria_frasco_3_opcion_1[$key] !="") ? $request->des_pedriatria_frasco_3_opcion_1[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_frasco_3_opcion_2[$key]) && $request->des_pedriatria_frasco_3_opcion_2[$key] !="") ? $request->des_pedriatria_frasco_3_opcion_2[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_hemocultivo_seguimiento_y_vigilancia[$key]) && $request->des_pedriatria_hemocultivo_seguimiento_y_vigilancia[$key] !="") ? $request->des_pedriatria_hemocultivo_seguimiento_y_vigilancia[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_fecha_primer_resultado_hemocultivo[$key]) && $request->des_pedriatria_fecha_primer_resultado_hemocultivo[$key] !="") ? $request->des_pedriatria_fecha_primer_resultado_hemocultivo[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->flag_solicitud_examen_especializados_hemocultivo_pedriatria[$key]) && $request->flag_solicitud_examen_especializados_hemocultivo_pedriatria[$key] !="") ? $request->flag_solicitud_examen_especializados_hemocultivo_pedriatria[$key] : "0";
            $p_laboratorio .= ",";
            // ====================== SOLICITUD PARA ESTUDIO CITOQUÍMICO LÍQUIDOS BIOLÓGICOS  ===============================================
            $p_laboratorio .= (isset($request->des_antecedentes_patologicos_personales[$key]) && $request->des_antecedentes_patologicos_personales[$key] !="") ? $request->des_antecedentes_patologicos_personales[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_antecedentes_patologicos_familiares[$key]) && $request->des_antecedentes_patologicos_familiares[$key] !="") ? $request->des_antecedentes_patologicos_familiares[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_liquido_cefaloraquideo[$key]) && $request->des_liquido_cefaloraquideo[$key] !="") ? $request->des_liquido_cefaloraquideo[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_liquido_pleural[$key]) && $request->des_liquido_pleural[$key] !="") ? $request->des_liquido_pleural[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_liquido_ascitico[$key]) && $request->des_liquido_ascitico[$key] !="") ? $request->des_liquido_ascitico[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_liquido_sinovial[$key]) && $request->des_liquido_sinovial[$key] !="") ? $request->des_liquido_sinovial[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_liquido_otro[$key]) && $request->des_liquido_otro[$key] !="") ? $request->des_liquido_otro[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_liquido_otro_especifique[$key]) && $request->des_liquido_otro_especifique[$key] !="") ? $request->des_liquido_otro_especifique[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_num_frasco_muestra_opcion[$key]) && $request->des_num_frasco_muestra_opcion[$key] !="") ? $request->des_num_frasco_muestra_opcion[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->flag_solicitud_citoquimicos_liquidos_biologicos[$key]) && $request->flag_solicitud_citoquimicos_liquidos_biologicos[$key] !="") ? $request->flag_solicitud_citoquimicos_liquidos_biologicos[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_tono_muscular[$key]) && $request->des_pedriatria_tono_muscular[$key] !="") ? $request->des_pedriatria_tono_muscular[$key] : "0";
            /******************************************************* SOLICITUD TEST DE TOLERANCIA A LA GLUCOSA ORAL *******************************************************/
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_tolerancia_paciente_pediatrico_ttgo[$key]) && $request->des_tolerancia_paciente_pediatrico_ttgo[$key] !="") ? $request->des_tolerancia_paciente_pediatrico_ttgo[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_fecha_tolerancia_paciente_pediatrico_ttgo[$key]) && $request->des_fecha_tolerancia_paciente_pediatrico_ttgo[$key] !="") ? $request->des_fecha_tolerancia_paciente_pediatrico_ttgo[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_tolerancia_paciente_pediatrico_glucosa_anhidra[$key]) && $request->des_tolerancia_paciente_pediatrico_glucosa_anhidra[$key] !="") ? $request->des_tolerancia_paciente_pediatrico_glucosa_anhidra[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_cantidad_tolerancia_paciente_pediatrico_glucosa_anhidra[$key]) && $request->des_cantidad_tolerancia_paciente_pediatrico_glucosa_anhidra[$key] !="") ? $request->des_cantidad_tolerancia_paciente_pediatrico_glucosa_anhidra[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_tolerancia_paciente_adulto_ttgo[$key]) && $request->des_tolerancia_paciente_adulto_ttgo[$key] !="") ? $request->des_tolerancia_paciente_adulto_ttgo[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_fecha_tolerancia_paciente_adulto_ttgo[$key]) && $request->des_fecha_tolerancia_paciente_adulto_ttgo[$key] !="") ? $request->des_fecha_tolerancia_paciente_adulto_ttgo[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_tolerancia_paciente_adulto_glucosa_anhidra[$key]) && $request->des_tolerancia_paciente_adulto_glucosa_anhidra[$key] !="") ? $request->des_tolerancia_paciente_adulto_glucosa_anhidra[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_cantidad_tolerancia_paciente_adulto_glucosa_anhidra[$key]) && $request->des_cantidad_tolerancia_paciente_adulto_glucosa_anhidra[$key] !="") ? $request->des_cantidad_tolerancia_paciente_adulto_glucosa_anhidra[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_g[$key]) && $request->des_tolerancia_paciente_gestante_g[$key] !="") ? $request->des_tolerancia_paciente_gestante_g[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_p_1[$key]) && $request->des_tolerancia_paciente_gestante_p_1[$key] !="") ? $request->des_tolerancia_paciente_gestante_p_1[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_p_2[$key]) && $request->des_tolerancia_paciente_gestante_p_2[$key] !="") ? $request->des_tolerancia_paciente_gestante_p_2[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_p_3[$key]) && $request->des_tolerancia_paciente_gestante_p_3[$key] !="") ? $request->des_tolerancia_paciente_gestante_p_3[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_p_4[$key]) && $request->des_tolerancia_paciente_gestante_p_4[$key] !="") ? $request->des_tolerancia_paciente_gestante_p_4[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_edad_gestacional_meses[$key]) && $request->des_tolerancia_paciente_gestante_edad_gestacional_meses[$key] !="") ? $request->des_tolerancia_paciente_gestante_edad_gestacional_meses[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_edad_gestacional_semanas[$key]) && $request->des_tolerancia_paciente_gestante_edad_gestacional_semanas[$key] !="") ? $request->des_tolerancia_paciente_gestante_edad_gestacional_semanas[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_edad_gestacional_dias[$key]) && $request->des_tolerancia_paciente_gestante_edad_gestacional_dias[$key] !="") ? $request->des_tolerancia_paciente_gestante_edad_gestacional_dias[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_ttgo[$key]) && $request->des_tolerancia_paciente_gestante_ttgo[$key] !="") ? $request->des_tolerancia_paciente_gestante_ttgo[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_fecha_tolerancia_paciente_gestante_ttgo[$key]) && $request->des_fecha_tolerancia_paciente_gestante_ttgo[$key] !="") ? $request->des_fecha_tolerancia_paciente_gestante_ttgo[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_glucosa_anhidra[$key]) && $request->des_tolerancia_paciente_gestante_glucosa_anhidra[$key] !="") ? $request->des_tolerancia_paciente_gestante_glucosa_anhidra[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_cantidad_tolerancia_paciente_gestante_glucosa_anhidra[$key]) && $request->des_cantidad_tolerancia_paciente_gestante_glucosa_anhidra[$key] !="") ? $request->des_cantidad_tolerancia_paciente_gestante_glucosa_anhidra[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_factores_riesgo[$key]) && $request->des_tolerancia_paciente_gestante_factores_riesgo[$key] !="") ? $request->des_tolerancia_paciente_gestante_factores_riesgo[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_especifique_tolerancia_paciente_gestante_factores_riesgo[$key]) && $request->des_especifique_tolerancia_paciente_gestante_factores_riesgo[$key] !="") ? $request->des_especifique_tolerancia_paciente_gestante_factores_riesgo[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->flag_solicitud_test_tolerancia_glucosa_oral[$key]) && $request->flag_solicitud_test_tolerancia_glucosa_oral[$key] !="") ? $request->flag_solicitud_test_tolerancia_glucosa_oral[$key] : "0";
            $p_laboratorio .= ",0";
            $p_laboratorio .= "},";
          }
        endforeach;
        if (strlen($p_laboratorio) > 1) $p_laboratorio = substr($p_laboratorio, 0, -1);
        $p_laboratorio .= "}";
        if($request->id_examen_lab[0]!=""):
          if (intval($request->id_establecimiento) === 76):

            $has_observacion_unica = (intval($request->id_nivel) === 3) ? 1 : 0;
            $id_interconsulta = 0;
            $id_servicio = 76;
            $lab_orden_observacion_unica = intval($request->id_nivel) === 3 ? $request->lab_orden_observacion : null;
            $flag_cq = 0;
            $id_hora_toma_muestra = null;

            $id_procedimiento_lab = $model_procedimiento->RegistrarLaboratorioOrden3(Auth::user()->id, $id_prestacion, $request->id_establecimiento, $request->fecha_evaluacion, $p_laboratorio, $has_observacion_unica, $id_evaluacion, $request->id_farmacia, $id_interconsulta, $request->id_tipo_atencion, $id_servicio, $request->id_topico, $request->id_cama, $request->id_medico, $request->id_asegurado, $lab_orden_observacion_unica, $flag_cq, $id_hora_toma_muestra);
          else:
          $id_procedimiento_lab = $model_procedimiento->RegistrarLaboratorioOrden2(Auth::user()->id, $id_prestacion, $request->id_establecimiento, $request->fecha_evaluacion, $p_laboratorio, 2, $id_evaluacion, $request->id_farmacia, 0, $request->id_tipo_atencion, 76, $request->id_topico, $request->id_cama, $request->id_medico, $request->id_asegurado);
          endif;
          // $model_lab = new \App\Models\Laboratorio;
          // $ok=$model_lab->ActualizaIdEvaluacion($id_procedimiento_lab,$id_evaluacion);
          
        endif;
      endif;
                  
      //Imagenologia
      if (isset($request->id_examen_rad)) :
        $p_radiologia = "";
        $p_radiologia .= "{";
        foreach ($request->id_examen_rad as $key => $value) :
          if ($request->id_examen_rad[$key] != "") {
            $p_radiologia .= "{";
            $p_radiologia .= $request->id_examen_rad[$key] . ",";
            $p_radiologia .= (isset($request->examen_rad_cantidad[$key]) && $request->examen_rad_cantidad[$key] > 0) ? $request->examen_rad_cantidad[$key] : "0";
            $p_radiologia .= ",";
            $p_radiologia .= (isset($request->examen_rad_observacion[$key]) && $request->examen_rad_observacion[$key] !="") ? str_replace($search, $replace, $request->examen_rad_observacion[$key]) : 'NULL';
            $p_radiologia .= ",";
            $p_radiologia .= (isset($request->id_comparativo[$key]) && $request->id_comparativo[$key] !="") ? $request->id_comparativo[$key] : "0";
            $p_radiologia .= ",";
            $p_radiologia .= (isset($request->id_lado[$key]) && $request->id_lado[$key] !="") ? $request->id_lado[$key] : "0";
            $p_radiologia .= ",";
            $p_radiologia .= (isset($request->miSelectRad[$key]) && $request->miSelectRad[$key] !="") ? $request->miSelectRad[$key] : "0";
            $p_radiologia .= ",";
            $p_radiologia .= (isset($request->radio_portatil[$key]) && $request->radio_portatil[$key] !="") ? $request->radio_portatil[$key] : "0";
            $p_radiologia .= "},";
          }
        endforeach;
        if (strlen($p_radiologia) > 1) $p_radiologia = substr($p_radiologia, 0, -1);
        $p_radiologia .= "}";
        if($request->id_examen_rad[0]!=""):
          $id_procedimiento_rad = $model_procedimiento->RegistrarProcedimientoImagenes($request->id_user, $id_prestacion, $request->id_establecimiento, $request->fecha_atencion, $p_radiologia, 3, $id_evaluacion, $request->id_farmacia);
           /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Identificar radiología a la cual pertenece 
          if($request->id_establecimiento == 76 ){
              $updated = DB::table('rua_imagenologia')->where('id', $request->id_rua_imagenes)->update(['id_rad' => $id_procedimiento_rad,'id_evol'=> $id_evaluacion, 'id_estado_reg' => 1]);
          }
          ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
          $model_rad = new \App\Models\Radiologia;
          $ok=$model_rad->ActualizaIdEvaluacion($id_procedimiento_rad,$id_evaluacion);
        endif;
      endif;
      /***************************************************************************/
      $model_interconsulta = new \App\Models\Interconsulta;
      if(!empty($request->id_servicio_interconsulta)):
        $p_interconsulta = "";
        $p_interconsulta .= "{";
        foreach ($request->id_servicio_interconsulta as $key => $value) :

          $personal_coordinacion = $request->interconsulta_personal_coodinacion[$key];
          $personal_coordinacion = $personal_coordinacion != "" ? $personal_coordinacion : "NULL";

          if ($request->id_servicio_interconsulta[$key] != "") {
            $p_interconsulta .= "{";
            $p_interconsulta .= $request->id_servicio_interconsulta[$key] . ",";
            $p_interconsulta .= $turno . ",";
            $p_interconsulta .= "0,";
            $p_interconsulta .= str_replace($search, $replace, $request->motivo_interconsulta[$key]) . ",";
            $p_interconsulta .= "0,0,";
            $p_interconsulta .= $request->is_estable . ",";
            $p_interconsulta .= $personal_coordinacion;
            $p_interconsulta .= "},";
          }
        endforeach;
        if (strlen($p_interconsulta) > 1) $p_interconsulta = substr($p_interconsulta, 0, -1);
        $p_interconsulta .= "}"; 
        // dd(isset($request->id_examen_rad),"Algo5");
        if($request->id_servicio_interconsulta[0]!=""):
          $id_interconsulta = $model_interconsulta->RegistrarInterconsultaEvaluacion($request->id_prestacion, $asegurado->id, $request->id_tipo_atencion, $request->fecha_atencion, $request->id_medico, $request->id_establecimiento, $p_interconsulta,$id_evaluacion);
          $model_inter = new \App\Models\Interconsulta;
          $ok=$model_inter->ActualizaIdEvaluacion($id_interconsulta,$id_evaluacion);
        endif;
      endif;
    
      /*$model_referencia = new \App\Models\Referencia;

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
          $model_refer = new \App\Models\Referencia;
          $ok=$model_refer->ActualizaIdEvaluacion($id_referencia,$id_evaluacion);

          if($request->id_paciente!=0):
            $model_emergencia = new \App\Models\Emergencia;
            $update=$model_emergencia->ActualizaEstadoEmergencia($request->id_paciente,4);
          endif;

        endif;
      endif;
      */
      /***************************************************************************/   
      if (isset($request->id_producto)) :
        $id_receta_vale = $receta->postRegistrarRecetaValeNuevoConDiagnosticoEvaluacion($id_receta_vale, $request->id_farmacia_receta, $request->id_user, $id_consultorio, $nro_receta, $digitos_adicionales, $asegurado->nro_doc_ident, $asegurado->cip, $asegurado->tipo_doc_ident, $asegurado->nombre, $asegurado->paterno, $asegurado->materno, $tipo_beneficiario, $titular->nro_doc_ident, $titular->tipo_doc_ident, $titular->nombre, $titular->paterno, $titular->materno, $request->fecha_expedicion, $request->fecha_atencion, $nro_historia, $asegurado->grado, $request->id_medico, $id_anestesiologo, $id_tiporeceta, $turno, $obs_registro, $id_receta_vale_bus, $id_admision, $tipo_via, $sub_via, $final_via, $nro_guia, $interconsulta, $interconsulta_dias, $request->id_prestacion, $id_tipo_num, $estado_receta, $p_producto, $p_diagnostico, $asegurado->sexo, $asegurado->fecha_nac, $request->codigo_upss, $id_evaluacion);
          $model_rec_val = new \App\Models\Receta_vale;
          $ok=$model_rec_val->ActualizaIdEvaluacion($id_receta_vale,$id_evaluacion);
      else :
        $id_receta_vale = 1;
      endif;
      
      if (isset($request->id_diagnostico_egreso)):
        foreach ($request->id_diagnostico_egreso as $key => $value):
          if ($key == 0 && $request->id_diagnostico_egreso[$key] != "") {
            $id_1 = $request->id_diagnostico_egreso[$key];
          }
          if ($key == 1 && $request->id_diagnostico_egreso[$key] != "") {
            $id_2 = $request->id_diagnostico_egreso[$key];
          }
          if ($key == 2 && $request->id_diagnostico_egreso[$key] != "") {
            $id_3 = $request->id_diagnostico_egreso[$key];
          }
        endforeach;
      endif;

      $p_producto_alta = "";
      $p_producto_alta .= "{";
      if (isset($request->id_producto_alta)) :
        if ($request->id_producto_alta[0] != "ru='undefined'") :
          foreach ($request->id_producto_alta as $key => $value) :
            if ($request->id_producto_alta[$key] != "") {
              $p_producto_alta .= "{";
              $p_producto_alta .= $request->id_producto_alta[$key] . ",";
              $p_producto_alta .= $request->tiempo_periodo_alta[$key] . ",";
              $p_producto_alta .= $request->nro_med_solictados_alta[$key] . ",";
              $p_producto_alta .= (isset($request->nro_med_entregados_alta[$key]) && $request->nro_med_entregados_alta[$key] != "") ? $request->nro_med_entregados_alta[$key] : 0;
              $p_producto_alta .= ",";
              $p_producto_alta .= (isset($request->nro_stocks_almacen_alta[$key]) && $request->nro_stocks_almacen_alta[$key] != "") ? $request->nro_stocks_almacen_alta[$key] : 0;
              $p_producto_alta .= ",";
              $p_producto_alta .= (isset($request->nro_stocks_establecimiento_alta[$key]) && $request->nro_stocks_establecimiento_alta[$key] != "") ? $request->nro_stocks_establecimiento_alta[$key] : 0;
              $p_producto_alta .= ",";
              $p_producto_alta .= (isset($request->nro_stocks_alta[$key]) && $request->nro_stocks_alta[$key] != "") ? $request->nro_stocks_alta[$key] : 0;
              $p_producto_alta .= ",";
              $p_producto_alta .= (isset($request->posologia_alta[$key]) && $request->posologia_alta[$key] != "") ? str_replace(",", "###", $request->posologia_alta[$key]) : "-";
              $p_producto_alta .= ",";
              $p_producto_alta .= (isset($request->miSelectProd_alta[$key]) && $request->miSelectProd_alta[$key] != "") ? $request->miSelectProd_alta[$key] : 0;
              $p_producto_alta .= ",";
              $p_producto_alta .= $request->id_via_administracion_alta[$key];
              $p_producto_alta .= "},";
            }
          endforeach;
          if (strlen($p_producto_alta) > 1) $p_producto_alta = substr($p_producto_alta, 0, -1);
        endif;
      endif;
      $p_producto_alta .= "}";

      $p_diagnostico_alta = "";
      $p_diagnostico_alta .= "{";
      if (isset($request->id_diagnostico_egreso)) :
        foreach ($request->id_diagnostico_egreso as $key => $value) :
          if ($request->id_diagnostico_egreso[$key] != "") {
            $p_diagnostico_alta .= "{";
            $p_diagnostico_alta .= $request->id_diagnostico_egreso[$key] . ",";
            $p_diagnostico_alta .= "0";
            $p_diagnostico_alta .= "},";
          }
        endforeach;
        if (strlen($p_diagnostico_alta) > 1) $p_diagnostico_alta = substr($p_diagnostico_alta, 0, -1);
      endif;
      $p_diagnostico_alta .= "}";

      $receta_flag_origen = 2; // 2 = Alta Médica Evaluacion

      $id_receta_vale_alta = 0;
      if (isset($request->id_producto_alta)) :
        if ($request->id_producto_alta[0] != "ru='undefined'") :
          $id_receta_vale_alta = $receta->postRegistrarRecetaValeNuevoConDiagnosticoEvaluacionAltas(
            $id_receta_vale_alta,
            $request->id_farmacia_receta_egreso,
            $request->id_user,
            $id_consultorio,
            $nro_receta,
            $digitos_adicionales,
            $dni_beneficiario,
            $cip_beneficiario,
            $tipodoc_beneficiario,
            $nombre_beneficiario,
            $paterno_beneficiario,
            $materno_beneficiario,
            $tipo_beneficiario,
            $dni_titular,
            $tipdoc_titular,
            $nombre_titular,
            $paterno_titular,
            $materno_titular,
            $request->fecha_expedicion_alta,
            $fecha_atencion,
            $nro_historia,
            $asegurado->grado,
            $request->id_medico,
            $id_anestesiologo,
            $id_tiporeceta,
            $turno,
            $obs_registro,
            $id_receta_vale_bus,
            $id_admision,
            $tipo_via,
            $sub_via,
            $final_via,
            $nro_guia,
            $interconsulta,
            $interconsulta_dias,
            $id_prestacion,
            $id_tipo_num,
            $estado_receta,
            $p_producto_alta,
            $p_diagnostico_alta,
            $asegurado->sexo,
            $asegurado->fecha_nac,
            $request->codigo_upss,
            $id_evaluacion,
            $receta_flag_origen                 
          );
          $model_rec_val_alta = new \App\Models\Receta_vale;
          $ok_alta = $model_rec_val_alta->ActualizaIdEvaluacion($id_receta_vale_alta, $id_evaluacion);
          if (!$ok_alta):
            throw new \Exception("Error al enlazar la receta de alta con la Evaluación.");
          endif;
        endif;
      endif;

      return redirect('/ver_evaluacion/'.$request->id_user.'/'.$id_evaluacion);
  }

  public function ver_atenciones_evaluacion($id_paciente, $estado,$id_tipo_atencion)
    {

        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $date = Carbon::now();
        $fechaHasta = $date->format('d-m-Y');
        $fechaDesdex = $date->format('d-m-Y');
        $fechaDesdey = strtotime('-30 day', strtotime($fechaDesdex));
        $fechaDesde = date('d-m-Y', $fechaDesdey);
        $model_emergencias = new Emergencia;

        if($id_tipo_atencion==2){
          $verificar = Emergencia::where('id', $id_paciente)->where('estado', $estado)->first();
        }
        else{
          $verificar = Hospitalizacione::where('id', $id_paciente)->where('estado', $estado)->first(); 
        }

        $model_consultorio = new Consultorio;
    
        if(is_object($verificar)):
          $paciente = $model_emergencias->GetBuscaPacienteEmergencia($id_paciente, $estado);
          if(count($paciente)>0):
          $id_user = Auth::user()->id;
          //$usuario_actual = $model_usuario->GetById($id_user);
          $flagemergenciareferencia = Auth::user()->flaghistoria;
          $id_consultorio_actual = Auth::user()->id_consultorio;
          $dni_beneficiario = "";
          $nombre_beneficiario = "";
          $estado = "1";
          $id_atencione = $id_tipo_atencion;

          //====================
          $model_farma = new Farmacia();
          $farmacia = $model_farma->GetFarmaByUser($id_user);
          $id_farmacia = $farmacia->id;
          $id_ipress = $farmacia->id_establecimiento;
          $modo_validacion = $farmacia->modo_validacion;

          $model_consultorio = new Consultorio;
          $consultorio = $model_consultorio->ShowAllConsultoriosSubConsultorio($farmacia->id_establecimiento);

          $medico = 0;

          $model_establecimiento = new \App\Models\Establecimiento;
          $establecimientos = $model_establecimiento->GetById($id_ipress);
          if(Auth::user()->tipo_registro==1){
            $model_medico = new Medico();
            $medico_actual = $model_medico->GetMedicoByDniAndEstablecimiento(Auth::user()->dni,$farmacia->id_establecimiento);
            $medico = $medico_actual->id;
          }
          $datoadicional = $model_emergencias->GetObtenerCama($paciente->cama);

          $ruta = "all_pacientes_evaluaciones_by_emergencia";
          $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
          $permiso = $menu[0];

          $model_tipo_parentesco = new \App\Models\Tipo_parentescos;
                 $tipo_parentesco_obj = $model_tipo_parentesco->getTipo_parentescos_by_id($paciente->id_tipo_parentesco); 
                 $tipo_parentesco=$tipo_parentesco_obj[0]->descripcion;

            return view('frontend.evaluaciones.all_evaluaciones_atenciones', compact('farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nombre_beneficiario', 'estado','flagemergenciareferencia','consultorio','medico','id_consultorio_actual','id_atencione','paciente','id_paciente','establecimientos','datoadicional','permiso','tipo_parentesco'));
          else:
            return redirect('/dashboard')->withInput()->withErrors(array('Registros de paciente no encontrado..!'));
          endif;
        else:
          if($estado==2):
            return redirect('/dashboard')->withInput()->withErrors(array('Elemento no encontrado ..!'));
          else:
            return redirect('/dashboard')->withInput()->withErrors(array('Elemento no encontrado ..!'));
          endif;
        endif;
    }

    public function listar_evaluacion_ajax(Request $request){
    
    $model_evaluacion = new Evaluacione();
    $p[]=$request->fecha_ini;
    $p[]=$request->fecha_fin;
    $p[]=$request->id_paciente;
    $p[]=$request->estado;
    $p[]=$request->id_atencione;
    $p[]=$request->dni_beneficiario;
    $p[]=$request->NumeroPagina;
    $p[]=$request->NumeroRegistros;
    $data = $model_evaluacion->listar_evaluacion_ajax($p);
    $iTotalDisplayRecords = isset($data[0]->totalrows)?$data[0]->totalrows:0;
    $result["PageStart"] = $request->NumeroPagina;
    $result["pageSize"] = $request->NumeroRegistros;
    $result["SearchText"] = "";
    $result["ShowChildren"] = true;
    $result["iTotalRecords"] = $iTotalDisplayRecords;
    $result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
    $result["aaData"] = $data;
    echo json_encode($result);
    
  }

  public function listar_evaluacion_emergencia_ajax(Request $request){
    
    $model_evaluacion = new Evaluacione();
    $p[]=Auth::user()->id_establecimiento;
    $p[]=$request->fecha_ini;
    $p[]=$request->fecha_fin;
    $p[]=$request->dni_beneficiario;
    $p[]=$request->nombre_beneficiario;
    $p[]=$request->estado;
    $p[]=2;
    $p[]=$request->id_prioridad;
    $p[]=$request->id_topico;
    $p[]=$request->NumeroPagina;
    $p[]=$request->NumeroRegistros;
    $data = $model_evaluacion->listar_evaluacion_emergencia($p);
    $iTotalDisplayRecords = isset($data[0]->totalrows)?$data[0]->totalrows:0;
    $result["PageStart"] = $request->NumeroPagina;
    $result["pageSize"] = $request->NumeroRegistros;
    $result["SearchText"] = "";
    $result["ShowChildren"] = true;
    $result["iTotalRecords"] = $iTotalDisplayRecords;
    $result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
    $result["aaData"] = $data;
    echo json_encode($result);
    
  }

  
  public function historial_atenciones_evaluacion($id_paciente, $estado)
    {
      
      $model_emergencias = new Emergencia;
      $paciente = $model_emergencias->GetBuscaPacienteEmergencia( $id_paciente, $estado);
      if(count($paciente)>0):
        $model_evaluaciones = new \App\Models\Evolucion;
        $evaluaciones = $model_evaluaciones->GetEvolucionesByPaciente($id_paciente, $estado);
        return view('frontend.evaluaciones.historial_atenciones_evaluacion', compact('evaluaciones','paciente'));
      else:
        return redirect('/dashboard')->withInput()->withErrors(array('Elemento no encontrado ..!'));
      endif;

    }

  public function modal_atencion_paciente($id_ext){
    $model_emergencia = new \App\Models\Emergencia;
    $model_prestacion = new \App\Models\Prestaciones;
    $triaje_inicial = $model_emergencia->GetEmergenciaById($id_ext);
    $prestacion = $model_prestacion->GetPrestacionByIdPrestacionExt($id_ext);
    return view('frontend.epicrisis.modal_atencion',compact('prestacion','triaje_inicial'));
  }

  public function modal_evaluaciones_paciente($id_ext){
    $model_evaluacion = new \App\Models\Evolucion;
    $evaluaciones = $model_evaluacion->GetEvolucionesByIdExt($id_ext);
    return view('frontend.epicrisis.modal_evaluaciones',compact('evaluaciones'));
  }

  // public function registrar_evaluacion_transaction_emergencia(Request $request)
  public function registrar_evaluacion_transaction_emergencia(EvaluacionEmergenciaFormRequest $request)
  {
    
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    if (intval($request->id_tipo_egreso) == 2):
      Log::info("Info Evaluacion Emergencia => id_emergencia: " . $request->id_emergencia . 
        "\n\ndatos del request: " . json_encode($request->all())
      );
    endif;

    $registro_validado = false;
    $mensaje_error = "";

    DB::beginTransaction();
    try {

    $search = array('+', ',','"','/', '{', '}');
    $replace = array('\+', '\,', '\"', '\/', '\{', '\}');
    
    $model_prestacion = new \App\Models\Prestaciones;

    if ($request->id_diagnostico[0]!="") :
      $p_diagnostico = "";
      $p_diagnostico .= "{";
      if (isset($request->id_diagnostico)) :
        foreach ($request->id_diagnostico as $key => $value) :
          if ($request->id_diagnostico[$key] != "") {
            $p_diagnostico .= "{";
            $p_diagnostico .= $request->id_diagnostico[$key] . ",";
            $p_diagnostico .= (isset($request->id_tipo_diagnostico[$key]) && $request->id_tipo_diagnostico[$key] > 0) ? $request->id_tipo_diagnostico[$key] : "0";
            $p_diagnostico .= "},";
          }
        endforeach;
        if (strlen($p_diagnostico) > 1) $p_diagnostico = substr($p_diagnostico, 0, -1);
      endif;
      $p_diagnostico .= "}";
    else:
        $diagnosticos = $model_prestacion->GetDiagnosticoByIdPrestacion($request->id_prestacion);
        $p_diagnostico = "";
        $p_diagnostico .= "{";
        foreach ($diagnosticos as $diagnostico ) :
            $p_diagnostico .= "{";
            $p_diagnostico .= $diagnostico->id_diagnostico . ",";
            $p_diagnostico .= $diagnostico->id_tipo_diagnostico;
            $p_diagnostico .= "},";
        endforeach;
        if (strlen($p_diagnostico) > 1) $p_diagnostico = substr($p_diagnostico, 0, -1);
        $p_diagnostico .= "}";
        
    endif;

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
    
    $id_estado_reg = 1;
    $observacion = "";
    $id_receta_vale = 0;
    $id_anestesiologo = 0;
    $id_receta_vale_bus = 0;
    $id_admision = 0;
    $tipo_via = "";
    $sub_via = "";
    $final_via = "";
    $nro_guia = "";
    $interconsulta_dias = 0;
    $nro_receta = "";
    $digitos_adicionales = "";
    $id_tipo_num = 0;
    $fecha_expedicion =  (isset($request->fecha_expedicion)) ? $request->fecha_expedicion : "";
    $anamnesis = (isset($request->anamnesis)) ? $request->anamnesis : "";
    $examen_fisico = (isset($request->examen_fisico)) ? $request->examen_fisico : "";
    $estado_receta = 2;
    $cantidadReceta = (isset($request->cantidadReceta) && $request->cantidadReceta > 0) ? $request->cantidadReceta : 1;
    $id_asegurado   = $request->id_asegurado;
    $receta_prescrita        =  0;
    
    $p_producto = "";
    $p_producto .= "{";
    if (isset($request->id_producto)) :
      if($request->id_producto[0]!="ru='undefined'"):
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
            $p_producto .= $request->id_via_administracion[$key];
            $p_producto .= "},";
          }
        endforeach;
        if (strlen($p_producto) > 1) $p_producto = substr($p_producto, 0, -1);
      endif;    
    endif;
    $p_producto .= "}";



    $id_farmacia_receta        =  ($request->id_farmacia_receta!="") ? $request->id_farmacia_receta : 0;
    
    if (Auth::user()->tipo_registro == 3) $id_receta_vale_bus = 1;

    $id_sub_consultorio=0;

    /*if($request->id_modalidad==2){
      $medico_consultorio = new Medico_consultorio();
      $sub_consultorio_medico = $medico_consultorio->GetSubConsultorioByIdEstablecimientoAndIdConsultorio( $request->id_establecimiento, $request->id_servicio, $request->id_tipo_atencion);  
      $id_sub_consultorio =$sub_consultorio_medico[0]->id;
    }*/
    
     
    if($request->id_tipo_atencion==2){
        $id_sub_consultorio=545;
    }
    
    // $model_emergencia = new \App\Models\Emergencia;
    $model_evaluacion = new \App\Models\Evaluacione;
    $receta = new \App\Models\Receta_vale;
    
    
    /*****************************Otros datos**************************************************************/
    $temperatura                = $request->temperatura != '' ? $request->temperatura : 0;
    $sa02_porcentaje            = $request->saturacion != '' ? $request->saturacion : 0;
    $presion_arterial_x         = $request->presion_arterial_x != '' ? $request->presion_arterial_x : 0;
    $presion_arterial_y        = $request->presion_arterial_y != '' ? $request->presion_arterial_y : 0;
    $talla_cm                   = $request->talla != '' ? $request->talla : 0;
    $tamano_cintura_cm          = $request->tamanio_cintura != '' ? $request->tamanio_cintura : 0;
    $frecuencia_respiratoria    = $request->frecuencia_respiratoria != '' ? $request->frecuencia_respiratoria: 0;
    $peso_kg                    = $request->peso != '' ? $request->peso: 0;
    $frecuencia_cardiaca_fetal  = $request->frec_cardiaca_fetal != '' ? $request->frec_cardiaca_fetal : 0;
    $frecuencia_cardiaca        = $request->frec_cardiaca != '' ? $request->frec_cardiaca : 0;
    $fi02                       = $request->oxigenacion != '' ? $request->oxigenacion : 0;
    
    if(isset($request->id_producto)):
      $valor = 1;     //Valor
    else:
      $valor = 2; //vaciio
    endif;

    // Log::info("[request] => ", ["request" => $request]);
    $fecha_hora_atencion = $request->fecha_atencion . ' ' . $request->hora_atencion;

    $id_evaluacion = $model_evaluacion->registrarEvaluacion_v2($request->id_emergencia,$request->id_prestacion, $request->id_asegurado, $request->id_tipo_atencion,  $fecha_hora_atencion, $request->id_medico,  $request->id_user, $examen_fisico, $temperatura, $sa02_porcentaje, $presion_arterial_x, $presion_arterial_y, $talla_cm, $tamano_cintura_cm, $frecuencia_respiratoria, $peso_kg, $frecuencia_cardiaca_fetal, $frecuencia_cardiaca, $fi02, $request->motivo_visita, $request->tiempo_enfermedad, $request->examen_clinico, $request->examen_auxiliar, $request->tratamiento_auxiliar, $request->plan_trabajo,$request->antecedente,$request->antecedente_familiar,$request->id_prioridad,$request->id_topico,$request->fecha_inicio,$request->appt,$request->id_cama, $p_diagnostico, $valor);
    
    $evaluacionUpdateSiteds = Evaluacione::where("id", $id_evaluacion);
    $filas_afectadas_upd_siteds = $evaluacionUpdateSiteds->update([
      "updated_at_cod_siteds" => Carbon::now(),
      "updated_us_cod_siteds" => Auth::user()->id
    ]);

    if ($filas_afectadas_upd_siteds === 0):
      throw new \Exception("Error al intentar actualizar el código Siteds de la Evaluación.");
    endif;

    /* $eventosPrevios = Prestacion_eventos::where('id_prestacion', $request->id_prestacion)->first();
    if ($eventosPrevios) {
      $eventosPrevios->evento_1 = $request->input("evento_1") ? 11 : null; 
      $eventosPrevios->evento_2 = $request->input("evento_2") ? 13 : null; 
      $eventosPrevios->evento_3 = $request->input("evento_3") ? 21 : null; 
      $eventosPrevios->evento_4 = $request->input("evento_4") ? 14 : null; 
      $eventosPrevios->evento_5 = $request->input("evento_5") ? 12 : null; 
      $eventosPrevios->evento_6 = $request->input("evento_6") ? 16 : null; 
      $eventosPrevios->evento_7 = $request->input("evento_7") ? 15 : null; 
      $eventosPrevios->evento_8 = $request->input("evento_8") ? 8 : null;
      $eventosPrevios->save(); 
    } else {
    } */
    /* foreach ($eventos as $idEvento) {
    Prestacion_eventos::create([
      'id_prestacion' => $request->id_prestacion,
      'id_evento' => $idEvento,
      'estado' => 1,
      'created_us' => Auth::id(),
    ]);
    } */
    /*********************************  Eventos epidemiologicos ************************************/
    /* $eventos = $request->input('evento_ep', []);
    $idPrestacion = $request->id_prestacion;
    foreach ($eventos as $idEvento) {
      $registroExistente = Prestacion_eventos::where('id_prestacion', $idPrestacion)
        ->where('id_evento', $idEvento)
        ->first();

      if ($registroExistente) {
        $registroExistente->update([
            'estado' => 0,
            'updated_us' => Auth::id(),
        ]);
      } else {
        // Si no existe, crea un nuevo registro
        dd($idEvento);
        Prestacion_eventos::create([
            'id_prestacion' => $idPrestacion,
            'id_evento' => $idEvento,
            'estado' => 1,
            'created_us' => Auth::id(),
        ]);
      }
    } */

    /****************PUERPERIO*********************************************/
    $puerperio = $request -> fecha_parto == null ? false : true;
    $fecha_parto_real = $request -> fecha_parto;
    $hora_parto_real = $request -> hora_parto;
    $tipo_parto = $request -> tipo_parto;
    $complicacion_parto = $request -> complicacion_parto == 1 ? "SIN COMPLICACIONES" : "COMPLICADO";
    $observacion_parto = $request -> descripcion_parto;
    $estados_vida = $request -> estado_vida_puerpera;
    $tipo_docs = $request -> tipo_documento_puerpera;
    $dnis = $request -> numero_documento_puerpera;
    $sexos = $request -> sexo_puerpera;
    $pesos = $request -> peso_puerpera;
    $tallas = $request -> talla_puerpera;
    error_log($puerperio);
    error_log($fecha_parto_real);
    error_log($hora_parto_real);
    error_log($tipo_parto);
    error_log($complicacion_parto);
    error_log($observacion_parto);

    // Array de matrices que contienen toda la data ingresada para nada nacido:
    $natis = [];
    for ($i = 0; $i < sizeof($dnis); $i++) {
      $data = [$estados_vida[$i], $tipo_docs[$i], $dnis[$i], $sexos[$i], $pesos[$i], $tallas[$i]];
      array_push($natis, $data);
    }
    for ($i = 0; $i < sizeof($natis); $i++) {
      error_log(implode($natis[$i]));
    }
    error_log(implode(array($request)));

    $puerperioEncontrado = null;
    if ($request -> id_puerperio) {
      $puerperioEncontrado = Puerperio::find($request->id_puerperio);
    }

    // SI O SI DEBE HABER UN NATI MINIMO
    if ($puerperio == 1) {
      if (sizeof($natis) == 0) {
        throw new \Exception("No puede registrar una prestacion con informacion de puerperio incompleta.");
        // Session::flash('flash_message', 'No puede registrar una prestacion con informacion de puerperio incompleta.');
        // return redirect('/formulario_evaluacion_emergencia/'.$request->id_emergencia);
      }
      $paciente = Asegurado::find($id_asegurado);
      $paciente->estadios_salud_materna = '3';
      $paciente->update_estadios_salud_materna = Carbon::now();
      $paciente->id_prestacion_estadios_salud_materna = $request->id_prestacion;
      $paciente->id_secuencia_estadios_salud_materna = '3';
      if (!$paciente->save()):
        throw new \Exception("Error al actualizar el estado de Salud materna con el paciente.");
      endif;

      if ($puerperioEncontrado == null) {
        $model_puerperia = new \App\Models\Puerperio;
        $model_puerperia -> fecha_parto = $fecha_parto_real." ".$hora_parto_real;
        $model_puerperia -> id_tipo_parto = $request -> tipo_parto;
        $model_puerperia -> complicaciones = $complicacion_parto;
        $model_puerperia -> descripcion = $observacion_parto;
        if (!$model_puerperia->save()):
          throw new \Exception("Error al registrar los datos del Puerperio.");
        endif;

        $emergencia = Emergencia::find(Prestaciones::find($request->id_prestacion)->id_ext);
        error_log("id emergencia para la prestacion: ".(string)$emergencia->id);
        $emergencia -> id_puerperio = $model_puerperia -> id;
        if (!$emergencia->save()):
          throw new \Exception("Error al enlazar los datos del Puerperio con el registro de Emergencia.");
        endif;

        for ($i = 0; $i < sizeof($natis); $i++) {
          $model_nati = new \App\Models\Nati;
          $model_nati -> id_puerperio = $model_puerperia -> id;
          $model_nati -> estado = $natis[$i][0]; // primer dato, es el estado
          $model_nati -> id_tipo_doc_nati = $natis[$i][1]; // tipo_doc
          $model_nati -> num_doc = $natis[$i][2];
          $model_nati -> sexo = $natis[$i][3];
          $model_nati -> peso = $natis[$i][4];
          $model_nati -> talla = $natis[$i][5];
          if (!$model_nati->save()):
            throw new \Exception("Error al registrar un Neonato (1." . ($i + 1) . ").");
          endif;
        }
      } else {
        for ($i = 0; $i < sizeof($natis); $i++) {
          $model_nati = new \App\Models\Nati;
          $model_nati -> id_puerperio = $puerperioEncontrado -> id;
          $model_nati -> estado = $natis[$i][0]; // primer dato, es el estado
          $model_nati -> id_tipo_doc_nati = $natis[$i][1]; // tipo_doc
          $model_nati -> num_doc = $natis[$i][2];
          $model_nati -> sexo = $natis[$i][3];
          $model_nati -> peso = $natis[$i][4];
          $model_nati -> talla = $natis[$i][5];
          error_log($natis[$i][4]);
          error_log($natis[$i][5]);
          if (!$model_nati->save()):
            throw new \Exception("Error al registrar un Neonato (2." . ($i + 1) . ").");
          endif;
        }
      }
    }
    /****************FIN PUERPERIO*********************************************/

    $idPrestacion = $request->input('id_prestacion');
    $eventosSeleccionados = $request->input('evento_ep', []); // eventos marcados en el formulario

    $eventosSeleccionados = array_map('intval', $eventosSeleccionados);

    $eventosExistentes = Prestacion_eventos::where('id_prestacion', $idPrestacion)->get();

    /* foreach ($eventosExistentes as $evento) {
        if (!in_array($evento->id_evento, $eventosSeleccionados)) {
            $evento->estado = 0;
            $evento->updated_us = Auth::id();
            $evento->updated_at = date('Y-m-d H:i:s');
            $evento->save();
        }
    } */

    foreach ($eventosSeleccionados as $idEvento) {
        $evento = null;
        foreach ($eventosExistentes as $e) {
            if ($e->id_evento == $idEvento) {
                $evento = $e;
                break;
            }
        }

        if ($evento) {
            if ($evento->estado != 1) {
                $evento->estado = 1;
                $evento->updated_us = Auth::id();
                $evento->updated_at = date('Y-m-d H:i:s');
                if ($evento->save()):
                  throw new \Exception("Error al actualizar un evento (Prestación evento).");
                endif;
            }
        } else {
            $registro = Prestacion_eventos::create([
                'id_prestacion' => $idPrestacion,
                'id_evolucion' => $id_evaluacion,
                'id_evento' => $idEvento,
                'estado' => 1,
                'created_us' => Auth::id(),
            ]);

            if (empty($registro)):
              throw new \Exception("Error al registrar un evento (Prestación evento).");
            endif;
        }
    }

    $model_evaluacion_siteds = new \App\Models\Evaluacione;
    $ok=$model_evaluacion_siteds->ActualizaSiteds($request->codigo_siteds,$id_evaluacion);
    if (!$ok):
      throw new \Exception("Error al actualizar el Siteds de la Evaluación.");
    endif;
    
    $id_consultorio = 76;
    $id_tiporeceta = $request->id_tipo_atencion;
    $obs_registro = "";
    $interconsulta = "";
    $interconsulta_dias = 0;
    $nro_receta = "";
    $digitos_adicionales = "";
    $id_tipo_num = 0;
    $estado_receta = 2;
    
    $date = Carbon::now();
      $hora=$date->toTimeString(); 

      if($hora>'00:00' & $hora<'07:31'):
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
            //else:
              //if($hora>='00:00' & $hora<='01:31'):
                /*echo*/ //$turno='';
              //endif;
            endif;
          endif;
        endif;
      endif;

    $prestacion = Prestaciones::find($request->id_prestacion);
    $cita = Cita::find($prestacion->id_cita);
    $asegurado = Asegurado::find($cita->id_asegurado);
    $titular = Asegurado::find($cita->id_titular);

    if (isset($request->id_producto)) :
      if($request->id_producto[0]!="ru='undefined'"):
        $id_receta_vale = $receta->postRegistrarRecetaValeNuevoConDiagnosticoEvaluacion($id_receta_vale, $request->id_farmacia_receta, $request->id_user, $id_consultorio, $nro_receta, $digitos_adicionales, $request->nrodocafiliado, $titular->cip, $request->tipodoc_beneficiario, $request->nombre_beneficiario, $request->paterno_beneficiario, $request->materno_beneficiario, $request->tipo_beneficiario, $titular->nro_doc_ident, $titular->tipo_doc_ident, $titular->nombre, $titular->paterno, $titular->materno, $request->fecha_expedicion, $request->fecha_atencion, $request->nro_historia, $request->grado, $request->id_medico, $id_anestesiologo, $id_tiporeceta, $turno, $obs_registro, $id_receta_vale_bus, $id_admision, $tipo_via, $sub_via, $final_via, $nro_guia, $interconsulta, $interconsulta_dias, $request->id_prestacion, $id_tipo_num, $estado_receta, $p_producto, $p_diagnostico, $request->nomsexo, $request->fecnacafiliado, $request->codigo_upss, $id_evaluacion);
          $model_rec_val = new \App\Models\Receta_vale;
          $ok=$model_rec_val->ActualizaIdEvaluacion($id_receta_vale,$id_evaluacion);
          if (!$ok):
            throw new \Exception("Error al enlazar una de las recetas vales con la Evaluación.");
          endif;
      endif;
    else :
      $id_receta_vale = 1;
    endif;
    
    /****************Actualiza Atencion Emergencia*********************************************/
    if($request->id_emergencia!=0):
        $model_cama = new \App\Models\Cama;
        $model_emergencia = new \App\Models\Emergencia;
        if($request->id_prioridad!=0): //prioridad <3
          //if($request->id_prioridad<3):
            $update=$model_cama->actualizaEstado($request->id_cama, 1, Auth::user()->id_establecimiento, "4");
            if (!$update):
              throw new \Exception("Error al ocupar la Cama del paciente.");
            endif;
          //else:
          //  $update=$model_cama->actualizaEstado($request->id_cama, 0);  
          //endif;
          $update=$model_emergencia->ActualizaUbicacionEstado($request->id_emergencia,$request->id_prioridad,$request->id_topico,$request->fecha_inicio,$request->appt,$request->id_cama,Auth::user()->id_establecimiento);

          if (!$update):
            throw new \Exception("Error al actualizar los datos donde se ubica el paciente en la Evaluación.");
          endif;
        endif;
        $updateid=$model_prestacion->AgregarIdExt($request->id_prestacion,$request->id_emergencia);
        if (!$updateid):
          throw new \Exception("Error al enlazar el registro de la atención (Prestación) con la atención de Emergencia.");
        endif;
    endif;


    /*********************************************************************/
    $model_procedimiento = new \App\Models\Procedimiento;
    if (isset($request->id_examen_lab)) :
      $id_examen_lab = array_filter($request->id_examen_lab, function ($valor) {
        return !empty($valor);
      });
      if (!empty($id_examen_lab)) {
      $p_laboratorio = "";
      $p_laboratorio .= "{";
      foreach ($request->id_examen_lab as $key => $value) :
        if ($request->id_examen_lab[$key] != "") {
          $p_laboratorio .= "{";
          $p_laboratorio .= $request->id_examen_lab[$key] . ",";
          if (intval($request->id_nivel) === 3):
            $p_laboratorio .= "0";
          else:
          $p_laboratorio .= (isset($request->examen_lab_observacion[$key]) && $request->examen_lab_observacion[$key] !="") ? str_replace($search, $replace, $request->examen_lab_observacion[$key]) : "0";
          endif;
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->examen_lab_cantidad[$key]) && $request->examen_lab_cantidad[$key] > 0) ? $request->examen_lab_cantidad[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->miSelectLab[$key]) && $request->miSelectLab[$key] !="") ? $request->miSelectLab[$key] : "0";
          $p_laboratorio .= ",null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null";
          $p_laboratorio .= ",null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null";
          $p_laboratorio .= ",null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null";
          $p_laboratorio .= ",null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null";
          $p_laboratorio .= ",null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null";
          $p_laboratorio .= ",null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null";
          $p_laboratorio .= ",null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null";
          $p_laboratorio .= ",null,null,null,";
          $p_laboratorio .= (isset($request->examen_lab_tiempo[$key]) && $request->examen_lab_tiempo[$key] !="") ? $request->examen_lab_tiempo[$key] : "0";
          $p_laboratorio .= "},";
        }
      endforeach;
      if (strlen($p_laboratorio) > 1) $p_laboratorio = substr($p_laboratorio, 0, -1);
      $p_laboratorio .= "}";
      if($request->id_examen_lab[0]!=""):
        if (intval($request->id_establecimiento) === 76):

          $has_observacion_unica = (intval($request->id_nivel) === 3) ? 1 : 0;
          $id_interconsulta = 0;
          $id_servicio = 76;
          $lab_orden_observacion_unica = intval($request->id_nivel) === 3 ? $request->lab_orden_observacion : null;
          $flag_cq = 0;
          $id_hora_toma_muestra = null;

          $id_procedimiento_lab = $model_procedimiento->RegistrarLaboratorioOrden3($request->id_user, $request->id_prestacion, $request->id_establecimiento, $request->fecha_atencion, $p_laboratorio, $has_observacion_unica, $id_evaluacion,$request->id_farmacia, $id_interconsulta, $request->id_tipo_atencion, $id_servicio, $request->id_topico, $request->id_cama, $request->id_medico, $request->id_asegurado, $lab_orden_observacion_unica, $flag_cq, $id_hora_toma_muestra);
        else:
        $id_procedimiento_lab = $model_procedimiento->RegistrarLaboratorioOrden2($request->id_user, $request->id_prestacion, $request->id_establecimiento, $request->fecha_atencion, $p_laboratorio, 2, $id_evaluacion,$request->id_farmacia, 0, $request->id_tipo_atencion, 76, $request->id_topico, $request->id_cama, $request->id_medico, $request->id_asegurado);
        endif;
      endif;
      // $model_lab = new \App\Models\Laboratorio;
      // $ok=$model_lab->ActualizaIdEvaluacion($id_procedimiento_lab,$id_evaluacion);
      }
    endif;

    //insertar procedimientos x diagnosticos

    if(isset($request->id_procedimiento)):
      if(isset($request->miSelect)):
        $arrayprocedimientos=$request->id_procedimiento;
        $arraydiagnosticos=$request->miSelect;
        for($iterador=0;$iterador<count($arrayprocedimientos);$iterador++){
   
          $model_procedimiento_metodos = new \App\Models\Procedimiento;
          $idprocedimiento= $arrayprocedimientos[$iterador];
         
          $iddiagnostico = $arraydiagnosticos[$iterador];
      
          $idprestacionxDiagProc = $request->id_prestacion;
          $mensajeSucess = $model_procedimiento_metodos->RegistrarProcedimientoxDiagnosticoEvaluacion($idprestacionxDiagProc,$idprocedimiento,$iddiagnostico,1, $id_evaluacion);  
        }

      endif;
    endif;

    $model_procedimiento = new \App\Models\Procedimiento;
    if (isset($request->id_examen_rad)) :
      $p_radiologia = "";
      $p_radiologia .= "{";
      $id_examen_rad = array_filter($request->id_examen_rad, function ($valor) {
        return !empty($valor);
      });
      if(!empty($id_examen_rad)) {
      foreach ($request->id_examen_rad as $key => $value) :
        if ($request->id_examen_rad[$key] != "") {
          $p_radiologia .= "{";
          $p_radiologia .= $request->id_examen_rad[$key] . ",";
          $p_radiologia .= (isset($request->examen_rad_cantidad[$key]) && $request->examen_rad_cantidad[$key] > 0) ? $request->examen_rad_cantidad[$key] : "0";
          $p_radiologia .= ",";
          $p_radiologia .= (isset($request->examen_rad_observacion[$key]) && $request->examen_rad_observacion[$key] !="") ? str_replace($search, $replace, $request->examen_rad_observacion[$key]) : 'NULL';
          $p_radiologia .= ",";
          $p_radiologia .= (isset($request->id_comparativo[$key]) && $request->id_comparativo[$key] !="") ? $request->id_comparativo[$key] : "0";
          $p_radiologia .= ",";
          $p_radiologia .= (isset($request->id_lado[$key]) && $request->id_lado[$key] !="") ? $request->id_lado[$key] : "0";
          $p_radiologia .= ",";
          $p_radiologia .= (isset($request->miSelectRad[$key]) && $request->miSelectRad[$key] !="") ? $request->miSelectRad[$key] : "0";
          $p_radiologia .= ",";
          $p_radiologia .= (isset($request->radio_portatil[$key]) && $request->radio_portatil[$key] !="") ? $request->radio_portatil[$key] : "0";
          $p_radiologia .= "},";
        }
      endforeach;
      if (strlen($p_radiologia) > 1) $p_radiologia = substr($p_radiologia, 0, -1);
      $p_radiologia .= "}";
      if($request->id_examen_rad[0]!=""):
        $id_procedimiento_rad = $model_procedimiento->RegistrarProcedimientoImagenes($request->id_user, $request->id_prestacion, $request->id_establecimiento, $request->fecha_atencion, $p_radiologia, 3, $id_evaluacion, $request->id_farmacia);
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Identificar radiología a la cual pertenece 
        if($request->id_establecimiento == 76 ){
            $updated = DB::table('rua_imagenologia')->where('id', $request->id_rua_imagenes)->update(['id_rad' => $id_procedimiento_rad,'id_evol'=> $id_evaluacion, 'id_estado_reg' => 1]);
        }
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      endif;
      $model_rad = new \App\Models\Radiologia;
      $ok=$model_rad->ActualizaIdEvaluacion($id_procedimiento_rad,$id_evaluacion);
      if (!$ok):
        throw new \Exception("Error al enlazar una de las ordenes de Imagenes con la Evaluación.");
      endif;
      }
    endif;
    /***************************************************************************/
    /* $model_interconsulta = new \App\Models\Interconsulta;
    if($request->id_servicio_interconsulta!=0 || $request->id_servicio_interconsulta!="" || $request->id_servicio_interconsulta!=null):
       if (isset($request->id_servicio_interconsulta)) :
        foreach ($request->id_servicio_interconsulta as $key => $value) :
          $id_servicio_interconsulta = $model_interconsulta->RegistrarInterconsultaEvaluacion($request->id_prestacion, $id_asegurado, $request->id_tipo_atencion, $request->fecha_atencion, $request->id_medico, $request->id_establecimiento, $request->id_servicio_interconsulta[$key], $turno,$request->personal_salud[$key],$request->resumen_interconsulta[$key],$id_evaluacion);
        endforeach;
      endif;
    endif;
    */
    $model_interconsulta = new \App\Models\Interconsulta;
      if(!empty($request->id_servicio_interconsulta)):
        $p_interconsulta = "";
        $p_interconsulta .= "{";
        foreach ($request->id_servicio_interconsulta as $key => $value) :

          $personal_coordinacion = $request->interconsulta_personal_coodinacion[$key];
          $personal_coordinacion = $personal_coordinacion != "" ? $personal_coordinacion : "NULL";

          if ($request->id_servicio_interconsulta[$key] != "") {
            $p_interconsulta .= "{";
            $p_interconsulta .= $request->id_servicio_interconsulta[$key] . ",";
            $p_interconsulta .= $turno . ",";
            $p_interconsulta .= "0,";
            $p_interconsulta .= str_replace($search, $replace, $request->motivo_interconsulta[$key]) . ",";
            $p_interconsulta .= "0,0,";
            $p_interconsulta .= $request->is_estable . ",";
            $p_interconsulta .= $personal_coordinacion;
            $p_interconsulta .= "},";
          }
        endforeach;
        if (strlen($p_interconsulta) > 1) $p_interconsulta = substr($p_interconsulta, 0, -1);
        $p_interconsulta .= "}"; 

        if($request->id_servicio_interconsulta[0]!=""):
          $id_interconsulta = $model_interconsulta->RegistrarInterconsultaEvaluacion($request->id_prestacion, $id_asegurado, $request->id_tipo_atencion, $request->fecha_atencion, $request->id_medico, $request->id_establecimiento, $p_interconsulta,$id_evaluacion);
          //$model_inter = new \App\Models\Interconsulta;
          //$ok=$model_inter->ActualizaIdEvaluacion($id_interconsulta,$id_evaluacion);
        endif;
      endif;

    if ($request->flag_registro_alta):
      $date = Carbon::now();
      $fechaActual = $date->format('d-m-Y');
      $horaActual = $date->format('H:i:s');

      // 
      $model_emergencia2 = Emergencia::where('id', '=', $request->id_emergencia)->first();
      /*$dni = Auth::user()->dni;
      $titular_prueba_jhon = Asegurado::where('id', '=', $model_emergencia2->id_asegurado)->first();
      $dni_prueba_jhon = $titular_prueba_jhon->nro_doc_ident;

      $celular = Auth::user()->celular;

      if ($celular == '2') {
        $titular = Asegurado::where('id', '=', $model_emergencia2->id_asegurado)->first();
        $titular->nro_doc_ident = '1234567';
        $titular->save();
      }

      if ($celular == '3') {
        $titular = Asegurado::where('id', '=', $model_emergencia2->id_asegurado)->first();
        $titular->sexo = 'F';
        $titular->save();
      }

      $medico = DB::table('medicos')
        ->select('medicos.*')
        ->where('dni', $dni)
        ->first();*/

      //SE PASO A ESTA PARTE PARA REUTILIZAR
      $model_emergencia2_ = new Emergencia();
      $valida_prestacion = $model_emergencia2_->getIdPrestacion($request->id_emergencia);
      $model_intervencion_validar = new \App\Models\CqxIntervencion;
      $existe_intervencion_cqx = $model_intervencion_validar::where('id_prestacion', $valida_prestacion->id)->exists();
      //FIN SE PASO A ESTA PARTE PARA REUTILIZAR

      //SI YA EXISTE LA INTERVENCION QUIRURGICA DE LA LA PRESTACION
      if ($existe_intervencion_cqx) {
        //SI SELECCIONO INTERVENCION QUIRURGICA
        if ((string)$request->requiere_intervencion_quirurgica === '1') {
          //RETORNARA EL MENSAJE QUE LA PRESTACION YA HA SIDO REGISTRADO
          Session::flash('flash_message', 'No se pudo realizar el alta médica del paciente que requiere intervención quirúrgica, ya que la prestación correspondiente ya ha sido registrada en el módulo de Centro Quirúrgico.');
          return redirect('/alta_medica/' . Auth::user()->id . '/' . $request->id_emergencia);
        }
      }

      if ($model_emergencia2->id_referencia != 0) {
        if ($request->id_tipo_egreso == 3) {
          $datos_referencia = Referencia::find($model_emergencia2->id_referencia);

          $model_actuliza_estado = new \App\Models\Actualizacion_estado;
          $model_actuliza_estado->estado_antiguo = $datos_referencia->estado_proceso;
          $model_actuliza_estado->estado_nuevo = 8;
          $model_actuliza_estado->id_referencia = $datos_referencia->id;
          $model_actuliza_estado->motivo_actualizacion = "Se guardo el alta en emergencia realizandoce la contrareferencia";
          $model_actuliza_estado->created_us = Auth::user()->id;
          $model_actuliza_estado->flag_estado_reg = 1;
          if (!$model_actuliza_estado->save()):
            throw new \Exception("Error al registrar un registro (Actualizacion_estado).");
          endif;

          $datos_referencia->estado_proceso = 8;
          $datos_referencia->id_servicio_contrareferencia = $request->especialida_destino;
          $datos_referencia->observacion_contrareferencia = $request->especialidad_destino_otro;
          $datos_referencia->id_establecimiento_contrareferencia = $request->id_establecimiento_traslado;
          $datos_referencia->id_medico_contrareferencia = $request->id_medico;
          $datos_referencia->id_prestacion_contrareferencia = $request->id_emergencia;
          $datos_referencia->fecha_atencion_cotrareferencia = $fechaActual;
          if (!$datos_referencia->save()):
            throw new \Exception("Error al actualizar el registro de Referencia.");
          endif;

        } else {
          $datos_referencia = Referencia::find($model_emergencia2->id_referencia);

          $model_actuliza_estado = new \App\Models\Actualizacion_estado;
          $model_actuliza_estado->estado_antiguo = $datos_referencia->estado_proceso;
          $model_actuliza_estado->estado_nuevo = 9;
          $model_actuliza_estado->id_referencia = $datos_referencia->id;
          $model_actuliza_estado->motivo_actualizacion = "Se guardo el alta en emergencia pero no se selecciono traslado para la contrareferencia";
          $model_actuliza_estado->created_us = Auth::user()->id;
          $model_actuliza_estado->flag_estado_reg = 1;
          if (!$model_actuliza_estado->save()):
            throw new \Exception("Error al registrar un registro (Actualizacion_estado).");
          endif;

          $datos_referencia->estado_proceso = 9;
          $datos_referencia->id_servicio_contrareferencia = 9;
          $datos_referencia->observacion_contrareferencia = "";
          $datos_referencia->id_medico_contrareferencia = $request->id_medico;
          $datos_referencia->id_prestacion_contrareferencia = $request->id_emergencia;
          $datos_referencia->fecha_atencion_cotrareferencia = $fechaActual;
          if (!$datos_referencia->save()):
            throw new \Exception("Error al actualizar el registro de Referencia.");
          endif;
        }
      }
      $fecha_cierre = $request->fecha_egreso;
      $hora_cierre = $request->hora_egreso;
      $fecha_cierre = date('Y-m-d', strtotime($fecha_cierre));
      $date = $fecha_cierre;
      $fechaActual = $fecha_cierre;
      $horaActual = $hora_cierre;
      $id_user =  Auth::user()->id;
      $model_emergencia = Emergencia::where('id', '=', $request->id_emergencia)->first();
      $emergencia_prestacion = Prestaciones::where('id_ext', '=', $request->id_emergencia)->where('id_tipo_atencion', 2)->first();

      $existeevolucion = "SELECT count(e.id)  from emergencias e
                          inner join evaluaciones e2 on e2.id_emergencia = e.id
                          where e.id = '$request->id_emergencia' and e2.id_estado_reg <> 0";
      $existeevolucion = DB::select($existeevolucion);
      $existeevolucion = $existeevolucion[0]->count;

      if ($existeevolucion == 0) {
        $fecha_hora_ingreso = $model_emergencia->fecha_atencion;
        $id_evaluacion = 0;
      } else {
        $datoevalucion = "SELECT e2.* from emergencias e
                          inner join evaluaciones e2 on e2.id_emergencia = e.id
                          where e.id = '$request->id_emergencia' and e2.id_tipo_atencion = 2 and e2.id_estado_reg <> 0 order by e2.created_at desc";
        $datoevalucion = DB::select($datoevalucion);
        $fecha_hora_ingreso = $datoevalucion[0]->fecha_atencion;
        $id_evaluacion = $datoevalucion[0]->id;
      }

      $unido = ($fecha_cierre . " " . $hora_cierre);
      $resulta = ((strtotime($unido)) - (strtotime($fecha_hora_ingreso))) / 86400;

      if ($resulta < 0) {
        Session::flash('flash_message', 'NO SE REGISTRO EL ALTA - LA FECHA DE ALTA INGRESADA ' . $unido . ' ES MENOR A LA FECHA DE LA ULTIMA ATENCION ' . $fecha_hora_ingreso . ' - FORMATO DE FECHA 24 HORAS, MODIFIQUE LA FECHA DE ALTA');
        return redirect('/alta_medica/' . $id_user . '/' . $request->id_emergencia);
      }

      $dato_triaje_cpms = "SELECT * from triajes where id = '$model_emergencia->id_triaje'";
      $dato_triaje_cpms = DB::select($dato_triaje_cpms);

      $resulta_hora = ((strtotime($unido)) - (strtotime($fecha_hora_ingreso))) / 43200;
      //dd($resulta_hora);
      $estable_nivel =  DB::table('establecimientos')
        ->select('establecimientos.*')
        ->where('id', $model_emergencia->id_establecimiento)
        ->first();

      if ($estable_nivel == '3') {
        if ($model_emergencia->id_triaje == "1" || $model_emergencia->id_triaje == "12") {
          if ($resulta_hora < 1) {
            $codigo_cpms = "99234";
          } else {
            $codigo_cpms = "99295";
          }
        } else if ($model_emergencia->id_triaje == "20") {
          $codigo_cpms = "";
        } else {
          if ($resulta_hora < 1) {
            $codigo_cpms = "99234";
          } else {
            $codigo_cpms = "99231.15";
          }
        }
      } else {
        if ($model_emergencia->id_prioridad == '1') {
          $codigo_cpms = "99285";
        } else if ($model_emergencia->id_prioridad == '2') {
          $codigo_cpms = "99284";
        } else if ($model_emergencia->id_prioridad == '3') {
          $codigo_cpms = "99282";
        } else {
          $codigo_cpms = "99281";
        }
      }

      $emergencia_model = new \App\Models\Emergencia;

      /**********Calcula estancia*************/
      $inicio = ($model_emergencia->fecha_registro_ingreso);
      $final = ($date);
      $inicios = strtotime($model_emergencia->fecha_registro_ingreso);
      $fecha_alta = explode(" ", $date);
      $hora_alta = explode(" ", $horaActual);
      $fecha_finals = $fecha_alta[0] . ' ' . $hora_alta[0];
      $finals = strtotime($fecha_finals);
      $resulta = ($finals - $inicios) / 86400;
      $resulta = round($resulta);

      if ($resulta == "0") {
        $dia_estancia = '1';
      } else {
        $dia_estancia = $resulta;
      }

      $update_alta = $emergencia_model->InsertaEstancia($request->id_emergencia, $emergencia_prestacion->id, $id_evaluacion, 2, $codigo_cpms, $model_emergencia->fecha_atencion, $id_user, $dia_estancia);

      $model_emergencia->origen_alta = 3;
      $model_emergencia->cpms_alta = $codigo_cpms;
      $model_emergencia->fecha_alta_medica = $date;
      $model_emergencia->hora_alta_medica = $horaActual;
      $model_emergencia->id_tipo_egres_med = $request->id_tipo_egreso;
      $model_emergencia->flag_egreso = '1';
      $model_emergencia->estado = '5';
      $model_emergencia->id_medico_egreso = $id_user;
      $p_diagnostico = "";
      $id_1 = 0;
      $id_2 = 0;
      $id_3 = 0;

      if (isset($request->id_diagnostico_egreso)):
        foreach ($request->id_diagnostico_egreso as $key => $value):
          if ($key == 0 && $request->id_diagnostico_egreso[$key] != "") {
            $id_1 = $request->id_diagnostico_egreso[$key];
          }
          if ($key == 1 && $request->id_diagnostico_egreso[$key] != "") {
            $id_2 = $request->id_diagnostico_egreso[$key];
          }
          if ($key == 2 && $request->id_diagnostico_egreso[$key] != "") {
            $id_3 = $request->id_diagnostico_egreso[$key];
          }
        endforeach;
      endif;

       $p_producto_alta = "";
      $p_producto_alta .= "{";
      if (isset($request->id_producto_alta)) :
        if ($request->id_producto_alta[0] != "ru='undefined'") :
          foreach ($request->id_producto_alta as $key => $value) :
            if ($request->id_producto_alta[$key] != "") {
              $p_producto_alta .= "{";
              $p_producto_alta .= $request->id_producto_alta[$key] . ",";
              $p_producto_alta .= $request->tiempo_periodo_alta[$key] . ",";
              $p_producto_alta .= $request->nro_med_solictados_alta[$key] . ",";
              $p_producto_alta .= (isset($request->nro_med_entregados_alta[$key]) && $request->nro_med_entregados_alta[$key] != "") ? $request->nro_med_entregados_alta[$key] : 0;
              $p_producto_alta .= ",";
              $p_producto_alta .= (isset($request->nro_stocks_almacen_alta[$key]) && $request->nro_stocks_almacen_alta[$key] != "") ? $request->nro_stocks_almacen_alta[$key] : 0;
              $p_producto_alta .= ",";
              $p_producto_alta .= (isset($request->nro_stocks_establecimiento_alta[$key]) && $request->nro_stocks_establecimiento_alta[$key] != "") ? $request->nro_stocks_establecimiento_alta[$key] : 0;
              $p_producto_alta .= ",";
              $p_producto_alta .= (isset($request->nro_stocks_alta[$key]) && $request->nro_stocks_alta[$key] != "") ? $request->nro_stocks_alta[$key] : 0;
              $p_producto_alta .= ",";
              $p_producto_alta .= (isset($request->posologia_alta[$key]) && $request->posologia_alta[$key] != "") ? str_replace(",", "###", $request->posologia_alta[$key]) : "-";
              $p_producto_alta .= ",";
              $p_producto_alta .= (isset($request->miSelectProd_alta[$key]) && $request->miSelectProd_alta[$key] != "") ? $request->miSelectProd_alta[$key] : 0;
              $p_producto_alta .= ",";
              $p_producto_alta .= $request->id_via_administracion_alta[$key];
              $p_producto_alta .= "},";
            }
          endforeach;
          if (strlen($p_producto_alta) > 1) $p_producto_alta = substr($p_producto_alta, 0, -1);
        endif;
      endif;
      $p_producto_alta .= "}";

      $p_diagnostico_alta = "";
      $p_diagnostico_alta .= "{";
      if (isset($request->id_diagnostico_egreso)) :
        foreach ($request->id_diagnostico_egreso as $key => $value) :
          if ($request->id_diagnostico_egreso[$key] != "") {
            $p_diagnostico_alta .= "{";
            $p_diagnostico_alta .= $request->id_diagnostico_egreso[$key] . ",";
            $p_diagnostico_alta .= "0";
            $p_diagnostico_alta .= "},";
          }
        endforeach;
        if (strlen($p_diagnostico_alta) > 1) $p_diagnostico_alta = substr($p_diagnostico_alta, 0, -1);
      endif;
      $p_diagnostico_alta .= "}";
     
      // Origen de la receta
      $receta_flag_origen = 2; // 2 = Alta Médica Evaluacion

      $id_receta_vale_alta = 0;
      if (isset($request->id_producto_alta)) :
        if ($request->id_producto_alta[0] != "ru='undefined'") :
          $id_receta_vale_alta = $receta->postRegistrarRecetaValeNuevoConDiagnosticoEvaluacionAltas(
            $id_receta_vale_alta,
            $request->id_farmacia_receta_egreso,
            $request->id_user,
            $id_consultorio,
            $nro_receta,
            $digitos_adicionales,
            $asegurado->nro_doc_ident,      
            $titular->cip,
            $asegurado->tipo_doc_ident,     
            $asegurado->nombre,            
            $asegurado->paterno,          
            $asegurado->materno,           
            $request->tipo_beneficiario,            
            $titular->nro_doc_ident,
            $titular->tipo_doc_ident,
            $titular->nombre,
            $titular->paterno,
            $titular->materno,
            $request->fecha_expedicion,
            $request->fecha_atencion,
            $request->nro_historia,               
            $asegurado->grado,             
            $request->id_medico,
            $id_anestesiologo,
            $id_tiporeceta,
            $turno,
            $obs_registro,
            $id_receta_vale_bus,
            $id_admision,
            $tipo_via,
            $sub_via,
            $final_via,
            $nro_guia,
            $interconsulta,
            $interconsulta_dias,
            $request->id_prestacion,
            $id_tipo_num,
            $estado_receta,
            $p_producto_alta,
            "{}",
            $asegurado->sexo,               
            $asegurado->fecha_nac,          
            $request->codigo_upss,          
            $id_evaluacion,
            $receta_flag_origen
        );               
        
          $model_rec_val_alta = new \App\Models\Receta_vale;
          $ok_alta = $model_rec_val_alta->ActualizaIdEvaluacion($id_receta_vale_alta, $id_evaluacion);
          if (!$ok_alta):
            throw new \Exception("Error al enlazar la receta de alta con la Evaluación.");
          endif;
        endif;
      endif;

      if (isset($request->id_diagnostico_egreso)) {
        foreach ($request->id_diagnostico_egreso as $key => $value) {
          if ($value == "") continue;

          $tipo_diag = (isset($request->id_tipo_diagnostico_egreso[$key]) && $request->id_tipo_diagnostico_egreso[$key] != "")
            ? $request->id_tipo_diagnostico_egreso[$key]
            : 2;

          $yaExiste = \App\Models\Receta_diagnostico::where('id_prestacion', $emergencia_prestacion->id)
            ->where('id_diagnostico', $value)
            ->where('id_clasificacion_diagnosticos', 3)
            ->where('estado', 1)
            ->exists();

          if (!$yaExiste) {
            $diag_egreso = new \App\Models\Receta_diagnostico();
            $diag_egreso->id_diagnostico                = $value;
            $diag_egreso->id_tipo_diagnostico           = $tipo_diag;
            $diag_egreso->id_clasificacion_diagnosticos = 3;
            $diag_egreso->id_prestacion                 = $emergencia_prestacion->id;
            $diag_egreso->id_receta_vale                = null;
            $diag_egreso->id_evaluacion                 = $id_evaluacion;
            $diag_egreso->id_consejeria                 = 0;
            $diag_egreso->id_interconsulta              = 0;
            $diag_egreso->estado                        = 1;
            $diag_egreso->created_at                    = Carbon::now();
            $diag_egreso->created_us                    = $request->id_user;
            if (!$diag_egreso->save()) {
              throw new \Exception("Error al registrar el diagnóstico de egreso.");
            }
          }
        }

      }

      $model_emergencia->id_diag_cab = $id_1;
      $model_emergencia->id_diag_cuer1 = $id_2;
      $model_emergencia->id_diag_cuer2 = $id_3;
      if ($request->id_tipo_egreso == '1') { //alta
        $model_emergencia->id_condi_egres_med = $request->id_condicion_alta;
        $model_emergencia->id_servicio_destino = "0";
        $model_emergencia->id_upss_alta = "0";
        $model_emergencia->obs_traslado = "0";
        $model_emergencia->detalle_alta_admin = "0";
        $model_emergencia->id_establecimiento_traslado = "0";
        $model_emergencia->necropsia = "0";
        $model_emergencia->causas_muerte = "0";
        $model_emergencia->condicion_traslado = "0";
        $model_emergencia->personal_acompañante = "0";
        $model_emergencia->especialida_destino = "0";
        $model_emergencia->especialidad_destino_otro = "0";
      } else if ($request->id_tipo_egreso == '2') { //hospitalizado
        $upss = "SELECT 
                concat(u.descripcion_upss,' / ',u.codigo ) as nombre,
                u.id as id_upss,
                u.codigo as codigo_upss,
                c.id as id_consultorio,
                c.nombre as nombre_consultorio
                from consultorios c 
                inner join upsses u on u.id_consultorio = c.id 
                where u.id_tipo_atencion = '3' and u.estado = '1' and u.id='$request->id_servicio_hospitalizacion'";
        $upss = DB::select($upss);
        $upss = $upss[0];

        $model_emergencia->id_condi_egres_med = "3";
        $model_emergencia->id_servicio_destino = $upss->id_consultorio; //servicio encargado
        $model_emergencia->id_upss_alta = $upss->id_upss; //upss encargado
        $model_emergencia->obs_traslado = "0";
        $model_emergencia->detalle_alta_admin = "0";
        $model_emergencia->obs_traslado = "0";
        $model_emergencia->necropsia = "0";
        $model_emergencia->causas_muerte = "0";
        $model_emergencia->condicion_traslado = "0";
        $model_emergencia->personal_acompañante = "0";
        $model_emergencia->especialida_destino = "0";
        $model_emergencia->especialidad_destino_otro = "0";

        // $model_emergencia->id_upss_origen = $request->id_ups_origen;
        // $model_emergencia->id_servicio_origen = $request->id_servicio_origen;
        // $model_emergencia->id_servicio_destino_ho = $request->id_servicio_destino;
        // $model_emergencia->id_pabellon_destino = $request->id_pabellon_destino;
        // $model_emergencia->id_cuarto_destino = $request->id_cama;

        // $model_emergencia->pabellon_ubicacion = $request->id_pabellon_ubicacion;
        // $model_emergencia->id_upss_ubicacion = $request->id_upss_ubicacion;
        $model_emergencia->id_edificio_ubicacion = $request->id_edificio_ubicacion;
        $model_emergencia->id_pabellon_ubicacion = $request->id_pabellon_ubicacion;
        // $model_emergencia->id_servicio_ubicacion = $request->id_servicio_ubicacion;
        // $model_emergencia->id_cama_ubicacion = $request->id_cama_ubicacion;
        // switch ($request->id_clase_cama_ubicacion) {
        //   case 1:
        //   case 6:
        //   case 7:
        //     $model_emergencia->personal_coordinacion = $request->personal_coordinacion;
        //     break;
        // }

      } else if ($request->id_tipo_egreso == '3') { //traslado
        $model_emergencia->id_servicio_destino = "0";
        $model_emergencia->id_upss_alta = "0";
        $model_emergencia->detalle_alta_admin = "0";
        $model_emergencia->id_condi_egres_med = "3";
        $model_emergencia->obs_traslado = $request->obs_traslado;
        $model_emergencia->id_establecimiento_traslado = $request->id_establecimiento_traslado;
        $model_emergencia->necropsia = "0";
        $model_emergencia->condicion_traslado = $request->condicion_traslado;
        $model_emergencia->personal_acompañante = $request->personal_acompañante;
        $model_emergencia->especialida_destino = $request->especialida_destino;
        $model_emergencia->especialidad_destino_otro = $request->especialidad_destino_otro;
        $model_emergencia->causas_muerte = "0";
      } else if ($request->id_tipo_egreso == '4') { //fallecido
        $model_emergencia->id_servicio_destino = "0";
        $model_emergencia->id_upss_alta = "0";
        $model_emergencia->obs_traslado = "0";
        $model_emergencia->obs_traslado = "0";
        $model_emergencia->detalle_alta_admin = "0";
        $model_emergencia->id_condi_egres_med = "6";
        $model_emergencia->necropsia = $request->necropsia;
        $model_emergencia->causas_muerte = $request->causas_muerte;
        //$model_emergencia->fecha_defuncion = $request->fecha_defuncion;
        //$model_emergencia->hora_defuncion = $request->hora_defuncion;
        $model_emergencia->condicion_traslado = "0";
        $model_emergencia->personal_acompañante = "0";
        $model_emergencia->especialida_destino = "0";
        $model_emergencia->especialidad_destino_otro = "0";
      } else if ($request->id_tipo_egreso == '7') { //retiro voluntario
        $model_emergencia->id_servicio_destino = "0";
        $model_emergencia->id_upss_alta = "0";
        $model_emergencia->obs_traslado = "0";
        $model_emergencia->obs_traslado = "0";
        $model_emergencia->id_condi_egres_med = "4";
        $model_emergencia->necropsia = "0";
        $model_emergencia->detalle_alta_admin = $request->obs_retiro;
        $model_emergencia->causas_muerte = "0";
        $model_emergencia->condicion_traslado = "0";
        $model_emergencia->personal_acompañante = "0";
        $model_emergencia->especialida_destino = "0";
        $model_emergencia->especialidad_destino_otro = "0";
      } else if ($request->id_tipo_egreso == '6') { //fuga
        $model_emergencia->id_servicio_destino = "0";
        $model_emergencia->id_upss_alta = "0";
        $model_emergencia->obs_traslado = "0";
        $model_emergencia->obs_traslado = "0";
        $model_emergencia->id_condi_egres_med = "5";
        $model_emergencia->necropsia = "0";
        $model_emergencia->detalle_alta_admin = $request->obs_fuga;
        $model_emergencia->causas_muerte = "0";
        $model_emergencia->condicion_traslado = "0";
        $model_emergencia->personal_acompañante = "0";
        $model_emergencia->especialida_destino = "0";
        $model_emergencia->especialidad_destino_otro = "0";
      } else {
        $model_emergencia->id_servicio_destino = "0";
        $model_emergencia->id_upss_alta = "0";
        $model_emergencia->obs_traslado = "0";
        $model_emergencia->obs_traslado = "0";
        $model_emergencia->necropsia = "0";
        $model_emergencia->detalle_alta_admin = "0";
        $model_emergencia->causas_muerte = "0";
        $model_emergencia->id_condi_egres_med = "0";
        $model_emergencia->id_condi_egres_med = "1";
        $model_emergencia->condicion_traslado = "0";
        $model_emergencia->personal_acompañante = "0";
        $model_emergencia->especialida_destino = "0";
        $model_emergencia->especialidad_destino_otro = "0";
      }

      if ($model_emergencia->id_tipo_beneficiario == '12') {
        $model_emergencia->numero_boleta = $request->numeroboleta;
        $model_emergencia->numero_documento_coronel_guardia  = $request->numeroguar;
      } else {
        $model_emergencia->numero_boleta = "0";
        $model_emergencia->numero_documento_coronel_guardia  = "0";
      }

      if (isset($model_emergencia->cama_estado)) {
        $model_cama = \App\Models\Cama::where('id', '=', $model_emergencia->cama_estado)->first();
        $model_cama->estado = 0; // 1 - No disponible (Estos datos son los que van a ir a produccion)
        $model_cama->situacion = '1'; // '5' - En Desinfeccion (Estos datos son los que van a ir a produccion)
        if (!$model_cama->save()):
          throw new \Exception("Error al desocupar la cama.");
        endif;
      }

      if (!$model_emergencia->save()):
        throw new \Exception("Error al actualizar los datos del Alta.");
      endif;

      //PARA NO SE PUEDA GENERAR DUPLICACACION
      if (!$existe_intervencion_cqx) { //PARA NO SE PUEDA GENERAR DUPLICACACION
        if ($request->id_tipo_egreso == '2') {
          $flag_quirurgico = 0;
          if (isset($request->requiere_intervencion_quirurgica) && intval($request->requiere_intervencion_quirurgica) == 1) {
            $flag_quirurgico = $request->requiere_intervencion_quirurgica;
            $id_cita_cqx=0;
            if (intval($request->es_programacion_quirurgica) === 1){
              $evaluacion = Evaluacion::find($id_evaluacion);
              $evaluacion->envio_intervencion_quirurgica=true;
              $evaluacion->save();
              $datos_prestacion = DB::table('prestaciones as p')
                ->join('sub_consultorios as sc', 'p.id_sub_consultorio', '=', 'sc.id')
                ->select(
                    'p.id',
                    'p.id_cita',
                    'p.id_asegurado',
                    'p.fecha_atencion',
                    'p.id_establecimiento',
                    'sc.id_consultorio'
                )
                ->where('p.id_ext', $model_emergencia->id)
                ->where('p.id_tipo_atencion', 2)
                ->where('p.id_estado_reg', 1)
                ->first();
              
              $model_hospitalizacion_orden = new \App\Models\HospitalizacionOrden();
              $model_hospitalizacion_orden->RegistrarOrdenDeHospitalizacionDesdeEmergencia($model_emergencia->id, $flag_quirurgico);
              $fecha_probable_pq = !empty($request->fecha_probable_pq)? Carbon::parse($request->fecha_probable_pq)->format('Y-m-d'): null;
              
              $cqxIntervencion = \App\Models\CqxIntervencion::create([
                "id_prestacion"=>$datos_prestacion->id,
                "id_asegurado"=>$datos_prestacion->id_asegurado,
                "fecha_solicitud"=>Carbon::now(),
                "id_establecimiento"=>!empty($datos_prestacion->id_establecimiento)?$datos_prestacion->id_establecimiento:null,
                "id_consultorio"=>!empty($datos_prestacion->id_consultorio)?$datos_prestacion->id_consultorio:null,
                "flag" => 2,
                "id_estado_reg"=>1,
                "created_us" => Auth::user()->id,
                "created_at" => Carbon::now(),
                "emergencia_tipo_cirugia" => !empty($request->id_tipo_cirugia)?$request->id_tipo_cirugia:null,
                "emergencia_duracion_cirugia" => !empty($request->duracion_cirugia)?$request->duracion_cirugia:null,
                "hora_inicio_prog_qx" => !empty($request->hora_inicio_intervencion)?$request->hora_inicio_intervencion:null,
                "hora_fin_prog_qx" => !empty($request->hora_fin_intervencion)?$request->hora_fin_intervencion:null,
                "id_medico_reg_programacion" =>$request->id_medico,
                "fecha_probable" => $fecha_probable_pq,
                "es_desde_evaluacion_programacion"=>true,
                "id_evolucion" => $id_evaluacion
                ]);
              $cqx_id_intervencion = $cqxIntervencion->id;
                      
              $cqxcita=\App\Models\CqxCita::create([
                "id_cqx_intervenciones" => $cqx_id_intervencion,
                "id_consultorio" => !empty($datos_prestacion->id_consultorio)?$datos_prestacion->id_consultorio:null,
                "id_prestacion" => !empty($datos_prestacion->id)?$datos_prestacion->id:null,
                "fecha_atencion" => Carbon::now(),
                "fecha_probable" => $fecha_probable_pq,
                "is_programacion_quirurgica" => true,
                "id_medico_reg_programacion" => $request->id_medico,
                "id_cita" => $datos_prestacion->id_cita,
                "tipo" => 2,
                "flag" => 3,
                "id_estado_reg" => 1,
                "created_us" => Auth::user()->id,
                "created_at" => Carbon::now()
              ]);
              $id_cita_cqx=$cqxcita->id;
            }

            //Registro de diagnosticos cqx
            if (isset($request->id_diagnostico_quirurgico)):
              $dignostico_cqx = new \App\Models\Receta_diagnostico();
              foreach ($request->id_diagnostico_quirurgico as $key => $value):
                if ($request->id_diagnostico_quirurgico[$key] != "") {
                  $dignostico_cqx->id_diagnostico = $request->id_diagnostico_quirurgico[$key];
                  $dignostico_cqx->id_tipo_diagnostico = $request->id_tipo_diagnostico_quirurgico[$key];
                  $dignostico_cqx->estado = 1;
                  $dignostico_cqx->id_prestacion = $request->id_prestacion;
                  $dignostico_cqx->created_at = Carbon::now();
                  $dignostico_cqx->created_us = $request->id_user;
                  $dignostico_cqx->id_intervencion = $cqx_id_intervencion;
                  $dignostico_cqx->save();
                }
              endforeach;
            endif;

            // Registro de Medicos Cirujanos
            if (isset($request->id_medico_cirujano)):
              $model_cq_profesional = new \App\Models\CqxProfesional();
              foreach ($request->id_medico_cirujano as $key => $value):
                if ($request->id_medico_cirujano[$key] != "") {
                  $p_id_medico = $request->id_medico_cirujano[$key];
                  $p_id_medico_cargo = (isset($request->id_medico_cirujano_cargo[$key]) && $request->id_medico_cirujano_cargo[$key] > 0 ? $request->id_medico_cirujano_cargo[$key] : 0);
                  $flag_personal = 0; // Cirujanos
                  $response = $model_cq_profesional->RegistrarProfesionalesPreQx($cqx_id_intervencion, $p_id_medico, $p_id_medico_cargo, $request->id_user, $flag_personal, $id_cita_cqx);
                }
              endforeach;
            endif;

            // Registro de Procedimientos de Intervencion quirurgica
            if (isset($request->id_procedimiento_intervencion)) :
              $model_cq_procedimiento = new \App\Models\CqxProcedimiento();
              foreach ($request->id_procedimiento_intervencion as $key => $value) :
                if ($request->id_procedimiento_intervencion[$key] != "") {
                  $p_id_procedimiento = $request->id_procedimiento_intervencion[$key];
                  $model_cq_procedimiento->RegistrarProcedimientoPreQx($cqx_id_intervencion, $p_id_procedimiento, Auth::user()->id, $id_cita_cqx, 1, false);
                }
              endforeach;
            endif;
          } else {
            $model_hospitalizacion_orden = new \App\Models\HospitalizacionOrden();
            $response = $model_hospitalizacion_orden->RegistrarOrdenDeHospitalizacionDesdeEmergencia($request->id_emergencia, $flag_quirurgico);
          }
          
        }
      }

      /*$celular = Auth::user()->celular;
      if ($celular == '1') {
        $titular = Asegurado::where('id', '=', $model_emergencia->id_asegurado)->first();
        $titular->nro_doc_ident = $dni_prueba_jhon;
        $titular->save();
      }*/

      $model_trama = new \App\Models\Emergencia;
      // $model_evaluacion = new \App\Models\Evaluacione;
      $enviado_dato = $model_trama->enviartrama($request->id_emergencia);

      // Session::flash('flash_message', 'Alta Medica exitosa');
    endif;

    DB::commit();
    $registro_validado = true;

    /*if ($request->id_prestacion == 0) {
      Session::flash('flash_message', 'No se pudo registrar la Evaluación Médica, hubo un problema con la conexión de internet de su establecimiento PNP, vuelva a intentarlo nuevamente');
      return redirect('/all_pacientes_evaluaciones_by_emergencia');
    } else {
      Session::flash('flash_message', 'Evaluación Médica exitosa');
      return redirect('/ver_evaluacion/' . $request->id_user . '/' . $id_evaluacion);
    }*/

    } catch (\Exception $e) {
      DB::rollback();
      $mensaje_error = $e->getMessage();
      Log::error("Registro Evaluacion Emergencia => id_emergencia: " . $request->id_emergencia . 
          "\n\nmensaje: " . $e->getMessage() . 
          "\n\ndatos del request: " . json_encode($request->all()) . 
          "\n\nstack trace: " . $e->getTraceAsString()
      );
    } catch (\Throwable $th) {
      DB::rollback();
      $mensaje_error = $th->getMessage();
      Log::critical("Registro Evaluacion Emergencia => id_emergencia: " . $request->id_emergencia . 
          "\n\nmensaje: " . $th->getMessage() . 
          "\n\ndatos del request: " . json_encode($request->all()) . 
          "\n\nstack trace: " . $th->getTraceAsString()
      );
    }
    if ($registro_validado):
        Session::flash('flash_message', 'Evaluación Médica exitosa');
        return redirect('/ver_evaluacion/' . $request->id_user . '/' . $id_evaluacion);
    else:
        Session::flash('flash_warning', '<strong>Error:</strong> No se pudo registrar la Evaluación Médica, vuelva a intentarlo nuevamente.<br><br>' . $mensaje_error);
        return redirect('/all_pacientes_evaluaciones_by_emergencia');
    endif;
  }

  public function anular_evaluacion($id_evaluacion)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $evaluacion = Evaluacione::where('id', $id_evaluacion)->first();
    $evaluacion->id_estado_reg = 0;
    $evaluacion->deleted_us = Auth::user()->id;
    $evaluacion->deleted_at = Carbon::now();
    $evaluacion->save();

    $recetas = Receta_vale::where('id_evaluacion', '=', $id_evaluacion)->where('estado', '<>', 0)->get();
    foreach ($recetas as $rv) {
      $rv->estado = 0;
      $rv->save();
    }

    $laboratorio = Laboratorio::where('id_evaluacion', $id_evaluacion)->first();
    if(count($laboratorio)>0){
      $laboratorio->estado = 0;
      $laboratorio->save();
    }
    
    
    $radiologia = Radiologia::where('id_evaluacion', $id_evaluacion)->first();
    if(count($radiologia)>0){
      $radiologia->estado = 0;
      $radiologia->save();
    }
    $array["sw"] = true;
    echo json_encode($array);
  }

  public function validacion_beneficiario_dias_por_examen_rua_emergencia_evaluaciones($nrodoctitular, $id_examen_lab)
  {
      if (!Auth::check()) {
          return redirect('/auth/login');
      }

      $model_evaluaciones = new Evaluacione();
      $examenes_evaluaciones = $model_evaluaciones->validacionBeneficiarioDiasPorExamenRua_emergencia_evaluaciones($nrodoctitular, $id_examen_lab);
      //dd($examenes_evaluaciones);
      return $examenes_evaluaciones;
  }

  public function validar_dato_siteds_evaluacion($codigo_siteds){

      $modelEvaluacion = new Evaluacione();
      $data = $modelEvaluacion->validar_Siteds_Emergencia($codigo_siteds);   
      return json_encode($data);
    }

  public function updateCodeSiteds(Request $request, $id) {
    // TODO: agregar las columnas updated_at_cod_siteds, updated_us_cod_siteds en emergencias
    $evaluacion = Evaluacione::find($id);
    if (!$evaluacion ) {
      return response()->json(['message' => 'No existe registro de la emergencia'], 404);
    }
    Log::info(["cod_siteds_susalud" => $request->input('cod_siteds_susalud')]);
    $evaluacion->cod_siteds_susalud = $request->input('cod_siteds_susalud');
    $evaluacion->save();
    return response()->json([
        'emergencia_id' => $id,
        'message' => 'Código SITEDS actualizado correctamente'
    ], 200);
  }
  public function listar_evaluacion_by_prestacion_ajax(Request $request){
    
    $model_evaluacion = new Evaluacione();
    $p[]=$request->fecha_ini;
    $p[]=$request->fecha_fin;
    $p[]=$request->id_paciente;
    $p[]=$request->estado;
    $p[]=$request->id_prestacion;
    $p[]=$request->dni_beneficiario;
    $p[]=$request->NumeroPagina;
    $p[]=$request->NumeroRegistros;
    $data = $model_evaluacion->listar_evaluacion_by_prestacion_ajax($p);
    $iTotalDisplayRecords = isset($data[0]->totalrows)?$data[0]->totalrows:0;
    $result["PageStart"] = $request->NumeroPagina;
    $result["pageSize"] = $request->NumeroRegistros;
    $result["SearchText"] = "";
    $result["ShowChildren"] = true;
    $result["iTotalRecords"] = $iTotalDisplayRecords;
    $result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
    $result["aaData"] = $data;
    echo json_encode($result);
    
  }

  public function edit_evaluacion($id_user, $id_evaluacion)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $modelfarma = new \App\Models\Farmacia;
    $model_receta = new \App\Models\Receta_vale;
    $model_producto = new \App\Models\Producto;
    $model_productos_receta = new \App\Models\Producto_receta;
    $model_establecimiento = new \App\Models\Establecimiento;
    $model_prestacion = new \App\Models\Prestaciones;
    
    
    
    $model_referencia = new \App\Models\Referencia;
    $model_emergencias = new  \App\Models\Emergencia;
    $model_triaje = new  \App\Models\Triaje;
    $model_upss = new \App\Models\Upss;
    $model_kardex = new Kardexe();
    $model_medicos = new Medico;
    $model_producto = new Producto;
    $modelcama= new \App\Models\Cama();

    $evaluacion = Evaluacione::find($id_evaluacion);
    $medico = $model_medicos->GetMedicoById($evaluacion->id_medico);

    // Validacion de personal de salud logueado con personal de salud que registro la evolucion
    $medico_logueado = $model_medicos->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni, Auth::user()->id_establecimiento);

    if (!empty($medico_logueado)):
      if (intval($medico->id) !== intval($medico_logueado->id)):
        return back()->withInput()->withErrors(array("No puede modificar la Evaluación seleccionada, porque no es el Profesional que lo registró."));
      endif;
    else:
        return back()->withInput()->withErrors(array("No está registrado como Profesional de Salud en este Establecimiento médico."));
    endif;
    // Fin Validacion de personal de salud logueado con personal de salud que registro la evolucion
    
    //verifico si existe el id en emergencia
    // $evaluacion = Evaluacione::find($id_evaluacion);
    $id_emergencia = $evaluacion->id_emergencia;

    $emergencia = Emergencia::find($id_emergencia);
    
    if(is_object($emergencia)):

      $primera_atencion = Prestaciones::where('id_ext', $id_emergencia)->where('id_estado_reg', 1)->orderBy('id', 'DESC')->first();
      //verifico si existe prestacion
      if(is_object($primera_atencion)):

        $is_evaluacion_ultima = false;
        $evaluacion_ultima = Evaluacione::where('id_emergencia', $id_emergencia)
                                  ->where('id_tipo_atencion', 2)
                                  ->where('id_estado_reg', 1)
                                  ->orderBy('fecha_atencion', 'desc')
                                  ->orderBy('id', 'desc')
                                  ->first();
        
        $is_evaluacion_ultima = $evaluacion_ultima->id == $id_evaluacion;
        
        $id_prestacion =  $primera_atencion->id;
        
      
        $farmacia = $modelfarma->GetFarmaByUser(Auth::user()->id);
        $triaje = $model_emergencias->GetCargarEmergencia($id_emergencia, 3);
        
        $fechaServidor = $model_kardex->getFechaServidor();
        $date = Carbon::now();
        $fecha_actual = $date->format('d-m-Y');
        $hora = $date->format('H:i');
        
        $id_ipress = $farmacia->id_establecimiento;
        $establecimiento = $model_establecimiento->GetById($id_ipress);
        if ($id_ipress == 1) {
          $nivel = 2;
          $id_farmacia = Auth::user()->id_farmacia;
        } else {
          if($id_ipress==76){
            $id_farmacia = 19;
          }
          else{
            $id_farmacia = Auth::user()->id_farmacia;
          }
          $nivel = $establecimiento[0]->nivel;
        }
        
        $farmacias = $modelfarma->GetFarmaciaById($id_farmacia);
        $establecimientos = $model_establecimiento->ShowAllEstablecimientos();
        $estado=3;
        $id_tipo_atencion=2;
        $datos_evaluacion = $model_emergencias->GetBuscaEmergenciaEvaluacion($id_emergencia, $medico->id, 3);
        
        //ultimo sited
        // $codigo_siteds = $model_emergencias->getSitedsValidForEvaluacion($id_emergencia);
        $codigo_siteds = $evaluacion->cod_siteds_susalud;
        
        //ultima atencion evaluacion
        //$ultima_atencion_evaluacion = DB::table('evaluaciones')
        //      ->where('id_emergencia', $id_emergencia)
        //      ->where('id_prestacion', $id_prestacion)
        //      ->orderby('id', 'desc')
        //      ->first();
        
        //if(count($ultima_atencion_evaluacion)>0):
        //  $buscar_ultima_atencion = DB::table('evaluaciones')
        //      ->where('id_emergencia', $id_emergencia)
        //      ->orderby('id', 'desc')
        //      ->first();
        //endif;
        $id_principal = $id_emergencia;
        $model_consultorio = new Consultorio;
        $establecimiento_servicios = $model_consultorio->GetServiciosPorEstablecimiento($id_ipress);
        $producto = $model_producto->getProductosByNivelAndIdServicio($nivel, $id_ipress, $id_farmacia);
        $opcionesprioridad = $model_emergencias->listarPrioridades();
        $topicosprioridad = $model_triaje->ShowTriaje($evaluacion->ubica_prioridad);
        
        $flag = 0 ;
        $id_tipo_atencione=2;
        
        $codigo_triaje = $model_triaje->getConsultorioPorIdTriaje_TODO($datos_evaluacion->id_triaje);
        if ($codigo_triaje[0]->prioridad !== 4) {
          if ($codigo_triaje[0]->id_ups == '' && $datos_evaluacion->id_triaje==12) {
            $codigo_upss = '230600';
            $descripcion_upss ='UNIDAD DE VIGILANCIA INTENSIVA (UVI)';
          }else{
            $r_upss = $model_upss->upss_by_id($codigo_triaje[0]->id_ups,2);
            $codigo_upss = $r_upss->codigo == '' ? 'S/N' : $r_upss->codigo;
            $descripcion_upss = $r_upss->descripcion_upss == '' ? '-' : $r_upss->descripcion_upss;
          }
        } else {
          $r_upss = '230200';
          $codigo_upss = $r_upss;
        }

        // $model_cama = new \App\Models\Cama;
        $model_emergencia = new \App\Models\Emergencia;
        $obtenerdato = $model_emergencia->GetObtenerServicio($id_emergencia);
        
        $dato_cama = $model_emergencia->GetObtenerCama($obtenerdato->cama);
        
        $totalcamas = null;
        if ($is_evaluacion_ultima):
          $model_cama = new \App\Models\Cama();
          $totalcamas = $model_cama->GetCamaActualyVacias($obtenerdato->cama,'EMERGENCIA',$id_ipress);
        else:
          $evaluacion_cama_data = $model_emergencia->GetObtenerCama($evaluacion->cama_estado);
          $evaluacion->cuarto = $evaluacion_cama_data->cuarto;
          $evaluacion->cama = $evaluacion_cama_data->cama;
        endif;
        
        $id_cama_actual = $obtenerdato->cama;

        /***************************************************** */

        // $arraydiagnosticos =[];
      
        $model_procedimiento_metodos = new \App\Models\Procedimiento;
      
        $listaDiagnosticos = DB::table('receta_diagnosticos')
                  ->select('id_diagnostico')
                  ->where('id_prestacion', '=', $id_prestacion)
                  ->where('id_interconsulta', '=',0)
                  ->where('estado', '=',1)
                  ->get();

        $diagnostico_evaluacion = DB::table('receta_diagnosticos as rd')
          ->join('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
          ->where('rd.id_evaluacion', $id_evaluacion)
          ->where('rd.id_interconsulta', 0)
          ->where('rd.estado', 1)
          ->select('rd.id', 
              'rd.id_diagnostico', 
              DB::raw('coalesce(rd.id_tipo_diagnostico, 0) as id_tipo_diagnostico'), 
              'rd.id_categoria', 
              'rd.id_clasificacion_diagnosticos', 
              'd.codigo', 
              'd.nombre'
          )
          ->orderBy('rd.id', 'asc')
          ->get();

        $diagnostico_evaluacion_distinct = [];
        foreach ($diagnostico_evaluacion as $key => $de) {
            $clave = $de->id_diagnostico;
            // si esa clave aún no existe, lo guardamos
            if (!isset($diagnostico_evaluacion_distinct[$clave])) {
              $diagnostico_evaluacion_distinct[$clave] = $de;
            }
        }

        $diagnostico_evaluacion_distinct = array_values($diagnostico_evaluacion_distinct);
      
        // if(count($listaDiagnosticos)>0){
        //   for($i=0;$i<count($listaDiagnosticos);$i++){
        //     $idactual = $listaDiagnosticos[$i]->id_diagnostico;
        //     $diagnostico = DB::table('diagnosticos')
        //     ->select('id','codigo','nombre')
        //     ->where('id', $idactual)
        //     ->first();
        //     $arraydiagnosticos[]=$diagnostico;
        //   } 
        // }
        $model_prestacion = new Prestaciones();
        // $arraydeprocedimientos = $model_prestaciones->GetDiagnosticoByIdPrestacion($id_prestacion);
        $procedimiento_evaluacion = $model_prestacion->GetProcedimientosByIdPrestacionydiagEvaluacion($id_prestacion, 1, $id_evaluacion);

        // Edit
        // $procedimiento_evaluacion = $model_prestacion->GetProcedimientosByIdPrestacionYIdEvolucionYTipo($id_prestacion, $id_evaluacion, 1);dd($id_prestacion, $id_evaluacion, $procedimiento_evaluacion);

        $flag_no_encontrado = 0;
        $model_asegurado= new Asegurado();
        if($datos_evaluacion->id_asegurado_titular!=null || $datos_evaluacion->id_asegurado_titular>0):
          $dato_titular = $model_asegurado->Busca_datos_titular($datos_evaluacion->id_asegurado_titular);
        else:
          $flag_no_encontrado = 1;
        endif;

          //dd($arraydeprocedimientos);
        /******************************************************** */
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

          if($emergencia->id_referencia != 0){
            $model_referencia = new \App\Models\Referencia;
            $referencias = $model_referencia->GetBuscaReferenciaById($emergencia->id_referencia);
            $model_prestacionreferncia = new \App\Models\Prestaciones;
            $diagnostico_prestacion_referencia = $model_prestacionreferncia->GetDiagnosticoByIdPrestacion($referencias->id_prestacion);
            $medico_referencia = DB::table('medicos')
            ->select('medicos.*')
            ->where('id', $referencias->id_medico_referencia)
            ->first();
            $nombre_medico_referencia = $medico_referencia->paterno. ' '. $medico_referencia->materno. ','.$medico_referencia->nombre;
            $procedimientos_refencia = $model_referencia->buscar_procedimiento_referencia($emergencia->id_referencia);
            $servicios_ipress_origen = $model_referencia->busca_servicio_por_ipres_origen_referencia($referencias->id_establecimiento);
          
          }else{
            $diagnostico_prestacion_referencia = "";
            $referencias = "";
            $procedimientos_refencia="";
            $servicios_ipress_origen="";
            $nombre_medico_referencia="";
          }

          $model_interconsulta = new \App\Models\Interconsulta();
          $interconsultas = $model_interconsulta->GetInterconsultaByIdEvaluacion2($id_prestacion, $id_evaluacion);
          $has_interconsulta_registrada = $interconsultas && count($interconsultas) > 0;

          $recetas = $model_receta->GetDatosRecetasByIdEvaluacion($id_evaluacion);
          $has_receta_registrada = $recetas && count($recetas) > 0;
          $farmacia_id_receta_primera = $recetas && count($recetas) > 0 ? $recetas[0]->id_farmacia : 0;

          $model_laboratorio = new \App\Models\Laboratorio();
          $laboratorio_ordenes = $model_laboratorio->GetLaboratorioByIdPrestacionEvaluacion($id_prestacion, $id_evaluacion);
          $has_laboratorio_orden_registrada = $laboratorio_ordenes && count($laboratorio_ordenes) > 0;

          $model_radiologia = new \App\Models\Radiologia;
          $radiologia_ordenes = $model_radiologia->GetRadiologiaByIdPrestacionEvaluacion($id_prestacion, $id_evaluacion);
          $has_radiologia_orden_registrada = $radiologia_ordenes && count($radiologia_ordenes) > 0;

             // Verificar si el usuario es medico de profesion
      
      $is_profesion_medico = false;
      if (count($medico) > 0) {
        switch ($medico->id_profesion) {
          case 20: case 4: case 51: case 52: case 1: $is_profesion_medico = true; break;
        }
        if (!$is_profesion_medico && (intval($nivel) == 1 || intval($nivel) == 2) && in_array(intval($medico->id_profesion), [12, 2, 5])) {
          $is_profesion_medico = true;
        }
      }

        $eventosMarcados = Prestacion_eventos::where('id_prestacion', $id_prestacion)
        ->where('estado', 1)
        ->lists('id_evento')
        ->toArray();
        $gestacion = Gestacion::where("id", $emergencia->id_gestacion)->first();
        $puerperio = Puerperio::where("id", $emergencia->id_puerperio)->first();
        $cita = $model_emergencias->GetBuscaEmergencia($id_emergencia, $medico->id, $emergencia->estado);
        $tipoPartosSql = "SELECT * FROM tipo_partos";
        $tipoPartos = DB::select($tipoPartosSql);
        $tipoDocsNatiSql = "SELECT * FROM tipo_doc_nati";
        $tipoDocsNati = DB::select($tipoDocsNatiSql);
        return view('frontend.evaluaciones.edit_evaluacion', compact( 'arraydeprocedimientos','id_prestacion','eventosMarcados','tipoPartos','tipoDocsNati','cita','gestacion','puerperio','emergencia','evaluacion','diagnostico_prestacion_referencia','referencias','procedimientos_refencia','servicios_ipress_origen','nombre_medico_referencia', 'fechaServidor', 'nivel', 'farmacias','establecimientos','triaje','producto','id_principal','estado','datos_evaluacion','id_tipo_atencion','establecimiento_servicios','id_farmacia','opcionesprioridad','topicosprioridad','totalcamas','flag','id_tipo_atencione','codigo_upss','id_emergencia','dato_cama','id_cama_actual','hora','dato_titular','turno','flag_no_encontrado','codigo_siteds','is_profesion_medico','diagnostico_evaluacion','procedimiento_evaluacion','id_evaluacion', 'is_evaluacion_ultima', 'recetas', 'has_receta_registrada', 'farmacia_id_receta_primera', 'diagnostico_evaluacion_distinct', 'interconsultas', 'has_interconsulta_registrada', 'laboratorio_ordenes', 'has_laboratorio_orden_registrada', 'radiologia_ordenes', 'has_radiologia_orden_registrada'));

      else:
        return redirect('/formulario_atencion_emergencia/'.$id_emergencia)->withInput()->withErrors(array('Primero debe ingresar una atencion, para poder registrar su evaluacion del paciente  ..!'));
      endif;
    else:
      return redirect('/all_pacientes_evaluaciones_by_emergencia/')->withInput()->withErrors(array('Elemento no encontrado ..!'));
    endif; 
  }

  public function emergencia_evaluacion_store(Request $request)
  {
    if (!Auth::check()) :
        return redirect('/auth/login');
    endif;
    
    $registro_validado = false;
    $mensaje_error = "";
    
    DB::beginTransaction();
    try {

      $search = array('+', ',','"','/', '{', '}');
      $replace = array('\+', '\,', '\"', '\/', '\{', '\}');

      $id_prestacion = $request->id_prestacion;
      $id_evaluacion = $request->id_evaluacion;

      $date = Carbon::now();
      $hora = $date->toTimeString(); 

      $turno = "";
      if ($hora > '00:00' && $hora < '07:31'):
        $turno = 'MD';
      else:
        if ($hora > '07:30' && $hora < '13:31'):
          $turno = 'M';
        else:
          if ($hora > '13:30' && $hora < '19:31'):
            $turno = 'T';
          else:
            if ($hora > '19:30' && $hora <= '23:59'):
              $turno = 'N';
            endif;
          endif;
        endif;
      endif;

      // Datos de la Evaluacion
      $evaluacion = Evaluacione::find($id_evaluacion);
      $evaluacion->examen_fisico = $request->examen_fisico;
      $evaluacion->motivo_visita = $request->motivo_visita;
      $evaluacion->tiempo_enfermedad = $request->tiempo_enfermedad;
      $evaluacion->examen_clinico = $request->examen_clinico;
      $evaluacion->examen_auxiliar = $request->examen_auxiliar;
      $evaluacion->tratamiento_auxiliar = $request->tratamiento_auxiliar;
      $evaluacion->plan_trabajo = $request->plan_trabajo;
      $evaluacion->antecedente = $request->antecedente;
      $evaluacion->antecedente_familiar = $request->antecedente_familiar;
      $evaluacion->updated_us = Auth::user()->id;

      if ($request->signos_vitales_evaluacion_emergencia == "true"):
        $temperatura = $request->temperatura != '' ? $request->temperatura : 0;
        $sa02_porcentaje = $request->saturacion != '' ? $request->saturacion : 0;
        $presion_arterial_x = $request->presion_arterial_x != '' ? $request->presion_arterial_x : 0;
        $presion_arterial_y = $request->presion_arterial_y != '' ? $request->presion_arterial_y : 0;
        $talla_cm = $request->talla != '' ? $request->talla : 0;
        $tamano_cintura_cm = $request->tamanio_cintura != '' ? $request->tamanio_cintura : 0;
        $frecuencia_respiratoria = $request->frecuencia_respiratoria != '' ? $request->frecuencia_respiratoria: 0;
        $peso_kg = $request->peso != '' ? $request->peso: 0;
        $frecuencia_cardiaca_fetal = $request->frec_cardiaca_fetal != '' ? $request->frec_cardiaca_fetal : 0;
        $frecuencia_cardiaca = $request->frec_cardiaca != '' ? $request->frec_cardiaca : 0;
        $fi02 = $request->oxigenacion != '' ? $request->oxigenacion : 0;

        $evaluacion->temperatura = $temperatura;
        $evaluacion->sa02_porcentaje = $sa02_porcentaje;
        $evaluacion->presion_arterial_x = $presion_arterial_x;
        $evaluacion->presion_arterial_y = $presion_arterial_y;
        $evaluacion->talla_cm = $talla_cm;
        $evaluacion->tamano_cintura_cm = $tamano_cintura_cm;
        $evaluacion->frecuencia_respiratoria = $frecuencia_respiratoria;
        $evaluacion->peso_kg = $peso_kg;
        $evaluacion->frecuencia_cardiaca_fetal = $frecuencia_cardiaca_fetal;
        $evaluacion->frecuencia_cardiaca = $frecuencia_cardiaca;
        $evaluacion->fi02 = $fi02;
      endif;

      if (!$evaluacion->save()):
        throw new \Exception("Error al intentar actualizar los datos de la evolución.");
      endif;

      //$evaluacion->fecha_atencion, id_medico ,created_us, 
      // $evaluacion->temperatura , 
      // $evaluacion->sa02_porcentaje , 
      // $evaluacion->presion_arterial_x ,
      // $evaluacion->presion_arterial_y , 
      // $evaluacion->talla_cm , 
      // $evaluacion->tamano_cintura_cm , 
      // $evaluacion->frecuencia_respiratoria , 
      // $evaluacion->peso_kg, 
      // $evaluacion->frecuencia_cardiaca_fetal , 
      // $evaluacion->frecuencia_cardiaca , 
      // $evaluacion->fi02, 
      // $evaluacion->ubica_prioridad,
      // $evaluacion->ubica_topico,
      // $evaluacion->fecha_inicio_estado,
      // $evaluacion->hora_estado,
      // $evaluacion->cama_estado
      

      $list_diagnostico_ya_registrado = DB::table('receta_diagnosticos as rd')
          ->join('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
          ->where('rd.id_evaluacion', $id_evaluacion)
          ->where('rd.id_interconsulta', 0)
          ->where('rd.id_consejeria', 0)
          ->where('rd.estado', 1)
          ->select('rd.id', 
              'rd.id_diagnostico', 
              DB::raw('coalesce(rd.id_tipo_diagnostico, 0) as id_tipo_diagnostico'), 
              'rd.id_categoria', 
              'rd.id_clasificacion_diagnosticos', 
              'd.codigo', 
              'd.nombre'
          )
          ->orderBy('rd.id', 'asc')
          ->get();
      
      if (!empty($request->id_diagnostico)) {
          foreach ($request->id_diagnostico as $indice => $id_cie) {

              $id_receta_diagnostico = intval($request->id_receta_diagnostico[$indice]);
              $id_tipo_diagnostico = intval($request->id_tipo_diagnostico[$indice]);

              if ($id_receta_diagnostico > 0) {
                  // Modificacion de diagnosticos ya registrados
                  $upd_receta_diagnostico = \App\Models\Receta_diagnostico::find($id_receta_diagnostico);
                  if ($upd_receta_diagnostico->id_tipo_diagnostico != $id_tipo_diagnostico):
                    $upd_receta_diagnostico->id_tipo_diagnostico = $id_tipo_diagnostico;
                    $upd_receta_diagnostico->updated_us = Auth::user()->id;
                    $upd_receta_diagnostico->updated_at = Carbon::now();
                    if (!$upd_receta_diagnostico->save()):
                      throw new \Exception("Error al intentar actualizar los datos de un diagnóstico (" . $id_cie . "). ");
                    endif;
                  endif;

                  // Quitar diagnosticos que permanecen
                  foreach ($list_diagnostico_ya_registrado as $indice2 => $obj_dx) {
                      if (intval($obj_dx->id) == $id_receta_diagnostico) {
                          unset($list_diagnostico_ya_registrado[$indice2]);
                          break;
                      }
                  }

              } else {
                  // Nuevos diagnosticos
                  $receta_diagnostico = new \App\Models\Receta_diagnostico();
                  $receta_diagnostico->id_receta_vale = null;
                  $receta_diagnostico->id_diagnostico = $id_cie;
                  $receta_diagnostico->id_tipo_diagnostico = $id_tipo_diagnostico;
                  $receta_diagnostico->estado = 1;
                  $receta_diagnostico->id_prestacion = $request->id_prestacion;
                  $receta_diagnostico->created_at = Carbon::now();
                  $receta_diagnostico->created_us = $request->id_user;
                  $receta_diagnostico->id_evaluacion = $request->id_evaluacion;
                  if (!$receta_diagnostico->save()):
                    throw new \Exception("Error al intentar registrar nuevo diagnóstico (" . $id_cie . "). ");
                  endif;
              }
          }

          // Verificar diagnosticos no considerados
          if (!empty($list_diagnostico_ya_registrado)) {
              foreach ($list_diagnostico_ya_registrado as $obj_dx) {

                  // Anular diagnosticos
                  $upd_receta_diagnostico = \App\Models\Receta_diagnostico::find($obj_dx->id);
                  $upd_receta_diagnostico->estado = 0;
                  $upd_receta_diagnostico->deleted_us = Auth::user()->id;
                  $upd_receta_diagnostico->deleted_at = Carbon::now();
                  if (!$upd_receta_diagnostico->save()):
                    throw new \Exception("Error al intentar anular un diagnóstico (" . $upd_receta_diagnostico->id_diagnostico . "). ");
                  endif;
              }
          }
      }
      // Fin Actualizacion de diagnosticos

      // Actualizacion de procedimientos
      $id_diagnostico_del_procedimiento = 0;

      $model_prestacion = new Prestaciones();
      $list_procedimiento_ya_registrado = $model_prestacion->GetProcedimientosByIdPrestacionydiagEvaluacion($id_prestacion, 1, $id_evaluacion);

      // "select p.id cod, p.codigo, p.descripcion nombre, 1 cantidad, d.id id_diagnostico
      // from procedimientos_diagnoticos pd 
      // inner join procedimientos p on p.id = pd.id_procedimientos
      // inner join diagnosticos d on d.id = pd.id_diagnosticos
      // where pd.id_prestacion = " . $id_prestacion . " AND pd.id_evaluacion = " . $id_evaluacion . "
      // AND pd.estado = 1 AND pd.id_interconsulta = 0 
      // order by p.id asc
      // ";
      // $list_procedimiento_ya_registrado = $model_prestacion->GetProcedimientosByIdPrestacionYIdEvolucionYTipo($request->id_prestacion, $request->id_evaluacion, 1);
      
      if (!empty($request->id_procedimiento)) {
          foreach ($request->id_procedimiento as $indice => $id_cpms) {

            $procedimiento_existe = false;
            $id_diagnostico_del_procedimiento = intval($request->miSelect[$indice]);
            
            // foreach ($request->id_procedimiento_backup as $indice_backup => $id_cpms_backup):
            foreach ($list_procedimiento_ya_registrado as $indice_backup => $obj_proc):

              $id_procedimiento_backup = $obj_proc->cod;
              $id_diagnostico_backup = $obj_proc->id_diagnostico;

              if (intval($id_procedimiento_backup) == intval($id_cpms)):

                $procedimiento_existe = true;

                $procedimiento_diagnostico = \App\Models\ProcedimientoDiagnotico::where("id_prestacion", $id_prestacion)
                                                                                ->where("id_evaluacion", $id_evaluacion)
                                                                                ->where("id_procedimientos", $id_procedimiento_backup)
                                                                                ->where("id_diagnosticos", $id_diagnostico_backup)
                                                                                ->where("estado", 1)
                                                                                ->where("id_interconsulta", 0)
                                                                                ->get();

                $cantidad = $procedimiento_diagnostico ? count($procedimiento_diagnostico) : 0;

                if ($cantidad == 1):

                  $filas_afectadas = \App\Models\ProcedimientoDiagnotico::where("id_prestacion", $id_prestacion)
                                                                        ->where("id_evaluacion", $id_evaluacion)
                                                                        ->where("id_procedimientos", $id_procedimiento_backup)
                                                                        ->where("id_diagnosticos", $id_diagnostico_backup)
                                                                        ->where("estado", 1)
                                                                        ->where("id_interconsulta", 0)
                                                                        ->update([
                                                                            "id_diagnosticos" => $id_diagnostico_del_procedimiento,
                                                                            "updated_us" => Auth::user()->id,
                                                                            "updated_at" => Carbon::now()
                                                                        ]);
                  
                  if ($filas_afectadas !== 1):
                    throw new \Exception("Error al intentar actualizar un procedimiento (" . $id_cpms . "). ");
                  else:
                    unset($list_procedimiento_ya_registrado[$indice_backup]);
                    break;
                  endif;

                else:
                  throw new \Exception("Error al intentar actualizar un procedimiento (Cantidad " . $cantidad . ").");
                endif;
                
              endif;
            endforeach;

            if (!$procedimiento_existe):

              // Nuevo procedimiento
              $prestacion_procedimiento = new \App\Models\ProcedimientoDiagnotico();
              $prestacion_procedimiento->id_prestacion = $request->id_prestacion;
              $prestacion_procedimiento->id_procedimientos = $id_cpms;
              $prestacion_procedimiento->id_diagnosticos = $id_diagnostico_del_procedimiento;
              $prestacion_procedimiento->estado = 1;
              $prestacion_procedimiento->id_evaluacion = $id_evaluacion;
              $prestacion_procedimiento->id_interconsulta = 0;
              $prestacion_procedimiento->id_consejeria = 0;
              $prestacion_procedimiento->created_at = Carbon::now();
              $prestacion_procedimiento->created_us = $request->id_user;

              if (!$prestacion_procedimiento->save()):
                throw new \Exception("Error al intentar registrar un procedimiento (" . $id_cpms . ").");
              endif;

            endif;

          }

          // Verificar procedimientos no considerados
          if (!empty($list_procedimiento_ya_registrado)) {
              foreach ($list_procedimiento_ya_registrado as $obj_proc) {
                  // Anular procedimientos
                  $procedimiento_diagnostico = \App\Models\ProcedimientoDiagnotico::where("id_prestacion", $id_prestacion)
                                                                                  ->where("id_evaluacion", $id_evaluacion)
                                                                                  ->where("id_procedimientos", $obj_proc->cod)
                                                                                  ->where("id_diagnosticos", $obj_proc->id_diagnostico)
                                                                                  ->where("estado", 1)
                                                                                  ->where("id_interconsulta", 0)
                                                                                  ->get();

                  $cantidad = $procedimiento_diagnostico ? count($procedimiento_diagnostico) : 0;

                  if ($cantidad == 1):
                    $filas_afectadas = \App\Models\ProcedimientoDiagnotico::where("id_prestacion", $id_prestacion)
                                                                          ->where("id_evaluacion", $id_evaluacion)
                                                                          ->where("id_procedimientos", $obj_proc->cod)
                                                                          ->where("id_diagnosticos", $obj_proc->id_diagnostico)
                                                                          ->where("estado", 1)
                                                                          ->where("id_interconsulta", 0)
                                                                          ->update([
                                                                              "estado" => 0,
                                                                              "deleted_us" => Auth::user()->id,
                                                                              "deleted_at" => Carbon::now()
                                                                          ]);

                    if ($filas_afectadas !== 1):
                      throw new \Exception("Error al intentar anular un procedimiento (" . $obj_proc->cod . ").");
                    endif;
                  else:
                    throw new \Exception("Error al intentar actualizar un procedimiento (Cantidad " . $cantidad . ").");
                  endif;
              }
          }
      }
      // Fin Actualizacion de procedimientos

      // Actualizacion de Interconsultas
      $model_interconsulta = new \App\Models\Interconsulta();
      if (!empty($request->id_servicio_interconsulta)):
        $p_interconsulta = "{";

        foreach ($request->id_servicio_interconsulta as $key => $value):
          $personal_coordinacion = $request->interconsulta_personal_coodinacion[$key];
          $personal_coordinacion = $personal_coordinacion != "" ? $personal_coordinacion : "NULL";

          if ($request->id_servicio_interconsulta[$key] != "") {
            $p_interconsulta .= "{";
            $p_interconsulta .= $request->id_servicio_interconsulta[$key] . ",";
            $p_interconsulta .= $turno . ",";
            $p_interconsulta .= "0,";
            $p_interconsulta .= str_replace($search, $replace, $request->motivo_interconsulta[$key]) . ",";
            $p_interconsulta .= "0,0,";
            $p_interconsulta .= $request->is_estable . ",";
            $p_interconsulta .= $personal_coordinacion;
            $p_interconsulta .= "},";
          }
        endforeach;

        if (strlen($p_interconsulta) > 1) $p_interconsulta = substr($p_interconsulta, 0, -1);
        $p_interconsulta .= "}"; 

        if ($request->id_servicio_interconsulta[0] != ""):
          $interconsulta_id = $model_interconsulta->RegistrarInterconsultaEvaluacion($request->id_prestacion, $request->id_asegurado, $request->id_tipo_atencion, $request->fecha_atencion, $request->id_medico, $request->id_establecimiento, $p_interconsulta, $id_evaluacion);
          
          if (intval($interconsulta_id) == 0):
            throw new \Exception("Error al intentar registrar las nuevas interconsultas. ");
          endif;
        endif;
      endif;
      // Fin Actualizacion de Interconsultas

      $id_receta_vale = 0;
      $id_consultorio = 76;
      $nro_receta = "";
      $digitos_adicionales = "";

      $id_tipo_num = 0;
      $id_receta_vale_bus = Auth::user()->tipo_registro == 3 ? 1 : 0; // Tipo registro: Digitador de Consultorio
      $estado_receta = 2;
      $id_anestesiologo = 0;
      $id_tiporeceta = $request->id_tipo_atencion;
      $obs_registro = "";
      $id_admision = 0;
      $tipo_via = "";
      $sub_via = "";
      $final_via = "";
      $nro_guia = "";
      $interconsulta = "";
      $interconsulta_dias = 0;
      $id_tipo_num = 0;
      $estado_receta = 2;

      $p_producto = "{";
      if (isset($request->id_producto)) :
        if ($request->id_producto[0] != "ru='undefined'"):
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
              $p_producto .= $request->id_via_administracion[$key];
              $p_producto .= "},";
            }
          endforeach;
          if (strlen($p_producto) > 1) $p_producto = substr($p_producto, 0, -1);
        endif;
      endif;
      $p_producto .= "}";

      $asegurado = Asegurado::find($request->id_asegurado);
      $titular = Asegurado::find($asegurado->id_asegurado_titular);

      if ($request->id_diagnostico[0] != ""):
        $p_diagnostico = "";
        $p_diagnostico .= "{";

        if (isset($request->id_diagnostico)) :
          foreach ($request->id_diagnostico as $key => $value) :
            if ($request->id_diagnostico[$key] != "") {
              $p_diagnostico .= "{";
              $p_diagnostico .= $request->id_diagnostico[$key] . ",";
              $p_diagnostico .= (isset($request->id_tipo_diagnostico[$key]) && $request->id_tipo_diagnostico[$key] > 0) ? $request->id_tipo_diagnostico[$key] : "0";
              $p_diagnostico .= "},";
            }
          endforeach;
          if (strlen($p_diagnostico) > 1) $p_diagnostico = substr($p_diagnostico, 0, -1);
        endif;

        $p_diagnostico .= "}";
      else:
          $diagnosticos = $model_prestacion->GetDiagnosticoByIdPrestacion($request->id_prestacion);
          $p_diagnostico = "";
          $p_diagnostico .= "{";
          foreach ($diagnosticos as $diagnostico ) :
              $p_diagnostico .= "{";
              $p_diagnostico .= $diagnostico->id_diagnostico . ",";
              $p_diagnostico .= $diagnostico->id_tipo_diagnostico;
              $p_diagnostico .= "},";
          endforeach;
          if (strlen($p_diagnostico) > 1) $p_diagnostico = substr($p_diagnostico, 0, -1);
          $p_diagnostico .= "}";
      endif;

      if (isset($request->id_producto)):
        if ($request->id_producto[0] != "ru='undefined'"):
          $receta = new \App\Models\Receta_vale();
          $id_receta_vale = $receta->postRegistrarRecetaValeNuevoConDiagnosticoEvaluacion($id_receta_vale, $request->id_farmacia_receta, $request->id_user, $id_consultorio, $nro_receta, $digitos_adicionales, $request->nrodocafiliado, $titular->cip, $request->tipodoc_beneficiario, $request->nombre_beneficiario, $request->paterno_beneficiario, $request->materno_beneficiario, $request->tipo_beneficiario, $titular->nro_doc_ident, $titular->tipo_doc_ident, $titular->nombre, $titular->paterno, $titular->materno, $request->fecha_expedicion, $request->fecha_atencion, $request->nro_historia, $request->grado, $request->id_medico, $id_anestesiologo, $id_tiporeceta, $turno, $obs_registro, $id_receta_vale_bus, $id_admision, $tipo_via, $sub_via, $final_via, $nro_guia, $interconsulta, $interconsulta_dias, $id_prestacion, $id_tipo_num, $estado_receta, $p_producto, $p_diagnostico, $request->nomsexo, $request->fecnacafiliado, $request->codigo_upss, $id_evaluacion);

          if ($id_receta_vale > 0):
            $model_rec_val = new \App\Models\Receta_vale;
            $ok = $model_rec_val->ActualizaIdEvaluacion($id_receta_vale, $id_evaluacion);
            
            if (!$ok):
              throw new \Exception("Error al intentar vincular la receta con la evaluacion.");
            endif;

          else:
            throw new \Exception("Error al intentar registrar la receta.");
          endif;

        endif;
      endif;

      // Registro de Ordenes de Laboratorio
      if (isset($request->id_examen_lab)):
        $id_examen_lab = array_filter($request->id_examen_lab, function ($valor) {
          return !empty($valor);
        });

        if (!empty($id_examen_lab)):
          $p_laboratorio = "";
          $p_laboratorio .= "{";
          foreach ($request->id_examen_lab as $key => $value):
            if ($request->id_examen_lab[$key] != "") {
              $p_laboratorio .= "{";
              $p_laboratorio .= $request->id_examen_lab[$key] . ",";
              if (intval($request->id_nivel) === 3):
                $p_laboratorio .= "0";
              else:
              $p_laboratorio .= (isset($request->examen_lab_observacion[$key]) && $request->examen_lab_observacion[$key] !="") ? str_replace($search, $replace, $request->examen_lab_observacion[$key]) : "0";
              endif;
              $p_laboratorio .= ",";
              $p_laboratorio .= (isset($request->examen_lab_cantidad[$key]) && $request->examen_lab_cantidad[$key] > 0) ? $request->examen_lab_cantidad[$key] : "0";
              $p_laboratorio .= ",";
              $p_laboratorio .= (isset($request->miSelectLab[$key]) && $request->miSelectLab[$key] !="") ? $request->miSelectLab[$key] : "0";
              $p_laboratorio .= "},";
            }
          endforeach;

          if (strlen($p_laboratorio) > 1) $p_laboratorio = substr($p_laboratorio, 0, -1);
          $p_laboratorio .= "}";

          if ($request->id_examen_lab[0] != ""):
            $model_procedimiento = new \App\Models\Procedimiento();

            if (intval($request->id_establecimiento) === 76):
              $has_observacion_unica = (intval($request->id_nivel) === 3) ? 1 : 0;
              $id_interconsulta = 0;
              $id_servicio = 76;
              $lab_orden_observacion_unica = intval($request->id_nivel) === 3 ? $request->lab_orden_observacion : null;
              $flag_cq = 0;
              $id_hora_toma_muestra = null;

              $id_procedimiento_lab = $model_procedimiento->RegistrarLaboratorioOrden3($request->id_user, $request->id_prestacion, $request->id_establecimiento, $request->fecha_atencion, $p_laboratorio, $has_observacion_unica, $id_evaluacion,$request->id_farmacia, $id_interconsulta, $request->id_tipo_atencion, $id_servicio, $request->id_topico, $request->id_cama, $request->id_medico, $request->id_asegurado, $lab_orden_observacion_unica, $flag_cq, $id_hora_toma_muestra);

            else:
            $id_procedimiento_lab = $model_procedimiento->RegistrarLaboratorioOrden2($request->id_user, $request->id_prestacion, $request->id_establecimiento, $request->fecha_atencion, $p_laboratorio, 2, $id_evaluacion,$request->id_farmacia, 0, $request->id_tipo_atencion, 76, $request->id_topico, $request->id_cama, $request->id_medico, $request->id_asegurado);
            endif;

            switch (intval($id_procedimiento_lab)):
              case 0: throw new \Exception("Error al intentar registrar la nueva Orden de laboratorio."); break;
              case -1: throw new \Exception("Error al intentar actualizar los campos de flag_cita_laboratorio."); break;
              case -2: throw new \Exception("Error al intentar registrar un examen de la Orden de laboratorio."); break;
              case -3: throw new \Exception("Error al intentar actualizar un tipo de examen de la Orden de labotatorio."); break;
            endswitch;
          endif;
        endif;
      endif;
      // Fin Registro de Ordenes de Laboratorio

      // Registro de ordenes de Imagenes
      if (isset($request->id_examen_rad)):
        $p_radiologia = "{";
        $id_examen_rad = array_filter($request->id_examen_rad, function ($valor) {
          return !empty($valor);
        });

        if (!empty($id_examen_rad)):
          foreach ($request->id_examen_rad as $key => $value) :
            if ($request->id_examen_rad[$key] != "") {
              $p_radiologia .= "{";
              $p_radiologia .= $request->id_examen_rad[$key] . ",";
              $p_radiologia .= (isset($request->examen_rad_cantidad[$key]) && $request->examen_rad_cantidad[$key] > 0) ? $request->examen_rad_cantidad[$key] : "0";
              $p_radiologia .= ",";
              $p_radiologia .= (isset($request->examen_rad_observacion[$key]) && $request->examen_rad_observacion[$key] !="") ? str_replace($search, $replace, $request->examen_rad_observacion[$key]) : 'NULL';
              $p_radiologia .= ",";
              $p_radiologia .= (isset($request->id_comparativo[$key]) && $request->id_comparativo[$key] !="") ? $request->id_comparativo[$key] : "0";
              $p_radiologia .= ",";
              $p_radiologia .= (isset($request->id_lado[$key]) && $request->id_lado[$key] !="") ? $request->id_lado[$key] : "0";
              $p_radiologia .= ",";
              $p_radiologia .= (isset($request->miSelectRad[$key]) && $request->miSelectRad[$key] !="") ? $request->miSelectRad[$key] : "0";
              $p_radiologia .= ",";
              $p_radiologia .= (isset($request->radio_portatil[$key]) && $request->radio_portatil[$key] !="") ? $request->radio_portatil[$key] : "0";
              $p_radiologia .= "},";
            }
          endforeach;
          if (strlen($p_radiologia) > 1) $p_radiologia = substr($p_radiologia, 0, -1);
          $p_radiologia .= "}";

          if ($request->id_examen_rad[0] != ""):
            $model_procedimiento = new \App\Models\Procedimiento();
            $id_procedimiento_rad = $model_procedimiento->RegistrarProcedimientoImagenes($request->id_user, $request->id_prestacion, $request->id_establecimiento, $request->fecha_atencion, $p_radiologia, 3, $id_evaluacion, $request->id_farmacia);
            
            switch (intval($id_procedimiento_rad)):
              case 0: throw new \Exception("Error al intentar registrar la nueva Orden de imágenes."); break;
              case -1: throw new \Exception("Error al intentar registrar un examen en la Orden en imágenes."); break;
              default:
                $model_rad = new \App\Models\Radiologia;
                $result = $model_rad->ActualizaIdEvaluacion($id_procedimiento_rad, $id_evaluacion);
                  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Identificar radiología a la cual pertenece 
                  if($request->id_establecimiento == 76 ){
                      $updated = DB::table('rua_imagenologia')->where('id', $request->id_rua_imagenes)->update(['id_rad' => $id_procedimiento_rad,'id_evol'=> $id_evaluacion, 'id_estado_reg' => 1]);
                  }
                  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                if (!$result):
                  throw new \Exception("Error al intentar vincular la Orden de imagénes con la evaluacion.");
                endif;
            endswitch;
          endif;
        endif;
      endif;
      // Fin Registro de ordenes de Imagenes
      
      DB::commit();
      $registro_validado = true;

    } catch (\Exception $e) {
      DB::rollback();
      Log::error("Edicion Evaluacion Emergencia => id_emergencia: " . $request->id_principal . " id_evaluacion: " . $request->id_evaluacion . 
          "\n\nmensaje: " . $e->getMessage() . 
          "\n\ndatos del request: " . json_encode($request->all()) . 
          "\n\nstack trace: " . $e->getTraceAsString()
      );
    } catch (\Throwable $th) {
      DB::rollback();
      Log::critical("Edicion Evaluacion Emergencia => id_emergencia: " . $request->id_principal . " id_evaluacion: " . $request->id_evaluacion . 
          "\n\nmensaje: " . $th->getMessage() . 
          "\n\ndatos del request: " . json_encode($request->all()) . 
          "\n\nstack trace: " . $th->getTraceAsString()
      );
    }

    if ($registro_validado):
      Session::flash('flash_message', 'Evaluación Médica exitosa');
      return redirect('/ver_evaluacion/' . $request->id_user . '/' . $request->id_evaluacion);
    else:
      Session::flash('flash_warning', '<strong>Error:</strong> No se pudo registrar la Evaluación Médica, vuelva a intentarlo nuevamente.<br><br>' . $e->getMessage());
      return redirect('/emergencia/evaluacion/edit/'. $request->id_user . '/' . $request->id_evaluacion);
    endif;
  }

}
