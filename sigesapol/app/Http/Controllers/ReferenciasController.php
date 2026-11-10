<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use DB;
use App;
use Illuminate\Support\Facades\Route;
use Auth;
use Carbon\Carbon;
use App\Models\Procedimiento;
use App\Models\Farmacia;
use App\Models\User;
use App\Models\TipoAtencione;
use App\Models\Consultorio;
use App\Models\Medico;
use App\Models\RecomendaPlanTelesalud;
use App\Models\Referencia;
use App\Models\Establecimiento;

class ReferenciasController extends Controller
{ 
  
  function __construct()
    {
        $this->middleware('auth'); 
    } 
    
  public function all_referencias($id_user = 0)
  {
    $model_medico = new \App\Models\Medico();
    $model_usuario = new User;
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
    if ($ruta == "listar_referencia_ajax");
    $ruta = "all_referencias";
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
    $permiso = $menu[0];
    if ($permiso->lectura == "")
      return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
    $id_user = Auth::user()->id;

    $date = Carbon::now();
    $fechaHasta = $date->format('d-m-Y');
    $fechaDesdex = $date->format('d-m-Y');
    $fechaDesdey = strtotime('-1 day', strtotime($fechaDesdex));
    $fechaDesde = date('j-m-Y', $fechaDesdey);

    //====================
    $model_farma = new Farmacia();
    $farmacia = $model_farma->GetFarmaByUser($id_user);

    $dni_beneficiario = '';
    $nombre_beneficiario = '';
    $estado = 0;
    $buscar_servicio = 0;
    $id_establecimiento = 0;
    $buscar_servicio_destino = 0;
    $establecimiento_actual = Auth::user()->id_establecimiento;

    $servicio = "SELECT distinct (c.id ) as id,
    c.nombre
    from sub_consultorios sc
    inner join consultorios c on c.id = sc.id_consultorio
    where sc.id_establecimiento  = '$establecimiento_actual' order by nombre asc ";
    $servicio = DB::select($servicio);

    $establecimiento = "SELECT e.*,e.id,e.nombre,e.nivel from establecimientos e where e.id_estado_reg='1' and ubigeo is not null and codigo is not null order by nivel  desc ";
    $establecimiento = DB::select($establecimiento);

    return view('frontend.referencias.all_referencias', compact('farmacia', 'fechaDesde','buscar_servicio','id_establecimiento','buscar_servicio_destino','servicio','establecimiento','establecimiento_actual', 'fechaHasta', 'dni_beneficiario', 'nombre_beneficiario', 'estado'));
  }

  public function listar_referencia_ajax(Request $request)
  {
    $establecimiento_actual = Auth::user()->id_establecimiento;
    $model_referencias = new Referencia();
    $p[] = $establecimiento_actual;
    $p[] = $request->id_establecimiento;
    $p[] = $request->fecha_ini;
    $p[] = $request->fecha_fin;
    $p[] = $request->dni_beneficiario;
    $p[] = strtoupper($request->nombre_beneficiario);
    $p[] = $request->estado;
    $p[] = $request->buscar_servicio;
    $p[] = $request->buscar_servicio_destino;
    $p[] = $request->NumeroPagina;
    $p[] = $request->NumeroRegistros;
    $totalRegistros = Referencia::count();
    //dd($p);
    $data = $model_referencias->listar_referencia_ajax($p);
    error_log($data == null);
    $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;
    $result["PageStart"] = $request->NumeroPagina;
    $result["pageSize"] = $request->NumeroRegistros;
    $result["SearchText"] = "";
    $result["ShowChildren"] = true;
    $result["iTotalRecords"] = $totalRegistros;
    $result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
    $result["aaData"] = $data;
    echo json_encode($result);
  }

  public function listar_referencia_ajax2()
  {

    $model_interconsultas = new Referencia();
    $p[] = 76;
    $p[] = '12-10-2021';
    $p[] = '12-11-2021';
    $p[] = '';
    $p[] = '';
    $p[] = 1;
    $p[] = 1;
    $p[] = 10;
    $data = $model_interconsultas->listar_referencia_ajax($p);
    $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;
    $result["PageStart"] = 1;
    $result["pageSize"] = 10;
    $result["SearchText"] = "";
    $result["ShowChildren"] = true;
    $result["iTotalRecords"] = $iTotalDisplayRecords;
    $result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
    $result["aaData"] = $data;
    echo json_encode($result);
  }


  public function atender_interconsulta($id)
  {
    
  }

  public function ver_atencion_referencia($id_prestacion,$id_referencia){

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $modelfarma = new Farmacia;
    $model_establecimiento = new \App\Models\Establecimiento;
    $model_prestacion = new \App\Models\Prestaciones;
    $model_referencia = new \App\Models\Referencia;
    

    $farmacia = $modelfarma->GetFarmaByUser(Auth::user()->id);
    $id_farmacia = $farmacia->id;
    $flag_receta_dias = $farmacia->flag_receta_dias;

    
    $date = Carbon::now();
    $fecha_actual = $date->format('d-m-Y');
    $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
    
    $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
    $procedimiento = $model_prestacion->GetProcedimientosByIdPrestacion($id_prestacion, 1);
    $pendiente_firma = $model_prestacion->GetDocumentosPendintesFirmarPrestacionId($id_prestacion);
    $flag_pendinte_firma = (count($pendiente_firma) > 0) ? 1 : 0;

    $id_ipress = $farmacia->id_establecimiento;
    $establecimiento = $model_establecimiento->GetById($id_ipress);
    if ($id_ipress == 1) {
      $nivel = 2;
    } else {
      $nivel = $establecimiento[0]->nivel;
    }
    
    $farmacias = $modelfarma->GetFarmaciaByIdEstablecimiento($id_ipress);
    $establecimientos = $model_establecimiento->ShowAllEstablecimientos();
    $referencia = $model_referencia->GetReferenciaById($id_prestacion,$id_referencia);
    

    return view('frontend.referencias.ver_atencion', compact('farmacia', 'prestacion', 'diagnostico', 'procedimiento', 'medicamentos', 'id_prestacion',  'farmacias', 'nivel', 'flag_pendinte_firma','establecimientos','referencia'));
  }

  public function modal_referencia_paciente($id_prestacion ){
        $model_referencia = new \App\Models\Referencia;
        $referencia = $model_referencia->GetReferenciaByIdPrest($id_prestacion);
        
        if(is_object($referencia)):
          $model_establecimiento = new \App\Models\Establecimiento;
          $establecimiento = $model_establecimiento->GetById($referencia->id_establecimiento);
        endif;

        return view('frontend.epicrisis.modal_referencias',compact('referencia', 'establecimiento'));
    }

    public function form_solicitud($id_referencia,$id_asegurado){
      
      $id_establecimiento= Auth::user()->id_establecimiento;
      $model_establecimiento = new \App\Models\Establecimiento;
      $local = $model_establecimiento->GetById($id_establecimiento);
      $nivel = $local[0]->nivel;
      $model_referencia = new \App\Models\Referencia;
      $referencia = $model_referencia->GetBuscaReferenciaById($id_referencia);

      $ipress = $model_referencia->busca_ipress_x_servicio($referencia->id_servicio,$id_establecimiento,$nivel);
      $asegurado = $model_referencia->buscar_asegurado_pasiente($id_asegurado,$id_establecimiento);

      $ipress_no_pnp = "SELECT codigo as id, establecimiento as nombre_ipress FROM ipress_no_pnp where categoria != 'I-1' order by codigo desc";
      $ipress_no_pnp = DB::select($ipress_no_pnp);

      return view('frontend.referencias.form_solicitud',compact('ipress','referencia','asegurado','id_referencia','ipress_no_pnp'));
    }

