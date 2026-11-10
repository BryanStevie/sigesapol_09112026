<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use DB;
use App;
use App\Models\Auditoria;
use App\Models\Actualiza_estados_trama;
use App\Models\Establecimiento;
use App\Models\Farmacia;
use App\Models\Prestaciones;
use Illuminate\Support\Facades\Route;
use App\Models\trama_prestacion;
use App\Models\trama_producto;
use Auth;
use Carbon\Carbon;
use App\UseCases\Auditoria\ValidarTramasMasivasUseCase;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Hospitalizacione; 
use App\Models\Emergencia; 
use App\Models\Consultorio;
use App\Models\Medico;
use stdClass;


class AuditoriaController extends Controller
{ 
  public function all_auditoria($id_user = 0)
  {
    $model_medico = new \App\Models\Medico();
    $model_usuario = new User;
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
    if ($ruta == "listar_auditoria_ajax");
    $ruta = "all_auditoria";
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);

    $date = Carbon::now();
    $fechaHasta = $date->format('d-m-Y');
    $fechaDesdex = $date->format('d-m-Y');
    $fechaDesdey = strtotime('-1 day', strtotime($fechaDesdex));
    $fechaDesde = date('j-m-Y', $fechaDesdey);
    //====================
    $model_farma = new Farmacia();
    $farmacia = $model_farma->GetFarmaByUser($id_user);

    $dni_beneficiario = '';
    $nombre_beneficiario = '';
    $estado = 10;
    $buscar_servicio = 0;
    $buscar_servicio_destino = 0;
    $buscar_ipress_origen_servicio=0;
    $establecimiento_actual = Auth::user()->id_establecimiento;
    $buscar_ipress_origen_servicio=0;
    $buscar_ipress_origen_medico=0;
    $editado_auditoria=0;
    $servicio = "SELECT distinct (c.id) as id,
    c.nombre
    from sub_consultorios sc
    inner join consultorios c on c.id = sc.id_consultorio
    where sc.id_establecimiento  = '$establecimiento_actual' order by nombre asc ";
    $servicio = DB::select($servicio);

    $niveles = DB::table('establecimientos')
    ->select('nivel')
    ->distinct()
    ->where('nivel', '!=', '0')
    ->orderBy('nivel')
    ->get();

    if($establecimiento_actual == '76'){
      $establecimientos = DB::table('establecimientos')
      ->select('id', 'nombre','nivel','nivel_real')
      ->distinct()
      ->where('id_estado_reg','!=','0')
      ->where('id','!=','78')
      ->where('id','!=','96')
      ->whereNotNull('codigo')
      ->orderBy('nivel_real', 'desc')
      ->orderBy('nombre', 'asc')
      ->get();
      $id_establecimiento = '0';
    }else{
      $establecimientos = DB::table('establecimientos')
      ->select('id', 'nombre','nivel','nivel_real')
      ->distinct()
      ->where('id_estado_reg','!=','0')
      ->where('id','!=','78')
      ->where('id','!=','96')
      ->where('id','=',$establecimiento_actual)
      ->whereNotNull('codigo')
      ->orderBy('nivel_real', 'desc')
      ->orderBy('nombre', 'asc')
      ->get();
      $id_establecimiento = $establecimiento_actual;
    }
   
    $servicios = DB::table('consultorios')
    ->select('id', 'nombre')
    ->distinct()
    ->where('estado','=','4')
    ->orderBy('nombre')
  ->get();

    $medicos = DB::table('medicos')
    ->select('id', 'nombre', 'paterno', 'materno')
    ->distinct()
    ->where('estado','!=','0')
    ->orderBy('nombre')
    ->get();

    $establecimiento = "SELECT e.*,e.id,e.nombre,e.nivel from establecimientos e where e.id_estado_reg='1' and id != '78' and ubigeo is not null and codigo is not null order by nivel  desc ";
    $establecimiento = DB::select($establecimiento);
    $id_user = Auth::user()->id;
 
    return view('frontend.auditoria.all_auditoria', compact('medicos','servicios','id_user','medico_auditor','nivel','medicos','servicios_trama','editado_auditoria',
    'trama_prestacion','establecimientos','niveles','niveles1','farmacia', 'fechaDesde','buscar_servicio','id_establecimiento','buscar_servicio_destino',
    'establecimiento','establecimiento_actual', 'fechaHasta', 'dni', 'buscar_nivel','establecimientos','nombre_beneficiario', 'estado'));
  }

  public function vista_mejorada($id_user = 0)
  {
    $model_medico = new \App\Models\Medico();
    $model_usuario = new User;
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
    if ($ruta == "listar_auditoria_hospi_ajax");
    $ruta = "all_auditoria";
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);

    
    $date = Carbon::now();
    $fechaHasta = $date->format('d-m-Y');
    $fechaDesdex = $date->format('d-m-Y');
    $fechaDesdey = strtotime('-1 day', strtotime($fechaDesdex));
    $fechaDesde = date('j-m-Y', $fechaDesdey);
    //====================
    $model_farma = new Farmacia();
    $farmacia = $model_farma->GetFarmaByUser($id_user);

    $dni_beneficiario = '';
    $nombre_beneficiario = '';
    $estado = 10;
    $buscar_servicio = 0;
    $buscar_servicio_destino = 0;
    $buscar_ipress_origen_servicio=0;
    $establecimiento_actual = Auth::user()->id_establecimiento;
    $buscar_ipress_origen_servicio=0;
    $buscar_ipress_origen_medico=0;

    $servicio = "SELECT distinct (c.id) as id,
    c.nombre
    from sub_consultorios sc
    inner join consultorios c on c.id = sc.id_consultorio
    where sc.id_establecimiento  = '$establecimiento_actual' order by nombre asc ";
    $servicio = DB::select($servicio);

    $niveles = DB::table('establecimientos')
    ->select('nivel')
    ->distinct()
    ->where('nivel', '!=', '0')
    ->orderBy('nivel')
    ->get();

    $establecimientos = DB::table('establecimientos')
    ->select('id', 'nombre','nivel','codigo')
    ->distinct()
    ->where('id_estado_reg','!=','0')
    ->where('nivel','!=','0')
    ->whereNotNull('codigo')     
    ->orderBy('nivel', 'desc')
    ->get();
  
    $servicios = DB::table('consultorios')
    ->select('id', 'nombre')
    ->distinct()
    ->where('estado','=','4')
    ->orderBy('nombre')
  ->get();

    $medicos = DB::table('medicos')
    ->select('id', 'nombre', 'paterno', 'materno')
    ->distinct()
    ->where('estado','!=','0')
    ->orderBy('nombre')
    ->get();

    $establecimiento = "SELECT e.*,e.id,e.nombre,e.nivel from establecimientos e where e.id_estado_reg='1' and ubigeo is not null and codigo is not null order by nivel  desc ";
    $establecimiento = DB::select($establecimiento);
    $id_user = Auth::user()->id;
 
    return view('frontend.auditoria.vista_mejorada', compact('medicos','servicios','id_user','medico_auditor','nivel','medicos','servicios_trama','establecimientos','trama_prestacion','establecimientos','niveles','niveles1','farmacia', 'fechaDesde','buscar_servicio','id_establecimiento','buscar_servicio_destino','servicio','establecimiento','establecimiento_actual', 'fechaHasta', 'dni', 'buscar_nivel','establecimientos','nombre_beneficiario', 'estado'));
  }

  public function reglas_aplicadas($id_cups)
  {
    $model_medico = new \App\Models\Medico();
    $model_usuario = new User;
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
    if ($ruta == "listar_auditoria_hospi_ajax");
    $ruta = "all_auditoria";
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);

    $date = Carbon::now();
    $anioActual = date('Y'); 
    $mesActual = date('n');  

    $cups_pnp = DB::table('cups_pnp')
    ->select('cups_pnp.*')
    ->where('id',$id_cups)   
    ->first();    
    $trama = DB::table('trama_prestacion')
    ->select('trama_prestacion.*')
    ->where('id_cups',$id_cups)
    ->first();
  
    $model_auditoria = new \App\Models\Auditoria;

    $reglas_1 = $model_auditoria->GetreglasRC_01($id_cups,$cups_pnp->tipo_atencion);
    $rc1 = $reglas_1[0];
    $reglas_2 = $model_auditoria->GetreglasRC_02($id_cups,$cups_pnp->tipo_atencion);
    $rc2 = $reglas_2[0];
    $reglas_3 = $model_auditoria->GetreglasRC_03($id_cups,$cups_pnp->tipo_atencion);
    $rc3 = $reglas_3[0];
    $reglas_21 = $model_auditoria->Getreglas2($id_cups,$cups_pnp->tipo_atencion);
    $rc4 = $reglas_21[0];
    $reglas_3 = $model_auditoria->Getreglas3($id_cups,$cups_pnp->tipo_atencion);
    $rc9 = $reglas_3;
    $reglas_4 = $model_auditoria->Getreglas4($id_cups,$cups_pnp->tipo_atencion);
    $rc12 = $reglas_4;
    $reglas_5 = $model_auditoria->Getreglas5($id_cups,$cups_pnp->tipo_atencion);
    $rc13 = $reglas_5;
    $reglas_6 = $model_auditoria->Getreglas6($id_cups,$cups_pnp->tipo_atencion);
    $rc14 = $reglas_6;
    $reglas_7 = $model_auditoria->Getreglas7($id_cups,$cups_pnp->tipo_atencion);
    $rc15 = $reglas_7;
    $reglas_8 = $model_auditoria->Getreglas8($id_cups,$cups_pnp->tipo_atencion);
    $rc16 = $reglas_8;
    $reglas_9 = $model_auditoria->Getreglas9($id_cups,$cups_pnp->tipo_atencion);
    $rc21 = $reglas_9;
    $reglas_10 = $model_auditoria->Getreglas10($id_cups,$cups_pnp->tipo_atencion);
    $rc23 = $reglas_10;
    $reglas_11 = $model_auditoria->Getreglas11($id_cups,$cups_pnp->tipo_atencion);
    $rv9 = $reglas_11;
    $reglas_13 = $model_auditoria->Getreglas13($id_cups,$cups_pnp->tipo_atencion);
    $rc17 = $reglas_13;
    //dd($rc3);
    return view('frontend.auditoria.vista_reglas_aplicadas', compact('trama','rc1','rc2','rc3','rc4','rc9','rc12','rc13','rc14','rc15','rc16','rc17'));
  }

  public function vista_reporte($id_user = 0)
  {
    $model_medico = new \App\Models\Medico();
    $model_usuario = new User;
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
    if ($ruta == "listar_auditoria_hospi_ajax");
    $ruta = "all_auditoria";
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);

    $date = Carbon::now();
    $anioActual = date('Y'); 
    $mesActual = date('n');  

    $model_farma = new Farmacia();
    $farmacia = $model_farma->GetFarmaByUser($id_user);
    $establecimiento_actual = Auth::user()->id_establecimiento;

    $establecimientos = DB::table('establecimientos')
    ->select(
        'establecimientos.id',
        'establecimientos.nombre',
        'establecimientos.nivel',
        'establecimientos.codigo',
        'establecimientos.ubigeo',
        'departamentos.descripcion as departamento',
        'provincias.descripcion as provincia',
        'distritos.descripcion as distrito'
    )
    ->distinct()
    ->join('departamentos', DB::raw('SUBSTRING(establecimientos.ubigeo, 1, 2)'), '=', 'departamentos.codigo')
    ->join('provincias', DB::raw('SUBSTRING(establecimientos.ubigeo, 1, 4)'), '=', 'provincias.codigo')
    ->join('distritos', 'establecimientos.ubigeo', '=', 'distritos.codigo')
    ->where('establecimientos.id_estado_reg', '!=', '0')
    ->where('establecimientos.nivel', '!=', '0')
    ->whereNotNull('establecimientos.codigo')
    ->orderBy('establecimientos.nivel', 'desc')
    ->get();

    $id_user = Auth::user()->id;
    $id_establecimiento = Auth::user()->id_establecimiento;
    $establecimiento_codigo = DB::table('establecimientos')
    ->select('codigo')
    ->distinct()
    ->where('id_estado_reg','!=','0')
    ->where('id',$id_establecimiento)   
    ->first();    
    $codigo_est = $establecimiento_codigo->codigo;

    return view('frontend.auditoria.vista_reportes', compact('id_user','codigo_est','mesActual','anioActual','establecimientos','farmacia'));
  }

  public function all_auditoria_hospi($id_user = 0)
  {
    $model_medico = new \App\Models\Medico();
    $model_usuario = new User;
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
    if ($ruta == "listar_auditoria_hospi_ajax");
    $ruta = "all_auditoria";
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);

    
    $date = Carbon::now();
    $fechaHasta = $date->format('d-m-Y');
    $fechaDesdex = $date->format('d-m-Y');
    $fechaDesdey = strtotime('-1 day', strtotime($fechaDesdex));
    $fechaDesde = date('j-m-Y', $fechaDesdey);
    //====================
    $model_farma = new Farmacia();
    $farmacia = $model_farma->GetFarmaByUser($id_user);

    $dni_beneficiario = '';
    $nombre_beneficiario = '';
    $estado = 10;
    $buscar_servicio = 0;
    $buscar_servicio_destino = 0;
    $buscar_ipress_origen_servicio=0;
    $establecimiento_actual = Auth::user()->id_establecimiento;
    $buscar_ipress_origen_servicio=0;
    $buscar_ipress_origen_medico=0;
    $editado_auditoria=0;
    $servicio = "SELECT distinct (c.id) as id,
    c.nombre
    from sub_consultorios sc
    inner join consultorios c on c.id = sc.id_consultorio
    where sc.id_establecimiento  = '$establecimiento_actual' order by nombre asc ";
    $servicio = DB::select($servicio);

    $niveles = DB::table('establecimientos')
    ->select('nivel')
    ->distinct()
    ->where('nivel', '!=', '0')
    ->orderBy('nivel')
    ->get();

    if($establecimiento_actual == '76'){
      $establecimientos = DB::table('establecimientos')
      ->select('id', 'nombre','nivel','nivel_real')
      ->distinct()
      ->where('id_estado_reg','!=','0')
      ->where('id','!=','78')
      ->where('id','!=','96')
      ->whereNotNull('codigo')
      ->orderBy('nivel_real', 'desc')
      ->orderBy('nombre', 'asc')
      ->get();
      $id_establecimiento = '0';
    }else{
      $establecimientos = DB::table('establecimientos')
      ->select('id', 'nombre','nivel','nivel_real')
      ->distinct()
      ->where('id_estado_reg','!=','0')
      ->where('id','!=','78')
      ->where('id','!=','96')
      ->where('id','=',$establecimiento_actual)
      ->whereNotNull('codigo')
      ->orderBy('nivel_real', 'desc')
      ->orderBy('nombre', 'asc')
      ->get();
      $id_establecimiento = $establecimiento_actual;
    }

    $servicios = DB::table('consultorios')
    ->select('id', 'nombre')
    ->distinct()
    ->where('estado','=','4')
    ->orderBy('nombre')
  ->get();

    $medicos = DB::table('medicos')
    ->select('id', 'nombre', 'paterno', 'materno')
    ->distinct()
    ->where('estado','!=','0')
    ->orderBy('nombre')
    ->get();

    $establecimiento = "SELECT e.*,e.id,e.nombre,e.nivel from establecimientos e where e.id_estado_reg='1' and ubigeo is not null and codigo is not null order by nivel  desc ";
    $establecimiento = DB::select($establecimiento);
    $id_user = Auth::user()->id;
 
    return view('frontend.auditoria.all_auditoria_hospi', compact('medicos','servicios','id_user','editado_auditoria',
    'medico_auditor','nivel','medicos','servicios_trama','establecimientos','trama_prestacion','establecimientos',
    'niveles','niveles1','farmacia', 'fechaDesde','buscar_servicio','id_establecimiento','buscar_servicio_destino',
    'servicio','establecimiento','establecimiento_actual', 'fechaHasta', 'dni', 'buscar_nivel','establecimientos','nombre_beneficiario', 'estado'));
  }

  public function all_auditoria_ambulatorio($id_user = 0)
  {
    $model_medico = new \App\Models\Medico();
    $model_usuario = new User;
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
    if ($ruta == "listar_auditoria_ajax");
    $ruta = "all_auditoria_ambulatorio";
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);

    $date = Carbon::now();
    $fechaHasta = $date->format('d-m-Y');
    $fechaDesdex = $date->format('d-m-Y');
    $fechaDesdey = strtotime('-1 day', strtotime($fechaDesdex));
    $fechaDesde = date('j-m-Y', $fechaDesdey);
    //====================
    $model_farma = new Farmacia();
    $farmacia = $model_farma->GetFarmaByUser($id_user);

    $dni_beneficiario = '';
    $nombre_beneficiario = '';
    $estado = 10;
    $buscar_servicio = 0;
    $buscar_servicio_destino = 0;
    $buscar_ipress_origen_servicio=0;
    $establecimiento_actual = Auth::user()->id_establecimiento;
    $buscar_ipress_origen_servicio=0;
    $buscar_ipress_origen_medico=0;
    $editado_auditoria=0;
    $servicio = "SELECT distinct (c.id) as id,
    c.nombre
    from sub_consultorios sc
    inner join consultorios c on c.id = sc.id_consultorio
    where sc.id_establecimiento  = '$establecimiento_actual' order by nombre asc ";
    $servicio = DB::select($servicio);

    $niveles = DB::table('establecimientos')
    ->select('nivel')
    ->distinct()
    ->where('nivel', '!=', '0')
    ->orderBy('nivel')
    ->get();

    if($establecimiento_actual == '76'){
      $establecimientos = DB::table('establecimientos')
      ->select('id', 'nombre','nivel','nivel_real')
      ->distinct()
      ->where('id_estado_reg','!=','0')
      ->where('id','!=','78')
      ->where('id','!=','96')
      ->whereNotNull('codigo')
      ->orderBy('nivel_real', 'desc')
      ->orderBy('nombre', 'asc')
      ->get();
      $id_establecimiento = '0';
    }else{
      $establecimientos = DB::table('establecimientos')
      ->select('id', 'nombre','nivel','nivel_real')
      ->distinct()
      ->where('id_estado_reg','!=','0')
      ->where('id','!=','78')
      ->where('id','!=','96')
      ->where('id','=',$establecimiento_actual)
      ->whereNotNull('codigo')
      ->orderBy('nivel_real', 'desc')
      ->orderBy('nombre', 'asc')
      ->get();
      $id_establecimiento = $establecimiento_actual;
    }
   
    $servicios = DB::table('consultorios')
    ->select('id', 'nombre')
    ->distinct()
    ->where('estado','=','4')
    ->orderBy('nombre')
  ->get();

    $medicos = DB::table('medicos')
    ->select('id', 'nombre', 'paterno', 'materno')
    ->distinct()
    ->where('estado','!=','0')
    ->orderBy('nombre')
    ->get();

    $establecimiento = "SELECT e.*,e.id,e.nombre,e.nivel from establecimientos e where e.id_estado_reg='1' and ubigeo is not null and codigo is not null order by nivel  desc ";
    $establecimiento = DB::select($establecimiento);
    $id_user = Auth::user()->id;
 
    return view('frontend.auditoria.all_auditoria_ambu', compact('medicos','servicios','editado_auditoria',
    'id_user','medico_auditor','nivel','medicos','servicios_trama','trama_prestacion','establecimientos',
    'niveles','niveles1','farmacia', 'fechaDesde','buscar_servicio','id_establecimiento','buscar_servicio_destino',
    'establecimiento','establecimiento_actual', 'fechaHasta', 'dni', 'buscar_nivel','establecimientos','nombre_beneficiario', 'estado'));
  }

  public function obtenerTodosEstablecimientos()
  {
      $establecimientos = DB::table('establecimientos')
          ->select('id', 'nombre as nombre_estab')
          ->where('establecimientos.id_estado_reg', '!=', '0')
          ->whereNotNull('establecimientos.codigo')
          ->distinct()
          ->orderBy('nombre')
          ->get();
      return response()->json(['establecimientos' => $establecimientos]);
  }
  
  public function envio_masimo_tramas()
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    else:
      if(Auth::user()->actualizo_passw==0):
        return redirect('auth/password/change');
      endif;
    endif;
    $id_user = Auth::user()->id;
 
      return view('frontend.auditoria.enviar_masimavamente_tramas_1_1', [

      ]);
  }
  public function obtenerEstablecimientosPorNivel(Request $request)
  {
      $nivel = $request->nivel; 

      if ($nivel == 0) {
          $establecimientos = DB::table('establecimientos')
              ->select('id', 'nombre as nombre_estab')
              ->where('establecimientos.id_estado_reg', '!=', '0')
              //->where('establecimientos.is_ipress', '!=', '0') 
              ->distinct()
              ->orderBy('nombre')
              ->get();
      } else {
          $establecimientos = DB::table('sub_consultorios')
              ->select('establecimientos.id', 'establecimientos.nombre as nombre_estab')
              ->rightJoin('establecimientos', 'sub_consultorios.id_establecimiento', '=', 'establecimientos.id')
              ->where('establecimientos.nivel', $nivel)
              ->where('establecimientos.id_estado_reg', '!=', '0')
             // ->where('establecimientos.is_ipress', '!=', '0')
              ->distinct()
              ->orderBy('establecimientos.nombre')
              ->get();
      }
  
      return response()->json(['establecimientos' => $establecimientos]);
  }
  

  public function obtenerServiciosPorEstablecimiento(Request $request)
  {
      $establecimiento = $request->establecimiento;
      if ($establecimiento == 0) {
          $servicios = DB::table('consultorios')
              ->select('id', 'nombre as nombre_servicio')
              ->where('consultorios.estado', '=', '4')
              ->distinct() 
              ->orderBy('nombre_servicio')
              ->get();
      } else {
          $servicios = DB::table('sub_consultorios')
              ->select('consultorios.id', 'consultorios.nombre as nombre_servicio')
              ->join('consultorios', 'sub_consultorios.id_consultorio', '=', 'consultorios.id')
              ->where('sub_consultorios.id_establecimiento', $establecimiento)
              ->where('consultorios.estado', '=', '4') 
              ->distinct() 
              ->orderBy('consultorios.nombre')
              ->get();
      }
  
      return response()->json(['servicios' => $servicios]);
  }
  
  public function obtenerMedicosPorServicio(Request $request)
  {
      $servicio = $request->servicio; 
 
      if ($servicio == 0) {
   
          $medicos = DB::table('medicos')
              ->select('id', DB::raw("CONCAT(nombre, ' ', paterno, ' ', materno) AS nombre_medico"), 'nombre') 
              ->where('estado', '!=', '0')
              ->distinct()
              ->orderBy('nombre') 
              ->get();
      } else {
     
          $medicos = DB::table('medicos')
              ->select('id', DB::raw("CONCAT(nombre, ' ', paterno, ' ', materno) AS nombre_medico"), 'nombre') 
              ->where('estado', '!=', '0')
              ->where('id_consultorio', $servicio)
              ->distinct()
              ->orderBy('nombre') 
              ->get();
      }
  
      return response()->json(['medicos' => $medicos]);
  }
  
