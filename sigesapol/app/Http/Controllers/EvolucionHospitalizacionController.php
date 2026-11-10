<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\Route;
use App\Models\Emergencia;
use App\Models\Evaluacione;
use App\Models\Hospitalizacione;
use App\Models\Kardexe;
use App\Models\Consultorio;
use App\Models\Medico;
use App\Models\Producto;
use App\Models\Prestaciones;
use DB;
use App\Models\Asegurado;
use App\Models\Cama;
use App\Models\CambioCama;
use App\Models\Cita;
use App\Models\Evaluacion;
use Session;
use App\Models\Farmacia;
use App\Models\Gestacion;
use App\Models\HospitalizacionAtencione;
use App\Models\Laboratorio;
use App\Models\Nati;
use App\Models\Prestacion_eventos;
use App\Models\Receta_vale;
use App\Models\Producto_receta;
use App\Models\Puerperio;
use App\Models\Receta_diagnostico;
use App\Models\Siteds;
use App\Models\tipoDocNati;
use App\Models\Upss;
use Illuminate\Support\Facades\Log;
use QrCode;
use Archive7z\Archive7z;
use DateTime;
use Maatwebsite\Excel\Facades\Excel;
use stdClass;
use SoapClient;

class EvolucionHospitalizacionController extends Controller
{
    function __construct()
    {
        $this->middleware('auth'); 
    }
    
    public function all_pacientes($id_user = 0) 
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        else:
            if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
            endif;
        endif;