    public function form_solicitud_contrareferencia($id_referencia,$id_asegurado){
      
      $id_establecimiento= Auth::user()->id_establecimiento;
      $model_establecimiento = new \App\Models\Establecimiento;
      $local = $model_establecimiento->GetById($id_establecimiento);
      $nivel = $local[0]->nivel;
      $model_referencia = new \App\Models\Referencia;
      $referencia = $model_referencia->GetBuscaReferenciaById($id_referencia);
      $ipress = $model_referencia->busca_ipress_x_servicio_contra($referencia->id_servicio_contrareferencia,$id_establecimiento,$nivel);
      $asegurado = $model_referencia->buscar_asegurado_pasiente($id_asegurado,$id_establecimiento);

      return view('frontend.referencias.form_solicitud_contrareferencia',compact('ipress','referencia','asegurado','id_referencia'));
    }
  
    public function get_ipress($id_establecimiento){
      $id_establecimiento= Auth::user()->id_establecimiento;
      $model_establecimiento = new \App\Models\Establecimiento;
      $establecimiento = $model_establecimiento->Get($id_establecimiento);
      return json_encode($establecimiento);
    }
    public function get_datosasegurado($id_asegurado,$id_establecimiento){
      
      $id_establecimiento= Auth::user()->id_establecimiento;
      $model_asegurado = new \App\Models\Asegurado;
      $asegurado = $model_asegurado->busca_datos_asegurados_porid_nsolicitud($id_asegurado,$id_establecimiento);
      return json_encode($asegurado);
    }

    public function obtener_si_existe_referencia($tipodoc_beneficiario,$dni_beneficiario){
      
      $id_establecimiento= Auth::user()->id_establecimiento;
      $model_referencia = new \App\Models\Referencia;
      $asegurado = $model_referencia->obtener_si_existe_referencia($tipodoc_beneficiario,$dni_beneficiario);
      $dato = 0;
      if($asegurado->flag_referencia == '1'){
          if($id_establecimiento == $asegurado->id_establecimiento_referido){
            $dato = 2;
          }else{
            $dato = 1;
          }
      }
      return $dato;
    }

    public function formulario_cita_referencia($id_referencia) {
      if (!Auth::check()) :
        return redirect('/auth/login');
      endif;
  
      $model_kardex = new \App\Models\Kardexe;
      $model_usuario = new User;
      $model_consultorio = new \App\Models\Consultorio;
      $model_referido = new \App\Models\Referencia;
      $model_prestacion = new \App\Models\Prestaciones;
  
      $fechaServidor = $model_kardex->getFechaServidor();
      $user = $model_usuario->GetById(Auth::user()->id);
      $flagemergenciareferencia = $user->flagemergenciareferencia;
      $establecimientoactual = Auth::user()->id_establecimiento;
        $date = new Carbon();
        $anio = $date->format('Y');
      $servicio = "SELECT distinct (sc.id_consultorio ) as id,
                      c.nombre
                      from sub_consultorios sc
                      inner join consultorios c on c.id = sc.id_consultorio
                      where sc.id_establecimiento  = '$establecimientoactual'";
      $servicio = DB::select($servicio);
  
      $estableciemientoreferencia = "SELECT  
                                    *,
                                    concat(nombre,' - ',red_region,' - ',codigo )  as concatenado
                                    from establecimientos e where id = '76'";
                                    $estableciemientoreferencia = DB::select($estableciemientoreferencia);
      
      $referencia = $model_referido->GetBuscaReferenciaById($id_referencia);
      $prestacion = $model_prestacion->getServicioPorIdPrestacion($referencia->id_prestacion);
      $prestacion = $prestacion[0];
      $consultorio_destino = $model_consultorio->GetNombreById($referencia->id_servicio);
      $tipo_doc = $prestacion->id_tipo_doc_ident;
      $dni_referido = $prestacion->nro_doc_ident;
      $esReferido = $prestacion->referido_origen;
      $servicio_destino[] = $consultorio_destino->id;
      $servicio_destino[] = $consultorio_destino->nombre;
      $servicio_origen[] = $prestacion->id_servicio_origen;
      $servicio_origen[] = $prestacion->nombre_servicio_origen;
      $ipress_origen[] = $prestacion->id_iprees_origen;
      $ipress_origen[] = $prestacion->nombre_iprees_origen;
 
      if( $consultorio_destino->id == '113'){
              $model_laboratorio = new \App\Models\Laboratorio;
      $res = $model_laboratorio->obtenerOrdenesDeLaboratorioCitaOrigenCitaResultado($prestacion->nro_doc_ident, $referencia->id_establecimiento );
      ;
      if (is_array($res) && count($res) > 0) {
         $flag_laboratorio = 1;
            $flag_imagenes = 0;
           
        foreach ($res as $key) {
            $key->cpms = $model_laboratorio->obtenerCpmsPorIdLaboratorio($key->id); 
        }
        }
       else {
            $flag_laboratorio = 0;
               $flag_imagenes = 0;
        }
      }else  if( $consultorio_destino->id == '114'){
            
        $model_imagenes = new \App\Models\Radiologia;

        $res = $model_imagenes->obtenerOrdenesDeImagenes($prestacion->nro_doc_ident, $referencia->id_establecimiento, 0 );
        $res = [$res[0]];
      if (is_array($res) && count($res) > 0) {
         $flag_imagenes = 1;
          $flag_laboratorio = 0;
        foreach ($res as $key) {
       
            $radio = "SELECT p.codigo as codigo_lab, p.descripcion as descripcion_corta FROM prestacion_procedimientos  pp
                      inner join procedimientos p on pp.id_procedimiento = p.id
                      where  pp.id_lab_rad = '$key->id' AND pp.tipo_procedimiento = '3'";
                      $radio = DB::select($radio);
                     $key->cpms =$radio;
        }
        }
       else {
            $flag_imagenes = 0;
             $flag_laboratorio = 0;
        }
      }else{
        $res = 0;
      }

      return view('frontend.referencias.form_cita_referencia', compact('fechaServidor','servicio','anio','flagemergenciareferencia','estableciemientoreferencia',
                                                                       'dni_referido','tipo_doc','esReferido','servicio_destino','servicio_origen','ipress_origen'
                                                                       ,'id_referencia','flag_laboratorio','flag_imagenes','res'));
    }
    public function validar_referencia(Request $request){
      
      $referencia = Referencia::find($request->id);

      $model_actuliza_estado = new \App\Models\Actualizacion_estado;
      $model_actuliza_estado->estado_antiguo = $referencia->estado_proceso;
      $model_actuliza_estado->id_referencia = $request->id;
      if($request->flag_valida_ref == '2'){
        $model_actuliza_estado->motivo_actualizacion = "guardado los datos de la invalidacion de la referencia";
        $model_actuliza_estado->estado_nuevo = 3;
      }else{
        $model_actuliza_estado->motivo_actualizacion = "guardado los datos de la validacionde la referencia";
        $model_actuliza_estado->estado_nuevo = 4;
      }
      $model_actuliza_estado->created_us = Auth::user()->id;
      $model_actuliza_estado->flag_estado_reg = 1;
      $model_actuliza_estado->detalle_motivo = $request->recomendaciones;
      $model_actuliza_estado->save();


      $referencia->flag_valida_ref = $request->flag_valida_ref;
      $referencia->recomendaciones = $request->recomendaciones;

      if($request->flag_valida_ref == '2'){
        $referencia->estado_proceso = 3;
      }else{
        $referencia->estado_proceso = 4;
      }

      $referencia->updated_at = Carbon::now();
      $referencia->save();
      return json_encode(true);
    }

