<?php 

namespace App\UseCases\CentroQuirurgico;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VerDetalleIntervencionNivel2UseCase
{
  /**
   *
   * @param number $cqxIntervencionId
   * @throws Exception
   */
  public function execute($cqxIntervencionId)
  {
    $user = Auth::user();

    $intervencion = DB::table('cqx_atenciones as ca')
                        ->selectRaw("
                            ci.id as cqx_intervencion_id,
                            ca.id as cqx_atencion_id,
                            p.id as prestacion_id,
                            ta.nombre as tipo_atencion,
                            c.id as consultorio_id,
                            c.nombre as consultorio,
                            to_char(ca.created_at, 'dd-mm-yyyy') as fecha_atencion_intervencion,
                            to_char(ca.created_at, 'HH24:MI:SS') as hora_atencion_intervencion,
                            ca.codigo_siteds as atencion_codigo_siteds,
                            u.id as upss_id,
                            u.codigo as upss_codigo,
                            u.descripcion_upss,
                            e.id as establecimiento_id,
                            e.nombre as establecimiento,
                            a.nro_doc_ident as asegurado_nro_doc_ident,
                            a.paterno as asegurado_paterno,
                            a.materno as asegurado_materno,
                            a.nombre as asegurado_nombre,
                            ah.id as asegurado_historia_id,
                            ah.nro_historia,
                            tb.id as tipo_beneficiario_id,
                            tb.descripcion as tipo_beneficiario,
                            a.fecha_nac as asegurado_fecha_nac,
                            EXTRACT(YEAR FROM age(current_date, a.fecha_nac::date))::int AS asegurado_edad,
                            CASE WHEN a.sexo = 'F' THEN 'Femenino' ELSE 'Masculino' END::varchar AS asegurado_sexo,
                            a.cip as asegurado_cip,
                            a.grado as asegurado_grado,
                            m.id as medico_id,
                            m.nombre as medico_nombre,
                            m.paterno as medico_paterno,
                            m.materno as medico_materno,
                            m.dni as medico_dni,
                            tdi.id as medico_tipo_documento_id,
                            tdi.descripcion as medico_tipo_documento,
                            p2.id as medico_profesion_id,
                            p2.nombre as medico_profesion,
                            m.colegiatura as medico_colegiatura,
                            m.rne as medico_rne,
                            e2.id as medico_especializacion_id,
                            e2.nombre as medico_especializacion,
                            ca.id_clasificacion_cirugia,
                            cta.id as tipo_anestesia_id,
                            cta.descripcion as tipo_anestesia,
                            ca.horas_programadas,
                            to_char(ca.hora_inicio_intervencion, 'HH24:MI:SS') as hora_inicio_intervencion,
                            to_char(ca.hora_fin_intervencion, 'HH24:MI:SS') as hora_fin_intervencion,
                            ca.horas_efectivas,
                            to_char(ca.hora_inicio_acto_qx, 'HH24:MI:SS') as hora_inicio_acto_qx,
                            to_char(ca.hora_fin_acto_qx, 'HH24:MI:SS') as hora_fin_acto_qx,
                            ca.horas_acto_qx,
                            ca.tiene_complicacion_medica,
                            ctc.id as tipo_programacion_id,
                            ci.flag_firma_atencion_qx as cqx_intervenciones_flag_firma_atencion_qx,
                            ci.fecha_firma_atencion_qx as cqx_intervenciones_fecha_firma_atencion_qx,
                            ci.ruta_firma_atencion_qx as cqx_intervenciones_ruta_firma_atencion_qx,
                            ca.fecha_firma as cqx_atenciones_fecha_firma,
                            ca.ruta_firma as cqx_atenciones_ruta_firma,
                            ca.flag_firma as cqx_atenciones_flag_firma,
                            ctc.descripcion as tipo_programacion")
                        ->join('cqx_intervenciones as ci', 'ca.id_cqx_intervencion', '=', 'ci.id')
                        ->join('prestaciones as p', 'ca.id_prestacion', '=', 'p.id')
                        ->join('tipo_atenciones as ta', 'p.id_tipo_atencion', '=', 'ta.id')
                        ->join('asegurados as a', 'ci.id_asegurado', '=', 'a.id')
                        ->join('consultorios as c', 'ca.id_servicio', '=', 'c.id')
                        ->join('upsses as u', DB::raw('p.upss::integer'), '=', 'u.id')
                        ->join('establecimientos as e', 'ci.id_establecimiento', '=', 'e.id')
                        ->join('asegurado_historias as ah', function ($join) {
                            $join->on('ah.id_asegurado', '=', 'a.id')
                                ->on('ah.id_establecimiento', '=', 'e.id');
                        })
                        ->join('tipo_beneficiarios as tb', 'tb.id', '=', 'a.id_tipo_beneficiario')
                        ->join('medicos as m', 'p.id_medico', '=', 'm.id')
                        ->join('tipo_documento_identidad as tdi', 'm.tipo_documento', '=', 'tdi.id')
                        ->join('profesiones as p2', 'm.id_profesion', '=', 'p2.id')
                        ->join('especializaciones as e2', 'm.id_especializacion', '=', 'e2.id')
                        ->join('cqx_tipo_anestesia as cta', 'ca.id_tipo_anestecia', '=', 'cta.id')
                        ->join('cqx_tipo_cirugias as ctc', 'ca.id_tipo_programacion', '=', 'ctc.id')
                        ->where('ca.id_cqx_intervencion', $cqxIntervencionId)
                        ->first();

    $profesionalesResult = DB::table('cqx_profesionales as cp')
                                ->join('medicos as m', 'cp.id_medico', '=', 'm.id')
                                ->join('cqx_tipo_professionales as ctp', 'cp.id_tipo_professional', '=', 'ctp.id')
                                ->join('profesiones as p', 'm.id_profesion', '=', 'p.id')
                                ->where('cp.id_cqx_intervenciones', $cqxIntervencionId)
                                ->select(
                                    'ctp.descripcion as cqx_tipo_professional',
                                    'm.id as medico_id',
                                    'm.nombre as medico_nombre',
                                    'm.paterno as medico_paterno',
                                    'm.materno as medico_materno',
                                    'm.colegiatura',
                                    'cp.flag_personal',
                                    'p.id as profesional_id',
                                    'p.nombre as profesional'
                                )
                                ->get();

    $profesionalesResult = collect($profesionalesResult);
    $grouped = $profesionalesResult->groupBy('flag_personal');
    $profesionales = array(
      'cirujanos' => isset($grouped[0]) ? $grouped[0]->values()->toArray() : array(),
      'anestesiologos' => isset($grouped[1]) ? $grouped[1]->values()->toArray() : array(),
      'enfermeria' => isset($grouped[2]) ? $grouped[2]->values()->toArray() : array(),
    );

    $procedimientos = DB::table('prestacion_procedimientos as pp')
                    ->join('procedimientos as p', 'pp.id_procedimiento', '=', 'p.id')
                    ->join('diagnosticos as d', 'pp.id_diagnostico', '=', 'd.id')
                    ->where('pp.id_prestacion', $intervencion->prestacion_id)
                    ->select([
                        'pp.id as prestacion_procedimiento_id',
                        'p.id as procedimiento_id',
                        'p.codigo as procedimiento_codigo',
                        'p.descripcion as procedimiento_descripcion',
                        'pp.cantidad',
                        'pp.tiempo',
                        'pp.id_diagnostico',
                        'd.codigo as diagnostico_codigo',
                        'd.nombre as diagnostico'
                    ])
                    ->get();
          
    $diagnosticos = DB::table('receta_diagnosticos as rd')
                          ->join('tipo_diagnosticos as td', 'rd.id_tipo_diagnostico', '=', 'td.id')
                          ->join('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
                          ->where('rd.id_intervencion', $cqxIntervencionId)
                          ->orderBy('rd.id', 'desc')
                          ->select([
                              'rd.id as receta_diagnostico_id',
                              'td.id as tipo_diagnostico_id',
                              'd.id as diagnostico_id',
                              'd.codigo as diagnostico_codigo',
                              'd.nombre as diagnostico_nombre',
                              'td.descripcion as tipo_diagnostico_nombre',
                          ])
                          ->get();
    return compact("intervencion", "profesionales", "procedimientos", "diagnosticos", "user");
  }
}