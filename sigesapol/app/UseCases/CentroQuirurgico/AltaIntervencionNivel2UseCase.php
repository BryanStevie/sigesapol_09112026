<?php 

namespace App\UseCases\CentroQuirurgico;

use App\Models\CqxIntervencion;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade as PDF;

class AltaIntervencionNivel2UseCase
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
  
      $upsses = DB::table('consultorios as c')
                    ->join('upsses as u', 'u.id_consultorio', '=', 'c.id')
                    ->selectRaw("
                        CONCAT(u.descripcion_upss, ' / ', u.codigo) as nombre,
                        u.id as id_upss,
                        u.codigo as codigo_upss,
                        c.id as id_consultorio,
                        c.nombre as nombre_consultorio
                    ")
                    ->where('u.id_tipo_atencion', '3')
                    ->where('u.estado', '1')
                    ->where('u.descripcion_upss', 'like', 'H%')
                    ->orderBy('nombre', 'asc')
                    ->get();
   
      $currentConsultorio = DB::table('cqx_intervenciones as ci')
                                ->join('consultorios as c', 'ci.id_consultorio', '=', 'c.id')
                                ->select('c.id', 'c.nombre')
                                ->where('ci.id', $cqxIntervencionId)
                                ->first();
  
      $consultorios = DB::table('consultorios as c3')
                          ->join('asignacion_servicios as as2', 'as2.id_consultorio', '=', 'c3.id')
                          ->join('pisos as p1', 'as2.id_piso', '=', 'p1.id')
                          ->join('pabellones as p2', 'p1.id_pabellon', '=', 'p2.id')
                          ->select('c3.id', 'c3.nombre')
                          ->where('as2.id_establecimiento', $intervencion->id_establecimiento)
                          ->where('as2.nombre_atencion', "HOSPITALIZACION")
                          // ->where('p2.id', $id_pabellon)
                          ->where('as2.estado', 0)
                          ->distinct()
                          ->orderBy('c3.nombre', 'asc')
                          ->get();
      
      // $consultorios = DB::table('upsses as up')
      //                     ->join('consultorios as co', 'co.id', '=', 'up.id_consultorio')
      //                     ->select('co.id', 'co.nombre')
      //                     ->where('up.estado', 1)
      //                     ->where('up.id_tipo_atencion', 3)
      //                     ->distinct()
      //                     ->orderBy('co.id')
      //                     ->get();
 
      return [
        'success' => true,
        'message' => 'Intervención quirúrgica suspendida correctamente.',
        'data' => [
          "upsses" => $upsses,
          "currentConsultorio" => $currentConsultorio,
          "consultorios" => $consultorios,
          "intervencion" => $intervencion,
        ],
      ];
    } catch (Exception $e) {
      $mensaje = !empty($e->getMessage()) ? $e->getMessage() : 'Error al suspender la intervención quirúrgica nivel 2.';
      $context = [
        'user_id' => Auth::id(),
        'mensaje' => $mensaje,
        'file'    => $e->getFile(),
        'line'    => $e->getLine(),
      ];
      Log::error("[SuspenderCqxIntervencionNivel2UseCase]\n" . json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
      throw new Exception($mensaje);
    }
  }
}