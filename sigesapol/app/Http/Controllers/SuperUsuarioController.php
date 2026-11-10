<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Farmacia;
use App\Models\Especializacione;
use App\Models\Establecimiento;
use App\Models\Medico;
use App\Models\Medico_turno;
use App\Models\Medico_consultorio;
use App\Models\Consultorio;
use App\Models\Receta_medico;
use App\Models\Receta_vale;
use App\Models\User;
use App\Models\Emergencia;
use App\Models\Prestaciones;
use App\Models\Hospitalizacione;
use App\Models\TipoAtencione;
use App\Models\Superusuario;
use App\Models\Cama;
use App\Helpers\Util;

use SoapClient;
use Session;
use Carbon\Carbon;
use DB;
use App;
use Auth;
use Illuminate\Support\Facades\Route;

require_once 'funciones/function.php';

class SuperUsuarioController extends Controller
{

  function __construct()
    {
        $this->middleware('auth'); 
    } 
  
    public function ver_usuarios_su($id_user = 0) {
        $model_medico = new \App\Models\Medico();
        $model_usuario = new User;
        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;
        $id_user =  Auth::user()->id;
        if ($id_user != '3068' && $id_user != '3263') {
          return redirect('/auth/login');
        }
        $date = Carbon::now();
        $fechaHasta = $date->format('d-m-Y');
        $fechaDesdex = $date->format('d-m-Y');
        $fechaDesdey = strtotime('-7 day', strtotime($fechaDesdex));
        $fechaDesde = date('d-m-Y', $fechaDesdey);
        //====================
        $id_establecimiento='';
        $dni_beneficiario='';
        $nombre_beneficiario='';
        $dni_administrador='';
        $nombre_administrador='';
        $estado=1; //activo
        $opcion='Super Usuario';

        $establecimientos  = "SELECT * from establecimientos";
        $establecimientos  = DB::select($establecimientos);
        
        return view('frontend.super_usuario.ver_usuarios_actualidad', compact('farmacia', 'fechaDesde', 'establecimientos', 'fechaHasta','dni_beneficiario','nombre_beneficiario','dni_administrador','nombre_administrador','estado','opcion'));
    }

    public function listar_usuarios_modificados_ajax(Request $request)
    {
     
      $model_emergencias = new Superusuario();
      $p[] = $request->establecimiento;
      $p[] = $request->fecha_ini;
      $p[] = $request->fecha_fin;
      $p[] = $request->dni_beneficiario;
      $p[] = strtoupper($request->nombre_beneficiario);
      $p[] = $request->dni_administrador;
      $p[] = strtoupper($request->nombre_administrador);
      $p[] = $request->estado;
      $p[] = $request->NumeroPagina;
      $p[] = $request->NumeroRegistros;
      $p[] = $request->opcion;
      $data = $model_emergencias->listar_usuarios_modificados_ajax($p);
      //dd($data);
      $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;
      $result["PageStart"] = $request->NumeroPagina;
      $result["pageSize"] = $request->NumeroRegistros;
      $result["SearchText"] = "";
      $result["ShowChildren"] = true;
      $result["iTotalRecords"] = $iTotalDisplayRecords;
      $result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
      $result["aaData"] = $data;
      //dd($data);
      echo json_encode($result);
   
    }

    public function ver_permisos($id_user, $dni) {
      
          if (!Auth::check()):
              return redirect('/auth/login');
          endif;
          $user_administrado = "SELECT * from users u where dni = '$dni'-- and status = '1'";
          $user_administrado =  DB::select($user_administrado);
          $user_administrado = $user_administrado[0];

          $local_usuario = "SELECT * from establecimientos e where id = '$user_administrado->id_establecimiento'";
          $local_usuario =  DB::select($local_usuario);
          $local_usuario = $local_usuario[0];
         
          $farmacia_usuario = "SELECT * from farmacias f where id = '$user_administrado->id_farmacia'";
          $farmacia_usuario =  DB::select($farmacia_usuario);
          $farmacia_usuario = $farmacia_usuario[0];

          $permisos_user = "SELECT
                            m.id,
                            p.idmenu,
                            p.crear,
                            p.modificar,
                            p.eliminar,
                            p.lectura,
                            m.nombremenu,
                            p.updated_at,
                            m.parentid
                            from permisos p 
                            inner join menus m on p.idmenu = m.id 
                            where p.idusuario = '$user_administrado->id' and m.flagregistro = '1' order by m.parentid asc ";
          $permisos_user =  DB::select($permisos_user);
          //dd($permisos_user);
          return view('frontend.super_usuario.ver_historial_permisos', compact('user_administrado', 'permisos_user','local_usuario','farmacia_usuario'));
      }

