<?php 

namespace App\UseCases\CentroQuirurgico;

use App\Models\CqxIntervencion;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SuspenderCqxIntervencionNivel2UseCase
{
  /**
   * Ejecuta el registro de la intervención quirúrgica y su historial.
   *
   * @param number $cqxIntervencionId
   * @param array $data
   * @return array
   * @throws Exception
   */
  public function execute($cqxIntervencionId, array $data)
  {
    DB::beginTransaction();

    try {
      $motivo = isset($data['motivo']) ? $data['motivo'] : null;

      $intervencion = CqxIntervencion::find($cqxIntervencionId);
      if (!$intervencion) {
        throw new Exception('Intervención quirúrgica no encontrada.');
      }

      $intervencion->update([
        'is_suspended' => true,
        "suspended_us" => Auth::user()->id,
        "suspended_at" => Carbon::now(),
        'suspended_motivo' => $motivo,
        'flag'=> 5,
      ]);

      DB::commit();

      return [
        'success' => true,
        'message' => 'Intervención quirúrgica suspendida correctamente.',
      ];
    } catch (Exception $e) {
      DB::rollBack();
      Log::error('[SuspenderCqxIntervencionNivel2UseCase]', [
          'cqx_intervencion_id' => $cqxIntervencionId,
          'user_id' => Auth::user()->id,
          'mensaje' => $e->getMessage(),
          'file' => $e->getFile(),
          'line' => $e->getLine(),
      ]);
      $mensaje = !empty($e->getMessage()) ? $e->getMessage() : 'Error al suspender la intervención quirúrgica nivel 2.';
      throw new Exception($mensaje);
    }
  }
}