<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Kardexe;
use App\Models\Medico_consultorio;
use stdClass;
use App\Models\Asegurado;
use App\Models\Cama;
use App\Models\Consejeria;
use App\Models\Consultorio;
use App\Models\Diagnostico;
use App\Models\Farmacia;
use App\Models\Hospitalizacione;
use App\Models\Emergencia;
use App\Models\Establecimiento;
use App\Models\Medico;
use App\Models\Prestacion_procedimiento;
use App\Models\Prestaciones;
use App\Models\Producto;
use App\Models\Receta_diagnostico;
use App\Models\tipoDocNati;
use App\Models\Triaje;
use App\Models\Upss;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session as FacadesSession;
use QrCode;
use Archive7z\Archive7z;
use Maatwebsite\Excel\Facades\Excel;

class ConsejeriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id_hospitalizacion)
    {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $hospitalizacion = Hospitalizacione::where('id', $id_hospitalizacion)->first();
        //dd($verificar );
        
        $estado = $hospitalizacion->estado;
        $model_medicos = new Medico();
        //$medico = $model_medicos->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni, Auth::user()->id_establecimiento);
        // $medico = $model_medicos->GetDatosMedicoByDniAndEstablecimientoSaenzLeguia(Auth::user()->dni, [77, 76]);
        $medico = $model_medicos->GetDatosMedicoByDni(Auth::user()->dni);
        $id_user = Auth::user()->id;
        $id_farmacia = Auth::user()->id_farmacia;
        $model_consultorio = new Consultorio();
        $model_upss = new Upss();

        if (is_object($hospitalizacion)):
            if (is_object($medico)):
                
                $model_hospitalizacion = new Hospitalizacione;
                $modelfarma = new Farmacia();
                $modelcama = new Cama();
                $farmacias = $modelfarma->GetFarmaByUser($id_user);
                $id_ipress = $farmacias->id_establecimiento;
                $cita = $model_hospitalizacion->GetBuscaHospitalizado($id_hospitalizacion, $medico->id, $estado);
                // $model_kardex = new Kardexe();
                // $fechaServidor = $model_kardex->getFechaServidor();
                $date = Carbon::now();
                $fechaServidor = $date->format('Y-m-d H:i:s');
                $hora = $date->format('H:i');
                
                $cuartos = $modelcama->GetAllPabellonesYServicioOSalaYCuartos($hospitalizacion->id_establecimiento,'HOSPITALIZACION');
                $cama = null;
                if (isset($hospitalizacion) && isset($hospitalizacion->id_cama_estado)) {
                  $cama = $model_hospitalizacion->GetObtenerCama($hospitalizacion->id_cama_estado);
                }
                // Servicio Encargado
                $model_consultorio = new \App\Models\Consultorio;
                //$res_servicio = $model_consultorio->getConsultorioPorEstablecimiento($id_ipress, $hospitalizacion->id_servicio_encargado);
                //$servicio = $res_servicio[0];
                $servicio = $model_consultorio->GetNombreById($hospitalizacion->id_servicio_encargado);

                // Especialidades del Profesional
                $medico_consultorio = new Medico_consultorio();
                $consultoriomedico = $medico_consultorio->GetConsultorioByIdIpressAndMedico($medico->id_establecimiento, $medico->id);
                //dd($consultoriomedico);
                $consultorios_medicos = array();
                foreach ($consultoriomedico as $cm) {
                  $consultorio_por_establecimiento = $model_consultorio->getConsultorioPorEstablecimiento($id_ipress, $cm->id_consultorio);
                  $cm->nombre_consultorio = isset($consultorio_por_establecimiento[0]->nombre) ? $consultorio_por_establecimiento[0]->nombre : null;
                  if (isset($consultorio_por_establecimiento[0]->nombre) && $cm->id_consultorio !== 76 && $cm->id_consultorio !== 105) {
                    $consultorios_medicos[] = $cm;
                  }
                }
                
                $controller_consultorio = new \App\Http\Controllers\ConsultorioController();
                $upsses = $controller_consultorio->obtener_upss($hospitalizacion->id_servicio_encargado, 3);

                $upsses_hos = $controller_consultorio->obtener_upss_hos($hospitalizacion->id_servicio_encargado);

                $model_farma = new Farmacia();
                $model_triaje = new Triaje();
                $farmacia = $model_farma->GetFarmaByIdfarma($id_farmacia);
                $id_ipress = $farmacia->id_establecimiento;

                $model_producto = new Producto();
                $model_establecimiento = new \App\Models\Establecimiento;
                $establecimiento = $model_establecimiento->GetById($id_ipress);
                $establecimientos = $model_establecimiento->ShowAllEstablecimientos();
                $tipo_atencion = $model_farma->GetFarmaciaTipoAtencionByIdFarmacia($id_farmacia);

                if ($id_ipress == 1) {
                $nivel = 2;
                } else {
                $nivel = $establecimiento[0]->nivel;
                }

                //$producto = $model_producto->getProductosByNivelAndIdServicio($nivel, $id_ipress, 19);
                //$consultorio = $model_consultorio->ShowAllConsultoriosSubConsultorio($id_ipress);
                //$farmacias = $model_farma->GetFarmaciasHospitalizacion(Auth::user()->id_establecimiento);

                $id_cita = $id_hospitalizacion;
                $id_tipo_atencione = 3;

                $triaje = null;

                //$modalidad = 1;
                $flag = 0;

                $flag_no_encontrado = 0;  
                $model_asegurado= new Asegurado();
                if($cita->id_asegurado_titular!=null || $cita->id_asegurado_titular>0):
                $dato_titular = $model_asegurado->Busca_datos_titular($cita->id_asegurado_titular);
                else:
                $flag_no_encontrado=1;
                endif;

                if ($hora > '01:30' & $hora < '07:31'):
                /*echo*/$turno = 'MD';
                else:
                if ($hora > '07:30' & $hora < '13:31'):
                    /*echo*/$turno = 'M';
                else:
                    if ($hora > '13:30' & $hora < '19:31'):
                    /*echo*/$turno = 'T';
                    else:
                    if ($hora > '19:30' & $hora <= '23:59'):
                        /*echo*/$turno = 'N';
                    else:
                        if ($hora >= '00:00' & $hora <= '01:31'):
                        /*echo*/$turno = 'N';
                        endif;
                    endif;
                    endif;
                endif;
                endif;

                $tipo_doc_nati = tipoDocNati::all();

                //$tipo_partos = DB::select("SELECT * FROM tipo_partos tp ");

                // $gestacionModel = new Gestacion();
                // $lastGestacion = null;
                // $lastGestacionArray = $gestacionModel->getLastByAseguradoDNI($cita->nro_doc_ident);
                // if (isset($lastGestacionArray) && count($lastGestacionArray) > 0) {
                // $lastGestacion = $lastGestacionArray[0];
                // }

                //$model_hospitalizacion_atencion = new \App\Models\HospitalizacionAtencione;
                //$h_antecedente = $model_hospitalizacion_atencion->GetByIdAsegurado($hospitalizacion->id_asegurado);

                //$dx_admision = $model_hospitalizacion->GetDiagnosticosAdmisionById($id_hospitalizacion);

                $model_intraoperatorio = new \App\Models\IntraOperatorio();
                $model_consejeria = new \App\Models\Consejeria;
                $model_receta_diagnostico = new Receta_diagnostico();
                $model_prestacion_procedimiento = new Prestacion_procedimiento();


                $ultima_atencion = $model_intraoperatorio->getUltimaAtencionXAtencion($id_hospitalizacion, 3);

                $model_prestacion = new \App\Models\Prestaciones;

                $diagnostico = [];
                $procedimiento = [];

                // $atencion = $model_consejeria->getAtencionById($id_hospitalizacion);
                Log::info(["ultima_atencion" => $ultima_atencion]);
                if (isset($ultima_atencion) && isset($ultima_atencion->id_prestacion)) {
                  $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacionEmergencia($ultima_atencion->id_prestacion);
                  $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionYTipo($ultima_atencion->id_prestacion, 1);
                } else {
                  $history_consejerias = $model_consejeria->getByHospitalizacionId($id_hospitalizacion);

                  if (isset($history_consejerias) && count($history_consejerias) > 0) {
                    $array_consejerias_id = [];
                    foreach ($history_consejerias as $key => $value) {
                      $array_consejerias_id[] = $value->id;
                    }
                    $diagnosticos_history = $model_receta_diagnostico->getDiagnosticosByArrayConsejeriaId($array_consejerias_id);
                    if (isset($diagnosticos_history) && count($diagnosticos_history) > 0) {
                      $diagnostico = array_merge($diagnostico, $diagnosticos_history);
                    }

                    $procedimientos_history = $model_prestacion_procedimiento->getByArrayConsejeriaId($array_consejerias_id);
                    if (isset($procedimientos_history) && count($procedimientos_history) > 0) {
                      $procedimiento = array_merge($procedimiento, $procedimientos_history);
                    }
                  } 

                }
                
                $diagnostico_model = new Diagnostico();
                if(isset($hospitalizacion->id_diagnostico_inicial)){
                  $diagnostico_inicial = $diagnostico_model->getDiagnosticoOnCreateConsejeriaById($hospitalizacion->id_diagnostico_inicial);
                  if (isset($diagnostico_inicial)) {
                    $diagnostico[] = $diagnostico_inicial;
                  }
                }

                if(isset($hospitalizacion->id_dx_2)){
                  $dx_2 = $diagnostico_model->getDiagnosticoOnCreateConsejeriaById($hospitalizacion->id_dx_2);
                  if (isset($dx_2)) {
                    $diagnostico[] = $dx_2;
                  }
                }

                if(isset($hospitalizacion->id_dx_3)){
                  $dx_3 = $diagnostico_model->getDiagnosticoOnCreateConsejeriaById($hospitalizacion->id_dx_3);
                  if (isset($dx_3)) {
                    $diagnostico[] = $dx_3;
                  }
                }

                // $dx_traslado = null;
                // if(is_object($ultima_atencion)):
                // if ($hospitalizacion->estado == 5) {
                // Diagnosticos de Traslado
                //     $dx_traslado = $model_hospitalizacion->GetDiagnosticosTraslado($ultima_atencion->id_prestacion, $ultima_atencion->id_evolucion);
                // }
                // endif;

                return view('frontend.hospitalizacion.consejeria.form_atencion_consejeria',
                compact(
                    'upsses',
                    'fechaServidor',
                    'cita',
                    'tipo_atencion',  
                    //'farmacias',
                    'nivel',
                    'medico',
                    'id_servicio',
                    'id_cita',
                    'id_tipo_atencione',
                    'establecimientos',
                    'codigo_upss',
                    //'modalidad',
                    'flag',
                    'hora',
                    'turno',
                    'hospitalizacion',
                    'dato_titular',
                    'flag_no_encontrado',
                    //'tipo_doc_nati',
                    //'tipo_partos',
                    //'lastGestacion', 
                    //'signo_vital', 
                    //'h_antecedente', 
                    'cama', 
                    'diagnostico',
                    'procedimiento',
                    'atencion',
                    'servicio',
                    'consultoriomedico',
                    'consultorios_medicos',
                    'upsses_hos'
                    //'dx_admision', 
                    //'dx_traslado'
                )
                );

            else:
                //return redirect('/hospitalizado_by_atender/')->withInput()->withErrors(array('Medico no encontrado ..!'));
                return redirect('/all_consejeria')->withFlashDanger("Tiene que ser médico de este establecimiento; para entrar a esta opción: coordinar con el administrador del Sistema");
            endif;
        // else:
        //     return redirect('/hospitalizado_by_atender/')->withInput()->withErrors(array('Elemento no encontrado ..!'));
        endif;

    }

    public function create_consejeria_emergencia($id_emergencia){
      if (!Auth::check()):
        return redirect('/auth/login');
      endif;

      $emergencia = Emergencia::where('id', $id_emergencia)->first();
      $estado = $emergencia->estado;
      $model_medicos = new Medico();
      //$medico = $model_medicos->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni, Auth::user()->id_establecimiento);
      // $medico = $model_medicos->GetDatosMedicoByDniAndEstablecimientoSaenzLeguia(Auth::user()->dni, [77, 76]);
      $medico = $model_medicos->GetDatosMedicoByDni(Auth::user()->dni);
      $id_user = Auth::user()->id;
      $id_farmacia = Auth::user()->id_farmacia;
      $model_consultorio = new Consultorio();
      $model_upss = new Upss();

          if (is_object($emergencia)):
            if (is_object($medico)):

              $model_emergencia = new Emergencia;
              $modelfarma = new Farmacia();
              $modelcama = new Cama();
              $farmacias = $modelfarma->GetFarmaByUser($id_user);
              $id_ipress = $farmacias->id_establecimiento;
              $cita = $model_emergencia->GetBuscaEmergencia($id_emergencia, $medico->id, $estado);

              $date = Carbon::now();
              $fechaServidor = $date->format('Y-m-d H:i:s');
              $hora = $date->format('H:i');

              $cuartos = $modelcama->GetAllPabellonesYServicioOSalaYCuartos($emergencia->id_establecimiento,'EMERGENCIA');
              $cama = $model_emergencia->GetObtenerCama($emergencia->cama_estado);
              // $res_servicio = $model_consultorio->ShowConsultoriosAllX2( $emergencia->id_triaje, $emergencia->id_establecimiento);
              //$servicio = $res_servicio[0];
           
              $model_consultorio = new \App\Models\Consultorio;
              $res_servicio = $model_consultorio->getConsultorioPorEstablecimiento2($emergencia->id_establecimiento, $id_emergencia);
              $servicio = $res_servicio[0];

              $medico_consultorio = new Medico_consultorio();
              $consultoriomedico = $medico_consultorio->GetConsultorioByIdIpressAndMedico($medico->id_establecimiento, $medico->id);

              $consultorios_medicos = array();
              foreach ($consultoriomedico as $cm) {
                $consultorio_por_establecimiento = $model_consultorio->getConsultorioPorEstablecimiento($id_ipress, $cm->id_consultorio);
                $cm->nombre_consultorio = isset($consultorio_por_establecimiento[0]->nombre) ? $consultorio_por_establecimiento[0]->nombre : null;
                if (isset($consultorio_por_establecimiento[0]->nombre) && $cm->id_consultorio !== 76 && $cm->id_consultorio !== 105) {
                  $consultorios_medicos[] = $cm;
                }
              }

              $controller_consultorio = new \App\Http\Controllers\ConsultorioController();
              $upsses = $model_upss->ShowConsultoriosUpss2($emergencia->id, 2);

              $upsses2 = $controller_consultorio->obtener_upss2($emergencia->id_servicio);

              $model_farma = new Farmacia();
              $model_triaje = new Triaje();
              $farmacia = $model_farma->GetFarmaByIdfarma($id_farmacia);
              $id_ipress = $farmacia->id_establecimiento;

              $model_producto = new Producto();
              $model_establecimiento = new \App\Models\Establecimiento;
              $establecimiento = $model_establecimiento->GetById($id_ipress);
              $establecimientos = $model_establecimiento->ShowAllEstablecimientos();
              $tipo_atencion = $model_farma->GetFarmaciaTipoAtencionByIdFarmacia($id_farmacia);

              if ($id_ipress == 1) {
                $nivel = 2;
              } else {
                $nivel = $establecimiento[0]->nivel;
              }

              $id_cita = $id_emergencia;
              $id_tipo_atencione = 2;

              $triaje = null;
              $flag = 0;
              $flag_no_encontrado = 0; 

              $model_asegurado= new Asegurado();
              if($cita->id_asegurado_titular!=null || $cita->id_asegurado_titular>0):
                $dato_titular = $model_asegurado->Busca_datos_titular($cita->id_asegurado_titular);
              else:
                $flag_no_encontrado=1;
              endif;

              if ($hora > '01:30' & $hora < '07:31'):
                /*echo*/$turno = 'MD';
                else:
                if ($hora > '07:30' & $hora < '13:31'):
                    /*echo*/$turno = 'M';
                else:
                    if ($hora > '13:30' & $hora < '19:31'):
                    /*echo*/$turno = 'T';
                    else:
                    if ($hora > '19:30' & $hora <= '23:59'):
                        /*echo*/$turno = 'N';
                    else:
                        if ($hora >= '00:00' & $hora <= '01:31'):
                        /*echo*/$turno = 'N';
                        endif;
                    endif;
                    endif;
                endif;
                endif;

                $tipo_doc_nati = tipoDocNati::all();
                $model_intraoperatorio = new \App\Models\IntraOperatorio();
                $model_consejeria = new \App\Models\Consejeria;
                $model_receta_diagnostico = new Receta_diagnostico();
                $model_prestacion_procedimiento = new Prestacion_procedimiento();

                $ultima_atencion_emergencia = $model_intraoperatorio->getUltimaAtencionXAtencion2($id_emergencia, 2);
                $model_prestacion = new \App\Models\Prestaciones;

                $diagnostico = [];
                $procedimiento = [];
                
                //Log::info(message: ["ultima_atencion_emergencia" => $ultima_atencion_emergencia]);
                if (isset($ultima_atencion_emergencia) && isset($ultima_atencion_emergencia->id_prestacion)) {
                  $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacionEmergencia($ultima_atencion_emergencia->id_prestacion);
                  $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionYTipo($ultima_atencion_emergencia->id_prestacion, 1);
                } else {
                  $history_consejerias = $model_consejeria->getByEmergenciaId($id_emergencia);
                
                  if (isset($history_consejerias) && count($history_consejerias) > 0) {
                    $array_consejerias_id = [];
                    foreach ($history_consejerias as $key => $value) {
                      $array_consejerias_id[] = $value->id;
                    }

                    $diagnosticos_history = $model_receta_diagnostico->getDiagnosticosByArrayConsejeriaId($array_consejerias_id);
                    if (isset($diagnosticos_history) && count($diagnosticos_history) > 0) {
                       $diagnostico = array_merge($diagnostico, $diagnosticos_history);
                    }

                    $procedimientos_history = $model_prestacion_procedimiento->getByArrayConsejeriaId($array_consejerias_id);
                    if (isset($procedimientos_history) && count($procedimientos_history) > 0) {
                       $procedimiento = array_merge($procedimiento, $procedimientos_history);
                    }
                  }
                }
                
                $diagnostico_model = new Diagnostico();
                if(isset($emergencia->id_diag_cab)){
                  $id_diag_cab = $diagnostico_model->getDiagnosticoOnCreateConsejeriaById($emergencia->id_diag_cab);
                  if (isset($id_diag_cab)) {
                    $diagnostico[] = $id_diag_cab;
                  }
                }

                if(isset($emergencia->id_diag_cuer1)){
                  $id_diag_cuer1 = $diagnostico_model->getDiagnosticoOnCreateConsejeriaById($emergencia->id_diag_cuer1);
                  if (isset($id_diag_cuer1)) {
                  $diagnostico[] = $id_diag_cuer1;
                  }
                }

                if(isset($emergencia->id_diag_cuer2)){
                  $id_diag_cuer2 = $diagnostico_model->getDiagnosticoOnCreateConsejeriaById($emergencia->id_diag_cuer2);
                  if (isset($id_diag_cuer2)) {
                    $diagnostico[] = $id_diag_cuer2;
                  }
                }

          return view('frontend.emergencia.consejeria.form_atencion_consejeria_emergencia',
                      compact('upsses','fechaServidor','cita','tipo_atencion','nivel',
                      'medico','id_servicio','id_cita','id_tipo_atencione','establecimientos','codigo_upss',
                      'flag','hora','turno','emergencia','dato_titular','flag_no_encontrado','cama','diagnostico',
                      'procedimiento','servicio','consultoriomedico','consultorios_medicos','upsses2'));
          else:
            return redirect('/all_consejeria_by_emergencia')->withFlashDanger("Tiene que ser médico de este establecimiento; para entrar a esta opción: coordinar con el administrador del Sistema");
          endif;
        endif;
    }

    public function store(Request $request)
    { 
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        // dd($request);
        $registrado = true;

        try {
          DB::beginTransaction();

          $model_consejeria = new \App\Models\Consejeria();
          $model_intraoperatorio = new \App\Models\IntraOperatorio();
          $ultima_atencion = $model_intraoperatorio->getUltimaAtencionXAtencion($request->id_hospitalizacion, $request->id_tipo_atencion);

          $id_prestacion = null;
          $id_evolucion = null;
          if (isset($ultima_atencion)) {
            if (isset($ultima_atencion->id_prestacion)) {
              $id_prestacion = $ultima_atencion->id_prestacion;
            }
            if (isset($ultima_atencion->id_evolucion)) {
              $id_evolucion = $ultima_atencion->id_evolucion;
            }
          }

          $medico = Medico::where('dni', '=', auth()->user()->dni)->first();
          $fechaHora = $request->fecha_atencion ." ".  $request->hora_atencion;
          
          $datetime = DateTime::createFromFormat('d-m-Y H:i', $fechaHora);
          $fecha_atencion = $datetime->format('Y-m-d H:i:s');

          $upss = Upss::where('id', '=', $request->codigo_upss)->first();

          $arrayRegistro = array();
          $obj = new stdClass();
          $obj->id_prestacion = $id_prestacion;
          $obj->id_hospitalizacion = $request->id_hospitalizacion;
          $obj->id_evolucion = $id_evolucion;
          $obj->id_asegurado = $request->id_asegurado;
          $obj->id_tipo_atencion = $request->id_tipo_atencion;
          $obj->id_establecimiento = auth()->user()->id_establecimiento;
          $obj->id_servicio = $request->id_servicio_encargado;
          $obj->id_medico = $medico->id;
          $obj->fecha_atencion = $fecha_atencion;
          $obj->upss = $upss->codigo;
          $obj->id_servicio_especialista = $request->especializacion_medico;
          $obj->upss_especialista = $request->codigo_upss_profesional;
          $obj->observacion = $request->observacion;

          $arrayRegistro[] = $obj;

          $res_insert_consejeria = $model_consejeria->insertConsejeria($arrayRegistro);

          if ($res_insert_consejeria->id > 0) {
            $registrado = true;
          
            //============ Diagnosticos Adicionales ==================
            $p_diagnostico = "";
            $p_diagnostico .= "{";
            if (isset($request->id_diagnostico)):
              foreach ($request->id_diagnostico as $key => $value) :
                if ($request->id_diagnostico[$key] != "") {
                  $p_diagnostico .= "{";
                  $p_diagnostico .= $request->id_diagnostico[$key] . ",";
                  $p_diagnostico .= (isset($request->id_tipo_diagnostico[$key]) && $request->id_tipo_diagnostico[$key] > 0) ? $request->id_tipo_diagnostico[$key] : "0";
                  $p_diagnostico .= ",";
                  $p_diagnostico .= $request->id_categoria_diagnostico[$key] . ", 8";
                  $p_diagnostico .= "},";
                }
              endforeach;
              if (strlen($p_diagnostico) > 1) $p_diagnostico = substr($p_diagnostico, 0, -1);
            endif;
            $p_diagnostico .= "}";
            if (isset($request->id_diagnostico)):
              $res_diagnostico = $model_consejeria->addDiagnostico($id_prestacion, $p_diagnostico, $request->id_user,$res_insert_consejeria->id);
              Log::info('Agregando Diagnosticos Consejeria', ['addDiagnostico' => $res_diagnostico]);
            endif;
            //============================================================
            
            //=========== Procedimientos Adicionales =====================
            $p_procedimiento = "";
            $p_procedimiento .= "{";
            if (isset($request->id_procedimiento)) :
              foreach ($request->id_procedimiento as $key => $value) :
                if ($request->id_procedimiento[$key] != "") {
                  $p_procedimiento .= "{";
                  $p_procedimiento .= $request->id_procedimiento[$key] . ",";
                  $p_procedimiento .= (isset($request->procedimiento_cantidad[$key]) && $request->procedimiento_cantidad[$key] > 0) ? $request->procedimiento_cantidad[$key] : "0";
                  $p_procedimiento .= ",";
                  $p_procedimiento .= isset($request->miSelect[$key]) ? $request->miSelect[$key] : 0;
                  $p_procedimiento .= "},";
                }
              endforeach;
              if (strlen($p_procedimiento) > 1) $p_procedimiento = substr($p_procedimiento, 0, -1);
            endif;
            $p_procedimiento .= "}";
            if (isset($request->id_procedimiento)):
              $res_procedimiento = $model_consejeria->addProcedimiento($id_prestacion, $p_procedimiento, $request->id_user, $res_insert_consejeria->id);
              Log::info('Agregando Procedimientos Consejeria', ['addProcedimiento' => $res_procedimiento]);
            endif;

            //insertar procedimientos x diagnosticos de consejeria
            if(isset($request->id_procedimiento)):
              if(isset($request->miSelect)):
                $arrayprocedimientos=$request->id_procedimiento;
                $arraydiagnosticos=$request->miSelect;
                for($iterador=0;$iterador<count($arrayprocedimientos);$iterador++){

                  $idprocedimiento= $arrayprocedimientos[$iterador];
                
                  $iddiagnostico = $arraydiagnosticos[$iterador] ? $arraydiagnosticos[$iterador] : null;

                  $idprestacionxDiag = null;
                  if (isset($ultima_atencion->id_prestacion)) {
                    $idprestacionxDiag = $ultima_atencion->id_prestacion;
                  }
                  $mensajeSucess = $model_consejeria->RegistrarProcedimientoxDiagnostico($idprestacionxDiag,$idprocedimiento,$iddiagnostico,1, $res_insert_consejeria->id);  
                  Log::info('Agregando Procedimientos Diagnosticos Consejeria', ['RegistrarProcedimientoxDiagnostico' => $mensajeSucess]);
                }
        
              endif;
            endif;
            //============================================================
            DB::commit();

            FacadesSession::flash('flash_message', 'Registro exitoso');
            return redirect("/ver_atencion_consejeria/$res_insert_consejeria->id/$request->id_hospitalizacion");
            
          } else {
            $registrado = false;
            DB::rollback();
            FacadesSession::flash('flash_message', 'No se pudo registrar, hubo un problema con la conexión de internet de su establecimiento PNP, vuelva a intentarlo nuevamente');
              return redirect('/all_consejeria');
          }

        } catch (QueryException $e) {
          DB::rollback();
          throw $e;
        } catch (Exception $e) {
          DB::rollback();
          throw $e;
        }
    }

    public function store_emergencia(Request $request)
    {
      if (!Auth::check()):
        return redirect('/auth/login');
      endif;

      $registrado_emergencia = true;

      try {
        DB::beginTransaction();
        $model_consejeria = new \App\Models\Consejeria();
        $model_intraoperatorio = new \App\Models\IntraOperatorio();
        $ultima_atencion_emergencia = $model_intraoperatorio->getUltimaAtencionXAtencion2($request->id_emergencia, $request->id_tipo_atencion);
       
        $id_prestacion = null;
        $id_evolucion = null;

        if (isset($ultima_atencion_emergencia)) {
          if (isset($ultima_atencion_emergencia->id_prestacion)) {
            $id_prestacion = $ultima_atencion_emergencia->id_prestacion;
          }
          if (isset($ultima_atencion_emergencia->id_evolucion)) {
            $id_evolucion = $ultima_atencion_emergencia->id_evolucion;
          }
        }

        $medico = Medico::where('dni', '=', auth()->user()->dni)->first();
        $fechaHora = $request->fecha_atencion ." ".  $request->hora_atencion;

        $datetime = DateTime::createFromFormat('d-m-Y H:i', $fechaHora);
        $fecha_atencion = $datetime->format('Y-m-d H:i:s');

        $upss = Upss::where('id', '=', $request->codigo_upss)->first();

        $arrayRegistro = array();
        $obj = new stdClass();
        $obj->id_prestacion = $id_prestacion;
        $obj->id_emergencia = $request->id_emergencia;
        $obj->id_evolucion = $id_evolucion;
        $obj->id_asegurado = $request->id_asegurado;
        $obj->id_tipo_atencion = $request->id_tipo_atencion;
        $obj->id_establecimiento = auth()->user()->id_establecimiento;
        $obj->id_servicio = $request->id_servicio;
        $obj->id_medico = $medico->id;
        $obj->fecha_atencion = $fecha_atencion;
        $obj->upss = $upss->codigo;
        $obj->id_servicio_especialista = $request->especializacion_medico;
        $obj->upss_especialista = $request->codigo_upss_profesional;
        $obj->observacion = $request->observacion;

        $arrayRegistro[] = $obj;
        $res_insert_consejeria = $model_consejeria->insertConsejeriaEmergencia($arrayRegistro);
        if ($res_insert_consejeria->id > 0) {
          $registrado_emergencia = true;

           //============ Diagnosticos Adicionales ==================
           $p_diagnostico = "";
           $p_diagnostico .= "{";
           if (isset($request->id_diagnostico)):
             foreach ($request->id_diagnostico as $key => $value) :
               if ($request->id_diagnostico[$key] != "") {
                 $p_diagnostico .= "{";
                 $p_diagnostico .= $request->id_diagnostico[$key] . ",";
                 $p_diagnostico .= (isset($request->id_tipo_diagnostico[$key]) && $request->id_tipo_diagnostico[$key] > 0) ? $request->id_tipo_diagnostico[$key] : "0";
                 $p_diagnostico .= ",";
                 $p_diagnostico .= $request->id_categoria_diagnostico[$key] . ", 8";
                 $p_diagnostico .= "},";
               }
             endforeach;
             if (strlen($p_diagnostico) > 1) $p_diagnostico = substr($p_diagnostico, 0, -1);
           endif;
           $p_diagnostico .= "}";
           if (isset($request->id_diagnostico)):
             $res_diagnostico = $model_consejeria->addDiagnostico($id_prestacion, $p_diagnostico, $request->id_user,$res_insert_consejeria->id);
             Log::info('Agregando Diagnosticos Consejeria', ['addDiagnostico' => $res_diagnostico]);
           endif;
           //============================================================
           
           //=========== Procedimientos Adicionales =====================
           $p_procedimiento = "";
           $p_procedimiento .= "{";
           if (isset($request->id_procedimiento)) :
             foreach ($request->id_procedimiento as $key => $value) :
               if ($request->id_procedimiento[$key] != "") {
                 $p_procedimiento .= "{";
                 $p_procedimiento .= $request->id_procedimiento[$key] . ",";
                 $p_procedimiento .= (isset($request->procedimiento_cantidad[$key]) && $request->procedimiento_cantidad[$key] > 0) ? $request->procedimiento_cantidad[$key] : "0";
                 $p_procedimiento .= ",";
                 $p_procedimiento .= isset($request->miSelect[$key]) ? $request->miSelect[$key] : 0;
                 $p_procedimiento .= "},";
               }
             endforeach;
             if (strlen($p_procedimiento) > 1) $p_procedimiento = substr($p_procedimiento, 0, -1);
           endif;
           $p_procedimiento .= "}";
           if (isset($request->id_procedimiento)):
             $res_procedimiento = $model_consejeria->addProcedimiento($id_prestacion, $p_procedimiento, $request->id_user, $res_insert_consejeria->id);
             Log::info('Agregando Procedimientos Consejeria', ['addProcedimiento' => $res_procedimiento]);
           endif;

           //insertar procedimientos x diagnosticos de consejeria
           if(isset($request->id_procedimiento)):
             if(isset($request->miSelect)):
               $arrayprocedimientos=$request->id_procedimiento;
               $arraydiagnosticos=$request->miSelect;
               for($iterador=0;$iterador<count($arrayprocedimientos);$iterador++){

                 $idprocedimiento= $arrayprocedimientos[$iterador];
               
                 $iddiagnostico = $arraydiagnosticos[$iterador] ? $arraydiagnosticos[$iterador] : null;

                 $idprestacionxDiag = null;
                 if (isset($ultima_atencion_emergencia->id_prestacion)) {
                   $idprestacionxDiag = $ultima_atencion_emergencia->id_prestacion;
                 }
                 $mensajeSucess = $model_consejeria->RegistrarProcedimientoxDiagnostico($idprestacionxDiag,$idprocedimiento,$iddiagnostico,1, $res_insert_consejeria->id);  
                 Log::info('Agregando Procedimientos Diagnosticos Consejeria', ['RegistrarProcedimientoxDiagnostico' => $mensajeSucess]);
               }
       
             endif;
           endif;
           //============================================================
           DB::commit();

           FacadesSession::flash('flash_message', 'Registro exitoso');
           return redirect("/ver_atencion_consejeria_emergencia/$res_insert_consejeria->id/$request->id_emergencia");
           
         } else {
           $registrado_emergencia = false;
           DB::rollback();
           FacadesSession::flash('flash_message', 'No se pudo registrar, hubo un problema con la conexión de internet de su establecimiento PNP, vuelva a intentarlo nuevamente');
             return redirect('/all_consejeria_by_emergencia');
         }

       } catch (QueryException $e) {
         DB::rollback();
         throw $e;
       } catch (Exception $e) {
         DB::rollback();
         throw $e;
       }
    }

    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      if (!Auth::check()):
          return redirect('/auth/login');
      endif;

      $consejeria_model = new Consejeria();
      $consultorio_model = new Consultorio();
      $medico_consultorio = new Medico_consultorio();
      $model_medicos = new Medico();
      $receta_diagnostico_model = new Receta_diagnostico();
      $prestacion_procedimiento_model = new Prestacion_procedimiento();
      $model_hospitalizacione = new Hospitalizacione();
      $model_upss = new Upss();

      $controller_consultorio = new ConsultorioController();

      $model_prestacion = new Prestaciones();
      $diagnostico_model = new Diagnostico();

      $consejeria = $consejeria_model->getById($id);

      if (intval($consejeria->consejeria_estado) == 0 || $consejeria->flag_firma || Auth::user()->id != $consejeria->created_us) {
        return redirect('/auth/login');
      }

      //$medico = $model_medicos->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni, Auth::user()->id_establecimiento);
      // $medico = $model_medicos->GetDatosMedicoByDniAndEstablecimientoSaenzLeguia(Auth::user()->dni, [77, 76]);
      $medico = $model_medicos->GetDatosMedicoByDni(Auth::user()->dni);

      $consultoriomedico = $medico_consultorio->GetConsultorioByIdIpressAndMedico($consejeria->id_establecimiento, $medico->id);

      $cama = $model_hospitalizacione->GetObtenerCama($consejeria->hospitalizacion_id_cama_estado);

      $upss_encargado = Upss::where('id_consultorio', $consejeria->hospitalizacion_id_servicio_encargado)->where('id_tipo_atencion', 3)->where('estado', 1)->first();
      //$upss_encargado2 = $model_upss->showconsultoriohospit($consejeria->consejeria_id);

      // Especialidades del Profesional
      $medico_consultorio = new Medico_consultorio();
      $consultoriomedico = $medico_consultorio->GetConsultorioByIdIpressAndMedico($medico->id_establecimiento, $medico->id);

      $consultorios_medicos = array();
      foreach ($consultoriomedico as $cm) {
      $consultorio_por_establecimiento = $consultorio_model->getConsultorioPorEstablecimiento(Auth::user()->id_establecimiento, $cm->id_consultorio);
      $cm->nombre_consultorio = isset($consultorio_por_establecimiento[0]->nombre) ? $consultorio_por_establecimiento[0]->nombre : null;
        if (isset($consultorio_por_establecimiento[0]->nombre) && $cm->id_consultorio !== 76 && $cm->id_consultorio !== 105) {
          $consultorios_medicos[] = $cm;
        }
      }

      $diagnosticos = $receta_diagnostico_model->getDiagnosticosByConsejeriaId($id);
      $procedimientos = $prestacion_procedimiento_model->getByConsejeriaId($id);

      $diagnosticoHistory = [];
      $procedimientoHistory = [];
      if (isset($consejeria->id_prestacion)) {
        $diagnosticoHistory = $model_prestacion->GetDiagnosticoByIdPrestacionEmergencia($consejeria->id_prestacion);
        $procedimientoHistory = $model_prestacion->GetProcedimientosByIdPrestacionYTipo($consejeria->id_prestacion, 1);
      } else {
        $history_consejerias = $consejeria_model->getByHospitalizacionId($consejeria->hospitalizacion_id);

        if (isset($history_consejerias) && count($history_consejerias) > 0) {
          $array_consejerias_id = [];
          foreach ($history_consejerias as $key => $value) {
            if (intval($value->id) !== intval($id)) {
              $array_consejerias_id[] = $value->id;
            }
          }
          Log::info(["array_consejerias_id" => $array_consejerias_id]);
          $diagnosticos_history = $receta_diagnostico_model->getDiagnosticosByArrayConsejeriaId($array_consejerias_id);
          if (isset($diagnosticos_history) && count($diagnosticos_history) > 0) {
            $diagnosticoHistory = array_merge($diagnosticoHistory, $diagnosticos_history);
          }

          $procedimientos_history = $prestacion_procedimiento_model->getByArrayConsejeriaId($array_consejerias_id);
          if (isset($procedimientos_history) && count($procedimientos_history) > 0) {
            $procedimientoHistory = array_merge($procedimientoHistory, $procedimientos_history);
          }
        } 
      }
      
      if(isset($consejeria->hospitalizacion_id_diagnostico_inicial)){
        $diagnostico_inicial = $diagnostico_model->getDiagnosticoOnCreateConsejeriaById($consejeria->hospitalizacion_id_diagnostico_inicial);
        if (isset($diagnostico_inicial)) {
          $diagnosticoHistory[] = $diagnostico_inicial;
        }
      }

      if(isset($consejeria->hospitalizacion_id_dx_2)){
        $dx_2 = $diagnostico_model->getDiagnosticoOnCreateConsejeriaById($consejeria->hospitalizacion_id_dx_2);
        if (isset($dx_2)) {
          $diagnosticoHistory[] = $dx_2;
        }
      }

      if(isset($consejeria->hospitalizacion_id_dx_3)){
        $dx_3 = $diagnostico_model->getDiagnosticoOnCreateConsejeriaById($consejeria->hospitalizacion_id_dx_3);
        if (isset($dx_3)) {
          $diagnosticoHistory[] = $dx_3;
        }
      }

      // dd([
      //   'consejeria' => $consejeria,
      //   'diagnosticos' => $diagnosticos,
      //   'procedimientos' => $procedimientos,
      //   'consultorios_medicos' => $consultorios_medicos,
      //   'cama' => $cama,
      //   'upss_encargado' => $upss_encargado,
      //   'diagnosticoHistory' => $diagnosticoHistory,
      //   'procedimientoHistory' => $procedimientoHistory,
      // ]);

      return view('frontend.hospitalizacion.consejeria.form_editar_consejeria', compact(
        'consejeria',
        'diagnosticos',
        'procedimientos',
        'consultorios_medicos',
        'cama',
        'upss_encargado',
        'upss_encargado2',
        'diagnosticoHistory',
        'procedimientoHistory'
      ));
    }

    public function edit_emergencia($id)
    {
      if (!Auth::check()):
        return redirect('/auth/login');
      endif;

      $consejeria_model = new Consejeria();
      $consultorio_model = new Consultorio();
      $medico_consultorio = new Medico_consultorio();
      $model_medicos = new Medico();
      $receta_diagnostico_model = new Receta_diagnostico();
      $prestacion_procedimiento_model = new Prestacion_procedimiento();
      $model_emergencia = new Emergencia();
      $model_upss = new Upss();

      $controller_consultorio = new ConsultorioController();

      $model_prestacion = new Prestaciones();
      $diagnostico_model = new Diagnostico();

      $consejeria = $consejeria_model->getByIdEmergencia($id);

      if (intval($consejeria->consejeria_estado) == 0 || $consejeria->flag_firma || Auth::user()->id != $consejeria->created_us) {
        return redirect('/auth/login');
      }

      //$medico = $model_medicos->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni, Auth::user()->id_establecimiento);
      $medico = $model_medicos->GetDatosMedicoByDni(Auth::user()->dni);

      $consultoriomedico = $medico_consultorio->GetConsultorioByIdIpressAndMedico($consejeria->id_establecimiento, $medico->id);
      
      $cama = $model_emergencia->GetObtenerCama($consejeria->emergencias_cama_estado);

      $upss_encargado = $model_upss->ShowConsultoriosUpss2($consejeria->emergencia_id, 2);

      $consultorios_medicos = array();
      foreach ($consultoriomedico as $cm) {
        $consultorio_por_establecimiento = $consultorio_model->getConsultorioPorEstablecimiento(Auth::user()->id_establecimiento, $cm->id_consultorio);
        $cm->nombre_consultorio = isset($consultorio_por_establecimiento[0]->nombre) ? $consultorio_por_establecimiento[0]->nombre : null;
        if (isset($consultorio_por_establecimiento[0]->nombre) && $cm->id_consultorio !== 76 && $cm->id_consultorio !== 105) {
          $consultorios_medicos[] = $cm;
        }
      }
      $diagnosticos = $receta_diagnostico_model->getDiagnosticosByConsejeriaId($id);
      $procedimientos = $prestacion_procedimiento_model->getByConsejeriaId($id);
      
      $diagnosticoHistory = [];
      $procedimientoHistory = [];
      if (isset($consejeria->id_prestacion)) {
        $diagnosticoHistory = $model_prestacion->GetDiagnosticoByIdPrestacionEmergencia($consejeria->id_prestacion);
        $procedimientoHistory = $model_prestacion->GetProcedimientosByIdPrestacionYTipo($consejeria->id_prestacion, 1);
      } else {
        $history_consejerias_emergencia = $consejeria_model->getByEmergenciaId($consejeria->emergencias_id);
        //dd($diagnosticoHistory);

        if (isset($history_consejerias_emergencia) && count($history_consejerias_emergencia) > 0) {
          $array_consejerias_id = [];
          foreach ($history_consejerias_emergencia as $key => $value) {
            if (intval($value->id) !== intval($id)) {
              $array_consejerias_id[] = $value->id;
            }
          }
          //Log::info(["array_consejerias_id" => $array_consejerias_id]);
          $diagnosticos_history = $receta_diagnostico_model->getDiagnosticosByArrayConsejeriaId($array_consejerias_id);
          if (isset($diagnosticos_history) && count($diagnosticos_history) > 0) {
            $diagnosticoHistory = array_merge($diagnosticoHistory, $diagnosticos_history);
          }

          $procedimientos_history = $prestacion_procedimiento_model->getByArrayConsejeriaId($array_consejerias_id);
          if (isset($procedimientos_history) && count($procedimientos_history) > 0) {
            $procedimientoHistory = array_merge($procedimientoHistory, $procedimientos_history);
          }
        } 
      }

      if(isset($consejeria->emergencias_id_diag_cab)){
        $id_diag_cab = $diagnostico_model->getDiagnosticoOnCreateConsejeriaById($consejeria->emergencias_id_diag_cab);
        if (isset($id_diag_cab)) {
          $diagnosticoHistory[] = $id_diag_cab;
        }
      }

      if(isset($consejeria->emergencias_id_diag_cuer1)){
        $id_diag_cuer1 = $diagnostico_model->getDiagnosticoOnCreateConsejeriaById($consejeria->emergencias_id_diag_cuer1);
        if (isset($id_diag_cuer1)) {
          $diagnosticoHistory[] = $id_diag_cuer1;
        }
      }

      if(isset($consejeria->emergencias_id_diag_cuer2)){
        $id_diag_cuer2 = $diagnostico_model->getDiagnosticoOnCreateConsejeriaById($consejeria->emergencias_id_diag_cuer2);
        if (isset($id_diag_cuer2)) {
          $diagnosticoHistory[] = $id_diag_cuer2;
        }
      }

      return view('frontend.emergencia.consejeria.form_editar_consejeria_emergencia', compact(
        'consejeria',
        'diagnosticos',
        'procedimientos',
        'consultorios_medicos',
        'cama',
        'upss_encargado',
        'diagnosticoHistory',
        'procedimientoHistory'
      ));
    }

    public function update(Request $request, $id)
    {
      if (!Auth::check()) :
        // return response()->json(["message" => "no tiene session el usuario"], 401);
        return redirect('/auth/login');
      endif;

      $id_prestacion = null;

      $updateConsejeria = Consejeria::find($id);
      if (isset($updateConsejeria->id_prestacion)) {
        $id_prestacion = $updateConsejeria->id_prestacion;
      }

      $fecha_atencion = Carbon::createFromFormat('Y-m-d H:i', "$request->fecha_atencion $request->hora_atencion")->format('Y-m-d H:i:s');

      $updateConsejeria->update([
        'fecha_atencion' => $fecha_atencion,
        'id_servicio_especialista' => $request->especializacion_medico,
        'upss_especialista' => $request->codigo_upss_profesional,
        'updated_us' => Auth::user()->id,
        'updated_at' => Carbon::now(),
        'observacion' => $request->observacion
      ]);

      foreach ($request->diagnosticos as $key => $diagnostico) {
        if (empty($diagnostico['receta_diagnostico_id'])) {
          $newRecetaDiagnostico = Receta_diagnostico::create([
            "id_receta_vale" => $request->id_prestacion,
            "id_diagnostico" => $diagnostico['id_diagnostico'],
            "id_tipo_diagnostico" => $diagnostico['id_tipo_diagnostico'],
            "estado" => 1,
            "id_prestacion" => $id_prestacion,
            "created_at" => Carbon::now(),
            "created_us" => Auth::user()->id,
            "id_evaluacion" => 0,
            "id_interconsulta" => 0,
            "id_categoria" => $diagnostico['id_categoria_diagnostico'],
            "id_consejeria" => $id
          ]);
        } else {
          $updateRecetaDiagnostico = Receta_diagnostico::find($diagnostico['receta_diagnostico_id']);
          $updateRecetaDiagnostico->update([
            'id_diagnostico' => $diagnostico['id_diagnostico'],
            'id_tipo_diagnostico' => $diagnostico['id_tipo_diagnostico'],
            'id_categoria' => $diagnostico['id_categoria_diagnostico'],
            'updated_us' => Auth::user()->id,
            'updated_at' => Carbon::now(),
          ]);
        }
      }

      foreach ($request->procedimientos as $key => $procedimiento) {
        if (empty($procedimiento['prestacion_procedimiento_id'])) {
          $newPrestacionProcedimiento = Prestacion_procedimiento::create([
            "id_prestacion" => $id_prestacion,
            "id_procedimiento" => $procedimiento['id_procedimiento'],
            "cantidad" => $procedimiento['procedimiento_cantidad'],
            "id_estado_reg" => 1,
            "created_at" => Carbon::now(),
            "created_us" => Auth::user()->id,
            "flag_informe" => 0,
            "flag_validated" => 0,
            "id_rua" => 0,
            "id_lab_rad" => 0,
            "id_evaluacion" => 0,
            "tipo_procedimiento" => 1,
            "flag" => 0,
            "total_informe" => 0,
            "tipo_plantilla" => 0,
            "id_diagnostico" => $procedimiento['procedimiento_diagnostico'],
            "id_interconsulta" => 0,
            "id_consejeria" => $id
          ]);
        } else {
          $updatePrestacionProcedimiento = Prestacion_procedimiento::find($procedimiento['prestacion_procedimiento_id']);
          $updatePrestacionProcedimiento->update([
            'id_procedimiento' => $procedimiento['id_procedimiento'],
            'cantidad' => $procedimiento['procedimiento_cantidad'],
            "id_diagnostico" => $procedimiento['procedimiento_diagnostico'],
            'updatet_us' => Auth::user()->id,
            'updated_at' => Carbon::now()
          ]);
        }
      }

      session()->flash('success', "La consejeria ha sido actualizada exitosamente.!");
      return response()->json(["message" => "La consejeria ha sido actualizada exitosamente.!"], 200);
    }

    public function update_emergencia(Request $request, $id)
    {
      if (!Auth::check()) :
        // return response()->json(["message" => "no tiene session el usuario"], 401);
        return redirect('/auth/login');
      endif;

      $id_prestacion = null;

      $updateConsejeria = Consejeria::find($id);
      if (isset($updateConsejeria->id_prestacion)) {
        $id_prestacion = $updateConsejeria->id_prestacion;
      }

      $fecha_atencion = Carbon::createFromFormat('Y-m-d H:i', "$request->fecha_atencion $request->hora_atencion")->format('Y-m-d H:i:s');

      $updateConsejeria->update([
        'fecha_atencion' => $fecha_atencion,
        'id_servicio_especialista' => $request->especializacion_medico,
        'upss_especialista' => $request->codigo_upss_profesional,
        'updated_us' => Auth::user()->id,
        'updated_at' => Carbon::now(),
        'observacion' => $request->observacion
      ]);

      foreach ($request->diagnosticos as $key => $diagnostico) {
        if (empty($diagnostico['receta_diagnostico_id'])) {
          $newRecetaDiagnostico = Receta_diagnostico::create([
            "id_receta_vale" => $request->id_prestacion,
            "id_diagnostico" => $diagnostico['id_diagnostico'],
            "id_tipo_diagnostico" => $diagnostico['id_tipo_diagnostico'],
            "estado" => 1,
            "id_prestacion" => $id_prestacion,
            "created_at" => Carbon::now(),
            "created_us" => Auth::user()->id,
            "id_evaluacion" => 0,
            "id_interconsulta" => 0,
            "id_categoria" => $diagnostico['id_categoria_diagnostico'],
            "id_consejeria" => $id
          ]);
        } else {
          $updateRecetaDiagnostico = Receta_diagnostico::find($diagnostico['receta_diagnostico_id']);
          $updateRecetaDiagnostico->update([
            'id_diagnostico' => $diagnostico['id_diagnostico'],
            'id_tipo_diagnostico' => $diagnostico['id_tipo_diagnostico'],
            'id_categoria' => $diagnostico['id_categoria_diagnostico'],
            'updated_us' => Auth::user()->id,
            'updated_at' => Carbon::now(),
          ]);
        }
      }

      foreach ($request->procedimientos as $key => $procedimiento) {
        if (empty($procedimiento['prestacion_procedimiento_id'])) {
          $newPrestacionProcedimiento = Prestacion_procedimiento::create([
            "id_prestacion" => $id_prestacion,
            "id_procedimiento" => $procedimiento['id_procedimiento'],
            "cantidad" => $procedimiento['procedimiento_cantidad'],
            "id_estado_reg" => 1,
            "created_at" => Carbon::now(),
            "created_us" => Auth::user()->id,
            "flag_informe" => 0,
            "flag_validated" => 0,
            "id_rua" => 0,
            "id_lab_rad" => 0,
            "id_evaluacion" => 0,
            "tipo_procedimiento" => 1,
            "flag" => 0,
            "total_informe" => 0,
            "tipo_plantilla" => 0,
            "id_diagnostico" => $procedimiento['procedimiento_diagnostico'],
            "id_interconsulta" => 0,
            "id_consejeria" => $id
          ]);
        } else {
          $updatePrestacionProcedimiento = Prestacion_procedimiento::find($procedimiento['prestacion_procedimiento_id']);
          $updatePrestacionProcedimiento->update([
            'id_procedimiento' => $procedimiento['id_procedimiento'],
            'cantidad' => $procedimiento['procedimiento_cantidad'],
            "id_diagnostico" => $procedimiento['procedimiento_diagnostico'],
            'updatet_us' => Auth::user()->id,
            'updated_at' => Carbon::now()
          ]);
        }
      }

      session()->flash('success', "La consejeria ha sido actualizada exitosamente.!");
      return response()->json(["message" => "La consejeria ha sido actualizada exitosamente.!"], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function all_consejeria() 
    {
        $id_user = Auth::user()->id;
        $model_upss = new \App\Models\Upss();
        $upss = $model_upss->ShowUPSS_Procedimiento_Hospitalizacion();

        $date = Carbon::now();
        $fechaHasta = $date->format('d-m-Y');
        $fechaDesdex = $date->format('d-m-Y');
        $fechaDesdey = strtotime('-1 day', strtotime($fechaDesdex));
        $fechaDesde = date('d-m-Y', $fechaDesdey);

        $fechaFin = $date->format('d-m-Y');
        $fechaIniciox = $date->format('d-m-Y');
        $fechaInicioy = strtotime('-1 day', strtotime($fechaIniciox));
        $fechaInicio = date('d-m-Y',  $fechaInicioy);


        return view('frontend.consejeria.all',compact('id_user','upss','fechaDesde','fechaHasta','fechaInicio','fechaFin'));
    }

    public function all_consejeria_by_emergencia() 
    {
        $id_user = Auth::user()->id;
        $model_upss = new \App\Models\Upss();
        $upss = $model_upss->ShowUPSS_Procedimiento_Emergencia();
        
        $date = Carbon::now();
        $fechaHasta = $date->format('d-m-Y');
        $fechaDesdex = $date->format('d-m-Y');
        $fechaDesdey = strtotime('-1 day', strtotime($fechaDesdex));
        $fechaDesde = date('d-m-Y', $fechaDesdey);

        $fechaFin = $date->format('d-m-Y');
        $fechaIniciox = $date->format('d-m-Y');
        $fechaInicioy = strtotime('-1 day', strtotime($fechaIniciox));
        $fechaInicio = date('d-m-Y',  $fechaInicioy );

        return view('frontend.consejeria.all_atenciones_emergencia',compact('id_user','upss','fechaDesde','fechaHasta','fechaInicio','fechaFin'));
    }

    public function ver_prestaciones_consejeria($id_hospitalizacion)
    {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $origen = request()->get('origen', 'consejeria');
        
        $hospitalizacion = Hospitalizacione::where('id',$id_hospitalizacion)->first();
        $estado = $hospitalizacion->estado;

        $model_medicos = new Medico();
        $medico = $model_medicos->GetDatosMedicoByDni( Auth::user()->dni);

        $medico_consultorio = new Medico_consultorio();
        $consultoriomedico = $medico_consultorio->ServicioBancoSangre($medico->id_establecimiento, $medico->id);

        if($hospitalizacion != null):
            $id_user = Auth::user()->id;
            $model_hospitalizacione = new Hospitalizacione();
            $paciente = $model_hospitalizacione->GetBuscaPacienteHospitalizacion($hospitalizacion->id, $hospitalizacion->estado);
            $cama = null;
            if (isset($hospitalizacion->id_cama_estado)) {
              $cama = $model_hospitalizacione->GetObtenerCama($hospitalizacion->id_cama_estado);
            }
            $establecimiento = Establecimiento::find($hospitalizacion->id_establecimiento);
            $atenciones = [];
            return view('frontend.consejeria.all_atenciones', compact('origen','hospitalizacion', 'cama', 'atenciones', 'id_user', 'paciente', 'id_hospitalizacion','estado', 'establecimiento','consultoriomedico'));
        else:
            return redirect('/dashboard')->withInput()->withErrors(array('Elemento no encontrado ..!'));
        endif;
    }

    public function ver_prestaciones_emergencia_consejeria($id_emergencia)
    {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $emergencia = Emergencia::where('id',$id_emergencia)->first();
        $estado = $emergencia->estado;
        if($emergencia != null):
          $id_user = Auth::user()->id;
          $model_emergencia = new $emergencia();
          $paciente = $model_emergencia->GetBuscaPacienteEmergencia_cama2($emergencia->id,$emergencia->estado);
          $cama = $model_emergencia->GetObtenerCama($emergencia->cama_estado);
          $atenciones = [];
          return view('frontend.consejeria.all_emergencia_consejeria', compact('emergencia','cama','atenciones','id_user','paciente','id_emergencia','estado'));
        else:
          return redirect('/dashboard')->withInput()->withErrors(array('Elemento no encontrado ..!'));
        endif;
    }

 
    public function all_hospitalizado_consejeria_ajax(Request $request)
    { 
        $model_consejeria = new \App\Models\Consejeria();
        $p[] = Auth::user()->id_establecimiento;
        $p[] = $request->fecha_ini;
        $p[] = $request->fecha_fin;
        $p[] = $request->ndocumento;
        $p[] = $request->id_servicio_destino;
        $p[] = $request->busqueda_datos;
        $p[] = $request->estado;
        $p[] = $request->NumeroPagina;
        $p[] = $request->NumeroRegistros;
        
        $data = $model_consejeria->GetHospitalizadosConsejeria($p);
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

    public function all_hospitalizado_consejeria_ajax2(Request $request)
    { 
        $model_consejeria = new \App\Models\Consejeria();
        $p[] = Auth::user()->id_establecimiento;
        $p[] = $request->fecha_ini;
        $p[] = $request->fecha_fin;
        $p[] = $request->fecha_ini_registro;
        $p[] = $request->fecha_fin_registro;
        $p[] = $request->ndocumento;
        $p[] = $request->id_servicio_destino;
        $p[] = $request->busqueda_datos;
        $p[] = $request->estado;
        $p[] = $request->NumeroPagina;
        $p[] = $request->NumeroRegistros;
        
        $data = $model_consejeria->GetHospitalizadosConsejeria2($p);
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


    public function all_emergencia_consejeria_ajax(Request $request)
    { 
        $model_consejeria = new \App\Models\Consejeria();
        $p[] = Auth::user()->id_establecimiento;
        $p[] = $request->fecha_ini;
        $p[] = $request->fecha_fin;
        $p[] = $request->ndocumento;
        $p[] = $request->id_servicio_destino;
        $p[] = $request->busqueda_datos;
        $p[] = $request->estado;
        $p[] = $request->NumeroPagina;
        $p[] = $request->NumeroRegistros;

        $data = $model_consejeria->GetEmergenciasConsejeria($p);
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

    public function all_emergencia_consejeria_ajax2(Request $request)
    { 
        $model_consejeria = new \App\Models\Consejeria();
        $p[] = Auth::user()->id_establecimiento;
        $p[] = $request->fecha_ini;
        $p[] = $request->fecha_fin;
        $p[] = $request->fecha_ini_registro;
        $p[] = $request->fecha_fin_registro;
        $p[] = $request->ndocumento;
        $p[] = $request->id_servicio_destino;
        $p[] = $request->busqueda_datos;
        $p[] = $request->estado;
        $p[] = $request->NumeroPagina;
        $p[] = $request->NumeroRegistros;

        $data = $model_consejeria->GetEmergenciasConsejeria2($p);
        //dd($data);
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

    public function all_historial_consejeria_ajax(Request $request)
    { 
        $model_consejeria = new \App\Models\Consejeria();
        $p[] = $request->id_hospitalizacion;
        $p[] = $request->fecha_ini;
        $p[] = $request->fecha_fin;
        $p[] = $request->NumeroPagina;
        $p[] = $request->NumeroRegistros;
        
        $data = $model_consejeria->GetHistorialConsejeria($p);
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

    public function all_historial_emergencia_consejeria_ajax(Request $request)
    { 
        $model_consejeria = new \App\Models\Consejeria();
        $p[] = $request->id_emergencia;
        $p[] = $request->fecha_ini;
        $p[] = $request->fecha_fin;
        $p[] = $request->NumeroPagina;
        $p[] = $request->NumeroRegistros;

        $data = $model_consejeria->GetHistorialEmergenciaConsejeria($p);
        //dd($data);
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

    public function ver_atencion_consejeria($id_consejeria,$id_hospitalizacion)
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }
        
        $modelfarma = new Farmacia;
        //$model_receta = new Receta_vale;
        $model_prestacion = new \App\Models\Prestaciones;
        // $model_evaluacion = new \App\Models\Evaluacione;
        $model_kardex = new Kardexe();
        $id_user = Auth::user()->id;
        $farmacias = $modelfarma->GetFarmaByUser($id_user);
        $id_ipress = $farmacias->id_establecimiento;
        $id_farmacia = $farmacias->id;

        $hospitalizacion = Hospitalizacione::where('id',$id_hospitalizacion)->first();
        $model_hospitalizacione = new Hospitalizacione();
        $cama = null;
        if (isset($hospitalizacion) && isset($hospitalizacion->id_cama_estado)) {
          $cama = $model_hospitalizacione->GetObtenerCama($hospitalizacion->id_cama_estado);
        }

        $model_establecimiento = new \App\Models\Establecimiento;
        $establecimiento = $model_establecimiento->GetById($id_ipress);
        $nivel = $establecimiento[0]->nivel;
        
        /*if($nivel == 3):
          $model_consultorio = new \App\Models\Consultorio;
          $res_servicio = $model_consultorio->getConsultorioPorEstablecimiento($id_ipress, $hospitalizacion->id_servicio_encargado);
          $servicio = $res_servicio[0];
        else:
          $model_consultorio = new \App\Models\Consultorio;
          $res_servicio = $model_consultorio->getConsultorioPorEstablecimiento3($id_ipress, $hospitalizacion->id_servicio_encargado);
          $servicio = $res_servicio[0];
        endif;*/
        

        $fechaServidor = $model_kardex->getFechaServidor();
        $date = Carbon::now();
        $fecha_actual = $date->format('d-m-Y');
        $hora = $date->format('H:i');

        // $hospitalizacion = Hospitalizacione::where('id', $id_hospitalizacion)->first();
        $estado = $hospitalizacion->estado;

        // $model_hospitalizacion = new Hospitalizacione;
        $model_medicos = new Medico();

        // $medico = $model_medicos->GetDatosMedicoByDniAndEstablecimientoSaenzLeguia(Auth::user()->dni, [77, 76]);
        $medico = $model_medicos->GetDatosMedicoByDni(Auth::user()->dni);
        $cita = $model_hospitalizacione->GetBuscaHospitalizadoConsejeria($id_hospitalizacion,  $estado,$id_consejeria);
        
        $controller_consultorio = new \App\Http\Controllers\ConsultorioController();
        $upsses = $controller_consultorio->obtener_upss($hospitalizacion->id_servicio_encargado, 3);

        $model_intraoperatorio = new \App\Models\IntraOperatorio;
        $ultima_atencion = $model_intraoperatorio->getUltimaAtencionXAtencion($id_hospitalizacion, 3);

        $model_prestacion = new \App\Models\Prestaciones;
        $model_consejeria = new \App\Models\Consejeria;
        $model_receta_diagnostico = new Receta_diagnostico();
        $model_prestacion_procedimiento = new Prestacion_procedimiento();

        $consejeria = Consejeria::where('id',  $id_consejeria)->first();
        
        $atencion_hospitalizacion = null;
        $diagnostico = [];
        $procedimiento = [];
        if (isset($ultima_atencion) && isset($ultima_atencion->id_prestacion)) {
          $atencion_hospitalizacion = $model_consejeria->getAtencionByConsejeria($ultima_atencion->id_prestacion, $id_consejeria);
        }

        $atencion = $model_consejeria->getAtencionById($id_hospitalizacion);

        // if (isset($ultima_atencion) && isset($ultima_atencion->id_prestacion)) {
        //   $diagnostico = $model_prestacion->GetDiagnosticoByIdConsejeriaEmergencia($id_consejeria);
        //   $procedimiento = $model_prestacion->GetProcedimientosByIdConsejeriaYTipo($id_consejeria, 1);
        // } else {
          $diagnostico = $model_receta_diagnostico->getDiagnosticosByConsejeriaId($id_consejeria, null);
          $procedimiento = $model_prestacion_procedimiento->getByConsejeriaId($id_consejeria, null);

          $consejeria_detalles = Consejeria::where('consejerias.id', $id_consejeria)
          ->join('consultorios as c2', 'consejerias.id_servicio_especialista', '=', 'c2.id')
          ->leftJoin('consultorios as c_cargo', 'consejerias.id_servicio', '=', 'c_cargo.id')
          ->join('upsses as u', DB::raw('CAST(consejerias.upss_especialista AS INTEGER)'), '=', 'u.id')
          ->leftJoin('users as us', 'consejerias.deleted_us', '=', 'us.id')
          ->select(
              'consejerias.*',
              'c_cargo.nombre as servicio',
              'c2.id as especialidad_id',
              'c2.nombre as especialidad',
              'u.id as upss_id',
              'u.codigo as upss_codigo',
              'u.descripcion_upss',
              'us.name AS deleted_name',
              'us.paterno AS deleted_paterno',
              'us.materno AS deleted_materno',
              'us.dni AS deleted_dni'
          )
          ->first();
          
        // $diagnostico_model = new Diagnostico();
        // if(isset($hospitalizacion->id_diagnostico_inicial)){
        //   $diagnostico_inicial = $diagnostico_model->getDiagnosticoOnCreateConsejeriaById($hospitalizacion->id_diagnostico_inicial);
        //   if (isset($diagnostico_inicial)) {
        //     $diagnostico[] = $diagnostico_inicial;
        //   }
        // }

        // if(isset($hospitalizacion->id_dx_2)){
        //   $dx_2 = $diagnostico_model->getDiagnosticoOnCreateConsejeriaById($hospitalizacion->id_dx_2);
        //   if (isset($dx_2)) {
        //     $diagnostico[] = $dx_2;
        //   }
        // }

        // if(isset($hospitalizacion->id_dx_3)){
        //   $dx_3 = $diagnostico_model->getDiagnosticoOnCreateConsejeriaById($hospitalizacion->id_dx_3);
        //   if (isset($dx_3)) {
        //     $diagnostico[] = $dx_3;
        //   }
        // }


        return view('frontend.consejeria.ver_atencion_consejeria', compact('atencion_hospitalizacion',
            'hospitalizacion','cama','atencion','farmacias',/*'servicio',*/ 'fechaServidor',
            'diagnostico','procedimiento','cita','upsses','medico', 'id_consejeria', 'ultima_atencion', 'consejeria', 'consejeria_detalles'
        ));
    }

    public function ver_atencion_consejeria_emergencia($id_consejeria,$id_emergencia){
      if (!Auth::check()) {
        return redirect('/auth/login');
      }

      $modelfarma = new Farmacia;
      $model_prestacion = new \App\Models\Prestaciones;
      $model_kardex = new Kardexe();
      $model_upss = new Upss();

      $id_user = Auth::user()->id;
      $farmacias = $modelfarma->GetFarmaByUser($id_user);
      $id_ipress = $farmacias->id_establecimiento;
      $id_farmacia = $farmacias->id;

      $emergencia = Emergencia::where('id',$id_emergencia)->first();
      $model_emergencia = new Emergencia();
      $cama = $model_emergencia->GetObtenerCama($emergencia->cama_estado);

      $model_consultorio = new \App\Models\Consultorio;
      $res_servicio = $model_consultorio->getConsultorioPorEstablecimiento2($emergencia->id_establecimiento, $id_emergencia);
      $servicio = $res_servicio[0];
     
      $fechaServidor = $model_kardex->getFechaServidor();
      $date = Carbon::now();
      $fecha_actual = $date->format('d-m-Y');
      $hora = $date->format('H:i');

      $estado = $emergencia->estado;
      $model_medicos = new Medico();

      $medico = $model_medicos->GetDatosMedicoByDni(Auth::user()->dni);
      $cita = $model_emergencia->GetBuscaEmergenciaConsejeria($id_emergencia, $estado,$id_consejeria);

      $controller_consultorio = new \App\Http\Controllers\ConsultorioController();
      
      $upsses = $model_upss->ShowConsultoriosUpss2($emergencia->id,2);

      $model_intraoperatorio = new \App\Models\IntraOperatorio;
      $ultima_atencion_emergencia = $model_intraoperatorio->getUltimaAtencionXAtencion2($id_emergencia, 2);

      $model_prestacion = new \App\Models\Prestaciones;
      $model_consejeria = new \App\Models\Consejeria;
      $model_receta_diagnostico = new Receta_diagnostico();
      $model_prestacion_procedimiento = new Prestacion_procedimiento();

      $flag_no_encontrado = 0;  
      $model_asegurado= new Asegurado();
      if($cita->id_asegurado_titular!=null || $cita->id_asegurado_titular>0):
        $dato_titular = $model_asegurado->Busca_datos_titular($cita->id_asegurado_titular);
      else:
        $flag_no_encontrado=1;
      endif;

      $consejeria = Consejeria::where('id',  $id_consejeria)->first();
      
      $atencion_emergencia = null;
      $diagnostico = [];
      $procedimiento = [];
      if (isset($ultima_atencion_emergencia) && isset($ultima_atencion_emergencia->id_prestacion)) {
        $atencion_emergencia = $model_consejeria->getAtencionByConsejeria($ultima_atencion_emergencia->id_prestacion, $id_consejeria);
      }
      
      $atencion = $model_consejeria->getAtencionEmergenciaById($id_emergencia);
      $diagnostico = $model_receta_diagnostico->getDiagnosticosByConsejeriaId($id_consejeria, null);
      $procedimiento = $model_prestacion_procedimiento->getByConsejeriaId($id_consejeria, null);
      
      $consejeria_detalles = Consejeria::where('consejerias.id', $id_consejeria)
      ->join('consultorios as c2', 'consejerias.id_servicio_especialista', '=', 'c2.id')
      ->leftjoin('consultorios as c_cargo', 'consejerias.id_servicio', '=', 'c_cargo.id')
      ->join('upsses as u', DB::raw('CAST(consejerias.upss_especialista AS INTEGER)'), '=', 'u.id')
      ->leftJoin('users as us', 'consejerias.deleted_us', '=', 'us.id')
      ->select(
          'consejerias.*',
          'c_cargo.nombre as servicio',
          'c2.id as especialidad_id',
          'c2.nombre as especialidad',
          'u.id as upss_id',
          'u.codigo as upss_codigo',
          'u.descripcion_upss',
          'us.name AS deleted_name',
          'us.paterno AS deleted_paterno',
          'us.materno AS deleted_materno',
          'us.dni AS deleted_dni'
      )
      ->first();

        return view('frontend.consejeria.ver_atencion_consejeria_emergencia', compact('atencion_emergencia',
        'emergencia','cama','atencion','farmacias','servicio','fechaServidor','dato_titular',
        'diagnostico','procedimiento','cita','upsses','medico','id_consejeria','ultima_atencion_emergencia','consejeria','consejeria_detalles'
      ));
    }

    public function validarRegistroConsejeria(Request $request)
    {
      if (!Auth::check()):
          return redirect('/auth/login');
      endif;
      //================================================================
      // Alertar si existe una atencion con los paremetros (tipo atencion, fecha atención, servicio, profesional)
      // $request->id_tipo_atencion;
      // $request->fecha_atencion;
      // $request->especializacion_medico;
      $medico = Medico::where('dni', '=', auth()->user()->dni)->first();

      $fecha = DateTime::createFromFormat('d-m-Y', $request->fecha_atencion);
      $fecha_atencion = $fecha->format('Y,m,d');

      $model_consejeria = new \App\Models\Consejeria();
      $res = $model_consejeria->validarRegistroConsejeria($request->id_tipo_atencion, $fecha_atencion, $request->especializacion_medico, $medico->id);
      
      return $res;
    }
    
    /**
     * Generar PDF
     * @param int $id ID de la consejeria
     * @param int $action 1: Ver PDF, 2: Generar PDF para firmar
     */
    function generatePdf($id, $action = 1) {
      if ($action === 2) {
        $path = public_path('tmp/pdf');
        if (!is_dir($path)) {
          mkdir($path);
        }
        $path = public_path('tmp/pdf/sin_firma/');
        if (!is_dir($path)) {
          mkdir($path);
        }
      }

      $model_consejeria = new Consejeria();
      $receta_diagnostico_model = new Receta_diagnostico();
      $prestacion_procedimiento_model = new Prestacion_procedimiento();
      // $model_prestacion = new Prestaciones();

      $consejeria = $model_consejeria->getById($id);
      
      $diagnosticos = $receta_diagnostico_model->getDiagnosticosByConsejeriaId($id);
      $procedimientos = $prestacion_procedimiento_model->getByConsejeriaId($id);

      // $diagnosticos = $model_prestacion->GetDiagnosticoByIdPrestacionEmergencia($consejeria->id_prestacion);
      // $procedimientos = $model_prestacion->GetProcedimientosByIdPrestacionYTipo($consejeria->id_prestacion, 1);

      $carpeta = date("Y")."/".date("m")."/".date("d");
      $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_consejeria_".$id.".pdf";
      $codigo_qr = QrCode::format('png')->size(150)->generate("https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_consejeria_" . $id . ".pdf");
      $pdf = \PDF::loadView('pdf.firmar_consejeria_hospitalizacion', compact(
        "consejeria", 
         "url_descarga", 
         "codigo_qr",
         "diagnosticos",
         "procedimientos"
        ));
      $pdf->setPaper('A4', 'portrait');
      $pdf->getDomPDF()->set_option("enable_php", true);
      if ($action === 1) {
        return $pdf->stream("consejeria_".$id. ".pdf");
      } else {
        file_put_contents($path."consejeria_".$id. ".pdf", $pdf->output());
      }
    }

    function generatePdfEmergencia($id, $action = 1){
      if ($action === 2) {
        $path = public_path('tmp/pdf');
        if (!is_dir($path)) {
          mkdir($path);
        }
        $path = public_path('tmp/pdf/sin_firma/');
        if (!is_dir($path)) {
          mkdir($path);
        }
      }

      $model_consejeria = new Consejeria();
      $receta_diagnostico_model = new Receta_diagnostico();
      $prestacion_procedimiento_model = new Prestacion_procedimiento();

      $consejeria = $model_consejeria->getByIdEmergencia($id);
      $diagnosticos = $receta_diagnostico_model->getDiagnosticosByConsejeriaId($id);
      $procedimientos = $prestacion_procedimiento_model->getByConsejeriaId($id);

      $carpeta = date("Y")."/".date("m")."/".date("d");
      $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_consejeria_".$id.".pdf";
      $codigo_qr = QrCode::format('png')->size(150)->generate("https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_consejeria_" . $id . ".pdf");
      $pdf = \PDF::loadView('pdf.firmar_consejeria_emergencia', compact(
        "consejeria", 
         "url_descarga", 
         "codigo_qr",
         "diagnosticos",
         "procedimientos"
        ));
      $pdf->setPaper('A4', 'portrait');
      $pdf->getDomPDF()->set_option("enable_php", true);
      if ($action === 1) {
        return $pdf->stream("consejeria_".$id. ".pdf");
      } else {
        file_put_contents($path."consejeria_".$id. ".pdf", $pdf->output());
      }
    }

    function firmarPdf($id) {
      $path = public_path('tmp/csv');
      $path_zip = public_path('tmp/zip');
      if (!is_dir($path)) {
          mkdir($path);
      }
      if (!is_dir($path_zip)) {
          mkdir($path_zip);
          mkdir(public_path('tmp/zip/firmados'));
          mkdir(public_path('tmp/zip/sin_firma'));
      }
      $csv = "";
      if (Auth::check()):
          $csv .= Auth::user()->id;
      endif;
      $csv .= "_".date("YmdHis");
      Excel::create($csv, function($excel) use($id){
        $excel->sheet('GENERAL', function($sheet) use($id) {
            $variable = [];
            $this->generatePdf($id, 2);
            array_push($variable, array(url().'/tmp/pdf/sin_firma/consejeria_'.$id.'.pdf', url().'/upload_firma/?name=cf_consejeria_'.$id.'.pdf&id_tipo=consejeria_hospitalizacion&id_resol='.$id, 'cf_consejeria_'.$id.'.pdf','0','45','350','70','Fecha de Firma: <DATE> Firmante: <SIGNER>',url().'/images/escudo.jpg','1','7'));
            $sheet->fromArray($variable, null, '', false, false);
        });
      })->store('csv',public_path('tmp/csv'));
      $obj = new Archive7z(public_path('tmp/zip/sin_firma/'.$csv.'.7z'));
      $obj->addEntry(public_path('/tmp/pdf/sin_firma/consejeria_'.$id.'.pdf'));
      $resultado["csv"]=$csv;
      $resultado["sw"]=true;
      return response()->json($resultado); 
    }
    
    function firmarPdfEmergencia($id){
      $path = public_path('tmp/csv');
      $path_zip = public_path('tmp/zip');
      if (!is_dir($path)) {
          mkdir($path);
      }
      if (!is_dir($path_zip)) {
        mkdir($path_zip);
        mkdir(public_path('tmp/zip/firmados'));
        mkdir(public_path('tmp/zip/sin_firma'));
      }
      $csv = "";
      if (Auth::check()):
          $csv .= Auth::user()->id;
      endif;
      $csv .= "_".date("YmdHis");
      Excel::create($csv, function($excel) use($id){
        $excel->sheet('GENERAL', function($sheet) use($id) {
            $variable = [];
            $this->generatePdfEmergencia($id, 2);
            array_push($variable, array(url().'/tmp/pdf/sin_firma/consejeria_'.$id.'.pdf', url().'/upload_firma/?name=cf_consejeria_'.$id.'.pdf&id_tipo=consejeria_emergencia&id_resol='.$id, 'cf_consejeria_'.$id.'.pdf','0','45','350','70','Fecha de Firma: <DATE> Firmante: <SIGNER>',url().'/images/escudo.jpg','1','7'));
            $sheet->fromArray($variable, null, '', false, false);
        });
      })->store('csv',public_path('tmp/csv'));
      $obj = new Archive7z(public_path('tmp/zip/sin_firma/'.$csv.'.7z'));
      $obj->addEntry(public_path('/tmp/pdf/sin_firma/consejeria_'.$id.'.pdf'));
      $resultado["csv"]=$csv;
      $resultado["sw"]=true;
      return response()->json($resultado); 
    }

    function updateFirma($id, Request $request) {
      $consejeria = Consejeria::where("id", $id)->first();
      if (!isset($consejeria)) {
          return response()->json([
              "message" => "No existe registro de la consejeria"
          ], 404);
      }
      $consejeria->update([
        'fecha_firma' => Carbon::now(),
        'ruta_firma' => "cf_consejeria_{$id}.pdf",
        'flag_firma' => true
      ]);
      return response()->json([
        "message" => "Se actualizo la firma de la consejeria",
        "success" => true
      ], 200);
    }
     
    function updateEmergenciaFirma($id, Request $request) {
      $consejeria = Consejeria::where("id", $id)->first();
      if (!isset($consejeria)) {
        return response()->json([
            "message" => "No existe registro de la consejeria"
        ], 404);
      }
      $consejeria->update([
        'fecha_firma' => Carbon::now(),
        'ruta_firma' => "cf_consejeria_{$id}.pdf",
        'flag_firma' => true
      ]);
      return response()->json([
        "message" => "Se actualizo la firma de la consejeria",
        "success" => true
      ], 200);
    }
   
    function changeStatus(Request $request, $id) {
      if (!Auth::check()) :
        return response()->json(["message" => "no tiene session el usuario"], 401);
      endif;

      $consejeria = Consejeria::where("id", $id)->first();
      if (!isset($consejeria)) {
          return response()->json([
              "message" => "No existe registro de la consejeria"
          ], 404);
      }

      Receta_diagnostico::where('id_consejeria', $id)->update(['estado' => $request->estado]);
      Prestacion_procedimiento::where('id_consejeria', $id)->update(['id_estado_reg' => $request->estado]);


      $consejeria->update([
        'estado' => $request->estado,
        'updated_us' => Auth::user()->id,
        'updated_at' => Carbon::now(),
        'deleted_at' => (intval($request->estado) === 0) ? Carbon::now() : null,
        'deleted_us' => (intval($request->estado) === 0) ? Auth::user()->id : null,
        'deleted_reason' => $request->deleted_reason
      ]);
      return response()->json([
        "message" => "Se actualizo el estado de la consejeria",
        "success" => true
      ], 200);
    }

    function changeEmergenciaStatus(Request $request, $id) {
      if (!Auth::check()) :
        return response()->json(["message" => "no tiene session el usuario"], 401);
      endif;

      $consejeria = Consejeria::where("id", $id)->first();
      if (!isset($consejeria)) {
          return response()->json([
              "message" => "No existe registro de la consejeria"
          ], 404);
      }

      Receta_diagnostico::where('id_consejeria', $id)->update(['estado' => $request->estado]);
      Prestacion_procedimiento::where('id_consejeria', $id)->update(['id_estado_reg' => $request->estado]);

      $consejeria->update([
        'estado' => $request->estado,
        'updated_us' => Auth::user()->id,
        'updated_at' => Carbon::now(),
        'deleted_at' => (intval($request->estado) === 0) ? Carbon::now() : null,
        'deleted_us' => (intval($request->estado) === 0) ? Auth::user()->id : null,
        'deleted_reason' => $request->deleted_reason
      ]);
      return response()->json([
        "message" => "Se actualizo el estado de la consejeria",
        "success" => true
      ], 200);
    }
}
