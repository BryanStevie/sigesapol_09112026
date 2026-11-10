<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use App\Models\Farmacia;
use App\Models\Establecimiento;
use App\Http\Requests;
use App\Models\Access\User\User;
use App\Http\Controllers\Controller;
use App\Models\Consultorio;
use App\Models\TipoAtencione;
use App\Models\Comisaria;
//use App\Models\Farmacia_tipo_atencione;
use Illuminate\Support\Facades\Route;
use App\Models\Genera_correlativonea;
use Auth;

// Cristiam 

class ComisariaController extends Controller
{

    function __construct()
    {
        $this->middleware('auth'); 
    } 
    
    function index(){
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $model= new Farmacia;
        $farmacias=$model->ShowAll();
        $ruta = Route::getCurrentRoute()->getPath();
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id,$ruta);
        $permiso = $menu[0];
        if($permiso->lectura == "")return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        return view('frontend.farmacias.all',['farmacias' => $farmacias,'permiso'=>$permiso]);
    }
    
}

?>