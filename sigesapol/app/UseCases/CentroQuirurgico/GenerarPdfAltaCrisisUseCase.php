<?php 

namespace App\UseCases\CentroQuirurgico;

use App\Models\Establecimiento;
use App\Models\Hospitalizacione;
use App\Models\Prestaciones;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;

class GenerarPdfAltaCrisisUseCase
{
  /**
   *
   * @param number $cqxIntervencionId
   * @throws Exception
   */
  public function execute($cqxIntervencionId)
  {
    try {
      $epicrisis = DB::table('cqx_atenciones as a')
                        ->join('cqx_intervenciones as i', 'a.id_cqx_intervencion', '=', 'i.id')
                        ->select('a.*', 'i.id')
                        ->where('i.id', $cqxIntervencionId)
                        ->first();
      
      $model_hospitalizacion = new Hospitalizacione();
      $hospitalizacion = $model_hospitalizacion->getHospitalizacionByIdCqx($epicrisis->id_hospitalizacion);

      $dias_estancia = 0;
      $fecha_ingreso = Carbon::createFromFormat('Y-m-d H:i:s', $hospitalizacion->fecha_ingreso);
      $fecha_alta_medica = Carbon::createFromFormat('Y-m-d H:i:s', $hospitalizacion->fecha_alta_medica);
      $diferencia_dias = $fecha_alta_medica->diffInDays($fecha_ingreso);
      $dia_estancia = $diferencia_dias + 1;
      
      $servicios_concurrencia = null;
      if ($hospitalizacion->id_tipo_egreso == 1) {
          $servicios_concurrencia = $model_hospitalizacion->getServiciosConcurrenciaByIdHospitalizacion($epicrisis->id_hospitalizacion);
      }

      $model_establecimiento = new Establecimiento();
      $establecimiento = $model_establecimiento->GetById(Auth::user()->id_establecimiento);
      $nombre_establecimiento = $establecimiento[0]->nombre;

      $model_prestacion = new Prestaciones();
      $diagnosticos_egreso = $model_prestacion->GetDiagnosticoByIdPrestacion($epicrisis->id_prestacion);
      $pdf = PDF::loadView('frontend.centro_quirurgico.nivel_2.pdf_alta_epicrisis_nivel_2', compact('hospitalizacion', 'dia_estancia', 'servicios_concurrencia', 'nombre_establecimiento', 'epicrisis', 'diagnosticos_egreso'));

      $pdf->getDomPDF()->set_option("enable_php", true);
      $pdf->setPaper('A4', 'portable');
      return $pdf->stream('alta_crisis.pdf');
    } catch (Exception $e) {
      Log::error('[GenerarPdfByIdUseCase]', [
          'cqx_intervencion_id' => $cqxIntervencionId,
          'user_id' => Auth::user()->id,
          'mensaje' => $e->getMessage(),
          'file' => $e->getFile(),
          'line' => $e->getLine(),
      ]);
      $mensaje = !empty($e->getMessage()) ? $e->getMessage() : 'Error al generar el pdf de la intervención quirúrgica por ID.';
      throw new Exception($mensaje);
    }
  }
}