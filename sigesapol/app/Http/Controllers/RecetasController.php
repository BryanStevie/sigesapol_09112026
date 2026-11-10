<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Farmacia;
use App\Models\Producto;
use App\Models\Consultorio;
use App\Models\Receta_atendida;
use App\Models\Receta_stock;
use App\Models\Receta_vale;
use App\Models\Upss;
use App\Stock;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use SoapClient;
use Session;
use App\Models\Anaquele;
use Auth;

class RecetasController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('auth'); 
    } 
    
    public function index(Request $request) {
        //
    }

    public function add_datos_receta(Request $request) {
        $id_farmacia = $request->id_farmacia;
        $receta = new Receta_atendida;
        $receta->nro_historia = $request->nro_historia;
        $receta->serie = $request->serie;
        $receta->nro_ticket = $request->nro_ticket;
        $receta->dni_afiliado = $request->dni_afiliado;
        $receta->nombre_afiliado = $request->nombre_afiliado;
        $receta->paterno_afiliado = $request->ape_paterno;
        $receta->materno_afiliado = $request->ape_materno;
        $receta->fecha_nacimiento = $request->fecha_nac;
        $receta->edad = $request->edad;
        $receta->sexo = $request->sexo;
        $receta->email = $request->e_mail;
        $receta->telefono = $request->telefono;
        $receta->cmp = $request->cmp;
        $receta->nombre_medico = $request->nombres_medico;
        $receta->paterno_medico = $request->ape_paterno_medico;
        $receta->cod_consultorio = $request->id_consultorio;
        $receta->consultorio = $request->especialidad;
        $receta->cod_producto = $request->id_producto;
        $receta->producto = $request->descripcion;
        $receta->id_farmacia = $request->id_farmacia;
        $receta->id_user = $request->id_user;
        $receta->save();
        //---------------------------------
        $id_receta = $receta->id;
        //---------------------------------                
        $modelatendidos = new Receta_stock;
        $modelstock = new Stock;
        $modelfarma = new Farmacia;
        $datos_receta = Receta_atendida::find($id_receta);
        $productos = $modelstock->ShowMedicamentoByFarma($id_farmacia);
        $farmacia = $modelfarma->GetFarmaByIdfarmacia($id_farmacia);
        $medicamentos = $modelatendidos->ShowMedicamentosAtendidos($id_receta);
        return view('frontend.recetas.add_datos_receta', compact('productos', 'id_receta', 'datos_receta', 'farmacia', 'medicamentos'));
        //return view('frontend.recetas.inex', compact('productos','id_receta','datos_receta'));
    }

    public function editar_medicamento($id_receta_stock) {
        $model = new Receta_stock;
        $medicamentos = $model->GetMedicamentoAtendido($id_receta_stock);
        return view('frontend.recetas.editar_medicamento_entregado', compact('medicamentos'));
    }

    public function update_medicamento_atendido(Request $request) {
        $receta_stock = Receta_stock::find($request->id_receta_stock);
        //$id_stock=$receta_stock->id_stock;
        //var_dump($id_stock); exit;
        $id_stock = Stock::find($receta_stock->id_stock);
        if (($request->asigando) > ($id_stock->monto_actual)) {
            return redirect('/editar_medicamento/' . $request->id_receta_stock)
                            ->withInput()->withErrors(array('La cantidad asignada es mayor al stock actual del medicamento'));
        } else {
            $faltante = ($receta_stock->cantidad_solicitada) - ($receta_stock->cantidad_atendida);
            if (($request->asigando) > $faltante) {
                return redirect('/editar_medicamento/' . $request->id_receta_stock)
                                ->withInput()->withErrors(array('La cantidad asignada no puede ser mayor a la cantidad faltante'));
            } else {
                $monto_actual = $id_stock->monto_actual;
                $monto_consumido = $id_stock->monto_consumido;
                //var_dump("El monto si es valido " . $monto_actual); exit;
                $receta_stock->cantidad_atendida = $request->atendido + $request->asigando;
                $id_stock->monto_actual = $monto_actual - $request->asigando;
                $id_stock->monto_consumido = $monto_consumido + $request->asigando;
                $id_stock->save();
                $receta_stock->save();
                $id = $receta_stock->id;
                $update_receta_stock = Receta_stock::find($id);
                $cantidad_atendida = $update_receta_stock->cantidad_atendida;
                $cantidad_solicitada = $update_receta_stock->cantidad_solicitada;
                $resta = $cantidad_solicitada - $cantidad_atendida;
                if ($resta > 0) {
                    $update_receta_stock->estado = 0;
                    $update_receta_stock->save();
                    return redirect('/editar_medicamento/' . $request->id_receta_stock)->withFlashSuccess("Se atendio correctamente");
                } else {
                    $update_receta_stock->estado = 1;
                    $update_receta_stock->save();
                    return redirect('/editar_medicamento/' . $request->id_receta_stock)->withFlashSuccess("Se atendio correctamente");
                }
            }
        }
        //var_dump($id_stock->monto_actual-$request->asigando); exit;
    }

    public function medicamento_entregado(Request $request) {
        $modelatendidos = new Receta_stock;
        $modelstock = new Stock;
        $modelfarma = new Farmacia;
        //----------------------------------------------------------------------
        $solicitado = $request->cantidad_solicitada;
        $atendido = $request->cantidad_atendida;
        $id_medicina = $request->id_medicamento;
        //--------------------identifica el medicamento del stock---------------
        $restar_stocks = Stock::findOrFail($id_medicina);
        $consumido = $restar_stocks->monto_consumido;
        $actual = $restar_stocks->monto_actual;
        //----------------------------------------------------------------------
        $medicamento_atendido = new Receta_stock;
        $medicamento_atendido->id_receta = $request->id_receta;
        $medicamento_atendido->id_stock = $request->id_medicamento;
        //$medicamento_atendido->cantidad_solicitada = $request->cantidad_solicitada;
        //$medicamento_atendido->cantidad_atendida = $request->cantidad_atendida;
        $existe = $medicamento_atendido->ExisteMedicamento($request->id_receta, $request->id_medicamento);
        if ($existe) {
            Session::flash('flash_message', 'El medicamento ya ha sido atendido');
        } else {
            if ($actual == 0) {
                if ($atendido > $solicitado) {
                    $medicamento_atendido->cantidad_solicitada = $request->cantidad_solicitada;
                    $medicamento_atendido->cantidad_atendida = 0;
                    $medicamento_atendido->estado = 0;
                    $medicamento_atendido->save();
                    Session::flash('flash_message', 'Usted a digitado la cantidad atendida (' . $atendido . ') mayor a la cantidad solicitada (' . $solicitado . '). Ademas no se puede entregar este medicamento ya que no cuenta con stock actualmente.');
                } else {
                    $medicamento_atendido->cantidad_solicitada = $request->cantidad_solicitada;
                    $medicamento_atendido->cantidad_atendida = 0;
                    $medicamento_atendido->estado = 0;                    
                    $medicamento_atendido->save();
                    Session::flash('flash_message', 'Se registro correctamente, pero ha quedado pendiente la entrega del medicamento,ya que le medicamento no cuenta con stock actualmente');
                }

//Session::flash('flash_message', 'El stock del medicamento esta agotado');
            } else {
                //Session::flash('flash_message', 'Exite stock del medicamento');
                if ($atendido > $actual) {
                    $medicamento_atendido->cantidad_solicitada = $request->cantidad_solicitada;
                    $medicamento_atendido->cantidad_atendida = 0;
                    $medicamento_atendido->estado = 0;
                    $medicamento_atendido->save();
                    Session::flash('flash_message', 'Usted a digitado la cantidad atendida (' . $atendido . ') mayor al stock actual (' . $actual . ') del medicamento. Por favor edite y digite correctamente la cantidad atendida');
                } else {
                    if ($atendido > $solicitado) {
                        $medicamento_atendido->cantidad_solicitada = $request->cantidad_solicitada;
                        $medicamento_atendido->cantidad_atendida = 0;
                        $medicamento_atendido->estado = 0;
                        $medicamento_atendido->save();
                        Session::flash('flash_message', 'Usted a digitado la cantidad atendida (' . $atendido . ') mayor a la cantidad solicitada (' . $solicitado . '). Por favor edite y digite correctamente la cantidad atendida');
                    } else {
                        $medicamento_atendido->cantidad_solicitada = $request->cantidad_solicitada;
                        $medicamento_atendido->cantidad_atendida = $request->cantidad_atendida;
                        if ($solicitado == $atendido) {
                            $medicamento_atendido->estado = 1; //Se atendido correctamente y completamente los medicamentos solicitados
                        } else {
                            $medicamento_atendido->estado = 0; //Se atendido correctamente y parcialmente los medicamentos solicitados
                        }
                        $restar_stocks->monto_consumido = $consumido + $request->cantidad_atendida;
                        $restar_stocks->monto_actual = $actual - $request->cantidad_atendida;
                        $restar_stocks->save();
                        $medicamento_atendido->save();
                        Session::flash('flash_message', 'Se atendió correctamente el medicamento.');
                    }
                }
            }
        }

        //----------Datos necesarios para la vista: Atender Medicamentos--------
        $id_receta = $request->id_receta;
        $datos_receta = Receta_atendida::find($request->id_receta);
        $productos = $modelstock->ShowMedicamentoByFarma($request->id_farmacia);
        $farmacia = $modelfarma->GetFarmaByIdfarmacia($request->id_farmacia);
        $medicamentos = $modelatendidos->ShowMedicamentosAtendidos($id_receta);
        return view('frontend.recetas.add_datos_receta', compact('productos', 'id_receta', 'datos_receta', 'farmacia', 'medicamentos'));
    }
	
	
	
	/*
    public function recetabynro_historia(Request $request) {
        //----------------------------------------------------------------------
        $modelstock = new Stock;
        $model_receta_atencias = new Receta_atendida;
        //----------------------------------------------------------------------
        $id_farmacia = $request->farmacia;
        $serie = $request->serie;
        $nro_ticket = $request->nro_ticket;
        $client = new SoapClient("http://192.168.0.132/sishc/ws_terceros.php?wsdl", array('login' => 'userws', 'password' => "WSaludPol@3210"));
        $receta = $client->get_head_invoice('A', $serie, $nro_ticket, '', '1');
        $servicio = $client->get_head_invoice('A', $serie, $nro_ticket, '', '2');
        //var_dump($servicio,$receta); exit;
        //----------------------------------------------------------------------           
        $existe = $model_receta_atencias->ExisteRecetaAtendida($receta[0]->nro_historia);
        if ($existe) {
            //------------------------------------------------------------------
            $modelatendidos = new Receta_stock;
            $modelfarma = new Farmacia;
            //------------------------------------------------------------------
            $get_receta = $model_receta_atencias->RecetaSiAtendida($receta[0]->nro_historia);
            $id_receta = $get_receta[0]->id;
            $datos_receta = Receta_atendida::find($id_receta);
            $productos = $modelstock->ShowMedicamentoByFarma($id_farmacia);
            $farmacia = $modelfarma->GetFarmaByIdfarmacia($id_farmacia);
            $medicamentos = $modelatendidos->ShowMedicamentosAtendidos($id_receta);
            return view('frontend.recetas.add_datos_receta', compact('productos', 'id_receta', 'datos_receta', 'farmacia', 'medicamentos'));
            //------------------------------------------------------------------
        } else {
            if (count($receta) > 0) {
                $atencion = $receta;
                $productos = $modelstock->ShowMedicamentoByFarma($id_farmacia);
                return view('frontend.recetas.get_receta', compact('productos', 'atencion', 'servicio', 'id_farmacia'));
            } else {
                return redirect('/atender_receta/' . $request->user)->withInput()->withErrors(array('La Receta no existe..!'));
            }
        }
        //----------------------------------------------------------------------
    }
	*/
	
	
	/*
    public function recetasby_dni(Request $request) {
        
        $dni = $request->dni;
        $client = new SoapClient("http://192.168.0.132/sishc/ws_terceros.php?wsdl", array('login' => 'userws', 'password' => "WSaludPol@3210"));
        $receta = $client->get_head_invoice('', '', '', $dni, '1');
        
        if (count($receta) > 0) {
            return view('frontend.recetas.allbypaciente', compact('receta'));
        } else {
            return redirect('/atender_receta/' . $request->user)->withInput()->withErrors(array('No tiene recetas..!'));
        }
    }
	*/

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

    public function search($id_user) {
        $modelfarma = new Farmacia;
        $id_farmacia = $modelfarma->GetFarmaByUser($id_user);
        //var_dump($id_farmacia,$id_user ); exit;
        return view('frontend.recetas.search', ['farmacia' => $id_farmacia], ['usuario' => $id_user]);
    }
    
    public function recetas_atendidas($id_user){
        
        $modelfarma = new Farmacia;
        $farmacia = $modelfarma->GetFarmaByUser($id_user);
        $id_farmacia=$farmacia->id;
        //var_dump($id_farmacia); exit;
        //----------------------------------------------------------------------
        $usuario=$id_user;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $modelReceta=new Receta_vale();
        $recetas=$modelReceta->AllRecetasByFarmaAndFecha($id_farmacia, $fecha); 
        //var_dump($recetas); exit;        
        $nro_atendidas=$modelReceta->GetCountRecetasByUserAndFecha($id_farmacia, $fecha);
        return view('frontend.receta_vales.recetas_atendidas', compact('recetas','fecha','usuario','nro_atendidas','farmacia'));
    }
    
    
    public function recetas_invalidadas($id_farmacia, $id_user){
        //----------------------------------------------------------------------
        $modelfarma = new Farmacia;
        $farmacia = $modelfarma->GetFarmaByUser($id_user);
        $usuario=$id_user;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $modelReceta=new Receta_vale();
        $recetas=$modelReceta->AllRecetasInvalidasByFarmaAndFecha($id_farmacia, $fecha);        
        $nro_atendidas=$modelReceta->GetCountRecetasInvalidadasByUserAndFecha($id_farmacia, $fecha);
        return view('frontend.receta_vales.recetas_invalidadas', compact('recetas','fecha','usuario','nro_atendidas','farmacia','usuario_creador'));
    }
    
    public function listar_recetas(Request $request){
        $modelfarma = new Farmacia;
        $farmacia = $modelfarma->GetFarmaByUser($request->id_user);
        $id_farmacia=$farmacia->id;
        //----------------------------------------------------------------------
        $usuario=$request->id_user;        
        $fecha = $request->fecha;
		$fechaHasta = $request->fechaHasta;
        $modelReceta=new Receta_vale();
        //$recetas=$modelReceta->AllRecetasByFarmaAndFecha($id_farmacia, $fecha);
		//echo $id_farmacia."|".$fecha."|".$fechaHasta;exit();
		$recetas=$modelReceta->AllRecetasByFarmaAndFechaDesdeHasta($id_farmacia, $fecha ,$fechaHasta);
        //var_dump($recetas); exit;
        //$nro_atendidas=$modelReceta->GetCountRecetasByUserAndFecha($id_farmacia, $fecha);
		$nro_atendidas=$modelReceta->GetCountRecetasByUserAndFechaDesdeHasta($id_farmacia, $fecha ,$fechaHasta);
        return view('frontend.receta_vales.recetas_atendidas', compact('recetas','fecha','usuario','nro_atendidas','farmacia','fechaHasta'));
    }
	
	public function listar_recetas_nuevo(Request $request) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        if (!$request->id_user):
            return redirect('/all_recetas_atendidas_by_farmacia');
        endif;

        $modelfarma = new Farmacia;
        $farmacia = $modelfarma->GetFarmaByUser($request->id_user);
        $id_farmacia = $farmacia->id;
        $modo_validacion = $farmacia->modo_validacion;
        //----------------------------------------------------------------------
        $usuario = $request->id_user;
        $fechaDesde = $request->fechaDesde;
        $fechaHasta = $request->fechaHasta;
        $dni_beneficiario = strtoupper($request->dni_beneficiario);
        $nro_receta = $request->nro_receta;
        $estado = $request->estado;
        $iddispensador = $request->iddispensador;
        $id_consultorio = $request->id_consultorio;
        
        $modelReceta = new Receta_vale();
        $model_consultorio = new Consultorio();
        $model_upss = new Upss();
        
        $dispensador=$modelReceta->AllDispensadorByFarmacia($id_farmacia);

        if($id_farmacia==19){
          $consultorio = $model_upss->ShowUPSS_mergencia();  
        }
        else{
          $consultorio = $model_consultorio->ShowConsultorios($id_farmacia);
        }
        
        if ($estado == 1):
            $recetas = $modelReceta->AllRecetasByFarmaAndFechaDesdeHasta($id_farmacia, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_receta, $iddispensador, $id_consultorio);
            //$cantidad = $modelReceta->GetCountRecetasByUserAndFechaDesdeHasta($id_farmacia, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_receta, $iddispensador, $id_consultorio);//Cuenta resultado
        else:
            $recetas = $modelReceta->AllRecetasInvalidasByFarmaAndFechaDesdeHasta($id_farmacia, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_receta, $iddispensador, $id_consultorio);
            //$cantidad = $modelReceta->GetCountRecetasInvalidasByUserAndFechaDesdeHasta($id_farmacia, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_receta, $iddispensador, $id_consultorio);
        endif;
        //print_r($recetas);exit();
        return view('frontend.receta_vales.all_recetas_atendidas_by_farmacia', compact('recetas', 'fechaDesde', 'usuario', 'farmacia', 'fechaHasta', 'estado', 'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nro_receta', 'dispensador', 'iddispensador','consultorio','id_consultorio'));
    }

	public function listar_recetas_prescrita(Request $request) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        if (!$request->id_user):
            return redirect('/all_recetas_prescritas_by_farmacia');
        endif;

        $modelfarma = new Farmacia;
        $farmacia = $modelfarma->GetFarmaByUser($request->id_user);
        $id_farmacia = $farmacia->id;
		$id_establecimiento = $farmacia->id_establecimiento;
        $modo_validacion = $farmacia->modo_validacion;
        //----------------------------------------------------------------------
        $usuario = $request->id_user;
        $fechaDesde = $request->fechaDesde;
        $fechaHasta = $request->fechaHasta;
        $dni_beneficiario = strtoupper($request->dni_beneficiario);
        $nro_receta = $request->nro_receta;
        $iddispensador=0;
        $id_consultorio = $request->id_consultorio;
        
        $modelReceta = new Receta_vale();
        $model_consultorio = new Consultorio();
        $model_upss = new Upss();
        
        $establecimiento = $modelfarma->ObtenerNivelFarmacia($id_farmacia);
        if($id_establecimiento == 76 ){
            $recetas = $modelReceta->AllRecetasPrescritasByFarmaAndFechaDesdeHasta($id_farmacia, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_receta, $iddispensador,$id_consultorio);
            //dd($recetas);
            $cantidad = $modelReceta->GetCountRecetasPrescritasByUserFarmaciaAndFechaDesdeHasta($id_farmacia, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_receta, $iddispensador,$id_consultorio);//Cuenta resultado
        }
        else{
                $recetas = $modelReceta->AllRecetasPrescritasByEstablecimientoAndFechaDesdeHasta($id_establecimiento, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_receta, $iddispensador,$id_consultorio);
            $cantidad = $modelReceta->GetCountRecetasPrescritasByUserAndFechaDesdeHasta($id_establecimiento, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_receta, $iddispensador,$id_consultorio);//Cuenta resultado
        }

        if($id_farmacia==19){
          $consultorio = $model_upss->ShowUPSS_mergencia();  
        }
        else{
          $consultorio = $model_consultorio->ShowConsultorios($id_farmacia);
        }
        
        $variable=2;
        return view('frontend.receta_vales.all_recetas_prescritas_by_farmacia', compact('recetas', 'fechaDesde', 'usuario', 'cantidad', 'farmacia', 'fechaHasta', 'estado', 'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nro_receta','variable','consultorio','id_consultorio'));
    }
