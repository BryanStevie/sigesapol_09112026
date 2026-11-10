<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Farmacia;
use App\Models\Sala_quirurgica;
use Session;
use DB;
use App;
use Illuminate\Support\Facades\Route;
use Auth;

class SalaQuirurgicaController extends Controller
{
    /**
     * Display a listing of the resource..
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('auth'); 
    } 
    
    public function index() {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $id_user = Auth::user()->id;
        $modelfarma = new Farmacia();
        $modelsala = new Sala_quirurgica();
        $farmacias = $modelfarma->GetFarmaByUser($id_user);
        $id_ipress = $farmacias->id_establecimiento;
        $farmacia = $farmacias->nombre;
        $salas = $modelsala->ShowallById_Ipress($id_ipress);

        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1)
            ;$ruta = $ruta[0];
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        return view('frontend.sala_quirurgicas.all', ['salas' => $salas, 'permiso' => $permiso]);
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
		$id_user = Auth::user()->id;
        $modelfarma= new Farmacia();
		$farmacias=$modelfarma->GetFarmaByUser($id_user);
        $id_establecimiento=$farmacias->id_establecimiento;
        return view('frontend.sala_quirurgicas.create',compact('id_establecimiento'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $nombre = trim($request->nombre);
        $sala = new Sala_quirurgica;
        $sala->nombre = trim($request->nombre);
        $sala->id_establecimiento = $request->id_establecimiento;

        $model_sala = new Sala_quirurgica;
        $existe = $model_sala->ExisteSalaQuirurgica($nombre, $request->id_establecimiento);
        if ($existe) {
            return redirect('/all_sala_quirurgica')
                            ->withInput()
                            ->withErrors(array('No se ha podido registrar, Pabell&oacute;n ya existe'));
        } else {
            $sala->save();
            Session::flash('flash_message', 'Registro exitoso');
            return redirect('/all_sala_quirurgica');
        }

        /*
          $sala->save();
          Session::flash('flash_message', 'Registro exitoso');
          return redirect('/all_sala_quirurgica');
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
		
        $sala = Sala_quirurgica::findOrFail($id);
		$id_user = Auth::user()->id;
        $modelfarma= new Farmacia();
        $farmacias=$modelfarma->GetFarmaByUser($id_user);
        $id_establecimiento=$farmacias->id_establecimiento;
        return view('frontend.sala_quirurgicas.edit', compact('sala','id_establecimiento'));
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
        $update=Sala_quirurgica::where('id','=',$request->id)->first();
		$update->nombre=trim($request->nombre);
		
		$model_sala=new Sala_quirurgica;
		$existe = $model_sala->ExisteSalaQuirurgica($nombre, $request->id_establecimiento, $request->id);
		if ($existe) {
			return redirect('/all_sala_quirurgica')
							->withInput()
							->withErrors(array('No se ha podido actualizar, Pabell&oacute;n ya existe'));
		}else{
			$update->save();
			return redirect('/all_sala_quirurgica')->withFlashSuccess("Se actualizo correctamente");
		}
		/*
		$update->save();
		return redirect('/all_sala_quirurgica')->withFlashSuccess("Se actualizo correctamente");
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
		$update=Sala_quirurgica::where('id','=',$id)->first();
		$update->estado=3;
		$update->save();
		Session::flash('flash_message', 'Se elimino correctamente');
	}
	
	
}
