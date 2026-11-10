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

class ConfirmarFirmaAltaIntervencionNivel2UseCase
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
    //cf_20251222_alta_epicrisis_37215.pdf
    try {
      $cqxIntervencionId = $request->id_intervencion;
      $intervencion = CqxIntervencion::find($cqxIntervencionId);
      if (!$intervencion) {
        throw new SigesapolException("No existe la intervención indicada.", JsonResponse::HTTP_NOT_FOUND);
      }
      $timestamp = Carbon::now()->format('Ymd');
      if ($intervencion && $intervencion->id_hospitalizacion) {
        $alta_pac = Hospitalizacione::where('id', $intervencion->id_hospitalizacion)->first();
        $alta_pac->update([
          'fecha_firma_alta_medica' => Carbon::now(),
          "ruta_papeleta_egreso" => "cf_" . $timestamp . "_papeleta_egreso_" . $cqxIntervencionId . ".pdf",
          "flag_alta_medica" => 1,
          'ruta_epicrisis' => "cf_" . $timestamp . "_alta_epicrisis_" . $cqxIntervencionId . ".pdf", 
        ]);
      }
    } catch (SigesapolException $e) {
      DB::rollBack();
      Log::warning("RegistrarAltaIntervencionNivel2", ["error" => $e->getMessage(), "status" => $e->getStatus(), "user" => Auth::id()]);
      throw $e;
    } catch (Exception $e) {
      DB::rollBack();
      Log::error("RegistrarAltaIntervencionNivel2 - ERROR", ["error" => $e->getMessage(), "file" => $e->getFile(), "line" => $e->getLine(), "user" => Auth::id()]);
      throw new SigesapolException("Error al confirma la firma de centro quirurgico nivel 2.", 500);
    }
  }
}