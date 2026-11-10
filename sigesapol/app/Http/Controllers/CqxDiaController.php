<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\CqxDia;

class CqxDiaController extends Controller
{
    function getAllActivos() {
        $model_cqx_dias = new CqxDia();
        $cqx_dias = $model_cqx_dias->GetAllActivos();

        return response()->json([
            "message" => "Se realizo la consulta con exito",
            "data" => $cqx_dias
        ], 200);
    }
}
