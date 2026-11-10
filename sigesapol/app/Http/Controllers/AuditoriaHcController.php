<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\AuditoriaHc;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuditoriaHcController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Registra en auditoria_hc que un usuario visualizó la Historia Clínica de un paciente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function registrar(Request $request)
    {
        $this->validate($request, [
            'origen' => 'required',
            'doc_buscado' => 'required',
        ]);

        $idTipoAtencion = $request->input('id_tipo_atencion');

        $log = new AuditoriaHc;
        $log->origen = $request->input('origen');
        $log->doc_buscado = $request->input('doc_buscado');
        $log->id_tipo_atencion = ($idTipoAtencion === '' || $idTipoAtencion === null) ? null : $idTipoAtencion;
        $log->created_at = Carbon::now();
        $log->created_us = Auth::user()->id;
        $log->save();

        return response()->json([
            "message" => "Auditoría registrada correctamente"
        ], JsonResponse::HTTP_CREATED);
    }
}
