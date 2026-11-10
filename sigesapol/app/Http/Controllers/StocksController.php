<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Stock;
use App\Models\Establecimiento;
use App\Models\Farmacia;
use App\Models\Proveedore;
use App\Models\Dependencia;
use App\Models\MotivoTransferencia;
use App\Models\Concepto;
use App\Models\Clasificador_gasto;
use App\Models\Tipo_documento;
use App\Models\Tipo_proceso;
use App\Models\Requerimiento;
use App\Models\Producto;
use App\Models\Tipo_producto;
use App\Models\Carga;
use App\Models\Stock_farmacia;
use App\Models\Stock_lote;
use App\Models\Kardexe;
use App\Models\Kardex_lote;
use App\Models\Consultorio;
use App\Models\Carga_inicial_stock;
use App\Models\Guia_entrada;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;
use App\Models\Carga_pecosa;
use App\Models\Junta_medica;
use App\Models\Req_producto;
use Validator;
use DB;
use SoapClient;
use App;
use Auth;
use Illuminate\Support\Facades\Route;
use App\Models\Genera_correlativonea;
use App\Models\Laboratorio;
use App\Models\User;
use App\Models\Receta_vale;
use DateTime;

class StocksController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('auth'); 
    } 
    
    public function pecosa_anterior_kardex($numero, $id_farmacia, $fecha) {
        if (!Auth::check()):
            return redirect('/auth/login'); 
        endif;
        $modelfarma = new Farmacia;
        $farmacia = $modelfarma->GetFarmaByIdfarma($id_farmacia);
        $pecosas = DB::table('kardexes')
                        ->join('productos', 'kardexes.id_producto', '=', 'productos.id')
                        ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
                        ->where('kardexes.numero_movimiento', '=', $numero)
                        ->where('kardexes.nombre_farmacia', '=', $farmacia->nombre)
                        ->select('kardexes.*', 'unidades.abreviatura as unidad')->get();
        return view('frontend.stocks.all_pecosas_anteriores', compact('pecosas', 'fecha', 'numero', 'farmacia'));
    }

    public function eliminar_producto_carga_pecosa($id_carga_pecosa, $id_guia_entrada, $id_user) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $update = Carga_pecosa::where('id', '=', $id_carga_pecosa)->first();
        $update->estado = 0;  //1:Carga pendiente, 2:Entrada finalizada, 0:eliminado    
        $update->save();
        Session::flash('flash_message', 'Se Elimino el registro');
        return redirect('/recepcionar_productos_pecosa/' . $id_guia_entrada . '/' . $id_user);
    }

    public function actualizar_producto_carga_pecosa(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $cad = "";
        $update = Carga_pecosa::where('id', '=', $request->id_carga_pecosa)->first();

        if ($update->lote != $request->lote):
            $cad = "LT(" . $update->lote . ")";
            $update->lote = $request->lote;
        endif;

        if ($update->registro_sanitario != $request->registro_sanitario):
            $cad = "RS(" . $update->registro_sanitario . ")";
            $update->registro_sanitario = $request->registro_sanitario;
        endif;

        if ($update->precio_promedio != $request->precio_promedio):
            $cad = "PU(" . $update->precio_promedio . ")";
            $update->precio_promedio = $request->precio_promedio;
        endif;

        if ($request->fecha_vencimiento != '0000-00-00 00:00:00' && $request->fecha_vencimiento != ''):
            $update->fecha_vencimiento = date("d-m-Y", strtotime($update->fecha_vencimiento));
            if ($update->fecha_vencimiento != $request->fecha_vencimiento):
                $cad = "FV(" . $update->fecha_vencimiento . ")";
                $update->fecha_vencimiento = $request->fecha_vencimiento;
            endif;
        endif;

        if($update->id_producto==11045 || $update->id_producto==15938 || $update->id_producto==14607 || $update->id_producto==14608):  //Gas
            $update->cantidad = $request->cantidad*1000; //conversion a Litros
        else:
            $update->cantidad = $request->cantidad;
        endif;

        if(isset($request->presentacion_comercial))
            $update->presentacion_comercial = $request->presentacion_comercial;

        $update->observacion = $request->observacion;

        $update->save();
        Session::flash('flash_message', 'Se actualizó correctamente los datos del producto registrado');
        return redirect('/recepcionar_productos_pecosa/' . $request->id_guia_entrada . '/' . $request->id_user);
    }

    public function editar_producto_carga_pecosa($id_carga_pecosa, $id_farmacia) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $model_farma = new Farmacia;
        $farmacia = $model_farma->GetFarmaByIdfarma($id_farmacia);
        $model_carga_pecosa = new Carga_pecosa();
        $productos = $model_carga_pecosa->GetProductoAgregado($id_carga_pecosa);
        if (count($productos) == 0)
            return redirect('/inicio')->withFlashSuccess("No existe la url ingresada");
        return view('frontend.stocks.editar_producto_pecosa', compact('productos', 'farmacia'));
    }

	public function editar_producto_pecosa_actualizar($id_carga_pecosa, $id_farmacia) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $model_farma = new Farmacia;
        $farmacia = $model_farma->GetFarmaByIdfarma($id_farmacia);
        $model_carga_pecosa = new Carga_pecosa();
        $productos = $model_carga_pecosa->GetProductoAgregado($id_carga_pecosa);        
        if (count($productos) == 0)
            return redirect('/inicio')->withFlashSuccess("No existe la url ingresada");
        
        return view('frontend.stocks.editar_producto_pecosa_actualizar', compact('productos', 'farmacia'));
        
    }
	
	/*
	public function eliminar_producto_pecosa_actualizar($id_carga_pecosa, $id_guia_entrada, $id_user, $id_producto) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $id_farmacia=Auth::user()->id_farmacia;

        $update = Carga_pecosa::where('id', '=', $id_carga_pecosa)->first();
        $update->estado = 0;  
        $update->save();

        $update_kardex = Kardex_lote::where('id_ingreso_salida', '=', $id_carga_pecosa)->first();
		if (count($update_kardex) > 0){
		
			$update_kardex->estado = 0;
			$update_kardex->save();
	
			$cad = "select count(*) total from kardex_lotes where id_kardex=".$update_kardex->id_kardex;
				$data = DB::select($cad);
	
			$cad2 = "select count(*) total_estado from kardex_lotes where estado=0 and id_kardex=".$update_kardex->id_kardex;
				$data2 = DB::select($cad2);
	
			if($data[0]->total==$data2[0]->total_estado){
				$cad3 = "update kardexes set estado=0 where id=".$update_kardex->id_kardex;
				$data3 = DB::update($cad3);    
			}
			
			$cad4 = "select sum(cantidad) total from kardex_lotes where estado=1 and id_kardex=".$update_kardex->id_kardex;
				$data4 = DB::select($cad4);
	
	
			$contar = "select count(*) contar from kardex_lotes where estado=1 and id_kardex=".$update_kardex->id_kardex;
				$data5 = DB::select($contar);
	
			if($contar>0){
				$cad5 = "update kardexes set cantidad=".$data4[0]->total." where id=".$update_kardex->id_kardex;
				$data5 = DB::update($cad5);    
			}
			
			Session::flash('flash_message', 'Se elimino el registro');
			
		}else{
			Session::flash('flash_message', 'No se pudo eliminar');
		}
        return redirect('/recepcionar_productos_pecosa/' . $id_guia_entrada . '/' . $id_user);
    }
	*/
	
	public function eliminar_producto_pecosa_actualizar($id_carga_pecosa, $id_guia_entrada, $id_user, $id_producto) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;        
        $id_farmacia=Auth::user()->id_farmacia;
        $update = Carga_pecosa::where('id', '=', $id_carga_pecosa)->first();
        $update->estado = 0;  
        $update->save();        
        $cad1 = "update kardex_lotes set estado=0 where id_ingreso_salida=". $id_carga_pecosa ." and id_kardex in (select id from kardexes where tipo_movimiento = 'IR' and id_nro_movimiento=".$id_guia_entrada." and id_farmacia=".$id_farmacia." and estado=1 and id_producto=".$id_producto." )";
        $data1 = DB::update($cad1);
        $cad = "select count(*) total from kardex_lotes where  id_kardex in (select id from kardexes where tipo_movimiento = 'IR' and id_nro_movimiento=".$id_guia_entrada." and id_farmacia=".$id_farmacia." and estado=1 and id_producto=".$id_producto." )";
            $data = DB::select($cad);       
        $cad2 = "select count(*) total_estado from kardex_lotes where estado=0 and id_kardex in (select id from kardexes where tipo_movimiento = 'IR' and id_nro_movimiento=".$id_guia_entrada." and id_farmacia=".$id_farmacia." and estado=1 and id_producto=".$id_producto." )";
        $data2 = DB::select($cad2);
        if($data[0]->total==$data2[0]->total_estado){
            $cad3 = "update kardexes set estado=0 where id in (select id from kardexes where tipo_movimiento = 'IR' and id_nro_movimiento=".$id_guia_entrada." and id_farmacia=".$id_farmacia." and estado=1 and id_producto=".$id_producto." )";
            $data3 = DB::update($cad3);    
        }
        else
        {
            $cad4 = "select sum(cantidad) total from kardex_lotes where estado=1 and id_kardex in (select id from kardexes where tipo_movimiento = 'IR' and id_nro_movimiento=".$id_guia_entrada." and id_farmacia=".$id_farmacia." and estado=1 and id_producto=".$id_producto." )";
            $data4 = DB::select($cad4);
            $cad5 = "update kardexes set cantidad=".$data4[0]->total." where id in (select id from kardexes where tipo_movimiento = 'IR' and id_nro_movimiento=".$id_guia_entrada." and id_farmacia=".$id_farmacia." and estado=1 and id_producto=".$id_producto." )";
                $data5 = DB::update($cad5);
            $contar = "select count(*) contar from kardex_lotes where estado=1 and id_kardex in (select id from kardexes where tipo_movimiento = 'IR' and id_nro_movimiento=".$id_guia_entrada." and id_farmacia=".$id_farmacia." and estado=1 and id_producto=".$id_producto." )";
                $data5 = DB::select($contar);  
            if($contar>0){
                $cad5 = "update kardexes set cantidad=".$data4[0]->total." where id in (select id from kardexes where tipo_movimiento = 'IR' and id_nro_movimiento=".$id_guia_entrada." and id_farmacia=".$id_farmacia." and estado=1 and id_producto=".$id_producto." )";
                $data5 = DB::update($cad5);    
            }  
        }   
        Session::flash('flash_message', 'Se elimino el registro');
        return redirect('/recepcionar_productos_pecosa/' . $id_guia_entrada . '/' . $id_user);
    }
	
	public function actualizar_producto_pecosa_directo(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $cad = "";
        $update = Carga_pecosa::where('id', '=', $request->id_carga_pecosa)->first();
        if ($update->lote != $request->lote):
            $cad = "LT(" . $update->lote . ")";
            $update->lote = $request->lote;
        endif;
        if ($update->registro_sanitario != $request->registro_sanitario):
            $cad = "RS(" . $update->registro_sanitario . ")";
            $update->registro_sanitario = $request->registro_sanitario;
        endif;
        if ($update->precio_promedio != $request->precio_promedio):
            $cad = "PU(" . $update->precio_promedio . ")";
            $update->precio_promedio = $request->precio_promedio;
        endif;
        if ($request->fecha_vencimiento != '0000-00-00 00:00:00' && $request->fecha_vencimiento != ''):
            $update->fecha_vencimiento = date("d-m-Y", strtotime($update->fecha_vencimiento));
            if ($update->fecha_vencimiento != $request->fecha_vencimiento):
                $cad = "FV(" . $update->fecha_vencimiento . ")";
                $update->fecha_vencimiento = $request->fecha_vencimiento;
            endif;
        endif;
        if($request->presentacion==1):
            $presentacion_comercial = $request->presentacion_comercial;
            $update->presentacion_comercial = $request->presentacion_comercial;
        else:
            $presentacion_comercial = 1;
            $update->presentacion_comercial = 1;
        endif;
        
        if($update->id_producto==11045 || $update->id_producto==15938 || $update->id_producto==14607 || $update->id_producto==14608):  //Gas
            $update->cantidad = $request->cantidad*1000; //conversion a Litros
        else:
            $update->cantidad = $request->cantidad;
        endif;   
        $update->observacion = $request->observacion;        
        $update->save();        
        if(count($update) > 0){        
            $cad1 = "update kardex_lotes set cantidad=".$request->cantidad." , fecha_vencimiento='".$request->fecha_vencimiento."' , precio_unitario='".$request->precio_promedio."' , registro_sanitario='".$request->registro_sanitario."' , lote='".$request->lote."', presentacion_comercial=".$presentacion_comercial."  where id_ingreso_salida=". $request->id_carga_pecosa ." and id_kardex in (select id from kardexes where tipo_movimiento = 'IR' and id_nro_movimiento=".$update->id_guia_entrada." and id_farmacia=".$request->id_farmacia." and estado=1 and id_producto=".$request->id_producto." )";
            $data1 = DB::update($cad1);
            $cad = "select sum(cantidad) total from kardex_lotes where estado=1 and id_kardex in (select id from kardexes where tipo_movimiento = 'IR' and id_nro_movimiento=".$update->id_guia_entrada." and id_farmacia=".$request->id_farmacia." and estado=1 and id_producto=".$request->id_producto.")" ;
            $data = DB::select($cad);
            $cad3 = "update kardexes set cantidad=".$data[0]->total." where  tipo_movimiento = 'IR' and id_nro_movimiento=".$update->id_guia_entrada." and id_farmacia=".$request->id_farmacia." and estado=1 and id_producto=".$request->id_producto;
            $data3 = DB::update($cad3);      
            Session::flash('flash_message', 'Se actualizó correctamente los datos del producto registrado');
        }else{
            Session::flash('flash_message', 'No se puedo actualizar');
        }
        return redirect('/recepcionar_productos_pecosa/' . $request->id_guia_entrada . '/' . $request->id_user);
    }
   
    public function editar_producto_nota_entrada($id_carga_pecosa, $id_farmacia) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $model_farma = new Farmacia;
        $farmacia = $model_farma->GetFarmaByIdfarma($id_farmacia);
        $model_carga_pecosa = new Carga_pecosa();
        $productos = $model_carga_pecosa->GetProductoAgregado($id_carga_pecosa);
		$model_guia_entrada= new Guia_entrada();
		$guia=Guia_entrada::where('id','=',$productos->id_guia_entrada)->first();
		$concepto=$guia->concepto;
		if (count($productos) == 0)
			return redirect('/inicio')->withFlashSuccess("No existe la url ingresada");
        return view('frontend.stocks.editar_producto_nota_entrada', compact('productos', 'farmacia','concepto'));
    }
	
	public function editar_producto_nota_entrada_actualizar($id_carga_pecosa, $id_farmacia) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $model_farma = new Farmacia;
        $farmacia = $model_farma->GetFarmaByIdfarma($id_farmacia);
        $model_carga_pecosa = new Carga_pecosa();
        $productos = $model_carga_pecosa->GetProductoAgregado($id_carga_pecosa);
         
        $model_guia_entrada= new Guia_entrada();
        
        $guia=Guia_entrada::where('id','=',$productos->id_guia_entrada)->first();

        
        
        $concepto=$guia->concepto;

        if (count($productos) == 0)
            return redirect('/inicio')->withFlashSuccess("No existe la url ingresada");

        
        if($farmacia->almacen==1):
            return view('frontend.stocks.editar_producto_nota_entrada_almacen_actualizar', compact('productos','farmacia','concepto'));
        else:
            return view('frontend.stocks.editar_producto_nota_entrada_actualizar', compact('productos', 'farmacia'));
        endif;
        
    }

    public function registrar_pecosa($id_farmacia = 0) {
        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;

        $id_farmacia = Auth::user()->id_farmacia;

        $model_farma = new Farmacia;
        $farmacia = $model_farma->GetFarmaByIdfarma($id_farmacia);
        $model_guias = new Guia_entrada();
        
        $model_kardex = new Kardexe();
        $anos = $model_kardex->ShowAnos();
        $new_ano = $anos[0]->ano;

        $pecosas = $model_guias->ShowPecosasByIdfarmacia($id_farmacia, $new_ano);
        
        $destino = $model_farma->GetFarmaciaDestinoRequerimiento();
        
        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1)
            ;$ruta = $ruta[0];
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
		
        $count = Guia_entrada::where('estado', '=', 1)->where('id_farmacia', '=', $id_farmacia)->count();
        
        if($farmacia->almacen==1)
            $count=0;

        //return view('frontend.stocks.all_pecosas', compact('destino','pecosas', 'farmacia', 'count', 'requerimientopen', 'anos', 'permiso'));
        return view('frontend.stocks.all_pecosas', compact('destino','pecosas', 'farmacia', 'count', 'anos', 'permiso'));
    }
    
	public function obtener_nroreque_por_id_origen($id_origen){
		
		if (!Auth::check()):
			return redirect('/auth/login');
		endif;
		
            $id_farmacia = Auth::user()->id_farmacia;
            $model_requerimiento = New Requerimiento;
            $nro = $model_requerimiento->GetNroRequerimientoPendienteByIdOrigen($id_origen, $id_farmacia);
            return $nro;
	}

    public function recepcionar_productos_pecosa($id_pecosa, $id_user) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByUser($id_user);
        $nombre_farmacia = $farma->nombre;
        $id_farmacia = $farma->id;
		$almacen = $farma->almacen;
        $flag_pecosa = $farma->flag_pecosa;
        $nivel = $model_farma->ObtenerNivelFarmacia($id_farmacia);
        //--------------------------------------------------------------------
        $model_pecosa = new Guia_entrada();
        $dato_pecosa = $model_pecosa->GetPecosaByIdPecosa($id_pecosa);
        $productos_recpecionados = $model_pecosa->ShowProductoByPecosa($id_pecosa);
        $nprod=count($productos_recpecionados);
        //exit(print_r($dato_pecosa));
        $id_requerimiento = trim($dato_pecosa->id_requerimiento);
        //--------------------------------------------------------------------
        if ($id_requerimiento <> "") {
            $model_reque = new Requerimiento();
            $productos = $model_reque->showProductosRequerimientoByIdPecosa($id_requerimiento);
        } else {
            $model_productos = new Producto;
            $productos = $model_productos->ProductosFarmaciasSegunNivel($nivel->nivel);
        }
        //--------------------------------------------------------------------
        $requerimiento = $model_reque->GetRequerimientoById($id_requerimiento);
        if(Auth::user()->flagresponsablefarmacia==1){            
            $date = Carbon::now();
            $fecha = $date->format('d-m-Y');   
            $fechaIngreso = Carbon::parse($requerimiento->updated_at);
            $fechaActual = Carbon::parse($fecha);
            $tiempo = $fechaActual->diffInDays($fechaIngreso);
        }
        else{
            $tiempo=31;
        }
        //--------------------------------------------------------------------

        $model_nea = new Genera_correlativonea;
        $nea = $model_nea->obtenerUltiNroNeaByFarmaAndAnio($id_farmacia);
        $nea = $nea[0];

        $model_pecosa = new Guia_entrada();
        $productos_recpecionados = $model_pecosa->ShowProductoByPecosa($id_pecosa);

        $id_farmacia = Auth::user()->id_farmacia;
        
        /*$model_kardex = new Kardexe();
        foreach ($productos_recpecionados as $k) {
            $encontrado = $model_kardex->GetBuscaMovimiento($k->id_producto, $id_farmacia, $k->fecha_carga);
            if($encontrado->mov > 0):
                $sw = 1; 
            endif;
        }*/
        
	    return view('frontend.stocks.recepcion_productos_pecosa', compact('dato_pecosa', 'productos_recpecionados', 'productos', 'nombre_farmacia', 'flag_pecosa', 'nea','tiempo','almacen','nprod','id_farmacia'));
    }

    public function add_producto_pecosa_mejorado(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $producto = Producto::where('id', $request->id_producto)->first();
        $existe = Carga_pecosa::where('id_guia_entrada', $request->id_guia_entrada)
                        ->where('id_producto', $request->id_producto)->where('estado', 1)->where('lote', $request->lote)->get();
        if (count($existe) > 0) {
            Session::flash('flash_message', 'El Producto ' . $producto->nombre . ' de lote ' . $request->lote . ' ya esta en la lista, solo edite la cantidad de ser necesario');
            return redirect('/recepcionar_productos_pecosa/' . $request->id_pecosa . '/' . $request->id_user);
        } else {
            $insert = new Carga_pecosa;
            $insert->id_farmacia = $request->id_farmacia;
            $insert->id_producto = $request->id_producto;
            $insert->registro_sanitario = $request->registro_sanitario;
            $insert->lote = $request->lote;
            $insert->fecha_vencimiento = $request->fecha_vencimiento;
            $insert->fecha_carga = $fecha;
            $insert->cantidad = $request->cantidad;
            if(isset($request->presentacion_comercial)&&$request->presentacion_comercial!='')            
                $insert->presentacion_comercial = $request->presentacion_comercial;
            $insert->id_guia_entrada = $request->id_guia_entrada;
            $insert->precio_promedio = $request->precio;
            $insert->estado = 1; //1:Carga pendiente, 2:Entrada finalizada, 0:eliminado
            $insert->save();
            Session::flash('flash_message', 'Producto recepcionado');
            return redirect('/recepcionar_productos_pecosa/' . $request->id_pecosa . '/' . $request->id_user);
        }
    }

    public static function add_producto_pecosa_mejorado_2($id_guia_entrada, $id_farmacia, $id_producto, $cantidad, $registro_sanitario, $lote, $fecha_vencimiento, $precio_promedio = 0) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $producto = Producto::where('id', $id_producto)->first();
        $existe = Carga_pecosa::where('id_guia_entrada', $id_guia_entrada)
                        ->where('id_producto', $id_producto)->where('lote', $lote)->get();
        if (count($existe) > 0) {
            //Session::flash('flash_message', 'El Producto '.$producto->nombre.' de lote ' . $lote . ' ya esta en la lista, solo edite la cantidad de ser necesario');
            //return redirect('/recepcionar_productos_pecosa/' . $request->id_pecosa . '/' . $request->id_user);
        } else {
            $insert = new Carga_pecosa;
            $insert->id_farmacia = $id_farmacia;
            $insert->id_producto = $id_producto;
            $insert->registro_sanitario = $registro_sanitario;
            $insert->lote = $lote;
            if ($fecha_vencimiento != '0000-00-00 00:00:00')
                $insert->fecha_vencimiento = $fecha_vencimiento;
            $insert->fecha_carga = $fecha;
            $insert->cantidad = $cantidad;
            $insert->id_guia_entrada = $id_guia_entrada;
            $insert->estado = 1; //1:Carga pendiente, 2:Entrada finalizada, 0:eliminado
            $insert->precio_promedio = $precio_promedio;
            $insert->save();
            //Session::flash('flash_message', 'Producto recepcionado');
            //return redirect('/recepcionar_productos_pecosa/' . $request->id_pecosa . '/' . $request->id_user);
        }
    }

    public function insert_producto_pecosa(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
         
        $model_receta_vale = new \App\Models\Receta_vale;
        $id_pecosas = $request->id_guia_entrada;
        $id_usuario = $request->id_user;
        $id_farmacia = $request->id_farmacia;
        $ano_nota = $model_receta_vale->getAnoServidor(); //Wilian
        $nota_entrada = str_pad($request->nota_entrada, 5, "0", STR_PAD_LEFT);
        
        $model_kardex = new Kardexe;
        $result = $model_kardex->postRegistrarIngreso($id_pecosas, $id_usuario, $id_farmacia, $ano_nota, $nota_entrada);
		
		$guia_entrada = Guia_entrada::where('id', '=', $request->id_guia_entrada)->first();
        $guia_entrada->estado = 2;
        $guia_entrada->ano_nota = $model_receta_vale->getAnoServidor(); //Wilian
        $guia_entrada->nota_entrada = str_pad($request->nota_entrada, 5, "0", STR_PAD_LEFT);
        $guia_entrada->save();


        $nea = Genera_correlativonea::where('anio_nea', $model_receta_vale->getAnoServidor())->where('id_farmacia', $id_farmacia)->first();
		if (!isset($nea)){
			$nea = new \App\Models\Genera_correlativonea;
			$nea->anio_nea = date("Y");
			$nea->id_farmacia = $id_farmacia;
			$nea->estado = 1;
			$nea->id_user_created = $id_usuario;
		}
        $nea->nro_nea = $request->nota_entrada;
        $nea->save();
        Session::flash('flash_message', 'Pecosa y Productos recepcionado e ingresados');
        return redirect('/recepcionar_productos_pecosa/' . $id_pecosas . '/' . $id_usuario);
    }
    
    public function insert_producto_pecosa2(Request $request) { 
        
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;         
        $model_receta_vale = new \App\Models\Receta_vale;
        $id_pecosas = $request->id_guia_entrada; //Procedimiento
        $id_usuario = $request->id_user;

        $id_farmacia = $request->id_farmacia;
        $ano_nota = $model_receta_vale->getAnoServidor(); //Wilian
        $nota_entrada = $request->nota_entrada_actu;
        $model_kardex = new Kardexe;

        if($request->registro==1){            
            //Insertar en kardexes            
            $model_carga_pecosa = new \App\Models\Carga_pecosa;
            $result_pecosas = $model_carga_pecosa->GetCargaPecosa($id_pecosas, $id_farmacia);            
            if(count($result_pecosas)>0){
                $date = Carbon::now();
                $fecha = $date->format('d-m-Y');   
                $fechaActual = Carbon::parse($fecha);                   
                foreach ($result_pecosas as $key => $result_pecosa) {
                        $kardex = new \App\Models\Kardexe;
                        $kardex->codigo_producto = $result_pecosa->codigo_producto;
                        $kardex->nombre_producto = $result_pecosa->nombre_producto;
                        $kardex->tipo_movimiento = "IR";
                        $kardex->numero_movimiento = $result_pecosa->nro_pecosa;
                        $kardex->origen = 'ALMACEN';                    
                        $kardex->destino = $result_pecosa->nombre_farmacia;                    
                        $kardex->estado = 1;
                        $kardex->cantidad = $result_pecosa->cantidadtot_producto;                    
                        $kardex->nombre_farmacia = $result_pecosa->nombre_farmacia;
                        $kardex->fecha_movimiento = $result_pecosa->fechaentrada_pecosa;
                        $kardex->id_tipo_movimiento = 2;                    
                        $kardex->id_producto = $result_pecosa->id_producto;                                                      
                        $kardex->id_nro_movimiento = $id_pecosas;
                        $kardex->id_origen = 4;
                        $kardex->id_destino = $id_farmacia;                    
                        $kardex->id_farmacia =  $id_farmacia;                        
                        $kardex->id_user_created =  $request->id_user;
                        $kardex->save();
                        $idkardex = $kardex->id;
                        $result_productos_pecosa = $model_kardex->GetProductoPecosa($id_pecosas,$result_pecosa->id_producto);
                        
                        foreach ($result_productos_pecosa as $key => $rowProductos) {
                            $kardex_lote = new \App\Models\Kardex_lote;
                            $kardex_lote->id_kardex = $idkardex;
                            $kardex_lote->lote = $rowProductos->lote;
                            if ($rowProductos->fecha_vencimiento != '0000-00-00 00:00:00' && $rowProductos->fecha_vencimiento != ''):
                                $kardex_lote->fecha_vencimiento = $rowProductos->fecha_vencimiento;
                            endif;
                            $kardex_lote->registro_sanitario = $rowProductos->registro_sanitario;
                            $kardex_lote->cantidad = $rowProductos->cantidad;                        
                            $kardex_lote->precio_unitario = $rowProductos->precio;
                            $kardex_lote->id_ingreso_salida = $rowProductos->id;
                            $kardex_lote->fecha_registro = $fechaActual;
                            $kardex_lote->save();
                        }
                } 
                $cad="Update guia_entradas Set estado=2 Where id=".$id_pecosas;
                $data = DB::update($cad);

                Session::flash('flash_message', 'Pecosa y Productos se ingresaron correctamente');
            }
        }
        else
        {            
            //Buscar guia_entrada y carga_pecosa los productos.            
            $model_carga_pecosa = new \App\Models\Carga_pecosa;
            $result_pecosas = $model_carga_pecosa->GetCargaPecosa($id_pecosas, $id_farmacia);
            
            if(count($result_pecosas)>0){
                $date = Carbon::now();
                $fecha = $date->format('d-m-Y');   
                $fechaActual = Carbon::parse($fecha);                   
                foreach ($result_pecosas as $key => $result_pecosa) {
                    
                    $kardex = Kardexe::where('id_nro_movimiento', '=', $id_pecosas)->where('id_farmacia', '=',$id_farmacia)->where('id_tipo_movimiento', '=',2)->where('estado', '=',1)->where('id_producto', '=',$result_pecosa->id_producto)->first();    
                    
                    if(count($kardex)>0){
                        $kardex->cantidad = $result_pecosa->cantidadtot_producto;                    
                        $kardex->id_user_update =  $request->id_user;
                        $kardex->save();
                        $idkardex = $kardex->id;
                        $result_productos_pecosa = $model_kardex->GetProductoPecosa($id_pecosas,$result_pecosa->id_producto);

                        //dd($result_productos_pecosa);
                        if(count($result_productos_pecosa)>0){
                            foreach ($result_productos_pecosa as $key => $rowProductos) {
                                $kardex_lote = Kardex_lote::where('id_ingreso_salida', '=', $rowProductos->id)->where('estado', '=',1)->first();

                                if(count($kardex_lote)>0){

                                    $kardex_lote->id_kardex = $idkardex;
                                    $kardex_lote->lote = $rowProductos->lote;
                                    if ($rowProductos->fecha_vencimiento != '0000-00-00 00:00:00' && $rowProductos->fecha_vencimiento != ''):
                                        $kardex_lote->fecha_vencimiento = $rowProductos->fecha_vencimiento;
                                    endif;
                                    $kardex_lote->registro_sanitario = $rowProductos->registro_sanitario;
                                    $kardex_lote->cantidad = $rowProductos->cantidad;                        
                                    $kardex_lote->precio_unitario = $rowProductos->precio;
                                    $kardex_lote->id_ingreso_salida = $rowProductos->id;
                                    $kardex_lote->fecha_registro = $fechaActual;
                                    $kardex_lote->save();    
                                }
                                else
                                {

                                    $kardex_lote = Kardex_lote::where('id_ingreso_salida', '=', $rowProductos->id)->where('estado', '=',1)->first();
                                    
                                    $kardex_lote = new \App\Models\Kardex_lote;
                                    $kardex_lote->id_kardex = $idkardex;
                                    $kardex_lote->lote = $rowProductos->lote;
                                    if ($rowProductos->fecha_vencimiento != '0000-00-00 00:00:00' && $rowProductos->fecha_vencimiento != ''):
                                        $kardex_lote->fecha_vencimiento = $rowProductos->fecha_vencimiento;
                                    endif;
                                    $kardex_lote->registro_sanitario = $rowProductos->registro_sanitario;
                                    $kardex_lote->cantidad = $rowProductos->cantidad;                        
                                    $kardex_lote->precio_unitario = $rowProductos->precio;
                                    $kardex_lote->id_ingreso_salida = $rowProductos->id;
                                    $kardex_lote->fecha_registro = $fechaActual;
                                    $kardex_lote->save();    
                                }
                                
                            }    
                        }
                    }
                    else
                    {
                        $kardex = new \App\Models\Kardexe;
                        $kardex->codigo_producto = $result_pecosa->codigo_producto;
                        $kardex->nombre_producto = $result_pecosa->nombre_producto;
                        $kardex->tipo_movimiento = "IR";
                        $kardex->numero_movimiento = $result_pecosa->nro_pecosa;
                        $kardex->origen = 'ALMACEN';                    
                        $kardex->destino = $result_pecosa->nombre_farmacia;                    
                        $kardex->estado = 1;
                        $kardex->cantidad = $result_pecosa->cantidadtot_producto;                    
                        $kardex->nombre_farmacia = $result_pecosa->nombre_farmacia;
                        $kardex->fecha_movimiento = $result_pecosa->fechaentrada_pecosa;
                        $kardex->id_tipo_movimiento = 2;                    
                        $kardex->id_producto = $result_pecosa->id_producto;                                                      
                        $kardex->id_nro_movimiento = $id_pecosas;
                        $kardex->id_origen = 4;
                        $kardex->id_destino = $id_farmacia;                    
                        $kardex->id_farmacia =  $id_farmacia;                        
                        $kardex->id_user_created =  $request->id_user;
                        $kardex->save();
                        $idkardex = $kardex->id;
                        $result_productos_pecosa = $model_kardex->GetProductoPecosa($id_pecosas,$result_pecosa->id_producto);
                        
                        foreach ($result_productos_pecosa as $key => $rowProductos) {
                            $kardex_lote = new \App\Models\Kardex_lote;
                            $kardex_lote->id_kardex = $idkardex;
                            $kardex_lote->lote = $rowProductos->lote;
                            if ($rowProductos->fecha_vencimiento != '0000-00-00 00:00:00' && $rowProductos->fecha_vencimiento != ''):
                                $kardex_lote->fecha_vencimiento = $rowProductos->fecha_vencimiento;
                            endif;
                            $kardex_lote->registro_sanitario = $rowProductos->registro_sanitario;
                            $kardex_lote->cantidad = $rowProductos->cantidad;                        
                            $kardex_lote->precio_unitario = $rowProductos->precio;
                            $kardex_lote->id_ingreso_salida= $rowProductos->id;
                            $kardex_lote->fecha_registro = $fechaActual;
                            $kardex_lote->save();
                        }
                    }
                } 
                Session::flash('flash_message', 'Pecosa y Productos se actualizaron correctamente');                   
            }
            else
            {
                Session::flash('flash_message', 'No existe producto alguno para actualizar');        
            }
        }            
        
        return redirect('/recepcionar_productos_guia_entrada/' . $id_pecosas . '/' . $id_usuario);
    }

    public function add_producto_pecosa(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $nueva_cantidad = $request->cantidad;
        //----------------Obtener nombre del producto --------------------------
        $model_producto = new Producto;
        $producto = $model_producto->GetIdProdByCodMed($request->codigo_producto);
        $id_producto = $producto->id;
        $nombre_producto = $producto->nombre;
        //----------------------------------------------------------------------
        $model_Stock = new Stock_farmacia;
        $producto_stock = $model_Stock->ExisteProdxCodigoAndIdfarmacia($request->codigo_producto, $request->id_farmacia);
        if (count($producto_stock) > 0) {
            $id_stock_farmacia = $producto_stock->id;
            $stock_farmacia = Stock_farmacia::where('id', '=', $id_stock_farmacia)->first();
            $cantidad_actual = $stock_farmacia->cantidad;
            $stock_farmacia->cantidad = $cantidad_actual + $nueva_cantidad;
            $stock_farmacia->save();
            //------------------------------------------------------------------
            $stock_lote = new \App\Models\Stock_lote();
            $stock_lote->id_stock_farmacia = $id_stock_farmacia;
            $stock_lote->registro_sanitario = $request->registro_sanitario;
            $stock_lote->lote = $request->lote;
            $stock_lote->cantidad = $request->cantidad;
            $stock_lote->fecha_vencimiento = $request->fecha_vencimiento;
            $stock_lote->fecha_ingreso = $request->fecha;
            $stock_lote->save();
        } else {
            $insert_stock = new Stock_farmacia;
            $insert_stock->codigo_producto = $request->codigo_producto;
            $insert_stock->nombre_producto = $nombre_producto;
            $insert_stock->cantidad = $request->cantidad;
            $insert_stock->nombre_farmacia = $request->nombre_farmacia;
            $insert_stock->id_farmacia = $request->id_farmacia;
            $insert_stock->id_producto = $id_producto;
            $insert_stock->save();
            $id_stock_farmacia = $insert_stock->id;
            //------------------------------------------------------------------
            $stock_lote = new \App\Models\Stock_lote();
            $stock_lote->id_stock_farmacia = $id_stock_farmacia;
            $stock_lote->registro_sanitario = $request->registro_sanitario;
            $stock_lote->lote = $request->lote;
            $stock_lote->cantidad = $request->cantidad;
			if(isset($request->presentacion_comercial))
                $stock_lote->presentacion_comercial = $request->presentacion_comercial;
            $stock_lote->fecha_vencimiento = $request->fecha_vencimiento;
            $stock_lote->fecha_ingreso = $request->fecha;
            $stock_lote->save();
        }
        $kardex = new Kardexe;
        $kardex->codigo_producto = $request->codigo_producto;
        $kardex->nombre_producto = $nombre_producto;
        $kardex->tipo_movimiento = 'IR';
        $kardex->numero_movimiento = $request->numero_movimiento;
        $kardex->origen = 'ALMACEN';
        $kardex->destino = $request->nombre_farmacia;
        $kardex->estado = 1;
        $kardex->id_tipo_movimiento = 2;
        $kardex->cantidad = $request->cantidad;
        $kardex->nombre_farmacia = $request->nombre_farmacia;
        $kardex->fecha_movimiento = $request->fecha;
        $kardex->save();
        Session::flash('flash_message', 'Producto recepcionado y registrado');
        return redirect('/recepcionar_productos_pecosa/' . $request->id_pecosa . '/' . $request->id_user);
    }
    

    public function add_producto_pecosa2(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $nueva_cantidad = $request->cantidad;
        //----------------Obtener nombre del producto --------------------------
        $model_producto = new Producto;
        $producto = $model_producto->GetIdProdByCodMed($request->codigo_producto);
        $id_producto = $producto->id;
        $nombre_producto = $producto->nombre;
        //----------------------------------------------------------------------
        $model_Stock = new Stock_farmacia;
        $producto_stock = $model_Stock->ExisteProdxCodigoAndIdfarmacia($request->codigo_producto, $request->id_farmacia);
        if (count($producto_stock) > 0) {
            $id_stock_farmacia = $producto_stock->id;
            $stock_farmacia = Stock_farmacia::where('id', '=', $id_stock_farmacia)->first();
            $cantidad_actual = $stock_farmacia->cantidad;
            $stock_farmacia->cantidad = $cantidad_actual + $nueva_cantidad;
            $stock_farmacia->save();
            //------------------------------------------------------------------
            $stock_lote = new \App\Models\Stock_lote();
            $stock_lote->id_stock_farmacia = $id_stock_farmacia;
            $stock_lote->registro_sanitario = $request->registro_sanitario;
            $stock_lote->lote = $request->lote;
            $stock_lote->cantidad = $request->cantidad;
            $stock_lote->fecha_vencimiento = $request->fecha_vencimiento;
            $stock_lote->fecha_ingreso = $request->fecha;
            $stock_lote->save();
        } else {
            $insert_stock = new Stock_farmacia;
            $insert_stock->codigo_producto = $request->codigo_producto;
            $insert_stock->nombre_producto = $nombre_producto;
            $insert_stock->cantidad = $request->cantidad;
            $insert_stock->nombre_farmacia = $request->nombre_farmacia;
            $insert_stock->id_farmacia = $request->id_farmacia;
            $insert_stock->id_producto = $id_producto;
            $insert_stock->save();
            $id_stock_farmacia = $insert_stock->id;
            //------------------------------------------------------------------
            $stock_lote = new \App\Models\Stock_lote();
            $stock_lote->id_stock_farmacia = $id_stock_farmacia;
            $stock_lote->registro_sanitario = $request->registro_sanitario;
            $stock_lote->lote = $request->lote;
            $stock_lote->cantidad = $request->cantidad;
            $stock_lote->fecha_vencimiento = $request->fecha_vencimiento;
            $stock_lote->fecha_ingreso = $request->fecha;
            $stock_lote->save();
        }
        $kardex = new Kardexe;
        $kardex->codigo_producto = $request->codigo_producto;
        $kardex->nombre_producto = $nombre_producto;
        $kardex->tipo_movimiento = 'IR';
        $kardex->numero_movimiento = $request->numero_movimiento;
        $kardex->origen = 'ALMACEN';
        $kardex->destino = $request->nombre_farmacia;
        $kardex->estado = 1;
        $kardex->id_tipo_movimiento = 2;
        $kardex->cantidad = $request->cantidad;
        $kardex->nombre_farmacia = $request->nombre_farmacia;
        $kardex->fecha_movimiento = $request->fecha;
        $kardex->save();
        Session::flash('flash_message', 'Producto recepcionado y registrado');
        return redirect('/recepcionar_productos_pecosa/' . $request->id_pecosa . '/' . $request->id_user);
    }

    public function add_pecosa(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $existe = Guia_entrada::where('id_farmacia', $request->id_farmacia)->where('numero', $request->numero)->where('estado', 2)->where('id_requerimiento', $request->id_requerimiento)->first();
        if (count($existe) > 0) {
            Session::flash('flash_message', 'El ingreso, ya se encuentra registrado.');
            return redirect('/registrar_pecosa/' . $request->id_farmacia);
        } else {

            $model_farma = new Farmacia;
            $farma = $model_farma->GetFarmaByIdfarma($request->id_farmacia);
            $rango_registro = $farma->rango_registro;
            $flag_pecosa = $farma->flag_pecosa;

            $fecha_hoy = date('j-m-Y');
            $fecha_pasada = strtotime('-' . $rango_registro . ' day', strtotime($fecha_hoy));
            $fecha_pasada = date('j-m-Y', $fecha_pasada);

            $f1 = strtotime($request->fecha_entrada);
            $f2 = strtotime($fecha_pasada);
            if ($f1 < $f2) {
                Session::flash('flash_message', 'La fecha solo puede estar comprendida entre hoy y ' . $rango_registro . ' días atrás');
                return redirect('/registrar_pecosa/' . $request->id_farmacia);
            } else {

                if($flag_pecosa == ""){
                    Session::flash('flash_message', 'Primero debe configurar el tipo de ingreso de la pecosa (MANUAL/AUTOMÁTICA)');
                    return redirect('/registrar_pecosa/');
                }
                
                if ($flag_pecosa == 1):

                    $pecosa = new Guia_entrada();
                    $pecosa->id_requerimiento = $request->id_requerimiento; //$id_requerimiento;
                    $pecosa->numero = $request->numero;
                    $pecosa->ano_nota = $request->ano;
                    $pecosa->fecha_entrada = $request->fecha_entrada;
                    $pecosa->id_farmacia = $request->id_farmacia;
                    $pecosa->id_user = $request->id_user;
                    $pecosa->estado = 1; //Pecosa registrada pero pendiente
                    $pecosa->save();
                    Session::flash('flash_message', 'Ingreso Registrado, ahora registre los productos llegados');
                    return redirect('/registrar_pecosa/' . $request->id_farmacia);

                elseif ($flag_pecosa == 2):

                    if (App::environment('desarrollo')):
                        $client = new SoapClient("http://192.168.0.243/monitoreo/webservices/server.php?wsdl", array('login' => 'userafis@ludpol', 'password' => "WS@ludPol@fi"));
                    elseif (App::environment('local')):
                        $client = new SoapClient("http://10.19.39.10:8088/webservices/server.php?wsdl", array('login' => 'userafis@ludpol', 'password' => "WS@ludPol@fi"));
                    endif;

                    $id_establecimiento = $farma->id_establecimiento;
                    $model_establecimiento = new Establecimiento;
                    $establecimiento = $model_establecimiento->GetById($id_establecimiento);
                    $cod_unidad = $farma->id_egestion;
                    //echo $cod_unidad;exit();
                    $result = $client->ObtenerDatosPecosa($request->numero, $request->ano, $cod_unidad, $request->id_farmacia);

                    if (count($result) > 0):

                        $obtenerId = DB::Transaction(function () use($request, $result) {

                                    $model_productos = new Producto;
                                    $pecosa = new Guia_entrada();
                                    $id_farmacia = $request->id_farmacia;
                                    $fecha = $request->fecha_entrada;

                                    $pecosa->numero = $request->numero;
                                    $pecosa->ano = $request->ano;
                                    $pecosa->fecha_entrada = $request->fecha_entrada;
                                    $pecosa->id_farmacia = $request->id_farmacia;
                                    $pecosa->id_user = $request->id_user;
                                    $pecosa->estado = 1; //Pecosa registrada pero pendiente
                                    $pecosa->save();
                                    $id_guia_entrada = $pecosa->id;

                                    foreach ($result as $row) {

                                        $data_producto = $model_productos->GetIdProdByCodMed($row->PRODCODIGO);
                                        if (!$data_producto):
                                            $data_unidad = $model_productos->GetIdUnidadByAbreviatura($row->UMNOMBRE);
                                            $data_tipo_producto = $model_productos->GetIdTipoProductoByIdGestion($row->CODTIPOPRODUCTO);
                                            $producto = new Producto;
                                            $producto->codigo = $row->PRODCODIGO;
                                            $producto->nombre = $row->PRODNOMBRE;
                                            $producto->id_tipo = $data_tipo_producto->id;
                                            $producto->id_unidad = $data_unidad->id;
                                            $producto->estado = "1";
                                            $producto->save();
                                            $data_producto = $model_productos->GetIdProdByCodMed($row->PRODCODIGO);
                                        endif;

                                        $id_producto = $data_producto->id;
                                        $lote = $row->NUMLOTE;
                                        $fecha_vencimiento = $row->FECHAVENCIMIENTO;
                                        $cantidad = $row->CANTIDADENTREGADA;
                                        $registro_sanitario = $row->REGISTROSANITARIO;
                                        $precio_promedio = $row->PRECIOUNITARIO_COSTOPROMEDIO;
                                        $this->add_producto_pecosa_mejorado_2($pecosa->id, $id_farmacia, $id_producto, $cantidad, $registro_sanitario, $lote, $fecha_vencimiento, $precio_promedio);
                                    }

                                    return $id_guia_entrada;
                                });

                        if ($obtenerId > 0) {
                            Session::flash('flash_message', 'Pecosa Registrada, ahora recepcione y registre los productos llegados');
                        } else {
                            Session::flash('flash_message', 'No se realizó la carga de la pecosa..!!');
                        }
                    else:
                        Session::flash('flash_message', 'No se pudo Registrar la Pecosa, no corresponde a la farmacia o año');
                    endif;

                    return redirect('/registrar_pecosa/' . $request->id_farmacia);

                endif;
            }
        }
    }

    public function add_pecosa_manual(Request $request) {
        $existe = Guia_entrada::where('id_farmacia', $request->id_farmacia)->where('numero', $request->numero)
                        ->where('estado', 2)->first();
        if (count($existe) > 0) {
            Session::flash('flash_message', 'Ya se encuentra registrado la pecosa');
            return redirect('/registrar_pecosa/' . $request->id_farmacia);
        } else {
            $fecha_hoy = date('j-m-Y');
            $fecha_pasada = strtotime('-30 day', strtotime($fecha_hoy));
            $fecha_pasada = date('j-m-Y', $fecha_pasada);

            $f1 = strtotime($request->fecha_entrada);
            $f2 = strtotime($fecha_pasada);
            if ($f1 < $f2) {
                Session::flash('flash_message', 'La fecha solo puede estar comprendida entre hoy y 30 días atrás');
                return redirect('/registrar_pecosa/' . $request->id_farmacia);
            } else {
                $pecosa = new Guia_entrada();
                $pecosa->numero = $request->numero;
                $pecosa->fecha_entrada = $request->fecha_entrada;
                $pecosa->id_farmacia = $request->id_farmacia;
                $pecosa->id_user = $request->id_user;
                $pecosa->estado = 1; //Pecosa registrada pero pendiente
                $pecosa->save();
                Session::flash('flash_message', 'Pecosa Registrada, ahora recepcione y registre los productos llegados');
                return redirect('/registrar_pecosa/' . $request->id_farmacia);
            }
        }
    }

    public function eliminar_pecosa($id_pecosa, $id_farmacia) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $existe = Guia_entrada::where('id', $id_pecosa)->first();
        if (count($existe) > 0) {

            //$delete_carga_pecosa = Carga_pecosa::where('id_guia_entrada', '=', $id_pecosa)->first();
            //$delete_carga_pecosa->delete();

            $carga_pecosa = Carga_pecosa::where('id_guia_entrada', '=', $id_pecosa)->get();
            foreach ($carga_pecosa as $row) {
                $delete_carga_pecosa = Carga_pecosa::where('id', '=', $row->id)->first();
                $delete_carga_pecosa->delete();
            }

            $delete_guia_entrada = Guia_entrada::where('id', '=', $id_pecosa)->first();
            $delete_guia_entrada->delete();

            Session::flash('flash_message', 'Se elimino la pecosa satisfactoriamente');
            return redirect('/registrar_pecosa/' . $id_farmacia);
        } else {
            Session::flash('flash_message', 'Ya se encuentra eliminada la pecosa');
            return redirect('/registrar_pecosa/' . $id_farmacia);
        }
    }

    public function insert_productos_tabla_stock_farmacias(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $obtenerId = DB::Transaction(function () use($request) {

                    $id_user = Auth::user()->id;
                    $id_farmacia = Auth::user()->id_farmacia;
                    $model_carga = new Carga;
                    $productos = $model_carga->ObtenerProductoPorIdCargaInicialAgrupadoByProducto($request->id_carga_incial);

                    foreach ($productos as $p) {
                        $model_producto_anaquel = new \App\Models\Producto_anaquele;
                        $existe_anaquel = $model_producto_anaquel->anaquelByIdProductoAndIdFarmacia($p->id_producto, $id_farmacia);

                        $insert_kardex = new Kardexe();
                        $insert_kardex->codigo_producto = $p->codigo_producto;
                        $insert_kardex->nombre_producto = $p->nombre_producto;
                        $insert_kardex->tipo_movimiento = 'S. Inicial';
                        $insert_kardex->numero_movimiento = '00000001';
                        $insert_kardex->origen = 'LIMA';
                        $insert_kardex->destino = $p->nombre_farmacia;
                        $insert_kardex->estado = 1;
                        $insert_kardex->cantidad = $p->cantidad;
                        $insert_kardex->nombre_farmacia = $p->nombre_farmacia;
                        $insert_kardex->fecha_movimiento = $p->fecha_ingreso;
                        $insert_kardex->id_tipo_movimiento = 1;
                        //$insert_kardex->precio_unitario = $p->precio_promedio;

                        $insert_kardex->id_producto = $p->id_producto;
                        $insert_kardex->id_nro_movimiento = $request->id_carga_incial;
                        $insert_kardex->id_origen = $id_farmacia;
                        $insert_kardex->id_destino = $id_farmacia;
                        $insert_kardex->id_farmacia = $id_farmacia;
                        if ($existe_anaquel) $insert_kardex->id_anaquel = $existe_anaquel->id_anaquel;
                        $insert_kardex->id_user_created = $id_user;
                        $insert_kardex->save();
                        $idkardex = $insert_kardex->id;

                        $productosLote = $model_carga->ObtenerProductoPorIdCargaInicialAndIdProducto($request->id_carga_incial, $p->id_producto);

                        foreach ($productosLote as $key => $rowProductosLote) {

                            $kardex_lote = new \App\Models\Kardex_lote;
                            $kardex_lote->id_kardex = $idkardex;
                            $kardex_lote->lote = $rowProductosLote->lote;
                            $kardex_lote->fecha_vencimiento = $rowProductosLote->fecha_vencimiento;
                            $kardex_lote->registro_sanitario = $rowProductosLote->registro_sanitario;
                            $kardex_lote->cantidad = $rowProductosLote->cantidad;
                            $kardex_lote->fecha_registro = $rowProductosLote->fecha_ingreso;
                            $kardex_lote->precio_unitario = $rowProductosLote->precio_promedio;
							$kardex_lote->id_ingreso_salida = $rowProductosLote->id;
                            $kardex_lote->save();
                        }
                    }

                    $update_carga_inicial = Carga_inicial_stock::where('id', '=', $request->id_carga_incial)->first();
                    $update_carga_inicial->estado = 2; // estdado 2: carga incial ingresado al Stock de la farmacia y kardexs
                    $update_carga_inicial->save();
                    $id_carga_incial = $update_carga_inicial->id;
                    return $id_carga_incial;
                });

        if ($obtenerId > 0) {
            Session::flash('flash_message', 'Se realizó la carga inicial de prodcutos al stock correctamente..!!');
            return redirect('/get_insert_cabecera_carga_inicial/' . $request->id_farmacia . '/' . $request->id_carga_incial);
        } else {
            Session::flash('flash_message', 'No se realizó la carga inicial al stock..!!');
            return redirect('/get_insert_cabecera_carga_inicial/' . $request->id_farmacia . '/' . $request->id_carga_incial);
        }
    }

    public function add_carga_inicial(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $model_farma = new Farmacia;
        $modelstock = new Stock_farmacia;
        $modelProducto = new Producto;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        foreach ($request->codigo_producto as $key => $value) {
            $producto = $modelProducto->GetIdProdByCodMed($request->codigo_producto[$key]);
            $nombre_producto = $producto->nombre;
            $carga = new Carga;
            $carga->codigo = $request->codigo_producto[$key];
            $carga->nombre = $nombre_producto;
            $carga->cantidad = $request->cantidad[$key];
            $carga->registro_sanitario = $request->registro_sanitario[$key];
            $carga->lote = $request->lote[$key];
            $carga->fecha_vencimiento = $request->fecha_vencimiento[$key];
            $carga->id_farmacia = $request->id_farmacia;
            $carga->fecha_ingreso = $fecha;
            $carga->save();
        }
        $modelcarga = New Carga;
        $modelStockFarmacia = new Stock_farmacia;
        $cargas = $modelcarga->ShowCargasById_farmacia($request->id_farmacia);
        $carga_lotes = $modelcarga->ShowCargasLotesById_farmacia($request->id_farmacia);
        foreach ($cargas as $ca) {
            $nombre_farma = $model_farma->GetFarmaByIdfarma($ca->id_farmacia);
            $nomfarma = $nombre_farma->nombre;
            $id = DB::table('stock_farmacias')->insertGetId(
                    ['codigo_producto' => $ca->codigo, 'nombre_producto' => $ca->nombre,
                        'cantidad' => $ca->cantidad, 'nombre_farmacia' => $nomfarma, 'id_farmacia' => $ca->id_farmacia
                    ]
            );
        }
        foreach ($carga_lotes as $cl) {
            $stock_farmacia = $modelStockFarmacia->GetProductoById_farmaciaAndCodProduto($cl->id_farmacia, $cl->codigo);
            $id_stock_farmacia = $stock_farmacia->id;
            $id = DB::table('stock_lotes')->insertGetId(
                    ['id_stock_farmacia' => $id_stock_farmacia,
                        'registro_sanitario' => $cl->registro_sanitario,
                        'lote' => $cl->lote,
                        'cantidad' => $cl->cantidad,
                        'fecha_vencimiento' => $cl->fecha_vencimiento,
                        'fecha_ingreso' => $cl->fecha_ingreso
                    ]
            );
        }
        $stocks = $modelstock->ShowStockByIdFarmacia($request->id_farmacia);
        foreach ($stocks as $s) {
            $id = DB::table('kardexes')->insertGetId(
                    ['codigo_producto' => $s->codigo_producto,
                        'nombre_producto' => $s->nombre_producto,
                        'tipo_movimiento' => 'S. Inicial',
                        'numero_movimiento' => '00000001',
                        'origen' => 'LIMA',
                        'destino' => $s->nombre_farmacia,
                        'estado' => 1,
                        'cantidad' => $s->cantidad,
                        'nombre_farmacia' => $s->nombre_farmacia,
                        'fecha_movimiento' => $fecha,
                        'id_tipo_movimiento' => 1
                    ]
            );
        }
        Session::flash('flash_message', 'Carga Inicial Exitosa');
        return redirect('/stocksbyFamarcia/' . $request->id_user);
    }

    public function buscar_codigo_producto($codigo_producto) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        return ($codigo_producto);
    }

    public function get_insert_cabecera_carga_inicial($id_farmacia, $id_carga_inicial) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $id_farmacia = Auth::user()->id_farmacia;
        $id_user = Auth::user()->id;

        $model_producto = new Producto();
        $model_farmacia = new Farmacia();
        $nivel = $model_farmacia->ObtenerNivelFarmacia($id_farmacia);
        $model_cargainicial = new Carga_inicial_stock();
        $cargainicial = $model_cargainicial->GetCargaRegistradaByFarmacia($id_farmacia);
        $modelCarga = new Carga();

        //$cabecera = Carga_inicial_stock::where('id_farmacia', '=', $id)->where('estado', '>=', 1)->get();
        if (count($cargainicial) == 0)
            return redirect('/formulario_importar_stock/' . $id_user);

        $productos_agregados = $modelCarga->ShowProductosCargadosByIdCabecera($cargainicial->id); //($id_carga_inicial);
        $cabecera = Carga_inicial_stock::where('id', '=', $cargainicial->id)->first(); //$id_carga_inicial)->first();
        $farmacia = Farmacia::where('id', '=', $id_farmacia)->first();

		if($farmacia->almacen==1){
            $productos = $model_producto->ProductosFarmaciasSegunNivel(3);
        }
        else
        {
            $productos = $model_producto->ProductosFarmaciasSegunNivel($nivel->nivel);    
        }
        
        return view('frontend.carga_inicial.from_agregar_productos_carga_inicial', compact('farmacia', 'cabecera', 'productos', 'productos_agregados'));
    }

    public function agregar_producto_tabla_cargas(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $date = Carbon::now();
        $fecha_actual = $date->format('d-m-Y');
        $f1 = strtotime($request->fecha_vencimiento);
        $f2 = strtotime($fecha_actual);
        $validator = Validator::make($request->all(), [
                    'id_producto' => 'required',
                    'registro_sanitario' => 'required:max:20',
                    'lote' => 'required:max:20',
                    'cantidad' => 'required:numeric',
                    'fecha_vencimiento' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect('/get_insert_cabecera_carga_inicial/' . $request->id_farmacia . '/' . $request->id_carga_inicial)
                            ->withInput()->withErrors($validator);
        }

        $producto = Producto::where('id', $request->id_producto)->first();
        $modelCarga = new Carga();
        $duplicado = $modelCarga->ValidaLoteDuplicado($request->lote, $request->id_producto, $request->id_carga_inicial);
        if ($duplicado > 0) {
            Session::flash('flash_message', 'El producto ' . $producto->nombre . ' con lote: ' . $request->lote . ' ya esta ingresado..!');
            return redirect('/get_insert_cabecera_carga_inicial/' . $request->id_farmacia . '/' . $request->id_carga_inicial);
        } else {
            if ($f1 < $f2) {
                Session::flash('flash_message', 'La Fecha de Vencimiento seleccionada no puede ser menor a la fecha de hoy');
                return redirect('/get_insert_cabecera_carga_inicial/' . $request->id_farmacia . '/' . $request->id_carga_inicial);
            } else {
                $carga = new Carga;
                $carga->codigo = $producto->codigo;
                $carga->nombre = $producto->nombre;
                $carga->registro_sanitario = $request->registro_sanitario;
                $carga->lote = $request->lote;
                $carga->cantidad = $request->cantidad;
                $carga->fecha_vencimiento = $request->fecha_vencimiento;
                $carga->id_farmacia = $request->id_farmacia;
                $carga->fecha_ingreso = $request->fecha_corte;
                $carga->id_producto = $request->id_producto;
                $carga->id_carga_inicial = $request->id_carga_inicial;
                $carga->estado = 1; //activo: 1, eliminado: 0
                $carga->save();
                Session::flash('flash_message', 'Producto agregado al carga inicila correctamente');
                return redirect('/get_insert_cabecera_carga_inicial/' . $request->id_farmacia . '/' . $request->id_carga_inicial);
            }
        }
    }

    public function eliminar_producto_cargado_tabla_cargas($id_carga, $id_carga_inicial, $id_farmacia) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $delete = Carga::where('id', '=', $id_carga)->first();
        /*
          $delete->estado = 0;
          $delete->save();
         */
        $delete->delete();
        Session::flash('flash_message', 'Se elimino el producto correctamente');
        return redirect('/get_insert_cabecera_carga_inicial/' . $id_farmacia . '/' . $id_carga_inicial);
    }

    public function update_producto_tabla_cargas(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $producto = Producto::where('id', $request->id_producto)->first();
        $update = Carga::where('id', $request->id_carga)->first();

        $update->lote = $request->lote;
        $update->registro_sanitario = $request->registro_sanitario;
        $update->fecha_vencimiento = $request->fecha_vencimiento;
        $update->cantidad = $request->cantidad;
        if ($request->precio_promedio <> "") {
            $update->precio_promedio = $request->precio_promedio;
        }
        $update->save();
        Session::flash('flash_message', 'Se actualizo los datos del producto ' . $producto->nombre . ' correctamente');
        return redirect('/get_insert_cabecera_carga_inicial/' . $request->id_farmacia . '/' . $request->id_carga_inicial);
    }

    public function editar_producto_cargado_tabla_cargas($id_carga) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $modelcarga = new Carga;
        $producto = $modelcarga->GetProductoById_carga($id_carga);
        $productos = DB::table('productos')->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
                        ->select('productos.*', 'unidades.abreviatura as unidad')->orderBy('productos.id_tipo', 'asc')->get();
        return view('frontend.carga_inicial.from_editar_producto_tabla_carga', compact('producto', 'productos'));
    }

    public function insert_cabecera_carga_inicial(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $modelFarmacia = new Farmacia();
        $farma = $modelFarmacia->GetFarmaByIdfarma($request->id_farmacia);
        $rango_registro = $farma->rango_registro;

        $fecha_obtenida = strtotime('-' . $rango_registro . ' day', strtotime($request->fecha_hoy));
        $fecha_obtenida = date('Y-m-j', $fecha_obtenida);
        $f1 = strtotime($fecha_obtenida);
        $f2 = strtotime($request->fecha_corte);
        if ($f2 < $f1) {
            Session::flash('flash_message', 'La fecha de carga inicial seleccionada (' . $request->fecha_corte . ') solo puede estar comprendida entre hoy y 30 días atrás');
            return redirect('/from_registrar_cabecera_carga_inicial/' . $request->id_farmacia);
        } else {
            $modelCarga = new Carga();
            $modelProducto = new Producto();
            $validator = Validator::make($request->all(), ['fecha_corte' => 'required']);
            if ($validator->fails()) {
                return redirect('/from_registrar_cabecera_carga_inicial/' . $request->id_farmacia)->withInput()->withErrors($validator);
            }
            $cabecera_carga = new Carga_inicial_stock();
            if (!isset($id)):
                $cabecera_carga->id_farmacia = $request->id_farmacia;
                $cabecera_carga->id_user = $request->id_user;
                $cabecera_carga->estado = 1;
                $cabecera_carga->fecha_corte = $request->fecha_corte;
                $cabecera_carga->save();
                $id = $cabecera_carga->id;
            endif;

            $productos_agregados = $modelCarga->ShowProductosCargadosByIdCabecera($id);
            $cabecera = Carga_inicial_stock::where('id', '=', $id)->first();
            $nivel = $modelFarmacia->ObtenerNivelFarmacia($request->id_farmacia);
            $productos = $modelProducto->ProductosFarmaciasSegunNivel($nivel->nivel);
            $farmacia = Farmacia::where('id', '=', $request->id_farmacia)->select('nombre')->first();
            Session::flash('flash_message', 'Se registro los datos de la Carga Incial');
            return view('frontend.carga_inicial.from_agregar_productos_carga_inicial', compact('farmacia', 'cabecera', 'productos', 'productos_agregados'));
        }
    }

    public function from_registrar_cabecera_carga_inicial($id/* ,$id_cabecera_carga=0 */) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $id = Auth::user()->id_farmacia;
        $farmacia = Farmacia::where('id', '=', $id)->select('nombre')->first();
        $cabecera = Carga_inicial_stock::where('id_farmacia', '=', $id)->where('estado', '>=', 1)->get();

        $date = Carbon::now();
        $fecha_hoy = $date->format('d-m-Y');

        //Jose
        $id_user = Auth::user()->id;
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByUser($id_user);
        $id_farmacia = $farma->id;
		$almacen = $farma->almacen;
        $model_establecimiento = new Establecimiento;
        $establecimiento = $model_establecimiento->GetByIdFarmacia($id_farmacia);
        //$nivel_establecimiento = $establecimiento->nivel;

        if (count($cabecera) == 0)
            return redirect('/formulario_importar_stock/' . $id_user);

        return view('frontend.carga_inicial.from_registrar_cabecera_carga_inicial', compact('fecha_hoy', 'farmacia', 'cabecera', 'establecimiento','id_farmacia','almacen'));
    }

    public function form_carga_inicial($id_user) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByUser($id_user);
        $id_farmacia = $farma->id;
        $model_producto = new Producto;
        $productos = $model_producto->ShowAll();
        return view('frontend.stocks.form_carga_inicial', compact('productos', 'id_farmacia'));
    }

    public function form_carga_inicial_personalizado($id_farmacia) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $model_carga = new \App\Models\Carga_inicial_stock;
        $model_farmacia = new Farmacia;
        $model_producto = new Producto;
        $farmacia = $model_farmacia->GetFarmaByIdfarma($id_farmacia);
        $productos = $model_producto->ShowAll();
        $datos_carga = $model_carga->GetCargaRegistradaByFarmacia($id_farmacia);
        if (count($datos_carga) > 0) {
            $model_lote = new \App\Models\Stock_lote;
            $lotes = $model_lote->ShowLotesById_farmacia($id_farmacia);
            return view('frontend.stocks.form_registrar_productos_iniciales', compact('productos', 'id_farmacia', 'datos_carga', 'farmacia', 'lotes'));
        } else {
            return view('frontend.stocks.form_carga_inicial_personalizado', compact('productos', 'id_farmacia', 'datos_carga', 'farmacia'));
        }
    }

    public function cerrar_carga_inicial($id_farmacia, $id_user) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $model_carga = \App\Models\Carga_inicial_stock::where('id_farmacia', '=', $id_farmacia)->first();
        $model_carga->estado = 1; //cerrado la carga inicial de stock
        $model_carga->save();
        Session::flash('flash_message', 'Se cerro la carga inicial de stock');
        return redirect('/stocksbyFamarcia/' . $id_user);
    }

    public function add_registrar_productos_iniciales(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        //----------------------------------------------------------------------
        $model_produto = new Producto;
        $producto = $model_produto->GetProductoById_producto($request->id_producto);
        $stock_actual = DB::table('stock_farmacias')->where('id_farmacia', '=', $request->id_farmacia)
                        ->where('id_producto', '=', $request->id_producto)->first();
        //var_dump($stock_actual); exit;        
        if (count($stock_actual) > 0) {
            $stock_update = Stock_farmacia::where('id_farmacia', '=', $request->id_farmacia)
                            ->where('id_producto', '=', $request->id_producto)->first();
            $cantidad_actual = $stock_update->cantidad;
            $stock_update->cantidad = $cantidad_actual + $request->cantidad;
            $stock_update->save();
            //----------Para registral el lote----------------------------------
            $id_stock_farmacia = $stock_update->id;
            $save_stock_lote = new \App\Models\Stock_lote;
            $save_stock_lote->id_stock_farmacia = $id_stock_farmacia;
            $save_stock_lote->registro_sanitario = $request->registro_sanitario;
            $save_stock_lote->lote = $request->lote;
            $save_stock_lote->cantidad = $request->cantidad;
            $save_stock_lote->fecha_vencimiento = $request->fecha_vencimiento;
            $save_stock_lote->fecha_ingreso = $request->fecha_ingreso;
            $save_stock_lote->save();
        } else {
            //---Para Guardar el stock de un producto --------------------------
            $save_stock_farmacia = new Stock_farmacia;
            $save_stock_farmacia->codigo_producto = $producto->codigo;
            $save_stock_farmacia->nombre_producto = $producto->nombre;
            $save_stock_farmacia->cantidad = $request->cantidad;
            $save_stock_farmacia->nombre_farmacia = $request->nombre_farmacia;
            $save_stock_farmacia->id_farmacia = $request->id_farmacia;
            $save_stock_farmacia->id_producto = $request->id_producto;
            $save_stock_farmacia->save();
            //----------Para registral el lote----------------------------------
            $id_stock_farmacia = $save_stock_farmacia->id;
            $save_stock_lote = new \App\Models\Stock_lote;
            $save_stock_lote->id_stock_farmacia = $id_stock_farmacia;
            $save_stock_lote->registro_sanitario = $request->registro_sanitario;
            $save_stock_lote->lote = $request->lote;
            $save_stock_lote->cantidad = $request->cantidad;
            $save_stock_lote->fecha_vencimiento = $request->fecha_vencimiento;
            $save_stock_lote->fecha_ingreso = $request->fecha_ingreso;
            $save_stock_lote->save();
        }
        $kardex = new Kardexe;
        $kardex->codigo_producto = $producto->codigo;
        $kardex->nombre_producto = $producto->nombre;
        $kardex->tipo_movimiento = 'S. Inicial';
        $kardex->numero_movimiento = '00000001';
        $kardex->origen = $request->nombre_farmacia;
        $kardex->destino = $request->nombre_farmacia;
        $kardex->estado = 1;
        $kardex->cantidad = $request->cantidad;
        $kardex->nombre_farmacia = $request->nombre_farmacia;
        $kardex->fecha_movimiento = $request->fecha_ingreso;
        $kardex->id_tipo_movimiento = 1;
        $kardex->save();
        Session::flash('flash_message', 'Registro existoso');
        return redirect('/form_carga_inicial_personalizado/' . $request->id_farmacia);
    }

    public function add_carga_inicial_personalizado(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $carga = new \App\Models\Carga_inicial_stock;
        $carga->id_farmacia = $request->id_farmacia;
        $carga->id_user = $request->id_user;
        $carga->estado = 0;
        $carga->fecha_corte = $request->fecha_corte;
        $carga->save();
        Session::flash('flash_message', 'Registro existoso, ahora registre los productos farmacéuticos');
        return redirect('/form_carga_inicial_personalizado/' . $request->id_farmacia);
    }

    public function show_almacen_central($id_user) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        //$client = new SoapClient("http://app02.saludpol.gob.pe:8085/sisafiliado/ws/ws_AfiliadoSALUDPOL.php?wsdl", array('login' => 'userafis@ludpol', 'password' => "WS@ludPol@fi"));
        $client = new SoapClient("http://www.saludpol.gob.pe/monitoreo/webservices/server.php?wsdl");
        $almacen = $client->ObtenerDatos();
        //var_dump($almacen);
        exit;
        return view('frontend.stocks.show_almacen_central');
    }

    public function cargarstock_farmacia($id_producto, $id_user) {
		//echo "como estas";exit();
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $model_producto = new Producto();
        $model_stock = new Stock_farmacia();
        $model_kardex = new Kardexe();

        $productos = $model_producto->GetCodByIdProducto($id_producto);
        $codigo_producto = $productos->codigo;
        $usuarios = DB::table('users')->where('id', '=', $id_user)->first();
        $id_farmacia = $usuarios->id_farmacia;

        $farmacia = DB::table('farmacias')->where('id', '=', $id_farmacia)->first();
        $nombre_farmacia = $farmacia->nombre;
		$id_establecimiento = $farmacia->id_establecimiento;
        $fecha_fin = $model_kardex->getFechaServidor();
		
		$id_farmacia_almacen = 113;
		//$total = $model_kardex->ShowTotalByIdProductoAndIdFarmacia($id_producto, $id_farmacia, $fecha_fin);
		$totalAlmacen = $model_kardex->ShowTotalCantidadByIdProductoAndIdEstablecimiento2($id_producto, $id_establecimiento, $id_farmacia_almacen);
        $cantidadAlmacen = isset($totalAlmacen[0]) ? $totalAlmacen[0]->total_suma : 0;
        //$totalEstablecimiento = $model_kardex->ShowTotalCantidadByIdProductoAndIdEstablecimiento($id_producto, $id_establecimiento, $fecha_fin);
		//$totalAlmacen = $model_kardex->ShowTotalByIdProductoAndIdFarmacia($id_producto, $id_farmacia_almacen, $fecha_fin);
        
		$cantidad = 0;
        if (isset($total->total) && $total->total > 0)$cantidad = $total->total;
		
		$cantidadEstablecimiento = 0;
        $totalEstablecimiento = $model_kardex->ShowTotalCantidadByIdProductoAndIdEstablecimiento2($id_producto, $id_establecimiento, 0);
        /*if (isset($totalEstablecimiento->total) && $totalEstablecimiento->total > 0)$cantidadEstablecimiento = $totalEstablecimiento->total;
		*/
        $cantidad_farmacia = 0;
      
      foreach ($totalEstablecimiento as $row) {
        if ($row->id_farmacia == $id_farmacia) {
            $cantidad_farmacia = $row->total_suma;
        }
        if($row->id_farmacia!=109 && $row->id_farmacia!=51 && $row->id_farmacia!=54 ){
            $cantidadEstablecimiento += $row->total_suma;
        }
        //if(Auth::user()->id == 115):
        //  echo $row->id_farmacia;echo $row->total_suma;
        //endif;
      }

		//if (isset($totalAlmacen->total) && $totalAlmacen->total > 0)$cantidadAlmacen = $totalAlmacen->total;
		
        $array = array();
        $array["cantidad"] = $cantidad_farmacia;
		$array["cantidadEstablecimiento"] = $cantidadEstablecimiento;
		$array["cantidadAlmacen"] = $cantidadAlmacen;

        echo json_encode($array);
    }

	public function cargarstock_establecimiento($id_producto, $id_user) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $model_kardex = new Kardexe();
        $usuarios = DB::table('users')->where('id', '=', $id_user)->first();
        $id_farmacia = $usuarios->id_farmacia;

        $farmacia = DB::table('farmacias')->where('id', '=', $id_farmacia)->first();
        $id_establecimiento = $farmacia->id_establecimiento;
        $fecha_fin = $model_kardex->getFechaServidor();
        $stock = $model_kardex->ShowTotalByIdProductoAndIdEstablecimiento($id_producto, $id_establecimiento, $fecha_fin);
        return $stock;
		
    }
	
    public function obtener_lotes_por_codprod_idfarma($codigo_producto, $id_farmacia, $cantidad_prescrita) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $producto = Producto::where('codigo', '=', $codigo_producto)->first();
        $model_stock = new Stock_farmacia();
        $lotes = $model_stock->ShowLotesById_productoAndId_farmacia($producto->id, $id_farmacia, $cantidad_prescrita);
        return $lotes;
    }

    public function obtener_lotes_por_idprod_idfarma($id_producto, $id_farmacia, $cantidad_prescrita) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        //$model_stock = new Stock_farmacia();
        $model_kardex = new Kardexe();

        //$lotes = $model_stock->ShowLotesById_productoAndId_farmacia($id_producto, $id_farmacia, $cantidad_prescrita);
        $lotes = $model_kardex->GetKardexLotesByIdProductoAndIdFarmaciaAndCantidad($id_producto, $id_farmacia, $cantidad_prescrita);

        return $lotes;
    }

    public function responsable_destino($id_farmacia) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $dato = DB::table('users')
                ->join('assigned_roles', 'users.id', '=', 'assigned_roles.user_id')
                ->join('roles', 'assigned_roles.role_id', '=', 'roles.id')
                ->where('users.id_farmacia', $id_farmacia)
                ->where('roles.id', 3)
                ->where('users.status', 1)
                ->select('users.id', 'users.name', 'users.paterno as paterno_usuario', 'users.materno as materno_usuario')
                ->groupBy('users.id', 'users.name', 'users.paterno', 'users.materno')
                ->orderBy('users.id')
                ->get();

        return $dato;
    }

    public function profesionales_destinos($id_farmacia) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $dato = DB::table('medicos')
                ->join('establecimientos', 'medicos.id_establecimiento', '=', 'establecimientos.id')
                ->join('farmacias', 'establecimientos.id', '=', 'farmacias.id_establecimiento')
                ->where('farmacias.id', $id_farmacia)
                ->select('medicos.id', 'medicos.nombre as name', 'medicos.paterno as paterno_usuario', 'medicos.materno as materno_usuario')
                ->get();
        return $dato;
    }

    
    public function profesionales_destino($id_ipress,$id_servicio) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $cad=" select c.id,m.id, m.nombre as name, m.paterno as paterno_usuario, m.materno as materno_usuario
                from medicos m
                Inner Join medico_consultorios mc On m.id=mc.id_medico
                Inner Join consultorios c On c.id=mc.id_consultorio

                Inner Join establecimientos est On m.id_establecimiento = est.id
                Where m.id_establecimiento=".$id_ipress." and c.id=".$id_servicio." and m.estado=1 and mc.estado = 1
                group By c.id, m.id, m.nombre, m.paterno, m.materno
                Order By m.paterno";
                
        $dato = DB::select($cad);
        
        return $dato;
    }

    public function editar_lotes($id) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $lote = DB::table('stock_lotes')
                        ->join('stock_farmacias', 'stock_lotes.id_stock_farmacia', '=', 'stock_farmacias.id')
                        ->join('farmacias', 'stock_farmacias.id_farmacia', '=', 'farmacias.id')
                        ->join('productos', 'stock_farmacias.codigo_producto', '=', 'productos.codigo')
                        ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
                        ->where('stock_lotes.id', '=', $id)
                        ->select('stock_lotes.*', 'stock_lotes.id as id_lote', 'farmacias.id as id_farmacia', 'farmacias.nombre as nombre_farmacia', 'stock_farmacias.*', 'unidades.abreviatura as unidad', 'stock_lotes.cantidad as cantidad_lote', 'stock_farmacias.id as id_stock_farmacia')->first();
        return view('frontend.stocks.editar_lote', compact('lote'));
    }

    public function update_lote(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        //$update=DB::table('stock_lotes')->where('id','=',$request->id_lote)->first();
        $update = Stock_lote::where('id', '=', $request->id_lote)->first();
        $update->lote = $request->lote;
        $update->registro_sanitario = $request->registro_sanitario;
        $update->fecha_vencimiento = $request->fecha_vencimiento;
        $update->save();
        Session::flash('flash_message', 'Actualización correcta del Lote ' . $request->lote);
        return redirect('/asignar_lote_vencimiento/' . $request->id_stock_farmacia);
    }

    public function asignar_lote_vencimiento($id_stock_farmacia) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $model_Stock = new Stock_farmacia;
        $producto = $model_Stock->GetStockByIdStockFarmacia($id_stock_farmacia);
        $disponible = count($model_Stock->GetCantGeneralStockLoteByProd($id_stock_farmacia));
        $lotes = $model_Stock->GetLotesById_stock_farmacia($id_stock_farmacia);
        if ($disponible > 0) {
            $cantidad = $model_Stock->GetCantGeneralStockLoteByProd($id_stock_farmacia);
            $cantidad_disponible = ($producto->cantidad) - $cantidad[0]->cantidad;
        } else {
            $cantidad_disponible = $producto->cantidad;
        }
        return view('frontend.stocks.add_lote_vencimiento_to_stock', compact('producto', 'cantidad_disponible', 'lotes'));
    }

    public function asignar_lote_vencimientos($id_producto, $id_farmacia) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        //$stock = Stock_farmacia::where('codigo_producto', $codigo_producto)->where('id_farmacia', $id_farmacia)->first();
        //$id_stock_farmacia = $stock->id;
        //$model_Stock = new Stock_farmacia;
        $model_kardex = new Kardexe();
        //$producto = $model_Stock->GetStockByIdStockFarmacia($id_stock_farmacia);

        $farmacia = DB::table('farmacias')->where('id', '=', $id_farmacia)->first();
        $nombre_farmacia = $farmacia->nombre;
        $fecha_fin = Kardexe::getFechaServidor();
        $kardex = Kardexe::ShowTotalByProd($id_producto, $nombre_farmacia, "", $fecha_fin);
        
        //$cantidad = $total->total;
        //$disponible = count($model_Stock->GetCantGeneralStockLoteByProd($id_stock_farmacia));
        //$lotes = $model_Stock->GetLotesById_stock_farmacia($id_stock_farmacia);

        $lotes = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmacia($id_producto, $id_farmacia);
        
        //dd($lotes);
        /*
          if ($disponible > 0) {
            $cantidad = $model_Stock->GetCantGeneralStockLoteByProd($id_stock_farmacia);
            $cantidad_disponible = ($producto->cantidad) - $cantidad[0]->cantidad;
          } else {
            $cantidad_disponible = $producto->cantidad;
          }
         */
        //exit(print_r($id_farmacia));
        return view('frontend.stocks.add_lote_vencimiento_to_stock', compact(/* 'producto', */'kardex', /* 'cantidad_disponible', */ 'lotes', 'id_farmacia'));
    }

    public function index() {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $stocks = Stock::paginate(15);
        return view('frontend.stocks.index', compact('stocks'));
    }

    public function reasignar_lotes(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $stock_lote = new \App\Models\Stock_lote;
        $stock_lote->id_stock_farmacia = $request->id_stock_farmacia;
        $stock_lote->registro_sanitario = $request->registro_sanitario;
        $stock_lote->lote = $request->lote;
        $stock_lote->cantidad = $request->cantidad;
        $stock_lote->fecha_vencimiento = $request->fecha_vencimiento;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $stock_lote->fecha_ingreso = $fecha;
        $stock_lote->save();
        Session::flash('flash_message', 'Asignación correcta');
        return redirect('/asignar_lote_vencimiento/' . $request->id_stock_farmacia);
    }

    public function registrar_requerimiento(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $model_requerimientos = new \App\Models\Requerimiento;
        //---------------------------------------------------------
        $usuario = $request->id_user;
        $requerimiento = new \App\Models\Requerimiento;
        $requerimiento->numero = $request->numero;
        $requerimiento->id_farmacia = $request->id_farmacia;
        $requerimiento->fecha = $request->fecha;
        $requerimiento->contador = $request->contador;
        $requerimiento->save();
        $id_requerimiento = $requerimiento->id;
        foreach ($request->codigo_producto as $key => $value) {
            $req_producto = new \App\Models\Req_producto();
            $req_producto->id_requerimiento = $id_requerimiento;
            $req_producto->codigo_producto = $request->codigo_producto[$key];
            $req_producto->cantidad = $request->cantidad_solicitada[$key];
            $req_producto->save();
        }
        $farmacia = $request->nombre_farmacia;
        $requerimientos = $model_requerimientos->ShowRequerimientosByIdFarmacia($request->id_farmacia);
        //var_dump($requerimientos); exit;
        Session::flash('flash_message', 'Registro exitoso');
        return view('frontend.stocks.all_requerimientos', compact('farmacia', 'requerimientos', 'usuario'));
        //return redirect('/stocksbyFamarcia/'.$request->id_user);
    }

    public function opcion_ingreso_regular(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $opcion = $request->opcion;
        if ($opcion == 1) {
            return redirect('/ver_requerimiento/' . $request->id_user);
        } else {
            $model_establecimiento = new Farmacia;
            $model_producto = new Producto;
            $productos = $model_producto->ShowAll();
            $farma = $model_establecimiento->GetFarmaByUser($request->id_user);
            $id_farmacia = $farma->id;
            $nombre_farmacia = $farma->nombre;
            //---------------------------------------------------------
            return view('frontend.stocks.formulario_addguia_sin_requerimiento', compact('nombre_farmacia', 'id_farmacia', 'fecha', 'productos'));
        }
    }

    public function actualizar_stock_farmacia(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $update_stock = Stock_farmacia::where('id', $request->id_stock_farmacia)->first();
        $update_stock->codigo_producto = $request->codigo_producto;
        $update_stock->nombre_producto = $request->nombre_producto;
        $update_stock->cantidad = $request->cantidad;
        $update_stock->save();
        Session::flash('flash_message', 'Actualización correcta');
        return redirect('/stocksbyFamarcia/' . $request->id_user);
    }

    public function update_kardex_sinicial(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $update_kardex = Kardexe::where('id', $request->id_kardex)->first();
        //var_dump($update_kardex); exit;
        $update_kardex->codigo_producto = $request->codigo;
        $update_kardex->nombre_producto = $request->producto;
        $update_kardex->cantidad = $request->cantidad;
        $update_kardex->fecha_movimiento = $request->fecha_movimiento;
        $update_kardex->save();
        Session::flash('flash_message', 'Actualización correcta');
        return redirect('/editar_kardex_sinicial/' . $request->id_user);
    }

    public function modificar_kardex_sinicial($id) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $model_kardex = new Kardexe();
        $kardex = $model_kardex->GetKardexSaldoInicialById($id);
        return view('frontend.stocks.editar_kardex_sinicial', compact('kardex'));
    }

    public function editar_stock_farmacia($id_user, $codigo_producto) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $model_farma = new Farmacia;
        $model_Stock = new Stock_farmacia;
        $farma = $model_farma->GetFarmaByUser($id_user);
        $id_farmacia = $farma->id;
        $farmacia = $farma->nombre;
        $productos = $model_Stock->ShowStockByIdFarmaciaAndCodigo($id_farmacia, $codigo_producto);
        return view('frontend.stocks.editar_stock_farmacia', compact('productos', 'farmacia'));
    }

    public function editar_kardex_sinicial($id_user) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $model_farma = new Farmacia;
        $model_kardex = new Kardexe();
        $farma = $model_farma->GetFarmaByUser($id_user);
        $nombre_farmacia = $farma->nombre;
        $saldo_inicial = $model_kardex->ShowSaldosInicialesByMovAndFarmacia('S. Inicial', $nombre_farmacia);
        return view('frontend.receta_vales.kardexs_saldo_inicial', compact('saldo_inicial'));
    }

    public function ver_requerimientos($id_user = 0) {
        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;
        $id_user = Auth::user()->id;
        //====================
        $usuario = $id_user;
        $model_requerimientos = new \App\Models\Requerimiento;
        
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByUser($id_user);
        $id_farmacia = $farma->id;
        $farmacia = $farma->nombre;
    
        $nombre_servicio = '';
        if(Auth::user()->id_consultorio!=''):
            $servicio = new Consultorio();
            $nombre_servicio = $servicio->GetNombreById(Auth::user()->id_consultorio)->nombre;
        endif;

        $requerimientos = $model_requerimientos->ShowRequerimientosByIdFarmacia($id_farmacia,Auth::user()->id_consultorio,Auth::user()->id_establecimiento);
        //exit(print_r($requerimientos));
        $exiten_requerimientos = Requerimiento::where('estado', 1)->where('id_farmacia', $id_farmacia)->get();
        
        $destino = $model_farma->GetEstablecimientoDestinoRequerimiento(Auth::user()->id_establecimiento);
        
        //====================
        $model_tipo_producto = new Tipo_producto();
        $tipo_productos = $model_tipo_producto->ShowAll();
        //var_dump($tipo_productos); exit();

        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1)
            ;$ruta = $ruta[0];
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        //====================
        return view('frontend.stocks.all_requerimientos', compact('destino','farmacia', 'requerimientos', 'usuario', 'tipo_productos', 'exiten_requerimientos', 'permiso','nombre_servicio'));
    }

    public function all_requerimientos_recibidos($id_user = 0) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $id_user = Auth::user()->id;
        //====================
        $usuario = $id_user;
        $model_requerimientos = new \App\Models\Requerimiento;
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByUser($id_user);
        $id_farmacia = $farma->id;
        $farmacia = $farma->nombre;
        /*if($id_farmacia==23):
            $id_farmacia=49;
        endif;*/
        $lista_requerimientos = $model_requerimientos->ShowRequerimientosByFarmacia($id_farmacia);
        //====================
        $model_tipo_producto = new Tipo_producto();
        $tipo_productos = $model_tipo_producto->ShowAll();
        //var_dump($tipo_productos); exit();

        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1)
            ;$ruta = $ruta[0];
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        //====================
        return view('frontend.stocks.all_requerimientos_recibidos', compact('farmacia', 'lista_requerimientos', 'usuario', 'tipo_productos','permiso'));
    }

    public function seleccion_requerimiento($id_user) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $usuario = $id_user;
        $model_requerimientos = new \App\Models\Requerimiento;
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByUser($id_user);
        $id_farmacia = $farma->id;
        $farmacia = $farma->nombre;
        $requerimientos = $model_requerimientos->ShowRequerimientosByIdFarmacia($id_farmacia);
        return view('frontend.stocks.form_seleccion_requerimiento', compact('farmacia', 'requerimientos', 'usuario'));
    }

    public function formulario_add_adicionales($id_user, $id_requerimiento) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByUser($id_user);
        $nombre_farmacia = $farma->nombre;
        $model_productos = new Producto;
        $productos = $model_productos->ShowAll();
        $model_requerimiento = new Requerimiento();
        $requerimiento = $model_requerimiento->ShowRequerimientosByIdRequerimiento($id_requerimiento);
        //====================================
        return view('frontend.stocks.adicionales_requerimientos', compact('nombre_farmacia', 'productos', 'id_requerimiento', 'requerimiento'));
    }

    public function registrar_adicionales_requerimiento(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        foreach ($request->id_producto as $key => $value) {
            $model_req_productos = new Req_producto();
            $exite = $model_req_productos->YaSeAgregoEsteProdAEsteRequerimiento($request->id_producto[$key], $request->id_requerimiento);
            if (count($exite) == 0) {
                $req_productos = new Req_producto();
                $req_productos->id_requerimiento = $request->id_requerimiento;
                $req_productos->codigo_producto = $request->id_producto[$key];
                $req_productos->cantidad = $request->cantidad[$key];
                $req_productos->save();
            }
        }
        Session::flash('flash_message', 'Registro correcto');
        return redirect('/ver_requerimiento/' . $request->id_user);
    }

    public function formulario_add_juntamedica($id_user) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        //====================
        $model_farma = new Farmacia;
        $model_producto = new Producto;
        //===============================
        $productos = $model_producto->ShowAll();
        $farma = $model_farma->GetFarmaByUser($id_user);
        $nombre_farmacia = $farma->nombre;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        //====================
        return view('frontend.stocks.formulario_add_juntamedica', compact('farma', 'nombre_farmacia', 'productos', 'fecha'));
    }

    public function generar_requerimiento(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $model_establecimiento = new Farmacia;
        $model_producto = new Producto;
        $model_requerimiento = new \App\Models\Requerimiento;
        //---------------------------------------------------------
        $nombre_farmacia = $request->nombre_farmacia;
        $id_farmacia = $request->id_farmacia;
        //var_dump($id_farmacia); exit;
        $establecimiento = $model_establecimiento->GetIpress($request->id_establecimiento);
        
        if($request->id_consultorio==0):
            $requerimiento = $model_requerimiento->GetContadorByIdFarmacia($id_farmacia);
        else:
            $requerimiento = $model_requerimiento->GetContadorByIdConsultorio($request->id_consultorio,$request->id_establecimiento);
        endif;

        //$requerimiento = $model_requerimiento->GetContadorByIdFarmacia($id_farmacia);
        $contador = $requerimiento->numero;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $aviso = 0;
        foreach ($request->codigo_producto as $key => $value) {
            if ($request->cantidad_solicitada[$key] > 0 && $request->cantidad_solicitada[$key] != "") {
                $dato_producto = $model_producto->GetDatosProductoByCodigo($request->codigo_producto[$key]);
                $nombre = $dato_producto->nombre;
                $unidad = $dato_producto->abreviatura;
                $productos = array('codigo' => $request->codigo_producto[$key], 'cantidad' => $request->cantidad_solicitada[$key],
                    'nombre' => $nombre, 'unidad' => $unidad);
                $listado[] = $productos;
                $aviso = 1;
            }
        }
        if ($aviso == 0) {
            Session::flash('flash_message', 'No solicito ningun requerimiento de productos');
            return view('frontend.stocks.formulario_requerimientos', compact('nombre_farmacia', 'establecimiento', 'fecha', 'id_farmacia', 'listado', 'aviso', 'contador'));
        } else {
            return view('frontend.stocks.formulario_requerimientos', compact('nombre_farmacia', 'establecimiento', 'fecha', 'id_farmacia', 'listado', 'aviso', 'contador'));
        }
    }

    public function update_pedido(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $model_establecimiento = new Farmacia;
        $model_producto = new Producto;
        $model_requerimiento = new \App\Models\Requerimiento;
        $destino = $model_establecimiento->GetFarmaciaDestinoRequerimiento();
        //---------------------------------------------------------

        $id_user = Auth::user()->id;
        $update = Requerimiento::where('id', '=', $request->id_requerimiento)->first();
        $update->documento = $request->documento;
        $update->tipo_producto_egestion = $request->tipo_producto_egestion;
        $update->id_user_update = $id_user;
        $update->id_destino = $request->id_destino;
        $update->save();

        return redirect('/ver_productos_del_pedido/' . $request->id_requerimiento . '/' . $request->tipo_producto_egestion . '/' . $request->id_farmacia);
    }

    public function addstock($id_user) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        //====================
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByUser($id_user);
        $id_farmacia = $farma->id;
        $nombre_farmacia = $farma->nombre;
        //====================
        return view('frontend.stocks.add_stock_farmacia', compact('nombre_farmacia', 'id_farmacia'));
    }

    public function stocksbyuser($id_user) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        //====================
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByUser($id_user);
        $id_farmacia = $farma->id;
        //====================
        $stocks = DB::table('stocks')
                ->join('productos', 'stocks.id_producto', '=', 'productos.id')
                ->select('stocks.id_producto', 'productos.nombre', DB::raw('sum(stocks.monto_inicial) as suma'), 'productos.codigo')
                ->where('stocks.id_farmacia', '=', $id_farmacia)
                ->where('stocks.estado', '=', 1)
                ->groupBy('stocks.id_producto', 'productos.nombre', 'productos.codigo')
                ->get();
        return view('frontend.stocks.index', compact('stocks', 'farma'));
    }

    public function add_stock_farmacia(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $stock = new Stock_farmacia;
        $stock->codigo_producto = $request->codigo_producto;
        $stock->nombre_producto = $request->nombre_producto;
        $stock->cantidad = $request->cantidad;
        $stock->nombre_farmacia = $request->farmacia;
        $stock->id_farmacia = $request->id_farmacia;
        $stock->save();
        Session::flash('flash_message', 'Update correcto');
        return redirect('/stocksbyFamarcia/' . $request->id_user);
    }

    public function anadir_kardex_sinicial(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $kardex = new Kardexe();
        $kardex->codigo_producto = $request->codigo_producto;
        $kardex->nombre_producto = $request->nombre_producto;
        $kardex->tipo_movimiento = $request->tipo_movimiento;
        $kardex->numero_movimiento = $request->numero_movimiento;
        $kardex->origen = $request->origen;
        $kardex->destino = $request->destino;
        $kardex->estado = $request->estado;
        $kardex->cantidad = $request->cantidad;
        $kardex->nombre_farmacia = $request->nombre_farmacia;
        $kardex->fecha_movimiento = $request->fecha_movimiento;
        $kardex->id_tipo_movimiento = $request->id_tipo_movimiento;
        $kardex->save();
        Session::flash('flash_message', 'Update correcto');
        return redirect('/stocksbyFamarcia/' . $request->id_user);
    }

    public function busqueda_receta_personalizada(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $model_kardex = new Kardexe();
        $id_farmacia = $request->id_farmacia;
        $receta = $model_kardex->GetRecetaByNroRecetaAndCodProductoAndFarma($request->nro_receta, $request->codigo_producto, $request->nombre_farmacia);
        //var_dump($receta); exit;
        return view('frontend.receta_vales.actualizar_receta', compact('receta', 'id_farmacia'));
    }

    public function update_receta_personalizada(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $kardex = Kardexe::where('id', $request->id_kardex)->first();
        $kardex->cantidad = $request->cantidad;
        $kardex->save();
        $stock = Stock_farmacia::where('id_farmacia', $request->id_farmacia)->where('codigo_producto', $request->codigo_producto)->first();
        $nueva_cantidad = $request->cantidad_antigua - $request->cantidad;
        $cantidad_actual = $stock->cantidad;
        $stock->cantidad = $cantidad_actual + $nueva_cantidad;
        $stock->save();
        $receta = \App\Models\Receta_vale::where('nro_receta', $request->numero_movimiento)->first();
        //var_dump($receta); exit;
        $id_receta = $receta->id;
        $producto = Producto::where('codigo', $request->codigo_producto)->first();
        $id_producto = $producto->id;
        $producto_receta = \App\Models\Producto_receta::where('id_receta_vale', $id_receta)->where('id_producto', $id_producto)->first();
        $producto_receta->cantidad_prescrita = $request->cantidad;
        $producto_receta->cantidad_dispensada = $request->cantidad;
        $producto_receta->save();
        Session::flash('flash_message', 'Update correcto');
        return redirect('/stocksbyFamarcia/' . $request->id_user);
    }

    public function corregir_receta($id_user) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByUser($id_user);
        $nombre_farmacia = $farma->nombre;
        $id_farmacia = $farma->id;
        return view('frontend.receta_vales.corregir_receta', compact('nombre_farmacia', 'id_farmacia'));
    }

    public function add_kardex_sinicial() {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        return view('frontend.stocks.add_kardex_sinicial');
    }

    public function form_generar_rerporte_ici_by_farma($id_farmacia) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
        $nombre_farmacia = $farma->nombre;
        $date = Carbon::now();
        $fecha_actual = $date->format('d-m-Y');
        $f_inicio_operaciones = Kardexe::where('nombre_farmacia', '=', $nombre_farmacia)
                        ->where('id_tipo_movimiento', '=', 1)->min('fecha_movimiento');
        $count_f_inicio_operaciones = Kardexe::where('nombre_farmacia', '=', $nombre_farmacia)
                        ->where('id_tipo_movimiento', '=', 1)->count();
        //var_dump($count_f_inicio_operaciones);exit();
        return view('frontend.stocks.form_generar_rerporte_ici_by_farma', compact('id_farmacia', 'nombre_farmacia', 'fecha_actual', 'f_inicio_operaciones', 'count_f_inicio_operaciones'));
    }

    public function generar_reporte_demanda_nosatisfecha($id_farmacia) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
        $nombre_farmacia = $farma->nombre;
        return view('frontend.direoss.stocks.generar_reporte_demanda_nosatisfecha', compact('id_farmacia', 'nombre_farmacia'));
    }

    public function otros_reportes_by_farma($tipo = 0, $id_farmacia = 0) {
        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;

        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1)
            ;$ruta = $ruta[0];
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

        if ($id_farmacia == 0)
            $id_farmacia = Auth::user()->id_farmacia;
        //------------------------------------------------------
        $model_farma = new Farmacia;
        $model_producto = new Producto();
        $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
        $nombre_farmacia = $farma->nombre;
        $nivel = $model_farma->ObtenerNivelFarmacia($id_farmacia);
        if($id_farmacia!=132):
            $productosnivel = $model_producto->ProductosFarmaciasSegunNivel($nivel->nivel);
        else:
            $productosnivel = $model_producto->getProductosByFarmacotecnia(76);
        endif;

        
        $date = Carbon::now();
        $fecha_actual = $date->format('d-m-Y');
        $f_inicio_operaciones = Kardexe::where('nombre_farmacia', '=', $nombre_farmacia)->where('id_tipo_movimiento', '=', 1)->min('fecha_movimiento');
        if(count($f_inicio_operaciones)==0){
            $f_inicio_operaciones = Kardexe::where('nombre_farmacia', '=', $nombre_farmacia)->where('id_tipo_movimiento', '=', 9)->min('fecha_movimiento');
            $count_f_inicio_operaciones = Kardexe::where('nombre_farmacia', '=', $nombre_farmacia)->where('id_tipo_movimiento', '=', 9)->count();
        }
        else{
            $count_f_inicio_operaciones = Kardexe::where('nombre_farmacia', '=', $nombre_farmacia)->where('id_tipo_movimiento', '=', 1)->count();    
        }
            
        
        $farmacias = $model_farma->ShowFarmacias();

        /*
          $reportes[8] = "Por Productos atendidos";
          $reportes[3] = "Por Productos no atendidos";
          $reportes[4] = "Por Diagnostico";
          $reportes[6] = "Por Servicios";
          $reportes[7] = "Por Tipo de Beneficiarios";
          $reportes[13] = "Por Beneficiarios";
          $reportes[14] = "Lista de Beneficiarios";
          $reportes[10] = "Por Productos con Stock con Promedio mensual";
          $reportes[11] = "Por Productos segun mes de vencimiento";
          $reportes[12] = "Por Productos sin Stock";
          $titulo_menu = $reportes[$tipo];
         */
        $menu = App\Models\Menu::getMenuByUsuario(Auth::user()->id, 'otros_reportes_by_farma/' . $tipo);
        //$titulo_menu = $menu[0]->nombremenu;
		$titulo_menu =(isset($menu[0]->nombremenu) && $menu[0]->nombremenu!="")?$menu[0]->nombremenu:"";

        /*
          $titulo_menu = Menu::where('nombre_farmacia', '=', $nombre_farmacia)
          ->where('id_tipo_movimiento', '=', 1)->min('fecha_movimiento');
         */
        $examenes = null;
        if ($tipo == 17) {
            $laboratorio = new App\Models\Laboratorio();
            $examenes = $laboratorio->listarExamenesLaboratorioConDatosPersonalizados();
        }
        //------------------------------------------------------ 
        return view('frontend.direoss.stocks.otros_reportes_by_farma', compact('id_farmacia', 'nombre_farmacia', 'fecha_actual', 'f_inicio_operaciones', 'count_f_inicio_operaciones', 'farmacias', 'tipo', 'titulo_menu', 'productosnivel', 'examenes'));
    }

    public function otros_resporte_by_farmacia(Request $request) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        if (!$request->id_farmacia):
            return redirect('/inicio');
        endif;

        $id_farmacia = $request->id_farmacia;
        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;
        $tipo = $request->tipo;

        $menu = App\Models\Menu::getMenuByUsuario(Auth::user()->id, 'otros_reportes_by_farma/');
        $menu_ = App\Models\Menu::getMenuByUsuario(Auth::user()->id, 'otros_reportes_by_farma/' . $tipo);
        $titulo_menu = $menu_[0]->nombremenu;

        //------------------------------------------------------
        $model_stock = new Stock_farmacia;
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
        $nombre_farmacia = $farma->nombre;

        //------------------------------------------------------
        $model_receta = new \App\Models\Receta_vale;
        //------------------------------------------------------
        $fecha_inicio_operaciones = $request->fecha_inicio_operaciones;
        $f1 = strtotime($fecha_inicio);
        $f2 = strtotime($fecha_inicio_operaciones);
        $f3 = strtotime($fecha_fin);
        //echo $f1."|".$f2;exit();
        //----------------------------------------------------------------------
        //exit(print_r($tipo));
        if ($f1 < $f2) {
            Session::flash('flash_message', 'La fecha de inicio seleccionada (' . $fecha_inicio . ') no debe ser menor fecha de inicio del uso del aplicativo (' . $fecha_inicio_operaciones . ').');
            return redirect('/otros_reportes_by_farma/' . $tipo . '/' . $request->id_farmacia);
        } else {
            if ($f1 > $f3) {
                Session::flash('flash_message', 'La fecha de inicio seleccionada (' . $fecha_inicio . ') no debe ser mayor fecha fin seleccionada (' . $fecha_fin . ').');
                return redirect('/otros_reportes_by_farma/' . $tipo . '/' . $request->id_farmacia);
            } else {

                if ($tipo == 3) {
                    $productos = $model_receta->ShowDemanadaNoSatisfechaByFarma($fecha_inicio, $fecha_fin, $id_farmacia);
                    $total_desabastecimiento = $model_receta->GetTotalDemanadaNoSatisfechaByFarma($fecha_inicio, $fecha_fin, $id_farmacia);
                    //----------------------------------------------------------
                    $model_kardex = new Kardexe();
                    $stocks = $model_kardex->ShowTotalKardexByFarmacia($nombre_farmacia, $fecha_fin);

                    $datos_productos = $model_receta->obtenerDatosProductos();
                    $mes_anio = array();
                    $productoos = $model_receta->obternerProductosNoAtendidos($fecha_inicio, $fecha_fin, $id_farmacia);
                    foreach ($productoos as $arr) {
                        $mes_anio[] = $arr->fecha_anio;
                    }
                    $mes_anio = array_unique($mes_anio);
                    $meses = $model_receta->obtener_mes();
                    return view('frontend.direoss.stocks.form_demanda_nosatisfecha', compact('id_farmacia', 'nombre_farmacia', 'fecha_inicio', 'stocks', 'fecha_fin', 'productos', 'total_desabastecimiento', 'productoos', 'mes_anio', 'meses', 'datos_productos', 'menu', 'titulo_menu'));
                }
                if ($tipo == 8) {
                    $productos = $model_receta->ShowProductosAtendidosAlterno($fecha_inicio, $fecha_fin, $nombre_farmacia);
                    $total_desabastecimiento = $model_receta->GetTotalProductosAtendidosAlterno($fecha_inicio, $fecha_fin, $nombre_farmacia);
                    //-----------------------------------------------------------
                    $model_kardex = new Kardexe();
                    $stocks = $model_kardex->ShowTotalKardexByFarmacia($nombre_farmacia, $fecha_fin);

                    $datos_productos = $model_receta->obtenerDatosProductos();
                    $mes_anio = array();

                    $productoos = $model_receta->obternerProductos($fecha_inicio, $fecha_fin, $nombre_farmacia);
                    foreach ($productoos as $arr) {
                        $mes_anio[] = $arr->fecha_anio;
                    }
                    $mes_anio = array_unique($mes_anio);
                    $meses = $model_receta->obtener_mes();
                    return view('frontend.direoss.stocks.form_demanda_atendida', compact('id_farmacia', 'nombre_farmacia', 'stocks', 'fecha_inicio', 'fecha_fin', 'productos', 'total_desabastecimiento', 'productoos', 'mes_anio', 'meses', 'datos_productos', 'menu', 'titulo_menu'));
                }
                if ($tipo == 4) {
                    $cies = $model_receta->ShowDiagnosticosByFarma($fecha_inicio, $fecha_fin, $id_farmacia);
                    //----------------------------------------------------------
                    $mes_anio = array();
                    $productoos = $model_receta->obternerDiagnosticosPorMeses($fecha_inicio, $fecha_fin, $id_farmacia);
                    foreach ($productoos as $arr) {
                        $mes_anio[] = $arr->fecha_anio;
                    }
                    $mes_anio = array_unique($mes_anio);
                    $meses = $model_receta->obtener_mes();
                    return view('frontend.direoss.stocks.form_reporte_by_diagnosticos_by_farma', compact('id_farmacia', 'nombre_farmacia', 'fecha_inicio', 'fecha_fin', 'cies', 'productoos', 'mes_anio', 'meses', 'menu', 'titulo_menu'));
                }
                if ($tipo == 5) {
                    $medicos = $model_receta->ShowMedicosByFarma($fecha_inicio, $fecha_fin, $id_farmacia);
                    return view('frontend.direoss.stocks.form_reporte_by_medicos_by_farma', compact('id_farmacia', 'nombre_farmacia', 'fecha_inicio', 'fecha_fin', 'medicos', 'menu', 'titulo_menu'));
                }
                if ($tipo == 6) {
                    
                        $consultorios = $model_receta->ShowConsultoriosByFarma($fecha_inicio, $fecha_fin, $id_farmacia);                        
                    
                    return view('frontend.direoss.stocks.form_reporte_by_consultorios_by_farma', compact('id_farmacia', 'nombre_farmacia', 'fecha_inicio', 'fecha_fin', 'consultorios', 'menu', 'titulo_menu'));
                }
                if ($tipo == 7) {
                    if($id_farmacia==22 || $id_farmacia==14){
                        $hijo_incapacitado = $model_receta->ShowDataBeneficiariosByFarma2($fecha_inicio, $fecha_fin, $id_farmacia, 'HIJO INCAPACITADO','AMBULATORIO'); 
                        $hijo_no_incapacitado = $model_receta->ShowDataBeneficiariosByFarma2($fecha_inicio, $fecha_fin, $id_farmacia, 'HIJO NO INCAPACITADO','AMBULATORIO');
                        $padres = $model_receta->ShowDataBeneficiariosByFarma2($fecha_inicio, $fecha_fin, $id_farmacia, 'PADRES','AMBULATORIO');
                        $conyuges = $model_receta->ShowDataBeneficiariosByFarma2($fecha_inicio, $fecha_fin, $id_farmacia, 'CONYUGE','AMBULATORIO');
                        $titular = $model_receta->ShowDataBeneficiariosByFarma2($fecha_inicio, $fecha_fin, $id_farmacia, 'TITULAR','AMBULATORIO');

                        $hijo_incapacitado_1 = $model_receta->ShowDataBeneficiariosByFarma2($fecha_inicio, $fecha_fin, $id_farmacia, 'HIJO INCAPACITADO','EMERGENCIA'); 
                        $hijo_no_incapacitado_1= $model_receta->ShowDataBeneficiariosByFarma2($fecha_inicio, $fecha_fin, $id_farmacia, 'HIJO NO INCAPACITADO','EMERGENCIA');
                        $padres_1 = $model_receta->ShowDataBeneficiariosByFarma2($fecha_inicio, $fecha_fin, $id_farmacia, 'PADRES','EMERGENCIA');
                        $conyuges_1 = $model_receta->ShowDataBeneficiariosByFarma2($fecha_inicio, $fecha_fin, $id_farmacia, 'CONYUGE','EMERGENCIA');
                        $titular_1 = $model_receta->ShowDataBeneficiariosByFarma2($fecha_inicio, $fecha_fin, $id_farmacia, 'TITULAR','EMERGENCIA');

                        $hijo_incapacitado_2 = $model_receta->ShowDataBeneficiariosByFarma2($fecha_inicio, $fecha_fin, $id_farmacia, 'HIJO INCAPACITADO','HOSPITALIZACION'); 
                        $hijo_no_incapacitado_2 = $model_receta->ShowDataBeneficiariosByFarma2($fecha_inicio, $fecha_fin, $id_farmacia, 'HIJO NO INCAPACITADO','HOSPITALIZACION');
                        $padres_2 = $model_receta->ShowDataBeneficiariosByFarma2($fecha_inicio, $fecha_fin, $id_farmacia, 'PADRES','HOSPITALIZACION');
                        $conyuges_2 = $model_receta->ShowDataBeneficiariosByFarma2($fecha_inicio, $fecha_fin, $id_farmacia, 'CONYUGE','HOSPITALIZACION');
                        $titular_2 = $model_receta->ShowDataBeneficiariosByFarma2($fecha_inicio, $fecha_fin, $id_farmacia, 'TITULAR','HOSPITALIZACION');

                        return view('frontend.direoss.stocks.form_reporte_by_tipobeneficiario_by_farma', compact('id_farmacia', 'nombre_farmacia', 'fecha_inicio', 'fecha_fin', 'padres', 'conyuges', 'titular', 'hijo_no_incapacitado', 'hijo_incapacitado','padres_1', 'conyuges_1', 'titular_1', 'familiares', 'hijo_no_incapacitado_1', 'hijo_incapacitado_1','padres_2', 'conyuges_2', 'titular_2', 'hijo_no_incapacitado_2', 'hijo_incapacitado_2', 'menu', 'titulo_menu'));
                    }
                    else
                    {
                        $hijo_incapacitado = $model_receta->ShowDataBeneficiariosByFarma($fecha_inicio, $fecha_fin, $id_farmacia, 'HIJO INCAPACITADO'); 
                        $hijo_no_incapacitado = $model_receta->ShowDataBeneficiariosByFarma($fecha_inicio, $fecha_fin, $id_farmacia, 'HIJO NO INCAPACITADO');
                        $padres = $model_receta->ShowDataBeneficiariosByFarma($fecha_inicio, $fecha_fin, $id_farmacia, 'PADRES');
                        $conyuges = $model_receta->ShowDataBeneficiariosByFarma($fecha_inicio, $fecha_fin, $id_farmacia, 'CONYUGE');
                        $titular = $model_receta->ShowDataBeneficiariosByFarma($fecha_inicio, $fecha_fin, $id_farmacia, 'TITULAR');

                        return view('frontend.direoss.stocks.form_reporte_by_tipobeneficiario_by_farma', compact('id_farmacia', 'nombre_farmacia', 'fecha_inicio', 'fecha_fin', 'padres', 'conyuges', 'titular', 'familiares', 'hijo_no_incapacitado', 'hijo_incapacitado', 'menu', 'titulo_menu'));
                    }
                }

                if ($tipo == 10) {
                    $productos = $model_receta->ShowProductosAtendidosAlterno($fecha_inicio, $fecha_fin, $nombre_farmacia);

                    
                    return view('frontend.direoss.stocks.form_stock_mayor_meses', compact('productos', 'id_farmacia', 'nombre_farmacia', 'fecha_inicio', 'fecha_fin', 'menu', 'titulo_menu'));
                }

                if ($tipo == 11) {//Fecha de vencimiento
                    //$stocks = $model_stock->GetStockPorLotesByFechaVencimiento($id_farmacia, $fecha_inicio, $fecha_fin);
                    $model_kardex = new Kardexe();
                    $stocks = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechasVencimiento("", $id_farmacia, $fecha_inicio, $fecha_fin);
                    return view('frontend.direoss.stocks.form_fecha_vencimiento', compact('stocks', 'id_farmacia', 'nombre_farmacia', 'fecha_inicio', 'fecha_fin', 'menu', 'titulo_menu'));
                }

                if ($tipo == 12) {
                    //$stocks = $model_stock->GetSinStock($id_farmacia);
                    $model_kardex = new Kardexe();
                    //$stocks = $model_kardex->ShowTotalKardexByFarmaciaSinStock($nombre_farmacia,$fecha_fin);
                    $stocks = $model_kardex->ShowTotalKardexByFarmaciaSinStockKardex($nombre_farmacia, $fecha_fin);
                    return view('frontend.direoss.stocks.form_sin_stock', compact('stocks', 'id_farmacia', 'nombre_farmacia', 'fecha_inicio', 'fecha_fin', 'menu', 'titulo_menu'));
                }

                if ($tipo == 13) {
                    $beneficiario = $model_receta->ShowByDNI(strtoupper($request->dni));
                    $recetas = $model_receta->AllRecetasByDniAndFechas(strtoupper($request->dni), $fecha_inicio, $fecha_fin, $request->id_farmaciaBus);
                    $recetasGroup = $model_receta->AllRecetasByDniAndFechasGroup(strtoupper($request->dni), $fecha_inicio, $fecha_fin, $request->id_farmaciaBus);
                    //exit(print_r($recetasGroup));
                    return view('frontend.direoss.stocks.form_reporte_by_beneficiario', compact('recetas', 'beneficiario', 'id_farmacia', 'nombre_farmacia', 'fecha_inicio', 'fecha_fin', 'recetasGroup', 'menu', 'titulo_menu'));
                }

                if ($tipo == 14) {
                    $recetas = $model_receta->AllRecetasByFarmaciaAndFechas($id_farmacia, $fecha_inicio, $fecha_fin);
                    return view('frontend.direoss.stocks.form_reporte_lista_beneficiario', compact('recetas', 'id_farmacia', 'nombre_farmacia', 'fecha_inicio', 'fecha_fin', 'menu', 'titulo_menu'));
                }

                if ($tipo == 15) {
                    $id_producto = $request->id_producto;
                    if(empty($id_producto)){
                        Session::flash('flash_message', 'Debe de seleccionar un producto');
                        return redirect('/otros_reportes_by_farma/' . $tipo . '/' . $request->id_farmacia);
                    }
                    $model_producto = new Producto();
                    $nivel = $model_farma->ObtenerNivelFarmacia($id_farmacia);
                    $productosnivel = $model_producto->ProductosFarmaciasSegunNivel($nivel->nivel);
                    $producto = $model_producto->GetProductoById_producto($id_producto);
                    $nomproducto = $producto->codigo . ": " . $producto->nombre;

                    $resultado = $model_receta->ShowRecetasByProductoAndFechas($id_farmacia, $id_producto, $fecha_inicio, $fecha_fin);
                    
                    return view('frontend.direoss.stocks.form_reporte_recetas_by_producto', compact('resultado', 'id_farmacia', 'nombre_farmacia', 'fecha_inicio', 'fecha_fin', 'menu', 'titulo_menu', 'id_producto', 'productosnivel', 'nomproducto'));
                }

                if ($tipo == 17) {
                    $fecha_inicio = date("Ymd", strtotime($fecha_inicio));
                    $fecha_fin = date("Ymd", strtotime($fecha_fin));
                    $codigo_cpms = $request->codigo_cpms;

                    $ws_laboratorio = new \App\Ws\LaboratorioLNS;
                    $data = $ws_laboratorio->CallReporteLab($fecha_inicio, $fecha_fin, $codigo_cpms);
                    return view('frontend.direoss.stocks.reporte_laboratorio', compact('data'));
                }

                if ($tipo == 16) {
                    return view('frontend.direoss.stocks.form_reporte_by_diagnosticos_by_establecimiento');
                }
            }
        }
    }

    public function ver_det_servicio($tipo_repor, $id_user, $id_consultorio, $fecha_inicio, $fecha_fin) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $modelfarma = new Farmacia;
        $farmacia = $modelfarma->GetFarmaByUser($id_user);
        $id_farmacia = $farmacia->id;
        $modelConsultorio = new Consultorio;
        $consultorio = $modelConsultorio->GetNombreById($id_consultorio);
        //--------------------------------------------
        $model_stock_farmacias = new Stock_farmacia;
        //--------------------------------------------
        $date = Carbon::now();
        $fecha_actual = $date->format('d-m-Y');

        $medicamentos = $model_stock_farmacias->GetProductoByServicio($id_farmacia,$id_consultorio,$fecha_inicio,$fecha_fin);
        
        


        return view('frontend.stocks.ver_productos_servicio_modal', compact('medicamentos', 'fecha_inicio', 'fecha_fin', 'farmacia', 'consultorio'));
    
    }


    public function form_demanda_nosatisfecha(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $id_farmacia = $request->id_farmacia;
        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;
        //------------------------------------------------------
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
        //var_dump($farma); exit;
        //$id_establecimiento = $farma->id_establecimiento;
        $nombre_farmacia = $farma->nombre;
        //------------------------------------------------------                        
        $model_receta = new \App\Models\Receta_vale;
        $productos = $model_receta->ShowDemanadaNoSatisfechaByFarma($fecha_inicio, $fecha_fin, $id_farmacia);
        $total_desabastecimiento = $model_receta->GetTotalDemanadaNoSatisfechaByFarma($fecha_inicio, $fecha_fin, $id_farmacia);
        return view('frontend.direoss.stocks.form_demanda_nosatisfecha', compact('id_farmacia', 'nombre_farmacia', 'fecha_inicio', 'fecha_fin', 'productos', 'total_desabastecimiento'));
    }

    public function generar_reporte_ici_by_farma(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $id_farmacia = $request->id_farmacia;
        $fecha_fin = $request->fecha_fin;

        //====================
        $model_farma = new Farmacia;
        $model_producto = new Producto;
        $model_kardex = new Kardexe();
        //===============================
        $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
        //var_dump($farma); exit;
        $id_establecimiento = $farma->id_establecimiento;
        $nombre_farmacia = $farma->nombre;
        $id_establecimiento = $farma->id_establecimiento;
        //====================================
        //$kardexs = $model_kardex->ShowMedicamentos($nombre_farmacia);
        $kardexs = $model_kardex->ShowTotalKardexByFarmaciaIci($nombre_farmacia, $fecha_fin, $id_establecimiento);
        $movimientos = $model_kardex->ShowTipoMov();

        $fecha_inicio_cpma = $model_kardex->getFechaServidorRestaMeses(6);
        
        $fecha_fin_cpma = $model_kardex->getFechaServidor();

        $resultado = array();
        foreach ($kardexs as $k) {
            $arrayarticulo = array('id_producto' => $k->id_producto,
                'codigo' => $k->codigo_producto,
                'nombre' => $k->nombre_producto,
                'unidad' => $k->unidad,
                //'cantidad' => $total->total,
                'cantidad' => $k->total,
                //'ingresos' => $ingresos->total,
                /* 'egresos' => $egresos->total, */
                'egresos' => $k->egresos,
                /* 'nro_mes' => $nro_mes->nro_mes, */
                'nro_mes' => $k->nro_mes,
                /* 'recetas_devueltas' => $recetasDevueltas->recetas_devueltas, */
                'recetas_devueltas' => $k->recetas_devueltas,
                /* 'can' => $can->total */
                'can' => $k->can,
            );
            $listado = array();
            
            foreach ($movimientos as $m) {
                $suma = 0;
            
                if ($m->id == 1)
                    $arraymovimiento = array('id' => $m->id, 'codgo' => $m->codgo, 'total' => $k->saldo_inicial);
                if ($m->id == 2)
                    $arraymovimiento = array('id' => $m->id, 'codgo' => $m->codgo, 'total' => $k->ingreso_regular);
                if ($m->id == 3)
                    $arraymovimiento = array('id' => $m->id, 'codgo' => $m->codgo, 'total' => $k->ingreso_junta_medica);
                if ($m->id == 4)
                    $arraymovimiento = array('id' => $m->id, 'codgo' => $m->codgo, 'total' => $k->salida_receta);
                if ($m->id == 5)
                    $arraymovimiento = array('id' => $m->id, 'codgo' => $m->codgo, 'total' => $k->salida_diaria);
                if ($m->id == 6)
                    $arraymovimiento = array('id' => $m->id, 'codgo' => $m->codgo, 'total' => $k->salida_junta_medica);
                if ($m->id == 7)
                    $arraymovimiento = array('id' => $m->id, 'codgo' => $m->codgo, 'total' => $k->transferencia);
                if ($m->id == 8)
                    $arraymovimiento = array('id' => $m->id, 'codgo' => $m->codgo, 'total' => $k->receta_devuelta);
                if ($m->id == 9)
                    $arraymovimiento = array('id' => $m->id, 'codgo' => $m->codgo, 'total' => $k->ingreso_transferencia);
                $listado[] = $arraymovimiento;
            }

            $arrayarticulo['movimiento'] = $listado;
            $resultado[] = $arrayarticulo;
        }
        return view('frontend.direoss.stocks.formulario_ici', compact('farma', 'nombre_farmacia', 'kardexs', 'movimientos', 'resultado', 'fecha_fin', 'mes'));
    }

    public function tabular_stockgeneral_ipress() {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $modelstock = new Stock_farmacia;
        $modelfarmacia = new Farmacia;
        $farmacias = $modelfarmacia->NombreFarmacias();
        $productos = $modelstock->ProductosByFarmacia();
        $resultados = array();
        foreach ($productos as $p) {
            $arrayarticulo = array(
                //'codigo' => $p->codigo_producto,
                //'nombre' => $p->nombre_producto,
                'codigo' => $p->codigo,
                'nombre' => $p->nombre,
                'unidad' => $p->unidad,
                'cantidad' => $p->cantidad
            );
            $cantidades = array();
            foreach ($farmacias as $f) {
                //$cantidad=$modelstock->CantidadByFarmacia($f->id, $p->codigo_producto);
                $cantidad = $modelstock->CantidadByFarmacia($f->id, $p->codigo);
                $arraycantidad = array('cant' => $cantidad[0]->cantidad, 'farma' => $cantidad[1]->nombre);
                $cantidades[] = $arraycantidad;
            }
            $arrayarticulo['cantidades'] = $cantidades;
            $resultados[] = $arrayarticulo;
        }
        return view('frontend.direoss.stocks.stocks_ipress', compact('farmacias', 'productos', 'resultados'));
    }

    public function tabular_stockgeneral_por_tipo($tipo) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $modelstock = new Stock_farmacia;
        $modelfarmacia = new Farmacia;
        $farmacias = $modelfarmacia->NombreFarmacias();
        $productos = $modelstock->ProductosByTipoProducto($tipo);
        //$productos=$modelstock->ProductosDifferentMedicamentos();
        $resultados = array();
        foreach ($productos as $p) {
            $arrayarticulo = array(
                'codigo' => $p->codigo,
                'nombre' => $p->nombre,
                'unidad' => $p->unidad,
                'cantidad' => $p->cantidad
            );
            $cantidades = array();
            foreach ($farmacias as $f) {
                $cantidad = $modelstock->CantidadByFarmacia($f->id, $p->codigo);
                $arraycantidad = array('cant' => $cantidad[0]->cantidad, 'farma' => $cantidad[1]->nombre);
                $cantidades[] = $arraycantidad;
            }
            $arrayarticulo['cantidades'] = $cantidades;
            $resultados[] = $arrayarticulo;
        }
        switch ($tipo) {
            case 1:
                $nombre = 'MEDICAMENTOS';
                break;
            case 2:
                $nombre = 'INSUMOS DE LABORATORIO';
                break;
            case 3:
                $nombre = 'BIOMEDICOS';
                break;
            case 4:
                $nombre = 'MATERIAL DE LABORATORIO';
                break;
            case 5:
                $nombre = 'MATERIAL DENTAL';
                break;
            case 6:
                $nombre = 'MATERIAL FOTOGRAFICO Y FONOTECNICO';
                break;
        }
        return view('frontend.direoss.stocks.tabular_stockgeneral_ipress_otros_biomedicos', compact('farmacias', 'productos', 'resultados', 'nombre'));
    }

    public function tabular_stockgeneral_ipress_medicamentos() {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $modelstock = new Stock_farmacia;
        $modelfarmacia = new Farmacia;
        $farmacias = $modelfarmacia->NombreFarmacias();
        $productos = $modelstock->ProductosByTipoProducto(1);
        $resultados = array();
        foreach ($productos as $p) {
            $arrayarticulo = array(
                'codigo' => $p->codigo,
                'nombre' => $p->nombre,
                'unidad' => $p->unidad,
                'cantidad' => $p->cantidad
            );
            $cantidades = array();
            foreach ($farmacias as $f) {
//$cantidad=$modelstock->CantidadByFarmacia($f->id, $p->codigo_producto);
                $cantidad = $modelstock->CantidadByFarmacia($f->id, $p->codigo);
                $arraycantidad = array('cant' => $cantidad[0]->cantidad, 'farma' => $cantidad[1]->nombre);
                $cantidades[] = $arraycantidad;
            }
            $arrayarticulo['cantidades'] = $cantidades;
            $resultados[] = $arrayarticulo;
        }
        return view('frontend.direoss.stocks.stocks_ipress_medicamentos', compact('farmacias', 'productos', 'resultados'));
    }

    public function stockGeneral() {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $modelstock = new Stock_farmacia;
        $modelfarmacia = new Farmacia;
        $model_kardex = new Kardexe;

        $farmacias = $modelfarmacia->NombreFarmacias();
        //$productos = $modelstock->ProductosByFarmacia();
        $fecha_fin = $model_kardex->getFechaServidor();
        $productos = $model_kardex->ShowTotalKardexByFarmacia("", $fecha_fin);

        $resultados = array();
        foreach ($productos as $p) {
            $arrayarticulo = array(
                'id_producto' => $p->id_producto,
                'codigo' => $p->codigo_producto,
                'nombre' => $p->nombre_producto,
                'unidad' => $p->unidad,
                'cantidad' => $p->total
            );
            $cantidades = array();
            foreach ($farmacias as $f) {
                //$cantidad = $modelstock->CantidadByFarmacia($f->id, $p->codigo);
                $Stocks = $model_kardex->ShowTotalByProd($p->id_producto, $f->nombre, "", $fecha_fin);
                $cant = 0;
                if ($Stocks)
                    $cant = $Stocks->total;
                $arraycantidad = array('cant' => $cant, 'farma' => $f->nombre);
                $cantidades[] = $arraycantidad;
            }
            $arrayarticulo['cantidades'] = $cantidades;
            $resultados[] = $arrayarticulo;
        }
        return view('frontend.direoss.stocks.stocks_ipress_pnp', compact('farmacias', 'productos', 'resultados'));
    }

    public function generar_reporte_ici(Request $request) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
		/*
        if (!$request->id_user):
            return redirect('/inicio');
        endif;
		*/
		
		/*
        $id_user = $request->id_user;
        $fecha_fin = $request->fecha_fin;
        $model_farma = new Farmacia;
        $model_producto = new Producto;
        $model_kardex = new Kardexe();
        $farma = $model_farma->GetFarmaByUser($id_user);
        $id_establecimiento = $farma->id_establecimiento;
        $nombre_farmacia = $farma->nombre;
        $id_establecimiento = $farma->id_establecimiento;
        $kardexs = $model_kardex->ShowTotalKardexByFarmaciaIci($nombre_farmacia, $fecha_fin, $id_establecimiento);
        $movimientos = $model_kardex->ShowTipoMov();
		*/

		 $model_kardex = new Kardexe();
        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;        
        ///*************************************
        $model_farma = new Farmacia;
        $model_producto = new Producto;        
        //===============================
        $farma = $model_farma->GetFarmaciaById($request->id_farmacia);
        $id_establecimiento = $farma[0]->id_establecimiento;
        $nombre_farmacia = $farma[0]->nombre;
        $id_farmacia = $farma[0]->id;

        //$kardexs = $model_kardex->ShowTotalKardexByFarmaciaIciMes($nombre_farmacia, $fecha_inicio, $fecha_fin, $id_establecimiento);
        $kardexs = $model_kardex->ShowTotalKardexByFarmaciaIciMes($request->id_farmacia, $fecha_inicio, $fecha_fin);

        

        $movimientos = $model_kardex->ShowTipoMov();
		
        $fecha_inicio_cpma = $model_kardex->getFechaServidorRestaMeses(6);
        $fecha_fin_cpma = $model_kardex->getFechaServidor();

        $resultado = array();
        foreach ($kardexs as $k) {
            //$total = $model_kardex->ShowTotalByProd($k->codigo_producto, $nombre_farmacia, "", $fecha_fin);
            //$ingresos = $model_kardex->ShowIngresosByProd($k->codigo_producto, $nombre_farmacia, $fecha_inicio_cpma, $fecha_fin_cpma);
            //$egresos = $model_kardex->ShowEgresosByProd($k->codigo_producto, $nombre_farmacia, $fecha_inicio_cpma, $fecha_fin_cpma);
            //$recetasDevueltas = $model_kardex->ShowRecetasDevueltas($k->codigo_producto, $nombre_farmacia, $fecha_inicio_cpma, $fecha_fin_cpma);
            //$nro_mes = $model_kardex->ShowNroMesByProd($k->codigo_producto, $nombre_farmacia, $fecha_inicio_cpma, $fecha_fin_cpma);
            //$can = $model_kardex->GetCantidadCAN($k->codigo_producto, $id_establecimiento);
            $arrayarticulo = array('id_producto' => $k->id_producto,
                'codigo' => $k->codigo_producto,
                'nombre' => $k->nombre_producto,
                'unidad' => $k->unidad,
				'precio' => $k->precio,
                'fecha' => $k->fecha,
                //'cantidad' => $total->total,
                'cantidad' => $k->total,
                //'ingresos' => $ingresos->total,
                /* 'egresos' => $egresos->total, */
                'egresos' => $k->egresos,
                /* 'nro_mes' => $nro_mes->nro_mes, */
                'nro_mes' => $k->nro_mes,
                /* 'recetas_devueltas' => $recetasDevueltas->recetas_devueltas, */
                'recetas_devueltas' => $k->recetas_devueltas,
                /* 'can' => $can->total */
                //'can' => $k->can
            );

            $listado = array();

            /*
              foreach ($movimientos as $m) {
              $suma = 0;
              $suma +=$model_kardex->ShowTotalesByMov_2($k->codigo_producto, $nombre_farmacia, $m->id, $fecha_fin)->suma;
              $arraymovimiento = array('id' => $m->id, 'codgo' => $m->codgo, 'total' => $suma);
              $listado[] = $arraymovimiento;
              }
             */

            foreach ($movimientos as $m) {
                $suma = 0;
                //$suma +=$model_kardex->ShowTotalesByMov_2($k->codigo_producto, $nombre_farmacia, $m->id, $fecha_fin)->suma;
                if ($m->id == 1)
                    $arraymovimiento = array('id' => $m->id, 'codgo' => $m->codgo, 'total' => $k->saldo_inicial);
                if ($m->id == 2)
                    $arraymovimiento = array('id' => $m->id, 'codgo' => $m->codgo, 'total' => $k->ingreso_regular);
                if ($m->id == 3)
                    $arraymovimiento = array('id' => $m->id, 'codgo' => $m->codgo, 'total' => $k->ingreso_junta_medica);
                if ($m->id == 4)
                    $arraymovimiento = array('id' => $m->id, 'codgo' => $m->codgo, 'total' => $k->salida_receta);
                if ($m->id == 5)
                    $arraymovimiento = array('id' => $m->id, 'codgo' => $m->codgo, 'total' => $k->salida_diaria);
                if ($m->id == 6)
                    $arraymovimiento = array('id' => $m->id, 'codgo' => $m->codgo, 'total' => $k->salida_junta_medica);
                if ($m->id == 7)
                    $arraymovimiento = array('id' => $m->id, 'codgo' => $m->codgo, 'total' => $k->transferencia);
                if ($m->id == 8)
                    $arraymovimiento = array('id' => $m->id, 'codgo' => $m->codgo, 'total' => $k->receta_devuelta);
                if ($m->id == 9)
                    $arraymovimiento = array('id' => $m->id, 'codgo' => $m->codgo, 'total' => $k->ingreso_transferencia);
                $listado[] = $arraymovimiento;
            }

            $arrayarticulo['movimiento'] = $listado;
            $resultado[] = $arrayarticulo;
        }
        return view('frontend.stocks.formulario_ici', compact('farma', 'nombre_farmacia', 'kardexs', 'movimientos', 'resultado', 'fecha_inicio', 'fecha_fin','id_farmacia'));
    }

    public function formulario_add_guia($id_user, $id_requerimiento) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $model_farma = new Farmacia;
        $modelRequerimiento = new \App\Models\Req_producto();
        $requerimientos = new Requerimiento;
        $farma = $model_farma->GetFarmaByUser($id_user);
        $nombre_farmacia = $farma->nombre;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $productos = $modelRequerimiento->ProductosByRequerimiento($id_requerimiento);
        $requerimiento = $id_requerimiento;
        $getRequerimiento = $requerimientos->ShowRequerimientosByIdRequerimiento($id_requerimiento);
        $estado = $getRequerimiento->estado;
        return view('frontend.stocks.formulario_add_guia', compact('farma', 'nombre_farmacia', 'productos', 'fecha', 'requerimiento', 'estado', 'getRequerimiento'));
    }

    public function add_junta_medica(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        //----------------------------------------------------------------------
        $modelJunta = new Junta_medica();
        $modelFarmacia = new Farmacia();
        $farmacia = $modelFarmacia->GetFarmaByUser($request->id_user);
        $nombrefarmacia = $farmacia->nombre;
        //----------------------------------------------------------------------
        $modelJunta->numero = $request->nro_guia;
        $modelJunta->fecha_entrada = $request->fecha;
        $modelJunta->id_farmacia = $request->id_farmacia;
        $modelJunta->id_user = $request->id_user;
        $modelJunta->save();
        //----------------Para el Kardexs---------------------------------------
        $numero = $modelJunta->numero;
        //----------------------------------------------------------------------
        foreach ($request->codigo_producto as $key => $value) {
            $stockfarmacia = new Stock_farmacia;
            $modelProduct = new Producto;
            //------------------------------------------------------------------
            $codigo = $request->codigo_producto[$key];
            $product = $modelProduct->GetNombreProdyCod($codigo);
            $nombre_producto = $product->nombre;
            $existe = $stockfarmacia->ExisteStockFarmacia($codigo, $nombrefarmacia);
            if ($existe) {
                $updateStock = Stock_farmacia::where('codigo_producto', $codigo)->where('nombre_farmacia', $nombrefarmacia)->first();
                $cantidadActual = $updateStock->cantidad;
                $updateStock->cantidad = $cantidadActual + $request->cantidad[$key];
                $updateStock->save();
            } else {
                $addStock = new Stock_farmacia;
                $addStock->codigo_producto = $request->codigo_producto[$key];
                $addStock->nombre_producto = $nombre_producto;
                $addStock->nombre_farmacia = $nombrefarmacia;
                $addStock->cantidad = $request->cantidad[$key];
                $addStock->save();
            }
            $kardex = new Kardexe;
            $kardex->codigo_producto = $request->codigo_producto[$key];
            $kardex->nombre_producto = $nombre_producto;
            $kardex->tipo_movimiento = 'IJM';
            $kardex->numero_movimiento = $numero;
            $kardex->origen = 'LIMA';
            $kardex->destino = $nombrefarmacia;
            $kardex->estado = 1;
            $kardex->id_tipo_movimiento = 3;
            $kardex->cantidad = $request->cantidad[$key];
            $kardex->nombre_farmacia = $nombrefarmacia;
            $kardex->fecha_movimiento = $request->fecha;
            $kardex->save();
        }
        Session::flash('flash_message', 'Registro exitoso');
        $juntas = $modelJunta->ShowAllJuntas($request->id_farmacia);
        return view('frontend.stocks.reporte_juntas_medicas', compact('juntas'));
    }

    public function reporte_juntas_medicas($id_farmacia) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $modelJunta = new Junta_medica();
        $juntas = $modelJunta->ShowAllJuntas($id_farmacia);
        return view('frontend.stocks.reporte_juntas_medicas', compact('juntas'));
    }

    public function add_productos_sinrequerimiento(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $modelGuiaEntrada = new Guia_entrada();
        $nombrefarmacia = $request->nombre_farmacia;
        //----------------------------------------------------------------------
        $modelGuiaEntrada->numero = $request->nro_guia;
        $modelGuiaEntrada->fecha_entrada = $request->fecha;
        $modelGuiaEntrada->id_farmacia = $request->id_farmacia;
        $modelGuiaEntrada->id_user = $request->id_user;
        $modelGuiaEntrada->save();
        //----------------Para el Kardexs---------------------------------------
        $numero = $request->nro_guia;
        //----------------------------------------------------------------------
        foreach ($request->codigo_producto as $key => $value) {
            $stockfarmacia = new Stock_farmacia;
            $modelProduct = new Producto;
            //------------------------------------------------------------------
            $codigo = $request->codigo_producto[$key];
            $product = $modelProduct->GetNombreProdyCod($codigo);
            $nombre_producto = $product->nombre;
            $existe = $stockfarmacia->ExisteStockFarmacia($codigo, $nombrefarmacia);
            if ($existe) {
                $updateStock = Stock_farmacia::where('codigo_producto', $codigo)->where('nombre_farmacia', $nombrefarmacia)->first();
                $cantidadActual = $updateStock->cantidad;
                $updateStock->cantidad = $cantidadActual + $request->cantidad[$key];
                $updateStock->save();
            } else {
                $addStock = new Stock_farmacia;
                $addStock->codigo_producto = $request->codigo_producto[$key];
                $addStock->nombre_producto = $nombre_producto;
                $addStock->nombre_farmacia = $nombrefarmacia;
                $addStock->cantidad = $request->cantidad[$key];
                $addStock->save();
            }
            $kardex = new Kardexe;
            $kardex->codigo_producto = $request->codigo_producto[$key];
            $kardex->nombre_producto = $nombre_producto;
            $kardex->tipo_movimiento = 'IR';
            $kardex->numero_movimiento = $numero;
            $kardex->origen = 'LIMA';
            $kardex->destino = $nombrefarmacia;
            $kardex->estado = 1;
            $kardex->id_tipo_movimiento = 2;
            $kardex->cantidad = $request->cantidad[$key];
            $kardex->nombre_farmacia = $nombrefarmacia;
            $kardex->fecha_movimiento = $request->fecha;
            $kardex->save();
        }
        Session::flash('flash_message', 'Registro exitoso');
        return redirect('/stocksbyFamarcia/' . $request->id_user);
    }

    public function add_guia(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $modelGuia = new Guia_entrada();
        $modelFarmacia = new Farmacia();
        $farmacia = $modelFarmacia->GetFarmaByUser($request->id_user);
        $id_farmacia = $farmacia->id;
        $nombrefarmacia = $farmacia->nombre;
        $modelGuia->numero = $request->nro_guia;
        $modelGuia->fecha_entrada = $request->fecha;
        $modelGuia->id_farmacia = $request->id_farmacia;
        $modelGuia->id_user = $request->id_user;
        $modelGuia->save();
        $numero = $modelGuia->numero;
        foreach ($request->codigo_producto as $key => $value) {
            $stockfarmacia = new Stock_farmacia;
            $modelProduct = new Producto;
            $codigo = $request->codigo_producto[$key];
            $product = $modelProduct->GetNombreProdyCod($codigo);
            $nombre_producto = $product->nombre;
            $existe = $stockfarmacia->ExisteStockFarmacia($codigo, $nombrefarmacia);
            $cantidad_recibida = $request->cantidad[$key];
            $cantidad_solicitada = $request->cantidad_solicitada[$key];
            if ($existe) {
                $updateStock = Stock_farmacia::where('codigo_producto', $codigo)->where('nombre_farmacia', $nombrefarmacia)->first();
                $cantidadActual = $updateStock->cantidad;
                $updateStock->cantidad = $cantidadActual + $request->cantidad[$key];
                $updateStock->id_farmacia = $id_farmacia;
                $updateStock->save();
                Req_producto::where('id', $request->id_req_producto[$key])
                        ->update(['cantidad_recibida' => $request->cantidad[$key]]);
            } else {
                $addStock = new Stock_farmacia;
                $addStock->codigo_producto = $request->codigo_producto[$key];
                $addStock->nombre_producto = $nombre_producto;
                $addStock->nombre_farmacia = $nombrefarmacia;
                $addStock->cantidad = $request->cantidad[$key];
                $addStock->id_farmacia = $id_farmacia;
                $addStock->save();
                Req_producto::where('id', $request->id_req_producto[$key])
                        ->update(['cantidad_recibida' => $request->cantidad[$key]]);
            }
            $kardex = new Kardexe;
            $kardex->codigo_producto = $request->codigo_producto[$key];
            $kardex->nombre_producto = $nombre_producto;
            $kardex->tipo_movimiento = 'IR';
            $kardex->numero_movimiento = $numero;
            $kardex->origen = 'LIMA';
            $kardex->destino = $nombrefarmacia;
            $kardex->estado = 1;
            $kardex->id_tipo_movimiento = 2;
            $kardex->cantidad = $request->cantidad[$key];
            $kardex->nombre_farmacia = $nombrefarmacia;
            $kardex->fecha_movimiento = $request->fecha;
            $kardex->save();
            Requerimiento::where('id', $request->id_requerimiento)
                    ->update(['estado' => 'atendido', 'nro_pecosa' => $request->nro_guia]);
        }
        Session::flash('flash_message', 'Registro exitoso');
        return redirect('/formulario_add_guia/' . $request->id_user . "/" . $request->id_requerimiento);
    }

    public function form_generar_rerporte_ici($id_user = 0) {
        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;

        $id_user = Auth::user()->id;
        $model_farmacia = new Farmacia;
        $farmacia = $model_farmacia->GetFarmaByUser($id_user);
        $nombre_farmacia = $farmacia->nombre;
        //var_dump($nombre_farmacia); exit;
        $usuario = $id_user;
        $date = Carbon::now();
        $fecha_actual = $date->format('d-m-Y');
        $f_inicio_operaciones = Kardexe::where('nombre_farmacia', '=', $nombre_farmacia)
                        ->where('id_tipo_movimiento', '=', 1)->min('fecha_movimiento');
        $count_f_inicio_operaciones = Kardexe::where('nombre_farmacia', '=', $nombre_farmacia)
                        ->where('id_tipo_movimiento', '=', 1)->count();
		$id_farmacia = $farmacia->id;
        return view('frontend.stocks.form_generar_rerporte_ici', compact('usuario', 'fecha_actual', 'f_inicio_operaciones', 'nombre_farmacia', 'count_f_inicio_operaciones','id_farmacia'));
    }

    public function formulario_consumo_diario($id_user) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $model_farma = new Farmacia;
        $model_stock = new Stock_farmacia;
        $farma = $model_farma->GetFarmaByUser($id_user);
        $nombre_farmacia = $farma->nombre;
        $medicamentos = $model_stock->ShowStockByFarmacia($nombre_farmacia);
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        return view('frontend.stocks.formulario_consumo_diario', compact('medicamentos', 'farma', 'fecha'));
    }

    public function formulario_salida_diaria($id_user) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $model_farma = new Farmacia;
        $model_stock = new Stock_farmacia;
        $farma = $model_farma->GetFarmaByUser($id_user);
        $nombre_farmacia = $farma->nombre;
        $medicamentos = $model_stock->ShowStockByFarmacia($nombre_farmacia);
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        return view('frontend.stocks.formulario_salida_diaria', compact('medicamentos', 'farma', 'fecha'));
    }

    public function formulario_consumo_juntamedica($id_user) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $model_farma = new Farmacia;
        $model_stock = new Stock_farmacia;
        $farma = $model_farma->GetFarmaByUser($id_user);
        $nombre_farmacia = $farma->nombre;
        $medicamentos = $model_stock->ShowStockByFarmacia($nombre_farmacia);
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        return view('frontend.stocks.formulario_consumo_juntamedica', compact('medicamentos', 'farma', 'fecha'));
    }

    public function add_consumo_juntamedica(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        foreach ($request->codigo_producto as $key => $value) {
            $updateStock = Stock_farmacia::where('codigo_producto', $request->codigo_producto[$key])->where('nombre_farmacia', $request->nombre_farmacia)->first();
            $nueva_cantidad = $request->cantidad[$key];
            $cantidad_actual = $updateStock->cantidad;
            if ($nueva_cantidad != "") {
                $updateStock->cantidad = $cantidad_actual - $nueva_cantidad;
                $updateStock->save();
                $kardex = new Kardexe;
                $kardex->codigo_producto = $request->codigo_producto[$key];
                $kardex->nombre_producto = $request->nombre_producto[$key];
                $kardex->tipo_movimiento = 'SJM';
                $kardex->numero_movimiento = '00000006';
                $kardex->origen = $request->nombre_farmacia;
                $kardex->destino = 'BENEFICIARIO';
                $kardex->estado = 1;
                $kardex->id_tipo_movimiento = 6;
                $kardex->cantidad = $request->cantidad[$key];
                $kardex->nombre_farmacia = $request->nombre_farmacia;
                $kardex->fecha_movimiento = $request->fecha;
                $kardex->save();
            }
        }
        Session::flash('flash_message', 'Registro exitoso');
        return redirect('/stocksbyFamarcia/' . $request->id_user);
    }

    public function add_consumo_diario(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        foreach ($request->codigo_producto as $key => $value) {
            $updateStock = Stock_farmacia::where('codigo_producto', $request->codigo_producto[$key])->where('nombre_farmacia', $request->nombre_farmacia)->first();
            $nueva_cantidad = $request->cantidad[$key];
            $cantidad_actual = $updateStock->cantidad;
            $nombre_producto = $updateStock->nombre_producto;
            if ($nueva_cantidad != "") {
                $updateStock->cantidad = $cantidad_actual - $nueva_cantidad;
                $updateStock->save();
                $kardex = new Kardexe;
                $kardex->codigo_producto = $request->codigo_producto[$key];
                $kardex->nombre_producto = $nombre_producto;
                $kardex->tipo_movimiento = 'SD';
                $kardex->numero_movimiento = '00000005';
                $kardex->origen = $request->nombre_farmacia;
                $kardex->destino = 'BENEFICIARIO';
                $kardex->estado = 1;
                $kardex->id_tipo_movimiento = 5;
                $kardex->cantidad = $request->cantidad[$key];
                $kardex->nombre_farmacia = $request->nombre_farmacia;
                $kardex->fecha_movimiento = $request->fecha;
                $kardex->save();
            }
        }
        $cant_gral_receta = $request->cant_gral_receta;
        $fecha = $request->fecha;
        $id_farmacia = $request->id_farmacia;
        $id_user = $request->id_user;
        $salidaDiaria = new \App\Models\Salida_diaria();
        $salidaDiaria->numero = '00000005';
        $salidaDiaria->cant_gral_receta = $cant_gral_receta;
        $salidaDiaria->fecha_salida = $fecha;
        $salidaDiaria->id_farmacia = $id_farmacia;
        $salidaDiaria->id_user = $id_user;
        $salidaDiaria->save();
        Session::flash('flash_message', 'Registro exitoso');
        return redirect('/stocksbyFamarcia/' . $request->id_user);
    }

    public function reporte_salidad_diaria($id_user) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $fecha = "";
        $cant_gral_receta = "";
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByUser($id_user);
        $salidas = NULL;
        return view('frontend.stocks.reporte_salida_diaria', compact('farma', 'fecha', 'cant_gral_receta', 'salidas'));
    }

    public function reporte_salida_diaria_ok(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $id_user = $request->id_user;
        $model_farma = new Farmacia;
        $model_kardexes = new Kardexe();
        $farma = $model_farma->GetFarmaByUser($id_user);
        $nombre_farmacia = $farma->nombre;
        $fecha = $request->fecha_salida;
        $id_farmacia = $request->id_farmacia;
        $model_SalidaDiarias = new \App\Models\Salida_diaria();
        $salida_diarias = $model_SalidaDiarias->GetCantidadConsolidado($id_farmacia, $fecha);
        $cant_gral_receta = $salida_diarias->cantidad;
        $salidas = $model_kardexes->CantSalidasDiarias('SD', $fecha, $nombre_farmacia);
        return view('frontend.stocks.reporte_salida_diaria', compact('farma', 'fecha', 'cant_gral_receta', 'salidas'));
    }

    public function modulo_ingresos() {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        return view('frontend.stocks.modulo_ingresos');
    }

    public function eliminar_producto_del_pedido($id_req_producto, $id_requerimiento, $tipo_producto_egestion, $id_farmacia) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $req_producto = Req_producto::find($id_req_producto);
        $req_producto->estado = 0;
        $req_producto->save();
        $codigo = $req_producto->codigo_producto;
        $producto = Producto::where('codigo', $codigo)->first();
        ;
        Session::flash('flash_message', 'El Producto ' . $producto->nombre . ' fue eliminado');
        return redirect('/ver_productos_del_pedido/' . $id_requerimiento . '/' . $tipo_producto_egestion . '/' . $id_farmacia);
    }

    public function editar_producto_del_pedido($id_req_producto, $id_requerimiento, $tipo_producto_egestion, $id_farmacia) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $model_producto = new Producto;
        $req_producto = Req_producto::find($id_req_producto);
        $requerimiento = Requerimiento::find($id_requerimiento);
        $farmacia = Farmacia::find($id_farmacia);
        $producto = Producto::where('codigo', '=', $req_producto->codigo_producto)->first();
        $productos = $model_producto->ShowProductosByIdegestion($tipo_producto_egestion);
        return view('frontend.stocks.editar_producto_del_pedido', compact('farmacia', 'req_producto', 'producto', 'productos', 'requerimiento', 'id_requerimiento', 'tipo_producto_egestion'));
    }

    public function update_producto_del_pedido(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $update = Req_producto::find($request->id_req_producto);
        $update->codigo_producto = $request->codigo_producto;
        $update->cantidad = $request->cantidad;
        $update->save();
        Session::flash('flash_message', 'Producto actualizado');
        return redirect('/ver_productos_del_pedido/' . $request->id_requerimiento . '/' . $request->tipo_producto_egestion . '/' . $request->id_farmacia);
    }

    public function enviar_pedido_almacen(Request $request) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        /*
          if(App::environment('desarrollo')):
          $client = new SoapClient("http://192.168.0.243/monitoreo/webservices/server.php?wsdl", array('login' => 'userafis@ludpol', 'password' => "WS@ludPol@fi"));
          elseif(App::environment('local')):
          $client = new SoapClient("http://10.19.39.10:8088/webservices/server.php?wsdl", array('login' => 'userafis@ludpol', 'password' => "WS@ludPol@fi"));
          endif;

          $result = $client->GrabarPedidoCab($request->codtipoproducto, $request->cod_unidad, $request->documento, 950, 565);
          $nro_pecosa=$result->NUMPECOSA;
          $cod_pecosa=$result->CODPECOSA;
          //----------------------------------------------------------------------
          if($result->CODPECOSA > 0):
          //echo count($request->codigo_producto);exit();
          if(count($request->codigo_producto)=="0"):
          Session::flash('flash_message', 'Falta Agregar los Productos');
          return redirect('/ver_productos_del_pedido/' . $request->id_requerimiento . '/' . $request->codtipoproducto . '/' . $request->id_farmacia);
          endif;

          foreach ($request->codigo_producto as $key => $value) {
          $result_det = $client->GrabarPedidoDet($cod_pecosa, $request->codigo_producto[$key], $request->cantidad[$key], 565);
          }
          $requerimiento = Requerimiento::find($request->id_requerimiento);
          $requerimiento->estado = 2;
          $requerimiento->nro_pecosa = $nro_pecosa;
          $requerimiento->cod_pecosa = $cod_pecosa;
          $requerimiento->fecha = $fecha;
          $requerimiento->save();
          //----------------------------------------------------------------------
          Session::flash('flash_message', 'Pedido Enviado');

          else:
          Session::flash('flash_message', 'Pedido no fue Enviado al almacen');

          endif;
         */
        $requerimiento = Requerimiento::find($request->id_requerimiento);
        //if (Auth::user()->flagresponsablefarmacia==1)
        //$requerimiento->estado = 3;
        //else
            $requerimiento->estado = 2;
        //$requerimiento->nro_pecosa = $nro_pecosa;
        //$requerimiento->cod_pecosa = $cod_pecosa;
        $requerimiento->fecha = $fecha;
        $requerimiento->save();
        return redirect('/ver_productos_del_pedido/' . $request->id_requerimiento . '/' . $request->codtipoproducto . '/' . $request->id_farmacia);
    }

    public function ver_productos_del_pedido($id_requerimiento, $id_tipo_producto_egestion, $id_farmacia) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $model_requerimiento = new Requerimiento();
        $model_req_producto = new Req_producto();
        $model_farmacia = new Farmacia();
        $model_producto = new Producto();
        $nivel = $model_farmacia->ObtenerNivelFarmacia($id_farmacia);
        $farmacia = Farmacia::where('id', '=', $id_farmacia)->first();
        $almacen=$farmacia->almacen;
        //var_dump($nivel); exit;
        $establecimiento = $model_farmacia->GetEstablecimientoPNP($id_farmacia);
        $requerimiento = $model_requerimiento->GetRequerimientoById($id_requerimiento);
        $destino = $model_farmacia->GetFarmaciaDestinoRequerimiento();
        
        if($farmacia->almacen==1){
            $productos = $model_producto->ProductosFarmaciasSegunNivelAndTipoProducto(3, $id_tipo_producto_egestion);
        }
        else
        {
            if($id_farmacia==36 || $id_farmacia==37){
                $productos = $model_producto->ProductosFarmaciasSegunNivelAndTipoProducto(2, $id_tipo_producto_egestion);
            }
            else
            {
                $productos = $model_producto->ProductosFarmaciasSegunNivelAndTipoProducto($nivel->nivel, $id_tipo_producto_egestion);       
            }
        }

        $model_tipo_producto = new Tipo_producto();
        $tipo_productos = $model_tipo_producto->ShowAll();


        $farmacia = $model_farmacia->GetFarmaByIdfarma($id_farmacia);
        $tipo_producto = Tipo_producto::where('id_egestion', '=', $id_tipo_producto_egestion)->first();
        $productosbyrequerimientos = $model_req_producto->ProductosByRequerimiento($id_requerimiento);

        if(Auth::user()->flagresponsablefarmacia==1){            
            $date = Carbon::now();
            $fecha = $date->format('d-m-Y');   
            $fechaIngreso = Carbon::parse($requerimiento->updated_at);
            $fechaActual = Carbon::parse($fecha);
            $tiempo = $fechaActual->diffInDays($fechaIngreso);
        }
        else{
            $tiempo=31;
        }

        $nombre_servicio = '';
        if(Auth::user()->id_consultorio!=''):
            $servicio = new Consultorio();
            $nombre_servicio = $servicio->GetNombreById(Auth::user()->id_consultorio)->nombre;
        endif;
        //exit(print_r($tipo_producto));
        return view('frontend.stocks.form_add_productos_al_pedido', compact('destino','tipo_productos', 'requerimiento', 'productos', 'tipo_producto', 'farmacia', 'productosbyrequerimientos', 'establecimiento','tiempo','almacen','nombre_servicio'));
    }

    public function ver_productos_del_pedido_enviado($id_requerimiento, $id_tipo_producto_egestion, $id_farmacia) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $model_requerimiento = new Requerimiento();
        $model_req_producto = new Req_producto();
        $model_farmacia = new Farmacia();
        $model_producto = new Producto();
        $nivel = $model_farmacia->ObtenerNivelFarmacia($id_farmacia);
        $farmacia = Farmacia::where('id', '=', $id_farmacia)->first();
        $almacen=$farmacia->almacen;
        $requerimiento = $model_requerimiento->GetRequerimientoById($id_requerimiento);
        
        
        if($farmacia->almacen==1){
            $productos = $model_producto->ProductosFarmaciasSegunNivelAndTipoProducto(3, $id_tipo_producto_egestion);
        }
        else
        {
            if($id_farmacia==36 || $id_farmacia==37){
                $productos = $model_producto->ProductosFarmaciasSegunNivelAndTipoProducto(2, $id_tipo_producto_egestion);
            }
            else
            {
                $productos = $model_producto->ProductosFarmaciasSegunNivelAndTipoProducto($nivel->nivel, $id_tipo_producto_egestion);       
            }
        }

        $model_tipo_producto = new Tipo_producto();
        $tipo_productos = $model_tipo_producto->ShowAll();


        $farmacia = $model_farmacia->GetFarmaByIdfarma($id_farmacia);
        $tipo_producto = Tipo_producto::where('id_egestion', '=', $id_tipo_producto_egestion)->first();
        $productosbyrequerimientos = $model_req_producto->ProductosByRequerimiento($id_requerimiento);

        return view('frontend.stocks.lista_productos_requerimiento', compact('tipo_productos', 'requerimiento', 'productos', 'tipo_producto', 'farmacia', 'productosbyrequerimientos'));
    }

    public function add_producto_al_pedido(Request $request) {
        
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $validator = Validator::make($request->all(), ['codigo_producto' => 'required', 'cantidad' => 'required|numeric',]);
        if ($validator->fails()) {
            return redirect('/ver_productos_del_pedido/' . $request->id_requerimiento . '/' . $request->tipo_producto_egestion . '/' . $request->id_farmacia)
                            ->withInput()->withErrors($validator);
        }
        $existe = Req_producto::where('id_requerimiento', $request->id_requerimiento)->where('codigo_producto', $request->codigo_producto)->where('estado', 1)->first();
        $datosProducto = Producto::where('codigo', $request->codigo_producto)->first();
        //$datosProducto = Producto::where('id', $request->id_producto)->first();
        //$existe = Req_producto::where('id_requerimiento', $request->id_requerimiento)->where('codigo_producto', $datosProducto->codigo_producto)
                        
        
        if (count($existe) > 0) {
            Session::flash('flash_message', 'El Producto ' . $datosProducto->nombre . ' ya esta en lista, solo edite la cantidad de ser necesario');
            return redirect('/ver_productos_del_pedido/' . $request->id_requerimiento . '/' . $request->tipo_producto_egestion . '/' . $request->id_farmacia);
        } else {
            $productos_requerimiento = new Req_producto();
            $productos_requerimiento->id_requerimiento = $request->id_requerimiento;
            $productos_requerimiento->codigo_producto = $request->codigo_producto;
            $productos_requerimiento->cantidad = $request->cantidad;
            $productos_requerimiento->estado = 1;
            $productos_requerimiento->save();
            Session::flash('flash_message', 'Producto agregado');
            return redirect('/ver_productos_del_pedido/' . $request->id_requerimiento . '/' . $request->tipo_producto_egestion . '/' . $request->id_farmacia);
        }
    }

    public function registrar_pedido(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $tiempo=1;
        $validator = Validator::make($request->all(), ['documento' => 'required', 'tipo_producto_egestion' => 'required',]);
        if ($validator->fails()) {
            return redirect('/ver_requerimiento/' . $request->id_user)->withInput()->withErrors($validator);
        }
        
        if($request->id_consultorio == 0):
            
            $existe = Requerimiento::where('id_farmacia', $request->id_farmacia)->where('documento', $request->documento)
                        ->where('estado', '>', 0)->first();
        else:
            $existe = Requerimiento::where('id_establecimiento', $request->id_establecimiento)->where('id_consultorio', $request->id_consultorio)->where('documento', $request->documento)
                        ->where('estado', '>', 0)->first();
        endif;    
        
        if (count($existe) > 0) {
            Session::flash('flash_message', 'El Pedido con el documento ' . $request->documento . ' ya existe');
            return redirect('/ver_requerimiento/' . $request->id_user);
        } else {
            $id_user = Auth::user()->id;

            $model_farmacia = new Farmacia();
            $model_producto = new Producto();
            $model_req_producto = new Req_producto();
            $establecimiento = $model_farmacia->GetEstablecimientoPNP($request->id_farmacia);
            $farmacia = $model_farmacia->GetFarmaByIdfarma($request->id_farmacia);
            $model_requerimiento = new Requerimiento();
            
            //validar si es primer dia del año
            $newyear = date('Y'); 
            if($request->id_consultorio == 0):
                $obtener_requerimiento = $model_requerimiento->GetContadorByIdFarmacia($request->id_farmacia);
            else:
                $obtener_requerimiento = $model_requerimiento->GetContadorByIdConsultorio($request->id_consultorio,$request->id_establecimiento);
            endif;

            $contador = $obtener_requerimiento->numero;
            $n = $contador + 1;
            
            

            $destino = $model_farmacia->GetFarmaciaDestinoRequerimiento();
            //----------------------------------------
            $insert_requerimiento = new Requerimiento;
            $insert_requerimiento->id_farmacia = $request->id_farmacia;
            $insert_requerimiento->numero = $establecimiento->codigo . "-" . $request->id_farmacia . "-" . $n . "-" . date("Y");
            $insert_requerimiento->contador = $n;
            $insert_requerimiento->estado = 1; //estado: 1:agregando productos(pendiente) -   estado 2: Enviado (Webservice) - estado 0: anulado
            $insert_requerimiento->documento = $request->documento;
            $insert_requerimiento->tipo_producto_egestion = $request->tipo_producto_egestion;
            $insert_requerimiento->id_user_created = $id_user;
            $insert_requerimiento->id_destino = $request->id_destino;
            $insert_requerimiento->ano = $newyear;
            if($request->id_consultorio!=0):
                $insert_requerimiento->id_consultorio = $request->id_consultorio;
                $insert_requerimiento->id_establecimiento = $request->id_establecimiento;
            endif;
            $insert_requerimiento->save();
            $id = $insert_requerimiento->id;
            $requerimiento = $model_requerimiento->GetRequerimientoById($id);
            $productosbyrequerimientos = $model_req_producto->ProductosByRequerimiento($id);

            $nivel = $model_farmacia->ObtenerNivelFarmacia($request->id_farmacia);
            if($request->id_farmacia==36 || $request->id_farmacia==37){
                $productos = $model_producto->ProductosFarmaciasSegunNivel(2);
            }
            else
            {
                if($farmacia->almacen==1){
                    $productos = $model_producto->ProductosFarmaciasSegunNivel(3);
                }
                else
                {
                    $productos = $model_producto->ProductosFarmaciasSegunNivel($nivel->nivel);        
                }
                
            }
            $model_tipo_producto = new Tipo_producto();
            $tipo_productos = $model_tipo_producto->ShowAll();

            $nombre_servicio='';
            if(Auth::user()->id_consultorio!=''):
                $servicio = new Consultorio();
                $nombre_servicio = $servicio->GetNombreById(Auth::user()->id_consultorio)->nombre;
            endif;

            $tipo_producto = Tipo_producto::where('id_egestion', '=', $request->tipo_producto_egestion)->first();
            Session::flash('flash_message', 'Se agrego el pedido y agregar productos farmacéuticos y dispositivos médicos');
            return view('frontend.stocks.form_add_productos_al_pedido', compact('destino','tipo_productos', 'productosbyrequerimientos', 'establecimiento', 'requerimiento', 'productos', 'tipo_producto', 'farmacia','tiempo','nombre_servicio'));
        }
    }

    public function eliminar_pedido($id, $id_user) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $requerimiento = Requerimiento::where('id', $id)->first();
        $sin_productos = Req_producto::where('id_requerimiento', $id)->where('estado', '>', 0)->get();
        if (count($sin_productos) > 0) {
            Session::flash('flash_message', 'No se puede eliminar el Pedido N° ' . $requerimiento->numero . ' por que tiene productos selecionados');
            return redirect('/ver_requerimiento/' . $id_user);
        } else {
            $id_user = Auth::user()->id;
            $req = Requerimiento::where('id', $id)->first();
            $req->estado = 0;
            $req->id_user_update = $id_user;
            $req->save();
            Session::flash('flash_message', $req->numero . ' fue eliminado');
            return redirect('/ver_requerimiento/' . $id_user);
        }
    }

    public function formulario_solicitar_requerimiento($id_user) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByUser($id_user);
        $nombre_farmacia = $farma->nombre;
        $stocks = DB::table('stock_farmacias')
                ->join('productos', 'stock_farmacias.codigo_producto', '=', 'productos.codigo')
                ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
                ->select('stock_farmacias.*', 'unidades.abreviatura')
                ->where('stock_farmacias.nombre_farmacia', '=', $nombre_farmacia)
                ->orderBy('stock_farmacias.nombre_producto', 'asc')
                ->get();
        return view('frontend.stocks.formulario_solicitar_requerimiento', compact('stocks', 'farma'));
    }
    public function stockFarmaciaByEstablecimiento(Request $request) {

        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;

        $id_user = Auth::user()->id;
        
        $model_stock = new Stock_farmacia;
        $model_farma = new Farmacia;
        if(!isset($request->id_establecimiento)){
            $farma = $model_farma->GetFarmaByUser($id_user);
            $id_establecimiento = $farma->id_establecimiento;
        } else {
            $id_establecimiento = $request->id_establecimiento;
        }
        
        $model = new Establecimiento();
        $establecimientos = $model->ShowAll();
        
        $model_kardexes = new Kardexe();
        //if($id_establecimiento!=78)
        $productos = $model_kardexes->GetStockActualByIdEstablecimientoAndIdProducto($id_establecimiento, 0);
        
        return view('frontend.stocks.reporte_stock_establecimiento', compact('establecimientos', 'productos', 'id_establecimiento'));
    }
    
    public function stocksbyFamarcia($id_user = 0, $situacion = 0) {

        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;

        $id_user = Auth::user()->id;

        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1)
            ;$ruta = $ruta[0];
        /* $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0]; */
        if (/* $permiso->lectura == "" */false)
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

        // PROCESO DE CARGA PESADA PARA EL STOCK
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        //====================
        $model_stock = new Stock_farmacia;
        $model_carga = new Carga_inicial_stock;
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByUser($id_user);
        $nombre_farmacia = $farma->nombre;
        $id_establecimiento = $farma->id_establecimiento;
        $id_farmacia = $farma->id;
        $almacen = $farma->almacen;
        $model_establecimiento = new Establecimiento;
        $establecimiento = $model_establecimiento->GetByIdfarmacia($id_farmacia);
        $carga = $model_carga->GetCargaRegistradaByFarmacia($id_farmacia);
        $contador = count($carga);
        if ($contador == 0) {
            $estado_carga = 0;
        } else {
            $estado_carga = $carga->estado;
        }
        $farmaciaActual = 1;

        //====================
        //$model_farma = new Farmacia;
        $model_producto = new Producto;
        $model_kardex = new Kardexe();

        $mes = 6;

        $fecha_inicio = $model_kardex->getFechaServidorRestaMeses(6);
        $fecha_fin = $model_kardex->getFechaServidor();
        //====================================
        //$kardexs = $model_kardex->ShowMedicamentos($nombre_farmacia);
        $kardexs = $model_kardex->ShowTotalKardexByFarmacia($nombre_farmacia, $fecha_fin);
        //$movimientos = $model_kardex->ShowTipoMov();
        //exit(print_r($kardexs));
        $resultado = array();

        foreach ($kardexs as $k) {
            $arrayarticulo = array('id_producto' => $k->id_producto,
                'codigo' => $k->codigo_producto,
                'nombre' => $k->nombre_producto,
                'unidad' => $k->unidad,
                'cantidad' => $k->total,
                /* 'egresos' => $egresos->total, */
                'egresos' => $k->egresos,
                /* 'nro_mes' => $nro_mes->nro_mes, */
                'nro_mes' => $k->nro_mes,
                /* 'recetas_devueltas' => $recetasDevueltas->recetas_devueltas, */
                'recetas_devueltas' => $k->recetas_devueltas,
                    /* 'can' => $can->total); */
            );

            $resultado[] = $arrayarticulo;
        }
        

        //----------------------------------------------------------------------
        $countMedicamento = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 1);
        $countbiomedico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 3);
        $countinsumolaboratorio = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 2);
        $countMaterialLaboratorio = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 4);
        $countMaterialDental = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 5);
        $countMatFotograficoAndFonotecnico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 6);
		$countMaterialQuirurgico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 7);
        $countProductosAfines = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 8);
        //----------------------------------------------------------------------
        $fecha_inicio_venc = $fecha_fin;
        $fecha_fin_venc = $model_kardex->getFechaServidorRestaMeses("-6");
        $stocks_proximos_vencer = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechasVencimiento("", $id_farmacia, $fecha_inicio_venc, $fecha_fin_venc);
        $count_proximos_vencer = count($stocks_proximos_vencer);
        //$stocks_productos_vencidos = $model_kardex->ShowTotalKardexByFarmaciaSinStock($nombre_farmacia, $fecha_inicio_venc);
		$stocks_productos_vencidos = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechaVencido("", $id_farmacia, $fecha_fin);
        $count_productos_vencidos = count($stocks_productos_vencidos);

        //----------------------------------------------------------------------
        
        return view('frontend.stocks.index', compact('resultado', 'farma', 'fecha', 'constock', 'sinstock', 'minstock', 'id_farmacia', 'estado_carga', 'countMedicamento', 'countbiomedico', 'countinsumolaboratorio', 'countMaterialLaboratorio', 'countMaterialDental', 'countMatFotograficoAndFonotecnico', 'count_proximos_vencer', 'count_productos_vencidos', 'situacion', 'farmaciaActual', 'establecimiento','almacen','countMaterialQuirurgico','countProductosAfines'));
    }

    public function stocksbyFamarciaModal($id_user) {
        $situacion = 0;

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        /*
          $id_user = Auth::user()->id;

          $ruta = Route::getCurrentRoute()->getPath();
          if(count($ruta=explode("/",$ruta)) > 1);$ruta=$ruta[0];
          $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id,$ruta);
          $permiso = $menu[0];
          if($permiso->lectura == "")return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
         */
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        //====================
        $model_stock = new Stock_farmacia;
        $model_carga = new Carga_inicial_stock;
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByUser($id_user);
        $nombre_farmacia = $farma->nombre;
        $id_establecimiento = $farma->id_establecimiento;
        $id_farmacia = $farma->id;
        $carga = $model_carga->GetCargaRegistradaByFarmacia($id_farmacia);
        $contador = count($carga);
        if ($contador == 0) {
            $estado_carga = 0;
        } else {
            $estado_carga = $carga->estado;
        }
        $farmaciaActual = 1;

        //====================
        //$model_farma = new Farmacia;
        $model_producto = new Producto;
        $model_kardex = new Kardexe();

        $mes = 6;

        $fecha_inicio = $model_kardex->getFechaServidorRestaMeses(6);
        $fecha_fin = $model_kardex->getFechaServidor();

        //====================================
        //$kardexs = $model_kardex->ShowMedicamentos($nombre_farmacia);
        $kardexs = $model_kardex->ShowTotalKardexByFarmacia($nombre_farmacia, $fecha_fin);
        //$movimientos = $model_kardex->ShowTipoMov();
        //exit(print_r($kardexs));
        $resultado = array();
        foreach ($kardexs as $k) {
            //$total = $model_kardex->ShowTotalByProd($k->codigo_producto, $nombre_farmacia, $fecha_inicio, $fecha_fin);
            //$ingresos = $model_kardex->ShowIngresosByProd($k->codigo_producto, $nombre_farmacia, $fecha_inicio, $fecha_fin);
            //$egresos = $model_kardex->ShowEgresosByProd($k->codigo_producto, $nombre_farmacia, $fecha_inicio, $fecha_fin);
            //$recetasDevueltas = $model_kardex->ShowRecetasDevueltas($k->codigo_producto, $nombre_farmacia, $fecha_inicio, $fecha_fin);
            //$nro_mes = $model_kardex->ShowNroMesByProd($k->codigo_producto, $nombre_farmacia, $fecha_inicio, $fecha_fin);
            //$can = $model_kardex->GetCantidadCAN($k->codigo_producto, $id_establecimiento);

            $arrayarticulo = array('id_producto' => $k->id_producto,
                'codigo' => $k->codigo_producto,
                'nombre' => $k->nombre_producto,
                'unidad' => $k->unidad,
                'cantidad' => $k->total,
                /* 'egresos' => $egresos->total, */
                'egresos' => $k->egresos,
                /* 'nro_mes' => $nro_mes->nro_mes, */
                'nro_mes' => $k->nro_mes,
                /* 'recetas_devueltas' => $recetasDevueltas->recetas_devueltas, */
                'recetas_devueltas' => $k->recetas_devueltas,
                    /* 'can' => $can->total); */
            );

            $resultado[] = $arrayarticulo;
        }


        //exit();
        //----------------------------------------------------------------------
        $countMedicamento = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 1);
        $countbiomedico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 3);
        $countinsumolaboratorio = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 2);
        $countMaterialLaboratorio = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 4);
        $countMaterialDental = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 5);
        $countMatFotograficoAndFonotecnico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 6);
		$countMaterialQuirurgico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 7);
        $countProductosAfines = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 8);
        //----------------------------------------------------------------------
        $fecha_inicio_venc = $fecha_fin;
        $fecha_fin_venc = $model_kardex->getFechaServidorRestaMeses("-6");
        $stocks_proximos_vencer = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechasVencimiento("", $id_farmacia, $fecha_inicio_venc, $fecha_fin_venc);
        $count_proximos_vencer = count($stocks_proximos_vencer);
        $stocks_productos_vencidos = $model_kardex->ShowTotalKardexByFarmaciaSinStock($nombre_farmacia, $fecha_inicio_venc);
        $count_productos_vencidos = count($stocks_productos_vencidos);
        //----------------------------------------------------------------------

        return view('frontend.stocks.index-modal', compact('resultado', 'farma', 'fecha', 'constock', 'sinstock', 'minstock', 'id_farmacia', 'estado_carga', 'countMedicamento', 'countbiomedico', 'countinsumolaboratorio', 'countMaterialLaboratorio', 'countMaterialDental', 'countMatFotograficoAndFonotecnico', 'count_proximos_vencer', 'count_productos_vencidos', 'situacion', 'farmaciaActual','countMaterialQuirurgico','countProductosAfines'));
    }

    public function stocksbyFamarciaAndConsultorio() {
        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;

        $id_user = Auth::user()->id;
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByUser($id_user);
        $id_farmacia = $farma->id;
        
        $idconsultorio = 0;
        $model_consultorio = new Consultorio;
        $consultorio = $model_consultorio->ShowConsultorios($id_farmacia);
        //exit(print_r($consultorio));
        return view('frontend.stocks.reporte_stockactu', compact('consultorio', 'idconsultorio'));
    }

    public function stocksbyFamarciaAndConsultorioPost(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $idconsultorio = $request->id_consultorio;

        $id_user = Auth::user()->id;

        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        //====================
        $model_stock = new Stock_farmacia;
        $model_carga = new Carga_inicial_stock;
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByUser($id_user);
        $nombre_farmacia = $farma->nombre;
        $id_establecimiento = $farma->id_establecimiento;
        $id_farmacia = $farma->id;
        $model_establecimiento = new Establecimiento;
        $establecimiento = $model_establecimiento->GetByIdfarmacia($id_farmacia);
        $carga = $model_carga->GetCargaRegistradaByFarmacia($id_farmacia);
        $contador = count($carga);
        if ($contador == 0) {
            $estado_carga = 0;
        } else {
            $estado_carga = $carga->estado;
        }
        $farmaciaActual = 1;

        //====================
        //$model_farma = new Farmacia;
        $model_producto = new Producto;
        $model_kardex = new Kardexe();

        $mes = 6;

        $fecha_inicio = $model_kardex->getFechaServidorRestaMeses(6);
        $fecha_fin = $model_kardex->getFechaServidor();

        //====================================
        $kardexs = $model_kardex->ShowTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 0, $idconsultorio, $id_farmacia);
        $resultado = array();
        foreach ($kardexs as $k) {
            $arrayarticulo = array('id_producto' => $k->id_producto,
                'codigo' => $k->codigo_producto,
                'nombre' => $k->nombre_producto,
                'unidad' => $k->unidad,
                'cantidad' => $k->total,
                /* 'egresos' => $egresos->total, */
                'egresos' => $k->egresos,
                /* 'nro_mes' => $nro_mes->nro_mes, */
                'nro_mes' => $k->nro_mes,
                /* 'recetas_devueltas' => $recetasDevueltas->recetas_devueltas, */
                'recetas_devueltas' => $k->recetas_devueltas,
                    /* 'can' => $can->total); */
            );

            $resultado[] = $arrayarticulo;
        }

        $model_consultorio = new Consultorio;
        $consultorio = $model_consultorio->ShowConsultorios($id_farmacia);

        return view('frontend.stocks.reporte_stockactu', compact('consultorio', 'idconsultorio', 'resultado'));
    }

    public function stocksbyFamarcias($id_farmacia, $situacion = 0) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $model_stock = new Stock_farmacia;
        $model_carga = new \App\Models\Carga_inicial_stock;
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
        $model_establecimiento = new Establecimiento;
        $establecimiento = $model_establecimiento->GetByIdfarmacia($id_farmacia);
        $nombre_farmacia = $farma->nombre;
        $id_establecimiento = $farma->id_establecimiento;
        $id_farmacia = $farma->id;
        $carga = $model_carga->GetCargaRegistradaByFarmacia($id_farmacia);
        $contador = count($carga);
        if ($contador == 0) {
            $estado_carga = 0;
        } else {
            $estado_carga = $carga->estado;
        }
        $farmaciaActual = 2;

        //====================
        //$model_farma = new Farmacia;
        $model_producto = new Producto;
        $model_kardex = new Kardexe();

        $mes = 6;
        $fecha_inicio = $model_kardex->getFechaServidorRestaMeses(6);
        $fecha_fin = $model_kardex->getFechaServidor();

        //===============================
        //$farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
        //$id_establecimiento = $farma->id_establecimiento;
        //$nombre_farmacia = $farma->nombre;
        //====================================
        //$kardexs = $model_kardex->ShowMedicamentos($nombre_farmacia);
        $kardexs = $model_kardex->ShowTotalKardexByFarmacia($nombre_farmacia, $fecha_fin);
        //$movimientos = $model_kardex->ShowTipoMov();

        $resultado = array();
        foreach ($kardexs as $k) {
            //$total = $model_kardex->ShowTotalByProd($k->codigo_producto, $nombre_farmacia, $fecha_inicio, $fecha_fin);
            //$ingresos = $model_kardex->ShowIngresosByProd($k->codigo_producto, $nombre_farmacia, $fecha_inicio, $fecha_fin);
            //$egresos = $model_kardex->ShowEgresosByProd($k->codigo_producto, $nombre_farmacia, $fecha_inicio, $fecha_fin);
            //$recetasDevueltas = $model_kardex->ShowRecetasDevueltas($k->codigo_producto, $nombre_farmacia, $fecha_inicio, $fecha_fin);
            //$nro_mes = $model_kardex->ShowNroMesByProd($k->codigo_producto, $nombre_farmacia, $fecha_inicio, $fecha_fin);
            //$can = $model_kardex->GetCantidadCAN($k->codigo_producto, $id_establecimiento);

            $arrayarticulo = array('id_producto' => $k->id_producto,
                'codigo' => $k->codigo_producto,
                'nombre' => $k->nombre_producto,
                'unidad' => $k->unidad,
                //'cantidad' => $total->total,
                'cantidad' => $k->total,
                //'ingresos' => $ingresos->total,
                /* 'egresos' => $egresos->total, */
                'egresos' => $k->egresos,
                /* 'nro_mes' => $nro_mes->nro_mes, */
                'nro_mes' => $k->nro_mes,
                /* 'recetas_devueltas' => $recetasDevueltas->recetas_devueltas, */
                'recetas_devueltas' => $k->recetas_devueltas,
                    /* 'can' => $can->total */
            );

            /*
              $listado = array();
              foreach ($movimientos as $m) {
              $suma = 0;
              if ($m->id == 1) {
              $suma = $model_kardex->ShowSaldoInicialAnteriorByProd($k->codigo_producto, $nombre_farmacia, $fecha_inicio)->suma;
              }
              $suma +=$model_kardex->ShowTotalesByMov($k->codigo_producto, $nombre_farmacia, $m->id, $fecha_inicio, $fecha_fin)->suma;
              $arraymovimiento = array('id' => $m->id, 'codgo' => $m->codgo, 'total' => $suma);
              $listado[] = $arraymovimiento;
              }
              $arrayarticulo['movimiento'] = $listado;
             */
            $resultado[] = $arrayarticulo;
        }

        //----------------------------------------------------------------------
        $countMedicamento = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 1);
        $countbiomedico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 3);
        $countinsumolaboratorio = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 2);
        $countMaterialLaboratorio = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 4);
        $countMaterialDental = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 5);
        $countMatFotograficoAndFonotecnico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 6);
		$countMaterialQuirurgico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 7);
        $countProductosAfines = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 8);
        //----------------------------------------------------------------------
        $fecha_inicio_venc = $fecha_fin;
        $fecha_fin_venc = $model_kardex->getFechaServidorRestaMeses("-6");
        $stocks_proximos_vencer = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechasVencimiento("", $id_farmacia, $fecha_inicio_venc, $fecha_fin_venc);
        $count_proximos_vencer = count($stocks_proximos_vencer);
        /*$stocks_productos_vencidos = $model_kardex->ShowTotalKardexByFarmaciaSinStock($nombre_farmacia, $fecha_inicio_venc);
        $count_productos_vencidos = count($stocks_productos_vencidos);*/
        $stocks_productos_vencidos = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechaVencido("", $id_farmacia, $fecha_fin);
        $count_productos_vencidos = count($stocks_productos_vencidos);
        //----------------------------------------------------------------------
        return view('frontend.stocks.index', compact('resultado', 'farma', 'fecha', 'constock', 'sinstock', 'minstock', 'id_farmacia', 'estado_carga', 'countMedicamento', 'countbiomedico', 'countinsumolaboratorio', 'countMaterialLaboratorio', 'countMaterialDental', 'countMatFotograficoAndFonotecnico', 'count_proximos_vencer', 'count_productos_vencidos', 'situacion', 'farmaciaActual', 'establecimiento','countMaterialQuirurgico','countProductosAfines'));
    }

    public function buscar_prodvencidos_porfecha(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        if (!$request->id_farmacia):
            return redirect('/productos_proximos_vencer/1');
        endif;

        $id_farmacia = $request->id_farmacia;
        $dias = $request->ano + $request->mes;
        $periodo = 0; //en otro rango
        //----------------------------------------------------------------------
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        //----------------------------------------------------------------------
        $nuevafecha = strtotime('+' . $dias . ' day', strtotime($fecha));
        $nuevafecha = date('m-Y', $nuevafecha);
        //----------------------------------------------------------------------
        $model_stock = new Stock_farmacia;
        $model_farma = new Farmacia;
        $model_kardex = new Kardexe();
        $fecha_fin = $model_kardex->getFechaServidor();
        $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
        $id_establecimiento = $farma->id_establecimiento;
        $nombre_farmacia = $farma->nombre;
        //----------------------------------------------------------------------
        //$stocks = $model_stock->ProductosProximosVencer($dias, $id_farmacia);
        $fecha_inicio_venc = $fecha_fin;
        $meses = $dias / 30;
        $fecha_fin_venc = $model_kardex->getFechaServidorRestaMeses("-" . $meses);
        $stocks = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechasVencimiento("", $id_farmacia, $fecha_inicio_venc, $fecha_fin_venc);
        //----------------------------------------------------------------------
        //$array_productos = $model_stock->GetProductosByFarmacia($id_farmacia);
        //$existe_stock = count($array_productos);
        $stocks_productos = $model_kardex->GetKardexLotesByCodigoProductoAndLoteAndFechaVencimientoAndRegSanitarioAndIdFarmacia("", "", "", "", $id_farmacia, "", "");
        $existe_stock = count($stocks_productos);
        //----------------------------------------------------------------------
        $countMedicamento = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 1);
        $countbiomedico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 3);
        $countinsumolaboratorio = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 2);
        $countMaterialLaboratorio = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 4);
        $countMaterialDental = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 5);
        $countMatFotograficoAndFonotecnico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 6);
		$countMaterialQuirurgico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 7);
        $countProductosAfines = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 8);
        //----------------------------------------------------------------------
        $fecha_inicio_venc = $fecha_fin;
        $fecha_fin_venc = $model_kardex->getFechaServidorRestaMeses("-6");
        $stocks_proximos_vencer = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechasVencimiento("", $id_farmacia, $fecha_inicio_venc, $fecha_fin_venc);
        $count_proximos_vencer = count($stocks_proximos_vencer);
        //$stocks_productos_vencidos = $model_kardex->ShowTotalKardexByFarmaciaSinStock($nombre_farmacia, $fecha_inicio_venc);
		$stocks_productos_vencidos = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechaVencido("", $id_farmacia, $fecha_fin);
        $count_productos_vencidos = count($stocks_productos_vencidos);
        //----------------------------------------------------------------------
        return view('frontend.stocks.productos_prox_vencer', compact('stocks', 'farma', 'fecha', 'constock', 'sinstock', 'minstock', 'id_farmacia', 'countMedicamento', 'countbiomedico', 'countinsumolaboratorio', 'countMaterialLaboratorio', 'countMaterialDental', 'countMatFotograficoAndFonotecnico', 'existe_stock', 'count_proximos_vencer', 'count_productos_vencidos', 'nuevafecha', 'periodo', 'dias','countMaterialQuirurgico','countProductosAfines'));
    }

    public function productos_vencidos($id_farmacia) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $mostrar_fecha = $date->format('m-Y');
        $model_stock = new Stock_farmacia;
        $model_farma = new Farmacia;
        $model_kardex = new Kardexe();
        $fecha_fin = $model_kardex->getFechaServidor();

        $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
        //$stocks = $model_stock->ProductosVencidos($fecha, $id_farmacia);
        //$fecha_fin 	= $model_kardex->getFechaServidorRestaMeses(-16);
        $stocks = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechaVencido("", $id_farmacia, $fecha_fin);
        //exit(print_r($stocks));
        //===============================
        $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
        $id_establecimiento = $farma->id_establecimiento;
        $nombre_farmacia = $farma->nombre;

        //----------------------------------------------------------------------
        //$array_productos = $model_stock->GetProductosByFarmacia($id_farmacia);
        //$existe_stock = count($array_productos);
        $stocks_productos = $model_kardex->GetKardexLotesByCodigoProductoAndLoteAndFechaVencimientoAndRegSanitarioAndIdFarmacia("", "", "", "", $id_farmacia, "", "");
        $existe_stock = count($stocks_productos);
        //----------------------------------------------------------------------
        $countMedicamento = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 1);
        $countbiomedico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 3);
        $countinsumolaboratorio = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 2);
        $countMaterialLaboratorio = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 4);
        $countMaterialDental = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 5);
        $countMatFotograficoAndFonotecnico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 6);
		$countMaterialQuirurgico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 7);
        $countProductosAfines = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 8);
        //----------------------------------------------------------------------
        $fecha_inicio_venc = $fecha_fin;
        $fecha_fin_venc = $model_kardex->getFechaServidorRestaMeses("-6");
        $stocks_proximos_vencer = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechasVencimiento("", $id_farmacia, $fecha_inicio_venc, $fecha_fin_venc);
        $count_proximos_vencer = count($stocks_proximos_vencer);
        $stocks_productos_vencidos = $model_kardex->ShowTotalKardexByFarmaciaSinStock($nombre_farmacia, $fecha_inicio_venc);
        //exit(print_r($stocks_productos_vencidos));
        $count_productos_vencidos = count($stocks);
        //----------------------------------------------------------------------
        return view('frontend.stocks.productos_vencidos', compact('stocks', 'farma', 'fecha', 'constock', 'sinstock', 'minstock', 'id_farmacia', 'countMedicamento', 'countbiomedico', 'countinsumolaboratorio', 'countMaterialLaboratorio', 'countMaterialDental', 'countMatFotograficoAndFonotecnico', 'existe_stock', 'count_proximos_vencer', 'count_productos_vencidos', 'mostrar_fecha','countMaterialQuirurgico','countProductosAfines'));
    }

    public function productos_proximos_vencer($id_farmacia) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $periodo = 1; //en 06 meses
        //----------------------------------------------------------------------
        $nuevafecha = strtotime('+180 day', strtotime($fecha));
        $nuevafecha = date('Y-m-j', $nuevafecha);
        $dias = 180;
        //----------------------------------------------------------------------
        $model_stock = new Stock_farmacia;
        $model_farma = new Farmacia;
        $model_kardex = new Kardexe();
        $fecha_fin = $model_kardex->getFechaServidor();
        $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
        $id_establecimiento = $farma->id_establecimiento;
        $nombre_farmacia = $farma->nombre;

        //$stocks = $model_stock->ProductosProximosVencer(180, $id_farmacia);
        $fecha_inicio_venc = $fecha_fin;
        $fecha_fin_venc = $model_kardex->getFechaServidorRestaMeses("-6");
        $stocks = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechasVencimiento("", $id_farmacia, $fecha_inicio_venc, $fecha_fin_venc);

        //----------------------------------------------------------------------
        //$array_productos = $model_stock->GetProductosByFarmacia($id_farmacia);
        //$existe_stock = count($array_productos);
        $stocks_productos = $model_kardex->GetKardexLotesByCodigoProductoAndLoteAndFechaVencimientoAndRegSanitarioAndIdFarmacia("", "", "", "", $id_farmacia, "", "");
        $existe_stock = count($stocks_productos);

        //----------------------------------------------------------------------
        $countMedicamento = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 1);
        $countbiomedico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 3);
        $countinsumolaboratorio = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 2);
        $countMaterialLaboratorio = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 4);
        $countMaterialDental = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 5);
        $countMatFotograficoAndFonotecnico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 6);
		$countMaterialQuirurgico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 7);
        $countProductosAfines = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 8);
        //----------------------------------------------------------------------
        $fecha_inicio_venc = $fecha_fin;
        $fecha_fin_venc = $model_kardex->getFechaServidorRestaMeses("-6");
        $stocks_proximos_vencer = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechasVencimiento("", $id_farmacia, $fecha_inicio_venc, $fecha_fin_venc);
        $count_proximos_vencer = count($stocks_proximos_vencer);
        /*$stocks_productos_vencidos = $model_kardex->ShowTotalKardexByFarmaciaSinStock($nombre_farmacia, $fecha_inicio_venc);
        $count_productos_vencidos = count($stocks_productos_vencidos);*/
        $stocks_productos_vencidos = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechaVencido("", $id_farmacia, $fecha_fin);
        $count_productos_vencidos = count($stocks_productos_vencidos);
        //----------------------------------------------------------------------
        return view('frontend.stocks.productos_prox_vencer', compact('stocks', 'farma', 'fecha', 'constock', 'sinstock', 'minstock', 'id_farmacia', 'countMedicamento', 'countbiomedico', 'countinsumolaboratorio', 'countMaterialLaboratorio', 'countMaterialDental', 'countMatFotograficoAndFonotecnico', 'existe_stock', 'count_proximos_vencer', 'count_productos_vencidos', 'nuevafecha', 'periodo', 'dias','countMaterialQuirurgico','countProductosAfines'));
    }

    public function productos_sinstock($id_farmacia) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $model_stock = new Stock_farmacia;
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
        $stocks = $model_stock->GetSinStock($id_farmacia);
        $constock = $model_stock->GetCountConStock($id_farmacia);
        $sinstock = $model_stock->GetCountSinStock($id_farmacia);
        $minstock = $model_stock->GetCountMinimoStock($id_farmacia);
        //----------------------------------------------------------------------
        //$array_productos = $model_stock->GetProductosByFarmacia($id_farmacia);
        //$existe_stock = count($array_productos);
        $stocks_productos = $model_kardex->GetKardexLotesByCodigoProductoAndLoteAndFechaVencimientoAndRegSanitarioAndIdFarmacia("", "", "", "", $id_farmacia, "", "");
        $existe_stock = count($stocks_productos);
        //----------------------------------------------------------------------
        $countMedicamento = $model_stock->GetCountProductosByTipoProductoAndFarma($id_farmacia, 1);
        $countbiomedico = $model_stock->GetCountProductosByTipoProductoAndFarma($id_farmacia, 3);
        $countinsumolaboratorio = $model_stock->GetCountProductosByTipoProductoAndFarma($id_farmacia, 2);
        $countMaterialLaboratorio = $model_stock->GetCountProductosByTipoProductoAndFarma($id_farmacia, 4);
        $countMaterialDental = $model_stock->GetCountProductosByTipoProductoAndFarma($id_farmacia, 5);
        $countMatFotograficoAndFonotecnico = $model_stock->GetCountProductosByTipoProductoAndFarma($id_farmacia, 6);
		$countMaterialQuirurgico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 7);
        $countProductosAfines = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 8);
        //----------------------------------------------------------------------
        $fecha_inicio_venc = $fecha_fin;
        $fecha_fin_venc = $model_kardex->getFechaServidorRestaMeses("-6");
        $stocks_proximos_vencer = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechasVencimiento("", $id_farmacia, $fecha_inicio_venc, $fecha_fin_venc);
        $count_proximos_vencer = count($stocks_proximos_vencer);
        $stocks_productos_vencidos = $model_kardex->ShowTotalKardexByFarmaciaSinStock($nombre_farmacia, $fecha_inicio_venc);
        $count_productos_vencidos = count($stocks_productos_vencidos);
        //----------------------------------------------------------------------
        return view('frontend.stocks.productos_sinstock', compact('stocks', 'farma', 'fecha', 'constock', 'sinstock', 'minstock', 'id_farmacia', 'countMedicamento', 'countbiomedico', 'countinsumolaboratorio', 'countMaterialLaboratorio', 'countMaterialDental', 'countMatFotograficoAndFonotecnico', 'existe_stock', 'count_proximos_vencer', 'count_productos_vencidos','countMaterialQuirurgico','countProductosAfines'));
    }

    public function productos_minstock($id_farmacia) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $model_stock = new Stock_farmacia;
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
        $stocks = $model_stock->GetMinStock($id_farmacia);
        $constock = $model_stock->GetCountConStock($id_farmacia);
        $sinstock = $model_stock->GetCountSinStock($id_farmacia);
        $minstock = $model_stock->GetCountMinimoStock($id_farmacia);
        //----------------------------------------------------------------------
        //$array_productos = $model_stock->GetProductosByFarmacia($id_farmacia);
        //$existe_stock = count($array_productos);
        $stocks_productos = $model_kardex->GetKardexLotesByCodigoProductoAndLoteAndFechaVencimientoAndRegSanitarioAndIdFarmacia("", "", "", "", $id_farmacia, "", "");
        $existe_stock = count($stocks_productos);
        //----------------------------------------------------------------------
        $countMedicamento = $model_stock->GetCountProductosByTipoProductoAndFarma($id_farmacia, 1);
        $countbiomedico = $model_stock->GetCountProductosByTipoProductoAndFarma($id_farmacia, 3);
        $countinsumolaboratorio = $model_stock->GetCountProductosByTipoProductoAndFarma($id_farmacia, 2);
        $countMaterialLaboratorio = $model_stock->GetCountProductosByTipoProductoAndFarma($id_farmacia, 4);
        $countMaterialDental = $model_stock->GetCountProductosByTipoProductoAndFarma($id_farmacia, 5);
        $countMatFotograficoAndFonotecnico = $model_stock->GetCountProductosByTipoProductoAndFarma($id_farmacia, 6);
		$countMaterialQuirurgico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 7);
        $countProductosAfines = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 8);
        //----------------------------------------------------------------------
        $fecha_inicio_venc = $fecha_fin;
        $fecha_fin_venc = $model_kardex->getFechaServidorRestaMeses("-6");
        $stocks_proximos_vencer = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechasVencimiento("", $id_farmacia, $fecha_inicio_venc, $fecha_fin_venc);
        $count_proximos_vencer = count($stocks_proximos_vencer);
        $stocks_productos_vencidos = $model_kardex->ShowTotalKardexByFarmaciaSinStock($nombre_farmacia, $fecha_inicio_venc);
        $count_productos_vencidos = count($stocks_productos_vencidos);
        //----------------------------------------------------------------------
        return view('frontend.stocks.productos_minstock', compact('stocks', 'farma', 'fecha', 'constock', 'sinstock', 'minstock', 'id_farmacia', 'countMedicamento', 'countbiomedico', 'countinsumolaboratorio', 'countMaterialLaboratorio', 'countMaterialDental', 'countMatFotograficoAndFonotecnico', 'existe_stock', 'count_proximos_vencer', 'count_productos_vencidos','countMaterialQuirurgico','countProductosAfines'));
    }

    public function productos_constock($id_farmacia) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $model_stock = new Stock_farmacia;
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
        $stocks = $model_stock->GetConStock($id_farmacia);
        $constock = $model_stock->GetCountConStock($id_farmacia);
        $sinstock = $model_stock->GetCountSinStock($id_farmacia);
        $minstock = $model_stock->GetCountMinimoStock($id_farmacia);
        //----------------------------------------------------------------------
        //$array_productos = $model_stock->GetProductosByFarmacia($id_farmacia);
        //$existe_stock = count($array_productos);
        $stocks_productos = $model_kardex->GetKardexLotesByCodigoProductoAndLoteAndFechaVencimientoAndRegSanitarioAndIdFarmacia("", "", "", "", $id_farmacia, "", "");
        $existe_stock = count($stocks_productos);
        //----------------------------------------------------------------------
        $countMedicamento = $model_stock->GetCountProductosByTipoProductoAndFarma($id_farmacia, 1);
        $countbiomedico = $model_stock->GetCountProductosByTipoProductoAndFarma($id_farmacia, 3);
        $countinsumolaboratorio = $model_stock->GetCountProductosByTipoProductoAndFarma($id_farmacia, 2);
        $countMaterialLaboratorio = $model_stock->GetCountProductosByTipoProductoAndFarma($id_farmacia, 4);
        $countMaterialDental = $model_stock->GetCountProductosByTipoProductoAndFarma($id_farmacia, 5);
        $countMatFotograficoAndFonotecnico = $model_stock->GetCountProductosByTipoProductoAndFarma($id_farmacia, 6);
		$countMaterialQuirurgico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 7);
        $countProductosAfines = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 8);
        //----------------------------------------------------------------------
        $fecha_inicio_venc = $fecha_fin;
        $fecha_fin_venc = $model_kardex->getFechaServidorRestaMeses("-6");
        $stocks_proximos_vencer = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechasVencimiento("", $id_farmacia, $fecha_inicio_venc, $fecha_fin_venc);
        $count_proximos_vencer = count($stocks_proximos_vencer);
        $stocks_productos_vencidos = $model_kardex->ShowTotalKardexByFarmaciaSinStock($nombre_farmacia, $fecha_inicio_venc);
        $count_productos_vencidos = count($stocks_productos_vencidos);
        //----------------------------------------------------------------------
        return view('frontend.stocks.productos_constock', compact('stocks', 'farma', 'fecha', 'constock', 'sinstock', 'minstock', 'id_farmacia', 'countMedicamento', 'countbiomedico', 'countinsumolaboratorio', 'countMaterialLaboratorio', 'countMaterialDental', 'countMatFotograficoAndFonotecnico', 'existe_stock', 'count_proximos_vencer', 'count_productos_vencidos','countMaterialQuirurgico','countProductosAfines'));
    }

    public function productos_porlotes(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $id_farmacia = Auth::user()->id_farmacia;
        //$usuario=$request->id_user;
        if($request->fechaCorte!=''){
            $fecha = $request->fechaCorte;    
        }   
        else{
            $date = Carbon::now();
            $fecha = $date->format('d-m-Y');
        }
	$model_stock = new Stock_farmacia;
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
        //----------------------------------------------------------------------
        //$stocks = $model_stock->GetStockPorLotes($id_farmacia);
        $model_kardex = new Kardexe();
        $stocks = $model_kardex->GetReporteKardexLotes($id_farmacia, $fecha);
	$fecha_fin = $model_kardex->getFechaServidor();

        //===============================
        $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
        $id_establecimiento = $farma->id_establecimiento;
        $nombre_farmacia = $farma->nombre;

        //----------------------------------------------------------------------
        $existe_stock = count($stocks);
        //----------------------------------------------------------------------
        $countMedicamento = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 1);
        $countbiomedico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 3);
        $countinsumolaboratorio = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 2);
        $countMaterialLaboratorio = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 4);
        $countMaterialDental = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 5);
        $countMatFotograficoAndFonotecnico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 6);
		$countMaterialQuirurgico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 7);
        $countProductosAfines = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 8);
        //----------------------------------------------------------------------
        $fecha_inicio_venc = $fecha_fin;
        $fecha_fin_venc = $model_kardex->getFechaServidorRestaMeses("-6");
        $stocks_proximos_vencer = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechasVencimiento("", $id_farmacia, $fecha_inicio_venc, $fecha_fin_venc);
        $count_proximos_vencer = count($stocks_proximos_vencer);
        //$stocks_productos_vencidos = $model_kardex->ShowTotalKardexByFarmaciaSinStock($nombre_farmacia, $fecha_inicio_venc);
		$stocks_productos_vencidos = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechaVencido("", $id_farmacia, $fecha_fin);
        $count_productos_vencidos = count($stocks_productos_vencidos);
        //----------------------------------------------------------------------
        return view('frontend.stocks.productos_porlotes', compact('stocks', 'farma', 'fecha', 'constock', 'sinstock', 'minstock', 'id_farmacia', 'countMedicamento', 'countbiomedico', 'countinsumolaboratorio', 'countMaterialLaboratorio', 'countMaterialDental', 'countMatFotograficoAndFonotecnico', 'existe_stock', 'count_proximos_vencer', 'count_productos_vencidos','countMaterialQuirurgico','countProductosAfines'));
    }

    public function productos_por_tipo($id_farmacia, $tipo) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $model_stock = new Stock_farmacia;
        $model_farma = new Farmacia;

        $model_producto = new Producto;
        $model_kardex = new Kardexe();

        $mes = 6;
        $fecha_inicio = $model_kardex->getFechaServidorRestaMeses(6);
        $fecha_fin = $model_kardex->getFechaServidor();

        //===============================
        $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);

        $id_establecimiento = $farma->id_establecimiento;
        $nombre_farmacia = $farma->nombre;
        //====================================
        $kardexs = $model_kardex->ShowTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, $tipo);
        //$kardexs = $model_kardex->ShowMedicamentosByTipo($nombre_farmacia, $tipo);
        //$movimientos = $model_kardex->ShowTipoMov();

        $resultado = array();
        foreach ($kardexs as $k) {
            //$total = $model_kardex->ShowTotalByProd($k->codigo_producto, $nombre_farmacia, $fecha_inicio, $fecha_fin);
            //$ingresos = $model_kardex->ShowIngresosByProd($k->codigo_producto, $nombre_farmacia, $fecha_inicio, $fecha_fin);
            //$egresos = $model_kardex->ShowEgresosByProd($k->codigo_producto, $nombre_farmacia, $fecha_inicio, $fecha_fin);
            //$recetasDevueltas = $model_kardex->ShowRecetasDevueltas($k->codigo_producto, $nombre_farmacia, $fecha_inicio, $fecha_fin);
            //$nro_mes = $model_kardex->ShowNroMesByProd($k->codigo_producto, $nombre_farmacia, $fecha_inicio, $fecha_fin);
            //$can = $model_kardex->GetCantidadCAN($k->codigo_producto, $id_establecimiento);

            $arrayarticulo = array('id_producto' => $k->id_producto,
                'codigo' => $k->codigo_producto,
                'nombre' => $k->nombre_producto,
                'unidad' => $k->unidad,
                'cantidad' => $k->total,
                /* 'ingresos' => $ingresos->total, */
                /* 'egresos' => $egresos->total, */
                'egresos' => $k->egresos,
                /* 'nro_mes' => $nro_mes->nro_mes, */
                'nro_mes' => $k->nro_mes,
                /* 'recetas_devueltas' => $recetasDevueltas->recetas_devueltas, */
                'recetas_devueltas' => $k->recetas_devueltas,
                    //'can' => $can->total
            );

            $resultado[] = $arrayarticulo;
        }

        //----------------------------------------------------------------------
        $existe_stock = count($kardexs);
        //----------------------------------------------------------------------
        $countMedicamento = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 1);
        $countbiomedico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 3);
        $countinsumolaboratorio = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 2);
        $countMaterialLaboratorio = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 4);
        $countMaterialDental = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 5);
        $countMatFotograficoAndFonotecnico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 6);
		$countMaterialQuirurgico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 7);
        $countProductosAfines = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 8);
        //----------------------------------------------------------------------
        switch ($tipo) {
            case 1:
                $nombre = 'MEDICAMENTOS';
                $contador_tipo = $countMedicamento;
                break;
            case 2:
                $nombre = 'INSUMOS DE LABORATORIO';
                $contador_tipo = $countinsumolaboratorio;
                break;
            case 3:
                $nombre = 'BIOMEDICOS';
                $contador_tipo = $countbiomedico;
                break;
            case 4:
                $nombre = 'MATERIAL DE LABORATORIO';
                $contador_tipo = $countMaterialLaboratorio;
                break;
            case 5:
                $nombre = 'MATERIAL DENTAL';
                $contador_tipo = $countMaterialDental;
                break;
            case 6:
                $nombre = 'MATERIAL FOTOGRAFICO Y FONOTECNICO';
                $contador_tipo = $countMatFotograficoAndFonotecnico;
                break;
			case 7:
                $nombre = 'MATERIAL QUIRURGICO';
                $contador_tipo = $countMatFotograficoAndFonotecnico;
                break;
            case 8:
                $nombre = 'PRODUCTOS AFINES';
                $contador_tipo = $countMatFotograficoAndFonotecnico;
                break;
        }
        //----------------------------------------------------------------------
        $fecha_inicio_venc = $fecha_fin;
        $fecha_fin_venc = $model_kardex->getFechaServidorRestaMeses("-6");
        $stocks_proximos_vencer = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechasVencimiento("", $id_farmacia, $fecha_inicio_venc, $fecha_fin_venc);
        $count_proximos_vencer = count($stocks_proximos_vencer);
        /*$stocks_productos_vencidos = $model_kardex->ShowTotalKardexByFarmaciaSinStock($nombre_farmacia, $fecha_inicio_venc);
        $count_productos_vencidos = count($stocks_productos_vencidos);*/
        $stocks_productos_vencidos = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechaVencido("", $id_farmacia, $fecha_fin);
        $count_productos_vencidos = count($stocks_productos_vencidos);
        //----------------------------------------------------------------------
        return view('frontend.stocks.productos_portipo', compact('resultado', 'farma', 'fecha', 'constock', 'sinstock', 'minstock', 'id_farmacia', 'countMedicamento', 'countbiomedico', 'countinsumolaboratorio', 'countMaterialLaboratorio', 'countMaterialDental', 'countMatFotograficoAndFonotecnico', 'nombre', 'contador_tipo', 'existe_stock', 'tipo', 'count_proximos_vencer', 'count_productos_vencidos','countMaterialQuirurgico','countProductosAfines'));
    }

    public function allFarmacias_withStocks() {
        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;

        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1)
            ;$ruta = $ruta[0];
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

        $model_stock = new Stock_farmacia;
        $model_farmacia = new Farmacia;
        $model_kardex = new Kardexe();
        $fecha_inicio = $model_kardex->getFechaServidorRestaMeses(6);
        $fecha_fin = $model_kardex->getFechaServidor();

        $id_user = Auth::user()->id;
        $farmacia = $model_farmacia->GetFarmaByUser($id_user);

        if ($farmacia->flagestablecimientos == 1 && $farmacia->flagfarmacias == 1):
            $id_establecimiento = 0;
            $allfarmacias = $model_farmacia->OnlyFarmacias($id_establecimiento);
        endif;

        if ($farmacia->flagestablecimientos == 0 && $farmacia->flagfarmacias == 1):
            $id_establecimiento = $farmacia->id_establecimiento;
            $allfarmacias = $model_farmacia->OnlyFarmacias($id_establecimiento);
        endif;

        if ($farmacia->flagestablecimientos == 0 && $farmacia->flagfarmacias == 0):
            $id_farmacia = Auth::user()->id_farmacia;
            $allfarmacias = $model_farmacia->GetFarmaciaById($id_farmacia);
        endif;

        foreach ($allfarmacias as $f) {
            $id_farmacia = $f->id;
            $nombre = $f->nombre;
            $total = $model_kardex->ShowCountTotalKardexByFarmacia($f->nombre, $fecha_fin, 0);
            $desabastecido = 0;
            $sobreStock = 0;
            $normoStock = 0;
            $subStock = 0;
            $sinRotacion = 0;
            $kardexs = $model_kardex->ShowTotalKardexByFarmacia($f->nombre, $fecha_fin);

            foreach ($kardexs as $k) {
                //$egresos = $model_kardex->ShowEgresosByProd($k->codigo_producto, $f->nombre, $fecha_inicio, $fecha_fin);
                //$recetasDevueltas = $model_kardex->ShowRecetasDevueltas($k->codigo_producto, $f->nombre, $fecha_inicio, $fecha_fin);
                //$nro_mes = $model_kardex->ShowNroMesByProd($k->codigo_producto, $f->nombre, $fecha_inicio, $fecha_fin);

                if ($k->total > 0 && ($k->total - $k->recetas_devueltas) == 0) {
                    $cpma = -1;
                } else {
                    if ($k->nro_mes == 0) {
                        $cpma = -1;
                    } else {
                        if (($k->total - $k->recetas_devueltas) == 0) {
                            $cpma = 0;
                        } else {
                            $cpma = ($k->total - $k->recetas_devueltas) / $k->nro_mes;
                        }
                    }
                }

                if (($k->total - $k->recetas_devueltas) == 0) {
                    $meses_provicion = 0;
                } else {
                    $provision = round(($k->total / $cpma), 1);
                    $meses_provicion = $provision;
                }


                if ($k->total <= 0):
                    $desabastecido++;
                else:
                    if ($cpma <= 0):
                        $sinRotacion++;
                    endif;
                    if ($meses_provicion > 0 && $meses_provicion < 2):
                        $subStock++;
                    endif;
                    if ($meses_provicion >= 2 && $meses_provicion <= 6):
                        $normoStock++;
                    endif;
                    if ($meses_provicion > 6):
                        $sobreStock++;
                    endif;

                endif;
            }

            $farma_productos = array('id_farmacia' => $id_farmacia, 'nombre' => $nombre, 'total' => $total, 'desabastecido' => $desabastecido, 'sobreStock' => $sobreStock, 'normoStock' => $normoStock, 'subStock' => $subStock, 'sinRotacion' => $sinRotacion);
            $listado[] = $farma_productos;
        }
        return view('frontend.direoss.stocks.index', compact('listado'));
    }

    public function create() {
        $productos = DB::table('productos')
                ->select('productos.*')
                ->get();
        return view('frontend.stocks.create', compact('productos'));
    }

    public function store(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $model_farma = new Farmacia;
        $user = $request->id_user;
        $farma = $model_farma->GetFarmaByUser($user);
        $id_farmacia = $farma->id;
        $stock = new Stock;
        $stock->monto_inicial = $request->monto_inicial;
        $stock->monto_actual = $request->monto_inicial;
        $stock->monto_consumido = 0;
        $stock->fecha_registro = Carbon::now();
        $stock->estado = $request->estado;
        $stock->id_producto = $request->id_producto;
        $stock->id_user = $request->id_user;
        $stock->fecha_vencimiento = $request->fecha_vencimiento;
        $stock->id_farmacia = $id_farmacia;
        $existe = $stock->ExisteStock($request->id_producto, $id_farmacia);
        if ($existe) {
            return redirect('/stocksbyuser/' . $user)
                            ->withInput()
                            ->withErrors(array('El Producto tiene stock'));
        }
        $stock->save();
        Session::flash('flash_message', 'Stock added!');
        return redirect('/stocksbyuser/' . $user);
    }

    public function show($id) {
        $stock = Stock::findOrFail($id);
        return view('frontend.stocks.show', compact('stock'));
    }

    public function edit($id) {
        $stock = Stock::findOrFail($id);

        return view('frontend.stocks.edit', compact('stock'));
    }

    public function update($id, Request $request) {
        $stock = Stock::findOrFail($id);
        $stock->monto_inicial = $request->monto_inicial;
        $stock->monto_actual = $request->monto_inicial;
        $stock->estado = $request->estado;
        $stock->update($request->all());
        Session::flash('flash_message', 'Stock updated!');
        return redirect('/stocksbyuser/' . $request->id_user);
    }

    public function destroy($id) {
        Stock::destroy($id);

        Session::flash('flash_message', 'Stock deleted!');

        return redirect('stocks');
    }

    public function all_nota_entrada($id_farmacia = 0) {

        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;

        $id_farmacia = Auth::user()->id_farmacia;
        $count = Guia_entrada::where('estado', '=', 1)->where('id_farmacia', '=', $id_farmacia)->count();
        $model_farma = new Farmacia;
        $farmacia = $model_farma->GetFarmaByIdfarma($id_farmacia);
        //------------------------------------------------------------------------
        $model_guias = new Guia_entrada();
        
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');   
        $fechaActual = Carbon::parse($fecha);
		if($farmacia->almacen==1){
            $fechaHasta = $date->format('d-m-Y');
            $date->modify('first day of this month');
            $fechaDesde = $date->format('d-m-Y');
            $pecosas = $model_guias->ShowAllNotaEntradaByIdAlmacen($id_farmacia,$fechaDesde,$fechaHasta);
        }
        else
        {
            $pecosas = $model_guias->ShowAllNotaEntradaByIdfarmacia($id_farmacia,0);    
        }
        $model_dependencia = new Dependencia;
        $dependencia = $model_dependencia->getDependencia('S');
        //-------------------------------------------------------------------------
        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1)
            ;$ruta = $ruta[0];
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        //echo $farmacia->almacen; exit();
		if($farmacia->almacen==1){
            return view('frontend.stocks.all_notas_entradas_almacen', compact('pecosas', 'farmacia', 'permiso','dependencia','fechaActual','fechaDesde','fechaHasta'));
        }else
        {
            return view('frontend.stocks.all_notas_entradas', compact('pecosas', 'farmacia', 'permiso','dependencia','fechaActual'));
        }

    }

    public function add_documento_nota_entrada($id_user) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $model_farmacia = new Farmacia;
        $model_guias = new Guia_entrada();
        
        $model_kardex = new Kardexe();
        $fechaServidor = $model_kardex->getFechaServidor();
        
        $hoy = date('Y-m-j');
        $farmacia = $model_farmacia->GetFarmaByUser($id_user);
        $rango_registro = $farmacia->rango_registro;
        $id_farmacia = $farmacia->id;
        $almacen=$farmacia->almacen;
        $nro_nota_entrada = $model_guias->getNroNota($id_farmacia);
        
        
        $model_motivo = new MotivoTransferencia;
        $motivo = $model_motivo->getMotivoTransferenciaNotaEntrada();

        //print_r($motivo_transferencia);
        $fecha_hace_treinta_dias = strtotime('-' . $rango_registro . ' day', strtotime($hoy));
        $fecha_hace_treinta_dias = date('d-m-Y', $fecha_hace_treinta_dias);
        //----------------------------------------------------------------------
        if($almacen==1){
            $model_dependencia = new Dependencia;
            $dependencia = $model_dependencia->getDependencia('S');

            $model_concepto = new Concepto;
            $concepto = $model_concepto->getConcepto('S');

            return view('frontend.stocks.documento_nota_entrada_almacen', compact('farmacia','id_farmacia', 'fecha_hace_treinta_dias', 'rango_registro','motivo','fechaServidor','nro_nota_entrada','dependencia','concepto'));
        }
        else
        {
            return view('frontend.stocks.documento_nota_entrada', compact('farmacia','id_farmacia', 'fecha_hace_treinta_dias', 'rango_registro','motivo','fechaServidor','nro_nota_entrada'));    
        }        
    }

    public function edit_nota_entrada($id, $id_user) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $model_farmacia = new Farmacia;
        $model_guias = new Guia_entrada();
        
        $model_kardex = new Kardexe();
        $fechaServidor = $model_kardex->getFechaServidor();
        
        $hoy = date('Y-m-j');
        $farmacia = $model_farmacia->GetFarmaByUser($id_user);
        $rango_registro = $farmacia->rango_registro;
        $id_farmacia = $farmacia->id;        
        $almacen = $farmacia->almacen;        
        
        $model_guias = new Guia_entrada;
        $pecosa = $model_guias->GetPecosaByIdNotaEntrada($id);
        $model_guiaentrada = new Guia_entrada;
        
        if($almacen!=1){
            $nro_repetido = $model_guiaentrada->ExisteNumeroEntrada($pecosa->nota_entrada,$id_farmacia);    
        }
        else
        {
            $nro_repetido = $model_guiaentrada->ExisteNumeroEntrada($pecosa->nota_entrada,$id_farmacia,$pecosa->concepto);
        }
		
        if(count($nro_repetido)>1) 
            $mostrar=1;       
        else
            $mostrar=0;

        
        $model_motivo = new MotivoTransferencia;
        $motivo = $model_motivo->getMotivoTransferenciaNotaEntrada();

        $fecha_hace_treinta_dias = strtotime('-' . $rango_registro . ' day', strtotime($hoy));
        $fecha_hace_treinta_dias = date('d-m-Y', $fecha_hace_treinta_dias);
        
        if($almacen!=1){
            switch ($pecosa->tipo) {
                case 2: $model_origen = new Consultorio;
                        $destinos = $model_origen->ShowConsultorios($farmacia->id); 
                    break;
                case 4: $model_origen = new Dependencia;
                        $destinos = $model_origen->getDependencia('S');
                    break;
                case 5: $model_origen = new Establecimiento;
                        $destinos = $model_origen->EstablecimientosSinSistema();
                    break;
            }    
            
            //----------------------------------------------------------------------
            return view('frontend.stocks.editar_nota_entrada', compact('farmacia','id_farmacia', 'fecha_hace_treinta_dias', 'rango_registro', 'motivo','destinos','fechaServidor','pecosa'));
        }
        else
        {
            //$model_dependencia = new Dependencia;
            //$dependencia = $model_dependencia->getDependencia('S');
			
			if($pecosa->concepto==11){
                $model_establecimiento = new Establecimiento;
                $dependencia = $model_establecimiento->ShowAll();
            }
            else
            {
                if($pecosa->concepto==7 || $pecosa->concepto==8){
                    $model_proveedor = new Proveedore;
                    $dependencia = $model_proveedor->getProveedorActivo();
                }
                else
                {
                    $model_dependencia = new Dependencia;
                    $dependencia = $model_dependencia->getTodasDependencias();
                }
            }

            $model_concepto = new Concepto;
            $concepto = $model_concepto->getConcepto('S');

            $model_proveedor = new Proveedore;
            $proveedores = $model_proveedor->getProveedor();

            $model_tipo_proceso = new Tipo_proceso;
            $tipo_procesos = $model_tipo_proceso->getTipoProceso();

            $model_cg = new Clasificador_gasto;
            $cgs = $model_cg->getCg();
            
            //--------------------------------------------------------------------
            return view('frontend.stocks.editar_nota_entrada_almacen', compact('farmacia','id_farmacia', 'fecha_hace_treinta_dias', 'rango_registro', 'motivo','destinos','fechaServidor','pecosa','dependencia','concepto','proveedores','tipo_procesos','cgs','mostrar'));
        }
    }

    public function cargartipodocumento($tipo) {
        $model_tipo_documento = new Tipo_documento;
        $tipo_documento = $model_tipo_documento->getTipoDocumento($tipo);
        return $tipo_documento;
    }

    public function cargarnronota($id_farmacia) {
        $model_guias = new Guia_entrada();
        $nro_nota_entrada = $model_guias->getNroNota($id_farmacia);

        return $nro_nota_entrada;
    }

    public function cargarcg() {
        $model_cg = new Clasificador_gasto;
        $cg = $model_cg->getCg();
        return $cg;
    }
    
    public function cargartipoproceso() {
        $model_tipo_proceso = new Tipo_proceso;
        $tipo_proceso = $model_tipo_proceso->getTipoProceso();
        return $tipo_proceso;
    }
    

    public function edit_documento_transferencia($id) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $id_user = Auth::user()->id;
        $model_farmacia = new Farmacia;
        $hoy = date('Y-m-j');
        $farmacia = $model_farmacia->GetFarmaByUser($id_user);
        //----------------------------------------------------------------------
        $model_transferencias = new Transferencia();
        $transferencia = $model_transferencias->GetTransferenciaById($id);
        
        $flag = $model_farmacia->ExisteFarmaciaInternaorExterna($farmacia->id_establecimiento, $transferencia->id_farmacia_destino);
        
        $model_mottransferencia = new MotivoTransferencia;
        $motivo_transferencia = $model_mottransferencia->getMotivoTransferenciaActivo();
        //echo $flag;exit();
        $id_farmacia=$farmacia->id;
        return view('frontend.transferencias.editar_documento_transferencia', compact('farmacia', 'transferencia', 'flag', 'motivo_transferencia','id_farmacia'));
    }

    public function registrar_documento_nota_entrada(Request $request) {
        $model_guia = new Guia_entrada();

        
        $model_guia_entrada = new Guia_entrada;
        $model_guia_entrada->numero = $request->nota_entrada;
        $model_guia_entrada->ano_nota = $request->ano_actual;
        $model_guia_entrada->id_user = $request->id_user;
        $model_guia_entrada->id_farmacia = $request->id_farmacia;
        $model_guia_entrada->fecha_entrada = $request->fecha_entrada; 

        $model_guia_entrada->nota_entrada = $request->nota_entrada;  
        $model_guia_entrada->ano = $request->ano_actual;
        $model_guia_entrada->lugar_origen = $request->id_lugar;
        $model_guia_entrada->motivo = $request->motivo;
        $model_guia_entrada->tipo = $request->tipo;
        $model_guia_entrada->responsable = $request->responsable_destino;
        $model_guia_entrada->referencia = $request->referencia;
        
        $model_guia_entrada->estado = 1; //Pecosa registrada pero pendiente
        $model_guia_entrada->save();
        
        $id_nota_entrada = $model_guia_entrada->id;
        Session::flash('flash_message', 'Se registro el documento de la nota de entrada, continuar con el registro de los productos farmaceúticos a recepcionar!');
        return redirect('/recepcionar_productos_guia_entrada/' . $id_nota_entrada . '/' . $request->id_user);            

    }

    public function registrar_documento_nota_entrada_almacen(Request $request) {
        
        $model_guia = new Guia_entrada();

        $tipo = $request->tipo;
        $model_guia_entrada = new Guia_entrada;
        
        $model_guia_entrada->ano_nota = $request->ano_actual;        
        $model_guia_entrada->ano = $request->ano_actual;
        $model_guia_entrada->id_farmacia = $request->id_farmacia;
        $model_guia_entrada->id_user = $request->id_user;
        $model_guia_entrada->concepto = $request->concepto;
        $model_guia_entrada->lugar_origen = $request->id_lugar_origen;
        $model_guia_entrada->fecha_entrada = $request->fecha_entrada;

        if($request->concepto==1){
             
            $numero_guia=str_pad($request->nota_entrada_nro_guia, 7, "0", STR_PAD_LEFT);
            $siaf=str_pad($request->nro_exp_siaf, 10, "0", STR_PAD_LEFT);

            $year = date('Y');

            $contar_guia_entrada = Guia_entrada::where('nota_entrada', '=', $numero_guia)->where('ano_nota', '=', $year)->where('id_farmacia', '=', $request->id_farmacia)->where('estado','<>',0)->count();

            if($contar_guia_entrada>0){
                //$busca_guia_entrada = Guia_entrada::where('nota_entrada', '=', $numero_guia)->where('ano_nota', '=', $year)->where('id_farmacia', '=', $request->id_farmacia)->first();
				$busca_guia_entrada = Guia_entrada::where('nota_entrada', '=', $numero_guia)->where('ano_nota', '=', $year)->where('id_farmacia', '=', $request->id_farmacia)->where('estado','<>',0)->first();
            
                if($request->nro_entrega<=$busca_guia_entrada->total_entrega){

                    //$model_guia_entrada->numero = $numero_guia;
					//$model_guia_entrada->numero = $request->nota_entrada_nro_guia_1;
                    //$model_guia_entrada->nota_entrada = $numero_guia;  
					$model_guia_entrada->numero = $model_guia_entrada->getNroNota($request->id_farmacia);
                    $model_guia_entrada->nota_entrada = $numero_guia;
					
                    $model_guia_entrada->nro_exp_siaf = $siaf;    
                    $model_guia_entrada->proveedor_id = $busca_guia_entrada->proveedor_id;
                    if($request->factura)
                        $model_guia_entrada->factura = $request->factura;
                    $model_guia_entrada->nro_entrega = $request->nro_entrega;
					$model_guia_entrada->lugar_origen = $busca_guia_entrada->lugar_origen;
                    $model_guia_entrada->total_entrega = $busca_guia_entrada->total_entrega;
                    $model_guia_entrada->tipo_proceso = $busca_guia_entrada->tipo_proceso;
                    //$model_guia_entrada->nro_proceso = $request->nro_proceso;  
					$model_guia_entrada->nro_proceso = $busca_guia_entrada->nro_proceso;  
                    $model_guia_entrada->ff_rb = $busca_guia_entrada->ff_rb;  
                    $model_guia_entrada->c_g = $busca_guia_entrada->c_g;
                    //$model_guia_entrada->fecha_doc_recepcion = $request->fecha_destino;
					//$model_guia_entrada->fecha_doc_recepcion = $request->fecha_doc_recepcion;
					$model_guia_entrada->fecha_doc_recepcion = $request->fecha_doc_recepcion;
                    $model_guia_entrada->nro_contrato = $busca_guia_entrada->nro_contrato;
					$model_guia_entrada->referencia = $busca_guia_entrada->referencia; 
                }
                else
                {
                    Session::flash('flash_message', 'El numero de entrada no debe ser mayor al total de entrega!');
                    return redirect('/add_documento_nota_entrada/' . $request->id_user);
                }
            }
            else
            {
                //$busca_guia_entrada = Guia_entrada::where('nota_entrada', '=', $numero_guia)->where('ano_nota', '=', $year)->where('id_farmacia', '=', $request->id_farmacia)->get();
				$busca_guia_entrada = Guia_entrada::where('nota_entrada', '=', $numero_guia)->where('ano_nota', '=', $year)->where('id_farmacia', '=', $request->id_farmacia)->where('estado','<>',0)->get();
            
                if($request->nro_entrega<=$request->total_entrega){
                    //$model_guia_entrada->numero = $numero_guia;
					//$model_guia_entrada->numero = $request->nota_entrada_nro_guia_1;
					$model_guia_entrada->numero = $model_guia_entrada->getNroNota($request->id_farmacia);
                    $model_guia_entrada->nota_entrada = $numero_guia;  
                    $model_guia_entrada->nro_exp_siaf = $siaf;    
                    $model_guia_entrada->proveedor_id = $request->id_proveedor;
                    $model_guia_entrada->factura = $request->factura;
                    $model_guia_entrada->nro_entrega = $request->nro_entrega;
                    $model_guia_entrada->total_entrega = $request->total_entrega;
                    $model_guia_entrada->tipo_proceso = $request->tipo_proceso;
                    $model_guia_entrada->nro_proceso = $request->nro_proceso;  
                    $model_guia_entrada->ff_rb = $request->f_r;  
                    $model_guia_entrada->c_g = $request->c_g;
                    //$model_guia_entrada->fecha_doc_recepcion = $request->fecha_destino;
					//$model_guia_entrada->fecha_doc_recepcion = $request->fecha_doc_recepcion;
					$model_guia_entrada->fecha_doc_recepcion = $request->fecha_doc_recepcion;
                    $model_guia_entrada->nro_contrato = $request->nro_contrato;
					$model_guia_entrada->referencia = $request->referencia; 
                }
                else
                {
                    Session::flash('flash_message', 'El numero de entrada no debe ser mayor al total de entrega!');
                    return redirect('/add_documento_nota_entrada/' . $request->id_user);                                    
                }
            }
        }
        else
        {
            //$model_guia_entrada->numero = $request->nota_entrada_nro_guia;
			//$model_guia_entrada->numero = $request->nota_entrada_nro_guia_1;
            //$model_guia_entrada->nota_entrada = $request->nota_entrada_nro_guia;
			$model_guia_entrada->numero = $model_guia_entrada->getNroNota($request->id_farmacia);
            $model_guia_entrada->nota_entrada = $model_guia_entrada->getNroNotaConcepto($request->id_farmacia);
            $model_guia_entrada->referencia = $request->referencia; 
        }
        
        //$model_guia_entrada->referencia = $request->referencia; 
        $model_guia_entrada->estado = 1;
        $model_guia_entrada->save();
        
        $id_nota_entrada = $model_guia_entrada->id;
        Session::flash('flash_message', 'Se registro el documento de la nota de entrada, continuar con el registro de los productos farmaceúticos a recepcionar!');
        return redirect('/recepcionar_productos_guia_entrada/' . $id_nota_entrada . '/' . $request->id_user);        
    }

    public function recepcionar_productos_guia_entrada($id_pecosa, $id_user) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByUser($id_user);
        $nombre_farmacia = $farma->nombre;
        $id_farmacia = $farma->id;
        $almacen = $farma->almacen;
        
        $model_pecosa = new Guia_entrada();
        $dato_pecosa = $model_pecosa->GetPecosaByIdNotaEntrada($id_pecosa);

        $nivel = $model_farma->ObtenerNivelFarmacia($id_farmacia);
        $productos_recpecionados = $model_pecosa->ShowProductoByPecosa($id_pecosa);
        $x=count($productos_recpecionados); 
        
		if(count($dato_pecosa)>0){
			if($dato_pecosa->concepto==1  || $dato_pecosa->concepto==7 || $dato_pecosa->concepto==8){
                $contador = Guia_entrada::where('nota_entrada', '=',  $dato_pecosa->nota_entrada)->where('id_farmacia', '=', $id_farmacia)->count();  
                if(count($contador)>1){
                    $mostrar=2;
                }
                else
                {
                    $mostrar=1;    
                }
            }
            else
            {
                $mostrar=0;   
            }
        }
        else
        {
            $guia_entrada_producto="";
            $factura_producto="";
            $total_cantidad="";
            $mostrar=0;
        }
        
        $model_productos = new Producto;
        $productos = $model_productos->ProductosFarmaciasSegunNivelIncluidoNoPetitorio($nivel->nivel);
		
		
        if(Auth::user()->flagresponsablefarmacia==1){            
            $date = Carbon::now();
            $fecha = $date->format('d-m-Y');   
            $fechaIngreso = Carbon::parse($dato_pecosa->updated_at);
            $fechaActual = Carbon::parse($fecha);
            $tiempo = $fechaActual->diffInDays($fechaIngreso);
        }
        else{
            $tiempo=31;
        }
		
		
		if($almacen==1):
            return view('frontend.stocks.recepcion_productos_nota_entrada_almacen', compact('dato_pecosa', 'productos_recpecionados', 'productos', 'nombre_farmacia','mostrar','almacen','tiempo'));
        else:
            return view('frontend.stocks.recepcion_productos_nota_entrada', compact('dato_pecosa', 'productos_recpecionados', 'productos', 'nombre_farmacia','almacen','id_farmacia','tiempo'));
        endif;
		
    }

    public function registrar_productos_($id_transferencia, $id_farmacia) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $model_farmacia = new Farmacia;
        $model_transferencia = new Transferencia;
        //$model_lote = new \App\Models\Stock_lote();
        //$lotes = $model_lote->ShowLotesTransferirById_farmacia($id_farmacia);
        $model_kardex = new Kardexe();
        //$lotes = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmacia("",$id_farmacia);
        
        $tipo_transferencia = $model_transferencia->ObtenerTipoTransferencia($id_transferencia);
        $transferencia = $model_transferencia->GetTransferenciaById($id_transferencia);
        

            if ($tipo_transferencia->tipo == 1) {
                $destino = Farmacia::where('id', $transferencia->id_farmacia_destino)->select('nombre')->first();
            } else {
                if ($tipo_transferencia->tipo == 3) {
                    $destino = Establecimiento::where('id', $transferencia->id_farmacia_destino)->select('nombre')->first();
                } else {
                    $destino = Consultorio::where('id', $transferencia->id_consultorio)->select('nombre')->first();
                }
            }
        $farma = $model_farmacia->GetFarmaByIdfarma($transferencia->id_farmacia);
        $farmacia_origen = $farma->nombre;
        $almacen = $farma->almacen;
        //$productos = $model_transferencia->ShowLotesTransferidosById_transferencia($id_transferencia);
        $productos = $model_transferencia->ShowLotesTransferidosById_transferencia_nuevo($id_transferencia);
        
        if($almacen==1){
            $lotes = $model_kardex->GetKardexProductoByIdFarmaciaExterna($id_farmacia,$transferencia->id_farmacia_destino );
        }else{
            $lotes = $model_kardex->GetKardexProductoByIdFarmacia($id_farmacia);
        }
        
        return view('frontend.transferencias.registrar_productos_transferir', compact('lotes', 'transferencia', 'farmacia_origen', 'productos', 'destino','almacen'));
    }

    public function add_producto_nota_entrada(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $producto = Producto::where('id', $request->id_producto)->first();
        $existe = Carga_pecosa::where('id_guia_entrada', $request->id_guia_entrada)
                        ->where('id_producto', $request->id_producto)->where('estado', 1)->where('lote', $request->lote)->get();
        if (count($existe) > 0) {
            Session::flash('flash_message', 'El Producto ' . $producto->nombre . ' de lote ' . $request->lote . ' ya esta en la lista, solo edite la cantidad de ser necesario');
            return redirect('/recepcionar_productos_guia_entrada/' . $request->id_pecosa . '/' . $request->id_user);
        } else {

            $insert = new Carga_pecosa;
            $insert->id_farmacia = $request->id_farmacia;
            $insert->id_producto = $request->id_producto;
            $insert->registro_sanitario = $request->registro_sanitario;
            $insert->lote = $request->lote;
            $insert->fecha_vencimiento = $request->fecha_vencimiento;
            $insert->fecha_carga = $fecha;
            if($producto->id==11045 || $producto->id==15938 || $producto->id==14607 || $producto->id==14608):  //Gas
                $insert->cantidad = $request->cantidad*1000; //conversion a Litros
            else:
                $insert->cantidad = $request->cantidad;
            endif;
            $insert->id_guia_entrada = $request->id_guia_entrada;
            $insert->precio_promedio = $request->precio; 
			if(isset($request->presentacion_comercial)&&$request->presentacion_comercial!='') 
                $insert->presentacion_comercial = $request->presentacion_comercial;
            $insert->estado = 1; //1:Carga pendiente, 2:Entrada finalizada, 0:eliminado
			/*
            if($request->almacen==1){
                $insert->factura = $request->factura; 
                $insert->guia_entrada = $request->nro_guia; 
            }
            */
			if($request->almacen==1){
				$insert->factura = $request->factura; 
				$insert->guia_entrada = $request->nro_guia; 
				//$insert->total_cantidad = $request->total_cantidad;
				//$insert->precio_compra = $request->precio;
				//$insert->marca = $request->marca;
				$insert->total_cantidad = $request->total_cantidad;
				$insert->precio_compra = $request->precio;
				$insert->marca = $request->marca;
			}
			
            $insert->save();     
            $id_pecosa_insert=$insert->id;  


            if($request->guardar_nota_directo==1){

                $model_carga_pecosa = new Carga_pecosa;
                $result_pecosa = $model_carga_pecosa->GetCargaPecosaProducto($request->id_pecosa, $request->id_farmacia,$request->id_producto );   
                
                $cuenta_kardex = new Kardexe;
                $cuenta_kardex = Kardexe::where('id_nro_movimiento', '=', $request->id_guia_entrada)->where('id_farmacia', '=', $request->id_farmacia)->where('numero_movimiento', '=', $request->numero_movimiento)->where('id_producto', '=', $request->id_producto)->count();

                if($cuenta_kardex==0){
                    $kardex = new Kardexe;                
                    $kardex->codigo_producto = $result_pecosa[0]->codigo_producto;
                    $kardex->nombre_producto = $result_pecosa[0]->nombre_producto;
                    $kardex->tipo_movimiento = "IR";
                    $kardex->numero_movimiento = $result_pecosa[0]->nro_pecosa;
                    $kardex->origen = 'ALMACEN';                    
                    $kardex->destino = $result_pecosa[0]->nombre_farmacia;
                    $kardex->estado = 1;
                    $kardex->cantidad = $result_pecosa[0]->cantidadtot_producto;
                    $kardex->nombre_farmacia = $result_pecosa[0]->nombre_farmacia;
                    $kardex->fecha_movimiento = $result_pecosa[0]->fechaentrada_pecosa;
                    $kardex->id_tipo_movimiento = 2;                    
                    $kardex->id_producto = $result_pecosa[0]->id_producto;      
                    $kardex->id_nro_movimiento = $request->id_pecosa;
                    $kardex->id_origen = 4;
                    $kardex->id_destino = $request->id_farmacia;
                    $kardex->id_farmacia =  $request->id_farmacia;
                    $kardex->id_user_created =  $request->id_user;
                    $kardex->save();                
                    $idkardex=$kardex->id;
                }
                else
                {                    
                    
                    $busca_kardex = Kardexe::where('id_nro_movimiento', '=', $request->id_guia_entrada)->where('id_farmacia', '=', $request->id_farmacia)->where('numero_movimiento', '=', $request->numero_movimiento)->where('id_producto', '=', $request->id_producto)->first();
                    
                    $busca_kardex->cantidad = $result_pecosa[0]->cantidadtot_producto;
                    $busca_kardex->save();
                    $idkardex=$busca_kardex->id;
                }
  
                //Crear kardex lotes
                $kardex_lote = new \App\Models\Kardex_lote;
                $kardex_lote->id_kardex = $idkardex;
                $kardex_lote->lote = $request->lote;
                $kardex_lote->fecha_vencimiento = $request->fecha_vencimiento;
                $kardex_lote->registro_sanitario = $request->registro_sanitario;
                $kardex_lote->cantidad = $request->cantidad;
                $kardex_lote->fecha_registro  = $fecha;
                $kardex_lote->precio_unitario = $request->precio;
                if(isset($request->presentacion_comercial)&&$request->presentacion_comercial!='') 
                    $kardex_lote->presentacion_comercial = $request->presentacion_comercial;
                $kardex_lote->id_ingreso_salida = $id_pecosa_insert;
                $kardex_lote->save();
                
            }   
            Session::flash('flash_message', 'Producto agregado a la lista');
                return redirect('/recepcionar_productos_guia_entrada/' . $request->id_pecosa . '/' . $request->id_user);         
        }
    }

	    public function insert_producto_nota_entrada(Request $request) { 
        
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;         
        $model_receta_vale = new \App\Models\Receta_vale;
        $id_pecosas = $request->id_guia_entrada; //Procedimiento
        $id_usuario = $request->id_user;

        $id_farmacia = $request->id_farmacia;
        $ano_nota = $model_receta_vale->getAnoServidor(); //Wilian
        $nota_entrada = $request->nota_entrada_actu;
        $model_kardex = new Kardexe;

           
        
            $model_carga_pecosa = new \App\Models\Carga_pecosa;
            $result_pecosas = $model_carga_pecosa->GetCargaPecosa($id_pecosas, $id_farmacia);   
            if(count($result_pecosas)>0){
                $date = Carbon::now();
                $fecha = $date->format('d-m-Y');   
                $fechaActual = Carbon::parse($fecha);                   
                foreach ($result_pecosas as $key => $result_pecosa) {
                        $kardex = new \App\Models\Kardexe;
                        $kardex->codigo_producto = $result_pecosa->codigo_producto;
                        $kardex->nombre_producto = $result_pecosa->nombre_producto;
                        $kardex->tipo_movimiento = "IR";
                        $kardex->numero_movimiento = $result_pecosa->nro_pecosa;
                        $kardex->origen = 'ALMACEN';                    
                        $kardex->destino = $result_pecosa->nombre_farmacia;
                        $kardex->estado = 1;
                        $kardex->cantidad = $result_pecosa->cantidadtot_producto;
                        $kardex->nombre_farmacia = $result_pecosa->nombre_farmacia;
                        $kardex->fecha_movimiento = $result_pecosa->fechaentrada_pecosa;
                        $kardex->id_tipo_movimiento = 2;                    
                        $kardex->id_producto = $result_pecosa->id_producto;                                                      
                        $kardex->id_nro_movimiento = $id_pecosas;
                        $kardex->id_origen = 4;
                        $kardex->id_destino = $id_farmacia;                    
                        $kardex->id_farmacia =  $id_farmacia;                        
                        $kardex->id_user_created =  $request->id_user;
                        $kardex->save();
                        $idkardex = $kardex->id;
                        $result_productos_pecosa = $model_kardex->GetProductoPecosa($id_pecosas,$result_pecosa->id_producto);
                        
                        foreach ($result_productos_pecosa as $key => $rowProductos) {
                            $kardex_lote = new \App\Models\Kardex_lote;
                            $kardex_lote->id_kardex = $idkardex;
                            $kardex_lote->lote = $rowProductos->lote;
                            if ($rowProductos->fecha_vencimiento != '0000-00-00 00:00:00' && $rowProductos->fecha_vencimiento != ''):
                                $kardex_lote->fecha_vencimiento = $rowProductos->fecha_vencimiento;
                            endif;
                            $kardex_lote->registro_sanitario = $rowProductos->registro_sanitario;
                            $kardex_lote->cantidad = $rowProductos->cantidad;
                            $kardex_lote->precio_unitario = $rowProductos->precio;
                            $kardex_lote->id_ingreso_salida = $rowProductos->id;
                            $kardex_lote->fecha_registro = $fechaActual;
                            $kardex_lote->presentacion_comercial = $rowProductos->presentacion_comercial;
                            $kardex_lote->save();
                        }
                } 
                $cad="Update guia_entradas Set estado=2 Where id=".$id_pecosas;
                $data = DB::update($cad);

                Session::flash('flash_message', 'Pecosa y Productos se ingresaron correctamente');
            }
        
        //insertar kardex actualizado
        return redirect('/recepcionar_productos_guia_entrada/' . $id_pecosas . '/' . $id_usuario);
    }

    public function actualizar_producto_nota_entrada(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $cad = "";
        $update = Carga_pecosa::where('id', '=', $request->id_carga_pecosa)->first();

        if ($update->lote != $request->lote):
            $cad = "LT(" . $update->lote . ")";
            $update->lote = $request->lote;
        endif;

        if ($update->registro_sanitario != $request->registro_sanitario):
            $cad = "RS(" . $update->registro_sanitario . ")";
            $update->registro_sanitario = $request->registro_sanitario;
        endif;

        if ($update->precio_promedio != $request->precio_promedio):
            $cad = "PU(" . $update->precio_promedio . ")";
            $update->precio_promedio = $request->precio_promedio;
        endif;

        if ($request->fecha_vencimiento != '0000-00-00 00:00:00' && $request->fecha_vencimiento != ''):
            $update->fecha_vencimiento = date("d-m-Y", strtotime($update->fecha_vencimiento));
            if ($update->fecha_vencimiento != $request->fecha_vencimiento):
                $cad = "FV(" . $update->fecha_vencimiento . ")";
                $update->fecha_vencimiento = $request->fecha_vencimiento;
            endif;
        endif;
        
        if($update->id_producto==11045 || $update->id_producto==15938 || $update->id_producto==14607 || $update->id_producto==14608):  //Gas
            $update->cantidad = $request->cantidad*1000; //conversion a Litros
        else:
            $update->cantidad = $request->cantidad;
        endif;

        if(isset($request->presentacion_comercial))
            $update->presentacion_comercial = $request->presentacion_comercial;
        $update->observacion = $request->observacion;
	if($request->almacen==1){            
          if($request->concepto==1 || $request->concepto==7 || $request->concepto==8){
            $update->factura = $request->factura;
            $update->guia_entrada = $request->nro_guia;
            if($request->concepto==1 ){
                $update->total_cantidad = $request->total_cantidad;
            }            
            $update->precio_compra = $request->precio_promedio;
            $update->marca = $request->marca;            
          }
        }
		
        if($request->concepto==1){
            $update->factura = $request->factura;
            $update->guia_entrada = $request->nro_guia;
            $update->total_cantidad = $request->total_cantidad;
            $update->precio_compra = $request->precio_promedio;
            $update->marca = $request->marca;
        }

		
        $update->save();
        Session::flash('flash_message', 'Se actualizó correctamente los datos del producto registrado');
        return redirect('/recepcionar_productos_guia_entrada/' . $request->id_guia_entrada . '/' . $request->id_user);
    }
	
    public function actualizar_producto_nota_entrada_directo(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $cad = "";
        $update = Carga_pecosa::where('id', '=', $request->id_carga_pecosa)->first();

        if ($update->lote != $request->lote):
            $cad = "LT(" . $update->lote . ")";
            $update->lote = $request->lote;
        endif;

        if ($update->registro_sanitario != $request->registro_sanitario):
            $cad = "RS(" . $update->registro_sanitario . ")";
            $update->registro_sanitario = $request->registro_sanitario;
        endif;

        if ($update->precio_promedio != $request->precio_promedio):
            $cad = "PU(" . $update->precio_promedio . ")";
            $update->precio_promedio = $request->precio_promedio;
        endif;

        if ($request->fecha_vencimiento != '0000-00-00 00:00:00' && $request->fecha_vencimiento != ''):
            $update->fecha_vencimiento = date("d-m-Y", strtotime($update->fecha_vencimiento));
            if ($update->fecha_vencimiento != $request->fecha_vencimiento):
                $cad = "FV(" . $update->fecha_vencimiento . ")";
                $update->fecha_vencimiento = $request->fecha_vencimiento;
            endif;
        endif;        
       
        if($update->id_producto==11045 || $update->id_producto==15938 || $update->id_producto==14607 || $update->id_producto==14608):  //Gas
            $update->cantidad = $request->cantidad*1000; //conversion a Litros
        else:
            $update->cantidad = $request->cantidad;
        endif;
        $cantidad_kardex = $update->cantidad;
        if($request->concepto==1 || $request->concepto==7 || $request->concepto==8){
            $update->factura = $request->factura;
            $update->guia_entrada = $request->nro_guia;
            if($request->concepto==1 ){
                $update->total_cantidad = $request->total_cantidad;
            }            
            $update->precio_compra = $request->precio_promedio;
            $update->marca = $request->marca;
        }

        if($request->presentacion==1):
            $presentacion_comercial = $request->presentacion_comercial;
            $update->presentacion_comercial = $request->presentacion_comercial;
        else:
            $presentacion_comercial = 1;
            $update->presentacion_comercial = 1;
        endif;

        $update->observacion = $request->observacion;
        $update->save();

        $cad1 = "update kardex_lotes set cantidad=".$cantidad_kardex." , fecha_vencimiento='".$request->fecha_vencimiento."' , precio_unitario='".$request->precio_promedio."' , registro_sanitario='".$request->registro_sanitario."' , lote='".$request->lote."', presentacion_comercial=".$presentacion_comercial."  where id_ingreso_salida=". $request->id_carga_pecosa ." and id_kardex in (select id from kardexes where tipo_movimiento = 'IR' and id_nro_movimiento=".$update->id_guia_entrada." and id_farmacia=".$request->id_farmacia." and estado=1 and id_producto=".$request->id_producto." )";
        $data1 = DB::update($cad1);    
       
        $cad = "select sum(cantidad) total from kardex_lotes where estado=1 and id_kardex in (select id from kardexes where tipo_movimiento = 'IR' and id_nro_movimiento=".$update->id_guia_entrada." and id_farmacia=".$request->id_farmacia." and estado=1 and id_producto=".$request->id_producto.")" ;
            $data = DB::select($cad);

        $cad3 = "update kardexes set cantidad=".$data[0]->total." where  tipo_movimiento = 'IR' and id_nro_movimiento=".$update->id_guia_entrada." and id_farmacia=".$request->id_farmacia." and estado=1 and id_producto=".$request->id_producto;
            $data3 = DB::update($cad3);    

        Session::flash('flash_message', 'Se actualizó correctamente los datos del producto registrado');
        return redirect('/recepcionar_productos_guia_entrada/' . $request->id_guia_entrada . '/' . $request->id_user);
    }
	
	/*
    public function eliminar_producto_nota_entrada($id_carga_pecosa, $id_guia_entrada, $id_user) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $id_farmacia=Auth::user()->id_farmacia;

        $update = Carga_pecosa::where('id', '=', $id_carga_pecosa)->first();
        $update->estado = 0;  
		$update->save();

        $update_contador = Kardexe::where('id_nro_movimiento', '=', $id_guia_entrada)->where('id_farmacia', '=', $id_farmacia)->count();  
        
        if($update_contador>0){
            $update_kardexes = Kardexe::where('id_nro_movimiento', '=', $id_guia_entrada)->where('id_farmacia', '=', $id_farmacia)->first();
            $update_kardex_lote_contador = Kardex_lote::where('id_kardex', '=', $update_kardexes->id)->where('estado', '=', 1)->count();

            if($update_kardex_lote_contador==1){
                $update_kardexes->estado = 0;  
                $update_kardexes->save();

                $update_kardex_lote = Kardex_lote::where('id_kardex', '=', $update_kardexes->id)->where('estado', '=',1)->first();
                $update_kardex_lote->estado = 0;  
                $update_kardex_lote->save();
            }
            else
            {
                $update_kardex_lote = Kardex_lote::where('id_kardex', '=', $update_kardexes->id)->where('id_ingreso_salida', '=', $id_carga_pecosa)->first();
                $update_kardex_lote->estado = 0; 
                $update_kardex_lote->save();
            }
        }
        
        Session::flash('flash_message', 'Se Elimino el registro');
        return redirect('/recepcionar_productos_guia_entrada/' . $id_guia_entrada . '/' . $id_user);
    }
	*/

	public function eliminar_producto_nota_entrada($id_carga_pecosa, $id_guia_entrada, $id_user) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $id_farmacia=Auth::user()->id_farmacia;

        $update = Carga_pecosa::where('id', '=', $id_carga_pecosa)->first();
        $update->estado = 0;  
        $update->save();        
        
        Session::flash('flash_message', 'Se Elimino el registro');
        return redirect('/recepcionar_productos_guia_entrada/' . $id_guia_entrada . '/' . $id_user);
    }

    public function eliminar_producto_nota_entrada_actualizar($id_carga_pecosa, $id_guia_entrada, $id_user, $id_producto) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
       
        $id_farmacia=Auth::user()->id_farmacia;

        $update = Carga_pecosa::where('id', '=', $id_carga_pecosa)->first();
        $update->estado = 0;  
        $update->save();

        $cad1 = "update kardex_lotes set estado=0 where id_ingreso_salida=". $id_carga_pecosa ." and id_kardex in (select id from kardexes where tipo_movimiento = 'IR' and id_nro_movimiento=".$id_guia_entrada." and id_farmacia=".$id_farmacia." and estado=1 and id_producto=".$id_producto." )";
        $data1 = DB::update($cad1);

        $cad = "select count(*) total from kardex_lotes where  id_kardex in (select id from kardexes where tipo_movimiento = 'IR' and id_nro_movimiento=".$id_guia_entrada." and id_farmacia=".$id_farmacia." and estado=1 and id_producto=".$id_producto." )";
            $data = DB::select($cad);
       
        $cad2 = "select count(*) total_estado from kardex_lotes where estado=0 and id_kardex in (select id from kardexes where tipo_movimiento = 'IR' and id_nro_movimiento=".$id_guia_entrada." and id_farmacia=".$id_farmacia." and estado=1 and id_producto=".$id_producto." )";
        $data2 = DB::select($cad2);

        if($data[0]->total==$data2[0]->total_estado){
            $cad3 = "update kardexes set estado=0 where id in (select id from kardexes where tipo_movimiento = 'IR' and id_nro_movimiento=".$id_guia_entrada." and id_farmacia=".$id_farmacia." and estado=1 and id_producto=".$id_producto." )";
            $data3 = DB::update($cad3);    
        }
        else
        {
            $cad4 = "select sum(cantidad) total from kardex_lotes where estado=1 and id_kardex in (select id from kardexes where tipo_movimiento = 'IR' and id_nro_movimiento=".$id_guia_entrada." and id_farmacia=".$id_farmacia." and estado=1 and id_producto=".$id_producto." )";
            $data4 = DB::select($cad4);

            $cad5 = "update kardexes set cantidad=".$data4[0]->total." where id in (select id from kardexes where tipo_movimiento = 'IR' and id_nro_movimiento=".$id_guia_entrada." and id_farmacia=".$id_farmacia." and estado=1 and id_producto=".$id_producto." )";
                $data5 = DB::update($cad5);

            $contar = "select count(*) contar from kardex_lotes where estado=1 and id_kardex in (select id from kardexes where tipo_movimiento = 'IR' and id_nro_movimiento=".$id_guia_entrada." and id_farmacia=".$id_farmacia." and estado=1 and id_producto=".$id_producto." )";
                $data5 = DB::select($contar);  

            if($contar>0){
                $cad5 = "update kardexes set cantidad=".$data4[0]->total." where id in (select id from kardexes where tipo_movimiento = 'IR' and id_nro_movimiento=".$id_guia_entrada." and id_farmacia=".$id_farmacia." and estado=1 and id_producto=".$id_producto." )";
                $data5 = DB::update($cad5);    
            }  
        }        
       
        Session::flash('flash_message', 'Se elimino el registro');
        return redirect('/recepcionar_productos_guia_entrada/' . $id_guia_entrada . '/' . $id_user);
    }


    //public function buscar_nota_entrada($nro_nota_entrada) {
	//public function buscar_nota_entrada($nro_nota_entrada,$id_farmacia) {
	public function buscar_nota_entrada($nro_nota_entrada,$id_farmacia,$concepto) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $model_guiaentrada = new Guia_entrada;
        //$guia = $model_guiaentrada->ExisteNumeroEntrada($nro_nota_entrada);
		//$guia = $model_guiaentrada->ExisteNumeroEntrada($nro_nota_entrada, $id_farmacia);
		$guia = $model_guiaentrada->ExisteNumeroEntrada($nro_nota_entrada, $id_farmacia, $concepto);
        
        return $guia;
    }

    //public function evitar_duplicados($nro_doc, $tipo,$id_farmacia) {
	public function evitar_duplicados($nro_doc, $tipo,$id_farmacia,$concepto) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        if($tipo==1){
            $model_guiaentrada = new Guia_entrada;
            //$guia = $model_guiaentrada->ExisteNumeroEntrada($nro_doc, $id_farmacia);
			$guia = $model_guiaentrada->ExisteNumeroEntrada($nro_doc, $id_farmacia, $concepto);
        }
        else
        {
            $model_guiaentrada = new Guia_entrada;
            $guia = $model_guiaentrada->ExisteSiaf($nro_doc, $id_farmacia);
        }
        
        return $guia;
    }

    public function evitar_duplicados_edicion($nro_doc, $tipo,$id_farmacia, $id) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        if($tipo==1){
            $model_guiaentrada = new Guia_entrada;
            $guia = $model_guiaentrada->ExisteNumeroEntrada($nro_doc, $id_farmacia, $id);
        }
        else
        {
            $model_guiaentrada = new Guia_entrada;
            $guia = $model_guiaentrada->ExisteSiaf($nro_doc, $id_farmacia, $id);
        }
        
        return $guia;
    }

    
    public function editar_documento_nota_entrada(Request $request) {
        $model_guia = new Guia_entrada();

        $model_guia_entrada = Guia_entrada::where('id', '=', $request->id)->first();
        $model_guia_entrada->numero = $request->nota_entrada;
        $model_guia_entrada->ano_nota = $request->ano_actual;
        $model_guia_entrada->id_user = $request->id_user;
        $model_guia_entrada->id_farmacia = $request->id_farmacia;
        $model_guia_entrada->fecha_entrada = $request->fecha_entrada;       
        $model_guia_entrada->nota_entrada = $request->nota_entrada;  
        $model_guia_entrada->ano = $request->ano_actual;
        $model_guia_entrada->lugar_origen = $request->id_lugar;
        $model_guia_entrada->motivo = $request->motivo;
        $model_guia_entrada->tipo = $request->tipo;  
        $model_guia_entrada->responsable = $request->responsable_destino;          
        $model_guia_entrada->referencia = $request->referencia;  
        //$model_guia_entrada->estado = 1; //Pecosa registrada pero pendiente
        $model_guia_entrada->save();
        
        $id_nota_entrada = $model_guia_entrada->id;
        
        Session::flash('flash_message', 'Se actualizo correctamente el documento de registro!');
        return redirect('/edit_nota_entrada/' . $id_nota_entrada . '/' . $request->id_user);
    
        
    }

    public function editar_documento_nota_entrada_almacen(Request $request) {
        
        $model_guia = new Guia_entrada();
        $model_guia_entrada = Guia_entrada::where('id', '=', $request->id)->first();        
        $model_guia_entrada->id_user = $request->id_user;        
        //$model_guia_entrada->lugar_origen = $request->id_lugar_origen;
        $model_guia_entrada->fecha_entrada = $request->fecha_entrada;
        
        if($request->concepto==1){

            if(isset($request->nro_exp_siaf)){  
                $numero_guia=str_pad($request->nota_entrada_nro_guia, 7, "0", STR_PAD_LEFT);
                $siaf=str_pad($request->nro_exp_siaf, 10, "0", STR_PAD_LEFT);
            }
            $year = date('Y');            
            if($request->mostrar==0){ //primera vez
                $busca_guia_entrada = Guia_entrada::where('nota_entrada', '=', $numero_guia)->where('ano_nota', '=', $year)->where('id_farmacia', '=', $request->id_farmacia)->first();

                $model_guiaentrada = new Guia_entrada;
                //$nro_repetido = $model_guiaentrada->ExisteNumeroEntrada($numero_guia,$request->id_farmacia);
				$nro_repetido = $model_guiaentrada->ExisteNumeroEntrada($numero_guia,$request->id_farmacia,1);

                if(count($nro_repetido)>1){
                    $mostrar=1;  
                    $total=$busca_guia_entrada->total_entrega;     
                } 
                else{                    
                    $mostrar=0;
                    $total=$request->total_entrega;
                }

                if($request->nro_entrega<=$total){                    
                    $model_guia_entrada->numero = $numero_guia;
                    $model_guia_entrada->nota_entrada = $numero_guia;  
                    $model_guia_entrada->nro_exp_siaf = $siaf;    

                    if($mostrar==1){
                        $model_guia_entrada->proveedor_id = $busca_guia_entrada->proveedor_id;
                        $model_guia_entrada->nro_entrega = $busca_guia_entrada->nro_entrega;
                        $model_guia_entrada->total_entrega = $busca_guia_entrada->total_entrega;
                        $model_guia_entrada->tipo_proceso = $busca_guia_entrada->tipo_proceso;
                        $model_guia_entrada->nro_proceso = $busca_guia_entrada->nro_proceso;  
                        $model_guia_entrada->ff_rb = $busca_guia_entrada->ff_rb;  
                        $model_guia_entrada->c_g = $busca_guia_entrada->c_g;
                        //$model_guia_entrada->fecha_doc_recepcion = $request->fecha_destino;
						$model_guia_entrada->fecha_doc_recepcion = $request->fecha_doc_recepcion;
                        $model_guia_entrada->nro_contrato = $busca_guia_entrada->nro_contrato;
						$model_guia_entrada->lugar_origen = $busca_guia_entrada->id_lugar_origen;
                        $model_guia_entrada->referencia = $busca_guia_entrada->referencia; 
                    }
                    else
                    {
                        $model_guia_entrada->proveedor_id = $request->id_proveedor;
                        $model_guia_entrada->nro_entrega = $request->nro_entrega;
                        $model_guia_entrada->total_entrega = $request->total_entrega;
                        $model_guia_entrada->tipo_proceso = $request->tipo_proceso;
                        $model_guia_entrada->nro_proceso = $request->nro_proceso;  
                        $model_guia_entrada->ff_rb = $request->f_r;  
                        $model_guia_entrada->c_g = $request->c_g;
                        //$model_guia_entrada->fecha_doc_recepcion = $request->fecha_destino;
						$model_guia_entrada->fecha_doc_recepcion = $request->fecha_doc_recepcion;
                        $model_guia_entrada->nro_contrato = $request->nro_contrato;
						$model_guia_entrada->referencia = $request->referencia; 
                        $model_guia_entrada->lugar_origen = $request->id_lugar_origen;
                    }
                    
                }
                else
                {
                    Session::flash('flash_message', 'El numero de entrada no debe ser mayor al total de entrega!');
                    return redirect('/edit_nota_entrada/' . $request->id . '/' . $request->id_user);            
                }                
            }
            else
            {
                //$model_guia_entrada->referencia = $request->referencia; 
                //$model_guia_entrada->nro_entrega = $request->nro_entrega;
				$model_guia_entrada->nro_entrega = $request->nro_entrega;
            }
        }
        else
        {
			/*
            if($request->concepto==7):
                $model_guia_entrada->numero = $request->nota_entrada_nro_guia;
                $model_guia_entrada->nota_entrada = $request->nota_entrada_nro_guia;
            endif;            
            $model_guia_entrada->fecha_doc_recepcion = $request->fecha_destino;
			*/
			$model_guia_entrada->referencia = $request->referencia; 
            $model_guia_entrada->lugar_origen = $request->id_lugar_origen; 
        }        
        
        $model_guia_entrada->fecha_entrada = $request->fecha_entrada;
        //$model_guia_entrada->estado = 1;
        $model_guia_entrada->save();
        
        Session::flash('flash_message', 'Se actualizo correctamente el documento de registro');
        return redirect('/edit_nota_entrada/' . $request->id . '/' . $request->id_user);
    }
	/*
    public function eliminar($id){
        $update=Guia_entrada::where('id','=',$id)->first();
        $update->estado=0;
        $update->save();

        $cuenta=Carga_pecosa::where('id_guia_entrada','=',$id)->count();
        if($cuenta>0){
            $update_pecosa=Carga_pecosa::where('id_guia_entrada','=',$id)->first();
            $update_pecosa->estado=0;
            $update_pecosa->save();

            $cuenta2=Kardexe::where('id_nro_movimiento','=',$id)->count();
            if($cuenta2>0){
                $update_kardexe=Kardexe::where('id_nro_movimiento','=',$id)->first();
                $update_kardexe->estado=0;
                $update_kardexe->save();
                $idkardex=$update_kardexe->id;

                $cuenta3=Kardex_lote::where('id_kardex','=',$idkardex)->count();
                if($cuenta3>0){
                    $update_kardex_lote=Kardex_lote::where('id_kardex','=',$idkardex)->first();
                    $update_kardex_lote->estado=0;
                    $update_kardex_lote->save();    
                }
            }
        }

        Session::flash('flash_message', 'Se elimino correctamente');
    }
	*/
	
	public function eliminar($id){
		$update=Guia_entrada::where('id','=',$id)->first();
		$update->estado=0;
		$update->save();    
	}
	
    public function eliminar_almacen($id){
        $update=Guia_entrada::where('id','=',$id)->first();
        $update->estado=0;
        $update->save();

        $cuenta=Carga_pecosa::where('id_guia_entrada','=',$id)->count();
        if($cuenta>0){
            $update_pecosa=Carga_pecosa::where('id_guia_entrada','=',$id)->first();
            $update_pecosa->estado=0;
            $update_pecosa->save();

            $cuenta2=Kardexe::where('id_nro_movimiento','=',$id)->count();
            if($cuenta2>0){
                $update_kardexe=Kardexe::where('id_nro_movimiento','=',$id)->first();
                $update_kardexe->estado=0;
                $update_kardexe->save();
                $idkardex=$update_kardexe->id;

                $cuenta3=Kardex_lote::where('id_kardex','=',$idkardex)->count();
                if($cuenta3>0){
                    $update_kardex_lote=Kardex_lote::where('id_kardex','=',$idkardex)->first();
                    $update_kardex_lote->estado=0;
                    $update_kardex_lote->save();    
                }
            }
        }

        Session::flash('flash_message', 'Se elimino correctamente');
    }

    public function all_orden_ingresadas($id_user = 0) {

        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;

        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1)
            ;$ruta = $ruta[0];
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

        $id_user = Auth::user()->id;

        $num_doc = "";
        $num_siaf = "";
        $reg_sanitario = "";
        $lote = "";
        $id_tipo_proceso="";
		$idconcepto="";
        $idregistrador="";
        $id_producto="";
        $id_tipo_producto="";
        $id_proveedor="";
        $l_origen = "";

        $date = Carbon::now();
        $fechaDesde = $date->format('Y-m-d');
        $fechaHasta = $date->format('Y-m-d');

        //==================== 
        $model_farma = new Farmacia();
        $farmacia = $model_farma->GetFarmaByUser($id_user);
        $id_farmacia = $farmacia->id;
        $modo_validacion = $farmacia->modo_validacion;
        //====================
        $model_guias = new Guia_entrada();
        
		
		$model_concepto = new Concepto();
        $conceptos = $model_concepto->getConcepto('S');
		
        //$registrador = $model_guias->AllRegistradores($id_farmacia);

        $model_producto = new Producto();
        $nivel = $model_farma->ObtenerNivelFarmacia($id_farmacia);
        $productosnivel = $model_producto->ProductosFarmaciasSegunNivelIncluidoNoPetitorio($nivel->nivel);

        $model_proveedor = new Proveedore;
        $proveedores = $model_proveedor->getProveedor();

        $model_dependencia = new Dependencia;
        $dependencias = $model_dependencia->getDependencia('S');

        $model_tipo_producto = new Tipo_producto();
        $tipo_productos = $model_tipo_producto->ShowAll();

        $model_tipo_proceso = new Tipo_proceso;
        $tipo_procesos = $model_tipo_proceso->getTipoProceso();
        //====================
        $fechaDesde = $date->format('d-m-Y');
        $fechaHasta = $date->format('d-m-Y');

        //return view('frontend.stocks.todas_ordenes_ingresadas', compact('ordenes_compra', 'farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'tipo_productos', 'registrador','productosnivel','proveedores','dependencias','lote','id_tipo_proceso','num_siaf','num_doc','tipo_procesos','reg_sanitario','l_origen','id_user','idregistrador','id_tipo_proceso','id_producto','id_tipo_producto','id_proveedor'));
		if($farmacia->almacen==1){
            $ordenes_compra = $model_guias->ShowAllIngresos($id_farmacia, $fechaDesde, $fechaHasta);
            return view('frontend.stocks.todas_ordenes_ingresadas', compact('ordenes_compra', 'farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'tipo_productos', 'conceptos','productosnivel','proveedores','dependencias','lote','id_tipo_proceso','num_siaf','num_doc','tipo_procesos','reg_sanitario','l_origen','id_user','idconcepto','id_tipo_proceso','id_producto','id_tipo_producto','id_proveedor'));
        }
        else
        {
            $ordenes_compra = $model_guias->ShowAllIngresosFarmacia($id_farmacia, $fechaDesde, $fechaHasta);
            return view('frontend.stocks.todos_ingresos_farmacia', compact('ordenes_compra', 'farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'tipo_productos', 'conceptos','productosnivel','proveedores','dependencias','lote','id_tipo_proceso','num_siaf','num_doc','tipo_procesos','reg_sanitario','l_origen','id_user','idconcepto','id_tipo_proceso','id_producto','id_tipo_producto','id_proveedor'));
        }
        
    }

    public function buscar_orden_internamiento(Request $request) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        

        if (!$request->id_user):
            return redirect('/all_orden_ingresadas');
        endif;

        $modelfarma = new Farmacia;
        $farmacia = $modelfarma->GetFarmaByUser($request->id_user);
        $id_farmacia = $farmacia->id;
        $modo_validacion = $farmacia->modo_validacion;
        
        //----------------------------------------------------------------------
        $usuario = $request->id_user;
        $date = Carbon::now();        
        
        $fechaDesde = date("Y-m-d", strtotime($request->fechaDesde));
        $fechaHasta = date("Y-m-d", strtotime($request->fechaHasta));

        
        if ($request->num_doc):
            $num_doc=str_pad($request->num_doc, 7, "0", STR_PAD_LEFT);
        else:
            $num_doc=$request->num_doc;
        endif;

        if ($request->num_siaf):
            $num_siaf=str_pad($request->num_siaf, 10, "0", STR_PAD_LEFT);
        else:
            $num_siaf=$request->num_siaf;
        endif;        
        
        $reg_sanitario = $request->reg_sanitario;
        $lote= $request->lote;
		$idconcepto = $request->idconcepto;
        //$idregistrador = $request->idregistrador;
        $id_producto = $request->id_producto; 
        $id_tipo_producto = $request->id_tipo_producto; 
        $id_tipo_proceso = $request->id_tipo_proceso; 
        $id_proveedor = $request->id_proveedor; 
        $l_origen = $request->l_origen;
        
        //==================== 
        $model_farma = new Farmacia();
        $farmacia = $model_farma->GetFarmaByUser($request->id_user);
        $id_farmacia = $farmacia->id;
        $modo_validacion = $farmacia->modo_validacion;
        //====================
        $model_guias = new Guia_entrada();        
        //$registrador = $model_guias->AllRegistradores($id_farmacia);
		$model_concepto = new Concepto();
        $conceptos = $model_concepto->getConcepto('S');
		
        $model_producto = new Producto();
        $nivel = $model_farma->ObtenerNivelFarmacia($id_farmacia);
        $productosnivel = $model_producto->ProductosFarmaciasSegunNivel($nivel->nivel);

        $model_proveedor = new Proveedore;
        $proveedores = $model_proveedor->getProveedor();

        $model_dependencia = new Dependencia;
        $dependencias = $model_dependencia->getDependencia('S');

        $model_tipo_producto = new Tipo_producto();
        $tipo_productos = $model_tipo_producto->ShowAll();

        $model_tipo_proceso = new Tipo_proceso;
        $tipo_procesos = $model_tipo_proceso->getTipoProceso();
        
        $fechaDesde = date("d-m-Y", strtotime($fechaDesde));
        $fechaHasta = date("d-m-Y", strtotime($fechaHasta));

        if ($request->num_doc):
            $num_doc=ltrim($request->num_doc,"0");
        else:
            $num_doc=$request->num_doc;
        endif;

        if ($request->num_siaf):
            $num_siaf=ltrim($request->num_siaf,"0");
        else:
            $num_siaf=$request->num_siaf;
        endif;        
		/*
        return view('frontend.stocks.todas_ordenes_ingresadas', compact('ordenes_compra', 'farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'tipo_productos', 'registrador','productosnivel','proveedores','dependencias','lote','num_siaf','num_doc','tipo_procesos','reg_sanitario','l_origen','id_user','idregistrador','id_tipo_proceso','id_producto','id_tipo_producto','id_proveedor'));
		*/

        if($farmacia->almacen==1){
            $ordenes_compra = $model_guias->ShowAllIngresos($id_farmacia, $fechaDesde, $fechaHasta, $l_origen, $num_doc,$num_siaf,$reg_sanitario,$lote, $idconcepto,$id_producto,$id_tipo_producto,$id_proveedor,$id_tipo_proceso);
            
		    return view('frontend.stocks.todas_ordenes_ingresadas', compact('ordenes_compra', 'farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'tipo_productos', 'conceptos','productosnivel','proveedores','dependencias','lote','num_siaf','num_doc','tipo_procesos','reg_sanitario','l_origen','id_user','idconcepto','id_tipo_proceso','id_producto','id_tipo_producto','id_proveedor'));
        }
        else
        {
            $ordenes_compra = $model_guias->ShowAllIngresosFarmacia($id_farmacia, $fechaDesde, $fechaHasta, $num_doc,$reg_sanitario,$lote, $id_producto,$id_tipo_producto);
            return view('frontend.stocks.todos_ingresos_farmacia', compact('ordenes_compra', 'farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'tipo_productos', 'conceptos','productosnivel','proveedores','dependencias','lote','id_tipo_proceso','num_siaf','num_doc','tipo_procesos','reg_sanitario','l_origen','id_user','idconcepto','id_tipo_proceso','id_producto','id_tipo_producto','id_proveedor'));
        }

    }
	
	public function allEstablecimientos_withStocks(Request $request) {
        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;

        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1)
            ;$ruta = $ruta[0];
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

        $model_stock = new Stock_farmacia;
        $model_farmacia = new Farmacia;
        $model_kardex = new Kardexe();
        $date = Carbon::now();
    
        if($request->fecha_inicio=='')
            $fecha_inicio = $date->format('d-m-Y');
        else
            $fecha_inicio = $request->fecha_inicio;

        if($request->fecha_fin=='')
            $fecha_fin = $date->format('d-m-Y');
        else
            $fecha_fin = $request->fecha_fin;

        $id_user = Auth::user()->id;
        $farmacia = $model_farmacia->GetFarmaByUser($id_user);        
        $allfarmacias = $model_farmacia->OnlyFarmaciasEstablecimiento();            
        
        foreach ($allfarmacias as $f) {
            $id_establecimiento = $f->id;
            $nombre = $f->nombre;
            $total = $model_kardex->ShowCountTotalKardexByEstablecimiento($f->nombre, $fecha_inicio,$fecha_fin);            
            
            $desabastecido = 0;
            $por_desabastecido=0;
            $punt_desabastecido=0;            
            $desabastecido_medicamentos = 0;
            $por_desabastecido_medicamentos=0;
            $punt_desabastecido_medicamentos=0;
            $desabastecido_dispositivos = 0;
            $por_desabastecido_dispositivos=0;
            $punt_desabastecido_dispositivos=0;

            $sobreStock = 0;
            $por_sobreStock=0;
            $punt_sobreStock=0;            
            $sobreStock_medicamentos = 0;
            $por_sobreStock_medicamentos=0;
            $punt_sobreStock_medicamentos=0;
            $sobreStock_dispositivos = 0;
            $por_sobreStock_dispositivos=0;
            $punt_sobreStock_dispositivos=0;
            
            $normoStock = 0;
            $por_normoStock=0;
            $punt_normoStock=0;            
            $normoStock_medicamentos = 0;
            $por_normoStock_medicamentos=0;
            $punt_normoStock_medicamentos=0;
            $normoStock_dispositivos = 0;
            $por_normoStock_dispositivos=0;
            $punt_normoStock_dispositivos=0;
            
            $subStock = 0;
            $por_subStock=0;
            $punt_subStock=0;            
            $subStock_medicamentos = 0;
            $por_subStock_medicamentos=0;
            $punt_subStock_medicamentos=0;
            $subStock_dispositivos = 0;
            $por_subStock_dispositivos=0;
            $punt_subStock_dispositivos=0;
            
            $sinRotacion = 0;
            $por_sinRotacion=0;
            $punt_sinRotacion=0;            
            $sinRotacion_medicamentos = 0;
            $por_sinRotacion_medicamentos=0;
            $punt_sinRotacion_medicamentos=0;
            $sinRotacion_dispositivos = 0;
            $por_sinRotacion_dispositivos=0;
            $punt_sinRotacion_dispositivos=0;
            
            $total1 = 0;
            $total_medicamentos = 0;
            $total_dispositivos = 0;
            $kardexs = $model_kardex->ShowTotalKardexByEstablecimiento($f->nombre, $fecha_inicio, $fecha_fin);
            //dd($kardexs);
            foreach ($kardexs as $k) {
                if ($k->total > 0 && ($k->egresos - $k->recetas_devueltas) == 0) {
                    $cpma = -1;
                } else {
                    if ($k->nro_mes == 0) {
                        $cpma = -1;
                    } else {
                        if (($k->egresos - $k->recetas_devueltas) == 0) {
                            $cpma = 0;
                        } else {
                            $cpma = ($k->egresos - $k->recetas_devueltas) / $k->nro_mes;
                        }
                    }
                }

                if($cpma>0){
                            $provision = round(($k->total / $cpma), 1);
                            $meses_provicion = $provision;
                        }else
                        {
                            if (($k->egresos - $k->recetas_devueltas) == 0) {
                                $meses_provicion = 0;
                            } else {                                
                                $meses_provicion = $k->total;
                            }
                        }

                if ($k->total <= 0){
                    $desabastecido++;                    
                    if($k->tipo_producto=='MEDICINAS')
                        $desabastecido_medicamentos++;
                }else{
                    if ($cpma <= 0){
                        $sinRotacion++;
                        if($k->tipo_producto=='MEDICINAS')
                            $sinRotacion_medicamentos++;
                    }
                    if ($meses_provicion > 0 && $meses_provicion < 2){
                        $subStock++;
                        if($k->tipo_producto=='MEDICINAS')
                          $subStock_medicamentos++;  
                    }
                    if ($meses_provicion >= 2 && $meses_provicion <= 6){
                        $normoStock++;
                        if($k->tipo_producto=='MEDICINAS')
                            $normoStock_medicamentos++;
                    }
                    if ($meses_provicion > 6){
                        $sobreStock++;
                        if($k->tipo_producto=='MEDICINAS')
                            $sobreStock_medicamentos++;
                    }
                    if($meses_provicion==0 && $cpma >= 0 && $k->total > 0){
                            $subStock++;
                            if($k->tipo_producto=='MEDICINAS')
                                $subStock_medicamentos++;  
                    }
                }

                if($k->tipo_producto=='MEDICINAS')
                    $total_medicamentos++;                
            }

            $total1=$normoStock+$subStock+$sobreStock+$sinRotacion+$desabastecido;
            $total_medicamentos=$normoStock_medicamentos+$subStock_medicamentos+$sobreStock_medicamentos+$sinRotacion_medicamentos+$desabastecido_medicamentos;
            $total_dispositivos=$total1-$total_medicamentos;

            if($total1>0){
                $por_normoStock=$normoStock*100/$total1;
                $punt_normoStock=$por_normoStock/100;            
                $por_normoStock_medicamentos=$normoStock_medicamentos*100/$total_medicamentos;
                $punt_normoStock_medicamentos=$por_normoStock_medicamentos/100;
                $normoStock_dispositivos=$normoStock-$normoStock_medicamentos;
                $por_normoStock_dispositivos=$normoStock_dispositivos*100/$total_dispositivos;
                $punt_normoStock_dispositivos=$por_normoStock_dispositivos/100;  

                $por_subStock=$subStock*100/$total1;
                $punt_subStock=1-($por_subStock/100);
                $por_subStock_medicamentos=$subStock_medicamentos*100/$total_medicamentos;
                $punt_subStock_medicamentos=1-($por_subStock_medicamentos/100);
                $subStock_dispositivos=$subStock-$subStock_medicamentos;
                $por_subStock_dispositivos=$subStock_dispositivos*100/$total_dispositivos;
                $punt_subStock_dispositivos=1-($por_subStock_dispositivos/100);

                $por_sobreStock=$sobreStock*100/$total1;
                $punt_sobreStock=$por_sobreStock/100;            
                $por_sobreStock_medicamentos=$sobreStock_medicamentos*100/$total_medicamentos;
                $punt_sobreStock_medicamentos=1-($por_sobreStock_medicamentos/100);
                $sobreStock_dispositivos=$sobreStock-$sobreStock_medicamentos;
                $por_sobreStock_dispositivos=$sobreStock_dispositivos*100/$total_dispositivos;
                $punt_sobreStock_dispositivos=1-($por_sobreStock_dispositivos/100);

                $por_desabastecido=$desabastecido*100/$total1;
                $punt_desabastecido=2-(2*$por_desabastecido/100);
                $por_desabastecido_medicamentos=$desabastecido_medicamentos*100/$total_medicamentos;
                $punt_desabastecido_medicamentos=2-(2*$por_desabastecido_medicamentos/100);  
                $desabastecido_dispositivos=$desabastecido-$desabastecido_medicamentos;
                $por_desabastecido_dispositivos=$desabastecido_dispositivos*100/$total_dispositivos;
                $punt_desabastecido_dispositivos=2-(2*$por_desabastecido_dispositivos/100);  

                $por_sinRotacion=$sinRotacion*100/$total1;
                $punt_sinRotacion=1-(5*$por_sinRotacion/100);
                $por_sinRotacion_medicamentos=$sinRotacion_medicamentos*100/$total_medicamentos;
                $punt_sinRotacion_medicamentos=1-(5*$por_sinRotacion_medicamentos/100);                
                $sinRotacion_dispositivos=$sinRotacion-$sinRotacion_medicamentos;
                $por_sinRotacion_dispositivos=$sinRotacion_dispositivos*100/$total_dispositivos;
                $punt_sinRotacion_dispositivos=1-(5*$por_sinRotacion_dispositivos/100);

                $existencia=$subStock+$normoStock+$sobreStock+$sinRotacion;
                $por_existencia=$existencia*100/$total1;
                $punt_existencia=2*$por_existencia/100;                
                $disponibilidad=$normoStock+$sobreStock+$sinRotacion;
                $por_disponibilidad=$disponibilidad*100/$total1;
                $punt_disponibilidad=2*$por_disponibilidad/100;


                $existencia_medicamentos=$subStock_medicamentos+$normoStock_medicamentos+$sobreStock_medicamentos+$sinRotacion_medicamentos;
                $por_existencia_medicamentos=$existencia_medicamentos*100/$total_medicamentos;
                $punt_existencia_medicamentos=2*$por_existencia_medicamentos/100;                
                $disponibilidad_medicamentos=$normoStock_medicamentos+$sobreStock_medicamentos+$sinRotacion_medicamentos;
                $por_disponibilidad_medicamentos=$disponibilidad_medicamentos*100/$total_medicamentos;
                $punt_disponibilidad_medicamentos=2*$por_disponibilidad_medicamentos/100;
                
                $existencia_dispositivos=$subStock_dispositivos+$normoStock_dispositivos+$sobreStock_dispositivos+$sinRotacion_dispositivos;
                $por_existencia_dispositivos=$existencia_dispositivos*100/$total_dispositivos;
                $punt_existencia_dispositivos=2*$por_existencia_dispositivos/100;                
                $disponibilidad_dispositivos=$normoStock_dispositivos+$sobreStock_dispositivos+$sinRotacion_dispositivos;
                $por_disponibilidad_dispositivos=$disponibilidad_dispositivos*100/$total_dispositivos;
                $punt_disponibilidad_dispositivos=2*$por_disponibilidad_dispositivos/100;
    
            }
            
            $farma_productos = array('id_establecimiento' => $id_establecimiento, 'nombre' => $nombre, 'total' => $total1, 'desabastecido' => 
$desabastecido, 'sobreStock' => $sobreStock, 'normoStock' => $normoStock, 'subStock' => $subStock, 'sinRotacion' => $sinRotacion, 'total_medicamentos'=>$total_medicamentos,'normoStock_medicamentos'=>$normoStock_medicamentos,'subStock_medicamentos'=>$subStock_medicamentos,'sinRotacion_medicamentos'=>$sinRotacion_medicamentos,'desabastecido_medicamentos'=>$desabastecido_medicamentos,'sobreStock_medicamentos'=>$sobreStock_medicamentos,'por_normoStock'=>$por_normoStock,'punt_normoStock'=>$punt_normoStock,'por_normoStock_medicamentos'=>$por_normoStock_medicamentos,'punt_normoStock_medicamentos'=>$punt_normoStock_medicamentos,'por_subStock'=>$por_subStock,'punt_subStock'=>$punt_subStock,'por_subStock_medicamentos'=>$por_subStock_medicamentos,'punt_subStock_medicamentos'=>$punt_subStock_medicamentos,'por_sobreStock'=>$por_sobreStock,'punt_sobreStock'=>$punt_sobreStock,'por_sobreStock_medicamentos'=>$por_sobreStock_medicamentos,'punt_sobreStock_medicamentos'=>$punt_sobreStock_medicamentos,'por_desabastecido'=>$por_desabastecido,'punt_desabastecido'=>$punt_desabastecido,'por_desabastecido_medicamentos'=>$por_desabastecido_medicamentos,'punt_desabastecido_medicamentos'=>$punt_desabastecido_medicamentos,'por_sinRotacion'=>$por_sinRotacion,'punt_sinRotacion'=>$punt_sinRotacion,'por_sinRotacion_medicamentos'=>$por_sinRotacion_medicamentos,'punt_sinRotacion_medicamentos'=>$punt_sinRotacion_medicamentos,'por_normoStock_dispositivos'=>$por_normoStock_dispositivos,'punt_normoStock_dispositivos'=>$punt_normoStock_dispositivos,'punt_normoStock_dispositivos'=>$punt_normoStock_dispositivos,'por_subStock_dispositivos'=>$por_subStock_dispositivos,'punt_subStock_dispositivos'=>$punt_subStock_dispositivos,'por_sobreStock_dispositivos'=>$por_sobreStock_dispositivos,'punt_sobreStock_dispositivos'=>$punt_sobreStock_dispositivos,'punt_desabastecido_dispositivos'=>$punt_desabastecido_dispositivos,'por_desabastecido_dispositivos'=>$por_desabastecido_dispositivos,'por_sinRotacion_dispositivos'=>$por_sinRotacion_dispositivos,'punt_sinRotacion_dispositivos'=>$punt_sinRotacion_dispositivos,'sinRotacion_dispositivos'=>$sinRotacion_dispositivos,'sobreStock_dispositivos'=>$sobreStock_dispositivos,'desabastecido_dispositivos'=>$desabastecido_dispositivos,'subStock_dispositivos'=>$subStock_dispositivos,'normoStock_dispositivos'=>$normoStock_dispositivos,'total_dispositivos'=>$total_dispositivos,'existencia'=>$existencia,'por_existencia'=>$por_existencia,'punt_existencia'=>$punt_existencia,'disponibilidad'=>$disponibilidad,'por_disponibilidad'=>$por_disponibilidad,'punt_disponibilidad'=>$punt_disponibilidad,'existencia_medicamentos'=>$existencia_medicamentos,'por_existencia_medicamentos'=>$por_existencia_medicamentos,'punt_existencia_medicamentos'=>$punt_existencia_medicamentos,'disponibilidad_medicamentos'=>$disponibilidad_medicamentos,'por_disponibilidad_medicamentos'=>$por_disponibilidad_medicamentos,'punt_disponibilidad_medicamentos'=>$punt_disponibilidad_medicamentos,'existencia_dispositivos'=>$existencia_dispositivos,'por_existencia_dispositivos'=>$por_existencia_dispositivos,'punt_existencia_dispositivos'=>$punt_existencia_dispositivos,'disponibilidad_dispositivos'=>$disponibilidad_dispositivos,'por_disponibilidad_dispositivos'=>$por_disponibilidad_dispositivos,'punt_disponibilidad_dispositivos'=>$punt_disponibilidad_dispositivos);
            $listado[] = $farma_productos;
        }
        return view('frontend.direoss.stocks.monitoreo_ici', compact('listado','fecha_inicio','fecha_fin'));
    }
	
    public function allEstablecimientos_withStocks_25062020(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1)
            ;$ruta = $ruta[0];
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

        $model_stock = new Stock_farmacia;
        $model_farmacia = new Farmacia;
        $model_kardex = new Kardexe();
        $date = Carbon::now();
    
        if($request->fecha_inicio=='')
            $fecha_inicio = $date->format('d-m-Y');
        else
            $fecha_inicio = $request->fecha_inicio;

        if($request->fecha_fin=='')
            $fecha_fin = $date->format('d-m-Y');
        else
            $fecha_fin = $request->fecha_fin;

        $id_user = Auth::user()->id;
        $farmacia = $model_farmacia->GetFarmaByUser($id_user);        
        $allfarmacias = $model_farmacia->OnlyFarmaciasEstablecimiento();            
        
        foreach ($allfarmacias as $f) {
            $id_establecimiento = $f->id;
            $nombre = $f->nombre;
            $total = $model_kardex->ShowCountTotalKardexByEstablecimiento($f->nombre, $fecha_inicio,$fecha_fin);            
            $desabastecido = 0;
            $sobreStock = 0;
            $normoStock = 0;
            $subStock = 0;
            $sinRotacion = 0;
            $kardexs = $model_kardex->ShowTotalKardexByEstablecimiento($f->nombre, $fecha_inicio, $fecha_fin);

            foreach ($kardexs as $k) {
                if ($k->total > 0 && ($k->egresos - $k->recetas_devueltas) == 0) {
                    $cpma = -1;
                } else {
                    if ($k->nro_mes == 0) {
                        $cpma = -1;
                    } else {
                        if (($k->egresos - $k->recetas_devueltas) == 0) {
                            $cpma = 0;
                        } else {
                            $cpma = ($k->egresos - $k->recetas_devueltas) / $k->nro_mes;
                        }
                    }
                }

                if($cpma>0){
                            $provision = round(($k->total / $cpma), 1);
                            $meses_provicion = $provision;
                        }else
                        {
                            if (($k->egresos - $k->recetas_devueltas) == 0) {
                                $meses_provicion = 0;
                            } else {                                
                                $meses_provicion = $k->total;
                            }
                        }

                if ($k->total <= 0):
                    $desabastecido++;
                else:
                    if ($cpma <= 0):
                        $sinRotacion++;
                    endif;
                    if ($meses_provicion > 0 && $meses_provicion < 2):
                        $subStock++;
                    endif;
                    if ($meses_provicion >= 2 && $meses_provicion <= 6):
                        $normoStock++;
                    endif;
                    if ($meses_provicion > 6):
                        $sobreStock++;
                    endif;

                endif;
            }

            $farma_productos = array('id_establecimiento' => $id_establecimiento, 'nombre' => $nombre, 'total' => $total, 'desabastecido' => 

$desabastecido, 'sobreStock' => $sobreStock, 'normoStock' => $normoStock, 'subStock' => $subStock, 'sinRotacion' => $sinRotacion);
            $listado[] = $farma_productos;
        }        

        return view('frontend.direoss.stocks.monitoreo_ici', compact('listado','fecha_inicio','fecha_fin'));
    }

	/*
	public function cargarorigen($tipo) {
       if($tipo==1 || $tipo==9 || $tipo==12){
            $model_dependencia = new Dependencia;
            $destino = $model_dependencia->getTodasDependencias();
        }
        else
        {
            if($tipo==14 || $tipo==15){
                $model_proveedor = new Proveedore;
                $destino = $model_proveedor->getProveedorActivo();
            }
            else
            {
                $model_establecimiento = new Establecimiento;
                $destino = $model_establecimiento->ShowAll();
            }
        }        
        return $destino;
    }
	*/
	
	public function cargarorigen($tipo) {
        if($tipo==11){
            $model_establecimiento = new Establecimiento;
            $destino = $model_establecimiento->ShowAll();
        }
        else
        {
            if($tipo==7 || $tipo==8){
                $model_proveedor = new Proveedore;
                $destino = $model_proveedor->getProveedorActivo();
            }
            else
            {
                $model_dependencia = new Dependencia;
            $destino = $model_dependencia->getTodasDependencias();
            }
        } 
        
        return $destino;
    }

	public function movimiento_producto($id_user = 0) {

        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;

        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1)
            ;$ruta = $ruta[0];
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

        $id_user = Auth::user()->id;

        $id_producto="";
        $anio = "";

        $date = Carbon::now();
        $fechaDesde = $date->format('Y-m-d');
        $fechaHasta = $date->format('Y-m-d');

        //==================== 
        $model_farma = new Farmacia();
        $farmacia = $model_farma->GetFarmaByUser($id_user);
        $id_farmacia = $farmacia->id;
        $modo_validacion = $farmacia->modo_validacion;
        //====================
        $model_producto = new Producto();
        $nivel = $model_farma->ObtenerNivelFarmacia($id_farmacia);
        $productosnivel = $model_producto->ProductosFarmaciasSegunNivelIncluidoNoPetitorio($nivel->nivel);
        $nombre_producto='';
        
		$fecha_inicio = $date->format('d-m-Y');
        $fecha_fin = $date->format('d-m-Y');
        $saldo_inicial=0;
        //====================
        

        return view('frontend.stocks.reporte_movimiento_producto', compact('nombre_producto', 'farmacia','modo_validacion', 'id_farmacia', 
'id_user','id_producto','anio','id_proveedor','productosnivel','fecha_inicio','fecha_fin','saldo_inicial'));
    }

	public function buscar_movimiento_producto(Request $request) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;        

        if (!$request->id_user):
            return redirect('/movimiento_producto');
        endif;

        $modelfarma = new Farmacia;
        $farmacia = $modelfarma->GetFarmaByUser($request->id_user);
        $id_farmacia = $farmacia->id;
        
        //if($id_farmacia==113):
            if($request->id_producto!=''):

                $modo_validacion = $farmacia->modo_validacion;        
                //----------------------------------------------------------------------
                $usuario = $request->id_user;
                $date = Carbon::now();   

                $id_producto = $request->id_producto; 
                $fecha_inicio = $request->fecha_inicio;
                $fecha_fin = $request->fecha_fin;
                $fechaDesde = $request->fecha_inicio;
                $fechaHasta = $request->fecha_fin;
                //==================== 
                $model_farma = new Farmacia();
                $farmacia = $model_farma->GetFarmaByUser($request->id_user);
                $id_farmacia = $farmacia->id;
                $modo_validacion = $farmacia->modo_validacion;
                //====================
                $model_guias = new Guia_entrada();   
                if($id_farmacia==113):     
                    $ordenes_compra = $model_guias->ShowAllMovimientosProductosCompra($id_farmacia, $fecha_inicio, $fecha_fin, $id_producto);            
                    $ingresos_compra = $model_guias->ShowAllIngresosProductos($id_farmacia, $fecha_inicio, $fecha_fin, $id_producto);
                    $ingresos_varios = $model_guias->ShowAllMovimientosProductos($id_farmacia, $fecha_inicio, $fecha_fin, $id_producto);
                    $ordenes_salidas = $model_guias->ShowAllSalidasProductos($id_farmacia,  $fecha_inicio, $fecha_fin, $id_producto);  
                else:
                    $ingresos_transferencias = $model_guias->ShowAllIngresosTransferenciasProductosFarmacia($id_farmacia, $fecha_inicio, $fecha_fin, $id_producto);
                    $ingresos_nota_entradas = $model_guias->ShowAllMovimientosProductosFarmacia($id_farmacia, $fecha_inicio, $fecha_fin, $id_producto);
                    $salidas_producto = $model_guias->ShowAllSalidasProductosFarmacia($id_farmacia,  $fecha_inicio, $fecha_fin, $id_producto); 
                endif;
                
                $registrador = $model_guias->AllRegistradores($id_farmacia);

                $model_producto = new Producto();
                $nivel = $model_farma->ObtenerNivelFarmacia($id_farmacia);
                $productosnivel = $model_producto->ProductosFarmaciasSegunNivelIncluidoNoPetitorio($nivel->nivel);
                
                $nombre_producto='';
               	
                //$modelReceta = new Receta_vale();
                $modelReceta = new \App\Models\Receta_vale;
        		$model_kardex = new Kardexe();
                $saldo_final=$model_kardex->ShowSaldoInicial($id_producto,$id_farmacia,$fecha_fin);
                

                $nombre_farmacia=$farmacia->nombre;

                $kardex = $modelReceta->AllKardexByFarmaAndProducto($id_farmacia, $nombre_farmacia, $id_producto, $fechaDesde, $fechaHasta);
                /*if ($fechaDesde != ""):
                    $kardex_inicial1=$model_kardex->ShowCargaInicial($id_farmacia,$id_producto,$fechaDesde);
                    $fechaDesde = strtotime('-1 day', strtotime($fechaDesde));
                    $fechaDesde = date('j-m-Y', $fechaDesde);
                    $kardex_inicial = $model_kardex->ShowTotalKardexByFarmaciaResumen($id_farmacia, $id_producto, $fechaDesde);
                    $kardex_inicial = (isset($kardex_inicial[0])) ? $kardex_inicial[0] : 0;
                    $fechaDesde = strtotime('+1 day', strtotime($fechaDesde));
                    $fechaDesde = date('j-m-Y', $fechaDesde);
                endif;
                */
                
                if ($fechaDesde != ""):
                    $fechaDesde = strtotime('-1 day', strtotime($fechaDesde));
                    $fechaDesde = date('j-m-Y', $fechaDesde);
                    $kardex_inicial = $model_kardex->ShowTotalKardexByFarmaciaResumen($id_farmacia, $id_producto, $fechaDesde);
                    $kardex_inicial = (isset($kardex_inicial[0])) ? $kardex_inicial[0] : 0;
                    $fechaDesde = strtotime('+1 day', strtotime($fechaDesde));
                    $fechaDesde = date('j-m-Y', $fechaDesde);
                endif;
                
                if(isset($kardex_inicial1[0]->cantidad)) 
                    $saldo_inicial=$kardex_inicial1[0]->cantidad;
                else
                {
                    if(isset($kardex_inicial->total)) 
                        $saldo_inicial=$kardex_inicial->total;
                    else
                        $saldo_inicial=0;
                }    //$saldo_inicial=$saldo_inicial1;
                
                if($farmacia->almacen==1):
                    return view('frontend.stocks.reporte_movimiento_producto', compact('nombre_producto', 'farmacia','id_farmacia','id_user','id_producto','ordenes_compra','productosnivel','ingresos_compra','ordenes_salidas','fecha_inicio','fecha_fin','saldo_inicial','ingresos_varios','saldo_final'));
                else:
                    return view('frontend.stocks.reporte_movimiento_producto_farmacia', compact('nombre_producto', 'farmacia','id_farmacia','id_user','id_producto','productosnivel','ingresos_transferencias','salidas_producto','fecha_inicio','fecha_fin','saldo_inicial','ingresos_nota_entradas','saldo_final', 'kardex'));
                endif;
            else:
                Session::flash('flash_message', 'Debe seleccionar un producto');
                return redirect('/movimiento_producto');
            endif;
        //else:
        //    Session::flash('flash_message', 'Esta opción es sólo para almacén San Borja');
        //    return redirect('/movimiento_producto');
        //endif;
    }
	
	public function busca_productos_guia_entrada($id_pecosa,$id_producto) {
        
        //$valor[]="";
		$valor=array();
        $model_pecosa = new Guia_entrada();
        $dato_pecosa = $model_pecosa->GetPecosaByIdNotaEntrada($id_pecosa);
        if($dato_pecosa->concepto==1){
            $dato_anterior=Guia_entrada::where('nro_exp_siaf',$dato_pecosa->nro_exp_siaf)->where('nota_entrada',$dato_pecosa->nota_entrada)->where('estado', '=',2)->where('id', '<>',$dato_pecosa->id)->first();
            $y=count($dato_anterior); 
            if($y==0){ 
                $productos_recpecionados = $model_pecosa->ShowOnlyProductoByPecosa($id_pecosa,$id_producto);
                $z=count($productos_recpecionados); 

                $productos_recpecionados2 = $model_pecosa->ShowProductoByPecosa($id_pecosa);
                $p=count($productos_recpecionados2);                 
                if($z==0){
                    if($p>0){
                        $valor[0]=$productos_recpecionados2[0]->guia_entrada;
                        $valor[1]=$productos_recpecionados2[0]->factura;
                        $valor[2]=""; $valor[3]=""; $valor[4]=""; $valor[5]="";  $valor[6]=$dato_pecosa->concepto; $valor[7]=1;
                    }
                    else
                    {
                        $valor[0]="";  $valor[1]=""; $valor[2]=""; $valor[3]=""; $valor[4]="";   $valor[5]="";  $valor[6]=$dato_pecosa->concepto; $valor[7]=2;
                    }
                }
                else 
                {
                    $valor[0]=$productos_recpecionados[0]->guia_entrada;
                    $valor[1]=$productos_recpecionados[0]->factura;
                    $valor[2]=$productos_recpecionados[0]->total_cantidad;
                    $valor[3]=$productos_recpecionados[0]->presentacion_comercial;
                    $valor[4]=$productos_recpecionados[0]->precio_promedio;
                    $valor[5]=$productos_recpecionados[0]->marca;
                    $valor[6]=$dato_pecosa->concepto;
                    $valor[7]=3;
                }

            }
            else
            {
                $productos_recpecionados_anterior = $model_pecosa->ShowOnlyProductoByPecosa($dato_anterior->id,$id_producto);
                $m=count($productos_recpecionados_anterior);
                if($m>0){ 
                    $productos_recpecionados = $model_pecosa->ShowOnlyProductoByPecosa($id_pecosa,$id_producto);
                    $n=count($productos_recpecionados);
                    $productos_recpecionados2 = $model_pecosa->ShowProductoByPecosa($id_pecosa);
                    $q=count($productos_recpecionados2); 
                    if($n==0){
                        if($q==0){
                            $valor[0]="";
                            $valor[1]="";
                            $valor[2]=$productos_recpecionados_anterior[0]->total_cantidad;
                            $valor[3]="";
                            $valor[4]=$productos_recpecionados_anterior[0]->precio_promedio;
                            $valor[5]=$productos_recpecionados_anterior[0]->marca;
                            $valor[6]=$dato_pecosa->concepto;
                            $valor[7]=4;
                        }
                        else
                        {
                           $valor[0]=$productos_recpecionados2[0]->guia_entrada;
                            $valor[1]=$productos_recpecionados2[0]->factura;
                            $valor[2]=$productos_recpecionados_anterior[0]->total_cantidad;
                            $valor[3]="";
                            $valor[4]=$productos_recpecionados_anterior[0]->precio_promedio;
                            $valor[5]=$productos_recpecionados_anterior[0]->marca;
                            $valor[6]=$dato_pecosa->concepto;
                            $valor[7]=5;
                        }
                    }
                    else
                    {
                        $valor[0]=$productos_recpecionados[0]->guia_entrada;
                        $valor[1]=$productos_recpecionados[0]->factura;
                        $valor[2]=$productos_recpecionados[0]->total_cantidad;
                        $valor[3]=$productos_recpecionados[0]->presentacion_comercial;
                        $valor[4]=$productos_recpecionados[0]->precio_promedio;
                        $valor[5]=$productos_recpecionados[0]->marca;
                        $valor[6]=$dato_pecosa->concepto;
                        $valor[7]=6;
                    }
                }
                else
                {
                    $productos_recpecionados2 = $model_pecosa->ShowProductoByPecosa($id_pecosa);
                    $r=count($productos_recpecionados2); //0 
                    if($r>0){
                        $valor[0]=$productos_recpecionados2[0]->guia_entrada;
                        $valor[1]=$productos_recpecionados2[0]->factura;
                        $valor[2]=""; $valor[3]=""; $valor[4]=""; $valor[5]="";
                        $valor[6]=$dato_pecosa->concepto;
                        $valor[7]=7;

                    }
                    else
                    {
                        $valor[0]=""; $valor[1]="";$valor[2]=""; $valor[3]=""; $valor[4]=""; $valor[5]=""; $valor[6]=$dato_pecosa->concepto; $valor[7]=8;
                    }
                }
            }

        }
        else
        {
            if($dato_pecosa->concepto==7 || $dato_pecosa->concepto==8){
                $productos_recpecionados = $model_pecosa->ShowProductoByPecosa($id_pecosa);
                $x=count($productos_recpecionados); 
                if($x==0){ 
                    $valor[0]=""; $valor[1]=""; $valor[2]=""; $valor[3]=""; $valor[4]=""; $valor[5]=""; $valor[6]=$dato_pecosa->concepto; $valor[7]=9;
                } 
                else
                {
                    $valor[0]=$productos_recpecionados[0]->guia_entrada;
                    $valor[1]=$productos_recpecionados[0]->factura;
                    $valor[2]=""; $valor[3]=""; $valor[4]=""; $valor[5]=""; 
                    $valor[6]=$dato_pecosa->concepto;
                    $valor[7]=10;
                }

            }
            else
            {
                $valor[0]=""; $valor[1]=""; $valor[2]=""; $valor[3]=""; $valor[4]=""; $valor[5]="";
                $valor[6]=$dato_pecosa->concepto;
                $valor[7]=11;
            }
        }
        return $valor; 
    }
	
	
    public function valida_productos_guia_entrada($id_pecosa,$id_producto,$cantidad) {
        
        $sw[0]=0;
        $cant=0;
        $model_pecosa = new Guia_entrada();
        $dato_pecosa = $model_pecosa->GetPecosaByIdNotaEntrada($id_pecosa);
        
        if($dato_pecosa->concepto==1){

            $cad="select sum(cantidad) total, total_cantidad from carga_pecosas cp  where id_guia_entrada in (select id from guia_entradas ge where nro_exp_siaf ='".$dato_pecosa->nro_exp_siaf."' and nota_entrada ='".$dato_pecosa->nota_entrada."' and id_producto=".$id_producto." and estado!=0) and estado !=0 group by total_cantidad";
            $data = DB::select($cad);
            
            $y=count($data); //0
            if($y>0){ //es unico
                $cant=$data[0]->total+$cantidad;
               if($cant>$data[0]->total_cantidad){                    
                    $sw[0]=1;
               }               
            }

        }
        
        return $sw; 
    }

    public function valida_productos_guia_entrada_modificado($id_pecosa,$id_producto,$cantidad,$id_carga_pecosa) {
        
        $sw[0]=0;
        $cant=0;
        $model_pecosa = new Guia_entrada();
        $dato_pecosa = $model_pecosa->GetPecosaByIdNotaEntrada($id_pecosa);
        
        if($dato_pecosa->concepto==1){

            $cad="select sum(cantidad) total, total_cantidad from carga_pecosas cp  where id_guia_entrada in (select id from guia_entradas ge where nro_exp_siaf ='".$dato_pecosa->nro_exp_siaf."' and nota_entrada ='".$dato_pecosa->nota_entrada."' and id_producto=".$id_producto." and estado!=0) group by total_cantidad";
            $data = DB::select($cad);
            
            $cad2="select cantidad from carga_pecosas cp  where id=".$id_carga_pecosa;
            $data2 = DB::select($cad2);
            
            $cant=$data[0]->total+$cantidad-$data2[0]->cantidad;

            if($cant>$data[0]->total_cantidad){                    
                    $sw[0]=1;
               } 
        }
        
        return $sw; 
    }

    public function anular($id){
		
		if (!Auth::check()):
			return redirect('/auth/login');
		endif;
		$sw = 0 ; 
        $model_pecosa = new Guia_entrada();
        $productos_recpecionados = $model_pecosa->ShowProductoByPecosa($id);

        $id_farmacia = Auth::user()->id_farmacia;
        
        $model_kardex = new Kardexe();
        foreach ($productos_recpecionados as $k) {
            $encontrado = $model_kardex->GetBuscaMovimiento($k->id_producto, $id_farmacia, $k->fecha_carga, $k->registro_sanitario, $k->lote, $k->fecha_vencimiento, $k->precio_promedio);
            if($encontrado->mov > 0):
                $sw = 1; 
            endif;
        }

        if($sw==0): 
            $update=Guia_entrada::where('id','=',$id)->first();
            $update->estado=0;
            $update->anulado=0;
            $update->save();

            $cad3 = "update kardex_lotes set estado=0 where id_kardex in (select id from kardexes where tipo_movimiento = 'IR' and id_nro_movimiento='".$id."' and id_farmacia='".$id_farmacia."' and estado=1)";
                $data3 = DB::update($cad3);    

            $cad3 = "update kardexes set estado=0 where  tipo_movimiento = 'IR' and id_nro_movimiento='".$id."' and id_farmacia='".$id_farmacia."' and estado=1";
                $data3 = DB::update($cad3);    

            Session::flash('flash_message', 'Se anulo correctamente');
        else:
            Session::flash('flash_message', 'No se pudo anular el ingreso; porque existe un producto que tiene movimiento de stock, coordinar con el administrador del Sistema');
        endif;
    }
		
	public function print_ingresos_almacen($fecha_inicio, $fecha_fin, $id_farmacia, $num_doc ,$num_siaf, $reg_sanitario, $lote, $l_origen ,$id_proveedor, $id_tipo_proceso, $id_tipo_producto,$idconcepto,$id_producto) {
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
        $nombre_farmacia = $farma->nombre;
        //----------------------------------------------------        
        if ($l_origen == 0) $l_origen="";
        if ($id_proveedor == 0) $id_proveedor="";
        if ($id_tipo_proceso == 0) $id_tipo_proceso="";
        if ($id_tipo_producto == 0) $id_tipo_producto="";
        if ($id_producto == 0) $id_producto="";
        if ($idconcepto == 0) $idconcepto="";
        if ($num_doc!=0):
            $num_doc=str_pad($num_doc, 7, "0", STR_PAD_LEFT);
        else:
            $num_doc=$num_doc;
        endif;
        if ($num_siaf!=0):
            $num_siaf=str_pad($num_siaf, 10, "0", STR_PAD_LEFT);
        else:
            $num_siaf=$num_siaf;
        endif;        
        $fechaDesde = date("Y-m-d", strtotime($fecha_inicio));
        $fechaHasta = date("Y-m-d", strtotime($fecha_fin));
        $model_guias = new Guia_entrada();        
        $ordenes_compra = $model_guias->ShowAllIngresos($id_farmacia, $fechaDesde, $fechaHasta, $l_origen, $num_doc,$num_siaf,$reg_sanitario,$lote, $idconcepto,$id_producto,$id_tipo_producto,$id_proveedor,$id_tipo_proceso);        
        //----------------------------------------------------
        return view('frontend.stocks.print_ingresos_almacen', compact('ordenes_compra', 'farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'tipo_productos', 'conceptos','productosnivel','proveedores','dependencias','lote','id_tipo_proceso','num_siaf','num_doc','tipo_procesos','reg_sanitario','l_origen','id_user','idconcepto','id_tipo_proceso','id_producto','id_tipo_producto','id_proveedor'));
    }
	
	public function print_movimiento_producto($fecha_inicio, $fecha_fin, $id_producto, $id_user) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;        
        $modelfarma = new Farmacia;
        $farmacia = $modelfarma->GetFarmaByUser($id_user);
        $id_farmacia = $farmacia->id;            
        $nombre_farmacia = $farmacia->nombre;            
        //----------------------------------------------------------------------
        $usuario = $id_user;
        //====================
        $model_guias = new Guia_entrada();
        $ordenes_compra = $model_guias->ShowAllMovimientosProductosCompra($id_farmacia, $fecha_inicio, $fecha_fin, $id_producto);
        $ingresos_compra = $model_guias->ShowAllIngresosProductos($id_farmacia, $fecha_inicio, $fecha_fin, $id_producto);
        $ingresos_varios = $model_guias->ShowAllMovimientosProductos($id_farmacia, $fecha_inicio, $fecha_fin, $id_producto);
        $ordenes_salidas = $model_guias->ShowAllSalidasProductos($id_farmacia,  $fecha_inicio, $fecha_fin, $id_producto);     
        $model_producto = new Producto();
        $nivel = $modelfarma->ObtenerNivelFarmacia($id_farmacia);
        $productosnivel = $model_producto->ProductosFarmaciasSegunNivel($nivel->nivel);        
        $nombre_producto='';        
        //$model_kardex = new Kardexe();
        //$saldo_inicial=$model_kardex->ShowSaldoInicial($id_producto,$id_farmacia,$fecha_fin);
		$model_kardex = new Kardexe();
        $saldo_final=$model_kardex->ShowSaldoInicial($id_producto,$id_farmacia,$fecha_fin);
        $nombre_farmacia=$farmacia->nombre;

        if($fecha_inicio<'13-09-2020')
            $fecha_inicial='13-09-2020';
        else
            $fecha_inicial=$fecha_inicio;


        if ($fecha_inicio != ""):
            $fecha_ini = strtotime('-1 day', strtotime($fecha_inicial));
            $fecha_ini = date('j-m-Y', $fecha_ini);        
            $kardex_inicial = $model_kardex->ShowCargaInicial($id_farmacia, $id_producto, $fecha_ini);
            if (isset($kardex_inicial[0])):
                $saldo_inicial=$kardex_inicial[0]->cantidad;
            else: 
                $saldo_inicial=0;
            endif;
        endif;
		
        //----------------------------------------------------        
        return view('frontend.stocks.print_movimiento_producto', compact('nombre_producto', 'farmacia', 
'id_farmacia','id_user','id_producto','ordenes_compra','anio','productosnivel','ingresos_compra','ordenes_salidas','fecha_inicio','fecha_fin','saldo_inicial','ingresos_varios','saldo_final'));
    }
	
	public function stocksbyAlmacenModal() {
        $situacion = 0;

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        //====================
        $model_stock = new Stock_farmacia;
        $model_carga = new Carga_inicial_stock;
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByIdfarma(113);
        $nombre_farmacia = $farma->nombre;
        $id_establecimiento = $farma->id_establecimiento;
        $id_farmacia = $farma->id;
        $carga = $model_carga->GetCargaRegistradaByFarmacia(113);
        $contador = count($carga);
        if ($contador == 0) {
            $estado_carga = 0;
        } else {
            $estado_carga = $carga->estado;
        }
        $farmaciaActual = 1;

        //====================        
        $model_producto = new Producto;
        $model_kardex = new Kardexe();

        $mes = 6;

        $fecha_inicio = $model_kardex->getFechaServidorRestaMeses(6);
        $fecha_fin = $model_kardex->getFechaServidor();

        //====================================        
        $kardexs = $model_kardex->ShowTotalKardexByFarmacia($nombre_farmacia, $fecha_fin);
        $resultado = array();
        foreach ($kardexs as $k) {
            
            $arrayarticulo = array('id_producto' => $k->id_producto,
                'codigo' => $k->codigo_producto,
                'nombre' => $k->nombre_producto,
                'unidad' => $k->unidad,
                'cantidad' => $k->total,                
                'egresos' => $k->egresos,                
                'nro_mes' => $k->nro_mes,                
                'recetas_devueltas' => $k->recetas_devueltas,                    
            );

            $resultado[] = $arrayarticulo;
        }
        //----------------------------------------------------------------------
        $countMedicamento = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 1);
        $countbiomedico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 3);
        $countinsumolaboratorio = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 2);
        $countMaterialLaboratorio = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 4);
        $countMaterialDental = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 5);
        $countMatFotograficoAndFonotecnico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 6);
        $countMaterialQuirurgico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 7);
        $countProductosAfines = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 8);
        //----------------------------------------------------------------------
        $fecha_inicio_venc = $fecha_fin;
        $fecha_fin_venc = $model_kardex->getFechaServidorRestaMeses("-6");
        $stocks_proximos_vencer = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechasVencimiento("", $id_farmacia, $fecha_inicio_venc, $fecha_fin_venc);
        $count_proximos_vencer = count($stocks_proximos_vencer);
        $stocks_productos_vencidos = $model_kardex->ShowTotalKardexByFarmaciaSinStock($nombre_farmacia, $fecha_inicio_venc);
        $count_productos_vencidos = count($stocks_productos_vencidos);
        //----------------------------------------------------------------------

        return view('frontend.stocks.index-modal-almacen', compact('resultado', 'farma', 'fecha', 'constock', 'sinstock', 'minstock', 'id_farmacia', 'estado_carga', 'countMedicamento', 'countbiomedico', 'countinsumolaboratorio', 'countMaterialLaboratorio', 'countMaterialDental', 'countMatFotograficoAndFonotecnico', 'count_proximos_vencer', 'count_productos_vencidos', 'situacion', 'farmaciaActual','countMaterialQuirurgico','countProductosAfines'));
    }
	
	public function cargarstock_porfarmacia($id_producto, $id_farmacia) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $model_producto = new Producto();
        $model_stock = new Stock_farmacia();
        $model_kardex = new Kardexe();

        $productos = $model_producto->GetCodByIdProducto($id_producto);
        $codigo_producto = $productos->codigo;
        
        $farmacia = DB::table('farmacias')->where('id', '=', $id_farmacia)->first();
        $nombre_farmacia = $farmacia->nombre;
        $id_establecimiento = $farmacia->id_establecimiento;
        $fecha_fin = $model_kardex->getFechaServidor();
        
        $id_farmacia_almacen = 113;
        $total = $model_kardex->ShowTotalByIdProductoAndIdFarmacia($id_producto, $id_farmacia, $fecha_fin);
        $totalEstablecimiento = $model_kardex->ShowTotalCantidadByIdProductoAndIdEstablecimiento($id_producto, $id_establecimiento, $fecha_fin);
        $totalAlmacen = $model_kardex->ShowTotalByIdProductoAndIdFarmacia($id_producto, $id_farmacia_almacen, $fecha_fin);
        
        $cantidad = 0;
        if (isset($total->total) && $total->total > 0)$cantidad = $total->total;
        
        $cantidadEstablecimiento = 0;
        if (isset($totalEstablecimiento->total) && $totalEstablecimiento->total > 0)$cantidadEstablecimiento = $totalEstablecimiento->total;
        
        $cantidadAlmacen = 0;
        if (isset($totalAlmacen->total) && $totalAlmacen->total > 0)$cantidadAlmacen = $totalAlmacen->total;
        
        $array = array();
        $array["cantidad"] = $cantidad;
        $array["cantidadEstablecimiento"] = $cantidadEstablecimiento;
        $array["cantidadAlmacen"] = $cantidadAlmacen;
        echo json_encode($array);
    }

    public function buscar_nota_entrada_almacen(Request $request) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $id_farmacia = Auth::user()->id_farmacia;
        $count = Guia_entrada::where('estado', '=', 1)->where('id_farmacia', '=', $id_farmacia)->count();
        $model_farma = new Farmacia;
        $farmacia = $model_farma->GetFarmaByIdfarma($id_farmacia);
        //------------------------------------------------------------------------
        $model_guias = new Guia_entrada();
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');   
        $fechaActual = Carbon::parse($fecha);

        if($farmacia->almacen==1){
            $fechaDesde = $request->fechaDesde;
            $fechaHasta = $request->fechaHasta;
            $pecosas = $model_guias->ShowAllNotaEntradaByIdAlmacen($id_farmacia,$fechaDesde,$fechaHasta);
        }
        
        $model_dependencia = new Dependencia;
        $dependencia = $model_dependencia->getDependencia('S');
        //-------------------------------------------------------------------------
        $ruta = "all_notas_entradas";
        
        if (count($ruta = explode("/", $ruta)) > 1)
            ;$ruta = $ruta[0];
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        if($farmacia->almacen==1){
            return view('frontend.stocks.all_notas_entradas_almacen', compact('pecosas', 'farmacia', 'permiso','dependencia','fechaActual','fechaDesde','fechaHasta'));
        }
    }

    public function print_ingresos_farmacia($fecha_inicio, $fecha_fin, $id_farmacia, $num_doc, $reg_sanitario, $lote, $id_tipo_producto,$id_producto) {
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
        $nombre_farmacia = $farma->nombre;
        //----------------------------------------------------        
        if ($id_tipo_producto == 0) $id_tipo_producto="";
        if ($id_producto == 0) $id_producto="";        
        $num_doc=$num_doc;
        $fechaDesde = date("Y-m-d", strtotime($fecha_inicio));
        $fechaHasta = date("Y-m-d", strtotime($fecha_fin));
        $model_guias = new Guia_entrada();      
        $ordenes_compra = $model_guias->ShowAllIngresosFarmacia($id_farmacia, $fechaDesde, $fechaHasta, $num_doc,$reg_sanitario,$lote, $id_producto,$id_tipo_producto);      
        
        //----------------------------------------------------
        return view('frontend.stocks.print_ingresos_farmacia', compact('ordenes_compra', 'farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'tipo_productos', 'conceptos','productosnivel','proveedores','dependencias','lote','id_tipo_proceso','num_siaf','num_doc','tipo_procesos','reg_sanitario','l_origen','id_user','idconcepto','id_tipo_proceso','id_producto','id_tipo_producto','id_proveedor'));
    }
/**
     * Carga de stock mejorado
     */
    public function cargarstock_farmacia2($id_producto, $id_user) {
		if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $model_kardex = new Kardexe();
        $usuarios = DB::table('users')->where('id', '=', $id_user)->first();
        $id_farmacia = $usuarios->id_farmacia;

        $farmacia = DB::table('farmacias')->where('id', '=', $id_farmacia)->first();
		$id_establecimiento = $farmacia->id_establecimiento;
		$id_farmacia_almacen = 113;

		$totalEstablecimiento = $model_kardex->ShowTotalCantidadByIdProductoAndIdEstablecimiento2($id_producto, $id_establecimiento, 0);
        $totalAlmacen = $model_kardex->ShowTotalCantidadByIdProductoAndIdEstablecimiento2($id_producto, $id_establecimiento, $id_farmacia_almacen);
        
		$cantidad = 0;
        $cantidadEstablecimiento = 0;
        foreach ($totalEstablecimiento as $row) {
            if ($row->id_farmacia == $id_farmacia) {
                $cantidad = $row->total_suma;
            }
            $cantidadEstablecimiento += $row->total_suma;
        }
		
		$cantidadAlmacen = isset($totalAlmacen[0]) ? $totalAlmacen[0]->total_suma : 0;
		
        $array = array();
        $array["cantidad"] = $cantidad;
		$array["cantidadEstablecimiento"] = $cantidadEstablecimiento;
		$array["cantidadAlmacen"] = $cantidadAlmacen;
        echo json_encode($array);
    }

    public function GetCountsForReporteStockLocal()
    {
        $model_kardex = new Kardexe();
        $farmacia = Farmacia::where('id', User::where('id', Auth::id())->first()->id_farmacia)->first();
        $fecha_fin = $model_kardex->getFechaServidor();
        $fecha_fin_venc = $model_kardex->getFechaServidorRestaMeses("-6");

        /*$countMedicamento = $model_kardex->ShowCountTotalKardexByFarmacia($farmacia->nombre, $fecha_fin, 1);
        $countinsumolaboratorio = $model_kardex->ShowCountTotalKardexByFarmacia($farmacia->nombre, $fecha_fin, 2);
        $countbiomedico = $model_kardex->ShowCountTotalKardexByFarmacia($farmacia->nombre, $fecha_fin, 3);
        $countMaterialLaboratorio = $model_kardex->ShowCountTotalKardexByFarmacia($farmacia->nombre, $fecha_fin, 4);
        $countMaterialDental = $model_kardex->ShowCountTotalKardexByFarmacia($farmacia->nombre, $fecha_fin, 5);
        $countMatFotograficoAndFonotecnico = $model_kardex->ShowCountTotalKardexByFarmacia($farmacia->nombre, $fecha_fin, 6);
		$countMaterialQuirurgico = $model_kardex->ShowCountTotalKardexByFarmacia($farmacia->nombre, $fecha_fin, 7);
        $countProductosAfines = $model_kardex->ShowCountTotalKardexByFarmacia($farmacia->nombre, $fecha_fin, 8);
        $cantidad_proximos_vencer = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechaVencimientoV2("", $farmacia->id, $fecha_fin_venc);
		$cantidad_productos_vencidos = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechaVencimientoV2("", $farmacia->id, $fecha_fin);

        $result = array(
            'countMedicamento' => $countMedicamento,
            'countinsumolaboratorio' => $countinsumolaboratorio,
            'countbiomedico' => $countbiomedico,
            'countMaterialLaboratorio' => $countMaterialLaboratorio,
            'countMaterialDental' => $countMaterialDental,
            'countMatFotograficoAndFonotecnico' => $countMatFotograficoAndFonotecnico,
            'countMaterialQuirurgico' => $countMaterialQuirurgico,
            'countProductosAfines' => $countProductosAfines,
            'cantidad_proximos_vencer' => $cantidad_proximos_vencer->count,
            'cantidad_productos_vencidos' => $cantidad_productos_vencidos->count,
        ); */

        $result = array(
            'countMedicamento' => 0,
            'countinsumolaboratorio' => 0,
            'countbiomedico' => 0,
            'countMaterialLaboratorio' => 0,
            'countMaterialDental' => 0,
            'countMatFotograficoAndFonotecnico' => 0,
            'countMaterialQuirurgico' => 0,
            'countProductosAfines' => 0,
            'cantidad_proximos_vencer' => 0,
            'cantidad_productos_vencidos' => 0,
        );

        return response()->json($result);
    }

    public function GetKardexsForReporte($tipo = 0)
    {
        $model_kardex = new Kardexe();
        $farmacia = Farmacia::where('id', User::where('id', Auth::id())->first()->id_farmacia)->first();
        $fecha_fin = $model_kardex->getFechaServidor();
        $kardexs = $model_kardex->ShowTotalKardexByFarmacia($farmacia->nombre, $fecha_fin, $tipo > 0 ? $tipo : 0);
        return response()->json($kardexs);
    }

    public function GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechaVencido()
    {
        $model_kardex = new Kardexe();
        $farmacia = Farmacia::where('id', User::where('id', Auth::id())->first()->id_farmacia)->first();
        $fecha_fin = $model_kardex->getFechaServidor();
        $stocks = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechaVencido("", $farmacia->id, $fecha_fin);
        return response()->json($stocks);
    }

/*    public function GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechasVencimiento()
    {
        $model_kardex = new Kardexe();
        $farmacia = Farmacia::where('id', User::where('id', Auth::id())->first()->id_farmacia)->first();
        $fecha_fin = $model_kardex->getFechaServidor();
        $fecha_inicio_venc = $fecha_fin;
        $fecha_fin_venc = $model_kardex->getFechaServidorRestaMeses("-6");
        $stocks = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechasVencimiento("", $farmacia->id, $fecha_inicio_venc, $fecha_fin_venc);
        return response()->json($stocks);
    }
*/

    public function GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechasVencimiento(Request $request)
    {
        $model_kardex = new Kardexe();
        $fechaIngresada = $request->ano.'-'.$request->mes;
        $meses = 0;
        /* $esBisiesto = checkdate(2, 29, $request->ano); */
        if ($fechaIngresada != '-') {
            $fechaActual = new DateTime();
            $fechaFutura = new DateTime($fechaIngresada . "-30"/* ($esBisiesto ? ($request->mes == "02" ? "-29" : "-30") : ($request->mes == "02" ? "-28s" : "-30")) */);
            $diferencia = $fechaActual->diff($fechaFutura);
            $meses = ($diferencia->y * 12) + $diferencia->m;
        }
        $farmacia = Farmacia::where('id', User::where('id', Auth::id())->first()->id_farmacia)->first();
        $fecha_inicio_venc = $model_kardex->getFechaServidor();
        $mesToSet = ($meses != 0 ? $meses : 0);
        if ($request->mesesTranscurridos != '') {
            $fecha_fin_venc = $model_kardex->getFechaServidorRestaMesesV2("-" . $request->mesesTranscurridos);
        } else {
            $fecha_fin_venc = $model_kardex->getFechaServidorRestaMesesV3($mesToSet);
        }
        $fechaParsed = new DateTime($fecha_fin_venc);
        $mesAndAnio = $fechaParsed->format("Y-m");
        $onlyMes = $fechaParsed->format("m");
        $stocks = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechasVencimientoV2(null, $farmacia->id, $fecha_inicio_venc, $onlyMes=="02" ? $mesAndAnio.'-28' : $mesAndAnio.'-30');
        $data = [
            "stocks" => $stocks,
        ];
        return response()->json($data);
    }

    public function newStocksByFarmacia($id_user = 0, $situacion = 0) {

        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;

        $id_user = Auth::user()->id;
        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1) {
            $ruta = $ruta[0];
        }
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == ""/* false */ ){
            return redirect('/dashboard')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        }

        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');

        $model_stock = new Stock_farmacia;
        $model_carga = new Carga_inicial_stock;
        $model_farma = new Farmacia;
        $model_establecimiento = new Establecimiento;
        $model_producto = new Producto;
        $model_kardex = new Kardexe();

        $farma = $model_farma->GetFarmaByUser($id_user);
        $id_farmacia = $farma->id;
        $nombre_farmacia = $farma->nombre;
        $id_establecimiento = $farma->id_establecimiento;
        $almacen = $farma->almacen;
        $establecimiento = $model_establecimiento->GetByIdfarmacia($id_farmacia);
        $carga = $model_carga->GetCargaRegistradaByFarmacia($id_farmacia);
        $estado_carga = 0;
        if (count($carga) > 0) {
            $estado_carga = $carga->estado;
        }
        $farmaciaActual = 1;
        $mes = 6;
        $fecha_inicio = $model_kardex->getFechaServidorRestaMeses(6);
        $fecha_fin = $model_kardex->getFechaServidor();
        $fecha_inicio_venc = $fecha_fin;
        $fecha_fin_venc = $model_kardex->getFechaServidorRestaMeses("-6");

        /* $model_kardex = new Kardexe(); */
        
        // ----------------------------------PROCESO DE CARGA PESADA PARA EL STOCK------------------------------------------------------------
        //PESADO 1
        /* $kardexs = $model_kardex->ShowTotalKardexByFarmacia($nombre_farmacia, $fecha_fin); */
        /* $resultado = array();
        foreach ($kardexs as $k) {
            $arrayarticulo = array('id_producto' => $k->id_producto,
                'codigo' => $k->codigo_producto,
                'nombre' => $k->nombre_producto,
                'unidad' => $k->unidad,
                'cantidad' => $k->total,
                'egresos' => $k->egresos,
                'nro_mes' => $k->nro_mes,
                'recetas_devueltas' => $k->recetas_devueltas,
            );

            $resultado[] = $arrayarticulo;
        } */
        // FIN PESADO 1

        // PESADO 2
        /* $countMedicamento = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 1);
        $countinsumolaboratorio = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 2);
        $countbiomedico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 3);
        $countMaterialLaboratorio = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 4);
        $countMaterialDental = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 5);
        $countMatFotograficoAndFonotecnico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 6);
		$countMaterialQuirurgico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 7);
        $countProductosAfines = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 8); */
        // FIN PESADO 2

        //PESADO 3
        /* $cantidad_proximos_vencer = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechaVencimientoV2("", $id_farmacia, $fecha_fin);
		$cantidad_productos_vencidos = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechaVencimientoV2("", $id_farmacia, $fecha_fin); */
        // FIN PESADO 3

        //----------------------------------------------------------------------
        
        return view('frontend.stocks.indexV2', compact('farma', 'fecha', 'id_farmacia', 'estado_carga', 'situacion', 'farmaciaActual', 'establecimiento','almacen'));
    }

    public function newStocksByFarmacias($id_farmacia, $situacion = 0)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        else:
            if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
            endif;
        endif;

        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');

        $model_stock = new Stock_farmacia;
        $model_carga = new Carga_inicial_stock;
        $model_farma = new Farmacia;
        $model_establecimiento = new Establecimiento;
        $model_producto = new Producto;
        $model_kardex = new Kardexe();

        $farma = $model_farma->GetFarmaByIdfarmacia($id_farmacia);
        $nombre_farmacia = $farma->nombre;
        $id_establecimiento = $farma->id_establecimiento;
        $almacen = $farma->almacen;
        $establecimiento = $model_establecimiento->GetByIdfarmacia($id_farmacia);
        $carga = $model_carga->GetCargaRegistradaByFarmacia($id_farmacia);
        $estado_carga = 0;
        if (count($carga) > 0) {
            $estado_carga = $carga->estado;
        }
        $farmaciaActual = 2;
        $mes = 6;
        $fecha_inicio = $model_kardex->getFechaServidorRestaMeses(6);
        $fecha_fin = $model_kardex->getFechaServidor();
        $fecha_inicio_venc = $fecha_fin;
        $fecha_fin_venc = $model_kardex->getFechaServidorRestaMeses("-6");
        
        return view('frontend.stocks.indexV2', compact('farma', 'fecha', 'id_farmacia', 'estado_carga', 'situacion', 'farmaciaActual', 'establecimiento','almacen'));
    }

    public function GetStockActualByIdEstablecimientoAndIdProducto(Request $request)
    {
        $model_kardexes = new Kardexe();
        $id_establecimiento = $request->id_establecimiento;
        $productos = $model_kardexes->GetStockActualByIdEstablecimientoAndIdProducto($id_establecimiento, 0);
        return response()->json($productos);
    }

    public function GetReporteKardexLotes(Request $request)
    {
        $id_farmacia = Auth::user()->id_farmacia;
        if($request->fechaCorte!=''){
            $fecha = $request->fechaCorte;    
        }   
        else{
            $date = Carbon::now();
            $fecha = $date->format('d-m-Y');
        }
        $model_kardex = new Kardexe();
        $stocks = $model_kardex->GetReporteKardexLotes($id_farmacia, $fecha);
        return response()->json($stocks);
    }

    public function buscarProductosPorLotes()
    {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $id_farmacia = Auth::user()->id_farmacia;
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        //----------------------------------------------------------------------
        /* $stocks = $model_kardex->GetReporteKardexLotes($id_farmacia, $fecha); */
        /* $existe_stock = count($stocks); */
        //----------------------------------------------------------------------
        /* $countMedicamento = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 1);
        $countbiomedico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 3);
        $countinsumolaboratorio = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 2);
        $countMaterialLaboratorio = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 4);
        $countMaterialDental = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 5);
        $countMatFotograficoAndFonotecnico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 6);
		$countMaterialQuirurgico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 7);
        $countProductosAfines = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 8); */
        //----------------------------------------------------------------------
        /* $fecha_inicio_venc = $fecha_fin;
        $fecha_fin_venc = $model_kardex->getFechaServidorRestaMeses("-6");
        $stocks_proximos_vencer = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechasVencimiento("", $id_farmacia, $fecha_inicio_venc, $fecha_fin_venc);
        $count_proximos_vencer = count($stocks_proximos_vencer);
		$stocks_productos_vencidos = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechaVencido("", $id_farmacia, $fecha_fin);
        $count_productos_vencidos = count($stocks_productos_vencidos); */
        //----------------------------------------------------------------------
        return view('frontend.stocks.productos_porlotesV2', compact('fecha', 'farma', 'id_farmacia'));
    }

    public function newProductosPorTipo($id_farmacia, $tipo) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $model_farma = new Farmacia;
        $model_kardex = new Kardexe();
        $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);

        //====================================
       /*  $kardexs = $model_kardex->ShowTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, $tipo); */
        //$kardexs = $model_kardex->ShowMedicamentosByTipo($nombre_farmacia, $tipo);
        //$movimientos = $model_kardex->ShowTipoMov();

        /* $resultado = array();
        foreach ($kardexs as $k) { */
            //$total = $model_kardex->ShowTotalByProd($k->codigo_producto, $nombre_farmacia, $fecha_inicio, $fecha_fin);
            //$ingresos = $model_kardex->ShowIngresosByProd($k->codigo_producto, $nombre_farmacia, $fecha_inicio, $fecha_fin);
            //$egresos = $model_kardex->ShowEgresosByProd($k->codigo_producto, $nombre_farmacia, $fecha_inicio, $fecha_fin);
            //$recetasDevueltas = $model_kardex->ShowRecetasDevueltas($k->codigo_producto, $nombre_farmacia, $fecha_inicio, $fecha_fin);
            //$nro_mes = $model_kardex->ShowNroMesByProd($k->codigo_producto, $nombre_farmacia, $fecha_inicio, $fecha_fin);
            //$can = $model_kardex->GetCantidadCAN($k->codigo_producto, $id_establecimiento);

           /*  $arrayarticulo = array('id_producto' => $k->id_producto,
                'codigo' => $k->codigo_producto,
                'nombre' => $k->nombre_producto,
                'unidad' => $k->unidad,
                'cantidad' => $k->total, */
                /* 'ingresos' => $ingresos->total, */
                /* 'egresos' => $egresos->total, */
                /* 'egresos' => $k->egresos, */
                /* 'nro_mes' => $nro_mes->nro_mes, */
                /* 'nro_mes' => $k->nro_mes, */
                /* 'recetas_devueltas' => $recetasDevueltas->recetas_devueltas, */
                /* 'recetas_devueltas' => $k->recetas_devueltas, */
                    //'can' => $can->total
           /*  );

            $resultado[] = $arrayarticulo;
        } */

        //----------------------------------------------------------------------
        /* $existe_stock = count($kardexs); */
        //----------------------------------------------------------------------
       /*  $countMedicamento = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 1);
        $countbiomedico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 3);
        $countinsumolaboratorio = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 2);
        $countMaterialLaboratorio = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 4);
        $countMaterialDental = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 5);
        $countMatFotograficoAndFonotecnico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 6);
		$countMaterialQuirurgico = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 7);
        $countProductosAfines = $model_kardex->ShowCountTotalKardexByFarmacia($nombre_farmacia, $fecha_fin, 8); */
        //----------------------------------------------------------------------
        switch ($tipo) {
            case 1:
                $nombre = 'MEDICAMENTOS';
                break;
            case 2:
                $nombre = 'INSUMOS DE LABORATORIO';
                break;
            case 3:
                $nombre = 'BIOMEDICOS';
                break;
            case 4:
                $nombre = 'MATERIAL DE LABORATORIO';
                break;
            case 5:
                $nombre = 'MATERIAL DENTAL';
                break;
            case 6:
                $nombre = 'MATERIAL FOTOGRAFICO Y FONOTECNICO';
                break;
			case 7:
                $nombre = 'MATERIAL QUIRURGICO';
                break;
            case 8:
                $nombre = 'PRODUCTOS AFINES';
                break;
        }
        //----------------------------------------------------------------------
        /* $fecha_inicio_venc = $fecha_fin;
        $fecha_fin_venc = $model_kardex->getFechaServidorRestaMeses("-6");
        $stocks_proximos_vencer = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechasVencimiento("", $id_farmacia, $fecha_inicio_venc, $fecha_fin_venc);
        $count_proximos_vencer = count($stocks_proximos_vencer); */
        /*$stocks_productos_vencidos = $model_kardex->ShowTotalKardexByFarmaciaSinStock($nombre_farmacia, $fecha_inicio_venc);
        $count_productos_vencidos = count($stocks_productos_vencidos);*/
        /* $stocks_productos_vencidos = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechaVencido("", $id_farmacia, $fecha_fin);
        $count_productos_vencidos = count($stocks_productos_vencidos); */
        //----------------------------------------------------------------------
        return view('frontend.stocks.productos_portipoV2', compact('farma', 'fecha', 'id_farmacia', 'nombre', 'tipo'));
    }

    public function buscarProductosProximosAVencer($id_farmacia) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $periodo = 1; //en 06 meses
        //----------------------------------------------------------------------
        $nuevafecha = strtotime('+180 day', strtotime($fecha));
        $nuevafecha = date('Y-m-j', $nuevafecha);
        $dias = 180;
        //----------------------------------------------------------------------
        $model_stock = new Stock_farmacia;
        $model_farma = new Farmacia;
        $model_kardex = new Kardexe();
        $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);

        //-contadores 
        
        // contadores fecha venc
        return view('frontend.stocks.productos_prox_vencerV2', compact('farma', 'fecha', 'id_farmacia', 'nuevafecha', 'periodo', 'dias'));
    }

    public function buscarProductosVencidos($id_farmacia) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $mostrar_fecha = $date->format('m-Y');
        $model_stock = new Stock_farmacia;
        $model_farma = new Farmacia;
        $model_kardex = new Kardexe();
        $fecha_fin = $model_kardex->getFechaServidor();

        $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
        return view('frontend.stocks.productos_vencidosV2', compact('stocks', 'farma', 'fecha', 'constock', 'sinstock', 'minstock', 'id_farmacia', 'countMedicamento', 'countbiomedico', 'countinsumolaboratorio', 'countMaterialLaboratorio', 'countMaterialDental', 'countMatFotograficoAndFonotecnico', 'existe_stock', 'count_proximos_vencer', 'count_productos_vencidos', 'mostrar_fecha','countMaterialQuirurgico','countProductosAfines'));
    }

    public function buscarStockFarmaciaPorEstablecimiento() {
        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;
        $id_user = Auth::user()->id;
        $model_stock = new Stock_farmacia;
        $model_farma = new Farmacia;       
        $farma = $model_farma->GetFarmaByUser($id_user);
        $id_establecimiento = $farma->id_establecimiento;
        $model = new Establecimiento();
        $establecimientos = $model->ShowAll();
        $ff = App\Models\Farmacia::GetFarmaciaByIdEstablecimiento($id_establecimiento);
        
        return view('frontend.stocks.reporte_stock_establecimientoV2', compact('establecimientos', 'id_establecimiento', 'ff'));
    }

    /**
     * 29jun2024
     * Copia de funcion buscarStockFarmaciaPorEstablecimiento
     * Es para probar tabla de stock historia kardex_points_2
     */
    public function buscarStockFarmaciaPorEstablecimiento_prueba() {
        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;
        $id_user = Auth::user()->id;
        $model_stock = new Stock_farmacia;
        $model_farma = new Farmacia;       
        $farma = $model_farma->GetFarmaByUser($id_user);
        $id_establecimiento = $farma->id_establecimiento;
        $model = new Establecimiento();
        $establecimientos = $model->ShowAll();
        $ff = App\Models\Farmacia::GetFarmaciaByIdEstablecimiento($id_establecimiento);
        
        return view('frontend.stocks.reporte_stock_establecimientoV2_prueba', compact('establecimientos', 'id_establecimiento', 'ff'));
    }

    /**
     * 29jun2024
     * Copia de funcion GetStockActualByIdEstablecimientoAndIdProducto
     * Es para probar tabla de stock historia kardex_points_2
     */
    public function GetStockActualByIdEstablecimientoAndIdProducto_prueba(Request $request)
    {
        $model_kardexes = new Kardexe();
        $id_establecimiento = $request->id_establecimiento;
        $productos = $model_kardexes->GetStockActualByIdEstablecimientoAndIdProducto_Prueba($id_establecimiento, 0);
        return response()->json($productos);
    }

    /**
     * 29jun2024 stockFarmaciaByEstablecimiento
     * Copia de funcion stockFarmaciaByEstablecimiento con cambio en la ultima lina
     * Es para probar tabla de stock historia kardex_points_2
     */
    public function stockFarmaciaByEstablecimiento_prueba(Request $request) {

        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;

        $id_user = Auth::user()->id;
        
        $model_stock = new Stock_farmacia;
        $model_farma = new Farmacia;
        if(!isset($request->id_establecimiento)){
            $farma = $model_farma->GetFarmaByUser($id_user);
            $id_establecimiento = $farma->id_establecimiento;
        } else {
            $id_establecimiento = $request->id_establecimiento;
        }
        
        $model = new Establecimiento();
        $establecimientos = $model->ShowAll();
        $ff = App\Models\Farmacia::GetFarmaciaByIdEstablecimiento($id_establecimiento);
        
        return view('frontend.stocks.reporte_stock_establecimientoV2_prueba', compact('establecimientos', 'id_establecimiento', 'ff'));
    }
}
