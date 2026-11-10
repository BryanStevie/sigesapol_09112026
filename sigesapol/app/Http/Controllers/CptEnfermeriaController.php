<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Cpt_Cpms;
use App\Models\Emergencia;
use App\Models\Enfermeria;
use App\Models\Enfermeria_cpt;
use App\Models\Hospitalizacione;
use App\Models\Nota_enfermeria_evoluciones;
use App\Models\Prestaciones;
use App\Models\TipoAtencione;
use App\Models\Turno;
use Archive7z\Archive7z;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class CptEnfermeriaController extends Controller
{
    public function lista_nota_ingreso_cpt(Request $request)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;
        $model_cpt = new Enfermeria_cpt;
        $p[] = $request->fecha_evolucion_bus_inicial_listado;  //0
        $p[] = $request->fecha_evolucion_bus_final_listado;  //1 
        $p[] = $request->id_turno;  //2 
        $p[] = $request->id_tipo_atencion;  //3 - ID TIPO ATENCION
        $p[] = $request->id_asegurado;
        $p[] = $request->id_enfermeria;
        $p[] = $request->id_ext;
        $turnos = $this->turnos();
        $tipoAtenciones = $this->tipoAtenciones();
        $datosPacienteIdAsegurado = $this->getDatosPaciente($request->id_asegurado);
        $estadoAlergia = $request->id_enfermeria ? $this->getEstadoAlergia($request->id_enfermeria) : 0;
        $estadoAcomp = $request->id_enfermeria ? $this->getEstadoAcomp($request->id_enfermeria) : 0;
        $data['listado_cpt'] = $model_cpt->getlista_nota_ingreso_cpt($p);
        $data['turnos'] = $turnos;
        $data['tipo_atenciones'] = $tipoAtenciones;
        $data['datos_paciente'] =  $datosPacienteIdAsegurado;
        $data['estado_alergia'] =  $estadoAlergia;
        $data['estado_acomp'] =  $estadoAcomp;
        return json_encode($data);
    }

    public function turnos()
    {
        $model_turno = new Turno();
        $turno = $model_turno->ShowAll();
        return $turno;
    }

    public function tipoAtenciones()
    {
        $model_atencione = new TipoAtencione;
        $atencione = $model_atencione->ShowTipoAtencionAll();
        return $atencione;
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
    asegurados.paterno,asegurados.materno,asegurados.nombre,asegurados.sexo
    FROM asegurados INNER JOIN asegurado_historias ON asegurado_historias.id_asegurado = asegurados.id
    left join tipo_beneficiarios tb on tb.id =asegurados.id_tipo_beneficiario
    left join tipo_sangres ts on asegurados.id_tipo_sangre = ts.id
    WHERE asegurados.id_estado_reg=1 and asegurados.id = :id_asegurado
    ", ['id_asegurado' => $id_asegurado]);
        return $data[0];
    }

    public function getEstadoAlergia($id_enfermeria)
    {
        $data =  DB::select("select 
    ae.id_alergia as flag_alergia
    FROM antecedentes_enfermerias ae
    WHERE ae.id_enfermeria = :id_enfermeria
    ", ['id_enfermeria' => $id_enfermeria]);
        return $data ? $data[0] : 0;
    }

    public function getEstadoAcomp($id_enfermeria)
    {
        $data =  DB::select("select 
    numero_acompanante as celular_acomp
    FROM valoraciones_enfermerias ve
    WHERE ve.id_enfermeria = :id_enfermeria
    ", ['id_enfermeria' => $id_enfermeria]);
        return $data ? $data[0] : 0;
    }

    public function registrar_nota_ingreso_cpt(Request $request)
    {
        if (!Auth::check()) :
            return response()->json(['message' => 'No autenticado.'], 401);
        endif;
        $data = $request->all();
        try {
            DB::beginTransaction();
            $nuevoCpt = new Enfermeria_cpt;
            $nuevoCpt->id_usuario_registro = Auth::user()->id;
            $nuevoCpt->id_enfermeria = $request->id_enfermeria ? $request->id_enfermeria : null;
            $nuevoCpt->id_ext = $request->id_ext ? $request->id_ext : null;
            $nuevoCpt->id_tipo_atenciones = $request->id_tipo_atenciones_registro;
            $nuevoCpt->id_establecimiento = Auth::user()->id_establecimiento;
            $nuevoCpt->id_asegurado = $request->id_asegurado2;
            $nuevoCpt->id_turno = $request->turno_cpt;
            $nuevoCpt->estado = 1;
            $nuevoCpt->flag_firma = 0;
            $nuevoCpt->created_at = $request->fecha_registro_cpt . ' ' . (preg_match('/^([01]\d|2[0-3]):[0-5]\d:[0-5]\d$/', $request->hora_registro_cpt) ? $request->hora_registro_cpt : $request->hora_registro_cpt . ':00');
            $nuevoCpt->updated_at = $request->fecha_registro_cpt . ' ' . (preg_match('/^([01]\d|2[0-3]):[0-5]\d:[0-5]\d$/', $request->hora_registro_cpt) ? $request->hora_registro_cpt : $request->hora_registro_cpt . ':00');
            $nuevoCpt->save();
            $cptRegistrado_id = $nuevoCpt->id;
            if ($request->id_relacionado_CPMS_ENFERMERIA) {
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
            DB::commit();
            return response()->json(['message' => 'Registro guardado correctamente.', 'id' => $nuevoCpt->id], 200);
        } catch (\Exception $e) {
            Log::error('ERROR EN ENFERMERIA AL GUARDAR EVOLUCIONES: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);
            DB::rollback();
            return response()->json(['message' => 'Error interno al guardar el registro.'], 500);
        }
    }

    public function fecha_registro_admision($id_enfermeria)
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

    public function diagnosticos_pacientes_prestacion($id_prestacion)
    {
        $model = new Enfermeria_cpt;
        $diagnosticos = $model->diagnosticos_pacientes_prestacion($id_prestacion);
        return $diagnosticos;
    }

    public function diagnosticos_hospi_admision($id_enfermeria)
    {
        $enfermeria = Enfermeria::where("id", "=", $id_enfermeria)->first();
        $model = new Enfermeria_cpt();
        $diagnosticos = $model->diagnosticos_hospi_admision($enfermeria->id_ext_enfermeria);
        return $diagnosticos;
    }

    public function datosGlobales(Request $request)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;
        $data['turnos'] = $this->turnos();
        $data['datos_paciente'] =  $this->getDatosPaciente($request->id_asegurado);
        $data['diagnosticos'] = $this->buscarDiagnosticosByExtId($request->id_ext, $request->id_tipo_atencion);
        $data['estado_alergia'] =  $request->id_enfermeria ? $this->getEstadoAlergia($request->id_enfermeria) : 0;
        $data['estado_acomp'] = $request->id_enfermeria ? $this->getEstadoAcomp($request->id_enfermeria) : 0;
        $data['fechas_ingreso_egreso'] = $this->fechas_ingreso_egreso($request->id_ext, $request->id_tipo_atencion);
        return json_encode($data);
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

    public function datosGlobalesHospi(Request $request)
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
        $data['diagnosticos'] = $this->buscarDiagnosticosDeHospitalizacion($request->id_enfermeria);
        $data['fecha_registro_admision'] = $this->fecha_registro_admision($request->id_enfermeria);

        return json_encode($data);
    }

    public function ver_nota_ingreso_cpt(Request $request)
    {
        if (!Auth::check()) :
            return response()->json(['message' => 'No autenticado.'], 401);
        endif;
        $model_cpt = new Enfermeria_cpt;
        $cpt = Enfermeria_cpt::where("id", $request->id_cpt)->latest("id")->first();
        $enfermeria = Enfermeria::where("id", $cpt->id_enfermeria)->latest("id")->first();
        $turnos = $this->turnos();
        $tipoAtenciones = $this->tipoAtenciones();
        $datosPacienteIdAsegurado = $this->getDatosPaciente($request->id_asegurado);
        $data['turnos'] = $turnos;
        /* $data['diagnosticos'] = $enfermeria && $enfermeria->id_tipo_atencion == 3 ? $this->buscarDiagnosticosDeHospitalizacion($enfermeria->id) : $this->buscarDiagnosticosEmergencia($enfermeria->id); */
        $data['diagnosticos'] = $this->buscarDiagnosticosByExtId($request->id_ext, $request->id_tipo_atencion);
        $data['tipo_atenciones'] = $tipoAtenciones;
        $data['datos_paciente'] =  $datosPacienteIdAsegurado;
        $data['nota_cpt'] = $model_cpt->getver_nota_ingreso_cpt($request->id_cpt);
        $data['cpms'] = $model_cpt->getver_nota_ingreso_cpt_cpms($request->id_cpt);
        /* $data['estado_alergia'] =  $this->getEstadoAlergia($request->id_enfermeria);
        $data['estado_acomp'] =  $estadoAcomp; */
        $data['estado_alergia'] =  $request->id_enfermeria ? $this->getEstadoAlergia($request->id_enfermeria) : 0;
        $data['estado_acomp'] = $request->id_enfermeria ? $this->getEstadoAcomp($request->id_enfermeria) : 0;
        $data['fechas_ingreso_egreso'] = $this->fechas_ingreso_egreso($request->id_ext, $request->id_tipo_atencion);
        return response()->json($data);
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

    public function buscarDiagnosticosDeHospitalizacion($id_enfermeria)
    {
        $enfermeria = Enfermeria::where("id", "=", $id_enfermeria)->first();
        $model = new Nota_enfermeria_evoluciones();
        $diagnosticos = [
            'diagnosticos_atencion' => $enfermeria->id_prestacion ? $model->diagnosticos_pacientes_prestacion($enfermeria->id_prestacion) : $this->buscarDiagnosticosDePrestacion($enfermeria->id_ext_enfermeria, 3),
            'diagnosticos_admision' => $enfermeria->id_ext_enfermeria && $enfermeria->id_tipo_atencion == 3 ? $model->diagnosticos_hospi_admision($enfermeria->id_ext_enfermeria) : [],
        ];
        return $diagnosticos;
    }

    public function buscarDiagnosticosByExtId($id_ext, $id_tipo_atencion)
    {
        $model = new Nota_enfermeria_evoluciones();
        $diagnosticos = [
            'diagnosticos_admision' => $id_tipo_atencion == 3 ? ($model->diagnosticos_hospi_admision($id_ext)) : [],
            'diagnosticos_atencion' => $this->buscarDiagnosticosDePrestacion($id_ext, $id_tipo_atencion),
        ];
        return $diagnosticos;
    }

    public function pdf_nota_ingreso_cpt($id_cpt)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $date = Carbon::now();
        $fechaActual = $date->format('d/m/Y');
        $horaActual = $date->format('H:i:s');

        $id_establecimiento = Auth::user()->id_establecimiento; // id_establecimiento = 76 - 1 mdico  
        $model_establecimiento = new \App\Models\Establecimiento();
        $establecimiento =  $model_establecimiento->GetById($id_establecimiento);
        $nombre_establecimiento = $establecimiento[0]->nombre;
        $model_cpt = new Enfermeria_cpt;
        $cpt_cabecera = $model_cpt->getver_nota_ingreso_cpt($id_cpt);
        $cpt_cpms = $model_cpt->getver_nota_ingreso_cpt_cpms($id_cpt);
        $id_tipo_atencion = $cpt_cabecera[0]->id_tipo_atenciones; //TIPO ATENCION
        $id_ext_enfermeria = $cpt_cabecera[0]->id_ext; //ID DE EMERGENCIA O HOSPITALIZACION
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
        $datos_paciente = $this->getDatosPaciente($cpt_cabecera[0]->id_asegurado);
        $pdf = \PDF::loadView(
            'frontend.hospitalizacion_enfermeria.cpt.pdf.pdf_nota_ingreso_cpt',
            compact(
                'id_tipo_atencion',
                'datos_paciente',
                'cpt_cabecera',
                'cpt_cpms',
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

    public function motivoAnulacionCpt(Request $request)
    {
        $cpt = Enfermeria_cpt::find($request->id_cpt);
        if ($cpt) {
            $cpt->estado = 0;
            $cpt->id_usuario_anulado = Auth::user()->id;
            $cpt->motivo_anulacion = $request->anulacion_motivo_cpt;
            $cpt->fecha_anulacion = Carbon::now();
            $cpt->save();
        } else {
            return response()->json(['cpt anulado:' => $request->id_cpt], 404);
        }
        return json_encode(1);
    }

    public function anularItemProcedimientoEnfermeriaCpt(Request $request)
    {
        $cpms = Cpt_Cpms::where('id', $request->id_cpms)
            ->first();
        if ($cpms) {
            $cpms->estado_reg = 0;
            $cpms->deleted_us = Auth::user()->id;
            $cpms->deleted_at = Carbon::now();
            $cpms->save();
            return response()->json([
                'success' => true,
                'message' => 'Registro eliminado correctamente.',
                'id' => $cpms->id
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Registro no encontrado.'
            ], 404);
        }
    }

    public function actualizar_cpt_cpms(Request $request)
    {
        if (!Auth::check()) :
            return response()->json(['message' => 'No autenticado.'], 401);
        endif;
        $data = $request->all();
        $turnoSeleccionado = $request->turno_cpt;
        $usuario_id = $request->id_asegurado2;
        $fechaEvol = Carbon::createFromFormat('Y-m-d', $request->fecha_upt_cpt);
        /* $cptEncontrado = Enfermeria_cpt::whereRaw('DATE(created_at) = ?', [$fechaEvol->format('Y-m-d')])
            ->where('id_asegurado', $usuario_id)
            ->where('id_turno', $turnoSeleccionado)
            ->where('id_tipo_atenciones', $request->id_tipo_atenciones_listado)
            ->where('id', '!=', $request->id_cpt)
            ->where('estado', 1)
            ->get();
        if (($cptEncontrado->count() > 0)) {
            return response()->json([
                'message' => 'Ya existe un registro para este paciente en el mismo turno y día.'
            ], 409);
        } */
        try {
            DB::beginTransaction();
            $cptParaActualizar = Enfermeria_cpt::find($data['id_cpt']);
            $cptParaActualizar->id_turno = $request->turno_cpt;
            $cptParaActualizar->updated_us = Auth::user()->id;
            $cptParaActualizar->updated_at = $request->fecha_upt_cpt . ' ' . (preg_match('/^([01]\d|2[0-3]):[0-5]\d:[0-5]\d$/', $request->hora_upt_cpt) ? $request->hora_upt_cpt : $request->hora_upt_cpt . ':00');
            $cptParaActualizar->save();
            if ($request->id_relacionado_CPMS_ENFERMERIA_EDITAR) {
                foreach ($request->id_relacionado_CPMS_ENFERMERIA_EDITAR as $key => $value) {
                    /* $cpms_id = $request->id_cpms_upt[$key]; */
                    $cpms_id = isset($request->id_cpms_upt[$key]) ? $request->id_cpms_upt[$key] : null;
                    $procedimiento_id = $request->id_relacionado_CPMS_ENFERMERIA_EDITAR[$key];
                    $procedimiento_cantidad = $request->procedimiento_cantidad[$key];
                    $procedimiento_tiempo = $request->procedimiento_tiempo[$key];
                    $diagnostico_id = $request->procedimiento_diagnosticos[$key];
                    $cpms = DB::table('cpt_cpms')
                        ->where('id', $cpms_id);
                    if ($cpms->exists()) {
                        $data = [
                            'updated_at' => Carbon::now(),
                            'updated_us' => Auth::user()->id,
                            'cantidad' => $procedimiento_cantidad,
                            'tiempo' => $procedimiento_tiempo,
                            'id_diagnostico' => $diagnostico_id,
                            'id_procedimiento' => $procedimiento_id
                        ];
                        $cpms->update($data);
                    } else {
                        $data = [
                            'id_prestacion' => null,
                            'id_procedimiento' => $procedimiento_id,
                            'id_diagnostico' => $diagnostico_id,
                            'estado_reg' => 1,
                            'created_at' =>  Carbon::now(),
                            'created_us' => Auth::user()->id,
                            'cantidad' => $procedimiento_cantidad,
                            'tiempo' => $procedimiento_tiempo,
                            'id_nota_enfermeria_cpt' => $cptParaActualizar->id
                        ];
                        DB::table('cpt_cpms')->insert($data);
                    }
                }
            }
            DB::commit();
            return response()->json(['message' => 'Registro actualizado correctamente.', 'id_cpt' => $cptParaActualizar->id], 200);
        } catch (\Exception $e) {
            Log::error('ERROR EN ENFERMERIA AL GUARDAR EVOLUCIONES: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            DB::rollback();
            return response()->json(['message' => 'Error interno al guardar el registro.'], 500);
        }
    }

    public function cptPDFfirmar($cpt_id, $id_tipo_atencion)
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
            $csv .= "_cpt_enfermeria";

            Excel::create($csv, function ($excel) use ($cpt_id) {
                $excel->sheet('GENERAL', function ($sheet) use ($cpt_id) {
                    $variable = [];
                    $this->generarPdfParaFirmaCpt($cpt_id);
                    array_push($variable, array(url() . '/tmp/pdf/sin_firma/cpt_enfermeria_' . $cpt_id . '.pdf', url() . '/upload_firma/?name=cf_cpt_enfermeria_' . $cpt_id . '.pdf&id_tipo=cpt_enfermeria&id_resol=' . $cpt_id, 'cf_cpt_enfermeria_' . $cpt_id . '.pdf', '0', '45', '350', '70', 'Fecha de Firma: <DATE> Firmante: <SIGNER>', url() . '/images/escudo.jpg', '1', '7'));
                    $sheet->fromArray($variable, null, '', false, false);
                });
            })->store('csv', public_path('tmp/csv'));

            $obj = new Archive7z(public_path('tmp/zip/sin_firma/' . $csv . '.7z'));
            $obj->addEntry(public_path('/tmp/pdf/sin_firma/cpt_enfermeria_' . $cpt_id . '.pdf'));
            $resultado["csv"] = $csv;
            $resultado["sw"] = true;
        } catch (\Exception $e) {
            Log::error('ERROR EN CPT ENFERMERIA FIRMA PDF: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);
        }
        return response()->json($resultado);
    }

    public function generarPdfParaFirmaCpt($id_cpt)
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

        $id_establecimiento = Auth::user()->id_establecimiento; // id_establecimiento = 76 - 1 mdico  
        $model_establecimiento = new \App\Models\Establecimiento();
        $establecimiento =  $model_establecimiento->GetById($id_establecimiento);
        
        $nombre_establecimiento = $establecimiento[0]->nombre;
        $model_cpt = new Enfermeria_cpt;
        $cpt_cabecera = $model_cpt->getver_nota_ingreso_cpt($id_cpt);
        $cpt_cpms = $model_cpt->getver_nota_ingreso_cpt_cpms($id_cpt);
        $id_tipo_atencion = $cpt_cabecera[0]->id_tipo_atenciones; //TIPO ATENCION
        $id_ext_enfermeria = $cpt_cabecera[0]->id_ext; //ID DE EMERGENCIA O HOSPITALIZACION
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
        $datos_paciente = $this->getDatosPaciente($cpt_cabecera[0]->id_asegurado);
        $pdf = \PDF::loadView(
            'frontend.hospitalizacion_enfermeria.cpt.pdf.pdf_nota_ingreso_cpt',
            compact(
                'id_tipo_atencion',
                'datos_paciente',
                'cpt_cabecera',
                'cpt_cpms',
                'datos_atencion',
                'nombre_establecimiento',
                'fechaActual',
                'horaActual',
                'codigo_cups'
            )
        );
        $pdf->setPaper('A4', 'portrait');
        file_put_contents($path . "cpt_enfermeria_" . $id_cpt . ".pdf", $pdf->output());
    }

    public function actualizarFirmaConTokenCpt(Request $request)
    {
        $id = intval($request->id_resol);
        $cpt = Enfermeria_cpt::where('id', $id)->first();
        if ($cpt->fecha_firma == "") {
            $cpt->fecha_firma = date("d-m-Y H:i:s");
            $cpt->ruta_firma = date("Y/m/d") . "/cf_cpt_enfermeria_" . $id . ".pdf";
            $cpt->flag_firma = 1;
            $cpt->save();
        }
        return "ok";
    }

    public function postArgumentsCpt(Request $request)
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
}
