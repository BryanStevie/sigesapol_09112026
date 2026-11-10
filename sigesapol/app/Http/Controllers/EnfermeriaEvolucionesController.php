<?php

namespace App\Http\Controllers;

use App\Models\Cpt_Cpms;
use App\Models\Diagnostico;
use App\Models\Emergencia;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Nota_enfermeria_evoluciones;
use App\Models\Nota_enfermeria_evoluciones_detalle;
use App\Models\Nota_enfermeria_evoluciones_invasivos;
use App\Models\TipoAtencione;
use App\Models\Turno;
use App\Models\Punto;
use App\Models\Invasivo;
use App\Models\Prestacion_procedimiento;
use App\Models\Enfermeria;
use App\Models\Enfermeria_cpt;
use App\Models\Evolucion;
use App\Models\Hospitalizacione;
use App\Models\Prestaciones;
use Archive7z\Archive7z;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use Illuminate\Support\Facades\Log;
use Auth;
//use DB;
use Illuminate\Support\Facades\DB;

class EnfermeriaEvolucionesController extends Controller
{
  function __construct()
  {
    $this->middleware('auth');
  }

  function esHoraValida($hora)
  {
    return preg_match('/^([01]\d|2[0-3]):[0-5]\d:[0-5]\d$/', $hora);
  }

  public function registrar_nota_ingreso_evoluciones(Request $request)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $valor_response = 0;
    $data = $request->all();
    try {

      DB::beginTransaction();

      $objNota_enfermeria_evoluciones = new Nota_enfermeria_evoluciones;
      $objNota_enfermeria_evoluciones->id_usuario_registro = Auth::user()->id;
      $objNota_enfermeria_evoluciones->id_enfermeria = $request->id_enfermeria;
      $objNota_enfermeria_evoluciones->id_tipo_atenciones = $request->id_tipo_atenciones_registro;
      $objNota_enfermeria_evoluciones->descripcion_subjetivo = $request->descripcion_subjetivo;
      $objNota_enfermeria_evoluciones->descripcion_objetivo = $request->descripcion_objetivo;
      $objNota_enfermeria_evoluciones->descripcion_analisis = $request->descripcion_analisis;
      $objNota_enfermeria_evoluciones->descripcion_plan = $request->descripcion_plan;
      $objNota_enfermeria_evoluciones->descripcion_intervenciones = $request->descripcion_intervenciones;
      $objNota_enfermeria_evoluciones->descripcion_evaluaciones = $request->descripcion_evaluaciones;
      $objNota_enfermeria_evoluciones->id_establecimiento = Auth::user()->id_establecimiento;
      $objNota_enfermeria_evoluciones->id_asegurado = $request->id_asegurado2;
      $objNota_enfermeria_evoluciones->id_prestacion = $request->id_prestacion_registro == '' ? null : $request->id_prestacion_registro;
      $objNota_enfermeria_evoluciones->id_turno = $request->turno;
      $objNota_enfermeria_evoluciones->img_puntos_base = $request->img_puntos_base;
      $objNota_enfermeria_evoluciones->estado = 1;
      $objNota_enfermeria_evoluciones->flag_firma = 0;
      $objNota_enfermeria_evoluciones->created_at = $request->fecha_registro_evolucion . ' ' . (preg_match('/^([01]\d|2[0-3]):[0-5]\d:[0-5]\d$/', $request->hora_registro_evolucion) ? $request->hora_registro_evolucion : $request->hora_registro_evolucion . ':00');
      $objNota_enfermeria_evoluciones->updated_at = $request->fecha_registro_evolucion . ' ' . (preg_match('/^([01]\d|2[0-3]):[0-5]\d:[0-5]\d$/', $request->hora_registro_evolucion) ? $request->hora_registro_evolucion : $request->hora_registro_evolucion . ':00');
      $objNota_enfermeria_evoluciones->save();

      $id_enfermeria_evoluciones = $objNota_enfermeria_evoluciones->id;
      //INAVASIVOS
      if (!empty($request->invasivos)) {
        foreach ($request->invasivos as $key => $value) {
          $evoluciones_invasivos = new Nota_enfermeria_evoluciones_invasivos();
          $evoluciones_invasivos->tipo = $value;
          $evoluciones_invasivos->fecha = isset($data['invasivo_fecha'][$key]) && !empty($data['invasivo_fecha'][$key]) ? $data['invasivo_fecha'][$key] : null;
          $evoluciones_invasivos->lugar = !empty($data['invasivo_lugar'][$key]) ? $data['invasivo_lugar'][$key] : null;
          $evoluciones_invasivos->accion = !empty($data['invasivo_accion'][$key]) ? $data['invasivo_accion'][$key] : null;
          $evoluciones_invasivos->id_evolucion = $id_enfermeria_evoluciones;
          $evoluciones_invasivos->id_enfermeria = $request->id_enfermeria;
          $evoluciones_invasivos->save();
        }
      }

      if ($request->id_relacionado_DIAGNOSTICO_ENFERMERIA) {
        //DIAGNOSTICO_ENFERMERIA
        foreach ($request->id_relacionado_DIAGNOSTICO_ENFERMERIA as $key => $value) {
          $nota_enfermeria_evoluciones_detalleDE = new Nota_enfermeria_evoluciones_detalle();
          $nota_enfermeria_evoluciones_detalleDE->id_enfermeria_evoluciones = $id_enfermeria_evoluciones;
          $id_de_seleccion = $request->id_relacionado_DIAGNOSTICO_ENFERMERIA[$key];
          $valor_de_seleccion = $request->cantidad_detalle_DIAGNOSTICO_ENFERMERIA[$key];
          $tipo_de_seleccion = 'DIAGNOSTICO_ENFERMERIA';
          $nota_enfermeria_evoluciones_detalleDE->id_de_seleccion = $id_de_seleccion;
          $nota_enfermeria_evoluciones_detalleDE->valor_de_seleccion = $valor_de_seleccion;
          $nota_enfermeria_evoluciones_detalleDE->tipo_de_seleccion = $tipo_de_seleccion;
          $nota_enfermeria_evoluciones_detalleDE->save();
        }
      }

      if ($request->id_relacionado_PLAN_CUIDADO_ENFERMERIA) {
        //PLAN_CUIDADO_ENFERMERIA
        foreach ($request->id_relacionado_PLAN_CUIDADO_ENFERMERIA as $key => $value) {
          $nota_enfermeria_evoluciones_detallePE = new Nota_enfermeria_evoluciones_detalle();
          $nota_enfermeria_evoluciones_detallePE->id_enfermeria_evoluciones = $id_enfermeria_evoluciones;
          $id_de_seleccion = $request->id_relacionado_PLAN_CUIDADO_ENFERMERIA[$key];
          $valor_de_seleccion = $request->cantidad_detalle_PLAN_CUIDADO_ENFERMERIA[$key];
          $tipo_de_seleccion = 'PLAN_CUIDADO_ENFERMERIA';
          $nota_enfermeria_evoluciones_detallePE->id_de_seleccion = $id_de_seleccion;
          $nota_enfermeria_evoluciones_detallePE->valor_de_seleccion = $valor_de_seleccion;
          $nota_enfermeria_evoluciones_detallePE->tipo_de_seleccion = $tipo_de_seleccion;
          $nota_enfermeria_evoluciones_detallePE->save();
        }

        //EVALUACIONES_ENFERMERIA
        foreach ($request->id_relacionado_PLAN_CUIDADO_ENFERMERIA as $key => $value) {
          $nota_enfermeria_evoluciones_detalleIE = new Nota_enfermeria_evoluciones_detalle();
          $nota_enfermeria_evoluciones_detalleIE->id_enfermeria_evoluciones = $id_enfermeria_evoluciones;
          $id_de_seleccion = $request->id_relacionado_PLAN_CUIDADO_ENFERMERIA[$key];
          $valor_de_seleccion = $request->cantidad_detalle_EVALUACIONES_ENFERMERIA[$key];
          $tipo_de_seleccion = 'EVALUACIONES_ENFERMERIA';
          $nota_enfermeria_evoluciones_detalleIE->id_de_seleccion = $id_de_seleccion;
          $nota_enfermeria_evoluciones_detalleIE->valor_de_seleccion = $valor_de_seleccion;
          $nota_enfermeria_evoluciones_detalleIE->tipo_de_seleccion = $tipo_de_seleccion;
          $nota_enfermeria_evoluciones_detalleIE->save();
        }
      }

      if ($request->id_relacionado_INTERVENCIONES_ENFERMERIA) {
        //INTERVENCIONES_ENFERMERIA
        foreach ($request->id_relacionado_INTERVENCIONES_ENFERMERIA as $key => $value) {
          $nota_enfermeria_evoluciones_detalleIE = new Nota_enfermeria_evoluciones_detalle();
          $nota_enfermeria_evoluciones_detalleIE->id_enfermeria_evoluciones = $id_enfermeria_evoluciones;
          $id_de_seleccion = $request->id_relacionado_INTERVENCIONES_ENFERMERIA[$key];
          $valor_de_seleccion = $request->cantidad_detalle_INTERVENCIONES_ENFERMERIA[$key];
          $tipo_de_seleccion = 'INTERVENCIONES_ENFERMERIA';
          $nota_enfermeria_evoluciones_detalleIE->id_de_seleccion = $id_de_seleccion;
          $nota_enfermeria_evoluciones_detalleIE->valor_de_seleccion = $valor_de_seleccion;
          $nota_enfermeria_evoluciones_detalleIE->tipo_de_seleccion = $tipo_de_seleccion;
          $nota_enfermeria_evoluciones_detalleIE->save();
        }
      }

      if ($request->id_relacionado_CPMS_ENFERMERIA) {

        /* if ($request->id_prestacion_registro) {
          foreach ($request->id_relacionado_CPMS_ENFERMERIA as $key => $value) {
            $procedimiento_id = $request->id_relacionado_CPMS_ENFERMERIA[$key];
            $procedimiento_cantidad = $request->procedimiento_cantidad[$key];
            $procedimiento_diagnostico = $request->procedimiento_diagnosticos[$key];
            $procedimiento_tiempo = $request->procedimiento_tiempo[$key];
            $data = [
              'id_prestacion' => $request->id_prestacion_registro,
              'id_procedimiento' => $procedimiento_id,
              'id_estado_reg' => 1,
              'created_at' =>  Carbon::now(),
              'created_us' => Auth::user()->id,
              'cantidad' => $procedimiento_cantidad,
              'tiempo' => $procedimiento_tiempo,
              'enfermeria' => 1,
              'id_evolucion_enfermeria' => $id_enfermeria_evoluciones
            ];
            DB::table('prestacion_procedimientos')->insert($data);

            $data2 = [
              'id_prestacion' => $request->id_prestacion_registro,
              'id_procedimientos' => $procedimiento_id,
              'id_diagnosticos' => $procedimiento_diagnostico,
              'estado' => 1,
              'id_evolucion' => $id_enfermeria_evoluciones,
            ];
            DB::table('procedimientos_diagnoticos')->insert($data2);
          }
        } else {
          foreach ($request->id_relacionado_CPMS_ENFERMERIA as $key => $value) {
            $procedimiento_id = $request->id_relacionado_CPMS_ENFERMERIA[$key];
            $procedimiento_cantidad = $request->procedimiento_cantidad[$key];
            $procedimiento_diagnostico = $request->procedimiento_diagnosticos[$key];
            $data = [
              'id_procedimiento' => $procedimiento_id,
              'created_at' =>  Carbon::now(),
              'created_us' => Auth::user()->id,
              'cantidad' => $procedimiento_cantidad,
              'id_diagnostico' => $procedimiento_diagnostico,
              'id_evolucion_enfermeria' => $id_enfermeria_evoluciones
            ];
            DB::table('evolucion_procedimiento_diagnosticos')->insert($data);
          }
        } */
        $nuevoCpt = new Enfermeria_cpt;
        $nuevoCpt->id_usuario_registro = Auth::user()->id;
        $nuevoCpt->id_enfermeria = $request->id_enfermeria ? $request->id_enfermeria : null;
        $nuevoCpt->id_ext = $request->id_ext ? $request->id_ext : null;
        $nuevoCpt->id_tipo_atenciones = $request->id_tipo_atenciones_registro;
        $nuevoCpt->id_establecimiento = Auth::user()->id_establecimiento;
        $nuevoCpt->id_asegurado = $request->id_asegurado2;
        $nuevoCpt->id_turno = $request->turno;
        $nuevoCpt->estado = 1;
        $nuevoCpt->flag_firma = 0;
        $nuevoCpt->created_at = $request->fecha_registro_evolucion . ' ' . (preg_match('/^([01]\d|2[0-3]):[0-5]\d:[0-5]\d$/', $request->hora_registro_evolucion) ? $request->hora_registro_evolucion : $request->hora_registro_evolucion . ':00');
        $nuevoCpt->updated_at = $request->fecha_registro_evolucion . ' ' . (preg_match('/^([01]\d|2[0-3]):[0-5]\d:[0-5]\d$/', $request->hora_registro_evolucion) ? $request->hora_registro_evolucion : $request->hora_registro_evolucion . ':00');
        $nuevoCpt->save();
        $cptRegistrado_id = $nuevoCpt->id;
        foreach ($request->id_relacionado_CPMS_ENFERMERIA as $key => $value) {
          $procedimiento_id = $request->id_relacionado_CPMS_ENFERMERIA[$key];
          $procedimiento_cantidad = $request->procedimiento_cantidad[$key];
          $procedimiento_tiempo = $request->procedimiento_tiempo[$key];
          $procedimiento_diagnostico = $request->procedimiento_diagnosticos[$key];
          $data = [
            'id_prestacion' => null,
            'id_procedimiento' => $procedimiento_id,
            'id_diagnostico' => $procedimiento_diagnostico,
            'tiene_prestacion' => null,
            'created_at' =>  Carbon::now(),
            'created_us' => Auth::user()->id,
            'cantidad' => $procedimiento_cantidad,
            'tiempo' => $procedimiento_tiempo,
            'estado_reg' => 1,
            'id_nota_enfermeria_cpt' => $cptRegistrado_id
          ];
          DB::table('cpt_cpms')->insert($data);
        }
      }
      $valor_response = $id_enfermeria_evoluciones;
      DB::commit();
    } catch (\Exception $e) {
      Log::error('ERROR EN ENFERMERIA AL GUARDAR EVOLUCIONES: ' . $e->getMessage(), [
        'exception' => $e,
        'trace' => $e->getTraceAsString(),
      ]);

      DB::rollback();
    }

