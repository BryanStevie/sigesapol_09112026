<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Kardexe;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\caracterFisico;
use App\Models\Emergencia;
use App\Models\Enfermeria;
use App\Models\Monitoreo;
use App\Models\Monitoreo_detalles;
use App\Models\Nota_enfermeria;
use App\Models\Prestaciones;
use App\Models\Receta_vale;
use App\Models\Turno;
use App\Models\valoracionesEnfermerias;
use Archive7z\Archive7z;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use setasign\Fpdi\Fpdi;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class MonitoreoEnfermeriaController extends Controller
{
    function __construct()
    {
        $this->middleware('auth'); 
    } 
    
    public function registrar_monitoreo_paciente(Request $request)
    {
        $fechaActual = Kardexe::getFechaServidor();
        Log::info($fechaActual);
        return response()->json($fechaActual);
    }

    public function buscar_registro_emergencia($id_emergencia)
    {
        $id_nota_enfermeria = Emergencia::where('id', $id_emergencia)->pluck('id_nota_enfermeria');
        return response()->json($id_nota_enfermeria);
    }

    public function buscar_ptje_glasgow_nota($id_nota)
    {
        $puntaje_glasgow_ingresado = valoracionesEnfermerias::where('id_enfermeria', $id_nota)->pluck('punto_calculado_glasgow');
        return response()->json($puntaje_glasgow_ingresado);
    }

    public function listarMonitoreoDetalles(Request $request)
    {
        $incio = $request->fecha_monitoreo_inicial;
        $fin = $request->fecha_monitoreo_final;
        $inicioFormateado = Carbon::createFromFormat('d/m/Y', $incio)->format('Y-m-d');
        $finalFormateado = Carbon::createFromFormat('d/m/Y', $fin)->format('Y-m-d');
        $model_monitoreo = new Monitoreo();
        $id_ext_enfermeria = Enfermeria::where('id', '=', $request->id_enfermeria_moni)->select('id_ext_enfermeria')->first()->id_ext_enfermeria;
        $existeAtencionMedica = Prestaciones::where('id_ext', '=', $id_ext_enfermeria)->exists();
        if($existeAtencionMedica) {
            $monitoreos = $model_monitoreo->lista_monitoreos_por_turno($request->id_enfermeria_moni, $inicioFormateado, $finalFormateado, $request->listado_turno);
        } else {
            $monitoreos = $model_monitoreo->lista_monitoreos_por_turno_sa($request->id_enfermeria_moni, $inicioFormateado, $finalFormateado, $request->listado_turno);
        }
        return response()->json($monitoreos);
    }

    public function listarMonitoreoDetalles_hospi(Request $request)
    {
        $incio = $request->fecha_monitoreo_inicial;
        $fin = $request->fecha_monitoreo_final;
        $inicioFormateado = Carbon::createFromFormat('d/m/Y', $incio)->format('Y-m-d');
        $finalFormateado = Carbon::createFromFormat('d/m/Y', $fin)->format('Y-m-d');
        $model_monitoreo = new Monitoreo();
        $monitoreos = $model_monitoreo->lista_monitoreos_por_turno_hospi($request->id_enfermeria_moni, $inicioFormateado, $finalFormateado, $request->listado_turno);
        return response()->json($monitoreos);
    }

    public function RegistrarDetalleDeMonitoreo(Request $request)
    {
        $time = new DateTime($request->fecha_hora_monitoreo);
        $horaFormateada = $time->format('H:i:s');
        $fechaActual = date('Y-m-d');
        $fechaParaRegistro = $fechaActual . " " . $horaFormateada;
        // TODO: MEJORAR CONSULTA, SIEMPRE TRAER EL MONITREO DEL DIA PERO DE ESA EMERGENCIA(del id de la emergencia, debo buscar la enfermeria y luego su monitoreo)
        $camposMonitoreoDetalle = caracterFisico::where('flag_monitoreo', 1)->where('keyword', '!=', 'infusiones')->where('keyword', '!=', 'infusiones_cantidad')->select('id', 'keyword')->get();
       /*  $monitoreoDelDia =(Monitoreo::join('enfermerias', 'monitoreos.id_enfermeria', '=', 'enfermerias.id')
            ->join('emergencias', 'enfermerias.id_ext_enfermeria', '=', 'emergencias.id')
            ->where('emergencias.id_asegurado', $request->asegurado_id_monitoreo)
            ->whereRaw('CAST(monitoreos.created_at AS DATE) BETWEEN ? AND ?', [date('Y-m-d').' 00:00:00', date('Y-m-d').' 23:59:59'])
            ->select('monitoreos.id')
            ->get()); // Siempre debe ser 1 registro por dia (SHM). */
        $exists = Monitoreo::whereRaw('CAST(created_at AS DATE) BETWEEN ? AND ?', [date('Y-m-d').' 00:00:00', date('Y-m-d').' 23:59:59'])
        ->where('id_enfermeria', $request->id_enfermeria)
        ->first();

        // VALIDA SI EXISTE UN MONITOREO EN EL DIA PARA ESE NOTA DE USUARIO:
        if (/* count($monitoreoDelDia) > 0 */isset($exists)) {
            foreach($camposMonitoreoDetalle as $i => $campo) {
                $campoParaBuscar = $campo->keyword;
                if (isset($request[$campoParaBuscar]) /* && $request[$campoParaBuscar] != '' */) {
                    $monitoreoDetalle = new Monitoreo_detalles();
                    $monitoreoDetalle->id_monitoreo = /* $monitoreoDelDia[0] */$exists->id;
                    $monitoreoDetalle->id_caracter_fisico = $campo->id;
                    $monitoreoDetalle->hora_monitoreo = $fechaParaRegistro; // El request solo guardar la hora, la fecha siempre sera la actual.
                    $monitoreoDetalle->id_turno = $request['turno_reg'];
                    $monitoreoDetalle->descripcion = $request[$campoParaBuscar];
                    $monitoreoDetalle->created_us = Auth::id();
                    $monitoreoDetalle->updated_us = Auth::id();
                    $monitoreoDetalle->created_at = date('Y-m-d H:i:s');
                    $monitoreoDetalle->updated_at = date('Y-m-d H:i:s');
                    $monitoreoDetalle->save();
                }
            }
            // Seccion infusiones
            if (count($request->infusiones) > 0) {
                $arrayInfusiones = [];
                foreach ($request->infusiones as $key => $infusion) {
                    if($infusion != '') {
                        $json_infusion = [
                            "id" => $request->infusiones[$key],
                            "nombre" => $request->nombre_infusion[$key],
                            "cantidad" => $request->infusiones_cantidad[$key],
                        ];
                        $arrayInfusiones[] = $json_infusion;
                    }
                }
                $monitoreoDetalle = new Monitoreo_detalles();
                $monitoreoDetalle->id_monitoreo = /* $monitoreoDelDia[0] */$exists->id;
                $monitoreoDetalle->id_caracter_fisico = 37;
                $monitoreoDetalle->hora_monitoreo = $fechaParaRegistro;
                $monitoreoDetalle->id_turno = $request['turno_reg'];
                $monitoreoDetalle->descripcion = json_encode($arrayInfusiones);
                $monitoreoDetalle->created_us = Auth::id();
                $monitoreoDetalle->updated_us = Auth::id();
                $monitoreoDetalle->created_at = date('Y-m-d H:i:s');
                $monitoreoDetalle->updated_at = date('Y-m-d H:i:s');
                $monitoreoDetalle->save();
            } else {
                $arrayInfusiones = [];
                $monitoreoDetalle = new Monitoreo_detalles();
                $monitoreoDetalle->id_monitoreo = /* $monitoreoDelDia[0] */$exists->id;
                $monitoreoDetalle->id_caracter_fisico = 37;
                $monitoreoDetalle->hora_monitoreo = $fechaParaRegistro;
                $monitoreoDetalle->id_turno = $request['turno_reg'];
                $monitoreoDetalle->descripcion = json_encode($arrayInfusiones);
                $monitoreoDetalle->created_us = Auth::id();
                $monitoreoDetalle->updated_us = Auth::id();
                $monitoreoDetalle->created_at = date('Y-m-d H:i:s');
                $monitoreoDetalle->updated_at = date('Y-m-d H:i:s');
                $monitoreoDetalle->save();
            }
        } else {
            // Crea un nuevo monitoreo con la fecha y hora del dia actual
            $monitoreo = new Monitoreo();
            $monitoreo->id_enfermeria = $request->id_enfermeria;
            $monitoreo->created_us = Auth::id();
            $monitoreo->updated_us = Auth::id();
            $monitoreo->created_at = date('Y-m-d H:i:s');
            $monitoreo->updated_at = date('Y-m-d H:i:s');
            $monitoreo->save();

            foreach($camposMonitoreoDetalle as $i => $campo) {
                $campoParaBuscar = $campo->keyword;
                if (isset($request[$campoParaBuscar])) {
                    $monitoreoDetalle = new Monitoreo_detalles();
                    $monitoreoDetalle->id_monitoreo = $monitoreo->id;
                    $monitoreoDetalle->id_caracter_fisico = $campo->id;
                    $monitoreoDetalle->hora_monitoreo = $fechaParaRegistro;
                    $monitoreoDetalle->id_turno = $request['turno_reg'];
                    $monitoreoDetalle->descripcion = $request[$campoParaBuscar];
                    $monitoreoDetalle->created_us = Auth::id();
                    $monitoreoDetalle->updated_us = Auth::id();
                    $monitoreoDetalle->created_at = date('Y-m-d H:i:s');
                    $monitoreoDetalle->updated_at = date('Y-m-d H:i:s');
                    $monitoreoDetalle->save();
                }
            }
            // Seccion infusiones
            if (count($request->infusiones) > 0) {
                $arrayInfusiones = [];
                foreach ($request->infusiones as $key => $infusion) {
                    if($infusion != '') {
                        $json_infusion = [
                            "id" => $request->infusiones[$key],
                            "nombre" => $request->nombre_infusion[$key],
                            "cantidad" => $request->infusiones_cantidad[$key],
                        ];
                        $arrayInfusiones[] = $json_infusion;
                    }
                }
                $monitoreoDetalle = new Monitoreo_detalles();
                $monitoreoDetalle->id_monitoreo = $monitoreo->id;
                $monitoreoDetalle->id_caracter_fisico = 37;
                $monitoreoDetalle->hora_monitoreo = $fechaParaRegistro;
                $monitoreoDetalle->id_turno = $request['turno_reg'];
                $monitoreoDetalle->descripcion = json_encode($arrayInfusiones);
                $monitoreoDetalle->created_us = Auth::id();
                $monitoreoDetalle->updated_us = Auth::id();
                $monitoreoDetalle->created_at = date('Y-m-d H:i:s');
                $monitoreoDetalle->updated_at = date('Y-m-d H:i:s');
                $monitoreoDetalle->save();
            } else {
                $arrayInfusiones = [];
                $monitoreoDetalle = new Monitoreo_detalles();
                $monitoreoDetalle->id_monitoreo = $monitoreo->id;
                $monitoreoDetalle->id_caracter_fisico = 37;
                $monitoreoDetalle->hora_monitoreo = $fechaParaRegistro;
                $monitoreoDetalle->id_turno = $request['turno_reg'];
                $monitoreoDetalle->descripcion = json_encode($arrayInfusiones);
                $monitoreoDetalle->created_us = Auth::id();
                $monitoreoDetalle->updated_us = Auth::id();
                $monitoreoDetalle->created_at = date('Y-m-d H:i:s');
                $monitoreoDetalle->updated_at = date('Y-m-d H:i:s');
                $monitoreoDetalle->save();
            }
        }
        return response()->json($camposMonitoreoDetalle);
    }

    public function buscarMonitoreoParaEditar($id_monitoreo, $fecha_hora) {
        $model_monitoreo = new Monitoreo();
        $id_usuario = Auth::id();
        $detallesMonitoreo = $model_monitoreo->buscarRegistrosDeUnMonitoreoPorHora($id_monitoreo, $fecha_hora, $id_usuario);
        return response()->json($detallesMonitoreo);
    }

    private function endsWith($haystack, $needle)
    {
        // Verificar si $needle es más largo que $haystack
        if (strlen($needle) > strlen($haystack)) {
            return false;
        }
        
        // Obtener la longitud del sufijo
        $length = strlen($needle);
        
        // Comparar el final de $haystack con $needle
        return substr($haystack, -$length) === $needle;
    }

    public function editarMonitoreo(Request $request)
    {
        /* $fechaActual = date('Y-m-d'); */
        $camposMonitoreoDetalle = caracterFisico::where('flag_monitoreo', 1)->where('keyword', '!=', 'infusiones')->where('keyword', '!=', 'infusiones_cantidad')->select('id', 'keyword')->get();
        $data = $request->all();
        $suffix = '_up';
        $renamedData = [];
        foreach ($data as $key => $value) {
            if ($this->endsWith($key, $suffix)) {
                $newKey = substr($key, 0, -strlen($suffix));
                $renamedData[$newKey] = $value;
            } else {
                $renamedData[$key] = $value;
            }
        }
        $detalles = [];
        foreach($camposMonitoreoDetalle as $i => $campo) {
            $campoParaBuscar = $campo->keyword;
            if (isset($renamedData[$campoParaBuscar])/*  && $renamedData[$campoParaBuscar] != '' */) {
                $monitoreoDetalle = Monitoreo_detalles::join('caracter_fisicos', 'caracter_fisicos.id', '=', 'monitoreo_detalles.id_caracter_fisico')
                                    ->where('monitoreo_detalles.id_monitoreo', $renamedData['id_monitoreo'])
                                    ->where('caracter_fisicos.keyword', $campoParaBuscar)
                                    ->where('monitoreo_detalles.hora_monitoreo',/*  $fechaActual . " " .  */$renamedData['date_monitoreo'])
                                    ->select('monitoreo_detalles.id', 'caracter_fisicos.keyword')
                                    ->first();
                $detalles[] = $monitoreoDetalle;
                $detalleModel = Monitoreo_detalles::find($monitoreoDetalle->id);
                if ($detalleModel) {
                    $detalleModel->descripcion = $renamedData[$campoParaBuscar];
                    $detalleModel->save();
                }
            }
        }

        // Seccion infusiones
        if (isset($renamedData['infusiones']) && count($renamedData['infusiones']) > 0) {
            $infusionExistente = Monitoreo_detalles::join('caracter_fisicos', 'caracter_fisicos.id', '=', 'monitoreo_detalles.id_caracter_fisico')
            ->where('monitoreo_detalles.id_monitoreo', $renamedData['id_monitoreo'])
            ->where('caracter_fisicos.keyword', 'infusiones')
            ->where('monitoreo_detalles.hora_monitoreo', $renamedData['date_monitoreo'])
            ->select('monitoreo_detalles.id', 'monitoreo_detalles.descripcion')
            ->first();
            $arrayInfusionesUpdated = [];
            foreach ($renamedData['infusiones'] as $key_updater => $infusion_id) {
                if($infusion_id != '') {
                    $json_infusion = [
                        "id" => $renamedData['infusiones'][$key_updater],
                        "nombre" => $renamedData['nombre_infusion'][$key_updater],
                        "cantidad" => $renamedData['infusiones_cantidad'][$key_updater],
                    ];
                    $arrayInfusionesUpdated[] = $json_infusion;
                }
            }
            if ($infusionExistente) {
                $detalleInfusion = Monitoreo_detalles::find($infusionExistente->id);
                $detalleInfusion->descripcion = json_encode($arrayInfusionesUpdated);
                $detalleInfusion->save();
            } else {
                $monitoreoDetalle = new Monitoreo_detalles();
                $monitoreoDetalle->id_monitoreo = $renamedData['id_monitoreo'];
                $monitoreoDetalle->id_caracter_fisico = 37;
                $monitoreoDetalle->hora_monitoreo = $renamedData['date_monitoreo'];
                $monitoreoDetalle->id_turno = $request['id_turno'];
                $monitoreoDetalle->descripcion = json_encode($arrayInfusionesUpdated);
                // SOLO PODRA EDITAR EL MISMO USUARIO
                $monitoreoDetalle->created_us = Auth::id();
                $monitoreoDetalle->updated_us = Auth::id();
                $monitoreoDetalle->created_at = date('Y-m-d H:i:s');
                $monitoreoDetalle->updated_at = date('Y-m-d H:i:s');
                $monitoreoDetalle->save();
            }
        }
        return response()->json([]);
    }

    public function detalleMonitoreo($id_monitoreo, /* $id_turno,  */$fecha_hora)
    {
        // Se recomiendo implementar un middleware que respona ante la ausencia de una sesion activa en el servidor
        /* $id_usuario = Auth::id(); */
        $model_monitoreo = new Monitoreo();
        $detallesMonitoreo = $model_monitoreo->detallarMonitoreo($id_monitoreo, $fecha_hora/* , $id_turno, $id_usuario */);
        return response()->json($detallesMonitoreo);
    }

    public function exportarMonitoreoParaFirmar($id_monitoreo, $fecha_hora, $id_tipo_atencion = 2)
    {
        $path = public_path('tmp/csv');
        $path_zip = public_path('tmp/zip');
        if (!is_dir($path)) {
            error_log('No existe la carpeta tmp/csv');
            mkdir($path);
        }
        if (!is_dir($path_zip)) {
            error_log('No existe la carpeta tmp/zip');
            mkdir($path_zip);
            mkdir(public_path('tmp/zip/firmados'));
            mkdir(public_path('tmp/zip/sin_firma'));
        }
        $csv = "";
        if (Auth::check()):
            $csv .= Auth::user()->id;
        endif;
        $csv .= "_".date("YmdHis");
        $csv.= "_moni";
        $dateTime = new DateTime($fecha_hora);
        $timestamp = $dateTime->getTimestamp();
        $row = $id_monitoreo . "_" . $timestamp;
        Excel::create($csv, function($excel) use($row, $id_monitoreo, $fecha_hora, $id_tipo_atencion){
            $excel->sheet('GENERAL', function($sheet) use($row, $id_monitoreo, $fecha_hora, $id_tipo_atencion) {
                $variable = [];
                $this->generarPdfParaFirma($id_monitoreo, $fecha_hora, $id_tipo_atencion);
                array_push($variable, array(url().'/tmp/pdf/sin_firma/monitoreo_'.$row.'.pdf', url().'/upload_firma/?name=cf_monitoreo_'.$row.'.pdf&id_tipo=monitoreo&id_resol='.$row, 'cf_monitoreo_'.$row.'.pdf','0','45','350','70','Fecha de Firma: <DATE> Firmante: <SIGNER>',url().'/images/escudo.jpg','1','7'));
                $sheet->fromArray($variable, null, '', false, false);
            });
        })->store('csv',public_path('tmp/csv'));
        $pa_sisdolica = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'pa_sisdolica');
        $obj = new Archive7z(public_path('tmp/zip/sin_firma/'.$csv.'.7z'));
        if ($pa_sisdolica->ruta_firma == "") {
            $obj->addEntry(public_path('/tmp/pdf/sin_firma/monitoreo_hxt_'.$row.'.pdf')); 
        }
        $resultado["csv"]=$csv;
        $resultado["sw"]=true;
        return response()->json($resultado); 
    }

    public function generarPdfParaFirma($id_monitoreo, $fecha_hora, $id_tipo_atencion)
    {
        $path = public_path('tmp/pdf');
		if (!is_dir($path)) {
            mkdir($path);
		}
		$path = public_path('tmp/pdf/sin_firma/');
		if (!is_dir($path)) {
            mkdir($path);
		}               
        $date = Carbon::now();
        $fechaActualPdf = $date->format('Y/m/d');
        $model_establecimiento = new \App\Models\Establecimiento();
		$model_receta_diagnostico = new \App\Models\Receta_diagnostico; 
		$model_receta_vale = new Receta_vale(); 
		$model_nota_enfermeria = new Nota_enfermeria();
        $model_enfermeria = new Enfermeria(); 
        $fechaActual = $date->format('d/m/Y');
        $horaActual = $date->format('H:i:s');
        $id_establecimiento = Auth::user()->id_establecimiento;  
        $establecimiento =  $model_establecimiento->GetById($id_establecimiento);
        $nombre_establecimiento = $establecimiento[0]->nombre;
        $monitoreoPorDia = Monitoreo::find($id_monitoreo);
        $notaEnfermeria = Enfermeria::find($monitoreoPorDia->id_enfermeria);
		$datos_nota_ingreso = $model_nota_enfermeria->getObtener_nota_ingreso_by_id($notaEnfermeria->id, '');  
        $infusionesString = Monitoreo_detalles::getInfusiones($id_monitoreo, $fecha_hora); 
        $infusiones = json_decode($infusionesString->descripcion, true);
        $date = new DateTime($fecha_hora);
        $timestamp = $date->getTimestamp();
        $pa_sisdolica = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'pa_sisdolica');
        $pa_diastolica = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'pa_diastolica');
        $pulso_fc = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'pulso_fc');
        $frecuencia_respiratoria = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'frecuencia_respiratoria');
        $fi02 = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'fi02');
        $sa02 = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'sa02');
        $temperatura = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'temperatura');
        $frecuencia_m = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'frecuencia_m');
        $frecuencia_p = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'frecuencia_p');
        $pupila_izquierda = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'pupila_izquierda');
        $pupila_derecha = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'pupila_derecha');
        $grado_dependencia = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'grado_dependencia');
        $fijacion_der = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'fijacion_der');
        $fijacion_izq = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'fijacion_izq');
        $hemoglucotest = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'hemoglucotest');
        $hgt = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'hgt');
        $solpol = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'solpol');
        $diurisis_horaria = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'diurisis_horaria');
        $p_picco = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'p_picco');
        $co2 = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'co2');
        $p_limite = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'p_limite');
        $p_maxima = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'p_maxima');
        $presion_soporte = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'presion_soporte');
        $i_e = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'i_e');
        $vol_min = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'vol_min');
        $peed = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'peed');
        $volumen_tiral = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'volumen_tiral');
        $modalidad = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'modalidad');
        $vmni = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'vmni');
        $vmi = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'vmi');
        $fu_tubo = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'fu_tubo');
        $pam = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'pam');
        $rass = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'rass');
        $glasgow = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'glasgow');
        $conciencia_monitoreo = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'conciencia_monitoreo');
        $temperatura = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'temperatura');
        $dispositivo_oxigenoterapia = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'dispositivo_oxigenoterapia');
        $tiraje = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'tiraje');
        $respiracion_espontanea = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'respiracion_espontanea');
        $turno_monitoreo = Turno::where('id', $pa_sisdolica->id_turno)->select('descripcion')->first();
        $fijacion_nombre = null;
        $vm_nombre = null;
        $cabecera = null;
        $datos_atencion = null;
        $diagnosticos = null;
        if ($id_tipo_atencion == 2) {
            $datos_atencion = $model_receta_vale->getEmergenciaById_farmacia_new($notaEnfermeria->id_ext_enfermeria);
            $cabecera = $model_enfermeria->ObtenerCabeceraPdfById_sa($notaEnfermeria->id);
            $diagnosticos = $model_receta_diagnostico->DiagnosticosByEmergencia_Enfermeria($notaEnfermeria->id_ext_enfermeria); 
            if ($fijacion_der->descripcion != '') {
                $fijacion_nombre = 'DER';
            } else if ($fijacion_izq->descripcion != '') {
                $fijacion_nombre = 'IZQ';
            }
            if ($vmni->descripcion != '') {
                $vm_nombre = 'VMNI';
            } else if ($vmi->descripcion != '') {
                $vm_nombre = 'VMI';
            }
        } else if ($id_tipo_atencion == 3) {
            $diagnosticos = $model_receta_diagnostico->DiagnosticosByHospitalizacion_Enfermeria($notaEnfermeria->id_ext_enfermeria); 
            $datos_atencion = $model_receta_vale->getHospitalizacionById_farmacia_new($notaEnfermeria->id_ext_enfermeria);
            $cabecera = $model_enfermeria->ObtenerCabeceraPdfByIdHospi($notaEnfermeria->id);
        }
        $intrepretacionRass_up = array(
            "4"  => 'COMBATIVO',
            "3"  => 'MUY AGITADO',
            "2"  => 'AGITADO',
            "1"  => 'INQUIETO',
            "0"  => 'ALERTA Y TRANQUILO',
            "-1" => 'SOMNOLIENTO',
            "-2" => 'SEDACION LIGERA',
            "-3" => 'SEDACION MODERADA',
            "-4" => 'SEDACION PROFUNDA',
            "-5" => 'NO ESTIMULABLE',
        );
        $descripcionRass_up = array(
            "4"  => 'Violento o combativo, con riesgo para el personal',
            "3"  => 'Intenta arrancarse los tubos o cateteres o es agresivo con el personal',
            "2"  => 'Movimientos descoordinados o desadaptacion del respirador',
            "1"  => 'Ansioso, pero sin movimientos agresivos o vigorosos',
            "0"  => '',
            "-1" => 'Tendencia al sueño, es capaz de estar mas de 10 segundos despierto (apertura de ojos) a la llamada',
            "-2" => 'Menos de 10 segundos despierto (apertura de ojos) a la llamada',
            "-3" => 'Movimientos (sin apertura de ojos) a la llamada',
            "-4" => 'No responde a la voz, pero se mueve o abre los ojos, al estimulo fisico',
            "-5" => 'Sin respuesta a la voz o al estimulo fisico',
        );   
        $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/".$fechaActualPdf."/cf_monitoreo_hxt_".$id_monitoreo. "_" . $timestamp.".pdf";
        $codigo_qr = QrCode::format('png')->size(150)->generate("https://sigesapol.dirsapolpnp.gob.pe/".$fechaActualPdf."/cf_monitoreo_hxt_".$id_monitoreo. "_" . $timestamp.".pdf");             
        $intrepretacionRass = $rass->descripcion != '' ? $intrepretacionRass_up[$rass->descripcion] : '';
        $descripcionRass = $rass->descripcion != '' ? $descripcionRass_up[$rass->descripcion] : '';
        $pdf = \PDF::loadView('frontend.emergencia_enfermeria.pdf_monitoreo_enfermeria_cf',
        compact(
            'fijacion_nombre',
            'vm_nombre',
            'url_descarga',
            'codigo_qr',
            'turno_monitoreo',
            'datos_atencion',
            'cabecera',
            'id_tipo_atencion',
            'intrepretacionRass',
            'descripcionRass',
            'notaEnfermeria',
            'diagnosticos' ,
            'datos_nota_ingreso' ,
            'nombre_establecimiento',
            'fechaActual',
            'horaActual',
            'infusiones',
            'id_monitoreo',
            'timestamp', 
            'pa_sisdolica',
            'pa_diastolica',
            'pulso_fc',
            'frecuencia_respiratoria',
            'fi02',
            'sa02',
            'temperatura',
            'frecuencia_m',
            'frecuencia_p',
            'pupila_izquierda',
            'pupila_derecha',
            'grado_dependencia',
            'fijacion_der',
            'fijacion_izq',
            'hemoglucotest',
            'hgt',
            'solpol',
            'diurisis_horaria',
            'p_picco',
            'co2',
            'p_limite',
            'p_maxima',
            'presion_soporte',
            'i_e',
            'vol_min',
            'peed',
            'volumen_tiral',
            'modalidad',
            'vmni',
            'vmi',
            'fu_tubo',
            'pam',
            'rass',
            'glasgow',
            'conciencia_monitoreo',
            'temperatura',
            'fecha_hora',
            'tiraje',
            'dispositivo_oxigenoterapia',
            'respiracion_espontanea'
        ));
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'portrait');
        $dateTime = new DateTime($fecha_hora);
        $timestamp = $dateTime->getTimestamp();
		file_put_contents($path."monitoreo_hxt_".$id_monitoreo. "_" . $timestamp .".pdf", $pdf->output());
    }

    public function exportarMonitoreo($id_monitoreo, $fecha_hora, $id_tipo_atencion = 2)
    {
        $date = Carbon::now();
        $model_establecimiento = new \App\Models\Establecimiento();
		$model_receta_diagnostico = new \App\Models\Receta_diagnostico; 
		$model_receta_vale = new Receta_vale(); 
		$model_nota_enfermeria = new Nota_enfermeria();
        $model_enfermeria = new Enfermeria(); 
        $fechaActual = $date->format('d/m/Y');
        $horaActual = $date->format('H:i:s');
        $id_establecimiento = Auth::user()->id_establecimiento;  
        $establecimiento =  $model_establecimiento->GetById($id_establecimiento);
        $nombre_establecimiento = $establecimiento[0]->nombre;
        $monitoreoPorDia = Monitoreo::find($id_monitoreo);
        $notaEnfermeria = Enfermeria::find($monitoreoPorDia->id_enfermeria);
		$datos_nota_ingreso = $model_nota_enfermeria->getObtener_nota_ingreso_by_id($notaEnfermeria->id, '');  
        $infusionesString = Monitoreo_detalles::getInfusiones($id_monitoreo, $fecha_hora); 
        $infusiones = json_decode($infusionesString->descripcion, true);
        $date = new DateTime($fecha_hora);
        $timestamp = $date->getTimestamp();
        $pa_sisdolica = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'pa_sisdolica');
        $pa_diastolica = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'pa_diastolica');
        $pulso_fc = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'pulso_fc');
        $frecuencia_respiratoria = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'frecuencia_respiratoria');
        $fi02 = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'fi02');
        $sa02 = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'sa02');
        $temperatura = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'temperatura');
        $frecuencia_m = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'frecuencia_m');
        $frecuencia_p = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'frecuencia_p');
        $pupila_izquierda = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'pupila_izquierda');
        $pupila_derecha = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'pupila_derecha');
        $grado_dependencia = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'grado_dependencia');
        $fijacion_der = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'fijacion_der');
        $fijacion_izq = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'fijacion_izq');
        $hemoglucotest = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'hemoglucotest');
        $hgt = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'hgt');
        $solpol = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'solpol');
        $diurisis_horaria = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'diurisis_horaria');
        $p_picco = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'p_picco');
        $co2 = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'co2');
        $p_limite = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'p_limite');
        $p_maxima = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'p_maxima');
        $presion_soporte = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'presion_soporte');
        $i_e = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'i_e');
        $vol_min = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'vol_min');
        $peed = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'peed');
        $volumen_tiral = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'volumen_tiral');
        $modalidad = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'modalidad');
        $vmni = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'vmni');
        $vmi = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'vmi');
        $fu_tubo = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'fu_tubo');
        $pam = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'pam');
        $rass = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'rass');
        $glasgow = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'glasgow');
        $conciencia_monitoreo = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'conciencia_monitoreo');
        $temperatura = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'temperatura');
        $dispositivo_oxigenoterapia = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'dispositivo_oxigenoterapia');
        $tiraje = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'tiraje');
        $respiracion_espontanea = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'respiracion_espontanea');
        $turno_monitoreo = Turno::where('id', $pa_sisdolica->id_turno)->select('descripcion')->first();
        $fijacion_nombre = null;
        $vm_nombre = null;
        $cabecera = null;
        $datos_atencion = null;
        $diagnosticos = null;
        if ($id_tipo_atencion == 2) {
            $datos_atencion = $model_receta_vale->getEmergenciaById_farmacia_new($notaEnfermeria->id_ext_enfermeria);
            $cabecera = $model_enfermeria->ObtenerCabeceraPdfById_sa($notaEnfermeria->id);
            $diagnosticos = $model_receta_diagnostico->DiagnosticosByEmergencia_Enfermeria($notaEnfermeria->id_ext_enfermeria); 
            if ($fijacion_der->descripcion != '') {
                $fijacion_nombre = 'DER';
            } else if ($fijacion_izq->descripcion != '') {
                $fijacion_nombre = 'IZQ';
            }
            if ($vmni->descripcion != '') {
                $vm_nombre = 'VMNI';
            } else if ($vmi->descripcion != '') {
                $vm_nombre = 'VMI';
            }
        } else if ($id_tipo_atencion == 3) {
            $diagnosticos = $model_receta_diagnostico->DiagnosticosByHospitalizacion_Enfermeria($notaEnfermeria->id_ext_enfermeria); 
            $datos_atencion = $model_receta_vale->getHospitalizacionById_farmacia_new($notaEnfermeria->id_ext_enfermeria);
            $cabecera = $model_enfermeria->ObtenerCabeceraPdfByIdHospi($notaEnfermeria->id);
        }
        $intrepretacionRass_up = array(
            "4"  => 'COMBATIVO',
            "3"  => 'MUY AGITADO',
            "2"  => 'AGITADO',
            "1"  => 'INQUIETO',
            "0"  => 'ALERTA Y TRANQUILO',
            "-1" => 'SOMNOLIENTO',
            "-2" => 'SEDACION LIGERA',
            "-3" => 'SEDACION MODERADA',
            "-4" => 'SEDACION PROFUNDA',
            "-5" => 'NO ESTIMULABLE',
        );
        $descripcionRass_up = array(
            "4"  => 'Violento o combativo, con riesgo para el personal',
            "3"  => 'Intenta arrancarse los tubos o cateteres o es agresivo con el personal',
            "2"  => 'Movimientos descoordinados o desadaptacion del respirador',
            "1"  => 'Ansioso, pero sin movimientos agresivos o vigorosos',
            "0"  => '',
            "-1" => 'Tendencia al sueño, es capaz de estar mas de 10 segundos despierto (apertura de ojos) a la llamada',
            "-2" => 'Menos de 10 segundos despierto (apertura de ojos) a la llamada',
            "-3" => 'Movimientos (sin apertura de ojos) a la llamada',
            "-4" => 'No responde a la voz, pero se mueve o abre los ojos, al estimulo fisico',
            "-5" => 'Sin respuesta a la voz o al estimulo fisico',
        );                
        $intrepretacionRass = $rass->descripcion != '' ? $intrepretacionRass_up[$rass->descripcion] : '';
        $descripcionRass = $rass->descripcion != '' ? $descripcionRass_up[$rass->descripcion] : '';
        $pdf = \PDF::loadView('frontend.emergencia_enfermeria.pdf_monitoreo_enfermeria',
        compact(
            'fijacion_nombre',
            'vm_nombre',
            'turno_monitoreo',
            'datos_atencion',
            'cabecera',
            'id_tipo_atencion',
            'intrepretacionRass',
            'descripcionRass',
            'notaEnfermeria',
            'diagnosticos' ,
            'datos_nota_ingreso' ,
            'nombre_establecimiento',
            'fechaActual',
            'horaActual',
            'infusiones',
            'id_monitoreo',
            'timestamp', 
            'pa_sisdolica',
            'pa_diastolica',
            'pulso_fc',
            'frecuencia_respiratoria',
            'fi02',
            'sa02',
            'temperatura',
            'frecuencia_m',
            'frecuencia_p',
            'pupila_izquierda',
            'pupila_derecha',
            'grado_dependencia',
            'fijacion_der',
            'fijacion_izq',
            'hemoglucotest',
            'hgt',
            'solpol',
            'diurisis_horaria',
            'p_picco',
            'co2',
            'p_limite',
            'p_maxima',
            'presion_soporte',
            'i_e',
            'vol_min',
            'peed',
            'volumen_tiral',
            'modalidad',
            'vmni',
            'vmi',
            'fu_tubo',
            'pam',
            'rass',
            'glasgow',
            'conciencia_monitoreo',
            'temperatura',
            'fecha_hora',
            'tiraje',
            'dispositivo_oxigenoterapia',
            'respiracion_espontanea'
        ));
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('monitoreo_emergencia.pdf'); 
    }

    public function generarCabeceraMonitoreoPorDia($id_monitoreo, $id_tipo_atencion)
    {
        $date = Carbon::now();
        $model_establecimiento = new \App\Models\Establecimiento();
		$model_receta_diagnostico = new \App\Models\Receta_diagnostico; 
		$model_receta_vale = new Receta_vale(); 
		$model_nota_enfermeria = new Nota_enfermeria();
        $model_enfermeria = new Enfermeria(); 
        $fechaActual = $date->format('d/m/Y');
        $horaActual = $date->format('H:i:s');
        $id_establecimiento = Auth::user()->id_establecimiento;  
        $establecimiento =  $model_establecimiento->GetById($id_establecimiento);
        $nombre_establecimiento = $establecimiento[0]->nombre;
        $monitoreoPorDia = Monitoreo::find($id_monitoreo);
        $notaEnfermeria = Enfermeria::find($monitoreoPorDia->id_enfermeria);
        if ($id_tipo_atencion == 2) {
            $datos_atencion = $model_receta_vale->getEmergenciaById_farmacia_new($notaEnfermeria->id_ext_enfermeria);
            $diagnosticos = $model_receta_diagnostico->DiagnosticosByEmergencia_Enfermeria($notaEnfermeria->id_ext_enfermeria); 
            $datos_nota_ingreso = $model_nota_enfermeria->getObtener_nota_ingreso_by_id($notaEnfermeria->id, '');  
            $cabecera = $model_enfermeria->ObtenerCabeceraPdfById_sa($notaEnfermeria->id);
        } else {
            $datos_atencion = $model_receta_vale->getHospitalizacionById_farmacia_new($notaEnfermeria->id_ext_enfermeria);
            $diagnosticos = $model_receta_diagnostico->DiagnosticosByHospitalizacion_Enfermeria($notaEnfermeria->id_ext_enfermeria); 
            $datos_nota_ingreso = $model_nota_enfermeria->getObtener_nota_ingreso_by_id_hospi($notaEnfermeria->id, 3);  
            $cabecera = $model_enfermeria->ObtenerCabeceraPdfByIdHospi($notaEnfermeria->id);
        }
        $timestamp = $date->getTimestamp();
        $pdf = \PDF::loadView('frontend.emergencia_enfermeria.pdf_monitoreo_cabecera',
        compact(
            'fechaActual',
            'horaActual',
            'id_establecimiento',
            'establecimiento',
            'nombre_establecimiento',
            'monitoreoPorDia',
            'notaEnfermeria',
            'datos_atencion',
            'diagnosticos',
            'datos_nota_ingreso',
            'cabecera',
            'timestamp',
            'id_tipo_atencion'
        ));
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('monitoreo_s.pdf'); 
    }

    public function exportarMonitoreoSinCabecera($id_monitoreo, $fecha_hora, $id_tipo_atencion)
    {
        $date = Carbon::now();
        $model_establecimiento = new \App\Models\Establecimiento();
		$model_receta_diagnostico = new \App\Models\Receta_diagnostico; 
		$model_receta_vale = new Receta_vale(); 
		$model_nota_enfermeria = new Nota_enfermeria();
        $model_enfermeria = new Enfermeria(); 
        $fechaActual = $date->format('d/m/Y');
        $horaActual = $date->format('H:i:s');
        $id_establecimiento = Auth::user()->id_establecimiento;  
        $establecimiento =  $model_establecimiento->GetById($id_establecimiento);
        $nombre_establecimiento = $establecimiento[0]->nombre;
        $monitoreoPorDia = Monitoreo::find($id_monitoreo);
        $notaEnfermeria = Enfermeria::find($monitoreoPorDia->id_enfermeria);
        $infusionesString = Monitoreo_detalles::getInfusiones($id_monitoreo, $fecha_hora); 
        $infusiones = json_decode($infusionesString->descripcion, true);
        $date = new DateTime($fecha_hora);
        $timestamp = $date->getTimestamp();
        $pa_sisdolica = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'pa_sisdolica');
        $pa_diastolica = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'pa_diastolica');
        $pulso_fc = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'pulso_fc');
        $frecuencia_respiratoria = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'frecuencia_respiratoria');
        $fi02 = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'fi02');
        $sa02 = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'sa02');
        $temperatura = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'temperatura');
        $frecuencia_m = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'frecuencia_m');
        $frecuencia_p = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'frecuencia_p');
        $pupila_izquierda = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'pupila_izquierda');
        $pupila_derecha = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'pupila_derecha');
        $grado_dependencia = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'grado_dependencia');
        $fijacion_der = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'fijacion_der');
        $fijacion_izq = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'fijacion_izq');
        $hemoglucotest = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'hemoglucotest');
        $hgt = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'hgt');
        $solpol = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'solpol');
        $diurisis_horaria = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'diurisis_horaria');
        $p_picco = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'p_picco');
        $co2 = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'co2');
        $p_limite = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'p_limite');
        $p_maxima = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'p_maxima');
        $presion_soporte = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'presion_soporte');
        $i_e = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'i_e');
        $vol_min = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'vol_min');
        $peed = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'peed');
        $volumen_tiral = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'volumen_tiral');
        $modalidad = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'modalidad');
        $vmni = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'vmni');
        $vmi = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'vmi');
        $fu_tubo = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'fu_tubo');
        $pam = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'pam');
        $rass = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'rass');
        $glasgow = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'glasgow');
        $conciencia_monitoreo = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'conciencia_monitoreo');
        $temperatura = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'temperatura');
        $dispositivo_oxigenoterapia = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'dispositivo_oxigenoterapia');
        $tiraje = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'tiraje');
        $respiracion_espontanea = Monitoreo_detalles::getDetallePorKeyword($id_monitoreo, $fecha_hora, 'respiracion_espontanea');
        $turno_monitoreo = Turno::where('id', $pa_sisdolica->id_turno)->select('descripcion')->first();
        $fijacion_nombre = null;
        $vm_nombre = null;
        if ($id_tipo_atencion == 2) {
            if ($fijacion_der->descripcion != '') {
                $fijacion_nombre = 'DER';
            } else if ($fijacion_izq->descripcion != '') {
                $fijacion_nombre = 'IZQ';
            }
            if ($vmni->descripcion != '') {
                $vm_nombre = 'VMNI';
            } else if ($vmi->descripcion != '') {
                $vm_nombre = 'VMI';
            }
        }
        $intrepretacionRass_up = array(
            "4"  => 'COMBATIVO',
            "3"  => 'MUY AGITADO',
            "2"  => 'AGITADO',
            "1"  => 'INQUIETO',
            "0"  => 'ALERTA Y TRANQUILO',
            "-1" => 'SOMNOLIENTO',
            "-2" => 'SEDACION LIGERA',
            "-3" => 'SEDACION MODERADA',
            "-4" => 'SEDACION PROFUNDA',
            "-5" => 'NO ESTIMULABLE',
        );
        $descripcionRass_up = array(
            "4"  => 'Violento o combativo, con riesgo para el personal',
            "3"  => 'Intenta arrancarse los tubos o cateteres o es agresivo con el personal',
            "2"  => 'Movimientos descoordinados o desadaptacion del respirador',
            "1"  => 'Ansioso, pero sin movimientos agresivos o vigorosos',
            "0"  => '',
            "-1" => 'Tendencia al sueño, es capaz de estar mas de 10 segundos despierto (apertura de ojos) a la llamada',
            "-2" => 'Menos de 10 segundos despierto (apertura de ojos) a la llamada',
            "-3" => 'Movimientos (sin apertura de ojos) a la llamada',
            "-4" => 'No responde a la voz, pero se mueve o abre los ojos, al estimulo fisico',
            "-5" => 'Sin respuesta a la voz o al estimulo fisico',
        );                
        $intrepretacionRass = $rass->descripcion != '' ? $intrepretacionRass_up[$rass->descripcion] : '';
        $descripcionRass = $rass->descripcion != '' ? $descripcionRass_up[$rass->descripcion] : '';
        $pdf = \PDF::loadView('frontend.emergencia_enfermeria.pdf_monitoreo_sin_cabecera',
        compact(
            'fecha_hora',
            'fijacion_nombre',
            'vm_nombre',
            'turno_monitoreo',
            'intrepretacionRass',
            'descripcionRass',
            'fechaActual',
            'horaActual',
            'infusiones',
            'id_monitoreo',
            'timestamp', 
            'pa_sisdolica',
            'pa_diastolica',
            'pulso_fc',
            'frecuencia_respiratoria',
            'fi02',
            'sa02',
            'temperatura',
            'frecuencia_m',
            'frecuencia_p',
            'pupila_izquierda',
            'pupila_derecha',
            'grado_dependencia',
            'fijacion_der',
            'fijacion_izq',
            'hemoglucotest',
            'hgt',
            'solpol',
            'diurisis_horaria',
            'p_picco',
            'co2',
            'p_limite',
            'p_maxima',
            'presion_soporte',
            'i_e',
            'vol_min',
            'peed',
            'volumen_tiral',
            'modalidad',
            'vmni',
            'vmi',
            'fu_tubo',
            'pam',
            'rass',
            'glasgow',
            'conciencia_monitoreo',
            'temperatura',
            'tiraje',
            'dispositivo_oxigenoterapia',
            'respiracion_espontanea',
            'id_tipo_atencion'
        ));
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('monitoreo_s.pdf'); 
    }

    public function exportarMonitoreoPorDia($id_enfermeria, $fecha, $id_tipo_atencion = 2)
    {
        $model_monitoreo = new Monitoreo();
        $monitoreos = null;
        if ($id_tipo_atencion == 2) {
            $monitoreos = $model_monitoreo->lista_monitoreos_por_dia($id_enfermeria, $fecha);
        } else if ($id_tipo_atencion == 3) {
            $monitoreos = $model_monitoreo->lista_monitoreos_por_dia_hospi($id_enfermeria, $fecha);
        }
        $pdfStreams = [];
        if (count($monitoreos) > 0) {
            $pdfStreams[] = $this->generarCabeceraMonitoreoPorDia($monitoreos[0]->id, $id_tipo_atencion);
            foreach ($monitoreos as $index => $item) {
                $pdfStreams[] = $this->exportarMonitoreoSinCabecera($item->id, $fecha . ' ' . $item->hora, $id_tipo_atencion);
            }
        }
        $combinedPdfStream = $this->mergePdfs($pdfStreams);

        // Devolver el PDF combinado como descarga
        return response()->stream(
            function () use ($combinedPdfStream) {
                echo $combinedPdfStream;
            },
            200,
            ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'attachment; filename="monitoreo_' . $fecha . '.pdf"']
        );
    }

    private function mergePdfs(array $pdfStreams)
    {
        $pdf = new Fpdi();

        foreach ($pdfStreams as $pdfStream) {
            // Crear un archivo temporal para FPDI
            $tempFile = tempnam(sys_get_temp_dir(), 'pdf');
            file_put_contents($tempFile, $pdfStream);

            // Cargar el archivo temporal
            $pageCount = $pdf->setSourceFile($tempFile);

            // Importar cada página del PDF
            for ($i = 1; $i <= $pageCount; $i++) {
                $templateId = $pdf->importPage($i);
                $pdf->AddPage();
                $pdf->useTemplate($templateId);
            }

            // Eliminar el archivo temporal
            unlink($tempFile);
        }

        // Devuelve el PDF combinado como un stream
        return $pdf->Output('S');
    }

}