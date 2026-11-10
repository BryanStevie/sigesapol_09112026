<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Farmacia;
use App\Models\Clasificador_gasto;
use App\Models\Establecimiento;
use SoapClient;
use Session;
use DB;
use App;
use Auth;
use Illuminate\Support\Facades\Route;
use App\Models\Concepto;

class ClasificadorGastoController extends Controller
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
    
    public function update_cg(Request $request) {
            
			if (!Auth::check()):
				return redirect('/auth/login');
			endif;
		
            $id_user = Auth::user()->id;
            $cg = Clasificador_gasto::where('id', '=', $request->id_cg)->first();
            $cg->descripcion_cg = $request->descripcion_cg;
            $cg->save();
            
            Session::flash('flash_message', 'Actualización exitosa');
            return redirect('/listar_cgs');

    }
    
    public function editar_cg($id) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $id_user = Auth::user()->id;
        
        $cg = DB::table('clasificador_gastos')->where('clasificador_gastos.id', '=', $id)
                        ->select('clasificador_gastos.*')->first();
        
        return view('frontend.clasificador_gastos.editar_cg', compact('cg'));
    }

    
    public function formulario_cg() {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        return view('frontend.clasificador_gastos.create_cg');
    }

	/*
    public function index($id_user = 0) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $id_user = Auth::user()->id;        
        $modelcg = new Clasificador_gasto();        
        $cgs = $modelcg->getTodoCg();
        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1)
            ;$ruta = $ruta[0];
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];


        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

        return view('frontend.clasificador_gastos.all', ['cgs' => $cgs, 'permiso' => $permiso]);
    }
	*/
	
	public function index($id_user = 0) {

        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;

        $id_user = Auth::user()->id;        
        $modelcg = new Clasificador_gasto();
        $cgs = $modelcg->getTodoCg();
        $modelconcepto = new Concepto();
        $concepto = $modelconcepto->getConceptoTodos();

        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1)
            ;$ruta = $ruta[0];
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];


        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

        return view('frontend.clasificador_gastos.all', ['cgs' => $cgs,'concepto' => $concepto, 'permiso' => $permiso]);
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
        $modelcgs= new Clasificador_gasto();
        $cg_registrado = $modelcgs->cg_yaregistrada($request->descripcion_cg);
        if(count($cg_registrado)>0){
            //$dato = "El Proveedor <b>".$cg_registrado->descripcion_cg." </b> ya se encuentra registrado, en estado ";
			$dato = "El Clasificador de Gasto <b>".$cg_registrado->descripcion_cg." </b> ya se encuentra registrado, en estado ";
            $dato.= $cg_registrado->estado == "1"? "<b>ACTIVO</b>":"<b>INACTIVO</b>";
            return redirect('/listar_cgs/' . $request->id_user)->withInput()->withErrors(array($dato));
        } else {
            
            $cg = new Clasificador_gasto;
            $cg->descripcion_cg = $request->descripcion_cg;            
            $cg->estado = 1;            
            $cg->save();
            $id_cg = $cg->id;
            //-----Registro de la tabla Medico_consultorio ------------------------
            Session::flash('flash_message', 'Registro exitoso');
            return redirect('/listar_cgs/');
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
                    $update=Clasificador_gasto::where('id','=',$id)->first();
                    $update->estado=3;
                    $update->id_user_desactivate = $id_user;
                    $update->fec_desactivate = date('Y-m-d H:i:s');
                    $update->save();
                    
                    Session::flash('flash_message', 'Se desactivo correctamente');
                } else {
                    $update=Clasificador_gasto::where('id','=',$id)->first();
                    $update->estado=1;
                    $update->id_user_desactivate = $id_user;
                    $update->fec_desactivate = date('Y-m-d H:i:s');
                    $update->save();
                    Session::flash('flash_message', 'Se activo correctamente');
                }
	}
	
	
	
	public function update_concepto(Request $request) {
        
		if (!Auth::check()):
			return redirect('/auth/login');
		endif;
		
		$id_user = Auth::user()->id;
		$concepto = Concepto::where('id', '=', $request->id_concepto)->first();
		$concepto->descripcion_conceptos = $request->descripcion_concepto;
		$concepto->opcion = $request->tipo;
		$concepto->save();
		
		Session::flash('flash_message', 'Actualización exitosa');
		return redirect('/listar_cgs');

    }

	public function editar_concepto($id) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $id_user = Auth::user()->id;
        
        $conceptos = DB::table('conceptos')->where('conceptos.id', '=', $id)
                        ->select('conceptos.*')->first();
        
        return view('frontend.clasificador_gastos.editar_conceptos', compact('conceptos'));
    }

	public function formulario_concepto() {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        return view('frontend.clasificador_gastos.create_concepto');
    }

	public function store_concepto(Request $request) {        
        $modelconcepto= new Concepto();
        $cp_registrado = $modelconcepto->cp_yaregistrada($request->descripcion_concepto);
        if(count($cp_registrado)>0){
            $dato = "El Concepto <b>".$cp_registrado->descripcion_conceptos." </b> ya se encuentra registrado, en estado ";
            $dato.= $cp_registrado->estado == "1"? "<b>ACTIVO</b>":"<b>INACTIVO</b>";
            return redirect('/listar_cgs/' . $request->id_user)->withInput()->withErrors(array($dato));
        } else {
            
            $cp = new Concepto;
            $cp->descripcion_conceptos = $request->descripcion_cg;            
            $cp->opcion = $request->tipo;            
            $cp->estado = 1;            
            $cp->save();
            $id_cp = $cp->id;
            //-----Registro de la tabla Medico_consultorio ------------------------
            Session::flash('flash_message', 'Registro exitoso');
            return redirect('/listar_cgs/');
        }
    }

	public function desactivar_c($id, $estado){
	
		if (!Auth::check()):
			return redirect('/auth/login');
		endif;
		
		$id_user = Auth::user()->id;
			if($estado == "1"){
				$update=Concepto::where('id','=',$id)->first();
				$update->estado=3;
				$update->save();
				
				Session::flash('flash_message', 'Se desactivo correctamente');
			} else {
				$update=Concepto::where('id','=',$id)->first();
				$update->estado=1;
				$update->save();
				Session::flash('flash_message', 'Se activo correctamente');
			}
    }
	
	
	

}
