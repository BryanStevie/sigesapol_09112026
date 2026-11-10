<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Farmacia;
use App\Models\Tm3Equipamiento;
use App\Models\Procedimiento;
use App\Models\Establecimiento;
use App\Models\Subseccion;
use App\Models\Seccion;
use Carbon\Carbon;
use App\Models\Receta_vale;
use App\Models\Cita;
use App\Models\Prestaciones;
use App\Models\Consultorio;
use App\Models\TipoAtencione;
use SoapClient;
use Session;
use DB;
use App;
use App\Models\Tm5Infraestructura;
use App\Models\Tm6ServiciosBasicos;
use Auth;
use Illuminate\Support\Facades\Route;

class ProcedimientoController extends Controller
{
  function __construct()
    {
        $this->middleware('auth'); 
    } 
    
  public function update_procedimiento(Request $request)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    $procedimiento = Procedimiento::where('id', '=', $request->id)->first();
    $procedimiento->codigo = $request->codigo;
    $procedimiento->descripcion = $request->descripcion;
    $procedimiento->genero = $request->genero;
    $procedimiento->t_nivel1 = $request->t_nivel1;
    $procedimiento->t_nivel2 = $request->t_nivel2;
    $procedimiento->t_nivel3 = $request->t_nivel3;
    if (!is_null($request->t_nivel1) || !is_null($request->t_nivel2) || !is_null($request->t_nivel3)) {
      if ($request->t_nivel1 == "")
        $procedimiento->t_nivel1 = 0;
      else
        $procedimiento->t_nivel1 = $request->t_nivel1;

      if ($request->t_nivel2 == "")
        $procedimiento->t_nivel2 = 0;
      else
        $procedimiento->t_nivel2 = $request->t_nivel2;

      if ($request->t_nivel3 == "")
        $procedimiento->t_nivel3 = 0;
      else
        $procedimiento->t_nivel3 = $request->t_nivel3;
    }

    $procedimiento->convenio = $request->convenio;
    $procedimiento->estado = 1;

    $model_seccion = new Seccion;
    $secciones = $model_seccion->BuscaSeccion($request->seccion);

    $model_subseccion = new Subseccion;
    $subsecciones = $model_subseccion->BuscaSubseccion($request->subseccion);

    /************************************************************************************/
    $procedimiento->codigo_grupo = $request->grupo;
    if ($request->grupo == 1) {
      $procedimiento->nombre_grupo = 'Servicios médicos y estomatológicos';
    } else {
      $procedimiento->nombre_grupo = 'Servicios de salud colectiva';
    }

    $procedimiento->codigo_seccion = $secciones->codigo;
    $procedimiento->seccion = $secciones->nombre;

    $procedimiento->codigo_subseccion = $subsecciones->codigo;
    $procedimiento->subseccion = $subsecciones->nombre;
    $procedimiento->save();
    $id_procedimiento = $procedimiento->id;

