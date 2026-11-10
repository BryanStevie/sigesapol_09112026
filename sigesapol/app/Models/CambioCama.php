<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CambioCama extends Model
{
    protected $table = 'cambio_camas';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_cama_atencion',     // id de la cama_origen
        'estado_ubicacion',     // tipo: 1 = misma cama, 2 = rotacion, 3 = traslado, 4 = rotacion despositado
        'fecha_hora_inicio',    // fecha y hora de cambio
        'id_cama',              // id de la cama_destino
        'id_prestacion',        // id_prestacion
        'id_evolucion',         // id_evolucion
        'estado',               
        'created_at',           
        'updated_at',           
        'medico_id',            // id del medico
        'id_tipo_traslado',     // 1 => PROCEDIMIENTO QX, 2 => DE SERVICIO
        'id_consultorio',
        'id_upss_encargado',
        'id_servicio_encargado',
        'id_tipo_atencion',
        'id_ext',
        'id_upss_encargado_atencion',
        'id_servicio_encargado_atencion'
    ];

    public function getByPrestacionId($prestacionId) {
        $result = DB::table('cambio_camas AS t1')
                ->select(
                    't1.id',
                    DB::raw('t1.fecha_hora_inicio::date AS fecha_cambio'),
                    DB::raw('t1.fecha_hora_inicio::time AS hora_cambio'),
                    't1.estado_ubicacion',
                    DB::raw("
                        CASE
                            WHEN t1.estado_ubicacion IN ('1', '2') THEN 'ROTACIÓN'
                            WHEN t1.estado_ubicacion = '3' THEN 'TRASLACIÓN'
                            WHEN t1.estado_ubicacion = '4' THEN 'ORIGEN'
                            ELSE 'ROTACIÓN'
                        END AS cambio_cama_tipo_nombre
                    "),
                    't1.id_prestacion',
                    't1.id_evolucion',
                    't1.estado',
                    't2.id AS cama_origen_id',
                    't2.nombre AS cama_origen_nombre',
                    't3.id AS cuarto_origen_id',
                    't3.nombre AS cuarto_origen_nombre',
                    't4.id AS servicio_origen_id',
                    't4.nombre AS servicio_origen_nombre',
                    't5.id AS camas_destino_id',
                    't5.nombre AS cama_destino_nombre',
                    't6.id AS cuarto_destino_id',
                    't6.nombre AS cuarto_destino_nombre',
                    't7.id AS servicio_destino_id',
                    't7.nombre AS servicio_destino_nombre',
                    't8.dni AS medico_dni',
                    't8.nombre AS medico_nombre',
                    't8.paterno AS medico_paterno',
                    't8.materno AS medico_materno'
                )
                ->join('camas AS t2', 't1.id_cama_atencion', '=', 't2.id')
                ->join('cuartos AS t3', 't2.id_cuarto', '=', 't3.id')
                ->join('asignacion_servicios AS ags', 'ags.id', '=', 't3.id_asignacion_servicio')
                ->join('consultorios AS t4', 'ags.id_consultorio', '=', 't4.id')
                ->join('camas AS t5', 't1.id_cama', '=', 't5.id')
                ->join('cuartos AS t6', 't5.id_cuarto', '=', 't6.id')
                ->join('asignacion_servicios AS ags2', 'ags2.id', '=', 't6.id_asignacion_servicio')
                ->join('consultorios AS t7', 'ags2.id_consultorio', '=', 't7.id')
                ->leftJoin('medicos AS t8', 't1.medico_id', '=', 't8.id')
                ->where('t1.id_prestacion', $prestacionId)
                ->orderBy('t1.created_at', 'desc')
                ->get();

        return $result;
    }

    public function getTrasladoByIdEvolucion($id_evolucion) {
        $result = DB::table('cambio_camas AS t1')
                ->select(
                    't1.id',
                    DB::raw("TO_CHAR(t1.fecha_hora_inicio::date, 'DD-MM-YYYY') AS fecha_cambio_formateado"),  
                    DB::raw('t1.created_at::date AS fecha_cambio'),
                    DB::raw('t1.created_at::time AS hora_cambio'),
                    't1.fecha_hora_inicio',
                    't1.id_tipo_traslado',
                    DB::raw("
                        CASE
                            WHEN t1.id_tipo_traslado = 1 THEN 'A PROCEDIMIENTO QX'
                            WHEN t1.id_tipo_traslado = 2 THEN 'DE SERVICIO'
                            ELSE ''
                        END AS tipo_traslado
                    "),
                    't1.estado_ubicacion',
                    DB::raw("
                        CASE
                            WHEN t1.estado_ubicacion = '1' THEN 'SIN CAMBIO'
                            WHEN t1.estado_ubicacion = '2' THEN 'ROTACIÓN'
                            WHEN t1.estado_ubicacion = '3' THEN 'TRASLADO'
                            WHEN t1.estado_ubicacion = '4' THEN 'ROTACIÓN DESPOSITADO'
                            ELSE ''
                        END AS cambio_cama_tipo_nombre
                    "),
                    't1.id_prestacion',
                    't1.id_evolucion',
                    't1.estado',
                    't2.id AS cama_origen_id',
                    't2.nombre AS cama_origen_nombre',
                    't3.id AS cuarto_origen_id',
                    't3.nombre AS cuarto_origen_nombre',
                    't4.id AS servicio_origen_id',
                    't4.nombre AS servicio_origen_nombre',
                    't5.id AS camas_destino_id',
                    't5.nombre AS cama_destino_nombre',
                    't6.id AS cuarto_destino_id',
                    't6.nombre AS cuarto_destino_nombre',
                    't7.id AS servicio_destino_id',
                    't7.nombre AS servicio_destino_nombre',
                    't8.dni AS medico_dni',
                    't8.nombre AS medico_nombre',
                    't8.paterno AS medico_paterno',
                    't8.materno AS medico_materno',
                    'u1.codigo as upss_encargado_origen_codigo',
                    'u1.descripcion_upss as upss_encargado_origen_descripcion',
                    't9.nombre as servicio_encargado_origen_nombre',
                    'u2.codigo as upss_encargado_destino_codigo',
                    'u2.descripcion_upss as upss_encargado_destino_descripcion',
                    't10.nombre as servicio_encargado_destino_nombre'
                )
                ->leftJoin('camas AS t2', 't1.id_cama_atencion', '=', 't2.id')
                ->leftJoin('cuartos AS t3', 't2.id_cuarto', '=', 't3.id')
                ->leftJoin('asignacion_servicios AS ags', 'ags.id', '=', 't3.id_asignacion_servicio')
                ->leftJoin('consultorios AS t4', 'ags.id_consultorio', '=', 't4.id')
                ->leftJoin('camas AS t5', 't1.id_cama', '=', 't5.id')
                ->leftJoin('cuartos AS t6', 't5.id_cuarto', '=', 't6.id')
                ->leftJoin('asignacion_servicios AS ags2', 'ags2.id', '=', 't6.id_asignacion_servicio')
                ->leftJoin('consultorios AS t7', 'ags2.id_consultorio', '=', 't7.id')
                ->leftJoin('medicos AS t8', 't1.medico_id', '=', 't8.id')
                ->leftJoin('upsses as u1', 't1.id_upss_encargado_atencion', '=', 'u1.id')
                ->leftJoin('consultorios as t9', 't1.id_servicio_encargado_atencion', '=', 't9.id')
                ->leftJoin('upsses as u2', 't1.id_upss_encargado', '=', 'u2.id')
                ->leftJoin('consultorios as t10', 't1.id_servicio_encargado', '=', 't10.id')
                ->where('t1.id_evolucion', $id_evolucion)
                ->where('t1.estado_ubicacion', '3')
                ->first();

        return $result;
    }

    public function RegistrarCamaAtencionQx($id_hospitalizacion, $id_cama_atencion, $estado_ubicacion, $fecha_hora_inicio, $id_cama, $id_prestacion, $id_evolucion, $created_at)
    {
        $cama = new CambioCama();
        $cama->id_cama_atencion = $id_cama_atencion;
        $cama->estado_ubicacion = $estado_ubicacion;
        $cama->fecha_hora_inicio = $fecha_hora_inicio;
        $cama->id_cama = $id_cama;
        $cama->id_prestacion = $id_prestacion;
        $cama->id_evolucion = $id_evolucion;
        $cama->estado = 1;
        $cama->created_at = $created_at;
        $cama->save();

        $hospitalizacion = \App\Models\Hospitalizacione::find($id_hospitalizacion);
        $hospitalizacion->id_cama_estado = $id_cama;
        $hospitalizacion->save();

        return true;
    }

  
    public function RegistrarCamaAtencionQxAQ($id_hospitalizacion, $id_cama_atencion, $estado_ubicacion, $fecha_hora_inicio, $id_cama, $id_prestacion, $id_evolucion, $created_at, $id_upss_encargado_atencion,
    $id_servicio_encargado_atencion,$id_upss_encargado,$id_servicio_encargado)
    {
        $cama = new CambioCama();
        // Validar cadenas vacías y convertirlas en 0 si es necesario
        $cama->id_cama_atencion = ($id_cama_atencion === '' || $id_cama_atencion === null) ? 0 : $id_cama_atencion;
        $cama->estado_ubicacion = ($estado_ubicacion === '' || $estado_ubicacion === null) ? 0 : $estado_ubicacion;
        $cama->fecha_hora_inicio = $fecha_hora_inicio;
        $cama->id_cama = ($id_cama === '' || $id_cama === null) ? 0 : $id_cama;
        $cama->id_prestacion = $id_prestacion;
        $cama->id_evolucion = $id_evolucion;
        $cama->estado = 1; 
        $cama->created_at = $created_at;
        $cama->id_upss_encargado_atencion = $id_upss_encargado_atencion;
        $cama->id_servicio_encargado_atencion = $id_servicio_encargado_atencion;
        $cama->id_upss_encargado = $id_upss_encargado;
        $cama->id_servicio_encargado = $id_servicio_encargado;
        $cama->save();
    
        // Validar id_hospitalizacion antes de buscar
        if ($id_hospitalizacion && is_numeric($id_hospitalizacion)) {
            $hospitalizacion = \App\Models\Hospitalizacione::find($id_hospitalizacion);
    
            if ($hospitalizacion) {
                $hospitalizacion->id_cama_estado = ($id_cama === '' || $id_cama === null) ? 0 : $id_cama;
                $hospitalizacion->save();             
            }
        return true;
      }
  }
    public function getByExtIdForHospitalizacion($id_ext) {
        $result = DB::table('cambio_camas AS t1')
                ->select(
                    't1.id',
                    DB::raw('t1.fecha_hora_inicio::date AS fecha_cambio'),
                    DB::raw('t1.fecha_hora_inicio::time AS hora_cambio'),
                    't1.estado_ubicacion',
                    DB::raw("
                        CASE
                            WHEN t1.estado_ubicacion IN ('1', '2') THEN 'ROTACIÓN'
                            WHEN t1.estado_ubicacion = '3' THEN 'TRASLACIÓN'
                            WHEN t1.estado_ubicacion = '4' THEN 'ORIGEN'
                            ELSE 'ROTACIÓN'
                        END AS cambio_cama_tipo_nombre
                    "),
                    't1.id_evolucion',
                    't1.estado',
                    't2.id AS cama_origen_id',
                    't2.nombre AS cama_origen_nombre',
                    't3.id AS cuarto_origen_id',
                    't3.nombre AS cuarto_origen_nombre',
                    't4.id AS servicio_origen_id',
                    't4.nombre AS servicio_origen_nombre',
                    't5.id AS camas_destino_id',
                    't5.nombre AS cama_destino_nombre',
                    't6.id AS cuarto_destino_id',
                    't6.nombre AS cuarto_destino_nombre',
                    't7.id AS servicio_destino_id',
                    't7.nombre AS servicio_destino_nombre',
                    't8.dni AS medico_dni',
                    't8.nombre AS medico_nombre',
                    't8.paterno AS medico_paterno',
                    't8.materno AS medico_materno'
                )
                ->join('camas AS t2', 't1.id_cama_atencion', '=', 't2.id')
                ->join('cuartos AS t3', 't2.id_cuarto', '=', 't3.id')
                ->join('asignacion_servicios AS ags', 'ags.id', '=', 't3.id_asignacion_servicio')
                ->join('consultorios AS t4', 'ags.id_consultorio', '=', 't4.id')
                ->join('camas AS t5', 't1.id_cama', '=', 't5.id')
                ->join('cuartos AS t6', 't5.id_cuarto', '=', 't6.id')
                ->join('asignacion_servicios AS ags2', 'ags2.id', '=', 't6.id_asignacion_servicio')
                ->join('consultorios AS t7', 'ags2.id_consultorio', '=', 't7.id')
                ->leftJoin('medicos AS t8', 't1.medico_id', '=', 't8.id')
                ->where('t1.id_ext', $id_ext)
                ->where('t1.id_tipo_atencion', 3)
                ->orderBy('t1.created_at', 'desc')
                ->get();

        return $result;
    }

    public function getDataByIdPrestacion($prestacion_id) {
        $result = DB::table('cambio_camas AS cc')
                    ->where('cc.id_prestacion', $prestacion_id)
                    ->where('cc.id_evolucion', 0)
                    ->where('cc.estado', '1')
                    ->select('cc.id', 'cc.id_cama_atencion', 'cc.id_servicio_encargado')
                    ->first();
        return $result;
    }

    public function getDataByIdPrestacionAndIdEvolucion($prestacion_id, $evolucion_id) {
        $result = DB::table('cambio_camas AS cc')
                    ->where('cc.id_prestacion', $prestacion_id)
                    ->where('cc.id_evolucion', $evolucion_id)
                    ->where('cc.estado', '1')
                    ->select('cc.id', 'cc.id_cama_atencion', 'cc.id_servicio_encargado')
                    ->first();
        return $result;
    }
}