    public function registrar_solicitud_formulario(Request $request)
    {
     
      $model_referencia = \App\Models\Referencia::find($request->id_referencia);
     
      if($model_referencia->id_servicio == '113'){
              $examenes = "SELECT l.id from laboratorios l 
                           where l.id_prestacion = $model_referencia->id_prestacion";
                   $examenes = DB::select($examenes);
                       foreach($examenes as $id_lab){                
                            $model_laboratorio = \App\Models\Laboratorio::find($id_lab->id);
                            $model_laboratorio->id_establecimiento_origen = $model_laboratorio->id_establecimiento;
                            $model_laboratorio->id_establecimiento = $request->establecimiento_destino;
                            $model_laboratorio->save();
                       }
      }else if($model_referencia->id_servicio == '114'){
        $esamenes = "SELECT l.id from radiologias l 
                           where l.id_prestacion = $model_referencia->id_prestacion";
        $esamenes = DB::select($esamenes);
           foreach($esamenes as $id_lab){                
                            $model_laboratorio = \App\Models\Radiologia::find($id_lab->id);
                            $model_laboratorio->id_establecimiento_origen = $model_laboratorio->id_establecimiento;
                            $model_laboratorio->id_establecimiento = $request->establecimiento_destino;
                            $model_laboratorio->save();
                            }
      }
      $asegurado = \App\Models\Asegurado::find($model_referencia->id_asegurado);
      $asegurado->flag_referencia = 1;
      $asegurado->id_referido = $request->id_referencia;
      $asegurado->save();
 
      $model_referencia->nombre_personal_coor = $request->personal_coordinacion;
      if($request->establecimiento_destino != '0'){
        $model_referencia->id_establecimiento_des = $request->establecimiento_destino;
        $model_referencia->referencias_ipress_no_pnp = '0';
              $model_referencia->estado_proceso = 2;
      }else{
        $model_referencia->id_establecimiento_des = $request->establecimiento_destino_pnp;
        $model_referencia->referencias_ipress_no_pnp = '1';
              $model_referencia->estado_proceso = 9;
      }
   
      $model_referencia->familiar_acompanante = $request->nombre_personal_acoma;
      $model_referencia->numero_familiar_acompanante = $request->celular_personal_acoma;
        $model_referencia->id_nivel_categoria = !empty($request->nivel_categoria) ? $request->nivel_categoria: 0;
        $model_referencia->id_tipo_ambulancia = !empty($request->tipo_ambulancia) ? $request->tipo_ambulancia : 0;
        $model_referencia->id_pasaje = !empty($request->tipo_ruta) ? $request->tipo_ruta : 0;

        $model_referencia->flag_uci = ($request->id_uci_referencia === "false") ? 1 : 0;
        $model_referencia->flag_ventilador = ($request->ventilador_mecanico === "false") ? 1 : 0;

        $model_referencia->flag =
            ($request->id_uci_referencia === "false" || $request->ventilador_mecanico === "false")
                ? 2
                : 1;

      $model_referencia->save();
    
      $model_actuliza_estado = new \App\Models\Actualizacion_estado;
      $model_actuliza_estado->estado_antiguo = 1;
      $model_actuliza_estado->estado_nuevo = 2;
      $model_actuliza_estado->id_referencia = $request->id_referencia;
      $model_actuliza_estado->motivo_actualizacion = "guardado de datos para llenar los datos en los documentos de referencia";
      $model_actuliza_estado->created_us = Auth::user()->id;
      $model_actuliza_estado->flag_estado_reg = 1;
      $model_actuliza_estado->save();
       
      return redirect('/all_referencias');

    }
  
    public function registrar_cita_transaction_referencia(Request $request)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    //dd($request->id_referencia);
    $esAfiliado = (int)$request->es_afiliado;
    
    $id_turno = 0;
    if (!empty($request->id_turno)) {
      $id_turno = $request->id_turno;
    }

    $model_cita = new \App\Models\Cita;
    $id_cita = 0;

    if($request->establecimiento_referencia != ""){
      
      $establecimientoref = "SELECT *, e.nombre as nombre_establecimiento from establecimientos e where id = '$request->establecimiento_referencia'";
    $establecimientoref = DB::select($establecimientoref);
    $establecimientoref = $establecimientoref[0];
    }else{
      $establecimientoref = "SELECT *, e.nombre as nombre_establecimiento from establecimientos e where id = '76'";
      $establecimientoref = DB::select($establecimientoref);
      $establecimientoref = $establecimientoref[0];
      $establecimientoref->nombre_establecimiento = 0;
    }

    if($request->referido == "NO"){
      $request->servicio_referencia   = $request->consultorio_referencia;
    }
    
    $id_cita = $model_cita->registrarCitaReferencia($id_cita, $request->id_establecimiento, $request->id_consultorio/*id_servicio*/, /*$id_medico*/ NULL, $request->id_user, $request->fecha_cita, $request->tipodoc_beneficiario, $request->nrodocafiliado, $request->nombre_beneficiario, $request->paterno_beneficiario, $request->materno_beneficiario, $request->tipo_beneficiario, $request->nro_historia, $request->grado, $request->fecnacafiliado, $request->nomsexo, $request->cip_beneficiario, $request->tipodoc_titular, $request->nrodoctitular, $request->nombre_titular, $request->paterno_titular, $request->materno_titular, NULL, 1, 1, $request->correo, $request->domicilio, $request->telefono,$request->telefono2, 1,1,$request->referido,$request->adicional
    ,$establecimientoref->nombre_establecimiento,$request->servicio_referencia,$request->tipo_doc,$request->numero_doc,$request->ano,$request->origen_doc,$request->codigo_renipres,$request->obs_registro_cita, $request->id_referencia);

    $model_referencia = \App\Models\Referencia::find($request->id_referencia);
    $model_actuliza_estado = new \App\Models\Actualizacion_estado;
    $model_actuliza_estado->estado_antiguo =  $model_referencia->estado_proceso;
    $model_actuliza_estado->estado_nuevo = 5;
    $model_actuliza_estado->id_referencia = $request->id_referencia;
    $model_actuliza_estado->motivo_actualizacion = "guardado la cita de la referencia";
    $model_actuliza_estado->created_us = Auth::user()->id;
    $model_actuliza_estado->flag_estado_reg = 1;
    $model_actuliza_estado->save();

