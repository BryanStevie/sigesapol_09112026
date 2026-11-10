<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use DB;
use App;
use Exception;
use Illuminate\Support\Facades\Route;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Medico;
use App\Models\Docsinfirmar;
use App\Models\Interconsulta;
use App\Models\Emergencia;
use App\Models\SubConsultorio;
use App\Models\Hospitalizacione;
use App\Models\Cita;
use App\Models\Asegurado_historia;
use App\Models\HospitalizacionOrden;
use App\Models\Receta_vale;
use App\Models\Laboratorio;
use App\Models\Farmacia;
use App\Models\Prestaciones;
use App\Models\Prestacion_procedimiento;
use App\Models\Evaluacione;
use Archive7z\Archive7z;
use App\Models\Prestacion_eventos;
use App\Models\Establecimiento;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use Illuminate\Validation;
use SoapClient;
use SoapHeader;
use QrCode;
use App\Models\Gastroenterologia;
use DateTime;
use Response;
/**
 * Class DocSinFirmarController
 */
class DocSinFirmarController extends Controller {
    
    function __construct()
    {
        $this->middleware('auth'); 
    } 

//============================================== VISTA DEL MÓDULO DOCUMENTOS SIN FIRMAR ==============================================
    public function all_docs_sin_firmar(){
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
        $id_user = Auth::user()->dni;
        $id_establecimiento = Auth::user()->id_establecimiento;
        $medicos = $model_medico->GetDatosMedicoByDniAndEstablecimiento($id_user,$id_establecimiento);
        $id_servicio = Auth::user()->id_consultorio;
        //$id_medico =  $medicos->id;
        if ($medicos) {
            $id_medico = $medicos->id;
        } else {
            $id_medico = null; 
        }
        $id_servicio = 0;
        $date = Carbon::now();
        $fechaHasta = $date->format('d/m/Y');
        $fechaDesdex = $date->format('d-m-Y');
        $fechaDesdey = strtotime('-1 months', strtotime($fechaDesdex));
        $fechaDesde = date('d/m/Y', $fechaDesdey);
        //=============================
        $fechaHastafirm  = $date->format('d/m/Y'); 
        $fechaDesdex     = $date->format('d-m-Y'); 
        $fechaDesdey     = date('d/m/Y');         
        $fechaDesdefirm  = $fechaDesdey;           
        //====================
        $model_farma = new Farmacia();
        $farmacia = $model_farma->GetFarmaByUser(Auth::user()->id);

        $model_consultorio = new \App\Models\Consultorio;
        $servicios = $model_consultorio->ShowAllConsultoriosSubConsultorio($farmacia->id_establecimiento);
        $listar_divisiones = $model_consultorio->getDivisiones_docsinfirmar();
        $listar_dep = [];
        $dni_beneficiario = '';
        $nombre_beneficiario = '';
        $estado = 1;
        $model_user = new \App\Models\User;
        $medicos= $model_user->GetUserByEstablecimiento(Auth::user()->id_establecimiento);
        $listar_div_enf = collect($model_consultorio->getDivisiones())->filter(function ($item) {
            return in_array($item->id, [8, 1]);
        })->values();
        $medico_emergencia = DB::table('users')->select('users.id','users.name','users.paterno','users.materno')->join('medicos','medicos.dni','=','users.dni')->whereIn('medicos.id_unidad',[3,4,6])->where('medicos.estado',1)->where('medicos.id_establecimiento',$id_establecimiento)->get();
        return view('frontend.docsinfirmar.all_docsinfirmar', compact('farmacia', 'fechaDesde', 'fechaHasta', 'fechaDesdefirm', 'fechaHastafirm','dni_beneficiario', 'nombre_beneficiario', 'estado','id_medico','id_servicio','servicios','id_tipo_atencion','listar_divisiones','listar_dep','medicos','medico_emergencia','listar_div_enf'));
    }

//============================================== CANTIDAD DE DOCUMENTOS SIN FIRMAR(ALERTA) ==============================================
	public function obtener_cantidad_docs_sinfirmar_by_user() {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $respuesta = null;
        $model_medico = new Medico();
        $id_establecimiento = Auth::user()->id_establecimiento;
        $medico = $model_medico->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni, $id_establecimiento);
        $id_user= Auth::user()->id;
        if (count($medico) > 0):
            $docsinfirmar = new Docsinfirmar();
            $id_servicio = Auth::user()->id_consultorio;
            $id_medico= $medico->id;
            $has_todo_servicios = Auth::user()->flagservicios;
            if (!empty($id_servicio) && intval($id_servicio) > 0):
                $respuesta = $docsinfirmar->GetDocsPendientesFirmaByIdServicio($id_establecimiento, $id_servicio,$id_user);

            elseif (!empty($has_todo_servicios) && intval($has_todo_servicios) == 1):
                $respuesta = $docsinfirmar->GetDocsPendientesFirmaByIdServicio($id_establecimiento, 0,$id_user);
            endif;
        endif;
        return $respuesta;
    }

