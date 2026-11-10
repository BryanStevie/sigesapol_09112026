<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Corredore;
use App\Models\Establecimiento;
use Auth;
use Session;
use Illuminate\Support\Facades\Route;

class CorredoreController extends Controller
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
    $model_corredores = new Corredore();
    $corredores = $model_corredores->ShowAllWithEstablecimiento();
    $ruta = Route::getCurrentRoute()->getPath();
    if (count($ruta = explode("/", $ruta)) > 1);
    $ruta = $ruta[0];

    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
    $permiso = $menu[0];
    // dd($permiso);
    if ($permiso->lectura == "" || $permiso->lectura == "0")
        return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

        return view('frontend.corredores.index', compact('corredores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Establecimiento();
        $establecimientos = $model->ShowAll();
        return view('frontend.corredores.create', compact('establecimientos'));
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

        $model = new Corredore;
        //Nuevo
        if ($request->id_corredor == ""):
            if ($model->Registrar($request->id_establecimiento, strtoupper($request->nombre), Auth::user()->id)):
                Session::flash('flash_message', 'Registro exitoso');
            else:
                Session::flash('flash_message', 'Ha ocurrido un error al registrar el Corredor.');
            endif;

        // Editar
        else:
            if ($model->Modificar($request->id_corredor, $request->id_establecimiento, strtoupper($request->nombre), Auth::user()->id)):
                Session::flash('flash_message', 'Modificación exitosa');
            else:
                Session::flash('flash_message', 'No se ha podido registrar la modificación.');
            endif;
            
        endif;

        return redirect('/corredores');
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
        $model_establecimiento = new Establecimiento();
        $establecimientos = $model_establecimiento->ShowAll();

        $model_corredor = new Corredore();
        $corredor = $model_corredor->GetById($id);

        return view('frontend.corredores.edit', compact('corredor', 'establecimientos'));
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

    public function desactivar($id) {
        $model = new Corredore();
        return json_encode($model->Desactivar($id, Auth::user()->id));
    }

    public function activar($id) {
        $model = new Corredore();
        return json_encode($model->Activar($id, Auth::user()->id));
    }

    public function get_corredores_por_establecimiento($id_establecimiento) {
        $model = new Corredore();
        return $model->ShowAllByIdEstablecimiento($id_establecimiento);
    }

}