    if($model_referencia->id_servicio == '114' || $model_referencia->id_servicio == '113'){
 $model_referencia->estado_proceso = 7;
      if($model_referencia->id_servicio == '114'){
      
      $radio =  DB::table('radiologias')
    ->select('radiologias.id')
    ->where('id_prestacion',$model_referencia->id_prestacion)
    ->first();
      $presta_proce = "SELECT * FROM prestacion_procedimientos WHERE id_lab_rad = '$radio->id' and tipo_procedimiento = '3'";
      $presta_proce = DB::select($presta_proce);
      foreach($presta_proce as $pp){
        $model_pp = \App\Models\Prestacion_procedimiento::find($pp->id);
        $model_pp->flag_cita_imagenes = '1';
        $model_pp->id_citas_multiples_rad = $id_cita;
        $model_pp->save();
      }

    }
   if($model_referencia->id_servicio == '113'){
      $prest =  DB::table('prestaciones')
    ->select('prestaciones.id_cita')
    ->where('id',$model_referencia->id_prestacion)
    ->first();
     $model_ci = \App\Models\Cita::find($prest->id_cita);
        $model_ci->flag_cita_laboratorio_ce = '1';
        $model_ci->id_cita_laboratorio_ce = $id_cita;
        $model_ci->save();
   }

}else{
   $model_referencia->estado_proceso = 5;
}
    $model_referencia->id_cita_triaje_referencia = $id_cita;
    $model_referencia->save();

    $medico_turno = new \App\Models\Medico_turno;
    $fecha_atencion = date("Y-m-d", strtotime($request->fecha_cita));

    if ($id_turno == 0) {
      $medico_turno->registrarMedicoCita($fecha_atencion, $request->id_consultorio);
    } else {
      $medico_turno->registrarMedicoCitaHorario($id_cita, $fecha_atencion, $request->id_consultorio, $id_turno);
    }

