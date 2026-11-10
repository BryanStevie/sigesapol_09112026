<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use SoapClient;
use Session;
use DB;
use App;
use Illuminate\Support\Facades\Route;
use Auth;
use Carbon\Carbon;
use App\Models\Farmacia;
use App\Models\Receta_vale;
use App\Models\Cita;
use App\Models\Prestaciones;
use App\Models\User;
use App\Models\Consultorio;
use App\Models\Beneficiario;
use App\Models\Emergencia;
use App\Models\Forma_ingreso;
use App\Models\Estado_ingreso;
use App\Models\Distrito;
use App\Models\Establecimiento;
use App\Models\Kardexe;
use App\Models\Categoria;
use App\Models\Violencia;
use App\Models\Comisaria;
use App\Models\Situacion;
use App\Models\Unidadpago;
use App\Models\Diagnostico;
use App\Models\Tipo_beneficiario;
use App\Models\Triaje;
use App\Models\TipoViolencia;
use App\Models\Medico_turno;
use App\Models\Medico;
use App\Models\Producto;
use App\Models\Evolucion;
use App\Models\SubConsultorio;
use App\Models\Asegurado;
use App\Models\Asegurado_historia;
use App\Models\Referencia;
use App\Models\Producto_receta;
use App\Models\TipoAtencione;
use App\Models\Hospitalizacione;


class EvolucionController extends Controller
{ 
  function __construct()
    {
        $this->middleware('auth'); 
    }
    
