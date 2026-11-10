<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Farmacia;
use App\Models\Pabellone;
use App\Models\Piso;
use App\Models\Asignacion_servicio;
use App\Models\Consultorio;
use App\Models\Cuarto;
use App\Models\Cama;
use Session;
use DB;
use App;
use Illuminate\Support\Facades\Route;
use Auth;
use Carbon\Carbon;

class CensoCamaController extends Controller
{
    function __construct()
    {
        $this->middleware('auth'); 
    } 

    public function censo() {
    
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        if(Auth::user()->tipo_registro || Auth::user()->flagestablecimientos == 1):

            $id_user = Auth::user()->id;
            
            $id_establecimiento_user = Auth::user()->id_establecimiento;
        
            $cama = new Cama();
            $establecimientos = $cama->GetEstablecimientos($id_establecimiento_user);
            
            $tipo_atenciones = $this->obtener_atencion_por_idestablecimiento($id_establecimiento_user);
            
            if (count($tipo_atenciones) > 0) {
            
                $servicios = $this->obtener_servicio_por_idestablecimiento_tipoatencion2($id_establecimiento_user, $tipo_atenciones[0]->nombre_atencion);
                
                $sumas = $this->getResumenCamas($id_establecimiento_user, null);
                
                $total_cama = $sumas['total'];
                $camas_disponibles_cant = 0; 
                $camas_ocupadas_cant = $sumas['c_ocupadas'];
                $camas_libres_cant = $sumas['c_libres'];
                $camas_bloqueadas_cant = $sumas['c_bloqueadas'];
                
                $estadistica_camas_todos_servicios = $cama->GetTodosServiciosConEstadisticaCamas($id_establecimiento_user, $tipo_atenciones[0]->nombre_atencion,"");
                
                $result = [];
                foreach ($estadistica_camas_todos_servicios as $row) {
                    $idpabellon = $row->idpabellon;
        
                    if (!isset($result[$idpabellon])) {
                        $result[$idpabellon] = clone $row;
                    } else {
                        // Sumar los valores numéricos
                        $result[$idpabellon]->sin_situacion += $row->sin_situacion;
                        $result[$idpabellon]->total += $row->total;
                        $result[$idpabellon]->c_disponibles += $row->c_disponibles;
                        $result[$idpabellon]->c_ocupadas += $row->c_ocupadas;
                        $result[$idpabellon]->c_libres += $row->c_libres;
                        $result[$idpabellon]->c_bloqueadas += $row->c_bloqueadas;
                    }
                }
                $result = array_values($result);
                $estadistica_camas_todos_servicios = $result;
                
                $array_censo_camas = [];
                foreach ($estadistica_camas_todos_servicios as $key => $value) {
                    if((int)$value->total == 0){
                        continue;
                    }
                    array_push($array_censo_camas,$value);
                }
                $estadistica_camas_todos_servicios = $array_censo_camas;
                
                $date = Carbon::now();
                $fecha_formato = $date->format('d-m-Y');
                $hora_formato = $date->format('H:i');

                $tipo_atencion_seleccionado = $tipo_atenciones[0]->nombre_atencion;
                $nombre_servicio = "TODOS";

                return view('frontend.camas.censo_camas', compact(
                    'id_user', 
                    'id_establecimiento_user', 
                    'fecha_formato', 
                    'hora_formato', 
                    'total_cama', 
                    'camas_disponibles_cant', 
                    'camas_ocupadas_cant', 
                    'camas_libres_cant', 
                    'camas_bloqueadas_cant', 
                    'establecimientos',
                    'tipo_atenciones',
                    'servicios',
                    'estadistica_camas_todos_servicios',
                    'tipo_atencion_seleccionado',
                    'nombre_servicio',
                    'sumas'
                ));
            } else {
                return redirect('/dashboard')
                    ->withInput()
                    ->withErrors(array('No existe información de camas en el establecimiento donde se encuentra.'));
            }
        else:
            return redirect('/dashboard');
        endif;
    }

    public function obtener_atencion_por_idestablecimiento($id_establecimiento) {
        $cama = new Cama;
		$tipo_atenciones = $cama->GetAtencionPorIdEstablecimiento($id_establecimiento);
        return $tipo_atenciones;
    }

