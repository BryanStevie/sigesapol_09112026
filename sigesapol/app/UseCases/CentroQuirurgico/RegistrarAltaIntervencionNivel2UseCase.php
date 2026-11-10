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

class RegistrarAltaIntervencionNivel2UseCase
{
  /**
   *
   * @param request $request
   * @return array
   * @throws Exception
   */
  public function execute($request)
  {
    DB::beginTransaction();
    try {

      $intervencion = CqxIntervencion::find($request->intervencionId);
      if (!$intervencion) {
          throw new SigesapolException("No existe la intervención indicada.", JsonResponse::HTTP_NOT_FOUND);
      }
      if (!$intervencion->id_prestacion) {
        throw new SigesapolException("Tiene que realizar la atencion de la orden para dar de alta", JsonResponse::HTTP_NOT_FOUND);
      }
      if (!$intervencion->id_hospitalizacion) {
        throw new SigesapolException("La intervención no tiene hospitalización asociada.", JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
      }
      $cqxAtencion = CqxAtencione::where('id_cqx_intervencion', $request->intervencionId)->first();
      if (!$cqxAtencion) {
        throw new SigesapolException("No existe la intervención indicada.", JsonResponse::HTTP_NOT_FOUND);
      }
      
      $cqxAtencion->update([
        'egreso_resumen' => isset($request->resumenEnfermedadActual) && !empty(isset($request->resumenEnfermedadActual)) ? $request->resumenEnfermedadActual : null,
        'egreso_examen_clinico' => isset($request->examenClinico) && !empty(isset($request->examenClinico)) ? $request->examenClinico : null,
        'egreso_examen_auxiliar' => isset($request->examenesAuxiliares) && !empty(isset($request->examenesAuxiliares)) ? $request->examenesAuxiliares : null,
        'egreso_evolucion' => isset($request->evolucion) && !empty(isset($request->evolucion)) ? $request->evolucion : null,
        'egreso_tratamiento' => isset($request->tratamiento) && !empty(isset($request->tratamiento)) ? $request->tratamiento : null,
        'egreso_procedimiento' => isset($request->procedimientosTerapeuticos) && !empty(isset($request->procedimientosTerapeuticos)) ? $request->procedimientosTerapeuticos : null,
        'egreso_complicacion' => isset($request->complicaciones) && !empty(isset($request->complicaciones)) ? $request->complicaciones : null,
        'egreso_medidas_generales' => isset($request->medidasGenerales) && !empty(isset($request->medidasGenerales)) ? $request->medidasGenerales : null,
        'egreso_medicamentos_prescritos' => isset($request->medidamentosPrescritos) && !empty(isset($request->medidamentosPrescritos)) ? $request->medidamentosPrescritos : null,
        'egreso_pronostico' => isset($request->pronostico) && !empty(isset($request->pronostico)) ? $request->pronostico : null,
        /*  "id_condicion_alta" => $request->condicion,
            "fecha_alta_medica" => $request->fechaAltaMedica,
            "hora_alta_medica" => $request->horaAltaMedica,
            "id_tipo_egreso" => $request->destino, 
        */
      ]);

      $hospitalizacion = Hospitalizacione::find($intervencion->id_hospitalizacion);
      if(!$hospitalizacion) {
        throw new SigesapolException("No se encontró la hospitalización.", JsonResponse::HTTP_NOT_FOUND);
      }             
      
      $destino = $request->destino;
      if (!isset($destino) || empty($destino) || (intval($destino) !== 1 && intval($destino) !== 2)) {
        throw new SigesapolException("No se encontró la hospitalización.", JsonResponse::HTTP_BAD_REQUEST);
      }
  
      $fechaAltaMedica = Carbon::createFromFormat('d/m/Y', $request->fechaEgreso)->format('Y-m-d');
      $horaAltaMedica  = Carbon::createFromFormat('H:i', $request->horaEgreso)->format('H:i:s');
      $fechaAltaMedicaDB = null;
      $horaAltaMedicaDB  = null;
      if (intval($destino) === 1) {
          $fechaAltaMedicaDB = $fechaAltaMedica;
          $horaAltaMedicaDB  = $horaAltaMedica;
      }

         $hospitalizacion->update([
            "estado" => intval($destino) === 1 ? 6 : 1,
            "id_tipo_egreso" => $request->destino,
            "id_condicion_alta" => $request->condicion,
            "fecha_alta_cq_n_2" => $fechaAltaMedica,
            "hora_alta_cq_n_2" => $horaAltaMedica,
            "fecha_alta_medica" => $fechaAltaMedicaDB,
            "hora_alta_medica" => $horaAltaMedicaDB,
            "id_medico_alta" => Auth::user()->id,
            "id_consultorio" => isset($request->salaDestino) && !empty($request->salaDestino) ? $request->salaDestino : null,
            "id_upss_encargado" => isset($request->servicioHospitalizacion) && !empty($request->servicioHospitalizacion) ? $request->servicioHospitalizacion : null,
            "id_cama_estado" => isset($request->cama) && !empty($request->cama) ? $request->cama : null,
            "id_cama" => isset($request->cama) && !empty($request->cama) ? $request->cama : null,
            'egreso_resumen' => isset($request->resumenEnfermedadActual) && !empty(isset($request->resumenEnfermedadActual)) ? $request->resumenEnfermedadActual : null,
            'egreso_examen_clinico' => isset($request->examenClinico) && !empty(isset($request->examenClinico)) ? $request->examenClinico : null,
            'egreso_examen_auxiliar' => isset($request->examenesAuxiliares) && !empty(isset($request->examenesAuxiliares)) ? $request->examenesAuxiliares : null,
            'egreso_evolucion' => isset($request->evolucion) && !empty(isset($request->evolucion)) ? $request->evolucion : null,
            'egreso_tratamiento' => isset($request->tratamiento) && !empty(isset($request->tratamiento)) ? $request->tratamiento : null,
            'egreso_procedimiento' => isset($request->procedimientosTerapeuticos) && !empty(isset($request->procedimientosTerapeuticos)) ? $request->procedimientosTerapeuticos : null,
            'egreso_complicacion' => isset($request->complicaciones) && !empty(isset($request->complicaciones)) ? $request->complicaciones : null,
            'egreso_medidas_generales' => isset($request->medidasGenerales) && !empty(isset($request->medidasGenerales)) ? $request->medidasGenerales : null,
            'egreso_medicamentos_prescritos' => isset($request->medidamentosPrescritos) && !empty(isset($request->medidamentosPrescritos)) ? $request->medidamentosPrescritos : null,
            'egreso_pronostico' => isset($request->pronostico) && !empty(isset($request->pronostico)) ? $request->pronostico : null,
          ]);
    
     
      if (intval($destino) === 2 && isset($request->cama) && intval($request->cama) > 0) {
        CambioCama::create([
          "id_cama_atencion" => isset($hospitalizacion->id_cama) && !empty($hospitalizacion->id_cama) ? $hospitalizacion->id_cama : null,
          "estado_ubicacion" => 3,
          "fecha_hora_inicio" => $fechaAltaMedica . ' ' . $horaAltaMedica,
          "id_cama" => $request->cama,
          "id_prestacion" => $intervencion->id_prestacion,
          "id_evolucion" => null,
          "estado" => 1,
          "id_upss_encargado_atencion" => null,
          "id_servicio_encargado_atencion" => null,
          "id_upss_encargado" => $request->servicioHospitalizacion,
          "id_servicio_encargado" => $request->salaDestino,
          "created_at" => Carbon::now()
        ]);

        Cama::where('id', $request->cama)->update([
          "estado" => 0,
          "situacion" => '1'
        ]);
      }

      $intervencion->update([
        'updated_us' => Auth::user()->id,
        'updated_at' => Carbon::now(),
        "id_tipo_egreso" => $request->destino,
        'flag'=> 4,
      ]);

     DB::commit();
      return [
        'data' => $request->all()
      ];
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
      throw new SigesapolException("Error en el alta de intervención.", 500);
    }
  }
}