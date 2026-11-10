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
use App\Models\Procedimiento;
use App\Models\TipoAtencione;
use App\Models\Upss;
use Carbon\Carbon;
use DateTime;

class ConsultorioController extends Controller
{

  function __construct()
  {
    $this->middleware('auth');
  }

  public function api_departamentos($id = 0)
  {
    $consultorio = new Consultorio();
    $consultorio_departamentos = $consultorio->getDepartamentosPorDivision((int)$id);
    return Response::json($consultorio_departamentos);
    //echo json_encode($consultorio_departamentos);
  }

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
      if (Auth::user()->actualizo_passw == 0):
        return redirect('auth/password/change');
      endif;
    endif;

    $id_user = Auth::user()->id;
    $model_farma = new Farmacia;
    $farma = $model_farma->GetFarmaByUser($id_user);
    $id_farmacia = $farma->id;

    $model = new User;
    $establecimientotot = $model->GetById($id_user);
    $existe = $establecimientotot->flagestablecimientos;

    $model = new Consultorio;
    if ($existe == "1") {
      $consultorios = $model->ShowConsultoriosAll();
    } else {
      $consultorios = $model->ShowConsultorios($id_farmacia);
    }

    $ruta = Route::getCurrentRoute()->getPath();
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
    $permiso = $menu[0];
    if ($permiso->lectura == "")
      return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
    return view('frontend.consultorios.all', ['consultorios' => $consultorios, 'existe' => $existe, 'permiso' => $permiso]);
  }

  public function create()
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $consultorio = new Consultorio();
    $consultorio_divisiones = $consultorio->getDivisiones();
    //$model= new Establecimiento();
    //$establecimientos=$model->ShowAll();
    return view('frontend.consultorios.create', [
      'consultorio_divisiones' => $consultorio_divisiones
      //'consultorio_departamentos' => $consultorio_departamentos
    ]);
  }

  public function eliminar($id)
  {
    $update = Consultorio::where('id', '=', $id)->first();
    $update->estado = 3;
    $update->save();
    Session::flash('flash_message', 'Se elimino correctamente');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $consultorio = new Consultorio;
    $existe_all = $consultorio->ExisteConsultorioAll('', $request->nombre);
    if ($existe_all->cant <> "0") {
      return redirect('/create_servicio')
        ->withInput()
        ->withErrors(array('No se ha podido registrar, Servicio ' . $request->nombre . ' ya existe'));
    } else {
      $consultorio->id_consultorio_division = intval($request->consultorio_division);
      $consultorio->id_consultorio_departamento = intval($request->consultorio_departamento);
      $consultorio->nombre = $request->nombre;
      $consultorio->abreviatura = $request->abreviatura;
      $consultorio->tipo = $request->tipo_consultorio;
      $consultorio->estado = 1; //activo
      $consultorio->create_us = Auth::user()->id;
      $consultorio->flag_intervalo_cita = empty($request->flag_intervalo_cita) ? 0 : 1;
      $consultorio->save();
      Session::flash('flash_message', 'Registro exitoso');
      return redirect('/all_servicios');
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
  public function edit($id, $ff = "")
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $consultorio = Consultorio::findOrFail($id);
    $consultorio_divisiones = $consultorio->getDivisiones();
    $consultorio_departamentos = $consultorio->getDepartamentosPorDivision($consultorio->id_consultorio_division);
    return view('frontend.consultorios.edit', [
      'consultorio' => $consultorio,
      'consultorio_divisiones' => $consultorio_divisiones,
      'consultorio_departamentos' => $consultorio_departamentos,
      'ff' => $ff
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request)
  {
    $consultorio = new Consultorio;
    $existe_all = $consultorio->ExisteConsultorioAll($request->id, $request->nombre);
    if ($existe_all->cant <> "0") {
      return redirect('/edit_servicio/' . $request->id)
        ->withInput()
        ->withErrors(array('No se ha podido actualizar el registro, Servicio ' . $request->nombre . ' ya existe'));
    } else {
      $update = Consultorio::where('id', '=', $request->id)->first();
      $update->id_consultorio_division = intval($request->consultorio_division);
      $update->id_consultorio_departamento = intval($request->consultorio_departamento);
      $update->nombre = $request->nombre;
      $update->abreviatura = $request->abreviatura;
      $update->tipo = $request->tipo_consultorio;
      //$update->estado = 1; //activo
      $update->update_us = Auth::user()->id;
      $update->flag_intervalo_cita = empty($request->flag_intervalo_cita) ? 0 : 1;
      $update->flag_quirurgico = empty($request->flag_quirurgico) ? 0 : 1;
      $update->save();
      return redirect('/all_servicios')->withFlashSuccess("Se actualizo correctamente");
    }
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

  public function add_producto_servicio__($id_servicio, $id_farmacia)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $modelfarma = new Farmacia();
    $model_producto = new Producto();
    $model_producto_servicio = new Producto_servicio();
    $id_user = Auth::user()->id;
    $farmacias = $modelfarma->GetFarmaByUser($id_user);
    $id_farmacia = $farmacias->id;
    $nivel = $modelfarma->ObtenerNivelFarmacia($id_farmacia);
    if ($id_farmacia == 36 || $id_farmacia == 37) {
      $productos = $model_producto->ProductosFarmaciasSegunNivel(2);
    } else {
      $productos = $model_producto->ProductosFarmaciasSegunNivel($nivel->nivel);
    }


    //$producto_servicio = $model_producto_servicio->ObtenerProductoPorIdServicio($id_servicio, $id_farmacia);
    $producto_servicio = $model_producto_servicio->listarProductoPorIdServicioIdFarmacia($id_farmacia, $id_servicio);
    $model_consultorio = new Consultorio();
    $consultorio = $model_consultorio->GetNombreById($id_servicio);
    $model_farma_servicio = new \App\Models\Consultorio_farmacia;
    $existe_farmacia = $model_farma_servicio->ExisteFarmaciaServicio($id_farmacia, $id_servicio);

    if ($existe_farmacia) {
      //return view('frontend.consultorios.registrar_productos_servicio', compact('productos', 'id_servicio', 'producto_servicio', 'consultorio'));
      $farmacia = $model_consultorio->GetNombreFarmaciaById($id_farmacia);
      return view('frontend.consultorios.registrar_productos_servicio', compact('productos', 'id_servicio', 'producto_servicio', 'consultorio', 'id_farmacia', 'farmacia'));
    } else {
      return redirect('/all_servicios/')
        ->withInput()
        ->withErrors(array('El Centro de Atención no está activado. Primero Relacione el Centro de Atención y despues agregue productos.'));
    }
  }

  public function add_producto_servicio($id_servicio, $id_farmacia, $tipo)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $modelfarma = new Farmacia();
    $model_producto = new Producto();
    $model_producto_servicio = new Producto_servicio();
    $id_user = Auth::user()->id;
    $nivel = $modelfarma->ObtenerNivelFarmacia($id_farmacia);
    $nv = (isset($nivel->nivel) && $nivel->nivel != "") ? $nivel->nivel : 3;
    $productos = $model_producto->ProductosFarmaciasSegunNivel($nv);
    $producto_servicio = $model_producto_servicio->listarProductoPorIdServicioIdFarmacia($id_farmacia, $id_servicio);
    $model_consultorio = new Consultorio();
    $consultorio = $model_consultorio->GetNombreById($id_servicio);

    $farmacia = $model_consultorio->GetNombreFarmaciaById($id_farmacia);
    /*************TIPOS DE ANESTESIA PARA UN PORDUCTO SERVICIO******/
    $tipo_anestesia = DB::table('cqx_tipo_anestesia')
                            ->select()
                            ->where('estado',1)
                            ->get();

    $producto_servicio = collect($producto_servicio)->map(function($p){
        $tipos = DB::table('cqx_anestesia_producto_servicios as aps')
            ->join('cqx_tipo_anestesia as ta','ta.id','=','aps.id_tipo_anestesia')
            ->where('aps.id_producto_servicio',$p->id)
            ->where('aps.estado',1)
            ->select('ta.id','ta.descripcion')
            ->get();

        $arr = [];

        foreach($tipos as $t){
           $arr[] = [
              'id' => $t->id,
              'descripcion' => $t->descripcion
          ];
        }

        $p->tipos_anestesia = $arr;

        return $p;
    });
    /*************TIPOS DE ANESTESIA PARA UN PRODUCTO SERVICIO******/

    return view('frontend.consultorios.registrar_productos_servicio', compact('productos', 'id_servicio', 'producto_servicio', 'consultorio', 'id_farmacia', 'farmacia', 'tipo','tipo_anestesia'));
  }

  public function obtener_consultorio_parent($parent)
  {

    $model_consultorio = new Consultorio();
    $consultorio = $model_consultorio->ShowConsultoriosByParent($parent);
    return $consultorio;
  }

  public function add_tipo_atencion_servicio($id_servicio, $id_farmacia)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $modelfarma = new Farmacia();
    $model_producto = new Producto();
    $model_producto_servicio = new Producto_servicio();
    $id_user = Auth::user()->id;
    $nivel = $modelfarma->ObtenerNivelFarmacia($id_farmacia);
    $nv = (isset($nivel->nivel) && $nivel->nivel != "") ? $nivel->nivel : 3;
    $TipoAtencionFarmacia = $modelfarma->GetFarmaciaTipoAtencionByIdFarmacia($id_farmacia);
    $TipoAtencionServicioFarmacia = $modelfarma->listarTipoAtencionPorIdServicioIdFarmacia($id_farmacia, $id_servicio);
    $model_consultorio = new Consultorio();
    $consultorio = $model_consultorio->GetNombreById($id_servicio);
    $farmacia = $model_consultorio->GetNombreFarmaciaById($id_farmacia);

    return view('frontend.consultorios.registrar_tipo_atencion_servicio', compact('TipoAtencionFarmacia', 'id_servicio', 'TipoAtencionServicioFarmacia', 'consultorio', 'id_farmacia', 'farmacia'));
  }


  public function add_centro_atencion_servicio($id_servicio, $tipo)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    $model_farma = new Farmacia;
    $farma = $model_farma->GetFarmaByUser($id_user);
    $id_farmacia = $farma->id;

    $model = new User;
    $establecimientotot = $model->GetById($id_user);
    $existe = $establecimientotot->flagestablecimientos;

    $model_consultorio = new Consultorio();
    $consultorio = $model_consultorio->GetNombreById($id_servicio);

    $model_establecimiento = new Establecimiento();
    $establecimientos = $model_establecimiento->ShowAll();

    $model_consultorio_farmacia = new Consultorio_farmacia();

    if ($existe == "1") {
      $consultorio_servicio = $model_consultorio_farmacia->ObtenerFarmaciaPorIdServicio($id_servicio);
    } else {
      $consultorio_servicio = $model_consultorio_farmacia->ObtenerFarmacias($id_servicio, $id_farmacia);
    }

    return view('frontend.consultorios.registrar_establecimiento_servicio', compact('establecimientos', 'id_servicio', 'consultorio_servicio', 'consultorio', 'existe', 'tipo'));
  }

  public function add_consultorios_servicio($id_servicio)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    $model_farma = new Farmacia;
    $farma = $model_farma->GetFarmaByUser($id_user);
    $id_farmacia = $farma->id;

    $model = new User;
    $establecimientotot = $model->GetById($id_user);
    $existe = $establecimientotot->flagestablecimientos;

    $model_consultorio = new Consultorio();
    $consultorio = $model_consultorio->GetNombreById($id_servicio);

    $model_establecimiento = new Establecimiento();
    $establecimientos = $model_establecimiento->ShowAll();

    $model_tipoAtencion = new TipoAtencione();
    $tipo_atenciones = $model_tipoAtencion->ShowTipoAtencionAll();

    $model_subconsultorio = new SubConsultorio();

    if ($existe == "1") {
      $subconsultorio_servicio = $model_subconsultorio->ObtenerSubConsultorioPorIdServicio(0, $id_servicio);
    } else {
      $subconsultorio_servicio = $model_subconsultorio->ObtenerSubConsultorioPorIdServicio($farma->id_establecimiento, $id_servicio);
    }

    $ids_consultorios_procedimiento = array();
    if (\Schema::hasColumn('sub_consultorios', 'flag_procedimiento')) {
      $ids_consultorios_procedimiento = array_map('intval', DB::table('sub_consultorios')
        ->where('id_consultorio', $id_servicio)->where('flag_procedimiento', 1)->lists('id'));
    }

    return view('frontend.consultorios.registrar_consultorio_servicio', compact('establecimientos', 'id_servicio', 'subconsultorio_servicio', 'consultorio', 'existe', 'establecimientotot', 'tipo_atenciones', 'ids_consultorios_procedimiento'));
  }

  public function add_procprogramados_solicitud(Request $request, $id_servicio)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    // Procedimientos programados: solo IPRESS de nivel 3, establecimiento 76 o administrador general
    if (Auth::user()->flagestablecimientos != 1 && !$this->ppEstablecimientoHabilitado(Auth::user()->id_establecimiento)) {
      return redirect('/all_servicios')->withErrors(['Procedimientos programados no está habilitado para su IPRESS.']);
    }

    $model_consultorio = new Consultorio();
    $consultorio = $model_consultorio->GetNombreById($id_servicio);

    $model_establecimiento = new Establecimiento();
    $establecimientos = $model_establecimiento->ShowAll();
    if (Auth::user()->flagestablecimientos != 1) {
      $establecimientos = array_values(array_filter($establecimientos, function ($e) {
        return intval($e->id) === intval(Auth::user()->id_establecimiento);
      }));
    }

    $id_establecimiento = intval($request->input('id_establecimiento'));
    $procedimientos_servicio = array();
    $nivel = null;
    if ($this->ppIpressPermitida($id_establecimiento)) {
      $procedimientos_servicio = DB::select("select ps.id, ps.orden, ps.id_estado_reg, ps.requiere_cita, ps.requiere_informe, ps.descripcion_corta, p.codigo, p.descripcion
        from procedimientos_servicios ps
        inner join procedimientos p on p.id = ps.id_procedimiento
        where ps.id_servicio = ? and ps.id_establecimiento = ?
        order by ps.id_estado_reg desc, ps.orden asc, p.codigo asc", [$id_servicio, $id_establecimiento]);
      $nivel = $this->ppNivelIpress($id_establecimiento);
    } else {
      $id_establecimiento = 0;
    }

    $tipos_cita = array(0 => 'No necesita cita', 1 => 'Necesita cita', 2 => 'Necesita cita de reevaluación');

    return view('frontend.consultorios.registrar_procprogramados_solicitud', compact('id_servicio', 'consultorio', 'procedimientos_servicio', 'nivel', 'tipos_cita', 'establecimientos', 'id_establecimiento'));
  }

  private function ppIpressPermitida($id_establecimiento)
  {
    $id_establecimiento = intval($id_establecimiento);
    if ($id_establecimiento <= 0) {
      return false;
    }
    if (Auth::user()->flagestablecimientos == 1) {
      return DB::table('establecimientos')->where('id', $id_establecimiento)->where('id_estado_reg', 1)->exists();
    }
    return $id_establecimiento === intval(Auth::user()->id_establecimiento) && $this->ppEstablecimientoHabilitado($id_establecimiento);
  }

  private function ppNivelIpress($id_establecimiento)
  {
    return intval(DB::table('establecimientos')->where('id', intval($id_establecimiento))->value('nivel'));
  }

  private function ppConfiguracionEditable($id){
    $registro = DB::table('procedimientos_servicios')->where('id', intval($id))->first();
    return ($registro && $this->ppIpressPermitida($registro->id_establecimiento)) ? $registro : null;
  }

  public function pp_buscar_procedimiento_convenio(Request $request)
  {
    $keyword = trim($request->input('keyword'));
    $id_establecimiento = intval($request->input('id_establecimiento'));
    if (!$this->ppIpressPermitida($id_establecimiento)) {
      return response()->json([]);
    }
    $nivel = $this->ppNivelIpress($id_establecimiento);
    $columnas_nivel = [1 => 't_nivel1', 2 => 't_nivel2', 3 => 't_nivel3'];
    if (!isset($columnas_nivel[$nivel])) {
      return response()->json([]);
    }
    $limite = $keyword == '' ? 10 : 20;
    $data = DB::select("select id, codigo, descripcion
      from procedimientos
      where translate(lower(codigo || ' - ' || descripcion), 'áéíóúäëïöü', 'aeiouaeiou') like translate(lower(?), 'áéíóúäëïöü', 'aeiouaeiou')
        and convenio = 1 and estado = 1 and coalesce(" . $columnas_nivel[$nivel] . ", 0) > 0
      order by codigo asc
      limit " . $limite, ['%' . $keyword . '%']);
    $resultado = array();
    foreach ($data as $item) {
      $resultado[] = [
        'label' => $item->codigo . ' - ' . $item->descripcion,
        'codigo' => $item->codigo,
        'id' => $item->id,
      ];
    }
    return response()->json($resultado);
  }

  public function pp_agregar_procedimiento_servicio(Request $request)
  {
    try {
      $id_servicio = intval($request->id_servicio);
      $id_procedimiento = intval($request->id_procedimiento);
      $id_establecimiento = intval($request->id_establecimiento);
      $orden = trim($request->orden) == '' ? null : intval($request->orden);
      $requiere_cita = intval($request->requiere_cita);
      $descripcion_corta = trim($request->descripcion_corta);

      if ($id_servicio <= 0 || $id_procedimiento <= 0) {
        return response()->json(['success' => false, 'message' => 'Faltan datos del servicio o del procedimiento.']);
      }
      if (mb_strlen($descripcion_corta, 'UTF-8') > 100) {
        return response()->json(['success' => false, 'message' => 'La Descripción corta debe tener hasta 100 caracteres.']);
      }
      if ($descripcion_corta == '') $descripcion_corta = null;
      if (!$this->ppIpressPermitida($id_establecimiento)) {
        return response()->json(['success' => false, 'message' => 'La IPRESS seleccionada no es válida.']);
      }
      if (!in_array($requiere_cita, array(0, 1, 2), true)) {
        return response()->json(['success' => false, 'message' => 'El tipo de cita no es válido.']);
      }
      $columnas_nivel = [1 => 't_nivel1', 2 => 't_nivel2', 3 => 't_nivel3'];
      $nivel_ipress = $this->ppNivelIpress($id_establecimiento);
      $permitido = isset($columnas_nivel[$nivel_ipress]) && DB::table('procedimientos')
        ->where('id', $id_procedimiento)
        ->whereRaw('coalesce(' . $columnas_nivel[$nivel_ipress] . ', 0) > 0')
        ->exists();
      if (!$permitido) {
        return response()->json(['success' => false, 'message' => 'El procedimiento no corresponde al nivel de la IPRESS.']);
      }
      $existe = DB::table('procedimientos_servicios')
        ->where('id_servicio', $id_servicio)
        ->where('id_procedimiento', $id_procedimiento)
        ->where('id_establecimiento', $id_establecimiento)
        ->exists();
      if ($existe) {
        return response()->json(['success' => false, 'message' => 'El procedimiento ya está agregado a este servicio en la IPRESS.']);
      }
      DB::table('procedimientos_servicios')->insert([
        'id_servicio' => $id_servicio,
        'id_procedimiento' => $id_procedimiento,
        'id_establecimiento' => $id_establecimiento,
        'descripcion_corta' => $descripcion_corta,
        'orden' => $orden,
        'requiere_cita' => $requiere_cita,
        'requiere_informe' => intval($request->requiere_informe) == 1 ? 1 : 0,
        'id_estado_reg' => 1,
        'created_at' => Carbon::now(),
        'created_us' => Auth::user()->id,
      ]);
      return response()->json(['success' => true, 'message' => 'Procedimiento agregado correctamente.']);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'No se pudo agregar el procedimiento.']);
    }
  }

  public function pp_editar_procedimiento_servicio(Request $request)
  {
    try {
      $descripcion_corta = trim($request->descripcion_corta);
      $requiere_cita = intval($request->requiere_cita);
      if (mb_strlen($descripcion_corta, 'UTF-8') > 100) {
        return response()->json(['success' => false, 'message' => 'La Descripción corta debe tener hasta 100 caracteres.']);
      }
      if ($descripcion_corta == '') $descripcion_corta = null;
      if (!preg_match('/^\d{1,4}$/', trim($request->orden))) {
        return response()->json(['success' => false, 'message' => 'Ingrese un N° de Orden válido (solo números).']);
      }
      if (!in_array($requiere_cita, array(0, 1, 2), true)) {
        return response()->json(['success' => false, 'message' => 'El tipo de cita no es válido.']);
      }
      $registro = $this->ppConfiguracionEditable($request->id);
      if (!$registro) {
        return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
      }
      DB::table('procedimientos_servicios')->where('id', $registro->id)->update([
        'descripcion_corta' => $descripcion_corta,
        'orden' => intval($request->orden),
        'requiere_cita' => $requiere_cita,
        'updated_at' => Carbon::now(),
        'updated_us' => Auth::user()->id,
      ]);
      return response()->json(['success' => true]);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'No se pudo actualizar el procedimiento.']);
    }
  }

  public function pp_requiere_informe_procedimiento_servicio(Request $request)
  {
    try {
      $registro = $this->ppConfiguracionEditable($request->id);
      if (!$registro) {
        return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
      }
      DB::table('procedimientos_servicios')->where('id', $registro->id)->update([
        'requiere_informe' => intval($request->requiere_informe) == 1 ? 1 : 0,
        'updated_at' => Carbon::now(),
        'updated_us' => Auth::user()->id,
      ]);
      return response()->json(['success' => true]);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'No se pudo actualizar si necesita informe.']);
    }
  }

  public function pp_estado_procedimiento_servicio(Request $request)
  {
    try {
      $registro = $this->ppConfiguracionEditable($request->id);
      if (!$registro) {
        return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
      }
      $nuevo_estado = $registro->id_estado_reg == 1 ? 0 : 1;
      DB::table('procedimientos_servicios')->where('id', $registro->id)->update([
        'id_estado_reg' => $nuevo_estado,
        'updated_at' => Carbon::now(),
        'updated_us' => Auth::user()->id,
      ]);
      return response()->json(['success' => true, 'nuevo_estado' => $nuevo_estado]);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'No se pudo actualizar el estado.']);
    }
  }

  public function add_procprogramados_turnos($id_sub_consultorio, $id_servicio)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $model_subconsultorio = new SubConsultorio();
    $subconsultorio = $model_subconsultorio->GetNombreById($id_sub_consultorio);

    $model_consultorio = new Consultorio();
    $consultorio = $model_consultorio->GetNombreById($id_servicio);

    $model_pct = new \App\Models\ProcedimientoConsultorioTurno();
    $turnos_dia = $model_pct->ObtenerTurnosPorSubConsultorio($id_sub_consultorio);

    $rangos = $this->ppRangosTurno();
    $turnos_usados = array();
    foreach ($turnos_dia as $td) {
      $turnos_usados[intval($td->id_turno)] = true;
    }
    $model_turno = new Turno();
    $turno = array();
    foreach ($model_turno->ShowAll() as $t) {
      $id_t = intval($t->id);
      if (isset($rangos[$id_t])) {
        $t->hora_ini = $rangos[$id_t][1];
        $t->hora_fin = $rangos[$id_t][2];
        $t->rango = $rangos[$id_t][1] . ' - ' . $rangos[$id_t][2];
        $t->permitido = 1;
        $turno[] = $t;
      } else if (isset($turnos_usados[$id_t])) {
        $t->rango = '';
        $t->permitido = 0;
        $turno[] = $t;
      }
    }

    return view('frontend.consultorios.registrar_procprogramados_turnos', compact('id_sub_consultorio', 'id_servicio', 'subconsultorio', 'consultorio', 'turno', 'turnos_dia'));
  }

  private function ppNormalizarHora($hora)
  {
    $hora = trim($hora);
    if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $hora)) return false;
    return date("g:i a", strtotime('2000-01-01 ' . $hora));
  }

  private function ppNormalizarFecha($fecha)
  {
    $fecha = trim($fecha);
    if ($fecha == '') return null;
    $d = DateTime::createFromFormat('d-m-Y', $fecha);
    if (!$d || $d->format('d-m-Y') !== $fecha) return false;
    return $d->format('Y-m-d');
  }

  private function ppRangosTurno()
  {
    return array(
      2 => array('MAÑANA', '07:31', '13:30', '00:00'),
      3 => array('TARDE', '13:31', '19:30', '13:31'),
      4 => array('NOCHE', '19:31', '07:30', '19:31'),
    );
  }

  private function ppMinutos($hora)
  {
    $t = strtotime('2000-01-01 ' . $hora);
    return intval(date('H', $t)) * 60 + intval(date('i', $t));
  }

  private function ppValidarRangoTurno($id_turno, $hora_ini, $hora_fin)
  {
    $rangos = $this->ppRangosTurno();
    if (!isset($rangos[intval($id_turno)])) {
      return 'El turno no está permitido para procedimientos programados (solo Mañana, Tarde y Noche).';
    }
    $r = $rangos[intval($id_turno)];
    $ini = $this->ppMinutos($hora_ini);
    $fin = $this->ppMinutos($hora_fin);
    $r_ini = $this->ppMinutos($r[1]);
    $r_fin = $this->ppMinutos($r[2]);
    if ($r_fin > $r_ini) {
      $dentro = $ini >= $this->ppMinutos($r[3]) && $fin <= $r_fin;
    } else {
      $dentro = $ini >= $r_ini && $fin <= 1439;
    }
    if (!$dentro) {
      return 'El horario debe estar dentro del turno ' . $r[0] . ' (' . $r[1] . ' - ' . $r[2] . ').';
    }
    return '';
  }

  private function ppValidarTurnoDia($id_sub_consultorio, $dia, $hora_ini, $hora_fin, $excluir_id = 0, $fecha_inicio = null, $fecha_fin = null, $id_turno = null)
  {
    if (!in_array($dia, \App\Models\ProcedimientoConsultorioTurno::DiasSemana(), true)) {
      return 'El día seleccionado no es válido.';
    }
    if (strtotime('2000-01-01 ' . $hora_fin) <= strtotime('2000-01-01 ' . $hora_ini)) {
      return 'La hora de fin debe ser mayor a la hora de inicio.';
    }
    if ($id_turno !== null) {
      $error_rango = $this->ppValidarRangoTurno($id_turno, $hora_ini, $hora_fin);
      if ($error_rango != '') return $error_rango;
    }
    if ($fecha_inicio && $fecha_fin && $fecha_fin < $fecha_inicio) {
      return 'La fecha final debe ser mayor o igual a la fecha de inicio.';
    }
    $model_pct = new \App\Models\ProcedimientoConsultorioTurno();
    $cruce = $model_pct->ObtenerSolapamientoDia($id_sub_consultorio, $dia, $hora_ini, $hora_fin, $excluir_id, $fecha_inicio, $fecha_fin);
    if ($cruce) {
      return 'El horario se cruza con el turno ' . $cruce->turno . ' (' . $cruce->hora_ini . ' - ' . $cruce->hora_fin . ') del ' . $cruce->dia . '.';
    }
    return '';
  }

  public function pp_guardar_turno_dia(Request $request)
  {
    try {
      $id = intval($request->id);
      $id_sub_consultorio = intval($request->id_sub_consultorio);
      $id_turno = intval($request->id_turno);
      $dia = trim($request->dia);
      $hora_ini = $this->ppNormalizarHora($request->hora_ini);
      $hora_fin = $this->ppNormalizarHora($request->hora_fin);
      $tiempo_atencion = trim($request->tiempo_atencion) == '' ? null : intval($request->tiempo_atencion);
      $fecha_inicio = $this->ppNormalizarFecha($request->fecha_inicio);
      $fecha_fin = $this->ppNormalizarFecha($request->fecha_fin);

      if ($id_sub_consultorio <= 0 || $id_turno <= 0) {
        return response()->json(['success' => false, 'message' => 'Faltan datos del consultorio o del turno.']);
      }
      if ($hora_ini === false || $hora_fin === false) {
        return response()->json(['success' => false, 'message' => 'Ingrese una hora de inicio y fin válidas.']);
      }
      if ($tiempo_atencion !== null && ($tiempo_atencion <= 0 || $tiempo_atencion > 999)) {
        return response()->json(['success' => false, 'message' => 'El tiempo de atención debe estar entre 1 y 999 minutos.']);
      }
      if ($fecha_inicio === false || $fecha_fin === false) {
        return response()->json(['success' => false, 'message' => 'Las fechas deben tener el formato dd-mm-aaaa.']);
      }

      $registro = null;
      if ($id > 0) {
        $registro = \App\Models\ProcedimientoConsultorioTurno::where('id', $id)
          ->where('id_sub_consultorio', $id_sub_consultorio)->where('id_estado_reg', '<>', 0)->first();
        if (!$registro) {
          return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
        }
        $dia = $registro->dia;
      }
      $id_turno_ef = $registro ? intval($registro->id_turno) : $id_turno;

      if ($id_turno_ef == 4 && $this->ppMinutos($hora_fin) <= $this->ppMinutos($hora_ini)) {
        return $this->ppGuardarNochePartida($registro, $id_sub_consultorio, $dia, $hora_ini, $hora_fin, $tiempo_atencion, $fecha_inicio, $fecha_fin);
      }

      $error = $this->ppValidarTurnoDia($id_sub_consultorio, $dia, $hora_ini, $hora_fin, $id, $fecha_inicio, $fecha_fin, $id_turno_ef);
      if ($error != '') {
        return response()->json(['success' => false, 'message' => $error]);
      }

      if (!$registro) {
        $registro = new \App\Models\ProcedimientoConsultorioTurno;
        $registro->id_sub_consultorio = $id_sub_consultorio;
        $registro->id_turno = $id_turno;
        $registro->dia = $dia;
        $registro->id_estado_reg = 1;
        $registro->created_us = Auth::user()->id;
      } else {
        $registro->updated_us = Auth::user()->id;
      }
      $registro->hora_ini = $hora_ini;
      $registro->hora_fin = $hora_fin;
      $registro->tiempo_atencion = $tiempo_atencion;
      $registro->fecha_inicio = $fecha_inicio;
      $registro->fecha_fin = $fecha_fin;
      $registro->save();
      return response()->json(['success' => true, 'message' => 'Horario guardado correctamente.']);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'No se pudo guardar el horario.']);
    }
  }

  private function ppGuardarNochePartida($registro, $id_sub_consultorio, $dia, $hora_ini, $hora_fin, $tiempo_atencion, $fecha_inicio, $fecha_fin)
  {
    $dias = \App\Models\ProcedimientoConsultorioTurno::DiasSemana();
    $pos = array_search($dia, $dias, true);
    if ($pos === false) {
      return response()->json(['success' => false, 'message' => 'El día seleccionado no es válido.']);
    }
    $dia_sig = $dias[($pos + 1) % count($dias)];
    $fin_1 = $this->ppNormalizarHora('23:59');
    $ini_2 = $this->ppNormalizarHora('00:00');
    $id_actual = $registro ? intval($registro->id) : 0;

    $rangos = $this->ppRangosTurno();
    if ($this->ppMinutos($hora_fin) > $this->ppMinutos($rangos[4][2])) {
      return response()->json(['success' => false, 'message' => 'El horario debe estar dentro del turno NOCHE (' . $rangos[4][1] . ' - ' . $rangos[4][2] . ').']);
    }

    $error = $this->ppValidarTurnoDia($id_sub_consultorio, $dia, $hora_ini, $fin_1, $id_actual, $fecha_inicio, $fecha_fin, 4);
    if ($error != '') {
      return response()->json(['success' => false, 'message' => $dia . ': ' . $error]);
    }
    $error = $this->ppValidarTurnoDia($id_sub_consultorio, $dia_sig, $ini_2, $hora_fin, 0, $fecha_inicio, $fecha_fin, 2);
    if ($error != '') {
      return response()->json(['success' => false, 'message' => $dia_sig . ': ' . $error]);
    }

    DB::beginTransaction();
    try {
      if (!$registro) {
        $registro = new \App\Models\ProcedimientoConsultorioTurno;
        $registro->id_sub_consultorio = $id_sub_consultorio;
        $registro->id_turno = 4;
        $registro->dia = $dia;
        $registro->id_estado_reg = 1;
        $registro->created_us = Auth::user()->id;
      } else {
        $registro->updated_us = Auth::user()->id;
      }
      $registro->hora_ini = $hora_ini;
      $registro->hora_fin = $fin_1;
      $registro->tiempo_atencion = $tiempo_atencion;
      $registro->fecha_inicio = $fecha_inicio;
      $registro->fecha_fin = $fecha_fin;
      $registro->save();

      $siguiente = new \App\Models\ProcedimientoConsultorioTurno;
      $siguiente->id_sub_consultorio = $id_sub_consultorio;
      $siguiente->id_turno = 2;
      $siguiente->dia = $dia_sig;
      $siguiente->id_estado_reg = 1;
      $siguiente->created_us = Auth::user()->id;
      $siguiente->hora_ini = $ini_2;
      $siguiente->hora_fin = $hora_fin;
      $siguiente->tiempo_atencion = $tiempo_atencion;
      $siguiente->fecha_inicio = $fecha_inicio;
      $siguiente->fecha_fin = $fecha_fin;
      $siguiente->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->json(['success' => false, 'message' => 'No se pudo guardar el horario.']);
    }

    return response()->json(['success' => true, 'message' => 'Horario guardado correctamente.']);
  }

  public function pp_estado_turno_dia(Request $request)
  {
    try {
      $registro = \App\Models\ProcedimientoConsultorioTurno::where('id', intval($request->id))
        ->where('id_estado_reg', '<>', 0)->first();
      if (!$registro) {
        return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
      }
      if ($registro->id_estado_reg == 1) {
        $registro->id_estado_reg = 3;
      } else {
        $error = $this->ppValidarTurnoDia($registro->id_sub_consultorio, $registro->dia, $registro->hora_ini, $registro->hora_fin, $registro->id, $registro->fecha_inicio, $registro->fecha_fin, intval($registro->id_turno));
        if ($error != '') {
          return response()->json(['success' => false, 'message' => 'No se puede activar: ' . $error]);
        }
        $registro->id_estado_reg = 1;
      }
      $registro->updated_us = Auth::user()->id;
      $registro->save();
      return response()->json(['success' => true, 'nuevo_estado' => $registro->id_estado_reg]);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'No se pudo actualizar el estado.']);
    }
  }

  public function pp_eliminar_turno_dia(Request $request)
  {
    try {
      $registro = \App\Models\ProcedimientoConsultorioTurno::where('id', intval($request->id))
        ->where('id_estado_reg', '<>', 0)->first();
      if (!$registro) {
        return response()->json(['success' => false, 'message' => 'Registro no encontrado.']);
      }
      $registro->id_estado_reg = 0;
      $registro->updated_us = Auth::user()->id;
      $registro->save();
      return response()->json(['success' => true, 'message' => 'Turno eliminado correctamente.']);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'No se pudo eliminar el turno.']);
    }
  }

  public function pp_copiar_turno_dias(Request $request)
  {
    $origen = \App\Models\ProcedimientoConsultorioTurno::where('id', intval($request->id))
      ->where('id_estado_reg', 1)->first();
    if (!$origen) {
      return response()->json(['success' => false, 'message' => 'Registro de origen no encontrado o desactivado.']);
    }
    $dias = is_array($request->dias) ? $request->dias : array();
    $resultado = array();

    DB::beginTransaction();
    try {
      foreach ($dias as $dia) {
        if ($dia == $origen->dia) continue;
        // Si el día ya tiene exactamente este horario no se duplica ni se avisa
        $igual = \App\Models\ProcedimientoConsultorioTurno::where('id_sub_consultorio', $origen->id_sub_consultorio)
          ->where('dia', $dia)->where('id_turno', $origen->id_turno)
          ->where('hora_ini', $origen->hora_ini)->where('hora_fin', $origen->hora_fin)
          ->where('id_estado_reg', '<>', 0)->exists();
        if ($igual) continue;
        $error = $this->ppValidarTurnoDia($origen->id_sub_consultorio, $dia, $origen->hora_ini, $origen->hora_fin, 0, $origen->fecha_inicio, $origen->fecha_fin, intval($origen->id_turno));
        if ($error != '') {
          $resultado[] = $dia . ': ' . $error;
          continue;
        }
        $nuevo = new \App\Models\ProcedimientoConsultorioTurno;
        $nuevo->id_sub_consultorio = $origen->id_sub_consultorio;
        $nuevo->id_turno = $origen->id_turno;
        $nuevo->dia = $dia;
        $nuevo->hora_ini = $origen->hora_ini;
        $nuevo->hora_fin = $origen->hora_fin;
        $nuevo->tiempo_atencion = $origen->tiempo_atencion;
        $nuevo->fecha_inicio = $origen->fecha_inicio;
        $nuevo->fecha_fin = $origen->fecha_fin;
        $nuevo->id_estado_reg = 1;
        $nuevo->created_us = Auth::user()->id;
        $nuevo->save();
      }
      DB::commit();
      // Solo se devuelven los días que no se pudieron agregar (cruce con otro horario)
      return response()->json(['success' => true, 'errores' => $resultado]);
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->json(['success' => false, 'message' => 'No se pudo copiar el horario.']);
    }
  }

  public function add_consultorio_turnos($id_sub_consultorio, $id_servicio)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    $model_farma = new Farmacia;
    $farma = $model_farma->GetFarmaByUser($id_user);
    $id_farmacia = $farma->id;

    $model = new User;
    $establecimientotot = $model->GetById($id_user);
    $existe = $establecimientotot->flagestablecimientos;

    $model_subconsultorio = new SubConsultorio();
    $subconsultorio = $model_subconsultorio->GetNombreById($id_sub_consultorio);

    $model_turno = new Turno();
    $turno = $model_turno->ShowAll();

    $model_subconsultorioturno = new SubConsultorioTurno();

    $model_consultorio = new Consultorio();
    $consultorio = $model_consultorio->GetNombreById($id_servicio);

    if ($existe == "1") {
      $subconsultorioturno = $model_subconsultorioturno->ObtenerSubConsultorioTurnoPorId($id_sub_consultorio);
    } else {
      $subconsultorioturno = $model_subconsultorioturno->ObtenerSubConsultorioTurnoPorId($id_sub_consultorio);
    }

    return view(
      'frontend.consultorios.registrar_sub_consultorio_turno',
      compact('turno', 'id_sub_consultorio', 'id_servicio', 'subconsultorioturno', 'subconsultorio', 'existe', 'consultorio')
    );
  }

  public function agregar_producto_servicio(Request $request)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    $model_servicio_productos = new Producto_servicio;
    $model_servicio_productos->id_servicio = $request->id_servicio;
    $model_servicio_productos->id_producto = $request->id_producto;
    $model_servicio_productos->id_farmacia = $request->id_farmacia;
    $model_servicio_productos->estado = 1;
    $model_servicio_productos->id_user_created = $id_user;

    $model_producto_servicio = new \App\Models\Producto_servicio;
    $existe = $model_producto_servicio->ExisteProductoServicio($request->id_producto, $request->id_servicio, $request->id_farmacia);

    if ($existe) {
      return redirect('/add_producto_servicio/' . $request->id_servicio . '/' . $request->id_farmacia . '/1')
        ->withInput()
        ->withErrors(array('No se ha podido registrar, Producto ya existe en este Servicio'));
    } else {
      $model_servicio_productos->save();
      Session::flash('flash_message', 'Se agrego correctamento el producto al Servicio');
      return redirect('/add_producto_servicio/' . $request->id_servicio . '/' . $request->id_farmacia . '/1');
    }
  }

  public function agregar_tipo_atencion_farmacia_servicio(Request $request)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    $id_farmacia = $request->id_farmacia;
    $id_consultorio = $request->id_servicio;
    $id_tipo_atencion = $request->id_tipo_atencion;
    $model = new Farmacia();

    $existe = $model->postRegistrarTipoAtencionFarmaciaServicio("e", $id_farmacia, $id_consultorio, $id_tipo_atencion, $id_user);
    if ($existe == "S") {
      return redirect('/add_tipo_atencion_servicio/' . $request->id_servicio . '/' . $request->id_farmacia)
        ->withInput()
        ->withErrors(array('No se ha podido registrar, Tipo de Atención ya existe en este Servicio'));
    } else {

      $ok = $model->postRegistrarTipoAtencionFarmaciaServicio("c", $id_farmacia, $id_consultorio, $id_tipo_atencion, $id_user);
      Session::flash('flash_message', 'Se actualizó correctamento el Tipo de Atención');
      return redirect('/add_tipo_atencion_servicio/' . $request->id_servicio . '/' . $request->id_farmacia);
    }
  }

  public function eliminar_tipo_atencion_servicio_farmacia($id)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;

    $tipo_atencion_servicio = Farmacia_consultorio_tipo_atencione::find($id);
    $id_servicio = $tipo_atencion_servicio->id_consultorio;
    $id_farmacia = $tipo_atencion_servicio->id_farmacia;
    $model = new Farmacia();
    $ok = $model->postRegistrarTipoAtencionFarmaciaServicio("d", 0, 0, $id, $id_user);
    Session::flash('flash_message', 'Se elimino correctamente el Tipo de Atención del Servicio');
    return redirect('/add_tipo_atencion_servicio/' . $id_servicio . '/' . $id_farmacia);
  }

  public function agregar_farmacia_servicio(Request $request)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    $model_farmacia_productos = new Consultorio_farmacia;
    $model_farmacia_productos->id_consultorio = $request->id_servicio;
    $model_farmacia_productos->id_farmacia = $request->id_farmacia;
    $model_farmacia_productos->estado = 1;
    $model_farmacia_productos->id_user_created = $id_user;
    //$model_farmacia_productos->save();

    $model_farma_servicio = new \App\Models\Consultorio_farmacia;
    $existe = $model_farma_servicio->ExisteFarmaciaServicio($request->id_farmacia, $request->id_servicio);
    //print_r($existe);exit();

    if ($existe) {
      return redirect('/add_centro_atencion_servicio/' . $request->id_servicio . '/' . $request->tipo)
        ->withInput()
        ->withErrors(array('No se ha podido registrar. El Centro de Atención ya existe en este Servicio o está desactivado'));
    } else {
      $model_farmacia_productos->save();
      Session::flash('flash_message', 'Se agrego correctamento el Centro de Atención al Servicio');
      return redirect('/add_centro_atencion_servicio/' . $request->id_servicio . '/' . $request->tipo);
    }
  }

  public function agregar_consultorio_servicio(Request $request)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    $model_subconsultorio = new SubConsultorio;
    $model_subconsultorio->fecha_inicio_actividades = $request->fecha_inicio;
    $model_subconsultorio->fecha_final_actividades = $request->fecha_final;
    $model_subconsultorio->id_consultorio = $request->id_servicio;
    $model_subconsultorio->id_establecimiento = $request->id_establecimiento;
    $model_subconsultorio->id_tipo_atencion_1 = $request->id_tipo_atencion;
    $model_subconsultorio->descripcion = strtoupper(trim($request->descripcion));
    $model_subconsultorio->abreviatura = $request->abreviatura;
    $model_subconsultorio->tiempo_atencion = $request->tiempo_atencion;
    $model_subconsultorio->descripcion_tv = $request->descripcion_tv;
    $model_subconsultorio->id_estado_reg = 1;
    $model_subconsultorio->created_us = $id_user;
    $model_subconsultorio->adicional = isset($request->adicional) ? 1 : 0;
    $model_subconsultorio->mopri_cqx = isset($request->mopri_cqx) ? 1 : 0;
    $model_existe = new SubConsultorio;
    $existe = $model_existe->ExisteConsultorioServicio($request->id_establecimiento, $request->id_servicio, strtoupper(trim($request->descripcion)), $request->id_tipo_atencion);

    $resulta = 0;
    if ($request->fecha_final == '') {
    } else {
      $resulta = ((strtotime($request->fecha_final)) - (strtotime($request->fecha_inicio))) / 86400;
    }

    if ($resulta < 0) {
      return redirect('/add_consultorios_servicio/' . $request->id_servicio)
        ->withInput()
        ->withErrors(array('No se ha podido registrar. la Fecha de Inicio debe ser menor a la Fecha Final'));
    } else {
      if ($existe) {
        return redirect('/add_consultorios_servicio/' . $request->id_servicio)
          ->withInput()
          ->withErrors(array('No se ha podido registrar. El Consultorio ya existe en este Servicio o está desactivado'));
      } else {
        $model_subconsultorio->save();
        Session::flash('flash_message', 'Se agrego correctamento el Consultorio al Servicio');
        return redirect('/add_consultorios_servicio/' . $request->id_servicio);
      }
    }
  }

  public function agregar_consultorio_turno(Request $request)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    $model_subconsultorioturno = new SubConsultorioTurno;
    $model_subconsultorioturno->id_sub_consultorio = $request->id_sub_consultorio;
    $model_subconsultorioturno->id_turno = $request->id_turno;
    $model_subconsultorioturno->hora_ini = date("g:i a", strtotime($request->hora_ini));
    $model_subconsultorioturno->hora_fin = date("g:i a", strtotime($request->hora_fin));
    $model_subconsultorioturno->id_estado_reg = 1;
    $model_subconsultorioturno->created_us = $id_user;
    $id_servicio = $request->id_servicio;
    $model_existe = new SubConsultorioTurno;
    $existe = $model_existe->ExisteConsultorioTurno($request->id_sub_consultorio, $request->id_turno, $request->hora_ini);


    if ($existe) {
      return redirect('/add_consultorio_turnos/' . $request->id_sub_consultorio . '/' . $id_servicio)
        ->withInput()
        ->withErrors(array('No se ha podido registrar. El Turno ya existe en este Consultorio o está desactivado'));
    } else {
      $model_subconsultorioturno->save();
      Session::flash('flash_message', 'Se agrego correctamento el Turno al Consultorio');
      return redirect('/add_consultorio_turnos/' . $request->id_sub_consultorio . '/' . $id_servicio);
    }
  }

  public function obtener_consultorio_por_id_establecimiento($id_establecimiento, $id_servicio)
  {

    $model_subconsultorio = new SubConsultorio;
    $subconsultorio = $model_subconsultorio->ObtenerSubConsultorioPorIdServicio($id_establecimiento, $id_servicio);
    return $subconsultorio;
  }

  public function eliminar_producto_en_servicio($id)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    $producto_servicio = Producto_servicio::find($id);
    $id_servicio = $producto_servicio->id_servicio;
    $id_farmacia = $producto_servicio->id_farmacia;
    $producto_servicio->estado = 0;
    $producto_servicio->id_user_update = $id_user;
    $producto_servicio->save();
    Session::flash('flash_message', 'Se elimino correctamente el producto del Servicio');
    return redirect('/add_producto_servicio/' . $id_servicio . '/' . $id_farmacia . '/1');
  }

  public function desactivar_centro_atencion_servicio($id, $estado)
  {
    //print_r($estado);

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;

    if ($estado == "1") {
      $update = Consultorio_farmacia::where('id', '=', $id)->first();
      $update->estado = 3;
      $update->id_user_update = $id_user;
      $update->save();

      Session::flash('flash_message', 'Se desactivo correctamente');
    } else {
      $update = Consultorio_farmacia::where('id', '=', $id)->first();
      $update->estado = 1;
      $update->id_user_update = $id_user;
      $update->save();
      Session::flash('flash_message', 'Se activo correctamente');
    }
  }

  public function desactivar_consultorio_servicio($id, $estado)
  {
    //print_r($estado);
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;

    if ($estado == "1") {
      $update = SubConsultorio::where('id', '=', $id)->first();
      $update->id_estado_reg = 3;
      $update->updated_us = $id_user;
      $update->save();

      Session::flash('flash_message', 'Se desactivo correctamente');
    } else {
      $update = SubConsultorio::where('id', '=', $id)->first();
      $update->id_estado_reg = 1;
      $update->updated_us = $id_user;
      $update->save();
      Session::flash('flash_message', 'Se activo correctamente');
    }
  }

  public function desactivar_consultorio_turno($id, $estado)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $id_user = Auth::user()->id;
    if ($estado == "1") {
      $update = SubConsultorioTurno::where('id', '=', $id)->first();
      $update->id_estado_reg = 3;
      $update->updated_us = $id_user;
      $update->save();
      Session::flash('flash_message', 'Se desactivo correctamente');
    } else {
      $update = SubConsultorioTurno::where('id', '=', $id)->first();
      if (!empty($update)) {
        SubConsultorioTurno::DesactivarTurnos($update->id_sub_consultorio, $update->id_turno);
      }
      $update->id_estado_reg = 1;
      $update->updated_us = $id_user;
      $update->save();
      Session::flash('flash_message', 'Se activo correctamente');
    }
    exit;
  }

  public function mostrar_consultorios($id_ipress)
  {
    $model = new Consultorio;
    $consultorios = $model->ShowAllConsultorios($id_ipress);
    return $consultorios;
  }

  public function obtener_consultorios_por_ipress($id_ipress, $id_servicio)
  {
    $model = new Consultorio;
    $consultorios = $model->ShowAConsultorio($id_ipress, $id_servicio);
    return $consultorios;
  }

  public function add_upss_servicio($id_servicio)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    $model_farma = new Farmacia;
    $farma = $model_farma->GetFarmaByUser($id_user);
    $id_farmacia = $farma->id;

    $model = new User;
    $establecimientotot = $model->GetById($id_user);
    $existe = $establecimientotot->flagestablecimientos;

    $model_consultorio = new Consultorio();
    $consultorio = $model_consultorio->GetNombreById($id_servicio);

    $model_atencione = new TipoAtencione();
    $atenciones = $model_atencione->ShowTipoAtencionAll();

    $model_upss = new Upss();
    $upss_servicio = $model_upss->ShowConsultoriosUpss($id_servicio);

    return view('frontend.consultorios.registrar_upss_servicio', compact('atenciones', 'id_servicio', 'upss_servicio', 'consultorio', 'existe'));
  }

  public function agregar_upss_servicio(Request $request)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    $model_upss = new Upss;
    $model_upss->id_consultorio = $request->id_servicio;
    $model_upss->id_tipo_atencion = $request->id_atenciones;
    $model_upss->codigo = trim($request->codigo);
    $model_upss->descripcion_upss = trim($request->descripcion_upss);
    $model_upss->estado = 1;
    $model_upss->create_us = $id_user;

    $model_existe = new Upss;

    $existe = $model_existe->ExisteCodigoUpss($request->codigo);
    //$existe_atencion = $model_existe->ExisteAtencionUpss($request->id_servicio, $request->id_atenciones);


    //if ($existe == true || $existe_atencion == true) {
    if ($existe == true) {
      return redirect('/add_upss_servicio/' . $request->id_servicio)
        ->withInput()
        ->withErrors(array('No se ha podido registrar. Ya existe el código del Upss: ' . $request->descripcion . ' o ya existe el tipo de atención en este Servicio'));
    } else {
      $model_upss->save();
      Session::flash('flash_message', 'Se agrego correctamento los datos del Upss al Servicio');
      return redirect('/add_upss_servicio/' . $request->id_servicio);
    }
  }

  public function editar_upss($id_servicio, $upss)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    $model_farma = new Farmacia;
    $farma = $model_farma->GetFarmaByUser($id_user);
    $id_farmacia = $farma->id;

    $model = new User;
    $establecimientotot = $model->GetById($id_user);
    $existe = $establecimientotot->flagestablecimientos;

    $model_consultorio = new Consultorio();
    $consultorio = $model_consultorio->GetNombreById($id_servicio);

    $model_atencione = new TipoAtencione();
    $atenciones = $model_atencione->ShowTipoAtencionAll();

    $model_upss = new Upss();
    $upss_servicio = $model_upss->ShowConsultoriosUpss($id_servicio);

    $upss_encontrado = Upss::find($upss);

    return view('frontend.consultorios.editar_upss_servicio', compact('atenciones', 'id_servicio', 'upss_servicio', 'consultorio', 'existe', 'upss_encontrado'));
  }

  public function update_upss_servicio(Request $request)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    $model_upss = Upss::find($request->id);
    $model_upss->id_consultorio = $request->id_servicio;
    $model_upss->id_tipo_atencion = $request->id_atenciones;
    $model_upss->codigo = trim($request->codigo);
    $model_upss->descripcion_upss = trim($request->descripcion_upss);
    $model_upss->update_us = $id_user;

    $model_existe = new Upss;
    $existe = $model_existe->ExisteCodigoUpss($request->codigo, $request->id);
    //$existe_atencion = $model_existe->ExisteAtencionUpss($request->id_servicio, $request->id_atenciones, $request->id);

    //if ($existe == true || $existe_atencion == true) {
    if ($existe == true) {
      return redirect('/editar_upss/' . $request->id_servicio . '/' . $request->id)
        ->withInput()
        ->withErrors(array('No se ha podido actualizar. Ya existe el código del Upss: ' . $request->codigo . ' o ya existe el tipo de atención en este Servicio'));
    } else {
      $model_upss->save();
      Session::flash('flash_message', 'Se actualizo correctamento los datos del Upss al Servicio');
      return redirect('/editar_upss/' . $request->id_servicio . '/' . $request->id);
    }
  }

  public function desactivar_upss_servicio($id, $estado)
  {
    //print_r($estado);
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;

    if ($estado == "1") {
      $update = Upss::where('id', '=', $id)->first();
      $update->estado = 3;
      $update->update_us = $id_user;
      $update->save();

      Session::flash('flash_message', 'Se desactivo correctamente');
    } else {
      $update = Upss::where('id', '=', $id)->first();
      $update->estado = 1;
      $update->update_us = $id_user;
      $update->save();
      Session::flash('flash_message', 'Se activo correctamente');
    }
  }

  public function obtener_consultorio_interconsulta($buscar, $id_establecimiento)
  {

    $model_consultorio = new \App\Models\Consultorio;
    $consultorio = $model_consultorio->getBuscarConsultorios($buscar, $id_establecimiento);
    $array = array();
    foreach ($consultorio as $leer) {
      $array[] = array(
        "value" => $leer->nombre,
        "label" => $leer->nombre,
        "id" => $leer->id,
        "has_guardia" => $leer->has_guardia
      );
    }

    echo json_encode($array);
  }

  public function edit_consultorios_servicio($id_sub_consultorio)
  {
    $subconsultorio = SubConsultorio::where('id', $id_sub_consultorio)->first();
    $subconsultorio =  json_decode(json_encode($subconsultorio['attributes']));

    $model_tipoAtencion = new TipoAtencione();
    $tipo_atenciones = $model_tipoAtencion->ShowTipoAtencionAll();

    $pp_habilitado = $this->ppEstablecimientoHabilitado($subconsultorio->id_establecimiento);

    return view('frontend.consultorios.editar_subconsultorio', compact('subconsultorio', 'tipo_atenciones', 'pp_habilitado'));
  }

  // Procedimientos programados: solo para IPRESS de nivel 3 o el establecimiento 76
  private function ppEstablecimientoHabilitado($id_establecimiento)
  {
    return Establecimiento::ppHabilitado($id_establecimiento);
  }

  public function update_consultorios_servicio(Request $request)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $inicio =  $request->fecha_inicio;
    $final = $request->fecha_final;

    if ($final != null && $inicio != null) {
      //////año
      $anofechaInicio = DateTime::createFromFormat('d-m-Y', $inicio)->format('Y');
      $anofechaFinal = DateTime::createFromFormat('d-m-Y', $final)->format('Y');
      /////mes
      $mesfechaInicio = DateTime::createFromFormat('d-m-Y', $inicio)->format('m');
      $mesfechaFinal = DateTime::createFromFormat('d-m-Y', $final)->format('m');
      /////dia
      $diafechaInicio = DateTime::createFromFormat('d-m-Y', $inicio)->format('d');
      $diafechaFinal = DateTime::createFromFormat('d-m-Y', $final)->format('d');

      if ($anofechaFinal < $anofechaInicio) {
        Session::flash('flash_message', 'La fecha inicial debe ser menor a la fecha final');
        return redirect('/edit_subconsultorios_servicio_2/' . $request->id);
      } else {
        if ($mesfechaFinal < $mesfechaInicio) {
          Session::flash('flash_message', 'La fecha inicial debe ser menor a la fecha final');
          return redirect('/edit_subconsultorios_servicio_2/' . $request->id);
        } else {
          if ($diafechaFinal < $diafechaInicio) {
            Session::flash('flash_message', 'La fecha inicial debe ser menor a la fecha final');
            return redirect('/edit_subconsultorios_servicio_2/' . $request->id);
          }
        }
      }
    }

    $subconsultorio = SubConsultorio::where('id', $request->id)->first();
    $subconsultorio->id_tipo_atencion_1 = $request->id_tipo_atencion_1;
    $subconsultorio->descripcion = $request->consultorio;
    $subconsultorio->abreviatura = $request->abreviatura;
    $subconsultorio->fecha_inicio_actividades = $inicio;
    $subconsultorio->fecha_final_actividades = $final;
    if ($request->proviene == 1):
      $subconsultorio->tiempo_atencion = $request->tiempo_atencion;
    endif;
    $subconsultorio->descripcion_tv = $request->descripcion_tv;
    $subconsultorio->adicional = isset($request->adicional) ? 1 : 0;
    $subconsultorio->mopri_cqx = isset($request->mopri_cqx) ? 1 : 0;
    if (\Schema::hasColumn('sub_consultorios', 'flag_procedimiento')) {
      // Solo IPRESS de nivel 3 o el establecimiento 76 pueden tener consultorios de procedimientos programados
      $subconsultorio->flag_procedimiento = (isset($request->flag_procedimiento) && $this->ppEstablecimientoHabilitado($subconsultorio->id_establecimiento)) ? 1 : 0;
    }
    $subconsultorio->save();
    Session::flash('flash_message', 'Se actualizo correctamente');
    return redirect('/add_consultorios_servicio/' . $subconsultorio->id_consultorio);
  }

  public function obtener_consultorio_tipo_atencion($id_ipress, $id_tipo_atencion)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $model_consultorio = new \App\Models\Consultorio;
    $consultorio = $model_consultorio->ShowConsultorioTipoAtencion($id_ipress, $id_tipo_atencion);
    return $consultorio;
  }

  public function obtener_consultorio_tipo_atencion_medico($id_ipress, $id_medico)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $model_consultorio = new \App\Models\Consultorio;
    $consultorio = $model_consultorio->ShowConsultorioMedico($id_ipress, $id_medico);
    return $consultorio;
  }

  public function obtener_upss($id_servicio, $id_tipo_atencion)
  {
    $model_consultorio = new \App\Models\Upss;
    $consultorio = $model_consultorio->ShowConsultoriosUpss($id_servicio, $id_tipo_atencion);
    if (sizeof($consultorio) == 0) {
      $tipo_atencion = TipoAtencione::find($id_tipo_atencion);
      $servicio = Consultorio::where('nombre', $tipo_atencion->nombre)->first();
      if (isset($servicio)) {
        $consultorio =  $model_consultorio->ShowConsultoriosUpss($servicio->id, $id_tipo_atencion);
        return $consultorio;
      }
    }
    return $consultorio;
  }

  public function obtener_upss_hos($id_servicio)
  {
    $model_consultorio = new \App\Models\Upss;
    $consultorio = $model_consultorio->ShowConsultoriosUpssHosConsExt($id_servicio);

    if (sizeof($consultorio) == 0) {
      $tipos_atencion = [1, 3];
      foreach ($tipos_atencion as $id_tipo_atencion) {
        $tipo_atencion = TipoAtencione::find($id_tipo_atencion);
        if ($tipo_atencion) {
          $servicio = Consultorio::where('nombre', $tipo_atencion->nombre)->first();
          if ($servicio) {
            $consultorio = $model_consultorio->ShowConsultoriosUpssHosConsExt($servicio->id);
            return $consultorio;
          }
        }
      }
    }
    return $consultorio;
  }

  public function obtener_upss2($id_servicio)
  {
    $model_consultorio = new \App\Models\Upss;
    $consultorio = $model_consultorio->ShowConsultoriosUpssConsExt($id_servicio);

    if (sizeof($consultorio) == 0) {
      $tipos_atencion = [1, 2];
      foreach ($tipos_atencion as $id_tipo_atencion) {
        $tipo_atencion = TipoAtencione::find($id_tipo_atencion);
        if ($tipo_atencion) {
          $servicio = Consultorio::where('nombre', $tipo_atencion->nombre)->first();
          if ($servicio) {
            $consultorio = $model_consultorio->ShowConsultoriosUpssConsExt($servicio->id);
            return $consultorio;
          }
        }
      }
    }
    return $consultorio;
  }

  public function get_consultorio_por_establecimiento($id_ipress)
  {
    $model_consultorio = new \App\Models\Consultorio;
    $consultorios = $model_consultorio->GetAllPorIpress($id_ipress);
    return $consultorios;
  }
  //===================================================================//
  public function editar_subconsultorio_dia($id_subconsultorio_turno, $dia, $turno_selected, $hora_ini, $hora_fin, $tiem_atencion, $id_servicio, $id_sub_consultorio, $fecha_inicio_turno = 0, $fecha_fin_turno = 0)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $model_turno = new Turno();
    $turno = $model_turno->ShowAll();

    //Obtener key de día
    $helper_obtenerDiaPorValue = new Util();
    $keyDia = $helper_obtenerDiaPorValue->obtenerDiaPorValue($dia);

    //Obtener días de la semana
    $helper_diasDeLaSemana = new Util();
    $diasDeLaSemana = $helper_diasDeLaSemana->obtenerDiasDeSemana();

    return view('frontend.consultorios.editar_subconsultorio_dia', compact('id_subconsultorio_turno', 'keyDia', 'diasDeLaSemana', 'turno_selected', 'hora_ini', 'hora_fin', 'tiem_atencion', 'turno', 'id_servicio', 'id_sub_consultorio', 'fecha_inicio_turno', 'fecha_fin_turno'));
  }

  public function update_subconsultorios_turnos(Request $request)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    //Actualiza sub_consultorio_turnos
    $id_subconsultorio_turno = $request->id_subconsultorio_turno;
    $sub_consultorio_turnos = SubConsultorioTurno::where('id', $id_subconsultorio_turno)->first();
    $sub_consultorio_turnos->id_turno = $request->id_turno;
    //fechas
    $hora_ini = date("g:i a", strtotime($request->hora_ini));
    $hora_fin = date("g:i a", strtotime($request->hora_fin));
    $sub_consultorio_turnos->hora_ini = $hora_ini;
    $sub_consultorio_turnos->hora_fin = $hora_fin;
    //dia
    $helper_diaValue = new Util();
    $diaValue = $helper_diaValue->obtenerDiaPorKey($request->id_dia);
    $sub_consultorio_turnos->dia = $diaValue;
    $sub_consultorio_turnos->tiempo_atencion = $request->tiempo_atencion;
    $sub_consultorio_turnos->fecha_inicio_turno = $request->fecha_inicio;
    $sub_consultorio_turnos->fecha_fin_turno = $request->fecha_final;

    $id_sub_consultorio = $request->id_sub_consultorio;
    $id_servicio = $request->id_servicio;
    //dd($sub_consultorio_turnos);

    if ($request->id_dia == "0") {
      session::flash('flash_warning', 'Debes seleccionar el día');
      return redirect('/add_consultorio_turnos_dia/' . $id_sub_consultorio . '/' . $id_servicio);
    }
    if ($request->id_turno == "0") {
      session::flash('flash_warning', 'Debes seleccionar el turno');
      return redirect('/add_consultorio_turnos_dia/' . $id_sub_consultorio . '/' . $id_servicio);
    }
    if ($hora_ini == "12:00 am") {
      session::flash('flash_warning', 'Debes completar la hora de inicio');
      return redirect('/add_consultorio_turnos_dia/' . $id_sub_consultorio . '/' . $id_servicio);
    }
    if ($hora_fin == "12:00 am") {
      session::flash('flash_warning', 'Debes completar la hora de fin');
      return redirect('/add_consultorio_turnos_dia/' . $id_sub_consultorio . '/' . $id_servicio);
    }
    if ($request->tiempo_atencion == "") {
      session::flash('flash_warning', 'Debes completar el tiempo de atención');
      return redirect('/add_consultorio_turnos_dia/' . $id_sub_consultorio . '/' . $id_servicio);
    }

    $sub_consultorio_turnos->save();
    session::flash('flash_message', 'Se actualizo correctamente');
    return redirect('/add_consultorio_turnos_dia/' . $id_sub_consultorio . '/' . $id_servicio);
  }

  //Metodo para agregar dia y turno ipress 76
  public function agregar_consultorio_turno_dia(Request $request)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    $model_subconsultorioturno = new SubConsultorioTurno;
    $model_subconsultorioturno->id_sub_consultorio = $request->id_sub_consultorio;
    $model_subconsultorioturno->id_turno = $request->id_turno;
    $model_subconsultorioturno->hora_ini = date("g:i a", strtotime($request->hora_ini));
    $model_subconsultorioturno->hora_fin = date("g:i a", strtotime($request->hora_fin));

    //Obtener día
    $helper_obtenerDiaPorKey = new Util();
    $DiaValue = $helper_obtenerDiaPorKey->obtenerDiaPorKey($request->dia);
    $model_subconsultorioturno->dia = $DiaValue;
    $model_subconsultorioturno->tiempo_atencion = $request->tiempo_atencion;
    $model_subconsultorioturno->id_estado_reg = 1;
    $model_subconsultorioturno->created_us = $id_user;
    $model_subconsultorioturno->fecha_inicio_turno = $request->fecha_inicio;
    $model_subconsultorioturno->fecha_fin_turno = $request->fecha_final;
    $id_servicio = $request->id_servicio;
    $model_existe = new SubConsultorioTurno;
    $existe = $model_existe->ExisteConsultorioTurno($request->id_sub_consultorio, $request->id_turno, $request->hora_ini);
    //dd($model_subconsultorioturno);

    if ($existe) {
      return redirect('/add_consultorio_turnos_dia/' . $request->id_sub_consultorio . '/' . $id_servicio)
        ->withInput()
        ->withErrors(array('No se ha podido registrar. El Turno ya existe en este Consultorio o está desactivado'));
    } else {
      $model_subconsultorioturno->save();
      Session::flash('flash_message', 'Se agrego correctamento el Turno al Consultorio');
      return redirect('/add_consultorio_turnos_dia/' . $request->id_sub_consultorio . '/' . $id_servicio)->with('agregar', 'ok');
    }
  }

  public function add_consultorio_turnos_dia($id_sub_consultorio, $id_servicio)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    $model_farma = new Farmacia;
    $farma = $model_farma->GetFarmaByUser($id_user);
    $id_farmacia = $farma->id;

    $model = new User;
    $establecimientotot = $model->GetById($id_user);
    $existe = $establecimientotot->flagestablecimientos;

    $model_subconsultorio = new SubConsultorio();
    $subconsultorio = $model_subconsultorio->GetNombreById($id_sub_consultorio);

    $model_turno = new Turno();
    $turno = $model_turno->ShowAll();

    $model_subconsultorioturno = new SubConsultorioTurno();

    $model_consultorio = new Consultorio();
    $consultorio = $model_consultorio->GetNombreById($id_servicio);
    //Obtener Días
    $helper_diasDeLaSemana = new Util();
    $diasDeLaSemana = $helper_diasDeLaSemana->obtenerDiasDeSemana();


    if ($existe == "1") {
      $subconsultorioturno = $model_subconsultorioturno->ObtenerSubConsultorioTurnoPorId($id_sub_consultorio);
    } else {
      $subconsultorioturno = $model_subconsultorioturno->ObtenerSubConsultorioTurnoPorId($id_sub_consultorio);
    }

    $this->desactivarEstadosOpcionTodos($subconsultorioturno, $id_user);

    return view(
      'frontend.consultorios.registrar_sub_consultorio_turno_dia',
      compact('turno', 'id_sub_consultorio', 'id_servicio', 'subconsultorioturno', 'subconsultorio', 'existe', 'consultorio', 'diasDeLaSemana')
    );
  }

  public function edit_consultorios_servicio_2($id_sub_consultorio)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $subconsultorio = SubConsultorio::where('id', $id_sub_consultorio)->first();
    $subconsultorio =  json_decode(json_encode($subconsultorio['attributes']));

    $model_tipoAtencion = new TipoAtencione();
    $tipo_atenciones = $model_tipoAtencion->ShowTipoAtencionAll();

    $pp_habilitado = $this->ppEstablecimientoHabilitado($subconsultorio->id_establecimiento);

    return view('frontend.consultorios.editar_subconsultorio_2', compact('subconsultorio', 'tipo_atenciones', 'pp_habilitado'));
  }

  public function desactivar_consultorio_turno_con_dia($id, $estado)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $id_user = Auth::user()->id;
    $model_SubConsultorioTurno = SubConsultorioTurno::where('id', '=', $id)->first();
    $dia = $model_SubConsultorioTurno->dia;
    $id_sub_consultorio = $model_SubConsultorioTurno->id_sub_consultorio;

    if ($estado == "1" && $dia !== "Todos") {
      $update = SubConsultorioTurno::where('id', '=', $id)->first();
      $update->id_estado_reg = 3;
      $update->updated_us = $id_user;
      $update->save();
      Session::flash('flash_message', 'Se desactivo correctamente');
    } elseif ($estado == "1"  && $dia == "Todos") {
      $model_subconsultorioturno = new SubConsultorioTurno();
      $subconsultorioturno = $model_subconsultorioturno->ObtenerSubConsultorioTurnoPorId($id_sub_consultorio);
      $update = SubConsultorioTurno::where('id', '=', $id)->first();
      $update->id_estado_reg = 3;
      $update->updated_us = $id_user;
      $this->activarEstadosOpcionTodos($subconsultorioturno, $id_user);
      $update->save();
      Session::flash('flash_message', 'Se desactivo correctamente');
    } elseif ($estado !== "1"  && $dia == "Todos") {
      $model_subconsultorioturno = new SubConsultorioTurno();
      $subconsultorioturno = $model_subconsultorioturno->ObtenerSubConsultorioTurnoPorId($id_sub_consultorio);
      $update = SubConsultorioTurno::where('id', '=', $id)->first();
      $update->id_estado_reg = 1;
      $update->updated_us = $id_user;
      $this->desactivarEstadosOpcionTodos($subconsultorioturno, $id_user);
      $update->save();
      Session::flash('flash_message', 'Se activo correctamente');
    } else {
      $update = SubConsultorioTurno::where('id', '=', $id)->first();
      /*
      if (!empty($update)) {
        SubConsultorioTurno::DesactivarTurnos($update->id_sub_consultorio, $update->id_turno);
      }*/
      $update->id_estado_reg = 1;
      $update->updated_us = $id_user;
      $update->save();
      Session::flash('flash_message', 'Se activo correctamente');
    }
    exit;
  }

  private function activarEstadosOpcionTodos($subconsultorioturno, $id_user)
  {
    //================================Estados Todos los días================================//
    $id_subconsultorioturnoTodosMD = array();
    $id_subconsultorioturnoTodosM = array();
    $id_subconsultorioturnoTodosT = array();
    $id_subconsultorioturnoTodosN = array();

    $id_subconsultorioturnoDiasMD = array();
    $id_subconsultorioturnoDiasM = array();
    $id_subconsultorioturnoDiasT = array();
    $id_subconsultorioturnoDiasN = array();

    foreach ($subconsultorioturno as $key => $value) {
      //=================CANTIDAD TODOS TOTAL=========================
      //Cantidad de Todos MD
      if ($value->dia == "Todos" && $value->id_estado_reg == 1 && $value->abreviatura_turno == 'MD') {
        $id_subconsultorioturnoTodosMD[$key]["id"] =  $value->id;
        $id_subconsultorioturnoTodosMD[$key]["dia"] = $value->dia;
        $id_subconsultorioturnoTodosMD[$key]["estado_registro"] = $value->id_estado_reg;
      }
      //Cantidad de Todos M
      if ($value->dia == "Todos" && $value->id_estado_reg == 1 && $value->abreviatura_turno == 'M') {
        $id_subconsultorioturnoTodosM[$key]["id"] =  $value->id;
        $id_subconsultorioturnoTodosM[$key]["dia"] = $value->dia;
        $id_subconsultorioturnoTodosM[$key]["estado_registro"] = $value->id_estado_reg;
      }
      //Cantidad de Todos T
      if ($value->dia == "Todos" && $value->id_estado_reg == 1 && $value->abreviatura_turno == 'T') {
        $id_subconsultorioturnoTodosT[$key]["id"] =  $value->id;
        $id_subconsultorioturnoTodosT[$key]["dia"] = $value->dia;
        $id_subconsultorioturnoTodosT[$key]["estado_registro"] = $value->id_estado_reg;
      }
      //Cantidad de Todos N
      if ($value->dia == "Todos" && $value->id_estado_reg == 1 && $value->abreviatura_turno == 'N') {
        $id_subconsultorioturnoTodosN[$key]["id"] =  $value->id;
        $id_subconsultorioturnoTodosN[$key]["dia"] = $value->dia;
        $id_subconsultorioturnoTodosN[$key]["estado_registro"] = $value->id_estado_reg;
      }

      //=================CANTIDAD DIAS TOTAL=========================
      //Cantidad de dias 
      if ($value->dia !== "Todos" && $value->abreviatura_turno == 'MD') {
        $id_subconsultorioturnoDiasMD[$key]["id"] =  $value->id;
        $id_subconsultorioturnoDiasMD[$key]["dia"] = $value->dia;
        $id_subconsultorioturnoDiasMD[$key]["estado_registro"] = $value->id_estado_reg;
      }
      //Cantidad de dias 
      if ($value->dia !== "Todos" && $value->abreviatura_turno == 'M') {
        $id_subconsultorioturnoDiasM[$key]["id"] =  $value->id;
        $id_subconsultorioturnoDiasM[$key]["dia"] = $value->dia;
        $id_subconsultorioturnoDiasM[$key]["estado_registro"] = $value->id_estado_reg;
      }
      //Cantidad de dias 
      if ($value->dia !== "Todos" && $value->abreviatura_turno == 'T') {
        $id_subconsultorioturnoDiasT[$key]["id"] =  $value->id;
        $id_subconsultorioturnoDiasT[$key]["dia"] = $value->dia;
        $id_subconsultorioturnoDiasT[$key]["estado_registro"] = $value->id_estado_reg;
      }
      //Cantidad de dias 
      if ($value->dia !== "Todos" && $value->abreviatura_turno == 'N') {
        $id_subconsultorioturnoDiasN[$key]["id"] =  $value->id;
        $id_subconsultorioturnoDiasN[$key]["dia"] = $value->dia;
        $id_subconsultorioturnoDiasN[$key]["estado_registro"] = $value->id_estado_reg;
      }
    }

    //=========================ACTUALIZAR ESTADOS===================================== 
    //CONTADOR
    $cantTodosMD = count($id_subconsultorioturnoTodosMD);
    $cantTodosM = count($id_subconsultorioturnoTodosM);
    $cantTodosT = count($id_subconsultorioturnoTodosT);
    $cantTodosN = count($id_subconsultorioturnoTodosN);

    //ACTIVAR DIA-TURNO-MD
    if ($cantTodosMD > 0) {
      $update = "";
      foreach ($id_subconsultorioturnoDiasMD as $key => $value) {
        $update = SubConsultorioTurno::where('id', '=', $value['id'])->first();
        $update->id_estado_reg = 1;
        $update->updated_us = $id_user;
        $update->save();
      }
    }
    //ACTIVAR DIA-TURNO-M
    if ($cantTodosM > 0) {
      $update = "";
      foreach ($id_subconsultorioturnoDiasM as $key => $value) {
        $update = SubConsultorioTurno::where('id', '=', $value['id'])->first();
        $update->id_estado_reg = 1;
        $update->updated_us = $id_user;
        $update->save();
      }
    }
    //ACTIVAR DIA-TURNO-T
    if ($cantTodosT > 0) {
      $update = "";
      foreach ($id_subconsultorioturnoDiasT as $key => $value) {
        $update = SubConsultorioTurno::where('id', '=', $value['id'])->first();
        $update->id_estado_reg = 1;
        $update->updated_us = $id_user;
        $update->save();
      }
    }
    //ACTIVAR DIA-TURNO-N
    if ($cantTodosN > 0) {
      $update = "";
      foreach ($id_subconsultorioturnoDiasN as $key => $value) {
        $update = SubConsultorioTurno::where('id', '=', $value['id'])->first();
        $update->id_estado_reg = 1;
        $update->updated_us = $id_user;
        $update->save();
      }
    }
    //==========================================================================================

  }
  private function desactivarEstadosOpcionTodos($subconsultorioturno, $id_user)
  {
    //================================Estados Todos los días================================//
    $id_subconsultorioturnoTodosMD = array();
    $id_subconsultorioturnoTodosM = array();
    $id_subconsultorioturnoTodosT = array();
    $id_subconsultorioturnoTodosN = array();

    $id_subconsultorioturnoDiasMD = array();
    $id_subconsultorioturnoDiasM = array();
    $id_subconsultorioturnoDiasT = array();
    $id_subconsultorioturnoDiasN = array();

    foreach ($subconsultorioturno as $key => $value) {
      //=================CANTIDAD TODOS TOTAL=========================
      //Cantidad de Todos MD
      if ($value->dia == "Todos" && $value->id_estado_reg == 1 && $value->abreviatura_turno == 'MD') {
        $id_subconsultorioturnoTodosMD[$key]["id"] =  $value->id;
        $id_subconsultorioturnoTodosMD[$key]["dia"] = $value->dia;
        $id_subconsultorioturnoTodosMD[$key]["estado_registro"] = $value->id_estado_reg;
      }
      //Cantidad de Todos M
      if ($value->dia == "Todos" && $value->id_estado_reg == 1 && $value->abreviatura_turno == 'M') {
        $id_subconsultorioturnoTodosM[$key]["id"] =  $value->id;
        $id_subconsultorioturnoTodosM[$key]["dia"] = $value->dia;
        $id_subconsultorioturnoTodosM[$key]["estado_registro"] = $value->id_estado_reg;
      }
      //Cantidad de Todos T
      if ($value->dia == "Todos" && $value->id_estado_reg == 1 && $value->abreviatura_turno == 'T') {
        $id_subconsultorioturnoTodosT[$key]["id"] =  $value->id;
        $id_subconsultorioturnoTodosT[$key]["dia"] = $value->dia;
        $id_subconsultorioturnoTodosT[$key]["estado_registro"] = $value->id_estado_reg;
      }
      //Cantidad de Todos N
      if ($value->dia == "Todos" && $value->id_estado_reg == 1 && $value->abreviatura_turno == 'N') {
        $id_subconsultorioturnoTodosN[$key]["id"] =  $value->id;
        $id_subconsultorioturnoTodosN[$key]["dia"] = $value->dia;
        $id_subconsultorioturnoTodosN[$key]["estado_registro"] = $value->id_estado_reg;
      }

      //=================CANTIDAD DIAS TOTAL=========================
      //Cantidad de dias 
      if ($value->dia !== "Todos" && $value->abreviatura_turno == 'MD') {
        $id_subconsultorioturnoDiasMD[$key]["id"] =  $value->id;
        $id_subconsultorioturnoDiasMD[$key]["dia"] = $value->dia;
        $id_subconsultorioturnoDiasMD[$key]["estado_registro"] = $value->id_estado_reg;
      }
      //Cantidad de dias 
      if ($value->dia !== "Todos" && $value->abreviatura_turno == 'M') {
        $id_subconsultorioturnoDiasM[$key]["id"] =  $value->id;
        $id_subconsultorioturnoDiasM[$key]["dia"] = $value->dia;
        $id_subconsultorioturnoDiasM[$key]["estado_registro"] = $value->id_estado_reg;
      }
      //Cantidad de dias 
      if ($value->dia !== "Todos" && $value->abreviatura_turno == 'T') {
        $id_subconsultorioturnoDiasT[$key]["id"] =  $value->id;
        $id_subconsultorioturnoDiasT[$key]["dia"] = $value->dia;
        $id_subconsultorioturnoDiasT[$key]["estado_registro"] = $value->id_estado_reg;
      }
      //Cantidad de dias 
      if ($value->dia !== "Todos" && $value->abreviatura_turno == 'N') {
        $id_subconsultorioturnoDiasN[$key]["id"] =  $value->id;
        $id_subconsultorioturnoDiasN[$key]["dia"] = $value->dia;
        $id_subconsultorioturnoDiasN[$key]["estado_registro"] = $value->id_estado_reg;
      }
    }

    //=========================ACTUALIZAR ESTADOS===================================== 
    //CONTADOR
    $cantTodosMD = count($id_subconsultorioturnoTodosMD);
    $cantTodosM = count($id_subconsultorioturnoTodosM);
    $cantTodosT = count($id_subconsultorioturnoTodosT);
    $cantTodosN = count($id_subconsultorioturnoTodosN);

    //DESACTIVAR DIA-TURNO-MD
    if ($cantTodosMD > 0) {
      $update = "";
      foreach ($id_subconsultorioturnoDiasMD as $key => $value) {
        $update = SubConsultorioTurno::where('id', '=', $value['id'])->first();
        $update->id_estado_reg = 3;
        $update->updated_us = $id_user;
        $update->save();
      }
    }
    //DESACTIVAR DIA-TURNO-M
    if ($cantTodosM > 0) {
      $update = "";
      foreach ($id_subconsultorioturnoDiasM as $key => $value) {
        $update = SubConsultorioTurno::where('id', '=', $value['id'])->first();
        $update->id_estado_reg = 3;
        $update->updated_us = $id_user;
        $update->save();
      }
    }
    //DESACTIVAR DIA-TURNO-T
    if ($cantTodosT > 0) {
      $update = "";
      foreach ($id_subconsultorioturnoDiasT as $key => $value) {
        $update = SubConsultorioTurno::where('id', '=', $value['id'])->first();
        $update->id_estado_reg = 3;
        $update->updated_us = $id_user;
        $update->save();
      }
    }
    //DESACTIVAR DIA-TURNO-N
    if ($cantTodosN > 0) {
      $update = "";
      foreach ($id_subconsultorioturnoDiasN as $key => $value) {
        $update = SubConsultorioTurno::where('id', '=', $value['id'])->first();
        $update->id_estado_reg = 3;
        $update->updated_us = $id_user;
        $update->save();
      }
    }
    //==========================================================================================

  }
  public function get_servicios_administrativos_by_establecimiento($id_establecimiento)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $model_consultorio = new Consultorio();
    $servicios = $model_consultorio->getServiciosAdministrativosByEstablecimiento($id_establecimiento);
    return $servicios;
  }

  public function obtener_servicio_by_id_upss($id_upss)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $model_consultorio = new Consultorio();
    $servicios = $model_consultorio->ListarServicioByIdUpss($id_upss);
    return $servicios;
  }

  public function searchByFilters(Request $request)
  {
    $nombre = $request->query('nombre');

    $consultorios = Consultorio::where('nombre', 'ILIKE', "%{$nombre}%")->get();

    return response()->json($consultorios, 200);
  }

  public function obtener_servicio_by_id_upss2($id_upss)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $model_consultorio = new Consultorio();
    $servicios = $model_consultorio->ListarServicioByIdUpss2($id_upss);
    return $servicios;
  }

  public function servicio_establecimiento_listar($id_servicio)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    $model_farma = new Farmacia;
    $farma = $model_farma->GetFarmaByUser($id_user);
    $id_farmacia = $farma->id;

    $model = new User;
    $establecimientotot = $model->GetById($id_user);
    $existe = $establecimientotot->flagestablecimientos;

    $model_consultorio = new Consultorio();
    $consultorio = $model_consultorio->GetNombreById($id_servicio);

    $model_establecimiento = new Establecimiento();
    $establecimientos = $model_establecimiento->ShowAll();

    $establecimientos_registrados = $model_establecimiento->GetEstablecimientoPorServicio($id_servicio);

    return view('frontend.consultorios.listar_servicio_establecimiento', compact('establecimientos', 'id_servicio', 'establecimientos_registrados', 'consultorio', 'existe'));
  }

  public function servicio_establecimiento_editar($id_servicio_establecimiento)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $model_establecimiento = new Establecimiento();
    $servicio_establecimiento = $model_establecimiento->GetServicioEstablecimientoPorId($id_servicio_establecimiento);

    if (!empty($servicio_establecimiento)) {

      $id_user = Auth::user()->id;
      $model_farma = new Farmacia;
      $farma = $model_farma->GetFarmaByUser($id_user);
      $id_farmacia = $farma->id;

      $model = new User;
      $establecimientotot = $model->GetById($id_user);
      $existe = $establecimientotot->flagestablecimientos;

      $model_consultorio = new Consultorio();
      $consultorio = $model_consultorio->GetNombreById($servicio_establecimiento->id_consultorio);

      $establecimientos = $model_establecimiento->ShowAll();

      return view('frontend.consultorios.editar_establecimiento_servicio', compact('servicio_establecimiento', 'establecimientos', 'consultorio', 'existe'));
    } else {
      return view('frontend.consultorios.all');
    }
  }

  public function guardarTipoAnestesiaProducto(Request $r)
  {
    try {

        DB::beginTransaction();

        DB::table('cqx_anestesia_producto_servicios')
            ->where('id_producto_servicio', $r->id_producto_servicio)
            ->update([
                'estado' => 0,
                'updated_at' => Carbon::now(),
                'updated_us' => auth()->user()->id
            ]);

        foreach ($r->id_tipo_anestesia as $id_tipo) {

            DB::table('cqx_anestesia_producto_servicios')->insert([
                'id_producto_servicio' => $r->id_producto_servicio,
                'id_tipo_anestesia'    => $id_tipo,
                'estado'               => 1,
                'created_at'           => Carbon::now(),
                'created_us'           =>  auth()->user()->id
            ]);
        }

        DB::commit();

        return response()->json([
            'ok' => true,
            'msg' => 'Tipos de anestesia actualizados correctamente'
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'ok' => false,
            'msg' => 'Error al guardar tipo de anestesia',
            'error' => $e->getMessage()
        ], 500);
    }
  }
  
  function getConsultoriosByFarmaciaId($farmacia_id)
  {
    $consultorios = Consultorio::join('consultorio_farmacias as cf', 'consultorios.id', '=', 'cf.id_consultorio')
                                ->where('cf.id_farmacia', $farmacia_id)
                                ->select('consultorios.*')
                                ->get();
    return response()->json($consultorios, 200);
  }

  public function obtenerDepartamentos(Request $request){
    $consultorio = new Consultorio();
    $departamentos = $consultorio->getDepartamentosPorDivision($request->id_division);
    return response()->json($departamentos);
  }
}
