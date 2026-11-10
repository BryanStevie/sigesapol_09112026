<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Dependencia;
use Session;
use DB;
use App;
use Auth;
use Illuminate\Support\Facades\Route;

class DependenciaController extends Controller
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
    
    public function update_dependencia(Request $request) {
            
			if (!Auth::check()):
            	return redirect('/auth/login');
        	endif;
		
            $id_user = Auth::user()->id;
            $dependencia = Dependencia::where('id', '=', $request->id_dependencia)->first();
            $dependencia->nombre_dependencia = $request->nombre_dependencia;
            $dependencia->opcion = $request->id_opcion;
            $dependencia->save();
            
            Session::flash('flash_message', 'Actualización exitosa');
            return redirect('/listar_dependencias');

    }
    
    public function editar_dependencia($id) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $id_user = Auth::user()->id;
        
        $dependencia = DB::table('dependencias')->where('dependencias.id', '=', $id)
                        ->select('dependencias.*')->first();
        
        return view('frontend.dependencias.editar_dependencia', compact('dependencia'));
    }

    
    public function formulario_dependencia() {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        return view('frontend.dependencias.create_dependencia');
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
        $modeldependencia = new Dependencia();        
        $dependencias = $modeldependencia->getTodasDependencias();
        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1)
            ;$ruta = $ruta[0];
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];


        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

        return view('frontend.dependencias.all', ['dependencias' => $dependencias, 'permiso' => $permiso]);
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
        $modeldependencia= new Dependencia();
        $dependencia_registrado = $modeldependencia->dependencia_yaregistrada($request->nombre_dependencia);
        if(count($dependencia_registrado)>0){
            $dato = "El Proveedor <b>".$dependencia_registrado->nombre_dependencia." </b> ya se encuentra registrado, en estado ";
            $dato.= $dependencia_registrado->estado == "1"? "<b>ACTIVO</b>":"<b>INACTIVO</b>";
            return redirect('/listar_dependencias/' . $request->id_user)->withInput()->withErrors(array($dato));
        } else {
            
            $dependencia = new Dependencia;
            $dependencia->nombre_dependencia = $request->nombre_dependencia;
            $dependencia->opcion = $request->id_opcion;
            $dependencia->estado = 1;            
            $dependencia->save();
            $id_dependencia = $dependencia->id;
            //-----Registro de la tabla Medico_consultorio ------------------------
            Session::flash('flash_message', 'Registro exitoso');
            return redirect('/listar_dependencias/');
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
            $id_user = Auth::user()->id;
                if($estado == "1"){
                    $update=Dependencia::where('id','=',$id)->first();
                    $update->estado=3;
                    $update->id_user_desactivate = $id_user;
                    $update->fec_desactivate = date('Y-m-d H:i:s');
                    $update->save();
                    
                    Session::flash('flash_message', 'Se desactivo correctamente');
                } else {
                    $update=Dependencia::where('id','=',$id)->first();
                    $update->estado=1;
                    $update->id_user_desactivate = $id_user;
                    $update->fec_desactivate = date('Y-m-d H:i:s');
                    $update->save();
                    Session::flash('flash_message', 'Se activo correctamente');
                }
	}

    public function listar_dependencias_ajax(Request $request)
      {
        
        $model_dependencias = new Dependencia();
        $draw = $request->draw;
        $start = ($request->start == 0) ? 1 : (int)$request->start / 10 + 1;
        $length = 10;
        $search_value = $request->search["value"];

        $p[] = (string)$start;
        $p[] = (string)$length;
        $p[] = $search_value;
        
        $data = $model_dependencias->listar_dependencias_ajax($p);

        $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;
        $result["draw"] = $draw;
        $result["recordsTotal"] = $iTotalDisplayRecords;
        $result["recordsFiltered"] = $iTotalDisplayRecords;
        $result["data"] = $data;

        echo json_encode($result);
      }
	
	
}
