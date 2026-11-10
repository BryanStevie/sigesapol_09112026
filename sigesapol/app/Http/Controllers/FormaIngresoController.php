<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use App\Models\Forma_ingreso;
use App\Http\Requests;
use App\Models\Access\User\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Models\Genera_correlativonea;
use Auth;

class FormaIngresoController extends Controller
{

    function __construct()
    {
        $this->middleware('auth'); 
    }
    
    public function index() {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $model = new Forma_ingreso();

        $objFormaIngreso=$model->ShowAll();
        $ruta = Route::getCurrentRoute()->getPath();
		$menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id,$ruta);
		$permiso = $menu[0];
        
		if($permiso->lectura == "")return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        
        return view('frontend.forma_ingreso.all', ['formasIngreso' => $objFormaIngreso, 'permiso' => $permiso]);
    }

    public function create() {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        return view('frontend.forma_ingreso.create');
    }

    public function store(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $data = Forma_ingreso::latest('id')->first();

		$objFormaIngreso = new Forma_ingreso();
		$objFormaIngreso->id = $data->id;
        $objFormaIngreso->codigo = $request->codigo;
		$objFormaIngreso->descripcion = $request->descripcion;
		$objFormaIngreso->estado = 'N';      
		$objFormaIngreso->save();
		

		Session::flash('flash_message', 'Registro exitoso');
		return redirect('/all_forma_ingreso');
    }

    public function deshabilitar($id){
		$update=Forma_ingreso::where('id','=',$id)->first();
		$update->estado='I';
		$update->save();
		Session::flash('flash_message', 'Se deshabilitó correctamente');
    }

    public function habilitar($id){
		$update=Forma_ingreso::where('id','=',$id)->first();
		$update->estado='N';
		$update->save();
		Session::flash('flash_message', 'Se deshabilitó correctamente');
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
		
        $objFormaIngreso = Forma_ingreso::findOrFail($id);
        return view('frontend.forma_ingreso.edit', ['formaIngreso' => $objFormaIngreso]);
    }

    public function update(Request $request)
    {
	
        $formaIngreso = Forma_ingreso::find($request->id);
        $formaIngreso->descripcion=trim($request->descripcion);
		
        $formaIngreso->save();
		
        return redirect('/all_forma_ingreso')->withFlashSuccess("Se actualizó correctamente");
    }

}
