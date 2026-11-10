<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Origen_paciente;
use Session;
use DB;
use App;
use Illuminate\Support\Facades\Route;
use Auth;

class OrigenPacienteController extends Controller
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

        $model = new Origen_paciente;
        $origenes = $model->ShowAll();
        $ruta = Route::getCurrentRoute()->getPath();
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        return view('frontend.origen_pacientes.all', compact('origenes', 'permiso'));
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
        return view('frontend.origen_pacientes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $nombre = trim($request->nombre);
        $origen = new Origen_paciente;
        $origen->nombre = trim($request->nombre);
        $origen->estado = 1;

        $model_origen = new Origen_paciente;
        $existe = $model_origen->ExisteOrigenPaciente($nombre);
        if ($existe) {
            return redirect('/all_origen_paciente')
                            ->withInput()
                            ->withErrors(array('No se ha podido registrar, Origen de paciente ya existe'));
        } else {
            $origen->save();
            Session::flash('flash_message', 'Registro exitoso');
            return redirect('/all_origen_paciente');
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
		
        $origen = Origen_paciente::findOrFail($id);
        return view('frontend.origen_pacientes.edit', compact('origen'));
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
        $update=Origen_paciente::where('id','=',$request->id)->first();
		$update->nombre=trim($request->nombre);
		
		$model_origen=new Origen_paciente;
		$existe = $model_origen->ExisteOrigenPaciente($nombre, $request->id);
		if ($existe) {
			return redirect('/all_origen_paciente')
							->withInput()
							->withErrors(array('No se ha podido actualizar, Origen de paciente ya existe'));
		}else{
			$update->save();
			return redirect('/all_origen_paciente')->withFlashSuccess("Se actualizo correctamente");
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
		$update=Origen_paciente::where('id','=',$id)->first();
		$update->estado=3;
		$update->save();
		Session::flash('flash_message', 'Se elimino correctamente');
	}
	
	
}
