<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Session;
use Carbon\Carbon;
use App\Models\Cita;
use App\Models\Menu;
use App\Http\Requests;
use App\Models\Farmacia;
use App\Models\Asegurado;
use App\Models\Radiologia;
use App\Models\Consultorio;
use App\Models\Prestaciones;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Plantilla_radiologia;
use Illuminate\Support\Facades\Route;
use App\Models\Radiologia_procedimiento;
use Illuminate\Support\Facades\Log;
use SoapClient;

class RadiologiaController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('auth'); 
    } 
    
    public function index() {
        //
		  
          
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
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
    public function editar_radiologia($id) {
        //
        $model_radiologia = new Radiologia();
        $procedimiento = $model_radiologia->GetRadiologiaPrestacionById($id);
        //$procedimiento = Radiologia::findOrFail($id); //estimacion_servicio
        //$procedimiento = Radiologia_procedimiento::findOrFail($id); //estimacion_servicio
//        dd($procedimiento);
        return $id;
        
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

    public function all_radiologia_prescritas_by_ipress($id_user = 0) {

        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;

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
        $id_tipo_atencion = 0;

        $date = Carbon::now();
        
        $fechaDesde = $date->format('d-m-Y');
        $fechaHasta = $date->format('d-m-Y');


        $fechaoDesde = $date->format('d-m-Y');
        $fechaoHasta = $date->format('d-m-Y');

        $fechamDesde = $date->format('d-m-Y');
        $fechamHasta = $date->format('d-m-Y');

        //==================== 
        $model_farma = new Farmacia();
        $farmacia = $model_farma->GetIpressByIduser($id_user);
        $id_establecimiento = $farmacia->id;
        $id_farmacia = "";//$farmacia->id;

		$farmacias = $model_farma->GetFarmaciaByIdEstablecimiento( auth()->user()->id_establecimiento );

        //====================
        $tipo_registro=0;
        //=====================
        $model_radiologia = new Radiologia();
        $radiologias = [];//$model_radiologia->AllExamenRadiologiaByEstablecimientoAndFechaDesdeHasta($id_establecimiento, $fechaDesde, $fechaHasta);
        $cantidad = 0;//$model_radiologia->GetCountExamenRadiologiaByFechaDesdeHasta($id_establecimiento, $fechaDesde, $fechaHasta);
        $servicio_examen = null;
        
        $model_atencione = new \App\Models\TipoAtencione;
        $tipo_atenciones = $model_atencione->listarTipoAtencionesLaboratorio();
        //Perfil para fiscalizador
        $tipo_perfil = 1;
        
        //Formatear variables de retorno
        $id_prioridad_temp = "";
        $id_servicio_temp = "";
        $id_servicio_imagenes_temp = "";
        $id_referido_temp = "";
        $id_estado_muestra_temp = "";
        
        $id_estado_informe_temp= "";

        $listServiciosImagenes = $this->listaServiciosImagenes();

        // if(Auth::user()->tipo_registro == 6 || Auth::user()->tipo_registro == 7 || Auth::user()->tipo_registro == 1):
        //     $servicio_examen = $model_radiologia->AllServicioExamenRadiologiaByEstablecimientoAndFechaDesdeHasta($id_establecimiento, $fechaDesde, $fechaHasta);
        // endif;

        $nivel_ipress = $model_farma->ObtenerNivelFarmacia(Auth::user()->id_farmacia);
        $nivel=$nivel_ipress->nivel;
        
        if($nivel==3):
            //====================
            return view('frontend.radiologias.all_radiologias_prescritas_by_ipress', compact('radiologias', 'cantidad', 'farmacia', 'fechaoDesde', 'fechaoHasta', 'fechaDesde', 'fechaHasta','fechamDesde', 'fechamHasta', 'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nro_prestacion','tipo_registro', 'nro_examen','id_user','servicio_examen','tipo_atenciones','tipo_perfil','id_establecimiento','id_prioridad_temp','id_servicio_temp','id_servicio_imagenes_temp','id_referido_temp','id_estado_muestra_temp','id_estado_informe_temp','id_tipo_atencion', 'farmacias','listServiciosImagenes'));
        else:
            //====================
            return view('frontend.radiologias.all_radiologias_prescritas_by_ipress_nivel1y2', compact('radiologias', 'cantidad', 'farmacia', 'fechaoDesde', 'fechaoHasta', 'fechaDesde', 'fechaHasta','fechamDesde', 'fechamHasta', 'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nro_prestacion','tipo_registro', 'nro_examen','id_user','servicio_examen','tipo_atenciones','tipo_perfil','id_establecimiento','id_prioridad_temp','id_servicio_temp','id_servicio_imagenes_temp','id_referido_temp','id_estado_muestra_temp','id_estado_informe_temp','id_tipo_atencion', 'farmacias','listServiciosImagenes','nivel'));
        endif;
    }

    public function listar_examenes_radiologicos(Request $request) {


        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        if (!$request->id_user):
            return redirect('/all_radiologia_prescritas_by_ipress');
        endif;

        $id_user =$request->id_user;
        //==================== 
        $model_farma = new Farmacia();
        $farmacia = $model_farma->GetIpressByIduser($id_user);
        $id_establecimiento = $farmacia->id;
        $id_farmacia = isset($request->id_farmacia) ? $request->id_farmacia : 0;
        $farmacias = $model_farma->GetFarmaciaByIdEstablecimiento( auth()->user()->id_establecimiento );
        //----------------------------------------------------------------------
        $flagfiscalizador=$request->flagfiscalizador;
        
        $fechaDesde = $request->fechaDesde;
        $fechaHasta = $request->fechaHasta;
        $fechaoDesde = $request->fechaoDesde;
        $fechaoHasta = $request->fechaoHasta;
        $fechamDesde = $request->fechamDesde;
        $fechamHasta = $request->fechamHasta;

        $dni_beneficiario = strtoupper($request->dni_beneficiario);
        //$nro_prestacion = $request->nro_prestacion;
        //$nro_examen = $request->nro_examen;
        $tipo_registro = $request->tipo_registro;
        $id_tipo_atencion = isset($request->tipo_atencion) ? $request->tipo_atencion : 0;
        //$referido = isset($request->referido) ? $request->referido : "";
        //$estado = $request->estado;
        $estado_muestra= $request->estado_muestra;
        $estado_informe = $request->estado_informe;

        // Emergencia
        //$id_prioridad = isset($request->id_prioridad) ? $request->id_prioridad : 0;
        //$id_servicio = isset($request->id_servicio) ? $request->id_servicio : 0;

        //Servicio Imagenes
        $id_servicio_imagenes = isset($request->id_servicio_imagenes) ? $request->id_servicio_imagenes : 0;
        //dd($id_servicio_imagenes);

        //ignorar fechas para busqueda de DNI
        $ignorarFechas = $request->has('ignorar_fechas');

        if (!empty($dni_beneficiario) && $ignorarFechas) {
            $fechaDesde = null;
            $fechaHasta = null;
            $fechaoDesde = null;
            $fechaoHasta = null;
            $fechamDesde = null;
            $fechamHasta = null;
        }

        //Limpiar variables
        $id_prioridad_temp = "";
        $id_servicio_temp = "";
        $id_servicio_imagenes_temp = "";
        $id_referido_temp = "";
        $id_estado_muestra_temp = "";
        $id_estado_informe_temp= "";

        // Variables de retorno
        $id_prioridad_temp = $request->id_prioridad;
        $id_servicio_temp = $request->id_servicio;
        $id_servicio_imagenes_temp = $request->id_servicio_imagenes;
        $id_referido_temp = isset($request->referido) ? $request->referido : "";
        //==========================estado_muestra
        $estado_muestra = $request->estado_muestra;
        $id_estado_muestra_temp = $estado_muestra;
        if ($tipo_registro === "7") {
            $estado_muestra = null;
            $id_estado_muestra_temp = "0";
        }
        //==========================estado_informe
        $estado_informe = $request->estado_informe;
        $id_estado_informe_temp = $estado_informe;

        if ($tipo_registro === "6") {
            $estado_informe = null;
            $id_estado_informe_temp = "0";
        }
        //dd($id_estado_informe_temp);

        $cantidad = 0;
        $model_radiologia = new Radiologia();
        switch ($id_tipo_atencion) {
            case 0:
                $estado_informe_raw = $request->input('estado_informe');
                $estado_muestra_raw = $request->input('estado_muestra');             
                //#CONSULTA EXTERNA + EMERGENCIA + HOSPITALIZACIÓN
                if(($tipo_registro === "7" || $tipo_registro === "1")) {
                    if($flagfiscalizador ==='1'){
                        // $estado_informe;
                        // $estado_muestra;
                        $estado_informe = ($estado_informe_raw === '0') ? null : $estado_informe_raw;
                        $estado_muestra = ($estado_muestra_raw === '0') ? null : $estado_muestra_raw;
                    }else{
                    $estado_muestra = null;
                    }
                }else if($tipo_registro === "6"){
                    $estado_informe = null;
                }
                $radiologia1 = $model_radiologia->AllExamenRadiologiaAmbulatorioByEstablecimientoAndFechaDesdeHasta(
                    $id_establecimiento,$fechaoDesde,$fechaoHasta,$fechaDesde, $fechaHasta,$fechamDesde, $fechamHasta, $dni_beneficiario,$id_servicio_imagenes,$estado_muestra,$estado_informe
                );
                $radiologia3 = $model_radiologia->AllExamenRadiologiaEmergenciaSinCitaByEstablecimientoAndFechaDesdeHasta(
                    $id_establecimiento,$fechaoDesde,$fechaoHasta,$fechaDesde, $fechaHasta,$fechamDesde, $fechamHasta, $dni_beneficiario,$id_servicio_imagenes,$estado_muestra,$estado_informe
                );
                $radiologia2 = $model_radiologia->AllExamenRadiologiaHospitalizacionSinCitaByEstablecimientoAndFechaDesdeHasta(
                    $id_establecimiento,$fechaoDesde,$fechaoHasta,$fechaDesde, $fechaHasta,$fechamDesde, $fechamHasta, $dni_beneficiario,$id_servicio_imagenes,$estado_muestra,$estado_informe
                );
                $radiologias = array_merge($radiologia1,$radiologia2,$radiologia3);
                $cantidad = count($radiologias);
                $model_emergencia = new \App\Models\Emergencia;
                $model_radiologia = new \App\Models\Radiologia;
                // Asignamos ubicaciones de cama
                foreach ($radiologias as $key) {
                    $hospitalizacion = \App\Models\Hospitalizacione::where('id', $key->id_ext)->first();
                    $key->ubicacion_cama = ($hospitalizacion && $hospitalizacion->id_cama_estado) ? $model_emergencia->GetObtenerCama($hospitalizacion->id_cama_estado) : "0";

                }
                // Verificamos si el usuario cumple la condición antes de modificar $radiologias
                if (in_array(Auth::user()->tipo_registro, [6, 7, 1])) {
                    foreach ($radiologias as $key) {
                        $key->servicio_examen = $model_radiologia->AllServicioExamenRadiologiaByEstablecimientoAndFechaDesdeHasta($key->id_pp,$dni_beneficiario);
                        $key->informe_adicional = $model_radiologia->obtenerInformesAdicionales($key->id_pp,$dni_beneficiario);
                    }
                }
                break;
            case 1: //AMBULATORIO
                $estado_informe_raw = $request->input('estado_informe');
                $estado_muestra_raw = $request->input('estado_muestra');             
                if(($tipo_registro === "7" || $tipo_registro === "1")) {
                    if($flagfiscalizador ==='1'){
                        $estado_informe = ($estado_informe_raw === '0') ? null : $estado_informe_raw;
                        $estado_muestra = ($estado_muestra_raw === '0') ? null : $estado_muestra_raw;
                    }else{
                    $estado_muestra = null;
                    }
                }else if($tipo_registro === "6"){
                    $estado_informe = null;
                }
                $radiologias = $model_radiologia->AllExamenRadiologiaAmbulatorioByEstablecimientoAndFechaDesdeHasta(
                    $id_establecimiento,$fechaoDesde,$fechaoHasta,$fechaDesde, $fechaHasta,$fechamDesde, $fechamHasta, $dni_beneficiario,$id_servicio_imagenes,$estado_muestra,$estado_informe
                );
                $cantidad = count($radiologias);
                $model_radiologia = new \App\Models\Radiologia;
                    if (in_array(Auth::user()->tipo_registro, [6, 7, 1])) {
                        foreach ($radiologias as $key) {
                            $key->servicio_examen = $model_radiologia->AllServicioExamenRadiologiaByEstablecimientoAndFechaDesdeHasta($key->id_pp,$dni_beneficiario);
                            $key->informe_adicional = $model_radiologia->obtenerInformesAdicionales($key->id_pp,$dni_beneficiario);
                        }
                    }
                break;
            case 2: //Emergencia
                $estado_informe_raw = $request->input('estado_informe');
                $estado_muestra_raw = $request->input('estado_muestra');             
                if(($tipo_registro === "7" || $tipo_registro === "1")) {
                    if($flagfiscalizador ==='1'){
                        $estado_informe = ($estado_informe_raw === '0') ? null : $estado_informe_raw;
                        $estado_muestra = ($estado_muestra_raw === '0') ? null : $estado_muestra_raw;
                    }else{
                    $estado_muestra = null;
                    }
                }else if($tipo_registro === "6"){
                    $estado_informe = null;
                }
                $radiologias = $model_radiologia->AllExamenRadiologiaEmergenciaSinCitaByEstablecimientoAndFechaDesdeHasta(
                    $id_establecimiento,$fechaoDesde,$fechaoHasta,$fechaDesde, $fechaHasta,$fechamDesde, $fechamHasta, $dni_beneficiario,$id_servicio_imagenes,$estado_muestra,$estado_informe
                );
                $cantidad = count($radiologias);
                $model_radiologia = new \App\Models\Radiologia;
                    if (in_array(Auth::user()->tipo_registro, [6, 7, 1])) {
                        foreach ($radiologias as $key) {
                            $key->servicio_examen = $model_radiologia->AllServicioExamenRadiologiaByEstablecimientoAndFechaDesdeHasta($key->id_pp,$dni_beneficiario);
                            $key->informe_adicional = $model_radiologia->obtenerInformesAdicionales($key->id_pp,$dni_beneficiario);
                        }
                    }
                break;
            case 3://HOSPITALIZACIÓN                
                $estado_informe_raw = $request->input('estado_informe');
                $estado_muestra_raw = $request->input('estado_muestra');             
                if(($tipo_registro === "7" || $tipo_registro === "1")) {
                    if($flagfiscalizador ==='1'){
                        $estado_informe = ($estado_informe_raw === '0') ? null : $estado_informe_raw;
                        $estado_muestra = ($estado_muestra_raw === '0') ? null : $estado_muestra_raw;
                    }else{
                    $estado_muestra = null;
                    }
                }else if($tipo_registro === "6"){
                    $estado_informe = null;
                }
                $radiologias = $model_radiologia->AllExamenRadiologiaHospitalizacionSinCitaByEstablecimientoAndFechaDesdeHasta(
                    $id_establecimiento,$fechaoDesde,$fechaoHasta,$fechaDesde, $fechaHasta,$fechamDesde, $fechamHasta, $dni_beneficiario,$id_servicio_imagenes,$estado_muestra,$estado_informe
                );
                $cantidad = count($radiologias);
                
                $model_emergencia = new \App\Models\Emergencia;
                $model_radiologia = new \App\Models\Radiologia;
                        
                foreach ($radiologias as $key) {
                    $hospitalizacion = \App\Models\Hospitalizacione::where('id', $key->id_ext)->first();
                    $key->ubicacion_cama = ($hospitalizacion && $hospitalizacion->id_cama_estado) ? $model_emergencia->GetObtenerCama($hospitalizacion->id_cama_estado) : "0";
                }
                if (in_array(Auth::user()->tipo_registro, [6, 7, 1])) {
                    foreach ($radiologias as $key) {
                        $key->servicio_examen = $model_radiologia->AllServicioExamenRadiologiaByEstablecimientoAndFechaDesdeHasta($key->id_pp,$dni_beneficiario);
                        $key->informe_adicional = $model_radiologia->obtenerInformesAdicionales($key->id_pp,$dni_beneficiario);
                    }
                }
            break;
                    
        }

        //$cantidad = $model_radiologia->GetCountExamenRadiologiaByFechaDesdeHasta($id_establecimiento, $fechaoDesde, $fechaoHasta, $dni_beneficiario, $nro_prestacion, $nro_examen,$id_tipo_atencion);
        
        $model_atencione = new \App\Models\TipoAtencione;
        $tipo_atenciones = $model_atencione->listarTipoAtencionesLaboratorio();
        //Perfil para fiscalizador
        $tipo_perfil = 1;

        $listServiciosImagenes = $this->listaServiciosImagenes();
        if (!isset($radiologias)) {
            $radiologias = [];
            $cantidad = 0;
        }

        // Observación de examen de imágenes (examen no realizado)
        $this->agregarObservacionExamenImagenes($radiologias);

        // Si la solicitud es AJAX, devolvemos JSON para DataTable
        if ($request->ajax()) {
            return response()->json([
                'data' => $radiologias,
                'recordsTotal' => $cantidad,
                'recordsFiltered' => $cantidad,
            ]);
        }
        // dd(["radiologias" => $radiologias, "id_estado_temp" => $id_estado_temp]);

        //dd(compact('radiologias', 'cantidad', 'farmacia', 'fechaoDesde', 'fechaoHasta', 'fechaDesde', 'fechaHasta','fechamDesde', 'fechamHasta', 'id_farmacia', 'dni_beneficiario', 'nro_prestacion', 'nro_examen','id_user', 'tipo_atenciones','tipo_perfil','id_establecimiento','id_tipo_atencion','id_prioridad_temp','id_servicio_temp','id_servicio_imagenes_temp','id_referido_temp','id_estado_temp','id_estado_inf_temp','farmacias','listServiciosImagenes'));

        $nivel_ipress = $model_farma->ObtenerNivelFarmacia(Auth::user()->id_farmacia);
        $nivel=$nivel_ipress->nivel;
        
        if($nivel == 3):
            return view('frontend.radiologias.all_radiologias_prescritas_by_ipress', compact('radiologias', 'cantidad', 'farmacia', 'fechaoDesde', 'fechaoHasta', 'fechaDesde', 'fechaHasta','fechamDesde', 'fechamHasta', 'id_farmacia', 'dni_beneficiario', 'nro_prestacion', 'nro_examen','id_user', 'tipo_atenciones','tipo_perfil','id_establecimiento','id_tipo_atencion','id_prioridad_temp','id_servicio_temp','id_servicio_imagenes_temp','id_referido_temp','id_estado_muestra_temp','id_estado_informe_temp','farmacias','listServiciosImagenes','nivel'));
        else:
            return view('frontend.radiologias.all_radiologias_prescritas_by_ipress_nivel1y2', compact('radiologias', 'cantidad', 'farmacia', 'fechaoDesde', 'fechaoHasta', 'fechaDesde', 'fechaHasta','fechamDesde', 'fechamHasta', 'id_farmacia', 'dni_beneficiario', 'nro_prestacion', 'nro_examen','id_user', 'tipo_atenciones','tipo_perfil','id_establecimiento','id_tipo_atencion','id_prioridad_temp','id_servicio_temp','id_servicio_imagenes_temp','id_referido_temp','id_estado_muestra_temp','id_estado_informe_temp','farmacias','listServiciosImagenes','nivel'));
        endif;
        
        
    }
    public function getNivelradreportebyuser($userId){
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

		$nivel = DB::table('establecimientos as e')->join('users as u', 'u.id_establecimiento', '=', 'e.id')->where('u.id', $userId)->value('nivel'); 

        $nivel = $nivel ?: 0;

        header('Content-Type: application/json');
        echo json_encode(['nivel' => $nivel]);
        exit;
	}
    
    public function formulario_atencion_radiologia($id) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $id_user = Auth::user()->id;
        $id_farmacia = Auth::user()->id_farmacia;
        
        $modelFarmacia = new Farmacia();
        $model_cie = new \App\Models\Diagnostico;
        $model_consultorio = new \App\Models\Consultorio;
        $model_diagnostico = new \App\Models\Diagnostico;
        $model_kardex = new \App\Models\Kardexe();    
        $model_medico = new \App\Models\Medico();
        $model_usuario = new \App\Models\User;
        
        $fechaServidor = $model_kardex->getFechaServidor();
        //-----------------------------------------------        
        $nivel = $modelFarmacia->ObtenerNivelFarmacia($id_farmacia);
        $farma = $modelFarmacia->GetFarmaByIdfarma($id_farmacia);
        $tipo_atencion = $modelFarmacia->GetFarmaciaTipoAtencionByIdFarmacia($id_farmacia);
        $id_nivel=$nivel->nivel;

        $tipo = "dni";      
        //------------------------------------------------------------------
        $fecha_hoy = date('d-m-Y');
        //------------------------------------------------------------------
        $establecimiento = $modelFarmacia->GetIpressByIduser($id_user);
        $id_ipress = $establecimiento->id;

        $user = $model_usuario->GetById($id_user);
        $flagresponsablefarmacia = $user->flagresponsablefarmacia;
        $flagfiscalizador = $user->flagfiscalizador;

        $id_ipress = $establecimiento->id;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $consultorio = $model_consultorio->ShowConsultorios($id_farmacia);
        $errorWS = 1;
        /*********************************************************************************************************/
        $model_diagnosticos = new \App\Models\Receta_diagnostico;
        
        $fecha_fin = $model_kardex->getFechaServidor();
        $id_farmacia = Auth::user()->id_farmacia;
        $farmacia = DB::table('farmacias')->where('id', '=', $id_farmacia)->first();
        $id_establecimiento = $farmacia->id_establecimiento;
        
        /************************************************************************************************************/
        $model_prestacion = new \App\Models\Prestaciones;
        $model_radiologia = new \App\Models\Radiologia;        

        $radiologia = $model_radiologia->GetRadiologiaById($id);
        $id_prestacion=$radiologia->id_prestacion;
        $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
        $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
        //$procradiologia = $model_radiologia->GetProcedimientosByIdRadiologia($id_prestacion,2);  
        $procradiologia = $model_prestacion->GetProcedimientosByIdPrestacionAndIdLaboratorio($id_prestacion,$id,3);

        //GetProcedimientosByIdPrestacionAndIdLaboratorio

        /*****************************/
        return view('frontend.radiologias.form_radiologia_atencion', compact('prestacion','diagnostico','radiologia','procradiologia'));    
    }

    public function modal_historial_radiologico($dni_beneficiario){
        $radiologia_prestaciones = new \App\Models\Radiologia;
        $examenes = $radiologia_prestaciones->GetExamenRadiologiaByDni($dni_beneficiario);
        $adendas = $radiologia_prestaciones -> obtenerInformesAdicionales("",$dni_beneficiario);
        $servicio_examen = $radiologia_prestaciones->AllServicioExamenRadiologiaByEstablecimientoAndFechaDesdeHasta("",$dni_beneficiario);

        // Observación de examen de imágenes (examen no realizado) por id_pp
        $this->agregarObservacionExamenImagenes($servicio_examen);

        return view('frontend.radiologias.modal_historial_radiologia',compact('examenes','adendas','servicio_examen'));
    }

    public function edit($id) {
        //
        $laboratorios = Radiologia::findOrFail($id);
        return view('frontend.laboratorios.edit', ['laboratorios' => $laboratorios]);
    }

    public function registrar_examen_radiologia(Request $request) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $radiologia = Radiologia::Where('id',$request->id_radiologia)->first();
        if($radiologia->estado==1):
            $date = Carbon::now();
            $fecha_actual = $date->format('d-m-Y');

            $radiologia->fecha_atencion = $fecha_actual;
            $radiologia->id_establecimiento = $request->id_establecimiento;
            $radiologia->id_responsable = Auth::user()->id;
            $radiologia->observacion = $request->observacion;
            $radiologia->save();
            
            Session::flash('flash_message', 'Se registro con exitoso');
            return redirect('all_radiologia_prescritas_by_ipress');
        else:

            Session::flash('flash_message', 'Ya se encuentra atendido');
        return redirect('all_radiologia_prescritas_by_ipress');

        endif;
            
        
    }

    public function all_radiologia_atendidas_by_ipress($id_user = 0) {

        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;

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
        $model_radiologia = new Radiologia();
        $recetas = $model_radiologia->AllExamenRadiologiaAtendidaByEstablecimientoAndFechaDesdeHasta($id_establecimiento, $fechaDesde, $fechaHasta);
        
        $cantidad = $model_radiologia->GetCountExamenRadiologiaAtendidaByFechaDesdeHasta($id_establecimiento, $fechaDesde, $fechaHasta);
        
        //====================
        return view('frontend.radiologias.all_radiologias_atendidas_by_ipress', compact('recetas', 'cantidad', 'farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nro_prestacion', 'nro_examen','id_user' ));
    }

    public function listar_examenes_radiologicos_atendidos(Request $request) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        if (!$request->id_user):
            return redirect('/all_radiologia_prescritas_by_ipress');
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
        
        $model_laboratorio = new Radiologia();
        $recetas = $model_laboratorio->AllExamenRadiologiaAtendidaByEstablecimientoAndFechaDesdeHasta($id_establecimiento, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_prestacion, $nro_examen);
        
        $cantidad = $model_laboratorio->GetCountExamenRadiologiaAtendidaByFechaDesdeHasta($id_establecimiento, $fechaDesde, $fechaHasta, $dni_beneficiario, $nro_prestacion, $nro_examen);
        
        return view('frontend.radiologias.all_radiologias_atendidas_by_ipress', compact('recetas', 'cantidad', 'farmacia', 'fechaDesde', 'fechaHasta', 'id_farmacia', 'dni_beneficiario', 'nro_prestacion', 'nro_examen','id_user' ));
    }

    public function modal_radiologia_paciente($id_prestacion ){
        $model_prestacion = new \App\Models\Prestaciones;
        $radiologias = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 3);
        
        return view('frontend.epicrisis.modal_radiologias',compact('radiologias'));
    }

    /*
    Mostar atencion
    */
     public function radiologia_atencion_vista($id, $id_servicio_radiologia, $id_pp, $id_cita = 0) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $id_user = Auth::user()->id;
        $id_farmacia = Auth::user()->id_farmacia;
        
        $modelFarmacia = new Farmacia();
        $model_cie = new \App\Models\Diagnostico;
        $model_consultorio = new \App\Models\Consultorio;
        $model_diagnostico = new \App\Models\Diagnostico;
        $model_kardex = new \App\Models\Kardexe();    
        $model_medico = new \App\Models\Medico();
        $model_usuario = new \App\Models\User;
        
        $fechaServidor = $model_kardex->getFechaServidor();
        //-----------------------------------------------        
        $nivel = $modelFarmacia->ObtenerNivelFarmacia($id_farmacia);
        $farma = $modelFarmacia->GetFarmaByIdfarma($id_farmacia);
        $tipo_atencion = $modelFarmacia->GetFarmaciaTipoAtencionByIdFarmacia($id_farmacia);
        $id_nivel=$nivel->nivel;

        $tipo = "dni";      
        //------------------------------------------------------------------
        $fecha_hoy = date('d-m-Y');
        //------------------------------------------------------------------
        $establecimiento = $modelFarmacia->GetIpressByIduser($id_user);
        $id_ipress = $establecimiento->id;

        $user = $model_usuario->GetById($id_user);
        $flagresponsablefarmacia = $user->flagresponsablefarmacia;
        $flagfiscalizador = $user->flagfiscalizador;

        $id_ipress = $establecimiento->id;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $consultorio = $model_consultorio->ShowConsultorios($id_farmacia);
        $errorWS = 1;
        /*********************************************************************************************************/
        $model_diagnosticos = new \App\Models\Receta_diagnostico;
        
        $fecha_fin = $model_kardex->getFechaServidor();
        $id_farmacia = Auth::user()->id_farmacia;
        $farmacia = DB::table('farmacias')->where('id', '=', $id_farmacia)->first();
        $id_establecimiento = $farmacia->id_establecimiento;
        
        /************************************************************************************************************/
        $model_prestacion = new \App\Models\Prestaciones;
        $model_radiologia = new \App\Models\Radiologia;        
	    $model_plantilla = new \App\Models\Plantilla_radiologia;
        $model_referencia = new \App\Models\Referencia;   
        $radiologia = $model_radiologia->GetRadiologiaById($id);
        $id_prestacion=$radiologia->id_prestacion;

        //**** */
        $prestacion_tipo_atencion = DB::table('prestaciones')->select('prestaciones.id_tipo_atencion')->where('prestaciones.id','=',$id_prestacion)->first();
        $id_tipo_atencion = $prestacion_tipo_atencion->id_tipo_atencion;
        switch ($id_tipo_atencion) {
            case '1': //CONSULTA EXTERNA
                $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
                $referencia = $prestacion->referido;

                if($id_prestacion>1910698){
                    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionydiag($id_prestacion, 1);
                } else {
                    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 1);
                }
                break;
                
            case '2': //EMERGENCIA
                $prestacion = $model_prestacion->GetPrestacionEmergenciaRadiologiaById([$id_prestacion]);

                $referencia = $prestacion->id_motivo_ingreso;

                if($id_prestacion>1910698){
                    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionydiag($id_prestacion, 1);
                } else {
                    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 1);
                }
                break;
            case '3': //HOSPITALIZACIÓN
                if(!empty($radiologia->id_evaluacion)){
                    $evolucion = $model_radiologia->GetRadiologiaById_ev($id);
                }
                
                $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
                $referencia = 'FALTA';

                $model_emergencia = new \App\Models\Emergencia;
                $hospitalizacion = \App\Models\Hospitalizacione::where('id', $prestacion->id_ext)->first();
                $camas = $model_emergencia->GetObtenerCama($hospitalizacion->id_cama_estado);
                if($id_prestacion>1910698){
                    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionydiag($id_prestacion, 1);
                } else {
                    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 1);
                }
                break;
        }


        $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
        $procradiologia = $model_prestacion->GetProcedimientosByIdPrestacionAndIdLabRadAndTipoAndIdServicioRad($id, $id_servicio_radiologia,$id_pp);
        $categoria_radiologias = DB::table('categoria_radiologias')->select('categoria_radiologias.*')->where('id_servicio', $id_servicio_radiologia)->first();
	    $plantillas = $model_plantilla->PlantillaServicio($categoria_radiologias->id,$id_nivel);        
	   /*****************************/
        return view('frontend.radiologias.ver_rad', compact('prestacion','diagnostico','radiologia','evolucion','procradiologia','plantillas','id_tipo_atencion','referencia','procedimiento','referencia_datos','diagnostico_referencia','id_cita','camas'));    
    }




    /*
    Mostar formulario de registro del INFORME RADIOLOGICO
    */
    public function radiologia_informe($id, $id_servicio_radiologia, $id_pp, $id_cita = 0) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        // Examen observado (no realizado): no se permite atender
        $pp_observado = DB::table('prestacion_procedimientos')->where('id', $id_pp)->whereNotNull('obs_imagenes')->first();
        if ($pp_observado) {
            session()->flash('activar_autoenvio', true);
            Session::flash('flash_message', 'El examen se encuentra OBSERVADO, no se puede atender.');
            return redirect('all_radiologia_prescritas_by_ipress');
        }

        $id_user = Auth::user()->id;
        $id_farmacia = Auth::user()->id_farmacia;
        
        $modelFarmacia = new Farmacia();
        $model_cie = new \App\Models\Diagnostico;
        $model_consultorio = new \App\Models\Consultorio;
        $model_diagnostico = new \App\Models\Diagnostico;
        $model_kardex = new \App\Models\Kardexe();    
        $model_medico = new \App\Models\Medico();
        $model_usuario = new \App\Models\User;
        
        $fechaServidor = $model_kardex->getFechaServidor();
        //-----------------------------------------------        
        $nivel = $modelFarmacia->ObtenerNivelFarmacia($id_farmacia);
        $farma = $modelFarmacia->GetFarmaByIdfarma($id_farmacia);
        $tipo_atencion = $modelFarmacia->GetFarmaciaTipoAtencionByIdFarmacia($id_farmacia);
        $id_nivel=$nivel->nivel;

        $tipo = "dni";      
        //------------------------------------------------------------------
        $fecha_hoy = date('d-m-Y');
        //------------------------------------------------------------------
        $establecimiento = $modelFarmacia->GetIpressByIduser($id_user);
        $id_ipress = $establecimiento->id;

        $user = $model_usuario->GetById($id_user);
        $flagresponsablefarmacia = $user->flagresponsablefarmacia;
        $flagfiscalizador = $user->flagfiscalizador;

        $id_ipress = $establecimiento->id;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $consultorio = $model_consultorio->ShowConsultorios($id_farmacia);
        $errorWS = 1;
        /*********************************************************************************************************/
        $model_diagnosticos = new \App\Models\Receta_diagnostico;
        
        $fecha_fin = $model_kardex->getFechaServidor();
        $id_farmacia = Auth::user()->id_farmacia;
        $farmacia = DB::table('farmacias')->where('id', '=', $id_farmacia)->first();
        $id_establecimiento = $farmacia->id_establecimiento;
        
        /************************************************************************************************************/
        $model_prestacion = new \App\Models\Prestaciones;
        $model_radiologia = new \App\Models\Radiologia;        
	    $model_plantilla = new \App\Models\Plantilla_radiologia;
        $model_referencia = new \App\Models\Referencia;   
        $radiologia = $model_radiologia->GetRadiologiaById($id);
        $id_prestacion=$radiologia->id_prestacion;

        //**** */
        $prestacion_tipo_atencion = DB::table('prestaciones')->select('prestaciones.id_tipo_atencion')->where('prestaciones.id','=',$id_prestacion)->first();
        $id_tipo_atencion = $prestacion_tipo_atencion->id_tipo_atencion;
        switch ($id_tipo_atencion) {
            case '1': //CONSULTA EXTERNA
                // $prestacion = $model_prestacion->GetPrestacionById($id_prestacion); //comentado el 24-05-26
                $prestacion = $this->GetProcedimientosRad_PrestacionById($id_prestacion);
                $referencia = $prestacion->referido;
                // if ($referencia == "SI") {
                //     $referencia_datos = $model_referencia->obtenerDatosDeRefereanciaParaAtencionLabPorId($prestacion->id_referencia);
                //     $diagnostico_referencia = $model_prestacion->GetDiagnosticoByIdPrestacion($referencia_datos->id_prestacion_referencia);
                // }
                if($id_prestacion>1910698){
                    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionydiag2($id_prestacion, 1);
                } else {
                    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 1);
                }
                break;
                
            case '2': //EMERGENCIA
                $prestacion = $model_prestacion->GetPrestacionEmergenciaRadiologiaById([$id_prestacion]);
                //dd($id_prestacion);
                $referencia = $prestacion->id_motivo_ingreso;
                //if ($prestacion->id_motivo_ingreso == 4) {
                    //En emergencia no usa el módulo de referencia
                    //$referencia_datos = $model_referencia->obtenerDatosDeRefereanciaParaAtencionLabPorId($prestacion->id_referencia);
                    //$diagnostico_referencia = $model_prestacion->GetDiagnosticoByIdPrestacion($referencia_datos->id_prestacion_referencia);
                //}
                if($id_prestacion>1910698){
                    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionydiag2($id_prestacion, 1);
                } else {
                    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 1);
                }
                break;
            case '3': //HOSPITALIZACIÓN
                if(!empty($radiologia->id_evaluacion)){
                    $evolucion = $model_radiologia->GetRadiologiaById_ev($id);
                }
                
                // $prestacion = $model_prestacion->GetPrestacionById($id_prestacion); //comentado el 24-05-26
                $prestacion = $this->GetProcedimientosRad_PrestacionById($id_prestacion);
                $referencia = 'FALTA';//$prestacion->id_motivo_ingreso;
                //if ($prestacion->id_motivo_ingreso == 4) {
                    //En emergencia no usa el módulo de referencia
                    //$referencia_datos = $model_referencia->obtenerDatosDeRefereanciaParaAtencionLabPorId($prestacion->id_referencia);
                    //$diagnostico_referencia = $model_prestacion->GetDiagnosticoByIdPrestacion($referencia_datos->id_prestacion_referencia);
                //}
                $model_emergencia = new \App\Models\Emergencia;
                $hospitalizacion = \App\Models\Hospitalizacione::where('id', $prestacion->id_ext)->first();
                $camas = $model_emergencia->GetObtenerCama($hospitalizacion->id_cama_estado);
                if($id_prestacion>1910698){
                    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionydiag2($id_prestacion, 1);
                } else {
                    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 1);
                }
                break;
        }


        $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
        $procradiologia = $model_prestacion->GetProcedimientosByIdPrestacionAndIdLabRadAndTipoAndIdServicioRad($id, $id_servicio_radiologia,$id_pp);
        $categoria_radiologias = DB::table('categoria_radiologias')->select('categoria_radiologias.*')->where('id_servicio', $id_servicio_radiologia)->first();
	    $plantillas = $model_plantilla->PlantillaServicio($categoria_radiologias->id, $id_nivel);        
	    /*****************************/
        $client = new SoapClient(env('WI_SALUDPOL_URL') . "/ws/WS_AfiliadoSP_Serv.php?wsdl", array('login' => env('WI_SALUDPOL_USERNAME'), 'password' => env('WI_SALUDPOL_PASSWORD')));

        if($prestacion->tipo_doc_ident=='DNI'){ 
            $tipo_doc_ident = '1';
        }else if($prestacion->tipo_doc_ident=='CE'){ 
            $tipo_doc_ident = '2';
        }else if($prestacion->tipo_doc_ident=='PAS'){ 
            $tipo_doc_ident = '3';
        }else if($prestacion->tipo_doc_ident=='DIE'){ 
            $tipo_doc_ident = '4';
        }

        $beneficiario = $client->getAseguradoValidate($tipo_doc_ident, $prestacion->nro_doc_ident);

        if (is_object($beneficiario)) :
            $flag = 0 ;
            $errorWS = 1 ;
        else:
            $flag = 1 ;
            $errorWS = 0 ;
        endif;
        if ($errorWS > 0) {
            return view('frontend.radiologias.form_radiologia_informe', compact('prestacion','diagnostico','radiologia','flagfiscalizador','evolucion','procradiologia','plantillas','id_tipo_atencion','id_pp','id_servicio_radiologia','referencia','procedimiento','referencia_datos','diagnostico_referencia','id_cita','camas'));    
        }else{
            if($id_nivel == 3){
                return redirect('/all_radiologia_prescritas_by_ipress')->withInput()->withErrors(array('El paciente ya no esta asegurado, por consiguiente no tiene derecho de atención, realizar los tramites correspondiente con SALUDPOL..!'));
            }else{
                return redirect('/all_radiologias_prescritas_by_ipress_nivel1y2')->withInput()->withErrors(array('El paciente ya no esta asegurado, por consiguiente no tiene derecho de atención, realizar los tramites correspondiente con SALUDPOL..!'));
            }
        }
    }

    /*
    Registrar el INFORME RADIOLOGICO
    */

    private function agregarObservacionExamenImagenes($radiologias) {
        $ids_pp = [];
        foreach ($radiologias as $r) {
            if (!empty($r->id_pp)) $ids_pp[] = $r->id_pp;
        }

        $observaciones = [];
        if (!empty($ids_pp)) {
            $observaciones = DB::table('prestacion_procedimientos as pp')
                ->leftJoin('users as u', 'u.id', '=', 'pp.obs_imagenes_us')
                ->whereIn('pp.id', array_unique($ids_pp))
                ->whereNotNull('pp.obs_imagenes')
                ->select('pp.id', 'pp.obs_imagenes', 'pp.obs_imagenes_at',
                    DB::raw("TRIM(COALESCE(u.paterno,'') || ' ' || COALESCE(u.materno,'') || ' ' || COALESCE(u.name,'')) as responsable"))
                ->get();
            $observaciones = collect($observaciones)->keyBy('id');
        }

        foreach ($radiologias as $r) {
            $obs = (!empty($r->id_pp) && isset($observaciones[$r->id_pp])) ? $observaciones[$r->id_pp] : null;
            $r->obs_imagenes             = $obs ? $obs->obs_imagenes : null;
            $r->obs_imagenes_at          = $obs ? $obs->obs_imagenes_at : null;
            $r->obs_imagenes_responsable = $obs ? $obs->responsable : null;
        }
    }

    public function registrar_observacion_examen_imagenes(Request $request) {

        if (!Auth::check()):
            return response()->json(['success' => false, 'message' => 'Sesión expirada, vuelva a iniciar sesión.'], 401);
        endif;

        $obs_imagenes  = trim((string) $request->obs_imagenes);
        $id_radiologia = $request->id_radiologia;
        $ids_pp        = array_filter((array) $request->id_pres_proc_total, 'is_numeric');

        if ($obs_imagenes === '' || !is_numeric($id_radiologia) || empty($ids_pp)) {
            return response()->json(['success' => false, 'message' => 'Datos incompletos para registrar la observación.'], 422);
        }

        try {
            $affected = DB::table('prestacion_procedimientos')
                ->whereIn('id', $ids_pp)
                ->where('tipo_procedimiento', 3)
                ->where('id_lab_rad', $id_radiologia)
                ->whereNull('obs_imagenes')
                ->update([
                    'obs_imagenes'    => mb_substr($obs_imagenes, 0, 600),
                    'obs_imagenes_us' => Auth::user()->id,
                    'obs_imagenes_at' => Carbon::now(),
                ]);
        } catch (\Exception $e) {
            Log::error('registrar_observacion_examen_imagenes: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'No se pudo registrar la observación.'], 500);
        }

        // Al volver al listado se reenvía la búsqueda con los filtros guardados
        session()->flash('activar_autoenvio', true);
        Session::flash('flash_message', 'Examen observado correctamente');

        return response()->json([
            'success'  => true,
            'affected' => $affected,
            'redirect' => url('all_radiologia_prescritas_by_ipress'),
        ]);
    }

    public function registrar_radiologia_informe(Request $request) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $total_examenes = count($request->id_pres_proc_total);
        $nro_proc = count($request->id_pres_proc);
        
        $res = ""; $i=0; 

        DB::beginTransaction();

        if ( auth()->user()->tipo_registro == 6 ) {  //Perfil Digitador de Radiología
        
            try {
                if ( isset($request->id_pres_proc_total) ) {
                    foreach ($request->id_pres_proc_total as $row):
                        $res = DB::update("UPDATE prestacion_procedimientos SET confirmar_toma = ?, confirmar_toma_created_at = ?, confirmar_toma_created_us = ? WHERE id = ? AND tipo_procedimiento = ? AND id_lab_rad = ?",
                                            ['4', Carbon::now(), Auth::user()->id, $row, 3, $request->id_radiologia]);
                    endforeach;
                }
    
                if ($total_examenes == 1) {
                    if ( isset($request->id_pres_proc) ) {
                        foreach ($request->id_pres_proc as $row):
                            
                            $parrafo=$request->parrafo1;
                            $tipo_plantilla=$request->id_plantilla1;
                            $total_informe = 0;
        
                            $affected = DB::update("UPDATE prestacion_procedimientos SET confirmar_toma = ?, confirmar_toma_created_at = ?, confirmar_toma_created_us = ?, observacion_registro_informe = ?, created_at_digitador_radiologia = ?, created_us_digitador_radiologia = ? WHERE id = ? AND tipo_procedimiento = ? AND id_lab_rad = ?",
                                                ['0', Carbon::now(), Auth::user()->id, $request->observacion_examen, Carbon::now(), Auth::user()->id, $row, 3, $request->id_radiologia]);
                            $i++;
                        endforeach;
                    }
                } else {
                    if ( isset($request->id_pres_proc) ) {
                        foreach ($request->id_pres_proc as $row):
                            if($request->id_examen_plantilla_2=='1'):
                                $parrafo=$request->parrafo1;
                                $tipo_plantilla=$request->id_plantilla1;
                                $total_informe = 1;
                                //dd('Entro en condición todos los examenes en una sola platilla');
                            else:
                                $parrafo=$request->parrafo[$i];
                                $tipo_plantilla=$request->id_plantilla[$i];
                                $total_informe = 2;
                                //dd('Entro en condición una plantilla por examen');
                            endif;
                            $affected = DB::update("UPDATE prestacion_procedimientos SET confirmar_toma = ?, confirmar_toma_created_at = ?, confirmar_toma_created_us = ?, observacion_registro_informe = ?, created_at_digitador_radiologia = ?, created_us_digitador_radiologia = ? WHERE id = ? AND tipo_procedimiento = ? AND id_lab_rad = ?",
                                                ['0', Carbon::now(), Auth::user()->id, $request->observacion_examen, Carbon::now(), Auth::user()->id, $row, 3, $request->id_radiologia]);
                            $i++;
                        endforeach;
                    }
                }
    
                DB::commit();
                $res = "Y";
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            } catch (\Throwable $th) {
                DB::rollback();
                throw $th;
            }
        
        }

        if ( auth()->user()->tipo_registro == 1 || auth()->user()->tipo_registro == 7 ) { //Perfil Prescriptor Médico || Profesional de Imagenes

            if ($request->bool_contenedor_profesional_imagenes == 'false') {
                try {
                    if ( isset($request->id_pres_proc_total) ) {
                        foreach ($request->id_pres_proc_total as $row):
                            $res = DB::update("UPDATE prestacion_procedimientos SET confirmar_toma = ?, confirmar_toma_created_at = ?, confirmar_toma_created_us = ?, observacion_registro_informe = ?, created_at_digitador_radiologia = ?, created_us_digitador_radiologia = ? WHERE id = ? AND tipo_procedimiento = ? AND id_lab_rad = ?",
                                                ['4', Carbon::now(), Auth::user()->id, $request->observacion_examen, Carbon::now(), Auth::user()->id,$row, 3, $request->id_radiologia]);
                        endforeach;
                    }
                    
                    DB::commit();
                    $res = "Y";
                } catch (\Exception $e) {
                    DB::rollback();
                    throw $e;
                } catch (\Throwable $th) {
                    DB::rollback();
                    throw $th;
                }
            }
            if ($request->bool_contenedor_profesional_imagenes == 'true') {
                try {
                    if (isset($request->id_pres_proc_total)) {
                        foreach ($request->id_pres_proc_total as $row):
                            $res = DB::update("UPDATE prestacion_procedimientos SET confirmar_toma = ?, confirmar_toma_created_at = ?, confirmar_toma_created_us = ? , validated_us = ?, validated_at = ?, flag_validated = ?  WHERE id = ? AND tipo_procedimiento = ? AND id_lab_rad = ?",
                                ['4', Carbon::now(), Auth::user()->id,Auth::user()->id, Carbon::now(), '1', $row, 3, $request->id_radiologia]);
                        endforeach;
                    }

                    if ($total_examenes == 1) {
                        if (isset($request->id_pres_proc)) {
                            foreach ($request->id_pres_proc as $row):
                                $parrafo = $request->parrafo1;
                                $total_informe = 1;
                                $tipo_plantilla = (isset($request->id_plantilla[$i]) && is_numeric($request->id_plantilla[$i])) ? $request->id_plantilla[$i] : null;
                                if ($tipo_plantilla !== null) {
                                    $affected = DB::update("UPDATE prestacion_procedimientos SET confirmar_toma = ?, confirmar_toma_created_at = ?, confirmar_toma_created_us = ?, informe_parrafo = ?, flag_informe = ?, transcribed_us = ?, transcribed_at = ?, total_informe = ?, tipo_plantilla = ?, observacion_registro_informe = ?, created_at_registro_informe = ?, created_us_registro_informe = ? WHERE id = ? AND tipo_procedimiento = ? AND id_lab_rad = ?",
                                        ['0', Carbon::now(), Auth::user()->id, $parrafo, '1', Auth::user()->id, Carbon::now(), $total_informe, $tipo_plantilla, $request->observacion_examen, Carbon::now(), Auth::user()->id, $row, 3, $request->id_radiologia]);
                                } else {
                                    $affected = DB::update("UPDATE prestacion_procedimientos SET confirmar_toma = ?, confirmar_toma_created_at = ?, confirmar_toma_created_us = ?, informe_parrafo = ?, flag_informe = ?, transcribed_us = ?, transcribed_at = ?, total_informe = ?, observacion_registro_informe = ?, created_at_registro_informe = ?, created_us_registro_informe = ? WHERE id = ? AND tipo_procedimiento = ? AND id_lab_rad = ?",
                                        ['0', Carbon::now(), Auth::user()->id, $parrafo, '1', Auth::user()->id, Carbon::now(), $total_informe, $request->observacion_examen, Carbon::now(), Auth::user()->id, $row, 3, $request->id_radiologia]);
                                }
                                $i++;
                            endforeach;
                        }
                    } else {
                        if (isset($request->id_pres_proc)) {
                            foreach ($request->id_pres_proc as $row):
                                if ($request->id_examen_plantilla_2 == '1'):
                                    $parrafo = $request->parrafo1;
                                    $tipo_plantilla = (isset($request->id_plantilla[$i]) && is_numeric($request->id_plantilla[$i])) ? $request->id_plantilla[$i] : null;
                                    $total_informe = 1;
                                else:
                                    $parrafo = $request->parrafo[$i];
                                    $tipo_plantilla = (isset($request->id_plantilla[$i]) && is_numeric($request->id_plantilla[$i])) ? $request->id_plantilla[$i] : null;
                                    $total_informe = 2;
                                endif;

                                if ($tipo_plantilla !== null) {
                                    $affected = DB::update("UPDATE prestacion_procedimientos SET confirmar_toma = ?, confirmar_toma_created_at = ?, confirmar_toma_created_us = ?, informe_parrafo = ?, flag_informe = ?, transcribed_us = ?, transcribed_at = ?, total_informe = ?, tipo_plantilla = ?, observacion_registro_informe = ?, created_at_registro_informe = ?, created_us_registro_informe = ? WHERE id = ? AND tipo_procedimiento = ? AND id_lab_rad = ?",
                                        ['0', Carbon::now(), Auth::user()->id, $parrafo, '1', Auth::user()->id, Carbon::now(), $total_informe, $tipo_plantilla, $request->observacion_examen, Carbon::now(), Auth::user()->id, $row, 3, $request->id_radiologia]);
                                } else {
                                    $affected = DB::update("UPDATE prestacion_procedimientos SET confirmar_toma = ?, confirmar_toma_created_at = ?, confirmar_toma_created_us = ?, informe_parrafo = ?, flag_informe = ?, transcribed_us = ?, transcribed_at = ?, total_informe = ?, observacion_registro_informe = ?, created_at_registro_informe = ?, created_us_registro_informe = ? WHERE id = ? AND tipo_procedimiento = ? AND id_lab_rad = ?",
                                        ['0', Carbon::now(), Auth::user()->id, $parrafo, '1', Auth::user()->id, Carbon::now(), $total_informe, $request->observacion_examen, Carbon::now(), Auth::user()->id, $row, 3, $request->id_radiologia]);
                                }
                                $i++;
                            endforeach;
                        }
                    }

                    DB::commit();
                    $res = "Y";
                } catch (\Exception $e) {
                    DB::rollback();
                    throw $e;
                } catch (\Throwable $th) {
                    DB::rollback();
                    throw $th;
                }

            }
        }
        
        $radiologia = Radiologia::find($request->nro_prestacion);
        if ($radiologia) {
            $radiologia->cod_siteds_susalud = $request->cod_siteds_susalud;
            $radiologia->updated_at_cod_siteds = Carbon::now();
            $radiologia->updated_us_cod_siteds = Auth::user()->id;
            $radiologia->save();
        }

        if ($res=="Y"):
            $model_prestacion = new \App\Models\Prestaciones;
            $model_cita = new \App\Models\Cita;
            $prestacion = $model_prestacion->GetPrestacionById($request->id_prestacion);
            $PresPro = DB::table('prestacion_procedimientos')->select('prestacion_procedimientos.*')->where('id', '=', $request->id_pres_proc)->orderBy('id', 'desc')->first();
            $pp_cita = DB::table('citas')->select('citas.id_pp')->where('id', '=', $request->id_prestacion)->orderBy('id', 'desc')->first();
            if (!empty($request->id_cita) && is_numeric($request->id_cita)) {
            $id_pp_cita=DB::table('citas')->where('id', $request->id_cita)->orderBy('id', 'desc')->first();
            $cantidad_pp = DB::table('prestacion_procedimientos')->where('id_citas_multiples_rad', $request->id_cita)->count();
            }
            //dd(['pres_pro'=> $PresPro->id, 'id_pp_cita'=>$id_pp_cita->id_pp]);
            $cita = $model_cita->GetCitasByIdCita($prestacion->id_cita);
            if (empty($request->id_cita)) {
                DB::table('citas')->insert([
                        'id_establecimiento' => $cita->id_establecimiento,
                        'id_asegurado' => $cita->id_asegurado,
                        'id_titular' => $cita->id_titular,
                        'parentesco' => $cita->parentesco,
                        'grado' => $cita->grado,
                        'id_sub_consultorio' => $cita->id_sub_consultorio,
                        'id_medico' => $cita->id_medico,
                        'fecha_hora' => Carbon::now(),
                        'id_pp' => $PresPro->id,
                        'id_estado_cita' => 4,
                        'id_estado_reg' => 1,
                        'created_at' => Carbon::now(),
                        'created_us' => auth()->user()->id,
                        'id_tipo_cita' => 1
                    ]);
                $ultima_cita = DB::table('citas')->where('id_asegurado', $cita->id_asegurado)->orderBy('id', 'desc')->first();
                DB::table('citas_llamados')->insert([
                        'id_cita' => $ultima_cita->id,
                        'id_establecimiento' => $cita->id_establecimiento,
                        'id_asegurado' => $cita->id_asegurado,
                        'id_titular' => $cita->id_titular,
                        'parentesco' => $cita->parentesco,
                        'grado' => $cita->grado,
                        'id_sub_consultorio' => $cita->id_sub_consultorio,
                        'id_medico' => $cita->id_medico,
                        'fecha_hora' => Carbon::now(),
                        'id_estado_cita' => 4,
                        'id_estado_reg' => 1,
                        'created_at' => Carbon::now(),
                        'created_us' => auth()->user()->id,
                        'id_tipo_cita' => 1
                    ]);
                }else{

                    if (is_numeric($request->id_cita)) {
                    $rowActualizadoCita = DB::update("UPDATE citas SET id_estado_cita = ?, updated_us = ?, updated_at = ? WHERE id = ?",
                        [4, auth()->user()->id, Carbon::now(), $request->id_cita]
                    );
                    $rowActualizadoCita_por_pp = DB::update("UPDATE prestacion_procedimientos SET flag_observ_citmul = ? WHERE id = ?",
                        [4,$PresPro->id]
                    );
                    $rowActualizadoCitaLlamado =DB::update("UPDATE citas_llamados SET id_estado_cita = ?, updated_us = ?, updated_at = ? WHERE id_cita = ? ",[4,auth()->user()->id,Carbon::now(),$request->id_cita]);
                    }
                }

            /*if (is_numeric($request->id_cita)) {
                $rowActualizadoCita = DB::update(
                    "UPDATE citas SET id_estado_cita = ?, updated_us = ?, updated_at = ? WHERE id = ?",
                    [4, auth()->user()->id, Carbon::now(), $request->id_cita]
                );

            }*/
            //$rowActualizadoCitaLlamado =DB::update("UPDATE citas_llamados SET id_estado_cita = ?, updated_us = ?, updated_at = ? WHERE id_cita = ? ",[4,auth()->user()->id,Carbon::now(),$request->id_cita]);
            Session::flash('flash_message', 'Se registro con exito'); // AHORA SALDRA EL MENSAJE
        else:
            Session::flash('flash_message', 'No se ha podido registrar, verifique y vuelva a intentarlo');
        endif;
        if((auth()->user()->tipo_registro == 1 || auth()->user()->tipo_registro == 7) && auth()->user()->flagfiscalizador == 1){
            return redirect()->route('radiologia_informe_validacion', [
                'id_radiologia' => $request->input('id_radiologia'),
                'id_servicio_radiologia' => $request->input('id_servicio_radiologia'),
                'id_pp' => is_array($request->input('id_pres_proc')) 
                            ? $request->input('id_pres_proc')[0] 
                            : $request->input('id_pres_proc')
            ]);
        }else{
            session()->flash('activar_autoenvio', true);
            return redirect('all_radiologia_prescritas_by_ipress');
        }
        
    }

    /*
    Mostar formulario de modificacion del INFORME RADIOLOGICO
    */
    public function radiologia_informe_validacion($id, $id_servicio_radiologia, $id_pp) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $id_user = Auth::user()->id;
        $id_farmacia = Auth::user()->id_farmacia;
        
        $modelFarmacia = new Farmacia();
        $model_cie = new \App\Models\Diagnostico;
        $model_consultorio = new \App\Models\Consultorio;
        $model_diagnostico = new \App\Models\Diagnostico;
        $model_kardex = new \App\Models\Kardexe();    
        $model_medico = new \App\Models\Medico();
        $model_usuario = new \App\Models\User;
        
        $fechaServidor = $model_kardex->getFechaServidor();
        //-----------------------------------------------        
        $nivel = $modelFarmacia->ObtenerNivelFarmacia($id_farmacia);
        $farma = $modelFarmacia->GetFarmaByIdfarma($id_farmacia);
        $tipo_atencion = $modelFarmacia->GetFarmaciaTipoAtencionByIdFarmacia($id_farmacia);
        $id_nivel=$nivel->nivel;

        $tipo = "dni";      
        //------------------------------------------------------------------
        $fecha_hoy = date('d-m-Y');
        //------------------------------------------------------------------
        $establecimiento = $modelFarmacia->GetIpressByIduser($id_user);
        $id_ipress = $establecimiento->id;

        $user = $model_usuario->GetById($id_user);
        $flagresponsablefarmacia = $user->flagresponsablefarmacia;
        $flagfiscalizador = $user->flagfiscalizador;

        $id_ipress = $establecimiento->id;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $consultorio = $model_consultorio->ShowConsultorios($id_farmacia);
        $errorWS = 1;
        /*********************************************************************************************************/
        $model_diagnosticos = new \App\Models\Receta_diagnostico;
        
        $fecha_fin = $model_kardex->getFechaServidor();
        $id_farmacia = Auth::user()->id_farmacia;
        $farmacia = DB::table('farmacias')->where('id', '=', $id_farmacia)->first();
        $id_establecimiento = $farmacia->id_establecimiento;
        
        /************************************************************************************************************/
        $model_prestacion = new \App\Models\Prestaciones;
        $model_radiologia = new \App\Models\Radiologia;        
	    $model_plantilla = new \App\Models\Plantilla_radiologia;
        $model_referencia = new \App\Models\Referencia;   
        $radiologia = $model_radiologia->GetRadiologiaById($id);
        $id_prestacion=$radiologia->id_prestacion;

        //**** */
        $prestacion_tipo_atencion = DB::table('prestaciones')->select('prestaciones.id_tipo_atencion')->where('prestaciones.id','=',$id_prestacion)->first();
        $id_tipo_atencion = $prestacion_tipo_atencion->id_tipo_atencion;
        switch ($id_tipo_atencion) {
            case '1': //CONSULTA EXTERNA
                //$prestacion = $model_prestacion->GetPrestacionById($id_prestacion); // comentado el 24-05-26
                $prestacion = $this->GetProcedimientosRad_PrestacionById($id_prestacion);
                $referencia = $prestacion->referido;
                // if ($referencia == "SI") {
                //     $referencia_datos = $model_referencia->obtenerDatosDeRefereanciaParaAtencionLabPorId($prestacion->id_referencia);
                //     $diagnostico_referencia = $model_prestacion->GetDiagnosticoByIdPrestacion($referencia_datos->id_prestacion_referencia);
                // }
                if($id_prestacion>1910698){
                    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionydiag_Test($id_prestacion, 1, $radiologia->id_evaluacion);
                } else {
                    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 1);
                }
                break;
                
            case '2': //EMERGENCIA
                $prestacion = $model_prestacion->GetPrestacionEmergenciaRadiologiaById([$id_prestacion]);
                $referencia = $prestacion->id_motivo_ingreso;
                //if ($prestacion->id_motivo_ingreso == 4) {
                    //En emergencia no usa el módulo de referencia
                    //$referencia_datos = $model_referencia->obtenerDatosDeRefereanciaParaAtencionLabPorId($prestacion->id_referencia);
                    //$diagnostico_referencia = $model_prestacion->GetDiagnosticoByIdPrestacion($referencia_datos->id_prestacion_referencia);
                //}
                if($id_prestacion>1910698){
                    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionydiag_Test($id_prestacion, 1, $radiologia->id_evaluacion);
                } else {
                    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 1);
                }
                break;

            case '3': //HOSPITALIZACIÓN
                if(!empty($radiologia->id_evaluacion)){
                    $evolucion = $model_radiologia->GetRadiologiaById_ev($id);
                }
                // $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);// comentado el 24-05-26
                $prestacion = $this->GetProcedimientosRad_PrestacionById($id_prestacion);
                $referencia = 'FALTA';//$prestacion->id_motivo_ingreso;
                //if ($prestacion->id_motivo_ingreso == 4) {
                    //En emergencia no usa el módulo de referencia
                    //$referencia_datos = $model_referencia->obtenerDatosDeRefereanciaParaAtencionLabPorId($prestacion->id_referencia);
                    //$diagnostico_referencia = $model_prestacion->GetDiagnosticoByIdPrestacion($referencia_datos->id_prestacion_referencia);
                //}
                $model_emergencia = new \App\Models\Emergencia;
                $hospitalizacion = \App\Models\Hospitalizacione::where('id', $prestacion->id_ext)->first();
                $camas = $model_emergencia->GetObtenerCama($hospitalizacion->id_cama_estado);
                if($id_prestacion>1910698){
                    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionydiag_Test($id_prestacion, 1, $radiologia->id_evaluacion);
                } else {
                    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 1);
                }
                break;
        }
        $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
        $procradiologia = $model_prestacion->GetProcedimientosByIdPrestacionAndIdLabRadAndTipoAndIdServicioRad($id, $id_servicio_radiologia, $id_pp);
        $categoria_radiologias = DB::table('categoria_radiologias')->select('categoria_radiologias.*')->where('id_servicio', $id_servicio_radiologia)->where('nivel',$id_nivel)->first();
	    $plantillas = $model_plantilla->PlantillaServicio($categoria_radiologias->id,$id_nivel);
        
        /*****************************/
        $total_informe = 0;
        foreach ($procradiologia as $row) {
            // 0 = tiene examenes, 
            // 1 = eliminado, 
            // 2 = ws cambia el estado obtiene,
            // 3 = modificado, 
            // 4 = no registrado
            if (($row->confirmar_toma == '0' || $row->confirmar_toma == '3') && $row->total_informe > 0) {
                $total_informe = $row->total_informe;           
            }
        }
        $bool_contenedor_profesional_imagenes = "true";
        foreach ($procradiologia as $row) {
            // 0 = tiene examenes, 
            // 1 = eliminado, 
            // 2 = ws cambia el estado obtiene,
            // 3 = modificado, 
            // 4 = no registrado
            if ($row->confirmar_toma !== '1' && $row->confirmar_toma !== '4' && $row->confirmar_toma !== null && $row->confirmar_toma !== '') {
                $bool_contenedor_profesional_imagenes = "true";
                break; // Si encontramos un registro que no cumple, salimos del bucle
            }
            $bool_contenedor_profesional_imagenes = "false";
        }
        
        $observacion_examen = $procradiologia[0]->observacion_registro_informe;
        $pendiente_firma = $model_prestacion->GetDocumentosPendintesFirmarPrestacionId($id_prestacion);
        $flag_pendiente_firma = (count($pendiente_firma) > 0) ? 1 : 0;

        $cant_pp_registrados = 0;

        foreach ($procradiologia as $row) {
            if ($row->confirmar_toma == '0' || $row->confirmar_toma == '3') {
                $cant_pp_registrados++;
            }
        }
        /*****************************/
        $client = new SoapClient(env('WI_SALUDPOL_URL') . "/ws/WS_AfiliadoSP_Serv.php?wsdl", array('login' => env('WI_SALUDPOL_USERNAME'), 'password' => env('WI_SALUDPOL_PASSWORD')));

        if($prestacion->tipo_doc_ident=='DNI'){ 
            $tipo_doc_ident = '1';
        }else if($prestacion->tipo_doc_ident=='CE'){ 
            $tipo_doc_ident = '2';
        }else if($prestacion->tipo_doc_ident=='PAS'){ 
            $tipo_doc_ident = '3';
        }else if($prestacion->tipo_doc_ident=='DIE'){ 
            $tipo_doc_ident = '4';
        }

        $beneficiario = $client->getAseguradoValidate($tipo_doc_ident, $prestacion->nro_doc_ident);

        if (is_object($beneficiario)) :
            $flag = 0 ;
            $errorWS = 1 ;
        else:
            $flag = 1 ;
            $errorWS = 0 ;
        endif;

        if ($errorWS == 0) {
            $toma_registrada = DB::table('prestacion_procedimientos')
                ->where('id', $id_pp)
                ->whereNotNull('confirmar_toma_created_at')
                ->exists();
            if ($toma_registrada) {
                $errorWS = 1;
            }
        }

        if ($errorWS > 0) {
            return view('frontend.radiologias.form_radiologia_informe_validacion', compact('prestacion','diagnostico','radiologia','evolucion','procradiologia','plantillas','id_tipo_atencion','referencia','procedimiento','total_informe','flag_pendiente_firma','id_servicio_radiologia','observacion_examen','cant_pp_registrados','referencia_datos','diagnostico_referencia','id_pp','bool_contenedor_profesional_imagenes','camas'));    
        }else{
            if($id_nivel == 3){
                return redirect('/all_radiologia_prescritas_by_ipress')->withInput()->withErrors(array('El paciente ya no esta asegurado, por consiguiente no tiene derecho de atención, realizar los tramites correspondiente con SALUDPOL..!'));
            }else{
                return redirect('/all_radiologias_prescritas_by_ipress_nivel1y2')->withInput()->withErrors(array('El paciente ya no esta asegurado, por consiguiente no tiene derecho de atención, realizar los tramites correspondiente con SALUDPOL..!'));
            }
        }
    }
    

        public function radiologia_firmar_informe($id, $id_servicio_radiologia, $id_pp) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $id_user = Auth::user()->id;
        $id_farmacia = Auth::user()->id_farmacia;
        
        $modelFarmacia = new Farmacia();
        $model_cie = new \App\Models\Diagnostico;
        $model_consultorio = new \App\Models\Consultorio;
        $model_diagnostico = new \App\Models\Diagnostico;
        $model_kardex = new \App\Models\Kardexe();    
        $model_medico = new \App\Models\Medico();
        $model_usuario = new \App\Models\User;
        
        $fechaServidor = $model_kardex->getFechaServidor();
        //-----------------------------------------------        
        $nivel = $modelFarmacia->ObtenerNivelFarmacia($id_farmacia);
        $farma = $modelFarmacia->GetFarmaByIdfarma($id_farmacia);
        $tipo_atencion = $modelFarmacia->GetFarmaciaTipoAtencionByIdFarmacia($id_farmacia);
        $id_nivel=$nivel->nivel;

        $tipo = "dni";      
        //------------------------------------------------------------------
        $fecha_hoy = date('d-m-Y');
        //------------------------------------------------------------------
        $establecimiento = $modelFarmacia->GetIpressByIduser($id_user);
        $id_ipress = $establecimiento->id;

        $user = $model_usuario->GetById($id_user);
        $flagresponsablefarmacia = $user->flagresponsablefarmacia;
        $flagfiscalizador = $user->flagfiscalizador;

        $id_ipress = $establecimiento->id;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $consultorio = $model_consultorio->ShowConsultorios($id_farmacia);
        $errorWS = 1;
        /*********************************************************************************************************/
        $model_diagnosticos = new \App\Models\Receta_diagnostico;
        
        $fecha_fin = $model_kardex->getFechaServidor();
        $id_farmacia = Auth::user()->id_farmacia;
        $farmacia = DB::table('farmacias')->where('id', '=', $id_farmacia)->first();
        $id_establecimiento = $farmacia->id_establecimiento;
        
        /************************************************************************************************************/
        $model_prestacion = new \App\Models\Prestaciones;
        $model_radiologia = new \App\Models\Radiologia;        

        $radiologia = $model_radiologia->GetRadiologiaById($id);
        if(!empty($radiologia->id_evaluacion)){
            $evolucion = $model_radiologia->GetRadiologiaById_ev($id);
        }
        $id_prestacion=$radiologia->id_prestacion;
        // $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
        $prestacion_tipo_atencion = DB::table('prestaciones')->select('prestaciones.id_tipo_atencion')->where('prestaciones.id','=',$id_prestacion)->first();
        $id_tipo_atencion = $prestacion_tipo_atencion->id_tipo_atencion;
        switch ($id_tipo_atencion) {
            case '1': //CONSULTA EXTERNA
                // $prestacion = $model_prestacion->GetPrestacionById($id_prestacion); //comentado el 24-05-26
                $prestacion = $this->GetProcedimientosRad_PrestacionById($id_prestacion);
                break;
            case '2': //EMERGENCIA
                $prestacion = $model_prestacion->GetPrestacionEmergenciaRadiologiaById([$id_prestacion]);
                break;
            case '3': //HOSPITALIZACIÓN
                if(!empty($radiologia->id_evaluacion)){
                    $evolucion = $model_radiologia->GetRadiologiaById_ev($id);
                }
                // $prestacion = $model_prestacion->GetPrestacionById($id_prestacion); //comentado el 24-05-26
                $prestacion = $this->GetProcedimientosRad_PrestacionById($id_prestacion);

                $model_emergencia = new \App\Models\Emergencia;
                $hospitalizacion = \App\Models\Hospitalizacione::where('id', $prestacion->id_ext)->first();
                $camas = $model_emergencia->GetObtenerCama($hospitalizacion->id_cama_estado);
                break;
        }
        $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
        $procradiologia = $model_prestacion->GetProcedimientosByIdPrestacionAndIdLabRadAndTipoAndIdServicioRad($id, $id_servicio_radiologia, $id_pp);
        /*****************************/
        $model_plantilla = new \App\Models\Plantilla_radiologia;
        $plantillas = $model_plantilla->PlantillaServicio($id_servicio_radiologia,$id_nivel);
        $total_informe = 0;
        foreach ($procradiologia as $row) {
            // 0 = tiene examenes, 
            // 1 = eliminado, 
            // 2 = ws cambia el estado obtiene,
            // 3 = modificado, 
            // 4 = no registrado
            if (($row->confirmar_toma == '0' || $row->confirmar_toma == '3') && $row->total_informe > 0) {
                $total_informe = $row->total_informe;
            }
        }
        
        $pendiente_firma = $model_prestacion->GetDocumentosPendintesFirmarPrestacionId($id_prestacion);
        //dd($pendiente_firma);
        $flag_pendiente_firma = (count($pendiente_firma) > 0) ? 1 : 0;
        return view('frontend.radiologias.form_firmar_imagenologia', compact('prestacion','diagnostico','radiologia','procradiologia','evolucion','id_tipo_atencion','id_servicio_radiologia','plantillas','total_informe','flag_pendiente_firma','id_pp'));    
    }

    /*
    Modificar el INFORME RADIOLOGICO
    */
    public function modificar_validar_radiologia_informe(Request $request) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        //dd($request);
        $total_examenes = count($request->id_pres_proc_total);
        $nro_proc = count($request->id_pres_proc);
        
        $res = ""; $i=0;
        $j = 0;
        //dd($request);
        
        DB::beginTransaction();

        if ( auth()->user()->tipo_registro == 6 ) {  //Perfil Digitador de Radiología

            try {
                if ($total_examenes == 1) {
                    if ( isset($request->id_pres_proc) ) {

                        foreach ($request->id_pres_proc as $row):
                            
                            $parrafo=$request->parrafo1;
                            $tipo_plantilla=$request->id_plantilla1;
                            $total_informe = 0;
                            $tipo_plantilla = (is_numeric($tipo_plantilla)) ? intval($tipo_plantilla) : null;


                            $affected = DB::update("UPDATE prestacion_procedimientos SET observacion_registro_informe = ?, updated_at_digitador_radiologia = ?, updated_us_digitador_radiologia = ? WHERE id = ? AND tipo_procedimiento = ? AND id_lab_rad = ?",[$request->observacion_examen, Carbon::now(), Auth::user()->id, $row, 3,$request->id_radiologia]);
                            
                        endforeach;

                    }

                    if ( isset($request->id_pres_proc_total) ) {
                        //Actulizamos estado confirmar_toma
                        foreach ($request->id_pres_proc_total as $row) {
                            
                            $array_estado       = $request->estado;
                            $estado             = $array_estado[$j];

                            $affected = DB::update("UPDATE prestacion_procedimientos SET confirmar_toma = ?, confirmar_toma_updated_at = ?, confirmar_toma_updated_us = ? WHERE id = ?",[$estado, Carbon::now(), Auth::user()->id, $row]);
                            $j++;
                        }
                    }

                } else {
                    //dd("total_examenes > 1");
                    if ( isset($request->id_pres_proc) ) {
                        foreach ($request->id_pres_proc as $row):
                            if($request->id_examen_plantilla_2=='1'):
                                $parrafo=$request->parrafo1;
                                $tipo_plantilla=$request->id_plantilla1;
                                $total_informe = 1;
                                $tipo_plantilla = (is_numeric($tipo_plantilla)) ? intval($tipo_plantilla) : null;

                                //dd('Entro en condición todos los examenes en una sola platilla');
                            else:
                                $parrafo=$request->parrafo[$i];
                                $tipo_plantilla=$request->id_plantilla[$i];
                                $total_informe = 2;
                                //dd('Entro en condición una plantilla por examen');
                            endif;
                            
                            $affected = DB::update("UPDATE prestacion_procedimientos SET observacion_registro_informe = ?, updated_at_digitador_radiologia = ?, updated_us_digitador_radiologia = ? WHERE id = ? AND tipo_procedimiento = ? AND id_lab_rad = ?",[$request->observacion_examen, Carbon::now(), Auth::user()->id, $row, 3,$request->id_radiologia]);
                            $i++;
                        endforeach;
                    }

                    if ( isset($request->id_pres_proc_total) ) {
                        //Actulizamos estado confirmar_toma
                        foreach ($request->id_pres_proc_total as $row) {
                            
                            $array_estado       = $request->estado;
                            $estado             = $array_estado[$j];

                            $affected = DB::update("UPDATE prestacion_procedimientos SET confirmar_toma = ?, confirmar_toma_updated_at = ?, confirmar_toma_updated_us = ? WHERE id = ?",[$estado, Carbon::now(), Auth::user()->id, $row]);
                            $j++;
                        }
                    }

                }
                DB::commit();
                $res = "Y";
            } catch (\Exception $e) {
                DB::rollback();
                Log::error("Modificar Valdiar Radiologia Informe (1 bloque): " . 
                    "\n\nmensaje: " . $e->getMessage() . 
                    "\n\ndatos del request: " . json_encode($request->all()) . 
                    "\n\nstack trace: " . $e->getTraceAsString()
                );
                throw $e;
            } catch (\Throwable $th) {
                DB::rollback();
                Log::critical("Modificar Valdiar Radiologia Informe (1 bloque): " . 
                    "\n\nmensaje: " . $th->getMessage() . 
                    "\n\ndatos del request: " . json_encode($request->all()) . 
                    "\n\nstack trace: " . $th->getTraceAsString()
                );
                throw $th;
            }

        }

        if ( auth()->user()->tipo_registro == 1 || auth()->user()->tipo_registro == 7 ) { //Perfil Prescriptor Médico || Profesional de Imagenes
            if ($request->bool_contenedor_profesional_imagenes == 'false') {
                try {
                    if ( isset($request->id_pres_proc_total) ) {
                        //Actulizamos estado confirmar_toma
                        foreach ($request->id_pres_proc_total as $row) {
                            
                            $array_estado       = $request->estado;
                            $estado             = $array_estado[$j];

                            $affected = DB::update("UPDATE prestacion_procedimientos SET confirmar_toma = ?, confirmar_toma_updated_at = ?, confirmar_toma_updated_us = ?, observacion_registro_informe = ?, updated_at_digitador_radiologia = ?, updated_us_digitador_radiologia = ?  WHERE id = ?",[$estado, Carbon::now(), Auth::user()->id, $request->observacion_examen, Carbon::now(), Auth::user()->id, $row]);
                            $j++;
                        }
                    }
    
                    DB::commit();
                    $res = "Y";
                } catch (\Exception $e) {
                    DB::rollback();
                    Log::error("Modificar Valdiar Radiologia Informe (2 bloque): " . 
                        "\n\nmensaje: " . $e->getMessage() . 
                        "\n\ndatos del request: " . json_encode($request->all()) . 
                        "\n\nstack trace: " . $e->getTraceAsString()
                    );
                    throw $e;
                } catch (\Throwable $th) {
                    DB::rollback();
                    Log::critical("Modificar Valdiar Radiologia Informe (2 bloque): " . 
                        "\n\nmensaje: " . $th->getMessage() . 
                        "\n\ndatos del request: " . json_encode($request->all()) . 
                        "\n\nstack trace: " . $th->getTraceAsString()
                    );
                    throw $th;
                }
            }
            if ($request->bool_contenedor_profesional_imagenes == 'true') {
                try {
                    if ($total_examenes == 1) {
                        if ( isset($request->id_pres_proc) ) {
    
                            foreach ($request->id_pres_proc as $row):
                                
                                $parrafo=$request->parrafo1;
                                $tipo_plantilla=$request->id_plantilla1;
                                $total_informe = 1;
                                $tipo_plantilla = (is_numeric($tipo_plantilla)) ? intval($tipo_plantilla) : null;
                                $affected = DB::update("UPDATE prestacion_procedimientos SET informe_parrafo = ?, flag_informe = ?, total_informe = ?, tipo_plantilla = ?, observacion_registro_informe = ?, updated_at_registro_informe = ?, updated_us_registro_informe = ?,validated_us = ?, validated_at = ?, flag_validated = ? WHERE id = ? AND tipo_procedimiento = ? AND id_lab_rad = ?",[$parrafo, '1', $total_informe, $tipo_plantilla, $request->observacion_examen, Carbon::now(), Auth::user()->id,Auth::user()->id, Carbon::now(), '1', $row, 3,$request->id_radiologia]);
                            endforeach;
    
                        }
    
                        if ( isset($request->id_pres_proc_total) ) {
                            //Actulizamos estado confirmar_toma
                            foreach ($request->id_pres_proc_total as $row) {
                                
                                $array_estado       = $request->estado;
                                $estado             = $array_estado[$j];
    
                                $affected = DB::update("UPDATE prestacion_procedimientos SET confirmar_toma = ?, confirmar_toma_updated_at = ?, confirmar_toma_updated_us = ? WHERE id = ?",[$estado, Carbon::now(), Auth::user()->id, $row]);
                                $j++;
                            }
                        }
    
                    } else {
                        //dd("total_examenes > 1");
                        if ( isset($request->id_pres_proc) ) {
                            foreach ($request->id_pres_proc as $row):
                                if($request->id_examen_plantilla_2=='1'):
                                    $parrafo=$request->parrafo1;
                                    $tipo_plantilla=$request->id_plantilla1;
                                    $total_informe = 1;
                                    //dd('Entro en condición todos los examenes en una sola platilla');
                                    $tipo_plantilla = (is_numeric($tipo_plantilla)) ? intval($tipo_plantilla) : null;
                                else:
                                    $parrafo=$request->parrafo[$i];
                                    $tipo_plantilla=$request->id_plantilla[$i];
                                    $total_informe = 2;
                                    $tipo_plantilla = (is_numeric($tipo_plantilla)) ? intval($tipo_plantilla) : null;
                                    //dd('Entro en condición una plantilla por examen');
                                endif;
                                
                                $affected = DB::update("UPDATE prestacion_procedimientos SET informe_parrafo = ?, flag_informe = ?, total_informe = ?, tipo_plantilla = ?, observacion_registro_informe = ?, updated_at_registro_informe = ?, updated_us_registro_informe = ? WHERE id = ? AND tipo_procedimiento = ? AND id_lab_rad = ?",[$parrafo, '1', $total_informe, $tipo_plantilla, $request->observacion_examen, Carbon::now(), Auth::user()->id, $row, 3,$request->id_radiologia]);
                                $i++;
                            endforeach;
                        }
    
                        if ( isset($request->id_pres_proc_total) ) {
                            //Actulizamos estado confirmar_toma
                            foreach ($request->id_pres_proc_total as $row) {
                                
                                $array_estado       = $request->estado;
                                $estado             = $array_estado[$j];
    
                                $affected = DB::update("UPDATE prestacion_procedimientos SET confirmar_toma = ?, confirmar_toma_updated_at = ?, confirmar_toma_updated_us = ? WHERE id = ?",[$estado, Carbon::now(), Auth::user()->id, $row]);
                                $j++;
                            }
                        }
    
                    }
                    DB::commit();
                    $res = "Y";
                } catch (\Exception $e) {
                    DB::rollback();
                    Log::error("Modificar Valdiar Radiologia Informe (3 bloque): " . 
                        "\n\nmensaje: " . $e->getMessage() . 
                        "\n\ndatos del request: " . json_encode($request->all()) . 
                        "\n\nstack trace: " . $e->getTraceAsString()
                    );
                    throw $e;
                } catch (\Throwable $th) {
                    DB::rollback();
                    Log::critical("Modificar Valdiar Radiologia Informe (3 bloque): " . 
                        "\n\nmensaje: " . $th->getMessage() . 
                        "\n\ndatos del request: " . json_encode($request->all()) . 
                        "\n\nstack trace: " . $th->getTraceAsString()
                    );
                    throw $th;
                }
            }

        }
        // return $res;
        if ($res=="Y"):
            Session::flash('flash_message', 'Se modificó con éxito'); // AHORA SALDRA EL MENSAJE
        else:
            Session::flash('flash_message', 'No se ha podido modificar, verifique y vuelva a intentarlo');
        endif;

        return redirect('/radiologia_informe_validacion/'.$request->id_radiologia.'/'.$request->id_servicio_radiologia.'/'.$request->id_pres_proc_total[0]);
        
    }

    /*
    Validar el INFORME RADIOLOGICO
    */
    public function validar_radiologia_informe(Request $request) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $res = "";
        DB::beginTransaction();
        try {
            foreach ($request->id_pres_proc as $row):
                $affected = DB::update("UPDATE prestacion_procedimientos SET validated_us = ?, validated_at = ?, flag_validated = ? WHERE id = ? ",
                                    [Auth::user()->id, Carbon::now(), '1', $row]);
            endforeach;

            DB::commit();
            $res = "Y";
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        if ($res=="Y"):
            Session::flash('flash_message', 'El informe ha sido validado con éxito'); // AHORA SALDRA EL MENSAJE
        else:
            Session::flash('flash_message', 'No se ha podido validar el informe, verifique y vuelva a intentarlo');
        endif;
        session()->flash('activar_autoenvio', true);
        return redirect('/all_radiologia_prescritas_by_ipress');
    }

    public function invalidar_examen_imagenologia(Request $request)
    {
    
        if (!Auth::check()) :
          return redirect('/auth/login');
        endif;

        //0 Orden ya se encuenta anulada
        //1 se registro correctamente la invalidación
        //2 No se puede anular, al menos un examen de la orden ya tiene informe
        //3 Error al anular
        
        $id_radiologia = $request->id_radiologia;
        $id_user = $request->id_user;
        $id_prestacion = $request->id_prestacion;
        $datos = DB::table('prestacion_procedimientos')
                ->select('prestacion_procedimientos.id')
                ->where('prestacion_procedimientos.id_lab_rad', '=', $id_radiologia)                
                ->where('prestacion_procedimientos.tipo_procedimiento', '=', 3)                
                ->where('prestacion_procedimientos.id_estado_reg', '=', 1)                
                ->whereNotNull('prestacion_procedimientos.created_at_registro_informe')                
                ->orderBy('prestacion_procedimientos.id', 'asc')
                ->get();

        $radiologia = Radiologia::find($id_radiologia);
        if($radiologia->id_establecimiento ==76){
            //se obtiene la evolución de la radiología
            $rad_evol = (!empty($radiologia) && !empty($radiologia->id_evaluacion)) ? $radiologia->id_evaluacion : null;
            // solo para examenes que fueron registrados mediante el nuevo modal
            $rua_rad=DB::table('rua_imagenologia')->where('id_rad', $id_radiologia)->where('id_evol', $rad_evol)->exists();
        }
        // Si ningun examen de la orden cuenta con informe
        if (count($datos) == 0) {

            // Se valida si la orden esta habilitada
            if ($radiologia->estado == 1) {
                
                $radiologia->estado = 0; 
                $radiologia->obs_examen_invalidado = $request->motivo_imagenologia; 
                $radiologia->id_user_examen_invalidado = $request->id_user; 
                $radiologia->flag_invalidado = 1;
                $radiologia->fecha_orden_invalidado = Carbon::now();

                if ($radiologia->save()) {
                    // Orden anulada
                    if($radiologia->id_establecimiento ==76){
                        if ($rua_rad) { //una vez guardada la anulación también cambiamos de estado la tabla rua_imagenología
                            DB::table('rua_imagenologia')->where('id_rad', $id_radiologia)->where('id_evol', $rad_evol)->update(['id_estado_reg' => 0]);
                        }
                    }
                    return "1";
                } else {
                    // Error al anular la orden
                    return "3";
                }

            } else {
                // Orden ya se encuenta anulada
                return "0";
            }
        } elseif (count($datos) > 0) {
            // Al menos un examen de la orden ya tiene informe
            return "2";
        }

    }
   
   public function index_plantilla() {
        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;

        $model = new Plantilla_radiologia;
        $modelFarmacia = new \App\Models\Farmacia();
        $nivel = $modelFarmacia->ObtenerNivelFarmacia(Auth::user()->id_farmacia);
        $id_nivel=$nivel->nivel;

        $tipo_plantillas = $model->ShowAll($id_nivel);
        $ruta = Route::getCurrentRoute()->getPath();
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        return view('frontend.radiologias.plantillas.all', compact('tipo_plantillas', 'permiso'));
    }

    public function create_plantilla()
    {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $model_radiologia = new \App\Models\Radiologia();
        $modelFarmacia = new \App\Models\Farmacia();
        $nivel = $modelFarmacia->ObtenerNivelFarmacia(Auth::user()->id_farmacia);
        $id_nivel=$nivel->nivel;

        $lista_categorias = $model_radiologia->getTodasCategoriasRadiologias($id_nivel);

        return view('frontend.radiologias.plantillas.create', compact('lista_categorias'));
    }

    public function store_plantilla(Request $request) {
        $nombre = trim($request->nombre_plantilla);
        $plantilla = new Plantilla_radiologia;
        $plantilla->nombre_plantilla = trim($request->nombre_plantilla);
        $plantilla->id_categoria_radiologia = trim($request->id_servicio);
        $plantilla->cabecera = trim($request->titulo);
        $plantilla->cuerpo = trim($request->cuerpo_plantilla);
        $plantilla->pie_pagina = trim($request->footer_plantilla);
        $plantilla->estado = 1;

        $model_tipo = new Plantilla_radiologia;
        $existe = $model_tipo->ExistePlantilla($nombre, $request->id_servicio);
        if ($existe) {
            return redirect('/all_plantilla_radiologias')
                            ->withInput()
                            ->withErrors(array('No se ha podido registrar, la plantilla ya existe'));
        } else {
            $plantilla->save();
            Session::flash('flash_message', 'Registro exitoso');
            return redirect('/all_plantilla_radiologias');
        }
    }

    public function edit_plantilla($id)
    {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $plantilla = Plantilla_radiologia::findOrFail($id);
        $model_radiologia = new \App\Models\Radiologia();
        $modelFarmacia = new \App\Models\Farmacia();
        $nivel = $modelFarmacia->ObtenerNivelFarmacia(Auth::user()->id_farmacia);
        $id_nivel=$nivel->nivel;
        $lista_categorias = $model_radiologia->getTodasCategoriasRadiologias($id_nivel);
        return view('frontend.radiologias.plantillas.edit', compact('plantilla','lista_categorias'));
    }

    public function update_plantilla(Request $request)
    {
        $nombre=trim($request->nombre_plantilla);
        $update=Plantilla_radiologia::where('id','=',$request->id)->first();
        $update->nombre_plantilla=trim($request->nombre_plantilla);
        $model_tipo=new Plantilla_radiologia;
        $existe = $model_tipo->ExistePlantilla($nombre, $request->id);
        $update->id_categoria_radiologia = trim($request->id_servicio);
        $update->cabecera = trim($request->titulo);
        $update->cuerpo = trim($request->cuerpo_plantilla);
        $update->pie_pagina = trim($request->footer_plantilla);
        
        if ($existe) {
            return redirect('/all_plantilla_radiologias')
                            ->withInput()
                            ->withErrors(array('No se ha podido actualizar, Plantilla de radiologia ya existe'));
        }else{
            $update->save();
            return redirect('/all_plantilla_radiologias')->withFlashSuccess("Se actualizo correctamente");
        }
    }

    public function eliminar_plantilla($id, $estado){
        $update=Plantilla_radiologia::where('id','=',$id)->first();
        $update->estado=$estado;
        $update->save();
        Session::flash('flash_message', 'Se elimino correctamente');
    }

    public function getcargarplantilla($cmbest) {
        $plantilla=Plantilla_radiologia::where('id','=',$cmbest)->first();
        $plantilla_seleccionada=$plantilla->cabecera.' '.$plantilla->cuerpo.' '.$plantilla->pie_pagina; 
        return $plantilla_seleccionada;
    }

    public function modal_siteds_imagenologia($id,$id_asegurado)
  {
    $asegurado = \App\Models\Asegurado::find($id_asegurado);
    return view('frontend.radiologias.modal_siteds', compact('id', 'asegurado'));
  }

  public function update_siteds_imagenologia(Request $request)
  {
    $siteds = Radiologia::find($request->id);
    $siteds->cod_siteds_susalud = $request->codigo_siteds;
    $siteds->updated_at_cod_siteds = Carbon::now();
    $siteds->updated_us_cod_siteds = $request->id_user;
    $siteds->save();

    return redirect('/all_radiologia_prescritas_by_ipress')->withFlashSuccess("Se actualizo correctamente el SITEDS");
  }

    public function buscar_siteds_radiologia($fecha_expedicion,$dni_beneficiario){

        $modelRadiologia = new Radiologia();
        $data = $modelRadiologia->busca_Siteds_Radiologia($dni_beneficiario,$fecha_expedicion);   
        return json_encode($data);
      }
      public function validar_dato_siteds_radiologia($codigo_siteds){

        $modelRadiologia = new Radiologia();
        $data = $modelRadiologia->validar_siteds_receta_vale($codigo_siteds);   
        return json_encode($data);
      }

    public function addCategoriaRadiologias($id_consultorio)
    {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $model_establecimiento = new \App\Models\Establecimiento();
        $establecimiento = $model_establecimiento->GetById( auth()->user()->id_establecimiento );
        $nivel = $establecimiento[0]->nivel;

        if ($id_consultorio == 76) {
            $id_tipo_atencion = 2;
        } else if ($id_consultorio == 105) {
            $id_tipo_atencion = 3;
        } else {
            $id_tipo_atencion = 1;
        }
        
        $model_tipoatencion = new \App\Models\TipoAtencione();
        $tipo_atencion = $model_tipoatencion->VerTipoAtencion($id_tipo_atencion);
        $descripcion_tipo=$tipo_atencion->nombre;
        $id_user = Auth::user()->id;
        
        $model_radiologia = new \App\Models\Radiologia();
        $categorias = $model_radiologia->getTodasCategoriasRadiologias($nivel);
        $model_consultorio = new \App\Models\Consultorio();
        $consultorio = $model_consultorio->GetNombreById($id_consultorio);
    
        $p[] = $nivel;
        $p[] = $id_tipo_atencion;
        $p[] = $id_consultorio;

        $categoria_radiologias = $model_radiologia->getCategoriaNivelRadiologia($p);
        return view('frontend.radiologias.categoria_radiologia', compact('categorias', 'nivel', 'categoria_radiologias', 'id_tipo_atencion', 'descripcion_tipo','consultorio'));
    }

    public function addCategoriaRadiologiasNivel($id_consultorio,$nivel)
    {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        
        if ($id_consultorio == 76) {
            $id_tipo_atencion = 2;
        } else if ($id_consultorio == 105) {
            $id_tipo_atencion = 3;
        } else {
            $id_tipo_atencion = 1;
        }
        
        $model_tipoatencion = new \App\Models\TipoAtencione();
        $tipo_atencion = $model_tipoatencion->VerTipoAtencion($id_tipo_atencion);
        $descripcion_tipo=$tipo_atencion->nombre;
        $id_user = Auth::user()->id;
        
        $model_radiologia = new \App\Models\Radiologia();
        $categorias = $model_radiologia->getTodasCategoriasRadiologias($nivel);
        $model_consultorio = new \App\Models\Consultorio();
        $consultorio = $model_consultorio->GetNombreById($id_consultorio);
    
        $p[] = $nivel;
        $p[] = $id_tipo_atencion;
        $p[] = $id_consultorio;

        $categoria_radiologias = $model_radiologia->getCategoriaNivelRadiologia($p);
        return view('frontend.radiologias.categoria_radiologia_nivel1y2', compact('categorias', 'nivel', 'categoria_radiologias', 'id_tipo_atencion', 'descripcion_tipo','consultorio'));
    }

    public function agregar_categoria_radiologia_atencion(Request $request)
    {
        if (!Auth::check()) :
          return redirect('/auth/login');
        endif;

        $model_radiologia = new \App\Models\Radiologia;
        $id_user = Auth::user()->id;

        $existe = $model_radiologia->ExisteRadCategoriaAtenconNivel($request->id_categoria, $request->tipo, $request->nivel, $request->id_consultorio);

        if (count($existe)>0) {
          return redirect('/add_categoria_radiologias/' . $request->id_consultorio)
            ->withInput()
            ->withErrors(array('No se ha podido registrar la categoría, ya existe en este nivel de atención'));
        } else {
          $model_radiologia->RegistrarCategoriaRadNivelAtencion($request->id_categoria, $request->tipo, $request->nivel, $request->orden,$id_user,$request->id_consultorio);
          Session::flash('flash_message', 'Se agrego correctamento la categoría a este nivel de atención');
          if($request->proviene==1):
            return redirect('/add_categoria_radiologias/' . $request->id_consultorio);
          else:
            return redirect('/add_categoria_radiologias_nivel/' . $request->id_consultorio.'/'.$request->nivel);
          endif;
        }
    }
    
    public function addRadNivel($nivel,$tipo,$id_categoria,$id_consultorio)
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

        $model_procedimiento = new \App\Models\Procedimiento;
        $listar_procedimientos_radiologias = $model_procedimiento->getProcedimientosRad();

        $model_radiologia = new \App\Models\Radiologia;
        
        $listar_examenes = $model_radiologia->getRad($nivel, $tipo, $id_categoria, $id_consultorio);
        $categoria = $model_radiologia->getNombreCategoriasRad($id_categoria);

        return view('frontend.radiologias.registrar_rad_nivel', compact('listar_procedimientos_radiologias','listar_examenes','tipo', 'descripcion_nivel', 'descripcion_tipo','nivel','categoria','id_categoria','id_consultorio'));

    }

    //============================================================================
    public function exam_relacionados_CAT_RAD($id)
    {
        $model_radiologia = new \App\Models\Radiologia;
        $listar_examenes_relacionados = $model_radiologia->exam_relacionados_Categ_RAD($id);

        return response()->json([
            "cantidad" => $listar_examenes_relacionados
        ]);
    }
    //=============================================================================
    // public function desactivar_examen_niv_cat_rad($id, $estado)
    // {
    //     //print_r($estado);

    //     if (!Auth::check()) :
    //         return redirect('/auth/login');
    //     endif;

    //     $id_user = Auth::user()->id;
    //     $model_radiologia = new \App\Models\Radiologia();
    //     $rad_nivate = $model_radiologia->getRadNivelAtencion($id);
    //     if ($estado == "1") {
    //         $db_update_productos = DB::table('radnivelatenciones')
    //         ->where('id', $id)
    //         ->update(['estado' => 0, 'id_user_act_ex' => auth()->user()->id, 'iduser_act_ex_at' => Carbon::now()]);

    //         Session::flash('flash_message', 'Se desactivo correctamente');
    //     } else {
    //         $db_update_productos = DB::table('radnivelatenciones')
    //         ->where('id', $id)
    //         ->update(['estado' => 1, 'id_user_act_ex' => auth()->user()->id, 'iduser_act_ex_at' => Carbon::now()]);

    //         Session::flash('flash_message', 'Se activo correctamente');
    //     }
    // }
    //========================================================================================================
    public function desactivar_examen_niv_cat_rad(Request $request, $id, $estado)
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }

        $id_user = Auth::id();
        $motivo = $request->input('motivo');
        $model_radiologia = new \App\Models\Radiologia();
        $rad_nivate = $model_radiologia->getRadNivelAtencion($id);
        if (!empty($rad_nivate->id_user_update)) {
            $user_anterior = $rad_nivate->id_user_update;
            $fecha_anterior = $rad_nivate->updated_at;
        } else {
            $user_anterior = $rad_nivate->id_user_created;
            $fecha_anterior = $rad_nivate->created_at;
        }
        
        $nuevo_estado = ($estado == "1") ? 0 : 1;
        

        DB::table('radnivelatenciones')
            ->where('id', $id)
            ->update([
                'estado' => $nuevo_estado,
                'id_user_update' => $id_user,
                'updated_at' => Carbon::now(),
            ]);

        // Insertar en radnivelatencione_auditores
        DB::table('radnivelatencione_auditores')->insert([
            'operacion' => 0, 
            'id_radnivelatencione' => $rad_nivate->id,
            'id_procedimiento' => $rad_nivate->id_procedimiento,
            'codigo_rad' => $rad_nivate->codigo_rad,
            'id_categoria' => $rad_nivate->id_categoria,
            'estado_incial' => $rad_nivate->estado,
            'estado' => $nuevo_estado,
            'descripcion_incial' => $rad_nivate->descripcion_corta_backup,
            'created_us' => $id_user,
            'created_at' => Carbon::now(),
            'incial_created_us' => $user_anterior,
            'inicial_created_at' =>$fecha_anterior,
            'motivo'              => $motivo,
        ]);

        $mensaje = ($nuevo_estado == 0) ? 'Se desactivó correctamente' : 'Se activó correctamente';

        Session::flash('flash_message', $mensaje);
    }
    //========================================================================================================

    
