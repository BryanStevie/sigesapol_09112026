<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Violencia;
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

class ViolenciaController  extends Controller {

    function __construct()
    {
        $this->middleware('auth'); 
    } 
    
    public function obtener_tipo_violencia($id_violencia) {

        $objViolencia = new Violencia;
        $Violencia = $objViolencia->showTipo_violencia($id_violencia) ;
        return $Violencia;
    }

}