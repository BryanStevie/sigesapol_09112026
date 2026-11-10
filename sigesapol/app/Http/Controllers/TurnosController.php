<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Turno;
use Illuminate\Http\JsonResponse;
use Auth;

class TurnosController extends Controller
{
    function __construct()
    {
        $this->middleware('auth'); 
    } 
    
    public function getAllApi() {
        $turnos = Turno::where("turnos.flag_enfermeria_hospitalizacion", null)->get();
        return response()->json($turnos, JsonResponse::HTTP_OK);
    }
}
