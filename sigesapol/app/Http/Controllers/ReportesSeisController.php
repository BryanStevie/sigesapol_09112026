<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Access\User\User;
use App\Models\Asegurado;
use App\Models\Colegio_profesionale;
use App\Models\Consultorio;
use App\Models\CqxAnestesia;
use App\Models\CqxTipoAnestesia;
use App\Models\Departamento;
use App\Models\Diagnostico;
use App\Models\Distrito;
use App\Models\Especializacione;
use App\Models\Establecimiento;
use App\Models\Farmacia;
use App\Models\Grado_instruccion;
use App\Models\Medico;
use App\Models\Profesione;
use App\Models\Provincia;
use App\Models\Situacion;
use App\Models\Tipo_beneficiario;
use App\Models\Tipo_documento;
use App\Models\Tipo_documento_identidad;
use App\Models\Tipo_grado;
use App\Models\Titular_grado;
use App\Models\Upss;
use App\Models\Eventos_epidemiologicos;
use Carbon\Carbon;
use DateTime;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use PHPExcel_Style_Border;

class ReportesSeisController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        if (Auth::check()) {
            $ipressActual = Establecimiento::where("id", Auth::user()->id_establecimiento)
                ->select("codigo", "nombre as nombre_ipress", "id")
                ->first();
            $flag_establecimientos = Auth::user()->flagestablecimientos;

