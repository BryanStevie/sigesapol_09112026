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
use App\Models\Cuarto;
use Session;
use DB;
use App;
use Illuminate\Support\Facades\Route;
use Auth;

class CuartoController extends Controller
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
        $modelcuarto = new Cuarto();
        $farmacias = $modelfarma->GetFarmaByUser($id_user);
        $id_ipress = $farmacias->id_establecimiento;
        $farmacia = $farmacias->nombre;

        $cuartos = $modelcuarto->ShowallCuartosId_Ipress($id_ipress);
        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1)
            ;$ruta = $ruta[0];
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        return view('frontend.cuartos.all', ['cuartos' => $cuartos, 'permiso' => $permiso]);
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

        return view('frontend.cuartos.create', compact('pabellon', 'id_establecimiento'));
    }

    public function obtener_consultorio_por_idpiso($id_piso){
	
		$model_cuarto = new Cuarto;
		$consultorio = $model_cuarto->GetConsultorioByIdPiso($id_piso);
        return $consultorio;
	}
	
	
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        
        $nombre = trim($request->nombre);
        $cuarto = new Cuarto;
        $cuarto->nombre = $request->nombre;
        $cuarto->id_asignacion_servicio = $request->id_asignacion_servicio;
        $cuarto->id_establecimiento = $request->id_establecimiento;
        $cuarto->nombre_atencion = $request->nombre_atencion;
        $cuarto->estado=0;

        $model_cuarto = new Cuarto;
        $existe = $model_cuarto->ExisteCuarto($nombre, $request->id_asignacion_servicio, $request->id_establecimiento);
        if ($existe) {
            return redirect('/all_cuarto')
                            ->withInput()
                            ->withErrors(array('No se ha podido registrar, Cuarto ya existe en este Pabell&oacute;n, Piso y Servicio'));
        } else {
            $cuarto->save();
            Session::flash('flash_message', 'Registro exitoso');
            return redirect('/all_cuarto');
        }

        //$cuarto->save();
        //Session::flash('flash_message', 'Registro exitoso');
        //return redirect('/all_cuarto');
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
		
		$model_cuarto= new Cuarto();
		$model_consultorio = new Consultorio;
		$model_asignacion=new Asignacion_servicio;
		$modelfarma= new Farmacia();
		$model= new Pabellone();
		$model_prioridad = new \App\Models\Triaje;
		$id_user = Auth::user()->id;
        $farmacias=$modelfarma->GetFarmaByUser($id_user);
        $id_establecimiento=$farmacias->id_establecimiento;
		
		$cuarto = $model_cuarto->GetCuartoById($id);
        $pabellon=$model->ShowallById_Ipress($id_establecimiento,$cuarto->nombre_atencion);
        if($cuarto->nombre_atencion=='EMERGENCIA'):
            //$consultorio = $model_prioridad->ShowTriajeAsignacion($cuarto->id_prioridad, $cuarto->id_asignacion_servicio) ;
            $consultorio = $model_cuarto->GetConsultorioByIdPiso($cuarto->id_piso);
        else:
            $consultorio = $model_cuarto->GetConsultorioByIdPiso($cuarto->id_piso);
            //$consultorio = $model_consultorio->ShowConsultorioTipoAtencion($id_establecimiento, 3);
        endif;
        
        $piso = $model_asignacion->GetPisoByIdPabellon($cuarto->id_pabellon, $id_establecimiento);
        $prioridades = $model_prioridad->ShowPrioridad();
        
        
        return view('frontend.cuartos.edit', compact('cuarto','pabellon','consultorio','piso_servicio','piso','consultorio','id_establecimiento','prioridades'));
    }

    public function update(Request $request)
    {
		$nombre = trim($request->nombre);
        $update=Cuarto::where('id','=',$request->id)->first();
		$update->nombre=$request->nombre;
		$update->id_asignacion_servicio=$request->id_asignacion_servicio;
        $update->nombre_atencion = $request->nombre_atencion;
		$model_cuarto=new Cuarto;
		$existe = $model_cuarto->ExisteCuarto($nombre, $request->id_asignacion_servicio, $request->id_establecimiento,$request->id);
		if ($existe) {
			return redirect('/all_cuarto')
							->withInput()
							->withErrors(array('No se ha podido actualizar, Cuarto ya existe en este Pabell&oacute;n, Piso y Servicio'));
		}else{
			$update->save();
			return redirect('/all_cuarto')->withFlashSuccess("Se actualizo correctamente");
		}
		
		//$update->save();
		//return redirect('/all_cuarto')->withFlashSuccess("Se actualizo correctamente");
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
		$update=Cuarto::where('id','=',$id)->first();
		$update->estado=3;
		$update->save();
		Session::flash('flash_message', 'Se elimino correctamente');
	}

    public function activar($id){
		$update=Cuarto::where('id','=',$id)->first();
		$update->estado=1;
		$update->save();
		Session::flash('flash_message', 'Se activó correctamente');
	}
	
	
}
