<?php

namespace App\Http\Controllers;
use App\Helpers\Util;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Consultorio;
use App\Models\SubConsultorio;
use App\Models\SubConsultorioTurno;
use App\Models\Turno;
use App\Models\Recursos_salud_detalle;
use Session;
use DB;
use App;
use Illuminate\Support\Facades\Route;
use Auth;
use Response;
use App\Models\Producto;
use App\Models\Producto_servicio;
use App\Models\Farmacia_consultorio_tipo_atencione;
use App\Models\Farmacia;
use App\Models\User;
use App\Models\Establecimiento;
use App\Models\Consultorio_farmacia;
use App\Models\Recursos_salud;
use App\Models\TipoAtencione;
use App\Models\Upss;
use DateTime;

class RecursosSaludController extends Controller
{

  function __construct()
    {
        $this->middleware('auth'); 
    } 
    

public function index()
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    /*$model_farma = new Farmacia;
    $farma = $model_farma->GetFarmaByUser($id_user);
    $id_farmacia = $farma->id;*/
///$ruta = Route::getCurrentRoute()->getPath();
   //     if (count($ruta = explode("/", $ruta)) > 1)
     //       ;$ruta = $ruta[0];
    //$menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
      //  $tipo = $menu[0];

    $model = new User;
    $establecimientotot = $model->GetById($id_user);
    $existe = $establecimientotot->flagestablecimientos;

    $model_establecimiento = new Establecimiento();
    $establecimientos = $model_establecimiento->ShowAll();

    $model_recursos_salud = new Recursos_salud();

    if ($existe == "1") {
      $consultorio_servicio = $model_recursos_salud->ShowEstablecimeintosAll();
    } else {
      $consultorio_servicio = $model_recursos_salud->ShowEstablecimeintosAllPorId($establecimientotot->id_establecimiento);
    }

    return view('frontend.recursos_salud.all_recursos_salud', 
    compact('establecimientos', 'id_servicio', 'consultorio_servicio', 'consultorio', 'existe', 'tipo'));
  }


  public function agregar_ipress_recurso(Request $request)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    $model_recursos_salud = new Recursos_salud;
    $model_recursos_salud->id_ipress = $request->id_establecimiento_recurso;
    $model_recursos_salud->estado = 1;
    $model_recursos_salud->id_user_created = $id_user;

    $existe = $model_recursos_salud->ExisteIpressRecursoSalud($request->id_establecimiento_recurso);
    

    if ($existe == "1") {
      return redirect('/listar_recursos_salud')
        ->withInput()
        ->withErrors(array('No se ha podido registrar. Ya se encuentra registrado o está desactivado'));
    } else {
      $model_recursos_salud->save();
      Session::flash('flash_message', 'Se agrego correctamente');
      return redirect('/listar_recursos_salud');
    }
  }


  public function desactivar_ipress_recurso($id)
  {
    
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    $update = Recursos_salud::where('id', '=', $id)->first();
    $msj="";
    if ($update->estado == 1) {  
      $update->estado = 3;
      $msj= 'Se desactivo correctamente';
    } else {
      $update->estado = 1;
      $msj='Se activo correctamente';
    }

    $update->id_user_update = $id_user;
    $update->save();
    Session::flash('flash_message', $msj);

  }

  
  public function desactivar_ipress_recurso_detalle($id)
  {
    
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    $update = Recursos_salud_detalle::where('id', '=', $id)->first();
    $msj="";
    if ($update->estado == 1) {  
      $update->estado = 3;
      $msj= 'Se desactivo correctamente';
    } else {
      $update->estado = 1;
      $msj='Se activo correctamente';
    }

    
    $update->save();
    Session::flash('flash_message', $msj);

  }


  
  public function add_ipress_recurso_detalle($id_ipress_recuso_salud,$id_ipress)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    
    $model_recurso_salud_detalle = new Recursos_salud_detalle();
    $recurso_salud_detalle=$model_recurso_salud_detalle->ShowAll($id_ipress);

    $id_user = Auth::user()->id;
    $model_establecimiento= new Establecimiento();
    $establecimiento=$model_establecimiento->GetById($id_ipress);
  
    return view('frontend.recursos_salud.registrar_recursos_salud', 
    compact('establecimiento', 'recurso_salud_detalle','id_ipress','id_ipress_recuso_salud'));
  }


  public function filtrarPorFecha(Request $request)
{
    if (!Auth::check()) :
        return redirect('/auth/login');
    endif;

    $id_ipress_recuso_salud = $request->input('id_ipress_recuso_salud');
    $id_ipress = $request->input('id_ipress');
    $fecha_desde = $request->input('fecha_desde');
    $fecha_hasta = $request->input('fecha_hasta');

    $model = new Recursos_salud_detalle();
    $recurso_salud_detalle = $model->ShowAll($id_ipress, $fecha_desde, $fecha_hasta);

    $model_establecimiento = new Establecimiento();
    $establecimiento = $model_establecimiento->GetById($id_ipress);

    return view('frontend.recursos_salud.registrar_recursos_salud', 
        compact('establecimiento', 'recurso_salud_detalle', 'id_ipress', 'id_ipress_recuso_salud'));
}

  
  public function create($id_ipress_recurso_salud,$id_ipress)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    //$consultorio = new Consultorio();
    //$consultorio_divisiones = $consultorio->getDivisiones();
    //$model= new Establecimiento();
    //$establecimientos=$model->ShowAll();
    
    $model_recurso_salud_detalle = new Recursos_salud_detalle();
    $recurso_salud_detalle=$model_recurso_salud_detalle->ShowAll($id_ipress);

    $id_user = Auth::user()->id;
    $model_establecimiento= new Establecimiento();
    $establecimiento=$model_establecimiento->GetById($id_ipress);
    
    $ugipress=$model_establecimiento->GetById(78);

  $modo="create";
  
    return view('frontend.recursos_salud.create', 
  
     compact('establecimiento','modo' ,'recurso_salud_detalle','id_ipress','ugipress','id_ipress_recurso_salud'));
  }

