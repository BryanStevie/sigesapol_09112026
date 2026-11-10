<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Transferencia;
use App\Models\Clasificador_gasto;
use App\Models\Transferencia_producto;
use App\Models\Farmacia;
use App\Models\Establecimiento;
use App\Models\Dependencia;
use App\Models\Concepto;
use App\Models\Consultorio;
use App\Models\Medico;
use Carbon\Carbon;
use App\Models\Kardexe;
use App\Models\Guia_entrada;
use App\Models\Carga_pecosa;
use App\Models\Producto;
use App\Models\Proveedore;
use App\Models\Tipo_producto;
use App\Models\Kardex_lote;
use App\Models\Tipo_proceso;
use Session;
use DB;
use App\Models\Stock_lote;
use App\Models\Stock_farmacia;
use Auth;
use Illuminate\Support\Facades\Route;
use App\Models\MotivoTransferencia;
use App\Models\Genera_correlativonea;
use App\Models\User;

class TransferenciaController extends Controller
{

  function __construct()
    {
        $this->middleware('auth'); 
    } 
    
  public function enviar_productos_al_stock(Request $request)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $obtenerId = DB::Transaction(function () use ($request) {
      $date = Carbon::now();
      $fecha_movimiento = $date->format('d-m-Y');
      $id_user = Auth::user()->id;
      $id_farmacia = Auth::user()->id_farmacia;

      //------------------------------------------------------------------
      $model_transferencia = new Transferencia;
      $model_transferencia_productos = new Transferencia_producto;
      $model_receta_vale = new \App\Models\Receta_vale;

      $productos = $model_transferencia_productos->ObtenerProductoPorIdTransferenciaAgrupadoByProducto($request->id_transferencia);
      //----------Descontar Stock Farmacia Origen ------------------------
      foreach ($productos as $p) {
        //sacer agrupado y desagrupado por idproducto

        $productosLote = $model_transferencia_productos->ObtenerProductoPorIdTransferenciaAndIdProducto($request->id_transferencia, $p->id_producto);

        $model_farmacia = new \App\Models\Farmacia;
        $id_farmaciaori = $model_farmacia->GetIdFarmadByNomFarma($request->nombre_farmacia_origen);
        $id_farmaciaori_almacen = $id_farmaciaori->almacen;
        $id_farmaciaori_almacen_ipress = $id_farmaciaori->almacen_ipress;
        $id_farmaciaori = $id_farmaciaori->id;
        $id_farmaciades = $model_farmacia->GetIdFarmadByNomFarma($request->nombre_farmacia_destino);
        $id_establecimiento = $id_farmaciades->id_establecimiento;
        $id_farmaciades = $id_farmaciades->id;
        
        $model_producto_anaquel = new \App\Models\Producto_anaquele;
        $existe_anaquelori = $model_producto_anaquel->anaquelByIdProductoAndIdFarmacia($p->id_producto, $id_farmaciaori);
        $model_producto_anaquel = new \App\Models\Producto_anaquele;
        $existe_anaqueldes = $model_producto_anaquel->anaquelByIdProductoAndIdFarmacia($p->id_producto, $id_farmaciades);

        if ($id_farmaciaori_almacen != 1 && $id_farmaciaori_almacen_ipress !=1) {

          $existe_kardex = DB::table('kardexes')
            ->where('id_nro_movimiento', $request->id_transferencia)
            ->where('id_producto', $p->id_producto)
            ->where('id_tipo_movimiento', 7)
            ->where('id_origen', $id_farmaciaori)
            ->where('id_destino', $id_farmaciades)
            ->where('id_farmacia', $request->id_farmacia_origen)
            ->where('estado', 1)->first();

          //print_r($existe_kardex);exit();
          if ($existe_kardex == false) {

            $insert_kardex_descontar = new Kardexe;
            $insert_kardex_descontar->codigo_producto = $p->codigo_producto;
            $insert_kardex_descontar->nombre_producto = $p->nombre_producto;
            $insert_kardex_descontar->tipo_movimiento = 'TRANS';
            $insert_kardex_descontar->numero_movimiento = $p->nro_acta;
            $insert_kardex_descontar->origen = $request->nombre_farmacia_origen;
            $insert_kardex_descontar->destino = $request->nombre_farmacia_destino;
            $insert_kardex_descontar->cantidad = $p->cantidad;
            $insert_kardex_descontar->estado = 1;
            $insert_kardex_descontar->nombre_farmacia = $request->nombre_farmacia_origen;
            $insert_kardex_descontar->fecha_movimiento = $fecha_movimiento;
            $insert_kardex_descontar->id_tipo_movimiento = 7;

            $insert_kardex_descontar->id_producto = $p->id_producto;
            $insert_kardex_descontar->id_nro_movimiento = $request->id_transferencia;
            $insert_kardex_descontar->id_origen = $id_farmaciaori;
            $insert_kardex_descontar->id_destino = $id_farmaciades;
            $insert_kardex_descontar->id_farmacia = $request->id_farmacia_origen;
            if ($existe_anaquelori) $insert_kardex_descontar->id_anaquel = $existe_anaquelori->id_anaquel;
            $insert_kardex_descontar->id_user_created = $id_user;
            $insert_kardex_descontar->created_at = Carbon::now();
            $insert_kardex_descontar->save();
            $idkardex_descontar = $insert_kardex_descontar->id;
          }
        }
        //----------Agregar al Kardex ------------------------
        $existe_kardex_point_ = DB::table('kardex_points')
          ->where('id_establecimiento', $id_establecimiento)
          ->where('id_producto', $p->id_producto)
          ->where('id_farmacia', $id_farmacia)->first();

        if ($existe_kardex_point_ == false) {
          $Kardex_points = new \App\Models\Kardex_points;
          $Kardex_points->id_establecimiento = $id_establecimiento;
          $Kardex_points->id_producto = $p->id_producto;
          $Kardex_points->id_farmacia = $id_farmacia;
          $Kardex_points->id_kardex = 13822153;
          $Kardex_points->total = 0;
          $Kardex_points->save();
        }

        $existe_kardex_ = DB::table('kardexes')
          ->where('id_nro_movimiento', $request->id_transferencia)
          ->where('id_producto', $p->id_producto)
          ->where('id_tipo_movimiento', 9)
          ->where('id_origen', $id_farmaciaori)
          ->where('id_destino', $id_farmaciades)
          ->where('id_farmacia', $id_farmacia)
          ->where('estado', 1)->first();

        if ($existe_kardex_ == false) {

          $insert_kardex_aumentar = new Kardexe;
          $insert_kardex_aumentar->codigo_producto = $p->codigo_producto;
          $insert_kardex_aumentar->nombre_producto = $p->nombre_producto;
          $insert_kardex_aumentar->tipo_movimiento = 'ING. TRANS';
          $insert_kardex_aumentar->numero_movimiento = $p->nro_acta;
          $insert_kardex_aumentar->origen = $request->nombre_farmacia_origen;
          $insert_kardex_aumentar->destino = $request->nombre_farmacia_destino;
          $insert_kardex_aumentar->cantidad = $p->cantidad;
          $insert_kardex_aumentar->estado = 1;
          $insert_kardex_aumentar->nombre_farmacia = $request->nombre_farmacia_destino;
          $insert_kardex_aumentar->fecha_movimiento = $fecha_movimiento;
          $insert_kardex_aumentar->id_tipo_movimiento = 9;

          $insert_kardex_aumentar->id_producto = $p->id_producto;
          $insert_kardex_aumentar->id_nro_movimiento = $request->id_transferencia;
          $insert_kardex_aumentar->id_origen = $id_farmaciaori;
          $insert_kardex_aumentar->id_destino = $id_farmaciades;
          $insert_kardex_aumentar->id_farmacia = $id_farmacia;
          if ($existe_anaqueldes) $insert_kardex_aumentar->id_anaquel = $existe_anaqueldes->id_anaquel;
          $insert_kardex_aumentar->id_user_created = $id_user;
          $insert_kardex_aumentar->created_at = Carbon::now();
          $insert_kardex_aumentar->save();
          $idkardex_aumentar = $insert_kardex_aumentar->id;

          foreach ($productosLote as $key => $rowProductosLote) {

            if ($id_farmaciaori_almacen != 1 && $id_farmaciaori_almacen_ipress != 1) {
              $kardex_lote = new \App\Models\Kardex_lote;
              $kardex_lote->id_kardex = $idkardex_descontar;
              $kardex_lote->lote = $rowProductosLote->lote;
              $kardex_lote->fecha_vencimiento = $rowProductosLote->fecha_vencimiento;
              $kardex_lote->registro_sanitario = $rowProductosLote->registro_sanitario;
              $kardex_lote->cantidad = $rowProductosLote->cantidad;
              $kardex_lote->precio_unitario = $rowProductosLote->precio_unitario;
              $kardex_lote->fecha_registro = $fecha_movimiento;
              $kardex_lote->id_ingreso_salida = $rowProductosLote->id;
              $kardex_lote->presentacion_comercial = $rowProductosLote->presentacion_comercial;
              $kardex_lote->save();
            }
            $kardex_lote = new \App\Models\Kardex_lote;
            $kardex_lote->id_kardex = $idkardex_aumentar;
            $kardex_lote->lote = $rowProductosLote->lote;
            $kardex_lote->fecha_vencimiento = $rowProductosLote->fecha_vencimiento;
            $kardex_lote->registro_sanitario = $rowProductosLote->registro_sanitario;
            $kardex_lote->cantidad = $rowProductosLote->cantidad;
            $kardex_lote->precio_unitario = $rowProductosLote->precio_unitario;
            $kardex_lote->fecha_registro = $fecha_movimiento;
            $kardex_lote->id_ingreso_salida = $rowProductosLote->id;
            $kardex_lote->presentacion_comercial = $rowProductosLote->presentacion_comercial;
            $kardex_lote->save();
          }




          //----------Aumentar Stock Farmacia Destino -----------------------
          $id_user = Auth::user()->id;
          $modelfarma = new Farmacia();
          $farmacias = $modelfarma->GetFarmaByUser($id_user);
          $id_establecimiento = $farmacias->id_establecimiento;
          $id_farmacia = $farmacias->id;
          $transferencia = Transferencia::where('id', $request->id_transferencia)->first();
          $transferencia->estado = 4;

          $transferencia->ano_nota = $model_receta_vale->getAnoServidor(); //Wilian
          $transferencia->nota_entrada = str_pad($request->nota_entrada, 5, "0", STR_PAD_LEFT);
          $transferencia->save();

          $nea = Genera_correlativonea::where('anio_nea', $model_receta_vale->getAnoServidor())->where('id_farmacia', $id_farmacia)->first();
          if (!isset($nea)) {
            $nea = new \App\Models\Genera_correlativonea;
            $nea->anio_nea = date("Y");
            $nea->id_farmacia = $id_farmacia;
            $nea->estado = 1;
            $nea->id_user_created = $id_user;
          }
          $nea->nro_nea = $request->nota_entrada;
          $nea->save();
        }
      }
    });
    Session::flash('flash_message', 'Transferencia exitosa..!!');
    return redirect('/all_transferencias_recibidas/' . $request->id_farmacia_destino);
  }

  public function mostrar_productos_recibidos($id_transferencia)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $model_transferencia = new Transferencia;
    $model_transferencia_productos = new Transferencia_producto;
    $model_farmacia = new Farmacia;
    $transferencia = $model_transferencia->GetTransferenciaById($id_transferencia);
    //$productos = $model_transferencia_productos->ObtenerProductoPorIdTransferencia($id_transferencia);
    $productos = $model_transferencia_productos->ObtenerProductoPorIdTransferenciaAndIdProducto($id_transferencia);
    //exit(print_r($productos));
    $id_farmacia_origen = $transferencia->id_farmacia;
    $id_farmacia_destino = $transferencia->id_farmacia_destino;
    $nombre_farmacia_origen = $model_farmacia->GetFarmaByIdfarma($id_farmacia_origen);
    $nombre_farmacia_destino = $model_farmacia->GetFarmaByIdfarma($id_farmacia_destino);
    $model_nea = new Genera_correlativonea;
    $nea = $model_nea->obtenerUltiNroNeaByFarmaAndAnio($id_farmacia_destino);
    $nea = $nea[0];

    //exit(print_r($nea));
    return view('frontend.transferencias.recepcionar_productos_transferir', compact('id_transferencia', 'transferencia', 'nombre_farmacia_origen', 'nombre_farmacia_destino', 'productos', 'id_farmacia_origen', 'id_farmacia_destino', 'nea'));
  }

  public function all_transferencias_recibidas($id_farmacia_destino = 0)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    else:
      if(Auth::user()->actualizo_passw==0):
        return redirect('auth/password/change');
      endif;
    endif;

    $id_farmacia_destino = Auth::user()->id_farmacia;

    $model_farmacia = new Farmacia;
    $model_transferencia = new Transferencia;
    $farmacia = $model_farmacia->GetFarmaByIdfarma($id_farmacia_destino);
    $transferencias = $model_transferencia->allTransferenciasRecibidasById_farmacia($id_farmacia_destino);

    $ruta = Route::getCurrentRoute()->getPath();
    if (count($ruta = explode("/", $ruta)) > 1);
    $ruta = $ruta[0];
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
    $permiso = $menu[0];
    if ($permiso->lectura == "")
      return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
    return view('frontend.transferencias.all_transferencias_recibidas', compact('transferencias', 'farmacia', 'permiso'));
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index($id_farmacia = 0)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    else:
      if(Auth::user()->actualizo_passw==0):
        return redirect('auth/password/change');
      endif;
    endif;
    $id_farmacia = Auth::user()->id_farmacia;

    $model_farmacia = new Farmacia;
    $model_transferencia = new Transferencia;
    //----------------------------------------------------------------------        
    $date = Carbon::now();
    $fechaHasta = $date->format('d-m-Y');
    $date->modify('first day of this month');
    $fechaDesde = $date->format('d-m-Y');
    $farmacia = $model_farmacia->GetFarmaByIdfarma($id_farmacia);

    //dd($farmacia);
    $almacen = $farmacia->almacen;
    //$transferencias = $model_transferencia->allTransferenciasById_farmacia($id_farmacia);
    $transferencias = $model_transferencia->allTransferenciasByFarmacia($id_farmacia, $fechaDesde, $fechaHasta);

    //        dd($transferencias);

    $date = Carbon::now();
    $fecha = $date->format('d-m-Y');
    $fechaActual = Carbon::parse($fecha);

    $ruta = Route::getCurrentRoute()->getPath();

    if (count($ruta = explode("/", $ruta)) > 1);
    $ruta = $ruta[0];
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
    $permiso = $menu[0];

    if ($permiso->lectura == "")
      return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
    return view('frontend.transferencias.all_transferencias', compact('transferencias', 'farmacia', 'permiso', 'fechaActual', 'almacen', 'fechaDesde', 'fechaHasta'));
  }

  public function index_almacen($id_farmacia)
  {
    $model_farmacia = new Farmacia;
    $model_transferencia = new Transferencia;
    //----------------------------------------------------------------------        
    $farmacia = $model_farmacia->GetFarmaByIdfarma($id_farmacia);
    $transferencias = $model_transferencia->allTransferenciasById_farmacia($id_farmacia);
    return view('frontend.transferencias.almacen.all_transferencias', compact('transferencias', 'farmacia'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create($id_user)
  {
    $model_farmacia = new Farmacia;
    $model_stock = new \App\Models\Stock_farmacia;
    //----------------------------------------------------------------------
    $farmacia = $model_farmacia->GetFarmaByUser($id_user);
    $id_farmacia = $farmacia->id;
    $productos = $model_stock->GetProductosByFarmacia($id_farmacia);
    //var_dump(count($productos)); exit;
    //----------------------------------------------------------------------
    return view('frontend.transferencias.form_transferencia', compact('farmacia', 'productos'));
  }

  public function add_documento_transferencia($id_user)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $model_farmacia = new Farmacia;
    $model_transferencia = new Transferencia;

    $model_kardex = new Kardexe();
    $fechaServidor = $model_kardex->getFechaServidor();

    $hoy = date('Y-m-j');
    $farmacia = $model_farmacia->GetFarmaByUser($id_user);
    $rango_registro = $farmacia->rango_registro;
    $id_farmacia = $farmacia->id;
    $almacen = $farmacia->almacen;
    $nro_acta = $model_transferencia->getNroActa($id_farmacia, $almacen);

    $model_mottransferencia = new MotivoTransferencia;
    $motivo_transferencia = $model_mottransferencia->getMotivoTransferenciaActivo();

    if ($almacen == 1)
      $nro_acta2 = $model_transferencia->getNroNotaS($id_farmacia);
    else
      $nro_acta2 = 0;


    //print_r($motivo_transferencia);
    $fecha_hace_treinta_dias = strtotime('-' . $rango_registro . ' day', strtotime($hoy));
    $fecha_hace_treinta_dias = date('d-m-Y', $fecha_hace_treinta_dias);
    //----------------------------------------------------------------------

    return view('frontend.transferencias.documento_transferencia', compact('farmacia', 'id_farmacia', 'fecha_hace_treinta_dias', 'rango_registro', 'motivo_transferencia', 'nro_acta', 'nro_acta2', 'fechaServidor', 'almacen'));
  }

  public function edit_documento_transferencia($id)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    $model_farmacia = new Farmacia;
    $hoy = date('Y-m-j');
    $farmacia = $model_farmacia->GetFarmaByUser($id_user);

    //----------------------------------------------------------------------
    $model_transferencias = new Transferencia();
    $transferencia = $model_transferencias->GetTransferenciaById($id);

    $model_ipress = new Establecimiento;
    $establecimientos = $model_ipress->ShowAll();

    $almacen = $farmacia->almacen;

    if ($transferencia->tipo == 1) {
      $model_farmacia = new Farmacia;
      $destinos = $model_farmacia->ShowFarmacias();
      $model_medico = new Medico;
      $responsable_destinos = $model_medico->responsable_ipress($transferencia->id_farmacia_destino);
      $lugar = $transferencia->id_farmacia_destino;
      $muestra = 'select';
    } else {
      if ($transferencia->tipo == 2) {
        $model_consultorio = new Consultorio;
        $id_user = Auth::user()->id;
        $modelfarma = new Farmacia();
        $farmacias = $modelfarma->GetFarmaByUser($id_user);
        $id_establecimiento2 = $farmacias->id_establecimiento;
        $destinos = $model_consultorio->ShowAllConsultorios($id_establecimiento2);
        //$destinos = $model_consultorio->ShowConsultorios($farmacia->id); 
        $id_ipress = $model_ipress->GetByIdFarmacia($farmacia->id);
        $model_medico = new Medico;
        $responsable_destinos = $model_medico->profesionales($id_ipress->id, $transferencia->id_consultorio);
        $lugar = $transferencia->id_consultorio;
        $muestra = 'select';
        $establecimientos = 0;
      } else {
        if ($transferencia->tipo == 3) {
          if ($transferencia->id_farmacia_destino == 0) {
            $destinos = collect(['id' => '0', 'nombre' => 'SIN SISTEMA']);
            $responsable_destinos = $transferencia->nombre_jefe_servicio;
            $lugar = 0;
            $muestra = 'text';
          } else {
            /*
                        $model_farmacia = new Farmacia;
                        $destinos = $model_farmacia->ShowFarmacias();
                        $model_medico = new Medico;
                        $responsable_destinos = $model_medico->responsable_ipress($transferencia->id_farmacia_destino);
                        $lugar=$transferencia->id_farmacia_destino;
                        $muestra='select';
						*/

            if ($almacen == 1) {
              $model_farmacia = new Farmacia;
              $farmacias = $model_farmacia->GetFarmaByUser($id_user);
              $id_establecimiento2 = $farmacias->id_establecimiento;
              $destinos = $model_farmacia->OnlyFarmacias($transferencia->id_ipress);
              $model_medico = new Medico;
              $responsable_destinos = $transferencia->nombre_jefe_servicio;
              $lugar = $transferencia->id_farmacia_destino;
              $muestra = 'text';
            } else {
              $model_farmacia = new Farmacia;
              $destinos = $model_farmacia->ShowFarmacias();
              $model_medico = new Medico;
              $responsable_destinos = $model_medico->responsable_ipress($transferencia->id_farmacia_destino);
              $lugar = $transferencia->id_farmacia_destino;
              $muestra = 'select';
            }
          }
        } else {
          /*
                    $model_dependencia = new Dependencia;
                    $destinos = $model_dependencia->getTodasDependencias();
                    $responsable_destinos = $transferencia->nombre_jefe_servicio;
                    $lugar=0;
                    $muestra='text';
					*/
          $model_dependencia = new Dependencia;
          $destinos = $model_dependencia->getTodasDependencias();
          $responsable_destinos = $transferencia->nombre_jefe_servicio;
          if ($transferencia->id_farmacia_destino != "") {
            $lugar = $transferencia->id_farmacia_destino;
          } else {
            $lugar = 0;
          }
          $muestra = 'text';
        }
      }
    }

    $model_mottransferencia = new MotivoTransferencia;
    $motivo_transferencia = $model_mottransferencia->getMotivoTransferenciaActivo();

    $id_farmacia = $farmacia->id;
    return view('frontend.transferencias.editar_documento_transferencia', compact('farmacia', 'transferencia', 'establecimientos',  'destinos',  'motivo_transferencia', 'muestra', 'id_farmacia', 'responsable_destinos', 'lugar', 'almacen'));
  }

  public function add_documento_transferencia_almacen($id_user)
  {
    $model_farmacia = new Farmacia;
    //----------------------------------------------------------------------
    $farmacia = $model_farmacia->GetFarmaByUser($id_user);
    return view('frontend.transferencias.almacen.documento_transferencia', compact('farmacia'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function registrar_documento_transferencia(Request $request)
  {
    $model_transferencias = new Transferencia();
    $duplicado = $model_transferencias->DuplicadoTransferencias($request->nro_acta, $request->id_origen);
    //exit(print_r($duplicado));
    if (count($duplicado) > 0) {
      $estTransferencia = $duplicado->estado;
      switch ($estTransferencia) {
        case "0":
          $nestTransferencia = "Eliminado";
          break;
        case "1":
          $nestTransferencia = "Pendiente de envío";
          break;
        case "3":
          $nestTransferencia = "Por recepcionar";
          break;
        case "4":
          $nestTransferencia = "Finalizado";
          break;
        default:
          $nestTransferencia = "Activo";
          break;
      }
      if ($request->id_origen == 30) {
        Session::flash('flash_message', 'El número de documento ' . $duplicado->nro_acta . ',  especificado ya se registro el ' . $duplicado->fecha . ' y está en estado ' . $nestTransferencia);
        return redirect('/add_documento_transferencia_almacen/' . $request->id_user);
      } else {
        Session::flash('flash_message', 'El número de documento ' . $duplicado->nro_acta . ',  especificado ya se registro el ' . $duplicado->fecha . ' y está en estado ' . $nestTransferencia);
        return redirect('/add_documento_transferencia/' . $request->id_user);
      }
    } else {

      $existe = $model_transferencias->ExisteTransferencia($request->nro_acta, $request->id_origen);
      if ($existe) {
        return redirect('/all_transferencias')
          ->withInput()
          ->withErrors(array('No se ha podido registrar la Transferencia, Nro de Acta ya existe'));
      } else {
        $tipo = $request->tipo;
        $transferencia = new Transferencia;
        $model_receta_vale = new \App\Models\Receta_vale;

        $transferencia->nro_acta = $request->nro_acta;


        $transferencia->fecha = $request->fecha;
        $transferencia->causa = $request->causa;
        $transferencia->id_motitransferencia = $request->id_motivo;
        $transferencia->observacion = $request->observacion;
        $transferencia->id_farmacia = $request->id_origen;
        $transferencia->nombre_jefe_farmacia_local = $request->responsable_origen;
        $transferencia->ano_acta = $model_receta_vale->getAnoServidor();

        switch ($request->tipo) {

          case '1':
            $transferencia->id_farmacia_destino = $request->id_lugar; //farmacias 
            $transferencia->tipo = 1;
            break;
          case '2':
            $transferencia->id_consultorio = $request->id_lugar; //consultorios
            $transferencia->tipo = 2;
            break;
          case '3':
            $transferencia->tipo = 3;
            $transferencia->id_ipress = $request->id_ipress;
            if ($request->id_lugar > 0) { //farmacia
              $transferencia->id_farmacia_destino = $request->id_lugar;
            } else {   //ipress
              $transferencia->id_farmacia_destino = 0;
            }
            break;
          case '4':
            $transferencia->id_farmacia_destino = $request->id_lugar; //consultorios
            $transferencia->tipo = 4;
            break;
        }

        $transferencia->nombre_jefe_servicio = $request->responsable_destino;
        $transferencia->estado = 1;
        $transferencia->save();
        $id_transferencia = $transferencia->id;
        Session::flash('flash_message', 'Se registro el documento de la transferencia, continuar con el registro de los productos farmaceúticos a trasferir!');
        return redirect('/registrar_productos_transferir/' . $id_transferencia . '/' . $request->id_origen);
      }
    }
  }

  public function editar_documento_transferencia(Request $request)
  {

    $model_transferencias = new Transferencia();

    $duplicado = $model_transferencias->DuplicadoTransferencias($request->nro_acta, $request->id_origen, $request->id);
    if (count($duplicado) > 0) {
      if ($request->id_origen == 30) {
        Session::flash('flash_message', 'El número de documento especificado ya se registro el ' . $duplicado->fecha);
        return redirect('/edit_documento_transferencia/' . $request->id);
      } else {
        Session::flash('flash_message', 'El número de documento especificado ya se registro el ' . $duplicado->fecha);
        return redirect('/edit_documento_transferencia/' . $request->id);
      }
    } else {
      $tipo = $request->tipo;

      $transferencia = Transferencia::where('id', '=', $request->id)->first();
      $transferencia->id_motitransferencia = $request->id_motivo;
      $transferencia->observacion = $request->observacion;
      $transferencia->nombre_jefe_farmacia_local = $request->responsable_origen;
      $transferencia->nombre_jefe_servicio = $request->responsable_destino;
      if (Auth::user()->flagestablecimientos == 1) :
        /*********************/
        $transferencia->id_farmacia = $request->id_origen;
        switch ($request->tipo) {
          case '1':
            $transferencia->id_farmacia_destino = $request->id_lugar; //farmacias 
            $transferencia->tipo = 1;
            break;
          case '2':
            $transferencia->id_consultorio = $request->id_lugar; //consultorios
            $transferencia->tipo = 2;
            break;
          case '3':
            $transferencia->tipo = 3;
            $transferencia->id_ipress = $request->id_ipress;
            if ($request->id_lugar > 0) { //farmacia
              $transferencia->id_farmacia_destino = $request->id_lugar;
            } else {   //ipress
              $transferencia->id_farmacia_destino = 0;
            }
            break;
          case '4':
            $transferencia->id_farmacia_destino = $request->id_lugar; //consultorios
            $transferencia->tipo = 4;
            break;
        }
      endif;
      /*********************/
      //$transferencia->estado = 1;
      $transferencia->save();

      Session::flash('flash_message', 'Se actualizo correctamente el documento de transferencia registrada!');
      return redirect('/edit_documento_transferencia/' . $request->id);
    }
  }

  public function eliminar_producto_para_transferir($id_transferencia_producto, $id_farmacia)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    $transferencia_productos = \App\Models\Transferencia_producto::find($id_transferencia_producto);
    $id_transferencia = $transferencia_productos->id_transferencia;
    $transferencia_productos->estado = 0; //eliminar producto
    $transferencia_productos->id_user_update = $id_user;
    $transferencia_productos->save();

    Session::flash('flash_message', 'Se elimino correctamente el producto a transferir');
    return redirect('/registrar_productos_transferir/' . $id_transferencia . '/' . $id_farmacia);
  }

  public function eliminar_producto_para_transferir_actualizado($id_transferencia_producto, $id_farmacia)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    $transferencia_productos = \App\Models\Transferencia_producto::find($id_transferencia_producto);
    $id_transferencia = $transferencia_productos->id_transferencia;
    $transferencia_productos->estado = 0; //eliminar producto
    $transferencia_productos->id_user_update = $id_user;
    $transferencia_productos->save();

    //Actualizar Kardexe y kardex_lote        
    $cad = "select t.nro_acta, tp.id_producto, tp.id from transferencias t inner join transferencia_productos tp on t.id=tp.id_transferencia where tp.id=" . $id_transferencia_producto;
    $data = DB::select($cad);

    $cad2 = "update kardex_lotes set estado=0 where id_ingreso_salida=" . $data[0]->id . " and id_kardex in (select id from kardexes where tipo_movimiento = 'TRANS' and numero_movimiento='" . $data[0]->nro_acta . "' and id_farmacia=" . $id_farmacia . " and id_producto=" . $data[0]->id_producto . " )";
    $data2 = DB::select($cad2);

    $cad3 = "select count(*) total from kardex_lotes where  id_kardex in (select id from kardexes where tipo_movimiento = 'TRANS' and numero_movimiento='" . $data[0]->nro_acta . "' and id_farmacia=" . $id_farmacia . " and estado=1 and id_producto=" . $data[0]->id_producto . " )";
    $data3 = DB::select($cad3);

    $cad4 = "select count(*) total_estado from kardex_lotes where estado=0 and id_kardex in (select id from kardexes where tipo_movimiento = 'TRANS' and numero_movimiento='" . $data[0]->nro_acta . "' and id_farmacia=" . $id_farmacia . " and estado=1 and id_producto=" . $data[0]->id_producto . " )";
    $data4 = DB::select($cad4);

    if ($data3[0]->total == $data4[0]->total_estado) {
      $cad5 = "update kardexes set estado=0 where id in (select id from kardexes where tipo_movimiento = 'TRANS' and numero_movimiento='" . $data[0]->nro_acta . "' and id_farmacia=" . $id_farmacia . " and estado=1 and id_producto=" . $data[0]->id_producto . " )";
      $data5 = DB::update($cad5);
    } else {
      $cad6 = "select sum(cantidad) total from kardex_lotes where estado=1 and id_kardex in (select id from kardexes where tipo_movimiento = 'TRANS' and numero_movimiento='" . $data[0]->nro_acta . "' and id_farmacia=" . $id_farmacia . " and estado=1 and id_producto=" . $data[0]->id_producto . " )";
      $data6 = DB::select($cad6);

      $cad7 = "update kardexes set cantidad=" . $data6[0]->total . " where id in (select id from kardexes where tipo_movimiento = 'TRANS' and numero_movimiento='" . $data[0]->nro_acta . "' and id_farmacia=" . $id_farmacia . " and estado=1 and id_producto=" . $data[0]->id_producto . " )";
      $data7 = DB::update($cad7);

      $contar = "select count(*) contar from kardex_lotes where estado=1 and id_kardex in (select id from kardexes where tipo_movimiento = 'TRANS' and numero_movimiento='" . $data[0]->nro_acta . "' and id_farmacia=" . $id_farmacia . " and estado=1 and id_producto=" . $data[0]->id_producto . " )";
      $data8 = DB::select($contar);

      if ($contar > 0) {
        $cad9 = "update kardexes set cantidad=" . $data6[0]->total . " where id in (select id from kardexes where tipo_movimiento = 'TRANS' and numero_movimiento='" . $data[0]->nro_acta . "' and id_farmacia=" . $id_farmacia . " and estado=1 and id_producto=" . $data[0]->id_producto . " )";
        $data9 = DB::update($cad9);
      }
    }

    Session::flash('flash_message', 'Se elimino correctamente el producto a transferir');
    return redirect('/registrar_productos_transferir/' . $id_transferencia . '/' . $id_farmacia);
  }

  public function update_producto_transferencia_actualizado(Request $request)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    if($request->id_producto==11045 || $request->id_producto==15938 || $request->id_producto==14607 || $request->id_producto==14608):
      $request->cantidad=$request->cantidad*1000;
    endif;
    $model_kardex = new Kardexe();
    $fecha_vencimiento = "";
    $id_user = Auth::user()->id;
    $lote = $model_kardex->GetKardexLotesByCodigoProductoAndLoteAndFechaVencimientoAndRegSanitarioAndIdFarmacia($request->id_producto, $request->lote, $fecha_vencimiento, $request->registro_sanitario, $request->id_farmacia, $request->precio);

    $transferencia_productos = \App\Models\Transferencia_producto::find($request->id_transferencia_producto);

    $transferencia_productos->cantidad = $request->cantidad;
    $transferencia_productos->id_user_update = $id_user;
    $transferencia_productos->save();

    $total_productos = $transferencia_productos->Contar($transferencia_productos->id_transferencia, $transferencia_productos->id_producto);

    //Buscar producto
    $cad = "select t.nro_acta, tp.id_producto from transferencias t inner join transferencia_productos tp on t.id=tp.id_transferencia where tp.id=" . $request->id_transferencia_producto;
    $data = DB::select($cad);

    //Actualizar Kardexe y kardex_lote
    $cad1 = "update kardex_lotes set cantidad=" . $request->cantidad . " where id_ingreso_salida=" . $request->id_transferencia_producto . " and id_kardex in (select id from kardexes where estado=1 and id_producto=" . $total_productos[0]->id_producto . " and numero_movimiento='" . $data[0]->nro_acta . "' and id_farmacia=" . $request->id_farmacia . ")";
    $data1 = DB::update($cad1);

    $cad2 = "update kardexes set cantidad=" . $total_productos[0]->cantidad . " where estado=1 and id_producto=" . $total_productos[0]->id_producto . " and numero_movimiento='" . $data[0]->nro_acta . "' and id_farmacia=" . $request->id_farmacia;
    $data2 = DB::update($cad2);

    Session::flash('flash_message', 'Se Actualizo correctamente el producto a transferir');
    return redirect('/registrar_productos_transferir/' . $request->id_transferencia . '/' . $request->id_farmacia);
  }

  public function update_producto_transferencia(Request $request)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    if($request->id_producto==11045 || $request->id_producto==15938 || $request->id_producto==14607 || $request->id_producto==14608):
      $request->cantidad=$request->cantidad*1000;
    endif;
    $model_kardex = new Kardexe();
    $fecha_vencimiento = "";
    $id_user = Auth::user()->id;

    //dd($request->fecha_vencimiento);
    $lote = $model_kardex->GetKardexLotesByCodigoProductoAndLoteAndFechaVencimientoAndRegSanitarioAndIdFarmacia($request->id_producto, $request->lote, $fecha_vencimiento, $request->registro_sanitario, $request->id_farmacia, $request->precio);
    if (isset($lote[0]->cantidad) && $request->cantidad * $request->presentacion_comercial <= $lote[0]->cantidad) :
      $transferencia_productos = \App\Models\Transferencia_producto::find($request->id_transferencia_producto);
      $transferencia_productos->cantidad = $request->cantidad;
      $transferencia_productos->id_user_update = $id_user;
      $transferencia_productos->save();
      Session::flash('flash_message', 'Se Actualizo correctamente el producto a transferir');
      return redirect('/registrar_productos_transferir/' . $request->id_transferencia . '/' . $request->id_farmacia);
    else :
      Session::flash('flash_message', 'La cantidad ingresada debe ser menor o igual al stock del producto farmaceutico');
      return redirect('/registrar_productos_transferir/' . $request->id_transferencia . '/' . $request->id_farmacia);
    endif;
  }

  public function editar_producto_para_transferir($id_transferencia_producto)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $transferencia_productos = \App\Models\Transferencia_producto::find($id_transferencia_producto);
    $id_producto = $transferencia_productos->id_producto;
    $producto = DB::table('productos')->join('unidades', 'productos.id_unidad', '=', 'unidades.id')->select('productos.*', 'productos.presentacion_comercial as presentacion', 'unidades.abreviatura')
      ->where('productos.id', '=', $id_producto)->first();

    //$id_stock_lote = $transferencia_productos->id_stock_lote;
    //$producto_lote = Stock_lote::where('id', '=', $id_stock_lote)->first();
    $model_transferencias = new Transferencia();
    $producto_lote = $model_transferencias->ShowLotesTransferidosById_transferencia_nuevo($transferencia_productos->id_transferencia, $id_transferencia_producto);
    $producto_lote = $producto_lote[0];

    $id_farmacia = Auth::user()->id_farmacia;
    $fecha = explode("-", $producto_lote->fecha_vencimiento);
    $anio = $fecha[0];
    $mes = $fecha[1];
    //$mesvencimiento = $mes."-".$anio;
    $mesvencimiento = $anio . "-" . $mes;
    $model_kardex = new Kardexe();


    $lote = $model_kardex->GetKardexLotesByCodigoProductoAndLoteAndFechaVencimientoAndRegSanitarioAndIdFarmacia($producto_lote->id_producto, $producto_lote->lote, $mesvencimiento, $producto_lote->registro_sanitario, $id_farmacia, $producto_lote->precio_unitario);
    //print_r($lote); exit();

    $lote = $lote[0];
    return view('frontend.transferencias.editar_producto_para_transferir', compact('transferencia_productos', 'producto', 'producto_lote', 'lote'));
  }

  public function editar_producto_para_transferir_actualizado($id_transferencia_producto)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $transferencia_productos = \App\Models\Transferencia_producto::find($id_transferencia_producto);
    $id_producto = $transferencia_productos->id_producto;
    $producto = DB::table('productos')->join('unidades', 'productos.id_unidad', '=', 'unidades.id')->select('productos.*', 'unidades.abreviatura')
      ->where('productos.id', '=', $id_producto)->first();

    $model_transferencias = new Transferencia();
    $producto_lote = $model_transferencias->ShowLotesTransferidosById_transferencia_nuevo($transferencia_productos->id_transferencia, $id_transferencia_producto);
    $producto_lote = $producto_lote[0];

    $id_farmacia = Auth::user()->id_farmacia;
    $fecha = explode("-", $producto_lote->fecha_vencimiento);
    $anio = $fecha[0];
    $mes = $fecha[1];
    $mesvencimiento = $anio . "-" . $mes;
    $model_kardex = new Kardexe();

    $lote = $model_kardex->GetKardexLotesByCodigoProductoAndLoteAndFechaVencimientoAndRegSanitarioAndIdFarmacia($producto_lote->id_producto, $producto_lote->lote, $mesvencimiento, $producto_lote->registro_sanitario, $id_farmacia, $producto_lote->precio_unitario);

    $cantidad = count($lote);

    if ($cantidad > 0)
      $lote = $lote[0];

    return view('frontend.transferencias.editar_producto_para_transferir_actualizado', compact('transferencia_productos', 'producto', 'producto_lote', 'lote', 'cantidad'));
  }

  public function registrar_productos_transferir($id_transferencia, $id_farmacia)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $model_farmacia = new Farmacia;
    $model_transferencia = new Transferencia;
    $model_kardex = new Kardexe();
    $tipo_transferencia = $model_transferencia->ObtenerTipoTransferencia($id_transferencia);
    if ($tipo_transferencia->tipo == 1) {
      $transferencia = $model_transferencia->GetTransferenciaById($id_transferencia);
      $destino = Farmacia::where('id', $transferencia->id_farmacia_destino)->select('nombre')->first();
      $id_destino = $transferencia->id_farmacia_destino;
    } else {
      if ($tipo_transferencia->tipo == 3) {
        $busca_transferencia = Transferencia::where('id', $id_transferencia)->first();
        if ($busca_transferencia->id_farmacia_destino == 0) {  //ipress sin sistema
          $transferencia = $model_transferencia->GetTransferenciaByIdEstablecimiento($id_transferencia);
          $destino = Establecimiento::where('id', $busca_transferencia->id_ipress)->select('nombre')->first();
        } else {
          $transferencia = $model_transferencia->GetTransferenciaById($id_transferencia);
          $destino = Farmacia::where('id', $transferencia->id_farmacia_destino)->select('nombre')->first();
        }
        $id_destino = $busca_transferencia->id_farmacia_destino;
      } else {
        if ($tipo_transferencia->tipo == 4) {
          $transferencia = $model_transferencia->GetTransferenciaByIdDependencia($id_transferencia);
          $destino = Dependencia::where('id', $transferencia->id_farmacia_destino)->select('nombre_dependencia as nombre')->first();
          $id_destino = $transferencia->id_farmacia_destino;
        } else {
          $transferencia = $model_transferencia->GetTransferenciaById($id_transferencia);
          $destino = Consultorio::where('id', $transferencia->id_consultorio)->select('nombre')->first();
          $id_destino = $transferencia->id_consultorio;
        }
      }
    }
    $farma = $model_farmacia->GetFarmaByIdfarma($transferencia->id_farmacia);
    $farmacia_origen = $farma->nombre;
    $productos = $model_transferencia->ShowLotesTransferidosById_transferencia_nuevo($id_transferencia);
    if ($tipo_transferencia->tipo == 1) {
      $lotes = $model_kardex->GetKardexProductoByIdFarmacia($id_farmacia);
    } else {
      if ($tipo_transferencia->tipo == 2) {
        $lotes = $model_kardex->GetKardexProductoByIdFarmacia($id_farmacia);
      } else {
        if ($tipo_transferencia->tipo == 3) {
          $lotes = $model_kardex->GetKardexProductoByIdFarmaciaExterna($id_farmacia, $busca_transferencia->id_ipress);
        } else {
          $lotes = $model_kardex->GetKardexProductoByIdFarmacia($id_farmacia);
        }
      }
    }
    $tipo = $tipo_transferencia->tipo;
    $farmacia = Farmacia::where('id', $id_farmacia)->first();
    $almacen = $farmacia->almacen;
    $almacen_ipress = $farmacia->almacen_ipress;
    return view('frontend.transferencias.registrar_productos_transferir', compact('lotes', 'transferencia', 'farmacia_origen', 'productos', 'destino', 'tipo', 'id_destino', 'id_farmacia', 'almacen', 'almacen_ipress'));
  }


  public function cerrar_carga_transferencia($id_transferencia, $tipo, $id_farmacia)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $model_transferencia = new \App\Models\Transferencia;
    $model_transferencia_productos = new Transferencia_producto;
    $productos = $model_transferencia_productos->ObtenerProductoPorIdTransferenciaAgrupadoByProductoInterna($id_transferencia);

    $id_user = Auth::user()->id;

    $model_busca_kardex = new Kardexe();
    $busca = $model_busca_kardex->GetBuscaTransferencia($id_farmacia, $id_transferencia);

    if (count($busca) > 0) {

      $transferencia = Transferencia::where('id', '=', $id_transferencia)->first();
      $model_farmacia = Farmacia::where('id', '=', $id_farmacia)->first();
      $almacen_x = $model_farmacia->almacen;
      //$almacen_y = $model_farmacia->almacen_ipress;

      if ($almacen_x == 1) :
        $transferencia->estado = 5;
      else :
        $transferencia->estado = 4;
      endif;

      $transferencia->save();

      Session::flash('flash_message', 'Se culmino y cerro la transferencia');

      return redirect('/registrar_productos_transferir/' . $id_transferencia . '/' . $id_farmacia);
    } else {

      if (count($productos) > 0) :

        if ($tipo == 2) { //Transferencia Interna Servicios
          $transferencia = Transferencia::where('id', '=', $id_transferencia)->first();
          $id_consultorio = $transferencia->id_consultorio;
          $fecho_movimiento = $transferencia->fecha;
          $transferencia->estado = 4;
          $numero_movimiento = $transferencia->nro_acta;
          $transferencia->save();

          foreach ($productos as $prod) {
            $productosLote = $model_transferencia_productos->ObtenerProductoPorIdTransferenciaAndIdProductoInterna($id_transferencia, $prod->id_producto);
            $consultorios = Consultorio::where('id', '=', $id_consultorio)->first();
            $farmacia = Farmacia::where('id', '=', $id_farmacia)->first();
            $kardex = new Kardexe;
            $kardex->codigo_producto = $prod->codigo_producto;
            $kardex->nombre_producto = $prod->nombre_producto;
            $kardex->tipo_movimiento = "TRANS";
            $kardex->numero_movimiento = $numero_movimiento;
            $kardex->id_nro_movimiento = $id_transferencia;
            $kardex->origen = $farmacia->nombre;
            $kardex->destino = $consultorios->nombre;
            $kardex->estado = 1;
            $kardex->cantidad = $prod->cantidad;
            $kardex->nombre_farmacia = $farmacia->nombre;
            $kardex->fecha_movimiento = $fecho_movimiento;
            $kardex->id_tipo_movimiento = 7;
            $kardex->id_producto = $prod->id_producto;
            $kardex->id_farmacia =  $id_farmacia;
            $kardex->created_at = Carbon::now();
            $kardex->save();
            $idkardex = $kardex->id;

            foreach ($productosLote as $key => $rowProductosLote) {
              $kardex_lote = new \App\Models\Kardex_lote;
              $kardex_lote->id_kardex = $idkardex;
              $kardex_lote->lote = $rowProductosLote->lote;
              if ($rowProductosLote->fecha_vencimiento != '0000-00-00 00:00:00' && $rowProductosLote->fecha_vencimiento != '') :
                $kardex_lote->fecha_vencimiento = $rowProductosLote->fecha_vencimiento;
              endif;
              $kardex_lote->registro_sanitario = $rowProductosLote->registro_sanitario;
              $kardex_lote->cantidad = $rowProductosLote->cantidad;
              $kardex_lote->fecha_registro  = $fecho_movimiento;
              $kardex_lote->precio_unitario = $rowProductosLote->precio_unitario;
              $kardex_lote->presentacion_comercial = $rowProductosLote->presentacion_comercial;
              $kardex_lote->id_ingreso_salida = $rowProductosLote->id;
              $kardex_lote->save();
            }
          }
        } else {
          if ($tipo == 3) {//Transferencia Externa EESS PNP

            $transferencia = Transferencia::where('id', '=', $id_transferencia)->first();

            if ($transferencia->id_farmacia_destino == 0) {

              $id_farmacia_destino = $transferencia->id_ipress;

              $fecho_movimiento = $transferencia->fecha;

              $transferencia->estado = 4;

              $numero_movimiento = $transferencia->nro_acta;
              $transferencia->save();
              foreach ($productos as $prod) {
                $productosLote = $model_transferencia_productos->ObtenerProductoPorIdTransferenciaAndIdProductoInterna($id_transferencia, $prod->id_producto);
                $farmacia_origen = Farmacia::where('id', '=', $id_farmacia)->first();
                $farmacia_destino = Establecimiento::where('id', '=', $id_farmacia_destino)->first();
                $kardex = new Kardexe;
                $kardex->codigo_producto = $prod->codigo_producto;
                $kardex->nombre_producto = $prod->nombre_producto;
                $kardex->tipo_movimiento = "TRANS";
                $kardex->numero_movimiento = $numero_movimiento;
                $kardex->id_nro_movimiento = $id_transferencia;
                $kardex->origen = $farmacia_origen->nombre;
                $kardex->destino = $farmacia_destino->nombre;
                $kardex->id_farmacia =  $id_farmacia;
                $kardex->id_producto = $prod->id_producto;
                $kardex->estado = 1;
                $kardex->cantidad = $prod->cantidad;
                $kardex->nombre_farmacia = $farmacia_origen->nombre;
                $kardex->fecha_movimiento = $fecho_movimiento;
                $kardex->id_tipo_movimiento = 7;
                $kardex->created_at = Carbon::now();
                $kardex->save();
                $idkardex = $kardex->id;
                foreach ($productosLote as $key => $rowProductosLote) {
                  $kardex_lote = new \App\Models\Kardex_lote;
                  $kardex_lote->id_kardex = $idkardex;
                  $kardex_lote->lote = $rowProductosLote->lote;
                  if ($rowProductosLote->fecha_vencimiento != '0000-00-00 00:00:00' && $rowProductosLote->fecha_vencimiento != '') :
                    $kardex_lote->fecha_vencimiento = $rowProductosLote->fecha_vencimiento;
                  endif;
                  $kardex_lote->registro_sanitario = $rowProductosLote->registro_sanitario;
                  $kardex_lote->cantidad = $rowProductosLote->cantidad;
                  $kardex_lote->fecha_registro  = $fecho_movimiento;
                  $kardex_lote->precio_unitario = $rowProductosLote->precio_unitario;
                  $kardex_lote->presentacion_comercial = $rowProductosLote->presentacion_comercial;
                  $kardex_lote->id_ingreso_salida = $rowProductosLote->id;
                  $kardex_lote->save();
                }
              }
            } else {//Distribucion Interna de Medicamentos
              $farmacia = Farmacia::where('id', '=', $id_farmacia)->first();
              $almacen = $farmacia->almacen;
              $almacen_ipress = $farmacia->almacen_ipress;
              if ($almacen == 1 || $almacen_ipress == 1) {
                $transferencia = Transferencia::where('id', '=', $id_transferencia)->first();
                //$id_farmacia_destino = $transferencia->id_farmacia_destino;
                $id_farmacia_destino = $transferencia->id_ipress;
                $fecho_movimiento = $transferencia->fecha;
                $transferencia->estado = 5;
                $numero_movimiento = $transferencia->nro_acta;
                $transferencia->save();

                foreach ($productos as $prod) {
                  $productosLote = $model_transferencia_productos->ObtenerProductoPorIdTransferenciaAndIdProductoInterna($id_transferencia, $prod->id_producto);
                  $farmacia_origen = Farmacia::where('id', '=', $id_farmacia)->first();
                  $farmacia_destino = Establecimiento::where('id', '=', $id_farmacia_destino)->first();
                  $kardex = new Kardexe;
                  $kardex->codigo_producto = $prod->codigo_producto;
                  $kardex->nombre_producto = $prod->nombre_producto;
                  $kardex->tipo_movimiento = "TRANS";
                  $kardex->numero_movimiento = $numero_movimiento;
                  $kardex->id_nro_movimiento = $id_transferencia;
                  $kardex->origen = $farmacia_origen->nombre;
                  $kardex->destino = $farmacia_destino->nombre;
                  $kardex->id_farmacia =  $id_farmacia;
                  $kardex->id_producto = $prod->id_producto;
                  $kardex->estado = 1;
                  $kardex->cantidad = $prod->cantidad;
                  $kardex->nombre_farmacia = $farmacia_origen->nombre;
                  $kardex->fecha_movimiento = $fecho_movimiento;
                  $kardex->id_tipo_movimiento = 7;
                  $kardex->created_at = Carbon::now();
                  $kardex->save();
                  $idkardex = $kardex->id;
                  foreach ($productosLote as $key => $rowProductosLote) {
                    $kardex_lote = new \App\Models\Kardex_lote;
                    $kardex_lote->id_kardex = $idkardex;
                    $kardex_lote->lote = $rowProductosLote->lote;
                    if ($rowProductosLote->fecha_vencimiento != '0000-00-00 00:00:00' && $rowProductosLote->fecha_vencimiento != '') :
                      $kardex_lote->fecha_vencimiento = $rowProductosLote->fecha_vencimiento;
                    endif;
                    $kardex_lote->registro_sanitario = $rowProductosLote->registro_sanitario;
                    $kardex_lote->cantidad = $rowProductosLote->cantidad;
                    $kardex_lote->fecha_registro  = $fecho_movimiento;
                    $kardex_lote->precio_unitario = $rowProductosLote->precio_unitario;
                    $kardex_lote->presentacion_comercial = $rowProductosLote->presentacion_comercial;
                    $kardex_lote->id_ingreso_salida = $rowProductosLote->id;
                    $kardex_lote->save();
                  }
                }
                Session::flash('flash_message', 'Se desconto el stock actual del almacen, no olvide enviar al destino para culminar la transferencia');
                return redirect('/registrar_productos_transferir/' . $id_transferencia . '/' . $id_farmacia);
              } else {
                $transferencia = Transferencia::where('id', '=', $id_transferencia)->first();
                $transferencia->estado = 3;
                $transferencia->save();
              }
            }
          } else {
            if ($tipo == 4) {//Transferencia IPRESS NO PNP
              $transferencia = Transferencia::where('id', '=', $id_transferencia)->first();
              $id_farmacia_destino = $transferencia->id_farmacia_destino;
              //$fecho_movimiento = $transferencia->fecha;
              //$transferencia->estado = 4;
              $fecho_movimiento = $transferencia->fecha;
              $farmacia = Farmacia::where('id', '=', $id_farmacia)->first();
              $almacen = $farmacia->almacen;
              $almacen_ipress = $farmacia->almacen_ipress;
              if ($almacen == 1 || $almacen_ipress == 1)
                $transferencia->estado = 5;
              else
                $transferencia->estado = 4;

              $numero_movimiento = $transferencia->nro_acta;
              $transferencia->save();
              foreach ($productos as $prod) {
                $productosLote = $model_transferencia_productos->ObtenerProductoPorIdTransferenciaAndIdProductoInterna($id_transferencia, $prod->id_producto);
                $farmacia_origen = Farmacia::where('id', '=', $id_farmacia)->first();
                $farmacia_destino = Dependencia::where('id', '=', $id_farmacia_destino)->first();
                $kardex = new Kardexe;
                $kardex->codigo_producto = $prod->codigo_producto;
                $kardex->nombre_producto = $prod->nombre_producto;
                $kardex->tipo_movimiento = "TRANS";
                $kardex->numero_movimiento = $numero_movimiento;
                $kardex->id_nro_movimiento = $id_transferencia;
                $kardex->origen = $farmacia_origen->nombre;
                $kardex->destino = $farmacia_destino->nombre_dependencia;
                $kardex->id_farmacia =  $id_farmacia;
                $kardex->id_producto = $prod->id_producto;
                $kardex->estado = 1;
                $kardex->cantidad = $prod->cantidad;
                $kardex->nombre_farmacia = $farmacia_origen->nombre;
                $kardex->fecha_movimiento = $fecho_movimiento;
                $kardex->id_tipo_movimiento = 7;
                $kardex->created_at = Carbon::now();
                $kardex->save();
                $idkardex = $kardex->id;
                foreach ($productosLote as $key => $rowProductosLote) {
                  $kardex_lote = new \App\Models\Kardex_lote;
                  $kardex_lote->id_kardex = $idkardex;
                  $kardex_lote->lote = $rowProductosLote->lote;
                  if ($rowProductosLote->fecha_vencimiento != '0000-00-00 00:00:00' && $rowProductosLote->fecha_vencimiento != '') :
                    $kardex_lote->fecha_vencimiento = $rowProductosLote->fecha_vencimiento;
                  endif;
                  $kardex_lote->registro_sanitario = $rowProductosLote->registro_sanitario;
                  $kardex_lote->cantidad = $rowProductosLote->cantidad;
                  $kardex_lote->fecha_registro  = $fecho_movimiento;
                  $kardex_lote->precio_unitario = $rowProductosLote->precio_unitario;
                  $kardex_lote->id_ingreso_salida = $rowProductosLote->id;
                  $kardex_lote->presentacion_comercial = $rowProductosLote->presentacion_comercial;
                  $kardex_lote->save();
                }
              }
            } else {
              $transferencia = Transferencia::where('id', '=', $id_transferencia)->first();
              $transferencia->estado = 3;
              $transferencia->save();
            }
          }
        }

        Session::flash('flash_message', 'Se culmino y cerro la transferencia');

        return redirect('/registrar_productos_transferir/' . $id_transferencia . '/' . $id_farmacia);

      else :

        return redirect('/registrar_productos_transferir/' . $id_transferencia . '/' . $id_farmacia)
          ->withInput()
          ->withErrors(array('Debe agregar los productos a la Transferencia para enviar al destino'));
      endif;
    }
  }

  public function cambiar_estado_transferencia($id_transferencia, $tipo, $id_farmacia)
  {
    $model_transferencia = new \App\Models\Transferencia;
    $model_transferencia_productos = new Transferencia_producto;
    $productos = $model_transferencia_productos->ObtenerProductoPorIdTransferenciaAgrupadoByProductoInterna($id_transferencia);

    if (count($productos) > 0) :
      if ($tipo == 3) :

        $transferencia = Transferencia::where('id', '=', $id_transferencia)->first();
        $transferencia->estado = 3;
        $transferencia->save();

        Session::flash('flash_message', 'Se culmino y cerro la transferencia');
        return redirect('/registrar_productos_transferir/' . $id_transferencia . '/' . $id_farmacia);
      else :
        return redirect('/registrar_productos_transferir/' . $id_transferencia . '/' . $id_farmacia)
          ->withInput()
          ->withErrors(array('No se a podido culminar su transferencia al destino'));
      endif;
    else :
      return redirect('/registrar_productos_transferir/' . $id_transferencia . '/' . $id_farmacia)
        ->withInput()
        ->withErrors(array('Debe agregar los productos a la Transferencia para descontar el stock'));
    endif;
  }

  public function obtener_lote_byIdlote_Idfarmacia($id_lote, $id_farmacia)
  {
    $model_lote = new \App\Models\Stock_lote;
    $lote = $model_lote->getLoteByidlote($id_lote, $id_farmacia);
    return $lote;
  }

  public function obtener_Cantidad_by_CodigoProducto_Lote_FechaVencimiento_RegistroSanitario_Idfarmacia($id_producto, $lote, $fecha_vencimiento, $registro_sanitario, $id_farmacia, $precio_unitario, $id_transferencia)
  {$id_user = Auth::user()->id;
    //exit();
    if ($fecha_vencimiento == 0)
      $fecha_vencimiento = " ";
    $lote = str_replace("|", "/", $lote);
    $registro_sanitario = str_replace("|", "/", $registro_sanitario);
    //echo $lote; exit();
    $model_transferencia = new Transferencia();
    $model_kardex = new Kardexe();
    $lote = $model_kardex->GetKardexLotesByCodigoProductoAndLoteAndFechaVencimientoAndRegSanitarioAndIdFarmacia($id_producto, $lote, $fecha_vencimiento, $registro_sanitario, $id_farmacia, $precio_unitario, $id_transferencia,$id_user);
    //if($id_user==4387 || $id_user==2657): dd($lote); endif;
    $resultadof[0] = NULL;
    $indice = 0;
    $rs = (object) array();

    $model_farma = new Farmacia;
    $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
    $almacen = $farma->almacen;
    $almacen_ipress = $farma->almacen_ipress;

    foreach ($lote as $key => $rowLote) {
      $dettranspen = $model_transferencia->GetCantByCodigoProductoAndRegSanitarioAndLoteAndFechaVencimientoAndPrecioUni($rowLote->id_producto, $rowLote->lote, $rowLote->fecha_vencimiento, $rowLote->registro_sanitario, $rowLote->precio_unitario, $id_farmacia,$id_user);
      if ($rowLote->presentacion_comercial == 1) {
        $canTransPen = (int)0;
        $canStock = (int)$rowLote->cantidad;
        if (isset($dettranspen->cantidad)) $canTransPen = (int)$dettranspen->cantidad;
      } else {
        $canTransPen = (float)0;
        $canStock = (float)$rowLote->cantidad;
        if (isset($dettranspen->cantidad)) $canTransPen = (float)$dettranspen->cantidad;
      }


      if ($almacen == 1 || $almacen_ipress == 1)
        $canTransPen = 0;

      if ($canStock > $canTransPen) {
        //$resultadof[$indice] = $rowLote;
        $rs->id_producto = $rowLote->id_producto;
        $rs->prod_farmaceutico = $rowLote->prod_farmaceutico;
        $rs->codigo_producto = $rowLote->codigo_producto;
        $rs->id_farmacia = $rowLote->id_farmacia;
        $rs->abreviatura = $rowLote->abreviatura;
        $rs->lote = $rowLote->lote;
        $rs->registro_sanitario = $rowLote->registro_sanitario;
        $rs->fecha_vencimiento = $rowLote->fecha_vencimiento;
        $rs->precio_unitario = $rowLote->precio_unitario;
        $rs->cantidad = $rowLote->cantidad;
        //$rs->cantidadtranspen = $canTransPen;
        //if($almacen!=1)
        $rs->cantidadtranspen = $canTransPen;
        //else
        //	 $rs->cantidadtranspen = 0;
        $rs->created_at = $rowLote->created_at;
        $rs->presentacion = $rowLote->presentacion;
        $rs->presentacion_comercial = $rowLote->presentacion_comercial;
        $rs->cantidad2 = $rowLote->cantidad / $rowLote->presentacion_comercial;
        $resultadof[$indice] = $rs;
        $indice++;
      }
    }
    return $resultadof;
  }

  public function existeTransferenciaPendienteByProducto($id_producto, $id_farmacia, $id_transferencia)
  {
    $result = "";
    $model_farma = new Farmacia;
    $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
    $almacen = $farma->almacen;
    $almacen_ipress = $farma->almacen_ipress;
    
    if ($almacen != 1 && $almacen_ipress != 1) {
      $transferencia = new Transferencia();
      $rs = $transferencia->ExisteTransferenciaPendienteByProducto($id_producto, $id_farmacia, $id_transferencia);
      if (isset($rs->nro_acta))
        if ($rs->nro_acta <> "") {
          $result = trim($rs->id_transferencia . "|" . $rs->nro_acta);
        }
    }

    return $result;
  }

  public function existeTransferenciaPendienteByProducto2($id_producto, $id_farmacia, $id_transferencia)
  {
    $result = "";
    $transferencia = new Transferencia();
    $rs = $transferencia->ExisteTransferenciaPendienteByProducto2($id_producto, $id_farmacia, $id_transferencia);
    if (isset($rs->nro_acta))
      if ($rs->nro_acta <> "") {
        $result = trim($rs->id_transferencia . "|" . $rs->nro_acta);
      }
    return $result;
  }

  public function obtenerLoteByIdProductoAndIdfarmacia($id_producto, $id_farmacia)
  {

    $model_farma = new Farmacia;
    $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
    $almacen = $farma->almacen;
    $almacen_ipress = $farma->almacen_ipress;

    $model_transferencia = new Transferencia();
    $model_kardex = new Kardexe();
    $lote = $model_kardex->GetKardexLotesByIdProductoAndIdFarmacia($id_producto, $id_farmacia);

    $resultadof[0] = NULL;
    $indice = 0;
    //$rs = (object) array();
    foreach ($lote as $key => $rowLote) {
      $dettranspen = $model_transferencia->GetCantByCodigoProductoAndRegSanitarioAndLoteAndFechaVencimientoAndPrecioUni($rowLote->id_producto, $rowLote->lote, $rowLote->fecha_vencimiento, $rowLote->registro_sanitario, $rowLote->precio_unitario, $id_farmacia);

      if ($rowLote->presentacion_comercial == 1) {
        $canTransPen = (int)0;
        $canStock = (int)$rowLote->cantidad;
        if (isset($dettranspen->cantidad)) $canTransPen = (int)$dettranspen->cantidad;
      } else {
        $canTransPen = (float)0;
        $canStock = (float)$rowLote->cantidad;
        if (isset($dettranspen->cantidad)) $canTransPen = (float)$dettranspen->cantidad;
      }
      if ($almacen == 1 || $almacen_ipress == 1 )
        $canTransPen = 0;
      if ($canStock > $canTransPen) {
        $resultadof[$indice] = $rowLote;
        $indice++;
      }
    }

    return $resultadof;
  }

  public function agregar_producto_transferencia(Request $request)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    //$cantidad_actual = $request->cantidad_actual;
    if($request->id_producto==11045 || $request->id_producto==15938 || $request->id_producto==14607 || $request->id_producto==14608):
      $request->cantidad_transferir = $request->cantidad_transferir*1000;
    endif;
    $cantidad_transferir = $request->cantidad_transferir * $request->id_presentacion_comercial;
    $model_transferencia = new Transferencia;
    $model_kardex = new Kardexe();
    $transferencia = $model_transferencia->GetTransferenciaById($request->id_transferencia);
    $fecha_fin = $model_kardex->getFechaServidor();
    $stock = $model_kardex->ShowTotalByIdProductoAndIdFarmacia($request->id_producto, $transferencia->id_farmacia, $fecha_fin);
    $cantidad_actual = 0;
    if (isset($stock->total) && $stock->total > 0)
      echo $cantidad_actual = $stock->total;

    if ($cantidad_transferir > 0) {
      if ($cantidad_transferir <= $cantidad_actual) {
        $model_produto = new \App\Models\Producto;
        $producto = $model_produto->GetIdProdByCodMed($request->codigo);
        $id_producto = $request->id_producto;

        if (count($id_producto) > 0) {
          $existe_lote = DB::table('transferencia_productos')
            ->where('id_transferencia', $request->id_transferencia)
            ->where('id_producto', $request->id_producto)
            ->where('registro_sanitario', $request->registro_sanitario)
            ->where('estado', 1)
            ->where('lote', $request->lote)
            ->where(DB::raw("to_char(fecha_vencimiento,'mm-yyyy')"), $request->fecha_vencimiento)
            ->where('precio_unitario', $request->precio)->first();

          $existe_lote_general = $model_transferencia->ExisteProductoTransferencias($request->id_producto, $request->registro_sanitario, $request->lote, $transferencia->id_farmacia, $request->precio, $request->fecha_vencimiento);
        } else {
          $existe_lote = DB::table('transferencia_productos')
            ->where('id_transferencia', $request->id_transferencia)
            ->where('id_producto', $producto->id)
            ->where('registro_sanitario', $request->registro_sanitario)
            ->where('estado', 1)
            ->where('lote', $request->lote)
            ->where(DB::raw("to_char(fecha_vencimiento,'mm-yyyy')"), $request->fecha_vencimiento)
            ->where('precio_unitario', $request->precio)->first();

          $existe_lote_general = $model_transferencia->ExisteProductoTransferencias($producto->id, $request->registro_sanitario, $request->lote, $transferencia->id_farmacia, $request->precio, $request->fecha_vencimiento);
        }

        //$existe_otro_trans = $existe_lote_general->cantidad;
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByIdfarma($transferencia->id_farmacia);
        $almacen = $farma->almacen;
        $almacen_ipress = $farma->almacen_ipress;
        $cantidad_disponible_stock = $cantidad_actual - $existe_lote_general->cantidad;
        if ($almacen != 1 && $almacen_ipress !=1) {
          $existe_otro_trans = $existe_lote_general->cantidad;
          $cantidad_disponible_resto = $cantidad_disponible_stock - $request->cantidad_transferir * $request->presentacion_comercial;
        } else {
          $existe_otro_trans = null;
          $cantidad_disponible_resto = 1;
        }
        //$cantidad_disponible_stock = $cantidad_actual - $existe_lote_general->cantidad;
        //$cantidad_disponible_resto = $cantidad_disponible_stock - $request->cantidad_transferir*$request->presentacion_comercial;
        /*print_r($existe_lote_general);
                exit();*/
        if (count($existe_lote) > 0) {
          Session::flash('flash_message', 'El Producto ' . $producto->nombre . ' de lote ' . $request->lote . ', registro sanitario ' . $request->registro_sanitario . ', fecha vencimimiento ' . $request->fecha_vencimiento . ', precio ' . $request->precio . ' ya esta en lista, solo edite la cantidad de ser necesario');
          return redirect('/registrar_productos_transferir/' . $request->id_transferencia . '/' . $request->id_farmacia);
        } else if ($existe_otro_trans <> "") {
          Session::flash('flash_message', 'El Producto ' . $producto->nombre . ' de lote ' . $request->lote . ', registro sanitario ' . $request->registro_sanitario . ', fecha vencimimiento ' . $request->fecha_vencimiento . ', precio ' . $request->precio . ' ya esta en la transferencia ' . $existe_lote_general->nro_acta . ' con cantidad total de ' . $existe_lote_general->cantidad . ' pendiente de envio o aceptación.');
          return redirect('/registrar_productos_transferir/' . $request->id_transferencia . '/' . $request->id_farmacia);
        } else if ($cantidad_disponible_resto < 0) {
          Session::flash('flash_message', 'El Producto ' . $producto->nombre . ' de lote ' . $request->lote . ', registro sanitario ' . $request->registro_sanitario . ', fecha vencimimiento ' . $request->fecha_vencimiento . ', precio ' . $request->precio . ' ya esta en la transferencia ' . $existe_lote_general->nro_acta . ' con cantidad total de ' . $existe_lote_general->cantidad . ' pendiente de envio o aceptación.');
          return redirect('/registrar_productos_transferir/' . $request->id_transferencia . '/' . $request->id_farmacia);
        } else {


          $id_user = Auth::user()->id;
          $model_transferencia_productos = new \App\Models\Transferencia_producto;
          $model_transferencia_productos->id_transferencia = $request->id_transferencia;

          //si no tiene id_producto utilizar codigo_producto
          if (count($id_producto) > 0) {
            $model_transferencia_productos->id_producto = $request->id_producto;
          } else {
            $model_transferencia_productos->id_producto = $producto->id;
          }

          $model_transferencia_productos->cantidad = $request->cantidad_transferir;
          $model_transferencia_productos->estado = 1;
          $model_transferencia_productos->registro_sanitario = $request->registro_sanitario;
          $model_transferencia_productos->lote = $request->lote;
          $model_transferencia_productos->precio_unitario = $request->precio;
          $model_transferencia_productos->presentacion_comercial = $request->id_presentacion_comercial;

          if ($request->fecha_vencimiento != '0000-00-00 00:00:00' && $request->fecha_vencimiento != '') :
            $model_transferencia_productos->fecha_vencimiento = "01-" . $request->fecha_vencimiento;
          endif;
          $model_transferencia_productos->id_user_created = $id_user;
          $model_transferencia_productos->save();
          $id_ingreso_salida = $model_transferencia_productos->id;

          $modelfarma = new Farmacia();
          $farmacias = $modelfarma->GetFarmaByUser($id_user);
          $id_establecimiento2 = $farmacias->id_establecimiento;
          $id_farmacia = $farmacias->id;

          if ($farmacias->almacen == 1 || $farmacias->almacen_ipress == 1) { //Actualizar kardex


            //if($request->registro==2){ //Actualizar kardex

            /*$productos = $model_transferencia_productos->ObtenerProductoPorIdTransferenciaAgrupadoByProductoInterna($request->id_transferencia);
                                        dd($productos);                    */

            $total_productos = $model_transferencia_productos->Contar($request->id_transferencia, $request->id_producto);


            $farmacia_origen = Farmacia::where('id', '=', $request->id_farmacia)->first();

            $buscar_kardex = Kardexe::where('estado', '=', 1)->where('id_producto', '=', $request->id_producto)->where('id_farmacia', '=', $request->id_farmacia)->where('id_nro_movimiento', '=', $request->id_transferencia)->count();

            if ($buscar_kardex == 0) {
              $kardex = new Kardexe;
              $kardex->codigo_producto = $request->codigo;
              $kardex->nombre_producto = $request->producto;
              $kardex->tipo_movimiento = "TRANS";
              $kardex->numero_movimiento = $transferencia->nro_acta;
              $kardex->id_nro_movimiento = $request->id_transferencia;
              $kardex->origen = $farmacia_origen->nombre;
              $kardex->destino = $request->consultorio_destino;
              $kardex->id_farmacia =  $request->id_farmacia;
              $kardex->id_producto = $request->id_producto;
              $kardex->estado = 1;
              $kardex->cantidad = $request->cantidad_transferir;
              $kardex->nombre_farmacia = $farmacia_origen->nombre;
              $kardex->fecha_movimiento = $request->fecha_movimiento;
              $kardex->id_tipo_movimiento = 7;
              $kardex->created_at = Carbon::now();
              $kardex->save();
              $idkardex = $kardex->id;
            } else {
              $update_kardex = Kardexe::where('estado', '=', 1)->where('id_producto', '=', $request->id_producto)->where('id_farmacia', '=', $request->id_farmacia)->where('numero_movimiento', '=', $transferencia->nro_acta)->first();
              $update_kardex->cantidad = $total_productos[0]->cantidad;
              $update_kardex->save();
              $idkardex = $update_kardex->id;
            }

            //Crear kardex lotes
            $kardex_lote = new \App\Models\Kardex_lote;
            $kardex_lote->id_kardex = $idkardex;
            $kardex_lote->lote = $request->lote;
            if ($request->fecha_vencimiento != '0000-00-00 00:00:00' && $request->fecha_vencimiento != '') :
              $kardex_lote->fecha_vencimiento = '01-' . $request->fecha_vencimiento;
            endif;
            $kardex_lote->registro_sanitario = $request->registro_sanitario;
            $kardex_lote->cantidad = $request->cantidad_transferir;
            $kardex_lote->presentacion_comercial = $request->id_presentacion_comercial;
            $kardex_lote->fecha_registro  = $request->fecha_movimiento;
            $kardex_lote->precio_unitario = $request->precio;
            $kardex_lote->id_ingreso_salida = $id_ingreso_salida;
            $kardex_lote->save();

            Session::flash('flash_message', 'Se transferio correctamento el producto farmaceútico');
            return redirect('/registrar_productos_transferir/' . $request->id_transferencia . '/' . $request->id_farmacia);
          }

          Session::flash('flash_message', 'Se agrego correctamento el producto farmaceútico a transferir');
          return redirect('/registrar_productos_transferir/' . $request->id_transferencia . '/' . $request->id_farmacia);
        }
      } else {
        Session::flash('flash_message', 'La cantidad a transferir debe estar comprendido entre 1 y el stock disponible');
        return redirect('/registrar_productos_transferir/' . $request->id_transferencia . '/' . $request->id_farmacia);
      }
    } else {
      Session::flash('flash_message', 'La cantidad a transferir no debe ser igual a 0 o negativo');
      return redirect('/registrar_productos_transferir/' . $request->id_transferencia . '/' . $request->id_farmacia);
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
  public function edit($id)
  {
    //
  }

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

  public function cargardestino($tipo, $id_establecimiento = 0)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $model_farmacia = new Farmacia;
    $model_consultorio = new Consultorio;
    //----------------------------------------------------------------------
    $id_user = Auth::user()->id;
    $modelfarma = new Farmacia();
    $farmacias = $modelfarma->GetFarmaByUser($id_user);
    $id_establecimiento2 = $farmacias->id_establecimiento;
    $id_farmacia = $farmacias->id;
    $almacen = $farmacias->almacen;
    switch ($tipo) {
      case 1: //Distribucion Farmacia PNP 
        $destino = $model_farmacia->ShowFarmaciasInterna($id_establecimiento, $id_farmacia, $almacen);
        if (count($destino) == 0) {
          $destino = collect(['id' => '0', 'nombre' => 'SIN SISTEMA']);
        }
        break;
      case 2: //Distribucion Servicio PNP 
        $destino = $model_consultorio->ShowAllConsultorios($id_establecimiento2);
        break;
      case 3: //Distribucion Externa PNP
        $model_establecimiento = new Establecimiento;
        $destino = $model_establecimiento->ShowAllEstablecimientos($id_establecimiento2);

        break;
      case 4: //Distribucion NO PNP
        $model_dependencia = new Dependencia;
        $destino = $model_dependencia->getTodasDependencias();
        break;
      case 5: //Distribucion Establecimiento sin sistema PNP
        $model_establecimiento = new Establecimiento;
        $destino = $model_establecimiento->EstablecimientosSinSistema();
        break;
    }

    return $destino;
  }

  public function cargardestino_almacen($tipo, $id_farmacia)
  {
    $model_farmacia = new Farmacia;
    $establecimiento = $model_farmacia->GetEstablecimientoPNP($id_farmacia);
    $model_consultorio = new Consultorio;
    //----------------------------------------------------------------------
    if ($tipo == 1) {
      $destino = $model_farmacia->ShowFarmacias();
    } else {
      if ($tipo == 2) {
        $destino = $model_consultorio->ShowConsultoriosAll();
      } else {
        $destino = $model_farmacia->ObtenerFarmaciasInternas($establecimiento->id, $id_farmacia);
      }
    }
    //var_dump($destino); exit;
    return $destino;
  }

  public function eliminar($id)
  {
    $update = Transferencia::where('id', '=', $id)->first();
    $update->estado = 0;
    $update->save();
    Session::flash('flash_message', 'Se elimino correctamente');
  }

  public function anular($id)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $update = Transferencia::where('id', '=', $id)->first();
    $update->estado = 0;
    $update->anulado = 0;
    $update->save();
    $id_farmacia = Auth::user()->id_farmacia;
    //////////////////////
    //kardex_lotes
    //kardexes
    //////////////////////
    //$id_farmacia=46;
    /*
        $cad3 = "update kardex_lotes set estado=0 where id_kardex in (select id from kardexes where numero_movimiento='".$update->numero_movimiento."' and id_farmacia=46 and estado=1)";
		*/
    $cad3 = "update kardex_lotes set estado=0 where id_kardex in (select id from kardexes where tipo_movimiento = 'TRANS' and id_nro_movimiento='" . $id . "' and id_farmacia='" . $id_farmacia . "' and estado=1)";
    $data3 = DB::update($cad3);

    //$cad3 = "update kardexes set estado=0 where numero_movimiento='".$update->numero_movimiento."' and id_farmacia=46 and estado=1";
    $cad3 = "update kardexes set estado=0 where  tipo_movimiento = 'TRANS' and id_nro_movimiento='" . $id . "' and id_farmacia='" . $id_farmacia . "' and estado=1";

    $data3 = DB::update($cad3);


    //////////////////
    Session::flash('flash_message', 'Se anulo correctamente');
  }

  public function all_transferencias_productos()
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $ruta = Route::getCurrentRoute()->getPath();
    if (count($ruta = explode("/", $ruta)) > 1);
    $ruta = $ruta[0];

    //$menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
    //$permiso = $menu[0];

    //if ($permiso->lectura == "")
    //return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

    $id_user = Auth::user()->id;
    $id_farmacia = Auth::user()->id_farmacia;

    $nro_pecosa = "";

    $date = Carbon::now();
    $fechaDesde = $date->format('d-m-Y');
    $fechaHasta = $date->format('d-m-Y');

    //==================== 
    $model_recetas = new Receta_vale();
    $model_cg = new Clasificador_gasto();
    $model_producto = new Producto();


    $transferencias = $model_transferencia->AllTransferenciaByFarmaAndFechaDesdeHasta($id_farmacia, $fechaDesde, $fechaHasta);

    $cantidad = $model_transferencia->GetCountRecetasByUserAndFechaDesdeHasta($id_farmacia, $fechaDesde, $fechaHasta);

    $ipress = cargardestino(1, 0);
    $clasificador = $model_c->gcargarcg();
    $productos = $model_producto->ProductosFarmaciasSegunNivel(3);

    //====================
    return view('frontend.transferencias.all_transferencias_atendidas_by_farmacia', compact('transferencias', 'cantidad', 'ipress', 'fechaDesde', 'fechaHasta', 'clasificador', 'id_farmacia', 'productos', 'nro_pecosa'));
  }

  public function all_transferencias_salidas($id_user = 0)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    else:
      if(Auth::user()->actualizo_passw==0):
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

    $reg_sanitario = "";
    $lote = "";
    $l_dependencia = "";
    $l_establecimiento = "";
    $id_producto = "";
    $id_tipo_producto = "";
    $l_origen = "";
    $estado = 6;

    $date = Carbon::now();
    $fechaDesde = $date->format('Y-m-d');
    $fechaHasta = $date->format('Y-m-d');

    //==================== 
    $model_farma = new Farmacia();
    $farmacia = $model_farma->GetFarmaByUser($id_user);
    $id_farmacia = $farmacia->id;
    $modo_validacion = $farmacia->modo_validacion;
    //====================
    $model_transferencias = new Transferencia();

    //$ordenes_salidas = $model_transferencias->ShowAllSalidas($id_farmacia, $fechaDesde, $fechaHasta);

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

    $model_ipress = new Establecimiento;
    $establecimientos = $model_ipress->ShowAll();
    //====================
    $fechaDesde = $date->format('d-m-Y');
    $fechaHasta = $date->format('d-m-Y');

    if ($farmacia->almacen == 1) :
      $ordenes_salidas = $model_transferencias->ShowAllSalidas($id_farmacia, $fechaDesde, $fechaHasta, $estado);
      return view('frontend.transferencias.todas_transferencias_almacen', compact('ordenes_salidas', 'farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'tipo_productos', 'productosnivel', 'dependencias', 'lote', 'reg_sanitario', 'l_establecimiento', 'l_dependencia', 'id_user', 'id_tipo_proceso', 'id_producto', 'id_tipo_producto', 'establecimientos', 'estado'));
    else :
      $ordenes_salidas = $model_transferencias->ShowAllSalidasFarmacia($id_farmacia, $fechaDesde, $fechaHasta, $estado);
      return view('frontend.transferencias.todas_transferencias_farmacia', compact('ordenes_salidas', 'farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'tipo_productos', 'productosnivel', 'dependencias', 'lote', 'reg_sanitario', 'l_establecimiento', 'l_dependencia', 'id_user', 'id_tipo_proceso', 'id_producto', 'id_tipo_producto', 'establecimientos', 'estado'));
    endif;
  }

  public function buscar_orden_salidas(Request $request)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    if (!$request->id_user) :
      return redirect('/all_transferencias_salidas');
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

    $reg_sanitario = $request->reg_sanitario;
    $lote = $request->lote;
    $idregistrador = $request->idregistrador;
    $id_producto = $request->id_producto;
    $id_tipo_producto = $request->id_tipo_producto;
    $l_dependencia = $request->l_dependencia;
    $l_establecimiento = $request->l_establecimiento;
    $estado = $request->estado;
    //==================== 
    $model_farma = new Farmacia();
    $farmacia = $model_farma->GetFarmaByUser($request->id_user);
    $id_farmacia = $farmacia->id;
    $modo_validacion = $farmacia->modo_validacion;
    //====================
    $model_transferencias = new Transferencia();
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
    $model_ipress = new Establecimiento;
    $establecimientos = $model_ipress->ShowAll();
    $fechaDesde = date("d-m-Y", strtotime($fechaDesde));
    $fechaHasta = date("d-m-Y", strtotime($fechaHasta));

    if ($farmacia->almacen == 1) :
      $ordenes_salidas = $model_transferencias->ShowAllSalidas($id_farmacia, $fechaDesde, $fechaHasta, $estado, $reg_sanitario, $lote, $id_producto, $id_tipo_producto, $l_establecimiento, $l_dependencia);
      return view('frontend.transferencias.todas_transferencias_almacen', compact('ordenes_salidas', 'farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'tipo_productos', 'productosnivel', 'dependencias', 'lote', 'reg_sanitario', 'l_establecimiento', 'l_dependencia', 'id_user', 'id_tipo_proceso', 'id_producto', 'id_tipo_producto', 'establecimientos', 'estado'));
    else :
      $ordenes_salidas = $model_transferencias->ShowAllSalidasFarmacia($id_farmacia, $fechaDesde, $fechaHasta, $estado, $reg_sanitario, $lote, $id_producto, $id_tipo_producto);
      return view('frontend.transferencias.todas_transferencias_farmacia', compact('ordenes_salidas', 'farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'tipo_productos', 'productosnivel', 'dependencias', 'lote', 'reg_sanitario', 'l_establecimiento', 'l_dependencia', 'id_user', 'id_tipo_proceso', 'id_producto', 'id_tipo_producto', 'establecimientos', 'estado'));
    endif;
  }

  /*public function buscar_nota_salida($nro_nota_entrada) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $model_guiaentrada = new Guia_entrada;
        $guia = $model_guiaentrada->ExisteNumeroEntrada($nro_nota_entrada);
        
        return $guia;
    }*/

  public function cambiar_estado_envio($id_transferencia, $id_farmacia)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $model_transferencia = new \App\Models\Transferencia;
    $model_transferencia_productos = new Transferencia_producto;
    $productos = $model_transferencia_productos->ObtenerProductoPorIdTransferenciaAgrupadoByProductoInterna($id_transferencia);

    $id_user = Auth::user()->id;

    $model_busca_kardex = new Kardexe();
    $busca = $model_busca_kardex->GetBuscaTransferencia($id_farmacia, $id_transferencia);

    if (count($busca) > 0) {

      $transferencia = Transferencia::where('id', '=', $id_transferencia)->first();

      //$transferencia->estado = 6;
      if($transferencia->tipo == 4 || $transferencia->tipo == 2 || $transferencia->id_farmacia_destino == 0):
        $transferencia->estado = 4;
      else:
        $transferencia->estado = 3;
      endif;
      
      $transferencia->save();

      Session::flash('flash_message', 'Se envio la transferencia a destino');

      return redirect('/registrar_productos_transferir/' . $id_transferencia . '/' . $id_farmacia);
    }
  }

  public function print_salidas_almacen($fecha_inicio, $fecha_fin, $id_farmacia, $reg_sanitario, $lote, $l_dependencia, $l_establecimiento, $id_tipo_producto, $id_producto, $estado)
  {

    $model_farma = new Farmacia;
    $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
    $nombre_farmacia = $farma->nombre;
    //----------------------------------------------------        
    if ($l_dependencia == 0) $l_dependencia = "";
    if ($l_establecimiento == 0) $l_establecimiento = "";
    if ($id_tipo_producto == 0) $id_tipo_producto = "";
    if ($id_producto == 0) $id_producto = "";

    $fechaDesde = date("Y-m-d", strtotime($fecha_inicio));
    $fechaHasta = date("Y-m-d", strtotime($fecha_fin));

    $model_transferencias = new Transferencia();
    $ordenes_salidas = $model_transferencias->ShowAllSalidas($id_farmacia, $fechaDesde, $fechaHasta, $estado, $reg_sanitario, $lote, $id_producto, $id_tipo_producto, $l_establecimiento, $l_dependencia);


    return view('frontend.transferencias.print_salidas_almacen', compact('ordenes_salidas', 'farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'tipo_productos', 'productosnivel', 'dependencias', 'lote', 'reg_sanitario', 'l_establecimiento', 'l_dependencia', 'id_user', 'id_tipo_proceso', 'id_producto', 'id_tipo_producto', 'establecimientos'));
  }

  public function busca_transferencia(Request $request)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $id_farmacia = Auth::user()->id_farmacia;

    $model_farmacia = new Farmacia;
    $model_transferencia = new Transferencia;
    //-----------------------------------------------
    if ($request->fechaDesde != "") {
      $fechaDesde = $request->fechaDesde;
      $fechaHasta = $request->fechaHasta;
    } else {
      $date = Carbon::now();
      $fechaHasta = $date->format('d-m-Y');
      $date->modify('first day of this month');
      $fechaDesde = $date->format('d-m-Y');
    }

    $farmacia = $model_farmacia->GetFarmaByIdfarma($id_farmacia);
    $almacen = $farmacia->almacen;
    $transferencias = $model_transferencia->allTransferenciasByFarmacia($id_farmacia, $fechaDesde, $fechaHasta);

    $date = Carbon::now();
    $fecha = $date->format('d-m-Y');
    $fechaActual = Carbon::parse($fecha);
    $ruta = "all_transferencias";

    if (count($ruta = explode("/", $ruta)) > 1);
    $ruta = $ruta[0];
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
    $permiso = $menu[0];

    if ($permiso->lectura == "")
      return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
    return view('frontend.transferencias.all_transferencias', compact('transferencias', 'farmacia', 'permiso', 'fechaActual', 'almacen', 'fechaDesde', 'fechaHasta'));
  }

  public function print_salidas_farmacia($fecha_inicio, $fecha_fin, $id_farmacia, $reg_sanitario, $lote, $id_tipo_producto, $id_producto)
  {

    $model_farma = new Farmacia;
    $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
    $nombre_farmacia = $farma->nombre;
    //----------------------------------------------------        
    if ($id_tipo_producto == 0) $id_tipo_producto = "";
    if ($id_producto == 0) $id_producto = "";

    $fechaDesde = date("Y-m-d", strtotime($fecha_inicio));
    $fechaHasta = date("Y-m-d", strtotime($fecha_fin));

    $model_transferencias = new Transferencia();
    $ordenes_salidas = $model_transferencias->ShowAllSalidasFarmacia($id_farmacia, $fechaDesde, $fechaHasta, $reg_sanitario, $lote, $id_producto, $id_tipo_producto);


    return view('frontend.transferencias.print_salidas_farmacia', compact('ordenes_salidas', 'farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'tipo_productos', 'productosnivel', 'dependencias', 'lote', 'reg_sanitario', 'l_establecimiento', 'l_dependencia', 'id_user', 'id_tipo_proceso', 'id_producto', 'id_tipo_producto', 'establecimientos'));
  }

  public function actualizar_kardex_point($id_transferencia,$id_farmacia, $id_establecimiento)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

      $date = Carbon::now();
      $fecha_movimiento = $date->format('d-m-Y');
      $id_user = Auth::user()->id;
      
      //------------------------------------------------------------------
      $model_transferencia = new Transferencia;
      $model_transferencia_productos = new Transferencia_producto;
      $model_receta_vale = new \App\Models\Receta_vale;

      $productos = $model_transferencia_productos->ObtenerProductoPorIdTransferenciaAgrupadoByProducto($id_transferencia);
      //----------Descontar Stock Farmacia Origen ------------------------
      foreach ($productos as $p) {
        //sacer agrupado y desagrupado por idproducto

        $productosLote = $model_transferencia_productos->ObtenerProductoPorIdTransferenciaAndIdProducto($id_transferencia, $p->id_producto);

        //----------Agregar al Kardex ------------------------
        $existe_kardex_point_ = DB::table('kardex_points')
          ->where('id_establecimiento', $id_establecimiento)
          ->where('id_producto', $p->id_producto)
          ->where('id_farmacia', $id_farmacia)->first();

        if ($existe_kardex_point_ == false) {
          $Kardex_points = new \App\Models\Kardex_points;
          $Kardex_points->id_establecimiento = $id_establecimiento;
          $Kardex_points->id_producto = $p->id_producto;
          $Kardex_points->id_farmacia = $id_farmacia;
          $Kardex_points->id_kardex = 13822153;
          $Kardex_points->total = 0;
          $Kardex_points->save();
        }

      }
    Session::flash('flash_message', 'Transferencia exitosa..!!');
  }
}
