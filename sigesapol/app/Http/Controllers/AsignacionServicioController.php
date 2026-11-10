<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Farmacia;
use App\Models\Pabellone;
use App\Models\Piso;
use App\Models\Asignacion_servicio;
use App\Models\Consultorio;
use Session;
use DB;
use App;
use Illuminate\Support\Facades\Route;
use Auth;

class AsignacionServicioController extends Controller
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
        $modelasignacionservicio = new Asignacion_servicio();
        $farmacias = $modelfarma->GetFarmaByUser($id_user);
        $id_ipress = $farmacias->id_establecimiento;
        $farmacia = $farmacias->nombre;

        $asignaciones = $modelasignacionservicio->ShowallById_Ipress($id_ipress);
        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1)
            ;$ruta = $ruta[0];
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        return view('frontend.asignacion_servicios.all', ['asignaciones' => $asignaciones, 'permiso' => $permiso]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $id_user = Auth::user()->id;
        $modelfarma = new Farmacia();
        $farmacias = $modelfarma->GetFarmaByUser($id_user);
        $id_establecimiento = $farmacias->id_establecimiento;
        $model = new Pabellone();
        $pabellon = $model->ShowallByIdIpress($id_establecimiento);
        $model_consultorio = new Consultorio;
        
        return view('frontend.asignacion_servicios.create', compact('pabellon', 'id_establecimiento'));
    }

    public function obtener_piso_por_idpabellon($id_pabellon){
	    $id_establecimiento = Auth::user()->id_establecimiento;
		$model_asignacion = new Asignacion_servicio;
        $piso = $model_asignacion->GetPisoByIdPabellon($id_pabellon, $id_establecimiento);
        return $piso;
	}

    public function obtener_servicios_atencion($tipo_atencion){
        $id_establecimiento = Auth::user()->id_establecimiento;
        $model_consultorio = new Consultorio;
        $servicios = $model_consultorio->ShowConsultorioTipoAtencion($id_establecimiento, $tipo_atencion);
        return $servicios;
    }

    public function obtener_prioridades(){
        $id_establecimiento = Auth::user()->id_establecimiento;
        $model_prioridad = new \App\Models\Triaje;
        $prioridades = $model_prioridad->ShowPrioridad();
        return $prioridades;
    }


    public function store(Request $request) {
        
        $model_consultorio = new Consultorio;
        $consultorio = $model_consultorio->GetNombreById($request->id_consultorio);
        $asignacion = new Asignacion_servicio;
        $asignacion->nombre_atencion=$request->nombre_atencion;
        $asignacion->id_piso = $request->id_piso;
        $asignacion->id_consultorio = $request->id_consultorio;
        $asignacion->id_establecimiento = $request->id_establecimiento;
        $asignacion->nombre = $consultorio->nombre;
        //if($request->nombre_atencion='EMERGENCIA'):
        //    $asignacion->id_prioridad = $request->id_prioridad;
        //endif;
        $asignacion->estado=0;

        $model_asignacion = new Asignacion_servicio;
        $existe = $model_asignacion->ExisteAsignacionServicio($request->id_piso, $request->id_consultorio, $request->id_establecimiento);
        if ($existe) {
            return redirect('/all_asignacion_servicio')
                            ->withInput()
                            ->withErrors(array('No se ha podido registrar, La asignacion de Pabell&oacute;n, Piso y Servicio ya existe'));
        } else {
            $asignacion->save();
            Session::flash('flash_message', 'Registro exitoso');
            return redirect('/all_asignacion_servicio');
        }

        /*
          $asignacion->save();
          Session::flash('flash_message', 'Registro exitoso');
          return redirect('/all_asignacion_servicio');
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
    public function edit($id) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $model_asignacion = new Asignacion_servicio;
        $asignacion = $model_asignacion->GetAsignacionServiciosById($id);
        $id_user = Auth::user()->id;
        $modelfarma = new Farmacia();
        $farmacias = $modelfarma->GetFarmaByUser($id_user);
        $id_establecimiento = $farmacias->id_establecimiento;
        $model = new Pabellone();
        $pabellon = $model->ShowallById_Ipress($id_establecimiento,$asignacion->nombre_atencion);
        $model_consultorio = new Consultorio;
        $model_prioridad = new \App\Models\Triaje;

        if($asignacion->nombre_atencion=='EMERGENCIA'):
            $consultorio = $model_consultorio->ShowConsultorioTipoAtencion($id_establecimiento, 2);
        else:
            $consultorio = $model_consultorio->ShowConsultorioTipoAtencion($id_establecimiento, 3);
        endif;
        $piso = $model_asignacion->GetPiso($asignacion->id_piso, $id_establecimiento);
        
        $prioridades = $model_prioridad->ShowPrioridad();
        
        return view('frontend.asignacion_servicios.edit', compact('asignacion', 'pabellon', 'consultorio', 'piso', 'id_establecimiento','prioridades'));
    }

    public function update(Request $request)
    {
        
        $update=Asignacion_servicio::where('id','=',$request->id)->first();
		$update->id_piso=$request->id_piso;
		$update->id_consultorio=$request->id_consultorio;
        $update->nombre_atencion=$request->nombre_atencion;
		if($request->nombre_atencion='EMERGENCIA'):
            $update->id_prioridad = $request->id_prioridad;
        endif;
		$model_asignacion=new Asignacion_servicio;
		$existe = $model_asignacion->ExisteServicio($request->id_piso, $request->id_consultorio, $request->id_establecimiento, $request->id);
		if ($existe) {
			return redirect('/all_asignacion_servicio')
							->withInput()
							->withErrors(array('No se ha podido actualizar, La asignacion de Pabell&oacute;n, Piso y Servicio ya existe'));
		}else{
			$update->save();
			return redirect('/all_asignacion_servicio')->withFlashSuccess("Se actualizo correctamente");
		}
		//$update->save();
		//return redirect('/all_asignacion_servicio')->withFlashSuccess("Se actualizo correctamente");
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
		$update=Asignacion_servicio::where('id','=',$id)->first();
		$update->estado=3;
		$update->save();
		Session::flash('flash_message', 'Se elimino correctamente');
	}
	
	
}
