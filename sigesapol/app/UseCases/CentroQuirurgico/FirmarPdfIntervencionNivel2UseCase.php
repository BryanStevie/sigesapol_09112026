<?php 

namespace App\UseCases\CentroQuirurgico;

use Archive7z\Archive7z;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade as PDF;

class FirmarPdfIntervencionNivel2UseCase
{
  /**
   *
   * @param number $cqxIntervencionId
   * @return array
   * @throws Exception
   */
  public function execute($intervencion_id)
  {
    try {
      $path = public_path('tmp/csv');
      $path_zip = public_path('tmp/zip');
      if (!is_dir($path)) {
        mkdir($path);
      }
      if (!is_dir($path_zip)) {
        mkdir($path_zip);
        mkdir(public_path('tmp/zip/firmados'));
        mkdir(public_path('tmp/zip/sin_firma'));
      }
      $csv = "";
      if (Auth::check()):
        $csv .= Auth::user()->id;
      endif;
      $csv .= "_" . date("YmdHis");
      Excel::create($csv, function ($excel) use ($intervencion_id) {
          $excel->sheet('GENERAL', function ($sheet) use ($intervencion_id) {
              $variable = [];
              $this->generarPdf($intervencion_id);
              array_push($variable, array(url() . '/tmp/pdf/sin_firma/intervencion_quirurgica_' . $intervencion_id . '.pdf', url() . '/upload_firma/?name=cf_intervencion_quirurgica_' . $intervencion_id . '.pdf&id_tipo=intervencion_quirurgica&id_resol=' . $intervencion_id, 'cf_intervencion_quirurgica_' . $intervencion_id . '.pdf', '0', '45', '350', '70', 'Fecha de Firma: <DATE> Firmante: <SIGNER>', url() . '/images/escudo.jpg', '1', '7'));
              $sheet->fromArray($variable, null, '', false, false);
          });
      })->store('csv', public_path('tmp/csv'));
      $obj = new Archive7z(public_path('tmp/zip/sin_firma/' . $csv . '.7z'));
      $obj->addEntry(public_path('/tmp/pdf/sin_firma/intervencion_quirurgica_' . $intervencion_id . '.pdf'));
      $resultado["csv"] = $csv;
      $resultado["sw"] = true;
      return $resultado;
    } catch (Exception $e) {
      $mensaje = $e->getMessage() ?: 'Error al generar los documentos para firma.';
      Log::error("[FirmarPdfIntervencionNivel2UseCase] Error", [
        'id_intervencion' => $intervencion_id,
        'user_id' => Auth::id(),
        'mensaje' => $mensaje,
        'file' => $e->getFile(),
        'line' => $e->getLine()
      ]);
      throw new Exception($mensaje);
    }
  }