public function listar_auditoria_ajax(Request $request)
{

  $model_referencias = new Auditoria();
  $p[] = $request->id_establecimiento;
  $p[] = $request->fecha_ini;
  $p[] = $request->fecha_fin;
  $p[] = $request->dni;
  $p[] = $request->buscar_ipress_origen_nivel;
  $p[] = $request->buscar_ipress_origen_servicio;
  $p[] = $request->buscar_ipress_origen_medico;
  $p[] = strtoupper($request->nombre_beneficiario);
  $p[] = $request->estado;
  $p[] = $request->editado_auditoria;
  $p[] = $request->NumeroPagina;
  $p[] = $request->NumeroRegistros;
  $data = $model_referencias->listar_auditoria_ajax($p);
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

public function listar_auditoria_hospi_ajax(Request $request)
{

  $model_referencias = new Auditoria();
  $p[] = $request->id_establecimiento;
  $p[] = $request->fecha_ini;
  $p[] = $request->fecha_fin;
  $p[] = $request->dni;
  $p[] = $request->buscar_ipress_origen_nivel;
  $p[] = $request->buscar_ipress_origen_servicio;
  $p[] = $request->buscar_ipress_origen_medico;
  $p[] = strtoupper($request->nombre_beneficiario);
  $p[] = $request->estado;
  $p[] = $request->editado_auditoria;
  $p[] = $request->NumeroPagina;
  $p[] = $request->NumeroRegistros;
  $data = $model_referencias->listar_auditoria_hospi_ajax($p);
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

public function listar_auditoria_ambu_ajax(Request $request)
{

  $model_referencias = new Auditoria();
  $p[] = $request->id_establecimiento;
  $p[] = $request->fecha_ini;
  $p[] = $request->fecha_fin;
  $p[] = $request->dni;
  $p[] = $request->buscar_ipress_origen_nivel;
  $p[] = $request->buscar_ipress_origen_servicio;
  $p[] = $request->buscar_ipress_origen_medico;
  $p[] = strtoupper($request->nombre_beneficiario);
  $p[] = $request->estado;
  $p[] = $request->editado_auditoria;
  $p[] = $request->NumeroPagina;
  $p[] = $request->NumeroRegistros;
  $data = $model_referencias->listar_auditoria_ambu_ajax($p);
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

public function listar_auditoria_cups_ajax(Request $request)
{

  $model_referencias = new Auditoria();
  $p[] = $request->dni;
  $p[] = $request->ipress;
  $p[] = $request->tipo_atencion;
  $p[] = $request->referido;
  $p[] = $request->tipo_atencion_detalle;
  $p[] = $request->correlativo;
  $p[] = $request->anio;
  $p[] = $request->NumeroPagina;
  $p[] = $request->NumeroRegistros;
  $data = $model_referencias->listar_auditoria_cups_ajax($p);
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

public function editarAllAuditoria($numero_correlativo)
{
    $model_auditoria = new \App\Models\Auditoria;
    $wssaludpol = new \App\Ws\Saludpol;
    $prestacion = $model_auditoria->getPrestacionById_Auditoria($numero_correlativo);
    $fecha_atencion = $prestacion->fecha_atencion_fups;
    $dni_pac = $prestacion->paciente_nro_doc_identidad;
    $consulta_ws = $wssaludpol->BuscarTitularFamiliar(1,$dni_pac,$fecha_atencion);
    $estado_asegurado = $consulta_ws->estado;
    $estado_situacion = $consulta_ws->situacion;
    $estado_caducidad = $consulta_ws->caducidad;
    $año_caducidad = substr($estado_caducidad, 0, 4);
    $mes_caducidad = substr($estado_caducidad, 4, 2);
    $dia_caducidad = substr($estado_caducidad, 6, 2);
    $dni = DB::table('tipo_documento_identidad')
            ->select('tipo_documento_identidad.*')
            ->where('estado', '1')
            ->where('id', $prestacion->tipo_doc_identidad)
            ->first();
    $nombre_docu = $dni->descripcion;
    $dni_m = DB::table('tipo_documento_identidad')
    ->select('tipo_documento_identidad.*')
    ->where('estado', '1')
    ->where('id', $prestacion->id_tipodoc_ident)
    ->first();
    $nombre_docu_m = $dni_m->descripcion;
    $upss = DB::table('upsses')
          ->select('upsses.*')
          ->where('estado', '1')
          ->where('codigo', $prestacion->id_upss_fups)
          ->first();
    $nombre_upss = $upss->descripcion_upss;
    $tipo_covertura = DB::table('tipo_atenciones')
                      ->select('tipo_atenciones.*')
                      ->where('estado', '2')
                      ->where('cod_saludpol', $prestacion->id_tipo_cobert)
                      ->first();
    $nombre_cobertura = $tipo_covertura->nombre;   
    $estado = DB::table('trama_estado')
    ->select('trama_estado.*')
    ->where('id_estado', $prestacion->id_estado)
    ->first();
    $nombre_estado = $estado->descripcion;
    $detalle_prestacion = DB::table('prestaciones')
                          ->select('prestaciones.*')
                          ->where('id',$prestacion->id_prestacion)
                          ->first();
    $id_cups = DB::table('cups_pnp')
                          ->select('cups_pnp.*')
                          ->where('id', $prestacion->id_cups)
                          ->first();  
    //dd($prestacion);
   // $imagenes_via_ws = $model_auditoria->ws_datos_imagenes($numero_correlativo,$id_cups->tipo_atencion,$prestacion->fecha_ingreso_fups,$prestacion->fecha_egreso_fups,$dni_pac);                    
        /*                
    $resultado = [];
    $dato_labo = 0;
    $dato_ima = 0;
    $wsintermedia = new \App\Ws\LaboratorioLNS;
  if($id_cups->tipo_atencion == '2') {
    $detalle_emergencia = DB::table('emergencias')
                          ->select('emergencias.*')
                          ->where('id',$detalle_prestacion->id_ext)
                          ->first();
                          $egreso = $detalle_emergencia->id_diag_cab;
                          $fecha1 = $detalle_emergencia->fecha_registro_ingreso;
                          $fecha_formateada1 = date("d/m/Y", strtotime($fecha1));
                          $fecha_formateada1iam = date("Y-m-d", strtotime($fecha1));
                          $fecha2 = $detalle_emergencia->fecha_alta_medica;
                          $fecha_formateada2 = date("d/m/Y", strtotime($fecha2));    
                          $fecha_formateada1i2am = date("Y-m-d", strtotime($fecha2));  
                          $fecha_corrido1 = date("Ymd", strtotime($fecha1));                          
                          $fecha_corrido2 = date("Ymd", strtotime($fecha2)); 
                          $tablaintermedia = $wsintermedia->CallResultadosLaboratorioSinFiltro($dni_pac,$fecha_corrido1,$fecha_corrido2);
                          dd($tablaintermedia);
                            $dato_labo = 1;
                            $tabla = $tablaintermedia->ResultadosLabView;
                            $resultado = [];  
                            foreach( $tabla as $ta){              
                              if(  $ta->o_fecha_toma >= $fecha_formateada1 &&  $ta->o_fecha_toma <= $fecha_formateada2){                                
                                $soloNumero = preg_replace('/\D/', '', $ta->e_cod_alterno);                        
                                $dato_proce_lab = DB::table('procedimientos')
                                ->select('procedimientos.*')
                                ->where('codigo',$soloNumero)
                                ->first();
                                $dato_cie =  DB::table('diagnosticos')
                                ->select('diagnosticos.*')
                                ->where('id',$egreso)
                                ->first();         
                                $objeto = new stdClass();
                                $objeto->cpms = $soloNumero;
                                $objeto->t_nivel3 = $dato_proce_lab->t_nivel3;
                                $objeto->o_fecha_toma = $ta->o_fecha_toma;
                                $objeto->nombres = $ta->e_nombre;
                                $objeto->egreso  = $dato_cie->codigo;
                                $objeto->cantidad  = 1;
                                $resultado[] = $objeto;                                    
                            }                                                        
                          }
                          $tablaimagen = $wsintermedia->CallResultadosImagenes($dni_pac,20250101,20251231); 
                           
                          if(property_exists($tablaimagen, 'ResponseExamenRadCpt')){                
                            $dato_ima = 1;
                            
                            $tablaimagenes = $tablaimagen->ResponseExamenRadCpt; 
                                
                            $resultadoimagen = [];                            
                            foreach( $tablaimagenes as $tablaims){
                              
                              if (is_object($tablaims)) {
                                if(  $tablaims->fechaAtencion >= $fecha_formateada1iam &&  $tablaims->fechaAtencion <= $fecha_formateada1i2am){
                                  $dato_proce = DB::table('procedimientos')
                                  ->select('procedimientos.*')
                                  ->where('codigo',$tablaims->codCpms)
                                  ->first();
                                  $objetoima = new stdClass();
                                  $objetoima->cie10 = $tablaims->codCie;
                                  $objetoima->cpms = $tablaims->codCpms;
                                  $objetoima->cantidad = $tablaims->cantidadCpms;
                                  $objetoima->descripcion = $dato_proce->descripcion;
                                  $objetoima->t_nivel3 = $dato_proce->t_nivel3;
                                  $objetoima->cantidad  = 1;
                                  $resultadoimagen[] = $objetoima;                                                     
                                }
                            } else {                                      
                              if($tablaims != "MH" && $tablaims != "Only AuthenticationClearTextPassword and AuthenticationMD5Password supported for now. Received: 10"){                               
                                if(  $tablaimagenes->fechaAtencion >= $fecha_formateada1iam &&  $tablaimagenes->fechaAtencion <= $fecha_formateada1i2am){
                                  $dato_proce = DB::table('procedimientos')
                                  ->select('procedimientos.*')
                                  ->where('codigo',$tablaimagenes->codCpms)
                                  ->first();
                                  
                                  $objetoima = new stdClass();
                                  $objetoima->cie10 = $tablaimagenes->codCie;
                                  $objetoima->cpms = $tablaimagenes->codCpms;
                                  $objetoima->cantidad = $tablaimagenes->cantidadCpms;
                                  $objetoima->descripcion = $dato_proce->descripcion;
                                  $objetoima->t_nivel3 = $dato_proce->t_nivel3;
                                  $objetoima->cantidad  = 1;
                                  $resultadoimagen[] = $objetoima;                                                     
                                }
                              }                                                                    
                            }                            
                            }                                                        
                          }
  }else if($id_cups->tipo_atencion == '3'){
    $detalle_emergencia = DB::table('hospitalizaciones')
    ->select('hospitalizaciones.*')
    ->where('id',$detalle_prestacion->id_ext)
    ->first();
    $egreso = $detalle_emergencia->id_diag_cab;
    $fecha1 = $detalle_emergencia->fecha_ingreso;
    $fecha_formateada1 = date("d/m/Y", strtotime($fecha1));
    $fecha_formateada1iam = date("Y-m-d", strtotime($fecha1));
    if($detalle_emergencia->fecha_alta_administrativa !== null){
      $fecha2 = $detalle_emergencia->fecha_alta_administrativa;
    }else{
      $fecha2 = $prestacion->fecha_egreso_fups;
    }
    $fecha_corrido1 = date("Ymd", strtotime($fecha1));
    $fecha_corrido2 = date("Ymd", strtotime($fecha2));
    $fecha_formateada2 = date("d/m/Y", strtotime($fecha2));  
    $fecha_formateada1i2am = date("Y-m-d", strtotime($fecha2)); /* 
    $tablaintermedia = $wsintermedia->CallResultadosLaboratorioSinFiltro($dni_pac,$fecha_corrido1,$fecha_corrido2);
    //dd($tablaintermedia);
      $dato_labo = 1;
      $tabla = $tablaintermedia->ResultadosLabView;
      $resultado = [];
      foreach( $tabla as $ta){
        if(  $ta->o_fecha_toma >= $fecha_formateada1 &&  $ta->o_fecha_toma <= $fecha_formateada2){
          //dd($ta);
          $soloNumero = preg_replace('/\D/', '', $ta->e_cod_alterno);                        
          $dato_proce_lab = DB::table('procedimientos')
          ->select('procedimientos.*')
          ->where('codigo',$soloNumero)
          ->first();
          //dd($dato_proce_lab->t_nivel3);
          $dato_cie =  DB::table('diagnosticos')
          ->select('diagnosticos.*')
          ->where('id',$egreso)
          ->first();
          if(is_object($dato_proce_lab) && $dato_proce_lab->t_nivel3 != null){
            $objeto = new stdClass();
          $objeto->cpms = $soloNumero;
          $objeto->t_nivel3 = $dato_proce_lab->t_nivel3;
          $objeto->o_fecha_toma = $ta->o_fecha_toma;
          $objeto->nombres = $ta->e_nombre;
          $objeto->egreso  = $dato_cie->codigo;
          $objeto->cantidad  = 1;
          $resultado[] = $objeto; 
          }                      
      }                                                        
    }
    $tablaimagen = $wsintermedia->CallResultadosImagenes($dni_pac,$fecha_formateada1iam,$fecha_formateada1i2am); 
    if(property_exists($tablaimagen, 'ResponseExamenRadCpt')){                
      $dato_ima = 1;
      $tablaimaged = $tablaimagen->ResponseExamenRadCpt;
      $resultadoimagen = [];
      foreach( $tablaimaged as $tablaims){
        if (is_object($tablaims)) {
          if(  $tablaims->fechaAtencion >= $fecha_formateada1iam &&  $tablaims->fechaAtencion <= $fecha_formateada1i2am){
            $dato_proce = DB::table('procedimientos')
            ->select('procedimientos.*')
            ->where('codigo',$tablaims->codCpms)
            ->first();
            $objetoima = new stdClass();
            $objetoima->cie10 = $tablaims->codCie;
            $objetoima->cpms = $tablaims->codCpms;
            $objetoima->cantidad = $tablaims->cantidadCpms;
            $objetoima->descripcion = $dato_proce->descripcion;
            $objetoima->t_nivel3 = $dato_proce->t_nivel3;
            $objetoima->cantidad  = 1;
            $resultadoimagen[] = $objetoima;                               
          }
        } else {   
          if($tablaims != "MH" && $tablaims != "Only AuthenticationClearTextPassword and AuthenticationMD5Password supported for now. Received: 10"){     
                if(  $tablaimaged->fechaAtencion >= $fecha_formateada1iam &&  $tablaimaged->fechaAtencion <= $fecha_formateada1i2am){
                  $dato_proce = DB::table('procedimientos')
                  ->select('procedimientos.*')
                  ->where('codigo',$tablaimaged->codCpms)
                  ->first();
                  $objetoima = new stdClass();
                  $objetoima->cie10 = $tablaimaged->codCie;
                  $objetoima->cpms = $tablaimaged->codCpms;
                  $objetoima->cantidad = $tablaimaged->cantidadCpms;
                  $objetoima->descripcion = $dato_proce->descripcion;
                  $objetoima->t_nivel3 = $dato_proce->t_nivel3;
                  $objetoima->cantidad  = 1;
                  $resultadoimagen[] = $objetoima;                               
                }
              }
        }
      }                                                        
    }
  }else{

  }
  $datosSinDuplicadosimag = []; 
  if(!empty($resultadoimagen)){
    $dato_ima = 2;
    $datosSinDuplicadosimag = [];
foreach ($resultadoimagen as $fila) {
    if (!in_array($fila, $datosSinDuplicadosimag)) {
        $datosSinDuplicadosimag[] = $fila;
    }
}

$cpms_val = array_map(function($item) {
  return $item->cpms;
}, $datosSinDuplicadosimag);
$repeti_cpms = array_count_values($cpms_val);
  }

  $datosSinDuplicados = [];  
  $datolabfi = [];
  $resultadofinallabo = [];
  if(!empty($resultado)){
        $dato_labo = 2;
        $datosSinDuplicados = [];
          foreach ($resultado as $fila) {
              if (!in_array($fila, $datosSinDuplicados)) {
                  $datosSinDuplicados[] = $fila;
              }
          }          
      $cpms_values = array_map(function($item) {
            return $item->cpms;
        }, $datosSinDuplicados);
        $repeticiones_cpms = array_count_values($cpms_values);
        $datofinallabo = [];

      foreach($datosSinDuplicados as $labora){
        $objetolabora = new stdClass();
        $objetolabora->cpm = $labora->cpms;
        $objetolabora->t_nivel3 = $labora->t_nivel3;
        $objetolabora->nombres = $labora->nombres;
        $objetolabora->egreso = $labora->egreso;
        $datofinallabo[] = $objetolabora; 
      }
      foreach ($datofinallabo as $fila) {
        if (!in_array($fila, $datolabfi)) {
            $datolabfi[] = $fila;
        }
      }
      foreach($datolabfi as $a){
        foreach($repeticiones_cpms as $r => $cantidad){
          if($a->cpm == $r){
            $objetofinallabo = new stdClass();
            $objetofinallabo->cpm = $a->cpm;
            $objetofinallabo->t_nivel3 = $a->t_nivel3;
            $objetofinallabo->nombres = $a->nombres;
            $objetofinallabo->egreso = $a->egreso;
            $objetofinallabo->cantidad = $cantidad;
            $resultadofinallabo[] = $objetofinallabo;
         
          }
        }
      }
    }

    if($dato_labo == '2'){
      if($prestacion->lab_consulta_ws <> '1'){
        $model_prrestacion = new \App\Models\trama_prestacion;
        $model_prrestacion = \App\Models\trama_prestacion::find($numero_correlativo);
        $model_prrestacion->lab_consulta_ws= '1';
        $model_prrestacion->save();
        foreach($resultadofinallabo as $inser){
          $model_procedimiento = new \App\Models\trama_procedimientos;
          $model_procedimiento->id_cie10 = $inser->egreso;
          $model_procedimiento->precio = $inser->t_nivel3;
          $model_procedimiento->id_proced = $inser->cpm;
          $model_procedimiento->cantidad_indicada = $inser->cantidad;
          $model_procedimiento->cantidad_ejecutada = $inser->cantidad;
          $model_procedimiento->valorizacion = $inser->cantidad * $inser->t_nivel3;
          $model_procedimiento->id_trama_prestacion = $numero_correlativo;
          $model_procedimiento->lab_consulta_ws = '1';
          $model_procedimiento->save();
        }
      }      
    }
    if($dato_ima == '2'){
      if($prestacion->ima_consulta_ws <> '1'){
        $model_prrestacion = new \App\Models\trama_prestacion;
        $model_prrestacion = \App\Models\trama_prestacion::find($numero_correlativo);
        $model_prrestacion->ima_consulta_ws= '1';
        $model_prrestacion->save();
        foreach($datosSinDuplicadosimag as $inser){
          $model_procedimiento = new \App\Models\trama_procedimientos;
          $model_procedimiento->id_cie10 = $inser->cie10;
          $model_procedimiento->precio = $inser->t_nivel3;
          $model_procedimiento->id_proced = $inser->cpms;
          $model_procedimiento->cantidad_indicada = $inser->cantidad;
          $model_procedimiento->cantidad_ejecutada = $inser->cantidad;
          $model_procedimiento->valorizacion = $inser->cantidad * $inser->t_nivel3;
          $model_procedimiento->id_trama_prestacion = $numero_correlativo;
          $model_procedimiento->ima_consulta_ws = '1';
          $model_procedimiento->save();
        }
      }     
    } */
      
   $estable_nivel =  DB::table('establecimientos')
      ->select('establecimientos.*')
      ->where('id',  $detalle_prestacion->id_establecimiento )
      ->first();    
          
    $nivel = $estable_nivel->nivel;

    if (is_null($id_cups)) {
      $nombre_cups = '-';
  } else {
    $nombre_cups = $id_cups->codigo_ipress.'-'.$id_cups->año.'-'.$id_cups->intramural_extramural.'-'.$id_cups->no_referidos_referidos.'-'.$id_cups->tipo_atencion.'-'.$id_cups->id_ext;
  }
     $diagnosticos_prestacion = $model_auditoria->GetPrestacionDiagnosticoAuditoria($numero_correlativo);
    //$diagnosticos_egresos= $model_auditoria->GetPrestacionDiagnosticoEgresos($numero_correlativo);
    $procedimientos_trama = $model_auditoria->getProcedimientosByAuditoria($numero_correlativo);
    $procedimientos_tramalab = $model_auditoria->getProcedimientosByAuditoria_labo($numero_correlativo);
    $procedimientos_tramaima = $model_auditoria->getProcedimientosByAuditoria_imag($numero_correlativo);
    $productos_trama = $model_auditoria->getProductosByAuditoria($numero_correlativo);

    //PRODUCTOS DE RECETAS FISICAS SOLO EMERGENCIA Y HOSPITALIZACION
    $parametro_rf = $model_auditoria->getDatosParaLaRecetaFisica($numero_correlativo);
    $productos_receta_fisica="";
    $rf_tipo_atencion=isset($parametro_rf[0]->tipo_atencion)?$parametro_rf[0]->tipo_atencion:'';
    $rf_dni=$parametro_rf[0]->dni;
    $rf_fecha_ingreso=$parametro_rf[0]->fecha_ingreso;
    $rf_fecha_alta_medica=$parametro_rf[0]->fecha_alta_medica;
    if(!empty($parametro_rf)){
      $productos_receta_fisica = $model_auditoria->getProductosRecetaFisicaByDniFechas($numero_correlativo,$rf_tipo_atencion,$rf_dni,$rf_fecha_ingreso,$rf_fecha_alta_medica);
    }
     
    //FIN PRODUCTOS DE RECETAS FISICAS SOLO EMERGENCIA Y HOSPITALIZACION
    $diag = 0;
    $proc = 0;
    $pres = 0;
    $prod = 0;
    $diag_egres = 0;
    $diag_aten = 0;
    $cod_prod = [];
    $cod_diag = [];
    $cod_proc = [];
    $dato_precio = 0;
    $dato_precio_procedimiento = 0;
    $dato_precio_productos = 0;
    foreach($productos_trama as $datos){

      $dato_precio_productos = $datos->valorizacion + $dato_precio_productos;
      $dato_precio = $datos->valorizacion + $dato_precio;
    }
    foreach($procedimientos_trama as $datos){
      $dato_precio_procedimiento = $datos->valorizacion_procedimiento + $dato_precio_procedimiento;
      $dato_precio = $datos->valorizacion_procedimiento + $dato_precio;
    }
    /******************EMERGENCIA Y HOSPITALIZACION****************/
    $atencion_id = '';
    $atencion_estado = '';
    $atencion_tipo ='';
    if($detalle_prestacion->id_tipo_atencion== 2 || $detalle_prestacion->id_tipo_atencion==3){
      $table = null;
      switch ($detalle_prestacion->id_tipo_atencion) {
          case 2:
              $table = 'emergencias';
              break;
          case 3:
              $table = 'hospitalizaciones';
              break;
      }
      $datos_atencion = DB::table($table)->where('id', $detalle_prestacion->id_ext)->first();
      $atencion_id = $datos_atencion->id;
      $atencion_estado = $datos_atencion->estado;
      $atencion_tipo = $detalle_prestacion->id_tipo_atencion;
    }
    /******************FIN EMERGENCIA Y HOSPITALIZACION****************/

    if($detalle_prestacion->id_tipo_atencion == '1'){
      return view('frontend.auditoria.editar_all_auditoria_ambulatorio',
      [
         'prestacion' => $prestacion,
         'diagnosticos_prestacion' => $diagnosticos_prestacion,
         'detalle_prestacion' => $detalle_prestacion,
         'nombre_upss'=>$nombre_upss,
         'nombre_docu'=>$nombre_docu,
         'nombre_docu_m'=>$nombre_docu_m,
         'nombre_cobertura'=>$nombre_cobertura,
         'nombre_cups'=>$nombre_cups,
         'nombre_estado'=>$nombre_estado,
         'productos_trama'=>$productos_trama,
         'procedimientos_trama' => $procedimientos_trama,
         'diag' => $diag,
         'proc' => $proc,
         'pres' => $pres,
         'prod' => $prod,
         'cod_prod' => $cod_prod,
         'cod_diag' => $cod_diag,
         'diag_aten' => $diag_aten,
         'diag_egres' => $diag_egres,
         'dato_precio' => $dato_precio,
         'estado_asegurado' => $estado_asegurado,
         'estado_situacion' => $estado_situacion,
         'año_caducidad' => $año_caducidad,
         'mes_caducidad' => $mes_caducidad,
         'dato_precio_productos' => $dato_precio_productos,
         'dato_precio_procedimiento' => $dato_precio_procedimiento,
         'nivel' => $nivel,
         'dia_caducidad' => $dia_caducidad
     ]);
    }else{
      return view('frontend.auditoria.editar_all_auditoria',
     [

        'prestacion' => $prestacion,
        'diagnosticos_prestacion' => $diagnosticos_prestacion,
        'nombre_upss'=>$nombre_upss,
        'nombre_docu'=>$nombre_docu,
        'nombre_docu_m'=>$nombre_docu_m,
        'nombre_cobertura'=>$nombre_cobertura,
        'nombre_cups'=>$nombre_cups,
        'detalle_prestacion' => $detalle_prestacion,
        'nombre_estado'=>$nombre_estado,
        'productos_trama'=>$productos_trama,
        'procedimientos_trama' => $procedimientos_trama,
        'diag' => $diag,
        'proc' => $proc,
        'pres' => $pres,
        'prod' => $prod,
        'cod_prod' => $cod_prod,
        'cod_diag' => $cod_diag,
        'diag_aten' => $diag_aten,
        'diag_egres' => $diag_egres,
        'dato_precio' => $dato_precio,
        'estado_asegurado' => $estado_asegurado,
        'estado_situacion' => $estado_situacion,
        'año_caducidad' => $año_caducidad,
        'mes_caducidad' => $mes_caducidad,
        'dato_precio_productos' => $dato_precio_productos,
        'dato_precio_procedimiento' => $dato_precio_procedimiento,
        'nivel' => $nivel,
        'dia_caducidad' => $dia_caducidad,
        'atencion_id'=>$atencion_id,
        'atencion_estado'=>$atencion_estado,
        'id_tipo'=>$atencion_tipo,
        'productos_receta_fisica'=>$productos_receta_fisica,
        'rf_tipo_atencion'=>$rf_tipo_atencion,
        'rf_dni'=>$rf_dni,
        'rf_fecha_ingreso'=>$rf_fecha_ingreso,
        'rf_fecha_alta_medica'=>$rf_fecha_alta_medica
    ]);
    }
    
}

public function editarAuditoria($id_prestacion)
  {
      if (!Auth::check()) :
          return redirect('/auth/login');
      endif;
      $model_medicos = new \App\Models\Medico;
      $medico = $model_medicos->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni,Auth::user()->id_establecimiento);
      if (empty($medico)) {
        Session::flash('flash_message', 'DEBE SER MEDICO PARA ENTRAR A ESTA OPCION');
        return redirect('/');
      }
      $medico_auditor= array(
        'id_auditor'=>$medico->id,
        'tipo_documento'=>!empty($medico->tipo_documento)?$medico->tipo_documento:'',
        'dni'=>!empty($medico->dni)?$medico->dni:'',
        'nombre'=>!empty($medico->nombre)?$medico->nombre:'',
        'paterno'=>!empty($medico->paterno)?$medico->paterno:'',
        'materno'=>!empty($medico->materno)?$medico->materno:'',
        'colegiatura'=>!empty($medico->colegiatura)?$medico->colegiatura:'',
        'rne'=>!empty($medico->rne)?$medico->rne:'',
        'id_profesion'=>!empty($medico->id_profesion)?$medico->id_profesion:'',
        'id_especializacion'=>!empty($medico->id_especializacion)?$medico->id_especializacion:''
      );
     
      $auditoriaModel = new \App\Models\Auditoria;
      $data = $auditoriaModel->getDatosPantallaAuditoria($id_prestacion);

      $datos_cups = DB::table('cups_pnp')
      ->select('cups_pnp.*')
      ->where('id', $id_prestacion)
      ->first();
      $tipo_atencion = $datos_cups->tipo_atencion;
      $datos_extra = null;

      if($datos_cups->tipo_atencion == 1){
        $datos_extra = null;
      }else if($datos_cups->tipo_atencion == 2){
        $datos_extra = DB::table('emergencias')
        ->select('emergencias.*')
        ->where('id', $datos_cups->id_ext)
        ->first();
      }else if($datos_cups->tipo_atencion == 3){
        $datos_extra = null;
      }else{
        $datos_extra = null;
      }

      $model_departamento = new \App\Models\Departamento();
      $departamento = $model_departamento->ShowAll();

      $provincia = null;
      if ($data['id_departamento'] != null):
        $model_provincia = new \App\Models\Provincia();
        $provincia = $model_provincia->ShowAllByIdDepartamento($data['id_departamento']);
      endif;

      $distrito = null;
      if ($data['id_provincia'] != null):
        $model_distrito = new \App\Models\Distrito();
        $distrito = $model_distrito->ShowDistrito($data['id_provincia']);
      endif;

      $model_tipo_parentescos =  new \App\Models\Tipo_parentescos;
      $tipo_parentesco = $model_tipo_parentescos->getTipo_parentescos();

      $model_kardex = new \App\Models\Kardexe();
      $fechaServidor = $model_kardex->getFechaServidor();

      $id_user = Auth::user()->id;
      $model_farma = new \App\Models\Farmacia;
      $farma = $model_farma->GetFarmaByUser($id_user);
      $id_farmacia = $farma->id;
      $id_ipress = $farma->id_establecimiento;

      $farmacias = $model_farma->GetFarmaciaById($id_farmacia);

      $model_establecimiento = new \App\Models\Establecimiento;
      $establecimiento = $model_establecimiento->GetById($id_ipress);
      if ($id_ipress == 1) {
        $nivel = 2;
      } else {
        $nivel = $establecimiento[0]->nivel;
      }
      
      $model = new \App\Models\User;
      $establecimientotot = $model->GetById($id_user);
      $existe = $establecimientotot->flagestablecimientos;

      $model = new \App\Models\Consultorio;
      if ($existe == "1") {
        $consultorios = $model->ShowConsultoriosAll();
      } else {
        $consultorios = $model->ShowConsultorios($id_farmacia);
      }
      $model_profesion = new \App\Models\Profesione;
      $profesiones = $model_profesion->ShowAll();

      $modelespecializaciones =  new \App\Models\Especializacione();
      $especializaciones = $modelespecializaciones->ShowAll();

      $auditoria =  new \App\Models\Auditoria();
      $tipo_seguros = $auditoria->getTipoBeneficiarioTitularDHambiente();

      $model_producto = new \App\Models\Auditoria;
      $producto = $model_producto->getProductosByNivelAndIdServicio($nivel, $id_ipress, $id_farmacia);
      return view('frontend.auditoria.editar_auditoria', compact('producto','farmacias','fechaServidor','datos_extra','tipo_atencion','nivel','tipo_parentesco','departamento','provincia','distrito','medico_auditor','id_prestacion','profesiones','especializaciones','consultorios','tipo_seguros','data'));
      
  }

  public function actualizarAuditoria(Request $request, $id){
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $resultado=false;
    DB::beginTransaction();
    try {

     ////////////////// VALIDADOR ESTRUCTURAL Y OTRAS REGLAS DE NEGOCIO///////////////////////////
     $tipo_documento_regex1="";
     $tipo_documento_regex2="";
     $tipo_documento_msg1="";
     $tipo_documento_msg2="";
      if($request['tipo_documento']==1){
        $tipo_documento_regex1='regex: /^\d{8}$/';
        $tipo_documento_msg1="El Documento de Identidad solo soporta 8 digitos";
      }else if($request['tipo_documento']==2){
        $tipo_documento_regex1='regex: /^\d{12}$/';
        $tipo_documento_msg1="El Documento de Identidad solo soporta 12 digitos";
      }
      $tipo_documento_regex2="";
      if($request['med_tipo_documento']==1){
        $tipo_documento_regex2='regex: /^\d{8}$/';
        $tipo_documento_msg2="El Documento de Identidad solo soporta 8 digitos";
      }else if($request['med_tipo_documento']==2){
        $tipo_documento_regex2='regex: /^\d{12}$/';
        $tipo_documento_msg2="El Documento de Identidad solo soporta 12 digitos";
      }

      $rules = [
        'asunto_audicion'=>['required','regex:/^[^\s][a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/','max:255',],
        'observaciones_audicion'=>['regex:/^[^\s][a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/','max:255',],
        'n_historia_clinica'=>['required','numeric'],
        'edad'=>['required','numeric'],
        'domicilio_actual'=>['max:50',],
        'n_documento_identidad' =>['required',$tipo_documento_regex1,'numeric',],//regex acepta solo 8 ó 12 digitos dependiendo el tipo de documento
        'apellido_paterno' => ['required','regex:/^[^\s][a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/','max:200',],
        'apellido_materno' => ['required','regex:/^[^\s][a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/','max:200',],
        'nombres' => ['required','regex:/^[^\s][a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/','max:255',],
        'acompanante'=>['required'],
        'numero_acompanante'=>['numeric'],
        'med_n_documento_identidad' =>['required',$tipo_documento_regex2,'numeric',],//regex acepta solo 8 ó 12 digitos dependiendo el tipo de documento
        'med_ape_paterno' => ['required','regex:/^[^\s][a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/','max:200',],
        'med_ape_materno' => ['required','regex:/^[^\s][a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/','max:200',],
        'med_nombres' => ['required','regex:/^[^\s][a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/','max:255',],
        'med_num_colegiatura'=>['required','numeric'],
        'med_numero_rne'=>['required','numeric'],
        'id_departamento'=>['required'],
        'id_provincia'=>['required'],
        'id_distrito'=>['required'],
        'desarrollo_cronologico_enf' => ['max:50',],
        'motivo_visita' => ['max:255',],
       
        'temperatura' => ['numeric'],
        'frecuencia_respiratoria' => ['numeric'],
        'frecuencia_cardiaca' => ['numeric'],
        'presion_arterial_1' => ['numeric'],
        'presion_arterial_2' => ['numeric'],
        'saturacion_oxigeno' => ['numeric'],
        'peso' => ['numeric'],
        'talla' => ['numeric'],
        'tamano_cintura' => ['numeric'],
        'fi02' => ['numeric'],
        'examenes_auxiliares' => ['max:50'],
        'tipo_egreso' => ['required'],
        'condicion_egreso' => ['required'],
        'fecha_egreso'=>['required','date'],
        'hora_egreso'=>['required','date_format:H:i:s'],
        'fecha_nacimiento'=>['required','date'],
        // 'fecha_atencion'=>['required','date'],
        // 'hora_atencion'=>['required','date_format:H:i'],
        // 'nombre_diagnostico.*' => '',
        // 'id_diagnostico_ingreso_nuevos.*'=>'',
        // 'id_diagnostico_ingreso_nuevos_tipo_diagnostico.*' => '',
        // 'id_clasificacion.*' => '',
        'id_examen_patologia_anulado.*'=>'string',
        'id_examen_imagenologia_anulado.*'=>'string',

        'nombre_diagnostico2.*' => '',
        'id_diagnostico_externo_nuevos.*'=>'',
        'id_diagnostico_externo_nuevos_tipo_diagnostico.*' => '',
        'id_clasificacion2.*' => '',
        'ingreso_egreso.*' => '',
        'id_diagnosticos_externo_anulado.*'=>'string',
        'id_procedimiento_externo_anulado.*'=>'string',
        'id_producto_anulado.*'=>'string',
        'id_diagnostico_alta_anulado.*'=>'string',

        'nombre_diagnostico_alta.*' => '',
        'tipo_diagnostico_alta.*' => '',
        'id_clasificacion_alta.*' => '',

        'nombre_procedimiento.*' => '',
        'procedimiento_cantidad.*' => '',
        'miSelect.*' => '',

        'id_producto.*' => '',
        'tiempo_periodo.*' => '',
        'id_via_administracion.*' => '',
        'posologia.*' => '',
        'nro_med_solictados.*' => '',
        'nro_med_dispensados.*' => '',
        'miSelectProd.*' => '',
        'id_cie10_producto.*'=>'',
        'codigo_producto.*'=>''        
      ];

      // Personalizar mensajes de error
      $messages = [
        'asunto_audicion.required' => 'El campo "Asunto Audicion" es obligatorio.',
        'asunto_audicion.regex' => 'El campo "Asunto Audición" no puede comenzar con un espacio y solo puede contener letras y espacios.',
        'asunto_audicion.max' => 'El campo "Asunto Audicion" logitud maximo 255.',

        'observaciones_audicion.regex' => 'El campo "Observaciones de la Audicion" no puede comenzar con un espacio y solo puede contener letras y espacios.',
        'observaciones_audicion.max' => 'El campo "Observaciones de la Audicion" logitud maximo 255.',
        
        'n_historia_clinica.required' => 'El campo "N° Historia Clinica" es obligatorio.',
        'n_historia_clinica.numeric' => 'El "N° Historia Clinica" debe contener solo números.',
        'edad.required' => 'El campo "Edad" es obligatorio.',
        'edad.numeric' => 'El campo "Edad" debe contener solo números.',
        
        'domicilio_actual.max' => 'El campo "Domicilio Actual" logitud maximo 50.',

        'n_documento_identidad.required' => 'El campo "Documento de Identidad" es obligatorio.',
        'n_documento_identidad.numeric' => 'El "Documento de Identidad" debe contener solo números.',
        'n_documento_identidad.regex' => $tipo_documento_msg1,

        'apellido_paterno.required' => 'El campo "Apellido Paterno" es obligatorio.',
        'apellido_paterno.regex' => 'El campo apellido paterno no puede comenzar con un espacio y solo puede contener letras y espacios.',
        'apellido_materno.required' => 'El campo "Apellido Materno" es obligatorio.',
        'apellido_materno.regex' => 'El campo apellido materno no puede comenzar con un espacio y solo puede contener letras y espacios.',
        'nombres.required' => 'El campo "Nombres" es obligatorio.',
        'nombres.regex' => 'El campo "Apellidos" no puede comenzar con un espacio y solo puede contener letras y espacios.',
        'doc_num_rne.required' => 'El campo "N° RNE" es obligatorio.',
        'doc_num_rne.numeric' => 'El campo "N° RNE" debe contener solo números.',
        'acompanante.required' => 'El campo "Acompañante" es obligatorio.',
       
        'numero_acompanante.numeric' => 'El "Telefono de acompañante" debe contener solo números.',

        'med_n_documento_identidad.required' => 'El campo "Documento de Identidad" es obligatorio.',
        'med_n_documento_identidad.numeric' => 'El "Documento de Identidad" debe contener solo números.',
        'med_n_documento_identidad.regex' => $tipo_documento_msg2,

        'med_ape_paterno.required' => 'El campo "Apellido Paterno" es obligatorio.',
        'med_ape_paterno.regex' => 'El campo apellido paterno no puede comenzar con un espacio y solo puede contener letras y espacios.',
        'med_ape_materno.required' => 'El campo "Apellido Materno" es obligatorio.',
        'med_ape_materno.regex' => 'El campo apellido materno no puede comenzar con un espacio y solo puede contener letras y espacios.',
        'med_nombres.required' => 'El campo "Nombres" es obligatorio.',

        'med_num_colegiatura.required' => 'El campo "N° Colegiatura" es obligatorio.',
        'med_num_colegiatura.numeric' => 'El "N° Colegiatura" debe contener solo números.',
        'med_numero_rne.required' => 'El campo "N° RNE" es obligatorio.',
        'med_numero_rne.numeric' => 'El "N° RNE" debe contener solo números.',
      

       
        'desarrollo_cronologico_enf.max' => 'El campo "Desarrollo Cronologico de Enfermedad" logitud maximo 50',

        'motivo_visita.max' => 'El campo "Motivo de Visita" logitud maximo 255',

        'temperatura.max' => 'El campo "Temperatura (T)" logitud maximo 5',
       // 'temperatura.between' => 'La temperatura debe estar entre 35 y 40 grados.',
      
        //'frecuencia_respiratoria.between' => 'La frecuencia respiratoria debe estar entre 10 y 35 (/min ).',

        //'frecuencia_cardiaca.between' => 'La frecuencia cardiaca debe estar entre 50 y 150 (/min ).',

    
        //'presion_arterial_1.between' => 'La Presión arterial debe estar entre 0 y 250 (/min ).',

  
        //'presion_arterial_2.between' => 'La Presión arterial debe estar entre 0 y 140 (/min ).',

   
        //'saturacion_oxigeno.between' => 'La Saturacion de Oxigeno debe estar entre 60 y 100 ( % ).',
      
       // 'peso.between' => 'El peso debe estar entre 1 y 180 ( Kg ).',

        'talla.numeric' => 'El campo "TALLA (cm)" debe contener solo números.',
        //'talla.between' => 'La Talla debe estar entre 30 y 200 ( cm ).',

     
        'tamano_cintura.numeric' => 'El campo "Tamaño de la cintura (cm)" debe contener solo números.',

        
       
        'fi02.numeric' => 'El campo "Fi02 (%)" debe contener solo números.',
        //'fi02.between' => 'El campo "Fi02 (%)" debe estar entre 21 y 100',

        'examenes_auxiliares.max'=> 'El campo "Exámenes Auxiliares" logitud maximo 50',

        'tipo_egreso.required'=> 'El campo "Tipo de Egreso" es obligatorio.',


        'condicion_egreso.required'=> 'El campo "Condicion de Egreso" es obligatorio.',
      

        'fecha_egreso.required'=> 'El campo "Fecha de Egreso" es obligatorio.',
        'fecha_egreso.date' => 'La fecha de egreso debe ser una fecha válida.',

        'hora_egreso.required'=> 'El campo "Hora de Egreso" es obligatorio.',
        'hora_egreso.date_format' => 'La hora de egreso debe estar en formato HH:mm.',

        'fecha_nacimiento.required'=> 'El campo "Fecha de Nacimiento" es obligatorio.',
        'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida.',

        // 'fecha_atencion.required'=> 'El campo "Fecha de atención" es obligatorio.',
        // 'fecha_atencion.date' => 'La fecha de atención debe ser una fecha válida.',

        // 'hora_atencion.required'=> 'El campo "Hora de atención" es obligatorio.',
        // 'hora_atencion.date_format' => 'La hora de atención debe estar en formato HH:mm.',
        'id_departamento.required' => 'El campo "Departamento" es obligatorio.',
        'id_provincia.required' => 'El campo "Provincia" es obligatorio.',
        'id_distrito.required' => 'El campo "Distrito" es obligatorio.',
      ];
      // Validar con reglas y mensajes personalizados
      
      $validator = Validator::make($request->all(), $rules, $messages);
      if ($validator->fails()) {
        session()->flash('error_warning', "Error de validacion");
        return redirect()
            ->route('editar_auditoria', ['id' => $id]) 
            ->withErrors($validator) 
            ->withInput(); 
      ////////////////// FIN VALIDADOR ESTRUCTURAL Y OTRAS REGLAS DE NEGOCIO//////////////
            
      }else{
       ////////////////// GUARDAR DATOS///////////////////////////
        $auditoria = new \App\Models\Auditoria;
        $resultado = $auditoria->guardarDatosAuditor($request->all());

        $model_historial = new \App\Models\trama_historial;
        $model_historial->usuario_modificacion = Auth::user()->id;
        $model_historial->fecha_usuario_modificacion = Carbon::now();
        $model_historial->descripcion = 'Modificado por el Medico Auditor';
        $model_historial->id_trama_actualizada = $request['id_trama_prestacion'];
        $model_historial->save();
        DB::commit();
      ////////////////// FIN GUARDAR DATOS///////////////////////////
      }
     

      // $auditoria = new \App\Models\Auditoria;
      // $model_trama = \App\Models\trama_prestacion::find($request['id_trama_prestacion']);
      // $model_trama->id_estado = 9;

      // $model_historial = new \App\Models\trama_historial;
      // $model_historial->usuario_modificacion = Auth::user()->id;
      // $model_historial->fecha_usuario_modificacion = Carbon::now();
      // $model_historial->descripcion = 'Modificado por el Medico Auditor';
      // $model_historial->id_trama_actualizada = $model_trama->id;
      // $model_historial->save();

      //$model_trama->save();

    } catch (\Exception $e) {
      DB::rollback();
      Log::error('Error al querer guadar datos de auditoria', [
        'message' => $e->getMessage(),
        'code' => $e->getCode(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString(),
    ]);
      $errorCode = $e->getCode();
      switch ($errorCode) {
        case '22001':
          $errorMessage = 'El valor ingresado es demasiado largo para uno de los campos.';
          break;
      case '23000':
          $errorMessage = 'Violación de restricción de integridad.';
          break;
      case '23502':
          $errorMessage = 'Un campo requerido no puede ser nulo.';
          break;
      case '23503':
          $errorMessage = 'Violación de clave foránea.';
          break;
      case '23505':
          $errorMessage = 'Violación de la restricción de unicidad. El valor ya existe.';
          break;
      case '42703':
          $errorMessage = 'Columna indefinida.';
          break;
      case '42804':
          $errorMessage = 'Incompatibilidad de tipos de datos.';
          break;
      case '42P01':
          $errorMessage = 'Alguna Tabla de la base de dtaos no esta definida.';
          break;
      case '42P02':
          $errorMessage = 'Algun Parámetro indefinido.';
          break;
      case '57014':
          $errorMessage = 'Consulta cancelada.';
          break;
      case '53200':
          $errorMessage = 'Memoria insuficiente.';
          break;
      case '53100':
          $errorMessage = 'Disco lleno.';
          break;
      default:
          $errorMessage = 'Ocurrió un error al intentar guardar los datos. Por favor, comuniquese con el administrador del sistema.';
      }
      session()->flash('error_danger', $errorMessage);
      return redirect()->back()->withErrors(['error' => $errorMessage]);
    } catch (\Throwable $th) {
      DB::rollback();
      throw $th;
    }
    if($resultado){
      return redirect('/all_auditoria/');
    }
  }

  public function estadosTramaPrestacionGrafico(Request $request){
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $data1 =array(
    'total'=>0,
    'total_enviados'=>0,
    'total_no_enviados'=>0
    );
    $data2 =array(
    'total_enviados'=>0,
    'total_observados'=>0,
    'total_modificados'=>0,
    'total_reenviados'=>0
    );
    $fecha_inicio = date('d-m-Y', strtotime('-30 days'));
   
    $fecha_fin_actual = date('d-m-Y');

    if ($request->isMethod('post')) {
      $consulta = $request->consulta;
      $fecha_inicio = $request->fecha_inicio;
      $fecha_fin = $request->fecha_fin;
  
      // Verificar si la consulta es 1 y si las fechas no están vacías
      if ($consulta == 1 && !empty($fecha_inicio) && !empty($fecha_fin)) {
          // Convertir las fechas al formato adecuado
          $fecha_inicio = date('Y-m-d', strtotime($fecha_inicio));
          $fecha_fin = date('Y-m-d', strtotime($fecha_fin));
      } else {
          $fecha_inicio = null;
          $fecha_fin = null;
      }


      $model_auditoria = new \App\Models\Auditoria;
      $data_grafico = $model_auditoria->consulta_graficos($fecha_inicio,$fecha_fin);
      /*
      $respuesta1Query = DB::table('trama_prestacion as tp')
          ->join('trama_estado as te', 'tp.id_estado', '=', 'te.id_estado');
  
  
      if (!empty($fecha_inicio) && !empty($fecha_fin)) {
          $respuesta1Query->whereBetween('tp.fech_alta_atenc', [$fecha_inicio, $fecha_fin]);
      }
  
      $respuesta1 = $respuesta1Query->select([
          DB::raw('COALESCE(SUM(te.id_estado), 0) AS total'),
          DB::raw('COALESCE(SUM(CASE WHEN te.id_estado = 1 THEN 1 ELSE 0 END), 0) AS total_enviados'),
          DB::raw('COALESCE(SUM(CASE WHEN te.id_estado = 0 THEN 1 ELSE 0 END), 0) AS total_no_enviados')
      ])->first();
  
     
      $respuesta2Query = DB::table('trama_observaciones as tob')
          ->join('trama_prestacion as tp', 'tob.id_trama_prestacion', '=', 'tp.id')
          ->join('trama_estado as te', 'tp.id_estado', '=', 'te.id_estado')
          ->where('te.id_estado', 1);
  
    
      if (!empty($fecha_inicio) && !empty($fecha_fin)) {
          $respuesta2Query->whereBetween('tp.fech_alta_atenc', [$fecha_inicio, $fecha_fin]);
      }
  
      $respuesta2 = $respuesta2Query->select([
          DB::raw('COALESCE(COUNT(*), 0) AS total_enviados'),
          DB::raw('COALESCE(SUM(CASE WHEN tob.id_estado = 2 THEN 1 ELSE 0 END), 0) AS total_observados'),
          DB::raw('COALESCE(SUM(CASE WHEN tob.id_estado = 3 THEN 1 ELSE 0 END), 0) AS total_modificados'),
          DB::raw('COALESCE(SUM(CASE WHEN tob.id_estado = 4 THEN 1 ELSE 0 END), 0) AS total_reenviados')
      ])->first();
   */
      $data1 =array(
          'total'=>$data_grafico->total_enviados,
          'total_enviados'=>$data_grafico->total_estado_1,
          'total_no_enviados'=>$data_grafico->total_estado_0
      );
      $data2 =array(
        'total_enviados'=>$data_grafico->total_estado_1,
        'total_observados'=>$data_grafico->total_estado_2,
        'total_modificados'=>$data_grafico->total_estado_3,
        'total_reenviados'=>$data_grafico->total_estado_4
    );
    $fecha_inicio = date('d-m-Y',strtotime($fecha_inicio));
    $fecha_fin_actual = date('d-m-Y',strtotime($fecha_fin));
      return view('frontend.auditoria.estados_tramaprestacion_grafico', compact('data1', 'data2','fecha_inicio','fecha_fin_actual'));
    }
    return view('frontend.auditoria.estados_tramaprestacion_grafico', compact('data1', 'data2','fecha_inicio','fecha_fin_actual'));

  }

  public function validar_productos_auditoria(Request $request){

    $model_auditoria = new \App\Models\trama_prestacion();
    $model_trama_producto = new \App\Models\trama_producto();

    $model_auditoria = trama_prestacion::find($request->id_estado);
    $recetas = "select * from receta_vales where id_prestacion =" .$model_auditoria->id_prestacion;
    $recetas = DB::select($recetas);
    dd($recetas);
    if(is_array($recetas) && !empty($recetas)){
      foreach($recetas as $receta){
          $producto = "select * from producto_recetas where id_receta_vale =" .$receta->id;
            dd($producto);

      }
    }

    dd($model_auditoria);
    $trama_productos = "select * from trama_productos where id_trama_prestacion =" .$request->id_estado;
        $trama_productos = DB::select($trama_productos);
        if(is_array($trama_productos) && !empty($trama_productos)){
            foreach($trama_productos as $productos){
                $dispensado =  DB::table('producto_recetas')
                ->select('producto_recetas.*')
                ->where('id', $productos->id_producto_recetas)
                ->first();
                  
                $model_trama_producto = trama_producto::find($productos->id);
                $model_trama_producto->cantidad_entregada = $dispensado->cantidad_dispensada;
                $model_trama_producto->valorizacion = $model_trama_producto->precio * $dispensado->cantidad_dispensada;
                $model_trama_producto->save();
                $model_auditoria->actualizacon_trama = 1;
            }
        }else{
          $model_auditoria->actualizacon_trama = 1;
        }
        $model_auditoria->save();
    return json_encode(true);
  }

  public function validar_ws_SaludPol(Request $request){

    //dd($request->id_estado);
    $model_auditoria = new \App\Models\trama_prestacion();
    $trama_paquete_masivo = new \App\Models\trama_paquete_masivo();
    $trama_paquete_masivo->estado = 1;
    $trama_paquete_masivo->created_us = Auth::user()->id;
    $trama_paquete_masivo->dato_correlativo = 0;
    $trama_paquete_masivo->save();
    $dato_corelativo = $trama_paquete_masivo->id;
    $model_auditoria = trama_prestacion::find($request->id_estado);
    //dd($model_auditoria);
    $model_auditoria->id_estado = 1;
    
    $estable_actual = Auth::user()->id_establecimiento;
    $establecimiento_actual =  DB::table('establecimientos')
    ->select('establecimientos.*')
    ->where('id', $estable_actual)
    ->first();
    $date = Carbon::now();
    $dias = $date->day;
    $mes = $date->month;
    $año = $date->format('y');
    $codigo_paquete = $establecimiento_actual->codigo.$año.$mes.$dias.$dato_corelativo;
    $model_interoperabilidad = new \App\Models\Trama_interoperabilidad();
    //dd($dni_user);
   
    $token_saludpol = $model_interoperabilidad->token_saludpol($codigo_paquete);
    //dd($token_saludpol);
    $token_saludpol = trim($token_saludpol, '"');
    $data = json_decode($token_saludpol, true);
    $token = isset($data['token']) ? $data['token'] : 0;
    $dni_user =$token;
    //$dni_user = 'eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiI5ODU0NTg5NSIsImlhdCI6MTc3MDczNzUxNiwiZXhwIjoxNzcwODIzOTE2fQ.KrAHgqNvoWohyCP2InUFcSBMssSMhAcESmaMac-KE30';

    $enviar_masivo_1 = $model_interoperabilidad->Envio_trama_masivo_1($model_auditoria->id,$codigo_paquete,$dni_user);
    dd($enviar_masivo_1);
    if ($enviar_masivo_1 === false) {
      $model_auditoria->id_estado = 3;
  }else{
    $model_auditoria->id_estado = 1;
  }
  $model_auditoria->comentario_saludpol = $codigo_paquete;
      $model_auditoria->save();

    return json_encode(true);
  }

  public function validar_ws_SaludPol_respuesta(Request $request){

    $model_auditoria = new \App\Models\trama_prestacion();
    $wsdirehum = new \App\Ws\Dirrehum;
    $ws = $wsdirehum->BuscarPrestacionWsMininterrespuesta($request);

    $model_auditoria->id_saludpol = $ws->idprestacion;
   
    $model_auditoria = trama_prestacion::find($request->id_estado);

    $model_auditoria->save();

    return json_encode(true);
  }

  public function envio_masivo_10(Request $request){
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_array = [];
    $id_select = [];
    $id_enviado = [];
    $id_array[0] = $request->id_trama1;
    $id_array[1] = $request->id_trama2;
    $id_array[2] = $request->id_trama3;
    $id_array[3] = $request->id_trama4;
    $id_array[4] = $request->id_trama5;
    $id_array[5] = $request->id_trama6;
    $id_array[6] = $request->id_trama7;
    $id_array[7] = $request->id_trama8;
    $id_array[8] = $request->id_trama9;
    $id_array[9] = $request->id_trama10;

    $id_enviado[0] = 0;
    $id_enviado[1] = 0;
    $id_enviado[2] = 0;
    $id_enviado[3] = 0;
    $id_enviado[4] = 0;
    $id_enviado[5] = 0;
    $id_enviado[6] = 0;
    $id_enviado[7] = 0;
    $id_enviado[8] = 0;
    $id_enviado[9] = 0;

    $model_auditoria = new \App\Models\trama_prestacion();
      $wsdirehum = new \App\Ws\Dirrehum;

      if(isset($request->check1)){
        $id_select[0] = 1;
        $ws1 = $wsdirehum->BuscarPrestacionWsMininter($request->id_trama1);
        $ws1 = $ws1->prestacion;
        if(isset($ws1)){
          $ws1 = $ws1[0];
          if($ws1->msg == 'Datos recibidos correctamente'){
            $id_enviado[0] = 1;
              $model_auditoria = trama_prestacion::find($request->id_trama1);
              $model_auditoria->id_estado = 1;
              $model_auditoria->id_saludpol = $ws1->idprestacion;

              $estados_trama = new \App\Models\Actualiza_estados_trama;
              $estados_trama->id_trama_prestacion = $request->id_trama1;
              $estados_trama->id_cups = $model_auditoria->id_cups;
              $estados_trama->fecha_auditoria = $model_auditoria->fech_alta_atenc;
              $estados_trama->estado_previo = 0;
              $estados_trama->id_estado_reg = 1;
              $estados_trama->estado_actualizado = 1;
              $estados_trama->id_user_responsable = Auth::user()->id;
              $estados_trama->save();
              $model_auditoria->save();
          }
        }
      }else{
        $id_select[0] = 0;
      }
      if(isset($request->check2)){
        $id_select[1] = 1;
        $ws2 = $wsdirehum->BuscarPrestacionWsMininter($request->id_trama2);
        $ws2 = $ws2->prestacion;
        if(isset($ws2)){
          $ws2 = $ws2[0];
          if($ws2->msg == 'Datos recibidos correctamente'){
            $id_enviado[1] = 1;
              $model_auditoria = trama_prestacion::find($request->id_trama2);
              $model_auditoria->id_estado = 1;
              $model_auditoria->id_saludpol = $ws2->idprestacion;

              $estados_trama = new \App\Models\Actualiza_estados_trama;
              $estados_trama->id_trama_prestacion = $request->id_trama2;
              $estados_trama->id_cups = $model_auditoria->id_cups;
              $estados_trama->fecha_auditoria = $model_auditoria->fech_alta_atenc;
              $estados_trama->estado_previo = 0;
              $estados_trama->id_estado_reg = 1;
              $estados_trama->estado_actualizado = 1;
              $estados_trama->id_user_responsable = Auth::user()->id;
              $estados_trama->save();
              $model_auditoria->save();
          }
        }
      }else{
        $id_select[1] = 0;
      }
      if(isset($request->check3)){
        $id_select[2] = 1;
        $ws3 = $wsdirehum->BuscarPrestacionWsMininter($request->id_trama3);
        $ws3 = $ws3->prestacion;
        if(isset($ws3)){
          $ws3 = $ws3[0];
          if($ws3->msg == 'Datos recibidos correctamente'){
            $id_enviado[2] = 1;
              $model_auditoria = trama_prestacion::find($request->id_trama3);
              $model_auditoria->id_estado = 1;
              $model_auditoria->id_saludpol = $ws3->idprestacion;

              $estados_trama = new \App\Models\Actualiza_estados_trama;
              $estados_trama->id_trama_prestacion = $request->id_trama3;
              $estados_trama->id_cups = $model_auditoria->id_cups;
              $estados_trama->fecha_auditoria = $model_auditoria->fech_alta_atenc;
              $estados_trama->estado_previo = 0;
              $estados_trama->id_estado_reg = 1;
              $estados_trama->estado_actualizado = 1;
              $estados_trama->id_user_responsable = Auth::user()->id;
              $estados_trama->save();
              $model_auditoria->save();
          }
        }
      }else{
        $id_select[2] = 0;
      }
      if(isset($request->check4)){
        $id_select[3] = 1;
        $ws4 = $wsdirehum->BuscarPrestacionWsMininter($request->id_trama4);
        $ws4 = $ws4->prestacion;
        if(isset($ws4)){
          $ws4 = $ws4[0];
          if($ws4->msg == 'Datos recibidos correctamente'){
            $id_enviado[3] = 1;
              $model_auditoria = trama_prestacion::find($request->id_trama4);
              $model_auditoria->id_estado = 1;
              $model_auditoria->id_saludpol = $ws4->idprestacion;

              $estados_trama = new \App\Models\Actualiza_estados_trama;
              $estados_trama->id_trama_prestacion = $request->id_trama4;
              $estados_trama->id_cups = $model_auditoria->id_cups;
              $estados_trama->fecha_auditoria = $model_auditoria->fech_alta_atenc;
              $estados_trama->estado_previo = 0;
              $estados_trama->id_estado_reg = 1;
              $estados_trama->estado_actualizado = 1;
              $estados_trama->id_user_responsable = Auth::user()->id;
              $estados_trama->save();
              $model_auditoria->save();
          }
        }
      }else{
        $id_select[3] = 0;
      }
      if(isset($request->check5)){
        $id_select[4] = 1;
        $ws5 = $wsdirehum->BuscarPrestacionWsMininter($request->id_trama5);
        $ws5 = $ws5->prestacion;
        if(isset($ws5)){
          $ws5 = $ws5[0];
          if($ws5->msg == 'Datos recibidos correctamente'){
            $id_enviado[4] = 1;
              $model_auditoria = trama_prestacion::find($request->id_trama5);
              $model_auditoria->id_estado = 1;
              $model_auditoria->id_saludpol = $ws5->idprestacion;

              $estados_trama = new \App\Models\Actualiza_estados_trama;
              $estados_trama->id_trama_prestacion = $request->id_trama5;
              $estados_trama->id_cups = $model_auditoria->id_cups;
              $estados_trama->fecha_auditoria = $model_auditoria->fech_alta_atenc;
              $estados_trama->estado_previo = 0;
              $estados_trama->id_estado_reg = 1;
              $estados_trama->estado_actualizado = 1;
              $estados_trama->id_user_responsable = Auth::user()->id;
              $estados_trama->save();
              $model_auditoria->save();
          }
        }
      }else{
        $id_select[4] = 0;
      }
      if(isset($request->check6)){
        $id_select[5] = 1;
        $ws6 = $wsdirehum->BuscarPrestacionWsMininter($request->id_trama6);
        $ws6 = $ws6->prestacion;
        if(isset($ws6)){
          $ws6 = $ws6[0];
          if($ws6->msg == 'Datos recibidos correctamente'){
            $id_enviado[5] = 1;
              $model_auditoria = trama_prestacion::find($request->id_trama6);
              $model_auditoria->id_estado = 1;
              $model_auditoria->id_saludpol = $ws6->idprestacion;

              $estados_trama = new \App\Models\Actualiza_estados_trama;
              $estados_trama->id_trama_prestacion = $request->id_trama6;
              $estados_trama->id_cups = $model_auditoria->id_cups;
              $estados_trama->fecha_auditoria = $model_auditoria->fech_alta_atenc;
              $estados_trama->estado_previo = 0;
              $estados_trama->id_estado_reg = 1;
              $estados_trama->estado_actualizado = 1;
              $estados_trama->id_user_responsable = Auth::user()->id;
              $estados_trama->save();
              $model_auditoria->save();
          }
        }
      }else{
        $id_select[5] = 0;
      }
      if(isset($request->check7)){
        $id_select[6] = 1;
        $ws7 = $wsdirehum->BuscarPrestacionWsMininter($request->id_trama7);
        $ws7 = $ws7->prestacion;
        if(isset($ws7)){
          $ws7 = $ws7[0];
          if($ws7->msg == 'Datos recibidos correctamente'){
            $id_enviado[6] = 1;
              $model_auditoria = trama_prestacion::find($request->id_trama7);
              $model_auditoria->id_estado = 1;
              $model_auditoria->id_saludpol = $ws7->idprestacion;

              $estados_trama = new \App\Models\Actualiza_estados_trama;
              $estados_trama->id_trama_prestacion = $request->id_trama7;
              $estados_trama->id_cups = $model_auditoria->id_cups;
              $estados_trama->fecha_auditoria = $model_auditoria->fech_alta_atenc;
              $estados_trama->estado_previo = 0;
              $estados_trama->id_estado_reg = 1;
              $estados_trama->estado_actualizado = 1;
              $estados_trama->id_user_responsable = Auth::user()->id;
              $estados_trama->save();
              $model_auditoria->save();
          }
        }
      }else{
        $id_select[6] = 0;
      }
      if(isset($request->check8)){
        $id_select[7] = 1;
        $ws8 = $wsdirehum->BuscarPrestacionWsMininter($request->id_trama8);
        $ws8 = $ws8->prestacion;
        if(isset($ws8)){
          $ws8 = $ws8[0];
          if($ws8->msg == 'Datos recibidos correctamente'){
            $id_enviado[7] = 1;
              $model_auditoria = trama_prestacion::find($request->id_trama8);
              $model_auditoria->id_estado = 1;
              $model_auditoria->id_saludpol = $ws8->idprestacion;

              $estados_trama = new \App\Models\Actualiza_estados_trama;
              $estados_trama->id_trama_prestacion = $request->id_trama8;
              $estados_trama->id_cups = $model_auditoria->id_cups;
              $estados_trama->fecha_auditoria = $model_auditoria->fech_alta_atenc;
              $estados_trama->estado_previo = 0;
              $estados_trama->id_estado_reg = 1;
              $estados_trama->estado_actualizado = 1;
              $estados_trama->id_user_responsable = Auth::user()->id;
              $estados_trama->save();
              $model_auditoria->save();
          }
        }
      }else{
        $id_select[7] = 0;
      }
      if(isset($request->check9)){
        $id_select[8] = 1;
        $ws9 = $wsdirehum->BuscarPrestacionWsMininter($request->id_trama9);
        $ws9 = $ws9->prestacion;
        if(isset($ws9)){
          $ws9 = $ws9[0];
          if($ws9->msg == 'Datos recibidos correctamente'){
            $id_enviado[8] = 1;
              $model_auditoria = trama_prestacion::find($request->id_trama9);
              $model_auditoria->id_estado = 1;
              $model_auditoria->id_saludpol = $ws9->idprestacion;

              $estados_trama = new \App\Models\Actualiza_estados_trama;
              $estados_trama->id_trama_prestacion = $request->id_trama9;
              $estados_trama->id_cups = $model_auditoria->id_cups;
              $estados_trama->fecha_auditoria = $model_auditoria->fech_alta_atenc;
              $estados_trama->estado_previo = 0;
              $estados_trama->id_estado_reg = 1;
              $estados_trama->estado_actualizado = 1;
              $estados_trama->id_user_responsable = Auth::user()->id;
              $estados_trama->save();
              $model_auditoria->save();
          }
        }
      }else{
        $id_select[8] = 0;
      }
      if(isset($request->check10)){
        $id_select[9] = 1;
        $ws10 = $wsdirehum->BuscarPrestacionWsMininter($request->id_trama10);
        $ws10 = $ws10->prestacion;
        if(isset($ws10)){
          $ws10 = $ws10[0];
          if($ws10->msg == 'Datos recibidos correctamente'){
            $id_enviado[9] = 1;
              $model_auditoria = trama_prestacion::find($request->id_trama10);
              $model_auditoria->id_estado = 1;
              $model_auditoria->id_saludpol = $ws10->idprestacion;

              $estados_trama = new \App\Models\Actualiza_estados_trama;
              $estados_trama->id_trama_prestacion = $request->id_trama10;
              $estados_trama->id_cups = $model_auditoria->id_cups;
              $estados_trama->fecha_auditoria = $model_auditoria->fech_alta_atenc;
              $estados_trama->estado_previo = 0;
              $estados_trama->id_estado_reg = 1;
              $estados_trama->estado_actualizado = 1;
              $estados_trama->id_user_responsable = Auth::user()->id;
              $estados_trama->save();
              $model_auditoria->save();
          }
        }
      }else{
        $id_select[9] = 0;
      }
   
    return view('frontend.auditoria.envio_masivo_10', [
      'id_array'=> $id_array,
      'id_select' => $id_select,
      'id_enviado' => $id_enviado,
    ]);

  }

  public function envio_masivo_1a1(){
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $listado_trama = "SELECT * FROM trama_prestacion where id_estado = '0' and  numero_correlativo is not null order by id desc limit 10";
    $listado_trama = DB::select($listado_trama);
    $model_auditoria = new \App\Models\Auditoria;
      $data = 0;
      $array_precio = [];
    foreach($listado_trama as $data_listado){
      $procedimientos_trama = $model_auditoria->getProcedimientosByAuditoria($data_listado->id);
      $productos_trama = $model_auditoria->getProductosByAuditoria($data_listado->id);
      $dato_precio = 0;
      foreach($productos_trama as $datos){
        $dato_precio = $datos->valorizacion + $dato_precio;
      }
      foreach($procedimientos_trama as $datos){
        $dato_precio = $datos->valorizacion_procedimiento + $dato_precio;
      }
      $array_precio[$data] = $dato_precio;
      $data = $data + 1;
    }
    
    return view('frontend.auditoria.envio_masivo_1a1', [
      'listado_trama'=> $listado_trama,
      'array_precio' => $array_precio
    ]);

  }
  
  public function ver_datos_enviados($numero_correlativo)
  {
      $model_auditoria = new \App\Models\Auditoria;
      $wssaludpol = new \App\Ws\Saludpol;
      $model_interoperabilidad = new \App\Models\Trama_interoperabilidad();

       $dni_user = Auth::user()->dni;

    $estable_actual = Auth::user()->id_establecimiento;
    $establecimiento_actual =  DB::table('establecimientos')
    ->select('establecimientos.*')
    ->where('id', $estable_actual)
    ->first();
    $date = Carbon::now();
    $dias = $date->day;
    $mes = $date->month;
    $año = $date->format('y');
    $codigo_paquete = $establecimiento_actual->codigo. $año.$mes.$dias;
   
    $token_saludpol = $model_interoperabilidad->token_saludpol($codigo_paquete);
    //dd($token_saludpol);
    $token_saludpol = trim($token_saludpol, '"');
    $data = json_decode($token_saludpol, true);
    $token = isset($data['token']) ? $data['token'] : 0;
   
      $prestacion = $model_auditoria->getPrestacionById_Auditoria($numero_correlativo);
      $resultado = $model_interoperabilidad->envioObservacionPrestacion($prestacion,$token);
     dd($resultado);
      $fecha_atencion = $prestacion->fecha_atencion_fups;
      $dni_pac = $prestacion->paciente_nro_doc_identidad;
      $consulta_ws = $wssaludpol->BuscarTitularFamiliar(1,$dni_pac,$fecha_atencion);
      $consulta_ws = $wssaludpol->BuscarTitularFamiliar(
          1,
          $dni_pac,
          $fecha_atencion
      );

      if (is_array($consulta_ws) && isset($consulta_ws[0]->Error)) {

          $estado_asegurado = 'INACTIVO';
          $estado_situacion = 'INACTIVO';
          $estado_caducidad = '';

      } elseif (is_object($consulta_ws)) {

          $estado_asegurado = isset($consulta_ws->estado)
              ? $consulta_ws->estado
              : '';

          $estado_situacion = isset($consulta_ws->situacion)
              ? $consulta_ws->situacion
              : '';

          $estado_caducidad = isset($consulta_ws->caducidad)
              ? $consulta_ws->caducidad
              : '';

      } else {

          $estado_asegurado = '';
          $estado_situacion = '';
          $estado_caducidad = '';
      }

      $año_caducidad = '';
      $mes_caducidad = '';
      $dia_caducidad = '';

      if (!empty($estado_caducidad) && strlen($estado_caducidad) >= 8) {

          $año_caducidad = substr($estado_caducidad, 0, 4);
          $mes_caducidad = substr($estado_caducidad, 4, 2);
          $dia_caducidad = substr($estado_caducidad, 6, 2);
      }

      $dni = DB::table('tipo_documento_identidad')
              ->select('tipo_documento_identidad.*')
              ->where('estado', '1')
              ->where('id', $prestacion->tipo_doc_identidad)
              ->first();       
      $nombre_docu = $dni->descripcion;
      $dni_m = DB::table('tipo_documento_identidad')
      ->select('tipo_documento_identidad.*')
      ->where('estado', '1')
      ->where('id', $prestacion->id_tipodoc_ident)
      ->first();
      $nombre_docu_m = $dni_m->descripcion;
      $upss = DB::table('upsses')
            ->select('upsses.*')
            ->where('estado', '1')
            ->where('codigo', $prestacion->id_upss_fups)
            ->first();
      $nombre_upss = $upss->descripcion_upss;
      $tipo_covertura = DB::table('tipo_atenciones')
                        ->select('tipo_atenciones.*')
                        ->where('estado', '2')
                        ->where('cod_saludpol', $prestacion->id_tipo_cobert)
                        ->first();             
      $nombre_cobertura = $tipo_covertura->nombre;
      $destino = DB::table('destinos')
                    ->select('destinos.*')
                    ->where('estado', 'S')
                    ->where('cod_saludpol', $prestacion->id_destino_aseg_fups)
                    ->first();
      $nombre_destino = $destino->descripcion;
      $id_cups = DB::table('cups_pnp')
                  ->select('cups_pnp.*')
                  ->where('id', $prestacion->id_cups)
                  ->first();
      $nombre_cups = $id_cups->codigo_ipress.'-'.$id_cups->año.'-'.$id_cups->intramural_extramural.'-'.$id_cups->no_referidos_referidos.'-'.$id_cups->tipo_atencion.'-'.$id_cups->id_ext;

        if (
            isset($resultado['data']) &&
            is_array($resultado['data']) &&
            count($resultado['data']) > 0
        ) {
            $model_trama_pres = trama_prestacion::find($prestacion->id);
            if ($model_trama_pres) {
                $model_trama_pres->id_estado = 3;
                $model_trama_pres->id_saludpol = 5;
                $model_trama_pres->save();
            }
        }else{
            $model_trama_pres = trama_prestacion::find($prestacion->id);
            if ($model_trama_pres) {
                $model_trama_pres->id_estado = 4;
                $model_trama_pres->save();
            }
        }
      
      $estado = DB::table('trama_estado as te')
        ->join('trama_prestacion as tp', 'te.id_estado', '=', 'tp.id_estado')
        ->select('te.*')
        ->where('tp.id', $prestacion->id)
        ->first();
      $nombre_estado = $estado->descripcion;
      $observacion_saludpol = [];

      foreach ($resultado['data'] as $item) {

          $observacion_saludpol[] = [
              'id' => $item['id'],
              'tipoRegla'  => $item['tipoRegla'],
              'codRegla' => $item['codRegla'],
              'valorObservado' => $item['valorObservado'],
              'descripcion' => $item['descripcionObservacion'],
          ];
      }

      return view('frontend.auditoria.ver_datos_enviados', [
          'prestacion' => $prestacion,
          'nombre_upss'=>$nombre_upss,
          'nombre_docu'=>$nombre_docu,
          'nombre_docu_m'=>$nombre_docu_m,
          'nombre_cobertura'=>$nombre_cobertura,
          'nombre_destino'=>$nombre_destino,
          'nombre_cups'=>$nombre_cups,
          'nombre_estado'=>$nombre_estado,
          'observacion_saludpol'=>$observacion_saludpol,
          'estado_asegurado' => $estado_asegurado,
          'estado_situacion' => $estado_situacion,
          'año_caducidad' => $año_caducidad,
          'mes_caducidad' => $mes_caducidad,
          'dia_caducidad' => $dia_caducidad
      ]);
  }


  function enviar_masivo_ws($opcion)  {
    /*
    $trama_paquete_masivo = new \App\Models\trama_paquete_masivo();
    $trama_paquete_masivo->estado = 1;
    $trama_paquete_masivo->created_us = Auth::user()->id;
    $trama_paquete_masivo->dato_correlativo = 0;
    $trama_paquete_masivo->save();*/
    $dni_user = Auth::user()->dni;

    $estable_actual = Auth::user()->id_establecimiento;
    $establecimiento_actual =  DB::table('establecimientos')
    ->select('establecimientos.*')
    ->where('id', $estable_actual)
    ->first();
    $date = Carbon::now();
    $dias = $date->day;
    $mes = $date->month;
    $año = $date->format('y');
    $codigo_paquete = $establecimiento_actual->codigo. $año.$mes.$dias;

    $model_interoperabilidad = new \App\Models\Trama_interoperabilidad();
       
    $token_saludpol = $model_interoperabilidad->token_saludpol($codigo_paquete);
    //dd($token_saludpol);
    $token_saludpol = trim($token_saludpol, '"');
    $data = json_decode($token_saludpol, true);
    $token = isset($data['token']) ? $data['token'] : 0;
    $dni_user =$token;
    $enviar_masivo_1 = $model_interoperabilidad->Envio_trama_masivo($opcion,$codigo_paquete,$dni_user);
    dd($enviar_masivo_1);
      $flagFallos = 0;
      $codigo_error = '';

      if ($enviar_masivo_1->data->prestacionesFallidas > 0) {
          $flagFallos = 1;
          $codigo_error = $enviar_masivo_1->data->numeroProceso;
      }
    dd($codigo_error);

    if($opcion == '2'){
      return redirect('/all_auditoria_ambulatorio/');
    }else if($opcion == '1'){
      return redirect('/all_auditoria_hospi/');
    }else if ($opcion == '0'){
      return redirect('/all_auditoria/');
    }else{
      return redirect('/auth/login');
    }

  }

  public function exportar_reporte_auditoria_mejorado($anio,$mes,$tipo_atencion_detalle,$ipress,
  $estado_trama,$cod_paquete,$resultado) {
    $date = Carbon::now();
    Excel::create('Reporte_Auditoria Del '.$mes.'/'.$anio.'. Fecha del reporte '.$date, function($excel) use($date, $anio, $mes, $tipo_atencion_detalle, $ipress, $estado_trama, $cod_paquete, $resultado) {
      $excel->sheet('Sheet 1', function($sheet) use($date, $anio, $mes, $tipo_atencion_detalle, $ipress, $estado_trama, $cod_paquete, $resultado) {
         
          $variable = [];
          $n = 1;
          $model_fema = new \App\Models\Auditoria;
          $femas = $model_fema->GetReporteAuditoriaGeneral($date,Auth::user()->dni, $anio, $mes, $tipo_atencion_detalle, $ipress, $estado_trama, $cod_paquete, $resultado);
  
          $sheet->mergeCells('A1:F1');
          $sheet->cell('A1', function($cell) {$cell->setValue('Reporte De Auditoria General ');  $cell->setFontSize(15); $cell->setFontWeight('bold'); $cell->setAlignment('center'); $cell->setValignment('middle'); });
          
          $sheet->mergeCells('A3:A3');
          $sheet->cell('A3', function($cell) {$cell->setValue('#');   $cell->setFontSize(13); $cell->setFontWeight('bold'); $cell->setAlignment('center'); $cell->setValignment('middle'); $cell->setBackground('#3809F7'); });
          $sheet->mergeCells('B3:H3');
          $sheet->cell('B3', function($cell) {$cell->setValue('DATOS ');   $cell->setFontSize(13); $cell->setFontWeight('bold'); $cell->setAlignment('center'); $cell->setValignment('middle'); $cell->setBackground('#09BB62');});

          array_push($variable, array(' Reporte del mes  ' . $mes . ' y del año  ' . $anio));
          array_push($variable, array(""));
          array_push($variable, array("N","CUPS ","DNI PACIENTE","DATOS PACIENTE","FECHA PRESTACION","TIPO DE ATENCION","ESTADO","PRECIO"));
          foreach ($femas as $u) {
              if($u->id_estado == '1'){
                $dato_estaos = 'Enviado';
              }else{
                $dato_estaos = 'No Enviado';
              }
              if ($u->tipo_atencion == '1') {
                  $dato_tip_ate = 'Consulta Externa';
              } elseif ($u->tipo_atencion == '2') {
                  $dato_tip_ate = 'Emergencia';
              } else {
                  $dato_tip_ate = 'Hospitalizacion';
              }

              array_push($variable, array($n++, $u->codigo_ipress.' - '. $u->año.' - '. $u->intramural_extramural.' - '. $u->no_referidos_referidos.' - '. $u->tipo_atencion.' - '. $u->id_ext,
              $u->paciente_nro_doc_identidad,$u->paciente_apellido_paterno.' '.$u->paciente_apellido_materno.' '.$u->paciente_nombres,$u->fecha_atencion_fups,$dato_tip_ate,$dato_estaos,$u->precio)); 
          }

          $sheet->with($variable)->mergeCells('A2:W2'); 
      });
  })->export('xlsx');
}
  public function ver_atenciones($id_atencion, $estado, $id_tipo_atencion)
  {
    switch ($id_tipo_atencion) {
      case 2:
          return $this->ver_atenciones_evaluacion_emergencia($id_atencion, $estado, $id_tipo_atencion);
      case 3:
          return $this->ver_atenciones_evaluacion_hospitalizacion($id_atencion, $estado, $id_tipo_atencion);
      default:
          abort(404, 'Tipo de atención no válido.');
    }
  }
  public function ver_atenciones_evaluacion_emergencia($id_atencion, $estado, $id_tipo_atencion)
  {
      
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $date = Carbon::now();
    $fechaHasta = $date->format('d-m-Y');
    $fechaDesdex = $date->format('d-m-Y');
    $fechaDesdey = strtotime('-30 day', strtotime($fechaDesdex));
    $fechaDesde = date('d-m-Y', $fechaDesdey);
    $model_emergencias = new Emergencia;

    if($id_tipo_atencion==2){
      $verificar = Emergencia::where('id', $id_atencion)->where('estado', $estado)->first();
    }
    else{
      $verificar = Hospitalizacione::where('id', $id_atencion)->where('estado', $estado)->first(); 
    }

    $model_consultorio = new Consultorio;

    if(is_object($verificar)):
      $paciente = $model_emergencias->GetBuscaPacienteEmergencia($id_atencion, $estado);
      if(count($paciente)>0):
        $id_user = Auth::user()->id;
        //$usuario_actual = $model_usuario->GetById($id_user);
        $flagemergenciareferencia = Auth::user()->flaghistoria;
        $id_consultorio_actual = Auth::user()->id_consultorio;
        $dni_beneficiario = "";
        $nombre_beneficiario = "";
        $estado = "1";
        $id_atencione = $id_tipo_atencion;

        //====================
        $model_farma = new Farmacia();
        $farmacia = $model_farma->GetFarmaByUser($id_user);
        $id_farmacia = $farmacia->id;
        $id_ipress = $farmacia->id_establecimiento;
        $modo_validacion = $farmacia->modo_validacion;

        $model_consultorio = new Consultorio;
        $consultorio = $model_consultorio->ShowAllConsultoriosSubConsultorio($farmacia->id_establecimiento);

        $medico = 0;

        $model_establecimiento = new \App\Models\Establecimiento;
        $establecimientos = $model_establecimiento->GetById($id_ipress);
        if(Auth::user()->tipo_registro==1){
          $model_medico = new Medico();
          $medico_actual = $model_medico->GetMedicoByDniAndEstablecimiento(Auth::user()->dni,$farmacia->id_establecimiento);
          $medico = $medico_actual->id;
        }
    
        $datoadicional = $model_emergencias->GetObtenerCama($paciente->cama);

        $ruta = "all_pacientes_evaluaciones_by_emergencia";
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        $model_tipo_parentesco = new \App\Models\Tipo_parentescos;
            $tipo_parentesco_obj = $model_tipo_parentesco->getTipo_parentescos_by_id($paciente->id_tipo_parentesco); 
            $tipo_parentesco=$tipo_parentesco_obj[0]->descripcion;

        return view('frontend.auditoria.all_evaluaciones_atenciones_emergencia', compact('farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nombre_beneficiario', 'estado','flagemergenciareferencia','consultorio','medico','id_consultorio_actual','id_atencione','paciente','id_atencion','establecimientos','datoadicional','permiso','tipo_parentesco'));
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
  public function ver_atenciones_evaluacion_hospitalizacion($id_hospitalizacion, $estado, $id_tipo_atencion)
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
              return view('frontend.auditoria.all_evaluaciones_atenciones_hospitalizacion',compact('fechaDesde', 'fechaHasta','estado','id_atencione','paciente','id_hospitalizacion','datoadicional','permiso','atenciones'));
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

  public function ver_prestacion($id_user, $id_prestacion)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    if (Auth::user()->tipo_registro == 1) {
      $modelfarma = new Farmacia;
      $model_receta = new \App\Models\Receta_vale;
      $model_producto = new \App\Models\Producto;
      $model_productos_receta = new \App\Models\Producto_receta;
      $model_establecimiento = new \App\Models\Establecimiento;
      $model_prestacion = new \App\Models\Prestaciones;
      $model_gestacion = new \App\Models\Gestacion;
      $model_laboratorio = new \App\Models\Laboratorio;
      $model_radiologia = new \App\Models\Radiologia;
      $model_interconsulta = new \App\Models\Interconsulta;
      $model_referencia = new \App\Models\Referencia;
      $model_kardex = new \App\Models\Kardexe();

      $farmacia = $modelfarma->GetFarmaByUser($id_user);
      $id_farmacia = $farmacia->id;
      $flag_receta_dias = $farmacia->flag_receta_dias;

      $fechaServidor = $model_kardex->getFechaServidor();
      $date = Carbon::now();
      $fecha_actual = $date->format('d-m-Y');


      //      $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
      $prestacion = $model_gestacion->GetPrestacionById($id_prestacion);
      $receta = $model_receta->GetDatosRecetasByIdPrestacion($id_prestacion);
      $prestaciones = $model_prestacion->GetPrestacionById($id_prestacion);
      $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
      // Inicio Centro Quirurgico
      $model_cqx_cita = new \App\Models\CqxCita;
      $riesgos_qx = $model_cqx_cita->GetRiesgos($id_prestacion);
      $interconsultas_pre_qx = $model_interconsulta->GetInterconsultaPrequirurgicaByIdPrestacion($id_prestacion);
      $data_cqx_cita = $model_cqx_cita->GetCitaByIdPrestacion($id_prestacion);
      $cqx_cita_profesionales = null;
      $cqx_cita_procedimientos = null;
      if ($data_cqx_cita != null) {
        $model_cqx_profesional = new \App\Models\CqxProfesional();
        $cqx_cita_profesionales = $model_cqx_profesional->GetByIdCqxIdCita($data_cqx_cita->id_cita,$data_cqx_cita->id);
        $model_cqx_procedimiento = new \App\Models\CqxProcedimiento();
        $cqx_cita_procedimientos = $model_cqx_procedimiento->GetByIdCqxIdCita($data_cqx_cita->id_cita,$data_cqx_cita->id);
      }
      // Fin Centro Quirurgico
      $diagnosticos = \App\Models\Diagnostico::where('estado',1)
      ->whereRaw('LENGTH(codigo) > 4') 
      ->get();
      if($id_prestacion>1910698):
        $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionydiag($id_prestacion, 1);
      else:
        $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 1);
      endif;
      $procedimientos = \App\Models\Procedimiento::where('estado',1)
      ->get();
      // TRAER DATA DE GESTACION:
      $prestacion_for_find_gestacion = Prestaciones::find($prestacion->id);
      $gestacion = null;
      if (isset($prestacion_for_find_gestacion->id_gestacion)) {
        $gestacion = \App\Models\Gestacion::find($prestacion_for_find_gestacion->id_gestacion);
        error_log("GESTACION DE PRESTACION CITA: ".(string)$gestacion->id);
      }
      // Fin insercion se gestacion

      
      $puerperio = null;
      $emergencia = Emergencia::find($prestacion_for_find_gestacion->id_ext);
      if (isset($emergencia) && isset($emergencia->id_puerperio)) {
        $puerperio = \App\Models\Puerperio::find($emergencia->id_puerperio);
      }

      $natis = null;
      if (isset($puerperio)) {
        $natis = \App\Models\Nati::where("id_puerperio", $puerperio->id)->get();
      }

      //$laboratorio = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 2);
      $laboratorio = $model_laboratorio->GetLaboratorioByIdPrestacion($id_prestacion);
      if (empty($laboratorio)) {
        $flag_laboratorio = "0";
        $flag_firmar_laboratorio = "";
      } else {
        $flag_laboratorio = "1";
        $flag_firmar_laboratorio = $laboratorio[0]->fecha_firma;
      }
      //$radiologia = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 3);
      $radiologia = $model_radiologia->GetRadiologiaByIdPrestacion($id_prestacion);
      if (count($radiologia)>0) {
        $flag_radiologia = "1";
        $flag_firmar_radiologia = $radiologia[0]->fecha_firma;
      } else {
        $flag_radiologia = "0";
        $flag_firmar_radiologia = "";
      }
      $pendiente_firma = $model_prestacion->GetDocumentosPendintesFirmarPrestacionId($id_prestacion);
      $flag_pendinte_firma = (count($pendiente_firma) > 0) ? 1 : 0;

      $id_ipress = $farmacia->id_establecimiento;
      $establecimiento = $model_establecimiento->GetById($id_ipress);
      if ($id_ipress == 1) {
        $nivel = 2;
      } else {
        $nivel = $establecimiento[0]->nivel;
      }
        $referencia = "";
        $dato_texto_obs = "";
        $dato_servicio_ref = "";

      if($prestaciones->flag_ref_cref == '1'){
        $referencia = $model_referencia->GetReferenciaByIdPrestacion($id_prestacion);
        $servicio_referencia  = "SELECT * FROM consultorios where id = '$referencia->id_servicio'";
        $servicio_referencia =  DB::select($servicio_referencia);
        $servicio_referencia = $servicio_referencia[0];

        $dato_texto_obs = $referencia->observacion_referencia;
        $dato_servicio_ref = $servicio_referencia->nombre;
      }
      
      if($prestaciones->flag_ref_cref == '2'){
        $referencia = $model_referencia->GetContraReferenciaByIdPrestacion($id_prestacion);
        $servicio_referencia  = "SELECT * FROM consultorios where id = '$referencia->id_servicio_contrareferencia'";
        $servicio_referencia =  DB::select($servicio_referencia);
        $servicio_referencia = $servicio_referencia[0];

        $dato_texto_obs = $referencia->observacion_contrareferencia;
        $dato_servicio_ref = $servicio_referencia->nombre;
      }

      //Si tiene atenciones en hospitalizacion o emergencia
      if($prestacion->id_ext!=0):
        if($prestacion->tipo_atencion=='EMERGENCIA'):
          $model_emergencia = new \App\Models\Emergencia;
          $obtenerdato = $model_emergencia->GetObtenerServicio($prestacion->id_ext);
          $prestacion->servicio= $obtenerdato->servicio;
          $prestacion->consultorio= $obtenerdato->consultorio;

          $producto = $model_producto->getProductosByNivelAndIdServicio($nivel, 76, $id_farmacia); //76 -> servicio de hopitalizacion 
        else:
          //if($prestacion->id_tipo_atencion=3){
          if($prestacion->tipo_atencion=='HOSPITALIZACION'){
            $model_hospitalizacion = new \App\Models\Hospitalizacione;
            $obtenerdatoh = $model_hospitalizacion->GetObtenerDatos($prestacion->id_ext);
            $prestacion->servicio= $obtenerdatoh->servicio;
            $prestacion->consultorio= $obtenerdatoh->consultorio;
            $prestacion->pabellon= $obtenerdatoh->pabellon;
            $prestacion->piso= $obtenerdatoh->piso;
            $prestacion->cuarto= $obtenerdatoh->cuarto;
            $prestacion->cama= $obtenerdatoh->cama;
          
          }

          $producto = $model_producto->getProductosByNivelAndIdServicio($nivel, $prestacion->id_consultorio, $id_farmacia);
        endif; 
      else:
        if($prestacion->tipo_atencion=='HOSPITALIZACION'){
          $prestacion->servicio= 'HOSPITALIZACION';
          $prestacion->consultorio= 'C1';
          $prestacion->pabellon= '';
          $prestacion->piso= '';
          $prestacion->cuarto= '';
          $prestacion->cama= '';
        }
      endif;
    
      $model_cita = new \App\Models\Cita;
      $cita = $model_cita->GetCitasById($prestacion->id_cita);
   
      if($cita->id_referencia != 0){
        $model_referencia = new \App\Models\Referencia;
        $referencias = $model_referencia->GetBuscaReferenciaById($cita->id_referencia);
        $model_prestacionreferncia = new \App\Models\Prestaciones;
        $diagnostico_prestacion_referencia = $model_prestacionreferncia->GetDiagnosticoByIdPrestacion($referencias->id_prestacion);
        $procedimientos_refencia = $model_referencia->buscar_procedimiento_referencia($cita->id_referencia);
      }else{
        $diagnostico_prestacion_referencia = "";
        $referencias = "";
        $procedimientos_refencia="";
      }
 
      $producto = $model_producto->getProductosByNivelAndIdServicio($nivel, $prestacion->id_consultorio, $id_farmacia);
      
      $farmacias = $modelfarma->GetFarmaciaByIdEstablecimiento($id_ipress);
      $establecimientos = $model_establecimiento->ShowAllEstablecimientos();
      $interconsultas = $model_interconsulta->GetInterconsultaByIdPrestacion($id_prestacion);
      $referencia = $model_referencia->GetReferenciaByIdPrest($id_prestacion);
        
      $datos_oftalmo = null;

      if ($prestacion->id_consultorio == 13 &&  $prestacion->id_establecimiento==76) {
        //----------- INICIO OFTALMOLOGÌA -----------
        $datos_oftalmo = \App\Models\Oftalmologia::where('prestacion_id', '=', $id_prestacion)->first();
        //----------- FIN OFTALMOLOGÌA -----------
      }

      $datos_gastro = null;

      if ($prestacion->id_consultorio == 102 &&  $prestacion->id_establecimiento==76) {
        //----------- INICIO OFTALMOLOGÌA -----------
        $datos_gastro = \App\Models\Gastroenterologia::where('prestacion_id', '=', $id_prestacion)->first();
        //----------- FIN OFTALMOLOGÌA -----------
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
      //dd($ordenes);
      //========================================================================================

      return view('frontend.auditoria.ver_prestacion_ambulatoria', compact('farmacia','dato_texto_obs','cita','diagnostico_prestacion_referencia','procedimientos_refencia','referencias' ,'dato_servicio_ref','prestacion', 'prestaciones','diagnostico', 'procedimiento', 'medicamentos', 'id_prestacion', 'fechaServidor', 'receta', 'producto', 'farmacias', 'nivel', 'laboratorio', 'radiologia', 'flag_pendinte_firma','establecimientos','interconsultas','referencia','diagnosticos','procedimientos', 'gestacion', 'flag_laboratorio', 'flag_firmar_laboratorio','datos_oftalmo', 'puerperio', 'natis','flag_radiologia','flag_firmar_radiologia','riesgos_qx', 'interconsultas_pre_qx','datos_gastro', 'data_cqx_cita', 'cqx_cita_profesionales', 'cqx_cita_procedimientos','ordenImagenesAnulada','ordenes'));
    }
    else
    {
      return redirect('/dashboard')->withInput()->withErrors(array('No tiene el perfil de médico..!'));
    }
  }

  public function mostrar_evolucion($id_evaluacion, $id_tipo_atencion)
  {
    if (!Auth::check()) :
        return redirect('/auth/login');
    endif;
    $modelfarma = new Farmacia;
    $model_receta = new \App\Models\Receta_vale;
    $model_producto = new \App\Models\Producto;
    $model_productos_receta = new \App\Models\Producto_receta;
    $model_establecimiento = new \App\Models\Establecimiento;
    $model_prestacion = new \App\Models\Prestaciones;
    $model_laboratorio = new \App\Models\Laboratorio;
    $model_radiologia = new \App\Models\Radiologia;
    $model_interconsulta = new \App\Models\Interconsulta;
    $model_referencia = new \App\Models\Referencia;
    $model_evaluacion = new \App\Models\Evaluacione;
    $model_kardex = new \App\Models\Kardexe();
    $farmacia = $modelfarma->GetFarmaByUser(Auth::user()->id);
    $id_farmacia = $farmacia->id;
    $flag_receta_dias = $farmacia->flag_receta_dias;
    $fechaServidor = $model_kardex->getFechaServidor();
    $date = Carbon::now();
    $fecha_actual = $date->format('d-m-Y');
    $evaluacion = $model_evaluacion->GetEvaluacionById($id_evaluacion);
    
    //$model_emergencias = new Emergencia;
    $model_evolucion = new \App\Models\Evaluacione;
    $paciente = $model_evolucion->GetBuscaPacienteHospitalizacionEvaluacion($evaluacion->id_emergencia);
    
    //verifico si existe idemergencia
    if(count($evaluacion)>0):
        $id_prestacion =  $evaluacion->id_prestacion;
        $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
        $hospitalizacione = Hospitalizacione::find($prestacion->id_ext);
        $gestacion = null;
        $puerperio = null;
      if (isset($hospitalizacione->id_gestacion)) {
        $gestacion = \App\Models\Gestacion::find($hospitalizacione->id_gestacion);
        error_log('HAY GESTACION EN EVALUACION: '.(string)$gestacion);
      }
      if (isset($hospitalizacione->id_puerperio)) {
        $puerperio = \App\Models\Puerperio::find($hospitalizacione->id_puerperio);
        error_log('HAY PUERPERIO EN EVALUACION: '.(string)$puerperio);
        $natis = $puerperio->natis;
        error_log('sus natis: '.(string)$natis);
      }
      $receta = $model_receta->GetDatosRecetasByIdEvaluacion($id_evaluacion);
      $diagnostico_atencion = $model_prestacion->GetDiagnosticoByIdPrestacionEmergencia($id_prestacion);
      $procedimiento_atencion = $model_prestacion->GetProcedimientosByIdPrestacionYTipo($id_prestacion,1);
        
      $laboratorio = $model_laboratorio->GetLaboratorioByIdPrestacionEvaluacion($id_prestacion,$id_evaluacion);
      $radiologia = $model_radiologia->GetRadiologiaByIdPrestacionEvaluacion($id_prestacion,$id_evaluacion);
      $id_farmacia = Auth::user()->id_farmacia;
      
      $model_emergencia = new \App\Models\Emergencia;
      $diagnostico_evaluacion = $model_evaluacion->GetDiagnosticoByIdEvaluacion($id_evaluacion);
      $procedimiento_evaluacion = $model_prestacion->GetProcedimientosByIdPrestacionYIdEvolucionYTipo($id_prestacion, $id_evaluacion, 1);
      
      $datoadicional = $model_emergencia->GetObtenerCama($evaluacion->cama_estado);
      $interconsultas = $model_interconsulta->GetInterconsultaByIdEvaluacion2($id_prestacion,$id_evaluacion);
      $referencia = $model_referencia->GetReferenciaByIdPrest($id_prestacion);
      $flag_no_encontrado = 0;
      $model_asegurado= new \App\Models\Asegurado();
      if($prestacion->id_asegurado_titular!=null || $prestacion->id_asegurado_titular>0):
        $dato_titular = $model_asegurado->Busca_datos_titular($prestacion->id_asegurado_titular);
      else:
        $flag_no_encontrado = 1;
      endif;
      $date = Carbon::now();
        $talla_cm = $evaluacion->talla_cm;
        $peso_kg = $evaluacion->peso_kg;
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
        $natisEvaluacion = [];
        if (isset($evaluacion->id_puerperio)) {
            $model_nati = new \App\Models\Nati();
            $natisEvaluacion = $model_nati->getByIdPuerpera($evaluacion->id_puerperio);
        }
        $cambioCamaModel = new \App\Models\CambioCama();
        $traslado = $cambioCamaModel->getTrasladoByIdEvolucion($id_evaluacion);
        $diagnosticos_traslado = null;
        if (isset($traslado)) {
            $receta_diagnostico_model = new \App\Models\Receta_diagnostico();
            $diagnosticos_traslado = $receta_diagnostico_model->getDiagnosticosTrasladoByIdCambioCama($traslado->id);
        }
        //============== Ordenes de Imagenes invalidados por Imagenes ============================
        $ordenImagenesAnulada = $model_radiologia->cpmsAnuladosEvaluaciones(2,$id_prestacion,$id_evaluacion);
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
        //========================================================================================
      return view('frontend.auditoria.mostrar_evolucion', compact('farmacia', 'prestacion', 'diagnostico_atencion','diagnostico_evaluacion', 'procedimiento_atencion', 'procedimiento_evaluacion', 'medicamentos', 'fechaServidor', 'receta',  'laboratorio', 'radiologia','referencia','evaluacion','paciente','id_evaluacion','datoadicional','interconsultas','dato_titular', 'gestacion', 'puerperio', 'natis', 'peso_ideal','imc', 'natisEvaluacion', 'traslado', 'diagnosticos_traslado','flag_radiologia','ordenImagenesAnulada','ordenes'));
    else:
      return redirect('/dashboard')->withInput()->withErrors(array('No tiene el perfil de médico..!'));
    endif;
    
  }
  public function mostrar_evaluacion($id_evaluacion, $id_tipo_atencion)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $model_establecimiento = new \App\Models\Establecimiento;
    $model_kardex = new \App\Models\Kardexe();
    $model_evaluacion = new \App\Models\Evaluacione();
    $model_medico = new Medico();

    $fechaServidor = $model_kardex->getFechaServidor();
    $date = Carbon::now();
    $fecha_actual = $date->format('d-m-Y');
    
    $evaluacion = $model_evaluacion->GetEvaluacion($id_evaluacion);
    
    $medico = $model_medico->MostrarMedico($evaluacion->id_medico);
    
    $establecimiento = $model_establecimiento->GetById(Auth::user()->id_establecimiento);

    if ($id_tipo_atencion == 2)
      $verificar = Emergencia::where('id', $evaluacion->id_emergencia)->where('estado', 3)->first();
    else
      $verificar = Hospitalizacione::where('id', $evaluacion->id_emergencia)->where('estado', 3)->first();
    
    if(is_object($verificar)):
        if ($id_tipo_atencion == 2) {
          $model_emergencia = new Emergencia;
          $paciente = $model_emergencia->GetBuscaEmergencia( $evaluacion->id_emergencia,$evaluacion->id_medico, 3);
        } else {
          $paciente = $verificar;
        }
    endif;
    
    /*********************************************/
    $model_emergencia = new \App\Models\Emergencia;
    $modelfarma = new \App\Models\Farmacia;
    $model_prestacion = new \App\Models\Prestaciones;
    $model_laboratorio = new \App\Models\Laboratorio;
    $model_radiologia = new \App\Models\Radiologia;
    $model_interconsulta = new \App\Models\Interconsulta;
    $model_receta = new \App\Models\Receta_vale;
    $model_auditoria = new \App\Models\Auditoria;
    
    $obtenerdato = null;
    if ($id_tipo_atencion == 2) {
      $obtenerdato = $model_evaluacion->GetObtenerServicioEvaluacion($evaluacion->id);
      $evaluacion->servicio= $obtenerdato->servicio;
      $evaluacion->consultorio= $obtenerdato->consultorio;
    } 
    $id_ipress = Auth::user()->id_establecimiento;
    $farmacias = $modelfarma->GetFarmaciaByIdEstablecimiento($id_ipress);
    $establecimientos = $model_establecimiento->ShowAllEstablecimientos();
    $diagnostico_prestaciones = $model_auditoria->GetDiagnosticoByIdEvaluacion($id_evaluacion);
    $procedimiento_evaluacion = $model_auditoria->GetProcedimientosByIdEvaluacion($id_evaluacion);
    $datoadicional = $model_emergencia->GetObtenerCama($obtenerdato->cama);
    $laboratorio = $model_laboratorio->GetLaboratorioByIdPrestacionEvaluacion($evaluacion->id_prestacion,$id_evaluacion);
    $radiologia = $model_radiologia->GetRadiologiaByIdPrestacionEvaluacion($evaluacion->id_prestacion,$id_evaluacion);
    
    $interconsultas = $model_interconsulta->GetInterconsultaByIdEvaluacion2($evaluacion->id_prestacion,$id_evaluacion);
    $receta = $model_receta->GetDatosRecetasByIdEvaluacion($id_evaluacion);

    return view('frontend.auditoria.mostrar_evaluacion', compact('farmacias','receta','laboratorio','radiologia','interconsultas','diagnostico_prestaciones','procedimiento_evaluacion','evaluacion', 'paciente','fechaServidor', 'nivel','medico','datoadicional'));
  }
  public function mostrar_atencion_hospitalizacion($id_prestacion)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    if (Auth::user()->tipo_registro == 1) {
      $modelfarma = new Farmacia;
      $model_receta = new \App\Models\Receta_vale;
      $model_producto = new \App\Models\Producto;
      $model_productos_receta = new \App\Models\Producto_receta;
      $model_establecimiento = new \App\Models\Establecimiento;
      $model_prestacion = new \App\Models\Prestaciones;
      $model_laboratorio = new \App\Models\Laboratorio;
      $model_radiologia = new \App\Models\Radiologia;
      $model_interconsulta = new \App\Models\Interconsulta;
      $model_referencia = new \App\Models\Referencia;
      $model_kardex = new \App\Models\Kardexe();

      $id_user = Auth::user()->id;
      $farmacia = $modelfarma->GetFarmaByUser($id_user);
      $flag_receta_dias = $farmacia->flag_receta_dias;

      $fechaServidor = $model_kardex->getFechaServidor();
      $date = Carbon::now();
      $fecha_actual = $date->format('d-m-Y');
      $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
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
      $model_asegurado= new \App\Models\Asegurado();
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

      return view('frontend.auditoria.mostrar_atencion_hospitalizacion', compact('dato_titular','farmacia', 'prestacion', 'diagnostico', 'procedimiento', 'medicamentos', 'id_prestacion', 'fechaServidor', 'receta', 'producto', 'farmacias', 'nivel', 'laboratorio', 'radiologia', 'flag_pendinte_firma','establecimientos','interconsultas','referencia','procedimientos','datoadicional','sw','permiso','flag_no_encontrado','turno','id_farmacia','dato_hospitalizacion','obj_hospitalizacion_atencion','peso_ideal','imc'));
    }
    else
    {
      return redirect('/dashboard')->withInput()->withErrors(array('No tiene el perfil de médico..!'));
    }
  }
public function obtener_procedimiento_auditorias($buscar, $nivel, $genero, $tipo)
{

  //$model_procedimiento = new \App\Models\Procedimiento;
  //$procedimiento = $model_procedimiento->getProcedimientoByConvenioEmergencia($buscar, $nivel, $genero, $tipo);  
  $model_procedimiento = new \App\Models\Auditoria;
  $procedimiento = $model_procedimiento->getProcedimientoByConveniosAuditoria($buscar, $nivel, $genero, $tipo);  

  $array = array();
  foreach ($procedimiento as $leer) {
    $array[] = array(
      "value" => $leer->codigo . " - " . $leer->descripcion,
      "label" => $leer->codigo . " - " . $leer->descripcion,
      "id" => $leer->id,
    );
  }

  echo json_encode($array);
}
  public function saveIncluirProductosTrama(Request $request){
    try {
      $datos = $request->all();
      $model_auditoria = new Auditoria;
      if (!empty($datos['productos'])) {
          foreach($datos['productos'] as $p) {
              $trama_producto = new trama_producto();
              $trama_producto->id_trama_prestacion = $datos['id_trama_prestacion'];
              $trama_producto->id_prod_med = $p['codigo_producto'];
              $trama_producto->cantidad_prescrita = $p['cantidad_prescrita'];
              $trama_producto->cantidad_entregada =  $p['cantidad_dispensada'];
              $trama_producto->precio = $p['precio'];
              $trama_producto->valorizacion = $p['valorizacion'];
              $trama_producto->id_cie10 = $p['codigo_diagnostico'];
              $trama_producto->save();
              if (!$trama_producto) {
                return response()->json(['success' => false]);
              }
          }
          $data = $model_auditoria->getProductosByAuditoria($datos['id_trama_prestacion']);
          $procedimientos_trama = $model_auditoria->getProcedimientosByAuditoria($datos['id_trama_prestacion']);
          $productos_trama = $model_auditoria->getProductosByAuditoria($datos['id_trama_prestacion']);
          $dato_precio = 0;
          $dato_precio_procedimiento = 0;
          $dato_precio_productos = 0;
          foreach($productos_trama as $datos){
            $dato_precio_productos = $datos->valorizacion + $dato_precio_productos;
            $dato_precio = $datos->valorizacion + $dato_precio;
          }
          foreach($procedimientos_trama as $datos){
            $dato_precio_procedimiento = $datos->valorizacion_procedimiento + $dato_precio_procedimiento;
            $dato_precio = $datos->valorizacion_procedimiento + $dato_precio;
          }

          return response()->json(['success' => true,'productos'=>$data,'dato_precio_productos'=>$dato_precio_productos,'dato_precio_procedimiento'=>$dato_precio_procedimiento,'dato_precio'=>$dato_precio]);
      } else {
        return response()->json(['success' => false]);
      }
    } catch (\Exception $e) {
        Log::error('Error al insertar productos de recetas fisicas en trama productos: ' . $e->getTraceAsString());
        return response()->json(['success' => false, 'error' => 'Ocurrió un error al procesar la solicitud.']);
    }
  }

  public function all_auditoria_empaquetado($id_user = 0)
  {
    $model_usuario = new User;
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
    if ($ruta == "listar_auditoria_hospi_ajax");
    $ruta = "all_auditoria";
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
  
    $model_auditoria = new \App\Models\Auditoria;

    $reglas_1 = $model_auditoria->codigpaquete($id_user);
    
    return view('frontend.auditoria.vista_codigo_paquete', compact('reglas_1'));
  }

  public function ver_detalle_codigo_paquete($id)
  {
    $model_usuario = new User;
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
    if ($ruta == "listar_auditoria_hospi_ajax");
    $ruta = "all_auditoria";
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
  
    $model_auditoria = new \App\Models\Trama_interoperabilidad();

    $reglas_1 = $model_auditoria->BuscarEstadoTramaInteroperabilidad($id);
    $enviar_masivo_1 = json_decode($reglas_1, true);
    $mostrar_1 = $enviar_masivo_1['paquete']['0'];
    $mostrar_2 = $enviar_masivo_1['paquete']['0']['prestaciones'];
    return view('frontend.auditoria.detalle_vista_codigo_paquete', compact('mostrar_1','mostrar_2'));
  }

  public function all_auditoria_observados($id_user = 0)
  {
    $model_usuario = new User;
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
    if ($ruta == "listar_auditoria_hospi_ajax");
    $ruta = "all_auditoria";
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
  
    $model_auditoria = new \App\Models\Auditoria;

    $reglas_1 = $model_auditoria->codigpaquete($id_user);
    foreach($reglas_1 as $d){
      $model_trama= new \App\Models\Trama_interoperabilidad();
      if($d->observaciones !== null){
        $dato = $model_trama->BuscarEstadoTramaInteroperabilidad($d->observaciones);
        $enviar_masivo_1 = json_decode($dato, true);
        $mostrar_2 = $enviar_masivo_1['paquete']['0']['prestaciones'];
          foreach($mostrar_2 as $ef){
            if($ef['estado_fups'] > 3){
                $numero = $ef['v_numero_fups'];
                $busca_id = "SELECT * FROM trama_prestacion where numero_correlativo = '$numero' and observaciones = '$d->observaciones' and id_estado <> '0'";
                $busca_id = DB::select($busca_id);
                $busca_id = $busca_id[0];
                if($busca_id->id_estado < 3){
                  $trama_pres = trama_prestacion::find($busca_id->id);
                  $trama_pres->id_estado =  $numero = $ef['estado_fups'];
                  $trama_pres->save();
                }
            }
          }
      }
    }
   
    $mstrar = "SELECT * FROM trama_prestacion WHERE id_estado = '5'";
    $mstrar = DB::select($mstrar);
   // dd($mstrar);
    return view('frontend.auditoria.vista_prestaciones_observadas', compact('reglas_1'));
  }

  public function modificar_auditoria($numero_correlativo)
  {
      $model_auditoria = new \App\Models\Auditoria;
      $wssaludpol = new \App\Ws\Saludpol;
      $prestacion = $model_auditoria->getPrestacionById_Auditoria($numero_correlativo);
      $fecha_atencion = $prestacion->fecha_atencion_fups;
      $dni_pac = $prestacion->paciente_nro_doc_identidad;
      //dd($dni_pac);
      $consulta_ws = $wssaludpol->BuscarTitularFamiliar(1,$dni_pac,$fecha_atencion);
      //dd($consulta_ws);
      $estado_asegurado = $consulta_ws->estado;
      $estado_situacion = $consulta_ws->situacion;
      $estado_caducidad = $consulta_ws->caducidad;
      $año_caducidad = substr($estado_caducidad, 0, 4);
      $mes_caducidad = substr($estado_caducidad, 4, 2);
      $dia_caducidad = substr($estado_caducidad, 6, 2);
      $dni = DB::table('tipo_documento_identidad')
              ->select('tipo_documento_identidad.*')
              ->where('estado', '1')
              ->where('id', $prestacion->tipo_doc_identidad)
              ->first();
      $nombre_docu = $dni->descripcion;
      $dni_m = DB::table('tipo_documento_identidad')
      ->select('tipo_documento_identidad.*')
      ->where('estado', '1')
      ->where('id', $prestacion->id_tipodoc_ident)
      ->first();
      $nombre_docu_m = $dni_m->descripcion;
      $upss = DB::table('upsses')
            ->select('upsses.*')
            ->where('estado', '1')
            ->where('codigo', $prestacion->id_upss_fups)
            ->first();
      $nombre_upss = $upss->descripcion_upss;
      $tipo_covertura = DB::table('tipo_atenciones')
                        ->select('tipo_atenciones.*')
                        ->where('estado', '2')
                        ->where('cod_saludpol', $prestacion->id_tipo_cobert)
                        ->first();
      $nombre_cobertura = $tipo_covertura->nombre;   
      $estado = DB::table('trama_estado')
      ->select('trama_estado.*')
      ->where('id_estado', $prestacion->id_estado)
      ->first();
      $nombre_estado = $estado->descripcion;
      $detalle_prestacion = DB::table('prestaciones')
                            ->select('prestaciones.*')
                            ->where('id',$prestacion->id_prestacion)
                            ->first();
      $id_cups = DB::table('cups_pnp')
                            ->select('cups_pnp.*')
                            ->where('id', $prestacion->id_cups)
                            ->first();

 if (is_null($id_cups)) {
   $nombre_cups = '-';
} else {
 $nombre_cups = $id_cups->codigo_ipress.'-'.$id_cups->año.'-'.$id_cups->intramural_extramural.'-'.$id_cups->no_referidos_referidos.'-'.$id_cups->tipo_atencion.'-'.$id_cups->id_ext;
}
 $diagnosticos_prestacion = $model_auditoria->GetPrestacionDiagnosticoAuditoria($numero_correlativo);
 $procedimientos_trama = $model_auditoria->getProcedimientosByAuditoria($numero_correlativo);
 $procedimientos_tramalab = $model_auditoria->getProcedimientosByAuditoria_labo($numero_correlativo);
 $procedimientos_tramaima = $model_auditoria->getProcedimientosByAuditoria_imag($numero_correlativo);
 $productos_trama = $model_auditoria->getProductosByAuditoria($numero_correlativo);
 $diagnosticos_insertar = DB::table('diagnosticos')
 ->select('codigo', 'nombre') 
 ->where('estado', 1)
 ->get();

 $procedimientos_insertar = DB::table('procedimientos')
 ->select('codigo', 'descripcion','tipo_procedimiento','t_nivel3') 
 ->where('estado', 1)
 ->get();

 $productos_insertar = DB::table('productos')
 ->select('codigo', 'nombre','precio_trama') 
 ->where('estado', 1)
 ->get();
 return view('frontend.auditoria.modificar_auditoria',
 [
    'prestacion' => $prestacion,
    'diagnosticos_prestacion' => $diagnosticos_prestacion,
    'procedimientos_tramalab' => $procedimientos_tramalab,
    'procedimientos_tramaima' => $procedimientos_tramaima,
    'procedimientos_insertar' => $procedimientos_insertar,
    'diagnosticos_insertar' => $diagnosticos_insertar,
    'productos_insertar' => $productos_insertar,
    'nombre_upss'=>$nombre_upss,
    'detalle_prestacion' => $detalle_prestacion,
    'nombre_docu'=>$nombre_docu,
    'nombre_docu_m'=>$nombre_docu_m,
    'nombre_cobertura'=>$nombre_cobertura,
    'nombre_cups'=>$nombre_cups,
    'nombre_estado'=>$nombre_estado,
    'productos_trama'=>$productos_trama,
    'procedimientos_trama' => $procedimientos_trama,
    'estado_asegurado' => $estado_asegurado,
    'estado_situacion' => $estado_situacion,
    'año_caducidad' => $año_caducidad,
    'mes_caducidad' => $mes_caducidad,
    'dia_caducidad' => $dia_caducidad
]);
  }
  public function guardar_diagnostico_auditoria(Request $request){
    
    $id_user = Auth::user()->id;
    $date = new Carbon();
    $data = $request->input('data');
    $total = count($data['codigo_cie10']); 
    $trama_prestacion = DB::table('trama_prestacion')
    ->select('trama_prestacion.*')
    ->where('id', $data['id_trama'][0])
    ->first();
          //dd($data);
    for ($i = 0; $i < $total; $i++) {
      if($data['id_trama'][$i] == "0"){
        $diagnosticos_insertar = DB::table('diagnosticos')
        ->select('id') 
        ->where('codigo', $data['codigo_cie10'][$i])
        ->first();
        $model_trama_diag = new \App\Models\trama_diagnosticos;
        $model_trama_diag->renipress = $trama_prestacion->codigo_renipress;
        $model_trama_diag->numero_lote_fups = $trama_prestacion->numero_lote;
        $model_trama_diag->numero_fups= $trama_prestacion->numero_correlativo;
        $model_trama_diag->id_cie10  = $data['codigo_cie10'][$i];
        $model_trama_diag->id_tipo_diag = $data['tipo_diagnostico'][$i];
        $model_trama_diag->tipo_diag = $data['tipo_atencion'][$i];
        $model_trama_diag->id_identificador_diag = $data['id_identificador_diag'][$i];
        $model_trama_diag->id_trama_prestacion = $trama_prestacion->id;
        $model_trama_diag->id_diagnostico = $diagnosticos_insertar->id;
        $model_trama_diag->created_us = $id_user;
        $model_trama_diag->created_at = $date;
        $model_trama_diag->estado = 1;
        $model_trama_diag->actualizado = 1;
        $model_trama_diag->save();       
      }else{
        if($data['eliminado_diag'][$i] == "1"){
          $trama_diagnosticos = \App\Models\trama_diagnosticos::find($data['id_trama_diagnostico'][$i]);
          $trama_diagnosticos->estado = 0;
          $trama_diagnosticos->actualizado = 0;
          $trama_diagnosticos->historico = "ELIMINADO";
          $trama_diagnosticos->save();
        }else{
          $trama_diagnosticos = \App\Models\trama_diagnosticos::find($data['id_trama_diagnostico'][$i]);
          $trama_diagnosticos->estado = 0;
          $trama_diagnosticos->actualizado = 0;
          $trama_diagnosticos->save();
          $diagnosticos_insertar = DB::table('diagnosticos')
          ->select('id') 
          ->where('codigo', $trama_diagnosticos->id_cie10)
          ->first();
          $model_trama_diag = new \App\Models\trama_diagnosticos;
          $model_trama_diag->renipress = $trama_diagnosticos->renipress;
          $model_trama_diag->numero_lote_fups = $trama_diagnosticos->numero_lote_fups;
          $model_trama_diag->numero_fups = $trama_diagnosticos->numero_fups;
          $model_trama_diag->id_cie10 = $trama_diagnosticos->id_cie10;
          $model_trama_diag->id_tipo_diag = $data['tipo_diagnostico'][$i];
          $model_trama_diag->tipo_diag = $data['tipo_atencion'][$i];
          $model_trama_diag->id_identificador_diag = $data['id_identificador_diag'][$i];
          $model_trama_diag->id_trama_prestacion = $trama_prestacion->id;
          $model_trama_diag->id_diagnostico = $diagnosticos_insertar->id;
          $model_trama_diag->created_us = $id_user;
          $model_trama_diag->created_at = $date;
          $model_trama_diag->estado = 1;
          $model_trama_diag->actualizado = 1;
          $model_trama_diag->historico = $trama_diagnosticos->id;
          $model_trama_diag->save();
        }
      }      
    }
    return response()->json(['success' => true]);
  }
  
  public function guardar_procedimiento_auditoria(Request $request){
    //dd($request);
    $id_user = Auth::user()->id;
    $date = new Carbon();
    $data = $request->input('data');
    $total = count($data['diagnostico_relacionado']); 
    $trama_prestacion = DB::table('trama_prestacion')
    ->select('trama_prestacion.*')
    ->where('id', $data['id_trama'][0])
    ->first();
  
    for ($i = 0; $i < $total; $i++) {
      if($data['id_trama'][$i] == "0"){
        $tipoTexto = strtoupper(trim($data['tipo_procedimiento'][$i]));
switch ($tipoTexto) {
    case 'MÉDICO':
        $tipoProc = 1;
        break;
    case 'LABORATORIO':
        $tipoProc = 2;
        break;
    case 'IMAGENOLOGÍA':
    case 'IMAGENOLOGIA': // En caso venga sin tilde
        $tipoProc = 3;
        break;
    default:
        $tipoProc = 0; // O null si lo prefieres
        break;
}

        $model_trama_diag = new \App\Models\trama_procedimientos;
        $model_trama_diag->renipress = $trama_prestacion->codigo_renipress;
        $model_trama_diag->numero_lote_fups = $trama_prestacion->numero_lote;
        $model_trama_diag->numero_fups= $trama_prestacion->numero_correlativo;
        $model_trama_diag->id_cie10  = $data['diagnostico_relacionado'][$i];
        $model_trama_diag->id_proced = $data['codigo_procedimiento'][$i];
        $model_trama_diag->cantidad_indicada = $data['cantidad_indicada'][$i];
        $model_trama_diag->cantidad_ejecutada = $data['cantidad_indicada'][$i];
        $model_trama_diag->precio =  $data['precio_procedimiento'][$i];
        $model_trama_diag->id_trama_prestacion =  $trama_prestacion->id;
        $model_trama_diag->tipo_procedimiento =  $tipoProc;
        $model_trama_diag->valorizacion =  $data['precio_procedimiento'][$i] * $data['cantidad_indicada'][$i];
        $model_trama_diag->created_us = $id_user;
        $model_trama_diag->created_at = $date;
        $model_trama_diag->estado = 1;
        $model_trama_diag->actualizado = 1;
        $model_trama_diag->save();       
      }else{
        if($data['eliminado_proc'][$i] == "1"){
          $trama_diagnosticos = \App\Models\trama_procedimientos::find($data['id_procedimiento'][$i]);
          $trama_diagnosticos->estado = 0;
          $trama_diagnosticos->actualizado = 0;
          $trama_diagnosticos->historico = "ELIMINADO";
          $trama_diagnosticos->save();
        }else{
          
          $trama_diagnosticos = \App\Models\trama_procedimientos::find($data['id_procedimiento'][$i]);
          $trama_diagnosticos->estado = 0;
          $trama_diagnosticos->actualizado = 0;
          $trama_diagnosticos->save();
          $model_trama_diag = new \App\Models\trama_procedimientos;
          $model_trama_diag->renipress = $trama_diagnosticos->renipress;
          $model_trama_diag->numero_lote_fups = $trama_diagnosticos->numero_lote_fups;
          $model_trama_diag->numero_fups = $trama_diagnosticos->numero_fups;
          $model_trama_diag->id_cie10 = $data['diagnostico_relacionado'][$i];
          $model_trama_diag->id_proced = $trama_diagnosticos->id_proced;
          $model_trama_diag->cantidad_indicada = $data['cantidad_indicada'][$i];
          $model_trama_diag->cantidad_ejecutada = $data['cantidad_indicada'][$i];
          $model_trama_diag->precio =  $data['precio_procedimiento'][$i];
          $model_trama_diag->id_trama_prestacion =  $trama_prestacion->id;
          $model_trama_diag->tipo_procedimiento =  $trama_diagnosticos->tipo_procedimiento;
          $model_trama_diag->valorizacion =  $data['precio_procedimiento'][$i] * $data['cantidad_indicada'][$i];
          $model_trama_diag->created_us = $id_user;
          $model_trama_diag->created_at = $date;
          $model_trama_diag->estado = 1;
          $model_trama_diag->actualizado = 1;
          $model_trama_diag->historico = $trama_diagnosticos->id;
          $model_trama_diag->save();
        }
      }      
    }
   return response()->json(['success' => true]);
  }
  
  public function guardar_producto_auditoria(Request $request){
    $id_user = Auth::user()->id;
    $date = new Carbon();
    $data = $request->input('data');

    $total = count($data['id_prod_med']); 
    $trama_prestacion = DB::table('trama_prestacion')
    ->select('trama_prestacion.*')
    ->where('codigo_renipress', $data['renipress'][0])
    ->where('numero_lote', $data['num_lote'][0])
    ->where('numero_correlativo', $data['num_correlativo'][0])
    ->first();

    for ($i = 0; $i < $total; $i++) {
      if($data['id_trama'][$i] == "0"){

        $model_trama_diag = new \App\Models\trama_productos;
 $model_trama_diag->renipress = $trama_prestacion->codigo_renipress;
 $model_trama_diag->numero_lote_fups = $trama_prestacion->numero_lote;
 $model_trama_diag->numero_fups = $trama_prestacion->numero_correlativo;
 $model_trama_diag->id_cie10 = $data['id_cie10'][$i]; 
 $model_trama_diag->id_prod_med = $data['id_prod_med'][$i]; 
 $model_trama_diag->cantidad_prescrita = $data['cantidad_prescrita'][$i]; 
 $model_trama_diag->cantidad_entregada = $data['cantidad_entregada'][$i]; 
 $model_trama_diag->precio = $data['precio'][$i]; 
 $model_trama_diag->id_trama_prestacion = $trama_prestacion->id; 
 $model_trama_diag->valorizacion = $data['precio'][$i] * $data['cantidad_entregada'][$i]; 
 $model_trama_diag->created_us = $id_user; 
 $model_trama_diag->created_at = $date;
 $model_trama_diag->estado = 1;
 $model_trama_diag->actualizado = 1; 
 $model_trama_diag->save();     
      }else{
        if($data['eliminado'][$i] == "1"){
      
          $trama_diagnosticos = \App\Models\trama_productos::find($data['id_producto'][$i]);
          $trama_diagnosticos->estado = 0;
          $trama_diagnosticos->actualizado = 0;
          $trama_diagnosticos->historico = "ELIMINADO";
          $trama_diagnosticos->save();
        }else{
        
          $trama_diagnosticos = \App\Models\trama_productos::find($data['id_producto'][$i]);
          $trama_diagnosticos->estado = 0;
          $trama_diagnosticos->actualizado = 0;
          $trama_diagnosticos->save();
          $model_trama_diag = new \App\Models\trama_productos;
          $model_trama_diag->renipress = $trama_diagnosticos->renipress;
          $model_trama_diag->numero_lote_fups = $trama_diagnosticos->numero_lote_fups;
          $model_trama_diag->numero_fups = $trama_diagnosticos->numero_fups;
          $model_trama_diag->id_cie10 = $data['id_cie10'][$i];
          $model_trama_diag->id_prod_med = $trama_diagnosticos->id_prod_med;
          $model_trama_diag->cantidad_prescrita = $data['cantidad_prescrita'][$i];
          $model_trama_diag->cantidad_entregada = $data['cantidad_entregada'][$i];
          $model_trama_diag->precio =  $data['precio'][$i];
          $model_trama_diag->id_trama_prestacion =  $trama_prestacion->id;
          $model_trama_diag->valorizacion =  $data['precio'][$i] * $data['cantidad_entregada'][$i];
          $model_trama_diag->created_us = $id_user;
          $model_trama_diag->created_at = $date;
          $model_trama_diag->estado = 1;
          $model_trama_diag->actualizado = 1;
          $model_trama_diag->historico = $trama_diagnosticos->id;
          $model_trama_diag->save();
        }
      }      
    }
   return response()->json(['success' => true]);
  }

  public function guardar_observacion_auditoria(Request $request){
    $observacion = $request->input('observacion');  
    $id_trama = $request->input('id_trama');       
    $id_user = $request->input('id_user'); 
    $date = new Carbon();

    $trama_prestacion = \App\Models\trama_prestacion::find($id_trama);
    $trama_prestacion->comentario = $observacion;

    $trama_prestacion->save();

  }

  public function modificar_validacion_auditoria($id_trama){
      
    $id_user = Auth::user()->id; 
    $date = new Carbon();

    $trama_prestacion = \App\Models\trama_prestacion::find($id_trama);
    $trama_prestacion->actualizacon_trama = '2';
    $trama_prestacion->updated_as = $id_user;
    $trama_prestacion->updated_at = $date;
    $trama_prestacion->save();
    return redirect("/modificar_auditoria/$id_trama");
  }

  public function ficha_auditoria_emergencia($id_trama){
    
    $id_user = Auth::user()->id; 
    $date = new Carbon();
    $trama_prestacion = DB::table('trama_prestacion')
    ->select('trama_prestacion.*')
    ->where('id', $id_trama)
    ->first();
    $trama_diagnosticos = DB::table('trama_diagnosticos')
    ->select('trama_diagnosticos.*')
    ->where('id_trama_prestacion', $id_trama)
    ->where('id_tipo_diag', 2)
    ->first();
  
    $procedimientos = "SELECT tp.id,tp.id_proced FROM trama_procedimientos tp where tp.id_trama_prestacion = '$id_trama'AND (tp.estado != '0' OR tp.estado IS NULL)";
    $procedimientos = DB::select($procedimientos);
    $codigos_especiales = [
      '99246', '99284', '99201', '99215', '99211',
      '99203', '99243', '99248', '99285', '99244',
      '99202', '99306', '94799', '01999', '97139',
      '99199', '84999', '32999', '66999'
    ];
    foreach ($procedimientos as $proce){
      if (in_array($proce->id_proced, $codigos_especiales)) {       
        $trama = \App\Models\trama_procedimientos::find($proce->id);
        $trama->updated_us = $id_user;
        $trama->updated_at = $date;
        $trama->estado = '0';
        $trama->save();
      }
    }
    
    $procedimientos_insertar = "SELECT p.* FROM procedimientos p where p.estado = '1'and codigo in ('99206','96365','94760','15859','99234','51701','94640')";
    $procedimientos_insertar = DB::select($procedimientos_insertar);
    foreach ($procedimientos_insertar as $proce_inser){
    $model_trama_diag = new \App\Models\trama_procedimientos;
    $model_trama_diag->renipress = $trama_prestacion->codigo_renipress;
    $model_trama_diag->numero_lote_fups = $trama_prestacion->numero_lote;
    $model_trama_diag->numero_fups= $trama_prestacion->numero_correlativo;
    $model_trama_diag->id_cie10  = $trama_diagnosticos->id_cie10;
    $model_trama_diag->id_proced = $proce_inser->codigo;
    $model_trama_diag->cantidad_indicada = 1;
    $model_trama_diag->cantidad_ejecutada = 1;
    $model_trama_diag->precio =  $proce_inser->t_nivel3;
    $model_trama_diag->id_trama_prestacion =  $trama_prestacion->id;
    $model_trama_diag->tipo_procedimiento =  1;
    $model_trama_diag->valorizacion = $proce_inser->t_nivel3;
    $model_trama_diag->created_us = $id_user;
    $model_trama_diag->created_at = $date;
    $model_trama_diag->estado = 1;
    $model_trama_diag->actualizado = 1;
    $model_trama_diag->save(); 
    }   

    return redirect("/modificar_auditoria/$id_trama");
  }

  public function ficha_auditoria_consulta_externa($id_trama){
    
    $id_user = Auth::user()->id; 
    $date = new Carbon();
    $trama_prestacion = DB::table('trama_prestacion')
    ->select('trama_prestacion.*')
    ->where('id', $id_trama)
    ->first();
    $trama_diagnosticos = DB::table('trama_diagnosticos')
    ->select('trama_diagnosticos.*')
    ->where('id_trama_prestacion', $id_trama)
    ->where('id_tipo_diag', 2)
    ->first();
  
    $procedimientos = "SELECT tp.id,tp.id_proced FROM trama_procedimientos tp where tp.id_trama_prestacion = '$id_trama'AND (tp.estado != '0' OR tp.estado IS NULL)";
    $procedimientos = DB::select($procedimientos);
    $codigos_especiales = [
      '99246', '99284', '99201', '99215', '99211',
      '99203', '99243', '99248', '99285', '99244',
      '99202', '99306', '94799', '01999', '97139',
      '99199', '84999', '32999', '66999'
    ];
    foreach ($procedimientos as $proce){
      if (in_array($proce->id_proced, $codigos_especiales)) {       
        $trama = \App\Models\trama_procedimientos::find($proce->id);
        $trama->updated_us = $id_user;
        $trama->updated_at = $date;
        $trama->estado = '0';
        $trama->save();
      }
    }
    
    $procedimientos_insertar = "SELECT p.* FROM procedimientos p where p.estado = '1'and codigo in ('99206','96365','94760','15859','99234','51701','94640')";
    $procedimientos_insertar = DB::select($procedimientos_insertar);
    foreach ($procedimientos_insertar as $proce_inser){
    $model_trama_diag = new \App\Models\trama_procedimientos;
    $model_trama_diag->renipress = $trama_prestacion->codigo_renipress;
    $model_trama_diag->numero_lote_fups = $trama_prestacion->numero_lote;
    $model_trama_diag->numero_fups= $trama_prestacion->numero_correlativo;
    $model_trama_diag->id_cie10  = $trama_diagnosticos->id_cie10;
    $model_trama_diag->id_proced = $proce_inser->codigo;
    $model_trama_diag->cantidad_indicada = 1;
    $model_trama_diag->cantidad_ejecutada = 1;
    $model_trama_diag->precio =  $proce_inser->t_nivel3;
    $model_trama_diag->id_trama_prestacion =  $trama_prestacion->id;
    $model_trama_diag->tipo_procedimiento =  1;
    $model_trama_diag->valorizacion = $proce_inser->t_nivel3;
    $model_trama_diag->created_us = $id_user;
    $model_trama_diag->created_at = $date;
    $model_trama_diag->estado = 1;
    $model_trama_diag->actualizado = 1;
    $model_trama_diag->save(); 
    }   

    return redirect("/modificar_auditoria/$id_trama");
  }

  public function ficha_auditoria_hospitalizacion($id_trama){
    
    $id_user = Auth::user()->id; 
    $date = new Carbon();
    $trama_prestacion = DB::table('trama_prestacion')
    ->select('trama_prestacion.*')
    ->where('id', $id_trama)
    ->first();
    $trama_diagnosticos = DB::table('trama_diagnosticos')
    ->select('trama_diagnosticos.*')
    ->where('id_trama_prestacion', $id_trama)
    ->where('id_tipo_diag', 2)
    ->first();
  
    $procedimientos = "SELECT tp.id,tp.id_proced FROM trama_procedimientos tp where tp.id_trama_prestacion = '$id_trama'AND (tp.estado != '0' OR tp.estado IS NULL)";
    $procedimientos = DB::select($procedimientos);
    $codigos_especiales = [
      '99246', '99284', '99201', '99215', '99211',
      '99203', '99243', '99248', '99285', '99244',
      '99202', '99306', '94799', '01999', '97139',
      '99199', '84999', '32999', '66999'
    ];
    foreach ($procedimientos as $proce){
      if (in_array($proce->id_proced, $codigos_especiales)) {       
        $trama = \App\Models\trama_procedimientos::find($proce->id);
        $trama->updated_us = $id_user;
        $trama->updated_at = $date;
        $trama->estado = '0';
        $trama->save();
      }
    }
    
    $procedimientos_insertar = "SELECT p.* FROM procedimientos p where p.estado = '1'and codigo in ('99206','96365','94760','15859','99234','51701','94640')";
    $procedimientos_insertar = DB::select($procedimientos_insertar);
    foreach ($procedimientos_insertar as $proce_inser){
    $model_trama_diag = new \App\Models\trama_procedimientos;
    $model_trama_diag->renipress = $trama_prestacion->codigo_renipress;
    $model_trama_diag->numero_lote_fups = $trama_prestacion->numero_lote;
    $model_trama_diag->numero_fups= $trama_prestacion->numero_correlativo;
    $model_trama_diag->id_cie10  = $trama_diagnosticos->id_cie10;
    $model_trama_diag->id_proced = $proce_inser->codigo;
    $model_trama_diag->cantidad_indicada = 1;
    $model_trama_diag->cantidad_ejecutada = 1;
    $model_trama_diag->precio =  $proce_inser->t_nivel3;
    $model_trama_diag->id_trama_prestacion =  $trama_prestacion->id;
    $model_trama_diag->tipo_procedimiento =  1;
    $model_trama_diag->valorizacion = $proce_inser->t_nivel3;
    $model_trama_diag->created_us = $id_user;
    $model_trama_diag->created_at = $date;
    $model_trama_diag->estado = 1;
    $model_trama_diag->actualizado = 1;
    $model_trama_diag->save(); 
    }   

    return redirect("/modificar_auditoria/$id_trama");
  }

  public function all_cpms_ficha($id_trama = null){
      
    $procedimientos = "SELECT p.*, u.name as nombre_usuario, u.paterno as paterno_usuario, u.materno as materno_usuario                
                from procedimientos_ficha_auditoria p
                inner join users u on p.created_us = u.id 
                ";
    $procedimientos = DB::select($procedimientos);
    $referencias = [];
    return view('frontend.auditoria.cpms_fichas_auditoria',
  [
    'procedimientos' => $procedimientos,
    'referencias' => $referencias
    
  ]);
  }

  function validarTramasMasivas(Request $request, ValidarTramasMasivasUseCase $useCase) 
  {
    try {
      return $useCase->execute($request);
    } catch (SigesapolException $e) {
      return response()->json([
        "success" => false,
        "message" => $e->getMessage()
      ], $e->getStatus());
    } catch (Exception $e) {
      return response()->json([
        "success" => false,
        "message" => "Error inesperado en el servidor."
      ], 500);
    } 
  }
}