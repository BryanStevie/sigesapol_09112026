<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Farmacia;
use App\Models\Kardexe;
use App\Models\Pabellone;
use App\Models\Topico;
use App\Models\Anaquele;
use App\Models\Producto;
use App\Models\Producto_anaquele;
use Session;
use DB;
use App;
use Illuminate\Support\Facades\Route;
use Auth;

class AnaquelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;
		
	$id_user = Auth::user()->id;
        $modelfarma= new Farmacia();
        $modelanaquel= new Anaquele();
        $farmacias=$modelfarma->GetFarmaByUser($id_user);
        $id_farmacia=$farmacias->id;
        //$farmacia=$farmacias->nombre;
        $anaqueles=$modelanaquel->ShowallById_IdFarmacia($id_farmacia);
		
        $ruta = Route::getCurrentRoute()->getPath();
        if(count($ruta=explode("/",$ruta)) > 1);$ruta=$ruta[0];
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id,$ruta);
        $permiso = $menu[0];
        if($permiso->lectura == "")return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        return view('frontend.anaquel.all',['anaqueles' => $anaqueles,'permiso'=>$permiso]);
		
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
        $id_farmacia=$farmacias->id;
        return view('frontend.anaquel.create',compact('id_farmacia'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $nombre = trim($request->nombre);
        $anaquel = new Anaquele;
        $anaquel->nombre = trim($request->nombre);
        $anaquel->id_farmacia = $request->id_farmacia;

        $model_anaquel = new Anaquele;
        $existe = $model_anaquel->ExisteAnaquel($nombre, $request->id_farmacia);
        if ($existe) {
            return redirect('/all_anaquel')
                            ->withInput()
                            ->withErrors(array('No se ha podido registrar, Anaquel ya existe'));
        } else {
            $anaquel->save();
            Session::flash('flash_message', 'Registro exitoso');
            return redirect('/all_anaquel');
        }

        /*
          $pabellon->save();
          Session::flash('flash_message', 'Registro exitoso');
          return redirect('/all_pabellon');
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
    public function edit($id)
    {
		if (!Auth::check()):
			return redirect('/auth/login');
		endif;
		
        $anaquel = Anaquele::findOrFail($id);
		$id_user = Auth::user()->id;
        $modelfarma= new Farmacia();
        $farmacias=$modelfarma->GetFarmaByUser($id_user);
        $id_farmacia=$farmacias->id;
        return view('frontend.anaquel.edit', compact('anaquel','id_farmacia'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
	 
	 public function add_producto_anaquel($id_anaquel) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        //$model_farmacia = new Farmacia;
        //$model_transferencia = new Transferencia;
        /*
          $model_kardex = new Kardexe();
          $lotes = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmacia("",$id_farmacia);
          $tipo_transferencia = $model_transferencia->ObtenerTipoTransferencia($id_transferencia);
          $transferencia = $model_transferencia->GetTransferenciaById($id_transferencia);
          $farma = $model_farmacia->GetFarmaByIdfarma($transferencia->id_farmacia);
          $farmacia_origen = $farma->nombre;
         */
        //$productos = $model_transferencia->ShowLotesTransferidosById_transferencia_nuevo($id_transferencia);
        $id_user = Auth::user()->id;
        $modelfarma = new Farmacia();
        $model_producto = new Producto();
        $model_producto_anaquel = new Producto_anaquele();
        $farmacias = $modelfarma->GetFarmaByUser($id_user);
        $id_farmacia = $farmacias->id;

        $nivel = $modelfarma->ObtenerNivelFarmacia($id_farmacia);
        if($id_farmacia==36 || $id_farmacia==37){
            $productos = $model_producto->ProductosFarmaciasSegunNivel(2);
        }
        else
        {
          if($id_farmacia==113){
            $productos = $model_producto->ProductosFarmaciasSegunNivel(3);    
          }
          else
          {
            if($id_farmacia==132){
                $productos = $model_producto->getProductosByFarmacotecnia(76);
            }
            else{
                $productos = $model_producto->ProductosFarmaciasSegunNivel($nivel->nivel);
            }     
          }
        }

        $producto_anaquel = $model_producto_anaquel->ObtenerProductoPorIdAnaquel($id_anaquel);

        return view('frontend.anaquel.registrar_productos_anaquel', compact('productos', 'id_anaquel', 'producto_anaquel'));
    }

    public function eliminar_producto_anaquel($id) {
		
		if (!Auth::check()):
            return redirect('/auth/login');
        endif;
		
        $id_user = Auth::user()->id;
        $producto_anaquel = Producto_anaquele::find($id);
	    $id_anaquel = $producto_anaquel->id_anaquel;
        $producto_anaquel->estado = 0;
        $producto_anaquel->id_user_update = $id_user;
        $producto_anaquel->save();
        Session::flash('flash_message', 'Se elimino correctamente el producto del Anaquel');
        return redirect('/add_producto_anaquel/' . $id_anaquel);
    }
	
	
	public function agregar_producto_anaquel(Request $request) {
		
		if (!Auth::check()):
            return redirect('/auth/login');
        endif;
		
        $id_user = Auth::user()->id;
        $model_anaquel_productos = new Producto_anaquele;
        $model_anaquel_productos->id_anaquel = $request->id_anaquel;
        $model_anaquel_productos->id_producto = $request->id_producto;
        $model_anaquel_productos->estado = 1;
        $model_anaquel_productos->id_user_created = $id_user;

        $model_producto_anaquel = new \App\Models\Producto_anaquele;
        $existe = $model_producto_anaquel->ExisteProductoAnaquel($request->id_producto, $request->id_anaquel);

        if ($existe) {
            return redirect('/add_producto_anaquel/' . $request->id_anaquel)
                            ->withInput()
                            ->withErrors(array('No se ha podido registrar, Producto ya existe en este Anaquel'));
        } else {
            $id_farmacia = Auth::user()->id_farmacia;
            $existe_all = $model_producto_anaquel->ExisteProductoAnaquelAll($request->id_producto, $id_farmacia);
            if ($existe_all) {
                return redirect('/add_producto_anaquel/' . $request->id_anaquel)
                                ->withInput()
                                ->withErrors(array('No se ha podido registrar, Producto ya existe en otro Anaquel de la misma Farmacia'));
            } else {
                $model_anaquel_productos->save();
/*
                $upKardex = Kardexe::where('id_producto', '=', $request->id_producto)->where('id_farmacia', '=', $id_farmacia)->whereNull('id_anaquel')->first();
                $upKardex->id_anaquel = $request->id_anaquel;
                $upKardex->id_user_update = $id_user;
                $upKardex->save();
*/
                $modal_kardex = new Kardexe();
                $Updatekardex = $modal_kardex->UpdateIdAnaquelByProducto($request->id_producto, $request->id_anaquel, $id_farmacia, $id_user);                
                
                Session::flash('flash_message', 'Se agrego correctamento el producto al Anaquel');
                return redirect('/add_producto_anaquel/' . $request->id_anaquel);
            }
        }

        //$model_anaquel_productos->save();
        //Session::flash('flash_message', 'Se agrego correctamento el producto al Anaquel');
        //return redirect('/add_producto_anaquel/' . $request->id_anaquel);
    }

    public function update(Request $request)
    {
		$nombre=trim($request->nombre);
                $update=Anaquele::where('id','=',$request->id)->first();
		$update->nombre=trim($request->nombre);
		$model_anaquel=new Anaquele;
		$existe = $model_anaquel->ExisteAnaquel($nombre, $request->id_farmacia, $request->id);
		if ($existe) {
			return redirect('/all_anaquel')
							->withInput()
							->withErrors(array('No se ha podido actualizar, Anaquel ya existe'));
		}else{
			$update->save();
			return redirect('/all_anaquel')->withFlashSuccess("Se actualizo correctamente");
		}
		/*
		$update->save();
		return redirect('/all_pabellon')->withFlashSuccess("Se actualizo correctamente");
		*/
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
		$update=Anaquele::where('id','=',$id)->first();
		$update->estado=3;
		$update->save();
        $id_user = Auth::user()->id;
        
        $producto_anaquel = new Anaquele();
        $ok = $producto_anaquel->UpdateProductoAnaquel($id, $id_user);
    

		Session::flash('flash_message', 'Se elimino correctamente');
	}
	
}