  function generarPdf($cqxIntervencionId)
  {
    try {
      $path = public_path('tmp/pdf');
      if (!is_dir($path)) {
        mkdir($path);
      }
      $path = public_path('tmp/pdf/sin_firma/');
      if (!is_dir($path)) {
        mkdir($path);
      }
      $usuario = Auth::user();

      $intervencion = DB::table('cqx_intervenciones as ci')
                          ->selectRaw("
                              ci.id::bigint as cqx_intervencio_id,
                              e.id as establecimiento_id,
                              e.codigo as establecimiento_codigo,
                              e.nombre as establecimiento_nombre,
                              ta.id::int as tipo_atencion_id,
                              ta.nombre as tipo_atencion,
                              ci.fecha_intervencion,
                              ci.fecha_solicitud,
                              a.nro_doc_ident as asegurado_nro_doc_ident,
                              tdi.id::int as tipo_documento_identidad_id,
                              tdi.descripcion as tipo_documento_identidad,
                              a.nombre as asegurado_nombre,
                              a.paterno as asegurado_paterno,
                              a.materno as asegurado_materno,
                              a.grado as asegurado_grado,
                              a.sexo as asegurado_grado_abrev,
                              CASE 
                                  WHEN a.sexo = 'F' THEN 'Femenino'
                                  ELSE 'Masculino'
                              END::varchar AS asegurado_sexo,
                              c.id::int as servicio_id,
                              c.nombre as servicio,
                              ci.flag::int as flag_intervencion,
                              ci.id_establecimiento::int,
                              h.fecha_ingreso::date as fecha_admision,
                              EXTRACT(YEAR FROM age(current_date, a.fecha_nac::date))::int AS edad,
                              EXTRACT(YEAR  FROM age(current_date, a.fecha_nac::date))::int AS edad_anios,
                              EXTRACT(MONTH FROM age(current_date, a.fecha_nac::date))::int AS edad_meses,
                              EXTRACT(DAY   FROM age(current_date, a.fecha_nac::date))::int AS edad_dias,
                              ci.is_suspended,
                              ci.suspended_us,
                              ci.suspended_at,
                              ci.suspended_motivo,
                              ah.id as asegurado_historia_id,
                              ah.nro_historia as asegurado_historia_nro_historia,
                              ca.id as cqx_atencion_id,
                              cta.id as tipo_anestesia_id,
                              cta.descripcion as tipo_anestesia,
                              to_char(ca.hora_inicio_intervencion, 'HH24:MI:SS') as hora_inicio_intervencion,
                              to_char(ca.hora_fin_intervencion,   'HH24:MI:SS') as hora_fin_intervencion,
                              ca.horas_programadas,
                              p.id as prestacion_id,
                              h.id_diagnostico_inicial as hospitalizacion_id_dx_1,
                              h.id_dx_2 as hospitalizacion_id_dx_2,
                              h.id_dx_3 as hospitalizacion_id_dx_3
                          ")
                          ->join('hospitalizaciones as h', 'ci.id_hospitalizacion', '=', 'h.id')
                          ->join('establecimientos as e', 'ci.id_establecimiento', '=', 'e.id')
                          ->join('tipo_atenciones as ta', 'ci.id_tipo_atencion_origen', '=', 'ta.id')
                          ->join('asegurados as a', 'ci.id_asegurado', '=', 'a.id')
                          ->join('asegurado_historias as ah', function ($join) { $join->on('ah.id_asegurado', '=', 'a.id') ->on('ah.id_establecimiento', '=', 'ci.id_establecimiento');})
                          ->join('tipo_documento_identidad as tdi', 'a.id_tipo_doc_ident', '=', 'tdi.id')
                          ->join('cqx_atenciones as ca', 'ca.id_cqx_intervencion', '=', 'ci.id')
                          ->join('prestaciones as p', 'ca.id_prestacion', '=', 'p.id')
                          ->join('cqx_tipo_anestesia as cta', 'ca.id_tipo_anestecia', '=', 'cta.id')
                          ->join('consultorios as c', 'ci.id_consultorio', '=', 'c.id')
                          ->where('ci.id', $cqxIntervencionId)
                          ->first();

      $procedimientos = DB::table('prestacion_procedimientos as pp')
                          ->join('procedimientos as p', 'pp.id_procedimiento', '=', 'p.id')
                          ->where('pp.id_prestacion', $intervencion->prestacion_id)
                          ->select([
                              'pp.id as prestacion_procedimiento_id',
                              'p.id as procedimiento_id',
                              'p.codigo as procedimiento_codigo',
                              'p.descripcion as procedimiento_descripcion',
                              'pp.cantidad',
                              'pp.tiempo',
                          ])
                          ->get();

      $profesionales = DB::table('cqx_profesionales as cp')
                            ->join('medicos as m', 'cp.id_medico', '=', 'm.id')
                            ->join('cqx_tipo_professionales as ctp', 'cp.id_tipo_professional', '=', 'ctp.id')
                            ->where('cp.id_cqx_intervenciones', $cqxIntervencionId)
                            ->select([
                                'ctp.descripcion as cqx_tipo_professional',
                                'm.id as medico_id',
                                'm.nombre as medico_nombre',
                                'm.paterno as medico_paterno',
                                'm.materno as medico_materno',
                            ])
                            ->get();
          
      $arrayDiagnosticosIdsPost = [$intervencion->hospitalizacion_id_dx_1, $intervencion->hospitalizacion_id_dx_2, $intervencion->hospitalizacion_id_dx_3];
      $pre_diagnosticos = DB::table('diagnosticos as d')->whereIn('d.id', $arrayDiagnosticosIdsPost)->get();

      $post_diagnosticos = DB::table('receta_diagnosticos as rd')
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
      
      
      $is_firma = true;
      $pdf = PDF::loadView('frontend.centro_quirurgico.nivel_2.pdf_atencion_nivel_2', compact("intervencion", "procedimientos", "profesionales", "pre_diagnosticos", "post_diagnosticos", "is_firma"));
      $pdf->getDomPDF()->set_option("enable_php", true);
      $pdf->setPaper('A4', 'portable');
      file_put_contents($path . "intervencion_quirurgica_" . $cqxIntervencionId . ".pdf", $pdf->output());
    } catch (Exception $e) {
      throw new Exception("Error al generar PDF: " . $e->getMessage());
    }
  }
}