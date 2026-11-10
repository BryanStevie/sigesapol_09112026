<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\CqxTipoProfesional;

class CqxTipoProfesionalController extends Controller
{
    function getAllActivos() {
        $model_cqx_tipo_profesional = new CqxTipoProfesional();
        $cqx_tipo_professionales = $model_cqx_tipo_profesional->GetAllActivos();

        return response()->json([
            "message" => "Se realizo la consulta con exito",
            "data" => $cqx_tipo_professionales
        ], 200);
    }

    function GetAllCirujanosActivos() {
        $model_cqx_tipo_profesional = new CqxTipoProfesional();
        $cqx_tipo_professionales = $model_cqx_tipo_profesional->GetAllCirujanosActivos();

        return response()->json([
            "message" => "Se realizo la consulta con exito",
            "data" => $cqx_tipo_professionales
        ], 200);
    }
    
    function getByFlagProfesional($flag_profesional)
    {
        $tipos = CqxTipoProfesional::where('flag_profesional', $flag_profesional)->where('estado', 1)->get();
        return response()->json([
            "message" => "Se realizo la consulta con exito",
            "data" => $tipos
        ], 200);
    } 
}
