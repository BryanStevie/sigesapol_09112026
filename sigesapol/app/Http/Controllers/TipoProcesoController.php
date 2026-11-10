<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Farmacia;
use App\Models\Tipo_proceso;
use App\Models\Establecimiento;
use SoapClient;
use Session;
use DB;
use App;
use Auth;
use Illuminate\Support\Facades\Route;

class TipoProcesoController extends Controller
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
    
    public function update_tipo_proceso(Request $request) {
            
			if (!Auth::check()):
            	return redirect('/auth/login');
        	endif;
		
            $id_user = Auth::user()->id;
            $tipo_proceso = Tipo_proceso::where('id', '=', $request->id_tipo_proceso)->first();
            $tipo_proceso->abreviatura_tipo_proceso = $request->abreviatura_tipo_proceso;
            $tipo_proceso->descripcion_tipo_proceso = $request->descripcion_tipo_proceso;
            $tipo_proceso->save();
            
            Session::flash('flash_message', 'Actualización exitosa');
            return redirect('/listar_tipo_procesos');

    }
    
    public function editar_tipo_proceso($id) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $id_user = Auth::user()->id;
        
        $tipo_proceso = DB::table('tipo_procesos')->where('tipo_procesos.id', '=', $id)
                        ->select('tipo_procesos.*')->first();
        
        return view('frontend.tipo_procesos.editar_tipo_proceso', compact('tipo_proceso'));
    }

    
    public function formulario_tipo_proceso() {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        return view('frontend.tipo_procesos.create_tipo_proceso');
    }

    public function index($id_user = 0) {

        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;

        $id_user = Auth::user()->id;        
        $modeltipoproceso = new Tipo_proceso();        
        $tipo_procesos = $modeltipoproceso->getTodoTipoProceso();
        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1)
            ;$ruta = $ruta[0];
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];


        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

        return view('frontend.tipo_procesos.all', ['tipo_procesos' => $tipo_procesos, 'permiso' => $permiso]);
    }

    /*public function index()
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;
            return view('frontend.query_editor.query_editor');
    }
    */

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id_user)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    
    public function store(Request $request) {        
        $modeltipoprocesos= new Tipo_proceso();
        $tipoproceso_registrado = $modeltipoprocesos->tipoproceso_yaregistrada($request->abreviatura_tipo_proceso);
        if(count($tipoproceso_registrado)>0){
            $dato = "El Proveedor <b>".$tipoproceso_registrado->descripcion_tipo_proceso." </b> ya se encuentra registrado, en estado ";
            $dato.= $tipoproceso_registrado->estado == "1"? "<b>ACTIVO</b>":"<b>INACTIVO</b>";
            return redirect('/listar_dependencias/' . $request->id_user)->withInput()->withErrors(array($dato));
        } else {
            
            $tipo_proceso = new Tipo_proceso;
            $tipo_proceso->descripcion_tipo_proceso = $request->descripcion_tipo_proceso;
            $tipo_proceso->abreviatura_tipo_proceso = $request->abreviatura_tipo_proceso;
            $tipo_proceso->estado = 1;            
            $tipo_proceso->save();
            $id_tipo_proceso = $tipo_proceso->id;
            //-----Registro de la tabla Medico_consultorio ------------------------
            Session::flash('flash_message', 'Registro exitoso');
            return redirect('/listar_tipo_procesos/');
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
	
	public function desactivar($id, $estado){
		
		if (!Auth::check()):
            return redirect('/auth/login');
        endif;
		
            $id_user = Auth::user()->id;
                if($estado == "1"){
                    $update=Tipo_proceso::where('id','=',$id)->first();
                    $update->estado=3;
                    //$update->id_user_desactivate = $id_user;
                    //$update->fec_desactivate = date('Y-m-d H:i:s');
                    $update->save();
                    
                    Session::flash('flash_message', 'Se desactivo correctamente');
                } else {
                    $update=Tipo_proceso::where('id','=',$id)->first();
                    $update->estado=1;
                    //$update->id_user_activate = $id_user;
                    //$update->fec_activate = date('Y-m-d H:i:s');
                    $update->save();
                    Session::flash('flash_message', 'Se activo correctamente');
                }
	}
	
	
}
