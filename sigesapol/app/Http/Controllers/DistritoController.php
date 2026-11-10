<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Distrito;
use App\Http\Controllers\Controller;
use SoapClient;
use App\Models\Contador_receta;
use Carbon\Carbon;
use DB;
use Session;
use App;
use Auth;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Mail;

class DistritoController  extends Controller {

    function __construct()
    {
        $this->middleware('auth'); 
    } 
    
    public function obtener_provincia_por_departamento($departamento) {

        $Distrito = new Distrito;
        $Provincia = $Distrito->ShowProvincia($departamento) ;
        return $Provincia;
    }

    public function obtener_distrito_por_provincia($provincia) {

        $Distrito = new Distrito;
        $Distritos = $Distrito->ShowDistrito($provincia) ;
        return $Distritos;
    }

    public function obtener_ids_ubigeo($departamento_nombre,$provincia_nombre,$distrito_nombre) {
               
        $departamento_nombre = $departamento_nombre == "" ? "CALLAO" : $departamento_nombre;
        $departamento = DB::table('departamentos')->where('descripcion', '=', $departamento_nombre)->first();
        $provincia = DB::table('provincias')->where('descripcion', '=', $provincia_nombre)->first();
        $distrito = DB::table('distritos')->where('descripcion', '=', $distrito_nombre)->first();

        $response = array($departamento->id_departamento, $provincia->id_provincia, $distrito->id_distrito);
        return $response;
    }

}