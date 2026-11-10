<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Stock;
use App\Models\Establecimiento;
use App\Models\Farmacia;
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
use Validator;
use DB;
use SoapClient;
use App;
use Auth;
use Illuminate\Support\Facades\Route;
use App\Models\Genera_correlativonea;
use App\Models\User;
use App\Models\Consumo_promedio;
use Maatwebsite\Excel\Facades\Excel;

class ConsumoPromedioController extends Controller {

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
    
    }

    public function add_producto_farmacia_cpm() {

        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;

        $id_farmacia = Auth::user()->id_farmacia;
        $modelfarma = new Farmacia();
        $model_producto = new Producto();
        $model_producto_consumo = new Consumo_promedio();
        $id_user = Auth::user()->id;
        $nivel = $modelfarma->ObtenerNivelFarmacia($id_farmacia);
        $nv = (isset($nivel->nivel) && $nivel->nivel!="")?$nivel->nivel:3;
        $productos = $model_producto->ProductosFarmaciasSegunNivel($nv);
        $productos_cpm = $model_producto_consumo->listarProductoPorIdFarmacia($id_farmacia);               
        $farmacia = $modelfarma->GetFarmaByUser($id_user);

        return view('frontend.consumos.registrar_productos_cpm', compact('productos', 'productos_cpm', 'id_farmacia', 'farmacia', 'count_proximos_vencer', 'count_productos_vencidos'));
    }

    public function agregar_producto_cpm(Request $request) {
        
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $id_user = Auth::user()->id;
        $data = Consumo_promedio::latest('id')->first();
        
        $model_consumo_promedios = new Consumo_promedio;
        $existe = $model_consumo_promedios->ExisteProductoRegistrado($request->id_producto,  $request->id_farmacia);

        if ($existe) {
            return redirect('/add_producto_cpm' )
                            ->withInput()
                            ->withErrors(array('No se ha podido registrar, Producto ya esta registrado, modifique si es necesario'));
        } else {
            $model_consumo_promedios->id = ($data->id+1);
            $model_consumo_promedios->cpm = $request->cpm;
            $model_consumo_promedios->id_producto = $request->id_producto;
            $model_consumo_promedios->id_farmacia = $request->id_farmacia;
            $model_consumo_promedios->estado = 1;
            $model_consumo_promedios->id_user_register = $id_user;
            $model_consumo_promedios->save();
            Session::flash('flash_message', 'Se agrego correctamento el producto');
            return redirect('/add_producto_cpm');
        }
    }

    public function eliminar_producto_consumo($id) {
        
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $id_user = Auth::user()->id;
        $model_consumo_promedios = Consumo_promedio::find($id);        
        $model_consumo_promedios->estado = 0;
        $model_consumo_promedios->id_user_modific = $id_user;
        $model_consumo_promedios->save();
        Session::flash('flash_message', 'Se elimino correctamente el producto');
        return redirect('/add_producto_cpm');
    }

    public function edit($id) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $model_consumo_promedio = new Consumo_promedio;
        
        $consumo_promedio = $model_consumo_promedio->BuscarProductoCP($id);

        return view('frontend.consumos.edit', ['consumo_promedio' => $consumo_promedio]);
    }

    
    public function update(Request $request) {
        
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $id_user = Auth::user()->id;
        $model_consumo_promedios = Consumo_promedio::find($request->id);        
        $model_consumo_promedios->cpm = $request->cpm;
        $model_consumo_promedios->id_user_modific = $id_user;
        $model_consumo_promedios->save();
        Session::flash('flash_message', 'Se actualizo correctamente');
        return redirect('/add_producto_cpm');
    }


    public function stocksbyFamarciabyCPM($id_user = 0, $situacion = 0) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $id_user = Auth::user()->id;

        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        //====================
        
        
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByUser($id_user);
        $nombre_farmacia = $farma->nombre;
        $id_establecimiento = $farma->id_establecimiento;
        $id_farmacia = $farma->id;
        $almacen = $farma->almacen;
        $model_establecimiento = new Establecimiento;
        $establecimiento = $model_establecimiento->GetByIdfarmacia($id_farmacia);
        
        //====================        
        $model_producto = new Producto;
        $model_server = new Kardexe();
        $model_kardex = new Consumo_promedio();

        $mes = 6;

        $fecha_fin = $model_server->getFechaServidor();
        //====================================        
        $kardexs = $model_kardex->ShowStockCpmByFarmacia($nombre_farmacia, $fecha_fin);        
        $resultado = array();

        foreach ($kardexs as $k) {
            $arrayarticulo = array('codigo' => $k->codigo_producto,
                'nombre' => $k->nombre_producto,
                'unidad' => $k->unidad,
                'tipo' => $k->tipo_producto,
                'cantidad' => $k->total,                
                'cpm' => $k->cpm,                
            );

            $resultado[] = $arrayarticulo;
        }
        //----------------------------------------------------------------------
        
        return view('frontend.consumos.index', compact('resultado', 'farma', 'fecha', 'id_farmacia', 'count_proximos_vencer', 'count_productos_vencidos', 'situacion', 'farmaciaActual', 'establecimiento','almacen'));
    }

    public function productos_vencidos_new($id_farmacia) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $mostrar_fecha = $date->format('m-Y');
        
        $model_farma = new Farmacia;
        $model_kardex = new Kardexe();
        $fecha_fin = $model_kardex->getFechaServidor();

        $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);        
        $stocks = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechaVencido("", $id_farmacia, $fecha_fin);                
                
        //----------------------------------------------------------------------
        $fecha_inicio_venc = $fecha_fin;
        $fecha_fin_venc = $model_kardex->getFechaServidorRestaMeses("-6");
        $stocks_proximos_vencer = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechasVencimiento("", $id_farmacia, $fecha_inicio_venc, $fecha_fin_venc);
        $count_proximos_vencer = count($stocks_proximos_vencer);        
        $stocks_productos_vencidos = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechaVencido("", $id_farmacia, $fecha_fin);
        $count_productos_vencidos = count($stocks);
        //----------------------------------------------------------------------
        return view('frontend.consumos.productos_vencidos_new', compact('stocks', 'farma', 'fecha', 'id_farmacia', 'count_proximos_vencer', 'count_productos_vencidos', 'mostrar_fecha'));
    }

    public function productos_proximos_vencer_new($id_farmacia) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');        
        //----------------------------------------------------------------------
        $nuevafecha = strtotime('+180 day', strtotime($fecha));
        $nuevafecha = date('Y-m-j', $nuevafecha);
        $dias = 180;
        //----------------------------------------------------------------------        
        $model_farma = new Farmacia;
        $model_kardex = new Kardexe();
        $fecha_fin = $model_kardex->getFechaServidor();
        $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
        
        $fecha_inicio_venc = $fecha_fin;
        $fecha_fin_venc = $model_kardex->getFechaServidorRestaMeses("-6");
        $stocks = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechasVencimiento("", $id_farmacia, $fecha_inicio_venc, $fecha_fin_venc);
        //----------------------------------------------------------------------
        $stocks_proximos_vencer = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechasVencimiento("", $id_farmacia, $fecha_inicio_venc, $fecha_fin_venc);
        $count_proximos_vencer = count($stocks_proximos_vencer);
        //-----------------------------------------------------------------------        
        $stocks_productos_vencidos = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechaVencido("", $id_farmacia, $fecha_fin);
        $count_productos_vencidos = count($stocks_productos_vencidos);

        //----------------------------------------------------------------------
        return view('frontend.consumos.productos_prox_vencer_new', compact('stocks', 'farma', 'fecha',  'id_farmacia', 'count_proximos_vencer', 'count_productos_vencidos', 'nuevafecha',  'dias'));
    }
    
    public function nuevos_registros_sb() {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;        

        $id_farmacia = Auth::user()->id_farmacia;
        $id_user = Auth::user()->id;

        //========================================================
        $date = Carbon::now();   
        
        $fechaDesde = $date->format('d-m-Y');
        $fechaHasta = $date->format('d-m-Y');
        $periodo=1;
        
        //========================================================
        $model_cp = new Consumo_promedio();
        
        $ingresos_amacen = $model_cp->ShowNuevoIngresoSB(113, $fechaDesde, $fechaHasta);            
        
        $stocks=count($ingresos_amacen);

        return view('frontend.consumos.productos_nuevos_sb', compact('id_farmacia','id_user','fechaDesde','fechaHasta','ingresos_amacen','periodo','stocks', 'count_proximos_vencer', 'count_productos_vencidos'));
        
    }

    public function busca_nuevo_registro_sb(Request $request) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;        

        $id_farmacia = Auth::user()->id_farmacia;
        $id_user = Auth::user()->id;

        //========================================================
        $date = Carbon::now();   

        $fechaDesde = $request->fechaDesde;
        $fechaHasta = $request->fechaHasta;        
        $periodo=1;        
        //========================================================
        $model_cp = new Consumo_promedio();
        //Ingreso desde almacen
        $ingresos_amacen = $model_cp->ShowNuevoIngresoSB(113, $fechaDesde, $fechaHasta);        

        $stocks=count($ingresos_amacen);

        return view('frontend.consumos.productos_nuevos_sb', compact('id_farmacia','id_user','fechaDesde','fechaHasta','ingresos_amacen','periodo','stocks', 'count_proximos_vencer', 'count_productos_vencidos'));
        
    }

    public function imprimir_stock_byidcpmfarmacia($id_farmacia,$situacion=0) {
      //====================
      $model_farma = new Farmacia;
      $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
      $nombre_farmacia = $farma->nombre;
      $id_establecimiento = $farma->id_establecimiento;
      $date = Carbon::now();
      $fecha = $date->format('d-m-Y');
    
      $model_producto = new Producto;
      $model_kardex = new Kardexe();    
    
    //====================
        Excel::create('Reporte de Stock', function($excel) use($id_farmacia, $nombre_farmacia, $fecha,$model_kardex,$id_establecimiento,$situacion) {
            $excel->sheet('GENERAL', function($sheet) use($id_farmacia, $nombre_farmacia, $fecha,$model_kardex,$id_establecimiento,$situacion) {
      
      $mes = 6;
      $fecha_inicio=$model_kardex->getFechaServidorRestaMeses(6);
      $fecha_fin=$model_kardex->getFechaServidor();
      
      //====================================
      $model_CP = new Consumo_promedio();
      $kardexs = $model_CP->ShowStockCpmByFarmacia($nombre_farmacia,$fecha_fin);
      $resultado = array();
      foreach ($kardexs as $k) {
            $arrayarticulo = array('codigo' => $k->codigo_producto,
                'nombre' => $k->nombre_producto,
                'unidad' => $k->unidad,
                'tipo' => $k->tipo_producto,
                'cantidad' => $k->total,                
                'cpm' => $k->cpm,                
            );

            $resultado[] = $arrayarticulo;
        }
    
      $time = time();
      $nuevoFormatoFecha= date("d-m-Y (H:i:s)", $time);
      $variable = [];
      $n = 1;
      array_push($variable, array($nombre_farmacia));
      array_push($variable, array("REPORTE DE PRODUCTO AL " . $nuevoFormatoFecha));
      array_push($variable, array(""));
      array_push($variable, array("N", "CÓDIGO", "PRODUCTO FARMACÉUTICO O DISPOSITIVO MÉDICO","TIPO PRODUCTO", "UNIDAD","STOCK ACTUAL","CPM"));


      foreach ($resultado as $r) {
          
            $cpm = "";
            $cpma = "";
            $meses_provicion = "";
            
            if (($r['cpm']) == 0) {
                $meses_provicion = $r['cantidad'];
            } else {
                $meses_provicion = round(($r['cantidad'] / $r['cpm']), 1);
            }
            
            $color = "";
            $situacionTemp = "";
           
            if ($meses_provicion > 0):
                if ($r['cpm']== 0):
                    $color = "#4472C4"; //Sin Rotacion Azul
                    $situacionTemp = 5;
                else:
                    if ($meses_provicion < 2):
                        $color = "#FFC000"; //SubStock amarillo
                        $situacionTemp = 4;
                    endif;
                    if ($meses_provicion >= 2 && $meses_provicion <= 6):
                        $color = "#70AD47"; //NormoStock verde
                        $situacionTemp = 3;
                    endif;
                     if ($meses_provicion > 6):
                        $color = "#ED7D31"; //SobreStock anaranjado
                        $situacionTemp = 2;
                    endif;
                endif;
            else:
                if ($r['cantidad'] <= 0):
                    $color = "#FF0000"; //rojo
                    $situacionTemp = 1;
                endif;
            endif;

            /**************************/            
            $color="";
            $situacionTemp="";
            if($situacion == 1):
                if ($r['cantidad'] <= 0):
                    $color = "#FF0000"; //rojo
                    $situacionTemp = 1;
                    array_push($variable, array($n++, $r['codigo'], $r['nombre'], $r['tipo'], $r['unidad'],"0",$r['cpm']));
                endif;
            endif;
            if($situacion == 2):
                if ($meses_provicion > 6):
                    $color = "#FFC000"; //SubStock amarillo
                    $situacionTemp = 2;
                    array_push($variable, array($n++, $r['codigo'], $r['nombre'], $r['tipo'], $r['unidad'],$r['cantidad'],$r['cpm']));              
                endif;
            endif;
            if($situacion == 3):
                if ($meses_provicion >=2):
                  if ($meses_provicion <= 6):
                    $color = "#FFC000"; //SubStock amarillo
                    $situacionTemp = 3;
                    array_push($variable, array($n++, $r['codigo'], $r['nombre'], $r['tipo'], $r['unidad'],$r['cantidad'],$r['cpm']));
                  endif;
                endif;
            endif;
            if($situacion == 4):
                if ($meses_provicion > 0):
                  if ($meses_provicion < 2):
                    $color = "#FFC000"; //SubStock amarillo
                    $situacionTemp = 4;
                    array_push($variable, array($n++, $r['codigo'], $r['nombre'], $r['tipo'], $r['unidad'],$r['cantidad'],$r['cpm']));
                  endif;
                endif;
            endif;
            if($situacion == 5):
                if ($meses_provicion > 6):
                    $color = "#4472C4"; //Sin Rotacion Azul
                    $situacionTemp = 5;
                    array_push($variable, array($n++, $r['codigo'], $r['nombre'], $r['tipo'], $r['unidad'],$r['cantidad'],$r['cpm']));
                endif;
            endif;
          
                }
                $sheet->setStyle(array(
                    'font' => array(
                        'name' => 'arial',
                        'size' => 12,
                        'bold' => true,
                        'text-align' => 'center'
                    )
                ));
                $sheet->with($variable)->mergeCells('A2:E2')->mergeCells('A3:E3');
                $sheet->setStyle(array(
                    'font' => array(
                        'name' => 'arial',
                        'size' => 12,
                        'bold' => false,
                        'text-align' => 'center'
                    )
                ));
            });
        })->export('xls');
    }

    public function descargar_nuevos_productos_ingresados_almacen($fechaDesde,$fechaHasta) {
        //====================
        $id_farmacia = Auth::user()->id_farmacia;
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
        $nombre_farmacia = $farma->nombre;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');

        $id_farmacia = Auth::user()->id_farmacia;
        $id_user = Auth::user()->id;

        //========================================================
        $date = Carbon::now();        
        $periodo=1;        
        //========================================================        
        Excel::create('Reporte de Productos con Stock Regular', function($excel) use($fechaDesde, $fechaHasta,$nombre_farmacia) {
            $excel->sheet('GENERAL', function($sheet) use($fechaDesde, $fechaHasta,$nombre_farmacia) {
                $model_cp = new Consumo_promedio();        
                $ingresos_amacen = $model_cp->ShowNuevoIngresoSB(113, $fechaDesde, $fechaHasta);                
                $time = time();
                $nuevoFormatoFecha= date("d-m-Y (H:i:s)", $time);
                $variable = [];
                $n = 1;
                array_push($variable, array($nombre_farmacia));
                array_push($variable, array("REPORTE DE PRODUCTOS QUE INGRESARON EN LA DROGUERIA UE-020 " . $nuevoFormatoFecha));
                array_push($variable, array(""));
                array_push($variable, array("N", "CODIGO", "NOMBRE", "UNIDAD","CANTIDAD"));
                
                foreach ($ingresos_amacen as $s) {
                    array_push($variable, array($n++, $s->codigo, $s->nombre, $s->abreviatura,$s->stock_cant));
                }
                $sheet->setStyle(array(
                    'font' => array(
                        'name' => 'arial',
                        'size' => 12,
                        'bold' => true,
                        'text-align' => 'center'
                    )
                ));
                $sheet->with($variable)->mergeCells('A2:E2')->mergeCells('A3:E3');
                $sheet->setStyle(array(
                    'font' => array(
                        'name' => 'arial',
                        'size' => 12,
                        'bold' => false,
                        'text-align' => 'center'
                    )
                ));
            });
        })->export('xls');
    }


    public function allMonitoreo_withStocks() {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $id_user = Auth::user()->id;

        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        //====================        
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByUser($id_user);
        $nombre_farmacia = $farma->nombre;
        $id_establecimiento = $farma->id_establecimiento;
        $id_farmacia = $farma->id;
        $almacen = $farma->almacen;
        $model_establecimiento = new Establecimiento;
        $establecimiento = $model_establecimiento->GetByIdfarmacia($id_farmacia);
        //----------------------------------------------------------------------        
        return view('frontend.consumos.panel', compact('resultado', 'farma', 'fecha', 'id_farmacia', 'count_proximos_vencer', 'count_productos_vencidos', 'situacion', 'farmaciaActual', 'establecimiento','almacen','situacion'));
    
    }

    
    public function allMonitoreo($situacion = 0, $nivel=0) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $id_user = Auth::user()->id;

        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        //====================        
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByUser($id_user);
        $nombre_farmacia = $farma->nombre;
        $id_establecimiento = $farma->id_establecimiento;
        $id_farmacia = $farma->id;
        $almacen = $farma->almacen;
        $model_establecimiento = new Establecimiento;
        $establecimiento = $model_establecimiento->GetByIdfarmacia($id_farmacia);
        
        //====================        
        $model_producto = new Producto;
        $model_server = new Kardexe();
        $model_cp = new Consumo_promedio();

        $mes = 6;
        $fecha_inicio = $model_server->getFechaServidorRestaMeses(6);
        $fecha_fin = $model_server->getFechaServidor();
        //====================================                
        $kardexs = $model_cp->ShowStockByFarmaciaNivel($fecha_inicio, $fecha_fin, $nivel);
        $resultado = array();

        foreach ($kardexs as $k) {
            $arrayarticulo = array('codigo' => $k->codigo_producto,
                'nombre' => $k->nombre_producto,
                'unidad' => $k->unidad,
                'cantidad' => $k->total,
                'tipo' => $k->tipo_producto,
                'egresos' => $k->egresos,
                'nro_mes' => $k->nro_mes,
                'recetas_devueltas' => $k->recetas_devueltas,
            );

            $resultado[] = $arrayarticulo;
        }

        //----------------------------------------------------------------------        
        return view('frontend.consumos.monitoreo', compact('resultado', 'farma', 'fecha', 'id_farmacia', 'count_proximos_vencer', 'count_productos_vencidos', 'situacion', 'farmaciaActual', 'establecimiento','almacen','situacion','nivel'));
    }

    public function allNivel_withStocks() {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $model_stock = new Stock_farmacia;
        $model_farmacia = new Farmacia;
        $model_kardex = new Kardexe();
        $model_cp = new Consumo_promedio();
        $fecha_inicio = $model_kardex->getFechaServidorRestaMeses(6);
        $fecha_fin = $model_kardex->getFechaServidor();

        $id_user = Auth::user()->id;
        $id_farmacia = Auth::user()->id_farmacia;

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

        for($i=0;$i<4;$i++) {
        //    foreach ($allfarmacias as $f) {
            //$id_farmacia = $f->id;
            //$nombre = $f->nombre;
            $total = $model_cp->ShowCountTotalNivel($i, $fecha_fin);
            $desabastecido = 0;
            $sobreStock = 0;
            $normoStock = 0;
            $subStock = 0;
            $sinRotacion = 0;            
            $kardexs = $model_cp->ShowStockByFarmaciaNivel($fecha_inicio, $fecha_fin, $i);

            foreach ($kardexs as $k) {
                
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

            switch ($i) {
                case 0:
                    $nombre='ALMACEN SAN BORJA';
                    break;
                
                case 1:
                    $nombre='NIVEL I';
                    break;

                case 2:
                    $nombre='NIVEL II';
                    break;

                case 3:
                    $nombre='NIVEL III';
                    break;
            }
                
            $farma_productos = array('id_farmacia' => $id_farmacia, 'nombre' => $nombre, 'total' => $total, 'desabastecido' => $desabastecido, 'sobreStock' => $sobreStock, 'normoStock' => $normoStock, 'subStock' => $subStock, 'sinRotacion' => $sinRotacion, 'nivel' => $i);
            $listado[] = $farma_productos;
        }
        return view('frontend.consumos.consolida_nivel', compact('listado'));
    }

}
