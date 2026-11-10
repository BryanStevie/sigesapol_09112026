<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Farmacia;
use App\Models\SolicitudProgramacionProcDetalles;
use App\Models\SolicitudProgramacionProcedimientos;

class ProcedimientoProgramadoController extends Controller
{
  const PP_MAX_INFORMES = 3;

  public function consultarSolicitudProcedimientoPendiente(Request $request)
  {
    $detalles = SolicitudProgramacionProcDetalles::with(['solicitud', 'procedimiento'])
      ->whereIn('estado', [1, 2])
      ->whereIn('id_procedimiento', (array) $request->procedimientos_id)
      ->whereHas('solicitud', function ($query) use ($request) {
        $query->where('solicitud_programacion_procedimientos.asegurado_dni', (string) $request->nro_doc_asegurado);
      })
      ->get()
      ->unique(function ($item) {
        return $item->id_procedimiento . '-' . $item->id_solicitud;
      })
      ->map(function ($item) {
        return [
          'id_procedimiento' => $item->id_procedimiento,
          'codigo_procedimiento' => $item->procedimiento->codigo ? $item->procedimiento->codigo : null,
          'nombre_procedimiento' => $item->procedimiento->descripcion ? $item->procedimiento->descripcion : null,
          'solicitud' => $item->solicitud,
        ];
      })
      ->values();
    return response()->json($detalles);
  }

