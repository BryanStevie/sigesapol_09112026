<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Medico;

class CqxProfesionalController extends Controller
{
    function getByEstablecimientoConsultorio($id_establecimiento, $id_consultorio) {
        $model_medico = new Medico();
        $cqx_profesionales = $model_medico->GetProfesionalesXEspecialidad($id_establecimiento, $id_consultorio);

        return response()->json([
            "message" => "Se realizo la consulta con exito",
            "data" => $cqx_profesionales
        ], 200);
    }
}
