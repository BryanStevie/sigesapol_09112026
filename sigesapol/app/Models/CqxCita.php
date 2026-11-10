<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class CqxCita extends Model
{
    // protected $table = 'cqx_citas';
    protected $table = 'cqx_citas';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id_cqx_intervenciones',
        'id_consultorio',
        'id_prestacion',
        'id_cita',
        'flag',
        'id_estado_reg',
        'created_us',
        'created_at',
        'updated_us',
        'updated_at',
        'observacion',
        'tipo',
        'fecha_firma',
        'ruta_firma',
        'flag_firma',
        'fecha_probable',
        'is_programacion_quirurgica',
        'id_prioridad_programacion',
        'is_requiere_internamiento_preqx',
        'id_tiempo_internamiento_preqx',
        'is_hospitalizacion_postqx',
        'id_tiempo_hospitalizacion_postqx',
        'id_lugar_operacion',
        'id_medico_reg_programacion',
        'fecha_atencion',
        'fecha_inicio_intervencion',
        'fecha_fin_intervencion',
        'id_riesgo',
        'id_tipo_cirugia',
        'observacion_asa',
        'es_interconsulta'
    ];

    public  function RegistrarRiesgoQx($id_prestacion, $id_user,$p_interconsulta,$servicios_cqx)
    {
        $cad = "Select sp_insert_riesgo_qx(?,?,?,?,?,?)";
        $data = DB::select($cad, array($id_prestacion, $id_user,$servicios_cqx['rq_cardiologia'], $servicios_cqx['rq_neumologia'],$servicios_cqx['rq_anestesiologia'],$p_interconsulta));
        return $data[0]->sp_insert_riesgo_qx;
    }

    public  function GetRiesgos($id_prestacion)
    {
        $riesgos = null;
        $intervencion = DB::table('cqx_intervenciones')
            ->where('id_prestacion', $id_prestacion)
            ->where('id_estado_reg', 1)
            ->where('flag', 1)
            ->first();
        
        if ($intervencion != null) {
            $riesgos = DB::table('cqx_citas')
                ->select('cqx_citas.*', 'consultorios.nombre as servicio')
                ->join('consultorios', 'cqx_citas.id_consultorio', '=', 'consultorios.id')
                ->where('cqx_citas.id_cqx_intervenciones', $intervencion->id)
                ->where('cqx_citas.id_estado_reg', 1)
                ->where('cqx_citas.tipo', 1)
                ->where('cqx_citas.es_interconsulta', null)
                ->get();
        }
        return $riesgos;
    }

    public  function ActualizarIdCitaDelRiesgoQxSolicitado($id_servicio_especifico, $id_cita, $id_intervencion, $id_registro_cqxcita)
    {
        /*$intervencion_quirurgica = DB::table('cqx_intervenciones')
                        ->where('id_prestacion', $id_prestacion)
                        ->where('flag', 1)
                        ->where('id_estado_reg', 1)
                        ->first();

        $riesgo_qx = CqxCita::where('id_cqx_intervenciones', $intervencion_quirurgica->id)
                        ->where('id_consultorio', $id_servicio)
                        ->where('id_estado_reg', 1)
                        ->first();*/
        $data = CqxCita::where('id_cqx_intervenciones', $id_intervencion)
                        ->where('id', $id_registro_cqxcita)
                        ->where('id_consultorio', $id_servicio_especifico)
                        ->where('flag','<>',5)
                        ->update([
                            'id_cita' => $id_cita,
                            'flag' => 2
                        ]);
        return $data;
    }

    public function GetCitaIntervencionQuirurgica($id_cita)
    {
        $prestacioneIntQx = DB::table('cqx_citas as rq')
            ->join('cqx_intervenciones as iq', 'rq.id_cqx_intervenciones', '=', 'iq.id')
            ->where('rq.id_cita', $id_cita)
            ->where('rq.flag', 2)
            ->where('rq.id_estado_reg', 1)
            ->where('iq.id_estado_reg', 1)
            ->select('rq.*', DB::raw("TO_CHAR(iq.fecha_probable, 'DD-MM-YYYY') as fecha_probable"))
            ->first();
        return $prestacioneIntQx;
    }

    public function RegistrarAtencionRiesgoQx($id_cq_cita, $id_prestacion, $observacion, $id_user = 0, $fecha_registro = null)
    {
        $riesgo_qx = CqxCita::find($id_cq_cita);
        
        if ($observacion == null):
            // Atencion Sin observacion
            $riesgo_qx->id_prestacion = $id_prestacion;
            $riesgo_qx->flag = 3;
        else:
            // Atencion Con observacion
            $riesgo_qx->id_prestacion = $id_prestacion;
            $riesgo_qx->observacion = $observacion;
            $riesgo_qx->flag = 4;

            $cqx_cita = new CqxCita;
            $cqx_cita->id_cqx_intervenciones = $riesgo_qx->id_cqx_intervenciones;
            $cqx_cita->id_consultorio = $riesgo_qx->id_consultorio;
            $cqx_cita->id_prestacion = 0;
            $cqx_cita->id_cita = 0;
            $cqx_cita->tipo = 1;
            $cqx_cita->flag = 1;
            $cqx_cita->id_estado_reg = 1;
            $cqx_cita->created_us = $id_user;
            $cqx_cita->created_at = $fecha_registro;
            $cqx_cita->save();

        endif;
        
        return $riesgo_qx->save();
    }

    public function RegistrarCitaAIntervencionQuirurgica($id_user, $id_intervencion_quirurgica, $id_cita, $id_servicio)
    {
        $cq_cita = new CqxCita();
        $cq_cita->id_cqx_intervenciones = $id_intervencion_quirurgica;
        $cq_cita->id_consultorio = $id_servicio;
        $cq_cita->id_prestacion = 0;
        $cq_cita->id_cita = $id_cita;
        $cq_cita->flag = 2;
        $cq_cita->id_estado_reg = 1;
        $cq_cita->created_us = $id_user;
        $cq_cita->tipo = 2;
        return $cq_cita->save();
    }

    public function GetCitaByIdPrestacion($id_prestacion)
    {
        $cqx_cita = DB::table('cqx_citas')
            ->leftJoin('cqx_dias as cd1', 'cqx_citas.id_prioridad_programacion', '=', 'cd1.id')
            ->leftJoin('cqx_dias as cd2', 'cqx_citas.id_tiempo_internamiento_preqx', '=', 'cd2.id')
            ->leftJoin('cqx_dias as cd3', 'cqx_citas.id_tiempo_hospitalizacion_postqx', '=', 'cd3.id')
            ->leftJoin('cqx_dias as cd4', 'cqx_citas.id_riesgo', '=', 'cd4.id')
            ->leftJoin('cqx_tipo_cirugias', 'cqx_citas.id_tipo_cirugia', '=', 'cqx_tipo_cirugias.id')
            ->where('cqx_citas.id_prestacion', $id_prestacion)
            ->where('cqx_citas.id_estado_reg', 1)
            ->select(
                'cqx_citas.*', // Selecciona todos los campos de cqx_citas
                'cd1.descripcion as prioridad',
                'cd2.descripcion as tiempo_internamiento_preqx',
                'cd3.descripcion as tiempo_hospitalizacion_postqx',
                'cd4.descripcion as numero_riesgo',
                'cqx_tipo_cirugias.descripcion as tipo_cirugia',
                DB::raw(" CASE WHEN cqx_citas.id_lugar_operacion = 1 THEN 'HOSPITALIZADO' 
                            WHEN cqx_citas.id_lugar_operacion = 2 THEN 'CLÍNICA DE DÍA' 
                            ELSE '' END as lugar_operacion
                ")
            )
            ->first();
        return $cqx_cita;
    }

    public function GetRiesgosRegistrados($id_intervencion)
    {
        $riesgos = DB::table('cqx_citas as cc')
            ->select('cc.id', 'cc.id_consultorio', 'c.nombre as servicio', 'cd.descripcion as riesgo', 'cc.fecha_atencion', 'p.plan_trabajo', 'cc.flag', 'cc.observacion')
            ->join('consultorios as c', 'cc.id_consultorio', '=', 'c.id')
            ->join('prestaciones as p', 'cc.id_prestacion', '=', 'p.id')
            ->join('cqx_dias as cd', 'cc.id_riesgo', '=', 'cd.id')
            ->where('cc.id_cqx_intervenciones', $id_intervencion)
            ->where('cc.tipo', 1)
            ->orderBy('cc.fecha_atencion')
            ->get();
        return $riesgos;
    }

    public function RegistrarNumeroRiesgo($id_cq_cita, $id_servicio, $fecha_atencion, $id_riesgo, $id_tipo_cirugia, $observacion_asa)
    {
        $model = CqxCita::find($id_cq_cita);
        $model->fecha_atencion = $fecha_atencion;
        $model->id_riesgo = $id_riesgo;
        if ($id_servicio == 72): // Si no es Anestesiologia
            $model->id_tipo_cirugia = $id_tipo_cirugia;
            $model->observacion_asa = $observacion_asa;
        endif;
        return $model->save();
    }

    public function GetById($id_cqx_cita)
    {
        $data = CqxCita::find($id_cqx_cita);
        return $data;
    }
}
