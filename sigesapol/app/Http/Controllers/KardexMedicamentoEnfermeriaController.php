<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\HorarioKardexMedicamento;
use App\Models\KardexMedicamentoEnfermeria;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class KardexMedicamentoEnfermeriaController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('auth'); 
    } 
    
    public function getByidKardexEnfermeria($id_kardex_enfermeria)
    {
        $kardex_medicamento_enfermeria = KardexMedicamentoEnfermeria::leftJoin('via_administracion', 'kardex_medicamentos_enfermerias.id_via_administracion', '=', 'via_administracion.id')
                                                ->join('users', 'kardex_medicamentos_enfermerias.created_us', '=', 'users.id')
                                                ->leftJoin('infusiones_enfermerias', 'kardex_medicamentos_enfermerias.id_infusion', '=', 'infusiones_enfermerias.id')
                                                ->where('kardex_medicamentos_enfermerias.id_kardex_enfermeria', $id_kardex_enfermeria)
                                                ->select(
                                                    'kardex_medicamentos_enfermerias.*', 
                                                    'via_administracion.descripcion',
                                                    'users.dni as usuario_dni',
                                                    'users.name as usuario_nombre',
                                                    'users.paterno as usuario_paterno',
                                                    'users.materno as usuario_materno',
                                                    'infusiones_enfermerias.descripcion as infusiones_descripcion'
                                                )
                                                ->orderBy('kardex_medicamentos_enfermerias.id', 'asc')
                                                ->get();
        
        $kardex_medicamento_enfermeria = $kardex_medicamento_enfermeria->map(function ($item) {
            $item->horarios = HorarioKardexMedicamento::where("id_medicamento", $item->id)->orderBy('id', 'ASC')->get();
            return $item;
        });

        return response()->json($kardex_medicamento_enfermeria);
    }

    public function updateArray(Request $request)
    {
        $kardexEnfermeriasId = $request->input('id_kardex_enfermeria');
        $kardexFecha = $request->input("kardexFecha");
        $carbonKardexDate = null;
        if ($kardexFecha !== null && !empty($kardexFecha))  {
            $carbonKardexDate = Carbon::createFromFormat('d/m/Y', $kardexFecha);
        }

        $arrayHidratacion = $request->input('arrayHidratacion');
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
                if (isset($hidratacion['id']) && intval($hidratacion['id']) > 0) {
                    $kardexMedicamentoUpdate = KardexMedicamentoEnfermeria::find($hidratacion['id']);
                    $kardexMedicamentoUpdate->update([
                        'fecha' => $carbonKardexDate->format('Y-m-d'),
                        'hidratacion' =>  intval($request->input("radioFormMedicamento")),
    
                        'medicamento' => $hidratacion['medicamento'],
                        'volumen_infusion' => $hidratacion['volumen_infusion'],
                        'id_via_administracion' => $hidratacion['id_via_administracion'],
                        'frasco' => json_encode($frascoHidratacionJson),
                        'hora' => $hidratacion['hora'],
                    ]);

                } else {
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
        }

        $arrayInfusion = $request->input('arrayInfusion');
        if (isset($arrayInfusion) && count($arrayInfusion) > 0) {
            foreach ($arrayInfusion as $key => $infusion) {
                if (isset($infusion['id']) && intval($infusion['id']) > 0) {
                    $kardexMedicamentoUpdate = KardexMedicamentoEnfermeria::find($infusion['id']);
                    $kardexMedicamentoUpdate->update([
                        'fecha' => $carbonKardexDate->format('Y-m-d'),
                        'hidratacion' =>  intval($request->input("radioFormMedicamento")),
    
                        'id_infusion' => $infusion['id_infusion'],
                        'medicamento' => $infusion['infusiones_descripcion'],
                        'volumen_infusion' => $infusion['volumen_infusion'],
                        'id_via_administracion' => $infusion['id_via_administracion'],
                        'hora' => $infusion['hora'],
                        'titulable' => $infusion['titulable'], 
                    ]);

                } else {
                    $newKardexMedicamentoEnfermeria = KardexMedicamentoEnfermeria::create([
                        'id_kardex_enfermeria' => $kardexEnfermeriasId,
                        'fecha' => $carbonKardexDate->format('Y-m-d'),
                        'hidratacion' =>  intval($request->input("radioFormMedicamento")),
    
                        'id_infusion' => $infusion['id_infusion'],
                        'medicamento' => $infusion['infusiones_descripcion'],
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
        }

        $arrayAntibioticos = $request->input('arrayAntibioticos');
        if (isset($arrayAntibioticos) && count($arrayAntibioticos) > 0) {
            foreach ($arrayAntibioticos as $key => $antibiotico) {
                if (isset($antibiotico['id']) && intval($antibiotico['id']) > 0) {
                    $kardexMedicamentoUpdate = KardexMedicamentoEnfermeria::find($antibiotico['id']);
                    $kardexMedicamentoUpdate->update([
                        'fecha' => $carbonKardexDate->format('Y-m-d'),
                        'hidratacion' =>  intval($request->input("radioFormMedicamento")),
                        
                        'medicamento' => $antibiotico['medicamento'],
                        'id_via_administracion' => $antibiotico['id_via_administracion'],
                        'frecuencia' => $antibiotico['frecuencia'],
                        'hora' => $antibiotico['hora'],
                        'dias_administracion' => $antibiotico['dias_administracion'],
                        'id_medicamento' => $antibiotico['id_medicamento'],
                    ]);
                } else {
                    $newAntibioticos = KardexMedicamentoEnfermeria::create([
                        'id_kardex_enfermeria' => $kardexEnfermeriasId,
                        'fecha' => $carbonKardexDate->format('Y-m-d'),
                        'hidratacion' =>  intval($request->input("radioFormMedicamento")),
                        
                        'medicamento' => $antibiotico['medicamento'],
                        'id_via_administracion' => $antibiotico['id_via_administracion'],
                        'frecuencia' => $antibiotico['frecuencia'],
                        'hora' => $antibiotico['hora'],
                        'dias_administracion' => $antibiotico['dias_administracion'],
                        'flag_registro' => 2,
                        'created_us' => Auth::user()->id,
                        'created_at' => Carbon::now(),
                        'id_medicamento' => $antibiotico['id_medicamento'],
                    ]);
                    if (isset($antibiotico['horarios']) && count($antibiotico['horarios']) > 0) {
                        foreach ($antibiotico['horarios'] as $indexH => $horario) {
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
        }

        $arrayOtros = $request->input("arrayOtros");
        if (isset($arrayOtros) && count($arrayOtros) > 0) {
            foreach ($arrayOtros as $key => $otro) {
                if (isset($otro["id"]) && intval($otro["id"]) > 0) {
                    $kardexMedicamentoUpdate = KardexMedicamentoEnfermeria::find($otro["id"]);
                    $kardexMedicamentoUpdate->update([
                        'fecha' => $carbonKardexDate->format('Y-m-d'),
                        'hidratacion' =>  intval($request->input("radioFormMedicamento")),
                        
                        'medicamento' => $otro['medicamento'],
                        'id_via_administracion' => $otro['id_via_administracion'],
                        'condicional' => $otro['condicional'] === true ? 1 : 0,
                        'frecuencia' => $otro['frecuencia'],
                        'hora' => $otro['hora'],
                        'dias_administracion' => $otro['dias_administracion'],
                    ]);
                } else {
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
                if (isset($inhaladores['id']) && intval($inhaladores['id']) > 0) {
                    $kardexMedicamentoUpdate = KardexMedicamentoEnfermeria::find($inhaladores['id']);
                    $kardexMedicamentoUpdate->update([
                        'fecha' => $carbonKardexDate->format('Y-m-d'),
                        'hidratacion' =>  intval($request->input("radioFormMedicamento")),
                        
                        'medicamento' => $inhaladores['medicamento'],
                        'id_via_administracion' => $inhaladores['id_via_administracion'],
                        'terapia_rescate' => json_encode($terapiaRescateJson),
                        'frecuencia' => $inhaladores['frecuencia'],
                        'hora' => $inhaladores['hora'],
                        'dias_administracion' => $inhaladores['dias_administracion'],
                    ]);
                } else {
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
                if (isset($tranfusiones['id']) && intval($tranfusiones['id']) > 0) {
                    $kardexMedicamentoUpdate = KardexMedicamentoEnfermeria::find($tranfusiones['id']);
                    $kardexMedicamentoUpdate->update([
                        'fecha' => $carbonKardexDate->format('Y-m-d'),
                        'hidratacion' =>  intval($request->input("radioFormMedicamento")),
                        
                        'medicamento' => $tranfusiones['medicamento'],
                        'paquetes' => json_encode($TranfusionesJson),
                        'hora' => $tranfusiones['hora'],
                    ]);
                } else {        
                    $newTranfusiones= KardexMedicamentoEnfermeria::create([
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
        }

        $arrayHemoglucotest = $request->input("arrayHemoglucotest");
        if (isset($arrayHemoglucotest) && count($arrayHemoglucotest) > 0) {
            foreach ($arrayHemoglucotest as $key => $hemoglucotest) {
                if (isset($hemoglucotest["id"]) && intval($hemoglucotest["id"]) > 0) {
                    $kardexMedicamentoUpdate = KardexMedicamentoEnfermeria::find($hemoglucotest["id"]);
                    $kardexMedicamentoUpdate->update([
                        'fecha' => $carbonKardexDate->format('Y-m-d'),
                        'hidratacion' =>  intval($request->input("radioFormMedicamento")),
                        
                        'medicamento' => $hemoglucotest['medicamento'],
                        'frecuencia' => $hemoglucotest['frecuencia'],
                        'dias_administracion' => $hemoglucotest['dias_administracion'],
                        'hora' => $hemoglucotest['hora'],
                    ]);
                } else {
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
        }

        return response()->json([
            "message" => "Se registro el kardex exitosamente"
        ], 200);
    }

    public function changeState(Request $request, $id) {
        $kardexMedicamentoEnfermeria = KardexMedicamentoEnfermeria::find($id);
        $kardexMedicamentoEnfermeria->update([
            "estado" => $request->input('estado'),
        ]);

        return response()->json([
            "message" => "Se cambió el estado del kardex exitosamente"
        ], 200);
    }
}