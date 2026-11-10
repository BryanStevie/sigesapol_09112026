<?php 

namespace App\UseCases\RecetaVale;

use App\Models\CqxCita;
use App\Models\CqxFormatoEscaneado;
use App\Models\CqxIntervencion;
use App\Models\CqxProcedimiento;
use App\Models\Interconsulta;
use Exception;
use Auth;
use DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class RegistrarCqxIntervencionesHistorialAtencionesUseCase
{
  /**
   * Ejecuta el registro de la intervención quirúrgica y su historial.
   *
   * @param array $data
   * @return array
   * @throws Exception
   */
  public function execute(array $data)
  {
    // Log::info(['usecase_request' => $data]);
    DB::beginTransaction();

    try {
      $isActiveIntervencionQuirurgica = intval($data['isActiveIntervencionQuirurgica']) === 1;
      $isActiveInterconsultaPreQuirurgica = intval($data['isActiveInterconsultaPreQuirurgica']) === 1;
      $idAsegurado = $data['idAsegurado'];
      $idServicio = $data['idServicio'];

      $idPrestacion = isset($data['idPrestacion']) ? $data['idPrestacion'] : null;

      $procedimientos = isset($data['procedimientos']) ? json_decode($data['procedimientos'], true) : array();
      $interconsultas = isset($data['interconsultas']) ? json_decode($data['interconsultas'], true) : array();

      $rqCardiologia = $data['rqCardiologia'];
      $rqNeumologia = $data['rqNeumologia'];
      $rqAnestesiologia = $data['rqAnestesiologia'];
      $servicios_cqx = [
        "rq_cardiologia" => [ "idConsultorio" => 27, "isActive" => false ],
        "rq_neumologia" => [ "idConsultorio" => 63, "isActive" => false ],
        "rq_anestesiologia" => [ "idConsultorio" => 72, "isActive" => false ],
      ];
      
      if($isActiveIntervencionQuirurgica){
        $servicios_cqx = [
          "rq_cardiologia" => [ "idConsultorio" => 27, "isActive" => isset($rqCardiologia) && intval($rqCardiologia) === 1 ? true : false ],
          "rq_neumologia" => [ "idConsultorio" => 63, "isActive" => isset($rqNeumologia) && intval($rqNeumologia) === 1 ? true : false ],
          "rq_anestesiologia" => [ "idConsultorio" => 72, "isActive" => isset($rqAnestesiologia) && intval($rqAnestesiologia) === 1 ? true : false ],
        ];

        // === Buscar prestación ===
        $prestacion = DB::table('prestaciones as p')
                          ->join('sub_consultorios as sc', 'p.id_sub_consultorio', '=', 'sc.id')
                          ->where('p.id', $idPrestacion)
                          ->select(
                              'p.id as id_prestacion',
                              'p.id_cita',
                              'p.id_asegurado',
                              'p.fecha_atencion',
                              'p.id_establecimiento',
                              'sc.id_consultorio',
                              'p.id_tipo_atencion',
                              'p.id_medico',
                              'p.id_ext'
                          )
                          ->first();

        if (!isset($prestacion) || empty($prestacion) || !isset($prestacion->id_cita) || $prestacion->id_cita == 0) {
          return response()->json([
            "message" => "No existe registro de la prestacion o no tiene cita asociada",
            "success" => false
          ], 404);
        }

        $existe = CqxCita::where('id_prestacion', $idPrestacion)->where('id_estado_reg', 1)->exists();
        if($existe){
           return array("success" => false, "message" => "La prestación utilizada para registrar esta información ya se encuentra registrada en Centro Quirúrgico.");
        }

        // === Crear Intervención ===
        $newCqxIntervencion = CqxIntervencion::create([
          'id_prestacion' => $idPrestacion,
          'id_asegurado' => $prestacion->id_asegurado,
          'fecha_solicitud' => $prestacion->fecha_atencion,
          'id_establecimiento' => $prestacion->id_establecimiento,
          'id_consultorio' => $prestacion->id_consultorio,
          'flag' => 1,
          'id_estado_reg' => 1,
          'created_us' => Auth::user()->id,
          'created_at' => Carbon::now(),
          'origen_registro' => 2,
        ]);

        if ($newCqxIntervencion) {
          // Registrar consultorios Neumo,Cardio y Anestesio si estan checkeados
          foreach($servicios_cqx as $servicio) {
            if($servicio['isActive']) {
                $newCqxCita = CqxCita::create([
                    'id_cqx_intervenciones' => $newCqxIntervencion->id,
                    'id_consultorio' => $servicio['idConsultorio'],
                    'id_prestacion' => 0,
                    'id_cita' => 0,
                    'tipo' => 1,
                    'flag' => 1,
                    'id_estado_reg'=> 1,
                    'created_us'=> Auth::user()->id,
                    'created_at' => Carbon::now(),
                ]);
            }
          }

          // === Registrar procedimientos ===
          if (isset($procedimientos) && count($procedimientos) > 0) {
            foreach ($procedimientos as $p) {
              CqxProcedimiento::create([
                'id_cqx_intervenciones' => $newCqxIntervencion->id,
                'id_procedimiento' => $p['id_procedimiento_h_iq'],
                'flag' => 1,
                'flag_pre_post' => 1,
                'created_us' => Auth::user()->id,
                'created_at' => Carbon::now(),
                'id_cqx_cita' => null,
                'flag_examen_patologia' => false,
              ]);
            }
          }

          // === Interconsultas prequirúrgicas ===
          if ($isActiveInterconsultaPreQuirurgica && isset($interconsultas) && count($interconsultas)) {
            foreach ($interconsultas as $key => $interconsulta) {
              $upss = DB::table('upsses')
                          ->select('codigo', 'descripcion_upss')
                          ->where('id_consultorio', $interconsulta['preqx_id_servicio'])
                          ->where('id_tipo_atencion', 1)
                          ->where('estado', 1)
                          ->first();

              if (isset($upss) && !empty($upss)) {
                Interconsulta::create([
                  'id_prestacion' => $idPrestacion,
                  'id_asegurado' => $prestacion->id_asegurado,
                  'id_ext' => $prestacion->id_ext,
                  'id_tipo_atencion' => $prestacion->id_tipo_atencion,
                  'fecha_atencion' => $prestacion->fecha_atencion,
                  'id_medico_solicitante' => $prestacion->id_medico,
                  'motivo_interconsulta' => $interconsulta['preqx_motivo_interconsulta'],
                  'id_establecimiento' => $prestacion->id_establecimiento,
                  'id_estado_reg' => 1,
                  'id_servicio' => $interconsulta['preqx_id_servicio'],
                  'turno' => $this->determinarTurno(),
                  'codigo_upss' => $upss->codigo,
                  'descripcion_upss' => $upss->descripcion_upss,
                  'created_at' => Carbon::now(),
                  'pre_quirurgico' => true
                ]);

                CqxCita::create([
                  'id_cqx_intervenciones' => $newCqxIntervencion->id,
                  'id_consultorio' => $interconsulta['preqx_id_servicio'],
                  'id_prestacion' => 0,
                  'id_cita' => 0,
                  'tipo' => 1,
                  'flag' => 1,
                  'id_estado_reg'=> $servicios_cqx['rq_anestesiologia']['isActive'],
                  'created_us'=> Auth::user()->id,
                  'created_at' => Carbon::now(),
                  'es_interconsulta' => true,
                ]);
              }
            }
          }

          // === Archivos adjuntos (MOPRI) ===
          if (isset($data['fileMopri']) && is_array($data['fileMopri']) && count($data['fileMopri']) > 0) {
            foreach ($data['fileMopri'] as $file) {
              $this->guardarArchivoMopri($file, $newCqxIntervencion, $prestacion);
            }
          }
        }
      }

      DB::commit();
      return array("success" => true, "message" => "El registro se realizó exitosamente");

    } catch (Exception $e) {
      DB::rollBack();
      Log::error("Error en UseCase RegistrarCqxIntervencionesHistorialAtenciones", array("error" => $e->getMessage(), "trace" => $e->getTraceAsString()));
      return array("success" => false, "message" => $e->getMessage());
    }
  }

  private function determinarTurno()
  {
      $hora = Carbon::now()->format('H:i');
      if ($hora >= '01:31' && $hora <= '07:30') return 'MD';
      if ($hora >= '07:31' && $hora <= '13:30') return 'M';
      if ($hora >= '13:31' && $hora <= '19:30') return 'T';
      return 'N';
  }

  private function guardarArchivoMopri($file, $intervencion, $prestacion)
  {
    $timestamp = Carbon::now()->format('YmdHis');
    $uniqueId = uniqid();
    $extension = $file->getClientOriginalExtension();
    $original_name = $file->getClientOriginalName();
    $size = $file->getSize();
    $tipoDocumento = $file->getClientMimeType();

    $directorio = 'formatos_escaneados';
    $nombreArchivo = "mopri_{$timestamp}_{$uniqueId}." . strtolower($extension);
    $rutaRelativa = $directorio . '/' . $nombreArchivo;

    if (!Storage::disk('local')->exists($directorio)) {
      Storage::disk('local')->makeDirectory($directorio);
    }

    Storage::disk('local')->put($rutaRelativa, file_get_contents($file->getRealPath()));

    CqxFormatoEscaneado::create([
      'id_tipo_formato' => 2,
      'id_intervencion' => $intervencion->id,
      'id_asegurado' => $prestacion->id_asegurado,
      'tipo_documento' => $tipoDocumento,
      'tamano_archivo' => $size,
      'fecha_creacion' => Carbon::now()->toDateString(),
      'hora_creacion' => Carbon::now()->toTimeString(),
      'created_us' => Auth::user()->id,
      'created_at' => Carbon::now()->toDateString(),
      'estado' => 1,
      'ruta' => $directorio . '/',
      'nombre_archivo' => $nombreArchivo,
      'flag_origen_escaneado' => 1,
      'nombre_simple' => $original_name
    ]);
  }
}