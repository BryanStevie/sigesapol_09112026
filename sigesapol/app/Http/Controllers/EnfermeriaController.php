<?php

namespace App\Http\Controllers;

use App\Models\CambioCama;
use App\Models\Nota_enfermeria;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\antecedentesEnfermeria;
use App\Models\Asegurado;
use App\Models\Balance_hidrico;
use App\Models\Consultorio;
use App\Models\Cama;
use App\Models\Emergencia;
use App\Models\Enfermeria;
use App\Models\Hospitalizacione;
use App\Models\enfermeriaDetalle;
use App\Models\Establecimiento;
use App\Models\IntervencionTerapeutica;
use App\Models\Kardexe;
use App\Models\KardexEnfermerias;
use App\Models\Medico;
use App\Models\Monitoreo;
use App\Models\Nota_enfermeria_evoluciones;
use App\Models\Prestacion_procedimiento;
use App\Models\Prestaciones;
use App\Models\Receta_diagnostico;
use App\Models\Receta_vale;
use Archive7z\Archive7z;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade as PDF;
use Maatwebsite\Excel\Facades\Excel;
use QrCode;


class EnfermeriaController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    public function buscar_datos_prestacion_enfermeria($id_prestacion)
    {
        /* $query = "select d.id as diagnostico_id, d.codigo as codigo_diagnostico, d.nombre as nombre_diagnostico, td.descripcion as tipo_diagnostico from prestaciones p
                inner join prestaciones_diagnosticos pd ON pd.prestacion_id = p.id
                inner join diagnosticos d on d.id = pd.diagnostico_id 
                inner join tipo_diagnosticos td on td.id = pd.id_tipo_diagnostico
                where p.id = :id_prestacion"; */
        $query = "select DISTINCT ON (d.id)
                    d.id as diagnostico_id, 
                    d.codigo as codigo_diagnostico, 
                    d.nombre as nombre_diagnostico, 
                    td.descripcion as tipo_diagnostico, 
                    rd.id_evaluacion,
                    p.tratamiento_auxiliar,
                    case WHEN(rd.id_evaluacion::int) = 0 then 'ATENCIÓN' else 'EVALUACIÓN' END as clasificacion 
                from prestaciones p
                inner join receta_diagnosticos rd ON rd.id_prestacion = p.id
                inner join diagnosticos d on d.id = rd.id_diagnostico 
                inner join tipo_diagnosticos td on td.id = rd.id_tipo_diagnostico 
                where p.id = :id_prestacion
                order by d.id, rd.created_at desc";
        $diagnosticos = DB::select($query, ['id_prestacion' => $id_prestacion]);
        return response()->json($diagnosticos);
    }

    public function buscar_infusiones_monitoreo_enfermeria(Request $request)
    {
        $query = $request->get('data');
        return response()->json(IntervencionTerapeutica::GetIntervencionesTipo2($query));
    }

    function registrar_rotacion_cama(Request $request)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $prestacion = Prestaciones::where("id", "=", $request->id_prestacion)->first();
        if (!isset($prestacion) || !isset($prestacion->id_ext)) {
            session()->flash('error', "No existe registro de la prestacion");
            return redirect('/emergencia_by_enfermeria');
        }

        $estado_ubicacion = 1;
        $fecha_hora = null;
        $id_cama = 0;
        $medico_id = null;
        if (strval($request->rotacion_cama) === 'true') {
            $estado_ubicacion = 2;
            // $fecha_hora = Carbon::createFromFormat('d/m/Y H:i', $request->fecha_rotacion . " ". $request->hora_rotacion);
            $fecha_hora = Carbon::createFromFormat('Y-m-d H:i', $request->fecha_rotacion . " " . $request->hora_rotacion);
            $id_cama = $request->cama_rotacion;
        }
        if (strval($request->traslacion_cama) === 'true') {
            $estado_ubicacion = 3;
            // $fecha_hora = Carbon::createFromFormat('d/m/Y H:i', $request->fecha_traslacion . " ". $request->hora_traslacion);
            $fecha_hora = Carbon::createFromFormat('Y-m-d H:i', $request->fecha_traslacion . " " . $request->hora_traslacion);
            $id_cama = $request->cama_traslacion;
            $medico_id = $request->medico_traslacion;
        }
        $fecha_hora_inicio = $fecha_hora->format('Y-m-d H:i:s');

        $emergenciaUpdated = Emergencia::where("id", "=", $prestacion->id_ext)->first();

        $firstCama = CambioCama::where("id_prestacion", $request->id_prestacion)->first();
        if (!isset($firstCama)) {
            $firstCama = CambioCama::create([
                'id_cama_atencion' => $emergenciaUpdated->cama_estado,
                'estado_ubicacion' => 4,
                'fecha_hora_inicio' => $prestacion->fecha_atencion,
                'id_cama' => $emergenciaUpdated->cama_estado,
                'id_prestacion' => $request->id_prestacion,
                'id_evolucion' => 0,
                'estado' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => null,
                'medico_id' => $medico_id,
            ]);
        }

        $newCambioCama = new CambioCama();
        $newCambioCama->id_cama_atencion = $request->cama_id;
        $newCambioCama->estado_ubicacion = $estado_ubicacion;
        $newCambioCama->fecha_hora_inicio = $fecha_hora_inicio;
        $newCambioCama->id_cama = $id_cama;
        $newCambioCama->id_prestacion = $request->id_prestacion;
        $newCambioCama->id_evolucion = 0;
        $newCambioCama->estado = 1;
        $newCambioCama->created_at = Carbon::now();
        $newCambioCama->updated_at = null;
        $newCambioCama->medico_id = $medico_id;
        $newCambioCama->save();

        if (!isset($newCambioCama->id)) {
            session()->flash('error', "Hubo un problema al registrar el cambio de cama, vuelva a intentarlo nuevamente!");
            return redirect('/emergencia_by_enfermeria');
        }

        $emergenciaUpdated->cama_estado = $id_cama;
        $emergenciaUpdated->save();

        session()->flash('success', "Se registro el cambio de cama exitosamente!");
        if ($request->id_tipo_atencion == 3) {
            return redirect('/hospitalizacion_by_enfermeria');
        }
        return redirect('/emergencia_by_enfermeria');
    }

    function registrar_rotacion_cama_hospi(Request $request)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        /* $prestacion = Prestaciones::where("id", "=", $request->id_prestacion)->first();
        if(!isset($prestacion) || !isset($prestacion->id_ext)){
            session()->flash('error', "No existe registro de la prestacion");
            return redirect('/emergencia_by_enfermeria');
        } */

        $cama_origen_id = $request->cama_id;
        $estado_ubicacion = 1;
        $fecha_hora = null;
        $id_cama = 0;
        $medico_id = null;
        $medico_model = new Medico();
        $medico = $medico_model->GetMostrarMedicoByDNI(Auth::user()->dni);
        if (isset($medico)) {
            $medico_id = $medico->id;
        }

        if (strval($request->rotacion_cama) === 'true') {
            $estado_ubicacion = 2;
            $fecha_hora = Carbon::createFromFormat('d/m/Y H:i', $request->fecha_rotacion . " " . $request->hora_rotacion);
            $id_cama = $request->cama_rotacion;
        }
        // if (strval($request->traslacion_cama) === 'true') {
        //     $estado_ubicacion = 3;
        //     $fecha_hora = Carbon::createFromFormat('d/m/Y H:i', $request->fecha_traslacion . " ". $request->hora_traslacion);
        //     $id_cama = $request->cama_traslacion;
        //     $medico_id = $request->medico_traslacion;
        // }
        $fecha_hora_inicio = $fecha_hora->format('Y-m-d H:i:s');

        $hospiUpdated = Hospitalizacione::where("id", "=", $request->id_hospi)->first();

        $firstCama = CambioCama::where("id_ext", $request->id_hospi)->where('id_tipo_atencion', 3)->first();
        $lastCambioCama = CambioCama::where("id_ext", $request->id_hospi)->where('id_tipo_atencion', 3)->latest("id")->first();
        /* dd($lastCambioCama); */

        if (!isset($firstCama)) {
            $firstCama = CambioCama::create([
                'id_cama_atencion' => $hospiUpdated->id_cama,
                'estado_ubicacion' => 4,
                'fecha_hora_inicio' => $hospiUpdated->fecha_ingreso,
                'id_cama' => $hospiUpdated->id_cama,
                'id_prestacion' => $request->id_prestacion,
                'id_evolucion' => 0,
                'estado' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => null,
                'medico_id' => $medico_id,
            ]);
        }

        $newCambioCama = new CambioCama();
        $newCambioCama->id_cama_atencion = $request->cama_id;
        $newCambioCama->estado_ubicacion = $estado_ubicacion;
        $newCambioCama->fecha_hora_inicio = $fecha_hora_inicio;
        $newCambioCama->id_cama = $id_cama;
        $newCambioCama->id_prestacion = null;
        $newCambioCama->id_tipo_atencion = 3;
        $newCambioCama->id_ext = $request->id_hospi;
        $newCambioCama->id_evolucion = 0;
        $newCambioCama->estado = 1;
        $newCambioCama->created_at = Carbon::now();
        $newCambioCama->updated_at = null;
        $newCambioCama->medico_id = $medico_id;
        $newCambioCama->save();

        if (!isset($newCambioCama->id)) {
            session()->flash('error', "Hubo un problema al registrar el cambio de cama, vuelva a intentarlo nuevamente!");
            return redirect('/emergencia_by_enfermeria');
        }

        $cama = Cama::where("id", "=", $id_cama)->select("id_cuarto as cuarto_id")->first();

        /* $hospiUpdated->id_cama = $id_cama; */
        $hospiUpdated->id_cama_estado = $id_cama;
        $hospiUpdated->id_cuarto_origen = $hospiUpdated->id_cuarto;
        $hospiUpdated->id_cuarto = $cama->cuarto_id;
        $hospiUpdated->save();

        // Ocupar cama destino
        if (isset($id_cama)) {
            $cama1 = Cama::where("id", $id_cama)->first();
            Cama::where('id', $id_cama)->update([
                'estado' => 1,
                'situacion' => '4',
            ]);
        }

        // Liberar cama origen
        if (isset($cama_origen_id)) {
            $cama2 = Cama::where("id", $cama_origen_id)->first();
            Cama::where('id', $cama_origen_id)->update([
                'estado' => 0,
                'situacion' => '1',
            ]);
        }

        if (isset($lastCambioCama->id_cama)) {
            Cama::where('id', $lastCambioCama->id_cama)->update([
                'estado' => 0,
                'situacion' => '1',
            ]);
        }

        /* dd($cama2); */
        /* dd($cama1); */

        session()->flash('success', "Se registro el cambio de cama exitosamente!");
        if ($request->id_tipo_atencion == 3) {
            return redirect('/hospitalizacion_by_enfermeria');
        }
        return redirect('/emergencia_by_enfermeria');
    }

    function obtenerCabeceraById($id)
    {
        $enfermeriaModel = new Enfermeria;
        $notaEnfermeria = Enfermeria::where('id', '=', $id)->first();
        $existeAtencionMedica = Prestaciones::where('prestaciones.id_ext', '=', $notaEnfermeria->id_ext_enfermeria)->exists();
        if ($existeAtencionMedica) {
            $result = $enfermeriaModel->ObtenerCabeceraPdfById($id);
        } else {
            $result = $enfermeriaModel->ObtenerCabeceraPdfById_sa($id);
        }
        return response()->json($result);
    }

    public function lista_pcientes_enfermeria(Request $request)
    {

        $model_enfermeria = new enfermeria();
        $p[] = $request->fecha_ini;
        $p[] = $request->fecha_fin;
        $p[] = Auth::user()->id_establecimiento;
        $p[] = $request->nombre_beneficiario;
        $p[] = $request->dni_beneficiario;
        $p[] = $request->seleccionar_estado;
        $p[] = $request->tipo_atencion;
        $p[] = $request->id_prioridad;
        $p[] = $request->id_topico;
        $p[] = $request->NumeroPagina;
        $p[] = $request->NumeroRegistros;
        $data = $model_enfermeria->lista_pcientes_enfermeria($p);
        $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;
        $result["PageStart"] = $request->NumeroPagina;
        $result["pageSize"] = $request->NumeroRegistros;
        $result["SearchText"] = "";
        $result["ShowChildren"] = true;
        $result["iTotalRecords"] = $iTotalDisplayRecords;
        $result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
        $result["aaData"] = $data;
        echo json_encode($result);
    }

    public function consultaExternaListarNotaEnfermeria()
    {
        $userSession = Auth::user();

        $model_consultorio = new Consultorio;
        $consultorio = $model_consultorio->ShowConsultorioTipoAtencion($userSession->id_establecimiento, 1);

        return view('frontend.enfermeria.consulta_externa.listar_nota_enfermeria', compact('consultorio'));
    }

    public function obtenerCitasEnfermeria(Request $request)
    {
        $enfermeria = new Enfermeria;

        $offset = ($request->pagina - 1) * $request->cantidad_pagina;
        $limit = $request->cantidad_pagina;

        $id_establecimiento = Auth::user()->id_establecimiento;

        $p = [];
        $p[0] = $request->fecha_atencion_inicio;
        $p[1] = $request->fecha_atencion_fin;
        $p[2] = $request->nro_doc_ident;
        $p[3] = $request->estado;
        $p[4] = $request->id_servicio;
        $p[5] = $id_establecimiento;
        $p[6] = $limit;
        $p[7] = $offset;

        $result = $enfermeria->obtenerCitasEnfermeria($p);

        $recordsTotal = isset($result[0]) && isset($result[0]->total_registros) ? $result[0]->total_registros : 0;

        $response = [
            "draw" => intval($request->draw),
            "page" => intval($request->pagina),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsTotal,
            "data" => isset($result) ? $result : []
        ];

        return response()->json($response);
    }

    public function consultaExternaRegistraNotaIngreso()
    {
        $userSession = Auth::user();

        // $model_consultorio = new Consultorio;
        // $consultorio = $model_consultorio->ShowConsultorioTipoAtencion($userSession->id_establecimiento, 1);

        $medicoModel = new Medico;
        $medico = $medicoModel->GetMostrarMedicoByDNI($userSession->dni);

        if (is_object($medico)) :
            $establecimientoModel = new Establecimiento;
            $establecimientos = $establecimientoModel->ShowAll();
            return view('frontend.enfermeria.consulta_externa.registrar_nota_ingreso', compact('medico', 'establecimientos'));
        else:
            session()->flash('error', "No existe personal registrado en la opcion de RR:HH -> Personal de Salud, si tiene inconveniente para el ingreso a esta opcion coordine con el responsable del establecimiento o comuniquese con el administrador del Sistema");
            return redirect('/dashboard');

        endif;
    }

    public function consultaExternaEditarEnfermeria($enfermeria_id)
    {
        $userSession = Auth::user();

        $model_consultorio = new Consultorio;
        $consultorio = $model_consultorio->ShowConsultorioTipoAtencion($userSession->id_establecimiento, 1);

        $establecimientoModel = new Establecimiento;
        $establecimientos = $establecimientoModel->ShowAll();

        $enfermeriaModel = new Enfermeria;
        $enfermeria = $enfermeriaModel->obtenerEnfermeriaConsultaExternaPorId($enfermeria_id);

        $prestacion = Prestaciones::where("id", $enfermeria->prestacion_id)->first();

        $signosVitales = enfermeriaDetalle::where('id_enfermeria', $enfermeria_id)->get();

        $procedimientos = [];
        if (isset($enfermeria) && isset($enfermeria->prestacion_id)) {
            // $procedimientos = Prestacion_procedimiento::select(
            //     'prestacion_procedimientos.id as prestacion_procedimiento_id',
            //     'prestacion_procedimientos.cantidad as prestacion_procedimiento_cantidad',
            //     'prestacion_procedimientos.id_estado_reg as prestacion_procedimiento_id_estado_reg',
            //     'procedimientos.id as prestacion_id',
            //     'procedimientos.codigo as procedimiento_codigo',
            //     'procedimientos.descripcion as procedimiento_descripcion',
            //     'diagnosticos.id as diagnostico_id',
            //     'diagnosticos.nombre as diagnostico_nombre'
            // )
            //     ->join('procedimientos', 'prestacion_procedimientos.id_procedimiento', '=', 'procedimientos.id')
            //     ->leftJoin('diagnosticos', 'prestacion_procedimientos.id_diagnostico', '=', 'diagnosticos.id')
            //     ->where('prestacion_procedimientos.id_prestacion', $enfermeria->prestacion_id)
            //     ->whereNotNull('prestacion_procedimientos.id_enfermeria')
            //     ->where('prestacion_procedimientos.id_estado_reg', 1)
            //     ->get();
            $procedimientos = DB::table('prestacion_procedimientos as pp')

                    ->join('procedimientos as p', 'pp.id_procedimiento', '=', 'p.id')
                    ->leftJoin('diagnosticos as d', 'pp.id_diagnostico', '=', 'd.id')
                    ->select(
                        'pp.id as prestacion_procedimiento_id',
                        'pp.cantidad as prestacion_procedimiento_cantidad',
                        'pp.id_estado_reg as prestacion_procedimiento_id_estado_reg',
                        'p.id as procedimiento_id',
                        'p.codigo as procedimiento_codigo',
                        'p.descripcion as procedimiento_descripcion',
                        'd.id as diagnostico_id',
                        'd.nombre as diagnostico_nombre'
                    )
                    ->where('pp.id_prestacion', '=', $enfermeria->prestacion_id)
                    ->where('pp.id_enfermeria', '=', $enfermeria_id)
                    ->where('pp.id_estado_reg', 1)
                    ->orderBy('pp.id', 'asc')
                    ->get();

        }
        
        return view('frontend.enfermeria.consulta_externa.editar_nota_ingreso', compact('enfermeria', 'signosVitales', 'procedimientos', 'consultorio', 'establecimientos', "prestacion"));
    }

    public function consultaExternaVerNotaEnfermeria($enfermeria_id)
    {
        $enfermeriaModel = new Enfermeria;
        $enfermeria = $enfermeriaModel->obtenerEnfermeriaConsultaExternaPorId($enfermeria_id);

        $signosVitales = enfermeriaDetalle::where('id_enfermeria', $enfermeria_id)->get();

        $procedimientos = [];
        if (isset($enfermeria) && isset($enfermeria->prestacion_id)) {
            /*$procedimientos = Prestacion_procedimiento::select(
                'prestacion_procedimientos.id as prestacion_procedimiento_id',
                'prestacion_procedimientos.cantidad as prestacion_procedimiento_cantidad',
                'prestacion_procedimientos.id_estado_reg as prestacion_procedimiento_id_estado_reg',
                'procedimientos.id as prestacion_id',
                'procedimientos.codigo as procedimiento_codigo',
                'procedimientos.descripcion as procedimiento_descripcion',
                'diagnosticos.id as diagnostico_id',
                'diagnosticos.nombre as diagnostico_nombre'
            )
                ->join('procedimientos', 'prestacion_procedimientos.id_procedimiento', '=', 'procedimientos.id')
                ->leftJoin('diagnosticos', 'prestacion_procedimientos.id_diagnostico', '=', 'diagnosticos.id')
                ->where('prestacion_procedimientos.id_prestacion', $enfermeria->prestacion_id)
                ->whereNotNull('prestacion_procedimientos.id_enfermeria')
                ->get();*/
            // $procedimientos = DB::table('prestacion_procedimientos as pp')
            //         ->join('procedimientos as p', 'pp.id_procedimiento', '=', 'p.id')
            //         ->leftJoin('diagnosticos as d', 'pp.id_diagnostico', '=', 'd.id')
            //         ->select(DB::raw('DISTINCT ON (p.id) pp.id AS prestacion_procedimiento_id, pp.cantidad AS prestacion_procedimiento_cantidad, pp.id_estado_reg AS prestacion_procedimiento_id_estado_reg,
            //             p.id AS procedimiento_id, p.codigo AS procedimiento_codigo, p.descripcion AS procedimiento_descripcion, d.id AS diagnostico_id, d.nombre AS diagnostico_nombre
            //         '))
            //         ->where('pp.id_prestacion', '=', $enfermeria->prestacion_id)
            //         ->whereNotNull('pp.id_enfermeria')
            //         ->where('pp.id_estado_reg', 1)
            //         ->orderBy('p.id', 'asc')
            //         ->orderBy('pp.created_at', 'desc')
            //         ->get();
           /* $procedimientos = DB::table('enfermerias as e')
                    ->join('prestacion_procedimientos as pp', 'e.id_prestacion', '=', 'pp.id_prestacion')
                    ->leftJoin('diagnosticos as d', 'pp.id_diagnostico', '=', 'd.id')
                    ->join('procedimientos as p', 'pp.id_procedimiento', '=', 'p.id')
                    ->select(
                        'pp.id as prestacion_procedimiento_id',
                        'pp.cantidad as prestacion_procedimiento_cantidad',
                        'pp.id_estado_reg as prestacion_procedimiento_id_estado_reg',
                        'p.id as procedimiento_id',
                        'p.codigo as procedimiento_codigo',
                        'p.descripcion as procedimiento_descripcion',
                        'd.id as diagnostico_id',
                        'd.nombre as diagnostico_nombre'
                    )
                    ->where('e.id', $enfermeria->enfermeria_id)
                    ->whereNotNull('pp.id_enfermeria')
                    ->where('pp.id_estado_reg', 1)
                    ->get(); */

                    $procedimientos = DB::table('prestacion_procedimientos as pp')
                        ->join('procedimientos as p', 'pp.id_procedimiento', '=', 'p.id')
                        ->leftJoin('diagnosticos as d', 'pp.id_diagnostico', '=', 'd.id')
                        ->select(
                            'pp.id as prestacion_procedimiento_id',
                            'pp.cantidad as prestacion_procedimiento_cantidad',
                            'pp.id_estado_reg as prestacion_procedimiento_id_estado_reg',
                            'p.id as procedimiento_id',
                            'p.codigo as procedimiento_codigo',
                            'p.descripcion as procedimiento_descripcion',
                            'd.id as diagnostico_id',
                            'd.nombre as diagnostico_nombre'
                        )
                        ->where('pp.id_prestacion', '=', $enfermeria->prestacion_id)
                        ->where('pp.id_enfermeria', '=', $enfermeria_id)
                        ->where('pp.id_estado_reg', 1)
                        ->orderBy('pp.id', 'asc')
                        ->get();


        }
        

        return view('frontend.enfermeria.consulta_externa.ver_detalle_nota_ingreso', compact('enfermeria', 'signosVitales', 'procedimientos'));
    }

    public function consultaExternaActualizarEnfermeria(Request $request, $id)
    {
        if (!Auth::check()) :
            return response()->json([
                "message" => "no tiene session el usuario"
            ], 401);
        endif;

        $fechaAtencionEnfermeriaCarbon = Carbon::createFromFormat('d/m/Y H:i', $request->fecha_atencion_enfermeria . ' ' . $request->hora_atencion_enfermeria);
        $errores = Carbon::getLastErrors();
        if ($errores['warning_count'] > 0 || $errores['error_count'] > 0) {
            return response()->json([
                "message" => "Formato de fecha y/o hora inválido.",
            ], 422);
        }
        $fechaAtencionEnfermeria = $fechaAtencionEnfermeriaCarbon->format('Y-m-d H:i:s.000');

        $updateEnfermeria = Enfermeria::find($id);
        $updateEnfermeria->update([
            'created_at' => $fechaAtencionEnfermeria,
            'updated_us' => Auth::user()->id,
            'updated_at' => Carbon::now(),
            'observacion_enfermeria' => $request->observacion_enfermeria
        ]);

        foreach ($request->caracter_fisicos as $key => $caracter_fisico) {
            $updatenCaracterFisico = enfermeriaDetalle::find($caracter_fisico['id']);
            $updatenCaracterFisico->update([
                'descripcion' => $caracter_fisico['value'],
                'updated_us' => Auth::user()->id,
                'updated_at' => Carbon::now(),
            ]);
        }

        $arrayCardiologia = $request->antecedentes['cardiologia'];

        $updateAntecedenteEnfermeria = antecedentesEnfermeria::find($request->antecedentes_enfermeria_id);
        $updateAntecedenteEnfermeria->update([
            'id_hipertension' => $request->antecedentes['id_hipertension'],
            'descripcion_hipertension' => $request->antecedentes['descripcion_hipertension'],
            'id_diabetes_millitus' => $request->antecedentes['id_diabetes_millitus'],
            'descripcion_diabetes_millitus' => $request->antecedentes['descripcion_diabetes_millitus'],
            'id_alergia' => $request->antecedentes['id_alergia'],
            'descripcion_alergia' => $request->antecedentes['descripcion_alergia'],
            'id_hemodialisis' => $request->antecedentes['id_hemodialisis'],
            'descripcion_hemodialisis' => $request->antecedentes['descripcion_hemodialisis'],
            'descripcion_observacion' => $request->antecedentes['descripcion_observacion'],
            'descripcion_otro' => $request->antecedentes['descripcion_otro'],
            'cardio_parametro_1' => isset($arrayCardiologia[0]) ? $arrayCardiologia[0] : null,
            'cardio_parametro_2' => isset($arrayCardiologia[1]) ? $arrayCardiologia[1] : null,
            'cardio_parametro_3' => isset($arrayCardiologia[2]) ? $arrayCardiologia[2] : null,
            'cardio_parametro_4' => isset($arrayCardiologia[3]) ? $arrayCardiologia[3] : null,
            'updated_us' => Auth::user()->id,
            'updated_at' => Carbon::now(),
        ]);

        if (isset($request->procedimientos) && count($request->procedimientos) > 0) {
            foreach ($request->procedimientos as $key => $procedimiento) {
                if (isset($procedimiento['prestacion_procedimiento_id']) && !empty($procedimiento['prestacion_procedimiento_id'])) {
                    $updateProcedimiento = Prestacion_procedimiento::find($procedimiento['prestacion_procedimiento_id']);
                    $updateProcedimiento->update([
                        "id_procedimiento" => $procedimiento["id_procedimiento"],
                        "updated_at" => Carbon::now(),
                        "updatet_us" => Auth::user()->id,
                        "cantidad" => $procedimiento["procedimiento_cantidad"],
                        "id_diagnostico" => $procedimiento["procedimiento_diagnostico"],
                        "id_enfermeria" => $id,
                    ]);
                } else {
                    $newProcedimiento = Prestacion_procedimiento::create([
                        "id_prestacion" => $request->id_prestacion,
                        "id_procedimiento" => $procedimiento["id_procedimiento"],
                        "id_estado_reg" => 1,
                        "created_at" => Carbon::now(),
                        "created_us" => Auth::user()->id,
                        "cantidad" => $procedimiento["procedimiento_cantidad"],
                        "id_diagnostico" => $procedimiento["procedimiento_diagnostico"],
                        "id_enfermeria" => $id,
                    ]);
                }
            }
        }

        if ($request->procedimientos_eliminados && count($request->procedimientos_eliminados) > 0) {
            foreach ($request->procedimientos_eliminados as $procedimientoElimando) {
                $updateProcedimientoEliminado = Prestacion_procedimiento::find($procedimientoElimando);
                $updateProcedimientoEliminado->update([
                    "id_estado_reg" => 0,
                    "updated_at" => Carbon::now(),
                    "updatet_us" => Auth::user()->id,
                ]);
            }
        }

        session()->flash('success', "La nota de enfermería en consulta externa ha sido actualizada exitosamente.!");
        return response()->json([
            "message" => "La nota de enfermería en consulta externa ha sido actualizada exitosamente.!",
        ], 201);
    }

    public function consultaExternaGenerarPDFEnfermeria($enfermeria_id)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $enfermeriaModel = new Enfermeria;
        $enfermeria = $enfermeriaModel->obtenerEnfermeriaConsultaExternaPorId($enfermeria_id);

        $signosVitales = enfermeriaDetalle::where('id_enfermeria', $enfermeria_id)->get();

        $procedimientos = [];
        $diagnosticos = [];
        if (isset($enfermeria) && isset($enfermeria->prestacion_id)) {
            // $procedimientos = Prestacion_procedimiento::select(
            //     'prestacion_procedimientos.id as prestacion_procedimiento_id',
            //     'prestacion_procedimientos.cantidad as prestacion_procedimiento_cantidad',
            //     'prestacion_procedimientos.id_estado_reg as prestacion_procedimiento_id_estado_reg',
            //     'procedimientos.id as prestacion_id',
            //     'procedimientos.codigo as procedimiento_codigo',
            //     'procedimientos.descripcion as procedimiento_descripcion',
            //     'diagnosticos.id as diagnostico_id',
            //     'diagnosticos.nombre as diagnostico_nombre'
            // )
            //     ->join('procedimientos', 'prestacion_procedimientos.id_procedimiento', '=', 'procedimientos.id')
            //     ->leftJoin('diagnosticos', 'prestacion_procedimientos.id_diagnostico', '=', 'diagnosticos.id')
            //     ->where('prestacion_procedimientos.id_prestacion', $enfermeria->prestacion_id)
            //     ->whereNotNull('prestacion_procedimientos.id_enfermeria')
            //     ->get();

            $procedimientos = DB::table('prestacion_procedimientos as pp')
                    ->join('procedimientos as p', 'pp.id_procedimiento', '=', 'p.id')
                    ->leftJoin('diagnosticos as d', 'pp.id_diagnostico', '=', 'd.id')
                    ->select(
                        'pp.id as prestacion_procedimiento_id',
                        'pp.cantidad as prestacion_procedimiento_cantidad',
                        'pp.id_estado_reg as prestacion_procedimiento_id_estado_reg',
                        'p.id as procedimiento_id',
                        'p.codigo as procedimiento_codigo',
                        'p.descripcion as procedimiento_descripcion',
                        'd.id as diagnostico_id',
                        'd.nombre as diagnostico_nombre'
                    )
                    ->where('pp.id_prestacion', '=', $enfermeria->prestacion_id)
                    ->where('pp.id_enfermeria', '=', $enfermeria_id)
                    ->where('pp.id_estado_reg', 1)
                    ->orderBy('pp.id', 'asc')
                    ->get();

            // $diagnosticos = DB::table('prestaciones as p')
            //     ->join('receta_diagnosticos as rd', 'rd.id_prestacion', '=', 'p.id')
            //     ->join('diagnosticos as d', 'd.id', '=', 'rd.id_diagnostico')
            //     ->join('tipo_diagnosticos as td', 'td.id', '=', 'rd.id_tipo_diagnostico')
            //     ->select('d.codigo as codigo_diagnostico', 'd.nombre as nombre_diagnostico', 'td.descripcion as tipo_diagnostico')
            //     ->where('p.id', $enfermeria->prestacion_id)
            //     ->orderBy('rd.created_at', 'desc')
            //     ->get();
            $diagnosticos = DB::table('receta_diagnosticos as rd')
                ->join('prestaciones as p', 'rd.id_prestacion', '=', 'p.id')
                ->join('diagnosticos as d', 'd.id', '=', 'rd.id_diagnostico')
                ->join('tipo_diagnosticos as td', 'td.id', '=', 'rd.id_tipo_diagnostico')
                ->select(DB::raw('DISTINCT ON (d.id) d.id AS id_diagnostico, d.codigo AS codigo_diagnostico, d.nombre AS nombre_diagnostico, td.descripcion AS tipo_diagnostico'))
                ->where('p.id', '=', $enfermeria->prestacion_id)
                ->orderBy('d.id', 'asc')
                ->orderBy('rd.created_at', 'desc')
                ->get();
        }

        $usuario = Auth::user();

        $fechaActual = Carbon::now()->format('d-m-Y');
        $horaActual = Carbon::now()->format('H:i:s');
        $pdf = PDF::loadView('frontend.enfermeria.consulta_externa.pdf_nota_enfermeria', compact(
            'enfermeria',
            'signosVitales',
            'procedimientos',
            'diagnosticos',
            'usuario',
            'fechaActual',
            'horaActual'
        ));
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'portable');
        return $pdf->stream('nota_enfermeria_consulta_externa.pdf');
    }

    public function consultaExternaGenerarPDFFirmarEnfermeria($enfermeria_id)
    {
        $path = public_path('tmp/pdf');
        if (!is_dir($path)) {
            mkdir($path);
        }
        $path = public_path('tmp/pdf/sin_firma/');
        if (!is_dir($path)) {
            mkdir($path);
        }

        $enfermeriaModel = new Enfermeria;
        $enfermeria = $enfermeriaModel->obtenerEnfermeriaConsultaExternaPorId($enfermeria_id);

        $signosVitales = enfermeriaDetalle::where('id_enfermeria', $enfermeria_id)->get();

        $procedimientos = [];
        $diagnosticos = [];
        if (isset($enfermeria) && isset($enfermeria->prestacion_id)) {
            $procedimientos = Prestacion_procedimiento::select(
                'prestacion_procedimientos.id as prestacion_procedimiento_id',
                'prestacion_procedimientos.cantidad as prestacion_procedimiento_cantidad',
                'prestacion_procedimientos.id_estado_reg as prestacion_procedimiento_id_estado_reg',
                'procedimientos.id as prestacion_id',
                'procedimientos.codigo as procedimiento_codigo',
                'procedimientos.descripcion as procedimiento_descripcion',
                'diagnosticos.id as diagnostico_id',
                'diagnosticos.nombre as diagnostico_nombre'
            )
                ->join('procedimientos', 'prestacion_procedimientos.id_procedimiento', '=', 'procedimientos.id')
                ->leftJoin('diagnosticos', 'prestacion_procedimientos.id_diagnostico', '=', 'diagnosticos.id')
                ->where('prestacion_procedimientos.id_prestacion', $enfermeria->prestacion_id)
                ->where('prestacion_procedimientos.id_enfermeria', $enfermeria_id)
                ->where('prestacion_procedimientos.id_estado_reg', 1)
                ->get();

            $diagnosticos = DB::table('prestaciones as p')
                ->join('receta_diagnosticos as rd', 'rd.id_prestacion', '=', 'p.id')
                ->join('diagnosticos as d', 'd.id', '=', 'rd.id_diagnostico')
                ->join('tipo_diagnosticos as td', 'td.id', '=', 'rd.id_tipo_diagnostico')
                ->select('d.codigo as codigo_diagnostico', 'd.nombre as nombre_diagnostico', 'td.descripcion as tipo_diagnostico')
                ->where('p.id', $enfermeria->prestacion_id)
                ->orderBy('rd.created_at', 'desc')
                ->get();
        }

        $usuario = Auth::user();

        $fechaActual = Carbon::now()->format('d-m-Y');
        $horaActual = Carbon::now()->format('H:i:s');

        $carpeta = date("Y") . "/" . date("m") . "/" . date("d");
        $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/" . $carpeta . "/cf_nota_enfermeria_consulta_externa_" . $enfermeria_id . ".pdf";
        $codigo_qr = QrCode::format('png')->size(150)->generate("https://sigesapol.dirsapolpnp.gob.pe/" . $carpeta . "/cf_nota_enfermeria_consulta_externa_" . $enfermeria_id . ".pdf");
        $pdf = PDF::loadView('frontend.enfermeria.consulta_externa.pdf_firma_nota_enfermeria', compact('enfermeria', 'signosVitales', 'procedimientos', 'diagnosticos', 'usuario', 'fechaActual', 'horaActual', "url_descarga", "codigo_qr"));
        // $pdf = \PDF::loadView('pdf.firma_kardex_enfermeria', compact("kardexEnfermeria", "enfermeria", "diagnosticos", "examenLaboratorioKardexes", "examenImagenKardexes", "cuidadoEnfermeros", "interconsultas", "kardex_medicamento_enfermeria", "url_descarga", "codigo_qr"));
        $pdf->setPaper('A4', 'portrait');
        $pdf->getDomPDF()->set_option("enable_php", true);
        file_put_contents($path . "nota_enfermeria_consulta_externa_" . $enfermeria_id . ".pdf", $pdf->output());
    }

    public function consultaExternaFirmarPDFEnfermeria(Request $request)
    {
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
        $enfermeria_id = $request->enfermeria_id;
        $csv .= "_" . date("YmdHis");
        Excel::create($csv, function ($excel) use ($enfermeria_id) {
            $excel->sheet('GENERAL', function ($sheet) use ($enfermeria_id) {
                $variable = [];
                Log::info(["enfermeria_id" => $enfermeria_id]);
                $this->consultaExternaGenerarPDFFirmarEnfermeria($enfermeria_id);
                array_push($variable, array(url() . '/tmp/pdf/sin_firma/nota_enfermeria_consulta_externa_' . $enfermeria_id . '.pdf', url() . '/upload_firma/?name=cf_nota_enfermeria_consulta_externa_' . $enfermeria_id . '.pdf&id_tipo=nota_enfermeria_consulta_externa&id_resol=' . $enfermeria_id, 'cf_nota_enfermeria_ce_' . $enfermeria_id . '.pdf', '0', '45', '350', '70', 'Fecha de Firma: <DATE> Firmante: <SIGNER>', url() . '/images/escudo.jpg', '1', '7'));
                $sheet->fromArray($variable, null, '', false, false);
            });
        })->store('csv', public_path('tmp/csv'));
        $obj = new Archive7z(public_path('tmp/zip/sin_firma/' . $csv . '.7z'));
        $obj->addEntry(public_path('/tmp/pdf/sin_firma/nota_enfermeria_consulta_externa_' . $enfermeria_id . '.pdf'));
        $resultado["csv"] = $csv;
        $resultado["sw"] = true;
        return response()->json($resultado);
    }

    public function consultaExternaActualizarFirmaEnfermeria(Request $request, $enfermeria_id)
    {
        $enfermeriaUpdate = Enfermeria::where("id", $enfermeria_id)->first();
        if (!isset($enfermeriaUpdate)) {
            return response()->json([
                "message" => "No existe registro de la nota de enfermeria"
            ], 404);
        }
        $enfermeriaUpdate->update([
            "ruta_firma" => "cf_nota_enfermeria_consulta_externa_{$enfermeria_id}.pdf",
            "fecha_firma" => Carbon::now(),
            "flag_firma" => 1
        ]);
        return response()->json([
            "message" => "Se actualizo la firma de la nota de enfermeria exitosamente"
        ], 200);
    }

    public function buscarIdPrestacionParaNotaDeHospitalizacion($id_enfermeria, $id_tipo_atencion)
    {
        $model_enfermeria = new Nota_enfermeria();
        $model_prestacion = new Prestaciones();
        $res = $model_enfermeria->getIdExtByIdEnfermeria($id_enfermeria);
        $id_prestacion = $model_prestacion->getIdPrestacionSiExistev2($res[0]->id_prestacion);
        return response()->json(sizeof($id_prestacion) == 0 ? [] : $id_prestacion);
    }

    public function buscarIdPrestacionParaNotaDeEmergencia($id_enfermeria, $id_tipo_atencion)
    {
        $model_enfermeria = new Nota_enfermeria();
        $model_prestacion = new Prestaciones();
        $res = $model_enfermeria->getIdExtByIdEnfermeria($id_enfermeria);
        $id_prestacion = $model_prestacion->getIdPrestacionSiExiste($res[0]->id_ext_enfermeria, $id_tipo_atencion);
        return response()->json(sizeof($id_prestacion) == 0 ? [] : $id_prestacion);
    }

    function obtenerTerapeuticasByAseguradoId($id_asegurado)
    {
        $model_prestacion = new Prestaciones();
        $result = $model_prestacion->obtenerTerapeuticasByAseguradoId($id_asegurado);
        return response()->json($result);
    }

    function obtenerHistorialEnfermeriaByAseguradoId($id_asegurado)
    {
        $model_enfermeria = new Enfermeria();
        $result = $model_enfermeria->getHistorialEnfermeriaByAseguradoId($id_asegurado);
        return response()->json($result);
    }

    function GetAtencionesByIdAsegurado($id_asegurado)
    {
        $prestacion_model = new Prestaciones();
        $result = $prestacion_model->GetAtencionesByIdAsegurado($id_asegurado);
        return response()->json($result);
    }

    function modal_historial_enfermeria($id_asegurado)
    {
        $enfermeria_model = new Enfermeria();
        $enfermerias = $enfermeria_model->getHistorialEnfermeriaByAseguradoId($id_asegurado);

        return view('frontend.enfermeria.modal_historial', compact('enfermerias'));
    }

    function modal_historial_enfermeria_v2($dni_beneficiario)
    {
        $enfermeria_model = new Enfermeria();
        $enfermerias = $enfermeria_model->getHistorialEnfermeriaByAseguradoId_v2($dni_beneficiario);

        return view('frontend.enfermeria.modal_historial', compact('enfermerias'));
    }

    function modal_historial_enf_moni_v2($dni_beneficiario, $tipo_atencion, Request $request)
    {
        $asegurado = Asegurado::where("nro_doc_ident", $dni_beneficiario)->first();
        $model_monitoreo = new Monitoreo();
        $monitoreos = $model_monitoreo->allMonitoreosPorIdAsegurado($asegurado->id, $tipo_atencion);
        return view('frontend.enfermeria.modal_historial_monitoreo', compact('monitoreos', 'tipo_atencion'));
    }

    function modal_historial_enf_kardex_v2($dni_beneficiario, $tipo_atencion, Request $request)
    {
        $asegurado = Asegurado::where("nro_doc_ident", $dni_beneficiario)->first();
        $model_kardex_enf = new KardexEnfermerias();
        $kardexes = $model_kardex_enf->allKardexesByAseguradoId($asegurado->id, $tipo_atencion);
        return view('frontend.enfermeria.modal_historial_kardex', compact('kardexes', 'tipo_atencion'));
    }

    function modal_historial_enf_bh_v2($dni_beneficiario, $tipo_atencion, Request $request)
    {
        $query = DB::table('balance_hidricos as bh')
            ->leftJoin('balance_hidrico_detalles as bhd', 'bh.id', '=', 'bhd.id_balance_hidrico')
            ->leftJoin('users as u', 'bh.created_us', '=', 'u.id')
            ->leftJoin('turnos as t', 'bh.id_turno', '=', 't.id')
            ->leftJoin('enfermerias as enf', 'enf.id', '=', 'bh.id_enfermeria')
            ->select(
                'bh.id',
                'bh.codigo_fecha_24',
                'bh.id_turno',
                'enf.id_tipo_atencion as tipo_atencion_bh',
                'u.name as nombre',
                'u.paterno',
                'u.materno',
                't.abreviatura as turno',
                'bh.flag_firma',
                'bh.ruta_firma',
                'bh.peso as peso_al_registrar_bh',
                DB::raw('TO_CHAR(bh.fecha_registro, \'DD/MM/YYYY\') AS fecha_registro'),
                DB::raw(
                    '
                LPAD(EXTRACT(HOUR FROM bh.hora_registro)::text, 2, \'0\') || \':\' ||
                LPAD(EXTRACT(MINUTE FROM bh.hora_registro)::text, 2, \'0\') || \':\' ||
                LPAD(EXTRACT(SECOND FROM bh.hora_registro)::text, 2, \'0\') AS hora_registro'
                ),
                DB::raw('SUM(CASE WHEN bhd.tipo_balance = 1 AND es_visible != 0 AND bhd.id_caracter_fisico != 46 THEN bhd.valor_caracter_fisico ELSE 0 END) - 
                    SUM(CASE WHEN (bhd.tipo_balance = 2 AND es_visible != 0) 
                    OR (bhd.tipo_balance = 1 AND es_visible != 0 AND bhd.id_caracter_fisico = 46)
                    THEN bhd.valor_caracter_fisico ELSE 0 END) AS diferencia')
            )
            ->leftJoin(DB::raw("(
            SELECT bh.codigo_fecha_24,
                  SUM(CASE WHEN bhd.tipo_balance = 1 AND es_visible != 0 AND bhd.id_caracter_fisico != 46 THEN bhd.valor_caracter_fisico ELSE 0 END) - 
                  SUM(CASE WHEN (bhd.tipo_balance = 2 AND es_visible != 0) 
                  OR (bhd.tipo_balance = 1 AND es_visible != 0 AND bhd.id_caracter_fisico = 46)
                  THEN bhd.valor_caracter_fisico ELSE 0 END) AS bh24
            FROM balance_hidricos AS bh
            LEFT JOIN enfermerias AS enf2 ON bh.id_enfermeria = enf2.id
            LEFT JOIN balance_hidrico_detalles AS bhd ON bh.id = bhd.id_balance_hidrico
            WHERE enf2.id_tipo_atencion = " . $tipo_atencion . "
            GROUP BY bh.codigo_fecha_24
        ) AS subquery"), 'bh.codigo_fecha_24', '=', 'subquery.codigo_fecha_24')
            ->addSelect('subquery.bh24')
            ->groupBy(
                'bh.id',
                'bh.codigo_fecha_24',
                'u.name',
                'enf.id_tipo_atencion',
                'u.paterno',
                'u.materno',
                't.abreviatura',
                'bh.fecha_registro',
                'bh.hora_registro',
                'subquery.bh24',
                'bh.id_turno'
            );
        $asegurado = Asegurado::where("nro_doc_ident", $dni_beneficiario)->first();
        $query->where('bh.id_asegurado', $asegurado->id);
        $query->where('enf.id_tipo_atencion', $tipo_atencion);
        $results = $query->get();

        return view('frontend.enfermeria.modal_historial_bh', compact('results'));
    }

    function modal_historial_enf_sv_v2($dni_beneficiario, $tipo_atencion, Request $request)
    {
        $model_nota_ingreso = new Nota_enfermeria;
        $arreglo_lista = [];
        $i = 0;

        $asegurado = Asegurado::where("nro_doc_ident", $dni_beneficiario)->first();

        $fechaActual = Carbon::now();
        $dato_signo_vitales = DB::select("SELECT 
            sv.*, 
            a.id AS id_asegurado,
            a.nombre AS nombre_asegurado,
            e.id as enfermeria_id
        FROM signo_vitales sv
        JOIN enfermerias e ON e.id = sv.id_enfermeria

        -- JOIN condicional con emergencias u hospitalizaciones
        LEFT JOIN emergencias em 
            ON e.id_tipo_atencion = 2 AND e.id_ext_enfermeria = em.id
        LEFT JOIN hospitalizaciones h 
            ON e.id_tipo_atencion = 3 AND e.id_ext_enfermeria = h.id
        -- JOIN con asegurados, tomando el id_asegurado de la tabla correcta
        LEFT JOIN asegurados a 
            ON a.id = COALESCE(em.id_asegurado, h.id_asegurado)
        -- Filtro por asegurado
        WHERE a.id = ?", [$asegurado->id]);

        if (empty($dato_signo_vitales)) {
            $estado = '0';
            $datosPuntos = [
                ["x" => 0, "y" => 0],
            ];
            $datosPuntos2 = [
                ["x" => 0, "y" => 0],
            ];
            $datosPuntos3 = [
                ["x" => 0, "y" => 0],
            ];
            $datosPuntos4 = [
                ["x" => 0, "y" => 0],
            ];
            $datosPuntos5 = [
                ["x" => 0, "y" => 0],
            ];
            $datosPuntos6 = [
                ["x" => 0, "y" => 0],
            ];
            $datosBarras = [
                ['label' => 'SIN DATOS', 'start' => 0, 'end' => 0],
            ];
        } else {
            foreach ($dato_signo_vitales as $signo) {
                $arreglo_lista[$i] = new \stdClass();

                $actualizado = DB::table('signo_vitales_historico')
                    ->select('signo_vitales_historico.*')
                    ->where('id_signo_vital', $signo->id)
                    ->first();
                if ($actualizado === null) {
                    $arreglo_lista[$i]->seactualizo = 0;
                } else {
                    $arreglo_lista[$i]->seactualizo = 1;
                }
                $solo_fecha = 0;

                $dato1 = DB::table('signo_vitales_detalles')
                    ->select('signo_vitales_detalles.*')
                    ->where('id_signo_vital', $signo->id)
                    ->where('id_caracter_fisico', '1')
                    ->first();

                $dato2 = DB::table('signo_vitales_detalles')
                    ->select('signo_vitales_detalles.*')
                    ->where('id_signo_vital', $signo->id)
                    ->where('id_caracter_fisico', '2')
                    ->first();

                $dato3 = DB::table('signo_vitales_detalles')
                    ->select('signo_vitales_detalles.*')
                    ->where('id_signo_vital', $signo->id)
                    ->where('id_caracter_fisico', '4')
                    ->first();

                $dato4 = DB::table('signo_vitales_detalles')
                    ->select('signo_vitales_detalles.*')
                    ->where('id_signo_vital', $signo->id)
                    ->where('id_caracter_fisico', '6')
                    ->first();

                $dato5 = DB::table('signo_vitales_detalles')
                    ->select('signo_vitales_detalles.*')
                    ->where('id_signo_vital', $signo->id)
                    ->where('id_caracter_fisico', '9')
                    ->first();

                $dato6 = DB::table('signo_vitales_detalles')
                    ->select('signo_vitales_detalles.*')
                    ->where('id_signo_vital', $signo->id)
                    ->where('id_caracter_fisico', '17')
                    ->first();

                $dato7 = DB::table('signo_vitales_detalles')
                    ->select('signo_vitales_detalles.*')
                    ->where('id_signo_vital', $signo->id)
                    ->where('id_caracter_fisico', '58')
                    ->first();

                $dato1CreatedAt = Carbon::parse($dato1->created_at);

                if ($dato1CreatedAt->isSameDay($fechaActual)) {
                    if ($dato1->id_turno == 1) {
                        $madrugada = 1;
                    }
                    if ($dato1->id_turno == 2) {
                        $mañana = 1;
                    }
                    if ($dato1->id_turno == 3) {
                        $tarde = 1;
                    }
                    if ($dato1->id_turno == 4) {
                        $noche = 1;
                    }
                }
                $codigo_fecha = $dato1->created_at;
                list($fecha, $hora) = explode(' ', $codigo_fecha);
                list($anio, $mes, $dia) = explode('-', $fecha);
                $mes = (int)$mes;
                $dia = (int)$dia;
                $diamesanio = $dia . $mes . $anio;
                $solo_fecha = substr($codigo_fecha, 0, 10);
                $arreglo_lista[$i]->codigo_turno = $diamesanio;
                $ini = $solo_fecha . ' 00:00:00';
                $fin = $solo_fecha . ' 23:59:59';
                $dato_balance_hidrico1 = ("SELECT * 
                    FROM balance_hidricos
                    WHERE id_asegurado =  '$asegurado->id'
                    AND created_at BETWEEN '$ini' AND '$fin' ");
                $dato_balance_hidrico = DB::select($dato_balance_hidrico1);
                if (empty($dato_balance_hidrico)) {
                    $arreglo_lista[$i]->balance_turno = '-';
                    $arreglo_lista[$i]->balance_x24 = '-';
                    $arreglo_lista[$i]->diuresis = '-';
                    $arreglo_lista[$i]->deposisiones  = '-';
                    $arreglo_lista[$i]->drenajes = '-';
                } else {
                    foreach ($dato_balance_hidrico as $balance) {
                        $calcula_turno = "SELECT 
                                                (SUM(CASE WHEN tipo_balance = 1 AND es_visible != 0 AND id_caracter_fisico != 46 THEN total_m ELSE 0 END) -
                                                SUM(CASE WHEN tipo_balance = 2 AND es_visible != 0 OR (tipo_balance = 1 AND es_visible != 0 AND id_caracter_fisico = 46) THEN total_m ELSE 0 END)) AS diferencia_total_m
                                            FROM (
                                                SELECT 
                                                    balance_hidrico_detalles.codigo_agrupacion,
                                                    SUM(CASE 
                                                            WHEN balance_hidrico_detalles.id_turno = '$dato1->id_turno' THEN balance_hidrico_detalles.valor_caracter_fisico
                                                        ELSE 0 END) AS total_m,
                                                    balance_hidrico_detalles.tipo_balance,
                                                    balance_hidrico_detalles.id_caracter_fisico,
                                                    balance_hidrico_detalles.es_visible
                                                FROM balance_hidrico_detalles
                                                JOIN balance_hidricos ON balance_hidrico_detalles.id_balance_hidrico = balance_hidricos.id
                                                LEFT JOIN enfermerias AS enf2 ON balance_hidricos.id_enfermeria = enf2.id
                                                WHERE balance_hidrico_detalles.estado = 1
                                                AND balance_hidrico_detalles.es_visible = 1
                                                AND enf2.id_tipo_atencion = '$tipo_atencion'
                                                AND (balance_hidrico_detalles.tipo_balance = 1 OR balance_hidrico_detalles.tipo_balance = 2)
                                                AND balance_hidricos.created_at BETWEEN '$ini' AND '$fin'
                                                AND balance_hidricos.id_asegurado = '$asegurado->id'
                                                GROUP BY balance_hidrico_detalles.codigo_agrupacion, balance_hidrico_detalles.descripcion, balance_hidrico_detalles.id_caracter_fisico, balance_hidrico_detalles.es_visible, balance_hidrico_detalles.tipo_balance
                                            ) AS subquery;";
                        $calcula_turno = DB::select($calcula_turno);
                        $calcula_turno = $calcula_turno[0];
                        $balance_xturno = $calcula_turno->diferencia_total_m;
                        ////////////////////////////
                        $calcula_24h = "SELECT 
                                                /* (SUM(CASE WHEN tipo_balance = 1 THEN total_m ELSE 0 END) -
                                                SUM(CASE WHEN tipo_balance = 2 THEN total_m ELSE 0 END)) AS diferencia_total_m */
                                                (SUM(CASE WHEN tipo_balance = 1 AND es_visible != 0 AND id_caracter_fisico != 46 THEN total_m ELSE 0 END) -
                                                SUM(CASE WHEN tipo_balance = 2 AND es_visible != 0 OR (tipo_balance = 1 AND es_visible != 0 AND id_caracter_fisico = 46) THEN total_m ELSE 0 END)) AS diferencia_total_m
                                            FROM (
                                                SELECT 
                                                    balance_hidrico_detalles.codigo_agrupacion,
                                                    SUM(CASE 
                                                            WHEN balance_hidrico_detalles.id_turno = 1 THEN balance_hidrico_detalles.valor_caracter_fisico
                                                            WHEN balance_hidrico_detalles.id_turno = 2 THEN balance_hidrico_detalles.valor_caracter_fisico
                                                            WHEN balance_hidrico_detalles.id_turno = 3 THEN balance_hidrico_detalles.valor_caracter_fisico
                                                            WHEN balance_hidrico_detalles.id_turno = 4 THEN balance_hidrico_detalles.valor_caracter_fisico
                                                        ELSE 0 END) AS total_m,
                                                    balance_hidrico_detalles.tipo_balance,
                                                    balance_hidrico_detalles.id_caracter_fisico,
                                                    balance_hidrico_detalles.es_visible
                                                FROM balance_hidrico_detalles
                                                JOIN balance_hidricos ON balance_hidrico_detalles.id_balance_hidrico = balance_hidricos.id
                                                LEFT JOIN enfermerias AS enf2 ON balance_hidricos.id_enfermeria = enf2.id
                                                WHERE balance_hidrico_detalles.estado = 1
                                                AND balance_hidrico_detalles.es_visible = 1
                                                AND enf2.id_tipo_atencion = '$tipo_atencion'
                                                AND (balance_hidrico_detalles.tipo_balance = 1 OR balance_hidrico_detalles.tipo_balance = 2)
                                                AND balance_hidricos.created_at BETWEEN '$ini' AND '$fin'
                                                AND balance_hidricos.id_asegurado = '$asegurado->id'
                                                GROUP BY balance_hidrico_detalles.codigo_agrupacion, balance_hidrico_detalles.descripcion, balance_hidrico_detalles.id_caracter_fisico, balance_hidrico_detalles.es_visible, balance_hidrico_detalles.tipo_balance
                                            ) AS subquery;";
                        $calcula_24h = DB::select($calcula_24h);
                        $calcula_24h = $calcula_24h[0];
                        $balance_x24 = $calcula_24h->diferencia_total_m;

                        $arreglo_lista[$i]->balance_turno = $balance_xturno;
                        $arreglo_lista[$i]->balance_x24 = $balance_x24;
                    }
                }
                $responsable = $dato1 ? DB::table('users')->select('users.*')->where('id', $dato1->created_us)->first() : null;

                $arreglo_lista[$i]->presionx = $dato1 ? $dato1->descripcion : 'N/A';
                $arreglo_lista[$i]->presiony = $dato2 ? $dato2->descripcion : 'N/A';
                $arreglo_lista[$i]->cardiaca = $dato3 ? $dato3->descripcion : 'N/A';
                $arreglo_lista[$i]->respiratorio = $dato4 ? $dato4->descripcion : 'N/A';
                $arreglo_lista[$i]->temperatura = $dato5 ? $dato5->descripcion : 'N/A';
                $arreglo_lista[$i]->presionmedia = $dato6 ? $dato6->descripcion : 'N/A';
                $fecha = $dato1 ? $dato1->created_at : 'N/A';
                $arreglo_lista[$i]->fecha = $fecha;
                if ($tipo_atencion == 3) {
                    $dato_cabecera_jhon = $model_nota_ingreso->getCabecera_id_withSPforHospi($signo->enfermeria_id);
                } else if ($tipo_atencion == 2) {
                    $dato_cabecera_jhon = $model_nota_ingreso->getCabecera_id_withSPforEmergencia($signo->enfermeria_id);
                }
                /* $dato_peso =  $dato_cabecera_jhon[0]->asegurado_peso ? $dato_cabecera_jhon[0]->asegurado_peso : "Sin data."; */
                $dato_peso = isset($dato_cabecera_jhon[0]->asegurado_peso) && $dato_cabecera_jhon[0]->asegurado_peso
                    ? $dato_cabecera_jhon[0]->asegurado_peso
                    : "Sin data.";
                $arreglo_lista[$i]->peso = $dato_peso;

                $partes = explode(' ', $fecha);
                $soloFecha = $partes[0];

                if ($dato1 && $dato1->id_turno == '1') {
                    $datoturo = 'MADRUGRADA';
                } elseif ($dato1 && $dato1->id_turno == '2') {
                    $datoturo = 'MAÑANA';
                } elseif ($dato1 && $dato1->id_turno == '3') {
                    $datoturo = 'TARDE';
                } else {
                    $datoturo = 'NOCHE';
                }
                $arreglo_lista[$i]->id_turno = $dato1->id_turno;
                $arreglo_lista[$i]->turno = $datoturo;
                $arreglo_lista[$i]->responsable = $responsable ? $responsable->name . ', ' . $responsable->paterno . ' ' . $responsable->materno : 'N/A';
                $arreglo_lista[$i]->ubica = isset($dato_cabecera_jhon[0]) ? $dato_cabecera_jhon[0]->cuarto : 'N/A';
                $arreglo_lista[$i]->id_signos_vitales = $signo->id;
                $arreglo_lista[$i]->numero = $i + 1;
                $arreglo_lista[$i]->id_signo_vital = $dato1->id_signo_vital;

                $cardiaca = $dato1 ? $dato1->descripcion : 0;
                $cardiaca1 = $dato2 ? $dato2->descripcion : 0;
                $cardiaca2 = $dato3 ? $dato3->descripcion : 0;
                $cardiaca3 = $dato4 ? $dato4->descripcion : 0;
                $cardiaca4 = $dato5 ? $dato5->descripcion : 0;
                $cardiaca5 = $dato6 ? $dato6->descripcion : 0;

                $datosPuntos[] = [
                    'x' => $datoturo . ' - ' . $soloFecha,
                    'y' => $cardiaca
                ];
                $datosPuntos2[] = [
                    'x' =>  $datoturo . ' - ' . $soloFecha,
                    'y' => $cardiaca1
                ];
                $datosPuntos3[] = [
                    'x' =>  $datoturo . ' - ' . $soloFecha,
                    'y' => $cardiaca2
                ];
                $datosPuntos4[] = [
                    'x' => $datoturo . ' - ' . $soloFecha,
                    'y' => $cardiaca3
                ];
                if ($cardiaca4 && floatval($cardiaca4) >= 30) {
                    $datosPuntos5[] = [
                        'x' => $datoturo . ' - ' . $soloFecha,
                        'y' => $cardiaca4
                    ];
                }
                $datosPuntos6[] = [
                    'x' => $datoturo . ' - ' . $soloFecha,
                    'y' => $cardiaca5
                ];
                $datosBarras[] = [
                    ['label' => $soloFecha . ' - ' . $datoturo, 'start' => $cardiaca1, 'end' => $cardiaca],
                ];
                $i++;
            }
        }

        return view('frontend.enfermeria.modal_historial_sv', compact('arreglo_lista'));
    }

    function modal_historial_enf_evo_v2($dni_beneficiario, $tipo_atencion, Request $request)
    {
        $objNota_enfermeria_evoluciones = new Nota_enfermeria_evoluciones();
        $asegurado = Asegurado::where("nro_doc_ident", $dni_beneficiario)->first();
        $evoluciones = $objNota_enfermeria_evoluciones->getlista_nota_ingreso_evoluciones_paginado($tipo_atencion, $asegurado->id, $request->perPage);
        return view('frontend.enfermeria.modal_historial_evol', compact('evoluciones'));
    }

    function getDetailByIdTipoAtencionId($id, $id_tipo_atencion)
    {
        $model_receta_diagnostico = new Receta_diagnostico();

        $enfermeria = Enfermeria::find($id);
        $data = null;
        $diagnosticos = [];
        $procedimientos = [];
        $signosVitales = [];
        if ($enfermeria) {
            switch (intval($id_tipo_atencion)) {
                case 1:
                    $enfermeriaModel = new Enfermeria;
                    $data = $enfermeriaModel->obtenerEnfermeriaConsultaExternaPorId($id);
                    $signosVitales = enfermeriaDetalle::where('id_enfermeria', $data->enfermeria_id)->get();
                    $procedimientos = DB::table('enfermerias as e')
                                        ->join('prestacion_procedimientos as pp', 'e.id_prestacion', '=', 'pp.id_prestacion')
                                        ->leftJoin('diagnosticos as d', 'pp.id_diagnostico', '=', 'd.id')
                                        ->join('procedimientos as p', 'pp.id_procedimiento', '=', 'p.id')
                                        ->select(
                                            'pp.id as prestacion_procedimiento_id',
                                            'pp.cantidad as prestacion_procedimiento_cantidad',
                                            'pp.id_estado_reg as prestacion_procedimiento_id_estado_reg',
                                            'p.id as procedimiento_id',
                                            'p.codigo as procedimiento_codigo',
                                            'p.descripcion as procedimiento_descripcion',
                                            'd.id as diagnostico_id',
                                            'd.nombre as diagnostico_nombre'
                                        )
                                        ->where('e.id', $data->enfermeria_id)
                                        ->whereNotNull('pp.id_enfermeria')
                                        ->where('pp.id_estado_reg', 1)
                                        ->get();
                    break;
                case 2:
                    $model_receta = new Receta_vale();
                    $data = $model_receta->getEmergenciaById_farmacia_new($enfermeria->id_ext_enfermeria);
                    $diagnosticos = $model_receta_diagnostico->DiagnosticosByEmergencia_Enfermeria_formulario_prueba($enfermeria->id_ext_enfermeria);
                    break;
                case 3:
                    $model_nota_enfermeria = new Nota_enfermeria();
                    $data = $model_nota_enfermeria->getHospitalizacionById_farmacia_new2($enfermeria->id_ext_enfermeria);
                    $diagnosticos = $model_receta_diagnostico->DiagnosticosByHospitalizacion_Enfermeria($enfermeria->id_ext_enfermeria);
                    break;
                default:
                    $model_receta = new Receta_vale();
                    $data = $model_receta->getEmergenciaById_farmacia_new($enfermeria->id_ext_enfermeria);
                    break;
            }
        }

        $response = [
            "enfermeria" => $data,
            "diagnosticos" => $diagnosticos,
            "procedimientos" => $procedimientos,
            "signosVitales" => $signosVitales
        ];

        return response()->json($response);
    }

    function getLatestTerapeuticaByIdAsegurado($id_asegurado)
    {
        $enfermeria_model = new Enfermeria();
        $response = $enfermeria_model->getLatestTerapeuticaByIdAsegurado($id_asegurado);
        return response()->json($response);
    }

    /**
     * Obtiene las enfermerías por ID externo de enfermería
     */
    function getEnfermeriasByIdExtEnfermeria($id_ext_enfermeria, Request $request)
    {
        $id_tipo_atencion = $request->query('id_tipo_atencion');

        $atenciones = DB::table('enfermerias as e')
            ->join('users as u', 'e.created_us', '=', 'u.id')
            ->where('e.id_tipo_atencion', '=', $id_tipo_atencion)
            ->where('e.id_ext_enfermeria', '=', $id_ext_enfermeria)
            ->where('e.id_estado_reg', '=', 1)
            ->orderBy('e.id', 'desc')
            ->select(
                'e.id as enfermeria_id',
                'e.id_prestacion',
                'e.id_tipo_atencion',
                'e.fecha_firma',
                'e.ruta_firma',
                'e.flag_firma',
                'e.created_us',
                'e.created_at',
                'e.id_ext_enfermeria',
                DB::raw("TO_CHAR(e.created_at, 'DD-MM-YYYY') as fecha_atencion"),
                DB::raw("TO_CHAR(e.created_at, 'HH12:MI AM') as hora_atencion"),
                'u.id as responsable_id',
                'u.dni as responsable_dni',
                'u.name as responsable_nombre',
                'u.paterno as responsable_paterno',
                'u.materno as responsable_materno',
                'e.flag_firma',
                'e.ruta_firma',
                'e.fecha_firma'
            )
            ->get();

        return response()->json([
            "message" => "Enfermerías obtenidas exitosamente",
            "data" => $atenciones
        ], 200);
    }

    function obtenerEnfermeriaByIdExtEnfermeria($id_ext_enfermeria)
    {
        $enfermeria = Enfermeria::where('id_ext_enfermeria', $id_ext_enfermeria)->orderBy('id', 'asc')->first();
        if (!$enfermeria) {
            return response()->json(["message" => "Enfermería no encontrada"], 404);
        }
        $signosVitales = enfermeriaDetalle::where('id_enfermeria', $enfermeria->id)->get();

        $result = [
            "enfermeria" => $enfermeria,
            "signosVitales" => $signosVitales
        ];

        return response()->json([
            "message" => "Enfermería obtenida exitosamente",
            "data" => $result
        ], 200);
    }

    function exportarReporteEnfermeria(Request $request)
    {
        $enfermeria = new Enfermeria;
        $model_establecimiento = new Establecimiento();

        $offset = ($request->pagina - 1) * $request->cantidad_pagina;
        $limit = $request->cantidad_pagina;

        $id_establecimiento = Auth::user()->id_establecimiento;

        $p = [];
        $p[0] = $request->fecha_atencion_inicio;
        $p[1] = $request->fecha_atencion_fin;
        $p[2] = $request->nro_doc_ident;
        $p[3] = $request->estado;
        $p[4] = $request->id_servicio;
        $p[5] = $id_establecimiento;
        $p[6] = $limit;
        $p[7] = $offset;

        $ordenes = $enfermeria->obtenerCitasEnfermeria($p);

        $establecimiento = $model_establecimiento->GetById(Auth::user()->id_establecimiento);
        $nombre_establecimiento = $establecimiento[0]->nombre;

        $descriptions = [
            [$nombre_establecimiento],
            ["REPORTE DE ÓRDENES DE LABORATORIO"],
            ["Fecha Solicitud Desde: " . $request->fecha_atencion_inicio],
            ["Fecha Solicitud Hasta: " . $request->fecha_atencion_fin],
            ["Total: " . count($ordenes) . " órdenes de laboratorio"],
            ["Usuario: " . Auth::user()->name],
            [""]
        ];

        $headers = [
            'N°',
            'FECHA Y HORA CITA',
            'FECHA Y HORA ATENCION',
            'DATOS ATENCION',
            'PACIENTE DNI',
            'PACIENTE NOMBRE Y APELLIDOS',
            'PACIENTE EDAD',
            'PACIENTE GENERO',
            'REFERIDO',
            'MEDICO DNI',
            'MEDICO NOMBRE Y APELLIDOS'
        ];

        $data = [];
        if ($ordenes && count($ordenes) > 0) {
            foreach ($ordenes as $key => $orden) {
                $beneficiario = isset($orden->asegurado_nombre) ? $orden->asegurado_nombre : "";
                $beneficiario .= isset($orden->asegurado_materno) ? " " . $orden->asegurado_materno : "";
                $beneficiario .= isset($orden->asegurado_paterno) ? " " . $orden->asegurado_paterno : "";

                $medico = isset($orden->medico_nombre) ? $orden->medico_nombre : "";
                $medico .= isset($orden->medico_materno) ? " " . $orden->medico_materno : "";
                $medico .= isset($orden->medico_paterno) ? " " . $orden->medico_paterno : "";

                $data[] = [
                    isset($orden->prestacion_id) ? $orden->prestacion_id : $orden->cita_id,
                    isset($orden->cita_fecha_hora) ? Carbon::parse($orden->cita_fecha_hora)->format('d-m-Y H:i') : "",
                    isset($orden->prestacion_fecha_atencion) ? Carbon::parse($orden->prestacion_fecha_atencion)->format('d-m-Y H:i') : "",
                    isset($orden->consultorio_nombre) ? $orden->consultorio_nombre : "",
                    isset($orden->asegurado_numero_documento) ? $orden->asegurado_numero_documento : "",
                    $beneficiario,
                    isset($orden->asegurado_edad) ? $orden->asegurado_edad : "",
                    isset($orden->asegurado_sexo) ? $orden->asegurado_sexo : "",
                    isset($orden->asegurado_flag_referencia) ? $orden->asegurado_flag_referencia : "",
                    isset($orden->medico_dni) ? $orden->medico_dni : "",
                    $medico,
                ];
            }
        }

        $exportData = array_merge($descriptions, [$headers], $data);

        Excel::create('Exportar Reporte de Enfermeria Consulta Externa', function ($excel) use ($exportData, $descriptions) {
            $excel->sheet('GENERAL', function ($sheet) use ($exportData, $descriptions) {
                $sheet->rows($exportData);

                $headerRow = count($descriptions) + 1;

                $sheet->row($headerRow, function ($row) {
                    $row->setBackground('#d9edf7');
                    $row->setFontWeight('bold');
                });

                $sheet->setAutoSize(true);
            });
        })->download('xlsx');
    }
    function validateDuplicateProcedure($id_procedimiento, Request $request)
    {
        $query = DB::table('enfermerias as e')
            ->select(
                'e.id as id_enfermeria',
                'e.created_at as fecha_atencion_enfermeria',
                'e.created_us as id_responsable',
                'pp.id_procedimiento',
                'pr.descripcion as procedimiento_descripcion',
                'pr.codigo',
                'pr.descripcion_corta',
                'c.nombre as consultorio_nombre'
            )
            ->join('prestaciones as p', 'e.id_prestacion', '=', 'p.id')
            ->join('prestacion_procedimientos as pp', 'pp.id_prestacion', '=', 'p.id')
            ->join('sub_consultorios as sc', 'p.id_sub_consultorio', '=', 'sc.id')
            ->join('consultorios as c', 'sc.id_consultorio', '=', 'c.id')
            ->join('procedimientos as pr', 'pp.id_procedimiento', '=', 'pr.id')
            ->where('pp.id_procedimiento', $id_procedimiento);
        if ($request->has('id_asegurado')) {
            $query->where('p.id_asegurado', $request->input('id_asegurado'));
        }
        if ($request->has('id_consultorio')) {
            $query->where('sc.id_consultorio', $request->input('id_consultorio'));
        }
        if ($request->has('fecha')) {
            $query->where(DB::raw('DATE(e.created_at)'), '=', $request->input('fecha'));
        }
        $prestacion_procedimiento = $query->first();

        return response()->json([
            "message" => "Validación de procedimiento duplicado",
            "data" => [
                "prestacion_procedimiento" => $prestacion_procedimiento,
                'is_duplicate' => (bool) $prestacion_procedimiento
            ]
        ], 200);
    }

    function getConsultoriosByEstablecimientoTipoAtencion($establecimiento_id, $tipo_atencion_id) 
    {
        $model_consultorio = new Consultorio;
        $consultorios = $model_consultorio->ShowConsultorioTipoAtencion($establecimiento_id, $tipo_atencion_id);

        return response()->json([
            'success' => true,
            'mensaje' => 'Consultorios obtenidos correctamente',
            'data' => $consultorios
        ]);
    }
}
