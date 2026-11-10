<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class CqxIntervencion extends Model
{
    protected $table = "cqx_intervenciones";
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'id_prestacion',
        'id_asegurado',
        'fecha_solicitud',
        'id_establecimiento',
        'id_consultorio',
        'flag',
        'id_estado_reg',
        'created_us',
        'created_at',
        'updated_us',
        'updated_at',
        'fecha_probable',
        'id_prioridad_programacion',
        'is_requiere_internamiento_preqx',
        'id_tiempo_internamiento_preqx',
        'is_hospitalizacion_postqx',
        'id_tiempo_hospitalizacion_postqx',
        'id_lugar_operacion',
        'id_medico_reg_programacion',
        'is_validated',
        'is_suspended',
        'suspended_us',
        'suspended_at',
        'suspended_motivo',
        'id_hospitalizacion',
        'fecha_inicio_intervencion',
        'fecha_fin_intervencion',
        'id_sala_intervencion',
        'id_tipo_anestesia',
        'validated_us',
        'validated_at',
        'hora_inicio_prog_qx',
        'hora_fin_prog_qx',
        'id_cama_alta',
        'id_medico_alta',
        'fecha_alta',
        'flag_firma_atencion_qx',
        'fecha_firma_atencion_qx',
        'ruta_firma_atencion_qx',
        'flag_atencion_qx',
        'emergencia_tipo_cirugia',
        'emergencia_duracion_cirugia',
        'fecha_firma_reporte_operatorio',
        'ruta_firma_reporte_operatorio',
        'flag_firma_reporte_operatorio',
        'origen_registro', // 1: desde CQ, 2: Desde Historial de Atenciones
        'is_sugerencia_medica',
        'sugerencia_medica',
        'id_tipo_atencion_origen',
        'id_clasificacion_cirugia',
        'id_interconsulta',
        'es_traslado_interconsultas_acqx',
        'pendiente_alta_emergencia',
        'id_tipo_egreso',
        'es_desde_evolucion_programacion'
    ];

    /*public function RegistrarCitaAIntervencionQuirurgica($id_intervencion_quirurgica, $id_cita)
    {
        $intervencion_quirurgica = IntervencionQuirurgica::find($id_intervencion_quirurgica);
        $intervencion_quirurgica->id_cita2 = $id_cita;
        $intervencion_quirurgica->save();
    }*/

    //Intervencion Quirurgica
    public function ValidarTieneSolicitudIntervencionQuirurgica($tipo_doc_asegurado, $numero_doc_asegurado, $id_establecimiento, $id_servicio)
    {
        $asegurado = DB::table('asegurados')
            ->where('tipo_doc_ident', $tipo_doc_asegurado)
            ->where('nro_doc_ident', $numero_doc_asegurado)
            ->where('id_estado_reg', 1)
            ->first();

        $intervencion_quirurgica = DB::table('cqx_intervenciones as iq')
            ->where('iq.id_asegurado', $asegurado->id)
            ->where('iq.id_establecimiento', $id_establecimiento)
            ->where('iq.id_consultorio', $id_servicio)
            ->where('iq.flag', 1)
            ->select('iq.id', 'iq.id_prestacion', DB::raw("TO_CHAR(iq.fecha_solicitud, 'DD-MM-YYYY') as fecha_solicitud"))
            ->first();
        return $intervencion_quirurgica;
    }

    public function RegistrarProgramacionQuirurgica(
        $id_intervencion_quirurgica, 
        $id_medico_reg_programacion, 
        $id_prioridad_programacion, 
        $is_requiere_internamiento_preqx, 
        $is_hospitalizacion_postqx, 
        $id_lugar_operacion, 
        $fecha_probable, 
        $id_tiempo_internamiento_preqx, 
        $id_tiempo_hospitalizacion_postqx, 
        $id_cqx_cita, 
        $fecha_atencion,
        $hora_fin_prog_qx = null, $hora_inicio_prog_qx = null, $emergencia_duracion_cirugia = null, $id_tipo_cirugia
    )
    {
        if (isset($id_cqx_cita) && intval($id_cqx_cita) > 0) {
            $cqx_cita = \App\Models\CqxCita::find($id_cqx_cita);
            $cqx_cita->fecha_atencion = $fecha_atencion;
            $cqx_cita->fecha_probable = $fecha_probable;
            $cqx_cita->is_programacion_quirurgica = true;
            $cqx_cita->id_medico_reg_programacion = $id_medico_reg_programacion;
            $cqx_cita->id_prioridad_programacion = $id_prioridad_programacion;
            $cqx_cita->is_requiere_internamiento_preqx = $is_requiere_internamiento_preqx;
            $cqx_cita->is_hospitalizacion_postqx = $is_hospitalizacion_postqx;
            $cqx_cita->id_lugar_operacion = $id_lugar_operacion;
            if ($is_requiere_internamiento_preqx)
                $cqx_cita->id_tiempo_internamiento_preqx = $id_tiempo_internamiento_preqx;

            if ($is_hospitalizacion_postqx)
                $cqx_cita->id_tiempo_hospitalizacion_postqx = $id_tiempo_hospitalizacion_postqx;

            $cqx_cita->save();
        }

        $intervencion = CqxIntervencion::find($id_intervencion_quirurgica);
        $intervencion->id_medico_reg_programacion = $id_medico_reg_programacion;
        $intervencion->id_prioridad_programacion = $id_prioridad_programacion;
        $intervencion->is_requiere_internamiento_preqx = $is_requiere_internamiento_preqx;
        $intervencion->is_hospitalizacion_postqx = $is_hospitalizacion_postqx;
        $intervencion->id_lugar_operacion = $id_lugar_operacion;
        $intervencion->fecha_probable = $fecha_probable;
        $intervencion->hora_fin_prog_qx = $hora_fin_prog_qx;
        $intervencion->hora_inicio_prog_qx = $hora_inicio_prog_qx;
        $intervencion->emergencia_duracion_cirugia = $emergencia_duracion_cirugia;
        $intervencion->flag = 2;
        $intervencion->emergencia_tipo_cirugia = $id_tipo_cirugia;
        if ($is_requiere_internamiento_preqx)
            $intervencion->id_tiempo_internamiento_preqx = $id_tiempo_internamiento_preqx;

        if ($is_hospitalizacion_postqx)
            $intervencion->id_tiempo_hospitalizacion_postqx = $id_tiempo_hospitalizacion_postqx;

        return $intervencion->save();
    }

    public function RegistrarFechaProbable($id_intervencion_quirurgica, $id_cqx_cita, $fecha_probable, $fecha_atencion)
    {
        $cqx_cita = \App\Models\CqxCita::find($id_cqx_cita);
        $cqx_cita->fecha_atencion = $fecha_atencion;
        $cqx_cita->fecha_probable = $fecha_probable;
        $cqx_cita->is_programacion_quirurgica = false;
        $cqx_cita->save();

        $intervencion = CqxIntervencion::find($id_intervencion_quirurgica);
        $intervencion->fecha_probable = $fecha_probable;
        return $intervencion->save();
    }

    public function GetById($id)
    {
        //$intervencion = CqxIntervencion::find($id);
        $intervencion = DB::table('cqx_intervenciones as i')
            ->leftJoin('prestaciones as pr', 'i.id_prestacion', '=', 'pr.id')
            ->leftJoin('cqx_citas as citas', function ($join) {
                $join->on('i.id', '=', 'citas.id_cqx_intervenciones')
                    ->where('citas.is_programacion_quirurgica','=', true);
            })
            ->leftJoin('cqx_tipo_cirugias as tc', 'i.emergencia_tipo_cirugia', '=', 'tc.id')
            ->leftJoin('asegurados as a', 'i.id_asegurado', '=', 'a.id')
            ->leftJoin('cqx_dias as cd', 'i.id_prioridad_programacion', '=', 'cd.id')
            ->leftJoin('cqx_dias as cd2', 'i.id_tiempo_internamiento_preqx', '=', 'cd2.id')
            ->leftJoin('cqx_dias as cd3', 'i.id_tiempo_hospitalizacion_postqx', '=', 'cd3.id')
            ->leftJoin('cq_salas as cs', 'i.id_sala_intervencion', '=', 'cs.id')
            ->leftJoin('cqx_tipo_anestesia as cta', 'i.id_tipo_anestesia', '=', 'cta.id')
            ->select('citas.id_lugar_operacion','tc.descripcion as tipo_cirugia','a.sexo','i.*', 'cd.descripcion as prioridad_programacion','cd2.descripcion as tiempo_internamiento_preqx', 'cd3.descripcion as tiempo_hospitalizacion_postqx','cs.nombre as sala_intervencion', 'cta.descripcion as tipo_anestesia', 'pr.id_tipo_atencion')
            ->where('i.id', '=', $id)
            ->first();
        return $intervencion;
    }

    public function UpdateFlagAtencionQX($id)
    {
       $updateFlag = DB::table('cqx_intervenciones')
        ->where('id', $id)
        ->update(['flag_atencion_qx' => 1]);
        return $updateFlag;
    }

    public function ValidarIntervencionQuirurgica($id)
    {
        $intervencion = CqxIntervencion::find($id);
        $intervencion->is_validated = true;
        $intervencion->flag = 3;
        $intervencion->save();
        return $intervencion;
    }

    public function Suspender($id, $id_usuario, $fecha, $motivo)
    {
        $intervencion = CqxIntervencion::find($id);
        $intervencion->is_suspended = true;
        $intervencion->suspended_us = $id_usuario;
        $intervencion->suspended_at = $fecha;
        $intervencion->suspended_motivo = $motivo;
        $intervencion->flag = 1;//al suspender lo volvemos al estado 1 pendiente
        $intervencion->save();
        return $intervencion;
    }
    public function SuspenderSegunRegla($id_intervencion, $id_establecimiento) 
    {
        return DB::select("SELECT * FROM sp_validar_suspension_cqx_segun_regla(?, ?)", [$id_intervencion, $id_establecimiento]); 
    }

    public function RegistrarIdHospitalizacion($id_atencion, $id_hospitalizacion)
    {
        $intervencion = CqxIntervencion::find($id_atencion);
        $intervencion->id_hospitalizacion = $id_hospitalizacion;
        return $intervencion->save();
    }
    public function RegistrarIdHospitalizacion2($id_atencion,$tipo_atencion,$id_hospitalizacion)
    {
        $id_atencion = (int) $id_atencion;
        $tipo_atencion = (int) $tipo_atencion;
        $id_hospitalizacion = (int) $id_hospitalizacion;

        if ($id_atencion > 0 && $tipo_atencion > 0 && $id_hospitalizacion > 0) {
            DB::update("
                UPDATE cqx_intervenciones
                SET id_hospitalizacion = ?
                FROM prestaciones
                WHERE prestaciones.id = cqx_intervenciones.id_prestacion
                AND prestaciones.id_ext = ?
                AND prestaciones.id_tipo_atencion = ?
            ", [$id_hospitalizacion, $id_atencion, $tipo_atencion]);
        } else {
            \Log::error("Error en RegistrarIdHospitalizacion2: parámetros inválidos", [
                'id_atencion' => $id_atencion,
                'tipo_atencion' => $tipo_atencion,
                'id_hospitalizacion' => $id_hospitalizacion
            ]);
        }
    }

    public function ValidarProgramacionQuirurgica($id_intervencion_quirurgica, $id_prioridad_programacion, $is_requiere_internamiento_preqx, $is_hospitalizacion_postqx,$id_sala_intervencion, $id_tipo_anestesia, $id_medico_validacion, $fecha_validacion, $id_tiempo_internamiento_preqx, $id_tiempo_hospitalizacion_postqx, $id_lugar_operacion,$is_prioridad_programacion_semanas)
    {
        $intervencion = CqxIntervencion::find($id_intervencion_quirurgica);
        $intervencion->id_lugar_operacion =!empty( $id_lugar_operacion)? $id_lugar_operacion:null;
        $intervencion->is_requiere_internamiento_preqx = !empty($is_requiere_internamiento_preqx) && $is_requiere_internamiento_preqx == "1" ? true : false;
        $intervencion->is_hospitalizacion_postqx = !empty($is_hospitalizacion_postqx) && $is_hospitalizacion_postqx == "1" ? true : false;
        $intervencion->is_requiere_programacion_semanas = !empty($is_prioridad_programacion_semanas) && $is_prioridad_programacion_semanas == "1" ? true : false;
        // $intervencion->fecha_inicio_intervencion = $fecha_inicio_intervencion;

        // $intervencion->fecha_fin_intervencion = $fecha_fin_intervencion;
        $intervencion->id_sala_intervencion = $id_sala_intervencion;
        $intervencion->id_tipo_anestesia = $id_tipo_anestesia;
        $intervencion->validated_us = $id_medico_validacion;
        $intervencion->validated_at = $fecha_validacion;

        if ($is_requiere_internamiento_preqx)
            $intervencion->id_tiempo_internamiento_preqx = $id_tiempo_internamiento_preqx;

        if ($is_hospitalizacion_postqx)
            $intervencion->id_tiempo_hospitalizacion_postqx = $id_tiempo_hospitalizacion_postqx;
        if ($is_prioridad_programacion_semanas)
            $intervencion->id_prioridad_programacion = $id_prioridad_programacion;

        return $intervencion->save();
    }

    public function RegistrarAltaCq($id_intervencion, $id_cama_alta, $id_medico_alta, $fecha_alta)
    {
        $intervencion = CqxIntervencion::find($id_intervencion);
        $intervencion->flag = 2;
        $intervencion->id_cama_alta = $id_cama_alta;
        $intervencion->id_medico_alta = $id_medico_alta;
        $intervencion->fecha_alta = $fecha_alta;
        return $intervencion->save();
    }

    public function RegistrarAltaCqAQ($id_intervencion, $id_cama_alta, $id_medico_alta, $fecha_alta)
    {
        $intervencion = CqxIntervencion::find($id_intervencion);
        $intervencion->flag = 2;
        $intervencion->id_cama_alta = ($id_cama_alta === '' || $id_cama_alta === null) ? 0 : $id_cama_alta;
        $intervencion->id_medico_alta = $id_medico_alta;
        $intervencion->fecha_alta = $fecha_alta;
        return $intervencion->save();
    }

    public  function RegistrarIntervencionQxDesdeEmergencia($id_emergencia, $id_user, $id_tipo_cirugia, $duracion_cirugia, $fecha_cierre,$hora_inicio_intervencion,$hora_fin_intervencion)
    {
        $cad = "Select sp_insert_riesgo_qx_desde_emergencia(?,?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_emergencia, $id_user, 1, 1, 1, $id_tipo_cirugia, $duracion_cirugia, $fecha_cierre,$hora_inicio_intervencion,$hora_fin_intervencion));
        return $data[0]->sp_insert_riesgo_qx_desde_emergencia;
    }
    //PARA PACIENTE NUEVO EN ADMISION DE HOSPITALIZACION
    public  function RegistrarIntervencionQxDesdeHospitalizacion($id_hospitalizacion, $id_user,$id_asegurado,$id_establecimiento,$id_medico,$id_servicio_encargado_origen)
    {
        $cad = "Select sp_insert_riesgo_qx_desde_hospitalizacion(?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_hospitalizacion, $id_user, $id_asegurado, $id_establecimiento, $id_medico, $id_servicio_encargado_origen));
        return $data[0]->sp_insert_riesgo_qx_desde_hospitalizacion;
    }
    //PARA PACIENTE TRASLADO DE HOSPITALIZACION A CENTRO QUIRURGICO
    public  function RegistrarIntervencionQxDesdeHospitalizacionTraslado($id_prestacion,$id_hospitalizacion,$id_evaluacion,$id_user,$id_tipo_cirugia, $duracion_cirugia, $fecha_cierre, $id_servicio_encargado_origen,$hora_inicio_intervencion,$hora_fin_intervencion)
    {
        $cad = "Select sp_insert_riesgo_qx_desde_hospitalizacion_traslado(?,?,?,?,?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_prestacion,$id_hospitalizacion,$id_evaluacion,$id_user,$id_tipo_cirugia,$duracion_cirugia,$fecha_cierre,$id_servicio_encargado_origen,$hora_inicio_intervencion,$hora_fin_intervencion));
        return $data[0]->sp_insert_riesgo_qx_desde_hospitalizacion_traslado;
    }
    public function getReporteIntervenciones($tipo_atencion = null, $id_establecimiento = null,$fecha_ini = null,$fecha_fin = null,$fecha_ini_intervencion = null,$fecha_fin_intervencion = null,$dni_beneficiario = null,$nombre_beneficiario = null,$id_servicio = null,$id_estado = null,$id_estado_pendiente) {
        $query = "
            SELECT * FROM sp_reporte_intervenciones_v2(
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?
            )
        ";

        $data = DB::select($query, [
            $tipo_atencion,
            $id_establecimiento,
            $fecha_ini,
            $fecha_fin,
            $fecha_ini_intervencion,
            $fecha_fin_intervencion,
            $dni_beneficiario,
            $nombre_beneficiario,
            $id_servicio,
            $id_estado,
            $id_estado_pendiente
        ]);

        return $data;
    }
    public function getReporteDetalladoIntervenciones($fecha_ini = null, $fecha_fin = null,$fecha_ini_intervencion = null,$fecha_fin_intervencion = null,$dni_beneficiario = null,$nombre_beneficiario = null,$id_servicio = null,$id_estado = null,$tipo_atencion = null,$id_establecimiento = null,$id_medico) {
        $query = "
            SELECT * FROM sp_reporte_intervenciones_detalle_v1(
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?
            )
        ";

        $data = DB::select($query, [
            $fecha_ini,
            $fecha_fin,
            $fecha_ini_intervencion,
            $fecha_fin_intervencion,
            $dni_beneficiario,
            $nombre_beneficiario,
            $id_servicio,
            $id_estado,
            $tipo_atencion,
            $id_establecimiento,
            $id_medico
        ]);

        return $data;
    }
}
