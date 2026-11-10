<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Farmacia;
use App\Models\Pabellone;
use Session;
use DB;
use App;
use Illuminate\Support\Facades\Route;
use Auth;

class PabellonController extends Controller
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

        $id_user = Auth::user()->id;
        $modelfarma = new Farmacia();
        $modelpabellon = new Pabellone();
        $farmacias = $modelfarma->GetFarmaByUser($id_user);
        $id_ipress = $farmacias->id_establecimiento;
        $farmacia = $farmacias->nombre;
        $pabellones = $modelpabellon->ShowallByIdIpress($id_ipress);

        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1)
            ;$ruta = $ruta[0];
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        return view('frontend.pabellones.all', ['pabellones' => $pabellones, 'permiso' => $permiso]);
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
        return view('frontend.pabellones.create',compact('id_establecimiento'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $nombre = trim($request->nombre);
        $pabellon = new Pabellone;
        $pabellon->nombre = trim($request->nombre);
        $pabellon->nombre_atencion = trim($request->nombre_atencion);
        $pabellon->id_establecimiento = $request->id_establecimiento;
        $pabellon->estado=0;

        $model_pabellon = new Pabellone;
        $existe = $model_pabellon->ExistePabellon($nombre, $request->id_establecimiento);
        if ($existe) {
            return redirect('/all_pabellon')
                            ->withInput()
                            ->withErrors(array('No se ha podido registrar, Pabell&oacute;n ya existe'));
        } else {
            $pabellon->save();
            Session::flash('flash_message', 'Registro exitoso');
            return redirect('/all_pabellon');
        }

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
		
        $pabellon = Pabellone::findOrFail($id);
		$id_user = Auth::user()->id;
        $modelfarma= new Farmacia();
        $farmacias=$modelfarma->GetFarmaByUser($id_user);
        $id_establecimiento=$farmacias->id_establecimiento;
        return view('frontend.pabellones.edit', compact('pabellon','id_establecimiento'));
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
        $update=Pabellone::where('id','=',$request->id)->first();
		$update->nombre=trim($request->nombre);
		$update->nombre_atencion = trim($request->nombre_atencion);
		$model_pabellon=new Pabellone;
		$existe = $model_pabellon->ExistePabellon($nombre, $request->id_establecimiento, $request->id);
		if ($existe) {
			return redirect('/all_pabellon')
							->withInput()
							->withErrors(array('No se ha podido actualizar, Pabell&oacute;n ya existe'));
		}else{
			$update->save();
			return redirect('/all_pabellon')->withFlashSuccess("Se actualizo correctamente");
		}
		/*
		$update->save();
		return redirect('/all_pabellon')->withFlashSuccess("Se actualizo correctamente");
		*/
    }

    public function obtener_piso_por_idpabellon($id_pabellon){
    
        $model_asignacion = new Asignacion_servicio;
        $piso = $model_asignacion->GetPisoByIdPabellon($id_pabellon);
        return $piso;
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
		$update=Pabellone::where('id','=',$id)->first();
		$update->estado=3;
		$update->save();
		Session::flash('flash_message', 'Se elimino correctamente');
	}
	
	
}
