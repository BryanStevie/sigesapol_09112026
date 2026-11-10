<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class MovimientoHcSolicitud extends Model
{
    protected $table = 'movimiento_hc_solicitud';
    protected $primaryKey = 'id';

    protected $fillable = [
        'numero', 'id_establecimiento', 'id_servicio', 'servicio_otro', 'id_user_responsable','id_documento', 'responsable',
        'estado', 'observacion', 'anio', 'created_us', 'updated_us',
        'id_canal_ingreso', 'id_motivo', 'nro_documento', 'fecha_documento', 'fecha_requerida', 'fecha_retorno_prevista',
        'cantidad_hc', 'id_prioridad', 'id_lugar_archivo', 'observacion_archivo',
        'fecha_hora_registro', 'fecha_hora_despacho', 'documento_ruta',
    ];

    public function actualizarEstadoHistorias(array $ids_historias, $estado_hc, $id_movimiento)
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', $ids_historias))));

        if (empty($ids)) {
            return 0;
        }

        return DB::table('asegurado_historias')
            ->whereIn('id', $ids)
            ->update([
                'estado_hc'                  => $estado_hc,
                'id_movimiento_hc_solicitud' => $id_movimiento,
            ]);
    }

    /**
     * Ids de asegurado_historias vigentes (estado > 0) en el detalle de una solicitud.
     */
    public function idsHistoriasDetalle($id_movimiento)
    {
        return DB::table('movimiento_hc_detalle')
            ->where('id_movimiento_hc_solicitud', $id_movimiento)
            ->where('estado', '>', 0)
            ->lists('id_asegurado_historia');
    }

    public function actualizarEstadoDetalle($id_movimiento, $estado)
    {
        return DB::table('movimiento_hc_detalle')
            ->where('id_movimiento_hc_solicitud', $id_movimiento)
            ->where('estado', '>', 0)
            ->update(['estado' => $estado]);
    }

    /**
     * Historias quitadas de un borrador: vuelven a estado_hc = 0 (sin solicitud) y su fila del detalle
     * queda anulada (estado = 0, deleted_at, deleted_us).
     */
    public function liberarHistoriasQuitadas($id_movimiento, array $ids_vigentes, $id_usuario)
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', $ids_vigentes))));

        $query = DB::table('movimiento_hc_detalle')
            ->where('id_movimiento_hc_solicitud', $id_movimiento)
            ->where('estado', '>', 0);

        if (!empty($ids)) {
            $query->whereNotIn('id_asegurado_historia', $ids);
        }

        $quitadas = $query->lists('id_asegurado_historia');

        if (empty($quitadas)) {
            return 0;
        }

        DB::table('asegurado_historias')->whereIn('id', $quitadas)->update([
            'estado_hc'                  => 0,
            'id_movimiento_hc_solicitud' => null,
        ]);

        return DB::table('movimiento_hc_detalle')
            ->where('id_movimiento_hc_solicitud', $id_movimiento)
            ->where('estado', '>', 0)
            ->whereIn('id_asegurado_historia', $quitadas)
            ->update([
                'estado'     => 0,
                'deleted_at' => date('Y-m-d H:i:s'),
                'deleted_us' => $id_usuario,
            ]);
    }

    /**
     * Ids de asegurado_historias de la salida que siguen fuera (detalle en estado 2).
     */
    public function idsHistoriasPendientes($id_movimiento)
    {
        return DB::table('movimiento_hc_detalle')
            ->where('id_movimiento_hc_solicitud', $id_movimiento)
            ->where('estado', 2)
            ->lists('id_asegurado_historia');
    }

    /**
     * Recepcion en Archivo: el detalle pasa a estado 3 (con la recepcion que lo recibio y la auditoria
     * de actualizacion) y se limpia asegurado_historias (estado_hc = 0, id_movimiento_hc_solicitud = NULL).
     */
    public function marcarHistoriasDevueltas($id_movimiento, array $ids_historias, $id_recepcion, $id_usuario)
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', $ids_historias))));

        if (empty($ids)) {
            return 0;
        }

        DB::table('asegurado_historias')
            ->whereIn('id', $ids)
            ->where('id_movimiento_hc_solicitud', $id_movimiento)
            ->update([
                'estado_hc'                  => 0,
                'id_movimiento_hc_solicitud' => null,
            ]);

        return DB::table('movimiento_hc_detalle')
            ->where('id_movimiento_hc_solicitud', $id_movimiento)
            ->where('estado', 2)
            ->whereIn('id_asegurado_historia', $ids)
            ->update([
                'estado'                        => 3,
                'id_movimiento_hc_recepcion'    => $id_recepcion,
                'observacion_archivo_recepcion' => null,
                'updated_at'                    => date('Y-m-d H:i:s'),
                'updated_us'                    => $id_usuario,
            ]);
    }

    /**
     * Historias de la salida que no se devolvieron en este ingreso: siguen en estado 2.
     */
    public function marcarHistoriasNoDevueltas($id_movimiento, array $ids_historias, $observacion)
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', $ids_historias))));

        if (empty($ids)) {
            return 0;
        }

        return DB::table('movimiento_hc_detalle')
            ->where('id_movimiento_hc_solicitud', $id_movimiento)
            ->where('estado', 2)
            ->whereIn('id_asegurado_historia', $ids)
            ->update(['observacion_archivo_recepcion' => $observacion]);
    }

    public function historiasDeMovimiento($id_movimiento)
    {
        return DB::table('movimiento_hc_detalle as d')
            ->join('asegurado_historias as ah', 'd.id_asegurado_historia', '=', 'ah.id')
            ->join('asegurados as ase', 'ah.id_asegurado', '=', 'ase.id')
            ->select('ah.id', 'ah.nro_historia', 'ase.tipo_doc_ident', 'ase.nro_doc_ident',
                'ase.paterno', 'ase.materno', 'ase.nombre')
            ->where('d.id_movimiento_hc_solicitud', $id_movimiento)
            ->where('d.estado', '>', 0)
            ->orderBy('ase.paterno')->orderBy('ase.materno')->orderBy('ase.nombre')
            ->get();
    }

    /**
     * Historias que no estan en Archivo (estado_hc <> 0) y que no pertenecen a la solicitud indicada.
     * Bloquea las filas (FOR UPDATE) para que dos usuarios no tomen la misma HC; usar dentro de una transaccion.
     */
    public function historiasNoDisponibles(array $ids_historias, $id_movimiento = null)
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', $ids_historias))));

        if (empty($ids)) {
            return array();
        }

        $query = DB::table('asegurado_historias as ah')
            ->select('ah.id', 'ah.nro_historia', 'ah.estado_hc')
            ->whereIn('ah.id', $ids)
            ->whereRaw('COALESCE(ah.estado_hc, 0) <> 0');

        if ($id_movimiento) {
            $query->where(function ($q) use ($id_movimiento) {
                $q->whereNull('ah.id_movimiento_hc_solicitud')
                  ->orWhere('ah.id_movimiento_hc_solicitud', '<>', $id_movimiento);
            });
        }

        return $query->lockForUpdate()->get();
    }

    public function buscarHistorias($id_establecimiento, $termino, $limite = 15)
    {
        $termino = mb_strtoupper(trim(preg_replace('/\s+/', ' ', $termino)));

        $query = DB::table('asegurado_historias as ah')
            ->join('asegurados as ase', 'ah.id_asegurado', '=', 'ase.id')
            ->select('ah.id', 'ah.nro_historia', 'ase.tipo_doc_ident', 'ase.nro_doc_ident',
                'ase.paterno', 'ase.materno', 'ase.nombre', 'ah.estado_hc', 'ah.id_movimiento_hc_solicitud')
            ->where('ah.id_establecimiento', $id_establecimiento)
            ->where('ase.id_estado_reg', 1)
            ->where('ah.id_estado_reg', 1);

        if (ctype_digit($termino)) {
            $query->where(function ($q) use ($termino) {
                $q->where('ah.nro_historia', 'like', '%' . $termino)
                  ->orWhere('ase.nro_doc_ident', $termino);
            });
        } else {
            $palabras = explode(' ', $termino);
            $prefijo = function ($palabra) {
                return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $palabra) . '%';
            };

            $query->whereRaw('UPPER(ase.paterno) LIKE ?', [$prefijo($palabras[0])]);

            if (isset($palabras[1])) {
                $query->whereRaw('UPPER(ase.materno) LIKE ?', [$prefijo($palabras[1])]);
            }
        }

        return $query->orderBy('ase.paterno')->orderBy('ase.materno')->orderBy('ase.nombre')
            ->limit($limite)->get();
    }

    public function listarSolicitudes($id_establecimiento, $filtros = array())
    {
        $query = DB::table('movimiento_hc_solicitud as m')
            ->leftJoin('consultorios as c', 'm.id_servicio', '=', 'c.id')
            ->join('users as us_despacho', 'm.created_us', '=', 'us_despacho.id')
            ->select('m.*', DB::raw('CASE WHEN m.id_servicio = 0 THEN m.servicio_otro ELSE c.nombre END as servicio_solicitante'), 'us_despacho.paterno as user_paterno_despacho', 'us_despacho.materno as user_materno_despacho', 'us_despacho.name as user_nombre_despacho')
            ->where('m.id_establecimiento', $id_establecimiento);

        if (!empty($filtros['desde'])) {
            $query->where('m.fecha_hora_registro', '>=', $filtros['desde'] . ' 00:00:00');
        }
        if (!empty($filtros['hasta'])) {
            $query->where('m.fecha_hora_registro', '<=', $filtros['hasta'] . ' 23:59:59');
        }
        if (isset($filtros['id_servicio']) && $filtros['id_servicio'] !== '') {
            $query->where('m.id_servicio', $filtros['id_servicio']);
        }
        if (!empty($filtros['id_motivo'])) {
            $query->where('m.id_motivo', $filtros['id_motivo']);
        }
        if (!empty($filtros['estado'])) {
            $query->whereIn('m.estado', (array) $filtros['estado']);
        }
        if (!empty($filtros['texto'])) {
            $texto = mb_strtoupper(trim($filtros['texto']));
            $numero = null;

            if (preg_match('/^SOL-(\d{4})-(\d+)$/', $texto, $coincidencia)) {
                $numero = str_pad($coincidencia[2], 5, '0', STR_PAD_LEFT);
                $query->where('m.anio', $coincidencia[1]);
            } elseif (ctype_digit($texto)) {
                $numero = str_pad($texto, 5, '0', STR_PAD_LEFT);
            }

            $query->where(function ($q) use ($texto, $numero) {
                if ($numero) {
                    $q->where('m.numero', $numero);
                }
                $q->orWhereExists(function ($e) use ($texto) {
                    $e->select(DB::raw(1))
                        ->from('movimiento_hc_detalle as d')
                        ->join('asegurado_historias as ah', 'd.id_asegurado_historia', '=', 'ah.id')
                        ->join('asegurados as ase', 'ah.id_asegurado', '=', 'ase.id')
                        ->whereRaw('d.id_movimiento_hc_solicitud = m.id')
                        ->where('d.estado', '>', 0)
                        ->where(function ($x) use ($texto) {
                            $x->where('ah.nro_historia', 'like', '%' . $texto)
                              ->orWhere('ase.nro_doc_ident', $texto);
                        });
                });
            });
        }

        return $query->orderBy('m.id', 'desc')->get();
    }

    public function historiasDeMovimientos(array $ids_movimientos, $estado = null)
    {
        if (empty($ids_movimientos)) {
            return array();
        }

        $query = DB::table('movimiento_hc_detalle as d')
            ->join('asegurado_historias as ah', 'd.id_asegurado_historia', '=', 'ah.id')
            ->join('asegurados as ase', 'ah.id_asegurado', '=', 'ase.id')
            ->leftJoin('movimiento_hc_recepcion as r', 'd.id_movimiento_hc_recepcion', '=', 'r.id')
            ->select('ah.id', 'd.id_movimiento_hc_solicitud as id_movimiento_historia', 'ah.nro_historia', 'ase.nro_doc_ident',
                'ase.paterno', 'ase.materno', 'ase.nombre', 'd.estado as estado_hc', 'r.recepcion_at', 'r.anio_ingreso', 'r.nro_ingreso')
            ->whereIn('d.id_movimiento_hc_solicitud', $ids_movimientos);

        if ($estado) {
            $query->where('d.estado', $estado);
        } else {
            $query->where('d.estado', '>', 0);
        }

        return $query->orderBy('ase.paterno')->orderBy('ase.materno')->orderBy('ase.nombre')->get();
    }

    public function generarNroMovimiento($id_establecimiento, $anio = null)
    {
        $anio = $anio ?: date('Y');

        $ultimo = DB::table('movimiento_hc_solicitud')
            ->where('id_establecimiento', $id_establecimiento)
            ->where('anio', (string) $anio)
            ->max(DB::raw('CAST(numero AS INTEGER)'));

        return str_pad(((int) $ultimo) + 1, 5, '0', STR_PAD_LEFT);
    }
}
