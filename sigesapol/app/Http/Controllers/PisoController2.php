<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Farmacia;
use App\Models\Pabellone;
use App\Models\Piso;
use Session;
use DB;
use App;
use Illuminate\Support\Facades\Route;
use Auth;

class PisoController extends Controller
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
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $id_user = Auth::user()->id;
        $modelfarma = new Farmacia();
        $modelpiso = new Piso();
        $farmacias = $modelfarma->GetFarmaByUser($id_user);
        $id_ipress = $farmacias->id_establecimiento;
        $farmacia = $farmacias->nombre;
        $pisos = $modelpiso->ShowallById_Ipress($id_ipress);

        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1)
            ;$ruta = $ruta[0];
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];

        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        return view('frontend.pisos.all', ['pisos' => $pisos, 'permiso' => $permiso]);
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
		
		$id_user = Auth::user()->id;
        $modelfarma= new Farmacia();
        $farmacias=$modelfarma->GetFarmaByUser($id_user);
        $id_establecimiento=$farmacias->id_establecimiento;
        $model= new Pabellone();
        $pabellon=$model->ShowallByIdIpress($id_establecimiento);
		
        return view('frontend.pisos.create',compact('pabellon','id_establecimiento'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $nombre = trim($request->nombre);
        $piso = new Piso;
        $piso->nombre = trim($request->nombre);
        $piso->id_pabellon = $request->id_pabellon;
        $piso->nombre_atencion = $request->nombre_atencion;
        $piso->id_establecimiento = $request->id_establecimiento;
        $piso->estado=0;

        $model_piso = new Piso;
        $existe = $model_piso->ExistePiso($nombre, $request->id_pabellon, $request->id_establecimiento);
        if ($existe) {
            return redirect('/all_piso')
                            ->withInput()
                            ->withErrors(array('No se ha podido registrar, Piso ya existe en este Pabell&oacute;n'));
        } else {
            $piso->save();
            Session::flash('flash_message', 'Registro exitoso');
            return redirect('/all_piso');
        }

        //Session::flash('flash_message', 'Registro exitoso');
        //return redirect('/all_piso');
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
		
        $piso = Piso::findOrFail($id);
        //dd($piso);
        $id_user = Auth::user()->id;
        $modelfarma= new Farmacia();
        $farmacias=$modelfarma->GetFarmaByUser($id_user);
        $id_establecimiento=$farmacias->id_establecimiento;
		$model= new Pabellone();
        $pabellon=$model->ShowallById_Ipress($id_establecimiento, $piso->nombre_atencion);
        return view('frontend.pisos.edit', compact('piso','pabellon','id_establecimiento'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {
        $nombre = trim($request->nombre);
        $update = Piso::where('id', '=', $request->id)->first();
        $update->nombre = trim($request->nombre);
        $update->id_pabellon = $request->id_pabellon;
        $update->nombre_atencion = $request->nombre_atencion;


        $model_piso = new Piso;
        $existe = $model_piso->ExistePiso($nombre, $request->id_pabellon, $request->id_establecimiento, $request->id);
        if ($existe) {
            return redirect('/all_piso')
                            ->withInput()
                            ->withErrors(array('No se ha podido actualizar, Piso ya existe en este Pabell&oacute;n'));
        } else {
            $update->save();
            return redirect('/all_piso')->withFlashSuccess("Se actualizo correctamente");
        }
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
		$update=Piso::where('id','=',$id)->first();
		$update->estado=3;
		$update->save();
		Session::flash('flash_message', 'Se elimino correctamente');
	}

    public function obtener_pabellon($atencion){
        $id_establecimiento = Auth::user()->id_establecimiento;
        $model_piso = new Piso;
        $pabellon = $model_piso->GetPabellonbyAtencion($atencion,$id_establecimiento);
        return $pabellon;
    }

    public function obtener_prioridad_piso($piso, $nombre_atencion) {
        $id_establecimiento = Auth::user()->id_establecimiento;
        $model_piso = new \App\Models\Piso;
        $prioridad = $model_piso->GetPisoyPrioridad($piso,$id_establecimiento,$nombre_atencion);
        return $prioridad;
  }

  public function obtener_prioridad_piso_prioridad($piso, $nombre_atencion,$prioridad) {
        $id_establecimiento = Auth::user()->id_establecimiento;
        $model_piso = new \App\Models\Piso;
        $topico = $model_piso->GetPrioridadbyTopico($piso,$id_establecimiento,$nombre_atencion,$prioridad);
        return $topico;
  }
	
	
}
