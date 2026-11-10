<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Tipo_contrato;
use Session;
use DB;
use App;
use Illuminate\Support\Facades\Route;
use Auth;

class TipoContratoController extends Controller
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

        $model = new Tipo_contrato;
        $tipo_contratos = $model->ShowAll();
        $ruta = Route::getCurrentRoute()->getPath();
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        return view('frontend.tipo_contratos.all', compact('tipo_contratos', 'permiso'));
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
        return view('frontend.tipo_contratos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $nombre = trim($request->nombre);
        $tipo = new Tipo_contrato;
        $tipo->nombre = trim($request->nombre);
        $tipo->estado = 1;

        $model_tipo = new Tipo_contrato;
        $existe = $model_tipo->ExisteTipoContrato($nombre);
        if ($existe) {
            return redirect('/all_tipo_contrato')
                            ->withInput()
                            ->withErrors(array('No se ha podido registrar, Tipo de Contrato ya existe'));
        } else {
            $tipo->save();
            Session::flash('flash_message', 'Registro exitoso');
            return redirect('/all_tipo_contrato');
        }

        /*
          $tipo->save();
          Session::flash('flash_message', 'Registro exitoso');
          return redirect('/all_tipo_contrato');
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
		
        $tipo_contrato = Tipo_contrato::findOrFail($id);
        return view('frontend.tipo_contratos.edit', compact('tipo_contrato'));
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
        $update=Tipo_contrato::where('id','=',$request->id)->first();
		$update->nombre=trim($request->nombre);
		
		$model_tipo=new Tipo_contrato;
		$existe = $model_tipo->ExisteTipoContrato($nombre, $request->id);
		if ($existe) {
			return redirect('/all_tipo_contrato')
							->withInput()
							->withErrors(array('No se ha podido actualizar, Tipo de Contrato ya existe'));
		}else{
			$update->save();
			return redirect('/all_tipo_contrato')->withFlashSuccess("Se actualizo correctamente");
		}
		
		/*
		$update->save();
		return redirect('/all_tipo_contrato')->withFlashSuccess("Se actualizo correctamente");
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
		$update=Tipo_contrato::where('id','=',$id)->first();
		$update->estado=3;
		$update->save();
		Session::flash('flash_message', 'Se elimino correctamente');
	}
	
	
}
