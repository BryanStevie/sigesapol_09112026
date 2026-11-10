<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Consultorio;
use App\Models\Modulos;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;


class ModulosController extends Controller
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
    
    public function index()
    {
        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;
        $model_modulos = new Modulos();
        $modulos = $model_modulos->mostrarVentanillas();
        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1);
        $ruta = $ruta[0];

        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        // dd($permiso);
        if ($permiso->lectura == "" || $permiso->lectura == "0")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        
        return view('frontend.modulos.all', compact('modulos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::check()):
			return redirect('/auth/login');
		endif;

        $model_consultorio = new Consultorio();
        $ticket_servicios = $model_consultorio->ShowConsultoriosTicketsAll();

        return view('frontend.modulos.create', compact("ticket_servicios"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        if ($request->id_modulo == "") {
            $modulo = new Modulos();
            $modulo->id_servicio = $request->id_servicio;
            $modulo->nombre = $request->nombre_modulo;
            $modulo->estado = "1";
            $modulo->save();
            Session::flash('flash_message', 'Registro exitoso');

        } else {
            $id_modulo = $request->id_modulo;
            $modulo = Modulos::where('id','=',$id_modulo)->first();
            $modulo->id_servicio = $request->id_servicio;
            $modulo->nombre = $request->nombre_modulo;
            $modulo->save();
            Session::flash('flash_message', 'Modificación exitosa');
        }

        return redirect('/modulo_all');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::check()):
			return redirect('/auth/login');
		endif;

        $model_modulo = new Modulos();
        $modulo = $model_modulo->mostrarModuloPorId($id);

        $model_consultorio = new Consultorio();
        $ticket_servicios = $model_consultorio->ShowConsultoriosTicketsAll();

        return view('frontend.modulos.edit', compact("modulo","ticket_servicios"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function actualizar_estado($id_ventanilla,$estado)
    {
        if (!Auth::check()):
			return redirect('/auth/login');
		endif;

        $modulo = Modulos::where('id','=',$id_ventanilla)->first();
        $modulo->estado = $estado;
        $modulo->save();
        return "Y";
    }
}
