<?php 

namespace App\UseCases\CentroQuirurgico;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ObtenerIntervencionByIdUseCase
{
  /**
   *
   * @param number $cqxIntervencionId
   * @return array
   * @throws Exception
   */
  public function execute($cqxIntervencionId)
  {
    try {
      $intervencion = DB::table('cqx_intervenciones as ci')
        ->select([
            'ci.id as id_cqx_intervencion',
            'a.id as id_asegurado',
            'a.nro_doc_ident as asegurado_nro_doc_ident',
            'a.nombre as asegurado_nombre',
            'a.paterno as asegurado_paterno',
            'a.materno as asegurado_materno',
            'a.fecha_nac as asegurado_fecha_nac',
            'ah.nro_historia as asegurado_nro_historia',
            'tb.id as id_tipo_beneficiario',
            'tb.descripcion as tipo_beneficiario',
            'at.id as id_asegurado_titular',
            'at.nombre as asegurado_titular_nombre',
            'at.paterno as asegurado_titular_paterno',
            'at.materno as asegurado_titular_materno',
            'at.nro_doc_ident as asegurado_titular_nro_doc_ident',
            'at.cip as asegurado_titular_cip',
            'at.grado as asegurado_titular_grado',
            'at.discapacidad as asegurado_titular_discapacidad',
            'ta.id as id_tipo_atencion',
            'ta.nombre as tipo_atencion_nombre',
            'h.id as id_hospitalizacion',
            'h.fecha_ingreso as hospitalizacion_fecha_ingreso',
            'c2.id as id_consultorio_origen',
            'c2.nombre as consultorio_origen',
            'm.id as id_medico',
            'm.nombre as medico_nombre',
            'm.paterno as medico_paterno',
            'm.materno as medico_materno',
            'm.dni as medico_dni',
            'p.id as id_profesion',
            'p.nombre as profesion_nombre',
            'm.colegiatura as medico_colegiatura',
            'm.rne as medico_rne',
            DB::raw('UPPER(h.cod_siteds_susalud) as hospitalizacion_cod_siteds_susalud'),
            DB::raw("CASE WHEN a.sexo = 'M' THEN 'MASCULINO' ELSE 'FEMENINO' END AS asegurado_sexo"),
            DB::raw("EXTRACT(YEAR FROM age(to_date(a.fecha_nac, 'DD/MM/YYYY'))) AS asegurado_edad"),
            'e.nivel as establecimiento_nivel',
            'e.id as id_establecimiento',
            DB::raw("CASE WHEN a.sexo = 'M' THEN '1' ELSE '2' END AS asegurado_sexo_abrev"),
            'h.id_diagnostico_inicial as hospitalizacion_id_dx_1',
            'h.id_dx_2 as hospitalizacion_id_dx_2',
            'h.id_dx_3 as hospitalizacion_id_dx_3',
            'm2.id as id_medico_responsable_cqx',
            'm2.dni as medico_responsable_cqx_dni',
            'm2.nombre as medico_responsable_cqx_nombre',
            'm2.paterno as medico_responsable_cqx_paterno',
            'm2.materno as medico_responsable_cqx_materno',
            'p2.id as medico_responsable_cqx_id_profesion',
            'p2.nombre as medico_responsable_cqx_profesion',
            'm2.colegiatura as medico_responsable_cqx_colegiatura',
            'm2.rne as medico_responsable_cqx_rne'
        ])
        ->join('hospitalizaciones as h', 'ci.id_hospitalizacion', '=', 'h.id')
        ->join('asegurados as a', 'ci.id_asegurado', '=', 'a.id')
        ->join('establecimientos as e', 'ci.id_establecimiento', '=', 'e.id')
        ->join('consultorios as c', 'ci.id_consultorio', '=', 'c.id')
        ->leftJoin('tipo_beneficiarios as tb', 'a.id_tipo_beneficiario', '=', 'tb.id')
        ->leftJoin('tipo_documento_identidad as tdi', 'a.id_tipo_doc_ident', '=', 'tdi.id')
        ->leftJoin('asegurado_historias as ah', function ($join) { $join->on('ah.id_asegurado', '=', 'a.id') ->on('ah.id_establecimiento', '=', 'e.id');})
        ->leftJoin('asegurados as at', 'a.id_asegurado_titular', '=', 'at.id')
        ->leftJoin('tipo_atenciones as ta', 'ci.id_tipo_atencion_origen', '=', 'ta.id')
        ->leftJoin('consultorios as c2', 'h.id_consultorio', '=', 'c2.id')
        ->leftJoin('medicos as m', 'h.id_medico', '=', 'm.id')
        ->leftJoin('profesiones as p', 'm.id_profesion', '=', 'p.id')
        ->leftJoin('medicos as m2', 'ci.validated_us', '=', 'm2.id')
        ->leftJoin('profesiones as p2', 'm2.id_profesion', '=', 'p2.id')
        ->where('ci.id', $cqxIntervencionId)
        ->first();
      
      $arrayDiagnosticosIdsPost = [$intervencion->hospitalizacion_id_dx_1, $intervencion->hospitalizacion_id_dx_2, $intervencion->hospitalizacion_id_dx_3];

      $diagnosticos = DB::table('diagnosticos as d')->whereIn('d.id', $arrayDiagnosticosIdsPost)->get();
      
      $intervencion->pre_diagnosticos = isset($diagnosticos) ? $diagnosticos : [];

      Log::info(['intervencion' => $intervencion]);
      // $diagnosticosPost = 
        
      return [
        'success' => true,
        'data' => $intervencion
      ];
    } catch (\Exception $e) {
      Log::error('[ObtenerIntervencionByIdUseCase]', [
          'cqx_intervencion_id' => $cqxIntervencionId,
          'user_id' => Auth::user()->id,
          'mensaje' => $e->getMessage(),
          'file' => $e->getFile(),
          'line' => $e->getLine(),
      ]);
      $mensaje = !empty($e->getMessage()) ? $e->getMessage() : 'Error al obtener la intervención quirúrgica por ID.';
      throw new Exception($mensaje);
    }
  }
}