<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Fema_ficha;
use App\Models\Fema_examene;
use App\Models\Fema_diagnostico;
use App\Models\Medico;
use App\Models\Situacion;
use App\Models\Tipo_trabajo;
use App\Helpers\Util;
use App\Models\Profesione;
use App\Models\Asegurado;
use App\Models\Raza;
use SoapClient;
use Session;
use DB;
use App;
use App\Models\Fema_fichaReporte;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

require_once 'funciones/function.php';

class FemaController extends Controller
{
  function __construct()
    {
        $this->middleware('auth'); 
    }
    
  public function index()
  {

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
    if ($ruta == "consulta_titular");
    $ruta = "consulta_titular";
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
    $permiso = $menu[0];
    if ($permiso->crear == 0)
      return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

    $id_usuario = Auth::user()->id;

    return view('frontend.fema.consulta_titular', compact('id_usuario','permiso'));
  }

  

  public function busqueda_fema_ficha() {
    if (!Auth::check()) :
        return redirect('/auth/login');
    else:
      if(Auth::user()->actualizo_passw==0):
        return redirect('auth/password/change');
      endif;
    endif;

    $id_usuario = Auth::user()->id;
    $fiscalisador = DB::table('users')
    ->select('users.*')
    ->where('id',$id_usuario)
    ->first();

    $modelFemaFicha = new Fema_ficha;
    $date = Carbon::now();
    $fechaDesde = $date->format('d-m-Y');
    $fechaHasta = $date->format('d-m-Y');
    $nroDocumento = "";
    $idProceso = "";
    $idSubProceso = "";
    $nroFicha = "";
    
    $variable = 1; 
    //return view('frontend.fema.busqueda_fichas', compact('fichas','fechaDesde','fechaHasta','nroDocumento','idProceso','idSubProceso','nroFicha'));

    return view('frontend.fema.busqueda_fichas', compact('variable','fechaDesde','fechaHasta','nroDocumento','idProceso','idSubProceso','nroFicha','fiscalisador'));
  }

  public function all_reporte_laboratorio_fema($id = 0) {
    if (!Auth::check()) :
        return redirect('/auth/login');
    else:
      if(Auth::user()->actualizo_passw==0):
        return redirect('auth/password/change');
      endif;
    endif;

    $modelFemaFicha = new Fema_ficha;
    $date = Carbon::now();
    $fechaDesde = $date->format('d-m-Y');
    $fechaHasta = $date->format('d-m-Y');
    $nroDocumento = "";
    $idProceso = "";
    $idSubProceso = "";
    $nroFicha = "";
    $id_establecimiento = Auth::user()->id_establecimiento;
    if($id_establecimiento==76){ 
      $id_establecimiento = 0;
    }
    $variable = 1;
    
    return view('frontend.fema.mostrar_fichas_labo_fema', compact('fechaDesde','fechaHasta','nroDocumento','variable'));
  }

