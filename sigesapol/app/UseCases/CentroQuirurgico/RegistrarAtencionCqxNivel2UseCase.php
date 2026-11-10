<?php 

namespace App\UseCases\CentroQuirurgico;

use App\Models\CqxAtencione;
use App\Models\CqxIntervencion;
use App\Models\CqxProfesional;
use App\Models\HospitalizacionAtencione;
use App\Models\Hospitalizacione;
use App\Models\Prestacion_procedimiento;
use App\Models\Prestaciones;
use App\Models\Receta_diagnostico;
use App\Models\Upss;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegistrarAtencionCqxNivel2UseCase
{
  /**
   * Ejecuta el registro de la intervención quirúrgica y su historial.
   *
   * @param array $data
   * @return array
   * @throws Exception
   */
  public function execute(Request $request)
  {
    DB::beginTransaction();
    try {
      $cirujanos = $request->cirujanos;
      $anastesiologos = $request->anastesiologos;
      $enfermerasIntrumentistas = $request->enfermerasIntrumentistas;
      $diagnosticos = $request->diagnosticos;
      $procedimientos = $request->procedimientos;
      $tieneComplicacionMedica = $request->tieneComplicacionMedica;
      
      $fechaHoraAtencion = Carbon::createFromFormat('d/m/Y H:i', $request->fechaAtencion.' '.$request->horaAtencion)->format('Y-m-d H:i:s.u');

      $upss = Upss::where('id', $request->upssId)->first();
      $tieneComplicacionMedica = intval($request->tieneComplicacionMedica) === 1 ? true : false;
      $horaInicioIntervencion = Carbon::parse($request->horaInicioIntervencion)->format('Y-m-d H:i:s');
      $horaFinIntervencion = Carbon::parse($request->horaFinIntervencion)->format('Y-m-d H:i:s');

      $horaInicioActoQx = Carbon::parse($request->horaInicioActoQx)->format('Y-m-d H:i:s');
      $horaFinActoQx = Carbon::parse($request->horaFinActoQx)->format('Y-m-d H:i:s');

      Hospitalizacione::where('id', $request->hospitalizacionId)->update([
        'id_user_atencion' => Auth::user()->id,
        'fecha_atencion' => Carbon::now(),
        'estado' => 4,
        'updated_at' => Carbon::now()
      ]);
      

      if ($cirujanos && count($cirujanos) > 0) {
        foreach ($cirujanos as $key => $cirujano) {
          $newCirujano = CqxProfesional::create([
            "id_cqx_intervenciones" => $request->intervencionId,
            "id_medico" => $cirujano["cirujano_profesional_id"],
            "id_tipo_professional" => $cirujano["cirujano_tipo_cirujano"],
            "flag_validacion" => 1,
            "flag_personal" => 0,
            "estado" => 1,
            "created_us" => Auth::user()->id,
            "created_at" => Carbon::now()
          ]);
        }
      }

      if ($anastesiologos && count($anastesiologos) > 0) {
        foreach ($anastesiologos as $key => $anastesiologo) {
          $newAnastesiologo = CqxProfesional::create([
            "id_cqx_intervenciones" => $request->intervencionId,
            "id_medico" => $anastesiologo["anastesiologo_profesional_id"],
            "id_tipo_professional" => $anastesiologo["anastesiologo_tipo_cirujano"],
            "flag_validacion" => 1,
            "flag_personal" => 1,
            "estado" => 1,
            "created_us" => Auth::user()->id,
            "created_at" => Carbon::now()
          ]);
        }
      }

      if ($enfermerasIntrumentistas && count($enfermerasIntrumentistas) > 0) {
        foreach ($enfermerasIntrumentistas as $key => $enfermeras) {
          $newEnfermera = CqxProfesional::create([
            "id_cqx_intervenciones" => $request->intervencionId,
            "id_medico" => $enfermeras["enfermera_intru_profesional_id"],
            "id_tipo_professional" => $enfermeras["enfermera_intru_tipo_cirujano"],
            "flag_validacion" => 1,
            "flag_personal" => 2,
            "estado" => 1,
            "created_us" => Auth::user()->id,
            "created_at" => Carbon::now()
          ]);
        }
      }

      $newPrestacionPq = Prestaciones::create([
        "id_asegurado" => $request->aseguradoId,
        "id_tipo_atencion" => 3,
        "id_establecimiento" => $request->establecimientoId,
        "id_sub_consultorio" => 53,
        "id_medico" => $request->medicoId,
        "id_motivo_alta" => 0,
        "id_estado_reg" => 1,
        "anamnesis" => "",
        "examen_fisico" => "",
        "condicion" => 0,
        "observacion" => "",
        "created_at" => Carbon::now(),
        "created_us" => Auth::user()->id,
        "antecedente" => null,
        "plan_trabajo" => "",
        "aspectos_generales" => "",
        "temperatura" => 0,
        "sa02_porcentaje" => 0,
        "presion_arterial_x" => 0,
        "presion_arterial_y" => 0,
        "talla_cm" => 0,
        "tamano_cintura_cm" => 0,
        "frecuencia_respiratoria" => 0,
        "peso_kg" => 0,
        "frecuencia_cardiaca_fetal" => 0,
        "frecuencia_cardiaca" => 0,
        "fi02" => 0,
        "motivo_visita" => "",
        "tiempo_enfermedad" => "",
        "examen_clinico" => "",
        "examen_auxiliar" => "",
        "tratamiento_auxiliar" => "",
        "ipress" => null,
        "upss" => $request->upssId,
        "flag" => 0,
        "id_modalidad" => null,
        "codigo_upss" => $upss->codigo,
        "receta_prescrita" => null,
        "antecedente_familiar" => null,
        "adicional" => 0,
        "registrar_cita_por_medico" => 0,
        "cups_pnp" => null,
        "flag_ref_cref" => 0,
      ]);

      HospitalizacionAtencione::create([
        "id_prestacion" => $newPrestacionPq->id,
        "id_ext" => $request->hospitalizacionId,
        "created_us" => Auth::user()->id,
        "created_at" => Carbon::now(),
      ]);

      if ($procedimientos && count($procedimientos) > 0) {
        foreach ($procedimientos as $key => $procedimiento) {
          $newProcedimiento = Prestacion_procedimiento::create([
            "id_prestacion" => $newPrestacionPq->id,
            "id_procedimiento" => $procedimiento["procedure_id"],
            "id_estado_reg" => 1,
            "created_at" => Carbon::now(),
            "created_us" => Auth::user()->id,
            "cantidad" => $procedimiento["procedimiento_cantidad"],
            "id_diagnostico" => $procedimiento["procedimiento_diagnostico"],
            "tiempo" => $procedimiento["procedimiento_tiempo"],
          ]);
        }
      }

      if ($diagnosticos && count($diagnosticos) > 0) {
        foreach ($diagnosticos as $key => $diagnostico) {
          $newDiagnostico = Receta_diagnostico::create([
            "id_diagnostico" => $diagnostico["diagnosticos_id"],
            "id_tipo_diagnostico" => $diagnostico["tipo_diagnosticos"],
            "estado" => 1,
            "id_prestacion" => $newPrestacionPq->id,
            "created_at" => Carbon::now(),
            "created_us" => Auth::user()->id,
            'clasificacion_cqx' => $diagnostico["clasificacion"],
            "id_intervencion" => $request->intervencionId,
          ]);
        }
      }

      $newCqxAtencione = CqxAtencione::create([
        "id_cqx_intervencion" => $request->intervencionId,
        "id_hospitalizacion" => $request->hospitalizacionId,
        "id_prestacion" => $newPrestacionPq->id,
        "created_us" => Auth::user()->id,
        "created_at" => $fechaHoraAtencion,
        "id_estado_reg" => 1,
        "codigo_siteds" => $request->codigoSiteds,
        "id_servicio" => $request->servicioId,
        "tiene_complicacion_medica" => $tieneComplicacionMedica,
        "codigo_upss" => $upss->codigo,
        "id_clasificacion_cirugia" => $request->clasificacionCirugiaId,
        "id_tipo_anestecia" => $request->tipoAnestesiaId,
        "horas_programadas" => $request->hrProgramadas,
        "hora_inicio_intervencion" => $horaInicioIntervencion,
        "hora_fin_intervencion" => $horaFinIntervencion,
        "hora_inicio_acto_qx" => $horaInicioActoQx,
        "hora_fin_acto_qx" => $horaFinActoQx,
        "id_tipo_programacion" => $request->idTipoProgramacion,
        "horas_acto_qx" => $request->horasActoQx,
        "horas_efectivas" => $request->horasEfectivas,
      ]);

      CqxIntervencion::where('id', $request->intervencionId)->update([
        'id_prestacion' => $newPrestacionPq->id,
        'updated_us' => Auth::user()->id,
        'updated_at' => Carbon::now(),
        'fecha_intervencion' => $fechaHoraAtencion,
        'flag'=> 3,
      ]);

      DB::commit();
      return [
        'success' => true,
        'message' => 'Intervención quirúrgica suspendida correctamente.',
        'data' => $newCqxAtencione,
      ];
    } catch (Exception $e) {
      DB::rollBack();
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