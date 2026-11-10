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
use App\Models\User;
use App\Models\Medico;
use App\Models\Interconsulta;
use App\Models\Emergencia;
use App\Models\Asegurado;
use App\Models\Prestaciones;
use App\Models\SubConsultorio;
use App\Models\Cita;
use App\Models\Asegurado_historia;
use App\Models\Receta_vale;
use App\Models\Kardexe;
use App\Models\Producto_receta;
use App\Models\Producto;
use App\Models\Laboratorio;
use App\Models\Upss;
use Illuminate\Support\Facades\Log;
use SoapClient;

class InterconsultasController extends Controller
{ 
  
  function __construct()
  {
      $this->middleware('auth'); 
  } 
  
  public function all_interconsultas($id_tipo_atencion = 0)
  {
    $model_medico = new \App\Models\Medico();
    $model_medico_turno = new \App\Models\Medico_turno();
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
    if ($ruta == "listar_interconsulta_ajax");
    $ruta = "all_interconsultas";
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
    $permiso = $menu[0];
    if ($permiso->lectura == "")
      return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
    $id_user = Auth::user()->dni;
    $id_establecimiento = Auth::user()->id_establecimiento;
    

    if(Auth::user()->flagservicios!=1):
      $id_servicio = Auth::user()->id_consultorio;
      $medicos = $model_medico->GetDatosMedicoByDniAndEstablecimiento($id_user,$id_establecimiento);
      $id_medico =   $medicos->id;
    else:
      $id_servicio = 0;
      $id_medico =   0;
    endif;
    //$servicio = $model_medico_turno->GetMedicoByIdIpressAndIdConsultorioAndSoloEmergencia($id_establecimiento,$id_servicio);
    
    $date = Carbon::now();
    $fechaHasta = $date->format('d-m-Y');
    $fechaDesdex = $date->format('d-m-Y');
    $fechaDesdey = strtotime('-1 day', strtotime($fechaDesdex));
    $fechaDesde = date('j-m-Y', $fechaDesdey);

    //====================
    $model_farma = new Farmacia();
    $farmacia = $model_farma->GetFarmaByUser(Auth::user()->id);

    $model_consultorio = new \App\Models\Consultorio;
    $servicios = $model_consultorio->ShowAllConsultoriosSubConsultorio($farmacia->id_establecimiento);

    $dni_beneficiario = '';
    $nombre_beneficiario = '';
    $estado = 1;

    return view('frontend.interconsultas.all_interconsultas', compact('farmacia', 'fechaDesde', 'fechaHasta', 'dni_beneficiario', 'nombre_beneficiario', 'estado','id_medico','id_servicio','servicios','id_tipo_atencion'));
  }

