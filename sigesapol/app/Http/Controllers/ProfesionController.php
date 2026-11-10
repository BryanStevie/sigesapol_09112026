<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Profesione;
use Session;
use DB;
use App;
use Illuminate\Support\Facades\Route;
use Auth;

class ProfesionController extends Controller
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
    
    public function index() {
        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;

        $model = new Profesione;
        $profesiones = $model->ShowAll();
        $ruta = Route::getCurrentRoute()->getPath();
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        return view('frontend.profesiones.all', compact('profesiones', 'permiso'));
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
        //$model= new Establecimiento();
        //$establecimientos=$model->ShowAll();
        return view('frontend.profesiones.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $nombre = trim($request->nombre);
        $profesion = new Profesione;
        $profesion->nombre = trim($request->nombre);
        $profesion->estado = 1;

        $model_profesion = new Profesione;
        $existe = $model_profesion->ExisteProfesion($nombre);
        if ($existe) {
            return redirect('/all_profesiones')
                            ->withInput()
                            ->withErrors(array('No se ha podido registrar, Profesion ya existe'));
        } else {
            $profesion->save();
            Session::flash('flash_message', 'Registro exitoso');
            return redirect('/all_profesiones');
        }

        /*
          $especialidad->save();
          Session::flash('flash_message', 'Registro exitoso');
          return redirect('/all_especialidad');
         */
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
		
        $profesion = Profesione::findOrFail($id);
        return view('frontend.profesiones.edit', compact('profesion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
		$nombre=trim($request->nombre);
        $update=Profesione::where('id','=',$request->id)->first();
		$update->nombre=trim($request->nombre);
		
		$model_profesion=new Profesione;
		$existe = $model_profesion->ExisteProfesion($nombre, $request->id);
		if ($existe) {
			return redirect('/all_profesiones')
							->withInput()
							->withErrors(array('No se ha podido actualizar, Profesion ya existe'));
		}else{
			$update->save();
			return redirect('/all_profesiones')->withFlashSuccess("Se actualizo correctamente");
		}
		
		/*
		$update->save();
		return redirect('/all_especialidad')->withFlashSuccess("Se actualizo correctamente");
		*/
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
	
	public function eliminar($id){
		$update=Profesione::where('id','=',$id)->first();
		$update->estado=3;
		$update->save();
		Session::flash('flash_message', 'Se elimino correctamente');
	}
	
	
}
