<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Consultorio;
use App\Models\MovimientoHcDetalle;
use App\Models\MovimientoHcRecepcion;
use App\Models\MovimientoHcSolicitud;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class HCMovimientoController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Formulario de registro de una nueva solicitud de Historias Clínicas.
     */
    public function formSolicitud()
    {

        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;
        if (Auth::user()->actualizo_passw == 0) :
            return redirect('auth/password/change');
        endif;

        $user_id_establecimiento = Auth::user()->id_establecimiento;
        $model_consultorio = new Consultorio();
        $servicios = $model_consultorio->ShowAllConsultoriosSubConsultorio($user_id_establecimiento);
        
        $fecha_now = Carbon::now();
        $fecha_hora_actual = $fecha_now->format('d-m-Y H:i');
        $fecha_hora_actual_sin_formato = $fecha_now->format('Y-m-d H:i');
        $fecha_actual = $fecha_now->format('Y-m-d'); 

        $usuario_nombre_completo = Auth::user()->paterno . " " . Auth::user()->materno . ", " . Auth::user()->name;
        
        $movimiento_anio = $fecha_now->format('Y');
        $modelo_movimiento = new MovimientoHcSolicitud();
        $movimiento__nro_nuevo = $modelo_movimiento->generarNroMovimiento($user_id_establecimiento, $movimiento_anio);
        $solicitud_nro = "SOL-" . $movimiento_anio . "-" . $movimiento__nro_nuevo;

        $servicioSeleccionado = 1;

        $historiasSeleccionadas = [];

        $movimiento = null;

        return view('frontend.hc.movimiento.form_solicitud', array_merge($this->catalogosSolicitud(), compact(
            'solicitud_nro',
            'fecha_hora_actual',
            'fecha_hora_actual_sin_formato',
            'servicios',
            'servicioSeleccionado',
            'usuario_nombre_completo',
            'user_id_establecimiento',
            'fecha_actual',
            'movimiento_anio',
            'historiasSeleccionadas',
            'movimiento'
        )));
    }

    /**
     * Catálogos usados por el formulario de solicitud (registro y edición)
     */
    private function catalogosSolicitud($soloActivos = true)
    {
        $consultar = function ($tabla, $tipo = null) {
            $query = DB::table($tabla)->select('id', 'nombre')
                        ->where('id_estado_reg', 1);
            
            if ($tipo !== null) {
                $query->where('tipo', $tipo);
            }
            return $query->orderBy('id')->get();
        };

        $canales_ingreso = $consultar('movimiento_hc_canal_ingreso');
        $motivos = $consultar('movimiento_hc_motivo');
        $tipo_documentos = $consultar('movimiento_hc_tipo_documento', 'S');
        $lugares_archivo = $consultar('movimiento_hc_lugar_archivo');
        $prioridades = $consultar('movimiento_hc_prioridad');

        return compact('canales_ingreso', 'motivos', 'tipo_documentos', 'lugares_archivo', 'prioridades');
    }

    public function usuariosPorServicio(Request $request)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $usuarios = DB::table('users')
            ->select('id', 'paterno', 'materno', 'name')
            ->where('id_consultorio', (int) $request->get('id_servicio'))
            ->where('status', 1)
            ->orderBy('paterno')->orderBy('materno')->orderBy('name')
            ->get();

        $resultado = [];
        foreach ($usuarios as $u) {
            $resultado[] = ['id' => $u->id, 'nombre' => mb_strtoupper(trim($u->paterno . ' ' . $u->materno . ', ' . $u->name))];
        }

        return response()->json(['ok' => true, 'usuarios' => $resultado]);
    }

    /**
     * Búsqueda de historias clínicas (JSON) por N° de HC, DNI o apellidos.
     */
    public function buscarHistorias(Request $request)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $termino = trim($request->get('q', ''));

        if (mb_strlen($termino) < 3) {
            return response()->json(['ok' => false, 'mensaje' => 'Ingrese al menos 3 caracteres.', 'historias' => []]);
        }

        $limite = 15;
        $id_solicitud = (int) $request->get('id_solicitud');
        $modelo_movimiento = new MovimientoHcSolicitud();
        $historias = $modelo_movimiento->buscarHistorias(Auth::user()->id_establecimiento, $termino, $limite + 1);

        $excede = count($historias) > $limite;

        $resultado = [];
        foreach (array_slice($historias, 0, $limite) as $h) {
            // Una HC del mismo borrador que se edita sigue disponible
            $propia = $id_solicitud && $h->id_movimiento_hc_solicitud == $id_solicitud;
            $resultado[] = [
                'estado_hc' => $propia ? 0 : (int) $h->estado_hc,
                'id'        => $h->id,
                'nro_hc'    => $h->nro_historia,
                'tipo_doc'  => $h->tipo_doc_ident,
                'dni'       => $h->nro_doc_ident,
                'paciente'  => trim($h->paterno . ' ' . $h->materno . ', ' . $h->nombre),
            ];
        }

        return response()->json(['ok' => true, 'excede' => $excede, 'historias' => $resultado]);
    }

    /**
     * Listado de solicitudes registradas.
     */
    public function listarSolicitudes(Request $request)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $user_id_establecimiento = Auth::user()->id_establecimiento;

        $desde = $this->fechaFiltro($request->get('desde'), Carbon::now());
        $hasta = $this->fechaFiltro($request->get('hasta'), Carbon::now());
        $id_servicio = $request->get('id_servicio');
        $id_motivo = $request->get('id_motivo');
        $estado = $request->get('estado');
        $texto = trim($request->get('texto', ''));

        $modelo_movimiento = new MovimientoHcSolicitud();
        $solicitudes = $modelo_movimiento->listarSolicitudes($user_id_establecimiento, array(
            'desde'       => $desde->format('Y-m-d'),
            'hasta'       => $hasta->format('Y-m-d'),
            'id_servicio' => $id_servicio,
            'id_motivo'   => $id_motivo,
            'estado'      => $estado,
            'texto'       => $texto,
        ));

        $ids_movimientos = array();
        foreach ($solicitudes as $s) {
            $ids_movimientos[] = $s->id;
        }
        $historias_por_solicitud = array();
        foreach ($modelo_movimiento->historiasDeMovimientos($ids_movimientos) as $h) {
            $historias_por_solicitud[$h->id_movimiento_historia][] = $h;
        }

        $model_consultorio = new Consultorio();
        $servicios = $model_consultorio->ShowAllConsultoriosSubConsultorio($user_id_establecimiento);

        $catalogos = $this->catalogosSolicitud();
        $motivos = $catalogos['motivos'];

        $nombres = [];
        foreach (['canales_ingreso', 'motivos', 'tipo_documentos', 'lugares_archivo', 'prioridades'] as $clave) {
            foreach ($catalogos[$clave] as $item) {
                $nombres[$clave][$item->id] = $item->nombre;
            }
        }

        $desde = $desde->format('d-m-Y');
        $hasta = $hasta->format('d-m-Y');

        return view('frontend.hc.movimiento.lista_solicitudes', compact(
            'solicitudes',
            'historias_por_solicitud',
            'nombres',
            'servicios',
            'motivos',
            'desde',
            'hasta',
            'id_servicio',
            'id_motivo',
            'estado',
            'texto'
        ));
    }

    /**
     * Exporta a Excel el listado de solicitudes con los mismos filtros de la pantalla
     * (una fila por historia clínica).
     */
    public function exportarSolicitudes(Request $request)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $desde = $this->fechaFiltro($request->get('desde'), Carbon::now());
        $hasta = $this->fechaFiltro($request->get('hasta'), Carbon::now());

        $modelo_movimiento = new MovimientoHcSolicitud();
        $solicitudes = $modelo_movimiento->listarSolicitudes(Auth::user()->id_establecimiento, array(
            'desde'       => $desde->format('Y-m-d'),
            'hasta'       => $hasta->format('Y-m-d'),
            'id_servicio' => $request->get('id_servicio'),
            'id_motivo'   => $request->get('id_motivo'),
            'estado'      => $request->get('estado'),
            'texto'       => trim($request->get('texto', '')),
        ));

        $ids_movimientos = array();
        foreach ($solicitudes as $s) {
            $ids_movimientos[] = $s->id;
        }
        $historias_por_solicitud = array();
        foreach ($modelo_movimiento->historiasDeMovimientos($ids_movimientos) as $h) {
            $historias_por_solicitud[$h->id_movimiento_historia][] = $h;
        }

        $catalogos = $this->catalogosSolicitud();
        $nombres = [];
        foreach (['motivos', 'tipo_documentos'] as $clave) {
            foreach ($catalogos[$clave] as $item) {
                $nombres[$clave][$item->id] = $item->nombre;
            }
        }
        $estados_hc = [1 => 'PENDIENTE DE DESPACHO', 2 => 'EN EL SERVICIO', 3 => 'DEVUELTA'];

        $filas = [];
        $filas[] = array('Seguimiento del Movimiento de Historias Clínicas');
        $filas[] = array('De ' . $desde->format('d-m-Y') . ' a ' . $hasta->format('d-m-Y'));
        $filas[] = array('Total:');
        $filas[] = array('');
        $filas[] = array('N', 'N° HC', 'N° Documento', 'Paciente', 'N° Solicitud', 'Fecha registro', 'Solicitado por', 'Motivo', 'Documento', 'Donde está', 'Fecha de salida', 'Estado');

        $n = 0;
        foreach ($solicitudes as $s) {
            $motivo = isset($nombres['motivos'][$s->id_motivo]) ? $nombres['motivos'][$s->id_motivo] : '';
            $documento = trim((isset($nombres['tipo_documentos'][$s->id_documento]) ? $nombres['tipo_documentos'][$s->id_documento] : '') . ' N° ' . $s->nro_documento);
            $fecha_registro = $s->fecha_hora_registro ? Carbon::parse($s->fecha_hora_registro)->format('d-m-Y H:i') : '';
            $fecha_salida = $s->fecha_hora_despacho ? Carbon::parse($s->fecha_hora_despacho)->format('d-m-Y H:i') : '';
            $historias_fila = isset($historias_por_solicitud[$s->id]) ? $historias_por_solicitud[$s->id] : [null];

            foreach ($historias_fila as $h) {
                $filas[] = array(
                    ++$n,
                    $h ? $h->nro_historia : '',
                    $h ? $h->nro_doc_ident : '',
                    $h ? trim($h->paterno . ' ' . $h->materno . ', ' . $h->nombre) : '',
                    'SOL-' . $s->anio . '-' . $s->numero,
                    $fecha_registro,
                    $s->servicio_solicitante,
                    $motivo,
                    $documento,
                    $s->servicio_solicitante,
                    $fecha_salida,
                    ($h && isset($estados_hc[$h->estado_hc])) ? $estados_hc[$h->estado_hc] : '',
                );
            }
        }
        $filas[2] = array('Total: ' . $n);

        Excel::create('Seguimiento de Movimiento de HC', function ($excel) use ($filas) {
            $excel->sheet('GENERAL', function ($sheet) use ($filas) {
                $sheet->with($filas)->mergeCells('A2:C2')->mergeCells('A3:C3')->mergeCells('A4:C4');
            });
        })->export('xlsx');
    }

    /**
     * Convierte una fecha d-m-Y del filtro; si es inválida usa el valor por defecto.
     */
    private function fechaFiltro($valor, $defecto)
    {
        try {
            return Carbon::createFromFormat('d-m-Y', $valor)->startOfDay();
        } catch (\Exception $e) {
            return $defecto;
        }
    }

    /**
     * Formulario de edición de una solicitud existente.
     */
    public function editarSolicitud($id)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $movimiento = MovimientoHcSolicitud::where('id', $id)
            ->where('id_establecimiento', Auth::user()->id_establecimiento)
            ->firstOrFail();

        if ($movimiento->estado > 1) {
            return redirect()->route('hc_movimiento.lista_solicitudes')
                ->withFlashDanger('La solicitud no admite modificaciones.');
        }

        $user_id_establecimiento = Auth::user()->id_establecimiento;
        $model_consultorio = new Consultorio();
        $servicios = $model_consultorio->ShowAllConsultoriosSubConsultorio($user_id_establecimiento);

        $solicitud_nro = 'SOL-' . $movimiento->anio . '-' . $movimiento->numero;
        $fecha_hora_actual = Carbon::parse($movimiento->created_at)->format('d-m-Y H:i');
        $usuario_nombre_completo = Auth::user()->paterno . ' ' . Auth::user()->materno . ', ' . Auth::user()->name;
        $historiasSeleccionadas = [];
        $modelo_movimiento = new MovimientoHcSolicitud();
        foreach ($modelo_movimiento->historiasDeMovimiento($movimiento->id) as $h) {
            $historiasSeleccionadas[] = [
                'id'       => $h->id,
                'nro_hc'   => $h->nro_historia,
                'dni'      => $h->nro_doc_ident,
                'paciente' => trim($h->paterno . ' ' . $h->materno . ', ' . $h->nombre),
            ];
        }

        return view('frontend.hc.movimiento.form_solicitud', array_merge($this->catalogosSolicitud(), compact(
            'movimiento',
            'servicios',
            'solicitud_nro',
            'fecha_hora_actual',
            'usuario_nombre_completo',
            'user_id_establecimiento',
            'historiasSeleccionadas'
        )));
    }

    /**
     * Actualiza una solicitud existente.
     */
    public function actualizarSolicitud(Request $request, $id)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        // El PDF es opcional; al subir otro, sustituye al anterior
        $this->validate($request, $this->reglasSolicitud(), $this->mensajesSolicitud());

        $historias = (array) $request->input('historias', []);
        $datos = $this->datosSolicitud($request) + ['updated_us' => Auth::user()->id];
        $ruta_nueva = null;
        $ruta_anterior = null;

        try {
            DB::beginTransaction();

            $objMovimiento = MovimientoHcSolicitud::where('id', $id)
                ->where('id_establecimiento', Auth::user()->id_establecimiento)
                ->firstOrFail();

            if ($objMovimiento->estado > 1) {
                DB::rollback();
                return redirect()->back()->withInput()->withFlashDanger('La solicitud no admite modificaciones.');
            }

            if ($request->hasFile('documento_adjunto')) {
                $ruta_nueva = $this->guardarDocumento($request->file('documento_adjunto'), $objMovimiento);
                $ruta_anterior = $objMovimiento->documento_ruta;
                $datos['documento_ruta'] = $ruta_nueva;
            }

            $modelo_movimiento = new MovimientoHcSolicitud();

            if ($mensaje = $this->mensajeHistoriasNoDisponibles($modelo_movimiento->historiasNoDisponibles($historias, $objMovimiento->id))) {
                DB::rollback();
                $this->eliminarDocumento($ruta_nueva);
                return redirect()->back()->withInput()->withFlashDanger($mensaje);
            }

            $objMovimiento->update($datos);

            $modelo_movimiento->liberarHistoriasQuitadas($objMovimiento->id, $historias, Auth::user()->id);
            $modelo_movimiento->actualizarEstadoHistorias($historias, $datos['estado'], $objMovimiento->id);

            $this->registrarDetalle($objMovimiento->id, $historias, $datos['estado']);

            DB::commit();
            $this->eliminarDocumento($ruta_anterior);
        } catch (\Exception $e) {
            DB::rollback();
            $this->eliminarDocumento($ruta_nueva);
            Log::error('ERROR EN HC MOVIMIENTO AL ACTUALIZAR SOLICITUD: ' . $e->getMessage());
            return redirect()->back()->withInput()->withFlashDanger('No se pudo actualizar la solicitud.');
        }

        return redirect()->route('hc_movimiento.lista_solicitudes')->withFlashSuccess('Solicitud actualizada correctamente.');
    }

    /**
     * GUARDAR (borrador) => 1: sigue editable. ENVIAR => 2: ya no admite modificaciones.
     */
    /**
     * Mensaje para las historias que no estan en Archivo (1 = Bloqueada en un borrador, 2 = En el Servicio).
     * Devuelve null si todas estan disponibles.
     */
    private function mensajeHistoriasNoDisponibles($no_disponibles)
    {
        if (count($no_disponibles) === 0) {
            return null;
        }

        $detalle = [];
        foreach ($no_disponibles as $h) {
            $detalle[] = 'HC ' . $h->nro_historia . ' (' . ($h->estado_hc == 1 ? 'Bloqueada' : 'En el Servicio') . ')';
        }

        return 'No se puede registrar la solicitud, las siguientes historias no están en Archivo: ' . implode(', ', $detalle) . '.';
    }

    private function estadoSegunAccion($accion)
    {
        return $accion === 'enviar' ? 2 : 1;
    }

    /**
     * Sincroniza movimiento_hc_detalle con las historias seleccionadas.
     * Borrador => estado 1, enviada => estado 2 (igual que asegurado_historias.estado_hc).
     */
    private function registrarDetalle($id_solicitud, array $historias, $estado)
    {
        $modelo_movimiento = new MovimientoHcSolicitud();
        $ids = array_values(array_unique(array_filter(array_map('intval', $historias))));
        $existentes = $modelo_movimiento->idsHistoriasDetalle($id_solicitud);

        foreach (array_diff($ids, $existentes) as $id_historia) {
            $obj_detalle = new MovimientoHcDetalle();
            $obj_detalle->id_movimiento_hc_solicitud = $id_solicitud;
            $obj_detalle->id_asegurado_historia = $id_historia;
            $obj_detalle->estado = $estado;
            $obj_detalle->id_estado_reg = 1;
            $obj_detalle->created_at = Carbon::now();
            $obj_detalle->created_us = Auth::user()->id;
            $obj_detalle->save();
        }

        $modelo_movimiento->actualizarEstadoDetalle($id_solicitud, $estado);
    }

    /**
     * Guarda el PDF sustentatorio en el disco privado (storage/app) y devuelve la ruta relativa.
     * El nombre lleva el id de la solicitud, una marca de tiempo y el nombre original limpio.
     */
    private function guardarDocumento($archivo, MovimientoHcSolicitud $solicitud)
    {
        $nombre_original = pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME);
        $nombre_limpio = str_slug($nombre_original) ?: 'documento';
        $ruta = 'hc_movimiento/' . $solicitud->anio . '/' . $solicitud->id_establecimiento
            . '/sol-' . $solicitud->id . '-' . time() . '-' . $nombre_limpio . '.pdf';

        Storage::put($ruta, file_get_contents($archivo->getRealPath()));

        return $ruta;
    }

    private function eliminarDocumento($ruta)
    {
        if ($ruta && Storage::exists($ruta)) {
            Storage::delete($ruta);
        }
    }

    /**
     * Descarga el PDF sustentatorio de una solicitud (solo usuarios del mismo establecimiento).
     */
    public function descargarDocumento($id)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $movimiento = MovimientoHcSolicitud::where('id', $id)
            ->where('id_establecimiento', Auth::user()->id_establecimiento)
            ->firstOrFail();

        if (empty($movimiento->documento_ruta) || !Storage::exists($movimiento->documento_ruta)) {
            abort(404);
        }

        $nombre = preg_replace('/^sol-\d+-\d+-/', '', basename($movimiento->documento_ruta));

        return response()->download(storage_path('app/' . $movimiento->documento_ruta), $nombre);
    }

    /**
     * Detalle de una solicitud (JSON) para el modal de la lista: datos registrados en el
     * formulario, historias solicitadas y si tiene PDF sustentatorio.
     */
    public function verSolicitud($id)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $s = DB::table('movimiento_hc_solicitud as mhc')
            ->leftJoin('consultorios as se', 'mhc.id_servicio', '=', 'se.id')
            ->where('mhc.id', $id)
            ->select('mhc.*', 'se.nombre as servicio')
            ->first();

        if (!$s) {
            abort(404);
        }

        $catalogos = $this->catalogosSolicitud();
        $nombres = [];
        foreach (['canales_ingreso', 'motivos', 'tipo_documentos', 'lugares_archivo', 'prioridades'] as $clave) {
            foreach ($catalogos[$clave] as $item) {
                $nombres[$clave][$item->id] = $item->nombre;
            }
        }
        $nombre = function ($clave, $valor) use ($nombres) {
            return isset($nombres[$clave][$valor]) ? $nombres[$clave][$valor] : '';
        };
        $fecha = function ($valor, $formato) {
            return $valor ? Carbon::parse($valor)->format($formato) : '';
        };
        $estados = [1 => 'PENDIENTE DE DESPACHO', 2 => 'EN EL SERVICIO', 3 => 'RECEPCIÓN COMPLETA', 4 => 'RECEPCIÓN INCOMPLETA'];

        $modelo_movimiento = new MovimientoHcSolicitud();
        $historias = [];
        foreach ($modelo_movimiento->historiasDeMovimientos([$s->id]) as $h) {
            $historias[] = [
                'nro_hc'    => $h->nro_historia,
                'dni'       => $h->nro_doc_ident,
                'paciente'  => trim($h->paterno . ' ' . $h->materno . ', ' . $h->nombre),
                'estado_hc' => (int) $h->estado_hc,
            ];
        }

        return response()->json([
            'ok'        => true,
            'solicitud' => [
                'nro'                    => 'SOL-' . $s->anio . '-' . $s->numero,
                'estado'                 => isset($estados[$s->estado]) ? $estados[$s->estado] : '',
                'fecha_registro'         => $fecha($s->fecha_hora_registro, 'd-m-Y H:i'),
                'servicio'               => (intval($s->id_servicio) === 0 ? $s->servicio_otro : $s->servicio),
                'solicitante'            => $s->responsable,
                'canal_ingreso'          => $nombre('canales_ingreso', $s->id_canal_ingreso),
                'motivo'                 => $nombre('motivos', $s->id_motivo),
                'tipo_documento'         => $nombre('tipo_documentos', $s->id_documento),
                'nro_documento'          => $s->nro_documento,
                'fecha_documento'        => $fecha($s->fecha_documento, 'd-m-Y'),
                'cantidad_hc'            => $s->cantidad_hc,
                'prioridad'              => $nombre('prioridades', $s->id_prioridad),
                'lugar_archivo'          => $nombre('lugares_archivo', $s->id_lugar_archivo),
                'fecha_requerida'        => $fecha($s->fecha_requerida, 'd-m-Y'),
                'fecha_retorno_prevista' => $fecha($s->fecha_retorno_prevista, 'd-m-Y'),
                'observacion'            => $s->observacion,
                'observacion_archivo'    => $s->observacion_archivo,
                'url_documento'          => $s->documento_ruta ? route('hc_movimiento.descargar_documento', $s->id) : null,
            ],
            'historias' => $historias,
        ]);
    }

    private function mensajesSolicitud()
    {
        return [
            'servicio_otro.required_if' => 'Debe especificar el servicio solicitante.',
            'persona_solicita.required_if' => 'Debe ingresar la persona que solicita.',
            'id_user_responsable.required_unless' => 'Debe seleccionar la persona que solicita.',
            'historias.required' => 'Debe agregar al menos una historia clínica a la solicitud.',
            'historias.min'      => 'Debe agregar al menos una historia clínica a la solicitud.',
            'fecha_documento.before' => 'La fecha del documento no puede ser mayor a la fecha actual.',
        ];
    }

    private function reglasSolicitud()
    {
        return [
            'documento_adjunto'       => 'mimes:pdf|max:5120',
            'id_servicio_solicitante' => 'required|integer',
            'servicio_otro'           => 'required_if:id_servicio_solicitante,0|max:50',
            'persona_solicita'       => 'required_if:id_servicio_solicitante,0|max:150',
            'id_user_responsable'    => 'required_unless:id_servicio_solicitante,0|integer',
            'id_canal_ingreso'        => 'required|integer',
            'id_motivo'               => 'required|integer',
            'id_tipo_documento'       => 'required|integer',
            'nro_documento'           => 'required|max:50',
            'fecha_documento'         => 'required|date_format:d-m-Y|before:' . Carbon::tomorrow()->format('d-m-Y'),
            'cantidad_hc'             => 'required|integer|min:1',
            'id_prioridad'            => 'integer',
            'id_lugar_archivo'        => 'integer',
            'fecha_requerida'         => 'date_format:d-m-Y',
            'fecha_retorno_prevista'  => 'required|date_format:d-m-Y',
            'observacion'             => 'max:500',
            'observacion_archivo'     => 'max:500',
            'accion'                  => 'required|in:enviar,borrador',
            'historias'               => 'required|array|min:1',
        ];
    }

    /**
     * Campos del formulario mapeados a columnas de movimiento_hc_solicitud.
     */
    private function datosSolicitud(Request $request)
    {
        $id_user_responsable = null;
        $responsable = $request->persona_solicita;
        if ((string) $request->id_servicio_solicitante !== '0') {
            $u = DB::table('users')->select('id', 'paterno', 'materno', 'name')->where('id', (int) $request->id_user_responsable)->first();
            if ($u) {
                $id_user_responsable = $u->id;
                $responsable = trim($u->paterno . ' ' . $u->materno . ', ' . $u->name);
            }
        }

        return [
            'id_user_responsable' => $id_user_responsable,
            'id_servicio'        => $request->id_servicio_solicitante,
            'servicio_otro'      => (string) $request->id_servicio_solicitante === '0' ? mb_strtoupper(trim($request->servicio_otro)) : null,
            'id_canal_ingreso'   => $request->id_canal_ingreso,
            'id_motivo'          => $request->id_motivo,
            'id_documento'       => $request->id_tipo_documento,
            'nro_documento'      => mb_strtoupper(trim($request->nro_documento)),
            'fecha_documento'    => Carbon::createFromFormat('d-m-Y', $request->fecha_documento)->format('Y-m-d'),
            'fecha_requerida'    => $request->fecha_requerida ? Carbon::createFromFormat('d-m-Y', $request->fecha_requerida)->format('Y-m-d') : null,
            'fecha_retorno_prevista' => Carbon::createFromFormat('d-m-Y', $request->fecha_retorno_prevista)->format('Y-m-d'),
            'cantidad_hc'        => $request->cantidad_hc,
            'id_prioridad'       => $request->id_prioridad ?: null,
            'id_lugar_archivo'   => $request->id_lugar_archivo ?: null,
            'responsable'        => mb_strtoupper(trim($responsable)),
            'observacion'        => $request->observacion,
            'observacion_archivo' => trim($request->observacion_archivo) ?: null,
            'estado'             => $this->estadoSegunAccion($request->accion),
            'fecha_hora_despacho' => $request->accion === 'enviar' ? Carbon::now() : null,
        ];
    }

    /**
     * Guarda la cabecera de la solicitud en movimiento_hc_solicitud.
     */
    public function guardarSolicitud(Request $request)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $this->validate($request, $this->reglasSolicitud(), $this->mensajesSolicitud());

        $historias = (array) $request->input('historias', []);
        $anio = $request->movimiento_anio ?: date('Y');
        $id_establecimiento = Auth::user()->id_establecimiento;
        $ruta_documento = null;

        try {
            DB::beginTransaction();

            $modelo_movimiento = new MovimientoHcSolicitud();

            if ($mensaje = $this->mensajeHistoriasNoDisponibles($modelo_movimiento->historiasNoDisponibles($historias))) {
                DB::rollback();
                return redirect()->back()->withInput()->withFlashDanger($mensaje);
            }

            $objMovimiento = new MovimientoHcSolicitud();
            $objMovimiento->fill($this->datosSolicitud($request));
            $objMovimiento->id_establecimiento = $id_establecimiento;
            $objMovimiento->anio = $anio;
            $objMovimiento->numero = $modelo_movimiento->generarNroMovimiento($id_establecimiento, $anio);
            $objMovimiento->fecha_hora_registro = $request->fecha_hora_actual_sin_formato;
            $objMovimiento->created_us = Auth::user()->id;
            $objMovimiento->save();

            if ($request->hasFile('documento_adjunto')) {
                $ruta_documento = $this->guardarDocumento($request->file('documento_adjunto'), $objMovimiento);
                $objMovimiento->documento_ruta = $ruta_documento;
                $objMovimiento->save();
            }

            $modelo_movimiento->actualizarEstadoHistorias($historias, $objMovimiento->estado, $objMovimiento->id);

            $this->registrarDetalle($objMovimiento->id, $historias, $objMovimiento->estado);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $this->eliminarDocumento($ruta_documento);
            Log::error('ERROR EN HC MOVIMIENTO AL GUARDAR SOLICITUD: ' . $e->getMessage());
            return redirect()->back()->withInput()->withFlashDanger('No se pudo registrar la solicitud.');
        }

        return redirect()->route('hc_movimiento.lista_solicitudes')
            ->withFlashSuccess('Solicitud SOL-' . $objMovimiento->anio . '-' . $objMovimiento->numero . ' registrada correctamente.');
    }

    /**
     * Formulario de ingreso de historias clínicas a Archivo (devolución).
     */
    public function formRecepcion()
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $tipos_documento_devolucion = DB::table('movimiento_hc_tipo_documento')
            ->select('id', 'nombre')
            ->where('tipo', 'D')
            ->where('id_estado_reg', 1)
            ->orderBy('id')
            ->get();

        $user_id_establecimiento = Auth::user()->id_establecimiento;
        $model_consultorio = new Consultorio();
        $servicios = $model_consultorio->ShowAllConsultoriosSubConsultorio($user_id_establecimiento);

        $fecha_now = Carbon::now();
        $fecha_hora_actual = $fecha_now->format('d-m-Y H:i');
        $fecha_hora_actual_sin_formato = $fecha_now->format('Y-m-d H:i');

        $usuario_nombre_completo = Auth::user()->paterno . " " . Auth::user()->materno . ", " . Auth::user()->name;

        $ingreso_anio = $fecha_now->format('Y');
        $modelo_recepcion = new MovimientoHcRecepcion();
        $ingreso_nro_nuevo = $modelo_recepcion->generarNroIngreso($user_id_establecimiento, $ingreso_anio);
        $ingreso_nro = $modelo_recepcion->formatoNroIngreso($ingreso_anio, $ingreso_nro_nuevo);

        return view('frontend.hc.movimiento.form_recepcion', compact(
            'tipos_documento_devolucion',
            'servicios',
            'ingreso_nro',
            'ingreso_anio',
            'fecha_hora_actual',
            'fecha_hora_actual_sin_formato',
            'usuario_nombre_completo'
        ));
    }

    private function reglasRecepcion()
    {
        return [
            'id_movimiento_hc_solicitud'    => 'required|integer',
            'fecha_hora_actual_sin_formato' => 'required|date_format:Y-m-d H:i',
            'persona_entrega'               => 'required|max:150',
            'id_tipo_documento'             => 'required|integer',
            'nro_documento'                 => 'required|max:50',
            'observacion'                   => 'max:200',
            'historias'                     => 'required|array',
        ];
    }

    /**
     * Guarda la cabecera del ingreso de historias a Archivo en movimiento_hc_recepcion.
     */
    public function guardarRecepcion(Request $request)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $this->validate($request, $this->reglasRecepcion(), [
            'id_movimiento_hc_solicitud.required' => 'Debe buscar la salida a recepcionar.',
            'historias.required'                  => 'Debe marcar al menos una historia como devuelta.',
        ]);

        $id_establecimiento = Auth::user()->id_establecimiento;
        $anio_ingreso = $request->ingreso_anio ?: date('Y');

        $salida = MovimientoHcSolicitud::where('id', $request->id_movimiento_hc_solicitud)
            ->where('id_establecimiento', $id_establecimiento)
            ->whereIn('estado', [2, 4])
            ->whereNotNull('fecha_hora_despacho')
            ->first();

        if (is_null($salida)) {
            return redirect()->back()->withInput()->withFlashDanger('La salida seleccionada no es válida para registrar un ingreso.');
        }

        try {
            DB::beginTransaction();

            $modelo_recepcion = new MovimientoHcRecepcion();

            $objRecepcion = new MovimientoHcRecepcion();
            $objRecepcion->id_movimiento_hc_solicitud = $salida->id;
            $objRecepcion->id_establecimiento = $id_establecimiento;
            $objRecepcion->anio_ingreso = $anio_ingreso;
            $objRecepcion->nro_ingreso = $modelo_recepcion->generarNroIngreso($id_establecimiento, $anio_ingreso);
            $objRecepcion->recepcion_at = $request->fecha_hora_actual_sin_formato;
            $objRecepcion->recepcion_us = Auth::user()->id;
            $objRecepcion->persona_devuelve = mb_strtoupper(trim($request->persona_entrega));
            $objRecepcion->id_documento_devolucion = $request->id_tipo_documento;
            $objRecepcion->nro_documento_devolucion = mb_strtoupper(trim($request->nro_documento));
            $objRecepcion->observacion_recepcion = trim($request->observacion) ?: null;
            $objRecepcion->id_estado_reg = 1;
            $objRecepcion->created_us = Auth::user()->id;
            $objRecepcion->save();

            $modelo_movimiento = new MovimientoHcSolicitud();
            $pendientes = $modelo_movimiento->idsHistoriasPendientes($salida->id);
            $devueltas = array_values(array_intersect($pendientes, array_map('intval', (array) $request->input('historias', []))));
            $no_devueltas = array_values(array_diff($pendientes, $devueltas));

            if (empty($devueltas)) {
                DB::rollback();
                return redirect()->back()->withInput()->withFlashDanger('Las historias marcadas no pertenecen a la salida o ya fueron devueltas.');
            }

            $modelo_movimiento->marcarHistoriasDevueltas($salida->id, $devueltas, $objRecepcion->id, Auth::user()->id);
            $modelo_movimiento->marcarHistoriasNoDevueltas($salida->id, $no_devueltas, 'No devuelta');

            // 3 = recepcion completa (todas devueltas) · 4 = recepcion incompleta (quedan HC por devolver)
            $salida->estado = empty($no_devueltas) ? 3 : 4;
            $salida->updated_us = Auth::user()->id;
            $salida->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('ERROR EN HC MOVIMIENTO AL GUARDAR RECEPCION: ' . $e->getMessage());
            return redirect()->back()->withInput()->withFlashDanger('No se pudo registrar el ingreso.');
        }

        return redirect()->route('hc_movimiento.form_recepcion')
            ->withFlashSuccess('Ingreso ' . $modelo_recepcion->formatoNroIngreso($objRecepcion->anio_ingreso, $objRecepcion->nro_ingreso) . ' registrado correctamente.');
    }

    /**
     * Filas del panel "Ingresos registrados": ingresos del establecimiento filtrados por fecha y servicio.
     */
    public function listarIngresos(Request $request)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $filtros = array(
            'id_servicio'  => $request->get('id_servicio'),
            'id_solicitud' => $request->get('id_solicitud'),
        );
        foreach (array('desde', 'hasta') as $campo) {
            try {
                $filtros[$campo] = Carbon::createFromFormat('d-m-Y', $request->get($campo))->format('Y-m-d');
            } catch (\Exception $e) {
                $filtros[$campo] = null;
            }
        }

        $modelo_recepcion = new MovimientoHcRecepcion();
        $ingresos = $modelo_recepcion->listarIngresos(Auth::user()->id_establecimiento, $filtros);

        return response()->json(['ok' => true, 'ingresos' => $this->ingresosParaJson($ingresos)]);
    }

    private function ingresosParaJson($ingresos)
    {
        $modelo_recepcion = new MovimientoHcRecepcion();
        $tipos_documento = DB::table('movimiento_hc_tipo_documento')
            ->where('tipo', 'D')
            ->lists('nombre', 'id');
        $filas = [];

        $ids_ingresos = [];
        foreach ($ingresos as $r) {
            $ids_ingresos[] = $r->id;
        }
        $historias_por_ingreso = [];
        foreach ($modelo_recepcion->historiasDeIngresos($ids_ingresos) as $h) {
            $historias_por_ingreso[$h->id_movimiento_hc_recepcion][] = [
                'nro_hc'   => $h->nro_historia,
                'dni'      => $h->nro_doc_ident,
                'paciente' => trim($h->paterno . ' ' . $h->materno . ', ' . $h->nombre),
            ];
        }


        foreach ($ingresos as $r) {
            $filas[] = [
                'id'          => $r->id,
                'historias'   => isset($historias_por_ingreso[$r->id]) ? $historias_por_ingreso[$r->id] : [],
                'nro_ingreso' => $modelo_recepcion->formatoNroIngreso($r->anio_ingreso, $r->nro_ingreso),
                'fecha'       => Carbon::parse($r->recepcion_at)->format('d-m-Y H:i'),
                'solicitud'   => 'SOL-' . $r->solicitud_anio . '-' . $r->solicitud_numero,
                'servicio'    => $r->servicio,
                'entrego'     => $r->persona_devuelve,
                'recibio'     => trim($r->user_paterno . ' ' . $r->user_materno . ', ' . $r->user_nombre),
                'despachadas' => (int) $r->despachadas,
                'devueltas'   => (int) $r->devueltas,
                'pendientes'  => (int) $r->despachadas - (int) $r->devueltas_acumuladas,
                'cargo'       => trim((isset($tipos_documento[$r->id_documento_devolucion]) ? $tipos_documento[$r->id_documento_devolucion] : '') . ' ' . $r->nro_documento_devolucion),
            ];
        }

        return $filas;
    }

    /**
     * Busca la salida (solicitud enviada y despachada) a recepcionar por N° de solicitud,
     * N° de HC o DNI. Responde JSON con los datos de la salida y sus historias.
     */
    public function buscarSalida(Request $request)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $termino = trim($request->get('q', ''));

        if (mb_strlen($termino) < 3) {
            return response()->json(['ok' => false, 'mensaje' => 'Ingrese al menos 3 caracteres.']);
        }

        $modelo_movimiento = new MovimientoHcSolicitud();
        $solicitudes = $modelo_movimiento->listarSolicitudes(Auth::user()->id_establecimiento, array(
            'estado' => [2, 4],
            'texto'  => $termino,
        ));

        $salida = null;
        foreach ($solicitudes as $s) {
            if (!empty($s->fecha_hora_despacho)) {
                $salida = $s;
                break;
            }
        }

        if (is_null($salida)) {
            // Distingue por qué no hay salida a recepcionar
            $existentes = $modelo_movimiento->listarSolicitudes(Auth::user()->id_establecimiento, array('texto' => $termino));
            if (count($existentes) === 0) {
                $mensaje = 'No se ha encontrado solicitud con el dato buscado.';
            } else {
                $estado = $existentes[0]->estado;
                if ($estado == 1) {
                    $mensaje = 'La solicitud buscada aún no ha sido despachada.';
                } elseif ($estado == 3) {
                    $mensaje = 'La solicitud buscada ya fue recepcionada.';
                }
            }
            return response()->json(['ok' => false, 'mensaje' => $mensaje]);
        }

        $catalogos = $this->catalogosSolicitud();
        $nombre_motivo = '';
        foreach ($catalogos['motivos'] as $motivo) {
            if ($motivo->id == $salida->id_motivo) {
                $nombre_motivo = $motivo->nombre;
            }
        }

        $fecha_despacho = Carbon::parse($salida->fecha_hora_despacho);
        $dias_fuera = $fecha_despacho->copy()->startOfDay()->diffInDays(Carbon::now()->startOfDay());

        $historias = [];
        foreach ($modelo_movimiento->historiasDeMovimientos([$salida->id], 2) as $h) {
            $historias[] = [
                'id'           => $h->id,
                'nro_hc'       => $h->nro_historia,
                'dni'          => $h->nro_doc_ident,
                'paciente'     => trim($h->paterno . ' ' . $h->materno . ', ' . $h->nombre),
                'fecha_salida' => $fecha_despacho->format('d-m-Y'),
                'dias_fuera'   => $dias_fuera,
            ];
        }

        $modelo_recepcion = new MovimientoHcRecepcion();
        $ingresos = $this->ingresosParaJson($modelo_recepcion->listarIngresos(Auth::user()->id_establecimiento, array(
            'id_solicitud' => $salida->id,
        )));

        return response()->json([
            'ok'        => true,
            'ingresos'  => $ingresos,
            'solicitud' => [
                'id'           => $salida->id,
                'nro'          => 'SOL-' . $salida->anio . '-' . $salida->numero,
                'servicio'     => $salida->servicio_solicitante,
                'solicitante'  => $salida->responsable,
                'motivo'       => $nombre_motivo,
                'fecha_salida' => $fecha_despacho->format('d-m-Y H:i'),
            ],
            'historias' => $historias,
        ]);
    }

}