    echo $id_cita;
  }

    public function registrar_formulario_contrareferencia(Request $request)
    {
   
      $model_referencia = \App\Models\Referencia::find($request->id_referencia);
      $asegurado = \App\Models\Asegurado::find($model_referencia->id_asegurado);
      $asegurado->flag_referencia = 0;
      $asegurado->id_establecimiento_referido = 0;
      $asegurado->save();

      $model_referencia->datos_auxiliar_contrareferencia = $request->datos_extra_contrareferencia;
      $model_referencia->estado_proceso = 7;
      $model_referencia->id_establecimiento_contrareferencia = $request->establecimiento_destino;   
      $model_referencia->save();
    
      $model_actuliza_estado = new \App\Models\Actualizacion_estado;
      $model_actuliza_estado->estado_antiguo = 6;
      $model_actuliza_estado->estado_nuevo = 7;
      $model_actuliza_estado->id_referencia = $request->id_referencia;
      $model_actuliza_estado->motivo_actualizacion = "Se finaliza la Referencia con la Contrareferencia";
      $model_actuliza_estado->created_us = Auth::user()->id;
      $model_actuliza_estado->flag_estado_reg = 1;
      $model_actuliza_estado->save();
       
      return redirect('/all_referencias');

    }

    public function get_procedimientos()
    {
      $procedimientos = new Procedimiento;
      $dato = $procedimientos->procedimientosxestado();
      return json_encode($dato);
    }

    public function modal_historial_rc($id_asegurado){
      $modal_referencias =new \App\Models\Referencia; 
      $referencias = $modal_referencias->get_historial_id_asegurado($id_asegurado);
      return view('frontend.referencias.modal_historial_rc',compact('referencias'));
  }

  public function modal_historial_rc_dni($dni_beneficiario){
    $modal_referencias =new \App\Models\Referencia; 
    $referencias = $modal_referencias->get_historial_dni_asegurado($dni_beneficiario);
    return view('frontend.referencias.modal_historial_rc_dni',compact('referencias'));
  }

  public function pdf_formato_fups($id_prestacion){	

    $model_referencia = new \App\Models\Referencia;
    $data = $model_referencia->get_formato_fups($id_prestacion);	
    $nacidos = $model_referencia->fups_nacidos($id_prestacion);
    $fups_diagnosticos = $model_referencia->fups_diagnosticos($id_prestacion);
    ///////////////////////PRODUCTOS FARMACEUTICOS////////////////////////
    $fups_productos_famaceuticos = $model_referencia->fups_productos_famaceuticos($id_prestacion);
    ///////////////////////DISPOSITIVOS MEDICOS/PRODUCTOS SANITARIOS////////////////////////
    $fups_dispositivos_medicos = $model_referencia->fups_dispositivos_medicos($id_prestacion);
    //////////////////////PROCEDIMIENTOS/DIAGNOSTICOS////////////////////////
    $fups_procedimientos = $model_referencia->fups_procedimientos($id_prestacion);
    
    $datos = array(	
      'numero_formato_1'=> !empty($data->numero_formato_1) ? $data->numero_formato_1 : '',	
      'numero_formato_2'=> !empty($data->numero_formato_2) ? $data->numero_formato_2 : '',	
      'numero_formato_3'=> !empty($data->numero_formato_3) ? $data->numero_formato_3 : '',	
      'codigo_unico_de_renipress'=> !empty($data->codigo_unico_de_renipress) ? $data->codigo_unico_de_renipress : '',	
      'nombre_ipress_atencion'=> !empty($data->nombre_ipress_atencion) ? $data->nombre_ipress_atencion : '',	
      'lugar_de_atencion'=> !empty($data->lugar_de_atencion) ? $data->lugar_de_atencion : '',	
      'tipo_ingreso'=> !empty($data->tipo_ingreso) ? $data->tipo_ingreso : '',
      'cod_ipress'=> !empty($data->cod_ipress) ? $data->cod_ipress : '',	
      'nombre_de_la_ipress'=> !empty($data->nombre_de_la_ipress) ? $data->nombre_de_la_ipress : '',
      'n_hoja_de_referencia'=> !empty($data->hoja_referencia) ? $data->hoja_referencia : '',	
      'identificacion_tdi'=> !empty($data->tdi) ? $data->tdi : '',	
      'identificacion_dni'=> !empty($data->nro_doc_ident) ? $data->nro_doc_ident : '',	
      'historia_clinica'=> !empty($data->historia_clinica) ? $data->historia_clinica : '',	
      'sexo'=> !empty($data->sexo) ? $data->sexo : '',	
      'dia_nacimiento'=> !empty($data->dia_nac) ? $data->dia_nac : '',	
      'mes_nacimiento'=> !empty($data->mes_nac) ? $data->mes_nac : '',	
      'anio_nacimiento'=> !empty($data->anio_nac) ? $data->anio_nac : '',
      'dia_fallecido'=> !empty($data->dia_fall) ? $data->dia_fall : '',
      'mes_fallecido'=> !empty($data->mes_fall) ? $data->mes_fall : '',
      'anio_fallecido'=> !empty($data->anio_fall) ? $data->anio_fall : '',
      'apellido_paterno'=> !empty($data->ape_paterno) ? $data->ape_paterno : '',	
      'apellido_materno'=> !empty($data->ape_materno) ? $data->ape_materno : '',	
      'primer_nombre'=> !empty($data->primer_nombre) ? $data->primer_nombre : '',	
      'otros_nombres'=> !empty($data->otros_nombres) ? $data->otros_nombres : '',
      'etapa_gestante'=> !empty($data->etapa) ? $data->etapa : '',		
      'nacidos'=> !empty($nacidos) ? $nacidos : '',	
      'dia_probable_parto'=> !empty($data->dia_prob_part) ? $data->dia_prob_part : '',//AQUI TRAE YA SEA EL PROBABLE O EL DE PARTO
      'mes_probable_parto'=> !empty($data->mes_prob_part) ? $data->mes_prob_part : '',//AQUI TRAE YA SEA EL PROBABLE O EL DE PARTO
      'anio_probable_parto'=> !empty($data->anio_prob_part) ? $data->anio_prob_part : '',//AQUI TRAE YA SEA EL PROBABLE O EL DE PARTO
      'dia_atencion'=> !empty($data->dia) ? $data->dia : '',// DE LA ATENCION
      'mes_atencion'=> !empty($data->mes) ? $data->mes : '',
      'anio_atencion'=> !empty($data->anio) ? $data->anio : '',
      'hora_atencion'=> !empty($data->hora) ? $data->hora : '',
      'renipress'=> !empty($data->renipress) ? $data->renipress : '', //N? DE FUPS A VINCULAR
      'lote'=> !empty($data->lote) ? $data->lote : '', //N? DE FUPS A VINCULAR
      'correlativo'=> !empty($data->correlativo) ? $data->correlativo : '', //N? DE FUPS A VINCULAR
      'upss'=> !empty($data->upss) ? $data->upss : '',
      'descripcion'=> !empty($data->descripcion) ? $data->descripcion : '',
      'tipo_prestacion'=> !empty($data->tipo_prestacion) ? $data->tipo_prestacion : '',
      'dia_ingreso'=> !empty($data->dia_ingreso) ? $data->dia_ingreso : '',	//FECHA DE INGRESO
      'mes_ingreso'=> !empty($data->mes_ingreso) ? $data->mes_ingreso : '',		//FECHA DE INGRESO
      'anio_ingreso'=> !empty($data->anio_ingreso) ? $data->anio_ingreso : '',	//FECHA DE INGRESO
      'hora_ingreso'=> !empty($data->hora_fecha_ingreso) ? $data->hora_fecha_ingreso : '',		//FECHA DE INGRESO
      'minuto_ingreso'=> !empty($data->min_fecha_ingreso) ? $data->min_fecha_ingreso : '',	//FECHA DE INGRESO
      'dia_inicio_ca'=> !empty($data->dia_inicio_ca) ? $data->dia_inicio_ca : '',	//CORTE ADMINISTRATIVO (APLICA PARA TIPO 5)
      'mes_inicio_ca'=> !empty($data->mes_inicio_ca) ? $data->mes_inicio_ca : '',	//CORTE ADMINISTRATIVO (APLICA PARA TIPO 5)
      'anio_inicio_ca'=> !empty($data->anio_inicio_ca) ? $data->anio_inicio_ca : '',	//CORTE ADMINISTRATIVO (APLICA PARA TIPO 5)
      'hora_inicio_ca'=> !empty($data->hora_inicio_ca) ? $data->hora_inicio_ca : '',	//CORTE ADMINISTRATIVO (APLICA PARA TIPO 5)
      'min_inicio_ca'=> !empty($data->min_inicio_ca) ? $data->min_inicio_ca : '',
      'dia_fin_ca'=> !empty($data->dia_fin_ca) ? $data->dia_fin_ca : '',	//CORTE ADMINISTRATIVO (APLICA PARA TIPO 5)
      'mes_fin_ca'=> !empty($data->mes_fin_ca) ? $data->mes_fin_ca : '', //CORTE ADMINISTRATIVO (APLICA PARA TIPO 5)
      'anio_fin_ca'=> !empty($data->anio_fin_ca) ? $data->anio_fin_ca : '', //CORTE ADMINISTRATIVO (APLICA PARA TIPO 5)
      'hora_fin_ca'=> !empty($data->hora_fin_ca) ? $data->hora_fin_ca : '',	//CORTE ADMINISTRATIVO (APLICA PARA TIPO 5)
      'min_fin_ca'=> !empty($data->min_fin_ca) ? $data->min_fin_ca : '',//CORTE ADMINISTRATIVO (APLICA PARA TIPO 5)
      'dia_fecha_Egreso'=>  !empty($data->dia_fecha_egreso) ? $data->dia_fecha_egreso : '',	 //FECHA DE EGRESO
      'mes_fecha_Egreso'=> !empty($data->mes_fecha_egreso) ? $data->mes_fecha_egreso : '',	 //FECHA DE EGRESO
      'anio_fecha_Egreso'=>!empty($data->anio_fecha_egreso) ? $data->anio_fecha_egreso : '',	 //FECHA DE EGRESO
      'hora_fecha_Egreso'=> !empty($data->hora_fecha_egreso) ? $data->hora_fecha_egreso : '',	 //FECHA DE EGRESO
      'min_fecha_Egreso'=> !empty($data->min_fecha_egreso) ? $data->min_fecha_egreso : '',	 //FECHA DE EGRESO
      'tipo_destino'=> !empty($data->tipo_destino) ? $data->tipo_destino : '',	//SE REFIERE / CONTRARREFIERE A
      'codigo_renae'=> !empty($data->codigo_renae) ? $data->codigo_renae : '', //SE REFIERE / CONTRARREFIERE A
      'codigo_nombre'=> !empty($data->codigo_nombre) ? $data->codigo_nombre : '', //SE REFIERE / CONTRARREFIERE A
      'n_hoja_ref_contra'=> !empty($data->n_hoja_ref_contra) ? $data->n_hoja_ref_contra : '', //SE REFIERE / CONTRARREFIERE A
      'tipo_documento'=> !empty($data->tipo_doc) ? $data->tipo_doc : '',
      'dni'=> !empty($data->nro_doc) ? $data->nro_doc : '',
      'medico_responsable'=> !empty($data->nombre_responsable) ? $data->nombre_responsable : '',
      'tipo_profesion'=> !empty($data->tipo_prof) ? $data->tipo_prof : '',
      'especialidad'=> !empty($data->especialidad) ? $data->especialidad : '',
      'n_rnie'=> !empty($data->rne) ? $data->rne : '',
      'colegiatura'=> !empty($data->colegiatura) ? $data->colegiatura : '',	
      'observaciones'=> !empty($data->observaciones) ? $data->observaciones : '',	
      'vincular_renipress'=> !empty($data->vincular_renipress) ? $data->vincular_renipress : '',
      'vincular_lote'=> !empty($data->vincular_lote) ? $data->vincular_lote : '',
      'vincular_correlativo'=> !empty($data->vincular_correlativo) ? $data->vincular_correlativo : ''
    );	

    $pdf = \PDF::loadView('frontend.referencias.pdf_formato_fups', 	
    compact(	
      'datos',
      'fups_diagnosticos',
      'fups_productos_famaceuticos',
      'fups_dispositivos_medicos',
      'fups_procedimientos'
    ));	
    $pdf->getDomPDF()->set_option("enable_php", true);	
    $pdf->setPaper('A4', 'portrait'); //Horizontal	
    return $pdf->stream('pdf_formato_fups.pdf');	
  }

  public function pdf_formato_fups_vinculante_receta($id_prestacion){	

    $model_referencia = new \App\Models\Referencia;
    $data = $model_referencia->get_formato_fups($id_prestacion);	
    $nacidos = $model_referencia->fups_nacidos($id_prestacion);
    $fups_diagnosticos = $model_referencia->fups_diagnosticos($id_prestacion);
    ///////////////////////PRODUCTOS FARMACEUTICOS////////////////////////
    $fups_productos_famaceuticos = $model_referencia->fups_productos_famaceuticos($id_prestacion);
    ///////////////////////DISPOSITIVOS MEDICOS/PRODUCTOS SANITARIOS////////////////////////
    $fups_dispositivos_medicos = $model_referencia->fups_dispositivos_medicos($id_prestacion);
    //////////////////////PROCEDIMIENTOS/DIAGNOSTICOS////////////////////////
    $fups_procedimientos = $model_referencia->fups_procedimientos($id_prestacion);
    
    $datos = array(	
      'numero_formato_1'=> !empty($data->numero_formato_1) ? $data->numero_formato_1 : '',	
      'numero_formato_2'=> !empty($data->numero_formato_2) ? $data->numero_formato_2 : '',	
      'numero_formato_3'=> !empty($data->numero_formato_3) ? $data->numero_formato_3 . '-1' : '',	
      'codigo_unico_de_renipress'=> !empty($data->codigo_unico_de_renipress) ? $data->codigo_unico_de_renipress : '',	
      'nombre_ipress_atencion'=> !empty($data->nombre_ipress_atencion) ? $data->nombre_ipress_atencion : '',	
      'lugar_de_atencion'=> !empty($data->lugar_de_atencion) ? $data->lugar_de_atencion : '',	
      'tipo_ingreso'=> !empty($data->tipo_ingreso) ? $data->tipo_ingreso : '',
      'cod_ipress'=> !empty($data->cod_ipress) ? $data->cod_ipress : '',	
      'nombre_de_la_ipress'=> !empty($data->nombre_de_la_ipress) ? $data->nombre_de_la_ipress : '',
      'n_hoja_de_referencia'=> !empty($data->hoja_referencia) ? $data->hoja_referencia : '',	
      'identificacion_tdi'=> !empty($data->tdi) ? $data->tdi : '',	
      'identificacion_dni'=> !empty($data->nro_doc_ident) ? $data->nro_doc_ident : '',	
      'historia_clinica'=> !empty($data->historia_clinica) ? $data->historia_clinica : '',	
      'sexo'=> !empty($data->sexo) ? $data->sexo : '',	
      'dia_nacimiento'=> !empty($data->dia_nac) ? $data->dia_nac : '',	
      'mes_nacimiento'=> !empty($data->mes_nac) ? $data->mes_nac : '',	
      'anio_nacimiento'=> !empty($data->anio_nac) ? $data->anio_nac : '',
      'dia_fallecido'=> !empty($data->dia_fall) ? $data->dia_fall : '',
      'mes_fallecido'=> !empty($data->mes_fall) ? $data->mes_fall : '',
      'anio_fallecido'=> !empty($data->anio_fall) ? $data->anio_fall : '',
      'apellido_paterno'=> !empty($data->ape_paterno) ? $data->ape_paterno : '',	
      'apellido_materno'=> !empty($data->ape_materno) ? $data->ape_materno : '',	
      'primer_nombre'=> !empty($data->primer_nombre) ? $data->primer_nombre : '',	
      'otros_nombres'=> !empty($data->otros_nombres) ? $data->otros_nombres : '',
      'etapa_gestante'=> !empty($data->etapa) ? $data->etapa : '',		
      'nacidos'=> !empty($nacidos) ? $nacidos : '',	
      'dia_probable_parto'=> !empty($data->dia_prob_part) ? $data->dia_prob_part : '',//AQUI TRAE YA SEA EL PROBABLE O EL DE PARTO
      'mes_probable_parto'=> !empty($data->mes_prob_part) ? $data->mes_prob_part : '',//AQUI TRAE YA SEA EL PROBABLE O EL DE PARTO
      'anio_probable_parto'=> !empty($data->anio_prob_part) ? $data->anio_prob_part : '',//AQUI TRAE YA SEA EL PROBABLE O EL DE PARTO
      'dia_atencion'=> !empty($data->dia) ? $data->dia : '',// DE LA ATENCION
      'mes_atencion'=> !empty($data->mes) ? $data->mes : '',
      'anio_atencion'=> !empty($data->anio) ? $data->anio : '',
      'hora_atencion'=> !empty($data->hora) ? $data->hora : '',
      'renipress'=> !empty($data->renipress) ? $data->renipress : '', //N? DE FUPS A VINCULAR
      'lote'=> !empty($data->lote) ? $data->lote : '', //N? DE FUPS A VINCULAR
      'correlativo'=> !empty($data->correlativo) ? $data->correlativo : '', //N? DE FUPS A VINCULAR
      'upss'=> !empty($data->upss) ? $data->upss : '',
      'descripcion'=> !empty($data->descripcion) ? $data->descripcion : '',
      'tipo_prestacion'=> !empty($data->tipo_prestacion) ? $data->tipo_prestacion : '',
      'dia_ingreso'=> !empty($data->dia_ingreso) ? $data->dia_ingreso : '',	//FECHA DE INGRESO
      'mes_ingreso'=> !empty($data->mes_ingreso) ? $data->mes_ingreso : '',		//FECHA DE INGRESO
      'anio_ingreso'=> !empty($data->anio_ingreso) ? $data->anio_ingreso : '',	//FECHA DE INGRESO
      'hora_ingreso'=> !empty($data->hora_fecha_ingreso) ? $data->hora_fecha_ingreso : '',		//FECHA DE INGRESO
      'minuto_ingreso'=> !empty($data->min_fecha_ingreso) ? $data->min_fecha_ingreso : '',	//FECHA DE INGRESO
      'dia_inicio_ca'=> !empty($data->dia_inicio_ca) ? $data->dia_inicio_ca : '',	//CORTE ADMINISTRATIVO (APLICA PARA TIPO 5)
      'mes_inicio_ca'=> !empty($data->mes_inicio_ca) ? $data->mes_inicio_ca : '',	//CORTE ADMINISTRATIVO (APLICA PARA TIPO 5)
      'anio_inicio_ca'=> !empty($data->anio_inicio_ca) ? $data->anio_inicio_ca : '',	//CORTE ADMINISTRATIVO (APLICA PARA TIPO 5)
      'hora_inicio_ca'=> !empty($data->hora_inicio_ca) ? $data->hora_inicio_ca : '',	//CORTE ADMINISTRATIVO (APLICA PARA TIPO 5)
      'min_inicio_ca'=> !empty($data->min_inicio_ca) ? $data->min_inicio_ca : '',
      'dia_fin_ca'=> !empty($data->dia_fin_ca) ? $data->dia_fin_ca : '',	//CORTE ADMINISTRATIVO (APLICA PARA TIPO 5)
      'mes_fin_ca'=> !empty($data->mes_fin_ca) ? $data->mes_fin_ca : '', //CORTE ADMINISTRATIVO (APLICA PARA TIPO 5)
      'anio_fin_ca'=> !empty($data->anio_fin_ca) ? $data->anio_fin_ca : '', //CORTE ADMINISTRATIVO (APLICA PARA TIPO 5)
      'hora_fin_ca'=> !empty($data->hora_fin_ca) ? $data->hora_fin_ca : '',	//CORTE ADMINISTRATIVO (APLICA PARA TIPO 5)
      'min_fin_ca'=> !empty($data->min_fin_ca) ? $data->min_fin_ca : '',//CORTE ADMINISTRATIVO (APLICA PARA TIPO 5)
      'dia_fecha_Egreso'=>  !empty($data->dia_fecha_egreso) ? $data->dia_fecha_egreso : '',	 //FECHA DE EGRESO
      'mes_fecha_Egreso'=> !empty($data->mes_fecha_egreso) ? $data->mes_fecha_egreso : '',	 //FECHA DE EGRESO
      'anio_fecha_Egreso'=>!empty($data->anio_fecha_egreso) ? $data->anio_fecha_egreso : '',	 //FECHA DE EGRESO
      'hora_fecha_Egreso'=> !empty($data->hora_fecha_egreso) ? $data->hora_fecha_egreso : '',	 //FECHA DE EGRESO
      'min_fecha_Egreso'=> !empty($data->min_fecha_egreso) ? $data->min_fecha_egreso : '',	 //FECHA DE EGRESO
      'tipo_destino'=> !empty($data->tipo_destino) ? $data->tipo_destino : '',	//SE REFIERE / CONTRARREFIERE A
      'codigo_renae'=> !empty($data->codigo_renae) ? $data->codigo_renae : '', //SE REFIERE / CONTRARREFIERE A
      'codigo_nombre'=> !empty($data->codigo_nombre) ? $data->codigo_nombre : '', //SE REFIERE / CONTRARREFIERE A
      'n_hoja_ref_contra'=> !empty($data->n_hoja_ref_contra) ? $data->n_hoja_ref_contra : '', //SE REFIERE / CONTRARREFIERE A
      'tipo_documento'=> !empty($data->tipo_doc) ? $data->tipo_doc : '',
      'dni'=> !empty($data->nro_doc) ? $data->nro_doc : '',
      'medico_responsable'=> !empty($data->nombre_responsable) ? $data->nombre_responsable : '',
      'tipo_profesion'=> !empty($data->tipo_prof) ? $data->tipo_prof : '',
      'especialidad'=> !empty($data->especialidad) ? $data->especialidad : '',
      'n_rnie'=> !empty($data->rne) ? $data->rne : '',
      'colegiatura'=> !empty($data->colegiatura) ? $data->colegiatura : '',	
      'observaciones'=> !empty($data->observaciones) ? $data->observaciones : '',	
      'vincular_renipress'=> !empty($data->numero_formato_1) ? $data->numero_formato_1 : '',
      'vincular_lote'=> !empty($data->numero_formato_2) ? $data->numero_formato_2 : '',
      'vincular_correlativo'=> !empty($data->numero_formato_3) ? $data->numero_formato_3 : ''
    );	

    $pdf = \PDF::loadView('frontend.referencias.pdf_formato_fups', 	
    compact(	
      'datos',
      'fups_diagnosticos',
      'fups_productos_famaceuticos',
      'fups_dispositivos_medicos',
      'fups_procedimientos'
    ));	
    $pdf->getDomPDF()->set_option("enable_php", true);	
    $pdf->setPaper('A4', 'portrait'); //Horizontal	
    return $pdf->stream('pdf_formato_fups.pdf');	
  }

  public function atenciones_odontologia_reporte($id_user = 0)
  {
    $model_medico = new \App\Models\Medico();
    $model_usuario = new User;
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
    $model_farma = new Farmacia();
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
    $medicos= $model_medico->ShowSoloMedicos(Auth::user()->id_establecimiento);
    $model_consultorio = new Consultorio;
    $consultorio = $model_consultorio->ShowAllConsultoriosSubConsultorio($farmacia->id_establecimiento);
    $model_atencione = new TipoAtencione;
    $atencione = "SELECT * FROM tipo_atenciones where id = '1'";
    $atencione = DB::select($atencione);
    //$atencione = $atencione[0];
      return view('frontend.auditoria.all_atenciones_odontologia', compact('tipo_registro','usuario_actual', 'farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nombre_beneficiario', 'estado', 'flagemergenciareferencia', 'consultorio', 'medico', 'id_consultorio_actual', 'atencione', 'id_atencione','medicos'));
 
  }

  public function all_tele_referencia($id_user = 0)
  {
    $model_medico = new \App\Models\Medico();
    $model_usuario = new User;
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
    if ($ruta == "listar_referencia_ajax");
    $ruta = "all_referencias";
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
    $permiso = $menu[0];
    if ($permiso->lectura == "")
      return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
    $id_user = Auth::user()->id;

    $date = Carbon::now();
    $fechaHasta = $date->format('d-m-Y');
    $fechaDesdex = $date->format('d-m-Y');
    $fechaDesdey = strtotime('-1 day', strtotime($fechaDesdex));
    $fechaDesde = date('j-m-Y', $fechaDesdey);

    $model_farma = new Farmacia();
    $farmacia = $model_farma->GetFarmaByUser($id_user);

    $dni_beneficiario = '';
    $nombre_beneficiario = '';
    $estado = 0;
    $buscar_servicio = 0;
    $id_establecimiento = 0;
    $buscar_servicio_destino = 0;
    $establecimiento_actual = Auth::user()->id_establecimiento;

    $servicio = "SELECT distinct (c.id ) as id,
    c.nombre
    from sub_consultorios sc
    inner join consultorios c on c.id = sc.id_consultorio
    where sc.id_establecimiento  = '$establecimiento_actual' order by nombre asc ";
    $servicio = DB::select($servicio);

    $establecimiento = "SELECT e.*,e.id,e.nombre,e.nivel from establecimientos e where e.id_estado_reg='1' and ubigeo is not null and codigo is not null order by nivel  desc ";
    $establecimiento = DB::select($establecimiento);

    $medicos_tele_medicina = "SELECT * FROM medicos where tele_medicina = '1'";
    $medicos_tele_medicina = DB::select($medicos_tele_medicina);

    $consultorios_tele_medicina = "SELECT c.* FROM consultorios c
                                      inner join consultorio_farmacias cf on c.id = cf.id_consultorio
                                      inner join farmacias f on f.id = cf.id_farmacia
                                      inner join establecimientos e on e.id = f.id_establecimiento                                
                                      where c.id = '213' and e.id = '$establecimiento_actual' ";
    $consultorios_tele_medicina = DB::select($consultorios_tele_medicina);

    return view('frontend.referencias.all_telemedicina', compact('farmacia','consultorios_tele_medicina', 'fechaDesde','buscar_servicio','medicos_tele_medicina','id_establecimiento','buscar_servicio_destino','servicio','establecimiento','establecimiento_actual', 'fechaHasta', 'dni_beneficiario', 'nombre_beneficiario', 'estado'));
  }

  public function listar_telemedicina_ajax(Request $request)
  {
    $establecimiento_actual = Auth::user()->id_establecimiento;
    $model_referencias = new Referencia();
    $p[] = $establecimiento_actual;
    $p[] = $request->id_establecimiento;
    $p[] = $request->fecha_ini;
    $p[] = $request->fecha_fin;
    $p[] = $request->dni_beneficiario;
    $p[] = strtoupper($request->nombre_beneficiario);
    $p[] = $request->estado;
    $p[] = $request->buscar_servicio;
    $p[] = $request->buscar_servicio_destino;
    $p[] = $request->NumeroPagina;
    $p[] = $request->NumeroRegistros;
    $totalRegistros = Referencia::count();
    $data = $model_referencias->listar_telemedicina_ajax($p);
    error_log($data == null);
    $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;
    $result["PageStart"] = $request->NumeroPagina;
    $result["pageSize"] = $request->NumeroRegistros;
    $result["SearchText"] = "";
    $result["ShowChildren"] = true;
    $result["iTotalRecords"] = $totalRegistros;
    $result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
    $result["aaData"] = $data;
    echo json_encode($result);
  }
  
  public function registrar_cita_tele_salud(Request $request)
  {
   
    $model_referencia = \App\Models\Referencia::find($request->id);
    $model_referencia->fecha_cita_teleinterconsulta = $request->fecha;
    $model_referencia->hora_cita_teleinterconsulta = $request->hora;
    $model_referencia->medico_tele_consulta = $request->medico;
    $model_referencia->consultorio_tele_consulta = $request->consultorio;
    $model_referencia->id_estado_teleinterconsulta = 2;
    $model_referencia->save();
  }