/*	public function listar_recetas_prescrita(Request $request) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        if (!$request->id_user):
            return redirect('/all_recetas_prescritas_by_farmacia');
        endif;

        $modelfarma = new Farmacia;
        $farmacia = $modelfarma->GetFarmaByUser($request->id_user);
        $id_farmacia = $farmacia->id;
		$id_establecimiento = $farmacia->id_establecimiento;
        $modo_validacion = $farmacia->modo_validacion;
        //----------------------------------------------------------------------
        $usuario = $request->id_user;
        $fechaDesde = $request->fechaDesde;
        $fechaHasta = $request->fechaHasta;
        $dni_beneficiario = strtoupper($request->dni_beneficiario);
        $nro_receta = $request->nro_receta;
        $estado = $request->estado;
        $iddispensador = $request->iddispensador;
        
        $modelReceta = new Receta_vale();
        
        $dispensador=$modelReceta->AllDispensadorByFarmacia($id_farmacia);
        
        if ($estado == 1):
            $establecimiento = $modelfarma->ObtenerNivelFarmacia($id_farmacia);
            //if($establecimiento->nivel!=3):
                $recetas = $modelReceta->AllRecetasPrescritasByEstablecimientoAndFechaDesdeHasta($id_establecimiento, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_receta, $iddispensador);
                $cantidad = $modelReceta->GetCountRecetasPrescritasByUserAndFechaDesdeHasta($id_establecimiento, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_receta, $iddispensador);//Cuenta resultado
            //else:
            //    $recetas = $modelReceta->AllRecetasPrescritasByFarmaAndFechaDesdeHasta($id_farmacia, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_receta, $iddispensador);
            
            //   $cantidad = $modelReceta->GetCountRecetasPrescritasByUserFarmaciaAndFechaDesdeHasta($id_farmacia, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_receta, $iddispensador);
            //endif;
        else:
            $recetas = $modelReceta->AllRecetasInvalidasByFarmaAndFechaDesdeHasta($id_farmacia, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_receta, $iddispensador);
            $cantidad = $modelReceta->GetCountRecetasInvalidasByUserAndFechaDesdeHasta($id_farmacia, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_receta, $iddispensador);
        endif;
        //print_r($recetas);exit();
        return view('frontend.receta_vales.all_recetas_prescritas_by_farmacia', compact('recetas', 'fechaDesde', 'usuario', 'cantidad', 'farmacia', 'fechaHasta', 'estado', 'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nro_receta', 'dispensador', 'iddispensador'));
    }
*/	
    public function listar_beneficiarios_servicios(Request $request) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        if (!$request->id_user):
            return redirect('/all_recetas_atendidas_by_servicio');
        endif;

        $modelfarma = new Farmacia;
        $farmacia = $modelfarma->GetFarmaByUser($request->id_user);
        $id_farmacia = $farmacia->id;
        $modo_validacion = $farmacia->modo_validacion;
        //----------------------------------------------------------------------
        $usuario = $request->id_user;
        $fechaDesde = $request->fechaDesde;
        $fechaHasta = $request->fechaHasta;
        $dni_beneficiario = strtoupper($request->dni_beneficiario);
        $nro_receta = $request->nro_receta;
        $estado = $request->estado;
        $idconsultorio = $request->idconsultorio;
        
        $modelReceta = new Receta_vale();

        //$dispensador=$modelReceta->AllDispensadorByFarmacia($id_farmacia);

        $model_consultorio = new Consultorio;
        $consultorio = $model_consultorio->ShowConsultorios($id_farmacia);
        
        
        $recetas = $modelReceta->AllRecetasByFarmaAndFechaDesdeHastaServicio($id_farmacia, $fechaDesde, $fechaHasta, $idconsultorio);
        $cantidad = $modelReceta->GetCountRecetasByUserAndFechaDesdeHastaServicio($id_farmacia, $fechaDesde, $fechaHasta, $idconsultorio);//Cuenta resultado
    
        //print_r($recetas);exit();
        return view('frontend.receta_vales.all_recetas_atendidas_by_servicio', compact('recetas', 'fechaDesde', 'usuario', 'cantidad', 'farmacia', 'fechaHasta', 'estado', 'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nro_receta', 'consultorio', 'idconsultorio'));
    }

    public function listar_trama(Request $request) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        if (!$request->id_user):
            return redirect('/trama_saludpol');
        endif;

        $model_farma = new Farmacia();
        $farmacia = $model_farma->GetFarmaByUser($request->id_user);
        $id_farmacia = $farmacia->id;
        $modo_validacion = $farmacia->modo_validacion;
        $id_establecimiento = $farmacia->id_establecimiento;
        //----------------------------------------------------------------------
        $usuario = $request->id_user;
        $fechaDesde = $request->fechaDesde;
        $fechaHasta = $request->fechaHasta;
        
        $model_recetas = new Receta_vale();

        $recetas = $model_recetas->TramaAllRecetasByFarmaAndFechaDesdeHasta($id_establecimiento, $fechaDesde, $fechaHasta);

        return view('frontend.receta_vales.trama_saludpol', compact('recetas', 'fechaDesde', 'usuario','fechaHasta', 'modo_validacion','id_establecimiento'));
    }

    public function listar_hospitalizados(Request $request) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        if (!$request->id_user):
            return redirect('/all_hospitalizados_registrados_by_farmacia');
        endif;

        $modelfarma = new Farmacia;
        $farmacia = $modelfarma->GetFarmaByUser($request->id_user);
        $id_farmacia = 14; //$farmacia->id;
        $modo_validacion = $farmacia->modo_validacion;
        //----------------------------------------------------------------------
        $usuario = $request->id_user;
        $fechaDesde = $request->fechaDesde;
        $fechaHasta = $request->fechaHasta;
        $dni_beneficiario = strtoupper($request->dni_beneficiario);
        $nro_hospitalizacion = $request->nro_hospitalizacion;
        $estado = $request->estado;
        $iddispensador = $request->iddispensador;
        
        /*
          if($dni_beneficiario!="" || $nro_receta!=""):
          $fechaDesde   = "";
          $fechaHasta   = "";
          endif;
         */
        $model_hospitalizado = new \App\Models\Hospitalizacione;
        
        if ($estado == 1):
            
            $hospitalizado = $model_hospitalizado->AllHospitalizadosByFarmaAndFechaDesdeHasta($id_farmacia, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_hospitalizacion, $iddispensador);

            $cantidad = $model_hospitalizado->GetCountRegistroByUserAndFechaDesdeHasta($id_farmacia, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_hospitalizacion, $iddispensador);

            $dispensador = $model_hospitalizado->AllRegistradorByFarmacia($id_farmacia);
        else:
            $hospitalizado = $model_hospitalizado->AllHospitalizadosAltaByFarmaAndFechaDesdeHasta($id_farmacia, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_hospitalizacion, $iddispensador);

            $cantidad = $model_hospitalizado->GetCountRegistroAltaByUserAndFechaDesdeHasta($id_farmacia, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_hospitalizacion, $iddispensador);

            $dispensador = $model_hospitalizado->AllRegistradorAltaByFarmacia($id_farmacia);
        endif;
        //print_r($recetas);exit();
        
        return view('frontend.receta_vales.all_hospitalizados_registrados_by_farmacia', compact('hospitalizado', 'fechaDesde', 'usuario', 'cantidad', 'farmacia', 'fechaHasta', 'estado', 'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nro_hospitalizacion', 'dispensador', 'iddispensador'));
    }

    public function listar_admision(Request $request){
		
		if (!Auth::check()):
			return redirect('/auth/login');
		endif;
		
		if (!$request->id_user):
			return redirect('/all_admision_by_farmacia');
		endif;
		
        $modelfarma = new Farmacia;
        $farmacia = $modelfarma->GetFarmaByUser($request->id_user);
        $id_farmacia=$farmacia->id;
        //----------------------------------------------------------------------
        $usuario=$request->id_user;        
        $fechaDesde 		= $request->fechaDesde;
		$fechaHasta 		= $request->fechaHasta;
		$dni_beneficiario 	= $request->dni_beneficiario;
		$nro_admision		= $request->nro_admision;
		$estado				= $request->estado;
        $modelReceta=new Receta_vale();
		/*
		if($dni_beneficiario!="" || $nro_admision!=""):
			$fechaDesde 	= "";
			$fechaHasta 	= "";
		endif;
        */
		$recetas=$modelReceta->AllAdmisionByFarmaAndFechaDesdeHasta($id_farmacia, $fechaDesde ,$fechaHasta, $dni_beneficiario, $nro_admision);
		$cantidad=$modelReceta->GetCountAdmisionByUserAndFechaDesdeHasta($id_farmacia, $fechaDesde ,$fechaHasta, $dni_beneficiario, $nro_admision);
		
        return view('frontend.receta_vales.all_admision_by_farmacia', compact('recetas','fechaDesde','usuario','cantidad','farmacia','fechaHasta','estado','id_farmacia','dni_beneficiario','nro_admision'));
    }
	
	public function listar_quirurgico(Request $request){
	
		if (!Auth::check()):
			return redirect('/auth/login');
		endif;
		
		if (!$request->id_user):
			return redirect('/all_quirurgico_by_farmacia');
		endif;
		
        $modelfarma = new Farmacia;
        $farmacia = $modelfarma->GetFarmaByUser($request->id_user);
        $id_farmacia=$farmacia->id;
        //----------------------------------------------------------------------
        $usuario=$request->id_user;        
        $fechaDesde 		= $request->fechaDesde;
		$fechaHasta 		= $request->fechaHasta;
		$dni_beneficiario 	= $request->dni_beneficiario;
		$nro_quirurgico		= $request->nro_quirurgico;
		$estado				= $request->estado;
        $modelReceta=new Receta_vale();
		/*
		if($dni_beneficiario!="" || $nro_admision!=""):
			$fechaDesde 	= "";
			$fechaHasta 	= "";
		endif;
        */
		$recetas=$modelReceta->AllQuirurgicoByFarmaAndFechaDesdeHasta($id_farmacia, $fechaDesde ,$fechaHasta, $dni_beneficiario, $nro_quirurgico);
		$cantidad=$modelReceta->GetCountQuirurgicoByUserAndFechaDesdeHasta($id_farmacia, $fechaDesde ,$fechaHasta, $dni_beneficiario, $nro_quirurgico);
		
        return view('frontend.receta_vales.all_quirurgico_by_farmacia', compact('recetas','fechaDesde','usuario','cantidad','farmacia','fechaHasta','estado','id_farmacia','dni_beneficiario','nro_quirurgico'));
    }
	
	public function listar_resumen_recetas_atendidas(Request $request){
		
		if (!Auth::check()):
			return redirect('/auth/login');
		endif;
		
		if (!$request->id_user):
			return redirect('/all_resumen_recetas_atendidas');
		endif;
		
        $modelfarma = new Farmacia;
        $farmacia = $modelfarma->GetFarmaByUser($request->id_user);
        $id_farmacia=$farmacia->id;
        //----------------------------------------------------------------------
        $usuario=$request->id_user;        
        $fechaDesde 		= $request->fechaDesde;
		$fechaHasta 		= $request->fechaHasta;
		//$dni_beneficiario 	= $request->dni_beneficiario;
		//$nro_quirurgico		= $request->nro_quirurgico;
		//$estado				= $request->estado;
        $modelReceta=new Receta_vale();
		/*
		if($dni_beneficiario!="" || $nro_admision!=""):
			$fechaDesde 	= "";
			$fechaHasta 	= "";
		endif;
        */
		$resumen=$modelReceta->AllResumenRecetasAtendidasByFarmaAndFecha($id_farmacia, $fechaDesde ,$fechaHasta);
                //exit(print_r($resumen));
		//$cantidad=$modelReceta->GetCountQuirurgicoByUserAndFechaDesdeHasta($id_farmacia, $fechaDesde ,$fechaHasta, $dni_beneficiario, $nro_quirurgico);
		$cantidad = count($resumen);
        return view('frontend.receta_vales.all_resumen_recetas_atendidas_by_farmacia', compact('resumen','fechaDesde','usuario','cantidad','farmacia','fechaHasta','estado','id_farmacia'));
    }
	
	
	public function listar_kardex(Request $request) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        if (!$request->id_producto):
            return redirect('/all_kardex_by_farmacia');
        endif;

        //----------------------------------------------------------------------
        //$usuario=$request->id_user;        
        $fechaDesde = $request->fechaDesde;
        $fechaHasta = $request->fechaHasta;
        
        $nombre_producto = $request->nombre_producto;
        $id_producto = $request->id_producto;

        $model_farma = new Farmacia;
        $id_user = Auth::user()->id;
        $farma = $model_farma->GetFarmaByUser($id_user);
        $id_farmacia = $farma->id;
        $nombre_farmacia=$farma->nombre;
        //$codigo_producto="59020038";
        $id_producto = $request->id_producto;
        $modelReceta = new Receta_vale();
        $model_kardex = new \App\Models\Kardexe();

        $kardex = $modelReceta->AllKardexByFarmaAndProducto($id_farmacia, $nombre_farmacia, $id_producto, $fechaDesde, $fechaHasta);

        if ($fechaDesde != ""):
            $fechaDesde = strtotime('-1 day', strtotime($fechaDesde));
            $fechaDesde = date('j-m-Y', $fechaDesde);
            $kardex_inicial = $model_kardex->ShowTotalKardexByFarmaciaResumen($id_farmacia, $id_producto, $fechaDesde);
            $kardex_inicial = (isset($kardex_inicial[0])) ? $kardex_inicial[0] : 0;
            $fechaDesde = strtotime('+1 day', strtotime($fechaDesde));
            $fechaDesde = date('j-m-Y', $fechaDesde);
        endif;
        //print_r($fechaDesde);
        return view('frontend.stocks.kardex_lote', compact('kardex', 'kardex_inicial', 'cantidad', 'nombre_producto', 'id_producto', 'fechaDesde', 'fechaHasta'));
    }

    public function listar_kardex_lote_movimiento_producto_receta(Request $request) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        if (!$request->id_producto):
            return redirect('/all_kardex_no_atendido_by_farmacia');
        endif;

        //----------------------------------------------------------------------
        $fechaDesde = $request->fechaDesde;
        $fechaHasta = $request->fechaHasta;
        
        $nombre_producto = $request->nombre_producto;
        $id_producto = $request->id_producto;

        $model_farma = new Farmacia;
        $id_user = Auth::user()->id;
        $farma = $model_farma->GetFarmaByUser($id_user);
        $id_farmacia = $farma->id;
        $nombre_farmacia=$farma->nombre;
        $id_producto = $request->id_producto;
        $modelReceta = new Receta_vale();
        

        $kardex = $modelReceta->AllRecetaKardexByFarmaAndProducto($id_farmacia, $nombre_farmacia, $id_producto, $fechaDesde, $fechaHasta);

        return view('frontend.stocks.kardex_lote_movimiento_producto_receta', compact('kardex', 'nombre_producto', 'id_producto', 'fechaDesde', 'fechaHasta'));
    }
	
	public function consolidado_stock(Request $request) {

        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;
        
        $idconsultorio = ($request->id_consultorio == null) ? 0 : $request->id_consultorio;

        $fechaDesde = $request->fechaDesde;
        $fechaHasta = $request->fechaHasta;
        $id_anaquel = $request->id_anaquel;
        
        if ($id_anaquel == "") $id_anaquel=0;

        $nombre_producto = $request->nombre_producto;
        $id_producto = $request->id_producto;

        $model_farma = new Farmacia;
        $id_user = Auth::user()->id;
        $farma = $model_farma->GetFarmaByUser($id_user);
        $id_farmacia = $farma->id;
        $nombre_farmacia = $farma->nombre;
        $id_producto = $request->id_producto;
        $modelReceta = new Receta_vale();
        $model_kardex = new \App\Models\Kardexe();

        if ($fechaDesde == ""):
            $fechaHasta = $model_kardex->getFechaServidor();
            $fechaDesde = strtotime($fechaHasta);
            $fechaDesde = date('d-m-Y', $fechaDesde); //. " 00:00:00";
            $fechaHasta = $fechaDesde; //. " 23:59:59";
        else:
            $fechaDesde = $fechaDesde;
            $fechaHasta = $fechaHasta;
        endif;
        $modelanaquel= new Anaquele();
        $anaqueles=$modelanaquel->ShowallById_IdFarmacia($id_farmacia);
        
        $kardex = $model_kardex->ShowTotalKardexByFarmaciaConsolidado($id_farmacia, $fechaDesde, $fechaHasta, $id_anaquel, $idconsultorio);
        
        //$kardex = $modelReceta->AllKardexByFarmaAndProducto($id_farmacia, $id_producto, $fechaDesde, $fechaHasta);
        //null
        $model_consultorio = new Consultorio;
        //$consultorio = $model_consultorio->ShowConsultoriosAll();
        $consultorio = $model_consultorio->ShowConsultorios($id_farmacia);

        return view('frontend.stocks.consolidado_stock', compact('kardex', 'kardex_inicial', 'cantidad', 'nombre_producto', 'id_producto', 'fechaDesde', 'fechaHasta', 'anaqueles', 'id_anaquel', 'consultorio', 'idconsultorio'));
    }

    public function listar_cita(Request $request){
		
		if (!Auth::check()):
			return redirect('/auth/login');
		endif;
		
		if (!$request->id_user):
			return redirect('/all_cita_by_farmacia');
		endif;
		
        $modelfarma = new Farmacia;
        $farmacia = $modelfarma->GetFarmaByUser($request->id_user);
        $id_farmacia=$farmacia->id;
        //----------------------------------------------------------------------
        $usuario=$request->id_user;        
        $fechaDesde 		= $request->fechaDesde;
		$fechaHasta 		= $request->fechaHasta;
		$dni_beneficiario 	= $request->dni_beneficiario;
		$nro_cita			= $request->nro_cita;
		$estado				= $request->estado;
        $modelReceta=new Receta_vale();
		
		if($dni_beneficiario!="" || $nro_cita!=""):
			$fechaDesde 	= "";
			$fechaHasta 	= "";
		endif;
        
		$recetas=$modelReceta->AllCitaByFarmaAndFechaDesdeHasta($id_farmacia, $fechaDesde ,$fechaHasta, $dni_beneficiario, $nro_cita);
		$cantidad=$modelReceta->GetCountCitaByUserAndFechaDesdeHasta($id_farmacia, $fechaDesde ,$fechaHasta, $dni_beneficiario, $nro_cita);
		
        return view('frontend.receta_vales.all_cita_by_farmacia', compact('recetas','fechaDesde','usuario','cantidad','farmacia','fechaHasta','estado'));
    }
	
	public function listar_hospitalizacion(Request $request){
	
		if (!Auth::check()):
			return redirect('/auth/login');
		endif;
		
		if (!$request->id_user):
			return redirect('/all_hospitalizacion_by_farmacia');
		endif;
		
        $modelfarma = new Farmacia;
        $farmacia = $modelfarma->GetFarmaByUser($request->id_user);
        $id_farmacia=$farmacia->id;
        //----------------------------------------------------------------------
        $usuario=$request->id_user;        
        $fechaDesde 		= $request->fechaDesde;
		$fechaHasta 		= $request->fechaHasta;
		$dni_beneficiario 	= $request->dni_beneficiario;
		$nro_admision		= $request->nro_admision;
		$estado				= $request->estado;
        $modelReceta=new Receta_vale();
		
		if($dni_beneficiario!="" || $nro_admision!=""):
			$fechaDesde 	= "";
			$fechaHasta 	= "";
		endif;
        
		$recetas=$modelReceta->AllHospitalizacionByFarmaAndFechaDesdeHasta($id_farmacia, $fechaDesde ,$fechaHasta, $dni_beneficiario, $nro_admision);
		$cantidad=$modelReceta->GetCountHospitalizacionByUserAndFechaDesdeHasta($id_farmacia, $fechaDesde ,$fechaHasta, $dni_beneficiario, $nro_admision);
		
        return view('frontend.receta_vales.all_hospitalizacion_by_farmacia', compact('recetas','fechaDesde','usuario','cantidad','farmacia','fechaHasta','estado'));
    }
    
    public function listar_recetas_invalidadas(Request $request){
        $modelfarma = new Farmacia;
        $farmacia = $modelfarma->GetFarmaByUser($request->id_user);
        $id_farmacia=$farmacia->id;
        //----------------------------------------------------------------------
        $usuario=$request->id_user;        
        $fecha = $request->fecha;
        $modelReceta=new Receta_vale();
        $recetas=$modelReceta->AllRecetasInvalidasByFarmaAndFecha($id_farmacia, $fecha);
        //var_dump($recetas); exit;
        $nro_atendidas=$modelReceta->GetCountRecetasInvalidadasByUserAndFecha($id_farmacia, $fecha);
        return view('frontend.receta_vales.recetas_invalidadas', compact('recetas','fecha','usuario','nro_atendidas','farmacia'));
    }

    public function ShowTotalKardexByFarmaciaConsolidado(Request $request)
    {
        $idconsultorio = ($request->id_consultorio == null) ? 0 : $request->id_consultorio;

        $fechaDesde = $request->fechaDesde;
        $fechaHasta = $request->fechaHasta;
        $id_anaquel = $request->id_anaquel;
        
        if ($id_anaquel == "") $id_anaquel=0;

        $model_farma = new Farmacia;
        $id_user = Auth::user()->id;
        $farma = $model_farma->GetFarmaByUser($id_user);
        $id_farmacia = $farma->id;
        $model_kardex = new \App\Models\Kardexe();

        if ($fechaDesde == ""):
            $fechaHasta = $model_kardex->getFechaServidor();
            $fechaDesde = strtotime($fechaHasta);
            $fechaDesde = date('d-m-Y', $fechaDesde); //. " 00:00:00";
            $fechaHasta = $fechaDesde; //. " 23:59:59";
        else:
            $fechaDesde = $fechaDesde;
            $fechaHasta = $fechaHasta;
        endif;
        $kardexs = $model_kardex->ShowTotalKardexByFarmaciaConsolidado($id_farmacia, $fechaDesde, $fechaHasta, $id_anaquel, $idconsultorio);
        return response()->json($kardexs);
    }

    public function newConsolidadoStock(Request $request) {

        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;
        
        $idconsultorio = 0;
        $id_anaquel = 0;
        $nombre_producto = $request->nombre_producto;

        $model_farma = new Farmacia;
        $id_user = Auth::user()->id;
        $farma = $model_farma->GetFarmaByUser($id_user);
        $id_farmacia = $farma->id;
        $nombre_farmacia = $farma->nombre;
        $modelReceta = new Receta_vale();
        $model_kardex = new \App\Models\Kardexe();

        $fechaHasta = $model_kardex->getFechaServidor();
        $fechaDesde = strtotime($fechaHasta);
        $fechaDesde = date('d-m-Y', $fechaDesde); //. " 00:00:00";
        $fechaHasta = $fechaDesde; //. " 23:59:59";
        $modelanaquel= new Anaquele();
        $anaqueles=$modelanaquel->ShowallById_IdFarmacia($id_farmacia);
        
        //$kardex = $model_kardex->ShowTotalKardexByFarmaciaConsolidado($id_farmacia, $fechaDesde, $fechaHasta, $id_anaquel, $idconsultorio);
        
        $model_consultorio = new Consultorio;
        $consultorio = $model_consultorio->ShowConsultorios($id_farmacia);

        return view('frontend.stocks.consolidado_stockV2', compact('fechaDesde', 'fechaHasta', 'anaqueles', 'id_anaquel', 'consultorio', 'idconsultorio'));
    }
    
    
    

}
