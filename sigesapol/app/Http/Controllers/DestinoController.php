<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Destino;
use SoapClient;
use Session;
use DB;
use App;
use Auth;
use Illuminate\Support\Facades\Route;

class DestinoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('auth'); 
    } 
    
    public function obtener_destino_por_condicion($id_condicion) {

        $DestinoCondicion = new Destino;
        $Destino = $DestinoCondicion->ShowDestino($id_condicion) ;
        return $Destino;
    }
	
	
}