  public function registrar_evolucion_transaction(Request $request) {
    
    if (!Auth::check()):
        return redirect('/auth/login');
    endif;

    //=========================================================================== bloquea el registro de evoluciones hasta completar la Historia Clinica
    if ($request->id_tipo_atencion == 3 && !empty($request->id_principal)) {
      $hospitalizacion_evolucion = Hospitalizacione::find($request->id_principal);
      if ($hospitalizacion_evolucion && !$hospitalizacion_evolucion->historiaClinicaCompleta()) {
        return redirect('/form_atencion_prestacion_hspcu/' . Auth::user()->id . '/' . $request->id_principal)->withInput()->withErrors(array('Debe completar la Historia Clínica antes de registrar evoluciones..!'));
      }
    }

    $objEvolucion = new Evolucion();

    $temperatura                = $request->temperatura != '' ? $request->temperatura : 0;
    $sa02_porcentaje            = $request->saturacion != '' ? $request->saturacion : 0;
    $presion_arterial_x         = $request->presion_arterial_x != '' ? $request->presion_arterial_x : 0;
    $presion_arterial_y        = $request->presion_arterial_y != '' ? $request->presion_arterial_y : 0;
    $talla_cm                   = $request->talla != '' ? $request->talla : 0;
    $tamano_cintura_cm          = $request->tamanio_cintura != '' ? $request->tamanio_cintura : 0;
    $frecuencia_respiratoria    = $request->frecuencia_respiratoria != '' ? $request->frecuencia_respiratoria: 0;
    $peso_kg                    = $request->peso != '' ? $request->peso: 0;
    $frecuencia_cardiaca_fetal  = $request->frec_cardiaca_fetal != '' ? $request->frec_cardiaca_fetal : 0;
    $frecuencia_cardiaca        = $request->frec_cardiaca != '' ? $request->frec_cardiaca : 0;
    $fi02                       = $request->oxigenacion != '' ? $request->oxigenacion : 0;
    $id_devolucion              = 0;
    
    $p_diagnostico = "";
      $p_diagnostico .= "{";
      if (isset($request->id_diagnostico)) :
        foreach ($request->id_diagnostico as $key => $value) :
          if ($request->id_diagnostico[$key] != "") {
            $p_diagnostico .= "{";
            $p_diagnostico .= $request->id_diagnostico[$key] . ",";
            $p_diagnostico .= (isset($request->id_tipo_diagnostico[$key]) && $request->id_tipo_diagnostico[$key] > 0) ? $request->id_tipo_diagnostico[$key] : "0";
            $p_diagnostico .= "},";
          }
        endforeach;
        if (strlen($p_diagnostico) > 1) $p_diagnostico = substr($p_diagnostico, 0, -1);
      endif;
      $p_diagnostico .= "}";

      $p_procedimiento = "";
      $p_procedimiento .= "{";
      if (isset($request->id_procedimiento)) :
        foreach ($request->id_procedimiento as $key => $value) :
          if ($request->id_procedimiento[$key] != "") {
            $p_procedimiento .= "{";
            $p_procedimiento .= $request->id_procedimiento[$key] . ",";
            $p_procedimiento .= (isset($request->procedimiento_cantidad[$key]) && $request->procedimiento_cantidad[$key] > 0) ? $request->procedimiento_cantidad[$key] : "0";
            $p_procedimiento .= "},";
          }
        endforeach;
        if (strlen($p_procedimiento) > 1) $p_procedimiento = substr($p_procedimiento, 0, -1);
      endif;
      $p_procedimiento .= "}";

      $p_detalle = "";
      $p_detalle .= "{";
      if (isset($request->id_producto)) :
        foreach ($request->id_producto as $key => $value) :
          $p_detalle .= "{";
          $p_detalle .= $request->id_producto[$key] . ",";
          $p_detalle .= $request->tiempo_periodo[$key] . ",";
          $p_detalle .= $request->nro_med_solictados[$key] . ",";
          $p_detalle .= (isset($request->nro_med_entregados[$key]) && $request->nro_med_entregados[$key] != "") ? $request->nro_med_entregados[$key] : 0;
          $p_detalle .= ",";
          $p_detalle .= (isset($request->nro_stocks_almacen[$key]) && $request->nro_stocks_almacen[$key] != "") ? $request->nro_stocks_almacen[$key] : 0;
          $p_detalle .= ",";
          $p_detalle .= (isset($request->nro_stocks_establecimiento[$key]) && $request->nro_stocks_establecimiento[$key] != "") ? $request->nro_stocks_establecimiento[$key] : 0;
          $p_detalle .= ",";
          $p_detalle .= (isset($request->nro_stocks[$key]) && $request->nro_stocks[$key] != "") ? $request->nro_stocks[$key] : 0;
          $p_detalle .= ",";
          $p_detalle .= (isset($request->posologia[$key]) && $request->posologia[$key] != "") ? str_replace(",", "###", $request->posologia[$key]) : "-";
          $p_detalle .= "},";
        endforeach;
        $p_detalle = substr($p_detalle, 0, -1);
      endif;
      $p_detalle .= "}";

    $id_evolucion = $objEvolucion->RegistrarEvolucion($request->id_prestacion,$request->id_principal , $request->id_asegurado , $request->id_tipo_atencion , $request->fecha_atencion, $request->id_medico , $request->id_establecimiento , $temperatura , $sa02_porcentaje , $presion_arterial_x ,$presion_arterial_y , $talla_cm , $tamano_cintura_cm , $frecuencia_respiratoria , $peso_kg, $frecuencia_cardiaca_fetal , $frecuencia_cardiaca , $fi02 , $request->hora , $request->id_servicio , $request->subjetivo , $request->objetivo , $request->analisis , $request->tratamiento , $request->indicaciones, $request->id_cama, $p_procedimiento,  $p_diagnostico);

    if($id_evolucion==0){
      Session::flash('flash_message', 'No se pudo registrar la Evolucion, hubo un problema con la conexión de internet de su establecimiento PNP, vuelva a intentarlo nuevamente');
      return redirect('/all_pacientes_evoluciones_by_emergencia');
    }
    else
    {
      $model_emergencia = new \App\Models\Emergencia;
      $update=$model_emergencia->ActualizaEstadoEmergencia($request->id_principal,0);

      $id_prestacion = $request->id_prestacion;

      $model_prestaciones = new Prestaciones();
      $receta = new Receta_Vale();
      $diagnosticos = $model_prestaciones->GetDiagnosticoByIdPrestacion($id_prestacion);
      $p_diagnostico = "";
      $p_diagnostico .= "{";
      foreach ($diagnosticos as $row) :
        $p_diagnostico .= "{";
        $p_diagnostico .= $row->id_diagnostico . ",";
        $p_diagnostico .= ($row->id_tipo_diagnostico > 0) ? $row->id_tipo_diagnostico : "0";
        $p_diagnostico .= "},";
      endforeach;
      $p_diagnostico = substr($p_diagnostico, 0, -1);
      $p_diagnostico .= "}";

      $id_receta_vale = 0;
      $id_anestesiologo = 0;
      $obs_registro = "";
      $id_receta_vale_bus = 0;
      $id_admision = 0;
      $tipo_via = "";
      $sub_via = "";
      $final_via = "";
      $nro_guia = "";
      $interconsulta = "";
      $interconsulta_dias = 0;
      $nro_receta = "";
      $digitos_adicionales = "";
      $id_tipo_num = 0;
      $estado_receta = 2;
      $prestacion = Prestaciones::find($id_prestacion);
      $subconsultorio = SubConsultorio::find($prestacion->id_sub_consultorio);
      $cita = Cita::find($prestacion->id_cita);
      $asegurado = Asegurado::find($cita->id_asegurado);
      $titular = Asegurado::find($cita->id_titular);
      $historia = Asegurado_historia::find($cita->id_asegurado);
      
      $id_medico = $prestacion->id_medico;
      $id_tiporeceta = $prestacion->id_tipo_atencion;
      $fecha_atencion = $request->fecha_expedicion;
      $id_consultorio = $subconsultorio->id_consultorio;
      $grado = $cita->grado;
      $tipo_beneficiario = $cita->parentesco;

      $dni_beneficiario = $asegurado->nro_doc_ident;
      $cip_beneficiario = $asegurado->cip;
      $tipodoc_beneficiario = $asegurado->tipo_doc_ident;
      $nombre_beneficiario = $asegurado->nombre;
      $paterno_beneficiario = $asegurado->paterno;
      $materno_beneficiario = $asegurado->materno;

      $dni_titular = $titular->nro_doc_ident;
      $tipdoc_titular = $titular->tipo_doc_ident;
      $nombre_titular = $titular->nombre;
      $paterno_titular = $titular->paterno;
      $materno_titular = $titular->materno;

      $nro_historia = isset($historia->nro_historia) ? $historia->nro_historia : "";
      $hora = date('H');
      
      if ($hora > 6 && $hora < 13) $turno = "M";
      elseif ($hora >= 13 && $hora < 19) $turno = "T";
      elseif ($hora >= 19 && $hora <= 24) $turno = "N";
      else $turno = "MD";
      
      $model_procedimiento = new \App\Models\Procedimiento;
      //Laboratorio
      if (isset($request->id_examen_lab)) :
        $p_laboratorio = "";
        $p_laboratorio .= "{";
        foreach ($request->id_examen_lab as $key => $value) :
          if ($request->id_examen_lab[$key] != "") {
            $p_laboratorio .= "{";
            $p_laboratorio .= $request->id_examen_lab[$key] . ",";
            $p_laboratorio .= (isset($request->examen_lab_cantidad[$key]) && $request->examen_lab_cantidad[$key] > 0) ? $request->examen_lab_cantidad[$key] : "0";
            $p_laboratorio .= "},";
          }
        endforeach;
        if (strlen($p_laboratorio) > 1) $p_laboratorio = substr($p_laboratorio, 0, -1);
        $p_laboratorio .= "}";
        if($request->id_examen_lab[0]!=""):
          $id_procedimiento_lab = $model_procedimiento->RegistrarProcedimiento($request->id_user, $id_prestacion, $request->id_establecimiento, $request->fecha_atencion, $p_laboratorio, 2, $id_evolucion);
          $model_lab = new \App\Models\Laboratorio;
          $ok=$model_lab->ActualizaIdEvolucion($id_procedimiento_lab,$id_evolucion);
        endif;
      endif;
      //Imagenologia
      if (isset($request->id_examen_rad)) :
        $p_radiologia = "";
        $p_radiologia .= "{";
        foreach ($request->id_examen_rad as $key => $value) :
          if ($request->id_examen_rad[$key] != "") {
            $p_radiologia .= "{";
            $p_radiologia .= $request->id_examen_rad[$key] . ",";
            $p_radiologia .= (isset($request->examen_rad_cantidad[$key]) && $request->examen_rad_cantidad[$key] > 0) ? $request->examen_rad_cantidad[$key] : "0";
            $p_radiologia .= "},";
          }
        endforeach;
        if (strlen($p_radiologia) > 1) $p_radiologia = substr($p_radiologia, 0, -1);
        $p_radiologia .= "}";
        if($request->id_examen_rad[0]!=""):
          $id_procedimiento_lab = $model_procedimiento->RegistrarProcedimiento($request->id_user, $id_prestacion, $request->id_establecimiento, $request->fecha_atencion, $p_radiologia, 3, $id_evolucion);
          $model_rad = new \App\Models\Radiologia;
          $ok=$model_rad->ActualizaIdEvolucion($id_procedimiento_lab,$id_evolucion);
        endif;
      endif;
      
      $model_interconsulta = new \App\Models\Interconsulta;
      if (isset($request->id_servicio_interconsulta)) :
        $p_interconsulta = "";
        $p_interconsulta .= "{";
        foreach ($request->id_servicio_interconsulta as $key => $value) :
          if ($request->id_servicio_interconsulta[$key] != "") {
            $p_interconsulta .= "{";
            $p_interconsulta .= $request->id_servicio_interconsulta[$key] . ",";
            $p_interconsulta .= $request->nombre_servicio_interconsulta[$key] . ",";
            $p_interconsulta .= $request->motivo_interconsulta[$key] . ",";
            $p_interconsulta .= $request->resumen_interconsulta[$key] ;
            $p_interconsulta .= "},";
          }
        endforeach;
        if (strlen($p_interconsulta) > 1) $p_interconsulta = substr($p_interconsulta, 0, -1);
        $p_interconsulta .= "}"; 

        if($request->id_servicio_interconsulta[0]!=""):
          $id_interconsulta = $model_interconsulta->RegistrarInterconsulta($id_prestacion, $asegurado->id, $prestacion->id_tipo_atencion, $prestacion->fecha_atencion, $prestacion->id_medico, $prestacion->id_establecimiento, $p_interconsulta);
          $model_inter = new \App\Models\Interconsulta;
          $ok=$model_inter->ActualizaIdEvolucion($id_interconsulta,$id_evolucion);
        endif;
      endif;
    
      $model_referencia = new \App\Models\Referencia;

      if (isset($request->id_establecimiento_referencia)) :
        if($request->id_establecimiento_referencia!=""):
          if($request->id_uci==2){
            $id_uci_cv=0;
          }
          else{
            $id_uci_cv=$request->id_uci_cv;
          }
          if($request->id_ambulancia==2){
            $id_ambulancia_ref=0;$id_tipo_ambulancia=0;
          }
          else{
            $id_ambulancia_ref=$request->id_ambulancia_ref;$id_tipo_ambulancia=$request->id_tipo_ambulancia;
          }

          $id_referencia = $model_referencia->RegistrarReferencia($id_prestacion, $asegurado->id, $prestacion->id_tipo_atencion, $prestacion->fecha_atencion, $prestacion->id_medico, $request->id_establecimiento_referencia, $request->id_uci,$id_uci_cv,$request->id_ambulancia,$id_ambulancia_ref,$id_tipo_ambulancia,$request->id_pasaje,$request->id_acompanante,$request->id_situacion,$request->observacion_referencia,$request->id_establecimiento);
          $model_refer = new \App\Models\Referencia;
          $ok=$model_refer->ActualizaIdEvolucion($id_referencia,$id_evolucion);

          
          if($request->id_principal!=0):
            if($request->id_principal->id_tipo_atencion==2):
              $model_emergencia = new \App\Models\Emergencia;
              $update=$model_emergencia->ActualizaEstadoEmergencia($request->id_principal,4);
            else:
              $model_hospitalizacion = new \App\Models\Hospitalizacione;
              $update=$model_hospitalizacion->ActualizaEstadoHospitalizacion($request->id_principal,4);
             endif;
          endif;
        endif;
      endif;

      
      if (isset($request->id_producto)) :
        $id_receta_vale = $receta->postRegistrarRecetaVale($id_receta_vale, $request->id_farmacia_receta, $request->id_user, $id_consultorio, $nro_receta, $digitos_adicionales, $dni_beneficiario, $cip_beneficiario, $tipodoc_beneficiario, $nombre_beneficiario, $paterno_beneficiario, $materno_beneficiario, $tipo_beneficiario, $dni_titular, $tipdoc_titular, $nombre_titular, $paterno_titular, $materno_titular, $request->fecha_expedicion, $fecha_atencion, $nro_historia, $grado, $id_medico, $id_anestesiologo, $id_tiporeceta, $turno, $obs_registro, $id_receta_vale_bus, $id_admision, $tipo_via, $sub_via, $final_via, $nro_guia, $interconsulta, $interconsulta_dias, $id_prestacion, $id_tipo_num, $estado_receta, $p_detalle, $p_diagnostico);
          $model_rec_val = new \App\Models\Receta_vale;
          $ok=$model_rec_val->ActualizaIdEvolucion($id_receta_vale,$id_evolucion);
      else :
        $id_receta_vale = 1;
      endif;
      Session::flash('flash_message', 'Evolución exitosa');

      return redirect('/ver_evolucion/'.$request->id_user.'/'.$id_evolucion);
      
    }
  }

