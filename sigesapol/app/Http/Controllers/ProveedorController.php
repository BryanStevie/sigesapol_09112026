<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Farmacia;
use App\Models\Proveedore;
use App\Models\Especializacione;
use App\Models\Establecimiento;
use App\Models\Medico;
use App\Models\Medico_turno;
use App\Models\Medico_consultorio;
use App\Models\Receta_medico;
use App\Models\Receta_vale;
use SoapClient;
use Session;
use DB;
use App;
use Auth;
use Illuminate\Support\Facades\Route;

class ProveedorController extends Controller
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
    
    public function update_proveedor(Request $request) {
            
			if (!Auth::check()):
            	return redirect('/auth/login');
        	endif;
		
            $id_user = Auth::user()->id;
            $proveedor = Proveedore::where('id', '=', $request->id_proveedor)->first();
            $proveedor->ruc_proveedor = $request->ruc_proveedor;
            $proveedor->nombre_proveedor = $request->nombre_proveedor;
            $proveedor->direccion_proveedor = $request->direccion_proveedor;
            $proveedor->telefono_proveedor = $request->telefono;
            $proveedor->fax_proveedor = $request->fax;
            $proveedor->email_proveedor = $request->email;
            $proveedor->save();
            
            Session::flash('flash_message', 'Actualización exitosa');
            return redirect('/listar_proveedores');

    }
    
    public function cambiar_medico_principal($id_receta_vale) {
        $receta= Receta_vale::where('id',$id_receta_vale)->first();
        $id_medico=$receta->id_medico;
        $model_medico=new Receta_medico();
        $model_especializacion= new Especializacione();
        //----------------------------------------------------------------------
        $datos_profesional=$model_especializacion->ObtenerEspcialidadDelProfesional($id_medico);
        $nombre_especializacion=$datos_profesional->nombre;
        $profesionales=$model_medico->obtenerProfesionalesPorEspecialidad($nombre_especializacion);
        return view('frontend.receta_vales.cambiar_medico_principal',compact('datos_profesional','profesionales','id_receta_medico','id_receta_vale'));
    }

    public function cambiar_profesional_salud($id_receta_medico) {
        $model_medico=new Receta_medico();
        $model_especializacion= new Especializacione();
        $profesional= Receta_medico::where('id',$id_receta_medico)->first();
        $id_medico=$profesional->id_medico;
        $id_receta_vale=$profesional->id_receta_vale;
        $datos_profesional=$model_especializacion->ObtenerEspcialidadDelProfesional($id_medico);
        $nombre_especializacion=$datos_profesional->nombre;
        $profesionales=$model_medico->obtenerProfesionalesPorEspecialidad($nombre_especializacion);
        return view('frontend.receta_vales.cambiar_profesionales',compact('datos_profesional','profesionales','id_receta_medico','id_receta_vale'));
    }
    
    public function editar_proveedor($id) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $id_user = Auth::user()->id;
        
        $proveedor = DB::table('proveedores')->where('proveedores.id', '=', $id)
                        ->select('proveedores.*')->first();
        
        return view('frontend.proveedores.editar_proveedor', compact('proveedor'));
    }

    
    /*public function create_personal_institucional($id_user){
        $modelfarma= new Farmacia();
        $modelIpress= new Establecimiento();
        $farmacias=$modelfarma->GetFarmaByUser($id_user);
        $id_ipress=$farmacias->id_establecimiento;
        $establecimiento=$modelIpress->GetById($id_ipress); 
        return view('frontend.medicos.create_personal_institucional',compact('establecimiento'));
    }*/

    public function existe_personal($dni){
		/*
		if(App::environment('desarrollo')):
        	$client = new SoapClient(env('WS_URL_DESARROLLO')."/ws/ws_AfiliadoSALUDPOL.php?wsdl", array('login' => 'userafis@ludpol', 'password' => "WS@ludPol@fi"));
		elseif(App::environment('local')):
        	$client = new SoapClient(env('WS_URL_REMOTO')."/ws/ws_AfiliadoSALUDPOL.php?wsdl", array('login' => 'userafis@ludpol', 'password' => "WS@ludPol@fi"));
		endif;
		*/
		$client = new SoapClient(env('WI_SALUDPOL_URL')."/ws/ws_AfiliadoSALUDPOL.php?wsdl", array('login' => env('WI_SALUDPOL_USERNAME1'), 'password' => env('WI_SALUDPOL_PASSWORD1')));
		
        $personal = $client->get_busafi_activo($dni);
        if(count($personal)>0){
            $dato=1;
            return $dato;
        }else{
            $dato=0;
            return $dato;
        }
    }
    
    public function verpersonal($dni){
		/*
		if(App::environment('desarrollo')):
        	$client = new SoapClient(env('WS_URL_DESARROLLO')."/ws/ws_AfiliadoSALUDPOL.php?wsdl", array('login' => 'userafis@ludpol', 'password' => "WS@ludPol@fi"));     
		elseif(App::environment('local')):   
        	$client = new SoapClient(env('WS_URL_REMOTO')."/ws/ws_AfiliadoSALUDPOL.php?wsdl", array('login' => 'userafis@ludpol', 'password' => "WS@ludPol@fi"));
		endif;
		*/
		$client = new SoapClient(env('WI_SALUDPOL_URL')."/ws/ws_AfiliadoSALUDPOL.php?wsdl", array('login' => env('WI_SALUDPOL_USERNAME1'), 'password' => env('WI_SALUDPOL_PASSWORD1')));
		
        $personal = $client->get_busafi_activo($dni);

            return($personal);

    }

    public function evitar_duplicados_medicos($dni) {
        $model_medico = new Medico;
        $medico = $model_medico->exite_medico($dni);        
        return $medico;
    }
    
    public function formulario_proveedor() {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        return view('frontend.proveedores.create_proveedor');
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
        $modelproveedor = new Proveedore();        
        $proveedores = $modelproveedor->getTodosProveedor();
        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1)
            ;$ruta = $ruta[0];
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];


        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

        return view('frontend.proveedores.all', ['proveedores' => $proveedores, 'permiso' => $permiso]);
    }

    public function cargaproveedor()
    {
        $modelproveedor = new Proveedore();        
        $proveedores = $modelproveedor->getProveedor();
        return $proveedores;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id_user)
    {
        //
        $modelfarma= new Farmacia();
        $modelIpress= new Establecimiento();
        $farmacias=$modelfarma->GetFarmaByUser($id_user);
        $id_ipress=$farmacias->id_establecimiento;
        $establecimiento=$modelIpress->GetById($id_ipress);        
        return view('frontend.medicos.create',compact('establecimiento'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        
        $modelproveedor= new Proveedore();
        $proveedor_registrado = $modelproveedor->medico_yaregistrado($request->ruc_proveedor);
        if(count($proveedor_registrado)>0){
            $dato = "El Proveedor <b>".$proveedor_registrado->nombre_proveedor." </b> identificado con RUC <b>".$proveedor_registrado->ruc_proveedor."</b> ya se encuentra registrado, en estado ";
            $dato.= $proveedor_registrado->estado == "1"? "<b>ACTIVO</b>":"<b>INACTIVO</b>";
            return redirect('/listar_proveedores/' . $request->id_user)->withInput()->withErrors(array($dato));
        } else {
            
            $proveedor = new Proveedore;
            $proveedor->ruc_proveedor = $request->ruc_proveedor;
            $proveedor->nombre_proveedor = $request->nombre_proveedor;
            $proveedor->direccion_proveedor = $request->direccion_proveedor;
            $proveedor->telefono_proveedor = $request->telefono;
            $proveedor->fax_proveedor = $request->fax;
            $proveedor->email_proveedor = $request->email;
            $proveedor->estado = 1;            
            $proveedor->save();
            $id_proveedor = $proveedor->id;
            //-----Registro de la tabla Medico_consultorio ------------------------
            Session::flash('flash_message', 'Registro exitoso');
            return redirect('/listar_proveedores/');
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
                    $update=Proveedore::where('id','=',$id)->first();
                    $update->estado=3;
                    //$update->id_user_desactivate = $id_user;
                    //$update->fec_desactivate = date('Y-m-d H:i:s');
                    $update->save();
                    
                    Session::flash('flash_message', 'Se desactivo correctamente');
                } else {
                    $update=Proveedore::where('id','=',$id)->first();
                    $update->estado=1;
                    //$update->id_user_activate = $id_user;
                    //$update->fec_activate = date('Y-m-d H:i:s');
                    $update->save();
                    Session::flash('flash_message', 'Se activo correctamente');
                }
	}
	
    public function listar_proveedores_ajax(Request $request)
      {
        
        $model_proveedor = new Proveedore();
        $draw = $request->draw;
        $start = ($request->start == 0) ? 1 : (int)$request->start / 10 + 1;
        $length = 10;
        $search_value = $request->search["value"];

        $p[] = (string)$start;
        $p[] = (string)$length;
        $p[] = $search_value;
        
        $data = $model_proveedor->listar_proveedores_ajax($p);

        $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;
        $result["draw"] = $draw;
        $result["recordsTotal"] = $iTotalDisplayRecords;
        $result["recordsFiltered"] = $iTotalDisplayRecords;
        $result["data"] = $data;

        echo json_encode($result);
      }
	
}