public function store(Request $request)
{
  print_r($request->all());
    try {
     
        $recurso = new Recursos_salud_detalle();
// Campos obligatorios
        $recurso->periodo = $request->input('periodo');
        $recurso->id_ugipress = $request->input('id_ugipress');
        $recurso->id_ipress = $request->input('id_ipress');
        $recurso->consultorio_fisico = $request->input('consultorio_fisico');
        $recurso->consultorio_funcional = $request->input('consultorio_funcional');
        
        
        $recurso->camas_hospitalarias = $request->input('camas_hospitalarias') ?: 0;
        $recurso->sillas_dentales = $request->input('sillones_dentales') ?: 0;
        $recurso->piezas_mano = $request->input('piezas_mano') ?: 0;
        $recurso->nro_ambulancias = $request->input('ambulancias') ?: 0;
        $recurso->nro_salas_quirurgicas = $request->input('salas_quirurgicas') ?: 0;
        
        
        $recurso->id_ipress_recurso_salud = $request->input('id_ipress_recurso_salud')  ;
        $recurso->id_user_created = Auth::id();
        $recurso->estado = 1;
        $recurso->save();

        Session::flash('flash_message', 'Registro exitoso');
            return redirect('/listar_recursos_salud/');

    } catch (\Exception $e) {
        return back()->withInput()->withErrors(['error' => 'Error al guardar: ' . $e->getMessage()]);
    }
}

public function edit($id)
{
    $recurso = Recursos_salud_detalle::findOrFail($id);
    $establecimiento = Establecimiento::find($recurso->id_ipress);
    $ugipress = Establecimiento::find($recurso->id_ugipress);

    return view('frontend.recursos_salud.create', [
        'modo' => 'editar',
        'recurso' => $recurso,
        'establecimiento' => [$establecimiento],
        'ugipress' => [$ugipress],
        'id_ipress' => $recurso->id_ipress,
        'id_ipress_recurso_salud' => $recurso->id_ipress_recurso_salud,
    ]);
}

public function show($id)
{
  
 $recurso = Recursos_salud_detalle::findOrFail($id);    
    $establecimiento = Establecimiento::find($recurso->id_ipress);
    $ugipress = Establecimiento::find($recurso->id_ugipress);

    return view('frontend.recursos_salud.create', [
        'modo' => 'ver',
        'recurso' => $recurso,
        'establecimiento' => [$establecimiento],
        'ugipress' => [$ugipress],
        'id_ipress' => $recurso->id_ipress,
        'id_ipress_recurso_salud' => $recurso->id_ipress_recurso_salud,
    ]);
}

public function update(Request $request, $id)
{
    try {
        $recurso = Recursos_salud_detalle::findOrFail($id);
        $recurso->periodo = $request->input('periodo');
        $recurso->consultorio_fisico = $request->input('consultorio_fisico');
        $recurso->consultorio_funcional = $request->input('consultorio_funcional');
        $recurso->camas_hospitalarias = $request->input('camas_hospitalarias') ?: 0;
        $recurso->sillas_dentales = $request->input('sillones_dentales') ?: 0;
        $recurso->piezas_mano = $request->input('piezas_mano') ?: 0;
        $recurso->nro_ambulancias = $request->input('ambulancias') ?: 0;
        $recurso->nro_salas_quirurgicas = $request->input('salas_quirurgicas') ?: 0;
        $recurso->save();

        Session::flash('flash_message', 'Actualización exitosa');
        return redirect('/listar_recursos_salud/');
    } catch (\Exception $e) {
        return back()->withInput()->withErrors(['error' => 'Error al actualizar: ' . $e->getMessage()]);
    }
}



}