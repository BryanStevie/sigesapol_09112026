<?php

namespace App\UseCases\CentroQuirurgico;

use App\Exceptions\SigesapolException;
use App\Models\CqxIntervencion;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception;

class GeneraPdfAdmisionHospitalizacionUseCase
{
  /**
   *
   * @param number $cqxIntervencionId
   * @throws Exception
   */
  public function execute($cqxIntervencionId) 
  {
    try {
      $intervencion = CqxIntervencion::find($cqxIntervencionId);
      $is_firma = false;
      if (!isset($intervencion) || !isset($intervencion->id_hospitalizacion) || empty($intervencion->id_hospitalizacion)) {
        throw new SigesapolException("No existe la intervención indicada.", JsonResponse::HTTP_NOT_FOUND);
      }

      $hospitalizacion = DB::table('cqx_intervenciones as ci')
                          ->select([
                            'ci.id as cqx_intervencion_id',
                            'h.id as hospitalizacion_id',
                            'a.id as asegurado_id',
                            'h.cups_pnp',
                            'h.cod_siteds_susalud',
                            'ah.id as asegurado_historia_id',
                            'ah.nro_historia as asegurado_historia_nro',
                            DB::raw("TO_CHAR(h.fecha_ingreso, 'DD-MM-YYYY HH24:MI') AS hospitalizacion_fecha_hora_ingreso"),
                            'a.nro_doc_ident as asegurado_nro_doc_ident',
                            'a.nombre as asegurado_nombre',
                            'a.paterno as asegurado_paterno',
                            'a.materno as asegurado_materno',
                            DB::raw("EXTRACT(YEAR FROM age(current_date, a.fecha_nac::date))::int AS edad"),
                            DB::raw("EXTRACT(YEAR FROM age(current_date, a.fecha_nac::date))::int AS edad_anios"),
                            DB::raw("EXTRACT(MONTH FROM age(current_date, a.fecha_nac::date))::int AS edad_meses"),
                            DB::raw("EXTRACT(DAY FROM age(current_date, a.fecha_nac::date))::int AS edad_dias"),
                            DB::raw("CASE WHEN a.sexo = 'F' THEN 'Femenino' ELSE 'Masculino' END::varchar AS asegurado_sexo"),
                            'a2.nro_doc_ident as asegurado_titular_nro_doc_ident',
                            'a2.nombre as asegurado_titular_nombre',
                            'a2.paterno as asegurado_titular_paterno',
                            'a2.materno as asegurado_titular_materno',
                            'a2.grado as asegurado_titular_grado',
                            'a2.cip as asegurado_titular_cip',
                            'a2.situacion_paciente as asegurado_titular_situacion_paciente',
                            'tp.id as tipo_parentesco_id',
                            'tp.descripcion as asegurado_tipo_parentesco',
                            'tb.id as tipo_beneficiario_id',
                            'tb.descripcion as asegurado_tipo_beneficiario',
                            'a.fecha_nac',
                            'a.sexo',
                            'r.id as raza_id',
                            'r.nombre as asegurado_raza',
                            'ec.id as estado_civil_id',
                            'ec.nombre as asegurado_estado_civil',
                            'a.cip as asegurado_cip',
                            'a.telefono as asegurado_telefono',
                            'a.grado as asegurado_grado',
                            'o.id as id_ocupacion',
                            'o.nombre as asegurado_ocupacion',
                            'a.direccion_nacimiento as asegurado_direccion_nacimiento',
                            'd.id_distrito as asegurados_id_distrito_nacimiento',
                            'p.id_provincia as asegurados_id_provincia_nacimiento',
                            'd2.id_departamento as asegurados_id_departamento_nacimiento',
                            'd.descripcion as asegurados_distrito_nacimiento',
                            'p.descripcion as asegurados_provincia_nacimiento',
                            'd2.descripcion as asegurados_departamento_nacimiento',
                            'r2.id as asegurado_religion_id',
                            'r2.nombre as asegurado_religion',
                            'h.id_ext_origen',
                            'h.origen',
                            'h.id_upss_encargado',
                            'u.descripcion_upss',
                            'c.id as servicio_encargado_id',
                            'c.nombre as servicio_encargado',
                            'm.id as medico_id',
                            'm.nombre as medico_nombre',
                            'm.paterno as medico_paterno',
                            'm.materno as medico_materno',
                            'm.dni as medico_dni',
                            'p2.id as medico_profesion_id',
                            'p2.nombre as medico_profesion',
                            'm.colegiatura',
                            'm.rne',
                            'h.id_diagnostico_inicial as hospitalizacion_id_dx_1',
                            'h.id_dx_2 as hospitalizacion_id_dx_2',
                            'h.id_dx_3 as hospitalizacion_id_dx_3',
                            'e.id as establecimiento_id',
                            'e.codigo as establecimiento_codigo',
                            'e.nombre as establecimiento_nombre',
                            'm2.nombre as hospitalizacion_medico_nombre',
                            'm2.paterno as hospitalizacion_medico_paterno',
                            'm2.materno as hospitalizacion_medico_materno',
                            'm3.id as id_medico_responsable_cqx',
                            'm3.dni as medico_responsable_cqx_dni',
                            'm3.nombre as medico_responsable_cqx_nombre',
                            'm3.paterno as medico_responsable_cqx_paterno',
                            'm3.materno as medico_responsable_cqx_materno',
                            'p3.id as medico_responsable_cqx_id_profesion',
                            'p3.nombre as medico_responsable_cqx_profesion',
                            'm3.colegiatura as medico_responsable_cqx_colegiatura',
                            'm3.rne as medico_responsable_cqx_rne',
                            'c2.id as servicio_origen_id',
                            'c2.nombre as servicio_origen_nombre',
                          ])
                          ->join('hospitalizaciones as h', 'ci.id_hospitalizacion', '=', 'h.id')
                          ->join('asegurados as a', 'h.id_asegurado', '=', 'a.id')
                          ->join('asegurado_historias as ah', function ($join) {$join->on('ci.id_asegurado', '=', 'ah.id_asegurado')->on('ah.id_establecimiento', '=', 'h.id_establecimiento');})
                          ->leftJoin('asegurados as a2', 'a.id_asegurado_titular', '=', 'a2.id')
                          ->leftJoin('tipo_parentescos as tp', 'a.id_tipo_parentesco', '=', 'tp.id')
                          ->leftJoin('tipo_beneficiarios as tb', 'a.id_tipo_beneficiario', '=', 'tb.id')
                          ->leftJoin('razas as r', 'a.id_raza', '=', 'r.id')
                          ->leftJoin('estado_civiles as ec', 'a.id_estado_civil', '=', 'ec.id')
                          ->leftJoin('ocupaciones as o', 'a.id_ocupacion', '=', 'o.id')
                          ->leftJoin('distritos as d', 'a.id_distrito_nacimiento', '=', 'd.id_distrito')
                          ->leftJoin('provincias as p', 'd.id_provincia', '=', 'p.id_provincia')
                          ->leftJoin('departamentos as d2', 'p.id_departamento', '=', 'd2.id_departamento')
                          ->leftJoin('religiones as r2', 'a.id_religion', '=', 'r2.id')
                          ->join('upsses as u', 'h.id_upss_encargado', '=', 'u.id')
                          ->join('consultorios as c', 'h.id_servicio_encargado', '=', 'c.id')
                          ->join('users as u2', 'ci.created_us', '=', 'u2.id')
                          ->join('medicos as m', 'u2.dni', '=', 'm.dni')
                          ->join('profesiones as p2', 'm.id_profesion', '=', 'p2.id')
                          ->join('establecimientos as e', 'ci.id_establecimiento', '=', 'e.id')
                          ->leftJoin('users as u3', 'h.id_user', '=', 'u3.id')
                          ->leftJoin('medicos as m2', 'u3.dni', '=', 'm2.dni')
                          ->join('medicos as m3', 'ci.validated_us', '=', 'm3.id')
                          ->join('profesiones as p3', 'm3.id_profesion', '=', 'p3.id')
                          ->join('consultorios as c2', 'h.id_servicio_origen', '=', 'c2.id')
                          ->where('h.id', $intervencion->id_hospitalizacion)
                          ->first();

      $arrayDiagnosticosIdsPost = [$hospitalizacion->hospitalizacion_id_dx_1, $hospitalizacion->hospitalizacion_id_dx_2, $hospitalizacion->hospitalizacion_id_dx_3];
      $diagnosticos = DB::table('diagnosticos as d')->whereIn('d.id', $arrayDiagnosticosIdsPost)->get();

      $user = Auth::user();

      $dateCurrent = Carbon::now()->format('d-m-Y');
      $hourCurrent  = Carbon::now()->format('H:i');
      
      $pdf = PDF::loadView('frontend.centro_quirurgico.nivel_2.pdf_hoja_hospitalizacion_nivel_2', compact('is_firma', 'intervencion', 'hospitalizacion', 'diagnosticos', 'user', 'dateCurrent', 'hourCurrent'));
      $pdf->getDomPDF()->set_option("enable_php", true);
      $pdf->setPaper('A4', 'portable');
      return $pdf->stream('papeleta_egreso.pdf');
    } catch (SigesapolException $e) {
        DB::rollBack();
        Log::warning("RegistrarAltaIntervencionNivel2 - WARNING", [
            "error" => $e->getMessage(),
            "status" => $e->getStatus(),
            "user" => Auth::id()
        ]);
        throw $e;
    } catch (Exception $e) {
      DB::rollBack();
      Log::error("RegistrarAltaIntervencionNivel2 - ERROR", [
          "error" => $e->getMessage(),
          "file"  => $e->getFile(),
          "line"  => $e->getLine(),
          "user"  => Auth::id()
      ]);
      throw new SigesapolException("Error al generar la hoja de hospitalizacion.", 500);
    }
  }
}
