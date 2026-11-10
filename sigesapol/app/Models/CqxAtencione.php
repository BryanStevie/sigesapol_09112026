<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
 
class CqxAtencione extends Model
{
    protected $table = 'cqx_atenciones';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
      'id_cqx_intervencion', // NOT NULL,
      'id_hospitalizacion',
      'id_prestacion', // NOT NULL,
      'created_us',
      'created_at',
      'updated_us',
      'updated_at',
      'id_estado_reg', // NOT NULL,
      'allazgo_operatorio',
      'pieza_operatorio',
      'examen_bacteriologico',
      'examen_patologico',
      'registrar_destino',
      'fecha_firma',
      'ruta_firma',
      'flag_firma',
      'codigo_siteds',
      'id_servicio',
      'tiene_hallazgo',
      'tiene_pieza',
      'tiene_examen_bact',
      'tiene_examen_pat',
      'complicacion_medica',
      'tiene_complicacion_medica',
      'codigo_upss',
      'tiene_descripcion_procedimiento',
      'descripcion_procedimiento',
      'id_clasificacion_cirugia',
      'egreso_resumen',
      'egreso_examen_clinico',
      'egreso_examen_auxiliar',
      'egreso_evolucion',
      'egreso_tratamiento',
      'egreso_procedimiento',
      'egreso_complicacion',
      'egreso_medidas_generales',
      'egreso_medicamentos_prescritos',
      'egreso_pronostico',
      'id_tipo_anestecia',
      'horas_programadas',
      'hora_inicio_intervencion',
      'hora_fin_intervencion',
      'hora_inicio_acto_qx',
      'hora_fin_acto_qx',
      'id_tipo_programacion',
      'horas_acto_qx',
      'horas_efectivas',
      'hora_inicio_operatorio',
      'hora_fin_operatorio',
      'hora_duracion_operatorio'
    ];
    public function RegistrarHistorialAtencionQuirurgica(
            $id_intervencion, 
            $id_hospitalizacion, 
            $id_prestacion, 
            $id_user, 
            $created_at,
            $allazgo_operatorio,
            $pieza_operatorio, 
            $examen_patologico,
            $examen_bacteriologico,
            $registrar_destino,
            $codigo_siteds,
            $id_servicio,
            $tiene_hallazgo,
            $tiene_pieza,
            $tiene_examen_bact,
            $tiene_examen_pat,
            $complicacion_medica,
            $tiene_complicacion_medica,
            $descripcion_procedimiento,
            $tiene_descripcion_procedimiento,
            $codigo_upss,
            $id_clasificacion_cirugia,
            $egreso_resumen,
            $egreso_examen_clinico,
            $egreso_examen_auxiliar,
            $egreso_evolucion,
            $egreso_tratamiento,
            $egreso_procedimiento,
            $egreso_complicacion,
            $egreso_medidas_generales,
            $egreso_medicamentos_prescritos,
            $egreso_pronostico,$hora_inicio_operatorio,$hora_fin_operatorio,$hora_duracion_operatorio)
    {
        // Validar y asignar id_hospitalizacion si está vacío o nulo
        $id_hospitalizacion = ($id_hospitalizacion === '' || $id_hospitalizacion === null) ? 0 : $id_hospitalizacion;

        // Crear una nueva instancia del modelo
        $atencion = new CqxAtencione();
        $atencion->id_cqx_intervencion = $id_intervencion;
        $atencion->id_hospitalizacion = $id_hospitalizacion;
        $atencion->id_prestacion = $id_prestacion;
        $atencion->created_us = $id_user;
        $atencion->created_at = $created_at;
        $atencion->id_estado_reg = 1;
        $atencion->allazgo_operatorio = $allazgo_operatorio;
        $atencion->pieza_operatorio = $pieza_operatorio;
        $atencion->examen_patologico = $examen_patologico;
        $atencion->examen_bacteriologico = $examen_bacteriologico;
        $atencion->registrar_destino = $registrar_destino;
        $atencion->codigo_siteds = $codigo_siteds;
        $atencion->id_servicio = $id_servicio;
        $atencion->tiene_hallazgo = $tiene_hallazgo;
        $atencion->tiene_pieza = $tiene_pieza;
        $atencion->tiene_examen_bact = $tiene_examen_bact;
        $atencion->tiene_examen_pat = $tiene_examen_pat;
        $atencion->complicacion_medica = $complicacion_medica;
        $atencion->tiene_complicacion_medica = $tiene_complicacion_medica;
        $atencion->descripcion_procedimiento = $descripcion_procedimiento;
        $atencion->tiene_descripcion_procedimiento = $tiene_descripcion_procedimiento;
        $atencion->codigo_upss = $codigo_upss;
        $atencion->id_clasificacion_cirugia = $id_clasificacion_cirugia;
        $atencion->egreso_resumen = $egreso_resumen;
        $atencion->egreso_examen_clinico = $egreso_examen_clinico;
        $atencion->egreso_examen_auxiliar = $egreso_examen_auxiliar;
        $atencion->egreso_evolucion = $egreso_evolucion;
        $atencion->egreso_tratamiento = $egreso_tratamiento;
        $atencion->egreso_procedimiento = $egreso_procedimiento;
        $atencion->egreso_complicacion = $egreso_complicacion;
        $atencion->egreso_medidas_generales = $egreso_medidas_generales;
        $atencion->egreso_medicamentos_prescritos = $egreso_medicamentos_prescritos;
        $atencion->egreso_pronostico = $egreso_pronostico;
        $atencion->hora_inicio_operatorio = $hora_inicio_operatorio;
        $atencion->hora_fin_operatorio = $hora_fin_operatorio;
        $atencion->hora_duracion_operatorio = $hora_duracion_operatorio;
        $atencion->save();
        return $atencion->id;
    }

    public function GetDatosByIdIntervencion_cqx($id_intervencion) {
        $datos = DB::table('cqx_atenciones')
                ->select('ci.id_prestacion as id_prestacion_inicial','cqx_atenciones.id','cqx_atenciones.id_prestacion','cqx_atenciones.id_cqx_intervencion','complicacion_medica','tiene_complicacion_medica')
                ->join('cqx_intervenciones as ci', 'cqx_atenciones.id_cqx_intervencion', '=', 'ci.id')
                ->where('cqx_atenciones.id_cqx_intervencion', '=', $id_intervencion)
                ->first();
        return $datos;
    }    
}
