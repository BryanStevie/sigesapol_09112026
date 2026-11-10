<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\CuidadoEnfermeros;
use App\Models\Enfermeria;
use App\Models\ExamenImagenKardexes;
use App\Models\ExamenLaboratorioKardexes;
use App\Models\HorarioKardexMedicamento;
use App\Models\KardexEnfermerias;
use App\Models\KardexInterconsultas;
use App\Models\KardexMedicamentoEnfermeria;
use App\Models\Procedimiento;
use App\Models\Receta_diagnostico;
use Archive7z\Archive7z;
use QrCode;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;


class kardexEnfermeriasController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @param $emergenciaId
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('auth');
    }

    public function getAllByEmergenciaId($emergenciaId)
    {
        $kardexEnfermeriasModel = new KardexEnfermerias();

        $kardex = $kardexEnfermeriasModel->getAllByEmergenciaId($emergenciaId);

        return response()->json($kardex, 200);
    }

    public function searchByFilters(Request $request)
    {
        $kardexEnfermeriasModel = new KardexEnfermerias();
        $kardex = $kardexEnfermeriasModel->searchByFilters($request);
        return response()->json($kardex, 200);
    }
    public function searchByFiltersHospi(Request $request)
    {
        $kardexEnfermeriasModel = new KardexEnfermerias();
        $kardex = $kardexEnfermeriasModel->searchByFiltersHospitalizacion($request);
        return response()->json($kardex, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        Log::info(["request" => $request]);
        $enfermeria = Enfermeria::where("id_ext_enfermeria", "=", $request->input('emergenciaId'))->first();
        if (!isset($enfermeria)) {
            return response()->json([
                "message" => "No existe registro de la enfermeria"
            ], 404);
        }

        // TODO: AGREGAR VLIDACION EN CASO NO EXISTA LA ENFERMERIA
        $newKardex = new KardexEnfermerias();
        $newKardex->id_enfermeria = $enfermeria->id;
        $newKardex->id_turno = $request->input('turnoId');
        $newKardex->created_us = Auth::user()->id;
        $newKardex->created_at = Carbon::now();
        $newKardex->save();

        $kardexEnfermeriasId = $newKardex->id;

        $arrayLaboratorio = $request->input("arrayLaboratorio");
        if (is_array($arrayLaboratorio) && count($arrayLaboratorio) > 0) {
            foreach ($arrayLaboratorio as $key => $laboratorioItem) {
                $carbonDateLab = Carbon::createFromFormat('d/m/Y', $laboratorioItem['exam_aux_lab_fecha[]']);
                $newExamLab = ExamenLaboratorioKardexes::create([
                    'id_kardex_enfermeria' => $kardexEnfermeriasId,
                    'nombre_procedimiento' => $laboratorioItem['exam_aux_lab_nombre[]'],
                    'toma_muestra' => $laboratorioItem['exam_aux_lab_muestra[]'] === true ? 1 : 0,
                    'resultado' => $laboratorioItem['exam_aux_lab_result[]'] === true ? 1 : 0,
                    'fecha' => $carbonDateLab->format('Y-m-d'),
                    'hora' => $laboratorioItem['exam_aux_lab_hora[]'],
                    'reactivo' => $laboratorioItem['exam_aux_lab_reactive[]'] === true ? 1 : 0,
                    'id_procedimiento' => $laboratorioItem['id_procedimiento'],
                ]);
            }
        }

        $arrayImagenologia = $request->input("arrayImagenologia");
        if (is_array($arrayImagenologia) && count($arrayImagenologia) > 0) {
            foreach ($arrayImagenologia as $key => $imagenItem) {
                $carbonDateImage = Carbon::createFromFormat('d/m/Y', $imagenItem['exam_aux_imag_fecha[]']);
                $newExamImagen = ExamenImagenKardexes::create([
                    'id_kardex_enfermeria' => $kardexEnfermeriasId,
                    'nombre_imagen' => $imagenItem['exam_aux_imag_nombre[]'],
                    'toma_imagen' => $imagenItem['exam_aux_imag_imagen[]'] === true ? 1 : 0,
                    'fecha' => $carbonDateImage->format('Y-m-d'),
                    'hora' => $imagenItem['exam_aux_imag_hora[]'],
                    'id_procedimiento' => $imagenItem['id_procedimiento'],
                ]);
            }
        }

        $newCuidadoEnfermeros = CuidadoEnfermeros::create([
            'id_kardex_enfermeria' => $kardexEnfermeriasId,
            'cfv' => $request->input("controlFuncionesVitales") === true ? 1 : 0,
            'bhe' => $request->input("barreraHematoencefalica") === true ? 1 : 0,
            /* 'cabecera' => $request->input("cabecera"), */
            'cabecera' => $request->input('cabecera') === '' ||
              $request->input('cabecera') === null
                ? null
                : (int) $request->input('cabecera'),
            'id_oxigeno_terapia' => $request->input("oxigenoTerapiaId"),
            'oxigenoterapia_cantidad' => !empty($request->input("oxigenoterapiaCantidad")) ? $request->input("oxigenoterapiaCantidad") : null,
            'movilizacion' => $request->input("movilización") === true ? 1 : 0,
            /* 'hemoglucotest' => $request->input("hemoglucotest"), */
            'hemoglucotest' => json_encode($request->input("hemoglucotest")),
        ]);

        $arrayInterconsulta = $request->input("arrayInterconsulta");
        if (is_array($arrayInterconsulta) && count($arrayInterconsulta) > 0) {
            foreach ($arrayInterconsulta as $index => $interconsulta) {
                $carbonDateEnvio = Carbon::createFromFormat('d/m/Y', $interconsulta['interconsulta_fecha_envio[]']);
                $carbonDateRespuesta = Carbon::createFromFormat('d/m/Y', $interconsulta['interconsulta_fecha_respuesta[]']);
                $newKardexInterconsultas = KardexInterconsultas::create([
                    'id_kardex_enfermeria' => $kardexEnfermeriasId,
                    'id_consultorios' => $interconsulta["interconsulta_id_service[]"],
                    'fecha_envio' => $carbonDateEnvio->format('Y-m-d'),
                    'fecha_respuesta' => $carbonDateRespuesta->format('Y-m-d'),
                    'hora_envio' => $interconsulta["interconsulta_hora_envio[]"],
                    'hora_respuesta' => $interconsulta["interconsulta_hora_respuesta[]"]
                ]);
            }
        }

        $kardexFecha = $request->input("kardexFecha");
        $carbonKardexDate = null;
        if ($kardexFecha !== null && !empty($kardexFecha)) {
            $carbonKardexDate = Carbon::createFromFormat('d/m/Y', $kardexFecha);
        }

        $arrayHidratacion = $request->input("arrayHidratacion");
        if (isset($arrayHidratacion) && count($arrayHidratacion) > 0) {
            foreach ($arrayHidratacion as $key => $hidratacion) {
                $frascoHidratacionJson = [
                    'I' => $hidratacion['I'],
                    'II' => $hidratacion['II'],
                    'III' => $hidratacion['III'],
                    'IV' => $hidratacion['IV'],
                    'V' => $hidratacion['V'],
                    'VI' => $hidratacion['VI'],
                ];
                $newKardexMedicamentoEnfermeria = KardexMedicamentoEnfermeria::create([
                    'id_kardex_enfermeria' => $kardexEnfermeriasId,
                    'fecha' => $carbonKardexDate->format('Y-m-d'),
                    'hidratacion' =>  intval($request->input("radioFormMedicamento")),

                    'medicamento' => $hidratacion['medicamento'],
                    'volumen_infusion' => $hidratacion['volumen_infusion'],
                    'id_via_administracion' => $hidratacion['id_via_administracion'],
                    'frasco' => json_encode($frascoHidratacionJson),
                    'hora' => $hidratacion['hora'],
                    'flag_registro' => 0,
                    'created_us' => Auth::user()->id,
                    'created_at' => Carbon::now(),
                ]);
            }
        }

        $arrayInfusion = $request->input("arrayInfusion");
        if (isset($arrayInfusion) && count($arrayInfusion) > 0) {
            foreach ($arrayInfusion as $key => $infusion) {
                $newKardexMedicamentoEnfermeria = KardexMedicamentoEnfermeria::create([
                    'id_kardex_enfermeria' => $kardexEnfermeriasId,
                    'fecha' => $carbonKardexDate->format('Y-m-d'),
                    'hidratacion' =>  intval($request->input("radioFormMedicamento")),

                    'id_infusion' => $infusion['id_infusion'],
                    'volumen_infusion' => $infusion['volumen_infusion'],
                    'id_via_administracion' => $infusion['id_via_administracion'],
                    'hora' => $infusion['hora'],
                    'titulable' => $infusion['titulable'],
                    'flag_registro' => 1,
                    'created_us' => Auth::user()->id,
                    'created_at' => Carbon::now(),
                ]);
            }
        }

        $arrayAntibioticos = $request->input("arrayAntibioticos");
        if (isset($arrayAntibioticos) && count($arrayAntibioticos) > 0) {
            foreach ($arrayAntibioticos as $key => $antibioticos) {
                $newAntibioticos = KardexMedicamentoEnfermeria::create([
                    'id_kardex_enfermeria' => $kardexEnfermeriasId,
                    'fecha' => $carbonKardexDate->format('Y-m-d'),
                    'hidratacion' =>  intval($request->input("radioFormMedicamento")),

                    'medicamento' => $antibioticos['medicamento'],
                    'id_via_administracion' => $antibioticos['id_via_administracion'],
                    'frecuencia' => $antibioticos['frecuencia'],
                    'hora' => $antibioticos['hora'],
                    'dias_administracion' => $antibioticos['dias_administracion'],
                    'flag_registro' => 2,
                    'created_us' => Auth::user()->id,
                    'created_at' => Carbon::now(),
                    'id_medicamento' => $antibioticos['id_medicamento'],
                ]);
                if (isset($antibioticos['horarios']) && count($antibioticos['horarios']) > 0) {
                    foreach ($antibioticos['horarios'] as $indexH => $horario) {
                        $newHorarioAnti = HorarioKardexMedicamento::create([
                            'id_kardex' => $kardexEnfermeriasId,
                            'id_medicamento' => $newAntibioticos->id,
                            'hora' => $horario["hora"],
                            'created_us' => Auth::user()->id,
                            'created_at' => Carbon::now(),
                            'fecha' => $horario["fecha"],
                        ]);
                    }
                }
            }
        }

        $arrayOtros = $request->input("arrayOtros");
        if (isset($arrayOtros) && count($arrayOtros) > 0) {
            foreach ($arrayOtros as $key => $otro) {
                $newOtro = KardexMedicamentoEnfermeria::create([
                    'id_kardex_enfermeria' => $kardexEnfermeriasId,
                    'fecha' => $carbonKardexDate->format('Y-m-d'),
                    'hidratacion' =>  intval($request->input("radioFormMedicamento")),

                    'medicamento' => $otro['medicamento'],
                    'id_via_administracion' => $otro['id_via_administracion'],
                    'condicional' => $otro['condicional'] === true ? 1 : 0,
                    'frecuencia' => $otro['frecuencia'],
                    'hora' => $otro['hora'],
                    'dias_administracion' => $otro['dias_administracion'],
                    'flag_registro' => 3,
                    'created_us' => Auth::user()->id,
                    'created_at' => Carbon::now(),
                ]);
                if (isset($otro['horarios']) && count($otro['horarios']) > 0) {
                    foreach ($otro['horarios'] as $indexHo => $horarioOtro) {
                        $newHorarioOtro = HorarioKardexMedicamento::create([
                            'id_kardex' => $kardexEnfermeriasId,
                            'id_medicamento' => $newOtro->id,
                            'hora' => $horarioOtro["hora"],
                            'created_us' => Auth::user()->id,
                            'created_at' => Carbon::now(),
                            'fecha' => $horarioOtro["fecha"],
                        ]);
                    }
                }
            }
        }

        $arrayInhaladores = $request->input("arrayInhaladores");
        if (isset($arrayInhaladores) && count($arrayInhaladores) > 0) {
            foreach ($arrayInhaladores as $key => $inhaladores) {
                $terapiaRescateJson = [
                    'I' => $inhaladores['I'],
                    'II' => $inhaladores['II'],
                    'III' => $inhaladores['III'],
                    'IV' => $inhaladores['IV'],
                    'V' => $inhaladores['V'],
                    'VI' => $inhaladores['VI'],
                ];
                $newInhalador = KardexMedicamentoEnfermeria::create([
                    'id_kardex_enfermeria' => $kardexEnfermeriasId,
                    'fecha' => $carbonKardexDate->format('Y-m-d'),
                    'hidratacion' =>  intval($request->input("radioFormMedicamento")),

                    'medicamento' => $inhaladores['medicamento'],
                    'id_via_administracion' => $inhaladores['id_via_administracion'],
                    'terapia_rescate' => json_encode($terapiaRescateJson),
                    'frecuencia' => $inhaladores['frecuencia'],
                    'hora' => $inhaladores['hora'],
                    'dias_administracion' => $inhaladores['dias_administracion'],
                    'flag_registro' => 4,
                    'created_us' => Auth::user()->id,
                    'created_at' => Carbon::now(),
                ]);
                if (isset($inhaladores['horarios']) && count($inhaladores['horarios']) > 0) {
                    foreach ($inhaladores['horarios'] as $indexInhalador => $horarioInha) {
                        $newHorarioInhalador = HorarioKardexMedicamento::create([
                            'id_kardex' => $kardexEnfermeriasId,
                            'id_medicamento' => $newInhalador->id,
                            'hora' => $horarioInha["hora"],
                            'created_us' => Auth::user()->id,
                            'created_at' => Carbon::now(),
                            'fecha' => $horarioInha["fecha"],
                        ]);
                    }
                }
            }
        }

        $arrayTranfusiones = $request->input("arrayTranfusiones");
        if (isset($arrayTranfusiones) && count($arrayTranfusiones) > 0) {
            foreach ($arrayTranfusiones as $key => $tranfusiones) {
                $TranfusionesJson = [
                    'I' => $tranfusiones['I'],
                    'II' => $tranfusiones['II'],
                    'III' => $tranfusiones['III'],
                    'IV' => $tranfusiones['IV'],
                    'V' => $tranfusiones['V'],
                    'VI' => $tranfusiones['VI'],
                ];
                $newTranfusiones = KardexMedicamentoEnfermeria::create([
                    'id_kardex_enfermeria' => $kardexEnfermeriasId,
                    'fecha' => $carbonKardexDate->format('Y-m-d'),
                    'hidratacion' =>  intval($request->input("radioFormMedicamento")),

                    'medicamento' => $tranfusiones['medicamento'],
                    'paquetes' => json_encode($TranfusionesJson),
                    'hora' => $tranfusiones['hora'],
                    'flag_registro' => 5,
                    'created_us' => Auth::user()->id,
                    'created_at' => Carbon::now(),
                ]);
            }
        }

        $arrayHemoglucotest = $request->input("arrayHemoglucotest");
        if (isset($arrayHemoglucotest) && count($arrayHemoglucotest) > 0) {
            foreach ($arrayHemoglucotest as $key => $hemoglucotest) {
                $newHemoglucotest = KardexMedicamentoEnfermeria::create([
                    'id_kardex_enfermeria' => $kardexEnfermeriasId,
                    'fecha' => $carbonKardexDate->format('Y-m-d'),
                    'hidratacion' =>  intval($request->input("radioFormMedicamento")),

                    'medicamento' => $hemoglucotest['medicamento'],
                    'frecuencia' => $hemoglucotest['frecuencia'],
                    'dias_administracion' => $hemoglucotest['dias_administracion'],
                    'hora' => $hemoglucotest['hora'],
                    'flag_registro' => 6,
                    'created_us' => Auth::user()->id,
                    'created_at' => Carbon::now(),
                ]);
                if (isset($hemoglucotest['horarios']) && count($hemoglucotest['horarios']) > 0) {
                    foreach ($hemoglucotest['horarios'] as $indexHemo => $horarioHemo) {
                        $newHorarioHemo = HorarioKardexMedicamento::create([
                            'id_kardex' => $kardexEnfermeriasId,
                            'id_medicamento' => $newHemoglucotest->id,
                            'hora' => $horarioHemo["hora"],
                            'created_us' => Auth::user()->id,
                            'created_at' => Carbon::now(),
                            'fecha' => $horarioHemo["fecha"],
                        ]);
                    }
                }
            }
        }
        return response()->json([
            "message" => "Se registro el kardex exitosamente"
        ], 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getById($id)
    {
        $kardexEnfermeria = KardexEnfermerias::join('turnos', 'kardex_enfermerias.id_turno', '=', 'turnos.id')
            ->where('kardex_enfermerias.id', $id)
            ->select(
                'kardex_enfermerias.id',
                'kardex_enfermerias.id_enfermeria',
                'kardex_enfermerias.id_turno',
                'turnos.descripcion as turno_descripcion',
                'kardex_enfermerias.fecha_firma',
                'kardex_enfermerias.ruta_firma',
                'kardex_enfermerias.flag_firma'
            )
            ->first();
        if (!isset($kardexEnfermeria) || !isset($kardexEnfermeria->id)) {
            return response()->json([
                "message" => "No se encontró el kardex enfermería"
            ], 404);
        }

        $examenLaboratorioKardexes = ExamenLaboratorioKardexes::where('id_kardex_enfermeria', $kardexEnfermeria->id)->get();
        $examenImagenKardexes = ExamenImagenKardexes::where('id_kardex_enfermeria', $kardexEnfermeria->id)->get();
        $cuidadoEnfermeros = CuidadoEnfermeros::where('id_kardex_enfermeria', $kardexEnfermeria->id)->first();

        $interconsultas = KardexInterconsultas::join('consultorios', 'kardex_interconsultas.id_consultorios', '=', 'consultorios.id')->select("kardex_interconsultas.*", "consultorios.nombre as consultorio_nombre")->where('kardex_interconsultas.id_kardex_enfermeria', $kardexEnfermeria->id)->get();

        $response = [
            "kardexEnfermeria" => $kardexEnfermeria,
            "examenLaboratorioKardexes" => $examenLaboratorioKardexes,
            "examenImagenKardexes" => $examenImagenKardexes,
            "cuidadoEnfermeros" => $cuidadoEnfermeros,
            "interconsultas" => $interconsultas,
        ];

        return response()->json($response, 200);
    }

    public function getFullById($id)
    {
        $kardex_enfermerias = KardexEnfermerias::select([
            'kardex_enfermerias.id as kardex_enfermerias_id',
            'enfermerias.id as enfermeria_id',
            'emergencias.id as emergencia_id',
            'prioridades.id as prioridad_id',
            'turnos.id as turno_id',
            'prestaciones.id as prestacion_id',
            'kardex_enfermerias.fecha_firma as kardex_enfermerias_fecha_firma',
            'kardex_enfermerias.flag_firma as kardex_enfermerias_flag_firma',
            'kardex_enfermerias.created_at as kardex_enfermerias_created_at',
            'kardex_enfermerias.updated_users as kardex_enfermerias_updated_user',
            'kardex_enfermerias.estado as kardex_enfermerias_estado',
            'turnos.descripcion as turno_nombre',
            'prioridades.descripcion as prioridad_nombre'
        ])
            ->join('enfermerias', 'kardex_enfermerias.id_enfermeria', '=', 'enfermerias.id')
            ->join('emergencias', 'enfermerias.id_ext_enfermeria', '=', 'emergencias.id')
            ->leftJoin('prioridades', 'emergencias.id_prioridad', '=', 'prioridades.id')
            ->join('turnos', 'kardex_enfermerias.id_turno', '=', 'turnos.id')
            ->leftJoin('prestaciones', 'prestaciones.id_ext', '=', 'emergencias.id')
            ->where('kardex_enfermerias.id', $id)
            ->first();

        $examenLaboratorioKardexes = ExamenLaboratorioKardexes::where('id_kardex_enfermeria', $id)->get();
        $examenImagenKardexes = ExamenImagenKardexes::where('id_kardex_enfermeria', $id)->get();
        $cuidadoEnfermeros = CuidadoEnfermeros::join('parametros', 'cuidado_enfermeros.id_oxigeno_terapia', '=', 'parametros.id')
            ->select("cuidado_enfermeros.*", "parametros.descrip_larga as oxigeno_terapia_descripcion")
            ->where('cuidado_enfermeros.id_kardex_enfermeria', $id)
            ->first();
        $interconsultas = KardexInterconsultas::join('consultorios', 'kardex_interconsultas.id_consultorios', '=', 'consultorios.id')->select("kardex_interconsultas.*", "consultorios.nombre as consultorio_nombre")->where('kardex_interconsultas.id_kardex_enfermeria', $id)->get();
        $kardex_medicamento_enfermeria = KardexMedicamentoEnfermeria::select(
            'kardex_medicamentos_enfermerias.*',
            'infusiones_enfermerias.descripcion as infusion_descripcion',
            'via_administracion.id as id_via_administracion',
            'via_administracion.descripcion as via_administracion_descripcion'
        )
            ->leftJoin('infusiones_enfermerias', 'kardex_medicamentos_enfermerias.id_infusion', '=', 'infusiones_enfermerias.id')
            ->leftJoin('via_administracion', 'kardex_medicamentos_enfermerias.id_via_administracion', '=', 'via_administracion.id')
            ->where('kardex_medicamentos_enfermerias.id_kardex_enfermeria', $id)
            ->get();

        if (isset($kardex_medicamento_enfermeria) && count($kardex_medicamento_enfermeria) > 0) {
            $kardex_medicamento_enfermeria = $kardex_medicamento_enfermeria->map(function ($item) {
                $item->horarios = HorarioKardexMedicamento::where("id_medicamento", $item->id)->orderBy('id', 'ASC')->get();
                return $item;
            });
        }

        $kardex_enfermerias->examenesLaboratorio = $examenLaboratorioKardexes;
        $kardex_enfermerias->examenesImagen = $examenImagenKardexes;
        $kardex_enfermerias->cuidadoEnfermeros = $cuidadoEnfermeros;
        $kardex_enfermerias->interconsultas = $interconsultas;
        $kardex_enfermerias->medicamentos = $kardex_medicamento_enfermeria;

        return response()->json($kardex_enfermerias, 200);
    }
    public function getFullByIdHospi($id)
    {
        $kardex_enfermerias = KardexEnfermerias::select([
            'kardex_enfermerias.id as kardex_enfermerias_id',
            'enfermerias.id as enfermeria_id',
            'hospitalizaciones.id as hospitalizacion_id',
            'pabellones.id as pabellon_id',
            'turnos.id as turno_id',
            'prestaciones.id as prestacion_id',
            'kardex_enfermerias.fecha_firma as kardex_enfermerias_fecha_firma',
            'kardex_enfermerias.flag_firma as kardex_enfermerias_flag_firma',
            'kardex_enfermerias.created_at as kardex_enfermerias_created_at',
            'kardex_enfermerias.updated_users as kardex_enfermerias_updated_user',
            'kardex_enfermerias.estado as kardex_enfermerias_estado',
            'turnos.descripcion as turno_nombre',
            'pabellones.nombre as pabellon_nombre',
            'consultorios.nombre as consultorio_nombre',
        ])
            ->join('enfermerias', 'kardex_enfermerias.id_enfermeria', '=', 'enfermerias.id')
            ->join('hospitalizaciones', 'enfermerias.id_ext_enfermeria', '=', 'hospitalizaciones.id')
            ->leftJoin('pabellones', 'hospitalizaciones.id_pabellon', '=', 'pabellones.id')
            ->leftJoin('consultorios', 'hospitalizaciones.id_consultorio', '=', 'consultorios.id')
            ->join('turnos', 'kardex_enfermerias.id_turno', '=', 'turnos.id')
            ->leftJoin('prestaciones', function ($join) {
                $join->on('hospitalizaciones.id', '=', 'prestaciones.id_ext')
                    ->where('prestaciones.id_tipo_atencion', '=', 3);
            })
            ->where('kardex_enfermerias.id', $id)
            ->first();

        $examenLaboratorioKardexes = ExamenLaboratorioKardexes::where('id_kardex_enfermeria', $id)->get();
        $examenImagenKardexes = ExamenImagenKardexes::where('id_kardex_enfermeria', $id)->get();
        $cuidadoEnfermeros = CuidadoEnfermeros::join('parametros', 'cuidado_enfermeros.id_oxigeno_terapia', '=', 'parametros.id')
            ->select("cuidado_enfermeros.*", "parametros.descrip_larga as oxigeno_terapia_descripcion")
            ->where('cuidado_enfermeros.id_kardex_enfermeria', $id)
            ->first();
        $interconsultas = KardexInterconsultas::join('consultorios', 'kardex_interconsultas.id_consultorios', '=', 'consultorios.id')->select("kardex_interconsultas.*", "consultorios.nombre as consultorio_nombre")->where('kardex_interconsultas.id_kardex_enfermeria', $id)->get();
        $kardex_medicamento_enfermeria = KardexMedicamentoEnfermeria::select(
            'kardex_medicamentos_enfermerias.*',
            'infusiones_enfermerias.descripcion as infusion_descripcion',
            'via_administracion.id as id_via_administracion',
            'via_administracion.descripcion as via_administracion_descripcion'
        )
            ->leftJoin('infusiones_enfermerias', 'kardex_medicamentos_enfermerias.id_infusion', '=', 'infusiones_enfermerias.id')
            ->leftJoin('via_administracion', 'kardex_medicamentos_enfermerias.id_via_administracion', '=', 'via_administracion.id')
            ->where('kardex_medicamentos_enfermerias.id_kardex_enfermeria', $id)
            ->get();

        if (isset($kardex_medicamento_enfermeria) && count($kardex_medicamento_enfermeria) > 0) {
            $kardex_medicamento_enfermeria = $kardex_medicamento_enfermeria->map(function ($item) {
                $item->horarios = HorarioKardexMedicamento::where("id_medicamento", $item->id)->orderBy('id', 'ASC')->get();
                return $item;
            });
        }

        $kardex_enfermerias->examenesLaboratorio = $examenLaboratorioKardexes;
        $kardex_enfermerias->examenesImagen = $examenImagenKardexes;
        $kardex_enfermerias->cuidadoEnfermeros = $cuidadoEnfermeros;
        $kardex_enfermerias->interconsultas = $interconsultas;
        $kardex_enfermerias->medicamentos = $kardex_medicamento_enfermeria;

        return response()->json($kardex_enfermerias, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $enfermeria = Enfermeria::where("id_ext_enfermeria", "=", $request->input('emergenciaId'))->first();
        if (!isset($enfermeria)) {
            return response()->json([
                "message" => "No existe registro de la enfermeria"
            ], 404);
        }
        $kardexUpdated = KardexEnfermerias::where("id", "=", $id)->first();
        if (!isset($kardexUpdated)) {
            return response()->json([
                "message" => "No existe registro de la kardex"
            ], 404);
        }

        $updatedUsersJson = [];
        if (is_null($kardexUpdated->updated_users)) {
            $updatedUsersJson = [["updated_us" => Auth::user()->id, "updated_at" => Carbon::now()->format('Y-m-d H:i:s')]];
        } else {
            $updatedUsersJson = json_decode($kardexUpdated->updated_users, true);
            $updatedUsersJson[] = ["updated_us" => Auth::user()->id, "updated_at" => Carbon::now()->format('Y-m-d H:i:s')];
        }
        $kardexUpdated->id_enfermeria = $enfermeria->id;
        $kardexUpdated->id_turno = $request->input('turnoId');
        $kardexUpdated->updated_users = json_encode($updatedUsersJson);
        $kardexUpdated->save();

        $arrayLaboratorio = $request->input("arrayLaboratorio");
        if (is_array($arrayLaboratorio) && count($arrayLaboratorio) > 0) {
            foreach ($arrayLaboratorio as $key => $laboratorioItem) {
                $carbonDateLab = Carbon::createFromFormat('d/m/Y', $laboratorioItem['exam_aux_lab_fecha[]']);

                if (isset($laboratorioItem['exam_aux_lab_id[]']) && intval($laboratorioItem['exam_aux_lab_id[]']) > 0) {
                    $updateExamenLab = ExamenLaboratorioKardexes::find($laboratorioItem["exam_aux_lab_id[]"]);
                    $updateExamenLab->update([
                        'nombre_procedimiento' => $laboratorioItem['exam_aux_lab_nombre[]'],
                        'toma_muestra' => $laboratorioItem['exam_aux_lab_muestra[]'] === true ? 1 : 0,
                        'resultado' => $laboratorioItem['exam_aux_lab_result[]'] === true ? 1 : 0,
                        'fecha' => $carbonDateLab->format('Y-m-d'),
                        'hora' => $laboratorioItem['exam_aux_lab_hora[]'],
                        'reactivo' => $laboratorioItem['exam_aux_lab_reactive[]'] === true ? 1 : 0,
                        'id_procedimiento' => $laboratorioItem['id_procedimiento'],
                    ]);
                } else {
                    $newExamLab = ExamenLaboratorioKardexes::create([
                        'id_kardex_enfermeria' => $id,
                        'nombre_procedimiento' => $laboratorioItem['exam_aux_lab_nombre[]'],
                        'toma_muestra' => $laboratorioItem['exam_aux_lab_muestra[]'] === true ? 1 : 0,
                        'resultado' => $laboratorioItem['exam_aux_lab_result[]'] === true ? 1 : 0,
                        'fecha' => $carbonDateLab->format('Y-m-d'),
                        'hora' => $laboratorioItem['exam_aux_lab_hora[]'],
                        'reactivo' => $laboratorioItem['exam_aux_lab_reactive[]'] === true ? 1 : 0,
                        'id_procedimiento' => $laboratorioItem['id_procedimiento'],
                    ]);
                }
            }
        }

        $arrayImagenologia = $request->input("arrayImagenologia");
        if (is_array($arrayImagenologia) && count($arrayImagenologia) > 0) {
            foreach ($arrayImagenologia as $key => $imagenItem) {
                $carbonDateImage = Carbon::createFromFormat('d/m/Y', $imagenItem['exam_aux_imag_fecha[]']);
                if (isset($imagenItem['exam_aux_imag_id[]']) && intval($imagenItem['exam_aux_imag_id[]']) > 0) {
                    $updateExamenImag = ExamenImagenKardexes::find($imagenItem["exam_aux_imag_id[]"]);
                    $updateExamenImag->update([
                        'nombre_imagen' => $imagenItem['exam_aux_imag_nombre[]'],
                        'toma_imagen' => $imagenItem['exam_aux_imag_imagen[]'] === true ? 1 : 0,
                        'fecha' => $carbonDateImage->format('Y-m-d'),
                        'hora' => $imagenItem['exam_aux_imag_hora[]'],
                        'id_procedimiento' => $imagenItem['id_procedimiento'],
                    ]);
                } else {
                    $newExamImagen = ExamenImagenKardexes::create([
                        'id_kardex_enfermeria' => $id,
                        'nombre_imagen' => $imagenItem['exam_aux_imag_nombre[]'],
                        'toma_imagen' => $imagenItem['exam_aux_imag_imagen[]'] === true ? 1 : 0,
                        'fecha' => $carbonDateImage->format('Y-m-d'),
                        'hora' => $imagenItem['exam_aux_imag_hora[]'],
                        'id_procedimiento' => $imagenItem['id_procedimiento'],
                    ]);
                }
            }
        }

        $cuidateEnfermosUpdate = CuidadoEnfermeros::find($request->input("cuidadoEnfermerosId"));
        $cuidateEnfermosUpdate->update([
            'cfv' => $request->input("controlFuncionesVitales") === true ? 1 : 0,
            'bhe' => $request->input("barreraHematoencefalica") === true ? 1 : 0,
            /* 'cabecera' => $request->input("cabecera"), */
            'cabecera' => $request->input('cabecera') === '' ||
              $request->input('cabecera') === null
                ? null
                : (int) $request->input('cabecera'),
            'id_oxigeno_terapia' => $request->input("oxigenoTerapiaId"),
            'oxigenoterapia_cantidad' => !empty($request->input("oxigenoterapiaCantidad")) ? $request->input("oxigenoterapiaCantidad") : null,
            'movilizacion' => $request->input("movilización") === true ? 1 : 0,
            /* 'hemoglucotest' => $request->input("hemoglucotest") */
            'hemoglucotest' => json_encode($request->input("hemoglucotest")),
        ]);


        $arrayInterconsulta = $request->input("arrayInterconsulta");
        if (is_array($arrayInterconsulta) && count($arrayInterconsulta) > 0) {
            foreach ($arrayInterconsulta as $index => $interconsulta) {
                $carbonDateEnvio = Carbon::createFromFormat('d/m/Y', $interconsulta['interconsulta_fecha_envio[]']);
                $carbonDateRespuesta = Carbon::createFromFormat('d/m/Y', $interconsulta['interconsulta_fecha_respuesta[]']);
                if (isset($interconsulta['interconsulta_id[]']) && intval($interconsulta['interconsulta_id[]']) > 0) {
                    $interconsultasUpdate = KardexInterconsultas::find($interconsulta['interconsulta_id[]']);
                    $interconsultasUpdate->update([
                        'id_consultorios' => $interconsulta["interconsulta_id_service[]"],
                        'fecha_envio' => $carbonDateEnvio->format('Y-m-d'),
                        'fecha_respuesta' => $carbonDateRespuesta->format('Y-m-d'),
                        'hora_envio' => $interconsulta["interconsulta_hora_envio[]"],
                        'hora_respuesta' => $interconsulta["interconsulta_hora_respuesta[]"]
                    ]);
                } else {
                    $newKardexInterconsultas = KardexInterconsultas::create([
                        'id_kardex_enfermeria' => $id,
                        'id_consultorios' => $interconsulta["interconsulta_id_service[]"],
                        'fecha_envio' => $carbonDateEnvio->format('Y-m-d'),
                        'fecha_respuesta' => $carbonDateRespuesta->format('Y-m-d'),
                        'hora_envio' => $interconsulta["interconsulta_hora_envio[]"],
                        'hora_respuesta' => $interconsulta["interconsulta_hora_respuesta[]"]
                    ]);
                }
            }
        }
        return response()->json([
            "message" => "Se registro el kardex exitosamente"
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateEstado(Request $request, $id)
    {
        $kardexEnfermeriasUpdated = KardexEnfermerias::find($id);
        if (!isset($kardexEnfermeriasUpdated)) {
            return response()->json([
                "message" => "No existe registro de la kardex"
            ], 404);
        }

        $kardexEnfermeriasUpdated->estado = $request->input('estado');
        $kardexEnfermeriasUpdated->save();

        return response()->json([
            "message" => "Se actualizo el estado del kardex exitosamente"
        ], 200);
    }

    public function generatePdfKardexEnfermeriaFirmar($id, $id_tipo_atencion)
    {
        $path = public_path('tmp/pdf');
        if (!is_dir($path)) {
            mkdir($path);
        }
        $path = public_path('tmp/pdf/sin_firma/');
        if (!is_dir($path)) {
            mkdir($path);
        }
        $kardexEnfermeria = KardexEnfermerias::where("id", $id)->first();
        $enfermeriaModel = new Enfermeria;
        $model_receta_diagnostico = new Receta_diagnostico;
        $diagnosticos = null;
        $enfermeria = null;
        if ($id_tipo_atencion == 2) {
            $enfermeria = $enfermeriaModel->ObtenerCabeceraPdfById_sa($kardexEnfermeria->id_enfermeria);
            $diagnosticos = $model_receta_diagnostico->DiagnosticosByEmergencia_Enfermeria($enfermeria->id_emergencia);
        } else {
            $enfermeria = $enfermeriaModel->ObtenerCabeceraPdfByIdHospi($kardexEnfermeria->id_enfermeria);
            $diagnosticos = $model_receta_diagnostico->DiagnosticosByHospitalizacion_Enfermeria($enfermeria->id_hospitalizacion);
        }
        $examenLaboratorioKardexes = ExamenLaboratorioKardexes::where('id_kardex_enfermeria', $id)->get();
        $examenImagenKardexes = ExamenImagenKardexes::where('id_kardex_enfermeria', $id)->get();
        $cuidadoEnfermeros = CuidadoEnfermeros::join('parametros', 'cuidado_enfermeros.id_oxigeno_terapia', '=', 'parametros.id')
            ->select("cuidado_enfermeros.*", "parametros.descrip_larga as oxigeno_terapia_descripcion")
            ->where('cuidado_enfermeros.id_kardex_enfermeria', $id)
            ->first();
        $interconsultas = KardexInterconsultas::join('consultorios', 'kardex_interconsultas.id_consultorios', '=', 'consultorios.id')->select("kardex_interconsultas.*", "consultorios.nombre as consultorio_nombre")->where('kardex_interconsultas.id_kardex_enfermeria', $id)->get();
        $kardex_medicamento_enfermeria = KardexMedicamentoEnfermeria::select(
            'kardex_medicamentos_enfermerias.*',
            'infusiones_enfermerias.descripcion as infusion_descripcion',
            'via_administracion.id as id_via_administracion',
            'via_administracion.descripcion as via_administracion_descripcion'
        )
            ->leftJoin('infusiones_enfermerias', 'kardex_medicamentos_enfermerias.id_infusion', '=', 'infusiones_enfermerias.id')
            ->leftJoin('via_administracion', 'kardex_medicamentos_enfermerias.id_via_administracion', '=', 'via_administracion.id')
            ->where('kardex_medicamentos_enfermerias.id_kardex_enfermeria', $id)
            ->get();


        if (isset($kardex_medicamento_enfermeria) && count($kardex_medicamento_enfermeria) > 0) {
            $kardex_medicamento_enfermeria = $kardex_medicamento_enfermeria->map(function ($item) {
                $item->horarios = HorarioKardexMedicamento::where("id_medicamento", $item->id)->orderBy('id', 'ASC')->get();
                return $item;
            });
        }

        $carpeta = date("Y") . "/" . date("m") . "/" . date("d");
        $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/" . $carpeta . "/cf_kardex_enfermeria_" . $id . ".pdf";
        $codigo_qr = QrCode::format('png')->size(150)->generate("https://sigesapol.dirsapolpnp.gob.pe/" . $carpeta . "/cf_kardex_enfermeria_" . $id . ".pdf");
        $pdf = \PDF::loadView('pdf.firma_kardex_enfermeria', compact("kardexEnfermeria", 'id_tipo_atencion', "enfermeria", "diagnosticos", "examenLaboratorioKardexes", "examenImagenKardexes", "cuidadoEnfermeros", "interconsultas", "kardex_medicamento_enfermeria", "url_descarga", "codigo_qr"));
        $pdf->setPaper('A4', 'portrait');
        $pdf->getDomPDF()->set_option("enable_php", true);
        file_put_contents($path . "kardex_enfermeria_" . $id . ".pdf", $pdf->output());
    }

    public function firmarPdf($kardex_enfermeria_id, $fecha_hora, $id_tipo_atencion = 2)
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
        $csv .= "_" . date("YmdHis");
        Excel::create($csv, function ($excel) use ($kardex_enfermeria_id, $id_tipo_atencion) {
            $excel->sheet('GENERAL', function ($sheet) use ($kardex_enfermeria_id, $id_tipo_atencion) {
                $variable = [];
                $this->generatePdfKardexEnfermeriaFirmar($kardex_enfermeria_id, $id_tipo_atencion);
                array_push($variable, array(url() . '/tmp/pdf/sin_firma/kardex_enfermeria_' . $kardex_enfermeria_id . '.pdf', url() . '/upload_firma/?name=cf_kardex_enfermeria_' . $kardex_enfermeria_id . '.pdf&id_tipo=kardex_enfermeria&id_resol=' . $kardex_enfermeria_id, 'cf_kardex_enfermeria_' . $kardex_enfermeria_id . '.pdf', '0', '45', '350', '70', 'Fecha de Firma: <DATE> Firmante: <SIGNER>', url() . '/images/escudo.jpg', '1', '7'));
                $sheet->fromArray($variable, null, '', false, false);
            });
        })->store('csv', public_path('tmp/csv'));
        $obj = new Archive7z(public_path('tmp/zip/sin_firma/' . $csv . '.7z'));
        $obj->addEntry(public_path('/tmp/pdf/sin_firma/kardex_enfermeria_' . $kardex_enfermeria_id . '.pdf'));
        $resultado["csv"] = $csv;
        $resultado["sw"] = true;
        return response()->json($resultado);
    }

    public function getDetalleKardex($id, $tipo_atencion_id)
    {
        $kardex_enfermerias = KardexEnfermerias::select([
            'kardex_enfermerias.id as kardex_enfermerias_id',
            'enfermerias.id as enfermeria_id',
            'turnos.id as turno_id',
            'prestaciones.id as prestacion_id',
            'kardex_enfermerias.fecha_firma as kardex_enfermerias_fecha_firma',
            'kardex_enfermerias.flag_firma as kardex_enfermerias_flag_firma',
            'kardex_enfermerias.created_at as kardex_enfermerias_created_at',
            'kardex_enfermerias.updated_users as kardex_enfermerias_updated_user',
            'kardex_enfermerias.estado as kardex_enfermerias_estado',
            'turnos.descripcion as turno_nombre',
            /* DB::raw("
                CASE 
                    WHEN $tipo_atencion_id = 2 THEN ext.id
                    WHEN $tipo_atencion_id = 3 THEN hospitalizaciones.id
                    ELSE NULL
                END AS id_ext
            "), */
            /* DB::raw("
                CASE 
                    WHEN $tipo_atencion_id = 2 THEN prioridades.id
                    ELSE NULL
                END AS prioridad_id
            "),
            DB::raw("
                CASE 
                    WHEN $tipo_atencion_id = 2 THEN prioridades.descripcion
                    ELSE NULL
                END AS prioridad_nombre
            "), */
            /* DB::raw("
                CASE 
                    WHEN $tipo_atencion_id = 3 THEN pabellones.id
                    ELSE NULL
                END AS pabellon_id
            "),
            DB::raw("
                CASE 
                    WHEN $tipo_atencion_id = 3 THEN pabellones.nombre
                    ELSE NULL
                END AS pabellon_nombre
            "),
            DB::raw("
                CASE 
                    WHEN $tipo_atencion_id = 3 THEN consultorios.nombre
                    ELSE NULL
                END AS consultorio_nombre
            "), */
        ])
            ->join('enfermerias', 'kardex_enfermerias.id_enfermeria', '=', 'enfermerias.id');
        if ($tipo_atencion_id == 2) {
            // Si es atención de emergencia
            $kardex_enfermerias->join('emergencias as ext', 'ext.id', '=', 'enfermerias.id_ext_enfermeria')
                ->addSelect('ext.id as id_ext')
                ->leftJoin('prioridades', 'ext.id_prioridad', '=', 'prioridades.id')
                ->addSelect('prioridades.id as prioridad_id')
                ->addSelect('prioridades.descripcion as prioridad_nombre')
                ->join('camas as c2', 'ext.cama_estado', '=', 'c2.id')
                ->join('cuartos as c3', 'c2.id_cuarto', '=', 'c3.id')
                ->leftJoin('triajes as t', 'ext.id_triaje', '=', 't.id')
                ->leftJoin('sub_consultorios as sc', 't.id_sub_consultorio', '=', 'sc.id')
                ->leftJoin('consultorios as c', 'sc.id_consultorio', '=', 'c.id')
                ->join('asegurados as aseg', 'aseg.id', '=', 'ext.id_asegurado')
                ->leftJoin('prestaciones', function ($join) {
                    $join->on('ext.id', '=', 'prestaciones.id_ext')
                        ->where('prestaciones.id_tipo_atencion', '=', 2);
                });
        } else if ($tipo_atencion_id == 3) {
            $kardex_enfermerias->join('hospitalizaciones', 'enfermerias.id_ext_enfermeria', '=', 'hospitalizaciones.id')
                ->addSelect('hospitalizaciones.id as id_ext')
                ->leftJoin('pabellones', 'hospitalizaciones.id_pabellon', '=', 'pabellones.id')
                ->addSelect('pabellones.id as pabellon_id')
                ->addSelect('pabellones.nombre as pabellon_nombre')
                ->leftJoin('consultorios', 'hospitalizaciones.id_consultorio', '=', 'consultorios.id')
                ->addSelect('consultorios.nombre as consultorio_nombre')
                ->leftJoin('prestaciones', function ($join) {
                    $join->on('hospitalizaciones.id', '=', 'prestaciones.id_ext')
                        ->where('prestaciones.id_tipo_atencion', '=', 3);
                });
        }
        $kardex_enfermerias->join('turnos', 'kardex_enfermerias.id_turno', '=', 'turnos.id')
            ->where('kardex_enfermerias.id', $id)
            ->first();

        $examenLaboratorioKardexes = ExamenLaboratorioKardexes::where('id_kardex_enfermeria', $id)->get();
        $examenImagenKardexes = ExamenImagenKardexes::where('id_kardex_enfermeria', $id)->get();
        $cuidadoEnfermeros = CuidadoEnfermeros::join('parametros', 'cuidado_enfermeros.id_oxigeno_terapia', '=', 'parametros.id')
            ->select("cuidado_enfermeros.*", "parametros.descrip_larga as oxigeno_terapia_descripcion")
            ->where('cuidado_enfermeros.id_kardex_enfermeria', $id)
            ->first();
        $interconsultas = KardexInterconsultas::join('consultorios', 'kardex_interconsultas.id_consultorios', '=', 'consultorios.id')->select("kardex_interconsultas.*", "consultorios.nombre as consultorio_nombre")->where('kardex_interconsultas.id_kardex_enfermeria', $id)->get();
        $kardex_medicamento_enfermeria = KardexMedicamentoEnfermeria::select(
            'kardex_medicamentos_enfermerias.*',
            'infusiones_enfermerias.descripcion as infusion_descripcion',
            'via_administracion.id as id_via_administracion',
            'via_administracion.descripcion as via_administracion_descripcion'
        )
            ->leftJoin('infusiones_enfermerias', 'kardex_medicamentos_enfermerias.id_infusion', '=', 'infusiones_enfermerias.id')
            ->leftJoin('via_administracion', 'kardex_medicamentos_enfermerias.id_via_administracion', '=', 'via_administracion.id')
            ->where('kardex_medicamentos_enfermerias.id_kardex_enfermeria', $id)
            ->get();

        if (isset($kardex_medicamento_enfermeria) && count($kardex_medicamento_enfermeria) > 0) {
            $kardex_medicamento_enfermeria = $kardex_medicamento_enfermeria->map(function ($item) {
                $item->horarios = HorarioKardexMedicamento::where("id_medicamento", $item->id)->orderBy('id', 'ASC')->get();
                return $item;
            });
        }

        $kardex_enfermerias->examenesLaboratorio = $examenLaboratorioKardexes;
        $kardex_enfermerias->examenesImagen = $examenImagenKardexes;
        $kardex_enfermerias->cuidadoEnfermeros = $cuidadoEnfermeros;
        $kardex_enfermerias->interconsultas = $interconsultas;
        $kardex_enfermerias->medicamentos = $kardex_medicamento_enfermeria;

        return response()->json($kardex_enfermerias, 200);
    }
}