  public function listar_interconsulta_ajax(Request $request)
  {
    $model_interconsultas = new Interconsulta();
    $p[] = Auth::user()->id_establecimiento;
    $p[] = $request->fecha_ini;
    $p[] = $request->fecha_fin;
    $p[] = $request->dni_beneficiario;
    $p[] = strtoupper($request->nombre_beneficiario);
    $p[] = $request->estado;
    $p[] = $request->id_medico;
    $p[] = $request->id_servicio;
    $p[] = $request->NumeroPagina;
    $p[] = $request->NumeroRegistros;
    if ($request->tipo_atencion == "3") {
      $data = $model_interconsultas->listar_interconsulta_hospitalizacion_ajax($p);
    } else {
    $data = $model_interconsultas->listar_interconsulta_ajax($p);
    }
    //Log::info(["data", $data]);
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

  public function atender_interconsulta($id_prestacion,$id_interconsulta,$id_evaluacion)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $init_interconsulta = Interconsulta::find($id_interconsulta);

    if (is_null($init_interconsulta->fecha_rpta_atencion)) {

      $asegurado_model = Asegurado::find($init_interconsulta->id_asegurado);

      $client = new SoapClient(env('WI_SALUDPOL_URL') . "/ws/WS_AfiliadoSP_Serv.php?wsdl", array('login' => env('WI_SALUDPOL_USERNAME'), 'password' => env('WI_SALUDPOL_PASSWORD')));
      $beneficiario = $client->getAseguradoValidate($asegurado_model->id_tipo_doc_ident, $asegurado_model->nro_doc_ident);

      $esta_asegurado = false;
      if (is_object($beneficiario)):
        $esta_asegurado = true;
      endif;

      if ($esta_asegurado):
        $modelfarma = new Farmacia;
        $model_establecimiento = new \App\Models\Establecimiento;
        $model_prestacion = new \App\Models\Prestaciones;
        $model_interconsulta = new \App\Models\Interconsulta;
        // $model_evaluacion = new \App\Models\Evaluacione;

        $farmacia = $modelfarma->GetFarmaByUser(Auth::user()->id);
        
        $id_farmacia = $farmacia->id;
        $date = Carbon::now();
        $fecha_actual = $date->format('d-m-Y');

        // $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
        $prestacion = DB::table("prestaciones as pr")
                ->join("sub_consultorios as sc", "pr.id_sub_consultorio", "=", "sc.id")
                ->join("asegurados as as2", "pr.id_asegurado", "=", "as2.id")
                ->join('asegurado_historias as ah', function($join) {
                    $join->on('ah.id_asegurado', '=', 'as2.id')
                        ->on('ah.id_establecimiento', '=', 'pr.id_establecimiento');
                })
                ->leftJoin("tipo_beneficiarios as tb", "as2.id_tipo_beneficiario", "=", "tb.id")
                ->select(
                    "pr.*", 
                    "sc.id_consultorio", 
                    "as2.sexo", 
                    "as2.nro_doc_ident", 
                    "as2.tipo_doc_ident",
                    "as2.paterno as asegurado_paterno",
                    "as2.materno as asegurado_materno",
                    "as2.nombre as asegurado_nombre",
                    "tb.descripcion as parentesco",
                    "as2.fecha_nac",
                    "as2.cip",
                    "as2.grado",
                    "ah.nro_historia",
                    "pr.fecha_atencion as fecha_hora_atencion",
                    DB::raw("date_part('year', age(to_date(to_char(as2.fecha_nac::date, 'DD/MM/YYYY'), 'DD/MM/YYYY'))) as edadpaciente")
                )
                ->where("pr.id", $id_prestacion)
                ->first();
        $has_profesional_rpta_hospitalizacion = true;

        $model_receta_diagnostico = new \App\Models\Receta_diagnostico();
        $diagnosticos = $model_receta_diagnostico->GetDiagnosticosPrestacionOrEvaluacion($id_prestacion, $id_evaluacion);

        $model_procedimiento = new \App\Models\Procedimiento();
        $procedimientos = $model_procedimiento->GetProcedimientosPrestacionOrEvaluacion($id_prestacion, $id_evaluacion);

        if ($prestacion->id_tipo_atencion == 3): // Hospitalizacion
          // $model_hospitalizacion = new \App\Models\Hospitalizacione();
          $interconsulta = $model_interconsulta->GetInterconsultaById2($id_interconsulta);

          // $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacionHospitalizacion($id_prestacion);
          // $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionHospitalizacion($id_prestacion, 1); 
          
          if ($id_evaluacion != 0):
            // $diagnostico_evaluacion = $model_evaluacion->GetDiagnosticoByIdEvaluacion($id_evaluacion);
            // $procedimiento_evaluacion = $model_prestacion->GetProcedimientosByIdPrestacionydiagEvaluacion($id_prestacion, 1, $id_evaluacion);
          endif;
        else: // Emergencia
          if($id_evaluacion==0):
            // $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacionEmergencia($id_prestacion);
            $interconsulta = $model_interconsulta->GetInterconsultaById($id_prestacion,$id_interconsulta);
            // $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionEmergencia($id_prestacion, 1);
          else:  //evaluacion emergencia
            // $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacionEmergencia($id_prestacion);
            // $diagnostico_evaluacion = $model_evaluacion->GetDiagnosticoByIdEvaluacion($id_evaluacion);
            $interconsulta = $model_interconsulta->GetInterconsultaByIdEvaluacion($id_prestacion,$id_evaluacion,$id_interconsulta);
            // $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionEmergencia($id_prestacion, 1);
            // $procedimiento_evaluacion = $model_prestacion->GetProcedimientosByIdPrestacionydiagEvaluacion($id_prestacion, 1, $id_evaluacion);
          endif;
        endif;

        $has_profesional_rpta_hospitalizacion = $interconsulta->id_medico_rpta != null ? true : false;
        if (!$has_profesional_rpta_hospitalizacion):

          $model_medico = new \App\Models\Medico;
          $profesional_rpta = $model_medico->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni, Auth::user()->id_establecimiento);

          if ($profesional_rpta != null):
            $interconsulta->id_medico_rpta = $profesional_rpta->id;
            $interconsulta->documento_tr = $profesional_rpta->dni;
            $interconsulta->medico_nombre_tr = $profesional_rpta->nombre;
            $interconsulta->medico_paterno_tr = $profesional_rpta->paterno;

            $interconsulta->medico_materno_tr = $profesional_rpta->materno;
            $interconsulta->colegiatura_medico_tr = $profesional_rpta->colegiatura;
            $interconsulta->tr_rne = $profesional_rpta->rne;
            $interconsulta->profesion = $profesional_rpta->profesion;
            $interconsulta->especializacion = $profesional_rpta->especializacion;
          endif;

        endif;
      
        $pendiente_firma = $model_prestacion->GetDocumentosPendintesFirmarPrestacionId($id_prestacion);
        $flag_pendinte_firma = (count($pendiente_firma) > 0) ? 1 : 0;

        $id_ipress = $farmacia->id_establecimiento;
        $establecimiento = $model_establecimiento->GetById($id_ipress);
        if ($id_ipress == 1) {
          $nivel = 2;
        } else {
          $nivel = $establecimiento[0]->nivel;
        }
        
        $farmacias = $modelfarma->GetFarmaciaPorTipoAtencionInterconsulta($id_ipress, $prestacion->id_tipo_atencion);
        $establecimientos = $model_establecimiento->ShowAllEstablecimientos();
        $model_kardex = new \App\Models\Kardexe();
        $fechaServidor = $model_kardex->getFechaServidor(); 

        $model_producto = new \App\Models\Producto;
        $model_establecimiento = new \App\Models\Establecimiento;
        $establecimiento = $model_establecimiento->GetById($id_ipress);
        $establecimientos = $model_establecimiento->ShowAllEstablecimientos();
        
        if ($id_ipress == 1) {
            $nivel = 2;
        } else {
            $nivel = $establecimiento[0]->nivel;
        }
        $datos_ubicacion = [];
        $area_id = 0;
        $cama_id = 0;

        if ($prestacion->id_tipo_atencion == 2): // Emergencia

          $data = DB::table('emergencias as e')
                        ->join('prioridades as p', 'e.ubica_prioridad', '=', 'p.id')
                        ->where('e.id', $prestacion->id_ext)
                        ->select('p.descripcion as prioridad')
                        ->first();

          $datos_ubicacion["emergencia_prioridad"] = $data->prioridad;

          $model_emergencia = new Emergencia;
          $obtenerdato = $model_emergencia->GetObtenerServicio($prestacion->id_ext);
          $dato_cama = $model_emergencia->GetObtenerCama($obtenerdato->cama);

          $datos_ubicacion["emergencia_cuarto"] = $dato_cama->cuarto;
          $datos_ubicacion["emergencia_cama"] = $dato_cama->cama;
          $area_id = $obtenerdato->ubica_topico;
          $cama_id = $obtenerdato->cama;

        elseif ($prestacion->id_tipo_atencion == 3): // Hospitalizacion

          $hospitalizacione = App\Models\Hospitalizacione::where('id', $prestacion->id_ext)->first();

          $model_consultorio = new \App\Models\Consultorio;
          $servicio_encargado = $model_consultorio->GetNombreById($hospitalizacione->id_servicio_encargado);
          $servicio_ubicacion = $model_consultorio->GetNombreById($hospitalizacione->id_servicio_destino);

          $model_emergencia = new Emergencia;
          $dato_cama = $model_emergencia->GetObtenerCama($hospitalizacione->id_cama_estado);

          $datos_ubicacion["hospitalizacion_servicio_encargado"] = $servicio_encargado->nombre;
          $datos_ubicacion["hospitalizacion_servicio_fisico"] = $servicio_ubicacion->nombre;
          $datos_ubicacion["hospitalizacion_cama"] = $dato_cama->cama;

        endif;

        $model_upss = new App\Models\Upss();
        $upsses = $model_upss->ShowConsultoriosUpss($interconsulta->id_servicio, $interconsulta->id_tipo_atencion);

        //DATOS PARA CENTRO QUIRURGICO
        $tiene_flag_quirurgico = \App\Models\Consultorio::where('id',$interconsulta->id_servicio)->where('flag_quirurgico',1)->exists();

        $model_tipo_cirugia = new \App\Models\CqxTipoAnestesia();
        $tipo_cirugias = $model_tipo_cirugia->GetAllTipoCirugiasActivos();

        $model_cqx_tipo_profesional = new \App\Models\CqxTipoProfesional();
        $cqx_tipo_profesionales = $model_cqx_tipo_profesional->GetAllActivos();
        //FIN DATOS PARA CENTRO QUIRURGICO
        
        return view('frontend.interconsultas.atencion_interconsulta', compact('id_interconsulta','esta_asegurado','fechaServidor','farmacia', 'prestacion', 'diagnosticos', 'procedimientos', 'medicamentos', 'id_prestacion',  'farmacias', 'nivel', 'flag_pendinte_firma','establecimientos','interconsulta','id_evaluacion','has_profesional_rpta_hospitalizacion','datos_ubicacion','upsses', 'area_id', 'cama_id',
        'tiene_flag_quirurgico','tipo_cirugias','cqx_tipo_profesionales'));
      else:
        return redirect('/all_interconsultas')->withInput()->withErrors(array('El paciente ya no está asegurado, por consiguiente no tiene derecho de atención, realizar los trámites correspondiente con SALUDPOL.'));
      endif;
    } else {
      Session::flash('flash_warning', 'La Interconsulta ya está respondida.');
      return redirect('/all_interconsultas');
    }
  }

  public function ver_atencion_interconsultas($id_prestacion,$id_interconsulta,$id_tipo_atencion,$id_evaluacion,$id_establecimiento)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $model_prestacion = new \App\Models\Prestaciones;
    $model_interconsulta = new \App\Models\Interconsulta;
    $model_evaluacion = new \App\Models\Evaluacione;

    $diagnostico_evaluacion = null;
    $procedimiento_evaluacion = null;

    // $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
    $prestacion = DB::table("prestaciones as pr")
                ->join("sub_consultorios as sc", "pr.id_sub_consultorio", "=", "sc.id")
                ->join("consultorios as co", "sc.id_consultorio", "=", "co.id")
                ->join("asegurados as as2", "pr.id_asegurado", "=", "as2.id")
                ->join('asegurado_historias as ah', function($join) {
                    $join->on('ah.id_asegurado', '=', 'as2.id')
                        ->on('ah.id_establecimiento', '=', 'pr.id_establecimiento');
                })
                ->join("tipo_atenciones as ta", "pr.id_tipo_atencion", "=", "ta.id")
                ->join("establecimientos as es", "pr.id_establecimiento", "=", "es.id")
                ->join("medicos as me", "pr.id_medico", "=", "me.id")
                ->leftJoin("tipo_beneficiarios as tb", "as2.id_tipo_beneficiario", "=", "tb.id")
                ->leftJoin("especializaciones as es2", "me.id_especializacion", "=", "es2.id")
                ->leftJoin("profesiones as pr2", "me.id_profesion", "=", "pr2.id")
                ->select(
                    "pr.*", 
                    "sc.id_consultorio", 
                    "sc.descripcion as consultorio",
                    "as2.sexo", 
                    "as2.nro_doc_ident", 
                    "as2.tipo_doc_ident",
                    "as2.paterno as asegurado_paterno",
                    "as2.materno as asegurado_materno",
                    "as2.nombre as asegurado_nombre",
                    "as2.id_asegurado_titular",
                    "tb.descripcion as parentesco",
                    "as2.fecha_nac",
                    "as2.cip",
                    "as2.grado",
                    "ah.nro_historia",
                    "pr.fecha_atencion as fecha_hora_atencion",
                    "ta.nombre as tipo_atencion",
                    "co.nombre as servicio",
                    "es.nombre as establecimiento",
                    DB::raw("date_part('year', age(to_date(to_char(as2.fecha_nac::date, 'DD/MM/YYYY'), 'DD/MM/YYYY'))) as edadpaciente"),
                    DB::raw("to_char(pr.fecha_atencion,'dd-mm-YYYY') as fecha_atencion"),
                    "me.dni",
                    "me.paterno as medico_paterno",
                    "me.materno as medico_materno",
                    "me.nombre as medico_nombre",
                    "es2.nombre as especializacion",
                    "pr2.nombre as profesion",
                    "me.colegiatura",
                    "me.rne"
                )
                ->where("pr.id", $id_prestacion)
                ->first();

    if($id_establecimiento !=76):
      $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
      $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 1);   
      $interconsulta = $model_interconsulta->GetInterconsultaById($id_prestacion,$id_interconsulta);
    else:
      if($id_tipo_atencion==2):
          $model_emergencia = new \App\Models\Emergencia;
          $obtenerdato = $model_emergencia->GetObtenerServicioActual($prestacion->id_ext);

          $datoadicional = $model_emergencia->GetObtenerCama($obtenerdato->cama);
          $prestacion->servicio= $obtenerdato->servicio;
          $prestacion->consultorio= $obtenerdato->consultorio;
          if($id_evaluacion==0):
            $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacionEmergencia($id_prestacion);
            $interconsulta = $model_interconsulta->GetInterconsultaById($id_prestacion,$id_interconsulta);
            $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionEmergencia($id_prestacion, 1);
          else:  //evaluacion emergencia
            $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacionEmergencia($id_prestacion);
            $diagnostico_evaluacion = $model_evaluacion->GetDiagnosticoByIdEvaluacion($id_evaluacion);
            $interconsulta = $model_interconsulta->GetInterconsultaByIdEvaluacion($id_prestacion,$id_evaluacion,$id_interconsulta);
            $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionEmergencia($id_prestacion, 1);
            $procedimiento_evaluacion = $model_prestacion->GetProcedimientosByIdPrestacionydiagEvaluacion($id_prestacion, 1, $id_evaluacion);
          endif;
      elseif ($id_tipo_atencion == 3):

        $model_hospitalizacion = new \App\Models\Hospitalizacione();
        $datoadicional =  $model_hospitalizacion->GetObtenerDatos($prestacion->id_ext); // JALA LOS DATOS DE LA CAMA ACTUAL, PERO DEBEMOS DE JALAR LA CAMA DE ESE MOMENTO (PREGUNTAR) 

        // $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
        $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacionHospitalizacion($id_prestacion);
        
        $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionHospitalizacion($id_prestacion, 1);   
        $interconsulta = $model_interconsulta->GetInterconsultaById2($id_interconsulta);
        
        if ($id_evaluacion != 0):
          $diagnostico_evaluacion = $model_evaluacion->GetDiagnosticoByIdEvaluacion($id_evaluacion);
          $procedimiento_evaluacion = $model_prestacion->GetProcedimientosByIdPrestacionydiagEvaluacion($id_prestacion, 1, $id_evaluacion);
        endif;

      else: //ambulatorio
        // $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
        $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
        $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 1);   
        $interconsulta = $model_interconsulta->GetInterconsultaById($id_prestacion,$id_interconsulta);
      endif;
    endif;

    $model_asegurado= new Asegurado();
    $dato_titular=null;
    $dato_titular = $model_asegurado->Busca_datos_titular($prestacion->id_asegurado_titular);

    $has_diagnostico_evolucion = $procedimiento_evaluacion != null ? true : false;

    return view('frontend.interconsultas.ver_atencion', compact('farmacia', 'prestacion', 'diagnostico', 'procedimiento', 'medicamentos', 'id_prestacion',  'flag_pendinte_firma','interconsulta','datoadicional','obtenerdato','dato_titular','diagnostico_evaluacion','procedimiento_evaluacion','id_evaluacion','has_diagnostico_evolucion')); 
    
    
  }

    public function modal_interconsulta_paciente($id_prestacion ){
        $model_interconsulta = new \App\Models\Interconsulta;
        $interconsultas = $model_interconsulta->GetInterconsultaByIdPrestacion($id_prestacion);
        
        return view('frontend.epicrisis.modal_interconsultas',compact('interconsultas'));
    }
  
    function registrar_interconsulta_rpta_transaction(Request $request)
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

      $user_id_farmacia = Auth::user()->id_farmacia;

      $model_prestacion = new \App\Models\Prestaciones;
      $id_prestacion=$request->id_prestacion;
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
  
      $p_procedimiento = "";
      $p_procedimiento .= "{";
      if (isset($request->id_procedimiento)) :
        foreach ($request->id_procedimiento as $key => $value) :
          if ($request->id_procedimiento[$key] != "") {
            $p_procedimiento .= "{";
            $p_procedimiento .= $request->id_procedimiento[$key] . ",";
            $p_procedimiento .= (isset($request->procedimiento_cantidad[$key]) && $request->procedimiento_cantidad[$key] > 0) ? $request->procedimiento_cantidad[$key] : "0";
            $p_procedimiento .= ",";
            $p_procedimiento .= $request->miSelect[$key];
            $p_procedimiento .= "},";
          }
        endforeach;
        if (strlen($p_procedimiento) > 1) $p_procedimiento = substr($p_procedimiento, 0, -1);
      endif;
      $p_procedimiento .= "}";

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
            $mensajeSucess = $model_procedimiento_metodos->RegistrarProcedimientoxDiagnosticoInterconsulta($idprestacionxDiagProc,$idprocedimiento,$iddiagnostico,1,  $request->id_interconsulta);  
          }
  
        endif;
      endif;

      $p_producto = "";
      $p_producto .= "{";
      if ($request->flag_add_receta_vale):
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
      endif;
      $p_producto .= "}";

      $id_receta_vale = 0;
      $nro_receta = "";
      $digitos_adicionales = "";
      $id_anestesiologo = 0;
      $id_tiporeceta = $request->id_tipo_atencion;
      $obs_registro = "";
      $id_receta_vale_bus = 0;
      $id_admision = 0;
      $tipo_via = "";
      $sub_via = "";
      $final_via = "";
      $nro_guia = "";
      $interconsulta = "";
      $interconsulta_dias = 0;
      $id_tipo_num = 0;
      $estado_receta = 2;
      $id_evaluacion = $request->id_evaluacion; // Depende si es una evolucion (H) o evaluacion (E) se envia el id, por defecto va en 0
      //$p_producto_combinado = "{}";

      $date = Carbon::now();
      $hora = $date->format('H:i');

      if ($hora >= '00:00' && $hora <= '07:30') {
          $turno = 'MD';
      } elseif ($hora >= '07:31' && $hora <= '13:30') {
          $turno = 'M';
      } elseif ($hora >= '13:31' && $hora <= '19:30') {
          $turno = 'T';
      } elseif ($hora >= '19:31' && $hora <= '23:59') {
          $turno = 'N';
      }

      $prestacion = Prestaciones::find($request->id_prestacion);
      $cita = Cita::find($prestacion->id_cita);
      // $asegurado = Asegurado::find($cita->id_asegurado);
      $titular = Asegurado::find($cita->id_titular);

      if (!empty($titular)):
        $titular_cip = $titular->cip;
        $titular_nro_doc_ident = $titular->nro_doc_ident;
        $titular_tipo_doc_ident = $titular->tipo_doc_ident;
        $titular_nombre = $titular->nombre;
        $titular_paterno = $titular->paterno;
        $titular_materno = $titular->materno;
      else:
        $titular_cip = "";
        $titular_nro_doc_ident = "";
        $titular_tipo_doc_ident = "";
        $titular_nombre = "";
        $titular_paterno = "";
        $titular_materno = "";
      endif;

      //postRegistrarRecetaValeNuevoConDiagnosticoEvaluacionHO
      if (isset($request->id_producto)) :
        if($request->id_producto[0]!="ru='undefined'"):
          $receta_model = new Receta_vale();
          $id_receta_vale = $receta_model->postRegistrarRecetaValeNuevoConDiagnosticoInterconsulta($id_receta_vale, $request->id_farmacia_receta, $request->id_user, $request->id_servicio, $nro_receta, $digitos_adicionales, $request->nrodocafiliado, $titular_cip, $request->tipodoc_beneficiario, $request->nombre_beneficiario, $request->paterno_beneficiario, $request->materno_beneficiario, $request->tipo_beneficiario, $titular_nro_doc_ident, $titular_tipo_doc_ident, $titular_nombre, $titular_paterno, $titular_materno, $request->fecha_expedicion, $request->fecha_atencion, $request->nro_historia, $request->grado, $request->id_medico_rpta, $id_anestesiologo, $id_tiporeceta, $turno, $obs_registro, $id_receta_vale_bus, $id_admision, $tipo_via, $sub_via, $final_via, $nro_guia, $interconsulta, $interconsulta_dias, $request->id_prestacion, $id_tipo_num, $estado_receta, $p_producto, $p_diagnostico, $request->nomsexo, $request->fecnacafiliado, $request->codigo_upss, $id_evaluacion, $request->id_interconsulta);
          
          if ($id_evaluacion > 0):
            $ok = $receta_model->ActualizaIdEvaluacion($id_receta_vale, $id_evaluacion);
          endif;

        endif;
      else :
        $id_receta_vale = 1;
      endif;

      if ($request->flag_add_orden_lab):
      $model_procedimiento = new \App\Models\Procedimiento;
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
              $p_laboratorio .= "},";
          }
        endforeach;
        if (strlen($p_laboratorio) > 1) $p_laboratorio = substr($p_laboratorio, 0, -1);
        $p_laboratorio .= "}";
        if($request->id_examen_lab[0]!=""):

            $has_observacion_unica = (intval($request->id_nivel) === 3) ? 1 : 0;
            $lab_orden_observacion_unica = intval($request->id_nivel) === 3 ? $request->lab_orden_observacion : null;
            $id_area = 0;
            $flag_cq = (isset($request->id_perfil_preoperatorio) && intval($request->id_perfil_preoperatorio) > 0) ? 1 : 0;
            $id_hora_toma_muestra = (intval($request->id_nivel) === 3 && intval($request->id_tipo_atencion) === 3 && isset($request->id_hora_toma_muestra)) ? $request->id_hora_toma_muestra : null;
            
            $id_procedimiento_lab = $model_procedimiento->RegistrarLaboratorioOrden3($request->id_user, $request->id_prestacion, $request->id_establecimiento, $request->fecha_atencion, $p_laboratorio, $has_observacion_unica, $id_evaluacion, $user_id_farmacia, $request->id_interconsulta, $request->id_tipo_atencion, $request->id_servicio, $request->area_id, $request->cama_id, $request->id_medico_rpta, $request->id_asegurado, $lab_orden_observacion_unica, $flag_cq, $id_hora_toma_muestra);

            if ($id_evaluacion > 0):
              $model_lab = new \App\Models\Laboratorio;
              $ok = $model_lab->ActualizaIdEvaluacion($id_procedimiento_lab, $id_evaluacion);
            endif;
        endif;
      endif;
      endif;
      
      if ($request->flag_add_orden_rad):
      $model_procedimiento = new \App\Models\Procedimiento;
      if (isset($request->id_examen_rad)) :
        $p_radiologia = "";
        $p_radiologia .= "{";
        foreach ($request->id_examen_rad as $key => $value) :
          if ($request->id_examen_rad[$key] != "") {
              $p_radiologia .= "{";
              $p_radiologia .= $request->id_examen_rad[$key] . ",";
              $p_radiologia .= (isset($request->examen_rad_cantidad[$key]) && $request->examen_rad_cantidad[$key] > 0) ? $request->examen_rad_cantidad[$key] : "0";
              $p_radiologia .= ",";
              $p_radiologia .= (isset($request->examen_rad_observacion[$key]) && $request->examen_rad_observacion[$key] !="") ? str_replace($search, $replace, $request->examen_rad_observacion[$key]) : "0";
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
          $id_procedimiento_rad = $model_procedimiento->RegistrarRadiologiaOrden($request->id_user, $request->id_prestacion, $request->id_establecimiento, $request->fecha_atencion, $p_radiologia, 3, $id_evaluacion, $user_id_farmacia, $request->id_interconsulta);
          /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Identificar radiología a la cual pertenece 
          if($request->id_establecimiento == 76 ){
            $updated = DB::table('rua_imagenologia')->where('id', $request->id_rua_imagenes)->update(['id_rad' => $id_procedimiento_rad, 'id_estado_reg' => 1, 'id_asegurado' => $cita->id_asegurado, 'id_interconsulta' => $request->id_interconsulta]);
          }
          ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
          if ($id_evaluacion > 0):
            $model_rad = new \App\Models\Radiologia;
            $ok = $model_rad->ActualizaIdEvaluacion($id_procedimiento_rad, $id_evaluacion);
          endif;
        endif;
      endif;
      endif;
      /****************************************************************************/
          /*$p_producto = "";
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
                $p_producto .= $request->id_via_administracion[$key];
                $p_producto .= "},";
              }
            endforeach;
            if (strlen($p_producto) > 1) $p_producto = substr($p_producto, 0, -1);
          endif;
          $p_producto .= "}";*/

          /*********************************************************************/
          /*$model_procedimiento = new \App\Models\Procedimiento;
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
              $id_procedimiento_lab = $model_procedimiento->RegistrarProcedimientoInterconsulta($request->id_user, $id_prestacion, $request->id_establecimiento, $request->fecha_atencion, $p_laboratorio, 2, $request->id_interconsulta);
                $model_lab = new \App\Models\Laboratorio;
                $ok=$model_lab->ActualizaIdInterconsulta($id_procedimiento_lab,$request->id_interconsulta);
            endif;
          endif;*/
          
          /*if (isset($request->id_examen_rad)) :
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
              $id_procedimiento_rad = $model_procedimiento->RegistrarProcedimientoInterconsulta($request->id_user, $id_prestacion, $request->id_establecimiento, $request->fecha_atencion, $p_radiologia, 3, $request->id_interconsulta);
              $model_rad = new \App\Models\Radiologia;
              $ok=$model_rad->ActualizaIdInterconsulta($id_procedimiento_rad,$request->id_interconsulta);
            endif;
            
      
          endif;*/
    /*if (isset($request->id_producto)) :
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
      $historia = Asegurado_historia::where('id_asegurado',$cita->id_asegurado)->where('id_establecimiento',$cita->id_establecimiento)->first();

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
      $dni_titular = $titular->nro_doc_ident;
      $tipdoc_titular = $titular->tipo_doc_ident;
      $nombre_titular = $titular->nombre;
      $paterno_titular = $titular->paterno;
      $materno_titular = $titular->materno;

      $nro_historia = isset($historia->nro_historia) ? $historia->nro_historia : "";
      $hora = date('H');
      //echo $hora;exit();
      if ($hora > 6 && $hora < 13) $turno = "M";
      elseif ($hora >= 13 && $hora < 19) $turno = "T";
      elseif ($hora >= 19 && $hora <= 24) $turno = "N";
      else $turno = "MD";
      //echo $turno;exit();

      $receta = new \App\Models\Receta_Vale();
      $id_receta_vale = $receta->postRegistrarRecetaValeNuevoConDiagnostico($id_receta_vale, $request->id_farmacia_receta, $request->id_user, $id_consultorio, $nro_receta, $digitos_adicionales, $dni_beneficiario, $cip_beneficiario, $tipodoc_beneficiario, $nombre_beneficiario, $paterno_beneficiario, $materno_beneficiario, $tipo_beneficiario, $dni_titular, $tipdoc_titular, $nombre_titular, $paterno_titular, $materno_titular, $request->fecha_expedicion, $fecha_atencion, $request->nro_historia, $grado, $id_medico, $id_anestesiologo, $id_tiporeceta, $turno, $obs_registro, $id_receta_vale_bus, $id_admision, $tipo_via, $sub_via, $final_via, $nro_guia, $interconsulta, $interconsulta_dias, $id_prestacion, $id_tipo_num, $estado_receta, $p_producto, $p_diagnostico, $sexo, $fecha_nacimiento, $codigo_upss);
          $model_rec_val = new \App\Models\Receta_vale;
          $ok=$model_rec_val->ActualizaIdInterconsulta($id_receta_vale,$request->id_interconsulta);
    else :
      $id_receta_vale = 1;
    endif;*/
      /***************************************************************************/
      $model_interconsulta = new \App\Models\Interconsulta;
      $id_rpta_interconsulta = $model_interconsulta->RegistrarInterconsultaRpta($request->id_prestacion, $id_evaluacion, $p_procedimiento, $p_diagnostico, $request->id_interconsulta, $request->fecha_atencion, $request->rpta_interconsulta, $request->id_user, $request->id_medico_rpta);
      
      $interconsultaSiteds = Interconsulta::where("id", $request->id_interconsulta);
      if (isset($request->codigo_siteds) && empty($interconsultaSiteds->cod_siteds)) {
        $interconsultaSiteds->update([
          'cod_siteds' => $request->codigo_siteds,
          'updated_us_cod_siteds' => Auth::user()->id,
          'updated_at_cod_siteds' => Carbon::now(),
        ]);
      }
      
      /*if ($request->id_interconsulta == 0) {
        Session::flash('flash_message', 'No se pudo registrar la Respuesta de la Interconsulta, hubo un problema con la conexión de internet de su establecimiento PNP, vuelva a intentarlo nuevamente');
        return redirect('/all_interconsultas');
      } else {
        Session::flash('flash_message', 'Respuesta de la Interconsulta exitosa');
        
        return redirect('/ver_atencion_interconsulta_rptas/' . $request->id_prestacion . '/' . $request->id_interconsulta . '/' . $request->id_evaluacion. '/' . $request->id_tipo_atencion);
      }*/
        /***********************************************************************************************************/
        /******************************************CENTRO QUIRURGICO************************************************/ 
        
        if ((int)$request->requiere_intervencion_quirurgica === 1) {
            $id_prestacion = !empty($request->id_prestacion)?$request->id_prestacion:null;
            $id_servicio = !empty($request->id_servicio)?$request->id_servicio:null;
            $fechaAtencionPqx = Carbon::now();
            $fechaProbable = !empty($request->fecha_probable_pq)?Carbon::parse($request->fecha_probable_pq)->format('Y-m-d'):null;
            $idPrioridadProgramacion =  !empty($request->id_prioridad_programacion)?$request->id_prioridad_programacion:null;//form
            $id_clasificacion_cirugia= !empty($request->id_clasificacion_cirugia)?$request->id_clasificacion_cirugia:null;//form
            $id_tipo_cirugia = !empty($request->id_tipo_cirugia)?$request->id_tipo_cirugia:null;//form
            $duracion_cirugia = !empty($request->duracion_cirugia)?$request->duracion_cirugia:null;//form 
            $hora_inicio_intervencion = !empty($request->hora_inicio_intervencion)?$request->hora_inicio_intervencion:null;//form 
            $hora_fin_intervencion = !empty($request->hora_fin_intervencion)?$request->hora_fin_intervencion:null;//form 
            $id_tipo_atencion = !empty($request->id_tipo_atencion)?$request->id_tipo_atencion:null;//form 
            $horaInicioPq = $hora_inicio_intervencion;
            $horaFinPq = $hora_fin_intervencion;
            $tiempoCuracionCqx = $duracion_cirugia;
            $id_interconsulta = !empty($request->id_interconsulta)?$request->id_interconsulta:null;
            $id_establecimiento = !empty($request->id_establecimiento)?$request->id_establecimiento:null;
            $id_asegurado = !empty($request->id_asegurado)?$request->id_asegurado:null;

            $aseguradoPq = Asegurado::where('nro_doc_ident', '=', $request->dni_beneficiario)
                ->where('tipo_doc_ident', '=', $request->tipodoc_beneficiario)
                ->where('id_estado_reg', '=', 1)
                ->orderBy('id', 'desc')
                ->first();
            /*****************************************************************************************************/
            /***************************************PROGRAMACION***************************************************/
            $id_hospitalizacion = \App\Models\Interconsulta::where('id', $id_interconsulta)->where('id_tipo_atencion', 3)->value('id_ext');

            $pendiente_alta_emergencia=false;
            if(intval($id_tipo_atencion)===2){//CUANDO ES EMERGENCIA
              $pendiente_alta_emergencia=true;
              $id_emergencia = \App\Models\Interconsulta::where('id', $id_interconsulta)->where('id_tipo_atencion', 2)->value('id_ext');

              $emergencia = \App\Models\Emergencia::find($id_emergencia);
              if($emergencia->estado==5){
                $pendiente_alta_emergencia=false;

                $orden = new \App\Models\HospitalizacionOrden();
                $orden->id_establecimiento = $emergencia->id_establecimiento;
                $orden->id_tipo_atencion = 2;
                $orden->id_ext = $emergencia->id;
                $orden->id_asegurado = $emergencia->id_asegurado;
                $orden->fecha_derivacion =  Carbon::now();//
                $orden->id_medico = $request->id_medico_rpta;
                $orden->id_sala = $emergencia->id_servicio_destino ? $emergencia->id_servicio_destino: null;//
                $orden->flag = 1;
                $orden->created_us = $emergencia->id_usuario_egreso?$emergencia->id_usuario_egreso:null;//
                $orden->id_upss_responsable = $emergencia->id_upss_alta?$emergencia->id_upss_alta:null;//
                $orden->id_upss_traslado = $emergencia->id_upss_alta?$emergencia->id_upss_alta:null;//
                $orden->flag_quirurgico = 1;
                $orden->id_servicio_destino_hospi = $emergencia->id_servicio_destino_ho ?$emergencia->id_servicio_destino_ho: null;//
                $orden->id_pabellon_destino = $emergencia->id_pabellon_destino ?$emergencia->id_pabellon_destino: null;//
                $orden->id_cuarto_destino = $emergencia->id_cuarto_destino ?$emergencia->id_cuarto_destino: null;//
                $orden->save();

              }else{
                $codigo_upss = \App\Models\Interconsulta::where('id', $id_interconsulta)->where('id_tipo_atencion', 2)->value('codigo_upss');
                $id_upss= \App\Models\Upss::where('codigo', $codigo_upss)->value('id');
            
                $orden = new \App\Models\HospitalizacionOrden();
                $orden->id_establecimiento = $emergencia->id_establecimiento;
                $orden->id_tipo_atencion = 2;
                $orden->id_ext = $emergencia->id;
                $orden->id_asegurado = $emergencia->id_asegurado;
                $orden->fecha_derivacion = Carbon::now();//
                $orden->id_medico = $request->id_medico_rpta;
                $orden->id_sala = $request->id_servicio;
                $orden->flag = 1;
                $orden->created_us = $emergencia->id_usuario_egreso;
                $orden->flag_quirurgico = 1;
                $orden->id_upss_traslado = $id_upss;//SERA UN ID TEMPORAL YA QUE DESPUES SERA ACTUALIZADO
                $orden->save();
              }

            }
            if (intval($request->es_programacion_quirurgica) === 1 && $aseguradoPq) {
            
                $cqxIntervencion = \App\Models\CqxIntervencion::create([
                    "id_prestacion"=>$id_prestacion,
                    "id_asegurado"=>$id_asegurado,
                    "id_consultorio"=>$id_servicio,
                    "id_establecimiento"=>$id_establecimiento,
                    "id_medico_reg_programacion" => $request->id_medico_rpta,
                    "id_prioridad_programacion" => $idPrioridadProgramacion,
                    "fecha_probable" => $fechaProbable,
                    "fecha_solicitud"=>Carbon::now(),
                    "hora_fin_prog_qx" => $horaFinPq,
                    "hora_inicio_prog_qx" => $horaInicioPq,
                    "emergencia_duracion_cirugia" => $tiempoCuracionCqx,
                    "flag" => 2,
                    "emergencia_tipo_cirugia" => $id_tipo_cirugia,
                    "id_clasificacion_cirugia" => $id_clasificacion_cirugia,
                    "id_interconsulta" => $id_interconsulta,
                    "es_traslado_interconsultas_acqx" => true,
                    "created_us" => Auth::user()->id,
                    "id_hospitalizacion"=>$id_hospitalizacion,
                    "pendiente_alta_emergencia"=>$pendiente_alta_emergencia
                  ]);
                  //Actualizando el flag de la prestacion para quirurgico
                  //\App\Models\Prestaciones::where('id', $id_prestacion)->update(['flag_intervencion_quirurgica' => true]);
                  // Insertar en cqx_cita usando el ID generado
                  \App\Models\CqxCita::create([
                    "id_cqx_intervenciones" => $cqxIntervencion->id,
                    "id_consultorio" => $id_servicio,
                    "id_prestacion" => $id_prestacion,
                    "fecha_atencion" => $fechaAtencionPqx,
                    "fecha_probable" => $fechaProbable,
                    "is_programacion_quirurgica" => true,
                    "id_medico_reg_programacion" => $request->id_medico_rpta,
                    "id_prioridad_programacion" => $idPrioridadProgramacion,
                    "id_cita" => $cita->id,
                    "tipo" => 2,
                    "flag" => 3,
                    "id_estado_reg" => 1,
                    "created_us" => Auth::user()->id,
                    "created_at" => Carbon::now()
                ]);
                //Registro de diagnosticos cqx
                if (isset($request->id_diagnostico_quirurgico)) :
                    $dignostico_cqx = new \App\Models\Receta_diagnostico();
                    foreach ($request->id_diagnostico_quirurgico as $key => $value) :
                      if ($request->id_diagnostico_quirurgico[$key] != "") {
                        $dignostico_cqx->id_diagnostico = $request->id_diagnostico_quirurgico[$key];
                        $dignostico_cqx->id_tipo_diagnostico = $request->id_tipo_diagnostico_quirurgico[$key];
                        $dignostico_cqx->estado = 1;
                        $dignostico_cqx->id_prestacion = $request->id_prestacion;
                        $dignostico_cqx->created_at=Carbon::now();
                        $dignostico_cqx->created_us=$request->id_user;
                        $dignostico_cqx->id_intervencion= $cqxIntervencion->id;
                        $dignostico_cqx->save();
                      }
                    endforeach;
                  endif; 

                // Registro de Medicos Cirujanos
                if (isset($request->id_medico_cirujano)) :
                  $model_cq_profesional = new \App\Models\CqxProfesional();
                  foreach ($request->id_medico_cirujano as $key => $value) :
                    if ($request->id_medico_cirujano[$key] != "") {
                      $p_id_medico = $request->id_medico_cirujano[$key];
                      $p_id_medico_cargo = (isset($request->id_medico_cirujano_cargo[$key]) && $request->id_medico_cirujano_cargo[$key] > 0 ? $request->id_medico_cirujano_cargo[$key] : 0);
                      $flag_personal = 0; // Cirujanos
                      $response = $model_cq_profesional->RegistrarProfesionalesPreQx($cqxIntervencion->id, $p_id_medico, $p_id_medico_cargo, $request->id_user, $flag_personal, $cita->id);
                    }
                  endforeach;
                endif;
                // Registro de Procedimientos de Intervencion quirurgica
                if (isset($request->id_procedimiento_intervencion)) :
                    $model_cq_procedimiento = new \App\Models\CqxProcedimiento();
                    foreach ($request->id_procedimiento_intervencion as $key => $value) :
                      if ($request->id_procedimiento_intervencion[$key] != "") {
                        $p_id_procedimiento = $request->id_procedimiento_intervencion[$key];
                        $model_cq_procedimiento->RegistrarProcedimientoPreQx($cqxIntervencion->id, $p_id_procedimiento, Auth::user()->id, $cita->id,1,false);
                      }
                    endforeach;
                endif;
              /*****************************************************************************************************/
              /***************************************FIN PROGRAMACION***************************************************/
            }else{
              $cqxIntervencion = \App\Models\CqxIntervencion::create([
                  "id_prestacion"=>$id_prestacion,
                  "id_asegurado"=>$id_asegurado,
                  "id_consultorio"=>$id_servicio,
                  "id_establecimiento"=>$id_establecimiento,
                  "id_prioridad_programacion" => $idPrioridadProgramacion,
                  "fecha_solicitud"=>Carbon::now(),
                  "emergencia_duracion_cirugia" => $tiempoCuracionCqx,
                  "flag" => 1,
                  "emergencia_tipo_cirugia" => $id_tipo_cirugia,
                  "id_clasificacion_cirugia" => $id_clasificacion_cirugia,
                  "id_interconsulta" => $id_interconsulta,
                  "es_traslado_interconsultas_acqx" => true,
                  "created_us" => Auth::user()->id,
                  "id_hospitalizacion"=>$id_hospitalizacion,
                  "pendiente_alta_emergencia"=>$pendiente_alta_emergencia
                  ]);
                //Registro de diagnosticos cqx
                if (isset($request->id_diagnostico_quirurgico)) :
                    $dignostico_cqx = new \App\Models\Receta_diagnostico();
                    foreach ($request->id_diagnostico_quirurgico as $key => $value) :
                      if ($request->id_diagnostico_quirurgico[$key] != "") {
                        $dignostico_cqx->id_diagnostico = $request->id_diagnostico_quirurgico[$key];
                        $dignostico_cqx->id_tipo_diagnostico = $request->id_tipo_diagnostico_quirurgico[$key];
                        $dignostico_cqx->estado = 1;
                        $dignostico_cqx->id_prestacion = $request->id_prestacion;
                        $dignostico_cqx->created_at=Carbon::now();
                        $dignostico_cqx->created_us=$request->id_user;
                        $dignostico_cqx->id_intervencion= $cqxIntervencion->id;
                        $dignostico_cqx->save();
                      }
                    endforeach;
                  endif; 

                // Registro de Medicos Cirujanos
                if (isset($request->id_medico_cirujano)) :
                  $model_cq_profesional = new \App\Models\CqxProfesional();
                  foreach ($request->id_medico_cirujano as $key => $value) :
                    if ($request->id_medico_cirujano[$key] != "") {
                      $p_id_medico = $request->id_medico_cirujano[$key];
                      $p_id_medico_cargo = (isset($request->id_medico_cirujano_cargo[$key]) && $request->id_medico_cirujano_cargo[$key] > 0 ? $request->id_medico_cirujano_cargo[$key] : 0);
                      $flag_personal = 0; // Cirujanos
                      $response = $model_cq_profesional->RegistrarProfesionalesPreQx($cqxIntervencion->id, $p_id_medico, $p_id_medico_cargo, $request->id_user, $flag_personal, 0);
                    }
                  endforeach;
                endif;
                // Registro de Procedimientos de Intervencion quirurgica
                if (isset($request->id_procedimiento_intervencion)) :
                    $model_cq_procedimiento = new \App\Models\CqxProcedimiento();
                    foreach ($request->id_procedimiento_intervencion as $key => $value) :
                      if ($request->id_procedimiento_intervencion[$key] != "") {
                        $p_id_procedimiento = $request->id_procedimiento_intervencion[$key];
                        $model_cq_procedimiento->RegistrarProcedimientoPreQx($cqxIntervencion->id, $p_id_procedimiento, Auth::user()->id, 0,1,false);
                      }
                    endforeach;
                endif;
            }
        }
        /***********************************************************************************************************/
        /******************************************FIN CENTRO QUIRURGICO************************************************/ 

        DB::commit();
        $registro_validado = true;
          
      } catch (\Exception $e) {
        DB::rollback();
        Log::error("Registro Interconsulta => id_interconsulta: " . $request->id_interconsulta . 
            "\n\nmensaje: " . $e->getMessage() . 
            "\n\ndatos del request: " . json_encode($request->all()) . 
            "\n\nstack trace: " . $e->getTraceAsString()
        );
      } catch (\Throwable $th) {
        DB::rollback();
        Log::critical("Registro Interconsulta => id_interconsulta: " . $request->id_interconsulta . 
            "\n\nmensaje: " . $th->getMessage() . 
            "\n\ndatos del request: " . json_encode($request->all()) . 
            "\n\nstack trace: " . $th->getTraceAsString()
        );
      }
      if ($registro_validado):
        Session::flash('flash_message', 'Respuesta de la Interconsulta exitosa');
        return redirect('/ver_atencion_interconsulta_rptas/' . $request->id_prestacion . '/' . $request->id_interconsulta . '/' . $request->id_evaluacion. '/' . $request->id_tipo_atencion);
      else:
        Session::flash('flash_warning', '<strong>Error:</strong> No se pudo registrar la Respuesta de la Interconsulta, vuelva a intentarlo nuevamente.<br><br>' . $e->getMessage());
        return redirect('/all_interconsultas');
      endif;
    }

    public function ver_atencion_interconsulta_rptas($id_prestacion,$id_interconsulta, $id_evaluacion, $id_tipo_atencion)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $init_interconsulta = Interconsulta::find($id_interconsulta);

    if (!is_null($init_interconsulta->fecha_rpta_atencion)) {
    $modelfarma = new Farmacia;
    $model_establecimiento = new \App\Models\Establecimiento;
    $model_prestacion = new \App\Models\Prestaciones;
    $model_interconsulta = new \App\Models\Interconsulta;
    $model_evaluacion = new \App\Models\Evaluacione;
    

    $farmacia = $modelfarma->GetFarmaByUser(Auth::user()->id);
    $id_farmacia = $farmacia->id;
    $flag_receta_dias = $farmacia->flag_receta_dias;

    
    $date = Carbon::now();
    $fecha_actual = $date->format('d-m-Y');
    // $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
    $prestacion = DB::table("prestaciones as pr")
                ->join("sub_consultorios as sc", "pr.id_sub_consultorio", "=", "sc.id")
                ->join("asegurados as as2", "pr.id_asegurado", "=", "as2.id")
                ->join('asegurado_historias as ah', function($join) {
                    $join->on('ah.id_asegurado', '=', 'as2.id')
                        ->on('ah.id_establecimiento', '=', 'pr.id_establecimiento');
                })
                ->leftJoin("tipo_beneficiarios as tb", "as2.id_tipo_beneficiario", "=", "tb.id")
                ->select(
                    "pr.*", 
                    "sc.id_consultorio", 
                    "as2.sexo", 
                    "as2.nro_doc_ident", 
                    "as2.tipo_doc_ident",
                    "as2.paterno as asegurado_paterno",
                    "as2.materno as asegurado_materno",
                    "as2.nombre as asegurado_nombre",
                    "tb.descripcion as parentesco",
                    "as2.fecha_nac",
                    "as2.cip",
                    "as2.grado",
                    "ah.nro_historia",
                    "pr.fecha_atencion as fecha_hora_atencion",
                    DB::raw("date_part('year', age(to_date(to_char(as2.fecha_nac::date, 'DD/MM/YYYY'), 'DD/MM/YYYY'))) as edadpaciente")
                )
                ->where("pr.id", $id_prestacion)
                ->first();
    
    $diagnostico = $model_interconsulta->GetDiagnosticoByIdInterconsulta( $id_interconsulta);
    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionydiagInterconsulta($id_prestacion,1, $id_interconsulta);
    $pendiente_firma = $model_prestacion->GetDocumentosPendintesFirmarPrestacionId($id_prestacion);
    $flag_pendinte_firma = (count($pendiente_firma) > 0) ? 1 : 0;

    $id_ipress = $farmacia->id_establecimiento;
    $establecimiento = $model_establecimiento->GetById($id_ipress);
    if ($id_ipress == 1) {
      $nivel = 2;
    } else {
      $nivel = $establecimiento[0]->nivel;
    }
    
    $farmacias = $modelfarma->GetFarmaciaByIdEstablecimiento($id_ipress);
    $establecimientos = $model_establecimiento->ShowAllEstablecimientos();

    $datos_ubicacion = [];

    if ($id_tipo_atencion != 3):
      if($id_evaluacion==0):
        $interconsulta = $model_interconsulta->GetInterconsultaById($id_prestacion,$id_interconsulta);
      else:
        $interconsulta = $model_interconsulta->GetInterconsultaByIdEvaluacion($id_prestacion,$id_evaluacion,$id_interconsulta);
      endif;

      $data = DB::table('emergencias as e')
                    ->join('prioridades as p', 'e.ubica_prioridad', '=', 'p.id')
                    ->where('e.id', $prestacion->id_ext)
                    ->select('p.descripcion as prioridad')
                    ->first();

      $datos_ubicacion["emergencia_prioridad"] = $data->prioridad;

      $model_emergencia = new Emergencia;
      $obtenerdato = $model_emergencia->GetObtenerServicio($prestacion->id_ext);
      $dato_cama = $model_emergencia->GetObtenerCama($obtenerdato->cama);

      $datos_ubicacion["emergencia_cuarto"] = $dato_cama->cuarto;
      $datos_ubicacion["emergencia_cama"] = $dato_cama->cama;

    else:
      $interconsulta = $model_interconsulta->GetInterconsultaById2($id_interconsulta);

      $hospitalizacione = App\Models\Hospitalizacione::where('id', $prestacion->id_ext)->first();

      $model_consultorio = new App\Models\Consultorio();
      $servicio_encargado = $model_consultorio->GetNombreById($hospitalizacione->id_servicio_encargado);
      $servicio_ubicacion = $model_consultorio->GetNombreById($hospitalizacione->id_servicio_destino);

      $model_emergencia = new Emergencia;
      $dato_cama = $model_emergencia->GetObtenerCama($hospitalizacione->id_cama_estado);

      $datos_ubicacion["hospitalizacion_servicio_encargado"] = $servicio_encargado->nombre;
      $datos_ubicacion["hospitalizacion_servicio_fisico"] = $servicio_ubicacion->nombre;
      $datos_ubicacion["hospitalizacion_cama"] = $dato_cama->cama;

    endif;

    $model_receta = new Receta_vale;
    $model_kardex = new Kardexe();
    $model_productos_receta = new Producto_receta;
    $model_producto = new Producto;
    $model_laboratorio = new Laboratorio;
    $model_radiologia = new \App\Models\Radiologia;
    $receta = $model_receta->GetDatosRecetasByIdInterconsulta($id_interconsulta);

    $fechaServidor = $model_kardex->getFechaServidor();
    $producto = $model_producto->getProductosByNivelAndIdServicio($nivel, $prestacion->id_consultorio, $id_farmacia);
    $laboratorio = $model_laboratorio->GetLaboratorioByIdPrestacionInterconsultaRpta($id_prestacion, $id_interconsulta);
      if (empty($laboratorio)) {
        $flag_laboratorio = "0";
        $flag_firmar_laboratorio = "";
      } else {
        $flag_laboratorio = "1";
        $flag_firmar_laboratorio = $laboratorio[0]->fecha_firma;
      }

    $radiologia = $model_radiologia->GetRadiologiaByIdPrestacionInterconsulta($id_prestacion, $id_interconsulta);
      $pendiente_firma = $model_interconsulta->GetDocumentosPendientesFirmarInterconsultaRespuesta($id_interconsulta);
      $flag_pendinte_firma = (count($pendiente_firma) > 0) ? 1 : 0;
    //DATOS PARA QUIRURGICO
    $model_intervencion = new \App\Models\CqxIntervencion;
    $existe_intervencion_cqx = $model_intervencion::where('id_prestacion', $id_prestacion)->where('id_interconsulta',$id_interconsulta)->exists();
    $datos_intervencion="";
    $procedimientos_cqx="";
    $profesionales_cqx="";

    if ($existe_intervencion_cqx) {
      $datos_intervencion = $model_intervencion::select('cqx_intervenciones.id','tc.descripcion as tipo_cirugia','cqx_intervenciones.emergencia_duracion_cirugia','cqx_intervenciones.emergencia_tipo_cirugia','cqx_intervenciones.fecha_probable','cqx_intervenciones.id_prioridad_programacion as turno',DB::raw("
              CASE cqx_intervenciones.id_clasificacion_cirugia
                  WHEN 1 THEN 'CIRUGIA MAYOR'
                  WHEN 2 THEN 'CIRUGIA MENOR'
                  WHEN 3 THEN 'CIRUGIA MEDIANA'
                  ELSE 'SIN CLASIFICAR'
              END AS clasificacion_cirugia
          "))
        ->leftJoin('cqx_tipo_cirugias as tc', 'cqx_intervenciones.emergencia_tipo_cirugia', '=', 'tc.id')
        ->where('cqx_intervenciones.id_prestacion', $id_prestacion)->where('cqx_intervenciones.id_interconsulta', $id_interconsulta)->first();

      //DIAGNOSTICOS QUIRURGICOS
      $diagnosticos_cqx = DB::table('receta_diagnosticos as rd')
        ->leftJoin('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
        ->leftJoin('tipo_diagnosticos as td', 'td.id', '=', 'rd.id_tipo_diagnostico')
        ->select('d.nombre as descripcion_diagnostico', 'd.codigo as codigo_diagnostico', 'rd.id_tipo_diagnostico', 'td.descripcion as descripcion_tipo_diagnosticos')
        ->where('rd.id_intervencion', '=', $datos_intervencion->id)
        ->where('rd.id_prestacion', '=', $id_prestacion)
        ->get();

      //PROCEDIMIENTOS QUIRURGICOS
      $model_cq_procedimiento = new \App\Models\CqxProcedimiento();
      $procedimientos_cqx = $model_cq_procedimiento->GetByIdIntervencionQuirugica($datos_intervencion->id);

      //PROFESIONALES QUIRURGICOS
      $model_cq_profesional = new \App\Models\CqxProfesional();
      $profesionales_cqx = $model_cq_profesional->GetByIdIntervencionQuirugicaYTipoPersonal($datos_intervencion->id, 0);
    }
    //FIN DATOS PARA QUIRURGICO 

    return view('frontend.interconsultas.ver_atencion_rpta', compact('id_interconsulta','farmacia', 'prestacion', 'diagnostico', 'procedimiento', 'medicamentos', 'id_prestacion',  'farmacias', 'nivel', 'flag_pendinte_firma','establecimientos','interconsulta','receta','fechaServidor','producto','flag_laboratorio','flag_firmar_laboratorio','laboratorio','radiologia','pendiente_firma','flag_pendinte_firma', 'id_evaluacion','datos_ubicacion','existe_intervencion_cqx','datos_intervencion','diagnosticos_cqx','procedimientos_cqx','profesionales_cqx'));
    } else {
      Session::flash('flash_warning', 'La Interconsulta no ha sido respondida.');
      return redirect('/all_interconsultas');
    }
  }

  public function modal_historial_interconsulta($dni_beneficiario){
        $interconsulta_prestaciones = new \App\Models\Interconsulta;
        $interconsultas = $interconsulta_prestaciones->GetExamenInterconsultaRpta($dni_beneficiario);
        return view('frontend.interconsultas.modal_historial_interconsulta',compact('interconsultas'));
  }

  public function modal_historial_interconsulta_enf($dni_beneficiario){
        $interconsulta_prestaciones = new \App\Models\Interconsulta;
        $interconsultas = $interconsulta_prestaciones->GetExamenInterconsultaRpta($dni_beneficiario);
        return view('frontend.interconsultas.modal_historial_interconsulta_enf',compact('interconsultas'));
  }

  public function mostrar_interconsulta($id_interconsulta, $id_prestacion, $id_evaluacion){

    $model_prestacion = new \App\Models\Prestaciones;
    $model_interconsulta = new \App\Models\Interconsulta;
    $model_evaluacion = new \App\Models\Evaluacione;
    
    $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
    $flag_bloqueo_atencion = $prestacion ? DB::table('asegurados')->where('id', $prestacion->id_asegurado)->value('flag_bloqueo_atencion') : null;
    if ($flag_bloqueo_atencion !== null && intval($flag_bloqueo_atencion) === 1) :
      return response()->json(['message' => 'No puede ver la atención de este paciente.'], 403);
    endif;

    $id_establecimiento = Auth::user()->id_establecimiento; 
    if($id_establecimiento !=76):
      $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
      $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 1);   
      $interconsulta = $model_interconsulta->GetInterconsultaById($id_prestacion,$id_interconsulta);
    else:
      if($prestacion->id_tipo_atencion==2):
          $model_emergencia = new \App\Models\Emergencia;
          $obtenerdato = $model_emergencia->GetObtenerServicioActual($prestacion->id_ext);

          $datoadicional = $model_emergencia->GetObtenerCama($obtenerdato->cama);
          $prestacion->servicio= $obtenerdato->servicio;
          $prestacion->consultorio= $obtenerdato->consultorio;
          if($id_evaluacion==0):
            $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacionEmergencia($id_prestacion);
            $interconsulta = $model_interconsulta->GetInterconsultaById($id_prestacion,$id_interconsulta);
            $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionEmergencia($id_prestacion, 1);
          else:  //evaluacion emergencia
            $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacionEmergencia($id_prestacion);
            $diagnostico_evaluacion = $model_evaluacion->GetDiagnosticoByIdEvaluacion($id_evaluacion);
            $interconsulta = $model_interconsulta->GetInterconsultaByIdEvaluacion($id_prestacion,$id_evaluacion,$id_interconsulta);
            $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionEmergencia($id_prestacion, 1);
            $procedimiento_evaluacion = $model_prestacion->GetProcedimientosByIdPrestacionydiagEvaluacion($id_prestacion, 1, $id_evaluacion);
          endif;
      elseif ($prestacion->id_tipo_atencion == 3):

        $model_hospitalizacion = new \App\Models\Hospitalizacione;
        $hospitalizacion = $model_hospitalizacion->getById($prestacion->id_ext);
        $model_emergencia = new \App\Models\Emergencia;
        $datoadicional = $model_emergencia->GetObtenerCama($hospitalizacion->id_cama_estado);
        $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
        $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
        $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 1);
        if($id_evaluacion==0):
          $interconsulta = $model_interconsulta->GetInterconsultaById($id_prestacion,$id_interconsulta);
        else:
          $diagnostico_evaluacion = $model_evaluacion->GetDiagnosticoByIdEvaluacion($id_evaluacion);
          $procedimiento_evaluacion = $model_prestacion->GetProcedimientosByIdPrestacionydiagEvaluacion($id_prestacion, 1, $id_evaluacion);
          $interconsulta = $model_interconsulta->GetInterconsultaByIdEvaluacion($id_prestacion,$id_evaluacion,$id_interconsulta);
        endif;
      else: //ambulatorio, hospitalizacion
        $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
        $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
        $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 1);   
        $interconsulta = $model_interconsulta->GetInterconsultaById($id_prestacion,$id_interconsulta);
      endif;
    endif;

    $model_asegurado= new Asegurado();
    $dato_titular = $model_asegurado->Busca_datos_titular($prestacion->id_asegurado_titular);

    return view('frontend.interconsultas.mostrar_modal_interconsulta', compact('farmacia', 'prestacion', 'diagnostico', 'procedimiento', 'medicamentos', 'id_prestacion',  'flag_pendinte_firma','interconsulta','datoadicional','obtenerdato','dato_titular','diagnostico_evaluacion','procedimiento_evaluacion','id_evaluacion')); 

  }

  public function mostrar_interconsulta_rpta($id_interconsulta, $id_prestacion, $id_evaluacion){
    
    $model_prestacion = new \App\Models\Prestaciones;
    $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
    $flag_bloqueo_atencion = $prestacion ? DB::table('asegurados')->where('id', $prestacion->id_asegurado)->value('flag_bloqueo_atencion') : null;
    if ($flag_bloqueo_atencion !== null && intval($flag_bloqueo_atencion) === 1) :
      return response()->json(['message' => 'No puede ver la atención de este paciente.'], 403);
    endif;

    $modelfarma = new Farmacia;
    $model_establecimiento = new \App\Models\Establecimiento;
    $model_interconsulta = new \App\Models\Interconsulta;
    $model_evaluacion = new \App\Models\Evaluacione;
    
    $farmacia = $modelfarma->GetFarmaByUser(Auth::user()->id);
    $id_farmacia = $farmacia->id;
    $flag_receta_dias = $farmacia->flag_receta_dias;

    
    $date = Carbon::now();
    $fecha_actual = $date->format('d-m-Y');
    $prestacion = null; //$model_prestacion->GetPrestacionById($id_prestacion);
    
    
    $diagnostico = $model_interconsulta->GetDiagnosticoByIdInterconsulta( $id_interconsulta);
    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionydiagInterconsulta($id_prestacion,1, $id_interconsulta);
    $pendiente_firma = $model_prestacion->GetDocumentosPendintesFirmarPrestacionId($id_prestacion);
    $flag_pendinte_firma = (count($pendiente_firma) > 0) ? 1 : 0;

    $id_ipress = $farmacia->id_establecimiento;
    $establecimiento = $model_establecimiento->GetById($id_ipress);
    if ($id_ipress == 1) {
      $nivel = 2;
    } else {
      $nivel = $establecimiento[0]->nivel;
    }
    
    $farmacias = $modelfarma->GetFarmaciaByIdEstablecimiento($id_ipress);
    $establecimientos = $model_establecimiento->ShowAllEstablecimientos();
    if($id_evaluacion==0):
      $interconsulta = $model_interconsulta->GetInterconsultaById($id_prestacion,$id_interconsulta);
    else:
      $interconsulta = $model_interconsulta->GetInterconsultaByIdEvaluacion($id_prestacion,$id_evaluacion,$id_interconsulta);
    endif;
    return view('frontend.interconsultas.mostrar_modal_interconsulta_rpta', compact('id_interconsulta','farmacia', 'prestacion', 'diagnostico', 'procedimiento', 'medicamentos', 'id_prestacion',  'farmacias', 'nivel', 'flag_pendinte_firma','establecimientos','interconsulta'));

  }

  public function obtener_interconsultas_pendientes() {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $model_medico = new Medico();
    $medico = $model_medico->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni, Auth::user()->id_establecimiento);
    $datos = null;
    if (count($medico) > 0):
      $interconsulta = new Interconsulta();
      $datos = $interconsulta->GetInterconsultasPendientesByIdMedicoTratante($medico->id);
      return (count($datos) > 0) ? view('frontend.interconsultas.modal_interconsulta_pendiente', compact('datos')) : "";
    else:
      return "";
    endif;
  }

  public function obtener_cantidad_interconsultas_pendientes_by_user() {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $respuesta = null;
    $model_medico = new Medico();
    $id_establecimiento = Auth::user()->id_establecimiento;
    $medico = $model_medico->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni, $id_establecimiento);

    if (count($medico) > 0):
      $interconsulta = new Interconsulta();
      $id_servicio = Auth::user()->id_consultorio;
      $has_todo_servicios = Auth::user()->flagservicios;
      if (!empty($id_servicio) && intval($id_servicio) > 0):
        $respuesta = $interconsulta->GetInterconsultasPendientesByIdServicio($id_establecimiento, $id_servicio);

      elseif (!empty($has_todo_servicios) && intval($has_todo_servicios) == 1):
        $respuesta = $interconsulta->GetInterconsultasPendientesByIdServicio($id_establecimiento, 0);
      endif;
    endif;

    return $respuesta;
  }

  public function getConsultorioByPrestacionId($prestacionId)
  {
    $interconsultaModel = new Interconsulta;
    $interconsultas = $interconsultaModel->getConsultorioByPrestacionId($prestacionId);
    return response()->json($interconsultas);
  }

  public function updateCodeSiteds(Request $request, $interconsulta_id)
  {
    $interconsulta = Interconsulta::where("id", $interconsulta_id);
    $interconsulta->update([
      "cod_siteds" => $request->input('cod_siteds'),
      "updated_at_cod_siteds" => Carbon::now(),
      "updated_us_cod_siteds" => Auth::user()->id,
    ]);

    return response()->json([
      'interconsulta_id' => $interconsulta_id,
      'message' => 'Código SITEDS actualizado correctamente'
    ], 200);
  }

  public function cargarUpsses()
    {
        try {
            // Realizamos la consulta a la base de datos para obtener los registros
            $result = DB::table('upsses')
            ->whereIn('codigo', ['241100', '240200']) // Filtramos solo los códigos específicos
            ->where('estado', '1') // Filtramos por estado
            ->select('codigo', 
                DB::raw("REPLACE(REPLACE(descripcion_upss, 'HOSPITALIZACIÓN QUIRÚRGICA-', ''), 'CONSULTA EXTERNA-', '') as descripcion_upss") // Eliminamos la parte no deseada
            )
            ->get();

            // Retornamos la respuesta en formato JSON
            return response()->json($result);
        } catch (\Exception $e) {
            // Si ocurre un error, lo capturamos y respondemos con el mensaje de error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

  public function interconsulta_obtener_cantidad($id_establecimiento, $id_servicio, $id_tipo_atencion)
  {
    $interconsultas = DB::table('interconsultas')
      ->whereRaw('CAST(created_at AS DATE) = cast(now() as date)')
      ->where('id_establecimiento', $id_establecimiento)
      ->where('id_servicio', $id_servicio)
      ->where('id_tipo_atencion', $id_tipo_atencion)
      ->where('id_estado_reg', 1)
      ->select(
        DB::raw("count(id) as cantidad")
        , DB::raw("CAST(now() as date) as fecha_actual")
        , DB::raw("EXTRACT(DOW FROM NOW()) AS dia_semana_numero")
        , DB::raw("TO_CHAR(NOW(), 'HH24:MI:SS') AS hora_actual")
      )
      ->first();

    return json_encode($interconsultas);
  }

  public function interconsulta_obtener_dia_semana_json()
  {
    $data = DB::select("SELECT EXTRACT(DOW FROM NOW()) AS dia_semana_numero");
    return json_encode($data[0]);
  }

  public function cargarUpsDIAG()
    {
        try {
          
            $result1 = DB::table('upsses')
              ->whereIn('codigo', ['080000', '080100', '080200', '080300', '080400', '080503', '080600', '080700', '080800', '080900']) 
              ->orderBy('codigo', 'asc') 
              ->get(['codigo', 'descripcion_upss']); 

            return response()->json($result1);
        } catch (\Exception $e) {
            // Si ocurre un error, lo capturamos y respondemos con el mensaje de error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function anular_interconsulta(Request $request)
    {
        if (!Auth::check()) :
          return redirect('/auth/login');
        endif;
        
        /*
        * flag_rpta : 0 default , 1 Respondido con firma
        * fecha_rpta_atencion : fecha de respuesta (No necesariamente firmado)
        * */
        $id_interconsulta = $request->id_interconsulta;
        $interconsulta = Interconsulta::find($id_interconsulta);

        if ($interconsulta->fecha_rpta_atencion != null) {
          // Interconsulta ya respondida
          return "2";
        } elseif ($interconsulta->id_estado_reg == 0) {
          // Ya se encuentra anulada
          return "0";
        } else {
          $interconsulta->id_estado_reg = 0;
          $interconsulta->deleted_at = Carbon::now();
          $interconsulta->deleted_us = Auth::user()->id;
          $interconsulta->deleted_motivo = $request->motivo_anula_interconsulta;
          if ($interconsulta->save()) {
            return "1";
          } else {
            return "3";
          }
        }
    }

    public function anular_interconsulta2(Request $request)
    {
      if (!Auth::check()) :
        return redirect('/auth/login');
      endif;
      
      /*
      * flag_rpta : 0 default , 1 Respondido con firma
      * fecha_rpta_atencion : fecha de respuesta (No necesariamente firmado)
      * */
      $id_user = Auth::user()->id;
      $interconsulta = Interconsulta::find($request->interconsulta_id);
      if ($interconsulta) {
        if ($interconsulta->fecha_rpta_atencion != null) {
          // Interconsulta ya respondida
          return response()->json([
            'success' => false,
            'message' => 'La Interconsulta ya fue respondida, no es posible anularla.'
          ]);
          // Session::flash('flash_warning', 'La Interconsulta ya fue respondida, no es posible anularla.');
        } elseif ($interconsulta->id_estado_reg == 0) {
          // Ya se encuentra anulada
          return response()->json([
            'success' => false,
            'message' => 'La Interconsulta ya fue anulada en otra oportunidad, no es posible anularla.'
          ]);
          // Session::flash('flash_warning', 'La Interconsulta ya fue anulada en otra oportunidad, no es posible anularla.');
        } else {
          $interconsulta->id_estado_reg = 0;
          $interconsulta->deleted_at = Carbon::now();
          $interconsulta->deleted_us = Auth::user()->id;
          $interconsulta->deleted_motivo = $request->interconsulta_motivo_anula;
          if ($interconsulta->save()) {
            return response()->json([
              'success' => true,
              'message' => 'La Interconsulta fue anulada correctamente.'
            ]);
            // Session::flash('flash_warning', 'La Interconsulta fue anulada correctamente.');
          } else {
            return response()->json([
              'success' => false,
              'message' => 'La Interconsulta no pudo ser anulada, intente nuevamente.'
            ]);
          }
        }
      } else {
        return response()->json([
          'success' => false,
          'message' => 'La Interconsulta no se ha encontrado (' . $request->interconsulta_id . ').'
        ]);
        // Session::flash('flash_warning', 'La Interconsulta no se ha encontrado (' . $request->interconsulta_id . ').');
      }
    }
}