public function registrar_resultado_tele_salud(Request $request)
{
    try {
        $user = \Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        $model_referencia = \App\Models\Referencia::find($request->id);
        if (!$model_referencia) {
            return response()->json(['error' => 'Referencia no encontrada'], 404);
        }

        $date = Carbon::now();
        $model_referencia->comentario_teleinterconsulta = $request->comentario;
        $model_referencia->id_estado_teleinterconsulta = $request->procede;

        $comentarios = preg_split('/\r\n|\r|\n/', $request->comentariofinal);
        \Log::info('Comentarios parseados:', $comentarios);
        foreach ($comentarios as $comentario) {
            if (strpos($comentario, ':') !== false) {
                list($rawFlag, $descripcion) = explode(':', $comentario, 2);
                preg_match('/\d+/', $rawFlag, $matches);
                $flag = isset($matches[0]) ? intval($matches[0]) : null;
                $descripcion = trim($descripcion);
\Log::info('flag:', ['valor' => $flag]);
\Log::info('descripcion:', ['valor' => $descripcion]);
                if ($flag && $descripcion) {
                    $recomendacion = new \App\Models\RecomendaPlanTelesalud();
                    $recomendacion->descripcion = $descripcion;
                    $recomendacion->flag = $flag;
                    $recomendacion->orden = $flag;
                    $recomendacion->created_us = $user->id;
                    $recomendacion->created_at = $date;
                    $recomendacion->id_estado_reg = 1;
                    $recomendacion->id_referencia = $request->id;
                    $recomendacion->save();
                }
            }
        }

        $model_referencia->save();

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        \Log::error('Error en registrar_resultado_tele_salud', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
}
  
  public function vista_dirrehum_pacientes(Request $request)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    else:
      if(Auth::user()->actualizo_passw==0):
        return redirect('auth/password/change');
      endif;
    endif;
    if(Auth::user()->flagestablecimientos==1):
      return view('frontend.referencias.busqueda_dni');
    else:
      return redirect('/dashboard')->withFlashDanger("No tiene permiso para ver esta opción");
    endif;
  }

  public function dirrehum_asegurado($id_asegurado) {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $model_paciente = new \App\Models\Paciente;
    $model_prestacion = new \App\Models\Prestaciones;
    $paciente = $model_paciente->getById($id_asegurado);
    $atenciones = $model_prestacion->GetAtencionesByIdAsegurado($paciente->id_asegurado);
    $citas =  "SELECT 
                      ROW_NUMBER() OVER (ORDER BY c.fecha_hora DESC) AS numero,
                      e.nombre as nombre_establecimiento,
                      cc.nombre as nombre_consultorio ,
                      TO_CHAR(c.fecha_hora, 'YYYY-MM-DD HH24:MI') AS fecha_hora,
                      c.referido as referido_cita,es.descripcion as nombre_estado
                from citas c 
                inner join establecimientos e on c.id_establecimiento = e.id 
                inner join sub_consultorios sb on c.id_sub_consultorio = sb.id
                inner join consultorios cc on cc.id = sb.id_consultorio
                inner join estados es on es.id = c.id_estado_cita
                where c.id_estado_reg='1' and c.id_asegurado = $id_asegurado 
                order by c.fecha_hora  desc ";
    $citas = DB::select($citas);
   
    $referencias = "SELECT
                          ROW_NUMBER() OVER (ORDER BY r.fecha_atencion DESC) AS numero, e.nombre as nombre_establecimiento_destino,
                          cc.nombre as nombre_consultorio, ee.nombre as nombre_establecimiento_origen,m.nombre as nombre_medico,
                          m.paterno as paterno_medico,m.materno as materno_medico,
                          CASE r.id_estado_reg
                              WHEN 1 THEN 'Referencia generada por médico'
                              WHEN 2 THEN 'Referencia solicitada por área de referencias del establecimiento origen'
                              WHEN 4 THEN 'Validado por el área destino'
                              WHEN 3 THEN 'Invalidado por el área destino'
                              WHEN 5 THEN 'Cita generada'
                              WHEN 6 THEN 'Se generó la contrarreferencia'
                              WHEN 7 THEN 'Se realizó la contrarreferencia'
                              ELSE 'Estado desconocido'
                          END AS estado_referencia
                      from referencias r
                      inner join establecimientos e on r.id_establecimiento_des = e.id 
                      inner join establecimientos ee on r.id_establecimiento = ee.id 
                      inner join consultorios cc on cc.id = r.id_servicio
                      inner join medicos m on m.id = r.id_medico_referencia
                      where r.id_asegurado = $id_asegurado 
                      order by r.fecha_atencion  desc ";
    $referencias = DB::select($referencias);
  
    $id_usuario = Auth::user()->id;
    $accesos = DB::table('users')
    ->select('users.*')
    ->where('id',$id_usuario)
    ->first();
    return view('frontend.referencias.busqueda_dni_dirrehum', compact('paciente','atenciones','accesos','referencias','citas'));
  }
  public function procedimientos_examen_patologia_cqx()
  {
    $procedimientos = new Procedimiento;
    $dato = $procedimientos->procedimientosExamenPatologiaCqx();
    return json_encode($dato);
  }

    public function validar_solicitud_teleconsulta($id_prestacion,$id)
  {
    
    $date = new Carbon();
    $model_referencia = \App\Models\Referencia::find($id);
    $model_referencia->id_estado_teleinterconsulta = 1;
    $model_referencia->updated_at = $date;
    $model_referencia->updated_us = Auth::user()->id;
    $model_referencia->fecha_solicitud_tele = $date;
    $model_referencia->save();

     return redirect('/all_tele_referencia')->with('success', 'Solicitud validada correctamente');
  }

      public function obtener_upss_x_servicio($id)
  {
    
    $dato = "SELECT u.id,u.codigo,u.descripcion_upss FROM upsses u                            
                    where u.id_consultorio =  '$id' and u.estado = '1' ";
    $dato = DB::select($dato);

      return json_encode($dato);
  }

    public function resultado_validacion_referencia($id)
  {

$dato = DB::table('actualizacion_estados')
    ->join('users', 'users.id', '=', 'actualizacion_estados.created_us') 
    ->join('referencias', 'referencias.id', '=', 'actualizacion_estados.id_referencia')
    ->select(
        'referencias.flag_valida_ref',
        'referencias.recomendaciones',
        DB::raw("users.paterno || ' ' || users.materno || ' ' || users.name as usuario_nombre")
    )
    ->where('actualizacion_estados.id_referencia', $id)
    ->where('actualizacion_estados.flag_estado_reg', '1')
    ->first();

      return json_encode($dato);
  }
}