    public function all_pacientes_evoluciones_by_emergencia($id_user = 0)
    {
      $model_medico = new \App\Models\Medico();
      $model_usuario = new User;
      if (!Auth::check()) :
        return redirect('/auth/login');
      endif;

      $ruta = Route::getCurrentRoute()->getPath();
      if (count($ruta = explode("/", $ruta)) > 1);
      $ruta = $ruta[0];
      if ($ruta == "listar_triaje_emergencia_ajax");
      $ruta = "all_pacientes_evoluciones_by_emergencia";
      $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
      $permiso = $menu[0];
      if ($permiso->lectura == "")
        return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
      $id_user = Auth::user()->id;
      $usuario_actual = $model_usuario->GetById($id_user);
      $flagemergenciareferencia = $usuario_actual->flaghistoria;


      $date = Carbon::now();
      $fechaHasta = $date->format('d-m-Y');
      $fechaDesdex = $date->format('d-m-Y');
      $fechaDesdey = strtotime('-30 day', strtotime($fechaDesdex));
      $fechaDesde = date('d-m-Y', $fechaDesdey);

      //====================
      $model_farma = new Farmacia();
      $farmacia = $model_farma->GetFarmaByUser($id_user);
      $id_farmacia = $farmacia->id;
      $modo_validacion = $farmacia->modo_validacion;

      $dni_beneficiario = '';
      $nombre_beneficiario = '';
      $estado = 3;
      $opcion = 'evolucion';
      
      return view('frontend.admision.emergencia.all_pacientes_evoluciones_by_emergencia', compact('farmacia', 'fechaDesde', 'fechaHasta', 'dni_beneficiario', 'nombre_beneficiario', 'estado','opcion'));
    }

  public function formulario_evolucion_emergencia($id_emergencia)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $modelfarma = new \App\Models\Farmacia;
    $model_receta = new \App\Models\Receta_vale;
    $model_producto = new \App\Models\Producto;
    $model_productos_receta = new \App\Models\Producto_receta;
    $model_establecimiento = new \App\Models\Establecimiento;
    $model_prestacion = new \App\Models\Prestaciones;
    $model_laboratorio = new \App\Models\Laboratorio;
    $model_radiologia = new \App\Models\Radiologia;
    $model_interconsulta = new \App\Models\Interconsulta;
    $model_referencia = new \App\Models\Referencia;
    $model_emergencias = new  \App\Models\Emergencia;
    $model_kardex = new Kardexe();