      public function bitacora_modificacion($id_user, $dni) {
      
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $user_administrado = "SELECT * from users u where dni = '$dni' and status = '1'";
        $user_administrado =  DB::select($user_administrado);
        $user_administrado = $user_administrado[0];
        
        $bitacora = "SELECT 
                     *,
                     be.created_at as mostrar_fecha
                     from bitacora_ediciones be 
                     inner join users u on be.id_user_editador = u.id 
                     where id_user_modificado = '$user_administrado->id'";
        $bitacora =  DB::select($bitacora);

        $local_usuario = "SELECT * from establecimientos e where id = '$user_administrado->id_establecimiento'";
          $local_usuario =  DB::select($local_usuario);
          $local_usuario = $local_usuario[0];
         
          $farmacia_usuario = "SELECT * from farmacias f where id = '$user_administrado->id_farmacia'";
          $farmacia_usuario =  DB::select($farmacia_usuario);
          $farmacia_usuario = $farmacia_usuario[0];

        $user_editor = "SELECT * from users u where id = '$id_user' and status = '1'";
        $user_editor =  DB::select($user_editor);
        $user_editor = $user_editor[0];

        return view('frontend.super_usuario.ver_bitacora', compact('user_administrado','user_editor','bitacora','local_usuario','farmacia_usuario'));
    }

