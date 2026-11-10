<?php

namespace App\Http\Controllers;

use App\Enums\EstadoLaboratorio;
use App\Models\Triaje;
use App\Ws\LaboratorioLNS;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Laboratorio;
use App\Models\Farmacia;
use App\Http\Controllers\Controller;
use App\Models\Asegurado;
use App\Models\CategoriaRuaNivel;
use App\Models\Cita;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Consultorio;
use App\Models\Evaluacione;
use App\Models\Establecimiento;
use App\Models\Labnivelatencion;
use App\Models\LaboratorioArchivosResultado;
use App\Models\Medico;
use App\Models\Prestacion_procedimiento;
use App\Models\Procedimiento;
use App\Models\TipoAtencione;
use App\Models\Prestaciones;
use App\Models\SolicitudProcedimiento;
use Session;
use DB;
use Auth;
use DateTime;
use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use QrCode;
use Maatwebsite\Excel\Facades\Excel;
use stdClass;
use SoapClient;

class LaboratorioController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        //$this->middleware('auth'); 
        $this->middleware('auth', ['except' => ['modal_historial_laboratorio_paciente']]);
    }

    public function index() {
        //
		if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;
		
        $model = new Laboratorio;
        $laboratorios = $model->ShowAll();
		
		$ruta = Route::getCurrentRoute()->getPath();
		if(count($ruta=explode("/",$ruta)) > 1);$ruta=$ruta[0];
		$menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id,$ruta);
		$permiso = $menu[0];
		if($permiso->lectura == "")return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        return view('frontend.laboratorios.all', ['laboratorios' => $laboratorios,'permiso'=>$permiso]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
        return view('frontend.laboratorios.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
        $laboratorio = new Laboratorio();
        $laboratorio->ruc = $request->ruc;
        $laboratorio->nombre = $request->nombre;
        $ruc = $request->ruc;
        $existe = $laboratorio->ExisteLaboratorio($ruc, 0);
        if ($existe) {
            return redirect('/create_laboratorio')
                            ->withInput()
                            ->withErrors(array('El Laboratorio ya existe con este RUC'));
        }
        $laboratorio->save();
        return redirect('/all_laboratorios')->withFlashSuccess("Se registro correctamente el laboratorio");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
        $laboratorios = Laboratorio::findOrFail($id);
        return view('frontend.laboratorios.edit', ['laboratorios' => $laboratorios]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {
        //
        $ruc_actual = $request->ruc_actual;
        $ruc_nuevo = $request->ruc;
        $id = $request->id;
        $laboratorios = Laboratorio::find($request->id);
        $laboratorios->ruc = $request->ruc;
        $laboratorios->nombre = $request->nombre;
        $ruc = $request->ruc;

        if ($ruc_nuevo == $ruc_actual) {
            $laboratorios->save();
        } else {
            $existe = $laboratorios->ExisteLaboratorio($ruc, 0);
            if ($existe) {
                return redirect('/edit_laboratorios/' . $id)
                                ->withInput()
                                ->withErrors(array('El Laboratorio con este RUC ya existe'));
            }
            $laboratorios->save();
        }
        return redirect('/all_laboratorios')->withFlashSuccess("Se actualizo correctamente");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

    public function all_laboratorio_prescritos_by_ipress($id_user = 0) {

        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;

        if(Auth::user()->tipo_registro || Auth::user()->flagestablecimientos == 1):

            $ruta = Route::getCurrentRoute()->getPath();
            if (count($ruta = explode("/", $ruta)) > 1)
                ;$ruta = $ruta[0];
            $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
            $permiso = $menu[0];
            if ($permiso->lectura == "")
                return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

            $id_user = Auth::user()->id;
            
            $dni_beneficiario = "";
            $nro_prestacion = "";
            $nro_examen = "";

            $date = Carbon::now();
            $fechaDesde = $date->format('d-m-Y');
            $fechaHasta = $date->format('d-m-Y');
            //==================== 
            $model_farma = new Farmacia();
            $farmacia = $model_farma->GetIpressByIduser($id_user);
            $id_establecimiento = $farmacia->id;
            $id_farmacia = $farmacia->id;
            $farmacias = $model_farma->GetFarmaciaByIdEstablecimiento( auth()->user()->id_establecimiento );
            //====================
            $model_laboratorio = new Laboratorio();
            // $listados = $model_laboratorio->AllExamenLaboratorioByEstablecimientoAndFechaDesdeHasta($id_establecimiento, $fechaDesde, $fechaHasta);
            // $cantidad = $model_laboratorio->GetCountExamenLaboratorioByFechaDesdeHasta($id_establecimiento, $fechaDesde, $fechaHasta);
            //====================
            $id_establecimiento = auth()->user()->id_establecimiento;
            $model_atencione = new TipoAtencione;
            
            //Configuración de tipo de atención por responsable de laboratorio y profesional tecnico
            $user_establecimiento = auth()->user()->id_establecimiento;
            $user_farmacia = auth()->user()->id_farmacia;
            //Fiscalizador o responsable de laboratorio
            // $user_fiscalizador = 0; //Por default
            // $user_fiscalizador = auth()->user()->flagfiscalizador;
            // if ($user_fiscalizador == 0) {
            //     if ($user_farmacia == 19) {
            //         //Emergencia
            //         $tipo_atenciones = $model_atencione->listarTipoAtencionesLaboratorioPorId(2);
            //         $tipo_perfil = 0;
            //     } else {
            //         $tipo_atenciones = $model_atencione->listarTipoAtencionesLaboratorioPorId(1);
            //         $tipo_perfil = 0;
            //     }
            // } else {
                $tipo_atenciones = $model_atencione->listarTipoAtencionesLaboratorio();
                $tipo_perfil = 1;
            //}

            if ($farmacia && $farmacia->nivel && (intval($farmacia->nivel) === 2 || intval($farmacia->nivel) === 1))  {
              $model_consultorio = new Consultorio();
              $model_triaje = new Triaje();

              $consultorios = $model_consultorio->GetAllPorIpress($user_establecimiento);
              $prioridades = $model_triaje->ShowPrioridad();

              return view('frontend.laboratorios.all_laboratorios_prescritos_by_ipress_nivel2', compact('farmacia', 'id_farmacia', 'dni_beneficiario', 'nro_prestacion', 'nro_examen','id_user','id_establecimiento','tipo_atenciones','tipo_perfil','farmacias', 'consultorios', 'prioridades'));
            }

            $model_laboratorio = new Laboratorio();
            $ordenes_toma_muestra = $model_laboratorio->getOrdenTomaMuestraUsuario(Auth::user()->id, '20250714', '20250715');

            return view('frontend.laboratorios.all_laboratorios_prescritos_by_ipress', compact(/*'listados', 'cantidad', */'farmacia', 'fechaDesde', 'fechaHasta', 'id_farmacia', 'dni_beneficiario', 'nro_prestacion', 'nro_examen','id_user','id_establecimiento','tipo_atenciones','tipo_perfil','farmacias','ordenes_toma_muestra'));
        else:
            return redirect('/dashboard');
        endif;
    }

    public function listar_examenes_laboratorios($cadenaDataString) {
        
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $dataArray = explode(',', $cadenaDataString);
        
        if (!$dataArray[0]):
            return redirect('/all_laboratorio_prescritos_by_ipress');
        endif;
        
        
        $id_user =$dataArray[0];
        $estado = $dataArray[10];
        $id_farmacia = $dataArray[11];
        //==================== 
        $model_farma = new Farmacia();
        $farmacia = $model_farma->GetIpressByIduser($id_user);
        $id_establecimiento = $farmacia->id;
        // $id_farmacia = $farmacia->id;
        //----------------------------------------------------------------------

        $tipo_atencion = $dataArray[6];
        $id_servicio = $dataArray[7];
        $referido = $this->normalizarReferidoLaboratorio($dataArray[5]);
        $prioridad_emergencia = $dataArray[9];
        
        $fechaDesde = $dataArray[2];
        $fechaHasta = $dataArray[3];
        $dni_beneficiario = strtoupper($dataArray[4]);
        $apellido_nombres = strtoupper($dataArray[12]);
        // $nro_prestacion = $request->nro_prestacion;
        $nro_examen = $dataArray[8];
        $id_hora_toma_muestra = $dataArray[13];
        $id_ubicacion_hospital = $dataArray[14];

        $model_atencione = new TipoAtencione;
        $tipo_atenciones = $model_atencione->listarTipoAtencionesLaboratorio();
        $model_laboratorio = new Laboratorio();

        $listadosAgrupados = null;
        if ($estado == EstadoLaboratorio::PENDIENTE || $estado == EstadoLaboratorio::ATENDIDO || $estado == EstadoLaboratorio::TODO || $estado == EstadoLaboratorio::ANULADO || $estado == EstadoLaboratorio::PENDIENTE_ENVIO || $estado == EstadoLaboratorio::OBSERVADO) {
            $listados = $this->obtenerListadoExamenesLaboratorioPorFiltros([
                'id_user' => $id_user,
                'estado' => $estado,
                'id_farmacia' => $id_farmacia,
                'tipo_atencion' => $tipo_atencion,
                'fechaDesde' => $fechaDesde,
                'fechaHasta' => $fechaHasta,
                'dni_beneficiario' => $dni_beneficiario,
                'nro_examen' => $nro_examen,
                'id_servicio' => $id_servicio,
                'referido_valor' => $referido,
                'prioridad_emergencia' => $prioridad_emergencia,
                'apellido_nombres' => $apellido_nombres,
                'id_hora_toma_muestra' => $id_hora_toma_muestra,
                'id_ubicacion_hospital' => $id_ubicacion_hospital,
            ]);
            $cantidad = count($listados);

            if ($estado != EstadoLaboratorio::PENDIENTE) { // Es diferente de Ordenes pendientes
              $array_codigos = [];
              foreach ($listados as $value) {
                /*foreach ($this->obtenerCodigosOrdenDeFila($value, $tipo_atencion) as $codigo) {
                  $array_codigos[] = $codigo;
                }*/
                if (!empty($value->cod_orden_lab)) {
                  $array_codigos[] = $value->cod_orden_lab;
                }
              }
              $array_codigos = array_values($array_codigos);

              $respuesta = null;
              $sitio = @fopen(env('LABORATORIO_LNS_URI'), 'r');

              if ($sitio) {
                if (count($array_codigos) > 0) {
                  $model_laboratorio_resultado = new \App\Ws\LaboratorioLNS();
                  $respuesta = $model_laboratorio_resultado->ObtenerOrdenes($array_codigos);
                }
                $this->enriquecerListadosConOrdenesWs($listados, $tipo_atencion, $respuesta);
                fclose($sitio);
              } else {
                $this->inicializarEstadoLabListados($listados);
              }
              $listadosAgrupados = collect($listados)->groupBy('id_cita_laboratorio_agrupa');
            } else {
              foreach ($listados as $key => $value) {
                // Estado por defecto
                $value->estado_lab = 0;
                // $value->cantExamenConResultado = 0;
                // $value->orden_info_detallado = "";
              }
              $listadosAgrupados = collect($listados)->groupBy('id_cita_laboratorio_agrupa');
            }

            if($tipo_atencion == 1):
              return view('frontend.laboratorios.modal_lista_ambulatorio', compact('listados', 'cantidad', 'farmacia', 'fechaDesde', 'fechaHasta', 'id_farmacia', 'dni_beneficiario', 'nro_prestacion', 'nro_examen','id_user','tipo_atenciones','id_establecimiento','estado','tipo_atencion', 'listadosAgrupados'));
            elseif($tipo_atencion == 2):
              return view('frontend.laboratorios.modal_lista_emergencia', compact('listados', 'cantidad', 'farmacia', 'fechaDesde', 'fechaHasta', 'id_farmacia', 'dni_beneficiario', 'nro_prestacion', 'nro_examen','id_user','tipo_atenciones','id_establecimiento','estado','tipo_atencion'));
            elseif ($tipo_atencion == 3):
             return view('frontend.laboratorios.modal_lista_hospitalizacion', compact('listados', 'cantidad', 'farmacia', 'fechaDesde', 'fechaHasta', 'id_farmacia', 'dni_beneficiario', 'nro_prestacion', 'nro_examen','id_user','tipo_atenciones','id_establecimiento','estado','tipo_atencion'));
            endif;
        }
                                    
        if ($estado == EstadoLaboratorio::ATENDIDO_LABCORE) { // Atendido Labcore
            $estado_labcore = 1;
            $ws_laboratorio = new \App\Ws\LaboratorioLNS();
            if (env('CONEXION_WS_LAB') == "true") {
                $listadosOrdenes = $model_laboratorio->AllOrdenExamenLaboratorioByEstablecimientoAndFechaDesdeHasta($id_establecimiento, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_examen, $tipo_atencion, $id_servicio, $referido, $prioridad_emergencia,$estado,$id_farmacia);
                try {
                    foreach ($listadosOrdenes as $orden) {
                        $lista[] = $orden->cod_orden_lab;
                    }
                } catch (\Throwable $th) {
                    return [
                        "messages" => "Hubo un error con la variable listadosOrdenes",
                        "error" => $th->getMessage()
                    ];
                }
                
                
                //Pruebas manuales
                // $obj = '2403260617';
                // $lista[] = $obj;
                // $obj = '2403260618';
                // $lista[] = $obj;
                // $obj = '2403260620';
                // $lista[] = $obj;
                
                $respuesta = $ws_laboratorio->UpdateOrdenesLaboratorioValidados($lista);
                
                //Filtramos los o_numero que no sean NULL
                try {
                    foreach ($respuesta as $value) {
                        if ($value->o_numero !== "NULL") {
                            //Creamos objeto
                            $objeto = new stdClass();
                            $objeto->o_numero = $value->o_numero;
                            $objeto->descripcion = $value->Descripcion;
                            $listaFiltrada[] = $objeto;
                        }
                    }
                } catch (\Throwable $th) {
                    return [
                        "messages" => "Hubo un error con la variable respuesta",
                        "error" => $th->getMessage()
                    ];
                }
                
                try {
                    foreach ($listaFiltrada as $value) {
                        if ($value->descripcion == "VALIDADO") {
                            $model_laboratorio->UpdateEstadoValidadoLabByIdCO($value->o_numero);
                        }
                    }
                } catch (\Throwable $th) {
                    return [
                        "messages" => "Hubo un error con la variable listaFiltrada",
                        "error" => $th->getMessage()
                    ];
                }
                //dd($listaFiltrada);

                $listados = $model_laboratorio->AllValidadosExLabByEstaAndFechaDesdeHasta($id_establecimiento, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_examen, $tipo_atencion, $id_servicio, $referido, $prioridad_emergencia,$estado, $id_farmacia);
                //falta editar la consulta
                $cantidad = $model_laboratorio->GetCountExamenLaboratorioByFechaDesdeHasta($id_establecimiento, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_examen, $tipo_atencion, $id_servicio, $referido, $prioridad_emergencia,$estado, $id_farmacia);
                
                return view('frontend.laboratorios.modal_examen_laboratorio_validado', compact('listados', 'cantidad', 'farmacia', 'fechaDesde', 'fechaHasta', 'id_farmacia', 'dni_beneficiario', 'nro_prestacion', 'nro_examen','id_user','tipo_atenciones','id_establecimiento','estado_labcore'));
            } 
        }

        if ($estado == EstadoLaboratorio::FIRMADO) { // Firmado
            $estado_labcore = 2;

            $listados = $model_laboratorio->AllValidadosExLabByEstaAndFechaDesdeHasta($id_establecimiento, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_examen, $tipo_atencion, $id_servicio, $referido, $prioridad_emergencia,$estado,$id_farmacia);
            //falta editar la consulta
            $cantidad = $model_laboratorio->GetCountExamenLaboratorioByFechaDesdeHasta($id_establecimiento, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_examen, $tipo_atencion, $id_servicio, $referido, $prioridad_emergencia,$estado, $id_farmacia);
            
            return view('frontend.laboratorios.modal_examen_laboratorio_validado', compact('listados', 'cantidad', 'farmacia', 'fechaDesde', 'fechaHasta', 'id_farmacia', 'dni_beneficiario', 'nro_prestacion', 'nro_examen','id_user','tipo_atenciones','id_establecimiento','estado_labcore'));
        }
        
    }

    private function pgArrayToPhp($pgArray) {
      if (empty($pgArray) || $pgArray === '{}') return [];
      $clean = trim($pgArray, '{}');
      return explode(',', $clean);
    }

    private function pgArrayToPhp2($pgArray) {
      if (empty($pgArray) || $pgArray === '{}') return '';
      return str_replace(['{', '}'], '', $pgArray);
    }

    private function obtenerCodigosOrdenDeFila($row, $tipo_atencion) {
        /*$tipo = (string) $tipo_atencion;

        if ($tipo === '1') {
            $codigos = isset($row->codigos_orden_lab) ? $row->codigos_orden_lab : [];
            if (!is_array($codigos)) {
                $codigos = $this->pgArrayToPhp($codigos);
            }
            $resultado = [];
            foreach ($codigos as $codigo) {
                $codigo = trim((string) $codigo, " \"'");
                if ($codigo !== '') {
                    $resultado[] = $codigo;
                }
            }
            return $resultado;
        }*/

        if (!empty($row->cod_orden_lab)) {
            return [(string) $row->cod_orden_lab];
        }

        return [];
    }

    private function indexarRespuestaOrdenesWs($respuesta) {
        $indice = [];
        if ($respuesta === null || !isset($respuesta->CallListarOrdenesConSinValidacionResult->RespuestaOrdenLab)) {
            return $indice;
        }

        $items = $respuesta->CallListarOrdenesConSinValidacionResult->RespuestaOrdenLab;
        if (!is_array($items)) {
            $items = [$items];
        }

        foreach ($items as $apiItem) {
            if (isset($apiItem->orden) && isset($apiItem->codigoMensaje) && $apiItem->codigoMensaje === '00') {
                $indice[(string) $apiItem->orden] = $apiItem;
            }
        }

        return $indice;
    }

    private function aplicarEstadoLabDesdeApiItem($row, $apiItem) {
        $row->estado_lab = 0;
        $row->cantExamenConResultado = 0;
        $row->orden_info_detallado = '';

        if (isset($apiItem->validadoCompleto)) {
            if ($apiItem->validadoCompleto === 'SI') {
                $row->estado_lab = 1;
            } elseif ($apiItem->validadoCompleto === 'NO') {
                $row->estado_lab = 2;
            }
        }

        if (isset($apiItem->cantExamenConResultado) && isset($apiItem->cantExamenTotal)) {
            $row->cantExamenConResultado = $apiItem->cantExamenConResultado;
            $row->orden_info_detallado = $apiItem->cantExamenConResultado . ' de ' . $apiItem->cantExamenTotal;
        }
    }

    private function calcularEstadoLabAgrupado(array $codigosFila, array $indiceOrdenes) {
        if (count($codigosFila) === 0) {
            return 0;
        }

        $todosValidados = true;
        $algunoConResultado = false;
        $algunoProcesando = false;
        $encontradoEnWs = false;

        foreach ($codigosFila as $codigo) {
            if (!isset($indiceOrdenes[$codigo])) {
                $todosValidados = false;
                continue;
            }

            $encontradoEnWs = true;
            $item = $indiceOrdenes[$codigo];

            if (isset($item->cantExamenConResultado) && intval($item->cantExamenConResultado) > 0) {
                $algunoConResultado = true;
            }

            if (isset($item->validadoCompleto) && $item->validadoCompleto === 'NO') {
                $algunoProcesando = true;
                $todosValidados = false;
            } elseif (!isset($item->validadoCompleto) || $item->validadoCompleto !== 'SI') {
                $todosValidados = false;
            }
        }

        if (!$encontradoEnWs) {
            return 0;
        }
        if ($todosValidados && $algunoConResultado) {
            return 1;
        }
        if ($algunoConResultado || $algunoProcesando) {
            return 2;
        }

        return 0;
    }

    private function enriquecerListadosConOrdenesWs($listados, $tipo_atencion, $respuesta) {
        $indiceOrdenes = $this->indexarRespuestaOrdenesWs($respuesta);
        $tipo = (string) $tipo_atencion;

        foreach ($listados as $value) {
            $value->estado_lab = 0;
            $value->cantExamenConResultado = 0;
            $value->orden_info_detallado = '';
            $value->ordenes_ws = [];
            $value->tiene_resultado_parcial_o_completo = false;

            $codigosFila = $this->obtenerCodigosOrdenDeFila($value, $tipo_atencion);
            if (count($codigosFila) === 0) {
                continue;
            }

            /*if ($tipo === '1') {
                $detalles = [];
                foreach ($codigosFila as $codigo) {
                    if (!isset($indiceOrdenes[$codigo])) {
                        continue;
                    }
                    $item = $indiceOrdenes[$codigo];
                    $cantConResultado = isset($item->cantExamenConResultado) ? intval($item->cantExamenConResultado) : 0;
                    if ($cantConResultado > 0) {
                        $value->tiene_resultado_parcial_o_completo = true;
                    }
                    $detalles[] = (object) [
                        'cod_orden_lab' => $codigo,
                        'validadoCompleto' => isset($item->validadoCompleto) ? $item->validadoCompleto : null,
                        'cantExamenConResultado' => $cantConResultado,
                        'cantExamenTotal' => isset($item->cantExamenTotal) ? $item->cantExamenTotal : 0,
                        'orden_info_detallado' => (isset($item->cantExamenConResultado) && isset($item->cantExamenTotal))
                            ? $item->cantExamenConResultado . ' de ' . $item->cantExamenTotal
                            : '',
                    ];
                }
                $value->ordenes_ws = $detalles;

                $cantidadOrdenes = isset($value->cantidad_ordenes) ? intval($value->cantidad_ordenes) : count($codigosFila);
                if ($cantidadOrdenes === 1) {
                    $value->cod_orden_lab = $codigosFila[0];
                    if (isset($indiceOrdenes[$codigosFila[0]])) {
                        $this->aplicarEstadoLabDesdeApiItem($value, $indiceOrdenes[$codigosFila[0]]);
                    }
                } else {
                    $value->estado_lab = $this->calcularEstadoLabAgrupado($codigosFila, $indiceOrdenes);
                    if ($value->estado_lab === 2) {
                        $partesDetalle = [];
                        foreach ($detalles as $detalle) {
                            if ($detalle->cantExamenConResultado > 0 && $detalle->orden_info_detallado !== '') {
                                $partesDetalle[] = $detalle->cod_orden_lab . ': ' . $detalle->orden_info_detallado;
                            }
                        }
                        $value->orden_info_detallado = implode(' | ', $partesDetalle);
                    }
                    $value->cantExamenConResultado = 0;
                    foreach ($detalles as $detalle) {
                        $value->cantExamenConResultado += $detalle->cantExamenConResultado;
                    }
                }
                continue;
            }*/

            $codigo = $codigosFila[0];
            if (isset($indiceOrdenes[$codigo])) {
                $this->aplicarEstadoLabDesdeApiItem($value, $indiceOrdenes[$codigo]);
            }
        }

        $this->finalizarOrdenesResultadoConsultaExterna($listados, $indiceOrdenes);
    }

    private function inicializarEstadoLabListados($listados) {
        foreach ($listados as $value) {
            $value->estado_lab = 0;
            $value->cantExamenConResultado = 0;
            $value->orden_info_detallado = '';
            $value->ordenes_ws = [];
            $value->tiene_resultado_parcial_o_completo = false;
        }
        $this->finalizarOrdenesResultadoConsultaExterna($listados, []);
    }

    private function finalizarOrdenesResultadoConsultaExterna($listados, array $indiceOrdenes = []) {
        foreach ($listados as $value) {
            $cantidadOrdenes = isset($value->cantidad_ordenes) ? intval($value->cantidad_ordenes) : 0;
            if ($cantidadOrdenes > 1) {
                $this->armarOrdenesResultadoConsultaExterna($value, $indiceOrdenes);
                continue;
            }
            if ($cantidadOrdenes === 1 && isset($value->cantidad_total_por_orden) && is_array($value->cantidad_total_por_orden)) {
                $value->cantidad_total = intval($value->cantidad_total_por_orden[0]); // ? intval($value->cantidad_total_por_orden[0]) : 0;
                $value->cantidad_enviado = intval($value->cantidad_enviado_por_orden[0]); // ? intval($value->cantidad_enviado_por_orden[0]) : 0;
                $value->cantidad_pendiente = intval($value->cantidad_pendiente_por_orden[0]); // ? intval($value->cantidad_pendiente_por_orden[0]) : 0;
            }
        }
    }

    private function armarOrdenesResultadoConsultaExterna($row, array $indiceOrdenes = []) {
        $codigos = $this->obtenerCodigosOrdenDeFila($row, '1');
        $enviados = isset($row->cantidad_enviado_por_orden) && is_array($row->cantidad_enviado_por_orden)
            ? $row->cantidad_enviado_por_orden : [];
        $totales = isset($row->cantidad_total_por_orden) && is_array($row->cantidad_total_por_orden)
            ? $row->cantidad_total_por_orden : [];
        $pendientes = isset($row->cantidad_pendiente_por_orden) && is_array($row->cantidad_pendiente_por_orden)
            ? $row->cantidad_pendiente_por_orden : [];

        $ordenes = [];
        foreach ($codigos as $indice => $codigo) {
            $orden = (object) [
                'cod_orden_lab' => $codigo,
                'cantidad_enviado' => intval($enviados[$indice]) ? intval($enviados[$indice]) : 0,
                'cantidad_total' => intval($totales[$indice]) ? intval($totales[$indice]) : 0,
                'cantidad_pendiente' => intval($pendientes[$indice]) ? intval($pendientes[$indice]) : 0,
                'estado_lab' => 0,
                'cantExamenConResultado' => 0,
                'orden_info_detallado' => '',
            ];

            if (isset($indiceOrdenes[$codigo])) {
                $this->aplicarEstadoLabDesdeApiItem($orden, $indiceOrdenes[$codigo]);
            }

            $ordenes[] = $orden;
        }

        $row->ordenes_resultado = $ordenes;
        $row->resultado_agrupado_todas_estado_1 = count($ordenes) > 0;
        $row->resultado_agrupado_alguna_estado_2 = false;
        $row->resultado_agrupado_total_pendiente = 0;
        $row->resultado_agrupado_tiene_boton = false;
        $row->resultado_agrupado_caso = 0;

        foreach ($ordenes as $orden) {
            if (intval($orden->estado_lab) !== 1) {
                $row->resultado_agrupado_todas_estado_1 = false;
            }
            if (intval($orden->estado_lab) === 2) {
                $row->resultado_agrupado_alguna_estado_2 = true;
            }
            $row->resultado_agrupado_total_pendiente += intval($orden->cantidad_pendiente);
            if (intval($orden->estado_lab) !== 0 && intval($orden->cantExamenConResultado) > 0) {
                $row->resultado_agrupado_tiene_boton = true;
            }
        }

        if ($row->resultado_agrupado_alguna_estado_2) {
            $row->resultado_agrupado_caso = 3;
        } elseif ($row->resultado_agrupado_todas_estado_1 && $row->resultado_agrupado_total_pendiente > 0) {
            $row->resultado_agrupado_caso = 2;
        } elseif ($row->resultado_agrupado_todas_estado_1 && $row->resultado_agrupado_total_pendiente === 0) {
            $row->resultado_agrupado_caso = 1;
        } elseif ($row->resultado_agrupado_tiene_boton) {
            $row->resultado_agrupado_caso = 3;
        }
    }

    private function normalizarReferidoLaboratorio($referidoValor) {
      if ((string) $referidoValor === '1') {
        return 'SI';
      } elseif ((string) $referidoValor === '2') {
        return 'NO';
      }
      return '';
    }

    /**
     * Obtiene el listado de órdenes de laboratorio según filtros (misma lógica que listar_examenes_laboratorios).
     */
    public function obtenerListadoExamenesLaboratorioPorFiltros(array $filtros) {
        $id_user = $filtros['id_user'];
        $estado = $filtros['estado'];
        $id_farmacia = isset($filtros['id_farmacia']) ? $filtros['id_farmacia'] : '';

        $model_farma = new Farmacia();
        $farmacia = $model_farma->GetIpressByIduser($id_user);
        $id_establecimiento = $farmacia->id;

        $tipo_atencion = $filtros['tipo_atencion'];
        $id_servicio = isset($filtros['id_servicio']) ? $filtros['id_servicio'] : '';
        $referido = $this->normalizarReferidoLaboratorio(isset($filtros['referido_valor']) ? $filtros['referido_valor'] : '');
        $prioridad_emergencia = isset($filtros['prioridad_emergencia']) ? $filtros['prioridad_emergencia'] : '';
        $fechaDesde = isset($filtros['fechaDesde']) ? $filtros['fechaDesde'] : '';
        $fechaHasta = isset($filtros['fechaHasta']) ? $filtros['fechaHasta'] : '';
        $dni_beneficiario = isset($filtros['dni_beneficiario']) ? strtoupper($filtros['dni_beneficiario']) : '';
        $apellido_nombres = isset($filtros['apellido_nombres']) ? strtoupper($filtros['apellido_nombres']) : '';
        $nro_examen = isset($filtros['nro_examen']) ? $filtros['nro_examen'] : '';
        $id_hora_toma_muestra = isset($filtros['id_hora_toma_muestra']) ? $filtros['id_hora_toma_muestra'] : '';
        $id_ubicacion_hospital = isset($filtros['id_ubicacion_hospital']) ? $filtros['id_ubicacion_hospital'] : '';

        if (!in_array($estado, [
          EstadoLaboratorio::PENDIENTE,
          EstadoLaboratorio::ATENDIDO,
          EstadoLaboratorio::TODO,
          EstadoLaboratorio::ANULADO,
          EstadoLaboratorio::PENDIENTE_ENVIO,
          EstadoLaboratorio::OBSERVADO,
        ], true)) {
            return [];
        }

        $model_laboratorio = new Laboratorio();
        $listados = [];

        switch ((string) $tipo_atencion) {
            case '1':
                $listados = $model_laboratorio->AllExamenLaboratorioConsultaExternaList(
                    $id_establecimiento,
                    $fechaDesde,
                    $fechaHasta,
                    $dni_beneficiario,
                    $nro_examen,
                    $tipo_atencion,
                    $id_servicio,
                    $referido,
                    $prioridad_emergencia,
                    $estado,
                    $id_farmacia,
                    $apellido_nombres
                ); //dd($listados);

                /*$listados = array_map(function ($row) {
                    $row->ids_orden_lab = $this->pgArrayToPhp($row->ids_orden_lab);
                    $row->codigos_orden_lab = $this->pgArrayToPhp($row->codigos_orden_lab);
                    $row->servicios = $this->pgArrayToPhp($row->servicios);
                    $row->observaciones_orden_lab = $this->pgArrayToPhp($row->observaciones_orden_lab);
                    $row->cantidad_sr_por_orden = $this->pgArrayToPhp($row->cantidad_sr_por_orden);
                    $row->cantidad_total_por_orden = $this->pgArrayToPhp($row->cantidad_total_por_orden);
                    $row->cantidad_enviado_por_orden = $this->pgArrayToPhp($row->cantidad_enviado_por_orden);
                    $row->cantidad_pendiente_por_orden = $this->pgArrayToPhp($row->cantidad_pendiente_por_orden);
                    $row->ids_orden_lab_texto = implode(', ', $row->ids_orden_lab);
                    $row->servicios_texto = implode(', ', $row->servicios);
                    $row->codigos_orden_lab_texto = implode(', ', $row->codigos_orden_lab);
                    $row->cantidad_sr_por_orden_texto = implode(', ', $row->cantidad_sr_por_orden);

                    $row->cantidad_total = array_sum(array_map('intval', $row->cantidad_total_por_orden));
                    $row->cantidad_enviado = array_sum(array_map('intval', $row->cantidad_enviado_por_orden));
                    $row->cantidad_pendiente = array_sum(array_map('intval', $row->cantidad_pendiente_por_orden));
                    $row->cantidad_ordenes_con_sr = count(array_filter($row->cantidad_sr_por_orden, function($v) { return intval($v) > 0; }));

                    return $row;
                }, $lista);*/

                /*if ($estado == EstadoLaboratorio::PENDIENTE) {
                    $this->finalizarOrdenesResultadoConsultaExterna($listados, []);
                }*/
                break;

            case '2':
                $listados = $this->GetAllOrdenesEmergencia(
                    $id_establecimiento,
                    $fechaDesde,
                    $fechaHasta,
                    $dni_beneficiario,
                    $nro_examen,
                    $tipo_atencion,
                    $id_servicio,
                    $referido,
                    $prioridad_emergencia,
                    $estado,
                    $id_farmacia,
                    $apellido_nombres
                );
                break;

            case '3':
                $listados = $model_laboratorio->AllExamenLaboratorioHospitalizacionList_v2(
                    $id_establecimiento,
                    $fechaDesde,
                    $fechaHasta,
                    $dni_beneficiario,
                    $nro_examen,
                    $tipo_atencion,
                    $id_servicio,
                    $referido,
                    $prioridad_emergencia,
                    $estado,
                    $id_farmacia,
                    $id_hora_toma_muestra,
                    $id_ubicacion_hospital
                );
                foreach ($listados as $key) {
                    $model_emergencia = new \App\Models\Emergencia;
                    $key->ubicacion_cama = (!is_null($key->id_cama) && intval($key->id_cama) > 0
                        ? $model_emergencia->GetObtenerCama($key->id_cama)
                        : 0);
                }
                break;
        }

        return $listados;
    }

    function obtenerPrimeraFechaValida($fechas) {
        // Convertir a array si es string
        if (!is_array($fechas)) {
            $fechas = explode(',', $fechas);
        }

        // Limpiar espacios y filtrar vacíos o null
        $fechas_limpias = array_filter(array_map('trim', $fechas), function($fecha) {
            return !empty($fecha) && strtolower($fecha) !== 'null';
        });

        // Retornar la primera fecha válida o null
        return count($fechas_limpias) > 0 ? reset($fechas_limpias) : null;
    }

    public function all_laboratorio_atendidas_by_ipress($id_user = 0) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        if(Auth::user()->tipo_registro || Auth::user()->flagestablecimientos == 1):

            $ruta = Route::getCurrentRoute()->getPath();
            if (count($ruta = explode("/", $ruta)) > 1)
                ;$ruta = $ruta[0];
            $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
            $permiso = $menu[0];
            if ($permiso->lectura == "")
                return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

            $id_user = Auth::user()->id;
            
            $dni_beneficiario = "";
            $nro_prestacion = "";
            $nro_examen = "";

            $date = Carbon::now();
            $fechaDesde = $date->format('d-m-Y');
            $fechaHasta = $date->format('d-m-Y');
            //==================== 
            $model_farma = new Farmacia();
            $farmacia = $model_farma->GetIpressByIduser($id_user);
            $id_establecimiento = $farmacia->id;
            $id_farmacia = $farmacia->id;
            //====================
            $model_laboratorio = new Laboratorio();
            $listados = $model_laboratorio->AllExamenLaboratorioByEstablecimientoAndFechaDesdeHasta($id_establecimiento, $fechaDesde, $fechaHasta);
            $cantidad = $model_laboratorio->GetCountExamenLaboratorioByFechaDesdeHasta($id_establecimiento, $fechaDesde, $fechaHasta);
            //====================
            return view('frontend.laboratorios.all_laboratorios_prescritos_by_ipress', compact('listados', 'cantidad', 'farmacia', 'fechaDesde', 'fechaHasta', 'id_farmacia', 'dni_beneficiario', 'nro_prestacion', 'nro_examen','id_user' ));
        else:
            return redirect('/dashboard');
        endif;
    }

    public function listar_atenciones_laboratorios(Request $request) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        if (!$request->id_user):
            return redirect('/all_laboratorio_prescritos_by_ipress');
        endif;

        $id_user =$request->id_user;
        //==================== 
        $model_farma = new Farmacia();
        $farmacia = $model_farma->GetIpressByIduser($id_user);
        $id_establecimiento = $farmacia->id;
        $id_farmacia = $farmacia->id;
        //----------------------------------------------------------------------
        
        $fechaDesde = $request->fechaDesde;
        $fechaHasta = $request->fechaHasta;
        $dni_beneficiario = strtoupper($request->dni_beneficiario);
        $nro_prestacion = $request->nro_prestacion;
        $nro_examen = $request->nro_examen;
        
        $model_laboratorio = new Laboratorio();
        $listados = $model_laboratorio->AllExamenLaboratorioByEstablecimientoAndFechaDesdeHasta($id_establecimiento, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_prestacion, $nro_examen);
        
        $cantidad = $model_laboratorio->GetCountExamenLaboratorioByFechaDesdeHasta($id_establecimiento, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_prestacion, $nro_examen);
        
        return view('frontend.laboratorios.all_laboratorios_prescritos_by_ipress', compact('listados', 'cantidad', 'farmacia', 'fechaDesde', 'fechaHasta', 'id_farmacia', 'dni_beneficiario', 'nro_prestacion', 'nro_examen','id_user' ));
    }
    
    public function formulario_atencion_laboratorio($id, $tipo_examen) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $id_user = Auth::user()->id;
        $tipo_user = Auth::user()->tipo_registro;

        if(Auth::user()->tipo_registro || Auth::user()->flagestablecimientos == 1):

        //-----------------------------------------------        
        $modelFarmacia = new Farmacia();
        $establecimiento = $modelFarmacia->GetIpressByIduser($id_user);
        $id_ipress = $establecimiento->id;
        /************************************************************************************************************/
        $model_prestacion = new \App\Models\Prestaciones;
        $model_laboratorio = new \App\Models\Laboratorio;        

        $laboratorio = $model_laboratorio->GetLaboratorioById($id);
        $jefe_laboratorio = $model_laboratorio->AllMedicosByIpress($id_ipress);
        $user = $model_laboratorio->AllResponsableByIpress($id_ipress);

        $id_prestacion=$laboratorio->id_prestacion;
        $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
        $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
        $proclaboratorio = $model_laboratorio->GetProcedimientosByIdLaboratorio($id_prestacion,$tipo_examen,$id);

        $idjefelaboratorio = 0;
        $idresponsable = 0;
        
        if(count($proclaboratorio)>0):
            return view('frontend.laboratorios.form_laboratorio_atencion', compact('prestacion','diagnostico','laboratorio','proclaboratorio','user','jefe_laboratorio','tipo_examen','idjefelaboratorio','idresponsable','id_ipress','id_user'));    
        else:
            return redirect('all_laboratorio_prescritos_by_ipress');
        endif;
    else:
        return redirect('all_laboratorio_prescritos_by_ipress');
    endif;
    }

    public function formularios($id) {
        /*****************************/
        switch ($id) {
            case '1': return view('frontend.laboratorios.form_bioquimica'); break;
            case '2': return view('frontend.laboratorios.form_serologica'); break;
            case '3': return view('frontend.laboratorios.form_hematologia'); break;
            case '4': return view('frontend.laboratorios.form_inmunoserologia'); break;
            case '5': return view('frontend.laboratorios.form_microbiologia'); break;
        }
        
    }

    public function registrar_examen_laboratorio_transaction(Request $request) {
        
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $model_laboratorio = new \App\Models\Laboratorio;
        ///dd($request);

        if ($request->prueba != null) {
            switch ($request->tipo_examen) {
                //bioquimica            
                case '1': $id_tipo_examen = $model_laboratorio->RegistrarExamenBioquimica($request->id_laboratorio, $request->id_ipress, $request->idresponsable, $request->idjefelaboratorio, $request->glucosa1, $request->urea, $request->creatinina, $request->colesterol, $request->trigleceridos, $request->hdl_colesterol, $request->ldl_colesterol, $request->amilasa, $request->acido_urico, $request->proteinas_totales,$request->albumina1,$request->globulinas,$request->tgo,$request->tgp,$request->bilirrubina_total,$request->bilirrubina_directa,$request->bilirrubina_indirecta,$request->fosfatasa_alcalina);break;
                
                //hematologia
                case '2': $id_tipo_examen = $model_laboratorio->RegistrarExamenHematologia($request->id_laboratorio, $request->id_ipress, $request->idresponsable, $request->idjefelaboratorio, $request->hematies1, $request->hemoglobina, $request->hematocrito, $request->vsg, $request->t_coagulacion, $request->t_sangria, $request->t_protombina, $request->leucocitos1, $request->neutrofilos, $request->basofilos,$request->monocitos,$request->linfocitos,$request->plaquetas,$request->mielcitos,$request->metamielcitos,$request->abastonados,$request->segmentados);break;

                //hematologia
                case '3': $id_tipo_examen = $model_laboratorio->RegistrarExamenHematologia($request->id_laboratorio, $request->id_ipress, $request->idresponsable, $request->idjefelaboratorio, $request->hematies1, $request->hemoglobina, $request->hematocrito, $request->vsg, $request->t_coagulacion, $request->t_sangria, $request->t_protombina, $request->leucocitos1, $request->neutrofilos, $request->basofilos,$request->monocitos,$request->linfocitos,$request->plaquetas,$request->mielcitos,$request->metamielcitos,$request->abastonados,$request->segmentados);break;
                
                //inmunoserologia
                case '4': $id_tipo_examen = $model_laboratorio->RegistrarExamenInmunoserologia($request->id_laboratorio, $request->id_ipress, $request->idresponsable, $request->idjefelaboratorio, $request->aglutinaciones_febriles, $request->tifico_h, $request->tifico_o, $request->paratifico_a, $request->paratifico_b, $request->brucellas_placa, $request->vdrl_rpr, $request->proteina_c_reactiva, $request->latex_reumatoideo, $request->antiestreptolisina,$request->rosa_bangals,$request->prueba_tubo,$request->mercaptoetanol,$request->hbctotal,$request->havgm,$request->hbcigm,$request->aghbs,$request->hcv,$request->hiv);break;
                
                //microbiologia
                case '5': $id_tipo_examen = $model_laboratorio->RegistrarExamenMicrobiologia($request->id_laboratorio, $request->id_ipress, $request->idresponsable, $request->idjefelaboratorio, $request->muestra1, $request->aspecto1, $request->consistencia, $request->color1, $request->lecucitos1, $request->hematies2, $request->levaduras,$request->parasitos,$request->polimorfonucleares,$request->mononucleares,$request->observaciones1,$request->color2,$request->aspecto2,$request->densidad,$request->muestra2,$request->hematies3,$request->lecucitos2,$request->celulas_epiteliales,$request->cristales,$request->cilindros,$request->germenes,$request->piocitos,$request->albumina2,$request->glucosa2,$request->urobilina,$request->pigmentos_biliares,$request->cuerpos_cetonicos,$request->ac_ascorbico,$request->nitritos,$request->thevenon,$request->observaciones2);break;

                //bioquimica
                case '5': break;
                //bioquimica
                case '6': break;
                //bioquimica
                case '7': break;
                //bioquimica
                case '8': break;
                //bioquimica
                case '9': break;
                //bioquimica
                case '10': break;
                //bioquimica
                case '11': break;            
            }

        }
        
        if ($request->observacion != null) {
            $id_user = Auth::user()->id;
            $res = $model_laboratorio->ConfirmarTomaMuestra($request->id_prestacion, 2, $request->prueba, strtoupper($request->observacion), $id_user, null, null, null);
        }

        if (empty($id_tipo_examen) || $id_tipo_examen = 0) {
                Session::flash('flash_message', 'No se pudo registrar el Examen de Laboratorio, hubo un problema con la conexión de internet de su establecimiento PNP, o ya se encuentra registrado... vuelva a intentarlo nuevamente');
                return redirect('all_laboratorio_prescritos_by_ipress');
        } else {
            //print_r($medicamentos);
            Session::flash('flash_message', 'Se registro con exitoso');
            return redirect('all_laboratorio_prescritos_by_ipress');
        }
    }

    public function modal_historial_laboratorio($dni_beneficiario){
        // $laboratorio_model = new \App\Models\Laboratorio;
        $p[] = $dni_beneficiario;
        $examenes = [];
        //$examenes = $laboratorio_prestaciones->GetExamenLaboratorioByDni($p);
        
        $ws_laboratorio = new \App\Ws\LaboratorioLNS();
        $ordenes_laboratorio_ws = $ws_laboratorio->CallVerOrdenesLabPorDocumento($dni_beneficiario);

        if (isset($ordenes_laboratorio_ws->RespuestaOrdenLab)):
        $ordenes_laboratorio = $ordenes_laboratorio_ws->RespuestaOrdenLab;
        if (!is_array($ordenes_laboratorio)) {
          $ordenes_laboratorio = [$ordenes_laboratorio];
        }

        $array_codigos = [];
        $cantidad = 0;
        foreach ($ordenes_laboratorio as $value):
          if ($cantidad < 10):
            if ($value->orden):
              array_push($array_codigos, $value->orden);
              $cantidad++;
            endif;
          else:
            break;
          endif;
        endforeach;

        $ordenes_lab_info = null;
        $laboratorio_model = new Laboratorio();
        if (count($array_codigos) > 0):
          // $ordenes_lab_info_ws = $ws_laboratorio->ObtenerOrdenes($array_codigos);
          // $laboratorio_model = new Laboratorio();
          $ordenes_lab_info = $laboratorio_model->GetDetallePorCodigoOrden($array_codigos);
        endif;

        foreach ($ordenes_laboratorio as $value) {
          $value->tiene_info = false;
          $value->solicitado = 0;
          $value->atendido = 0;
          $value->pendiente = 0;
          $value->sin_reactivo = 0;
          $value->toma_muestra_primera_obs = "";
          $value->toma_muestra_segunda_obs = "";
          $value->examenes = [];

          if ($ordenes_lab_info && count($ordenes_lab_info) > 0):
            foreach ($ordenes_lab_info as $info):
              if ($value->orden == $info->cod_orden_lab):
                $value->tiene_info = true;
                $value->solicitado = $info->cantidad_total;
                $value->atendido = $info->cantidad_enviado;
                $value->pendiente = $info->cantidad_pendiente;
                $value->sin_reactivo = $info->cantidad_sr;
                $value->toma_muestra_primera_obs = trim($info->observacion_toma_muestra) !== "" ? $info->observacion_toma_muestra : "-";
                $value->toma_muestra_segunda_obs = !is_null($info->observacion_toma_muestra) && trim($info->obs_lastupdate_tomamuestra) !== "" ? $info->obs_lastupdate_tomamuestra : "-";

                $value->examenes = $laboratorio_model->GetExamenesClasificadosPorOrden($info->cod_orden_lab);
                break;
              endif;
            endforeach;
          endif;
        }

        /*foreach ($ordenes_laboratorio as $value) {
            // Estado por defecto
            $value->estado_lab = 0;
            $value->cantExamenConResultado = 0;
            $value->orden_info_detallado = "";

            // Validar contra respuesta API
            if (isset($ordenes_lab_info_ws->CallListarOrdenesConSinValidacionResult) &&
                isset($ordenes_lab_info_ws->CallListarOrdenesConSinValidacionResult->RespuestaOrdenLab)) {
                
                $respuestaOrdenLab = $ordenes_lab_info_ws->CallListarOrdenesConSinValidacionResult->RespuestaOrdenLab;
                  
                // Si no es array (1 fila) se convierte
                if (!is_array($respuestaOrdenLab)) {
                  $respuestaOrdenLab = [$respuestaOrdenLab];
                }

                foreach ($respuestaOrdenLab as $apiItem) {
                    // Verifica coincidencia por orden
                    if (isset($apiItem->orden) && $apiItem->orden == $value->orden) {
                        
                        if (isset($apiItem->codigoMensaje) && $apiItem->codigoMensaje === "00") {
                            
                            if (isset($apiItem->validadoCompleto)) {
                                if ($apiItem->validadoCompleto === "SI") {
                                    $value->estado_lab = 1;
                                } elseif ($apiItem->validadoCompleto === "NO") {
                                    $value->estado_lab = 2;
                                }
                            }
                            
                            if (isset($apiItem->cantExamenConResultado) && isset($apiItem->cantExamenTotal)) {
                              $value->cantExamenConResultado = $apiItem->cantExamenConResultado;
                              $value->orden_info_detallado = $apiItem->cantExamenConResultado . " de " . $apiItem->cantExamenTotal;
                            }
                        }

                        // Una vez encontrada la orden, no es necesario seguir buscando
                        break;
                    }
                }
            }
        }*/
        else:
          $ordenes_laboratorio = null;
        endif;
        /*try {
            //Generar código de orden del examen
            foreach ($examenes as $examen) {
                if (is_null($examen->fecha_toma_muestra)) {
                    $examen->cod_orden_lab = null;
                } else {
                    $fecha = $examen->fecha_toma_muestra;
                    $numero = $examen->secuencial;
                    // Convertir la fecha al formato deseado ("2023/11/16")
                    $fechaObjeto = DateTime::createFromFormat('d/m/Y', $fecha);
                    $fechaFormateada = $fechaObjeto->format('Y/m/d');
                    // Procesamiento de la fecha para obtener "231116"
                    $fechaProcesada = substr(str_replace('/', '', $fechaFormateada), 2, 6);
                    // Convertir el número a una cadena y agregar ceros a la izquierda si es necesario
                    $numeroFormateado = str_pad($numero, 4, '0', STR_PAD_LEFT);
                    // Combinar ambas partes para obtener el resultado final
                    $examen->cod_orden_lab = $fechaProcesada . $numeroFormateado;
                }
            }
        } catch (\Throwable $th) {
            $th->getMessage();
        }*/
        
        return view('frontend.laboratorios.modal_historial_laboratorio',compact('examenes','ordenes_laboratorio'));
    }

    public function modal_historial_laboratorio2($dni_beneficiario) {
        $ws_laboratorio = new \App\Ws\LaboratorioLNS();
        $ordenes_laboratorio = $ws_laboratorio->CallVerOrdenesLabPorDocumento($dni_beneficiario);
        
        return view('frontend.laboratorios.modal_historial_laboratorio2',compact('ordenes_laboratorio'));
    }

    public function modal_historial_laboratorio_paciente($dni_beneficiario){
        $laboratorio_prestaciones = new \App\Models\Laboratorio;
        $examenes = $laboratorio_prestaciones->GetExamenLaboratorioByDni($dni_beneficiario);
        
        $ws_laboratorio = new \App\Ws\LaboratorioLNS();
        $ordenes_laboratorio = $ws_laboratorio->CallVerOrdenesLabPorDocumento($dni_beneficiario);
        return view('frontend.laboratorios.modal_historial_laboratorio_paciente',compact('examenes','ordenes_laboratorio'));
    }
  public function formulario_atencion_laboratorio_lns($id, $id_tipo_atencion) {

    if (!Auth::check()):
        return redirect('/auth/login');
    endif;
    
    $orden_laboratorio = Laboratorio::find($id);
    if (!empty($orden_laboratorio)):

      $asegurado_model = Asegurado::find($orden_laboratorio->id_asegurado);

      /*$client = new SoapClient(env('WI_SALUDPOL_URL') . "/ws/WS_AfiliadoSP_Serv.php?wsdl", array('login' => env('WI_SALUDPOL_USERNAME'), 'password' => env('WI_SALUDPOL_PASSWORD')));
      $beneficiario = $client->getAseguradoValidate($asegurado_model->id_tipo_doc_ident, $asegurado_model->nro_doc_ident);

      $esta_asegurado = false;
      if (is_object($beneficiario)):
        $esta_asegurado = true;
      endif;*/

      $esta_asegurado = true;

      if ($esta_asegurado):

        Log::info("Laboratorio Orden encontrada, flag_toma_muestra: " . $orden_laboratorio->flag_toma_muestra);
      if ($orden_laboratorio->flag_toma_muestra == 0):

        Log::info("Laboratorio flag_toma_muestra en 0");
        $id_user = Auth::user()->id;
        $tipo_user = Auth::user()->tipo_registro;

        if(Auth::user()->tipo_registro || Auth::user()->flagestablecimientos == 1):

          //-----------------------------------------------        
          $modelFarmacia = new Farmacia();
          $establecimiento = $modelFarmacia->GetIpressByIduser($id_user);
          $id_ipress = $establecimiento->id;
          $nivel = $establecimiento->nivel;

          /************************************************************************************************************/
          $model_prestacion = new \App\Models\Prestaciones;
          $model_laboratorio = new \App\Models\Laboratorio;        
          $model_referencia = new \App\Models\Referencia;        

          $laboratorio = $model_laboratorio->GetLaboratorioById($id);
          $jefe_laboratorio = $model_laboratorio->AllMedicosByIpress($id_ipress);
          $user = $model_laboratorio->AllResponsableByIpress($id_ipress);

          $id_prestacion=$laboratorio->id_prestacion;
          switch ($id_tipo_atencion) {
            case '1':
                $prestacion = $model_prestacion->GetPrestacionById($id_prestacion); //Ambulatorio
                $referencia = $prestacion->referido;
                /*if ($referencia == "SI") {
                    $referencia_datos = $model_referencia->obtenerDatosDeRefereanciaParaAtencionLabPorId($prestacion->id_referencia);
                    $diagnostico_referencia = $model_prestacion->GetDiagnosticoByIdPrestacion($referencia_datos->id_prestacion_referencia);
                }*/
                //validar codigo sited por día
                $codigo_siteds = $model_laboratorio->obtenerCodigoSitedsSiExisteOrdenLabDelDia($prestacion->id_asegurado);
                break;
            
            case '2':
                $p = [$laboratorio->id_prestacion];
                $prestacion_emergencia = $model_prestacion->GetPrestacionEmergenciaById($p); //emergencia
                $referencia = $prestacion_emergencia->id_motivo_ingreso;
                // if ($prestacion_emergencia->id_motivo_ingreso == 4) {
                //     $referencia_datos = $model_referencia->obtenerDatosDeRefereanciaParaAtencionLabPorId($prestacion_emergencia->id_referencia);
                //     $diagnostico_referencia = $model_prestacion->GetDiagnosticoByIdPrestacion($referencia_datos->id_prestacion_referencia);
                // }
                //validar codigo sited por día
                $codigo_siteds = $model_laboratorio->obtenerCodigoSitedsSiExisteOrdenLabDelDia($prestacion_emergencia->id_asegurado);
                break;
            
            case '3':
                $prestacion = $model_prestacion->GetPrestacionByIdLaboratorioN3($id_prestacion);
                $referencia = 'FALTA';//$prestacion->id_motivo_ingreso;
                //if ($prestacion->id_motivo_ingreso == 4) {
                    //En emergencia no usa el módulo de referencia
                    //$referencia_datos = $model_referencia->obtenerDatosDeRefereanciaParaAtencionLabPorId($prestacion->id_referencia);
                    //$diagnostico_referencia = $model_prestacion->GetDiagnosticoByIdPrestacion($referencia_datos->id_prestacion_referencia);
                //}
                $model_emergencia = new \App\Models\Emergencia;
                $hospitalizacion = \App\Models\Hospitalizacione::where('id', $prestacion->id_ext)->first();
                $camas = $model_emergencia->GetObtenerCama($hospitalizacion->id_cama_estado);
                break;
          }

          $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
          $total_examenes_clinicos1 = count($diagnostico);
          //$proclaboratorio = $model_laboratorio->GetAllProcedimientosByIdLaboratorio($id_prestacion,$id);
          $proclaboratorio = $model_prestacion->GetPrestacionLaboratorio($id_prestacion, $id);

          $total_examenes_clinicos = count($proclaboratorio);
          $contadorRegistrosConDatos = 0;

          // Recorre los resultados y cuenta los registros con datos en id_evaluacion
          foreach ($diagnostico as $registro) {
              if ($registro->id_evaluacion !== null) {
                  $contadorRegistrosConDatos++;
              }
          }

          $idjefelaboratorio = 0;
          $idresponsable = 0;
          if(count($proclaboratorio)>0):
            switch ($id_tipo_atencion) {
                case '1':
                    return view('frontend.laboratorios.form_laboratorio_atencion_ambulatorio_lns', 
                    compact('total_examenes_clinicos','contadorRegistrosConDatos','referencia','prestacion',
                    'diagnostico','laboratorio','proclaboratorio','user','jefe_laboratorio','idjefelaboratorio','idresponsable','id_ipress'
                    ,'id_user','nivel','id_tipo_atencion','codigo_siteds'/*,'referencia_datos','diagnostico_referencia'*/));
                    break;
                case '2':
                    return view('frontend.laboratorios.form_laboratorio_atencion_emergencia_lns', compact('prestacion_emergencia','total_examenes_clinicos','contadorRegistrosConDatos','referencia','diagnostico','laboratorio','proclaboratorio','user','jefe_laboratorio','idjefelaboratorio','idresponsable','id_ipress','id_user','nivel','id_tipo_atencion','codigo_siteds'/*,'referencia_datos','diagnostico_referencia'*/));
                    break;
                case '3':
                    return view('frontend.laboratorios.form_laboratorio_atencion_hospitalizacion_lns', compact('total_examenes_clinicos','contadorRegistrosConDatos','referencia','prestacion','diagnostico','laboratorio','proclaboratorio','user','jefe_laboratorio','idjefelaboratorio','idresponsable','id_ipress','id_user','nivel','id_tipo_atencion','codigo_siteds','referencia_datos','diagnostico_referencia','camas'));
                    break;
            }
          else:
              return redirect('all_laboratorio_prescritos_by_ipress');
          endif;
        else:
            return redirect('all_laboratorio_prescritos_by_ipress');
        endif;
      else:
        Log::info("Laboratorio Mensaje raro");
        Session::flash('flash_warning', 'Ya se realizó la Toma de Muestra de la Orden de Laboratorio.');
        return redirect('all_laboratorio_prescritos_by_ipress');
      endif;

      else:
        return redirect('/all_laboratorio_prescritos_by_ipress')->withInput()->withErrors(array('El paciente ya no está asegurado, por consiguiente no tiene derecho de atención, realizar los trámites correspondiente con SALUDPOL.'));
      endif;
    else:
      Session::flash('flash_warning', 'La Orden de Laboratorio no existe.');
      return redirect('all_laboratorio_prescritos_by_ipress');
    endif;
  }

  public function registrar_examen_laboratorio_lns(Request $request) {

    if (!Auth::check()):
        return redirect('/auth/login');
    endif;

    $registro_validado = false;
    $mensaje_adicional = "";
    DB::beginTransaction();
    try {

    if ($request->prueba != null) {
    
        $id_user = Auth::user()->id;
        $ws_laboratorio = new \App\Ws\LaboratorioLNS();
        $obj_laboratorio = new \App\Models\Laboratorio();
        Log::info("Laboratorio 0: id: " . $request->id_laboratorio);
        $response = $obj_laboratorio->RegistrarPrestacionProcedimientosProveedor($request->id_prestacion, $request->id_laboratorio, $request->pendiente, $request->prueba);
        if ($response):
        $secuencial = $ws_laboratorio->CallVerSecuencialLaboratorio();

        if($secuencial->codigoMensaje=='00'):
            Log::info("Laboratorio 1: genero secuencial : secuencial: " . $secuencial->descripcionMensaje);
            $res = $obj_laboratorio->ConfirmarTomaMuestra($request->id_prestacion, 2, $request->prueba, strtoupper($request->observacion), $id_user, $secuencial->descripcionMensaje,$request->id_laboratorio, $request->cod_siteds_susalud, $request->pendiente);
            if ($res == 'Y') {
                Log::info("Laboratorio 2: confirmo toma de muestra");
                if (Auth::user()->dni == "48839708") {
                  $datos = $obj_laboratorio->GetDataParaLIS($request->id_prestacion, $request->id_laboratorio, $request->id_tipo_atencion, $request->id_evaluacion);
                  Log::info("Laboratorio 3: se obtuvo la data para enviar , cantidad de datos : " . count($datos));
                  Log::info("Laboratorio 3.1: " . json_encode($datos));
                } else {
                if (env('CONEXION_WS_LAB') == "true") {
                    //Obtener para el LIS
                    // dd($request->id_prestacion, $request->id_laboratorio, $request->id_tipo_atencion, $request->id_evaluacion);
                    $datos = $obj_laboratorio->GetDataParaLIS($request->id_prestacion, $request->id_laboratorio, $request->id_tipo_atencion, $request->id_evaluacion);
                    Log::info("Laboratorio 3: se obtuvo la data para enviar , cantidad de datos : " . count($datos));
                    Log::info("Laboratorio 3.1: " . json_encode($datos));
                    if(count($datos) == 0){
                      Log::error("No hay datos para enviar.");
                      throw new \Exception("No hay datos para enviar.");
                      // Session::flash('flash_message', 'No se pudo registrar los exámenes de Laboratorio.');
                      // return redirect('/formulario_atencion_laboratorio_lns/'.$request->id_laboratorio.'/'.$request->id_tipo_atencion);
                    }
                    // dd($request->id_prestacion);
                    /*$obj_laboratorio->updateEstadoMigrate($request->id_prestacion, 2, $request->prueba, strtoupper($request->observacion), $id_user, $secuencial->descripcionMensaje,$request->id_laboratorio, $request->cod_siteds_susalud,$request->pendiente);*/
                    $lista = array();
                    foreach ($datos as $data) {
                        $obj = new \App\Models\InterLabHcentral();

                        $obj->id = 0;
                        $obj->idOrigen        = $data->id_tipo_atencion;
                        $obj->fechaMuestra    = $data->fecha_atencion;
                        $obj->idProcedencia   = $data->id_establecimiento;  // DEBE ENCAJAR CON LA BD "HNPNPLNS" DE POSTGRES, ESTA CONFIGURADO EN LABCORE
                        $obj->idDepartamento  = 0; // NO HAY ID DEPARTAMENTO, VER SI AFECTA AL LABCORE
                        $obj->idServicio      = (intval($data->id_tipo_atencion) === 2 ? $data->id_servicio : $data->id_consultorio); // id_tipo_atencion 2 : Hospitalizacion para Hospital LNS
                        $obj->dniMedico       = $data->ndoc_medico;
                        $obj->nombreMedico    = $data->nombre_medico;
                        $obj->dniProfLab      = "";
                        $obj->nombreProfLab   = "";
                        $obj->historiaClinica = $data->nro_historia;

                        $obj->dniPaciente     = $data->nro_doc_ident;
                        $obj->nombrePaciente  = $data->nombre_paciente;
                        $obj->genero          = $data->sexo_paciente;
                        $obj->fechaNac        = $data->fnac_paciente;
                        $obj->parentesco      = $data->parentesco_paciente;
                        $obj->grado           = $data->grado == null ? "":$data->grado;
                        $obj->situacionTitular = $data->paciente_situacion  == null ? "":$data->paciente_situacion; 
                        $obj->condicionUpss   = $data->upss;
                        $obj->codigoDiag      = $data->codigo_cie;
                        $obj->diagnostico     = $data->descripcion_cie  == null ? "":$data->descripcion_cie;

                        $obj->tipoDiag        = $data->tipo_diagnostico;
                        $obj->idDetalle       = $data->id_prestacion;
                        $obj->idServicioAnalisis = 0;
                        $obj->codigoProced    = $data->cpms_personalizado;
                        $obj->procedimiento   = $data->desc_procedimiento ==null ? "":$data->desc_procedimiento;
                        $obj->estadoLab       = 0;
                        $obj->nombrePc        = "";
                        $obj->fechaRegistro   = $data->fecha_registro_orden;  // HECHO - FECHA REGISTRO DEL RUA, DEBERIA OBTNERLO DE LA BD, VERIFICAR DE QUE CAMPO
                        $obj->observacion     = $data->observacion; // HECHO - DEBE OBTENERLO DE BD, AL MOMENTO DE REGISTRAR EL RUA, SE REGISTRA LA OBSERVACION
                        $obj->secuencia       = intval($secuencial->descripcionMensaje);//$data->secuencial; HECHO - DEBE OBTENERLO DE BD, AL MOMENTO DE REGISTRAR EL RUA

                        $obj->nombreUsuario   = $data->nombre_usuario; // USUARIO QUE REGISTRA EL RUA PARA REALIZAR LA PRUEBA
                        $obj->fechaHoraCrea = "";
                        $obj->apepaternoPaciente = $data->apepaterno_paciente;
                        $obj->apematernoPaciente = $data->apematerno_paciente;
                        $obj->prenombresPaciente = $data->prenombres_paciente;
                        $obj->codOrden = "";
                        $obj->codigo_proveedor = $data->codigo_proveedor;
                        $obj->codigo_proced_alterno = $data->cod_procedimiento;
                        $obj->validado = "";
                        $obj->estadoLabRoche = 0;
                        $obj->estadoProveedor = 0;
                        $obj->origenData = $data->servicio_area;
                        $obj->codigoMensaje = "";
                        $obj->descripcionMensaje = "";

                        $lista[] = $obj;
                    }
                    // dd($lista);
                    if(!isset($lista)){
                      Log::error("La lista no existe.");
                      throw new \Exception("La lista no existe.");
                      // Session::flash('flash_message', 'No se pudo registrar los exámenes de Laboratorio.');
                      // return redirect('/formulario_atencion_laboratorio_lns/'.$request->id_laboratorio.'/'.$request->id_tipo_atencion);
                    }
                    
                    $respuesta = $ws_laboratorio->InsertarOrdenLaboratorioNuevo($lista);
                    
                    // Validar que la respuesta sea un objeto válido antes de acceder a sus propiedades
                    if (is_string($respuesta)) {
                        // Si la respuesta es un string, significa que hubo un error en el web service
                        Log::error("Error en web service LNS: " . $respuesta);
                        throw new \Exception("Error en el servicio web LNS: " . $respuesta);
                    }
                    
                    if ($respuesta->codigoMensaje == '00') {
                        Log::info("Laboratorio 5: inserto la data a la tabla intermedia");
                        $response = $obj_laboratorio->updateEstadoMigrate(2, $request->prueba, $request->id_laboratorio, $request->pendiente);
                        if (!$response) {
                          // Session::flash('flash_message', 'Se registró con exito, se instaron '.$respuesta->descripcionMensaje.' pruebas');
                          // Session::flash('flash_message', 'Se registró con exito, se instaron '.count($datos).' pruebas');
                          // return redirect('all_laboratorio_prescritos_by_ipress');
                        // } else {
                          Log::error("No se ha podido actualizar el flag de 'migrado'. Intente nuevamente.");
                          throw new \Exception("No se ha podido actualizar el flag de 'migrado'. Intente nuevamente.");
                          // Session::flash('flash_warning', 'No se ha podido actualizar el flag de "migrado". Intente nuevamente.');
                          // return redirect('/formulario_atencion_laboratorio_lns/'.$request->id_laboratorio.'/'.$request->id_tipo_atencion);
                        } else {
                          $mensaje_adicional = '<br>Se envió ' . count($lista) . ' exámenes a los proveedores.';
                          Log::info("Se realizó la actualización a Migrado de todos los examenes.");
                        }
                    
                    } else {
                        Log::info("Laboratorio 6: no se pudo registrar en la tabla intermedia");
                        throw new \Exception("No se pudo registrar los exámenes de Laboratorio. Código: ".$respuesta->codigoMensaje."/ Descripción: ".$respuesta->descripcionMensaje);
                        // Session::flash('flash_message', 'No se pudo registrar los exámenes de Laboratorio. Código: '.$respuesta->codigoMensaje.'/ Descripción: '.$respuesta->descripcionMensaje);
                        // Session::flash('flash_message', 'No se pudo registrar los exámenes de Laboratorio. Código: / Descripción:');
                        // return redirect('/formulario_atencion_laboratorio_lns/'.$request->id_laboratorio.'/'.$request->id_tipo_atencion);
                    }

                } /*else {
                    Session::flash('flash_message', 'Se registró con exito');
                    return redirect('all_laboratorio_prescritos_by_ipress');
                }*/
                }
            } elseif ($res == 'P') {
              $laboratorio_cod_orden = Laboratorio::find($request->id_laboratorio);
              $mensaje_adicional .= '<br>Todos los exámenes eran Pendientes para la orden <b>' . $laboratorio_cod_orden->cod_orden_lab . "</b> y no se envió a los proveedores.";
              Log::info("No se realizó la actualización a Migrado porque todos los examenes de la orden " . $laboratorio_cod_orden->cod_orden_lab . " eran pendientes.");
            } else {
                Log::error("Laboratorio 7: no se registro los datos de toma de muestra");
                throw new \Exception("Ha ocurrido un error al registrar los datos de Toma de Muestra.");
                // Session::flash('flash_warning', 'Ha ocurrido un error al registrar los datos de Toma de Muestra.');
                // return redirect('/formulario_atencion_laboratorio_lns/'.$request->id_laboratorio.'/'.$request->id_tipo_atencion);
            }
        else:
            Log::error("Ha ocurrido un error al generar el Secuencial de la Orden.<br><br>Código: ".$secuencial->codigoMensaje."/ Descripción: ".$secuencial->descripcionMensaje);
            throw new \Exception("Ha ocurrido un error al generar el Secuencial de la Orden.<br><br>Código: ".$secuencial->codigoMensaje."/ Descripción: ".$secuencial->descripcionMensaje);
            // Session::flash('flash_warning', 'Ha ocurrido un error al generar el Secuencial de la Orden.<br><br>Código: '.$secuencial->codigoMensaje.'/ Descripción: '.$secuencial->descripcionMensaje);
            // return redirect('/formulario_atencion_laboratorio_lns/'.$request->id_laboratorio.'/'.$request->id_tipo_atencion);
        endif;

        else:
          Log::error("No se ha podido registrar los codigo de proveedor.");
          throw new \Exception("No se ha podido registrar los codigo de proveedor.");
          // Session::flash('flash_warning', 'No se ha podido registrar los codigo de proveedor.');
          // return redirect('/formulario_atencion_laboratorio_lns/'.$request->id_laboratorio.'/'.$request->id_tipo_atencion);
        endif;
    } else {
      $id_user = Auth::user()->id;
      $obj_laboratorio = new \App\Models\Laboratorio();

      $res = $obj_laboratorio->ConfirmarTomaMuestraOrdenSinReactivo(strtoupper($request->observacion), $id_user, $request->id_laboratorio, $request->cod_siteds_susalud);
      
      if ($res == 'Y'):
        $mensaje_adicional = "<br>Se registró con éxito toda la orden sin reactivo.";
        // Session::flash('flash_message', 'Se registró con exito toda la orden sin reactivo.');
        // return redirect('all_laboratorio_prescritos_by_ipress');
      else:
        Log::error("Ha ocurrido un error al registrar los datos de Toma de Muestra para la Orden sin reactivo.");
        throw new \Exception("Ha ocurrido un error al registrar los datos de Toma de Muestra para la Orden sin reactivo.");
        // Session::flash('flash_warning', 'Ha ocurrido un error al registrar los datos de Toma de Muestra para la Orden sin reactivo.');
        // return redirect('/formulario_atencion_laboratorio_lns/'.$request->id_laboratorio.'/'.$request->id_tipo_atencion);
      endif;
        // Session::flash('flash_warning', 'Para registrar, debe seleccionar al menos una prueba de Laboratorio.');
        // return redirect('/formulario_atencion_laboratorio_lns/'.$request->id_laboratorio.'/'.$request->id_tipo_atencion);
    }

    DB::commit();
    $registro_validado = true;

    } catch (\Exception $e) {
      DB::rollback();
      $mensaje_adicional = $e->getMessage();
      Log::error("Laboratorio: " . 
        "\n\nmensaje: " . $e->getMessage()
      );
    } catch (\Throwable $th) {
      DB::rollback();
      $mensaje_adicional = $th->getMessage();
      Log::critical("Laboratorio: " . 
        "\n\nmensaje: " . $th->getMessage()
      );
    }

    if ($registro_validado):
      Session::flash('flash_message', 'Se registró la Orden de Laboratorio con éxito.' . $mensaje_adicional);
      return redirect('/all_laboratorio_prescritos_by_ipress');
    else:
      Session::flash('flash_warning', '<strong>Error:</strong> No se pudo registrar la Orden de Laboratorio, vuelva a intentarlo nuevamente.<br><br>' . $mensaje_adicional);
      return redirect('/formulario_atencion_laboratorio_lns/'.$request->id_laboratorio.'/'.$request->id_tipo_atencion);
    endif;

  }

    public function formulario_edicion_atencion_laboratorio_lns($id, $id_tipo_atencion) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $id_user = Auth::user()->id;
        $tipo_user = Auth::user()->tipo_registro;

        if(Auth::user()->tipo_registro || Auth::user()->flagestablecimientos == 1):

            //-----------------------------------------------        
            $modelFarmacia = new Farmacia();
            $establecimiento = $modelFarmacia->GetIpressByIduser($id_user);
            $id_ipress = $establecimiento->id;
            /************************************************************************************************************/
            $model_prestacion = new \App\Models\Prestaciones;
            $model_laboratorio = new \App\Models\Laboratorio;
            $model_referencia = new \App\Models\Referencia;        

            $laboratorio = $model_laboratorio->GetLaboratorioById($id);
            $jefe_laboratorio = $model_laboratorio->AllMedicosByIpress($id_ipress);
            $user = $model_laboratorio->AllResponsableByIpress($id_ipress);

            $id_prestacion=$laboratorio->id_prestacion;

            switch ($id_tipo_atencion) {
                case '1':
                    $prestacion = $model_prestacion->GetPrestacionById($id_prestacion); //Ambulatorio
                    $referencia = $prestacion->referido;
                    /*if ($referencia == "SI") {
                        $referencia_datos = $model_referencia->obtenerDatosDeRefereanciaParaAtencionLabPorId($prestacion->id_referencia);
                        $diagnostico_referencia = $model_prestacion->GetDiagnosticoByIdPrestacion($referencia_datos->id_prestacion_referencia);
                    }*/
                    break;
                case '2':
                    $p = [$laboratorio->id_prestacion];
                    $prestacion_emergencia = $model_prestacion->GetPrestacionEmergenciaById($p); //emergencia
                    $referencia = $prestacion_emergencia->id_motivo_ingreso;
                    // if ($prestacion_emergencia->id_motivo_ingreso == 4) {
                    //     $referencia_datos = $model_referencia->obtenerDatosDeRefereanciaParaAtencionLabPorId($prestacion_emergencia->id_referencia);
                    //     $diagnostico_referencia = $model_prestacion->GetDiagnosticoByIdPrestacion($referencia_datos->id_prestacion_referencia);
                    // }
                    break;
                case '3':
                    $prestacion = $model_prestacion->GetPrestacionByIdHospitalizacion($id_prestacion);
                    $referencia = 'FALTA';//$prestacion->id_motivo_ingreso;
                    //if ($prestacion->id_motivo_ingreso == 4) {
                        //En emergencia no usa el módulo de referencia
                        //$referencia_datos = $model_referencia->obtenerDatosDeRefereanciaParaAtencionLabPorId($prestacion->id_referencia);
                        //$diagnostico_referencia = $model_prestacion->GetDiagnosticoByIdPrestacion($referencia_datos->id_prestacion_referencia);
                    //}
                    $model_emergencia = new \App\Models\Emergencia;
                    $hospitalizacion = \App\Models\Hospitalizacione::where('id', $prestacion->id_ext)->first();
                    $camas = $model_emergencia->GetObtenerCama($hospitalizacion->id_cama_estado);
                    break;
            }
            $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
            $proclaboratorio = $model_laboratorio->GetAllProcedimientosByIdLaboratorio($id_prestacion,$id);

            $total_diagnosticos = 0;
            foreach ($diagnostico as $registro) {
                if ($registro->id_evaluacion !== null) {
                    $total_diagnosticos++;
                }
            }
           // dd($total_diagnosticos);

            $total_examenes_clinicos = count($proclaboratorio);

            $idjefelaboratorio = 0;
            $idresponsable = 0;
            
            if(count($proclaboratorio)>0):
                switch ($id_tipo_atencion) {
                    case '1':
                        return view('frontend.laboratorios.form_edicion_ambulatorio_lns', compact('prestacion','diagnostico','laboratorio','proclaboratorio','referencia','total_examenes_clinicos','total_diagnosticos','user','jefe_laboratorio','idjefelaboratorio','idresponsable','id_ipress','id_user','id_tipo_atencion'));   
                        break;
                    case '2':
                        return view('frontend.laboratorios.form_edicion_emergencia_lns', compact('prestacion_emergencia','prestacion','diagnostico','laboratorio','proclaboratorio','referencia','total_examenes_clinicos','total_diagnosticos','user','jefe_laboratorio','idjefelaboratorio','idresponsable','id_ipress','id_user','id_tipo_atencion'));   
                        break;
                    case '3':
                        return view('frontend.laboratorios.form_edicion_hospitalizacion_lns', compact('prestacion','diagnostico','laboratorio','proclaboratorio','referencia','total_examenes_clinicos','total_diagnosticos','user','jefe_laboratorio','idjefelaboratorio','idresponsable','id_ipress','id_user','id_tipo_atencion','camas'));   
                        break;
                }
            else:
                return redirect('all_laboratorio_prescritos_by_ipress');
            endif;
        else:
            return redirect('all_laboratorio_prescritos_by_ipress');
        endif;
        
    }

    public function editar_examen_laboratorio_lns(Request $request) {

      if (!Auth::check()):
          return redirect('/auth/login');
      endif;

      $registro_validado = false;
      $mensaje_adicional = "";
      
      DB::beginTransaction();
      try {
        if ($request->prueba != null) {
          
          $id_user = Auth::user()->id;
          $ws_laboratorio = new \App\Ws\LaboratorioLNS();
          $obj_laboratorio = new \App\Models\Laboratorio();
          $id_prestacion      = $request->id_prestacion;
          $id_laboratorio     = $request->id_laboratorio;
          // $array_estado       = $request->estado;
          $array_pendiente      = $request->pendiente;
          //$array_idcptoriginal = $request->idcpt_original;
          $array_idrua = $request->idRua;
          $observacion = strtoupper($request->observacion_modificacion);

          $response = $obj_laboratorio->RegistrarPrestacionProcedimientosProveedor2($request->id_prestacion_procedimiento, $array_pendiente, $array_idrua, $id_laboratorio, $observacion);
          if ($response) {

              if (env('CONEXION_WS_LAB') == "true") {
                //Obtener para el LIS
                $datos = $obj_laboratorio->GetDataModificadaParaLIS($request->id_prestacion, $request->id_laboratorio, $request->id_tipo_atencion, $request->id_evaluacion);
                if (Auth::user()->dni == "48839708") {
                  Log::info("Laboratorio: se obtuvo la data para enviar , cantidad de datos : " . count($datos));
                  Log::info("Laboratorio: " . json_encode($datos));
                }

                if (count($datos) > 0) {
                  $lista = [];
                  foreach ($datos as $data) {
                      $obj = new \App\Models\InterLabHcentral();

                      $obj->id = 0;
                      $obj->idOrigen        = $data->id_tipo_atencion;
                      $obj->fechaMuestra    = $data->fecha_atencion;
                      $obj->idProcedencia   = $data->id_establecimiento;  // DEBE ENCAJAR CON LA BD "HNPNPLNS" DE POSTGRES, ESTA CONFIGURADO EN LABCORE
                      $obj->idDepartamento  = 0; // NO HAY ID DEPARTAMENTO, VER SI AFECTA AL LABCORE
                      $obj->idServicio      = (intval($data->id_tipo_atencion) === 2 ? $data->id_servicio : $data->id_consultorio); // id_tipo_atencion 2 : Hospitalizacion para Hospital LNS
                      $obj->dniMedico       = $data->ndoc_medico;
                      $obj->nombreMedico    = $data->nombre_medico;
                      $obj->dniProfLab      = "";
                      $obj->nombreProfLab   = "";
                      $obj->historiaClinica = $data->nro_historia;

                      $obj->dniPaciente     = $data->nro_doc_ident;
                      $obj->nombrePaciente  = $data->nombre_paciente;
                      $obj->genero          = $data->sexo_paciente;
                      $obj->fechaNac        = $data->fnac_paciente;
                      $obj->parentesco      = $data->parentesco_paciente;
                      $obj->grado           = $data->grado == null ? "" : $data->grado;
                      $obj->situacionTitular = $data->paciente_situacion == null ? "" : $data->paciente_situacion; 
                      $obj->condicionUpss   = $data->upss;
                      $obj->codigoDiag      = $data->codigo_cie;
                      $obj->diagnostico     = $data->descripcion_cie  == null ? "" : $data->descripcion_cie;

                      $obj->tipoDiag        = $data->tipo_diagnostico;
                      $obj->idDetalle       = $data->id_prestacion;
                      $obj->idServicioAnalisis = 0;
                      $obj->codigoProced    = $data->cpms_personalizado;
                      $obj->procedimiento   = $data->desc_procedimiento == null ? "" : $data->desc_procedimiento;
                      $obj->estadoLab       = $data->estado_lab;
                      $obj->nombrePc        = '';
                      $obj->fechaRegistro   = $data->fecha_registro_orden;  // HECHO - FECHA REGISTRO DEL RUA, DEBERIA OBTNERLO DE LA BD, VERIFICAR DE QUE CAMPO
                      $obj->observacion     = $data->observacion; // HECHO - DEBE OBTENERLO DE BD, AL MOMENTO DE REGISTRAR EL RUA, SE REGISTRA LA OBSERVACION
                      $obj->secuencia       = $data->secuencial;   // HECHO - DEBE OBTENERLO DE BD, AL MOMENTO DE REGISTRAR EL RUA

                      $obj->nombreUsuario   = $data->nombre_usuario; // USUARIO QUE REGISTRA EL RUA PARA REALIZAR LA PRUEBA
                      $obj->fechaHoraCrea = "";
                      $obj->apepaternoPaciente = $data->apepaterno_paciente;
                      $obj->apematernoPaciente = $data->apematerno_paciente;
                      $obj->prenombresPaciente = $data->prenombres_paciente;
                      $obj->codOrden = "";
                      $obj->codigo_proveedor = $data->codigo_proveedor;
                      $obj->codigo_proced_alterno = $data->cod_procedimiento;
                      $obj->validado = "";
                      $obj->estadoLabRoche = 0;
                      $obj->estadoProveedor = 0;
                      $obj->origenData = $data->servicio_area;
                      $obj->codigoMensaje = "";
                      $obj->descripcionMensaje = "";

                      $lista[] = $obj;
                  }
                  $respuesta = $ws_laboratorio->InsertarOrdenLaboratorioNuevo($lista);

                  if (isset($respuesta->codigoMensaje)) {

                    switch($respuesta->codigoMensaje) { //$respuesta->codigoMensaje
                      case "00":
                        $response = $obj_laboratorio->updateEstadoMigrate2($request->id_prestacion_procedimiento, $array_pendiente);
                        if (!$response) {
                          Log::error("No se ha podido actualizar el estado de los Examenes a Migrado.");
                          throw new \Exception("No se ha podido actualizar el estado de los Examenes a Migrado.");
                        } else {
                          $mensaje_adicional = '<br>Se envió ' . count($lista) . ' exámenes a los proveedores.';
                          Log::info("Se realizó la actualización a Migrado de todos los examenes.");
                        }
                        break;
                      default:
                        throw new \Exception('<b>Código:</b> '.$respuesta->codigoMensaje.'/ Descripción: '.$respuesta->descripcionMensaje);
                    }

                  } else {
                    Log::error("No se ha podido enviar los exámenes a los proveedores.");
                    throw new \Exception("No se ha podido enviar los exámenes a los proveedores.");
                  }

                } else {
                  Log::info("La data obtenida para enviar a Tabla Intermedia esta vacia");
                }

              } else {
                throw new \Exception("No se encuentra habilitado la variable de entorno CONEXION_WS_LAB.");
              }

          } else {
            throw new \Exception("No se ha podido registrar los datos de la Toma de Muestra.");
          }
        } else {
          throw new \Exception("Para registrar, debe seleccionar al menos una prueba de Laboratorio.");
        }

        DB::commit();
        $registro_validado = true;

      } catch (\Exception $e) {
          DB::rollback();
          Log::error("Laboratorio: " . 
              "\n\nmensaje: " . $e->getMessage()
          );
      } catch (\Throwable $th) {
          DB::rollback();
          Log::critical("Laboratorio: " . 
              "\n\nmensaje: " . $th->getMessage()
          );
      }
      if ($registro_validado):
        Session::flash('flash_message', 'Se modificó la Orden de Laboratorio con éxito.' . $mensaje_adicional);
        return redirect('/all_laboratorio_prescritos_by_ipress');
      else:
        Session::flash('flash_warning', '<strong>Error:</strong> No se pudo modificar la Orden de Laboratorio, vuelva a intentarlo nuevamente.<br><br>' . $e->getMessage());
        return redirect('/formulario_edicion_atencion_laboratorio_lns/'.$request->id_laboratorio.'/'.$request->id_tipo_atencion);
      endif;
    }

    public function modal_laboratorio_paciente($id_prestacion ){
        $model_prestacion = new \App\Models\Prestaciones;
        //$laboratorios = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 2);
        $laboratorios = $model_prestacion->GetProcedimientosByIdPrestacionLab($id_prestacion);
        return view('frontend.epicrisis.modal_laboratorios',compact('laboratorios'));
    }

    public function all_rua()
    {
        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;

        $ruta = Route::getCurrentRoute()->getPath();
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "")
          return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

        $modelfarma = new Farmacia;
        $id_farmacia = Auth::user()->id_farmacia;
        $nivel_ipress = $modelfarma->ObtenerNivelFarmacia($id_farmacia);
        $nivel =$nivel_ipress->nivel;

        if(Auth::user()->tipo_registro==5 || Auth::user()->flagestablecimientos==1):
            //return view('frontend.laboratorios.distribucion_rua', ['permiso' => $permiso, 'nivel'=> $nivel]);
            return view('frontend.laboratorios.distribucion_rua_v2', ['permiso' => $permiso, 'nivel'=> $nivel]);
        else:
            Session::flash('flash_message', 'Debe tener activado la opción de digitador de laboratorio en su perfil.');
            return redirect('/dashboard');
        endif;
    }

    public function add_rua_nivel($nivel,$tipo,$id_categoria)
    {
        if (!Auth::check()) :
          return redirect('/auth/login');
        endif;

        switch ($nivel) {
            case 1: $descripcion_nivel='NIVEL I'; break;
            case 2: $descripcion_nivel='NIVEL II'; break;
            case 3: $descripcion_nivel='NIVEL III'; break;
        }

        switch ($tipo) {
            case 1: $descripcion_tipo='AMBULATORIO'; break;
            case 2: $descripcion_tipo='EMERGENCIA'; break;
            case 3: $descripcion_tipo='HOSPITALIZACION'; break;
        }

        $modelfarma = new Farmacia();
        $id_user = Auth::user()->id;

        $model_procedimiento = new Procedimiento;
        $listar_procedimientos_laboratorios = $model_procedimiento->getProcedimientosLab();

        $model_laboratorio = new Laboratorio;
        $listar_examenes = $model_laboratorio->getRua($nivel, $tipo, $id_categoria);
        $categoria = $model_laboratorio->getNombreCategoriasRua($id_categoria);

        //return view('frontend.laboratorios.registrar_rua_nivel', compact('listar_procedimientos_laboratorios','listar_examenes','tipo', 'descripcion_nivel', 'descripcion_tipo','nivel','categoria','id_categoria'));
        return view('frontend.laboratorios.registrar_rua_nivel_v2', compact('listar_procedimientos_laboratorios','listar_examenes','tipo', 'descripcion_nivel', 'descripcion_tipo','nivel','categoria','id_categoria'));

    }

    public function agregar_rua_nivel_tipo_atencion(Request $request)
    {
        if (!Auth::check()) :
          return redirect('/auth/login');
        endif;

        $model_laboratorio = new \App\Models\Laboratorio;
        $id_user = Auth::user()->id;

        $id_categoria_rua_nivel = intval($request->id_consultorio) == 26 ? $request->id_categoria_rua_nivel : 0;

        $existe = $model_laboratorio->ExisteRuaAtenconNivel($request->id_procedimiento, $request->id_tipo_atencion, $request->id_nivel,$request->codigo_personalizado,$request->id_consultorio,$request->id_categoria, $id_categoria_rua_nivel);

        if (count($existe)>0) {
          return redirect('/add_rua_nivel/' . $request->id_nivel . '/' . $request->id_tipo_atencion. '/' . $request->id_categoria . '/' . $request->id_consultorio . '/' . $request->id_categoria_rua_nivel)
            ->withInput()
            ->withErrors(array('No se ha podido registrar el examen de laboratorio, ya existe en este nivel de atención'));
        } else {
          $model_laboratorio->RegistrarRuaNivelAtencion(
            $request->id_procedimiento,
            $request->id_tipo_atencion, 
            $request->id_nivel,
            $request->id_categoria, 
            $request->descripcion_corta, 
            $id_user,
            $request->id_consultorio, 
            $request->id_proveedor, 
            $request->codigo_personalizado,
            $request->id_categoria_rua_nivel
          );
          Session::flash('flash_message', 'Se agregó correctamente el exámen de laboratorio a este nivel de atención');
          return redirect('/add_rua_nivel/' . $request->id_nivel . '/' . $request->id_tipo_atencion. '/' . $request->id_categoria . '/' . $request->id_consultorio . '/' . $request->id_categoria_rua_nivel);
        }
    }

    public function eliminar_rua_nivel_tipo_atencion($id, $tipo_atencion, $nivel, $categoria, $servicio, $id_categoria_rua_nivel)
    {
        if (!Auth::check()) :
          return redirect('/auth/login');
        endif;

        $model_laboratorio = new \App\Models\Laboratorio;
        $id_user = Auth::user()->id;

        $existe = $model_laboratorio->ActualizaEstado($id, $id_user);

        Session::flash('flash_message', 'Se eliminó correctamente el examen de laboratorio');
        return redirect('/add_rua_nivel/' . $nivel . '/' . $tipo_atencion. '/' . $categoria. '/' . $servicio . '/' . $id_categoria_rua_nivel);
    }

    public function add_categoria_rua($nivel, $tipo)
    {

        if (!Auth::check()) :
          return redirect('/auth/login');
        endif;

        if ($nivel>3) :
          return redirect('/dashboard');
        endif;

        if ($tipo>7) :
          return redirect('/dashboard');
        endif;

        switch ($nivel) {
            case 1: $descripcion_nivel='NIVEL I'; break;
            case 2: $descripcion_nivel='NIVEL II'; break;
            case 3: $descripcion_nivel='NIVEL III'; break;
        }

        $model_tipoatencion = new TipoAtencione();
        $tipo_atencion = $model_tipoatencion->VerTipoAtencion($tipo);
        $descripcion_tipo=$tipo_atencion->nombre;
        $id_user = Auth::user()->id;
        
        $model_laboratorio = new Laboratorio();
        $categorias = $model_laboratorio->getTodasCategoriasRua();

        $p[] = $nivel;
        $p[] = $tipo;
        $categoria_laboratorios = $model_laboratorio->getCategoriaNivelRua($p);

        //return view('frontend.laboratorios.registrar_categoria_rua', compact('categorias', 'nivel', 'categoria_laboratorios', 'tipo', 'descripcion_tipo', 'descripcion_nivel'));
        return view('frontend.laboratorios.registrar_categoria_rua_v2', compact('categorias', 'nivel', 'categoria_laboratorios', 'tipo', 'descripcion_tipo', 'descripcion_nivel'));
    }

    public function agregar_categoria_rua_atencion(Request $request)
    {
        if (!Auth::check()) :
          return redirect('/auth/login');
        endif;

        $model_laboratorio = new \App\Models\Laboratorio;
        $id_user = Auth::user()->id;

        $id_perfil_preoperatorio = 0;
        if ($request->id_consultorio == 26): // CENTRO QUIRURGICO
          $id_perfil_preoperatorio = intval($request->id_perfil_preoperatorio);
        endif;

        $existe = $model_laboratorio->ExisteRuaCategoriaAtenconNivel($request->id_categoria, $request->tipo, $request->nivel, $request->id_consultorio, $id_perfil_preoperatorio);

        if (count($existe)>0) {
          return redirect('/add_categoria_rua2/' . $request->id_consultorio)
            ->withInput()
            ->withErrors(array('No se ha podido registrar la categoría, ya existe en este nivel de atención.'));
        } else {
          $model_laboratorio->RegistrarCategoriaRuaNivelAtencion($request->id_categoria, $request->tipo, $request->nivel, $request->orden,$id_user,$request->id_consultorio, $id_perfil_preoperatorio);
          Session::flash('flash_message', 'Se agregó correctamente la categoría a este nivel de atención.');
          return redirect('/add_categoria_rua2/' . $request->id_consultorio);
        }
    }

    public function desactivar_nivel_categoria($id, $estado)
      {
        //print_r($estado);

        if (!Auth::check()) :
          return redirect('/auth/login');
        endif;

        $id_user = Auth::user()->id;
        
        $model_laboratorio = new Laboratorio();
        $categorias = $model_laboratorio->getCategoriaNivelAtencionRua($id);
        
        if ($estado == "1") {
          $db_update = DB::table('categoria_rua_nivels')
          ->where('id', $id)
          ->update(['estado' => 0, 'id_user_update' => $id_user]);

          $db_update_productos = DB::table('labnivelatenciones')
          ->where('id_nivel', $categorias->id_nivel)
          ->where('id_tipo_atencion', $categorias->id_atencion)
          ->where('id_categoria', $categorias->id_categoria_rua)
          ->update(['estado' => 0, 'id_user_update' => $id_user]);

          Session::flash('flash_message', 'Se desactivo correctamente');
        } else {
          $db_update = DB::table('categoria_rua_nivels')
          ->where('id', $id)
          ->update(['estado' => 1, 'id_user_update' => $id_user]);

          $db_update_productos = DB::table('labnivelatenciones')
          ->where('id_nivel', $categorias->id_nivel)
          ->where('id_tipo_atencion', $categorias->id_atencion)
          ->where('id_categoria', $categorias->id_categoria_rua)
          ->update(['estado' => 1, 'id_user_update' => $id_user]);

          Session::flash('flash_message', 'Se activo correctamente');
        }
      }

    public function invalidar_examen_laboratorio(Request $request)
    {
        if (!Auth::check()) :
          return redirect('/auth/login');
        endif;

        //0 hubo un error
        //1 se registro correctamente
        //2 se envió al labcore
        //3 ya cuenta con cita de reevaluación
        $id_laboratorio = $request->id_laboratorio;
        $id_user = $request->id_user;
        // $id_prestacion = $request->id_prestacion;
        $laboratorio = Laboratorio::find($id_laboratorio);

        $tiene_cita_reevaluacion = false;
        $prestacion = Prestaciones::find($laboratorio->id_prestacion);
        if ($prestacion) {
            $cita = Cita::find($prestacion->id_cita);
            if ($cita && $cita->id_establecimiento == 76 && $cita->flag_cita_laboratorio == 1) {
                $tiene_cita_reevaluacion = true;
            }
        }

        if ($tiene_cita_reevaluacion) {
            return "3";
        } elseif ($laboratorio->estado == 1 && $laboratorio->fecha_registro_toma_muestra == null) {
            $laboratorio->estado = 0; 
            $laboratorio->obs_examen_invalidado = $request->motivo_laboratorio; 
            $laboratorio->id_user_examen_invalidado = $request->id_user; 
            $laboratorio->save();
            return "1";
        }
        elseif ($laboratorio->estado == 1 && $laboratorio->fecha_registro_toma_muestra !== null) {
            return "2";
        } 
        else {
            return "0";
        }
    }

    public function add_categoria_rua2(/*$nivel,*/$id_consultorio)
    {

        if (!Auth::check()) :
          return redirect('/auth/login');
        endif;
        /*
        if ($nivel>3) :
          return redirect('/dashboard');
        endif;
        
        if ($tipo>7) :
          return redirect('/dashboard');
        endif;
        */

        // $model_consultorio = new Consultorio();
        // $consultorio = $model_consultorio->GetNombreById($id_consultorio);
        
        $model_establecimiento = new Establecimiento();
        $establecimiento = $model_establecimiento->GetById( auth()->user()->id_establecimiento );
        $nivel = $establecimiento[0]->nivel;

        if ($id_consultorio == 76) {
            $id_tipo_atencion = 2;
        } else if ($id_consultorio == 105) {
            $id_tipo_atencion = 3;
        } else {
            $id_tipo_atencion = 1;
        }
        
        $model_tipoatencion = new TipoAtencione();
        $tipo_atencion = $model_tipoatencion->VerTipoAtencion($id_tipo_atencion);
        $descripcion_tipo=$tipo_atencion->nombre;
        $id_user = Auth::user()->id;
        
        $model_laboratorio = new Laboratorio();
        $categorias = $model_laboratorio->getTodasCategoriasRua();
        $model_consultorio = new Consultorio();
        $consultorio = $model_consultorio->GetNombreById($id_consultorio);
    
        $p[] = $nivel;
        $p[] = $id_tipo_atencion;
        $p[] = $id_consultorio;

        $categoria_laboratorios = $model_laboratorio->getCategoriaNivelRua($p);
        return view('frontend.laboratorios.modal_categoria_rua2', compact('categorias', 'nivel', 'categoria_laboratorios', 'id_tipo_atencion', 'descripcion_tipo','consultorio'));
        
        //return view('frontend.laboratorios.registrar_categoria_rua2', compact('establecimientos','consultorio'));
    }

    public function add_rua_nivel2($nivel,$tipo,$id_categoria,$id_consultorio, $id_categoria_rua_nivel)
    {

        if (!Auth::check()) :
          return redirect('/auth/login');
        endif;

        switch ($nivel) {
            case 1: $descripcion_nivel='NIVEL I'; break;
            case 2: $descripcion_nivel='NIVEL II'; break;
            case 3: $descripcion_nivel='NIVEL III'; break;
        }

        switch ($tipo) {
            case 1: $descripcion_tipo='AMBULATORIO'; break;
            case 2: $descripcion_tipo='EMERGENCIA'; break;
            case 3: $descripcion_tipo='HOSPITALIZACION'; break;
        }

        $modelfarma = new Farmacia();
        $id_user = Auth::user()->id;

        $model_procedimiento = new Procedimiento;
        $listar_procedimientos_laboratorios = $model_procedimiento->getProcedimientosLab();

        $model_laboratorio = new Laboratorio;
        
        // $listar_examenes = $model_laboratorio->getRua2($id_categoria_rua_nivel);
        // if (empty($listar_examenes)):
        $id_categoria_rua_nivel = intval($id_consultorio) == 26 ? $id_categoria_rua_nivel : 0;
        $listar_examenes = $model_laboratorio->getRua($nivel, $tipo, $id_categoria, $id_consultorio, $id_categoria_rua_nivel);
        // endif;
        $categoria = $model_laboratorio->getNombreCategoriasRua($id_categoria);
        $list_proveedores = $model_laboratorio->obtenerProveedoresApoyoAlDiagnostico(2);

        return view('frontend.laboratorios.registrar_rua_nivel', compact('listar_procedimientos_laboratorios','listar_examenes','tipo', 'descripcion_nivel', 'descripcion_tipo','nivel','categoria','id_categoria','id_consultorio','list_proveedores', 'id_categoria_rua_nivel'));

    }

    public function obtenerTipoDeAtenciones($nivel)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
          endif;
        
        if ($nivel>3) :
        return redirect('/dashboard');
        endif;
        
        //Nivel de ipress
        switch ($nivel) {
            case 1: 
                $descripcion_nivel='NIVEL I';
                $op_tipo_atencion = ["1"=>'AMBULATORIO'];
                break;
            case 2: 
                $descripcion_nivel='NIVEL II'; 
                $op_tipo_atencion = [
                    "1"=>'AMBULATORIO',
                    "2"=>'EMERGENCIA',
                    "3"=>'HOSPITALIZACIÓN'
                ];
                break;
            case 3: 
                $descripcion_nivel='NIVEL III'; 
                $op_tipo_atencion = [
                    "1"=>'AMBULATORIO',
                    "2"=>'EMERGENCIA',
                    "3"=>'HOSPITALIZACIÓN'
                ];
                break;
        }

        $data = []; 
        $data[] = $nivel;
        $data[] = $descripcion_nivel;
        $data[] = $op_tipo_atencion;

        return $data;
    }

    public function obtenerNivelPorEstablecimiento($id_establecimiento)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
          endif;
          
          $model_establecimiento = new Establecimiento();
          $establecimientos = $model_establecimiento->GetById($id_establecimiento);   
        return $establecimientos;
    }



    public function verAtencionLaboratorio($id_tipo_atencion,$id_lab)
    {   
        if (!Auth::check()) {
            return redirect('/auth/login');
        }
        
        $model_prestacion = new \App\Models\Prestaciones;
        $model_laboratorio = new \App\Models\Laboratorio;

        $laboratorio = $model_laboratorio->GetLaboratorioById($id_lab);
        $id_prestacion=$laboratorio->id_prestacion;

        if ($id_tipo_atencion == "1") { //Ambulatorio
            $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
            $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
            $laboratorio_prestacion = $model_laboratorio->GetExamenLaboratorioById($id_lab);
        } elseif ($id_tipo_atencion == "2") { // Emergencia
            $pe[] = $id_prestacion;
            $prestacion = $model_prestacion->GetPrestacionEmergenciaById($pe);
            $laboratorio_prestacion = $model_laboratorio->GetExamenLaboratorioById($id_lab);
            
            if ($laboratorio->id_evaluacion > 0) {
                $model_evaluacion = new \App\Models\Evaluacione;
                $diagnostico = $model_evaluacion->GetDiagnosticoByIdEvaluacion($laboratorio->id_evaluacion);
                $tabla_prestacion_evaluacion = $model_evaluacion->GetEvaluacion($laboratorio->id_evaluacion);
              
            } else {
                $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacionEmergencia($id_prestacion);
                $tabla_prestacion_emergencia = $model_prestacion->GetPrestacionByIdPrestacion($id_prestacion);
                $medico = $model_prestacion->getMedicoForId($tabla_prestacion_emergencia->id_medico);
                // dd($medico);
            }
        } else {    //Hospitalización
            $laboratorio_prestacion = $model_laboratorio->GetExamenLaboratorioById($id_lab);
            $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
        }
        $proclaboratorio = $model_laboratorio->GetAllProcedimientosByIdLaboratorio($id_prestacion,$id_lab);
        /*$diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacionEvaluacion($id_prestacion, $laboratorio->id_evaluacion);
        $proclaboratorio = $model_prestacion->GetPrestacionLaboratorio($id_prestacion, $id_lab);
        $lista_rua = $model_laboratorio->getVerRuaConItemPorIdPrestacion($prestacion->nivel_estable, $prestacion->id_tipo_atencion, $prestacion->id_consultorio, $prestacion->id);
        $total_categoria = $model_laboratorio->getNumCategoria($prestacion->nivel_estable, $prestacion->id_tipo_atencion, $prestacion->id_consultorio);*/
        $prestacion_procedimiento = $model_laboratorio->obtenerPrestacionProcedimientoPorIdPrestacionEmergencia($laboratorio->id_prestacion, $id_lab);
        // dd($laboratorio_prestacion);
        //  dd($diagnostico);
        //=============Pendiente convertir a función
        try {
            //Generar código de orden del examen
            foreach ($laboratorio_prestacion as $examen) {
                if (is_null($examen->fecha_toma_muestra)) {
                    $examen->cod_orden_lab = null;
                } else {
                    $fecha = $examen->fecha_toma_muestra;
                    $numero = $examen->secuencial;
                    // Convertir la fecha al formato deseado ("2023/11/16")
                    $fechaObjeto = DateTime::createFromFormat('d/m/Y', $fecha);
                    $fechaFormateada = $fechaObjeto->format('Y/m/d');
                    // Procesamiento de la fecha para obtener "231116"
                    $fechaProcesada = substr(str_replace('/', '', $fechaFormateada), 2, 6);
                    // Convertir el número a una cadena y agregar ceros a la izquierda si es necesario
                    $numeroFormateado = str_pad($numero, 4, '0', STR_PAD_LEFT);
                    // Combinar ambas partes para obtener el resultado final
                    $examen->cod_orden_lab = $fechaProcesada . $numeroFormateado;
                }
            }
        } catch (\Throwable $th) {
            $th->getMessage();
        }
        //===============================================
        
        $total_examenes_prestacion_procedimientos = count($prestacion_procedimiento);
        /*$nivel =$prestacion->nivel_estable;
        $tipo_atencion = $prestacion->tipo_atencion;
        $consultorio = $prestacion->servicio;*/

        $fechaNacimiento = $prestacion->fecha_nac;
        $fechaNacimientoObj = DateTime::createFromFormat('d/m/Y', $fechaNacimiento);
        $fechaActual = new DateTime();
        $diferencia = $fechaNacimientoObj->diff($fechaActual);
        $edad_paciente = $diferencia->y;

        /*
        $num_categoria = $total_categoria->total;
        $total_examenes = count($lista_rua) + $num_categoria;*/
        return view('frontend.laboratorios.modal_detalle_laboratorio',compact('laboratorio_prestacion','prestacion','diagnostico','total_examenes_prestacion_procedimientos','edad_paciente','proclaboratorio','laboratorio','id_tipo_atencion','tabla_prestacion_emergencia','tabla_prestacion_evaluacion','medico'));
    }

    public function obtenerPrioridadesEmergencia()
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }
        
        $model_laboratorio = new Laboratorio();
        $prioridades_emergencia = $model_laboratorio->prioridadesEmergencia();
        return $prioridades_emergencia;
    }
    
    public function obtenerDetallePrestacionesLaboratorio($dni_paciente) 
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }

        $model_laboratorio = new Laboratorio();
        $p[] = $dni_paciente;
        $data = $model_laboratorio->listarDetallePrestacionLaboratorio($p);

        return $data;
    }
    
    public function obtenerItemsDetallePrestacionesLaboratorio($id_laboratorio) 
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }

        $model_laboratorio = new Laboratorio();
        $p[] = $id_laboratorio;

        $data = $model_laboratorio->listarItemsDetallePrestacionLaboratorio($p);

        return $data;
    }

    //Endpoint de prueba borrar
    // public function pruebaResultadoLabCore($dni_beneficiario, $fecha_toma_muestra, $nro_orden)
    // {
    //     $ws_laboratorio = new \App\Ws\LaboratorioLNS();
    //     $resultados_lab = $ws_laboratorio->CallVistaResultadosLaboratorio($dni_beneficiario, $fecha_toma_muestra, $nro_orden);

    //     return dd($resultados_lab->ResultadosLabView);
    // }
    // //=============================

    public function validacion_beneficiario_dias_por_examen_rua($dni_beneficiario, $id_examen_lab)
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }

        $model_laboratorio = new Laboratorio();
        $examenes = $model_laboratorio->validacionBeneficiarioDiasPorExamenRua($dni_beneficiario, $id_examen_lab);
        $valor_dias = $model_laboratorio->getAllOptionsKey('laboratorio_duracion_resultados_dias');
        // return $examenes;
        return response()->json(["examenes"=>$examenes,"dias"=>$valor_dias]);
    }

    // public function obtenerDetallePrestacionesLaboratorioEmergencia($dni_paciente, $id_evaluacion) 
    // {
    //     if (!Auth::check()) {
    //         return redirect('/auth/login');
    //     }

    //     $model_laboratorio = new Laboratorio();
    //     $p[] = $dni_paciente;
    //     $p[] = $id_evaluacion;
    //     $data = $model_laboratorio->listarDetallePrestacionLaboratorioEmergencia($p);

    //     return $data;
    // }

    // public function obtenerItemsDetallePrestacionesLaboratorioEmergencia($id_prestacion,$id_evaluacion) 
    // {
    //     if (!Auth::check()) {
    //         return redirect('/auth/login');
    //     }

    //     $model_laboratorio = new Laboratorio();
    //     $p[] = $id_prestacion;
    //     $p[] = $id_evaluacion;
    //     $data = $model_laboratorio->listarItemsDetallePrestacionLaboratorioEmergencia($p);

    //     return $data;
    // }

    public function consultaWsResultBeneficiarioExamenRua($dni_beneficiario, $id_examen_lab)
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }

        $result = DB::table('labnivelatenciones')
        ->select('codigo_lab')
        ->where('id', $id_examen_lab)
        ->first();

        // Asegúrate de verificar si se encontró el resultado antes de intentar acceder a sus propiedades
        if ($result) {
            $ws_laboratorio = new \App\Ws\LaboratorioLNS();
            $resultados_lab = $ws_laboratorio->CallResultadoExamenPorCodCPMS($dni_beneficiario, $result->codigo_lab);
            // Convertir el objeto en un array de un solo elemento
            //$resultados_lab_array = [$resultados_lab->ResultadosLabView];
            if (empty($resultados_lab)) {
                //return response()->json("vacio");
                return response()->json("vacio");
            } else {
                //return response()->json($resultados_lab);
                if (is_array($resultados_lab)) {
                    //Si es un array de objetos entonces lo retorna la collection
                    return response()->json($resultados_lab);
                } else {
                    //Si no es un array entonces es solo un objeto tenemos guardamos en un array para que no tenga problemas en el front
                    $result_array = [$resultados_lab];
                    return response()->json($result_array);
                }
            }
            // Si encuentras un resultado, lo devuelves en formato JSON
            //return response()->json($resultados_lab);
        } else {
            // Si no se encuentra el resultado, puedes devolver un JSON con un mensaje de error o null
            return response()->json(['error' => 'No se encontró el código del laboratorio para el ID proporcionado']);
        }
        

        //return json_encode($resultados_lab);
    }

    public function registrarObservacionAsistenciaPaciente(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }

        // accion_asistencia_paciente: "observar" => 2, "anular" => 1
        $estadoAsistenciaPaciente = $request->accion_asistencia_paciente == 'observar' ? 2 : 1;

        try {
          DB::transaction(function () use ($request, $estadoAsistenciaPaciente) {
            if ($request->tipo_atencion_generico == 1) 
            {
                //=========Limpiamos cita del servicio amarrado al laboratorio antes de anular la cita de laboratorio
                /*$model_cita = new \App\Models\Cita;
                $cita_lab = $model_cita->GetConsultorioByIdCitaLab($request->id_generico);
                if ($cita_lab->id_consultorio == 113) {
                $model_laboratorio = new \App\Models\Laboratorio;
                $result = $model_laboratorio->limpiarCitaDeLaboratorioParaReprogramar($request->id_generico);
                }*/
                //====================Anulamos la cita===========================
                /*$cita = Cita::where('id', $request->id_generico)->first();
                $cita->id_estado_reg = 0;
                $cita->id_estado_cita = 5;
                $cita->updated_at = Carbon::now();
                $cita->updated_us = Auth::user()->id;
                $cita->estado_asistencia_paciente = 1;
                $cita->obs_asistencia_paciente = $request->obs_asistencia_del_paciente;
                $cita->save();*/
                //====================Reflejamos el estado en las ordenes de laboratorio para el listado===========================
                $ids_laboratorio = DB::table('prestaciones')
                  ->join('laboratorios', 'laboratorios.id_prestacion', '=', 'prestaciones.id')
                  ->where('prestaciones.id_cita_dependiente_laboratorio', $request->id_generico)
                  ->lists('laboratorios.id');

                foreach ($ids_laboratorio as $id_laboratorio) {
                  $laboratorio = Laboratorio::find($id_laboratorio);
                  if ($laboratorio) {
                    $laboratorio->estado_asistencia_paciente = $estadoAsistenciaPaciente;
                    $laboratorio->obs_asistencia_paciente = $request->obs_asistencia_del_paciente;
                    $laboratorio->deleted_us = Auth::user()->id;
                    $laboratorio->deleted_at = Carbon::now();
                    $laboratorio->save();
                  }
                }
                //===============================================================
            }
            else
            {
                $laboratorio = Laboratorio::find($request->id_generico);
                $laboratorio->estado_asistencia_paciente = $estadoAsistenciaPaciente;
                // $laboratorio->estado_n_1_2 = 2;
                $laboratorio->obs_asistencia_paciente = $request->obs_asistencia_del_paciente;
                $laboratorio->deleted_us = Auth::user()->id;
                $laboratorio->deleted_at = Carbon::now();
                $laboratorio->save();
            }
          });

          return response()->json(['success' => 'true']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al guardar la observación: ' . $e->getMessage()], 500);
        }
    }

    public function obtenerSolicitudProcedimiento($id_solicitud_procedimiento)
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }

        $model_solicitud_procedimiento = new SolicitudProcedimiento();
        $res  = $model_solicitud_procedimiento->obtenerSolicitudProcedimientoPorId($id_solicitud_procedimiento);

        return $res;
    }

    public function obtenerColumnasServicioConsultorios($tipo_atencion,$id_consultorio)
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }

        $consultorio = Consultorio::where('id',$id_consultorio)->first();

        return $consultorio;
    }
    
    public function registrarColumnaReporteLaboratorio(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }

        $consultorio = Consultorio::where('id',$request->id_consultorio)->first();
        $consultorio->reporte_columnas_lab = $request->num_columnas;
        $consultorio->save();

        return 'true';
    }

    public function obtenerSitedsGeneradoHoy($asegurado_dni, $tipo_atencion_id, $id_press)
    {
        $laboratorioModel = new Laboratorio;
        $result = $laboratorioModel->obtenerSitedsGeneradoHoy($asegurado_dni, $tipo_atencion_id, $id_press);
        return response()->json($result);
    }

    public function listarOrdenesLaboratorioCitaOrigen($nro_doc,$id_servicio) 
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }

        $model_laboratorio = new \App\Models\Laboratorio;
        $res = $model_laboratorio->obtenerOrdenesDeLaboratorioCitaOrigen($nro_doc, auth()->user()->id_establecimiento, $id_servicio );

        foreach ($res as $key) {
            $key->cpms = $model_laboratorio->obtenerCpmsPorIdLaboratorio($key->id);
        }

        return $res; 
    }

    public function listarOrdenesLaboratorioCitaOrigen2($nro_doc, $id_cqx_intervencion) 
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }

        $model_laboratorio = new \App\Models\Laboratorio;
        $res = $model_laboratorio->obtenerOrdenesDeLaboratorioCitaOrigen2($nro_doc, auth()->user()->id_establecimiento, $id_cqx_intervencion);

        foreach ($res as $key) {
            $key->cpms = $model_laboratorio->obtenerCpmsPorIdLaboratorio($key->id);
        }

        return $res; 
    }
    
    public function listarOrdenesLaboratorioCitaOrigenCitaResultado($nro_doc) 
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }

        $model_laboratorio = new \App\Models\Laboratorio;
        $res = $model_laboratorio->obtenerOrdenesDeLaboratorioCitaOrigenCitaResultado($nro_doc, auth()->user()->id_establecimiento );

        foreach ($res as $key) {
            $key->cpms = $model_laboratorio->obtenerCpmsPorIdLaboratorio($key->id);
        }

        return $res; 
    }

    public function laboratorio_agrupado($idPaciente,$idCita){

      if (!Auth::check()) {
        return redirect('/auth/login');
      }

      $model_laboratorio = new \App\Models\Laboratorio;
      // $informacion = $model_laboratorio->obtenerPacienteEmergencia($idPaciente,$idCita);
      $model_prestacion = new Prestaciones();
      $informacion = $model_prestacion->GetPrestacionLaboratorio_agrupado($idCita);

      $filas = [];
      foreach ($informacion as $item) {
          $cpms = $item->cpms_personalizado;

          if (!isset($filas[$cpms])) {
            // Primera vez: crea la fila base
            $filas[$cpms] = [
              'item' => $item,
              'opciones' => []
            ];
          }

          // Agrega siempre el servicio/id_lab al grupo
          $filas[$cpms]['opciones'][] = [
            'id_lab_rad' => $item->id_lab_rad,
            'servicio' => $item->servicio,
            'id_rua' => $item->id
          ];
      }

      $user_informacion = $model_laboratorio->obtenerUserlogeado( Auth::user()->id);

      $paciente =  $model_laboratorio->obtenerPaciente($informacion[0]->id_asegurado);
      $cita = $model_laboratorio->obtenerCitaLab( $idCita);
      $edad ="";
      if(!empty($paciente) && isset($paciente[0]->fecha_nac)){

        $fechaNacimiento = Carbon::createFromFormat('d/m/Y', $paciente[0]->fecha_nac);
        $edad = $fechaNacimiento->age; 
      }
      $servicios_agrupados = $this->obtenerServiciosAgrupadosUnicos($informacion);
      return view('frontend.laboratorios.form_laboratorio_atencion_ambulatorio_agrupado_lns',compact( 'informacion','user_informacion','paciente','edad','idCita','idPaciente','cita', 'filas', 'servicios_agrupados'));
    }

    function yaExisteIdPrestacion($array, $id) {
      foreach ($array as $obj) {
          if ($obj->id_prestacion == $id) {
              return true;
          }
      }
      return false;
    }

    private function obtenerServiciosAgrupadosUnicos($informacion)
    {
      $servicios = [];
      foreach ($informacion as $item) {
        if (!empty($item->servicio) && !in_array($item->servicio, $servicios, true)) {
          $servicios[] = $item->servicio;
        }
      }
      return $servicios;
    }

    public function insertLab_post(Request $request){
      $obj_laboratorio = new \App\Models\Laboratorio();
      $ws_laboratorio = new \App\Ws\LaboratorioLNS();
      //$this->insertDataLab(4562605,9362,1,0);
      $prestaciones  =$request->prestaciones;
      $comentario  =$request->comentario;
      $codSusalud  =$request->codSusalud;

      

      $array_prestaciones = [];
      
      foreach ($prestaciones as $key => &$value) {


        $cadena = $value['id_laboratorios'];
        $array_id_lab = explode(",", $cadena);


        foreach ($array_id_lab as $key2 => $value2) {
          # code...
          $obj_laboratorio->prestacion_procedimientos_proveedor($value['id_prestacion'], $value2);
          $obj_laboratorio->actializarDatosLboratorio_al_insert($value2,$comentario);
        }


        
        // Obtener todos los datos relacionados con esos IDs
        $ordenado_fecha = $obj_laboratorio->obtener_orden_prestacion($value['id_procedimientos_prestaciones']);
    
        // Convertir cantidad a entero
        // $cantidad = intval($value['cantidad']);
        $cantidad = 1;
      
        
        // Inicializar array para almacenar los elementos con el campo 'insert'
        $ordenado_fecha_con_insert = [];
        // dd($ordenado_fecha);


        //foreach($ordenado_fecha as $item){
          //if (!$this->yaExisteIdPrestacion($array_prestaciones,$item->id_prestacion)) {
              $obj = new stdClass();
              $obj->id_prestacion = $ordenado_fecha[0]->id_prestacion;
              $secuencial = $ws_laboratorio->CallVerSecuencialLaboratorio();
              $obj->correlativo = $secuencial;
              array_push($array_prestaciones, $obj);
          //}
        //}
     
        foreach ($ordenado_fecha as $i => $item) {
          $item = (array) $item; // Asegura que sea array
      
          // Buscar correlativo en $array_prestaciones
          $correlativo = null;
          foreach ($array_prestaciones as $obj) {
              if ($obj->id_prestacion == $item['id_prestacion']) {
                  $correlativo = $obj->correlativo;
                  break;
              }
          }
      
          $item['insert'] = $i < $cantidad ? true : false;
          $item['correlativo'] = $correlativo;
      
          $ordenado_fecha_con_insert[] = $item;
      }
    

        // Agregar al objeto de prestaciones
        $value['ordenado_fecha'] = $ordenado_fecha_con_insert;
    }
    
    




      $var_inserts = [];
      foreach ($prestaciones as $key => &$value) {
        # code...
        
        $id_procedimientos_prestaciones = explode(',', $value['id_procedimientos_prestaciones']);
        foreach ($id_procedimientos_prestaciones as $key2 => $value2) {
          # code...
          $prestacion_procedimiento = $obj_laboratorio->obtener_orden_prestacion($value2);
          $validacion = $this->tieneInsertActivo($value['ordenado_fecha'], $value2);
          // dd($value2,$value['ordenado_fecha']);
          //dd($value['valor_cheack'],$prestaciones);

          $pendiente = "true";
          if($value['pendiente']=="NO" )
          $pendiente = "false";

          $obj_laboratorio->actulizarPendienteAgrupado($pendiente,$value2);

          if($value['valor_cheack'] == "4"  ){
            continue;
          }

          if($key2 >= 1  ){
            continue;
          }

          $ss = $this->insertDataLab($value2,$prestacion_procedimiento[0]->id_prestacion,
          $prestacion_procedimiento[0]->id_lab_rad,1,
          $prestacion_procedimiento[0]->id_evaluacion,$comentario,$prestacion_procedimiento[0]->id_rua,
          $codSusalud,$value['valor_cheack'],$array_prestaciones);
          if($ss != false){
            if($value['valor_cheack'] == 0){
              array_push($var_inserts,$ss);
            }
           
          }
        }
      }

           
      
      foreach ($var_inserts as $key => $value) {
        # code...
        $respuesta = $ws_laboratorio->InsertarOrdenLaboratorioNuevo([$value]);

      }
       

      return response()->json(["cantidad"=> count($var_inserts),"data"=>  $var_inserts ]);


    }

    function tieneInsertActivo( $ordenes,  $id){
      foreach ($ordenes as $orden) {
          if (isset($orden['id']) && $orden['id'] == $id) {
              return isset($orden['insert']) ? (bool)$orden['insert'] : false;
          }
      }
      return false; // No se encontró el ID
    }
    function obtenerCorrelativo($array, $idPrestacion) {
      foreach ($array as $item) {
          if (isset($item->id_prestacion) && $item->id_prestacion == $idPrestacion) {
              return $item->correlativo;
          }
      }
  
      return null; // No encontrado
  }



    public function insertDataLab($id_procedimiento_prestacion,
    $idPrestacion,$id_laboratorio,$id_tipo_atencion,$id_evaluacion,$comentario,$id_rua,
    $codSusalud,$estado,$array_secuencial){
      $ws_laboratorio = new \App\Ws\LaboratorioLNS();
      $obj_laboratorio = new \App\Models\Laboratorio();

      // $secuencial = $ws_laboratorio->CallVerSecuencialLaboratorio();
        if (env('CONEXION_WS_LAB') == "true") {
          //Obtener para el LIS
          $id_user = Auth::user()->id;
        
          $datos = $obj_laboratorio->obteneInformacionWs($id_procedimiento_prestacion,$idPrestacion, $id_tipo_atencion,  $id_evaluacion);
    
          if(count($datos) == 0){
            //  dd($datos);
            return false;
          }
          $secuencial = $this->obtenerCorrelativo($array_secuencial, $datos[0]->id_prestacion);
          $data_lab = $obj_laboratorio->ActilizardataLab($id_laboratorio,$comentario,$id_user,$codSusalud,$secuencial->descripcionMensaje);
          $obj_laboratorio->ModificarTomaMuestraUnitario($id_procedimiento_prestacion,
          $estado,1);//verificar aqui envia a todos como con reactivo

          //Generar código de orden del examen
          foreach ($data_lab as $lp) {
            if (is_null($lp->fecha_toma_muestra)) {
                $cod_orden_lab = null;
            } else {
                $fecha = $lp->fecha_toma_muestra;
                $numero = $lp->secuencial;
                // Convertir la fecha al formato deseado ("2023/11/16")
                $fechaObjeto = DateTime::createFromFormat('d/m/Y', $fecha);
                $fechaFormateada = $fechaObjeto->format('Y/m/d');
                // Procesamiento de la fecha para obtener "231116"
                $fechaProcesada = substr(str_replace('/', '', $fechaFormateada), 2, 6);
                // Convertir el número a una cadena y agregar ceros a la izquierda si es necesario
                $numeroFormateado = str_pad($numero, 4, '0', STR_PAD_LEFT);
                // Combinar ambas partes para obtener el resultado final
                $cod_orden_lab = $fechaProcesada . $numeroFormateado;
            }
        }
          $obj_laboratorio->guardarNumeroOrdenLaboratorio($id_laboratorio, $cod_orden_lab);
          $laboratorio = Laboratorio::where("id", $id_laboratorio);
          $laboratorio->update([
              "updated_at_cod_siteds" => Carbon::now(),
              "updated_us_cod_siteds" => Auth::user()->id
          ]);

          foreach ($datos as $data) {
            
              $obj = new \App\Models\InterLabHcentral();
              // $obj = new stdClass();
              $obj->idOrigen        = $data->id_tipo_atencion;
              $obj->fechaMuestra    = $this->formatearFechaYmd($data->fecha_atencion);// explode(' ', $data->fecha_atencion)[0]; 
              $obj->idProcedencia   = $data->establecimiento_id;  // DEBE ENCAJAR CON LA BD "HNPNPLNS" DE POSTGRES, ESTA CONFIGURADO EN LABCORE
              $obj->idDepartamento  = 0; // NO HAY ID DEPARTAMENTO, VER SI AFECTA AL LABCORE
              $obj->idServicio      = ($data->id_tipo_atencion == 2 ? $data->id_servicio:$data->id_consultorio);//---
              $obj->dniMedico       = $data->medico_dni;
              $obj->nombreMedico    = $data->medico_nombre .' '.$data->medico_paterno.' '.$data->medico_materno;
              $obj->dniProfLab      = Auth::user()->dni;
              $obj->nombreProfLab   =  Auth::user()->nombre.' '.Auth::user()->paterno.' '.Auth::user()->materno;
              $obj->historiaClinica = $data->nro_historia;

              $obj->dniPaciente     = $data->paciente_dni;
              $obj->nombrePaciente  = $data->paciente_nombre .' '.$data->paciente_paterno.' '.$data->paciente_materno;;
              $obj->genero          = $data->paciente_sexo;
              $obj->fechaNac        = $this->formatearFechaYmd_2($data->fecha_nac);
              $obj->parentesco      = $data->tipo_beneficiario == null ? "":$data->tipo_beneficiario;
              $obj->grado           = $data->grado == null ? "":$data->grado;
              $obj->situacionTitular = $data->paciente_situacion == null ? "": $data->paciente_situacion;
              $obj->condicionUpss   = $data->upss;
              $obj->codigoDiag      = $data->diagnosticos_codigo;
              $obj->diagnostico     = $data->diagnosticos_nombre == null ? "": $data->diagnosticos_nombre;

              $obj->tipoDiag        = $data->tipo_diagnostico;
              $obj->idDetalle       = $data->id_prestacion;
              $obj->idServicioAnalisis = 0;
              $obj->codigoProced    = $data->codigo_procedimiento;
              $obj->procedimiento   = $data->procedimiento_descripcion ==null ? "":$data->procedimiento_descripcion;
              $obj->estadoLab       = 0;
              $obj->nombrePc        = '';
              $obj->fechaRegistro   = $this->formatearFechaYmd($data->fecha_registro_orden);  // HECHO - FECHA REGISTRO DEL RUA, DEBERIA OBTNERLO DE LA BD, VERIFICAR DE QUE CAMPO
              $obj->observacion     = $data->observacion == null ? "":$data->observacion; // HECHO - DEBE OBTENERLO DE BD, AL MOMENTO DE REGISTRAR EL RUA, SE REGISTRA LA OBSERVACION
              $obj->secuencia       = $secuencial->descripcionMensaje;//$data->secuencial; HECHO - DEBE OBTENERLO DE BD, AL MOMENTO DE REGISTRAR EL RUA

              $obj->nombreUsuario   =Auth::user()->nombre.' '.Auth::user()->paterno.' '.Auth::user()->materno; // USUARIO QUE REGISTRA EL RUA PARA REALIZAR LA PRUEBA
              $obj->apepaternoPaciente = $data->paciente_nombre;
              $obj->apematernoPaciente = $data->paciente_paterno;
              $obj->prenombresPaciente = $data->paciente_materno;
              $obj->codigo_proveedor = $data->codigo_proveedor;
              $obj->codigo_proced_alterno = $data->cpms_personalizado;

             return  $obj  ;
          }
      } 
    }

    function formatearFechaYmd($fecha) {
      $fechaObj = new DateTime($fecha);
      return $fechaObj->format('Ymd'); // Devuelve "20250327"
  }
  function formatearFechaYmd_2($fecha) {
      $fechaObj = DateTime::createFromFormat('d/m/Y', $fecha);
      
      // Verifica si se pudo parsear correctamente
      if ($fechaObj === false) {
          return ""; // o manejar el error como desees
      }

      return $fechaObj->format('Ymd');
  }


    public function laboratorio_agrupado_editar($idPaciente,$idCita){

      if (!Auth::check()) {
        return redirect('/auth/login');
      }

      $model_laboratorio = new \App\Models\Laboratorio;

      // $informacion = $model_laboratorio->obtenerPacienteEmergencia($idPaciente,$idCita);
      $user_informacion = $model_laboratorio->obtenerUserlogeado( Auth::user()->id);
      $model_prestacion = new Prestaciones();
      $informacion = $model_prestacion->GetPrestacionLaboratorio_agrupado($idCita);

      $filas = [];
      foreach ($informacion as $item) {
          $cpms = $item->cpms_personalizado;

          if (!isset($filas[$cpms])) {
            // Primera vez: crea la fila base
            $filas[$cpms] = [
              'item' => $item,
              'opciones' => []
            ];
          }

          // Agrega siempre el servicio/id_lab al grupo
          $filas[$cpms]['opciones'][] = [
            'id_lab_rad' => $item->id_lab_rad,
            'servicio' => $item->servicio,
            'id_rua' => $item->id,
            'confirmar_toma' => $item->confirmar_toma,
            'pendiente_laboratorio' => $item->pendiente_laboratorio,
            'id_prestacion_procedimiento' => $item->id_prestacion_procedimiento,
            'migrado_lab' => $item->migrado_lab
          ];
      }
      // dd($filas);
      // dd($informacion);
      $paciente =  $model_laboratorio->obtenerPaciente( $informacion[0]->id_asegurado);
      $cita = $model_laboratorio->obtenerCitaLab( $idCita);
      $edad ="";
      if(!empty($paciente) && isset($paciente[0]->fecha_nac)){

        $fechaNacimiento = Carbon::createFromFormat('d/m/Y', $paciente[0]->fecha_nac);
        $edad = $fechaNacimiento->age; 
      }
      $servicios_agrupados = $this->obtenerServiciosAgrupadosUnicos($informacion);
      return view('frontend.laboratorios.form_edicion_ambulatorio_agrupado_lns',compact('filas','user_informacion','paciente','edad','idCita','cita', 'servicios_agrupados'));
    }


    public function updateLab_post(Request $request){
      $obj_laboratorio = new \App\Models\Laboratorio();
      $ws_laboratorio = new \App\Ws\LaboratorioLNS();
      //$this->insertDataLab(4562605,9362,1,0);
      $prestaciones  =$request->prestaciones;
      $comentario  =$request->comentario;
      $codSusalud  =$request->cod_siteds;

      

      $array_prestaciones = [];

      
      $id_usuario = auth()->id();
      
      foreach ($prestaciones as $key => &$value) {

        $cadena = $value['id_laboratorios'];
        $array_id_lab = explode(",", $cadena);


        foreach ($array_id_lab as $key2 => $value2) {
          # code...
          $obj_laboratorio->prestacion_procedimientos_proveedor($value['id_prestacion'],$value2);
          $obj_laboratorio->actializarDatosLboratorio($value2,$comentario,$id_usuario );
          $obj_laboratorio->prestacion_procedimientos_proveedor($value['id_prestacion'], $value2);

        }


        
        // Obtener todos los datos relacionados con esos IDs
        $ordenado_fecha = $obj_laboratorio->obtener_orden_prestacion($value['id_procedimientos_prestaciones']);
    
        // Convertir cantidad a entero
        // $cantidad = intval($value['cantidad']);
        $cantidad = 1;
      
        
        // Inicializar array para almacenar los elementos con el campo 'insert'
        $ordenado_fecha_con_insert = [];
        // dd($ordenado_fecha);


        foreach($ordenado_fecha as $item){
          if (!$this->yaExisteIdPrestacion($array_prestaciones,$item->id_prestacion)) {
              $obj = new stdClass();
              $obj->id_prestacion = $item->id_prestacion;
              $secuencial = $ws_laboratorio->CallVerSecuencialLaboratorio();
              $obj->correlativo = $secuencial;
              array_push($array_prestaciones, $obj);
          }
        }
     
        foreach ($ordenado_fecha as $i => $item) {
          $item = (array) $item; // Asegura que sea array
      
          // Buscar correlativo en $array_prestaciones
          $correlativo = null;
          foreach ($array_prestaciones as $obj) {
              if ($obj->id_prestacion == $item['id_prestacion']) {
                  $correlativo = $obj->correlativo;
                  break;
              }
          }
      
          $item['insert'] = $i < $cantidad ? true : false;
          $item['correlativo'] = $correlativo;
      
          $ordenado_fecha_con_insert[] = $item;
      }
    

        // Agregar al objeto de prestaciones
        $value['ordenado_fecha'] = $ordenado_fecha_con_insert;
    }
    
    




      $var_inserts = [];
      foreach ($prestaciones as $key => &$value) {
        # code...
        
        $id_procedimientos_prestaciones = explode(',', $value['id_procedimientos_prestaciones']);
        foreach ($id_procedimientos_prestaciones as $key2 => $value2) {
          # code...
          $prestacion_procedimiento = $obj_laboratorio->obtener_orden_prestacion($value2);
          $validacion = $this->tieneInsertActivo($value['ordenado_fecha'], $value2);
          // dd($value2,$value['ordenado_fecha']);


          $pendiente = "true";
          if($value['pendiente']=="NO" )
          $pendiente = "false";

          $obj_laboratorio->actulizarPendienteAgrupado($pendiente,$value2);

          if(!$validacion || $value['insertar']== "0" ){
            continue;
          }

          $ss = $this->insertDataLab($value2,$prestacion_procedimiento[0]->id_prestacion,
          $prestacion_procedimiento[0]->id_lab_rad,1,
          $prestacion_procedimiento[0]->id_evaluacion,$comentario,$prestacion_procedimiento[0]->id_rua,
          $codSusalud,$value['valor_cheack'],$array_prestaciones);
          if($ss != false){
            if($value['valor_cheack'] == 0){
              array_push($var_inserts,$ss);
            }
           
          }
        }
      }

           
      
      foreach ($var_inserts as $key => $value) {
        # code...
        $respuesta = $ws_laboratorio->InsertarOrdenLaboratorioNuevo([$value]);

      }
       

      return response()->json(["cantidad"=> count($var_inserts),"data"=>  $var_inserts ]);


    }
  public function modal_historial_cita_laboratorio($dni_beneficiario){
    $model_laboratorio = new \App\Models\Laboratorio();
    $citas = $model_laboratorio->GetCitasByDni($dni_beneficiario);


    $unicos = [];
    $idsVistos = [];

    foreach ($citas as $item) {
        if (!in_array($item->id, $idsVistos)) {
            $unicos[] = $item;
            $idsVistos[] = $item->id;
        }
    }

    $citas = $unicos;
    return view('frontend.laboratorios.modal_laboratorio_historial_citas',compact('citas'));
  }


  public function ReprogramarCitaLaboratorio($idCita,$idSubConsultorio,$fecha){

    $model_laboratorio = new \App\Models\Laboratorio();
    $model_laboratorio->reprogramarCita($idCita,$idSubConsultorio,$fecha);
    

    
    return response()->json(["object"=>"success","message"=>"Se actulizao con exito"]);
  }

  public function getAllOptions(){
    $model_laboratorio = new \App\Models\Laboratorio();
    $options = $model_laboratorio->getAllOptions();
    

    
    return response()->json($options);
  }

  public function updateOption(Request $request)
    {
     

 
            // Actualizar la opción
            $updated = DB::table('config_options')
                ->where('id', $request->id)
                ->update([
                    'option_label' => $request->option_label,
                    'option_type' => $request->option_type,
                    'option_value' => $request->option_value,
                    'autoload' => $request->autoload
                ]);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Opción actualizada con éxito'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo actualizar la opción'
                ], 500);
            }
     
    }

 
    public function getOptionsByPrefix(Request $request)
    {
        $prefix = $request->prefix ;
        
        // Buscar opciones que comienzan con el prefijo
        $options = DB::table('config_options')
            ->where('option_name', 'like', $prefix . '%')
            ->get();
            
        return response()->json($options);
    }


       /**
     * Guardar opciones
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveOptions(Request $request)
    {
        try {
            if (!$request->has('options') || !is_array($request->options)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se recibieron opciones para guardar'
                ], 422);
            }
            
            $updatedCount = 0;
            
            foreach ($request->options as $optionId => $optionValue) {
                // Verificar si la opción existe
                $option = DB::table('config_options')
                    ->where('id', $optionId)
                    ->first();
                
                if ($option) {
                    // Procesar el valor según el tipo
                    $valueToSave = $optionValue;
                    
                    // Si es un checkbox y no se envió, establecer como 0
                    if ($option->option_type === 'checkbox' && $valueToSave === null) {
                        $valueToSave = '0';
                    }
                    
                    // Actualizar la opción
                    $updated = DB::table('config_options')
                        ->where('id', $optionId)
                        ->update([
                            'option_value' => $valueToSave
                        ]);
                    
                    if ($updated) {
                        $updatedCount++;
                    }
                }
            }
            
            if ($updatedCount > 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Se actualizaron ' . $updatedCount . ' opciones correctamente'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo actualizar ninguna opción'
                ], 500);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar las opciones: ' . $e->getMessage()
            ], 500);
        }
    }

    public function listar_examenes_laboratorios_nivel2(Request $request) {
        
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $id_user = $request->query('id_user');
        $id_establecimiento = Auth::user()->id_establecimiento;
        $fechaDesde = $request->query('fechaDesde');
        $fechaHasta = $request->query('fechaHasta');
        $dni_beneficiario = strtoupper($request->query('dni_beneficiario'));
        $referido = $request->query('referido');
        $tipo_atencion = $request->query('tipo_atencion');
        $id_servicio = $request->query('id_servicio');
        $nro_examen = $request->query('nro_examen');
        $id_prioridad = $request->query('id_prioridad');
        $estado = $request->query('estado');
        $id_farmacia = $request->query('id_farmacia');

        $filters = [
          "id_tipo_atencion" => $tipo_atencion,
          "id_establecimiento" => $id_establecimiento,
          "fecha_inicio" => Carbon::createFromFormat('d-m-Y', $fechaDesde)->format('Y-m-d'),
          "fecha_fin" => Carbon::createFromFormat('d-m-Y', $fechaHasta)->format('Y-m-d'),
          "nro_doc_ident" => $dni_beneficiario,
          "id_consultorio" => $id_servicio,
          "id_prioridad" => $id_prioridad,
          "referido" => $referido,
          "cod_orden_lab" => $nro_examen,
          "estado_n_1_2" => $estado, // 0: pendiente, 1: atendido, 2: anulado, 3: Reprogramado
        ];
        $model_laboratorio = new Laboratorio();

        $results = $model_laboratorio->getOrdenLaboratorioByFiltersNivel12($filters);
        
        $response = [
            "data" => isset($results) ? $results : [],
            "recordsTotal" => isset($results) ? count($results) : 0
        ];

        return response()->json($response);
    }

  public function formulario_atencion_laboratorio_nivel2($id, $id_tipo_atencion) {
    if (!Auth::check()):
        return redirect('/auth/login');
    endif;
  
    $id_user = Auth::user()->id;
    $tipo_user = Auth::user()->tipo_registro;

    if (!Auth::user()->tipo_registro && Auth::user()->flagestablecimientos != 1) {
      return redirect('all_laboratorio_prescritos_by_ipress');
    }

    //-----------------------------------------------        
    $modelFarmacia = new Farmacia();
    $establecimiento = $modelFarmacia->GetIpressByIduser($id_user);
    $id_ipress = $establecimiento->id;
    $nivel = $establecimiento->nivel;

    /************************************************************************************************************/
    $model_prestacion = new \App\Models\Prestaciones;
    $model_laboratorio = new \App\Models\Laboratorio;        
    $model_referencia = new \App\Models\Referencia; 
    $model_medico = new Medico;       
    // $model_asegurado = new Asegurado;     

    $laboratorio = $model_laboratorio->GetLaboratorioById($id);
    if (intval($laboratorio->estado_n_1_2) === 1) {
      session()->flash('error', "La orden de laboratorio ya fue atendida.");
      return redirect('/all_laboratorio_prescritos_by_ipress');
    }

    $jefe_laboratorio = $model_laboratorio->AllMedicosByIpress($id_ipress);
    $user = $model_laboratorio->AllResponsableByIpress($id_ipress);

    $id_prestacion=$laboratorio->id_prestacion;

    $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);

    // switch ($id_tipo_atencion) {
    //   case '1':
    //       $prestacion = $model_prestacion->GetPrestacionById($id_prestacion); //Ambulatorio
    //       $referencia = $prestacion->referido;
    //       if ($referencia == "SI") {
    //           $referencia_datos = $model_referencia->obtenerDatosDeRefereanciaParaAtencionLabPorId($prestacion->id_referencia);
    //           $diagnostico_referencia = $model_prestacion->GetDiagnosticoByIdPrestacion($referencia_datos->id_prestacion_referencia);
    //       }
    //       $codigo_siteds = $model_laboratorio->obtenerCodigoSitedsSiExisteOrdenLabDelDia($prestacion->id_asegurado);
    //       break;
      
    //   case '2':
    //       $p = [$laboratorio->id_prestacion];
    //       $prestacion_emergencia = $model_prestacion->GetPrestacionEmergenciaById($p); //emergencia
    //       $referencia = $prestacion_emergencia->id_motivo_ingreso;
    //       $codigo_siteds = $model_laboratorio->obtenerCodigoSitedsSiExisteOrdenLabDelDia($prestacion_emergencia->id_asegurado);
    //       break;
      
    //   case '3':
    //       $prestacion = $model_prestacion->getPrestacionHospitalizados($id_prestacion);
    //       $referencia = 'FALTA';//$prestacion->id_motivo_ingreso;
    //       $model_emergencia = new \App\Models\Emergencia;
    //       $hospitalizacion = \App\Models\Hospitalizacione::where('id', $prestacion->id_ext)->first();
    //       $camas = $model_emergencia->GetObtenerCama($hospitalizacion->id_cama_estado);
    //       break;
    // }

    $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
    $total_examenes_clinicos1 = count($diagnostico);
    $proclaboratorio = $model_prestacion->GetPrestacionLaboratorio($id_prestacion, $id);

    if (count($proclaboratorio) <= 0) {
      return redirect('all_laboratorio_prescritos_by_ipress');
    }

    $total_examenes_clinicos = count($proclaboratorio);
    $contadorRegistrosConDatos = 0;

    foreach ($diagnostico as $registro) {
        if ($registro->id_evaluacion !== null) {
            $contadorRegistrosConDatos++;
        }
    }

    $idjefelaboratorio = 0;
    $idresponsable = 0;

    // dd(compact(
    //   'establecimiento',
    //   'prestacion',
    //   'diagnostico',
    //   'laboratorio',
    //   'proclaboratorio',
    //   'id_tipo_atencion'
    // ));

    return view('frontend.laboratorios.form_laboratorio_atencion_nivel2', compact(
      'establecimiento',
      'prestacion',
      'diagnostico',
      'laboratorio',
      'proclaboratorio',
      'id_tipo_atencion'
    ));

    
    //   switch ($id_tipo_atencion) {
    //       case '1':
    //           return view('frontend.laboratorios.form_laboratorio_atencion_ambulatorio_lns', compact('total_examenes_clinicos','contadorRegistrosConDatos','referencia','prestacion','diagnostico','laboratorio','proclaboratorio','user','jefe_laboratorio','idjefelaboratorio','idresponsable','id_ipress','id_user','nivel','id_tipo_atencion','codigo_siteds','referencia_datos','diagnostico_referencia'));
    //           break;
    //       case '2':
    //           return view('frontend.laboratorios.form_laboratorio_atencion_emergencia_lns', compact('prestacion_emergencia','total_examenes_clinicos','contadorRegistrosConDatos','referencia','diagnostico','laboratorio','proclaboratorio','user','jefe_laboratorio','idjefelaboratorio','idresponsable','id_ipress','id_user','nivel','id_tipo_atencion','codigo_siteds'/*,'referencia_datos','diagnostico_referencia'*/));
    //           break;
    //       case '3':
    //           return view('frontend.laboratorios.form_laboratorio_atencion_hospitalizacion_lns', compact('total_examenes_clinicos','contadorRegistrosConDatos','referencia','prestacion','diagnostico','laboratorio','proclaboratorio','user','jefe_laboratorio','idjefelaboratorio','idresponsable','id_ipress','id_user','nivel','id_tipo_atencion','codigo_siteds','referencia_datos','diagnostico_referencia','camas'));
    //           break;
    //   }
  }
  
    public function registrar_examen_laboratorio_nivel2(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $id_laboratorio = $request->id_laboratorio; 
        $id_prestacion = $request->id_prestacion;
        $cod_siteds_susalud = $request->cod_siteds_susalud;
        $observacion = $request->observacion;
        $examenes = $request->examenes;
        $cod_orden_lab = null;
        $id_user = Auth::user()->id;
        $obj_laboratorio = new \App\Models\Laboratorio();
      
        $laboratorio_prestacion = $obj_laboratorio->GetLaboratorioByIdCO($id_laboratorio);//select
          //Generar código de orden del examen
        foreach ($laboratorio_prestacion as $lp) {
          if (is_null($lp->fecha_toma_muestra)) {
            $cod_orden_lab = null;
          } else {
            $fecha = $lp->fecha_toma_muestra;
            $numero = $lp->secuencial;
            $fechaObjeto = DateTime::createFromFormat('d/m/Y', $fecha);
            $fechaFormateada = $fechaObjeto->format('Y/m/d');
            $fechaProcesada = substr(str_replace('/', '', $fechaFormateada), 2, 6);
            $numeroFormateado = str_pad($numero, 4, '0', STR_PAD_LEFT);
            $cod_orden_lab = $fechaProcesada . $numeroFormateado;
          }
        }
        // $obj_laboratorio->guardarNumeroOrdenLaboratorio($id_laboratorio, $cod_orden_lab);
        $laboratorio = Laboratorio::where('id_prestacion', $id_prestacion)->where('id', $id_laboratorio)->first();
        if ($laboratorio) {
          $laboratorio->update([
            'secuencial' => null,
            'observacion_toma_muestra' => strtoupper($observacion),
            'fecha_registro_toma_muestra' => Carbon::now(),
            'id_usuario_tomamuestra' => $id_user,
            'cod_siteds_susalud' => $cod_siteds_susalud,
            'updated_at_cod_siteds' => Carbon::now(),
            'updated_us_cod_siteds' => $id_user,
            'cod_orden_lab' => $cod_orden_lab,
            'estado_n_1_2' => 1
          ]);
        }

        $cita = Cita::select('citas.id', 'citas.id_estado_cita')
            ->join('prestaciones as pres', 'pres.id_cita_dependiente_laboratorio', '=', 'citas.id')
            ->join('laboratorios as l', 'l.id_prestacion', '=', 'pres.id')
            ->where('l.id', $id_laboratorio)
            ->first();
        
        if ($cita && isset($cita->id)) {
            Cita::where('id', $cita->id)->update([ 'id_estado_cita' => 4 ]);
        }

        if (empty($examenes) || count($examenes) == 0) {
          return response()->json([
              'success' => false,
              'message' => 'Para registrar, debe seleccionar al menos una prueba de Laboratorio.'
          ], JsonResponse::HTTP_BAD_REQUEST);
        }

        foreach ($examenes as $examen) {
          $id_prestacion_procedimiento = $examen['idPrestacionProcedimiento'];
          if (isset($examen['archivos']) && is_array($examen['archivos']) && count($examen['archivos']) && isset($id_prestacion_procedimiento)) {
            foreach ($examen['archivos'] as $key => $file) {
              $timestamp = Carbon::now()->format('YmdHis');
              $uniqueId = uniqid();
              $extension = $file->getClientOriginalExtension();
              $original_name = $file->getClientOriginalName();
              $size = $file->getSize();

              $directorio = 'laboratorio/resultados';
              $nombreArchivo = "RL_{$timestamp}_{$uniqueId}." . strtolower($extension);
              $rutaRelativa = $directorio . '/' . $nombreArchivo;

              $contenido = file_get_contents($file->getRealPath());

              if (!Storage::disk('local')->exists($directorio)) {
                Storage::disk('local')->makeDirectory($directorio);
              }

              Storage::disk('local')->put($rutaRelativa, $contenido);

              $pathFile = $rutaRelativa;

              $newLaboratorioArchivosResultado = LaboratorioArchivosResultado::create([
                'id_prestacion_procedimiento' => $id_prestacion_procedimiento,
                'uri' => $pathFile,
                'original_name' => $original_name,
                'extension' => strtolower($extension),
                'size' => $size,
                'created_at' => Carbon::now(),
                'created_us' => Auth::user()->id,
              ]);
            }
          }
          // $pathFile = null;
          // if (isset($examen['archivo']) && $examen['archivo']) {
          //   $file = $examen['archivo'];

          //   $timestamp = Carbon::now()->format('YmdHis');
          //   $uniqueId = uniqid();
          //   $extension = $file->getClientOriginalExtension();

          //   $directorio = 'laboratorio/resultados';
          //   $nombreArchivo = "RL_{$timestamp}_{$uniqueId}." . strtolower($extension);
          //   $rutaRelativa = $directorio . '/' . $nombreArchivo;

          //   $contenido = file_get_contents($file->getRealPath());

          //   if (!Storage::disk('local')->exists($directorio)) {
          //     Storage::disk('local')->makeDirectory($directorio);
          //   }

          //   Storage::disk('local')->put($rutaRelativa, $contenido);

          //   $pathFile = $rutaRelativa;
          // }

          $reembolso = intval($examen['reembolso']) === 1 ? true : false;

          $prestacionProcedimiento = Prestacion_procedimiento::where('id', $id_prestacion_procedimiento)->first();
          if ($prestacionProcedimiento) {
            $prestacionProcedimiento->update([
              'confirmar_toma' => '0',
              'observacion' => $examen['observacion'],
              'cantidad' => $examen['cantidad'],
              'archivo_resultado' => null,
              'reembolso' => $reembolso,
              'confirmar_toma_created_us' => $id_user,
              'confirmar_toma_created_at' => Carbon::now(),
            ]);
          }
        }

        session()->flash('success', "Se registro exitosamente la atención de la orden de laboratorio.!");
        return response()->json([
            'success' => true,
            'message' => 'Se registro exitosamente la atención de la orden de laboratorio',
        ], JsonResponse::HTTP_CREATED);
    }

    function getHistorialAtencionesByIdAsegurado($id_asegurado) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $model_laboratorio = new Laboratorio;
        $atenciones = $model_laboratorio->GetHistorialAtencionesByIdAsegurado($id_asegurado);
        return response()->json([
            'success' => true,
            'data' => $atenciones
        ], JsonResponse::HTTP_OK);
    }

    public function laboratorioObtenerTopicos($id_establecimiento){
    $triajes = new Triaje();

    // Filtrar para que cada nombre sea único, dejando solo el primer objeto que lo tenga
    $triajesUnicosPorNombre = $triajes->obtenerTriajeLaoratorio($id_establecimiento);

    return $triajesUnicosPorNombre;
    }

    public function verDetalleAtencion($id_laboratorio, $id_prestacion) {
      return view('frontend.laboratorios.ver_detalle_atencion_nivel_1_2', compact('id_laboratorio', 'id_prestacion'));
    }
    
    public function getExamenesByIdPrestacionIdLaboratorio($id_laboratorio, $id_prestacion) {
      $id_user = Auth::user()->id;
      $model_prestacion = new Prestaciones();
      $modelFarmacia = new Farmacia();
      $model_laboratorio = new Laboratorio();   

      $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);

      $establecimiento = $modelFarmacia->GetIpressByIduser($id_user);

      $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);

      $examenesLaboratorio = $model_prestacion->GetPrestacionLaboratorio($id_prestacion, $id_laboratorio);
      if ($examenesLaboratorio && count($examenesLaboratorio) > 0) {
        foreach ($examenesLaboratorio as $key => $examen) {
          $archivosResultados = LaboratorioArchivosResultado::where('id_prestacion_procedimiento', $examen->id_prestacion_procedimiento)->where('estado', true)->get();
          $examen->files = $archivosResultados;
        }
      }

      $laboratorio = $model_laboratorio->GetLaboratorioById($id_laboratorio);

      $data = [
        "prestacion" => $prestacion,
        "establecimiento" => $establecimiento,
        "diagnosticos" => $diagnostico,
        "examenesLaboratorio" => $examenesLaboratorio,
        "ordenLaboratorio" => $laboratorio,
      ];

      return response()->json([
        'success' => true,
        'message' => "Se realizo la consulta exitosamente",
        'data' => $data
      ], JsonResponse::HTTP_OK);
    }

    public function descargarArchivoResultado($nombre_archivo)
    {
      $ruta = storage_path("app/laboratorio/resultados/{$nombre_archivo}.pdf");
      if (!file_exists($ruta)) {
          return response()->json(['error' => 'Archivo no encontrado'], 404);
      }
      return response()->download($ruta);
    }

  public function agregar_rua_nivel_tipo_atencion_nivel_1_2(Request $request)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $model_laboratorio = new Laboratorio();
    $id_user = Auth::user()->id;

    $id_nivel = $request->id_nivel;
    $id_categoria = $request->id_categoria;
    $id_procedimiento = $request->id_procedimiento;
    $descripcion_corta = $request->descripcion_corta;
    $id_tipo_atencion = $request->id_tipo_atencion;

    $labnivelatenciones = Labnivelatencion::where('id_procedimiento', $id_procedimiento)
              ->where('id_categoria', $id_categoria)
              ->where('id_tipo_atencion', $id_tipo_atencion)
              ->where('id_nivel', $id_nivel)
              ->where('estado', 1)
              ->get();

    if ($labnivelatenciones && count($labnivelatenciones) > 0) {
      return response()->json([
        'success' => false,
        'message' => "No se ha podido registrar el examen de laboratorio, ya existe en este nivel de atención",
      ], JsonResponse::HTTP_BAD_REQUEST);
    }

    $model_laboratorio->RegistrarRuaNivelAtencion($id_procedimiento, $id_tipo_atencion, $id_nivel, $id_categoria, $descripcion_corta, $id_user, null, null, null);

    return response()->json([
      'success' => true,
      'message' => "Se agrego correctamento el examen de laboratorio a este nivel de atención",
    ], JsonResponse::HTTP_CREATED);
  }

  public function cambiarEstadoNive1y2(Request $request, $id_labnivelatencion)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    
    $nuevo_estado = $request->nuevo_estado;

    $labnivelatencion = Labnivelatencion::where('id', $id_labnivelatencion)->first();
    if (!$labnivelatencion) {
      return response()->json([
        'success' => false,
        'message' => "No existe el rua",
      ], JsonResponse::HTTP_BAD_REQUEST);
    }

    $labnivelatencion->update([
      'estado' => $nuevo_estado,
      'id_user_update' => Auth::user()->id,
      'updated_at' => Carbon::now(),
    ]);

    return response()->json([
      'success' => true,
      'message' => "Se actualizo el estado correctamente",
    ], JsonResponse::HTTP_OK);
  }

  public function agregar_categoria_rua_atencion_nivel_1_2(Request $request)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $model_laboratorio = new \App\Models\Laboratorio;

    $id_user = Auth::user()->id;
    $id_categoria = $request->id_categoria;
    $tipo = $request->tipo;
    $nivel = $request->nivel;
    $orden = $request->orden;
    $categoriaRuaNivel = CategoriaRuaNivel::where('id_categoria_rua', $id_categoria)
              ->where('id_atencion', $tipo)
              ->where('id_nivel', $nivel)
              // ->where('orden', $orden)
              ->where('estado', 1)
              ->get();
    if ($categoriaRuaNivel && count($categoriaRuaNivel) > 0) {
      return response()->json([
        'success' => false,
        'message' => "No se ha podido registrar la categoria rua, ya existe",
      ], JsonResponse::HTTP_BAD_REQUEST);
    }
    $model_laboratorio->RegistrarCategoriaRuaNivelAtencion($id_categoria, $tipo, $nivel, $orden, $id_user, null);
    return response()->json([
      'success' => true,
      'message' => "Se agrego correctamento la categoria rua",
    ], JsonResponse::HTTP_CREATED);
  }

  public function getCategoriaRuaNivelById($id_categoria_rua_nivel){
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $model_laboratorio = new Laboratorio();

    $categoriaNivelAtencionRua = $model_laboratorio->getCategoriaNivelAtencionRua($id_categoria_rua_nivel);

    return response()->json([
      'success' => true,
      'message' => "exito",
      'data' => $categoriaNivelAtencionRua
    ], JsonResponse::HTTP_OK);
  }

  function updateCategoriaRuaNivel(Request $request, $id) {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    $id_categoria = $request->id_categoria;
    $tipo = $request->tipo;
    $nivel = $request->nivel;
    $orden = $request->orden;

    $categoriaRuaNivel = CategoriaRuaNivel::where('id_categoria_rua', $id_categoria)
          ->where('id_atencion', $tipo)
          ->where('id_nivel', $nivel)
          // ->where('orden', $orden)
          // ->where('estado', 1)
          ->orderBy('id', 'desc')
          ->first();
    
    if ($categoriaRuaNivel && intval($categoriaRuaNivel->id) !== intval($id)) {
      $message = "No fue posible actualizar porque esta categoría RUA ya está registrada.";
      if ($categoriaRuaNivel->estado == 0) {
        $message = "Ya existe una categoría RUA con los mismos datos, pero se encuentra desactivada.";
      }
      return response()->json([ 'success' => false, 'message' => $message ], JsonResponse::HTTP_BAD_REQUEST);
    }
    
    $categoriaRuaNivelUpdate = CategoriaRuaNivel::where('id', $id)->first();
    if (!$categoriaRuaNivelUpdate) {
      return response()->json([ 'success' => false, 'message' => "No existe la categoria RUA", ], JsonResponse::HTTP_BAD_REQUEST);
    }
    $categoriaRuaNivelUpdate->update([
      'id_categoria_rua' => $id_categoria,
      'orden' => $orden,
      'id_user_update' => $id_user,
      'updated_at' => Carbon::now(),
    ]);

    return response()->json([
      'success' => true,
      'message' => "Se actualizo la categoria RUA exitosamente",
    ], JsonResponse::HTTP_OK);
  }

  public function generateRUAPdf($id_laboratorio)
  {
    if (!Auth::check()) {
      return redirect('/auth/login');
    }

    $model_prestacion = new Prestaciones();
    $model_laboratorio = new Laboratorio();
    $model_cita = new Cita();
    $model_consultorio = new Consultorio();
    $model_establecimiento = new Establecimiento();

    $laboratorio = $model_laboratorio->GetLaboratorioById($id_laboratorio);
    $prestacion = $model_prestacion->GetPrestacionById($laboratorio->id_prestacion);
    $establecimiento = $model_establecimiento->GetById(auth()->user()->id_establecimiento );
    $consultorio = $model_consultorio->GetNombreById($prestacion->id_consultorio);
    $Provincia = $model_establecimiento->GetProvinciaForUbego($establecimiento[0]->ubigeo);
    $proxima_cita = $model_cita->GetCitasById($prestacion->id_cita_lab);
    $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacionEvaluacion($laboratorio->id_prestacion, $laboratorio->id_evaluacion);

    $id_nivel = $establecimiento && count($establecimiento) > 0 ? $establecimiento[0]->nivel : null;
    $id_tipo_atencion = $prestacion ? $prestacion->id_tipo_atencion : null;
    if($id_tipo_atencion == '7'){
      $id_tipo_atencion = '1';
    }
    $lista_rua = $model_laboratorio->getVerRuaConItemPorIdPrestacionNivel12($id_nivel, $id_tipo_atencion, $prestacion->id, $id_laboratorio);

    $count_examns_processed = 0;
    $count_sin_reactivo = 0;
    $lista_rua_with_header = [];
    if ($lista_rua && count($lista_rua) > 0) {
      foreach ($lista_rua as $key => $proc) {
        if ($key === 0 || intval($proc->id_categoria) !== $lista_rua[$key - 1]->id_categoria) {
          $array_proc = (array) $proc;
          $array_proc['is_header'] = true;
          $lista_rua_with_header[] = (object) $array_proc;
        }
        $lista_rua_with_header[] = $proc;
        if ($proc->id_rua) {
          $count_examns_processed++;
        }
        if (intval($proc->confirmar_toma) == 1 || intval($proc->confirmar_toma) == 4) {
          $count_sin_reactivo++;
        }
      }
    }

    $array_lista_rua = array_chunk($lista_rua_with_header, 30);

    $carpeta = date("Y")."/".date("m")."/".date("d");
    $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_laboratorio_".$id_laboratorio.".pdf";
    $codigo_qr = QrCode::format('png')->size(150)->generate("https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_laboratorio_".$id_laboratorio.".pdf");
    $pdf = \PDF::loadView('pdf.firmar_laboratorio_nivel_1_2',compact(
      'laboratorio',
      'prestacion',
      'establecimiento',
      'consultorio',
      'Provincia',
      'proxima_cita',
      'diagnostico',
      'array_lista_rua',
      'count_examns_processed',
      'count_sin_reactivo'
    ));

    $pdf->setPaper('A4', 'landscape');
    $pdf->getDomPDF()->set_option("enable_php", true);
    
    return $pdf->stream('laboratorio.pdf');
  }

  public function reprogramarCitaNivel12(Request $request, $id_laboratorio) {
    if (!Auth::check()) {
      return redirect('/auth/login');
    }

    $idSubConsultorio = $request->idSubConsultorio;
    $fechaHora = $request->fechaHora;

    $laboratorio = Laboratorio::where('id', $id_laboratorio)->first();
    if (!$laboratorio) {
      return response()->json([
        'success' => false,
        'message' => "No existe la orden de laboratorio"
      ], JsonResponse::HTTP_NOT_FOUND);
    }

    if (intval($laboratorio->estado_n_1_2) === 1 || intval($laboratorio->estado_n_1_2) === 2) {
      return response()->json([
        'success' => false,
        'message' => "No se puede reprogramar la cita, ya que la orden de laboratorio ya fue atendida o cancelada."
      ], JsonResponse::HTTP_BAD_REQUEST);
    }

    $prestacion = Prestaciones::select('id', 'id_cita', 'id_tipo_atencion', 'id_establecimiento', 'id_sub_consultorio')->where('id', $laboratorio->id_prestacion)->first();
    if (!$prestacion || !$prestacion->id_tipo_atencion) {
      return response()->json([
        'success' => false,
        'message' => "No existe la prestación asociada a la orden de laboratorio"
      ], JsonResponse::HTTP_NOT_FOUND);
    }

    if (intval($prestacion->id_tipo_atencion) < 1 || intval($prestacion->id_tipo_atencion) > 3 || !isset($prestacion->id_cita)) {
      return response()->json([
        'success' => false,
        'message' => "Tipo de atención no válido para reprogramación"
      ], JsonResponse::HTTP_BAD_REQUEST);
    }

    $cita = Cita::select('id', 'fecha_hora', 'id_sub_consultorio', 'id_estado_cita', 'id_cita_lab', 'id_cita_laboratorio_ce')->where('id', $prestacion->id_cita)->first();

    if (intval($prestacion->id_tipo_atencion) === 1) {
      if (empty($cita->id_cita_laboratorio_ce)) {
        return response()->json([
          'success' => false,
          'message' => "No existe la cita asociada a la orden de laboratorio"
        ], JsonResponse::HTTP_NOT_FOUND);
      }
      DB::update('UPDATE citas SET fecha_hora = ?, id_sub_consultorio = ?, id_estado_cita = ? WHERE id = ?', [$fechaHora, $idSubConsultorio, 10, $cita->id_cita_laboratorio_ce]);
      DB::update('UPDATE citas_llamados SET fecha_hora = ?, id_sub_consultorio = ? WHERE id_cita = ?', [$fechaHora, $idSubConsultorio, $cita->id_cita_laboratorio_ce]);
    }

    if (intval($prestacion->id_tipo_atencion) === 2 || intval($prestacion->id_tipo_atencion) === 3) {
      DB::update('UPDATE citas SET id_estado_cita = ? WHERE id = ?', [10, $cita->id]);
    }

    $laboratorio->update([
      'updated_at' => Carbon::now(),
      'updated_us' => Auth::user()->id,
      'estado_n_1_2' => 3
    ]);
    
    return response()->json([
      'success' => true,
      'message' => "Se reprogramó la cita de laboratorio exitosamente.",
    ], JsonResponse::HTTP_OK);
  }

  public function anularNivel1y2(Request $request, $id_laboratorio) {
    try {
      if (!Auth::check()) {
        return redirect('/auth/login');
      }
      $motivo = $request->motivo;
      $idCita = $request->idCita;
      
      $cita = Cita::where('id', $idCita)->first();
      if (!$cita) {
        return response()->json([ 'success' => false, 'message' => "No existe la cita" ], JsonResponse::HTTP_NOT_FOUND);
      }
      $cita->update([
        'id_estado_reg' => 0,
        'id_estado_cita' => 5,
        'updated_at' => Carbon::now(),
        'updated_us' => Auth::user()->id,
        'estado_asistencia_paciente' => 1,
        'obs_asistencia_paciente' => "Anulado : " . $motivo ? $motivo : null,
        // 'id_cita_laboratorio_ce' => null,
        // 'flag_cita_laboratorio_ce' => 0
      ]);
      
      $laboratorio = Laboratorio::where('id', $id_laboratorio)->first();
      if (!$laboratorio) {
        return response()->json([ 'success' => false, 'message' => "No existe la orden de laboratorio" ], JsonResponse::HTTP_NOT_FOUND);
      }
      $laboratorio->update([
        'estado_asistencia_paciente' => 1,
        'estado_n_1_2' => 2,
        'updated_at' => Carbon::now(),
        'updated_us' => Auth::user()->id,
        'obs_asistencia_paciente' => "Anulado : " . $motivo ? $motivo : null,
      ]);

      return response()->json([
        'success' => true,
        'message' => "Se anuló la orden de laboratorio exitosamente.",
      ], JsonResponse::HTTP_OK);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => "Error al anular la orden de laboratorio: " . $e->getMessage()
      ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  public function generateReportOrdenesNivel1y2(Request $request) {
    if (!Auth::check()):
      return redirect('/auth/login');
    endif;

    $id_user = $request->query('id_user');
    $id_establecimiento = Auth::user()->id_establecimiento;
    $fechaDesde = $request->query('fechaDesde');
    $fechaHasta = $request->query('fechaHasta');
    $dni_beneficiario = strtoupper($request->query('dni_beneficiario'));
    $referido = $request->query('referido');
    $tipo_atencion = $request->query('tipo_atencion');
    $id_servicio = $request->query('id_servicio');
    $nro_examen = $request->query('nro_examen');
    $id_prioridad = $request->query('id_prioridad');
    $estado = $request->query('estado');
                      $id_farmacia = $request->query('id_farmacia');

    $filters = [
      "id_tipo_atencion" => $tipo_atencion,
      "id_establecimiento" => $id_establecimiento,
      "fecha_inicio" => Carbon::createFromFormat('d-m-Y', $fechaDesde)->format('Y-m-d'),
      "fecha_fin" => Carbon::createFromFormat('d-m-Y', $fechaHasta)->format('Y-m-d'),
      "nro_doc_ident" => $dni_beneficiario,
      "id_consultorio" => $id_servicio,
      "id_prioridad" => $id_prioridad,
      "referido" => $referido,
      "cod_orden_lab" => $nro_examen,
      "estado_n_1_2" => $estado && intval($estado) !== 3 ? $estado : ""
    ];

    $model_laboratorio = new Laboratorio();
    $model_establecimiento = new Establecimiento();

    $ordenes = $model_laboratorio->getOrdenLaboratorioByFiltersNivel12($filters);

    $establecimiento = $model_establecimiento->GetById(Auth::user()->id_establecimiento);
    $nombre_establecimiento = $establecimiento[0]->nombre;

    $descriptions = [
      [$nombre_establecimiento],
      ["REPORTE DE ÓRDENES DE LABORATORIO"],
      ["Fecha Solicitud Desde: " . $fechaDesde],
      ["Fecha Solicitud Hasta: " . $fechaHasta],
      ["Total: " . count($ordenes) . " órdenes de laboratorio"],
      ["Usuario: " . Auth::user()->name],
      [""]
    ];

    $headers = [
      'N°',
      'Nro.Prestación',
      'Nro.Ex. Lab',
      'Tipo Atención',
      'Especialidad',
      'Reg.Lab',
      'Fec.Cita Lab',
      'Fec.Cita Prox.Servicio',
      'Beneficiario',
      'Medico',
      'Referido',
      'Estado'
    ];

    $data = [];
    if ($ordenes && count($ordenes) > 0) {
      foreach ($ordenes as $key => $orden) {
        $beneficiario = isset($orden->nombre_asegurado) ? $orden->nombre_asegurado : "";
        $beneficiario .= isset($orden->apellido_paterno_asegurado) ? " " . $orden->apellido_paterno_asegurado : "";
        $beneficiario .= isset($orden->apellido_materno_asegurado) ? " " .$orden->apellido_materno_asegurado : "";

        $medico = isset($orden->nombre_medico) ? $orden->nombre_medico : "";
        $medico .= isset($orden->paterno_medico) ? " " . $orden->paterno_medico : "";
        $medico .= isset($orden->materno_medico) ? " " . $orden->materno_medico : "";

        $estado = "PENDIENTE";
        switch (intval($orden->laboratorio_estado)) {
            case 0: $estado = "PENDIENTE"; break;
            case 1: $estado = "ATENDIDO"; break;
            case 2: $estado = "ANULADO"; break;
            case 3: $estado = "REPROGRAMADO"; break;
            default: $estado = "PENDIENTE"; break;
        }

        $data[] = [
          isset($orden->id_laboratorio) ? $orden->id_laboratorio : "",
          isset($orden->id_prestacion) ? $orden->id_prestacion : "",
          isset($orden->cod_orden_lab) ? $orden->cod_orden_lab : "",
          isset($orden->tipo_atencion) ? $orden->tipo_atencion : "",
          isset($orden->consultorio) ? $orden->consultorio : "",
          isset($orden->laboratorio_registro) ? Carbon::parse($orden->laboratorio_registro)->format('d-m-Y H:i') : "",
          isset($orden->fecha_cita_laboratorio) ? Carbon::parse($orden->fecha_cita_laboratorio)->format('d-m-Y H:i') : "",
          isset($orden->fecha_cita_proxima_laboratorio) ? Carbon::parse($orden->fecha_cita_proxima_laboratorio)->format('d-m-Y H:i') : "",
          $beneficiario,
          $medico,
          $referido,
          $estado
        ];
      }
    }

    $exportData = array_merge($descriptions, [$headers], $data);

    Excel::create('Exportar Reporte de Ordenes de Laboratorio', function($excel) use ($exportData, $descriptions) {
      $excel->sheet('Ordenes', function($sheet) use ($exportData, $descriptions) {
        $sheet->rows($exportData);

        $headerRow = count($descriptions) + 1;

        $sheet->row($headerRow , function($row) {
            $row->setBackground('#d9edf7');
            $row->setFontWeight('bold');
        });

        $sheet->setAutoSize(true);
      });
    })->download('xlsx');
  }

  public function editOrdenLabNivel1y2(Request $request, $id_laboratorio) {
    if (!Auth::check()):
      return redirect('/auth/login');
    endif;
    $resultados = $request->examenes;

    $laboratorio = Laboratorio::where('id', $id_laboratorio)->first();
    if (!$laboratorio) {
      return response()->json([ 'success' => false, 'message' => "No existe la orden de laboratorio" ], JsonResponse::HTTP_NOT_FOUND);
    }
    $laboratorio->update([
      'observacion_toma_muestra' => $request->observacion_toma_muestra,
      'updated_at' => Carbon::now(),
      'updated_us' => Auth::user()->id
    ]);

    foreach ($resultados as $resultado) {
      $id_prestacion_procedimiento = $resultado['id_prestacion_procedimiento'];
      if ($resultado['confirmar_toma'] == '0' || $resultado['confirmar_toma'] == '3') {
        $resultado['examen_procesado'] = 1;
      }
      if (
        isset($resultado['archivos']) && 
        is_array($resultado['archivos']) && 
        count($resultado['archivos']) && 
        isset($id_prestacion_procedimiento) && 
        isset($resultado['examen_procesado']) && 
        intval($resultado['examen_procesado']) === 1
      ) {
        foreach ($resultado['archivos'] as $key => $file) {
          $timestamp = Carbon::now()->format('YmdHis');
          $uniqueId = uniqid();
          $extension = $file->getClientOriginalExtension();
          $original_name = $file->getClientOriginalName();
          $size = $file->getSize();

          $directorio = 'laboratorio/resultados';
          $nombreArchivo = "RL_{$timestamp}_{$uniqueId}." . strtolower($extension);
          $rutaRelativa = $directorio . '/' . $nombreArchivo;

          $contenido = file_get_contents($file->getRealPath());

          if (!Storage::disk('local')->exists($directorio)) {
            Storage::disk('local')->makeDirectory($directorio);
          }

          Storage::disk('local')->put($rutaRelativa, $contenido);

          $pathFile = $rutaRelativa;

          $newLaboratorioArchivosResultado = LaboratorioArchivosResultado::create([
            'id_prestacion_procedimiento' => $id_prestacion_procedimiento,
            'uri' => $pathFile,
            'original_name' => $original_name,
            'extension' => strtolower($extension),
            'size' => $size,
            'created_at' => Carbon::now(),
            'created_us' => Auth::user()->id
          ]);
        }
      }
      if (isset($resultado['examen_procesado']) && intval($resultado['examen_procesado']) === 1) {
        $prestacionProcedimiento = Prestacion_procedimiento::where('id', $id_prestacion_procedimiento)->first();
        if ($prestacionProcedimiento) {
          $prestacionProcedimiento->update([ 
            'confirmar_toma' => '0',
            'confirmar_toma_created_us' => Auth::user()->id,
            'confirmar_toma_created_at' => Carbon::now()
          ]);
        }
      }
    }
    
    // foreach ($resultados as $resultado) {
    //   $pathFile = null;
    //   if (isset($resultado['archivo']) && $resultado['archivo']) {
    //     $file = $resultado['archivo'];
    //     $file_name = $resultado['archivo_nombre'];
    //     if (!$file || !$file->isValid()) {
    //       return response()->json([ 'success' => false, 'message' => "Error, Documentos erroneos" ], JsonResponse::HTTP_BAD_REQUEST);
    //     }

    //     $extension = pathinfo($file_name, PATHINFO_EXTENSION);
    //     $timestamp = Carbon::now()->format('YmdHis');
    //     $uniqueId = uniqid();

    //     $directorio = 'laboratorio/resultados';
    //     $nombreArchivo = "RL_{$timestamp}_{$uniqueId}." . strtolower($extension);
    //     $rutaRelativa = $directorio . '/' . $nombreArchivo;

    //     $contenido = file_get_contents($file->getRealPath());

    //     if (!Storage::disk('local')->exists($directorio)) {
    //       Storage::disk('local')->makeDirectory($directorio);
    //     }

    //     Storage::disk('local')->put($rutaRelativa, $contenido);

    //     $pathFile = $rutaRelativa;
    //   }

    //   $prestacionProcedimiento = Prestacion_procedimiento::where('id', $resultado['id_prestacion_procedimiento'])->first();
    //   if ($prestacionProcedimiento) {
    //     $prestacionProcedimiento->update([
    //       'confirmar_toma' => '0',
    //       'archivo_resultado' => $pathFile
    //     ]);
    //   }
    // }

    session()->flash('success', "Se actualizo exitosamente la atención de la orden de laboratorio.");
    return response()->json([
      'success' => true,
      'message' => 'Se actualizo exitosamente la atención de la orden de laboratorio.',
    ], JsonResponse::HTTP_OK); 
  }

  public function getExamenesByIdLaboratorio($id_laboratorio, $id_prestacion){
     $model_prestacion = new Prestaciones();
     $examenes = $model_prestacion->GetPrestacionLaboratorio($id_prestacion, $id_laboratorio);

     if ($examenes && count($examenes) > 0) {
      foreach ($examenes as $key => $examen) {
        $archivosResultados = LaboratorioArchivosResultado::where('id_prestacion_procedimiento', $examen->id_prestacion_procedimiento)->where('estado', true)->get();
        $examen->files = $archivosResultados;
      }
    }
    return response()->json([
      'success' => true,
      'message' => "Se realizo la consulta exitosamente",
      'data' => $examenes
    ], JsonResponse::HTTP_OK);
  }

  public function modal_historial_laboratorio_nivel_1_2($dni_beneficiario){
    $model_laboratorio = new Laboratorio;

    $asegurado = Asegurado::where("nro_doc_ident", $dni_beneficiario)->first();

    $ordenes = $model_laboratorio->GetHistorialAtencionesByIdAsegurado($asegurado->id);
    
    return view('frontend.laboratorios.modal_historial_laboratorio_nivel_1_2',compact('ordenes'));
  }

  public function GetAllOrdenesEmergencia($establecimiento_id, $fecha_inicio, $fecha_fin, $paciente_nro_documento, $examen_nro, $id_tipo_atencion, $servicio_id, $referido, $emergencia_prioridad, $estado, $farmacia_id, $paciente_apellido_nombres, $paraExportacion = false)
  {
    $model_laboratorio = new Laboratorio();
    $lista = $model_laboratorio->AllExamenLaboratorioEmergenciaList($establecimiento_id, $fecha_inicio, $fecha_fin, $paciente_nro_documento, $examen_nro, $id_tipo_atencion, $servicio_id, $referido, $emergencia_prioridad,$estado, $farmacia_id, $paciente_apellido_nombres, $paraExportacion);

    /*foreach ($lista as $r) {
      if ($r->id_evaluacion > 0) {
        $model_evaluacion = new Evaluacione();
        $evaluacion = $model_evaluacion->GetEvaluacionByIdEmergencia($r->id_evaluacion);
        $r->medico_evaluacion = $evaluacion->medico_paterno . " " . $evaluacion->medico_materno . ", " . $evaluacion->medico_nombre;
      }
    }*/

    return $lista;
  }

  public function laboratorio_orden_anular_json(Request $request)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    DB::beginTransaction();
    $anulacion_valida = false;
    $mensaje_error = "";

    try {
      $laboratorio = Laboratorio::find($request->laboratorio_orden_id);
      //$rowLaboratorio->estado == 1 && $rowLaboratorio->flag == 0 && is_null($rowLaboratorio->flag_toma_muestra

      if ($laboratorio) {
        if ($laboratorio->flag > 0) {
          // Orden firmada
          throw new \Exception("La Orden de Laboratorio ya fue firmada, no es posible anularla.");
        } elseif ($laboratorio->estado == 0) {
          // Ya se encuentra anulada
          throw new \Exception("La Orden de Laboratorio ya fue anulada en otra oportunidad, no es posible anularla.");
        } elseif (!is_null($laboratorio->flag_toma_muestra)) {
          // Ya se realizo la toma de muestra
          throw new \Exception("La Orden de Laboratorio ya pasó por la toma de muestra, no es posible anularla.");
        } else {
          $laboratorio->estado = 0;
          $laboratorio->deleted_at = Carbon::now();
          $laboratorio->deleted_us = Auth::user()->id;
          $laboratorio->deleted_motivo = $request->laboratorio_orden_motivo_anula;
          if (!$laboratorio->save()) {
            throw new \Exception("La Orden de Laboratorio no pudo ser anulada, intente nuevamente.");
          }
        }
      } else {
        throw new \Exception('La Orden de Laboratorio no se ha encontrado (' . $request->laboratorio_orden_id . ').');
      }

      DB::commit();
      $anulacion_valida = true;

    } catch (\Exception $e) {
      DB::rollback();
      Log::error("Anulacion Laboratorio Orden => id: " . $request->laboratorio_orden_id . 
          "\n\nmensaje: " . $e->getMessage() . 
          "\n\ndatos del request: " . json_encode($request->all()) . 
          "\n\nstack trace: " . $e->getTraceAsString()
      );
      $mensaje_error = $e->getMessage();
    } catch (\Throwable $th) {
      DB::rollback();
      Log::critical("Anulacion Laboratorio Orden => id: " . $request->laboratorio_orden_id . 
          "\n\nmensaje: " . $th->getMessage() . 
          "\n\ndatos del request: " . json_encode($request->all()) . 
          "\n\nstack trace: " . $th->getTraceAsString()
      );
      $mensaje_error = $th->getMessage();
    }

    if ($anulacion_valida):
      return response()->json([
        'success' => true,
        'message' => "La Orden de Laboratorio fue anulada correctamente."
      ]);
    else:
      return response()->json([
        'success' => false,
        'message' => $mensaje_error
      ]);
    endif;
  }

  public function registrar_examen_laboratorio_lns_agrupado(Request $request) {

    if (!Auth::check()):
        return redirect('/auth/login');
    endif;

    $registro_validado = false;
    $mensaje_adicional = "";
    DB::beginTransaction();
    try {

      if ($request->prueba != null) {

        $id_servicio = $request->input('id_servicio');
        $prueba      = $request->input('id_rua');
        $pendiente   = $request->input('pendiente');
        $id_cita_lab_agrupado = $request->input('id_cita');
        $cpms = $request->input('cpms');

        $ordenes = [];
        foreach ($id_servicio as $index => $idOrden) {
          $ordenes[$idOrden][] = [
            'prueba'    => $prueba[$index],
            'pendiente' => $pendiente[$index],
          ];
        }
      
        $id_user = Auth::user()->id;
        $ws_laboratorio = new \App\Ws\LaboratorioLNS();
        $obj_laboratorio = new \App\Models\Laboratorio();

        Log::info("Array disticnt de ordenes: " . json_encode($ordenes));

        $ordenes_id_2 = $obj_laboratorio->GetOrdenesPorCitaAgrupada($id_cita_lab_agrupado);

        foreach ($ordenes as $id_laboratorio => $detalle):

          $prueba = [];
          $pendiente = [];
          
          foreach ($detalle as $row_detalle):
            $prueba[] = $row_detalle['prueba'];
            $pendiente[] = $row_detalle['pendiente'];
          endforeach;

          $laboratorio = Laboratorio::find($id_laboratorio);

          Log::info("Id Laboratorio: " . $id_laboratorio);
          Log::info("Id prestacion obtenida: " . $laboratorio->id_prestacion);

          $response = $obj_laboratorio->RegistrarPrestacionProcedimientosProveedor(0, $id_laboratorio, $pendiente, $prueba);
          if ($response):
            $secuencial = $ws_laboratorio->CallVerSecuencialLaboratorio();

            if($secuencial->codigoMensaje=='00'):
                Log::info("Laboratorio 1: genero secuencial : secuencial: " . $secuencial->descripcionMensaje);
                Log::warning("id_orden_lab_todas, ahora ordenes_id_2: " . json_encode($ordenes_id_2));
                $res = $obj_laboratorio->ConfirmarTomaMuestra($laboratorio->id_prestacion, 2, $prueba, strtoupper($request->observacion), $id_user, $secuencial->descripcionMensaje,$id_laboratorio, $request->cod_siteds_susalud, $pendiente, $ordenes_id_2);

                Log::info("Resultado de Confirma Toma Mmuestra: " . $res);
                if ($res == 'Y') {
                    Log::info("Laboratorio 2: confirmo toma de muestra");
                    //================================================================================================
                    if (env('CONEXION_WS_LAB') == "true") {
                        //Obtener para el LIS
                        $datos = $obj_laboratorio->GetDataParaLIS($laboratorio->id_prestacion, $id_laboratorio, $request->id_tipo_atencion, $request->id_evaluacion);
                        Log::info("Laboratorio 3: se obtuvo la data para enviar , cantidad de datos : " . count($datos));
                        Log::info("Laboratorio 3.1: " . json_encode($datos));
                        if(count($datos) == 0){
                          Log::error("No hay datos para enviar.");
                          throw new \Exception("No hay datos para enviar.");
                        }
                        $lista = array();
                        foreach ($datos as $data) {
                            $obj = new \App\Models\InterLabHcentral();

                            $obj->id = 0;
                            $obj->idOrigen        = $data->id_tipo_atencion;
                            $obj->fechaMuestra    = $data->fecha_atencion;
                            $obj->idProcedencia   = $data->id_establecimiento;  // DEBE ENCAJAR CON LA BD "HNPNPLNS" DE POSTGRES, ESTA CONFIGURADO EN LABCORE
                            $obj->idDepartamento  = 0; // NO HAY ID DEPARTAMENTO, VER SI AFECTA AL LABCORE
                            $obj->idServicio      = (intval($data->id_tipo_atencion) === 2 ? $data->id_servicio : $data->id_consultorio); // id_tipo_atencion 2 : Hospitalizacion para Hospital LNS
                            $obj->dniMedico       = $data->ndoc_medico;
                            $obj->nombreMedico    = $data->nombre_medico;
                            $obj->dniProfLab      = "";
                            $obj->nombreProfLab   = "";
                            $obj->historiaClinica = $data->nro_historia;

                            $obj->dniPaciente     = $data->nro_doc_ident;
                            $obj->nombrePaciente  = $data->nombre_paciente;
                            $obj->genero          = $data->sexo_paciente;
                            $obj->fechaNac        = $data->fnac_paciente;
                            $obj->parentesco      = $data->parentesco_paciente;
                            $obj->grado           = $data->grado == null ? "":$data->grado;
                            $obj->situacionTitular = $data->paciente_situacion  == null ? "":$data->paciente_situacion; 
                            $obj->condicionUpss   = $data->upss;
                            $obj->codigoDiag      = $data->codigo_cie;
                            $obj->diagnostico     = $data->descripcion_cie  == null ? "":$data->descripcion_cie;

                            $obj->tipoDiag        = $data->tipo_diagnostico;
                            $obj->idDetalle       = $data->id_prestacion;
                            $obj->idServicioAnalisis = 0;
                            $obj->codigoProced    = $data->cpms_personalizado;
                            $obj->procedimiento   = $data->desc_procedimiento ==null ? "":$data->desc_procedimiento;
                            $obj->estadoLab       = 0;
                            $obj->nombrePc        = "";
                            $obj->fechaRegistro   = $data->fecha_registro_orden;  // HECHO - FECHA REGISTRO DEL RUA, DEBERIA OBTNERLO DE LA BD, VERIFICAR DE QUE CAMPO
                            $obj->observacion     = $data->observacion; // HECHO - DEBE OBTENERLO DE BD, AL MOMENTO DE REGISTRAR EL RUA, SE REGISTRA LA OBSERVACION
                            $obj->secuencia       = intval($secuencial->descripcionMensaje);//$data->secuencial; HECHO - DEBE OBTENERLO DE BD, AL MOMENTO DE REGISTRAR EL RUA

                            $obj->nombreUsuario   = $data->nombre_usuario; // USUARIO QUE REGISTRA EL RUA PARA REALIZAR LA PRUEBA
                            $obj->fechaHoraCrea = "";
                            $obj->apepaternoPaciente = $data->apepaterno_paciente;
                            $obj->apematernoPaciente = $data->apematerno_paciente;
                            $obj->prenombresPaciente = $data->prenombres_paciente;
                            $obj->codOrden = "";
                            $obj->codigo_proveedor = $data->codigo_proveedor;
                            $obj->codigo_proced_alterno = $data->cod_procedimiento;
                            $obj->validado = "";
                            $obj->estadoLabRoche = 0;
                            $obj->estadoProveedor = 0;
                            $obj->origenData = "";
                            $obj->codigoMensaje = "";
                            $obj->descripcionMensaje = "";

                            $lista[] = $obj;
                        }
                        // dd($lista);
                        if(!isset($lista)){
                          Log::error("La lista no existe.");
                          throw new \Exception("La lista no existe.");
                        }
                        
                        $respuesta = $ws_laboratorio->InsertarOrdenLaboratorioNuevo($lista);
                        
                        // Validar que la respuesta sea un objeto válido antes de acceder a sus propiedades
                        if (is_string($respuesta)) {
                            // Si la respuesta es un string, significa que hubo un error en el web service
                            Log::error("Error en web service LNS: " . $respuesta);
                            throw new \Exception("Error en el servicio web LNS: " . $respuesta);
                        }
                        
                        if ($respuesta->codigoMensaje == '00') {
                            Log::info("Laboratorio 5: inserto la data a la tabla intermedia");
                            $response = $obj_laboratorio->updateEstadoMigrate(2, $prueba, $id_laboratorio, $pendiente);
                            if (!$response) {
                              
                              Log::error("No se ha podido actualizar el flag de 'migrado'. Intente nuevamente.");
                              throw new \Exception("No se ha podido actualizar el flag de 'migrado'. Intente nuevamente.");
                              
                            } else {
                              $laboratorio_cod_orden = Laboratorio::find($id_laboratorio);
                              switch (count($lista)) {
                                case 1:
                                  $mensaje_adicional .= '<br>Se envió ' . count($lista) . ' examen a los proveedores para la orden <b>' . $laboratorio_cod_orden->cod_orden_lab . "</b>.";
                                  Log::info("Se realizó la actualización a Migrado de todos los examenes para la orden " . $laboratorio_cod_orden->cod_orden_lab . ".");
                                  break;
                                default:
                                  $mensaje_adicional .= '<br>Se envió ' . count($lista) . ' exámenes a los proveedores para la orden <b>' . $laboratorio_cod_orden->cod_orden_lab . "</b>.";
                                  Log::info("Se realizó la actualización a Migrado de todos los examenes para la orden " . $laboratorio_cod_orden->cod_orden_lab . ".");
                              }
                            }
                        
                        } else {
                            Log::info("Laboratorio 6: no se pudo registrar en la tabla intermedia");
                            throw new \Exception("No se pudo registrar los exámenes de Laboratorio. Código: ".$respuesta->codigoMensaje."/ Descripción: ".$respuesta->descripcionMensaje);
                        }

                    }

                } elseif ($res == 'P') {
                  $laboratorio_cod_orden = Laboratorio::find($id_laboratorio);
                  $mensaje_adicional .= '<br>Todos los exámenes eran Pendientes para la orden <b>' . $laboratorio_cod_orden->cod_orden_lab . "</b> y no se envió a los proveedores.";
                  Log::info("No se realizó la actualización a Migrado porque todos los examenes de la orden " . $laboratorio_cod_orden->cod_orden_lab . " eran pendientes.");
                } else {
                    Log::error("Laboratorio 7: no se registro los datos de toma de muestra");
                    throw new \Exception("Ha ocurrido un error al registrar los datos de Toma de Muestra.");
                }
            else:
                Log::error("Ha ocurrido un error al generar el Secuencial de la Orden.<br><br>Código: ".$secuencial->codigoMensaje."/ Descripción: ".$secuencial->descripcionMensaje);
                throw new \Exception("Ha ocurrido un error al generar el Secuencial de la Orden.<br><br>Código: ".$secuencial->codigoMensaje."/ Descripción: ".$secuencial->descripcionMensaje);
            endif;

          else:
            Log::error("No se ha podido registrar los codigo de proveedor.");
            throw new \Exception("No se ha podido registrar los codigo de proveedor.");
          endif;
        endforeach;

      } else {
        $id_user = Auth::user()->id;
        $obj_laboratorio = new \App\Models\Laboratorio();

        $res = $obj_laboratorio->ConfirmarTomaMuestraOrdenSinReactivo(strtoupper($request->observacion), $id_user, $request->id_laboratorio, $request->cod_siteds_susalud);
        
        if ($res == 'Y'):
          $mensaje_adicional = "<br>Se registró con éxito toda la orden sin reactivo.";
        else:
          Log::error("Ha ocurrido un error al registrar los datos de Toma de Muestra para la Orden sin reactivo.");
          throw new \Exception("Ha ocurrido un error al registrar los datos de Toma de Muestra para la Orden sin reactivo.");
        endif;
      }

      DB::commit();
      $registro_validado = true;

    } catch (\Exception $e) {
      DB::rollback();
      $mensaje_adicional = $e->getMessage();
      Log::error("Laboratorio: " . 
        "\n\nmensaje: " . $e->getMessage()
      );
    } catch (\Throwable $th) {
      DB::rollback();
      $mensaje_adicional = $th->getMessage();
      Log::critical("Laboratorio: " . 
        "\n\nmensaje: " . $th->getMessage()
      );
    }

    if ($registro_validado):
      Session::flash('flash_message', 'Se registró la Orden de Laboratorio con éxito.' . $mensaje_adicional);
      return redirect('/all_laboratorio_prescritos_by_ipress');
    else:
      Session::flash('flash_warning', '<strong>Error:</strong> No se pudo registrar la Orden de Laboratorio, vuelva a intentarlo nuevamente.<br><br>' . $mensaje_adicional);
      return redirect('/laboratorio_agrupado/0/'.$request->id_cita);
    endif;

  }

  public function editar_examen_laboratorio_lns_agrupado(Request $request) {

    if (!Auth::check()):
        return redirect('/auth/login');
    endif;//dd($request);

    $registro_validado = false;
    $mensaje_adicional = "";
    
    DB::beginTransaction();
    try {
      if ($request->prueba != null) {
        
        $id_user = Auth::user()->id;
        $ws_laboratorio = new \App\Ws\LaboratorioLNS();
        $obj_laboratorio = new \App\Models\Laboratorio();
        $observacion = strtoupper($request->observacion_modificacion);

        $id_servicio = $request->input('id_servicio');
        $prueba      = $request->input('id_rua');
        $pendiente   = $request->input('pendiente');
        $id_prestacion_procedimiento = $request->id_prestacion_procedimiento;
        $id_cita_lab_agrupado = $request->input('id_cita');

        $ordenes = [];
        foreach ($id_servicio as $index => $idOrden) {
          $ordenes[$idOrden][] = [
            'prueba'    => $prueba[$index],
            'pendiente' => $pendiente[$index],
            'id_prestacion_procedimiento' => $id_prestacion_procedimiento[$index]
          ];
        }

        Log::info("Array disticnt de ordenes: " . json_encode($ordenes));
        foreach ($ordenes as $id_laboratorio => $detalle)://dd($detalle);

          $array_idrua = [];
          $array_pendiente = [];
          $array_id_prestacion_procedimiento = [];
          
          $ordenes_id_db = DB::table('citas as ci')
                        ->join('prestaciones as pr', 'ci.id', '=', 'pr.id_cita')
                        ->join('laboratorios as la', 'pr.id', '=', 'la.id_prestacion')
                        ->where('ci.id_cita_laboratorio_ce', $id_cita_lab_agrupado)
                        ->where('la.estado', 1)
                        ->orderBy('la.id')
                        ->lists('la.id');

          Log::info("Variable ordenes_id_db:" . json_encode($ordenes_id_db));
          $ordenes_id_2 = [];
          foreach ($ordenes_id_db as $laboratorio_id_db):
            $ordenes_id_2[] = $laboratorio_id_db;
          endforeach;

          foreach ($detalle as $row_detalle):
            $array_idrua[] = $row_detalle['prueba'];
            $array_pendiente[] = $row_detalle['pendiente'];
            $array_id_prestacion_procedimiento[] = $row_detalle['id_prestacion_procedimiento'];
          endforeach;

          $laboratorio = Laboratorio::find($id_laboratorio);

          $response = $obj_laboratorio->RegistrarPrestacionProcedimientosProveedor2($array_id_prestacion_procedimiento, $array_pendiente, $array_idrua, $id_laboratorio, $observacion, $ordenes_id_2);
          if ($response) {

              if (env('CONEXION_WS_LAB') == "true") {
                //Obtener para el LIS
                $datos = $obj_laboratorio->GetDataModificadaParaLIS($laboratorio->id_prestacion, $id_laboratorio, $request->id_tipo_atencion, $request->id_evaluacion);

                Log::info("Laboratorio 3: se obtuvo la data para enviar , cantidad de datos : " . count($datos));
                Log::info("Laboratorio 3.1: " . json_encode($datos));

                if (count($datos) > 0) {
                  $lista = [];
                  foreach ($datos as $data) {
                      $obj = new \App\Models\InterLabHcentral();

                      $obj->id = 0;
                      $obj->idOrigen        = $data->id_tipo_atencion;
                      $obj->fechaMuestra    = $data->fecha_atencion;
                      $obj->idProcedencia   = $data->id_establecimiento;  // DEBE ENCAJAR CON LA BD "HNPNPLNS" DE POSTGRES, ESTA CONFIGURADO EN LABCORE
                      $obj->idDepartamento  = 0; // NO HAY ID DEPARTAMENTO, VER SI AFECTA AL LABCORE
                      $obj->idServicio      = (intval($data->id_tipo_atencion) === 2 ? $data->id_servicio : $data->id_consultorio); // id_tipo_atencion 2 : Hospitalizacion para Hospital LNS
                      $obj->dniMedico       = $data->ndoc_medico;
                      $obj->nombreMedico    = $data->nombre_medico;
                      $obj->dniProfLab      = "";
                      $obj->nombreProfLab   = "";
                      $obj->historiaClinica = $data->nro_historia;

                      $obj->dniPaciente     = $data->nro_doc_ident;
                      $obj->nombrePaciente  = $data->nombre_paciente;
                      $obj->genero          = $data->sexo_paciente;
                      $obj->fechaNac        = $data->fnac_paciente;
                      $obj->parentesco      = $data->parentesco_paciente;
                      $obj->grado           = $data->grado == null ? "" : $data->grado;
                      $obj->situacionTitular = $data->paciente_situacion == null ? "" : $data->paciente_situacion; 
                      $obj->condicionUpss   = $data->upss;
                      $obj->codigoDiag      = $data->codigo_cie;
                      $obj->diagnostico     = $data->descripcion_cie  == null ? "" : $data->descripcion_cie;

                      $obj->tipoDiag        = $data->tipo_diagnostico;
                      $obj->idDetalle       = $data->id_prestacion;
                      $obj->idServicioAnalisis = 0;
                      $obj->codigoProced    = $data->cpms_personalizado;
                      $obj->procedimiento   = $data->desc_procedimiento == null ? "" : $data->desc_procedimiento;
                      $obj->estadoLab       = $data->estado_lab;
                      $obj->nombrePc        = '';
                      $obj->fechaRegistro   = $data->fecha_registro_orden;  // HECHO - FECHA REGISTRO DEL RUA, DEBERIA OBTNERLO DE LA BD, VERIFICAR DE QUE CAMPO
                      $obj->observacion     = $data->observacion; // HECHO - DEBE OBTENERLO DE BD, AL MOMENTO DE REGISTRAR EL RUA, SE REGISTRA LA OBSERVACION
                      $obj->secuencia       = $data->secuencial;   // HECHO - DEBE OBTENERLO DE BD, AL MOMENTO DE REGISTRAR EL RUA

                      $obj->nombreUsuario   = $data->nombre_usuario; // USUARIO QUE REGISTRA EL RUA PARA REALIZAR LA PRUEBA
                      $obj->fechaHoraCrea = "";
                      $obj->apepaternoPaciente = $data->apepaterno_paciente;
                      $obj->apematernoPaciente = $data->apematerno_paciente;
                      $obj->prenombresPaciente = $data->prenombres_paciente;
                      $obj->codOrden = "";
                      $obj->codigo_proveedor = $data->codigo_proveedor;
                      $obj->codigo_proced_alterno = $data->cod_procedimiento;
                      $obj->validado = "";
                      $obj->estadoLabRoche = 0;
                      $obj->estadoProveedor = 0;
                      $obj->origenData = "";
                      $obj->codigoMensaje = "";
                      $obj->descripcionMensaje = "";

                      $lista[] = $obj;
                  }
                  $respuesta = $ws_laboratorio->InsertarOrdenLaboratorioNuevo($lista);

                  if (isset($respuesta->codigoMensaje)) {

                    switch($respuesta->codigoMensaje) { //$respuesta->codigoMensaje
                      case "00":
                        $response = $obj_laboratorio->updateEstadoMigrate2($array_id_prestacion_procedimiento, $array_pendiente);
                        if (!$response) {
                          Log::error("No se ha podido actualizar el estado de los Examenes a Migrado.");
                          throw new \Exception("No se ha podido actualizar el estado de los Examenes a Migrado.");
                        } else {
                          switch (count($lista)) {
                            case 1:
                              $mensaje_adicional .= '<br>Se envió ' . count($lista) . ' examen a los proveedores para la orden <b>' . $laboratorio->cod_orden_lab . '</b>.';
                              Log::info("Se realizó la actualización a Migrado de todos los examenes.");
                              break;
                            default:
                              $mensaje_adicional .= '<br>Se envió ' . count($lista) . ' exámenes a los proveedores para la orden <b>' . $laboratorio->cod_orden_lab . '</b>.';
                              Log::info("Se realizó la actualización a Migrado de todos los examenes.");
                          }
                        }
                        break;
                      default:
                        throw new \Exception('<b>Código:</b> '.$respuesta->codigoMensaje.'/ Descripción: '.$respuesta->descripcionMensaje);
                    }

                  } else {
                    Log::error("No se ha podido enviar los exámenes a los proveedores.");
                    throw new \Exception("No se ha podido enviar los exámenes a los proveedores.");
                  }

                } else {
                  Log::info("La data obtenida para enviar a Tabla Intermedia esta vacia");
                }

              } else {
                throw new \Exception("No se encuentra habilitado la variable de entorno CONEXION_WS_LAB.");
              }

          } else {
            throw new \Exception("No se ha podido registrar los datos de la Toma de Muestra.");
          }
          
        endforeach;

      } else {
        throw new \Exception("Para registrar, debe seleccionar al menos una prueba de Laboratorio.");
      }

      DB::commit();
      $registro_validado = true;

    } catch (\Exception $e) {
        DB::rollback();
        Log::error("Laboratorio: " . 
            "\n\nmensaje: " . $e->getMessage()
        );
    } catch (\Throwable $th) {
        DB::rollback();
        Log::critical("Laboratorio: " . 
            "\n\nmensaje: " . $th->getMessage()
        );
    }
    if ($registro_validado):
      Session::flash('flash_message', 'Se modificó la Orden de Laboratorio con éxito.' . $mensaje_adicional);
      return redirect('/all_laboratorio_prescritos_by_ipress');
    else:
      Session::flash('flash_warning', '<strong>Error:</strong> No se pudo modificar la Orden de Laboratorio, vuelva a intentarlo nuevamente.<br><br>' . $e->getMessage());
      return redirect('/laboratorio_agrupado_editar/0/'.$request->id_cita);
    endif;
  }
}