            View::share([
                'ipressActual'         => $ipressActual,
                'flag_establecimientos' => $flag_establecimientos,
            ]);
        }
    }

    function obtenerRangoMes($anio, $mes)
    {
        $inicio = Carbon::create($anio, $mes, 1)->startOfMonth()->format('Y-m-d');
        $fin    = Carbon::create($anio, $mes, 1)->endOfMonth()->format('Y-m-d');

        return [
            'inicio' => $inicio,
            'fin' => $fin,
        ];
    }

    public function buscarUps(Request $request)
    {
        $query = Upss::query();

        if ($request->has('codigo') && $request->codigo !== '') {
            $query->where('codigo', 'ILIKE', '%' . $request->codigo . '%');
        }

        if ($request->has('descripcion_upss') && $request->descripcion_upss !== '') {
            $query->where('descripcion_upss', 'ILIKE', '%' . $request->descripcion_upss . '%');
        }

        if ($request->has('estado') && $request->estado !== '') {
            $query->where('estado', $request->estado);
        }

        if ($request->has('tipo_consulta_medica') && $request->tipo_consulta_medica !== '') {
            if ($request->tipo_consulta_medica == 4) {
                // Cuando es 4, filtra por flag_estrategias
                $query->where('flag_estrategias_sanitarias', '=', 1);  // o el valor que necesites
            } else {
                // Para cualquier otro número, usa id_tipo_atencion
                $query->where('id_tipo_atencion', '=', $request->tipo_consulta_medica);
            }
        }

        if ($request->has('tipo_servicio') && $request->tipo_servicio !== '') {
            $query->where('id_consultorio', "=", $request->tipo_servicio);
        }

        $upsses = $query
            ->select("id", "codigo", "descripcion_upss", "estado")
            ->orderBy('codigo', 'asc')
            ->get();

        return response()->json($upsses);
    }

    public function buscarIpress(Request $request)
    {
        $query = Establecimiento::query();

        if ($request->has('descripcion_ipress') && $request->descripcion_ipress !== '') {
            $query->where('nombre', 'ILIKE', '%' . $request->descripcion_ipress . '%');
        }

        if ($request->has('estado') && $request->estado !== '') {
            $query->where('id_estado_reg', $request->estado);
        }

        if ($request->has('categoria_ipress') && $request->categoria_ipress !== '') {
            $query->where('nivel', $request->categoria_ipress);
        }

        if ($request->has('region_ipress') && $request->region_ipress !== '') {
            $query->where('red_region', $request->region_ipress);
        }

        $upsses = $query
            ->select("id", "codigo", "nombre as nombre_ipress", "red_region", "nivel", "ubigeo", "id_estado_reg as estado")
            ->orderBy('codigo', 'asc')
            ->get();

        return response()->json($upsses);
    }

    public function buscarDiagnostico(Request $request)
    {
        $query = Diagnostico::query();

        if ($request->has('descripcion_diagnostico') && $request->descripcion_diagnostico !== '') {
            $query->where('nombre', 'ILIKE', '%' . $request->descripcion_diagnostico . '%');
        }

        if ($request->has('estado') && $request->estado !== '') {
            $query->where('estado', $request->estado);
        }

        if ($request->has('sexo_diagnostico') && $request->sexo_diagnostico !== '') {
            $query->where('genero', $request->sexo_diagnostico);
        }

        if ($request->has('codigo_diagnostico') && $request->codigo_diagnostico !== '') {
            $query->where('codigo', $request->codigo_diagnostico);
        }

        $diagnosticos = $query
            ->select("id", "codigo", "nombre as nombre_diagnostico", "estado", "genero")
            ->orderBy('codigo', 'asc')
            ->get();

        return response()->json($diagnosticos);
    }

    public function buscarServ(Request $request)
    {
        $query = Consultorio::query();

        if ($request->has('descripcion_serv') && $request->descripcion_serv !== '') {
            $query->where('nombre', 'ILIKE', '%' . $request->descripcion_serv . '%');
        }

        if ($request->has('estado') && $request->estado !== '') {
            $query->where('estado', $request->estado);
        } else {
            $query->where(function ($q) {
                $q->where('estado', 4)
                    ->orWhere('estado', 0);
            });
        }

        $servicios = $query
            ->select("id", "codigo_sub_cobertura", "abreviatura", "nombre as nombre_servicio", "estado", "tipo")
            ->orderBy('nombre', 'asc')
            ->get();

        return response()->json($servicios);
    }

    public function buscarMedicoPorDni(Request $request)
    {
        $query = Medico::query();

        $medico = $query
            ->where("medicos.estado", "=", 1)
            ->where("dni", "=", $request->dni_medico)
            ->leftJoin('profesiones', 'profesiones.id', '=', 'medicos.id_profesion')
            ->leftJoin('especializaciones', 'especializaciones.id', '=', 'medicos.id_especializacion')
            ->leftJoin('tipo_documento_identidad', 'tipo_documento_identidad.id', '=', 'medicos.tipo_documento')
            ->leftJoin('colegio_profesionales', 'colegio_profesionales.id', '=', 'medicos.id_colegio_profesional')
            ->select("medicos.id as id_medico", "dni", "colegio_profesionales.descripcion as descripcion_cp", "colegio_profesionales.id as id_cp", "tipo_documento_identidad.descripcion as descripcion_td", "tipo_documento_identidad.id as id_td", "medicos.nombre as nombre_medico", "paterno as paterno_medico", "materno as materno_medico", "profesiones.nombre as nombre_profesion", "profesiones.id as id_profesion", "especializaciones.nombre as nombre_especializacion", "especializaciones.id as id_especializacion")
            ->first();

        return response()->json($medico);
    }

    public function reporteAtenciones()
    {
        $regiones_ipress = Establecimiento::where('id_estado_reg', 1)
            ->select("red_region")
            ->whereNotNull('red_region')
            ->where('red_region', '!=', '')
            ->groupBy('red_region')
            ->get();
        $ugipress = Establecimiento::where('id_estado_reg', 1)
            ->select("nombre", "id", "nivel")
            ->get();
        $tipo_docs = Tipo_documento_identidad::where('estado', 1)
            ->select("descripcion", "id", "codigo")
            ->get();
        $profesiones = Profesione::where('estado', 1)
            ->select("nombre", "id")
            ->get();
        $especialidades = Especializacione::where('estado', 1)
            ->select("nombre", "id")
            ->get();
        $colegio_profesionales = Colegio_profesionale::where('estado', 1)
            ->select("descripcion", "id", "codigo")
            ->get();
        $grados = Titular_grado::where('estado', 1)
            ->select("descripcion", "id", "descripcion_corta", "codigo")
            ->get();
        $tipoBeneficiarios = Tipo_beneficiario::select("descripcion", "id", "codigo")
            ->get();
        $situaciones = Situacion::select("descripcion", "id")
            ->get();
        $departamentos = Departamento::select("descripcion", "id_departamento", "codigo")
            ->get();
        $serviciosParaUps = Consultorio::where("estado", "=", 4)->get();
        return view("frontend.seis.reporte_atenciones", compact("regiones_ipress", "departamentos", "serviciosParaUps", "ugipress", "tipo_docs", "profesiones", "especialidades", "colegio_profesionales", "grados", "tipoBeneficiarios", "situaciones"));
    }

    public function buscarAseguradoPorDni(Request $request)
    {
        $query = Asegurado::query();

        $asegurado = $query
            ->where("nro_doc_ident", "=", $request->dni_asegurado)
            ->select("asegurados.id as id_asegurado", "nro_doc_ident", "nombre", "paterno", "materno", "sexo", "id_tipo_doc_ident", "cip", "id_tipo_beneficiario")
            ->first();

        return response()->json($asegurado);
    }

    public function buscarUsuarioPorDni(Request $request)
    {
        $query = User::query();

        $user = $query
            ->where("dni", "=", $request->dni_user)
            ->select("name", "paterno", "materno", "id")
            ->first();

        return response()->json($user);
    }

    public function buscarProvinciaPorDepartamento(Request $request)
    {
        $query = Provincia::query();

        $provincias = $query
            ->where("id_departamento", "=", $request->departamento_id)
            ->select("id_provincia", "codigo", "descripcion")
            ->get();

        return response()->json($provincias);
    }

    public function buscarDistritoPorProvincia(Request $request)
    {
        $query = Distrito::query();

        $distritos = $query
            ->where("id_provincia", "=", $request->provincia_id)
            ->select("id_distrito", "codigo", "descripcion")
            ->get();

        return response()->json($distritos);
    }

    public function reporteAtendidos()
    {
        $regiones_ipress = Establecimiento::where('id_estado_reg', 1)
            ->select("red_region")
            ->whereNotNull('red_region')
            ->where('red_region', '!=', '')
            ->groupBy('red_region')
            ->get();
        return view("frontend.seis.reporte_atendidos", compact("regiones_ipress"));
    }

    public function reporteAtendidosIpress(Request $request)
    {
        $establecimientosPg = null;
        $establecimientos = $request->ipress;
        if (!empty($establecimientos)) {
            $establecimientosPg = "{" . implode(",", $establecimientos) . "}";
        }
        $fechaInicio = $request->fechaIni;
        $fechaFin = $request->fechaFin;
        $condicionEstablecimiento = $request->input('condicionEstablecimiento') == "" ? null : $request->input('condicionEstablecimiento');
        $condicionServicio = $request->input('condicionServicio') == "" ? null : $request->input('condicionServicio');

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_atendidos(?, ?, ?, ?, ?)
        ", [
            $establecimientosPg,
            $fechaInicio,
            $fechaFin,
            $condicionEstablecimiento,
            $condicionServicio,
        ]);
        return response()->json($resultados);
    }

    public function reporteAtendidosIpressExport(Request $request)
    {
        $establecimientosPg = null;
        $establecimientos = $request->ipress;
        $establecimientos = array_filter($establecimientos, function ($v) {
            return trim($v) !== '';
        });
        if (!empty($establecimientos)) {
            $establecimientosPg = "{" . implode(",", $establecimientos) . "}";
        }
        $fechaInicio = $request->fechaIni;
        $fechaFin = $request->fechaFin;
        $condicionEstablecimiento = empty($request->input('condicionEstablecimiento'))
            ? null
            : $request->input('condicionEstablecimiento');

        $condicionServicio = empty($request->input('condicionServicio'))
            ? null
            : $request->input('condicionServicio');

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_atendidos_exportar(?, ?, ?, ?, ?)
        ", [
            $establecimientosPg,
            $fechaInicio,
            $fechaFin,
            $condicionEstablecimiento,
            $condicionServicio,
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_atendidos_' . date('Ymd_His');


        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data, $request) {
                $excel->sheet('Reporte', function ($sheet) use ($data, $request) {

                    $sheet->setWidth('A', 50);
                    $sheet->setWidth('B', 20);
                    $sheet->setWidth('C', 15);
                    $sheet->setWidth('D', 15);
                    $sheet->setWidth('E', 15);
                    $sheet->setWidth('F', 25);
                    $sheet->setWidth('G', 25);
                    $sheet->setWidth('J', 20);
                    $sheet->setWidth('K', 20);
                    $sheet->setWidth('L', 25);
                    $sheet->setWidth('M', 25);

                    // 🔹 Título
                    $sheet->mergeCells('A1:M1');
                    $sheet->cell('A1', function ($cell) {
                        $cell->setValue('CONSOLIDADO DE ATENCIONES Y ATENDIDOS - DIRSAL PNP');
                        $cell->setAlignment('center');
                        $cell->setValignment('center');
                        $cell->setFont([
                            'bold' => true,
                            'size' => 14
                        ]);
                    });

                    // 🔹 Bordes negros completos
                    $sheet->cells('A1:M1', function ($cells) {
                        $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    });

                    $fechaIni = $request->fechaIni;
                    $fechaFin = $request->fechaFin;

                    // 1. Crear el objeto DateTime desde el formato original
                    $dateIni = DateTime::createFromFormat('Y-m-d', $fechaIni);
                    $dateFin = DateTime::createFromFormat('Y-m-d', $fechaFin);

                    // 2. Formatear a DD/MM/YYYY
                    $fecha_formateada_ini = $dateIni->format('d/m/Y');
                    $fecha_formateada_fin = $dateFin->format('d/m/Y');

                    // 3. Extraer el año
                    $anio = $dateIni->format('Y');

                    // 🔹 Fila 2: Informacion de mes/periodo (sin borde)
                    $sheet->mergeCells('B3:D3');
                    $sheet->cell('B3', function ($cell) use ($fecha_formateada_fin, $fecha_formateada_ini) {
                        $cell->setValue("PERIODO: {$fecha_formateada_ini} - {$fecha_formateada_fin}");
                        $cell->setAlignment('left');
                        $cell->setValignment('center');
                        $cell->setFont([
                            'bold'  => true,
                            'size'  => 11
                        ]);
                    });

                    $sheet->cell('A4', function ($cell) {
                        $cell->setValue("Consolidado");
                        $cell->setAlignment('center');
                        $cell->setValignment('center');
                        $cell->setFont([
                            'bold'  => true,
                            'size'  => 11
                        ]);
                    });

                    // 🔹 Fila 3: Informacion de año ()
                    $sheet->mergeCells('B4:D4');
                    $sheet->cell('B4', function ($cell) use ($anio) {
                        $cell->setValue("AÑO: {$anio}");
                        $cell->setAlignment('left');
                        $cell->setValignment('center');
                        $cell->setFont([
                            'bold'  => true,
                            'size'  => 11
                        ]);
                    });

                    $agrupado = [];

                    foreach ($data as $row) {
                        $nombre_ipress = $row['nombre_ipress'];

                        if (!isset($agrupado[$nombre_ipress])) {
                            $agrupado[$nombre_ipress] = [];
                        }

                        $agrupado[$nombre_ipress][] = [
                            'consultorio' => $row['consultorio'],
                            'total_general'    => $row['total_general'],
                            'total_ab'    => $row['total_general'],
                            'titular_a'    => $row['titular'],
                            'familiar_b'    => $row['familiar'],
                            'accion_civica'    => $row['accion_civica'],
                            'tarifa'    => $row['tarifa'],
                            'total_general_reingreso'    => $row['total_general_nuevo_reingreso'],
                            'total_ab_reingreso'    => $row['total_general_nuevo_reingreso'],
                            'titular_a_reingreso'    => $row['titular_nuevo_reingreso'],
                            'familiar_b_reingreso'    => $row['familiar_nuevo_reingreso'],
                        ];
                    }

                    $fila = 6;

                    foreach ($agrupado as $nombre_ipress => $consultorios) {

                        // 🔹 Fila título IPRESS
                        $sheet->cell("A{$fila}", function ($cell) use ($nombre_ipress) {
                            $cell->setValue("NOMBRE IPRESS:  {$nombre_ipress}");
                            $cell->setFont([
                                'bold'  => true,
                                'color' => ['rgb' => 'FF0000'],
                                'size'  => 13
                            ]);
                        });
                        $sheet->mergeCells("A{$fila}:B{$fila}");
                        $sheet->getStyle("A{$fila}")->getFont()->setBold(true);
                        $fila++;
                        $fila++;

                        // 🔹 Cabecera de consultorios

                        // ATENCIONES (NUEVOS + REINGRESANTES + CONTINUADORES)
                        $sheet->mergeCells("B{$fila}:G{$fila}");
                        $sheet->setCellValue("B{$fila}", "ATENCIONES (NUEVOS + REINGRESANTES + CONTINUADORES)");

                        $sheet->cells("B{$fila}:G{$fila}", function ($cells) {
                            $cells->setFont([
                                'bold' => true
                            ]);
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                            $cells->setBorder('thin', 'thin', 'thin', 'thin');
                        });

                        // ATENCIONES (NUEVOS + REINGRESANTES)
                        $sheet->mergeCells("I{$fila}:M{$fila}");
                        $sheet->setCellValue("I{$fila}", "ATENCIONES (NUEVOS + REINGRESANTES)");

                        $sheet->cells("I{$fila}:M{$fila}", function ($cells) {
                            $cells->setFont([
                                'bold' => true
                            ]);
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                            $cells->setBorder('thin', 'thin', 'thin', 'thin');
                        });

                        $fila++;

                        $sheet->mergeCells("C{$fila}:E{$fila}");
                        $sheet->setCellValue("C{$fila}", "BENEFICIARIOS FOSPOLI");

                        $sheet->cells("A{$fila}:E{$fila}", function ($cells) {
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });

                        $sheet->mergeCells("J{$fila}:K{$fila}");
                        $sheet->setCellValue("J{$fila}", "BENEFICIARIOS FOSPOLI");

                        $sheet->cells("I{$fila}:K{$fila}", function ($cells) {
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });

                        $filaSuperior = $fila;

                        $sheet->setCellValue("A{$fila}", "PRODUCCION DE SERVICIOS");
                        $sheet->setCellValue("B{$fila}", "TOTAL GENERAL");
                        $sheet->cells("A{$filaSuperior}:G{$fila}", function ($cells) {
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });

                        $sheet->setCellValue("I{$fila}", "TOTAL");
                        $sheet->cells("I{$filaSuperior}:M{$fila}", function ($cells) {
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });

                        $fila++;

                        $sheet->mergeCells("A{$filaSuperior}:A{$fila}");
                        $sheet->mergeCells("B{$filaSuperior}:B{$fila}");
                        $sheet->mergeCells("F{$filaSuperior}:F{$fila}");
                        $sheet->mergeCells("G{$filaSuperior}:G{$fila}");
                        $sheet->setCellValue("C{$fila}", "TOTAL (a+b)");
                        $sheet->setCellValue("D{$fila}", "TITULAR (a)");
                        $sheet->setCellValue("E{$fila}", "FAMILIAR (b)");
                        $sheet->setCellValue("F{$filaSuperior}", "ACCION CIVICA (c)");
                        $sheet->setCellValue("G{$filaSuperior}", "CIVILES TARIFA (d)");
                        $sheet->mergeCells("I{$filaSuperior}:I{$fila}");
                        $sheet->mergeCells("L{$filaSuperior}:L{$fila}");
                        $sheet->mergeCells("M{$filaSuperior}:M{$fila}");
                        $sheet->setCellValue("K{$fila}", "TITULAR");
                        $sheet->setCellValue("J{$fila}", "FAMILIAR");
                        $sheet->setCellValue("L{$filaSuperior}", "ACCION CIVICA (c)");
                        $sheet->setCellValue("M{$filaSuperior}", "CIVILES TARIFA (d)");
                        $sheet->cells("A{$filaSuperior}:G{$fila}", function ($cells) {
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });
                        $sheet->cells("I{$filaSuperior}:M{$fila}", function ($cells) {
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });

                        $sheet->getStyle("A{$fila}:B{$fila}")->getFont()->setBold(false);
                        $fila++;

                        // 🔹 Filas de detalle
                        foreach ($consultorios as $c) {
                            $sheet->setCellValue("A{$fila}", $c['consultorio']);
                            $sheet->getStyle("A{$fila}")->getFont()->setBold(true);
                            $sheet->setCellValue("B{$fila}", $c['total_general']);
                            $sheet->getStyle("B{$fila}")->getFont()->setBold(true);
                            $sheet->setCellValue("C{$fila}", $c['total_general']);
                            $sheet->getStyle("C{$fila}")->getFont()->setBold(true);
                            $sheet->setCellValue("D{$fila}", $c['titular_a']);
                            $sheet->getStyle("D{$fila}")->getFont()->setBold(true);
                            $sheet->setCellValue("E{$fila}", $c['familiar_b']);
                            $sheet->getStyle("E{$fila}")->getFont()->setBold(true);
                            $sheet->setCellValue("F{$fila}", $c['accion_civica']);
                            $sheet->getStyle("F{$fila}")->getFont()->setBold(true);
                            $sheet->setCellValue("G{$fila}", $c['tarifa']);
                            $sheet->getStyle("G{$fila}")->getFont()->setBold(true);
                            $sheet->setCellValue("I{$fila}", $c['total_general_reingreso']);
                            $sheet->getStyle("I{$fila}")->getFont()->setBold(true);
                            $sheet->setCellValue("J{$fila}", $c['titular_a_reingreso']);
                            $sheet->getStyle("J{$fila}")->getFont()->setBold(true);
                            $sheet->setCellValue("K{$fila}", $c['familiar_b_reingreso']);
                            $sheet->getStyle("K{$fila}")->getFont()->setBold(true);
                            $sheet->setCellValue("L{$fila}", $c['accion_civica']);
                            $sheet->getStyle("L{$fila}")->getFont()->setBold(true);
                            $sheet->setCellValue("M{$fila}", $c['tarifa']);
                            $sheet->getStyle("M{$fila}")->getFont()->setBold(true);
                            $fila++;
                        }

                        // 🔹 Fila en blanco entre IPRESS
                        $fila++;
                    }
                });
            })->download('xlsx');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }

    public function reporteAtencionesUps(Request $request)
    {
        $user = Auth::user();
        $establecimiento = $user->id_establecimiento;
        $ups = $request->ups;
        $upsPg = "{" . implode(",", $ups) . "}";

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_prestaciones_upss(?, ?)
        ", [
            $establecimiento,
            $upsPg
        ]);

        $tipoReporte = $request->tipoReporte;

        if ($tipoReporte === 'json') {
            return response()->json($resultados);
        }
        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }

    public function reporteAtencionesUpsExport(Request $request)
    {
        $user = Auth::user();
        $establecimiento = $user->id_establecimiento;
        $ups = $request->ups;
        $upsPg = "{" . implode(",", $ups) . "}";

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_prestaciones_upss(?, ?)
        ", [
            $establecimiento,
            $upsPg
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_ups_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }

    public function reporteAtencionesDA(Request $request)
    {
        $establecimiento = $request->ugipress;
        $tipos_atencion = $request->tipoAtencionMultiple;
        if (!in_array($establecimiento, [75, 76, 77, 26])) {
            if (in_array(2, $tipos_atencion)) {
                if (!in_array(7, $tipos_atencion)) {
                    $tipos_atencion[] = 7;
                }
            }
        }
        $tipos_atencion_Pg = "{" . implode(",", $tipos_atencion) . "}";
        $fechaInicio = $request->fechaIni;
        $fechaFin = $request->fechaFin;
        $condicionEstablecimiento = $request->input('condicionEstablecimiento') == "" ? null : $request->input('condicionEstablecimiento');
        $condicionServicio = $request->input('condicionServicio') == "" ? null : $request->input('condicionServicio');

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_atenciones_datos_atencion(?, ?, ?, ?, ?, ?)
        ", [
            $establecimiento,
            $tipos_atencion_Pg,
            $fechaInicio,
            $fechaFin,
            $condicionEstablecimiento,
            $condicionServicio,
        ]);
        return response()->json($resultados);
    }

    public function reporteAtencionesDAExport(Request $request)
    {
        $establecimiento = $request->ugipress;
        $tipos_atencion = $request->tipoAtencionMultiple;
                if (!in_array($establecimiento, [75, 76, 77, 26])) {
            if (in_array(2, $tipos_atencion)) {
                if (!in_array(7, $tipos_atencion)) {
                    $tipos_atencion[] = 7;
                }
            }
        }
        $tipos_atencion_Pg = "{" . implode(",", $tipos_atencion) . "}";
        $fechaInicio = $request->fechaIni;
        $fechaFin = $request->fechaFin;
        $condicionEstablecimiento = $request->input('condicionEstablecimiento') == "" ? null : $request->input('condicionEstablecimiento');
        $condicionServicio = $request->input('condicionServicio') == "" ? null : $request->input('condicionServicio');

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_atenciones_datos_atencion(?, ?, ?, ?, ?, ?)
        ", [
            $establecimiento,
            $tipos_atencion_Pg,
            $fechaInicio,
            $fechaFin,
            $condicionEstablecimiento,
            $condicionServicio,
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_DA_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }

    public function reporteAtencionesTA()
    {
        $user = Auth::user();
        $establecimiento = $user->id_establecimiento;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_atenciones_tipo_atencion(?)
        ", [
            $establecimiento
        ]);
        return response()->json($resultados);
    }

    public function reporteAtencionesTAExport(Request $request)
    {
        $user = Auth::user();
        $establecimiento = $user->id_establecimiento;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_atenciones_tipo_atencion(?)
        ", [
            $establecimiento
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_TA_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }

    public function reporteAtencionesIpress(Request $request)
    {
        $establecimientos = $request->ipress;
        $establecimientosPg = "{" . implode(",", $establecimientos) . "}";

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_atenciones_datos_ipress(?)
        ", [
            $establecimientosPg,
        ]);
        return response()->json($resultados);
    }

    public function reporteAtencionesIpressExport(Request $request)
    {
        $establecimientos = $request->ipress;
        $establecimientosPg = "{" . implode(",", $establecimientos) . "}";

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_atenciones_datos_ipress(?)
        ", [
            $establecimientosPg,
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_ipress_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }

    public function reporteAtencionesDiagnosticos(Request $request)
    {
        $user = Auth::user();
        $establecimiento = $user->id_establecimiento;
        $diagnosticos = $request->diagnosticos;
        $diagnosticosPg = "{" . implode(",", $diagnosticos) . "}";

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_atenciones_datos_diagnosticos(?, ?)
        ", [
            $establecimiento,
            $diagnosticosPg
        ]);
        return response()->json($resultados);
    }

    public function reporteAtencionesDiagnosticosExport(Request $request)
    {
        $user = Auth::user();
        $establecimiento = $user->id_establecimiento;
        $diagnosticos = $request->diagnosticos;
        $diagnosticosPg = "{" . implode(",", $diagnosticos) . "}";

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_atenciones_datos_diagnosticos(?, ?)
        ", [
            $establecimiento,
            $diagnosticosPg
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_diagnosticos_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }

    public function reporteAtencionesServicios(Request $request)
    {
        $user = Auth::user();
        $establecimiento = $user->id_establecimiento;
        $servicios = $request->servicios;
        $situacion = $request->situacion;
        $serviciosPg = "{" . implode(",", $servicios) . "}";

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_atenciones_datos_servicios(?, ?, ?)
        ", [
            $establecimiento,
            $serviciosPg,
            $situacion
        ]);
        return response()->json($resultados);
    }

    public function listarReporteProcedimientosCPT(Request $request)
    {
        $ipressPg = (isset($request->ipress) && is_array($request->ipress) && count($request->ipress))
            ? '{' . implode(',', $request->ipress) . '}'
            : null;
        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;
        $codigoCpt = $request->codigoCpt == "" ? null : $request->codigoCpt;
        $tipoCpt = $request->tipoCpt == "" ? null : $request->tipoCpt;
        $descripcion = $request->descripcion == "" ? null : $request->descripcion;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_procedimientos_cpt(?, ?, ?, ?, ?, ?)
        ", [
            $ipressPg,
            $fechaInicio,
            $fechaFin,
            $codigoCpt,
            $tipoCpt,
            $descripcion,
        ]);
        return response()->json($resultados);
    }

    public function listarReporteProcedimientosMedico(Request $request)
    {
        $ipressPg = (isset($request->ipress) && is_array($request->ipress) && count($request->ipress))
            ? '{' . implode(',', $request->ipress) . '}'
            : null;
        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;
        $codigoCpt = $request->codigoCpt == "" ? null : $request->codigoCpt;
        $tipoCpt = $request->tipoCpt == "" ? null : $request->tipoCpt;
        $descripcion = $request->descripcion == "" ? null : $request->descripcion;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_procedimientos_medicos(?, ?, ?, ?, ?, ?)
        ", [
            $ipressPg,
            $fechaInicio,
            $fechaFin,
            $codigoCpt,
            $tipoCpt,
            $descripcion,
        ]);
        return response()->json($resultados);
    }

    public function listarReporteProcedimientosCE(Request $request)
    {
        $ipressPg = (isset($request->ipress) && is_array($request->ipress) && count($request->ipress))
            ? '{' . implode(',', $request->ipress) . '}'
            : null;
        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;
        $codigoCpt = $request->codigoCpt == "" ? null : $request->codigoCpt;
        $tipoCpt = $request->tipoCpt == "" ? null : $request->tipoCpt;
        $descripcion = $request->descripcion == "" ? null : $request->descripcion;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_procedimientos_consulta_externa(?, ?, ?, ?, ?, ?)
        ", [
            $ipressPg,
            $fechaInicio,
            $fechaFin,
            $codigoCpt,
            $tipoCpt,
            $descripcion,
        ]);
        return response()->json($resultados);
    }

    public function listarReporteProcedimientosProduccionIndividual(Request $request)
    {
        $ipressPg = (isset($request->ipress) && is_array($request->ipress) && count($request->ipress))
            ? '{' . implode(',', $request->ipress) . '}'
            : null;
        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;
        //$codigoCpt = $request->codigoCpt == "" ? null : $request->codigoCpt;
        $codigoCpt = $request->codigoCpt !== "" ? $request->codigoCpt : null;
        $tipoCpt = ($request->tipoCpt !== "" && $request->tipoCpt !== null) ? (int)$request->tipoCpt : null;
        //$descripcion = $request->descripcion == "" ? null : $request->descripcion;
        $descripcion = $request->descripcion !== "" ? $request->descripcion : null;
        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_procedimientos_por_produccion_individual(?, ?, ?, ?, ?, ?)
        ", [
            $ipressPg,
            $fechaInicio,
            $fechaFin,
            $codigoCpt,
            $tipoCpt,
            $descripcion,
        ]);
        
        return response()->json($resultados);
    }
    
    public function listarReporteProcedimientosPaciente(Request $request)
    {
        $ipressPg = (isset($request->ipress) && is_array($request->ipress) && count($request->ipress))
            ? '{' . implode(',', $request->ipress) . '}'
            : null;
        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;
        $codigoCpt = $request->codigoCpt == "" ? null : $request->codigoCpt;
        $tipoCpt = $request->tipoCpt == "" ? null : $request->tipoCpt;
        $descripcion = $request->descripcion == "" ? null : $request->descripcion;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_procedimientos_por_paciente(?, ?, ?, ?, ?, ?)
        ", [
            $ipressPg,
            $fechaInicio,
            $fechaFin,
            $codigoCpt,
            $tipoCpt,
            $descripcion,
        ]);
      
        return response()->json($resultados);
    }

        public function listarReporteProcedimientosPacienteU(Request $request)
    {
        $ipressPg = (isset($request->ipress) && is_array($request->ipress) && count($request->ipress))
            ? '{' . implode(',', $request->ipress) . '}'
            : null;
        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;
        $codigoCpt = $request->codigoCpt == "" ? null : $request->codigoCpt;
        $tipoCpt = $request->tipoCpt == "" ? null : $request->tipoCpt;
        $descripcion = $request->descripcion == "" ? null : $request->descripcion;
       
        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_procedimientos_por_pacienteu(?, ?, ?, ?, ?, ?)
        ", [
            $ipressPg,
            $fechaInicio,
            $fechaFin,
            $codigoCpt,
            $tipoCpt,
            $descripcion,
        ]);
        return response()->json($resultados);
    }

    public function listarReporteEstrategiasTipoIndividual(Request $request)
    {
        $ipress = isset($request->ipress) && is_array($request->ipress)
            ? array_filter($request->ipress)
            : [];

        $ipressPg = count($ipress) > 0
            ? '{' . implode(',', $ipress) . '}'
            : null;
        $periodo = $request->periodo;
        $sexo = $request->sexo == "" ? null : $request->sexo;
        $codigoCpt = $request->codigoCpt == "" ? null : $request->codigoCpt;
        $edadDesde = $request->edadDesde == "" ? null : $request->edadDesde;
        $edadHasta = $request->edadHasta == "" ? null : $request->edadHasta;
        $estadoEst = $request->estadoEst == "" ? null : $request->estadoEst;
        $descripcion = $request->descripcion == "" ? null : $request->descripcion;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_estrategias_por_tipo_individual(?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $ipressPg,
            $periodo,
            $codigoCpt,
            $estadoEst,
            $descripcion,
            $sexo,
            $edadDesde,
            $edadHasta,
        ]);
        return response()->json($resultados);
    }

    public function listarReporteEstrategiasPaciente(Request $request)
    {
        $periodo = $request->periodo;
        $ipress = isset($request->ipress) && is_array($request->ipress)
            ? array_filter($request->ipress)
            : [];
        $ipressPg = count($ipress) > 0
            ? '{' . implode(',', $ipress) . '}'
            : null;
        $codigoEstrategia = $request->codigoCpt == "" ? null : $request->codigoCpt;
        $nombreEstrategia = $request->nombreEstrategia == "" ? null : $request->nombreEstrategia;
        $sexo = $request->sexo == "" ? null : $request->sexo;
        $responsable = $request->responsable == "" ? null : $request->responsable;
        $tipo_beneficiario = $request->tipo_beneficiario == "" ? null : $request->tipo_beneficiario;
        $paciente = $request->paciente == "" ? null : $request->paciente;
        $diagnostico = $request->diagnostico == "" ? null : $request->diagnostico;
        $edadDesde = $request->edadDesde == "" ? null : $request->edadDesde;
        $edadHasta = $request->edadHasta == "" ? null : $request->edadHasta;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_estrategias_por_paciente(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $ipressPg,
            $periodo,
            $codigoEstrategia,
            $nombreEstrategia,
            $sexo,
            $responsable,
            $tipo_beneficiario,
            $paciente,
            $diagnostico,
            $edadDesde,
            $edadHasta,
        ]);
        return response()->json($resultados);
    }

    public function exportarReporteEstrategiasPaciente(Request $request)
    {
        $periodo = $request->periodo;
        $ipress = isset($request->ipress) && is_array($request->ipress)
            ? array_filter($request->ipress)
            : [];
        $ipressPg = count($ipress) > 0
            ? '{' . implode(',', $ipress) . '}'
            : null;
        $codigoEstrategia = $request->codigoCpt == "" ? null : $request->codigoCpt;
        $nombreEstrategia = $request->nombreEstrategia == "" ? null : $request->nombreEstrategia;
        $sexo = $request->sexo == "" ? null : $request->sexo;
        $responsable = $request->responsable == "" ? null : $request->responsable;
        $tipo_beneficiario = $request->tipo_beneficiario == "" ? null : $request->tipo_beneficiario;
        $paciente = $request->paciente == "" ? null : $request->paciente;
        $diagnostico = $request->diagnostico == "" ? null : $request->diagnostico;
        $edadDesde = $request->edadDesde == "" ? null : $request->edadDesde;
        $edadHasta = $request->edadHasta == "" ? null : $request->edadHasta;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_estrategias_por_paciente(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $ipressPg,
            $periodo,
            $codigoEstrategia,
            $nombreEstrategia,
            $sexo,
            $responsable,
            $tipo_beneficiario,
            $paciente,
            $diagnostico,
            $edadDesde,
            $edadHasta,
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_estrategia_paciente_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }

    public function listarReporteEstrategiasTipoMasivo(Request $request)
    {
        $ipress = isset($request->ipress) && is_array($request->ipress)
            ? array_filter($request->ipress)
            : [];

        $ipressPg = count($ipress) > 0
            ? '{' . implode(',', $ipress) . '}'
            : null;
        $periodo = $request->periodo;
        $codigoCpt = $request->codigoCpt == "" ? null : $request->codigoCpt;
        $nombre = $request->nombre == "" ? null : $request->nombre;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_estrategias_por_tipo_masivo(?, ?, ?, ?)
        ", [
            $ipressPg,
            $periodo,
            $codigoCpt,
            $nombre,
        ]);
        return response()->json($resultados);
    }
    public function exportarReporteEstrategiasTipoIndividual(Request $request)
    {
        $ipress = isset($request->ipress) && is_array($request->ipress)
            ? array_filter($request->ipress)
            : [];

        $ipressPg = count($ipress) > 0
            ? '{' . implode(',', $ipress) . '}'
            : null;
        $periodo = $request->periodo == "" ? null : $request->periodo;
        $sexo = $request->sexo == "" ? null : $request->sexo;
        $codigoCpt = $request->codigoCpt == "" ? null : $request->codigoCpt;
        $edadDesde = $request->edadDesde == "" ? null : $request->edadDesde;
        $edadHasta = $request->edadHasta == "" ? null : $request->edadHasta;
        $estadoEst = $request->estadoEst == "" ? null : $request->estadoEst;
        $descripcion = $request->descripcion == "" ? null : $request->descripcion;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_estrategias_por_tipo_individual_exportar(?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $ipressPg,
            $periodo,
            $codigoCpt,
            $estadoEst,
            $descripcion,
            $sexo,
            $edadDesde,
            $edadHasta,
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_estrategia_tipo_individual_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data, $request) {
                $excel->sheet('Reporte', function ($sheet) use ($data, $request) {

                    $sheet->setWidth('A', 15);
                    $sheet->setWidth('B', 50);
                    $sheet->setWidth('C', 70);
                    $sheet->setWidth('D', 25);
                    $sheet->setWidth('E', 15);
                    $sheet->setWidth('F', 15);
                    $sheet->setWidth('G', 15);
                    $sheet->setWidth('H', 25);
                    $sheet->setWidth('I', 25);

                    // 🔹 Título
                    $sheet->mergeCells('A1:H1');
                    $sheet->cell('A1', function ($cell) {
                        $cell->setValue('CONSOLIDADO DE ESTRATEGIAS REALIZADAS');
                        $cell->setAlignment('center');
                        $cell->setValignment('center');
                        $cell->setFont([
                            'bold' => true,
                            'size' => 14
                        ]);
                    });

                    // 🔹 Bordes negros completos
                    $sheet->cells('A1:H1', function ($cells) {
                        $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    });

                    /* $fechaIni = $request->fechaIni;
                    $fechaFin = $request->fechaFin; */

                    // 1. Crear el objeto DateTime desde el formato original
                    /* $dateIni = DateTime::createFromFormat('Y-m-d', $fechaIni);
                    $dateFin = DateTime::createFromFormat('Y-m-d', $fechaFin); */

                    // 2. Formatear a DD/MM/YYYY
                    /* $fecha_formateada_ini = $dateIni->format('d/m/Y');
                    $fecha_formateada_fin = $dateFin->format('d/m/Y'); */

                    // 3. Extraer el año
                    /* $anio = $dateIni->format('Y'); */

                    $periodo = $request->periodo;
                    $partes = explode("/", $periodo);
                    $anio = $partes[1];

                    // 🔹 Fila 2: Informacion de mes/periodo (sin borde)
                    $sheet->mergeCells('G3:H3');
                    $sheet->cell('G3', function ($cell) use ($request, $periodo, $anio) {
                        $cell->setValue("PERIODO: {$periodo}");
                        $cell->setAlignment('left');
                        $cell->setValignment('center');
                        $cell->setFont([
                            'bold'  => true,
                            'size'  => 11
                        ]);
                    });

                    $sheet->cell('B3', function ($cell) {
                        $cell->setValue("Consolidado");
                        $cell->setAlignment('center');
                        $cell->setValignment('center');
                        $cell->setFont([
                            'bold'  => true,
                            'size'  => 11
                        ]);
                    });

                    // 🔹 Fila 3: Informacion de año ()
                    $sheet->mergeCells('G4:H4');
                    $sheet->cell('G4', function ($cell) use ($anio) {
                        $cell->setValue("AÑO: {$anio}");
                        $cell->setAlignment('left');
                        $cell->setValignment('center');
                        $cell->setFont([
                            'bold'  => true,
                            'size'  => 11
                        ]);
                    });

                    /* $agrupado = [];

                    foreach ($data as $row) {
                        $codigo_ipress = $row['codigo_ipress'];

                        if (!isset($agrupado[$codigo_ipress])) {
                            $agrupado[$codigo_ipress] = [];
                        }

                        $agrupado[$codigo_ipress][] = [
                            'nombre_ipress' => $row['nombre_ipress'],
                            'codigo_estrategia'    => $row['codigo_estrategia'],
                            'estrategia'    => $row['estrategia'],
                            'estado'    => $row['estado'],
                            'total_general'    => $row['total_general'],
                            'total_ab'    => $row['total_a_b'],
                            'titular'    => $row['titular'],
                            'familiar'    => $row['familiar'],
                            'civil'    => $row['civil'],
                            'pagante'    => $row['pagante'],
                            'id_ipress'    => $row['id_ipress'],
                        ];
                    } */

                    $fila = 6;

                    $sheet->setCellValue("A{$fila}", "IPRESS");
                    $sheet->setCellValue("B{$fila}", "NOMBRE IPRESS");
                    $sheet->setCellValue("C{$fila}", "ESTRATEGIA");
                    $sheet->setCellValue("D{$fila}", "TOTAL GENERAL (a+b+c)");
                    $sheet->setCellValue("E{$fila}", "TOTAL (a+b)");
                    $sheet->setCellValue("F{$fila}", "TITULAR (a)");
                    $sheet->setCellValue("G{$fila}", "FAMILIAR (b)");
                    $sheet->setCellValue("H{$fila}", "ACCION CIVICA (c)");
                    $sheet->setCellValue("I{$fila}", "CIVILES TARIFA (d)");
                    $sheet->cells("A{$fila}:I{$fila}", function ($cells) {
                        $cells->setAlignment('center');
                        $cells->setValignment('center');
                        // Cambia el fondo a amarillo
                        $cells->setBackground('#FFFF00');
                    });

                    $sheet->getStyle("A{$fila}:I{$fila}")->getFont()->setBold(false);
                    $fila++;

                    foreach ($data as $ipress) {

                        // 🔹 Filas de detalle
                        $sheet->setCellValue("A{$fila}", $ipress['codigo_ipress']);
                        $sheet->getStyle("A{$fila}")->getFont()->setBold(false);
                        $sheet->setCellValue("B{$fila}", $ipress['nombre_ipress']);
                        $sheet->getStyle("B{$fila}")->getFont()->setBold(false);
                        $sheet->setCellValue("C{$fila}", $ipress['estrategia']);
                        $sheet->getStyle("C{$fila}")->getFont()->setBold(false);
                        $sheet->setCellValue("D{$fila}", $ipress['total_general']);
                        $sheet->getStyle("D{$fila}")->getFont()->setBold(true);
                        $sheet->setCellValue("E{$fila}", $ipress['total_a_b']);
                        $sheet->getStyle("E{$fila}")->getFont()->setBold(true);
                        $sheet->setCellValue("F{$fila}", $ipress['titular']);
                        $sheet->getStyle("F{$fila}")->getFont()->setBold(true);
                        $sheet->setCellValue("G{$fila}", $ipress['familiar']);
                        $sheet->getStyle("G{$fila}")->getFont()->setBold(true);
                        $sheet->setCellValue("H{$fila}", $ipress['civil']);
                        $sheet->getStyle("H{$fila}")->getFont()->setBold(true);
                        $sheet->setCellValue("I{$fila}", $ipress['pagante']);
                        $sheet->getStyle("I{$fila}")->getFont()->setBold(true);
                        $fila++;
                    }
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }

    public function exportarReporteEstrategiasTipoMasivo(Request $request)
    {
        $ipress = isset($request->ipress) && is_array($request->ipress)
            ? array_filter($request->ipress)
            : [];

        $ipressPg = count($ipress) > 0
            ? '{' . implode(',', $ipress) . '}'
            : null;
        $periodo = $request->periodo;
        $codigoCpt = $request->codigoCpt == "" ? null : $request->codigoCpt;
        $nombre = $request->nombre == "" ? null : $request->nombre;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_estrategias_por_tipo_masivo(?, ?, ?, ?)
        ", [
            $ipressPg,
            $periodo,
            $codigoCpt,
            $nombre,
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_estrategias_tipo_masivo' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }

    public function exportarReporteProcedimientosCPT(Request $request)
    {
        $ipress = isset($request->ipress) && is_array($request->ipress)
            ? array_filter($request->ipress, function ($v) {
                return $v !== null && $v !== "";
            })
            : null;
        $ipressPg = (!empty($ipress))
            ? '{' . implode(',', $ipress) . '}'
            : null;
        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;
        $codigoCpt = $request->codigoCpt == "" ? null : $request->codigoCpt;
        $tipoCpt = $request->tipoCpt == "" ? null : $request->tipoCpt;
        $descripcion = $request->descripcion == "" ? null : $request->descripcion;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_procedimientos_cpt(?, ?, ?, ?, ?, ?)
        ", [
            $ipressPg,
            $fechaInicio,
            $fechaFin,
            $codigoCpt,
            $tipoCpt,
            $descripcion,
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_procedimientos_cpt_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }

    public function exportarReporteProcedimientosMedico(Request $request)
    {
        $ipress = isset($request->ipress) && is_array($request->ipress)
            ? array_filter($request->ipress, function ($v) {
                return $v !== null && $v !== "";
            })
            : null;
        $ipressPg = (!empty($ipress))
            ? '{' . implode(',', $ipress) . '}'
            : null;
        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;
        $codigoCpt = $request->codigoCpt == "" ? null : $request->codigoCpt;
        $tipoCpt = $request->tipoCpt == "" ? null : $request->tipoCpt;
        $descripcion = $request->descripcion == "" ? null : $request->descripcion;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_procedimientos_medicos(?, ?, ?, ?, ?, ?)
        ", [
            $ipressPg,
            $fechaInicio,
            $fechaFin,
            $codigoCpt,
            $tipoCpt,
            $descripcion,
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_procedimientos_medico_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }

    public function exportarReporteProcedimientosCE(Request $request)
    {
        $ipress = isset($request->ipress) && is_array($request->ipress)
            ? array_filter($request->ipress, function ($v) {
                return $v !== null && $v !== "";
            })
            : null;
        $ipressPg = (!empty($ipress))
            ? '{' . implode(',', $ipress) . '}'
            : null;
        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;
        $codigoCpt = $request->codigoCpt == "" ? null : $request->codigoCpt;
        $tipoCpt = $request->tipoCpt == "" ? null : $request->tipoCpt;
        $descripcion = $request->descripcion == "" ? null : $request->descripcion;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_procedimientos_consulta_externa(?, ?, ?, ?, ?, ?)
        ", [
            $ipressPg,
            $fechaInicio,
            $fechaFin,
            $codigoCpt,
            $tipoCpt,
            $descripcion,
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_procedimientos_ce_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }

    /* public function exportarReporteProcedimientosProduccionIndividual(Request $request)
    {
        $ipress = isset($request->ipress) && is_array($request->ipress)
            ? array_filter($request->ipress, function ($v) {
                return $v !== null && $v !== "";
            })
            : null;
        $ipressPg = (!empty($ipress))
            ? '{' . implode(',', $ipress) . '}'
            : null;
        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;
        $codigoCpt = $request->codigoCpt == "" ? null : $request->codigoCpt;
        $tipoCpt = $request->tipoCpt == "" ? null : $request->tipoCpt;
        $descripcion = $request->descripcion == "" ? null : $request->descripcion;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_procedimientos_por_produccion_individual(?, ?, ?, ?, ?, ?)
        ", [
            $ipressPg,
            $fechaInicio,
            $fechaFin,
            $codigoCpt,
            $tipoCpt,
            $descripcion,
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_procedimientos_pi_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    } */
    public function exportarReporteProcedimientosProduccionIndividual(Request $request)
    {
        try {
            Log::info("EXPORTAR PROC P.I", [
                'request' => $request->all()
            ]);

            $ipress = isset($request->ipress) && is_array($request->ipress)
                ? array_filter($request->ipress, function ($v) {
                    return $v !== null && $v !== "";
                })
                : null;

            $ipressPg = (!empty($ipress))
                ? '{' . implode(',', $ipress) . '}'
                : null;

            $fechaInicio = $request->fechaInicio;
            $fechaFin = $request->fechaFin;
            $codigoCpt = $request->codigoCpt === "" ? null : $request->codigoCpt;
            $tipoCpt = $request->tipoCpt === "" ? null : $request->tipoCpt;
            $descripcion = $request->descripcion === "" ? null : $request->descripcion;

            $resultados = DB::select(
                "SELECT * FROM sp_reporte_seis_procedimientos_por_produccion_individual(?, ?, ?, ?, ?, ?)",
                [
                    $ipressPg,
                    $fechaInicio,
                    $fechaFin,
                    $codigoCpt,
                    $tipoCpt,
                    $descripcion,
                ]
            );

            $tipoReporte = $request->tipoReporte;
            $data = json_decode(json_encode($resultados), true);
            $filename = 'reporte_procedimientos_pi_' . date('Ymd_His');

            if ($tipoReporte === 'excel') {
                return Excel::create($filename, function ($excel) use ($data) {
                    $excel->sheet('Reporte', function ($sheet) use ($data) {
                        $sheet->fromArray($data);
                    });
                })->download('xlsx');
            }

            if ($tipoReporte === 'csv') {
                return Excel::create($filename, function ($excel) use ($data) {
                    $excel->sheet('Reporte', function ($sheet) use ($data) {
                        $sheet->fromArray($data);
                    });
                })->download('csv');
            }

            return response()->json([
                'error' => 'Tipo de reporte no válido',
            ], 400);
        } catch (\Throwable $e) {

            Log::error('ERROR EXPORTAR REPORTE PROCEDIMIENTOS P.I', [
                'message'   => $e->getMessage(),
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'trace'     => $e->getTraceAsString(),
                'request'   => $request->all()
            ]);

            return response()->json([
                'error'   => 'Error interno del servidor',
                'message' => 'Ocurrió un error al generar el reporte'
            ], 500);
        }
    }

    public function exportarReporteProcedimientosPaciente(Request $request)
    {
        $ipress = isset($request->ipress) && is_array($request->ipress)
            ? array_filter($request->ipress, function ($v) {
                return $v !== null && $v !== "";
            })
            : null;
        $ipressPg = (!empty($ipress))
            ? '{' . implode(',', $ipress) . '}'
            : null;
        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;
        $codigoCpt = $request->codigoCpt == "" ? null : $request->codigoCpt;
        $tipoCpt = $request->tipoCpt == "" ? null : $request->tipoCpt;
        $descripcion = $request->descripcion == "" ? null : $request->descripcion;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_procedimientos_por_paciente(?, ?, ?, ?, ?, ?)
        ", [
            $ipressPg,
            $fechaInicio,
            $fechaFin,
            $codigoCpt,
            $tipoCpt,
            $descripcion,
        ]);

        $tipoReporte = $request->tipoReporte;

        /* $data = json_decode(json_encode($resultados), true); */
        $data = collect($resultados)->map(function ($item) {
            $row = (array) $item;

            if (isset($row['medico_responsable'])) {
                $row['responsable_del_procedimiento'] = $row['medico_responsable'];
                unset($row['medico_responsable']);
            }

            return $row;
        })->toArray();
           
        $filename = 'reporte_procedimientos_paciente_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }

        public function exportarReporteProcedimientosPacienteU(Request $request)
    {
        $ipress = isset($request->ipress) && is_array($request->ipress)
            ? array_filter($request->ipress, function ($v) {
                return $v !== null && $v !== "";
            })
            : null;
        $ipressPg = (!empty($ipress))
            ? '{' . implode(',', $ipress) . '}'
            : null;
        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;
        $codigoCpt = $request->codigoCpt == "" ? null : $request->codigoCpt;
        $tipoCpt = $request->tipoCpt == "" ? null : $request->tipoCpt;
        $descripcion = $request->descripcion == "" ? null : $request->descripcion;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_procedimientos_por_pacienteu(?, ?, ?, ?, ?, ?)
        ", [
            $ipressPg,
            $fechaInicio,
            $fechaFin,
            $codigoCpt,
            $tipoCpt,
            $descripcion,
        ]);

        $tipoReporte = $request->tipoReporte;

        /* $data = json_decode(json_encode($resultados), true); */
        $data = collect($resultados)->map(function ($item) {
            $row = (array) $item;

            if (isset($row['medico_responsable'])) {
                $row['responsable_del_procedimiento'] = $row['medico_responsable'];
                unset($row['medico_responsable']);
            }

            return $row;
        })->toArray();

        $filename = 'reporte_procedimientos_paciente_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }

    public function reporteAtencionesServiciosExport(Request $request)
    {
        $user = Auth::user();
        $establecimiento = $user->id_establecimiento;
        $servicios = $request->servicios;
        $situacion = $request->situacion;
        $serviciosPg = "{" . implode(",", $servicios) . "}";

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_atenciones_datos_servicios(?, ?, ?)
        ", [
            $establecimiento,
            $serviciosPg,
            $situacion
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_servicios_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }

    public function reporteAtencionesResponsables(Request $request)
    {
        $medico_id = $request->medico_id;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_atenciones_datos_responsable(?)
        ", [
            $medico_id
        ]);
        return response()->json($resultados);
    }

    public function reporteAtencionesResponsablesExport(Request $request)
    {
        $medico_id = $request->medico_id;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_atenciones_datos_responsable(?)
        ", [
            $medico_id
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_responsables_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }

    public function reporteAtencionesPaciente(Request $request)
    {
        $establecimiento_id = Auth::user()->id_establecimiento;
        $asegurado_td = $request->asegurado_td != "" ? $request->asegurado_td : null;
        $asegurado_nro_doc = $request->asegurado_nro_doc != "" ? $request->asegurado_nro_doc : null;
        $asegurado_nro_cip = $request->asegurado_nro_cip != "" ? $request->asegurado_nro_cip : null;
        $asegurado_sexo = $request->asegurado_sexo != "" ? $request->asegurado_sexo : null;
        $asegurado_edad = $request->asegurado_edad != "" ? $request->asegurado_edad : null;
        $asegurado_edad_desde = $request->asegurado_edad_desde != "" ? $request->asegurado_edad_desde : null;
        $asegurado_edad_hasta = $request->asegurado_edad_hasta != "" ? $request->asegurado_edad_hasta : null;
        $asegurado_tipo_beneficiario = $request->asegurado_tipo_beneficiario != "" ? $request->asegurado_tipo_beneficiario : null;
        $asegurado_grado = $request->asegurado_grado != "" ? $request->asegurado_grado : null;
        $asegurado_situacion = $request->asegurado_situacion != "" ? $request->asegurado_situacion : null;
        $asegurado_discapacidad = $request->asegurado_discapacidad == "true" ? 1 : null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_atenciones_datos_paciente(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $establecimiento_id,
            $asegurado_td,
            $asegurado_nro_doc,
            $asegurado_nro_cip,
            $asegurado_sexo,
            $asegurado_edad,
            $asegurado_edad_desde,
            $asegurado_edad_hasta,
            $asegurado_tipo_beneficiario,
            $asegurado_grado,
            $asegurado_situacion,
            $asegurado_discapacidad,
        ]);
        return response()->json($resultados);
    }

    public function reporteAtencionesPacienteExport(Request $request)
    {
        $establecimiento_id = Auth::user()->id_establecimiento;
        $asegurado_td = $request->asegurado_td != "" ? $request->asegurado_td : null;
        $asegurado_nro_doc = $request->asegurado_nro_doc != "" ? $request->asegurado_nro_doc : null;
        $asegurado_nro_cip = $request->asegurado_nro_cip != "" ? $request->asegurado_nro_cip : null;
        $asegurado_sexo = $request->asegurado_sexo != "" ? $request->asegurado_sexo : null;
        $asegurado_edad = $request->asegurado_edad != "" ? $request->asegurado_edad : null;
        $asegurado_edad_desde = $request->asegurado_edad_desde != "" ? $request->asegurado_edad_desde : null;
        $asegurado_edad_hasta = $request->asegurado_edad_hasta != "" ? $request->asegurado_edad_hasta : null;
        $asegurado_tipo_beneficiario = $request->asegurado_tipo_beneficiario != "" ? $request->asegurado_tipo_beneficiario : null;
        $asegurado_grado = $request->asegurado_grado != "" ? $request->asegurado_grado : null;
        $asegurado_situacion = $request->asegurado_situacion != "" ? $request->asegurado_situacion : null;
        $asegurado_discapacidad = $request->asegurado_discapacidad == "true" ? 1 : null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_atenciones_datos_paciente(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $establecimiento_id,
            $asegurado_td,
            $asegurado_nro_doc,
            $asegurado_nro_cip,
            $asegurado_sexo,
            $asegurado_edad,
            $asegurado_edad_desde,
            $asegurado_edad_hasta,
            $asegurado_tipo_beneficiario,
            $asegurado_grado,
            $asegurado_situacion,
            $asegurado_discapacidad,
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_pacientes_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }

    public function reporteAtencionesPacienteUbigeo(Request $request)
    {
        $departamento_id = $request->departamento_id != "" ? $request->departamento_id : null;
        $provincia_id = $request->provincia_id != "" ? $request->provincia_id : null;
        $distrito_id = $request->distrito_id != "" ? $request->distrito_id : null;
        $id_ipress = Auth::user()->id_establecimiento;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_atenciones_datos_paciente_ubigeo(?, ?, ?, ?)
        ", [
            $id_ipress,
            $departamento_id,
            $provincia_id,
            $distrito_id,
        ]);
        return response()->json($resultados);
    }

    public function reporteAtencionesPacienteUbigeoExport(Request $request)
    {
        $departamento_id = $request->departamento_id != "" ? $request->departamento_id : null;
        $provincia_id = $request->provincia_id != "" ? $request->provincia_id : null;
        $distrito_id = $request->distrito_id != "" ? $request->distrito_id : null;
        $id_ipress = Auth::user()->id_establecimiento;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_atenciones_datos_paciente_ubigeo(?, ?, ?, ?)
        ", [
            $id_ipress,
            $departamento_id,
            $provincia_id,
            $distrito_id,
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_pacientes_ubigeo_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }

    public function reporteAtencionesAuditoria(Request $request)
    {
        $nro_documento = $request->nro_documento;
        $fecha_inicio = $request->fecha_inicio != "" ? $request->fecha_inicio : null;
        $fecha_fin = $request->fecha_fin != "" ? $request->fecha_fin : null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_atenciones_auditoria(?, ?, ?)
        ", [
            $nro_documento,
            $fecha_inicio,
            $fecha_fin,
        ]);
        return response()->json($resultados);
    }

    public function reporteAtencionesAuditoriaExport(Request $request)
    {
        $nro_documento = $request->nro_documento;
        $fecha_inicio = $request->fecha_inicio != "" ? $request->fecha_inicio : null;
        $fecha_fin = $request->fecha_fin != "" ? $request->fecha_fin : null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_atenciones_auditoria(?, ?, ?)
        ", [
            $nro_documento,
            $fecha_inicio,
            $fecha_fin,
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_auditoria_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }

    public function reporteConsolidadosAcciones()
    {
        $regiones_ipress = Establecimiento::where('id_estado_reg', 1)
            ->select("red_region")
            ->whereNotNull('red_region')
            ->where('red_region', '!=', '')
            ->groupBy('red_region')
            ->get();
        return view("frontend.seis.reporte_consolidados_acciones", compact("regiones_ipress"));
    }

    public function listarConsolidadoAcciones(Request $request)
    {
        $establecimientos = $request->ipress;
        $establecimientosPg = null;
        if ($establecimientos) {
            $establecimientosPg = "{" . implode(",", $establecimientos) . "}";
        }
        $fecha_inicio = $request->fechaIni != "" ? $request->fechaIni : null;
        $fecha_fin = $request->fechaFin != "" ? $request->fechaFin : null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_consolidado_general(?, ?, ?)
        ", [
            $establecimientosPg,
            $fecha_inicio,
            $fecha_fin,
        ]);
        return response()->json($resultados);
    }

    public function exportConsolidadoAcciones(Request $request)
    {
        $establecimientos = $request->ipress;
        $establecimientosPg = null;
        if ($establecimientos) {
            $establecimientosPg = "{" . implode(",", $establecimientos) . "}";
        }
        $fecha_inicio = $request->fechaIni != "" ? $request->fechaIni : null;
        $fecha_fin = $request->fechaFin != "" ? $request->fechaFin : null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_consolidado_general(?, ?, ?)
        ", [
            $establecimientosPg,
            $fecha_inicio,
            $fecha_fin,
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_consolidado_general_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data, $request) {
                $excel->sheet('Reporte', function ($sheet) use ($data, $request) {
                    $sheet->setWidth('A', 50);
                    $sheet->setWidth('B', 30);
                    $sheet->setWidth('C', 25);
                    $sheet->setWidth('D', 15);
                    $sheet->setWidth('E', 15);
                    $sheet->setWidth('F', 25);
                    $sheet->setWidth('G', 25);

                    // 🔹 Título
                    $sheet->mergeCells('A1:G1');
                    $sheet->cell('A1', function ($cell) {
                        $cell->setValue('CONSOLIDADO DE LAS ACCIONES DESARROLLADAS POR LA DIRSAL PNP');
                        $cell->setAlignment('center');
                        $cell->setValignment('center');
                        $cell->setFont([
                            'bold' => true,
                            'size' => 14
                        ]);
                    });

                    // 🔹 Bordes negros completos
                    $sheet->cells('A1:G1', function ($cells) {
                        $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    });

                    $fechaIni = $request->fechaIni;
                    $fechaFin = $request->fechaFin;

                    // 1. Crear el objeto DateTime desde el formato original
                    $dateIni = DateTime::createFromFormat('Y-m-d', $fechaIni);
                    $dateFin = DateTime::createFromFormat('Y-m-d', $fechaFin);

                    // 2. Formatear a DD/MM/YYYY
                    $fecha_formateada_ini = $dateIni->format('d/m/Y');
                    $fecha_formateada_fin = $dateFin->format('d/m/Y');

                    // 3. Extraer el año
                    $anio = $dateIni->format('Y');

                    // 🔹 Fila 2: Informacion de mes/periodo (sin borde)
                    $sheet->mergeCells('B3:D3');
                    $sheet->cell('B3', function ($cell) use ($fecha_formateada_fin, $fecha_formateada_ini) {
                        $cell->setValue("PERIODO: {$fecha_formateada_ini} - {$fecha_formateada_fin}");
                        $cell->setAlignment('left');
                        $cell->setValignment('center');
                        $cell->setFont([
                            'bold'  => true,
                            'size'  => 11
                        ]);
                    });

                    $sheet->cell('A4', function ($cell) {
                        $cell->setValue("Consolidado");
                        $cell->setAlignment('center');
                        $cell->setValignment('center');
                        $cell->setFont([
                            'bold'  => true,
                            'size'  => 11
                        ]);
                    });

                    // 🔹 Fila 3: Informacion de año ()
                    $sheet->mergeCells('B4:D4');
                    $sheet->cell('B4', function ($cell) use ($anio) {
                        $cell->setValue("AÑO: {$anio}");
                        $cell->setAlignment('left');
                        $cell->setValignment('center');
                        $cell->setFont([
                            'bold'  => true,
                            'size'  => 11
                        ]);
                    });

                    $fila = 5;

                    $sheet->mergeCells("A{$fila}:B{$fila}");
                    $sheet->getStyle("A{$fila}")->getFont()->setBold(true);
                    $fila++;
                    $fila++;

                    // 🔹 Cabecera de consultorios

                    // ATENCIONES (NUEVOS + REINGRESANTES + CONTINUADORES)
                    $sheet->mergeCells("B{$fila}:G{$fila}");
                    $sheet->setCellValue("B{$fila}", "ATENCIONES");

                    $sheet->cells("B{$fila}:G{$fila}", function ($cells) {
                        $cells->setFont([
                            'bold' => true
                        ]);
                        $cells->setAlignment('center');
                        $cells->setValignment('center');
                        $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    });

                    $fila++;

                    $sheet->mergeCells("C{$fila}:E{$fila}");
                    $sheet->setCellValue("C{$fila}", "BENEFICIARIOS FOSPOLI");

                    $sheet->cells("A{$fila}:E{$fila}", function ($cells) {
                        $cells->setAlignment('center');
                        $cells->setValignment('center');
                    });

                    $filaSuperior = $fila;

                    $sheet->setCellValue("A{$fila}", "CONSULTA EXTERNA");
                    $sheet->setCellValue("B{$fila}", "TOTAL GENERAL (a+b+c+d)");
                    $sheet->cells("A{$filaSuperior}:G{$fila}", function ($cells) {
                        $cells->setAlignment('center');
                        $cells->setValignment('center');
                    });

                    $fila++;

                    $sheet->mergeCells("A{$filaSuperior}:A{$fila}");
                    $sheet->mergeCells("B{$filaSuperior}:B{$fila}");
                    $sheet->mergeCells("F{$filaSuperior}:F{$fila}");
                    $sheet->mergeCells("G{$filaSuperior}:G{$fila}");
                    $sheet->setCellValue("C{$fila}", "TOTAL GENERAL (a+b)");
                    $sheet->setCellValue("D{$fila}", "TITULAR (a)");
                    $sheet->setCellValue("E{$fila}", "FAMILIAR (b)");
                    $sheet->setCellValue("F{$filaSuperior}", "ACCION CIVICA (c)");
                    $sheet->setCellValue("G{$filaSuperior}", "CIVILES TARIFA (d)");
                    $sheet->cells("A{$filaSuperior}:G{$fila}", function ($cells) {
                        $cells->setAlignment('center');
                        $cells->setValignment('center');
                    });

                    $fila++;

                    $grupos = [];

                    foreach ($data as $servicio) {
                        $grupo = $servicio['grupo']; // MEDICO, NO MEDICO, EGRESOS HOSPITALARIOS
                        $grupos[$grupo][] = $servicio;
                    }

                    /* foreach ($data as $servicio) {

                        // 🔹 Filas de detalle
                        $sheet->setCellValue("A{$fila}", $servicio['descripcion']);
                        $sheet->getStyle("A{$fila}")->getFont()->setBold(false);
                        $sheet->setCellValue("B{$fila}", $servicio['total']);
                        $sheet->getStyle("B{$fila}")->getFont()->setBold(false);
                        $sheet->setCellValue("C{$fila}", $servicio['total']);
                        $sheet->getStyle("C{$fila}")->getFont()->setBold(false);
                        $sheet->setCellValue("D{$fila}", $servicio['titular']);
                        $sheet->getStyle("D{$fila}")->getFont()->setBold(true);
                        $sheet->setCellValue("E{$fila}", $servicio['familiar']);
                        $sheet->getStyle("E{$fila}")->getFont()->setBold(true);
                        $sheet->setCellValue("F{$fila}", $servicio['civica']);
                        $sheet->getStyle("F{$fila}")->getFont()->setBold(true);
                        $sheet->setCellValue("G{$fila}", $servicio['tarifa']);
                        $fila++;
                    } */

                    foreach ($grupos as $nombreGrupo => $servicios) {

                        // Totales del grupo
                        $totalGeneral  = 0;
                        $totalTitular  = 0;
                        $totalFamiliar = 0;
                        $totalCivica   = 0;
                        $totalTarifa   = 0;

                        foreach ($servicios as $s) {
                            $totalGeneral  += $s['total'];
                            $totalTitular  += $s['titular'];
                            $totalFamiliar += $s['familiar'];
                            $totalCivica   += $s['civica'];
                            $totalTarifa   += $s['tarifa'];
                        }

                        // 🔹 Fila cabecera del grupo
                        $sheet->setCellValue("A{$fila}", $nombreGrupo);
                        $sheet->setCellValue("B{$fila}", $totalGeneral);
                        $sheet->setCellValue("C{$fila}", $totalGeneral);
                        $sheet->setCellValue("D{$fila}", $totalTitular);
                        $sheet->setCellValue("E{$fila}", $totalFamiliar);
                        $sheet->setCellValue("F{$fila}", $totalCivica);
                        $sheet->setCellValue("G{$fila}", $totalTarifa);

                        $sheet->getStyle("A{$fila}:G{$fila}")->getFont()->setBold(true);
                        $sheet->getStyle("A{$fila}:G{$fila}")->getFill()
                            ->getStartColor()->setARGB('FFEFEFEF');

                        $sheet->getStyle("A{$fila}:G{$fila}")
                            ->getBorders()
                            ->getTop()
                            ->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

                        $fila++;

                        // 3️⃣ Detalle de servicios del grupo
                        foreach ($servicios as $servicio) {

                            $sheet->setCellValue("A{$fila}", $servicio['descripcion']);
                            $sheet->setCellValue("B{$fila}", $servicio['total']);
                            $sheet->setCellValue("C{$fila}", $servicio['total']);
                            $sheet->setCellValue("D{$fila}", $servicio['titular']);
                            $sheet->setCellValue("E{$fila}", $servicio['familiar']);
                            $sheet->setCellValue("F{$fila}", $servicio['civica']);
                            $sheet->setCellValue("G{$fila}", $servicio['tarifa']);

                            $sheet->getStyle("A{$fila}:G{$fila}")->getFont()->setBold(false);

                            $fila++;
                        }

                        // Fila en blanco entre grupos (opcional)
                    }
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }

    public function listarConsolidadoAccionesAtenciones(Request $request)
    {
        $fecha_inicio = $request->fechaIni != "" ? $request->fechaIni : null;
        $fecha_fin = $request->fechaFin != "" ? $request->fechaFin : null;
        $tipoAtencion = $request->tipoAtencionAt != "" ? $request->tipoAtencionAt : null;
        $tipoConsulta = $request->tipoConsultaAt != "" ? $request->tipoConsultaAt : null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_consolidado_ipress_atenciones(?, ?, ?, ?)
        ", [
            $tipoAtencion,
            $fecha_inicio,
            $fecha_fin,
            $tipoConsulta,
        ]);
        return response()->json($resultados);
    }

    public function exportConsolidadoAccionesAtenciones(Request $request)
    {
        $fecha_inicio = $request->fechaIni != "" ? $request->fechaIni : null;
        $fecha_fin = $request->fechaFin != "" ? $request->fechaFin : null;
        $tipoAtencion = $request->tipoAtencionAt != "" ? $request->tipoAtencionAt : null;
        $tipoConsulta = $request->tipoConsultaAt != "" ? $request->tipoConsultaAt : null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_consolidado_ipress_atenciones(?, ?, ?, ?)
        ", [
            $tipoAtencion,
            $fecha_inicio,
            $fecha_fin,
            $tipoConsulta,
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_consolidado_atenciones_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }
    public function listarConsolidadoAccionesFarmacia(Request $request)
    {
        $establecimiento = Auth::user()->id_establecimiento;
        $fecha_inicio = $request->fechaIni != "" ? $request->fechaIni : null;
        $fecha_fin = $request->fechaFin != "" ? $request->fechaFin : null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_consolidado_ipress_farmacia(?, ?, ?)
        ", [
            $establecimiento,
            $fecha_inicio,
            $fecha_fin,
        ]);
        return response()->json($resultados);
    }

    public function listarConsolidadoAccionesFarmaciaExtra(Request $request)
    {
        $establecimiento = Auth::user()->id_establecimiento;
        $fecha_inicio = $request->fechaIni != "" ? $request->fechaIni : null;
        $fecha_fin = $request->fechaFin != "" ? $request->fechaFin : null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_consolidado_ipress_farmacia_detallado(?, ?, ?)
        ", [
            $establecimiento,
            $fecha_inicio,
            $fecha_fin,
        ]);
        return response()->json($resultados);
    }

    public function exportConsolidadoAccionesFarmacia(Request $request)
    {
        $establecimiento = Auth::user()->id_establecimiento;
        $fecha_inicio = $request->fechaIni != "" ? $request->fechaIni : null;
        $fecha_fin = $request->fechaFin != "" ? $request->fechaFin : null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_consolidado_ipress_farmacia(?, ?, ?)
        ", [
            $establecimiento,
            $fecha_inicio,
            $fecha_fin,
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_consolidado_farmacia_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }

    public function exportConsolidadoAccionesFarmaciaExtra(Request $request)
    {
        $establecimiento = Auth::user()->id_establecimiento;
        $fecha_inicio = $request->fechaIni != "" ? $request->fechaIni : null;
        $fecha_fin = $request->fechaFin != "" ? $request->fechaFin : null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_consolidado_ipress_farmacia_detallado(?, ?, ?)
        ", [
            $establecimiento,
            $fecha_inicio,
            $fecha_fin,
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_consolidado_farmacia_extra_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }

    public function listarConsolidadoAccionesProcedimiento(Request $request)
    {
        $tipo_examen = $request->tipo_examen != "" ? $request->tipo_examen : null;
        $fecha_inicio = $request->fechaIni != "" ? $request->fechaIni : null;
        $fecha_fin = $request->fechaFin != "" ? $request->fechaFin : null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_consolidado_ipress_procedimiento(?, ?, ?)
        ", [
            $tipo_examen,
            $fecha_inicio,
            $fecha_fin,
        ]);
        return response()->json($resultados);
    }

    public function listarConsolidadoAccionesProcedimientoExtra(Request $request)
    {
        /* $establecimiento = Auth::user()->id_establecimiento; */
        $establecimiento = $request->ipress_id != "" ? $request->ipress_id : null;
        $tipo_examen = $request->tipo_examen != "" ? $request->tipo_examen : null;
        $fecha_inicio = $request->fechaIni != "" ? $request->fechaIni : null;
        $fecha_fin = $request->fechaFin != "" ? $request->fechaFin : null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_consolidado_ipress_procedimiento_detallado(?, ?, ?, ?)
        ", [
            $establecimiento,
            $tipo_examen,
            $fecha_inicio,
            $fecha_fin,
        ]);
        return response()->json($resultados);
    }

    public function exportConsolidadoAccionesProcedimiento(Request $request)
    {
        $tipoExamen = $request->tipoExamen != "" ? $request->tipoExamen : null;
        $fecha_inicio = $request->fechaIni != "" ? $request->fechaIni : null;
        $fecha_fin = $request->fechaFin != "" ? $request->fechaFin : null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_consolidado_ipress_procedimiento(?, ?, ?)
        ", [
            $tipoExamen,
            $fecha_inicio,
            $fecha_fin,
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_consolidado_proc_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }

    public function exportConsolidadoAccionesProcedimientoExtra(Request $request)
    {
        $establecimiento = Auth::user()->id_establecimiento;
        $tipoExamen = $request->tipoExamen != "" ? $request->tipoExamen : null;
        $fecha_inicio = $request->fechaIni != "" ? $request->fechaIni : null;
        $fecha_fin = $request->fechaFin != "" ? $request->fechaFin : null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_consolidado_ipress_procedimiento_detallado(?, ?, ?, ?)
        ", [
            $establecimiento,
            $tipoExamen,
            $fecha_inicio,
            $fecha_fin,
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_consolidado_proc_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }

    public function buscarTipoExamenCA(Request $request)
    {
        $categorias = DB::table('categoria_radiologias as cr')
            ->join('categoria_radiologia_nivels as crn', 'cr.id', '=', 'crn.id_categoria_radiologia')
            ->select('cr.id', 'cr.nombre', 'crn.id_nivel')
            ->where('crn.estado', 1)
            ->where('crn.id_nivel', $request->nivel_ipress)
            ->groupBy('cr.id', 'cr.nombre', 'crn.id_nivel')
            ->get();

        return response()->json($categorias);
    }

    public function reportePartos()
    {
        $regiones_ipress = Establecimiento::where('id_estado_reg', 1)
            ->select("red_region")
            ->whereNotNull('red_region')
            ->where('red_region', '!=', '')
            ->groupBy('red_region')
            ->get();
        $tipoBeneficiarios = Tipo_beneficiario::select("descripcion", "id", "codigo")
            ->get();
        $grados = Titular_grado::where('estado', 1)
            ->select("descripcion", "id", "descripcion_corta", "codigo")
            ->get();
        return view("frontend.seis.reporte_partos", compact("regiones_ipress", "tipoBeneficiarios", "grados"));
    }

    public function reporteVigilancia()
    {
        $regiones_ipress = Establecimiento::where('id_estado_reg', 1)
            ->select("red_region")
            ->whereNotNull('red_region')
            ->where('red_region', '!=', '')
            ->groupBy('red_region')
            ->get();
        $tipoBeneficiarios = Tipo_beneficiario::select("descripcion", "id", "codigo")
            ->get();
        $serviciosParaUps = Consultorio::where("estado", "=", 4)->get();
        return view("frontend.seis.reporte_vigilancia", compact("regiones_ipress", "tipoBeneficiarios", "serviciosParaUps"));
    }

    public function reporteDiasCamas()
    {
        $regiones_ipress = Establecimiento::where('id_estado_reg', 1)
            ->select("red_region")
            ->whereNotNull('red_region')
            ->where('red_region', '!=', '')
            ->groupBy('red_region')
            ->get();
        return view("frontend.seis.reporte_dias_cama", compact("regiones_ipress"));
    }

    public function reporteProcedimientos()
    {
        $regiones_ipress = Establecimiento::where('id_estado_reg', 1)
            ->select("red_region")
            ->whereNotNull('red_region')
            ->where('red_region', '!=', '')
            ->groupBy('red_region')
            ->get();
        $secciones = DB::table('seccion_procedimientos')
            ->where('estado', 1)
            ->get();

        return view("frontend.seis.reporte_procedimientos", compact("regiones_ipress", "secciones"));
    }

    public function reportePorTipo()
    {
        $regiones_ipress = Establecimiento::where('id_estado_reg', 1)
            ->select("red_region")
            ->whereNotNull('red_region')
            ->where('red_region', '!=', '')
            ->groupBy('red_region')
            ->get();
        $secciones = DB::table('seccion_procedimientos')
            ->where('estado', 1)
            ->get();

        return view("frontend.seis.reporte_por_tipo", compact("regiones_ipress", "secciones"));
    }

    public function reportePorPaciente()
    {
        $regiones_ipress = Establecimiento::where('id_estado_reg', 1)
            ->select("red_region")
            ->whereNotNull('red_region')
            ->where('red_region', '!=', '')
            ->groupBy('red_region')
            ->get();
        $tipoBeneficiarios = Tipo_beneficiario::select("descripcion", "id", "codigo")
            ->get();
        return view("frontend.seis.reporte_por_paciente", compact("regiones_ipress", "tipoBeneficiarios"));
    }

    public function reporteMortalidad()
    {
        $regiones_ipress = Establecimiento::where('id_estado_reg', 1)
            ->select("red_region")
            ->whereNotNull('red_region')
            ->where('red_region', '!=', '')
            ->groupBy('red_region')
            ->get();
        $serviciosParaUps = Consultorio::where("estado", "=", 4)->get();
        $grupos_edad = DB::table('v_edadnombres')->get();

        return view("frontend.seis.reporte_mortalidad", compact("regiones_ipress", "serviciosParaUps", "grupos_edad"));
    }

    public function reporteMorbilidad()
    {
        $regiones_ipress = Establecimiento::where('id_estado_reg', 1)
            ->select("red_region")
            ->whereNotNull('red_region')
            ->where('red_region', '!=', '')
            ->groupBy('red_region')
            ->get();
        $grupos_edad = DB::table('v_edadnombres')->get();
        $tipoBeneficiarios = Tipo_beneficiario::select("descripcion", "id", "codigo")
            ->get();
        return view("frontend.seis.reporte_morbilidad", compact("regiones_ipress", "grupos_edad", "tipoBeneficiarios"));
    }

    public function reporteContraReferencia()
    {
        $regiones_ipress = Establecimiento::where('id_estado_reg', 1)
            ->select("red_region")
            ->whereNotNull('red_region')
            ->where('red_region', '!=', '')
            ->groupBy('red_region')
            ->get();
        $tipoBeneficiarios = Tipo_beneficiario::select("descripcion", "id", "codigo")
            ->get();
        $establecimientos = Establecimiento::select("codigo", "nombre", "id")->where("id_estado_reg", "=", 1)->get();
        return view("frontend.seis.reporte_contrareferencia", compact("regiones_ipress", "tipoBeneficiarios", "establecimientos"));
    }

    public function reporteReferencia()
    {
        $regiones_ipress = Establecimiento::where('id_estado_reg', 1)
            ->select("red_region")
            ->whereNotNull('red_region')
            ->where('red_region', '!=', '')
            ->groupBy('red_region')
            ->get();
        $tipoBeneficiarios = Tipo_beneficiario::select("descripcion", "id", "codigo")
            ->get();
        $establecimientos = Establecimiento::select("codigo", "nombre", "id")->where("id_estado_reg", "=", 1)->get();
        return view("frontend.seis.reporte_referencia", compact("regiones_ipress", "tipoBeneficiarios", "establecimientos"));
    }

    public function listarReporteReferencias(Request $request)
    {
        $ipress = isset($request->ipress) && is_array($request->ipress)
            ? array_filter($request->ipress, function ($v) {
                return $v !== null && $v !== "";
            })
            : null;
        $ipressPg = (!empty($ipress))
            ? '{' . implode(',', $ipress) . '}'
            : null;
        $p_anio                = $request->anio ?: null;
        $p_sexo                = $request->sexo ?: null;
        $p_responsable         = $request->responsable ?: null;
        $p_diagnostico         = $request->diagnostico ?: null;
        $p_beneficiario        = $request->tipo_beneficiario ?: null;
        $p_paciente            = $request->paciente ?: null;
        $p_upss                = $request->upss ?: null;
        $p_fecha_ini           = $request->fechaDesde ?: null;
        $p_fecha_fin           = $request->fechaHasta ?: null;
        $p_ipress_destino      = $request->ipressDestino ?: null;
        $p_procedimiento       = $request->procedimiento ?: null;
        $p_edad_ini            = $request->edadDesde ?: null;
        $p_edad_fin            = $request->edadHasta ?: null;

        $resultados = DB::select("
        SELECT * FROM sp_reporte_seis_referencia(
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )
    ", [
            $ipressPg,
            $p_anio,
            $p_fecha_ini,
            $p_fecha_fin,
            $p_sexo,
            $p_responsable,
            $p_diagnostico,
            $p_beneficiario,
            $p_paciente,
            $p_upss,
            $p_ipress_destino,
            $p_procedimiento,
            $p_edad_ini,
            $p_edad_fin,
        ]);
        return response()->json($resultados);
    }
    public function exportarReporteReferencias(Request $request)
    {
        $ipress = isset($request->ipress) && is_array($request->ipress)
            ? array_filter($request->ipress, function ($v) {
                return $v !== null && $v !== "";
            })
            : null;
        $ipressPg = (!empty($ipress))
            ? '{' . implode(',', $ipress) . '}'
            : null;
        $p_anio                = $request->anio ?: null;
        $p_sexo                = $request->sexo ?: null;
        $p_responsable         = $request->responsable ?: null;
        $p_diagnostico         = $request->diagnostico ?: null;
        $p_beneficiario        = $request->tipo_beneficiario ?: null;
        $p_paciente            = $request->paciente ?: null;
        $p_upss                = $request->upss ?: null;
        $p_fecha_ini           = $request->fechaDesde ?: null;
        $p_fecha_fin           = $request->fechaHasta ?: null;
        $p_ipress_destino      = $request->ipressDestino ?: null;
        $p_procedimiento       = $request->procedimiento ?: null;
        $p_edad_ini            = $request->edadDesde ?: null;
        $p_edad_fin            = $request->edadHasta ?: null;

        $resultados = DB::select("
        SELECT * FROM sp_reporte_seis_referencia(
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )
    ", [
            $ipressPg,
            $p_anio,
            $p_fecha_ini,
            $p_fecha_fin,
            $p_sexo,
            $p_responsable,
            $p_diagnostico,
            $p_beneficiario,
            $p_paciente,
            $p_upss,
            $p_ipress_destino,
            $p_procedimiento,
            $p_edad_ini,
            $p_edad_fin,
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_referencias_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }
    public function listarReporteContraReferencias(Request $request)
    {
        $ipress = isset($request->ipress) && is_array($request->ipress)
            ? array_filter($request->ipress, function ($v) {
                return $v !== null && $v !== "";
            })
            : null;
        $ipressPg = (!empty($ipress))
            ? '{' . implode(',', $ipress) . '}'
            : null;
        $p_anio                = $request->anio ?: null;
        $p_sexo                = $request->sexo ?: null;
        $p_responsable         = $request->responsable ?: null;
        $p_diagnostico         = $request->diagnostico ?: null;
        $p_beneficiario        = $request->tipo_beneficiario ?: null;
        $p_paciente            = $request->paciente ?: null;
        $p_upss                = $request->upss ?: null;
        $p_fecha_ini           = $request->fechaDesde ?: null;
        $p_fecha_fin           = $request->fechaHasta ?: null;
        $p_ipress_destino      = $request->ipressDestino ?: null;
        $p_procedimiento       = $request->procedimiento ?: null;
        $p_edad_ini            = $request->edadDesde ?: null;
        $p_edad_fin            = $request->edadHasta ?: null;

        $resultados = DB::select("
        SELECT * FROM sp_reporte_seis_contrareferencia(
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )
    ", [
            $ipressPg,
            $p_anio,
            $p_fecha_ini,
            $p_fecha_fin,
            $p_sexo,
            $p_responsable,
            $p_diagnostico,
            $p_beneficiario,
            $p_paciente,
            $p_upss,
            $p_ipress_destino,
            $p_procedimiento,
            $p_edad_ini,
            $p_edad_fin,
        ]);
        return response()->json($resultados);
    }
    public function exportarReporteContraReferencias(Request $request)
    {
        $ipress = isset($request->ipress) && is_array($request->ipress)
            ? array_filter($request->ipress, function ($v) {
                return $v !== null && $v !== "";
            })
            : null;
        $ipressPg = (!empty($ipress))
            ? '{' . implode(',', $ipress) . '}'
            : null;
        $p_anio                = $request->anio ?: null;
        $p_sexo                = $request->sexo ?: null;
        $p_responsable         = $request->responsable ?: null;
        $p_diagnostico         = $request->diagnostico ?: null;
        $p_beneficiario        = $request->tipo_beneficiario ?: null;
        $p_paciente            = $request->paciente ?: null;
        $p_upss                = $request->upss ?: null;
        $p_fecha_ini           = $request->fechaDesde ?: null;
        $p_fecha_fin           = $request->fechaHasta ?: null;
        $p_ipress_destino      = $request->ipressDestino ?: null;
        $p_procedimiento       = $request->procedimiento ?: null;
        $p_edad_ini            = $request->edadDesde ?: null;
        $p_edad_fin            = $request->edadHasta ?: null;

        $resultados = DB::select("
        SELECT * FROM sp_reporte_seis_contrareferencia(
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )
    ", [
            $ipressPg,
            $p_anio,
            $p_fecha_ini,
            $p_fecha_fin,
            $p_sexo,
            $p_responsable,
            $p_diagnostico,
            $p_beneficiario,
            $p_paciente,
            $p_upss,
            $p_ipress_destino,
            $p_procedimiento,
            $p_edad_ini,
            $p_edad_fin,
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_referencias_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }

    public function listarReportePartos(Request $request)
    {
        $ipress                  = $request->ipress ?: null;
        $p_ipress                = is_array($ipress) ? '{' . implode(',', $ipress) . '}' : null;
        $p_anio                  = $request->anio ?: null;
        $p_fechaIni              = $request->fechaIni ?: null;
        $p_fechaFin              = $request->fechaFin ?: null;
        $p_sexo_nacido           = $request->sexoRN ?: null;
        $p_responsable           = $request->nombrePaciente ?: null;
        $p_diagnostico           = $request->diagnostico ?: null;
        $p_tipo_beneficiario_id  = $request->tipo_beneficiario ?: null;
        $p_grado                 = $request->grado ?: null;
        $p_paciente              = $request->paciente ?: null;
        $p_complicacion          = $request->complicacion ?: null;
        $p_edad_desde            = $request->edadDesde ?: null;
        $p_edad_hasta            = $request->edadHasta ?: null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_partos(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $p_ipress,
            $p_anio,
            $p_fechaIni,
            $p_fechaFin,
            $p_sexo_nacido,
            $p_responsable,
            $p_diagnostico,
            $p_tipo_beneficiario_id,
            $p_grado,
            $p_paciente,
            $p_complicacion,
            $p_edad_desde,
            $p_edad_hasta,
        ]);
        return response()->json($resultados);
    }
    public function exportarReportePartos(Request $request)
    {
        $ipress                  = $request->ipress ?: null;
        $p_ipress                = is_array($ipress) ? '{' . implode(',', $ipress) . '}' : null;
        $p_anio                  = $request->anio ?: null;
        $p_fechaIni              = $request->fechaIni ?: null;
        $p_fechaFin              = $request->fechaFin ?: null;
        $p_sexo_nacido           = $request->sexoRN ?: null;
        $p_responsable           = $request->responsable ?: null;
        $p_diagnostico           = $request->diagnostico ?: null;
        $p_tipo_beneficiario_id  = $request->tipo_beneficiario ?: null;
        $p_grado                 = $request->grado ?: null;
        $p_paciente              = $request->paciente ?: null;
        $p_complicacion          = $request->complicacion ?: null;
        $p_edad_desde            = $request->edadDesde ?: null;
        $p_edad_hasta            = $request->edadHasta ?: null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_partos(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $p_ipress,
            $p_anio,
            $p_fechaIni,
            $p_fechaFin,
            $p_sexo_nacido,
            $p_responsable,
            $p_diagnostico,
            $p_tipo_beneficiario_id,
            $p_grado,
            $p_paciente,
            $p_complicacion,
            $p_edad_desde,
            $p_edad_hasta,
        ]);
        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_partos_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }
    public function listarReporteVigilancia(Request $request)
    {
        $ipress                  = $request->ipress ?: null;
        $p_ipress                = is_array($ipress) ? '{' . implode(',', $ipress) . '}' : null;
        $p_anio                  = $request->anio ?: null;
        $p_fechaIni              = $request->fechaIni ?: null;
        $p_fechaFin              = $request->fechaFin ?: null;
        $p_nombre_paciente       = $request->nombrePaciente ?: null;
        $p_sexo                  = $request->sexo ?: null;
        $p_codigo_evento = $request->input('codigoEvt')
            ? (int) $request->codigoEvt
            : null;
        $p_tipo_beneficiario_id  = $request->tipo_beneficiario ?: null;
        $p_edad_desde            = $request->edadDesde ?: null;
        $p_edad_hasta            = $request->edadHasta ?: null;
        $p_upss_nombre           = $request->ups ?: null;
        $p_servicio_id           = $request->servicio_id ?: null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_vigilancia_eventos(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $p_ipress,
            $p_anio,
            $p_fechaIni,
            $p_fechaFin,
            $p_nombre_paciente,
            $p_sexo,
            $p_codigo_evento,
            $p_tipo_beneficiario_id,
            $p_edad_desde,
            $p_edad_hasta,
            $p_upss_nombre,
            $p_servicio_id,
        ]);
        return response()->json($resultados);
    }

    public function exportarReporteVigilancia(Request $request)
    {
        $ipress = isset($request->ipress) && is_array($request->ipress)
            ? array_filter($request->ipress, function ($v) {
                return $v !== null && $v !== "";
            })
            : null;
        $ipressPg = (!empty($ipress))
            ? '{' . implode(',', $ipress) . '}'
            : null;
        $p_anio                  = $request->anio ?: null;
        $p_fechaIni              = $request->fechaIni ?: null;
        $p_fechaFin              = $request->fechaFin ?: null;
        $p_nombre_paciente       = $request->nombrePaciente ?: null;
        $p_sexo                  = $request->sexo ?: null;
        $p_codigo_evento = $request->input('codigoEvt')
            ? (int) $request->codigoEvt
            : null;
        $p_tipo_beneficiario_id  = $request->tipo_beneficiario ?: null;
        $p_edad_desde            = $request->edadDesde ?: null;
        $p_edad_hasta            = $request->edadHasta ?: null;
        $p_upss_nombre           = $request->ups ?: null;
        $p_servicio_id           = $request->servicio_id ?: null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_vigilancia_eventos(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $ipressPg,
            $p_anio,
            $p_fechaIni,
            $p_fechaFin,
            $p_nombre_paciente,
            $p_sexo,
            $p_codigo_evento,
            $p_tipo_beneficiario_id,
            $p_edad_desde,
            $p_edad_hasta,
            $p_upss_nombre,
            $p_servicio_id,
        ]);
        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_vigilancia_evt_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }
    public function listarReporteVigilanciaAlertas(Request $request)
    {
        $ipress                  = $request->ipress ?: null;
        $p_ipress                = is_array($ipress) ? '{' . implode(',', $ipress) . '}' : null;
        $p_anio                  = $request->anio ?: null;
        $p_fechaIni              = $request->fechaIni ?: null;
        $p_fechaFin              = $request->fechaFin ?: null;
        $p_nombre_paciente       = $request->nombrePaciente ?: null;
        $p_sexo                  = $request->sexo ?: null;
        $p_tipo_beneficiario_id  = $request->tipo_beneficiario ?: null;
        $p_edad_desde            = $request->edadDesde ?: null;
        $p_edad_hasta            = $request->edadHasta ?: null;
        $p_upss_nombre           = $request->ups ?: null;
        $p_servicio_id           = $request->servicio_id ?: null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_vigilancia_alertas(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $p_ipress,
            $p_anio,
            $p_fechaIni,
            $p_fechaFin,
            $p_nombre_paciente,
            $p_sexo,
            $p_tipo_beneficiario_id,
            $p_edad_desde,
            $p_edad_hasta,
            $p_upss_nombre,
            $p_servicio_id,
        ]);
        return response()->json($resultados);
    }

    public function exportarReporteVigilanciaAlertas(Request $request)
    {
        $ipress = isset($request->ipress) && is_array($request->ipress)
            ? array_filter($request->ipress, function ($v) {
                return $v !== null && $v !== "";
            })
            : null;
        $ipressPg = (!empty($ipress))
            ? '{' . implode(',', $ipress) . '}'
            : null;
        $p_anio                  = $request->anio ?: null;
        $p_fechaIni              = $request->fechaIni ?: null;
        $p_fechaFin              = $request->fechaFin ?: null;
        $p_nombre_paciente       = $request->nombrePaciente ?: null;
        $p_sexo                  = $request->sexo ?: null;
        $p_tipo_beneficiario_id  = $request->tipo_beneficiario ?: null;
        $p_edad_desde            = $request->edadDesde ?: null;
        $p_edad_hasta            = $request->edadHasta ?: null;
        $p_upss_nombre           = $request->ups ?: null;
        $p_servicio_id           = $request->servicio_id ?: null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_vigilancia_alertas(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $ipressPg,
            $p_anio,
            $p_fechaIni,
            $p_fechaFin,
            $p_nombre_paciente,
            $p_sexo,
            $p_tipo_beneficiario_id,
            $p_edad_desde,
            $p_edad_hasta,
            $p_upss_nombre,
            $p_servicio_id,
        ]);
        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_vigilancia_alerta_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }

    public function listarReporteCamas(Request $request)
    {
        $ipress = isset($request->ipress) && is_array($request->ipress)
            ? array_filter($request->ipress, function ($v) {
                return $v !== null && $v !== "";
            })
            : null;
        $ipressPg = (!empty($ipress))
            ? '{' . implode(',', $ipress) . '}'
            : null;
        $p_fechaIni              = $request->fechaIni ?: null;
        $p_fechaFin              = $request->fechaFin ?: null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_dias_camas(?, ?, ?)
        ", [
            $ipressPg,
            $p_fechaIni,
            $p_fechaFin,
        ]);
        return response()->json($resultados);
    }
    public function exportarReporteCamas(Request $request)
    {
        $ipress = isset($request->ipress) && is_array($request->ipress)
            ? array_filter($request->ipress, function ($v) {
                return $v !== null && $v !== "";
            })
            : null;
        $ipressPg = (!empty($ipress))
            ? '{' . implode(',', $ipress) . '}'
            : null;
        $p_fechaIni              = $request->fechaIni ?: null;
        $p_fechaFin              = $request->fechaFin ?: null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_dias_camas(?, ?, ?)
        ", [
            $ipressPg,
            $p_fechaIni,
            $p_fechaFin,
        ]);
        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_camas_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }
    public function listarReporteRecursos(Request $request)
    {
        $ipress = isset($request->ipress) && is_array($request->ipress)
            ? array_filter($request->ipress, function ($v) {
                return $v !== null && $v !== "";
            })
            : null;
        $ipressPg = (!empty($ipress))
            ? '{' . implode(',', $ipress) . '}'
            : null;
        $p_anio                  = $request->anio ?: null;
        $p_periodo              = $request->periodo ?: null;
        $p_tipoPersona              = $request->tipoPersona ?: null;
        $p_colegio       = $request->colegio ?: null;
        /* $p_grado                  = $request->grado ?: null;
        $p_sexo  = $request->sexo ?: null; */
        $p_situacion         = $request->situacion ?: null;
        $p_profesion            = $request->profesion ?: null;
        $p_especialidad            = $request->especialidad ?: null;
        $p_empleado            = $request->empleado ?: null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_recursos(?, ?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $ipressPg,
            $p_anio,
            $p_periodo,
            $p_tipoPersona,
            $p_colegio,
            $p_situacion,
            $p_profesion,
            $p_especialidad,
            $p_empleado,
        ]);
        return response()->json($resultados);
    }
    public function exportarReporteRecursos(Request $request)
    {
        $ipress = isset($request->ipress) && is_array($request->ipress)
            ? array_filter($request->ipress, function ($v) {
                return $v !== null && $v !== "";
            })
            : null;
        $ipressPg = (!empty($ipress))
            ? '{' . implode(',', $ipress) . '}'
            : null;
        $p_anio                  = $request->anio ?: null;
        $p_periodo              = $request->periodo ?: null;
        $p_tipoPersona              = $request->tipoPersona ?: null;
        $p_colegio       = $request->colegio ?: null;
        /* $p_grado                  = $request->grado ?: null;
        $p_sexo  = $request->sexo ?: null; */
        $p_situacion         = $request->situacion ?: null;
        $p_profesion            = $request->profesion ?: null;
        $p_especialidad            = $request->especialidad ?: null;
        $p_empleado            = $request->empleado ?: null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_recursos(?, ?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $ipressPg,
            $p_anio,
            $p_periodo,
            $p_tipoPersona,
            $p_colegio,
            $p_situacion,
            $p_profesion,
            $p_especialidad,
            $p_empleado,
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_recursos_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }

    public function listarReporteEgresosHospi(Request $request)
    {
        $ipress = isset($request->ipress) && is_array($request->ipress)
            ? array_filter($request->ipress, function ($v) {
                return $v !== null && $v !== "";
            })
            : null;
        $ipressPg = (!empty($ipress))
            ? '{' . implode(',', $ipress) . '}'
            : null;
        $p_anio                  = $request->anio ?: null;
        $p_fechaIni              = $request->fechaIni ?: null;
        $p_fechaFin              = $request->fechaFin ?: null;
        $p_medico_ordena       = $request->medico_ordena ?: null;
        $p_sexo         = $request->sexo ?: null;
        $p_medico_alta            = $request->medico_alta ?: null;
        $p_tipoBeneficiario            = $request->tipoBeneficiario ?: null;
        $p_grado            = $request->grado ?: null;
        $p_paciente            = $request->paciente ?: null;
        $p_situacion            = $request->situacion ?: null;
        $p_areaProc            = $request->areaProc ?: null;
        $p_servProc            = $request->servProc ?: null;
        $p_condicion            = $request->condicion ?: null;
        $p_edadDesde            = $request->edadDesde ?: null;
        $p_edadHasta            = $request->edadHasta ?: null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_egresos_hospitalarios(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $ipressPg,
            $p_anio,
            $p_fechaIni,
            $p_fechaFin,
            $p_sexo,
            $p_medico_ordena,
            $p_medico_alta,
            $p_tipoBeneficiario,
            $p_grado,
            $p_paciente,
            $p_situacion,
            $p_areaProc,
            $p_servProc,
            $p_condicion,
            $p_edadDesde,
            $p_edadHasta,
        ]);
        return response()->json($resultados);
    }

    public function exportarReporteEgresosHospi(Request $request)
    {
        $ipress = isset($request->ipress) && is_array($request->ipress)
            ? array_filter($request->ipress, function ($v) {
                return $v !== null && $v !== "";
            })
            : null;
        $ipressPg = (!empty($ipress))
            ? '{' . implode(',', $ipress) . '}'
            : null;
        $p_anio                  = $request->anio ?: null;
        $p_fechaIni              = $request->fechaIni ?: null;
        $p_fechaFin              = $request->fechaFin ?: null;
        $p_medico_ordena       = $request->medico_ordena ?: null;
        $p_sexo         = $request->sexo ?: null;
        $p_medico_alta            = $request->medico_alta ?: null;
        $p_tipoBeneficiario            = $request->tipoBeneficiario ?: null;
        $p_grado            = $request->grado ?: null;
        $p_paciente            = $request->paciente ?: null;
        $p_situacion            = $request->situacion ?: null;
        $p_areaProc            = $request->areaProc ?: null;
        $p_servProc            = $request->servProc ?: null;
        $p_condicion            = $request->condicion ?: null;
        $p_edadDesde            = $request->edadDesde ?: null;
        $p_edadHasta            = $request->edadHasta ?: null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_egresos_hospitalarios(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $ipressPg,
            $p_anio,
            $p_fechaIni,
            $p_fechaFin,
            $p_medico_ordena,
            $p_sexo,
            $p_medico_alta,
            $p_tipoBeneficiario,
            $p_grado,
            $p_paciente,
            $p_situacion,
            $p_areaProc,
            $p_servProc,
            $p_condicion,
            $p_edadDesde,
            $p_edadHasta,
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_egresos_hospi_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }

    public function listarReporteIntervenciones(Request $request)
    {
        $ipress = isset($request->ipress) && is_array($request->ipress)
            ? array_filter($request->ipress, function ($v) {
                return $v !== null && $v !== "";
            })
            : null;
        $ipressPg = (!empty($ipress))
            ? '{' . implode(',', $ipress) . '}'
            : null;
        $p_anio                  = $request->anio ?: null;
        $p_fechaIni              = $request->fechaIni ?: null;
        $p_fechaFin              = $request->fechaFin ?: null;
        $p_sexo                  = $request->sexo ?: null;
        $p_tipoAnestesia         = $request->tipoAnestesia ?: null;
        $p_tipoCirugia           = $request->tipoCirugia ?: null;
        $p_complicacion          = $request->complicacion ?: null;
        $p_tipoProgramacion      = $request->tipoProgramacion ?: null;
        $p_estadoProgramacion            = $request->estadoProgramacion ?: null;
        $p_responsable            = $request->responsable ?: null;
        $p_medico_ordena            = $request->medico_ordena ?: null;
        $p_diagnostico_nombre            = $request->diagnostico_nombre ?: null;
        $p_tipo_beneficiario            = $request->tipo_beneficiario ?: null;
        $p_grado            = $request->grado ?: null;
        $p_paciente            = $request->paciente ?: null;
        $p_edadDesde            = $request->edadDesde ?: null;
        $p_edadHasta            = $request->edadHasta ?: null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_intervenciones(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $ipressPg,
            $p_anio,
            $p_fechaIni,
            $p_fechaFin,
            $p_sexo,
            $p_tipoAnestesia,
            $p_tipoCirugia,
            $p_complicacion,
            $p_tipoProgramacion,
            $p_estadoProgramacion,
            $p_responsable,
            $p_medico_ordena,
            $p_diagnostico_nombre,
            $p_tipo_beneficiario,
            $p_grado,
            $p_paciente,
            $p_edadDesde,
            $p_edadHasta,
        ]);
        return response()->json($resultados);
    }

    public function exportarReporteIntervenciones(Request $request)
    {
        $ipress = isset($request->ipress) && is_array($request->ipress)
            ? array_filter($request->ipress, function ($v) {
                return $v !== null && $v !== "";
            })
            : null;
        $ipressPg = (!empty($ipress))
            ? '{' . implode(',', $ipress) . '}'
            : null;
        $p_anio                  = $request->anio ?: null;
        $p_fechaIni              = $request->fechaIni ?: null;
        $p_fechaFin              = $request->fechaFin ?: null;
        $p_sexo                  = $request->sexo ?: null;
        $p_tipoAnestesia         = $request->tipoAnestesia ?: null;
        $p_tipoCirugia           = $request->tipoCirugia ?: null;
        $p_complicacion          = $request->complicacion ?: null;
        $p_tipoProgramacion      = $request->tipoProgramacion ?: null;
        $p_estadoProgramacion            = $request->estadoProgramacion ?: null;
        $p_responsable            = $request->responsable ?: null;
        $p_medico_ordena            = $request->medico_ordena ?: null;
        $p_diagnostico_nombre            = $request->diagnostico_nombre ?: null;
        $p_tipo_beneficiario            = $request->tipo_beneficiario ?: null;
        $p_grado            = $request->grado ?: null;
        $p_paciente            = $request->paciente ?: null;
        $p_edadDesde            = $request->edadDesde ?: null;
        $p_edadHasta            = $request->edadHasta ?: null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_intervenciones(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $ipressPg,
            $p_anio,
            $p_fechaIni,
            $p_fechaFin,
            $p_sexo,
            $p_tipoAnestesia,
            $p_tipoCirugia,
            $p_complicacion,
            $p_tipoProgramacion,
            $p_estadoProgramacion,
            $p_responsable,
            $p_medico_ordena,
            $p_diagnostico_nombre,
            $p_tipo_beneficiario,
            $p_grado,
            $p_paciente,
            $p_edadDesde,
            $p_edadHasta,
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_intervenciones_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }

    public function listarReporteMorbilidad(Request $request)
    {
        $ipress                = $request->ipress ?: null;
        $p_ipress              = is_array($ipress) ? "{" . implode(",", $ipress) . "}" : null;
        $p_anio                = $request->anio ?: null;
        $p_fechaIni            = $request->fechaIni ?: null;
        $p_fechaFin            = $request->fechaFin ?: null;
        $p_diagnostico         = $request->diagnostico ?: null;
        $p_tipo_atencion       = $request->tipo_atencion ?: null;
        $p_sexo                = $request->sexo ?: null;
        $p_grupo_edad          = $request->grupo_edad ?: null;
        $p_tipo_beneficiario   = $request->tipo_beneficiario ?: null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_morbilidad(?, ?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $p_ipress,
            $p_anio,
            $p_fechaIni,
            $p_fechaFin,
            $p_diagnostico,
            $p_tipo_atencion,
            $p_sexo,
            $p_grupo_edad,
            $p_tipo_beneficiario,
        ]);
        return response()->json($resultados);
    }

    public function exportarReporteMorbilidad(Request $request)
    {
        $ipress                = $request->ipress ?: null;
        $p_ipress              = is_array($ipress) ? "{" . implode(",", $ipress) . "}" : null;
        $p_anio                = $request->anio ?: null;
        $p_fechaIni            = $request->fechaIni ?: null;
        $p_fechaFin            = $request->fechaFin ?: null;
        $p_diagnostico         = $request->diagnostico ?: null;
        $p_tipo_atencion       = $request->tipo_atencion ?: null;
        $p_sexo                = $request->sexo ?: null;
        $p_grupo_edad          = $request->grupo_edad ?: null;
        $p_tipo_beneficiario   = $request->tipo_beneficiario ?: null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_morbilidad(?, ?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $p_ipress,
            $p_anio,
            $p_fechaIni,
            $p_fechaFin,
            $p_diagnostico,
            $p_tipo_atencion,
            $p_sexo,
            $p_grupo_edad,
            $p_tipo_beneficiario,
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_morbilidad_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }

    public function listarReporteMortalidadDiagnostico(Request $request)
    {
        $ipress                = $request->ipress ?: null;
        $p_ipress              = is_array($ipress) ? "{" . implode(",", $ipress) . "}" : null;
        $p_anio                = $request->anio ?: null;
        $p_fechaIni            = $request->fechaIni ?: null;
        $p_fechaFin            = $request->fechaFin ?: null;
        $p_diagnostico         = $request->diagnostico ?: null;
        $p_hora_fallecimiento  = $request->hora_fallecimiento ?: null;
        $p_sexo                = $request->sexo ?: null;
        $p_upss                = $request->upss ?: null;
        $p_grupo_edad          = $request->grupo_edad ?: null;
        $p_servicio            = $request->servicio ?: null;
        $p_edadDesde           = $request->edadDesde ?: null;
        $p_edadHasta           = $request->edadHasta ?: null;
        $p_tipo_atencion       = $request->tipo_atencion ?: null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_mortalidad_por_diagnosticos(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $p_ipress,
            $p_anio,
            $p_fechaIni,
            $p_fechaFin,
            $p_diagnostico,
            $p_hora_fallecimiento,
            $p_sexo,
            $p_upss,
            $p_grupo_edad,
            $p_servicio,
            $p_edadDesde,
            $p_edadHasta,
            $p_tipo_atencion,
        ]);
        return response()->json($resultados);
    }
    public function listarReporteMortalidadNumerico(Request $request)
    {
        $ipress                = $request->ipress ?: null;
        $p_ipress              = is_array($ipress) ? "{" . implode(",", $ipress) . "}" : null;
        $p_anio                = $request->anio ?: null;
        $p_fechaIni            = $request->fechaIni ?: null;
        $p_fechaFin            = $request->fechaFin ?: null;
        $p_diagnostico         = $request->diagnostico ?: null;
        $p_hora_fallecimiento  = $request->hora_fallecimiento ?: null;
        $p_sexo                = $request->sexo ?: null;
        $p_upss                = $request->upss ?: null;
        $p_grupo_edad          = $request->grupo_edad ?: null;
        $p_servicio            = $request->servicio ?: null;
        $p_edadDesde           = $request->edadDesde ?: null;
        $p_edadHasta           = $request->edadHasta ?: null;
        $p_tipo_atencion       = $request->tipo_atencion ?: null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_mortalidad_numerico(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $p_ipress,
            $p_anio,
            $p_fechaIni,
            $p_fechaFin,
            $p_diagnostico,
            $p_hora_fallecimiento,
            $p_sexo,
            $p_upss,
            $p_grupo_edad,
            $p_servicio,
            $p_edadDesde,
            $p_edadHasta,
            $p_tipo_atencion,
        ]);
        return response()->json($resultados);
    }
    public function listarReporteMortalidadPaciente(Request $request)
    {
        $ipress                = $request->ipress ?: null;
        $p_ipress              = is_array($ipress) ? "{" . implode(",", $ipress) . "}" : null;
        $p_anio                = $request->anio ?: null;
        $p_fechaIni            = $request->fechaIni ?: null;
        $p_fechaFin            = $request->fechaFin ?: null;
        $p_diagnostico         = $request->diagnostico ?: null;
        $p_hora_fallecimiento  = $request->hora_fallecimiento ?: null;
        $p_sexo                = $request->sexo ?: null;
        $p_upss                = $request->upss ?: null;
        $p_grupo_edad          = $request->grupo_edad ?: null;
        $p_servicio            = $request->servicio ?: null;
        $p_edadDesde           = $request->edadDesde ?: null;
        $p_edadHasta           = $request->edadHasta ?: null;
        $p_tipo_atencion       = $request->tipo_atencion ?: null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_mortalidad_por_paciente(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $p_ipress,
            $p_anio,
            $p_fechaIni,
            $p_fechaFin,
            $p_diagnostico,
            $p_hora_fallecimiento,
            $p_sexo,
            $p_upss,
            $p_grupo_edad,
            $p_servicio,
            $p_edadDesde,
            $p_edadHasta,
            $p_tipo_atencion,
        ]);
        return response()->json($resultados);
    }

    public function exportarReporteMortalidadDiagnostico(Request $request)
    {
        $ipress                = $request->ipress ?: null;
        $p_ipress              = is_array($ipress) ? "{" . implode(",", $ipress) . "}" : null;
        $p_anio                = $request->anio ?: null;
        $p_fechaIni            = $request->fechaIni ?: null;
        $p_fechaFin            = $request->fechaFin ?: null;
        $p_diagnostico         = $request->diagnostico ?: null;
        $p_hora_fallecimiento  = $request->hora_fallecimiento ?: null;
        $p_sexo                = $request->sexo ?: null;
        $p_upss                = $request->upss ?: null;
        $p_grupo_edad          = $request->grupo_edad ?: null;
        $p_servicio            = $request->servicio ?: null;
        $p_edadDesde           = $request->edadDesde ?: null;
        $p_edadHasta           = $request->edadHasta ?: null;
        $p_tipo_atencion       = $request->tipo_atencion ?: null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_mortalidad_por_diagnosticos(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $p_ipress,
            $p_anio,
            $p_fechaIni,
            $p_fechaFin,
            $p_diagnostico,
            $p_hora_fallecimiento,
            $p_sexo,
            $p_upss,
            $p_grupo_edad,
            $p_servicio,
            $p_edadDesde,
            $p_edadHasta,
            $p_tipo_atencion,
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_mortalidad_diagnostico_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }
    public function exportarReporteMortalidadNumerico(Request $request)
    {
        $ipress                = $request->ipress ?: null;
        $p_ipress              = is_array($ipress) ? "{" . implode(",", $ipress) . "}" : null;
        $p_anio                = $request->anio ?: null;
        $p_fechaIni            = $request->fechaIni ?: null;
        $p_fechaFin            = $request->fechaFin ?: null;
        $p_diagnostico         = $request->diagnostico ?: null;
        $p_hora_fallecimiento  = $request->hora_fallecimiento ?: null;
        $p_sexo                = $request->sexo ?: null;
        $p_upss                = $request->upss ?: null;
        $p_grupo_edad          = $request->grupo_edad ?: null;
        $p_servicio            = $request->servicio ?: null;
        $p_edadDesde           = $request->edadDesde ?: null;
        $p_edadHasta           = $request->edadHasta ?: null;
        $p_tipo_atencion       = $request->tipo_atencion ?: null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_mortalidad_numerico(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $p_ipress,
            $p_anio,
            $p_fechaIni,
            $p_fechaFin,
            $p_diagnostico,
            $p_hora_fallecimiento,
            $p_sexo,
            $p_upss,
            $p_grupo_edad,
            $p_servicio,
            $p_edadDesde,
            $p_edadHasta,
            $p_tipo_atencion,
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_mortalidad_numerico_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }
    public function exportarReporteMortalidadPaciente(Request $request)
    {
        $ipress                = $request->ipress ?: null;
        $p_ipress              = is_array($ipress) ? "{" . implode(",", $ipress) . "}" : null;
        $p_anio                = $request->anio ?: null;
        $p_fechaIni            = $request->fechaIni ?: null;
        $p_fechaFin            = $request->fechaFin ?: null;
        $p_diagnostico         = $request->diagnostico ?: null;
        $p_hora_fallecimiento  = $request->hora_fallecimiento ?: null;
        $p_sexo                = $request->sexo ?: null;
        $p_upss                = $request->upss ?: null;
        $p_grupo_edad          = $request->grupo_edad ?: null;
        $p_servicio            = $request->servicio ?: null;
        $p_edadDesde           = $request->edadDesde ?: null;
        $p_edadHasta           = $request->edadHasta ?: null;
        $p_tipo_atencion       = $request->tipo_atencion ?: null;

        $resultados = DB::select("
            SELECT * FROM sp_reporte_seis_mortalidad_por_paciente(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $p_ipress,
            $p_anio,
            $p_fechaIni,
            $p_fechaFin,
            $p_diagnostico,
            $p_hora_fallecimiento,
            $p_sexo,
            $p_upss,
            $p_grupo_edad,
            $p_servicio,
            $p_edadDesde,
            $p_edadHasta,
            $p_tipo_atencion,
        ]);

        $tipoReporte = $request->tipoReporte;

        $data = json_decode(json_encode($resultados), true);

        $filename = 'reporte_mortalidad_paciente_' . date('Ymd_His');

        if ($tipoReporte === 'excel') {

            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('xlsx');
        }
        if ($tipoReporte === 'csv') {
            return Excel::create($filename, function ($excel) use ($data) {
                $excel->sheet('Reporte', function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }

        return response()->json([
            'error' => 'Tipo de reporte no válido',
        ], 400);
    }

    public function reporteEgresosHospitalarios()
    {
        $regiones_ipress = Establecimiento::where('id_estado_reg', 1)
            ->select("red_region")
            ->whereNotNull('red_region')
            ->where('red_region', '!=', '')
            ->groupBy('red_region')
            ->get();
        $tipoBeneficiarios = Tipo_beneficiario::select("descripcion", "id", "codigo")
            ->get();
        $situaciones = Situacion::select("descripcion", "id")
            ->get();
        $grados = Titular_grado::where('estado', 1)
            ->select("descripcion", "id", "descripcion_corta", "codigo")
            ->get();
        $areasP = DB::table('hospitalizaciones as h')
            ->join('upsses as u', 'u.id', '=', 'h.id_upss_origen')
            ->select('h.id_upss_origen', 'u.descripcion_upss')
            ->distinct()
            ->whereNotNull('h.id_upss_origen')
            ->get();
        $serviciosParaUps = Consultorio::where("estado", "=", 4)->get();
        return view("frontend.seis.reporte_egresos_hospi", compact("regiones_ipress", "areasP", "situaciones", "tipoBeneficiarios", "grados", "serviciosParaUps"));
    }

    public function reporteIntervenciones()
    {
        $regiones_ipress = Establecimiento::where('id_estado_reg', 1)
            ->select("red_region")
            ->whereNotNull('red_region')
            ->where('red_region', '!=', '')
            ->groupBy('red_region')
            ->get();
        $tiposAnestesia = CqxTipoAnestesia::where("estado", 1)->select("descripcion", "id")->get();
        $tiposProgramacion = DB::table('cqx_tipo_cirugias')
            ->select('id', 'descripcion')
            ->where('estado', 1)
            ->get();
        $tipoBeneficiarios = Tipo_beneficiario::select("descripcion", "id", "codigo")
            ->get();
        $grados = Titular_grado::where('estado', 1)
            ->select("descripcion", "id", "descripcion_corta", "codigo")
            ->get();
        return view("frontend.seis.reporte_intervenciones", compact("regiones_ipress", "tiposAnestesia", "tiposProgramacion", "tipoBeneficiarios", "grados"));
    }

    public function reporteRecursos()
    {
        $regiones_ipress = Establecimiento::where('id_estado_reg', 1)
            ->select("red_region")
            ->whereNotNull('red_region')
            ->where('red_region', '!=', '')
            ->groupBy('red_region')
            ->get();
        $colegio_profesionales = Colegio_profesionale::where('estado', 1)
            ->select("descripcion", "id", "codigo")
            ->get();
        $situaciones = Situacion::select("descripcion", "id")
            ->get();
        $grados = Titular_grado::where('estado', 1)
            ->select("descripcion", "id", "descripcion_corta", "codigo")
            ->get();
        $profesiones = Profesione::where('estado', 1)
            ->select("nombre", "id")
            ->get();
        $especialidades = Especializacione::where('estado', 1)
            ->select("nombre", "id")
            ->get();
        return view("frontend.seis.reporte_recursos", compact("regiones_ipress", "colegio_profesionales", "situaciones", "grados", "profesiones", "especialidades"));
    }

    public function tabla_a()
    {
        $id_establecimiento = Auth::user()->id_establecimiento;
        $establecimientoActual = Establecimiento::where("id", $id_establecimiento)->first();
        $model_farmacia = new Farmacia();
        $flagestablecimientos = Auth::user()->flagestablecimientos;
        $establecimientos = $model_farmacia->GetAllIpress();
        return view('frontend.seis.tabla_a', compact("establecimientos", "id_establecimiento", "establecimientoActual", "flagestablecimientos"));
    }

    public function getReporte_a(Request $request)
    {
        $periodo = $request->input("periodo");
        $rango = $this->obtenerRangoMes(/* Carbon::now()->year */$request->anio, $periodo);
        $fechaInicio = $rango["inicio"];
        $fechaFin = $rango["fin"];
        $idEstablecimiento = $request->input('id_establecimiento');

        $resultados = DB::select(
            'SELECT * FROM public.sp_reporte_a_recursos(?, ?, ?)',
            [$fechaInicio, $fechaFin, $idEstablecimiento]
        );

        $total = count($resultados);

        return response()->json([
            "draw" => intval($request->draw),
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            "data" => $resultados,
        ]);
    }
    public function tabla_b1()
    {
        $id_establecimiento = Auth::user()->id_establecimiento;
        $establecimientoActual = Establecimiento::where("id", $id_establecimiento)->first();
        $model_farmacia = new Farmacia();
        $flagestablecimientos = Auth::user()->flagestablecimientos;
        $establecimientos = $model_farmacia->GetAllIpress();
        return view('frontend.seis.tabla_b1', compact("establecimientos", "id_establecimiento", "establecimientoActual", "flagestablecimientos"));
    }

    public function getReporte_b1(Request $request)
    {
        $periodo = $request->input("periodo");
        $rango = $this->obtenerRangoMes($request->anio, $periodo);
        $fechaInicio = $rango["inicio"];
        $fechaFin = $rango["fin"];
        $idEstablecimiento = $request->input('id_establecimiento');

        $resultados = DB::select(
            'SELECT * FROM public.sp_reporte_b1_prod_ambulatoria(?, ?, ?)',
            [$fechaInicio, $fechaFin, $idEstablecimiento]
        );

        $total = count($resultados);

        return response()->json([
            "draw" => intval($request->draw),
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            "data" => $resultados,
        ]);
    }
    public function tabla_b2()
    {
        $id_establecimiento = Auth::user()->id_establecimiento;
        $establecimientoActual = Establecimiento::where("id", $id_establecimiento)->first();
        $model_farmacia = new Farmacia();
        $flagestablecimientos = Auth::user()->flagestablecimientos;
        $establecimientos = $model_farmacia->GetAllIpress();
        return view('frontend.seis.tabla_b2', compact("establecimientos", "id_establecimiento", "establecimientoActual", "flagestablecimientos"));
    }

    public function getReporte_b2(Request $request)
    {
        $periodo = $request->input("periodo");
        $rango = $this->obtenerRangoMes($request->anio, $periodo);
        $fechaInicio = $rango["inicio"];
        $fechaFin = $rango["fin"];
        $idEstablecimiento = $request->input('id_establecimiento');

        $resultados = DB::select(
            'SELECT * FROM public.sp_reporte_b2_morbilidad_ambulatoria(?, ?, ?)',
            [$fechaInicio, $fechaFin, $idEstablecimiento]
        );

        $total = count($resultados);

        return response()->json([
            "draw" => intval($request->draw),
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            "data" => $resultados,
        ]);
    }
    public function tabla_c1()
    {
        $id_establecimiento = Auth::user()->id_establecimiento;
        $establecimientoActual = Establecimiento::where("id", $id_establecimiento)->first();
        $model_farmacia = new Farmacia();
        $flagestablecimientos = Auth::user()->flagestablecimientos;
        $establecimientos = $model_farmacia->GetAllIpress();
        return view('frontend.seis.tabla_c1', compact("establecimientos", "id_establecimiento", "establecimientoActual", "flagestablecimientos"));
    }

    public function getReporte_c1(Request $request)
    {
        $periodo = $request->input("periodo");
        $rango = $this->obtenerRangoMes($request->anio, $periodo);
        $fechaInicio = $rango["inicio"];
        $fechaFin = $rango["fin"];
        $idEstablecimiento = $request->input('id_establecimiento');

        $resultados = DB::select(
            'SELECT * FROM public.sp_reporte_c1_prod_emergencia(?, ?, ?)',
            [$fechaInicio, $fechaFin, $idEstablecimiento]
        );

        $total = count($resultados);

        return response()->json([
            "draw" => intval($request->draw),
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            "data" => $resultados,
        ]);
    }
    public function tabla_c2()
    {
        $id_establecimiento = Auth::user()->id_establecimiento;
        $establecimientoActual = Establecimiento::where("id", $id_establecimiento)->first();
        $model_farmacia = new Farmacia();
        $flagestablecimientos = Auth::user()->flagestablecimientos;
        $establecimientos = $model_farmacia->GetAllIpress();
        return view('frontend.seis.tabla_c2', compact("establecimientos", "id_establecimiento", "establecimientoActual", "flagestablecimientos"));
    }

    public function getReporte_c2(Request $request)
    {
        $periodo = $request->input("periodo");
        $rango = $this->obtenerRangoMes($request->anio, $periodo);
        $fechaInicio = $rango["inicio"];
        $fechaFin = $rango["fin"];
        $idEstablecimiento = $request->input('id_establecimiento');

        $resultados = DB::select(
            'SELECT * FROM public.sp_reporte_C2_morbilidad_emergencia(?, ?, ?)',
            [$fechaInicio, $fechaFin, $idEstablecimiento]
        );

        $total = count($resultados);

        return response()->json([
            "draw" => intval($request->draw),
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            "data" => $resultados,
        ]);
    }
    public function tabla_d1()
    {
        $id_establecimiento = Auth::user()->id_establecimiento;
        $establecimientoActual = Establecimiento::where("id", $id_establecimiento)->first();
        $model_farmacia = new Farmacia();
        $flagestablecimientos = Auth::user()->flagestablecimientos;
        $establecimientos = $model_farmacia->GetAllIpress();
        return view('frontend.seis.tabla_d1', compact("establecimientos", "id_establecimiento", "establecimientoActual", "flagestablecimientos"));
    }

    public function getReporte_d1(Request $request)
    {
        $periodo = $request->input("periodo");
        $rango = $this->obtenerRangoMes($request->anio, $periodo);
        $fechaInicio = $rango["inicio"];
        $fechaFin = $rango["fin"];
        $idEstablecimiento = $request->input('id_establecimiento');

        $resultados = DB::select(
            'SELECT * FROM public.sp_reporte_d1_ps_hospitalizacion(?, ?, ?)',
            [$fechaInicio, $fechaFin, $idEstablecimiento]
        );

        $total = count($resultados);

        return response()->json([
            "draw" => intval($request->draw),
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            "data" => $resultados,
        ]);
    }
    public function tabla_d2()
    {
        $id_establecimiento = Auth::user()->id_establecimiento;
        $establecimientoActual = Establecimiento::where("id", $id_establecimiento)->first();
        $model_farmacia = new Farmacia();
        $flagestablecimientos = Auth::user()->flagestablecimientos;
        $establecimientos = $model_farmacia->GetAllIpress();
        return view('frontend.seis.tabla_d2', compact("establecimientos", "id_establecimiento", "establecimientoActual", "flagestablecimientos"));
    }

    public function getReporte_d2(Request $request)
    {
        $periodo = $request->input("periodo");
        $rango = $this->obtenerRangoMes($request->anio, $periodo);
        $fechaInicio = $rango["inicio"];
        $fechaFin = $rango["fin"];
        $idEstablecimiento = $request->input('id_establecimiento');

        $resultados = DB::select(
            'SELECT * FROM public.sp_reporte_d2_morbilidad_hospitalizacion(?, ?, ?)',
            [$fechaInicio, $fechaFin, $idEstablecimiento]
        );

        $total = count($resultados);

        return response()->json([
            "draw" => intval($request->draw),
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            "data" => $resultados,
        ]);
    }
    public function tabla_e()
    {
        $id_establecimiento = Auth::user()->id_establecimiento;
        $establecimientoActual = Establecimiento::where("id", $id_establecimiento)->first();
        $model_farmacia = new Farmacia();
        $flagestablecimientos = Auth::user()->flagestablecimientos;
        $establecimientos = $model_farmacia->GetAllIpress();
        return view('frontend.seis.tabla_e', compact("establecimientos", "id_establecimiento", "establecimientoActual", "flagestablecimientos"));
    }

    public function getReporte_e(Request $request)
    {
        $periodo = $request->input("periodo");
        $rango = $this->obtenerRangoMes($request->anio, $periodo);
        $fechaInicio = $rango["inicio"];
        $fechaFin = $rango["fin"];
        $idEstablecimiento = $request->input('id_establecimiento');

        $resultados = DB::select(
            'SELECT * FROM public.sp_reporte_e_ps_partos(?, ?, ?)',
            [$fechaInicio, $fechaFin, $idEstablecimiento]
        );

        $total = count($resultados);

        return response()->json([
            "draw" => intval($request->draw),
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            "data" => $resultados,
        ]);
    }
    public function tabla_f()
    {
        $id_establecimiento = Auth::user()->id_establecimiento;
        $establecimientoActual = Establecimiento::where("id", $id_establecimiento)->first();
        $model_farmacia = new Farmacia();
        $flagestablecimientos = Auth::user()->flagestablecimientos;
        $establecimientos = $model_farmacia->GetAllIpress();
        return view('frontend.seis.tabla_f', compact("establecimientos", "id_establecimiento", "establecimientoActual", "flagestablecimientos"));
    }

    public function getReporte_f(Request $request)
    {
        $periodo = $request->input("periodo");
        $rango = $this->obtenerRangoMes($request->anio, $periodo);
        $fechaInicio = $rango["inicio"];
        $fechaFin = $rango["fin"];
        $idEstablecimiento = $request->input('id_establecimiento');

        $resultados = DB::select(
            'SELECT * FROM public.sp_reporte_f_eventos_vigilancia(?, ?, ?)',
            [$fechaInicio, $fechaFin, $idEstablecimiento]
        );

        $total = count($resultados);

        return response()->json([
            "draw" => intval($request->draw),
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            "data" => $resultados,
        ]);
    }
    public function tabla_g()
    {
        $id_establecimiento = Auth::user()->id_establecimiento;
        $establecimientoActual = Establecimiento::where("id", $id_establecimiento)->first();
        $model_farmacia = new Farmacia();
        $flagestablecimientos = Auth::user()->flagestablecimientos;
        $establecimientos = $model_farmacia->GetAllIpress();
        return view('frontend.seis.tabla_g', compact("establecimientos", "id_establecimiento", "establecimientoActual", "flagestablecimientos"));
    }

    public function getReporte_g(Request $request)
    {
        $periodo = $request->input("periodo");
        $rango = $this->obtenerRangoMes($request->anio, $periodo);
        $fechaInicio = $rango["inicio"];
        $fechaFin = $rango["fin"];
        $idEstablecimiento = $request->input('id_establecimiento');

        $resultados = DB::select(
            'SELECT * FROM public.sp_reporte_g_procedimientos(?, ?, ?)',
            [$fechaInicio, $fechaFin, $idEstablecimiento]
        );

        $total = count($resultados);

        return response()->json([
            "draw" => intval($request->draw),
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            "data" => $resultados,
        ]);
    }
    public function tabla_h()
    {
        $id_establecimiento = Auth::user()->id_establecimiento;
        $establecimientoActual = Establecimiento::where("id", $id_establecimiento)->first();
        $model_farmacia = new Farmacia();
        $flagestablecimientos = Auth::user()->flagestablecimientos;
        $establecimientos = $model_farmacia->GetAllIpress();
        return view('frontend.seis.tabla_h', compact("establecimientos", "id_establecimiento", "establecimientoActual", "flagestablecimientos"));
    }

    public function getReporte_h(Request $request)
    {
        $periodo = $request->input("periodo");
        $rango = $this->obtenerRangoMes($request->anio, $periodo);
        $fechaInicio = $rango["inicio"];
        $fechaFin = $rango["fin"];
        $idEstablecimiento = $request->input('id_establecimiento');

        $resultados = DB::select(
            'SELECT * FROM public.sp_reporte_h_intervencion_quirurgica(?, ?, ?)',
            [$fechaInicio, $fechaFin, $idEstablecimiento]
        );

        $total = count($resultados);

        return response()->json([
            "draw" => intval($request->draw),
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            "data" => $resultados,
        ]);
    }
    public function tabla_i()
    {
        $id_establecimiento = Auth::user()->id_establecimiento;
        $establecimientoActual = Establecimiento::where("id", $id_establecimiento)->first();
        $model_farmacia = new Farmacia();
        $flagestablecimientos = Auth::user()->flagestablecimientos;
        $establecimientos = $model_farmacia->GetAllIpress();
        return view('frontend.seis.tabla_i', compact("establecimientos", "id_establecimiento", "establecimientoActual", "flagestablecimientos"));
    }

    public function getReporte_i(Request $request)
    {
        $periodo = $request->input("periodo");
        $rango = $this->obtenerRangoMes($request->anio, $periodo);
        $fechaInicio = $rango["inicio"];
        $fechaFin = $rango["fin"];
        $idEstablecimiento = $request->input('id_establecimiento');

        $resultados = DB::select(
            'SELECT * FROM public.sp_reporte_i_referencias(?, ?, ?)',
            [$fechaInicio, $fechaFin, $idEstablecimiento]
        );

        $total = count($resultados);

        return response()->json([
            "draw" => intval($request->draw),
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            "data" => $resultados,
        ]);
    }
    public function tabla_j()
    {
        $id_establecimiento = Auth::user()->id_establecimiento;
        $establecimientoActual = Establecimiento::where("id", $id_establecimiento)->first();
        $model_farmacia = new Farmacia();
        $flagestablecimientos = Auth::user()->flagestablecimientos;
        $establecimientos = $model_farmacia->GetAllIpress();
        return view('frontend.seis.tabla_j', compact("establecimientos", "id_establecimiento", "establecimientoActual", "flagestablecimientos"));
    }

    public function getReporte_j(Request $request)
    {
        $periodo = $request->input("periodo");
        $rango = $this->obtenerRangoMes($request->anio, $periodo);
        $fechaInicio = $rango["inicio"];
        $fechaFin = $rango["fin"];
        $idEstablecimiento = $request->input('id_establecimiento');

        $resultados = DB::select(
            'SELECT * FROM public.sp_reporte_j_programacion_asistencial(?, ?, ?)',
            [$fechaInicio, $fechaFin, $idEstablecimiento]
        );

        $total = count($resultados);

        return response()->json([
            "draw" => intval($request->draw),
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            "data" => $resultados,
        ]);
    }

    public function generarTxt($letra_tabla, $periodo, $id_establecimiento, $anio)
    {
        $rango = $this->obtenerRangoMes($anio, $periodo);
        $fechaInicio = $rango["inicio"];
        $fechaFin = $rango["fin"];

        $lineas = [];
        switch (strtoupper($letra_tabla)) {
            case "A":
                $resultados = DB::select(
                    'SELECT * FROM public.sp_reporte_a_recursos(?, ?, ?)',
                    [$fechaInicio, $fechaFin, $id_establecimiento]
                );
                /* foreach ($resultados as $fila) {
                    $filaArr = (array) $fila;
                    $numericos = array_filter($filaArr, function ($v) {
                        return is_numeric($v);
                    });
                    $lineas[] = implode('|', $numericos);
                } */
                $keysExcluidas = ['nombre_ugipress', 'nombre_ipress', 'descripcion_sexo', 'desc_grupo_edad'];
                foreach ($resultados as $fila) {
                    $filaArr = get_object_vars($fila);
                    foreach ($keysExcluidas as $key) {
                        unset($filaArr[$key]);
                    }
                    $lineas[] = implode('|', $filaArr);
                }
                break;

            case "B1":
                $resultados = DB::select(
                    'SELECT * FROM public.sp_reporte_b1_prod_ambulatoria(?, ?, ?)',
                    [$fechaInicio, $fechaFin, $id_establecimiento]
                );
                /* foreach ($resultados as $fila) {
                    $filaArr = (array) $fila;
                    $numericos = array_filter($filaArr, function ($v) {
                        return is_numeric($v);
                    });
                    $lineas[] = implode('|', $numericos);
                } */
                $keysExcluidas = ['nombre_ugipress', 'nombre_ipress', 'descripcion_sexo', 'descripcion_grupo_edad'];
                foreach ($resultados as $fila) {
                    $filaArr = get_object_vars($fila);
                    foreach ($keysExcluidas as $key) {
                        unset($filaArr[$key]);
                    }
                    $lineas[] = implode('|', $filaArr);
                }
                break;
            case "B2":
                $resultados = DB::select(
                    'SELECT * FROM public.sp_reporte_b2_morbilidad_ambulatoria(?, ?, ?)',
                    [$fechaInicio, $fechaFin, $id_establecimiento]
                );
                /* foreach ($resultados as $fila) {
                    $filaArr = (array) $fila;
                    $linea = [];
                    foreach ($filaArr as $campo => $valor) {
                        if (is_numeric($valor) || $campo === 'diagnostico_definitivo') {
                            $linea[] = $valor;
                        }
                    }
                    $lineas[] = implode('|', $linea);
                } */
                $keysExcluidas = ['nombre_ugipress', 'nombre_ipress', 'descripcion_sexo_paciente', 'descripcion_grupo_edad'];
                foreach ($resultados as $fila) {
                    $filaArr = get_object_vars($fila);
                    foreach ($keysExcluidas as $key) {
                        unset($filaArr[$key]);
                    }
                    $lineas[] = implode('|', $filaArr);
                }
                break;
            case "C1":
                $resultados = DB::select(
                    'SELECT * FROM public.sp_reporte_c1_prod_emergencia(?, ?, ?)',
                    [$fechaInicio, $fechaFin, $id_establecimiento]
                );
                /* $numericos = array_filter($filaArr, function ($v) {
                    return is_numeric($v);
                    }); */
                /* $filaArr = array_map(function ($v) {
                        return $v === null ? '' : $v;
                        }, $filaArr); */
                $keysExcluidas = ['nombre_ugipress', 'nombre_ipress', 'descripcion_sexo', 'desc_grupo_edad'];
                foreach ($resultados as $fila) {
                    $filaArr = get_object_vars($fila);
                    foreach ($keysExcluidas as $key) {
                        unset($filaArr[$key]);
                    }
                    $lineas[] = implode('|', $filaArr);
                }
                break;
            case "C2":
                $resultados = DB::select(
                    'SELECT * FROM public.sp_reporte_c2_morbilidad_emergencia(?, ?, ?)',
                    [$fechaInicio, $fechaFin, $id_establecimiento]
                );
                /* foreach ($resultados as $fila) {
                    $filaArr = (array) $fila;
                    $linea = [];
                    foreach ($filaArr as $campo => $valor) {
                        if (is_numeric($valor) || $campo === 'diagnostico_definitivo') {
                            $linea[] = $valor;
                        }
                    }
                    $lineas[] = implode('|', $linea);
                } */
                $keysExcluidas = ['nombre_ugipress', 'nombre_ipress', 'descripcion_sexo_paciente', 'descripcion_grupo_edad'];
                foreach ($resultados as $fila) {
                    $filaArr = get_object_vars($fila);
                    foreach ($keysExcluidas as $key) {
                        unset($filaArr[$key]);
                    }
                    $lineas[] = implode('|', $filaArr);
                }
                break;
            case "D1":
                $resultados = DB::select(
                    'SELECT * FROM public.sp_reporte_d1_ps_hospitalizacion(?, ?, ?)',
                    [$fechaInicio, $fechaFin, $id_establecimiento]
                );
                foreach ($resultados as $fila) {
                    $keysExcluidas = ['nombre_ugipress', 'nombre_ipress'];
                    foreach ($resultados as $fila) {
                        $filaArr = get_object_vars($fila);
                        foreach ($keysExcluidas as $key) {
                            unset($filaArr[$key]);
                        }
                        $lineas[] = implode('|', $filaArr);
                    }
                }
                break;
            case "D2":
                $resultados = DB::select(
                    'SELECT * FROM public.sp_reporte_d2_morbilidad_hospitalizacion(?, ?, ?)',
                    [$fechaInicio, $fechaFin, $id_establecimiento]
                );
                foreach ($resultados as $fila) {
                    $keysExcluidas = ['nombre_ugipress', 'nombre_ipress', 'descripcion_sexo_paciente', 'descripcion_grupo_edad'];
                    $filaArr = (array) $fila;
                    $filaArr = get_object_vars($fila);
                    foreach ($keysExcluidas as $key) {
                        unset($filaArr[$key]);
                    }
                    $lineas[] = implode('|', $filaArr);
                }
                break;
            case "E":
                $resultados = DB::select(
                    'SELECT * FROM public.sp_reporte_e_ps_partos(?, ?, ?)',
                    [$fechaInicio, $fechaFin, $id_establecimiento]
                );
                /* foreach ($resultados as $fila) {
                    $filaArr = (array) $fila;
                    $lineas[] = implode('|', $filaArr);
                } */
                foreach ($resultados as $fila) {
                    $keysExcluidas = ['nombre_ugipress', 'nombre_ipress', 'descripcion_sexo_paciente', 'descripcion_grupo_edad', 'parto_desc', 'desc_complicaciones'];
                    $filaArr = (array) $fila;
                    $filaArr = get_object_vars($fila);
                    foreach ($keysExcluidas as $key) {
                        unset($filaArr[$key]);
                    }
                    $lineas[] = implode('|', $filaArr);
                }
                break;
            case "F":
                $resultados = DB::select(
                    'SELECT * FROM public.sp_reporte_f_eventos_vigilancia(?, ?, ?)',
                    [$fechaInicio, $fechaFin, $id_establecimiento]
                );
                /* foreach ($resultados as $fila) {
                    $filaArr = (array) $fila;
                    $numericos = array_filter($filaArr, function ($v) {
                        return is_numeric($v);
                    });
                    $lineas[] = implode('|', $numericos);
                } */
                foreach ($resultados as $fila) {
                    $keysExcluidas = ['nombre_ugipress', 'nombre_ipress', 'descripcion_sexo', 'descripcion_grupo_edad', 'descripcion_evento'];
                    $filaArr = (array) $fila;
                    $filaArr = get_object_vars($fila);
                    foreach ($keysExcluidas as $key) {
                        unset($filaArr[$key]);
                    }
                    $lineas[] = implode('|', $filaArr);
                }
                break;
            case "G":
                $resultados = DB::select(
                    'SELECT * FROM public.sp_reporte_g_procedimientos(?, ?, ?)',
                    [$fechaInicio, $fechaFin, $id_establecimiento]
                );
                /* foreach ($resultados as $fila) {
                    $filaArr = (array) $fila;
                    $numericos = array_filter($filaArr, function ($v) {
                        return is_numeric($v);
                    });
                    $lineas[] = implode('|', $numericos);
                } */
                foreach ($resultados as $fila) {
                    $keysExcluidas = ['nombre_ugipress', 'nombre_ipress', 'descripcion_sexo', 'descripcion_grupo_edad', 'nombre_procedimiento'];
                    $filaArr = (array) $fila;
                    $filaArr = get_object_vars($fila);
                    foreach ($keysExcluidas as $key) {
                        unset($filaArr[$key]);
                    }
                    $lineas[] = implode('|', $filaArr);
                }
                break;
            case "H":
                $resultados = DB::select(
                    'SELECT * FROM public.sp_reporte_h_intervencion_quirurgica(?, ?, ?)',
                    [$fechaInicio, $fechaFin, $id_establecimiento]
                );
                foreach ($resultados as $fila) {
                    $keysExcluidas = ['nombre_ugipress', 'nombre_ipress','error_horas_efectivas','error_horas_programadas','error_horas_acto_qx','error_cirugias_suspendidas'];
                    $filaArr = (array) $fila;
                    $filaArr = array_map(function ($v) {
                        return $v === null ? '' : $v;
                    }, $filaArr);
                    foreach ($keysExcluidas as $key) {
                        unset($filaArr[$key]);
                    }
                    $lineas[] = implode('|', $filaArr);
                }
                break;
            case "I":
                $resultados = DB::select(
                    'SELECT * FROM public.sp_reporte_i_referencias(?, ?, ?)',
                    [$fechaInicio, $fechaFin, $id_establecimiento]
                );
                foreach ($resultados as $fila) {
                    $keysExcluidas = ['nombre_ipress', 'nombre_ugipress', 'nombre_centro_asistencial_origen', 'nombre_tipo_documento', 'descripcion_sexo', 'descripcion_centro_asistencial_destino'];
                    $filaArr = (array) $fila;
                    $filaArr = array_map(function ($v) {
                        return $v === null ? '' : $v;
                    }, $filaArr);
                    foreach ($keysExcluidas as $key) {
                        unset($filaArr[$key]);
                    }
                    Log::info($filaArr);
                    $lineas[] = implode('|', $filaArr);
                }
                break;
            case "J":
                $resultados = DB::select(
                    'SELECT * FROM public.sp_reporte_j_programacion_asistencial(?, ?, ?)',
                    [$fechaInicio, $fechaFin, $id_establecimiento]
                );
                foreach ($resultados as $fila) {
                    $keysExcluidas = ['nombre_ipress', 'nombre_ugipress', 'descripcion_colegio_profesional'];
                    $filaArr = (array) $fila;
                    $filaArr = array_map(function ($v) {
                        return $v === null ? '' : $v;
                    }, $filaArr);
                    foreach ($keysExcluidas as $key) {
                        unset($filaArr[$key]);
                    }
                    Log::info($filaArr);
                    $lineas[] = implode('|', $filaArr);
                }
                break;

            default:
                return response("Letra inválida", 400);
        }

        $contenido = implode("\n", $lineas);

        $ipress = Establecimiento::where("id", $id_establecimiento)->first();
        $nombreArchivo = $ipress->codigo . "_" . $anio . "_" . ($periodo > 9 ? $periodo : "0" . $periodo) . "_TA" . strtoupper((strlen($letra_tabla) > 1 ? $letra_tabla : $letra_tabla . "0")) . ".txt";

        return Response::make($contenido, 200, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="' . $nombreArchivo . '"'
        ]);
    }

    public function headings($data)
    {
        if (count($data) > 0) {
            return array_keys((array)$data[0]);
        }

        return [];
    }

    public function generarExcel($letra_tabla, $periodo, $id_establecimiento, $anio)
    {
        $rango = $this->obtenerRangoMes($anio, $periodo);
        $fechaInicio = $rango["inicio"];
        $fechaFin = $rango["fin"];

        $lineas = [];
        switch (strtoupper($letra_tabla)) {
            case "A":
                $resultados = DB::select(
                    'SELECT * FROM public.sp_reporte_a_recursos(?, ?, ?)',
                    [$fechaInicio, $fechaFin, $id_establecimiento]
                );
                foreach ($resultados as $fila) {
                    $filaArr = (array) $fila;
                    $numericos = array_filter($filaArr, function ($v) {
                        return is_numeric($v);
                    });
                    $lineas[] = [
                        "Periodo de Reporte" => $numericos["periodo"],
                        "Codigo de la IPRESS" => $numericos["codigo"],
                        "Codigo de la UGIPRESS" => $numericos["ugipress"],
                        "Consultorios Fisicos" => $numericos["consultorios_fisicos"],
                        "Consultorios Funcionales" => $numericos["consultorios_funcionales"],
                        "Camas Hospitalarias" => $numericos["camas_hospitalarias"],
                        "Total de Medicos" => $numericos["total_medicos"],
                        "Medicos Serums" => $numericos["total_medicos_serums"],
                        "Medicos Residentes" => $numericos["total_medicos_residentes"],
                        "Enfermeras" => $numericos["total_enfermeros"],
                        "Odontologos" => $numericos["total_odontologos"],
                        "Psicologos" => $numericos["total_psicologos"],
                        "Nutricionistas" => $numericos["total_nutricionistas"],
                        "Tecnologos Medicos" => $numericos["total_tecnologos_medicos"],
                        "Obstetrices" => $numericos["total_obstetrices"],
                        "Farmaceuticos" => $numericos["total_farmaceuticos"],
                        "Auxiliares/Tecnicos Asistenciales" => $numericos["total_auxiliares"],
                        "Otros Profesionales" => $numericos["total_otros_profesionales"],
                        "Ambulancias" => $numericos["ambulancias"],
                    ];
                }
                break;
            case "B1":
                $resultados = DB::select(
                    'SELECT * FROM public.sp_reporte_b1_prod_ambulatoria(?, ?, ?)',
                    [$fechaInicio, $fechaFin, $id_establecimiento]
                );
                $keysExcluidas = ['nombre_ugipress', 'nombre_ipress', 'descripcion_sexo_paciente', 'descripcion_grupo_edades'];
                foreach ($resultados as $fila) {
                    $filaArr = get_object_vars($fila);
                    foreach ($keysExcluidas as $key) {
                        unset($filaArr[$key]);
                    }
                    $lineas[] = [
                        "Periodo de Reporte" => $filaArr["periodo"],
                        "Codigo de la IPRESS" => $filaArr["codigo_ipress"],
                        "Codigo de la UGIPRESS" => $filaArr["codigo_ugipress"],
                        "Sexo del paciente" => $filaArr["descripcion_sexo"],
                        "Grupo de Edad en años" => $filaArr["descripcion_grupo_edad"],
                        "Atenciones Medicas" => $filaArr["atenciones_medicas"],
                        "Atenciones No Medicas" => $filaArr["atenciones_no_medicas"],
                        "Atendidos/Mes" => $filaArr["atendidos_mes"],
                    ];
                }
                /* foreach ($resultados as $fila) {
                    $filaArr = (array) $fila;
                    $numericos = array_filter($filaArr, function ($v) {
                        return is_numeric($v);
                    });

                    $lineas[] = [
                        "Periodo de Reporte" => $numericos["periodo"],
                        "Codigo de la IPRESS" => $numericos["codigo_ipress"],
                        "Codigo de la UGIPRESS" => $numericos["codigo_ugipress"],
                        "Sexo del paciente" => $numericos["sexo"],
                        "Grupo de Edad en años" => $numericos["id_grupo_edad"],
                        "Atenciones Medicas" => $numericos["atenciones_medicas"],
                        "Atenciones No Medicas" => $numericos["atenciones_no_medicas"],
                        "Atendidos/Mes" => $numericos["atendidos_mes"],
                    ];
                } */
                break;
            case "B2":
                $resultados = DB::select(
                    'SELECT * FROM public.sp_reporte_b2_morbilidad_ambulatoria(?, ?, ?)',
                    [$fechaInicio, $fechaFin, $id_establecimiento]
                );
                foreach ($resultados as $fila) {
                    $filaArr = (array) $fila;
                    $lineas[] = [
                        "Periodo de Reporte" => $filaArr["periodo_reporte"],
                        "Codigo de la IPRESS" => $filaArr["codigo_ipress"],
                        "Codigo de la UGIPRESS" => $filaArr["codigo_ugipress"],
                        "Sexo del paciente" => $filaArr["descripcion_sexo_paciente"],
                        "Grupo de Edad en años" => $filaArr["descripcion_grupo_edad"],
                        "Diagnostico Definitivo" => $filaArr["diagnostico_definitivo"],
                        "Total de Atendidos" => $filaArr["total_atendidos"],
                    ];
                }
                break;
            case "C1":
                $resultados = DB::select(
                    'SELECT * FROM public.sp_reporte_c1_prod_emergencia(?, ?, ?)',
                    [$fechaInicio, $fechaFin, $id_establecimiento]
                );
                foreach ($resultados as $fila) {
                    $filaArr = (array) $fila;
                    /* $numericos = array_filter($filaArr, function ($v) {
                        return is_numeric($v);
                    }); */
                    $lineas[] = [
                        "Periodo de Reporte" => $filaArr["periodo"],
                        "Codigo de la IPRESS" => $filaArr["codigo_ipress"],
                        "Codigo de la UGIPRESS" => $filaArr["codigo_ugipress"],
                        "Sexo del paciente" => $filaArr["descripcion_sexo"],
                        "Grupo de Edad en años" => $filaArr["desc_grupo_edad"],
                        "Numero Total de Atenciones(Medicas y no Medicas)" => $filaArr["atenciones"],
                        "Numero Total de Atendidos" => $filaArr["atendidos_mes"],
                    ];
                }
                break;
            case "C2":
                $resultados = DB::select(
                    'SELECT * FROM public.sp_reporte_c2_morbilidad_emergencia(?, ?, ?)',
                    [$fechaInicio, $fechaFin, $id_establecimiento]
                );
                foreach ($resultados as $fila) {
                    $filaArr = (array) $fila;
                    $numericos = array_filter($filaArr, function ($v) {
                        return !($v === null || $v === '');;
                    });
                    $lineas[] = [
                        "Periodo de Reporte" => $numericos["periodo_reporte"],
                        "Codigo de la IPRESS" => $numericos["codigo_ipress"],
                        "Codigo de la UGIPRESS" => $numericos["codigo_ugipress"],
                        "Sexo del paciente" => $numericos["descripcion_sexo_paciente"],
                        "Grupo de Edad en años" => $numericos["descripcion_grupo_edad"],
                        "Diagnostico de Emergencia" => $numericos["diagnostico_definitivo"],
                        "Numero Total de Atendidos" => $numericos["total_atendidos"],
                    ];
                }
                break;
            case "D1":
                $resultados = DB::select(
                    'SELECT * FROM public.sp_reporte_d1_ps_hospitalizacion(?, ?, ?)',
                    [$fechaInicio, $fechaFin, $id_establecimiento]
                );
                foreach ($resultados as $fila) {
                    $filaArr = (array) $fila;
                    /* $numericos = array_filter($filaArr, function ($v) {
                        return is_numeric($v);
                    }); */
                    $lineas[] = [
                        "Periodo de Reporte" => $filaArr["periodo_reporte"],
                        "Codigo de la IPRESS" => $filaArr["codigo_ipress"],
                        "Codigo de la UGIPRESS" => $filaArr["codigo_ugipress"],
                        "Servicio/Especialidad Catalogo UPSS" => $filaArr["codigo_upss"],
                        "Ingresos" => $filaArr["ingresos"],
                        "Egresos" => $filaArr["egresos"],
                        "Estancias" => $filaArr["estancias"],
                        "Pacientes Dias" => $filaArr["pacientes_dias"],
                        "Camas" => $filaArr["camas_disponibles"],
                        "Dias Cama Disponible" => $filaArr["dias_cama_disponible"],
                        "Fallecidos" => $filaArr["defunciones"],
                    ];
                }
                break;
            case "D2":
                $resultados = DB::select(
                    'SELECT * FROM public.sp_reporte_d2_morbilidad_hospitalizacion(?, ?, ?)',
                    [$fechaInicio, $fechaFin, $id_establecimiento]
                );
                foreach ($resultados as $fila) {
                    $filaArr = (array) $fila;
                    /* $numericos = array_filter($filaArr, function ($v) {
                        return !($v === null || $v === '');;
                    }); */
                    $lineas[] = [
                        "Periodo de Reporte" => $filaArr["periodo_reporte"],
                        "Codigo de la IPRESS" => $filaArr["codigo_ipress"],
                        "Codigo de la UGIPRESS" => $filaArr["codigo_ugipress"],
                        "Sexo del Paciente" => $filaArr["descripcion_sexo_paciente"],
                        "Grupo de Edad en años" => $filaArr["descripcion_grupo_edad"],
                        "Diagnostico de Egreso" => $filaArr["diagnostico_definitivo"],
                        "Total Atendidos" => $filaArr["total_atendidos"],
                    ];
                }
                break;
            case "E":
                $resultados = DB::select(
                    'SELECT * FROM public.sp_reporte_e_ps_partos(?, ?, ?)',
                    [$fechaInicio, $fechaFin, $id_establecimiento]
                );
                foreach ($resultados as $fila) {
                    $filaArr = (array) $fila;
                    $numericos = array_filter($filaArr, function ($v) {
                        return !($v === null || $v === '');
                    });
                    $lineas[] = [
                        "Periodo de Reporte" => $numericos["periodo_reporte"],
                        "Codigo de la IPRESS" => $numericos["codigo_ipress"],
                        "Codigo de la UGIPRESS" => $numericos["codigo_ugipress"],
                        "Tipo de Parto" => $numericos["tipo_parto"],
                        "Complicacion del Parto" => $numericos["complicaciones"],
                        "Partos" => $numericos["partos"],
                        "Total de Nacimientos" => $numericos["total_nacidos"],
                        "Nacimientos Vivos" => $numericos["nacidos_vivos"],
                        "Nacimientos Muertos" => $numericos["nacidos_muertos"],
                    ];
                }
                break;
            case "F":
                $resultados = DB::select(
                    'SELECT * FROM public.sp_reporte_f_eventos_vigilancia(?, ?, ?)',
                    [$fechaInicio, $fechaFin, $id_establecimiento]
                );
                foreach ($resultados as $fila) {
                    $filaArr = (array) $fila;
                    $numericos = array_filter($filaArr, function ($v) {
                        return !($v === null || $v === '');;
                    });
                    $lineas[] = [
                        "Periodo de Reporte" => $numericos["periodo"],
                        "Codigo de la IPRESS" => $numericos["codigo_ipress"],
                        "Codigo de la UGIPRESS" => $numericos["codigo_ugipress"],
                        "Sexo del Paciente" => $numericos["descripcion_sexo"],
                        "Grupo de Edad en años" => $numericos["descripcion_grupo_edad"],
                        "Eventos" => $numericos["evento"],
                        "Total" => $numericos["total"],
                    ];
                }
                break;
            case "G":
                $resultados = DB::select(
                    'SELECT * FROM public.sp_reporte_g_procedimientos(?, ?, ?)',
                    [$fechaInicio, $fechaFin, $id_establecimiento]
                );
                foreach ($resultados as $fila) {
                    $filaArr = (array) $fila;
                    $numericos = array_filter($filaArr, function ($v) {
                        return !($v === null || $v === '');;
                    });
                    $lineas[] = [
                        "Periodo de Reporte" => $numericos["periodo"],
                        "Codigo de la IPRESS" => $numericos["codigo_ipress"],
                        "Codigo de la UGIPRESS" => $numericos["codigo_ugipress"],
                        "Codigo del Procedimiento" => $numericos["cod_procedimiento"],
                        "Total Procedimientos" => $numericos["total_procedimiento"],
                        "Servicio/Especialidad Catalogo UPS" => $numericos["codigo_upss"],
                    ];
                }
                break;
            case "G1":
                $resultados = DB::select(
                    'SELECT * FROM public.sp_reporte_g_procedimientos(?, ?, ?)',
                    [$fechaInicio, $fechaFin, $id_establecimiento]
                );
                $establecimiento = Establecimiento::where('id', '=', intval($id_establecimiento))->first();
                
                foreach ($resultados as $fila) {
                    $filaArr = (array) $fila;
                    $numericos = array_filter($filaArr, function ($v) {
                        return !($v === null || $v === '');;
                    });
                    $upss = Upss::where('codigo', '=', $numericos["codigo_upss"])->first();
                    $lineas[] = [
                        "Periodo de Reporte" => $numericos["periodo"],
                        "Codigo de la IPRESS" => $numericos["codigo_ipress"],
                        "Nombre de la IPRESS" => $establecimiento->nombre,
                        "Codigo de la UGIPRESS" => $numericos["codigo_ugipress"],
                        "Nombre de la UGIPRESS" => $establecimiento->nombre,
                        "Codigo del Procedimiento" => $numericos["cod_procedimiento"],
                        "Nombre del Procedimiento" => $numericos["nombre_procedimiento"],
                        "Total Procedimientos" => $numericos["total_procedimiento"],
                        "UPS" => $numericos["codigo_upss"],
                        "Descripcion UPS" => $upss->descripcion_upss,
                    ];
                }
                break;
            case "H":
                $resultados = DB::select(
                    'SELECT * FROM public.sp_reporte_h_intervencion_quirurgica(?, ?, ?)',
                    [$fechaInicio, $fechaFin, $id_establecimiento]
                );
                foreach ($resultados as $fila) {
                    $filaArr = (array) $fila;
                    $numericos = array_filter($filaArr, function ($v) {
                        return !($v === null || $v === '');;
                    });
                    $lineas[] = [
                        "Periodo de Reporte" => $numericos["periodo"],
                        "Codigo de la IPRESS" => $numericos["codigo_ipress"],
                        "Codigo de la UGIPRESS" => $numericos["codigo_ugipress"],
                        "Programacion" => $numericos["programacion"],
                        "Cirugias Mayores" => $numericos["cirugias_mayores"],
                        "Cirugias Menores" => $numericos["cirugias_menores"],
                        "Horas Programadas" => $numericos["horas_programadas"],
                        "Horas Efectivas" => $numericos["horas_efectivas"],
                        "Horas Acto Quirurgico" => $numericos["horas_acto_quirurgico"],
                        "Cirugias Suspendidas" => $numericos["cirugias_suspendidas"],
                    ];
                }
                break;
            case "I":
                $resultados = DB::select(
                    'SELECT * FROM public.sp_reporte_i_referencias(?, ?, ?)',
                    [$fechaInicio, $fechaFin, $id_establecimiento]
                );
                foreach ($resultados as $fila) {
                    $filaArr = (array) $fila;
                    /* $numericos = array_filter($filaArr, function ($v) {
                        return $v;
                    }); */
                    $lineas[] = [
                        "Periodo de Reporte" => $filaArr["periodo"],
                        "Codigo de la IPRESS" => $filaArr["codigo_ipress"],
                        "Codigo de la UGIPRESS" => $filaArr["codigo_ugipress"],
                        "Centro Asistencial Origen" => $filaArr["codigo_centro_asistencial_origen"],
                        "Correlativo otorgado por la IPRESS o UGIPRESS" => $filaArr["correlativo"],
                        "Numero de Historia Clinica" => $filaArr["numero_historia_clinica"],
                        "Tipo documento de identidad del paciente" => $filaArr["tipo_documento_identidad"],
                        "Numero de documento de identidad del paciente" => $filaArr["numero_documento_identidad"],
                        "Sexo del Paciente" => $filaArr["sexo"],
                        "Edad del Paciente" => $filaArr["edad_formato"],
                        "Servicio Asistencial Origen Catalogo UPS" => $filaArr["servicio_origen"],
                        "Centro Asistencial Destino" => $filaArr["codigo_centro_asistencial_destino"],
                        "Servicio Asistencial Destino Catalogo UPS" => $filaArr["servicio_destino"],
                        "Diagnostico Principal Motivo de la Referencia" => $filaArr["diagnostico_principal"],
                        "Tipo de Diagnostico Principal Motivo de la Referencia" => $filaArr["tipo_diagnostico_principal"],
                        "Diagnostico Secundario Motivo de la Referencia" => $filaArr["diagnostico_secundario"],
                        "Tipo de Diagnostico Secundario Motivo de la Referencia" => $filaArr["tipo_diagnostico_secundario"],
                        "Fecha de Extension de la Referencia" => $filaArr["fecha_generacion"],
                        "Fecha de Tramite o Solicitud de la Referencia" => $filaArr["fecha_tramite"],
                    ];
                }
                break;
            case "J":
                $resultados = DB::select(
                    'SELECT * FROM public.sp_reporte_j_programacion_asistencial(?, ?, ?)',
                    [$fechaInicio, $fechaFin, $id_establecimiento]
                );
                foreach ($resultados as $fila) {
                    $filaArr = (array) $fila;
                    $numericos = array_filter($filaArr, function ($v) {
                        /* return $v; */
                        return $v !== null;
                    });
                    $lineas[] = [
                        "Periodo de Reporte" => $numericos["periodo_reporte"],
                        "Codigo de la IPRESS" => $numericos["codigo_ipress"],
                        "Codigo de la UGIPRESS" => $numericos["codigo_ugipress"],
                        "Codigo de Colegio Profesional del Personal de Salud" => $numericos["colegio_profesional"],
                        "Total de Profesionales" => $numericos["total_profesionales"],
                        "Horas Mensuales Programadas en Atencion Ambulatoria" => $numericos["horas_programadas_ambulatorio"],
                        "Horas Mensuales Programadas en Emergencia" => $numericos["horas_programadas_emergencia"],
                        "Horas Mensuales Programadas en Hospitalizacion" => $numericos["horas_programadas_hospitalizacion"],
                        "Horas Mensuales Administrativas Programadas" => $numericos["horas_programadas_administrativo"],
                        "Horas Mensuales Programadas en Capacitacion" => $numericos["horas_programadas_capacitacion"],
                        "Horas Mensuales Programadas en Centro Quirurgico" => $numericos["horas_programadas_centro_quirurgico"],
                        "Horas Mensuales Programadas en Centro Obstetricio" => $numericos["horas_programadas_centro_obtetricos"],
                        "Horas Mensuales Programadas para Procedimientos" => $numericos["horas_programadas_procedimientos"],
                        "Horas Mensuales Programadas Otras Actividades" => $numericos["horas_programadas_otras_actividades"],
                    ];
                }
                break;
            default:
                return response("Letra inválida", 400);
        }

        if (empty($lineas)) {
            return response("No hay datos para exportar", 404);
        }

        $ipress = Establecimiento::where("id", $id_establecimiento)->first();
        /* return Excel::create(
            $ipress->codigo . "_" . $anio . "_" . ($periodo > 9 ? $periodo : "0" . $periodo) . "_TA" . strtoupper((strlen($letra_tabla) > 1 ? $letra_tabla : $letra_tabla . "0")),
            function ($excel) use ($lineas) {
                $excel->sheet('Datos', function ($sheet) use ($lineas) {
                    if (empty($lineas)) {
                        return; // nada que exportar
                    }

                    $colCount   = count($lineas[0]);
                    $rowCount   = count($lineas);
                    $lastColumn = \PHPExcel_Cell::stringFromColumnIndex($colCount - 1);

                    // (1) Fila 1: números de columna (1..N)
                    $sheet->row(1, range(1, $colCount));
                    $sheet->getStyle('A1:' . $lastColumn . '1')->getAlignment()->setHorizontal('center');
                    $sheet->getStyle('A1:' . $lastColumn . '1')->getFont()->setBold(true);

                    // (2) Desde A2: encabezados + datos (headingGeneration = true)
                    // Encabezados quedarán en fila 2, datos desde fila 3
                    $sheet->fromArray($lineas, null, 'A2', true, true);

                    // Estilos de cabeceras (fila 2)
                    $sheet->getStyle('A2:' . $lastColumn . '2')->getAlignment()->setHorizontal('center');
                    $sheet->getStyle('A2:' . $lastColumn . '2')->getFont()->setBold(true);

                    // (3) Alinear datos a la derecha: filas 3 .. (rowCount + 2)
                    $dataRange = 'A3:' . $lastColumn . ($rowCount + 2);
                    $sheet->getStyle($dataRange)
                        ->getAlignment()
                        ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                });
            }
        )->download('xlsx'); */
        return Excel::create(
            $ipress->codigo . "_" . $anio . "_" . ($periodo > 9 ? $periodo : "0" . $periodo) . "_TA" . strtoupper((strlen($letra_tabla) > 1 ? $letra_tabla : $letra_tabla . "0")),
            function ($excel) use ($lineas, $letra_tabla) {

                $excel->sheet('Datos', function ($sheet) use ($lineas, $letra_tabla) {

                    if (empty($lineas)) {
                        return;
                    }

                    $colCount   = count($lineas[0]);
                    $rowCount   = count($lineas);
                    $lastColumn = \PHPExcel_Cell::stringFromColumnIndex($colCount - 1);

                    // ===============================
                    // (1) TITULO EN FILA 1
                    // ===============================
                    $titulo = "Tabla Agregada " . strtoupper(
                        (strlen($letra_tabla) > 1 ? $letra_tabla : $letra_tabla . "0")
                    ) . " - " . $this->tituloReporteExcel($letra_tabla);

                    $sheet->mergeCells('A1:' . $lastColumn . '1');
                    $sheet->row(1, [$titulo]);

                    $sheet->getStyle('A1')
                        ->getAlignment()
                        ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

                    // ===============================
                    // (2) Fila 2: números de columna
                    // ===============================
                    $sheet->row(2, range(1, $colCount));
                    $sheet->getStyle('A2:' . $lastColumn . '2')
                        ->getAlignment()
                        ->setHorizontal('center');
                    $sheet->getStyle('A2:' . $lastColumn . '2')
                        ->getFont()
                        ->setBold(true);

                    // ===============================
                    // (3) Encabezados + datos desde A3
                    // ===============================
                    $sheet->fromArray($lineas, null, 'A3', true, true);

                    // Estilos encabezados (fila 3)
                    $sheet->getStyle('A3:' . $lastColumn . '3')
                        ->getAlignment()
                        ->setHorizontal('center');
                    $sheet->getStyle('A3:' . $lastColumn . '3')
                        ->getFont()
                        ->setBold(true);

                    // ===============================
                    // (4) Datos desde fila 4
                    // ===============================
                    $dataRange = 'A4:' . $lastColumn . ($rowCount + 3);
                    $sheet->getStyle($dataRange)
                        ->getAlignment()
                        ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                });
            }
        )->download('xlsx');
    }

    public function tituloReporteExcel($letra_tabla)
    {
        $texto = "";
        switch (strtoupper($letra_tabla)) {
            case 'A':
                $texto = "Reporte de Recursos de Salud";
                break;

            case 'B1':
                $texto = "Reporte Consolidado de Produccion Asistencial en Consulta Ambulatoria";
                break;

            case 'B2':
                $texto = "Reporte Consolidado de Morbilidad en Consulta Ambulatoria";
                break;

            case 'C1':
                $texto = "Reporte Consolidado de Produccion Asistencial en Emergencia";
                break;

            case 'C2':
                $texto = "Reporte Consolidado de Morbilidad en Emergencia";
                break;

            case 'D1':
                $texto = "Reporte Consolidado de Produccion en Hospitalizacion";
                break;

            case 'D2':
                $texto = "Reporte Consolidado de Morbilidad en Hospitalizacion";
                break;

            case 'F':
                $texto = "Reporte Consolidado de Eventos bajo Vigilancia Institucional";
                break;

            case 'G':
                $texto = "Reporte Consolidado de Produccion Asistencial de Procedimientos";
                break;

            case 'G1':
                $texto = "Reporte Consolidado de Produccion Asistencial de Procedimientos";
                break;

            case 'J':
                $texto = "Reporte Consolidado de Programacion Asistencial";
                break;
            
            default:
                # code...
                break;
        }
        return $texto;
    }

    public function reporte_vista_estadistica()
    {
        $model_medico = new \App\Models\Medico();
        $model_usuario = new \App\Models\User;
        if (!Auth::check()) :
            return redirect('/auth/login');
        else:
            if (Auth::user()->actualizo_passw == 0):
                return redirect('auth/password/change');
            endif;
        endif;

        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1);
        $ruta = $ruta[0];
        if ($ruta == "listar_citas_medico_atenciones");
        $ruta = "all_citas_medico_atenciones";
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

        $id_user = Auth::user()->id;
        $usuario_actual = $model_usuario->GetById($id_user);
        $flagemergenciareferencia = $usuario_actual->flaghistoria;
        $id_consultorio_actual = $usuario_actual->id_consultorio;

        $tipo_registro = $usuario_actual->tipo_registro;

        $dni_beneficiario = "";
        $nombre_beneficiario = "";
        $estado = "0";
        $id_atencione = "0";

        $date = Carbon::now();
        $fechaDesde = $date->format('d-m-Y');
        $fechaHasta = $date->format('d-m-Y');

        $model_farma = new \App\Models\Farmacia();
        $farmacia = $model_farma->GetFarmaByUser($id_user);
        $id_farmacia = $farmacia->id;
        $modo_validacion = $farmacia->modo_validacion;

        $medico = 0;
        if ($flagemergenciareferencia != 1) {
            if (Auth::user()->tipo_registro == 1) {
                $buscamedico = $model_medico->medico_yaregistrado($usuario_actual->dni, Auth::user()->id_establecimiento);
                if (is_object($buscamedico)) :
                    $medico_actual = $model_medico->GetMedicoByDniAndEstablecimiento($buscamedico->dni, $farmacia->id_establecimiento);
                    $medico = $medico_actual->id;
                else :
                    return redirect('/dashboard')->withFlashDanger("Tiene que ser médico de este establecimiento; para entrar a esta opción: coordinar con el administrador del Sistema");
                endif;
            }
        }
        $medicos = $model_medico->ShowSoloMedicos(Auth::user()->id_establecimiento);
        $model_consultorio = new \App\Models\Consultorio;
        $consultorio = $model_consultorio->ShowAllConsultoriosSubConsultorio($farmacia->id_establecimiento);
        $model_atencione = new \App\Models\TipoAtencione;
        $atencione = $model_atencione->ShowTipoAtencionAll();

        return view('frontend.seis.reporte_vista_estadistica', compact('tipo_registro', 'usuario_actual', 'farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nombre_beneficiario', 'estado', 'flagemergenciareferencia', 'consultorio', 'medico', 'id_consultorio_actual', 'atencione', 'id_atencione', 'medicos'));
    }


    public function all_eventos_epidemiologicos($id_user = 0)
    {

        $model_medico = new \App\Models\Medico();
        $model_usuario = new \App\Models\User;
        if (!Auth::check()) :
            return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;

        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1);
        $ruta = $ruta[0];
        if ($ruta == "all_eventos_epidemiologicos");
        $ruta = "all_eventos_epidemiologicos";
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "")
          return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        
        $model_farmacia = new Farmacia();
        $id_user = Auth::user()->id;
        
        $date = Carbon::now();
        $fechaDesde = $date->format('d-m-Y');
        $fechaHasta = $date->format('d-m-Y');

        $model_eventos = new \App\Models\Eventos_epidemiologicos;
        $eventos = $model_eventos->getEventosEpidemiologicos();
        $id_establecimiento = Auth::user()->id_establecimiento;
        $establecimientoActual = Establecimiento::where("id", $id_establecimiento)->first();
        $establecimientos = $model_farmacia->GetAllIpress();
        $flagestablecimientos = Auth::user()->flagestablecimientos;

    
        return view('frontend.seis.eventos_epidemiologicos', compact('fechaDesde', 'fechaHasta', 'eventos','id_establecimiento','establecimientoActual','establecimientos','flagestablecimientos'));
        
    }

    public function listar_eventos_ajax(Request $request)
      {

        $model_eventos = new Eventos_epidemiologicos();
        $p[] = $request->fecha_ini;
        $p[] = $request->fecha_fin;
        $p[] = (isset($request->id_evento) && $request->id_evento != "") ? $request->id_evento : 0;
        $p[] = $request->id_establecimiento;
        $p[] = $request->NumeroPagina;
        $p[] = $request->NumeroRegistros;
        $data = $model_eventos->listar_eventos_ajax($p);
        $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;
        //$iTotalDisplayRecords = count($data);
        $result["PageStart"] = $request->NumeroPagina;
        $result["pageSize"] = $request->NumeroRegistros;
        $result["SearchText"] = "";
        $result["ShowChildren"] = true;
        $result["iTotalRecords"] = $iTotalDisplayRecords;
        $result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
        $result["aaData"] = $data;
        echo json_encode($result);
      }

    public function exportar_reporte_eventos_epidemiologicos($id_user, $fecha_ini, $fecha_fin, $id_evento,$id_establecimiento) {
        
        
        $modelusuario = new Farmacia();
        $usuario = $modelusuario->GetNombreUsuarioByIdUser($id_user);
        $nombre_usuario = $usuario->paterno." ".$usuario->materno." ".$usuario->name;
        Excel::create('Exportar Reportes de Eventos Epidemiologicos', function($excel) use($fecha_ini, $fecha_fin,$id_evento, $id_establecimiento, $nombre_usuario, $id_user) {
            $excel->sheet('GENERAL', function($sheet) use($fecha_ini, $fecha_fin,$id_evento, $id_establecimiento, $nombre_usuario, $id_user) {
                
                $modelfarma = new Farmacia;
                $model_usuario = new \App\Models\User;
                $model_eventos = new Eventos_epidemiologicos();
                $farmacia = $modelfarma->GetFarmaByUser($id_user);
                $id_farmacia = $farmacia->id;
                $establecimiento = $modelfarma->GetEstablecimientoPNP($id_farmacia);
                //----------------------------------------------------------------------
                $usuario_actual = $model_usuario->GetById($id_user);

                $p[] = $fecha_ini;
                $p[] = $fecha_fin;
                $p[] = (isset($id_evento) && $id_evento != "") ? $id_evento : 0;
                $p[] = $id_establecimiento;
                $p[] = 1;
                $p[] = 10000;
                $data = $model_eventos->listar_eventos_ajax($p);
               
                $nro_atendidas = isset($data[0]->totalrows)?$data[0]->totalrows:0;
                
                //--------------------------------------------------------------
                $variable = [];
                $n = 1;
                array_push($variable, array($establecimiento->nombre));
                array_push($variable, array("Reporte de Pacientes con Eventos Epidemiologicos"));
                array_push($variable, array("Fecha Desde: " . $fecha_ini));
                array_push($variable, array("Fecha Hasta: " . $fecha_fin));
                array_push($variable, array("Total: " . $nro_atendidas . " atenciones"));
                array_push($variable, array("Usuario : " . $nombre_usuario));
                array_push($variable, array(""));
                array_push($variable, array("N","Evento", "Establecimiento", "Cod Diag", "Descripcion Diag", "F. Atencion", "Nro Doc","Paciente","Servicio", "Sexo", "Edad"));

                foreach ($data as $r) {
                    array_push($variable, array($n++,$r->nombre_evento,$r->nombre_establecimiento, $r->codigo_diagnostico,$r->nombre_diagnostico, $r->fecha_atencion, $r->nro_doc_ident,$r->asegurado_paterno.' '.$r->asegurado_materno.', '.$r->asegurado_nombre,$r->servicio,$r->sexo,$r->edad));
                }
                $sheet->with($variable)->mergeCells('A2:E2')->mergeCells('A3:E3')->mergeCells('A4:C4')->mergeCells('A5:C5')->mergeCells('A6:C6');
            });
        })->export('xls');
    }

}