    return json_encode($valor_response);
  }

  public function actualizar_nota_ingreso_evoluciones(Request $request)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $valor_response = 0;
    //dd($request->all());
    $data = $request->all();
    $turnoSeleccionado = $request->turno2;
    $usuario_id = $request->id_asegurado;
    $inicioDelDia = $request->fecha_registro_evolucion_2 . ' 00:00:00'; // 2025-04-22 00:00:00
    $finDelDia = Carbon::today()->endOfDay(); // 2025-04-22 23:59:59
    /* dd($request->fecha_registro_evolucion_2); */
    /* dd($request->hora_registro_evolucion_2); */

    /* // Fecha actual del servidor con hora 00:00:00
    $hoyInicio = date('Y-m-d') . ' 00:00:00';
    // Si la fecha de inicio es mayor a hoy, invertir el orden en la consulta
    if (strtotime($inicioDelDia) > strtotime($hoyInicio)) {
      $temp = $inicioDelDia;
      $inicioDelDia = $finDelDia;
      $finDelDia = $temp;
    }
    dd($finDelDia); */
    /* $evaluacionesPreviasPorPacienteEnElDia = Nota_enfermeria_evoluciones::whereBetween('created_at', [$inicioDelDia, $finDelDia])
      ->where('id_asegurado', $usuario_id)
      ->where('id_turno', $turnoSeleccionado)
      ->where('id_tipo_atenciones', $request->id_tipo_atenciones_registro)
      ->where('estado', 1)
      ->where('id', '!=', $data['id_evoluciones'])
      ->get();
    if (($evaluacionesPreviasPorPacienteEnElDia->count() > 0)) {
      return 3;
    } */
    try {

      DB::beginTransaction();

      $objNota_enfermeria_evoluciones = Nota_enfermeria_evoluciones::find($data['id_evoluciones']);
      $objNota_enfermeria_evoluciones->id_usuario_registro = Auth::user()->id;
      $objNota_enfermeria_evoluciones->id_enfermeria = $request->id_enfermeria;
      $objNota_enfermeria_evoluciones->id_tipo_atenciones = $request->id_tipo_atenciones_evolucion_registro;
      //$objNota_enfermeria_evoluciones->id_nota_enfermeria = !empty($request->id_nota_enfermeria_evolucion_registro)?$request->id_nota_enfermeria_evolucion_registro: 0; 
      $objNota_enfermeria_evoluciones->descripcion_subjetivo = $request->descripcion_subjetivo;
      $objNota_enfermeria_evoluciones->descripcion_objetivo = $request->descripcion_objetivo;
      $objNota_enfermeria_evoluciones->descripcion_analisis = $request->descripcion_analisis;
      $objNota_enfermeria_evoluciones->descripcion_plan = $request->descripcion_plan;
      $objNota_enfermeria_evoluciones->descripcion_intervenciones = $request->descripcion_intervenciones;
      $objNota_enfermeria_evoluciones->descripcion_evaluaciones = $request->descripcion_evaluaciones;
      $objNota_enfermeria_evoluciones->id_establecimiento = Auth::user()->id_establecimiento;
      $objNota_enfermeria_evoluciones->id_asegurado = $request->id_asegurado;
      $objNota_enfermeria_evoluciones->id_prestacion = $request->id_prestacion_registro == '' ? null : $request->id_prestacion_registro;
      $objNota_enfermeria_evoluciones->id_turno = $request->turno2;
      $objNota_enfermeria_evoluciones->img_puntos_base = $request->img_puntos_base_update; //total de puntos del grafico
      $objNota_enfermeria_evoluciones->created_at = $request->fecha_registro_evolucion_2 . ' ' . (preg_match('/^([01]\d|2[0-3]):[0-5]\d:[0-5]\d$/', $request->hora_registro_evolucion_2) ? $request->hora_registro_evolucion_2 : $request->hora_registro_evolucion_2 . ':00');
      $objNota_enfermeria_evoluciones->updated_at = $request->fecha_registro_evolucion_2 . ' ' . (preg_match('/^([01]\d|2[0-3]):[0-5]\d:[0-5]\d$/', $request->hora_registro_evolucion_2) ? $request->hora_registro_evolucion_2 : $request->hora_registro_evolucion_2 . ':00');
      //$objNota_enfermeria_evoluciones->invasivos = json_encode($invasivos);//Invasivos: id,tipo,lugar y accion
      $objNota_enfermeria_evoluciones->save();

      $id_enfermeria_evoluciones = $objNota_enfermeria_evoluciones->id;
      //BORRAR TODO LOS DETALLES PARA LUEGO INSERTAR LOS ACTALIZADOS
      // if(Nota_enfermeria_evoluciones_detalle::where('id_enfermeria_evoluciones', $id_enfermeria_evoluciones)->exists()){
      //   Nota_enfermeria_evoluciones_detalle::where('id_enfermeria_evoluciones', $id_enfermeria_evoluciones)->delete();
      // }
      //FIN BORRAR TODO LOS DETALLES PARA LUEGO INSERTAR LOS ACTALIZADOS

      //INAVASIVOS

      //BORRAR TODO LOS DETALLES PARA LUEGO INSERTAR LOS ACTALIZADOS
      // if(Nota_enfermeria_evoluciones_invasivos::where('id_evolucion', $id_enfermeria_evoluciones)->exists()){
      //   Nota_enfermeria_evoluciones_invasivos::where('id_evolucion', $id_enfermeria_evoluciones)->delete();
      // }
      //FIN BORRAR TODO LOS DETALLES PARA LUEGO INSERTAR LOS ACTALIZADOS

      if (!empty($request->invasivos)) {
        foreach ($request->invasivos as $key => $value) {
          $evoluciones_invasivos = Nota_enfermeria_evoluciones_invasivos::where('id', $data['id_evolucion_invasivo'][$key])->first();
          if ($evoluciones_invasivos) {
            // Si existe, actualizar el registro
            $evoluciones_invasivos->tipo = $value;
            if (!empty($data['invasivo_fecha'][$key])) {
              $fecha = Carbon::createFromFormat('Y-m-d', $data['invasivo_fecha'][$key]);
              $evoluciones_invasivos->fecha = $fecha->format('Y-m-d');
            } else {
              $evoluciones_invasivos->fecha = null; // O asignar un valor predeterminado si es necesario
            }
            $evoluciones_invasivos->lugar = $data['invasivo_lugar'][$key];
            $evoluciones_invasivos->accion = $data['invasivo_accion'][$key];
            $evoluciones_invasivos->id_evolucion = $id_enfermeria_evoluciones;
            $evoluciones_invasivos->id_enfermeria = $request->id_enfermeria;
            $evoluciones_invasivos->save();
          }
        }
      }

      if ($request->id_relacionado_DIAGNOSTICO_ENFERMERIA_EDITAR) {
        //DIAGNOSTICO_ENFERMERIA
        foreach ($request->id_relacionado_DIAGNOSTICO_ENFERMERIA_EDITAR as $key => $value) {
          if (isset($data['id_detalle_d'][$key])) {
            $nota_enfermeria_evoluciones_detalleDE = Nota_enfermeria_evoluciones_detalle::where('id', $data['id_detalle_d'][$key])->first();
            if ($nota_enfermeria_evoluciones_detalleDE) {
              $nota_enfermeria_evoluciones_detalleDE->id_enfermeria_evoluciones = $id_enfermeria_evoluciones;
              $nota_enfermeria_evoluciones_detalleDE->id_de_seleccion = $request->id_relacionado_DIAGNOSTICO_ENFERMERIA_EDITAR[$key];
              $nota_enfermeria_evoluciones_detalleDE->valor_de_seleccion = $request->cantidad_detalle_DIAGNOSTICO_ENFERMERIA[$key];
              $nota_enfermeria_evoluciones_detalleDE->tipo_de_seleccion = 'DIAGNOSTICO_ENFERMERIA';
              $nota_enfermeria_evoluciones_detalleDE->save();
            }
          } else {
            $nota_enfermeria_evoluciones_detalleDE = new Nota_enfermeria_evoluciones_detalle;
            $nota_enfermeria_evoluciones_detalleDE->id_enfermeria_evoluciones = $id_enfermeria_evoluciones;
            $nota_enfermeria_evoluciones_detalleDE->id_de_seleccion = $request->id_relacionado_DIAGNOSTICO_ENFERMERIA_EDITAR[$key];
            $nota_enfermeria_evoluciones_detalleDE->valor_de_seleccion = $request->cantidad_detalle_DIAGNOSTICO_ENFERMERIA[$key];
            $nota_enfermeria_evoluciones_detalleDE->tipo_de_seleccion = 'DIAGNOSTICO_ENFERMERIA';
            $nota_enfermeria_evoluciones_detalleDE->save();
          }
        }
      }

      if ($request->id_relacionado_PLAN_CUIDADO_ENFERMERIA_EDITAR) {
        //PLAN_CUIDADO_ENFERMERIA
        foreach ($request->id_relacionado_PLAN_CUIDADO_ENFERMERIA_EDITAR as $key => $value) {
          if (isset($data['id_detalle_p'][$key])) {
            $nota_enfermeria_evoluciones_detalleDE = Nota_enfermeria_evoluciones_detalle::where('id', $data['id_detalle_p'][$key])->first();
            if ($nota_enfermeria_evoluciones_detalleDE) {
              $nota_enfermeria_evoluciones_detalleDE->id_enfermeria_evoluciones = $id_enfermeria_evoluciones;
              $nota_enfermeria_evoluciones_detalleDE->id_de_seleccion = $request->id_relacionado_PLAN_CUIDADO_ENFERMERIA_EDITAR[$key];
              $nota_enfermeria_evoluciones_detalleDE->valor_de_seleccion = $request->cantidad_detalle_PLAN_CUIDADO_ENFERMERIA[$key];
              $nota_enfermeria_evoluciones_detalleDE->tipo_de_seleccion = 'PLAN_CUIDADO_ENFERMERIA';
              $nota_enfermeria_evoluciones_detalleDE->save();
            }
          } else {
            $nota_enfermeria_evoluciones_detalleDE = new Nota_enfermeria_evoluciones_detalle;
            $nota_enfermeria_evoluciones_detalleDE->id_enfermeria_evoluciones = $id_enfermeria_evoluciones;
            $nota_enfermeria_evoluciones_detalleDE->id_de_seleccion = $request->id_relacionado_PLAN_CUIDADO_ENFERMERIA_EDITAR[$key];
            $nota_enfermeria_evoluciones_detalleDE->valor_de_seleccion = $request->cantidad_detalle_PLAN_CUIDADO_ENFERMERIA[$key];
            $nota_enfermeria_evoluciones_detalleDE->tipo_de_seleccion = 'PLAN_CUIDADO_ENFERMERIA';
            $nota_enfermeria_evoluciones_detalleDE->save();
          }
        }

        //EVALUACIONES_ENFERMERIA
        foreach ($request->id_relacionado_PLAN_CUIDADO_ENFERMERIA_EDITAR as $key => $value) {
          if (!empty($data['id_detalle_e'][$key])) {
            $nota_enfermeria_evoluciones_detalleDE = Nota_enfermeria_evoluciones_detalle::where('id', $data['id_detalle_e'][$key])->first();
            if ($nota_enfermeria_evoluciones_detalleDE) {
              $nota_enfermeria_evoluciones_detalleDE->id_enfermeria_evoluciones = $id_enfermeria_evoluciones;
              $nota_enfermeria_evoluciones_detalleDE->id_de_seleccion = $request->id_relacionado_PLAN_CUIDADO_ENFERMERIA_EDITAR[$key];
              $nota_enfermeria_evoluciones_detalleDE->valor_de_seleccion = $request->cantidad_detalle_EVALUACIONES_ENFERMERIA[$key];
              $nota_enfermeria_evoluciones_detalleDE->tipo_de_seleccion = 'EVALUACIONES_ENFERMERIA';
              $nota_enfermeria_evoluciones_detalleDE->save();
            }
          } else {
            $nota_enfermeria_evoluciones_detalleDE = new Nota_enfermeria_evoluciones_detalle;
            $nota_enfermeria_evoluciones_detalleDE->id_enfermeria_evoluciones = $id_enfermeria_evoluciones;
            $nota_enfermeria_evoluciones_detalleDE->id_de_seleccion = $request->id_relacionado_PLAN_CUIDADO_ENFERMERIA_EDITAR[$key];
            $nota_enfermeria_evoluciones_detalleDE->valor_de_seleccion = $request->cantidad_detalle_EVALUACIONES_ENFERMERIA[$key];
            $nota_enfermeria_evoluciones_detalleDE->tipo_de_seleccion = 'EVALUACIONES_ENFERMERIA';
            $nota_enfermeria_evoluciones_detalleDE->save();
          }
        }
      }

      if ($request->id_relacionado_INTERVENCIONES_ENFERMERIA_EDITAR) {
        //INTERVENCIONES_ENFERMERIA
        foreach ($request->id_relacionado_INTERVENCIONES_ENFERMERIA_EDITAR as $key => $value) {
          if (isset($data['id_detalle_i'][$key])) {
            $nota_enfermeria_evoluciones_detalleDE = Nota_enfermeria_evoluciones_detalle::where('id', $data['id_detalle_i'][$key])->first();
            if ($nota_enfermeria_evoluciones_detalleDE) {
              $nota_enfermeria_evoluciones_detalleDE->id_enfermeria_evoluciones = $id_enfermeria_evoluciones;
              $nota_enfermeria_evoluciones_detalleDE->id_de_seleccion = $request->id_relacionado_INTERVENCIONES_ENFERMERIA_EDITAR[$key];
              $nota_enfermeria_evoluciones_detalleDE->valor_de_seleccion = $request->cantidad_detalle_INTERVENCIONES_ENFERMERIA[$key];
              $nota_enfermeria_evoluciones_detalleDE->tipo_de_seleccion = 'INTERVENCIONES_ENFERMERIA';
              $nota_enfermeria_evoluciones_detalleDE->save();
            }
          } else {
            $nota_enfermeria_evoluciones_detalleDE = new Nota_enfermeria_evoluciones_detalle;
            $nota_enfermeria_evoluciones_detalleDE->id_enfermeria_evoluciones = $id_enfermeria_evoluciones;
            $nota_enfermeria_evoluciones_detalleDE->id_de_seleccion = $request->id_relacionado_INTERVENCIONES_ENFERMERIA_EDITAR[$key];
            $nota_enfermeria_evoluciones_detalleDE->valor_de_seleccion = $request->cantidad_detalle_INTERVENCIONES_ENFERMERIA[$key];
            $nota_enfermeria_evoluciones_detalleDE->tipo_de_seleccion = 'INTERVENCIONES_ENFERMERIA';
            $nota_enfermeria_evoluciones_detalleDE->save();
          }
        }
      }

      // DB::table('prestacion_procedimientos')->where('id_prestacion',$request->id_prestacion_registro)->where('enfermeria',1)->delete();
      // DB::table('procedimientos_diagnoticos')->where('id_prestacion',$request->id_prestacion_registro)->where('id_evolucion',$data['id_evoluciones'])->delete();

      if ($request->id_relacionado_CPMS_ENFERMERIA) {
        foreach ($request->id_relacionado_CPMS_ENFERMERIA as $key => $value) {
          $procedimiento_id = $request->id_relacionado_CPMS_ENFERMERIA[$key];
          $procedimiento_cantidad = $request->procedimiento_cantidad_editar[$key];
          $procedimiento_tiempo = $request->procedimiento_tiempo[$key];
          $procedimiento_diagnostico = $request->procedimiento_diagnosticos[$key];
          $cpms = DB::table('prestacion_procedimientos')
            ->where('id_procedimiento', $procedimiento_id)
            ->where('id_evolucion_enfermeria', $id_enfermeria_evoluciones);
          if ($cpms->exists()) {
            $data = [
              'updated_at' => Carbon::now(),
              'updatet_us' => Auth::user()->id,
              'cantidad' => $procedimiento_cantidad,
              'tiempo' => $procedimiento_tiempo,
              'id_diagnostico' => $procedimiento_diagnostico,
            ];
            $cpms->update($data);
          } else {
            $data = [
              'id_prestacion' => $request->id_prestacion_registro,
              'id_procedimiento' => $procedimiento_id,
              'id_diagnostico' => $procedimiento_diagnostico,
              'id_estado_reg' => 1,
              'created_at' =>  Carbon::now(),
              'created_us' => Auth::user()->id,
              'cantidad' => $procedimiento_cantidad,
              'tiempo' => $procedimiento_tiempo,
              'enfermeria' => 1,
              'id_evolucion_enfermeria' => $id_enfermeria_evoluciones
            ];
            DB::table('prestacion_procedimientos')->insert($data);
          }

          $query2 = DB::table('procedimientos_diagnoticos')
            ->where('id_procedimientos', $procedimiento_id)
            ->where('id_evolucion', $id_enfermeria_evoluciones);

          if (!$query2->exists()) {
            $data2 = [
              'id_prestacion' => $request->id_prestacion_registro,
              'id_procedimientos' => $procedimiento_id,
              'id_diagnosticos' => $procedimiento_diagnostico,
              'estado' => 1,
              'id_evolucion' => $id_enfermeria_evoluciones,
            ];
            DB::table('procedimientos_diagnoticos')->insert($data2);
          }
        }
      }
      $valor_response = $id_enfermeria_evoluciones;
      DB::commit();
    } catch (\Exception $e) {
      Log::error('ERROR EN ENFERMERIA AL GUARDAR EVOLUCIONES: ' . $e->getMessage(), [
        'exception' => $e,
        'trace' => $e->getTraceAsString(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
      ]);
      DB::rollback();
    }

    return json_encode($valor_response);
  }


  // DESDE ACA EMPIEZA LA SAECCION DE ENFERMERIA PARA EL MODULO PRIMERO EVOLUCIONES
  public function lista_nota_ingreso_evoluciones(Request $request)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $objNota_enfermeria_evoluciones = new Nota_enfermeria_evoluciones;
    $p[] = $request->fecha_evolucion_bus_inicial_listado;  //0
    $p[] = $request->fecha_evolucion_bus_final_listado;  //1 
    $p[] = $request->id_turno;  //2 
    $p[] = $request->id_tipo_atencion;  //3 - ID TIPO ATENCION
    $p[] = $request->id_asegurado;
    $turnos = $this->turnos();
    $tipoAtenciones = $this->tipoAtenciones();
    $estadoAcomp = $this->getEstadoAcomp($request->id_enfermeria);
    $datosPacienteIdAsegurado = $this->getDatosPaciente($request->id_asegurado);
    $estadoAlergia = $this->getEstadoAlergia($request->id_enfermeria);
    $data['evoluciones'] = $objNota_enfermeria_evoluciones->getlista_nota_ingreso_evoluciones($p);
    $data['turnos'] = $turnos;
    $data['tipo_atenciones'] = $tipoAtenciones;
    $data['datos_paciente'] =  $datosPacienteIdAsegurado;
    $data['estado_alergia'] =  $estadoAlergia;
    $data['estado_acomp'] =  $estadoAcomp;
    return json_encode($data);
  }

  public function ver_nota_ingreso_evoluciones(Request $request)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $objNota_enfermeria_evoluciones = new Nota_enfermeria_evoluciones;
    $model_cpt = new Enfermeria_cpt;
    /***************DATOS GLOBALES*******/
    $diagnosticos_pacientes_prestacion = $request->id_prestacion != '' ? $this->diagnosticos_pacientes_prestacion($request->id_prestacion) : $this->diagnosticos_pacientes_hospi($request->id_enfermeria);
    $turnos = $this->turnos();
    //$invasivos = $this->invasivos();
    $tipoAtenciones = $this->tipoAtenciones();
    $datosPacienteIdAsegurado = $this->getDatosPaciente($request->id_asegurado);
    //$data['invasivos_por_evolucion']=$listaInvasivosIdEvolucion;//LISTAD DE INVASIVOS POR EVOLUCION

    $currentPage = $request->input('page', 1); // Página actual (por defecto 1)
    $data['invasivos_por_atencion'] =  $this->listaInvasivosIdAtencion($request->id_enfermeria, $currentPage);
    $data['diagnosticos_por_prestacion'] = $diagnosticos_pacientes_prestacion;
    $data['turnos'] = $turnos;
    //$data['invasivos']= $invasivos;
    $data['tipo_atenciones'] = $tipoAtenciones;
    $data['datos_paciente'] =  $datosPacienteIdAsegurado;
    $data['fechas_ingreso_egreso'] = $this->fechas_ingreso_egreso($request->id_ext, $request->id_tipo_atencion);
    /***************DATOS GLOBALES*******/

    $data['nota_enfermeria'] = $objNota_enfermeria_evoluciones->getver_nota_ingreso_evoluciones($request->id_evoluciones);

    $tipo_de_seleccion_diagnostico = 'DIAGNOSTICO_ENFERMERIA';
    $data['nota_enfermeria_diagnostico'] = $objNota_enfermeria_evoluciones->getver_nota_ingreso_evoluciones_detalle_diagnostico($request->id_evoluciones, $tipo_de_seleccion_diagnostico);

    $tipo_de_seleccion_plan_cuidado = 'PLAN_CUIDADO_ENFERMERIA';
    $data['nota_enfermeria_plan_cuidado'] = $objNota_enfermeria_evoluciones->getver_nota_ingreso_evoluciones_detalle_diagnostico($request->id_evoluciones, $tipo_de_seleccion_plan_cuidado);

    $tipo_de_seleccion_intervenciones = 'INTERVENCIONES_ENFERMERIA';
    $data['nota_enfermeria_intervenciones'] = $objNota_enfermeria_evoluciones->getver_nota_ingreso_evoluciones_detalle_diagnostico($request->id_evoluciones, $tipo_de_seleccion_intervenciones);

    $tipo_de_seleccion_evaluaciones = 'EVALUACIONES_ENFERMERIA';
    $data['nota_enfermeria_evaluaciones'] = $objNota_enfermeria_evoluciones->getver_nota_ingreso_evoluciones_detalle_diagnostico($request->id_evoluciones, $tipo_de_seleccion_evaluaciones);

    $data['nota_enfermeria_cpms'] = $objNota_enfermeria_evoluciones->getver_nota_ingreso_evoluciones_procedimientos($request->id_evoluciones);
    $cptRegistradoEmergencia = Enfermeria_cpt::where("id_enfermeria", $request->id_enfermeria)
      ->where("id_tipo_atenciones", $request->id_tipo_atencion)
      ->first();
    if ($cptRegistradoEmergencia) {
      $data['nota_cpt'] = $model_cpt->getver_nota_ingreso_cpt($cptRegistradoEmergencia->id);
      $data['cpms'] = $model_cpt->getver_nota_ingreso_cpt_cpms($cptRegistradoEmergencia->id);
    } else {
      $data['nota_cpt'] = null;
      $data['cpms'] = null;
    }

    $data['invasivos_por_evolucion'] = $objNota_enfermeria_evoluciones->getver_nota_ingreso_evoluciones_invasivos($request->id_evoluciones);

    $data['fecha_registro_admision'] = $this->fecha_registro_admision($request->id_enfermeria);

    $data['estado_alergia'] =  $this->getEstadoAlergia($request->id_enfermeria);
    $data['estado_acomp'] =  $this->getEstadoAcomp($request->id_enfermeria);

    return response()->json($data);
  }
  public function cargarMasHistorialInvasivos(Request $request)
  {
    $currentPage = $request->input('page', 1); // Página actual (por defecto 1)

    $invasivos = DB::table('invasivos')
      ->select('invasivos.id', 'invasivos.descripcion_corta as tipo', DB::raw("TO_CHAR(nota_enfermeria_evoluciones_invasivos.fecha, 'DD/MM/YYYY') as fecha"), 'nota_enfermeria_evoluciones_invasivos.lugar', DB::raw("
            CASE 
                WHEN nota_enfermeria_evoluciones_invasivos.accion = 0 THEN ''
                WHEN nota_enfermeria_evoluciones_invasivos.accion = 1 THEN 'Colocacion'
                WHEN nota_enfermeria_evoluciones_invasivos.accion = 2 THEN 'Cambio'
                WHEN nota_enfermeria_evoluciones_invasivos.accion = 3 THEN 'Retiro'
                ELSE 'Desconocido'
            END as accion
        "))
      ->leftJoin('nota_enfermeria_evoluciones_invasivos', 'invasivos.id', '=', 'nota_enfermeria_evoluciones_invasivos.tipo')
      ->where('nota_enfermeria_evoluciones_invasivos.id_enfermeria', $request->id_enfermeria)
      ->paginate(5, ['*'], 'page', $currentPage);

    $data['invasivos_por_atencion'] = [
      'items' => $invasivos->items(),
      'current_page' => $invasivos->currentPage(),
      'total_pages' => $invasivos->lastPage(),
      'nextPageUrl' => $invasivos->nextPageUrl()
    ];
    return response()->json($data);
  }

  public function datosGlobales(Request $request)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $currentPage = $request->input('page', 1);
    $data['invasivos'] = $this->invasivos();
    $data['invasivos_por_atencion'] = $this->listaInvasivosIdAtencion($request->id_enfermeria, $currentPage);
    $data['turnos'] = $this->turnos();
    $data['datos_paciente'] =  $this->getDatosPaciente($request->id_asegurado);
    $data['estado_alergia'] =  $this->getEstadoAlergia($request->id_enfermeria);
    /* $data['diagnosticos_por_prestacion']= $request->id_prestacion != '' ? $this->diagnosticos_pacientes_prestacion($request->id_prestacion) : []; */
    /* $data['diagnosticos_por_prestacion']= $this->diagnosticos_pacientes_prestacion($request->id_prestacion); */
    $data['diagnosticos'] = $this->buscarDiagnosticosEmergencia($request->id_enfermeria);
    $data['fecha_registro_admision'] = $this->fecha_registro_admision($request->id_enfermeria);
    $data['fechas_ingreso_egreso'] = $this->fechas_ingreso_egreso($request->id_ext, $request->id_tipo_atencion);
    $data['estado_acomp'] = $this->getEstadoAcomp($request->id_enfermeria);
    return json_encode($data);
  }

  public function getEstadoAcomp($id_enfermeria)
  {
    $data =  DB::select("select 
    numero_acompanante as celular_acomp
    FROM valoraciones_enfermerias ve
    WHERE ve.id_enfermeria = :id_enfermeria
    ", ['id_enfermeria' => $id_enfermeria]);
    return $data[0];
  }

  public function datosGlobalesHospi(Request $request)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $currentPage = $request->input('page', 1);
    $data['invasivos'] = $this->invasivos();
    $data['invasivos_por_atencion'] = $this->listaInvasivosIdAtencion($request->id_enfermeria, $currentPage);
    $data['turnos'] = $this->turnosHospiEvolV2(Auth::user()->id_establecimiento);
    $data['establecimiento_id'] = Auth::user()->id_establecimiento;
    $data['datos_paciente'] =  $this->getDatosPaciente($request->id_asegurado);
    $data['estado_alergia'] =  $this->getEstadoAlergia($request->id_enfermeria);
    /* $data['diagnosticos_por_prestacion']= $this->diagnosticos_pacientes_hospi($request->id_enfermeria); */
    $data['diagnosticos'] = $this->buscarDiagnosticosDeHospitalizacion($request->id_enfermeria);
    $data['fecha_registro_admision'] = $this->fecha_registro_admision($request->id_enfermeria);
    $data['fechas_ingreso_egreso'] = $this->fechas_ingreso_egreso($request->id_ext, $request->id_tipo_atencion);
    return json_encode($data);
  }

  public function pdf_nota_ingreso_evoluciones($id_evoluciones)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $date = Carbon::now();
    $fechaActual = $date->format('d/m/Y');
    $horaActual = $date->format('H:i:s');

    //$nota_ingreso = new \App\Models\Enfermeria; 
    $model_receta = new \App\Models\Receta_vale;
    $model_receta_diagnostico = new \App\Models\Receta_diagnostico;
    //--- ESTABLECIMIENTO
    $id_establecimiento = Auth::user()->id_establecimiento; // id_establecimiento = 76 - 1 mdico  
    $model_establecimiento = new \App\Models\Establecimiento();
    $establecimiento =  $model_establecimiento->GetById($id_establecimiento);
    $nombre_establecimiento = $establecimiento[0]->nombre;

    /***************************EVOLUCIONES CABECERA********************************/
    $evolucionesModelo = new Nota_enfermeria_evoluciones;
    $evoluciones_cabecera = $evolucionesModelo->getver_nota_ingreso_evoluciones($id_evoluciones);
    /***************************FIN EVOLUCIONES DETALLES********************************/

    /***************************EVOLUCIONES DETALLES********************************/
    $tipo_de_seleccion_diagnostico = 'DIAGNOSTICO_ENFERMERIA';
    $evoluciones_cabecera_diagnostico = $evolucionesModelo->getver_nota_ingreso_evoluciones_detalle_diagnostico($id_evoluciones, $tipo_de_seleccion_diagnostico);

    $tipo_de_seleccion_plan_cuidado = 'PLAN_CUIDADO_ENFERMERIA';
    $evoluciones_cabecera_plan_cuidado = $evolucionesModelo->getver_nota_ingreso_evoluciones_detalle_diagnostico($id_evoluciones, $tipo_de_seleccion_plan_cuidado);

    $tipo_de_seleccion_intervenciones = 'INTERVENCIONES_ENFERMERIA';
    $evoluciones_cabecera_intervenciones = $evolucionesModelo->getver_nota_ingreso_evoluciones_detalle_diagnostico($id_evoluciones, $tipo_de_seleccion_intervenciones);

    $tipo_de_seleccion_evaluaciones = 'EVALUACIONES_ENFERMERIA';
    $evoluciones_cabecera_evaluaciones = $evolucionesModelo->getver_nota_ingreso_evoluciones_detalle_diagnostico($id_evoluciones, $tipo_de_seleccion_evaluaciones);

    /* $evoluciones_cabecera_cpms = $evolucionesModelo->getver_nota_ingreso_evoluciones_procedimientos($id_evoluciones);  */

    $evoluciones_cabecera_invasivos = $evolucionesModelo->getver_nota_ingreso_evoluciones_invasivos($id_evoluciones);
    /***************************FIN EVOLUCIONES DETALLES********************************/

    /***************************DATOS DE LA NOTA DE ENFERMERIA********************************/
    $id_tipo_atencion = $evoluciones_cabecera[0]->id_tipo_atencion; //TIPO ATENCION
    $id_ext_enfermeria = $evoluciones_cabecera[0]->id_ext_enfermeria; //ID DE EMERGENCIA O HOSPITALIZACION
    $codigo_cups = '';
    if ($id_tipo_atencion == 2) {
      $datos_atencion = Emergencia::from('emergencias as e1')
        ->leftJoin('camas as c1', 'c1.id', '=', 'e1.cama_estado')
        ->leftJoin('cuartos as c2', 'c2.id', '=', 'c1.id_cuarto')
        ->leftJoin('triajes as t', 't.id', '=', 'e1.id_triaje')
        ->leftJoin('prioridades as p2', 'p2.id', '=', 'e1.id_prioridad')
        ->select(
          't.descripcion as area',
          'p2.descripcion as prioridad',
          DB::raw("COALESCE(c2.nombre, 'SIN ASIGNAR') AS nombre_cuarto"),
          DB::raw("COALESCE(c1.nombre, 'SIN ASIGNAR') AS nombre_cama"),
          'e1.id as id_emergencia'
        )->where('e1.id', $id_ext_enfermeria)
        ->first();
      $codigo_cups = Emergencia::select('emergencias.cups_pnp as codigo_cups')->where('emergencias.id', $datos_atencion->id_emergencia)->first();
      $codigo_cups = $codigo_cups->codigo_cups;
    } else if ($id_tipo_atencion == 3) {
      $datos_atencion =  DB::selectOne("
                  select COALESCE(c2.nombre, 'SIN ASIGNAR') AS nombre_cuarto,
                  COALESCE(c1.nombre, 'SIN ASIGNAR') AS nombre_cama,
                  h.id as id_hospitalizacion
                  from hospitalizaciones h 
                  left join camas c1 on h.id_cama=c1.id 
                  left join cuartos c2 on h.id_cuarto = c2.id
                          WHERE h.id = :id_hospitalizacion
                  ", ['id_hospitalizacion' => $id_ext_enfermeria]);

      $codigo_cups = Hospitalizacione::select('hospitalizaciones.cups_pnp as codigo_cups')->where('hospitalizaciones.id', $datos_atencion->id_hospitalizacion)->first();
      $codigo_cups = $codigo_cups->codigo_cups;
    }

    /***************************FIN EVOLUCIONES DETALLES********************************/
    $datos_paciente = $this->getDatosPaciente($evoluciones_cabecera[0]->id_asegurado);
    //dd($datos_paciente);
    $pdf = \PDF::loadView(
      'frontend.emergencia_enfermeria.evoluciones.pdf_evoluciones.pdf_nota_ingreso_evoluciones',
      compact(
        'id_tipo_atencion',
        'datos_paciente',
        'evoluciones_cabecera',
        'evoluciones_cabecera_diagnostico',
        'evoluciones_cabecera_plan_cuidado',
        'evoluciones_cabecera_intervenciones',
        'evoluciones_cabecera_evaluaciones',
        'evoluciones_cabecera_cpms',
        'evoluciones_cabecera_invasivos',
        'datos_atencion',
        'nombre_establecimiento',
        'fechaActual',
        'horaActual',
        'codigo_cups'
      )
    );
    $pdf->getDomPDF()->set_option("enable_php", true);
    //$pdf->setPaper('A5', 'portrait');
    $pdf->setPaper('A4', 'portrait'); //Horizontal
    return $pdf->stream('nota_ingreso_evoluciones.pdf');
  }
  public function tipoAtenciones()
  {
    $model_atencione = new TipoAtencione;
    $atencione = $model_atencione->ShowTipoAtencionAll();
    return $atencione;
  }
  public function diagnosticos_pacientes_prestacion($id_prestacion)
  {
    $model = new Nota_enfermeria_evoluciones;
    $diagnosticos = $model->diagnosticos_pacientes_prestacion($id_prestacion);
    return $diagnosticos;
  }

  public function diagnosticos_pacientes_hospi($id_enfermeria)
  {
    $enfermeria = Enfermeria::where("id", "=", $id_enfermeria)->first();
    $model = new Nota_enfermeria_evoluciones();
    if ($enfermeria->id_prestacion) {
      $diagnosticos = $model->diagnosticos_pacientes_prestacion($enfermeria->id_prestacion);
    } else if ($enfermeria->id_tipo_atencion == 3) {
      $diagnosticos = $model->diagnosticos_hospi_admision($enfermeria->id_ext_enfermeria);
    } else {
      $diagnosticos = [];
    }
    return $diagnosticos;
  }

  // Metodo encargado de buscar diagnosticos de admision y tambien atencion, de hospi:
  public function buscarDiagnosticosDeHospitalizacion($id_enfermeria)
  {
    $enfermeria = Enfermeria::where("id", "=", $id_enfermeria)->first();
    $model = new Nota_enfermeria_evoluciones();
    $diagnosticos = [
      'diagnosticos_atencion' => $enfermeria->id_prestacion ? $model->diagnosticos_pacientes_prestacion($enfermeria->id_prestacion) : $this->buscarDiagnosticosDePrestacion($enfermeria->id_ext_enfermeria, 3),
      'diagnosticos_admision' => $enfermeria->id_ext_enfermeria ? $model->diagnosticos_hospi_admision($enfermeria->id_ext_enfermeria) : [],
    ];
    return $diagnosticos;
  }
  public function buscarDiagnosticosEmergencia($id_enfermeria)
  {
    $enfermeria = Enfermeria::where("id", "=", $id_enfermeria)->first();
    $model = new Nota_enfermeria_evoluciones();
    $diagnosticos = [
      'diagnosticos_atencion' => $enfermeria->id_prestacion ? $model->diagnosticos_pacientes_prestacion($enfermeria->id_prestacion) : $this->buscarDiagnosticosDePrestacion($enfermeria->id_ext_enfermeria, 2),
    ];
    return $diagnosticos;
  }

  public function buscarDiagnosticosDePrestacion($id_ext, $id_tipo_atencion)
  {
    $prestacion = Prestaciones::where("id_ext", $id_ext)->where("id_tipo_atencion", $id_tipo_atencion)->latest('id') // o el campo que identifique el orden cronológico
      ->first();
    $model = new Nota_enfermeria_evoluciones();
    return $prestacion ? $model->diagnosticos_pacientes_prestacion($prestacion->id) : [];
  }

  public function diagnosticos_pacientes_prestacion_combo($id_prestacion)
  {
    $model = new Nota_enfermeria_evoluciones;
    $diagnosticos = $model->diagnosticos_pacientes_prestacion($id_prestacion);
    return json_encode($diagnosticos);
  }
  public function turnos()
  {
    $model_turno = new Turno();
    $turno = $model_turno->ShowAll();
    return $turno;
  }
  public function turnosHospiEvol()
  {
    $model_turno = new Turno();
    $turno = $model_turno->ShowAllHospiEvol();
    return $turno;
  }
  
  public function turnosHospiEvolV2($id_establecimiento)
  {
    $model_turno = new Turno();
    if ($id_establecimiento == 76) {
      $turno = $model_turno->ShowAllHospiEvol();
    } else {
      $turno = $model_turno->ShowAll();
    }
    return $turno;
  }

  public function agregarPuntos(Request $request)
  {
    $punto = new Punto();
    $punto->x = $request->x;
    $punto->y = $request->y;
    $punto->color = $request->color;
    if ($request->tamanio_proceso == 10) {
      $punto->x = ($request->x - 3);
      $punto->y = ($request->y - 3);
    } elseif ($request->tamanio_proceso == 40) {
      $punto->x = ($request->x - 18);
      $punto->y = ($request->y - 18);
    } elseif ($request->tamanio_proceso == 60) {
      $punto->x = ($request->x - 30);
      $punto->y = ($request->y - 30);
    }
    $punto->tama = $request->tamanio_proceso;
    $punto->id_usuario =  Auth::user()->id;
    $punto->id_enfermeria = $request->id_enfermeria;
    $punto->id_tipo_atenciones = $request->id_tipo_atenciones;
    $punto->descripcion_proceso = $request->descripcion_proceso;
    $punto->tipo_registro = $request->tipo_registro;

    $punto->save();
    return json_encode(1);
  }
  //PUNTOS EN EVOLUCION
  public function pintarPuntosEvolucion($id_enfermeria, $id_tipo_atenciones)
  {
    $punto = new Nota_enfermeria_evoluciones;
    $puntos = $punto->pintarPuntosEvolucion($id_enfermeria, $id_tipo_atenciones);
    return json_encode($puntos);
  }

  public function destroyPuntos($punto_id)
  {
    $punto = new Punto;
    $punto = $punto::find($punto_id);
    $punto->delete();
    return json_encode(1);
  }
  public function invasivos()
  { //TABLA MASTER DE INVASIVOS PARA EL REGISTRO NUEVO
    $invasivos = new Invasivo();
    $data = $invasivos::select('id', 'descripcion_corta', 'descripcion')->get();
    return $data;
    //return json_encode($data);
  }
  public function motivoAnulacionEvolucion(Request $request)
  {
    $evolucion = Nota_enfermeria_evoluciones::find($request->id_evoluciones);
    if ($evolucion) {
      $evolucion->estado = 0;
      $evolucion->id_usuario_anulado = Auth::user()->id;
      $evolucion->motivo_anulacion = $request->anulacionMotivo;
      $evolucion->fecha_anulacion = Carbon::now();
      $evolucion->save();
    } else {
      return response()->json(['mensaje' => 'evolucion no encontrada'], 404);
    }
    return json_encode(1);
  }

  public function listaInvasivosIdAtencion($id_enfermeria, $currentPage)
  {
    $invasivos = DB::table('invasivos')
      ->select(
        'invasivos.id',
        'invasivos.descripcion_corta as tipo',
        DB::raw("TO_CHAR(nota_enfermeria_evoluciones_invasivos.fecha, 'DD/MM/YYYY') as fecha"),
        'nota_enfermeria_evoluciones_invasivos.lugar',
        DB::raw("
              CASE 
                  WHEN nota_enfermeria_evoluciones_invasivos.accion = 0 THEN ''
                  WHEN nota_enfermeria_evoluciones_invasivos.accion = 1 THEN 'Colocacion'
                  WHEN nota_enfermeria_evoluciones_invasivos.accion = 2 THEN 'Cambio'
                  WHEN nota_enfermeria_evoluciones_invasivos.accion = 3 THEN 'Retiro'
                  ELSE 'Desconocido'
              END as accion
          ")
      )
      ->leftJoin('nota_enfermeria_evoluciones_invasivos', 'invasivos.id', '=', 'nota_enfermeria_evoluciones_invasivos.tipo')
      ->where('nota_enfermeria_evoluciones_invasivos.id_enfermeria', $id_enfermeria)
      ->paginate(5, ['*'], 'page', $currentPage);
    return [
      'items' => $invasivos->items(),
      'current_page' => $invasivos->currentPage(),
      'total_pages' => $invasivos->lastPage(),
      'nextPageUrl' => $invasivos->nextPageUrl()
    ];
    // //$invasivos = new Nota_enfermeria_evoluciones_invasivos();

    // $data =  DB::select("
    // select inv.id,inv.descripcion_corta as tipo,COALESCE(TO_CHAR(inv2.fecha, 'YYYY-MM-DD'), '') AS fecha,inv2.lugar,CASE
    // WHEN accion = 1 THEN 'Colocación'
    // WHEN accion = 2 then 'Cambio'
    // WHEN accion = 3 THEN 'Retiro' ELSE '' END AS accion  
    // from invasivos as inv 
    // left join nota_enfermeria_evoluciones_invasivos as inv2 on inv.id =inv2.tipo
    //   WHERE inv2.id_enfermeria = :id_enfermeria and estado=1
    // ", ['id_enfermeria' => $id_enfermeria]);
    // return $data;
    //return json_encode($data);
  }
  public function listaInvasivosIdEvolucion($id_evolucion)
  {
    $invasivos = new Nota_enfermeria_evoluciones_invasivos();
    $data = $invasivos::select('id', 'fecha', 'tipo', 'lugar')
      ->selectRaw("CASE
            WHEN accion = 1 THEN 'Colocación'
            WHEN accion = 2 THEN 'Cambio'
            WHEN accion = 3 THEN 'Retiro'
            ELSE 'NINGUNO' 
          END AS accion")
      ->where('id_evolucion', $id_evolucion)->get();
    return $data;
    //return json_encode($data);
  }
  public function getDatosPaciente($id_asegurado)
  {
    $data =  DB::select("select 
    asegurados.fecha_nac  as fecha_nacimiento,  
    ts.descripcion_corta as tipo_sangre,
    tb.descripcion as parentesgo,
    to_char(age(TO_DATE(asegurados.fecha_nac , 'DD/MM/YYYY')), 'YY \"A\"')  as edad_corta,
    to_char(age(TO_DATE(asegurados.fecha_nac , 'DD/MM/YYYY')), 'YY \"años\" \"con\" MM \"meses\"  \"y\" DD \"dias\"') 
    as fec_nacim_descrip,
    LTRIM(asegurado_historias.nro_historia) as nro_historia, asegurados.nro_doc_ident,
    asegurados.paterno,asegurados.materno,asegurados.nombre,asegurados.sexo,
    CASE
        WHEN EXISTS (
            SELECT 1
            FROM asegurados_alergias aa
            WHERE aa.id_asegurado = asegurados.id
              AND aa.estado = TRUE
        )
        THEN 1
        ELSE 0
    END AS tiene_alergias
    FROM asegurados INNER JOIN asegurado_historias ON asegurado_historias.id_asegurado = asegurados.id
    left join tipo_beneficiarios tb on tb.id =asegurados.id_tipo_beneficiario
    left join tipo_sangres ts on asegurados.id_tipo_sangre = ts.id
    WHERE asegurados.id_estado_reg=1 and asegurados.id = :id_asegurado
    ", ['id_asegurado' => $id_asegurado]);
    return $data[0];
  }

  public function getDatosPacienteAjax($id_asegurado)
  {
    $data =  DB::select("select 
    asegurados.fecha_nac  as fecha_nacimiento,  
    ts.descripcion_corta as tipo_sangre,
    tb.descripcion as parentesgo,
    to_char(age(TO_DATE(asegurados.fecha_nac , 'DD/MM/YYYY')), 'YY \"A\"')  as edad_corta,
    to_char(age(TO_DATE(asegurados.fecha_nac , 'DD/MM/YYYY')), 'YY \"años\" \"con\" MM \"meses\"  \"y\" DD \"dias\"') 
    as fec_nacim_descrip,
    LTRIM(asegurado_historias.nro_historia) as nro_historia, asegurados.nro_doc_ident,
    asegurados.paterno,asegurados.materno,asegurados.nombre,asegurados.sexo,
    CASE
        WHEN EXISTS (
            SELECT 1
            FROM asegurados_alergias aa
            WHERE aa.id_asegurado = asegurados.id
              AND aa.estado = TRUE
        )
        THEN 1
        ELSE 0
    END AS tiene_alergias
    FROM asegurados INNER JOIN asegurado_historias ON asegurado_historias.id_asegurado = asegurados.id
    left join tipo_beneficiarios tb on tb.id =asegurados.id_tipo_beneficiario
    left join tipo_sangres ts on asegurados.id_tipo_sangre = ts.id
    WHERE asegurados.id_estado_reg=1 and asegurados.id = :id_asegurado
    ", ['id_asegurado' => $id_asegurado]);
    return response()->json($data[0]);
  }

  public function getEstadoAlergia($id_enfermeria)
  {
    $data =  DB::select("select 
    ae.id_alergia as flag_alergia
    FROM antecedentes_enfermerias ae
    WHERE ae.id_enfermeria = :id_enfermeria
    ", ['id_enfermeria' => $id_enfermeria]);
    return $data[0];
  }
  public function anularItemProcedimientoEnfermeria(Request $request)
  {
    $prestacionProcedimiento = Prestacion_procedimiento::where('id_procedimiento', $request->id_procedimiento)
      ->where('id_evolucion_enfermeria', $request->id_evoluciones)
      ->first();

    $procedimientoDiagnostico = DB::table('procedimientos_diagnoticos')->where('id_procedimientos', $request->id_procedimiento)
      ->where('id_evolucion', $request->id_evoluciones)
      ->first();

    if ($prestacionProcedimiento && $procedimientoDiagnostico) {

      $prestacionProcedimiento->id_estado_reg = 0;
      $prestacionProcedimiento->deleted_us = Auth::user()->id;
      $prestacionProcedimiento->deleted_at = Carbon::now();
      $prestacionProcedimiento->save();

      DB::table('procedimientos_diagnoticos')
        ->where('id_procedimientos', $request->id_procedimiento)
        ->where('id_evolucion', $request->id_evoluciones)
        ->update(['estado' => 0]);

      return json_encode(1);
    } else {
      return response()->json(['mensaje' => 'Registro no encontrado'], 404);
    }
  }
  public function anularItemEvolucionDetalle(Request $request)
  {
    $evolucionDetalle = Nota_enfermeria_evoluciones_detalle::find($request->id_evolucion_detalle);
    if ($evolucionDetalle) {
      $evolucionDetalle->estado = 0;
      $evolucionDetalle->delete_us = Auth::user()->id;
      $evolucionDetalle->delete_at = Carbon::now();
      $evolucionDetalle->save();
      return json_encode(1);
    } else {
      return response()->json(['mensaje' => 'Registro no econtrado'], 404);
    }
  }
  function fecha_registro_admision($id_enfermeria)
  {
    $data = Enfermeria::select('enfermerias.id')
      ->selectRaw("
        CASE 
            WHEN enfermerias.id_tipo_atencion = 2 THEN emergencias.fecha_registro_admision::date
            WHEN enfermerias.id_tipo_atencion = 3 THEN emergencias.fecha_registro_admision::date
        END AS fecha
    ")
      ->leftJoin('emergencias', 'enfermerias.id_ext_enfermeria', '=', 'emergencias.id')
      ->leftJoin('hospitalizaciones', 'enfermerias.id_ext_enfermeria', '=', 'hospitalizaciones.id')
      ->where('enfermerias.id', $id_enfermeria)
      ->first();
    return $data;
  }

  function fechas_ingreso_egreso($id_ext, $id_tipo_atencion)
  {
    $data = null;
    if ($id_tipo_atencion == 2) {
      $data = Emergencia::selectRaw("emergencias.fecha_registro_ingreso::timestamp  AS fecha_hora_ingreso, (emergencias.fecha_registro_egreso::date || ' ' || emergencias.hora_egreso)::timestamp AS fecha_hora_egreso")
        ->where('emergencias.id', $id_ext)
        ->first();
      return $data;
    } else if ($id_tipo_atencion == 3) {
      $data = Hospitalizacione::selectRaw("
          (hospitalizaciones.fecha_ingreso::date || ' ' || hospitalizaciones.hora_ingreso)::timestamp AS fecha_hora_ingreso,
          (hospitalizaciones.fecha_alta_medica::date || ' ' || hospitalizaciones.hora_alta_medica)::timestamp AS fecha_hora_egreso
        ")
        ->where('hospitalizaciones.id', $id_ext)
        ->first();
      return $data;
    }
  }

  /********************FIRMA PDF***************/
  public function evolucionesPDFfirmar($id_evolucion)
  {
    $resultado = array();
    try {
      $path = public_path('tmp/csv');
      $path_zip = public_path('tmp/zip');

      if (!is_dir($path)) {
        mkdir($path);
      }
      if (!is_dir($path_zip)) {
        mkdir($path_zip);
        mkdir(public_path('tmp/zip/firmados'));
        mkdir(public_path('tmp/zip/sin_firma'));
      }
      $csv = "";
      if (Auth::check()):
        $csv .= Auth::user()->id;
      endif;

      $csv .= "_" . date("YmdHis");
      $csv .= "_evoluciones_enfermeria";

      Excel::create($csv, function ($excel) use ($id_evolucion) {
        $excel->sheet('GENERAL', function ($sheet) use ($id_evolucion) {
          $variable = [];
          $this->generarPdfParaFirmaEvoluciones($id_evolucion);
          array_push($variable, array(url() . '/tmp/pdf/sin_firma/evoluciones_enfermeria_' . $id_evolucion . '.pdf', url() . '/upload_firma/?name=cf_evoluciones_enfermeria_' . $id_evolucion . '.pdf&id_tipo=evoluciones_enfermeria&id_resol=' . $id_evolucion, 'cf_evoluciones_enfermeria_' . $id_evolucion . '.pdf', '0', '45', '350', '70', 'Fecha de Firma: <DATE> Firmante: <SIGNER>', url() . '/images/escudo.jpg', '1', '7'));
          $sheet->fromArray($variable, null, '', false, false);
        });
      })->store('csv', public_path('tmp/csv'));

      $obj = new Archive7z(public_path('tmp/zip/sin_firma/' . $csv . '.7z'));
      $obj->addEntry(public_path('/tmp/pdf/sin_firma/evoluciones_enfermeria_' . $id_evolucion . '.pdf'));
      $resultado["csv"] = $csv;
      $resultado["sw"] = true;
    } catch (\Exception $e) {
      Log::error('ERROR EN EVOLUCIONES ENFERMERIA FIRMA PDF: ' . $e->getMessage(), [
        'exception' => $e,
        'trace' => $e->getTraceAsString(),
      ]);
    }
    return response()->json($resultado);
  }

  public function generarPdfParaFirmaEvoluciones($id_evolucion)
  {
    $path = public_path('tmp/pdf');
    if (!is_dir($path)) {
      mkdir($path);
    }
    $path = public_path('tmp/pdf/sin_firma/');
    if (!is_dir($path)) {
      mkdir($path);
    }

    $date = Carbon::now();
    $fechaActual = $date->format('d/m/Y');
    $horaActual = $date->format('H:i:s');

    //--- ESTABLECIMIENTO
    $id_establecimiento = Auth::user()->id_establecimiento; // id_establecimiento = 76 - 1 mdico  
    $model_establecimiento = new \App\Models\Establecimiento();
    $establecimiento =  $model_establecimiento->GetById($id_establecimiento);
    $nombre_establecimiento = $establecimiento[0]->nombre;

    /***************************EVOLUCIONES CABECERA********************************/
    $evolucionesModelo = new Nota_enfermeria_evoluciones;
    $evoluciones_cabecera = $evolucionesModelo->getver_nota_ingreso_evoluciones($id_evolucion);
    /***************************FIN EVOLUCIONES DETALLES********************************/

    /***************************EVOLUCIONES DETALLES********************************/
    $tipo_de_seleccion_diagnostico = 'DIAGNOSTICO_ENFERMERIA';
    $evoluciones_cabecera_diagnostico = $evolucionesModelo->getver_nota_ingreso_evoluciones_detalle_diagnostico($id_evolucion, $tipo_de_seleccion_diagnostico);

    $tipo_de_seleccion_plan_cuidado = 'PLAN_CUIDADO_ENFERMERIA';
    $evoluciones_cabecera_plan_cuidado = $evolucionesModelo->getver_nota_ingreso_evoluciones_detalle_diagnostico($id_evolucion, $tipo_de_seleccion_plan_cuidado);

    $tipo_de_seleccion_intervenciones = 'INTERVENCIONES_ENFERMERIA';
    $evoluciones_cabecera_intervenciones = $evolucionesModelo->getver_nota_ingreso_evoluciones_detalle_diagnostico($id_evolucion, $tipo_de_seleccion_intervenciones);

    $tipo_de_seleccion_evaluaciones = 'EVALUACIONES_ENFERMERIA';
    $evoluciones_cabecera_evaluaciones = $evolucionesModelo->getver_nota_ingreso_evoluciones_detalle_diagnostico($id_evolucion, $tipo_de_seleccion_evaluaciones);

    $evoluciones_cabecera_cpms = $evolucionesModelo->getver_nota_ingreso_evoluciones_procedimientos($id_evolucion);

    $evoluciones_cabecera_invasivos = $evolucionesModelo->getver_nota_ingreso_evoluciones_invasivos($id_evolucion);
    /***************************FIN EVOLUCIONES DETALLES********************************/

    /***************************DATOS DE LA NOTA DE ENFERMERIA********************************/
    $id_tipo_atencion = $evoluciones_cabecera[0]->id_tipo_atencion; //TIPO ATENCION
    $id_ext_enfermeria = $evoluciones_cabecera[0]->id_ext_enfermeria; //ID DE EMERGENCIA O HOSPITALIZACION
    $codigo_cups = '';
    if ($id_tipo_atencion == 2) {
      $datos_atencion = Emergencia::from('emergencias as e1')
        ->leftJoin('camas as c1', 'c1.id', '=', 'e1.cama_estado')
        ->leftJoin('cuartos as c2', 'c2.id', '=', 'c1.id_cuarto')
        ->leftJoin('triajes as t', 't.id', '=', 'e1.id_triaje')
        ->leftJoin('prioridades as p2', 'p2.id', '=', 'e1.id_prioridad')
        ->select(
          't.descripcion as area',
          'p2.descripcion as prioridad',
          DB::raw("COALESCE(c2.nombre, 'SIN ASIGNAR') AS nombre_cuarto"),
          DB::raw("COALESCE(c1.nombre, 'SIN ASIGNAR') AS nombre_cama"),
          'e1.id as id_emergencia'
        )->where('e1.id', $id_ext_enfermeria)
        ->first();
      $codigo_cups = Emergencia::select('emergencias.cups_pnp as codigo_cups')->where('emergencias.id', $datos_atencion->id_emergencia)->first();
      $codigo_cups = $codigo_cups->codigo_cups;
    } else if ($id_tipo_atencion == 3) {
      $datos_atencion =  DB::selectOne("
              select COALESCE(c2.nombre, 'SIN ASIGNAR') AS nombre_cuarto,
              COALESCE(c1.nombre, 'SIN ASIGNAR') AS nombre_cama,
              h.id as id_hospitalizacion
              from hospitalizaciones h 
              left join camas c1 on h.id_cama=c1.id 
              left join cuartos c2 on h.id_cuarto = c2.id
                      WHERE h.id = :id_hospitalizacion
              ", ['id_hospitalizacion' => $id_ext_enfermeria]);

      $codigo_cups = Hospitalizacione::select('hospitalizaciones.cups_pnp as codigo_cups')->where('hospitalizaciones.id', $datos_atencion->id_hospitalizacion)->first();
      $codigo_cups = $codigo_cups->codigo_cups;
    }

    /***************************FIN EVOLUCIONES DETALLES********************************/

    $datos_paciente = $this->getDatosPaciente($evoluciones_cabecera[0]->id_asegurado);

    $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/" . date("Y/m/d") . "/cf_evoluciones_enfermeria_" . $id_evolucion . ".pdf";
    $codigo_qr = QrCode::format('png')->size(150)->generate("https://sigesapol.dirsapolpnp.gob.pe/" . date("Y/m/d") . "/cf_evoluciones_enfermeria_" . $id_evolucion . ".pdf");

    $pdf = \PDF::loadView(
      'frontend.emergencia_enfermeria.evoluciones.pdf_evoluciones.pdf_nota_ingreso_evoluciones_firmado',
      compact(
        'url_descarga',
        'codigo_qr',
        'id_tipo_atencion',
        'datos_paciente',
        'evoluciones_cabecera',
        'evoluciones_cabecera_diagnostico',
        'evoluciones_cabecera_plan_cuidado',
        'evoluciones_cabecera_intervenciones',
        'evoluciones_cabecera_evaluaciones',
        'evoluciones_cabecera_cpms',
        'evoluciones_cabecera_invasivos',
        'datos_atencion',
        'nombre_establecimiento',
        'fechaActual',
        'horaActual',
        'codigo_cups'
      )
    );
    /* $pdf->set_option('isHtml5ParserEnabled', true);
    $pdf->set_option('isPhpEnabled', true);
    $pdf->set_option('isFontSubsettingEnabled', true); */
    $pdf->setPaper('A4', 'portrait');

    file_put_contents($path . "evoluciones_enfermeria_" . $id_evolucion . ".pdf", $pdf->output());
  }
  public function postArgumentsEvoluciones(Request $request)
  {
    $SERVER_PATH = env('REFIRMA_SERVER_PATH');
    $protocol = (env('REFIRMA_PROTOCOL') == 'http') ? "T" : "S";

    if ($request->type == "W") {
      $param = '{
                    "app":"pcx",
                    "mode":"lot-p",
                    "clientId":"' . env('REFIRMA_CLIENT_ID') . '",
                    "clientSecret":"' . env('REFIRMA_CLIENT_SECRET') . '",
                    "idFile":"MyForm",
                    "protocol":"' . $protocol . '",
                    "type":"W",
                    "fileDownloadUrl":"' . $SERVER_PATH . '/tmp/zip/sin_firma/' . $request->documentName . '",
                    "fileDownloadLogoUrl":"' . $SERVER_PATH . '/images/iLogo1.png",
                    "fileDownloadStampUrl":"' . $SERVER_PATH . '/images/logo_sanidad_firma.png",
                    "fileUploadUrl":"' . $SERVER_PATH . '/refirma/upload",
                    "contentFile":"' . $request->documentName . '",
                    "reason":"Soy el autor del documento",
                    "isSignatureVisible":"true",
                    "stampAppearanceId":"0",
                    "pageNumber":"0",
                    "posx":"20",
                    "posy":"5",
                    "fontSize":"7",
                    "dcfilter": ".*FIR.*|.*FAU.*",
                    "signatureLevel":"0",
                    "maxFileSize":"5242880"
                }';
      echo  base64_encode($param);
    }
  }

  public function actualizarFirmaConTokenEvoluciones(Request $request)
  {
    $id = intval($request->id_resol);
    $evolucion = Nota_enfermeria_evoluciones::where('id', $id)->first();
    if (!$evolucion) {
      return response()->json(['message' => 'No existe registro de la evolución'], 404);
    }
    if ($evolucion->fecha_firma == "") {
      $evolucion->fecha_firma = date("d-m-Y H:i:s");
      $evolucion->ruta_firma = date("Y/m/d") . "/cf_evoluciones_enfermeria_" . $id . ".pdf";
      $evolucion->flag_firma = 1;
      $evolucion->save();
    }
    return "ok";
  }
  public function actualizarFirmaSinTokenEvoluciones(Request $request)
  {
    $ok = false;
    $id = intval($request->id_resol);
    $evolucion = Nota_enfermeria_evoluciones::where('id', $id)->first();
    if (!$evolucion) {
      return response()->json([
        'success' => false,
        'message' => 'No se encontró la evolución indicada.'
      ], 404);
    }
    if ($evolucion->fecha_firma == "") {
      $evolucion->fecha_firma = date("d-m-Y H:i:s");
      $evolucion->ruta_firma = date("Y/m/d") . "/cf_evoluciones_enfermeria_" . $id . ".pdf";
      $evolucion->flag_firma = 2;
      $evolucion->save();
      $ok = true;
      return response()->json([
        'success' => true,
        'message' => 'La evolución fue firmada correctamente.'
      ]);
    }
    /* echo $ok; */
    return response()->json([
      'success' => false,
      'message' => 'La evolución ya se encuentra firmada.'
    ]);
  }

  public function obtenerEvolucion($evolucion_id)
  {
    $evolucion = Nota_enfermeria_evoluciones::where("id", "=", $evolucion_id)->first();
    return json_encode($evolucion);
  }

  public function buscar_diagnostico_por_id(Request $request)
  {
    $data = Diagnostico::where("id", "=", $request->id_diagnostico)->first();
    return $data;
  }

  public function getEvolucionDetail($id_tipo_atencion, $id_evolucion)
  {
    $model_prestacion = new Prestaciones();
    $evolucion = Nota_enfermeria_evoluciones::join("turnos", "nota_enfermeria_evoluciones.id_turno", "=", "turnos.id")
      ->where("nota_enfermeria_evoluciones.id", $id_evolucion)
      ->select(
        "nota_enfermeria_evoluciones.id_enfermeria",
        "nota_enfermeria_evoluciones.created_at",
        "nota_enfermeria_evoluciones.descripcion_subjetivo",
        "nota_enfermeria_evoluciones.descripcion_plan",
        "nota_enfermeria_evoluciones.descripcion_objetivo",
        "nota_enfermeria_evoluciones.descripcion_intervenciones",
        "nota_enfermeria_evoluciones.descripcion_evaluaciones",
        "nota_enfermeria_evoluciones.descripcion_analisis",
        "turnos.descripcion as turno_nombre"
      )
      ->first();
    $enfermeria = Enfermeria::where("id", $evolucion->id_enfermeria)->where("id_tipo_atencion", $id_tipo_atencion)->first();
    $prestacion = $model_prestacion->getOneIdPrestacionSiExiste($enfermeria->id_ext_enfermeria, $id_tipo_atencion);
    $id_prestacion = $prestacion->id;

    $model_evoluciones_enf = new Nota_enfermeria_evoluciones;
    $model_cpt = new Enfermeria_cpt;
    $puntos = $model_evoluciones_enf->pintarPuntosEvolucion($enfermeria->id, $id_tipo_atencion);

    $diagnosticos_pacientes_prestacion = $id_prestacion ? $this->diagnosticos_pacientes_prestacion($id_prestacion) : $this->diagnosticos_pacientes_hospi($enfermeria->id);
    $turnos = $this->turnos();
    $tipoAtenciones = $this->tipoAtenciones();

    $data['invasivos_por_atencion'] =  $this->listaInvasivosIdAtencion($enfermeria->id, 1);
    $data['diagnosticos_por_prestacion'] = $diagnosticos_pacientes_prestacion;
    $data['turnos'] = $turnos;
    $data['enfermeria'] = $enfermeria;
    $data['tipo_atenciones'] = $tipoAtenciones;
    $data['fechas_ingreso_egreso'] = $this->fechas_ingreso_egreso($enfermeria->id_ext_enfermeria, $id_tipo_atencion);
    $data['nota_enfermeria'] = $model_evoluciones_enf->getver_nota_ingreso_evoluciones($id_evolucion);
    $tipo_de_seleccion_diagnostico = 'DIAGNOSTICO_ENFERMERIA';
    $data['nota_enfermeria_diagnostico'] = $model_evoluciones_enf->getver_nota_ingreso_evoluciones_detalle_diagnostico($id_evolucion, $tipo_de_seleccion_diagnostico);
    $tipo_de_seleccion_plan_cuidado = 'PLAN_CUIDADO_ENFERMERIA';
    $data['nota_enfermeria_plan_cuidado'] = $model_evoluciones_enf->getver_nota_ingreso_evoluciones_detalle_diagnostico($id_evolucion, $tipo_de_seleccion_plan_cuidado);
    $tipo_de_seleccion_intervenciones = 'INTERVENCIONES_ENFERMERIA';
    $data['nota_enfermeria_intervenciones'] = $model_evoluciones_enf->getver_nota_ingreso_evoluciones_detalle_diagnostico($id_evolucion, $tipo_de_seleccion_intervenciones);
    $tipo_de_seleccion_evaluaciones = 'EVALUACIONES_ENFERMERIA';
    $data['nota_enfermeria_evaluaciones'] = $model_evoluciones_enf->getver_nota_ingreso_evoluciones_detalle_diagnostico($id_evolucion, $tipo_de_seleccion_evaluaciones);
    $data['evolucion'] = $evolucion;
    $data['nota_enfermeria_evolucion_det'] = $model_evoluciones_enf->getver_nota_ingreso_evoluciones_detalle_diagnostico($id_evolucion, $tipo_de_seleccion_evaluaciones);
    $data['nota_enfermeria_cpms'] = $model_evoluciones_enf->getver_nota_ingreso_evoluciones_procedimientos($id_evolucion);
    $cptRegistradoEmergencia = Enfermeria_cpt::where("id_enfermeria", $enfermeria->id)
      ->where("id_tipo_atenciones", $id_tipo_atencion)
      ->first();
    if ($cptRegistradoEmergencia) {
      $data['nota_cpt'] = $model_cpt->getver_nota_ingreso_cpt($cptRegistradoEmergencia->id);
      $data['cpms'] = $model_cpt->getver_nota_ingreso_cpt_cpms($cptRegistradoEmergencia->id);
    } else {
      $data['nota_cpt'] = null;
      $data['cpms'] = null;
    }
    $data['invasivos_por_evolucion'] = $model_evoluciones_enf->getver_nota_ingreso_evoluciones_invasivos($id_evolucion);
    $data['fecha_registro_admision'] = $this->fecha_registro_admision($enfermeria->id);
    $data['estado_alergia'] =  $this->getEstadoAlergia($enfermeria->id);
    $data['estado_acomp'] =  $this->getEstadoAcomp($enfermeria->id);
    $data['id_prestacion'] =  $id_prestacion;
    $data['puntos'] =  $puntos;

    if ($id_tipo_atencion == 2) {
      $emergencia = Emergencia::join('prioridades', 'emergencias.id_prioridad', '=', 'prioridades.id')
        ->where("emergencias.id", $enfermeria->id_ext_enfermeria)->select('emergencias.id', 'prioridades.descripcion as prioridad_nombre')->first();
      $data['emergencia'] =  $emergencia;
    } else if ($id_tipo_atencion == 3) {
      $hospitalizacion = Hospitalizacione::join('upsses', 'hospitalizaciones.id_servicio_origen', '=', 'upsses.id')->join('consultorios', 'upsses.id_consultorio', '=', 'consultorios.id')
        ->where("hospitalizaciones.id", $enfermeria->id_ext_enfermeria)->select('hospitalizaciones.id', 'consultorios.nombre as servicio_nombre')->first();
      $data['hospitalizacion'] =  $hospitalizacion;
    }

    return response()->json($data);
  }
}