  public function all_procprogramados()
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, "all_procprogramados");
    if (count($menu) == 0 || $menu[0]->lectura == "")
      return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

    $model_farma = new Farmacia();
    $farmacia = $model_farma->GetFarmaByUser(Auth::user()->id);

    $servicios = DB::select("SELECT distinct sc.id_consultorio as id, c.nombre
      from sub_consultorios sc
      inner join consultorios c on c.id = sc.id_consultorio
      where sc.id_establecimiento = ?
      order by c.nombre", [Auth::user()->id_establecimiento]);

    $fechaDesde = Carbon::now()->startOfMonth()->format('d-m-Y');
    $fechaHasta = Carbon::now()->format('d-m-Y');
    $model_atencione = new \App\Models\TipoAtencione;
    $tipo_atenciones = $model_atencione->listarTipoAtencionesLaboratorio();

    return view('frontend.procedimientos programados.all_procprogramados', compact('farmacia', 'servicios', 'fechaDesde', 'fechaHasta','tipo_atenciones'));
  }
  
  private function ppServiciosMedicoUsuario()
  {
    if (intval(Auth::user()->tipo_registro) !== 1) return [];
    $id_medico = DB::table('medicos')->where('dni', trim(Auth::user()->dni))->where('estado', 1)->orderBy('id', 'asc')->value('id');
    if (!$id_medico) return [];
    $servicios = DB::table('medico_consultorios')
      ->where('id_medico', $id_medico)->where('id_establecimiento', intval(Auth::user()->id_establecimiento))->where('estado', 1)
      ->lists('id_consultorio');
    return array_values(array_unique(array_filter(array_map('intval', $servicios))));
  }

  private function ppEdadTexto($fecha_nac)
  {
    $fnac = trim($fecha_nac);
    if ($fnac == '') return '';
    foreach (['d/m/Y', 'Y-m-d', 'd-m-Y', 'Y-m-d H:i:s'] as $formato) {
      $nac = \DateTime::createFromFormat($formato, $fnac);
      if ($nac) {
        $dif = $nac->diff(new \DateTime());
        return $dif->y . ' A, ' . $dif->m . ' M, ' . $dif->d . ' D';
      }
    }
    return '';
  }

  public function listar_procprogramados_ajax(Request $request)
  {
    $limit = intval($request->NumeroRegistros) > 0 ? intval($request->NumeroRegistros) : 10;
    $pagina = intval($request->NumeroPagina) > 0 ? intval($request->NumeroPagina) : 1;
    $offset = ($pagina - 1) * $limit;

    $servicios_medico = $this->ppServiciosMedicoUsuario();
    $data = DB::select("select * from sp_listar_procprogramados_paginado(?, ?, ?, ?, ?, ?, ?, ?, ?, ?::integer[])", [
      intval(Auth::user()->id_establecimiento),
      trim((string) $request->fecha_ini),
      trim((string) $request->fecha_fin),
      trim((string) $request->dni_beneficiario),
      strtoupper(trim((string) $request->nombre_beneficiario)),
      intval($request->id_servicio),
      intval($request->estado),
      $limit,
      $offset,
      '{' . implode(',', $servicios_medico) . '}',
    ]);
    foreach ($data as $fila) {
      $fila->edad = $this->ppEdadTexto($fila->fecha_nacimiento);
    }

    $solicitudes = array_unique(array_map(function ($f) { return intval($f->id_solicitud); }, $data));
    $gastros = [];
    if (count($solicitudes) > 0) {
      $filas_gastro = DB::table('gastroenterologia as g')
        ->join('solicitud_procedimientos_detalle as d', 'd.id', '=', 'g.id_procp')
        ->whereIn('d.id_solicitud', $solicitudes)
        ->select('g.id', 'g.baja', 'g.alta', 'g.flag', 'd.id_solicitud', 'd.id_procedimiento')
        ->orderBy('g.id')->get();
      foreach ($filas_gastro as $g) {
        $gastros[$g->id_solicitud . '_' . $g->id_procedimiento] = $g;
      }
    }
    foreach ($data as $fila) {
      $clave = intval($fila->id_solicitud) . '_' . intval($fila->id_procedimiento);
      $fila->gastro = isset($gastros[$clave]) ? $gastros[$clave] : null;
    }

    $con_ordenes = $this->ppClavesConOrdenes($solicitudes);
    foreach ($data as $fila) {
      $fila->tiene_ordenes = isset($con_ordenes[intval($fila->id_solicitud) . '_' . intval($fila->id_procedimiento)]) ? 1 : 0;
    }

    $informes = [];
    if (count($solicitudes) > 0) {
      $filas_informe = DB::table('solicitud_procedimientos_detalle as d')
        ->whereIn('d.id_solicitud', $solicitudes)->where('d.estado', 3)
        ->groupBy('d.id_solicitud', 'd.id_procedimiento', 'd.requiere_informe')
        ->select('d.id_solicitud', 'd.id_procedimiento', 'd.requiere_informe', DB::raw('max(d.id_medico_atencion) as id_medico_atencion'))
        ->get();
      foreach ($filas_informe as $fi) {
        $informes[$fi->id_solicitud . '_' . $fi->id_procedimiento] = $fi;
      }
    }
    $con_informe = [];
    if (count($solicitudes) > 0) {
      $filas_con_informe = DB::table('solicitud_procedimientos_informes')->whereIn('id_solicitud', $solicitudes)->where('id_estado_reg', 1)
        ->select('id_solicitud', 'id_procedimiento')->distinct()->get();
      foreach ($filas_con_informe as $ci) {
        $con_informe[$ci->id_solicitud . '_' . $ci->id_procedimiento] = true;
      }
    }
    $medico_usuario = $this->medicoUsuarioPP();
    foreach ($data as $fila) {
      $clave = intval($fila->id_solicitud) . '_' . intval($fila->id_procedimiento);
      $fi = isset($informes[$clave]) ? $informes[$clave] : null;
      $fila->requiere_informe = $fi ? intval($fi->requiere_informe) : 0;
      $fila->tiene_informe = ($fi && isset($con_informe[$clave])) ? 1 : 0;
      $fila->puede_subir_informe = ($fi && $this->ppPuedeSubirInforme($fi->id_medico_atencion, $medico_usuario)) ? 1 : 0;
    }
    $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;

    $result["PageStart"] = $request->NumeroPagina;
    $result["pageSize"] = $request->NumeroRegistros;
    $result["SearchText"] = "";
    $result["ShowChildren"] = true;
    $result["iTotalRecords"] = $iTotalDisplayRecords;
    $result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
    $result["aaData"] = $data;
    return response()->json($result);
  }

  private function medicoUsuarioPP()
  {
    $model_medico = new \App\Models\Medico();
    return $model_medico->GetResponsableProcedimientoProgramado(Auth::user()->dni, Auth::user()->id_establecimiento);
  }

  // Clasifica cada detalle según el médico de su cita de procedimiento (flag_pp = 1):
  // 'libre' = sin cita (no requiere cita), 'sin_medico' = cita sin médico programado,
  // 'propio' = cita del médico del usuario, 'otro' = cita de otro médico.
  private function ppClasificarDetallesPorMedico($ids_detalle)
  {
    $ids_detalle = array_map('intval', (array) $ids_detalle);
    $clasificacion = [];
    foreach ($ids_detalle as $id) {
      $clasificacion[$id] = 'libre';
    }
    if (count($ids_detalle) == 0) return $clasificacion;

    $citas = DB::table('citas as c')
      ->leftJoin('medicos as m', 'm.id', '=', 'c.id_medico')
      ->whereIn('c.id_solicitud_procedimiento_detalle', $ids_detalle)
      ->where('c.flag_pp', 1)
      ->where('c.id_estado_reg', 1)
      ->orderBy('c.id', 'desc')
      ->select('c.id_solicitud_procedimiento_detalle as id_detalle', 'c.id_medico', 'm.dni')
      ->get();
    $revisados = [];
    foreach ($citas as $cita) {
      $id = intval($cita->id_detalle);
      if (isset($revisados[$id])) continue;
      $revisados[$id] = true;
      if (!$cita->id_medico) {
        $clasificacion[$id] = 'sin_medico';
      } else if (trim($cita->dni) == trim(Auth::user()->dni)) {
        $clasificacion[$id] = 'propio';
      } else {
        $clasificacion[$id] = 'otro';
      }
    }
    return $clasificacion;
  }

  private function ppUrlAnterior()
  {
    $anterior = \URL::previous();
    if (strpos($anterior, '/formulario_atencion/') !== false) {
      return '/all_citas_medico_paciente';
    }
    if (!$anterior || strpos($anterior, '/atender_procprogramado/') !== false || $anterior == \URL::current()) {
      return '/all_procprogramados';
    }
    return $anterior;
  }

  private function ppSinPendientesPorAtender($id_solicitud, $id_procedimiento)
  {
    $detalle = DB::table('solicitud_procedimientos_detalle')
      ->where('id_solicitud', $id_solicitud)->where('id_procedimiento', $id_procedimiento)
      ->orderBy('updated_at', 'desc')->orderBy('id', 'desc')
      ->first();
    if (!$detalle) {
      return redirect($this->ppUrlAnterior())->withErrors(['El procedimiento no pertenece a la solicitud.']);
    }

    $estado = intval($detalle->estado);
    if ($estado === 6) {
      return redirect('/ver_atencion_procprogramado/' . intval($id_solicitud) . '/' . intval($id_procedimiento));
    }

    $fecha = $detalle->fecha_anulacion ? Carbon::parse($detalle->fecha_anulacion)->format('d/m/Y') : '';
    $motivo = trim($detalle->motivo_anulacion);
    $mensajes = [
      1 => 'El procedimiento aún no tiene cita para ser atendido.',
      3 => 'El procedimiento ya fue atendido.',
      4 => trim('La atención de este procedimiento fue anulada el ' . $fecha) . ($motivo !== '' ? ': ' . $motivo : '.'),
      5 => trim('El procedimiento fue observado el ' . $fecha) . ($motivo !== '' ? ': ' . $motivo : '.'),
    ];
    $mensaje = isset($mensajes[$estado]) ? $mensajes[$estado] : 'No hay procedimientos por atender en esta solicitud.';
    return redirect($this->ppUrlAnterior())->withErrors([$mensaje]);
  }

  public function historial_atenciones_procprogramado($nro_doc)
  {
    if (!Auth::check()) {
      return response()->json([]);
    }
    $model_detalle = new SolicitudProgramacionProcDetalles();
    $data = $model_detalle->GetHistorialAtencionesPP($nro_doc);
    foreach ($data as $fila) {
      $fila->puede_ver = intval($fila->id_establecimiento) === intval(Auth::user()->id_establecimiento) ? 1 : 0;
      // Gastroenterología: su informe reemplaza al informe subido en la columna Ver informe
      $g = $this->ppGastroDeAtencion($fila->id_solicitud, $fila->id_procedimiento);
      $fila->gastro = $g ? ['id' => $g->id, 'baja' => intval($g->baja), 'alta' => intval($g->alta), 'flag' => intval($g->flag)] : null;
    }
    return response()->json($data);
  }

  public function atender_procprogramado($id_solicitud, $id_procedimiento)
  {
    return $this->vistaAtencionPP($id_solicitud, $id_procedimiento, false);
  }

  public function ver_atencion_procprogramado($id_solicitud, $id_procedimiento)
  {
    return $this->vistaAtencionPP($id_solicitud, $id_procedimiento, true);
  }

  public function modal_ver_atencion_procprogramado($id_solicitud, $id_procedimiento)
  {
    return $this->vistaAtencionPP($id_solicitud, $id_procedimiento, true, true);
  }

  private function vistaAtencionPP($id_solicitud, $id_procedimiento, $confirmacion, $modal = false)
  {
    if (!Auth::check()) :
      return $modal ? response('<div class="text-danger text-center" style="padding:30px">Sesión expirada. Vuelva a iniciar sesión.</div>'): redirect('/auth/login');
    endif;

    $model_solicitud = new SolicitudProgramacionProcedimientos();
    $model_detalle = new SolicitudProgramacionProcDetalles();
    $model_medico = new \App\Models\Medico();

    $solicitud = $model_solicitud->GetDatosAtencionPP($id_solicitud, Auth::user()->id_establecimiento);
    if (!$solicitud) {
      return $modal? response('<div class="text-danger text-center" style="padding:30px">No se encontró la solicitud de procedimientos.</div>') : redirect('/all_procprogramados')->withErrors(['No se encontró la solicitud de procedimientos.']);
    }

    $edad = '';
    $fnac = trim($solicitud->fecha_nac);
    $nac = false;
    foreach (['d/m/Y', 'Y-m-d', 'd-m-Y', 'Y-m-d H:i:s'] as $formato) {
      $nac = \DateTime::createFromFormat($formato, $fnac);
      if ($nac) break;
    }
    if ($nac) {
      $dif = $nac->diff(new \DateTime());
      $edad = $dif->y . ' A, ' . $dif->m . ' M, ' . $dif->d . ' D';
      $solicitud->fecha_nac = $nac->format('d/m/Y');
    }
    $sexos = ['M' => 'MASCULINO', 'F' => 'FEMENINO', '1' => 'MASCULINO', '2' => 'FEMENINO'];
    $solicitud->sexo_txt = isset($sexos[strtoupper(trim($solicitud->sexo))]) ? $sexos[strtoupper(trim($solicitud->sexo))] : $solicitud->sexo;
    $solicitud->edad = $edad;

    $atencion_registrada = null;
    $en_atencion = false;
    $sin_medico_programado = false;
    if (!$confirmacion) {
      $procedimientos = $model_detalle->GetProcedimientosPorAtenderPP($id_solicitud, $id_procedimiento);
      if (count($procedimientos) == 0) {
        return $this->ppSinPendientesPorAtender($id_solicitud, $id_procedimiento);
      }
      $responsable = $this->medicoUsuarioPP();

      // Solo se muestran las sesiones cuya cita es del médico del usuario, sin médico programado o sin cita
      if (count($procedimientos) > 0) {
        $clasificacion = $this->ppClasificarDetallesPorMedico(array_map(function ($p) { return $p->id; }, $procedimientos));
        $procedimientos = array_values(array_filter($procedimientos, function ($p) use ($clasificacion) {
          return $clasificacion[intval($p->id)] != 'otro';
        }));
        if (count($procedimientos) == 0) {
          return redirect($this->ppUrlAnterior())->withErrors(['No está programado para atender en este consultorio']);
        }
        $solo_sin_medico = true;
        foreach ($procedimientos as $p) {
          if ($clasificacion[intval($p->id)] != 'sin_medico') $solo_sin_medico = false;
        }
        if ($solo_sin_medico) {
          // Igual que formulario_atencion: la cita sin médico programado no muestra responsable y falla al guardar
          $responsable = null;
          $sin_medico_programado = true;
        }
      }
    } else {
      $procedimientos = $model_detalle->GetUltimaAtencionPP($id_solicitud, $id_procedimiento);
      if (count($procedimientos) == 0) {
        return $modal ? response('<div class="text-danger text-center" style="padding:30px">No se encontró la atención registrada del procedimiento.</div>') : redirect('/all_procprogramados')->withErrors(['No se encontró la atención registrada del procedimiento.']);
      }
      $atencion_registrada = $procedimientos[0];
      $en_atencion = intval($atencion_registrada->estado) === 6;

      $responsable = $model_medico->GetResponsableProcedimientoProgramadoPorId($atencion_registrada->id_medico_atencion);
    }

    $solicitud_detalle = $model_solicitud->GetDetalleSolicitudPP($id_solicitud);
    $model_kardex = new \App\Models\Kardexe();
    $fechaServidor = $model_kardex->getFechaServidor();
    $model_farma = new Farmacia();
    $farmacia = $model_farma->GetFarmaByIdfarma(Auth::user()->id_farmacia);
    $model_establecimiento = new \App\Models\Establecimiento;
    $establecimiento = $model_establecimiento->GetById($farmacia->id_establecimiento);
    $nivel = intval($farmacia->id_establecimiento) == 1 ? 2 : $establecimiento[0]->nivel;
    $establecimiento = $establecimiento[0];
    $farmacias = $model_farma->OnlyFarmaciasXEstablecimiento($farmacia->id_establecimiento);

    $pp_gastro_tipo = $this->ppTipoGastroPorCita(DB::table('solicitud_procedimientos_detalle')->where('id_solicitud', intval($id_solicitud))->where('id_procedimiento', intval($id_procedimiento))->lists('id'));

    $vista = $modal ? 'frontend.procedimientos programados.modal_ver_atencion_procprogramado' : 'frontend.procedimientos programados.atender_procprogramado';
    $ordenes_pp = $confirmacion ? $this->ppOrdenesDeAtencion($id_solicitud, $id_procedimiento) : null;
    $pp_requiere_token = $this->ppRequiereToken();
    $pp_puede_firmar_token = $confirmacion && !$modal && !$en_atencion && $pp_requiere_token
      && count($this->ppDocumentosPorFirmarToken($id_solicitud, $id_procedimiento)) > 0
      && $this->ppEsMedicoDeAtencion($id_solicitud, $id_procedimiento);
    $pp_puede_editar_observacion = $confirmacion && !$modal && $en_atencion && $this->ppEsMedicoDeAtencion($id_solicitud, $id_procedimiento);

    return view($vista, compact('solicitud', 'procedimientos', 'responsable', 'id_procedimiento', 'solicitud_detalle', 'confirmacion', 'atencion_registrada', 'modal', 'en_atencion', 'sin_medico_programado', 'fechaServidor', 'farmacias', 'nivel', 'establecimiento', 'ordenes_pp', 'pp_requiere_token', 'pp_puede_firmar_token', 'pp_gastro_tipo', 'pp_puede_editar_observacion'));
  }

  private function ppTipoGastroPorCita($ids_detalle)
  {
    $ids_detalle = array_map('intval', (array) $ids_detalle);
    if (count($ids_detalle) == 0) return null;
    $abreviatura = DB::table('citas as c')
      ->join('sub_consultorios as sc', 'sc.id', '=', 'c.id_sub_consultorio')
      ->whereIn('c.id_solicitud_procedimiento_detalle', $ids_detalle)
      ->where('c.flag_pp', 1)
      ->where('c.id_estado_reg', 1)
      ->orderBy('c.id', 'desc')
      ->value('sc.abreviatura');
    $tipos = ['50EA' => 'alta', '52EB' => 'baja'];
    $abreviatura = strtoupper(trim((string) $abreviatura));
    return isset($tipos[$abreviatura]) ? $tipos[$abreviatura] : null;
  }

  private function ppClavesConOrdenes($solicitudes){
    $claves = [];
    if (count($solicitudes) == 0) return $claves;
    $detalles = DB::table('solicitud_procedimientos_detalle as d')
      ->join('solicitud_programacion_procedimientos as s', 's.id', '=', 'd.id_solicitud')
      ->whereIn('d.id_solicitud', $solicitudes)->where('d.estado', 3)
      ->select('d.id', 'd.id_solicitud', 'd.id_procedimiento', 's.id_prestacion')->get();
    if (count($detalles) == 0) return $claves;
    $clave_detalle = [];
    $prestaciones = [];
    foreach ($detalles as $d) {
      $clave_detalle[intval($d->id)] = intval($d->id_solicitud) . '_' . intval($d->id_procedimiento);
      $prestaciones[intval($d->id_prestacion)] = true;
    }
    foreach (['receta_vales', 'laboratorios', 'radiologias'] as $tabla) {
      $procps = DB::table($tabla)->whereIn('id_prestacion', array_keys($prestaciones))->whereIn('id_procp', array_keys($clave_detalle))->whereRaw('coalesce(estado, 1) <> 0')->lists('id_procp');
      foreach ($procps as $id_procp) {
        if (isset($clave_detalle[intval($id_procp)])) $claves[$clave_detalle[intval($id_procp)]] = true;
      }
    }
    return $claves;
  }

  public function pp_ordenes_atencion($id_solicitud, $id_procedimiento)
  {
    if (!Auth::check()) {
      return response('<div class="text-danger text-center" style="padding:30px">Sesión expirada. Vuelva a iniciar sesión.</div>');
    }
    $model_solicitud = new SolicitudProgramacionProcedimientos();
    if (!$model_solicitud->GetDatosAtencionPP($id_solicitud, Auth::user()->id_establecimiento)) {
      return response('<div class="text-danger text-center" style="padding:30px">No se encontró la solicitud de procedimientos.</div>');
    }
    $ordenes_pp = $this->ppOrdenesDeAtencion($id_solicitud, $id_procedimiento);
    $id_prestacion = $this->ppPrestacionDeSolicitud($id_solicitud);
    return view('frontend.procedimientos programados.partials.pp_ordenes_listado', compact('ordenes_pp', 'id_prestacion', 'id_solicitud', 'id_procedimiento'));
  }

  private function ppOrdenesDeAtencion($id_solicitud, $id_procedimiento)
  {
    $ids = DB::table('solicitud_procedimientos_detalle')->where('id_solicitud', intval($id_solicitud))->where('id_procedimiento', intval($id_procedimiento))->lists('id');
    $ordenes = ['recetas' => [], 'laboratorios' => [], 'radiologias' => [], 'gastro' => null, 'informe' => null];
    if (count($ids) == 0) return $ordenes;
    $id_prestacion = $this->ppPrestacionDeSolicitud($id_solicitud);

    $recetas = DB::table('receta_vales as rv')
      ->leftJoin('farmacias as f', 'f.id', '=', 'rv.id_farmacia')
      ->where('rv.id_prestacion', $id_prestacion)->whereIn('rv.id_procp', $ids)->whereRaw('coalesce(rv.estado, 1) <> 0')
      ->select('rv.id', 'rv.nro_receta', 'rv.fecha_expedicion', 'rv.flag', 'rv.ruta_receta', 'rv.estado', 'f.nombre as farmacia')
      ->orderBy('rv.id')->get();
    foreach ($recetas as $rv) {
      $rv->productos = DB::table('producto_recetas as pr')
        ->join('productos as p', 'p.id', '=', 'pr.id_producto')
        ->leftJoin('diagnosticos as dg', 'dg.id', '=', 'pr.id_diagnosticos')
        ->where('pr.id_receta_vale', $rv->id)->where('pr.estado', '<>', 0)
        ->select('p.codigo', 'p.nombre', 'pr.tiempo_periodo', 'pr.posologia', 'pr.cantidad_prescrita', 'pr.id_via_administracion', 'dg.codigo as diagnostico_codigo', 'dg.nombre as diagnostico')
        ->orderBy('pr.id')->get();
    }
    $ordenes['recetas'] = $recetas;

    foreach (['laboratorios' => 2, 'radiologias' => 3] as $tabla => $tipo) {
      $ruta = $tabla == 'laboratorios' ? 'ruta_laboratorio' : 'ruta_radiologia';
      $columnas = ['id', 'flag', $ruta . ' as ruta', 'created_at', 'estado'];
      if ($tabla == 'laboratorios') {
        $columnas[] = 'observacion';
        $columnas[] = 'fecha_registro_toma_muestra';
      }
      $cabeceras = DB::table($tabla)->where('id_prestacion', $id_prestacion)->whereIn('id_procp', $ids)->whereRaw('coalesce(estado, 1) <> 0')->select($columnas)->orderBy('id')->get();
      foreach ($cabeceras as $c) {
        $c->examenes = DB::table('prestacion_procedimientos as pp')
          ->join('procedimientos as p', 'p.id', '=', 'pp.id_procedimiento')
          ->leftJoin('diagnosticos as dg', 'dg.id', '=', 'pp.id_diagnostico')
          ->where('pp.id_lab_rad', $c->id)->where('pp.tipo_procedimiento', $tipo)->where('pp.id_estado_reg', 1)
          ->select('p.codigo', 'p.descripcion', 'pp.cantidad', 'pp.observacion', 'pp.lado_radio', 'pp.comparativo_radio', 'pp.radiologia_portatil', 'dg.codigo as diagnostico_codigo', 'dg.nombre as diagnostico')
          ->orderBy('pp.id')->get();
      }
      $ordenes[$tabla] = $cabeceras;
    }

    $ordenes['gastro'] = $this->ppGastroDeAtencion($id_solicitud, $id_procedimiento);
    $procedimiento = DB::table('procedimientos')->where('id', intval($id_procedimiento))->select('codigo', 'descripcion')->first();
    $ordenes['procedimiento_detalle'] = $procedimiento ? $procedimiento->codigo . ' - ' . $procedimiento->descripcion : '';

    $ordenes['informe'] = null;
    if (intval(DB::table('solicitud_procedimientos_detalle')->where('id_solicitud', intval($id_solicitud))->where('id_procedimiento', intval($id_procedimiento))->max('requiere_informe')) === 1) {
      $sesiones = $this->ppSesionesInforme($id_solicitud, $id_procedimiento);
      $archivos = count($sesiones) > 0 ? $this->ppInformesDeAtencion($id_solicitud, $id_procedimiento) : [];
      $ordenes['informe'] = [
        'subido' => count($archivos) > 0 ? 1 : 0,
        'archivos' => $archivos,
        'puede_gestionar' => count($sesiones) > 0 && $this->ppPuedeSubirInforme($this->ppMedicoDeSesiones($sesiones), $this->medicoUsuarioPP()),
        'disponibles' => self::PP_MAX_INFORMES - count($archivos),
      ];
    }
    return $ordenes;
  }

  public function guardar_atencion_procprogramado(Request $request)
  {
    if (!Auth::check()) {
      return response()->json(['success' => false, 'message' => 'Sesión expirada.']);
    }
    $ids = is_array($request->ids) ? array_map('intval', $request->ids) : [];
    $id_solicitud = intval($request->id_solicitud);
    if (count($ids) == 0) {
      return response()->json(['success' => false, 'message' => 'Seleccione al menos un procedimiento.']);
    }
    if (!preg_match('/[\p{L}\p{N}]/u', trim($request->observacion))) {
      return response()->json(['success' => false, 'message' => 'Registre las observaciones de la realización del procedimiento.']);
    }
    $responsable = $this->medicoUsuarioPP();
    if (!$responsable) {
      return response()->json(['success' => false, 'message' => 'Su usuario no está registrado como profesional de salud en este establecimiento.']);
    }

    $model_detalle = new SolicitudProgramacionProcDetalles();
    $validos = $model_detalle->GetIdsPorAtenderValidosPP($id_solicitud, $ids, Auth::user()->id_establecimiento);
    if (count($validos) != count($ids)) {
      return response()->json(['success' => false, 'message' => 'Algún procedimiento seleccionado ya fue atendido o no corresponde a la solicitud.']);
    }

    $clasificacion = $this->ppClasificarDetallesPorMedico($ids);
    if (in_array('otro', $clasificacion)) {
      return response()->json(['success' => false, 'message' => 'No está programado para atender en este consultorio']);
    }
    if (in_array('sin_medico', $clasificacion)) {
      return response()->json(['success' => false, 'message' => 'La cita no tiene un médico programado en el turno. No se puede registrar la atención.']);
    }

    $mensaje_ordenes = $this->ppValidarOrdenes($request);
    if ($mensaje_ordenes != '') {
      return response()->json(['success' => false, 'message' => $mensaje_ordenes]);
    }

    DB::beginTransaction();
    try {
      DB::table('solicitud_procedimientos_detalle')->whereIn('id', $ids)->update([
        'estado' => 6,
        'fecha_atencion' => Carbon::now(),
        'id_medico_atencion' => $responsable->id,
        'atendido_us' => Auth::user()->id,
        'observacion_atencion' => trim($request->observacion),
        'updated_at' => Carbon::now(),
        'updated_us' => Auth::user()->id,
      ]);
      $this->ppGuardarOrdenes($request, $id_solicitud, $ids, $responsable);
      DB::commit();
      return response()->json(['success' => true, 'message' => 'Atención del procedimiento guardada correctamente.']);
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error('guardar_atencion_procprogramado: ' . $e->getMessage());
      return response()->json(['success' => false, 'message' => 'No se pudo registrar la atención.']);
    }
  }

  public function pp_editar_observacion_atencion(Request $request){
    if (!Auth::check()) {
      return response()->json(['success' => false, 'message' => 'Sesión expirada.']);
    }
    $id_solicitud = intval($request->id_solicitud);
    $id_procedimiento = intval($request->id_procedimiento);
    $observacion = trim($request->observacion);
    if (!preg_match('/[\p{L}\p{N}]/u', $observacion)) {
      return response()->json(['success' => false, 'message' => 'Registre las observaciones de la realización del procedimiento.']);
    }
    if (!$this->ppEsMedicoDeAtencion($id_solicitud, $id_procedimiento)) {
      return response()->json(['success' => false, 'message' => 'Solo el médico responsable de la atención puede editar la observación.']);
    }

    $model_detalle = new SolicitudProgramacionProcDetalles();
    $ids_en_atencion = $model_detalle->GetIdsPorEstadoPP($id_solicitud, $id_procedimiento, [6], Auth::user()->id_establecimiento);
    $ids_ultima = array_map(function ($d) { return $d->id; }, $model_detalle->GetUltimaAtencionPP($id_solicitud, $id_procedimiento));
    $ids = array_values(array_intersect($ids_en_atencion, $ids_ultima));
    if (count($ids) == 0) {
      return response()->json(['success' => false, 'message' => 'La atención ya fue finalizada, no se puede editar la observación.']);
    }

    $gastro_cambios = [];
    $gastro = $this->ppGastroDeAtencion($id_solicitud, $id_procedimiento);
    if ($gastro && is_array($request->gastro)) {
      if (intval($gastro->flag) !== 0) {
        return response()->json(['success' => false, 'message' => 'El informe de gastroenterología ya fue firmado, no se puede editar.']);
      }
      foreach ($this->ppCamposGastro() as $parte => $campos) {
        if (intval($gastro->$parte) !== 1) continue;
        $con_texto = false;
        foreach ($campos as $campo) {
          $valor = array_key_exists($campo, $request->gastro) ? trim((string) $request->gastro[$campo]) : (string) $gastro->$campo;
          if ($valor !== '') $con_texto = true;
          if ($valor !== trim((string) $gastro->$campo)) $gastro_cambios[$campo] = $valor === '' ? null : $valor;
        }
        if (!$con_texto) {
          return response()->json(['success' => false, 'message' => 'El informe de endoscopia ' . $parte . ' no puede quedar vacío.']);
        }
      }
    }
    $guardada = DB::table('solicitud_procedimientos_detalle')->whereIn('id', $ids)->value('observacion_atencion');
    $obs_cambio = str_replace("\r\n", "\n", $observacion) !== str_replace("\r\n", "\n", trim((string) $guardada));

    DB::beginTransaction();
    try {
      if ($obs_cambio) {
        DB::table('solicitud_procedimientos_detalle')->whereIn('id', $ids)->update([
          'observacion_atencion' => $observacion,
          'observacion_editada_us' => Auth::user()->id,
          'observacion_editada_at' => Carbon::now(),
        ]);
      }
      if (count($gastro_cambios) > 0) {
        DB::table('gastroenterologia')->where('id', $gastro->id)->update($gastro_cambios + [
          'informe_editado_us' => Auth::user()->id,
          'informe_editado_at' => Carbon::now(),
        ]);
      }
      DB::commit();
      return response()->json(['success' => true, 'message' => 'Cambios guardados correctamente.', 'observacion' => $observacion]);
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error('pp_editar_observacion_atencion: ' . $e->getMessage());
      return response()->json(['success' => false, 'message' => 'No se pudo guardar los cambios.']);
    }
  }

  private function ppCamposGastro()
  {
    return [
      'baja' => ['baja_indicaciones', 'baja_medicacion', 'baja_estatica', 'baja_dinamica', 'baja_tacto_rectal', 'baja_preparacion', 'baja_escala_cd', 'baja_escala_ct', 'baja_escala_ci', 'baja_escala_total', 'baja_insercion', 'baja_canal_anal', 'baja_recto', 'baja_sigmoide', 'baja_descendente', 'baja_transverso', 'baja_ascendente', 'baja_ciego', 'baja_biopsia', 'baja_complicaciones', 'baja_observaciones', 'baja_diag_endoscopico'],
      'alta' => ['alta_indicaciones', 'alta_medicacion', 'alta_esofago', 'alta_cardias', 'alta_fondo', 'alta_cuerpo', 'alta_antro', 'alta_bulbo', 'alta_seg_porcion', 'alta_biopsia', 'alta_complicaciones', 'alta_observaciones', 'alta_diag_endoscopico'],
    ];
  }

  public function pp_agregar_ordenes(Request $request)
  {
    if (!Auth::check()) {
      return response()->json(['success' => false, 'message' => 'Sesión expirada.']);
    }
    $id_solicitud = intval($request->id_solicitud);
    $id_procedimiento = intval($request->id_procedimiento);
    if ($request->receta_activa != 1 && $request->lab_activo != 1 && $request->rad_activo != 1 && $request->gastro_activo != 1) {
      return response()->json(['success' => false, 'message' => 'Active la <b>receta</b> o el <b>examen</b> que desea agregar.']);
    }
    $responsable = $this->medicoUsuarioPP();
    if (!$responsable) {
      return response()->json(['success' => false, 'message' => 'Su usuario no está registrado como profesional de salud en este establecimiento.']);
    }
    $model_detalle = new SolicitudProgramacionProcDetalles();
    $ids = $model_detalle->GetIdsPorEstadoPP($id_solicitud, $id_procedimiento, [3, 6], Auth::user()->id_establecimiento);
    if (count($ids) == 0) {
      return response()->json(['success' => false, 'message' => 'No se encontró la atención del procedimiento.']);
    }
    $mensaje_ordenes = $this->ppValidarOrdenes($request);
    if ($mensaje_ordenes != '') {
      return response()->json(['success' => false, 'message' => $mensaje_ordenes]);
    }

    $gastro_existente = $this->ppGastroDeAtencion($id_solicitud, $id_procedimiento);
    if ($gastro_existente) {
      $request->merge(['gastro_activo' => 0]);
    }

    DB::beginTransaction();
    try {
      $this->ppGuardarOrdenes($request, $id_solicitud, $ids, $responsable);
      $pendiente_token = false;
      $atendido = DB::table('solicitud_procedimientos_detalle')->whereIn('id', $ids)->where('estado', 3)->count() > 0;
      if ($atendido) {
        $pendiente_token = $this->ppFirmarAtencion($id_solicitud, $id_procedimiento);
      }
      DB::commit();
      return response()->json(['success' => true, 'message' => $atendido ? 'Órdenes registradas y firmadas correctamente.' : 'Órdenes registradas correctamente.', 'pendiente_token' => $pendiente_token]);
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error('pp_agregar_ordenes: ' . $e->getMessage());
      return response()->json(['success' => false, 'message' => 'No se pudo registrar las órdenes.']);
    }
  }

  public function pp_invalidar_receta(Request $request)
  {
    if (!Auth::check()) return redirect('/auth/login');
    $url = '/ver_atencion_procprogramado/' . intval($request->id_solicitud) . '/' . intval($request->id_procedimiento);
    $receta = \App\Models\Receta_vale::find(intval($request->id_receta));
    if (!$receta || intval($receta->id_procp) <= 0) {
      return redirect($url)->withErrors(['No se encontró la receta.']);
    }
    if ($receta->estado == 1) {
      \Session::flash('flash_message', 'La Receta ha sido dispensada ya no se puede anular');
      return redirect($url);
    }
    $id_farmacia = Auth::user()->id_farmacia;
    $existe = DB::table('receta_devueltas')->where('id_receta_vale', $receta->id)->where('id_farmacia', $id_farmacia)->first();
    if (!$existe) {
      $devolver = new \App\Models\Receta_devuelta();
      $devolver->id_receta_vale = $receta->id;
      $devolver->fecha_devolucion = Carbon::now()->format('d-m-Y');
      $devolver->id_farmacia = $id_farmacia;
      $devolver->id_user = Auth::user()->id;
      $devolver->motivo = $request->motivo;
      $devolver->save();
    }
    $receta->estado = 0; 
    $receta->save();
    return redirect($url);
  }

  // INVALIDAR EXAMEN LABORATORIO
  public function pp_invalidar_laboratorio(Request $request)
  {
    if (!Auth::check()) return redirect('/auth/login');
    $url = '/ver_atencion_procprogramado/' . intval($request->id_solicitud) . '/' . intval($request->id_procedimiento);
    $laboratorio = \App\Models\Laboratorio::find(intval($request->id_laboratorio));
    if (!$laboratorio || intval($laboratorio->id_procp) <= 0) {
      return redirect($url)->withErrors(['No se encontró la orden de laboratorio.']);
    }
    if ($laboratorio->estado != 1) {
      \Session::flash('flash_message', 'El examen de laboratorio ya se encuentra anulado');
      return redirect($url);
    }
    if ($laboratorio->fecha_registro_toma_muestra !== null) {
      \Session::flash('flash_message', 'No se puede invalidar, ya se registró la toma de muestra');
      return redirect($url);
    }
    $laboratorio->estado = 0;
    $laboratorio->obs_examen_invalidado = $request->motivo;
    $laboratorio->id_user_examen_invalidado = Auth::user()->id;
    $laboratorio->save();
    return redirect($url);
  }

  // INVALIDAR EXAMEN IMAGENOLOGÍA
  public function pp_invalidar_imagenologia(Request $request)
  {
    if (!Auth::check()) return redirect('/auth/login');
    $url = '/ver_atencion_procprogramado/' . intval($request->id_solicitud) . '/' . intval($request->id_procedimiento);
    $radiologia = \App\Models\Radiologia::find(intval($request->id_radiologia));
    if (!$radiologia || intval($radiologia->id_procp) <= 0) {
      return redirect($url)->withErrors(['No se encontró la orden de imagenología.']);
    }
    if ($radiologia->estado != 1) {
      \Session::flash('flash_message', 'El examen de imágenes ya se encuentra anulado');
      return redirect($url);
    }
    $con_informe = DB::table('prestacion_procedimientos')
      ->where('id_lab_rad', $radiologia->id)->where('tipo_procedimiento', 3)->where('id_estado_reg', 1)
      ->whereNotNull('created_at_registro_informe')->exists();
    if ($con_informe) {
      \Session::flash('flash_message', 'No se puede invalidar, al menos un examen de la orden ya tiene informe');
      return redirect($url);
    }
    $radiologia->estado = 0;
    $radiologia->obs_examen_invalidado = $request->motivo;
    $radiologia->id_user_examen_invalidado = Auth::user()->id;
    $radiologia->flag_invalidado = 1;
    $radiologia->fecha_orden_invalidado = Carbon::now();
    $radiologia->save();
    if (intval($radiologia->id_establecimiento) == 76 && !empty($radiologia->id_evaluacion)) {
      DB::table('rua_imagenologia')->where('id_rad', $radiologia->id)->where('id_evol', $radiologia->id_evaluacion)->update(['id_estado_reg' => 0]);
    }
    return redirect($url);
  }

  private function ppValidarOrdenes(Request $request)
  {
    $msg = '';
    if ($request->receta_activa == 1) {
      $productos = is_array($request->id_producto) ? array_filter($request->id_producto, function ($v) { return $v != '' && $v != "ru='undefined'"; }) : [];
      if (intval($request->id_farmacia_receta) <= 0) $msg .= 'Debe seleccionar la <b>farmacia</b> de la receta.<br>';
      if (trim($request->fecha_expedicion) == '') $msg .= 'Debe ingresar la <b>fecha de expedición</b> de la receta.<br>';
      if (count($productos) == 0) $msg .= 'Debe ingresar mínimo un <b>producto</b> a la receta.<br>';
    }
    if ($request->lab_activo == 1) {
      $examenes = is_array($request->id_examen_lab) ? array_filter($request->id_examen_lab, function ($v) { return $v != ''; }) : [];
      if (count($examenes) == 0) $msg .= 'Debe registrar mínimo un <b>examen de laboratorio</b>.<br>';
    }
    if ($request->rad_activo == 1) {
      $examenes = is_array($request->id_examen_rad) ? array_filter($request->id_examen_rad, function ($v) { return $v != ''; }) : [];
      if (count($examenes) == 0) $msg .= 'Debe registrar mínimo un <b>examen de imagenología</b>.<br>';
    }
    return $msg;
  }

  private function ppGuardarOrdenes(Request $request, $id_solicitud, $ids, $responsable)
  {
    $receta_activa = $request->receta_activa == 1;
    $lab_activo = $request->lab_activo == 1;
    $rad_activo = $request->rad_activo == 1;
    $gastro_activo = $request->gastro_activo == 1;
    if (!$receta_activa && !$lab_activo && !$rad_activo && !$gastro_activo) return;

    $model_solicitud = new SolicitudProgramacionProcedimientos();
    $solicitud = $model_solicitud->GetDatosAtencionPP($id_solicitud, Auth::user()->id_establecimiento);
    if (!$solicitud) {
      throw new \Exception('No se encontró la solicitud ' . $id_solicitud);
    }
    $id_procp = min($ids);
    $prestacion = \App\Models\Prestaciones::find($solicitud->id_prestacion);
    $model_procedimiento = new \App\Models\Procedimiento;
    $search = array('+', ',', '"', '/', '{', '}');
    $replace = array('\+', '\,', '\"', '\/', '\{', '\}');
    $fecha_hora = Carbon::now()->format('Y-m-d H:i:s');
    $id_establecimiento = Auth::user()->id_establecimiento;
    $id_farmacia = Auth::user()->id_farmacia;
    $model_establecimiento = new \App\Models\Establecimiento;
    $establecimiento = $model_establecimiento->GetById($id_establecimiento);
    $nivel = intval($id_establecimiento) == 1 ? 2 : intval($establecimiento[0]->nivel);

    if ($receta_activa) {
      $this->ppGuardarReceta($request, $solicitud, $prestacion, $ids, $responsable, $id_procp);
    }

    if ($lab_activo) {
      $p_laboratorio = "{";
      foreach ($request->id_examen_lab as $key => $value) {
        if ($value == '') continue;
        $p_laboratorio .= "{" . $value . ",";
        if ($nivel === 3) {
          $p_laboratorio .= "0";
        } else {
          $p_laboratorio .= (isset($request->examen_lab_observacion[$key]) && $request->examen_lab_observacion[$key] != "") ? str_replace($search, $replace, $request->examen_lab_observacion[$key]) : "0";
        }
        $p_laboratorio .= ",";
        $p_laboratorio .= (isset($request->examen_lab_cantidad[$key]) && $request->examen_lab_cantidad[$key] > 0) ? $request->examen_lab_cantidad[$key] : "0";
        $p_laboratorio .= ",";
        $p_laboratorio .= (isset($request->miSelectLab[$key]) && $request->miSelectLab[$key] != "") ? $request->miSelectLab[$key] : "0";
        $p_laboratorio .= "},";
      }
      if (strlen($p_laboratorio) > 1) $p_laboratorio = substr($p_laboratorio, 0, -1);
      $p_laboratorio .= "}";

      $has_observacion_unica = $nivel === 3 ? 1 : 0;
      $lab_orden_observacion = $nivel === 3 ? $request->lab_orden_observacion : null;
      $cita_origen = ($prestacion && $prestacion->id_cita) ? DB::table('citas')->where('id', $prestacion->id_cita)->select('flag_cita_laboratorio', 'flag_cita_laboratorio_ce')->first() : null;

      $id_laboratorio = $model_procedimiento->RegistrarLaboratorioOrden3(Auth::user()->id, $solicitud->id_prestacion, $id_establecimiento, $fecha_hora, $p_laboratorio, $has_observacion_unica, 0, $id_farmacia, 0, $solicitud->id_tipo_atencion, $solicitud->id_servicio, 0, 0, $responsable->id, $solicitud->id_asegurado, $lab_orden_observacion, 0, null);
      if (intval($id_laboratorio) <= 0) {
        throw new \Exception('No se registró la orden de laboratorio.');
      }

      if ($cita_origen) {
        DB::table('citas')->where('id', $prestacion->id_cita)->update([
          'flag_cita_laboratorio' => $cita_origen->flag_cita_laboratorio,
          'flag_cita_laboratorio_ce' => $cita_origen->flag_cita_laboratorio_ce,
        ]);
      }
      DB::table('laboratorios')->where('id', $id_laboratorio)->update(['id_procp' => $id_procp]);
      DB::table('prestacion_procedimientos')->where('id_prestacion', $solicitud->id_prestacion)->where('id_lab_rad', $id_laboratorio)->where('tipo_procedimiento', 2)->update(['id_procp' => $id_procp]);
    }

    if ($rad_activo) {
      $p_radiologia = "{";
      foreach ($request->id_examen_rad as $key => $value) {
        if ($value == '') continue;
        $p_radiologia .= "{" . $value . ",";
        $p_radiologia .= (isset($request->examen_rad_cantidad[$key]) && $request->examen_rad_cantidad[$key] > 0) ? $request->examen_rad_cantidad[$key] : "0";
        $p_radiologia .= ",";
        $p_radiologia .= (isset($request->examen_rad_observacion[$key]) && $request->examen_rad_observacion[$key] != "") ? str_replace($search, $replace, $request->examen_rad_observacion[$key]) : 'NULL';
        $p_radiologia .= ",";
        $p_radiologia .= (isset($request->id_comparativo[$key]) && $request->id_comparativo[$key] != "") ? $request->id_comparativo[$key] : "0";
        $p_radiologia .= ",";
        $p_radiologia .= (isset($request->id_lado[$key]) && $request->id_lado[$key] != "") ? $request->id_lado[$key] : "0";
        $p_radiologia .= ",";
        $p_radiologia .= (isset($request->miSelectRad[$key]) && $request->miSelectRad[$key] != "") ? $request->miSelectRad[$key] : "0";
        $p_radiologia .= ",";
        $p_radiologia .= (isset($request->radio_portatil[$key]) && $request->radio_portatil[$key] != "") ? $request->radio_portatil[$key] : "0";
        $p_radiologia .= "},";
      }
      if (strlen($p_radiologia) > 1) $p_radiologia = substr($p_radiologia, 0, -1);
      $p_radiologia .= "}";

      $id_radiologia = $model_procedimiento->RegistrarProcedimientoImagenes(Auth::user()->id, $solicitud->id_prestacion, $id_establecimiento, $fecha_hora, $p_radiologia, 3, 0, $id_farmacia);
      if (intval($id_radiologia) <= 0) {
        throw new \Exception('No se registró la orden de imagenología.');
      }
      DB::table('radiologias')->where('id', $id_radiologia)->update(['id_procp' => $id_procp]);
      DB::table('prestacion_procedimientos')->where('id_prestacion', $solicitud->id_prestacion)->where('id_lab_rad', $id_radiologia)->where('tipo_procedimiento', 3)->update(['id_procp' => $id_procp]);
      if (intval($id_establecimiento) == 76 && intval($request->id_rua_imagenes) > 0) {
        DB::table('rua_imagenologia')->where('id', intval($request->id_rua_imagenes))->update(['id_rad' => $id_radiologia, 'id_estado_reg' => 1, 'id_asegurado' => $solicitud->id_asegurado]);
      }
    }

    if ($gastro_activo && intval($id_establecimiento) == 76 && intval($solicitud->id_servicio) == 102) {
      $id_gastro = $this->ppGuardarGastro($request, $solicitud->id_prestacion);
      if ($id_gastro) {
        DB::table('gastroenterologia')->where('id', $id_gastro)->update(['id_procp' => $id_procp]);
      }
    }
  }

  private function ppGuardarReceta(Request $request, $solicitud, $prestacion, $ids, $responsable, $id_procp)
  {
    $p_detalle = "{";
    foreach ($request->id_producto as $key => $value) {
      if ($value == '' || $value == "ru='undefined'") continue;
      $p_detalle .= "{";
      $p_detalle .= $value . ",";
      $p_detalle .= $request->tiempo_periodo[$key] . ",";
      $p_detalle .= $request->nro_med_solictados[$key] . ",";
      $p_detalle .= (isset($request->nro_med_entregados[$key]) && $request->nro_med_entregados[$key] != "") ? $request->nro_med_entregados[$key] : 0;
      $p_detalle .= ",";
      $p_detalle .= (isset($request->nro_stocks_almacen[$key]) && $request->nro_stocks_almacen[$key] != "") ? $request->nro_stocks_almacen[$key] : 0;
      $p_detalle .= ",";
      $p_detalle .= (isset($request->nro_stocks_establecimiento[$key]) && $request->nro_stocks_establecimiento[$key] != "") ? $request->nro_stocks_establecimiento[$key] : 0;
      $p_detalle .= ",";
      $p_detalle .= (isset($request->nro_stocks[$key]) && $request->nro_stocks[$key] != "") ? $request->nro_stocks[$key] : 0;
      $p_detalle .= ",";
      $p_detalle .= (isset($request->posologia[$key]) && $request->posologia[$key] != "") ? str_replace(",", "###", $request->posologia[$key]) : "-";
      $p_detalle .= ",";
      $p_detalle .= (isset($request->miSelectProd[$key]) && $request->miSelectProd[$key] != "") ? $request->miSelectProd[$key] : 0;
      $p_detalle .= ",";
      $p_detalle .= (isset($request->id_via_administracion[$key]) && $request->id_via_administracion[$key] != "") ? $request->id_via_administracion[$key] : 0;
      $p_detalle .= "},";
    }
    if (strlen($p_detalle) > 1) $p_detalle = substr($p_detalle, 0, -1);
    $p_detalle .= "}";
    $tipos_origen = [];
    $model_prestaciones = new \App\Models\Prestaciones();
    foreach ($model_prestaciones->GetDiagnosticoByIdPrestacion($solicitud->id_prestacion) as $row) {
      $tipos_origen[intval($row->id_diagnostico)] = intval($row->id_tipo_diagnostico) > 0 ? intval($row->id_tipo_diagnostico) : 0;
    }
    $id_diagnosticos = DB::table('solicitud_procedimientos_detalle')->whereIn('id', $ids)->whereNotNull('id_diagnostico')->distinct()->lists('id_diagnostico');
    $p_diagnostico = "{";
    foreach ($id_diagnosticos as $id_diagnostico) {
      $tipo = isset($tipos_origen[intval($id_diagnostico)]) ? $tipos_origen[intval($id_diagnostico)] : 0;
      $p_diagnostico .= "{" . intval($id_diagnostico) . "," . $tipo . "},";
    }
    if (strlen($p_diagnostico) > 1) $p_diagnostico = substr($p_diagnostico, 0, -1);
    $p_diagnostico .= "}";

    $asegurado = \App\Models\Asegurado::find($solicitud->id_asegurado);
    $titular = $asegurado && $asegurado->id_asegurado_titular ? \App\Models\Asegurado::find($asegurado->id_asegurado_titular) : null;
    if (!$titular) $titular = $asegurado;
    $cita = ($prestacion && $prestacion->id_cita) ? \App\Models\Cita::find($prestacion->id_cita) : null;
    $subconsultorio = $prestacion ? \App\Models\SubConsultorio::find($prestacion->id_sub_consultorio) : null;
    $historia = \App\Models\Asegurado_historia::where('id_asegurado', $solicitud->id_asegurado)->where('id_establecimiento', Auth::user()->id_establecimiento)->first();

    $hora = date('H');
    if ($hora > 6 && $hora < 13) $turno = "M";
    elseif ($hora >= 13 && $hora < 19) $turno = "T";
    elseif ($hora >= 19 && $hora <= 24) $turno = "N";
    else $turno = "MD";

    $model_receta = new \App\Models\Receta_vale();
    $id_receta_vale = $model_receta->postRegistrarRecetaValeNuevoConDiagnostico(
      0, $request->id_farmacia_receta, Auth::user()->id,
      $subconsultorio ? $subconsultorio->id_consultorio : $solicitud->id_servicio, "", "",
      $asegurado->nro_doc_ident, $asegurado->cip, $asegurado->tipo_doc_ident,
      $asegurado->nombre, $asegurado->paterno, $asegurado->materno,
      $cita ? $cita->parentesco : $asegurado->parentesco, $titular->nro_doc_ident, $titular->tipo_doc_ident, $titular->nombre,
      $titular->paterno, $titular->materno, $request->fecha_expedicion,
      $request->fecha_expedicion, $historia ? $historia->nro_historia : "", $cita ? $cita->grado : $asegurado->grado, $responsable->id, 0,
      $prestacion ? $prestacion->id_tipo_atencion : $solicitud->id_tipo_atencion, $turno, "", 0,
      0, "", "", "", "",
      "", 0, $solicitud->id_prestacion, 0,
      2, $p_detalle, $p_diagnostico, $asegurado->sexo, $asegurado->fecha_nac,
      $prestacion ? $prestacion->codigo_upss : null
    );
    if (intval($id_receta_vale) <= 0) {
      throw new \Exception('No se registró la receta.');
    }
    DB::table('receta_vales')->where('id', $id_receta_vale)->update(['id_procp' => $id_procp]);
    DB::table('receta_diagnosticos')->where('id_prestacion', $solicitud->id_prestacion)->where('id_receta_vale', $id_receta_vale)->update(['id_procp' => $id_procp]);
    return $id_receta_vale;
  }

  private function ppGuardarGastro(Request $request, $id_prestacion)
  {
    $baja = 0; $alta = 0;
    $campos_baja = ['baja_indicaciones', 'baja_medicacion', 'baja_estatica', 'baja_dinamica', 'baja_tacto_rectal', 'baja_preparacion', 'baja_escala_cd', 'baja_escala_ct', 'baja_escala_ci', 'baja_escala_total', 'baja_insercion', 'baja_canal_anal', 'baja_recto', 'baja_sigmoide', 'baja_descendente', 'baja_transverso', 'baja_ascendente', 'baja_ciego', 'baja_biopsia', 'baja_complicaciones', 'baja_observaciones', 'baja_diag_endoscopico'];
    $campos_alta = ['alta_indicaciones', 'alta_medicacion', 'alta_esofago', 'alta_cardias', 'alta_fondo', 'alta_cuerpo', 'alta_antro', 'alta_bulbo', 'alta_seg_porcion', 'alta_biopsia', 'alta_complicaciones', 'alta_observaciones', 'alta_diag_endoscopico'];
    foreach ($campos_baja as $c) { if ($request->$c != '') $baja = 1; }
    foreach ($campos_alta as $c) { if ($request->$c != '') $alta = 1; }
    if ($baja == 0 && $alta == 0) return null;

    $model_gastro = new \App\Models\Gastroenterologia;
    return $model_gastro->RegistrarInformeGastro(
      $id_prestacion,
      $request->baja_indicaciones, $request->baja_medicacion, $request->baja_estatica, $request->baja_dinamica,
      $request->baja_tacto_rectal, $request->baja_preparacion, $request->baja_escala_cd, $request->baja_escala_ct,
      $request->baja_escala_ci, $request->baja_escala_total, $request->baja_insercion, $request->baja_canal_anal,
      $request->baja_recto, $request->baja_sigmoide, $request->baja_descendente, $request->baja_transverso,
      $request->baja_ascendente, $request->baja_ciego, $request->baja_biopsia, $request->baja_complicaciones,
      $request->baja_observaciones, $request->baja_diag_endoscopico,
      $request->alta_indicaciones, $request->alta_medicacion, $request->alta_esofago, $request->alta_cardias,
      $request->alta_fondo, $request->alta_cuerpo, $request->alta_antro, $request->alta_bulbo,
      $request->alta_seg_porcion, $request->alta_biopsia, $request->alta_complicaciones, $request->alta_observaciones,
      $request->alta_diag_endoscopico,
      Auth::user()->id, $alta, $baja
    );
  }


  private function ppPuedeSubirInforme($id_medico_atencion, $medico_usuario)
  {
    if (!$medico_usuario || intval($id_medico_atencion) <= 0 || intval($id_medico_atencion) !== intval($medico_usuario->id)) {
      return false;
    }
    return true;
  }

  private function ppSesionesInforme($id_solicitud, $id_procedimiento)
  {
    return DB::table('solicitud_procedimientos_detalle as d')
      ->join('solicitud_programacion_procedimientos as s', 's.id', '=', 'd.id_solicitud')
      ->join('prestaciones as p', 'p.id', '=', 's.id_prestacion')
      ->where('d.id_solicitud', intval($id_solicitud))->where('d.id_procedimiento', intval($id_procedimiento))
      ->where('d.estado', 3)->where('p.id_establecimiento', Auth::user()->id_establecimiento)
      ->select('d.id', 'd.id_medico_atencion', 'd.requiere_informe')
      ->orderBy('d.id')->get();
  }

  private function ppMedicoDeSesiones($sesiones){
    $medico_atencion = 0;
    foreach ($sesiones as $s) {
      if (intval($s->id_medico_atencion) > 0) $medico_atencion = intval($s->id_medico_atencion);
    }
    return $medico_atencion;
  }

  private function ppInformesDeAtencion($id_solicitud, $id_procedimiento){
    return DB::table('solicitud_procedimientos_informes')
      ->where('id_solicitud', intval($id_solicitud))->where('id_procedimiento', intval($id_procedimiento))
      ->where('id_estado_reg', 1)->orderBy('id')->get();
  }

  public function pp_subir_informe(Request $request)
  {
    if (!Auth::check()) {
      return response()->json(['success' => false, 'message' => 'Sesión expirada.']);
    }
    $id_solicitud = intval($request->id_solicitud);
    $id_procedimiento = intval($request->id_procedimiento);
    $sesiones = $this->ppSesionesInforme($id_solicitud, $id_procedimiento);
    if (count($sesiones) == 0) {
      return response()->json(['success' => false, 'message' => 'No se encontró la atención del procedimiento.']);
    }
    if (intval($sesiones[0]->requiere_informe) !== 1) {
      return response()->json(['success' => false, 'message' => 'El procedimiento no requiere informe.']);
    }
    if (!$this->ppPuedeSubirInforme($this->ppMedicoDeSesiones($sesiones), $this->medicoUsuarioPP())) {
      return response()->json(['success' => false, 'message' => 'Solo el médico que realizó la atención puede subir el informe.']);
    }

    $archivos = array_values(array_filter((array) $request->file('informes')));
    if (count($archivos) == 0) {
      return response()->json(['success' => false, 'message' => 'Seleccione el archivo PDF del informe.']);
    }
    $actuales = count($this->ppInformesDeAtencion($id_solicitud, $id_procedimiento));
    if ($actuales + count($archivos) > self::PP_MAX_INFORMES) {
      return response()->json(['success' => false, 'message' => 'La atención puede tener hasta ' . self::PP_MAX_INFORMES . ' informes y ya tiene ' . $actuales . '.']);
    }
    foreach ($archivos as $archivo) {
      if (!$archivo->isValid()) {
        return response()->json(['success' => false, 'message' => 'No se pudo recibir el archivo ' . e($archivo->getClientOriginalName()) . '. Puede superar el límite de subida del servidor.']);
      }
      $es_pdf = strtolower($archivo->getClientOriginalExtension()) === 'pdf' && strpos(file_get_contents($archivo->getRealPath(), false, null, 0, 5), '%PDF') === 0;
      if (!$es_pdf) {
        return response()->json(['success' => false, 'message' => 'El informe debe ser un archivo PDF.']);
      }
      if ($archivo->getSize() > 10 * 1024 * 1024) {
        return response()->json(['success' => false, 'message' => 'Cada PDF no debe superar los 10 MB.']);
      }
    }

    $rutas = [];
    DB::beginTransaction();
    try {
      foreach ($archivos as $i => $archivo) {
        $ruta = 'pp_informes/' . date('Y') . '/' . date('m') . '/pp_informe_' . $id_solicitud . '_' . $id_procedimiento . '_' . date('YmdHis') . '_' . ($i + 1) . '.pdf';
        \Storage::disk('local')->put($ruta, file_get_contents($archivo->getRealPath()));
        $rutas[] = $ruta;
        DB::table('solicitud_procedimientos_informes')->insert([
          'id_solicitud' => $id_solicitud,
          'id_procedimiento' => $id_procedimiento,
          'ruta' => $ruta,
          'nombre_archivo' => mb_substr($archivo->getClientOriginalName(), 0, 255),
          'created_us' => Auth::user()->id,
          'created_at' => Carbon::now(),
        ]);
      }
      DB::commit();
      return response()->json(['success' => true, 'message' => count($archivos) > 1 ? 'Informes subidos correctamente.' : 'Informe subido correctamente.']);
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error('pp_subir_informe: ' . $e->getMessage());
      foreach ($rutas as $ruta) {
        if (\Storage::disk('local')->exists($ruta)) \Storage::disk('local')->delete($ruta);
      }
      return response()->json(['success' => false, 'message' => 'No se pudo subir el informe.']);
    }
  }

  public function pp_eliminar_informe(Request $request){
    if (!Auth::check()) {
      return response()->json(['success' => false, 'message' => 'Sesión expirada.']);
    }
    $informe = DB::table('solicitud_procedimientos_informes')->where('id', intval($request->id_informe))->where('id_estado_reg', 1)->first();
    if (!$informe) {
      return response()->json(['success' => false, 'message' => 'El informe no existe o ya fue eliminado.']);
    }
    $sesiones = $this->ppSesionesInforme($informe->id_solicitud, $informe->id_procedimiento);
    if (count($sesiones) == 0 || !$this->ppPuedeSubirInforme($this->ppMedicoDeSesiones($sesiones), $this->medicoUsuarioPP())) {
      return response()->json(['success' => false, 'message' => 'Solo el médico que realizó la atención puede eliminar el informe.']);
    }
    try {
      DB::table('solicitud_procedimientos_informes')->where('id', $informe->id)->update([
        'id_estado_reg' => 0,
        'deleted_us' => Auth::user()->id,
        'deleted_at' => Carbon::now(),
      ]);
      return response()->json(['success' => true, 'message' => 'Informe eliminado correctamente.']);
    } catch (\Exception $e) {
      Log::error('pp_eliminar_informe: ' . $e->getMessage());
      return response()->json(['success' => false, 'message' => 'No se pudo eliminar el informe.']);
    }
  }

  public function pp_ver_informe($id_solicitud, $id_procedimiento, $id_informe = null){
    if (!Auth::check()) return redirect('/auth/login');
    $ruta = null;
    if (count($this->ppSesionesInforme($id_solicitud, $id_procedimiento)) > 0) {
      foreach ($this->ppInformesDeAtencion($id_solicitud, $id_procedimiento) as $informe) {
        if ($id_informe === null || intval($informe->id) === intval($id_informe)) { $ruta = $informe->ruta; break; }
      }
    }
    if (!$ruta || !\Storage::disk('local')->exists($ruta)) {
      return response('No se encontró el informe.', 404);
    }
    return \Response::make(\Storage::disk('local')->get($ruta), 200, [
      'Content-Type' => 'application/pdf',
      'Content-Disposition' => 'inline; filename="' . basename($ruta) . '"',
    ]);
  }

  private function ppGastroDeAtencion($id_solicitud, $id_procedimiento)
  {
    $ids = DB::table('solicitud_procedimientos_detalle')->where('id_solicitud', intval($id_solicitud))->where('id_procedimiento', intval($id_procedimiento))->lists('id');
    if (count($ids) == 0) return null;
    return \App\Models\Gastroenterologia::whereIn('id_procp', $ids)->orderBy('id', 'desc')->first();
  }

  private function ppRequiereToken()
  {
    if (intval(Auth::user()->status_token) === 1) return false;
    if (intval(Auth::user()->tipo_registro) === 3) return false;
    return !$this->ppFirmaElectronicaHabilitada();
  }

  private function ppFirmaElectronicaHabilitada()
  {
    $model_establecimiento = new \App\Models\Establecimiento;
    $establecimiento = $model_establecimiento->GetById(Auth::user()->id_establecimiento);
    return count($establecimiento) > 0 && !empty($establecimiento[0]->flag_firma_electronica);
  }

  private function ppPartesGastro($gastro)
  {
    $partes = [];
    if (intval($gastro->baja) == 1) $partes[] = 'baja';
    if (intval($gastro->alta) == 1) $partes[] = 'alta';
    return $partes;
  }

  private function ppNombreArchivoGastro($gastro, $parte)
  {
    return 'pp_informe_gastro_' . $parte . '_' . $gastro->id;
  }

  private function ppFirmarAtencion($id_solicitud, $id_procedimiento)
  {
    if ($this->ppRequiereToken()) {
      return count($this->ppDocumentosPorFirmarToken($id_solicitud, $id_procedimiento)) > 0;
    }
    $this->ppFirmarOrdenesSinToken($id_solicitud, $id_procedimiento);
    $gastro = $this->ppGastroDeAtencion($id_solicitud, $id_procedimiento);
    if ($gastro && intval($gastro->flag) == 0) {
      $this->ppFirmarGastroSinToken($gastro);
    }
    return false;
  }

  private function ppFirmarOrdenesSinToken($id_solicitud, $id_procedimiento)
  {
    $ids = DB::table('solicitud_procedimientos_detalle')->where('id_solicitud', intval($id_solicitud))->where('id_procedimiento', intval($id_procedimiento))->lists('id');
    if (count($ids) == 0) return;
    $id_prestacion = $this->ppPrestacionDeSolicitud($id_solicitud);
    $documentos = [
      ['modelo' => \App\Models\Receta_vale::class, 'ruta' => 'ruta_receta', 'prefijo' => 'cf_receta_'],
      ['modelo' => \App\Models\Laboratorio::class, 'ruta' => 'ruta_laboratorio', 'prefijo' => 'cf_laboratorio_'],
      ['modelo' => \App\Models\Radiologia::class, 'ruta' => 'ruta_radiologia', 'prefijo' => 'cf_radiologia_'],
    ];
    foreach ($documentos as $doc) {
      $modelo = $doc['modelo'];
      $filas = $modelo::where('id_prestacion', $id_prestacion)->whereIn('id_procp', $ids)->where('estado', '<>', 0)->get();
      foreach ($filas as $fila) {
        if ($fila->fecha_firma == "") {
          $fila->fecha_firma = date("d-m-Y H:i:s");
          $fila->{$doc['ruta']} = date("Y/m/d") . "/" . $doc['prefijo'] . $fila->id . ".pdf";
          $fila->flag = 2;
          $fila->save();
        }
      }
    }
  }

  private function ppFirmarGastroSinToken($gastro)
  {
    foreach ($this->ppPartesGastro($gastro) as $parte) {
      $gastro->{$parte . '_fecha_firma'} = date("d-m-Y H:i:s");
      $gastro->{$parte . '_ruta_firma'} = date("Y/m/d") . "/cf_" . $this->ppNombreArchivoGastro($gastro, $parte) . ".pdf";
    }
    $gastro->flag = 2;
    $gastro->save();
  }

  private function ppPdfGastro($gastro, $parte)
  {
    $detalle = DB::table('solicitud_procedimientos_detalle')->where('id', $gastro->id_procp)->first();
    $model_prestacion = new \App\Models\Prestaciones;
    $prestacion = $model_prestacion->GetPrestacionById($gastro->prestacion_id);
    $medico = $detalle && $detalle->id_medico_atencion ? DB::table('medicos')->where('id', $detalle->id_medico_atencion)->first() : null;
    if ($medico) {
      $prestacion->medico_paterno = $medico->paterno;
      $prestacion->medico_materno = $medico->materno;
      $prestacion->medico_nombre = $medico->nombre;
      $prestacion->dni = $medico->dni;
      $prestacion->colegiatura_medico = $medico->colegiatura;
    }

    $procedimiento = DB::table('solicitud_procedimientos_detalle as d')
      ->join('procedimientos as pr', 'pr.id', '=', 'd.id_procedimiento')
      ->where('d.id', $gastro->id_procp)
      ->select('pr.codigo', 'pr.descripcion as nombre')->get();
    $tipos_origen = [];
    foreach ($model_prestacion->GetDiagnosticoByIdPrestacion($gastro->prestacion_id) as $row) {
      $tipos_origen[intval($row->id_diagnostico)] = intval($row->id_tipo_diagnostico);
    }
    $diagnostico = [];
    if ($detalle) {
      $diagnostico = DB::table('solicitud_procedimientos_detalle as d')
        ->join('diagnosticos as dg', 'dg.id', '=', 'd.id_diagnostico')
        ->where('d.id_solicitud', $detalle->id_solicitud)->where('d.id_procedimiento', $detalle->id_procedimiento)
        ->select('dg.id', 'dg.codigo', 'dg.nombre')->distinct()->get();
      foreach ($diagnostico as $dg) {
        $dg->id_tipo_diagnostico = isset($tipos_origen[intval($dg->id)]) ? $tipos_origen[intval($dg->id)] : 0;
      }
    }

    $datos_gastro = $gastro;
    $contador = $parte == 'baja' ? 1 : 2;
    $ruta = $gastro->{$parte . '_ruta_firma'};
    $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/" . ($ruta ? $ruta : date("Y/m/d") . "/cf_" . $this->ppNombreArchivoGastro($gastro, $parte) . ".pdf");
    $codigo_qr = \QrCode::format('png')->size(150)->generate($url_descarga);

    $firma_electronica = null;
    if (intval($gastro->flag) == 2 && $medico) {
      $fecha_firma = $gastro->{$parte . '_fecha_firma'};
      $firma_electronica = [
        'medico' => $medico->paterno . ' ' . $medico->materno . ' ' . ucwords(mb_strtolower($medico->nombre, 'UTF-8')),
        'fecha' => $fecha_firma ? date('d/m/Y H:i:s', strtotime($fecha_firma)) : '',
      ];
    }

    $pdf = \PDF::loadView('pdf.pp_informe_gastro_' . $parte, compact('prestacion', 'diagnostico', 'procedimiento', 'contador', 'datos_gastro', 'codigo_qr', 'url_descarga', 'firma_electronica'));
    $pdf->getDomPDF()->set_option("enable_php", true);
    return $pdf;
  }

  public function pp_informe_gastro_pdf($id_gastro, $parte)
  {
    if (!Auth::check()) return redirect('/auth/login');
    $gastro = \App\Models\Gastroenterologia::find(intval($id_gastro));
    if (!$gastro || intval($gastro->id_procp) <= 0 || !in_array($parte, $this->ppPartesGastro($gastro))) {
      return response('No se encontró el informe.', 404);
    }
    $nombre = "cf_" . $this->ppNombreArchivoGastro($gastro, $parte) . ".pdf";
    $firmado = public_path('tmp/pdf/firmados/' . $nombre);
    if (intval($gastro->flag) == 1 && file_exists($firmado)) {
      return \Response::make(file_get_contents($firmado), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => "inline; filename=" . $nombre,
      ]);
    }
    return $this->ppPdfGastro($gastro, $parte)->stream($nombre);
  }

  private function ppEsMedicoDeAtencion($id_solicitud, $id_procedimiento)
  {
    $medico = $this->medicoUsuarioPP();
    if (!$medico) return false;
    $id_medico_atencion = DB::table('solicitud_procedimientos_detalle')->where('id_solicitud', intval($id_solicitud))->where('id_procedimiento', intval($id_procedimiento))
      ->whereIn('estado', [3, 6])->whereNotNull('id_medico_atencion')->orderBy('fecha_atencion', 'desc')->value('id_medico_atencion');
    return intval($id_medico_atencion) > 0 && intval($id_medico_atencion) === intval($medico->id);
  }

  private function ppPrestacionDeSolicitud($id_solicitud)
  {
    return intval(DB::table('solicitud_programacion_procedimientos')->where('id', intval($id_solicitud))->value('id_prestacion'));
  }

  private function ppDocumentosPorFirmarToken($id_solicitud, $id_procedimiento)
  {
    $ids = DB::table('solicitud_procedimientos_detalle')->where('id_solicitud', intval($id_solicitud))->where('id_procedimiento', intval($id_procedimiento))->lists('id');
    $docs = [];
    if (count($ids) == 0) return $docs;
    $id_prestacion = $this->ppPrestacionDeSolicitud($id_solicitud);
    foreach (\App\Models\Receta_vale::where('id_prestacion', $id_prestacion)->whereIn('id_procp', $ids)->where('estado', '<>', 0)->get() as $r) {
      if (intval($r->flag) !== 1) $docs[] = ['tipo' => 'receta', 'fila' => $r, 'nombre' => 'receta_' . $r->id, 'ruta' => 'ruta_receta'];
    }
    foreach (\App\Models\Laboratorio::where('id_prestacion', $id_prestacion)->whereIn('id_procp', $ids)->where('estado', '<>', 0)->get() as $l) {
      if (intval($l->flag) !== 1) $docs[] = ['tipo' => 'laboratorio', 'fila' => $l, 'nombre' => 'laboratorio_' . $l->id, 'ruta' => 'ruta_laboratorio'];
    }
    foreach (\App\Models\Radiologia::where('id_prestacion', $id_prestacion)->whereIn('id_procp', $ids)->where('estado', '<>', 0)->get() as $x) {
      if (intval($x->flag) !== 1) $docs[] = ['tipo' => 'radiologia', 'fila' => $x, 'nombre' => 'radiologia_' . $x->id, 'ruta' => 'ruta_radiologia'];
    }
    $gastro = $this->ppGastroDeAtencion($id_solicitud, $id_procedimiento);
    if ($gastro && intval($gastro->flag) !== 1) {
      foreach ($this->ppPartesGastro($gastro) as $parte) {
        $docs[] = ['tipo' => 'gastro', 'fila' => $gastro, 'parte' => $parte, 'nombre' => $this->ppNombreArchivoGastro($gastro, $parte)];
      }
    }
    return $docs;
  }

  public function pp_preparar_firma_token(Request $request)
  {
    $resultado = [];
    try {
      $id_solicitud = intval($request->id_solicitud);
      $id_procedimiento = intval($request->id_procedimiento);
      if (!$this->ppEsMedicoDeAtencion($id_solicitud, $id_procedimiento)) {
        return response()->json(['sw' => false, 'msg' => 'Solo el médico que realizó la atención puede firmar.']);
      }
      $docs = $this->ppDocumentosPorFirmarToken($id_solicitud, $id_procedimiento);
      if (count($docs) == 0) {
        return response()->json(['sw' => false, 'msg' => 'No hay documentos pendientes de firma.']);
      }
      foreach ([public_path('tmp/pdf'), public_path('tmp/pdf/sin_firma'), public_path('tmp/zip'), public_path('tmp/zip/sin_firma'), public_path('tmp/zip/firmados')] as $dir) {
        if (!is_dir($dir)) mkdir($dir);
      }
      $excel = app('App\Http\Controllers\ExcelController');
      $nombre_lote = Auth::user()->id . "_" . date("YmdHis") . "_pp_atencion_" . $id_solicitud . "_" . $id_procedimiento;
      $obj = new \Archive7z\Archive7z(public_path('tmp' . DIRECTORY_SEPARATOR . 'zip' . DIRECTORY_SEPARATOR . 'sin_firma' . DIRECTORY_SEPARATOR . $nombre_lote . '.7z'));
      foreach ($docs as $doc) {
        $archivo = public_path('tmp' . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR . 'sin_firma' . DIRECTORY_SEPARATOR . $doc['nombre'] . '.pdf');
        if ($doc['tipo'] == 'receta') {
          $excel->ver_receta_vale($doc['fila']->id);
        } elseif ($doc['tipo'] == 'laboratorio') {
          if (intval($doc['fila']->id_establecimiento) == 76) $excel->ver_laboratorio_imprimir($doc['fila']->id);
          else $excel->generateRUAPdf_nivel1y2($doc['fila']->id);
        } elseif ($doc['tipo'] == 'radiologia') {
          $excel->ver_radiologia_imprimir($doc['fila']->id);
        } else {
          file_put_contents($archivo, $this->ppPdfGastro($doc['fila'], $doc['parte'])->output());
        }
        if (file_exists($archivo)) $obj->addEntry($archivo);
      }
      $resultado['sw'] = true;
      $resultado['documento'] = $nombre_lote . '.7z';
    } catch (\Exception $e) {
      Log::error('pp_preparar_firma_token: ' . $e->getMessage());
      $resultado['sw'] = false;
      $resultado['msg'] = 'Error al generar los PDF para firma.';
    }
    return response()->json($resultado);
  }

  public function pp_gastro_post_arguments(Request $request)
  {
    $SERVER_PATH = env('REFIRMA_SERVER_PATH');
    $protocol = (env('REFIRMA_PROTOCOL') == 'http') ? "T" : "S";
    if ($request->type == "W") {
      $param = '{
                    "app":"pcx",
                    "mode":"lot-p",
                    "clientId":"' . env('REFIRMA_CLIENT_ID') . '",
                    "clientSecret":"' . env('REFIRMA_CLIENT_SECRET') . '",
                    "idFile":"MyForm",
                    "protocol":"' . $protocol . '",
                    "type":"W",
                    "fileDownloadUrl":"' . $SERVER_PATH . '/tmp/zip/sin_firma/' . $request->documentName . '",
                    "fileDownloadLogoUrl":"' . $SERVER_PATH . '/images/iLogo1.png",
                    "fileDownloadStampUrl":"' . $SERVER_PATH . '/images/logo_sanidad_firma.png",
                    "fileUploadUrl":"' . $SERVER_PATH . '/refirma/upload",
                    "contentFile":"' . $request->documentName . '",
                    "reason":"Soy el autor del documento",
                    "isSignatureVisible":"true",
                    "stampAppearanceId":"0",
                    "pageNumber":"0",
                    "posx":"20",
                    "posy":"5",
                    "fontSize":"7",
                    "dcfilter": ".*FIR.*|.*FAU.*",
                    "signatureLevel":"0",
                    "maxFileSize":"5242880"
                }';
      echo base64_encode($param);
    }
  }

  public function pp_confirmar_firma_token(Request $request){
    try {
      $id_solicitud = intval($request->id_solicitud);
      $id_procedimiento = intval($request->id_procedimiento);
      if (!$this->ppEsMedicoDeAtencion($id_solicitud, $id_procedimiento)) {
        return response()->json(['status' => 'error', 'message' => 'Solo el médico que realizó la atención puede firmar.']);
      }
      $firmados = public_path('tmp/pdf/firmados/');
      $faltan = 0;
      $gastro_partes = [];
      foreach ($this->ppDocumentosPorFirmarToken($id_solicitud, $id_procedimiento) as $doc) {
        $existe = file_exists($firmados . 'cf_' . $doc['nombre'] . '.pdf');
        if (!$existe) $faltan++;
        if ($doc['tipo'] == 'gastro') {
          $gastro_partes[] = ['doc' => $doc, 'existe' => $existe];
          continue;
        }
        if ($existe) {
          $fila = $doc['fila'];
          $fila->fecha_firma = date("d-m-Y H:i:s");
          $fila->{$doc['ruta']} = date("Y/m/d") . "/cf_" . $doc['nombre'] . ".pdf";
          $fila->flag = 1;
          $fila->save();
        }
      }
      if (count($gastro_partes) > 0 && count(array_filter($gastro_partes, function ($g) { return !$g['existe']; })) == 0) {
        $gastro = $gastro_partes[0]['doc']['fila'];
        foreach ($gastro_partes as $g) {
          $gastro->{$g['doc']['parte'] . '_fecha_firma'} = date("d-m-Y H:i:s");
          $gastro->{$g['doc']['parte'] . '_ruta_firma'} = date("Y/m/d") . "/cf_" . $g['doc']['nombre'] . ".pdf";
        }
        $gastro->flag = 1;
        $gastro->save();
      }
      return response()->json(['status' => $faltan > 0 ? 'pendiente' : 'ok']);
    } catch (\Exception $e) {
      Log::error('pp_confirmar_firma_token: ' . $e->getMessage());
      return response()->json(['status' => 'error', 'message' => 'No se pudo actualizar la firma.']);
    }
  }

  public function finalizar_atencion_procprogramado(Request $request){
    if (!Auth::check()) {
      return response()->json(['success' => false, 'message' => 'Sesión expirada.']);
    }
    $id_solicitud = intval($request->id_solicitud);
    $id_procedimiento = intval($request->id_procedimiento);

    $model_detalle = new SolicitudProgramacionProcDetalles();
    $ids = $model_detalle->GetIdsPorEstadoPP($id_solicitud, $id_procedimiento, [6], Auth::user()->id_establecimiento);
    if (count($ids) == 0) {
      return response()->json(['success' => false, 'message' => 'No se encontró una atención guardada pendiente de finalizar.']);
    }

    if ($request->has('observacion')) {
      $ultima = $model_detalle->GetUltimaAtencionPP($id_solicitud, $id_procedimiento);
      $guardada = count($ultima) > 0 ? $ultima[0]->observacion_atencion : '';
      if (str_replace("\r\n", "\n", trim($request->observacion)) !== str_replace("\r\n", "\n", trim($guardada))) {
        return response()->json(['success' => false, 'message' => 'Guarde los cambios de la observación antes de finalizar.']);
      }
    }

    DB::beginTransaction();
    try {
      DB::table('solicitud_procedimientos_detalle')->whereIn('id', $ids)->update([
        'estado' => 3,
        'updated_at' => Carbon::now(),
        'updated_us' => Auth::user()->id,
      ]);

      DB::table('citas')->whereIn('id_solicitud_procedimiento_detalle', $ids)
        ->where('flag_pp', 1)->where('id_estado_reg', 1)
        ->update(['estado_dia_paciente' => 1]);

      $quedan = DB::table('solicitud_procedimientos_detalle')->where('id_solicitud', $id_solicitud)->whereIn('estado', [1, 2, 6])->count();
      if ($quedan == 0) {
        DB::table('solicitud_programacion_procedimientos')->where('id', $id_solicitud)->update(['tiene_pendientes' => false]);
      }
      $pendiente_token = $this->ppFirmarAtencion($id_solicitud, $id_procedimiento);
      DB::commit();
      return response()->json(['success' => true, 'message' => 'Atención del procedimiento finalizada correctamente.', 'pendiente_token' => $pendiente_token]);
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error('finalizar_atencion_procprogramado: ' . $e->getMessage());
      return response()->json(['success' => false, 'message' => 'No se pudo finalizar la atención.']);
    }
  }

  public function observar_procprogramado(Request $request)
  {
    if (!Auth::check()) {
      return response()->json(['success' => false, 'message' => 'Sesión expirada.']);
    }
    $id_solicitud = intval($request->id_solicitud);
    $id_procedimiento = intval($request->id_procedimiento);
    $motivo = trim($request->motivo);
    if (preg_match_all('/\p{L}/u', $motivo) < 5) {
      return response()->json(['success' => false, 'message' => 'Registre un motivo válido (mínimo 5 letras).']);
    }

    $model_detalle = new SolicitudProgramacionProcDetalles();
    $ids = $model_detalle->GetIdsPorEstadoPP($id_solicitud, $id_procedimiento, [1, 2], Auth::user()->id_establecimiento);
    if (count($ids) == 0) {
      return response()->json(['success' => false, 'message' => 'No se encontró el procedimiento pendiente a observar.']);
    }

    DB::beginTransaction();
    try {
      DB::table('solicitud_procedimientos_detalle')->whereIn('id', $ids)->update([
        'estado' => 5,
        'fecha_anulacion' => Carbon::now(),
        'motivo_anulacion' => $motivo,
        'anulado_us' => Auth::user()->id,
        'updated_at' => Carbon::now(),
        'updated_us' => Auth::user()->id,
      ]);

      // Si estaba citado, se anula su cita (la observación es definitiva) para liberar el cupo
      DB::table('citas')->whereIn('id_solicitud_procedimiento_detalle', $ids)
        ->where('flag_pp', 1)->where('id_estado_reg', 1)
        ->update(['id_estado_reg' => 0, 'id_estado_cita' => 5, 'updated_at' => Carbon::now(), 'updated_us' => Auth::user()->id]);

      // Si la solicitud ya no tiene procedimientos por citar, atender o finalizar, se cierra
      $quedan = DB::table('solicitud_procedimientos_detalle')->where('id_solicitud', $id_solicitud)->whereIn('estado', [1, 2, 6])->count();
      if ($quedan == 0) {
        DB::table('solicitud_programacion_procedimientos')->where('id', $id_solicitud)->update(['tiene_pendientes' => false]);
      }
      DB::commit();
      return response()->json(['success' => true, 'message' => 'Procedimiento observado correctamente.']);
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error('observar_procprogramado: ' . $e->getMessage());
      return response()->json(['success' => false, 'message' => 'No se pudo observar el procedimiento.']);
    }
  }

  public function anular_procprogramado(Request $request)
  {
    if (!Auth::check()) {
      return response()->json(['success' => false, 'message' => 'Sesión expirada.']);
    }
    $id_solicitud = intval($request->id_solicitud);
    $id_procedimiento = intval($request->id_procedimiento);
    $motivo = trim($request->motivo);
    if (preg_match_all('/\p{L}/u', $motivo) < 5) {
      return response()->json(['success' => false, 'message' => 'Registre un motivo válido (mínimo 5 letras).']);
    }

    $model_detalle = new SolicitudProgramacionProcDetalles();
    $ids = $model_detalle->GetIdsPorEstadoPP($id_solicitud, $id_procedimiento, [3], Auth::user()->id_establecimiento);
    if (count($ids) == 0) {
      return response()->json(['success' => false, 'message' => 'No se encontró la atención a anular.']);
    }

    DB::beginTransaction();
    try {
      DB::table('solicitud_procedimientos_detalle')->whereIn('id', $ids)->update([
        'estado' => 4,
        'fecha_anulacion' => Carbon::now(),
        'motivo_anulacion' => $motivo,
        'anulado_us' => Auth::user()->id,
        'updated_at' => Carbon::now(),
        'updated_us' => Auth::user()->id,
      ]);
      DB::commit();
      return response()->json(['success' => true, 'message' => 'Atención anulada correctamente.']);
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error('anular_procprogramado: ' . $e->getMessage());
      return response()->json(['success' => false, 'message' => 'No se pudo anular la atención.']);
    }
  }
}
