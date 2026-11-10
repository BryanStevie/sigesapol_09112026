<?php 

namespace App\UseCases\CentroQuirurgico;

use App\Exceptions\SigesapolException;
use App\Models\CqxIntervencion;
use App\Models\Hospitalizacione;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class GenerarPdfPapeletaEgresoUseCase
{
  /**
   *
   * @param number $cqxIntervencionId
   * @throws Exception
   */
  public function execute($cqxIntervencionId)
  {
    try {
      $intervencion = CqxIntervencion::select('id', 'id_hospitalizacion',DB::raw("to_char(fecha_intervencion, 'DD/MM/YYYY hh:mi:ss') as fecha_intervencion"))
                                        ->where('id', $cqxIntervencionId)->first();

      if (!isset($intervencion) || !isset($intervencion->id_hospitalizacion) || empty($intervencion->id_hospitalizacion)) {
        throw new SigesapolException("No existe la intervención indicada.", JsonResponse::HTTP_NOT_FOUND);
      }

      $model_hospitalizacion = new Hospitalizacione();
      $hospitalizacion = $model_hospitalizacion->getDatosPapeletaEgreso($intervencion->id_hospitalizacion);
      
      $date = Carbon::now();
      $fechaActual = $date->format('d/m/Y');
      $horaActual = $date->format('H:i:s');

      $dias_estancia = 0;
      $fecha_ingreso = Carbon::createFromFormat('d/m/Y', $hospitalizacion->fecha_ingreso);

      $fecha_alta_administrativa = Carbon::createFromFormat('d/m/Y', $hospitalizacion->fecha_alta_medica);
      $dias_calculados = $fecha_alta_administrativa->diffInDays($fecha_ingreso);
      
      $dias_estancia = $dias_calculados + 1;

      $user_actual = Auth::user();
          
      $asegurado = DB::table('asegurados')
          ->leftJoin('distritos', 'asegurados.id_distrito','=','distritos.id_distrito')
          ->leftJoin('provincias', 'distritos.id_provincia','=','provincias.id_provincia')
          ->leftJoin('departamentos', 'provincias.id_departamento','=','departamentos.id_departamento')
          ->leftJoin('distritos as distrito_nacimiento', 'asegurados.id_distrito_nacimiento','=','distrito_nacimiento.id_distrito')
          ->leftJoin('provincias as provincia_nacimiento', 'distrito_nacimiento.id_provincia','=','provincia_nacimiento.id_provincia')
          ->leftJoin('departamentos as departamento_nacimiento', 'provincia_nacimiento.id_departamento','=','departamento_nacimiento.id_departamento')
          ->leftJoin('distritos as distrito_procedencia', 'asegurados.id_distrito_procedencia','=','distrito_procedencia.id_distrito')
          ->leftJoin('provincias as provincia_procedencia', 'distrito_procedencia.id_provincia','=','provincia_procedencia.id_provincia')
          ->leftJoin('departamentos as departamento_procedencia', 'provincia_procedencia.id_departamento','=','departamento_procedencia.id_departamento')
          ->leftJoin('razas', 'asegurados.id_raza','=','razas.id')
          ->leftJoin('estado_civiles', 'asegurados.id_estado_civil','=','estado_civiles.id')
          ->leftJoin('grado_instrucciones', 'asegurados.id_grado_instruccion','=','grado_instrucciones.id')
          ->leftJoin('ocupaciones', 'asegurados.id_ocupacion','=','ocupaciones.id')
          ->leftJoin('religiones', 'asegurados.id_religion','=','religiones.id')
          ->select('asegurados.*', 'distritos.descripcion as distrito', 'provincias.descripcion as provincia', 'departamentos.descripcion as departamento', 'razas.nombre as etnia',
              'estado_civiles.nombre as estado_civil', 'grado_instrucciones.nombre as grado_instruccion', 'ocupaciones.nombre as ocupacion', 'religiones.nombre as religion',
              'distrito_nacimiento.descripcion as distrito_nacimiento2', 'provincia_nacimiento.descripcion as provincia_nacimiento2', 'departamento_nacimiento.descripcion as departamento_nacimiento2',
              'distrito_procedencia.descripcion as distrito_procedencia2', 'provincia_procedencia.descripcion as provincia_procedencia2', 'departamento_procedencia.descripcion as departamento_procedencia2')
          ->where('asegurados.id',$hospitalizacion->id_asegurado)
          ->where('asegurados.id_estado_reg', 1)
          ->first();

      $titular = null;
      $persona_datos_adicionales = null;
      $grado = null;

      if ($asegurado->id_asegurado_titular != null || $asegurado->id_asegurado_titular > 0) {
          $titular = DB::table('asegurados')
              ->select('asegurados.*')
              ->where('id',$asegurado->id_asegurado_titular)
              ->where('id_estado_reg', 1)
              ->first();
          
          if ($titular != null) {
              $persona_datos_adicionales = DB::table('persona_datos_adicionales')
                  ->select('persona_datos_adicionales.*')
                  ->where('id_asegurado',$titular->id)
                  ->first();
              if ($persona_datos_adicionales != null) {
                  $grado = DB::table('titular_grados')
                  ->select('titular_grados.*')
                  ->where('id',$persona_datos_adicionales->id_grado)
                  ->first();
              }
          }
      }
      $concurrencias = DB::table('hospitalizacion_concurrencias as hc')
              ->join('consultorios as c', 'c.id', '=', 'hc.id_servicio')
              ->select('hc.id', 'hc.id_hospitalizacion', 'hc.id_servicio', 'c.nombre')
              ->where('hc.id_hospitalizacion', $intervencion->id_hospitalizacion)
              ->get();
      
      $cqx_atencion = DB::table('cqx_atenciones')->select('cqx_atenciones.id_prestacion')->where('cqx_atenciones.id_hospitalizacion', $intervencion->id_hospitalizacion)->first();  
      $model_prestacion = new \App\Models\Prestaciones; 
      $diagnosticos = $model_prestacion->GetDiagnosticoByIdPrestacionHospitalizacion($cqx_atencion->id_prestacion);
      $pdf = PDF::loadView('frontend.centro_quirurgico.nivel_2.pdf_papeleta_egreso_nivel_2', compact('asegurado', 'concurrencias', 'dias_estancia', 'fechaActual', 'grado', 'horaActual', 'hospitalizacion', 'persona_datos_adicionales', 'titular', 'user_actual', 'diagnosticos' ,'intervencion'));
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
      throw new SigesapolException("Error en el alta de intervención.", 500);
    }
  }
}