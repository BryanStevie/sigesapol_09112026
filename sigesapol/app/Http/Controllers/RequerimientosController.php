<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\RequerimientoPfdm;
use App\Models\RequerimientoPfdm_MovimientosAp;
use App\Models\Requerimientos_movimientos;
use App\Models\User;
use Barryvdh\DomPDF\PDF;
use Exception;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class RequerimientosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function all_gestion_requerimientos()
    {
        $unidades = DB::table('tipo_consultorio_divisiones')->where("estado", 1)->get();
        $etapas = DB::table('requerimiento_pfdm_etapas')->get();
        $servicio = DB::table('consultorios')->where("estado", 4)->get();
        $tipos_producto = DB::table('tipo_productos')->where("estado", 1)->get();
        $prioridades = DB::table('prioridades_requerimiento')->get();
        return view("frontend.requerimientos.all_gestion_requerimientos", compact("unidades", "etapas", "tipos_producto", "prioridades", "servicio"));
    }

    public function all_historial_movimientos()
    {
        $unidades = DB::table('tipo_consultorio_divisiones')->where("estado", 1)->get();
        $etapas = DB::table('requerimiento_pfdm_etapas')->get();
        $servicio = DB::table('consultorios')->where("estado", 4)->get();
        $tipos_producto = DB::table('tipo_productos')->where("estado", 1)->get();
        $prioridades = DB::table('prioridades_requerimiento')->get();
        return view("frontend.requerimientos.all_historial_movimientos", compact("unidades", "etapas", "tipos_producto", "prioridades", "servicio"));
    }

    public function all_bandeja_ue020()
    {
        $unidades = DB::table('tipo_consultorio_divisiones')->where("estado", 1)->get();
        $etapas = DB::table('requerimiento_pfdm_etapas')->get();
        $servicio = DB::table('consultorios')->where("estado", 4)->get();
        $tipos_producto = DB::table('tipo_productos')->where("estado", 1)->get();
        $prioridades = DB::table('prioridades_requerimiento')->get();
        return view("frontend.requerimientos.all_bandeja_ue020", compact("unidades", "etapas", "tipos_producto", "prioridades", "servicio"));
    }

    public function all_ue020_area()
    {
        $etapas = DB::table('requerimiento_pfdm_etapas')->get();
        return view("frontend.requerimientos.all_ue020_area", compact("etapas"));
    }

    public function all_ue020_seccion()
    {
        $etapas = DB::table('requerimiento_pfdm_etapas')->get();
        return view("frontend.requerimientos.all_ue020_seccion", compact("etapas"));
    }

    public function all_ue020_sub_seccion()
    {
        $unidades = DB::table('tipo_consultorio_divisiones')->where("estado", 1)->get();
        $etapas = DB::table('requerimiento_pfdm_etapas')->get();
        $servicio = DB::table('consultorios')->where("estado", 4)->get();
        $tipos_producto = DB::table('tipo_productos')->where("estado", 1)->get();
        $prioridades = DB::table('prioridades_requerimiento')->get();
        return view("frontend.requerimientos.all_ue020_sub_seccion", compact("unidades", "etapas", "tipos_producto", "prioridades", "servicio"));
    }

    public function all_ue020_proc_seleccion()
    {
        $unidades = DB::table('tipo_consultorio_divisiones')->where("estado", 1)->get();
        $etapas = DB::table('requerimiento_pfdm_etapas')->get();
        $servicio = DB::table('consultorios')->where("estado", 4)->get();
        $tipos_producto = DB::table('tipo_productos')->where("estado", 1)->get();
        $prioridades = DB::table('prioridades_requerimiento')->get();
        return view("frontend.requerimientos.all_ue020_proc_seleccion", compact("unidades", "etapas", "tipos_producto", "prioridades", "servicio"));
    }

    public function all_ue020_ejecucion()
    {
        $unidades = DB::table('tipo_consultorio_divisiones')->where("estado", 1)->get();
        $etapas = DB::table('requerimiento_pfdm_etapas')->get();
        $servicio = DB::table('consultorios')->where("estado", 4)->get();
        $tipos_producto = DB::table('tipo_productos')->where("estado", 1)->get();
        $prioridades = DB::table('prioridades_requerimiento')->get();
        return view("frontend.requerimientos.all_ue020_ejecucion", compact("unidades", "etapas", "tipos_producto", "prioridades", "servicio"));
    }

    public function all_ue020_almacen()
    {
        $unidades = DB::table('tipo_consultorio_divisiones')->where("estado", 1)->get();
        $etapas = DB::table('requerimiento_pfdm_etapas')->get();
        $servicio = DB::table('consultorios')->where("estado", 4)->get();
        $tipos_producto = DB::table('tipo_productos')->where("estado", 1)->get();
        $prioridades = DB::table('prioridades_requerimiento')->get();
        return view("frontend.requerimientos.all_ue020_almacen", compact("unidades", "etapas", "tipos_producto", "prioridades", "servicio"));
    }

    public function guardarRequerimiento(Request $request)
    {
        // 1. Definir las reglas de validación
        // 'unique:tabla,columna' verifica que no exista en la base de datos
        $rules = [
            'nro_requerimiento' => 'required|unique:requerimientos_pfdm,numero_requerimiento',
            // puedes agregar más reglas aquí si lo deseas
        ];

        $messages = [
            'nro_requerimiento.unique' => 'El número de requerimiento ya se encuentra registrado.',
            'nro_requerimiento.required' => 'El número de requerimiento es obligatorio.',
        ];

        // 2. Ejecutar la validación manualmente (compatible con Laravel 5)
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $req = RequerimientoPfdm::create([
                'numero_requerimiento' => $request->nro_requerimiento,
                'tipo_documento' => $request->tipo_documento,
                'numero_sgd_ht' => $request->tipo_documento === "HT" ? $request->td_descripcion_th : $request->td_descripcion_sgd,
                'numero_oficio' => $request->nro_oficio,
                'fecha_oficio' => $request->fecha_oficio,
                'numero_informe' => $request->nro_informe,
                'fecha_informe' => $request->fecha_informe,
                'id_tipo_requerimiento' => $request->tipo_requerimiento,
                'observacion_requerimiento' => $request->obs_requerimiento,
                'prioridad' => $request->tipo_prioridad,
                'observacion_prioridad' => $request->obs_prioridad,
                'id_procedimiento_seleccion' => $request->proc_seleccion,
                'id_tipo_proceso' => $request->tipo_proceso,
                'id_tipo_producto' => $request->tipo_producto,
                'id_servicio' => $request->servicio_producto,
                'numero_pedido_siga' => $request->nro_pedido_siga,
                'denominacion' => $request->denominacion_requerimiento,
                'id_unidad_destino' => $request->destino_unidad,
                'id_etapa' => $request->destino_etapa,
                'id_responsable' => $request->destino_responsable,
                'unidad_medida' => $request->unidad_medida,
                'cantidad_total' => $request->cantidad_total,
                'id_producto' => $request->pfdm_id,
                'created_us' => Auth::user()->id,
                'updated_us' => Auth::user()->id,
                'estado_id' => 1,
                'etapa_id' => 1,
                'ubicacion_id' => 1,
                'proceso_id' => 1,
            ]);

            if ($request->has('numero_entrega')) {

                $detalles = [];

                foreach ($request->numero_entrega as $index => $numero) {

                    $detalles[] = [
                        'numero_entrega' => $numero,
                        'cantidad_parcial' => $request->cantidad_entrega[$index],
                        'numero_dias' => $request->dias_entrega[$index],
                    ];
                }

                $req->detalles()->createMany($detalles);
            }

            Requerimientos_movimientos::create([
                'requerimiento_id' => $req->id,
                'estado_id' => 1,
                'etapa_id' => 1,
                'ubicacion_id' => 1,
                'proceso_id' => 1,
                'orden' => 1,
                'id_registrador' => Auth::user()->id,
                'fecha_ingreso' => Carbon::now(),
                'observacion' => 'Requerimiento registrado en UGEPDMPS'
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Requerimiento guardado correctamente',
                'data' => $req
            ], 200);
        } catch (ValidationException $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function actualizarRequerimiento(Request $request)
    {
        $req = RequerimientoPfdm::findOrFail($request->req_id_upt);

        $rules = [
            'nro_requerimiento' => 'required',
        ];

        if ($req->numero_requerimiento !== $request->nro_requerimiento) {
            $rules['nro_requerimiento'] .= '|unique:requerimientos_pfdm,numero_requerimiento';
        }

        $messages = [
            'nro_requerimiento.unique' => 'El número de requerimiento ya se encuentra registrado.',
            'nro_requerimiento.required' => 'El número de requerimiento es obligatorio.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $req->fill([
                'numero_requerimiento' => $request->nro_requerimiento,
                'tipo_documento' => $request->tipo_documento,
                'numero_sgd_ht' => $request->tipo_documento === "HT"
                    ? $request->td_descripcion_th
                    : $request->td_descripcion_sgd,
                'numero_oficio' => $request->nro_oficio,
                'fecha_oficio' => $request->fecha_oficio,
                'numero_informe' => $request->nro_informe,
                'fecha_informe' => $request->fecha_informe,
                'id_tipo_requerimiento' => $request->tipo_requerimiento,
                'observacion_requerimiento' => $request->obs_requerimiento,
                'prioridad' => $request->tipo_prioridad,
                'observacion_prioridad' => $request->obs_prioridad,
                'id_procedimiento_seleccion' => $request->proc_seleccion,
                'id_tipo_proceso' => $request->tipo_proceso,
                'id_tipo_producto' => $request->tipo_producto,
                'id_servicio' => $request->servicio_producto,
                'numero_pedido_siga' => $request->nro_pedido_siga,
                'denominacion' => $request->denominacion_requerimiento,
                'id_unidad_destino' => $request->destino_unidad,
                'id_etapa' => $request->destino_etapa,
                'id_responsable' => $request->destino_responsable,
                'unidad_medida' => $request->unidad_medida,
                'cantidad_total' => $request->cantidad_total,
                'id_producto' => $request->pfdm_id,
            ]);

            if ($req->isDirty()) {
                $req->updated_us = Auth::user()->id;
                $req->save();
            }

            if ($request->has('numero_entrega')) {

                $req->detalles()->delete();

                $detalles = [];

                foreach ($request->numero_entrega as $index => $numero) {
                    $detalles[] = [
                        'numero_entrega' => $numero,
                        'cantidad_parcial' => $request->cantidad_entrega[$index],
                        'numero_dias' => $request->dias_entrega[$index],
                    ];
                }

                $req->detalles()->createMany($detalles);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Requerimiento actualizado correctamente',
                'data' => $req
            ], 200);
        } catch (ValidationException $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function registrarMovimientoAp(Request $request)
    {
        try {
            DB::beginTransaction();

            $req = RequerimientoPfdm::find($request->req_id);

            if (!$req) {
                return response()->json([
                    'message' => 'El requerimiento no existe'
                ], 404);
            }

            $tieneMovimientos = $req->movimientosAp()->where('tipo_movimiento', $request->tipo_mov)->exists();

            if (!$tieneMovimientos) {
                $req->estado_id = 9;
                $req->etapa_id = $request->tipo_mov == "1" ? 7 : 8;
                $req->proceso_id = 4;
                $req->ubicacion_id = $request->tipo_mov == "1" ? 5 : 6;

                $req->save();

                Requerimientos_movimientos::create([
                    'requerimiento_id' => $req->id,
                    'estado_id' => 9,
                    'etapa_id' => $request->tipo_mov == "1" ? 7 : 8,
                    'ubicacion_id' => $request->tipo_mov == "1" ? 5 : 6,
                    'proceso_id' => 4,
                    'orden' => 10,
                    'id_registrador' => Auth::user()->id,
                    'fecha_ingreso' => Carbon::now(),
                    'observacion' => 'Requerimiento en proceso.'
                ]);
            }

            $req->movimientosAp()->create([
                'requerimiento_id' => $req->id,
                'etapa' => $request->nueva_etapa_ap_desc,
                'observado' => $request->tiene_obs,
                'tipo_movimiento' => $request->tipo_mov,
                'observacion' => $request->obs_ap,
                'created_us' => Auth::user()->id,
            ]);

            DB::commit();

            return response()->json(['message' => 'Movimiento registrado']);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Error al registrar movimiento',
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Error al registrar movimiento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function registrarMovimientoPc(Request $request)
    {
        try {
            DB::beginTransaction();

            $req = RequerimientoPfdm::find($request->req_id);

            if (!$req) {
                return response()->json([
                    'message' => 'El requerimiento no existe'
                ], 404);
            }

            $tieneMovimientos = $req->movimientosAp()->where('tipo_movimiento', $request->tipo_mov)->exists();
            $ultimoMovimiento = $req->movimientos()
                ->orderBy('orden', 'desc')
                ->first();
            $ultimoOrden = $ultimoMovimiento
                ? (int) $ultimoMovimiento->orden
                : 0;

            if (!$tieneMovimientos) {
                $req->estado_id = 9;
                $req->etapa_id = 8;
                $req->proceso_id = 4;
                $req->ubicacion_id = 6;

                $req->save();

                Requerimientos_movimientos::create([
                    'requerimiento_id' => $req->id,
                    'estado_id' => 9,
                    'etapa_id' => 8,
                    'ubicacion_id' => 6,
                    'proceso_id' => 4,
                    'orden' => ($ultimoOrden + 1),
                    'id_registrador' => Auth::user()->id,
                    'fecha_ingreso' => Carbon::now(),
                    'observacion' => 'Requerimiento en proceso.'
                ]);
            }

            $req->movimientosAp()->create([
                'requerimiento_id' => $req->id,
                'etapa' => $request->nueva_etapa_ap_desc,
                'observado' => $request->tiene_obs,
                'tipo_movimiento' => $request->tipo_mov,
                'observacion' => $request->obs_ap,
                'created_us' => Auth::user()->id,
            ]);

            DB::commit();

            return response()->json(['message' => 'Movimiento registrado']);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Error al registrar movimiento',
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Error al registrar movimiento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function registrarMovimientoEj(Request $request)
    {
        try {
            DB::beginTransaction();

            $req = RequerimientoPfdm::find($request->req_id);

            if (!$req) {
                return response()->json([
                    'message' => 'El requerimiento no existe'
                ], 404);
            }

            $tieneMovimientos = $req->movimientosAp()->where('tipo_movimiento', $request->tipo_mov)->exists();
            $ultimoMovimiento = $req->movimientos()
                ->orderBy('orden', 'desc')
                ->first();
            $ultimoOrden = $ultimoMovimiento
                ? (int) $ultimoMovimiento->orden
                : 0;

            if (!$tieneMovimientos) {
                $req->estado_id = 9;
                $req->etapa_id = 9;
                $req->proceso_id = 4;
                $req->ubicacion_id = 7;

                $req->save();

                Requerimientos_movimientos::create([
                    'requerimiento_id' => $req->id,
                    'estado_id' => 9,
                    'etapa_id' => 9,
                    'ubicacion_id' => 7,
                    'proceso_id' => 4,
                    'orden' => ($ultimoOrden + 1),
                    'id_registrador' => Auth::user()->id,
                    'fecha_ingreso' => Carbon::now(),
                    'observacion' => 'Requerimiento en proceso.'
                ]);
            }

            $movimientoAp = $req->movimientosAp()->create([
                'requerimiento_id' => $req->id,
                'etapa' => $request->nueva_etapa_ap_desc,
                'observado' => $request->tiene_obs,
                'tipo_movimiento' => $request->tipo_mov,
                'observacion' => $request->obs_ap,
                'created_us' => Auth::user()->id,
            ]);

            if ($request->nueva_etapa_ap_desc == "CREACION DE ORDEN DE COMPRA") {
                $movimientoAp->contrato()->create([
                    'proveedor_id' => $request->prov_id,
                    'tipo_contrato' => $request->tipo_contrato,
                    'numero_contrato' => $request->numero_contrato,
                    'fecha_contrato' => $request->fecha_contrato,
                    'monto_contrato' => $request->monto_contrato,
                ]);
            }

            if ($request->nueva_etapa_ap_desc == "NOTIFICACION DE O/C") {
                if ($request->has('numero_entrega')) {

                    $req->detalles()->delete();

                    $detalles = [];

                    foreach ($request->numero_entrega as $index => $numero) {
                        $detalles[] = [
                            'numero_entrega' => $numero,
                            'cantidad_parcial' => $request->cantidad_parcial[$index],
                            'numero_dias' => $request->numero_dias[$index],
                            'fecha_internamiento' => $request->fecha_internamiento[$index],
                        ];
                    }

                    $req->detalles()->createMany($detalles);
                }
            }

            DB::commit();

            return response()->json(['message' => 'Movimiento registrado']);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Error al registrar movimiento',
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Error al registrar movimiento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function listarRequerimientos(Request $request)
    {
        $totalData = RequerimientoPfdm::count();
        $recordsTotal = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');

        $query = RequerimientoPfdm::query()
            ->join('users', 'requerimientos_pfdm.created_us', '=', 'users.id')
            ->join('prioridades_requerimiento', 'requerimientos_pfdm.prioridad', '=', 'prioridades_requerimiento.id')
            ->join('tipo_productos', 'requerimientos_pfdm.id_tipo_producto', '=', 'tipo_productos.id')
            ->leftJoin('requerimiento_pfdm_movimientos as rpm', function ($join) {
                $join->on('rpm.requerimiento_id', '=', 'requerimientos_pfdm.id')
                    ->where('rpm.estado_id', '=', 2)
                    ->where('rpm.orden', '=', 2);
            })
            ->select(
                'requerimientos_pfdm.*',
                'users.name as registrador_nombre',
                'users.paterno as registrador_paterno',
                'users.materno as registrador_materno',
                'prioridades_requerimiento.nombre as prioridad_nombre',
                'tipo_productos.nombre as tipo_producto_nombre',
                'rpm.fecha_ingreso as fecha_envio'
            );

        if ($request->has('nro_requerimiento')) {
            $query->where('numero_requerimiento', 'ILIKE', '%' . $request->nro_requerimiento . '%');
        }

        if ($request->has('nroSgdHt')) {
            $query->where('numero_sgd_ht', 'ILIKE', '%' . $request->nroSgdHt . '%');
        }

        if ($request->has('registrador_requerimiento')) {
            $query->where('created_us', $request->registrador_requerimiento);
        }

        if ($request->has('prioridad_requerimiento')) {
            $query->where('prioridad', $request->prioridad_requerimiento);
        }

        if ($request->has('tipo_producto_requerimiento')) {
            $query->where('id_tipo_producto', $request->tipo_producto_requerimiento);
        }

        if ($request->rangoInicio && $request->rangoFin) {
            $query->whereBetween('requerimientos_pfdm.created_at', [
                Carbon::parse($request->rangoInicio)->startOfDay(),
                Carbon::parse($request->rangoFin)->endOfDay()
            ]);
        }

        if ($request->estado_actual !== null) {
            if ($request->estado_actual == 1) {
                $query->where('requerimientos_pfdm.estado_id', $request->estado_actual);
            } else if ($request->estado_actual == 2) {
                $query->where('requerimientos_pfdm.estado_id', '>=', $request->estado_actual)
                    ->where('requerimientos_pfdm.estado_id', '!=', 8);
            } else if ($request->estado_actual == 8) {
                $query->where('requerimientos_pfdm.estado_id', $request->estado_actual);
            }
        }

        $query->where('requerimientos_pfdm.ubicacion_id', '>=', 1);

        $recordsFiltered = $query->count();

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->orderBy('id', 'asc')
            ->get();

        $result = [];

        foreach ($data as $row) {
            $result[] = [
                'requerimiento_id' => $row->id,
                'nro_requerimiento' => $row->numero_requerimiento,
                'tipo_doc_requerimiento' => $row->tipo_documento,
                'nro_doc_sgdht' => $row->numero_sgd_ht,
                'fechaReg_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'prioridad_requerimiento' => $row->prioridad_nombre,
                'fecha_envio_requerimiento' => $row->fecha_envio != null ? Carbon::parse($row->fecha_envio)->format('d/m/Y H:i:s') : null,
                'tipoProduc_requerimiento' => $row->tipo_producto_nombre,
                'registrador_requerimiento' => $row->registrador_nombre . ', ' . $row->registrador_paterno . ' ' . $row->registrador_materno,
                'estado_requerimiento' => $row->estado_id,
                'ubicacion_requerimiento' => $row->ubicacion_id,
            ];
        }

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $result
        ]);
    }

    public function listarRequerimientosAll(Request $request)
    {
        $totalData = RequerimientoPfdm::count();
        $recordsTotal = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');

        $query = RequerimientoPfdm::query()
            ->join('users', 'requerimientos_pfdm.created_us', '=', 'users.id')
            ->join('prioridades_requerimiento', 'requerimientos_pfdm.prioridad', '=', 'prioridades_requerimiento.id')
            ->join('tipo_productos', 'requerimientos_pfdm.id_tipo_producto', '=', 'tipo_productos.id')
            ->select(
                'requerimientos_pfdm.*',
                'users.name as registrador_nombre',
                'users.paterno as registrador_paterno',
                'users.materno as registrador_materno',
                'prioridades_requerimiento.nombre as prioridad_nombre',
                'tipo_productos.nombre as tipo_producto_nombre'
            );

        if ($request->has('nro_requerimiento')) {
            $query->where('numero_requerimiento', 'ILIKE', '%' . $request->nro_requerimiento . '%');
        }

        if ($request->has('nroSgdHt')) {
            $query->where('numero_sgd_ht', 'ILIKE', '%' . $request->nroSgdHt . '%');
        }

        if ($request->has('registrador_requerimiento')) {
            $query->where('created_us', $request->registrador_requerimiento);
        }

        if ($request->has('prioridad_requerimiento')) {
            $query->where('prioridad', $request->prioridad_requerimiento);
        }

        if ($request->has('tipo_producto_requerimiento')) {
            $query->where('id_tipo_producto', $request->tipo_producto_requerimiento);
        }

        if ($request->rangoInicio && $request->rangoFin) {
            $query->whereBetween('requerimientos_pfdm.created_at', [
                Carbon::parse($request->rangoInicio)->startOfDay(),
                Carbon::parse($request->rangoFin)->endOfDay()
            ]);
        }

        if ($request->estado_actual !== null) {
            if ($request->estado_actual == 1) {
                $query->where('requerimientos_pfdm.estado_id', '>=', 1);
            } else {
                $query->where('requerimientos_pfdm.estado_id', 8);
            }
        }

        $recordsFiltered = $query->count();

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->orderBy('id', 'asc')
            ->get();

        $result = [];

        foreach ($data as $row) {
            $result[] = [
                'requerimiento_id' => $row->id,
                'nro_requerimiento' => $row->numero_requerimiento,
                'tipo_doc_requerimiento' => $row->tipo_documento,
                'nro_doc_sgdht' => $row->numero_sgd_ht,
                'fechaReg_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'prioridad_requerimiento' => $row->prioridad_nombre,
                'tipoProduc_requerimiento' => $row->tipo_producto_nombre,
                'registrador_requerimiento' => $row->registrador_nombre . ', ' . $row->registrador_paterno . ' ' . $row->registrador_materno,
                'estado_requerimiento' => $row->estado_id,
            ];
        }

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $result
        ]);
    }

    public function listarRequerimientosEntrada(Request $request)
    {
        $totalData = RequerimientoPfdm::count();
        $recordsTotal = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');

        $query = RequerimientoPfdm::query()
            ->join('users', 'requerimientos_pfdm.created_us', '=', 'users.id')
            ->join('prioridades_requerimiento', 'requerimientos_pfdm.prioridad', '=', 'prioridades_requerimiento.id')
            ->join('tipo_productos', 'requerimientos_pfdm.id_tipo_producto', '=', 'tipo_productos.id')
            ->leftJoin('requerimiento_pfdm_movimientos as rpm', function ($join) {
                $join->on('rpm.requerimiento_id', '=', 'requerimientos_pfdm.id')
                    ->where('rpm.estado_id', '=', 3);
            })
            ->select(
                'requerimientos_pfdm.*',
                'users.name as registrador_nombre',
                'users.paterno as registrador_paterno',
                'users.materno as registrador_materno',
                'prioridades_requerimiento.nombre as prioridad_nombre',
                'tipo_productos.nombre as tipo_producto_nombre',
                'rpm.fecha_ingreso as fecha_recepcion'
            );

        if ($request->has('nro_requerimiento')) {
            $query->where('numero_requerimiento', 'ILIKE', '%' . $request->nro_requerimiento . '%');
        }

        if ($request->has('nroSgdHt')) {
            $query->where('numero_sgd_ht', 'ILIKE', '%' . $request->nroSgdHt . '%');
        }

        if ($request->has('prioridad_requerimiento')) {
            $query->where('prioridad', $request->prioridad_requerimiento);
        }

        if ($request->has('tipo_producto_requerimiento')) {
            $query->where('id_tipo_producto', $request->tipo_producto_requerimiento);
        }

        if ($request->rangoInicio && $request->rangoFin) {
            $query->whereBetween('requerimientos_pfdm.created_at', [
                Carbon::parse($request->rangoInicio)->startOfDay(),
                Carbon::parse($request->rangoFin)->endOfDay()
            ]);
        }

        if ($request->estado_actual !== null) {
            $query->where('requerimientos_pfdm.estado_id', $request->estado_actual);
        }

        if ($request->estado_actual == 2) {
            $query->where('requerimientos_pfdm.ubicacion_id', 1);
            $query->where('requerimientos_pfdm.etapa_id', 2);
        } else if ($request->estado_actual == 3) {
            $query->where('requerimientos_pfdm.ubicacion_id', 2);
            $query->where('requerimientos_pfdm.etapa_id', 3);
        }

        $recordsFiltered = $query->count();

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->orderBy('id', 'asc')
            ->get();

        $result = [];

        foreach ($data as $row) {
            $result[] = [
                'requerimiento_id' => $row->id,
                'nro_requerimiento' => $row->numero_requerimiento,
                'tipo_doc_requerimiento' => $row->tipo_documento,
                'nro_doc_sgdht' => $row->numero_sgd_ht,
                'fechaReg_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'fechaEnvio_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'prioridad_requerimiento' => $row->prioridad_nombre,
                'fecha_recepcion_requerimiento' => $row->fecha_recepcion != null ? Carbon::parse($row->fecha_recepcion)->format('d/m/Y H:i:s') : null,
                'tipoProduc_requerimiento' => $row->tipo_producto_nombre,
                'registrador_requerimiento' => $row->registrador_nombre . ', ' . $row->registrador_paterno . ' ' . $row->registrador_materno,
                'estado_requerimiento' => $row->estado_id,
            ];
        }

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $result
        ]);
    }

    public function listarRequerimientosEntradaSubSeccion(Request $request)
    {
        $totalData = RequerimientoPfdm::count();
        $recordsTotal = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');

        $query = RequerimientoPfdm::query()
            ->join('users', 'requerimientos_pfdm.created_us', '=', 'users.id')
            ->join('prioridades_requerimiento', 'requerimientos_pfdm.prioridad', '=', 'prioridades_requerimiento.id')
            ->join('tipo_productos', 'requerimientos_pfdm.id_tipo_producto', '=', 'tipo_productos.id')
            ->leftJoin('requerimiento_pfdm_movimientos as rpm', function ($join) {
                $join->on('rpm.requerimiento_id', '=', 'requerimientos_pfdm.id')
                    ->where('rpm.estado_id', '=', 3)
                    ->where('rpm.orden', '=', 9);
            })
            ->select(
                'requerimientos_pfdm.*',
                'users.name as registrador_nombre',
                'users.paterno as registrador_paterno',
                'users.materno as registrador_materno',
                'prioridades_requerimiento.nombre as prioridad_nombre',
                'tipo_productos.nombre as tipo_producto_nombre',
                'rpm.fecha_ingreso as fecha_recepcion'
            );

        if ($request->has('nro_requerimiento')) {
            $query->where('numero_requerimiento', 'ILIKE', '%' . $request->nro_requerimiento . '%');
        }

        if ($request->has('nroSgdHt')) {
            $query->where('numero_sgd_ht', 'ILIKE', '%' . $request->nroSgdHt . '%');
        }

        if ($request->has('prioridad_requerimiento')) {
            $query->where('prioridad', $request->prioridad_requerimiento);
        }

        if ($request->has('tipo_producto_requerimiento')) {
            $query->where('id_tipo_producto', $request->tipo_producto_requerimiento);
        }

        if ($request->rangoInicio && $request->rangoFin) {
            $query->whereBetween('requerimientos_pfdm.created_at', [
                Carbon::parse($request->rangoInicio)->startOfDay(),
                Carbon::parse($request->rangoFin)->endOfDay()
            ]);
        }

        if ($request->estado_actual !== null) {
            $query->where('requerimientos_pfdm.estado_id', $request->estado_actual);
        }

        if ($request->estado_actual == 2) {
            $query->where('requerimientos_pfdm.ubicacion_id', 4);
            $query->where('requerimientos_pfdm.etapa_id', 7);
        } else if ($request->estado_actual == 3) {
            $query->where('requerimientos_pfdm.ubicacion_id', 5);
            $query->where('requerimientos_pfdm.etapa_id', 3);
        }

        $recordsFiltered = $query->count();

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->orderBy('id', 'asc')
            ->get();

        $result = [];

        foreach ($data as $row) {
            $result[] = [
                'requerimiento_id' => $row->id,
                'nro_requerimiento' => $row->numero_requerimiento,
                'tipo_doc_requerimiento' => $row->tipo_documento,
                'nro_doc_sgdht' => $row->numero_sgd_ht,
                'fechaReg_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'fechaEnvio_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'prioridad_requerimiento' => $row->prioridad_nombre,
                'fecha_recepcion_requerimiento' => $row->fecha_recepcion != null ? Carbon::parse($row->fecha_recepcion)->format('d/m/Y H:i:s') : null,
                'tipoProduc_requerimiento' => $row->tipo_producto_nombre,
                'registrador_requerimiento' => $row->registrador_nombre . ', ' . $row->registrador_paterno . ' ' . $row->registrador_materno,
                'estado_requerimiento' => $row->estado_id,
            ];
        }

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $result
        ]);
    }

    public function listarRequerimientosEntradaSubSeccionPc(Request $request)
    {
        $totalData = RequerimientoPfdm::count();
        $recordsTotal = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');

        $query = RequerimientoPfdm::query()
            ->join('users', 'requerimientos_pfdm.created_us', '=', 'users.id')
            ->join('prioridades_requerimiento', 'requerimientos_pfdm.prioridad', '=', 'prioridades_requerimiento.id')
            ->join('tipo_productos', 'requerimientos_pfdm.id_tipo_producto', '=', 'tipo_productos.id')
            ->leftJoin('requerimiento_pfdm_movimientos as rpm', function ($join) {
                $join->on('rpm.requerimiento_id', '=', 'requerimientos_pfdm.id')
                    ->where('rpm.estado_id', '=', 3)
                    ->where('rpm.orden', '=', 12);
            })
            ->select(
                'requerimientos_pfdm.*',
                'users.name as registrador_nombre',
                'users.paterno as registrador_paterno',
                'users.materno as registrador_materno',
                'prioridades_requerimiento.nombre as prioridad_nombre',
                'tipo_productos.nombre as tipo_producto_nombre',
                'rpm.fecha_ingreso as fecha_recepcion'
            );

        if ($request->has('nro_requerimiento')) {
            $query->where('numero_requerimiento', 'ILIKE', '%' . $request->nro_requerimiento . '%');
        }

        if ($request->has('nroSgdHt')) {
            $query->where('numero_sgd_ht', 'ILIKE', '%' . $request->nroSgdHt . '%');
        }

        if ($request->has('prioridad_requerimiento')) {
            $query->where('prioridad', $request->prioridad_requerimiento);
        }

        if ($request->has('tipo_producto_requerimiento')) {
            $query->where('id_tipo_producto', $request->tipo_producto_requerimiento);
        }

        if ($request->rangoInicio && $request->rangoFin) {
            $query->whereBetween('requerimientos_pfdm.created_at', [
                Carbon::parse($request->rangoInicio)->startOfDay(),
                Carbon::parse($request->rangoFin)->endOfDay()
            ]);
        }

        if ($request->estado_actual !== null) {
            $query->where('requerimientos_pfdm.estado_id', $request->estado_actual);
        }

        if ($request->estado_actual == 2) {
            $query->where('requerimientos_pfdm.ubicacion_id', 5);
            $query->where('requerimientos_pfdm.etapa_id', 8);
        } else if ($request->estado_actual == 3) {
            $query->where('requerimientos_pfdm.ubicacion_id', 6);
            $query->where('requerimientos_pfdm.etapa_id', 3);
        }

        $recordsFiltered = $query->count();

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->orderBy('id', 'asc')
            ->get();

        $result = [];

        foreach ($data as $row) {
            $result[] = [
                'requerimiento_id' => $row->id,
                'nro_requerimiento' => $row->numero_requerimiento,
                'tipo_doc_requerimiento' => $row->tipo_documento,
                'nro_doc_sgdht' => $row->numero_sgd_ht,
                'fechaReg_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'fechaEnvio_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'prioridad_requerimiento' => $row->prioridad_nombre,
                'fecha_recepcion_requerimiento' => $row->fecha_recepcion != null ? Carbon::parse($row->fecha_recepcion)->format('d/m/Y H:i:s') : null,
                'tipoProduc_requerimiento' => $row->tipo_producto_nombre,
                'registrador_requerimiento' => $row->registrador_nombre . ', ' . $row->registrador_paterno . ' ' . $row->registrador_materno,
                'estado_requerimiento' => $row->estado_id,
            ];
        }

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $result
        ]);
    }

    public function listarRequerimientosEntradaSubSeccionEj(Request $request)
    {
        $totalData = RequerimientoPfdm::count();
        $recordsTotal = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');

        $query = RequerimientoPfdm::query()
            ->join('users', 'requerimientos_pfdm.created_us', '=', 'users.id')
            ->join('prioridades_requerimiento', 'requerimientos_pfdm.prioridad', '=', 'prioridades_requerimiento.id')
            ->join('tipo_productos', 'requerimientos_pfdm.id_tipo_producto', '=', 'tipo_productos.id')
            ->leftJoin('requerimiento_pfdm_movimientos as rpm', function ($join) {
                $join->on('rpm.requerimiento_id', '=', 'requerimientos_pfdm.id')
                    ->where('rpm.estado_id', '=', 3)
                    ->where('rpm.ubicacion_id', '=', 7);
            })
            ->select(
                'requerimientos_pfdm.*',
                'users.name as registrador_nombre',
                'users.paterno as registrador_paterno',
                'users.materno as registrador_materno',
                'prioridades_requerimiento.nombre as prioridad_nombre',
                'tipo_productos.nombre as tipo_producto_nombre',
                'rpm.fecha_ingreso as fecha_recepcion'
            );

        if ($request->has('nro_requerimiento')) {
            $query->where('numero_requerimiento', 'ILIKE', '%' . $request->nro_requerimiento . '%');
        }

        if ($request->has('nroSgdHt')) {
            $query->where('numero_sgd_ht', 'ILIKE', '%' . $request->nroSgdHt . '%');
        }

        if ($request->has('prioridad_requerimiento')) {
            $query->where('prioridad', $request->prioridad_requerimiento);
        }

        if ($request->has('tipo_producto_requerimiento')) {
            $query->where('id_tipo_producto', $request->tipo_producto_requerimiento);
        }

        if ($request->rangoInicio && $request->rangoFin) {
            $query->whereBetween('requerimientos_pfdm.created_at', [
                Carbon::parse($request->rangoInicio)->startOfDay(),
                Carbon::parse($request->rangoFin)->endOfDay()
            ]);
        }

        if ($request->estado_actual !== null) {
            $query->where('requerimientos_pfdm.estado_id', $request->estado_actual);
        }

        if ($request->estado_actual == 2) {
            $query->whereIn('requerimientos_pfdm.ubicacion_id', [5, 6]);
            $query->whereIn('requerimientos_pfdm.etapa_id', [9]);
        } else if ($request->estado_actual == 3) {
            $query->where('requerimientos_pfdm.ubicacion_id', 7);
            $query->where('requerimientos_pfdm.etapa_id', 3);
        }

        $recordsFiltered = $query->count();

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->orderBy('id', 'asc')
            ->get();

        $result = [];

        foreach ($data as $row) {
            $result[] = [
                'requerimiento_id' => $row->id,
                'nro_requerimiento' => $row->numero_requerimiento,
                'tipo_doc_requerimiento' => $row->tipo_documento,
                'nro_doc_sgdht' => $row->numero_sgd_ht,
                'fechaReg_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'fechaEnvio_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'prioridad_requerimiento' => $row->prioridad_nombre,
                'fecha_recepcion_requerimiento' => $row->fecha_recepcion != null ? Carbon::parse($row->fecha_recepcion)->format('d/m/Y H:i:s') : null,
                'tipoProduc_requerimiento' => $row->tipo_producto_nombre,
                'registrador_requerimiento' => $row->registrador_nombre . ', ' . $row->registrador_paterno . ' ' . $row->registrador_materno,
                'estado_requerimiento' => $row->estado_id,
            ];
        }

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $result
        ]);
    }

    public function listarRequerimientosEntradaSubSeccionAl(Request $request)
    {
        $totalData = RequerimientoPfdm::count();
        $recordsTotal = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');

        $query = RequerimientoPfdm::query()
            ->join('users', 'requerimientos_pfdm.created_us', '=', 'users.id')
            ->join('prioridades_requerimiento', 'requerimientos_pfdm.prioridad', '=', 'prioridades_requerimiento.id')
            ->join('tipo_productos', 'requerimientos_pfdm.id_tipo_producto', '=', 'tipo_productos.id')
            ->leftJoin('requerimiento_pfdm_movimientos as rpm', function ($join) {
                $join->on('rpm.requerimiento_id', '=', 'requerimientos_pfdm.id')
                    ->where('rpm.estado_id', '=', 3)
                    ->where('rpm.ubicacion_id', '=', 8);
            })
            ->select(
                'requerimientos_pfdm.*',
                'users.name as registrador_nombre',
                'users.paterno as registrador_paterno',
                'users.materno as registrador_materno',
                'prioridades_requerimiento.nombre as prioridad_nombre',
                'tipo_productos.nombre as tipo_producto_nombre',
                'rpm.fecha_ingreso as fecha_recepcion'
            );

        if ($request->has('nro_requerimiento')) {
            $query->where('numero_requerimiento', 'ILIKE', '%' . $request->nro_requerimiento . '%');
        }

        if ($request->has('nroSgdHt')) {
            $query->where('numero_sgd_ht', 'ILIKE', '%' . $request->nroSgdHt . '%');
        }

        if ($request->has('prioridad_requerimiento')) {
            $query->where('prioridad', $request->prioridad_requerimiento);
        }

        if ($request->has('tipo_producto_requerimiento')) {
            $query->where('id_tipo_producto', $request->tipo_producto_requerimiento);
        }

        if ($request->rangoInicio && $request->rangoFin) {
            $query->whereBetween('requerimientos_pfdm.created_at', [
                Carbon::parse($request->rangoInicio)->startOfDay(),
                Carbon::parse($request->rangoFin)->endOfDay()
            ]);
        }

        if ($request->estado_actual !== null) {
            $query->where('requerimientos_pfdm.estado_id', $request->estado_actual);
        }

        if ($request->estado_actual == 2) {
            $query->whereIn('requerimientos_pfdm.ubicacion_id', [7]);
            $query->whereIn('requerimientos_pfdm.etapa_id', [10]);
        } else if ($request->estado_actual == 3) {
            $query->where('requerimientos_pfdm.ubicacion_id', 8);
            $query->where('requerimientos_pfdm.etapa_id', 3);
        }

        $recordsFiltered = $query->count();

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->orderBy('id', 'asc')
            ->get();

        $result = [];

        foreach ($data as $row) {
            $result[] = [
                'requerimiento_id' => $row->id,
                'nro_requerimiento' => $row->numero_requerimiento,
                'tipo_doc_requerimiento' => $row->tipo_documento,
                'nro_doc_sgdht' => $row->numero_sgd_ht,
                'fechaReg_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'fechaEnvio_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'prioridad_requerimiento' => $row->prioridad_nombre,
                'fecha_recepcion_requerimiento' => $row->fecha_recepcion != null ? Carbon::parse($row->fecha_recepcion)->format('d/m/Y H:i:s') : null,
                'tipoProduc_requerimiento' => $row->tipo_producto_nombre,
                'registrador_requerimiento' => $row->registrador_nombre . ', ' . $row->registrador_paterno . ' ' . $row->registrador_materno,
                'estado_requerimiento' => $row->estado_id,
            ];
        }

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $result
        ]);
    }

    public function listarRequerimientosProceso(Request $request)
    {
        $totalData = RequerimientoPfdm::count();
        $recordsTotal = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');

        $query = RequerimientoPfdm::query()
            ->join('users', 'requerimientos_pfdm.created_us', '=', 'users.id')
            ->join('prioridades_requerimiento', 'requerimientos_pfdm.prioridad', '=', 'prioridades_requerimiento.id')
            ->join('tipo_productos', 'requerimientos_pfdm.id_tipo_producto', '=', 'tipo_productos.id')
            ->join('requerimiento_pfdm_movimientos as rpm', function ($join) {
                $join->on('rpm.requerimiento_id', '=', 'requerimientos_pfdm.id')
                    ->where('rpm.estado_id', '=', 3)
                    ->where('rpm.orden', '=', 3);
            })
            ->select(
                'requerimientos_pfdm.*',
                'users.name as registrador_nombre',
                'users.paterno as registrador_paterno',
                'users.materno as registrador_materno',
                'prioridades_requerimiento.nombre as prioridad_nombre',
                'tipo_productos.nombre as tipo_producto_nombre',
                'rpm.fecha_ingreso as fecha_recepcion'
            );

        if ($request->has('nro_requerimiento')) {
            $query->where('numero_requerimiento', 'ILIKE', '%' . $request->nro_requerimiento . '%');
        }

        if ($request->has('nroSgdHt')) {
            $query->where('numero_sgd_ht', 'ILIKE', '%' . $request->nroSgdHt . '%');
        }

        if ($request->has('registrador_requerimiento')) {
            $query->where('created_us', $request->registrador_requerimiento);
        }

        if ($request->has('prioridad_requerimiento')) {
            $query->where('prioridad', $request->prioridad_requerimiento);
        }

        if ($request->has('tipo_producto_requerimiento')) {
            $query->where('id_tipo_producto', $request->tipo_producto_requerimiento);
        }

        if ($request->rangoInicio && $request->rangoFin) {
            $query->whereBetween('requerimientos_pfdm.created_at', [
                Carbon::parse($request->rangoInicio)->startOfDay(),
                Carbon::parse($request->rangoFin)->endOfDay()
            ]);
        }

        $query->where('requerimientos_pfdm.estado_id', 3);
        $query->where('requerimientos_pfdm.etapa_id', 3);
        $query->where('requerimientos_pfdm.ubicacion_id', 2);

        $recordsFiltered = $query->count();

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->orderBy('id', 'asc')
            ->get();

        $result = [];

        foreach ($data as $row) {
            $result[] = [
                'requerimiento_id' => $row->id,
                'nro_requerimiento' => $row->numero_requerimiento,
                'tipo_doc_requerimiento' => $row->tipo_documento,
                'nro_doc_sgdht' => $row->numero_sgd_ht,
                'fechaReg_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'fechaEnvio_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'prioridad_requerimiento' => $row->prioridad_nombre,
                'fecha_recepcion_requerimiento' => Carbon::parse($row->fecha_recepcion)->format('d/m/Y H:i:s'),
                'tipoProduc_requerimiento' => $row->tipo_producto_nombre,
                'registrador_requerimiento' => $row->registrador_nombre . ', ' . $row->registrador_paterno . ' ' . $row->registrador_materno,
                'estado_requerimiento' => $row->estado_id,
            ];
        }

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $result
        ]);
    }

    public function listarRequerimientosProcesoSs(Request $request)
    {
        $totalData = RequerimientoPfdm::count();
        $recordsTotal = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');

        $subQuery = "
(
    SELECT mr1.id,
           mr1.requerimiento_id,
           mr1.observado,
           mr1.observacion
    FROM requerimiento_pfdm_movimientos_ap mr1
    INNER JOIN (
        SELECT requerimiento_id, MAX(id) as max_id
        FROM requerimiento_pfdm_movimientos_ap
        GROUP BY requerimiento_id
    ) mr2
        ON mr1.id = mr2.max_id
) as ultimo_movimiento
";

        $query = RequerimientoPfdm::query()
            ->join('users', 'requerimientos_pfdm.created_us', '=', 'users.id')
            ->join('prioridades_requerimiento', 'requerimientos_pfdm.prioridad', '=', 'prioridades_requerimiento.id')
            ->join('tipo_productos', 'requerimientos_pfdm.id_tipo_producto', '=', 'tipo_productos.id')
            ->leftJoin(DB::raw($subQuery), function ($join) {
                $join->on(
                    'requerimientos_pfdm.id',
                    '=',
                    'ultimo_movimiento.requerimiento_id'
                );
            })
            /* ->join('requerimiento_pfdm_movimientos as rpm', function ($join) {
                $join->on('rpm.requerimiento_id', '=', 'requerimientos_pfdm.id')
                    ->whereIn('rpm.estado_id', [3, 9])
                    ->whereIn('rpm.orden', [9, 10]);
            }) */
            ->join(DB::raw("
                (
                    SELECT requerimiento_id, MAX(orden) as max_orden
                    FROM requerimiento_pfdm_movimientos
                    WHERE estado_id IN (3, 9)
                    AND orden IN (9, 10)
                    GROUP BY requerimiento_id
                ) as rpm_max
            "), function ($join) {
                $join->on('rpm_max.requerimiento_id', '=', 'requerimientos_pfdm.id');
            })
            ->join('requerimiento_pfdm_movimientos as rpm', function ($join) {
                $join->on('rpm.requerimiento_id', '=', 'rpm_max.requerimiento_id')
                    ->on('rpm.orden', '=', 'rpm_max.max_orden');
            })
            ->select(
                'requerimientos_pfdm.*',
                'users.name as registrador_nombre',
                'users.paterno as registrador_paterno',
                'users.materno as registrador_materno',
                'prioridades_requerimiento.nombre as prioridad_nombre',
                'tipo_productos.nombre as tipo_producto_nombre',
                'rpm.fecha_ingreso as fecha_recepcion',
                DB::raw("
    CASE
        WHEN ultimo_movimiento.observado THEN 1
        ELSE 0
    END as tiene_observacion
")
            );

        if ($request->has('nro_requerimiento')) {
            $query->where('numero_requerimiento', 'ILIKE', '%' . $request->nro_requerimiento . '%');
        }

        if ($request->has('nroSgdHt')) {
            $query->where('numero_sgd_ht', 'ILIKE', '%' . $request->nroSgdHt . '%');
        }

        if ($request->has('registrador_requerimiento')) {
            $query->where('created_us', $request->registrador_requerimiento);
        }

        if ($request->has('prioridad_requerimiento')) {
            $query->where('prioridad', $request->prioridad_requerimiento);
        }

        if ($request->has('tipo_producto_requerimiento')) {
            $query->where('id_tipo_producto', $request->tipo_producto_requerimiento);
        }

        if ($request->rangoInicio && $request->rangoFin) {
            $query->whereBetween('requerimientos_pfdm.created_at', [
                Carbon::parse($request->rangoInicio)->startOfDay(),
                Carbon::parse($request->rangoFin)->endOfDay()
            ]);
        }

        $query->whereIn('requerimientos_pfdm.estado_id', [3, 9]);
        $query->whereIn('requerimientos_pfdm.etapa_id', [3, 7]);
        $query->whereIn('requerimientos_pfdm.ubicacion_id', [5]);

        $recordsFiltered = $query->count();

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->orderBy('id', 'asc')
            ->get();

        $result = [];

        foreach ($data as $row) {
            $result[] = [
                'requerimiento_id' => $row->id,
                'nro_requerimiento' => $row->numero_requerimiento,
                'tipo_doc_requerimiento' => $row->tipo_documento,
                'nro_doc_sgdht' => $row->numero_sgd_ht,
                'fechaReg_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'fechaEnvio_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'prioridad_requerimiento' => $row->prioridad_nombre,
                'fecha_recepcion_requerimiento' => Carbon::parse($row->fecha_recepcion)->format('d/m/Y H:i:s'),
                'tipoProduc_requerimiento' => $row->tipo_producto_nombre,
                'registrador_requerimiento' => $row->registrador_nombre . ', ' . $row->registrador_paterno . ' ' . $row->registrador_materno,
                'estado_requerimiento' => $row->estado_id,
                'estado_int_requerimiento' => $row->tiene_observacion,
            ];
        }

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $result
        ]);
    }

    public function listarRequerimientosProcesoPc(Request $request)
    {
        $totalData = RequerimientoPfdm::count();
        $recordsTotal = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');

        $subQuery = "
(
    SELECT mr1.id,
           mr1.requerimiento_id,
           mr1.observado,
           mr1.observacion
    FROM requerimiento_pfdm_movimientos_ap mr1
    INNER JOIN (
        SELECT requerimiento_id, MAX(id) as max_id
        FROM requerimiento_pfdm_movimientos_ap
        GROUP BY requerimiento_id
    ) mr2
        ON mr1.id = mr2.max_id
) as ultimo_movimiento
";

        $query = RequerimientoPfdm::query()
            ->join('users', 'requerimientos_pfdm.created_us', '=', 'users.id')
            ->join('prioridades_requerimiento', 'requerimientos_pfdm.prioridad', '=', 'prioridades_requerimiento.id')
            ->join('tipo_productos', 'requerimientos_pfdm.id_tipo_producto', '=', 'tipo_productos.id')
            ->leftJoin(DB::raw($subQuery), function ($join) {
                $join->on(
                    'requerimientos_pfdm.id',
                    '=',
                    'ultimo_movimiento.requerimiento_id'
                );
            })
            ->join(DB::raw("
                (
                    SELECT requerimiento_id, MAX(orden) as max_orden
                    FROM requerimiento_pfdm_movimientos
                    WHERE estado_id IN (3, 9)
                    GROUP BY requerimiento_id
                ) as rpm_max
            "), function ($join) {
                $join->on('rpm_max.requerimiento_id', '=', 'requerimientos_pfdm.id');
            })
            ->join('requerimiento_pfdm_movimientos as rpm', function ($join) {
                $join->on('rpm.requerimiento_id', '=', 'rpm_max.requerimiento_id')
                    ->on('rpm.orden', '=', 'rpm_max.max_orden');
            })
            ->select(
                'requerimientos_pfdm.*',
                'users.name as registrador_nombre',
                'users.paterno as registrador_paterno',
                'users.materno as registrador_materno',
                'prioridades_requerimiento.nombre as prioridad_nombre',
                'tipo_productos.nombre as tipo_producto_nombre',
                'rpm.fecha_ingreso as fecha_recepcion',
                'rpm.fecha_ingreso as fecha_recepcion',
                DB::raw("
    CASE
        WHEN ultimo_movimiento.observado THEN 1
        ELSE 0
    END as tiene_observacion
")
            );

        if ($request->has('nro_requerimiento')) {
            $query->where('numero_requerimiento', 'ILIKE', '%' . $request->nro_requerimiento . '%');
        }

        if ($request->has('nroSgdHt')) {
            $query->where('numero_sgd_ht', 'ILIKE', '%' . $request->nroSgdHt . '%');
        }

        if ($request->has('registrador_requerimiento')) {
            $query->where('created_us', $request->registrador_requerimiento);
        }

        if ($request->has('prioridad_requerimiento')) {
            $query->where('prioridad', $request->prioridad_requerimiento);
        }

        if ($request->has('tipo_producto_requerimiento')) {
            $query->where('id_tipo_producto', $request->tipo_producto_requerimiento);
        }

        if ($request->rangoInicio && $request->rangoFin) {
            $query->whereBetween('requerimientos_pfdm.created_at', [
                Carbon::parse($request->rangoInicio)->startOfDay(),
                Carbon::parse($request->rangoFin)->endOfDay()
            ]);
        }

        $query->whereIn('requerimientos_pfdm.estado_id', [3, 9]);
        $query->whereIn('requerimientos_pfdm.etapa_id', [3, 8]);
        $query->whereIn('requerimientos_pfdm.ubicacion_id', [6]);

        $recordsFiltered = $query->count();

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->orderBy('id', 'asc')
            ->get();

        $result = [];

        foreach ($data as $row) {
            $result[] = [
                'requerimiento_id' => $row->id,
                'nro_requerimiento' => $row->numero_requerimiento,
                'tipo_doc_requerimiento' => $row->tipo_documento,
                'nro_doc_sgdht' => $row->numero_sgd_ht,
                'fechaReg_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'fechaEnvio_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'prioridad_requerimiento' => $row->prioridad_nombre,
                'fecha_recepcion_requerimiento' => Carbon::parse($row->fecha_recepcion)->format('d/m/Y H:i:s'),
                'tipoProduc_requerimiento' => $row->tipo_producto_nombre,
                'registrador_requerimiento' => $row->registrador_nombre . ', ' . $row->registrador_paterno . ' ' . $row->registrador_materno,
                'estado_requerimiento' => $row->estado_id,
                'estado_int_requerimiento' => $row->tiene_observacion,
            ];
        }

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $result
        ]);
    }

    public function listarRequerimientosProcesoEj(Request $request)
    {
        $totalData = RequerimientoPfdm::count();
        $recordsTotal = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');

        $subQuery = "
(
    SELECT mr1.id,
           mr1.requerimiento_id,
           mr1.observado,
           mr1.observacion
    FROM requerimiento_pfdm_movimientos_ap mr1
    INNER JOIN (
        SELECT requerimiento_id, MAX(id) as max_id
        FROM requerimiento_pfdm_movimientos_ap
        GROUP BY requerimiento_id
    ) mr2
        ON mr1.id = mr2.max_id
) as ultimo_movimiento
";

        $query = RequerimientoPfdm::query()
            ->join('users', 'requerimientos_pfdm.created_us', '=', 'users.id')
            ->join('prioridades_requerimiento', 'requerimientos_pfdm.prioridad', '=', 'prioridades_requerimiento.id')
            ->join('tipo_productos', 'requerimientos_pfdm.id_tipo_producto', '=', 'tipo_productos.id')
            ->leftJoin(DB::raw($subQuery), function ($join) {
                $join->on(
                    'requerimientos_pfdm.id',
                    '=',
                    'ultimo_movimiento.requerimiento_id'
                );
            })
            ->join(DB::raw("
                (
                    SELECT requerimiento_id, MAX(orden) as max_orden
                    FROM requerimiento_pfdm_movimientos
                    WHERE estado_id IN (3, 9)
                    GROUP BY requerimiento_id
                ) as rpm_max
            "), function ($join) {
                $join->on('rpm_max.requerimiento_id', '=', 'requerimientos_pfdm.id');
            })
            ->join('requerimiento_pfdm_movimientos as rpm', function ($join) {
                $join->on('rpm.requerimiento_id', '=', 'rpm_max.requerimiento_id')
                    ->on('rpm.orden', '=', 'rpm_max.max_orden');
            })
            ->select(
                'requerimientos_pfdm.*',
                'users.name as registrador_nombre',
                'users.paterno as registrador_paterno',
                'users.materno as registrador_materno',
                'prioridades_requerimiento.nombre as prioridad_nombre',
                'tipo_productos.nombre as tipo_producto_nombre',
                'rpm.fecha_ingreso as fecha_recepcion',
                'rpm.fecha_ingreso as fecha_recepcion',
                DB::raw("
    CASE
        WHEN ultimo_movimiento.observado THEN 1
        ELSE 0
    END as tiene_observacion
")
            );

        if ($request->has('nro_requerimiento')) {
            $query->where('numero_requerimiento', 'ILIKE', '%' . $request->nro_requerimiento . '%');
        }

        if ($request->has('nroSgdHt')) {
            $query->where('numero_sgd_ht', 'ILIKE', '%' . $request->nroSgdHt . '%');
        }

        if ($request->has('registrador_requerimiento')) {
            $query->where('created_us', $request->registrador_requerimiento);
        }

        if ($request->has('prioridad_requerimiento')) {
            $query->where('prioridad', $request->prioridad_requerimiento);
        }

        if ($request->has('tipo_producto_requerimiento')) {
            $query->where('id_tipo_producto', $request->tipo_producto_requerimiento);
        }

        if ($request->rangoInicio && $request->rangoFin) {
            $query->whereBetween('requerimientos_pfdm.created_at', [
                Carbon::parse($request->rangoInicio)->startOfDay(),
                Carbon::parse($request->rangoFin)->endOfDay()
            ]);
        }

        $query->whereIn('requerimientos_pfdm.estado_id', [3, 9]);
        $query->whereIn('requerimientos_pfdm.etapa_id', [3, 9]);
        $query->whereIn('requerimientos_pfdm.ubicacion_id', [7]);

        $recordsFiltered = $query->count();

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->orderBy('id', 'asc')
            ->get();

        $result = [];

        foreach ($data as $row) {
            $result[] = [
                'requerimiento_id' => $row->id,
                'nro_requerimiento' => $row->numero_requerimiento,
                'tipo_doc_requerimiento' => $row->tipo_documento,
                'nro_doc_sgdht' => $row->numero_sgd_ht,
                'fechaReg_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'fechaEnvio_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'prioridad_requerimiento' => $row->prioridad_nombre,
                'fecha_recepcion_requerimiento' => Carbon::parse($row->fecha_recepcion)->format('d/m/Y H:i:s'),
                'tipoProduc_requerimiento' => $row->tipo_producto_nombre,
                'registrador_requerimiento' => $row->registrador_nombre . ', ' . $row->registrador_paterno . ' ' . $row->registrador_materno,
                'estado_requerimiento' => $row->estado_id,
                'estado_int_requerimiento' => $row->tiene_observacion,
            ];
        }

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $result
        ]);
    }

    public function listarRequerimientosProcesoAl(Request $request)
    {
        $totalData = RequerimientoPfdm::count();
        $recordsTotal = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');

        $query = RequerimientoPfdm::query()
            ->join('users', 'requerimientos_pfdm.created_us', '=', 'users.id')
            ->join('prioridades_requerimiento', 'requerimientos_pfdm.prioridad', '=', 'prioridades_requerimiento.id')
            ->join('tipo_productos', 'requerimientos_pfdm.id_tipo_producto', '=', 'tipo_productos.id')
            ->join(DB::raw("
                (
                    SELECT requerimiento_id, MAX(orden) as max_orden
                    FROM requerimiento_pfdm_movimientos
                    WHERE estado_id IN (3, 9)
                    GROUP BY requerimiento_id
                ) as rpm_max
            "), function ($join) {
                $join->on('rpm_max.requerimiento_id', '=', 'requerimientos_pfdm.id');
            })
            ->join('requerimiento_pfdm_movimientos as rpm', function ($join) {
                $join->on('rpm.requerimiento_id', '=', 'rpm_max.requerimiento_id')
                    ->on('rpm.orden', '=', 'rpm_max.max_orden');
            })
            ->select(
                'requerimientos_pfdm.*',
                'users.name as registrador_nombre',
                'users.paterno as registrador_paterno',
                'users.materno as registrador_materno',
                'prioridades_requerimiento.nombre as prioridad_nombre',
                'tipo_productos.nombre as tipo_producto_nombre',
                'rpm.fecha_ingreso as fecha_entrada'
            );

        if ($request->has('nro_requerimiento')) {
            $query->where('numero_requerimiento', 'ILIKE', '%' . $request->nro_requerimiento . '%');
        }

        if ($request->has('nroSgdHt')) {
            $query->where('numero_sgd_ht', 'ILIKE', '%' . $request->nroSgdHt . '%');
        }

        if ($request->has('registrador_requerimiento')) {
            $query->where('created_us', $request->registrador_requerimiento);
        }

        if ($request->has('prioridad_requerimiento')) {
            $query->where('prioridad', $request->prioridad_requerimiento);
        }

        if ($request->has('tipo_producto_requerimiento')) {
            $query->where('id_tipo_producto', $request->tipo_producto_requerimiento);
        }

        if ($request->rangoInicio && $request->rangoFin) {
            $query->whereBetween('requerimientos_pfdm.created_at', [
                Carbon::parse($request->rangoInicio)->startOfDay(),
                Carbon::parse($request->rangoFin)->endOfDay()
            ]);
        }

        $query->whereIn('requerimientos_pfdm.estado_id', [3, 9]);
        $query->whereIn('requerimientos_pfdm.etapa_id', [3, 10]);
        $query->whereIn('requerimientos_pfdm.ubicacion_id', [8]);

        $recordsFiltered = $query->count();

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->orderBy('id', 'asc')
            ->get();

        $result = [];

        foreach ($data as $row) {
            $result[] = [
                'requerimiento_id' => $row->id,
                'nro_requerimiento' => $row->numero_requerimiento,
                'tipo_doc_requerimiento' => $row->tipo_documento,
                'nro_doc_sgdht' => $row->numero_sgd_ht,
                'fechaReg_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'fechaEnvio_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'prioridad_requerimiento' => $row->prioridad_nombre,
                'fecha_entrada_requerimiento' => Carbon::parse($row->fecha_entrada)->format('d/m/Y H:i:s'),
                'tipoProduc_requerimiento' => $row->tipo_producto_nombre,
                'registrador_requerimiento' => $row->registrador_nombre . ', ' . $row->registrador_paterno . ' ' . $row->registrador_materno,
                'estado_requerimiento' => $row->estado_id,
                'estado_int_requerimiento' => $row->tiene_observacion,
            ];
        }

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $result
        ]);
    }

    public function listarRequerimientosSalidaSs(Request $request)
    {
        $totalData = RequerimientoPfdm::count();
        $recordsTotal = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');

        $subQuery = "
(
    SELECT mr1.id,
           mr1.requerimiento_id,
           mr1.observado,
           mr1.observacion
    FROM requerimiento_pfdm_movimientos_ap mr1
    INNER JOIN (
        SELECT requerimiento_id, MAX(id) as max_id
        FROM requerimiento_pfdm_movimientos_ap
        GROUP BY requerimiento_id
    ) mr2
        ON mr1.id = mr2.max_id
) as ultimo_movimiento
";

        $query = RequerimientoPfdm::query()
            ->join('users', 'requerimientos_pfdm.created_us', '=', 'users.id')
            ->join('prioridades_requerimiento', 'requerimientos_pfdm.prioridad', '=', 'prioridades_requerimiento.id')
            ->join('tipo_productos', 'requerimientos_pfdm.id_tipo_producto', '=', 'tipo_productos.id')
            ->leftJoin('requerimiento_pfdm_movimientos as rpm', function ($join) {
                $join->on('rpm.requerimiento_id', '=', 'requerimientos_pfdm.id')
                    ->where('rpm.estado_id', "=", 2)
                    ->where('rpm.orden', "=", 11)
                    ->whereIn('rpm.etapa_id', [8, 9]);
            })
            ->leftJoin(DB::raw($subQuery), function ($join) {
                $join->on(
                    'requerimientos_pfdm.id',
                    '=',
                    'ultimo_movimiento.requerimiento_id'
                );
            })
            ->select(
                'requerimientos_pfdm.*',
                'users.name as registrador_nombre',
                'users.paterno as registrador_paterno',
                'users.materno as registrador_materno',
                'prioridades_requerimiento.nombre as prioridad_nombre',
                'tipo_productos.nombre as tipo_producto_nombre',
                'rpm.fecha_ingreso as fecha_envio'
            );

        if ($request->has('nro_requerimiento')) {
            $query->where('numero_requerimiento', 'ILIKE', '%' . $request->nro_requerimiento . '%');
        }

        if ($request->has('nroSgdHt')) {
            $query->where('numero_sgd_ht', 'ILIKE', '%' . $request->nroSgdHt . '%');
        }

        if ($request->has('registrador_requerimiento')) {
            $query->where('created_us', $request->registrador_requerimiento);
        }

        if ($request->has('prioridad_requerimiento')) {
            $query->where('prioridad', $request->prioridad_requerimiento);
        }

        if ($request->has('tipo_producto_requerimiento')) {
            $query->where('id_tipo_producto', $request->tipo_producto_requerimiento);
        }

        if ($request->rangoInicio && $request->rangoFin) {
            $query->whereBetween('requerimientos_pfdm.created_at', [
                Carbon::parse($request->rangoInicio)->startOfDay(),
                Carbon::parse($request->rangoFin)->endOfDay()
            ]);
        }

        if ($request->has('estado_actual')) {
            $query->where('requerimientos_pfdm.estado_id', $request->estado_actual);
        }

        $query->whereIn('requerimientos_pfdm.ubicacion_id', [5]);
        $query->where('ultimo_movimiento.observado', false);

        $recordsFiltered = $query->count();

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->orderBy('id', 'asc')
            ->get();

        $result = [];

        foreach ($data as $row) {
            $result[] = [
                'requerimiento_id' => $row->id,
                'nro_requerimiento' => $row->numero_requerimiento,
                'tipo_doc_requerimiento' => $row->tipo_documento,
                'nro_doc_sgdht' => $row->numero_sgd_ht,
                'fechaReg_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'fechaEnvio_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'prioridad_requerimiento' => $row->prioridad_nombre,
                'fecha_envio_requerimiento' => $row->fecha_envio != null ? Carbon::parse($row->fecha_envio)->format('d/m/Y H:i:s') : null,
                'tipoProduc_requerimiento' => $row->tipo_producto_nombre,
                'registrador_requerimiento' => $row->registrador_nombre . ', ' . $row->registrador_paterno . ' ' . $row->registrador_materno,
                'estado_requerimiento' => $row->estado_id,
            ];
        }

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $result
        ]);
    }

    public function listarRequerimientosSalidaPc(Request $request)
    {
        $totalData = RequerimientoPfdm::count();
        $recordsTotal = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');

        $subQuery = "
(
    SELECT mr1.id,
           mr1.requerimiento_id,
           mr1.observado,
           mr1.observacion
    FROM requerimiento_pfdm_movimientos_ap mr1
    INNER JOIN (
        SELECT requerimiento_id, MAX(id) as max_id
        FROM requerimiento_pfdm_movimientos_ap
        GROUP BY requerimiento_id
    ) mr2
        ON mr1.id = mr2.max_id
) as ultimo_movimiento
";

        $query = RequerimientoPfdm::query()
            ->join('users', 'requerimientos_pfdm.created_us', '=', 'users.id')
            ->join('prioridades_requerimiento', 'requerimientos_pfdm.prioridad', '=', 'prioridades_requerimiento.id')
            ->join('tipo_productos', 'requerimientos_pfdm.id_tipo_producto', '=', 'tipo_productos.id')
            ->leftJoin(DB::raw($subQuery), function ($join) {
                $join->on(
                    'requerimientos_pfdm.id',
                    '=',
                    'ultimo_movimiento.requerimiento_id'
                );
            })
            ->leftJoin('requerimiento_pfdm_movimientos as rpm', function ($join) {
                $join->on('rpm.requerimiento_id', '=', 'requerimientos_pfdm.id')
                    ->where('rpm.estado_id', "=", 2)
                    ->where('rpm.etapa_id', "=", 9);
            })
            ->select(
                'requerimientos_pfdm.*',
                'users.name as registrador_nombre',
                'users.paterno as registrador_paterno',
                'users.materno as registrador_materno',
                'prioridades_requerimiento.nombre as prioridad_nombre',
                'tipo_productos.nombre as tipo_producto_nombre',
                'rpm.fecha_ingreso as fecha_envio'
            );

        if ($request->has('nro_requerimiento')) {
            $query->where('numero_requerimiento', 'ILIKE', '%' . $request->nro_requerimiento . '%');
        }

        if ($request->has('nroSgdHt')) {
            $query->where('numero_sgd_ht', 'ILIKE', '%' . $request->nroSgdHt . '%');
        }

        if ($request->has('registrador_requerimiento')) {
            $query->where('created_us', $request->registrador_requerimiento);
        }

        if ($request->has('prioridad_requerimiento')) {
            $query->where('prioridad', $request->prioridad_requerimiento);
        }

        if ($request->has('tipo_producto_requerimiento')) {
            $query->where('id_tipo_producto', $request->tipo_producto_requerimiento);
        }

        if ($request->rangoInicio && $request->rangoFin) {
            $query->whereBetween('requerimientos_pfdm.created_at', [
                Carbon::parse($request->rangoInicio)->startOfDay(),
                Carbon::parse($request->rangoFin)->endOfDay()
            ]);
        }

        if ($request->has('estado_actual')) {
            $query->where('requerimientos_pfdm.estado_id', $request->estado_actual);
        }

        $query->whereIn('requerimientos_pfdm.ubicacion_id', [6]);

        $query->where('ultimo_movimiento.observado', false);

        $recordsFiltered = $query->count();

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->orderBy('id', 'asc')
            ->get();

        $result = [];

        foreach ($data as $row) {
            $result[] = [
                'requerimiento_id' => $row->id,
                'nro_requerimiento' => $row->numero_requerimiento,
                'tipo_doc_requerimiento' => $row->tipo_documento,
                'nro_doc_sgdht' => $row->numero_sgd_ht,
                'fechaReg_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'fechaEnvio_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'prioridad_requerimiento' => $row->prioridad_nombre,
                'fecha_envio_requerimiento' => $row->fecha_envio != null ? Carbon::parse($row->fecha_envio)->format('d/m/Y H:i:s') : null,
                'tipoProduc_requerimiento' => $row->tipo_producto_nombre,
                'registrador_requerimiento' => $row->registrador_nombre . ', ' . $row->registrador_paterno . ' ' . $row->registrador_materno,
                'estado_requerimiento' => $row->estado_id,
            ];
        }

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $result
        ]);
    }

    public function listarRequerimientosSalidaEj(Request $request)
    {
        $totalData = RequerimientoPfdm::count();
        $recordsTotal = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');

        $subQuery = "
(
    SELECT mr1.id,
           mr1.requerimiento_id,
           mr1.observado,
           mr1.observacion
    FROM requerimiento_pfdm_movimientos_ap mr1
    INNER JOIN (
        SELECT requerimiento_id, MAX(id) as max_id
        FROM requerimiento_pfdm_movimientos_ap
        GROUP BY requerimiento_id
    ) mr2
        ON mr1.id = mr2.max_id
) as ultimo_movimiento
";

        $query = RequerimientoPfdm::query()
            ->join('users', 'requerimientos_pfdm.created_us', '=', 'users.id')
            ->join('prioridades_requerimiento', 'requerimientos_pfdm.prioridad', '=', 'prioridades_requerimiento.id')
            ->join('tipo_productos', 'requerimientos_pfdm.id_tipo_producto', '=', 'tipo_productos.id')
            ->leftJoin(DB::raw($subQuery), function ($join) {
                $join->on(
                    'requerimientos_pfdm.id',
                    '=',
                    'ultimo_movimiento.requerimiento_id'
                );
            })
            ->leftJoin('requerimiento_pfdm_movimientos as rpm', function ($join) {
                $join->on('rpm.requerimiento_id', '=', 'requerimientos_pfdm.id')
                    ->where('rpm.estado_id', "=", 2)
                    ->where('rpm.etapa_id', "=", 10);
            })
            ->select(
                'requerimientos_pfdm.*',
                'users.name as registrador_nombre',
                'users.paterno as registrador_paterno',
                'users.materno as registrador_materno',
                'prioridades_requerimiento.nombre as prioridad_nombre',
                'tipo_productos.nombre as tipo_producto_nombre',
                'rpm.fecha_ingreso as fecha_envio'
            );

        if ($request->has('nro_requerimiento')) {
            $query->where('numero_requerimiento', 'ILIKE', '%' . $request->nro_requerimiento . '%');
        }

        if ($request->has('nroSgdHt')) {
            $query->where('numero_sgd_ht', 'ILIKE', '%' . $request->nroSgdHt . '%');
        }

        if ($request->has('registrador_requerimiento')) {
            $query->where('created_us', $request->registrador_requerimiento);
        }

        if ($request->has('prioridad_requerimiento')) {
            $query->where('prioridad', $request->prioridad_requerimiento);
        }

        if ($request->has('tipo_producto_requerimiento')) {
            $query->where('id_tipo_producto', $request->tipo_producto_requerimiento);
        }

        if ($request->rangoInicio && $request->rangoFin) {
            $query->whereBetween('requerimientos_pfdm.created_at', [
                Carbon::parse($request->rangoInicio)->startOfDay(),
                Carbon::parse($request->rangoFin)->endOfDay()
            ]);
        }

        /* if ($request->has('estado_actual')) {
            $query->where('requerimientos_pfdm.estado_id', $request->estado_actual);
        } */

        $query->whereIn('requerimientos_pfdm.ubicacion_id', [7]);
        $query->whereIn('requerimientos_pfdm.etapa_id', [9, 10]);
        $query->whereIn('requerimientos_pfdm.estado_id', [9, 2]);

        $query->where('ultimo_movimiento.observado', false);

        $recordsFiltered = $query->count();

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->orderBy('id', 'asc')
            ->get();

        $result = [];

        foreach ($data as $row) {
            $result[] = [
                'requerimiento_id' => $row->id,
                'nro_requerimiento' => $row->numero_requerimiento,
                'tipo_doc_requerimiento' => $row->tipo_documento,
                'nro_doc_sgdht' => $row->numero_sgd_ht,
                'fechaReg_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'fechaEnvio_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'prioridad_requerimiento' => $row->prioridad_nombre,
                'fecha_envio_requerimiento' => $row->fecha_envio != null ? Carbon::parse($row->fecha_envio)->format('d/m/Y H:i:s') : null,
                'tipoProduc_requerimiento' => $row->tipo_producto_nombre,
                'registrador_requerimiento' => $row->registrador_nombre . ', ' . $row->registrador_paterno . ' ' . $row->registrador_materno,
                'estado_requerimiento' => $row->estado_id,
            ];
        }

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $result
        ]);
    }

    public function listarRequerimientosSalidaAl(Request $request)
    {
        $totalData = RequerimientoPfdm::count();
        $recordsTotal = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');

        $query = RequerimientoPfdm::query()
            ->join('users', 'requerimientos_pfdm.created_us', '=', 'users.id')
            ->join('prioridades_requerimiento', 'requerimientos_pfdm.prioridad', '=', 'prioridades_requerimiento.id')
            ->join('tipo_productos', 'requerimientos_pfdm.id_tipo_producto', '=', 'tipo_productos.id')
            ->leftJoin('requerimiento_pfdm_movimientos as rpm', function ($join) {
                $join->on('rpm.requerimiento_id', '=', 'requerimientos_pfdm.id')
                    ->where('rpm.estado_id', "=", 9)
                    ->where('rpm.ubicacion_id', "=", 8)
                    ->where('rpm.etapa_id', "=", 6);
            })
            ->select(
                'requerimientos_pfdm.*',
                'users.name as registrador_nombre',
                'users.paterno as registrador_paterno',
                'users.materno as registrador_materno',
                'prioridades_requerimiento.nombre as prioridad_nombre',
                'tipo_productos.nombre as tipo_producto_nombre',
                'rpm.fecha_ingreso as fecha_envio'
            );

        if ($request->has('nro_requerimiento')) {
            $query->where('numero_requerimiento', 'ILIKE', '%' . $request->nro_requerimiento . '%');
        }

        if ($request->has('nroSgdHt')) {
            $query->where('numero_sgd_ht', 'ILIKE', '%' . $request->nroSgdHt . '%');
        }

        if ($request->has('registrador_requerimiento')) {
            $query->where('created_us', $request->registrador_requerimiento);
        }

        if ($request->has('prioridad_requerimiento')) {
            $query->where('prioridad', $request->prioridad_requerimiento);
        }

        if ($request->has('tipo_producto_requerimiento')) {
            $query->where('id_tipo_producto', $request->tipo_producto_requerimiento);
        }

        if ($request->rangoInicio && $request->rangoFin) {
            $query->whereBetween('requerimientos_pfdm.created_at', [
                Carbon::parse($request->rangoInicio)->startOfDay(),
                Carbon::parse($request->rangoFin)->endOfDay()
            ]);
        }

        /* if ($request->has('estado_actual')) {
            $query->where('requerimientos_pfdm.estado_id', $request->estado_actual);
        } */

        $query->whereIn('requerimientos_pfdm.estado_id', [9, 10]);
        $query->whereIn('requerimientos_pfdm.etapa_id', [10, 6]);
        $query->whereIn('requerimientos_pfdm.ubicacion_id', [8]);

        $recordsFiltered = $query->count();

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->orderBy('id', 'asc')
            ->get();

        $result = [];

        foreach ($data as $row) {
            $result[] = [
                'requerimiento_id' => $row->id,
                'nro_requerimiento' => $row->numero_requerimiento,
                'fechaReg_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'fechaEnvio_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'prioridad_requerimiento' => $row->prioridad_nombre,
                'fecha_envio_requerimiento' => $row->fecha_envio != null ? Carbon::parse($row->fecha_envio)->format('d/m/Y H:i:s') : null,
                'tipoProduc_requerimiento' => $row->tipo_producto_nombre,
                'registrador_requerimiento' => $row->registrador_nombre . ', ' . $row->registrador_paterno . ' ' . $row->registrador_materno,
                'estado_requerimiento' => $row->estado_id,
            ];
        }

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $result
        ]);
    }

    public function listarMovimientosAp(Request $request)
    {
        $totalData = RequerimientoPfdm::count();
        $recordsTotal = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');

        $query = RequerimientoPfdm_MovimientosAp::query()
            ->join('requerimientos_pfdm', 'requerimientos_pfdm.id', '=', 'requerimiento_pfdm_movimientos_ap.requerimiento_id')
            ->join('users', 'requerimientos_pfdm.created_us', '=', 'users.id')
            ->join('prioridades_requerimiento', 'requerimientos_pfdm.prioridad', '=', 'prioridades_requerimiento.id')
            ->join('tipo_productos', 'requerimientos_pfdm.id_tipo_producto', '=', 'tipo_productos.id')
            ->select(
                'requerimientos_pfdm.*',
                'requerimiento_pfdm_movimientos_ap.id as movimiento_id',
                'requerimiento_pfdm_movimientos_ap.etapa as etapa_movimiento',
                'requerimiento_pfdm_movimientos_ap.observado as estado_movimiento',
                'requerimiento_pfdm_movimientos_ap.created_at as mov_fecha_reg',
                'users.name as registrador_nombre',
                'users.paterno as registrador_paterno',
                'users.materno as registrador_materno',
                'prioridades_requerimiento.nombre as prioridad_nombre',
                'tipo_productos.nombre as tipo_producto_nombre'
            );

        if ($request->has('requerimiento_id')) {
            $query->where('requerimiento_pfdm_movimientos_ap.requerimiento_id', $request->requerimiento_id);
        }

        if ($request->has('tipo_movimiento')) {
            $query->where('requerimiento_pfdm_movimientos_ap.tipo_movimiento', $request->tipo_movimiento);
        }

        if ($request->rangoInicio && $request->rangoFin) {
            $query->whereBetween('requerimientos_pfdm.created_at', [
                Carbon::parse($request->rangoInicio)->startOfDay(),
                Carbon::parse($request->rangoFin)->endOfDay()
            ]);
        }

        $recordsFiltered = $query->count();

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->orderBy('id', 'asc')
            ->get();

        $result = [];

        foreach ($data as $row) {
            $result[] = [
                'movimiento_id' => $row->movimiento_id,
                'fechaReg_movimiento' => Carbon::parse($row->mov_fecha_reg)->format('d/m/Y H:i:s'),
                'registrador_movimiento' => $row->registrador_nombre . ', ' . $row->registrador_paterno . ' ' . $row->registrador_materno,
                'etapa_movimiento' => $row->etapa_movimiento,
                'estado_movimiento' => $row->estado_movimiento,
            ];
        }

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $result
        ]);
    }

    public function listarRequerimientosSalida(Request $request)
    {
        $totalData = RequerimientoPfdm::count();
        $recordsTotal = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');

        $query = RequerimientoPfdm::query()
            ->join('users', 'requerimientos_pfdm.created_us', '=', 'users.id')
            ->join('prioridades_requerimiento', 'requerimientos_pfdm.prioridad', '=', 'prioridades_requerimiento.id')
            ->join('tipo_productos', 'requerimientos_pfdm.id_tipo_producto', '=', 'tipo_productos.id')
            ->join('requerimiento_pfdm_movimientos as rpm', function ($join) {
                $join->on('rpm.requerimiento_id', '=', 'requerimientos_pfdm.id')
                    ->where('rpm.estado_id', '=', 2)
                    ->where('rpm.orden', '=', 4);
            })
            ->select(
                'requerimientos_pfdm.*',
                'users.name as registrador_nombre',
                'users.paterno as registrador_paterno',
                'users.materno as registrador_materno',
                'prioridades_requerimiento.nombre as prioridad_nombre',
                'tipo_productos.nombre as tipo_producto_nombre',
                'rpm.fecha_ingreso as fecha_envio'
            );

        if ($request->has('nro_requerimiento')) {
            $query->where('numero_requerimiento', 'ILIKE', '%' . $request->nro_requerimiento . '%');
        }

        if ($request->has('nroSgdHt')) {
            $query->where('numero_sgd_ht', 'ILIKE', '%' . $request->nroSgdHt . '%');
        }

        if ($request->has('registrador_requerimiento')) {
            $query->where('created_us', $request->registrador_requerimiento);
        }

        if ($request->has('prioridad_requerimiento')) {
            $query->where('prioridad', $request->prioridad_requerimiento);
        }

        if ($request->has('tipo_producto_requerimiento')) {
            $query->where('id_tipo_producto', $request->tipo_producto_requerimiento);
        }

        if ($request->rangoInicio && $request->rangoFin) {
            $query->whereBetween('requerimientos_pfdm.created_at', [
                Carbon::parse($request->rangoInicio)->startOfDay(),
                Carbon::parse($request->rangoFin)->endOfDay()
            ]);
        }

        $query->where('requerimientos_pfdm.estado_id', 2);
        $query->where('requerimientos_pfdm.ubicacion_id', 2);

        $recordsFiltered = $query->count();

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->orderBy('id', 'asc')
            ->get();

        $result = [];

        foreach ($data as $row) {
            $result[] = [
                'requerimiento_id' => $row->id,
                'nro_requerimiento' => $row->numero_requerimiento,
                'tipo_doc_requerimiento' => $row->tipo_documento,
                'nro_doc_sgdht' => $row->numero_sgd_ht,
                'fechaReg_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'fechaEnvio_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'prioridad_requerimiento' => $row->prioridad_nombre,
                'fecha_proceso_requerimiento' => Carbon::parse($row->fecha_proceso)->format('d/m/Y H:i:s'),
                'tipoProduc_requerimiento' => $row->tipo_producto_nombre,
                'registrador_requerimiento' => $row->registrador_nombre . ', ' . $row->registrador_paterno . ' ' . $row->registrador_materno,
                'estado_requerimiento' => $row->estado_id,
            ];
        }

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $result
        ]);
    }

    public function listarRequerimientosProcesadosUE020(Request $request)
    {
        $totalData = RequerimientoPfdm::count();
        $recordsTotal = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');

        $query = RequerimientoPfdm::query()
            ->join('users', 'requerimientos_pfdm.created_us', '=', 'users.id')
            ->join('prioridades_requerimiento', 'requerimientos_pfdm.prioridad', '=', 'prioridades_requerimiento.id')
            ->join('tipo_productos', 'requerimientos_pfdm.id_tipo_producto', '=', 'tipo_productos.id')
            ->select(
                'requerimientos_pfdm.*',
                'users.name as registrador_nombre',
                'users.paterno as registrador_paterno',
                'users.materno as registrador_materno',
                'prioridades_requerimiento.nombre as prioridad_nombre',
                'tipo_productos.nombre as tipo_producto_nombre'
            );

        if ($request->has('nro_requerimiento')) {
            $query->where('numero_requerimiento', 'ILIKE', '%' . $request->nro_requerimiento . '%');
        }

        if ($request->has('nroSgdHt')) {
            $query->where('numero_sgd_ht', 'ILIKE', '%' . $request->nroSgdHt . '%');
        }

        if ($request->has('registrador_requerimiento')) {
            $query->where('created_us', $request->registrador_requerimiento);
        }

        if ($request->has('prioridad_requerimiento')) {
            $query->where('prioridad', $request->prioridad_requerimiento);
        }

        if ($request->has('tipo_producto_requerimiento')) {
            $query->where('id_tipo_producto', $request->tipo_producto_requerimiento);
        }

        if ($request->rangoInicio && $request->rangoFin) {
            $query->whereBetween('requerimientos_pfdm.created_at', [
                Carbon::parse($request->rangoInicio)->startOfDay(),
                Carbon::parse($request->rangoFin)->endOfDay()
            ]);
        }

        if ($request->estado_actual !== null) {
            $query->where('requerimientos_pfdm.estado_id', $request->estado_actual);
            $query->whereIn('requerimientos_pfdm.ubicacion_id', [2, 3]);
            $query->where('requerimientos_pfdm.proceso_id', 3);
        }

        $recordsFiltered = $query->count();

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->orderBy('id', 'asc')
            ->get();

        $result = [];

        foreach ($data as $row) {
            $result[] = [
                'requerimiento_id' => $row->id,
                'nro_requerimiento' => $row->numero_requerimiento,
                'tipo_doc_requerimiento' => $row->tipo_documento,
                'nro_doc_sgdht' => $row->numero_sgd_ht,
                'fechaReg_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'fechaEnvio_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'prioridad_requerimiento' => $row->prioridad_nombre,
                'fecha_proceso_requerimiento' => $row->fecha_proceso != null ? Carbon::parse($row->fecha_proceso)->format('d/m/Y H:i:s') : null,
                'tipoProduc_requerimiento' => $row->tipo_producto_nombre,
                'registrador_requerimiento' => $row->registrador_nombre . ', ' . $row->registrador_paterno . ' ' . $row->registrador_materno,
                'estado_requerimiento' => $row->estado_id,
            ];
        }

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $result
        ]);
    }

    public function listarRequerimientosRemitidosToSeccion(Request $request)
    {
        $totalData = RequerimientoPfdm::count();
        $recordsTotal = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');

        $query = RequerimientoPfdm::query()
            ->join('users', 'requerimientos_pfdm.created_us', '=', 'users.id')
            ->join('prioridades_requerimiento', 'requerimientos_pfdm.prioridad', '=', 'prioridades_requerimiento.id')
            ->join('tipo_productos', 'requerimientos_pfdm.id_tipo_producto', '=', 'tipo_productos.id')
            ->leftJoin('requerimiento_pfdm_movimientos as rpm', function ($join) {
                $join->on('rpm.requerimiento_id', '=', 'requerimientos_pfdm.id')
                    ->where('rpm.estado_id', '=', 3)
                    ->where('rpm.orden', '=', 7);
            })
            ->select(
                'requerimientos_pfdm.*',
                'users.name as registrador_nombre',
                'users.paterno as registrador_paterno',
                'users.materno as registrador_materno',
                'prioridades_requerimiento.nombre as prioridad_nombre',
                'tipo_productos.nombre as tipo_producto_nombre'
            );

        if ($request->has('nro_requerimiento')) {
            $query->where('numero_requerimiento', 'ILIKE', '%' . $request->nro_requerimiento . '%');
        }

        if ($request->has('nroSgdHt')) {
            $query->where('numero_sgd_ht', 'ILIKE', '%' . $request->nroSgdHt . '%');
        }

        if ($request->has('registrador_requerimiento')) {
            $query->where('created_us', $request->registrador_requerimiento);
        }

        if ($request->has('prioridad_requerimiento')) {
            $query->where('prioridad', $request->prioridad_requerimiento);
        }

        if ($request->has('tipo_producto_requerimiento')) {
            $query->where('id_tipo_producto', $request->tipo_producto_requerimiento);
        }

        if ($request->rangoInicio && $request->rangoFin) {
            $query->whereBetween('requerimientos_pfdm.created_at', [
                Carbon::parse($request->rangoInicio)->startOfDay(),
                Carbon::parse($request->rangoFin)->endOfDay()
            ]);
        }

        if ($request->estado_actual !== null) {
            $query->where('requerimientos_pfdm.estado_id', $request->estado_actual);
            $query->whereIn('requerimientos_pfdm.ubicacion_id', [3, 4]);
            $query->where('requerimientos_pfdm.proceso_id', 4);
        }

        $recordsFiltered = $query->count();

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->orderBy('id', 'asc')
            ->get();

        $result = [];

        foreach ($data as $row) {
            $result[] = [
                'requerimiento_id' => $row->id,
                'nro_requerimiento' => $row->numero_requerimiento,
                'tipo_doc_requerimiento' => $row->tipo_documento,
                'nro_doc_sgdht' => $row->numero_sgd_ht,
                'fechaReg_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'fechaEnvio_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'prioridad_requerimiento' => $row->prioridad_nombre,
                'fecha_proceso_requerimiento' => $row->fecha_proceso != null ? Carbon::parse($row->fecha_proceso)->format('d/m/Y H:i:s') : null,
                'tipoProduc_requerimiento' => $row->tipo_producto_nombre,
                'registrador_requerimiento' => $row->registrador_nombre . ', ' . $row->registrador_paterno . ' ' . $row->registrador_materno,
                'estado_requerimiento' => $row->estado_id,
            ];
        }

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $result
        ]);
    }

    public function listarRequerimientosRecibidosUE020(Request $request)
    {
        $totalData = RequerimientoPfdm::count();
        $recordsTotal = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');

        $query = RequerimientoPfdm::query()
            ->join('users', 'requerimientos_pfdm.created_us', '=', 'users.id')
            ->join('prioridades_requerimiento', 'requerimientos_pfdm.prioridad', '=', 'prioridades_requerimiento.id')
            ->join('tipo_productos', 'requerimientos_pfdm.id_tipo_producto', '=', 'tipo_productos.id')
            ->join('requerimiento_pfdm_movimientos as rpm', function ($join) {
                $join->on('rpm.requerimiento_id', '=', 'requerimientos_pfdm.id')
                    ->whereIn('rpm.estado_id', [3, 5])
                    ->whereIn('rpm.orden', [5, 6]);
            })
            ->select(
                'requerimientos_pfdm.*',
                'users.name as registrador_nombre',
                'users.paterno as registrador_paterno',
                'users.materno as registrador_materno',
                'prioridades_requerimiento.nombre as prioridad_nombre',
                'tipo_productos.nombre as tipo_producto_nombre',
                'rpm.fecha_ingreso as fecha_proceso'
            );

        if ($request->has('nro_requerimiento')) {
            $query->where('numero_requerimiento', 'ILIKE', '%' . $request->nro_requerimiento . '%');
        }

        if ($request->has('nroSgdHt')) {
            $query->where('numero_sgd_ht', 'ILIKE', '%' . $request->nroSgdHt . '%');
        }

        if ($request->has('registrador_requerimiento')) {
            $query->where('created_us', $request->registrador_requerimiento);
        }

        if ($request->has('prioridad_requerimiento')) {
            $query->where('prioridad', $request->prioridad_requerimiento);
        }

        if ($request->has('tipo_producto_requerimiento')) {
            $query->where('id_tipo_producto', $request->tipo_producto_requerimiento);
        }

        if ($request->rangoInicio && $request->rangoFin) {
            $query->whereBetween('requerimientos_pfdm.created_at', [
                Carbon::parse($request->rangoInicio)->startOfDay(),
                Carbon::parse($request->rangoFin)->endOfDay()
            ]);
        }

        if ($request->estado_actual !== null) {
            $query->where('requerimientos_pfdm.estado_id', $request->estado_actual);
            $query->where('requerimientos_pfdm.ubicacion_id', 3);
        }

        $recordsFiltered = $query->count();

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->orderBy('id', 'asc')
            ->get();

        $result = [];

        foreach ($data as $row) {
            $result[] = [
                'requerimiento_id' => $row->id,
                'nro_requerimiento' => $row->numero_requerimiento,
                'tipo_doc_requerimiento' => $row->tipo_documento,
                'nro_doc_sgdht' => $row->numero_sgd_ht,
                'fechaReg_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'fechaEnvio_requerimiento' => Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                'prioridad_requerimiento' => $row->prioridad_nombre,
                'fecha_proceso_requerimiento' => $row->fecha_proceso != null ? Carbon::parse($row->fecha_proceso)->format('d/m/Y H:i:s') : null,
                'tipoProduc_requerimiento' => $row->tipo_producto_nombre,
                'registrador_requerimiento' => $row->registrador_nombre . ', ' . $row->registrador_paterno . ' ' . $row->registrador_materno,
                'estado_requerimiento' => $row->estado_id,
            ];
        }

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $result
        ]);
    }

    public function exportarCsv(Request $request)
    {
        Excel::create('requerimientos', function ($excel) use ($request) {
            $excel->sheet('Datos', function ($sheet) use ($request) {
                $query = RequerimientoPfdm::query()
                    ->join('users', 'requerimientos_pfdm.created_us', '=', 'users.id')
                    ->join('prioridades_requerimiento', 'requerimientos_pfdm.prioridad', '=', 'prioridades_requerimiento.id')
                    ->join('tipo_productos', 'requerimientos_pfdm.id_tipo_producto', '=', 'tipo_productos.id')
                    ->select(
                        'requerimientos_pfdm.*',
                        'users.name as registrador_nombre',
                        'users.paterno as registrador_paterno',
                        'users.materno as registrador_materno',
                        'prioridades_requerimiento.nombre as prioridad_nombre',
                        'tipo_productos.nombre as tipo_producto_nombre'
                    );

                if ($request->has('nro_requerimiento')) {
                    $query->where('numero_requerimiento', 'ILIKE', '%' . $request->nro_requerimiento . '%');
                }

                if ($request->has('registrador_requerimiento') && $request->registrador_requerimiento != null) {
                    $query->where('created_us', $request->registrador_requerimiento);
                }

                if ($request->has('prioridad_requerimiento')) {
                    $query->where('prioridad', $request->prioridad_requerimiento);
                }

                if ($request->has('tipo_producto_requerimiento')) {
                    $query->where('id_tipo_producto', $request->tipo_producto_requerimiento);
                }

                if ($request->rangoInicio && $request->rangoFin) {
                    $query->whereBetween('requerimientos_pfdm.created_at', [
                        Carbon::parse($request->rangoInicio)->startOfDay(),
                        Carbon::parse($request->rangoFin)->endOfDay()
                    ]);
                }

                /* if ($request->estado_actual !== null) {
                    $query->where('requerimientos_pfdm.estado_id', $request->estado_actual);
                } */
                if ($request->estado_actual !== null) {

                    if ((int) $request->estado_actual === 1) {
                        $query->where('requerimientos_pfdm.estado_id', '>', 1)
                            ->where('requerimientos_pfdm.estado_id', '<>', 8);
                    } elseif ((int) $request->estado_actual === 0) {
                        $query->where('requerimientos_pfdm.estado_id', 8);
                    }
                }

                $data = $query->orderBy('requerimientos_pfdm.id', 'asc')
                    ->get()
                    ->map(function ($row) {
                        return [
                            $row->id,
                            $row->numero_requerimiento,
                            Carbon::parse($row->created_at)->format('d/m/Y H:i:s'),
                            $row->prioridad_nombre,
                            $row->tipo_producto_nombre,
                            $row->registrador_nombre . ' ' . $row->registrador_paterno . ' ' . $row->registrador_materno,
                            ($row->estado_id == 1)
                            ? 'REGISTRADO'
                            : (($row->estado_id == 0) ? 'ANULADO' : 'ENVIADO')
                        ];
                    });

                $result = [];
                foreach ($data as $row) {
                    $result[] = [
                        'ID' => $row[0],
                        'N° Requerimiento' => $row[1],
                        /* 'Fecha Registro' => Carbon::parse($row[2])->format('d/m/Y H:i:s'), */
                        'Fecha Registro' => Carbon::createFromFormat(
                            'd/m/Y H:i:s',
                            $row[2]
                        )->format('d/m/Y H:i:s'),
                        'Prioridad' => $row[3],
                        'Tipo Producto' => $row[4],
                        'Registrador' => $row[5],
                        'Estado' => $row[6]
                    ];
                }

                $sheet->fromArray($result);
            });
        })->download('xlsx');
    }

    public function anularRequerimientoPorId($req_id)
    {
        DB::beginTransaction();

        try {

            $requerimiento = RequerimientoPfdm::find($req_id);

            if (!$requerimiento) {
                return response()->json([
                    'status' => false,
                    'message' => 'Requerimiento no encontrado'
                ], 404);
            }

            if ($requerimiento->estado_id == 8) {
                return response()->json([
                    'status' => false,
                    'message' => 'El requerimiento ya se encuentra anulado'
                ], 400);
            }

            $requerimiento->estado_id = 8;
            $requerimiento->save();

            Requerimientos_movimientos::create([
                'requerimiento_id' => $requerimiento->id,
                'estado_id' => $requerimiento->estado_id,
                'etapa_id' => $requerimiento->etapa_id,
                'ubicacion_id' => $requerimiento->ubicacion_id,
                'proceso_id' => $requerimiento->proceso_id,
                'orden' => 0,
                'id_registrador' => Auth::user()->id,
                'fecha_ingreso' => Carbon::now(),
                'observacion' => 'Requerimiento anulado'
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Requerimiento anulado correctamente'
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Error al anular el requerimiento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function verRequerimientoPorId($req_id)
    {
        try {
            $requerimiento = RequerimientoPfdm::with([
                'producto',
                'movimientos',
                'tipoProducto',
                'detalles' => function ($query) {
                    $query->orderBy('numero_entrega', 'asc');
                }
            ])
                ->find($req_id);

            if (!$requerimiento) {
                return response()->json([
                    'status' => false,
                    'message' => 'Requerimiento no encontrado'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Requerimiento encontrado.',
                'data' => $requerimiento
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error al obtener el requerimiento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function verMovimientoInternoAp($mov_id)
    {
        try {
            $movimiento = RequerimientoPfdm_MovimientosAp::with(['requerimiento.detalles', 'contrato.proveedor'])
                ->find($mov_id);

            $req = RequerimientoPfdm::where("id", $movimiento->requerimiento_id)->first();
            $lastMovimientoApContrato = $req->movimientosAp()
                ->where('tipo_movimiento', 3)
                ->where('etapa', 'CREACION DE ORDEN DE COMPRA')
                ->latest('id')
                ->first();

            $lastContrato = null;
            if ($lastMovimientoApContrato) {
                $lastContrato = $lastMovimientoApContrato->contrato;
            }

            if (!$movimiento) {
                return response()->json([
                    'status' => false,
                    'message' => 'Movimiento interno no encontrado'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Movimiento interno encontrado.',
                'data' => $movimiento,
                'ultimo_contrato' => $lastContrato
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error al obtener el requerimiento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function buscarPfDm(Request $request)
    {
        try {
            $q = $request->get('q');

            $resultados = Producto::join('unidades', 'productos.id_unidad', '=', 'unidades.id')
                ->where(function ($query) use ($q) {
                    $query->where('productos.nombre', 'ILIKE', "%{$q}%")
                        ->orWhere('productos.codigo', 'ILIKE', "%{$q}%");
                })
                ->where('productos.estado', '1')
                ->limit(10)
                ->get([
                    'productos.id',
                    'productos.codigo',
                    'productos.nombre',
                    'productos.codigo_siga',
                    'productos.codigo_sismed',
                    'productos.id_unidad',
                    'unidades.nombre as unidad_medida'
                ]);

            return response()->json($resultados);
        } catch (Exception $e) {
            return response()->json([], 500);
        }
    }

    public function buscarResponsable(Request $request)
    {
        try {
            $q = $request->get('q');

            $resultados = User::where(function ($query) use ($q) {
                $query->where('users.dni', 'ILIKE', "%{$q}%")
                    ->orWhere('users.name', 'ILIKE', "%{$q}%")
                    ->orWhere('users.paterno', 'ILIKE', "%{$q}%")
                    ->orWhere('users.materno', 'ILIKE', "%{$q}%");
            })
                ->where('users.status', '1')
                ->limit(10)
                ->get([
                    'users.id',
                    'users.dni',
                    'users.name',
                    'users.paterno',
                    'users.materno',
                ]);

            return response()->json($resultados);
        } catch (Exception $e) {
            return response()->json([], 500);
        }
    }

    public function buscarPorRUC(Request $request)
    {
        try {

            $q = trim($request->get('q'));

            $resultados = DB::table('proveedores')
                ->select(
                    'id',
                    'ruc_proveedor',
                    'nombre_proveedor',
                    'direccion_proveedor',
                    'telefono_proveedor'
                )
                ->where('ruc_proveedor', 'ILIKE', "%{$q}%")
                ->where('estado', 1)
                ->orderBy('nombre_proveedor')
                ->limit(10)
                ->get();

            return response()->json($resultados);
        } catch (\Exception $e) {

            return response()->json([], 500);
        }
    }

    public function detalleProductosAndProvedor(Request $request)
    {
        try {

            $requerimientoId = $request->req_id;

            $requerimiento = RequerimientoPfdm::with([
                'detalles'
            ])->find($requerimientoId);

            if (!$requerimiento) {
                return response()->json([], 404);
            }

            $movimientoAp = $requerimiento->movimientosAp()
                ->where('tipo_movimiento', 3)
                ->where('etapa', 'CREACION DE ORDEN DE COMPRA')
                ->latest('id')
                ->first();

            $contrato = null;

            if ($movimientoAp) {

                $contrato = $movimientoAp->contrato;
            }

            return response()->json([
                'productos' => $requerimiento->detalles,

                'movimiento_ap' => $movimientoAp,

                'contrato' => $contrato,

            ]);
        } catch (Exception $e) {

            return response()->json([
                'message' => 'Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function remitirRequerimiento(Request $request)
    {
        try {
            $requerimiento = RequerimientoPfdm::findOrFail($request->req_id);

            $requerimiento->estado_id = 2;
            $requerimiento->etapa_id = 2;
            $requerimiento->proceso_id = 2;
            $requerimiento->ubicacion_id = 1;

            $requerimiento->save();

            Requerimientos_movimientos::create([
                'requerimiento_id' => $requerimiento->id,
                'estado_id' => 2,
                'etapa_id' => 2,
                'ubicacion_id' => 1,
                'proceso_id' => 2,
                'orden' => 2,
                'id_registrador' => Auth::user()->id,
                'fecha_ingreso' => Carbon::now(),
                'observacion' => 'Requerimiento remitido a UE 020'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registro actualizado correctamente'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function recepcionarRequerimientoEntrada(Request $request)
    {
        try {
            $requerimiento = RequerimientoPfdm::findOrFail($request->req_id);

            $requerimiento->estado_id = 3;
            $requerimiento->ubicacion_id = 2;
            $requerimiento->etapa_id = 3;
            $requerimiento->proceso_id = 2;

            $requerimiento->save();

            Requerimientos_movimientos::create([
                'requerimiento_id' => $requerimiento->id,
                'estado_id' => 3,
                'etapa_id' => 3,
                'ubicacion_id' => 2,
                'proceso_id' => 2,
                'orden' => 3,
                'id_registrador' => Auth::user()->id,
                'fecha_ingreso' => Carbon::now(),
                'observacion' => 'Recepcionado en UE020'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registro recepcionado correctamente'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function recepcionarRequerimientoEntradaSs(Request $request)
    {
        try {
            $requerimiento = RequerimientoPfdm::findOrFail($request->req_id);

            $requerimiento->estado_id = 3;
            $requerimiento->ubicacion_id = 5;
            $requerimiento->etapa_id = 3;
            $requerimiento->proceso_id = 4;

            $requerimiento->save();

            Requerimientos_movimientos::create([
                'requerimiento_id' => $requerimiento->id,
                'estado_id' => 3,
                'etapa_id' => 3,
                'ubicacion_id' => 5,
                'proceso_id' => 5,
                'orden' => 9,
                'id_registrador' => Auth::user()->id,
                'fecha_ingreso' => Carbon::now(),
                'observacion' => 'Recepcionado en Sub-seccion de Actos Preparatorios'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registro recepcionado correctamente'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function recepcionarRequerimientoEntradaPc(Request $request)
    {
        try {
            $requerimiento = RequerimientoPfdm::findOrFail($request->req_id);

            $requerimiento->estado_id = 3;
            $requerimiento->ubicacion_id = 6;
            $requerimiento->etapa_id = 3;
            $requerimiento->proceso_id = 4;

            $requerimiento->save();

            $ultimoMovimiento = $requerimiento->movimientos()
                ->orderBy('orden', 'desc')
                ->first();

            $ultimoOrden = $ultimoMovimiento
                ? (int) $ultimoMovimiento->orden
                : 0;

            Requerimientos_movimientos::create([
                'requerimiento_id' => $requerimiento->id,
                'estado_id' => 3,
                'etapa_id' => 3,
                'ubicacion_id' => 6,
                'proceso_id' => 4,
                'orden' => $ultimoOrden + 1,
                'id_registrador' => Auth::user()->id,
                'fecha_ingreso' => Carbon::now(),
                'observacion' => 'Recepcionado en Sub-seccion de Proceso de Seleccion'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registro recepcionado correctamente'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function recepcionarRequerimientoEntradaEj(Request $request)
    {
        try {
            $requerimiento = RequerimientoPfdm::findOrFail($request->req_id);

            $requerimiento->estado_id = 3;
            $requerimiento->ubicacion_id = 7;
            $requerimiento->etapa_id = 3;
            $requerimiento->proceso_id = 4;

            $requerimiento->save();

            $ultimoMovimiento = $requerimiento->movimientos()
                ->orderBy('orden', 'desc')
                ->first();

            $ultimoOrden = $ultimoMovimiento
                ? (int) $ultimoMovimiento->orden
                : 0;

            Requerimientos_movimientos::create([
                'requerimiento_id' => $requerimiento->id,
                'estado_id' => 3,
                'etapa_id' => 3,
                'ubicacion_id' => 7,
                'proceso_id' => 4,
                'orden' => $ultimoOrden + 1,
                'id_registrador' => Auth::user()->id,
                'fecha_ingreso' => Carbon::now(),
                'observacion' => 'Recepcionado en Sub-seccion de Ejecucion Contractual'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registro recepcionado correctamente'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function recepcionarRequerimientoEntradaAl(Request $request)
    {
        try {
            $requerimiento = RequerimientoPfdm::findOrFail($request->req_id);

            $requerimiento->estado_id = 3;
            $requerimiento->etapa_id = 3;
            $requerimiento->ubicacion_id = 8;
            $requerimiento->proceso_id = 6;

            $requerimiento->save();

            $ultimoMovimiento = $requerimiento->movimientos()
                ->orderBy('orden', 'desc')
                ->first();

            $ultimoOrden = $ultimoMovimiento
                ? (int) $ultimoMovimiento->orden
                : 0;

            Requerimientos_movimientos::create([
                'requerimiento_id' => $requerimiento->id,
                'estado_id' => 3,
                'etapa_id' => 3,
                'ubicacion_id' => 8,
                'proceso_id' => 6,
                'orden' => $ultimoOrden + 1,
                'id_registrador' => Auth::user()->id,
                'fecha_ingreso' => Carbon::now(),
                'observacion' => 'Recepcionado en Sub-seccion de Almacen'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registro recepcionado correctamente'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function recepcionarRequerimientoAbastecimiento(Request $request)
    {
        try {
            $requerimiento = RequerimientoPfdm::findOrFail($request->req_id);

            $requerimiento->estado_id = 3;
            $requerimiento->ubicacion_id = 3;
            $requerimiento->proceso_id = 3;
            $requerimiento->etapa_id = 3;

            $requerimiento->save();

            Requerimientos_movimientos::create([
                'requerimiento_id' => $requerimiento->id,
                'estado_id' => 3,
                'etapa_id' => 3,
                'ubicacion_id' => 3,
                'proceso_id' => 3,
                'orden' => 5,
                'id_registrador' => Auth::user()->id,
                'fecha_ingreso' => Carbon::now(),
                'observacion' => 'Recepcionado en Area Abastecimiento'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registro recepcionado correctamente'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function recepcionarRequerimientoSeccion(Request $request)
    {
        try {
            $requerimiento = RequerimientoPfdm::findOrFail($request->req_id);

            $requerimiento->estado_id = 3;
            $requerimiento->ubicacion_id = 4;
            $requerimiento->proceso_id = 4;
            $requerimiento->etapa_id = 3;

            $requerimiento->save();

            Requerimientos_movimientos::create([
                'requerimiento_id' => $requerimiento->id,
                'estado_id' => 3,
                'etapa_id' => 3,
                'ubicacion_id' => 4,
                'proceso_id' => 4,
                'orden' => 7,
                'id_registrador' => Auth::user()->id,
                'fecha_ingreso' => Carbon::now(),
                'observacion' => 'Recepcionado en Seccion de Programacion y Adquisiciones'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registro recepcionado correctamente'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function observarRequerimientoAbastecimiento(Request $request)
    {
        try {
            $requerimiento = RequerimientoPfdm::findOrFail($request->req_id);

            $requerimiento->estado_id = 6;
            $requerimiento->ubicacion_id = 3;
            $requerimiento->proceso_id = 3;
            $requerimiento->etapa_id = 5;

            $requerimiento->save();

            Requerimientos_movimientos::create([
                'requerimiento_id' => $requerimiento->id,
                'estado_id' => 6,
                'etapa_id' => 5,
                'ubicacion_id' => 3,
                'proceso_id' => 3,
                'orden' => null,
                'id_registrador' => Auth::user()->id,
                'fecha_ingreso' => Carbon::now(),
                'observacion' => $request->observacion
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registro recepcionado correctamente'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function enviarRequerimientoProceso(Request $request)
    {
        try {
            $requerimiento = RequerimientoPfdm::findOrFail($request->req_id);

            $requerimiento->estado_id = 2;
            $requerimiento->ubicacion_id = 2;
            $requerimiento->etapa_id = 4;
            $requerimiento->proceso_id = 3;

            $requerimiento->save();

            Requerimientos_movimientos::create([
                'requerimiento_id' => $requerimiento->id,
                'estado_id' => 2,
                'etapa_id' => 4,
                'ubicacion_id' => 2,
                'proceso_id' => 3,
                'orden' => 4,
                'id_registrador' => $request->responsable_id,
                'fecha_ingreso' => Carbon::now(),
                'observacion' => $request->observacion
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registro recepcionado correctamente'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function enviarRequerimientoToSeccion(Request $request)
    {
        try {
            $requerimiento = RequerimientoPfdm::findOrFail($request->req_id);

            $requerimiento->estado_id = 2;
            $requerimiento->ubicacion_id = 3;
            $requerimiento->etapa_id = 6;
            $requerimiento->proceso_id = 4;

            $requerimiento->save();

            Requerimientos_movimientos::create([
                'requerimiento_id' => $requerimiento->id,
                'estado_id' => 2,
                'etapa_id' => 6,
                'ubicacion_id' => 3,
                'proceso_id' => 4,
                'orden' => 6,
                'id_registrador' => $request->responsable_id,
                'fecha_ingreso' => Carbon::now(),
                'observacion' => $request->observacion
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Enviado Seccion de Prog. y Adq.'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function enviarRequerimientoToSs(Request $request)
    {
        try {
            $requerimiento = RequerimientoPfdm::findOrFail($request->req_id);

            $requerimiento->estado_id = 2;
            $requerimiento->ubicacion_id = $requerimiento->ubicacion_id;
            $requerimiento->etapa_id = $request->etapa_id;
            $requerimiento->proceso_id = 4;

            $requerimiento->save();

            Requerimientos_movimientos::create([
                'requerimiento_id' => $requerimiento->id,
                'estado_id' => 2,
                'etapa_id' => $request->etapa_id,
                'ubicacion_id' => $requerimiento->ubicacion_id,
                'proceso_id' => 4,
                'orden' => 11,
                'id_registrador' => $request->responsable_id,
                'fecha_ingreso' => Carbon::now(),
                'observacion' => $request->observacion
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Enviado Sub seccion.'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function enviarRequerimientoToPc(Request $request)
    {
        try {
            $requerimiento = RequerimientoPfdm::findOrFail($request->req_id);

            $requerimiento->estado_id = 2;
            $requerimiento->ubicacion_id = $requerimiento->ubicacion_id;
            $requerimiento->etapa_id = $request->etapa_id;
            $requerimiento->proceso_id = 4;

            $requerimiento->save();

            $ultimoMovimiento = $requerimiento->movimientos()
                ->orderBy('orden', 'desc')
                ->first();

            $ultimoOrden = $ultimoMovimiento
                ? (int) $ultimoMovimiento->orden
                : 0;

            Requerimientos_movimientos::create([
                'requerimiento_id' => $requerimiento->id,
                'estado_id' => 2,
                'etapa_id' => $request->etapa_id,
                'ubicacion_id' => $requerimiento->ubicacion_id,
                'proceso_id' => 4,
                'orden' => $ultimoOrden + 1,
                'id_registrador' => $request->responsable_id,
                'fecha_ingreso' => Carbon::now(),
                'observacion' => $request->observacion
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Enviado Sub seccion.'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function enviarRequerimientoToEj(Request $request)
    {
        try {
            $requerimiento = RequerimientoPfdm::findOrFail($request->req_id);

            $requerimiento->estado_id = 2;
            $requerimiento->ubicacion_id = $requerimiento->ubicacion_id;
            $requerimiento->etapa_id = $request->etapa_id;
            $requerimiento->proceso_id = 6;

            $requerimiento->save();

            $ultimoMovimiento = $requerimiento->movimientos()
                ->orderBy('orden', 'desc')
                ->first();

            $ultimoOrden = $ultimoMovimiento
                ? (int) $ultimoMovimiento->orden
                : 0;

            Requerimientos_movimientos::create([
                'requerimiento_id' => $requerimiento->id,
                'estado_id' => 2,
                'etapa_id' => $request->etapa_id,
                'ubicacion_id' => $requerimiento->ubicacion_id,
                'proceso_id' => 6,
                'orden' => $ultimoOrden + 1,
                'id_registrador' => $request->responsable_id,
                'fecha_ingreso' => Carbon::now(),
                'observacion' => $request->observacion
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Enviado Sub seccion.'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function enviarRequerimientoToAl(Request $request)
    {
        try {
            $requerimiento = RequerimientoPfdm::findOrFail($request->req_id);

            $requerimiento->estado_id = 9;
            $requerimiento->etapa_id = 6;
            $requerimiento->proceso_id = 4;

            $requerimiento->save();

            $ultimoMovimiento = $requerimiento->movimientos()
                ->orderBy('orden', 'desc')
                ->first();

            $ultimoOrden = $ultimoMovimiento
                ? (int) $ultimoMovimiento->orden
                : 0;

            Requerimientos_movimientos::create([
                'requerimiento_id' => $requerimiento->id,
                'estado_id' => 9,
                'etapa_id' => 6,
                'ubicacion_id' => $requerimiento->ubicacion_id,
                'proceso_id' => 4,
                'orden' => $ultimoOrden + 1,
                'id_registrador' => Auth::user()->id,
                'fecha_ingreso' => Carbon::now(),
                'observacion' => 'Procesado en Almacen.'
            ]);

            if ($request->has('numero_entrega')) {

                $requerimiento->detalles()->delete();

                $detalles = [];

                foreach ($request->numero_entrega as $index => $numero) {
                    $detalles[] = [
                        'numero_entrega' => $numero,
                        'cantidad_parcial' => $request->cantidad_parcial[$index],
                        'numero_dias' => $request->numero_dias[$index],
                        'fecha_internamiento' => $request->fecha_internamiento[$index],
                        'estado' => $request->estado_entrega[$index],
                        'observacion' => $request->obs_entrega[$index],
                    ];
                }

                $requerimiento->detalles()->createMany($detalles);
            }

            return response()->json([
                'success' => true,
                'message' => 'Enviado Sub seccion.'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function enviarRequerimientoToSubSeccion(Request $request)
    {
        try {
            $requerimiento = RequerimientoPfdm::findOrFail($request->req_id);

            $requerimiento->estado_id = 2;
            $requerimiento->ubicacion_id = 4;
            $requerimiento->etapa_id = 7;
            $requerimiento->proceso_id = 5;

            $requerimiento->save();

            Requerimientos_movimientos::create([
                'requerimiento_id' => $requerimiento->id,
                'estado_id' => 2,
                'etapa_id' => 7,
                'ubicacion_id' => 4,
                'proceso_id' => 5,
                'orden' => 8,
                'id_registrador' => $request->responsable_id,
                'fecha_ingreso' => Carbon::now(),
                'observacion' => $request->observacion
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Enviado Sub-seccion de Actos preparatorios'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function verMovimientosRequerimiento($req_id)
    {
        $requerimiento = RequerimientoPfdm::query()
            ->leftJoin(
                'tipo_productos',
                'requerimientos_pfdm.id_tipo_producto',
                '=',
                'tipo_productos.id'
            )
            ->select(
                'requerimientos_pfdm.*',
                'tipo_productos.nombre as tp_nombre'
            )
            ->find($req_id);

        $subQuery = "
(
    SELECT mr1.id,
           mr1.requerimiento_id,
           mr1.observado,
           mr1.observacion,
           mr1.created_at
    FROM requerimiento_pfdm_movimientos_ap mr1
    INNER JOIN (
        SELECT requerimiento_id, MAX(id) as max_id
        FROM requerimiento_pfdm_movimientos_ap
        GROUP BY requerimiento_id
    ) mr2
        ON mr1.id = mr2.max_id
) as ultimo_movimiento_obs
";

        $detalles_consolidado = RequerimientoPfdm::query()
            ->join('requerimientos_pfdm_detalle', 'requerimientos_pfdm.id', '=', 'requerimientos_pfdm_detalle.id_requerimiento')
            ->join('productos', 'requerimientos_pfdm.id_producto', '=', 'productos.id')
            ->select(
                'requerimientos_pfdm.*',
                'productos.codigo as codigo_producto',
                'productos.nombre as descripcion_producto',
                DB::raw('COUNT(requerimientos_pfdm_detalle.id) as total_entregas')
            )
            ->where('requerimientos_pfdm.id', $req_id)
            ->groupBy(
                'requerimientos_pfdm.id',
                'requerimientos_pfdm.numero_requerimiento',
                'requerimientos_pfdm.tipo_documento',
                'requerimientos_pfdm.numero_oficio',
                'requerimientos_pfdm.fecha_oficio',
                'requerimientos_pfdm.numero_informe',
                'requerimientos_pfdm.fecha_informe',
                'requerimientos_pfdm.id_tipo_requerimiento',
                'requerimientos_pfdm.observacion_requerimiento',
                'requerimientos_pfdm.prioridad',
                'requerimientos_pfdm.observacion_prioridad',
                'requerimientos_pfdm.id_procedimiento_seleccion',
                'requerimientos_pfdm.id_tipo_proceso',
                'requerimientos_pfdm.id_servicio',
                'requerimientos_pfdm.numero_pedido_siga',
                'requerimientos_pfdm.denominacion',
                'requerimientos_pfdm.created_at',
                'requerimientos_pfdm.updated_at',
                'requerimientos_pfdm.id_tipo_producto',
                'requerimientos_pfdm.numero_sgd_ht',
                'requerimientos_pfdm.created_us',
                'requerimientos_pfdm.updated_us',
                'requerimientos_pfdm.id_unidad_destino',
                'requerimientos_pfdm.id_etapa',
                'requerimientos_pfdm.id_responsable',
                'requerimientos_pfdm.unidad_medida',
                'requerimientos_pfdm.cantidad_total',
                'requerimientos_pfdm.id_producto',
                'requerimientos_pfdm.estado_id',
                'requerimientos_pfdm.ubicacion_id',
                'requerimientos_pfdm.etapa_id',
                'requerimientos_pfdm.proceso_id',
                'productos.codigo',
                'productos.nombre'
            )
            ->get();

        $movimientos_requerimiento = Requerimientos_movimientos::where("requerimiento_pfdm_movimientos.requerimiento_id", $req_id)
            ->join('users', 'requerimiento_pfdm_movimientos.id_registrador', '=', 'users.id')
            ->leftJoin(DB::raw($subQuery), function ($join) {
                $join->on(
                    'requerimiento_pfdm_movimientos.requerimiento_id',
                    '=',
                    'ultimo_movimiento_obs.requerimiento_id'
                );
            })
            ->select(
                'requerimiento_pfdm_movimientos.*',
                'users.name as responsable_nombres',
                'users.paterno as responsable_paterno',
                'users.materno as responsable_materno',
                'ultimo_movimiento_obs.observado as estado_int_mov',
                'ultimo_movimiento_obs.observacion as ultima_observacion',
                'ultimo_movimiento_obs.created_at as fecha_ultima_observacion',
                DB::raw("
                    COALESCE(
                        LEAD(requerimiento_pfdm_movimientos.fecha_ingreso) 
                        OVER (PARTITION BY requerimiento_pfdm_movimientos.requerimiento_id 
                            ORDER BY requerimiento_pfdm_movimientos.fecha_ingreso)
                    ) as fecha_salida
                ")
            )
            ->get();

        $prioridades = [
            1 => 'ALTA',
            2 => 'MEDIA',
            3 => 'BAJA',
        ];
        $tipos_requerimiento = [
            1 => 'ANUAL',
            2 => 'EXTRAORDINARIO',
            3 => 'EMERGENCIA',
            4 => 'OTRO',
        ];
        $tipos_seleccion = [
            1 => 'MENOR A 8 UIT',
            2 => 'MAYOR A 8 UIT',
        ];
        $tipos_proceso = [
            1 => 'SUBASTA INVERSA ELECTRONICA',
            2 => 'LICITACION PUBLICA Y LPA',
            3 => 'COMPRA DIRECTA',
        ];
        $prioridades_color = [
            1 => 'red',
            2 => 'orange',
            3 => 'green',
        ];

        $pdf = \PDF::loadView(
            'frontend.requerimientos.pdf.historial_movimientos_requerimiento',
            compact('requerimiento', 'prioridades', 'prioridades_color', 'tipos_requerimiento', 'tipos_seleccion', 'tipos_proceso', 'detalles_consolidado', 'movimientos_requerimiento')
        );

        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('movimientos_requerimiento_nro_' . $req_id . '.pdf');
    }
}