    /*$procedimiento_servicios=DB::table('procedimiento_servicios') 
            ->select('procedimiento_servicios.*')
            ->where('procedimiento_servicios.id_procedimiento','=',$request->id)
            ->where('procedimiento_servicios.estado','=',1)
            ->get();
    if (count($procedimiento_servicios) > 0) {
      DB::table('procedimiento_servicios')
            ->where('procedimiento_servicios.id_procedimiento','=',$request->id)
            ->delete();
      foreach ($request->id_servicio as $key => $value) {
        DB::table('procedimiento_servicios')->insert([
            'id_procedimiento' => $request->id,
            'id_servicio' => $value,
            'created_us' => auth()->user()->id,
            'created_at' => Carbon::now()
        ]);
      }
    } else {
      foreach ($request->id_servicio as $key => $value) {
        DB::table('procedimiento_servicios')->insert([
            'id_procedimiento' => $request->id,
            'id_servicio' => $value,
            'created_us' => auth()->user()->id,
            'created_at' => Carbon::now()
        ]);
      }
    }
    */
    Session::flash('flash_message', 'Actualización exitosa');
    return redirect('/listar_procedimientos');
  }

  public function editar_procedimiento($id)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $id_user = Auth::user()->id;
    $procedimientos = DB::table('procedimientos')->where('procedimientos.id', '=', $id)
      ->select('procedimientos.*')->first();

    $model_seccion = new Seccion;
    $secciones = $model_seccion->ShowSeccion($procedimientos->codigo_grupo);

    $model_subseccion = new Subseccion;
    $subsecciones = $model_subseccion->ShowSubseccionCodigo($procedimientos->codigo_seccion);

    $model_consultorio = new \App\Models\Consultorio;
    $servicios = $model_consultorio->GetAllPorIpress(auth()->user()->id_establecimiento);

    $model_procedimiento = new Procedimiento();
    $servicios_elegidos = $model_procedimiento->getServicioPorIdProcedimiento($id);

    return view('frontend.procedimientos.editar_procedimiento', compact('procedimientos', 'secciones', 'subsecciones','servicios','servicios_elegidos'));
  }


  public function formulario_procedimiento()
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $model_consultorio = new \App\Models\Consultorio;
    $servicios = $model_consultorio->GetAllPorIpress(auth()->user()->id_establecimiento);

    return view('frontend.procedimientos.create_procedimiento',compact('servicios'));
  }

  public function index($id_user = 0)
  {

    ini_set('memory_limit', '1280M');
    ini_set('max_execution_time', 300); // 5 minutes

    if (!Auth::check()) :
      return redirect('/auth/login');
    else:
      if(Auth::user()->actualizo_passw==0):
        return redirect('auth/password/change');
      endif;
    endif;

    $id_user = Auth::user()->id;
    $modelprocedimiento = new Procedimiento();
    $procedimientos = $modelprocedimiento->getProcedimientos(10, 3);
    $ruta = Route::getCurrentRoute()->getPath();
    if (count($ruta = explode("/", $ruta)) > 1);
    $ruta = $ruta[0];
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
    $permiso = $menu[0];

    if ($permiso->lectura == "")
      return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

    return view('frontend.procedimientos.all', ['procedimientos' => $procedimientos, 'permiso' => $permiso]);
    //return view('frontend.procedimientos.all_procedimientos', ['procedimientos' => $procedimientos, 'permiso' => $permiso]);
  }

  public function create($id_user)
  {
  }

  public function store(Request $request)
  {

    $modelprocedimientos = new Procedimiento();
    $procedimientos_registrado = $modelprocedimientos->procedimiento_yaregistrada($request->codigo);
    if (count($procedimientos_registrado) > 0) {
      $dato = "El Procedimiento <b>" . $procedimientos_registrado->codigo . " </b> ya se encuentra registrado, en estado ";
      $dato .= $procedimientos_registrado->estado == "1" ? "<b>ACTIVO</b>" : "<b>INACTIVO</b>";
      return redirect('/listar_procedimientos/' . $request->id_user)->withInput()->withErrors(array($dato));
    } else {
      $procedimiento = new Procedimiento;
      $procedimiento->codigo = $request->codigo;
      $procedimiento->descripcion = $request->descripcion;
      $procedimiento->genero = $request->genero;

      if (!is_null($request->t_nivel1) || !is_null($request->t_nivel2) || !is_null($request->t_nivel3)) {
        if ($request->t_nivel1 == "")
          $procedimiento->t_nivel1 = 0;
        else
          $procedimiento->t_nivel1 = $request->t_nivel1;

        if ($request->t_nivel2 == "")
          $procedimiento->t_nivel2 = 0;
        else
          $procedimiento->t_nivel2 = $request->t_nivel2;

        if ($request->t_nivel3 == "")
          $procedimiento->t_nivel3 = 0;
        else
          $procedimiento->t_nivel3 = $request->t_nivel3;
      }

      $procedimiento->convenio = $request->convenio;
      $procedimiento->estado = 1;

      $model_seccion = new Seccion;
      $secciones = $model_seccion->BuscaSeccion($request->seccion);

      $model_subseccion = new Subseccion;
      $subsecciones = $model_subseccion->BuscaSubseccion($request->subseccion);

      /************************************************************************************/
      $procedimiento->codigo_grupo = $request->grupo;
      if ($request->grupo == 1) {
        $procedimiento->nombre_grupo = 'Servicios médicos y estomatológicos';
      } else {
        $procedimiento->nombre_grupo = 'Servicios de salud colectiva';
      }

      $procedimiento->codigo_seccion = $secciones->codigo;
      $procedimiento->seccion = $secciones->nombre;

      $procedimiento->codigo_subseccion = $subsecciones->codigo;
      $procedimiento->subseccion = $subsecciones->nombre;
      $procedimiento->save();
      $id_procedimiento = $procedimiento->id;
      if ( isset($request->id_servicio) ) {
        foreach ($request->id_servicio as $key => $value) {
          DB::table('procedimiento_servicios')->insert([
              'id_procedimiento' => $procedimiento->id,
              'id_servicio' => $value,
              'created_us' => auth()->user()->id,
              'created_at' => Carbon::now()
          ]);
        }
      }
      
      Session::flash('flash_message', 'Registro exitoso');
      return redirect('/listar_procedimientos/');
    }
  }

  public function show($id)
  {
    //
  }

  public function update(Request $request, $id)
  {
    //
  }

  public function destroy($id)
  {
    //
  }

  public function desactivar($id, $estado)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    if ($estado == "1") {
      $update = Procedimiento::where('id', '=', $id)->first();
      $update->estado = 3;
      $update->save();
      Session::flash('flash_message', 'Se desactivo correctamente');
    } else {
      $update = Procedimiento::where('id', '=', $id)->first();
      $update->estado = 1;
      $update->save();
      Session::flash('flash_message', 'Se activo correctamente');
    }
  }

  public function cargarseccion($grupo)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $model_seccion = new Seccion;
    $seccion = $model_seccion->ShowSeccion($grupo);

    return $seccion;
  }

  public function cargarsubseccion($seccion)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $model_subseccion = new Subseccion;
    $subseccion = $model_subseccion->ShowSubseccion($seccion);

    return $subseccion;
  }

  public function listar_procedimientos_ajax(Request $request)
  {
    //dd($request->all());
    $model_procedimiento = new Procedimiento();
    $draw = $request->draw;
    $start = ($request->start == 0) ? 1 : (int)$request->start / 10 + 1;
    $length = 10;
    $search_value = $request->search["value"];

    $p[] = (string)$start;
    $p[] = (string)$length;
    $p[] = $search_value;

    $data = $model_procedimiento->listar_procedimientos_ajax($p);

    $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;
    $result["draw"] = $draw;
    $result["recordsTotal"] = $iTotalDisplayRecords;
    $result["recordsFiltered"] = $iTotalDisplayRecords;
    $result["data"] = $data;

    echo json_encode($result);
  }

  public function listar_procedimientos_ajax_for_ficha(Request $request)
  {
    $model_procedimiento = new Procedimiento();
    $procedimientos = $model_procedimiento->getAllProcedimientosPaginado($request);
    return response()->json([
      'draw' => $request->draw, // El número de la petición (para DataTables)
      'recordsTotal' => $procedimientos->total(), // Total de registros sin filtros
      'recordsFiltered' => $procedimientos->total(), // Total de registros filtrados
      'data' => $procedimientos->items() // Los procedimientos paginados
    ]);
  }

  public function modal_procedimiento_paciente($id_prestacion ){
    $model_prestacion = new \App\Models\Prestaciones;
    $procedimientos = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 1);
    
    return view('frontend.epicrisis.modal_procedimientos',compact('procedimientos'));
  }

  public function modal_procedimiento_paciente_nuevo($id_prestacion){
    $model_prestacion = new \App\Models\Prestaciones;
    $procedimientos = $model_prestacion->GetProcedimientosConDiagnosticoByIdPrestacion($id_prestacion, 1);
    
    return view('frontend.epicrisis.modal_procedimientos_nuevo',compact('procedimientos'));
  }

  public function modal_procedimiento_paciente_nuevo_ho($id_prestacion){
    $model_prestacion = new \App\Models\Prestaciones;
    $procedimientos = $model_prestacion->GetProcedimientosByIdPrestacionYTipo($id_prestacion, 1);
    
    return view('frontend.epicrisis.modal_procedimientos_nuevo_ho',compact('procedimientos','id_prestacion'));
  }

  public function modal_procedimiento_paciente_nuevo_ho_evo($id_prestacion){
    $model_prestacion = new \App\Models\Prestaciones;
    $procedimientos = $model_prestacion->GetProcedimientosByIdPrestacionYTipoHoEvo($id_prestacion, 1);
    
    return view('frontend.epicrisis.modal_procedimientos_nuevo_ho_evo',compact('procedimientos','$id_prestacion'));
  }

  public function searchByTipoProcedimientoAndText(Request $request, $tipo_procedimiento)
  {
    $text = $request->query('text');
    $procedimientoModel = new Procedimiento;
    $result = $procedimientoModel->searchByTipoProcedimientoAndText($tipo_procedimiento, $text);
    return $result;
  }
  public function listFichaProcedimientos($id_user = 0)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    $modelprocedimiento = new Procedimiento();
    $procedimientos = $modelprocedimiento->getProcedimientos(10, 3);

    return view('frontend.procedimientos.ficha_procedimientos.all', compact('procedimientos'));
  }

  public function listarProcedimientos()
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    return view('frontend.procedimientos.ficha_procedimientos.all');
  }

  public function listarProcedimientosTarifario()
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    return view('frontend.procedimientos.ficha_procedimientos.all');
  }

  public function editarFichaProcedimiento()
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    
    return view('frontend.procedimientos.ficha_procedimientos.editar_ficha');
  }

  public function fichaProcedimiento($procedimiento_id)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $procedimiento = Procedimiento::GetProcedimientoById($procedimiento_id);
    $equipamientos = Tm3Equipamiento::where('ups', '=', $procedimiento->ambientes_prestacionales)->get();
    $infraestructuras = Tm5Infraestructura::where('ambiente_fisico', '=', $procedimiento->ambientes_prestacionales)->get();
    $serviciosBasicos = Tm6ServiciosBasicos::where('ambiente_fisico', '=', $procedimiento->ambientes_prestacionales)->get();
    $model_tarifario = new Tarifario();
    $res_servicios_ga = $model_tarifario->getServicioAdmsGrlsByProcedureIdMono($procedimiento_id);
    $sumaSG1 = 0;
    $sumaSG2 = 0;
    $sumaSG3 = 0;
    $sumaSA1 = 0;
    $sumaSA2 = 0;
    $sumaSA3 = 0;
    $sumaSB1 = 0;
    $sumaSB2 = 0;
    $sumaSB3 = 0;
    foreach ($res_servicios_ga as $objeto) {
      $sumaSG1 += $objeto->sg_pms_1;
      $sumaSG2 += $objeto->sg_pms_2;
      $sumaSG3 += $objeto->sg_pms_3;
      $sumaSA1 += $objeto->sa_pms_1;
      $sumaSA2 += $objeto->sa_pms_2;
      $sumaSA3 += $objeto->sa_pms_3;
    }
    foreach ($serviciosBasicos as $objeto) {
      $sumaSB1 += $objeto->total_servicios_b_1;
      $sumaSB2 += $objeto->total_servicios_b_2;
      $sumaSB3 += $objeto->total_servicios_b_3;
    }
    $redSumaSG1 = round($sumaSG1, 2);
    $redSumaSG2 = round($sumaSG2, 2);
    $redSumaSG3 = round($sumaSG3, 2);
    $redSumaSA1 = round($sumaSA1, 2);
    $redSumaSA2 = round($sumaSA2, 2);
    $redSumaSA3 = round($sumaSA3, 2);
    $redSumaSB1 = round($sumaSB1, 2);
    $redSumaSB2 = round($sumaSB2, 2);
    $redSumaSB3 = round($sumaSB3, 2);
    return view('frontend.procedimientos.ficha_procedimientos.ficha', compact('procedimiento', 'equipamientos', 'infraestructuras', 'serviciosBasicos', 'res_servicios_ga','redSumaSG1', 'redSumaSG2', 'redSumaSG3', 'redSumaSA1', 'redSumaSA2', 'redSumaSA3', 'redSumaSB1', 'redSumaSB2', 'redSumaSB3'));
  }

  public function cambiar_estado_procedimiento(Request $request)
  {
    DB::beginTransaction();

    try {
        // Encuentra el procedimiento
        $procedimiento = Procedimiento::findOrFail($request->procedimiento_id);

        // Guarda el estado actual para referencia en el rollback
        /* $estadoAnterior = $procedimiento->estado;

        if($estadoAnterior == 1) {
          // Cambia el estado del procedimiento
          $procedimiento->estado = 0;
          $procedimiento->save();
        } else {
          $procedimiento->estado = 1;
          $procedimiento->save();
        } */
        $procedimiento->estado = !$procedimiento->estado;
        $procedimiento->save();
        // Si todo está correcto, confirma la transacción
        DB::commit();

        return response()->json([
          'success' => true, 
          'message' => 'El estado fue cambiado exitosamente.',
          'nuevo_estado' => $procedimiento->estado 
        ]);
    } catch (\Exception $e) {
        // Si ocurre un error, revierte el cambio
        DB::rollBack();

        // Opcionalmente, puedes loguear el error
        \Log::error('Error al cambiar el estado: ' . $e->getMessage());

        return response()->json([
          'success' => false, 
          'message' => 'El estado no pudo ser cambiado.',
        ]);
    }
  }
}