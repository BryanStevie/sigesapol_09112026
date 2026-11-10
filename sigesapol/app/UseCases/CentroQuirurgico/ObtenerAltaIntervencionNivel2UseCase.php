<?php 

namespace App\UseCases\CentroQuirurgico;

use App\Exceptions\SigesapolException;
use App\Models\Cama;
use App\Models\CambioCama;
use App\Models\CqxAtencione;
use App\Models\CqxIntervencion;
use App\Models\Hospitalizacione;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ObtenerAltaIntervencionNivel2UseCase
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
      $result =   DB::table('cqx_atenciones as ca')
                  ->select([
                      'ca.id as id_cqx_atencion',
                      'ca.id_cqx_intervencion',
                      'h.id as id_hospitalizacion',
                      'ca.id_prestacion',

                      'ca.egreso_resumen',
                      'ca.egreso_examen_clinico',
                      'ca.egreso_examen_auxiliar',
                      'ca.egreso_evolucion',
                      'ca.egreso_tratamiento',
                      'ca.egreso_procedimiento',
                      'ca.egreso_complicacion',
                      'ca.egreso_medidas_generales',
                      'ca.egreso_medicamentos_prescritos',
                      'ca.egreso_pronostico',

                      'h.estado as hospitalizacion_estado',
                      'h.id_tipo_egreso as hospitalizacion_id_tipo_egreso',
                      'h.id_condicion_alta as hospitalizacion_id_condicion_alta',
                      'h.fecha_alta_cq_n_2 as hospitalizacion_fecha_alta_medica',
                      'h.hora_alta_cq_n_2 as hospitalizacion_hora_alta_medica',
/*
                      COALESCE(NULLIF(cq.fecha_alta_cq_n_2, ''), h.fecha_alta_medica) 
                          AS fecha_alta_final,

                      COALESCE(NULLIF(cq.hora_alta_cq_n_2, ''), h.hora_alta_medica) 
                          AS hora_alta_final,
*/
                      'h.id_medico_alta as hospitalizacion_id_medico_alt',

                      'u.id as id_upss',
                      'u.descripcion_upss',

                      'c.id as id_cama',
                      'c.nombre as cama_nombre',

                      'c2.id as id_consultorio',
                      'c2.nombre as consultorio',
                  ])
                  ->join('hospitalizaciones as h', 'ca.id_hospitalizacion', '=', 'h.id')
                  ->join('upsses as u', 'h.id_upss_encargado', '=', 'u.id')
                  ->leftJoin('camas as c', 'h.id_cama_estado', '=', 'c.id')
                  ->leftJoin('consultorios as c2', 'h.id_consultorio', '=', 'c2.id')
                  ->where('ca.id_cqx_intervencion', $cqxIntervencionId)
                  ->first();

      if (!$result) {
        throw new SigesapolException("No existe una alta registrar", JsonResponse::HTTP_NOT_FOUND);
      }

      return $result;
    } catch (SigesapolException $e) {
        Log::warning("ObtenerAltaIntervencionNivel2UseCase - WARNING", [
            "error" => $e->getMessage(),
            "status" => $e->getStatus(),
            "user" => Auth::id()
        ]);
        throw $e;
    } catch (Exception $e) {
      Log::error("ObtenerAltaIntervencionNivel2UseCase - ERROR", [
          "error" => $e->getMessage(),
          "file"  => $e->getFile(),
          "line"  => $e->getLine(),
          "user"  => Auth::id()
      ]);
      throw new SigesapolException("Error en el alta de intervención.", 500);
    }
  }
}