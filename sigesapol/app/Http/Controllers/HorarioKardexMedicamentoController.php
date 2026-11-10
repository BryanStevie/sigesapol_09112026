<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\HorarioKardexMedicamento;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class HorarioKardexMedicamentoController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('auth'); 
    }
    
    public function getByMedicamentoId($id_medicamento)
    {
        $results = HorarioKardexMedicamento::where('id_medicamento', $id_medicamento)->orderBy('id', 'ASC')->get();
        return response()->json($results);
    }

    public function updateArrayByMedicamento(Request $request)
    {
        $arrayHorario = $request->input("arrayHorario");
        if (isset($arrayHorario) && count($arrayHorario) > 0) {
            foreach ($arrayHorario as $key => $horario) {
                $carbonFecha = Carbon::createFromFormat('d/m/Y', $horario['editar_medicamento_fecha[]']);

                $frascoJson = [
                    'I' => $horario['editar_medicamento_frasco_I'],
                    'II' => $horario['editar_medicamento_frasco_II'],
                    'III' => $horario['editar_medicamento_frasco_III'],
                    'IV' => $horario['editar_medicamento_frasco_IV'],
                    'V' => $horario['editar_medicamento_frasco_V'],
                    'VI' => $horario['editar_medicamento_frasco_VI'],
                ];

                if (isset($horario['editar_medicamento_id[]']) && intval($horario['editar_medicamento_id[]']) > 0) {
                    $updateHorario = HorarioKardexMedicamento::find($horario["editar_medicamento_id[]"]);
                    $updateHorario->update([
                        'hora' => $horario['editar_medicamento_hora[]'], 
                        'aplicacion' => intval($horario['editar_medicamento_aplicable']),
                        'ram' => $horario['editar_medicamento_ram[]'] === true ? 1 : 0,
                        'frasco' => json_encode($frascoJson),
                        'observacion' => $horario['editar_medicamento_observacion[]'],
                        'updated_us' => Auth::user()->id,
                        'updated_at' => Carbon::now(),
                        'fecha' => $carbonFecha->format('Y-m-d'),
                    ]);
                } else {
                    $newExamLab = HorarioKardexMedicamento::create([
                        'id_kardex' => $request->input("id_kardex"),
                        'id_medicamento' => $request->input("id_medicamento"),
                        'hora' => $horario['editar_medicamento_hora[]'], 
                        'aplicacion' => intval($horario['editar_medicamento_aplicable']),
                        'ram' => $horario['editar_medicamento_ram[]'] === true ? 1 : 0,
                        'frasco' => json_encode($frascoJson),
                        'observacion' => $horario['editar_medicamento_observacion[]'],
                        'created_us' => Auth::user()->id,
                        'created_at' => Carbon::now(),
                        'fecha' => $carbonFecha->format('Y-m-d'),
                    ]);
                }
            }
        }
        return response()->json([
            "message" => "Se actualizo los horarios del kardex"
        ], 200);
    }
}
