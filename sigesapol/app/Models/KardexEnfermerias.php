<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KardexEnfermerias extends Model
{
    protected $table = 'kardex_enfermerias';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_enfermeria',
        'fecha_firma',
        'ruta_firma',
        'flag_firma',
        'created_us',
        'created_at',
        'id_turno',
        'updated_users'
    ];

    public function getAllByEmergenciaId($emergenciaId)
    {
        $results = DB::table('kardex_enfermerias as ke')
            ->selectRaw('
                            ke.id as kardex_enfermerias_id,
                            e.id as enfermeria_id,
                            e2.id as emergencia_id,
                            ke.created_at::timestamp::date as fecha,
                            ke.created_at::timestamp::time as hora,
                            t2.descripcion as turno,
                            c3.nombre as cuarto,
                            c.nombre as servicio,
                            m.nombre as medico_nombre,
                            m.paterno as medico_paterno,
                            m.materno as medico_materno,
                            ke.estado as kardex_enfermerias_estado
                        ')
            ->join('enfermerias as e', 'ke.id_enfermeria', '=', 'e.id')
            ->join('users as u', 'ke.created_us', '=', 'u.id')
            ->leftJoin('medicos as m', 'u.dni', '=', 'm.dni')
            ->join('emergencias as e2', 'e.id_ext_enfermeria', '=', 'e2.id')
            ->join('camas as c2', 'e2.cama_estado', '=', 'c2.id')
            ->join('cuartos as c3', 'c2.id_cuarto', '=', 'c3.id')
            ->leftJoin('triajes as t', 'e2.id_triaje', '=', 't.id')
            ->leftJoin('sub_consultorios as sc', 't.id_sub_consultorio', '=', 'sc.id')
            ->leftJoin('consultorios as c', 'sc.id_consultorio', '=', 'c.id')
            ->join('turnos as t2', 'ke.id_turno', '=', 't2.id')
            ->where('e2.id', $emergenciaId)
            ->orderBy('ke.created_at', 'asc')
            ->get();
        return $results;
    }

    public function searchByFilters(Request $request)
    {
        $query = DB::table('kardex_enfermerias as ke')
            ->selectRaw('
                            ke.id as kardex_enfermerias_id,
                            ke.fecha_firma as kardex_enfermerias_fecha_firma,
                            ke.ruta_firma as kardex_enfermerias_ruta_firma,
                            ke.flag_firma as kardex_enfermerias_flag_firma,
                            e.id as enfermeria_id,
                            e2.id as emergencia_id,
                            ke.created_at::timestamp::date as fecha,
                            ke.created_at::timestamp::time as hora,
                            t2.descripcion as turno,
                            c3.nombre as cuarto,
                            c.nombre as servicio,
                            m.nombre as medico_nombre,
                            m.paterno as medico_paterno,
                            m.materno as medico_materno,
                            ke.estado as kardex_enfermerias_estado
                        ')
            ->join('enfermerias as e', 'ke.id_enfermeria', '=', 'e.id')
            ->join('users as u', 'ke.created_us', '=', 'u.id')
            ->leftJoin('medicos as m', 'u.dni', '=', 'm.dni')
            ->join('emergencias as e2', 'e.id_ext_enfermeria', '=', 'e2.id')
            ->join('camas as c2', 'e2.cama_estado', '=', 'c2.id')
            ->join('cuartos as c3', 'c2.id_cuarto', '=', 'c3.id')
            ->leftJoin('triajes as t', 'e2.id_triaje', '=', 't.id')
            ->leftJoin('sub_consultorios as sc', 't.id_sub_consultorio', '=', 'sc.id')
            ->leftJoin('consultorios as c', 'sc.id_consultorio', '=', 'c.id')
            ->join('turnos as t2', 'ke.id_turno', '=', 't2.id');
        //->where('e2.id', $emergenciaId)
        //->orderBy('ke.created_at', 'asc');
        if (!empty($request->query('emergencia_id'))) {
            $query->where('e2.id', $request->query('emergencia_id'));
        }
        if (!empty($request->query('fecha_inicio'))) {
            $query->whereDate('ke.created_at', '>=', $request->query('fecha_inicio'));
        }
        if (!empty($request->query('fecha_fin'))) {
            $dateCarbon = Carbon::createFromFormat('Y-m-d', $request->query('fecha_fin'));
            $fecha_fin_add_day = $dateCarbon->addDay()->format('Y-m-d');
            $query->whereDate('ke.created_at', '<', $fecha_fin_add_day);
        }
        if (!empty($request->query('turno_id'))) {
            $query->where('ke.id_turno', $request->query('turno_id'));
        }


        $results = $query->orderBy('ke.created_at', 'asc')->get();
        return $results;
    }

    public function searchByFiltersHospitalizacion(Request $request)
    {
        $query = DB::table('kardex_enfermerias as ke')
            ->selectRaw('
                            ke.id as kardex_enfermerias_id,
                            ke.fecha_firma as kardex_enfermerias_fecha_firma,
                            ke.ruta_firma as kardex_enfermerias_ruta_firma,
                            ke.flag_firma as kardex_enfermerias_flag_firma,
                            e.id as enfermeria_id,
                            h1.id as hospitalizacion_id,
                            ke.created_at::timestamp::date as fecha,
                            ke.created_at::timestamp::time as hora,
                            t2.descripcion as turno,
                            c2.nombre as cama,
                            c3.nombre as cuarto,
                            c.nombre as servicio,
                            m.nombre as medico_nombre,
                            m.paterno as medico_paterno,
                            m.materno as medico_materno,
                            ke.estado as kardex_enfermerias_estado
                        ')
            ->join('enfermerias as e', 'ke.id_enfermeria', '=', 'e.id')
            ->join('users as u', 'ke.created_us', '=', 'u.id')
            ->leftJoin('medicos as m', 'u.dni', '=', 'm.dni')
            /* ->join('emergencias as e2', 'e.id_ext_enfermeria', '=', 'e2.id') */
            ->join('hospitalizaciones as h1', 'e.id_ext_enfermeria', '=', 'h1.id')
            ->join('camas as c2', 'h1.id_cama', '=', 'c2.id')
            ->join('cuartos as c3', 'c2.id_cuarto', '=', 'c3.id')
            /* ->leftJoin('triajes as t', 'e2.id_triaje', '=', 't.id') */
            /* ->leftJoin('sub_consultorios as sc', 't.id_sub_consultorio', '=', 'sc.id') */
            ->leftJoin('consultorios as c', 'h1.id_consultorio', '=', 'c.id')
            ->join('turnos as t2', 'ke.id_turno', '=', 't2.id');
        //->where('e2.id', $emergenciaId)
        //->orderBy('ke.created_at', 'asc');
        if (!empty($request->query('hospitalizacion_id'))) {
            $query->where('h1.id', $request->query('hospitalizacion_id'));
        }
        if (!empty($request->query('fecha_inicio'))) {
            $query->whereDate('ke.created_at', '>=', $request->query('fecha_inicio'));
        }
        if (!empty($request->query('fecha_fin'))) {
            $dateCarbon = Carbon::createFromFormat('Y-m-d', $request->query('fecha_fin'));
            $fecha_fin_add_day = $dateCarbon->addDay()->format('Y-m-d');
            $query->whereDate('ke.created_at', '<', $fecha_fin_add_day);
        }
        if (!empty($request->query('turno_id'))) {
            $query->where('ke.id_turno', $request->query('turno_id'));
        }


        $results = $query->orderBy('ke.created_at', 'asc')->get();
        return $results;
    }

    public function allKardexesByAseguradoId($asegurado_id, $tipo_atencion_id)
    {
        $query = DB::table('kardex_enfermerias as ke')
            ->selectRaw('
                            ke.id as kardex_enfermerias_id,
                            ke.fecha_firma as kardex_enfermerias_fecha_firma,
                            ke.ruta_firma as kardex_enfermerias_ruta_firma,
                            ke.flag_firma as kardex_enfermerias_flag_firma,
                            enf.id as enfermeria_id,
                            ke.created_at::timestamp::date as fecha,
                            ke.created_at::timestamp::time as hora,
                            t2.descripcion as turno,
                            c2.nombre as cama,
                            c3.nombre as cuarto,
                            c.nombre as servicio,
                            m.nombre as medico_nombre,
                            m.paterno as medico_paterno,
                            m.materno as medico_materno,
                            ke.estado as kardex_enfermerias_estado,
                            CASE 
                                WHEN '. $tipo_atencion_id .' = 2 THEN ext.id
                                WHEN '. $tipo_atencion_id .' = 3 THEN ext.id
                            ELSE NULL
                            END as id_ext
                        ')
            ->join('users as u', 'ke.created_us', '=', 'u.id')
            ->leftJoin('medicos as m', 'u.dni', '=', 'm.dni')
            ->join('turnos as t2', 'ke.id_turno', '=', 't2.id')
            ->join("enfermerias as enf", "enf.id", "=", "ke.id_enfermeria");
        if ($tipo_atencion_id == 2) {
            // Si es atención de emergencia
            $query->join('emergencias as ext', 'ext.id', '=', 'enf.id_ext_enfermeria')
                ->join('camas as c2', 'ext.cama_estado', '=', 'c2.id')
                ->join('cuartos as c3', 'c2.id_cuarto', '=', 'c3.id')
                ->leftJoin('triajes as t', 'ext.id_triaje', '=', 't.id')
                ->leftJoin('sub_consultorios as sc', 't.id_sub_consultorio', '=', 'sc.id')
                ->leftJoin('consultorios as c', 'sc.id_consultorio', '=', 'c.id')
                ->join('asegurados as aseg', 'aseg.id', '=', 'ext.id_asegurado');
        } elseif ($tipo_atencion_id == 3) {
            // Si es atención hospitalaria
            $query->join('hospitalizaciones as ext', 'ext.id', '=', 'enf.id_ext_enfermeria')
                ->join('camas as c2', 'ext.id_cama', '=', 'c2.id')
                ->join('cuartos as c3', 'c2.id_cuarto', '=', 'c3.id')
                ->leftJoin('consultorios as c', 'ext.id_consultorio', '=', 'c.id')
                ->join('asegurados as aseg', 'aseg.id', '=', 'ext.id_asegurado');
        }
        $query->where("aseg.id", $asegurado_id);
        $results = $query->orderBy('ke.created_at', 'asc')->get();
        return $results;
    }
}