    $model_medicos = new Medico;
    $medico = $model_medicos->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni,Auth::user()->id_establecimiento );
    
    //verifico si existe el id en emergencia
    $verificar_existe = Emergencia::where('id', $id_emergencia)->where('estado', 3)->first();
    
    if(is_object($verificar_existe)):
      $buscar_ultima_atencion = Prestaciones::where('id_ext', $id_emergencia)->where('id_estado_reg', 1)->orderBy('id', 'DESC')->first();

      //verifico si existe prestacion
      if(is_object($buscar_ultima_atencion)):
        $id_prestacion =  $buscar_ultima_atencion->id;
        $farmacia = $modelfarma->GetFarmaByUser(Auth::user()->id);
        $triaje = $model_emergencias->GetCargarEmergencia($id_emergencia, 3);
        $fechaServidor = $model_kardex->getFechaServidor();
        $date = Carbon::now();
        $fecha_actual = $date->format('d-m-Y');
        
        $id_ipress = $farmacia->id_establecimiento;
        $establecimiento = $model_establecimiento->GetById($id_ipress);
        if ($id_ipress == 1) {
          $nivel = 2;
        } else {
          $nivel = $establecimiento[0]->nivel;
        }
        
        $farmacias = $modelfarma->GetFarmaciaByIdEstablecimiento($id_ipress);
        $establecimientos = $model_establecimiento->ShowAllEstablecimientos();
        $estado=3;
        $id_tipo_atencion=2;
        $datos_evolucion = $model_emergencias->GetBuscaEmergencia($id_emergencia, $medico->id, 3);
        $id_principal = $id_emergencia;
        
        return view('frontend.evoluciones.form_evolucion', compact( 'id_prestacion', 'fechaServidor', 'nivel', 'farmacias','establecimientos','triaje','id_principal','estado','datos_evolucion','id_tipo_atencion'));

      else:
        return redirect('/formulario_atencion_emergencia/'.$id_emergencia)->withInput()->withErrors(array('Primero debe ingresar una atencion, para poder registrar su evolucion del paciente  ..!'));
      endif;
    else:
      return redirect('/all_pacientes_evoluciones_by_emergencia/')->withInput()->withErrors(array('Elemento no encontrado ..!'));
    endif; 
  }

  public function modal_historial_evoluciones($id_paciente, $estado, $id_tipo_atencion)
  {   $model_evoluciones = new \App\Models\Evolucion;
      if($id_tipo_atencion==2):
        $evoluciones = $model_evoluciones->GetEvolucionesByPacienteEmergencia($id_paciente, $estado);
      else:
        $evoluciones = $model_evoluciones->GetEvolucionesByPacienteHospitalizacion($id_paciente, $estado);
      endif;

      return view('frontend.evoluciones.modal_historial_evoluciones', compact('evoluciones'));
  }

  public function mostrar_evolucion($id_evolucion, $id_tipo_atencion)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $model_establecimiento = new \App\Models\Establecimiento;
    $model_kardex = new Kardexe();
    $model_evolucion = new Evolucion();
    $model_medico = new Medico();

    $fechaServidor = $model_kardex->getFechaServidor();
    $date = Carbon::now();
    $fecha_actual = $date->format('d-m-Y');
    
    $evolucion = $model_evolucion->GetEvolucion($id_evolucion);
    $medico = $model_medico->MostrarMedico($evolucion->id_medico);
    
    $establecimiento = $model_establecimiento->GetById(Auth::user()->id_establecimiento);

    if($id_tipo_atencion==2):
      $verificar = Emergencia::where('id', $evolucion->id_paciente)->where('estado', 3)->first();
    else:
      $verificar = Hospitalizacione::where('id', $evolucion->id_paciente)->where('estado', 3)->first();
    endif;
    
    if(is_object($verificar)):
      if($id_tipo_atencion==2):
        $model_emergencia = new Emergencia;
        $paciente = $model_emergencia->GetBuscaPacienteEmergencia( $evolucion->id_paciente, 3);
      else:
        $model_hospitalizacion = new Hospitalizacione;
        $paciente = $model_hospitalizacion->GetBuscaPacienteHospitalizacion( $evolucion->id_paciente, 3);
      endif;
    endif;

    return view('frontend.evoluciones.mostrar_evolucion', compact('evolucion', 'paciente','fechaServidor', 'nivel','medico'));
  }

  public function ver_evolucion($id_user,$id_evolucion)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $modelfarma = new Farmacia;
    $model_receta = new Receta_vale;
    $model_producto = new Producto;
    $model_productos_receta = new Producto_receta;
    $model_establecimiento = new \App\Models\Establecimiento;
    $model_prestacion = new \App\Models\Prestaciones;
    $model_laboratorio = new \App\Models\Laboratorio;
    $model_radiologia = new \App\Models\Radiologia;
    $model_interconsulta = new \App\Models\Interconsulta;
    $model_referencia = new \App\Models\Referencia;
    $model_evolucion = new \App\Models\Evolucion;
    $model_kardex = new Kardexe();

    $farmacia = $modelfarma->GetFarmaByUser($id_user);
    $id_farmacia = $farmacia->id;
    $flag_receta_dias = $farmacia->flag_receta_dias;

    $fechaServidor = $model_kardex->getFechaServidor();
    $date = Carbon::now();
    $fecha_actual = $date->format('d-m-Y');

    $evolucion = $model_evolucion->GetEvolucion($id_evolucion);

    $referencia = Referencia::where('id_evolucion', $id_evolucion)->where('id_estado_reg', 1)->first();

    if($evolucion->id_tipo_atencion==2):
      if(is_object($referencia)):
        $verificar = Emergencia::where('id', $evolucion->id_paciente)->where('estado', 4)->first();
        $estado = 4;

      else:
        $verificar = Emergencia::where('id', $evolucion->id_paciente)->where('estado', 3)->first();
        $estado = 3;  
      endif;

      $model_emergencias = new Emergencia;
      $paciente = $model_emergencias->GetBuscaPacienteEmergencia( $evolucion->id_paciente, $estado);
    else:
      if(is_object($referencia)):
        $verificar = Hospitalizacione::where('id', $evolucion->id_paciente)->where('estado', 4)->first();
        $estado = 4;

      else:
        $verificar = Hospitalizacione::where('id', $evolucion->id_paciente)->where('estado', 3)->first();
        $estado = 3;  
      endif;

      $model_emergencias = new Hospitalizacione;
      $paciente = $model_emergencias->GetBuscaPacienteHospitalizacion( $evolucion->id_paciente, $estado);
    endif;
    
      //verifico si existe idemergencia
      if(count($evolucion)>0):

          $id_prestacion =  $evolucion->id_prestacion;

          $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);

          $receta = $model_receta->GetDatosRecetasByIdEvolucion($id_evolucion);

          $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
          
          //$laboratorio = $model_prestacion->GetProcedimientosByIdEvolucion($id_prestacion, 2, $id_evolucion);
          $laboratorio = $model_prestacion->GetProcedimientosByIdPrestacionLabEvo($id_prestacion, $id_evolucion);
          $radiologia = $model_prestacion->GetProcedimientosByIdEvolucion($id_prestacion, 3, $id_evolucion);
          $pendiente_firma = $model_prestacion->GetDocumentosPendintesFirmarPrestacionId($id_prestacion);
          $flag_pendinte_firma = (count($pendiente_firma) > 0) ? 1 : 0;

          $id_ipress = $farmacia->id_establecimiento;
          $establecimiento = $model_establecimiento->GetById($id_ipress);
          if ($id_ipress == 1) {
            $nivel = 2;
          } else {
            $nivel = $establecimiento[0]->nivel;
          }

          if($prestacion->tipo_atencion=='EMERGENCIA'):
            $model_emergencia = new \App\Models\Emergencia;
            $obtenerdato = $model_emergencia->GetObtenerServicio($prestacion->id_ext);
            $prestacion->servicio= $obtenerdato->servicio;
            $prestacion->consultorio= $obtenerdato->consultorio;

            $producto = $model_producto->getProductosByNivelAndIdServicio($nivel, 76, $id_farmacia); //76 -> servicio de hopitalizacion 
          else:
            if($prestacion->id_tipo_atencion=3){
              $model_hospitalizacion = new \App\Models\Hospitalizacione;
              $obtenerdatoh = $model_hospitalizacion->GetObtenerDatos($prestacion->id_ext);
              $prestacion->servicio= $obtenerdatoh->servicio;
              $prestacion->consultorio= $obtenerdatoh->consultorio;
              $prestacion->pabellon= $obtenerdatoh->pabellon;
              $prestacion->piso= $obtenerdatoh->piso;
              $prestacion->cuarto= $obtenerdatoh->cuarto;
              $prestacion->cama= $obtenerdatoh->cama;
            
            }
            $producto = $model_producto->getProductosByNivelAndIdServicio($nivel, $prestacion->id_consultorio, $id_farmacia);
          endif;
          
          $farmacias = $modelfarma->GetFarmaciaByIdEstablecimiento($id_ipress);
          $establecimientos = $model_establecimiento->ShowAllEstablecimientos();
          $interconsultas = $model_interconsulta->GetInterconsultaByIdEvolucion($id_prestacion, $id_evolucion);
          
          $buscareferencia = $model_referencia->GetReferenciaByIdEvolucion($id_evolucion);
          if(is_object($buscareferencia)):
            $referencia = $model_referencia->GetReferenciaByIdPrestacionEvolucion($id_prestacion, $id_evolucion);
          else:
            $referencia = $buscareferencia;
          endif;

          $diagnostico = $model_evolucion->GetDiagnosticoByIdEvolucion($id_evolucion);
          $procedimiento = $model_evolucion->GetProcedimientosByIdEvolucion($id_evolucion, 1);

          return view('frontend.evoluciones.ver_evolucion', compact('farmacia', 'prestacion', 'diagnostico', 'procedimiento', 'medicamentos', 'id_prestacion', 'fechaServidor', 'receta', 'producto', 'farmacias', 'nivel', 'laboratorio', 'radiologia', 'flag_pendinte_firma','establecimientos','interconsultas','referencia','evolucion','paciente','id_evolucion','diagnostico','procedimiento','estado'));
      else:
         return redirect('/all_pacientes_evoluciones_by_emergencia/')->withInput()->withErrors(array('Evolucion no encontrado ..!'));
      endif;
      
  }

  public function registrar_evolucion_adicional(Request $request)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_prestacion = $request->id_prestacion;
    $id_evolucion = $request->id_evolucion;
      $p_detalle = "";
      $p_detalle .= "{";
      if (isset($request->id_producto)) :
        foreach ($request->id_producto as $key => $value) :
          $p_detalle .= "{";
          $p_detalle .= $request->id_producto[$key] . ",";
          $p_detalle .= $request->tiempo_periodo[$key] . ",";
          $p_detalle .= $request->nro_med_solictados[$key] . ",";
          $p_detalle .= (isset($request->nro_med_entregados[$key]) && $request->nro_med_entregados[$key] != "") ? $request->nro_med_entregados[$key] : 0;
          $p_detalle .= ",";
          $p_detalle .= (isset($request->nro_stocks_almacen[$key]) && $request->nro_stocks_almacen[$key] != "") ? $request->nro_stocks_almacen[$key] : 0;
          $p_detalle .= ",";
          $p_detalle .= (isset($request->nro_stocks_establecimiento[$key]) && $request->nro_stocks_establecimiento[$key] != "") ? $request->nro_stocks_establecimiento[$key] : 0;
          $p_detalle .= ",";
          $p_detalle .= (isset($request->nro_stocks[$key]) && $request->nro_stocks[$key] != "") ? $request->nro_stocks[$key] : 0;
          $p_detalle .= ",";
          $p_detalle .= (isset($request->posologia[$key]) && $request->posologia[$key] != "") ? str_replace(",", "###", $request->posologia[$key]) : "-";
          $p_detalle .= "},";
        endforeach;
        $p_detalle = substr($p_detalle, 0, -1);
      endif;
      $p_detalle .= "}";

      $model_prestaciones = new Prestaciones();
      $receta = new Receta_Vale();
      $diagnosticos = $model_prestaciones->GetDiagnosticoByIdPrestacion($id_prestacion);
      $p_diagnostico = "";
      $p_diagnostico .= "{";
      foreach ($diagnosticos as $row) :
        $p_diagnostico .= "{";
        $p_diagnostico .= $row->id_diagnostico . ",";
        $p_diagnostico .= ($row->id_tipo_diagnostico > 0) ? $row->id_tipo_diagnostico : "0";
        $p_diagnostico .= "},";
      endforeach;
      $p_diagnostico = substr($p_diagnostico, 0, -1);
      $p_diagnostico .= "}";

      $id_receta_vale = 0;
      $id_anestesiologo = 0;
      $obs_registro = "";
      $id_receta_vale_bus = 0;
      $id_admision = 0;
      $tipo_via = "";
      $sub_via = "";
      $final_via = "";
      $nro_guia = "";
      $interconsulta = "";
      $interconsulta_dias = 0;
      $nro_receta = "";
      $digitos_adicionales = "";
      $id_tipo_num = 0;
      $estado_receta = 2;
      $prestacion = Prestaciones::find($id_prestacion);
      $subconsultorio = SubConsultorio::find($prestacion->id_sub_consultorio);
      $cita = Cita::find($prestacion->id_cita);
      $asegurado = Asegurado::find($cita->id_asegurado);
      $titular = Asegurado::find($cita->id_titular);
      $historia = Asegurado_historia::find($cita->id_asegurado);
      
      $id_medico = $prestacion->id_medico;
      $id_tiporeceta = $prestacion->id_tipo_atencion;
      $fecha_atencion = $request->fecha_expedicion;
      $id_consultorio = $subconsultorio->id_consultorio;
      $grado = $cita->grado;
      $tipo_beneficiario = $cita->parentesco;

      $dni_beneficiario = $asegurado->nro_doc_ident;
      $cip_beneficiario = $asegurado->cip;
      $tipodoc_beneficiario = $asegurado->tipo_doc_ident;
      $nombre_beneficiario = $asegurado->nombre;
      $paterno_beneficiario = $asegurado->paterno;
      $materno_beneficiario = $asegurado->materno;

      $dni_titular = $titular->nro_doc_ident;
      $tipdoc_titular = $titular->tipo_doc_ident;
      $nombre_titular = $titular->nombre;
      $paterno_titular = $titular->paterno;
      $materno_titular = $titular->materno;

      $nro_historia = isset($historia->nro_historia) ? $historia->nro_historia : "";
      $hora = date('H');
      
      if ($hora > 6 && $hora < 13) $turno = "M";
      elseif ($hora >= 13 && $hora < 19) $turno = "T";
      elseif ($hora >= 19 && $hora <= 24) $turno = "N";
      else $turno = "MD";
      /*********************************************************************/
      $model_procedimiento = new \App\Models\Procedimiento;
      //Laboratorio
      if (isset($request->id_examen_lab)) :
        $p_laboratorio = "";
        $p_laboratorio .= "{";
        foreach ($request->id_examen_lab as $key => $value) :
          if ($request->id_examen_lab[$key] != "") {
            $p_laboratorio .= "{";
            $p_laboratorio .= $request->id_examen_lab[$key] . ",";
            $p_laboratorio .= (isset($request->examen_lab_cantidad[$key]) && $request->examen_lab_cantidad[$key] > 0) ? $request->examen_lab_cantidad[$key] : "0";
            $p_laboratorio .= "},";
          }
        endforeach;
        if (strlen($p_laboratorio) > 1) $p_laboratorio = substr($p_laboratorio, 0, -1);
        $p_laboratorio .= "}";
        if($request->id_examen_lab[0]!=""):
          $id_procedimiento_lab = $model_procedimiento->RegistrarProcedimiento($request->id_user, $id_prestacion, $request->id_establecimiento, $request->fecha_atencion, $p_laboratorio, 2, $id_evolucion);
          $model_lab = new \App\Models\Laboratorio;
          $ok=$model_lab->ActualizaIdEvolucion($id_procedimiento_lab,$id_evolucion);
        endif;
      endif;
      //Imagenologia
      if (isset($request->id_examen_rad)) :
        $p_radiologia = "";
        $p_radiologia .= "{";
        foreach ($request->id_examen_rad as $key => $value) :
          if ($request->id_examen_rad[$key] != "") {
            $p_radiologia .= "{";
            $p_radiologia .= $request->id_examen_rad[$key] . ",";
            $p_radiologia .= (isset($request->examen_rad_cantidad[$key]) && $request->examen_rad_cantidad[$key] > 0) ? $request->examen_rad_cantidad[$key] : "0";
            $p_radiologia .= "},";
          }
        endforeach;
        if (strlen($p_radiologia) > 1) $p_radiologia = substr($p_radiologia, 0, -1);
        $p_radiologia .= "}";
        if($request->id_examen_rad[0]!=""):
          $id_procedimiento_lab = $model_procedimiento->RegistrarProcedimiento($request->id_user, $id_prestacion, $request->id_establecimiento, $request->fecha_atencion, $p_radiologia, 3, $id_evolucion);
          $model_rad = new \App\Models\Radiologia;
          $ok=$model_rad->ActualizaIdEvolucion($id_procedimiento_lab,$id_evolucion);
        endif;
      endif;
      /***************************************************************************/
      $model_interconsulta = new \App\Models\Interconsulta;
      if (isset($request->id_servicio_interconsulta)) :
        $p_interconsulta = "";
        $p_interconsulta .= "{";
        foreach ($request->id_servicio_interconsulta as $key => $value) :
          if ($request->id_servicio_interconsulta[$key] != "") {
            $p_interconsulta .= "{";
            $p_interconsulta .= $request->id_servicio_interconsulta[$key] . ",";
            $p_interconsulta .= $request->nombre_servicio_interconsulta[$key] . ",";
            $p_interconsulta .= $request->motivo_interconsulta[$key] . ",";
            $p_interconsulta .= $request->resumen_interconsulta[$key] ;
            $p_interconsulta .= "},";
          }
        endforeach;
        if (strlen($p_interconsulta) > 1) $p_interconsulta = substr($p_interconsulta, 0, -1);
        $p_interconsulta .= "}"; 

        if($request->id_servicio_interconsulta[0]!=""):
          $id_interconsulta = $model_interconsulta->RegistrarInterconsulta($id_prestacion, $asegurado->id, $prestacion->id_tipo_atencion, $prestacion->fecha_atencion, $prestacion->id_medico, $prestacion->id_establecimiento, $p_interconsulta);
          $model_inter = new \App\Models\Interconsulta;
          $ok=$model_inter->ActualizaIdEvolucion($id_interconsulta,$id_evolucion);
        endif;
      endif;
    
      $model_referencia = new \App\Models\Referencia;

      if (isset($request->id_establecimiento_referencia)) :
        if($request->id_establecimiento_referencia!=""):
          if($request->id_uci==2){
            $id_uci_cv=0;
          }
          else{
            $id_uci_cv=$request->id_uci_cv;
          }
          if($request->id_ambulancia==2){
            $id_ambulancia_ref=0;$id_tipo_ambulancia=0;
          }
          else{
            $id_ambulancia_ref=$request->id_ambulancia_ref;$id_tipo_ambulancia=$request->id_tipo_ambulancia;
          }

          $id_referencia = $model_referencia->RegistrarReferencia($id_prestacion, $asegurado->id, $prestacion->id_tipo_atencion, $prestacion->fecha_atencion, $prestacion->id_medico, $request->id_establecimiento_referencia, $request->id_uci,$id_uci_cv,$request->id_ambulancia,$id_ambulancia_ref,$id_tipo_ambulancia,$request->id_pasaje,$request->id_acompanante,$request->id_situacion,$request->observacion_referencia,$request->id_establecimiento);
          $model_refer = new \App\Models\Referencia;
          $ok=$model_refer->ActualizaIdEvolucion($id_referencia,$id_evolucion);

          if($request->id_paciente!=0):
            $model_emergencia = new \App\Models\Emergencia;
            $update=$model_emergencia->ActualizaEstadoEmergencia($request->id_paciente,4);
          endif;

        endif;
      endif;

      /***************************************************************************/   
      if (isset($request->id_producto)) :
        $id_receta_vale = $receta->postRegistrarRecetaVale($id_receta_vale, $request->id_farmacia_receta, $request->id_user, $id_consultorio, $nro_receta, $digitos_adicionales, $dni_beneficiario, $cip_beneficiario, $tipodoc_beneficiario, $nombre_beneficiario, $paterno_beneficiario, $materno_beneficiario, $tipo_beneficiario, $dni_titular, $tipdoc_titular, $nombre_titular, $paterno_titular, $materno_titular, $request->fecha_expedicion, $fecha_atencion, $nro_historia, $grado, $id_medico, $id_anestesiologo, $id_tiporeceta, $turno, $obs_registro, $id_receta_vale_bus, $id_admision, $tipo_via, $sub_via, $final_via, $nro_guia, $interconsulta, $interconsulta_dias, $id_prestacion, $id_tipo_num, $estado_receta, $p_detalle, $p_diagnostico);
          $model_rec_val = new \App\Models\Receta_vale;
          $ok=$model_rec_val->ActualizaIdEvolucion($id_receta_vale,$id_evolucion);
      else :
        $id_receta_vale = 1;
      endif;

      

      return redirect('/ver_evolucion/'.$request->id_user.'/'.$id_evolucion);
  }

  public function ver_atenciones_evolucion($id_paciente, $estado,$id_tipo_atencion)
    {

        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $date = Carbon::now();
        $fechaHasta = $date->format('d-m-Y');
        $fechaDesdex = $date->format('d-m-Y');
        $fechaDesdey = strtotime('-30 day', strtotime($fechaDesdex));
        $fechaDesde = date('d-m-Y', $fechaDesdey);
//        $model_evolucion = new Evolucion;

//        $evolucion = $model_evolucion->GetEvolucion($id_paciente);

        if($id_tipo_atencion==2){
          $verificar = Emergencia::where('id', $id_paciente)->where('estado', $estado)->first();
        }
        else{
          $verificar = Hospitalizacione::where('id', $id_paciente)->where('estado', $estado)->first(); 
        }

        $model_consultorio = new Consultorio;
    
        if(is_object($verificar)):
          if($id_tipo_atencion==2){
            $model_emergencias = new Emergencia;
            $paciente = $model_emergencias->GetBuscaPacienteEmergencia($id_paciente, $estado);
          }
          else{
            $model_hospitalizacion = new Hospitalizacione;
            $paciente = $model_hospitalizacion->GetBuscaPacienteHospitalizacion($id_paciente, $estado);
          }
          $id_user = Auth::user()->id;
          //$usuario_actual = $model_usuario->GetById($id_user);
          $flagemergenciareferencia = Auth::user()->flaghistoria;
          $id_consultorio_actual = Auth::user()->id_consultorio;
          $dni_beneficiario = "";
          $nombre_beneficiario = "";
          $estado = "1";
          $id_atencione = $id_tipo_atencion;

          //====================
          $model_farma = new Farmacia();
          $farmacia = $model_farma->GetFarmaByUser($id_user);
          $id_farmacia = $farmacia->id;
          $id_ipress = $farmacia->id_establecimiento;
          $modo_validacion = $farmacia->modo_validacion;

          $model_consultorio = new Consultorio;
          $consultorio = $model_consultorio->ShowAllConsultoriosSubConsultorio($farmacia->id_establecimiento);

          $medico = 0;

          $model_atencione = new TipoAtencione;
          $atencione = $model_atencione->ShowTipoAtencionAll();
          
          $model_establecimiento = new \App\Models\Establecimiento;
          $establecimientos = $model_establecimiento->GetById($id_ipress);
          if(Auth::user()->tipo_registro==1){
            $model_medico = new Medico();
            $medico_actual = $model_medico->GetMedicoByDniAndEstablecimiento(Auth::user()->dni,$farmacia->id_establecimiento);
            $medico = $medico_actual->id;
          }

          return view('frontend.evoluciones.all_evoluciones_atenciones', compact('farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nombre_beneficiario', 'estado','flagemergenciareferencia','consultorio','medico','id_consultorio_actual','atencione','id_atencione','paciente','id_paciente','establecimientos'));
        else:
          if($estado==2):
            return redirect('/dashboard')->withInput()->withErrors(array('Elemento no encontrado ..!'));
          else:
            return redirect('/dashboard')->withInput()->withErrors(array('Elemento no encontrado ..!'));
          endif;
        endif;
    }

    public function listar_evolucion_ajax(Request $request){
    
    $model_evolucion = new Evolucion();
    $p[]=$request->fecha_ini;
    $p[]=$request->fecha_fin;
    $p[]=$request->dni_beneficiario;
    $p[]=(isset($request->id_servicio) && $request->id_servicio!="")?$request->id_servicio:0;
    $p[]=$request->id_paciente;
    $p[]=$request->id_medico;
    $p[]=$request->estado;
    $p[]=$request->id_atencione;
    $p[]=Auth::user()->id_establecimiento;
    $p[]=$request->NumeroPagina;
    $p[]=$request->NumeroRegistros;
    $data = $model_evolucion->listar_evolucion_ajax($p);
    $iTotalDisplayRecords = isset($data[0]->totalrows)?$data[0]->totalrows:0;
    $result["PageStart"] = $request->NumeroPagina;
    $result["pageSize"] = $request->NumeroRegistros;
    $result["SearchText"] = "";
    $result["ShowChildren"] = true;
    $result["iTotalRecords"] = $iTotalDisplayRecords;
    $result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
    $result["aaData"] = $data;
    echo json_encode($result);
    
  }

  public function listar_evolucion_enfermeria_ajax(Request $request){
    $model_evolucion = new Evolucion();
    $p[]=$request->fecha_ini;
    $p[]=$request->fecha_fin;
    $p[]=$request->dni_beneficiario;
    $p[]=$request->id_paciente;
    $p[]=$request->id_enfermeria;
    $p[]=$request->id_atencione;
    $p[]=Auth::user()->id_establecimiento;
    $p[]=$request->NumeroPagina;
    $p[]=$request->NumeroRegistros;
    $data = $model_evolucion->listar_evolucion_ajax_enfermeria($p);
    $iTotalDisplayRecords = isset($data[0]->totalrows)?$data[0]->totalrows:0;
    $result["PageStart"] = $request->NumeroPagina;
    $result["pageSize"] = $request->NumeroRegistros;
    $result["SearchText"] = "";
    $result["ShowChildren"] = true;
    $result["iTotalRecords"] = $iTotalDisplayRecords;
    $result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
    $result["aaData"] = $data;
    echo json_encode($result);
    
  }


  public function historial_atenciones_evolucion($id_paciente, $estado)
    {
      
      $model_emergencias = new Emergencia;
      $paciente = $model_emergencias->GetBuscaPacienteEmergencia( $id_paciente, $estado);
      $model_evoluciones = new \App\Models\Evolucion;
      $evoluciones = $model_evoluciones->GetEvolucionesByPaciente($id_paciente, $estado);
      return view('frontend.evoluciones.historial_atenciones_evolucion', compact('evoluciones','paciente'));
    }

  public function modal_atencion_paciente($id_ext){
    $model_emergencia = new \App\Models\Emergencia;
    $model_prestacion = new \App\Models\Prestaciones;
    $triaje_inicial = $model_emergencia->GetEmergenciaById($id_ext);
    $prestacion = $model_prestacion->GetPrestacionByIdPrestacionExt($id_ext);
    return view('frontend.epicrisis.modal_atencion',compact('prestacion','triaje_inicial'));
  }

  public function modal_evoluciones_paciente($id_ext){
    $model_evolucion = new \App\Models\Evolucion;
    $evoluciones = $model_evolucion->GetEvolucionesByIdExt($id_ext);
    return view('frontend.epicrisis.modal_evoluciones',compact('evoluciones'));
  }

  public function formulario_evolucion_hospitalizacion($id_hospitalizacion)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $modelfarma = new \App\Models\Farmacia;
    $model_receta = new \App\Models\Receta_vale;
    $model_producto = new \App\Models\Producto;
    $model_productos_receta = new \App\Models\Producto_receta;
    $model_establecimiento = new \App\Models\Establecimiento;
    $model_prestacion = new \App\Models\Prestaciones;
    $model_laboratorio = new \App\Models\Laboratorio;
    $model_radiologia = new \App\Models\Radiologia;
    $model_interconsulta = new \App\Models\Interconsulta;
    $model_referencia = new \App\Models\Referencia;
    $model_hospitalizacion = new  \App\Models\Hospitalizacione;
    $model_kardex = new Kardexe();

    $model_medicos = new Medico;
    $medico = $model_medicos->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni,Auth::user()->id_establecimiento );
    
    //verifico si existe el id en hospitalizacion
    $verificar_existe = Hospitalizacione::where('id', $id_hospitalizacion)->where('estado', 3)->first();
    //=========================================================================== FLUJO DE 2 VISTAS UCI: bloquea evoluciones hasta completar la Vista 2 (Historia Clinica)
    if (is_object($verificar_existe) && !$verificar_existe->historiaClinicaCompleta()):
      return redirect('/form_atencion_prestacion_hspcu/' . Auth::user()->id . '/' . $id_hospitalizacion)->withInput()->withErrors(array('Debe completar la Historia Clínica antes de registrar evoluciones..!'));
    endif;
    
    if(is_object($verificar_existe)):
      $buscar_ultima_atencion = Prestaciones::where('id_ext', $id_hospitalizacion)->where('id_estado_reg', 1)->orderBy('id', 'DESC')->first();

      //verifico si existe prestacion
      if(is_object($buscar_ultima_atencion)):
        $id_prestacion =  $buscar_ultima_atencion->id;
        $farmacia = $modelfarma->GetFarmaByUser(Auth::user()->id);
        $fechaServidor = $model_kardex->getFechaServidor();
        $date = Carbon::now();
        $fecha_actual = $date->format('d-m-Y');
        
        $id_ipress = $farmacia->id_establecimiento;
        $establecimiento = $model_establecimiento->GetById($id_ipress);
        if ($id_ipress == 1) {
          $nivel = 2;
        } else {
          $nivel = $establecimiento[0]->nivel;
        }
        
        $farmacias = $modelfarma->GetFarmaciaByIdEstablecimiento($id_ipress);
        $establecimientos = $model_establecimiento->ShowAllEstablecimientos();
        $estado=3;
        $id_tipo_atencion=3;
        $datos_evolucion = $model_hospitalizacion->GetBuscaHospitalizado($id_hospitalizacion, $medico->id, 3);
        $id_principal = $id_hospitalizacion;

        return view('frontend.evoluciones.form_evolucion', compact( 'id_prestacion', 'fechaServidor', 'nivel', 'farmacias','establecimientos','id_principal','estado','datos_evolucion','id_tipo_atencion'));

      else:
        return redirect('/form_atenciones_hospitalizacion/'.$id_hospitalizacion)->withInput()->withErrors(array('Primero debe ingresar una atencion, para poder registrar su evolucion del paciente  ..!'));
      endif;
    else:
      return redirect('/hospitalizado_by_evolucion/')->withInput()->withErrors(array('Elemento no encontrado ..!'));
    endif; 
  }