//     public function editar_examen_niv_cat_rad(Request $request)
// {
//     if (!Auth::check()) {
//         return redirect('/auth/login');
//     }

//     $id = $request->id;

//     $radnivelatencion = DB::table('radnivelatenciones')
//         ->where('id', $id)
//         ->first();

//     if (!empty($radnivelatencion) && $radnivelatencion->estado == 1) {
//         $updated = DB::table('radnivelatenciones')
//             ->where('id', $id)
//             ->update([
//                 'descripcion_corta' => $request->update_descr_radnivl,
//                 'id_user_edit' => Auth::user()->id,
//                 'iduser_edit_at' => Carbon::now() 
//             ]);

//         return $updated ? "1" : "2";
//     }

//     return "2";
// }
//========================================================================================================
    public function editar_examen_niv_cat_rad(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }
        // dd($request->all());
        $id = $request->id;
        $id_user = Auth::user()->id;
        $motivo = $request->motivo;

        $model_radiologia = new \App\Models\Radiologia();
        $radnivelatencion = $model_radiologia->getRadNivelAtencion($id);

        if (!$radnivelatencion) {
            return "2"; 
        }

        if (!empty($radnivelatencion->id_user_update)) {
            $user_anterior = $radnivelatencion->id_user_update;
            $fecha_anterior = $radnivelatencion->updated_at;
        } else {
            $user_anterior = $radnivelatencion->id_user_created;
            $fecha_anterior = $radnivelatencion->created_at;
        }

        $updated = false;

        if ($radnivelatencion->estado == 1) {
            $updated = DB::table('radnivelatenciones')
                ->where('id', $id)
                ->update([
                    'descripcion_corta' => $request->update_descr_radnivl,
                    'id_user_update' => $id_user,
                    'updated_at' => Carbon::now(),
                ]);
        }

        // Insertar en radnivelatencione_auditores
        DB::table('radnivelatencione_auditores')->insert([
            'operacion'           => 1,
            'id_radnivelatencione'=> $radnivelatencion->id,
            'id_procedimiento'    => $radnivelatencion->id_procedimiento,
            'codigo_rad'          => $radnivelatencion->codigo_rad,
            'id_categoria'        => $radnivelatencion->id_categoria,
            'estado_incial'       => $radnivelatencion->estado,
            'descripcion_incial'  => $radnivelatencion->descripcion_corta_backup,
            'descripcion_nueva'   => $request->update_descr_radnivl,
            'created_us'          => $id_user,
            'created_at'          => Carbon::now(),
            'incial_created_us'   => $user_anterior,
            'inicial_created_at'  => $fecha_anterior,
            'motivo'              => $motivo,
        ]);

        return $updated ? "1" : "2";
    }
