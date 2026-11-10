<?php 

namespace App\UseCases\CentroQuirurgico;

use Illuminate\Http\Request;

use App\Exceptions\SigesapolException;
use App\Models\CqxAtencione;
use App\Models\CqxIntervencion;
use App\Models\Hospitalizacione;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;

class ConfirmarFirmaPdfIntervencionNivel2UseCase
{
  /**
   * Ejecuta el registro de la intervención quirúrgica y su historial.
   *
   * @param Request $request
   * @return array
   * @throws Exception
   */
  public function execute(Request $request)
  {
    try {
      $cqxIntervencionId = $request->id_intervencion;
      $cqxAtencionId = $request->cqx_atencion_id;

      $intervencion = CqxIntervencion::find($cqxIntervencionId);
      if (!$intervencion) {
        throw new SigesapolException("No existe la intervención indicada.", JsonResponse::HTTP_NOT_FOUND);
      }
      $intervencion->update([
        'flag_firma_atencion_qx' => 1,
        "fecha_firma_atencion_qx" => Carbon::now(),
        "ruta_firma_atencion_qx" => "cf_intervencion_quirurgica_{$cqxIntervencionId}.pdf",
      ]);

      $cqxAtencion = CqxAtencione::find($cqxAtencionId);
      if (!$cqxAtencion) {
        throw new SigesapolException("No existe la intervención indicada.", JsonResponse::HTTP_NOT_FOUND);
      }
      $cqxAtencion->update([
        'fecha_firma' => Carbon::now(),
        "ruta_firma" => "cf_intervencion_quirurgica_{$cqxIntervencionId}.pdf",
        "flag_firma" => 1,
      ]);


      // if ($intervencion && $intervencion->id_hospitalizacion) {
      //   $alta_pac = Hospitalizacione::where('id', $intervencion->id_hospitalizacion)->first();
      //   if ($alta_pac->fecha_firma_alta_medica == "") {
      //       $alta_pac->fecha_firma_alta_medica = date("d-m-Y H:i:s");
      //       $alta_pac->ruta_papeleta_egreso = date("Y/m/d") . "/cf_hospitalizacion-papeleta-egreso_" . $intervencion->id_hospitalizacion . ".pdf";
      //       $alta_pac->flag_alta_medica = 1;
      //       $alta_pac->save();
      //   }
      //   if ($alta_pac->ruta_epicrisis == "") {
      //       $alta_pac->ruta_epicrisis = date("Y/m/d") . "/cf_atencion-quirurgico-epicrisis_" . $intervencion->id_cqx_intervencion . ".pdf";
      //       $alta_pac->save();
      //   }
      // }

      return $intervencion;
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
      throw new SigesapolException("Error al confirma la firma de centro quirurgico nivel 2.", 500);
    }
  }
}