public function all_evolucion_enfermeria() {

            if (!Auth::check()) :
        return redirect('/auth/login');
      endif;

      $ruta = Route::getCurrentRoute()->getPath();
      if (count($ruta = explode("/", $ruta)) > 1);
      $ruta = $ruta[0];
      $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
      $permiso = $menu[0];
      if ($permiso->lectura == "")
        return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

      $id_user = Auth::user()->id;
      $id_user_consultorio = Auth::user()->id_consultorio;
      $model_farma = new Farmacia();
      $farmacia = $model_farma->GetFarmaByUser($id_user);
      $id_farmacia = $farmacia->id;
      $id_establecimiento = $farmacia->id_establecimiento;
      $establecimiento = $model_farma->GetIpressByIduser($id_user);
  
      
      //==================== 
      if ($id_user_consultorio != 17) {
        $model_hospitalizacione = new Hospitalizacione();
        $hospitalizado = $model_hospitalizacione->GetListaHospitalizado($id_establecimiento,3);
      } else {
          $model_hospitalizacione = new Hospitalizacione();
        $hospitalizado = $model_hospitalizacione->GetListaHospitalizadoDial($id_establecimiento,3);
        }
        
        //====================
      return view('frontend.hospitalizacion.all_evolucion_enfermeria', compact('hospitalizado', 'farmacia', 'id_farmacia', 'id_user_consultorio', 'id_user','establecimiento'));      
  }

  public function formulario_evolucion_enfermeria_hospitalizacion($id_hospitalizacion)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $modelfarma = new \App\Models\Farmacia;
    $model_establecimiento = new \App\Models\Establecimiento;
    $model_prestacion = new \App\Models\Prestaciones;
    $model_hospitalizacion = new  \App\Models\Hospitalizacione;
    $model_kardex = new Kardexe();

    $model_medicos = new Medico;
    $medico = $model_medicos->GetDatosMedicoByDniAndEstablecimiento(Auth::user()->dni,Auth::user()->id_establecimiento );
    
    //verifico si existe el id en hospitalizacion
    $verificar_existe = Hospitalizacione::where('id', $id_hospitalizacion)->where('estado', 3)->first();

    //=========================================================================== bloquea evoluciones hasta completar la Historia Clinica
    if (is_object($verificar_existe) && !$verificar_existe->historiaClinicaCompleta()):
      return redirect('/form_atencion_prestacion_hspcu/' . Auth::user()->id . '/' . $id_hospitalizacion)->withInput()->withErrors(array('Debe completar la Historia Clínica antes de registrar evoluciones..!'));
    endif;

    if(is_object($verificar_existe)):
      $buscar_ultima_atencion = Prestaciones::where('id_ext', $id_hospitalizacion)->where('id_estado_reg', 1)->orderBy('id', 'DESC')->first();

      //verifico si existe prestacion
      if(is_object($buscar_ultima_atencion)):
        $id_prestacion =  $buscar_ultima_atencion->id;
        $farmacia = $modelfarma->GetFarmaByUser(Auth::user()->id);
        $fechaServidor = $model_kardex->getFechaServidor();
        $date = Carbon::now();
        $fecha_actual = $date->format('d-m-Y');
        
        $id_ipress = $farmacia->id_establecimiento;
        $establecimiento = $model_establecimiento->GetById($id_ipress);
        if ($id_ipress == 1) {
          $nivel = 2;
        } else {
          $nivel = $establecimiento[0]->nivel;
        }
        
        $farmacias = $modelfarma->GetFarmaciaByIdEstablecimiento($id_ipress);
        $establecimientos = $model_establecimiento->ShowAllEstablecimientos();
        $estado=3;
        $id_tipo_atencion=3;
        $datos_evolucion = $model_hospitalizacion->GetBuscaHospitalizado($id_hospitalizacion, $medico->id, 3);
        $id_principal = $id_hospitalizacion;

        return view('frontend.evoluciones.form_evolucion_enfermeria', compact( 'id_prestacion', 'fechaServidor', 'nivel', 'farmacias','establecimientos','id_principal','estado','datos_evolucion','id_tipo_atencion'));

      else:
        return redirect('/form_atenciones_hospitalizacion/'.$id_hospitalizacion)->withInput()->withErrors(array('Primero debe ingresar una atencion, para poder registrar su evolucion del paciente  ..!'));
      endif;
    else:
      return redirect('/hospitalizado_by_evolucion/')->withInput()->withErrors(array('Elemento no encontrado ..!'));
    endif; 
  }


  public function registrar_evolucion_enfermeria(Request $request) {
    
    if (!Auth::check()):
        return redirect('/auth/login');
    endif;

    //=========================================================================== bloquea el registro de evoluciones hasta completar la Historia Clinica
    if ($request->id_tipo_atencion == 3 && !empty($request->id_principal)) {
      $hospitalizacion_evolucion = Hospitalizacione::find($request->id_principal);
      if ($hospitalizacion_evolucion && !$hospitalizacion_evolucion->historiaClinicaCompleta()) {
        return redirect('/form_atencion_prestacion_hspcu/' . Auth::user()->id . '/' . $request->id_principal)->withInput()->withErrors(array('Debe completar la Historia Clínica antes de registrar evoluciones..!'));
      }
    }

    $objEvolucion = new Evolucion();

    $temperatura                = $request->temperatura != '' ? $request->temperatura : 0;
    $sa02_porcentaje            = $request->saturacion != '' ? $request->saturacion : 0;
    $presion_arterial_x         = $request->presion_arterial_x != '' ? $request->presion_arterial_x : 0;
    $presion_arterial_y        = $request->presion_arterial_y != '' ? $request->presion_arterial_y : 0;
    $talla_cm                   = $request->talla != '' ? $request->talla : 0;
    $tamano_cintura_cm          = $request->tamanio_cintura != '' ? $request->tamanio_cintura : 0;
    $frecuencia_respiratoria    = $request->frecuencia_respiratoria != '' ? $request->frecuencia_respiratoria: 0;
    $peso_kg                    = $request->peso != '' ? $request->peso: 0;
    $frecuencia_cardiaca_fetal  = $request->frec_cardiaca_fetal != '' ? $request->frec_cardiaca_fetal : 0;
    $frecuencia_cardiaca        = $request->frec_cardiaca != '' ? $request->frec_cardiaca : 0;
    $fi02                       = $request->oxigenacion != '' ? $request->oxigenacion : 0;
    $id_devolucion              = 0;
    
    $p_diagnostico = "";
      $p_diagnostico .= "{";
      if (isset($request->id_diagnostico)) :
        foreach ($request->id_diagnostico as $key => $value) :
          if ($request->id_diagnostico[$key] != "") {
            $p_diagnostico .= "{";
            $p_diagnostico .= $request->id_diagnostico[$key] . ",";
            $p_diagnostico .= (isset($request->id_tipo_diagnostico[$key]) && $request->id_tipo_diagnostico[$key] > 0) ? $request->id_tipo_diagnostico[$key] : "0";
            $p_diagnostico .= "},";
          }
        endforeach;
        if (strlen($p_diagnostico) > 1) $p_diagnostico = substr($p_diagnostico, 0, -1);
      endif;
      $p_diagnostico .= "}";

      $p_procedimiento = "";
      $p_procedimiento .= "{";
      if (isset($request->id_procedimiento)) :
        foreach ($request->id_procedimiento as $key => $value) :
          if ($request->id_procedimiento[$key] != "") {
            $p_procedimiento .= "{";
            $p_procedimiento .= $request->id_procedimiento[$key] . ",";
            $p_procedimiento .= (isset($request->procedimiento_cantidad[$key]) && $request->procedimiento_cantidad[$key] > 0) ? $request->procedimiento_cantidad[$key] : "0";
            $p_procedimiento .= "},";
          }
        endforeach;
        if (strlen($p_procedimiento) > 1) $p_procedimiento = substr($p_procedimiento, 0, -1);
      endif;
      $p_procedimiento .= "}";


    $id_evolucion = $objEvolucion->RegistrarEvolucionEnfermeria($request->id_prestacion,$request->id_principal , $request->id_asegurado , $request->id_tipo_atencion , $request->fecha_atencion, $request->id_medico , $request->id_establecimiento , $temperatura , $sa02_porcentaje , $presion_arterial_x ,$presion_arterial_y , $talla_cm , $tamano_cintura_cm , $frecuencia_respiratoria , $peso_kg, $frecuencia_cardiaca_fetal , $frecuencia_cardiaca , $fi02 , $request->hora , $request->id_servicio , $request->sintomas, $p_procedimiento,  $p_diagnostico);

    if($id_evolucion==0){
      Session::flash('flash_message', 'No se pudo registrar la Evolucion, hubo un problema con la conexión de internet de su establecimiento PNP, vuelva a intentarlo nuevamente');
      return redirect('/all_pacientes_evoluciones_by_emergencia');
    }
    else
    {
      Session::flash('flash_message', 'Evolución exitosa');
      return redirect('/ver_evolucion_enfermeria/'.$request->id_user.'/'.$id_evolucion);
      
    }
  }

  public function ver_evolucion_enfermeria($id_user,$id_evolucion)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $modelfarma = new Farmacia;
    $model_establecimiento = new \App\Models\Establecimiento;
    $model_prestacion = new \App\Models\Prestaciones;
    $model_evolucion = new \App\Models\Evolucion;
    $model_kardex = new Kardexe();

    $farmacia = $modelfarma->GetFarmaByUser($id_user);
    $id_farmacia = $farmacia->id;
    $flag_receta_dias = $farmacia->flag_receta_dias;

    $fechaServidor = $model_kardex->getFechaServidor();
    $date = Carbon::now();
    $fecha_actual = $date->format('d-m-Y');

    $evolucion = $model_evolucion->GetEvolucionEnfermeria($id_evolucion);
    
    $model_emergencias = new Hospitalizacione;
    $paciente = $model_emergencias->GetBuscaPacienteHospitalizacion( $evolucion->id_paciente, 3);
    
      //verifico si existe idemergencia
      if(count($evolucion)>0):

          $id_prestacion =  $evolucion->id_prestacion;
          $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
          $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
          
          $pendiente_firma = $model_prestacion->GetDocumentosPendintesFirmarPrestacionId($id_prestacion);
          $flag_pendinte_firma = (count($pendiente_firma) > 0) ? 1 : 0;

          $id_ipress = $farmacia->id_establecimiento;
          $establecimiento = $model_establecimiento->GetById($id_ipress);
          if ($id_ipress == 1) {
            $nivel = 2;
          } else {
            $nivel = $establecimiento[0]->nivel;
          }

          if($evolucion->id_tipo_atencion=3){
            $model_hospitalizacion = new \App\Models\Hospitalizacione;
            $obtenerdatoh = $model_hospitalizacion->GetObtenerDatos($prestacion->id_ext);
            $prestacion->servicio= $obtenerdatoh->servicio;
            $prestacion->consultorio= $obtenerdatoh->consultorio;
            $prestacion->pabellon= $obtenerdatoh->pabellon;
            $prestacion->piso= $obtenerdatoh->piso;
            $prestacion->cuarto= $obtenerdatoh->cuarto;
            $prestacion->cama= $obtenerdatoh->cama;
          
          }
          
          $farmacias = $modelfarma->GetFarmaciaByIdEstablecimiento($id_ipress);
          $establecimientos = $model_establecimiento->ShowAllEstablecimientos();
          
          $diagnostico = $model_evolucion->GetDiagnosticoByIdEvolucion($id_evolucion);
          $procedimiento = $model_evolucion->GetProcedimientosByIdEvolucion($id_evolucion, 1);

          return view('frontend.evoluciones.ver_evolucion_enfermeria', compact('farmacia', 'prestacion', 'diagnostico', 'procedimiento', 'medicamentos', 'id_prestacion', 'fechaServidor', 'receta', 'producto', 'farmacias', 'nivel', 'laboratorio', 'radiologia', 'flag_pendinte_firma','establecimientos','evolucion','paciente','id_evolucion','diagnostico','procedimiento'));
      else:
         return redirect('/all_pacientes_evoluciones_by_emergencia/')->withInput()->withErrors(array('Evolucion no encontrado ..!'));
      endif;
      
  }

  public function ver_atenciones_evolucion_enfermeria($id_paciente, $estado,$id_tipo_atencion)
    {

        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $date = Carbon::now();
        $fechaHasta = $date->format('d-m-Y');
        $fechaDesdex = $date->format('d-m-Y');
        $fechaDesdey = strtotime('-30 day', strtotime($fechaDesdex));
        $fechaDesde = date('d-m-Y', $fechaDesdey);

        if($id_tipo_atencion==2){
          $verificar = Emergencia::where('id', $id_paciente)->where('estado', $estado)->first();
        }
        else{
          $verificar = Hospitalizacione::where('id', $id_paciente)->where('estado', $estado)->first(); 
        }

        $model_consultorio = new Consultorio;
    
        if(is_object($verificar)):
          if($id_tipo_atencion==2){
            $model_emergencias = new Emergencia;
            $paciente = $model_emergencias->GetBuscaPacienteEmergencia($id_paciente, $estado);
          }
          else{
            $model_hospitalizacion = new Hospitalizacione;
            $paciente = $model_hospitalizacion->GetBuscaPacienteHospitalizacion($id_paciente, $estado);
          }
          $id_user = Auth::user()->id;
          //$usuario_actual = $model_usuario->GetById($id_user);
          $flagemergenciareferencia = Auth::user()->flaghistoria;
          $id_consultorio_actual = Auth::user()->id_consultorio;
          $dni_beneficiario = "";
          $nombre_beneficiario = "";
          $estado = "1";
          $id_atencione = $id_tipo_atencion;

          //====================
          $model_farma = new Farmacia();
          $farmacia = $model_farma->GetFarmaByUser($id_user);
          $id_farmacia = $farmacia->id;
          $id_ipress = $farmacia->id_establecimiento;
          $modo_validacion = $farmacia->modo_validacion;

          $model_consultorio = new Consultorio;
          $consultorio = $model_consultorio->ShowAllConsultoriosSubConsultorio($farmacia->id_establecimiento);

          $medico = 0;

          $model_atencione = new TipoAtencione;
          $atencione = $model_atencione->ShowTipoAtencionAll();
          
          $model_establecimiento = new \App\Models\Establecimiento;
          $establecimientos = $model_establecimiento->GetById($id_ipress);
          if(Auth::user()->tipo_registro==1){
            $model_medico = new Medico();
            $medico_actual = $model_medico->GetMedicoByDniAndEstablecimiento(Auth::user()->dni,$farmacia->id_establecimiento);
            $medico = $medico_actual->id;
          }

          return view('frontend.evoluciones.all_evoluciones_atenciones_enfermeria', compact('farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nombre_beneficiario', 'estado','flagemergenciareferencia','consultorio','medico','id_consultorio_actual','atencione','id_atencione','paciente','id_paciente','establecimientos'));
        else:
          if($estado==2):
            return redirect('/dashboard')->withInput()->withErrors(array('Elemento no encontrado ..!'));
          else:
            return redirect('/dashboard')->withInput()->withErrors(array('Elemento no encontrado ..!'));
          endif;
        endif;
    }

    public function modal_historial_evoluciones_enfermeria($id_paciente, $estado, $id_tipo_atencion)
  {   
      $model_evoluciones = new \App\Models\Evolucion;
      $buscar_ultima_atencion = Prestaciones::where('id_ext', $id_paciente)->where('id_estado_reg', 1)->orderBy('id', 'DESC')->first();
      $evoluciones = $model_evoluciones->GetEvolucionesByPacienteEnfermeria($id_paciente, $buscar_ultima_atencion->id);

      
      return view('frontend.evoluciones.modal_historial_evoluciones_enfermeria', compact('evoluciones'));
  }
}