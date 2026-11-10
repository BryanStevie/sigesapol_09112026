<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Cenare;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use Auth;

class CenaresController extends Controller {

    function __construct()
    {
        $this->middleware('auth'); 
    } 
    
    public function trama_cenares() {

        /*
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1)
            ;$ruta = $ruta[0];
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        $id_user = Auth::user()->id;

        */
        $date = Carbon::now();
        $date->modify('first day of this month');
        $fechaDesde = $date->format('d-m-Y');
        
        return view('frontend.cenares.trama_cenares', compact('fechaDesde'));
    }

    public function listar_trama_cenares(Request $request) {

        /*if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        if (!$request->fechaDesde):
            return redirect('/trama_cenares');
        endif;
        */
        $opc=1;
        //----------------------------------------------------------------------
        $fechaDesde = $request->fechaDesde;
        
        $cod_producto = explode("-", $request->fechaDesde);
        $dia=$cod_producto[0];
        $mes=$cod_producto[1];
        $ano=$cod_producto[2];

        $fecha=$ano.$mes.$dia;
        //dd($fecha);

        $model_productos = new Cenare();
        
        $p[]=$request->fechaDesde;
        //$p[]=$opc;
        $result = $model_productos->buscar_trama_cenares($fecha,0);
        //echo json_encode($result);
        return view('frontend.cenares.trama_cenares', compact('result', 'fechaDesde'));
        
    }

    public function muestra_trama_cenares($fecha) {

        $cod_producto = explode("-", $fecha);
        $dia=$cod_producto[0];
        $mes=$cod_producto[1];
        $ano=$cod_producto[2];

        $fecha=$ano.$mes.$dia;
        $model_productos = new Cenare();
        $p[]=$fecha;
        $result = $model_productos->buscar_trama_cenares($fecha,0);

        echo json_encode($result);
        /*$result["aaData"] = $data; */

//        return view('frontend.cenares.trama_cenares', compact('result', 'fechaDesde'));

    }

    public function muestra_trama_cenares_hoy() {
        
        $date = Carbon::now();
        //$fecha = $date->format('Ymd');
        $fecha='20201122';
        $model_productos = new Cenare();

        $result = $model_productos->buscar_trama_cenares($fecha,0);

        echo json_encode($result);
        
    }
    
   
}