//============================================== LISTADO DE DOCUMENTOS SIN FIRMAR ==============================================
    public function listar_docsinfirmar_ajax(Request $request){
        // Obtener los parámetros de la petición
        $id_establecimiento = Auth::user()->id_establecimiento;
        $id_user = Auth::user()->id;
        $fecha_ini = isset($request->fecha_ini) && $request->fecha_ini != '' ? $request->fecha_ini : '2000-01-01';
        $fecha_fin = isset($request->fecha_fin) && $request->fecha_fin != '' ? $request->fecha_fin : date('Y-m-d');
        $dni_beneficiario = isset($request->dni_beneficiario) && $request->dni_beneficiario != '' ? $request->dni_beneficiario : null;
        $estado = isset($request->estado) ? $request->estado : 1;
        $pagina = isset($request->NumeroPagina) ? $request->NumeroPagina : 1;
        $limit = isset($request->NumeroRegistros) ? $request->NumeroRegistros : 20;
        $p = [
            $id_establecimiento,
            $fecha_ini,
            $fecha_fin,
            $dni_beneficiario,
            $estado,
            $id_user,
            $pagina,
            $limit
        ];

        $model_docsinfirmar = new Docsinfirmar();
        $data = $model_docsinfirmar->listar_docsinfirmar_ajax($p);

        $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : count($data);

        $result = [
            "PageStart" => $pagina,
            "pageSize" => $limit,
            "SearchText" => "",
            "ShowChildren" => true,
            "iTotalRecords" => $iTotalDisplayRecords,
            "iTotalDisplayRecords" => $iTotalDisplayRecords,
            "aaData" => $data
        ];
        return response()->json($result);
    }
    public static function establecimientoHabilitadoFirmasEnfermeria($id_establecimiento){
        $establecimientosHabilitados = [76];
        return in_array($id_establecimiento, $establecimientosHabilitados);
    }
    public function listar_notas_enfermeria_ajax(Request $request){
        $id_establecimiento = Auth::user()->id_establecimiento;
        $id_user = Auth::user()->id;

        /* if (!self::establecimientoHabilitadoFirmasEnfermeria($id_establecimiento)) {
            return response()->json(['status' => 'error', 'message' => 'Módulo no habilitado para este establecimiento'], 403);
        } */
        $fecha_ini = isset($request->fecha_ini) && $request->fecha_ini != '' ? $request->fecha_ini : '2000-01-01';
        $fecha_fin = isset($request->fecha_fin) && $request->fecha_fin != '' ? $request->fecha_fin : date('Y-m-d');
        $dni_beneficiario = isset($request->dni_beneficiario) && $request->dni_beneficiario != '' ? $request->dni_beneficiario : null;
        $pagina = isset($request->NumeroPagina) ? $request->NumeroPagina : 1;
        $limit = isset($request->NumeroRegistros) ? $request->NumeroRegistros : 20;
        $estado = isset($request->estado) && $request->estado == 'F' ? 'F' : 'P';
        $p = [
            $id_establecimiento,
            $fecha_ini,
            $fecha_fin,
            $dni_beneficiario,
            $id_user,
            $pagina,
            $limit,
            $estado
        ];

        $model_docsinfirmar = new Docsinfirmar();
        $data = $model_docsinfirmar->listar_notas_enfermeria_sinfirmar_ajax($p);

        $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : count($data);

        $result = [
            "PageStart" => $pagina,
            "pageSize" => $limit,
            "SearchText" => "",
            "ShowChildren" => true,
            "iTotalRecords" => $iTotalDisplayRecords,
            "iTotalDisplayRecords" => $iTotalDisplayRecords,
            "aaData" => $data
        ];
        return response()->json($result);
    }

    public function genera_csv_notas_enfermeria(Request $request){
        /* if (!self::establecimientoHabilitadoFirmasEnfermeria(Auth::user()->id_establecimiento)) {
            return response()->json(['status' => 'error', 'message' => 'Módulo no habilitado para este establecimiento'], 403);
        } */
        try {
            Log::info("=========== INICIO genera_csv_notas_enfermeria ===========");
            $pathCsv = public_path('tmp/csv');
            $pathZip = public_path('tmp/zip');

            foreach ([$pathCsv, $pathZip, "$pathZip/firmados", "$pathZip/sin_firma"] as $dir) {
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }
            }

            $csv = (Auth::check() ? Auth::user()->id : 'user') . "_" . date("YmdHis");

            $enfermeriaIds = json_decode($request->enfermeriaSeleccionadas ?: '[]', true) ?: [];
            $tiposAtencion = json_decode($request->tipoAtencionSeleccionada ?: '[]', true) ?: [];
            $tiposDocumento = json_decode($request->tipoDocumentoSeleccionado ?: '[]', true) ?: [];

            $notaEnfermeriaController = new \App\Http\Controllers\Nota_enfermeriaController();
            $enfermeriaController = new \App\Http\Controllers\EnfermeriaController();
            //$signosVitalesController = new \App\Http\Controllers\SignosVitalesController();
            //$kardexController = new \App\Http\Controllers\kardexEnfermeriasController();
            $balanceController = new \App\Http\Controllers\EnfermeriaBalanceHidricoController();
            $evolucionesController = new \App\Http\Controllers\EnfermeriaEvolucionesController();
            $cptController = new \App\Http\Controllers\CptEnfermeriaController();

            $documentos = [];

            $addDoc = function ($nombre, $id, $tipo) use (&$documentos) {
                $urlPdf = url("/tmp/pdf/sin_firma/{$nombre}_{$id}.pdf");
                $uploadLink = url("/upload_firma/?name=cf_{$nombre}_{$id}.pdf&id_tipo={$tipo}&id_resol={$id}");
                $filename = "cf_{$nombre}_{$id}.pdf";

                $documentos[] = [
                    $urlPdf,
                    $uploadLink,
                    $filename,
                    '0','45','350','70',
                    'Fecha de Firma: <DATE> Firmante: <SIGNER>',
                    url('/images/escudo.jpg'),'1','7'
                ];
            };

            foreach ($enfermeriaIds as $i => $id_raw) {
                $id_principal = intval($id_raw);
                $tipo_atencion = isset($tiposAtencion[$i]) ? intval($tiposAtencion[$i]) : 0;
                $documento = isset($tiposDocumento[$i]) ? strtoupper(trim($tiposDocumento[$i])) : '';

                if ($id_principal <= 0 || !in_array($tipo_atencion, [1, 2, 3])) {
                    Log::warning("Ítem de enfermería inválido en índice $i", ["id" => $id_raw, "tipo" => $tipo_atencion]);
                    continue;
                }

                Log::info("Generando PDF de firma de enfermería", ["id" => $id_principal, "tipo_atencion" => $tipo_atencion, "documento" => $documento]);

                switch ($documento) {
                    case 'NOTA DE ENFERMERIA':
                        if ($tipo_atencion == 1) {
                            $enfermeriaController->consultaExternaGenerarPDFFirmarEnfermeria($id_principal);
                            $addDoc("nota_enfermeria_consulta_externa", $id_principal, "nota_enfermeria_ce");
                        } else {
                            $notaEnfermeriaController->GenerarPDFFirmarEnfermeria($id_principal, $tipo_atencion);
                            $addDoc("nota_enfermeria", $id_principal, "nota_enfermeria");
                        }
                        break;

                    // case 'SIGNOS VITALES':
                    //     $signosVitalesController->GenerarPDFFirmarSignosConsolidado($id_principal, $tipo_atencion);
                    //     $addDoc("signos_vitales_consolidado", $id_principal, "signos_vitales");
                    //     break;

                    // case 'KARDEX':
                    //     $kardexController->generatePdfKardexEnfermeriaFirmar($id_principal, $tipo_atencion);
                    //     $addDoc("kardex_enfermeria", $id_principal, "kardex_enfermeria");
                    //     break;

                    case 'BALANCE HIDRICO':
                        $balanceController->generarPdfParaFirma($id_principal, $tipo_atencion);
                        $addDoc("balance_hidrico", $id_principal, "balance_hidrico");
                        break;

                    case 'EVOLUCION DE ENFERMERIA':
                        $evolucionesController->generarPdfParaFirmaEvoluciones($id_principal);
                        $addDoc("evoluciones_enfermeria", $id_principal, "evoluciones_enfermeria");
                        break;

                    case 'CPT':
                        $cptController->generarPdfParaFirmaCpt($id_principal);
                        $addDoc("cpt_enfermeria", $id_principal, "cpt_enfermeria");
                        break;

                    default:
                        Log::warning("Tipo de documento de enfermería no reconocido en índice $i: '$documento'");
                        break;
                }
            }

            if (count($documentos) === 0) {
                Log::info("No se encontraron notas de enfermería para firmar.");
                return response()->json([
                    'status' => 'ok',
                    'message' => 'No se encontraron notas de enfermería para firmar.',
                    'sw' => false
                ]);
            }

            Excel::create($csv, function ($excel) use ($documentos) {
                $excel->sheet('GENERAL', function ($sheet) use ($documentos) {
                    $sheet->fromArray($documentos, null, '', false, false);
                });
            })->store('csv', $pathCsv);

            $zip7 = new Archive7z("$pathZip/sin_firma/{$csv}.7z");

            foreach ($documentos as $doc) {
                $fileBase = basename(parse_url($doc[0], PHP_URL_PATH));
                $rutaSinFirma = public_path("tmp/pdf/sin_firma/{$fileBase}");
                $rutaFirmado  = public_path("tmp/pdf/firmados/{$fileBase}");

                if (file_exists($rutaFirmado) && is_readable($rutaFirmado)) {
                    $zip7->addEntry($rutaFirmado);
                    continue;
                }

                if (file_exists($rutaSinFirma) && is_readable($rutaSinFirma)) {
                    $zip7->addEntry($rutaSinFirma);
                    continue;
                }

                Log::warning("→ NO EXISTE PDF de nota de enfermería, se omite", ["archivo" => $fileBase]);
            }

            Log::info("=========== FIN genera_csv_notas_enfermeria ===========");

            return response()->json(["csv" => $csv, "sw" => true]);

        } catch (\Exception $e) {
            Log::error("Error en genera_csv_notas_enfermeria: " . $e->getMessage(), [
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Ocurrió un error al generar el CSV y el archivo .7z de notas de enfermería',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function actualizar_flag_docsinfirmar_enfermeria(Request $request){
        /* if (!self::establecimientoHabilitadoFirmasEnfermeria(Auth::user()->id_establecimiento)) {
            return response()->json(['status' => 'error', 'message' => 'Módulo no habilitado para este establecimiento'], 403);
        } */
        try {
            $ids = json_decode($request->enfermeriaSeleccionadas ?: '[]', true) ?: [];
            $documentos = json_decode($request->tipoDocumentoSeleccionado ?: '[]', true) ?: [];

            $tablaPorDocumento = [
                'NOTA DE ENFERMERIA'      => ['tabla' => 'enfermerias', 'columna' => 'id'],
                //'SIGNOS VITALES'          => ['tabla' => 'signo_vitales', 'columna' => 'id_enfermeria'],
                //'KARDEX'                  => ['tabla' => 'kardex_enfermerias', 'columna' => 'id'],
                'BALANCE HIDRICO'         => ['tabla' => 'balance_hidricos', 'columna' => 'id'],
                'EVOLUCION DE ENFERMERIA' => ['tabla' => 'nota_enfermeria_evoluciones', 'columna' => 'id'],
                'CPT'                     => ['tabla' => 'nota_enfermeria_cpt', 'columna' => 'id'],
            ];

            foreach ($ids as $i => $id_raw) {
                $id = intval($id_raw);
                $documento = isset($documentos[$i]) ? strtoupper(trim($documentos[$i])) : '';

                if ($id <= 0 || !isset($tablaPorDocumento[$documento])) {
                    Log::warning("actualizar_flag_docsinfirmar_enfermeria: ítem inválido en índice $i", ["id" => $id_raw, "documento" => $documento]);
                    continue;
                }

                $destino = $tablaPorDocumento[$documento];
                DB::table($destino['tabla'])
                    ->where($destino['columna'], $id)
                    ->update(['flag_docsinfirmar' => 1]);
            }

            return response()->json(['status' => 'ok']);

        } catch (\Exception $e) {
            Log::error("Error en actualizar_flag_docsinfirmar_enfermeria: " . $e->getMessage(), [
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Ocurrió un error al actualizar el estado de firma en DocSinFirmar',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function listar_docsfirmados_ajax(Request $request){
            // Obtener los parámetros de la petición
            $id_establecimiento = Auth::user()->id_establecimiento;
            $id_user = Auth::user()->id;
            $fecha_ini = isset($request->fecha_ini) && $request->fecha_ini != '' ? $request->fecha_ini : '2000-01-01';
            $fecha_fin = isset($request->fecha_fin) && $request->fecha_fin != '' ? $request->fecha_fin : date('Y-m-d');
            $pagina = isset($request->NumeroPagina) ? $request->NumeroPagina : 1;
            $limit = isset($request->NumeroRegistros) ? $request->NumeroRegistros : 20;
            $p = [
                $id_establecimiento,
                $id_user,
                $fecha_ini,
                $fecha_fin,
                $pagina,
                $limit
            ];

            $model_docsinfirmar = new Docsinfirmar();
            $data = $model_docsinfirmar->getDocsfirmadosUsuario($p);

            $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : count($data);

            $result = [
                "PageStart" => $pagina,
                "pageSize" => $limit,
                "SearchText" => "",
                "ShowChildren" => true,
                "iTotalRecords" => $iTotalDisplayRecords,
                "iTotalDisplayRecords" => $iTotalDisplayRecords,
                "aaData" => $data
            ];
            return response()->json($result);
        }
    //============================================== OBTENER EL TIPO DE ATENCIÓN ==============================================
        public function obtener_tipo_atencion_prestacion($id_prestacion, $doc_firmar){
            if (strtoupper($doc_firmar) === 'EVOLUCION') {
                $evolucion = (new Evaluacione)->GetEvaluacioneById($id_prestacion);
                if (!$evolucion) {
                    return response()->json(['error' => 'Evolución no encontrada'], 404);
                }
                return response()->json(['tipo' => $evolucion->id_tipo_atencion]);
            }else if(strtoupper($doc_firmar) === 'ALTA EMERGENCIA'){
                $emergencia = (new Emergencia)->getIdPrestacionid_Ext($id_prestacion);
                if (!$emergencia) {
                    return response()->json(['error' => 'Emergencia no encontrada'], 404);
                }
                return response()->json(['tipo' => $emergencia->id_tipo_atencion]);
            }else{
                $prestacion = (new Prestaciones)->GetPrestacionByIdPrestacion($id_prestacion);
                if (!$prestacion) {
                    return response()->json(['error' => 'Prestación no encontrada'], 404);
                }
                return response()->json(['tipo' => $prestacion->id_tipo_atencion]);
            }
        }

    //================================================== GENERA CSV ============================================================= 
    public function genera_csv_docsinfirmar(Request $request){
        try {
            //Log::info("=========== INICIO genera_csv_docsinfirmar ===========");
            $pathCsv = public_path('tmp/csv');
            $pathZip = public_path('tmp/zip');

            foreach ([$pathCsv, $pathZip, "$pathZip/firmados", "$pathZip/sin_firma"] as $dir) {
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                    //Log::info("Creando directorio: $dir");
                }
            }

            $csv = (Auth::check() ? Auth::user()->id : 'user') . "_" . date("YmdHis");

            $prestaciones  = json_decode($request->prestacionesSeleccionadas ?: '[]', true) ?: [];
            $id_princip    = json_decode($request->id_principal_selec ?: '[]', true) ?: [];
            $tipo_doc      = json_decode($request->tipodocseleccionado ?: '[]', true) ?: [];
            $tipo_atencion = json_decode($request->tipoantencseleccionado ?: '[]', true) ?: [];
            $evaluacione   = json_decode($request->evaluacionesseleccionadas ?: '[]', true) ?: [];
            $id_interc     = json_decode($request->interconsultaseleccionada ?: '[]', true) ?: [];

            //Log::info("Prestaciones recibidas:", [
            //     "prestaciones" => $prestaciones,
            //     "tipo_doc" => $tipo_doc,
            //     "tipo_atencion" => $tipo_atencion,
            //     "id_princip" => $id_princip,
            //     "evaluacione" => $evaluacione,
            //     "id_interc" => $id_interc
            // ]);

            $n = count($prestaciones);
            for ($i = 0; $i < $n; $i++) {
                $raw = isset($prestaciones[$i]) ? trim($prestaciones[$i]) : '';
                if ($raw === '') {
                    Log::warning("prestacion vacía en índice $i, se omitirá.");
                    continue;
                }

                if (strpos($raw, '|') !== false) {
                    $parts = explode('|', $raw);
                    $prestaciones[$i] = trim($parts[0]);

                    if (!isset($tipo_doc[$i]) || trim($tipo_doc[$i]) === '') {
                        $tipo_doc[$i] = isset($parts[1]) ? trim($parts[1]) : '';
                    }

                    if (!isset($id_princip[$i]) || trim($id_princip[$i]) === '') {
                        $id_princip[$i] = isset($parts[2]) ? trim($parts[2]) : 0;
                    }
                } else {
                    $prestaciones[$i] = trim($raw);
                }

                if (!isset($tipo_doc[$i])) $tipo_doc[$i] = '';
                if (!isset($tipo_atencion[$i])) $tipo_atencion[$i] = 0;
                if (!isset($id_princip[$i])) $id_princip[$i] = 0;
                if (!isset($evaluacione[$i])) $evaluacione[$i] = null;
                if (!isset($id_interc[$i])) $id_interc[$i] = 0;
            }

            $model_receta        = new Receta_vale;
            $model_laboratorio   = new \App\Models\Laboratorio;
            $model_radiologia    = new \App\Models\Radiologia;
            $model_interconsulta = new \App\Models\Interconsulta;
            $model_evaluacion    = new \App\Models\Evaluacione;
            $excelController     = new \App\Http\Controllers\ExcelController();
            $pdfController     = new \App\Http\Controllers\PdfController();

            $documentos = [];
            $idsExplicitos = array_map('intval', $id_princip);
            $addDoc = function ($nombre, $id, $tipo) use (&$documentos) {
                // Log::info("Agregando documento al listado", ["nombre" => $nombre,"id" => $id,"tipo" => $tipo]);

                $urlPdf = url("/tmp/pdf/sin_firma/{$nombre}_{$id}.pdf");
                $uploadLink = url("/upload_firma/?name=cf_{$nombre}_{$id}.pdf&id_tipo={$tipo}&id_resol={$id}");
                $filename = "cf_{$nombre}_{$id}.pdf";

                $documentos[] = [
                    $urlPdf,
                    $uploadLink,
                    $filename,
                    '0','45','350','70',
                    'Fecha de Firma: <DATE> Firmante: <SIGNER>',
                    url('/images/escudo.jpg'),'1','7'
                ];
            };

            foreach ($prestaciones as $i => $id_prestacion_raw) {
                $id_prestacion = intval($id_prestacion_raw);
                //Log::info("Iteración $i: ID de prestación ='$id_prestacion_raw', convertido a int=$id_prestacion");

                if ($id_prestacion <= 0) {
                    Log::warning("ID de prestación inválido en índice $i: " . $id_prestacion_raw);
                    continue;
                }

                $docActual  = isset($tipo_doc[$i]) ? strtoupper(trim($tipo_doc[$i])) : '';
                $atenActual = isset($tipo_atencion[$i]) ? intval($tipo_atencion[$i]) : 0;
                $id_princ   = isset($id_princip[$i]) ? intval($id_princip[$i]) : 0;
                $evol       = isset($evaluacione[$i]) ? trim($evaluacione[$i]) : null;
                $interc     = isset($id_interc[$i]) ? intval($id_interc[$i]) : 0;

                //Log::info("Datos extraídos: docActual='$docActual', atenActual=$atenActual, id_princ=$id_princ, evol='$evol', interc=$interc");

                $tieneEvolucion = $evol !== null && $evol != 0 && $evol !== '';
                $tieneInterconsulta = $interc != 0;
                //Log::info("¿Tiene evolución?: " . ($tieneEvolucion ? 'SI' : 'NO') . " | ¿Tiene interconsulta?: " . ($tieneInterconsulta ? 'SI' : 'NO'));

                $recetas        = [];
                $laboratorios   = [];
                $radiologias    = [];
                $interconsultas = [];

                if ($tieneInterconsulta) {
                    Log::info("Obteniendo datos asociados a interconsulta-respuesta: id_interconsulta=$interc");
                    $recetas        = $model_receta->GetRecetasByIdInterconsulta($interc);
                    $laboratorios   = $model_laboratorio->GetLaboratorioByIdInterconsulta($interc);
                    $radiologias    = $model_radiologia->GetRadiologiaByIdInterconsulta($interc);
                } elseif ($tieneEvolucion) {
                    $id_eval = $evol;
                    $id_prest = $id_prestacion;

                    //Log::info("Obteniendo datos asociados a evolución: id_prest=$id_prest, id_eval=$id_eval");
                    $recetas        = $model_receta->GetDatosRecetasByIdEvaluacion($id_eval);
                    $laboratorios   = $model_laboratorio->GetLaboratorioByIdPrestacionEvaluacion($id_prest, $id_eval);
                    $radiologias    = $model_radiologia->GetRadiologiaByIdPrestacionEvaluacion($id_prest, $id_eval);
                    $interconsultas = $model_interconsulta->GetInterconsultaByIdPrestacionEvaluaciondsf($id_prest, $id_eval);
                } else {
                    //Log::info("Obteniendo datos sin evolución para id_prestacion=$id_prestacion");
                    $recetas        = $model_receta->GetRecetasByIdPrestacion($id_prestacion);
                    $laboratorios   = $model_laboratorio->GetLaboratorioByIdPrestacion($id_prestacion);
                    $radiologias    = $model_radiologia->GetRadiologiaByIdPrestacion($id_prestacion);
                    $interconsultas = $model_interconsulta->GetInterconsultaByIdPrestacion($id_prestacion);
                }

                //Log::info("Cantidad de registros obtenidos: recetas=" . count($recetas) . ", laboratorios=" . count($laboratorios) . ", radiologias=" . count($radiologias) . ", interconsultas=" . count($interconsultas));

                switch ($docActual) {
                    case 'ATENCIÓN':
                        //Log::info("Procesando ATENCIÓN con tipo de atención $atenActual");
                        $model_prestacion   = new \App\Models\Prestaciones;
                        $prest = $model_prestacion
                        ->select('id','flag','id_tipo_atencion','id_ext')
                        ->find($id_prestacion);

                        if (!$prest) {
                            Log::warning("Prestación no encontrada", ["id" => $id_prestacion]);
                            continue;
                        }

                        //Log::info("Prestación encontrada", [
                        //     "id" => $prest->id,
                        //     "flag" => $prest->flag,
                        //     "tipo_atencion" => $prest->id_tipo_atencion
                            
                        // ]);

                        if ($prest->flag == 0 || $prest->flag == 2) {

                            if ($prest->id_tipo_atencion == 3) {

                                //Log::info("Generando PDF ATENCIÓN HOSPITALARIA");

                                $cq = DB::table('anestesia_quirurgicas')
                                    ->where('id_prestacion', $id_prestacion)
                                    ->first();

                                if ($cq) {
                                    //Log::info("Tiene cirugía, usa plantilla CQ");
                                    $excelController->ver_hospitalizacion_imprimir_cq_anestesia($id_prestacion);
                                    $addDoc("hospitalizacion_atencion", $id_prestacion, "prestacion");
                                } else {
                                    //Log::info("No es cirugía, plantilla normal");
                                    if(!empty($prest->id_ext)){
                                        //Log::info("tiene id_ext ->atencion de hospi");
                                        //Log::info("Generando PDF hospi");
                                        $excelController->ver_hospitalizacion_imprimir($id_prestacion);
                                        $addDoc("hospitalizacion_atencion", $id_prestacion, "prestacion");
                                    }else{
                                        //Log::info("No tiene id_ext ->se atiende como CE");
                                        //Log::info("Generando PDF prestación sin id_ext");
                                        $this->ver_pdf_prestacion($id_prestacion);
                                        $addDoc("prestacion", $id_prestacion, "prestacion");;
                                    }
                                }                                
                            } else {

                                    //Log::info("Generando PDF prestación normal");
                                    $this->ver_pdf_prestacion($id_prestacion);
                                
                                $addDoc("prestacion", $id_prestacion, "prestacion");
                            }
                        }
                        break;
                    case 'EVOLUCION':
                        if ($tieneEvolucion) {
                            //Log::info("Procesando EVOLUCION con tipo de atención $atenActual");
                            if ($atenActual == 3) {
                                $excelController->ver_evolucion_imprimir($id_eval);
                                $addDoc("evolucion", $id_eval, "evolucion");
                                //Log::info("EVOLUCIÓN HOSPITALIZACIÓN PDF generado para id_eval=$id_eval");
                            } elseif ($atenActual == 2) {
                                $excelController->ver_evaluacion_imprimir($id_eval);
                                $addDoc("evaluacion", $id_eval, "evaluacion");
                                //Log::info("EVALUACIÓN EMERGENCIA PDF generado para id_eval=$id_eval");
                            } else {
                                Log::warning("EVOLUCION: tipo de atención inesperado ($atenActual) en prestacion $id_prestacion");
                            }
                        } else {
                            Log::warning("EVOLUCION sin id de evaluacion para prestacion $id_prestacion");
                        }
                        break;

                    case 'RECETA':
                        if (!$recetas) {
                            Log::warning("Receta no encontrada", ["id" => $id_princ]);
                            continue;
                        }
                        foreach ($recetas as $r) {
                            //Log::info("Procesando RECETA id={$r->id}, ruta_receta='{$r->ruta_receta}'");
                            // if (empty($r->ruta_receta)) {
                            if($r->flag == 0 || $r->flag == 2){
                                // Receta de atención quirúrgica
                                if ($this->esRecetaAtencionQuirurgica($r->id)) {
                                    $atencionQxController = new \App\Http\Controllers\AtencionQuirurgicaController();
                                    $atencionQxController->recetaGenerarPdfParaFirma($r->id);
                                    $addDoc("receta_atencion_quirurgica", $r->id, "receta_atencion_quirurgica");
                                } else {
                                    $excelController->ver_receta_vale($r->id);
                                    $addDoc("receta", $r->id, "receta");
                                }
                                //Log::info("RECETA PDF generado para id={$r->id}");
                            }
                        }
                        break;

                    case 'ORDEN DE LABORATORIO':
                        if (!$laboratorios) {
                            Log::warning("laboratorio no encontrado", ["id" => $id_princ]);
                            continue;
                        }
                        foreach ($laboratorios as $lab) {
                            //Log::info("Procesando LABORATORIO id={$lab->id}, fecha_firma='{$lab->fecha_firma}', id_establecimiento={$lab->id_establecimiento}");
                            // if ($lab->fecha_firma) {
                            if ($lab->flag == 0 || $lab->flag == 2) {
                                if ($lab->id_establecimiento == 76) {
                                    $excelController->ver_laboratorio_imprimir($lab->id);
                                } else {
                                    $excelController->generateRUAPdf_nivel1y2($lab->id);
                                }
                                $pdfPath = public_path("/tmp/pdf/sin_firma/laboratorio_{$lab->id}.pdf");
                                if (file_exists($pdfPath)) {
                                    $addDoc("laboratorio", $lab->id, "laboratorio");
                                    //Log::info("LABORATORIO PDF generado: $pdfPath");
                                } else {
                                    Log::warning("Laboratorio PDF no generado para id {$lab->id}");
                                }
                            }
                        }
                        break;

                    case 'ORDEN DE DIAGNOSTICO POR IMÁGENES':
                        if (!$radiologias) {
                            Log::warning("radiología no encontrada", ["id" => $id_princ]);
                            continue;
                        }
                        foreach ($radiologias as $rx) {
                            //Log::info("Procesando RADIOLOGIA id={$rx->id}, fecha_firma='{$rx->fecha_firma}'");
                            // if (empty($rx->fecha_firma)) {
                            if ($rx->flag == 0 || $rx->flag == 2) {
                                $excelController->ver_radiologia_imprimir($rx->id);
                                $pdfPath = public_path("/tmp/pdf/sin_firma/radiologia_{$rx->id}.pdf");
                                if (file_exists($pdfPath)) {
                                    $addDoc("radiologia", $rx->id, "radiologia");
                                    //Log::info("RADIOLOGIA PDF generado: $pdfPath");
                                } else {
                                    Log::warning("Radiologia PDF no generado para id {$rx->id}");
                                }
                            }
                        }
                        break;

                    case 'INTERCONSULTA':
                        if (!$interconsultas) {
                            Log::warning("interconsulta no encontrada", ["id" => $id_princ]);
                            continue;
                        }
                        foreach ($interconsultas as $itc) {
                            //Log::info("Procesando INTERCONSULTA id={$itc->id_interconsulta}, fecha_firma='{$itc->fecha_firma_interconsulta}'");
                            // if (empty($itc->fecha_firma_interconsulta)) {
                            if ($itc->flag == 0 || $itc->flag == 2) {
                                $excelController->genera_pdf_interconsulta($itc->id_interconsulta, true);
                                $pdfPath = public_path("/tmp/pdf/sin_firma/interconsulta_{$itc->id_interconsulta}.pdf");
                                if (file_exists($pdfPath)) {
                                    $addDoc("interconsulta", $itc->id_interconsulta, "interconsulta");
                                    //Log::info("INTERCONSULTA PDF generado: $pdfPath");
                                } else {
                                    Log::warning("Interconsulta PDF no generado para id {$itc->id_interconsulta}");
                                }
                            }
                        }
                        break;
                    
                    case 'INTERCONSULTA-RESPUESTA':
                        $itc_rpta = DB::table('interconsultas')->where('id', $id_princ)->select('id as id_interconsulta', 'flag_rpta')->first();
                        if (!$itc_rpta) {
                            Log::warning("interconsulta no encontrada", ["id" => $id_princ]);
                            continue;
                        }
                        Log::info("Procesando INTERCONSULTA-RESPUESTA id={$itc_rpta->id_interconsulta}, flag={$itc_rpta->flag_rpta}");

                        if ($itc_rpta->flag_rpta == 0) {
                            $excelController->genera_pdf_interconsulta_rpta($itc_rpta->id_interconsulta, true);
                            $pdfPath = public_path("/tmp/pdf/sin_firma/interconsulta_rpta_{$itc_rpta->id_interconsulta}.pdf");
                            if (file_exists($pdfPath)) {
                                $addDoc("interconsulta_rpta", $itc_rpta->id_interconsulta, "interconsulta_rpta");
                                Log::info("INTERCONSULTA-RESPUESTA PDF generado: $pdfPath");
                            } else {
                                Log::warning("Interconsulta-respuesta PDF no generado para id {$itc_rpta->id_interconsulta}");
                            }
                        }
                        break;

                    case 'ALTA EMERGENCIA':
                        $emergencia = Prestaciones::join('emergencias', 'emergencias.id', '=', 'prestaciones.id_ext')
                            ->where('prestaciones.id', $id_prestacion)
                            ->select('prestaciones.id_ext','emergencias.id_tipo_egres_med')
                            ->first();

                        if ($emergencia) {
                            //Log::info("Procesando ALTA EMERGENCIA id_ext={$emergencia->id_ext}, id_tipo_egres_med={$emergencia->id_tipo_egres_med}");
                            $excelController->genera_pdf_alta_emergencia($emergencia->id_ext, true);
                            $addDoc("alta_emergencia", $emergencia->id_ext, "alta_emergencia");

                            if ($emergencia->id_tipo_egres_med == 2) {
                                $excelController->genera_pdf_indicaciones_alta_emergencia($emergencia->id_ext, true);
                                $addDoc("emergencia_indicaciones_alta", $emergencia->id_ext, "emergencia_indicaciones_alta");
                            }

                            if ($emergencia->id_tipo_egres_med == 4) {
                                $excelController->genera_pdf_emergencia_epicrisis($emergencia->id_ext, true);
                                $addDoc("emergencia_epicrisis", $emergencia->id_ext, "emergencia_epicrisis");
                            }
                        } else {
                            Log::warning("No se encontró emergencia relacionada a la prestación $id_prestacion");
                        }
                        break;
                    
                    case 'ALTA HOSPITALIZACION':
                        $hospitalizacion = Prestaciones::join('hospitalizaciones', 'hospitalizaciones.id', '=', 'prestaciones.id_ext')
                            ->where('prestaciones.id', $id_prestacion)
                            ->select('prestaciones.id_ext','hospitalizaciones.id')
                            ->first();

                        if ($hospitalizacion) {
                            // ALTA HOSPI
                            if ($hospitalizacion->id_ext != 4419) {
                                //Log::info("Procesando ALTA HOSPITALIZACION id_ext={$hospitalizacion->id_ext}");
                                $excelController->genera_pdf_alta_hospitalizacion($hospitalizacion->id_ext, true);
                                $addDoc("alta-hospitalizacion", $hospitalizacion->id_ext, "alta-hospitalizacion");
                            }
                            // EPICRISIS HOSPI
                            $excelController->genera_hospitalizacion_epicrisis($hospitalizacion->id_ext, true);
                            $addDoc("hospitalizacion-epicrisis", $hospitalizacion->id_ext, "hospitalizacion-epicrisis");

                            // PAPELETA DE EGRESO HOSPI
                            $model_hospitalizacion = new Hospitalizacione();
                            $papeleta_egreso = $model_hospitalizacion->getDatosPapeletaEgreso($hospitalizacion->id_ext);
                            if(isset($papeleta_egreso)){
                                $excelController->genera_hospitalizacion_papeleta_egreso($hospitalizacion->id_ext, true);
                                $addDoc("hospitalizacion-papeleta-egreso", $hospitalizacion->id_ext, "hospitalizacion-papeleta-egreso");
                            }

                        } else {
                            Log::warning("No se encontró emergencia relacionada a la prestación $id_prestacion");
                        }
                        break;
                    
                    case 'INFORME RADIOLOGIA':
                        $model_prestacion = new \App\Models\Prestaciones;
                        $model_radiologia = new \App\Models\Radiologia;
                        $id_rad = DB::table('prestacion_procedimientos')->where('id',$id_princ)->value('id_lab_rad');
                        $radiologia = $model_radiologia->GetRadiologiaById($id_rad);
                        $id_servicio_rad = DB::table('categoria_radiologias')->join('radnivelatenciones as r', 'r.id_categoria', '=', 'categoria_radiologias.id')->join('prestacion_procedimientos', 'prestacion_procedimientos.id_rua', '=', 'r.id')->where('prestacion_procedimientos.id', $id_princ)->value('categoria_radiologias.id_servicio');
                        $id_prestacion=$radiologia->id_prestacion;
                        $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
                        $procradiologia = $model_prestacion->GetProcedimientosByIdPrestacionAndIdLabRadAndTipoAndIdServicioRad($id_rad,$id_servicio_rad, $id_princ);
                        //Log::info("El valor de procradiologia es la siguiente : ",$procradiologia);
                        if ($procradiologia) {
                            // INFORME RADIOLÓGICO
                            //Log::info("Procesando INFORME RADIOLÓGICO id={$procradiologia[0]->id}");
                            $excelController->genera_pdf_informe_imagenologia($prestacion, $radiologia, $id_prestacion,$id_rad,$id_servicio_rad,$procradiologia[0]->id, true);
                            $addDoc("informe-imagenologia", $id_princ, "informe-imagenologia");
                        } else {
                            Log::warning("No se encontró informe radiológico relacionado a la prestación $id_prestacion");
                        }
                        break;

                    case 'INFORME RADIOLOGIA ADICIONAL(ADENDA)':
                        $model_prestacion = new \App\Models\Prestaciones;
                        $model_radiologia = new \App\Models\Radiologia;
                        $ira = DB::table('informes_radiologias_adicionales')->join('prestacion_procedimientos as pp', 'pp.id', '=', 'informes_radiologias_adicionales.id_prestacion_procedimiento')->where('informes_radiologias_adicionales.id', $id_princ)->select('informes_radiologias_adicionales.id','informes_radiologias_adicionales.id_prestacion_procedimiento','pp.id_prestacion','pp.id_lab_rad')->first();
                        $id_servicio_rad = DB::table('categoria_radiologias')->join('radnivelatenciones as r', 'r.id_categoria', '=', 'categoria_radiologias.id')->join('prestacion_procedimientos', 'prestacion_procedimientos.id_rua', '=', 'r.id')->where('prestacion_procedimientos.id', $ira->id_prestacion_procedimiento)->value('categoria_radiologias.id_servicio');
                        //$id_ira = DB::table('informes_radiologias_adicionales')->join('prestacion_procedimientos as pp', 'pp.id', '=', 'informes_radiologias_adicionales.id_prestacion_procedimiento')->where('informes_radiologias_adicionales.id_prestacion_procedimiento', $id_princ)->value('informes_radiologias_adicionales.id');
                        $radiologia = $model_radiologia->GetRadiologiaById($ira->id_lab_rad);
                        $id_prestacion=$ira->id_prestacion;
                        $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
                        $procradiologia = $model_radiologia->GetProcedimientosByIdPrestacionAndIdLabRadAndTipoAndIdServicioRadInformeAdicional($ira->id_lab_rad,$id_servicio_rad, $ira->id_prestacion_procedimiento,$id_princ);
                        //Log::info("El valor de procradiologia es la siguiente : ", ['procradiologia' => $procradiologia]);
                        if ($procradiologia) {
                            // INFORME RADIOLÓGICO
                            //Log::info("Procesando INFORME RADIOLÓGICO id={$procradiologia[0]->id}");
                            $excelController->genera_pdf_informe_imagenologia_adicional($prestacion, $radiologia, $id_prestacion,$radiologia->id,$id_servicio_rad,$procradiologia[0]->id, true,$id_princ);
                            $addDoc("informe-imagenologia-adicional", $id_princ, "informe-imagenologia-adicional");
                        } else {
                            Log::warning("No se encontró informe radiológico adicional relacionado a la prestación $id_prestacion");
                        }
                        break;

                    default:
                        //Log::info("Tipo de documento no manejado o vacío en índice $i: '$docActual' para prestacion $id_prestacion");
                        break;
                }
            }

            if (count($documentos) === 0) {
                //Log::info("No se encontraron documentos para firmar.");
                return response()->json([
                    'status' => 'ok',
                    'message' => 'No se encontraron documentos para firmar.',
                    'sw' => false
                ]);
            }

            //Log::info("Generando CSV...");

            Excel::create($csv, function ($excel) use ($documentos) {
                $excel->sheet('GENERAL', function ($sheet) use ($documentos) {
                    $sheet->fromArray($documentos, null, '', false, false);
                });
            })->store('csv', $pathCsv);

            //Log::info("Creando archivo 7z con los PDFs...");

            $zip7 = new Archive7z("$pathZip/sin_firma/{$csv}.7z");

            foreach ($documentos as $doc) {
                $fileBase = basename(parse_url($doc[0], PHP_URL_PATH)); 
                $rutaSinFirma = public_path("tmp/pdf/sin_firma/{$fileBase}");
                $rutaFirmado  = public_path("tmp/pdf/firmados/{$fileBase}");

                //Log::info("Validando existencia de PDFs", [
                //     "sin_firma" => $rutaSinFirma,
                //     "firmado"   => $rutaFirmado
                // ]);

                if (file_exists($rutaFirmado) && is_readable($rutaFirmado)) {
                    //Log::info("→ Usando PDF FIRMADO", ["file" => $rutaFirmado]);
                    $zip7->addEntry($rutaFirmado);
                    continue;
                }

                if (file_exists($rutaSinFirma) && is_readable($rutaSinFirma)) {
                    //Log::info("→ Usando PDF SIN FIRMA", ["file" => $rutaSinFirma]);
                    $zip7->addEntry($rutaSinFirma);
                    continue;
                }

                Log::warning("→ NO EXISTE NI FIRMADO NI SIN FIRMA, se omite", [
                    "sin_firma" => $rutaSinFirma,
                    "firmado"   => $rutaFirmado
                ]);
            }

            //Log::info("=========== FIN genera_csv_docsinfirmar ===========");

            return response()->json(["csv" => $csv, "sw" => true]);

        } catch (\Exception $e) {
            Log::error("Error en genera_csv_docsinfirmar: " . $e->getMessage(), [
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Ocurrió un error al generar el CSV y el archivo .7z',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function esRecetaAtencionQuirurgica($id_receta){
        $cqx = DB::table('receta_vales as rv')
            ->join('cqx_atenciones as ca', 'ca.id_prestacion', '=', 'rv.id_prestacion')
            ->where('rv.id', $id_receta)
            ->select('ca.id')
            ->first();
        return !empty($cqx);
    }

//=========================================== ACTUALIZA LOS CAMPOS DE FIRMA =============================================================
    public function actualiza_prestacion_mod_docsinfirmar(Request $request){
        try {

            $tipo = $request->id_tipo;
            $id = intval($request->id_resol);

            switch ($tipo) {
                case "prestacion":
                    $prestacion = \App\Models\Prestaciones::find($id);
                    if (!$prestacion) {
                        return response()->json(['status' => 'error', 'message' => 'Prestación no encontrada']);
                    }
                    if ($prestacion->flag == 0 || $prestacion->flag == 2) {
                        $prestacion->fecha_firma = date("Y-m-d H:i:s");
                        $prestacion->ruta_prestacion = date("Y/m/d") . "/cf_prestacion_" . $id . ".pdf";
                        $prestacion->flag = 1;
                        $prestacion->flag_docsinfirmar = 1;
                        $prestacion->save();
                        //Log::info('prestación actualizada ...');
                    }else{
                        return response()->json(['status' => 'omitido', 'message' => 'Prestación ya firmada']);
                    }
                    break;
                case "hospitalizacion":
                        $model_prestacion = new \App\Models\Prestaciones;
                        $prestacion = $model_prestacion->GetPrestacionByIdPrestacion($id);
                        $cq_anestesia = DB::table('anestesia_quirurgicas')
                                        ->where('anestesia_quirurgicas.id_prestacion','=',$id)
                                        ->select('anestesia_quirurgicas.*')
                                        ->first();
                        
                        if($prestacion->flag == 0 || $prestacion->flag == 2){
                            $prestacion->fecha_firma = date("d-m-Y H:i:s");
                            if(!empty($prestacion->id_ext)):
                                $prestacion->ruta_prestacion = date("Y/m/d")."/cf_hospitalizacion_atencion_".$id.".pdf";
                            else:
                                $prestacion->ruta_prestacion = date("Y/m/d")."/cf_prestacion_".$id.".pdf";
                            endif;
                            $prestacion->flag = 1;
                            $prestacion->flag_docsinfirmar = 1;
                            $prestacion->save();
                            //Log::info('hospitalización actualizada ...');
                        }else{
                            return response()->json(['status' => 'omitido', 'message' => 'hospitalización ya firmada']);
                        }
                        if (is_null($cq_anestesia)) {
                            $obj_hoja = \App\Models\HospitalizacionAtencione::where('id', '=', $prestacion->id_ext)->first();
                            if(isset($obj_hoja)){
                                if($obj_hoja->fecha_firma_terapeutica == ""){
                                    $obj_hoja->fecha_firma_terapeutica = date("d-m-Y H:i:s");
                                    $obj_hoja->ruta_terapeutica = date("Y/m/d")."/cf_hoja_terapeutica_".$id.".pdf";
                                    $obj_hoja->flag_terapeutica = 1;
                                    $obj_hoja->save();
                                }
                            }
                        }
                    break;
                //Evolución
                case 'evolucion':
                case 'evaluacion':
                    $evolucion = \App\Models\Evaluacione::find($request->id_principal);
                    if (!$evolucion) {
                        return response()->json(['status' => 'error', 'message' => 'Evolución no encontrada']);
                    }
                    $prefijos = [
                        'evolucion'  => 'cf_evaluacion_',
                        'evaluacion' => 'cf_evolucion_',
                    ];

                    $prefijo = $prefijos[$tipo];
                    //dd($prefijo); 

                    if($evolucion->flag == 0 || $evolucion->flag == 2){
                        $evolucion->fecha_firma       = date("Y-m-d H:i:s");
                        $evolucion->ruta_evaluacion   = date("Y/m/d") . "/" . $prefijo .$evolucion->id . ".pdf";
                        $evolucion->flag              = 1;
                        $evolucion->flag_docsinfirmar = 1;
                        $evolucion->save();
                        //Log::info('Evolución actualizada ...');
                    }else{
                        return response()->json(['status' => 'omitido', 'message' => 'Evolución ya firmada']);
                    }
                    break;

                // Receta
                case "receta":
                    $receta = \App\Models\Receta_vale::where('id', $request->id_principal)->where('id_prestacion', $id)->first();
                    if (!$receta) {
                        return response()->json(['status' => 'error', 'message' => 'Receta no encontrada']);
                    }
                    $prefijoReceta = $this->esRecetaAtencionQuirurgica($receta->id) ? "receta_atencion_quirurgica_" : "receta_";
                    $rutaPDF = public_path("tmp/pdf/sin_firma/{$prefijoReceta}{$receta->id}.pdf");
                    //Log::info("Validando PDF de Receta", [
                    //     "ruta" => $rutaPDF,
                    //     "existe" => file_exists($rutaPDF)
                    // ]);
                    if (!file_exists($rutaPDF) || !is_readable($rutaPDF)) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'PDF de Receta no existe, no se actualiza'
                        ]);
                    }
                    if ($receta->flag == 0 || $receta->flag == 2){
                        $receta->fecha_firma = date("Y-m-d H:i:s");
                        $receta->ruta_receta = date("Y/m/d") . "/cf_" . $prefijoReceta . $receta->id . ".pdf";
                        $receta->flag = 1;
                        $receta->flag_docsinfirmar = 1;
                        $receta->save();
                        //Log::info('Receta actualizada (PDF encontrado)...', [
                        //     "id" => $receta->id,
                        //     "pdf" => $receta->ruta_receta
                        // ]);
                    }else{
                        return response()->json(['status' => 'omitido', 'message' => 'Receta ya firmada']);
                    }
                    break;

                // Laboratorio
                case "laboratorio":
                    $lab = \App\Models\Laboratorio::where('id', $request->id_principal)->where('id_prestacion', $id)->first();
                    if (!$lab) {
                        return response()->json(['status' => 'error', 'message' => 'Laboratorio no encontrado']);
                    }
                    $rutaPDF = public_path("tmp/pdf/sin_firma/laboratorio_{$lab->id}.pdf");
                    //Log::info("Validando PDF de Laboratorio", [
                    //     "ruta" => $rutaPDF,
                    //     "existe" => file_exists($rutaPDF)
                    // ]);
                    if (!file_exists($rutaPDF) || !is_readable($rutaPDF)) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'PDF de Laboratorio no existe, no se actualiza'
                        ]);
                    }
                    if ($lab->flag == 0 || $lab->flag == 2){
                        $lab->fecha_firma = date("Y-m-d H:i:s");
                        $lab->ruta_laboratorio = date("Y/m/d") . "/cf_laboratorio_" . $lab->id . ".pdf";
                        $lab->flag = 1;
                        $lab->flag_docsinfirmar = 1;
                        $lab->save();
                        //Log::info('Laboratorio actualizada (PDF encontrado)...', [
                        //     "id" => $lab->id,
                        //     "pdf" => $lab->ruta_laboratorio
                        // ]);
                    }else{
                        return response()->json(['status' => 'omitido', 'message' => 'Laboratorio ya firmado']);
                    }
                    break;
                

                // Radiología
                case "radiologia":
                    $rad = \App\Models\Radiologia::where('id', $request->id_principal)->where('id_prestacion', $id)->first();
                    if (!$rad) {
                        return response()->json(['status' => 'error', 'message' => 'Radiología no encontrada']);
                    }
                    $rutaPDF = public_path("tmp/pdf/sin_firma/radiologia_{$rad->id}.pdf");
                    //Log::info("Validando PDF de radiología", [
                    //     "ruta" => $rutaPDF,
                    //     "existe" => file_exists($rutaPDF)
                    // ]);
                    if (!file_exists($rutaPDF) || !is_readable($rutaPDF)) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'PDF de radiología no existe, no se actualiza'
                        ]);
                    }

                    if ($rad->flag == 0 || $rad->flag == 2) {
                        $rad->fecha_firma = date("Y-m-d H:i:s");
                        $rad->ruta_radiologia = date("Y/m/d") . "/cf_radiologia_" . $rad->id . ".pdf";
                        $rad->flag = 1;
                        $rad->flag_docsinfirmar = 1;
                        $rad->save();
                        //Log::info('radiología actualizada (PDF encontrado)...', [
                        //     "id" => $rad->id,
                        //     "pdf" => $rad->ruta_radiologia
                        // ]);
                    }else{
                        return response()->json(['status' => 'omitido', 'message' => 'Radiología ya firmada']);
                    }
                    break;
               
                //Interconsulta
                case "interconsulta":

                    $itc = \App\Models\Interconsulta::where('id', $request->id_principal)
                        ->where('id_prestacion', $id)
                        ->first();

                    if (!$itc) {
                        return response()->json(['status' => 'error', 'message' => 'interconsulta no encontrada']);
                    }

                    $rutaPDF = public_path("tmp/pdf/sin_firma/interconsulta_{$itc->id}.pdf");

                    //Log::info("Validando PDF de interconsulta", [
                    //     "ruta" => $rutaPDF,
                    //     "existe" => file_exists($rutaPDF)
                    // ]);

                    if (!file_exists($rutaPDF) || !is_readable($rutaPDF)) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'PDF de interconsulta no existe, no se actualiza'
                        ]);
                    }

                    if ($itc->flag == 0 || $itc->flag == 2) {

                        $itc->fecha_firma_interconsulta = date("Y-m-d H:i:s");
                        $itc->ruta_interconsulta = date("Y/m/d") . "/cf_interconsulta_" . $itc->id . ".pdf";
                        $itc->flag = 1;
                        $itc->flag_docsinfirmar = 1;
                        $itc->save();

                        //Log::info('interconsulta actualizada (PDF encontrado)...', [
                        //     "id" => $itc->id,
                        //     "pdf" => $itc->ruta_interconsulta
                        // ]);

                    } else {

                        return response()->json([
                            'status' => 'omitido',
                            'message' => 'interconsulta ya firmada'
                        ]);

                    }

                    break;

                //Interconsulta_rpta
                case "interconsulta_rpta":

                    $itc = \App\Models\Interconsulta::where('id', $request->id_principal)->where('id_prestacion', $id)->first();

                    if (!$itc) {
                        return response()->json(['status' => 'error', 'message' => 'interconsulta_rpta no encontrada']);
                    }

                    $rutaPDF = public_path("tmp/pdf/sin_firma/interconsulta_rpta_{$itc->id}.pdf");

                    Log::info("Validando PDF de interconsulta_rpta", [
                        "ruta" => $rutaPDF,
                        "existe" => file_exists($rutaPDF)
                    ]);

                    if (!file_exists($rutaPDF) || !is_readable($rutaPDF)) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'PDF de interconsulta_rpta no existe, no se actualiza'
                        ]);
                    }

                    if ($itc->flag_rpta == 0 ||$itc->flag_rpta == 2) {

                        $itc->fecha_rpta_firma_interconsulta = date("Y-m-d H:i:s");
                        $itc->ruta_rpta_interconsulta = date("Y/m/d")."/cf_interconsulta_rpta_{$itc->id}.pdf";
                        $itc->flag_rpta = 1;
                        $itc->flag_docsinfirmar = 1;
                        $itc->save();

                        Log::info('interconsulta actualizada (PDF encontrado)...', [
                            "id" => $itc->id,
                            "pdf" => $itc->ruta_rpta_interconsulta
                        ]);

                    } else {

                        return response()->json([
                            'status' => 'omitido',
                            'message' => 'interconsulta_rpta ya firmada'
                        ]);

                    }

                    break;

                // Alta médica
                case "alta_medica":
                    //Log::info('entró a alta emergencia');
                    $alta = \App\Models\Emergencia::where('id', $request->id_principal)->first();
                    //Log::info('Alta médica encontrada', [
                    //     'id' => $alta->id,
                    //     'fecha_firma_egreso' => $alta->fecha_firma_egreso,
                    //     'id_tipo_egres_med' => $alta->id_tipo_egres_med
                    // ]);
                    if(!$alta){
                        return response()->json([
                            'status' => 'error',
                            'message' => 'No se encontró la emergencia'
                        ]);
                    }

                    // Alta médica
                    if(intval($alta->flag_firma_egreso) != 1){
                        //Log::info('actualizando BD de alta médica');
                        $alta->fecha_firma_egreso = date("Y-m-d H:i:s");
                        $alta->ruta_egreso = date("Y/m/d")."/cf_alta_emergencia_".$alta->id.".pdf";
                        $alta->flag_firma_egreso = 1;
                        $alta->flag_docsinfirmar = 1;
                        $alta->save();
                    }

                    // Indicaciones 
                    if($alta->id_tipo_egres_med == 2){
                        //Log::info('actualizando BD de indicaciones');
                        $alta->ruta_indicaciones = date("Y/m/d")."/cf_emergencia_indicaciones_alta_".$alta->id.".pdf";
                        $alta->flag_docsinfirmar = 1;
                        $alta->save();
                        $orden = HospitalizacionOrden::where('id_ext', $alta->id)
                                    ->where('id_estado_reg', 1)
                                    ->first();
                        if(!$orden){
                            Log::warning('No se encontró orden de hospitalización', ['id_ext' => $alta->id]);
                        }

                        if($orden){
                            $orden->fecha_firma = date("Y-m-d H:i:s");
                            $orden->ruta_orden = date("Y/m/d")."/cf_emergencia_indicaciones_alta_".$alta->id.".pdf";
                            $orden->flag_firma = 1;
                            $orden->flag_docsinfirmar = 1;
                            $orden->save();
                        }
                    }

                    // Epicrisis 
                    if($alta->id_tipo_egres_med == 4){
                        //Log::info('actualizando BD de epicrisis');
                        $alta->ruta_epicrisis = date("Y/m/d")."/cf_emergencia_epicrisis_".$alta->id.".pdf";
                        $alta->flag_docsinfirmar = 1;
                        $alta->save();
                    }
                    break;

                // Alta hospitalización
                case "alta_hospitalizacion":
                    //Log::info('entró a alta médica hospitalizacion');
                    $alta_pac = \App\Models\Hospitalizacione::where('id', $request->id_principal)->first();
                    //Log::info('Alta médica hospitalización encontrada', [
                    //     'id' => $alta_pac->id
                    // ]);
                    if(!$alta_pac){
                        return response()->json([
                            'status' => 'error',
                            'message' => 'No se encontró la emergencia'
                        ]);
                    }

                    // Alta médica hospitalización
                    if(!$alta_pac->fecha_firma_alta_medica){
                        //Log::info('actualizando BD de alta médica');
                        $alta_pac->fecha_firma_alta_medica = date("d-m-Y H:i:s");
                        $alta_pac->ruta_alta_medica = date("Y/m/d")."/cf_alta-hospitalizacion_".$alta_pac->id.".pdf";
                        $alta_pac->ruta_epicrisis = date("Y/m/d")."/cf_hospitalizacion-epicrisis_".$alta_pac->id.".pdf";
                        $alta_pac->ruta_papeleta_egreso = date("Y/m/d")."/cf_hospitalizacion-papeleta-egreso_".$alta_pac->id.".pdf";
                        $alta_pac->flag_alta_medica = 1;
                        $alta_pac->flag_docsinfirmar = 1;
                        $alta_pac->save();
                    }
                    break;
                
                // INFORME RADIOLÓGICO 
                case "imagenologia":
                    //Log::info('entró a informe radiológico');
                    $prest_proc = Prestacion_procedimiento::where('prestacion_procedimientos.id',$request->id_principal)->first();
                    if (!$prest_proc) {
                        return response()->json(['status' => 'error', 'message' => 'informe radiológico no encontrado']);
                    }

                    if ($prest_proc->flag == 0) {
                        $prest_proc->fecha_firma = date("Y-m-d H:i:s");
                        $prest_proc->ruta_prestacion_procedimiento = date("Y/m/d") . "/cf_informe-imagenologia_" . $prest_proc->id . ".pdf";
                        $prest_proc->flag = 1;
                        $prest_proc->flag_docsinfirmar = 1;
                        $prest_proc->save();
                        //Log::info('informe radiológico actualizado (PDF encontrado)...', [
                        //     "id" => $prest_proc->id,
                        //     "pdf" => $prest_proc->ruta_prestacion_procedimiento
                        // ]);
                    }else{
                        return response()->json(['status' => 'omitido', 'message' => 'informe radiológico ya firmada']);
                    }
                    break;

                // INFORME RADIOLÓGICO ADICIONAL
                case "imagenologia_adicional":
                    //Log::info('entró a informe radiológico adicional');
                    //Log::info($request->id_principal);
                    $ira = DB::table('informes_radiologias_adicionales')->join('prestacion_procedimientos as pp', 'pp.id', '=', 'informes_radiologias_adicionales.id_prestacion_procedimiento')->where('informes_radiologias_adicionales.id', $request->id_principal)->select('informes_radiologias_adicionales.*')->first();
                    //Log::info(json_encode($ira));
                    if (!$ira) {
                        return response()->json(['status' => 'error', 'message' => 'informe radiológico no encontrado']);
                    }
                    if ($ira->flag == 0) {
                        //Log::info('entró a ira->flag=0');
                        DB::table('informes_radiologias_adicionales')
                            ->where('id', $ira->id)
                            ->update([
                                'validated_us' => Auth::user()->id,
                                'validated_at' => Carbon::now(),
                                'flag_validated' => 1,
                                'fecha_firma' => Carbon::now(),
                                'ruta_informe_radiologia_adicional' => date("Y/m/d") . "/cf_informe-imagenologia-adicional_" . $ira->id . ".pdf",
                                'flag' => 1,
                                'flag_docsinfirmar' => 1,
                            ]);
                    }else{
                        return response()->json(['status' => 'omitido', 'message' => 'informe radiológico ya firmada']);
                    }
                    break;
                default:
                    return response()->json(['status' => 'error', 'message' => 'Tipo desconocido'], 400);
            }

            return response()->json(['status' => 'ok', 'message' => 'Documento firmado correctamente']);

        } catch (\Exception $e) {
            \Log::error("Error en actualiza_prestacion_mod_docsinfirmar: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Ocurrió un error al actualizar la prestación',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function verificar_firma_doc($tipo, $id, $id_principal){
        try {
            //Log::info("verificar_firma_doc -> Recibido", [
            //     'tipo' => $tipo,
            //     'id_prestacion' => $id,
            //     'id_principal' => $id_principal
            // ]);
            if (ob_get_level()) {
                ob_end_clean();
            }
            $flag = 0;

            switch ($tipo) {
                case 'prestacion':
                case 'hospitalizacion':
                    $prestacion = DB::table('prestaciones')
                        ->select('flag')
                        ->where('id', $id)
                        ->first();

                    $flag = ($prestacion && $prestacion->flag == 1) ? 1 : 0;

                    //Log::info("verificar_firma_doc -> Prestación/Hospitalización", [
                    //     'flag' => $flag,
                    //     'documento' => $prestacion
                    // ]);
                    break;
                // Evolución
                case 'evolucion':
                case 'evaluacion':
                    $evolucion = DB::table('prestaciones as p')
                        ->join('evaluaciones as ev', 'ev.id_prestacion', '=', 'p.id')
                        ->select('ev.flag')
                        ->where('p.id', $id)     
                        ->where('ev.id', $id_principal)    
                        ->first();

                    $flag = ($evolucion && $evolucion->flag == 1) ? 1 : 0;

                    //Log::info("verificar_firma_doc -> evolución", [
                    //     'flag' => $flag,
                    //     'documento' => $evolucion
                    // ]);
                    break;

                // Receta
                case 'receta':
                    $receta = \App\Models\Receta_vale::where('id', $id_principal)
                        ->where('id_prestacion', $id)
                        ->first();

                    $flag = ($receta && $receta->flag == 1) ? 1 : 0;

                    //Log::info("verificar_firma_doc -> Receta", [
                    //     'flag' => $flag,
                    //     'documento' => $receta
                    // ]);
                    break;

                // Laboratorio
                case 'laboratorio':
                    $laboratorio = \App\Models\Laboratorio::where('id', $id_principal)
                        ->where('id_prestacion', $id)
                        ->first();

                    $flag = ($laboratorio && $laboratorio->flag == 1) ? 1 : 0;

                    //Log::info("verificar_firma_doc -> Laboratorio", [
                    //     'flag' => $flag,
                    //     'documento' => $laboratorio
                    // ]);
                    break;

                // Radiología
                case 'radiologia':
                    $radiologia = \App\Models\Radiologia::where('id', $id_principal)
                        ->where('id_prestacion', $id)
                        ->first();

                    $flag = ($radiologia && $radiologia->flag == 1) ? 1 : 0;

                    //Log::info("verificar_firma_doc -> Radiología", [
                    //     'flag' => $flag,
                    //     'documento' => $radiologia
                    // ]);
                    break;
                
                // Interconsulta
                case 'interconsulta':
                    $interconsulta = \App\Models\Interconsulta::where('id', $id_principal)
                        ->where('id_prestacion', $id)
                        ->first();

                    $flag = ($interconsulta && $interconsulta->flag == 1) ? 1 : 0;

                    //Log::info("verificar_firma_doc -> interconsulta", [
                    //     'flag' => $flag,
                    //     'documento' => $interconsulta
                    // ]);
                    break;

                // Interconsulta-respuesta
                case 'interconsulta_rpta':
                    $interconsulta_rpta = \App\Models\Interconsulta::where('id', $id_principal)
                        ->where('id_prestacion', $id)
                        ->first();

                    $flag_rpta = ($interconsulta_rpta && $interconsulta_rpta->flag_rpta == 1) ? 1 : 0;

                    \Log::info("verificar_firma_doc -> interconsulta_rpta", [
                        'flag_rtpa' => $flag_rpta,
                        'documento' => $interconsulta_rpta
                    ]);
                    break;

                // Alta Emergencia
                case 'alta_medica':
                    $alta_emg = \App\Models\Emergencia::where('id', $id_principal)->first();
                    $flag = ($alta_emg && $alta_emg->flag_egreso == 1 && $alta_emg->flag_firma_egreso != 1) ? 0 : 1;
                    //Log::info("verificar_firma_doc -> alta_emg", [
                    //     'flag' => $flag,
                    //     'documento' => $alta_emg
                    // ]);
                    break;

                default:
                    $flag = 0;
                    \Log::warning("verificar_firma_doc -> Tipo desconocido", [
                        'tipo' => $tipo
                    ]);
                    break;
            }
            //Log::info('Salida JSON', ['flag' => $flag]);

            //Log::info("verificar_firma_doc -> Flag enviado al frontend", ['flag' => $flag]);

            return response()->json(['flag' => $flag]);

        } catch (\Exception $e) {
            \Log::error("Error en verificar_firma_doc: " . $e->getMessage(), [
                'tipo' => $tipo,
                'id' => $id,
                'id_principal' => $id_principal
            ]);
            return response()->json(['flag' => 0]);
        }
    }
    public function verificar_firma_evol($idEmergencia,$idtipoat) {
        if($idtipoat === '2'){
            $flag = DB::table('evaluaciones as ev')
                ->join('emergencias as e', 'e.id', '=', 'ev.id_emergencia')
                ->where('ev.id_emergencia', $idEmergencia)
                ->value('ev.flag'); 
        }else if($idtipoat === '3'){
            $flag = DB::table('evaluaciones as ev')
                ->join('hospitalizaciones as h', 'h.id', '=', 'ev.id_emergencia')
                ->where('ev.id_emergencia', $idEmergencia)
                ->value('ev.flag'); 
        }
        //Log::info("flag ev:", ['flag' => $flag]);

        return response()->json([
            'flag_evolucion' => $flag
        ]);

    }

    public function firmado_evol_id($id_evol) {
        $flag = DB::table('evaluaciones as ev')
            ->where('ev.id', $id_evol)
            ->value('ev.flag'); 

        //Log::info("flag ev:", ['flag' => $flag]);

        return response()->json([
            'flag_evolucion' => $flag
        ]);
    }

    public function firmado_interconsulta_rpta_id($id_interconsulta) {
        $interconsulta = DB::table('interconsultas as i')
            ->where('i.id', $id_interconsulta)
            ->select('i.flag', 'i.flag_rpta')
            ->first();

        $flag = $interconsulta ? $interconsulta->flag_rpta : null;
        $flagInterconsulta = $interconsulta ? $interconsulta->flag : null;

        \Log::info("flag interconsulta rpta:", ['flag_rpta' => $flag, 'flag_interconsulta' => $flagInterconsulta]);

        return response()->json([
            'flag_interconsulta' => $flagInterconsulta,
            'flag_interconsulta_rpta' => $flag
        ]);
    }

    public function datos_regreso_interconsulta_rpta($id_interconsulta) {
        $interconsulta = DB::table('interconsultas as i')
            ->join('prestaciones as p', 'i.id_prestacion', '=', 'p.id')
            ->where('i.id', $id_interconsulta)
            ->select('i.id', 'i.id_prestacion', 'i.id_evaluacion', 'p.id_tipo_atencion')
            ->first();

        if (!$interconsulta) {
            return response()->json(['ok' => false]);
        }

        return response()->json([
            'ok'               => true,
            'id_interconsulta' => $interconsulta->id,
            'id_prestacion'    => $interconsulta->id_prestacion,
            'id_evaluacion'    => $interconsulta->id_evaluacion ? $interconsulta->id_evaluacion : 0,
            'id_tipo_atencion' => $interconsulta->id_tipo_atencion
        ]);
    }

    public function es_quirurgico_docsinfirmar($id_prestacion){
        $esquirurgico = DB::table('prestaciones as p')
            ->where('p.id', $id_prestacion)
            ->value('p.flag_intervencion_quirurgica'); 

        //Log::info("flag intervencion quirurgica:", ['esquirurgico' => $esquirurgico]);

        return response()->json([
            'esquirurgico' => $esquirurgico
        ]);
    }
    //================================= LISTAR FIRMAS PENDIENTES USUARRIOS CON ACCESO ===================================
    public function listar_firm_pend_ajax(Request $request){
        //dd($request->all());
        // Obtener los parámetros de la petición
        $id_establecimiento = Auth::user()->id_establecimiento;
        $id_user        = Auth::user()->id;
        $fecha_ini      = isset($request->fecha_ini) && $request->fecha_ini != '' ? $request->fecha_ini : '2000-01-01';
        $fecha_fin      = isset($request->fecha_fin) && $request->fecha_fin != '' ? $request->fecha_fin : date('Y-m-d');
        $division       = isset($request->division) && $request->division != '' ? $request->division : null;
        $departamento   = isset($request->departamento) && $request->departamento != '' ? $request->departamento : null;
        $user_medico   = isset($request->prof_salud) && $request->prof_salud != '' ? $request->prof_salud : null;
        //$dni_beneficiario = isset($request->dni_beneficiario) && $request->dni_beneficiario != '' ? $request->dni_beneficiario : null;
        //$estado = isset($request->estado) ? $request->estado : 1;
        $pagina = isset($request->NumeroPagina) ? $request->NumeroPagina : 1;
        $limit = isset($request->NumeroRegistros) ? $request->NumeroRegistros : 20;
        $p = [
            $id_establecimiento,
            $fecha_ini,
            $fecha_fin,
            $division,
            $departamento,
            $user_medico,
            $pagina,
            $limit
        ];

        $model_docsinfirmar = new Docsinfirmar();
        $data = $model_docsinfirmar->getDocsfirmasPend($p);

        $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : count($data);

        $result = [
            "PageStart" => $pagina,
            "pageSize" => $limit,
            "SearchText" => "",
            "ShowChildren" => true,
            "iTotalRecords" => $iTotalDisplayRecords,
            "iTotalDisplayRecords" => $iTotalDisplayRecords,
            "aaData" => $data
        ];
        return response()->json($result);
    }

    public function listar_firm_pend_enfermeria_ajax(Request $request){
        $id_establecimiento = Auth::user()->id_establecimiento;
        $fecha_ini      = isset($request->fecha_ini) && $request->fecha_ini != '' ? $request->fecha_ini : '2000-01-01';
        $fecha_fin      = isset($request->fecha_fin) && $request->fecha_fin != '' ? $request->fecha_fin : date('Y-m-d');
        $division       = isset($request->division) && $request->division != '' ? $request->division : null;
        $departamento   = isset($request->departamento) && $request->departamento != '' ? $request->departamento : null;
        $user_medico    = isset($request->prof_salud) && $request->prof_salud != '' ? $request->prof_salud : null;
        $pagina = isset($request->NumeroPagina) ? $request->NumeroPagina : 1;
        $limit = isset($request->NumeroRegistros) ? $request->NumeroRegistros : 20;
        $p = [
            $id_establecimiento,
            $fecha_ini,
            $fecha_fin,
            $division,
            $departamento,
            $user_medico,
            $pagina,
            $limit
        ];

        $model_docsinfirmar = new Docsinfirmar();
        $data = $model_docsinfirmar->getFirmasPendEnfermeria($p);

        $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : count($data);

        $result = [
            "PageStart" => $pagina,
            "pageSize" => $limit,
            "SearchText" => "",
            "ShowChildren" => true,
            "iTotalRecords" => $iTotalDisplayRecords,
            "iTotalDisplayRecords" => $iTotalDisplayRecords,
            "aaData" => $data
        ];
        return response()->json($result);
    }
    
    public function ver_pdf_prestacion($id_prestacion) { 
    
        $path = public_path('tmp/pdf');
        if (!is_dir($path)) {
            mkdir($path);
        }
        
        $path = public_path('tmp/pdf/sin_firma/');
        if (!is_dir($path)) {
            mkdir($path);
        }
        
        $model_prestacion = new \App\Models\Prestaciones;

        $prestacion = $model_prestacion->GetPrestacionById_2($id_prestacion);
		//$diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
		$procedimiento = $model_prestacion->GetProcedimientoByIdPrestacionNew($id_prestacion);
        $ordenProcedimiento = null;
        $detallesAgrupados = null;
        /*
        
        $model_solicitud_pp = new \App\Models\SolicitudProgramacionProcedimientos;
        $ordenProcedimiento = $model_solicitud_pp->GetOrdenByIdPrestacion($id_prestacion);
        $solicitudPp = null;
        $detallesAgrupados = null;
        if ($ordenProcedimiento) {
            $solicitudPp = \App\Models\SolicitudProgramacionProcedimientos::with([
                'prestacion' => function ($query) {
                    $query->select('prestaciones.id', 'prestaciones.id_establecimiento', 'prestaciones.id_asegurado', 'prestaciones.updated_at', 'prestaciones.id_tipo_atencion', 'prestaciones.id_sub_consultorio', 'prestaciones.id_medico')
                    ->with(['establecimiento' => function ($q) {
                        $q->select('establecimientos.id', 'establecimientos.nombre');
                    }]);
                }
            ])->with("detalles.procedimiento")->where("id", $ordenProcedimiento->id)->first();
            $detallesAgrupados = $solicitudPp->detalles->groupBy('id_procedimiento');
        }*/

        $cups_pnp = DB::table('prestaciones as p')
                    ->select(DB::raw("
                        CASE
                            WHEN p.id_tipo_atencion = '2' THEN e.cups_pnp
                            WHEN p.id_tipo_atencion = '3' AND (h.cups_pnp IS NOT NULL OR h.cups_pnp != '') THEN h.cups_pnp
                            ELSE p.cups_pnp
                        END as cups,
                        p.id_tipo_atencion as tipo_atencion
                    "))
                    ->leftJoin('emergencias as e', function ($join) {
                        $join->on('p.id_ext', '=', 'e.id')
                            ->where('p.id_tipo_atencion', '=', '2');
                    })
                    ->leftJoin('hospitalizaciones as h', function ($join) {
                        $join->on('p.id_ext', '=', 'h.id')
                            ->where('p.id_tipo_atencion', '=', '3');
                    })
                    ->where('p.id', '=', $id_prestacion)
                    ->first();

        // Si es tipo 2 o 3 pero sin id_ext, tratarlo como tipo 1
        $tipo_atencion = $prestacion->id_tipo_atencion;
        if (($tipo_atencion == 2 || $tipo_atencion == 3) && is_null($prestacion->id_ext)) {
            $tipo_atencion = 1;
        }

        switch ($tipo_atencion) {
            case 1:
                //TODO: IMPLEMENTAR AQUI
                $informe_html = null;
                $emergencia_triaje = null;
                /*if ($prestacion->informe_pp) {
                    $informe_html = $prestacion->informe_pp;
                }*/
                $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
                $carpeta = date("Y")."/".date("m")."/".date("d");
                $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_prestacion_".$id_prestacion.".pdf";
                $codigo_qr = QrCode::format('png')->size(150)->generate("https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_prestacion_".$id_prestacion.".pdf");
                $emergencia_triaje = null;
                $pdf = \PDF::loadView('pdf.firmar_prestacion',compact('emergencia_triaje','ordenProcedimiento','detallesAgrupados','informe_html', 'natis', 'farmacia','prestacion','diagnostico','procedimiento','medicamentos','codigo_qr','url_descarga','cups_pnp'));
                $pdf->getDomPDF()->set_option("enable_php", true);
                break;
            case 7:
                //TODO: IMPLEMENTAR AQUI
                $informe_html = null;
                /*if ($prestacion->informe_pp) {
                    $informe_html = $prestacion->informe_pp;
                }*/
                $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
                $carpeta = date("Y")."/".date("m")."/".date("d");
                $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_prestacion_".$id_prestacion.".pdf";
                $codigo_qr = QrCode::format('png')->size(150)->generate("https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_prestacion_".$id_prestacion.".pdf");
                $emergencia_triaje = null;
                $pdf = \PDF::loadView('pdf.firmar_prestacion',compact('emergencia_triaje','ordenProcedimiento','detallesAgrupados','informe_html', 'natis', 'farmacia','prestacion','diagnostico','procedimiento','medicamentos','codigo_qr','url_descarga','cups_pnp'));
                $pdf->getDomPDF()->set_option("enable_php", true);
                break;

            case 2:
                $informe_html = null;
                $datoadicional= null;
                $puerperio = null;
                $emergencia_triaje = null;
                $eventosMarcados = Prestacion_eventos::where('id_prestacion', $id_prestacion)
                    ->where('estado', 1)
                    ->lists('id_evento')
                    ->toArray();
                if ($prestacion->id_establecimiento==76 || $prestacion->id_establecimiento==77 || $prestacion->id_establecimiento==26 || $prestacion->id_establecimiento==75):
                    $model_emergencia = new \App\Models\Emergencia;
                    $obtenerdato = $model_emergencia->GetObtenerServicio($prestacion->id_ext);
                    $emergencia_triaje = $model_emergencia->obtenerReporteSaludMaterna($prestacion->id_ext);
                    //para el foreach de neonatos
                    $emergencia = Emergencia::find($prestacion->id_ext);
                    $natis = null;
                    if (isset($emergencia->id_puerperio)) {
                        $puerperio = Puerperio::find($emergencia->id_puerperio);
                        if ($puerperio) {
                            $natis = $puerperio->natis;
                        }
                    }
                    if($obtenerdato->id_prioridad!=4):
                        $datoadicional = $model_emergencia->GetObtenerCama($obtenerdato->cama);
                        $prestacion->servicio= $obtenerdato->servicio;
                        $prestacion->consultorio= $obtenerdato->consultorio;
                        $prestacion->piso= $datoadicional->piso;
                        $prestacion->pabellon= $datoadicional->pabellon;
                        $prestacion->cuarto= $datoadicional->cuarto;
                        $prestacion->cama= $datoadicional->cama;
                    else:
                        $prestacion->servicio="IV - PATOLOGÍA AGUDA COMÚN";
                        $prestacion->consultorio="CONSULTORIO DE ATENCIÓN RÁPIDA (CAR)";
                        $prestacion->piso= "";
                        $prestacion->pabellon= "";
                        $prestacion->cuarto= "";
                        $prestacion->cama= "";
                    endif;
                    $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacionEmergencia($id_prestacion);
                else:
                    $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
                endif;
                $carpeta = date("Y")."/".date("m")."/".date("d");
                $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_prestacion_".$id_prestacion.".pdf";
                $codigo_qr = QrCode::format('png')->size(150)->generate("https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_prestacion_".$id_prestacion.".pdf");

                $pdf = \PDF::loadView('pdf.firmar_prestacion',compact('emergencia_triaje','ordenProcedimiento','detallesAgrupados','natis', 'farmacia','eventosMarcados','prestacion','diagnostico','procedimiento','medicamentos','codigo_qr','url_descarga','cups_pnp','informe_html'));
                $pdf->getDomPDF()->set_option("enable_php", true);
                break;
            case 3:
                $informe_html = null;
                if ($prestacion->id_establecimiento==76):
                    $hospitalizacion = DB::table('hospitalizaciones')
                    ->join('diagnosticos', 'hospitalizaciones.id_diagnostico_inicial','=','diagnosticos.id')
                    ->join('camas','hospitalizaciones.id_cama_estado','=','camas.id')
                    ->join('cuartos','camas.id_cuarto','=','cuartos.id')
                    ->join('asignacion_servicios','cuartos.id_asignacion_servicio','=','asignacion_servicios.id')
                    ->join('consultorios','asignacion_servicios.id_consultorio','=','consultorios.id')
                    ->select('hospitalizaciones.*','diagnosticos.codigo as codigo_cie','camas.nombre as cama','consultorios.nombre as servicio_sala')
                    ->where('hospitalizaciones.id',$prestacion->id_ext)
                    ->first();

                    $prestacion = DB::table('prestaciones')
                        ->join('medicos as m', 'prestaciones.id_medico', '=', 'm.id')
                        ->leftJoin('profesiones as p', 'm.id_profesion', '=', 'p.id')
                        ->select('prestaciones.*','m.paterno as profesional_paterno', 'm.materno as profesional_materno', 'm.nombre as profesional_nombre', 'm.colegiatura as profesional_colegiatura','p.nombre as profesional_profesion')
                        ->where('prestaciones.id_ext', $prestacion->id_ext)
                        ->where('prestaciones.id_tipo_atencion', 3)
                        ->where('prestaciones.id_estado_reg', 1)
                        ->first();

                    $atencion = DB::table('hospitalizacion_atenciones')
                        ->where('id_prestacion', $prestacion->id)
                        ->first();

                    $recetas = DB::table('receta_vales')
                        ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
                        ->select('receta_vales.*', 'farmacias.nombre as farmacia')
                        ->where('receta_vales.id_prestacion', '=', $prestacion->id)
                        ->where('receta_vales.estado', '<>', 0)
                        ->where('receta_vales.id_evaluacion', '=', 0)
                        ->where('receta_vales.id_interconsulta', '=', 0)
                        ->orderBy('receta_vales.id', 'asc')
                        ->get();

                    $receta_productos = DB::table('receta_vales')
                        ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                        ->join('productos', 'producto_recetas.id_producto', '=', 'productos.id')
                        ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
                        ->leftJoin('farmacias', 'producto_recetas.id_farmacia', '=', 'farmacias.id')
                        ->leftJoin('via_administracion', 'producto_recetas.id_via_administracion', '=', 'via_administracion.id')
                        ->where('receta_vales.id_prestacion', $prestacion->id)
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

                    $ordenes_laboratorio = DB::table('laboratorios')
                        ->where('id_prestacion', $prestacion->id)
                        ->where('estado', '<>', 0)
                        ->where('id_evaluacion', 0)
                        ->select(
                            'id',
                            'fecha_atencion',
                            'fecha_firma',
                            'ruta_laboratorio',
                            'flag',
                            'secuencial',
                            'fecha_registro_toma_muestra',
                            'estado',
                            DB::raw("to_char(fecha_registro_toma_muestra, 'DD/MM/YYYY') AS fecha_toma_muestra")
                        )
                        ->orderBy('id', 'asc')
                        ->get();

                    $orden_lab_examenes = DB::table('prestacion_procedimientos')
                        ->join('labnivelatenciones', 'prestacion_procedimientos.id_rua', '=', 'labnivelatenciones.id')
                        ->join('procedimientos', 'prestacion_procedimientos.id_procedimiento', '=', 'procedimientos.id')
                        ->leftJoin('diagnosticos', 'diagnosticos.id', '=', 'prestacion_procedimientos.id_diagnostico')
                        ->where('prestacion_procedimientos.id_prestacion', $prestacion->id)
                        ->where('prestacion_procedimientos.id_estado_reg', 1)
                        ->select(
                            'labnivelatenciones.id as id',
                            'prestacion_procedimientos.id_lab_rad as id_orden',
                            'labnivelatenciones.codigo_lab as codigo',
                            'labnivelatenciones.descripcion_corta as nombre',
                            'procedimientos.descripcion as nombre_completo',
                            'prestacion_procedimientos.cantidad',
                            'prestacion_procedimientos.created_at',
                            'labnivelatenciones.id_procedimiento',
                            'prestacion_procedimientos.observacion',
                            'diagnosticos.codigo as codigo_diagnostico',
                            'diagnosticos.nombre as diagnostico',
                            'prestacion_procedimientos.tipo_muestra_microbiologia',
                            'prestacion_procedimientos.id_solicitud_procedimiento'
                        )
                        ->orderBy('prestacion_procedimientos.id', 'asc')
                        ->get();

                    $ordenes_imagenologia = DB::table('radiologias')
                        ->where('id_prestacion', '=', $prestacion->id)
                        ->where('estado', '<>', 0)
                        ->where('id_evaluacion', '=', 0)
                        ->orderBy('id','asc')
                        ->get();

                    $orden_img_examenes = DB::table('prestacion_procedimientos as t1')
                        ->join('procedimientos as t2', 't1.id_procedimiento', '=', 't2.id')
                        ->leftJoin('diagnosticos as t3', 't1.id_diagnostico', '=', 't3.id')
                        ->where('t1.id_prestacion', $prestacion->id)
                        ->where('t1.tipo_procedimiento', 3)
                        ->where('t1.id_estado_reg', 1)
                        ->select(
                            't1.id',
                            't1.id_lab_rad as id_orden',
                            't2.codigo',
                            't2.descripcion as nombre',
                            't1.cantidad',
                            't1.observacion',
                            't3.nombre as diagnostico',
                            't3.codigo as cod_diag',
                            't1.comparativo_radio',
                            't1.lado_radio'
                        )
                        ->orderBy('t1.id')
                        ->get();

                    $interconsultas = DB::table('interconsultas as t8')
                        ->join('establecimientos as t3', 't8.id_establecimiento', '=', 't3.id')
                        ->join('consultorios as t6', 't8.id_servicio', '=', 't6.id')
                        ->join('medicos as tr', 't8.personal_salud_interconsulta', '=', 'tr.id')
                        ->where('t8.id_prestacion', $prestacion->id)
                        ->where('t8.id_evaluacion', 0)
                        ->where('t8.id_estado_reg', 1)
                        ->select(
                            't8.id',
                            't3.nombre as establecimiento',
                            't8.id_servicio',
                            't6.nombre as servicio',
                            'tr.nombre as profesional_nombre',
                            'tr.paterno as profesional_paterno',
                            'tr.materno as profesional_materno',
                            'tr.colegiatura as profesional_colegiatura',
                            't8.codigo_upss as codigo_interconsulta',
                            't8.motivo_interconsulta',
                            't8.resumen_interconsulta',
                            't8.fecha_firma_interconsulta',
                            't8.id as id_interconsulta',
                            't8.created_at'
                        )
                        ->orderBy('t8.created_at', 'ASC')
                        ->get();


                    $diagnosticos = DB::table('receta_diagnosticos as rd')
                        ->join('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
                        ->select('rd.*', 'd.codigo as cod_dx', 'd.nombre as nombre_dx')
                        ->where('rd.id_prestacion', $prestacion->id)
                        ->where(function ($sql) {
                            $sql->whereNull('rd.id_evaluacion')
                                ->orWhere('rd.id_evaluacion', 0);
                        })
                        //->whereNull('rd.id_receta_vale')
                        ->orderBy('rd.id')
                        ->orderBy('rd.id_categoria')
                        ->orderBy('rd.id_tipo_diagnostico')
                        ->get();

                    $procedimientos = DB::table('prestacion_procedimientos as pp')
                        ->join('procedimientos as p', 'pp.id_procedimiento', '=', 'p.id')
                        ->select('pp.id', 'pp.cantidad', 'p.codigo as cod_cpms', 'p.descripcion as descripcion_cpms')
                        ->where('pp.id_prestacion', $prestacion->id)
                        ->where('pp.tipo_procedimiento', 1)
                        ->where('pp.id_estado_reg', 1)
                        ->orderBy('pp.id')
                        ->get();

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
                        ->where('asegurados.id',$prestacion->id_asegurado)
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
                    // TODO PDF EP
                    $eventosMarcados = Prestacion_eventos::where('id_prestacion', $id_prestacion)
                    ->where('estado', 1)
                    ->lists('id_evento')
                    ->toArray();
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
                    $carpeta = date("Y")."/".date("m")."/".date("d");
                    $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_prestacion_".$id_prestacion.".pdf";
                    $codigo_qr = QrCode::format('png')->size(150)->generate("https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_prestacion_".$id_prestacion.".pdf");

                    $model_establecimiento = new Establecimiento;
                    $establecimiento = $model_establecimiento->GetById(Auth::user()->id_establecimiento);
                    $nombre_establecimiento = $establecimiento[0]->nombre;

                    $pdf = \PDF::loadView('pdf.hospitalizacion_atencion', compact('hospitalizacion','ordenProcedimiento','detallesAgrupados','prestacion','eventosMarcados','obj_hospitalizacion_atencion','atencion','asegurado','titular','persona_datos_adicionales','grado','diagnosticos','procedimientos','recetas','receta_productos','ordenes_laboratorio','orden_lab_examenes','ordenes_imagenologia','orden_img_examenes','interconsultas','cups_pnp','nombre_establecimiento'));
                    $pdf->getDomPDF()->set_option("enable_php", true);
                else:
                    $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
                    $carpeta = date("Y")."/".date("m")."/".date("d");
                    $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_prestacion_".$id_prestacion.".pdf";
                    $codigo_qr = QrCode::format('png')->size(150)->generate("https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_prestacion_".$id_prestacion.".pdf");

                    $pdf = \PDF::loadView('pdf.firmar_prestacion',compact('emergencia_triaje', 'ordenProcedimiento','detallesAgrupados','natis', 'farmacia','prestacion','diagnostico','procedimiento','medicamentos','codigo_qr','url_descarga','cups_pnp','informe_html'));
                    $pdf->getDomPDF()->set_option("enable_php", true);
                endif;
                break;
        }
        //return $pdf->stream('receta_vale.pdf');
        //guardamos el PDF en el path tmp/pdf/sin_firma/prestacion_123456.pdf
        file_put_contents($path."prestacion_".$id_prestacion.".pdf", $pdf->output());

    }
    
    //================================= LISTAR ALTAS EMERGENCIA PENDIENTES  ===================================
    public function listar_altas_pendientes_ajax(Request $request){
        $id_establecimiento = Auth::user()->id_establecimiento;
        $id_user        = Auth::user()->id;
        $fecha_ini      = isset($request->fecha_ini) && $request->fecha_ini != '' ? $request->fecha_ini : '2000-01-01';
        $fecha_fin      = isset($request->fecha_fin) && $request->fecha_fin != '' ? $request->fecha_fin : date('Y-m-d');
        $division       = isset($request->division) && $request->division != '' ? $request->division : null;
        $departamento   = isset($request->departamento) && $request->departamento != '' ? $request->departamento : null;
        $user_medico   = isset($request->prof_salud) && $request->prof_salud != '' ? $request->prof_salud : null;
        $pagina = isset($request->NumeroPagina) ? $request->NumeroPagina : 1;
        $limit = isset($request->NumeroRegistros) ? $request->NumeroRegistros : 20;
        $p = [
            $id_establecimiento,
            $fecha_ini,
            $fecha_fin,
            $division,
            $departamento,
            $user_medico,
            $pagina,
            $limit
        ];

        $model_docsinfirmar = new Docsinfirmar();
        $data = $model_docsinfirmar->getAltasEmgPend($p);

        $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : count($data);

        $result = [
            "PageStart" => $pagina,
            "pageSize" => $limit,
            "SearchText" => "",
            "ShowChildren" => true,
            "iTotalRecords" => $iTotalDisplayRecords,
            "iTotalDisplayRecords" => $iTotalDisplayRecords,
            "aaData" => $data
        ];
        return response()->json($result);
    }
    
    //================================= LISTAR ALTAS Hospi PENDIENTES  ===================================
    public function listar_altas_pendientes_hsp_ajax(Request $request){
        $id_establecimiento = Auth::user()->id_establecimiento;
        $id_user        = Auth::user()->id;
        $fecha_ini      = isset($request->fecha_ini) && $request->fecha_ini != '' ? $request->fecha_ini : '2000-01-01';
        $fecha_fin      = isset($request->fecha_fin) && $request->fecha_fin != '' ? $request->fecha_fin : date('Y-m-d');
        $division       = isset($request->division) && $request->division != '' ? $request->division : null;
        $departamento   = isset($request->departamento) && $request->departamento != '' ? $request->departamento : null;
        $user_medico   = isset($request->prof_salud) && $request->prof_salud != '' ? $request->prof_salud : null;
        $pagina = isset($request->NumeroPagina) ? $request->NumeroPagina : 1;
        $limit = isset($request->NumeroRegistros) ? $request->NumeroRegistros : 20;
        $p = [
            $id_establecimiento,
            $fecha_ini,
            $fecha_fin,
            $division,
            $departamento,
            $user_medico,
            $pagina,
            $limit
        ];

        $model_docsinfirmar = new Docsinfirmar();
        $data = $model_docsinfirmar->getAltasHspgPend($p);

        $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : count($data);

        $result = [
            "PageStart" => $pagina,
            "pageSize" => $limit,
            "SearchText" => "",
            "ShowChildren" => true,
            "iTotalRecords" => $iTotalDisplayRecords,
            "iTotalDisplayRecords" => $iTotalDisplayRecords,
            "aaData" => $data
        ];
        return response()->json($result);
    }  

}