    public function obtener_servicio_por_idestablecimiento_tipoatencion2($id_establecimiento, $tipo_atencion) {
        $cama = new Cama;
        $servicios = $cama->GetServicioPorIdEstablecimientoYTipoAtencion2($id_establecimiento, $tipo_atencion);
        return $servicios;
    }

    public function obtener_servicio_cama_consultorio($id_establecimiento,$id_consultorio, $tipo_atencion)
    {
        $camas = DB::select("
        SELECT * FROM sp_listar_camas_rotacion(?, ?, ?)", [$id_establecimiento,$tipo_atencion,$id_consultorio]);
        return response()->json($camas);
    }

    public function obtener_upss($idConsultorioActual)
    {
        $upss = new Cama;
        return $upss->listarUpsses($idConsultorioActual);
    }

    public function obtener_especialidades($id_upss)
    {
        $cama = new Cama;
        $especialidades = $cama->listarEspecialidadesPorUpss($id_upss);
        return $especialidades;
    }

    public function obtener_upss_cama($id_consultorio)
    {
        $cama = new Cama;
        $resultado = $cama->listarCamasPorConsultorio($id_consultorio);
        return $resultado;
    }

    public function medicosPorConsultorio($id)
    {
        $cama = new Cama;
        $resultado = $cama->medicosPorConsultorio($id);
        return $resultado;
    }

    public function obtener_situacion_cama()
    {
        $cama = new Cama;
        $situacion_cama = $cama->getSituacionCamas();
        return $situacion_cama;
    }

    public function censo_camas_filtro(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $id_user = $request->id_user;
        $id_establecimiento = $request->establecimiento;
        $tipo_atencion = $request->tipo_atencion;
        $id_servicio = $request->servicio_se;
        $id_establecimiento_user = Auth::user()->id_establecimiento;
        
        $sumas = $this->getResumenCamas($id_establecimiento, $id_servicio ?: null);

        $date = Carbon::now();
        $fecha_formato = $date->format('d-m-Y');
        $hora_formato = $date->format('H:i');
        
        $cama = new Cama();
        $establecimientos = $cama->GetEstablecimientos($id_establecimiento_user);
        $tipo_atenciones = $this->obtener_atencion_por_idestablecimiento($id_establecimiento);
        $tipo_atencion_seleccionado = $tipo_atencion;
        
        if ($id_servicio != "") {
            $servicio = new Consultorio();
            $nombre_servicio = $servicio->GetNombreById($id_servicio)->nombre;
        } else {
            $nombre_servicio = "TODOS";
        }
        
        return view('frontend.camas.censo_camas', compact(
            'id_user', 
            'id_establecimiento_user', 
            'fecha_formato', 
            'hora_formato', 
            'establecimientos',
            'tipo_atenciones',
            'id_establecimiento',
            'tipo_atencion',
            'id_servicio',
            'tipo_atencion_seleccionado',
            'nombre_servicio',
            'sumas'
        ));
    }

    private function getResumenCamas($id_establecimiento, $id_consultorio = null) {
        try {
            $query = "SELECT * FROM sp_resumen_camas(?, ?)";
            $result = DB::select($query, [$id_establecimiento, $id_consultorio]);
            
            if (empty($result)) {
                return [
                    'total' => 0,
                    'c_libres' => 0,
                    'c_ocupadas' => 0,
                    'c_bloqueadas' => 0,
                    'c_sin_situacion' => 0,
                    'c_aislados' => 0,
                    'c_cuidados_complejos' => 0,
                    'c_postquirurgicos' => 0
                ];
            }
            
            $data = $result[0];
            return [
                 'total' => isset($data->total_camas) ? $data->total_camas : 0,
                'c_libres' => isset($data->camas_libres) ? $data->camas_libres : 0,
                'c_ocupadas' => isset($data->camas_ocupadas) ? $data->camas_ocupadas : 0,
                'c_bloqueadas' => isset($data->camas_reparacion) ? $data->camas_reparacion : 0,
                'c_sin_situacion' => isset($data->camas_malogrado) ? $data->camas_malogrado : 0,
                'c_aislados' => isset($data->camas_aislados) ? $data->camas_aislados : 0,
                'c_cuidados_complejos' => isset($data->camas_cuidados_complejos) ? $data->camas_cuidados_complejos : 0,
                'c_postquirurgicos' => isset($data->camas_postquirurgicos) ? $data->camas_postquirurgicos : 0
            ];
        } catch (\Exception $e) {
            \Log::error('Error en getResumenCamas: ' . $e->getMessage());
            return [
                'total' => 0,
                'c_libres' => 0,
                'c_ocupadas' => 0,
                'c_bloqueadas' => 0,
                'c_sin_situacion' => 0,
                'c_aislados' => 0,
                'c_cuidados_complejos' => 0,
                'c_postquirurgicos' => 0
            ];
        }
    }

    public function GetCensoCamaForFiltros(Request $request){
        $request->establecimiento;
        $cama = new Cama();
        $data = $cama->GetServicioPorIdEstablecimientoYTipoAtencion2($request->establecimiento,$request->atencion);
        return response()->json($data);
    }

    public function listado_censo_camas(Request $request)
    {
        $id_establecimiento = $request->input('id_establecimiento');
        $id_servicio        = $request->input('id_servicio') ?: null;
        $nombre_atencion    = $request->input('nombre_atencion') ?: null;

        $id_tipo_atencion = null;
        if ($nombre_atencion === "HOSPITALIZACION") {
            $id_tipo_atencion = 3;
        } elseif ($nombre_atencion === "EMERGENCIA") {
            $id_tipo_atencion = 2;
        }

        $search = $request->input('search');
        $busqueda_paciente = isset($search['value']) ? trim($search['value']) : null;

        $start  = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);

        $params = [
            $id_establecimiento,
            $id_tipo_atencion,
            $id_servicio,
            $busqueda_paciente,
            $length,
            $start
        ];

        $cama = new Cama();
        $censos = $cama->listar_censo_camas($params);

        if (!empty($busqueda_paciente)) {
            $censos_filtrados = [];
            foreach ($censos as $c) {
                if (!empty($c->paciente)) {
                    $censos_filtrados[] = $c;
                }
            }
            $censos = array_values($censos_filtrados);
        }

        $data = [];
        $total = !empty($censos) ? $censos[0]->totalrows : 0;

        foreach ($censos as $c) {
            $fechaCensoCompleta = null;

            if (!empty($c->fecha_censo) && !empty($c->hora_censo)) {
                $fechaString = trim("{$c->fecha_censo} {$c->hora_censo}");

                try {
                    $fechaCensoCompleta = Carbon::createFromFormat('d/m/Y h:i:s A', $fechaString);
                } catch (\Exception $e1) {
                    try {
                        $fechaCensoCompleta = Carbon::createFromFormat('d/m/Y H:i:s', $fechaString);
                    } catch (\Exception $e2) {
                        try {
                            $fechaCensoCompleta = Carbon::parse($fechaString);
                        } catch (\Exception $e3) {
                            $fechaCensoCompleta = null;
                        }
                    }
                }
            }

            $tiempoTranscurrido = '';
            if ($fechaCensoCompleta) {
                $ahora = Carbon::now();
                $dias = $fechaCensoCompleta->diffInDays($ahora);
                $horas = $fechaCensoCompleta->copy()->addDays($dias)->diffInHours($ahora);
                $tiempoTranscurrido = "{$dias} día" . ($dias != 1 ? 's' : '') . ", {$horas} hora" . ($horas != 1 ? 's' : '');
            }

            $data[] = [
                'id_cama'               => $c->id_cama,
                'id_asegurado'          => $c->id_asegurado,
                'servicio_origen'       => $c->servicio_origen,
                'id_servicio'           => $c->id_servicio,
                'nombre_cama'           => $c->nombre_cama,
                'tipo_documento'        => $c->tipo_documento,
                'paciente'              => $c->paciente,
                'paciente_datos'        => $c->paciente_datos,
                'edad'                  => $c->edad,
                'sexo'                  => $c->sexo,
                'servicio_encargado'    => $c->servicio_encargado,
                'servicio_destino'      => $c->servicio_destino,
                'fecha_ingreso'         => $c->fecha_ingreso,
                'hora_ingreso'          => $c->hora_ingreso,
                'fecha_censo'           => $c->fecha_censo,
                'hora_censo'            => $c->hora_censo,
                'tiempo_transcurrido'   => $tiempoTranscurrido,
                'fecha_traslado'        => $c->fecha_traslado,
                'hora_traslado'         => $c->hora_traslado,
                'nombre_pabellon'       => $c->nombre_pabellon,
                'nombre_piso'           => $c->nombre_piso,
                'nombre_cuarto'         => $c->nombre_cuarto,
                'estado_cama'           => $c->estado_cama,
                'descripcion_estado'    => $c->descripcion_estado,
                'tipo_modulo'           => $c->tipo_modulo,
                'establecimiento'       => $c->establecimiento,
                'id_consultorio'        => $c->id_consultorio,
                'id_ext'                => $c->id_ext,
                'tipo_atencion'         => $c->tipo_atencion,
                'id_estado'             => $c->id_estado, 
                'nombre_upss'           => $c->nombre_upss, 
                'prestacion'            => $c->prestacion,
                'id_evaluacion'         => $c->id_evaluacion,
                'id_servicio_encargado' => $c->id_servicio_encargado,
                'id_upss_encargado'     => $c->id_upss_encargado,
            ];
        }

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data
        ]);
    }
 
    public function registrar_censos(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }

        $censos = $request->input('censos', []);
        $id_user = Auth::user()->id;

        DB::beginTransaction();

        try {
            foreach ($censos as $dato) {
                $dato_censo = [];
                foreach ($dato as $key => $value) {
                    $dato_censo[$key] = ($value === '' || $value === 'null') ? null : $value;
                }

                DB::table('censos')->insert([
                    'id_asegurado'        => isset($dato_censo['id_asegurado']) ? $dato_censo['id_asegurado'] : null,
                    'id_cama'             => isset($dato_censo['id_cama']) ? $dato_censo['id_cama'] : null,
                    'nro_cama'            => isset($dato_censo['nombre_cama']) ? $dato_censo['nombre_cama'] : null,
                    'id_servicio_actual'  => isset($dato_censo['servicio_origen']) ? $dato_censo['servicio_origen'] : null,
                    'fecha_ingreso'       => isset($dato_censo['fecha_ingreso']) && $dato_censo['fecha_ingreso'] != '' ? $dato_censo['fecha_ingreso'] : Carbon::now()->toDateString(),
                    'hora_ingreso'        => isset($dato_censo['hora_ingreso']) && $dato_censo['hora_ingreso'] != '' ? $dato_censo['hora_ingreso'] : Carbon::now()->toTimeString(),
                    'fecha_censo'         => Carbon::now()->toDateString(),
                    'hora_censo'          => Carbon::now()->toTimeString(),
                    'control_at'          => isset($dato_censo['control_at']) && is_numeric($dato_censo['control_at']) ? $dato_censo['control_at'] : 0,
                    'control_anterior_at' => isset($dato_censo['control_anterior_at']) && is_numeric($dato_censo['control_anterior_at']) ? $dato_censo['control_anterior_at'] : 0,
                    'id_servicio'         => isset($dato_censo['id_servicio']) ? $dato_censo['id_servicio'] : null,
                    'nombre_piso'         => isset($dato_censo['nombre_piso']) ? $dato_censo['nombre_piso'] : null,
                    'nombre_pabellon'     => isset($dato_censo['nombre_pabellon']) ? $dato_censo['nombre_pabellon'] : null,
                    'nombre_cuarto'       => isset($dato_censo['nombre_cuarto']) ? $dato_censo['nombre_cuarto'] : null,
                    'created_us'          => $id_user,
                    'estado'              => 1,
                    'created_at'          => Carbon::now(),
                ]);

            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Censo registrado correctamente.',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar censos: ' . $e->getMessage()
            ], 500);
        }
    }

    // public function registrar_rotacion_cama(Request $request)
    // {
    //     if (!Auth::check()) {
    //         return redirect('/auth/login');
    //     }

    //     try {
    //         DB::beginTransaction();

    //         $id_user          = Auth::user()->id;
    //         $idext            = $request->input('id_ext');
    //         $tipoAtencion     = $request->input('tipo_atencion');
    //         $idCamaAnterior   = $request->input('cama_anterior');
    //         $idCamaActual     = $request->input('cama_actual');
    //         $idServicioActual = $request->input('id_servicio_actual');
    //         $observacion      = $request->input('observacion');
    //         $fechaRotar       = $request->input('fecha_rotar');

    //         DB::table('censo_detalles')->insert([
    //             'id_accion'          => 1,
    //             'id_cama_anterior'   => $idCamaAnterior,
    //             'id_cama_actual'     => $idCamaActual,
    //             'id_servicio_actual' => $idServicioActual,
    //             'observacion'        => $observacion,
    //             'id_estado_cama'     => 1,
    //             'flag_liberar_cama'  => 0,
    //             'created_at'         => Carbon::now(),
    //             'created_us'         => $id_user,
    //             'fecha_rotar'        => $fechaRotar,
    //         ]);

    //         if ($tipoAtencion == 3) {

    //             DB::table('hospitalizaciones')
    //                 ->where('id', $idext)
    //                 ->update([
    //                     'id_cama_estado' => $idCamaActual,
    //                     'updated_at'     => Carbon::now(),
    //                 ]);

    //         } elseif ($tipoAtencion == 2) {

    //             DB::table('emergencias')
    //                 ->where('id', $idext)
    //                 ->update([
    //                     'cama_estado' => $idCamaActual,
    //                     'updated_at'  => Carbon::now(),
    //                 ]);

    //         } else {
    //             DB::rollBack();
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => "Tipo de atención no válido: {$tipoAtencion}"
    //             ]);
    //         }

    //         $CensoRequest = new Request([
    //             "censos" => [
    //                 [
    //                     "id_cama"            => $idCamaActual,
    //                     "servicio_origen"    => $idServicioActual,
    //                     "id_servicio"        => $idServicioActual,
    //                 ]
    //             ]
    //         ]);

    //         $this->registrar_censos($CensoRequest);

    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Rotación de cama registrada correctamente'
    //         ]);

    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error al registrar rotación de cama: ' . $e->getMessage()
    //         ]);
    //     }
    // }

    public function registrar_rotacion_cama(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }

        try {

            DB::beginTransaction();

            $id_user          = Auth::user()->id;
            $idext            = $request->input('id_ext');
            $tipoAtencion     = $request->input('tipo_atencion');
            $idCamaAnterior   = $request->input('cama_anterior');
            $idCamaActual     = $request->input('cama_actual');
            $idServicioActual = $request->input('id_servicio_actual');
            $observacion      = $request->input('observacion');
            $fechaRotar       = $request->input('fecha_rotar');

            $id_prestacion = $request->input('id_prestacion');
            $id_evaluacion = $request->input('id_evaluacion');

            $id_ext_destino        = $request->input('id_ext_destino', 0);
            $id_evaluacion_destino = $request->input('id_evaluacion_destino', 0);

            DB::table('censo_detalles')->insert([
                'id_accion'          => 1,
                'id_cama_anterior'   => $idCamaAnterior,
                'id_cama_actual'     => $idCamaActual,
                'id_servicio_actual' => $idServicioActual,
                'observacion'        => $observacion,
                'id_estado_cama'     => 1,
                'flag_liberar_cama'  => 0,
                'created_at'         => Carbon::now(),
                'created_us'         => $id_user,
                'fecha_rotar'        => $fechaRotar
            ]);

            $resultado = DB::select("
                SELECT * FROM sp_actualizar_estado_cama_censo_camas(
                    ?, ?, ?, ?, ?, ?, ?, ?
                )
            ", [
                $tipoAtencion,
                $idext,
                $idCamaActual,
                $id_prestacion,
                $id_evaluacion,
                $id_user,
                $id_ext_destino,
                $id_evaluacion_destino
            ]);

            $status  = $resultado[0]->status;
            $message = $resultado[0]->message;

            if ($status !== 'SUCCESS') {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => $message
                ]);
            }

            $CensoRequest = new Request([
                'censos' => [
                    [
                        'id_cama'         => $idCamaActual,
                        'servicio_origen' => $idServicioActual,
                        'id_servicio'     => $idServicioActual
                    ]
                ]
            ]);

            $this->registrar_censos($CensoRequest);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al registrar rotación de cama: '.$e->getMessage()
            ]);
        }
    }

    public function transferencia_cama(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }

        try {
            DB::beginTransaction();

            $id_user             = Auth::user()->id;
            $idext               = $request->input('id_ext');
            $TipoAtencion        = $request->input('tipo_atencion');
            $idEstablecimiento   = $request->input('id_establecimiento');

            $idUpssEncargado = $request->input('id_upss_encargado');
            $idUpssEncargado = (isset($idUpssEncargado) && trim($idUpssEncargado) !== '') ? (int) $idUpssEncargado : null;

            $idServicioEncargado = $request->input('id_servicio_encargado');
            $idServicioEncargado = (isset($idServicioEncargado) && trim($idServicioEncargado) !== '') ? (int) $idServicioEncargado : null;

            $id_prestacion       = $request->input('id_prestacion');
            $id_evaluacion       = $request->input('id_evaluacion');
            $idCamaActual        = $request->input('id_cama_actual');
            $idCamaAnterior      = $request->input('cama_anterior');
            $idServicioActual    = $request->input('id_servicio_actual');
            $observacion         = $request->input('observacion');
            $fechaRotar          = $request->input('fecha_rotar');
            $idMedicoOrdena      = $request->input('id_medico_ordena');

            DB::table('censo_detalles')->insert([
                'id_accion'          => 2,
                'id_cama_anterior'   => $idCamaAnterior,
                'id_cama_actual'     => $idCamaActual,
                'id_servicio_actual' => $idServicioActual,
                'observacion'        => $observacion,
                'id_medico_ordena'   => $idMedicoOrdena,
                'id_estado_cama'     => 1,
                'flag_liberar_cama'  => 0,
                'created_at'         => Carbon::now(),
                'created_us'         => $id_user,
                'fecha_rotar'        => $fechaRotar,
            ]);

            DB::select("
                SELECT sp_actualizar_estado_cama_censo_camas_transferencia( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) AS resultado
            ", [
                $TipoAtencion,        // p_tipo_atencion
                $idext,               // p_id_ext
                $idUpssEncargado,    // p_id_upss_encargado 
                $idServicioEncargado,   // p_id_servicio_encargado 
                $idServicioActual,    // p_id_servicio_cama
                $idCamaActual,        // p_id_cama
                $idEstablecimiento,   // p_id_establecimiento
                $id_prestacion,        // p_id_prestacion
                $id_evaluacion,        // p_id_evolucion
                $id_user              // p_user          
            ]);

            $CensoRequest = new Request([
                "censos" => [
                    [
                        "id_cama"            => $idCamaActual,
                        "servicio_origen"    => $idServicioActual,
                        "id_servicio"        => $idServicioActual,
                    ]
                ]
            ]);

            $this->registrar_censos($CensoRequest);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transferencia registrada correctamente.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al registrar la transferencia: ' . $e->getMessage()
            ]);
        }
    }

    // public function liberar_cama(Request $request)
    // {
    //     if (!Auth::check()) {
    //         return redirect('/auth/login');
    //     }

    //     try {
    //         DB::beginTransaction();

    //         $id_user = Auth::user()->id;

    //         DB::table('censo_detalles')->insert([
    //             'id_accion'          => 3,  
    //             'id_cama_actual'     => $request->input('id_cama_actual'),
    //             'id_servicio_actual' => $request->input('id_servicio_actual'),
    //             'observacion'        => $request->input('observacion'),
    //             'id_estado_cama'     => 1, 
    //             'flag_liberar_cama'  => 1,
    //             'created_at'         => Carbon::now(),
    //             'created_us'         => $id_user
    //         ]);

    //         DB::select("SELECT fn_actualizar_cama_libre(?, ?) AS resultado",
    //             [
    //               $request->input('id_cama_actual'),
    //               $id_user
    //             ]);

    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Cama liberada correctamente.'
    //         ]);

    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error al liberar la cama: ' . $e->getMessage()
    //         ]);
    //     }
    // }


    public function liberar_cama(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }

        try {
            DB::beginTransaction();

            $id_user        = Auth::user()->id;
            $idCamaActual   = $request->input('id_cama_actual');
            $idServicio     = $request->input('id_servicio_actual');
            $observacion    = $request->input('observacion');

            DB::table('censo_detalles')->insert([
                'id_accion'          => 3,  
                'id_cama_actual'     => $idCamaActual,
                'id_servicio_actual' => $idServicio,
                'observacion'        => $observacion,
                'id_estado_cama'     => 1, 
                'flag_liberar_cama'  => 1,
                'created_at'         => Carbon::now(),
                'created_us'         => $id_user
            ]);

            DB::select("
                SELECT fn_actualizar_cama_libre(?, ?) AS resultado
            ", [
                $idCamaActual,
                $id_user 
            ]);

            $CensoRequest = new Request([
                "censos" => [
                    [
                        "id_cama"         => $idCamaActual,
                        "servicio_origen" => $idServicio,
                        "id_servicio"     => $idServicio,
                    ]
                ]
            ]);

            $this->registrar_censos($CensoRequest);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cama liberada correctamente.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al liberar la cama: ' . $e->getMessage()
            ]);
        }
    }

    // public function modificar_estado_cama(Request $request)
    // {
    //     if (!Auth::check()) {
    //         return redirect('/auth/login');
    //     }

    //     try {
    //         DB::beginTransaction();

    //         $id_user = Auth::user()->id;

    //         DB::table('censo_detalles')->insert([
    //             'id_accion'          => 4,  
    //             'id_cama_actual'     => $request->input('id_cama_actual'),
    //             'id_servicio_actual' => $request->input('id_servicio_actual'),
    //             'observacion'        => $request->input('observacion'),
    //             'id_estado_cama'     => $request->input('estado'),
    //             'flag_liberar_cama'  => 0,
    //             'created_at'         => Carbon::now(),
    //             'created_us'         => $id_user
    //         ]);

    //         DB::select("SELECT fn_update_cama_situacion(?, ?, ?) AS resultado",
    //             [
    //                 $request->input('id_cama_actual'),  
    //                 $request->input('estado'),         
    //                 $id_user                            
    //             ]);

    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Cama modificada correctamente.'
    //         ]);

    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error al modificar el estado de la cama: ' . $e->getMessage()
    //         ]);
    //     }    
    // }


    public function modificar_estado_cama(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }

        try {
            DB::beginTransaction();

            $id_user        = Auth::user()->id;
            $idCamaActual   = $request->input('id_cama_actual');
            $idServicio     = $request->input('id_servicio_actual');
            
            if (empty($idServicio)) {
                $idServicio = null;
            }
            
            $estado         = $request->input('estado');
            $observacion    = $request->input('observacion');

            DB::table('censo_detalles')->insert([
                'id_accion'          => 4,  
                'id_cama_actual'     => $idCamaActual,
                'id_servicio_actual' => $idServicio,
                'observacion'        => $observacion,
                'id_estado_cama'     => $estado,
                'flag_liberar_cama'  => 0,
                'created_at'         => Carbon::now(),
                'created_us'         => $id_user
            ]);

       
            DB::select(
                "SELECT fn_update_cama_situacion(?, ?, ?) AS resultado",
                [
                    $idCamaActual, 
                    $estado,         
                    $id_user         
                ]
            );

            $CensoRequest = new Request([
                "censos" => [
                    [
                        "id_cama"         => $idCamaActual,
                        "servicio_origen" => $idServicio,
                        "id_servicio"     => $idServicio,
                    ]
                ]
            ]);

            $this->registrar_censos($CensoRequest);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Estado de cama modificado correctamente.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al modificar el estado de la cama: ' . $e->getMessage()
            ]);
        }
    }
}