  public function busca_fema_ficha(Request $request) {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_usuario = Auth::user()->id;
    $fiscalisador = DB::table('users')
    ->select('users.*')
    ->where('id',$id_usuario)
    ->first();
    
    $modelFemaFicha = new Fema_ficha;
    $fechaDesde = $request->fechaDesde;
    $fechaHasta = $request->fechaHasta;
    $nroDocumento = $request->nroDocumento;
    $idProceso = $request->idProceso;
    $idSubProceso = $request->idSubProceso;
    $nroFicha = $request->nroFicha;
    $fichas = $modelFemaFicha->GetAll($fechaDesde, $fechaHasta, $nroDocumento, $idProceso, $idSubProceso, $nroFicha);
  
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->validacion = $this->GetValidacion($fichas[$i]->id,$fichas[$i]->sexo);
    }
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->cie10 = $this->GetValidacioncie10($fichas[$i]->nro_doc_ident,$fichas[$i]->id);
    } 
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->yaderivado = $this->Getyaderivado($fichas[$i]->nro_doc_ident,$fichas[$i]->id);
    } 
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->odontologica = $this->Getodontologica($fichas[$i]->nro_doc_ident,$fichas[$i]->id);
    }
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->laboratorio = $this->Getlaboratorio($fichas[$i]->nro_doc_ident,$fichas[$i]->id);
    } 
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->clinico = $this->Getclinico($fichas[$i]->nro_doc_ident,$fichas[$i]->id);
    } 
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->mental = $this->Getmental($fichas[$i]->nro_doc_ident,$fichas[$i]->id);
    }
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->respiratorio = $this->Getrespiratorio($fichas[$i]->nro_doc_ident,$fichas[$i]->id);
    }
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->ginecologico = $this->Getginecologico($fichas[$i]->nro_doc_ident,$fichas[$i]->id);
    } 
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->cardiovascular = $this->Getcardiovascular($fichas[$i]->nro_doc_ident,$fichas[$i]->id);
    } 
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->oftalmologia = $this->Getoftalmologia($fichas[$i]->nro_doc_ident,$fichas[$i]->id);
    }
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->otorrinolaringologia = $this->Getotorrinolaringologia($fichas[$i]->nro_doc_ident,$fichas[$i]->id);
    }
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->atencionlaboratorio = $this->Getatencionlaboratorio($fichas[$i]->nro_doc_ident,$fichas[$i]->id);
    }
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->atencioncardiologia = $this->Getatencioncardiologia($fichas[$i]->nro_doc_ident,$fichas[$i]->id);
    }
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->atencionrespiratorio = $this->Getatencionrespiratorio($fichas[$i]->nro_doc_ident,$fichas[$i]->id);
    }

     $variable = 2; 
   
    return view('frontend.fema.busqueda_fichas', compact('fichas','fechaDesde','fechaHasta','nroDocumento','idProceso','idSubProceso','nroFicha','variable','fiscalisador'));
  }

  public function fema_atencion($id_ficha, $id_proceso, $id_tipo_examen) {
    if (!Auth::check()):
      return redirect('/auth/login');
    endif;

    $id_usuario = Auth::user()->id;

     $permiso = DB::table('permisos')
    ->select('permisos.lectura')
    ->where('idusuario',$id_usuario)
    ->where('idmenu','117')
    ->first();

    if ($permiso->lectura != '1'):
      return redirect('/auth/login');
    endif;

    //dd($permiso);

    $model_femaficha = new Fema_ficha();

    $existe = $model_femaficha->ExisteAtencionExamen($id_ficha, $id_proceso, $id_tipo_examen);
    
    if (count($existe) == 0):
      $ficha = $model_femaficha->GetById($id_ficha);
      $ficha = $ficha[0];
      
        $imc=($ficha->peso_kg)/((($ficha->talla_cm)/100)*(($ficha->talla_cm)/100));
        if($imc>40):
          $indice="OBESIDAD GRADO III";
          else:
            if($imc>35):
              $indice="OBESIDAD GRADO II";
            else:
              if($imc>30):
                $indice="OBESIDAD GRADO I";
              else:
                if($imc>25):
                  $indice="SOBRE PESO";
                else:
                  if($imc>18):
                    $indice="PESO NORMAL";
                  else:
                    $indice="  ";
                  endif;
                endif;
              endif;
            endif;
        endif;
        $imc = number_format($imc, 2);
        
      $model_medico = new Medico();
     // $profesional = $model_medico->GetDatosMedicoAndEstablecimiento2(Auth::user()->dni, Auth::user()->id_establecimiento);
  $profesional=DB::table('users')
      ->select('users.*')
      ->where('dni',Auth::user()->dni)
      ->first();

    /*  if(count($profesional)>0):
        $profesional = $profesional[0];
      else:
      Session::flash('flash_message', 'Debe ser médico de este establecimiento');
        return redirect('/dashboard');
      endif;
*/
      $model_situacion = new Situacion();
      $situaciones = $model_situacion->GetAll();

      $model_tipotrabajo = new Tipo_trabajo();
      $tipo_trabajos = $model_tipotrabajo->GetAll();

      $asegurado=DB::table('asegurados')
      ->select('asegurados.*')
      ->where('nro_doc_ident',$ficha->nro_doc_ident)
      ->first();

        $alert="select
        te.nombre, 
          fe.alerta 
          from fema_fichas ff 
          inner join fema_examenes fe on fe.id_fema_ficha  = ff.id 
          inner join tipo_examenes te on fe.id_tipo_examen = te.id 
          where ff.estado =1 and fe.estado = 1 and ff.nro_doc_ident = '$asegurado->nro_doc_ident'";
         $alert = DB::select($alert);
        
      $establecimientos=DB::table('establecimientos')
      ->select('establecimientos.*')
      ->where('id',Auth::user()->id_establecimiento)
      ->first();

      if($ficha->sexo == 'M'):
        $sexo = 'MASCULINO';
      else:
        $sexo = 'FEMENINO';
      endif;
      $datos = "SELECT * FROM fema_fichas where id = '$id_ficha'";
      $datos = DB::select($datos);
      $datos = $datos[0];
      return view('frontend.fema.ficha', compact('ficha','datos','id_proceso','alert','establecimientos','id_tipo_examen','sexo','profesional','situaciones','tipo_trabajos','imc','indice'));
    else:
      //Session::flash('flash_message', 'Este examen ya esta registrado');
      return redirect('/ver_fema_ficha/'.$id_ficha.'/'.$id_proceso.'/'.$id_tipo_examen);
    endif;
  }

  public function fema_atencion_anular($id_ficha, $id_proceso, $id_tipo_examen) {
    if (!Auth::check()):
      return redirect('/auth/login');
    endif;

    $model_femaficha = new Fema_ficha();

    $existe = $model_femaficha->ExisteAtencionExamen($id_ficha, $id_proceso, $id_tipo_examen);
    if (count($existe) == 0):

    else:
      //Session::flash('flash_message', 'Este examen ya esta registrado');
      return redirect('/anular_fema_ficha/'.$id_ficha.'/'.$id_proceso.'/'.$id_tipo_examen);
    endif;
  }

  public function anular_ficha_completa($id_ficha, $id_proceso) {
    if (!Auth::check()):
      return redirect('/auth/login');
    endif;

    $model_femaficha = new Fema_ficha();

    $cad = "select 
    * 
    from fema_fichas ff 
    inner join fema_triajes ft on ft.id_fema_ficha = ff.id 
    where ff.id = '$id_ficha' and ff.estado = '1' and ft.estado = '1'";
    $existe = DB::select($cad);
   
    if (count($existe) == 0):
    else:
      //Session::flash('flash_message', 'Este examen ya esta registrado');
      return redirect('/anularfichacompleta/'.$id_ficha.'/'.$id_proceso);
    endif;
  }

  public function anular_triaje($id_ficha, $id_proceso) {
    if (!Auth::check()):
      return redirect('/auth/login');
    endif;

    $model_femaficha = new Fema_ficha();
    
    $cad = "select 
    * 
    from fema_fichas ff 
    inner join fema_triajes ft on ft.id_fema_ficha = ff.id 
    where ff.id = '$id_ficha' and ff.estado = '1' and ft.estado = '1'";
    $existe = DB::select($cad);

    if (count($existe) == 0):
    else:
      //Session::flash('flash_message', 'Este examen ya esta registrado');
      return redirect('/anularficha_triaje/'.$id_ficha.'/'.$id_proceso);
    endif;
  }

  public function fema_preatencion($id_ficha, $id_proceso, $id_tipo_examen) {
    if (!Auth::check()):
      return redirect('/auth/login');
    endif;

    $model_femaficha = new Fema_ficha();

    $existe = $model_femaficha->ExisteAtencionExamen($id_ficha, $id_proceso, $id_tipo_examen);
    
    if (count($existe) == 0):
      $ficha = $model_femaficha->GetById($id_ficha);
      $ficha = $ficha[0];
      
        $imc=($ficha->peso_kg)/((($ficha->talla_cm)/100)*(($ficha->talla_cm)/100));
        if($imc>40):
          $indice="OBESIDAD GRADO III";
          else:
            if($imc>35):
              $indice="OBESIDAD GRADO II";
            else:
              if($imc>30):
                $indice="OBESIDAD GRADO I";
              else:
                if($imc>25):
                  $indice="SOBRE PESO";
                else:
                  if($imc>18):
                    $indice="PESO NORMAL";
                  else:
                    $indice="  ";
                  endif;
                endif;
              endif;
            endif;
        endif;
        $imc = number_format($imc, 2);
        
      $model_medico = new Medico();
     $profesional=DB::table('users')
      ->select('users.*')
      ->where('dni',Auth::user()->dni)
      ->first();
  //  $profesional = $model_medico->GetDatosMedicoAndEstablecimiento2(Auth::user()->dni, Auth::user()->id_establecimiento);
/*
    if(count($profesional)>0):
      $profesional = $profesional[0];
    else:
      Session::flash('flash_message', 'Debe ser médico de este establecimiento');
      return redirect('/dashboard');
    endif;
*/
     
      $model_situacion = new Situacion();
      $situaciones = $model_situacion->GetAll();

      $model_tipotrabajo = new Tipo_trabajo();
      $tipo_trabajos = $model_tipotrabajo->GetAll();

      $asegurado=DB::table('asegurados')
      ->select('asegurados.*')
      ->where('nro_doc_ident',$ficha->nro_doc_ident)
      ->first();

      $establecimientos=DB::table('establecimientos')
      ->select('establecimientos.*')
      ->where('id',$profesional->id_establecimiento)
      ->first();

      if($ficha->sexo == 'M'):
        $sexo = 'MASCULINO';
      else:
        $sexo = 'FEMENINO';
      endif;
      
      return view('frontend.fema.fichapre', compact('ficha','id_proceso','establecimientos','id_tipo_examen','sexo','profesional','situaciones','tipo_trabajos','imc','indice'));
    else:
      //Session::flash('flash_message', 'Este examen ya esta registrado');
      return redirect('/ver_fema_fichapre/'.$id_ficha.'/'.$id_proceso.'/'.$id_tipo_examen);
    endif;
  }

  public function anularFichaFema(Request $request) {
    if (!Auth::check()):
      return redirect('/auth/login');
    endif;

    $dato= DB::table('fema_examenes')
    ->select('fema_examenes.*')
    ->where('id_fema_ficha',$request->id_ficha)
    ->where('id_tipo_examen',$request->id_tipo_examen)
    ->first();

    if (count($dato) == 0):
      Session::flash('flash_message', 'No se pudo Anular la Atencion, vuelva a intentarlo nuevamente');
     return redirect('/busqueda_fema_ficha');
    else:
      
      $model_fema_examenes_envio = \App\Models\Fema_examene::find($dato->id);
      $model_fema_examenes_envio->estado="0";
      $model_fema_examenes_envio->motivo_anulacion=$request->motivo;
      $model_fema_examenes_envio->updated_us = Auth::user()->id;
      $model_fema_examenes_envio->updated_at = Carbon::now();
      $model_fema_examenes_envio->save(); 

    endif;
    
   Session::flash('flash_message', 'Se Anulo la Atencion, Exitosamente');
   return redirect('/busqueda_fema_ficha');

  }

  public function anularfichacompletafema(Request $request) {
    if (!Auth::check()):
      return redirect('/auth/login');
    endif;
   
    $dato= DB::table('fema_fichas')
    ->select('fema_fichas.*')
    ->where('id',$request->id_ficha)
    ->first();

    $datotriaje= DB::table('fema_triajes')
    ->select('fema_triajes.*')
    ->where('id_fema_ficha',$dato->id)
    ->first();
  
    if (count($dato) == 0):
      Session::flash('flash_message', 'No se pudo Anular la Atencion, vuelva a intentarlo nuevamente');
     return redirect('/busqueda_fema_ficha');
    else:
      
      $model_fema_examenes_envio = \App\Models\Fema_ficha::find($dato->id);
      $model_fema_examenes_envio->estado="0";
      $model_fema_examenes_envio->motivo_anulacion=$request->motivo;
      $model_fema_examenes_envio->updated_us = Auth::user()->id;
      $model_fema_examenes_envio->updated_at = Carbon::now();
      $model_fema_examenes_envio->save(); 

      $model_ficha_triaje = \App\Models\Fema_triaje::find($datotriaje->id);
      $model_ficha_triaje->motivo_anulacion=$request->motivo;
      $model_ficha_triaje->estado="0";
      $model_ficha_triaje->updated_us = Auth::user()->id;
      $model_ficha_triaje->updated_at = Carbon::now();
      $model_ficha_triaje->save(); 

    endif;
    
   Session::flash('flash_message', 'Se Anulo la FICHA Fema y el Triaje, Exitosamente');
   return redirect('/busqueda_fema_ficha');

  }

  public function anularficha_triaje_ft(Request $request) {
    if (!Auth::check()):
      return redirect('/auth/login');
    endif;
   
    $dato= DB::table('fema_fichas')
    ->select('fema_fichas.*')
    ->where('id',$request->id_ficha)
    ->first();

    $datotriaje= DB::table('fema_triajes')
    ->select('fema_triajes.*')
    ->where('id_fema_ficha',$dato->id)
    ->first();
  
    if (count($dato) == 0):
      Session::flash('flash_message', 'No se pudo Anular la ficha de triaje, vuelva a intentarlo nuevamente');
     return redirect('/busqueda_fema_ficha');
    else:

      $model_ficha_triaje = \App\Models\Fema_triaje::find($datotriaje->id);
      $model_ficha_triaje->estado="0";
      $model_ficha_triaje->motivo_anulacion=$request->motivo;
      $model_ficha_triaje->updated_us = Auth::user()->id;
      $model_ficha_triaje->updated_at = Carbon::now();
      $model_ficha_triaje->save(); 

    endif;
    
   Session::flash('flash_message', 'Se Anulo la FICHA de Triaje, Exitosamente');
   return redirect('/busqueda_fema_ficha');

  }

  public function guardarFichaFema(Request $request) {
    if (!Auth::check()):
      return redirect('/auth/login');
    endif;

    $ficha = new Fema_ficha();
    $triaje = "SELECT * from fema_triajes where id_fema_ficha = '$request->id_ficha'";
    $triaje = DB::select($triaje);
    $triaje = $triaje[0];
    $id_establecimiento = Auth::user()->id_establecimiento;
    $p_observacion = "";
    $p_observacion .= "{";
    if (isset($request->nombre_observacion)) :
      foreach ($request->nombre_observacion as $key => $value) :
        if ($request->nombre_observacion[$key] != "") {
          $p_observacion .= "{";
          $p_observacion .= $request->nombre_observacion[$key];
          $p_observacion .= "},";
        }
      endforeach;
      if (strlen($p_observacion) > 1) $p_observacion = substr($p_observacion, 0, -1);
    endif;
    $p_observacion .= "}";

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

    $dientes = "xxxx";

    $id_ficha = 0; // Init
    switch ($request->id_tipo_examen):
      case 1: //Odonto
        if ($request->id_proceso != 0):
          $id_ficha = $ficha->registrarFichaOdonto($request->id_proceso, $request->id_ficha, $request->id_tipo_examen, $dientes, $request->codigo_odontograma, $request->capacidadmasticatoria, $request->descripcion_odontograma, $request->alerta, $request->id_profesional, Auth::user()->id, $p_observacion, $p_diagnostico,$id_establecimiento);
        endif;
        break;
      case 3: //Clinico general
        if ($request->id_proceso != 0):
          $id_ficha = $ficha->registrarFichaExamenClinico($request->id_proceso, $request->id_ficha, $request->id_tipo_examen, $request->cli_gen_antecedentes, $request->cli_gen_examen_clinico, $request->alerta, $request->id_profesional, Auth::user()->id, $p_observacion, $p_diagnostico,$id_establecimiento);
        endif;
        break;
      case 4: //Salud mental
        if ($request->id_proceso != 0):
          $id_ficha = $ficha->registrarFichaSaludMental($request->id_proceso, $request->id_ficha, $request->id_tipo_examen, $request->sm_area_emocional, $request->sm_area_cognitiva, $request->sm_area_neurobiologica, $request->sm_derivacion, $request->sm_observaciones, $request->alerta, $request->id_profesional, Auth::user()->id, $p_observacion, $p_diagnostico,$id_establecimiento);
        endif;
        break;
      case 5: //Respiratorio
        if ($request->id_proceso != 0):
          $id_ficha = $ficha->registrarFichaRespiratorio($request->id_proceso, $request->id_ficha, $request->id_tipo_examen,$request->r_examen_clinico, $request->r_examen_radiologico, $request->r_examen_esputo, $request->alerta, $request->id_profesional, Auth::user()->id, $p_observacion, $p_diagnostico,$id_establecimiento);
        endif;
        break;
      case 6: //Ginecologico
        $g_p = $request->g_p_1."-".$request->g_p_2."-".$request->g_p_3."-".$request->g_p_4;
        if ($request->id_proceso != 0):
          $id_ficha = $ficha->registrarFichaGinecologico($request->id_proceso, $request->id_ficha, $request->id_tipo_examen, $request->g_g, $g_p, $request->g_menarquia, $request->g_mamografia, $request->g_irs, $request->g_fur, $request->g_mac, $request->g_urs, $request->g_andria, $request->g_antecedentes, $request->g_examen_pap, $request->g_fecha, $request->g_resultado_pap, $request->g_prueba_iva, $request->g_resultado_iva, $request->alerta, $request->id_profesional, Auth::user()->id, $p_observacion, $p_diagnostico, $request->g_gestante, $request->motivo_no_pap,$id_establecimiento);
        endif;
        break;
      case 7: //Cardiovascular
        if ($request->id_proceso != 0):
          $id_ficha = $ficha->registrarFichaCardiovascular($request->id_proceso, $request->id_ficha, $request->id_tipo_examen, $request->c_examen_clinico, $request->c_pa_sistolica, $request->c_pa_diastolica, $request->c_ecg, $request->alerta, $request->id_profesional, Auth::user()->id, $p_observacion, $p_diagnostico,$id_establecimiento);
        endif;
        break;
      case 8: //Oftalmologia
        if ($request->id_proceso != 0):
          $id_ficha = $ficha->registrarFichaOftalmologica($request->id_proceso, $request->id_ficha, $request->id_tipo_examen, $request->o_vision_cercana_od, $request->o_vision_cercana_od_corregida, $request->o_vision_cercana_oi, $request->o_vision_cercana_oi_corregida, $request->o_vision_cercana_od2, $request->o_vision_cercana_od_corregida2, $request->o_vision_cercana_oi2, $request->o_vision_cercana_oi_corregida2, $request->o_tonometria, $request->o_pupilas, $request->o_motil_ocular, $request->o_fondo_ojo, $request->o_vision_colores, $request->alerta, $request->id_profesional, Auth::user()->id, $p_observacion, $p_diagnostico,$id_establecimiento);
        endif;
        break;
      case 2: //Laboratorio
        if ($request->id_proceso != 0):
          $id_ficha = $ficha->registrarFichaLaboratorio($request->id_proceso, $request->id_ficha, $request->id_tipo_examen, $request->lab_vdrl, $request->lab_leucocitos, $request->lab_hematies, $request->lab_glucosa_paquete, $request->lab_albumina, $request->lab_nitritos, $request->lab_hiv, $request->lab_glucosa, $request->lab_colesterol, $request->lab_trigliceridos, $request->lab_hepatitis_b, $request->lab_anti_hct, $request->lab_hto, $request->lab_psa, $request->lab_otros, $request->alerta, $request->id_profesional, Auth::user()->id, $p_observacion, $p_diagnostico,$id_establecimiento);
        endif;
        break;      
        case 9: //OTORRINOLARINGOLOGIA
          if ($request->id_proceso != 0):
            $id_ficha = $ficha->registrarFichaOtorrinolaringologia($request->id_proceso, $request->id_ficha, $request->id_tipo_examen, $request->agudezaod, $request->suficienciaod, $request->agudezaoi, $request->suficienciaoi, $request->examennariz, $request->menbranatimpanica , $request->alerta, $request->id_profesional, Auth::user()->id, $p_observacion, $p_diagnostico,$id_establecimiento);
          endif;
          break;
    endswitch;

     if ($id_ficha == 0) {
     Session::flash('flash_message', 'No se pudo registrar la Ficha, vuelva a intentarlo nuevamente');
     return redirect('/busqueda_fema_ficha');
     } else {
     Session::flash('flash_message', 'Registro exitoso');
     return redirect('/busqueda_fema_ficha');
     }
  }

  public function guardarFichaFemapre(Request $request) {
    if (!Auth::check()):
      return redirect('/auth/login');
    endif;
  
    $model_ficha_fema_envio = \App\Models\Fema_ficha::find($request->id_ficha);
    
    switch ($request->id_tipo_examen):
    
      case 5: //Respiratorio
        if ($request->id_proceso != 0):
          //$id_ficha = $ficha->registrarFichaLaboratorio($request->id_proceso, $request->id_ficha, $request->id_tipo_examen, $request->lab_vdrl, $request->lab_leucocitos, $request->lab_hematies, $request->lab_glucosa_paquete, $request->lab_albumina, $request->lab_nitritos, $request->lab_hiv, $request->lab_glucosa, $request->lab_colesterol, $request->lab_trigliceridos, $request->lab_hepatitis_b, $request->lab_anti_hct, $request->lab_hto, $request->lab_psa, $request->lab_otros, $request->alerta, $request->id_profesional, Auth::user()->id, $p_observacion, $p_diagnostico);
          
            $model_ficha_fema_envio->codigo_respiratorio = $request->codigo_muestrar;
             $model_ficha_fema_envio->flag_estado   = "3";
             $model_ficha_fema_envio->alerta_respiratorio = $request->alerta;
            $model_ficha_fema_envio->respiratorio_created_us = Auth::user()->id;
            $model_ficha_fema_envio->respiratorio_updated_at = new Carbon();
            $model_ficha_fema_envio->save();  endif;
        break;

      case 7: //Cardiovascular
        if ($request->id_proceso != 0):
          //$id_ficha = $ficha->registrarFichaLaboratorio($request->id_proceso, $request->id_ficha, $request->id_tipo_examen, $request->lab_vdrl, $request->lab_leucocitos, $request->lab_hematies, $request->lab_glucosa_paquete, $request->lab_albumina, $request->lab_nitritos, $request->lab_hiv, $request->lab_glucosa, $request->lab_colesterol, $request->lab_trigliceridos, $request->lab_hepatitis_b, $request->lab_anti_hct, $request->lab_hto, $request->lab_psa, $request->lab_otros, $request->alerta, $request->id_profesional, Auth::user()->id, $p_observacion, $p_diagnostico);
          
            $model_ficha_fema_envio->codigo_cardiologia = $request->codigo_muestrac;
            $model_ficha_fema_envio->flag_estado   = "3";
            $model_ficha_fema_envio->alerta_cardiologia = $request->alerta;
            $model_ficha_fema_envio->cardiologia_created_us = Auth::user()->id;
            $model_ficha_fema_envio->cardiologia_updated_at = new Carbon();
            $model_ficha_fema_envio->save();  endif;
        break;

      case 2: //Laboratorio
        if ($request->id_proceso != 0):
          //$id_ficha = $ficha->registrarFichaLaboratorio($request->id_proceso, $request->id_ficha, $request->id_tipo_examen, $request->lab_vdrl, $request->lab_leucocitos, $request->lab_hematies, $request->lab_glucosa_paquete, $request->lab_albumina, $request->lab_nitritos, $request->lab_hiv, $request->lab_glucosa, $request->lab_colesterol, $request->lab_trigliceridos, $request->lab_hepatitis_b, $request->lab_anti_hct, $request->lab_hto, $request->lab_psa, $request->lab_otros, $request->alerta, $request->id_profesional, Auth::user()->id, $p_observacion, $p_diagnostico);
          
          $model_ficha_fema_envio->codigo_laboratorio = $request->codigo_muestral;
          $model_ficha_fema_envio->flag_estado   = "3";
          $model_ficha_fema_envio->alerta_laboratorio = $request->alerta;
          $model_ficha_fema_envio->laboratorio_created_us = Auth::user()->id;
          $model_ficha_fema_envio->laboratorio_updated_at = new Carbon();
          $model_ficha_fema_envio->save();

        endif;
        break;      

    endswitch;
    return redirect('/busqueda_fema_ficha');

  }

  // FUNCION PARA VER LA FICHA REGISTRADA (VER_ATENCION)
  public function verFichaFema($id_ficha, $id_proceso, $id_tipo_examen) {
    $model_femaficha = new Fema_ficha();

    $ficha = $model_femaficha->GetById($id_ficha);
   
    $ficha = $ficha[0];
  
    $imc=($ficha->peso_kg)/((($ficha->talla_cm)/100)*(($ficha->talla_cm)/100));

        if($imc>40):
          $indice="OBESIDAD GRADO III";
          else:
            if($imc>35):
              $indice="OBESIDAD GRADO II";
            else:
              if($imc>30):
                $indice="OBESIDAD GRADO I";
              else:
                if($imc>25):
                  $indice="SOBRE PESO";
                else:
                  if($imc>18):
                    $indice="PESO NORMAL";
                  else:
                    $indice="  ";
                  endif;
                endif;
              endif;
            endif;
        endif;

        $imc = number_format($imc, 2);

        if($ficha->sexo == 'M'):
          $sexo = 'MASCULINO';
        else:
          $sexo = 'FEMENINO';
        endif;
  
    $examen = $model_femaficha->VerFichaByIdFichaAndIdProcesoAndIdTipoExamen($id_ficha, $id_proceso, $id_tipo_examen);
    $examen = $examen[0];

    $model_medico = new Medico();
    $profesional = $model_medico->GetMedicoById2($examen->id_profesional);
    if($profesional->colegiatura == "0"):
      $profesional->colegiatura = ' ';
    endif;
   
   $establecimientos=DB::table('establecimientos')
   ->select('establecimientos.*')
   ->where('id',$profesional->id_establecimiento)
   ->first();

    $observaciones = $model_femaficha->GetObservacionesByIdFemaExamen($examen->id);

    $diagnosticos = $model_femaficha->GetDiagnosticosByIdFemaExamen($examen->id);

    return view('frontend.fema.ver_ficha', compact('ficha','sexo','imc','id_proceso','indice','establecimientos','id_tipo_examen','profesional','examen','observaciones','diagnosticos'));
  }

  public function anularfemaficha($id_ficha, $id_proceso, $id_tipo_examen) {
    $model_femaficha = new Fema_ficha();

    $ficha = $model_femaficha->GetById($id_ficha);
    $ficha = $ficha[0];
   
    $imc=($ficha->peso_kg)/((($ficha->talla_cm)/100)*(($ficha->talla_cm)/100));

        if($imc>40):
          $indice="OBESIDAD GRADO III";
          else:
            if($imc>35):
              $indice="OBESIDAD GRADO II";
            else:
              if($imc>30):
                $indice="OBESIDAD GRADO I";
              else:
                if($imc>25):
                  $indice="SOBRE PESO";
                else:
                  if($imc>18):
                    $indice="PESO NORMAL";
                  else:
                    $indice="  ";
                  endif;
                endif;
              endif;
            endif;
        endif;

        $imc = number_format($imc, 2);

        if($ficha->sexo == 'M'):
          $sexo = 'MASCULINO';
        else:
          $sexo = 'FEMENINO';
        endif;
  
    $examen = $model_femaficha->VerFichaByIdFichaAndIdProcesoAndIdTipoExamen($id_ficha, $id_proceso, $id_tipo_examen);
    $examen = $examen[0];
   
    $model_medico = new Medico();
    $profesional = $model_medico->GetMedicoById2($examen->id_profesional);
    if($profesional->colegiatura == "0"):
      $profesional->colegiatura = ' ';
    endif;
   
   $establecimientos=DB::table('establecimientos')
   ->select('establecimientos.*')
   ->where('id',$profesional->id_establecimiento)
   ->first();

    $observaciones = $model_femaficha->GetObservacionesByIdFemaExamen($examen->id);

    $diagnosticos = $model_femaficha->GetDiagnosticosByIdFemaExamen($examen->id);

    return view('frontend.fema.anular_ficha', compact('ficha','sexo','imc','id_proceso','indice','establecimientos','id_tipo_examen','profesional','examen','observaciones','diagnosticos'));
  }

  public function anularfichacompleta($id_ficha, $id_proceso) {
    $model_femaficha = new Fema_ficha();

    $ficha = $model_femaficha->GetById($id_ficha);
    $ficha = $ficha[0];
   
    $imc=($ficha->peso_kg)/((($ficha->talla_cm)/100)*(($ficha->talla_cm)/100));

        if($imc>40):
          $indice="OBESIDAD GRADO III";
          else:
            if($imc>35):
              $indice="OBESIDAD GRADO II";
            else:
              if($imc>30):
                $indice="OBESIDAD GRADO I";
              else:
                if($imc>25):
                  $indice="SOBRE PESO";
                else:
                  if($imc>18):
                    $indice="PESO NORMAL";
                  else:
                    $indice="  ";
                  endif;
                endif;
              endif;
            endif;
        endif;

        $imc = number_format($imc, 2);

        if($ficha->sexo == 'M'):
          $sexo = 'MASCULINO';
        else:
          $sexo = 'FEMENINO';
        endif;

    return view('frontend.fema.anularficha_completa', compact('ficha','sexo','imc','id_proceso','indice'));
  }

  public function anularficha_triaje($id_ficha, $id_proceso) {
    $model_femaficha = new Fema_ficha();

    $ficha = $model_femaficha->GetById($id_ficha);
    $ficha = $ficha[0];
   
    $imc=($ficha->peso_kg)/((($ficha->talla_cm)/100)*(($ficha->talla_cm)/100));

        if($imc>40):
          $indice="OBESIDAD GRADO III";
          else:
            if($imc>35):
              $indice="OBESIDAD GRADO II";
            else:
              if($imc>30):
                $indice="OBESIDAD GRADO I";
              else:
                if($imc>25):
                  $indice="SOBRE PESO";
                else:
                  if($imc>18):
                    $indice="PESO NORMAL";
                  else:
                    $indice="  ";
                  endif;
                endif;
              endif;
            endif;
        endif;

        $imc = number_format($imc, 2);

        if($ficha->sexo == 'M'):
          $sexo = 'MASCULINO';
        else:
          $sexo = 'FEMENINO';
        endif;

    return view('frontend.fema.anularficha_triaje', compact('ficha','sexo','imc','id_proceso','indice'));
  }

  public function aperturaFicha()
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    else:
      if(Auth::user()->actualizo_passw==0):
        return redirect('auth/password/change');
      endif;
    endif;
    $id_usuario = Auth::user()->id;
    $establecimiento = Auth::user()->id_establecimiento;
  
    return view('frontend.fema.apertura_ficha', compact('id_usuario','establecimiento'));
  }

    public function GetValidacion2($id,$sexo){
      
        if($sexo=='M'){
            $cad = "SELECT 
             coalesce(sum(case 
              when fe.id_tipo_examen = 1 then 1
              when fe.id_tipo_examen = 3 then 1
              when fe.id_tipo_examen = 4 then 1
              when fe.id_tipo_examen = 8 then 1
              when fe.id_tipo_examen = 9 then 1
              else 0 end) ,0) cantidad_examenes 
              from fema_fichas ff
              inner join fema_examenes fe on ff.id = fe.id_fema_ficha and fe.estado = 1
              and ff.id = '$id'";
              $valides = DB::select($cad);
        }else{
          $cad = "SELECT 
            coalesce(sum(case 
              when fe.id_tipo_examen = 1 then 1
              when fe.id_tipo_examen = 3 then 1
              when fe.id_tipo_examen = 4 then 1
              when fe.id_tipo_examen = 6 then 1
              when fe.id_tipo_examen = 8 then 1
              when fe.id_tipo_examen = 9 then 1
              else 0 end) ,0) cantidad_examenes 
              from fema_fichas ff
              inner join fema_examenes fe on ff.id = fe.id_fema_ficha and fe.estado = 1
              and ff.id = '$id'";
              $valides = DB::select($cad);
              
        }
        
      $valides=$valides[0]->cantidad_examenes;
      
      return $valides;
        
    }

    public function GetValidacion($id,$sexo){
      
      if($sexo=='M'){
          $cad = "SELECT 
           coalesce(sum(case 
            when fe.id_tipo_examen = 1 then 1
            when fe.id_tipo_examen = 3 then 1
            when fe.id_tipo_examen = 4 then 1
            when fe.id_tipo_examen = 8 then 1
            when fe.id_tipo_examen = 9 then 1
            else 0 end) ,0) cantidad_examenes 
            from fema_fichas ff
            inner join fema_examenes fe on ff.id = fe.id_fema_ficha and fe.estado = 1
            and ff.id = '$id'";
            $valides = DB::select($cad);
      }else{
        $cad = "SELECT 
          coalesce(sum(case 
            when fe.id_tipo_examen = 1 then 1
            when fe.id_tipo_examen = 3 then 1
            when fe.id_tipo_examen = 4 then 1
            when fe.id_tipo_examen = 6 then 1
            when fe.id_tipo_examen = 8 then 1
            when fe.id_tipo_examen = 9 then 1
            else 0 end) ,0) cantidad_examenes 
            from fema_fichas ff
            inner join fema_examenes fe on ff.id = fe.id_fema_ficha and fe.estado = 1
            and ff.id = '$id'";
            $valides = DB::select($cad);
            
      }
      
    $valides=$valides[0]->cantidad_examenes;
    return $valides;
      
}

    public function GetValidacionfiltro2($id,$sexo){
      
      if($sexo=='M'){
          $cad = "SELECT 
           coalesce(sum(case 
            when fe.id_tipo_examen = 1 then 1
            when fe.id_tipo_examen = 3 then 1
            when fe.id_tipo_examen = 4 then 1
            when fe.id_tipo_examen = 8 then 1
            when fe.id_tipo_examen = 9 then 1
            else 0 end) ,0) cantidad_examenes 
            from fema_fichas ff
            inner join fema_examenes fe on ff.id = fe.id_fema_ficha and fe.estado = 1
            and ff.id = '$id'";
            $valides = DB::select($cad);
            $valides=$valides[0]->cantidad_examenes;
            if($valides>4){
              $a=1;
            }else{
              $a=0;
            }
      }else{
        $cad = "SELECT 
          coalesce(sum(case 
            when fe.id_tipo_examen = 1 then 1
            when fe.id_tipo_examen = 3 then 1
            when fe.id_tipo_examen = 4 then 1
            when fe.id_tipo_examen = 6 then 1
            when fe.id_tipo_examen = 8 then 1
            when fe.id_tipo_examen = 9 then 1
            else 0 end) ,0) cantidad_examenes 
            from fema_fichas ff
            inner join fema_examenes fe on ff.id = fe.id_fema_ficha and fe.estado = 1
            and ff.id = '$id'";
            $valides = DB::select($cad);
            $valides=$valides[0]->cantidad_examenes;
            if($valides>5){
              $a=1;
            }else{
              $a=0;
            }
      }

    return $a;
      
  }

  public function GetValidacionfiltro($id,$sexo){
      
    if($sexo=='M'){
        $cad = "SELECT 
         coalesce(sum(case 
          when fe.id_tipo_examen = 1 then 1
          when fe.id_tipo_examen = 3 then 1
          when fe.id_tipo_examen = 4 then 1
          when fe.id_tipo_examen = 8 then 1
          when fe.id_tipo_examen = 9 then 1
          else 0 end) ,0) cantidad_examenes 
          from fema_fichas ff
          inner join fema_examenes fe on ff.id = fe.id_fema_ficha and fe.estado = 1
          and ff.id = '$id'";
          $valides = DB::select($cad);
          $valides=$valides[0]->cantidad_examenes;
          if($valides>4){
            $a=1;
          }else{
            $a=0;
          }
    }else{
      $cad = "SELECT 
        coalesce(sum(case 
          when fe.id_tipo_examen = 1 then 1
          when fe.id_tipo_examen = 3 then 1
          when fe.id_tipo_examen = 4 then 1
          when fe.id_tipo_examen = 6 then 1
          when fe.id_tipo_examen = 8 then 1
          when fe.id_tipo_examen = 9 then 1
          else 0 end) ,0) cantidad_examenes 
          from fema_fichas ff
          inner join fema_examenes fe on ff.id = fe.id_fema_ficha and fe.estado = 1
          and ff.id = '$id'";
          $valides = DB::select($cad);
          $valides=$valides[0]->cantidad_examenes;
          if($valides>5){
            $a=1;
          }else{
            $a=0;
          }
    }
   
  return $a;
    
}


    public function GetValidacioncie10Legacy($nro_doc_ident,$id_ficha){
   
        $cd = "SELECT
        count(d.id)as cie10
        from fema_diagnosticos fd 
        inner join fema_examenes fe on fe.id = fd.id_fema_examen 
        inner join diagnosticos d on d.id = fd.id_diagnostico 
        inner join fema_fichas ff on ff.id = fe.id_fema_ficha 
        where ff.estado =1 and fe.estado = 1 and ff.nro_doc_ident = '$nro_doc_ident' and ff.id = '$id_ficha' and d.estado_fema = '1' and fd.flag_anula_epidemiologia is null";
        
      $a = DB::select($cd);
      $a=$a[0]->cie10;
      if($a>1){
        $a=1;
      }
    return $a;
      
  }

  public function GetValidacioncie10($nro_doc_ident,$id_ficha){
   
    $cd = "SELECT
    count(d.id)as cie10
    from fema_diagnosticos fd 
    inner join fema_examenes fe on fe.id = fd.id_fema_examen 
    inner join diagnosticos d on d.id = fd.id_diagnostico 
    inner join fema_fichas ff on ff.id = fe.id_fema_ficha 
    where ff.estado =1 and fe.estado = 1 and ff.nro_doc_ident = '$nro_doc_ident' and ff.id = '$id_ficha' and d.estado_fema = '1' and fd.flag_anula_epidemiologia is null";
      
    $a = DB::select($cd);
    $a=$a[0]->cie10;
    if($a>1){
      $a=1;
    }
    return $a;
  }

  
  public function Getatencionlaboratorio2($nro_doc_ident,$id_ficha){
    $cd = "SELECT
    count(codigo_laboratorio) as atencionlaboratorio  
      from fema_fichas ff
    where ff.estado =1 and ff.nro_doc_ident = '$nro_doc_ident' and ff.id = '$id_ficha'";
    $atencionlaboratorio = DB::select($cd);
    $atencionlaboratorio = $atencionlaboratorio[0]->atencionlaboratorio;
      return $atencionlaboratorio;
    }

    public function Getatencionlaboratorio($nro_doc_ident,$id_ficha){
      $cd = "SELECT
      count(codigo_laboratorio) as atencionlaboratorio  
        from fema_fichas ff
      where ff.estado =1 and ff.nro_doc_ident = '$nro_doc_ident' and ff.id = '$id_ficha'";
      $atencionlaboratorio = DB::select($cd);
      $atencionlaboratorio = $atencionlaboratorio[0]->atencionlaboratorio;
      return $atencionlaboratorio;
  }
  

    public function Getatencioncardiologia2($nro_doc_ident,$id_ficha){
      $cd = "SELECT
      count(codigo_cardiologia) as atencioncardiologia  
        from fema_fichas ff
      where ff.estado =1 and ff.nro_doc_ident = '$nro_doc_ident' and ff.id = '$id_ficha'";
      $atencioncardiologia = DB::select($cd);
      $atencioncardiologia = $atencioncardiologia[0]->atencioncardiologia;
        return $atencioncardiologia;
      }

      public function Getatencioncardiologia($nro_doc_ident,$id_ficha){
        $cd = "SELECT
        count(codigo_cardiologia) as atencioncardiologia  
          from fema_fichas ff
        where ff.estado =1 and ff.nro_doc_ident = '$nro_doc_ident' and ff.id = '$id_ficha'";
        $atencioncardiologia = DB::select($cd);
        $atencioncardiologia = $atencioncardiologia[0]->atencioncardiologia;
        
        return $atencioncardiologia;
    }
    

  public function Getatencionrespiratorio2($nro_doc_ident,$id_ficha){
    $cd = "SELECT
    count(codigo_respiratorio) as atencionrespiratorio  
      from fema_fichas ff
    where ff.estado =1 and ff.nro_doc_ident = '$nro_doc_ident' and ff.id = '$id_ficha'";
    $atencionrespiratorio = DB::select($cd);
    $atencionrespiratorio = $atencionrespiratorio[0]->atencionrespiratorio;
    return $atencionrespiratorio;
  }

  public function Getatencionrespiratorio($nro_doc_ident,$id_ficha){
    $cd = "SELECT
      count(codigo_respiratorio) as atencionrespiratorio  
        from fema_fichas ff
      where ff.estado =1 and ff.nro_doc_ident = '$nro_doc_ident' and ff.id = '$id_ficha'";
    $atencionrespiratorio = DB::select($cd);
    $atencionrespiratorio = $atencionrespiratorio[0]->atencionrespiratorio;
    return $atencionrespiratorio;
  }
      

  public function Getodontologica($nro_doc_ident,$id_ficha){

    $cd = "SELECT
    count(id_fema_ficha) as ficha_odontologica  
      from fema_examenes fe 
      inner join fema_fichas ff on ff.id = fe.id_fema_ficha 
    where ff.estado =1 and fe.estado = 1 and ff.nro_doc_ident = '$nro_doc_ident' and ff.id = '$id_ficha' and id_tipo_examen = '1'";
    $ficha_odontologica = DB::select($cd);
    $ficha_odontologica = $ficha_odontologica[0]->ficha_odontologica;
      return $ficha_odontologica;
    }

  public function Getlaboratorio($nro_doc_ident,$id_ficha){
    $cd = "SELECT
    count(id_fema_ficha) as ficha_laboratorio  
      from fema_examenes fe 
      inner join fema_fichas ff on ff.id = fe.id_fema_ficha 
    where ff.estado =1 and fe.estado = 1 and ff.nro_doc_ident = '$nro_doc_ident' and ff.id = '$id_ficha' and id_tipo_examen = '2'";
    $ficha_laboratorio = DB::select($cd);
    $ficha_laboratorio = $ficha_laboratorio[0]->ficha_laboratorio;
      return $ficha_laboratorio;
    }

  public function Getclinico($nro_doc_ident,$id_ficha){
    $cd = "SELECT
    count(id_fema_ficha) as ficha_clinico  
      from fema_examenes fe 
      inner join fema_fichas ff on ff.id = fe.id_fema_ficha 
    where ff.estado =1 and fe.estado = 1 and ff.nro_doc_ident = '$nro_doc_ident' and id_tipo_examen = '3' and ff.id = '$id_ficha'";
    $ficha_clinico = DB::select($cd);
    $ficha_clinico = $ficha_clinico[0]->ficha_clinico;
      return $ficha_clinico;
    }

  public function Getmental($nro_doc_ident,$id_ficha){
    $cd = "SELECT
    count(id_fema_ficha) as ficha_mental  
      from fema_examenes fe 
      inner join fema_fichas ff on ff.id = fe.id_fema_ficha 
    where ff.estado =1 and fe.estado = 1 and ff.nro_doc_ident = '$nro_doc_ident' and id_tipo_examen = '4' and ff.id = '$id_ficha'";
    $ficha_mental = DB::select($cd);
    $ficha_mental = $ficha_mental[0]->ficha_mental;
      return $ficha_mental;
    }

  public function Getrespiratorio($nro_doc_ident,$id_ficha){
      $cd = "SELECT
      count(id_fema_ficha) as ficha_respiratorio  
        from fema_examenes fe 
        inner join fema_fichas ff on ff.id = fe.id_fema_ficha 
      where ff.estado =1 and fe.estado = 1 and ff.nro_doc_ident = '$nro_doc_ident' and id_tipo_examen = '5' and ff.id = '$id_ficha'";
      $ficha_respiratorio = DB::select($cd);
      $ficha_respiratorio = $ficha_respiratorio[0]->ficha_respiratorio;
        return $ficha_respiratorio;
      }

  public function Getginecologico($nro_doc_ident,$id_ficha){
      $cd = "SELECT
      count(id_fema_ficha) as ficha_ginecologico  
        from fema_examenes fe 
        inner join fema_fichas ff on ff.id = fe.id_fema_ficha 
      where ff.estado =1 and fe.estado = 1 and ff.nro_doc_ident = '$nro_doc_ident' and id_tipo_examen = '6' and ff.id = '$id_ficha'";
      $ficha_ginecologico = DB::select($cd);
      $ficha_ginecologico = $ficha_ginecologico[0]->ficha_ginecologico;
        return $ficha_ginecologico;
      }

  public function Getcardiovascular($nro_doc_ident,$id_ficha){
      $cd = "SELECT
      count(id_fema_ficha) as ficha_cardiovascular  
        from fema_examenes fe 
        inner join fema_fichas ff on ff.id = fe.id_fema_ficha 
      where ff.estado =1 and fe.estado = 1 and ff.nro_doc_ident = '$nro_doc_ident' and id_tipo_examen = '7' and ff.id = '$id_ficha'";
      $ficha_cardiovascular = DB::select($cd);
      $ficha_cardiovascular = $ficha_cardiovascular[0]->ficha_cardiovascular;
        return $ficha_cardiovascular;
      }
    
  public function Getoftalmologia($nro_doc_ident,$id_ficha){
      $cd = "SELECT
      count(id_fema_ficha) as ficha_oftalmologia  
        from fema_examenes fe 
        inner join fema_fichas ff on ff.id = fe.id_fema_ficha 
      where ff.estado =1 and fe.estado = 1 and ff.nro_doc_ident = '$nro_doc_ident' and id_tipo_examen = '8' and ff.id = '$id_ficha'";
      $ficha_oftalmologia = DB::select($cd);
      $ficha_oftalmologia = $ficha_oftalmologia[0]->ficha_oftalmologia;
        return $ficha_oftalmologia;
      }

  public function Getotorrinolaringologia($nro_doc_ident,$id_ficha){
        $cd = "SELECT
        count(id_fema_ficha) as ficha_otorrinolaringologia  
          from fema_examenes fe 
          inner join fema_fichas ff on ff.id = fe.id_fema_ficha 
        where ff.estado =1 and fe.estado = 1 and ff.nro_doc_ident = '$nro_doc_ident' and id_tipo_examen = '9' and ff.id = '$id_ficha'";
        $ficha_otorrinolaringologia = DB::select($cd);
        $ficha_otorrinolaringologia = $ficha_otorrinolaringologia[0]->ficha_otorrinolaringologia;
          return $ficha_otorrinolaringologia;
        }

        public function epidemiologia($f){

          if (!Auth::check()) :
            return redirect('/auth/login');
          endif;
    
          $personal=DB::table('fema_fichas')
                      ->select('fema_fichas.*')
                      ->where('id',$f)
                      ->where('estado',1)
                      ->first();
    
          $situacion=DB::table('situaciones')
                      ->select('situaciones.*')
                      ->where('id',$personal->id_situacion_pnp)
                      ->first();       
           
          $model_medico = new Medico();
     $profesional=DB::table('users')
      ->select('users.*')
      ->where('dni',Auth::user()->dni)
      ->first();
  //  $profesional = $model_medico->GetDatosMedicoAndEstablecimiento2(Auth::user()->dni, Auth::user()->id_establecimiento);
/*
    if(count($profesional)>0):
      $profesional = $profesional[0];
    else:
      Session::flash('flash_message', 'Debe ser médico de este establecimiento');
      return redirect('/dashboard');
    endif;
*/ 
            
          $subprocesos=DB::table('fema_tipo_procesos')
               ->select('fema_tipo_procesos.*')
               ->where('id',$personal->id_fema_tipo_proceso)
               ->where('estado',1)
               ->first(); 
         
          $procesos=DB::table('fema_procesos')
               ->select('fema_procesos.*')
               ->where('id',$subprocesos->id_proceso)
               ->where('estado',1)
               ->first();
    
          $triaje=DB::table('fema_triajes')
               ->select('fema_triajes.*')
               ->where('id_fema_ficha',$personal->id)
               ->where('estado',1)
               ->first();
    
          $cad= "SELECT
           fd.id,
           d.codigo,
           d.nombre,
           d.estado_fema
          from fema_diagnosticos fd 
          inner join fema_examenes fe on fe.id = fd.id_fema_examen 
          inner join diagnosticos d on d.id = fd.id_diagnostico 
          inner join fema_fichas ff on ff.id = fe.id_fema_ficha 
          where ff.estado =1 and fe.estado = 1 and ff.id = '$f'";
          $nomdiag = DB::select($cad);
    
          $verttatu="SELECT 
                count(ff.id) as tatuaje 
                from fema_fichas ff 
                inner join fema_examenes fe on fe.id_fema_ficha = ff.id 
                inner join fema_diagnosticos fd on fd.id_fema_examen = fe.id
                inner join diagnosticos d on d.id = fd.id_diagnostico 
                inner join consultorios c on c.id  = fd.id_servicio_derivacion
                where ff.estado =1 and fe.estado = 1 and ff.id = '$f' and d.estado_fema = '1' and d.codigo = 'L81.8'";
                $verttatu=DB::select($verttatu);
                $verttatu=$verttatu[0]->tatuaje;
    
          $cadd= "SELECT 
          c.id,
          c.nombre
          from consultorios c where tipo = '1' and id != '240' ";
    
          $nomcon = DB::select($cadd);
          
          $date = new Carbon();
    
          $imc=($triaje->peso_kg)/((($triaje->talla_cm)/100)*(($triaje->talla_cm)/100));
    
            if($imc>40):
              $indice="OBESIDAD GRADO III";
              else:
                if($imc>35):
                  $indice="OBESIDAD GRADO II";
                else:
                  if($imc>30):
                    $indice="OBESIDAD GRADO I";
                  else:
                    if($imc>25):
                      $indice="SOBRE PESO";
                    else:
                      if($imc>18):
                        $indice="PESO NORMAL";
                      else:
                        $indice="  ";
                      endif;
                    endif;
                  endif;
                endif;
            endif;
    
            $imc = number_format($imc, 2);
           
          return view('frontend.fema.epidemiologia',compact('profesional','situacion','imc','indice','personal','procesos','subprocesos','date','triaje','nomdiag','profesiones','nomcon','verttatu'));
    
        }
    
    public function Getyaderivado2($nro_documento,$id_ficha){

      $veri= "SELECT
      count(fd.derivado_us) yaderivado
      from fema_fichas ff 
      inner join fema_examenes fe on fe.id_fema_ficha  = ff.id 
      inner join fema_diagnosticos fd on fd.id_fema_examen = fe.id
      where ff.estado =1 and fe.estado = 1 and ff.id = '$id_ficha'";
          $ver = DB::select($veri);
          $ver=$ver[0]->yaderivado;
          return ($ver);
    }

    public function Getyaderivado($nro_documento, $id_ficha) {

      $veri = "SELECT
      count(fd.derivado_us) yaderivado
      from fema_fichas ff 
      inner join fema_examenes fe on fe.id_fema_ficha  = ff.id 
      inner join fema_diagnosticos fd on fd.id_fema_examen = fe.id
      where ff.estado =1 and fe.estado = 1 and ff.id = '$id_ficha'";

      $ver = DB::select($veri);
      $ver = $ver[0]->yaderivado;
      return $ver;
  }

    public function validar_fema_sub($nro_documento){
      
      if (!Auth::check()) :
        return redirect('/auth/login');
      endif;

      $personal=DB::table('fema_fichas')
                  ->select('fema_fichas.*')
                  ->where('nro_doc_ident',$nro_documento)
                  ->first();

          return ($personal);
    } 

    public function derivacion_epidemiologia(Request $request){
      
      if (!Auth::check()) :
        return redirect('/auth/login');
      endif;
      
      $dato= json_decode($request->id_diagnostico);
      
        $derivo = "SELECT 
        fd.id
        from fema_diagnosticos fd 
         inner join fema_examenes fe on fe.id = fd.id_fema_examen 
         inner join diagnosticos d on d.id = fd.id_diagnostico 
         inner join fema_fichas ff on ff.id = fe.id_fema_ficha 
         where ff.estado =1 and fe.estado = 1 and ff.id = '$request->id_prestacion' and d.estado_fema = '1'";
          $der = DB::select($derivo);
          $i=0;
          foreach($der as $d){
           
            $model_fema_diagnostico = \App\Models\Fema_diagnostico::find($d->id);
            $model_fema_diagnostico->derivado_us = Auth::user()->id;
            $model_fema_diagnostico->derivado_at = Carbon::now();

            $model_fema_diagnostico->id_servicio_derivacion = $dato[$i]->valor;
            $model_fema_diagnostico->save();
            $i++;
          };

          $detatu = "SELECT 
          count(fd.id) as dtatu
          from fema_diagnosticos fd 
           inner join fema_examenes fe on fe.id = fd.id_fema_examen 
           inner join diagnosticos d on d.id = fd.id_diagnostico 
           inner join fema_fichas ff on ff.id = fe.id_fema_ficha 
           where ff.estado =1 and fe.estado = 1 and  ff.id = '$request->id_prestacion' and d.estado_fema = '1' and d.codigo = 'L81.8'";
            $detatu = DB::select($detatu);
            $detatu = $detatu[0]->dtatu;
            
            if ($detatu == '0') :
             
            else:
              if (count($dato)>1){
                $dtatu = "SELECT 
                fd.id
                from fema_diagnosticos fd 
                inner join fema_examenes fe on fe.id = fd.id_fema_examen 
                inner join diagnosticos d on d.id = fd.id_diagnostico 
                inner join fema_fichas ff on ff.id = fe.id_fema_ficha 
                where ff.estado =1 and fe.estado = 1 and ff.id = '$request->id_prestacion' and d.estado_fema = '1' and d.codigo = 'L81.8'";
                  $dtatu = DB::select($dtatu);
                  $dtatu = $dtatu[0]->id;

                  $model_fema_diagnostico = \App\Models\Fema_diagnostico::find($dtatu);
                  $model_fema_diagnostico->derivado_us = Auth::user()->id;
                  $model_fema_diagnostico->derivado_at = Carbon::now();
      
                  $model_fema_diagnostico->id_servicio_derivacion2 = $request->derivacion2;
                  $model_fema_diagnostico->save();
                   
                  $ds=" SELECT
                  ff.id 
                  from fema_fichas ff 
                  where ff.estado =1  and ff.id = '$request->id_prestacion'";
                  $ds = DB::select($ds);
                  $ds=$ds[0];
              
                  $model_ficha_fema_envio = \App\Models\Fema_ficha::find($ds->id);
                  $model_ficha_fema_envio->flag_estado   = "4";
                  $model_ficha_fema_envio->flag_derivado   = "4";
                  $model_ficha_fema_envio->save();
                  
                }
            endif;
            
          return redirect('/busqueda_fema_ficha');
    
    } 

    public function cierre_ficha()
  {
    if (!Auth::check()) :
        return redirect('/auth/login');
    else:
      if(Auth::user()->actualizo_passw==0):
        return redirect('auth/password/change');
      endif;
    endif;

    $modelFemaFicha = new Fema_ficha;
    $date = Carbon::now();
    $fechaDesde = $date->format('d-m-Y');
    $fechaHasta = $date->format('d-m-Y');
    $nroDocumento = "";
    $idProceso = "";
    $idSubProceso = "";
    $nroFicha = "";
    
    $variable=1;
    return view('frontend.fema.cierre_ficha', compact('fichas','fechaDesde','fechaHasta','nroDocumento','idProceso','idSubProceso','nroFicha','variable'));
  }

  public function busqueda_fema_cierre() {
    if (!Auth::check()):
      return redirect('/auth/login');
    endif;

    $modelFemaFicha = new Fema_ficha;
    $date = Carbon::now();
    $fechaDesde = $date->format('d-m-Y');
    $fechaHasta = $date->format('d-m-Y');
    $nroDocumento = "";
    $idProceso = "";
    $idSubProceso = "";
    $nroFicha = "";
    $id_establecimiento = Auth::user()->id_establecimiento;
    if($id_establecimiento==76){ 
      $id_establecimiento = 0;
    }
    $fichas = $modelFemaFicha->GetAllcierre($fechaDesde, $fechaHasta, $nroDocumento, $id_establecimiento);
     dd($fichas);
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->validacion = $this->GetValidacion($fichas[$i]->id,$fichas[$i]->sexo);
    }
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->cie10 = $this->GetValidacioncie10($fichas[$i]->nro_doc_ident,$fichas[$i]->id);
    }
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->yaderivado = $this->Getyaderivado($fichas[$i]->nro_doc_ident,$fichas[$i]->id);
    } 
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->tatuaje = $this->Gettatuaje($fichas[$i]->nro_doc_ident);
    }
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->levantartatuaje = $this->Getlevantartatu($fichas[$i]->nro_doc_ident);
    }
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->validacionfiltro = $this->GetValidacionfiltro($fichas[$i]->id,$fichas[$i]->sexo);
    }
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->atencionlaboratorio = $this->Getatencionlaboratorio($fichas[$i]->nro_doc_ident,$fichas[$i]->id);
    }
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->atencioncardiologia = $this->Getatencioncardiologia($fichas[$i]->nro_doc_ident,$fichas[$i]->id);
    }
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->atencionrespiratorio = $this->Getatencionrespiratorio($fichas[$i]->nro_doc_ident,$fichas[$i]->id);
    }
    $variable=2;
    return view('frontend.fema.cierre_ficha', compact('fichas','fechaDesde','fechaHasta','nroDocumento','idProceso','idSubProceso','nroFicha','variable'));
  }

  public function busca_fema_cierre(Request $request) {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $modelFemaFicha = new Fema_fichaReporte();
    $fechaDesde = $request->fechaDesde;
    $fechaHasta = $request->fechaHasta;
    $nroDocumento = $request->nroDocumento;
    $idProceso = $request->idProceso;
    $idSubProceso = $request->idSubProceso;
    $nroFicha = $request->nroFicha;
    $id_establecimiento = Auth::user()->id_establecimiento;
    if($id_establecimiento==76){ 
      $id_establecimiento = 0;
    }
    $fichas = $modelFemaFicha->GetAllcierre($fechaDesde, $fechaHasta, $nroDocumento, $id_establecimiento);
 
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->validacion = $this->GetValidacion($fichas[$i]->id,$fichas[$i]->sexo);
    }
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->cie10 = $this->GetValidacioncie10($fichas[$i]->nro_doc_ident,$fichas[$i]->id);
    } 
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->yaderivado = $this->Getyaderivado($fichas[$i]->nro_doc_ident,$fichas[$i]->id);
    } 
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->tatuaje = $this->Gettatuaje($fichas[$i]->nro_doc_ident,$fichas[$i]->id);
    }
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->levantartatuaje = $this->Getlevantartatu($fichas[$i]->nro_doc_ident,$fichas[$i]->id);
    }
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->validacionfiltro = $this->GetValidacionfiltro($fichas[$i]->id,$fichas[$i]->sexo);
    }
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->atencionlaboratorio = $this->Getatencionlaboratorio($fichas[$i]->nro_doc_ident,$fichas[$i]->id);
    }
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->atencioncardiologia = $this->Getatencioncardiologia($fichas[$i]->nro_doc_ident,$fichas[$i]->id);
    }
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->atencionrespiratorio = $this->Getatencionrespiratorio($fichas[$i]->nro_doc_ident,$fichas[$i]->id);
    }
    $variable=2;

    return view('frontend.fema.cierre_ficha', compact('fichas','fechaDesde','fechaHasta','nroDocumento','idProceso','idSubProceso','nroFicha','variable'));
  }

  public function Gettatuaje2($nro_documento){
    $date = Carbon::now();
    $fechaActual = $date->format('Y');
    $veri= "SELECT
    count(d.nombre) tienetatu
    from fema_fichas ff 
    inner join fema_examenes fe on fe.id_fema_ficha  = ff.id 
    inner join fema_diagnosticos fd on fd.id_fema_examen = fe.id
    inner join diagnosticos d on d.id = fd.id_diagnostico 
    where ff.estado =1 and fe.estado = 1 and ff.dato_year_ficha ='$fechaActual' and ff.nro_doc_ident = '$nro_documento' and d.codigo  = 'L81.8'";
        $tatuaje = DB::select($veri);
        $tatuaje=$tatuaje[0]->tienetatu;
        if($tatuaje>1){
          $tatuaje='1';
        }
        return ($tatuaje);

  } 

  public function Gettatuaje($nro_documento) {
    $date = Carbon::now();
    $fechaActual = $date->format('Y');

    $veri = "SELECT
    count(d.nombre) tienetatu
    from fema_fichas ff 
    inner join fema_examenes fe on fe.id_fema_ficha = ff.id 
    inner join fema_diagnosticos fd on fd.id_fema_examen = fe.id
    inner join diagnosticos d on d.id = fd.id_diagnostico 
    where ff.estado = 1 and fe.estado = 1 
    and ff.dato_year_ficha = '$fechaActual' 
    and ff.nro_doc_ident = '$nro_documento' 
    and d.codigo = 'L81.8'";

    $tatuaje = DB::select($veri);
    $tatuaje = $tatuaje[0]->tienetatu;
    if ($tatuaje > 1) {
        $tatuaje = '1';
    }

    return $tatuaje;
}

   public function Getlevantartatu2($nro_documento){
    $date = Carbon::now();
    $fechaActual = $date->format('Y');
    $veri= "SELECT
    count(fd.id_servicio) levantartatu
    from fema_fichas ff 
    inner join fema_examenes fe on fe.id_fema_ficha  = ff.id 
    inner join fema_diagnosticos fd on fd.id_fema_examen = fe.id
    inner join diagnosticos d on d.id = fd.id_diagnostico 
    where ff.estado =1 and fe.estado = 1 and ff.dato_year_ficha ='$fechaActual' and ff.nro_doc_ident = '$nro_documento' and d.codigo  = 'L81.8'";
        $levantartatu = DB::select($veri);
        $levantartatu=$levantartatu[0]->levantartatu;
        if($levantartatu>1){
          $levantartatu='1';
        }
        return ($levantartatu);

  }

  public function Getlevantartatu($nro_documento) {
    $date = Carbon::now();
    $fechaActual = $date->format('Y');

    $veri = "SELECT
    count(fd.id_servicio) levantartatu
    from fema_fichas ff 
    inner join fema_examenes fe on fe.id_fema_ficha = ff.id 
    inner join fema_diagnosticos fd on fd.id_fema_examen = fe.id
    inner join diagnosticos d on d.id = fd.id_diagnostico 
    where ff.estado = 1 and fe.estado = 1 
    and ff.dato_year_ficha = '$fechaActual' 
    and ff.nro_doc_ident = '$nro_documento' 
    and d.codigo = 'L81.8'";

    $levantartatu = DB::select($veri);
    $levantartatu = $levantartatu[0]->levantartatu;

    if ($levantartatu > 1) {
        $levantartatu = '1';
    }

    return ($levantartatu);
}


  public function verderivacion($f){
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $personal=DB::table('fema_fichas')
    ->select('fema_fichas.*')
    ->where('id',$f)
    ->where('estado',1)
    ->first();

    $model_medico = new Medico();
     $profesional=DB::table('users')
      ->select('users.*')
      ->where('dni',Auth::user()->dni)
      ->first();
  //  $profesional = $model_medico->GetDatosMedicoAndEstablecimiento2(Auth::user()->dni, Auth::user()->id_establecimiento);
/*
    if(count($profesional)>0):
      $profesional = $profesional[0];
    else:
      Session::flash('flash_message', 'Debe ser médico de este establecimiento');
      return redirect('/dashboard');
    endif;
*/
  
    $local=DB::table('establecimientos')
    ->select('establecimientos.*')
    ->where('id',$profesional->id_establecimiento)
    ->first(); 

    $subprocesos=DB::table('fema_tipo_procesos')
    ->select('fema_tipo_procesos.*')
    ->where('id',$personal->id_fema_tipo_proceso)
    ->where('estado',1)
    ->first(); 

    $procesos=DB::table('fema_procesos')
    ->select('fema_procesos.*')
    ->where('id',$subprocesos->id_proceso)
    ->where('estado',1)
    ->first();

    $triaje=DB::table('fema_triajes')
    ->select('fema_triajes.*')
    ->where('id_fema_ficha',$personal->id)
    ->where('estado',1)
    ->first();
    
    $imc=($triaje->peso_kg)/((($triaje->talla_cm)/100)*(($triaje->talla_cm)/100));

    if($imc>40):
      $indice="OBESIDAD GRADO III";
      else:
        if($imc>35):
          $indice="OBESIDAD GRADO II";
        else:
          if($imc>30):
            $indice="OBESIDAD GRADO I";
          else:
            if($imc>25):
              $indice="SOBRE PESO";
            else:
              if($imc>18):
                $indice="PESO NORMAL";
              else:
                $indice="  ";
              endif;
            endif;
          endif;
        endif;
    endif;
    $imc = number_format($imc, 2);

    $cade= "SELECT
    count(fd.id) as existederivacion1
    from fema_diagnosticos fd 
    inner join fema_examenes fe on fe.id = fd.id_fema_examen 
    inner join diagnosticos d on d.id = fd.id_diagnostico 
    inner join fema_fichas ff on ff.id = fe.id_fema_ficha 
    inner join consultorios c on c.id = fd.id_servicio_derivacion 
    where ff.estado =1 and fe.estado = 1 and ff.id = '$f'";//6
    $existederivacion1 = DB::select($cade);
    $existederivacion1 = $existederivacion1[0]->existederivacion1;
    
    $cas = "SELECT
    count(fd.id) as existederivacion2 
    from fema_diagnosticos fd 
    inner join fema_examenes fe on fe.id = fd.id_fema_examen 
    inner join diagnosticos d on d.id = fd.id_diagnostico 
    inner join fema_fichas ff on ff.id = fe.id_fema_ficha 
    inner join consultorios c on c.id = fd.id_servicio_derivacion
    inner join consultorios c2 on c2.id = fd.id_servicio_derivacion2 
    where ff.estado =1 and fe.estado = 1 and ff.id = '$f'";//0
    $existederivacion2 = DB::select($cas);
    $existederivacion2 = $existederivacion2[0]->existederivacion2;
    
    /******* ojo aqui use eldato flag_firma para poder poner como null el nombre consultorio 2********/
    if($existederivacion1 == 0):
      $dato=0;
    else:
      if($existederivacion2 == 0):
   
        $car= "SELECT
        fd.id,
        d.codigo,
        d.nombre,
        fd.derivado_at,
        d.estado_fema,
        fd.id_servicio_derivacion,
        c.nombre as nombre_consultorio1,
        ff.flag_firma  as nombre_consultorio2,
        fd.derivado_us
        from fema_diagnosticos fd 
        inner join fema_examenes fe on fe.id = fd.id_fema_examen 
        inner join diagnosticos d on d.id = fd.id_diagnostico 
        inner join fema_fichas ff on ff.id = fe.id_fema_ficha 
        inner join consultorios c on c.id = fd.id_servicio_derivacion
        where ff.estado =1 and fe.estado = 1 and ff.id = '$f'";
        $nomdiag = DB::select($car);
    
	      $dato=$nomdiag[0]->derivado_at;
      else:
        
        $casi= "SELECT
          fd.id,
          d.codigo,
          d.nombre,
          fd.derivado_at,
          d.estado_fema,
          fd.id_servicio_derivacion,
          fd.id_servicio_derivacion2,
          c.nombre as nombre_consultorio1,
          c2.nombre as nombre_consultorio2,
          fd.derivado_us
          from fema_diagnosticos fd 
          inner join fema_examenes fe on fe.id = fd.id_fema_examen 
          inner join diagnosticos d on d.id = fd.id_diagnostico 
          inner join fema_fichas ff on ff.id = fe.id_fema_ficha 
          inner join consultorios c on c.id = fd.id_servicio_derivacion
          inner join consultorios c2 on c2.id = fd.id_servicio_derivacion2 
          where ff.estado =1 and fe.estado = 1 and ff.id = '$f'";
        $nomdiag = DB::select($casi);

        $adicionar="SELECT
        d.id,
        d.codigo,
        d.nombre,
        fe.id_tipo_examen,
        c.nombre as nombre_consultorio1,
        d.estado_fema,
        fd.derivado_us
        from fema_diagnosticos fd 
        inner join fema_examenes fe on fe.id = fd.id_fema_examen 
        inner join diagnosticos d on d.id = fd.id_diagnostico 
        inner join fema_fichas ff on ff.id = fe.id_fema_ficha 
        inner join consultorios c on c.id = fd.id_servicio_derivacion
        where ff.estado =1 and fe.estado = 1 and ff.id = '$f' and d.estado_fema = '1' and d.codigo !='L81.8'";
        $adicionar= DB::select($adicionar);
       
        for ($i =0; $i < count($adicionar); $i++){
          $adicionar[$i]->nombre_consultorio2 =0;
          $adicionar[$i]->id_servicio_derivacion2=0;
        }
        
        $nomdiag=array_merge($nomdiag,$adicionar);
   
        $dato=$nomdiag[0]->derivado_at;
      endif;
    endif;
    $idusuario = $nomdiag[0]->derivado_us;
    $derivador = "SELECT * from users where id = '$idusuario'";
    $derivador = DB::select($derivador);
    $derivador = $derivador[0];
   
    return view('frontend.fema.epidemiologia_cierre',compact('profesional','imc','indice','local','dato','personal','procesos','subprocesos','triaje','nomdiag','profesiones','existederivacion2','derivador'));
  }
  public function levantarobservacion($f){
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $personal=DB::table('fema_fichas')
    ->select('fema_fichas.*')
    ->where('id',$f)
    ->where('estado',1)
    ->first();

    $model_medico = new Medico();
     // $profesional = $model_medico->GetDatosMedicoAndEstablecimiento2(Auth::user()->dni, Auth::user()->id_establecimiento);
  $profesional=DB::table('users')
      ->select('users.*')
      ->where('dni',Auth::user()->dni)
      ->first();

    /*  if(count($profesional)>0):
        $profesional = $profesional[0];
      else:
      Session::flash('flash_message', 'Debe ser médico de este establecimiento');
        return redirect('/dashboard');
      endif;
*/      

    $subprocesos=DB::table('fema_tipo_procesos')
    ->select('fema_tipo_procesos.*')
    ->where('id',$personal->id_fema_tipo_proceso)
    ->where('estado',1)
    ->first(); 

    $procesos=DB::table('fema_procesos')
    ->select('fema_procesos.*')
    ->where('id',$subprocesos->id_proceso)
    ->where('estado',1)
    ->first();

    $triaje=DB::table('fema_triajes')
    ->select('fema_triajes.*')
    ->where('id_fema_ficha',$personal->id)
    ->where('estado',1)
    ->first();

    $cad= "SELECT
      fd.id,
      d.codigo,
      d.nombre,
      d.estado_fema,
      fd.id_servicio_derivacion ,
      fd.id_servicio_derivacion2,
      c.nombre as nombre_consultorio1,
      c2.nombre as nombre_consultorio2
      from fema_diagnosticos fd 
      inner join fema_examenes fe on fe.id = fd.id_fema_examen 
      inner join diagnosticos d on d.id = fd.id_diagnostico 
      inner join fema_fichas ff on ff.id = fe.id_fema_ficha 
      inner join consultorios c on c.id = fd.id_servicio_derivacion
      inner join consultorios c2 on c2.id = fd.id_servicio_derivacion2 
      where ff.estado =1 and fe.estado = 1 and ff.nro_doc_ident = '$f'";
    $nomdiag = DB::select($cad);

$establecimiento=DB::table('establecimientos')
        ->select('establecimientos.*')
        ->where('id',$profesional->id_establecimiento)
        ->first();

    $loca="SELECT
    e.id,
    e.nombre
    from establecimientos e";
    $local = DB::select($loca);
    
    $espe="SELECT
    c.id, 
    c.nombre  
    from consultorios c 
    where tipo = '1'";
    $servicio = DB::select($espe);
  $date = new Carbon();    
return view('frontend.fema.levantar_obs', compact('personal','profesional','establecimiento','servicio','subprocesos','procesos','razas','date','triaje','nomdiag','local'));

  }

  public function levantar_observacion(Request $request) {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $d=" SELECT
    fd.id 
    from fema_fichas ff 
    inner join fema_examenes fe on fe.id_fema_ficha  = ff.id 
    inner join fema_diagnosticos fd on fd.id_fema_examen = fe.id
    inner join diagnosticos d on d.id = fd.id_diagnostico 
    where ff.estado =1 and fe.estado = 1 and ff.id = '$request->id_prestacion' and d.codigo  = 'L81.8'";
    $id = DB::select($d);
    $id=$id[0];

    $modelFemaFicha = new Fema_ficha;
    $date = Carbon::now();
    $fechaDesde = $date->format('d-m-Y');
    $fechaHasta = $date->format('d-m-Y');
    $nroDocumento = "";
    $idProceso = "";
    $idSubProceso = "";
    $nroFicha = "";
    $fichas = $modelFemaFicha->GetAll($fechaDesde, $fechaHasta, $nroDocumento, $idProceso, $idSubProceso, $nroFicha);
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->validacion = $this->GetValidacion($fichas[$i]->id,$fichas[$i]->sexo);
    }
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->cie10 = $this->GetValidacioncie10($fichas[$i]->nro_doc_ident,$fichas[$i]->id);
    }
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->yaderivado = $this->Getyaderivado($fichas[$i]->nro_doc_ident,$fichas[$i]->id);
    } 
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->tatuaje = $this->Gettatuaje($fichas[$i]->nro_doc_ident,$fichas[$i]->id);
    }
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->levantartatuaje = $this->Getlevantartatu($fichas[$i]->nro_doc_ident);
    }
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->validacionfiltro = $this->GetValidacionfiltro($fichas[$i]->id,$fichas[$i]->sexo);
    }
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->atencionlaboratorio = $this->Getatencionlaboratorio($fichas[$i]->nro_doc_ident,$fichas[$i]->id);
    }
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->atencioncardiologia = $this->Getatencioncardiologia($fichas[$i]->nro_doc_ident,$fichas[$i]->id);
    }
    for ($i =0; $i < count($fichas); $i++) {
      $fichas[$i]->atencionrespiratorio = $this->Getatencionrespiratorio($fichas[$i]->nro_doc_ident,$fichas[$i]->id);
    }
    $model_fema_diagnostico = \App\Models\Fema_diagnostico::find($id->id);
    $model_fema_diagnostico->derivado_us = Auth::user()->id;
    $model_fema_diagnostico->derivado_at = Carbon::now();
    $model_fema_diagnostico->id_establecimiento   = $request->localle!= '' ? $request->localle : "";
    $model_fema_diagnostico->fecha_evaluacion   = $request->fechaeva!= '' ? $request->fechaeva : "";
    $model_fema_diagnostico->id_servicio   = $request->serviciole!= '' ? $request->serviciole : "";
    $model_fema_diagnostico->paterno_evaluador   = $request->paternodoc!= '' ? $request->paternodoc : "";
    $model_fema_diagnostico->materno_evaluador   = $request->maternodoc!= '' ? $request->maternodoc : "";
    $model_fema_diagnostico->nombre_evaluador   = $request->nombredoc!= '' ? $request->nombredoc : "";
    $model_fema_diagnostico->estado_motivo_constancia   = $request->etapatratamiento!= '' ? $request->etapatratamiento : "";
    $model_fema_diagnostico->observacion   = $request->obslevantar!= '' ? $request->obslevantar : "";
    
    $model_fema_diagnostico->save();

    $ds=" SELECT
    ff.id 
    from fema_fichas ff 
    where ff.estado =1  and ff.nro_doc_ident = '$request->nro_doc_ident' order by created_at ASC ";
    $ds = DB::select($ds);
    $ds=$ds[0];

    $model_ficha_fema_envio = \App\Models\Fema_ficha::find($ds->id);
    $model_ficha_fema_envio->flag_estado   = "5";
    $model_ficha_fema_envio->save();
    
    return redirect('/cierre_ficha');
  }

  public function cierre($f){
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    
    $personal=DB::table('fema_fichas')
    ->select('fema_fichas.*')
    ->where('id',$f)
    ->where('estado',1)
    ->first();

    $localff=" SELECT
    e.id,
    e.nombre
    from establecimientos e
    where e.id = '$personal->id_establecimiento'";
    $localff = DB::select($localff);
    $localff=$localff[0];
  
    $model_medico = new Medico();
     $profesional=DB::table('users')
      ->select('users.*')
      ->where('dni',Auth::user()->dni)
      ->first();
  //  $profesional = $model_medico->GetDatosMedicoAndEstablecimiento2(Auth::user()->dni, Auth::user()->id_establecimiento);
/*
    if(count($profesional)>0):
      $profesional = $profesional[0];
    else:
      Session::flash('flash_message', 'Debe ser médico de este establecimiento');
      return redirect('/dashboard');
    endif;
*/
    $loca=" SELECT
    e.id,
    e.nombre
    from establecimientos e
    where e.id = '$profesional->id_establecimiento'";
    $local = DB::select($loca);
    $local=$local[0];
    
    $subprocesos=DB::table('fema_tipo_procesos')
    ->select('fema_tipo_procesos.*')
    ->where('id',$personal->id_fema_tipo_proceso)
    ->where('estado',1)
    ->first(); 

    $procesos=DB::table('fema_procesos')
    ->select('fema_procesos.*')
    ->where('id',$subprocesos->id_proceso)
    ->where('estado',1)
    ->first();

    $triaje=DB::table('fema_triajes')
    ->select('fema_triajes.*')
    ->where('id_fema_ficha',$personal->id)
    ->where('estado',1)
    ->first();

    $locales=DB::table('establecimientos')
    ->select('establecimientos.*')
    ->where('id',$personal->id_establecimiento)
    ->first(); 
    
    $date = Carbon::now();

    $cerrado = $personal->cierre_created_us;

    return view('frontend.fema.cerrarficha',compact('profesional','cerrado','locales','personal','procesos','subprocesos','triaje','local','localff','date'));
  }

  public function buscafemageneral($f){
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $year = date('Y');
    
    $d=" SELECT 
    count(ff.id) as fema_general
     from fema_fichas ff 
     inner join fema_tipo_procesos ftp on ftp.id = ff.id_fema_tipo_proceso 
     where ff.estado =1 and ff.nro_doc_ident = '$f' and ftp.id_proceso = '1' and EXTRACT(YEAR FROM ff.created_at) = '$year'";
    $d = DB::select($d);
   
    return $d;
  }

  public function buscafichaabierta($f){
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $year = date('Y');

    $d="SELECT 
    ftp.nombre ,ff.created_at, ff.id_fema_tipo_proceso, e.nombre as establecimientos
    from fema_fichas ff 
    inner join fema_tipo_procesos ftp on ftp.id = ff.id_fema_tipo_proceso 
    inner join establecimientos e on e.id = ff.id_establecimiento 
    where ff.estado =1 and ff.nro_doc_ident = '$f' and EXTRACT(YEAR FROM ff.created_at) = '$year' order by ff.created_at ASC";
    $id = DB::select($d);
   
    return $id;
  }

  public function final_fema(Request $request){
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $personal=DB::table('fema_fichas')
    ->select('fema_fichas.*')
    ->where('id',$request->id_prestacion)
    ->where('estado','1')
    ->first();
 
    $model_ficha_fema_envio = \App\Models\Fema_ficha::find($personal->id);
    $model_ficha_fema_envio->cierre_updated_at                      = $request->fecha_cierre != '' ? $request->fecha_cierre : "";
    $model_ficha_fema_envio->flag_estado   = "6";
    $model_ficha_fema_envio->flag_cierre   = "1";
    $model_ficha_fema_envio->establecimiento_cierre = "$request->local_cierre";
    $model_ficha_fema_envio->cierre_created_us = Auth::user()->id;

    $model_ficha_fema_envio->save();
    return redirect('/cierre_ficha');
    
  }

  public function all_aperturas_fema($id = 0) {
    if (!Auth::check()) :
        return redirect('/auth/login');
    else:
      if(Auth::user()->actualizo_passw==0):
        return redirect('auth/password/change');
      endif;
    endif;
    $modelFemaFicha = new Fema_ficha;
    $date = Carbon::now();
    $fechaDesde = $date->format('d-m-Y');
    $fechaHasta = $date->format('d-m-Y');
    $nroDocumento = "";
    $idProceso = "";
    $idSubProceso = "";
    $nroFicha = "";
    $id_establecimiento = Auth::user()->id_establecimiento;
    if($id_establecimiento==76){ 
      $id_establecimiento = 0;
    }
    $fichas = $modelFemaFicha->GetAllFema1($fechaDesde, $fechaHasta, $nroDocumento ,$id_establecimiento);
    
    return view('frontend.fema.mostrar_aperturas', compact('fichas','fechaDesde','fechaHasta','nroDocumento','idProceso','idSubProceso','nroFicha'));
  }

  public function busca_apertura_fema(Request $request) {
    if (!Auth::check()) :
        return redirect('/auth/login');
    else:
      if(Auth::user()->actualizo_passw==0):
        return redirect('auth/password/change');
      endif;
    endif;

    $modelFemaFicha = new Fema_fichaReporte();
    $fechaDesde = $request->fechaDesde;
    $fechaHasta = $request->fechaHasta;
    $nroDocumento = $request->nroDocumento;
    $idProceso = $request->idProceso;
    $idSubProceso = $request->idSubProceso;
    $nroFicha = $request->nroFicha;
    $id_establecimiento = Auth::user()->id_establecimiento;
    if($id_establecimiento==76){ 
      $id_establecimiento = 0;
    }
    $fichas = $modelFemaFicha->GetAllFema1($fechaDesde, $fechaHasta, $nroDocumento ,$id_establecimiento);
    
    return view('frontend.fema.mostrar_aperturas', compact('fichas','fechaDesde','fechaHasta','nroDocumento','idProceso','idSubProceso','nroFicha'));
  }
  public function all_triajes_fema($id = 0) {
    if (!Auth::check()) :
        return redirect('/auth/login');
    else:
      if(Auth::user()->actualizo_passw==0):
        return redirect('auth/password/change');
      endif;
    endif;

    $modelFemaFicha = new Fema_fichaReporte();
    $date = Carbon::now();
    $fechaDesde = $date->format('d-m-Y');
    $fechaHasta = $date->format('d-m-Y');
    $nroDocumento = "";
    $idProceso = "";
    $idSubProceso = "";
    $nroFicha = "";
    $id_establecimiento = Auth::user()->id_establecimiento;
    if($id_establecimiento==76){ 
      $id_establecimiento = 0;
    }
    $fichas = $modelFemaFicha->GetAllTriaje1($fechaDesde, $fechaHasta, $nroDocumento ,$id_establecimiento);
    
    return view('frontend.fema.mostrar_triajes', compact('fichas','fechaDesde','fechaHasta','nroDocumento'));
  }

  public function busca_triaje_fema(Request $request) {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $modelFemaFicha = new Fema_fichaReporte();
    $fechaDesde = $request->fechaDesde;
    $fechaHasta = $request->fechaHasta;
    $nroDocumento = $request->nroDocumento;
    $idProceso = "";
    $idSubProceso = "";
    $id_establecimiento = Auth::user()->id_establecimiento;
    if($id_establecimiento==76){ 
      $id_establecimiento = 0;
    }
    $nroFicha = $request->nroFicha;
    $fichas = $modelFemaFicha->GetAllTriaje1($fechaDesde, $fechaHasta, $nroDocumento ,$id_establecimiento);
    
    return view('frontend.fema.mostrar_triajes', compact('fichas','fechaDesde','fechaHasta','nroDocumento'));
  }

  public function all_epidemiologias_fema($id = 0) {
    if (!Auth::check()) :
        return redirect('/auth/login');
    else:
      if(Auth::user()->actualizo_passw==0):
        return redirect('auth/password/change');
      endif;
    endif;

    $modelFemaFicha = new Fema_fichaReporte();
    $date = Carbon::now();
    $fechaDesde = $date->format('d-m-Y');
    $fechaHasta = $date->format('d-m-Y');
    $nroDocumento = "";
    $idProceso = "";
    $idSubProceso = "";
    $nroFicha = "";
    $id_establecimiento = Auth::user()->id_establecimiento;
    if($id_establecimiento==76){ 
      $id_establecimiento = 0;
    }
    //$fichas = $modelFemaFicha->GetAllEpidemiologia($fechaDesde, $fechaHasta);
    $variable = 1;
    return view('frontend.fema.mostrar_epidemiologias', compact('fechaDesde','fechaHasta','nroDocumento','variable'));
  }

  public function busca_epidemiologia_fema(Request $request) {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $modelFemaFicha = new Fema_fichaReporte();
    $fechaDesde = $request->fechaDesde;
    $fechaHasta = $request->fechaHasta;
    $nroDocumento = $request->nroDocumento;
    $idProceso = $request->idProceso;
    $idSubProceso = $request->idSubProceso;
    $nroFicha = $request->nroFicha;
    $id_establecimiento = Auth::user()->id_establecimiento;
    if($id_establecimiento==76){ 
      $id_establecimiento = 0;
    }
    $fichas = $modelFemaFicha->GetAllEpidemiologia1($fechaDesde, $fechaHasta, $nroDocumento ,$id_establecimiento);
    $variable = 2;
    return view('frontend.fema.mostrar_epidemiologias', compact('fichas','fechaDesde','fechaHasta','nroDocumento','idProceso','idSubProceso','nroFicha','variable'));
  }
  
  public function ficha_fema_envio  (Request $request){

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    
        $model_ficha_fema_envio = new \App\Models\Fema_ficha;
        $date = new Carbon();
        $anio = $date->format('Y');
          $model_ficha_fema_envio->nro_doc_ident                       = $request->dniReniec != '' ? $request->dniReniec : 0;
        
          if($request->dniReniec>'0'){
            $model_ficha_fema_envio->tipo_doc_ident                      = "1"; 
          }else{
            $model_ficha_fema_envio->tipo_doc_ident                    = $request->tipo_doc_ident != '' ? $request->tipo_doc_ident : 0;
          }
          
          $tiempo="SELECT DATE_PART('YEAR',AGE('$request->fecha_nacimiento'::date))";
          $tiempo = DB::select($tiempo);
          $tiempo=$tiempo[0]->date_part;
   /*       if($request->situacion!=""){
            $situacion=DB::table('situaciones')
            ->select('situaciones.*')
            ->where('descripcion',$request->situacion)
            ->first();
            $model_ficha_fema_envio->id_situacion_pnp = $situacion->id;
          }
          else
          {  */
            $model_ficha_fema_envio->id_situacion_pnp = 0; 
     /*     }  */
          $model_ficha_fema_envio->edad                                = $tiempo;
          $model_ficha_fema_envio->tipo_licencia_conducir              = $request->tipo_licencia != '' ? $request->tipo_licencia : "";
          $model_ficha_fema_envio->domicilio                           = $request->distrito_domicilio != '' ? $request->distrito_domicilio : 0;
          $model_ficha_fema_envio->fecha_nac                           = $request->fecha_nacimiento != '' ? $request->fecha_nacimiento : 0;
          $model_ficha_fema_envio->nombre                              = $request->nombres != '' ? $request->nombres : 0;
          $model_ficha_fema_envio->materno                             = $request->segundo_apellido != '' ? $request->segundo_apellido : 0;
          $model_ficha_fema_envio->foto                                = $request->foto != '' ? $request->foto : 0;
          $model_ficha_fema_envio->grado                               = $request->grado != '' ? $request->grado : 0;
          $model_ficha_fema_envio->carnet                              = $request->carnet != '' ? $request->carnet : 0;
          $model_ficha_fema_envio->estado_civil                        = $request->estado_civil != '' ? $request->estado_civil : 0;
          $model_ficha_fema_envio->unidades_pnp                        = $request->unidad != '' ? $request->unidad : 0;
          $model_ficha_fema_envio->dato_year_ficha = $anio;
          if($request->sexo=='MASCULINO'){
            $model_ficha_fema_envio->sexo                              = "M";
          }elseif($request->sexo=='FEMENINO'){
            $model_ficha_fema_envio->sexo                              = "F";
          }else{
            $model_ficha_fema_envio->sexo                              = "";
          }
          
          $model_ficha_fema_envio->paterno                             = $request->primer_apellido != '' ? $request->primer_apellido : 0; 
        
        if($request->fema_procesos1 == '2'){
          $model_ficha_fema_envio->id_fema_tipo_proceso  = $request->fema_tipo_procesosfiltro;
        }else{
          if($request->fema_tipo_procesos == '0'){
            $model_ficha_fema_envio->id_fema_tipo_proceso                = $request->fema_tipo_procesosgeneral != '' ? $request->fema_tipo_procesosgeneral : 0;
          }else{
            $model_ficha_fema_envio->id_fema_tipo_proceso                = $request->fema_tipo_procesos!= '' ? $request->fema_tipo_procesos : 0;
          }
        }
      
        if($request->fema_tipo_procesosgeneral=='1'){
  
          $model_ficha_fema_envio->descripcion                         = $request->descripcion1 != '' ? $request->descripcion1 : "";
  
        }elseif($request->fema_tipo_procesosgeneral=='2'){
          
          if($request->tipo_licencia=='1'){
            $model_ficha_fema_envio->fecha_aprobacion                  = $request->fechaexamenconducirnuevo != '' ? $request->fechaexamenconducirnuevo : "";
          }elseif($request->tipo_licencia=='2'){
            $model_ficha_fema_envio->fecha_aprobacion                  = $request->fechaexamenconducirreno != '' ? $request->fechaexamenconducirreno : "";
            $model_ficha_fema_envio->fecha_licencia_vencida              = $request->fechalicenciavencida != '' ? $request->fechalicenciavencida : "";
          }else{
          }
          $model_ficha_fema_envio->descripcion                         = $request->descripcion2 != '' ? $request->descripcion2 : "";
  
        }elseif($request->fema_tipo_procesosgeneral=='3'){
  
          $model_ficha_fema_envio->descripcion                         = $request->descripcion3 != '' ? $request->descripcion3 : "";
  
        }elseif($request->fema_tipo_procesosgeneral=='4'){
  
          $model_ficha_fema_envio->nro_ht                              = $request->ht != '' ? $request->ht : "";
          $model_ficha_fema_envio->nro_oficio                          = $request->oficio != '' ? $request->oficio : "";
          $model_ficha_fema_envio->nro_certificado_talento             = $request->certificadoth != '' ? $request->certificadoth : "";
          $model_ficha_fema_envio->nro_acta_reincorporacion            = $request->actareincorporacion != '' ? $request->actareincorporacion : "";  
      
          $model_ficha_fema_envio->descripcion                         = $request->descripcion4 != '' ? $request->descripcion4 : "";
        }elseif($request->fema_tipo_procesosgeneral=='5'){
          $model_ficha_fema_envio->descripcion                         = $request->descripcion5 != '' ? $request->descripcion5 : "";
        }elseif($request->fema_tipo_procesos=='8'){
          $model_ficha_fema_envio->descripcion                         = $request->descripcion8 != '' ? $request->descripcion8 : "";
        }elseif($request->fema_tipo_procesos=='10'){
          $model_ficha_fema_envio->descripcion                         = $request->descripcion10 != '' ? $request->descripcion10 : "";
        }elseif($request->fema_tipo_procesos=='11'){
          $model_ficha_fema_envio->descripcion                         = $request->descripcion11 != '' ? $request->descripcion11 : "";
        }elseif($request->fema_tipo_procesos=='12'){
          $model_ficha_fema_envio->descripcion                         = $request->descripcion12 != '' ? $request->descripcion12 : "";
        }elseif($request->fema_tipo_procesos=='13'){
          $model_ficha_fema_envio->descripcion                         = $request->descripcion13 != '' ? $request->descripcion13 : "";
        }else{
          $model_ficha_fema_envio->descripcion                         = $request->descripcion != '' ? $request->descripcion : "";
        }
  
        if($request->fema_tipo_procesos=='6'){
  
          if($request->audiometria =='on'){
            $model_ficha_fema_envio->audiometria ="1";
          }else{
            $model_ficha_fema_envio->audiometria ="0";
          }
  
          if($request->CampoVisual =='on'){
            $model_ficha_fema_envio->campovisual ="1";
          }else{
            $model_ficha_fema_envio->campovisual ="0";
          }
  
          if($request->Electroencefalograma =='on'){
            $model_ficha_fema_envio->electroencefalograma ="1";
          }else{
            $model_ficha_fema_envio->electroencefalograma ="0";
          }
  
          if($request->CarnetVacunaTropicales =='on'){
            $model_ficha_fema_envio->vacunatropicales ="1";
          }else{
            $model_ficha_fema_envio->vacunatropicales ="0";
          }
  
          if($request->CarnetVacunaCOVID =='on'){
            $model_ficha_fema_envio->vacunacovid ="1";
          }else{
            $model_ficha_fema_envio->vacunacovid ="0";
          }
  
        }
  
        if($request->fema_tipo_procesos=='7'){
      
          if($request->CarnetVacunaTropicales =='on'){
            $model_ficha_fema_envio->vacunatropicales ="1";
          }else{
            $model_ficha_fema_envio->vacunatropicales ="0";
          }
  
          if($request->CarnetVacunaCOVID =='on'){
            $model_ficha_fema_envio->vacunacovid ="1";
          }else{
            $model_ficha_fema_envio->vacunacovid ="0";
          }
  
        }
  
        if($request->fema_tipo_procesos=='9'){
      
          if($request->CarnetVacunaTropicales =='on'){
            $model_ficha_fema_envio->vacunatropicales ="1";
          }else{
            $model_ficha_fema_envio->vacunatropicales ="0";
          }
  
          if($request->CarnetVacunaCOVID =='on'){
            $model_ficha_fema_envio->vacunacovid ="1";
          }else{
            $model_ficha_fema_envio->vacunacovid ="0";
          }
  
        }
  
        if($request->fema_tipo_procesos=='10'){
      
          if($request->CarnetVacunaTropicales =='on'){
            $model_ficha_fema_envio->vacunatropicales ="1";
          }else{
            $model_ficha_fema_envio->vacunatropicales ="0";
          }
  
          if($request->CarnetVacunaCOVID =='on'){
            $model_ficha_fema_envio->vacunacovid ="1";
          }else{
            $model_ficha_fema_envio->vacunacovid ="0";
          }
  
        }
  
        $id_asegurado = 0;
        $asegurado = Asegurado::where('id_tipo_doc_ident','1')->where('nro_doc_ident',$request->dni_titular)->first();
        if (count($asegurado) == 0):
          $asegurado2 = new Asegurado();
          $asegurado2->tipo_doc_ident = 'DNI';
          $asegurado2->nro_doc_ident = $request->dni_titular;
          $asegurado2->nombre = $request->nombres;
          $asegurado2->paterno = $request->primer_apellido;
          $asegurado2->materno = $request->segundo_apellido;
          $asegurado2->id_estado_reg = "1";
          $asegurado2->cip = "";
          $asegurado2->sexo = substr($request->sexo, 0, 1);
          $asegurado2->fecha_nac = $request->fecha_nacimiento;
          $asegurado2->created_at = Carbon::now();
          $asegurado2->created_us = Auth::user()->id;
          $asegurado2->id_tipo_doc_ident = '1';
          $id_asegurado = $asegurado2->save();
        else:
          $id_asegurado = $asegurado->id;
        endif;
        $model_ficha_fema_envio->id_asegurado = $id_asegurado;
        $model_ficha_fema_envio->lugar_nacimiento = "";
        $model_ficha_fema_envio->ubigeo = "";
        $model_ficha_fema_envio->alojamiento = "";
        $model_ficha_fema_envio->unidad = "";
        $model_ficha_fema_envio->nro_historia = "";
        $model_ficha_fema_envio->grupo_sanguineo = "";
        $model_ficha_fema_envio->celular = "";
        $model_ficha_fema_envio->id_establecimiento = Auth::user()->id_establecimiento;
        $model_ficha_fema_envio->created_at = Carbon::now();
        $model_ficha_fema_envio->created_us = Auth::user()->id;
  
        $model_ficha_fema_envio->save();
  
        return redirect('/apertura_fema');
    
  }

  public function fema_triaje() {
    if (!Auth::check()) :
        return redirect('/auth/login');
    else:
      if(Auth::user()->actualizo_passw==0):
        return redirect('auth/password/change');
      endif;
    endif;
  
    return view('frontend.fema.buscar_fema');
  }

  public function buscar_dni_fema(Request $request){
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $date = new Carbon();
    $anio = $date->format('Y');
    $dni = $request->dni;
    
    $proceso = $request->tipo_proceso;
    $personal=DB::table('fema_fichas')
  
      ->select('fema_fichas.*')
      ->where('nro_doc_ident',$dni)
      ->where('id_fema_tipo_proceso',$proceso)
      ->where('dato_year_ficha',$anio)
      ->where('estado',1)
      ->first();
  
      if(isset($personal)){

      }else{
        Session::flash('flash_message', 'El dni no tiene ningun registro en Fema ' . $dni . ' , verificar en la su apertura');
        return redirect('/fema_triaje');
      }


      $establecimiento = DB::table('establecimientos')
              ->select('establecimientos.*')
              ->where('id',$personal->id_establecimiento)
              ->first();

      if(count($personal)>0):
        $triajes=DB::table('fema_triajes')
                ->select('fema_triajes.*')
                ->where('id_fema_ficha',$personal->id)
                ->where('estado',1)
          ->first();
        
        if(count($triajes)>0):
             Session::flash('flash_message', 'Ya tiene un triaje registrado en el establecimiento ' . $establecimiento->nombre . ' , verificar en la opcion Reporte de triajes');
          return redirect('/fema_triaje');
        else:
          $subprocesos=DB::table('fema_tipo_procesos')
                  ->select('fema_tipo_procesos.*')
                  ->where('id',$personal->id_fema_tipo_proceso)
                  ->first();
  
          $situacion=DB::table('situaciones')
                  ->select('situaciones.*')
                  ->where('id',$personal->id_situacion_pnp)
                  ->first();
  
          $procesos=DB::table('fema_procesos')
                  ->select('fema_procesos.*')
                  ->where('id',$subprocesos->id_proceso)
                  ->first();
                
    $model_medico = new Medico();
     $profesional=DB::table('users')
      ->select('users.*')
      ->where('dni',Auth::user()->dni)
      ->first();
  //  $profesional = $model_medico->GetDatosMedicoAndEstablecimiento2(Auth::user()->dni, Auth::user()->id_establecimiento);
/*
    if(count($profesional)>0):
      $profesional = $profesional[0];
    else:
      Session::flash('flash_message', 'Debe ser médico de este establecimiento');
      return redirect('/dashboard');
    endif;
*/
  
          $establecimiento=DB::table('establecimientos')
                      ->select('establecimientos.*')
                      ->where('id',$profesional->id_establecimiento)
                      ->first();
  
          $asegurado=DB::table('asegurados')
                      ->select('asegurados.*')
                      ->where('nro_doc_ident',$dni)
                      ->first();
  
          $historiaasegurado=DB::table('asegurado_historias')
                      ->select('asegurado_historias.*')
                      ->where('id_asegurado',$asegurado->id)
                      ->where('id_establecimiento',$profesional->id_establecimiento)
                      ->first();
  
          $razas = Raza::where('estado','1')->orderBy('nombre','asc')->get();
          $profesiones = Profesione::where('estado','1')->orderBy('created_at','desc')->get();
  
          return view('frontend.fema.ficha_triaje', compact('personal','anio','profesional','situacion','establecimiento','subprocesos','procesos','profesiones','razas','date','historiaasegurado'));
        endif;
      else:
          Session::flash('flash_message', 'Documento no encontrado, debe de aperturar una Ficha');
          return redirect('/fema_triaje');
      endif;
  }
  
  public function ficha_fema_triaje (Request $request){
  
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    
    $model_ficha_fema_envio = new \App\Models\Fema_ficha;
    $model_ficha_fema_envio = \App\Models\Fema_ficha::find($request->id_prestacion);
  
    $model_ficha_fema_envio->celular                      = $request->celular != '' ? $request->celular : "";
    $model_ficha_fema_envio->alojamiento                  = $request->alojamiento != '' ? $request->celular : "";
    $model_ficha_fema_envio->id_raza                      = $request->raza != '' ? $request->raza : "";
    $model_ficha_fema_envio->id_ocupacion                 = $request->ocupacion != '' ? $request->ocupacion : "";
  
    $model_ficha_fema_envio->save();
    $id_establecimiento = Auth::user()->id_establecimiento;
    $model_ficha_triaje = new \App\Models\Fema_triaje;
  
    $model_ficha_triaje->temperatura                            = $request->temperatura != '' ? $request->temperatura : "";
    $model_ficha_triaje->talla_cm                               = $request->talla != '' ? $request->talla : "";
    $model_ficha_triaje->peso_kg                                = $request->peso != '' ? $request->peso : "";
    $model_ficha_triaje->presion_arterial_x                     = $request->presionarteriax != '' ? $request->presionarteriax : "";
    $model_ficha_triaje->presion_arterial_y                     = $request->presionarteriay != '' ? $request->presionarteriay : "";
    $model_ficha_triaje->frecuencia_respiratoria                = $request->frecrespiratoria != '' ? $request->frecrespiratoria : "";
    $model_ficha_triaje->frecuencia_cardiaca                    = $request->freccardiaca != '' ? $request->freccardiaca : "";
    $model_ficha_triaje->sa02_porcentaje                        = $request->sa02 != '' ? $request->sa02 : "";
    $model_ficha_triaje->alerta                                 = $request->alerta_triaje != '' ? $request->alerta_triaje : "";
    $model_ficha_triaje->observacion                            = $request->observaciones_triaje != '' ? $request->observaciones_triaje : "";
    $model_ficha_triaje->historia_clinica_ipress                = $request->numero_historia_ipress != '' ? $request->numero_historia_ipress : 0;
    $model_ficha_triaje->historia_clinica                       = $request->numhis != '' ? $request->numhis : 0;
    $model_ficha_triaje->id_fema_ficha                          = $request->id_prestacion != '' ? $request->id_prestacion : "";
    $model_ficha_triaje->id_profesional                         = $request->id_profesional;
    $model_ficha_triaje->id_establecimiento                     = $id_establecimiento;
    $model_ficha_triaje->created_us = Auth::user()->id;
  
    $model_ficha_triaje->save();
  
    return redirect('/fema_triaje');
  }

  public function obtener_diagnostico_genero_fema($buscar, $genero, $examen)
  {

    $model_diagnostico = new \App\Models\Fema_ficha;
    $diagnostico = $model_diagnostico->getDiagnosticoByGeneroExamen($buscar, $genero ,$examen);
    $array = array();
    foreach ($diagnostico as $leer) {
      $array[] = array(
        "value" => $leer->codigo . " - " . $leer->nombre,
        "label" => $leer->codigo . " - " . $leer->nombre,
        "flag_saludpol" => $leer->flag_saludpol,
        "id" => $leer->id,
      );
    }

    echo json_encode($array);
  }
}
