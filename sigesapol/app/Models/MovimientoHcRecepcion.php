<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class MovimientoHcRecepcion extends Model
{
    protected $table = 'movimiento_hc_recepcion';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_movimiento_hc_solicitud',
        'id_establecimiento',
        'anio_ingreso',
        'nro_ingreso',
        'recepcion_at',
        'recepcion_us',
        'persona_devuelve',
        'id_documento_devolucion',
        'nro_documento_devolucion',
        'observacion_recepcion',
        'id_estado_reg',
        'created_us',
        'updated_us',
    ];

    /**
     * Siguiente correlativo de ingreso por IPRESS y año (entero, sin formato).
     */
    public function generarNroIngreso($id_establecimiento, $anio_ingreso = null)
    {
        $anio_ingreso = $anio_ingreso ?: date('Y');

        $ultimo = DB::table('movimiento_hc_recepcion')
            ->where('id_establecimiento', $id_establecimiento)
            ->where('anio_ingreso', $anio_ingreso)
            ->max('nro_ingreso');

        return ((int) $ultimo) + 1;
    }

    /**
     * Ingresos (recepciones) de un establecimiento, del más reciente al más antiguo. Filtros opcionales:
     * id_solicitud, desde / hasta (Y-m-d sobre recepcion_at) e id_servicio (de la solicitud).
     * Por cada ingreso: historias despachadas por la solicitud, devueltas en ese ingreso (FK del detalle)
     * y devueltas acumuladas hasta ese ingreso (para calcular los pendientes).
     */
    public function listarIngresos($id_establecimiento, $filtros = array())
    {
        $query = DB::table('movimiento_hc_recepcion as r')
            ->join('movimiento_hc_solicitud as s', 'r.id_movimiento_hc_solicitud', '=', 's.id')
            ->leftJoin('consultorios as c', 's.id_servicio', '=', 'c.id')
            ->leftJoin('users as u', 'r.recepcion_us', '=', 'u.id')
            ->select('r.id', 'r.anio_ingreso', 'r.nro_ingreso', 'r.recepcion_at', 'r.persona_devuelve',
                'r.id_documento_devolucion', 'r.nro_documento_devolucion',
                's.anio as solicitud_anio', 's.numero as solicitud_numero', 'c.nombre as servicio',
                'u.paterno as user_paterno', 'u.materno as user_materno', 'u.name as user_nombre',
                DB::raw('(SELECT COUNT(*) FROM movimiento_hc_detalle d WHERE d.id_movimiento_hc_solicitud = s.id AND d.estado > 0) as despachadas'),
                DB::raw('(SELECT COUNT(*) FROM movimiento_hc_detalle d WHERE d.id_movimiento_hc_recepcion = r.id) as devueltas'),
                DB::raw('(SELECT COUNT(*) FROM movimiento_hc_detalle d WHERE d.id_movimiento_hc_solicitud = s.id AND d.id_movimiento_hc_recepcion <= r.id) as devueltas_acumuladas'))
            ->where('r.id_establecimiento', $id_establecimiento);

        if (!empty($filtros['id_solicitud'])) {
            $query->where('r.id_movimiento_hc_solicitud', $filtros['id_solicitud']);
        }
        if (!empty($filtros['desde'])) {
            $query->where('r.recepcion_at', '>=', $filtros['desde'] . ' 00:00:00');
        }
        if (!empty($filtros['hasta'])) {
            $query->where('r.recepcion_at', '<=', $filtros['hasta'] . ' 23:59:59');
        }
        if (!empty($filtros['id_servicio'])) {
            $query->where('s.id_servicio', $filtros['id_servicio']);
        }

        return $query->orderBy('r.id', 'desc')->get();
    }

    /**
     * Historias recepcionadas en cada ingreso (movimiento_hc_detalle.id_movimiento_hc_recepcion).
     */
    public function historiasDeIngresos(array $ids_ingresos)
    {
        if (empty($ids_ingresos)) {
            return array();
        }

        return DB::table('movimiento_hc_detalle as d')
            ->join('asegurado_historias as ah', 'd.id_asegurado_historia', '=', 'ah.id')
            ->join('asegurados as ase', 'ah.id_asegurado', '=', 'ase.id')
            ->select('d.id_movimiento_hc_recepcion', 'ah.nro_historia', 'ase.nro_doc_ident',
                'ase.paterno', 'ase.materno', 'ase.nombre')
            ->whereIn('d.id_movimiento_hc_recepcion', $ids_ingresos)
            ->orderBy('ase.paterno')->orderBy('ase.materno')->orderBy('ase.nombre')
            ->get();
    }

    /**
     * Código visible del ingreso: IN-AAAA-NNNNNN.
     */
    public function formatoNroIngreso($anio_ingreso, $nro_ingreso)
    {
        return 'IN-' . $anio_ingreso . '-' . str_pad($nro_ingreso, 6, '0', STR_PAD_LEFT);
    }
}
