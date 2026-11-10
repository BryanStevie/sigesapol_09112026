<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\MotivoTransferencia;
use Session;
use DB;
use Illuminate\Support\Facades\Route;
use Auth;

class MotivoTransferenciaController extends Controller
{

  
	function __construct()
    {
        $this->middleware('auth'); 
    } 
    
    public function index() {
		//echo "entra";exit();
		
        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;

        $model = new MotivoTransferencia;
        $motivo = $model->ShowMotivoTransferenciaAll();
        $ruta = Route::getCurrentRoute()->getPath();
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        return view('frontend.motivo_transferencia.all', ['motivo' => $motivo, 'permiso' => $permiso]);
		
    }
	


    public function create()
    {
		if (!Auth::check()):
			return redirect('/auth/login');
		endif;

        $model= new MotivoTransferencia;
        $motivo=$model->getNroOrden();
        
        $orden=$motivo[0]->nro_orden;
        //print_r($orden);
        //exit();
        //$orden=13;
        return view('frontend.motivo_transferencia.create', compact('orden'));
    }	


    public function store(Request $request) {
        
        $motivo_transferencia = new MotivoTransferencia;
		
		$motivo_transferencia->orden=$request->orden;
        $motivo_transferencia->nombre=$request->nombre;
        $motivo_transferencia->abreviatura=$request->abreviatura;
		$motivo_transferencia->tipo_motivo=$request->tipo;
        $motivo_transferencia->estado=1;

        $nombre=$request->nombre;
		//echo $nombre ;exit();
        $existe = $motivo_transferencia->ExisteMotivoTransferencia($nombre);
        if ($existe) {
            return redirect('/all_motivo_transferencia')
                            ->withInput()
                            ->withErrors(array('No se ha podido registrar, Motivo de Transferencia ya existe'));
        } else {
            $motivo_transferencia->save();
            Session::flash('flash_message', 'Registro exitoso');
            return redirect('/all_motivo_transferencia');
        }

    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
		if (!Auth::check()):
			return redirect('/auth/login');
        endif;       
        $motivo= MotivoTransferencia::findOrFail($id);
        //echo $motivo ;exit();
		$id_user = Auth::user()->id;
        return view('frontend.motivo_transferencia.edit', compact('motivo'));
    }


    public function update(Request $request)
    {
        $update=MotivoTransferencia::where('id','=',$request->id)->first();        
		$update->orden=$request->orden;
        $update->nombre=trim($request->nombre);
        $update->abreviatura=trim($request->abreviatura);
        $update->tipo_motivo=$request->tipo;        
        $model=new MotivoTransferencia;
        $nombre=$request->nombre;
        $existe = $model->ExisteMotivoTransferencia($nombre, $request->id);
        //echo $existe ;exit();
		if ($existe) {
			return redirect('/all_motivo_transferencia')
							->withInput()
							->withErrors(array('No se ha podido actualizar, Motivo de Transferencia no existe'));
		}else{
			$update->save();
			return redirect('/all_motivo_transferencia')->withFlashSuccess("Se actualizo correctamente");
		}
    }


	public function eliminar($id){
		$update=MotivoTransferencia::where('id','=',$id)->first();
		$update->estado=0;
		$update->save();
		Session::flash('flash_message', 'Se elimino correctamente');
	}
	
	
}