//========================================================================================================
    public function desactivar_nivel_categoria_rad($id, $estado)
    {
        //print_r($estado);

        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $id_user = Auth::user()->id;
        
        $model_radiologia = new \App\Models\Radiologia();
        $categorias = $model_radiologia->getCategoriaNivelAtencionRad($id);
        
        if ($estado == "1") {
            $db_update = DB::table('categoria_radiologia_nivels')
            ->where('id', $id)
            ->update(['estado' => 0, 'id_user_update' => $id_user]);

            $db_update_productos = DB::table('radnivelatenciones')
            ->where('id_nivel', $categorias->id_nivel)
            ->where('id_tipo_atencion', $categorias->id_atencion)
            ->where('id_categoria', $categorias->id_categoria_radiologia)
            ->update(['estado' => 0, 'id_user_update' => $id_user]);

            Session::flash('flash_message', 'Se desactivo correctamente');
        } else {
            $db_update = DB::table('categoria_radiologia_nivels')
            ->where('id', $id)
            ->update(['estado' => 1, 'id_user_update' => $id_user]);

            $db_update_productos = DB::table('radnivelatenciones')
            ->where('id_nivel', $categorias->id_nivel)
            ->where('id_tipo_atencion', $categorias->id_atencion)
            ->where('id_categoria', $categorias->id_categoria_radiologia)
            ->update(['estado' => 1, 'id_user_update' => $id_user]);

            Session::flash('flash_message', 'Se activo correctamente');
        }
    }

    public function agregarRadNivelTipoAtencion(Request $request)
    {
        if (!Auth::check()) :
          return redirect('/auth/login');
        endif;

        $model_radiologia = new \App\Models\Radiologia;
        $id_user = Auth::user()->id;

        $existe = $model_radiologia->ExisteRadAtencionNivel($request->id_procedimiento, $request->id_tipo_atencion, $request->id_nivel,$request->descripcion_corta,$request->id_consultorio,$request->id_categoria);

        if (count($existe)>0) {
          return redirect('/add_rad_nivel/' . $request->id_nivel . '/' . $request->id_tipo_atencion. '/' . $request->id_categoria . '/' . $request->id_consultorio)
            ->withInput()
            ->withErrors(array('No se ha podido registrar el examen de laboratorio, ya existe en este nivel de atención'));
        } else {
          $model_radiologia->RegistrarRadNivelAtencion($request->id_procedimiento, $request->id_tipo_atencion, $request->id_nivel,$request->id_categoria, $request->descripcion_corta, $id_user,$request->id_consultorio);
          Session::flash('flash_message', 'Se agrego correctamento el examen de laboratorio a este nivel de atención');
          return redirect('/add_rad_nivel/' . $request->id_nivel . '/' . $request->id_tipo_atencion. '/' . $request->id_categoria . '/' . $request->id_consultorio);
        }
    }

    public function eliminarRadNivelTipoAtencion(Request $request, $id, $tipo_atencion, $nivel, $categoria, $id_consultorio)
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }
        //dd($request->all());

        $id_user = Auth::id();
        $motivo = $request->input('motivo'); 

        $model_radiologia = new \App\Models\Radiologia();
        $radnivelatencion = $model_radiologia->getRadNivelAtencion($id);

        if (!empty($radnivelatencion->id_user_update)) {
            $user_anterior = $radnivelatencion->id_user_update;
            $fecha_anterior = $radnivelatencion->updated_at;
        } else {
            $user_anterior = $radnivelatencion->id_user_created;
            $fecha_anterior = $radnivelatencion->created_at;
        }

        $nuevo_estado = 3; 

        $model_radiologia->ActualizaEstado($id, $id_user);

        DB::table('radnivelatencione_auditores')->insert([
            'operacion'           => 2,
            'id_radnivelatencione'=> $radnivelatencion->id,
            'id_procedimiento'    => $radnivelatencion->id_procedimiento,
            'codigo_rad'          => $radnivelatencion->codigo_rad,
            'id_categoria'        => $radnivelatencion->id_categoria,
            'estado_incial'       => $radnivelatencion->estado,
            'estado'              => $nuevo_estado,
            'descripcion_incial'  => $radnivelatencion->descripcion_corta_backup,
            'descripcion_nueva'   => $radnivelatencion->descripcion_corta,
            'motivo'              => $motivo, 
            'created_us'          => $id_user,
            'created_at'          => Carbon::now(),
            'incial_created_us'   => $user_anterior,
            'inicial_created_at'  => $fecha_anterior,
        ]);
        $mensaje = ($nuevo_estado == 3) ? 'Se eliminó correctamente' : 'Error al eliminar examen';
        Session::flash('flash_message', $mensaje);
        return response()->json(['success' => true]);
    }

    public function obtenerColumnasRadiologiaConsultorios($tipo_atencion,$id_consultorio)
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }

        $consultorio = \App\Models\Consultorio::where('id',$id_consultorio)->first();

        return $consultorio;
    }

    public function registrarColumnaReporteRadiologia(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }

        $consultorio = \App\Models\Consultorio::where('id',$request->id_consultorio)->first();
        $consultorio->reporte_columnas_rad = $request->num_columnas;
        $consultorio->save();
        
        return 'true';
    }

    public function obtener_radiologia_convenio($buscar, $nivel, $genero, $tipo_atencion, $id_servicio = 0)
    {

        $model_procedimiento = new \App\Models\Radiologia;

        if($tipo_atencion==1 && $nivel != 3)
            $id_servicio = 8;

            $procedimiento = $model_procedimiento->getRadiologiaRuaConvenio($buscar, $nivel, $genero, $tipo_atencion, $id_servicio);
           // $procedimiento = $model_procedimiento->getRadiologiaRuaConvenio_hsp($buscar, $nivel, $genero, 3, $id_servicio);

        $array = array();
        foreach ($procedimiento as $leer) {
        $array[] = array(
            "value" => $leer->codigo . " - " . $leer->descripcion,
            "label" => $leer->codigo . " - " . $leer->descripcion,
            "id" => $leer->id,
            "id_categoria" => $leer->id_categoria,
            "codigo_procedimiento" => $leer->codigo
        );
        }

        echo json_encode($array);
    }

    public function listaServiciosImagenes() 
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }
        $model_radiologia = new \App\Models\Radiologia;
        $modelFarmacia = new \App\Models\Farmacia;

        $nivel_ipress = $modelFarmacia->ObtenerNivelFarmacia(Auth::user()->id_farmacia);
        $nivel=$nivel_ipress->nivel;

        $res = $model_radiologia->listarServiciosDeImagenes($nivel);
        
        return $res; 
    }

    public function listaRegistradorImagenes() 
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }
        $model_user = new \App\Models\User;
        $res = $model_user->listarUsuariosConPerfilImagenes( auth()->user()->id_establecimiento );
        
        return $res; 
    }

    public function create_servicio()
    {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $categorias = DB::table('consultorios')->select('consultorios.id', 'consultorios.nombre')->get();

        //listar_categorias
        $model_radiologia = new \App\Models\Radiologia();
        $modelFarmacia = new \App\Models\Farmacia();
        $nivel = $modelFarmacia->ObtenerNivelFarmacia(Auth::user()->id_farmacia);
        $id_nivel=$nivel->nivel;
        $lista_categorias = $model_radiologia->getTodasCategoriasRadiologias($id_nivel);

        return view('frontend.radiologias.servicios.create', compact('categorias','lista_categorias','id_nivel'));
    }

    public function desactivar_categoria_radiologia($id)
    {
        
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $db_update = DB::table('categoria_radiologias')
        ->where('id', $id)
        ->update(['estado' => 0, 'updated_at' => Carbon::now(), 'updated_us' => auth()->user()->id ]);

        Session::flash('flash_message', 'Se desactivo correctamente');
    }

    public function registrarCategoriaRadiologia(Request $request)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        DB::table('categoria_radiologias')->insert([
            'nombre' => $request->descripcion_servicio,
            'estado' => 1,
            'created_at' => Carbon::now(),
            'created_us' => auth()->user()->id,
            'id_servicio' => $request->id_servicio,
            'nivel' => $request->nivel
        ]);

        Session::flash('flash_message', 'Se registró correctamente');
        return redirect('/create_servicio_radiologia');
    }

    public function list_plantillas_informes($cadenaDataString) {
        
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $dataArray = explode(',', $cadenaDataString);
        
        if (!$dataArray[0]):
            return redirect('/all_laboratorio_prescritos_by_ipress');
        endif;

        $model_prestacion = new \App\Models\Prestaciones;
        $model_plantilla = new \App\Models\Plantilla_radiologia;
        $modelFarmacia = new Farmacia();
        $nivel = $modelFarmacia->ObtenerNivelFarmacia($id_farmacia);
        $id_nivel=$nivel->nivel;

        foreach ($dataArray as $key => $value) {
            $procradiologia[] = $model_prestacion->getPresProceById($value);
        }

        $categoria_radiologias = DB::table('categoria_radiologias')->select('categoria_radiologias.*')->where('id_servicio', $procradiologia[0]->id_servicio_categoria_radiologia)->first();
        $plantillas = $model_plantilla->PlantillaServicio($categoria_radiologias->id, $id_nivel);
        
        return view('frontend.radiologias.modal_listar_informes', compact('procradiologia','plantillas'));
    }

    public function list_plantillas_informes_validacion($cadenaDataString) {
        
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $dataArray = explode(',', $cadenaDataString);
        
        if (!$dataArray[0]):
            return redirect('/all_laboratorio_prescritos_by_ipress');
        endif;

        $model_prestacion = new \App\Models\Prestaciones;
        $model_plantilla = new \App\Models\Plantilla_radiologia;
        $modelFarmacia = new Farmacia();
        $nivel = $modelFarmacia->ObtenerNivelFarmacia($id_farmacia);
        $id_nivel=$nivel->nivel;

        foreach ($dataArray as $key => $value) {
            $procradiologia[] = $model_prestacion->getPresProceById($value);
        }

        $categoria_radiologias = DB::table('categoria_radiologias')->select('categoria_radiologias.*')->where('id_servicio', $procradiologia[0]->id_servicio_categoria_radiologia)->first();
        $plantillas = $model_plantilla->PlantillaServicio($categoria_radiologias->id, $id_nivel);
        
        return view('frontend.radiologias.modal_listar_informes_validacion', compact('procradiologia','plantillas'));
    }

    public function obtener_pdfs_detalle_radiologia($id_radiologia, $id_servicio_radiologia, $id_pp){

        if (!Auth::check()) {
            return redirect('/auth/login');
        }

        $model_prestacion = new \App\Models\Prestaciones;
        $model_imagenes = new \App\Models\Radiologia;
        $res = $model_prestacion->GetProcedimientosByIdPrestacionAndIdLabRadAndTipoAndIdServicioRad($id_radiologia, $id_servicio_radiologia, $id_pp);
        foreach ($res as $key) {
            $key->informes_adicionales = $model_imagenes->GetProcedimientosByIdPrestacionAndIdLabRadAndTipoAndIdServicioRadInformeAdicional($id_radiologia, $id_servicio_radiologia, $id_pp, 0);
        }
        
        return $res;
        
    }

    public function listarOrdenesImagenesPendientes($nro_doc,$id_servicio_estudio) 
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }

        $model_imagenes = new \App\Models\Radiologia;
        $res = $model_imagenes->obtenerOrdenesDeImagenes1($nro_doc, auth()->user()->id_establecimiento, $id_servicio_estudio );
        
        // foreach ($res as $key) {
        //     $key->servicio_examen = $model_imagenes->AllServicioExamenRadiologiaByEstablecimientoAndFechaDesdeHasta($key->id);
        // }

        return $res; 
    }

    public function modal_historial_cita_imagenes($dni_beneficiario)
    {
        $model_radiologia = new \App\Models\Radiologia;
        $horario = $model_radiologia->GetCitasByDni($dni_beneficiario);

        $id_user = Auth::user()->id;
        $model_usuario = new \App\Models\User;
        $usuario = $model_usuario->GetById($id_user);
        $id_consultorio = $usuario->id_consultorio;
        $flagservicios = $usuario->flagservicios;
        return view('frontend.radiologias.historial.modal_historial_registro_cita', compact('horario', 'id_consultorio', 'flagservicios'));
    }

    public function llamada_imagenes($id_cita, $id_ticket)
    {
        if (!Auth::check()) :
        return redirect('/auth/login');
        endif;

        //echo json_encode("Prueba Llamado");
        $model_tv = new \App\Models\Tv;
        $controller_tv = new \App\Http\Controllers\TvController;
        // $index = intval($index);
        //dd($id_ticket);
        $id_cita = $model_tv->llamar_citado_tv($id_cita);
        $cantidad_llamada_t = $controller_tv->llamar_ticket_imagenes($id_ticket);
        $cade = "SELECT * FROM citas_llamados WHERE id_cita = ".(int)$id_cita." limit 1;";
        $cita = DB::select($cade);
        $cita = $cita[0];    
        $cantidad_llamada = $cita->cantidad_llamada;
        $data['cantidadLlamadas'] = $cantidad_llamada;
        $data['idCita'] = $cita->id_cita;

        echo json_encode($data);
    }

    public function cancela_llamada_imagenes($id_cita,$id_ticket)
    {
        if (!Auth::check()) :
        return redirect('/auth/login');
        endif;

        $controller_tv = new \App\Http\Controllers\TvController;

        $id_estado_tv = $controller_tv->posponer_llamado_ticket($id_ticket);
        $cad = "UPDATE citas_llamados SET id_estado_tv = 0, estado_llamada = '0' WHERE id_cita = ".(int)$id_cita.";";
        $rowActualizado =DB::update($cad);
        $data['update'] = $rowActualizado;

        echo json_encode($data);
    }

    // public function modal_comentario($id,$id_pp)
    // {
    //     $cita = \App\Models\Cita::find($id);
    //     $comentario = $cita->comentario;

    //     $cita= \App\Models\Prestacion_procedimiento::find($id_pp);
    //     $comentario = $cita->observacion_cita_imagenes_por_pp;

    //     return view('frontend.radiologias.modal_comentario', compact('cita'));
    // }

    public function modal_comentario($id, $id_pp)
    {
        $Presproc = \App\Models\Prestacion_procedimiento::find($id_pp);
        $cita = \App\Models\Cita::find($id);

        if (!$Presproc || !$cita) {
            abort(404, 'Datos no encontrados');
        }

        // Validar que el procedimiento pertenece a la cita múltiple
        if ($Presproc->id_citas_multiples_rad != $id) {
            abort(404, 'El procedimiento no está vinculado a esta cita múltiple');
        }

        $procedimientos = \App\Models\Prestacion_procedimiento::where('id_citas_multiples_rad', $id)->get();
        $cantidad = $procedimientos->count();
        $ids_pp = $procedimientos->pluck('id')->toArray();

        $comentario = null;
        // dd(["Id_presproc"=>$Presproc->id,
        //     "Id_pp"=>$cita->id_pp,
        //     "Id_cita"=>$cita->id,
        //     "Id_citas_multiples_rad"=>$Presproc->id_citas_multiples_rad,
        //     "cantidad"=>$cantidad,
        //     "id_pps con cita multiple relacionada"=>$ids_pp
        //     ]);
        // CASO 1: cantidad = 1 y Presproc->id == $id_pp y cita->id_pp == $id_pp
        if (
            $cantidad === 1 &&
            $Presproc->id == $id_pp &&
            $cita->id_pp == $id_pp
        ) {
            $comentario = $cita->comentario;

            // Limpiar observaciones del PP
            // if (!empty($Presproc->observacion_cita_imagenes_por_pp)) {
            //     $Presproc->observacion_cita_imagenes_por_pp = null;
            //     $Presproc->observ_citmul_us = null;
            //     $Presproc->observ_citmul_at = null;
            //     $Presproc->flag_observ_citmul = null;
            //     $Presproc->save();
            // }

        // CASO 2: cantidad > 1 y Presproc->id == $id_pp y cita->id_pp == Presproc->id
        } elseif (
            $cantidad > 1 &&
            $Presproc->id == $id_pp &&
            $cita->id_pp == $Presproc->id
        ) {
            $comentario = $Presproc->observacion_cita_imagenes_por_pp;

            // Actualizar cita por claridad
            $cita->id_pp = $Presproc->id;
            $cita->save();

            // if (!empty($Presproc->observacion_cita_imagenes_por_pp)) {
            //     $Presproc->observacion_cita_imagenes_por_pp = null;
            //     $Presproc->observ_citmul_us = null;
            //     $Presproc->observ_citmul_at = null;
            //     $Presproc->flag_observ_citmul = null;
            //     $Presproc->save();
            // }

        // CASO 3: cantidad > 1 y cita->id_pp está entre los vinculados
        } elseif (
            $cantidad > 1 &&
            in_array($cita->id_pp, $ids_pp)
        ) {
            $comentario = $Presproc->observacion_cita_imagenes_por_pp;

            // if (!empty($Presproc->observacion_cita_imagenes_por_pp)) {
            //     $Presproc->observacion_cita_imagenes_por_pp = null;
            //     $Presproc->observ_citmul_us = null;
            //     $Presproc->observ_citmul_at = null;
            //     $Presproc->flag_observ_citmul = null;
            //     $Presproc->save();
            // }
        }
        return view('frontend.radiologias.modal_comentario', compact('cita', 'comentario', 'cantidad','id_pp'));
    }

    public function update_comentario_radiologia(Request $request)
    {
        Log::info('Request', [
            'id_cita' => $request->id,
            'comentario' => $request->comentario,
            'cantidad' => $request->cantidad,
            'id_pp' => $request->id_pp,
        ]);

        $id_cita = $request->id;
        $comentario = $request->comentario;
        $cantidad = $request->cantidad;
        $user_id = Auth::user()->id;
        $id_pp = $request->id_pp;

        $cita = \App\Models\Cita::find($id_cita);
        if (!$cita) return response()->json(['status' => 0, 'msg' => 'Cita no encontrada']);
        $pp_cita = \App\Models\Prestacion_procedimiento::where('id', $cita->id_pp)->first();

        $pp_cita = \App\Models\Prestacion_procedimiento::find($cita->id_pp);
        $pp_relacionado = \App\Models\Prestacion_procedimiento::where('id_citas_multiples_rad', $id_cita)->first();

        if ($cantidad == 1 && $pp_relacionado && $pp_relacionado->id == $cita->id_pp) {
            Log::info('Entrando al CASO 1');

            $cita->comentario = $comentario;
            $cita->updated_at = Carbon::now();
            $cita->updated_us = $user_id;
            $cita->id_estado_cita = 7;
            $cita->save();

            DB::table('prestacion_procedimientos')->where('id', $cita->id_pp)->update([
                'flag_cita_imagenes' => null,
                'id_citas_multiples_rad' => null,
            ]);

        } elseif ($cantidad == 1 && $pp_relacionado && $pp_relacionado->id != $cita->id_pp) {
            Log::info('Entrando al CASO 2');

            $pp_relacionado->observacion_cita_imagenes_por_pp = $comentario;
            $pp_relacionado->observ_citmul_at = Carbon::now();
            $pp_relacionado->observ_citmul_us = $user_id;
            $pp_relacionado->flag_observ_citmul = 7;
            $pp_relacionado->save();

            DB::table('prestacion_procedimientos')->where('id', $pp_relacionado->id)->update([
                'flag_cita_imagenes' => null,
                'id_citas_multiples_rad' => null,
                'confirmar_toma' => null,
            ]);

            $cita->id_pp = $pp_relacionado->id;
            $cita->save();

        } else {
            Log::info('Entrando al CASO 3');

            if ($pp_cita) {
                if ($pp_cita->id == $id_pp) {
                    $pp_cita->observacion_cita_imagenes_por_pp = $comentario;
                    $pp_cita->observ_citmul_at = Carbon::now();
                    $pp_cita->observ_citmul_us = $user_id;
                    $pp_cita->flag_observ_citmul = 7;
                    $pp_cita->save();

                    DB::table('prestacion_procedimientos')->where('id', $cita->id_pp)->update([
                        'flag_cita_imagenes' => null,
                        'id_citas_multiples_rad' => null,
                        'confirmar_toma' => null,
                    ]);

                    $nuevo_pp = \App\Models\Prestacion_procedimiento::where('id_citas_multiples_rad', $id_cita)
                        ->where('id', '<>', $cita->id_pp)
                        ->first();

                    if ($nuevo_pp) {
                        $cita->id_pp = $nuevo_pp->id;
                        $cita->save();
                        Log::info('Nuevo id_pp asignado a la cita', ['nuevo_id_pp' => $nuevo_pp->id]);
                    } else {
                        Log::warning('No se encontró otro id_pp para asignar');
                    }

                } else {

                    $pp_diferente = \App\Models\Prestacion_procedimiento::find($id_pp);
                    if ($pp_diferente) {
                        $pp_diferente->observacion_cita_imagenes_por_pp = $comentario;
                        $pp_diferente->observ_citmul_at = Carbon::now();
                        $pp_diferente->observ_citmul_us = $user_id;
                        $pp_diferente->flag_observ_citmul = 7;
                        $pp_diferente->save();

                        DB::table('prestacion_procedimientos')->where('id', $pp_diferente->id)->update([
                            'flag_cita_imagenes' => null,
                            'id_citas_multiples_rad' => null,
                            'confirmar_toma' => null,
                        ]);

                        Log::info('Observación realizada sobre un id_pp diferente al asignado en la cita', ['id_pp' => $pp_diferente->id]);
                    } else {
                        Log::warning('No se encontró el id_pp alternativo enviado por request', ['id_pp' => $id_pp]);
                    }
                }
            } else {
                Log::warning('No se encontró el objeto pp_cita para la cita');
            }
        }
        session()->flash('activar_autoenvio', true);
        return response()->json(['status' => 1]);
    }
    // public function update_comentario(Request $request)
    // {
    //     $cita = \App\Models\Cita::find($request->id);
    //     $cita->comentario = $request->comentario;
    //     $cita->updated_at = Carbon::now();
    //     $cita->updated_us = Auth::user()->id;
    //     $cita->id_estado_cita = 7;
    //     $cita->save();

    //     //Actualizamos en la tabla de citas llamados para la tv
    //     $sql = "update citas_llamados 
    //     set comentario = '".$request->comentario."',
    //     updated_at = '".Carbon::now()."',
    //     updated_us = ".auth()->user()->id."
    //     id_estado_cita = 7 
    //     where id_cita = " . (int)$request->id;
    //     DB::select($sql);
        
    //     return json_encode(['status' => 1]);
    // }

    public function validacion_beneficiario_dias_por_examen_rad($dni_beneficiario, $id_examen_rad)
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }

        $model_radiologia = new \App\Models\Radiologia;
        $examenes = $model_radiologia->validacionBeneficiarioDiasPorExamenRad($dni_beneficiario, $id_examen_rad);
       // dd($examenes);
        return $examenes;
    }

    public function radiologia_informe_adicional($id, $id_servicio_radiologia, $id_pp, $id_cita = 0) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $id_user = Auth::user()->id;
        $id_farmacia = Auth::user()->id_farmacia;
        
        $modelFarmacia = new Farmacia();
        $model_cie = new \App\Models\Diagnostico;
        $model_consultorio = new \App\Models\Consultorio;
        $model_diagnostico = new \App\Models\Diagnostico;
        $model_kardex = new \App\Models\Kardexe();    
        $model_medico = new \App\Models\Medico();
        $model_usuario = new \App\Models\User;
        
        $fechaServidor = $model_kardex->getFechaServidor();
        //-----------------------------------------------        
        $nivel = $modelFarmacia->ObtenerNivelFarmacia($id_farmacia);
        $farma = $modelFarmacia->GetFarmaByIdfarma($id_farmacia);
        $tipo_atencion = $modelFarmacia->GetFarmaciaTipoAtencionByIdFarmacia($id_farmacia);
        $id_nivel=$nivel->nivel;

        $tipo = "dni";      
        //------------------------------------------------------------------
        $fecha_hoy = date('d-m-Y');
        //------------------------------------------------------------------
        $establecimiento = $modelFarmacia->GetIpressByIduser($id_user);
        $id_ipress = $establecimiento->id;

        $user = $model_usuario->GetById($id_user);
        $flagresponsablefarmacia = $user->flagresponsablefarmacia;
        $flagfiscalizador = $user->flagfiscalizador;

        $id_ipress = $establecimiento->id;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $consultorio = $model_consultorio->ShowConsultorios($id_farmacia);
        $errorWS = 1;
        /*********************************************************************************************************/
        $model_diagnosticos = new \App\Models\Receta_diagnostico;
        
        $fecha_fin = $model_kardex->getFechaServidor();
        $id_farmacia = Auth::user()->id_farmacia;
        $farmacia = DB::table('farmacias')->where('id', '=', $id_farmacia)->first();
        $id_establecimiento = $farmacia->id_establecimiento;
        
        /************************************************************************************************************/
        $model_prestacion = new \App\Models\Prestaciones;
        $model_radiologia = new \App\Models\Radiologia;        
	    $model_plantilla = new \App\Models\Plantilla_radiologia;
        $model_referencia = new \App\Models\Referencia;   
        $radiologia = $model_radiologia->GetRadiologiaById($id);
        $id_prestacion=$radiologia->id_prestacion;

        //**** */
        $prestacion_tipo_atencion = DB::table('prestaciones')->select('prestaciones.id_tipo_atencion')->where('prestaciones.id','=',$id_prestacion)->first();
        $id_tipo_atencion = $prestacion_tipo_atencion->id_tipo_atencion;
        switch ($id_tipo_atencion) {
            case '1': //CONSULTA EXTERNA
                $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
                $referencia = $prestacion->referido;
                // if ($referencia == "SI") {
                //     $referencia_datos = $model_referencia->obtenerDatosDeRefereanciaParaAtencionLabPorId($prestacion->id_referencia);
                //     $diagnostico_referencia = $model_prestacion->GetDiagnosticoByIdPrestacion($referencia_datos->id_prestacion_referencia);
                // }
                if($id_prestacion>1910698){
                    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionydiag($id_prestacion, 1);
                } else {
                    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 1);
                }
                break;
                
            case '2': //EMERGENCIA
                $prestacion = $model_prestacion->GetPrestacionEmergenciaRadiologiaById([$id_prestacion]);
                $referencia = $prestacion->id_motivo_ingreso;
                //if ($prestacion->id_motivo_ingreso == 4) {
                    //En emergencia no usa el módulo de referencia
                    //$referencia_datos = $model_referencia->obtenerDatosDeRefereanciaParaAtencionLabPorId($prestacion->id_referencia);
                    //$diagnostico_referencia = $model_prestacion->GetDiagnosticoByIdPrestacion($referencia_datos->id_prestacion_referencia);
                //}
                if($id_prestacion>1910698){
                    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionydiag($id_prestacion, 1);
                } else {
                    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 1);
                }
                break;
            
            case '3': //HOSPITALIZACIÓN
                if(!empty($radiologia->id_evaluacion)){
                    $evolucion = $model_radiologia->GetRadiologiaById_ev($id);
                }
                $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
                $referencia = 'FALTA';//$prestacion->id_motivo_ingreso;
                //if ($prestacion->id_motivo_ingreso == 4) {
                    //En emergencia no usa el módulo de referencia
                    //$referencia_datos = $model_referencia->obtenerDatosDeRefereanciaParaAtencionLabPorId($prestacion->id_referencia);
                    //$diagnostico_referencia = $model_prestacion->GetDiagnosticoByIdPrestacion($referencia_datos->id_prestacion_referencia);
                //}
                $model_emergencia = new \App\Models\Emergencia;
                $hospitalizacion = \App\Models\Hospitalizacione::where('id', $prestacion->id_ext)->first();
                $camas = $model_emergencia->GetObtenerCama($hospitalizacion->id_cama_estado);
                if($id_prestacion>1910698){
                    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionydiag($id_prestacion, 1);
                } else {
                    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 1);
                }
                break;
        }


        $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
        $procradiologia = $model_prestacion->GetProcedimientosByIdPrestacionAndIdLabRadAndTipoAndIdServicioRad($id, $id_servicio_radiologia,$id_pp);
        $categoria_radiologias = DB::table('categoria_radiologias')->select('categoria_radiologias.*')->where('id_servicio', $id_servicio_radiologia)->first();
	    $plantillas = $model_plantilla->PlantillaServicio($categoria_radiologias->id,$id_nivel);        
	   /*****************************/
        return view('frontend.radiologias.form_radiologia_informe_adicional', compact('prestacion','diagnostico','radiologia','flagfiscalizador','id_pp','id_servicio_radiologia','evolucion','procradiologia','plantillas','id_tipo_atencion','referencia','procedimiento','referencia_datos','diagnostico_referencia','id_cita','camas'));
    }

    /*
    Registrar el INFORME RADIOLOGICO
    */
    public function registrar_radiologia_informe_adicional(Request $request) {
        
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $total_examenes = count($request->id_pres_proc_total);
        $nro_proc = count($request->id_pres_proc);
        $res = ""; $i=0; $id_informe_adicional = "";

        DB::beginTransaction();
        if ( auth()->user()->tipo_registro == 1 || auth()->user()->tipo_registro == 7 ) { //Perfil Prescriptor Médico || Profesional de Imagenes
            //dd($request->id_pres_proc);
            try {
                if ( isset($request->id_pres_proc_total) ) {
                    foreach ($request->id_pres_proc_total as $row):
                        Log::info("Valor de proc_id:", ['id' => $row]);
                        $id_informe_adicional = DB::table('informes_radiologias_adicionales')->insertGetId([
                            'id_prestacion_procedimiento' => $row,
                            'confirmar_toma' => 4,
                            'id_estado_reg' => 1,
                            'created_us' => auth()->user()->id,
                            'created_at' => Carbon::now()
                        ]);
                        $affected1 = DB::update("UPDATE prestacion_procedimientos SET cantidad_adendas_rad = COALESCE(cantidad_adendas_rad, 0) + 1  WHERE id = ?", [$row]);
                    endforeach;
                }

                if ( isset($request->id_pres_proc) ) {
                    $parrafos = collect($request->parrafo);
                    $plantillas = collect($request->id_plantilla);

                    foreach ($request->id_pres_proc as $row):
                        $parrafo=$request->parrafo1;
                        $tipo_plantilla=$request->id_plantilla1;
                        $total_informe = 1;
                        $observacion=$request->observacion_examen;
    
                        // $affectedRows = DB::table('informes_radiologias_adicionales')
                        //                     ->where('id', $id_informe_adicional)
                        //                     ->update([
                        //                         'confirmar_toma' => '0',
                        //                         'informe_parrafo' => $parrafo,
                        //                         'observacion_registro_informe' => $observacion,
                        //                         'flag_informe' => '1',
                        //                         'transcribed_us' => auth()->user()->id,
                        //                         'transcribed_at' => Carbon::now(),
                        //                         'total_informe' => $total_informe,
                        //                         //'tipo_plantilla' => $tipo_plantilla
                        //                     ]);
                        //dd($affectedRows);

                        $data_update = [
                            'confirmar_toma' => '0',
                            'informe_parrafo' => $parrafo,
                            'observacion_registro_informe' => $observacion,
                            'flag_informe' => '1',
                            'transcribed_us' => auth()->user()->id,
                            'transcribed_at' => Carbon::now(),
                            'total_informe' => $total_informe,
                            'verif_us' => auth()->user()->id,
                            'verific_at' => Carbon::now(),
                            'flag_verif' => '1'
                        ];

                        // Solo agregar 'tipo_plantilla' si está presente y no es nulo o vacío
                        if (!empty($tipo_plantilla)) {
                            $data_update['tipo_plantilla'] = $tipo_plantilla;
                        }

                        $affectedRows = DB::table('informes_radiologias_adicionales')
                            ->where('id', $id_informe_adicional)
                            ->update($data_update);

                        $i++;
                    endforeach;
                }

                DB::commit();
                $res = "Y";
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            } catch (\Throwable $th) {
                DB::rollback();
                throw $th;
            }

        }

        if ($res=="Y"):
            Session::flash('flash_message', 'Se registro con exito'); // AHORA SALDRA EL MENSAJE
        else:
            Session::flash('flash_message', 'No se ha podido registrar, verifique y vuelva a intentarlo');
        endif;
        //dd($request->all());
        if((auth()->user()->tipo_registro == 1 || auth()->user()->tipo_registro == 7) && auth()->user()->flagfiscalizador == 1){
            return redirect()->route('radiologia_informe_validacion_adicional', [
                'id_radiologia' => $request->input('id_radiologia'),
                'id_servicio_radiologia' => $request->input('id_servicio_radiologia'),
                'id_pp' => is_array($request->input('id_pres_proc')) 
                            ? $request->input('id_pres_proc')[0] 
                            : $request->input('id_pres_proc'),
                'id_informe_adicional' => $id_informe_adicional
            ]);
        }else{
            session()->flash('activar_autoenvio', true);
            return redirect('all_radiologia_prescritas_by_ipress');
        }
    }

    public function radiologia_informe_validacion_adicional($id, $id_servicio_radiologia, $id_pp, $id_informe_adicional) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $id_user = Auth::user()->id;
        $id_farmacia = Auth::user()->id_farmacia;
        
        $modelFarmacia = new Farmacia();
        $model_cie = new \App\Models\Diagnostico;
        $model_consultorio = new \App\Models\Consultorio;
        $model_diagnostico = new \App\Models\Diagnostico;
        $model_kardex = new \App\Models\Kardexe();    
        $model_medico = new \App\Models\Medico();
        $model_usuario = new \App\Models\User;
        
        $fechaServidor = $model_kardex->getFechaServidor();
        //-----------------------------------------------        
        $nivel = $modelFarmacia->ObtenerNivelFarmacia($id_farmacia);
        $farma = $modelFarmacia->GetFarmaByIdfarma($id_farmacia);
        $tipo_atencion = $modelFarmacia->GetFarmaciaTipoAtencionByIdFarmacia($id_farmacia);
        $id_nivel=$nivel->nivel;

        $tipo = "dni";      
        //------------------------------------------------------------------
        $fecha_hoy = date('d-m-Y');
        //------------------------------------------------------------------
        $establecimiento = $modelFarmacia->GetIpressByIduser($id_user);
        $id_ipress = $establecimiento->id;

        $user = $model_usuario->GetById($id_user);
        $flagresponsablefarmacia = $user->flagresponsablefarmacia;
        $flagfiscalizador = $user->flagfiscalizador;

        $id_ipress = $establecimiento->id;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $consultorio = $model_consultorio->ShowConsultorios($id_farmacia);
        $errorWS = 1;
        /*********************************************************************************************************/
        $model_diagnosticos = new \App\Models\Receta_diagnostico;
        
        $fecha_fin = $model_kardex->getFechaServidor();
        $id_farmacia = Auth::user()->id_farmacia;
        $farmacia = DB::table('farmacias')->where('id', '=', $id_farmacia)->first();
        $id_establecimiento = $farmacia->id_establecimiento;
        
        /************************************************************************************************************/
        $model_prestacion = new \App\Models\Prestaciones;
        $model_radiologia = new \App\Models\Radiologia;        
	    $model_plantilla = new \App\Models\Plantilla_radiologia;
        $model_referencia = new \App\Models\Referencia;   
        $radiologia = $model_radiologia->GetRadiologiaById($id);
        $id_prestacion=$radiologia->id_prestacion;

        //**** */
        $prestacion_tipo_atencion = DB::table('prestaciones')->select('prestaciones.id_tipo_atencion')->where('prestaciones.id','=',$id_prestacion)->first();
        $id_tipo_atencion = $prestacion_tipo_atencion->id_tipo_atencion;
        switch ($id_tipo_atencion) {
            case '1': //CONSULTA EXTERNA
                $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
                $referencia = $prestacion->referido;
                // if ($referencia == "SI") {
                //     $referencia_datos = $model_referencia->obtenerDatosDeRefereanciaParaAtencionLabPorId($prestacion->id_referencia);
                //     $diagnostico_referencia = $model_prestacion->GetDiagnosticoByIdPrestacion($referencia_datos->id_prestacion_referencia);
                // }
                if($id_prestacion>1910698){
                    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionydiag($id_prestacion, 1);
                } else {
                    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 1);
                }
                break;
                
            case '2': //EMERGENCIA
                $prestacion = $model_prestacion->GetPrestacionEmergenciaRadiologiaById([$id_prestacion]);
                $referencia = $prestacion->id_motivo_ingreso;
                //if ($prestacion->id_motivo_ingreso == 4) {
                    //En emergencia no usa el módulo de referencia
                    //$referencia_datos = $model_referencia->obtenerDatosDeRefereanciaParaAtencionLabPorId($prestacion->id_referencia);
                    //$diagnostico_referencia = $model_prestacion->GetDiagnosticoByIdPrestacion($referencia_datos->id_prestacion_referencia);
                //}
                if($id_prestacion>1910698){
                    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionydiag($id_prestacion, 1);
                } else {
                    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 1);
                }
                break;
            
            case '3': //HOSPITALIZACIÓN
                if(!empty($radiologia->id_evaluacion)){
                    $evolucion = $model_radiologia->GetRadiologiaById_ev($id);
                }
                $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
                $referencia = 'FALTA';//$prestacion->id_motivo_ingreso;
                //if ($prestacion->id_motivo_ingreso == 4) {
                    //En emergencia no usa el módulo de referencia
                    //$referencia_datos = $model_referencia->obtenerDatosDeRefereanciaParaAtencionLabPorId($prestacion->id_referencia);
                    //$diagnostico_referencia = $model_prestacion->GetDiagnosticoByIdPrestacion($referencia_datos->id_prestacion_referencia);
                //}
                $model_emergencia = new \App\Models\Emergencia;
                $hospitalizacion = \App\Models\Hospitalizacione::where('id', $prestacion->id_ext)->first();
                $camas = $model_emergencia->GetObtenerCama($hospitalizacion->id_cama_estado);
                if($id_prestacion>1910698){
                    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacionydiag($id_prestacion, 1);
                } else {
                    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 1);
                }
                break;
        }

        
        $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
        $procradiologia = $model_radiologia->GetProcedimientosByIdPrestacionAndIdLabRadAndTipoAndIdServicioRadInformeAdicional($id, $id_servicio_radiologia, $id_pp, $id_informe_adicional);
        $categoria_radiologias = DB::table('categoria_radiologias')->select('categoria_radiologias.*')->where('id_servicio', $id_servicio_radiologia)->first();
	    $plantillas = $model_plantilla->PlantillaServicio($categoria_radiologias->id,$id_nivel);
        
        /*****************************/
        $total_informe = 0;
        foreach ($procradiologia as $row) {
            // 0 = tiene examenes, 
            // 1 = eliminado, 
            // 2 = ws cambia el estado obtiene,
            // 3 = modificado, 
            // 4 = no registrado
            if (($row->confirmar_toma == '0' || $row->confirmar_toma == '3') && $row->total_informe > 0) {
                $total_informe = $row->total_informe;
            }
        }
        $bool_contenedor_profesional_imagenes = "true";
        foreach ($procradiologia as $row) {
            // 0 = tiene examenes, 
            // 1 = eliminado, 
            // 2 = ws cambia el estado obtiene,
            // 3 = modificado, 
            // 4 = no registrado
            if ($row->confirmar_toma !== '1' && $row->confirmar_toma !== '4' && $row->confirmar_toma !== null && $row->confirmar_toma !== '') {
                $bool_contenedor_profesional_imagenes = "true";
                break; // Si encontramos un registro que no cumple, salimos del bucle
            }
            $bool_contenedor_profesional_imagenes = "false";
        }
        
        $observacion_examen = $procradiologia[0]->observacion_registro_informe;
        $pendiente_firma = $model_prestacion->GetDocumentosPendintesFirmarPrestacionId($id_prestacion);
        $flag_pendiente_firma = (count($pendiente_firma) > 0) ? 1 : 0;

        $cant_pp_registrados = 0;

        foreach ($procradiologia as $row) {
            if ($row->confirmar_toma == '0' || $row->confirmar_toma == '3') {
                $cant_pp_registrados++;
            }
        }

        return view('frontend.radiologias.form_radiologia_informe_validacion_adicional', compact('prestacion','diagnostico','radiologia','evolucion','procradiologia','plantillas','id_tipo_atencion','referencia','procedimiento','total_informe','flag_pendiente_firma','id_servicio_radiologia','observacion_examen','cant_pp_registrados','referencia_datos','diagnostico_referencia','id_pp','bool_contenedor_profesional_imagenes','id_informe_adicional','camas'));    
    }

    public function modificar_validar_radiologia_informe_adicional(Request $request) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        //dd($request);
        $total_examenes = count($request->id_pres_proc_total);
        $nro_proc = count($request->id_pres_proc);
        
        $res = ""; $i=0;
        $j = 0;
        //dd($request);
        DB::beginTransaction();

        if ( auth()->user()->tipo_registro == 1 || auth()->user()->tipo_registro == 7 ) { //Perfil Prescriptor Médico || Profesional de Imagenes
            
            try {
                    if ( isset($request->id_pres_proc) ) {

                        foreach ($request->id_pres_proc as $row):
                            
                            $parrafo=$request->parrafo1;
                            $tipo_plantilla=$request->id_plantilla1;
                            $total_informe = 1;
                            $observacion_examen=$request->observacion_examen;
                            //dd($observacion_examen);
                            $tipo_plantilla = (is_numeric($tipo_plantilla)) ? intval($tipo_plantilla) : null;

                            $affected = DB::update("UPDATE informes_radiologias_adicionales SET informe_parrafo = ?, flag_informe = ?, total_informe = ?, observacion_registro_informe = ?, tipo_plantilla = ?, updated_at_registro_informe = ?, updated_us_registro_informe = ?, verif_us = ?, verific_at = ?, flag_verif = ? WHERE id = ? ",[$parrafo, '1', $total_informe,$observacion_examen, $tipo_plantilla, Carbon::now(), Auth::user()->id, Auth::user()->id, Carbon::now(),'1', $request->id_informe_adicional]);
                            
                        endforeach;

                    }

                    if ( isset($request->id_pres_proc_total) ) {
                        //Actulizamos estado confirmar_toma
                        foreach ($request->id_pres_proc_total as $row) {
                            
                            $array_estado       = $request->estado;
                            $estado             = $array_estado[$j];

                            $affected = DB::update("UPDATE informes_radiologias_adicionales SET confirmar_toma = ? WHERE id = ?",[$estado, $request->id_informe_adicional]);
                            $j++;
                        }
                    }
                DB::commit();
                $res = "Y";
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            } catch (\Throwable $th) {
                DB::rollback();
                throw $th;
            }

        }
        // return $res;
        if ($res=="Y"):
            Session::flash('flash_message', 'Se modificó con éxito'); // AHORA SALDRA EL MENSAJE
        else:
            Session::flash('flash_message', 'No se ha podido modificar, verifique y vuelva a intentarlo');
        endif;

        return redirect('/radiologia_informe_validacion_adicional/'.$request->id_radiologia.'/'.$request->id_servicio_radiologia.'/'.$request->id_pres_proc_total[0].'/'.$request->id_informe_adicional);
        
    }

    public function radiologia_firmar_informe_adicional($id, $id_servicio_radiologia, $id_pp, $id_informe_adicional) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $id_user = Auth::user()->id;
        $id_farmacia = Auth::user()->id_farmacia;
        
        $modelFarmacia = new Farmacia();
        $model_cie = new \App\Models\Diagnostico;
        $model_consultorio = new \App\Models\Consultorio;
        $model_diagnostico = new \App\Models\Diagnostico;
        $model_kardex = new \App\Models\Kardexe();    
        $model_medico = new \App\Models\Medico();
        $model_usuario = new \App\Models\User;
        
        $fechaServidor = $model_kardex->getFechaServidor();
        //-----------------------------------------------        
        $nivel = $modelFarmacia->ObtenerNivelFarmacia($id_farmacia);
        $farma = $modelFarmacia->GetFarmaByIdfarma($id_farmacia);
        $tipo_atencion = $modelFarmacia->GetFarmaciaTipoAtencionByIdFarmacia($id_farmacia);
        $id_nivel=$nivel->nivel;

        $tipo = "dni";      
        //------------------------------------------------------------------
        $fecha_hoy = date('d-m-Y');
        //------------------------------------------------------------------
        $establecimiento = $modelFarmacia->GetIpressByIduser($id_user);
        $id_ipress = $establecimiento->id;

        $user = $model_usuario->GetById($id_user);
        $flagresponsablefarmacia = $user->flagresponsablefarmacia;
        $flagfiscalizador = $user->flagfiscalizador;

        $id_ipress = $establecimiento->id;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $consultorio = $model_consultorio->ShowConsultorios($id_farmacia);
        $errorWS = 1;
        /*********************************************************************************************************/
        $model_diagnosticos = new \App\Models\Receta_diagnostico;
        
        $fecha_fin = $model_kardex->getFechaServidor();
        $id_farmacia = Auth::user()->id_farmacia;
        $farmacia = DB::table('farmacias')->where('id', '=', $id_farmacia)->first();
        $id_establecimiento = $farmacia->id_establecimiento;
        
        /************************************************************************************************************/
        $model_prestacion = new \App\Models\Prestaciones;
        $model_radiologia = new \App\Models\Radiologia;        

        $radiologia = $model_radiologia->GetRadiologiaById($id);
        if(!empty($radiologia->id_evaluacion)){
            $evolucion = $model_radiologia->GetRadiologiaById_ev($id);
        }
        $id_prestacion=$radiologia->id_prestacion;
        // $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
        $prestacion_tipo_atencion = DB::table('prestaciones')->select('prestaciones.id_tipo_atencion')->where('prestaciones.id','=',$id_prestacion)->first();
        $id_tipo_atencion = $prestacion_tipo_atencion->id_tipo_atencion;
        switch ($id_tipo_atencion) {
            case '1': //CONSULTA EXTERNA
                $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
                break;
            case '2': //EMERGENCIA
                $prestacion = $model_prestacion->GetPrestacionEmergenciaRadiologiaById([$id_prestacion]);
                break;
            case '3': //HOSPITALIZACIÓN
                if(!empty($radiologia->id_evaluacion)){
                    $evolucion = $model_radiologia->GetRadiologiaById_ev($id);
                }
                $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);

                $model_emergencia = new \App\Models\Emergencia;
                $hospitalizacion = \App\Models\Hospitalizacione::where('id', $prestacion->id_ext)->first();
                $camas = $model_emergencia->GetObtenerCama($hospitalizacion->id_cama_estado);
                break;
        }
        $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
        $procradiologia = $model_radiologia->GetProcedimientosByIdPrestacionAndIdLabRadAndTipoAndIdServicioRadInformeAdicional($id, $id_servicio_radiologia, $id_pp, $id_informe_adicional);
        /*****************************/
        $model_plantilla = new \App\Models\Plantilla_radiologia;
        $plantillas = $model_plantilla->PlantillaServicio($id_servicio_radiologia,$id_nivel);
        $total_informe = 0;
        foreach ($procradiologia as $row) {
            // 0 = tiene examenes, 
            // 1 = eliminado, 
            // 2 = ws cambia el estado obtiene,
            // 3 = modificado, 
            // 4 = no registrado
            if (($row->confirmar_toma == '0' || $row->confirmar_toma == '3') && $row->total_informe > 0) {
                $total_informe = $row->total_informe;     
            }
        }
        
        $pendiente_firma = $model_prestacion->GetDocumentosPendintesFirmarPrestacionId($id_prestacion);
        $flag_pendiente_firma = (count($pendiente_firma) > 0) ? 1 : 0;
        return view('frontend.radiologias.form_firmar_imagenologia_adicional', compact('prestacion','diagnostico','radiologia','evolucion','id_tipo_atencion','procradiologia','id_servicio_radiologia','plantillas','total_informe','flag_pendiente_firma','id_pp','id_informe_adicional'));    
    }

    public function invalidarOrdenImagenes($opcion,$tipo,$id_radiologia,$observacion,$id_cita=0)
    {

        if (!Auth::check()) :
        return redirect('/auth/login');
        endif;
        //OPCION : 1 ELIMINADO POR EL MÉDICO EN SU ATENCIÓN
        //=============================================================================
        //OPCION : 2 ELIMINADO POR TECNOLOGO O PROFESIONAL DE IMAGENES DESDE SU MÓDULO
            //TIPO : 1 SIN CITA O SIN FIRMA
            //TIPO : 2 CON CITA

        $opcion = (int)$opcion;
        $r = 'false';
        try {
        
            switch ($opcion) {
                case 1:
                    
                    break;
                case 2:
                    if ($tipo == 1) {
                        $radiologia = Radiologia::find($id_radiologia);
                        $radiologia->estado = 0; 
                        $radiologia->obs_examen_invalidado = $observacion; 
                        $radiologia->flag_invalidado = (int)$opcion; //En el modulo de imagenes
                        $radiologia->id_user_examen_invalidado = auth()->user()->id; 
                        $radiologia->fecha_orden_invalidado = Carbon::now();
                        $radiologia->save();
                        $r = 'true';
                    }
                    if ($tipo == 2) {
                        $pres_pro = DB::table('prestacion_procedimientos')->where('tipo_procedimiento', 3)->where('id_lab_rad', $id_radiologia)->get();
                        Log::info("prestacion procedimiento : ". json_encode($pres_pro));
                        if ( count($pres_pro) > 0 ) {
                            foreach ($pres_pro as $key) {
                                $citas = DB::table('citas')->where('id_pp', $key->id)->first();
                                if ($citas !== null) {
                                    Log::info("citas : ". json_encode($citas));
                                    $cita = Cita::where('id', $citas->id)->first();
                                    $cita->id_estado_reg = 0;
                                    $cita->id_estado_cita = 5;
                                    $cita->updated_at = Carbon::now();
                                    $cita->updated_us = Auth::user()->id;
                                    $key->id = $cita->id_pp;
                                    $cita->save();
                                    $db_update = DB::table('prestacion_procedimientos')->where('id', $key->id)->update(['flag_cita_imagenes' => null]);
                                    
                                    //Anulamos tambien la tabla citas_llamados solo para colas
                                    $sql = "update citas_llamados set id_estado_reg = 0, id_estado_cita = 5, updated_at = '". Carbon::now() ."', updated_us = ".(int)auth()->user()->id." where id_cita = " . (int)$citas->id;
                                    DB::select($sql);   
                                }
                            }

                            $radiologia = Radiologia::find($id_radiologia);
                            $radiologia->estado = 0; 
                            $radiologia->obs_examen_invalidado = $observacion; 
                            $radiologia->flag_invalidado = (int)$opcion; //En el modulo de imagenes
                            $radiologia->id_user_examen_invalidado = auth()->user()->id; 
                            $radiologia->fecha_orden_invalidado = Carbon::now(); 
                            $radiologia->save();
                            $r = 'true';
                        }
                    }
                    break;
            }
            return $r;
        } catch (\Throwable $th) {
            Log::info("Error : " . $th->getMessage());
            $r = 'false';
            return $r;
        }
        
    }

    public function otros_reportes_by_imagenes() {
        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;

        $ruta = Route::getCurrentRoute()->getPath();
        $split_ruta = $ruta = explode("/", $ruta);
        $ruta_principal = $split_ruta[0];
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta_principal);

        $permiso = null;
        if (!empty($menu)) {
            $permiso = $menu[0];
        } else {
            return redirect('/dashboard')->withFlashWarning("No tiene permisos para ingresar a esa opción.");
        }

        if ($permiso->lectura == "") {
            return redirect('/dashboard')->withFlashWarning("No tiene permisos para ingresar a esa opción.");
        }
        
        $date = Carbon::now();
        $fecha_actual = $date->format('d-m-Y');
        
        //------------------------------------------------------
        // switch ($tipo) {
        //     case '1':
        //         dd('Entro en tipo 1');
        //         break;
            
        //     default:
        //         dd('Entro por default');
        //         break;
        // }
        //------------------------------------------------------ 
        return view('frontend.radiologias.reportes.otros_reportes_by_imagenes', compact('fecha_actual'));
    }


    public function updateCodeSiteds(Request $request, $id) {
        $radiologia = Radiologia::find($id);
        if (!$radiologia ) {
          return response()->json(['message' => 'No existe registro de la radiologia'], 404);
        }
        $radiologia->cod_siteds_susalud = $request->input('cod_siteds_susalud');
        $radiologia->updated_at_cod_siteds = Carbon::now();
        $radiologia->updated_us_cod_siteds = Auth::user()->id;
        $radiologia->save();
    
        return response()->json(['emergencia_id' => $id, 'message' => 'Código SITEDS actualizado correctamente'], 200);
      }

      public function ver_atencion_radiologia($id, $id_servicio_radiologia, $id_pp) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $id_user = Auth::user()->id;
        $id_farmacia = Auth::user()->id_farmacia;
        
        $modelFarmacia = new Farmacia();
        $model_cie = new \App\Models\Diagnostico;
        $model_consultorio = new \App\Models\Consultorio;
        $model_diagnostico = new \App\Models\Diagnostico;
        $model_kardex = new \App\Models\Kardexe();    
        $model_medico = new \App\Models\Medico();
        $model_usuario = new \App\Models\User;
        
        $fechaServidor = $model_kardex->getFechaServidor();
        //-----------------------------------------------        
        $nivel = $modelFarmacia->ObtenerNivelFarmacia($id_farmacia);
        $farma = $modelFarmacia->GetFarmaByIdfarma($id_farmacia);
        $tipo_atencion = $modelFarmacia->GetFarmaciaTipoAtencionByIdFarmacia($id_farmacia);
        $id_nivel=$nivel->nivel;

        $tipo = "dni";      
        //------------------------------------------------------------------
        $fecha_hoy = date('d-m-Y');
        //------------------------------------------------------------------
        $establecimiento = $modelFarmacia->GetIpressByIduser($id_user);
        $id_ipress = $establecimiento->id;

        $user = $model_usuario->GetById($id_user);
        $flagresponsablefarmacia = $user->flagresponsablefarmacia;
        $flagfiscalizador = $user->flagfiscalizador;

        $id_ipress = $establecimiento->id;
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $consultorio = $model_consultorio->ShowConsultorios($id_farmacia);
        $errorWS = 1;
        /*********************************************************************************************************/
        $model_diagnosticos = new \App\Models\Receta_diagnostico;
        
        $fecha_fin = $model_kardex->getFechaServidor();
        $id_farmacia = Auth::user()->id_farmacia;
        $farmacia = DB::table('farmacias')->where('id', '=', $id_farmacia)->first();
        $id_establecimiento = $farmacia->id_establecimiento;
        
        /************************************************************************************************************/
        $model_prestacion = new \App\Models\Prestaciones;
        $model_radiologia = new \App\Models\Radiologia;        

        $radiologia = $model_radiologia->GetRadiologiaById($id);
        if(!empty($radiologia->id_evaluacion)){
            $evolucion = $model_radiologia->GetRadiologiaById_ev($id);
        }
        $id_prestacion=$radiologia->id_prestacion;
        $prestacion_tipo_atencion = DB::table('prestaciones')->select('prestaciones.id_tipo_atencion')->where('prestaciones.id','=',$id_prestacion)->first();
        $id_tipo_atencion = $prestacion_tipo_atencion->id_tipo_atencion;
        switch ($id_tipo_atencion) {
            case '1': //CONSULTA EXTERNA
                $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
            case '2': //EMERGENCIA
                $prestacion = $model_prestacion->GetPrestacionEmergenciaRadiologiaById([$id_prestacion]);
            case '3': //HOSPITALIZACIÓN
                if(!empty($radiologia->id_evaluacion)){
                    $evolucion = $model_radiologia->GetRadiologiaById_ev($id);
                }
                $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);

                $model_emergencia = new \App\Models\Emergencia;
                $hospitalizacion = \App\Models\Hospitalizacione::where('id', $prestacion->id_ext)->first();
                $camas = $model_emergencia->GetObtenerCama($hospitalizacion->id_cama_estado);
        }
        $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
        $procradiologia = $model_prestacion->GetProcedimientosByIdPrestacionAndIdLabRadAndTipoAndIdServicioRad($id, $id_servicio_radiologia, $id_pp);
        /*****************************/
        $model_plantilla = new \App\Models\Plantilla_radiologia;
        $plantillas = $model_plantilla->PlantillaServicio($id_servicio_radiologia, $id_nivel);
        $total_informe = 0;
        foreach ($procradiologia as $row) {
            // 0 = tiene examenes, 
            // 1 = eliminado, 
            // 2 = ws cambia el estado obtiene,
            // 3 = modificado, 
            // 4 = no registrado
            if (($row->confirmar_toma == '0' || $row->confirmar_toma == '3') && $row->total_informe > 0) {
                $total_informe = $row->total_informe;
            }
        }
        
        $pendiente_firma = $model_prestacion->GetDocumentosPendintesFirmarPrestacionId($id_prestacion);
        $flag_pendiente_firma = (count($pendiente_firma) > 0) ? 1 : 0;
        return view('frontend.radiologias.ver_rad', compact('prestacion','diagnostico','evolucion','radiologia','procradiologia','id_tipo_atencion','id_servicio_radiologia','plantillas','total_informe','flag_pendiente_firma','id_pp'));    
    }

        public function ver_atencion_radiologia_adicional($id, $id_servicio_radiologia, $id_pp, $id_informe_adicional) {

            if (!Auth::check()):
                return redirect('/auth/login');
            endif;
            
            $id_user = Auth::user()->id;
            $id_farmacia = Auth::user()->id_farmacia;
            
            $modelFarmacia = new Farmacia();
            $model_cie = new \App\Models\Diagnostico;
            $model_consultorio = new \App\Models\Consultorio;
            $model_diagnostico = new \App\Models\Diagnostico;
            $model_kardex = new \App\Models\Kardexe();    
            $model_medico = new \App\Models\Medico();
            $model_usuario = new \App\Models\User;
            
            $fechaServidor = $model_kardex->getFechaServidor();
            //-----------------------------------------------        
            $nivel = $modelFarmacia->ObtenerNivelFarmacia($id_farmacia);
            $farma = $modelFarmacia->GetFarmaByIdfarma($id_farmacia);
            $tipo_atencion = $modelFarmacia->GetFarmaciaTipoAtencionByIdFarmacia($id_farmacia);
            $id_nivel=$nivel->nivel;
    
            $tipo = "dni";      
            //------------------------------------------------------------------
            $fecha_hoy = date('d-m-Y');
            //------------------------------------------------------------------
            $establecimiento = $modelFarmacia->GetIpressByIduser($id_user);
            $id_ipress = $establecimiento->id;
    
            $user = $model_usuario->GetById($id_user);
            $flagresponsablefarmacia = $user->flagresponsablefarmacia;
            $flagfiscalizador = $user->flagfiscalizador;
    
            $id_ipress = $establecimiento->id;
            $date = Carbon::now();
            $fecha = $date->format('d-m-Y');
            $consultorio = $model_consultorio->ShowConsultorios($id_farmacia);
            $errorWS = 1;
            /*********************************************************************************************************/
            $model_diagnosticos = new \App\Models\Receta_diagnostico;
            
            $fecha_fin = $model_kardex->getFechaServidor();
            $id_farmacia = Auth::user()->id_farmacia;
            $farmacia = DB::table('farmacias')->where('id', '=', $id_farmacia)->first();
            $id_establecimiento = $farmacia->id_establecimiento;
            
            /************************************************************************************************************/
            $model_prestacion = new \App\Models\Prestaciones;
            $model_radiologia = new \App\Models\Radiologia;        
    
            // $radiologia = $model_radiologia->GetRadiologiaById($id);
            // $id_prestacion=$radiologia->id_prestacion;
            // $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
            //**** */
            $radiologia = $model_radiologia->GetRadiologiaById($id);
            if(!empty($radiologia->id_evaluacion)){
                $evolucion = $model_radiologia->GetRadiologiaById_ev($id);
            }
            $id_prestacion=$radiologia->id_prestacion;
            $prestacion_tipo_atencion = DB::table('prestaciones')->select('prestaciones.id_tipo_atencion')->where('prestaciones.id','=',$id_prestacion)->first();
            $id_tipo_atencion = $prestacion_tipo_atencion->id_tipo_atencion;
            switch ($id_tipo_atencion) {
                case '1': //CONSULTA EXTERNA
                    $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
                    break;
                case '2': //EMERGENCIA
                    $prestacion = $model_prestacion->GetPrestacionEmergenciaRadiologiaById([$id_prestacion]);
                    break;
                case '3': //HOSPITALIZACIÓN
                    if(!empty($radiologia->id_evaluacion)){
                        $evolucion = $model_radiologia->GetRadiologiaById_ev($id);
                    }
                    $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);

                    $model_emergencia = new \App\Models\Emergencia;
                    $hospitalizacion = \App\Models\Hospitalizacione::where('id', $prestacion->id_ext)->first();
                    $camas = $model_emergencia->GetObtenerCama($hospitalizacion->id_cama_estado);
                    break;
            }

            $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
            $procradiologia = $model_radiologia->GetProcedimientosByIdPrestacionAndIdLabRadAndTipoAndIdServicioRadInformeAdicional($id, $id_servicio_radiologia, $id_pp, $id_informe_adicional);
            /*****************************/
            $model_plantilla = new \App\Models\Plantilla_radiologia;
            $plantillas = $model_plantilla->PlantillaServicio($id_servicio_radiologia,$id_nivel);
            $total_informe = 0;
            foreach ($procradiologia as $row) {
                // 0 = tiene examenes, 
                // 1 = eliminado, 
                // 2 = ws cambia el estado obtiene,
                // 3 = modificado, 
                // 4 = no registrado
                if (($row->confirmar_toma == '0' || $row->confirmar_toma == '3') && $row->total_informe > 0) {
                    $total_informe = $row->total_informe;
                }
            }
            
            $pendiente_firma = $model_prestacion->GetDocumentosPendintesFirmarPrestacionId($id_prestacion);
            $flag_pendiente_firma = (count($pendiente_firma) > 0) ? 1 : 0;
            return view('frontend.radiologias.ver_rad_adicionales', compact('prestacion','diagnostico','radiologia','id_tipo_atencion','evolucion','procradiologia','id_servicio_radiologia','plantillas','total_informe','flag_pendiente_firma','id_pp','id_informe_adicional'));    
        }

    public function radiologia_orden_anular_json(Request $request)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        DB::beginTransaction();
        $anulacion_valida = false;
        $mensaje_error = "";

        try {
            $id_radiologia = $request->radiologia_orden_id;
            $id_user = Auth::user()->id;

            $datos = DB::table('prestacion_procedimientos')
                    ->select('prestacion_procedimientos.id')
                    ->where('prestacion_procedimientos.id_lab_rad', '=', $id_radiologia)                
                    ->where('prestacion_procedimientos.tipo_procedimiento', '=', 3)                
                    ->where('prestacion_procedimientos.id_estado_reg', '=', 1)                
                    ->whereNotNull('prestacion_procedimientos.created_at_registro_informe')                
                    ->orderBy('prestacion_procedimientos.id', 'asc')
                    ->get();

            $tiene_cita= DB::table('prestacion_procedimientos')
                        ->select('prestacion_procedimientos.id')
                        ->where('prestacion_procedimientos.id_lab_rad','=', $id_radiologia)
                        ->where('prestacion_procedimientos.tipo_procedimiento', '=', 3)
                        ->where('prestacion_procedimientos.id_estado_reg', '=', 1)
                        ->whereNotNull('prestacion_procedimientos.flag_cita_imagenes')
                        ->orderBy('prestacion_procedimientos.id', 'asc')
                        ->get();

            $radiologia = Radiologia::find($id_radiologia);
            if($radiologia->id_establecimiento == 76){
                //se obtiene la evolución de la radiología
                $rad_evol = (!empty($radiologia) && !empty($radiologia->id_evaluacion)) ? $radiologia->id_evaluacion : null;
                // solo para examenes que fueron registrados mediante el nuevo modal
                $rua_rad=DB::table('rua_imagenologia')->where('id_rad', $id_radiologia)->where('id_evol', $rad_evol)->exists();
            }
            // Si ningun examen de la orden cuenta con informe
            if (count($datos) == 0 && count($tiene_cita) == 0) {

                // Se valida si la orden esta habilitada
                if ($radiologia->estado == 1) {
                    
                    $radiologia->estado = 0; 
                    $radiologia->obs_examen_invalidado = $request->radiologia_orden_motivo_anula; 
                    $radiologia->id_user_examen_invalidado = $id_user; 
                    $radiologia->flag_invalidado = 1;
                    $radiologia->fecha_orden_invalidado = Carbon::now();

                    if (!$radiologia->save()) {
                        throw new \Exception("La Orden de Imágenes no se ha podido anular.");
                    }else{
                        if($radiologia->id_establecimiento == 76){
                            if ($rua_rad) { //una vez guardada la anulación también cambiamos de estado la tabla rua_imagenología
                                DB::table('rua_imagenologia')->where('id_rad', $id_radiologia)->where('id_evol', $rad_evol)->update(['id_estado_reg' => 0]);
                            }
                        }
                    }

                } else {
                    // Orden ya se encuenta anulada
                    throw new \Exception("La Orden de Imágenes ya fue anulada en otra oportunidad, no es posible anularla.");
                }
            } elseif (count($datos) > 0) {
                // Al menos un examen de la orden ya tiene informe
                throw new \Exception("Al menos un Exámen de la Orden de Imágenes ya cuenta con informe, ya no se podrá anular.");
            } elseif (count($tiene_cita) >= 1) {
                throw new \Exception("Al menos un Exámen de la Orden de Imágenes ya cuenta con cita, ya no se podrá anular.");
            }

            DB::commit();
            $anulacion_valida = true;

        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Anulacion Imagenes Orden => id: " . $request->radiologia_orden_id . 
                "\n\nmensaje: " . $e->getMessage() . 
                "\n\ndatos del request: " . json_encode($request->all()) . 
                "\n\nstack trace: " . $e->getTraceAsString()
            );
            $mensaje_error = $e->getMessage();
        } catch (\Throwable $th) {
            DB::rollback();
            Log::critical("Anulacion Imagenes Orden => id: " . $request->radiologia_orden_id . 
                "\n\nmensaje: " . $th->getMessage() . 
                "\n\ndatos del request: " . json_encode($request->all()) . 
                "\n\nstack trace: " . $th->getTraceAsString()
            );
            $mensaje_error = $th->getMessage();
        }
    
        if ($anulacion_valida):
            return response()->json([
                'success' => true,
                'message' => "La Orden de Imágenes ha sido anulada correctamente"
            ]);
        else:
            return response()->json([
                'success' => false,
                'message' => $mensaje_error
            ]);
        endif;
    }

    public function llamadaAtendido_imagenes($id_cita, $id_ticket)
    {
        if (!Auth::check()) :
        return redirect('/auth/login');
        endif;

        //echo json_encode("Prueba Llamado");
        $model_tv = new \App\Models\Tv;
        $controller_tv = new \App\Http\Controllers\TvController;
        // $index = intval($index);
        $id_estado_tv = $controller_tv->confirmar_atencion_ticket($id_ticket);
        $cade = "SELECT * FROM citas_llamados WHERE id_cita = ".(int)$id_cita." limit 1;";
        $cita = DB::select($cade);
        $cita = $cita[0];    
        $cantidad_llamada = $cita->cantidad_llamada;
        $data['cantidadLlamadas'] = $cantidad_llamada;
        $data['idCita'] = $cita->id_cita;

        echo json_encode($data);
    }

    public function guardarAnamnesisImagenologia(Request $request) {
        try {
            $id = DB::table('rua_imagenologia')->insertGetId([
                'id_ext'             => intval($request->id_emergencia) === 0 ? null : $request->id_emergencia,
                'id_establecimiento' => $request->id_establecimiento,
                'id_asegurado'       => $request->id_asegurado,
                'id_estado_reg'      => 0,
                'anamnesis'          => $request->anamnesis,
                'examen_fisico'      => $request->examen_fisico,
                'examen_auxiliar'    => $request->examen_auxiliar,
                'created_us'         => Auth::user()->id,
                'created_at'         => Carbon::now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Datos guardados correctamente.',
                'id' => $id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function actualizarEstadoImagenologia(Request $request){
        try {
            DB::table('rua_imagenologia')
                ->where('id', $request->id) 
                ->update([
                    'id_estado_reg' => 0,
                    'updated_us'    => Auth::user()->id,
                    'updated_at'    => Carbon::now()
                ]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function GetProcedimientosRad_PrestacionById($id){
        $prestacion = DB::table('prestaciones as pr')
            ->selectRaw("pr.*, pr.id_tipo_atencion, sc.id_consultorio, sc.descripcion consultorio, as2.sexo, as2.nro_doc_ident, as2.tipo_doc_ident, as2.paterno asegurado_paterno, as2.materno asegurado_materno, as2.nombre asegurado_nombre, as2.id_asegurado_titular, tb.descripcion parentesco, as2.fecha_nac, as2.cip, as2.grado, ah.nro_historia, pr.fecha_atencion fecha_hora_atencion, ta.nombre tipo_atencion, co.nombre servicio, es.nombre establecimiento, date_part('year', age(as2.fecha_nac::date)) edadpaciente, to_char(pr.fecha_atencion, 'dd-mm-YYYY') fecha_atencion, me.dni, me.paterno medico_paterno, me.materno medico_materno, me.nombre medico_nombre, es2.nombre especializacion, pr2.nombre profesion, me.colegiatura, me.rne,tdi.descripcion,c.referido")
            ->join('sub_consultorios as sc', 'pr.id_sub_consultorio', '=', 'sc.id')
            ->join('consultorios as co', 'sc.id_consultorio', '=', 'co.id')
            ->join('asegurados as as2', 'pr.id_asegurado', '=', 'as2.id')
            ->join('asegurado_historias as ah', function ($join) {
                $join->on('ah.id_asegurado', '=', 'as2.id')
                    ->on('ah.id_establecimiento', '=', 'pr.id_establecimiento');
            })
            ->join('tipo_atenciones as ta', 'pr.id_tipo_atencion', '=', 'ta.id')
            ->join('establecimientos as es', 'pr.id_establecimiento', '=', 'es.id')
            ->join('medicos as me', 'pr.id_medico', '=', 'me.id')
            ->leftJoin('tipo_beneficiarios as tb', 'as2.id_tipo_beneficiario', '=', 'tb.id')
            ->leftJoin('especializaciones as es2', 'me.id_especializacion', '=', 'es2.id')
            ->leftJoin('profesiones as pr2', 'me.id_profesion', '=', 'pr2.id')
            ->leftJoin('tipo_documento_identidad as tdi', function ($join) {
                $join->on(
                    DB::raw('CAST(me.tipo_documento AS INTEGER)'),'=', DB::raw('CAST(tdi.codigo AS INTEGER)')
                );
            })
            ->leftJoin('citas as c', 'pr.id_cita', '=', 'c.id')
            ->where('pr.id', $id)
            ->first();

        if ($prestacion && $prestacion->id_ext != null) {

            $prestacion->nmro_cama = "";
            $prestacion->ubicacion = "";
            $prestacion->fecha_evaluacion = "";

            if ($prestacion->id_tipo_atencion == 3) { // hospitalizacion

                $cama_info = DB::table('hospitalizaciones as h')
                    ->selectRaw('
                        c.nombre nombre_cama,
                        cuar.nombre nombre_cuarto,
                        p.nombre pabellon
                    ')
                    ->leftJoin('camas as c', 'h.id_cama_estado', '=', 'c.id')
                    ->leftJoin('cuartos as cuar', 'c.id_cuarto', '=', 'cuar.id')
                    ->leftJoin('pabellones as p', 'p.id', '=', 'h.id_pabellon')
                    ->where('h.id', $prestacion->id_ext)
                    ->first();

                if ($cama_info) {
                    $prestacion->nmro_cama = $cama_info->nombre_cama;
                    $prestacion->ubicacion = $cama_info->pabellon;
                }

            } elseif ($prestacion->id_tipo_atencion == 2) { // emergencia

                $cama_info = DB::table('emergencias as e')
                    ->selectRaw("
                        ca.nombre nombre_cama,
                        cuar.nombre nombre_cuarto,
                        TO_CHAR(e.created_at, 'DD-MM-YYYY') fecha_evaluacion
                    ")
                    ->leftJoin('camas as ca', 'e.cama_estado', '=', 'ca.id')
                    ->leftJoin('cuartos as cuar', 'ca.id_cuarto', '=', 'cuar.id')
                    ->where('e.id', $prestacion->id_ext)
                    ->first();

                if ($cama_info) {
                    $prestacion->nmro_cama = $cama_info->nombre_cama;
                    $prestacion->ubicacion = $cama_info->nombre_cuarto;
                    $prestacion->fecha_evaluacion = $cama_info->fecha_evaluacion;
                }
            }
        }

        return $prestacion;
    }
}