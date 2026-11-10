<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use App\Models\Estado_ingreso;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Auth;

class EstadoIngresoController extends Controller
{

    function __construct()
    {
        $this->middleware('auth'); 
    }
    
    public function index() {

    }

    public function create() {
        
    }

    public function store(Request $request) {
        
    }

    public function deshabilitar($id){
		
    }

    public function habilitar($id){
		
    }
    
    public function obtener_estadoingreso_por_formaingreso($id_forma_ingreso) {

        $estadoIngreso = Estado_ingreso::where('id_forma_ingreso', $id_forma_ingreso)
                                        ->orderBy('descripcion','asc')
                                        ->get();
        return $estadoIngreso;
    }

}
