<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EstablecimientoSalud;
use Session;
use DB;
use App;
use Auth;
use Illuminate\Support\Facades\Route;

class EstablecimientoSaludController extends Controller
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
    
    public function update_establecimiento_salud(Request $request) {
            
			if (!Auth::check()):
            	return redirect('/auth/login');
        	endif;
		
            $id_user = Auth::user()->id;
            $establecimiento = Establecimiento::where('id', '=', $request->id_establecimiento)->first();
            $establecimiento->nombre = $request->nombre_establecimiento;
            $establecimiento->codigo = $request->codigo;
            $establecimiento->nivel = $request->id_nivel;
            $establecimiento->red_region = $request->red;
            $establecimiento->ubigeo = $request->ubigeo;
            $establecimiento->save();
            
            Session::flash('flash_message', 'Actualización exitosa');
            return redirect('/listar_establecimientos');

    }
    
    public function editar_establecimiento_salud($id) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $id_user = Auth::user()->id;
        
        $establecimiento = DB::table('establecimientos')->where('establecimientos.id', '=', $id)
                        ->select('establecimientos.*')->first();
        
        return view('frontend.establecimientos.editar_establecimiento', compact('establecimiento'));
    }

    
    public function formulario_establecimiento_salud() {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        return view('frontend.establecimientos.create_establecimiento');
    }

    
    public function index($id_user = 0) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $id_user = Auth::user()->id;        
        $modelestablecimiento = new Establecimiento();        
        $establecimientos = $modelestablecimiento->ShowAll();
        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1)
            ;$ruta = $ruta[0];
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];


        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

        return view('frontend.establecimientos.all', ['establecimientos' => $establecimientos, 'permiso' => $permiso]);
    }

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
        $modelestablecimiento= new Establecimiento();
        $establecimiento_registrado = $modelestablecimiento->establecimiento_yaregistrado($request->codigo);
        if(count($establecimiento_registrado)>0){
            $dato = "El Establecimiento <b>".$establecimiento_registrado->nombre." </b> ya se encuentra registrado, en estado ";
            $dato.= $establecimiento_registrado->estado == "1"? "<b>ACTIVO</b>":"<b>INACTIVO</b>";
            return redirect('/listar_establecimientos/' . $request->id_user)->withInput()->withErrors(array($dato));
        } else {
            
            $establecimiento = new Establecimiento;
            $establecimiento->nombre = $request->nombre_establecimiento;
            $establecimiento->codigo = $request->codigo;
            $establecimiento->nivel = $request->id_nivel;
            $establecimiento->red_region = $request->red;
            $establecimiento->ubigeo = $request->ubigeo;
            $establecimiento->id_estado_reg = 1;            
            $establecimiento->save();
            $id_establecimiento = $establecimiento->id;
            //-----Registro de la tabla Medico_consultorio ------------------------
            Session::flash('flash_message', 'Registro exitoso');
            return redirect('/listar_establecimientos/');
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
                    $update=Establecimiento::where('id','=',$id)->first();
                    $update->id_estado_reg=0;
                    $update->save();
                    
                    Session::flash('flash_message', 'Se desactivo correctamente');
                } else {
                    $update=Establecimiento::where('id','=',$id)->first();
                    $update->id_estado_reg=1;
                    $update->save();
                    Session::flash('flash_message', 'Se activo correctamente');
                }
	}
	
	
}
