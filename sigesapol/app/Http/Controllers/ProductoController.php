<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Medico;
use App\Models\Establecimiento;
use App\Models\Tipo_producto;
use App\Models\Unidade;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use Auth;

class ProductoController extends Controller
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

    public function index()
    {
        //
        if (!Auth::check()) :
            return redirect('/auth/login');
        else:
            if (Auth::user()->actualizo_passw == 0):
                return redirect('auth/password/change');
            endif;
        endif;

        $models = new Tipo_producto;
        $model = new Producto;
        $productos = $model->ShowAllMantenimiento();
        $tipos = $models->ShowAll();

        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1);
        $ruta = $ruta[0];
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "") return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        return view('frontend.productos.all', ['productos' => $productos, 'tipos' => $tipos, 'permiso' => $permiso, 'ruta' => $ruta]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $model_tipo = new Tipo_producto;
        $model_unidad = new Unidade;
        $tipos = $model_tipo->ShowAll();
        $unidades = $model_unidad->ShowAll();

        $producto = Producto::latest('id')->first();
        $ultimo_codigo = $producto->codigo + 1;

        return view('frontend.productos.create', ['tipos' => $tipos, 'unidades' => $unidades, 'ultimo_codigo' => $ultimo_codigo]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $codigo = $request->codigo_nuevo;
        $producto = new Producto;
        $producto->codigo = $request->codigo_nuevo;
        $producto->nombre = $request->nombre;
        $producto->id_tipo = $request->id_tipo;
        $producto->id_unidad = $request->id_unidad;
        $producto->codigo_siga = $request->codigo_siga;
        $producto->cod_trama = $request->codigo_trama;
        $producto->desc_trama = $request->descripcion_trama;
        $producto->precio_trama = ($request->precio_trama != '') ? $request->precio_trama : 0;
        $producto->nivel = $request->id_nivel;
        $producto->ru = $request->ru;
        $producto->concentracion = $request->concentracion;
        $producto->form_farm = $request->form_farm;
        $producto->presentacion = $request->presentacion;
        $producto->estado = "1";
        $producto->estado_egestion = 1;
        $producto->observacion = $request->observacion;
        $producto->descripcion_siga = $request->nombre_siga;
        $producto->petitorio = $request->petitorio;
        $producto->covid = $request->covid;
        $producto->created_us = Auth::user()->id;

        $existe = $producto->ExisteProducto($codigo, 0);
        if ($existe) {

            return redirect('/create_producto')
                ->withInput()
                ->withErrors(array('El producto ya existe'));
        }

        $producto->save();
        return redirect('/all_productos')->withFlashSuccess("Se registro correctamente el producto");
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

    public function edit($id)
    {
        //
        $productos = Producto::findOrFail($id);
        $id_tipo = $productos->id_tipo;
        $id_unidad = $productos->id_unidad;
        //
        $model_tipo = new Tipo_producto;
        $model_unidad = new Unidade;
        //
        $tipos = $model_tipo->ShowAll();
        $unidades = $model_unidad->ShowAll();
        $tipo = $model_tipo->GetById($id_tipo);
        $unidad = $model_unidad->GetById($id_unidad);
        $datos = [$unidades, $tipos, $unidad, $tipo];


        //dd($productos);
        //var_dump($unidad); exit;
        return view('frontend.productos.edit', ['productos' => $productos], ['datos' => $datos]);
    }

    public function update(Request $request)
    {

        $productos = Producto::find($request->id);
        $codigo = $request->codigo;
        $codigo_actual = $request->codigo_actual;
        $codigo_nuevo = $request->codigo_nuevo;
        $descripcion_anterior = $productos->nombre;

        if ($request->tipo_registro == 11 || $request->admin == 1): //comite
            $productos->nombre = $request->nombre;
            $productos->id_tipo = $request->id_tipo;
            $productos->id_unidad = $request->id_unidad;
            $productos->cod_trama = $request->codigo_trama;
            $productos->desc_trama = $request->descripcion_trama;
            $productos->nivel = $request->id_nivel;
            $productos->ru = $request->ru;
            $productos->concentracion = $request->concentracion;
            $productos->form_farm = $request->form_farm;
            $productos->presentacion = $request->presentacion;
            if ($request->estado == null):
                $productos->estado = 0;
                $productos->deleted_at = Carbon::now();
                $productos->deleted_us = Auth::user()->id;
            else:
                $productos->estado = 1;
            endif;
            $productos->estado_egestion = 1;
            $productos->presentacion_comercial = $request->presentacion_comercial;
            $productos->observacion = $request->observacion;
            $productos->restriccion = $request->restriccion;
            $productos->petitorio = $request->petitorio;
            $productos->covid = $request->covid;
            $productos->updated_at = Carbon::now();
            $productos->updated_us = Auth::user()->id;
            if ($request->admin == 1): //admin
                $productos->codigo_siga = $request->codigo_siga;
                $productos->descripcion_siga = $request->nombre_siga;
                $productos->updated_ejecutora = Carbon::now();
                $productos->updated_us_ejecutora = Auth::user()->id;
            endif;
        else:
            if ($request->tipo_registro == 12): //siga
                $productos->codigo_siga = $request->codigo_siga;
                $productos->descripcion_siga = $request->nombre_siga;
                $productos->updated_siga = Carbon::now();
                $productos->updated_us_siga = Auth::user()->id;
            else:
                $productos->precio_trama = ($request->precio_trama != '') ? $request->precio_trama : 0;  //ejecutora
                $productos->codigo_siga = $request->codigo_siga;
                $productos->descripcion_siga = $request->nombre_siga;
                $productos->updated_ejecutora = Carbon::now();
                $productos->updated_us_ejecutora = Auth::user()->id;
            endif;
        endif;

        if ($codigo_nuevo != "") {
            $existe = $productos->ExisteProducto($codigo_nuevo, 0);
            if ($existe) {
                return redirect('/all_productos')->withInput()
                    ->withErrors(array('El producto con el código digitado ya existe'));
            }
            if ($request->tipo_registro == 11 || $request->admin == 1):
                $productos->codigo = $codigo_nuevo;
                $productos->old_cod = $codigo_actual;
                $productos->old_nom = $descripcion_anterior;
            endif;
            $productos->save();
        } else {
            $productos->save();
        }
        return redirect('/all_productos')->withFlashSuccess("Se actualizo correctamente el producto");
    }

    public function destroy($id)
    {
        //
    }

    public function search_tipo(Request $request)
    {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $id_tipo = $request->id_tipo;
        $model = new Producto;
        //$productos = $model->ShowByTipo($id_tipo);
        $productos = $model->ShowAllMantenimiento($id_tipo);

        $model_tipo = new Tipo_producto;
        $tipos = $model_tipo->ShowAll();

        //$ruta = Route::getCurrentRoute()->getPath();
        $ruta = $request->ruta;
        if (count($ruta = explode("/", $ruta)) > 1);
        $ruta = $ruta[0];
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "") return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        return view('frontend.productos.all', ['productos' => $productos, 'tipos' => $tipos, 'permiso' => $permiso, 'ruta' => $ruta]);
    }

    public function create_lote()
    {
        $model = new Producto;
        $productos = $model->ShowAll();
        return view('frontend.lotes.create', ['productos' => $productos]);
    }

    public function obtener_producto_por_idipress_idconsultorio($id_ipress, $id_consultorio)
    {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        //if (!Auth::check()) {return redirect('/inicio')->withFlashSuccess("La session caduco, vuelva a iniciar session");}

        $id_farmacia = Auth::user()->id_farmacia;
        $model_producto = new Producto;
        $model_establecimiento = new Establecimiento;
        $establecimiento = $model_establecimiento->GetById($id_ipress);

        if ($id_ipress == 1) {
            $nivel = 2;
        } else {
            $nivel = $establecimiento[0]->nivel;
        }
        $producto = $model_producto->getProductosByNivelAndIdServicio($nivel, $id_consultorio, $id_farmacia);

        return $producto;
    }

    public function obtener_producto_por_idipress_idconsultorio_idfarmacia($id_ipress, $id_farmacia, $id_consultorio)
    {

        //$id_farmacia = Auth::user()->id_farmacia;
        $model_producto = new Producto;
        $model_establecimiento = new Establecimiento;
        $establecimiento = $model_establecimiento->GetById($id_ipress);

        if ($id_ipress == 1) {
            $nivel = 2;
        } else {
            $nivel = $establecimiento[0]->nivel;
        }
        $producto = $model_producto->getProductosByNivelAndIdServicio($nivel, $id_consultorio, $id_farmacia);

        return $producto;
    }

    public function obtener_producto_por_idipress_idconsultorio_idfarmacia_nutricial($id_ipress, $id_farmacia, $id_consultorio)
    {

        $model_producto = new Producto;
        $model_establecimiento = new Establecimiento;
        $establecimiento = $model_establecimiento->GetById($id_ipress);

        if ($id_ipress == 1) {
            $nivel = 2;
        } else {
            $nivel = $establecimiento[0]->nivel;
        }
        $producto = $model_producto->getProductosByNivelAndIdServicioNutricial($nivel, $id_farmacia);

        return $producto;
    }

    public function obtener_producto_por_hospitalizacion_mas_idconsultorio($id_farmacia, $id_consultorio, $id_consultorio2)
    {

        $model_producto = new Producto;
        $producto = $model_producto->getProductoHospitalizacionmasRestringido($id_farmacia, $id_consultorio, $id_consultorio2);

        return $producto;
    }

    public function obtener_producto_por_idipress_idconsultorio_medico($id_ipress, $id_consultorio, $id_consultorio2, $id_medico)
    {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $id_farmacia = Auth::user()->id_farmacia;
        $model_producto = new Producto;
        $model_establecimiento = new Establecimiento;
        $model_medico = new Medico;
        $establecimiento = $model_establecimiento->GetById($id_ipress);
        $medico = $model_medico->GetMedicoById($id_medico);
        $nivel = $establecimiento[0]->nivel;

        if ($medico->rne == '') {
            //$producto = $model_producto->getProductosByNivelAndIdServicioSinRestringido($nivel, $id_consultorio,$id_farmacia);
            $producto = $model_producto->getProductosByNivelAndIdServicioSinRestringido($nivel, $id_consultorio, $id_farmacia);
        } else {
            $producto = $model_producto->getProductosByNivelAndIdServicioConRestringido($nivel, $id_consultorio, $id_consultorio2, $id_farmacia);
        }


        //dd($producto);
        return $producto;
    }

    public function obtener_producto_por_idipress_idconsultorio_mas_interconsulta($id_ipress, $id_consultorio, $dni)
    {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $id_farmacia = Auth::user()->id_farmacia;
        $model_producto = new Producto;
        $model_establecimiento = new Establecimiento;
        $establecimiento = $model_establecimiento->GetById($id_ipress);
        $nivel = $establecimiento[0]->nivel;
        $producto = $model_producto->getProductosByNivelAndIdServicioHospitalizacion($id_consultorio, $id_farmacia, $dni);
        return $producto;
    }

    public function obtener_producto_por_idipress_idconsultorio_quirurgico($id_ipress, $id_farmacia, $id_consultorio)
    {
        $model_producto = new Producto;
        $model_establecimiento = new Establecimiento;
        $establecimiento = $model_establecimiento->GetById($id_ipress);

        if ($id_ipress == 1) {
            $nivel = 2;
        } else {
            $nivel = $establecimiento[0]->nivel;
        }
        $productos_sin_paquetizacion = $model_producto->getProductosByNivelAndIdServicioCQ($nivel, $id_consultorio, $id_farmacia);
        $productos_con_aquetizacion = $model_producto->get_productos_paquetizacion_anestesia($nivel, $id_consultorio, $id_farmacia);

        return array(
            'productos_sin_paquetizacion' => $productos_sin_paquetizacion,
            'productos_con_aquetizacion' => $productos_con_aquetizacion
        );
    }

    public function obtener_producto_pc($id_producto)
    {
        $model_producto = new Producto;
        $sw = false;
        $array = array();
        $productos = Producto::findOrFail($id_producto);
        if ($productos->presentacion_comercial == 1)
            $sw = true;

        $array["sw"] = $sw;

        echo json_encode($array);
    }

    public function establecimiento_producto()
    {

        if (!Auth::check()) :
            return redirect('/auth/login');
        else:
            if (Auth::user()->actualizo_passw == 0):
                return redirect('auth/password/change');
            endif;
        endif;

        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1);
        $ruta = $ruta[0];
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        $id_user = Auth::user()->id;

        $nombre_producto = "";
        $id_producto = "";
        return view('frontend.productos.establecimiento_producto', compact('nombre_producto', 'id_producto'));
    }

    public function listar_establecimiento_producto(Request $request)
    {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        if (!$request->id_producto):
            return redirect('/establecimiento_producto');
        endif;
        $nombre_producto = $request->nombre_producto;
        $id_producto = $request->id_producto;

        $model_productos = new Producto();
        $cod_producto = explode(" ", $request->nombre_producto);
        $codigo = $cod_producto[0];

        $p[] = $codigo;

        $result = $model_productos->buscar_producto_establecimiento($p);

        return view('frontend.productos.establecimiento_producto', compact('result', 'nombre_producto', 'id_producto'));
    }
    public function producto_por_farmacia($idProducto)
    {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $p[] = Auth::user()->id_establecimiento;
        $p[] = $idProducto;

        $model_productos = new Producto();
        $result = $model_productos->buscar_stock_producto_farmacia($p);

        return $result;
    }

    public function search(Request $request)
    {
        $searchTerm = $request->query('search');

        $query = Producto::query();

        if ($searchTerm) {
            $query->where(function ($subQuery) use ($searchTerm) {
                $subQuery->whereRaw('nombre ILIKE ?', ["%$searchTerm%"])
                    ->orWhereRaw('codigo ILIKE ?', ["%$searchTerm%"]);
            });
        }

        $productos = $query->get();

        return response()->json($productos, 200);
    }

    public function search_tipo_medicina($buscar)
    {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $model = new Producto;
        $productos = $model->getProductoMedicinaByNombre($buscar);
        $array = array();
        foreach ($productos as $leer) {
            $array[] = array(
                "value" => $leer->codigo . " - " . $leer->nombre,
                "label" => $leer->id . " - " . $leer->nombre,
                "id" => $leer->id,
            );
        }

        return json_encode($array);
    }

    /*public function establecimiento_producto_total() {

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
        $id_user = Auth::user()->id;

        //$nombre_producto = "";
        //$id_producto = "";
        $model_productos = new Producto();
        $result = $model_productos->exportar_total_producto_establecimiento();

        dd($result);

        return view('frontend.productos.establecimiento_producto_total', compact('result'));
    }
    */
    /*public function listar_establecimiento_producto($codigo){
        
        $model_productos = new Producto();
        //$p[]=$request->codigo;
        $p[]=$codigo;
        $data = $model_productos->buscar_producto_establecimiento($p);
        
        $result["aaData"] = $data;

        echo json_encode($result);
        
    }
    */

    public function obtener_producto_farmacotecnia($id_ipress)
    {

        $model_producto = new Producto;
        $producto = $model_producto->getProductosByFarmacotecnia($id_ipress);

        return $producto;
    }

    public function obtener_productos_atencion_quirurgica($id_ipress, $id_farmacia, $id_consultorio)
    {
        $model_producto = new Producto;
        $model_establecimiento = new Establecimiento;
        $establecimiento = $model_establecimiento->GetById($id_ipress);

        if ($id_ipress == 1) {
            $nivel = 2;
        } else {
            $nivel = $establecimiento[0]->nivel;
        }
        $productos_sin_paquetizacion = $model_producto->getProductosByNivelAndIdServicioCQ($nivel, $id_consultorio, $id_farmacia);

        return array(
            'productos_sin_paquetizacion' => $productos_sin_paquetizacion
        );
    }
}
