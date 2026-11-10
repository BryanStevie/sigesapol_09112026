<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\CambioCama;
use Auth;

class CambioCamasController extends Controller
{
    function __construct()
    {
        $this->middleware('auth'); 
    } 
    
    function getByPrestacionId($prestacion_id) {
        $cambioCamaModel = new CambioCama();
        $cambios = $cambioCamaModel->getByPrestacionId($prestacion_id);
        return response()->json($cambios, 200);
    }

    function getByExtId($id_ext)
    {
        $cambioCamaModel = new CambioCama();
        $cambios = $cambioCamaModel->getByExtIdForHospitalizacion($id_ext);
        return response()->json($cambios, 200);
    }
}
