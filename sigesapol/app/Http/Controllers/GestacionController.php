<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Gestacion;
use App\Models\Puerperio;
use Auth;

class GestacionController extends Controller
{
    function __construct()
    {
        $this->middleware('auth'); 
    }
    
    public function obtener_ultima_gestacion_dni_asegurado_ajax($asegurado_dni)
    {
        if($asegurado_dni === null || $asegurado_dni === '')
        {
            return response()->json(['error' => 'El campo dni es requerido'], 404);
        }
        
        $model_puerperio = new Puerperio();
        $lastPuerperio = $model_puerperio -> getLastPuerperioByAseguradoDni($asegurado_dni);
        $model_gestacion = new Gestacion();
        if (!empty($lastPuerperio)) {
            $ultima_gestacion = $model_gestacion->getLastByAseguradoDNI($asegurado_dni);
            if (!empty($ultima_gestacion)) {
                $fecha_parto = $lastPuerperio[0] -> fecha_parto;
                $fecha_atencion = $ultima_gestacion[0] -> fecha_atencion;
                if($fecha_atencion > $fecha_parto){
                    return response()->json($ultima_gestacion);
                }
            }
            return response()->json(empty($lastPuerperio) ? null : [$lastPuerperio[0]]);
        }
        $ultima_gestacion = $model_gestacion->getLastByAseguradoDNI($asegurado_dni);
        if (!empty($ultima_gestacion)) {
            return response()->json($ultima_gestacion);
        }
        return response()->json(empty($lastPuerperio) ? null : [$lastPuerperio[0]]);
    }
}