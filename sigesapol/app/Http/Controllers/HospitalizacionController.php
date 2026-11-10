<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use Illuminate\Support\Facades\DB;
use App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Farmacia;
use App\Models\Receta_vale;
use App\Models\Cita;
use App\Models\Prestaciones;
use App\Models\User;
use App\Models\Consultorio;
use App\Models\Medico;
use App\Models\Kardexe;
use App\Models\Producto;
use App\Models\Medico_consultorio;
use App\Models\Hospitalizacione;
use App\Models\Stock_farmacia;
use App\Models\Asegurado;
use App\Models\Evaluacione;
use App\Models\Establecimiento;
use App\Models\Admision_enfermeria;
use App\Models\Paciente;
use App\Models\Asegurado_historia;
use App\Models\Tipo_grado;
use App\Models\Triaje;
use App\Models\Distrito;
use App\Models\Tipo_parentescos;
use App\Models\Emergencia;
use SoapClient;
use App\Models\Upss;
use App\Models\Cama;
use App\Models\Cuarto;
use App\Models\Consejeria;
use App\Models\CqxIntervencion;
use App\Models\CqxProfesional;
use App\Models\Discapacidad;
use App\Models\Enfermeria;
use App\Models\Gestacion;
use App\Models\HospitalizacionAntecedentes;
use App\Models\Nati;
use App\Models\Pabellone;
use App\Models\Prestacion_procedimiento;
use App\Models\Prestacion_eventos;
use App\Models\Producto_receta;
use App\Models\Puerperio;
use App\Models\Receta_diagnostico;
use App\Models\SubConsultorio;
use App\Models\Tipo_documento_identidad;
use App\Models\tipoDocNati;
use Illuminate\Support\Facades\Redis;
use stdClass;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\CqxAtencione;

class HospitalizacionController extends Controller
{ 

  function __construct()
  {
      $this->middleware('auth'); 
  }
  
  public function all_atencion_hospitalizacion($id_user = 0) {
		
        $model_medico = new \App\Models\Medico();
        $model_usuario = new User;
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1)
            ;$ruta = $ruta[0];
		if($ruta=="listar_atenciones_hospitalizacion");$ruta="all_atencion_hospitalizacion";
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
			
        $id_user = Auth::user()->id;
        $usuario_actual = $model_usuario->GetById($id_user);
        //$flaghospitalizacion = $usuario_actual->flaghospitalizacion;
        $flagemergenciareferencia = $usuario_actual->flaghistoria;
		$id_consultorio_actual = $usuario_actual->id_consultorio;
		//dd($id_consultorio_actual);

        $dni_beneficiario = "";
        $nombre_beneficiario = "";
        $estado = "0";
        //$medico = $usuario_actual->dni;

        $date = Carbon::now();
        $fechaDesde = $date->format('d-m-Y');
        $fechaHasta = $date->format('d-m-Y');

        //====================
        $model_farma = new Farmacia();
        $farmacia = $model_farma->GetFarmaByUser($id_user);
        $id_farmacia = $farmacia->id;
        $modo_validacion = $farmacia->modo_validacion;
		
		$medico = 0;
		if($flagemergenciareferencia!=1){
			$medico_actual = $model_medico->GetMedicoByDniAndEstablecimiento($usuario_actual->dni,$farmacia->id_establecimiento);
			$medico = $medico_actual->id;
		}
        
		$model_consultorio = new Consultorio;
		$consultorio = $model_consultorio->ShowAllConsultoriosSubConsultorio($farmacia->id_establecimiento);
		
        return view('frontend.hospitalizacion.all_atenciones_hospitalizacion', compact('farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nombre_beneficiario', 'estado','flagemergenciareferencia','consultorio','medico','id_consultorio_actual'));
    }

    public function listar_prestacion_hospitalizacion(Request $request){
		
		$model_hospitalizacion = new Hospitalizacione();
		$p[]=$request->fecha_ini; //0
		$p[]=$request->fecha_fin;  //1
		$p[]=$request->dni_beneficiario; //2
		$p[]=$request->nombre_beneficiario;  //3
		//$p[]=$request->id_servicio;  //4
		$p[]="0";  //4
		//$p[]=$request->id_consultorio;  //5
		$p[]="0";  //6
		//$p[]=0;
		//$p[]=0;
		//$p[]=0;
		$p[]=$request->id_medico;
		$p[]=$request->estado;  //7
		$p[]=3;  //8 tipo_atencion
		$p[]=Auth::user()->id_establecimiento;  //9
		$p[]=$request->NumeroPagina;  //8
		$p[]=$request->NumeroRegistros;  //9
		
		$data = $model_hospitalizacion->listar_prestacion_hospitalizacion($p);
		$iTotalDisplayRecords = isset($data[0]->totalrows)?$data[0]->totalrows:0;
		//print_r($afiliacion);exit();
		
		$result["PageStart"] = $request->NumeroPagina;
		$result["pageSize"] = $request->NumeroRegistros;
		$result["SearchText"] = "";
		$result["ShowChildren"] = true;
		$result["iTotalRecords"] = $iTotalDisplayRecords;
		$result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
		$result["aaData"] = $data;
		
		echo json_encode($result);
		
	}
	

    public function formulario_atencion_hospitalizacion() {
		
		if (!Auth::check()):
            return redirect('/auth/login');
        endif;
		
		$model_medico = new Medico();
		$model_usuario = new User;
		
		$id_user = Auth::user()->id;
		$usuario_actual = $model_usuario->GetById($id_user);
        $id_farmacia = Auth::user()->id_farmacia;
		
		$model_consultorio = new Consultorio;
		$model_kardex = new Kardexe();
        $fechaServidor = $model_kardex->getFechaServidor();
		
		$model_farma = new Farmacia();
		$farmacia = $model_farma->GetFarmaByIdfarma($id_farmacia);
		$id_ipress = $farmacia->id_establecimiento;
		
        $model_producto = new Producto;
        $model_establecimiento = new \App\Models\Establecimiento;
        $establecimiento = $model_establecimiento->GetById($id_ipress);
        
		
		$medico = $model_medico->GetDatosMedicoByDniAndEstablecimiento($usuario_actual->dni,$farmacia->id_establecimiento);
		$tipo_atencion = $model_farma->GetFarmaciaTipoAtencionByIdFarmacia($id_farmacia);
        if($id_ipress==1){
            $nivel=2;
        }
        else
        {
            $nivel = $establecimiento[0]->nivel;
        }
		
        $consultorio = $model_consultorio->ShowConsultorios($id_farmacia);
		$medico_consultorio = new Medico_consultorio();
		$consultorio_medico = $medico_consultorio->GetConsultorioByIdEstablecimientoAndIdMedico($farmacia->id_establecimiento, $medico->id);
		$establecimiento=$establecimiento[0];
		$farmacias = $model_farma->GetFarmaciaByIdEstablecimiento($farmacia->id_establecimiento);
		if($nivel==3):
			if (Auth::user()->flaghospitalizacion==1) {
				$farmacia_atencion=128;
			}
			else{
				if (Auth::user()->flagemergencia==1) {
					$farmacia_atencion=19;
				}
				else
				{
					$farmacia_atencion=3;
				}
			}
		else:
			$farmacia_atencion=Auth::user()->id_farmacia;
		endif;
		
		return view('frontend.hospitalizacion.form_atencion_nuevo', compact('fechaServidor','consultorio','tipo_atencion','medico','establecimiento','consultorio_medico','farmacias','nivel','farmacia_atencion'));
		
	}
	
    public function all_citas_medico_paciente($id_user = 0) {
        $model_medico = new \App\Models\Medico();
        $model_usuario = new User;
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
		
        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1)
            ;$ruta = $ruta[0];
		if($ruta=="listar_atenciones_hospitalizacion");$ruta="listar_atenciones_hospitalizacion";
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        
        $id_user = Auth::user()->id;
        $usuario_actual = $model_usuario->GetById($id_user);
		$flaghospitalizacion = $usuario_actual->flaghospitalizacion;
		$id_consultorio_actual = $usuario_actual->id_consultorio;
		//echo $id_consultorio;
		//echo $flagemergenciareferencia;
        $dni_beneficiario = "";
        $nombre_beneficiario = "";
        $estado = "1";

        $date = Carbon::now();
        $fechaDesde = $date->format('d-m-Y');
        $fechaHasta = $date->format('d-m-Y');

        //====================
        $model_farma = new Farmacia();
        $farmacia = $model_farma->GetFarmaByUser($id_user);
        $id_farmacia = $farmacia->id;
        $modo_validacion = $farmacia->modo_validacion;
		
		$medico = 0;
		if($flaghospitalizacion!=1){
			$medico_actual = $model_medico->GetMedicoByDniAndEstablecimiento($usuario_actual->dni,$farmacia->id_establecimiento);
			$medico = $medico_actual->id;
		}
		$model_consultorio = new Consultorio;
		$consultorio = $model_consultorio->ShowAllConsultoriosSubConsultorio($farmacia->id_establecimiento);
		
        return view('frontend.receta_vales.all_citas_medico_paciente', compact('farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nombre_beneficiario', 'estado','flagemergenciareferencia','consultorio','medico','id_consultorio_actual'));
    }
	
    public function listar_citas_medico_paciente(Request $request) {
        $model_medico = new \App\Models\Medico();
        $model_usuario = new User;

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        if (!$request->id_user):
            return redirect('/all_citas_medico_paciente');
        endif;

        $modelfarma = new Farmacia;
        $farmacia = $modelfarma->GetFarmaByUser($request->id_user);
        $id_farmacia = $farmacia->id;
        $modo_validacion = $farmacia->modo_validacion;
        //----------------------------------------------------------------------

        $id_user = $request->id_user;
        $fechaDesde = $request->fechaDesde;
        //$fechaHasta = $request->fechaHasta;
        $dni_beneficiario = strtoupper($request->dni_beneficiario);
        $nombre_beneficiario = strtoupper($request->nombre_beneficiario);
        //$nro_receta = $request->nro_receta;
        $estado = $request->estado;

        $usuario_actual = $model_usuario->GetById($id_user);
		$flagemergenciareferencia = $usuario_actual->flaghistoria;
        //$medico_actual = $model_medico->GetMedicoByDNI($usuario_actual->dni);
		$medico = 0;
		if($flagemergenciareferencia!=1){
			$medico_actual = $model_medico->GetMedicoByDniAndEstablecimiento($usuario_actual->dni,$farmacia->id_establecimiento);
			$medico = $medico_actual->id;
		}
        //print_r($medico_actual);exit();
        /*
        $obtner_dni_medico = $model_medico->GetMedicoByDNI($user->dni);
        if (count($obtner_dni_medico) > 0) {
            $medico = $model_medico->GetMedicoByDNI($user->dni);
            $existe_medico = 1;
        } else {
            $existe_medico = 0;
        }*/

        //$iddispensador = $request->iddispensador;
        
        //$modelReceta = new Receta_vale();
        $model_citas = new Cita();

        $citas = $model_citas->AllCitaConfirmaPacienteFecha($fechaDesde, $dni_beneficiario, $nombre_beneficiario, $medico, $estado);
        
        $cantidad = $model_citas->GetCountCitaConfirmaPacienteFecha($fechaDesde, $dni_beneficiario, $nombre_beneficiario, $medico, $estado);
        //$cantidad = '1';


        //print_r($recetas);exit();
        return view('frontend.receta_vales.all_citas_medico_paciente', compact('citas', 'cantidad', 'farmacia', 'fechaDesde',  'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nombre_beneficiario', 'estado','flagemergenciareferencia'));
    }
	
	public function modal_asignar_consultorio(){
		
		if (!Auth::check()):
			return redirect('/auth/login');
		endif;
		
		$id_user = Auth::user()->id;
		$model_farma = new Farmacia();
        $farmacia = $model_farma->GetFarmaByUser($id_user);
		$model_consultorio = new Consultorio;
		
		//$consultorio = $model_consultorio->ShowAllConsultorios($farmacia->id_establecimiento);
		$consultorio = $model_consultorio->ShowAllConsultoriosSubConsultorio($farmacia->id_establecimiento);
		//$historia = \App\Models\Asegurado_historia::find($id);
		//$asegurado = \App\Models\Asegurado::find($historia->id_asegurado);
		return view('frontend.citas.modal_asignar_consultorio',compact('consultorio')/*,compact('medicos','horario','fecha_atencion','id_ipress','id_consultorio')*/);
	}
	
	public function asignar_cita(Request $request){
		
		foreach ($request->id_cita as $key => $value):
			$cita = Cita::where('id', '=', $value)->first();
			$cita->id_sub_consultorio = $request->id_consultorio;
			$cita->id_medico = $request->id_medico;
			$cita->save();
		endforeach;
		
	}
	
	public function listar_cita_ajax(Request $request){
		
		$model_citas = new Cita();
		$p[]=$request->fecha_ini;
		$p[]=$request->fecha_fin;
		$p[]=$request->dni_beneficiario;
		$p[]=$request->nombre_beneficiario;
		//$p[]=$request->id_servicio;
		$p[]=(isset($request->id_servicio) && $request->id_servicio!="")?$request->id_servicio:0;
		//$p[]=$request->id_consultorio;
		$p[]=(isset($request->id_consultorio) && $request->id_consultorio!="")?$request->id_consultorio:0;
		$p[]=$request->id_medico;
		//$p[]=0;
		//$p[]=0;
		//$p[]=0;
		$p[]=$request->estado;
		$p[]=$request->NumeroPagina;
		$p[]=$request->NumeroRegistros;
		$data = $model_citas->listar_cita_ajax($p);
		$iTotalDisplayRecords = isset($data[0]->totalrows)?$data[0]->totalrows:0;
		//print_r($afiliacion);exit();
		
		$result["PageStart"] = $request->NumeroPagina;
		$result["pageSize"] = $request->NumeroRegistros;
		$result["SearchText"] = "";
		$result["ShowChildren"] = true;
		$result["iTotalRecords"] = $iTotalDisplayRecords;
		$result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
		$result["aaData"] = $data;

		echo json_encode($result);
		
	}

	
	
	
	
	public function listar_citas_medico_atenciones(Request $request) {
        $model_medico = new \App\Models\Medico();
        $model_usuario = new User;

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        if (!$request->id_user):
            return redirect('/all_citas_medico_atenciones');
        endif;

        $modelfarma = new Farmacia;
        $farmacia = $modelfarma->GetFarmaByUser($request->id_user);
        $id_farmacia = $farmacia->id;
        $modo_validacion = $farmacia->modo_validacion;
        //----------------------------------------------------------------------

        $id_user = $request->id_user;
        $fechaDesde = $request->fechaDesde;
        //$fechaHasta = $request->fechaHasta;
        $dni_beneficiario = strtoupper($request->dni_beneficiario);
        $nombre_beneficiario = strtoupper($request->nombre_beneficiario);
        //$nro_receta = $request->nro_receta;
        $estado = $request->estado;

        $usuario_actual = $model_usuario->GetById($id_user);
		$flagemergenciareferencia = $usuario_actual->flaghistoria;
        //$medico_actual = $model_medico->GetMedicoByDNI($usuario_actual->dni);
		$medico = 0;
		if($flagemergenciareferencia!=1){
			$medico_actual = $model_medico->GetMedicoByDniAndEstablecimiento($usuario_actual->dni,$farmacia->id_establecimiento);
        	$medico = $medico_actual->id;
		}
        $model_citas = new Cita();
        $citas = $model_citas->AllCitaConfirmaAtencionFecha($fechaDesde, $dni_beneficiario, $nombre_beneficiario, $medico, $estado);        
        $cantidad = $model_citas->GetCountCitaConfirmaAtencionFecha($fechaDesde, $dni_beneficiario, $nombre_beneficiario, $medico, $estado);

        //print_r($recetas);exit();
        return view('frontend.receta_vales.all_citas_medico_atenciones', compact('citas', 'cantidad', 'farmacia', 'fechaDesde',  'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nombre_beneficiario', 'estado','flagemergenciareferencia'));
    }
	
	public function obtener_consultorio_cita($id_establecimiento,$tipodoc_beneficiario,$dni_beneficiario) {
	
		$model_cita = new Cita();
        $consultorio = $model_cita->GetServicioCitaByAseguradoAndEstablecimiento($tipodoc_beneficiario,$dni_beneficiario,$id_establecimiento);
        return $consultorio;
    }
	
	public function obtener_consultorio_cita_general($id_establecimiento,$tipodoc_beneficiario,$dni_beneficiario) {
	
		$model_cita = new Cita();
        $consultorio = $model_cita->GetServicioCitaGeneralByAseguradoAndEstablecimiento($tipodoc_beneficiario,$dni_beneficiario,$id_establecimiento);
        return $consultorio;
    }
	
	public function anular_cita($id_cita) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $cita = Cita::where('id', $id_cita)->first(); 
		$cita->id_estado_reg = 0;
		$cita->id_estado_cita = 5;
        $cita->save();
		
        $array["sw"] = true;
        echo json_encode($array);
		
    }
	
	public function anular_prestacion($id_prestacion) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
		
        $prestacion = Prestaciones::where('id', $id_prestacion)->first(); 
		$prestacion->id_estado_reg = 0;
        $prestacion->save();
		
		$cita = Cita::where('id', $prestacion->id_cita)->first(); 
		$cita->id_estado_reg = 0;
		$cita->id_estado_cita = 5;
        $cita->save();
		
		$recetas = Receta_vale::where('id_prestacion', '=', $id_prestacion)->where('estado', '<>', 0)->get();
		foreach ($recetas as $rv) {
            $rv->estado = 0;
            $rv->save();
        }
		
        $array["sw"] = true;
        echo json_encode($array);
		
    }
	
	public function update_historia(Request $request) {
        //$usuario = new User;
		$historia = \App\Models\Asegurado_historia::find($request->id);
        //$historia = new \App\Models\Asegurado_historia;
        //$historia->id_asegurado = $request->id_asegurado;
		//$historia->id_establecimiento = 76;
        $historia->nro_historia = $request->nro_historia;
		//$historia->id_estado_reg = 1;
		$historia->updated_at = Carbon::now();
		$historia->updated_us = $request->id_user;
        $historia->save();

        return redirect('/all_citas_medico_paciente')->withFlashSuccess("Se actualizo correctamente la Historia");
    }

    public function cargarstock_porfarmacia($id_producto, $id_farmacia) {
		//echo "como estas";exit();
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $model_producto = new Producto();
        $model_stock = new Stock_farmacia();
        $model_kardex = new Kardexe();

        $cantidad_farmacia = 0;$cantidadEstablecimiento = 0;$cantidadAlmacen = 0;

        if($id_producto!=null):
          $productos = $model_producto->GetCodByIdProducto($id_producto);
          $codigo_producto = $productos->codigo;
          
          
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
          
      
          $totalEstablecimiento = $model_kardex->ShowTotalCantidadByIdProductoAndIdEstablecimiento2($id_producto, $id_establecimiento, 0);
          /*if (isset($totalEstablecimiento->total) && $totalEstablecimiento->total > 0)$cantidadEstablecimiento = $totalEstablecimiento->total;
      */
          
        
          foreach ($totalEstablecimiento as $row) {
            if ($row->id_farmacia == $id_farmacia) {
              $cantidad_farmacia = $row->total_suma;
            }
            $cantidadEstablecimiento += $row->total_suma;
          }           

        endif;

		    //if (isset($totalAlmacen->total) && $totalAlmacen->total > 0)$cantidadAlmacen = $totalAlmacen->total;
		
        $array = array();
        $array["cantidad"] = $cantidad_farmacia;
		    $array["cantidadEstablecimiento"] = $cantidadEstablecimiento;
		    $array["cantidadAlmacen"] = $cantidadAlmacen;
		    echo json_encode($array);
    }

    public function ver_prestacion_hospitalizacion_anterior($id_user, $id_prestacion) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $modelfarma = new Farmacia;
		$model_receta = new Receta_vale;
		$model_producto = new Producto;
		$model_productos_receta = new Producto_receta;
		$model_establecimiento = new \App\Models\Establecimiento;
		$model_prestacion = new \App\Models\Prestaciones;
		$model_kardex = new Kardexe();
		
        $farmacia = $modelfarma->GetFarmaByUser($id_user);
        $id_farmacia = $farmacia->id;
        $flag_receta_dias = $farmacia->flag_receta_dias;
		
        $fechaServidor = $model_kardex->getFechaServidor();
        $date = Carbon::now();
        $fecha_actual = $date->format('d-m-Y');
        $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);		
		$receta = $model_receta->GetDatosRecetasByIdPrestacion($id_prestacion);
		$diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
		$procedimiento = $model_prestacion->GetProcedimientoByIdPrestacion($id_prestacion);
		$id_ipress = $farmacia->id_establecimiento;
		$establecimiento = $model_establecimiento->GetById($id_ipress);
		if($id_ipress==1){
            $nivel=2;
        }
        else
        {
            $nivel = $establecimiento[0]->nivel;   
        }
		$producto = $model_producto->getProductosByNivelAndIdServicio($nivel, $prestacion->id_consultorio,$id_farmacia);
		
		$farmacias = $modelfarma->GetFarmaciaByIdEstablecimiento($id_ipress);
        return view('frontend.hospitalizacion.ver_atencion', compact('farmacia','prestacion','diagnostico','procedimiento','medicamentos','id_prestacion','fechaServidor','receta','producto','farmacias'));
    }

	//nuevo modulo hospitalizacion

	public function all_pendientes_hospitalizacion_anterior($id_user = 0) {
    $model_medico = new \App\Models\Medico();
    $model_usuario = new User;
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $ruta = Route::getCurrentRoute()->getPath();
    if (count($ruta = explode("/", $ruta)) > 1);
    $ruta = $ruta[0];
    if ($ruta == "listar_emergencia_a_hospitalizar_ajax");
    $ruta = "all_pendientes_hospitalizacion";
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
    $permiso = $menu[0];
    if ($permiso->lectura == "")
      return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
    $id_user = Auth::user()->id;

    $date = Carbon::now();
    $fechaHasta = $date->format('d-m-Y');
    $fechaDesdex = $date->format('d-m-Y');
    $fechaDesdey = strtotime('-1 day', strtotime($fechaDesdex));
    $fechaDesde = date('d-m-Y', $fechaDesdey);

    //====================
    $model_farma = new Farmacia();
    $farmacia = $model_farma->GetFarmaByUser($id_user);

    $dni_beneficiario = '';
    $nombre_beneficiario = '';
    $estado = 0;

    return view('frontend.hospitalizacion.all_pendientes_hospitalizacion', compact('farmacia', 'fechaDesde', 'fechaHasta', 'dni_beneficiario', 'nombre_beneficiario', 'estado'));
  }

  public function all_pendientes_hospitalizacion($id_user = 0)
  {
    if (!Auth::check()):
      return redirect('/auth/login');
    endif;

    $ruta = Route::getCurrentRoute()->getPath();
    if (count($ruta = explode("/", $ruta)) > 1);
      $ruta = $ruta[0];
    if ($ruta == "listar_emergencia_a_hospitalizar_ajax")
      $ruta = "all_pendientes_hospitalizacion";
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
    $permiso = $menu[0];
    if ($permiso->lectura == "")
      return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

    $id_user = Auth::user()->id;
    $id_farmacia = Auth::user()->id_farmacia;
    $id_establecimiento = Auth::user()->id_establecimiento;

    $date = Carbon::now();
    $fechaHasta = $date->format('d-m-Y');
    $fechaDesdex = $date->format('d-m-Y');
    $fechaDesdey = strtotime('-1 day', strtotime($fechaDesdex));
    $fechaDesde = date('d-m-Y', $fechaDesdey);

    //====================
    // $model_farma = new Farmacia();
    // $farmacia = $model_farma->GetFarmaByUser($id_user);

    $dni_beneficiario = '';
    $nombre_beneficiario = '';
    $estado = 0;

    /*==========SECCION DE UPSS HOSPITALIZACION==========*/
    $model_upss = new \App\Models\Upss();
    $upss = $model_upss->ShowUPSS_by_Hospitalizacion();
    /*==========SECCION DE UPSS HOSPITALIZACION==========*/

    return view('frontend.hospitalizacion.all_pendientes_hospitalizacion2',
      compact('fechaDesde', 'fechaHasta', 'dni_beneficiario', 'nombre_beneficiario', 'estado', 'upss', 'id_user', 'id_establecimiento', 'id_farmacia')
    );
  }

public function hospitalizacion_by_enfermeria_ajax(Request $request)
	{

		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;

		$model_hospitalizacion = new Hospitalizacione();

    $p[] = Auth::user()->id_establecimiento;
    $p[] = $request->fecha_ini;
    $p[] = $request->fecha_fin;
    error_log($request->fecha_ini);
    error_log($request->fecha_fin);
    $p[] = $request->dni_beneficiario;
    $p[] = strtoupper($request->nombre_beneficiario);
    $p[] = 1; // PARA EL ESTADO DE LA HOSPITALIZACION (SE MANDA 1 PORQUE 1 SIGNIFICA QUE ESTA EN ADMSION)
    $p[] = intval($request->id_consultorio);
    $p[] = $request->esta_registrado; //0 = sin nota, 1 = con nota, 3-4 = con o sin nota
    $p[] = $request->NumeroPagina;
    $p[] = $request->NumeroRegistros;

		$data = $model_hospitalizacion->listar_hospitalizaciones_admisionadas($p);

		$iTotalDisplayRecords = isset($data['totalrows']) ? $data['totalrows'] : 0;

		$result["PageStart"] = $request->NumeroPagina;
		$result["pageSize"] = $request->NumeroRegistros;
		$result["SearchText"] = "";
		$result["ShowChildren"] = true;
		$result["iTotalRecords"] = $iTotalDisplayRecords;
		$result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
		$result["aaData"] = $data;
		//==================== 

		echo json_encode($result);
	}

  public function listar_emergencia_a_hospitalizar_ajax(Request $request)
  {

    $model_hospitalizacione = new Hospitalizacione();
    $p[] = Auth::user()->id_establecimiento;
    $p[] = $request->fecha_ini;
    $p[] = $request->fecha_fin;
    $p[] = $request->dni_beneficiario;
    $p[] = strtoupper($request->nombre_beneficiario);
    $p[] = $request->estado;
    $p[] = $request->id_servicio_destino;

    $p[] = $request->NumeroPagina;
    $p[] = $request->NumeroRegistros;

    $data = [];
    if ($request->estado == 0) {
      $data = $model_hospitalizacione->listar_emergencia_a_hospitalizar_ajax($p);
    } else if ($request->estado == 1) {
      // if (Auth::user()->id_establecimiento != 75) {
        $data = $model_hospitalizacione->listar_hospitalizaciones_admisionadas($p);
      // } else {
      //   $data = $model_hospitalizacione->listar_hospitalizaciones_admisionadas_geriatrico($p);
      // }
    }else{ /* ANULADOS */
      $data = [];
    }
    $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;
    $result["PageStart"] = $request->NumeroPagina;
    $result["pageSize"] = $request->NumeroRegistros;
    $result["SearchText"] = "";
    $result["ShowChildren"] = true;
    $result["iTotalRecords"] = $iTotalDisplayRecords;
    $result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
    $result["aaData"] = $data;
    echo json_encode($result);
  }

   public function admision_hospitalizacion(Request $request)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

   // if (count($request->all()) > 0) {
      $modelFarmacia = new Farmacia();
      $model_cie = new \App\Models\Diagnostico;
      $model_consultorio = new Consultorio;
      $model_diagnostico = new \App\Models\Diagnostico;
      $model_medico = new \App\Models\Medico();
      $model_receta_vale = new Receta_vale;
      $model_farmacia_servicio = new \App\Models\Farmacia_servicio();
      $model_kardex = new Kardexe();
      $model_usuario = new User;

      $fecha_atencion = $model_kardex->getFechaServidor();
      $hora_ingreso = '';
      //-----------------------------------------------        
      $id_establecimiento=Auth::user()->id_establecimiento;
      $dni_medico=Auth::user()->dni;     
	    $p[] = $id_establecimiento;
	    $p[] = '';
	    $p[] = '';
	    $p[] ='';
	    $p[] = '';
	    $p[] = 0;
	    $p[] = $request->id_emergencia;
	    $p[] = 1;
	    $p[] = 10;
	    $model_hospitalizacione = new Hospitalizacione();
	    $data = $model_hospitalizacione->listar_emergencia_a_hospitalizar_ajax($p);
	    
	    //$result["aaData"] = $data;
   		
   		$dni_beneficiario=  $data[0]->dni_beneficiario;
   		$tipo_doc_ident= $data[0]->tipo_doc_ident;
   		$id_consultorio_destino= $data[0]->id_servicio_destino;
   		$paciente= $data[0]->paterno." ".$data[0]->materno." ".$data[0]->nombre;
   		$tipo_beneficiario= $data[0]->tipo_beneficiario;
   		$id_tipo_beneficiario = $data[0]->id_tipo_beneficiario;
   		$descripcion_triaje =  $data[0]->descripcion_triaje;
   		$id_triaje = $data[0]->id_triaje;

   		$id_medico_egreso= $data[0]->id_medico_egreso;
   		$medico_egreso=$data[0]->pat_medico.' '.$data[0]->mat_medico.' '.$data[0]->nomb_medico;
   		$medico_dni= $data[0]->medico_dni;
   		$especializacion= $data[0]->especializacion;
   		$profesion= $data[0]->profesion;
   		
   		//DIAGNOSTICOS
        $model_emergencia = new \App\Models\Emergencia();
        $id_emergencia = $data[0]->id;
        $prestacion= $model_emergencia->getIdPrestacion($id_emergencia);
    	$id_prestacion =  $prestacion->id;
    	
        $model_prestacion = new \App\Models\Prestaciones;        
        $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion); 
		$id_diagnostico_inicial=0;
		foreach($diagnostico as $row):
			if($data[0]->fecha_firma_egreso!=''){
				if($row->id_clasificacion_diagnosticos==3){
    				$id_diagnostico_inicial = $row->id_diagnostico;
    			} 
    		}
    		else
    		{
    			$id_diagnostico_inicial = $row->id_diagnostico;
    		}
    		
    	endforeach;          
    	
   		//datos adicionales
   		$id_asegurado_titular=0; $apellidos_nombres_titular=""; $id_grado=""; $dni_titular="";
   		$id_asegurado_titular= $data[0]->id_asegurado_titular;
   		$paterno_titular=""; $materno_titular=""; $nombre_titular=""; $sexo_titular=""; $tipo_doc_ident_titular="";
   		/*if($id_asegurado_titular!=null){
			$model_personadatosadicional= new \App\Models\Persona_datos_adicional();
			$personadatosadicional= $model_personadatosadicional->persona_datos_Adicionales($id_asegurado_titular);
			$apellidos_nombres_titular=$personadatosadicional->paterno." ".$personadatosadicional->materno." ".$personadatosadicional->nombre;
			$id_grado = $personadatosadicional->id_grado;
			$dni_titular = $personadatosadicional->nro_doc_ident;

			$paterno_titular=$personadatosadicional->paterno;
			$materno_titular=$personadatosadicional->materno;
			$nombre_titular=$personadatosadicional->nombre;
			$sexo_titular=$personadatosadicional->sexo;
			$tipo_doc_ident_titular=$personadatosadicional->tipo_doc_ident;
		}
		*/
      //if ($tipo_doc_ident== "DNI") {
		$model_establecimiento= new Establecimiento();
   		$nivel = $model_establecimiento->GetById($id_establecimiento)[0]->nivel;   		
   		$farma = $modelFarmacia->GetFarmaByUser($request->id_user);
        $id_farmacia = $farma->id;

		$tipo_receta = "";
		$domicilio = "";
		$telefono_beneficiario = "";
		$correo = "";

		$model_asegurado= new Asegurado();
        $datos_asegurado = $model_asegurado->busca_datos_asegurados($dni_beneficiario, $id_establecimiento);
        $asegurado = $datos_asegurado[0];

        //obtener edad
        $originalDate = $asegurado->fecha_nac;
        $fech_nac = explode("/", $originalDate);
        $nacimiento=""; $asegurado->edadafiliado = 0;
        if($fech_nac!=null){
        $nacimiento = $fech_nac[2] . '-' . $fech_nac[1] . '-' . $fech_nac[0];        
        $dbDate = Carbon::parse($nacimiento);
        $diffYears = Carbon::now()->diffInYears($dbDate);
        $asegurado->edadafiliado = $diffYears;
    	}
        
        $dni = strtoupper($dni_beneficiario);

        $establecimiento = $modelFarmacia->GetIpressByIduser($request->id_user);
        $id_ipress = $establecimiento->id;

        $user = $model_usuario->GetById($request->id_user);       
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $consultorio = $model_consultorio->ShowConsultorioByIdIpressAndIdTipoAtencion($id_establecimiento,3);
        
        $tipo_hospitalizacion = 'HO';
        $medico = $model_medico->ShowallById_Ipress($id_ipress);
        
        $model_beneficiario = new \App\Models\Beneficiario();
        //$beneficiario = $model_beneficiario->buscar_personal_dni($dni, 'DNI');
             
        $model = new \App\Models\Cuarto();
          $pabellon = $model->GetCuartoTodosId(1, $id_ipress); //cuarto
          $consultorios = $model->GetConsultorioId($id_ipress); //cuarto
          if (count($consultorios) > 0)
            $id_consultorio = $consultorios->id_consultorio;

        if ($asegurado!="") {
          $opcReceta = 1;

          $cantidad_hospitalizado = DB::table('hospitalizaciones')
            ->select('hospitalizaciones.*')
            ->where('dni_beneficiario', $dni)
            ->where('id_farmacia', $id_farmacia)
            ->whereNull('fecha_salida')
            ->count();

			$tipo_receta = "HOSPITALIZACION";

		$model_grado=new \App\Models\Tipo_grado();
		$grado=$model_grado->ShowTipoGrado();

			$tarifa="";
			if($id_tipo_beneficiario=="8"){//titulares
				$tarifa="T1";
			}else if($id_tipo_beneficiario>="1" && $id_tipo_beneficiario<="12"){//derecho habientes
				$tarifa="T3";
			}else if($id_tipo_beneficiario=="13"){//civiles (NO TIENES SALUDPOL) 
				$tarifa="T4";
			}
			
			$id_cama="";
			$id_consultorio_hosp="";
			//$model_cama = new \App\Models\Cama;
			//$sala = $model_cama->GetConsultorioByIpressCama($id_ipress, $id_cama);
			$model_consultorio_cama = new \App\Models\Cama;
			$sala = $model_consultorio_cama->GetConsultorioByIpress($id_establecimiento);
			$id_sala_reg = ''; 
			$opcion = 0; 
			//----------------------------------------------------------------------
            if ($cantidad_hospitalizado == 0) {
            	if($id_diagnostico_inicial==0){
            		return redirect('/all_pendientes_hospitalizacion/')->withInput()->withErrors(array('Debe de tener un alta y un diagnostico de egreso..!'));
            	}
            	else
            	{	
            		return view('frontend.hospitalizacion.form_hospitalizacion_registro', compact('establecimiento', 'consultorio', 'diagnostico',  'id_farmacia', 'medico', 'fecha', 'fechaServidor', 'opcReceta', 'id_ipress', 'tipo_receta', 'pabellon', 'nivel', 'id_consultorio', 'tipo_hospitalizacion', 'id_consultorio_destino','dni_medico','paciente','tipo_beneficiario','tarifa', 'apellidos_nombres_titular', 'id_grado','grado','dni_titular','asegurado','id_tipo_beneficiario','paterno_titular','materno_titular','nombre_titular','sexo_titular','tipo_doc_ident_titular','descripcion_triaje','id_emergencia','id_triaje','id_cama','id_consultorio_hosp','medico_egreso','medico_dni','especializacion','profesion','id_medico_egreso','id_diagnostico_inicial','fecha_atencion','hora_ingreso','sala','id_sala_reg','opcion'));	
            	}
              	
            } else {
              return redirect('/all_pendientes_hospitalizacion/')->withInput()->withErrors(array('Ya se encuentra hospitalizado..!'));
            } 

        } else {
          return redirect('/all_pendientes_hospitalizacion/')->withInput()->withErrors(array('No se encuentra paciente ..!'));
        }
  }

  public function obtener_gestaciones_by_dni_paciente(Request $request)
	{
		$model_prestacion = new Prestaciones;
		$data = $model_prestacion -> getLastGestacionByDniPaciente($request -> dni);
		echo json_encode($data);
	}

  public function nueva_admision_hospitalizacion(Request $request){
	if (!Auth::check()) :
return redirect('/auth/login');
endif;

// if (count($request->all()) > 0) {
$modelFarmacia = new Farmacia();
$model_cie = new \App\Models\Diagnostico;
$model_consultorio = new Consultorio;
$model_diagnostico = new \App\Models\Diagnostico;
$model_medico = new \App\Models\Medico();
$model_receta_vale = new Receta_vale;
$model_farmacia_servicio = new \App\Models\Farmacia_servicio();
$model_kardex = new Kardexe();
$model_usuario = new User;

$fechaServidor = $model_kardex->getFechaServidor();
//-----------------------------------------------        
$id_establecimiento=Auth::user()->id_establecimiento;
$dni_medico=Auth::user()->dni; 	   
	 
	 $dni_beneficiario=  $request->dni_beneficiario;
	 
	 $id_consultorio_destino= "";    		
	 $descripcion_triaje =  "";
	 $id_medico_egreso= "";
	 $medico_egreso= "";   
	 $id_emergencia="";

  $id_user=Auth::user()->id;
  $model_establecimiento= new Establecimiento();
	 $nivel = $model_establecimiento->GetById($id_establecimiento)[0]->nivel;   		
	 $farma = $modelFarmacia->GetFarmaByUser($id_user);
  $id_farmacia = $farma->id;

  $tipo_receta = "";
  $domicilio = "";
  $telefono_beneficiario = "";
  $correo = "";		

  $establecimiento = $modelFarmacia->GetIpressByIduser($id_user);
  $id_ipress = $establecimiento->id;

  $user = $model_usuario->GetById($id_user);       
  $date = Carbon::now();
  $fecha = $date->format('d-m-Y');
  $consultorio = $model_consultorio->ShowConsultorios($id_farmacia);

   

  $tipo_hospitalizacion = 'HO';
  $medico = $model_medico->ShowallById_Ipress($id_ipress);

  $model_beneficiario = new \App\Models\Beneficiario();
  //$beneficiario = $model_beneficiario->buscar_personal_dni($dni, 'DNI');
 

  $model = new \App\Models\Cuarto();
	$pabellon = $model->GetCuartoTodosId(1, $id_ipress); //cuarto
	$consultorios = $model->GetConsultorioId($id_ipress); //cuarto
	if (count($consultorios) > 0)
	  $id_consultorio = $consultorios->id_consultorio; 
	  $tipo_receta = "HOSPITALIZACION";

	  $model_grado=new \App\Models\Tipo_grado();
	  $grado=$model_grado->ShowTipoGrado();

	  $tarifa="";	 
	  $id_tipo_beneficiario="";
	  $opcReceta = 1;
	  $tipo_doc_ident_titular = 1;

	  $model_tipo_documento_identidad=new \App\Models\Tipo_documento_identidad(); 
	  $tipo_documento_identidad=$model_tipo_documento_identidad->ShowTipoDocumentoIdentidad();

	  $model_beneficiario = new \App\Models\Tipo_beneficiario;
	  $tipo_beneficiario = $model_beneficiario->ShowTipo_beneficiario();  
	  //----------------------------------------------------------------------
	  return view('frontend.hospitalizacion.form_hospitalizacion_nuevo', compact('establecimiento', 'consultorio', 'diagnostico',  'id_farmacia', 'medico', 'fecha', 'fechaServidor', 'opcReceta', 'id_ipress', 'tipo_receta', 'pabellon', 'nivel', 'id_consultorio', 'tipo_hospitalizacion', 'id_consultorio_destino','dni_medico','paciente','tipo_beneficiario','tarifa', 'apellidos_nombres_titular', 'id_grado','grado','dni_titular','asegurado','id_tipo_beneficiario','paterno_titular','materno_titular','nombre_titular','sexo_titular','tipo_doc_ident_titular','descripcion_triaje','tipo_documento_identidad','tipo_beneficiario'));        
}

 /////////

public function registrar_hospitalizaciones_anterior(Request $request)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    if(isset($request->id_hospitalizacion)){ //actualizar
		
		$model_cuarto = new \App\Models\Cuarto;
        $model_consultorio = new \App\Models\Consultorio;
        $model_asignacion = new \App\Models\Asignacion_servicio;
        $model_cama = new \App\Models\Cama;
        $hospitalizacion = new \App\Models\Hospitalizacione;
        $model_usuario = new User;

        $id_establecimiento = Auth::user()->id_establecimiento;

        $modelmedico = new Paciente();
	    $dni = $request->dni_beneficiario;
		$id_tipo_doc_ident = $request->id_tipo_doc_ident;
		$id_user= $request->id_user;

		$asegurado = $modelmedico->GetAseguradoByDNI($dni, $id_tipo_doc_ident);	 
		//datos del titular
		if($asegurado!=null){
			  $id_asegurado = $asegurado->id;
		}

        $hospitalizacion = Hospitalizacione::where('id', '=', $request->id_hospitalizacion)->first(); 
        $hospitalizacion->fecha_ingreso = $request->fecha_atencion;
        $hospitalizacion->hora_ingreso = $request->hora;
        
        $hospitalizacion->id_user = $request->id_user;
        $user = $model_usuario->GetById($request->id_user);
        $hospitalizacion->nombre_registrador = $user->name . ' ' . $user->paterno . ' ' . $user->materno;
        	if ($request->nivel == 3) {
			  	if ($user->id_consultorio == 17) {
					$hospitalizacion->id_cama = 0;
					$hospitalizacion->id_cuarto = 0;
					$hospitalizacion->id_piso = 0;
					$hospitalizacion->id_pabellon = 0;
					$hospitalizacion->turno = '';
					$hospitalizacion->id_diagnostico_inicial = 67;
					$hospitalizacion->origen = '';
					$hospitalizacion->id_consultorio_hosp = 0;
					$hospitalizacion->id_consultorio = 17;
					$hospitalizacion->nombre_consultorio = 'NEFROLOGIA';
					$hospitalizacion->tipo_hospitalizacion = 'HE';
			  	} else {
					$hospitalizacion->id_cama = $request->id_cama;
					$completa_datos = $model_cuarto->GetCuartoCamaId($request->id_cama);
					$hospitalizacion->id_cuarto = $completa_datos[0]->id_cuarto;
					$id_cuarto = $completa_datos[0]->id_cuarto;
					$hospitalizacion->id_piso = $completa_datos[0]->id_piso;
					$hospitalizacion->id_pabellon = $completa_datos[0]->id_pabellon;
					$hospitalizacion->turno = $request->turno;
					$hospitalizacion->id_diagnostico_inicial = $request->id_diagnostico;
					$hospitalizacion->origen = $request->origen;
					$hospitalizacion->tarifa = $request->tarifa;
					$hospitalizacion->id_consultorio_hosp = $request->id_sala;
					$hospitalizacion->id_consultorio = $request->id_consultorio;
					$nombre_consultorio = $model_consultorio->GetNombreById($request->id_consultorio);
					$hospitalizacion->nombre_consultorio = $nombre_consultorio->nombre;
			  	}
			} else {
			  	$hospitalizacion->id_cama = $request->id_cama;
			  	$completa_datos = $model_cuarto->GetCuartoCamaId($request->id_cama);
			  	$hospitalizacion->id_cuarto = $completa_datos[0]->id_cuarto;
			  	$id_cuarto = $completa_datos[0]->id_cuarto;
			  	$hospitalizacion->id_piso = $completa_datos[0]->id_piso;
			  	$hospitalizacion->id_pabellon = $completa_datos[0]->id_pabellon;
			  	$hospitalizacion->id_diagnostico_inicial = $request->id_diagnostico;
			  	$hospitalizacion->id_consultorio_hosp = $completa_datos[0]->id_asignacion_servicio;
			  	$hospitalizacion->id_consultorio = $completa_datos[0]->id_consultorio;
			  	$hospitalizacion->nombre_consultorio = $completa_datos[0]->nombre_consultorio;
			}
            
            $hospitalizacion->save();

	        if ($user->id_consultorio != 17) {
	            //actualiza cama
	            $model_cama->actualizaEstado($request->id_cama, 1);

	            //contar cama
	            $nro_cama = $model_cuarto->consultaCama($id_cuarto);
	            $nro_cama_ocupada = $model_cuarto->consultaCamaOcupada($id_cuarto);

	            if ($nro_cama == $nro_cama_ocupada)
	              $model_cuarto->actualizaEstado($id_cuarto, 1);
	        }

           return redirect('/ver_hospitalizacion/' . $request->id_user . '/' . $request->id_hospitalizacion);
    
    }else{
    	
		    //IF NO EXISTE PACIENTE CREAR PACIENTE Y HISTORIA CLINICA
		    $modelmedico = new Paciente();
		    $dni = $request->dni_beneficiario;
			$id_tipo_doc_ident = $request->id_tipo_doc_ident;
			$id_user= $request->id_user;
			$asegurado = $modelmedico->GetAseguradoByDNI($dni, $id_tipo_doc_ident);
			
			//datos del titular
			if($asegurado!=null){
				  $id_asegurado = $asegurado->id;
				  //asegurados
				  $update = Paciente::where('id', '=', $id_asegurado)->first(); 
				  $update->tipo_doc_ident= $request->tipdoc_beneficiario;
//				  $update->cip= $request->cip;
				  $update->id_tipo_doc_ident= $request->id_tipo_doc_ident;
//				  $update->id_tipo_beneficiario= $request->tipo_beneficiario;
				  $update->fecha_nac= $request->fecha_nacimiento_beneficiario;
				  $update->id_estado_reg= "1";
				  $update->observacion= "actualizado en hospitalizacion";
				  $update->updated_us= $id_user;
				  $update->save();
			}else{ //NUEVO PACIENTE
				$model_paciente = new Paciente();

				$model_paciente->tipo_doc_ident= $request->tipdoc_beneficiario;
				$model_paciente->nro_doc_ident= $request->dni_beneficiario;
				$model_paciente->cip= $request->cip_beneficiario;

				$model_paciente->nombre= $request->nombre_beneficiario;
				$model_paciente->paterno= $request->paterno_beneficiario;
				$model_paciente->materno= $request->materno_beneficiario;
				$model_paciente->sexo= $request->nomsexo;
				$model_paciente->fecha_nac= $request->fecha_nacimiento_beneficiario;
				$model_paciente->id_estado_reg= "1";
				$model_paciente->observacion= "registrado en hospitalizacion";
				//$model_paciente->model_paciented_at= $request->model_paciented_at;
				$model_paciente->created_us= $id_user;		
				$model_paciente->id_tipo_doc_ident= $request->id_tipo_doc_ident;
				//$model_paciente->id_tipo_beneficiario= $request->tipo_beneficiario;		
			    $model_paciente->save();
			    $id_asegurado = $model_paciente->id;
			}

		    $model_hospitalizacion = new \App\Models\Hospitalizacione;
		    $model_farma = new Farmacia;

		    $farma = $model_farma->GetFarmaByIdfarma($request->id_farmacia);
		    $id_establecimiento = $farma->id_establecimiento;


		    $nro_historia = $request->nro_historia;
			//asegurado_historias
			if($nro_historia>0){		
				$update_historia= Asegurado_historia::where('id_asegurado', '=', $id_asegurado)->where('id_establecimiento', '=', $id_establecimiento)->first();
				$update_historia->nro_historia= $request->nro_historia;	
				$update_historia->id_estado_reg= "1";
				$update_historia->updated_us= $id_user;
				$update_historia->save();
				$objpaciente= new Paciente();
				$objpaciente->UpdateRecetaVale($dni, $id_establecimiento, $request->nro_historia);
				//actualizar en receta vale
			}else{
				$objpaciente= new Asegurado_historia();	

				$objpaciente->id_asegurado= $id_asegurado;
				$objpaciente->id_establecimiento= $id_establecimiento;	
				$objpaciente->nro_historia= $dni;	
				$objpaciente->id_estado_reg= "1";
				$objpaciente->created_us= $id_user;
				$objpaciente->save();		
			}

			//HOSPITALIZACIONES
		    if (count($request) > 0) {
		      
		      $nro_hospitalizacion = $model_hospitalizacion->getNroHospitalizacion($id_establecimiento);
		      
		      $obtenerId = DB::Transaction(function () use ($request, $nro_hospitalizacion, $id_establecimiento, $id_asegurado) {
		        $model_cuarto = new \App\Models\Cuarto;
		        $model_consultorio = new \App\Models\Consultorio;
		        $model_asignacion = new \App\Models\Asignacion_servicio;
		        $model_cama = new \App\Models\Cama;
		        $hospitalizacion = new \App\Models\Hospitalizacione;
		        $model_usuario = new User;

		        $hospitalizacion->nro_hospitalizacion = $nro_hospitalizacion;
		        $hospitalizacion->id_farmacia = $request->id_farmacia;
		        $hospitalizacion->id_user = $request->id_user;
		        $hospitalizacion->tipo_beneficiario = $request->tipo_beneficiario;
		        $hospitalizacion->dni_beneficiario = $request->dni_beneficiario;
		        $hospitalizacion->nombre_beneficiario = $request->nombre_beneficiario;
		        $hospitalizacion->paterno_beneficiario = $request->paterno_beneficiario;
		        $hospitalizacion->materno_beneficiario = $request->materno_beneficiario;
		        $hospitalizacion->dni_titular = $request->nrodoctitular;
		        $hospitalizacion->nombre_titular = $request->nomtitular;
		        $hospitalizacion->paterno_titular = $request->apepattitular;
		        $hospitalizacion->materno_titular = $request->apemattitular;
		        
		        $hospitalizacion->grado = $request->grado;
		        $hospitalizacion->fecha_nacimiento_beneficiario = $request->fecha_nacimiento_beneficiario;
		        $date = Carbon::now();
		        $fecha_actual = $date->format('d-m-Y');
		        if ($request->fecha_atencion == $fecha_actual) {
		          $fecha_actual = $date->format('d-m-Y H:i:s');
		          $hospitalizacion->fecha_ingreso = $fecha_actual;
		        } else {
		          $hospitalizacion->fecha_ingreso = $request->fecha_atencion;
		        }
		        //$hospitalizacion->hora_ingreso = $request->hora;
		        $hospitalizacion->edad = $request->edadafiliado;
		        $hospitalizacion->sexo = $request->nomsexo;
		        $hospitalizacion->nro_historia = $request->nro_historia;
		        $hospitalizacion->id_medico = $request->id_medico;

		        $user = $model_usuario->GetById($request->id_user);

		        $hospitalizacion->nombre_registrador = $user->name . ' ' . $user->paterno . ' ' . $user->materno;
		        $hospitalizacion->estado = 1;
		        $hospitalizacion->id_establecimiento=$id_establecimiento;

		        $hospitalizacion->id_asegurado = $id_asegurado;
		        $hospitalizacion->id_servicio_origen = $request->id_servicio;

		        if ($request->nivel == 3) {
		          if ($user->id_consultorio == 17) {
		            $hospitalizacion->id_cama = 0;
		            $hospitalizacion->id_cuarto = 0;
		            $hospitalizacion->id_piso = 0;
		            $hospitalizacion->id_pabellon = 0;
		            $hospitalizacion->turno = '';
		            $hospitalizacion->id_diagnostico_inicial = 67;
		            $hospitalizacion->origen = '';
		            $hospitalizacion->id_consultorio_hosp = 0;
		            $hospitalizacion->id_consultorio = 17;
		            $hospitalizacion->nombre_consultorio = 'NEFROLOGIA';
		            $hospitalizacion->tipo_hospitalizacion = 'HE';
		          } else {
		            $hospitalizacion->id_cama = $request->id_cama;
		            $completa_datos = $model_cuarto->GetCuartoCamaId($request->id_cama);
		            $hospitalizacion->id_cuarto = $completa_datos[0]->id_cuarto;
		            $id_cuarto = $completa_datos[0]->id_cuarto;
		            $hospitalizacion->id_piso = $completa_datos[0]->id_piso;
		            $hospitalizacion->id_pabellon = $completa_datos[0]->id_pabellon;
		            $hospitalizacion->turno = $request->turno;
		            $hospitalizacion->id_diagnostico_inicial = $request->id_diagnostico;
		            $hospitalizacion->origen = $request->origen;

		            $hospitalizacion->tarifa = $request->tarifa;
		            $hospitalizacion->id_consultorio_hosp = $request->id_sala;
		            $hospitalizacion->id_consultorio = $request->id_consultorio;
		            $nombre_consultorio = $model_consultorio->GetNombreById($request->id_consultorio);
		            $hospitalizacion->nombre_consultorio = $nombre_consultorio->nombre;
		          }
		        } else {
		          $hospitalizacion->id_cama = $request->id_cama;
		          $completa_datos = $model_cuarto->GetCuartoCamaId($request->id_cama);
		          $hospitalizacion->id_cuarto = $completa_datos[0]->id_cuarto;
		          $id_cuarto = $completa_datos[0]->id_cuarto;
		          $hospitalizacion->id_piso = $completa_datos[0]->id_piso;
		          $hospitalizacion->id_pabellon = $completa_datos[0]->id_pabellon;
		          $hospitalizacion->turno = $request->turno;
		          $hospitalizacion->id_diagnostico_inicial = $request->id_diagnostico;
		          $hospitalizacion->origen = $request->origen;

		          $hospitalizacion->id_consultorio_hosp = $completa_datos[0]->id_asignacion_servicio;
		          $hospitalizacion->id_consultorio = $completa_datos[0]->id_consultorio;
		          $hospitalizacion->nombre_consultorio = $completa_datos[0]->nombre_consultorio;
		        }



		        $existe_hospitalizacion = DB::table('hospitalizaciones')
		          ->where('id_farmacia', $request->id_farmacia)
		          ->where('dni_beneficiario', $request->dni_beneficiario)
		          ->where('id_cuarto', $hospitalizacion->id_cuarto)
		          ->where('id_cama', $hospitalizacion->id_cama)
		          ->where('id_consultorio', $hospitalizacion->id_consultorio)->first();

		        if ($existe_hospitalizacion == false) {

		          $hospitalizacion->save();

		          if ($user->id_consultorio != 17) {
		            //actualiza cama
		            $model_cama->actualizaEstado($request->id_cama, 1);

		            //contar cama
		            $nro_cama = $model_cuarto->consultaCama($id_cuarto);
		            $nro_cama_ocupada = $model_cuarto->consultaCamaOcupada($id_cuarto);

		            if ($nro_cama == $nro_cama_ocupada)
		              $model_cuarto->actualizaEstado($id_cuarto, 1);
		            //actualiza piso

		          }
		          $id_hospitalizacion = $hospitalizacion->id;

		          return $id_hospitalizacion;
		        } else {

		          return $existe_hospitalizacion->id;
		        }
		      });

		      if ($obtenerId == 0) {
		        Session::flash('flash_message', 'No se pudo registrar la Hospitalización, hubo un problema con la conexión de internet de su establecimiento PNP, vuelva a intentarlo nuevamente');
		        return redirect('/all_pendientes_hospitalizacion/');
		      } else {
		      	//cambiar estado emergencia
		      	if($request->id_emergencia>0){
			      	$model_emergencia = \App\Models\Emergencia::find($request->id_emergencia);
			      	$model_emergencia->flag_hospitalizado='1';
			      	$model_emergencia->save();
		      	}
		        Session::flash('flash_message', 'Hospitalización exitosa, El numero de Hospitalizacion es ' . $nro_hospitalizacion);
		        //return redirect('/nueva_admision/' . $request->id_user . '/' . $request->id_farmacia);
		        return redirect('/ver_hospitalizacion/' . $request->id_user . '/' . $obtenerId);
		      }
		    } else {
		      return redirect('/all_pendientes_hospitalizacion/');
		    }
	}

}

	 public function all_hospitalizado_by_establecimiento_anterior($id_user = 0) {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $ruta = Route::getCurrentRoute()->getPath();
    if (count($ruta = explode("/", $ruta)) > 1);
    $ruta = $ruta[0];
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
    $permiso = $menu[0];
    if ($permiso->lectura == "")
      return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

    $id_user = Auth::user()->id;
    $id_user_consultorio = Auth::user()->id_consultorio;
    $model_farma = new Farmacia();
    $farmacia = $model_farma->GetFarmaByUser($id_user);
    $id_farmacia = $farmacia->id;
    $id_establecimiento = $farmacia->id_establecimiento;

   $model_establecimiento = new Establecimiento();
   $ipress= $model_establecimiento->GetById($id_establecimiento);
   $nombre_ipress= $ipress[0]->nombre;

    if ($id_user_consultorio != 17) {
	    	$model_hospitalizacione = new Hospitalizacione();
	    	$hospitalizado = $model_hospitalizacione->GetListaTodosHospitalizado($id_establecimiento);
    } else {
        $model_hospitalizacione = new Hospitalizacione();
    	$hospitalizado = $model_hospitalizacione->GetListaTodosHospitalizadoDial($id_establecimiento);
    }
    return view('frontend.receta_vales.all_admision_by_hospitalizado', compact('hospitalizado', 'farmacia', 'id_farmacia', 'id_user_consultorio', 'id_user','nombre_ipress'));
    
  }

	public function hospitalizado_by_atender($id_user = 0) {

	    if (!Auth::check()) :
	      return redirect('/auth/login');
	    endif;

	    $ruta = Route::getCurrentRoute()->getPath();
	    if (count($ruta = explode("/", $ruta)) > 1);
	    $ruta = $ruta[0];
	    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
	    $permiso = $menu[0];
	    if ($permiso->lectura == "")
	      return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

	    $id_user = Auth::user()->id;
	    $id_user_consultorio = Auth::user()->id_consultorio;
	    $model_farma = new Farmacia();
	    $farmacia = $model_farma->GetFarmaByUser($id_user);
	    $id_farmacia = $farmacia->id;
	    $id_establecimiento = $farmacia->id_establecimiento;
	    $establecimiento = $model_farma->GetIpressByIduser($id_user);
	
	    
	    //==================== 
	    if ($id_user_consultorio != 17) {
	    	$model_hospitalizacione = new Hospitalizacione();
	    	$hospitalizado = $model_hospitalizacione->GetListaHospitalizadoSecuencia($id_establecimiento,2);
	    } else {
	        $model_hospitalizacione = new Hospitalizacione();
	    	$hospitalizado = $model_hospitalizacione->GetListaHospitalizadoDialSecuencia($id_establecimiento,2);
	      }
	      //====================
	    return view('frontend.hospitalizacion.all_hospitalizados', compact('hospitalizado', 'farmacia', 'id_farmacia', 'id_user_consultorio', 'id_user','establecimiento'));
	    
	}

	public function form_atenciones_hospitalizacion_anterior($id_hospitalizacion)
    {

        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $verificar = Hospitalizacione::where('id', $id_hospitalizacion)->first();
        $estado=$verificar->estado;
        
        $model_medicos = new Medico;
        $medico = $model_medicos->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni,Auth::user()->id_establecimiento );
        
        $id_user = Auth::user()->id;
        $id_farmacia = Auth::user()->id_farmacia;

        $model_consultorio = new Consultorio;
    
        if(is_object($verificar)):
            if(is_object($medico)):
	             
                $model_hospitalizacion = new Hospitalizacione;
                $cita = $model_hospitalizacion->GetBuscaHospitalizado($id_hospitalizacion, $medico->id, $estado);

                $model_kardex = new Kardexe();
                $fechaServidor = $model_kardex->getFechaServidor();

                $model_farma = new Farmacia();
                $farmacia = $model_farma->GetFarmaByIdfarma($id_farmacia);
                $id_ipress = $farmacia->id_establecimiento;
            
                $model_producto = new Producto;
                $model_establecimiento = new \App\Models\Establecimiento;
                $establecimiento = $model_establecimiento->GetById($id_ipress);
                $establecimientos = $model_establecimiento->ShowAllEstablecimientos();

                $tipo_atencion = $model_farma->GetFarmaciaTipoAtencionByIdFarmacia($id_farmacia);
            
                if ($id_ipress == 1) {
                    $nivel = 2;
                } else {
                    $nivel = $establecimiento[0]->nivel;
                }

                $producto = $model_producto->getProductosByNivelAndIdServicio($nivel, $id_ipress, $id_farmacia);
                $consultorio = $model_consultorio->ShowConsultorios($id_farmacia);
                $farmacias = $model_farma->GetFarmaciaByIdEstablecimiento($farmacia->id_establecimiento);
      

		        $obtenerdatoh = $model_hospitalizacion->GetObtenerDatos($id_hospitalizacion);
		        $cita->pabellon= $obtenerdatoh->pabellon;
		        $cita->piso= $obtenerdatoh->piso;
		        $cita->cuarto= $obtenerdatoh->cuarto;
		        $cita->cama= $obtenerdatoh->cama;
		      
      
                $id_cita=$id_hospitalizacion;
                $id_tipo_atencione=3;

                $codigo_upss = $model_consultorio->ShowCodigoUpssIdTipoAtencion($cita->id_servicio,$id_tipo_atencione);
                
                if(count($codigo_upss)==1){
                  $busca_codigo_upss=$codigo_upss[0]->codigo;
                }
                else{
                  return redirect('/hospitalizado_by_atender/')->withInput()->withErrors(array('Falta habilitar el codigo upss ..!'));
                }
				
				$model_prestacion = new Prestaciones;
                $count_establecimiento = $model_prestacion->GetCountAtenciones($cita->nro_doc_ident, $farmacia->id_establecimiento, $id_tipo_atencione);

                if($count_establecimiento->atencion > 0):
                  $ipress_bene = 'CONTINUADOR';
                  $count_servicio = $model_prestacion->GetCountAtenciones($cita->nro_doc_ident, $farmacia->id_establecimiento, $id_tipo_atencione, $cita->id_sub_consultorio);
                  if($count_servicio->atencion>0):
                    $upss_bene = 'CONTINUADOR';
                  else:
                    $upss_bene = 'NUEVO';
                  endif;
                else:
                  $ipress_bene = 'NUEVO';
                  $upss_bene = 'NUEVO';
                endif;
                
                $aEscala = $this->formulario_atencion_escalas();
                $client = new SoapClient(env('WI_SALUDPOL_URL') . "/ws/WS_AfiliadoSP_Serv.php?wsdl", array('login' => env('WI_SALUDPOL_USERNAME'), 'password' => env('WI_SALUDPOL_PASSWORD')));
			    $beneficiario = $client->getAseguradoValidate($cita->tipo_doc_ident, $cita->nro_doc_ident);

			    if (is_object($beneficiario)) :
			      $flag = 1 ;
			    else:
			      $flag = 0 ;
			    endif;

			    $modalidad = 1; 
                return view('frontend.hospitalizacion.form_atencion_hospitalizacion', compact('aEscala','fechaServidor', 'producto', 'cita', 'consultorio', 'tipo_atencion', 'farmacias', 'nivel','medico','id_servicio','id_cita','id_tipo_atencione','establecimientos','codigo_upss','upss_bene','ipress_bene','flag','modalidad'));
	            
            else:
                return redirect('/hospitalizado_by_atender/')->withInput()->withErrors(array('Medico no encontrado ..!'));
            endif;
        else:
            return redirect('/hospitalizado_by_atender/')->withInput()->withErrors(array('Elemento no encontrado ..!'));
        endif;
    }

    public function hospitalizado_by_evolucion() {

	    if (!Auth::check()) :
	      return redirect('/auth/login');
	    endif;

	    $ruta = Route::getCurrentRoute()->getPath();
	    if (count($ruta = explode("/", $ruta)) > 1);
	    $ruta = $ruta[0];
	    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
	    $permiso = $menu[0];
	    if ($permiso->lectura == "")
	      return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

	    $id_user = Auth::user()->id;
	    $id_user_consultorio = Auth::user()->id_consultorio;
	    $model_farma = new Farmacia();
	    $farmacia = $model_farma->GetFarmaByUser($id_user);
	    $id_farmacia = $farmacia->id;
	    $id_establecimiento = $farmacia->id_establecimiento;
	    $establecimiento = $model_farma->GetIpressByIduser($id_user);
	
	    
	    //==================== 
	    if ($id_user_consultorio != 17) {
	    	$model_hospitalizacione = new Hospitalizacione();
	    	$hospitalizado = $model_hospitalizacione->GetListaHospitalizado($id_establecimiento,3);
	    } else {
	        $model_hospitalizacione = new Hospitalizacione();
	    	$hospitalizado = $model_hospitalizacione->GetListaHospitalizadoDial($id_establecimiento,3);
	      }
	      //====================
	    return view('frontend.hospitalizacion.all_evolucion_hospitalizados', compact('hospitalizado', 'farmacia', 'id_farmacia', 'id_user_consultorio', 'id_user','establecimiento'));
	    
	}

	public function modal_historial_atenciones_hospitalizacion($id_hospitalizacion, $estado)
    {
      $model_hospitalizacion = new Hospitalizacione;
      $paciente = $model_hospitalizacion->GetBuscaPacienteHospitalizacion( $id_hospitalizacion, $estado);
      $prestaciones = new \App\Models\Prestaciones;
      $atenciones = $prestaciones->GetAtencionesByDniHospitalizacion($paciente->nro_doc_ident, $id_hospitalizacion);
      return view('frontend.atenciones.modal_historial_atenciones', compact('atenciones'));
    }

public function hospitalizado_by_enfermeria($id_user = 0) {

	    if (!Auth::check()) :
	      return redirect('/auth/login');
	    endif;

	    $ruta = Route::getCurrentRoute()->getPath();
	    if (count($ruta = explode("/", $ruta)) > 1);
	    $ruta = $ruta[0];
	    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
	    $permiso = $menu[0];
	    if ($permiso->lectura == "")
	      return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

	    $id_user = Auth::user()->id;
	    $id_user_consultorio = Auth::user()->id_consultorio;
	    $model_farma = new Farmacia();
	    $farmacia = $model_farma->GetFarmaByUser($id_user);
	    $id_farmacia = $farmacia->id;
	    $id_establecimiento = $farmacia->id_establecimiento;
	    $establecimiento = $model_farma->GetIpressByIduser($id_user);
	
	    
	    //==================== 
	    if ($id_user_consultorio != 17) {
	    	$model_hospitalizacione = new Hospitalizacione();
	    	$hospitalizado = $model_hospitalizacione->GetListaTodosHospitalizado($id_establecimiento);
	    } else {
	        $model_hospitalizacione = new Hospitalizacione();
	    	$hospitalizado = $model_hospitalizacione->GetListaTodosHospitalizadoDial($id_establecimiento);
	      }
	      //====================
	    return view('frontend.hospitalizacion.all_nota_ingresos', compact('hospitalizado', 'farmacia', 'id_farmacia', 'id_user_consultorio', 'id_user','establecimiento'));
	}

	public function formulario_nota_ingreso($id_hospitalizacion)
  	{

	    if (!Auth::check()) :
	      return redirect('/auth/login');
	    endif;

	    $modelfarma = new \App\Models\Farmacia;
	    $model_receta = new \App\Models\Receta_vale;
	    $model_establecimiento = new \App\Models\Establecimiento;
	    $model_hospitalizacion = new  \App\Models\Hospitalizacione;
	    $model_kardex = new Kardexe();
	    $model_medicos = new Medico;
	    $medico = $model_medicos->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni,Auth::user()->id_establecimiento );
	    
	    //verifico si existe el id en hospitalizacion
	    $verificar_existe = Hospitalizacione::where('id', $id_hospitalizacion)->where('estado', 1)->first();
	    
	    if(is_object($verificar_existe)):
	        
	        $farmacia = $modelfarma->GetFarmaByUser(Auth::user()->id);
	        $fechaServidor = $model_kardex->getFechaServidor();
	        $date = Carbon::now();
	        $fecha_actual = $date->format('d-m-Y');
	        
	        
			$id_ipress = $farmacia->id_establecimiento;
			$establecimiento = $model_establecimiento->GetById($id_ipress);
	        if ($id_ipress == 1) {
	          $nivel = 2;
	        } else {
	          $nivel = $establecimiento[0]->nivel;
	        }
	        
	        $estado=1;
	        $id_tipo_atencion=3;
	        $datos_hospitalizacion = $model_hospitalizacion->GetBuscaHospitalizado($id_hospitalizacion, $medico->id, 1);
	        $id_principal = $id_hospitalizacion;

	        return view('frontend.hospitalizacion.formulario_nota_ingresos', compact( 'fechaServidor', 'nivel','establecimiento','id_principal','estado','datos_hospitalizacion','id_tipo_atencion'));
	    else:
	      return redirect('/dashboard/')->withInput()->withErrors(array('Elemento no encontrado ..!'));
	    endif; 
	 }

	public function registrar_nota_ingreso_hospitalizado(Request $request) {
    
	    if (!Auth::check()):
	        return redirect('/auth/login');
	    endif;

	    $objNota = new Admision_enfermeria();
	    $objHospi = new Hospitalizacione();

	    $temperatura                = $request->temperatura != '' ? $request->temperatura : 0;
	    $sa02_porcentaje            = $request->saturacion != '' ? $request->saturacion : 0;
	    $presion_arterial_x         = $request->presion_arterial_x != '' ? $request->presion_arterial_x : 0;
	    $presion_arterial_y        = $request->presion_arterial_y != '' ? $request->presion_arterial_y : 0;
	    $talla_cm                   = $request->talla != '' ? $request->talla : 0;
	    $tamano_cintura_cm          = $request->tamanio_cintura != '' ? $request->tamanio_cintura : 0;
	    $frecuencia_respiratoria    = $request->frecuencia_respiratoria != '' ? $request->frecuencia_respiratoria: 0;
	    $peso_kg                    = $request->peso != '' ? $request->peso: 0;
	    $frecuencia_cardiaca_fetal  = $request->frec_cardiaca_fetal != '' ? $request->frec_cardiaca_fetal : 0;
	    $frecuencia_cardiaca        = $request->frec_cardiaca != '' ? $request->frec_cardiaca : 0;
	    $fi02                       = $request->oxigenacion != '' ? $request->oxigenacion : 0;
	    $id_devolucion              = 0;
	    
	    
	    $id_nota_ingreso = $objNota->RegistrarNotaIngresoHospitalizados($request->id_principal , $request->id_asegurado , $request->id_tipo_atencion , $request->fecha_atencion, $request->id_medico , $request->id_establecimiento , $temperatura , $sa02_porcentaje , $presion_arterial_x ,$presion_arterial_y , $talla_cm , $tamano_cintura_cm , $frecuencia_respiratoria , $peso_kg, $frecuencia_cardiaca_fetal , $frecuencia_cardiaca , $fi02 , $request->hora , $request->id_servicio , $request->condicion , $request->estado_general);

	    
        $update_estado_hospi=$objHospi->ActualizaSecuenciaHospitalizacion($request->id_principal,2);

	    if($id_nota_ingreso==0){
	      Session::flash('flash_message', 'No se pudo registrar la Evolucion, hubo un problema con la conexión de internet de su establecimiento PNP, vuelva a intentarlo nuevamente');
	      return redirect('/all_pacientes_evoluciones_by_emergencia');
	    }
	    else
	    {
	      Session::flash('flash_message', 'Nota de Ingreso exitosa...');
	      return redirect('/ver_nota_ingreso/'.$request->id_user.'/'.$id_nota_ingreso);
	    }
  	}

  	public function ver_nota_ingreso($id_user,$id_nota_ingreso)
  	{

	    if (!Auth::check()) :
	      return redirect('/auth/login');
	    endif;

	    $modelfarma = new Farmacia;
	    $model_establecimiento = new \App\Models\Establecimiento;
	    $model_hospitalizacion = new \App\Models\Hospitalizacione;
	    $model_admision_enfermeria = new \App\Models\Admision_enfermeria;
	    $model_kardex = new Kardexe();

	    $farmacia = $modelfarma->GetFarmaByUser($id_user);
	    $id_farmacia = $farmacia->id;
	    $flag_receta_dias = $farmacia->flag_receta_dias;

	    $fechaServidor = $model_kardex->getFechaServidor();
	    $date = Carbon::now();
	    $fecha_actual = $date->format('d-m-Y');

	    $datos_ingreso = $model_admision_enfermeria->GetNotaIngreso($id_nota_ingreso);

	    if($datos_ingreso->id_tipo_atencion==2):
	        $model_emergencias = new Emergencia;
	      $paciente = $model_emergencias->GetBuscaPacienteEmergencia( $datos_ingreso->id_paciente, $estado);
	    else:
	      $model_emergencias = new Hospitalizacione;
	      $paciente = $model_emergencias->GetBuscaPacienteHospitalizacion( $datos_ingreso->id_paciente, 1);
	    endif;
	    
      	//verifico si existe idemergencia
      	if(count($datos_ingreso)>0):

          	//$prestacion = $model_prestacion->GetPrestacionById($id_prestacion);

          	//$pendiente_firma = $$datos_ingreso->GetDocumentosPendintesFirmarPrestacionId($id_prestacion);
          	//$flag_pendinte_firma = (count($pendiente_firma) > 0) ? 1 : 0;
          	$flag_pendinte_firma = 1;
          	$id_ipress = $farmacia->id_establecimiento;
          	$establecimiento = $model_establecimiento->GetById($id_ipress);
          	if ($id_ipress == 1) {
            	$nivel = 2;
          	} else {
            	$nivel = $establecimiento[0]->nivel;
          	}
          	
	        $farmacias = $modelfarma->GetFarmaciaByIdEstablecimiento($id_ipress);
	        $establecimientos = $model_establecimiento->ShowAllEstablecimientos();
	        
          	return view('frontend.hospitalizacion.ver_nota_ingreso', compact('farmacia',  'fechaServidor',  'farmacias', 'nivel',  'flag_pendinte_firma','establecimientos','datos_ingreso','paciente','id_nota_ingreso'));
      else:
         return redirect('/all_pacientes_evoluciones_by_emergencia/')->withInput()->withErrors(array('Evolucion no encontrado ..!'));
      endif;
	      
  	}

  	public function rotacion_cama_hospitalizado($id_farmacia, $id_hospitalizado)
  {
    //exit();
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $model_hospitalizacione = new Hospitalizacione();
    $modelEstablecimiento = new Establecimiento();
    
    $hospitalizado =$model_hospitalizacione->GetBuscaPacienteHospitalizacion($id_hospitalizado,1);
      
    if (count($hospitalizado) > 0) {

      $modelFarmacia = new Farmacia();
      //$modelEstablecimiento = new Establecimiento();
      $model_consultorio = new Consultorio;
      $model_medico = new \App\Models\Medico();
      $model_farmacia_servicio = new \App\Models\Farmacia_servicio();
      $model_usuario = new User;

      //-----------------------------------------------        
      $establecimiento = $modelEstablecimiento->GetById($hospitalizado->id_establecimiento);
      $numero_historia = $hospitalizado->nro_historia;
      //------------------------------------------------------------------
      //------------------------------------------------------------------
      $user = Auth::user()->id;
      
      $id_ipress = $establecimiento[0]->id;
      $date = Carbon::now();
      $fecha = $date->format('d-m-Y');
      $consultorio = $model_consultorio->ShowConsultorios(Auth::user()->id_farmacia);

      $medico = $model_medico->ShowallById_Ipress($id_ipress);
      $nivel = $establecimiento[0]->nivel;

      $tipdoc = "1";
      $dni  = $hospitalizado->nro_doc_ident;
      
      if (Auth::user()->id_consultorio != 17) {
      	$model_cama = new \App\Models\Cama;

      	$cama = $model_cama->ShowBuscaCama($hospitalizado->id_cama,$nivel,$hospitalizado->id_consultorio,$hospitalizado->id_consultorio_hosp);
        
        $model_cuarto = new \App\Models\Cuarto;
      	$pabellon = $model_cuarto->BuscaCuarto($hospitalizado->id_cuarto,$id_ipress);

        if ($nivel == 2)
          $medico = DB::table('medicos')->where('id', $hospitalizado->id_medico)->get();    
      }

      $cantidad_hospitalizados = DB::table('hospitalizaciones')
        ->select('hospitalizaciones.*')
        ->where('dni_beneficiario', $hospitalizado->nro_doc_ident)
        ->whereNull('fecha_salida')
        ->count();

      $model_kardex = new Kardexe();
      $fechaServidor = $model_kardex->getFechaServidor();

      $model_cama = new \App\Models\Cama;
      $sala = $model_cama->GetConsultorioByIpressCama($id_ipress, $hospitalizado->id_cama);

      
      $id_user_consultorio = Auth::user()->id_consultorio;
      
      if ($cantidad_hospitalizados != 0) {
        if ($id_user_consultorio != 17) {

          return view('frontend.hospitalizacion.form_rotacion_cama', compact('fecha_pasada', 'numero_historia', 'beneficiario', 'hospitalizado', 'establecimiento', 'consultorio', 'diagnostico', 'producto', 'id_farmacia', 'medico', 'cama', 'fecha', 'rango_registro', 'fechaServidor', 'flag_receta_dias', 'nro_atenciones', 'flagresponsablefarmacia', 'flagfiscalizador', 'id_ipress', 'tipo_receta', 'pabellon', 'nivel', 'nro_hospitalizado', 'cie', 'id_hospitalizado', 'sala', 'id_user_consultorio'));
        } else {
          return view('frontend.hospitalizacion.form_rotacion_cama', compact('fecha_pasada', 'numero_historia', 'beneficiario', 'hospitalizado', 'establecimiento', 'consultorio', 'diagnostico', 'producto', 'id_farmacia', 'medico', 'fecha', 'rango_registro', 'fechaServidor', 'flag_receta_dias', 'nro_atenciones', 'flagresponsablefarmacia', 'flagfiscalizador', 'id_ipress', 'tipo_receta', 'nivel', 'nro_hospitalizado', 'cie', 'id_hospitalizado', 'id_user_consultorio'));
        }
      } else {
        return redirect('/all_hospitalizado_by_farmacia/')->withInput()->withErrors(array('No existe paciente hospitalizado con ese nro de hospitalizado..!'));
      }
    } else {
      return redirect('/all_hospitalizado_by_farmacia/')->withInput()->withErrors(array('No existe paciente hospitalizado con ese nro de hospitalizado..!'));
    }
  }

  public function rotacion_cama(Request $request)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $model_farma = new Farmacia;
    $farma = $model_farma->GetFarmaByIdfarma($request->id_farmacia);
    $nro_hospitalizacion = $request->nro_hospitalizado;
    $id_hospitalizacion = $request->id_hospitalizado;
    $model_cuarto = new \App\Models\Cuarto;
    $model_asignacion = new \App\Models\Asignacion_servicio;
    $model_cama = new \App\Models\Cama;
    $hospitalizacion = new \App\Models\Hospitalizacione;
    $model_usuario = new User;
    $paciente_hospitalizado = $hospitalizacion->GetBuscaPacienteHospitalizacion($id_hospitalizacion,1);

    $user = $model_usuario->GetById($request->id_user);
    $nombre_registrador = $user->name . ' ' . $user->paterno . ' ' . $user->materno;

    if (count($paciente_hospitalizado) > 0) {

      $model_consultorio = new \App\Models\Consultorio;
      //$fecha_ingreso = $request->fecha_atencion;
      
      $id_medico                = $request->id_medico != '' ? $request->id_medico : 0;
      
      if($request->id_opcion=='1'){
       	$request->id_cama =$request->id_cama2;
       }


      if ($user->id_consultorio != 17) {
        $id_cama_anterior = $paciente_hospitalizado->id_cama;

        $id_cama = $request->id_cama;
        $completa_datos = $model_cuarto->GetCuartoCamaId($request->id_cama);
        $id_cuarto = $completa_datos[0]->id_cuarto;
        $id_piso = $completa_datos[0]->id_piso;
        $id_pabellon = $completa_datos[0]->id_pabellon;
        $id_consultorio = $completa_datos[0]->id_consultorio;
        $nombre_consultorio = $completa_datos[0]->nombre_consultorio;
        $id_consultorio_hosp = $completa_datos[0]->id_asignacion_servicio;

      } else {
        if ($request->nivel == 3) {
          $id_cama = 0;
          $id_cuarto = 0;
          $id_piso = 0;
          $id_pabellon = 0;
          $id_consultorio_hosp = 0;
          $id_consultorio = 17;
          $nombre_consultorio = 'NEFROLOGIA';
        }
      }
	    DB::table('hospitalizaciones')
	        ->where('id', '=', $request->id_hospitalizado)
	        ->update([
	          'id_consultorio' => $id_consultorio,
	          'id_cuarto' => $id_cuarto,
	          'id_cama' => $id_cama,
	          'id_piso' => $id_piso,
	          'id_pabellon' => $id_pabellon,
	          'id_consultorio_hosp' => $id_consultorio_hosp,
	    ]);

        $id_rotacion = $model_cama->RegistrarRotacion($request->id_hospitalizado,$request->dni_beneficiario , $request->id_opcion , $request->fecha_cambio , $id_medico, $id_pabellon , $id_piso , $id_consultorio_hosp , $nombre_consultorio , $id_cuarto ,$id_cama , $id_consultorio , Auth::user()->id , $request->id_establecimiento,$request->documento);

      //actualiza cama
       
      if ($user->id_consultorio != 17) {
        if ($paciente_hospitalizado->id_cama != $request->id_cama) {
          $model_cama->actualizaEstado($request->id_cama, 1);
          $model_cama->actualizaEstado($paciente_hospitalizado->id_cama, 0);
        }
      }

      Session::flash('flash_message', 'Se han actualizado la cama del paciente');
      return redirect('/rotacion_cama_hospitalizado/' . $request->id_farmacia . '/' . $id_hospitalizacion);
    } else {
      Session::flash('flash_message', 'No se puede actualizar este registro, hubo un problema con la conexión de internet de su establecimiento PNP, vuelva a intentarlo nuevamente');
      return redirect('/rotacion_cama_hospitalizado/' . $request->id_farmacia . '/' . $id_hospitalizacion);
    }
  }

  public function modal_historial_cama($id_hospitalizado){
        $model_hospitalizacion = new \App\Models\Hospitalizacione;
        $rotaciones = $model_hospitalizacion->GetObtenerDatosRotacion($id_hospitalizado);
        
        return view('frontend.hospitalizacion.modal_historial_camas',compact('rotaciones'));
    }

    public function modifica_admision_hospitalizacion(Request $request) {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

   // if (count($request->all()) > 0) {
      $modelFarmacia = new Farmacia();
      $model_cie = new \App\Models\Diagnostico;
      $model_consultorio = new Consultorio;
      $model_consultorio_cama = new \App\Models\Cama;
      $model_diagnostico = new \App\Models\Diagnostico;
      $model_medico = new \App\Models\Medico();
      $model_receta_vale = new Receta_vale;
      $model_farmacia_servicio = new \App\Models\Farmacia_servicio();
      $model_kardex = new Kardexe();
      $model_usuario = new User;

      $fechaServidor = $model_kardex->getFechaServidor();
      //-----------------------------------------------        
      $id_establecimiento=Auth::user()->id_establecimiento;
      $dni_medico=Auth::user()->dni; 

   		$id_hospitalizacion= $request->nro_hospitalizacion;
   		$model_hospitalizacione = new Hospitalizacione();
   		$data=$model_hospitalizacione->GetListaHospitalizado_id($id_establecimiento, "1", $id_hospitalizacion);

   		$dni_beneficiario=  $data[0]->dni_beneficiario;
   		$id_asegurado =  $data[0]->id_asegurado;
   		//$tipo_doc_ident= $data[0]->tipo_doc_ident;
   		//$id_consultorio_destino= $data[0]->id_consultorio_destino;

   		$paciente= $data[0]->paterno_beneficiario." ".$data[0]->materno_beneficiario." ".$data[0]->nombre_beneficiario;
   		$descripcion_triaje =  $data[0]->servicio_origen;
   		$id_triaje = $data[0]->id_servicio_origen;
   		$id_consultorio_destino = $data[0]->id_consultorio_hosp;
   		$turno = $data[0]->turno;
   		$id_cama = $data[0]->id_cama;
   		$id_consultorio_hosp = $data[0]->id_consultorio_hosp;
   		$id_sala_reg = $data[0]->id_consultorio_hosp;

   		$date = date_create($data[0]->fecha_ingreso);
   		$fecha_atencion = date_format($date, 'd-m-Y');
   		$horax = date_create($data[0]->hora_ingreso);
   		$hora_ingreso = date_format($horax, 'H:i');

   		$sala = $model_consultorio_cama->GetConsultorioByIpress($id_establecimiento);
   		$cuarto = $model_consultorio_cama->GetCamaByIdConsultorio_todos($id_sala_reg);

   		$id_cuarto = $id_cama;
   		//$model_cama = new \App\Models\Cama;
      	//$sala = $model_cama->GetConsultorioByIpressCama($id_establecimiento, $id_cama);

   		$id_medico_egreso= $data[0]->id_medico;
   		$medico_egreso=$data[0]->pat_medico.' '.$data[0]->mat_medico.' '.$data[0]->nomb_medico;
   		$medico_dni= $data[0]->medico_dni;
   		$especializacion= $data[0]->especializacion;
   		$profesion= $data[0]->profesion;

   		//DIAGNOSTICOS
   		$id_diagnostico_inicial = $data[0]->id_diagnostico_inicial;
        $diagnostico_1 = $model_diagnostico->GetDiagnosticoById($id_diagnostico_inicial);
        $nombre_diagnostico_inicial = $diagnostico_1[0]->nombre;        

        //DIAGNOSTICOS
        $model_emergencia = new \App\Models\Emergencia();
        $prestacion= $model_emergencia->getIdPrestacion($data[0]->id_ingreso);
    	$id_prestacion =  $prestacion->id;
    	
        $model_prestacion = new \App\Models\Prestaciones;        
        $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion); 

		foreach($diagnostico as $row):
    		if($row->id_clasificacion_diagnosticos==3){
    			$id_diagnostico = $row->id_diagnostico;
    		} 
    	endforeach; 

        //datos paciente
        $model_establecimiento= new Establecimiento();
   		$nivel = $model_establecimiento->GetById($id_establecimiento)[0]->nivel;   		
   		$farma = $modelFarmacia->GetFarmaByUser($request->id_user);
        $id_farmacia = $farma->id;

		$tipo_receta = "";
		$domicilio = "";
		$telefono_beneficiario = "";
		$correo = "";

		$model_asegurado= new Asegurado();
        $datos_asegurado = $model_asegurado->busca_datos_asegurados_porid($id_asegurado, $id_establecimiento);
        $asegurado = $datos_asegurado[0];

        $tipo_beneficiario= $asegurado->tipo_beneficiario;
   		$id_tipo_beneficiario = $asegurado->id_tipo_beneficiario;

        //obtener edad
        $originalDate = $asegurado->fecha_nac;
        $fech_nac = explode("/", $originalDate);
        $nacimiento=""; $asegurado->edadafiliado = 0;
        if($fech_nac!=null){
        $nacimiento = $fech_nac[2] . '-' . $fech_nac[1] . '-' . $fech_nac[0];        
        $dbDate = Carbon::parse($nacimiento);
        $diffYears = Carbon::now()->diffInYears($dbDate);
        $asegurado->edadafiliado = $diffYears;
    	}
        
        $dni = strtoupper($dni_beneficiario);

   		//datos adicionales
   		$id_asegurado_titular=0; $apellidos_nombres_titular=""; $id_grado=""; $dni_titular="";
   		$id_asegurado_titular= $asegurado->id_asegurado_titular;
   		$paterno_titular=""; $materno_titular=""; $nombre_titular=""; $sexo_titular=""; $tipo_doc_ident_titular="";
   		if($id_asegurado_titular!=null){
			$model_personadatosadicional= new \App\Models\Persona_datos_adicional();
			$personadatosadicional= $model_personadatosadicional->persona_datos_Adicionales($id_asegurado_titular);
			$apellidos_nombres_titular=$personadatosadicional->paterno." ".$personadatosadicional->materno." ".$personadatosadicional->nombre;
			$id_grado = $personadatosadicional->id_grado;
			$dni_titular = $personadatosadicional->nro_doc_ident;

			$paterno_titular=strtoupper($personadatosadicional->paterno);
			$materno_titular=strtoupper($personadatosadicional->materno);
			$nombre_titular =strtoupper($personadatosadicional->nombre);
			$sexo_titular=$personadatosadicional->sexo;
			$tipo_doc_ident_titular=$personadatosadicional->tipo_doc_ident;
		}

      //if ($tipo_doc_ident== "DNI") {
		

        $establecimiento = $modelFarmacia->GetIpressByIduser($request->id_user);
        $id_ipress = $establecimiento->id;

        $user = $model_usuario->GetById($request->id_user);       
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $consultorio = $model_consultorio->ShowConsultorios($id_farmacia);

         

        $tipo_hospitalizacion = 'HO';
        $medico = $model_medico->ShowallById_Ipress($id_ipress);

        $model_beneficiario = new \App\Models\Beneficiario();
        //$beneficiario = $model_beneficiario->buscar_personal_dni($dni, 'DNI');
       
      
        $model = new \App\Models\Cuarto();
          $pabellon = $model->GetCuartoTodosId(1, $id_ipress); //cuarto
          $consultorios = $model->GetConsultorioId($id_ipress); //cuarto
          if (count($consultorios) > 0)
            $id_consultorio = $consultorios->id_consultorio;

        if ($asegurado!="") {
          $opcReceta = 1;

          $tipo_receta = "HOSPITALIZACION";

		$model_grado=new \App\Models\Tipo_grado();
		$grado=$model_grado->ShowTipoGrado();

			$tarifa="";
			if($id_tipo_beneficiario=="8"){//titulares
				$tarifa="T1";
			}else if($id_tipo_beneficiario>="1" && $id_tipo_beneficiario<="12"){//derecho habientes
				$tarifa="T3";
			}else if($id_tipo_beneficiario=="13"){//civiles (NO TIENES SALUDPOL) 
				$tarifa="T4";
			}
			
			$id_emergencia="0";
			$opcion=1; 
			
            //----------------------------------------------------------------------
            return view('frontend.hospitalizacion.form_hospitalizacion_registro', compact('establecimiento', 'consultorio', 'diagnostico',  'id_farmacia', 'medico', 'fecha', 'fechaServidor', 'opcReceta', 'id_ipress', 'tipo_receta', 'pabellon', 'nivel', 'id_consultorio', 'tipo_hospitalizacion', 'id_consultorio_destino','dni_medico','paciente','tipo_beneficiario','tarifa', 'apellidos_nombres_titular', 'id_grado','grado','dni_titular','asegurado','id_tipo_beneficiario','paterno_titular','materno_titular','nombre_titular','sexo_titular','tipo_doc_ident_titular','descripcion_triaje','id_emergencia','id_triaje','id_diagnostico_inicial','nombre_diagnostico_inicial','id_hospitalizacion','turno','id_cama','id_consultorio_hosp','medico_dni', 'medico_egreso','especializacion', 'profesion','id_medico_egreso','fecha_atencion','hora_ingreso','sala','id_sala_reg','cuarto','id_cuarto','opcion'));

        } else {
          return redirect('/all_pendientes_hospitalizacion/')->withInput()->withErrors(array('No se encuentra paciente ..!'));
        }
	
	}

	private function formulario_atencion_escalas() 
  {
    $aPuntaleLetras = ['', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];
    $model_reseta_vale = new \App\Models\Receta_vale();
    $escala = $model_reseta_vale->GetEscalas();
    $escala_opciones = $model_reseta_vale->GetEscalaOpciones();
    $aEscala = [];
    foreach ($escala as $item) {
      $aEscala[$item->id]['nombre'] = $item->nombre;
      $aEscala[$item->id]['tipo'] = $item->tipo;
    }
    foreach ($escala_opciones as $item) {
      if ($item->id_parent == 0) {
        $aEscala[$item->id_escala]['item'][$item->id]['nombre'] = $item->nombre;
      } else {
        $puntaje = null;
        if ($aEscala[$item->id_escala]['tipo'] == 3) {
          $puntaje = isset($aPuntaleLetras[$item->puntaje]) ? $aPuntaleLetras[$item->puntaje] : '--';
        } else {
          $puntaje = $item->puntaje;
        }
        $aEscala[$item->id_escala]['item'][$item->id_parent]['item'][] = [
          'nombre' => $item->nombre,
          'puntaje' => $puntaje
        ];
      }
    }
    return $aEscala;
  }

  ///// ALTAS ADMINISTRATIVAS NFALTA TERMINAR ACA QUE BLADE IRIA SI YA EXISTE O CREAR UNO NUEVO IRWINCITO HOSPITALIZACION

  public function ver_alta_administrativa($id_user, $id_hospitalizacion) // $id_emergencia es $id_hospitalizacion
  {   
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
  
    $hospitalizacion_prestacion = Prestaciones::where('id_ext', '=', $id_hospitalizacion)->first();
    if(is_object($hospitalizacion_prestacion)):

      $id_establecimiento=Auth::user()->id_establecimiento;
      $model_prestacion = new Prestaciones();
      $prestacion= $model_prestacion->getByIdExtYIdTipoAtencion($id_hospitalizacion, 3);
      $id_prestacion =  $prestacion->id;

      //usar eloquent directo
//$hospitalizacion = Hospitalizacione::where('id', '=', $id_hospitalizacion)->first();

      $hospitalizacion = Hospitalizacione::select('hospitalizaciones.*', 'users.*')
      ->join('users', 'hospitalizaciones.id_user', '=', 'users.id')
      ->where('hospitalizaciones.id', $id_hospitalizacion)
      ->first();

      $fecha_alta_administrativa = $hospitalizacion->fecha_alta_administrativa;
      //obtener el usuario_administrativo
      $name = $hospitalizacion->name;
      // $paciente_emergencia = $model_emergencia->GetBuscaEmergenciaTriaje($id_hospitalizacion,$emergencia->estado);
      //$egreso = $model_emergencia->GetEgresoEmergencia($id_hospitalizacion);
      $model_establecimiento = new \App\Models\Establecimiento;
      $model_prestacion = new \App\Models\Prestaciones;
      $model_kardex = new \App\Models\Kardexe();
      $fechaServidor = $model_kardex->getFechaServidor();
      $horaservidor = $model_kardex->getHoraServidor();
      $date = Carbon::now();
      $fecha_actual = $date->format('d-m-Y');
      $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
      $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
      $flag_pendinte_firma = ($hospitalizacion->fecha_firma_egreso == "") ? 1 : 0;

      if($prestacion->id_ext!=0):
        if($prestacion->tipo_atencion=='HOSPITALIZACION'):
          
            $model_emergencia = new \App\Models\Emergencia;
            //$obtenerdato = $model_emergencia->GetObtenerServicioActual($prestacion->id_ext);
            $datoadicional = $model_emergencia->GetObtenerCama($hospitalizacion->id_cama_estado);
            $sw = 1;
          
        endif;
      else:
          return redirect('/dashboard')->withInput()->withErrors(array('No encontro la atención..!'));
      endif;

      $gestacion = null;
      $puerperio = null;
      if (isset($hospitalizacion->id_gestacion)) {
        $gestacion = Gestacion::find($hospitalizacion->id_gestacion);
        error_log('HAY GESTACION EN ALTA: '.(string)$gestacion);
      }
      if (isset($hospitalizacion->id_puerperio)) {
        $puerperio = Puerperio::find($hospitalizacion->id_puerperio);
        error_log('HAY PUERPERIO EN ALTA: '.(string)$puerperio);
        $natis = $puerperio->natis;
        error_log('sus natis: '.(string)$natis);
      }
      
        // $fechaEgreso = $model_kardex->getFechaServidor();
        // $horaEgreso = $model_kardex->getHoraServidor();
      $establecimiento = $model_establecimiento->GetById($id_establecimiento);
      if ($id_establecimiento == 1) {
        $nivel = 2;
      } else {
        $nivel = $establecimiento[0]->nivel;
      }
      if($hospitalizacion->id_tipo_egreso==3){
        $estable = "select * from establecimientos e where id = '$hospitalizacion->id_establecimiento_traslado'";
        $estable=  DB::select($estable);
        $estable = $estable[0];
      }
    
      $model_consultorio = new \App\Models\Consultorio;
      $consulta_externa = $model_consultorio->ShowConsultorioByIdIpressAndIdTipoAtencion($id_establecimiento,1);
      $hospitalizacion1 = "select u.descripcion_upss as nombre,
                          u.id,
                          u.codigo 
                          from consultorios c 
                          inner join upsses u on u.id_consultorio = c.id 
                          where u.id_tipo_atencion = '3' and u.estado = '1' and u.descripcion_upss like 'H%'";
      $hospitalizacion1 = DB::select($hospitalizacion1);

      $diagnosti= array();
      $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$hospitalizacion->id_diag_cab'";
      $diagnos = DB::select($diagnos);
      $diagnos = $diagnos[0];
      $diagnos->cabecera = "PRINCIPAL";
      $diagnos->tipo = "DEFINITIVO";
      $diagnosti[0]=$diagnos;
      if($hospitalizacion->id_diag_cuer1>0){
        
          $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$hospitalizacion->id_diag_cuer1'";
          $diagnos = DB::select($diagnos);
          $diagnos = $diagnos[0];
          $diagnos->cabecera = "SECUNDARIO";
          $diagnos->tipo = "DEFINITIVO";
          $diagnosti[1]=$diagnos;
      }elseif($hospitalizacion->id_diag_cuer2>0){
        
          $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$hospitalizacion->id_diag_cuer2'";
          $diagnos = DB::select($diagnos);
          $diagnos = $diagnos[2];
          $diagnos->cabecera = "SECUNDARIO";
          $diagnos->tipo = "DEFINITIVO";
          $diagnosti[2]=$diagnos;
      }
    
      $dni=Auth::user()->dni;
      $model_medico = new Medico();
      $exite_medico=$model_medico->exite_medico($dni);
      $exite_medico_ipress=$model_medico->medico_yaregistrado($dni, $id_establecimiento);

      $model_usuario = new User();
      $usuario=$model_usuario->GetById($hospitalizacion->id_medico_alta);
      $medico = $model_medico->GetDatosMedicoAndEstablecimiento($usuario->dni, $usuario->id_establecimiento);
      
      if($exite_medico==0){
        Session::flash('flash_message', 'Sólo los médicos pueden registrar alta médica');
        return redirect('/all_hospitalizaciones_by_alta_administrativa');
      }else if($exite_medico_ipress==""){
        $model_establecimiento = new Establecimiento();
        $establecimiento=  $model_establecimiento->GetById($id_establecimiento);
        Session::flash('flash_message', 'Usted no es Médico en el establecimiento: '. $establecimiento[0]->nombre);
        return redirect('/all_hospitalizaciones_by_alta_administrativa');
      }else{

          return view('frontend.hospitalizacion.alta_administrativa_historiales', compact('fecha_alta_administrativa','name','prestacion','estable','diagnosti','diagnostico', 'id_prestacion', 'fechaServidor', 'horaservidor', 'nivel', 'flag_pendinte_firma',  'hospitalizacion1', 'consulta_externa', 'hospitalizacion','medico','datoadicional', 'sw' , 'gestacion', 'puerperio', 'natis'));

      } 
    else:
      Session::flash('flash_message', 'Necesita tener una prestacion para dar un Alta Medica');
      return redirect('/all_hospitalizaciones_by_alta_administrativa'); 
    endif;
  }
  public function admision_hospitalizacion_nuevo($id_emergencia = "")
  {
    if (!Auth::check()):
      return redirect('/auth/login');
    endif;

    // if (count($request->all()) > 0) {
    $modelFarmacia = new Farmacia();
    $model_cie = new \App\Models\Diagnostico;
    $model_consultorio = new Consultorio;
    $model_diagnostico = new \App\Models\Diagnostico;
    $model_medico = new \App\Models\Medico();
    $model_receta_vale = new Receta_vale;
    $model_farmacia_servicio = new \App\Models\Farmacia_servicio();
    $model_kardex = new Kardexe();
    $model_usuario = new User;
    $model_em= new Emergencia();

    $fechaServidor = $model_kardex->getFechaServidor();
    //-----------------------------------------------        
    $id_establecimiento = Auth::user()->id_establecimiento;
    $dni_medico = Auth::user()->dni;
    //Agregando campo_hora_alta_medica:
    $hora_alta = null;
    $fecha_alta=null;
    $emergencia=$model_em->getByidHoraEgreso($id_emergencia);
    if ($emergencia != null && count($emergencia) > 0) {
      $fecha_alta = $emergencia[0]->fecha_alta_medica;
      $hora_alta = $emergencia[0]->hora_alta_medica;
      //dd($emergencia);
    }
    // $dni_beneficiario = $request->dni_beneficiario;

    $id_consultorio_destino = "";
    $descripcion_triaje = "";
    $id_medico_egreso = "";
    $medico_egreso = "";
    // $id_emergencia = "";

    $id_user = Auth::user()->id;
    $model_establecimiento = new Establecimiento();
    $nivel = $model_establecimiento->GetById($id_establecimiento)[0]->nivel;
    $farma = $modelFarmacia->GetFarmaByUser($id_user);
    $id_farmacia = $farma->id;

    $tipo_receta = "";
    $domicilio = "";
    $telefono_beneficiario = "";
    $correo = "";

    $establecimiento = $modelFarmacia->GetIpressByIduser($id_user);
    $id_ipress = $establecimiento->id;

    $user = $model_usuario->GetById($id_user);
    $date = Carbon::now();
    $fecha = $date->format('d-m-Y');
    //$consultorio = $model_consultorio->ShowConsultorios($id_farmacia);
    //$consultorio = $model_consultorio->ShowConsultoriosHospi();



    $tipo_hospitalizacion = 'HO';
    $medico = $model_medico->ShowallById_Ipress($id_ipress);

    $model_beneficiario = new \App\Models\Beneficiario();
    //$beneficiario = $model_beneficiario->buscar_personal_dni($dni, 'DNI');


    $model = new \App\Models\Cuarto();
    $pabellon = $model->GetCuartoTodosId(1, $id_ipress); //cuarto
    $consultorios = $model->GetConsultorioId($id_ipress); //cuarto
    if (count($consultorios) > 0)
      $id_consultorio = $consultorios->id_consultorio;
    $tipo_receta = "HOSPITALIZACION";

    $model_grado = new \App\Models\Tipo_grado();
    $grado = $model_grado->ShowTipoGrado();

    $tarifa = "";
    $id_tipo_beneficiario = "";
    $opcReceta = 1;
    $tipo_doc_ident_titular = 1;

    $model_tipo_documento_identidad = new \App\Models\Tipo_documento_identidad();
    $tipo_documento_identidad = $model_tipo_documento_identidad->ShowTipoDocumentoIdentidad();

    $model_beneficiario = new \App\Models\Tipo_beneficiario;
    $tipo_beneficiario = $model_beneficiario->ShowTipo_beneficiario();


    $model = new Triaje();
    $prioridades = $model->ShowPrioridad(); //prioridades i ii iii iv 

    $model_parametros = new App\Models\Parametros;
    $profesionales = $model_parametros->getProfesionalesByIdEstablecimiento($id_establecimiento);

    $model_tipo_atencione = new App\Models\TipoAtencione;
    $tipo_origenes = $model_tipo_atencione->ShowTipoAtencionAll();

    /*
      $hospitalizacion = "select c.nombre, u.id, u.codigo from consultorios c inner join upsses u on 
      u.id_consultorio = c.id where u.id_tipo_atencion = '3' and u.estado = '1' and u.descripcion_upss is not null";
      $area_serv_hospitalizacion = DB::select($hospitalizacion);
      */

    $departamento = new Distrito;
    $departamentos = $departamento->ShowDepartamento();

    $model_tipo_parentescos = new Tipo_parentescos;
    $tipo_parentesco = $model_tipo_parentescos->getTipo_parentescos();

    $upsses = "SELECT DISTINCT up.id id_upss, up.descripcion_upss, up.codigo codigo_upss, co.estado 
      FROM upsses as up
      INNER JOIN consultorios as co ON co.id = up.id_consultorio
      inner join asignacion_servicios as2 on co.id = as2.id_consultorio
      WHERE up.estado = 1 and as2.id_establecimiento = 76 and as2.estado = 0 
      And up.id_tipo_atencion = 3
      ORDER BY up.descripcion_upss;";
    $upsses= DB::select($upsses);

    $model_cama = new Cama();
    $pabellones_servicios_cuartos = $model_cama->GetAllPabellonesYServicioOSalaYCuartos($id_establecimiento, 'HOSPITALIZACION');

    $model_paciente = new Paciente();
    $razas = $model_paciente->GetAllRazas();
    $tipo_sangres = $model_paciente->GetAllTipoSangres();
    $estado_civiles = $model_paciente->GetAllEstadoCiviles();
    $religiones = $model_paciente->GetAllReligiones();
    $grado_instrucciones = $model_paciente->GetAllGradoInstrucciones();
    $ocupaciones = $model_paciente->GetAllOcupaciones();
    
    // Obtener id prestacion de la atencion de origen
    $id_ext_prestacion_origen = 0;
    if ($id_emergencia != "") {
      $ext_prestacion_origen = DB::table('prestaciones')->where('id_ext',$id_emergencia)->where('id_tipo_atencion', 2)->where('id_estado_reg', 1)->select('id')->first();
      $id_ext_prestacion_origen = ($ext_prestacion_origen != null) ? $ext_prestacion_origen->id : 0;
    }
    //----------------------------------------------------------------------
    return view('frontend.hospitalizacion.form_hospitalizacion_nuevo2',
      compact(
        'fecha_alta',
        'hora_alta',
        'etnias', 'tipo_parentesco',
        'establecimiento',
        'upsses',
        'diagnostico',
        'id_farmacia',
        'medico',
        'fecha',
        'fechaServidor',
        'opcReceta',
        'id_ipress',
        'prioridades',
        'tipo_receta',
        'pabellon',
        'nivel',
        'id_consultorio',
        'tipo_hospitalizacion',
        'id_consultorio_destino',
        'dni_medico',
        'paciente',
        'tipo_beneficiario',
        'tarifa',
        'apellidos_nombres_titular',
        'id_grado',
        'grado',
        'dni_titular',
        'asegurado',
        'id_tipo_beneficiario',
        'paterno_titular',
        'materno_titular',
        'nombre_titular',
        'sexo_titular',
        'tipo_doc_ident_titular',
        'descripcion_triaje',
        'tipo_documento_identidad',
        'tipo_beneficiario',
        'departamentos',
        'id_emergencia',
        'id_ext_prestacion_origen',
        'tipo_origenes',
        'profesionales',
        'razas',
        'tipo_sangres',
        'estado_civiles',
        'religiones',
        'grado_instrucciones',
        'ocupaciones',
        'pabellones_servicios_cuartos'
      )
    );
  }

  public function obtener_datos_por_id_emergencia($id)
  {
    // $model_emergencia_triaje = new Emergencia();
    // $emergencia_triaje = $model_emergencia_triaje->obtenerRegistroEmergencia_($id);
    // return json_encode($emergencia_triaje);
    // ;
    $model_hospitalizacion_orden = new App\Models\HospitalizacionOrden();
    $hospitalizacion_orden = $model_hospitalizacion_orden->GetDatosById($id);
    return json_encode($hospitalizacion_orden);
  }

  public function registrar_hospitalizaciones(Request $request)
  {
    if (!Auth::check()):
      return redirect('/auth/login');
    endif;

    if (isset($request->id_hospitalizacion)) { //actualizar

    } else {

      $id_asegurado = $request->id_asegurado;

      // Paciente Nuevo
      if ($id_asegurado == "") {

        $paciente = new Paciente;
        $paciente->tipo_doc_ident = $request->tipo_doc_ident;
        $paciente->nro_doc_ident = $request->nro_doc_ident;
        $paciente->cip = $request->cip_beneficiario;
        $paciente->nombre = $request->nombre_beneficiario;
        $paciente->paterno = $request->paterno_beneficiario;
        $paciente->materno = $request->materno_beneficiario;
        $paciente->sexo = $request->sexo;
        $paciente->fecha_nac = $request->fecha_nac;
        $paciente->id_estado_reg = 1;
        $paciente->created_us = Auth::user()->id;

        $paciente->id_raza = $request->id_etnia;
        $paciente->id_tipo_sangre = $request->id_tipo_sangre;
        $paciente->id_estado_civil = $request->id_estado_civil;
        $paciente->id_religion = $request->id_religion;
        $paciente->id_grado_instruccion = $request->id_grado_instruccion;
        $paciente->id_ocupacion = $request->id_ocupacion;
        $paciente->id_distrito_nacimiento = $request->id_distrito_nacimiento;
        $paciente->direccion_nacimiento = strtoupper($request->direccion_nacimiento);
        $paciente->id_distrito_procedencia = $request->id_distrito_procedencia;
        $paciente->direccion_procedencia = strtoupper($request->direccion_procedencia);
        $paciente->id_distrito = $request->id_distrito_domicilio;
        $paciente->domicilio = strtoupper($request->direccion_domicilio);

        $id_tipo_doc_ident = 1;
        switch ($request->tipo_doc_ident) {
          case "DNI": $id_tipo_doc_ident = 1; break;
          case "CE": $id_tipo_doc_ident = 2; break;
          case "PAS": $id_tipo_doc_ident = 3; break;
          case "DIE": $id_tipo_doc_ident = 4; break;
        }
        $paciente->id_tipo_doc_ident = $id_tipo_doc_ident;

        $objTipoBeneficiario = new \App\Models\Tipo_beneficiario;
        $tipo_beneficiario = $objTipoBeneficiario->getTipoBeneficiarioDescripcion($request->parentesco_paciente);
        if ($tipo_beneficiario != null) {
          $paciente->id_tipo_beneficiario = $tipo_beneficiario->id;
        }

        // Si se encontro beneficiario:
        // Inicio de insersion en ASEGURADOS de pacientes presentes en SALUDPOL
        $beneficiario_ajax = json_decode($request->beneficiario_ajax);
        if ($beneficiario_ajax) {
          $id_tipo_doc_ident = null;
          $id_tipo_beneficiario = null;
          $fecha = $beneficiario_ajax->caducidad;
          $anio = substr($fecha, 0, 4);
          $mes = substr($fecha, 4, 2);
          $dia = substr($fecha, 6, 2);
          $fechaFormateada = "$dia/$mes/$anio";

          switch ($beneficiario_ajax->nomtipdocafiliado) {
            case 'DNI':
              $id_tipo_doc_ident = 1;
              break;
              
              default:
              break;
          }

          switch ($beneficiario_ajax->parentesco) {
            case 'PADRES':
              $id_tipo_beneficiario = 1;
              break;
            case 'HIJO MENOR DE EDAD':
              $id_tipo_beneficiario = 2;
              break;
            case 'CONCUBINA(O)':
              $id_tipo_beneficiario = 3;
              break;
            case 'HIJO NO INCAPACITADO':
              $id_tipo_beneficiario = 11;
              break;
            case 'CONYUGE':
              $id_tipo_beneficiario = 4;
              break;
            case 'HIJO MAYOR DE EDAD':
              $id_tipo_beneficiario = 5;
              break;
            case 'HIJO INCAPACITADO':
              $id_tipo_beneficiario = 6;
              break;
            case 'DERECHOHABIENTE':
              $id_tipo_beneficiario = 7;
              break;
            case 'TITULAR':
              $id_tipo_beneficiario = 8;
              break;
            case 'PADRES SOBREVIVIENTE':
              $id_tipo_beneficiario = 9;
              break;
            case 'ESPOSA':
              $id_tipo_beneficiario = 10;
              break;
            case 'OTROS':
              $id_tipo_beneficiario = 12;
              break;
            case 'CIVIL':
              $id_tipo_beneficiario = 13;
              break;
            case 'HIJO SOBREVIVIENTE':
              $id_tipo_beneficiario = 14;
              break;
            case 'CONYUGUE SOBREVIVIENTE':
              $id_tipo_beneficiario = 15;
              break;
            case 'HIJO INCAPACITADO SOBREVIVIENTE':
              $id_tipo_beneficiario = 16;
              break;
            case 'HIJO NO INCAPACITADO SOBREVIVIENTE':
              $id_tipo_beneficiario = 17;
              break;
            case 'PADRES SOBREVIVIENTE':
              $id_tipo_beneficiario = 18;
              break;
            case 'TITULAR SOBREVIVIENTE':
              $id_tipo_beneficiario = 19;
              break;
            case 'HIJO ESTUDIANTE':
              $id_tipo_beneficiario = 20;
              break;
            case 'HIJO MAYOR DE EDAD NO ESTUDIANTE':
              $id_tipo_beneficiario = 21;
              break;
            case 'CONCUBINA(O) SOBREVIVIENTE':
              $id_tipo_beneficiario = 22;
              break;
              
              default:
              break;
          }

          $asegurado_titular = null;
          
          $asegurado_titular = Asegurado::where("nro_doc_ident", "=", $beneficiario_ajax->nrodoctitular)->first();

          if ($asegurado_titular) {
          }

          $paciente->id_tipo_beneficiario = $id_tipo_beneficiario;
          $paciente->id_tipo_doc_ident = $id_tipo_doc_ident;
          $paciente->caducidad = $fechaFormateada;
          $paciente->dni_titular = $beneficiario_ajax->nrodoctitular;
          $paciente->id_asegurado_titular = $beneficiario_ajax != null ? $asegurado_titular->id : "";
          $paciente->materno_titular = $beneficiario_ajax->apemattitular;
          $paciente->nombre_titular = $beneficiario_ajax->nomtitular;
          $paciente->paterno_titular = $beneficiario_ajax->apepattitular;
          $paciente->tipo_doc_titular = $beneficiario_ajax->nomtipdoctitular;
        }

        $paciente->save();
 
        $id_asegurado = $paciente->id;
      } else {
        // Actualizar datos del paciente
        $paciente = Paciente::find($id_asegurado);
        $paciente->id_raza = $request->id_etnia;
        $paciente->id_tipo_sangre = $request->id_tipo_sangre;
        $paciente->id_estado_civil = $request->id_estado_civil;
        $paciente->id_religion = $request->id_religion;
        $paciente->id_grado_instruccion = $request->id_grado_instruccion;
        $paciente->id_ocupacion = $request->id_ocupacion;
        $paciente->id_distrito_nacimiento = $request->id_distrito_nacimiento;
        $paciente->direccion_nacimiento = strtoupper($request->direccion_nacimiento);
        $paciente->id_distrito_procedencia = $request->id_distrito_procedencia;
        $paciente->direccion_procedencia = strtoupper($request->direccion_procedencia);
        $paciente->id_distrito = $request->id_distrito_domicilio;
        $paciente->domicilio = strtoupper($request->direccion_domicilio);
        $paciente->save();
      }
      
        $model_hospitalizacion = new \App\Models\Hospitalizacione;
        $id_establecimiento = Auth::user()->id_establecimiento;
        $nro_hospitalizacion = $model_hospitalizacion->getNroHospitalizacion($id_establecimiento);

        $model_establecimiento = new \App\Models\Establecimiento;
        $establecimiento = $model_establecimiento->GetById($id_establecimiento);
      	$nivel = $establecimiento[0]->nivel;

        $resultTransaction = DB::Transaction(function () use ($request, $nro_hospitalizacion, $id_establecimiento, $id_asegurado, $nivel) {
          // $model_cuarto = new \App\Models\Cuarto;
          $model_consultorio = new \App\Models\Consultorio;
          $model_asignacion = new \App\Models\Asignacion_servicio;
          $model_cama = new \App\Models\Cama;
          $emergencia = new \App\Models\Emergencia();
          // $model_paciente = new Paciente();
          $model_usuario = new User;

          // // Actualizar datos del paciente
          // $paciente = Paciente::find($id_asegurado);
          // $paciente->id_raza = $request->id_etnia;
          // $paciente->id_tipo_sangre = $request->id_tipo_sangre;
          // $paciente->id_estado_civil = $request->id_estado_civil;
          // $paciente->id_religion = $request->id_religion;
          // $paciente->id_grado_instruccion = $request->id_grado_instruccion;
          // $paciente->id_ocupacion = $request->id_ocupacion;
          // $paciente->id_distrito_nacimiento = $request->id_distrito_nacimiento;
          // $paciente->direccion_nacimiento = strtoupper($request->direccion_nacimiento);
          // $paciente->id_distrito_procedencia = $request->id_distrito_procedencia;
          // $paciente->direccion_procedencia = strtoupper($request->direccion_procedencia);
          // $paciente->id_distrito = $request->id_distrito_domicilio;
          // $paciente->domicilio = strtoupper($request->direccion_domicilio);
          // $paciente->save();

          // Verificar si tiene orden de hospitalizacion
          $has_orden_hospitalizacion = ($request->id_hospitalizacion_orden != "");

          $hospitalizacion = new \App\Models\Hospitalizacione;
          $hospitalizacion->id_farmacia = $request->id_farmacia;
          $hospitalizacion->nro_hospitalizacion = $nro_hospitalizacion;
          $hospitalizacion->dni_beneficiario = $request->dni_beneficiario;
          
          $paciente_beneficiario = Paciente::where('id', '=', $id_asegurado)->first();
          $hospitalizacion->nombre_beneficiario = $paciente_beneficiario->nombre;
          $hospitalizacion->paterno_beneficiario = $paciente_beneficiario->paterno;
          $hospitalizacion->materno_beneficiario = $paciente_beneficiario->materno;

          $paciente_titular = Paciente::where('id', '=', $paciente_beneficiario->id_asegurado_titular)->first();
          if (count($paciente_titular) > 0) {
            $hospitalizacion->dni_titular = $paciente_titular->nro_doc_ident;
            $hospitalizacion->nombre_titular = $paciente_titular->nombre;
            $hospitalizacion->paterno_titular = $paciente_titular->paterno;
            $hospitalizacion->materno_titular = $paciente_titular->materno;
            $hospitalizacion->grado = $paciente_titular->grado;
          } else {
            $hospitalizacion->dni_titular = '';
            $hospitalizacion->nombre_titular = '';
            $hospitalizacion->paterno_titular = '';
            $hospitalizacion->materno_titular = '';
            $hospitalizacion->grado = '';
          }

          $hospitalizacion->fecha_nacimiento_beneficiario = $request->fecha_nac;
          $hospitalizacion->edad = $request->edad;
          $hospitalizacion->sexo = $request->sexo;
          $hospitalizacion->tipo_beneficiario = $request->tipo_beneficiario;
          $hospitalizacion->nro_historia = $request->nro_historia_local;
          $hospitalizacion->turno = $request->turno;

          $tarifa = "";
          if ($request->tipo_beneficiario == "CIVIL") { //civiles (NO TIENES SALUDPOL) 
            $tarifa = "T4";
          } else $tarifa = "T1";

          $hospitalizacion->tarifa = $tarifa;
          $hospitalizacion->fecha_ingreso = $request->fecha_atencion; //date("Y-m-d", strtotime(str_replace('/', '-', $request->fecha_atencion)));;
          $hospitalizacion->id_medico = $request->id_campo_busqueda_medico;
          //$hospitalizacion->flag_clinica_dia = 0;
          $hospitalizacion->id_hospitalizacion_orden = $request->id_hospitalizacion_orden;

          if ($has_orden_hospitalizacion) {
          $hospitalizacion->id_upss_origen = $request->id_upss_origen;
          $hospitalizacion->id_servicio_origen = $request->id_servicio_origen;
          } else {
            $hospitalizacion->id_upss_origen = $request->id_upss_encargado;
            $hospitalizacion->id_servicio_origen = $request->id_servicio_encargado;
          }
          $hospitalizacion->id_upss_encargado = $request->id_upss_encargado;
          $hospitalizacion->id_servicio_encargado = $request->id_servicio_encargado;
          $hospitalizacion->id_servicio_destino = isset($request->id_servicio_destino)?$request->id_servicio_destino:null;

          ///////////////////////////////////////////////////////////////
          $esNuevaAdmision = empty($request->id_orden_hospitalizacion);
          //$esClinicaDiaSiendoNuevo = $esNuevaAdmision && intval($request->id_servicio_destino) === 26;//26 CENTRO QUIRURGICO
          $esClinicaDiaOrdenExistente = !$esNuevaAdmision && (intval($request->flag_clinica_dia) === 2 || $request->id_pabellon == 46);//2 es clinica de dia y 46 =sala de recuperacion
          if ($esClinicaDiaOrdenExistente) {
            $hospitalizacion->flag_clinica_dia = intval($request->flag_clinica_dia) === 2 ? 1 : null;
            $hospitalizacion->flag_sala_recuperacion= $request->id_pabellon == 46 ? 1 : null;
            $hospitalizacion->id_pabellon_piso = $request->id_pabellon;
          } else {
            if(intval($request->flag_clinica_dia)!=2){
              $hospitalizacion->flag_clinica_dia = 0;
              $hospitalizacion->flag_sala_recuperacion = 0;
              if (isset($request->id_cama) && !empty($request->id_cama)) {
                $this->asignarCamaYCuarto($hospitalizacion, $request->id_cama);
              }
            }
          }
          ////////////////////////////////////////////////////////////////

          $hospitalizacion->estado = 1;
          $hospitalizacion->id_user = $request->id_user;

          $user = $model_usuario->GetById($request->id_user);
          $hospitalizacion->nombre_registrador = $user->name . ' ' . $user->paterno . ' ' . $user->materno;
          $hospitalizacion->id_establecimiento = $request->id_establecimiento;

          $numero = $hospitalizacion->generar_secuencia_hospitalizacion();
          $hospitalizacion->secuencia = $numero->count + 1;
          $hospitalizacion->id_asegurado = $id_asegurado;
          // $hospitalizacion->id_servicio_origen = $request->id_servicio_encargado;
          $hospitalizacion->hora_ingreso = $request->hora;
          
          $hospitalizacion->cod_siteds_susalud = $request->cod_siteds_susalud;
          $hospitalizacion->updated_at_cod_siteds = Carbon::now();
          $hospitalizacion->updated_us_cod_siteds = Auth::user()->id;
          $hospitalizacion->id_tipo_doc_ident = $request->id_tipo_doc_ident;
          $hospitalizacion->created_at = Carbon::now();

          $i = 1;
          foreach ($request->id_diagnostico as $key => $value) {
            switch ($i) {
              case 1: $hospitalizacion->id_diagnostico_inicial = $value; break;
              case 2: $hospitalizacion->id_dx_2 = $value; break;
              case 3: $hospitalizacion->id_dx_3 = $value; break;
            }
            $i++;
          }
          $hospitalizacion->id_tipo_origen = $request->origen;
          if ($request->id_atencion != null) { 
            $hospitalizacion->id_ext_origen = $request->id_atencion;
            $hospitalizacion->id_ext_prestacion_origen = $request->id_ext_prestacion_origen;
          }
          else{
          	$request->id_atencion = 1;
          }

          $id_tipo_acompaniante = $request->id_tipo_acompaniante;
          $hospitalizacion->id_tipo_acompaniante = $id_tipo_acompaniante;
          if ($id_tipo_acompaniante != 8) {
            $hospitalizacion->tipo_doc_acompaniante = $request->tipo_doc_acompaniante;
            $hospitalizacion->nro_doc_acompaniante = $request->nro_doc_acompaniante;
            $hospitalizacion->nombre_acompaniante = $request->nombre_acompaniante;
            $hospitalizacion->telefono_acompaniante = $request->telefono_acompaniante;
          }

          if ($request->menor_mayor_edad != "") {
            $id_tipo_apoderado = $request->id_tipo_apoderado;
            $hospitalizacion->id_tipo_apoderado = $id_tipo_apoderado;
            if ($id_tipo_apoderado != 8) {
              $hospitalizacion->tipo_doc_apoderado = $request->tipo_doc_apoderado;
              $hospitalizacion->nro_doc_apoderado = $request->nro_doc_apoderado;
              $hospitalizacion->nombre_apoderado = $request->nombre_apoderado;
              $hospitalizacion->telefono_apoderado = $request->telefono_apoderado;
            }
          }

          // $update_historia = new \App\Models\Asegurado_historia();
          $update_historia = Asegurado_historia::
                              where('id_asegurado', '=', $id_asegurado)
                              ->where('id_establecimiento', '=', $id_establecimiento)->first();
          if ($update_historia != null):
            $update_historia->nro_historia = $request->nro_historia_local;
            $successUpdateHistoria = $update_historia->save();
          else:
            $objpaciente= new Asegurado_historia();
            $objpaciente->id_asegurado= $id_asegurado;
            $objpaciente->id_establecimiento= $id_establecimiento;
            $objpaciente->nro_historia= $request->nro_historia_local;
            $objpaciente->id_estado_reg= 1;
            $objpaciente->created_us= $request->id_user;
            $objpaciente->save();
          endif;

          if (isset($request->isDiscapacidad) && intval($request->isDiscapacidad) === 1) {
            $hospitalizacion->id_discapacidad = $request->id_discapacidad;
          }

          $success = $hospitalizacion->save();
          
          //PARA PACIENTE NUEVO EN HOSPITALIZACION CON CLINICA DE DIA
          if(empty($request->id_orden_hospitalizacion) && intval($request->flag_clinica_dia)==2){
            $hospitalizacion->flag_clinica_dia = intval($request->flag_clinica_dia) === 2 ? 1 : null;
            $hospitalizacion->id_pabellon_piso = $request->id_pabellon;
            $fecha_cierre = $request->fecha_atencion;
            $modelintervencion = new \App\Models\CqxIntervencion;
            
            $id_intervencion_cqx = $modelintervencion->RegistrarIntervencionQxDesdeHospitalizacion($hospitalizacion->id, Auth::user()->id,$request->id_asegurado,Auth::user()->id_establecimiento,$request->id_campo_busqueda_medico,$request->id_servicio_encargado); 

            //Registro de diagnosticos cqx
            if (isset($request->id_diagnostico)) :
              $dignostico_cqx = new Receta_diagnostico();
              foreach ($request->id_diagnostico as $key => $value) :
                if ($request->id_diagnostico[$key] != "") {
                  $dignostico_cqx->id_diagnostico = $request->id_diagnostico[$key];
                  $dignostico_cqx->id_tipo_diagnostico = $request->id_tipo_diagnostico[$key];
                  $dignostico_cqx->estado = 1;
                  $dignostico_cqx->created_at=Carbon::now();
                  $dignostico_cqx->created_us=$request->id_user;
                  $dignostico_cqx->id_intervencion=$id_intervencion_cqx;
                  $dignostico_cqx->save();
                }
              endforeach;
            endif; 
          //FIN PARA PACIENTE NUEVO EN HOSPITALIZACION CON CLINICA DE DIA
          }

          if ($success) {
              $model_add_hospi = Hospitalizacione::where('id', '=', $hospitalizacion->id)->first();
              $date = Carbon::now();
              $fechaActual = $date->format('d-m-Y');
              /*GENERAR CUPS PNP HOSPITALIZACION*/
              $objEstablecimiento = Establecimiento::where('id', '=', Auth::user()->id_establecimiento)->first();
              $objCups_pnp = new \App\Models\Cups_pnp;
              $objCups_pnp->codigo_ipress = $objEstablecimiento->codigo;
              $objCups_pnp->año = substr($date->format('Y'), 2, 4);
              $objCups_pnp->intramural_extramural = 'I';
              $objCups_pnp->no_referidos_referidos = 0;
              /*
              if ($request->id_categoria == 4) {
                $objCups_pnp->no_referidos_referidos = 1;
              } else {
                $objCups_pnp->no_referidos_referidos = 0;
              }*/
              $objCups_pnp->tipo_atencion = 3;
              $valorConCeros = $hospitalizacion->id;
              $objCups_pnp->id_ext = $valorConCeros;
              $objCups_pnp->corte_adm = 'CA_00001';
              $objCups_pnp->fecha_ejecucion_atencion = $fechaActual;
              $objCups_pnp->save();
              /*GENERAR CUPS PNP HOSPITALIZACION*/
              $valor_cups_pnp = ($objCups_pnp->codigo_ipress . "-" . $objCups_pnp->año . "-" . $objCups_pnp->intramural_extramural
                . "-" . $objCups_pnp->no_referidos_referidos . "-" . $objCups_pnp->tipo_atencion . "-" . str_pad($hospitalizacion->id, 9, '0', STR_PAD_LEFT));

              $model_add_hospi->cups_pnp = $valor_cups_pnp;
              $model_add_hospi->save();

              if (intval($request->id_servicio_destino) != 26) {
                // Actualizar a Cama Ocupada
                if($request->flag_clinica_dia!=2){
                  $model_cama->actualizaEstado($request->id_cama, 1, $request->id_establecimiento, "4");
                }
              }
          }

            /*if ($user->id_consultorio != 17) {
              //actualiza cama
              $model_cama->actualizaEstado($request->id_cama, 1);
              //contar cama
              $nro_cama = $model_cuarto->consultaCama($id_cuarto);
              $nro_cama_ocupada = $model_cuarto->consultaCamaOcupada($id_cuarto);

              if ($nro_cama == $nro_cama_ocupada)
                $model_cuarto->actualizaEstado($id_cuarto, 1);
              //actualiza piso 
            }*/
            //cambiar estado emergencia

            //DESDE ALTA EMERGENCIA SI EL FLAG_QUIRURGICO=1 
            $orden_hospi = new \App\Models\HospitalizacionOrden();
            if(!empty($request->id_atencion) ){
              $flag_quirurgico = $orden_hospi->flagQuirurgico($request->id_atencion,$request->origen);
            }
            //DEVOLVERA TRUE SI ESA ORDEN DE HOSPI DEL TIPO_ATENCION 2 TIENE EL FLAG_QUIRURGICO=1

            if ($request->origen == 2) {
              $model_emergencia = Emergencia::find($request->id_atencion);
              $model_emergencia->flag_hospitalizado = '1';
              $model_emergencia->save();
            }
            // $esClinicaDiaOrdenExistente = !$esNuevaAdmision && (intval($request->flag_clinica_dia) === 2 || $request->id_pabellon == 46);//2 es clinica de dia y 46 =sala de recuperacion
            if (!empty($request->id_orden_hospitalizacion)) {
              // if ($request->origen == 4) {//DESDE AMBULATORIO el $request->id_atencion si es el id CQ_INTERVENCIONES
              if ($request->origen == 1) {//SE CORRIGIO PARA QUE SEA EL TIPO DE ATENCION AMBULATORIO
                $model_intervencion = new \App\Models\CqxIntervencion();
                $response = $model_intervencion->RegistrarIdHospitalizacion($request->id_atencion, $hospitalizacion->id);
                CqxAtencione::where('id_cqx_intervencion',$request->id_atencion)->update(['id_hospitalizacion' =>$hospitalizacion->id]);
              }
            }
            if (!empty($flag_quirurgico)) {//DESDE EMERGENCIA el $request->id_atencion no es el id CQ_INTERVENCIONES
              $model_intervencion = new \App\Models\CqxIntervencion();
              $model_intervencion->RegistrarIdHospitalizacion2($request->id_atencion,2,$hospitalizacion->id);
            }

            if ($has_orden_hospitalizacion) {
              $model_orden = new \App\Models\HospitalizacionOrden();
              $response = $model_orden->AdmisionarOrden($request->id_orden_hospitalizacion);
            }

            $intervencion = null;

            if(intval($nivel) === 2):
	            $consultorio = Consultorio::where('id', $request->id_servicio_destino)->first();
	            if (isset($consultorio) && intval($consultorio->flag_quirurgico) === 1 && isset($request->isRequireIntervencionQuirurgica) && intval($request->isRequireIntervencionQuirurgica) === 1) {
	              $intervencion = CqxIntervencion::create([
	                'id_asegurado' => $request->id_asegurado,
	                'fecha_solicitud'=> Carbon::now(),
	                'flag'=> 1,
	                'id_estado_reg'=> 1,
	                'created_us' => $request->id_user,
	                'created_at' => Carbon::now(),
	                'id_establecimiento'=> $request->id_establecimiento,
	                'es_nuevo_admision_hospitalizacion'=> true,
	                'id_hospitalizacion'=> $hospitalizacion->id,
	                'fecha_probable' => Carbon::now(),
	                'id_consultorio'=> $request->id_servicio_encargado,
	                'id_tipo_atencion_origen' => $request->origen,
                  'is_validated' => true,
                  'validated_us' => $request->profesional_cqx,
                  'validated_at' => Carbon::now(),
	              ]);
	            }
	        endif;

            $id_hospitalizacion = $hospitalizacion->id;

            return [
              'id_hospitalizacion' => $id_hospitalizacion,
              'id_intervencion' => isset($intervencion) && isset($intervencion->id) ? $intervencion->id : null
            ];
        });
		
		if($nivel==2):
	        if (isset($resultTransaction) && isset($resultTransaction['id_intervencion'])) {
	          session()->flash('success', "Admisión a hospitalización registrada correctamente. Ahora debe proceder a registrar la atención quirúrgica.");
	          return redirect('/cq_lista_pendiente');
	        }
	    endif;

        if ($resultTransaction['id_hospitalizacion'] == 0) {
          Session::flash('flash_message', 'No se pudo registrar la Hospitalización, hubo un problema con la conexión de internet de su establecimiento PNP, vuelva a intentarlo nuevamente');
          return redirect('/all_pendientes_hospitalizacion/');
        } else {
          Session::flash('flash_message', 'Hospitalización exitosa, El numero de Hospitalizacion es ' . $nro_hospitalizacion);
          return redirect('/ver_hospitalizacion/' . $request->id_user . '/' . $resultTransaction['id_hospitalizacion']);
        }

    }
  }



  public function all_hospitalizados_asegurados(Request $request)
  {
      if (!Auth::check()) {
          return redirect('/auth/login');
      }

      $fecha_ini = $request->input('fecha_ini');
      $fecha_fin = $request->input('fecha_fin');
      $id_asegurado = $request->input('id_asegurado');

      try {
          $fecha_ini = $fecha_ini ? Carbon::createFromFormat('d-m-Y', $fecha_ini)->format('Y-m-d') : null;
          $fecha_fin = $fecha_fin ? Carbon::createFromFormat('d-m-Y', $fecha_fin)->format('Y-m-d') : null;
      } catch (\Exception $e) {
          return response()->json([
              'sEcho' => 1,
              'iTotalRecords' => 0,
              'iTotalDisplayRecords' => 0,
              'aaData' => [],
              'error' => 'Formato de fecha inválido'
          ]);
      }

      $numeroPagina = (int) $request->input('NumeroPagina', 1);
      $numeroRegistros = (int) $request->input('NumeroRegistros', 10);

      $parametros = [$id_asegurado, $fecha_ini, $fecha_fin, $numeroPagina, $numeroRegistros];
      $hospitalizaciones = (new Hospitalizacione())->listar_hospitalizaciones_asegurados($parametros);

      $data = [];
      $total = 0;

      foreach ($hospitalizaciones as $registro) {
          $data[] = [
              'id' => $registro->id,
              'id_asegurado' => $registro->id_asegurado,
              'fecha_atencion' => $registro->fecha_atencion,
              'dni_beneficiario' => $registro->dni_beneficiario,
              'nombre_beneficiario' => $registro->nombre_beneficiario,
              'paterno_beneficiario' => $registro->paterno_beneficiario,
              'materno_beneficiario' => $registro->materno_beneficiario,
              'nombre_titular' => $registro->nombre_titular,
              'paterno_titular' => $registro->paterno_titular,
              'materno_titular' => $registro->materno_titular,
              'fecha_nacimiento_beneficiario' => $registro->fecha_nacimiento_beneficiario,
              'sexo' => $registro->sexo,
              'tipo_beneficiario' => $registro->tipo_beneficiario,
              'nro_historia' => $registro->nro_historia,
              'grado' => $registro->grado,
          ];
          $total = $registro->totalrows; 
      }

      return response()->json([
          'sEcho' => intval($request->input('sEcho', 1)),
          'iTotalRecords' => $total,
          'iTotalDisplayRecords' => $total,
          'aaData' => $data
      ]);
  }

  public function UpdateAsegurados(Request $request)
  {
      if (!Auth::check()) {
         return redirect('/auth/login');
      }
  
      $id_hospitalizacion = $request->input('id_hospitalizacion');
      $id_asegurado = $request->input('id_asegurado');
  
      try {
          $resultado = DB::select("SELECT fn_actualizar_hospitalizacion_datos(?, ?)", [
              $id_hospitalizacion,
              $id_asegurado
          ]);
  
          return response()->json([
              'mensaje' => $resultado[0]->fn_actualizar_hospitalizacion_datos
          ]);
      } catch (\Exception $e) {
          return response()->json(['mensaje' => 'Error: ' . $e->getMessage()], 500);
      }
  }
  

  public function all_hospitalizado_by_establecimiento($id_user = 0)
  {
    if (!Auth::check()):
      return redirect('/auth/login'); // 
    endif;
    // $ruta = Route::getCurrentRoute()->getPath();
    // if (count($ruta = explode("/", $ruta)) > 1)
    //   ;
    // $ruta = $ruta[0];
    // if ($ruta == "all_hospitalizado_by_establecimiento_ajax")
    //   ;
    // $ruta = "all_hospitalizado_by_establecimiento";
    // $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
    // $permiso = $menu[0];
    // if ($permiso->lectura == "")
    //   return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
    $id_user = Auth::user()->id;


    $id_user_consultorio = Auth::user()->id_consultorio;
    $model_farma = new Farmacia();
    $farmacia = $model_farma->GetFarmaByUser($id_user);
    $id_farmacia = $farmacia->id;
    $id_establecimiento = $farmacia->id_establecimiento;



    $date = Carbon::now();
    $fechaHasta = $date->format('d-m-Y');
    $fechaDesdex = $date->format('d-m-Y');
    $fechaDesdey = strtotime('-1 day', strtotime($fechaDesdex));
    $fechaDesde = date('d-m-Y', $fechaDesdey);

    /*==========SECCION DE UPSS HOSPITALIZACION==========*/
    $model_upss = new \App\Models\Upss();
    $upss = $model_upss->ShowUPSS_by_Hospitalizacion();
    /*==========SECCION DE UPSS HOSPITALIZACION==========*/

    return view('frontend.hospitalizacion.all_admision_by_hospitalizado2',
      compact(
        'id_user_consultorio',
        'id_user',
        'upss',
        'fechaDesde',
        'fechaHasta'
      )
    );
  }

  /*
  $p[] = Auth::user()->id_establecimiento; //0
  $p[] = $request->fecha_ini; //1
  $p[] = $request->fecha_fin; //2
  $p[] = $request->busqueda_datos; //3
  //$p[] = $request->id_servicio_destino; //4 - RICARDO
  $p[] = strtoupper($request->nombre_beneficiario); //5 - RICARDO
  $p[] = $request->estado; //'0,2,3'  5 seleccionar_estado
  $p[] = $request->NumeroPagina; //6
  $p[] = $request->NumeroRegistros; //7   
   */
  public function all_hospitalizado_by_establecimiento_ajax(Request $request)
  { 
    $model_hospitalizacione = new Hospitalizacione();
    $p[] = Auth::user()->id_establecimiento; //0
    $p[] = $request->fecha_ini; //1
    $p[] = $request->fecha_fin; //2
    $p[] = $request->ndocumento; //3
    $p[] = $request->id_servicio_destino; //4
    $p[] = $request->busqueda_datos; //5
    $p[] = $request->estado; //'0,2,3'  5 seleccionar_estado
    $p[] = $request->NumeroPagina; //6
    $p[] = $request->NumeroRegistros; //7  
    //$p[] = 'atencion'; //8 modulo de donde viene
    if ($request->estado == 1)
      $data = $model_hospitalizacione->GetListaHospitalizacionAtencionPendiente($p);
    else
      $data = $model_hospitalizacione->GetListaTodosHospitalizado2($p);

    $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;

    $result["PageStart"] = $request->NumeroPagina;
    $result["pageSize"] = $request->NumeroRegistros;
    $result["SearchText"] = "";
    $result["ShowChildren"] = true;
    $result["iTotalRecords"] = $iTotalDisplayRecords;
    $result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
    $result["aaData"] = $data;

    echo json_encode($result);
  }

  public function form_atenciones_hospitalizacion($id_hospitalizacion,$id_servicio_encargado ='') {

    if (!Auth::check()):
      return redirect('/auth/login');
    endif;

    $hospitalizacion = Hospitalizacione::where('id', $id_hospitalizacion)->first();
    if (is_object($hospitalizacion) && $hospitalizacion->esFlujoDosVistas() && $hospitalizacion->flag_exa_cu == 1) {
      return redirect('/form_atencion_prestacion_hspcu/' . Auth::user()->id . '/' . $id_hospitalizacion);
    }
    //dd($verificar );
    $valida_atencion = Prestaciones::where('id_ext', $id_hospitalizacion)->where('id_tipo_atencion', 4)->where('id_estado_reg', 1)->where('flag_alta_servicio', 0)->first();
    if (is_object($valida_atencion)):
      return redirect('/all_hospitalizado_by_establecimiento/')->withInput()->withErrors(array('Ya existe una prestación registrada, debe anular la prestacion anterior para poder registrar una nueva..!'));
    endif;
    $estado = $hospitalizacion->estado;
    $model_medicos = new Medico;
    $medico = $model_medicos->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni, Auth::user()->id_establecimiento);
    $id_user = Auth::user()->id;
    $id_farmacia = Auth::user()->id_farmacia;
    $model_upss = new Upss;

    if (is_object($hospitalizacion)):
      if (is_object($medico)):
        
        $model_hospitalizacion = new Hospitalizacione;
        $modelfarma = new Farmacia();
        $modelcama = new Cama();
        $farmacias = $modelfarma->GetFarmaByUser($id_user);
        $id_ipress = $farmacias->id_establecimiento;
        $cita = $model_hospitalizacion->GetBuscaHospitalizado($id_hospitalizacion, $medico->id, $estado);

        // $model_kardex = new Kardexe();
        // $fechaServidor = $model_kardex->getFechaServidor();
        $date = Carbon::now();
        $fechaServidor = $date->format('Y-m-d H:i:s');
        $hora = $date->format('H:i');
        
        $cuartos = $modelcama->GetAllPabellonesYServicioOSalaYCuartos($hospitalizacion->id_establecimiento,'HOSPITALIZACION');
        $cama = $model_hospitalizacion->GetObtenerCama($hospitalizacion->id_cama_estado);

        if ($hospitalizacion->id_servicio_destino != null) {
          $id_cuarto_actual = $hospitalizacion->id_servicio_destino;
        } else {
          $id_cuarto_actual = $cama->id_consultorio;
        }
        $camas = $modelcama->GetCamasByIdEstablecimientoYNombreTipoAtencionYIdCuarto($hospitalizacion->id_establecimiento,'HOSPITALIZACION',$cama->id_consultorio);

        $model_farma = new Farmacia();
        $model_triaje = new Triaje();
        $farmacia = $model_farma->GetFarmaByIdfarma($id_farmacia);

        $id_ipress = $farmacia->id_establecimiento;

        $model_producto = new Producto;
        $model_establecimiento = new \App\Models\Establecimiento;
        $establecimiento = $model_establecimiento->GetById($id_ipress);
        $establecimientos = $model_establecimiento->ShowAllEstablecimientos();
        $tipo_atencion = $model_farma->GetFarmaciaTipoAtencionByIdFarmacia($id_farmacia);

        if ($id_ipress == 1) {
          $nivel = 2;
        } else {
          $nivel = $establecimiento[0]->nivel;
        }

        //$producto = $model_producto->getProductosByNivelAndIdServicio($nivel, $id_ipress, 19);
        $model_consultorio = new Consultorio;
        $establecimiento_servicios = $model_consultorio->GetServiciosPorEstablecimiento($id_ipress);
        if($nivel == 3):
        	$farmacias = $model_farma->GetFarmaciasHospitalizacion(Auth::user()->id_establecimiento);
        else:
        	$farmacias = $model_farma->OnlyFarmaciasXEstablecimiento(Auth::user()->id_establecimiento);
        endif;

        $id_cita = $id_hospitalizacion;
        $id_tipo_atencione = 3;

        $triaje = null;

        $modalidad = 1;
        $flag = 0;

        /*$signos_vitales = null;
        if ($hospitalizacion->id_tipo_origen == 2):
          $emergencia = Emergencia::find($hospitalizacion->id_ext_origen);
          $evaluacion = DB::table('evaluaciones as ev')
            ->select('*')
            ->where('ev.id_emergencia', '=', $hospitalizacion->id_ext_origen)
            ->orderBy('ev.id', 'desc')
            ->take(1)
            ->get();
          if ($evaluacion) {
            $signos_vitales = [
              'presion_arterial_sistolica' => $evaluacion[0]->presion_arterial_x,
              'presion_arterial_diastolica' => $evaluacion[0]->presion_arterial_y,
              'tamano_cintura_cm' => $evaluacion[0]->tamano_cintura_cm,
              'frecuencia_cardiaca' => $evaluacion[0]->frecuencia_cardiaca,
              'frecuencia_cardiaca_fetal' => $evaluacion[0]->frecuencia_cardiaca_fetal,
              'frecuencia_respiratoria' => $evaluacion[0]->frecuencia_respiratoria,
              'fiO2' => $evaluacion[0]->fi02,
              'sa02' => $evaluacion[0]->sa02_porcentaje,
              'temperatura' => $evaluacion[0]->temperatura,
              'talla' => $evaluacion[0]->talla_cm,
              'peso' => $evaluacion[0]->peso_kg,
              'imc' => 0,
              'peso_ideal' => 0,
              'observacion_triaje' => '',
            ];
          } else {
            $signos_vitales = [
              'presion_arterial_sistolica' => $emergencia->presion_arterial_x,
              'presion_arterial_diastolica' => $emergencia->presion_arterial_y,
              'tamano_cintura_cm' => $emergencia->tamano_cintura_cm,
              'frecuencia_cardiaca' => $emergencia->frecuencia_cardiaca,
              'frecuencia_cardiaca_fetal' => $emergencia->frecuencia_cardiaca_fetal,
              'frecuencia_respiratoria' => $emergencia->frecuencia_respiratoria,
              'fiO2' => $emergencia->fi02,
              'sa02' => $emergencia->sa02_porcentaje,
              'temperatura' => $emergencia->temperatura,
              'talla' => $emergencia->talla_cm,
              'peso' => $emergencia->peso_kg,
              'imc' => 0,
              'peso_ideal' => 0,
              'observacion_triaje' => $emergencia->observacion_triaje,
            ];
          }
        endif;*/

        // $upss_encargado = Upss::where('id_consultorio', $hospitalizacion->id_servicio_encargado)
        //                     ->where('id_tipo_atencion', 3)
        //                     ->where('estado', 1)
        //                     ->first();

        $upss_encargado = DB::table('upsses as u')
                          ->join('consultorios as c', 'c.id', '=', 'u.id_consultorio')
                          ->select('u.*', 'c.nombre as nombre_consultorio')
                          ->where('u.id_consultorio', $hospitalizacion->id_servicio_encargado)
                          ->where('u.id', $hospitalizacion->id_upss_encargado)
                          ->where('u.id_tipo_atencion', 3)
                          ->where('u.estado', 1)
                          ->first();

        $regularizar = ($upss_encargado == null) ? "1" : "0";

        $model_consultorio = new App\Models\Consultorio;
        if ($hospitalizacion->id_servicio_destino != null) {
          $servicio_ubicacion = $model_consultorio->GetNombreById($hospitalizacion->id_servicio_destino);
        } else if ($hospitalizacion->id_cama_estado != null) {
          $model_cama = new App\Models\Hospitalizacione;
          $data_cama = $model_cama->GetObtenerCama($hospitalizacion->id_cama_estado);
          $servicio_ubicacion = $model_consultorio->GetNombreById($data_cama->id_consultorio);
        }
        
        $upss_destino = Upss::where('id_consultorio', $hospitalizacion->id_servicio_destino)
                          ->where('id_tipo_atencion', 3)
                          ->where('estado', 1)
                          ->first();
        //$r_upss = $model_upss->upss_by_id($hospitalizacion->);
        // $codigo_upss = $upss->codigo;

        $flag_no_encontrado = 0;  
        $model_asegurado= new Asegurado();
        if($cita->id_asegurado_titular!=null || $cita->id_asegurado_titular>0):
          $dato_titular = $model_asegurado->Busca_datos_titular($cita->id_asegurado_titular);
        else:
          $flag_no_encontrado=1;
        endif;

        // $date = Carbon::now();
        // $hora = $date->toTimeString();

        if ($hora > '01:30' & $hora < '07:31'):
          /*echo*/$turno = 'MD';
        else:
          if ($hora > '07:30' & $hora < '13:31'):
            /*echo*/$turno = 'M';
          else:
            if ($hora > '13:30' & $hora < '19:31'):
              /*echo*/$turno = 'T';
            else:
              if ($hora > '19:30' & $hora <= '23:59'):
                /*echo*/$turno = 'N';
              else:
                if ($hora >= '00:00' & $hora <= '01:31'):
                  /*echo*/$turno = 'N';
                endif;
              endif;
            endif;
          endif;
        endif;

        $tipo_doc_nati = tipoDocNati::all();

        $tipo_partos = DB::select("SELECT * FROM tipo_partos tp ");

        $gestacionModel = new Gestacion;
        $lastGestacion = null;
        $lastGestacionArray = $gestacionModel->getLastByAseguradoDNI($cita->nro_doc_ident);
        if (isset($lastGestacionArray) && count($lastGestacionArray) > 0) {
          $lastGestacion = $lastGestacionArray[0];
        }

        $model_signos = new \App\Models\signosVitales();
        $signo_vital = $model_signos->GetUltimoRegistroSignoVital(3, $id_hospitalizacion);
        $signos_vitales = null;
        if ($signo_vital != null) {
          $signos_vitales = $model_signos->GetSignosVitalesDetalleById($signo_vital->id);
        }

        $model_hospitalizacion_atencion = new \App\Models\HospitalizacionAtencione;
        $h_antecedente = $model_hospitalizacion_atencion->GetByIdAsegurado($hospitalizacion->id_asegurado);

        $dx_admision = $model_hospitalizacion->GetDiagnosticosAdmisionById($id_hospitalizacion);

        $model_intraoperatorio = new \App\Models\IntraOperatorio();
        $ultima_atencion = $model_intraoperatorio->getUltimaAtencionXAtencion($id_hospitalizacion, 3);

        $dx_traslado = null;
        if(is_object($ultima_atencion)):
          if ($hospitalizacion->estado == 5) {
            // Diagnosticos de Traslado
            $dx_traslado = $model_hospitalizacion->GetDiagnosticosTraslado($ultima_atencion->id_prestacion, $ultima_atencion->id_evolucion);
          }
        endif;

        $upsses = "SELECT DISTINCT up.id AS id_upss, up.descripcion_upss, up.codigo AS codigo_upss, co.estado 
        FROM upsses AS up
        INNER JOIN consultorios AS co ON co.id = up.id_consultorio
        WHERE (up.id_consultorio = 114 OR (up.estado = 1 AND up.id_tipo_atencion = 3))
        ORDER BY up.descripcion_upss;";
        /* $upsses = "SELECT DISTINCT up.id id_upss, up.descripcion_upss, up.codigo codigo_upss, co.estado 
          FROM upsses as up
          INNER JOIN consultorios as co ON co.id = up.id_consultorio
          WHERE up.estado = 1 
          And up.id_tipo_atencion = 3
          ORDER BY up.descripcion_upss;"; */
        $upsses= DB::select($upsses);

        $medicoservicio = $model_consultorio->MedicoDetalleServicio($id_ipress,$medico->id);

        $upss_cargo = $upss_encargado->nombre_consultorio;
        $servicio_medico = $medicoservicio->servicio;

        $servicios = explode('#|#', $servicio_medico);
        $servicios = array_map('trim', $servicios);
        
        if (!in_array($upss_cargo, $servicios) && !session()->pull('mostrar_error')) {
            session()->put('mostrar_error', true); 
            return redirect()->to('/form_atenciones_hospitalizacion/' . $id_hospitalizacion)
                ->with('mensaje_error', 'EL UPSS A CARGO DEL PACIENTE NO COINCIDE CON SU SERVICIO. BAJO SU RESPONSABILIDAD');
        }

        $is_profesion_medico = false;
        if (count($medico) > 0) {
          switch ($medico->id_profesion) {
            case 20: case 4: case 51: case 52: case 1: $is_profesion_medico = true; break;
          }
          if (!$is_profesion_medico && (intval($nivel) == 1 || intval($nivel) == 2) && in_array(intval($medico->id_profesion), [12, 2, 5])) {
            $is_profesion_medico = true;
          }
        }

      $client = new SoapClient(env('WI_SALUDPOL_URL') . "/ws/WS_AfiliadoSP_Serv.php?wsdl", array('login' => env('WI_SALUDPOL_USERNAME'), 'password' => env('WI_SALUDPOL_PASSWORD')));

	    if($cita->tipo_doc_ident=='DNI'){ 
	        $tipo_doc_ident = '1';
	    }else if($cita->tipo_doc_ident=='CE'){ 
	        $tipo_doc_ident = '2';
	    }else if($cita->tipo_doc_ident=='PAS'){ 
	        $tipo_doc_ident = '3';
	    }else if($cita->tipo_doc_ident=='DIE'){ 
	        $tipo_doc_ident = '4';
	    }

	    $beneficiario = $client->getAseguradoValidate($tipo_doc_ident, $cita->nro_doc_ident);

	    if (is_object($beneficiario)) :
	      $flag = 0 ;
	      $errorWS = 1 ;
	    else:
	      $flag = 1 ;
	      $errorWS = 0 ;
	    endif;
      $id_principal=$id_hospitalizacion;

	    if ($errorWS > 0) {
	        if (is_object($hospitalizacion) && $hospitalizacion->esFlujoDosVistas()){
          return view('frontend.hospitalizacion.form_atencion_hospitalizacion_cu',compact('camas','is_profesion_medico','fechaServidor','cita','establecimiento_servicios','farmacias','nivel',
            'id_cita','id_tipo_atencione','upss_encargado','servicio_ubicacion','flag','hora','turno','hospitalizacion','dato_titular','flag_no_encontrado','cuartos','id_cuarto_actual','tipo_doc_nati',
            'cama', 'dx_admision', 'dx_traslado', 'upsses', 'regularizar','upss_cargo','servicio_medico','id_principal'));
        }else{
          return view('frontend.hospitalizacion.form_atencion_hospitalizacion2',compact('signos_vitales','camas','is_profesion_medico','aEscala','fechaServidor','cita','establecimiento_servicios','tipo_atencion','farmacias','nivel','medico','id_servicio',
            'id_cita','id_tipo_atencione','establecimientos','upss_encargado','servicio_ubicacion','modalidad','flag','hora','turno','hospitalizacion','dato_titular','flag_no_encontrado','cuartos','id_cuarto_actual','tipo_doc_nati','tipo_partos','lastGestacion',
            'signo_vital', 'h_antecedente', 'cama', 'dx_admision', 'dx_traslado', 'upsses', 'regularizar','upss_cargo','servicio_medico','id_principal'));
        }
	    }
	    else{
	    	return redirect('/all_hospitalizado_by_establecimiento')->withInput()->withErrors(array('El paciente ya no esta asegurado, por consiguiente no tiene derecho de atención, realizar los tramites correspondiente con SALUDPOL..!'));
	    }
      else:
        return redirect('/all_hospitalizado_by_establecimiento/')->withInput()->withErrors(array('Medico no encontrado ..!'));
      endif;
    else:
      return redirect('/all_hospitalizado_by_establecimiento/')->withInput()->withErrors(array('Elemento no encontrado ..!'));
    endif;
  }

  public function registrar_prestacion_transaction_hospitalizacion(Request $request)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $datos=false;
    $id_prestacion=0;
    $mensaje_error = "";
    DB::beginTransaction();

    try {
      
      $search = array('+', ',','"','/', '{', '}');
      $replace = array('\+', '\,', '\"', '\/', '\{', '\}');

      /////////////////////////////////////////////////////////////////////////////////////
      $modo_hc_cu = null;
      if ($request->id_establecimiento == 76 && $request->id_servicio_encargado == 80 && !empty($request->id_principal)) {
        $hosp_flags_cu = DB::table('hospitalizaciones')->where('id', $request->id_principal)->first(['flag_exa_cu', 'flag_hc_cu', 'id_user_exa_cu']);
        if ($hosp_flags_cu && $hosp_flags_cu->flag_hc_cu == 1) {
          DB::rollBack();
          $id_prestacion_previa = \App\Models\Prestaciones::where('id_ext', $request->id_principal)->where('id_tipo_atencion', 3)->where('id_estado_reg', 1)->orderBy('id', 'DESC')->value('id');
          Session::flash('flash_message', 'Esta atención ya fue completada anteriormente.');
          return redirect('/ver_prestacion_hospitalizacion/' . $request->id_user . '/' . $id_prestacion_previa);
        } elseif ($hosp_flags_cu && $hosp_flags_cu->flag_exa_cu == 1) {
          if ((int) $hosp_flags_cu->id_user_exa_cu !== (int) Auth::user()->id) {
            DB::rollBack();
            Session::flash('flash_message', 'Esta atención corresponde a otro usuario. No tiene autorización para continuarla.');
            return redirect('/all_hospitalizado_by_establecimiento/')->withErrors(array('Esta atención corresponde a otro usuario. No tiene autorización para continuarla.'));
          }
          $modo_hc_cu = 'continuar';
        } else {
          $modo_hc_cu = 'crear';
        }
      }

      $model_prestacion = new \App\Models\Prestaciones;

      $p_diagnostico = "";
      $p_diagnostico .= "{";
      if (isset($request->id_diagnostico)) :
        foreach ($request->id_diagnostico as $key => $value) :
          if ($request->id_diagnostico[$key] != "") {
            $p_diagnostico .= "{";
            $p_diagnostico .= $request->id_diagnostico[$key] . ",";
            $p_diagnostico .= (isset($request->id_tipo_diagnostico[$key]) && $request->id_tipo_diagnostico[$key] > 0) ? $request->id_tipo_diagnostico[$key] : "0";
            $p_diagnostico .= ",";
            $p_diagnostico .= $request->id_categoria_diagnostico[$key] . ", 1";
            $p_diagnostico .= "},";
          }
        endforeach;
        if (strlen($p_diagnostico) > 1) $p_diagnostico = substr($p_diagnostico, 0, -1);
      endif;
      $p_diagnostico .= "}";

      $p_procedimiento = "";
      $p_procedimiento .= "{";
      if (isset($request->id_procedimiento)) :
        foreach ($request->id_procedimiento as $key => $value) :
          if ($request->id_procedimiento[$key] != "") {
            $p_procedimiento .= "{";
            $p_procedimiento .= $request->id_procedimiento[$key] . ",";
            $p_procedimiento .= (isset($request->procedimiento_cantidad[$key]) && $request->procedimiento_cantidad[$key] > 0) ? $request->procedimiento_cantidad[$key] : "0";
            $p_procedimiento .= "," . $request->miSelect[$key];
            $p_procedimiento .= "},";
          }
        endforeach;
        if (strlen($p_procedimiento) > 1) $p_procedimiento = substr($p_procedimiento, 0, -1);
      endif;
      $p_procedimiento .= "}";
      
      //$id_prestacion = 0;
      $fecha_alta = "";
      $id_motivo_alta = 0;
      $id_estado_reg = 1;
      $observacion = "";
      $id_receta_vale = 0;
      $id_anestesiologo = 0;
      $obs_registro = "";
      $id_receta_vale_bus = 0;
      $id_admision = 0;
      $tipo_via = "";
      $sub_via = "";
      $final_via = "";
      $nro_guia = "";
      $interconsulta = "";
      $interconsulta_dias = 0;
      $nro_receta = "";
      $digitos_adicionales = "";
      $id_tipo_num = 0;
      $id_servicio_derivar = (isset($request->id_servicio_derivar)) ? $request->id_servicio_derivar : 0;
      $fecha_expedicion =  (isset($request->fecha_expedicion)) ? $request->fecha_expedicion : "";
      $anamnesis = (isset($request->anamnesis)) ? $request->anamnesis : "";
      $examen_fisico = (isset($request->examen_fisico)) ? $request->examen_fisico : "";
      $aspectos_generales = (isset($request->aspectos_generales)) ? $request->aspectos_generales : "";
      $estado_receta = 2;
      $condicion = (isset($request->condicion)) ? $request->condicion : 0;
      $cantidadReceta = (isset($request->cantidadReceta) && $request->cantidadReceta > 0) ? $request->cantidadReceta : 1;
      $id_asegurado   = $request->id_asegurado;
      $receta_prescrita        =  0;
      $request->id_modalidad = 1;
      if($request->id_tipo_atencion==3){
        $id_emergencia = 0;
        $id_hospitalizacion = $request->id_cita;
        $id_cita= 0;
      }
      /*else
      {
        $id_hospitalizacion = $request->id_cita;
        $id_cita= 0; 
        $id_emergencia = 0;
      }*/

      $p_producto = "";
      $p_producto .= "{";
      if (isset($request->id_producto)) :
        $receta_prescrita=1;
        foreach ($request->id_producto as $key => $value) :
          if ($request->id_producto[$key] != "") {
            $p_producto .= "{";
            $p_producto .= $request->id_producto[$key] . ",";
            $p_producto .= $request->tiempo_periodo[$key] . ",";
            $p_producto .= $request->nro_med_solictados[$key] . ",";
            $p_producto .= (isset($request->nro_med_entregados[$key]) && $request->nro_med_entregados[$key] != "") ? $request->nro_med_entregados[$key] : 0;
            $p_producto .= ",";
            $p_producto .= (isset($request->nro_stocks_almacen[$key]) && $request->nro_stocks_almacen[$key] != "") ? $request->nro_stocks_almacen[$key] : 0;
            $p_producto .= ",";
            $p_producto .= (isset($request->nro_stocks_establecimiento[$key]) && $request->nro_stocks_establecimiento[$key] != "") ? $request->nro_stocks_establecimiento[$key] : 0;
            $p_producto .= ",";
            $p_producto .= (isset($request->nro_stocks[$key]) && $request->nro_stocks[$key] != "") ? $request->nro_stocks[$key] : 0;
            $p_producto .= ",";
            $p_producto .= (isset($request->posologia[$key]) && $request->posologia[$key] != "") ? str_replace(",", "###", $request->posologia[$key]) : "-";
            $p_producto .= ",";
            $p_producto .= (isset($request->miSelectProd[$key]) && $request->miSelectProd[$key] != "") ? $request->miSelectProd[$key] : 0;
            $p_producto .= ",";
            $p_producto .= $request->id_via_administracion[$key];
            $p_producto .= "},";
          }
        endforeach;
        if (strlen($p_producto) > 1) $p_producto = substr($p_producto, 0, -1);
      endif;
      $p_producto .= "}";

      $ipress_bene    = $request->ipress_bene;
      $upss_bene        = $request->upss_bene;
      $id_farmacia_receta        =  ($request->id_farmacia_receta!="") ? $request->id_farmacia_receta : 0;
      if (Auth::user()->tipo_registro == 3) $id_receta_vale_bus = 1;

      $id_sub_consultorio=53;
      $model_cita = new \App\Models\Cita;
      $model_kardex = new Kardexe();
      $id_cita = 0;
      $fecha_cita = $model_kardex->getFechaServidor();
      $correo = "";$domicilio = "";$telefono = ""; $telefono2 = ""; $tipo_cita = 1; $adicional = 1 ;
      $registrar_cita = 0;

    if ($modo_hc_cu === 'continuar') {
      $prestacion_existente_cu = \App\Models\Prestaciones::where('id_ext', $id_hospitalizacion)->where('id_tipo_atencion', 3)->where('id_estado_reg', 1)->orderBy('id', 'DESC')->first();
      if (!$prestacion_existente_cu) {
        DB::rollBack();
        Session::flash('flash_message', 'No se encontró la atención de exámenes previa para continuar el registro de la historia clínica.');
        return redirect('/form_atenciones_hospitalizacion/' . $id_hospitalizacion . '/' . $request->id_servicio_encargado);
      }
      $id_cita = $prestacion_existente_cu->id_cita;
      $id_asegurado = $prestacion_existente_cu->id_asegurado;
    } else {
      //dd($request);
      $id_cita = $model_cita->registrarCita($id_cita, $request->id_establecimiento, $id_sub_consultorio, $request->id_medico, $request->id_user, $fecha_cita, $request->tipodoc_beneficiario, $request->nrodocafiliado, $request->nombre_beneficiario, $request->paterno_beneficiario, $request->materno_beneficiario, $request->tipo_beneficiario, $request->nro_historia, $request->grado, $request->fecnacafiliado, $request->nomsexo, $request->cip_beneficiario, $request->tipodoc_titular, $request->nrodoctitular, $request->nombre_titular, $request->paterno_titular, $request->materno_titular, NULL, 1, 1, $correo,$domicilio,$telefono,$telefono2,$tipo_cita,2,'NO',0,NULL,NULL,'','','','','','','');
      $cita = $model_cita->GetCitasByIdCita($id_cita);
      $id_asegurado = $cita->id_asegurado;

      //VALIDA SI EXISTE UNA ATENCION MEDICA CON DATOS CITA Y PACIENTE, DE LO CONTRARIO MOSTRARA UN MENSAJE AL USUARIO
      $paciente = Asegurado::find($id_asegurado);
      $paciente_nombres = $paciente->nombre.' '.$paciente->paterno.' '.$paciente->materno;
      $prestaciones = Prestaciones::where('id_cita',$id_cita)->where('id_asegurado',$id_asegurado)->where('id_estado_reg',1)->exists();
      if($prestaciones){
        Session::flash('flash_message', 'No se pudo registrar la Prestación, ya existe una una atención médica con cita Nº '.$id_cita.' para el paciente '.$paciente_nombres.' , vuelva a intentarlo nuevamente');
        return redirect('/form_atenciones_hospitalizacion/'. $id_hospitalizacion);
      }
      //SOLO SE DEBE REGISTRAR LA ATENCION MEDICA SI NO EXISTE EL ID_CITA Y ID_ASEGURADO
    }
      $id_servicio_cama_actual_regularizar = $request->id_servicio_actual_paciente;$fecha_hora_atencion = date('Y-m-d H:i:s', strtotime("$request->fecha_atencion $request->hora_atencion"));

      if ($request->select_cambiar_cama == "0") { // Permanece en la misma cama
        $fecha_traslado_cama = null;
        $hora_traslado_cama = null;
        $id_servicio_cama_nueva = $request->id_servicio_actual_paciente;
        $id_cama_traslado = $request->id_cama_actual_paciente;
        $observacion_alta_servicio = null;
      } else if ($request->select_cambiar_cama == "1") { // Rotacion
        $fecha_traslado_cama = $request->fecha_inicio;
        $hora_traslado_cama = $request->appt;
        $id_servicio_cama_nueva = $request->id_area;
        $id_cama_traslado = $request->id_cama;
        $observacion_alta_servicio = null;
      }

      // Cuenta con Alta de Servicio
      if ($request->flag_alta_servicio == "1") {
          $id_cama_traslado = $request->id_cama_destino;
          $fecha_traslado_cama = $request->fecha_egreso;
          $hora_traslado_cama = $request->hora_egreso;
          $observacion_alta_servicio = $request->observacion_alta_servicio;
      }

      $id_upss_encargado = null;
      $id_servicio_encargado = null;
      $regularizar_datos_encargado = $request->regularizar_datos;
      $codigo_upss_encargado = null;

      if ($regularizar_datos_encargado == "1") {
        $id_upss_encargado = ($request->id_upss_encargado_regularizacion != "0" ? $request->id_upss_encargado_regularizacion : null);
        $id_servicio_encargado = ($request->id_servicio_encargado_regularizacion != "0" ? $request->id_servicio_encargado_regularizacion : null);

        $model_consultorio = new App\Models\Consultorio;
        $data_upss = $model_consultorio->ShowCodigoUpssId($id_upss_encargado);
        $codigo_upss_encargado = $data_upss->codigo;
      } else {
        $id_upss_encargado = $request->id_upss_encargado;
        $id_servicio_encargado = $request->id_servicio_encargado;
        $codigo_upss_encargado = $request->codigo_upss;
      }

      $fecha_hora_atencion = date('Y-m-d H:i:s', strtotime("$request->fecha_atencion $request->hora_atencion"));

      if ($modo_hc_cu === 'continuar') {
        $id_prestacion = $prestacion_existente_cu->id;
      } else {
        $id_prestacion = $model_prestacion->registrarPrestacionHospitalizacion($id_cita, $id_asegurado, $request->id_tipo_atencion, $request->id_establecimiento, $id_sub_consultorio, $fecha_hora_atencion, $fecha_alta, $request->id_medico, $id_motivo_alta, $id_estado_reg, $observacion, $id_receta_vale, $id_farmacia_receta, $request->id_user, $nro_receta, $digitos_adicionales, $id_anestesiologo, $obs_registro, $id_receta_vale_bus, $id_admision, $tipo_via, $sub_via, $final_via, $nro_guia, $interconsulta, $interconsulta_dias, $fecha_expedicion, $id_servicio_derivar, $id_tipo_num, $anamnesis, $examen_fisico, $aspectos_generales, $estado_receta, $condicion, $cantidadReceta, $p_procedimiento, $p_producto, $p_diagnostico, $ipress_bene, $upss_bene,$receta_prescrita, $request->id_modalidad, $request->nomsexo, $request->fecnacafiliado, $codigo_upss_encargado,$adicional,$registrar_cita,$id_hospitalizacion,$fecha_traslado_cama,$hora_traslado_cama,$id_cama_traslado,$request->flag_alta_servicio,$observacion_alta_servicio,$id_servicio_cama_nueva,$id_upss_encargado,$id_servicio_encargado,$request->select_cambiar_cama,$regularizar_datos_encargado, $id_servicio_cama_actual_regularizar);
      }
      $enfermeria = Enfermeria::where("id_ext_enfermeria", "=", $id_hospitalizacion)->where("id_tipo_atencion", "=", 3)->first();
      if ($enfermeria) {
        $enfermeria->id_prestacion = $id_prestacion;
        if (!$enfermeria->save()) {
          Log::error("No se ha podido actualizar el registro de enfermeria con la prestacion.");
          throw new \Exception("No se ha podido actualizar el registro de enfermeria con la prestacion.");
        }
      }
      /*****************************Otros datos**************************************************************/
      $temperatura                = $request->temperatura != '' ? $request->temperatura : 0;
      $sa02_porcentaje            = $request->saturacion != '' ? $request->saturacion : 0;
      $presion_arterial_x         = $request->presion_arterial_x != '' ? $request->presion_arterial_x : 0;
      $presion_arterial_y        = $request->presion_arterial_y != '' ? $request->presion_arterial_y : 0;
      $talla_cm                   = $request->talla != '' ? $request->talla : 0;
      $tamano_cintura_cm          = $request->tamanio_cintura != '' ? $request->tamanio_cintura : 0;
      $frecuencia_respiratoria    = $request->frecuencia_respiratoria != '' ? $request->frecuencia_respiratoria: 0;
      $peso_kg                    = $request->peso != '' ? $request->peso: 0;
      $frecuencia_cardiaca_fetal  = $request->frec_cardiaca_fetal != '' ? $request->frec_cardiaca_fetal : 0;
      $frecuencia_cardiaca        = $request->frec_cardiaca != '' ? $request->frec_cardiaca : 0;
      $fi02                       = $request->oxigenacion != '' ? $request->oxigenacion : 0;
      
      $model_prestacion->adicionarDatosPrestacion2($id_prestacion, $temperatura, $sa02_porcentaje, $presion_arterial_x, $presion_arterial_y, $talla_cm, $tamano_cintura_cm, $frecuencia_respiratoria, $peso_kg, $frecuencia_cardiaca_fetal, $frecuencia_cardiaca, $fi02, null, $request->tiempo_enfermedad, null, null, null, null,null,$request->antecedente_familiar,null,$request->indicacion_terapeutica,null,$request->procedimiento_medico_quirurgico,null);

      $model_h_atencione = \App\Models\HospitalizacionAtencione::firstOrNew(['id_prestacion' => $id_prestacion]);
      $model_h_atencione->id_prestacion = $id_prestacion;
      $model_h_atencione->id_ext = $id_hospitalizacion;
      $model_h_atencione->estado_general = $request->estado_general;
      $model_h_atencione->estado_hidratacion = $request->estado_hidratacion;
      $model_h_atencione->estado_nutricion = $request->estado_nutricion;

      $model_h_atencione->tiempo_enfermedad = $request->tiempo_enfermedad;
      $model_h_atencione->tiempo_enfermedad_valor  = $request->tiempo_enfermedad_text;
      $model_h_atencione->tiempo_enfermedad_unidad  = $request->periodo_enfermedad;
      $model_h_atencione->forma_inicio = $request->forma_inicio;
      $model_h_atencione->curso = $request->curso;
      $model_h_atencione->sintomas_signos_principal = $request->sintomas_signos_principal;
      $model_h_atencione->relato = $request->relato;
      $model_h_atencione->tratamiento_recibido_antes = $request->tratamiento_recibido_antes;
      
      $model_h_atencione->fn_bio_1 = $request->fn_bio_1;
      $model_h_atencione->fn_bio_2 = $request->fn_bio_2;
      $model_h_atencione->fn_bio_3 = $request->fn_bio_3;
      $model_h_atencione->fn_bio_4 = $request->fn_bio_4;
      $model_h_atencione->fn_bio_5 = $request->fn_bio_5;
      $model_h_atencione->fn_bio_6 = $request->fn_bio_6;

      $model_h_atencione->antc_1 = $request->antc_1;
      $model_h_atencione->antc_2 = $request->antc_2;
      $model_h_atencione->antc_3 = $request->antc_3;
      $model_h_atencione->antc_4 = $request->antc_4;
      $model_h_atencione->antc_5 = $request->antc_5;
      $model_h_atencione->antc_6 = $request->antc_6;
      $model_h_atencione->antc_7 = $request->antc_7;
      $model_h_atencione->antc_8 = $request->antc_8;
      $model_h_atencione->antc_9 = $request->antc_9;
      $model_h_atencione->antc_10 = $request->antc_10;
      $model_h_atencione->antc_11 = $request->antc_11;
      $model_h_atencione->antc_12 = $request->antc_12;
      $model_h_atencione->antc_13 = $request->antc_13;
      $model_h_atencione->antc_14 = $request->antc_14;
      $model_h_atencione->antc_15 = $request->antc_15;
      // $model_h_atencione->antc_16 = $request->antc_16;
      $model_h_atencione->antc_17 = $request->antc_17;
      $model_h_atencione->antc_18 = $request->antc_18;
      // $model_h_atencione->antc_19 = $request->antc_19;
      $model_h_atencione->antc_20 = $request->antc_20;
      $model_h_atencione->antc_21 = $request->antc_21;
      $model_h_atencione->antc_22 = $request->antc_22;
      $model_h_atencione->antc_23 = $request->antc_23;
      $model_h_atencione->antc_24 = $request->antc_24;
      $model_h_atencione->antc_25 = $request->antc_25;
      $model_h_atencione->antc_26 = $request->antc_26;
      $model_h_atencione->antc_27 = $request->antc_27;
      $model_h_atencione->antc_28 = $request->antc_28;
      $model_h_atencione->antc_29 = $request->antc_29;
      $model_h_atencione->antc_30 = $request->antc_30;
      $model_h_atencione->antc_31 = $request->antc_31;
      $model_h_atencione->antc_32 = $request->antc_32;
      $model_h_atencione->antc_33 = $request->antc_33;

      $model_h_atencione->exam_gen_1 = $request->exam_gen_1;
      $model_h_atencione->exam_gen_2 = $request->exam_gen_2;
      $model_h_atencione->exam_gen_3 = $request->exam_gen_3;
      $model_h_atencione->exam_gen_4 = $request->exam_gen_4;
      $model_h_atencione->exam_gen_5 = $request->exam_gen_5;
      $model_h_atencione->exam_gen_6 = $request->exam_gen_6;
      $model_h_atencione->exam_gen_7 = $request->exam_gen_7;
      $model_h_atencione->exam_gen_8 = $request->exam_gen_8;
      $model_h_atencione->exam_gen_9 = $request->exam_gen_9;

      $model_h_atencione->exam_reg_1 = $request->exam_reg_1;
      $model_h_atencione->exam_reg_2 = $request->exam_reg_2;
      $model_h_atencione->exam_reg_3 = $request->exam_reg_3;
      $model_h_atencione->exam_reg_4 = $request->exam_reg_4;
      $model_h_atencione->exam_reg_5 = $request->exam_reg_5;
      $model_h_atencione->exam_reg_6 = $request->exam_reg_6;
      $model_h_atencione->exam_reg_7 = $request->exam_reg_7;
      $model_h_atencione->exam_reg_8 = $request->exam_reg_8;
      $model_h_atencione->exam_reg_9 = $request->exam_reg_9;
      $model_h_atencione->exam_reg_10 = $request->exam_reg_10;
      $model_h_atencione->exam_reg_11 = $request->exam_reg_11;
      $model_h_atencione->exam_reg_12 = $request->exam_reg_12;
      $model_h_atencione->exam_reg_13 = $request->exam_reg_13;
      $model_h_atencione->exam_reg_14 = $request->exam_reg_14;
      $model_h_atencione->exam_reg_15 = $request->exam_reg_15;
      $model_h_atencione->exam_reg_16 = $request->exam_reg_16;
      $model_h_atencione->exam_reg_17 = $request->exam_reg_17;
      $model_h_atencione->exam_reg_18 = $request->exam_reg_18;
      $model_h_atencione->exam_reg_19 = $request->exam_reg_19;
      $model_h_atencione->exam_reg_20 = $request->exam_reg_20;
      $model_h_atencione->exam_reg_21 = $request->exam_reg_21;
      $model_h_atencione->exam_reg_22 = $request->exam_reg_22;
      $model_h_atencione->exam_reg_23 = $request->exam_reg_23;
      $model_h_atencione->exam_reg_24 = $request->exam_reg_24;
      $model_h_atencione->exam_reg_25 = $request->exam_reg_25;
      $model_h_atencione->exam_reg_26 = $request->exam_reg_26;
      $model_h_atencione->exam_reg_27 = $request->exam_reg_27;

      $model_h_atencione->dieta_terapeutica = $request->dieta_terapeutica;
      $model_h_atencione->complementario_terapeutica = $request->complementario_terapeutica;
      $model_h_atencione->indicacion_terapeutica = $request->indicacion_terapeutica;
      if($request->indicacion_terapeutica!='' || $request->indicacion_terapeutica!='' || $request->indicacion_terapeutica!=''):
        if($receta_prescrita==1):
          $model_h_atencione->hoja_terapeutica=1;
        endif;
      endif;
      
      $model_h_atencione->ampliacion_exam_fisico = $request->ampliacion_exam_fisico;
      //$model_h_atencione->created_at = Carbon::now();
      if ($model_h_atencione->exists) {
        $model_h_atencione->updated_us = Auth::user()->id;
      } else {
        $model_h_atencione->created_us = Auth::user()->id;
      } 

      $ruta = public_path("images/atenciones/");

      if ($request->hasFile('file_exam_gen_9')) {
        $imagen = $request->file('file_exam_gen_9');
        $nombreimagen = "1-".$id_prestacion.".".$imagen->guessExtension();
        $imagen->move($ruta,$nombreimagen);
        $model_h_atencione->ruta_img_exam_gen_9 = $nombreimagen;
      }
      if ($request->hasFile('file_cabeza_cuello')) {
        $imagen = $request->file('file_cabeza_cuello');
        $nombreimagen = "2-".$id_prestacion.".".$imagen->guessExtension();
        $imagen->move($ruta,$nombreimagen);
        $model_h_atencione->ruta_img_cabeza_cuello = $nombreimagen;
      }
      if ($request->hasFile('file_mamas')) {
        $imagen = $request->file('file_mamas');
        $nombreimagen = "3-".$id_prestacion.".".$imagen->guessExtension();
        $imagen->move($ruta,$nombreimagen);
        $model_h_atencione->ruta_img_mama = $nombreimagen;
      }
      if ($request->hasFile('file_exam_reg_10')) {
        $imagen = $request->file('file_exam_reg_10');
        $nombreimagen = "4-".$id_prestacion.".".$imagen->guessExtension();
        $imagen->move($ruta,$nombreimagen);
        $model_h_atencione->ruta_img_exam_reg_10 = $nombreimagen;
      }
      if ($request->hasFile('file_exam_reg_11')) {
        $imagen = $request->file('file_exam_reg_11');
        $nombreimagen = "5-".$id_prestacion.".".$imagen->guessExtension();
        $imagen->move($ruta,$nombreimagen);
        $model_h_atencione->ruta_img_exam_reg_11 = $nombreimagen;
      }
      if ($request->hasFile('file_exam_reg_12')) {
        $imagen = $request->file('file_exam_reg_12');
        $nombreimagen = "6-".$id_prestacion.".".$imagen->guessExtension();
        $imagen->move($ruta,$nombreimagen);
        $model_h_atencione->ruta_img_exam_reg_12 = $nombreimagen;
      }
      if ($request->hasFile('file_exam_reg_13')) {
        $imagen = $request->file('file_exam_reg_13');
        $nombreimagen = "7-".$id_prestacion.".".$imagen->guessExtension();
        $imagen->move($ruta,$nombreimagen);
        $model_h_atencione->ruta_img_exam_reg_13 = $nombreimagen;
      }
      if ($request->hasFile('file_neurologico')) {
        $imagen = $request->file('file_neurologico');
        $nombreimagen = "8-".$id_prestacion.".".$imagen->guessExtension();
        $imagen->move($ruta,$nombreimagen);
        $model_h_atencione->ruta_img_neurologico = $nombreimagen;
      }

      /********* GESTACION ******************/
      if (isset($request->frmGestacionActive) && strval($request->frmGestacionActive) === "true") {
        $fech_ult_regla = Carbon::createFromFormat('d/m/Y', $request->fecha_ultima_mestruacion)->format('Y-m-d');
        $fech_prob_parto = Carbon::createFromFormat('d/m/Y', $request->fecha_probable_parto)->format('Y-m-d');
        $duda = intval($request->options_duda) === 1 ? true : false;
        $newGestacion = Gestacion::create([
          "duda" => $duda,
          "fech_ult_regla" => $fech_ult_regla,
          "fech_prob_parto" => $fech_prob_parto,
          "semanas_gestacion" => $request->semanas_gestacion,
          "num_controles" => $request->num_controles_prenatal,
        ]);

        if ($newGestacion) {
          $model_h_atencione->id_gestacion = $newGestacion->id;
        } else {
          Log::error("No se ha podido registrar los datos de Gestacion.");
          throw new \Exception("No se ha podido registrar los datos de Gestación.");
        }
      }
      /********* END GESTACION **************/
      
      /********* PUERPERA **************/
      if (isset($request->frmPuerperaActive) && strval($request->frmPuerperaActive) === "true") {
        $fecha_parto = Carbon::createFromFormat('d/m/Y', $request->fecha_parto)->format('Y-m-d');
        $hora_parto = Carbon::createFromFormat('H:i', $request->hora_parto)->format('H:i:s');
        $complicaciones = intval($request->complicacion_parto) === 1 ? "SIN COMPLICACIONES" : "COMPLICADO";
        $newPuerpera = Puerperio::create([
          "fecha_parto" => $fecha_parto . " " . $hora_parto,
          "id_tipo_parto" => $request->tipo_parto,
          "complicaciones" => $complicaciones,
          "descripcion" => null,
          "created_at" => Carbon::now(),
          "created_us" => Auth::user()->id,
          "updated_at" => Carbon::now(),
          "updated_us" => Auth::user()->id,
        ]);

        if ($newPuerpera) {
          if (isset($request->numero_documento_puerpera) && is_array($request->numero_documento_puerpera) && count($request->numero_documento_puerpera) > 0) {
            for ($i=0; $i < count($request->numero_documento_puerpera); $i++) { 
              $newNeonatos = Nati::create([
                "id_puerperio" => $newPuerpera->id,
                "estado" => $request->estado_vida_puerpera[$i],
                "id_tipo_doc_nati" => $request->tipo_documento_puerpera[$i],
                "num_doc" => $request->numero_documento_puerpera[$i],
                "sexo" => $request->sexo_puerpera[$i],
                "talla" => $request->talla_puerpera[$i],
                "peso" => $request->peso_puerpera[$i],
                "created_at" => Carbon::now(),
                "created_us" => Auth::user()->id,
                "updated_at" => Carbon::now(),
                "updated_us" => Auth::user()->id,
              ]);

              if (!$newNeonatos) {
                Log::error("No se ha podido registrar los datos de Neonatos.");
                throw new \Exception("No se ha podido registrar los datos de Neonatos.");
              }
            }
          }

          $model_h_atencione->id_puerperio = $newPuerpera->id;
        } else {
          Log::error("No se ha podido registrar los datos de Puerpera.");
          throw new \Exception("No se ha podido registrar los datos de Puerpera.");
        }
      }
      /********* PUERPERA - END **************/

      if (!$model_h_atencione->save()) {
        Log::error("No se ha podido guardar los detalles de la Atención (hospitalizacion_atenciones).");
        throw new \Exception("No se ha podido guardar los detalles de la Atención (hospitalizacion_atenciones).");
      }

      /****************Actualiza Atencion Hospitalizacion*********************************************/

      // if($id_hospitalizacion!=0):
      //     $model_cama = new \App\Models\Cama;
      //     $model_hospitalizacion = new \App\Models\Hospitalizacione;
          //if($request->id_prioridad<4): //prioridad <3
            // $update=$model_cama->actualizaEstado($request->id_cama, 1, Auth::user()->id_establecimiento,"4");
            // $update=$model_hospitalizacion->ActualizaUbicacionEstado($id_hospitalizacion,$request->fecha_inicio,$request->appt,$request->id_cama);          
          //endif;
          // $updateid=$model_prestacion->AgregarIdExt($id_prestacion,$id_hospitalizacion);
      // endif;
      /*********************************************************************/
      $model_procedimiento = new \App\Models\Procedimiento;
      if (isset($request->id_examen_lab)) :
        $p_laboratorio = "";
        $p_laboratorio .= "{";
        foreach ($request->id_examen_lab as $key => $value) :
          if ($request->id_examen_lab[$key] != "") {
            
            $p_laboratorio .= "{";
            $p_laboratorio .= $request->id_examen_lab[$key] . ",";
            if (intval($request->id_nivel) === 3):
              $p_laboratorio .= "0";
            else:
            $p_laboratorio .= (isset($request->examen_lab_observacion[$key]) && $request->examen_lab_observacion[$key] !="") ? str_replace($search, $replace, $request->examen_lab_observacion[$key]) : "0";
            endif;
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->examen_lab_cantidad[$key]) && $request->examen_lab_cantidad[$key] > 0) ? $request->examen_lab_cantidad[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->miSelectLab[$key]) && $request->miSelectLab[$key] !="") ? $request->miSelectLab[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->descripcion_tipo_muestra[$key]) && $request->descripcion_tipo_muestra[$key] !="") ? $request->descripcion_tipo_muestra[$key] : "0";
            /** EXAMEN PARA HEMOCULTIVO PARA ADULTO */
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_fecha_tiempo_enfermedad[$key]) && $request->des_adulto_fecha_tiempo_enfermedad[$key] !="") ? $request->des_adulto_fecha_tiempo_enfermedad[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_respuesta_ocular[$key]) && $request->des_adulto_respuesta_ocular[$key] !="") ? $request->des_adulto_respuesta_ocular[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_respuesta_verbal[$key]) && $request->des_adulto_respuesta_verbal[$key] !="") ? $request->des_adulto_respuesta_verbal[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_respuesta_motora[$key]) && $request->des_adulto_respuesta_motora[$key] !="") ? $request->des_adulto_respuesta_motora[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_total_escala_glasgow[$key]) && $request->des_adulto_total_escala_glasgow[$key] !="") ? $request->des_adulto_total_escala_glasgow[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_presion_arterial_x[$key]) && $request->des_adulto_presion_arterial_x[$key] !="") ? $request->des_adulto_presion_arterial_x[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_presion_arterial_y[$key]) && $request->des_adulto_presion_arterial_y[$key] !="") ? $request->des_adulto_presion_arterial_y[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_frecuencia_cardiaca[$key]) && $request->des_adulto_frecuencia_cardiaca[$key] !="") ? $request->des_adulto_frecuencia_cardiaca[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_frecuencia_respiratorio[$key]) && $request->des_adulto_frecuencia_respiratorio[$key] !="") ? $request->des_adulto_frecuencia_respiratorio[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_temperatura[$key]) && $request->des_adulto_temperatura[$key] !="") ? $request->des_adulto_temperatura[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_creatinina[$key]) && $request->des_adulto_creatinina[$key] !="") ? $request->des_adulto_creatinina[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_diuresis[$key]) && $request->des_adulto_diuresis[$key] !="") ? $request->des_adulto_diuresis[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_procalcitonina[$key]) && $request->des_adulto_procalcitonina[$key] !="") ? $request->des_adulto_procalcitonina[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_pcr[$key]) && $request->des_adulto_pcr[$key] !="") ? $request->des_adulto_pcr[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_fiO2[$key]) && $request->des_adulto_fiO2[$key] !="") ? $request->des_adulto_fiO2[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_leucocitos[$key]) && $request->des_adulto_leucocitos[$key] !="") ? $request->des_adulto_leucocitos[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_plaquetas[$key]) && $request->des_adulto_plaquetas[$key] !="") ? $request->des_adulto_plaquetas[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_bilirrubina[$key]) && $request->des_adulto_bilirrubina[$key] !="") ? $request->des_adulto_bilirrubina[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_ima[$key]) && $request->des_adulto_ima[$key] !="") ? $request->des_adulto_ima[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_icc[$key]) && $request->des_adulto_icc[$key] !="") ? $request->des_adulto_icc[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_evp[$key]) && $request->des_adulto_evp[$key] !="") ? $request->des_adulto_evp[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_ecv[$key]) && $request->des_adulto_ecv[$key] !="") ? $request->des_adulto_ecv[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_demencia[$key]) && $request->des_adulto_demencia[$key] !="") ? $request->des_adulto_demencia[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_dm[$key]) && $request->des_adulto_dm[$key] !="") ? $request->des_adulto_dm[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_diabetes_lesion_organica[$key]) && $request->des_adulto_diabetes_lesion_organica[$key] !="") ? $request->des_adulto_diabetes_lesion_organica[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_hemiplejia[$key]) && $request->des_adulto_hemiplejia[$key] !="") ? $request->des_adulto_hemiplejia[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_epoc[$key]) && $request->des_adulto_epoc[$key] !="") ? $request->des_adulto_epoc[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_patologia_tejido_conectivo[$key]) && $request->des_adulto_patologia_tejido_conectivo[$key] !="") ? $request->des_adulto_patologia_tejido_conectivo[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_enfermedad_ulcerosa[$key]) && $request->des_adulto_enfermedad_ulcerosa[$key] !="") ? $request->des_adulto_enfermedad_ulcerosa[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_patologia_renal_moderada_o_grave[$key]) && $request->des_adulto_patologia_renal_moderada_o_grave[$key] !="") ? $request->des_adulto_patologia_renal_moderada_o_grave[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_neoplasias[$key]) && $request->des_adulto_neoplasias[$key] !="") ? $request->des_adulto_neoplasias[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_neoplasias_especifique[$key]) && $request->des_adulto_neoplasias_especifique[$key] !="") ? $request->des_adulto_neoplasias_especifique[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_leucemias[$key]) && $request->des_adulto_leucemias[$key] !="") ? $request->des_adulto_leucemias[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_leucemias_especifique[$key]) && $request->des_adulto_leucemias_especifique[$key] !="") ? $request->des_adulto_leucemias_especifique[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_linfomas_malignos[$key]) && $request->des_adulto_linfomas_malignos[$key] !="") ? $request->des_adulto_linfomas_malignos[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_metastasis_colida[$key]) && $request->des_adulto_metastasis_colida[$key] !="") ? $request->des_adulto_metastasis_colida[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_sida[$key]) && $request->des_adulto_sida[$key] !="") ? $request->des_adulto_sida[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_antecedentes_patologicos_otros_1[$key]) && $request->des_adulto_antecedentes_patologicos_otros_1[$key] !="") ? $request->des_adulto_antecedentes_patologicos_otros_1[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_antecedentes_patologicos_otros_1_especifique[$key]) && $request->des_adulto_antecedentes_patologicos_otros_1_especifique[$key] !="") ? $request->des_adulto_antecedentes_patologicos_otros_1_especifique[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_antecedentes_patologicos_otros_2[$key]) && $request->des_adulto_antecedentes_patologicos_otros_2[$key] !="") ? $request->des_adulto_antecedentes_patologicos_otros_2[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_antecedentes_patologicos_otros_2_especifique[$key]) && $request->des_adulto_antecedentes_patologicos_otros_2_especifique[$key] !="") ? $request->des_adulto_antecedentes_patologicos_otros_2_especifique[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_terapia_antimicrobiana[$key]) && $request->des_adulto_terapia_antimicrobiana[$key] !="") ? $request->des_adulto_terapia_antimicrobiana[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_frasco_1[$key]) && $request->des_adulto_frasco_1[$key] !="") ? $request->des_adulto_frasco_1[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_frasco_1_opcion_1[$key]) && $request->des_adulto_frasco_1_opcion_1[$key] !="") ? $request->des_adulto_frasco_1_opcion_1[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_frasco_1_opcion_2[$key]) && $request->des_adulto_frasco_1_opcion_2[$key] !="") ? $request->des_adulto_frasco_1_opcion_2[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_frasco_2[$key]) && $request->des_adulto_frasco_2[$key] !="") ? $request->des_adulto_frasco_2[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_frasco_2_opcion_1[$key]) && $request->des_adulto_frasco_2_opcion_1[$key] !="") ? $request->des_adulto_frasco_2_opcion_1[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_frasco_2_opcion_2[$key]) && $request->des_adulto_frasco_2_opcion_2[$key] !="") ? $request->des_adulto_frasco_2_opcion_2[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_frasco_3[$key]) && $request->des_adulto_frasco_3[$key] !="") ? $request->des_adulto_frasco_3[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_frasco_3_opcion_1[$key]) && $request->des_adulto_frasco_3_opcion_1[$key] !="") ? $request->des_adulto_frasco_3_opcion_1[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_frasco_3_opcion_2[$key]) && $request->des_adulto_frasco_3_opcion_2[$key] !="") ? $request->des_adulto_frasco_3_opcion_2[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_hemocultivo_seguimiento_y_vigilancia[$key]) && $request->des_adulto_hemocultivo_seguimiento_y_vigilancia[$key] !="") ? $request->des_adulto_hemocultivo_seguimiento_y_vigilancia[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_adulto_fecha_primer_resultado_hemocultivo[$key]) && $request->des_adulto_fecha_primer_resultado_hemocultivo[$key] !="") ? $request->des_adulto_fecha_primer_resultado_hemocultivo[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->flag_solicitud_examen_especializados_hemocultivo_adulto[$key]) && $request->flag_solicitud_examen_especializados_hemocultivo_adulto[$key] !="") ? $request->flag_solicitud_examen_especializados_hemocultivo_adulto[$key] : "0";
            /******************************************************* EXAMEN PARA HEMOCULTIVO PARA NEONATO Y PEDRIATRÍA *******************************************************/
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->descripcion_examen[$key]) && $request->descripcion_examen[$key] !="") ? $request->descripcion_examen[$key] : "0"; /** NOMBRE O DESCRIPCIÓN DEL EXAMEN ESPECIALIZADO */
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_fecha_tiempo_enfermedad[$key]) && $request->des_pedriatria_fecha_tiempo_enfermedad[$key] !="") ? $request->des_pedriatria_fecha_tiempo_enfermedad[$key] : "0";
            /** EDAD GESTIONAL **/
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_postermino[$key]) && $request->des_pedriatria_postermino[$key] !="") ? $request->des_pedriatria_postermino[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_termino[$key]) && $request->des_pedriatria_termino[$key] !="") ? $request->des_pedriatria_termino[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_prematuro_leve[$key]) && $request->des_pedriatria_prematuro_leve[$key] !="") ? $request->des_pedriatria_prematuro_leve[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_prematuro_moderado[$key]) && $request->des_pedriatria_prematuro_moderado[$key] !="") ? $request->des_pedriatria_prematuro_moderado[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_prematuro_extremo[$key]) && $request->des_pedriatria_prematuro_extremo[$key] !="") ? $request->des_pedriatria_prematuro_extremo[$key] : "0";
            /** APGAR **/
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_apariencia[$key]) && $request->des_pedriatria_apariencia[$key] !="") ? $request->des_pedriatria_apariencia[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_pulso[$key]) && $request->des_pedriatria_pulso[$key] !="") ? $request->des_pedriatria_pulso[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_gesto[$key]) && $request->des_pedriatria_gesto[$key] !="") ? $request->des_pedriatria_gesto[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_actividad_respiratoria[$key]) && $request->des_pedriatria_actividad_respiratoria[$key] !="") ? $request->des_pedriatria_actividad_respiratoria[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_total_apgar[$key]) && $request->des_pedriatria_total_apgar[$key] !="") ? $request->des_pedriatria_total_apgar[$key] : "0";
            /** ESCALA DE GLASGOW **/
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_respuesta_ocular[$key]) && $request->des_pedriatria_respuesta_ocular[$key] !="") ? $request->des_pedriatria_respuesta_ocular[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_respuesta_verbal[$key]) && $request->des_pedriatria_respuesta_verbal[$key] !="") ? $request->des_pedriatria_respuesta_verbal[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_respuesta_motora[$key]) && $request->des_pedriatria_respuesta_motora[$key] !="") ? $request->des_pedriatria_respuesta_motora[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_total_escala_glasgow[$key]) && $request->des_pedriatria_total_escala_glasgow[$key] !="") ? $request->des_pedriatria_total_escala_glasgow[$key] : "0";
            /** SIGNOS VITALES **/
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_presion_arterial_x[$key]) && $request->des_pedriatria_presion_arterial_x[$key] !="") ? $request->des_pedriatria_presion_arterial_x[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_presion_arterial_y[$key]) && $request->des_pedriatria_presion_arterial_y[$key] !="") ? $request->des_pedriatria_presion_arterial_y[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_frecuencia_cardiaca[$key]) && $request->des_pedriatria_frecuencia_cardiaca[$key] !="") ? $request->des_pedriatria_frecuencia_cardiaca[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_frecuencia_respiratorio[$key]) && $request->des_pedriatria_frecuencia_respiratorio[$key] !="") ? $request->des_pedriatria_frecuencia_respiratorio[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_temperatura[$key]) && $request->des_pedriatria_temperatura[$key] !="") ? $request->des_pedriatria_temperatura[$key] : "0";
            /** EXÁMENES DE LABORATORIO **/
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_creatinina[$key]) && $request->des_pedriatria_creatinina[$key] !="") ? $request->des_pedriatria_creatinina[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_diuresis[$key]) && $request->des_pedriatria_diuresis[$key] !="") ? $request->des_pedriatria_diuresis[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_procalcitonina[$key]) && $request->des_pedriatria_procalcitonina[$key] !="") ? $request->des_pedriatria_procalcitonina[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_pcr[$key]) && $request->des_pedriatria_pcr[$key] !="") ? $request->des_pedriatria_pcr[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_fiO2[$key]) && $request->des_pedriatria_fiO2[$key] !="") ? $request->des_pedriatria_fiO2[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_leucocitos[$key]) && $request->des_pedriatria_leucocitos[$key] !="") ? $request->des_pedriatria_leucocitos[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_plaquetas[$key]) && $request->des_pedriatria_plaquetas[$key] !="") ? $request->des_pedriatria_plaquetas[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_bilirrubina[$key]) && $request->des_pedriatria_bilirrubina[$key] !="") ? $request->des_pedriatria_bilirrubina[$key] : "0";
            
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_glucosa[$key]) && $request->des_pedriatria_glucosa[$key] !="") ? $request->des_pedriatria_glucosa[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_alt[$key]) && $request->des_pedriatria_alt[$key] !="") ? $request->des_pedriatria_alt[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_vcg[$key]) && $request->des_pedriatria_vcg[$key] !="") ? $request->des_pedriatria_vcg[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_tp[$key]) && $request->des_pedriatria_tp[$key] !="") ? $request->des_pedriatria_tp[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_sodio[$key]) && $request->des_pedriatria_sodio[$key] !="") ? $request->des_pedriatria_sodio[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_potasio[$key]) && $request->des_pedriatria_potasio[$key] !="") ? $request->des_pedriatria_potasio[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_calcio[$key]) && $request->des_pedriatria_calcio[$key] !="") ? $request->des_pedriatria_calcio[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_pco2[$key]) && $request->des_pedriatria_pco2[$key] !="") ? $request->des_pedriatria_pco2[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_tpta[$key]) && $request->des_pedriatria_tpta[$key] !="") ? $request->des_pedriatria_tpta[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_examen_laboratorio_otros[$key]) && $request->des_pedriatria_examen_laboratorio_otros[$key] !="") ? $request->des_pedriatria_examen_laboratorio_otros[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_examen_laboratorio_otros_especifique[$key]) && $request->des_pedriatria_examen_laboratorio_otros_especifique[$key] !="") ? $request->des_pedriatria_examen_laboratorio_otros_especifique[$key] : "0";
            /** TERAPIA ANTIMICROBIANA **/
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_terapia_antimicrobiana[$key]) && $request->des_pedriatria_terapia_antimicrobiana[$key] !="") ? $request->des_pedriatria_terapia_antimicrobiana[$key] : "0";
            /** SOLICITUD DE FRASCO DE HEMOCULTIVO **/
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_frasco_1[$key]) && $request->des_pedriatria_frasco_1[$key] !="") ? $request->des_pedriatria_frasco_1[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_frasco_1_opcion_1[$key]) && $request->des_pedriatria_frasco_1_opcion_1[$key] !="") ? $request->des_pedriatria_frasco_1_opcion_1[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_frasco_1_opcion_2[$key]) && $request->des_pedriatria_frasco_1_opcion_2[$key] !="") ? $request->des_pedriatria_frasco_1_opcion_2[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_frasco_2[$key]) && $request->des_pedriatria_frasco_2[$key] !="") ? $request->des_pedriatria_frasco_2[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_frasco_2_opcion_1[$key]) && $request->des_pedriatria_frasco_2_opcion_1[$key] !="") ? $request->des_pedriatria_frasco_2_opcion_1[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_frasco_2_opcion_2[$key]) && $request->des_pedriatria_frasco_2_opcion_2[$key] !="") ? $request->des_pedriatria_frasco_2_opcion_2[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_frasco_3[$key]) && $request->des_pedriatria_frasco_3[$key] !="") ? $request->des_pedriatria_frasco_3[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_frasco_3_opcion_1[$key]) && $request->des_pedriatria_frasco_3_opcion_1[$key] !="") ? $request->des_pedriatria_frasco_3_opcion_1[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_frasco_3_opcion_2[$key]) && $request->des_pedriatria_frasco_3_opcion_2[$key] !="") ? $request->des_pedriatria_frasco_3_opcion_2[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_hemocultivo_seguimiento_y_vigilancia[$key]) && $request->des_pedriatria_hemocultivo_seguimiento_y_vigilancia[$key] !="") ? $request->des_pedriatria_hemocultivo_seguimiento_y_vigilancia[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_fecha_primer_resultado_hemocultivo[$key]) && $request->des_pedriatria_fecha_primer_resultado_hemocultivo[$key] !="") ? $request->des_pedriatria_fecha_primer_resultado_hemocultivo[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->flag_solicitud_examen_especializados_hemocultivo_pedriatria[$key]) && $request->flag_solicitud_examen_especializados_hemocultivo_pedriatria[$key] !="") ? $request->flag_solicitud_examen_especializados_hemocultivo_pedriatria[$key] : "0";
            $p_laboratorio .= ",";
            // ====================== SOLICITUD PARA ESTUDIO CITOQUÍMICO LÍQUIDOS BIOLÓGICOS  ===============================================
            $p_laboratorio .= (isset($request->des_antecedentes_patologicos_personales[$key]) && $request->des_antecedentes_patologicos_personales[$key] !="") ? $request->des_antecedentes_patologicos_personales[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_antecedentes_patologicos_familiares[$key]) && $request->des_antecedentes_patologicos_familiares[$key] !="") ? $request->des_antecedentes_patologicos_familiares[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_liquido_cefaloraquideo[$key]) && $request->des_liquido_cefaloraquideo[$key] !="") ? $request->des_liquido_cefaloraquideo[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_liquido_pleural[$key]) && $request->des_liquido_pleural[$key] !="") ? $request->des_liquido_pleural[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_liquido_ascitico[$key]) && $request->des_liquido_ascitico[$key] !="") ? $request->des_liquido_ascitico[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_liquido_sinovial[$key]) && $request->des_liquido_sinovial[$key] !="") ? $request->des_liquido_sinovial[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_liquido_otro[$key]) && $request->des_liquido_otro[$key] !="") ? $request->des_liquido_otro[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_liquido_otro_especifique[$key]) && $request->des_liquido_otro_especifique[$key] !="") ? $request->des_liquido_otro_especifique[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_num_frasco_muestra_opcion[$key]) && $request->des_num_frasco_muestra_opcion[$key] !="") ? $request->des_num_frasco_muestra_opcion[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->flag_solicitud_citoquimicos_liquidos_biologicos[$key]) && $request->flag_solicitud_citoquimicos_liquidos_biologicos[$key] !="") ? $request->flag_solicitud_citoquimicos_liquidos_biologicos[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_pedriatria_tono_muscular[$key]) && $request->des_pedriatria_tono_muscular[$key] !="") ? $request->des_pedriatria_tono_muscular[$key] : "0";
            /******************************************************* SOLICITUD TEST DE TOLERANCIA A LA GLUCOSA ORAL *******************************************************/
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_tolerancia_paciente_pediatrico_ttgo[$key]) && $request->des_tolerancia_paciente_pediatrico_ttgo[$key] !="") ? $request->des_tolerancia_paciente_pediatrico_ttgo[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_fecha_tolerancia_paciente_pediatrico_ttgo[$key]) && $request->des_fecha_tolerancia_paciente_pediatrico_ttgo[$key] !="") ? $request->des_fecha_tolerancia_paciente_pediatrico_ttgo[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_tolerancia_paciente_pediatrico_glucosa_anhidra[$key]) && $request->des_tolerancia_paciente_pediatrico_glucosa_anhidra[$key] !="") ? $request->des_tolerancia_paciente_pediatrico_glucosa_anhidra[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_cantidad_tolerancia_paciente_pediatrico_glucosa_anhidra[$key]) && $request->des_cantidad_tolerancia_paciente_pediatrico_glucosa_anhidra[$key] !="") ? $request->des_cantidad_tolerancia_paciente_pediatrico_glucosa_anhidra[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_tolerancia_paciente_adulto_ttgo[$key]) && $request->des_tolerancia_paciente_adulto_ttgo[$key] !="") ? $request->des_tolerancia_paciente_adulto_ttgo[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_fecha_tolerancia_paciente_adulto_ttgo[$key]) && $request->des_fecha_tolerancia_paciente_adulto_ttgo[$key] !="") ? $request->des_fecha_tolerancia_paciente_adulto_ttgo[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_tolerancia_paciente_adulto_glucosa_anhidra[$key]) && $request->des_tolerancia_paciente_adulto_glucosa_anhidra[$key] !="") ? $request->des_tolerancia_paciente_adulto_glucosa_anhidra[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_cantidad_tolerancia_paciente_adulto_glucosa_anhidra[$key]) && $request->des_cantidad_tolerancia_paciente_adulto_glucosa_anhidra[$key] !="") ? $request->des_cantidad_tolerancia_paciente_adulto_glucosa_anhidra[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_g[$key]) && $request->des_tolerancia_paciente_gestante_g[$key] !="") ? $request->des_tolerancia_paciente_gestante_g[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_p_1[$key]) && $request->des_tolerancia_paciente_gestante_p_1[$key] !="") ? $request->des_tolerancia_paciente_gestante_p_1[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_p_2[$key]) && $request->des_tolerancia_paciente_gestante_p_2[$key] !="") ? $request->des_tolerancia_paciente_gestante_p_2[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_p_3[$key]) && $request->des_tolerancia_paciente_gestante_p_3[$key] !="") ? $request->des_tolerancia_paciente_gestante_p_3[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_p_4[$key]) && $request->des_tolerancia_paciente_gestante_p_4[$key] !="") ? $request->des_tolerancia_paciente_gestante_p_4[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_edad_gestacional_meses[$key]) && $request->des_tolerancia_paciente_gestante_edad_gestacional_meses[$key] !="") ? $request->des_tolerancia_paciente_gestante_edad_gestacional_meses[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_edad_gestacional_semanas[$key]) && $request->des_tolerancia_paciente_gestante_edad_gestacional_semanas[$key] !="") ? $request->des_tolerancia_paciente_gestante_edad_gestacional_semanas[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_edad_gestacional_dias[$key]) && $request->des_tolerancia_paciente_gestante_edad_gestacional_dias[$key] !="") ? $request->des_tolerancia_paciente_gestante_edad_gestacional_dias[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_ttgo[$key]) && $request->des_tolerancia_paciente_gestante_ttgo[$key] !="") ? $request->des_tolerancia_paciente_gestante_ttgo[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_fecha_tolerancia_paciente_gestante_ttgo[$key]) && $request->des_fecha_tolerancia_paciente_gestante_ttgo[$key] !="") ? $request->des_fecha_tolerancia_paciente_gestante_ttgo[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_glucosa_anhidra[$key]) && $request->des_tolerancia_paciente_gestante_glucosa_anhidra[$key] !="") ? $request->des_tolerancia_paciente_gestante_glucosa_anhidra[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_cantidad_tolerancia_paciente_gestante_glucosa_anhidra[$key]) && $request->des_cantidad_tolerancia_paciente_gestante_glucosa_anhidra[$key] !="") ? $request->des_cantidad_tolerancia_paciente_gestante_glucosa_anhidra[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_factores_riesgo[$key]) && $request->des_tolerancia_paciente_gestante_factores_riesgo[$key] !="") ? $request->des_tolerancia_paciente_gestante_factores_riesgo[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->des_especifique_tolerancia_paciente_gestante_factores_riesgo[$key]) && $request->des_especifique_tolerancia_paciente_gestante_factores_riesgo[$key] !="") ? $request->des_especifique_tolerancia_paciente_gestante_factores_riesgo[$key] : "0";
            $p_laboratorio .= ",";
            $p_laboratorio .= (isset($request->flag_solicitud_test_tolerancia_glucosa_oral[$key]) && $request->flag_solicitud_test_tolerancia_glucosa_oral[$key] !="") ? $request->flag_solicitud_test_tolerancia_glucosa_oral[$key] : "0";
            $p_laboratorio .= "},";
          }
        endforeach;
        if (strlen($p_laboratorio) > 1) $p_laboratorio = substr($p_laboratorio, 0, -1);
        $p_laboratorio .= "}";
        if($request->id_examen_lab[0]!=""):
          if (intval($request->id_establecimiento) === 76):
        $has_observacion_unica = (intval($request->id_nivel) === 3) ? 1 : 0;
        $id_evaluacion = 0;
        $id_interconsulta = 0;
        $id_area = 0;
        $lab_orden_observacion_unica = intval($request->id_nivel) === 3 ? $request->lab_orden_observacion : null;
        $flag_cq = (isset($request->id_perfil_preoperatorio) && intval($request->id_perfil_preoperatorio) > 0) ? 1 : 0;
        $id_hora_toma_muestra = (intval($request->id_nivel) === 3 && isset($request->id_hora_toma_muestra)) ? $request->id_hora_toma_muestra : null;

        $id_procedimiento_lab = $model_procedimiento->RegistrarLaboratorioOrden3($request->id_user, $id_prestacion, $request->id_establecimiento, $fecha_hora_atencion, $p_laboratorio, $has_observacion_unica, $id_evaluacion, $request->id_farmacia, $id_interconsulta, $request->id_tipo_atencion, $id_servicio_encargado, $id_area, $id_cama_traslado, $request->id_medico, $id_asegurado, $lab_orden_observacion_unica, $flag_cq, $id_hora_toma_muestra);
          else:
          $id_procedimiento_lab = $model_procedimiento->RegistrarProcedimiento2($request->id_user, $id_prestacion, $request->id_establecimiento, $request->fecha_atencion, $p_laboratorio, 2, 0, $request->id_farmacia);
          endif;
        endif;
      endif;

      //insertar procedimientos x diagnosticos

      if(isset($request->id_procedimiento)):
        if(isset($request->miSelect)):
          $arrayprocedimientos=$request->id_procedimiento;
          $arraydiagnosticos=$request->miSelect;
          for($iterador=0;$iterador<count($arrayprocedimientos);$iterador++){
    
            $model_procedimiento_metodos = new \App\Models\Procedimiento;
            $idprocedimiento= $arrayprocedimientos[$iterador];
          
            $iddiagnostico = $arraydiagnosticos[$iterador];
        
            $idprestacionxDiagProc = $id_prestacion;
            $mensajeSucess = $model_procedimiento_metodos->RegistrarProcedimientoxDiagnostico($idprestacionxDiagProc,$idprocedimiento,$iddiagnostico,1);  
          }

        endif;
      endif;

      /*function escape_pg_value($value) {
      if ($value === null || $value === '') return 'NULL';
      if (is_numeric($value)) return $value;
      $escaped = str_replace(['\\', '"'], ['\\\\', '\\"'], $value);
      return '"' . $escaped . '"';
      }*/
      if (isset($request->id_examen_rad)) :
        $p_radiologia = "";
        $p_radiologia .= "{";
        foreach ($request->id_examen_rad as $key => $value) :
          if ($request->id_examen_rad[$key] != "") {
            $p_radiologia .= "{";
            $p_radiologia .= $request->id_examen_rad[$key] . ",";
            $p_radiologia .= (isset($request->examen_rad_cantidad[$key]) && $request->examen_rad_cantidad[$key] > 0) ? $request->examen_rad_cantidad[$key] : "0";
            $p_radiologia .= ",";
            $p_radiologia .= (isset($request->examen_rad_observacion[$key]) && $request->examen_rad_observacion[$key] !="") ? str_replace($search, $replace, $request->examen_rad_observacion[$key]) : 'NULL';
            $p_radiologia .= ",";
            $p_radiologia .= (isset($request->id_comparativo[$key]) && $request->id_comparativo[$key] !="") ? $request->id_comparativo[$key] : "0";
            $p_radiologia .= ",";
            $p_radiologia .= (isset($request->id_lado[$key]) && $request->id_lado[$key] !="") ? $request->id_lado[$key] : "0";
            $p_radiologia .= ",";
            $p_radiologia .= (isset($request->miSelectRad[$key]) && $request->miSelectRad[$key] !="") ? $request->miSelectRad[$key] : "0";
            $p_radiologia .= ",";
            $p_radiologia .= (isset($request->radio_portatil[$key]) && $request->radio_portatil[$key] !="") ? $request->radio_portatil[$key] : "0";
            $p_radiologia .= "},";
          }
        endforeach;
        if (strlen($p_radiologia) > 1) $p_radiologia = substr($p_radiologia, 0, -1);
        $p_radiologia .= "}";
        if($request->id_examen_rad[0]!=""):
          $id_procedimiento_rad = $model_procedimiento->RegistrarProcedimientoImagenes($request->id_user, $id_prestacion, $request->id_establecimiento, $request->fecha_atencion, $p_radiologia, 3, 0, $request->id_farmacia);
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Identificar radiología a la cual pertenece 
        if (intval($request->id_establecimiento) == 76) {
          $updated = DB::table('rua_imagenologia')->where('id', $request->id_rua_imagenes)->update(['id_rad' => $id_procedimiento_rad, 'id_estado_reg' => 1]);
        }
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        endif;
      endif;
      /***************************************************************************/
      $date = Carbon::now();
      $hora = $date->format('H:i');

      if($hora>'01:30' & $hora<'07:31'):
        /*echo*/ $turno='MD';
      else:
        if($hora>'07:30' & $hora<'13:31'):
          /*echo*/ $turno='M';
        else:
          if($hora>'13:30' & $hora<'19:31'):
            /*echo*/ $turno='T';
          else:
            if($hora>'19:30' & $hora<='23:59'):
              /*echo*/ $turno='N';
            else:
              if($hora>='00:00' & $hora<='01:31'):
                /*echo*/ $turno='N';
              endif;
            endif;
          endif;
        endif;
      endif;
      
      /*$model_interconsulta = new \App\Models\Interconsulta;
      if($request->personal_salud!=0 || $request->personal_salud!="" || $request->personal_salud!=null):
        $id_servicio_interconsulta = $model_interconsulta->RegistrarInterconsulta($id_prestacion, $id_asegurado, $request->id_tipo_atencion, $request->fecha_atencion, $request->id_medico, $request->id_establecimiento, $request->inter_servicio, $request->personal_turno,$request->personal_salud,$request->motivo_interconsulta);
      endif;
      */
      // $search  = array('+', ',','"','/');
      // $replace = array('\+', '\,', '\"', '\/');

      $model_interconsulta = new \App\Models\Interconsulta;
      //  if($request->id_servicio_interconsulta!=null):
      //    $p_interconsulta = "";
      //    $p_interconsulta .= "{";
      //   foreach ($request->id_servicio_interconsulta as $key => $value) :
      //      if ($request->id_servicio_interconsulta[$key] != "") {
      //        $p_interconsulta .= "{";
      //        $p_interconsulta .= $request->id_servicio_interconsulta[$key] . ",";
      //        $p_interconsulta .= $turno . ",";
      //        $p_interconsulta .= "0,";
      //        $p_interconsulta .= trim($request->motivo_interconsulta[$key]) != "" ? str_replace($search, $replace, $request->motivo_interconsulta[$key]) : "\"\"";
      //        $p_interconsulta .= "},";
      //      }
      //    endforeach;
      //    if (strlen($p_interconsulta) > 1) $p_interconsulta = substr($p_interconsulta, 0, -1);
      //   $p_interconsulta .= "}"; 

      //    if($request->id_servicio_interconsulta[0]!=""):
      //      $id_interconsulta = $model_interconsulta->RegistrarInterconsulta($id_prestacion, $id_asegurado, $request->id_tipo_atencion, $request->fecha_atencion, $request->id_medico, $request->id_establecimiento, $p_interconsulta);
            //$model_inter = new \App\Models\Interconsulta;
            //$ok=$model_inter->ActualizaIdEvaluacion($id_interconsulta,$id_evaluacion);
      //    endif;
      //  endif;
      /* if (!empty($request->id_servicio_interconsulta)) {

        // Quitar duplicados de interconsultas en backend
        $id_servicio_interconsulta_unicos = [];
        $hidden_select_otorrino_unicos = [];  // Codigo UPSS
        $motivo_interconsulta_unicos = [];

        foreach ($request->id_servicio_interconsulta as $index => $id_servicio) {
          if (!in_array($id_servicio, $id_servicio_interconsulta_unicos)) {
            $id_servicio_interconsulta_unicos[] = $id_servicio;
            $hidden_select_otorrino_unicos[] = $request->hidden_select_OTORRINO[$index];
            $motivo_interconsulta_unicos[] = $request->motivo_interconsulta[$index];
          }
        }
        // Fin Quitar duplicados de interconsultas en backend
        
        $elementos = [];
    
        foreach ($id_servicio_interconsulta_unicos as $key => $value) {
            if (!empty($value)) {
                // Escapa los caracteres especiales en el motivo de la interconsulta
                $motivo_interconsulta = str_replace($search, $replace, $motivo_interconsulta_unicos[$key]);
                $motivo_interconsulta = addslashes($motivo_interconsulta); // Evita problemas con comillas
                $hidden_select_OTORRINO = isset($hidden_select_otorrino_unicos[$key]) ? addslashes($hidden_select_otorrino_unicos[$key]) : '';

                // Agrega el elemento en formato de array PostgreSQL
                $elementos[] = sprintf('{"%s","%s","%s","%s","%s"}', $value, $turno, "0", $motivo_interconsulta, $hidden_select_OTORRINO);
            }
        }
    
        // Une los elementos del array
        if (!empty($elementos)) {
          $p_interconsulta = '{' . implode(',', $elementos) . '}';
        
          // Valida que al menos hay un servicio antes de registrar
          if (!empty($id_servicio_interconsulta_unicos)) {
              $id_interconsulta = $model_interconsulta->RegistrarInterconsulta(
                  $id_prestacion, 
                  $id_asegurado, 
                  $request->id_tipo_atencion, 
                  $request->fecha_atencion, 
                  $request->id_medico, 
                  $request->id_establecimiento, 
                  $p_interconsulta
              );
          }
        }
      } */
      if (!empty($request->id_servicio_interconsulta)) {

        // Quitar duplicados de interconsultas en backend
        $id_servicio_interconsulta_unicos = [];
        $hidden_select_otorrino_unicos = [];      // Código UPSS para OTORRINO
        $hidden_select_diagnostico_unicos = [];     // Código UPSS para DIAGNOSTICO
        $motivo_interconsulta_unicos = [];
        $personal_coordinacion_unicos = [];

        foreach ($request->id_servicio_interconsulta as $index => $id_servicio) {
            if (!in_array($id_servicio, $id_servicio_interconsulta_unicos)) {
                $id_servicio_interconsulta_unicos[] = $id_servicio;
                $hidden_select_otorrino_unicos[] = $request->hidden_select_OTORRINO[$index];
                $hidden_select_diagnostico_unicos[] = $request->hidden_select_DIAGNOSTICO[$index];
                $motivo_interconsulta_unicos[] = $request->motivo_interconsulta[$index];
                $personal_coordinacion = $request->interconsulta_personal_coodinacion[$index];
                $personal_coordinacion_unicos[] = $personal_coordinacion != "" ? $personal_coordinacion : "NULL";
            }
        }
        // Fin quitar duplicados de interconsultas en backend

        $elementos = [];

        foreach ($id_servicio_interconsulta_unicos as $key => $value) {
          if (!empty($value)) {
              // Escapa los caracteres especiales en el motivo de la interconsulta
              $motivo_interconsulta = str_replace($search, $replace, $motivo_interconsulta_unicos[$key]);
              $motivo_interconsulta = addslashes($motivo_interconsulta); // Evita problemas con comillas

              $hidden_select_OTORRINO = isset($hidden_select_otorrino_unicos[$key])
                  ? addslashes($hidden_select_otorrino_unicos[$key])
                  : '';
              $hidden_select_DIAGNOSTICO = isset($hidden_select_diagnostico_unicos[$key])
                  ? addslashes($hidden_select_diagnostico_unicos[$key])
                  : '';

              // Agrega el elemento en formato de array PostgreSQL con 6 valores:
              // [id_servicio, turno, "0", motivo_interconsulta, hidden_select_OTORRINO, hidden_select_DIAGNOSTICO]
              $elementos[] = sprintf(
                  '{"%s","%s","%s","%s","%s","%s","%s","%s"}',
                  $value,
                  $turno,
                  "0",
                  $motivo_interconsulta,
                  $hidden_select_OTORRINO,
                  $hidden_select_DIAGNOSTICO,
                  $request->is_estable,
                  str_replace($search, $replace, $personal_coordinacion_unicos[$key])
              );
          }
        }

        // Une los elementos del array
        if (!empty($elementos)) {
          $p_interconsulta = '{' . implode(',', $elementos) . '}';

          // Valida que al menos hay un servicio antes de registrar
          if (!empty($id_servicio_interconsulta_unicos)) {
              /* dd( $id_prestacion, 
              $id_asegurado, 
              $request->id_tipo_atencion, 
              $request->fecha_atencion, 
              $request->id_medico, 
              $request->id_establecimiento, 
              $p_interconsulta); */
              $id_interconsulta = $model_interconsulta->RegistrarInterconsulta(
                  $id_prestacion, 
                  $id_asegurado, 
                  $request->id_tipo_atencion, 
                  $request->fecha_atencion, 
                  $request->id_medico, 
                  $request->id_establecimiento, 
                  $p_interconsulta
              );
          }
        }
      }

      /*$model_hospitalizacione = new \App\Models\Hospitalizacione;
      if ($request->flag_alta_servicio == "1") {

        $p_diagnostico_alta_servicio = "";
        $p_diagnostico_alta_servicio .= "{";
        if (isset($request->id_diagnostico)) :
          foreach ($request->id_diagnostico as $key => $value) :
            if ($request->id_diagnostico[$key] != "") {
              $p_diagnostico_alta_servicio .= "{";
              $p_diagnostico_alta_servicio .= $request->id_diagnostico[$key] . ",";
              $p_diagnostico_alta_servicio .= (isset($request->id_tipo_diagnostico[$key]) && $request->id_tipo_diagnostico[$key] > 0) ? $request->id_tipo_diagnostico[$key] : "0";
              $p_diagnostico_alta_servicio .= ",";
              $p_diagnostico_alta_servicio .= $request->id_categoria_diagnostico[$key] . ", 9"; //alta de servicio
              $p_diagnostico_alta_servicio .= "},";
            }
          endforeach;
          if (strlen($p_diagnostico_alta_servicio) > 1) $p_diagnostico_alta_servicio = substr($p_diagnostico_alta_servicio, 0, -1);
        endif;
        $p_diagnostico_alta_servicio .= "}";

        $update = $model_hospitalizacione->registrar_alta_servicio($id_hospitalizacion, $id_prestacion, 0, $request->id_user, $request->fecha_egreso, $request->hora_egreso, $request->id_cama_destino, $request->observacion_alta_servicio, $p_diagnostico_alta_servicio);
      } else {
        $update=$model_hospitalizacione->ActualizaEstadoHospitalizacionAtencion($id_hospitalizacion,3, $request->id_user);
      }*/
        
      $hospitalizacionUpdateSiteds = Hospitalizacione::find($id_hospitalizacion);
      if (isset($hospitalizacionUpdateSiteds) && !empty($request->cod_siteds_susalud)) {
        $hospitalizacionUpdateSiteds->cod_siteds_susalud = $request->cod_siteds_susalud;
        $hospitalizacionUpdateSiteds->updated_at_cod_siteds = Carbon::now();
        $hospitalizacionUpdateSiteds->updated_us_cod_siteds = Auth::user()->id;
        if (!$hospitalizacionUpdateSiteds->save()) {
          Log::error("No se ha podido actualizar la informacion de SITEDS.");
          throw new \Exception("No se ha podido actualizar la informacion de SITEDS.");
        }
      }

      if ($modo_hc_cu === 'crear') {
        DB::table('hospitalizaciones')->where('id', $request->id_principal)->update([
          'flag_exa_cu'        => 1,
          'fecha_hora_cu'      => Carbon::now(),
          'id_user_exa_cu'     => Auth::user()->id,
          'fecha_limite_hc_cu' => Carbon::now()->addHours(1),
        ]);
      } elseif ($modo_hc_cu === 'continuar') {
        $hosp_fecha_limite_cu = DB::table('hospitalizaciones')->where('id', $request->id_principal)->value('fecha_limite_hc_cu');
        $fuera_de_plazo_cu = $hosp_fecha_limite_cu ? Carbon::now()->gt(Carbon::parse($hosp_fecha_limite_cu)) : false;
        DB::table('hospitalizaciones')->where('id', $request->id_principal)->update([
          'flag_hc_cu'        => 1,
          'id_user_hc_cu'     => Auth::user()->id,
          'fecha_hora_hc_cu'  => Carbon::now(),
          'hc_cu_fuera_plazo' => $fuera_de_plazo_cu,
        ]);
      }

    if($datos && $id_prestacion>0){
        $eventosSeleccionados = $request->input('evento_ep', []); // eventos marcados en el formulario
        $eventosSeleccionados = array_map('intval', $eventosSeleccionados);
        $eventosExistentes = Prestacion_eventos::where('id_prestacion', $id_prestacion)->get();
        foreach ($eventosExistentes as $evento) {
          if (!in_array($evento->id_evento, $eventosSeleccionados)) {
              $evento->estado = 0;
              $evento->updated_us = Auth::id();
              $evento->updated_at = date('Y-m-d H:i:s');
              if (!$evento->save()) {
                Log::error("No se ha podido actualizar (1) el estado de los registros de (Prestacion eventos).");
                throw new \Exception("No se ha podido actualizar (1) el estado de los registros de (Prestacion eventos).");
              }
          }
        }
        foreach ($eventosSeleccionados as $idEvento) {
          $evento = null;
          foreach ($eventosExistentes as $e) {
              if ($e->id_evento == $idEvento) {
                  $evento = $e;
                  break;
              }
          }

          if ($evento) {
              if ($evento->estado != 1) {
                  $evento->estado = 1;
                  $evento->updated_us = Auth::id();
                  $evento->updated_at = date('Y-m-d H:i:s');
                  if (!$evento->save()) {
                    Log::error("No se ha podido actualizar (2) el estado de los registros de (Prestacion eventos).");
                    throw new \Exception("No se ha podido actualizar (2) el estado de los registros de (Prestacion eventos).");
                  }
              }
          } else {
              $newPrestacionEvento =Prestacion_eventos::create([
                  'id_prestacion' => $id_prestacion,
                  'id_evento' => $idEvento,
                  'estado' => 1,
                  'created_us' => Auth::id(),
              ]);

              if (!$newPrestacionEvento) {
                Log::error("No se ha podido registrar los datos de Eventos de prestación.");
                throw new \Exception("No se ha podido registrar los datos de Eventos de prestación.");
              }
          }
        }
      }

      DB::commit();
      $datos = true;

    } catch (\Exception $e) {
      DB::rollback();
      // throw $e;
      $mensaje_error = $e->getMessage();
      Log::error("Registro Atencion Hospitalizacion => id_hospitalizacion: " . $request->id_cita . 
        "\n\nmensaje: " . $e->getMessage() . 
        "\n\ndatos del request: " . json_encode($request->all()) . 
        "\n\nstack trace: " . $e->getTraceAsString()
      );
    } catch (\Throwable $th) {
      DB::rollback();
      // throw $th;
      $mensaje_error = $th->getMessage();
      Log::critical("Registro Atencion Hospitalizacion => id_hospitalizacion: " . $request->id_cita . 
        "\n\nmensaje: " . $th->getMessage() . 
        "\n\ndatos del request: " . json_encode($request->all()) . 
        "\n\nstack trace: " . $th->getTraceAsString()
      );
    }

    if ($datos) {
      Session::flash('flash_message', 'Prestación exitosa');
      if ($modo_hc_cu === 'crear') {
        // Firma electrónica sin token de las órdenes de Vista 1 (UCI dos vistas).
        $this->firmarOrdenesSinTokenVista1Cu($id_prestacion);
        return redirect('/form_atencion_prestacion_hspcu/' . $request->id_user . '/' . $request->id_principal);
      } else {
        return redirect('/ver_prestacion_hospitalizacion/' . $request->id_user . '/' . $id_prestacion);
      }
    } else {
      Session::flash('flash_warning', '<strong>Error:</strong> No se pudo registrar la Atención Médica, vuelva a intentarlo nuevamente.<br><br>' . $mensaje_error);
      return redirect('/all_hospitalizado_by_establecimiento');
    }
  }

  /**
   * Firma electrónica sin token de las órdenes (RECETA, LABORATORIO, RADIOLOGÍA,
   * INTERCONSULTA) generadas en la Vista 1 de hospitalización UCI (dos vistas),
   * para que el servicio destino pueda atenderlas de inmediato.
   */
  private function firmarOrdenesSinTokenVista1Cu($id_prestacion) {
    try {
      $fechaHoraFirmaCu = date("d-m-Y H:i:s");
      $carpetaFirmaCu   = date("Y/m/d");

      \App\Models\Receta_vale::where('id_prestacion', $id_prestacion)
        ->where('estado', '<>', 0)
        ->whereNull('fecha_firma')
        ->get()->each(function ($r) use ($fechaHoraFirmaCu, $carpetaFirmaCu) {
          $r->fecha_firma = $fechaHoraFirmaCu;
          $r->ruta_receta = $carpetaFirmaCu . "/cf_receta_" . $r->id . ".pdf";
          $r->flag = 2;
          $r->save();
        });

      \App\Models\Laboratorio::where('id_prestacion', $id_prestacion)
        ->where('estado', 1)
        ->whereNull('fecha_firma')
        ->get()->each(function ($l) use ($fechaHoraFirmaCu, $carpetaFirmaCu) {
          $l->fecha_firma = $fechaHoraFirmaCu;
          $l->ruta_laboratorio = $carpetaFirmaCu . "/cf_laboratorio_" . $l->id . ".pdf";
          $l->flag = 2;
          $l->save();
        });

      \App\Models\Radiologia::where('id_prestacion', $id_prestacion)
        ->where('estado', 1)
        ->whereNull('fecha_firma')
        ->get()->each(function ($r) use ($fechaHoraFirmaCu, $carpetaFirmaCu) {
          $r->fecha_firma = $fechaHoraFirmaCu;
          $r->ruta_radiologia = $carpetaFirmaCu . "/cf_radiologia_" . $r->id . ".pdf";
          $r->flag = 2;
          $r->save();
        });

      \App\Models\Interconsulta::where('id_prestacion', $id_prestacion)
        ->where('id_estado_reg', 1)
        ->whereNull('fecha_firma_interconsulta')
        ->get()->each(function ($i) use ($fechaHoraFirmaCu, $carpetaFirmaCu) {
          $i->fecha_firma_interconsulta = $fechaHoraFirmaCu;
          $i->ruta_interconsulta = $carpetaFirmaCu . "/cf_interconsulta_" . $i->id . ".pdf";
          $i->flag = 2;
          $i->save();
        });
    } catch (\Throwable $e) {
      Log::error("Error en firmarOrdenesSinTokenVista1Cu, id_prestacion={$id_prestacion}: " . $e->getMessage(), [
        'exception' => $e,
      ]);
    }
  }
  
  public function ver_prestacion_hospitalizacion($id_user, $id_prestacion)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    if (Auth::user()->tipo_registro == 1) {
      $modelfarma = new Farmacia;
      $model_receta = new Receta_vale;
      $model_producto = new Producto;
      $model_productos_receta = new Producto_receta;
      $model_establecimiento = new \App\Models\Establecimiento;
      $model_prestacion = new \App\Models\Prestaciones;
      $model_laboratorio = new \App\Models\Laboratorio;
      $model_radiologia = new \App\Models\Radiologia;
      $model_interconsulta = new \App\Models\Interconsulta;
      $model_referencia = new \App\Models\Referencia;
      $model_kardex = new Kardexe();

      $farmacia = $modelfarma->GetFarmaByUser($id_user);
      $flag_receta_dias = $farmacia->flag_receta_dias;

      $fechaServidor = $model_kardex->getFechaServidor();
      $date = Carbon::now();
      $fecha_actual = $date->format('d-m-Y');
      $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);

      if ($prestacion->id_tipo_atencion == 3 && !empty($prestacion->id_ext)) {
        $hospitalizacion_ver = Hospitalizacione::find($prestacion->id_ext);
        if ($hospitalizacion_ver && !$hospitalizacion_ver->historiaClinicaCompleta()) {
          if ((int) $hospitalizacion_ver->id_user_exa_cu !== (int) Auth::user()->id) {
            return redirect()->back()->withErrors(array('El registro de la Historia Clínica aún no está terminada, por lo que no se puede ingresar a esta atención. Médico responsable del registro: ' . $hospitalizacion_ver->nombreMedicoRegistroHc()));
          }
          return redirect('/form_atencion_prestacion_hspcu/' . $id_user . '/' . $prestacion->id_ext);
        }
      }

      $receta = $model_receta->GetDatosRecetasByIdPrestacion($id_prestacion);

      $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacionEmergencia($id_prestacion);
      /*$diagnosticos = \App\Models\Diagnostico::where('estado',1)
      ->whereRaw('LENGTH(codigo) > 4') 
      ->get();*/
      $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionYTipo($id_prestacion, 1);
      /*$procedimientos = \App\Models\Procedimiento::where('estado',1)
      ->get();*/
      $laboratorio = $model_laboratorio->GetLaboratorioByIdPrestacion($id_prestacion);
      $radiologia = $model_radiologia->GetRadiologiaByIdPrestacion($id_prestacion);
      $pendiente_firma = $model_prestacion->GetDocumentosPendintesFirmarPrestacionId($id_prestacion);
      $flag_pendinte_firma = (count($pendiente_firma) > 0) ? 1 : 0;

      $id_ipress = $farmacia->id_establecimiento;
      // if($id_ipress==76): //lns
      //   $id_farmacia = 19; //farmacia de emergencia
      // else:
        $id_farmacia = $farmacia->id;
      // endif;
      $establecimiento = $model_establecimiento->GetById($id_ipress);
      // if ($id_ipress == 1) {
      //   $nivel = 2;
      // } else {
        $nivel = $establecimiento[0]->nivel;
      // }
      //Si tiene atenciones en hospitalizacion o emergencia
      $sw = 0;
      if($prestacion->id_ext!=0):
        if($prestacion->id_tipo_atencion==3): // HOSPITALIZACION
          $dato_hospitalizacion = Hospitalizacione::where('id', '=', $prestacion->id_ext)->first();
          // $obj_hospitalizacion_atencion = \App\Models\HospitalizacionAtencione::where('id_prestacion', '=', $id_prestacion)->first();
          $obj_hospitalizacion_atencion = \App\Models\HospitalizacionAtencione::select('ha.*', 
              'p.fecha_parto', 
              'p.id_tipo_parto as puerperio_id_tipo_parto', 
              'tp.nombre as puerperio_tipo_parto', 
              'p.complicaciones as puerperio_complicaciones', 
              'p.descripcion as puerperio_descripcion')
          ->from('hospitalizacion_atenciones as ha')
          ->leftJoin('puerperios as p', 'ha.id_puerperio', '=', 'p.id')
          ->leftJoin('tipo_partos as tp', 'p.id_tipo_parto', '=', 'tp.id')
          ->where('ha.id_prestacion', '=', $id_prestacion) // Cambié aquí a 'ha.id' para que coincida con el contexto
          ->first();
          /*$model_emergencia = new \App\Models\Emergencia;
          if($dato_hospitalizacion->id_prioridad<4):
            $obtenerdato = $model_emergencia->GetObtenerServicioActual($prestacion->id_ext);
            $datoadicional = $model_emergencia->GetObtenerCama($obtenerdato->cama);
            $sw = 1;
            $prestacion->servicio= $obtenerdato->servicio;
            $prestacion->consultorio= $obtenerdato->consultorio;
          else:
            $prestacion->servicio="IV - PATOLOGÍA AGUDA COMÚN";
            $prestacion->consultorio="CONSULTORIO DE ATENCIÓN RÁPIDA (CAR)";
            $sw = 0;
          endif;*/
          $producto = $model_producto->getProductosByNivelAndIdServicio($nivel, $id_ipress, $id_farmacia); 
        endif;
      else:
           return redirect('/dashboard')->withInput()->withErrors(array('No encontro la atención..!'));
      endif;
      
      $producto = $model_producto->getProductosByNivelAndIdServicio($nivel, $prestacion->id_consultorio, $id_farmacia);
      
      $farmacias = $modelfarma->GetFarmaciaById($id_farmacia);
      $establecimientos = $model_establecimiento->ShowAllEstablecimientos();
      $interconsultas = $model_interconsulta->GetInterconsultaByIdPrestacion($id_prestacion);

      $interconsultas_id_servicio = [];
      if (!empty($interconsultas)) {
        foreach ($interconsultas as $ic) {
          $interconsultas_id_servicio[] = $ic->id_servicio;
        }
      }

      $referencia = $model_referencia->GetReferenciaByIdPrest($id_prestacion);
      
      $flag_no_encontrado=0; 
      $model_asegurado= new Asegurado();
      if($prestacion->id_asegurado_titular!=null || $prestacion->id_asegurado_titular>0):
        $dato_titular = $model_asegurado->Busca_datos_titular($prestacion->id_asegurado_titular);
      else:
        $flag_no_encontrado=1;
      endif;

      $ruta = "all_hospitalizado_by_establecimiento";
      $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
      $permiso = $menu[0];

      $date = Carbon::now();
      $hora=$date->toTimeString(); 

      if($hora>'01:30' & $hora<'07:31'):
        /*echo*/ $turno='MD';
      else:
        if($hora>'07:30' & $hora<'13:31'):
          /*echo*/ $turno='M';
        else:
          if($hora>'13:30' & $hora<'19:31'):
            /*echo*/ $turno='T';
          else:
            if($hora>'19:30' & $hora<='23:59'):
              /*echo*/ $turno='N';
            else:
              if($hora>='00:00' & $hora<='01:31'):
                /*echo*/ $turno='N';
              endif;
            endif;
          endif;
        endif;
      endif;

      $talla_cm = $prestacion->talla_cm;
      $peso_kg = $prestacion->peso_kg;
      $edadpaciente = $prestacion->edadpaciente;
      $sexo = $prestacion->sexo;
      $peso_ideal = "";
      $imc = "";
      
      // Cálculo del peso ideal
      if ($talla_cm != "0") { 
        // Peso por altura
        $peso_ideal = 50 + (($talla_cm - 150) / 4) * 3 + ($edadpaciente - 20) / 4;
        if ($sexo == "M")
          $peso_ideal = $peso_ideal * 0.9;
        
        if($peso_ideal <= 0)
          $peso_ideal = '';
        
      } else {
        $peso_ideal = "";
      }

      // Cálculo del IMC
      if ($peso_kg != "0" && $talla_cm != "0") {
        $talla_en_metros = $talla_cm * 0.01;
        $imc = "";
        if ($peso_kg > 0 && $talla_cm > 0) {
          $imc = $peso_kg / ($talla_en_metros * $talla_en_metros);
          $imc = number_format($imc, 2, '.', '');
        }
      } else {
        $imc = "";
      }
      // Formateo final
      $peso_ideal = ($peso_ideal == "") ? "-" : $peso_ideal;
      $imc = ($imc == "") ? "-" : $imc;

      $lab = $model_laboratorio->GetLaboratorioByIdPrestacion($id_prestacion);
      if (empty($lab)) {
        $flag_laboratorio = "0";
        $flag_firmar_laboratorio = "";
      } else {
        $flag_laboratorio = "1";
        $flag_firmar_laboratorio = $lab[0]->fecha_firma;
      }
      $rad = $model_radiologia->GetRadiologiaByIdPrestacion($id_prestacion);
      if (count($rad)>0) {
      
        $flag_radiologia = "1";
        $flag_firmar_radiologia = $rad[0]->fecha_firma;
      } else {
        $flag_radiologia = "0";
        $flag_firmar_radiologia = "";
      }

      //============== Ordenes de Imagenes invalidados por Imagenes ============================
      $ordenImagenesAnulada = $model_radiologia->cpmsAnulados(2,$id_prestacion);
      //dd($ordenImagenesAnulada);
      $orden = '';
      $ordenes = [];
      if ( count($ordenImagenesAnulada) > 0 ) {
        foreach ($ordenImagenesAnulada as $key) {
          if ( $orden !== $key->id_radiologia ) {
            $orden = $key->id_radiologia;

            $obj = new stdClass();
            $obj->id_radiologia = $key->id_radiologia;
            $obj->obs_examen_invalidado = $key->obs_examen_invalidado;
            $obj->fecha_orden_invalidado = $key->fecha_orden_invalidado;
            $obj->usuario = $key->name . ', ' . $key->paterno; 

            $ordenes[] = $obj;
          }
        }
        //dd($ordenes);

        foreach ($ordenes as $key1) {
          $key1->cpms = [];
          foreach ($ordenImagenesAnulada as $key2) {
            //dd($key1->id_radiologia);
            if ($key1->id_radiologia == $key2->id_radiologia) {

              $obj = new stdClass();
              $obj->id_pp = $key2->id;
              $obj->codigo_rad = $key2->codigo_rad;
              $obj->descripcion_corta = $key2->descripcion_corta;
              $obj->diagnostico = $key2->diagnostico;

              $key1->cpms[] = $obj;
            }  
          }  
        }

        
      }

      // Verificar si el usuario es medico de profesion
      $model_medico = new Medico();
      $medico = $model_medico->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni,Auth::user()->id_establecimiento );
      $is_profesion_medico = false;
      if (count($medico) > 0) {
        switch ($medico->id_profesion) {
          case 20: case 4: case 51: case 52: case 1: $is_profesion_medico = true; break;
        }
      }

      $model_consultorio = new Consultorio;
      $establecimiento_servicios = $model_consultorio->GetServiciosPorEstablecimiento($id_ipress);

      $eventosMarcados = Prestacion_eventos::where('id_prestacion', $id_prestacion)
          ->where('estado', 1)
          ->lists('id_evento')
          ->toArray();
      $gestacion = Gestacion::where("id", $obj_hospitalizacion_atencion->id_gestacion)->first();
      $puerperio = Puerperio::where("id", $obj_hospitalizacion_atencion->id_puerperio)->first();

      // Validar campo de habilitacion de firma electronica
      $is_habilitado_firma_electronica = $establecimiento[0]->flag_firma_electronica;
      $id_principal=$prestacion->id_ext;

      $cambio_cama_model = new App\Models\CambioCama();
      $cambio_cama_data = $cambio_cama_model->getDataByIdPrestacion($id_prestacion);
      /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      return view('frontend.hospitalizacion.ver_atencion2', compact('dato_titular','farmacia','gestacion','eventosMarcados','prestacion', 'puerperio','diagnostico', 'procedimiento', 'medicamentos', 'id_prestacion', 'fechaServidor', 'receta', 'producto', 'farmacias', 'nivel', 'laboratorio', 'radiologia', 'flag_pendinte_firma','establecimientos','interconsultas','referencia','procedimientos','datoadicional','sw','permiso','flag_no_encontrado','turno','id_farmacia','dato_hospitalizacion','obj_hospitalizacion_atencion','peso_ideal','imc','flag_laboratorio','flag_firmar_laboratorio','flag_radiologia','flag_firmar_radiologia','ordenImagenesAnulada','ordenes','is_profesion_medico', 'interconsultas_id_servicio','establecimiento_servicios', 'is_habilitado_firma_electronica','id_principal', 'cambio_cama_data'));
    }
    else
    {
      return redirect('/dashboard')->withInput()->withErrors(array('No tiene el perfil de médico..!'));
    }
  }
		
  public function registrarExamenComplementarios(Request $request)
  {
    
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;//dd($request);
    
    $search = array('+', ',','"','/', '{', '}');
    $replace = array('\+', '\,', '\"', '\/', '\{', '\}');
    
    $id_prestacion = $request->id_prestacion;
    $id_receta_vale = 0;
    $id_anestesiologo = 0;
    $obs_registro = "";
    $id_receta_vale_bus = 0;
    $id_admision = 0;
    $tipo_via = "";
    $sub_via = "";
    $final_via = "";
    $nro_guia = "";
    $interconsulta = "";
    $interconsulta_dias = 0;
    $nro_receta = "";
    $digitos_adicionales = "";
    $id_tipo_num = 0;
    $estado_receta = 2;
    $prestacion = Prestaciones::find($request->id_prestacion);
    $subconsultorio = SubConsultorio::find($prestacion->id_sub_consultorio);
    $cita = Cita::find($prestacion->id_cita);
    $hospitalizacion_at = DB::table('hospitalizacion_atenciones')
    ->where('id_prestacion', $request->id_prestacion)
    ->orderBy('id', 'desc')
    ->first();
    $request->merge(['id_hospitalizacion_atencion' => $hospitalizacion_at->id]);
    $asegurado = Asegurado::find($cita->id_asegurado);
    $titular = Asegurado::find($cita->id_titular);
    //$historia = Asegurado_historia::find($cita->id_asegurado);
    $historia = Asegurado_historia::where('id_asegurado',$cita->id_asegurado)->where('id_establecimiento',$cita->id_establecimiento)->first();
    //echo $p_diagnostico;exit();
    //print_r($prestacion);exit();

    $id_medico = $prestacion->id_medico;
    $id_tiporeceta = $prestacion->id_tipo_atencion;
    $codigo_upss = $prestacion->codigo_upss;
    $fecha_atencion = $request->fecha_expedicion;
    //$id_consultorio = $subconsultorio->id_consultorio;
    $id_consultorio = $request->id_consultorio_h;
    $grado = $cita->grado;
    $tipo_beneficiario = $cita->parentesco;

    $dni_beneficiario = $asegurado->nro_doc_ident;
    $cip_beneficiario = $asegurado->cip;
    $tipodoc_beneficiario = $asegurado->tipo_doc_ident;
    $nombre_beneficiario = $asegurado->nombre;
    $paterno_beneficiario = $asegurado->paterno;
    $materno_beneficiario = $asegurado->materno;
    $sexo = $asegurado->sexo;
    $fecha_nacimiento = $asegurado->fecha_nac;
    $dni_titular = $titular->nro_doc_ident;
    $tipdoc_titular = $titular->tipo_doc_ident;
    $nombre_titular = $titular->nombre;
    $paterno_titular = $titular->paterno;
    $materno_titular = $titular->materno;
    $receta_prescrita        =  0;
    $request->id_modalidad = 1;
    if($request->id_tipo_atencion==2){
      $id_emergencia = $request->id_cita;
      $id_hospitalizacion = 0;
      $id_cita= 0;
    }
    else
    {
      $id_hospitalizacion = $request->id_cita;
      $id_cita= 0; 
      $id_emergencia = 0;
    }

    $model_prestaciones = new Prestaciones();
    $receta = new Receta_Vale();
    // $diagnosticos = $model_prestaciones->GetDiagnosticoByIdPrestacion($id_prestacion);
    $list_diagnostico_ya_registrado = $model_prestaciones->GetDiagnosticoByIdPrestacionEmergencia($id_prestacion);

    $p_diagnostico = "";
    $p_diagnostico .= "{";
    foreach ($list_diagnostico_ya_registrado as $row) :
      $p_diagnostico .= "{";
      $p_diagnostico .= $row->id_diagnostico . ",";
      //$p_diagnostico.=$row->id_tipo_diagnostico;
      $p_diagnostico .= ($row->id_tipo_diagnostico > 0) ? $row->id_tipo_diagnostico : "0";
      $p_diagnostico .= "},";
    endforeach;
    $p_diagnostico = substr($p_diagnostico, 0, -1);
    $p_diagnostico .= "}";

    $p_producto = "";
    $p_producto .= "{";
    if (isset($request->id_producto)) :
      $receta_prescrita=1;
      foreach ($request->id_producto as $key => $value) :
        if ($request->id_producto[$key] != "") {
          $p_producto .= "{";
          $p_producto .= $request->id_producto[$key] . ",";
          $p_producto .= $request->tiempo_periodo[$key] . ",";
          $p_producto .= $request->nro_med_solictados[$key] . ",";
          $p_producto .= (isset($request->nro_med_entregados[$key]) && $request->nro_med_entregados[$key] != "") ? $request->nro_med_entregados[$key] : 0;
          $p_producto .= ",";
          $p_producto .= (isset($request->nro_stocks_almacen[$key]) && $request->nro_stocks_almacen[$key] != "") ? $request->nro_stocks_almacen[$key] : 0;
          $p_producto .= ",";
          $p_producto .= (isset($request->nro_stocks_establecimiento[$key]) && $request->nro_stocks_establecimiento[$key] != "") ? $request->nro_stocks_establecimiento[$key] : 0;
          $p_producto .= ",";
          $p_producto .= (isset($request->nro_stocks[$key]) && $request->nro_stocks[$key] != "") ? $request->nro_stocks[$key] : 0;
          $p_producto .= ",";
          $p_producto .= (isset($request->posologia[$key]) && $request->posologia[$key] != "") ? str_replace(",", "###", $request->posologia[$key]) : "-";
          $p_producto .= ",";
          $p_producto .= (isset($request->miSelectProd[$key]) && $request->miSelectProd[$key] != "") ? $request->miSelectProd[$key] : 0;
          $p_producto .= ",";
          $p_producto .= $request->id_via_administracion[$key];
          $p_producto .= "},";
        }
      endforeach;
      if (strlen($p_producto) > 1) $p_producto = substr($p_producto, 0, -1);
    endif;
    $p_producto .= "}";

    /*********************************************************************/
    $model_procedimiento = new \App\Models\Procedimiento;
    if (isset($request->id_examen_lab)) :
      $p_laboratorio = "";
      $p_laboratorio .= "{";
      foreach ($request->id_examen_lab as $key => $value) :
        if ($request->id_examen_lab[$key] != "") {
          $p_laboratorio .= "{";
          $p_laboratorio .= $request->id_examen_lab[$key] . ",";
          if (intval($request->id_nivel) === 3):
            $p_laboratorio .= "0";
          else:
          $p_laboratorio .= (isset($request->examen_lab_observacion[$key]) && $request->examen_lab_observacion[$key] !="") ? str_replace($search, $replace, $request->examen_lab_observacion[$key]) : "0";
          endif;
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->examen_lab_cantidad[$key]) && $request->examen_lab_cantidad[$key] > 0) ? $request->examen_lab_cantidad[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->miSelectLab[$key]) && $request->miSelectLab[$key] !="") ? $request->miSelectLab[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->descripcion_tipo_muestra[$key]) && $request->descripcion_tipo_muestra[$key] !="") ? $request->descripcion_tipo_muestra[$key] : "0";
          /** EXAMEN PARA HEMOCULTIVO PARA ADULTO */
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_fecha_tiempo_enfermedad[$key]) && $request->des_adulto_fecha_tiempo_enfermedad[$key] !="") ? $request->des_adulto_fecha_tiempo_enfermedad[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_respuesta_ocular[$key]) && $request->des_adulto_respuesta_ocular[$key] !="") ? $request->des_adulto_respuesta_ocular[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_respuesta_verbal[$key]) && $request->des_adulto_respuesta_verbal[$key] !="") ? $request->des_adulto_respuesta_verbal[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_respuesta_motora[$key]) && $request->des_adulto_respuesta_motora[$key] !="") ? $request->des_adulto_respuesta_motora[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_total_escala_glasgow[$key]) && $request->des_adulto_total_escala_glasgow[$key] !="") ? $request->des_adulto_total_escala_glasgow[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_presion_arterial_x[$key]) && $request->des_adulto_presion_arterial_x[$key] !="") ? $request->des_adulto_presion_arterial_x[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_presion_arterial_y[$key]) && $request->des_adulto_presion_arterial_y[$key] !="") ? $request->des_adulto_presion_arterial_y[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_frecuencia_cardiaca[$key]) && $request->des_adulto_frecuencia_cardiaca[$key] !="") ? $request->des_adulto_frecuencia_cardiaca[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_frecuencia_respiratorio[$key]) && $request->des_adulto_frecuencia_respiratorio[$key] !="") ? $request->des_adulto_frecuencia_respiratorio[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_temperatura[$key]) && $request->des_adulto_temperatura[$key] !="") ? $request->des_adulto_temperatura[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_creatinina[$key]) && $request->des_adulto_creatinina[$key] !="") ? $request->des_adulto_creatinina[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_diuresis[$key]) && $request->des_adulto_diuresis[$key] !="") ? $request->des_adulto_diuresis[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_procalcitonina[$key]) && $request->des_adulto_procalcitonina[$key] !="") ? $request->des_adulto_procalcitonina[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_pcr[$key]) && $request->des_adulto_pcr[$key] !="") ? $request->des_adulto_pcr[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_fiO2[$key]) && $request->des_adulto_fiO2[$key] !="") ? $request->des_adulto_fiO2[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_leucocitos[$key]) && $request->des_adulto_leucocitos[$key] !="") ? $request->des_adulto_leucocitos[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_plaquetas[$key]) && $request->des_adulto_plaquetas[$key] !="") ? $request->des_adulto_plaquetas[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_bilirrubina[$key]) && $request->des_adulto_bilirrubina[$key] !="") ? $request->des_adulto_bilirrubina[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_ima[$key]) && $request->des_adulto_ima[$key] !="") ? $request->des_adulto_ima[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_icc[$key]) && $request->des_adulto_icc[$key] !="") ? $request->des_adulto_icc[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_evp[$key]) && $request->des_adulto_evp[$key] !="") ? $request->des_adulto_evp[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_ecv[$key]) && $request->des_adulto_ecv[$key] !="") ? $request->des_adulto_ecv[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_demencia[$key]) && $request->des_adulto_demencia[$key] !="") ? $request->des_adulto_demencia[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_dm[$key]) && $request->des_adulto_dm[$key] !="") ? $request->des_adulto_dm[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_diabetes_lesion_organica[$key]) && $request->des_adulto_diabetes_lesion_organica[$key] !="") ? $request->des_adulto_diabetes_lesion_organica[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_hemiplejia[$key]) && $request->des_adulto_hemiplejia[$key] !="") ? $request->des_adulto_hemiplejia[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_epoc[$key]) && $request->des_adulto_epoc[$key] !="") ? $request->des_adulto_epoc[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_patologia_tejido_conectivo[$key]) && $request->des_adulto_patologia_tejido_conectivo[$key] !="") ? $request->des_adulto_patologia_tejido_conectivo[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_enfermedad_ulcerosa[$key]) && $request->des_adulto_enfermedad_ulcerosa[$key] !="") ? $request->des_adulto_enfermedad_ulcerosa[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_patologia_renal_moderada_o_grave[$key]) && $request->des_adulto_patologia_renal_moderada_o_grave[$key] !="") ? $request->des_adulto_patologia_renal_moderada_o_grave[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_neoplasias[$key]) && $request->des_adulto_neoplasias[$key] !="") ? $request->des_adulto_neoplasias[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_neoplasias_especifique[$key]) && $request->des_adulto_neoplasias_especifique[$key] !="") ? $request->des_adulto_neoplasias_especifique[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_leucemias[$key]) && $request->des_adulto_leucemias[$key] !="") ? $request->des_adulto_leucemias[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_leucemias_especifique[$key]) && $request->des_adulto_leucemias_especifique[$key] !="") ? $request->des_adulto_leucemias_especifique[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_linfomas_malignos[$key]) && $request->des_adulto_linfomas_malignos[$key] !="") ? $request->des_adulto_linfomas_malignos[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_metastasis_colida[$key]) && $request->des_adulto_metastasis_colida[$key] !="") ? $request->des_adulto_metastasis_colida[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_sida[$key]) && $request->des_adulto_sida[$key] !="") ? $request->des_adulto_sida[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_antecedentes_patologicos_otros_1[$key]) && $request->des_adulto_antecedentes_patologicos_otros_1[$key] !="") ? $request->des_adulto_antecedentes_patologicos_otros_1[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_antecedentes_patologicos_otros_1_especifique[$key]) && $request->des_adulto_antecedentes_patologicos_otros_1_especifique[$key] !="") ? $request->des_adulto_antecedentes_patologicos_otros_1_especifique[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_antecedentes_patologicos_otros_2[$key]) && $request->des_adulto_antecedentes_patologicos_otros_2[$key] !="") ? $request->des_adulto_antecedentes_patologicos_otros_2[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_antecedentes_patologicos_otros_2_especifique[$key]) && $request->des_adulto_antecedentes_patologicos_otros_2_especifique[$key] !="") ? $request->des_adulto_antecedentes_patologicos_otros_2_especifique[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_terapia_antimicrobiana[$key]) && $request->des_adulto_terapia_antimicrobiana[$key] !="") ? $request->des_adulto_terapia_antimicrobiana[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_frasco_1[$key]) && $request->des_adulto_frasco_1[$key] !="") ? $request->des_adulto_frasco_1[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_frasco_1_opcion_1[$key]) && $request->des_adulto_frasco_1_opcion_1[$key] !="") ? $request->des_adulto_frasco_1_opcion_1[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_frasco_1_opcion_2[$key]) && $request->des_adulto_frasco_1_opcion_2[$key] !="") ? $request->des_adulto_frasco_1_opcion_2[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_frasco_2[$key]) && $request->des_adulto_frasco_2[$key] !="") ? $request->des_adulto_frasco_2[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_frasco_2_opcion_1[$key]) && $request->des_adulto_frasco_2_opcion_1[$key] !="") ? $request->des_adulto_frasco_2_opcion_1[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_frasco_2_opcion_2[$key]) && $request->des_adulto_frasco_2_opcion_2[$key] !="") ? $request->des_adulto_frasco_2_opcion_2[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_frasco_3[$key]) && $request->des_adulto_frasco_3[$key] !="") ? $request->des_adulto_frasco_3[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_frasco_3_opcion_1[$key]) && $request->des_adulto_frasco_3_opcion_1[$key] !="") ? $request->des_adulto_frasco_3_opcion_1[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_frasco_3_opcion_2[$key]) && $request->des_adulto_frasco_3_opcion_2[$key] !="") ? $request->des_adulto_frasco_3_opcion_2[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_hemocultivo_seguimiento_y_vigilancia[$key]) && $request->des_adulto_hemocultivo_seguimiento_y_vigilancia[$key] !="") ? $request->des_adulto_hemocultivo_seguimiento_y_vigilancia[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_adulto_fecha_primer_resultado_hemocultivo[$key]) && $request->des_adulto_fecha_primer_resultado_hemocultivo[$key] !="") ? $request->des_adulto_fecha_primer_resultado_hemocultivo[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->flag_solicitud_examen_especializados_hemocultivo_adulto[$key]) && $request->flag_solicitud_examen_especializados_hemocultivo_adulto[$key] !="") ? $request->flag_solicitud_examen_especializados_hemocultivo_adulto[$key] : "0";
          /******************************************************* EXAMEN PARA HEMOCULTIVO PARA NEONATO Y PEDRIATRÍA *******************************************************/
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->descripcion_examen[$key]) && $request->descripcion_examen[$key] !="") ? $request->descripcion_examen[$key] : "0"; /** NOMBRE O DESCRIPCIÓN DEL EXAMEN ESPECIALIZADO */
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_fecha_tiempo_enfermedad[$key]) && $request->des_pedriatria_fecha_tiempo_enfermedad[$key] !="") ? $request->des_pedriatria_fecha_tiempo_enfermedad[$key] : "0";
          /** EDAD GESTIONAL **/
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_postermino[$key]) && $request->des_pedriatria_postermino[$key] !="") ? $request->des_pedriatria_postermino[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_termino[$key]) && $request->des_pedriatria_termino[$key] !="") ? $request->des_pedriatria_termino[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_prematuro_leve[$key]) && $request->des_pedriatria_prematuro_leve[$key] !="") ? $request->des_pedriatria_prematuro_leve[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_prematuro_moderado[$key]) && $request->des_pedriatria_prematuro_moderado[$key] !="") ? $request->des_pedriatria_prematuro_moderado[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_prematuro_extremo[$key]) && $request->des_pedriatria_prematuro_extremo[$key] !="") ? $request->des_pedriatria_prematuro_extremo[$key] : "0";
          /** APGAR **/
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_apariencia[$key]) && $request->des_pedriatria_apariencia[$key] !="") ? $request->des_pedriatria_apariencia[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_pulso[$key]) && $request->des_pedriatria_pulso[$key] !="") ? $request->des_pedriatria_pulso[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_gesto[$key]) && $request->des_pedriatria_gesto[$key] !="") ? $request->des_pedriatria_gesto[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_actividad_respiratoria[$key]) && $request->des_pedriatria_actividad_respiratoria[$key] !="") ? $request->des_pedriatria_actividad_respiratoria[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_total_apgar[$key]) && $request->des_pedriatria_total_apgar[$key] !="") ? $request->des_pedriatria_total_apgar[$key] : "0";
          /** ESCALA DE GLASGOW **/
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_respuesta_ocular[$key]) && $request->des_pedriatria_respuesta_ocular[$key] !="") ? $request->des_pedriatria_respuesta_ocular[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_respuesta_verbal[$key]) && $request->des_pedriatria_respuesta_verbal[$key] !="") ? $request->des_pedriatria_respuesta_verbal[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_respuesta_motora[$key]) && $request->des_pedriatria_respuesta_motora[$key] !="") ? $request->des_pedriatria_respuesta_motora[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_total_escala_glasgow[$key]) && $request->des_pedriatria_total_escala_glasgow[$key] !="") ? $request->des_pedriatria_total_escala_glasgow[$key] : "0";
          /** SIGNOS VITALES **/
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_presion_arterial_x[$key]) && $request->des_pedriatria_presion_arterial_x[$key] !="") ? $request->des_pedriatria_presion_arterial_x[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_presion_arterial_y[$key]) && $request->des_pedriatria_presion_arterial_y[$key] !="") ? $request->des_pedriatria_presion_arterial_y[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_frecuencia_cardiaca[$key]) && $request->des_pedriatria_frecuencia_cardiaca[$key] !="") ? $request->des_pedriatria_frecuencia_cardiaca[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_frecuencia_respiratorio[$key]) && $request->des_pedriatria_frecuencia_respiratorio[$key] !="") ? $request->des_pedriatria_frecuencia_respiratorio[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_temperatura[$key]) && $request->des_pedriatria_temperatura[$key] !="") ? $request->des_pedriatria_temperatura[$key] : "0";
          /** EXÁMENES DE LABORATORIO **/
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_creatinina[$key]) && $request->des_pedriatria_creatinina[$key] !="") ? $request->des_pedriatria_creatinina[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_diuresis[$key]) && $request->des_pedriatria_diuresis[$key] !="") ? $request->des_pedriatria_diuresis[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_procalcitonina[$key]) && $request->des_pedriatria_procalcitonina[$key] !="") ? $request->des_pedriatria_procalcitonina[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_pcr[$key]) && $request->des_pedriatria_pcr[$key] !="") ? $request->des_pedriatria_pcr[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_fiO2[$key]) && $request->des_pedriatria_fiO2[$key] !="") ? $request->des_pedriatria_fiO2[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_leucocitos[$key]) && $request->des_pedriatria_leucocitos[$key] !="") ? $request->des_pedriatria_leucocitos[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_plaquetas[$key]) && $request->des_pedriatria_plaquetas[$key] !="") ? $request->des_pedriatria_plaquetas[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_bilirrubina[$key]) && $request->des_pedriatria_bilirrubina[$key] !="") ? $request->des_pedriatria_bilirrubina[$key] : "0";
          
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_glucosa[$key]) && $request->des_pedriatria_glucosa[$key] !="") ? $request->des_pedriatria_glucosa[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_alt[$key]) && $request->des_pedriatria_alt[$key] !="") ? $request->des_pedriatria_alt[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_vcg[$key]) && $request->des_pedriatria_vcg[$key] !="") ? $request->des_pedriatria_vcg[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_tp[$key]) && $request->des_pedriatria_tp[$key] !="") ? $request->des_pedriatria_tp[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_sodio[$key]) && $request->des_pedriatria_sodio[$key] !="") ? $request->des_pedriatria_sodio[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_potasio[$key]) && $request->des_pedriatria_potasio[$key] !="") ? $request->des_pedriatria_potasio[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_calcio[$key]) && $request->des_pedriatria_calcio[$key] !="") ? $request->des_pedriatria_calcio[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_pco2[$key]) && $request->des_pedriatria_pco2[$key] !="") ? $request->des_pedriatria_pco2[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_tpta[$key]) && $request->des_pedriatria_tpta[$key] !="") ? $request->des_pedriatria_tpta[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_examen_laboratorio_otros[$key]) && $request->des_pedriatria_examen_laboratorio_otros[$key] !="") ? $request->des_pedriatria_examen_laboratorio_otros[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_examen_laboratorio_otros_especifique[$key]) && $request->des_pedriatria_examen_laboratorio_otros_especifique[$key] !="") ? $request->des_pedriatria_examen_laboratorio_otros_especifique[$key] : "0";
          /** TERAPIA ANTIMICROBIANA **/
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_terapia_antimicrobiana[$key]) && $request->des_pedriatria_terapia_antimicrobiana[$key] !="") ? $request->des_pedriatria_terapia_antimicrobiana[$key] : "0";
          /** SOLICITUD DE FRASCO DE HEMOCULTIVO **/
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_frasco_1[$key]) && $request->des_pedriatria_frasco_1[$key] !="") ? $request->des_pedriatria_frasco_1[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_frasco_1_opcion_1[$key]) && $request->des_pedriatria_frasco_1_opcion_1[$key] !="") ? $request->des_pedriatria_frasco_1_opcion_1[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_frasco_1_opcion_2[$key]) && $request->des_pedriatria_frasco_1_opcion_2[$key] !="") ? $request->des_pedriatria_frasco_1_opcion_2[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_frasco_2[$key]) && $request->des_pedriatria_frasco_2[$key] !="") ? $request->des_pedriatria_frasco_2[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_frasco_2_opcion_1[$key]) && $request->des_pedriatria_frasco_2_opcion_1[$key] !="") ? $request->des_pedriatria_frasco_2_opcion_1[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_frasco_2_opcion_2[$key]) && $request->des_pedriatria_frasco_2_opcion_2[$key] !="") ? $request->des_pedriatria_frasco_2_opcion_2[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_frasco_3[$key]) && $request->des_pedriatria_frasco_3[$key] !="") ? $request->des_pedriatria_frasco_3[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_frasco_3_opcion_1[$key]) && $request->des_pedriatria_frasco_3_opcion_1[$key] !="") ? $request->des_pedriatria_frasco_3_opcion_1[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_frasco_3_opcion_2[$key]) && $request->des_pedriatria_frasco_3_opcion_2[$key] !="") ? $request->des_pedriatria_frasco_3_opcion_2[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_hemocultivo_seguimiento_y_vigilancia[$key]) && $request->des_pedriatria_hemocultivo_seguimiento_y_vigilancia[$key] !="") ? $request->des_pedriatria_hemocultivo_seguimiento_y_vigilancia[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_fecha_primer_resultado_hemocultivo[$key]) && $request->des_pedriatria_fecha_primer_resultado_hemocultivo[$key] !="") ? $request->des_pedriatria_fecha_primer_resultado_hemocultivo[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->flag_solicitud_examen_especializados_hemocultivo_pedriatria[$key]) && $request->flag_solicitud_examen_especializados_hemocultivo_pedriatria[$key] !="") ? $request->flag_solicitud_examen_especializados_hemocultivo_pedriatria[$key] : "0";
          $p_laboratorio .= ",";
          // ====================== SOLICITUD PARA ESTUDIO CITOQUÍMICO LÍQUIDOS BIOLÓGICOS  ===============================================
          $p_laboratorio .= (isset($request->des_antecedentes_patologicos_personales[$key]) && $request->des_antecedentes_patologicos_personales[$key] !="") ? $request->des_antecedentes_patologicos_personales[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_antecedentes_patologicos_familiares[$key]) && $request->des_antecedentes_patologicos_familiares[$key] !="") ? $request->des_antecedentes_patologicos_familiares[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_liquido_cefaloraquideo[$key]) && $request->des_liquido_cefaloraquideo[$key] !="") ? $request->des_liquido_cefaloraquideo[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_liquido_pleural[$key]) && $request->des_liquido_pleural[$key] !="") ? $request->des_liquido_pleural[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_liquido_ascitico[$key]) && $request->des_liquido_ascitico[$key] !="") ? $request->des_liquido_ascitico[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_liquido_sinovial[$key]) && $request->des_liquido_sinovial[$key] !="") ? $request->des_liquido_sinovial[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_liquido_otro[$key]) && $request->des_liquido_otro[$key] !="") ? $request->des_liquido_otro[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_liquido_otro_especifique[$key]) && $request->des_liquido_otro_especifique[$key] !="") ? $request->des_liquido_otro_especifique[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_num_frasco_muestra_opcion[$key]) && $request->des_num_frasco_muestra_opcion[$key] !="") ? $request->des_num_frasco_muestra_opcion[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->flag_solicitud_citoquimicos_liquidos_biologicos[$key]) && $request->flag_solicitud_citoquimicos_liquidos_biologicos[$key] !="") ? $request->flag_solicitud_citoquimicos_liquidos_biologicos[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_pedriatria_tono_muscular[$key]) && $request->des_pedriatria_tono_muscular[$key] !="") ? $request->des_pedriatria_tono_muscular[$key] : "0";
          /******************************************************* SOLICITUD TEST DE TOLERANCIA A LA GLUCOSA ORAL *******************************************************/
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_tolerancia_paciente_pediatrico_ttgo[$key]) && $request->des_tolerancia_paciente_pediatrico_ttgo[$key] !="") ? $request->des_tolerancia_paciente_pediatrico_ttgo[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_fecha_tolerancia_paciente_pediatrico_ttgo[$key]) && $request->des_fecha_tolerancia_paciente_pediatrico_ttgo[$key] !="") ? $request->des_fecha_tolerancia_paciente_pediatrico_ttgo[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_tolerancia_paciente_pediatrico_glucosa_anhidra[$key]) && $request->des_tolerancia_paciente_pediatrico_glucosa_anhidra[$key] !="") ? $request->des_tolerancia_paciente_pediatrico_glucosa_anhidra[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_cantidad_tolerancia_paciente_pediatrico_glucosa_anhidra[$key]) && $request->des_cantidad_tolerancia_paciente_pediatrico_glucosa_anhidra[$key] !="") ? $request->des_cantidad_tolerancia_paciente_pediatrico_glucosa_anhidra[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_tolerancia_paciente_adulto_ttgo[$key]) && $request->des_tolerancia_paciente_adulto_ttgo[$key] !="") ? $request->des_tolerancia_paciente_adulto_ttgo[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_fecha_tolerancia_paciente_adulto_ttgo[$key]) && $request->des_fecha_tolerancia_paciente_adulto_ttgo[$key] !="") ? $request->des_fecha_tolerancia_paciente_adulto_ttgo[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_tolerancia_paciente_adulto_glucosa_anhidra[$key]) && $request->des_tolerancia_paciente_adulto_glucosa_anhidra[$key] !="") ? $request->des_tolerancia_paciente_adulto_glucosa_anhidra[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_cantidad_tolerancia_paciente_adulto_glucosa_anhidra[$key]) && $request->des_cantidad_tolerancia_paciente_adulto_glucosa_anhidra[$key] !="") ? $request->des_cantidad_tolerancia_paciente_adulto_glucosa_anhidra[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_g[$key]) && $request->des_tolerancia_paciente_gestante_g[$key] !="") ? $request->des_tolerancia_paciente_gestante_g[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_p_1[$key]) && $request->des_tolerancia_paciente_gestante_p_1[$key] !="") ? $request->des_tolerancia_paciente_gestante_p_1[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_p_2[$key]) && $request->des_tolerancia_paciente_gestante_p_2[$key] !="") ? $request->des_tolerancia_paciente_gestante_p_2[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_p_3[$key]) && $request->des_tolerancia_paciente_gestante_p_3[$key] !="") ? $request->des_tolerancia_paciente_gestante_p_3[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_p_4[$key]) && $request->des_tolerancia_paciente_gestante_p_4[$key] !="") ? $request->des_tolerancia_paciente_gestante_p_4[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_edad_gestacional_meses[$key]) && $request->des_tolerancia_paciente_gestante_edad_gestacional_meses[$key] !="") ? $request->des_tolerancia_paciente_gestante_edad_gestacional_meses[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_edad_gestacional_semanas[$key]) && $request->des_tolerancia_paciente_gestante_edad_gestacional_semanas[$key] !="") ? $request->des_tolerancia_paciente_gestante_edad_gestacional_semanas[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_edad_gestacional_dias[$key]) && $request->des_tolerancia_paciente_gestante_edad_gestacional_dias[$key] !="") ? $request->des_tolerancia_paciente_gestante_edad_gestacional_dias[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_ttgo[$key]) && $request->des_tolerancia_paciente_gestante_ttgo[$key] !="") ? $request->des_tolerancia_paciente_gestante_ttgo[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_fecha_tolerancia_paciente_gestante_ttgo[$key]) && $request->des_fecha_tolerancia_paciente_gestante_ttgo[$key] !="") ? $request->des_fecha_tolerancia_paciente_gestante_ttgo[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_glucosa_anhidra[$key]) && $request->des_tolerancia_paciente_gestante_glucosa_anhidra[$key] !="") ? $request->des_tolerancia_paciente_gestante_glucosa_anhidra[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_cantidad_tolerancia_paciente_gestante_glucosa_anhidra[$key]) && $request->des_cantidad_tolerancia_paciente_gestante_glucosa_anhidra[$key] !="") ? $request->des_cantidad_tolerancia_paciente_gestante_glucosa_anhidra[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_tolerancia_paciente_gestante_factores_riesgo[$key]) && $request->des_tolerancia_paciente_gestante_factores_riesgo[$key] !="") ? $request->des_tolerancia_paciente_gestante_factores_riesgo[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->des_especifique_tolerancia_paciente_gestante_factores_riesgo[$key]) && $request->des_especifique_tolerancia_paciente_gestante_factores_riesgo[$key] !="") ? $request->des_especifique_tolerancia_paciente_gestante_factores_riesgo[$key] : "0";
          $p_laboratorio .= ",";
          $p_laboratorio .= (isset($request->flag_solicitud_test_tolerancia_glucosa_oral[$key]) && $request->flag_solicitud_test_tolerancia_glucosa_oral[$key] !="") ? $request->flag_solicitud_test_tolerancia_glucosa_oral[$key] : "0";
          $p_laboratorio .= "},";
        }
      endforeach;
      if (strlen($p_laboratorio) > 1) $p_laboratorio = substr($p_laboratorio, 0, -1);
      $p_laboratorio .= "}";
      if($request->id_examen_lab[0]!=""):
        if (intval($request->id_establecimiento) === 76):
        $has_observacion_unica = (intval($request->id_nivel) === 3) ? 1 : 0;
        $id_evaluacion = 0;
        $id_interconsulta = 0;
        $id_area = 0;
        $lab_orden_observacion_unica = intval($request->id_nivel) === 3 ? $request->lab_orden_observacion : null;
        $flag_cq = (isset($request->id_perfil_preoperatorio) && intval($request->id_perfil_preoperatorio) > 0) ? 1 : 0;
        $id_hora_toma_muestra = (intval($request->id_nivel) === 3 && isset($request->id_hora_toma_muestra)) ? $request->id_hora_toma_muestra : null;

        $id_procedimiento_lab = $model_procedimiento->RegistrarLaboratorioOrden3($request->id_user, $id_prestacion, $request->id_establecimiento, $request->fecha_hora_atencion, $p_laboratorio, $has_observacion_unica, $id_evaluacion, $request->id_farmacia, $id_interconsulta, $request->id_tipo_atencion, $request->id_servicio_encargado, $id_area, $request->id_cama_atencion, $request->id_medico, $request->id_asegurado, $lab_orden_observacion_unica, $flag_cq, $id_hora_toma_muestra);
        else:
        $id_procedimiento_lab = $model_procedimiento->RegistrarProcedimiento2($request->id_user, $id_prestacion, $request->id_establecimiento, $request->fecha_atencion, $p_laboratorio, 2, 0, $request->id_farmacia);
        endif;
      endif;
    endif;
    /*function escape_pg_value($value) {
    if ($value === null || $value === '') return 'NULL';
    if (is_numeric($value)) return $value;
    $escaped = str_replace(['\\', '"'], ['\\\\', '\\"'], $value);
    return '"' . $escaped . '"';
    }*/
    if (isset($request->id_examen_rad)) :
      $p_radiologia = "";
      $p_radiologia .= "{";
      foreach ($request->id_examen_rad as $key => $value) :
        if ($request->id_examen_rad[$key] != "") {
          $p_radiologia .= "{";
          $p_radiologia .= $request->id_examen_rad[$key] . ",";
          $p_radiologia .= (isset($request->examen_rad_cantidad[$key]) && $request->examen_rad_cantidad[$key] > 0) ? $request->examen_rad_cantidad[$key] : "0";
          $p_radiologia .= ",";
          $p_radiologia .= (isset($request->examen_rad_observacion[$key]) && $request->examen_rad_observacion[$key] !="") ? str_replace($search, $replace, $request->examen_rad_observacion[$key]) : 'NULL';
          $p_radiologia .= ",";
          $p_radiologia .= (isset($request->id_comparativo[$key]) && $request->id_comparativo[$key] !="") ? $request->id_comparativo[$key] : "0";
          $p_radiologia .= ",";
          $p_radiologia .= (isset($request->id_lado[$key]) && $request->id_lado[$key] !="") ? $request->id_lado[$key] : "0";
          $p_radiologia .= ",";
          $p_radiologia .= (isset($request->miSelectRad[$key]) && $request->miSelectRad[$key] !="") ? $request->miSelectRad[$key] : "0";
          $p_radiologia .= ",";
          $p_radiologia .= (isset($request->radio_portatil[$key]) && $request->radio_portatil[$key] !="") ? $request->radio_portatil[$key] : "0";
          $p_radiologia .= "},";
        }
      endforeach;
      if (strlen($p_radiologia) > 1) $p_radiologia = substr($p_radiologia, 0, -1);
      $p_radiologia .= "}";
      if($request->id_examen_rad[0]!=""):
        $id_procedimiento_rad = $model_procedimiento->RegistrarProcedimientoImagenes($request->id_user, $id_prestacion, $request->id_establecimiento, $request->fecha_atencion, $p_radiologia, 3, 0, $request->id_farmacia);
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Identificar radiología a la cual pertenece 
        if (intval($request->id_establecimiento) == 76) {
          $updated = DB::table('rua_imagenologia')->where('id', $request->id_rua_imagenes)->update(['id_rad' => $id_procedimiento_rad, 'id_estado_reg' => 1]);
        }
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      endif;
    endif;
    /***************************************************************************/
    $date = Carbon::now();
    $hora=$date->toTimeString(); 

    if($hora>'01:30' & $hora<'07:31'):
      /*echo*/ $turno='MD';
    else:
      if($hora>'07:30' & $hora<'13:31'):
        /*echo*/ $turno='M';
      else:
        if($hora>'13:30' & $hora<'19:31'):
          /*echo*/ $turno='T';
        else:
          if($hora>'19:30' & $hora<='23:59'):
            /*echo*/ $turno='N';
          else:
            if($hora>='00:00' & $hora<='01:31'):
              /*echo*/ $turno='N';
            endif;
          endif;
        endif;
      endif;
    endif;
    
    // $search  = array('+', ',','"','/');
    // $replace = array('\+', '\,', '\"', '\/');
    
    $model_interconsulta = new \App\Models\Interconsulta;
      //if($request->id_servicio_interconsulta!=null):
      //  $p_interconsulta = "";
      //  $p_interconsulta .= "{";
      //  foreach ($request->id_servicio_interconsulta as $key => $value) :
      //    if ($request->id_servicio_interconsulta[$key] != "") {
      //      $p_interconsulta .= "{";
      //      $p_interconsulta .= $request->id_servicio_interconsulta[$key] . ",";
      //      $p_interconsulta .= $turno . ",";
      //      $p_interconsulta .= "0,";
      //      $p_interconsulta .= trim($request->motivo_interconsulta[$key]) != "" ? str_replace($search, $replace, $request->motivo_interconsulta[$key]) : "\"\"";
      //      $p_interconsulta .= "},";
      //    }
      //  endforeach;
      //  if (strlen($p_interconsulta) > 1) $p_interconsulta = substr($p_interconsulta, 0, -1);
      //  $p_interconsulta .= "}"; 

      //  if($request->id_servicio_interconsulta[0]!=""):
      //    $id_interconsulta = $model_interconsulta->RegistrarInterconsulta($id_prestacion, $request->id_asegurado, $request->id_tipo_atencion, $request->fecha_atencion, $request->id_medico, $request->id_establecimiento, $p_interconsulta);
      //  endif;
      //endif;
      /* if (!empty($request->id_servicio_interconsulta)) {

        // Quitar duplicados de interconsultas en backend
        $id_servicio_interconsulta_unicos = [];
        $hidden_select_otorrino_unicos = [];  // Codigo UPSS
        $motivo_interconsulta_unicos = [];

        foreach ($request->id_servicio_interconsulta as $index => $id_servicio) {
            if (!in_array($id_servicio, $id_servicio_interconsulta_unicos)) {
                $id_servicio_interconsulta_unicos[] = $id_servicio;
                $hidden_select_otorrino_unicos[] = $request->hidden_select_OTORRINO[$index];
                $motivo_interconsulta_unicos[] = $request->motivo_interconsulta[$index];
            }
        }
        // Fin Quitar duplicados de interconsultas en backend

        $elementos = [];
    
        foreach ($id_servicio_interconsulta_unicos as $key => $value) {
            if (!empty($value)) {
                // Escapa los caracteres especiales en el motivo de la interconsulta
                $motivo_interconsulta = str_replace($search, $replace, $motivo_interconsulta_unicos[$key]);  
                $motivo_interconsulta = addslashes($motivo_interconsulta); // Evita problemas con comillas
                $hidden_select_OTORRINO = isset($hidden_select_otorrino_unicos[$key]) ? addslashes($hidden_select_otorrino_unicos[$key]) : '';

                // Agrega el elemento en formato de array PostgreSQL
                $elementos[] = sprintf('{"%s","%s","%s","%s","%s"}', $value, $turno, "0", $motivo_interconsulta, $hidden_select_OTORRINO);
            }
        }
    
        // Une los elementos del array
        if (!empty($elementos)) {
          $p_interconsulta = '{' . implode(',', $elementos) . '}';
    
          // Valida que al menos hay un servicio antes de registrar
          if (!empty($request->id_servicio_interconsulta[0])) {
              $id_interconsulta = $model_interconsulta->RegistrarInterconsulta(
                  $request->id_prestacion, 
                  $asegurado->id, 
                  $request->id_tipo_atencion, 
                  $request->fecha_atencion, 
                  $request->id_medico, 
                  $request->id_establecimiento, 
                  $p_interconsulta 
              );
          }
        }  
    } */
    if (!empty($request->id_servicio_interconsulta)) {

      // Quitar duplicados de interconsultas en backend
      $id_servicio_interconsulta_unicos = [];
      $hidden_select_otorrino_unicos = [];      // Código UPSS para OTORRINO
      $hidden_select_diagnostico_unicos = [];     // Código UPSS para DIAGNOSTICO
      $motivo_interconsulta_unicos = [];
      $personal_coordinacion_unicos = [];

      foreach ($request->id_servicio_interconsulta as $index => $id_servicio) {
          if (!in_array($id_servicio, $id_servicio_interconsulta_unicos)) {
              $id_servicio_interconsulta_unicos[] = $id_servicio;
              $hidden_select_otorrino_unicos[] = $request->hidden_select_OTORRINO[$index];
              $hidden_select_diagnostico_unicos[] = $request->hidden_select_DIAGNOSTICO[$index];
              $motivo_interconsulta_unicos[] = $request->motivo_interconsulta[$index];
              $personal_coordinacion = $request->interconsulta_personal_coodinacion[$index];
              $personal_coordinacion_unicos[] = $personal_coordinacion != "" ? $personal_coordinacion : "NULL";
          }
      }
      // Fin quitar duplicados de interconsultas en backend

      $elementos = [];

      foreach ($id_servicio_interconsulta_unicos as $key => $value) {
          if (!empty($value)) {
              // Escapa los caracteres especiales en el motivo de la interconsulta
              $motivo_interconsulta = str_replace($search, $replace, $motivo_interconsulta_unicos[$key]);
              $motivo_interconsulta = addslashes($motivo_interconsulta); // Evita problemas con comillas

              $hidden_select_OTORRINO = isset($hidden_select_otorrino_unicos[$key])
                  ? addslashes($hidden_select_otorrino_unicos[$key])
                  : '';
              $hidden_select_DIAGNOSTICO = isset($hidden_select_diagnostico_unicos[$key])
                  ? addslashes($hidden_select_diagnostico_unicos[$key])
                  : '';

              // Agrega el elemento en formato de array PostgreSQL con 6 valores:
              // [id_servicio, turno, "0", motivo_interconsulta, hidden_select_OTORRINO, hidden_select_DIAGNOSTICO]
              $elementos[] = sprintf(
                  '{"%s","%s","%s","%s","%s","%s","%s","%s"}',
                  $value,
                  $turno,
                  "0",
                  $motivo_interconsulta,
                  $hidden_select_OTORRINO,
                  $hidden_select_DIAGNOSTICO,
                  $request->is_estable,
                  str_replace($search, $replace, $personal_coordinacion_unicos[$key])
              );
          }
      }

      // Une los elementos del array
      if (!empty($elementos)) {
          $p_interconsulta = '{' . implode(',', $elementos) . '}';

          // Valida que al menos hay un servicio antes de registrar
          if (!empty($id_servicio_interconsulta_unicos)) {
              $id_interconsulta = $model_interconsulta->RegistrarInterconsulta(
                  $request->id_prestacion, 
                  $asegurado->id, 
                  $request->id_tipo_atencion, 
                  $request->fecha_atencion, 
                  $request->id_medico, 
                  $request->id_establecimiento, 
                  $p_interconsulta
              );
          }
      }
  }
    if (isset($request->id_producto)) :
      $receta = new \App\Models\Receta_Vale();
      $id_receta_vale = $receta->postRegistrarRecetaValeNuevoConDiagnostico($id_receta_vale, $request->id_farmacia_receta, $request->id_user, $id_consultorio, $nro_receta, $digitos_adicionales, $dni_beneficiario, $cip_beneficiario, $tipodoc_beneficiario, $nombre_beneficiario, $paterno_beneficiario, $materno_beneficiario, $tipo_beneficiario, $dni_titular, $tipdoc_titular, $nombre_titular, $paterno_titular, $materno_titular, $request->fecha_expedicion, $fecha_atencion, $request->nro_historia, $grado, $id_medico, $id_anestesiologo, $id_tiporeceta, $turno, $obs_registro, $id_receta_vale_bus, $id_admision, $tipo_via, $sub_via, $final_via, $nro_guia, $interconsulta, $interconsulta_dias, $id_prestacion, $id_tipo_num, $estado_receta, $p_producto, $p_diagnostico, $sexo, $fecha_nacimiento, $codigo_upss);
  
    else :
      $id_receta_vale = 1;
    endif;

    if ($id_receta_vale == 0) {
      Session::flash('flash_message', 'No se pudo registrar la examen adicional, hubo un problema con la conexión de internet de su establecimiento PNP, vuelva a intentarlo nuevamente');
      return redirect('/listar_citas_medico_paciente');
    } else {
      Session::flash('flash_message', 'Prestación exitosa');
      return redirect('/ver_prestacion_hospitalizacion/' . $request->id_user . '/' . $id_prestacion);
    }
  }

  public function all_atenciones_by_hospitalizacion($id_user = 0) {
    
    if (!Auth::check()) :
        return redirect('/auth/login');
    else:
      if(Auth::user()->actualizo_passw==0):
        return redirect('auth/password/change');
      endif;
    endif;

    $ruta = "all_atenciones_by_hospitalizacion";
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);

    $permiso = null;
    if (!empty($menu)):
      $permiso = $menu[0];
    endif;

    if (is_null($permiso) || $permiso->lectura == "0"):
      return redirect('/dashboard')->withInput()->withErrors(array('No cuenta con permisos para acceder a esta opción.'));
    endif;

    $id_farmacia = Auth::user()->id_farmacia;

    $date = Carbon::now();
    $fechaHasta = $date->format('d-m-Y');
    $fechaDesdex = $date->format('d-m-Y');
    $fechaDesdey = strtotime('-30 day', strtotime($fechaDesdex));
    $fechaDesde = date('d-m-Y', $fechaDesdey);

    $estado=4; //3 (atencion)
    $opcion='alta_medica';
    $id_tipo_egreso = 0 ;

    $model_upss = new \App\Models\Upss();
    $upss = $model_upss->ShowUPSS_by_Hospitalizacion();
    
    return view('frontend.hospitalizacion.all_atenciones_by_hospitalizacion', compact('id_farmacia', 'fechaDesde', 'fechaHasta','estado','opcion','id_tipo_egreso','upss','permiso'));
  }

  public function listar_atencion_hospitalizacion_ajax(Request $request)
  {
   
    $model_hospitalizacione = new Hospitalizacione();
    $id_establecimiento = Auth::user()->id_establecimiento;
    $estado = $request->estado;
    
    $p[] = $id_establecimiento;
    $p[] = $request->fecha_ini;
    $p[] = $request->fecha_fin;
    $p[] = $request->dni_beneficiario;
    $p[] = strtoupper($request->nombre_beneficiario);
    $p[] = $request->estado;
    $p[] = $request->NumeroPagina;
    $p[] = $request->NumeroRegistros;
    $p[] = $request->id_servicio;

    //if ($id_establecimiento != 75) {
    $data = null;
    if (intval($request->estado) === 4):
      $data = $model_hospitalizacione->listar_hospitalizacion_alta_pendiente($p);
    elseif (intval($request->estado) === 6):
      $p[] = $request->id_tipo_egreso;
      $data = $model_hospitalizacione->listar_atencion_hospitalizacion_ajax($p);
    endif;
    /*} else {
      if ($estado == 4) {
        $p[5] = 1;
      } else if ($estado == 6) {
        $p[5] = 2;
      }
      $data = $model_hospitalizacione->listar_atencion_hospitalizacion_ajax_geriatrico($p);
    }*/

    $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;
    $result["PageStart"] = $request->NumeroPagina;
    $result["pageSize"] = $request->NumeroRegistros;
    $result["SearchText"] = "";
    $result["ShowChildren"] = true;
    $result["iTotalRecords"] = $iTotalDisplayRecords;
    $result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
    $result["aaData"] = $data;
    //dd($data);
    echo json_encode($result);
 
  }

  public function alta_medica($id_user, $id_hospitalizacion, $tipo=1)
  {   

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $ruta = "all_atenciones_by_hospitalizacion";
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);

    $permiso = null;
    if (!empty($menu)):
      $permiso = $menu[0];
    endif;

    if (is_null($permiso) || $permiso->crear == "0"):
      return redirect('/dashboard')->withInput()->withErrors(array('No cuenta con permisos para acceder a esta opción.'));
    endif;
   
    $hospitalizacion_prestacion = Prestaciones::where('id_ext', '=', $id_hospitalizacion)->where('id_tipo_atencion', '=', 3)->where('id_estado_reg', '=', 1)->select('id')->first();
    if(is_object($hospitalizacion_prestacion)):
      $evaluacion="SELECT * from evaluaciones e where id_prestacion = '$hospitalizacion_prestacion->id'";
      $evaluacion = DB::select($evaluacion);
      $evoluci=array();
      if(is_object($evaluacion)){
        $evoluci[0]=$hospitalizacion_prestacion;
        $evoluci[1]=$evaluacion;

      }else{
        $evoluci[0]=$hospitalizacion_prestacion;

      }
    
      //DATOS EMERGENCIA
      $id_establecimiento=Auth::user()->id_establecimiento;
      // $model_emergencia = new Emergencia();
      // $prestacion = $model_emergencia->getIdPrestacion($id_hospitalizacion); // select a prestaciones
      $id_prestacion = $hospitalizacion_prestacion->id;

      $hospitalizacion = Hospitalizacione::where('id', '=', $id_hospitalizacion)->first();
      //$paciente_emergencia = $model_emergencia->GetBuscaEmergenciaTriaje($id_hospitalizacion,$emergencia->estado);

      //$egreso = $model_emergencia->GetEgresoEmergencia($id_hospitalizacion);
      $model_establecimiento = new \App\Models\Establecimiento;
      $model_prestacion = new \App\Models\Prestaciones;
      $model_kardex = new \App\Models\Kardexe();

      // $fechaServidor = $model_kardex->getFechaServidor();
      // $horaservidor = $model_kardex->getHoraServidor();
      
      $date = Carbon::now();
      $fecha_actual = $date->format('d-m-Y');
      $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
      $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacionHospitalizacion($id_prestacion);
      $diagnostico_evolucion = $model_prestacion->GetDiagnosticoByIdPrestacionHospitalizacionEvolucion($id_prestacion);

      $model_hospitalizacione = new Hospitalizacione;
      $ultima_evolucion = Evaluacione::where('id_emergencia', $id_hospitalizacion)->where('id_tipo_atencion', 3)->where('id_estado_reg', 1)->orderBy('created_at', 'desc')->first();
      if ($ultima_evolucion != null)
        $datoadicional = $model_hospitalizacione->GetObtenerCama($ultima_evolucion->cama_estado);
      else
        $datoadicional = $model_hospitalizacione->GetObtenerCama($hospitalizacion->id_cama);

      /*if($prestacion->id_ext!=0):
        if($prestacion->tipo_atencion=='HOSPITALIZACION'):
          $dato_emergencia = Emergencia::where('id', '=', $prestacion->id_ext)->first(); 
          if($dato_emergencia->id_prioridad<4):
            $model_emergencia = new \App\Models\Emergencia;
            $obtenerdato = $model_emergencia->GetObtenerServicioActual($prestacion->id_ext);
            $datoadicional = $model_emergencia->GetObtenerCama($obtenerdato->cama);
            $sw = 1;
            $prestacion->servicio= $obtenerdato->servicio;
            $prestacion->consultorio= $obtenerdato->consultorio;
          else:
            $prestacion->servicio="IV - PATOLOGÍA AGUDA COMÚN";
            $prestacion->consultorio="CONSULTORIO DE ATENCIÓN RÁPIDA (CAR)";
            $sw = 0;
          endif;
        endif;
      else:
          return redirect('/dashboard')->withInput()->withErrors(array('No encontro la atención..!'));
      endif;*/
      $flag_pendinte_firma = ($hospitalizacion->fecha_firma_egreso == "") ? 1 : 0;

      $fechaEgreso = $model_kardex->getFechaServidor();
      $horaEgreso = $model_kardex->getHoraServidor();
      $establecimiento = $model_establecimiento->GetById($id_establecimiento);
      $nivel = $establecimiento[0]->nivel;

      $model_establecimiento = new \App\Models\Establecimiento;
      $establecimientos_traslado = $model_establecimiento->GetEstablecimientosNivel1Y2();

      $model_consultorio = new \App\Models\Consultorio;
      $consulta_externa = $model_consultorio->ShowConsultorioByIdIpressAndIdTipoAtencion($id_establecimiento,1);
      
      $hospitalizacion2= "SELECT 
      concat(u.descripcion_upss,' / ',u.codigo ) as nombre,
      u.id as id_upss,
      u.codigo as codigo_upss,
      c.id as id_consultorio,
      c.nombre as nombre_consultorio
      from consultorios c 
      inner join upsses u on u.id_consultorio = c.id 
      where u.id_tipo_atencion = '3' and u.estado = '1' and u.estado = '1' and u.descripcion_upss like 'H%' order by nombre asc ";
      $hospitalizacion2 = DB::select($hospitalizacion2);
      
      $upss="SELECT 
      concat(u.descripcion_upss,' / ',u.codigo ) as nombre,
      u.id as id_upss,
      u.codigo as codigo_upss,
      c.id as id_consultorio,
      c.nombre as nombre_consultorio
      from consultorios c 
      inner join upsses u on u.id_consultorio = c.id 
      where u.id_tipo_atencion = '3' and u.estado = '1' and u.estado = '1' and u.descripcion_upss like 'H%' order by nombre asc";
      $upss= DB::select($upss);

      $diagnostico_ingreso = DB::table('hospitalizaciones')
        ->join('diagnosticos as dx1','hospitalizaciones.id_diagnostico_inicial','=','dx1.id')
        ->leftJoin('diagnosticos as dx2','hospitalizaciones.id_dx_2','=','dx2.id')
        ->leftJoin('diagnosticos as dx3','hospitalizaciones.id_dx_3','=','dx3.id')
        ->where('hospitalizaciones.id', $id_hospitalizacion)
        ->select('hospitalizaciones.id','dx1.codigo as cod_dx1','dx1.nombre as nombre_dx1','dx2.codigo as cod_dx2','dx2.nombre as nombre_dx2','dx3.codigo as cod_dx3','dx3.nombre as nombre_dx3')
        ->first();

      $modelfarma = new Farmacia;
      $farmacia = $modelfarma->GetFarmaByUser($id_user);
      $id_ipress = $farmacia->id_establecimiento;
      $model_medicos = new Medico;
      $medico = $model_medicos->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni,Auth::user()->id_establecimiento );

      
      $upss_encargado = DB::table('upsses as u')
                      ->join('consultorios as c', 'c.id', '=', 'u.id_consultorio')
                      ->select('u.*', 'c.nombre as nombre_consultorio')
                      ->where('u.id_consultorio', $hospitalizacion->id_servicio_encargado)
                      ->where('u.id_tipo_atencion', 3)
                      ->where('u.estado', 1)
                      ->first();

      $medicoservicio = $model_consultorio->MedicoDetalleServicio($id_ipress,$medico->id);

      $upss_cargo = $upss_encargado->nombre_consultorio;
      $servicio_medico = $medicoservicio->servicio;
      
      $servicios = explode('#|#', $servicio_medico);
      $servicios = array_map('trim', $servicios);
      
      if (!in_array($upss_cargo, $servicios) && !session()->pull('mostrar_error')) {
          session()->put('mostrar_error', true); 
          return redirect()->to('/hospitalizacion_alta_medica/' . $id_user . '/' . $id_hospitalizacion . '/' . $tipo)
              ->with('mensaje_error', 'EL UPSS A CARGO DEL PACIENTE NO COINCIDE CON SU SERVICIO. BAJO SU RESPONSABILIDAD');
      } 

      $procedimientos = $model_prestacion->GetTodosProcedimientosConDiagnosticoByIdAtencion($id_hospitalizacion, 3);
      //VALIDAR MEDICO
      $dni=Auth::user()->dni;
    
      $model_medico = new Medico();
      $exite_medico=$model_medico->exite_medico($dni);
      $exite_medico_ipress=$model_medico->medico_yaregistrado($dni, $id_establecimiento);
      $medico = $model_medico->GetDatosMedicoAndEstablecimiento($dni, $id_establecimiento);
      $id_asegurado= $prestacion->id_asegurado;
      $model_hospitalizacione = new Hospitalizacione();
      //$datos_emergencia = $model_emergencias->GetEmergenciaByIdAsegurado($id_asegurado);
      $datos_hospitalizacion = $model_hospitalizacione->GetDiasEstanciaByIdHospitalizacion($prestacion->id_ext);
      $dia_estancia = $datos_hospitalizacion->dia_estancia;
      $servicios_concurrencia = $model_consultorio->ShowAllConsultoriosSubConsultorioAll($id_establecimiento);

      $data_siteds = DB::table('hospitalizaciones')
                    ->select([
                        'cod_siteds_susalud',
                        'updated_at_cod_siteds',
                        'updated_us_cod_siteds',
                        DB::raw('CASE WHEN updated_at_cod_siteds::DATE != CURRENT_DATE THEN 0 ELSE 1 END AS is_valid_cod_siteds')
                    ])
                    ->where('id', $id_hospitalizacion)
                    ->first();
      
                    
      if($exite_medico==0){
        Session::flash('flash_message', 'Sólo los médicos pueden registrar alta médica');
        return redirect('/all_atenciones_by_hospitalizacion');
      }else if($exite_medico_ipress==""){
        $model_establecimiento = new Establecimiento();
        $establecimiento=  $model_establecimiento->GetById($id_establecimiento);
        Session::flash('flash_message', 'Usted no es Médico en el establecimiento: '. $establecimiento[0]->nombre);
        return redirect('/all_atenciones_by_hospitalizacion');
      }else{
        if ($tipo == 1) 
           return view('frontend.hospitalizacion.alta_medica', compact('farmacia','evoluci','upss','establecimientos_traslado','fechaEgreso','horaEgreso', 'prestacion', 'diagnostico_ingreso', 'diagnostico', 'id_prestacion', 'fechaServidor', 'horaservidor','nivel', 'flag_pendinte_firma','hospitalizacion2','consulta_externa','paciente_emergencia','hospitalizacion','dia_estancia','sw','datoadicional','medico','procedimientos','servicios_concurrencia', 'data_siteds', 'diagnostico_evolucion',
           'upss_cargo','servicio_medico'));
        else
           return view('frontend.hospitalizacion.alta_medica_2', compact('farmacia','evoluci','upss','establecimientos_traslado','fechaEgreso','horaEgreso', 'prestacion', 'diagnostico_ingreso', 'diagnostico', 'id_prestacion', 'fechaServidor', 'horaservidor','nivel', 'flag_pendinte_firma','hospitalizacion2','consulta_externa','paciente_emergencia','hospitalizacion','dia_estancia','sw','datoadicional','medico','procedimientos','servicios_concurrencia', 'data_siteds', 'diagnostico_evolucion',
           'upss_cargo','servicio_medico'));
      } 
    else:
      Session::flash('flash_message', 'Necesita tener una prestacion para dar un Alta Medica');
      return redirect('/all_atenciones_by_hospitalizacion'); 
    endif;
  }

  public function registrar_alta_medica_hospitalizacion(Request $request){
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif; 
    
    $date = Carbon::now();
    $fechaActual = $date->format('d-m-Y');
    $horaActual = $date->format('H:i:s');
    $fecha_cierre = $request->fecha_egreso;
    $hora_cierre = $request->hora_egreso;
    $fecha_cierre = date('Y-m-d', strtotime($fecha_cierre));
    $date = $fecha_cierre;
    $fechaActual = $fecha_cierre;
    $horaActual = $hora_cierre;
    $id_user =  Auth::user()->id;
    $id_hospitalizacion = $request->id_emergencia;

    $model_emergencia = Hospitalizacione::where('id', '=', $request->id_emergencia)->first();
    $fecha_hora_ingreso = $model_emergencia->fecha_atencion;
    $emergencia_prestacion = Prestaciones::where('id_ext', '=', $request->id_emergencia)->where('id_tipo_atencion', '=', 3)->first();

    $existeevolucion = "SELECT count(e.id)  from hospitalizaciones e
                        inner join evaluaciones e2 on e2.id_emergencia = e.id
                        where e.id = '$request->id_emergencia' and e2.id_tipo_atencion = 3 and e2.id_estado_reg <> 0";
    $existeevolucion = DB::select($existeevolucion);
    $existeevolucion = $existeevolucion[0]->count;

    if($existeevolucion == 0){
      // $fecha_hora_ingreso=$model_emergencia->fecha_atencion;
      $id_evaluacion = 0;
    }else{
      $datoevalucion ="SELECT e2.* from hospitalizaciones e
      inner join evaluaciones e2 on e2.id_emergencia = e.id
      where e.id = '$request->id_emergencia' and e2.id_tipo_atencion = 3 and e2.id_estado_reg <> 0 order by e2.created_at desc";
      $datoevalucion = DB::select($datoevalucion);
      // $fecha_hora_ingreso = $datoevalucion[0]->created_at;
      $id_evaluacion = $datoevalucion[0]->id;
    }
  
    $unido = ($fecha_cierre . " " . $hora_cierre);
    $resulta=((strtotime($unido))-(strtotime($fecha_hora_ingreso)))/86400;
 
    if($resulta <= 0){
      Session::flash('flash_message', 'NO SE REGISTRO EL ALTA - LA FECHA DE ALTA INGRESADA '.$unido.' ES MENOR A LA FECHA DE LA ULTIMA ATENCION '.$fecha_hora_ingreso.' - FORMATO DE FECHA 24 HORAS, MODIFIQUE LA FECHA DE ALTA');
      return redirect('/hospitalizacion_alta_medica/'. $id_user . '/' . $request->id_emergencia . '/2'); 
    }
  
    // $dato_triaje_cpms = "SELECT * from triajes where id = '$model_emergencia->id_triaje'";
    // $dato_triaje_cpms= DB::select($dato_triaje_cpms);
    
    $resulta_hora=((strtotime($unido))-(strtotime($fecha_hora_ingreso)))/43200;
    //dd($resulta_hora);
    
    /*if($model_emergencia->id_triaje == "1" || $model_emergencia->id_triaje == "12"){
        if($resulta_hora<1){
          $codigo_cpms = "99234";
        }
        else{
          $codigo_cpms = "99295";
        }
        
    }else if($model_emergencia->id_triaje == "20" ){*/
      $codigo_cpms = "";
    /*}else{
      if($resulta_hora<1){
        $codigo_cpms = "99234";
      }
      else{
        $codigo_cpms = "99231.15";
      }
    }*/
    
    $emergencia_model = new \App\Models\Emergencia;

    /**********Calcula estancia*************/
    $inicio= ($model_emergencia->fechaingreso);
    $final= ($date);
    $inicios= strtotime($model_emergencia->fecha_ingreso);
    $fecha_alta=explode(" ",$date);
    $hora_alta=explode(" ",$horaActual);
    $fecha_finals=$fecha_alta[0].' '.$hora_alta[0];
    $finals= strtotime($fecha_finals);
    $resulta=($finals-$inicios)/86400;
    $resulta=round($resulta);
   
    if($resulta == "0"){
      $dia_estancia = '1';
    }else{
      $dia_estancia=$resulta;
    }

    
    $update_alta=$emergencia_model->InsertaEstancia($request->id_emergencia,$emergencia_prestacion->id,$id_evaluacion,3,$codigo_cpms,$model_emergencia->fecha_atencion,$id_user,$dia_estancia); 
    
    $model_emergencia->cpms_alta = $codigo_cpms;
    $model_emergencia->fecha_salida = $date;
    $model_emergencia->hora_egreso = $horaActual;
    $model_emergencia->fecha_alta_medica = $date;
    $model_emergencia->hora_alta_medica = $horaActual;
    $model_emergencia->id_tipo_egreso = $request->id_tipo_egreso;
    $model_emergencia->flag_egreso = '1';
    $model_emergencia->estado = 6;
    $model_emergencia->id_medico_alta = $id_user;
    $model_emergencia->egreso_resumen = $request->egreso_resumen;
    $model_emergencia->egreso_examen_clinico = $request->egreso_examen_clinico;
    $model_emergencia->egreso_examen_auxiliar = $request->egreso_examen_auxiliar;
    $model_emergencia->egreso_evolucion = $request->egreso_evolucion;
    $model_emergencia->egreso_tratamiento = $request->egreso_tratamiento;
    $model_emergencia->egreso_procedimiento = $request->egreso_procedimiento;
    $model_emergencia->egreso_complicacion = $request->egreso_complicacion;
    $model_emergencia->egreso_medidas_generales = $request->egreso_medidas_generales;
    $model_emergencia->egreso_medicamentos_prescritos = $request->egreso_medicamentos_prescritos;
    $model_emergencia->egreso_pronostico = $request->egreso_pronostico;

    $id_1=0;
    $id_2=0;
    $id_3=0; 
    if (isset($request->id_diagnostico)) :
      foreach ($request->id_diagnostico as $key => $value) : 
        if($key==0 && $request->id_diagnostico[$key] != ""){
          $id_1=$request->id_diagnostico[$key] ; 
        }
        if($key==1 && $request->id_diagnostico[$key] != ""){
          $id_2=$request->id_diagnostico[$key] ; 
        }
        if($key==2 && $request->id_diagnostico[$key] != ""){
          $id_3=$request->id_diagnostico[$key] ; 
        } 
      endforeach;
    endif; 
  
    $model_emergencia->id_diag_cab = $id_1;
    $model_emergencia->id_diag_cuer1 = $id_2;
    $model_emergencia->id_diag_cuer2 = $id_3;
    if($request->id_tipo_egreso == '1'){//alta
      $model_emergencia->id_condicion_alta = $request->id_condicion_alta;
      $model_emergencia->id_establecimiento_traslado = "0";
      $model_emergencia->obs_traslado = '';
      $model_emergencia->especialida_destino = "0";
      $model_emergencia->especialidad_destino_otro = "0";
      $model_emergencia->condicion_traslado = "0";
      $model_emergencia->necropsia = "0";
      $model_emergencia->causas_muerte = "0";
      $model_emergencia->observacion_egreso = '';

      // Registro de los servicios de concurrencia
      if ($request->id_servicio_concurrencia != null):
        foreach ($request->id_servicio_concurrencia as $key => $value):
          $model_hospitalizacion_concurrencia = new App\Models\HospitalizacionConcurrencia;
          $model_hospitalizacion_concurrencia->id_hospitalizacion = $request->id_emergencia;
          $model_hospitalizacion_concurrencia->id_servicio = $request->id_servicio_concurrencia[$key];
          $model_hospitalizacion_concurrencia->id_estado_reg = 1;
          $model_hospitalizacion_concurrencia->created_us = Auth::user()->id;
          $model_hospitalizacion_concurrencia->save();
        endforeach;
      endif;

    }else if($request->id_tipo_egreso == '3'){//traslado
      $model_emergencia->id_condicion_alta = "3";
      if ($request->id_tipo_traslado === '1') {
        $model_emergencia->id_establecimiento_traslado = "0";
      } else if ($request->id_tipo_traslado === '2') {
        $model_emergencia->id_establecimiento_traslado = $request->id_establecimiento_traslado;
      }
      // $model_emergencia->obs_traslado = $request->obs_traslado;
      // $model_emergencia->especialida_destino = $request->especialida_destino;
      // $model_emergencia->especialidad_destino_otro = $request->especialidad_destino_otro;
      // $model_emergencia->condicion_traslado = $request->condicion_traslado;
      $model_emergencia->necropsia = "0";
      $model_emergencia->causas_muerte = "0";
      $model_emergencia->observacion_egreso = '';
      $model_emergencia->id_tipo_traslado = $request->id_tipo_traslado;

    }else if($request->id_tipo_egreso == '4'){//fallecido
      $model_emergencia->id_condicion_alta = "6";
      $model_emergencia->id_establecimiento_traslado = "0";
      $model_emergencia->obs_traslado = '';
      $model_emergencia->especialida_destino = "0";
      $model_emergencia->especialidad_destino_otro = "0";
      $model_emergencia->condicion_traslado = "0";
      $model_emergencia->necropsia = $request->necropsia;
      $model_emergencia->causas_muerte = $request->necropsia == "SI" ? $request->causas_muerte : "";
      $model_emergencia->observacion_egreso = '';
      $model_emergencia->fecha_defuncion = $request->fecha_defuncion;
      $model_emergencia->hora_defuncion = $request->hora_defuncion;

    }else if($request->id_tipo_egreso == '7'){//retiro voluntario
      $model_emergencia->id_condicion_alta = "4";
      $model_emergencia->id_establecimiento_traslado = "0";
      $model_emergencia->obs_traslado = '';
      $model_emergencia->especialida_destino = "0";
      $model_emergencia->especialidad_destino_otro = "0";
      $model_emergencia->condicion_traslado = "0";
      $model_emergencia->necropsia = "0";
      $model_emergencia->causas_muerte = "0";
      $model_emergencia->observacion_egreso = $request->obs_retiro;

    }else if($request->id_tipo_egreso == '6'){//fuga
      $model_emergencia->id_condicion_alta = "5";
      $model_emergencia->id_establecimiento_traslado = "0";
      $model_emergencia->obs_traslado = '';
      $model_emergencia->especialida_destino = "0";
      $model_emergencia->especialidad_destino_otro = "0";
      $model_emergencia->condicion_traslado = "0";
      $model_emergencia->necropsia = "0";
      $model_emergencia->causas_muerte = "0";
      $model_emergencia->observacion_egreso = $request->obs_fuga;

    }/*else{
      $model_emergencia->id_servicio_destino = "0";
      $model_emergencia->id_upss_alta ="0";
      $model_emergencia->obs_traslado = "0";
      $model_emergencia->obs_traslado = "0";
      $model_emergencia->necropsia ="0";
      $model_emergencia->detalle_alta_admin = "0";
      $model_emergencia->causas_muerte = "0";
      $model_emergencia->id_condicion_alta = "1";
      $model_emergencia->condicion_traslado = "0";
      $model_emergencia->personal_acompañante = "0";
      $model_emergencia->especialida_destino = "0";
      $model_emergencia->especialidad_destino_otro = "0";
     
    }*/
    
    /*if($model_emergencia->id_tipo_beneficiario == '12'){
      $model_emergencia->numero_boleta = $request->numeroboleta;
      $model_emergencia->numero_documento_coronel_guardia  = $request->numeroguar;
    }else{
      $model_emergencia->numero_boleta = "0";
      $model_emergencia->numero_documento_coronel_guardia  = "0";
    }*/

    if(isset($model_emergencia->id_cama_estado)){

      $model_cama = Cama::where('id', '=', $model_emergencia->id_cama_estado)->first();
      $model_cama->estado = 0; // 1 - No disponible (Estos datos son los que van a ir a produccion)
      $model_cama->situacion = '1'; // '5' - En Desinfeccion (Estos datos son los que van a ir a produccion)
      $model_cama->save();
    }


    $model_emergencia->cod_siteds_susalud = $request->codigo_siteds;
    $model_emergencia->updated_at_cod_siteds = Carbon::now();
    $model_emergencia->updated_us_cod_siteds = Auth::user()->id;
   
    $model_emergencia->save(); 

    $model_trama = new \App\Models\Hospitalizacione;
    $enviado_dato = $model_trama->enviartrama($id_hospitalizacion);
    
    Session::flash('flash_message', 'Alta Medica exitosa');
    return redirect('/hospitalizacion_ver_alta_medica/'.$id_user.'/'.$id_hospitalizacion);

    // if(is_object($hospitalizacion_prestacion)):
    //   $id_establecimiento=Auth::user()->id_establecimiento;
    //   //$model_emergencia = new Emergencia();
    //   //$prestacion= $model_emergencia->getByIdExtYIdTipoAtencion($request->id_emergencia);
    //   $id_prestacion = $hospitalizacion_prestacion->id;
    //   $emergencia = Hospitalizacione::where('id', '=', $request->id_emergencia)->first();
    //   //$paciente_emergencia = $model_emergencia->GetBuscaEmergenciaTriaje($request->id_emergencia,$emergencia->estado);
    //   //$egreso = $model_emergencia->GetEgresoEmergencia($request->id_emergencia);
    //   $model_establecimiento = new \App\Models\Establecimiento;
    //   $model_prestacion = new \App\Models\Prestaciones;
    //   $model_kardex = new \App\Models\Kardexe();
    //   $fechaServidor = $model_kardex->getFechaServidor();
    //   $horaservidor = $model_kardex->getHoraServidor();
    //   $date = Carbon::now();
    //   $fecha_actual = $date->format('d-m-Y');
    //   $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
    //   $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
    //   $flag_pendinte_firma = ($emergencia->fecha_firma_egreso == "") ? 1 : 0;

    //   if($prestacion->id_ext!=0):
    //     if($prestacion->tipo_atencion=='HOSPITALIZACION'):
          
    //         $model_emergencia = new \App\Models\Emergencia;
    //         //$obtenerdato = $model_emergencia->GetObtenerServicioActual($prestacion->id_ext);
    //         $datoadicional = $model_emergencia->GetObtenerCama($emergencia->id_cama_estado);
    //         $sw = 1;
    //         // $prestacion->servicio= $obtenerdato->servicio;
    //         // $prestacion->consultorio= $obtenerdato->consultorio;

    //     endif;
    //   else:
    //       return redirect('/dashboard')->withInput()->withErrors(array('No encontro la atención..!'));
    //   endif;
      
    //     // $fechaEgreso = $model_kardex->getFechaServidor();
    //     // $horaEgreso = $model_kardex->getHoraServidor();
    //   $establecimiento = $model_establecimiento->GetById($id_establecimiento);
    //   $nivel = $establecimiento[0]->nivel;

    //   if($emergencia->id_tipo_egreso==3){
    //     $estable = "select * from establecimientos e where id = '$emergencia->id_establecimiento_traslado'";
    //     $estable=  DB::select($estable);
    //     $estable = $estable[0];
    //   }
    
    //   $model_consultorio = new \App\Models\Consultorio;
    //   $consulta_externa = $model_consultorio->ShowConsultorioByIdIpressAndIdTipoAtencion($id_establecimiento,1);

    //   $diagnosti= array();
    //   $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$emergencia->id_diag_cab'";
    //   $diagnos = DB::select($diagnos);
    //   $diagnos = $diagnos[0];
    //   $diagnos->cabecera = "PRINCIPAL";
    //   $diagnos->tipo = "DEFINITIVO";
    //   $diagnosti[0]=$diagnos;
    //   if($emergencia->id_diag_cuer1>0){
        
    //       $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$emergencia->id_diag_cuer1'";
    //       $diagnos = DB::select($diagnos);
    //       $diagnos = $diagnos[0];
    //       $diagnos->cabecera = "SECUNDARIO";
    //       $diagnos->tipo = "DEFINITIVO";
    //       $diagnosti[1]=$diagnos;
    //   }elseif($emergencia->id_diag_cuer2>0){
        
    //       $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$emergencia->id_diag_cuer2'";
    //       $diagnos = DB::select($diagnos);
    //       $diagnos = $diagnos[2];
    //       $diagnos->cabecera = "SECUNDARIO";
    //       $diagnos->tipo = "DEFINITIVO";
    //       $diagnosti[2]=$diagnos;
    //   }

    //   $dni=Auth::user()->dni;
    //   $model_medico = new Medico();
    //   $exite_medico=$model_medico->exite_medico($dni);
    //   $model_usuario = new User();
    //   $usuario=$model_usuario->GetById($emergencia->id_medico_alta);
    //   $exite_medico_ipress=$model_medico->medico_yaregistrado($dni, $id_establecimiento);
    //   $medico = $model_medico->GetDatosMedicoAndEstablecimiento($usuario->dni, $usuario->id_establecimiento);
    //   if($exite_medico==0){
    //     Session::flash('flash_message', 'Sólo los médicos pueden registrar alta médica');
    //     return redirect('/all_atenciones_by_hospitalizacion');
    //   }else if($exite_medico_ipress==""){
    //     $model_establecimiento = new Establecimiento();
    //     $establecimiento=  $model_establecimiento->GetById($id_establecimiento);
    //     Session::flash('flash_message', 'Usted no es Médico en el establecimiento: '. $establecimiento[0]->nombre);
    //     return redirect('/all_atenciones_by_hospitalizacion');
    //   }else{
    //     return view('frontend.hospitalizacion.alta_medica_firmar', compact('prestacion','upss','estable', 'diagnosti','diagnostico', 'id_prestacion', 'fechaServidor', 'horaservidor', 'nivel', 'flag_pendinte_firma', 'consulta_externa', 'emergencia','sw','datoadicional','medico' ));
    //   } 
    // else:
    //   Session::flash('flash_message', 'Necesita tener una prestacion para dar un Alta Medica');
    //   return redirect('/all_atenciones_by_hospitalizacion'); 
    // endif;
  }

  public function ver_alta_medica($id_user, $id_hospitalizacion) // $id_emergencia es $id_hospitalizacion
  {   
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
  
    $hospitalizacion_prestacion = Prestaciones::where('id_ext', '=', $id_hospitalizacion)->first();
    if(is_object($hospitalizacion_prestacion)):

      $id_establecimiento=Auth::user()->id_establecimiento;
      $model_prestacion = new Prestaciones();
      $prestacion= $model_prestacion->getByIdExtYIdTipoAtencion($id_hospitalizacion, 3);
      $id_prestacion =  $prestacion->id;
      $hospitalizacion = Hospitalizacione::where('id', '=', $id_hospitalizacion)->first();
      // $paciente_emergencia = $model_emergencia->GetBuscaEmergenciaTriaje($id_hospitalizacion,$emergencia->estado);
      //$egreso = $model_emergencia->GetEgresoEmergencia($id_hospitalizacion);
      $model_establecimiento = new \App\Models\Establecimiento;
      $model_prestacion = new \App\Models\Prestaciones;
      $model_kardex = new \App\Models\Kardexe();
      $fechaServidor = $model_kardex->getFechaServidor();
      $horaservidor = $model_kardex->getHoraServidor();
      $date = Carbon::now();
      $fecha_actual = $date->format('d-m-Y');
      $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
      //$diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
      $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacionHospitalizacion($id_prestacion);
      $diagnostico_evolucion = $model_prestacion->GetDiagnosticoByIdPrestacionHospitalizacionEvolucion($id_prestacion);
      $flag_pendinte_firma = ($hospitalizacion->fecha_firma_egreso == "") ? 1 : 0;

      if($prestacion->id_ext!=0):
        if($prestacion->tipo_atencion=='HOSPITALIZACION'):
          
            $model_emergencia = new \App\Models\Emergencia;
            //$obtenerdato = $model_emergencia->GetObtenerServicioActual($prestacion->id_ext);
            $datoadicional = $model_emergencia->GetObtenerCama($hospitalizacion->id_cama_estado);
            $sw = 1;
          
        endif;
      else:
          return redirect('/dashboard')->withInput()->withErrors(array('No encontro la atención..!'));
      endif;

      $gestacion = null;
      $puerperio = null;
      if (isset($hospitalizacion->id_gestacion)) {
        $gestacion = Gestacion::find($hospitalizacion->id_gestacion);
        error_log('HAY GESTACION EN ALTA: '.(string)$gestacion);
      }
      if (isset($hospitalizacion->id_puerperio)) {
        $puerperio = Puerperio::find($hospitalizacion->id_puerperio);
        error_log('HAY PUERPERIO EN ALTA: '.(string)$puerperio);
        $natis = $puerperio->natis;
        error_log('sus natis: '.(string)$natis);
      }
      
        // $fechaEgreso = $model_kardex->getFechaServidor();
        // $horaEgreso = $model_kardex->getHoraServidor();
      $establecimiento = $model_establecimiento->GetById($id_establecimiento);
      if ($id_establecimiento == 1) {
        $nivel = 2;
      } else {
        $nivel = $establecimiento[0]->nivel;
      }
      $estable = null;
      if($hospitalizacion->id_tipo_egreso==3){
        if ($hospitalizacion->id_tipo_traslado === 2) {
          $estable = "select * from establecimientos e where id = '$hospitalizacion->id_establecimiento_traslado'";
          $estable=  DB::select($estable);
          $estable = $estable[0];
        }
      }
    
      $model_consultorio = new \App\Models\Consultorio;
      $consulta_externa = $model_consultorio->ShowConsultorioByIdIpressAndIdTipoAtencion($id_establecimiento,1);
      $hospitalizacion1 = "select u.descripcion_upss as nombre,
                          u.id,
                          u.codigo 
                          from consultorios c 
                          inner join upsses u on u.id_consultorio = c.id 
                          where u.id_tipo_atencion = '3' and u.estado = '1' and u.descripcion_upss like 'H%'";
      $hospitalizacion1 = DB::select($hospitalizacion1);

      $diagnostico_ingreso = DB::table('hospitalizaciones')
        ->join('diagnosticos as dx1','hospitalizaciones.id_diagnostico_inicial','=','dx1.id')
        ->leftJoin('diagnosticos as dx2','hospitalizaciones.id_dx_2','=','dx2.id')
        ->leftJoin('diagnosticos as dx3','hospitalizaciones.id_dx_3','=','dx3.id')
        ->where('hospitalizaciones.id', $id_hospitalizacion)
        ->select('hospitalizaciones.id','dx1.codigo as cod_dx1','dx1.nombre as nombre_dx1','dx2.codigo as cod_dx2','dx2.nombre as nombre_dx2','dx3.codigo as cod_dx3','dx3.nombre as nombre_dx3')
        ->first();
      
      $diagnosti= array();
//puse esta validacion ya que al dar d ealta a un paciente de tipo DOMICILIARIA el campo id_diag_cab esta null
      if(isset($hospitalizacion->id_diag_cab)){
        $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$hospitalizacion->id_diag_cab'";
        $diagnos = DB::select($diagnos);
        $diagnos = $diagnos[0];
        $diagnos->cabecera = "PRINCIPAL";
        $diagnos->tipo = "DEFINITIVO";
        $diagnosti[0]=$diagnos;

        if ($hospitalizacion->id_diag_cuer1 > 0) {

          $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$hospitalizacion->id_diag_cuer1'";
          $diagnos = DB::select($diagnos);
          $diagnos = $diagnos[0];
          $diagnos->cabecera = "SECUNDARIO";
          $diagnos->tipo = "DEFINITIVO";
          $diagnosti[1] = $diagnos;
        }

        if ($hospitalizacion->id_diag_cuer2 > 0) {

          $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$hospitalizacion->id_diag_cuer2'";
          $diagnos = DB::select($diagnos);
          $diagnos = $diagnos[0];
          $diagnos->cabecera = "SECUNDARIO";
          $diagnos->tipo = "DEFINITIVO";
          $diagnosti[2] = $diagnos;
        }
      }
    
      $dni=Auth::user()->dni;
      $model_medico = new Medico();
      $exite_medico=$model_medico->exite_medico($dni);
      $exite_medico_ipress=$model_medico->medico_yaregistrado($dni, $id_establecimiento);

      $model_usuario = new User();
      $usuario=$model_usuario->GetById($hospitalizacion->id_medico_alta);
      $medico = $model_medico->GetDatosMedicoAndEstablecimiento($usuario->dni, $usuario->id_establecimiento);
      
      if($exite_medico==0){
        Session::flash('flash_message', 'Sólo los médicos pueden registrar alta médica');
        return redirect('/all_atenciones_by_hospitalizacion');
      }else if($exite_medico_ipress==""){
        $model_establecimiento = new Establecimiento();
        $establecimiento=  $model_establecimiento->GetById($id_establecimiento);
        Session::flash('flash_message', 'Usted no es Médico en el establecimiento: '. $establecimiento[0]->nombre);
        return redirect('/all_atenciones_by_hospitalizacion');
      }else{

          return view('frontend.hospitalizacion.ver_alta_medica', compact('prestacion','estable','diagnosti','diagnostico', 'id_prestacion', 'fechaServidor', 'horaservidor', 'nivel', 'flag_pendinte_firma',  'hospitalizacion1', 'consulta_externa', 'hospitalizacion','medico','datoadicional', 'sw' , 'gestacion', 'puerperio', 'natis','diagnostico_ingreso'));

      } 
    else:
      Session::flash('flash_message', 'Necesita tener una prestacion para dar un Alta Medica');
      return redirect('/all_atenciones_by_hospitalizacion'); 
    endif;
  }

  public function buscar_dia_estancia_ajax(Request $request){ 
    $id_asegurado = $request->id_asegurado;
    $fecha_egreso = $request->fecha_egreso;
    $hora_egreso = $request->hora_egreso;
    $model_hospitalizacione= new Hospitalizacione();
    $datos_estancia = $model_hospitalizacione->GetDiasEstancia($id_asegurado,$fecha_egreso,$hora_egreso);
    return json_encode($datos_estancia);    
  }

  public function all_hospitalizaciones_by_alta_administrativa($id_user = 0) {

    if (!Auth::check()) :
        return redirect('/auth/login');
    else:
      if(Auth::user()->actualizo_passw==0):
        return redirect('auth/password/change');
      endif;
    endif;

    // $ruta = Route::getCurrentRoute()->getPath();
    // if (count($ruta = explode("/", $ruta)) > 1)
    //     ;$ruta = $ruta[0];
    // if($ruta=="listar_triaje_emergencia_ajax");$ruta="all_admisiones_atendidas_by_emergencia";
    // $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
    // $permiso = $menu[0];
    // if ($permiso->lectura == "")
    //     return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
    $id_user = Auth::user()->id;
    // $usuario_actual = $model_usuario->GetById($id_user);
    // $flagemergenciareferencia = $usuario_actual->flaghistoria;
    

    $date = Carbon::now();
    $fechaHasta = $date->format('d-m-Y');
    $fechaDesdex = $date->format('d-m-Y');
    $fechaDesdey = strtotime('-30 day', strtotime($fechaDesdex));
    $fechaDesde = date('d-m-Y', $fechaDesdey);
    
    //====================
    $model_farma = new Farmacia();
    $farmacia = $model_farma->GetFarmaByUser($id_user);
    $dni_beneficiario='';
    $nombre_beneficiario='';
    $estado=6;
    //$opcion='alta_medica';
    $id_tipo_egreso = 0 ;
    
    return view('frontend.hospitalizacion.all_hospitalizaciones_by_alta_administrativa', compact('farmacia', 'fechaDesde', 'fechaHasta','dni_beneficiario','nombre_beneficiario','estado','opcion','id_tipo_egreso'));
  }

  public function alta_admin_hospitalizacion($id_user, $id_hospitalizacion)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $hospitalizacion_prestacion = Prestaciones::where('id_ext', '=', $id_hospitalizacion)      
      ->where('id_tipo_atencion', 3)
      ->first();
    if(is_object($hospitalizacion_prestacion)):
      $evaluacion="SELECT * from evaluaciones e where id_prestacion = '$hospitalizacion_prestacion->id'";
      $evaluacion = DB::select($evaluacion);
      $evoluci=array();
      if(is_object($evaluacion)){
        $evoluci[0]=$hospitalizacion_prestacion;
        $evoluci[1]=$evaluacion;
      }else{
        $evoluci[0]=$hospitalizacion_prestacion;
      }
    
      //DATOS EMERGENCIA
      $id_establecimiento=Auth::user()->id_establecimiento;
      $model_emergencia = new Emergencia();
      $prestacion = $model_emergencia->getIdPrestacionHospitalizacion($id_hospitalizacion); // select a prestaciones
      $id_prestacion = $prestacion->id;
      $hospitalizacion = Hospitalizacione::where('id', '=', $id_hospitalizacion)->first();

      $model_establecimiento = new \App\Models\Establecimiento;
      $model_prestacion = new \App\Models\Prestaciones;
      $model_kardex = new \App\Models\Kardexe();
      $date = Carbon::now();
      $fecha_actual = $date->format('d-m-Y');
      $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
      //$diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
      $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacionHospitalizacion($id_prestacion);
      $diagnostico_evolucion = $model_prestacion->GetDiagnosticoByIdPrestacionHospitalizacionEvolucion($id_prestacion);
      $flag_pendinte_firma = ($hospitalizacion->fecha_firma_egreso == "") ? 1 : 0;

      $fechaEgreso = $model_kardex->getFechaServidor();
      $horaEgreso = $model_kardex->getHoraServidor();
      $establecimiento = $model_establecimiento->GetById($id_establecimiento);
      $nivel = $establecimiento[0]->nivel;

      $estable = "select * from establecimientos e where id_estado_reg = '1'";
      $estable=  DB::select($estable);
      $model_consultorio = new \App\Models\Consultorio;
      $consulta_externa = $model_consultorio->ShowConsultorioByIdIpressAndIdTipoAtencion($id_establecimiento,1);
      
      $hospitalizacion2= "SELECT 
        concat(u.descripcion_upss,' / ',u.codigo ) as nombre,
        u.id as id_upss,
        u.codigo as codigo_upss,
        c.id as id_consultorio,
        c.nombre as nombre_consultorio
        from consultorios c 
        inner join upsses u on u.id_consultorio = c.id 
        where u.id_tipo_atencion = '3' and u.estado = '1' and u.estado = '1' and u.descripcion_upss like 'H%' order by nombre asc ";
      $hospitalizacion2 = DB::select($hospitalizacion2);
      
      $upss="SELECT 
        concat(u.descripcion_upss,' / ',u.codigo ) as nombre,
        u.id as id_upss,
        u.codigo as codigo_upss,
        c.id as id_consultorio,
        c.nombre as nombre_consultorio
        from consultorios c 
        inner join upsses u on u.id_consultorio = c.id 
        where u.id_tipo_atencion = '3' and u.estado = '1' and u.estado = '1' and u.descripcion_upss like 'H%' order by nombre asc";
      $upss= DB::select($upss);
    
      $diagnosticos = "select * from diagnosticos d where estado = '1' order by nombre";
      $diagnosticos = DB::select($diagnosticos);
      //VALIDAR MEDICO
      $dni=Auth::user()->dni;
    
      $model_medico = new Medico();
      $exite_medico=$model_medico->exite_medico($dni);
      $exite_medico_ipress=$model_medico->medico_yaregistrado($dni, $id_establecimiento);
      $medico = $model_medico->GetDatosMedicoAndEstablecimiento($dni, $id_establecimiento);
      $id_asegurado= $prestacion->id_asegurado;
      $model_hospitalizacione = new Hospitalizacione();
      //$datos_emergencia = $model_emergencias->GetEmergenciaByIdAsegurado($id_asegurado);
      $datos_hospitalizacion = $model_hospitalizacione->GetDiasEstanciaByIdHospitalizacion($prestacion->id_ext);
      $dia_estancia = $datos_hospitalizacion->dia_estancia;
      if($exite_medico==0){
        Session::flash('flash_message', 'Sólo los médicos pueden registrar alta administrativa');
        return redirect('/all_hospitalizaciones_by_alta_administrativa');
      }else if($exite_medico_ipress==""){
        $model_establecimiento = new Establecimiento();
        $establecimiento=  $model_establecimiento->GetById($id_establecimiento);
        Session::flash('flash_message', 'Usted no es Médico en el establecimiento: '. $establecimiento[0]->nombre);
        return redirect('/all_hospitalizaciones_by_alta_administrativa');
      }else{
        return view('frontend.hospitalizacion.alta_administrativa', compact('farmacia','evoluci','upss','estable','fechaEgreso','horaEgreso', 'prestacion', 'diagnosticos', 'diagnostico', 'id_prestacion', 'fechaServidor', 'horaservidor','nivel', 'flag_pendinte_firma','hospitalizacion2','consulta_externa','paciente_emergencia','hospitalizacion','dia_estancia','sw','datoadicional','medico','diagnostico_evolucion'));
      } 
    else:
      Session::flash('flash_message', 'Necesita tener una prestacion para dar un Alta Administrativa');
      return redirect('/all_hospitalizaciones_by_alta_administrativa'); 
    endif;
  }

  public function registrar_alta_administrativa_hospitalizacion(Request $request){
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $model_hospitalizacion = Hospitalizacione::where('id', '=', $request->id_hospitalizacion)->first();
    $model_hospitalizacion->id_user_alta_administrativa = $request->id_user;
    $model_hospitalizacion->fecha_alta_administrativa = $request->fecha_egreso;
    $model_hospitalizacion->hora_alta_administrativa = $request->hora_egreso;
    $model_hospitalizacion->estado = 7;
    
    if(isset($model_hospitalizacion->id_cama_estado)) {

      $model_cama = Cama::where('id', '=', $model_hospitalizacion->id_cama_estado)->first();
      $model_cama->estado = 0; // 1 - No disponible (Estos datos son los que van a ir a produccion)
      $model_cama->situacion = '1'; // '5' - En Desinfeccion (Estos datos son los que van a ir a produccion)
      $model_cama->save();
    }
   
    $model_hospitalizacion->save(); 
    //$model_trama = new \App\Models\Hospitalizacione;
    //$enviado_dato = $model_trama->enviartrama($request->id_hospitalizacion);
    Session::flash('flash_message', 'Alta Administrativa exitosa');
    return redirect('/all_hospitalizaciones_by_alta_administrativa');
  }

  public function hospitalizacion_admision($id_orden_hospitalizacion = "")
  {
    if (!Auth::check()):
      return redirect('/auth/login');
    endif;

    // if (count($request->all()) > 0) {
    $modelFarmacia = new Farmacia();
    $model_cie = new \App\Models\Diagnostico;
    $model_consultorio = new Consultorio;
    $model_diagnostico = new \App\Models\Diagnostico;
    $model_medico = new \App\Models\Medico();
    $model_receta_vale = new Receta_vale;
    $model_farmacia_servicio = new \App\Models\Farmacia_servicio();
    $model_kardex = new Kardexe();
    $model_usuario = new User;
    $model_em= new Emergencia();
    $model_hospitalizacion_orden = new \App\Models\HospitalizacionOrden();

    $fechaServidor = $model_kardex->getFechaServidor();
    //-----------------------------------------------        
    $id_establecimiento = Auth::user()->id_establecimiento;
    $dni_medico = Auth::user()->dni;
    
    $orden = null;
    $id_ext = null;
    $dato_traslado = null;
    if ($id_orden_hospitalizacion != "") {
      $orden = $model_hospitalizacion_orden->GetOrdenById2($id_orden_hospitalizacion);
      $dato_traslado = $model_hospitalizacion_orden->GetVerUbicacionTraslado($id_orden_hospitalizacion);
      $id_ext = $orden->id_ext;
    }

    $id_consultorio_destino = "";
    $descripcion_triaje = "";
    $id_medico_egreso = "";
    $medico_egreso = "";

    $id_user = Auth::user()->id;
    $model_establecimiento = new Establecimiento();
    $nivel = $model_establecimiento->GetById($id_establecimiento)[0]->nivel;
    $farma = $modelFarmacia->GetFarmaByUser($id_user);
    $id_farmacia = $farma->id;

    $tipo_receta = "";
    $domicilio = "";
    $telefono_beneficiario = "";
    $correo = "";

    $establecimiento = $modelFarmacia->GetIpressByIduser($id_user);
    $id_ipress = $establecimiento->id;

    $user = $model_usuario->GetById($id_user);
    $date = Carbon::now();
    $fecha = $date->format('d-m-Y');
    //$consultorio = $model_consultorio->ShowConsultorios($id_farmacia);
    //$consultorio = $model_consultorio->ShowConsultoriosHospi();



    $tipo_hospitalizacion = 'HO';
    $medico = $model_medico->ShowallById_Ipress($id_ipress);

    $model_beneficiario = new \App\Models\Beneficiario();
    //$beneficiario = $model_beneficiario->buscar_personal_dni($dni, 'DNI');


    $model = new \App\Models\Cuarto();
    $pabellon = $model->GetCuartoTodosId(1, $id_ipress); //cuarto
    $consultorios = $model->GetConsultorioId($id_ipress); //cuarto
    if (count($consultorios) > 0)
      $id_consultorio = $consultorios->id_consultorio;
    $tipo_receta = "HOSPITALIZACION";

    $model_grado = new \App\Models\Tipo_grado();
    $grado = $model_grado->ShowTipoGrado();

    $tarifa = "";
    $id_tipo_beneficiario = "";
    $opcReceta = 1;
    $tipo_doc_ident_titular = 1;

    $model_tipo_documento_identidad = new \App\Models\Tipo_documento_identidad();
    $tipo_documento_identidad = $model_tipo_documento_identidad->ShowTipoDocumentoIdentidad();

    $model_beneficiario = new \App\Models\Tipo_beneficiario;
    $tipo_beneficiario = $model_beneficiario->ShowTipo_beneficiario();


    //$model = new Triaje();
    //$prioridades = $model->ShowPrioridad(); //prioridades i ii iii iv 

    $model_parametros = new App\Models\Parametros;
    $profesionales = $model_parametros->getProfesionalesByIdEstablecimiento($id_establecimiento);

    $model_tipo_atencione = new App\Models\TipoAtencione;
    $tipo_origenes = $model_tipo_atencione->ShowTipoAtencionAll();

    /*
      $hospitalizacion = "select c.nombre, u.id, u.codigo from consultorios c inner join upsses u on 
      u.id_consultorio = c.id where u.id_tipo_atencion = '3' and u.estado = '1' and u.descripcion_upss is not null";
      $area_serv_hospitalizacion = DB::select($hospitalizacion);
      */

    $departamento = new Distrito;
    $departamentos = $departamento->ShowDepartamento();

    $model_tipo_parentescos = new Tipo_parentescos;
    $tipo_parentesco = $model_tipo_parentescos->getTipo_parentescos();

    $upsses = "SELECT DISTINCT up.id id_upss, up.descripcion_upss, up.codigo codigo_upss, co.estado 
      FROM upsses as up
      INNER JOIN consultorios as co ON co.id = up.id_consultorio
      WHERE up.estado = 1 
      And up.id_tipo_atencion = 3
      ORDER BY up.descripcion_upss;";
    $upsses= DB::select($upsses);

    $model_cama = new Cama();
    // $pabellones_servicios_cuartos = $model_cama->GetAllPabellonesYServicioOSalaYCuartos($id_establecimiento, 'HOSPITALIZACION');
    $pabellones = $model_cama->GetAllPabellones($id_establecimiento, 'HOSPITALIZACION');

    $model_paciente = new Paciente();
    $razas = $model_paciente->GetAllRazas();
    $tipo_sangres = $model_paciente->GetAllTipoSangres();
    $estado_civiles = $model_paciente->GetAllEstadoCiviles();
    $religiones = $model_paciente->GetAllReligiones();
    $grado_instrucciones = $model_paciente->GetAllGradoInstrucciones();
    $ocupaciones = $model_paciente->GetAllOcupaciones();
    
    // Obtener id prestacion de la atencion de origen
    $id_ext_prestacion_origen = 0;
    if ($orden != null) {
      if ($orden->id_tipo_atencion == 2 && $orden->id_ext != "") {
        $ext_prestacion_origen = DB::table('prestaciones')->where('id_ext',$orden->id_ext)->where('id_tipo_atencion', 2)->where('id_estado_reg', 1)->select('id')->first();
        $id_ext_prestacion_origen = ($ext_prestacion_origen != null) ? $ext_prestacion_origen->id : 0;
      }
    }
    $diagnosticos_programacion_qx=null;
    if ($id_orden_hospitalizacion != ""):
    //BUSCA LA PRESTACION DE LA PROGRAMACION QUIRURGICA POR EL ID DE HOSPITALIZACION EN CENTRO QUIRURGICO
    $id_prestacion = DB::table('hospitalizacion_ordenes as ho')
    ->join('cqx_citas as cc', 'ho.id_ext', '=', 'cc.id_cqx_intervenciones')
    ->where('ho.id', $id_orden_hospitalizacion)
    ->where('cc.is_programacion_quirurgica', true)
    ->value('cc.id_prestacion');
    //POR EL ID DE PRESTACION ENCONTRADO DEBE TRAER LOS 
    // $diagnosticos_programacion_qx=null;
    if(!empty($id_prestacion)){
      $model_prestacion = new \App\Models\Prestaciones;
      $diagnosticos_programacion_qx = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
    }
    endif;
    $model_medico = new Medico();
    $medicos_cqx = $model_medico->GetProfesionalesXEspecialidad($establecimiento->id, 0);
    
    $discapacidades = Discapacidad::all();
    $flag_api_sp = DB::table('config_options')->where('id', 5)->value('api_sp_activated'); // si es true debería utilizarse el API de SALUDPOL , caso contrario, la ws de SALUDPOL
    //----------------------------------------------------------------------
    return view('frontend.hospitalizacion.form_admision',
      compact(
        //'fecha_alta',
        //'hora_alta',
        'etnias', 'tipo_parentesco',
        'establecimiento',
        'upsses',
        'diagnostico',
        'id_farmacia',
        'medico',
        'fecha',
        'fechaServidor',
        'opcReceta',
        'id_ipress',
        'orden',
        'tipo_receta',
        'pabellon',
        'nivel',
        'id_consultorio',
        'tipo_hospitalizacion',
        'id_consultorio_destino',
        'dni_medico',
        'paciente',
        'tipo_beneficiario',
        'tarifa',
        'apellidos_nombres_titular',
        'id_grado',
        'grado',
        'dni_titular',
        'asegurado',
        'id_tipo_beneficiario',
        'paterno_titular',
        'materno_titular',
        'nombre_titular',
        'sexo_titular',
        'tipo_doc_ident_titular',
        'descripcion_triaje',
        'tipo_documento_identidad',
        'tipo_beneficiario',
        'departamentos',
        'id_ext',
        'id_ext_prestacion_origen',
        'tipo_origenes',
        'profesionales',
        'razas',
        'tipo_sangres',
        'estado_civiles',
        'religiones',
        'grado_instrucciones',
        'ocupaciones',
        'pabellones',
        'id_orden_hospitalizacion',
        'dato_traslado',
        'diagnosticos_programacion_qx',
        'discapacidades',
        'medicos_cqx',
        'flag_api_sp'
      )
    );
  }
  public function verificarExisteHospitalizacionOrden($id_asegurado){

    $id_asegurado_query = $id_asegurado;
    $queryHospitalizacionOrdenAsegurado = DB::table('hospitalizacion_ordenes as ho')
    ->select(
        'ho.id as ho_id',
        'ho.id_asegurado as ho_id_asegurado',
        DB::raw("TO_CHAR(ho.fecha_derivacion, 'DD/MM/YY') as ho_fecha_derivacion"),
        DB::raw("TO_CHAR(ho.fecha_derivacion, 'HH:MI AM') as ho_hora_derivacion")
    )
    ->where('ho.flag', 1)
    ->where('ho.id_establecimiento', Auth::user()->id_establecimiento)
    ->where('ho.id_asegurado', $id_asegurado_query)
    ->first();
    
    return response()->json(
      [
        'rows'=> isset($queryHospitalizacionOrdenAsegurado)? count($queryHospitalizacionOrdenAsegurado):0,
        'data'=> $queryHospitalizacionOrdenAsegurado
      ]
    );
  }

  public function ver_prestaciones_hospitalizacion($id_hospitalizacion)
  {
    if (!Auth::check()):
      return redirect('/auth/login');
    endif;

    $hospitalizacion = Hospitalizacione::where('id', $id_hospitalizacion)->first();
    if($hospitalizacion != null):

      $ruta = "all_hospitalizado_by_establecimiento";
      $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);

      $permiso = null;
      if (!empty($menu)) {
        $permiso = $menu[0];
      }

      if (!empty($permiso)):
        $id_user = Auth::user()->id;
        $is_fiscalizador = Auth::user()->flagfiscalizador;
        $model_hospitalizacione = new Hospitalizacione();
        $paciente = $model_hospitalizacione->GetBuscaPacienteHospitalizacion($hospitalizacion->id, $hospitalizacion->estado);
        $cama = null;
        if (isset($hospitalizacion->id_cama_estado)) {
          $cama = $model_hospitalizacione->GetObtenerCama($hospitalizacion->id_cama_estado);
        }
        $atenciones = $model_hospitalizacione->getAtencionesById($id_hospitalizacion);
        $servicio_encargado = null;
        if ($hospitalizacion->id_servicio_encargado != null):
          $model_consultorio = new \App\Models\Consultorio;
          $servicio_encargado = $model_consultorio->GetNombreById($hospitalizacion->id_servicio_encargado);
        endif;

        $formatos_escaneados="SELECT * from cqx_formato_escaneados where id_intervencion= '$hospitalizacion->id'";
        $formatos_escaneados = DB::select($formatos_escaneados);

        return view('frontend.hospitalizacion.all_atenciones', compact('hospitalizacion', 'cama', 'formatos_escaneados', 'atenciones', 'id_user', 'paciente', 'servicio_encargado', 'is_fiscalizador', 'permiso'));

      else:
        return redirect('/dashboard')->withInput()->withErrors(array('No cuenta con permisos para acceder a esa opción.'));
      endif;

    else:
      return redirect('/dashboard')->withInput()->withErrors(array('Elemento no encontrado ..!'));
    endif;
  }
  
  public function listar_alta_administrativa_ajax(Request $request)
  {
   
    $model_hospitalizacione = new Hospitalizacione();
    $id_establecimiento = Auth::user()->id_establecimiento;
    $estado = $request->estado;
    
    $p[] = $id_establecimiento;
    $p[] = $request->fecha_ini;
    $p[] = $request->fecha_fin;
    $p[] = $request->dni_beneficiario;
    $p[] = strtoupper($request->nombre_beneficiario);
    $p[] = $request->estado;
    $p[] = $request->NumeroPagina;
    $p[] = $request->NumeroRegistros;

    //if ($id_establecimiento != 75) {
      $data = $model_hospitalizacione->listar_alta_administrativa_ajax($p);
    //}

    $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;
    $result["PageStart"] = $request->NumeroPagina;
    $result["pageSize"] = $request->NumeroRegistros;
    $result["SearchText"] = "";
    $result["ShowChildren"] = true;
    $result["iTotalRecords"] = $iTotalDisplayRecords;
    $result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
    $result["aaData"] = $data;
    //dd($data);
    echo json_encode($result);
 
  }

  public function updateCodeSiteds(Request $request, $id) {
    $hospitalizacion = Hospitalizacione::find($id);
    if (!$hospitalizacion) {
      return response()->json(['message' => 'No existe registro de la hospitalización'], 404);
    }
    $hospitalizacion->cod_siteds_susalud = $request->input('cod_siteds_susalud');
    $hospitalizacion->updated_at_cod_siteds = Carbon::now();
    $hospitalizacion->updated_us_cod_siteds = Auth::user()->id;
    $hospitalizacion->save();
    
    return response()->json([
        'emergencia_id' => $id,
        'message' => 'Código SITEDS actualizado correctamente'
    ], 200);
  } 

  public function mostrar_atencion_hospitalizacion($id_prestacion)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    
    $model_prestacion = new \App\Models\Prestaciones;
    $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
    $flag_bloqueo_atencion = $prestacion ? DB::table('asegurados')->where('id', $prestacion->id_asegurado)->value('flag_bloqueo_atencion') : null;
    if ($flag_bloqueo_atencion !== null && intval($flag_bloqueo_atencion) === 1) :
      return response()->json(['message' => 'No puede ver la atención de este paciente.'], 403);
    endif;

    if (Auth::user()->tipo_registro == 1 || Auth::user()->tipo_registro == 2) {
      $modelfarma = new Farmacia;
      $model_receta = new Receta_vale;
      $model_producto = new Producto;
      $model_productos_receta = new Producto_receta;
      $model_establecimiento = new \App\Models\Establecimiento;
      $model_laboratorio = new \App\Models\Laboratorio;
      $model_radiologia = new \App\Models\Radiologia;
      $model_interconsulta = new \App\Models\Interconsulta;
      $model_referencia = new \App\Models\Referencia;
      $model_kardex = new Kardexe();

      $id_user = Auth::user()->id;
      $farmacia = $modelfarma->GetFarmaByUser($id_user);
      $flag_receta_dias = $farmacia->flag_receta_dias;

      $fechaServidor = $model_kardex->getFechaServidor();
      $date = Carbon::now();
      $fecha_actual = $date->format('d-m-Y');

      if ($prestacion->id_tipo_atencion == 3 && !empty($prestacion->id_ext)) {
        $hospitalizacion_ver = Hospitalizacione::find($prestacion->id_ext);
        if ($hospitalizacion_ver && !$hospitalizacion_ver->historiaClinicaCompleta()) {
          return redirect('/form_atencion_prestacion_hspcu/' . $id_user . '/' . $prestacion->id_ext);
        }
      }

      $receta = $model_receta->GetDatosRecetasByIdPrestacion($id_prestacion);

      $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacionEmergencia($id_prestacion);
      $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionYTipo($id_prestacion, 1);
      $laboratorio = $model_laboratorio->GetLaboratorioByIdPrestacion($id_prestacion);
      $radiologia = $model_radiologia->GetRadiologiaByIdPrestacion($id_prestacion);
      $pendiente_firma = $model_prestacion->GetDocumentosPendintesFirmarPrestacionId($id_prestacion);
      $flag_pendinte_firma = (count($pendiente_firma) > 0) ? 1 : 0;

      $id_ipress = $farmacia->id_establecimiento;
      $id_farmacia = $farmacia->id;
      $establecimiento = $model_establecimiento->GetById($id_ipress);
      $nivel = $establecimiento[0]->nivel;
      //Si tiene atenciones en hospitalizacion o emergencia
      $sw = 0;
      if($prestacion->id_ext!=0):
        if($prestacion->id_tipo_atencion==3): // HOSPITALIZACION
          $dato_hospitalizacion = Hospitalizacione::where('id', '=', $prestacion->id_ext)->first();
          $obj_hospitalizacion_atencion = \App\Models\HospitalizacionAtencione::where('id_prestacion', '=', $id_prestacion)->first();
          $producto = $model_producto->getProductosByNivelAndIdServicio($nivel, $id_ipress, $id_farmacia); 
        endif;
      else:
        return redirect('/dashboard')->withInput()->withErrors(array('No encontro la atención..!'));
      endif;
      
      $producto = $model_producto->getProductosByNivelAndIdServicio($nivel, $prestacion->id_consultorio, $id_farmacia);
      
      $farmacias = $modelfarma->GetFarmaciaById($id_farmacia);
      $establecimientos = $model_establecimiento->ShowAllEstablecimientos();
      $interconsultas = $model_interconsulta->GetInterconsultaByIdPrestacion($id_prestacion);

      $referencia = $model_referencia->GetReferenciaByIdPrest($id_prestacion);
      
      $flag_no_encontrado=0; 
      $model_asegurado= new Asegurado();
      if($prestacion->id_asegurado_titular!=null || $prestacion->id_asegurado_titular>0):
        $dato_titular = $model_asegurado->Busca_datos_titular($prestacion->id_asegurado_titular);
      else:
        $flag_no_encontrado=1;
      endif;

      $ruta = "all_hospitalizado_by_establecimiento";
      $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
      $permiso = $menu[0];

      $date = Carbon::now();
      $hora=$date->toTimeString(); 

      if($hora>'01:30' & $hora<'07:31'):
        /*echo*/ $turno='MD';
      else:
        if($hora>'07:30' & $hora<'13:31'):
          /*echo*/ $turno='M';
        else:
          if($hora>'13:30' & $hora<'19:31'):
            /*echo*/ $turno='T';
          else:
            if($hora>'19:30' & $hora<='23:59'):
              /*echo*/ $turno='N';
            else:
              if($hora>='00:00' & $hora<='01:31'):
                /*echo*/ $turno='N';
              endif;
            endif;
          endif;
        endif;
      endif;

      $talla_cm = $prestacion->talla_cm;
      $peso_kg = $prestacion->peso_kg;
      $edadpaciente = $prestacion->edadpaciente;
      $sexo = $prestacion->sexo;
      $peso_ideal = "";
      $imc = "";
      
      // Cálculo del peso ideal
      if ($talla_cm != "0") { 
        // Peso por altura
        $peso_ideal = 50 + (($talla_cm - 150) / 4) * 3 + ($edadpaciente - 20) / 4;
        if ($sexo == "M")
          $peso_ideal = $peso_ideal * 0.9;
        
        if($peso_ideal <= 0)
          $peso_ideal = '';
        
      } else {
        $peso_ideal = "";
      }

      // Cálculo del IMC
      if ($peso_kg != "0" && $talla_cm != "0") {
        $talla_en_metros = $talla_cm * 0.01;
        $imc = "";
        if ($peso_kg > 0 && $talla_cm > 0) {
          $imc = $peso_kg / ($talla_en_metros * $talla_en_metros);
          $imc = number_format($imc, 2, '.', '');
        }
      } else {
        $imc = "";
      }
      // Formateo final
      $peso_ideal = ($peso_ideal == "") ? "-" : $peso_ideal;
      $imc = ($imc == "") ? "-" : $imc;

      return view('frontend.hospitalizacion.mostrar_atencion_hospitalizacion', compact('dato_titular','farmacia', 'prestacion', 'diagnostico', 'procedimiento', 'medicamentos', 'id_prestacion', 'fechaServidor', 'receta', 'producto', 'farmacias', 'nivel', 'laboratorio', 'radiologia', 'flag_pendinte_firma','establecimientos','interconsultas','referencia','procedimientos','datoadicional','sw','permiso','flag_no_encontrado','turno','id_farmacia','dato_hospitalizacion','obj_hospitalizacion_atencion','peso_ideal','imc'));
    }
    else
    {
      return redirect('/dashboard')->withInput()->withErrors(array('No tiene el perfil de médico..!'));
    }
  }

  public function hospitalizacion_by_enfermeria()
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $ruta = Route::getCurrentRoute()->getPath();
    if (count($ruta = explode("/", $ruta)) > 1);
    $ruta = $ruta[0];
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
    $permiso = $menu[0];
    if ($permiso->lectura == "")
      return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

    $id_user = Auth::user()->id;
    $id_user_consultorio = Auth::user()->id_consultorio;
    $model_farma = new Farmacia();
    $farmacia = $model_farma->GetFarmaByUser($id_user);
    $id_farmacia = $farmacia->id;
    $id_establecimiento = $farmacia->id_establecimiento;
    $establecimiento = $model_farma->GetIpressByIduser($id_user);
    $id_tipo_atenciones = 3;
    $model_consultorios = new Consultorio();
    $servicios = $model_consultorios->ShowConsultoriosAll();
    
    return view('frontend.hospitalizacion_enfermeria.all_nota_ingresos_hospitalizacion', compact('id_establecimiento', 'servicios', 'diagnosticos','id_tipo_atenciones', 'farmacia', 'id_farmacia', 'id_user_consultorio', 'id_user','establecimiento'));
  }

  public function hospitalizado_by_enfermeria_ajax(Request $request)
	{

		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;

		$model_hospitalizacione = new Hospitalizacione();

    $p[] = $request->NumeroPagina; //0
    $p[] = $request->NumeroRegistros;  //1
    $p[] = $request->fecha_ini; //2
    $p[] = $request->fecha_fin;  //3
    $p[] = Auth::user()->id_establecimiento;  //4
    $p[] = $request->busqueda_datos;  //5
    $p[] = $request->seleccionar_estado;  //6
    $p[] = $request->id_consultorio;  //7

		$data = $model_hospitalizacione->GetListaTodosHospitalizadoNotaEnfermeria($p);

		$iTotalDisplayRecords = isset($data['totalrows']) ? $data['totalrows'] : 0;

		$result["PageStart"] = $request->NumeroPagina;
		$result["pageSize"] = $request->NumeroRegistros;
		$result["SearchText"] = "";
		$result["ShowChildren"] = true;
		$result["iTotalRecords"] = $iTotalDisplayRecords;
		$result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
		$result["aaData"] = $data['data'];
		//==================== 

		echo json_encode($result);
	}

  public function rotacion_cama_hospitalizacion( $id_farmacia,$id_hospitalizacion)
    {
      if (!Auth::check()) :
        return redirect('/auth/login');
      endif;

      // YMC
      $emergenciaModel = new Emergencia();
      $hospitalizacionModel = new Hospitalizacione();
      $camaModel = new Cama();
      $cuartoModel = new Cuarto();
      $ConsultorioModel = new Consultorio();
      $medicoModel = new Medico();

      $emergencia = $hospitalizacionModel->GetBuscaHospitalizado2($id_hospitalizacion);
      $pabellon = Pabellone::where('id', $emergencia->id_pabellon)->select('nombre')->first();
      if (!isset($emergencia)) {
        session()->flash('error', "No existe el registro de enfermeria");
        return redirect('/emergencia_by_enfermeria/')->withInput()->withErrors(array('No existe paciente hospitalizado con ese nro de hospitalizado..!'));
      }
      $emergencia->es_titular = isset($emergencia->id_tipo_beneficiario) && intval($emergencia->id_tipo_beneficiario) === 8 ? true : false;

      $medicos = $medicoModel->ShowMedicosByIpress($emergencia->id_establecimiento);
      $consultorios = $ConsultorioModel->ShowAllConsultoriosSubConsultorio_old($emergencia->id_establecimiento);

      $cuartos = $cuartoModel->ShowallCuartosId_Ipress($emergencia->id_establecimiento);

      $camaAcual = Cama::where("id", "=", $emergencia->cama_id)->first();
      $camas = null;
      if (isset($camaAcual)) {
        $camas = $camaModel->GetCamasByIdEstablecimientoYNombreTipoAtencionYIdCuarto($emergencia->id_establecimiento, 'HOSPITALIZACION', $camaAcual->id_cuarto);
      }
      $pabellones = $camaModel->GetAllPabellones($emergencia->id_establecimiento, 'HOSPITALIZACION');

      return view('frontend.hospitalizacion_enfermeria.form_rotacion_cama_emergencia', compact('emergencia', 'camas', 'cuartos', 'medicos', 'consultorios', 'pabellones', 'pabellon'));
    }

    public function listado_hospitalizaciones(Request $request){

      $model_hospitalizacion = new \App\Models\Hospitalizacione();
      $p[] = 0;
      $p[] = !empty($request->fecha_ini) ? Carbon::createFromFormat('d-m-Y', $request->fecha_ini)->format('Y-m-d') : null;
      $p[] = !empty($request->fecha_fin) ? Carbon::createFromFormat('d-m-Y', $request->fecha_fin)->format('Y-m-d') : null;
      $p[] = $request->NumeroPagina;
      $p[] = $request->NumeroRegistros;

      $data = $model_hospitalizacion->listar_hospitalizaciones_asegurados($p);
      $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;

      $result["PageStart"] = $request->NumeroPagina;
      $result["pageSize"] = $request->NumeroRegistros;
      $result["SearchText"] = "";
      $result["ShowChildren"] = true;
      $result["iTotalRecords"] = $iTotalDisplayRecords;
      $result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
      $result["aaData"] = $data;

      echo json_encode($result);
  }

   function hospitalazacionValidarAtendida(Request $request){

    $hospitalizacionModel = new Hospitalizacione();
    $idOrdenHospotalizacion = $request->idOrdenHospitalizacion;

    // Retornar true o false
    return response()->json(['respuesta' => $hospitalizacionModel->hospitalazacionValidarAtendida($idOrdenHospotalizacion)]);
 

   }

   function hospitalazacionValidarAtendidaForDni(Request $request){

    $hospitalizacionModel = new Hospitalizacione();

    // Retornar true o false
    return response()->json(['respuesta' => $hospitalizacionModel->hospitalazacionValidarAtendidaForDni($request->dni,$request->id_establecimiento)]);
  

   }
    public function ver_atenciones_evaluacion($id_hospitalizacion, $estado, $id_tipo_atencion)
  {
      if (!Auth::check()) :
          return redirect('/auth/login');
      endif;
      $date = Carbon::now();
      $fechaHasta = $date->format('d-m-Y');
      $fechaDesdex = $date->format('d-m-Y');
      $fechaDesdey = strtotime('-30 day', strtotime($fechaDesdex));
      $fechaDesde = date('d-m-Y', $fechaDesdey);

      $hospitalizacion = Hospitalizacione::where('id', $id_hospitalizacion)->where('estado', $estado)->first();

      if(is_object($hospitalizacion)):
          $model_hospitalizacione = new Hospitalizacione;
          $paciente = $model_hospitalizacione->GetBuscaPacienteHospitalizacion($id_hospitalizacion, $estado);
          if(count($paciente)>0):
              $estado = "1";
              $id_atencione = $id_tipo_atencion;
              $model_emergencias = new Emergencia;
              $datoadicional = $model_emergencias->GetObtenerCama($hospitalizacion->id_cama_estado);

              $ruta = "all_pacientes_evoluciones_by_hospitalizacion";
              $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
              $permiso = $menu[0];
              $atenciones = $model_hospitalizacione->getAtencionesResumenById($id_hospitalizacion);
              return view('frontend.hospitalizacion.all_evaluaciones_atenciones',compact('fechaDesde', 'fechaHasta','estado','id_atencione','paciente','id_hospitalizacion','datoadicional','permiso','atenciones'));
          else:
              return redirect('/dashboard')->withInput()->withErrors(array('Registros de paciente no encontrado..!'));
          endif;
      else:
          if($estado==2):
              return redirect('/dashboard')->withInput()->withErrors(array('Elemento no encontrado ..!'));
          else:
              return redirect('/dashboard')->withInput()->withErrors(array('Elemento no encontrado ..!'));
          endif;
      endif;
  }

  public function ver_atenciones_evaluacion_farmacia($id_hospitalizacion, $estado, $id_tipo_atencion)
  {
      if (!Auth::check()) :
          return redirect('/auth/login');
      endif;
      $date = Carbon::now();
      $fechaHasta = $date->format('d-m-Y');
      $fechaDesdex = $date->format('d-m-Y');
      $fechaDesdey = strtotime('-30 day', strtotime($fechaDesdex));
      $fechaDesde = date('d-m-Y', $fechaDesdey);

      $hospitalizacion = Hospitalizacione::where('id', $id_hospitalizacion)->first();

      if(is_object($hospitalizacion)):
          $model_hospitalizacione = new Hospitalizacione;
          $paciente = $model_hospitalizacione->GetBuscaPacienteHospitalizacionFarmacia($id_hospitalizacion);
          if(count($paciente)>0):
              $estado = "1";
              $id_atencione = $id_tipo_atencion;
              $model_emergencias = new Emergencia;
              $datoadicional = $model_emergencias->GetObtenerCama($hospitalizacion->id_cama_estado);

              $ruta = "all_pacientes_evoluciones_by_hospitalizacion";
              $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
              $permiso = $menu[0];
              $atenciones = $model_hospitalizacione->getAtencionesResumenById($id_hospitalizacion);
              $is_fiscalizador = Auth::user()->flagfiscalizador;

                $servicio_encargado = null;
                if ($hospitalizacion->id_servicio_encargado != null):
                    $model_consultorio = new \App\Models\Consultorio;
                    $servicio_encargado = $model_consultorio->GetNombreById($hospitalizacion->id_servicio_encargado);
                endif;

                $formatos_escaneados="SELECT * from cqx_formato_escaneados where id_intervencion= '$hospitalizacion->id'";
                $formatos_escaneados = DB::select($formatos_escaneados);
                
                $establecimiento_model = new \App\Models\Establecimiento;
                $establecimiento = $establecimiento_model->GetByIdEstablecimiento(Auth::user()->id_establecimiento);
                

                return view('frontend.hospitalizacion.evolucion.all_evaluaciones_atenciones', compact('fechaDesde', 'fechaHasta', 'formatos_escaneados', 'hospitalizacion','estado','id_atencione','paciente','id_hospitalizacion','datoadicional','permiso','atenciones','servicio_encargado','is_fiscalizador','establecimiento'));
          else:
              return redirect('/dashboard')->withInput()->withErrors(array('Registros de paciente no encontrado..!'));
          endif;
      else:
          if($estado==2):
              return redirect('/dashboard')->withInput()->withErrors(array('Elemento no encontrado ..!'));
          else:
              return redirect('/dashboard')->withInput()->withErrors(array('Elemento no encontrado ..!'));
          endif;
      endif;
  }

  

  public function edit_atencion($id_user, $id_prestacion)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    if (Auth::user()->tipo_registro == 1) {
      $modelfarma = new Farmacia;
      $model_receta = new Receta_vale;
      $model_producto = new Producto;
      $model_productos_receta = new Producto_receta;
      $model_establecimiento = new \App\Models\Establecimiento;
      $model_prestacion = new \App\Models\Prestaciones;
      $model_laboratorio = new \App\Models\Laboratorio;
      $model_radiologia = new \App\Models\Radiologia;
      $model_interconsulta = new \App\Models\Interconsulta;
      $model_referencia = new \App\Models\Referencia;
      $model_kardex = new Kardexe();

      $farmacia = $modelfarma->GetFarmaByUser($id_user);
      $flag_receta_dias = $farmacia->flag_receta_dias;

      $fechaServidor = $model_kardex->getFechaServidor();
      $date = Carbon::now();
      $fecha_actual = $date->format('d-m-Y');
      $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);

      if ($prestacion->id_tipo_atencion == 3 && !empty($prestacion->id_ext)) {
        $hospitalizacion_edit = Hospitalizacione::find($prestacion->id_ext);
        if ($hospitalizacion_edit && !$hospitalizacion_edit->historiaClinicaCompleta()) {
          return redirect('/form_atencion_prestacion_hspcu/' . $id_user . '/' . $prestacion->id_ext)->withErrors(array('Debe completar la Historia Clínica antes de editar esta atención..!'));
        }
      }

      $receta = $model_receta->GetDatosRecetasByIdPrestacion($id_prestacion);

      $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacionEmergencia($id_prestacion);
      /*$diagnosticos = \App\Models\Diagnostico::where('estado',1)
      ->whereRaw('LENGTH(codigo) > 4') 
      ->get();*/
      $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionYTipo($id_prestacion, 1);
      /*$procedimientos = \App\Models\Procedimiento::where('estado',1)
      ->get();*/
      $laboratorio = $model_laboratorio->GetLaboratorioByIdPrestacion($id_prestacion);
      $radiologia = $model_radiologia->GetRadiologiaByIdPrestacion($id_prestacion);
      $pendiente_firma = $model_prestacion->GetDocumentosPendintesFirmarPrestacionId($id_prestacion);
      $flag_pendinte_firma = (count($pendiente_firma) > 0) ? 1 : 0;

      $id_ipress = $farmacia->id_establecimiento;
      // if($id_ipress==76): //lns
      //   $id_farmacia = 19; //farmacia de emergencia
      // else:
        $id_farmacia = $farmacia->id;
      // endif;
      $establecimiento = $model_establecimiento->GetById($id_ipress);
      // if ($id_ipress == 1) {
      //   $nivel = 2;
      // } else {
        $nivel = $establecimiento[0]->nivel;
      // }
      //Si tiene atenciones en hospitalizacion o emergencia
      $sw = 0;
      if($prestacion->id_ext!=0):
        if($prestacion->id_tipo_atencion==3): // HOSPITALIZACION
          $dato_hospitalizacion = Hospitalizacione::where('id', '=', $prestacion->id_ext)->first();
          // $obj_hospitalizacion_atencion = \App\Models\HospitalizacionAtencione::where('id_prestacion', '=', $id_prestacion)->first();
          $obj_hospitalizacion_atencion = \App\Models\HospitalizacionAtencione::select('ha.*', 
              'p.fecha_parto', 
              'p.id_tipo_parto as puerperio_id_tipo_parto', 
              'tp.nombre as puerperio_tipo_parto', 
              'p.complicaciones as puerperio_complicaciones', 
              'p.descripcion as puerperio_descripcion')
          ->from('hospitalizacion_atenciones as ha')
          ->leftJoin('puerperios as p', 'ha.id_puerperio', '=', 'p.id')
          ->leftJoin('tipo_partos as tp', 'p.id_tipo_parto', '=', 'tp.id')
          ->where('ha.id_prestacion', '=', $id_prestacion) // Cambié aquí a 'ha.id' para que coincida con el contexto
          ->first();
          /*$model_emergencia = new \App\Models\Emergencia;
          if($dato_hospitalizacion->id_prioridad<4):
            $obtenerdato = $model_emergencia->GetObtenerServicioActual($prestacion->id_ext);
            $datoadicional = $model_emergencia->GetObtenerCama($obtenerdato->cama);
            $sw = 1;
            $prestacion->servicio= $obtenerdato->servicio;
            $prestacion->consultorio= $obtenerdato->consultorio;
          else:
            $prestacion->servicio="IV - PATOLOGÍA AGUDA COMÚN";
            $prestacion->consultorio="CONSULTORIO DE ATENCIÓN RÁPIDA (CAR)";
            $sw = 0;
          endif;*/
          $producto = $model_producto->getProductosByNivelAndIdServicio($nivel, $id_ipress, $id_farmacia); 
        endif;
      else:
           return redirect('/dashboard')->withInput()->withErrors(array('No encontro la atención..!'));
      endif;
      
      $producto = $model_producto->getProductosByNivelAndIdServicio($nivel, $prestacion->id_consultorio, $id_farmacia);
      
      $farmacias = $modelfarma->GetFarmaciaById($id_farmacia);
      $establecimientos = $model_establecimiento->ShowAllEstablecimientos();
      $interconsultas = $model_interconsulta->GetInterconsultaByIdPrestacion($id_prestacion);

      $interconsultas_id_servicio = [];
      if (!empty($interconsultas)) {
        foreach ($interconsultas as $ic) {
          $interconsultas_id_servicio[] = $ic->id_servicio;
        }
      }

      $referencia = $model_referencia->GetReferenciaByIdPrest($id_prestacion);
      
      $flag_no_encontrado=0; 
      $model_asegurado= new Asegurado();
      if($prestacion->id_asegurado_titular!=null || $prestacion->id_asegurado_titular>0):
        $dato_titular = $model_asegurado->Busca_datos_titular($prestacion->id_asegurado_titular);
      else:
        $flag_no_encontrado=1;
      endif;

      $ruta = "all_hospitalizado_by_establecimiento";
      $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
      $permiso = $menu[0];

      $date = Carbon::now();
      $hora=$date->toTimeString(); 

      if($hora>'01:30' & $hora<'07:31'):
        /*echo*/ $turno='MD';
      else:
        if($hora>'07:30' & $hora<'13:31'):
          /*echo*/ $turno='M';
        else:
          if($hora>'13:30' & $hora<'19:31'):
            /*echo*/ $turno='T';
          else:
            if($hora>'19:30' & $hora<='23:59'):
              /*echo*/ $turno='N';
            else:
              if($hora>='00:00' & $hora<='01:31'):
                /*echo*/ $turno='N';
              endif;
            endif;
          endif;
        endif;
      endif;

      $talla_cm = $prestacion->talla_cm;
      $peso_kg = $prestacion->peso_kg;
      $edadpaciente = $prestacion->edadpaciente;
      $sexo = $prestacion->sexo;
      $peso_ideal = "";
      $imc = "";
      
      // Cálculo del peso ideal
      if ($talla_cm != "0") { 
        // Peso por altura
        $peso_ideal = 50 + (($talla_cm - 150) / 4) * 3 + ($edadpaciente - 20) / 4;
        if ($sexo == "M")
          $peso_ideal = $peso_ideal * 0.9;
        
        if($peso_ideal <= 0)
          $peso_ideal = '';
        
      } else {
        $peso_ideal = "";
      }

      // Cálculo del IMC
      if ($peso_kg != "0" && $talla_cm != "0") {
        $talla_en_metros = $talla_cm * 0.01;
        $imc = "";
        if ($peso_kg > 0 && $talla_cm > 0) {
          $imc = $peso_kg / ($talla_en_metros * $talla_en_metros);
          $imc = number_format($imc, 2, '.', '');
        }
      } else {
        $imc = "";
      }
      // Formateo final
      $peso_ideal = ($peso_ideal == "") ? "-" : $peso_ideal;
      $imc = ($imc == "") ? "-" : $imc;

      $lab = $model_laboratorio->GetLaboratorioByIdPrestacion($id_prestacion);
      if (empty($lab)) {
        $flag_laboratorio = "0";
        $flag_firmar_laboratorio = "";
      } else {
        $flag_laboratorio = "1";
        $flag_firmar_laboratorio = $lab[0]->fecha_firma;
      }
      $rad = $model_radiologia->GetRadiologiaByIdPrestacion($id_prestacion);
      if (count($rad)>0) {
      
        $flag_radiologia = "1";
        $flag_firmar_radiologia = $rad[0]->fecha_firma;
      } else {
        $flag_radiologia = "0";
        $flag_firmar_radiologia = "";
      }

      //============== Ordenes de Imagenes invalidados por Imagenes ============================
      $ordenImagenesAnulada = $model_radiologia->cpmsAnulados(2,$id_prestacion);
      //dd($ordenImagenesAnulada);
      $orden = '';
      $ordenes = [];
      if ( count($ordenImagenesAnulada) > 0 ) {
        foreach ($ordenImagenesAnulada as $key) {
          if ( $orden !== $key->id_radiologia ) {
            $orden = $key->id_radiologia;

            $obj = new stdClass();
            $obj->id_radiologia = $key->id_radiologia;
            $obj->obs_examen_invalidado = $key->obs_examen_invalidado;
            $obj->fecha_orden_invalidado = $key->fecha_orden_invalidado;
            $obj->usuario = $key->name . ', ' . $key->paterno; 

            $ordenes[] = $obj;
          }
        }
        //dd($ordenes);

        foreach ($ordenes as $key1) {
          $key1->cpms = [];
          foreach ($ordenImagenesAnulada as $key2) {
            //dd($key1->id_radiologia);
            if ($key1->id_radiologia == $key2->id_radiologia) {

              $obj = new stdClass();
              $obj->id_pp = $key2->id;
              $obj->codigo_rad = $key2->codigo_rad;
              $obj->descripcion_corta = $key2->descripcion_corta;
              $obj->diagnostico = $key2->diagnostico;

              $key1->cpms[] = $obj;
            }  
          }  
        }

        
      }

      // Verificar si el usuario es medico de profesion
      $model_medico = new Medico();
      $medico = $model_medico->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni,Auth::user()->id_establecimiento );
      $is_profesion_medico = false;
      if (count($medico) > 0) {
        switch ($medico->id_profesion) {
          case 20: case 4: case 51: case 52: case 1: $is_profesion_medico = true; break;
        }
      }
      //========================================================================================
      return view('frontend.hospitalizacion.edit_atencion', compact('dato_titular','farmacia', 'prestacion', 'diagnostico', 'procedimiento', 'medicamentos', 'id_prestacion', 'fechaServidor', 'receta', 'producto', 'farmacias', 'nivel', 'laboratorio', 'radiologia', 'flag_pendinte_firma','establecimientos','interconsultas','referencia','procedimientos','datoadicional','sw','permiso','flag_no_encontrado','turno','id_farmacia','dato_hospitalizacion','obj_hospitalizacion_atencion','peso_ideal','imc','flag_laboratorio','flag_firmar_laboratorio','flag_radiologia','flag_firmar_radiologia','ordenImagenesAnulada','ordenes','is_profesion_medico', 'interconsultas_id_servicio'));
    }
    else
    {
      return redirect('/dashboard')->withInput()->withErrors(array('No tiene el perfil de médico..!'));
    }
  }

  public function anularAtencionHospitalizacion(Request $request){
    $rules = [
      'id' => 'required|integer|exists:hospitalizaciones,id',
      'motivo' => 'required|string|max:255',
    ];
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => $validator->errors()->first()
        ], 422); 
    }
    try {
       //PRESTACIONES
       $prestacion = Prestaciones::where('id_ext', $request->id)->first();
       if (!$prestacion) {
           throw new \Exception("Prestación no encontrada");
       }
       $prestacion->id_estado_reg = 0;
       $prestacion->updated_us = Auth::user()->id;
       $prestacion->updated_at = Carbon::now();
       $prestacion->save();

       //DIAGNOSTICOS
       \App\Models\Receta_diagnostico::where('id_prestacion', $prestacion->id)
           ->where('estado', '<>', 0)
           ->update(['estado' => 0]);
       //PROCEDIMIENTOS
       \App\Models\ProcedimientoDiagnotico::where('id_prestacion', $prestacion->id)
           ->where('estado', '<>', 0)
           ->update(['estado' => 0]);

       //RECETAS
       $recetas = Receta_vale::where('id_prestacion', $prestacion->id)->where('estado', '<>', 0)->get();
       foreach ($recetas as $rv) {
           $rv->estado = 0;
           $rv->save();
       }
       //LABORATORIO
       $laboratorio = \App\Models\Laboratorio::where('id_prestacion', $prestacion->id)->where('estado', '<>', 0)->get();
       foreach ($laboratorio as $lab) {
           $lab->estado = 0;
           $lab->save();
       }
       //IMAGENOLOGIA
       $radiologia = \App\Models\Radiologia::where('id_prestacion', $prestacion->id)->where('estado', '<>', 0)->get();
       foreach ($radiologia as $rad) {
           $rad->estado = 0;
           $rad->save();
       }
       //INTERCONSULTAS
       \App\Models\Interconsulta::where('id_prestacion', $prestacion->id)
           ->where('id_estado_reg', '<>', 0)
           ->update(['id_estado_reg' => 0]);

       //ACTUALIZAR TODAS LAS EVALUACIONES A ESTADO 0
       //$request->id  id_hospitalizacion
       $existe = Evaluacione::where('id_emergencia', $request->id)
        ->exists();
      // Si hay registros, se procede con la actualización
      if ($existe) {
        Evaluacione::where('id_emergencia', $request->id)
              ->update(['id_estado_reg' => 0]);
      }

      DB::table('hospitalizaciones')
          ->where('id', $request->id)
          ->update([
              'estado' => 1,//1=ADMISIONADO
              'motivo_anulacion_atencion' => $request->motivo,
              'fecha_anulacion_atencion' => Carbon::now(),
              'id_user_anulacion_atencion' => Auth::user()->id
          ]);

      return response()->json([
          'success' => true,
          'message' => 'Atención anulada correctamente'
      ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al anular la atención'
        ], 500);
    }
  }
  public function modal_paciente_hospitalizacion($id)
  {
    $hospitalizacion = Hospitalizacione::find($id);
    return view('frontend.admision.modal_elimina_paciente_hospitalizacion', compact('id', 'hospitalizacion'));
  }
  public function anular_paciente_hospitalizacion(Request $request)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $update = Hospitalizacione::where('id', '=', $request->id)->first();
    $update->estado = 0;
    $update->fecha_anulacion_paciente = Carbon::now();
    $update->id_user_anulacion_paciente = $request->id_user;
    $update->motivo_anulacion_paciente = $request->motivo_anulacion;
    $update->save();
    return redirect('/all_hospitalizado_by_establecimiento')->withFlashSuccess("Se elimino correctamente al paciente");
  }
  function verificaPendienteAltaEmergenciaByDni(Request $request){
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    //VALIDA SI EL PACIENTE TIENE PENDIENTE DE ALTA EN EMERGENCIA
    $pendiente_alta_emergencia = DB::table('emergencias')
    ->where('dni_beneficiario',$request->dni)
    ->where('estado', 3)
    ->exists();
    $nombres = DB::table('asegurados')
    ->select('nombre', 'paterno', 'materno')
    ->where('nro_doc_ident',$request->dni)
    ->first();
    $lista_pendiente_alta_emergencia = $this->obtener_alerta_atenciones_pendiente_alta($request->dni);
    return response()->json(['pendiente_alta' => $pendiente_alta_emergencia,'lista_pendiente_alta_emergencia'=>$lista_pendiente_alta_emergencia,'nombres'=>$nombres != null ? $nombres->paterno.' '.$nombres->materno.' '.$nombres->nombre : ""]);
   }
   public function obtener_alerta_atenciones_pendiente_alta($dni_beneficiario) {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    //OBTIENE PENDIENTE DE ALTA DE EMERGENCIA
    $data = DB::table('emergencias')
    ->join('establecimientos', 'establecimientos.id', '=', 'emergencias.id_establecimiento')
    ->join('triajes', 'triajes.id', '=', 'emergencias.id_triaje')
    ->where('emergencias.dni_beneficiario', $dni_beneficiario)
    ->whereNull('emergencias.fecha_alta_medica')
    ->where('emergencias.estado', 3)
    ->select([
        'emergencias.estado',
        'establecimientos.nombre as establecimiento_descripcion',
        'triajes.descripcion as servicio_descripcion',
        DB::raw("TO_CHAR(emergencias.fecha_registro_ingreso, 'DD/MM/YYYY') as fecha_registro_ingreso"),
        'emergencias.hora_registro_ingreso',
        'emergencias.hora_egreso_medico'
    ])
    ->orderBy('emergencias.fecha_registro_ingreso', 'asc')
    ->get();
    return $data;
  }
  private function asignarCamaYCuarto($hospitalizacion, $idCama)
  {
    $model_cuarto = new \App\Models\Cuarto;
    $datos       = $model_cuarto->GetCuartoCamaId($idCama);
    if (!empty($datos) && isset($datos[0])) {
        $fila = $datos[0];
        $hospitalizacion->id_pabellon          = $fila->id_pabellon;
        $hospitalizacion->id_piso              = $fila->id_piso;
        $hospitalizacion->id_consultorio_hosp  = $fila->id_asignacion_servicio;
        $hospitalizacion->nombre_consultorio   = $fila->nombre_consultorio;
        $hospitalizacion->id_cuarto            = $fila->id_cuarto;
        $hospitalizacion->id_cama              = $idCama;
        $hospitalizacion->id_consultorio       = $fila->id_consultorio;
        $hospitalizacion->id_cama_estado       = $idCama;
    }
  }

  public function mostrar_atencion_hospitalizacion_ajax($id_prestacion)
  {
    $modelfarma = new Farmacia;
    $model_receta = new Receta_vale;
    $model_producto = new Producto;
    $model_productos_receta = new Producto_receta;
    $model_establecimiento = new \App\Models\Establecimiento;
    $model_prestacion = new \App\Models\Prestaciones;
    $model_laboratorio = new \App\Models\Laboratorio;
    $model_radiologia = new \App\Models\Radiologia;
    $model_interconsulta = new \App\Models\Interconsulta;
    $model_referencia = new \App\Models\Referencia;
    $model_kardex = new Kardexe();

    $farmacia = $modelfarma->GetFarmaByUser(Auth::user()->id);
    $flag_receta_dias = $farmacia->flag_receta_dias;

    $fechaServidor = $model_kardex->getFechaServidor();
    $date = Carbon::now();
    $fecha_actual = $date->format('d-m-Y');
    $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
    $receta = $model_receta->GetDatosRecetasByIdPrestacion($id_prestacion);

    $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacionEmergencia($id_prestacion);
    /*$diagnosticos = \App\Models\Diagnostico::where('estado',1)
    ->whereRaw('LENGTH(codigo) > 4') 
    ->get();*/
    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionYTipo($id_prestacion, 1);
    /*$procedimientos = \App\Models\Procedimiento::where('estado',1)
    ->get();*/
    $laboratorio = $model_laboratorio->GetLaboratorioByIdPrestacion($id_prestacion);
    $radiologia = $model_radiologia->GetRadiologiaByIdPrestacion($id_prestacion);
    $pendiente_firma = $model_prestacion->GetDocumentosPendintesFirmarPrestacionId($id_prestacion);
    $flag_pendinte_firma = (count($pendiente_firma) > 0) ? 1 : 0;

    $id_ipress = $farmacia->id_establecimiento;
    // if($id_ipress==76): //lns
    //   $id_farmacia = 19; //farmacia de emergencia
    // else:
      $id_farmacia = $farmacia->id;
    // endif;
    $establecimiento = $model_establecimiento->GetById($id_ipress);
    // if ($id_ipress == 1) {
    //   $nivel = 2;
    // } else {
      $nivel = $establecimiento[0]->nivel;
    // }
    //Si tiene atenciones en hospitalizacion o emergencia
    $sw = 0;
    if($prestacion->id_ext!=0):
      if($prestacion->id_tipo_atencion==3): // HOSPITALIZACION
        $dato_hospitalizacion = Hospitalizacione::where('id', '=', $prestacion->id_ext)->first();
        // $obj_hospitalizacion_atencion = \App\Models\HospitalizacionAtencione::where('id_prestacion', '=', $id_prestacion)->first();
        $obj_hospitalizacion_atencion = \App\Models\HospitalizacionAtencione::select('ha.*', 
            'p.fecha_parto', 
            'p.id_tipo_parto as puerperio_id_tipo_parto', 
            'tp.nombre as puerperio_tipo_parto', 
            'p.complicaciones as puerperio_complicaciones', 
            'p.descripcion as puerperio_descripcion')
        ->from('hospitalizacion_atenciones as ha')
        ->leftJoin('puerperios as p', 'ha.id_puerperio', '=', 'p.id')
        ->leftJoin('tipo_partos as tp', 'p.id_tipo_parto', '=', 'tp.id')
        ->where('ha.id_prestacion', '=', $id_prestacion) // Cambié aquí a 'ha.id' para que coincida con el contexto
        ->first();
        $producto = $model_producto->getProductosByNivelAndIdServicio($nivel, $id_ipress, $id_farmacia); 
      endif;
    else:
         return redirect('/dashboard')->withInput()->withErrors(array('No encontro la atención..!'));
    endif;
    
    $producto = $model_producto->getProductosByNivelAndIdServicio($nivel, $prestacion->id_consultorio, $id_farmacia);
    
    $farmacias = $modelfarma->GetFarmaciaById($id_farmacia);
    $establecimientos = $model_establecimiento->ShowAllEstablecimientos();
    $interconsultas = $model_interconsulta->GetInterconsultaByIdPrestacion($id_prestacion);

    $interconsultas_id_servicio = [];
    if (!empty($interconsultas)) {
      foreach ($interconsultas as $ic) {
        $interconsultas_id_servicio[] = $ic->id_servicio;
      }
    }

    $referencia = $model_referencia->GetReferenciaByIdPrest($id_prestacion);
    
    $flag_no_encontrado=0; 
    $model_asegurado= new Asegurado();
    if($prestacion->id_asegurado_titular!=null || $prestacion->id_asegurado_titular>0):
      $dato_titular = $model_asegurado->Busca_datos_titular($prestacion->id_asegurado_titular);
    else:
      $flag_no_encontrado=1;
    endif;

    $ruta = "all_hospitalizado_by_establecimiento";
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
    $permiso = $menu[0];

    $date = Carbon::now();
    $hora=$date->toTimeString(); 

    if($hora>'01:30' & $hora<'07:31'):
      /*echo*/ $turno='MD';
    else:
      if($hora>'07:30' & $hora<'13:31'):
        /*echo*/ $turno='M';
      else:
        if($hora>'13:30' & $hora<'19:31'):
          /*echo*/ $turno='T';
        else:
          if($hora>'19:30' & $hora<='23:59'):
            /*echo*/ $turno='N';
          else:
            if($hora>='00:00' & $hora<='01:31'):
              /*echo*/ $turno='N';
            endif;
          endif;
        endif;
      endif;
    endif;

    $talla_cm = $prestacion->talla_cm;
    $peso_kg = $prestacion->peso_kg;
    $edadpaciente = $prestacion->edadpaciente;
    $sexo = $prestacion->sexo;
    $peso_ideal = "";
    $imc = "";
    
    // Cálculo del peso ideal
    if ($talla_cm != "0") { 
      // Peso por altura
      $peso_ideal = 50 + (($talla_cm - 150) / 4) * 3 + ($edadpaciente - 20) / 4;
      if ($sexo == "M")
        $peso_ideal = $peso_ideal * 0.9;
      
      if($peso_ideal <= 0)
        $peso_ideal = '';
      
    } else {
      $peso_ideal = "";
    }

    // Cálculo del IMC
    if ($peso_kg != "0" && $talla_cm != "0") {
      $talla_en_metros = $talla_cm * 0.01;
      $imc = "";
      if ($peso_kg > 0 && $talla_cm > 0) {
        $imc = $peso_kg / ($talla_en_metros * $talla_en_metros);
        $imc = number_format($imc, 2, '.', '');
      }
    } else {
      $imc = "";
    }
    // Formateo final
    $peso_ideal = ($peso_ideal == "") ? "-" : $peso_ideal;
    $imc = ($imc == "") ? "-" : $imc;

    $lab = $model_laboratorio->GetLaboratorioByIdPrestacion($id_prestacion);
    if (empty($lab)) {
      $flag_laboratorio = "0";
      $flag_firmar_laboratorio = "";
    } else {
      $flag_laboratorio = "1";
      $flag_firmar_laboratorio = $lab[0]->fecha_firma;
    }
    $rad = $model_radiologia->GetRadiologiaByIdPrestacion($id_prestacion);
    if (count($rad)>0) {
    
      $flag_radiologia = "1";
      $flag_firmar_radiologia = $rad[0]->fecha_firma;
    } else {
      $flag_radiologia = "0";
      $flag_firmar_radiologia = "";
    }

    $ordenImagenesAnulada = $model_radiologia->cpmsAnulados(2,$id_prestacion);
    $orden = '';
    $ordenes = [];
    if ( count($ordenImagenesAnulada) > 0 ) {
      foreach ($ordenImagenesAnulada as $key) {
        if ( $orden !== $key->id_radiologia ) {
          $orden = $key->id_radiologia;

          $obj = new stdClass();
          $obj->id_radiologia = $key->id_radiologia;
          $obj->obs_examen_invalidado = $key->obs_examen_invalidado;
          $obj->fecha_orden_invalidado = $key->fecha_orden_invalidado;
          $obj->usuario = $key->name . ', ' . $key->paterno; 

          $ordenes[] = $obj;
        }
      }

      foreach ($ordenes as $key1) {
        $key1->cpms = [];
        foreach ($ordenImagenesAnulada as $key2) {
          if ($key1->id_radiologia == $key2->id_radiologia) {
            $obj = new stdClass();
            $obj->id_pp = $key2->id;
            $obj->codigo_rad = $key2->codigo_rad;
            $obj->descripcion_corta = $key2->descripcion_corta;
            $obj->diagnostico = $key2->diagnostico;

            $key1->cpms[] = $obj;
          }  
        }  
      }

      
    }

    $model_medico = new Medico();
    $medico = $model_medico->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni,Auth::user()->id_establecimiento );
    $is_profesion_medico = false;
    if (count($medico) > 0) {
      switch ($medico->id_profesion) {
        case 20: case 4: case 51: case 52: case 1: $is_profesion_medico = true; break;
      }
    }

    $model_consultorio = new Consultorio;
    $establecimiento_servicios = $model_consultorio->GetServiciosPorEstablecimiento($id_ipress);

    $eventosMarcados = Prestacion_eventos::where('id_prestacion', $id_prestacion)
        ->where('estado', 1)
        ->lists('id_evento')
        ->toArray();
    $gestacion = Gestacion::where("id", $obj_hospitalizacion_atencion->id_gestacion)->first();
    $puerperio = Puerperio::where("id", $obj_hospitalizacion_atencion->id_puerperio)->first();

    $solicitud_pp = SolicitudProgramacionProcedimientos::where("id_prestacion", $prestacion->id)->first();
    $detallesAgrupados = null;
    if ($solicitud_pp) {
      $detallesAgrupados = $solicitud_pp->detalles->groupBy('id_procedimiento');
    }

    return view('frontend.hospitalizacion.ver_atencion_detalle', compact('dato_titular','farmacia','solicitud_pp','detallesAgrupados','gestacion','eventosMarcados','prestacion', 'puerperio','diagnostico', 'procedimiento', 'medicamentos', 'id_prestacion', 'fechaServidor', 'receta', 'producto', 'farmacias', 'nivel', 'laboratorio', 'radiologia', 'flag_pendinte_firma','establecimientos','interconsultas','referencia','procedimientos','datoadicional','sw','permiso','flag_no_encontrado','turno','id_farmacia','dato_hospitalizacion','obj_hospitalizacion_atencion','peso_ideal','imc','flag_laboratorio','flag_firmar_laboratorio','flag_radiologia','flag_firmar_radiologia','ordenImagenesAnulada','ordenes','is_profesion_medico', 'interconsultas_id_servicio','establecimiento_servicios'));
  }

  public function verificarAltaHospitalizacionCqx($dni)
  {
      // 1. VALIDAR ALTA PENDIENTE DE HOSPITALIZACIÓN
      $altaPendiente = Hospitalizacione::select(
              'hospitalizaciones.estado',
              'c.nombre as servicio',
              'hospitalizaciones.fecha_ingreso'
          )
          ->join('cqx_intervenciones as ci', 'hospitalizaciones.id', '=', 'ci.id_hospitalizacion')
          ->join('consultorios as c', 'ci.id_consultorio', '=', 'c.id')
          ->where('hospitalizaciones.dni_beneficiario', $dni)
          ->whereIn('hospitalizaciones.estado', [1, 4])
          ->first();

      // 2. VALIDAR SI TIENE ORDENES REGISTRADAS
      $ordenSolicitud = DB::table('hospitalizacion_ordenes as ho')
          ->select(
              'ci.fecha_registro',
              'c.nombre as servicio'
          )
          ->join('cqx_intervenciones as ci', 'ho.id_ext', '=', 'ci.id')
          ->join('consultorios as c', 'ci.id_consultorio', '=', 'c.id')
          ->join('asegurados as a', 'ci.id_asegurado', '=', 'a.id')
          ->where('ho.flag', 1)
          ->where('a.nro_doc_ident', $dni)
          ->first();

    return response()->json([
      'mensaje' => $this->generarMensajeDetalle($dni,$altaPendiente,$ordenSolicitud)
    ]);
  }

  private function generarMensajeDetalle($dni, $alta, $orden)
  {
      $fechaAlta = $alta ? date('d/m/Y', strtotime($alta->fecha_ingreso)) : null;
      $fechaOrden = $orden ? date('d/m/Y', strtotime($orden->fecha_registro)) : null;

      if ($alta && $orden) {
          return "El paciente con DNI <strong>$dni</strong> tiene:<br>
          - Alta pendiente en el servicio {$alta->servicio}, ingresado el <strong>$fechaAlta</strong>.<br>
          - Y tiene una orden registrada en el servicio <strong>{$orden->servicio}</strong>, fecha <strong>$fechaOrden</strong>.";
      }

      if ($alta) {
          return "El paciente con DNI <strong>$dni</strong> está pendiente de alta de hospitalización.<br>
          Servicio: <strong>{$alta->servicio}</strong><br>
          Fecha ingreso: <strong>$fechaAlta</strong><br><br>
          ¿Desea continuar o cancelar?";
      }

      if ($orden) {
          return "El paciente con DNI <strong>$dni</strong> tiene una orden registrada.<br>
          Servicio: <strong>{$orden->servicio}</strong><br>
          Fecha registro: <strong>$fechaOrden</strong>";
      }

      return "El paciente con DNI <strong>$dni</strong> no tiene hospitalización pendiente ni órdenes registradas.";
  }
  public function form_atencion_prestacion_hspcu($id_user, $id_principal) {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    if (Auth::user()->tipo_registro == 1) {
      $hospitalizacion_check = Hospitalizacione::where('id', $id_principal)->first();
      if (!$hospitalizacion_check || !$hospitalizacion_check->esFlujoDosVistas()) {
        return redirect('/dashboard')->withErrors(array('Esta atención no corresponde al flujo de dos vistas de Hospitalización UCI.'));
      }
      if ($hospitalizacion_check->flag_exa_cu != 1) {
        Session::flash('flash_message', 'Primero debe completar el Registro de Exámenes (Vista 1).');
        return redirect('/form_atenciones_hospitalizacion/' . $id_principal . '/' . $hospitalizacion_check->id_servicio_encargado);
      }
      $id_prestacion = \App\Models\Prestaciones::where('id_ext', $id_principal)->where('id_tipo_atencion', 3)->where('id_estado_reg', 1)->orderBy('id', 'DESC')->value('id');
      if (!$id_prestacion) {
        return redirect('/dashboard')->withErrors(array('No se encontró la atención de exámenes registrada para esta hospitalización.'));
      }
      if ($hospitalizacion_check->flag_hc_cu == 1) {
        return redirect('/ver_prestacion_hospitalizacion/' . $id_user . '/' . $id_prestacion);
      }
      if ((int) $hospitalizacion_check->id_user_exa_cu !== (int) Auth::user()->id) {
        $msg_otro_usuario = 'Esta atención corresponde a otro usuario. No tiene autorización para continuarla. Médico responsable del registro: ' . $hospitalizacion_check->nombreMedicoRegistroHc();
        Session::flash('flash_message', $msg_otro_usuario);
        return redirect('/all_hospitalizado_by_establecimiento/')->withErrors(array($msg_otro_usuario));
      }

      $modelfarma = new Farmacia;
      $model_receta = new Receta_vale;
      $model_producto = new Producto;
      $model_productos_receta = new Producto_receta;
      $model_establecimiento = new \App\Models\Establecimiento;
      $model_prestacion = new \App\Models\Prestaciones;
      $model_laboratorio = new \App\Models\Laboratorio;
      $model_radiologia = new \App\Models\Radiologia;
      $model_interconsulta = new \App\Models\Interconsulta;
      $model_referencia = new \App\Models\Referencia;
      $model_kardex = new Kardexe();

      $hospitalizacion = Hospitalizacione::where('id', $id_principal)->first();
      $estado = $hospitalizacion->estado;
      $model_medicos = new Medico;
      $medico = $model_medicos->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni, Auth::user()->id_establecimiento);
      $model_hospitalizacion = new Hospitalizacione;
      $cita = $model_hospitalizacion->GetBuscaHospitalizado($id_principal, $medico->id, $estado);


      $farmacia = $modelfarma->GetFarmaByUser($id_user);
      $flag_receta_dias = $farmacia->flag_receta_dias;

      $fechaServidor = $model_kardex->getFechaServidor();
      $date = Carbon::now();
      $fecha_actual = $date->format('d-m-Y');
      $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
      $receta = $model_receta->GetDatosRecetasByIdPrestacion($id_prestacion);

      $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacionEmergencia($id_prestacion);
      $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionYTipo($id_prestacion, 1);

      $id_ipress = $farmacia->id_establecimiento;
      $id_farmacia = $farmacia->id;
      $establecimiento = $model_establecimiento->GetById($id_ipress);
      $nivel = $establecimiento[0]->nivel;
      if($prestacion->id_ext!=0):
        if($prestacion->id_tipo_atencion==3): // HOSPITALIZACION
          $dato_hospitalizacion = Hospitalizacione::where('id', '=', $prestacion->id_ext)->first();
        endif;
      else:
           return redirect('/dashboard')->withInput()->withErrors(array('No encontro la atención..!'));
      endif;


      $flag_no_encontrado=0;
      $model_asegurado= new Asegurado();
      if($prestacion->id_asegurado_titular!=null || $prestacion->id_asegurado_titular>0):
        $dato_titular = $model_asegurado->Busca_datos_titular($prestacion->id_asegurado_titular);
      else:
        $flag_no_encontrado=1;
      endif;

      $date = Carbon::now();
      $hora=$date->toTimeString();

      if($hora>'01:30' & $hora<'07:31'):
        $turno='MD';
      else:
        if($hora>'07:30' & $hora<'13:31'):
          $turno='M';
        else:
          if($hora>'13:30' & $hora<'19:31'):
            $turno='T';
          else:
            if($hora>'19:30' & $hora<='23:59'):
              $turno='N';
            else:
              if($hora>='00:00' & $hora<='01:31'):
                $turno='N';
              endif;
            endif;
          endif;
        endif;
      endif;

      // Validar campo de habilitacion de firma electronica
      $is_habilitado_firma_electronica = $establecimiento[0]->flag_firma_electronica;

        $modelcama = new Cama();
        $model_hospitalizacion = new Hospitalizacione;
        $date = Carbon::now();
        $fechaServidor = $date->format('Y-m-d H:i:s');
        $hora = $date->format('H:i');

        $cuartos = $modelcama->GetAllPabellonesYServicioOSalaYCuartos($prestacion->id_establecimiento,'HOSPITALIZACION');
        $cama = $model_hospitalizacion->GetObtenerCama($dato_hospitalizacion->id_cama_estado);

        if ($dato_hospitalizacion->id_servicio_destino != null) {
          $id_cuarto_actual = $dato_hospitalizacion->id_servicio_destino;
        } else {
          $id_cuarto_actual = $cama->id_consultorio;
        }
        $camas = $modelcama->GetCamasByIdEstablecimientoYNombreTipoAtencionYIdCuarto($dato_hospitalizacion->id_establecimiento,'HOSPITALIZACION',$cama->id_consultorio);
      /////////////////////////////////////////////////////////////////////////////////////////////////////
      $id_principal=$prestacion->id_ext;
      $cambio_cama_model = new App\Models\CambioCama();
      $cambio_cama_data = $cambio_cama_model->getDataByIdPrestacion($id_prestacion);
      $upss_encargado = DB::table('upsses as u')
                          ->join('consultorios as c', 'c.id', '=', 'u.id_consultorio')
                          ->select('u.*', 'c.nombre as nombre_consultorio')
                          ->where('u.id_consultorio', $hospitalizacion->id_servicio_encargado)
                          ->where('u.id', $hospitalizacion->id_upss_encargado)
                          ->where('u.id_tipo_atencion', 3)
                          ->where('u.estado', 1)
                          ->first();
      $regularizar = ($upss_encargado == null) ? "1" : "0";
      $tipo_doc_nati = tipoDocNati::all();
      $client = new SoapClient(env('WI_SALUDPOL_URL') . "/ws/WS_AfiliadoSP_Serv.php?wsdl", array('login' => env('WI_SALUDPOL_USERNAME'), 'password' => env('WI_SALUDPOL_PASSWORD')));
	    if($cita->tipo_doc_ident=='DNI'){
	        $tipo_doc_ident = '1';
	    }else if($cita->tipo_doc_ident=='CE'){
	        $tipo_doc_ident = '2';
	    }else if($cita->tipo_doc_ident=='PAS'){
	        $tipo_doc_ident = '3';
	    }else if($cita->tipo_doc_ident=='DIE'){
	        $tipo_doc_ident = '4';
	    }
	    $beneficiario = $client->getAseguradoValidate($tipo_doc_ident, $cita->nro_doc_ident);

	    if (is_object($beneficiario)) :
	      $flag = 0 ;
	      $errorWS = 1 ;
	    else:
	      $flag = 1 ;
	      $errorWS = 0 ;
	    endif;
      $id_cita = $id_principal;
      $id_tipo_atencione = 3;
      $model_consultorio = new App\Models\Consultorio;
      if ($hospitalizacion->id_servicio_destino != null) {
        $servicio_ubicacion = $model_consultorio->GetNombreById($hospitalizacion->id_servicio_destino);
      } else if ($hospitalizacion->id_cama_estado != null) {
        $model_cama = new App\Models\Hospitalizacione;
        $data_cama = $model_cama->GetObtenerCama($hospitalizacion->id_cama_estado);
        $servicio_ubicacion = $model_consultorio->GetNombreById($data_cama->id_consultorio);
      }
      $medicoservicio = $model_consultorio->MedicoDetalleServicio($id_ipress,$medico->id);
      $upss_cargo = $upss_encargado->nombre_consultorio;
      $servicio_medico = $medicoservicio->servicio;
      $establecimiento_servicios = $model_consultorio->GetServiciosPorEstablecimiento($id_ipress);
      $model_hospitalizacion_atencion = new \App\Models\HospitalizacionAtencione;
      $h_antecedente = $model_hospitalizacion_atencion->GetByIdAsegurado($hospitalizacion->id_asegurado);
      $model_signos = new \App\Models\signosVitales();
      $signo_vital = $model_signos->GetUltimoRegistroSignoVital(3, $id_principal);
      $signos_vitales = null;
      if ($signo_vital != null) {
        $signos_vitales = $model_signos->GetSignosVitalesDetalleById($signo_vital->id);
      }
      /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      return view('frontend.hospitalizacion.form_atencion_hospitalizacion_cu2', compact('hospitalizacion','cita','regularizar','tipo_doc_nati','flag','id_cita','upss_cargo','upss_encargado','id_tipo_atencione','servicio_medico','signos_vitales','h_antecedente','servicio_ubicacion','dato_titular','farmacia','prestacion','diagnostico', 'procedimiento', 'id_prestacion', 'fechaServidor', 'receta', 'nivel','flag_no_encontrado','turno','id_farmacia','dato_hospitalizacion', 'is_habilitado_firma_electronica','id_cuarto_actual','cuartos','camas','hora','id_principal','cambio_cama_data','establecimiento_servicios'));
    }
    else
    {
      return redirect('/dashboard')->withInput()->withErrors(array('No tiene el perfil de médico..!'));
    }
  }
  
}