        $ruta = "all_pacientes_evoluciones_by_hospitalizacion";
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);

        $permiso = null;
        if (!empty($menu)):
            $permiso = $menu[0];
        endif;

        if (is_null($permiso) || $permiso->lectura == "0"):
            return redirect('/dashboard')->withInput()->withErrors(array('No cuenta con permisos para acceder a esta opción.'));
        endif;
    
        if (Auth::user()->tipo_registro == 1) {

            $id_user_consultorio = Auth::user()->id_consultorio;
            if(Auth::user()->id_establecimiento==75):
                $id_user_consultorio = 105;
            endif;
            $date = Carbon::now();
            $fechaHasta = $date->format('d-m-Y');
            $fechaDesdex = $date->format('d-m-Y');
            $fechaDesdey = strtotime('-30 day', strtotime($fechaDesdex));
            $fechaDesde = date('d-m-Y', $fechaDesdey);

            $model_upss = new \App\Models\Upss();
            $upss = $model_upss->ShowUPSS_by_Hospitalizacion();
            
            return view('frontend.hospitalizacion.evolucion.all_pacientes_evoluciones', compact('upss', 'fechaDesde', 'fechaHasta', 'permiso','id_user_consultorio'));
        } else {
            return redirect('/dashboard')->withInput()->withErrors(array('No tiene el perfil de médico..!'));
        }
    }

    public function listar_evolucion_hospitalizacion_ajax(Request $request){
    
        $model_evaluacion = new Evaluacione();
        $p[]=Auth::user()->id_establecimiento;
        $p[]=$request->fecha_ini;
        $p[]=$request->fecha_fin;
        $p[]=$request->dni_beneficiario;
        $p[]=$request->nombre_beneficiario;
        $p[]=$request->estado;
        $p[]=$request->NumeroPagina;
        $p[]=$request->NumeroRegistros;
        $p[]=$request->id_servicio;
        $data = $model_evaluacion->listar_evolucion_hospitalizacion($p);
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

    public function verificarHistoriaClinicaEvolucion($id_hospitalizacion){
        if (!Auth::check()) {
            return response()->json(['status' => 'error'], 401);
        }

        $hospitalizacione = Hospitalizacione::where('id', $id_hospitalizacion)->where('estado', 4)->first();
        if (!is_object($hospitalizacione) || $hospitalizacione->historiaClinicaCompleta()) {
            return response()->json(['status' => 'ok']);
        }

        if ((int) $hospitalizacione->id_user_exa_cu === (int) Auth::user()->id) {
            return response()->json(['status' => 'completar']);
        }

        return response()->json(['status' => 'bloqueado', 'medico' => $hospitalizacione->nombreMedicoRegistroHc()]);
    }

    public function form_evolucion_hospitalizacion($id_hospitalizacion) // antes $id_emergencia
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;
		$id_evaluacion = 0;
        $msg = "";

        $modelfarma = new \App\Models\Farmacia;
        // $model_producto = new \App\Models\Producto;
        $model_establecimiento = new \App\Models\Establecimiento;
        $model_laboratorio = new \App\Models\Laboratorio;
        $model_radiologia = new \App\Models\Radiologia;
        $model_interconsulta = new \App\Models\Interconsulta;
        $model_evolucion = new \App\Models\Evaluacione;
        $model_medicos = new Medico;
        $modelcama= new \App\Models\Cama();
        
        $medico = $model_medicos->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni, Auth::user()->id_establecimiento);
        
        $hospitalizacione = Hospitalizacione::where('id', $id_hospitalizacion)->where('estado', 4)->first();

        if (is_object($hospitalizacione) && !$hospitalizacione->historiaClinicaCompleta()) {
            if ((int) $hospitalizacione->id_user_exa_cu !== (int) Auth::user()->id) {
                return redirect('/all_pacientes_evoluciones_by_hospitalizacion')->withErrors(array('El registro de la Historia Clínica aún no está terminada, por lo que no es posible realizar esta acción. Médico responsable del registro: ' . $hospitalizacione->nombreMedicoRegistroHc()));
            }
            return redirect('/form_atencion_prestacion_hspcu/' . Auth::user()->id . '/' . $id_hospitalizacion)->withInput()->withErrors(array('Debe completar la Historia Clínica antes de registrar evoluciones..!'));
        }

        if(is_object($hospitalizacione)):
            $model_hospitalizacion = new \App\Models\IntraOperatorio();
            $ultima_atencion = $model_hospitalizacion->getUltimaAtencionXAtencion($id_hospitalizacion, 3);
            
            if(is_object($ultima_atencion)):
                $msg .= "ultima atencion si tiene data\n";
                $primera_atencion = Prestaciones::where('id_ext', $id_hospitalizacion)->where('id_tipo_atencion', 3)->whereRaw("COALESCE(flag_intervencion_quirurgica, false) = false")->where('id_estado_reg', 1)->orderBy('id', 'desc')->first();
                $id_prestacion = $primera_atencion->id;
                // dd($id_prestacion);

                $farmacia = $modelfarma->GetFarmaByUser(Auth::user()->id);
                $date = Carbon::now();
                $fechaServidor = $date->format('Y-m-d H:i:s');
                $hora = $date->format('H:i');
                
                $id_ipress = $farmacia->id_establecimiento;
                $establecimiento = $model_establecimiento->GetById($id_ipress);
                $id_farmacia = Auth::user()->id_farmacia;
                $nivel = $establecimiento[0]->nivel;
                
                $msg .= "despues de nivel\n";
                $farmacias = $modelfarma->GetFarmaciaById($id_farmacia);
                $estado=4;
                $id_tipo_atencion=3;
                $datos_evaluacion = $model_evolucion->GetBuscaHospitalizacionEvaluacion($id_hospitalizacion, $medico->id, $estado);
                
                // $producto = $model_producto->getProductosByNivelAndIdServicio($nivel, $id_ipress, $id_farmacia);
                $flag = 0 ;
                $id_tipo_atencione=3;
                $codigo_upss = "";

                $model_emergencia = new \App\Models\Emergencia;
                $dato_cama = $model_emergencia->GetObtenerCama($hospitalizacione->id_cama_estado);
                $cuartos = $modelcama->GetAllPabellonesYServicioOSalaYCuartos($hospitalizacione->id_establecimiento,'HOSPITALIZACION');
                $camas = $modelcama->GetCamasByIdEstablecimientoYNombreTipoAtencionYIdCuarto($hospitalizacione->id_establecimiento,'HOSPITALIZACION',$dato_cama->id_consultorio);
                $msg .= "despues de camas\n";
                $id_servicio= $dato_cama->id_consultorio;

                // $diagnosticos = DB::table('receta_diagnosticos')
                //         ->join('diagnosticos', 'receta_diagnosticos.id_diagnostico', '=', 'diagnosticos.id')
                //         ->select('receta_diagnosticos.id', 'diagnosticos.id', 'diagnosticos.codigo', 'diagnosticos.nombre', 'receta_diagnosticos.id_clasificacion_diagnosticos', 'receta_diagnosticos.id_categoria')
                //         ->where('receta_diagnosticos.id_prestacion', '=', $id_prestacion)
                //         ->where('receta_diagnosticos.id_interconsulta', '=', 0)
                //         ->where('receta_diagnosticos.estado', '=', 1)
                //         ->get();

                $model_prestacion = new Prestaciones();
                $arraydeprocedimientos = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);

                $flag_no_encontrado = 0;
                $model_asegurado= new Asegurado();
                if($datos_evaluacion->id_asegurado_titular!=null || $datos_evaluacion->id_asegurado_titular>0):
                    $dato_titular = $model_asegurado->Busca_datos_titular($datos_evaluacion->id_asegurado_titular);
                else:
                    $flag_no_encontrado = 1;
                endif;

                $codigo_siteds_valido = "0";
                // Se obtiene el ultimo codigo siteds , corregido
                $model_siteds = new \App\Models\Siteds;
                $codigo_siteds = $model_siteds->getUltimoCodigoSitedsEnElDia($id_hospitalizacion, 3, $ultima_atencion->tipo_atencion_ultima);
                $msg .= "despues de codigo siteds\n";
                $ordenes_laboratorio = null;
                $ordenes_imagenologia = null;
                $interconsultas = null;
                if ($ultima_atencion->tipo_atencion_ultima == "prestacion") {
                    $ordenes_laboratorio = $model_laboratorio->GetLaboratorioByIdPrestacion($ultima_atencion->id_prestacion);
                    $ordenes_imagenologia = $model_radiologia->GetRadiologiaByIdPrestacion($ultima_atencion->id_prestacion);
                    if ($ordenes_imagenologia->isEmpty()) $ordenes_imagenologia = null;
                    $interconsultas = $model_interconsulta->GetInterconsultaByIdPrestacion($ultima_atencion->id_prestacion);
                }

                if ($codigo_siteds != "")
                    $codigo_siteds_valido = "1";
                //dd($arraydeprocedimientos);
                /******************************************************** */
                // $date = Carbon::now();
                // $hora=$date->toTimeString(); 

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

                $tipo_doc_nati = tipoDocNati::all();

                $tipo_partos = DB::select("SELECT * FROM tipo_partos tp ");
        
                $gestacionModel = new Gestacion;
                $lastGestacion = null;
                $lastGestacionArray = $gestacionModel->getLastByAseguradoDNI($datos_evaluacion->nro_doc_ident);
                if (isset($lastGestacionArray) && count($lastGestacionArray) > 0) {
                  $lastGestacion = $lastGestacionArray[0];
                }
                $msg .= "despues de lastgestacion\n";
                $model_signos = new \App\Models\signosVitales();
                $signo_vital = $model_signos->GetUltimoRegistroSignoVital(3, $id_hospitalizacion);
                $signos_vitales = null;

                if ($signo_vital != null){ 
                    $signos_vitales = $model_signos->GetSignosVitalesDetalleById($signo_vital->id);
                }

                $upss_model = new Upss();

                $upps = $upss_model->ShowConsultoriosUpss($datos_evaluacion->id_consultorio);

                $listUpss = $upss_model->ShowUPSS_by_Hospitalizacion();

                $upss_encargado = Upss::where('id_consultorio', $hospitalizacione->id_servicio_encargado)
                            ->where('id', $hospitalizacione->id_upss_encargado)
                            ->where('id_tipo_atencion', 3)
                            ->where('estado', 1)
                            ->first();
                $existe_servicio_atencion = DB::table('medico_consultorios as mc')
                            ->join('consultorios as c', 'mc.id_consultorio', '=', 'c.id')
                            ->where('mc.id_establecimiento', $id_ipress)
                            ->where('mc.id_medico', $medico->id)
                            ->where('mc.estado', 1)
                            ->where('c.id', $hospitalizacione->id_servicio_encargado)
                            ->exists();
                $msg .= "despues de existe_servicio_atencion\n";
                $regularizar = ($upss_encargado == null) ? "1" : "0";

                $model_consultorio = new \App\Models\Consultorio;
                $establecimiento_servicios = $model_consultorio->GetServiciosPorEstablecimiento($id_ipress);

                if ($regularizar == "0") {
                    $servicio_ubicacion = $model_consultorio->GetNombreById($hospitalizacione->id_servicio_destino);
                    $id_servicio_actual = $hospitalizacione->id_servicio_destino;
                } else {
                    $model_hospitalizacion2 = new \App\Models\Hospitalizacione;
                    $data_cama = $model_hospitalizacion2->GetObtenerCama($hospitalizacione->id_cama_estado);
                    $servicio_ubicacion = $model_consultorio->GetNombreById($data_cama->id_consultorio);
                    $id_servicio_actual = $data_cama->id_consultorio;
                }

                $msg .= "despues de regularizar\n";
                // if (Auth::user()->dni == "48839708"): dd($arraydeprocedimientos,$id_prestacion,$fechaServidor,$nivel,$eventosMarcados,$obj_hospitalizacion_atencion,$farmacias,$triaje,$producto,$estado,$datos_evaluacion,$id_tipo_atencion,$id_farmacia,$cuartos,$camas,$flag,$id_tipo_atencione,$codigo_upss,$id_hospitalizacion,$dato_cama,$hora,$dato_titular,$turno,$ultima_atencion,$flag_no_encontrado,$id_servicio,$codigo_siteds,$codigo_siteds_valido,$ordenes_laboratorio,$ordenes_imagenologia,$interconsultas,$tipo_doc_nati,$tipo_partos,$lastGestacion,$upps,$listUpss,$signos_vitales,$signo_vital,$upss_encargado,$servicio_ubicacion,$hospitalizacione); endif;

                /****************BUSCA SI EL SERVICIO TIENE EL FLAG QUIRURGICO***********/
                $tiene_flag_quirurgico = \App\Models\Consultorio::where('id',$hospitalizacione->id_servicio_encargado)->where('flag_quirurgico',1)->exists();
                /****************FIN BUSCA SI EL SERVICIO TIENE EL FLAG QUIRURGICO***********/
                
                $upsses = "SELECT DISTINCT up.id id_upss, up.descripcion_upss, up.codigo codigo_upss, co.estado 
                            FROM upsses as up
                            INNER JOIN consultorios as co ON co.id = up.id_consultorio
                            WHERE up.estado = 1 
                            And up.id_tipo_atencion = 3
                            ORDER BY up.descripcion_upss;";
                $upsses = DB::select($upsses);

               
                $upss_encargado = DB::table('upsses as u')
                                ->join('consultorios as c', 'c.id', '=', 'u.id_consultorio')
                                ->select('u.*', 'c.nombre as nombre_consultorio')
                                ->where('u.id_consultorio', $hospitalizacione->id_servicio_encargado)
                                ->where('u.id', $hospitalizacione->id_upss_encargado)
                                ->where('u.id_tipo_atencion', 3)
                                ->where('u.estado', 1)
                                ->first();

                $medicoservicio = $model_consultorio->MedicoDetalleServicio($id_ipress,$medico->id);

                $upss_cargo = $upss_encargado->nombre_consultorio;
                $servicio_medico = $medicoservicio->servicio;

                $servicios = explode('#|#', $servicio_medico);
                $servicios = array_map('trim', $servicios);
                $msg .= "despues de servicios\n";
                if (!in_array($upss_cargo, $servicios) && !session()->pull('mostrar_error')) {
                    session()->put('mostrar_error', true); 
                    return redirect()->to('/form_evolucion_hospitalizacion/' . $id_hospitalizacion)
                        ->with('mensaje_error', 'EL UPSS A CARGO DEL PACIENTE NO COINCIDE CON SU SERVICIO. BAJO SU RESPONSABILIDAD');
                } 
                //hospitalizados.evolucion.formulario_registro
                $is_profesion_medico = false;
                if (count($medico) > 0) {
                    switch ($medico->id_profesion) {
                        case 20: case 4: case 51: case 52: case 1: $is_profesion_medico = true; break;
                    }
                    if (!$is_profesion_medico && (intval($nivel) == 1 || intval($nivel) == 2) && in_array(intval($medico->id_profesion), [12, 2, 5])) {
                      $is_profesion_medico = true;
                    }
                }

                /* $eventosPrevios = Prestacion_eventos::where("id_prestacion", $id_prestacion)->first(); */
                $obj_hospitalizacion_atencion = \App\Models\HospitalizacionAtencione::select('ha.*', 
                    'p.fecha_parto', 
                    'p.id_tipo_parto as puerperio_id_tipo_parto', 
                    'tp.nombre as puerperio_tipo_parto', 
                    'p.complicaciones as puerperio_complicaciones', 
                    'p.descripcion as puerperio_descripcion')
                ->from('hospitalizacion_atenciones as ha')
                ->leftJoin('puerperios as p', 'ha.id_puerperio', '=', 'p.id')
                ->leftJoin('tipo_partos as tp', 'p.id_tipo_parto', '=', 'tp.id')
                ->where('ha.id_prestacion', '=', $id_prestacion) // Cambié aquí a 'ha.id' para que coincida con el contexto
                ->first();
                $eventosMarcados = Prestacion_eventos::where('id_prestacion', $id_prestacion)
                ->where('estado', 1)
                ->lists('id_evento')
                ->toArray();

                $model_tipo_cirugia = new \App\Models\CqxTipoAnestesia();
                $tipo_cirugias = $model_tipo_cirugia->GetAllTipoCirugiasActivos();

                $model_cqx_tipo_profesional = new \App\Models\CqxTipoProfesional();
                $cqx_tipo_profesionales = $model_cqx_tipo_profesional->GetAllActivos();
                $msg .= "despues de cqx_tipo_profesionales\n";
                $client = new SoapClient(env('WI_SALUDPOL_URL') . "/ws/WS_AfiliadoSP_Serv.php?wsdl", array('login' => env('WI_SALUDPOL_USERNAME'), 'password' => env('WI_SALUDPOL_PASSWORD')));

                if($datos_evaluacion->tipo_doc_ident=='DNI'){ 
                    $tipo_doc_ident = '1';
                }else if($datos_evaluacion->tipo_doc_ident=='CE'){ 
                    $tipo_doc_ident = '2';
                }else if($datos_evaluacion->tipo_doc_ident=='PAS'){ 
                    $tipo_doc_ident = '3';
                }else if($datos_evaluacion->tipo_doc_ident=='DIE'){ 
                    $tipo_doc_ident = '4';
                }

                $beneficiario = $client->getAseguradoValidate($tipo_doc_ident, $datos_evaluacion->nro_doc_ident);
                $msg .= "despues de beneficiario\n";
                if (is_object($beneficiario)) :
                  $flag = 0 ;
                  $errorWS = 1 ;
                else:
                  $flag = 1 ;
                  $errorWS = 0 ;
                endif;

                $id_principal=$id_hospitalizacion;
                $id_user = Auth::user()->dni;
                // if (Auth::user()->dni == "48839708"): dd($msg); endif;
                // if (Auth::user()->dni == "48839708"): dd($arraydeprocedimientos,$id_prestacion,$fechaServidor,$nivel,$eventosMarcados,$obj_hospitalizacion_atencion,$farmacias,$triaje,$producto,$estado,$datos_evaluacion,$id_tipo_atencion,$id_farmacia,$cuartos,$camas,$flag,$id_tipo_atencione,$codigo_upss,$id_hospitalizacion,$dato_cama,$hora,$dato_titular,$turno,$ultima_atencion,$flag_no_encontrado,$id_servicio,$codigo_siteds,$codigo_siteds_valido,$ordenes_laboratorio,$ordenes_imagenologia,$interconsultas,$tipo_doc_nati,$tipo_partos,$lastGestacion,$upps,$listUpss,$signos_vitales,$signo_vital,$upss_encargado,$servicio_ubicacion,$hospitalizacione,$upsses,$id_servicio_actual,$regularizar,$puerperio,$natis,$receta_diagnosticos,$prestacion_procedimientos,$is_profesion_medico,$existe_servicio_atencion,$upss_cargo,$servicio_medico,$establecimiento_servicios,$tipo_cirugias,$cqx_tipo_profesionales); endif;
                           
                if ($errorWS > 0) {             
                    return view('frontend.hospitalizacion.evolucion.form_evolucion', compact('arraydeprocedimientos','id_prestacion','fechaServidor','nivel','eventosMarcados','obj_hospitalizacion_atencion','farmacias','triaje','producto','estado','datos_evaluacion','id_tipo_atencion','id_farmacia','cuartos','camas','flag','id_tipo_atencione','codigo_upss','id_hospitalizacion','dato_cama','hora','dato_titular','turno','ultima_atencion','flag_no_encontrado','id_servicio','codigo_siteds','codigo_siteds_valido','ordenes_laboratorio','ordenes_imagenologia','interconsultas','tipo_doc_nati','tipo_partos','lastGestacion','upps','listUpss', 'signos_vitales','signo_vital','upss_encargado','servicio_ubicacion','hospitalizacione','upsses','id_servicio_actual','regularizar','puerperio','natis','receta_diagnosticos','prestacion_procedimientos','is_profesion_medico','existe_servicio_atencion','upss_cargo','servicio_medico','establecimiento_servicios','tipo_cirugias','cqx_tipo_profesionales','tiene_flag_quirurgico','id_principal', 'id_user'));
                }
                else{
                    return redirect('/all_pacientes_evoluciones_by_hospitalizacion')->withInput()->withErrors(array('El paciente ya no esta asegurado, por consiguiente no tiene derecho de atención, realizar los tramites correspondiente con SALUDPOL..!'));
                }
            else:
                if (Auth::user()->dni == "48839708"): dd($hospitalizacione, $ultima_atencion); endif;
                return redirect('/form_atenciones_hospitalizacion/'.$id_hospitalizacion)->withInput()->withErrors(array('Primero debe ingresar una atencion, para poder registrar su evolución del paciente.'));
            endif;
        else:
            return redirect('/all_pacientes_evoluciones_by_hospitalizacion/')->withInput()->withErrors(array('No puede registrar la Evolución porque aún no cuenta con su Primera Atención.'));
        endif;
    }

    public function registrar_evolucion_transaction_hospitalizacion(Request $request)
    {

        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

         if (!empty($request->id_hospitalizacion)) {
            $hospitalizacion_evolucion = Hospitalizacione::find($request->id_hospitalizacion);
            if ($hospitalizacion_evolucion && !$hospitalizacion_evolucion->historiaClinicaCompleta()) {
                return redirect('/form_atencion_prestacion_hspcu/' . Auth::user()->id . '/' . $request->id_hospitalizacion)->withInput()->withErrors(array('Debe completar la Historia Clínica antes de registrar evoluciones..!'));
            }
        }

        $registro_validado = false;
        $mensaje_error = "";
        
        DB::beginTransaction();
        try {

            Log::info("Info Evolucion Hospitalizacion => id_hospitalizacion: " . $request->id_hospitalizacion . 
                "\n\ndatos del request: " . json_encode($request->all())
            );
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
                        $p_diagnostico .= ",";
                        $p_diagnostico .= $request->id_categoria_diagnostico[$key] . ", 8";
                        $p_diagnostico .= "},";
                    }
                endforeach;
                if (strlen($p_diagnostico) > 1) $p_diagnostico = substr($p_diagnostico, 0, -1);
            endif;
            $p_diagnostico .= "}";
        else:
        //     $diagnosticos = $model_prestacion->GetDiagnosticoByIdPrestacion($request->id_prestacion);
        //     $p_diagnostico = "";
        //     $p_diagnostico .= "{";
        //     foreach ($diagnosticos as $diagnostico ) :
        //         $p_diagnostico .= "{";
        //         $p_diagnostico .= $diagnostico->id_diagnostico . ",";
        //         $p_diagnostico .= $diagnostico->id_tipo_diagnostico;
        //         $p_diagnostico .= "},";
        //     endforeach;
        //     if (strlen($p_diagnostico) > 1) $p_diagnostico = substr($p_diagnostico, 0, -1);
            
        //     $p_diagnostico .= "}";
            Log::error("Bug Evolucion Hospitalizacion (No hay diagnostico): => id_hospitalizacion: " . $request->id_hospitalizacion . 
                "\n\ndatos del request: " . json_encode($request->all())
            );
        endif;

        $p_procedimiento = "";
        $p_procedimiento .= "{";
        if (isset($request->id_procedimiento)) :
            foreach ($request->id_procedimiento as $key => $value) :
                if ($request->id_procedimiento[$key] != "") {
                $p_procedimiento .= "{";
                $p_procedimiento .= $request->id_procedimiento[$key] . ",";
                $p_procedimiento .= (isset($request->procedimiento_cantidad[$key]) && $request->procedimiento_cantidad[$key] > 0) ? $request->procedimiento_cantidad[$key] : "0";
                $p_procedimiento .= "," . $request->miSelect[$key];
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
        //$estado_receta = 2;
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
        }
        
        
        if($request->id_tipo_atencion==2){ 
            $id_sub_consultorio=545;
        }*/
        
        $model_emergencia = new \App\Models\Emergencia;
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
        $diuresis                   = $request->diuresis;
        $balance_hidrico            = $request->balance_hidrico;
        
        if(isset($request->id_producto)):
        $valor = 1;     //Valor
        else:
        $valor = 2; //vaciio
        endif;

        $id_servicio_cama = 0;
        $flag_traslado_servicio = "0";
        $id_upss_encargado_origen = $request->id_upss_encargado;
        $id_servicio_encargado_origen = $request->id_servicio_encargado;
        $id_upss_encargado = $request->id_upss_encargado;
        $id_servicio_encargado = $request->id_servicio_encargado;
        $id_servicio_cama_actual_regularizar = $request->id_servicio_actual_paciente;
        $regularizar_datos_encargado = $request->regularizar_datos;

        $id_upss_encargado_regularizacion = null;
        $id_servicio_encargado_regularizacion = null;
        $id_servicio_cama_regularizacion = null;
        if ($regularizar_datos_encargado == "1") {
            $id_upss_encargado_regularizacion = ($request->id_upss_encargado_regularizacion != "0" ? $request->id_upss_encargado_regularizacion : null);
            $id_servicio_encargado_regularizacion = ($request->id_servicio_encargado_regularizacion != "0" ? $request->id_servicio_encargado_regularizacion : null);
            $id_servicio_cama_regularizacion = $request->id_servicio_actual_paciente;
        }

        if ($request->select_cambiar_cama == "0") { // Permanece en la misma cama
            $id_servicio_cama = $request->id_servicio_actual_paciente;
            $id_cama_rotacion = $request->id_cama_actual;
            $fecha_rotacion_cama = null;
            $hora_rotacion_cama = null;
            $observacion_alta_servicio = null;

        } else if ($request->select_cambiar_cama == "1") { // Rotacion
            $id_servicio_cama = $request->id_area_destino;
            $id_cama_rotacion = $request->id_cama_destino;
            $fecha_rotacion_cama = $request->fecha_inicio;
            $hora_rotacion_cama = $request->appt;
            $observacion_alta_servicio = null;

        } else if ($request->select_cambiar_cama == "2") { // Traslado
            if (intval($request->traslado_tipo) === 2) {
                $id_upss_encargado = $request->traslado_id_upss_encargado;
                $id_servicio_encargado = $request->traslado_id_servicio_encargado;
            }

        // Cuenta con Alta de Servicio
        // if ($request->flag_alta_servicio == "1") {
        //     $id_cama_rotacion = (isset($request->id_cama_destino) && !empty($request->id_cama_destino)) ? $request->id_cama_destino : null;
        //     $fecha_rotacion_cama = $request->fecha_egreso;
        //     $hora_rotacion_cama = $request->hora_egreso;
        //     $observacion_alta_servicio = $request->observacion_alta_servicio;
        // }
        // VALIDAR TRASLADO DE SERVICIO
        // if ($request->flag_alta_servicio == "1") {
        
        // $id_servicio_cama = 0;
        // $flag_traslado_servicio = "0";
        // if (isset($request->frmTrasladoActive) && strval($request->frmTrasladoActive) === "true") {
            $flag_traslado_servicio = "1";
            if (isset($request->traslado_tipo)) {
                $id_cama_rotacion =  intval($request->traslado_tipo) === 2 ? $request->traslado_cama : null;
                $fecha_rotacion_cama = intval($request->traslado_tipo) === 2 ? Carbon::createFromFormat('d/m/Y', $request->traslado_fecha)->format('Y-m-d') : null;
                $hora_rotacion_cama = intval($request->traslado_tipo) === 2 ? Carbon::createFromFormat('H:i', substr($request->traslado_hora, 0, 5))->format('H:i:s'): null;
                $observacion_alta_servicio = null;
                $id_servicio_cama = $request->traslado_upss_destino;
            }
        // } else {
        //     $id_servicio_cama = $request->id_area_destino;
        // }
        }

        $fecha_hora_atencion = date('Y-m-d H:i:s', strtotime("$request->fecha_atencion $request->hora_atencion"));

        $id_evaluacion = $model_evaluacion->registrarEvaluacionHospitalizacion(
            $request->id_hospitalizacion,
            $request->id_prestacion, 
            $request->id_asegurado, 
            $request->id_tipo_atencion,  
            $fecha_hora_atencion, 
            $request->id_medico,  
            $request->id_user, 
            $examen_fisico, 
            $temperatura, 
            $sa02_porcentaje, 
            $presion_arterial_x, 
            $presion_arterial_y, 
            $talla_cm, 
            $tamano_cintura_cm, 
            $frecuencia_respiratoria, 
            $peso_kg, 
            $frecuencia_cardiaca_fetal, 
            $frecuencia_cardiaca, 
            $fi02, 
            $diuresis, 
            $balance_hidrico, 
            $request->motivo_visita, 
            $request->tiempo_enfermedad, 
            $request->examen_clinico, 
            $request->examen_auxiliar, 
            $request->indicacion_terapeutica, 
            $request->plan_trabajo,
            $request->antecedente,
            $request->antecedente_familiar,
            $request->id_prioridad,
            $request->id_topico,
            $fecha_rotacion_cama,
            $hora_rotacion_cama,
            $id_cama_rotacion, 
            $p_diagnostico, 
            $valor,
            $request->cod_siteds_susalud,
            $request->apreciacion_subjetiva,
            $request->apreciacion_objetiva,
            $request->verificacion_tratamiento,
            $request->interpretacion_examenes, 
            $p_procedimiento, 
            $flag_traslado_servicio, 
            $observacion_alta_servicio,
            $request->id_establecimiento,
            $id_servicio_cama,
            $request->select_cambiar_cama,
            $id_upss_encargado,
            $id_servicio_encargado,
            $regularizar_datos_encargado,
            $id_upss_encargado_regularizacion,
            $id_servicio_encargado_regularizacion,
            $id_servicio_cama_regularizacion);
        
        $evaluacionUpdateSiteds = Evaluacione::find($id_evaluacion);
        $evaluacionUpdateSiteds->updated_at_cod_siteds = Carbon::now();
        $evaluacionUpdateSiteds->updated_us_cod_siteds = Auth::user()->id;

        /*********************************  Eventos epidemiologicos ************************************/
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
            Prestacion_eventos::create([
                'id_prestacion' => $request->id_prestacion,
                'evento_1' => $request->input("evento_1") ? 11 : null,
                'evento_2' => $request->input("evento_2") ? 13 : null,
                'evento_3' => $request->input("evento_3") ? 21 : null,
                'evento_4' => $request->input("evento_4") ? 14 : null,
                'evento_5' => $request->input("evento_5") ? 12 : null,
                'evento_6' => $request->input("evento_6") ? 16 : null,
                'evento_7' => $request->input("evento_7") ? 15 : null,
                'evento_8' => $request->input("evento_8") ? 8 : null,
                'estado' => 1,
            ]);
        } */
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
                    $evento->save();
                }
            } else {
                Prestacion_eventos::create([
                    'id_prestacion' => $idPrestacion,
                    'id_evento' => $idEvento,
                    'id_evolucion' => $id_evaluacion,
                    'estado' => 1,
                    'created_us' => Auth::id(),
                ]);
            }
        }

        /********* PUERPERA **************/
        if (isset($request->frmPuerperaActive) && strval($request->frmPuerperaActive) === "true") {
            $fecha_parto = Carbon::createFromFormat('d/m/Y', $request->fecha_parto)->format('Y-m-d');
            $hora_parto = Carbon::createFromFormat('H:i', $request->hora_parto)->format('H:i:s');
            $complicaciones = intval($request->complicacion_parto) === 1 ? "SIN COMPLICACIONES" : "COMPLICADO";
            $newPuerpera = Puerperio::create([
            "fecha_parto" => $fecha_parto . " " . $hora_parto,
            "id_tipo_parto" => $request->tipo_parto,
            "complicaciones" => $complicaciones,
            "descripcion" => null,
            "created_at" => Carbon::now(),
            "created_us" => Auth::user()->id,
            "updated_at" => Carbon::now(),
            "updated_us" => Auth::user()->id,
            ]);
            if (isset($request->numero_documento_puerpera) && is_array($request->numero_documento_puerpera) && count($request->numero_documento_puerpera) > 0) {
                for ($i=0; $i < count($request->numero_documento_puerpera); $i++) { 
                    $newNeonatos = Nati::create([
                    "id_puerperio" => $newPuerpera->id,
                    "estado" => intval($request->estado_vida_puerpera[$i]),
                    "id_tipo_doc_nati" => intval($request->tipo_documento_puerpera[$i]),
                    "num_doc" => $request->numero_documento_puerpera[$i],
                    "sexo" => $request->sexo_puerpera[$i],
                    "talla" => round(floatval($request->talla_puerpera[$i]),2),
                    "peso" => round(floatval($request->peso_puerpera[$i]),2),
                    "created_at" => Carbon::now(),
                    "created_us" => Auth::user()->id,
                    "updated_at" => Carbon::now(),
                    "updated_us" => Auth::user()->id,
                    "nati_estado_vida" => $request->nati_estado_vida[$i],
                    "nati_nombres" => $request->nati_nombres[$i],
                    "nati_paterno" => $request->nati_paterno[$i],
                    "nati_materno" => $request->nati_materno[$i],
                    "nati_edad_gestacional" => intval($request->nati_edad_gestacional[$i]),
                    "nati_peso_edad_gestacional" => intval($request->nati_peso_edad_gestacional[$i]),
                    "nati_perimetro_cefalico" => floatval($request->nati_perimetro_cefalico[$i]),
                    "nati_perimetro_toracico" => floatval($request->nati_perimetro_toracico[$i]),
                    "nati_apgar_1" => intval($request->nati_apgar_1[$i]),
                    "nati_apgar_5" => intval($request->nati_apgar_5[$i]),
                    "nati_enfermedad_congenita" => intval($request->nati_enfermedad_congenita[$i]),
                    "nati_contacto_piel" => intval($request->nati_contacto_piel[$i]),
                    "nati_lactancia_primera_h" => intval($request->nati_lactancia_primera_h[$i]),
                    "nati_alojamiento_conjunto" => intval($request->nati_alojamiento_conjunto[$i]),
                    "nati_requirio_hospitalizacion" => intval($request->nati_requirio_hospitalizacion[$i]),
                    ]);
                }
            }
            $evaluacionUpdateSiteds->id_puerperio = $newPuerpera->id;
            $ha = HospitalizacionAtencione::where("id_ext", $request->id_hospitalizacion)->first();
            $ha->id_puerperio = $newPuerpera->id;
            $ha->save();
        }
        /********* PUERPERA - END **************/
        
        $evaluacionUpdateSiteds->save();


        $id_consultorio = $request->id_servicio_encargado;
        $id_tiporeceta = $request->id_tipo_atencion;
        $obs_registro = "";
        $interconsulta = "";
        $interconsulta_dias = 0;
        $nro_receta = "";
        $digitos_adicionales = "";
        $id_tipo_num = 0;
        $estado_receta = 2;
        
        $date = Carbon::now();
        $hora = $date->format('H:i');

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
                $id_receta_vale = $receta->postRegistrarRecetaValeNuevoConDiagnosticoEvaluacion($id_receta_vale, $request->id_farmacia_receta, $request->id_user, $request->id_servicio_encargado, $nro_receta, $digitos_adicionales, $request->nrodocafiliado, $titular->cip, $request->tipodoc_beneficiario, $request->nombre_beneficiario, $request->paterno_beneficiario, $request->materno_beneficiario, $request->tipo_beneficiario, $titular->nro_doc_ident, $titular->tipo_doc_ident, $titular->nombre, $titular->paterno, $titular->materno, $request->fecha_expedicion, $request->fecha_atencion, $request->nro_historia, $request->grado, $request->id_medico, $id_anestesiologo, $id_tiporeceta, $turno, $obs_registro, $id_receta_vale_bus, $id_admision, $tipo_via, $sub_via, $final_via, $nro_guia, $interconsulta, $interconsulta_dias, $request->id_prestacion, $id_tipo_num, $estado_receta, $p_producto, $p_diagnostico, $request->nomsexo, $request->fecnacafiliado, $request->codigo_upss, $id_evaluacion);
                $model_rec_val = new \App\Models\Receta_vale;
                $ok=$model_rec_val->ActualizaIdEvaluacion($id_receta_vale,$id_evaluacion);
            endif;
        else :
            $id_receta_vale = 1;
        endif;

        /*********************************************************************/
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
                if (intval($request->id_establecimiento) === 76):
                $has_observacion_unica = (intval($request->id_nivel) === 3) ? 1 : 0;
                $lab_orden_observacion_unica = intval($request->id_nivel) === 3 ? $request->lab_orden_observacion : null;
                $id_interconsulta = 0;
                $id_area = 0;
                $flag_cq = (isset($request->id_perfil_preoperatorio) && intval($request->id_perfil_preoperatorio) > 0) ? 1 : 0;
                $id_hora_toma_muestra = (intval($request->id_nivel) === 3 && isset($request->id_hora_toma_muestra)) ? $request->id_hora_toma_muestra : null;
                
                $id_procedimiento_lab = $model_procedimiento->RegistrarLaboratorioOrden3($request->id_user, $request->id_prestacion, $request->id_establecimiento, $fecha_hora_atencion, $p_laboratorio, $has_observacion_unica, $id_evaluacion, $request->id_farmacia, $id_interconsulta, $request->id_tipo_atencion, $request->id_servicio_encargado, $id_area, $id_cama_rotacion, $request->id_medico, $request->id_asegurado, $lab_orden_observacion_unica, $flag_cq, $id_hora_toma_muestra);
                else:
                $id_procedimiento_lab = $model_procedimiento->RegistrarProcedimiento2($request->id_user, $request->id_prestacion, $request->id_establecimiento, $request->fecha_atencion, $p_laboratorio, 2, $id_evaluacion, $request->id_farmacia);
                endif;

                $model_lab = new \App\Models\Laboratorio;
                $ok = $model_lab->ActualizaIdEvaluacion($id_procedimiento_lab, $id_evaluacion);
            endif;
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
                $id_procedimiento_rad = $model_procedimiento->RegistrarProcedimientoImagenes($request->id_user, $request->id_prestacion, $request->id_establecimiento, $request->fecha_atencion, $p_radiologia, 3, $id_evaluacion, $request->id_farmacia);
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Identificar radiología a la cual pertenece 
                if (intval($request->id_establecimiento) == 76) {
                    $updated = DB::table('rua_imagenologia')->where('id', $request->id_rua_imagenes)->update(['id_rad' => $id_procedimiento_rad,'id_evol'=> $id_evaluacion, 'id_estado_reg' => 1]);
                }
                ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                $model_rad = new \App\Models\Radiologia;
                $ok = $model_rad->ActualizaIdEvaluacion($id_procedimiento_rad, $id_evaluacion);
            endif;
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
        // $search  = array('+', ',','"','/');
        // $replace = array('\+', '\,', '\"', '\/');

        $model_interconsulta = new \App\Models\Interconsulta;

        if (!empty($request->id_servicio_interconsulta)) {

            // Quitar duplicados de interconsultas en backend
            $id_servicio_interconsulta_unicos = [];
            $hidden_select_otorrino_unicos = [];      // Código UPSS para OTORRINO
            $hidden_select_diagnostico_unicos = [];     // Código UPSS para DIAGNÓSTICO
            $motivo_interconsulta_unicos = [];
            $personal_coordinacion_unicos = [];

            foreach ($request->id_servicio_interconsulta as $index => $id_servicio) {
                if (!in_array($id_servicio, $id_servicio_interconsulta_unicos)) {
                    $id_servicio_interconsulta_unicos[] = $id_servicio;
                    $hidden_select_otorrino_unicos[] = $request->hidden_select_OTORRINO[$index];
                    $hidden_select_diagnostico_unicos[] = $request->hidden_select_DIAGNOSTICO[$index];
                    $motivo_interconsulta_unicos[] = $request->motivo_interconsulta[$index];
                    $personal_coordinacion = $request->interconsulta_personal_coodinacion[$index];
                    $personal_coordinacion_unicos[] = $personal_coordinacion != "" ? $personal_coordinacion : "NULL";
                }
            }
            // Fin quitar duplicados de interconsultas en backend

            $elementos = [];

            foreach ($id_servicio_interconsulta_unicos as $key => $value) {
                if (!empty($value)) {
                    // Si el resumen está vacío, se usa una cadena vacía con comillas dobles
                    $resumen_interconsulta = !empty(trim($motivo_interconsulta_unicos[$key]))
                        ? str_replace($search, $replace, $motivo_interconsulta_unicos[$key])
                        : '""';

                    // Escapar caracteres especiales en el resumen
                    $resumen_interconsulta = addslashes($resumen_interconsulta);

                    $hidden_select_OTORRINO = isset($hidden_select_otorrino_unicos[$key])
                        ? addslashes($hidden_select_otorrino_unicos[$key])
                        : '';
                    $hidden_select_DIAGNOSTICO = isset($hidden_select_diagnostico_unicos[$key])
                        ? addslashes($hidden_select_diagnostico_unicos[$key])
                        : '';

                    // Agrega el elemento en formato de array PostgreSQL con 6 elementos:
                    // [id_servicio, turno, "0", resumen, hidden_select_OTORRINO, hidden_select_DIAGNOSTICO]
                    $elementos[] = sprintf(
                        '{"%s","%s","%s","%s","%s","%s","%s","%s"}',
                        $value,
                        $turno,
                        "0",
                        $resumen_interconsulta,
                        $hidden_select_OTORRINO,
                        $hidden_select_DIAGNOSTICO,
                        $request->is_estable,
                        str_replace($search, $replace, $personal_coordinacion_unicos[$key])
                    );
                }
            }

            // Si hay elementos, se forma el array PostgreSQL correctamente
            if (!empty($elementos)) {
                $p_interconsulta = '{' . implode(',', $elementos) . '}';

                // Solo registra si hay un servicio válido
                if (!empty($id_servicio_interconsulta_unicos)) {
                    $id_interconsulta = $model_interconsulta->RegistrarInterconsultaEvaluacion(
                        $request->id_prestacion, 
                        $id_asegurado, 
                        $request->id_tipo_atencion, 
                        $request->fecha_atencion, 
                        $request->id_medico, 
                        $request->id_establecimiento, 
                        $p_interconsulta, 
                        $id_evaluacion
                    );
                }
            }
        }
        /* $model_interconsulta = new \App\Models\Interconsulta;

        if (!empty($request->id_servicio_interconsulta)) {

            // Quitar duplicados de interconsultas en backend
            $id_servicio_interconsulta_unicos = [];
            $hidden_select_otorrino_unicos = []; // Codigo UPSS
            $motivo_interconsulta_unicos = [];

            foreach ($request->id_servicio_interconsulta as $index => $id_servicio) {
                if (!in_array($id_servicio, $id_servicio_interconsulta_unicos)) {
                    $id_servicio_interconsulta_unicos[] = $id_servicio;
                    $hidden_select_otorrino_unicos[] = $request->hidden_select_OTORRINO[$index];
                    $motivo_interconsulta_unicos[] = $request->resumen_interconsulta[$index];
                }
            }
            // Fin Quitar duplicados de interconsultas en backend

            $elementos = [];

            foreach ($id_servicio_interconsulta_unicos as $key => $value) {
                if (!empty($value)) {
                    // Si el resumen está vacío, se usa una cadena vacía con comillas dobles
                    $resumen_interconsulta = !empty(trim($motivo_interconsulta_unicos[$key])) 
                        ? str_replace($search, $replace, $motivo_interconsulta_unicos[$key]) 
                        : '""';

                    // Escapar caracteres especiales en el resumen
                    $resumen_interconsulta = addslashes($resumen_interconsulta); 

                    $hidden_select_OTORRINO = isset($hidden_select_otorrino_unicos[$key]) ? addslashes($hidden_select_otorrino_unicos[$key]) : '';

                    // Agrega el elemento en formato de array PostgreSQL
                    $elementos[] = sprintf('{"%s","%s","%s","%s","%s"}', $value, $turno, "0", $resumen_interconsulta, $hidden_select_OTORRINO);
                }
            }

            // Si hay elementos, se forma el array PostgreSQL correctamente
            if (!empty($elementos)) {
                $p_interconsulta = '{' . implode(',', $elementos) . '}';
                
                // Solo registra si hay un servicio válido
                if (!empty($id_servicio_interconsulta_unicos)) {
                    $id_interconsulta = $model_interconsulta->RegistrarInterconsultaEvaluacion(
                        $request->id_prestacion, 
                        $id_asegurado, 
                        $request->id_tipo_atencion, 
                        $request->fecha_atencion, 
                        $request->id_medico, 
                        $request->id_establecimiento, 
                        $p_interconsulta, 
                        $id_evaluacion
                    );
                }
            }
        } */


        //if($request->id_servicio_interconsulta!=null):
        //    $p_interconsulta = "";
        //    $p_interconsulta .= "{";
        //    foreach ($request->id_servicio_interconsulta as $key => $value) :
        //        if ($request->id_servicio_interconsulta[$key] != "") {
        //            $p_interconsulta .= "{";
        //            $p_interconsulta .= $request->id_servicio_interconsulta[$key] . ",";
        //            $p_interconsulta .= $turno . ",";
        //            $p_interconsulta .= "0,";
        //            $p_interconsulta .= trim($request->resumen_interconsulta[$key]) != "" ? str_replace($search, $replace, $request->resumen_interconsulta[$key]) : "\"\"";
        //            $p_interconsulta .= "},";
        //        }
        //    endforeach;
        //    if (strlen($p_interconsulta) > 1) $p_interconsulta = substr($p_interconsulta, 0, -1);
        //    $p_interconsulta .= "}"; 

        //    if($request->id_servicio_interconsulta[0]!=""):
        //        $id_interconsulta = $model_interconsulta->RegistrarInterconsultaEvaluacion($request->id_prestacion, $id_asegurado, $request->id_tipo_atencion, $request->fecha_atencion, $request->id_medico, $request->id_establecimiento, $p_interconsulta,$id_evaluacion);
        //        //$model_inter = new \App\Models\Interconsulta;
        //        //$ok=$model_inter->ActualizaIdEvaluacion($id_interconsulta,$id_evaluacion);
        //    endif;
        //endif;

        // $model_hospitalizacione = new \App\Models\Hospitalizacione;
        // if ($request->flag_alta_servicio == "1") {

        //     $p_diagnostico_alta_servicio = "";
        //     $p_diagnostico_alta_servicio .= "{";
        //     if (isset($request->id_diagnostico)) :
        //         foreach ($request->id_diagnostico as $key => $value) :
        //         if ($request->id_diagnostico[$key] != "") {
        //             $p_diagnostico_alta_servicio .= "{";
        //             $p_diagnostico_alta_servicio .= $request->id_diagnostico[$key] . ",";
        //             $p_diagnostico_alta_servicio .= (isset($request->id_tipo_diagnostico[$key]) && $request->id_tipo_diagnostico[$key] > 0) ? $request->id_tipo_diagnostico[$key] : "0";
        //             $p_diagnostico_alta_servicio .= ",";
        //             $p_diagnostico_alta_servicio .= $request->id_categoria_diagnostico[$key] . ", 9"; //alta de servicio
        //             $p_diagnostico_alta_servicio .= "},";
        //         }
        //         endforeach;
        //         if (strlen($p_diagnostico_alta_servicio) > 1) $p_diagnostico_alta_servicio = substr($p_diagnostico_alta_servicio, 0, -1);
        //     endif;
        //     $p_diagnostico_alta_servicio .= "}";

        //     $update = $model_hospitalizacione->registrar_evolucion_alta_servicio($request->id_hospitalizacion, $request->id_prestacion, $id_evaluacion, $request->id_user, $request->fecha_egreso, $request->hora_egreso, $request->id_cama_destino, $request->observacion_alta_servicio, $p_diagnostico_alta_servicio);
        // }

        // ************************ TRASLADO ************************
        // if (isset($request->frmTrasladoActive) && strval($request->frmTrasladoActive) === "true") {
        if ($request->select_cambiar_cama == "2") {
            $newCambioCama = null;
            $fecha_hora_inicio = null;
            if (intval($request->traslado_tipo) === 2) {
                $fecha_hora_inicio = Carbon::createFromFormat('d/m/Y H:i', $request->traslado_fecha . ' ' . substr($request->traslado_hora, 0, 5))->format('Y-m-d H:i:s');
            }
            $newCambioCama = CambioCama::create([
                "id_cama_atencion" => $request->id_cama_actual,
                "estado_ubicacion" => 3,
                "fecha_hora_inicio" => $fecha_hora_inicio,
                "id_cama" => intval($request->traslado_tipo) === 1 ? null : $request->traslado_cama,
                "id_prestacion" => $request->id_prestacion,
                "id_evolucion" => $id_evaluacion,
                "estado" => 1,
                "created_at" => Carbon::now(),
                // "updated_at" => Carbon::now(),
                "medico_id" => $request->id_medico,
                'id_tipo_traslado' => $request->traslado_tipo,
                "id_consultorio" => $request->traslado_upss_destino,
                "id_upss_encargado" => $id_upss_encargado,
                "id_servicio_encargado" => $id_servicio_encargado,
                "id_tipo_atencion" => 3,
                "id_ext" => $request->id_hospitalizacion,
                "id_upss_encargado_atencion" => $id_upss_encargado_origen,
                "id_servicio_encargado_atencion" => $id_servicio_encargado_origen
            ]);
            if (isset($request->id_traslado_diagnostico) && !empty($request->id_traslado_diagnostico) && is_array($request->id_traslado_diagnostico) && count($request->id_traslado_diagnostico) > 0) {
                for ($t = 0; $t < count($request->id_traslado_diagnostico); $t++) {
                    $newRecetaDiagnostico= Receta_diagnostico::create([
                        'id_diagnostico' => $request->id_traslado_diagnostico[$t],
                        'id_tipo_diagnostico' => $request->traslado_diagnostico_tipo[$t],
                        'estado' => 1,
                        'id_prestacion' => $request->id_prestacion,
                        'created_at' => Carbon::now(),
                        'created_us' => $request->id_user,
                        'id_clasificacion_diagnosticos' => 9,
                        'id_evaluacion' => $id_evaluacion,
                        'id_hospitalizacion' => $request->id_hospitalizacion,
                        'id_categoria' => 1,
                        'id_cambio_cama' => $newCambioCama->id,
                    ]);
                }
            }
            $updateEvaluacionOrdenTraslado = Evaluacion::find($id_evaluacion);
            $updateEvaluacionOrdenTraslado->update([
                'flag_orden_traslado' => 1,
                'estado_orden_traslado' => 0,
            ]);
        }

        /******************************************************************************/
        /*********************TRASLADO A CENTRO QUIRURGICO*****************************/
        /******************************************************************************/
        if ((int)$request->evolucion_requiere_intervencion_quirurgica === 1) {
            $duracion_cirugia = $request->duracion_cirugia ? floatval($request->duracion_cirugia) : null;
            $modelintervencion = new \App\Models\CqxIntervencion;

            $hora_inicio_intervencion = !empty($request->hora_inicio_intervencion)?$request->hora_inicio_intervencion:null;
            $hora_fin_intervencion = !empty($request->hora_fin_intervencion)?$request->hora_fin_intervencion:null;
           
            $cqx_id_intervencion = $modelintervencion->RegistrarIntervencionQxDesdeHospitalizacionTraslado($request->id_prestacion,$request->id_hospitalizacion,$id_evaluacion, Auth::user()->id,$request->id_tipo_cirugia,$duracion_cirugia,$fecha_hora_atencion,$id_servicio_encargado_origen,$hora_inicio_intervencion,$hora_fin_intervencion);
            $evolucion = Evaluacion::find($id_evaluacion);
            $evolucion->envio_intervencion_quirurgica = ((int) $request->evolucion_requiere_intervencion_quirurgica === 1) ? true : false;
            $evolucion->save();
            
          //Registro de diagnosticos cqx
          if (isset($request->id_diagnostico_quirurgico)) :
              $dignostico_cqx = new Receta_diagnostico();
              foreach ($request->id_diagnostico_quirurgico as $key => $value) :
                if ($request->id_diagnostico_quirurgico[$key] != "") {
                  $dignostico_cqx->id_diagnostico = $request->id_diagnostico_quirurgico[$key];
                  $dignostico_cqx->id_tipo_diagnostico = $request->id_tipo_diagnostico_quirurgico[$key];
                  $dignostico_cqx->estado = 1;
                  $dignostico_cqx->id_prestacion = $request->id_prestacion;
                  $dignostico_cqx->created_at=Carbon::now();
                  $dignostico_cqx->created_us=$request->id_user;
                  $dignostico_cqx->id_intervencion=$cqx_id_intervencion;
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
                  $response = $model_cq_profesional->RegistrarProfesionalesPreQx($cqx_id_intervencion, $p_id_medico, $p_id_medico_cargo, $request->id_user, $flag_personal, 0);
                }
              endforeach;
            endif;

          // Registro de Procedimientos de Intervencion quirurgica
          if (isset($request->id_procedimiento_intervencion)) :
              $model_cq_procedimiento = new \App\Models\CqxProcedimiento();
              foreach ($request->id_procedimiento_intervencion as $key => $value) :
                if ($request->id_procedimiento_intervencion[$key] != "") {
                  $p_id_procedimiento = $request->id_procedimiento_intervencion[$key];
                  $model_cq_procedimiento->RegistrarProcedimientoPreQx($cqx_id_intervencion, $p_id_procedimiento, Auth::user()->id, 0,1,false);
                }
              endforeach;
          endif;
        }
        /*************************************************////*****************************/
        /*********************FIN TRASLADO A CENTRO QUIRURGICO*****************************/
        /*************************************************////*****************************/
        
        // ********************** TRASLADO END ************************
            DB::commit();
            $registro_validado = true;

        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Registro Evolucion Hospitalizacion => id_hospitalizacion: " . $request->id_hospitalizacion . 
                "\n\nmensaje: " . $e->getMessage() . 
                "\n\ndatos del request: " . json_encode($request->all()) . 
                "\n\nstack trace: " . $e->getTraceAsString()
            );
        } catch (\Throwable $th) {
            DB::rollback();
            Log::critical("Registro Evolucion Hospitalizacion => id_hospitalizacion: " . $request->id_hospitalizacion . 
                "\n\nmensaje: " . $th->getMessage() . 
                "\n\ndatos del request: " . json_encode($request->all()) . 
                "\n\nstack trace: " . $th->getTraceAsString()
            );
        }
        if ($registro_validado):
            Session::flash('flash_message', 'Evolución Médica exitosa');
            return redirect('/ver_evaluacion_hospitalizacion/' . $request->id_user . '/' . $id_evaluacion);
        else:
            Session::flash('flash_warning', '<strong>Error:</strong> No se pudo registrar la Evolución Médica, vuelva a intentarlo nuevamente.<br><br>' . $e->getMessage());
            return redirect('/all_pacientes_evoluciones_by_hospitalizacion');
        endif;
    }

    public function ver_evaluacion($id_user, $id_evaluacion)
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
        $model_kardex = new Kardexe();

        $farmacia = $modelfarma->GetFarmaByUser($id_user);
        $id_farmacia = $farmacia->id;
        $flag_receta_dias = $farmacia->flag_receta_dias;

        $fechaServidor = $model_kardex->getFechaServidor();
        $date = Carbon::now();
        $fecha_actual = $date->format('d-m-Y');

        $evaluacion = $model_evaluacion->GetEvaluacionById($id_evaluacion);
        
        //$model_emergencias = new Emergencia;
        $model_evolucion = new Evaluacione;
        $paciente = $model_evolucion->GetBuscaPacienteHospitalizacionEvaluacion($evaluacion->id_emergencia);
        
        //verifico si existe idemergencia
        if(count($evaluacion)>0):

            $id_prestacion =  $evaluacion->id_prestacion;
               $prestacion = $model_prestacion->GetPrestacionByIdDatos($id_prestacion);
            //   $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
            
            /*$prestacion = Prestaciones::where("id", $id_prestacion)->first();*/
            $hospitalizacione = Hospitalizacione::find($prestacion->id_ext);
            $gestacion = null;
            $puerperio = null;
          if (isset($hospitalizacione->id_gestacion)) {
            $gestacion = Gestacion::find($hospitalizacione->id_gestacion);
            error_log('HAY GESTACION EN EVALUACION: '.(string)$gestacion);
          }
          if (isset($hospitalizacione->id_puerperio)) {
            $puerperio = Puerperio::find($hospitalizacione->id_puerperio);
            error_log('HAY PUERPERIO EN EVALUACION: '.(string)$puerperio);
            $natis = $puerperio->natis;
            error_log('sus natis: '.(string)$natis);
          }

          $receta = $model_receta->GetDatosRecetasByIdEvaluacion($id_evaluacion);
          $diagnostico_atencion = $model_prestacion->GetDiagnosticoByIdPrestacionEmergencia($id_prestacion);
          $procedimiento_atencion = $model_prestacion->GetProcedimientosByIdPrestacionYTipo($id_prestacion,1);
           
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
            //   if ($id_ipress == 1) {
            //     $nivel = 2;
                $id_farmacia = Auth::user()->id_farmacia;
            //   } else {
            //     if($id_ipress==76){
            //       $id_farmacia = 19;
            //     }
            //     else{
            //       $id_farmacia = Auth::user()->id_farmacia;
            //     }
                $nivel = $establecimiento[0]->nivel;
            //   }


          $model_emergencia = new \App\Models\Emergencia;
          //$obtenerdato = $model_emergencia->GetObtenerServicio($prestacion->id_ext);
        //   $obtenerdato = Hospitalizacione::find($prestacion->id_ext);

          //$prestacion->servicio= $obtenerdato->servicio;
          //$prestacion->consultorio= $obtenerdato->consultorio;

          $producto = $model_producto->getProductosByNivelAndIdServicio($nivel, $establecimiento[0]->id, $id_farmacia); //76 -> servicio de hopitalizacion 
          

          //$farmacias = $modelfarma->GetFarmaciaByIdEstablecimiento($id_ipress);
          $farmacias = $modelfarma->GetFarmaciaById($id_farmacia);
          $establecimientos = $model_establecimiento->ShowAllEstablecimientos();
          $diagnostico_evaluacion = $model_evaluacion->GetDiagnosticoByIdEvaluacion($id_evaluacion);
          /*$diagnostico_evaluacion = DB::table('receta_diagnosticos as rd')
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
            ->get();*/
          
          //$procedimiento = $model_evaluacion->GetProcedimientosByIdEvaluacion($id_evaluacion, 1);
          //$procedimiento_evaluacion = $model_prestacion->GetProcedimientosByIdPrestacionYIdEvolucionYTipo($id_prestacion, $id_evaluacion, 1);
            $model_procedimiento = new \App\Models\Procedimiento();
            $procedimiento_evaluacion = $model_procedimiento->GetProcedimientosHospitalizacionEvolucion($id_evaluacion);
          
          $datoadicional = $model_emergencia->GetObtenerCama($evaluacion->cama_estado);
          $interconsultas = $model_interconsulta->GetInterconsultaByIdEvaluacion2($id_prestacion,$id_evaluacion);

          $interconsultas_id_servicio = [];
          if (!empty($interconsultas)) {
            foreach ($interconsultas as $ic) {
                $interconsultas_id_servicio[] = $ic->id_servicio;
            }
          }


          $referencia = $model_referencia->GetReferenciaByIdPrest($id_prestacion);

          $ruta = "all_pacientes_evoluciones_by_hospitalizacion";
          $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
          $permiso = $menu[0];

          $flag_no_encontrado = 0;
          $model_asegurado= new Asegurado();
          $hospi = Hospitalizacione::where("id", $prestacion->id_ext)->first();
          $asegurado = Asegurado::where("id", $hospi->id_asegurado)->first();
          if($asegurado->id_asegurado_titular!=null || $asegurado->id_asegurado_titular>0):
            $dato_titular = $model_asegurado->Busca_datos_titular($asegurado->id_asegurado_titular);
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

            $talla_cm = $evaluacion->talla_cm;
            $peso_kg = $evaluacion->peso_kg;
            $fecha_nacimiento = DateTime::createFromFormat('d/m/Y', $asegurado->fecha_nac);
            $hoy = new DateTime();
            $edad = $hoy->diff($fecha_nacimiento)->y;
            $edadpaciente = $edad;
            $sexo = $asegurado->sexo;
            $peso_ideal = "";
            $imc = "";
            
            // Cálculo del peso ideal
            if ($talla_cm != "0") { 
                // Peso por altura
                $peso_ideal = 50 + (($talla_cm - 150) / 4) * 3 + ($edadpaciente - 20) / 4;
                if ($sexo == "M")
                $peso_ideal = $peso_ideal * 0.9;
                
                if($peso_ideal <= 0)
                $peso_ideal = '';
                
            } else {
                $peso_ideal = "";
            }

            // Cálculo del IMC
            if ($peso_kg != "0" && $talla_cm != "0") {
                $talla_en_metros = $talla_cm * 0.01;
                $imc = "";
                if ($peso_kg > 0 && $talla_cm > 0) {
                $imc = $peso_kg / ($talla_en_metros * $talla_en_metros);
                $imc = number_format($imc, 2, '.', '');
                }
            } else {
                $imc = "";
            }
            // Formateo final
            $peso_ideal = ($peso_ideal == "") ? "-" : $peso_ideal;
            $imc = ($imc == "") ? "-" : $imc;

            $natisEvaluacion = [];
            if (isset($evaluacion->id_puerperio)) {
                $model_nati = new Nati();
                $natisEvaluacion = $model_nati->getByIdPuerpera($evaluacion->id_puerperio);
            }


            $cambioCamaModel = new CambioCama();
            $traslado = $cambioCamaModel->getTrasladoByIdEvolucion($id_evaluacion);
            $diagnosticos_traslado = null;
            if (isset($traslado)) {
                $receta_diagnostico_model = new Receta_diagnostico();
                $diagnosticos_traslado = $receta_diagnostico_model->getDiagnosticosTrasladoByIdCambioCama($traslado->id);
            }

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

            $obj_hospitalizacion_atencion = \App\Models\HospitalizacionAtencione::select('ha.*', 
                    'p.fecha_parto', 
                    'p.id_tipo_parto as puerperio_id_tipo_parto', 
                    'tp.nombre as puerperio_tipo_parto', 
                    'p.complicaciones as puerperio_complicaciones', 
                    'p.descripcion as puerperio_descripcion')
                ->from('hospitalizacion_atenciones as ha')
                ->leftJoin('puerperios as p', 'ha.id_puerperio', '=', 'p.id')
                ->leftJoin('tipo_partos as tp', 'p.id_tipo_parto', '=', 'tp.id')
                ->where('ha.id_prestacion', '=', $id_prestacion) // Cambié aquí a 'ha.id' para que coincida con el contexto
                ->first();

            $eventosMarcados = Prestacion_eventos::where('id_prestacion', $id_prestacion)
                ->where('estado', 1)
                ->lists('id_evento')
                ->toArray();
            /*****************DATOS CENTRO QUIRURGICO*************/
            $model_intervencion = new \App\Models\CqxIntervencion;
            $existe_intervencion_cqx = $model_intervencion::where('id_evolucion',$id_evaluacion)->exists();
            $datos_intervencion="";
            $procedimientos_cqx="";
            $profesionales_cqx="";
            $diagnosticos_cqx="";
            if($existe_intervencion_cqx){
                $datos_intervencion = $model_intervencion::select('cqx_intervenciones.id','tc.descripcion as tipo_cirugia','cqx_intervenciones.emergencia_duracion_cirugia')            
                ->leftJoin('cqx_tipo_cirugias as tc', 'cqx_intervenciones.emergencia_tipo_cirugia', '=', 'tc.id')
                ->where('cqx_intervenciones.id_evolucion',$id_evaluacion)->first();
                //dd($datos_intervencion);
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
            /*****************FIN DATOS CENTRO QUIRURGICO*************/
            // Validar campo de habilitacion de firma electronica
            $is_habilitado_firma_electronica = $establecimiento[0]->flag_firma_electronica;
            $id_principal=$prestacion->id_ext;            

          return view('frontend.hospitalizacion.evolucion.ver_evaluacion', compact('farmacia', 'prestacion', 'servicio_ubicacion', 'obj_hospitalizacion_atencion','diagnostico_atencion','eventosMarcados','diagnostico_evaluacion', 'procedimiento_atencion', 'procedimiento_evaluacion', 'medicamentos', 'id_prestacion', 'fechaServidor', 'receta', 'producto', 'farmacias', 'nivel', 'laboratorio', 'radiologia', 'flag_pendinte_firma','establecimientos','referencia','evaluacion','paciente','id_evaluacion','procedimiento','estado','datoadicional','interconsultas','permiso','dato_titular','flag_no_encontrado','turno', 'gestacion', 'puerperio', 'natis', 'flag_laboratorio', 'flag_firmar_laboratorio','peso_ideal','imc', 'natisEvaluacion', 'traslado', 'diagnosticos_traslado','flag_radiologia','flag_firmar_radiologia','ordenImagenesAnulada','ordenes','hospitalizacione','is_profesion_medico','interconsultas_id_servicio','establecimiento_servicios','existe_intervencion_cqx','procedimientos_cqx','profesionales_cqx','diagnosticos_cqx','datos_intervencion', 'is_habilitado_firma_electronica','id_principal'));
      else:
         return redirect('/all_pacientes_evoluciones_by_hospitalizacion/')->withInput()->withErrors(array('Evaluacion no encontrado ..!'));
      endif;
      
    }

    public function ver_atenciones_evaluacion($id_hospitalizacion, $estado, $id_tipo_atencion)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $ruta = "all_pacientes_evoluciones_by_hospitalizacion";
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);

        $permiso = null;
        if (!empty($menu)):
            $permiso = $menu[0];
        endif;

        if (is_null($permiso) || $permiso->lectura == "0"):
            return redirect('/dashboard')->withInput()->withErrors(array('No cuenta con permisos para acceder a esta opción.'));
        endif;

        $date = Carbon::now();
        $fechaHasta = $date->format('d-m-Y');
        $fechaDesdex = $date->format('d-m-Y');
        $fechaDesdey = strtotime('-30 day', strtotime($fechaDesdex));
        $fechaDesde = date('d-m-Y', $fechaDesdey);

        $hospitalizacion = Hospitalizacione::where('id', $id_hospitalizacion)->where('estado', $estado)->first();
        if(is_object($hospitalizacion)):
            $model_hospitalizacione = new Hospitalizacione;
            $paciente = $model_hospitalizacione->GetBuscaPacienteHospitalizacion($id_hospitalizacion, $estado);
            if(count($paciente)>0):
                $estado = "1";
                $id_atencione = $id_tipo_atencion;
                $model_emergencias = new Emergencia;
                $datoadicional = $model_emergencias->GetObtenerCama($hospitalizacion->id_cama_estado);
                
                $atenciones = $model_hospitalizacione->getAtencionesResumenById($id_hospitalizacion);
                $is_fiscalizador = Auth::user()->flagfiscalizador;

                $servicio_encargado = null;
                if ($hospitalizacion->id_servicio_encargado != null):
                    $model_consultorio = new \App\Models\Consultorio;
                    $servicio_encargado = $model_consultorio->GetNombreById($hospitalizacion->id_servicio_encargado);
                endif;

                $formatos_escaneados="SELECT * from cqx_formato_escaneados where id_intervencion= '$hospitalizacion->id'";
                $formatos_escaneados = DB::select($formatos_escaneados);

                $establecimiento_model = new \App\Models\Establecimiento;
                $establecimiento = $establecimiento_model->GetByIdEstablecimiento($hospitalizacion->id_establecimiento);

                return view('frontend.hospitalizacion.evolucion.all_evaluaciones_atenciones', compact('fechaDesde', 'fechaHasta', 'formatos_escaneados', 'hospitalizacion','estado','id_atencione','paciente','id_hospitalizacion','datoadicional','permiso','atenciones','servicio_encargado','is_fiscalizador', 'establecimiento'));
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
    
    public function listar_evolucion_hospi_ajax(Request $request){
    
        $model_evaluacion = new Evaluacione();
        $p[]=$request->fecha_ini;
        $p[]=$request->fecha_fin;
        $p[]=$request->id_paciente;
        $p[]=$request->estado;
        $p[]=$request->id_atencione;
        $p[]=$request->NumeroPagina;
        $p[]=$request->NumeroRegistros;
        $data = $model_evaluacion->listar_evolucion_hospi_ajax($p);
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

    public function validar_codigo_siteds($codigo_siteds) {
        $hospitalizacione = new Hospitalizacione;
        $codigo_unico = $hospitalizacione->ExisteCodigoSiteds($codigo_siteds);
        return json_encode($codigo_unico);
    }

    public function updateCodeSiteds(Request $request, $id) {
        $evaluacion = Evaluacione::find($id);
        if (!$evaluacion) {
          return response()->json(['message' => 'No existe registro de la evaluacion'], 404);
        }
        $evaluacion->cod_siteds_susalud = $request->input('cod_siteds_susalud');
        $evaluacion->updated_at_cod_siteds = Carbon::now();
        $evaluacion->updated_us_cod_siteds = Auth::user()->id;
        $evaluacion->save();
        
        return response()->json([
            'emergencia_id' => $id,
            'message' => 'Código SITEDS actualizado correctamente'
        ], 200);
    }

    public function generateOrdenTrasladoPdf($id_evaluacion)
    {
        $evaluacion = Evaluacion::find($id_evaluacion);
        $prestacion = DB::table('prestaciones as p')
        ->join('asegurados as a', 'p.id_asegurado', '=', 'a.id')
        ->join('medicos as m', 'p.id_medico', '=', 'm.id')
        ->join('establecimientos as e', 'p.id_establecimiento', '=', 'e.id')
        ->leftJoin('tipo_beneficiarios as tb', 'a.id_tipo_beneficiario', '=', 'tb.id')
        ->join('asegurado_historias as ah', function($join) { 
            $join->on('ah.id_asegurado', '=', 'a.id')
                ->on('ah.id_establecimiento', '=', 'p.id_establecimiento'); 
        })
        ->where('p.id', $evaluacion->id_prestacion)
        ->select(
            'p.id as id_evaluacion',
            'a.id as id_asegurado',
            'a.nro_doc_ident as nro_doc_ident_asegurado',
            'a.nombre as asegurado_nombre',
            'a.paterno as asegurado_paterno',
            'a.materno as asegurado_materno',
            'a.id_tipo_beneficiario',
            'p.id_medico',
            'm.nombre as medico_nombre',
            'm.paterno as medico_paterno',
            'm.materno as medico_materno',
            'tb.codigo as tipo_beneficiario_codigo',
            'tb.descripcion as tipo_beneficiario_descripcion',
            'ah.id as id_asegurado_historias',
            'ah.nro_historia',
            DB::raw("TO_CHAR(p.fecha_atencion::timestamp, 'DD-MM-YYYY HH24:MI:SS') AS fecha_atencion"),
            'e.nombre as nombre_establecimiento'
        )
        ->limit(1)
        ->first();

        $lastTraslado = DB::table('evaluaciones as e')->join('cambio_camas as cc', 'e.id', '=', 'cc.id_evolucion')->where('e.id_emergencia', $evaluacion->id_emergencia)->where('e.id_tipo_atencion', 3)
                            ->where('e.id_estado_reg', 1)->where('cc.estado_ubicacion', '3')->where('e.id', '<', $id_evaluacion)
                            ->select('e.id as id_evaluacion',
                                    'cc.id as id_cambio_cama',
                                    'cc.estado_ubicacion',
                                    DB::raw("TO_CHAR(cc.fecha_hora_inicio::timestamp, 'DD-MM-YYYY HH24:MI:SS') AS fecha_atencion"),
                                    'cc.created_at')
                            ->orderBy('e.created_at', 'desc')->limit(1)->first();

        // if (!$lastTraslado && !$lastTraslado->fecha_atencion) {
        //   $lastTraslado = $prestacion;
        // }

        if (!$lastTraslado || empty($lastTraslado->fecha_atencion)) {
            $lastTraslado = $prestacion;
        }

        $cambioCamaModel = new CambioCama();
        $traslado = $cambioCamaModel->getTrasladoByIdEvolucion($id_evaluacion);

        $model_prestacion = new Prestaciones(); 
        $diagnosticos_ingreso = $model_prestacion->GetDiagnosticoByIdPrestacionEmergencia($evaluacion->id_prestacion);

        $receta_diagnostico_model = new Receta_diagnostico();
        $diagnosticos_traslado = $receta_diagnostico_model->getDiagnosticosTrasladoByIdCambioCama($traslado->id);

        $nombre_establecimiento = $prestacion->nombre_establecimiento;

        //dato hospi, dato de admision
        $hospitalizacion = DB::table('hospitalizaciones as h')->where('h.id', $evaluacion->id_emergencia)->select(
            DB::raw("to_char(h.fecha_ingreso::date,'DD-MM-YYYY') as fecha_ingreso"), 
            DB::raw("to_char(h.hora_ingreso::time,'HH:MI:SS') as hora_ingreso")
        )->first();
        
        
        //$hospitalizacion = $model_receta->getHospitalizacionById_farmacia_new_x($evaluacion->id_emergencia);
        $primer_prestacion = Prestaciones::where('id_ext', $evaluacion->id_emergencia)->whereRaw("COALESCE(flag_intervencion_quirurgica, false) = false")->where('id_tipo_atencion', 3)->where('id_estado_reg', 1)->orderBy('id', 'asc')->first();
        
        if($primer_prestacion->id == $evaluacion->id_prestacion ):
            $prestacion->fecha_atencion = $hospitalizacion->fecha_ingreso.' '.$hospitalizacion->hora_ingreso;
        else:
            $evaluacion_h = DB::table('evaluaciones as e')->where('e.id_tipo_atencion',3)->where('e.id_emergencia', $evaluacion->id_emergencia)->where('e.flag_orden_traslado', 1)->select(
            DB::raw("to_char(e.created_at::date,'DD-MM-YYYY') as fecha_ingreso"), 
            DB::raw("to_char(e.created_at::time,'HH:MI:SS') as hora_ingreso")
        )->orderBy('id', 'desc')->offset(1)->first();
            
       
            $prestacion->fecha_atencion = $evaluacion_h->fecha_ingreso.' '.$evaluacion_h->hora_ingreso;
        endif;

        //echo $traslado->fecha_cambio_formateado;
        //echo '<br>';
        //echo $evaluacion->fecha_solicitud_orden_traslado;
        //dd($evaluacion);
        
        $pdf = \PDF::loadView('pdf.hospitalizacion_orden_traslado_firma', compact("evaluacion", "prestacion", "traslado", "diagnosticos_ingreso", "diagnosticos_traslado", "lastTraslado", "nombre_establecimiento"));
        $pdf->setPaper('A4', 'portrait');
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream("orden_traslado_".$id_evaluacion. ".pdf");
    }


    public function generateOrdenTrasladoPdfFirmar($id_evaluacion)
    {
        $path = public_path('tmp/pdf');
		if (!is_dir($path)) {
			mkdir($path);
		}
		$path = public_path('tmp/pdf/sin_firma/');
		if (!is_dir($path)) {
			mkdir($path);
		}

        $evaluacion = Evaluacion::find($id_evaluacion);
        $prestacion = DB::table('prestaciones as p')
        ->join('asegurados as a', 'p.id_asegurado', '=', 'a.id')
        ->join('medicos as m', 'p.id_medico', '=', 'm.id')
        ->leftJoin('tipo_beneficiarios as tb', 'a.id_tipo_beneficiario', '=', 'tb.id')
        ->join('asegurado_historias as ah', function($join) { 
            $join->on('ah.id_asegurado', '=', 'a.id')
                ->on('ah.id_establecimiento', '=', 'p.id_establecimiento'); 
        })
        ->where('p.id', $evaluacion->id_prestacion)
        ->select(
            'p.id as id_evaluacion',
            'a.id as id_asegurado',
            'a.nro_doc_ident as nro_doc_ident_asegurado',
            'a.nombre as asegurado_nombre',
            'a.paterno as asegurado_paterno',
            'a.materno as asegurado_materno',
            'a.id_tipo_beneficiario',
            'p.id_medico',
            'm.nombre as medico_nombre',
            'm.paterno as medico_paterno',
            'm.materno as medico_materno',
            'tb.codigo as tipo_beneficiario_codigo',
            'tb.descripcion as tipo_beneficiario_descripcion',
            'ah.id as id_asegurado_historias',
            'ah.nro_historia',
            DB::raw("TO_CHAR(p.fecha_atencion::timestamp, 'DD-MM-YYYY HH24:MI:SS') AS fecha_atencion")
        )
        ->limit(1)
        ->first();

         $lastTraslado = DB::table('evaluaciones as e')->join('cambio_camas as cc', 'e.id', '=', 'cc.id_evolucion')->where('e.id_emergencia', $evaluacion->id_emergencia)->where('e.id_tipo_atencion', 3)
                            ->where('e.id_estado_reg', 1)->where('cc.estado_ubicacion', '3')->where('e.id', '<', $id_evaluacion)
                            ->select('e.id as id_evaluacion',
                                    'cc.id as id_cambio_cama',
                                    'cc.estado_ubicacion',
                                    DB::raw("TO_CHAR(cc.fecha_hora_inicio::timestamp, 'DD-MM-YYYY HH24:MI:SS') AS fecha_atencion"),
                                    'cc.created_at')
                            ->orderBy('e.created_at', 'desc')->limit(1)->first();

        if (!$lastTraslado && !$lastTraslado->fecha_atencion) {
          $lastTraslado = $prestacion;
        }
        
        $cambioCamaModel = new CambioCama();
        $traslado = $cambioCamaModel->getTrasladoByIdEvolucion($id_evaluacion);

        $model_prestacion = new Prestaciones(); 
        $diagnosticos_ingreso = $model_prestacion->GetDiagnosticoByIdPrestacionEmergencia($evaluacion->id_prestacion);

        $receta_diagnostico_model = new Receta_diagnostico();
        $diagnosticos_traslado = $receta_diagnostico_model->getDiagnosticosTrasladoByIdCambioCama($traslado->id);

        $carpeta = date("Y")."/".date("m")."/".date("d");
        $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_orden_traslado_".$id_evaluacion.".pdf";
        $codigo_qr = QrCode::format('png')->size(150)->generate("https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_orden_traslado_" . $id_evaluacion . ".pdf");
        $pdf = \PDF::loadView('pdf.hospitalizacion_orden_traslado_firma', compact("evaluacion", "prestacion", "traslado", "diagnosticos_ingreso", "diagnosticos_traslado", "url_descarga", "codigo_qr","lastTraslado"));
        $pdf->setPaper('A4', 'portrait');
        $pdf->getDomPDF()->set_option("enable_php", true);
		file_put_contents($path."orden_traslado_".$id_evaluacion. ".pdf", $pdf->output());
    }

    public function firmarOrdenTraslado($id_evaluacion)
    {
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
        Excel::create($csv, function($excel) use($id_evaluacion){
            $excel->sheet('GENERAL', function($sheet) use($id_evaluacion) {
                $variable = [];
                $this->generateOrdenTrasladoPdfFirmar($id_evaluacion);
                array_push($variable, array(url().'/tmp/pdf/sin_firma/orden_traslado_'.$id_evaluacion.'.pdf', url().'/upload_firma/?name=cf_orden_traslado_'.$id_evaluacion.'.pdf&id_tipo=orden_traslado&id_resol='.$id_evaluacion, 'cf_orden_traslado_'.$id_evaluacion.'.pdf','0','45','350','70','Fecha de Firma: <DATE> Firmante: <SIGNER>',url().'/images/escudo.jpg','1','7'));
                $sheet->fromArray($variable, null, '', false, false);
            });
        })->store('csv',public_path('tmp/csv'));
        $obj = new Archive7z(public_path('tmp/zip/sin_firma/'.$csv.'.7z'));
        $obj->addEntry(public_path('/tmp/pdf/sin_firma/orden_traslado_'.$id_evaluacion.'.pdf'));
        $resultado["csv"]=$csv;
        $resultado["sw"]=true;
        return response()->json($resultado); 
    }

    public function updateEvaluacionOrdenTraslado(Request $request, $id_evaluacion)
    {
        if (intval($request->estado) < 0 && intval($request->estado) > 3 ) {
            return response()->json(["message" => "el estado de la orden no existe"], 400);
        }

        $evaluacion = Evaluacion::find($id_evaluacion);
        if (!isset($evaluacion)) {
            return response()->json(["message" => "La evaluación no existe"], 400);
        }

        $medico_model = new Medico();
        
        $ruta_orden_traslado = "cf_orden_traslado_{$id_evaluacion}.pdf";

        $message_response = "";

        if (intval($request->estado) === 0 || intval($request->estado) === 1) {
            $medico_solicita = $medico_model->GetMedicoByDNI(Auth::user()->dni);
            $evaluacion->update([
                'updated_at' => Carbon::now(),
                'flag_orden_traslado' => 1,
                'fecha_solicitud_orden_traslado' => Carbon::now(),
                'ruta_orden_traslado' => intval($request->estado) === 0 ? null: $ruta_orden_traslado,
                'estado_orden_traslado' => $request->estado,
                'id_medico_solicita_traslado' => $medico_solicita->id,
            ]);
            $message_response = "Se registro la solicitud de traslado exitosamente";
        } 

        if (intval($request->estado) === 2) {
            $medico_recibe = $medico_model->GetMedicoByDNI(Auth::user()->dni);
            $evaluacion->update([
                'updated_at' => Carbon::now(),
                'flag_orden_traslado' => 1,
                'fecha_recibe_orden_traslado' => Carbon::now(),
                'ruta_orden_traslado' => $ruta_orden_traslado,
                'estado_orden_traslado' => $request->estado,
                'id_medico_recibe_traslado' => $medico_recibe->id,
            ]);
            $message_response = "Se registro la confirmación del traslado exitosamente";
        } 

        if (intval($request->estado) === 3) {
            $medico_recibe = $medico_model->GetMedicoByDNI(Auth::user()->dni);
            $evaluacion->update([
                'updated_at' => Carbon::now(),
                'flag_orden_traslado' => 1,
                'fecha_recibe_orden_traslado' => Carbon::now(),
                'ruta_orden_traslado' => $ruta_orden_traslado,
                'estado_orden_traslado' => $request->estado,
                'id_medico_recibe_traslado' => $medico_recibe->id,
            ]);
            $message_response = "Se cancelo la orden de traslado exitosamente";
        } 
        
        return response()->json([
            "data" => ["id_evaluacion" => $id_evaluacion],
            "message" => $message_response
        ]);
    }

    public function mostrar_evolucion($id_evaluacion, $id_tipo_atencion)
    {

        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $model_evaluacion = new \App\Models\Evaluacione;
        $evaluacion = $model_evaluacion->GetEvaluacionById($id_evaluacion);
        $flag_bloqueo_atencion = $evaluacion ? DB::table('asegurados')->where('id', $evaluacion->id_asegurado)->value('flag_bloqueo_atencion') : null;
        if ($flag_bloqueo_atencion !== null && intval($flag_bloqueo_atencion) === 1) :
            return response()->json(['message' => 'No puede ver la atención de este paciente.'], 403);
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
        $model_kardex = new Kardexe();

        $farmacia = $modelfarma->GetFarmaByUser(Auth::user()->id);
        $id_farmacia = $farmacia->id;
        $flag_receta_dias = $farmacia->flag_receta_dias;

        $fechaServidor = $model_kardex->getFechaServidor();
        $date = Carbon::now();
        $fecha_actual = $date->format('d-m-Y');
        
        //$model_emergencias = new Emergencia;
        $model_evolucion = new Evaluacione;
        $paciente = $model_evolucion->GetBuscaPacienteHospitalizacionEvaluacion($evaluacion->id_emergencia);
        
        //verifico si existe idemergencia
        if(count($evaluacion)>0):

            $id_prestacion =  $evaluacion->id_prestacion;

            $prestacion = $model_prestacion->GetPrestacionByIdLaboratorioN3($id_prestacion);
            $hospitalizacione = Hospitalizacione::find($prestacion->id_ext);
            $gestacion = null;
            $puerperio = null;
          if (isset($hospitalizacione->id_gestacion)) {
            $gestacion = Gestacion::find($hospitalizacione->id_gestacion);
            error_log('HAY GESTACION EN EVALUACION: '.(string)$gestacion);
          }
          if (isset($hospitalizacione->id_puerperio)) {
            $puerperio = Puerperio::find($hospitalizacione->id_puerperio);
            error_log('HAY PUERPERIO EN EVALUACION: '.(string)$puerperio);
            $natis = $puerperio->natis;
            error_log('sus natis: '.(string)$natis);
          }

          $receta = $model_receta->GetDatosRecetasByIdEvaluacion($id_evaluacion);
          $diagnostico_atencion = $model_prestacion->GetDiagnosticoByIdPrestacionEmergencia($id_prestacion);
          $procedimiento_atencion = $model_prestacion->GetProcedimientosByIdPrestacionYTipo($id_prestacion,1);
           
          $laboratorio = $model_laboratorio->GetLaboratorioByIdPrestacionEvaluacion($id_prestacion,$id_evaluacion);
          $radiologia = $model_radiologia->GetRadiologiaByIdPrestacionEvaluacion($id_prestacion,$id_evaluacion);
          $id_farmacia = Auth::user()->id_farmacia;
          
          $model_emergencia = new \App\Models\Emergencia;
          $diagnostico_evaluacion = $model_evaluacion->GetDiagnosticoByIdEvaluacion($id_evaluacion);
          $procedimiento_evaluacion = $model_prestacion->GetProcedimientosByIdPrestacionYIdEvolucionYTipo($id_prestacion, $id_evaluacion, 1);
          
          $datoadicional = $model_emergencia->GetObtenerCama($evaluacion->cama_estado);
          $interconsultas = $model_interconsulta->GetInterconsultaByIdEvaluacion2($id_prestacion,$id_evaluacion);
          $referencia = $model_referencia->GetReferenciaByIdPrest($id_prestacion);

          $flag_no_encontrado = 0;
          $model_asegurado= new Asegurado();
          if($prestacion->id_asegurado_titular!=null || $prestacion->id_asegurado_titular>0):
            $dato_titular = $model_asegurado->Busca_datos_titular($prestacion->id_asegurado_titular);
          else:
            $flag_no_encontrado = 1;
          endif;

          $date = Carbon::now();
            $talla_cm = $evaluacion->talla_cm;
            $peso_kg = $evaluacion->peso_kg;
            $edadpaciente = $prestacion->edadpaciente;
            $sexo = $prestacion->sexo;
            $peso_ideal = "";
            $imc = "";
            
            // Cálculo del peso ideal
            if ($talla_cm != "0") { 
                // Peso por altura
                $peso_ideal = 50 + (($talla_cm - 150) / 4) * 3 + ($edadpaciente - 20) / 4;
                if ($sexo == "M")
                $peso_ideal = $peso_ideal * 0.9;
                
                if($peso_ideal <= 0)
                $peso_ideal = '';
                
            } else {
                $peso_ideal = "";
            }

            // Cálculo del IMC
            if ($peso_kg != "0" && $talla_cm != "0") {
                $talla_en_metros = $talla_cm * 0.01;
                $imc = "";
                if ($peso_kg > 0 && $talla_cm > 0) {
                $imc = $peso_kg / ($talla_en_metros * $talla_en_metros);
                $imc = number_format($imc, 2, '.', '');
                }
            } else {
                $imc = "";
            }
            // Formateo final
            $peso_ideal = ($peso_ideal == "") ? "-" : $peso_ideal;
            $imc = ($imc == "") ? "-" : $imc;

            $natisEvaluacion = [];
            if (isset($evaluacion->id_puerperio)) {
                $model_nati = new Nati();
                $natisEvaluacion = $model_nati->getByIdPuerpera($evaluacion->id_puerperio);
            }


            $cambioCamaModel = new CambioCama();
            $traslado = $cambioCamaModel->getTrasladoByIdEvolucion($id_evaluacion);
            $diagnosticos_traslado = null;
            if (isset($traslado)) {
                $receta_diagnostico_model = new Receta_diagnostico();
                $diagnosticos_traslado = $receta_diagnostico_model->getDiagnosticosTrasladoByIdCambioCama($traslado->id);
            }

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
            //========================================================================================

          return view('frontend.hospitalizacion.evolucion.mostrar_evolucion', compact('farmacia', 'prestacion', 'diagnostico_atencion','diagnostico_evaluacion', 'procedimiento_atencion', 'procedimiento_evaluacion', 'medicamentos', 'fechaServidor', 'receta',  'laboratorio', 'radiologia','referencia','evaluacion','paciente','id_evaluacion','datoadicional','interconsultas','dato_titular', 'gestacion', 'puerperio', 'natis', 'peso_ideal','imc', 'natisEvaluacion', 'traslado', 'diagnosticos_traslado','flag_radiologia','ordenImagenesAnulada','ordenes'));
      else:
         return redirect('/all_pacientes_evoluciones_by_hospitalizacion/')->withInput()->withErrors(array('Evaluacion no encontrado ..!'));
      endif;
      
    }

    public function mostrar_evolucion_hospitalizacion($id_evaluacion, $id_tipo_atencion)
    {

        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $model_evaluacion = new \App\Models\Evaluacione;
        $evaluacion = $model_evaluacion->GetEvaluacionById($id_evaluacion);
        $flag_bloqueo_atencion = $evaluacion ? DB::table('asegurados')->where('id', $evaluacion->id_asegurado)->value('flag_bloqueo_atencion') : null;
        if ($flag_bloqueo_atencion !== null && intval($flag_bloqueo_atencion) === 1) :
            return response()->json(['message' => 'No puede ver la atención de este paciente.'], 403);
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
        $model_kardex = new Kardexe();

        $farmacia = $modelfarma->GetFarmaByUser(Auth::user()->id);
        $id_farmacia = $farmacia->id;
        $flag_receta_dias = $farmacia->flag_receta_dias;

        $fechaServidor = $model_kardex->getFechaServidor();
        $date = Carbon::now();
        $fecha_actual = $date->format('d-m-Y');

        $paciente = $model_evaluacion->GetBuscaPacienteHospitalizacionEvaluacion($evaluacion->id_emergencia);
        
        //verifico si existe idemergencia
        if(count($evaluacion)>0):

            $id_prestacion =  $evaluacion->id_prestacion;

            $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
            $hospitalizacione = Hospitalizacione::find($prestacion->id_ext);
            $gestacion = null;
            $puerperio = null;
          if (isset($hospitalizacione->id_gestacion)) {
            $gestacion = Gestacion::find($hospitalizacione->id_gestacion);
            error_log('HAY GESTACION EN EVALUACION: '.(string)$gestacion);
          }
          if (isset($hospitalizacione->id_puerperio)) {
            $puerperio = Puerperio::find($hospitalizacione->id_puerperio);
            error_log('HAY PUERPERIO EN EVALUACION: '.(string)$puerperio);
            $natis = $puerperio->natis;
            error_log('sus natis: '.(string)$natis);
          }

          $receta = $model_receta->GetDatosRecetasByIdEvaluacion($id_evaluacion);
          $diagnostico_atencion = $model_prestacion->GetDiagnosticoByIdPrestacionEmergencia($id_prestacion);
          $procedimiento_atencion = $model_prestacion->GetProcedimientosByIdPrestacionYTipo($id_prestacion,1);
           
          $laboratorio = $model_laboratorio->GetLaboratorioByIdPrestacionEvaluacion($id_prestacion,$id_evaluacion);
          $radiologia = $model_radiologia->GetRadiologiaByIdPrestacionEvaluacion($id_prestacion,$id_evaluacion);
          $id_farmacia = Auth::user()->id_farmacia;
          
          $model_emergencia = new \App\Models\Emergencia;
          $diagnostico_evaluacion = $model_evaluacion->GetDiagnosticoByIdEvaluacion($id_evaluacion);
          $procedimiento_evaluacion = $model_prestacion->GetProcedimientosByIdPrestacionYIdEvolucionYTipo($id_prestacion, $id_evaluacion, 1);
          
          $datoadicional = $model_emergencia->GetObtenerCama($evaluacion->cama_estado);
          $interconsultas = $model_interconsulta->GetInterconsultaByIdEvaluacion2($id_prestacion,$id_evaluacion);
          $referencia = $model_referencia->GetReferenciaByIdPrest($id_prestacion);

          $flag_no_encontrado = 0;
          $model_asegurado= new Asegurado();
          if($prestacion->id_asegurado_titular!=null || $prestacion->id_asegurado_titular>0):
            $dato_titular = $model_asegurado->Busca_datos_titular($prestacion->id_asegurado_titular);
          else:
            $flag_no_encontrado = 1;
          endif;

          $date = Carbon::now();
            $talla_cm = $evaluacion->talla_cm;
            $peso_kg = $evaluacion->peso_kg;
            $edadpaciente = $prestacion->edadpaciente;
            $sexo = $prestacion->sexo;
            $peso_ideal = "";
            $imc = "";
            
            // Cálculo del peso ideal
            if ($talla_cm != "0") { 
                // Peso por altura
                $peso_ideal = 50 + (($talla_cm - 150) / 4) * 3 + ($edadpaciente - 20) / 4;
                if ($sexo == "M")
                $peso_ideal = $peso_ideal * 0.9;
                
                if($peso_ideal <= 0)
                $peso_ideal = '';
                
            } else {
                $peso_ideal = "";
            }

            // Cálculo del IMC
            if ($peso_kg != "0" && $talla_cm != "0") {
                $talla_en_metros = $talla_cm * 0.01;
                $imc = "";
                if ($peso_kg > 0 && $talla_cm > 0) {
                $imc = $peso_kg / ($talla_en_metros * $talla_en_metros);
                $imc = number_format($imc, 2, '.', '');
                }
            } else {
                $imc = "";
            }
            // Formateo final
            $peso_ideal = ($peso_ideal == "") ? "-" : $peso_ideal;
            $imc = ($imc == "") ? "-" : $imc;

            $natisEvaluacion = [];
            if (isset($evaluacion->id_puerperio)) {
                $model_nati = new Nati();
                $natisEvaluacion = $model_nati->getByIdPuerpera($evaluacion->id_puerperio);
            }


            $cambioCamaModel = new CambioCama();
            $traslado = $cambioCamaModel->getTrasladoByIdEvolucion($id_evaluacion);
            $diagnosticos_traslado = null;
            if (isset($traslado)) {
                $receta_diagnostico_model = new Receta_diagnostico();
                $diagnosticos_traslado = $receta_diagnostico_model->getDiagnosticosTrasladoByIdCambioCama($traslado->id);
            }

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
            //========================================================================================

          return view('frontend.hospitalizacion.evolucion.mostrar_evolucion_hospitalizacion', compact('farmacia', 'prestacion', 'diagnostico_atencion','diagnostico_evaluacion', 'procedimiento_atencion', 'procedimiento_evaluacion', 'medicamentos', 'fechaServidor', 'receta',  'laboratorio', 'radiologia','referencia','evaluacion','paciente','id_evaluacion','datoadicional','interconsultas','dato_titular', 'gestacion', 'puerperio', 'natis', 'peso_ideal','imc', 'natisEvaluacion', 'traslado', 'diagnosticos_traslado','flag_radiologia','ordenImagenesAnulada','ordenes'));
      else:
         return redirect('/all_pacientes_evoluciones_by_hospitalizacion/')->withInput()->withErrors(array('Evaluacion no encontrado ..!'));
      endif;
      
    }

    public function registrar_evolucion_hospitalizacion_adicional(Request $request)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $registro_validado = false;
        $mensaje_error = "";

        if (!empty($request->id_prestacion)) {
            $prestacion_evolucion_adicional = Prestaciones::find($request->id_prestacion);
            if ($prestacion_evolucion_adicional && $prestacion_evolucion_adicional->id_tipo_atencion == 3 && !empty($prestacion_evolucion_adicional->id_ext)) {
                $hospitalizacion_evolucion_adicional = Hospitalizacione::find($prestacion_evolucion_adicional->id_ext);
                if ($hospitalizacion_evolucion_adicional && !$hospitalizacion_evolucion_adicional->historiaClinicaCompleta()) {
                    return redirect('/form_atencion_prestacion_hspcu/' . Auth::user()->id . '/' . $prestacion_evolucion_adicional->id_ext)->withInput()->withErrors(array('Debe completar la Historia Clínica antes de registrar evoluciones..!'));
                }
            }
        }

        DB::beginTransaction();
        try {
        $search = array('+', ',','"','/', '{', '}');
        $replace = array('\+', '\,', '\"', '\/', '\{', '\}');

        $id_prestacion = $request->id_prestacion;
        $id_evaluacion = $request->id_evaluacion;

        // Generacion de array de productos de Receta
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
        $model_evaluacion = new Evaluacione();
        $receta = new Receta_vale();

        // Generacion de array de diagnosticos
        //$diagnosticos = $model_prestaciones->GetDiagnosticoByIdPrestacionEmergencia($id_prestacion);
        $diagnosticos = $model_evaluacion->GetDiagnosticoByIdEvaluacion($id_evaluacion);
        
        $p_diagnostico = "";
        $p_diagnostico .= "{";
        foreach ($diagnosticos as $row) :
            $p_diagnostico .= "{";
            $p_diagnostico .= $row->id_diagnostico . ",";
            $p_diagnostico .= ($row->id_tipo_diagnostico > 0) ? $row->id_tipo_diagnostico : "0";
            $p_diagnostico .= ",";
            $p_diagnostico .= ($row->id_categoria > 0) ? $row->id_categoria : "0";
            $p_diagnostico .= ",";
            $p_diagnostico .= ($row->id_clasificacion_diagnosticos > 0) ? $row->id_clasificacion_diagnosticos : "0";
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
        $subconsultorio = \App\Models\SubConsultorio::find($prestacion->id_sub_consultorio);
        $cita = Cita::find($prestacion->id_cita);
        $asegurado = Asegurado::find($cita->id_asegurado);
        $titular = Asegurado::find($cita->id_titular);
        //$historia = Asegurado_historia::find($cita->id_asegurado);
        $historia = \App\Models\Asegurado_historia::where('id_asegurado',$cita->id_asegurado)->where('id_establecimiento',$cita->id_establecimiento)->first();//dd($request, $subconsultorio, $historia);

        $id_medico = $prestacion->id_medico;
        $id_tiporeceta = $prestacion->id_tipo_atencion;
        $fecha_atencion = $request->fecha_expedicion;
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

        $evolucion = Evaluacione::find($id_evaluacion);
        $hospitalizacione = Hospitalizacione::where('id', $evolucion->id_emergencia)->where('estado', 4)->first();
        $id_consultorio = $hospitalizacione->id_servicio_encargado;
        
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
                $p_laboratorio .= "},";
            }
            endforeach;
            if (strlen($p_laboratorio) > 1) $p_laboratorio = substr($p_laboratorio, 0, -1);
            $p_laboratorio .= "}";
            if($request->id_examen_lab[0]!=""):
                if (intval($request->id_establecimiento) === 76):
                $has_observacion_unica = (intval($request->id_nivel) === 3) ? 1 : 0;
                $id_interconsulta = 0;
                $id_area = 0;
                $lab_orden_observacion_unica = intval($request->id_nivel) === 3 ? $request->lab_orden_observacion : null;
                $flag_cq = (isset($request->id_perfil_preoperatorio) && intval($request->id_perfil_preoperatorio) > 0) ? 1 : 0;
                $id_hora_toma_muestra = (intval($request->id_nivel) === 3 && isset($request->id_hora_toma_muestra)) ? $request->id_hora_toma_muestra : null;

                $id_procedimiento_lab = $model_procedimiento->RegistrarLaboratorioOrden3($request->id_user, $id_prestacion, $request->id_establecimiento, $request->fecha_hora_atencion, $p_laboratorio, $has_observacion_unica, $id_evaluacion, $request->id_farmacia, $id_interconsulta, $request->id_tipo_atencion, $request->id_servicio_encargado, $id_area, $request->id_cama, $request->id_medico, $request->id_asegurado, $lab_orden_observacion_unica, $flag_cq, $id_hora_toma_muestra);
                else:
            $id_procedimiento_lab = $model_procedimiento->RegistrarProcedimiento2($request->id_user, $id_prestacion, $request->id_establecimiento, $request->fecha_atencion, $p_laboratorio, 2, $id_evaluacion, $request->id_farmacia);
                endif;
                $model_lab = new \App\Models\Laboratorio;
                $ok=$model_lab->ActualizaIdEvaluacion($id_procedimiento_lab,$id_evaluacion);
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
            if (intval($request->id_establecimiento) == 76) {
                $updated = DB::table('rua_imagenologia')->where('id', $request->id_rua_imagenes)->update(['id_rad' => $id_procedimiento_rad,'id_evol'=> $id_evaluacion, 'id_estado_reg' => 1]);
            }
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            $model_rad = new \App\Models\Radiologia;
            $ok=$model_rad->ActualizaIdEvaluacion($id_procedimiento_rad,$id_evaluacion);
            endif;
        endif;
        /***************************************************************************/
        
        $model_interconsulta = new \App\Models\Interconsulta;
        if (!empty($request->id_servicio_interconsulta)) {

            // Quitar duplicados de interconsultas en backend
            $id_servicio_interconsulta_unicos = [];
            $hidden_select_otorrino_unicos = [];      // Código UPSS para OTORRINO
            $hidden_select_diagnostico_unicos = [];     // Código UPSS para DIAGNOSTICO
            $motivo_interconsulta_unicos = [];
            $personal_coordinacion_unicos = [];

            foreach ($request->id_servicio_interconsulta as $index => $id_servicio) {
                if (!in_array($id_servicio, $id_servicio_interconsulta_unicos)) {
                    $id_servicio_interconsulta_unicos[] = $id_servicio;
                    $hidden_select_otorrino_unicos[] = $request->hidden_select_OTORRINO[$index];
                    $hidden_select_diagnostico_unicos[] = $request->hidden_select_DIAGNOSTICO[$index];
                    $motivo_interconsulta_unicos[] = $request->motivo_interconsulta[$index];
                    $personal_coordinacion = $request->interconsulta_personal_coodinacion[$index];
                    $personal_coordinacion_unicos[] = $personal_coordinacion != "" ? $personal_coordinacion : "NULL";
                }
            }
            // Fin quitar duplicados de interconsultas en backend

            $elementos = [];

            foreach ($id_servicio_interconsulta_unicos as $key => $value) {
                if (!empty($value)) {
                    // Escapa los caracteres especiales en el motivo de la interconsulta
                    $motivo_interconsulta = str_replace($search, $replace, $motivo_interconsulta_unicos[$key]);
                    $motivo_interconsulta = addslashes($motivo_interconsulta); // Evita problemas con comillas

                    $hidden_select_OTORRINO = isset($hidden_select_otorrino_unicos[$key])
                        ? addslashes($hidden_select_otorrino_unicos[$key])
                        : '';
                    $hidden_select_DIAGNOSTICO = isset($hidden_select_diagnostico_unicos[$key])
                        ? addslashes($hidden_select_diagnostico_unicos[$key])
                        : '';

                    // Agrega el elemento en formato de array PostgreSQL con 6 valores:
                    // [id_servicio, turno, "0", motivo_interconsulta, hidden_select_OTORRINO, hidden_select_DIAGNOSTICO]
                    $elementos[] = sprintf(
                        '{"%s","%s","%s","%s","%s","%s","%s","%s"}',
                        $value,
                        $turno,
                        "0",
                        $motivo_interconsulta,
                        $hidden_select_OTORRINO,
                        $hidden_select_DIAGNOSTICO,
                        $request->is_estable,
                        str_replace($search, $replace, $personal_coordinacion_unicos[$key])
                    );
                }
            }

            // Une los elementos del array
            if (!empty($elementos)) {
                $p_interconsulta = '{' . implode(',', $elementos) . '}';

                // Valida que al menos hay un servicio antes de registrar
                if (!empty($id_servicio_interconsulta_unicos)) {
                    $id_interconsulta = $model_interconsulta->RegistrarInterconsultaEvaluacion(
                        $request->id_prestacion, 
                        $asegurado->id, 
                        $request->id_tipo_atencion, 
                        $request->fecha_atencion, 
                        $request->id_medico, 
                        $request->id_establecimiento, 
                        $p_interconsulta, 
                        $id_evaluacion
                    );
                    if (empty($id_interconsulta) || intval($id_interconsulta) <= 0):
                        throw new \Exception("Error al intentar registrar la interconsulta.");
                    endif;
                }
            }
        }

        /* $model_interconsulta = new \App\Models\Interconsulta;
        if (!empty($request->id_servicio_interconsulta)) {

            // Quitar duplicados de interconsultas en backend
            $id_servicio_interconsulta_unicos = [];
            $hidden_select_otorrino_unicos = [];  // Codigo UPSS
            $motivo_interconsulta_unicos = [];

            foreach ($request->id_servicio_interconsulta as $index => $id_servicio) {
                if (!in_array($id_servicio, $id_servicio_interconsulta_unicos)) {
                    $id_servicio_interconsulta_unicos[] = $id_servicio;
                    $hidden_select_otorrino_unicos[] = $request->hidden_select_OTORRINO[$index];
                    $motivo_interconsulta_unicos[] = $request->motivo_interconsulta[$index];
                }
            }
            // Fin Quitar duplicados de interconsultas en backend

            $elementos = [];
        
            foreach ($id_servicio_interconsulta_unicos as $key => $value) {
                if (!empty($value)) {
                    // Escapa los caracteres especiales en el motivo de la interconsulta
                    $motivo_interconsulta = str_replace($search, $replace, $motivo_interconsulta_unicos[$key]);
                    $motivo_interconsulta = addslashes($motivo_interconsulta); // Evita problemas con comillas
                    $hidden_select_OTORRINO = isset($hidden_select_otorrino_unicos[$key]) ? addslashes($hidden_select_otorrino_unicos[$key]) : '';

                    // Agrega el elemento en formato de array PostgreSQL
                    $elementos[] = sprintf('{"%s","%s","%s","%s","%s"}', $value, $turno, "0", $motivo_interconsulta, $hidden_select_OTORRINO);
                }
            }
        
            // Une los elementos del array
            if (!empty($elementos)) {
                $p_interconsulta = '{' . implode(',', $elementos) . '}';
        
                // Valida que al menos hay un servicio antes de registrar
                if (!empty($id_servicio_interconsulta_unicos)) {
                    $id_interconsulta = $model_interconsulta->RegistrarInterconsultaEvaluacion(
                        $request->id_prestacion, 
                        $asegurado->id, 
                        $request->id_tipo_atencion, 
                        $request->fecha_atencion, 
                        $request->id_medico, 
                        $request->id_establecimiento, 
                        $p_interconsulta, 
                        $id_evaluacion
                    );
                }
            }
        } */

        //if($request->id_servicio_interconsulta!=null):
          //  $p_interconsulta = "";
           // $p_interconsulta .= "{";
           // foreach ($request->id_servicio_interconsulta as $key => $value) :
           // if ($request->id_servicio_interconsulta[$key] != "") {
           //     $p_interconsulta .= "{";
           //     $p_interconsulta .= $request->id_servicio_interconsulta[$key] . ",";
            //    $p_interconsulta .= $turno . ",";
            //    $p_interconsulta .= "0,";
            //    $p_interconsulta .= str_replace($search, $replace, $request->motivo_interconsulta[$key]) ;
            //    $p_interconsulta .= "},";
           // }
           // endforeach;
           // if (strlen($p_interconsulta) > 1) $p_interconsulta = substr($p_interconsulta, 0, -1);
           // $p_interconsulta .= "}"; 

           // if($request->id_servicio_interconsulta[0]!=""):
            //    $id_interconsulta = $model_interconsulta->RegistrarInterconsultaEvaluacion($request->id_prestacion, $asegurado->id, $request->id_tipo_atencion, $request->fecha_atencion, $request->id_medico, $request->id_establecimiento, $p_interconsulta,$id_evaluacion);
            //$model_inter = new \App\Models\Interconsulta;
            //$ok=$model_inter->ActualizaIdEvaluacion($id_interconsulta,$id_evaluacion);
           // endif;
        //endif;
        
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
            if (intval($id_receta_vale) <= 0):
                throw new \Exception("Error al intentar registrar la receta.");
            endif;
            $model_rec_val = new \App\Models\Receta_vale;
            $ok=$model_rec_val->ActualizaIdEvaluacion($id_receta_vale,$id_evaluacion);
            if (!$ok):
                throw new \Exception("Error al intentar vincular la receta con la evaluacion.");
            endif;
        else :
            $id_receta_vale = 1;
        endif;

            DB::commit();
            $registro_validado = true;

        } catch (\Exception $e) {
            DB::rollback();
            $mensaje_error = $e->getMessage();
            Log::error("Registro Evolucion Hospitalizacion Adicional => id_evaluacion: " . $request->id_evaluacion .
                "\n\nmensaje: " . $e->getMessage() .
                "\n\ndatos del request: " . json_encode($request->all()) .
                "\n\nstack trace: " . $e->getTraceAsString()
            );
        } catch (\Throwable $th) {
            DB::rollback();
            $mensaje_error = $th->getMessage();
            Log::critical("Registro Evolucion Hospitalizacion Adicional => id_evaluacion: " . $request->id_evaluacion .
                "\n\nmensaje: " . $th->getMessage() .
                "\n\ndatos del request: " . json_encode($request->all()) .
                "\n\nstack trace: " . $th->getTraceAsString()
            );
        }

        if ($registro_validado):
            Session::flash('flash_message', 'Registro adicional exitoso');
            return redirect('/ver_evaluacion_hospitalizacion/'.$request->id_user.'/'.$id_evaluacion);
        else:
            return redirect('/ver_evaluacion_hospitalizacion/'.$request->id_user.'/'.$request->id_evaluacion)->withInput()->withErrors(array('<strong>Error:</strong> No se pudo registrar el adicional de la evolución, vuelva a intentarlo.<br><br>' . $mensaje_error));
        endif;
    }

    public function modal_historial_indicaciones_terapeuticas($id_hospitalizacion)
    {   $model_evaluaciones = new \App\Models\Evaluacione;
      
        $indicaciones = $model_evaluaciones->GetIndicacionesTerapeuticas($id_hospitalizacion);
      
        return view('frontend.evaluaciones.mostrar_indicaciones_terapeuticas', compact('indicaciones'));
    }

    public function edit_evolucion_2($id_evolucion)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

		// $id_evaluacion = 0;

        $modelfarma = new \App\Models\Farmacia;
        // $model_producto = new \App\Models\Producto;
        $model_establecimiento = new \App\Models\Establecimiento;
        $model_laboratorio = new \App\Models\Laboratorio;
        $model_radiologia = new \App\Models\Radiologia;
        $model_interconsulta = new \App\Models\Interconsulta;
        $model_evolucion = new \App\Models\Evaluacione;
        $model_medicos = new Medico;
        $modelcama= new \App\Models\Cama();
        
        $evolucion = Evaluacione::find($id_evolucion);
        $medico = $model_medicos->GetMedicoById($evolucion->id_medico);

        // Validacion de personal de salud logueado con personal de salud que registro la evolucion
        if (Auth::user()->dni != "48839708"):
        $medico_logueado = $model_medicos->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni, Auth::user()->id_establecimiento);

        if (!empty($medico_logueado)):
            if (intval($medico->id) !== intval($medico_logueado->id)):
                return back()->withInput()->withErrors(array("No puede modificar la Evolución seleccionada, porque no es el Profesional que lo registró."));
            endif;
        else:
            return back()->withInput()->withErrors(array("No está registrado como Profesional de Salud en este Establecimiento médico."));
        endif;
        endif;
        // Fin Validacion de personal de salud logueado con personal de salud que registro la evolucion

        $hospitalizacione = Hospitalizacione::find($evolucion->id_emergencia);
        if (!is_null($hospitalizacione) && intval($hospitalizacione->estado) === 4):

            $id_hospitalizacion = $hospitalizacione->id;
            $model_hospitalizacion = new \App\Models\IntraOperatorio();
            $ultima_atencion = $model_hospitalizacion->getUltimaAtencionXAtencion($id_hospitalizacion, 3);
            
            if(is_object($ultima_atencion)):
                // Deberia sera de la ultima atencion, no solo si es prestacion
                $primera_atencion = Prestaciones::where('id_ext', $id_hospitalizacion)->where('id_tipo_atencion', 3)->whereRaw("COALESCE(flag_intervencion_quirurgica, false) = false")->where('id_estado_reg', 1)->orderBy('id', 'desc')->first();
                $id_prestacion = $primera_atencion->id;

                $farmacia = $modelfarma->GetFarmaByUser(Auth::user()->id);
                $date = Carbon::now();
                $fechaServidor = $date->format('Y-m-d H:i:s');
                $hora = $date->format('H:i');
                
                $id_ipress = $farmacia->id_establecimiento;
                $establecimiento = $model_establecimiento->GetById($id_ipress);
                $id_farmacia = Auth::user()->id_farmacia;
                $nivel = $establecimiento[0]->nivel;
                
                $farmacias = $modelfarma->GetFarmaciaById($id_farmacia);
                $estado=4;
                $id_tipo_atencion=3;
                $datos_evaluacion = $model_evolucion->GetBuscaHospitalizacionEvaluacion($id_hospitalizacion, $medico->id, $estado);
                
                // $producto = $model_producto->getProductosByNivelAndIdServicio($nivel, $id_ipress, $id_farmacia);
                $flag = 0 ;
                $id_tipo_atencione=3;
                $codigo_upss = "";

                $model_emergencia = new \App\Models\Emergencia;
                $dato_cama = $model_emergencia->GetObtenerCama($hospitalizacione->id_cama_estado);
                $cuartos = $modelcama->GetAllPabellonesYServicioOSalaYCuartos($hospitalizacione->id_establecimiento,'HOSPITALIZACION');
                $camas = $modelcama->GetCamasByIdEstablecimientoYNombreTipoAtencionYIdCuarto($hospitalizacione->id_establecimiento,'HOSPITALIZACION',$dato_cama->id_consultorio);

                $id_servicio= $dato_cama->id_consultorio;

                // $diagnosticos = DB::table('receta_diagnosticos')
                //         ->join('diagnosticos', 'receta_diagnosticos.id_diagnostico', '=', 'diagnosticos.id')
                //         ->select('receta_diagnosticos.id', 'diagnosticos.id', 'diagnosticos.codigo', 'diagnosticos.nombre', 'receta_diagnosticos.id_clasificacion_diagnosticos', 'receta_diagnosticos.id_categoria')
                //         ->where('receta_diagnosticos.id_prestacion', '=', $id_prestacion)
                //         ->where('receta_diagnosticos.id_interconsulta', '=', 0)
                //         ->where('receta_diagnosticos.estado', '=', 1)
                //         ->get();

                $model_prestacion = new Prestaciones();
                $arraydeprocedimientos = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);

                $flag_no_encontrado = 0;
                $model_asegurado= new Asegurado();
                if($datos_evaluacion->id_asegurado_titular!=null || $datos_evaluacion->id_asegurado_titular>0):
                    $dato_titular = $model_asegurado->Busca_datos_titular($datos_evaluacion->id_asegurado_titular);
                else:
                    $flag_no_encontrado = 1;
                endif;

                $codigo_siteds_valido = "0";
                // Se obtiene el ultimo codigo siteds , corregido
                //model_siteds = new \App\Models\Siteds;
                //$codigo_siteds = $model_siteds->getUltimoCodigoSitedsEnElDia($id_hospitalizacion, 3, $ultima_atencion->tipo_atencion_ultima);
                $codigo_siteds = $evolucion->cod_siteds_susalud;

                if (!str_contains($codigo_siteds, 't') && !str_contains($codigo_siteds, 'T') && $codigo_siteds != "") {
                    $codigo_siteds_valido = "1";
                }

                $ordenes_laboratorio = null;
                $ordenes_imagenologia = null;
                $interconsultas = null;
                if ($ultima_atencion->tipo_atencion_ultima == "prestacion") {
                    $ordenes_laboratorio = $model_laboratorio->GetLaboratorioByIdPrestacion($ultima_atencion->id_prestacion);
                    $ordenes_imagenologia = $model_radiologia->GetRadiologiaByIdPrestacion($ultima_atencion->id_prestacion);
                    if ($ordenes_imagenologia->isEmpty()) $ordenes_imagenologia = null;
                    $interconsultas = $model_interconsulta->GetInterconsultaByIdPrestacion($ultima_atencion->id_prestacion);
                }

                
                //dd($arraydeprocedimientos);
                /******************************************************** */
                // $date = Carbon::now();
                // $hora=$date->toTimeString(); 

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

                $tipo_doc_nati = tipoDocNati::all();

                $tipo_partos = DB::select("SELECT * FROM tipo_partos tp ");
        
                $gestacionModel = new Gestacion;
                $lastGestacion = null;
                $lastGestacionArray = $gestacionModel->getLastByAseguradoDNI($datos_evaluacion->nro_doc_ident);
                if (isset($lastGestacionArray) && count($lastGestacionArray) > 0) {
                  $lastGestacion = $lastGestacionArray[0];
                }

                $model_signos = new \App\Models\signosVitales();
                $signo_vital = $model_signos->GetUltimoRegistroSignoVital(3, $id_hospitalizacion);
                $signos_vitales = null;

                if ($signo_vital != null){ 
                    $signos_vitales = $model_signos->GetSignosVitalesDetalleById($signo_vital->id);
                }

                $upss_model = new Upss();

                $upps = $upss_model->ShowConsultoriosUpss($datos_evaluacion->id_consultorio);

                $listUpss = $upss_model->ShowUPSS_by_Hospitalizacion();

                $upss_encargado = Upss::where('id_consultorio', $hospitalizacione->id_servicio_encargado)
                            ->where('id', $hospitalizacione->id_upss_encargado)
                            ->where('id_tipo_atencion', 3)
                            ->where('estado', 1)
                            ->first();
                $existe_servicio_atencion = DB::table('medico_consultorios as mc')
                            ->join('consultorios as c', 'mc.id_consultorio', '=', 'c.id')
                            ->where('mc.id_establecimiento', $id_ipress)
                            ->where('mc.id_medico', $medico->id)
                            ->where('mc.estado', 1)
                            ->where('c.id', $hospitalizacione->id_servicio_encargado)
                            ->exists();
                
                $regularizar = ($upss_encargado == null) ? "1" : "0";

                $model_consultorio = new \App\Models\Consultorio;
                $establecimiento_servicios = $model_consultorio->GetServiciosPorEstablecimiento($id_ipress);

                if ($regularizar == "0") {
                    $servicio_ubicacion = $model_consultorio->GetNombreById($hospitalizacione->id_servicio_destino);
                    $id_servicio_actual = $hospitalizacione->id_servicio_destino;
                } else {
                    $model_hospitalizacion2 = new \App\Models\Hospitalizacione;
                    $data_cama = $model_hospitalizacion2->GetObtenerCama($hospitalizacione->id_cama_estado);
                    $servicio_ubicacion = $model_consultorio->GetNombreById($data_cama->id_consultorio);
                    $id_servicio_actual = $data_cama->id_consultorio;
                }
                /****************BUSCA SI EL SERVICIO TIENE EL FLAG QUIRURGICO***********/
                $tiene_flag_quirurgico = \App\Models\Consultorio::where('id',$hospitalizacione->id_servicio_encargado)->where('flag_quirurgico',1)->exists();
                /****************FIN BUSCA SI EL SERVICIO TIENE EL FLAG QUIRURGICO***********/
                
                $upsses = "SELECT DISTINCT up.id id_upss, up.descripcion_upss, up.codigo codigo_upss, co.estado 
                            FROM upsses as up
                            INNER JOIN consultorios as co ON co.id = up.id_consultorio
                            WHERE up.estado = 1 
                            And up.id_tipo_atencion = 3
                            ORDER BY up.descripcion_upss;";
                $upsses = DB::select($upsses);
                // dd($datos_evaluacion, $upps, $listUpss);

				//////////// hospitalizacion_atenciones
				/// Hallar el valor del id de tabla puerperios
				$id_puerperio = 0;
				$obj_hosp_atenciones = DB::table('hospitalizacion_atenciones')
													->select("id_puerperio")
													->where('id_prestacion',$id_prestacion)
													->where('id_ext',$id_hospitalizacion)
													->first();	
				if($obj_hosp_atenciones){ 
					$id_puerperio = $obj_hosp_atenciones->id_puerperio;
					$id_puerperio = intval($id_puerperio);
				}

				//////////// puerperios
				$obj_puerperio = DB::table('puerperios')
					->select(
						'id as hosp_pue_id',
						DB::raw("TO_CHAR(fecha_parto, 'DD/MM/YYYY') as hosp_pue_parto_fecha"),
						DB::raw("TO_CHAR(fecha_parto, 'HH24:MI:SS') as hosp_pue_parto_hora"),
						'id_tipo_parto as hosp_pue_tipo_parto_id',
						'complicaciones as hosp_pue_complicaciones'
					)
					->where('id', $id_puerperio)
					->first();
				$puerperio = array();
				$puerperio['frmPuerperaActive'] = 'false';
				$puerperio['hosp_pue_id'] = '0';
				$puerperio['hosp_pue_parto_fecha'] = '';
				$puerperio['hosp_pue_parto_hora'] = '0';
				$puerperio['hosp_pue_tipo_parto_id'] = '';
				$puerperio['hosp_pue_complicaciones'] = '';
				$puerperio = (object)$puerperio;
				if($obj_puerperio){ 
					$puerperio = $obj_puerperio;
					if($id_puerperio>0){ $puerperio->frmPuerperaActive = 'true'; } else { $puerperio->frmPuerperaActive = 'false'; }
				}

				//////////// natis, receta_diagnosticos, prestacion_procedimientos
				$natis = array();
				$receta_diagnosticos = array();
				$prestacion_procedimientos = array();				
				$mz_natis_procedimientos = array();
				if($id_puerperio>0){ 
					//////////// natis
					$db_natis = DB::table('natis')
						->select(
							'natis.*',
							'id as natis_id'
						)
						->where('id_puerperio',$id_puerperio)
						->get();
					$natis = $db_natis;					
					if(count($db_natis) > 0){ foreach($db_natis as $natis_valor){ 
						$natis_id = $natis_valor->natis_id;
							
							
						//////////// receta_diagnosticos
						$db_receta_diagnosticos = DB::table('receta_diagnosticos')
							->select(
								'receta_diagnosticos.*',
								'receta_diagnosticos.id_diagnostico as natis_diagnostico_id',
								'diagnosticos.nombre as natis_diagnostico_nombre'
							)
							->join('diagnosticos','diagnosticos.id','=','receta_diagnosticos.id_diagnostico')
							->where('receta_diagnosticos.rd_natis_id', '=', $natis_id)
							->where('receta_diagnosticos.rd_natis_id', '>', 0)
							->get();
						
						if(count($db_receta_diagnosticos) > 0){ foreach($db_receta_diagnosticos as $rda_index => $rda_valor){ 
							$receta_diagnosticos["$natis_id"]["$rda_index"] = $rda_valor;
						}}


						//////////// prestacion_procedimientos
						$db_prestacion_procedimientos = DB::table('prestacion_procedimientos')
							->select(
								'prestacion_procedimientos.*',
								'prestacion_procedimientos.id_procedimiento as natis_procedimiento_id',
								'procedimientos.descripcion as natis_procedimiento_descripcion',
								'diagnosticos.id as natis_procedimiento_diagnostico_id',
								'diagnosticos.nombre as natis_procedimiento_diagnostico_nombre'
							)
							->join('procedimientos','procedimientos.id','=','prestacion_procedimientos.id_procedimiento')
							->leftJoin('diagnosticos','diagnosticos.id','=','prestacion_procedimientos.id_diagnostico')
							->where('prestacion_procedimientos.pp_natis_id', '=', $natis_id)
							->where('prestacion_procedimientos.pp_natis_id', '>', 0)
							->get();

						if(count($db_prestacion_procedimientos) > 0){ foreach($db_prestacion_procedimientos as $ppa_index => $ppa_valor){ 
							$prestacion_procedimientos["$natis_id"]["$ppa_index"] = $ppa_valor;
						}}

					}}



					//////////// procedimientos por defecto para neonatos
					$db_natis_procedimientos = DB::table('procedimientos')
						->select(
							'procedimientos.*'
						)
						->where('procedimientos.flag_natis', '=', '1')
						->get();
					if(count($db_natis_procedimientos) > 0){ foreach($db_natis_procedimientos as $mz_index => $mz_valor){ 
						$mz_natis_procedimientos["$mz_index"] = $mz_valor;
					}}



				}	/// fin if id_puerperio
               
                $upss_encargado = DB::table('upsses as u')
                                ->join('consultorios as c', 'c.id', '=', 'u.id_consultorio')
                                ->select('u.*', 'c.nombre as nombre_consultorio')
                                ->where('u.id_consultorio', $hospitalizacione->id_servicio_encargado)
                                ->where('u.id', $hospitalizacione->id_upss_encargado)
                                ->where('u.id_tipo_atencion', 3)
                                ->where('u.estado', 1)
                                ->first();

                $medicoservicio = $model_consultorio->MedicoDetalleServicio($id_ipress,$medico->id);

                $upss_cargo = $upss_encargado->nombre_consultorio;
                $servicio_medico = $medicoservicio->servicio;

                $servicios = explode('#|#', $servicio_medico);
                $servicios = array_map('trim', $servicios);
                
                if (!in_array($upss_cargo, $servicios) && !session()->pull('mostrar_error')) {
                    session()->put('mostrar_error', true); 
                    return redirect()->to('/form_evolucion_hospitalizacion/' . $id_hospitalizacion)
                        ->with('mensaje_error', 'EL UPSS A CARGO DEL PACIENTE NO COINCIDE CON SU SERVICIO. BAJO SU RESPONSABILIDAD');
                } 
                //hospitalizados.evolucion.formulario_registro
                $is_profesion_medico = false;
                if (count($medico) > 0) {
                    switch ($medico->id_profesion) {
                        case 20: case 4: case 51: case 52: case 1: $is_profesion_medico = true; break;
                    }
                    if (!$is_profesion_medico && (intval($nivel) == 1 || intval($nivel) == 2) && in_array(intval($medico->id_profesion), [12, 2, 5])) {
                      $is_profesion_medico = true;
                    }
                }

                /* $eventosPrevios = Prestacion_eventos::where("id_prestacion", $id_prestacion)->first(); */
                $obj_hospitalizacion_atencion = \App\Models\HospitalizacionAtencione::select('ha.*', 
                    'p.fecha_parto', 
                    'p.id_tipo_parto as puerperio_id_tipo_parto', 
                    'tp.nombre as puerperio_tipo_parto', 
                    'p.complicaciones as puerperio_complicaciones', 
                    'p.descripcion as puerperio_descripcion')
                ->from('hospitalizacion_atenciones as ha')
                ->leftJoin('puerperios as p', 'ha.id_puerperio', '=', 'p.id')
                ->leftJoin('tipo_partos as tp', 'p.id_tipo_parto', '=', 'tp.id')
                ->where('ha.id_prestacion', '=', $id_prestacion) // Cambié aquí a 'ha.id' para que coincida con el contexto
                ->first();
                $eventosMarcados = Prestacion_eventos::where('id_prestacion', $id_prestacion)
                ->where('estado', 1)
                ->lists('id_evento')
                ->toArray();

                $model_tipo_cirugia = new \App\Models\CqxTipoAnestesia();
                $tipo_cirugias = $model_tipo_cirugia->GetAllTipoCirugiasActivos();

                $model_cqx_tipo_profesional = new \App\Models\CqxTipoProfesional();
                $cqx_tipo_profesionales = $model_cqx_tipo_profesional->GetAllActivos();

                $client = new SoapClient(env('WI_SALUDPOL_URL') . "/ws/WS_AfiliadoSP_Serv.php?wsdl", array('login' => env('WI_SALUDPOL_USERNAME'), 'password' => env('WI_SALUDPOL_PASSWORD')));

                if($datos_evaluacion->tipo_doc_ident=='DNI'){ 
                    $tipo_doc_ident = '1';
                }else if($datos_evaluacion->tipo_doc_ident=='CE'){ 
                    $tipo_doc_ident = '2';
                }else if($datos_evaluacion->tipo_doc_ident=='PAS'){ 
                    $tipo_doc_ident = '3';
                }else if($datos_evaluacion->tipo_doc_ident=='DIE'){ 
                    $tipo_doc_ident = '4';
                }

                $beneficiario = $client->getAseguradoValidate($tipo_doc_ident, $datos_evaluacion->nro_doc_ident);

                if (is_object($beneficiario)) :
                  $flag = 0 ;
                  $errorWS = 1 ;
                else:
                  $flag = 1 ;
                  $errorWS = 0 ;
                endif;

                // $diagnostico_evaluacion = $model_evolucion->GetDiagnosticoByIdEvaluacion($id_evolucion);
                $diagnostico_evaluacion = DB::table('receta_diagnosticos as rd')
                    ->join('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
                    ->where('rd.id_evaluacion', $id_evolucion)
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

                $model_procedimiento = new \App\Models\Procedimiento();
                $procedimiento_evaluacion = $model_procedimiento->GetProcedimientosHospitalizacionEvolucion($id_evolucion);

                $model_interconsulta = new \App\Models\Interconsulta();
                $interconsultas_reg = $model_interconsulta->GetInterconsultaByIdEvaluacion2($id_prestacion, $id_evolucion);
                $has_interconsulta_registrada = $interconsultas_reg && count($interconsultas_reg) > 0;

                $model_receta = new \App\Models\Receta_vale();
                $recetas = $model_receta->GetDatosRecetasByIdEvaluacion($id_evolucion);
                $has_receta_registrada = $recetas && count($recetas) > 0;
                $farmacia_id_receta_primera = $recetas && count($recetas) > 0 ? $recetas[0]->id_farmacia : 0;
                
                $model_laboratorio = new \App\Models\Laboratorio();
                $laboratorio_ordenes = $model_laboratorio->GetLaboratorioByIdPrestacionEvaluacion($id_prestacion, $id_evolucion);
                $has_laboratorio_orden_registrada = $laboratorio_ordenes && count($laboratorio_ordenes) > 0;

                $model_radiologia = new \App\Models\Radiologia;
                $radiologia_ordenes = $model_radiologia->GetRadiologiaByIdPrestacionEvaluacion($id_prestacion, $id_evolucion);
                $has_radiologia_orden_registrada = $radiologia_ordenes && count($radiologia_ordenes) > 0;
                
                $evolucion_data = Evaluacione::find($id_evolucion);
                $cambio_cama_model = new CambioCama();
                $cambio_cama_data = $cambio_cama_model->getDataByIdPrestacionAndIdEvolucion($evolucion_data->id_prestacion, $id_evolucion);
                
                if ($errorWS > 0) {             
                    return view('frontend.hospitalizacion.evolucion.edit_evolucion_2', compact('arraydeprocedimientos','id_prestacion','fechaServidor','nivel','eventosMarcados','obj_hospitalizacion_atencion','farmacias','triaje','producto','estado','datos_evaluacion','id_tipo_atencion','id_farmacia','cuartos','camas','flag','id_tipo_atencione','codigo_upss','id_hospitalizacion','dato_cama','hora','dato_titular','turno','ultima_atencion','flag_no_encontrado','id_servicio','codigo_siteds','codigo_siteds_valido','ordenes_laboratorio','ordenes_imagenologia','interconsultas','tipo_doc_nati','tipo_partos','lastGestacion','upps','listUpss','signos_vitales','signo_vital','upss_encargado','servicio_ubicacion','hospitalizacione','upsses','id_servicio_actual','regularizar','puerperio','natis','receta_diagnosticos','prestacion_procedimientos','mz_natis_procedimientos','is_profesion_medico','existe_servicio_atencion','upss_cargo','servicio_medico','establecimiento_servicios','tipo_cirugias','cqx_tipo_profesionales','tiene_flag_quirurgico', 'evolucion', 'diagnostico_evaluacion', 'procedimiento_evaluacion', 'interconsultas_reg', 'has_interconsulta_registrada', 'recetas', 'has_receta_registrada', 'farmacia_id_receta_primera', 'laboratorio_ordenes', 'has_laboratorio_orden_registrada', 'radiologia_ordenes', 'has_radiologia_orden_registrada', 'cambio_cama_data'));
                }
                else{
                    return redirect('/all_pacientes_evoluciones_by_hospitalizacion')->withInput()->withErrors(array('El paciente ya no esta asegurado, por consiguiente no tiene derecho de atención, realizar los tramites correspondiente con SALUDPOL..!'));
                }
            else:
                return redirect('/form_atenciones_hospitalizacion/'.$id_hospitalizacion)->withInput()->withErrors(array('Primero debe ingresar una atencion, para poder registrar su evolución del paciente.'));
            endif;
        else:
            $mensaje_error = "";
            if (is_null($hospitalizacione)):
                $mensaje_error = "No existe el registro de Hospitalización";
            elseif (intval($hospitalizacione->estado) === 5):
                $mensaje_error = "No puede registrar la Evolución porque aún no cuenta con su Primera Atención.";
            elseif (intval($hospitalizacione->estado) === 6 || intval($hospitalizacione->estado) === 7):
                $mensaje_error = "No puede registrar la Evolución porque el paciente ya fue dado de Alta.";
            endif;

            return redirect('/all_pacientes_evoluciones_by_hospitalizacion/')->withInput()->withErrors(array($mensaje_error));
        endif;
    }

    public function hospitalizacion_evolucion_update(Request $request)
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
            $id_evolucion = $request->id_evolucion;

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
            $evolucion = Evaluacione::find($id_evolucion);
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
            $diuresis = $request->diuresis != '' ? $request->diuresis : 0;
            $balance_hidrico = $request->balance_hidrico = $request->balance_hidrico;

            $evolucion->temperatura = $temperatura;
            $evolucion->sa02_porcentaje = $sa02_porcentaje;
            $evolucion->presion_arterial_x = $presion_arterial_x;
            $evolucion->presion_arterial_y = $presion_arterial_y;
            $evolucion->talla_cm = $talla_cm;
            $evolucion->tamano_cintura_cm = $tamano_cintura_cm;
            $evolucion->frecuencia_respiratoria = $frecuencia_respiratoria;
            $evolucion->peso_kg = $peso_kg;
            $evolucion->frecuencia_cardiaca_fetal = $frecuencia_cardiaca_fetal;
            $evolucion->frecuencia_cardiaca = $frecuencia_cardiaca;
            $evolucion->fi02 = $fi02;
            $evolucion->diuresis = $diuresis;
            $evolucion->balance_hidrico = $balance_hidrico;
            // SOAP
            $evolucion->apreciacion_subjetiva = $request->apreciacion_subjetiva;
            $evolucion->apreciacion_objetiva = $request->apreciacion_objetiva;
            $evolucion->verificacion_tratamiento = $request->verificacion_tratamiento;
            $evolucion->interpretacion_examenes = $request->interpretacion_examenes;
            $evolucion->plan_trabajo = $request->plan_trabajo;
            // Indicaciones Terapeuticas
            $evolucion->tratamiento_auxiliar = $request->indicacion_terapeutica;
            $evolucion->updated_us = Auth::user()->id;

            if (!$evolucion->save()):
                throw new \Exception("Error al intentar actualizar los datos de la evolución.");
            endif;

            $list_diagnostico_ya_registrado = DB::table('receta_diagnosticos as rd')
                ->join('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
                ->where('rd.id_evaluacion', $id_evolucion)
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
                    $id_prioridad = intval($request->id_categoria_diagnostico[$indice]);

                    if ($id_receta_diagnostico > 0) {
                        // Modificacion de diagnosticos ya registrados
                        $upd_receta_diagnostico = \App\Models\Receta_diagnostico::find($id_receta_diagnostico);
                        if (intval($upd_receta_diagnostico->id_tipo_diagnostico) !== $id_tipo_diagnostico
                            || intval($upd_receta_diagnostico->id_categoria) !== $id_prioridad):

                            $upd_receta_diagnostico->id_tipo_diagnostico = $id_tipo_diagnostico;
                            $upd_receta_diagnostico->id_categoria = $id_prioridad;
                            $upd_receta_diagnostico->updated_us = Auth::user()->id;
                            $upd_receta_diagnostico->updated_at = Carbon::now();
                            if (!$upd_receta_diagnostico->save()):
                                throw new \Exception("Error al intentar actualizar los datos de un diagnóstico (" . $id_cie . "). ");
                            endif;
                        endif;

                        // Quitar diagnosticos que permanecen
                        foreach ($list_diagnostico_ya_registrado as $indice2 => $obj_dx) {
                            if (intval($obj_dx->id) === $id_receta_diagnostico) {
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
                        $receta_diagnostico->created_us = Auth::user()->id;
                        $receta_diagnostico->id_evaluacion = $request->id_evolucion;
                        $receta_diagnostico->id_clasificacion_diagnosticos = 8; // Evolucion
                        $receta_diagnostico->id_categoria = $id_prioridad;
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

            $model_procedimiento = new \App\Models\Procedimiento();
            // $list_procedimiento_ya_registrado = $model_prestacion->GetProcedimientosByIdPrestacionydiagEvaluacion($id_prestacion, 1, $id_evaluacion);
            $list_procedimiento_ya_registrado = $model_procedimiento->GetProcedimientosHospitalizacionEvolucion($id_evolucion);
            $list_procedimiento_ya_registradoAux = $list_procedimiento_ya_registrado;
            
            if (!empty($request->id_procedimiento)) {
                foreach ($request->id_procedimiento as $indice => $id_cpms) {
    
                    $id_prestacion_procedimiento = intval($request->id_prestacion_procedimiento[$indice]);
                    $cantidad_procedimiento = intval($request->procedimiento_cantidad[$indice]);
                    $id_diagnostico_del_procedimiento = intval($request->miSelect[$indice]);
    
                    if ($id_prestacion_procedimiento > 0) {
                        // Modificacion de procedimientos ya registrados
                        $upd_prestacion_procedimiento = \App\Models\Prestacion_procedimiento::find($id_prestacion_procedimiento);
                        $upd_prestacion_procedimiento->cantidad = $cantidad_procedimiento;
                        $upd_prestacion_procedimiento->id_diagnostico = $id_diagnostico_del_procedimiento;
                        $upd_prestacion_procedimiento->updatet_us = Auth::user()->id; // updatet_us
                        $upd_prestacion_procedimiento->updated_at = Carbon::now(); 
                        if (!$upd_prestacion_procedimiento->save()):
                            throw new \Exception("Error al intentar actualizar los datos de un procedimiento (" . $id_cpms . "). ");
                        endif;
                        
                        // Quitar procedimientos que permanecen
                        foreach ($list_procedimiento_ya_registrado as $indice2 => $obj_proc) {
                            if (intval($obj_proc->id_prestacion_procedimiento) === $id_prestacion_procedimiento) {
                                unset($list_procedimiento_ya_registrado[$indice2]);
                                break;
                            }
                        }
    
                    } else {
                        // Nuevos procedimientos
                        $prestacion_procedimiento = new \App\Models\Prestacion_procedimiento();
                        $prestacion_procedimiento->id_prestacion = $request->id_prestacion;
                        $prestacion_procedimiento->id_procedimiento = $id_cpms;
                        $prestacion_procedimiento->id_estado_reg = 1;
                        $prestacion_procedimiento->created_at = Carbon::now();
                        $prestacion_procedimiento->created_us = $request->id_user;
                        $prestacion_procedimiento->cantidad = $cantidad_procedimiento;
                        $prestacion_procedimiento->id_evaluacion = $request->id_evolucion;
                        $prestacion_procedimiento->id_diagnostico = $id_diagnostico_del_procedimiento;
                        if (!$prestacion_procedimiento->save()):
                            throw new \Exception("Error al intentar registrar nuevo procedimiento (" . $id_cpms . "). ");
                        endif;
                    }
                }
    
                // Verificar procedimientos no considerados
                if (!empty($list_procedimiento_ya_registrado)) {
                    foreach ($list_procedimiento_ya_registrado as $obj_proc) {
                        // Anular procedimientos
                        $upd_prestacion_procedimiento = \App\Models\Prestacion_procedimiento::find($obj_proc->id_prestacion_procedimiento);
                        $upd_prestacion_procedimiento->id_estado_reg = 0;
                        $upd_prestacion_procedimiento->deleted_us = Auth::user()->id;
                        $upd_prestacion_procedimiento->deleted_at = Carbon::now();
                        if (!$upd_prestacion_procedimiento->save()):
                            throw new \Exception("Error al intentar anular un procedimiento (" . $upd_prestacion_procedimiento->id_procedimiento . "). ");
                        endif;
                    }
                }
            }
            // 

            if (!empty($request->id_procedimiento)) {
                foreach ($request->id_procedimiento as $indice => $id_cpms) {

                    $procedimiento_existe = false;
                    $id_diagnostico_del_procedimiento = intval($request->miSelect[$indice]);
                    
                    // foreach ($request->id_procedimiento_backup as $indice_backup => $id_cpms_backup):
                    foreach ($list_procedimiento_ya_registradoAux as $indice_backup => $obj_proc):

                        $id_procedimiento_backup = $obj_proc->id_procedimiento;
                        $id_diagnostico_backup = $obj_proc->id_diagnostico;

                        if (intval($id_procedimiento_backup) == intval($id_cpms)):

                            $procedimiento_existe = true;

                            $procedimiento_diagnostico = \App\Models\ProcedimientoDiagnotico::where("id_prestacion", $id_prestacion)
                                ->where("id_evaluacion", $id_evolucion)
                                ->where("id_procedimientos", $id_procedimiento_backup)
                                ->where("id_diagnosticos", $id_diagnostico_backup)
                                ->where("estado", 1)
                                ->where("id_interconsulta", 0)
                                ->where("id_consejeria", 0)
                                ->whereRaw('COALESCE(id_evolucion, 0) = 0')
                                ->whereRaw('COALESCE(id_intraoperatorio, 0) = 0')
                                ->whereRaw('COALESCE(id_hoja_recuperacion, 0) = 0')
                                ->get();

                            $cantidad = $procedimiento_diagnostico ? count($procedimiento_diagnostico) : 0;

                            if ($cantidad == 1):

                                $filas_afectadas = \App\Models\ProcedimientoDiagnotico::where("id_prestacion", $id_prestacion)
                                    ->where("id_evaluacion", $id_evolucion)
                                    ->where("id_procedimientos", $id_procedimiento_backup)
                                    ->where("id_diagnosticos", $id_diagnostico_backup)
                                    ->where("estado", 1)
                                    ->where("id_interconsulta", 0)
                                    ->where("id_consejeria", 0)
                                    ->whereRaw('COALESCE(id_evolucion, 0) = 0')
                                    ->whereRaw('COALESCE(id_intraoperatorio, 0) = 0')
                                    ->whereRaw('COALESCE(id_hoja_recuperacion, 0) = 0')
                                    ->update([
                                        "id_diagnosticos" => $id_diagnostico_del_procedimiento,
                                        "updated_us" => Auth::user()->id,
                                        "updated_at" => Carbon::now()
                                    ]);
                                
                                if ($filas_afectadas !== 1):
                                    throw new \Exception("Error al intentar actualizar un procedimiento (" . $id_cpms . "). ");
                                else:
                                    unset($list_procedimiento_ya_registradoAux[$indice_backup]);
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
                        $prestacion_procedimiento->id_evaluacion = $id_evolucion;
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
                if (!empty($list_procedimiento_ya_registradoAux)) {
                    foreach ($list_procedimiento_ya_registradoAux as $obj_proc) {
                        // Anular procedimientos
                        $procedimiento_diagnostico = \App\Models\ProcedimientoDiagnotico::where("id_prestacion", $id_prestacion)
                            ->where("id_evaluacion", $id_evolucion)
                            ->where("id_procedimientos", $obj_proc->id_procedimiento)
                            ->where("id_diagnosticos", $obj_proc->id_diagnostico)
                            ->where("estado", 1)
                            ->where("id_interconsulta", 0)
                            ->where("id_consejeria", 0)
                            ->whereRaw('COALESCE(id_evolucion, 0) = 0')
                            ->whereRaw('COALESCE(id_intraoperatorio, 0) = 0')
                            ->whereRaw('COALESCE(id_hoja_recuperacion, 0) = 0')
                            ->get();

                        $cantidad = $procedimiento_diagnostico ? count($procedimiento_diagnostico) : 0;

                        if ($cantidad == 1):
                            $filas_afectadas = \App\Models\ProcedimientoDiagnotico::where("id_prestacion", $id_prestacion)
                                ->where("id_evaluacion", $id_evolucion)
                                ->where("id_procedimientos", $obj_proc->id_procedimiento)
                                ->where("id_diagnosticos", $obj_proc->id_diagnostico)
                                ->where("estado", 1)
                                ->where("id_interconsulta", 0)
                                ->where("id_consejeria", 0)
                                ->whereRaw('COALESCE(id_evolucion, 0) = 0')
                                ->whereRaw('COALESCE(id_intraoperatorio, 0) = 0')
                                ->whereRaw('COALESCE(id_hoja_recuperacion, 0) = 0')
                                ->update([
                                    "estado" => 0,
                                    "deleted_us" => Auth::user()->id,
                                    "deleted_at" => Carbon::now()
                                ]);

                            if ($filas_afectadas !== 1):
                                throw new \Exception("Error al intentar anular un procedimiento (" . $obj_proc->id_procedimiento . ").");
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
                        $interconsulta_id = $model_interconsulta->RegistrarInterconsultaEvaluacion($request->id_prestacion, $request->id_asegurado, $request->id_tipo_atencion, $request->fecha_hora_atencion, $request->id_medico, $request->id_establecimiento, $p_interconsulta, $id_evolucion);
                        
                    if (intval($interconsulta_id) == 0):
                        throw new \Exception("Error al intentar registrar las nuevas interconsultas. ");
                    endif;
                endif;
            endif;
            // Fin Actualizacion de Interconsultas

            $id_receta_vale = 0;
            $id_consultorio = $request->id_servicio_encargado;
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
                    
                    if (strlen($p_producto) > 1):
                        $p_producto = substr($p_producto, 0, -1);
                    endif;

                endif;
            endif;
            $p_producto .= "}";

            $asegurado = Asegurado::find($request->id_asegurado);
            $titular = Asegurado::find($asegurado->id_asegurado_titular);

            $p_diagnostico = "{}";
            /*if ($request->id_diagnostico[0] != ""):
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

                    if (strlen($p_diagnostico) > 1):
                        $p_diagnostico = substr($p_diagnostico, 0, -1);
                    endif;
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
                
                if (strlen($p_diagnostico) > 1):
                    $p_diagnostico = substr($p_diagnostico, 0, -1);
                endif;

                $p_diagnostico .= "}";
            endif;*/

            if (isset($request->id_producto)):
                if ($request->id_producto[0] != "ru='undefined'"):
                    $receta = new \App\Models\Receta_vale();
                    $id_receta_vale = $receta->postRegistrarRecetaValeNuevoConDiagnosticoEvaluacion($id_receta_vale, $request->id_farmacia_receta, $request->id_user, $id_consultorio, $nro_receta, $digitos_adicionales, $request->nrodocafiliado, $titular->cip, $request->tipodoc_beneficiario, $request->nombre_beneficiario, $request->paterno_beneficiario, $request->materno_beneficiario, $request->tipo_beneficiario, $titular->nro_doc_ident, $titular->tipo_doc_ident, $titular->nombre, $titular->paterno, $titular->materno, $request->fecha_expedicion, $request->fecha_atencion, $request->nro_historia, $request->grado, $request->id_medico, $id_anestesiologo, $id_tiporeceta, $turno, $obs_registro, $id_receta_vale_bus, $id_admision, $tipo_via, $sub_via, $final_via, $nro_guia, $interconsulta, $interconsulta_dias, $id_prestacion, $id_tipo_num, $estado_receta, $p_producto, $p_diagnostico, $request->nomsexo, $request->fecnacafiliado, $request->codigo_upss, $id_evolucion);

                    if ($id_receta_vale > 0):
                        $model_rec_val = new \App\Models\Receta_vale;
                        $ok = $model_rec_val->ActualizaIdEvaluacion($id_receta_vale, $id_evolucion);
                        
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

                if (strlen($p_laboratorio) > 1):
                    $p_laboratorio = substr($p_laboratorio, 0, -1);
                endif;

                $p_laboratorio .= "}";

                if ($request->id_examen_lab[0] != ""):

                    $has_observacion_unica = (intval($request->id_nivel) === 3) ? 1 : 0;
                    $lab_orden_observacion_unica = intval($request->id_nivel) === 3 ? $request->lab_orden_observacion : null;
                    $id_interconsulta = 0;
                    $id_area = 0;
                    $flag_cq = (isset($request->id_perfil_preoperatorio) && intval($request->id_perfil_preoperatorio) > 0) ? 1 : 0;
                    $id_hora_toma_muestra = (intval($request->id_nivel) === 3 && isset($request->id_hora_toma_muestra)) ? $request->id_hora_toma_muestra : null;

                    $model_procedimiento = new \App\Models\Procedimiento();
                    if (intval($request->id_establecimiento) === 76):
                    $id_procedimiento_lab = $model_procedimiento->RegistrarLaboratorioOrden3($request->id_user, $request->id_prestacion, $request->id_establecimiento, 
                    $request->fecha_hora_atencion, $p_laboratorio, $has_observacion_unica, $id_evolucion,$request->id_farmacia, $id_interconsulta, $request->id_tipo_atencion, $request->id_servicio_encargado, $id_area, 
                    $request->id_cama_actual, $request->id_medico, $request->id_asegurado, $lab_orden_observacion_unica, $flag_cq, $id_hora_toma_muestra);
                    else:
                    $id_procedimiento_lab = $model_procedimiento->RegistrarLaboratorioOrden2($request->id_user, $request->id_prestacion, $request->id_establecimiento, 
                    $request->fecha_hora_atencion, $p_laboratorio, 2, $id_evolucion,$request->id_farmacia, 0, $request->id_tipo_atencion, $request->id_servicio_encargado, $request->id_servicio, 
                    $request->id_cama_actual, $request->id_medico, $request->id_asegurado);
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

                    if (strlen($p_radiologia) > 1):
                        $p_radiologia = substr($p_radiologia, 0, -1);
                    endif;

                    $p_radiologia .= "}";

                    if ($request->id_examen_rad[0] != ""):
                        $model_procedimiento = new \App\Models\Procedimiento();
                        $id_procedimiento_rad = $model_procedimiento->RegistrarProcedimientoImagenes($request->id_user, $request->id_prestacion, $request->id_establecimiento, $request->fecha_hora_atencion, $p_radiologia, 3, $id_evolucion, $request->id_farmacia);

                        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// nuevo req imagenes
                        if (intval($request->id_establecimiento) == 76) {
                            $updated = DB::table('rua_imagenologia')->where('id', $request->id_rua_imagenes)->update(['id_rad' => $id_procedimiento_rad,'id_evol'=> $id_evolucion, 'id_estado_reg' => 1]);
                        }
                        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        
                        switch (intval($id_procedimiento_rad)):
                            case 0: throw new \Exception("Error al intentar registrar la nueva Orden de imágenes."); break;
                            case -1: throw new \Exception("Error al intentar registrar un examen en la Orden en imágenes."); break;
                            default:
                                $model_rad = new \App\Models\Radiologia;
                                $result = $model_rad->ActualizaIdEvaluacion($id_procedimiento_rad, $id_evolucion);
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
            $mensaje_error = $e->getMessage();
            Log::error("Edicion Evaluacion Emergencia => id_hospitalizacion: " . $request->id_hospitalizacion . " id_evaluacion: " . $request->id_evolucion . 
                "\n\nmensaje: " . $e->getMessage() . 
                "\n\ndatos del request: " . json_encode($request->all()) . 
                "\n\nstack trace: " . $e->getTraceAsString()
            );
        } catch (\Throwable $th) {
            DB::rollback();
            $mensaje_error = $th->getMessage();
            Log::critical("Edicion Evaluacion Emergencia => id_hospitalizacion: " . $request->id_hospitalizacion . " id_evaluacion: " . $request->id_evolucion . 
                "\n\nmensaje: " . $th->getMessage() . 
                "\n\ndatos del request: " . json_encode($request->all()) . 
                "\n\nstack trace: " . $th->getTraceAsString()
            );
        }

        if ($registro_validado):
            Session::flash('flash_message', 'Evolución Médica exitosa');
            return redirect('/ver_evaluacion_hospitalizacion/' . $request->id_user . '/' . $request->id_evolucion);
        else:
            // Session::flash('flash_warning', '<strong>Error:</strong> No se pudo registrar la Evolución Médica, vuelva a intentarlo nuevamente.<br><br>' . $e->getMessage());
            return redirect('/hospitalizacion/evolucion/edit/'. $request->id_evolucion)->withInput()->withErrors(array('<strong>Error:</strong> No se pudo registrar la Evolución Médica, vuelva a intentarlo nuevamente.<br><br>' . $mensaje_error));
            // return redirect('/hospitalizacion/evolucion/edit/'. $request->id_evolucion);
        endif;
    }

    public function ajax_validar_evolucionqx(Request $request)
    {
        if (empty($request->id_hospitalizacion)) {
            return response()->json(['existe' => false]);
        }

        $existe = \DB::table('cqx_intervenciones')
            ->where('id_hospitalizacion', $request->id_hospitalizacion)
            ->exists();

        return response()->json(['existe' => $existe]);
    }
        

}