  public function registrar_alta_medica_emer(Request $request){
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif; 
    $date = Carbon::now();
    $fechaActual = $date->format('d-m-Y');
    $horaActual = $date->format('H:i:s');
    $id_user =  Auth::user()->id;
    $model_emergencia = Emergencia::where('id', '=', $request->id_emergencia)->first();
    $model_emergencia->fecha_alta_medica = $date;
    $model_emergencia->hora_alta_medica = $horaActual;
    $model_emergencia->id_tipo_egres_med = $request->id_tipo_egreso;
   
    $model_emergencia->flag_egreso = '1';
    $model_emergencia->estado = '5';
    $model_emergencia->id_medico_egreso = $id_user;

    $p_diagnostico = ""; 
    $id_1=0;
    $id_2=0;
    $id_3=0; 
    if (isset($request->id_diagnostico)) :
      foreach ($request->id_diagnostico as $key => $value) : 
        if($key==0 && $request->id_diagnostico[$key] != ""){
          $id_1=$request->id_diagnostico[$key] ; 
        }
        if($key==1 && $request->id_diagnostico[$key] != ""){
          $id_2=$request->id_diagnostico[$key] ; 
        }
        if($key==2 && $request->id_diagnostico[$key] != ""){
          $id_3=$request->id_diagnostico[$key] ; 
        } 
      endforeach;
    endif; 
  
    $model_emergencia->id_diag_cab = $id_1;
    $model_emergencia->id_diag_cuer1 = $id_2;
    $model_emergencia->id_diag_cuer2 = $id_3;
   
    if($request->id_tipo_egreso == '1'){//alta
      $model_emergencia->id_condi_egres_med = $request->id_condicion_alta;
      $model_emergencia->id_servicio_destino = "0";
      $model_emergencia->id_upss_alta ="0";
      $model_emergencia->obs_traslado = "0";
      $model_emergencia->detalle_alta_admin = "0";
      $model_emergencia->id_establecimiento_traslado = "0";
      $model_emergencia->necropsia = "0";
      $model_emergencia->causas_muerte = "0";
      $model_emergencia->condicion_traslado = "0";
      $model_emergencia->personal_acompañante = "0";
      $model_emergencia->especialida_destino = "0";
      $model_emergencia->especialidad_destino_otro = "0";
    }else if($request->id_tipo_egreso == '2'){//hospitalizado
      $upss="SELECT 
    concat(c.nombre,' / ',u.codigo ) as nombre,
    u.id as id_upss,
    u.codigo as codigo_upss,
    c.id as id_consultorio,
    c.nombre as nombre_consultorio
    from consultorios c 
    inner join upsses u on u.id_consultorio = c.id 
    where u.id_tipo_atencion = '3' and u.estado = '1' and u.id='$request->id_servicio_hospitalizacion'";
    $upss= DB::select($upss);
    $upss=$upss[0];
    $model_emergencia->id_condi_egres_med = "3";
      $model_emergencia->id_servicio_destino = $upss->id_consultorio;
      $model_emergencia->id_upss_alta = $upss->id_upss;
      $model_emergencia->obs_traslado = "0";
      $model_emergencia->detalle_alta_admin = "0";
      $model_emergencia->obs_traslado = "0";
      $model_emergencia->necropsia = "0";
      $model_emergencia->causas_muerte = "0";
      $model_emergencia->condicion_traslado = "0";
      $model_emergencia->personal_acompañante = "0";
      $model_emergencia->especialida_destino = "0";
      $model_emergencia->especialidad_destino_otro = "0";
    }else if($request->id_tipo_egreso == '3'){//traslado
      $model_emergencia->id_servicio_destino = "0";
      $model_emergencia->id_upss_alta ="0";
      $model_emergencia->detalle_alta_admin = "0";
      $model_emergencia->id_condi_egres_med = "3";
      $model_emergencia->obs_traslado = $request->obs_traslado;
      $model_emergencia->id_establecimiento_traslado = $request->id_establecimiento_traslado;
      $model_emergencia->necropsia = "0";
      $model_emergencia->condicion_traslado = $request->condicion_traslado;
      $model_emergencia->personal_acompañante = $request->personal_acompañante;
      $model_emergencia->especialida_destino = $request->especialida_destino;
      $model_emergencia->especialidad_destino_otro = $request->especialidad_destino_otro;
      $model_emergencia->causas_muerte = "0";
    }else if($request->id_tipo_egreso == '4'){//fallecido
      $model_emergencia->id_servicio_destino = "0";
      $model_emergencia->id_upss_alta ="0";
      $model_emergencia->obs_traslado = "0";
      $model_emergencia->obs_traslado = "0";
      $model_emergencia->detalle_alta_admin = "0";
      $model_emergencia->id_condi_egres_med = "6";
      $model_emergencia->necropsia = $request->necropsia;
      $model_emergencia->causas_muerte = $request->causas_muerte;
      $model_emergencia->condicion_traslado = "0";
      $model_emergencia->personal_acompañante = "0";
      $model_emergencia->especialida_destino = "0";
      $model_emergencia->especialidad_destino_otro = "0";
    }else if($request->id_tipo_egreso == '7'){//retiro voluntario
      $model_emergencia->id_servicio_destino = "0";
      $model_emergencia->id_upss_alta ="0";
      $model_emergencia->obs_traslado = "0";
      $model_emergencia->obs_traslado = "0";
      $model_emergencia->id_condi_egres_med = "4";
      $model_emergencia->necropsia = "0";
      $model_emergencia->detalle_alta_admin = $request->obs_retiro;
      $model_emergencia->causas_muerte = "0";
      $model_emergencia->condicion_traslado = "0";
      $model_emergencia->personal_acompañante = "0";
      $model_emergencia->especialida_destino = "0";
      $model_emergencia->especialidad_destino_otro = "0";
    }else if($request->id_tipo_egreso == '6'){//fuga
      $model_emergencia->id_servicio_destino = "0";
      $model_emergencia->id_upss_alta ="0";
      $model_emergencia->obs_traslado = "0";
      $model_emergencia->obs_traslado = "0";
      $model_emergencia->id_condi_egres_med = "5";
      $model_emergencia->necropsia = "0";
      $model_emergencia->detalle_alta_admin = $request->obs_fuga;
      $model_emergencia->causas_muerte = "0";
      $model_emergencia->condicion_traslado = "0";
      $model_emergencia->personal_acompañante = "0";
      $model_emergencia->especialida_destino = "0";
      $model_emergencia->especialidad_destino_otro = "0";
    }else{
      $model_emergencia->id_servicio_destino = "0";
      $model_emergencia->id_upss_alta ="0";
      $model_emergencia->obs_traslado = "0";
      $model_emergencia->obs_traslado = "0";
      $model_emergencia->necropsia ="0";
      $model_emergencia->detalle_alta_admin = "0";
      $model_emergencia->causas_muerte = "0";
      $model_emergencia->id_condi_egres_med = "0";
      $model_emergencia->id_condi_egres_med = "1";
      $model_emergencia->condicion_traslado = "0";
      $model_emergencia->personal_acompañante = "0";
      $model_emergencia->especialida_destino = "0";
      $model_emergencia->especialidad_destino_otro = "0";
     
    }
    
    if($model_emergencia->id_tipo_beneficiario == '12'){
      $model_emergencia->numero_boleta = $request->numeroboleta;
      $model_emergencia->numero_documento_coronel_guardia  = $request->numeroguar;
    }else{
      $model_emergencia->numero_boleta = "0";
      $model_emergencia->numero_documento_coronel_guardia  = "0";
    }

    if(isset($model_emergencia->cama_estado)){

      $model_cama = Cama::where('id', '=', $model_emergencia->cama_estado)->first();
      $model_cama->situacion = '0';
      $model_cama->save();
    }
   
    $model_emergencia->save(); 
    Session::flash('flash_message', 'Alta Medica exitosa');

    //$this->alta_medica_ver($id_user,$request->id_emergencia);
   
    Session::flash('flash_message', 'Alta Medica exitosa');
    $emergencia_prestacion = Prestaciones::where('id_ext', '=', $request->id_emergencia)->first();
    if(is_object($emergencia_prestacion)):

    $id_establecimiento=Auth::user()->id_establecimiento;
    $model_emergencia = new Emergencia();
    $prestacion= $model_emergencia->getIdPrestacion($request->id_emergencia);
    $id_prestacion =  $prestacion->id;
    $emergencia = Emergencia::where('id', '=', $request->id_emergencia)->first();
    $paciente_emergencia = $model_emergencia->GetBuscaEmergenciaTriaje($request->id_emergencia,$emergencia->estado);
    $egreso = $model_emergencia->GetEgresoEmergencia($request->id_emergencia);
    $model_establecimiento = new \App\Models\Establecimiento;
    $model_prestacion = new \App\Models\Prestaciones;
    $model_kardex = new \App\Models\Kardexe();
    $fechaServidor = $model_kardex->getFechaServidor();
    $horaservidor = $model_kardex->getHoraServidor();
    $date = Carbon::now();
    $fecha_actual = $date->format('d-m-Y');
    $prestacion = $model_prestacion->GetPrestacionById($id_prestacion);
    $diagnostico = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
    $flag_pendinte_firma = ($paciente_emergencia->fecha_firma_egreso == "") ? 1 : 0;
    
      $fechaEgreso = $model_kardex->getFechaServidor();
      $horaEgreso = $model_kardex->getHoraServidor();
    $establecimiento = $model_establecimiento->GetById($id_establecimiento);
    if ($id_establecimiento == 1) {
      $nivel = 2;
    } else {
      $nivel = $establecimiento[0]->nivel;
    }
    if($emergencia->id_tipo_egres_med==3){
      $estable = "select * from establecimientos e where id = '$emergencia->id_establecimiento_traslado'";
      $estable=  DB::select($estable);
      $estable = $estable[0];
    }
  
     $model_consultorio = new \App\Models\Consultorio;
     $consulta_externa = $model_consultorio->ShowConsultorioByIdIpressAndIdTipoAtencion($id_establecimiento,1);

    $diagnosti= array();
    $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$emergencia->id_diag_cab'";
    $diagnos = DB::select($diagnos);
    $diagnos = $diagnos[0];
    $diagnos->cabecera = "PRINCIPAL";
    $diagnos->tipo = "DEFINITIVO";
    $diagnosti[0]=$diagnos;
    if($emergencia->id_diag_cuer1>0){
       
        $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$emergencia->id_diag_cuer1'";
        $diagnos = DB::select($diagnos);
        $diagnos = $diagnos[0];
        $diagnos->cabecera = "SECUNDARIO";
        $diagnos->tipo = "DEFINITIVO";
        $diagnosti[1]=$diagnos;
    }elseif($emergencia->id_diag_cuer2>0){
       
        $diagnos = "SELECT  d.id,d.codigo,d.nombre,d.nombre as cabecera,d.nombre as tipo  from diagnosticos d where id='$emergencia->id_diag_cuer2'";
        $diagnos = DB::select($diagnos);
        $diagnos = $diagnos[2];
        $diagnos->cabecera = "SECUNDARIO";
        $diagnos->tipo = "DEFINITIVO";
        $diagnosti[2]=$diagnos;
    }

    $dni=Auth::user()->dni;
    $model_medico = new Medico();
    $exite_medico=$model_medico->exite_medico($dni);
    $exite_medico_ipress=$model_medico->medico_yaregistrado($dni, $id_establecimiento);
    if($exite_medico==0){
       Session::flash('flash_message', 'Sólo los médicos pueden registrar alta médica');
      return redirect('/all_atenciones_by_emergencia');
    }else if($exite_medico_ipress==""){
      $model_establecimiento = new Establecimiento();
      $establecimiento=  $model_establecimiento->GetById($id_establecimiento);
      Session::flash('flash_message', 'Usted no es Médico en el establecimiento: '. $establecimiento[0]->nombre);
      return redirect('/all_atenciones_by_emergencia');
    }else{
     
        return view('frontend.medicos.alta_medica_firmar1', compact('prestacion','upss','estable', 'diagnosti','diagnostico', 'id_prestacion', 'fechaServidor', 'horaservidor', 'nivel', 'flag_pendinte_firma', 'consulta_externa', 'paciente_emergencia', 'emergencia' ));

     } 
    else:
      Session::flash('flash_message', 'Necesita tener una prestacion para dar un Alta Medica');
      return redirect('/all_atenciones_by_emergencia'); 
    endif;
  }

}
