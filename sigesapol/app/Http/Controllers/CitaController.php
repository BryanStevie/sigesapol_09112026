<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use DB;
use App;
use App\Models\antecedentesEnfermeria;
use Illuminate\Support\Facades\Route;
use Auth;
use Carbon\Carbon;
use App\Models\Farmacia;
use App\Models\Receta_vale;
use App\Models\Cita;
use App\Models\Tv;
use App\Models\Prestaciones;
use App\Models\User;
use App\Models\Consultorio;
use App\Models\TipoAtencione;
use App\Models\Asegurado;
use App\Models\Enfermeria;
use App\Models\Establecimiento;
use App\Models\Laboratorio;
use App\Models\Radiologia;
use App\Models\SolicitudProgramacionProcDetalles;
use App\Models\Totem;
use App\Models\CqxCita;
use Exception;
use Illuminate\Support\Facades\Log;

use function GuzzleHttp\json_encode;

class CitaController extends Controller
{
  function __construct()
    {
        $this->middleware('auth'); 
    } 
    
  public function all_atenciones_gestantes_puerpera($id_user = 0)
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

    /*$ruta = Route::getCurrentRoute()->getPath();
    if (count($ruta = explode("/", $ruta)) > 1);
    $ruta = $ruta[0];
    if ($ruta == "listar_citas_medico_atenciones");
    $ruta = "all_atenciones_gestantes_puerpera";
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
    $permiso = $menu[0];
    if ($permiso->lectura == "")
      return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");*/

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

    //====================
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

    $model_consultorio = new Consultorio;
    //$consultorio = $model_consultorio->ShowAllConsultorios($farmacia->id_establecimiento);
    $consultorio = $model_consultorio->ShowAllConsultoriosSubConsultorio($farmacia->id_establecimiento);

    $model_atencione = new TipoAtencione;
    $atencione = $model_atencione->ShowTipoAtencionAll();

    //$medico_encontrado = $model_medico->GetDatosMedicoAndEstablecimiento($usuario_actual->dni, $farmacia->id_establecimiento);  

    //if (count($medico_encontrado)>0) {

      return view('frontend.atenciones.all_atenciones_gestantes_puerpera', compact('tipo_registro', 'farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nombre_beneficiario', 'estado', 'flagemergenciareferencia', 'consultorio', 'medico', 'id_consultorio_actual', 'atencione', 'id_atencione'));
    
    //} else {
    //    return redirect('/dashboard')->withFlashDanger("Tiene que ser médico de este establecimiento; o estar activo: coordinar con el administrador del Sistema");
    //}
  }




  public function listar_paginados_gestantes_modulos(Request $request)
  {

    $model_citas = new Cita();
    $p[] = $request->fecha_ini;
    $p[] = $request->fecha_fin;
    $p[] = $request->dni_beneficiario;
    $p[] = $request->nombre_beneficiario;
    //$p[]=$request->id_servicio;
    $p[] = (isset($request->id_servicio) && $request->id_servicio != "") ? $request->id_servicio : 0;
    //$p[]=$request->id_consultorio;
    $p[] = (isset($request->id_consultorio) && $request->id_consultorio != "") ? $request->id_consultorio : 0;
    $p[] = $request->id_medico;
    //$p[]=0;
    //$p[]=0;
    //$p[]=0;
    $p[] = $request->estado;
    $p[] = $request->id_atencione;
    $p[] = Auth::user()->id_establecimiento;
    $p[] = $request->id_condicion_establecimiento;
    $p[] = $request->id_condicion_servicio;
    $p[] = $request->NumeroPagina;
    $p[] = $request->NumeroRegistros;
    $data = $model_citas->listar_gestantes($p);
    $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;
    //print_r($afiliacion);exit();
    $result["PageStart"] = $request->NumeroPagina;
    $result["pageSize"] = $request->NumeroRegistros;
    $result["SearchText"] = "";
    $result["ShowChildren"] = true;
    $result["iTotalRecords"] = $iTotalDisplayRecords;
    $result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
    $result["aaData"] = $data;
    echo json_encode($result);
  }
  public function all_citas_medico_paciente($id_user = 0)
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

    $validadorIpress = Auth::user()->id_establecimiento;
    $validadorFarmacia = Auth::user()->id_farmacia;//121
  
    $tipo_registro = Auth::user()->tipo_registro;
    $id_user = Auth::user()->id;
    $model_usuario = new User;
    $usuario_actual = $model_usuario->GetById($id_user);


    $soloIpressLuisNSaes =0;
    $farmaciavalor = 0;


    $ruta = Route::getCurrentRoute()->getPath();
    if (count($ruta = explode("/", $ruta)) > 1);
    $ruta = $ruta[0];
    if ($ruta == "listar_citas_medico_paciente");
    $ruta = "all_citas_medico_paciente";
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
    $permiso = $menu[0];
    if ($permiso->lectura == "")
      return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

    $id_user = Auth::user()->id;
    $usuario_actual = $model_usuario->GetById($id_user);

    $flagemergenciareferencia = $usuario_actual->flaghistoria;
    $flagllamadacita = $usuario_actual->flagllamadacita;
    $id_consultorio_actual = $usuario_actual->id_consultorio;
    //echo $id_consultorio;
    //echo $flagemergenciareferencia;
    $dni_beneficiario = "";
    $nombre_beneficiario = "";
    $estado = "1";

    $date = Carbon::now();
    $fechaDesde = $date->format('d-m-Y');
    $fechaHasta = $date->format('d-m-Y');

    //====================
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
    //====================
    $model_consultorio = new Consultorio;

    $consultorio = $model_consultorio->ShowAllConsultoriosSubConsultorio($farmacia->id_establecimiento);

    //LNS - Central PIP
    if($validadorIpress == 76 && $tipo_registro ==1 && $validadorFarmacia==121 && $flagllamadacita == 1 ){
      $soloIpressLuisNSaes=1;
    }
    if($validadorFarmacia == 121 && $flagllamadacita == 1){
      $farmaciavalor =1;
    } 
    
    return view('frontend.receta_vales.all_citas_medico_paciente', compact('farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nombre_beneficiario', 'estado', 'flagemergenciareferencia', 'consultorio', 'medico', 'id_consultorio_actual','soloIpressLuisNSaes','farmaciavalor'));
  }

  public function listar_citas_medico_paciente(Request $request)
  {
  
    $model_medico = new \App\Models\Medico();
    $model_usuario = new User;

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    if (!$request->id_user) :
      return redirect('/all_citas_medico_paciente');
    endif;

    $modelfarma = new Farmacia;
    $farmacia = $modelfarma->GetFarmaByUser($request->id_user);
    $id_farmacia = $farmacia->id;
    $modo_validacion = $farmacia->modo_validacion;
    //----------------------------------------------------------------------

    $id_user = $request->id_user;
    $fechaDesde = $request->fechaDesde;
    //$fechaHasta = $request->fechaHasta;
    $dni_beneficiario = strtoupper($request->dni_beneficiario);
    $nombre_beneficiario = strtoupper($request->nombre_beneficiario);
    //$nro_receta = $request->nro_receta;
    $estado = $request->estado;

    $usuario_actual = $model_usuario->GetById($id_user);
    $flagemergenciareferencia = $usuario_actual->flaghistoria;
    //$medico_actual = $model_medico->GetMedicoByDNI($usuario_actual->dni);
    $medico = 0;
    if ($flagemergenciareferencia != 1) {
      $medico_actual = $model_medico->GetMedicoByDniAndEstablecimiento($usuario_actual->dni, $farmacia->id_establecimiento);
      $medico = $medico_actual->id;
    }
    //print_r($medico_actual);exit();
    /*
        $obtner_dni_medico = $model_medico->GetMedicoByDNI($user->dni);
        if (count($obtner_dni_medico) > 0) {
            $medico = $model_medico->GetMedicoByDNI($user->dni);
            $existe_medico = 1;
        } else {
            $existe_medico = 0;
        }*/

    //$iddispensador = $request->iddispensador;

    //$modelReceta = new Receta_vale();
    $model_citas = new Cita();

    $citas = $model_citas->AllCitaConfirmaPacienteFecha($fechaDesde, $dni_beneficiario, $nombre_beneficiario, $medico, $estado);

    $cantidad = $model_citas->GetCountCitaConfirmaPacienteFecha($fechaDesde, $dni_beneficiario, $nombre_beneficiario, $medico, $estado);
    //$cantidad = '1';
    


    //print_r($recetas);exit();
    return view('frontend.receta_vales.all_citas_medico_paciente', compact('citas', 'cantidad', 'farmacia', 'fechaDesde',  'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nombre_beneficiario', 'estado', 'flagemergenciareferencia'));
  }

  public function modal_asignar_consultorio()
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $id_user = Auth::user()->id;
    $model_farma = new Farmacia();
    $farmacia = $model_farma->GetFarmaByUser($id_user);
    $model_consultorio = new Consultorio;

    //$consultorio = $model_consultorio->ShowAllConsultorios($farmacia->id_establecimiento);
    $consultorio = $model_consultorio->ShowAllConsultoriosSubConsultorio($farmacia->id_establecimiento);
    //$historia = \App\Models\Asegurado_historia::find($id);
    //$asegurado = \App\Models\Asegurado::find($historia->id_asegurado);
    return view('frontend.citas.modal_asignar_consultorio', compact('consultorio')/*,compact('medicos','horario','fecha_atencion','id_ipress','id_consultorio')*/);
  }

  public function asignar_cita(Request $request)
  {
    
    foreach ($request->id_cita as $key => $value) :
      $cita = Cita::where('id', '=', $value)->first();
      $cita->id_sub_consultorio = $request->id_consultorio;
      $cita->id_medico = $request->id_medico;
      $cita->id_estado_cita = 10;
      $cita->updated_at = Carbon::now();
      $cita->updated_us = Auth::user()->id;
      $cita->save();

      $sql = "update citas_llamados 
       set id_sub_consultorio = ".(int)$request->id_consultorio.",
       id_medico = ".(int)$request->id_medico.",
       id_estado_cita = 10, 
       updated_at = '".Carbon::now()."',
       updated_us = ".auth()->user()->id."
       where id_cita = " . (int)$value;
      DB::select($sql);
    endforeach;
  }
  public function listar_paginados(Request $request)
  {
    $validadorIpress = Auth::user()->id_establecimiento;
    $tipo_registro = Auth::user()->tipo_registro;
    $idNestor =Auth::user()->id;
    $farmacia = Auth::user()->id_farmacia;//121
    $disabledButton = true;
    $id_user = Auth::user()->id;
    $model_usuario = new User;
    $usuario_actual = $model_usuario->GetById($id_user);

    
    $model_prestaciones = new Prestaciones;
    error_log($model_prestaciones->GetPrestacionesOfGestantesOrPuerera());
    $flagllamadacita = $usuario_actual->flagllamadacita;
    
    $cantidadllamadas=0;
    
    
    $model_citas = new Cita();
    $p[] = $request->fecha_ini;
    $p[] = $request->fecha_fin;
    $p[] = $request->fecha_reg_ini;
    $p[] = $request->fecha_reg_fin;
    $p[] = $request->dni_beneficiario;
    $p[] = $request->nombre_beneficiario;
    $p[] = (isset($request->id_servicio) && $request->id_servicio != "") ? $request->id_servicio : 0;
    $p[] = (isset($request->id_consultorio) && $request->id_consultorio != "") ? $request->id_consultorio : 0;
    $p[] = $request->nombre_estado_paciente;

    $p[] = $request->id_medico;
    $p[] = Auth::user()->id_establecimiento;
    $p[] = $request->id_condicion_establecimiento;
    $p[] = $request->id_condicion_servicio;
    //$p[] = $request->id_tipo_estado_paciente;
    $p[] = $request->estado;
    $p[] = $request->NumeroPagina;
    $p[] = $request->NumeroRegistros;
    
    //lista original
    $dataOriginal = $model_citas->listar_gestantes($p);
    $dataodenada =[];
   
      //lista nuevo paciente
      $data3 =[];
    $data4 =[];
    $listaYaIniciada = false;
    $ingresoNuevo =false;
    $autollamada=false;
    $dataFilter =[];
    $idCitas=[];
    $soloIpressLuisNSaes = false;

   
    //Validación de su orden_atencion previamente pasado por totem
    if($validadorIpress == 76 && $tipo_registro ==1 && $flagllamadacita == 1){
      $soloIpressLuisNSaes = true;
      $disabledButton = false;
      foreach ($data as $objr) {
        if(($objr->estado_presencia== "1"  || $objr->estado_presencia== "2" ) && $objr->estado_presencia!= null){
          if ( $objr->orden_atencion != null){
            $listaYaIniciada=true;
            break;
          } 
        }
      }
      if($listaYaIniciada ==true){
        foreach ($data as $objr2) {
          if(($objr->estado_presencia== "1"  || $objr->estado_presencia== "2" )  && $objr2->estado_presencia!= null){
            if ( $objr2->orden_atencion == null){
              $ingresoNuevo=true;
              break;
            } 
          }
        }         
      }
    }

 


   
 
    //modificacion si todos los pacientes se encuentran registrados antes de llamar
    //los ordenamos por orden_atencion asc
    //$autollamada = true
    if($validadorIpress == 76 && $tipo_registro ==1 && $farmacia==121 &&  $flagllamadacita == 1){
        if($listaYaIniciada ==true && $ingresoNuevo ==false){
        $data = $model_citas->listar_cita_ajax_orden($p);
        if(count($data)>0){
          if($data[0]->orden_atencion != null ){
            $autollamada=true;
          }
        }
      }
    }

    //Cuando nos registramos por el totem hace un update de la cita → estado_triaje = '1' | estado_presencia = '1' | fecha_presencia = NOW() | estado_dia_paciente = estado_de_tipología
    //Lista de citas con orden_atencion null y con número de orden
    if($validadorIpress == 76 && $tipo_registro ==1  && $farmacia==121 &&  $flagllamadacita == 1){
        if($listaYaIniciada ==true && $ingresoNuevo ==true){
        $autollamada =true;
        $dataodenada = $model_citas->listar_cita_ajax_orden($p);
        
      //quitarlos para proximamente agregarlos de nuevo
        for ($i=0; $i < count($dataodenada) ; $i++) {
          //estado_presencia => 1=Presente Triaje, 2=Presente para atenderse  
          if ( ($dataodenada[$i]->estado_presencia== "1" || $dataodenada[$i]->estado_presencia== "2")&& $dataodenada[$i]->orden_atencion == null){
            //Destruimos registro  
            unset($dataodenada[$i]);
          }
        }
        //Insertamos elemento sin orden_atencion al final del array
        foreach ($data as $key) {
          if($key->orden_atencion == null){
            array_push($data3,$key);
          }
        }
        //Insertamos elemento con estado_presencia al final del array
        foreach ($data3 as $key2) {
          if ($key2->estado_presencia!= null){
            array_push($data4,$key2);
          }
        } 
        //            ↓
        //datos nuevos data4 sin orden_atencion y con estado_presencia
        $fechaActual_carbon =Carbon::now();
        for ($i=0; $i <count($data4) ; $i++) { 
          $cita=Cita::where('id','=',$data4[$i]->id)->first();
          $fechahora= $cita->fecha_hora;
          $fechaCita = Carbon::createFromFormat('Y-m-d H:i:s', $fechahora);
          $siFechaCitaesMayor = $fechaCita->gt($fechaActual_carbon);
          $diffInMinutes = false;
          if($siFechaCitaesMayor){
            $diffInMinutes = $fechaCita->diffInMinutes($fechaActual_carbon);
          } else{
            $diffInMinutes = $fechaActual_carbon->diffInMinutes($fechaCita);  
          }
          //Valida el tiempo 30min despues des la hora actual
          if($diffInMinutes >=30){
            //Anteriormente quitamos los registros que pasaron por el totem, es aquí donde lo agregamos otra vez
            array_push($dataodenada,$data4[$i]);
          } else{
            //30 min antes de la hora actual
            $datares = $model_citas->citadoOrdenDeAtencionNotNull($cita->id,$cita->fecha_hora,$cita->id_sub_consultorio,$request->id_medico);
            if(count($datares)==0){
              array_push($dataodenada,$data4[$i]); 
              // aqui no problema 
            } else{
              $indexNuevo=null;
              if (count($datares) > 0) {
                try {
                  //Reindexar para que la iteración sea correcta
                  $dataodenada = array_values($dataodenada);
                  for ($j = 0; $j < count($dataodenada); $j++) { 
                    if ($dataodenada[$j]->id == $datares[0]->id) {
                        $indexNuevo = $j;
                        // Realizar alguna acción si la condición se cumple
                    }            
                  }
                } catch (Exception $e) {
                  //throw $th;
                  return ["Error"=>$e->getMessage(),
                          "Listas"=>[
                            "dataodenada"=>$dataodenada,
                            "datares"=>$datares]];
                } 
            } else {
              echo "EL ARRAY DATARES ESTA VACIO O NULO REMITIR MENSAJE.";

            }
              //$id es 3
              if($indexNuevo > 1){
                //dd($data4);
                $dataodenada =$this->insertar($dataodenada,$data4[$i],$indexNuevo);
              } else{
                if($indexNuevo==0){
                  $cita2=Cita::where('id','=',$dataodenada[1]->id)->first();
                  if($cita2->cantidad_llamada==0){
                    $dataodenada =$this->insertar($dataodenada,$data4[$i],0);
                  } else{
                    $dataodenada =$this->insertar($dataodenada,$data4[$i],1);
                  } 
                }
              }
            }
          }
        }
  
      $nuevoarray = [];
      $iterador =1;
      
      foreach ($dataodenada as $keydo => $valuedo) {
        $valuedo->orden_atencion=$iterador; 
        $nuevoarray[] = $valuedo;
        $iterador++;
      }
      $data =$dataodenada;   
    }
  }
    //valida que sea el ipress 76 y el doctor que sea 1
    if($validadorIpress == 76 && $tipo_registro ==1 && $farmacia==121 &&  $flagllamadacita == 1){      
      $disabledButton = false;
      foreach ($data as $obj) {
        if ( ($obj->estado_presencia== "1" || $obj->estado_presencia== "2" ) && $obj->estado_presencia!= null){
          $cita=Cita::where('id','=',$obj->id)->first();
          $cantidadllamadas = $cita->cantidad_llamada;

          break;  
        }
      }

      foreach ($data as $obj) {
        if ( ($obj->estado_presencia== "1" || $obj->estado_presencia== "2" )  && $obj->estado_presencia!= null){
          array_push($idCitas,$obj->id);  
        }
      }

      foreach ($data as $objrgeneral) {
        array_push($dataFilter,$objrgeneral);
      } 
    
             
    } else{
      if($validadorIpress == 76 && $tipo_registro ==1 && $farmacia==121 &&  $flagllamadacita == 1){  
        $disabledButton = false;
        foreach ($data as $obj) {
          if ( ($obj->estado_presencia == "1" || $obj->estado_presencia == "2" )  && $obj->estado_presencia!= null){      
            $cita=Cita::where('id','=',$obj->id)->first();
            $cantidadllamadas = $cita->cantidad_llamada;
            break;  
          }
        }

        foreach ($data as $obj) {
          if ( ($obj->estado_presencia == "1" || $obj->estado_presencia == "2" )  && $obj->estado_presencia!= null){
            array_push($idCitas,$obj->id);   
          }
        }
        
        foreach ($data as $objrgeneral) {
          array_push($dataFilter,$objrgeneral);
        }    
      }
    }
      
    $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;

  
    //print_r($afiliacion);exit();

    $result["PageStart"] = $request->NumeroPagina;
    $result["pageSize"] = $request->NumeroRegistros;
    $result["SearchText"] = "";
    $result["ShowChildren"] = true;
    $result["iTotalRecords"] = $iTotalDisplayRecords;
    $result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
    if($validadorIpress == 76 && $tipo_registro ==1 && $farmacia==121 &&  $flagllamadacita == 1){
        $result["aaData"] = $dataOriginal;
        $result["ccData"] = $data;
        $result["tipo_listado"] = 1;
    }else{
        $result["aaData"] = $dataOriginal;
        $result["tipo_listado"] = 0;
    }
    
    //id preparados
    $result["ids"] = $idCitas;
    $result["autollamada"] = $autollamada;
    $result["disabledButton"] = $disabledButton;
    $result["soloIpressLuisNSaes"] = $soloIpressLuisNSaes;
    $result["idNestor"] = $idNestor;
    $result["cantidadllamadas"]=$cantidadllamadas;
    $result["farmacia"] = $farmacia;
    $result["flagllamadacita"] = $flagllamadacita;

    echo json_encode($result);
}


  public function listar_cita_ajax(Request $request)
  {
    $validadorIpress = Auth::user()->id_establecimiento;
    $tipo_registro = Auth::user()->tipo_registro;
    $idNestor =Auth::user()->id;
    $farmacia = Auth::user()->id_farmacia;//121
    $disabledButton = true;
    $id_user = Auth::user()->id;
    $model_usuario = new User;
    $usuario_actual = $model_usuario->GetById($id_user);

    

    $flagllamadacita = $usuario_actual->flagllamadacita;
    
    $cantidadllamadas=0;
    
    
    $model_citas = new Cita();
    $p[] = $request->fecha_ini;
    $p[] = $request->fecha_fin;
    $p[] = $request->fecha_reg_ini;
    $p[] = $request->fecha_reg_fin;
    $p[] = $request->dni_beneficiario;
    $p[] = $request->nombre_beneficiario;
    $p[] = (isset($request->id_servicio) && $request->id_servicio != "") ? $request->id_servicio : 0;
    $p[] = (isset($request->id_consultorio) && $request->id_consultorio != "") ? $request->id_consultorio : 0;
    $p[] = $request->id_medico;
    $p[] = Auth::user()->id_establecimiento;
    $p[] = $request->id_condicion_establecimiento;
    $p[] = $request->id_condicion_servicio;
    $p[] = $request->estado;
    $p[] = $request->NumeroPagina;
    $p[] = $request->NumeroRegistros;
    //lista original
    $dataOriginal = $model_citas->listar_cita_ajax($p);
    $data = $model_citas->listar_cita_ajax_sin_paginas($p);
    $dataodenada =[];
   
      //lista nuevo paciente
      $data3 =[];
    $data4 =[];
    $listaYaIniciada = false;
    $ingresoNuevo =false;
    $autollamada=false;
    $dataFilter =[];
    $idCitas=[];
    $soloIpressLuisNSaes = false;

   
    //Validación de su orden_atencion previamente pasado por totem
    if($validadorIpress == 76 && $tipo_registro ==1 && $flagllamadacita == 1){
      $soloIpressLuisNSaes = true;
      $disabledButton = false;
      foreach ($data as $objr) {
        // estado_presencia== "1" => en triaje
        // estado_presencia== "2" => pasa a consultorio
        if(/*($objr->estado_presencia== "1"  || */$objr->estado_presencia== "2" /*)*/ && $objr->estado_presencia!= null){
          if ( $objr->orden_atencion != null){
            $listaYaIniciada=true;
            break;
          } 
        }
      }
      if($listaYaIniciada ==true){
        foreach ($data as $objr2) {
          if(/*($objr->estado_presencia== "1"  || */$objr->estado_presencia== "2" /*)*/  && $objr2->estado_presencia!= null){
            if ( $objr2->orden_atencion == null){
              $ingresoNuevo=true;
              break;
            } 
          }
        }         
      }
    }

 


   
 
    //modificacion si todos los pacientes se encuentran registrados antes de llamar
    //los ordenamos por orden_atencion asc
    //$autollamada = true
    if($validadorIpress == 76 && $tipo_registro ==1 && $farmacia==121 &&  $flagllamadacita == 1){
        if($listaYaIniciada ==true && $ingresoNuevo ==false){
        $data = $model_citas->listar_cita_ajax_orden($p);
        if(count($data)>0){
          if($data[0]->orden_atencion != null ){
            $autollamada=true;
          }
        }
      }
    }

    //Cuando nos registramos por el totem hace un update de la cita → estado_triaje = '1' | estado_presencia = '1' | fecha_presencia = NOW() | estado_dia_paciente = estado_de_tipología
    //Lista de citas con orden_atencion null y con número de orden
    if($validadorIpress == 76 && $tipo_registro ==1  && $farmacia==121 &&  $flagllamadacita == 1){
        if($listaYaIniciada ==true && $ingresoNuevo ==true){
        $autollamada =true;
        $dataodenada = $model_citas->listar_cita_ajax_orden($p);
        
      //quitarlos para proximamente agregarlos de nuevo
        for ($i=0; $i < count($dataodenada) ; $i++) {
          //estado_presencia => 1=Presente Triaje, 2=Presente para atenderse  
          if ( /*($dataodenada[$i]->estado_presencia== "1" || */$dataodenada[$i]->estado_presencia== "2"/*)*/ && $dataodenada[$i]->orden_atencion == null){
            //Destruimos registro  
            unset($dataodenada[$i]);
          }
        }
        //Insertamos elemento sin orden_atencion al final del array
        foreach ($data as $key) {
          if($key->orden_atencion == null){
            array_push($data3,$key);
          }
        }
        //Insertamos elemento con estado_presencia al final del array
        foreach ($data3 as $key2) {
          if ($key2->estado_presencia!= null){
            array_push($data4,$key2);
          }
        } 
        //            ↓
        //datos nuevos data4 sin orden_atencion y con estado_presencia
        $fechaActual_carbon =Carbon::now();
        for ($i=0; $i <count($data4) ; $i++) { 
          //$cita=Cita::where('id','=',$data4[$i]->id)->first();
          $cad = "SELECT * FROM citas_llamados WHERE id_cita = ".(int)$data4[$i]->id." limit 1;";
          $cita = DB::select($cad);
          $cita = $cita[0];
          $fechahora= $cita->fecha_hora;
          $fechaCita = Carbon::createFromFormat('Y-m-d H:i:s', $fechahora);
          $siFechaCitaesMayor = $fechaCita->gt($fechaActual_carbon);
          $diffInMinutes = false;
          if($siFechaCitaesMayor){
            $diffInMinutes = $fechaCita->diffInMinutes($fechaActual_carbon);
          } else{
            $diffInMinutes = $fechaActual_carbon->diffInMinutes($fechaCita);  
          }
          //Valida el tiempo 30min despues des la hora actual
          if($diffInMinutes >=30){
            //Anteriormente quitamos los registros que pasaron por el totem, es aquí donde lo agregamos otra vez
            array_push($dataodenada,$data4[$i]);
          } else{
            //30 min antes de la hora actual
            $datares = $model_citas->citadoOrdenDeAtencionNotNull($cita->id,$cita->fecha_hora,$cita->id_sub_consultorio,$request->id_medico);
            if(count($datares)==0){
              array_push($dataodenada,$data4[$i]); 
              // aqui no problema 
            } else{
              $indexNuevo=null;
              if (count($datares) > 0) {
                try {
                  //Reindexar para que la iteración sea correcta
                  $dataodenada = array_values($dataodenada);
                  for ($j = 0; $j < count($dataodenada); $j++) { 
                    if ($dataodenada[$j]->id == $datares[0]->id_cita) {
                        $indexNuevo = $j;
                        // Realizar alguna acción si la condición se cumple
                    }            
                  }
                } catch (Exception $e) {
                  //throw $th;
                  return ["Error"=>$e->getMessage(),
                          "Listas"=>[
                            "dataodenada"=>$dataodenada,
                            "datares"=>$datares]];
                } 
            } else {
              echo "EL ARRAY DATARES ESTA VACIO O NULO REMITIR MENSAJE.";

            }
              //$id es 3
              if($indexNuevo > 1){
                //dd($data4);
                $dataodenada =$this->insertar($dataodenada,$data4[$i],$indexNuevo);
              } else{
                if($indexNuevo==0){
                  //$cita2=Cita::where('id','=',$dataodenada[1]->id)->first();
                  $cad = "SELECT * FROM citas_llamados WHERE id_cita = ".(int)$dataodenada[1]->id." limit 1;";
                  $cita2 = DB::select($cad);
                  $cita2 = $cita2[0];
                  if($cita2->cantidad_llamada==0){
                    $dataodenada =$this->insertar($dataodenada,$data4[$i],0);
                  } else{
                    $dataodenada =$this->insertar($dataodenada,$data4[$i],1);
                  } 
                }
              }
            }
          }
        }
  
      $nuevoarray = [];
      $iterador =1;
      
      foreach ($dataodenada as $keydo => $valuedo) {
        $valuedo->orden_atencion=$iterador; 
        $nuevoarray[] = $valuedo;
        $iterador++;
      }
      $data =$dataodenada;   
    }
  }
    //valida que sea el ipress 76 y el doctor que sea 1
    if($validadorIpress == 76 && $tipo_registro ==1 && $farmacia==121 &&  $flagllamadacita == 1){      
      $disabledButton = false;
      foreach ($data as $obj) {
        if ( /*($obj->estado_presencia== "1" ||*/ $obj->estado_presencia== "2" /*)*/ && $obj->estado_presencia!= null){
          //$cita=Cita::where('id','=',$obj->id)->first();
          $cad = "SELECT * FROM citas_llamados WHERE id_cita = ".(int)$obj->id." limit 1;";
          $cita = DB::select($cad);
          $cita = $cita[0];
          $cantidadllamadas = $cita->cantidad_llamada;

          break;  
        }
      }

      foreach ($data as $obj) {
        if ( /*($obj->estado_presencia== "1" ||*/ $obj->estado_presencia== "2" /*)*/  && $obj->estado_presencia!= null){
          array_push($idCitas,$obj->id);  
        }
      }

      foreach ($data as $objrgeneral) {
        array_push($dataFilter,$objrgeneral);
      } 
    
             
    } else{
      if($validadorIpress == 76 && $tipo_registro ==1 && $farmacia==121 &&  $flagllamadacita == 1){  
        $disabledButton = false;
        foreach ($data as $obj) {
          if ( /*($obj->estado_presencia == "1" ||*/ $obj->estado_presencia == "2" /*)*/  && $obj->estado_presencia!= null){      
            //$cita=Cita::where('id','=',$obj->id)->first();
            $cad = "SELECT * FROM citas_llamados WHERE id_cita = ".(int)$obj->id." limit 1;";
            $cita = DB::select($cad);
            $cita = $cita[0];
            $cantidadllamadas = $cita->cantidad_llamada;
            break;  
          }
        }

        foreach ($data as $obj) {
          if ( /*($obj->estado_presencia == "1" ||*/ $obj->estado_presencia == "2" /*)*/  && $obj->estado_presencia!= null){
            array_push($idCitas,$obj->id);   
          }
        }
        
        foreach ($data as $objrgeneral) {
          array_push($dataFilter,$objrgeneral);
        }    
      }
    }
      
    $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;

    $ordenar_fecha_cita = $model_citas->ordenarFechacita($idCitas);
    $order_ids=[];
    foreach ($ordenar_fecha_cita as $key => $value) {
      array_push($order_ids,$value->id_cita);
    }
    $idCitas = $order_ids;
    //print_r($afiliacion);exit();

    $idsCitasListado = [];
    foreach ($dataOriginal as $fila) {
      $idsCitasListado[] = (int)$fila->id;
    }
    if (count($idsCitasListado) > 0) {
      $citasPp = DB::table('citas as c')
        ->join('solicitud_procedimientos_detalle as d', 'd.id', '=', 'c.id_solicitud_procedimiento_detalle')
        ->whereIn('c.id', $idsCitasListado)
        ->where('c.flag_pp', 1)
        ->select('c.id', 'd.id_solicitud', 'd.id_procedimiento')
        ->get();
      $urlsPp = [];
      foreach ($citasPp as $citaPp) {
        $urlsPp[$citaPp->id] = '/atender_procprogramado/' . $citaPp->id_solicitud . '/' . $citaPp->id_procedimiento;
      }
      foreach ($dataOriginal as $fila) {
        if (isset($urlsPp[$fila->id])) {
          $fila->url_atencion_pp = $urlsPp[$fila->id];
        }
      }
    }

    $result["PageStart"] = $request->NumeroPagina;
    $result["pageSize"] = $request->NumeroRegistros;
    $result["SearchText"] = "";
    $result["ShowChildren"] = true;
    $result["iTotalRecords"] = $iTotalDisplayRecords;
    $result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
    if($validadorIpress == 76 && $tipo_registro ==1 && $farmacia==121 &&  $flagllamadacita == 1){
        $result["aaData"] = $dataOriginal;
        $result["ccData"] = $data;
        $result["tipo_listado"] = 1;
    }else{
        $result["aaData"] = $dataOriginal;
        $result["tipo_listado"] = 0;
    }
    
    //id preparados
    $result["ids"] = $idCitas;
    $result["autollamada"] = $autollamada;
    $result["disabledButton"] = $disabledButton;
    $result["soloIpressLuisNSaes"] = $soloIpressLuisNSaes;
    $result["idNestor"] = $idNestor;
    $result["cantidadllamadas"]=$cantidadllamadas;
    $result["farmacia"] = $farmacia;
    $result["flagllamadacita"] = $flagllamadacita;

    echo json_encode($result);
}


  public function listar_prestacion_ajax(Request $request)
  {

    $model_citas = new Cita();
    $p[] = $request->fecha_ini;
    $p[] = $request->fecha_fin;
    $p[] = $request->dni_beneficiario;
    $p[] = $request->nombre_beneficiario;
    //$p[]=$request->id_servicio;
    $p[] = (isset($request->id_servicio) && $request->id_servicio != "") ? $request->id_servicio : 0;
    //$p[]=$request->id_consultorio;
    $p[] = (isset($request->id_consultorio) && $request->id_consultorio != "") ? $request->id_consultorio : 0;
    $p[] = $request->id_medico;
    //$p[]=0;
    //$p[]=0;
    //$p[]=0;
    $p[] = $request->estado;
    $p[] = $request->id_atencione;
    $p[] = Auth::user()->id_establecimiento;
    $p[] = $request->id_condicion_establecimiento;
    $p[] = $request->id_condicion_servicio;
    $p[] = $request->id_prestacion_buscar;
    $p[] = $request->NumeroPagina;
    $p[] = $request->NumeroRegistros;
    $data = $model_citas->listar_prestacion_ajax($p);
    $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;
    //print_r($afiliacion);exit();
    $result["PageStart"] = $request->NumeroPagina;
    $result["pageSize"] = $request->NumeroRegistros;
    $result["SearchText"] = "";
    $result["ShowChildren"] = true;
    $result["iTotalRecords"] = $iTotalDisplayRecords;
    $result["iTotalDisplayRecords"] = $iTotalDisplayRecords;
    $result["aaData"] = $data;
    echo json_encode($result);
  }

  public function all_citas_medico_atenciones($id_user = 0)
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
    $id_prestacion_buscar = "";
    
    $date = Carbon::now();
    $fechaDesde = $date->format('d-m-Y');
    $fechaHasta = $date->format('d-m-Y');

    //====================
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
    //else{
      $medicos= $model_medico->ShowSoloMedicos(Auth::user()->id_establecimiento);
    //}
    $model_consultorio = new Consultorio;
    //$consultorio = $model_consultorio->ShowAllConsultorios($farmacia->id_establecimiento);
    $consultorio = $model_consultorio->ShowAllConsultoriosSubConsultorio($farmacia->id_establecimiento);

    $model_atencione = new TipoAtencione;
    $atencione = $model_atencione->ShowTipoAtencionAll();

    //$medico_encontrado = $model_medico->GetDatosMedicoAndEstablecimiento($usuario_actual->dni, $farmacia->id_establecimiento);  

    //if (count($medico_encontrado)>0) {

      return view('frontend.receta_vales.all_citas_medico_atenciones', compact('tipo_registro','usuario_actual', 'farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nombre_beneficiario', 'estado', 'flagemergenciareferencia', 'consultorio', 'medico', 'id_consultorio_actual', 'atencione', 'id_atencione','medicos','id_prestacion_buscar'));
    
    //} else {
    //    return redirect('/dashboard')->withFlashDanger("Tiene que ser médico de este establecimiento; o estar activo: coordinar con el administrador del Sistema");
    //}
  }


  public function listar_citas_medico_atenciones(Request $request)
  {
    $model_medico = new \App\Models\Medico();
    $model_usuario = new User;

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    if (!$request->id_user) :
      return redirect('/all_citas_medico_atenciones');
    endif;

    $modelfarma = new Farmacia;
    $farmacia = $modelfarma->GetFarmaByUser($request->id_user);
    $id_farmacia = $farmacia->id;
    $modo_validacion = $farmacia->modo_validacion;
    //----------------------------------------------------------------------
    $atencione = $request->id_atencione;
    $id_user = $request->id_user;
    $fechaDesde = $request->fechaDesde;
    //$fechaHasta = $request->fechaHasta;
    $dni_beneficiario = strtoupper($request->dni_beneficiario);
    $nombre_beneficiario = strtoupper($request->nombre_beneficiario);
    //$nro_receta = $request->nro_receta;
    $estado = $request->estado;
    $usuario_actual = $model_usuario->GetById($id_user);
    $flagemergenciareferencia = $usuario_actual->flaghistoria;
    //$medico_actual = $model_medico->GetMedicoByDNI($usuario_actual->dni);
    $medico = 0;
    if ($flagemergenciareferencia != 1) {
      $medico_actual = $model_medico->GetMedicoByDniAndEstablecimiento($usuario_actual->dni, $farmacia->id_establecimiento);
      $medico = $medico_actual->id;
    }
    $model_citas = new Cita();
    $citas = $model_citas->AllCitaConfirmaAtencionFecha($fechaDesde, $dni_beneficiario, $nombre_beneficiario, $medico, $estado);
    $cantidad = $model_citas->GetCountCitaConfirmaAtencionFecha($fechaDesde, $dni_beneficiario, $nombre_beneficiario, $medico, $estado);
    //print_r($recetas);exit();
    return view('frontend.receta_vales.all_citas_medico_atenciones', compact('citas', 'cantidad', 'farmacia', 'fechaDesde',  'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nombre_beneficiario', 'estado', 'flagemergenciareferencia', 'id_atencione'));
  }

  public function obtener_consultorio_cita($id_establecimiento, $tipodoc_beneficiario, $dni_beneficiario)
  {

    $id_user = Auth::user()->id;
    $model_usuario = new User;
    $establecimientotot = $model_usuario->GetById($id_user);
    $flagservicios = $establecimientotot->flagservicios;

    
    $consultorio = null;
    if ($flagservicios == 1) {
      $model_cita = new Cita();
      //$consultorio = $model_cita->GetServicioCitaByAseguradoAndEstablecimiento($tipodoc_beneficiario,$dni_beneficiario,$id_establecimiento);
      $consultorio = $model_cita->GetServicioEstablecimiento($id_establecimiento);
    } else {
      $model_cita = new Cita();
      //$consultorio = $model_cita->GetServicioCitaByAseguradoAndEstablecimiento($tipodoc_beneficiario,$dni_beneficiario,$id_establecimiento);
      //$consultorio = $model_cita->GetServicioEstablecimiento($id_establecimiento);

      $model_consultorio = new Consultorio;
      $consultorio = $model_consultorio->getConsultorioPorEstablecimiento($id_establecimiento, $establecimientotot->id_consultorio);
    }
    
    return $consultorio;
  }



  public function obtener_consultorio_cita_general($id_establecimiento, $tipodoc_beneficiario, $dni_beneficiario)
  {

    $model_cita = new Cita();
    $consultorio = $model_cita->GetServicioCitaGeneralByAseguradoAndEstablecimiento($tipodoc_beneficiario, $dni_beneficiario, $id_establecimiento);
    return $consultorio;
  }

  public function anular_cita_radiologia($id_cita)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    
    //=========Limpiamos cita del servicio amarrado a imágenes antes de anular la cita 
    $model_cita = new \App\Models\Cita;
    $cita_lab = $model_cita->GetConsultorioByIdCitaLab($id_cita);
    if ($cita_lab->id_consultorio == 114) {
      $id_pp = 0;
      $cita = Cita::where('id', $id_cita)->first();
      $cita->id_estado_reg = 0;
      $cita->id_estado_cita = 5;
      $cita->updated_at = Carbon::now();
      $cita->updated_us = Auth::user()->id;
      $cita->comentario = "Anulado en admisión/médico";
      $id_pp = $cita->id_pp;
      $cita->save();
      $db_update = DB::table('prestacion_procedimientos')->where('id_citas_multiples_rad', $id_cita)->update(['flag_cita_imagenes' => null]);
      
    } 
    //Anulamos tambien la tabla citas_llamados solo para colas
    $sql = "update citas_llamados set id_estado_reg = 0, id_estado_cita = 5, updated_at = '". Carbon::now() ."', updated_us = ".(int)auth()->user()->id." where id_cita = " . (int)$id_cita;
    DB::select($sql);
    //====================================================================================================
    $array["sw"] = true;
    echo json_encode($array);
  }

  public function anular_cita($id_cita)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    //=========No se permite anular una cita de reevaluación si la atención que la originó ya tiene una cita de laboratorio generada
    $cita_origen_laboratorio = Cita::where('id_cita_lab', $id_cita)
      ->whereNotNull('id_cita_laboratorio_ce')
      ->first();
    if ($cita_origen_laboratorio) {
      $array["sw"] = false;
      $array["msg"] = "No se puede anular la cita porque ya cuenta con una cita de laboratorio";
      echo json_encode($array);
      return;
    }

    $cita_a_anular = Cita::where('id', $id_cita)->first();
    if ($cita_a_anular && intval($cita_a_anular->flag_pp) === 1 && $cita_a_anular->id_solicitud_procedimiento_detalle) {
      $detalle_pp = DB::table('solicitud_procedimientos_detalle')->where('id', $cita_a_anular->id_solicitud_procedimiento_detalle)->first();
      if ($detalle_pp && in_array(intval($detalle_pp->estado), array(3, 6))) {
        $array["sw"] = false;
        $array["msg"] = "No se puede anular la cita porque el procedimiento programado ya fue atendido";
        echo json_encode($array);
        return;
      }
      if ($detalle_pp && intval($detalle_pp->estado) === 2) {
        DB::table('solicitud_procedimientos_detalle')->where('id', $detalle_pp->id)
          ->update(['estado' => 1, 'updated_at' => Carbon::now(), 'updated_us' => Auth::user()->id]);
        DB::table('solicitud_programacion_procedimientos')->where('id', $detalle_pp->id_solicitud)->update(['tiene_pendientes' => true]);
      }
    }
    $detalles_reeval_pp = DB::table('solicitud_procedimientos_detalle')->where('id_cita_reevaluacion', $id_cita)->get();
    if (count($detalles_reeval_pp) > 0) {
      foreach ($detalles_reeval_pp as $d_reeval) {
        $con_cita_pp = DB::table('solicitud_procedimientos_detalle')
          ->where('id_solicitud', $d_reeval->id_solicitud)
          ->where('id_procedimiento', $d_reeval->id_procedimiento)
          ->whereIn('estado', [2, 3, 6])
          ->exists();
        if ($con_cita_pp) {
          $array["sw"] = false;
          $array["msg"] = "No se puede anular la cita de reevaluación porque el procedimiento programado ya cuenta con cita. Primero anule la cita del procedimiento";
          echo json_encode($array);
          return;
        }
      }
      DB::table('solicitud_procedimientos_detalle')->where('id_cita_reevaluacion', $id_cita)
        ->update(['id_cita_reevaluacion' => null, 'updated_at' => Carbon::now(), 'updated_us' => Auth::user()->id]);
    }

    //=========Limpiamos cita del servicio amarrado al laboratorio antes de anular la cita de laboratorio
    $model_cita = new \App\Models\Cita;
    $cita_lab = $model_cita->GetConsultorioByIdCitaLab($id_cita);
    if ($cita_lab->id_consultorio == 113) {
      $model_laboratorio = new \App\Models\Laboratorio;

      //Limpiamos la referencia huérfana en prestaciones antes que el SP limpie el enlace en citas
      $cita_origen_de_laboratorio = Cita::where('id_cita_laboratorio_ce', $id_cita)->first();
      if ($cita_origen_de_laboratorio) {
        DB::table('prestaciones')
          ->where('id_cita', $cita_origen_de_laboratorio->id)
          ->update(['id_cita_dependiente_laboratorio' => null]);
      }

      $result = $model_laboratorio->limpiarCitaDeLaboratorioParaReprogramar($id_cita);

      $cita = Cita::where('id', $id_cita)->first();
      $cita->id_estado_reg = 0;
      $cita->id_estado_cita = 5;
      $cita->updated_at = Carbon::now();
      $cita->updated_us = Auth::user()->id;
      $cita->estado_asistencia_paciente = 1;
      $cita->obs_asistencia_paciente = "Anulado en admisión";
      $cita->save();
    } elseif ($cita_lab->id_consultorio == 114) {
      $id_pp = 0;
      $cita = Cita::where('id', $id_cita)->first();
      $cita->id_estado_reg = 0;
      $cita->id_estado_cita = 5;
      $cita->updated_at = Carbon::now();
      $cita->updated_us = Auth::user()->id;
      $id_pp = $cita->id_pp;
      $cita->save();
      $db_update = DB::table('prestacion_procedimientos')->where('id', $id_pp)->update(['flag_cita_imagenes' => null]);
      
    } else {
      //=========Limpiamos el enlace de reevaluación en la cita hija que apunta a la cita que se está anulando
      $cita_reevaluacion = Cita::where('id_cita_lab', $id_cita)->first();
      if ($cita_reevaluacion) {
        $cita_reevaluacion->id_cita_lab = null;
        $cita_reevaluacion->flag_cita_laboratorio = 0;
        $cita_reevaluacion->save();
      }
      
      $cita = Cita::where('id', $id_cita)->first();
      $cita->id_estado_reg = 0;
      $cita->id_estado_cita = 5;
      $cita->updated_at = Carbon::now();
      $cita->updated_us = Auth::user()->id;
      $cita->save();
      /******************CENTRO QUIRURGICO**************/
      $cqx_cita = CqxCita::where('id_cita','=',$id_cita)->where('id_prestacion','=',0)->first();
      if (!empty($cqx_cita)) {
          $cqx_cita->id_cita = 0; //A SER ANULADO LA CITA SE CAMBIA cqx_cita->id_cita = 0 PARA VOLVER A CREAR NUEVA CITA EN LA LISTA DE PENDIENTE CQX
          $cqx_cita->flag = 1; //EL FLAG LO PONEMOS EN 1 YA QUE 2 ERA CUANDO LA CITA SE REGISTRO, PERO AHORA AL SER ANULADO LO VOLVEMOS A 1
          $cqx_cita->save();
      }
      /******************FIN CENTRO QUIRURGICO**************/

    }
    //Anulamos tambien la tabla citas_llamados solo para colas
    $sql = "update citas_llamados set id_estado_reg = 0, id_estado_cita = 5, updated_at = '". Carbon::now() ."', updated_us = ".(int)auth()->user()->id." where id_cita = " . (int)$id_cita;
    DB::select($sql);
    //====================================================================================================
    $array["sw"] = true;
    echo json_encode($array);
  }

  public function anular_prestacion($id_prestacion)
  {

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $prestacion = Prestaciones::where('id', $id_prestacion)->first();
    $prestacion->id_estado_reg = 0;
    $prestacion->updated_us = Auth::user()->id;
    $prestacion->updated_at = Carbon::now();
    $prestacion->save();

    $cita = Cita::where('id', $prestacion->id_cita)->first();
    $cita->id_estado_reg = 0;
    $cita->id_estado_cita = 5;
    $cita->updated_at = Carbon::now();
    $cita->updated_us = Auth::user()->id;
    $cita->save();

    $sql = "update citas_llamados 
      set id_estado_reg = 0,
      id_estado_cita = 5, 
      updated_at = '".Carbon::now()."',
      updated_us = ".auth()->user()->id."
      where id_cita = " . (int)$prestacion->id_cita;
    DB::select($sql);

    $recetas = Receta_vale::where('id_prestacion', '=', $id_prestacion)->where('estado', '<>', 0)->get();
    foreach ($recetas as $rv) {
      $rv->estado = 0;
      $rv->save();
    }

    $laboratorio = Laboratorio::where('id_prestacion', $id_prestacion)->first();
    if(count($laboratorio)>0):
      $laboratorio->estado = 0;
      $laboratorio->save();
    endif;

    $radiologia = Radiologia::where('id_prestacion', $id_prestacion)->first();
    if(count($radiologia)>0):
      $radiologia->estado = 0;
      $radiologia->save();
    endif;

    $array["sw"] = true;
    echo json_encode($array);
  }

  public function update_historia(Request $request)
  {
    //$usuario = new User;
    $historia = \App\Models\Asegurado_historia::find($request->id);
    //$historia = new \App\Models\Asegurado_historia;
    //$historia->id_asegurado = $request->id_asegurado;
    //$historia->id_establecimiento = 76;
    $historia->nro_historia = $request->nro_historia;
    //$historia->id_estado_reg = 1;
    $historia->updated_at = Carbon::now();
    $historia->updated_us = $request->id_user;
    $historia->save();

    return redirect('/all_citas_medico_paciente')->withFlashSuccess("Se actualizo correctamente la Historia");
  }

  public function update_telefono(Request $request)
  {

    $historia = \App\Models\Asegurado_historia::find($request->id);
    $id_asegurado = $historia->id_asegurado;

    $asegurado = Asegurado::find($id_asegurado);
    $asegurado->telefono = $request->nro_telefono;
    $asegurado->telefono2 = $request->nro_telefono2;
    $asegurado->updated_at = Carbon::now();
    $asegurado->updated_us = $request->id_user;
    $asegurado->save();

    return redirect('/all_citas_medico_paciente')->withFlashSuccess("Se actualizo correctamente el numero de telefono");
  }

  public function all_medico_atenciones_ipress($id_user = 0)
  {
    $model_medico = new \App\Models\Medico();
    $model_usuario = new User;
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $ruta = Route::getCurrentRoute()->getPath();
    if (count($ruta = explode("/", $ruta)) > 1);
    $ruta = $ruta[0];
    if ($ruta == "listar_atenciones_ipress");
    $ruta = "all_atenciones_medico_ipress";
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
    $permiso = $menu[0];
    if ($permiso->lectura == "")
      return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
    $id_user = Auth::user()->id;
    $usuario_actual = $model_usuario->GetById($id_user);
    $flagemergenciareferencia = $usuario_actual->flaghistoria;
    $id_consultorio_actual = $usuario_actual->id_consultorio;

    $dni_beneficiario = "";
    $nombre_beneficiario = "";
    $estado = "0";
    $id_atencione = "0";

    $date = Carbon::now();
    $fechaDesde = $date->format('d-m-Y');
    $fechaHasta = $date->format('d-m-Y');
    //====================
    $model_farma = new Farmacia();
    $farmacia = $model_farma->GetFarmaByUser($id_user);
    $id_farmacia = $farmacia->id;
    $modo_validacion = $farmacia->modo_validacion;
    $medico = 0;

    $medico_encontrado = $model_medico->GetDatosMedicoAndEstablecimiento($usuario_actual->dni, $farmacia->id_establecimiento);
    
    if (count($medico_encontrado)>0) {
      if ($flagemergenciareferencia != 1) {
        $medico_actual = $model_medico->GetMedicoByDniAndEstablecimiento($usuario_actual->dni, $farmacia->id_establecimiento);
        $medico = $medico_actual->id;
      }
      //====================
      $model_consultorio = new Consultorio;
      $consultorio = $model_consultorio->ShowAllConsultoriosSubConsultorio($farmacia->id_establecimiento);
      $model_atencione = new TipoAtencione;
      $atencione = $model_atencione->ShowTipoAtencionAll();      
    } else {
      Session::flash('flash_message', 'No se ha registrado al Medico, no encontrado');
      return redirect('/inicio');
    }
    
    return view('frontend.atenciones.all_atenciones_medico_ipress', compact('farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nombre_beneficiario', 'estado', 'flagemergenciareferencia', 'consultorio', 'medico', 'id_consultorio_actual', 'atencione', 'id_atencione'));
  }

  public function listar_atenciones_ipress(Request $request)
  {

    $model_citas = new Cita();
    $p[] = $request->fecha_ini;
    $p[] = $request->fecha_fin;
    $p[] = $request->dni_beneficiario;
    $p[] = $request->nombre_beneficiario;
    $p[] = 218;
    $p[] = 976;
    $p[] = $request->id_medico;
    $p[] = $request->estado;
    $p[] = $request->id_atencione;
    $p[] = 0;
    $p[] = $request->NumeroPagina;
    $p[] = $request->NumeroRegistros;
    $data = $model_citas->listar_prestacion_ajax($p);
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

  public function listar_feriados($anio, $mes = '')
  {
    $model_cita = new Cita();
    $feriados = $model_cita->GetFeriados($anio, $mes);
    echo json_encode($feriados);
  }

  public function valida_programacion_cita(Request $request)
  {
    /**
     * Respuestas validaciones
     * 90: El paciente no esta registrado en la tabla de asegurados
     */
    $validacion = null;
    $asegurado =  Asegurado::where("nro_doc_ident", $request->nro_documento)->first();
    if (empty($asegurado)) {
      $validacion = new \stdClass;
      $validacion->validacion = 90;
    } else {
      $model_cita = new Cita();
      $validacion = $model_cita->ValidaProgramacionCita($asegurado->id, $request->id_establecimiento, $request->id_sub_consultorio, $request->fecha);
    }
    echo json_encode($validacion);
  }

  public function modal_proxima_cita($tipodoc_beneficiario,$dni_beneficiario)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $medico_turno = new \App\Models\Medico_turno;
    $horario = $medico_turno->GetCitasByDniFecha(strtoupper($dni_beneficiario));

    $id_user = Auth::user()->id;
    $model_usuario = new User;
    $usuario = $model_usuario->GetById($id_user);
    $id_consultorio = $usuario->id_consultorio;
    $flagservicios = $usuario->flagservicios;
    unset($model_usuario);
    
    return view('frontend.citas.modal_proxima_cita', compact('horario', 'id_consultorio', 'flagservicios'));
  }

  public function modal_proxima_cita_servicio($tipodoc_beneficiario,$dni_beneficiario, $servicio, $id_establecimiento)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $medico_turno = new \App\Models\Medico_turno;
    $horario = $medico_turno->GetCitasByDniFechaServicio($dni_beneficiario, $servicio);

    if(count($horario)>0):
      return view('frontend.atenciones.modal_proxima_cita', compact('horario','id_establecimiento'));
    endif;
  }

  public function modal_proxima_cita_servicio2($tipodoc_beneficiario,$dni_beneficiario, $servicio, $id_establecimiento, $id_medico=0)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $medico_turno = new \App\Models\Medico_turno;
    $horario = $medico_turno->GetCitasByDniFechaServicio($dni_beneficiario, $servicio, 1);

    if(count($horario)>0):
      return view('frontend.atenciones.modal_proxima_cita', compact('horario','id_establecimiento','id_medico'));
    endif;
  }
  public function listar_cita_historia_ajax(Request $request)
  {

    $model_citas = new Cita();
    $p[] = $request->fecha_ini;
    $p[] = $request->fecha_fin;
    $p[] = (isset($request->id_servicio) && $request->id_servicio != "") ? $request->id_servicio : 0;
    $p[] = Auth::user()->id_establecimiento;
    $p[] = $request->estado;
    $p[] = $request->NumeroPagina;
    $p[] = $request->NumeroRegistros;
    $data = $model_citas->listar_cita_historia_ajax($p);
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
  
  public function all_atencion_estados_by_servicio($id_user = 0)
  {

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

    $dni_beneficiario = "";
    $nro_receta = "";
    $idconsultorio = 0;
    $estado = "1";

    $date = Carbon::now();
    $fechaDesde = $date->format('d-m-Y');
    $fechaHasta = $date->format('d-m-Y');

    //==================== 
    $model_farma = new Farmacia();
    $farmacia = $model_farma->GetFarmaByUser($id_user);
    $id_farmacia = $farmacia->id;
    $modo_validacion = $farmacia->modo_validacion;
    //====================



    //$dispensador = $model_recetas->AllDispensadorByFarmacia($id_farmacia);
    $model_consultorio = new Consultorio;
    $consultorio = $model_consultorio->ShowConsultorios($id_farmacia);
    //====================
    
    //Item Estados de atenciones
    $model_estados_atenciones = new Prestaciones();
    $estados_atenciones = $model_estados_atenciones->getReporteByRangoDeFechaByConsultorio($fechaDesde, $fechaHasta, $idconsultorio, $id_farmacia, $estado);
    
    return view('frontend.direoss.stocks.form_atencion_estados', compact('estados_atenciones','farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nro_receta', 'consultorio', 'idconsultorio', 'estado'));
      
  }

  public function listar_reporte_atenciones_estados(Request $request){

    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    if (!$request->id_user) :
      return redirect('/all_atencion_estados_by_servicio');
    endif;

    $fechaDesde = $request->fechaDesde;
    $fechaHasta = $request->fechaHasta;
    $idconsultorio = $request->idconsultorio;
    $id_farmacia = $request->id_farmacia;
    $estado = $request->estado;

    //=============================
    $model_farma = new Farmacia();
    $farmacia = $model_farma->GetFarmaByUser($request->id_user);

    //=============================
    $model_consultorio = new Consultorio;
    $consultorio = $model_consultorio->ShowConsultorios($id_farmacia);


    //Item Estados de atenciones
    $model_estados_atenciones = new Prestaciones();
    $estados_atenciones = $model_estados_atenciones->getReporteByRangoDeFechaByConsultorio($fechaDesde, $fechaHasta, $idconsultorio, $id_farmacia, $estado);
    return view('frontend.direoss.stocks.form_atencion_estados', compact('estados_atenciones','fechaDesde', 'fechaHasta', 'farmacia', 'consultorio', 'idconsultorio', 'id_farmacia', 'estado'));
    
  }

  public function modal_proxima_cita_medico($dni_beneficiario,$id_servicio)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $medico_turno = new \App\Models\Medico_turno;
    $horario = $medico_turno->GetCitasByDniFechaServicioProximaCita($dni_beneficiario,$id_servicio);

    $id_user = Auth::user()->id;
    $model_usuario = new User;
    $usuario = $model_usuario->GetById($id_user);
    $id_consultorio = $usuario->id_consultorio;
    $flagservicios = $usuario->flagservicios;
    unset($model_usuario);

    return view('frontend.citas.modal_proxima_cita', compact('horario', 'id_consultorio', 'flagservicios'));
  }

  public function obtener_consultorio_cita_especifico($id_establecimiento, $id_consultorio)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

      $consultorio = null;
      $model_cita = new Cita();
      $consultorio = $model_cita->GetServicioEstablecimientoEspecifico($id_establecimiento, $id_consultorio);

    return $consultorio;
  }
  public function obtenerIds($array){
    $p_ids = "";
    $p_ids .= "{";
    foreach ($array as $key => $value) :
     if ($array[$key] != "") {
       $p_ids .= "{";
        $p_ids .= $array[$key];
        $p_ids .= "},";
      }
     endforeach;
     if (strlen($p_ids) > 1) $p_ids = substr($p_ids, 0, -1);
     $p_ids .= "}";
     return $p_ids; 
  }
  
    function insertar($array,$elemento,$pos) {
          
      if($pos < 0) {
          return;
      }
          
      ## si la posicion es mayor que el tamaño de la lista
      ## el nodo se inserta al final
      if($pos>=count($array) ) {
          array_push($array,$elemento);
          return;
      }
          
      $listaaux=array(); # array auxiliar
          ## buscamos la posicion en el array, para ello las posiciones anteriores
          ## las guardamos en el array auxiliar
      for($cont=0;$cont<$pos;$cont++) {
          $listaaux[] = array_shift($array);
      }
      
          ## ahora se inserta el elemento al principio del array original
      array_unshift($array,$elemento);
  
          ## ahora recorremos el array auxiliar desde el final y vamos insertando
          ## sus elementos al principio del array original
      if(count($listaaux)>0) {
          for($i=count($listaaux)-1;$i>=0;$i--) {
              array_unshift($array,$listaaux[$i]);
          }
      }
   
      return $array;
  } 
  
  
  public function moverArrayAdelante($arr, $key, $before){
    $keys = array_keys($arr);
    if (in_array($key, $keys) && in_array($before, $keys)) {
        $newArr = [];
        foreach ($arr as $idx => $value) {
            switch ($idx) {
                case $key :
                    continue;
                case $before:
                    $newArr[$key] = $arr[$key];
                    $newArr[$before] = $value;
                    break;
                default:
                    $newArr[$idx] = $value;
                    break;
            }
        }
  
        return $newArr;
    }
  
    return $arr;
  }
  


  public function  pruebas(Request $request){
    if (!Auth::check()):
      return redirect('/auth/login');
  endif;
  $nombreAsegurado = "";
  $ids =json_decode((json_encode($request->ids)),true);
  $str = substr($ids, 1);
  $arr2 = substr($str, 0, -1);
  $ids_Integer = [];
  $array = explode(",", $arr2);
    if (isset($array)) :
        
     $p_ids = $this->obtenerIds($array);
  
      if($p_ids !=""):
       $data=0;
        $cad = "Select sp_updateOrden(?)";
        $data = DB::select($cad,array($p_ids));
      
    
  
       
      endif;
   endif;

   //$variableDeApoyo = json_decode((json_encode($request->variableapoyo)),true);
  
  
   $model_tv = new Tv();
   //llamar al primero de la lista
   foreach($array as $index){  
            //Convertimos a entero
            $index = intval($index);
            $id_cita = $model_tv->llamar_citado_tv($index);   
            
          if ($id_cita > 0) {
            
            //$cita = Cita::where('id','=',$id_cita)->first();
            $cade = "SELECT * FROM citas_llamados WHERE id_cita = ".(int)$id_cita." limit 1;";
            $cita = DB::select($cade);
            $cita = $cita[0];
            $cad = "select CONCAT (' ',c2.nombre,' ',c2.paterno,' ',c2.materno)
             from citas_llamados c inner join asegurados c2  
            on c.id_asegurado = c2.id where c.id_cita = ".$id_cita;
            $nombreAsegurado =DB::select($cad);
            $nombreAsegurado2 = $nombreAsegurado[0]->concat;
              
             $cantidad_llamada = $cita->cantidad_llamada;
              $data['cantidadLlamadas'] = $cantidad_llamada;
              $data['ticket'] = $cita->id_cita;
              $data['posicionfinal'] = $cita->indicadorfinal;
              $data['nombreAsegurado'] = $nombreAsegurado2;
          } 
         
              break; 
      
  }
   $data['ids'] =$array;
   
  
  
   return $data;
  
  }
  
  
  public function finalCola(Request $request) {
    if (!Auth::check()):
        return redirect('/auth/login');
    endif;
  
    $listadecodificada =json_decode((json_encode($request->listaPresencial)),true);
    
     $contador_finalcola =0;
    // $listarevert = $reques
     $respuesta=[];
     $data = [];
     $cantidad_llamada = 0;
     $indiceFinalCola = (isset($request->indicePosponer) && $request->indicePosponer != "") ? $request->indicePosponer : 0;
     $model_tv = new Tv();
    $ticketpueba = 0; 
    $idticket="";
    $llamadas="";
    $boleanoLastAlert =false;
  
     foreach($listadecodificada as $index){
             $id_cita = $index;
             if ($id_cita > 0) {
                 //$ticket = Cita::where('id','=',$id_cita)->first();
                 $cad = "SELECT * FROM citas_llamados WHERE id_cita = ".(int)$id_cita." limit 1;";
                 $ticket = DB::select($cad);
                 $ticket = $ticket[0];
                 
                 $cantidad_llamada = $ticket->cantidad_llamada;
                 $data['cantidadLlamadas'] = $cantidad_llamada;
                 $data['ticket'] = $ticket->id_cita;
                 $idticket = $ticket->id_cita;
                 $llamadas = $cantidad_llamada;
                 
             }   
        
                 break; 
  
         
     }
    
  foreach ($listadecodificada as $key => $value) {
    $posicion = array_search( $value, $listadecodificada );
   break;
  }
  $data['posicion'] = $posicion;
  $data['nuevovalorarray'] = $posicion +1 ;
  
  $funcionApoyo = new CitaController;
  $arrayManipulado=[];
  $retorno=0;
  
  if($cantidad_llamada > 1){
    if($indiceFinalCola<2){
   
      $arrayManipulado=$funcionApoyo->moverArrayAdelante($listadecodificada,$data['nuevovalorarray'],$posicion);
  
    }else{
      $boleanoLastAlert = true;
      $array_eliminado= array_shift($listadecodificada);
     
      //se envia al final  
       array_push($listadecodificada,$array_eliminado);
      
       $arrayManipulado = $listadecodificada;
      //$cita=Cita::where('id','=',$array_eliminado)->first();
      //$cita->id_estado_tv = 0; // 2
      //$cita ->indicadorfinal = 0;
      //$cita->save();
      $cad = "UPDATE citas_llamados SET id_estado_tv = 0, indicadorfinal = 0 WHERE id_cita = ".(int)$array_eliminado.";";
      DB::select($cad);
  
    }
    
  }
  
  //actualizar las posiciones en el nuevo array
  
    if (isset($arrayManipulado)) :
        
      $p_ids = "";
     $p_ids .= "{";
     foreach ($arrayManipulado as $key => $value) :
      if ($arrayManipulado[$key] != "") {
        $p_ids .= "{";
         $p_ids .= $arrayManipulado[$key];
         $p_ids .= "},";
       }
      endforeach;
      if (strlen($p_ids) > 1) $p_ids = substr($p_ids, 0, -1);
      $p_ids .= "}"; 
      if($p_ids !=""):
       $data=0;
        $cad = "Select sp_updateOrden(?)";
        $data = DB::select($cad,array($p_ids));
      
     foreach ($data as $d) {
          $retorno = $data;
      
      }
  
       
      endif;
   endif;
  
   
  
  
      $CitaAct = $idticket;
      
      //$cita = Cita::where('id','=',$CitaAct)->first(); 
      //$cita->id_estado_tv = 0; // 2
      // $cita->cantidad_llamada = $llamadas;
      // $cita->fecha_llamada = null;
      // $cita->orden_llamada = null;
      // $cita->estado_llamada = "0";
      // $cita->indicadorfinal=  $indiceFinalCola + 1 ;
  
      // $cita->save();
      //return array((int)$llamadas,(int)($indiceFinalCola + 1),(int)$CitaAct);
      $cad = "UPDATE citas_llamados SET id_estado_tv = 0, cantidad_llamada = ".(int)$llamadas.", fecha_llamada = null, orden_llamada = null, estado_llamada = '0', indicadorfinal = ".(int)($indiceFinalCola + 1)." WHERE id_cita = ".(int)$CitaAct." ;";
      $affected = DB::update($cad);
      // $affected = DB::update("UPDATE citas_llamados SET id_estado_tv = 0, cantidad_llamada = 0, fecha_llamada = null, orden_llamada = null, estado_llamada = '0', indicadorfinal = ? WHERE id_cita = ?", [$indiceFinalCola + 1, $CitaAct]);
      Log::info('Actualización final citas_llamados:', ['id_cita' => $CitaAct, 'affected' => $affected]);

       $data['indicefinal'] = $indiceFinalCola + 1;
       $data['indice'] = $CitaAct; 
       $data['llamadas'] = $CitaAct;
       $data['boleanoLastAlert'] = $boleanoLastAlert;
    // $cita = Cita::where('id','=',$id_cita)->first();
    // $cita->estado_presencia = null;
    // $cita->fecha_presencia = null;
    // $cita->estado_triaje = null;
    // $cita->save();
  
   
    echo json_encode($data);
  
  }  

  

  public function Cancelar(Request $request){

    if (!Auth::check()):
      return redirect('/auth/login');
  endif;
  $codigos = json_decode((json_encode($request->ids)),true);

      for ($i=0; $i <count($codigos) ; $i++) { 
        
        // $cita = Cita::where('id','=',$codigos[0])->first();
        // $cita->id_estado_tv=0;
        // $cita->estado_llamada='0';
        // $cita->save();
        $cad = "UPDATE citas_llamados SET id_estado_tv = 0, estado_llamada = '0' WHERE id_cita = ".(int)$codigos[0].";";
        DB::select($cad);
        break;
        
      }
  

    }



     //$medico_encontrado = $model_medico->GetDatosMedicoAndEstablecimiento($usuario_actual->dni, $farmacia->id_establecimiento);  
//dd($user);
    //if (count($medico_encontrado)>0) {

      public function buscar_ubicacion($ubicacion){
        if (!Auth::check()):
          return redirect('/auth/login');
        endif;
        $model = new Cita();
        $result = $model->buscar_servicio_x_establecimiento($ubicacion);
        return $result;
    
        }

  public function atender_en_consultorio_actual($id_cita, $id_medico, $id_tipo_atencion, $id_servicio) {
    
    if (!Auth::check()):
      return redirect('/auth/login');
    endif;

    $id_establecimiento = Auth::user()->id_establecimiento;
    $model_tipo_atencion = new TipoAtencione;
    $tipo_atencion = $model_tipo_atencion->VerTipoAtencion($id_tipo_atencion);
    
    $model_medico_turno = new \App\Models\Medico_turno;
    // Consultar turnos programados del profesional
    $data_turno = $model_medico_turno->getTurnoProgramadoDelProfesional($id_medico, $id_establecimiento, $id_servicio, $tipo_atencion->nombre);
    
    if (count($data_turno) > 0) {
      // Atender la cita programada con el Profesional indicado en la variable $id_medico
      return redirect('/formulario_atencion_tomado/'.$id_cita.'/'.$id_medico.'/'.$data_turno[0]->id_sub_consultorio);

    } else {
      // Redireccionar a la lista de Atenciones Medicas indicando que el Profesional no se encuentra programado
      Session::flash('flash_danger', 'No puede realizar la atencion, porque no se encuentra programado para este horario.');
      return redirect('/all_citas_medico_atenciones');
    }
  }

  public function update_siteds(Request $request)
  {
    $siteds = Cita::find($request->id);
    $siteds->codigo_siteds = $request->codigo_siteds;
    $siteds->updated_at_cod_siteds = Carbon::now();
    $siteds->updated_us_cod_siteds = $request->id_user;
    $siteds->save();
/*     if($request->retorno==1):
      return redirect('/all_citas_medico_paciente')->withFlashSuccess("Se actualizo correctamente el SITEDS");
    else:
      return redirect('/all_citas_medico_atenciones')->withFlashSuccess("Se actualizo correctamente el SITEDS");
    endif; */
    return response()->json([
      'message' => 'Se actualizó correctamente el código siteds.',
      "codigo_siteds"=> $request->codigo_siteds,
      "id" => $request->id
    ], 200);
  }

  public function buscar_siteds_atencion($fecha_atencion,$dni_beneficiario){

    $modelAtencion = new Cita();
    $data = $modelAtencion->busca_Siteds_Atencion($dni_beneficiario,$fecha_atencion);   
    return json_encode($data);
  }

  function buscarUltimaAtencionAsegurado(Request $request)  {
    $query = DB::table('prestaciones as p')
                ->select([
                    'p.id as prestacion_id',
                    'c.id as cita_id',
                    'a.id as asegurado_id',
                    'e.id as establecimiento_id',
                    'sc.id as sub_consultorio_id',
                    'c2.id as consultorio_id',
                    'a.nro_doc_ident as asegurado_numero_documento',
                    'a.nombre as asegurado_nombre',
                    'a.paterno as asegurado_paterno',
                    'a.materno as asegurado_materno',
                    'a.fecha_nac as asegurado_fecha_nacimiento',
                    'a.grado as asegurado_grado',
                    'a.id_tipo_beneficiario',
                    'tdia.descripcion as asegurado_tipo_doc_identidad',
                    'tb.descripcion as tipo_beneficiario',
                    'a.sexo as asegurado_sexo',
                    'p.fecha_atencion as prestacion_fecha_atencion',
                    'p.antecedente as prestacion_antecedente',
                    'p.antecedente_familiar as prestacion_antecedente_familiar',
                    'p.codigo_upss as prestacion_codigo_upss',
                    DB::raw("EXTRACT(YEAR FROM AGE(CURRENT_DATE, TO_DATE(a.fecha_nac, 'DD/MM/YYYY'))) as asegurado_edad"),
                    'm.dni as medico_nro_doc_ident',
                    'm.nombre as medico_nombre',
                    'm.paterno as medico_paterno',
                    'm.materno as medico_materno',
                    'm.rne as medico_rne',
                    'm.colegiatura as medico_colegiatura',
                    'm.id_profesion as medico_profesion_id',
                    'p2.nombre as medico_profesion',
                    'tdi.descripcion as medico_tipo_documento',
                    'e2.id as especializacion_id',
                    'e2.nombre as medico_especializacion',
                    'e.nombre as id_establecimiento_nombre',
                    'e.nivel as establecimiento_nivel',
                    'a.direccion_procedencia as asegurado_direccion_procedencia',
                    'a.direccion_nacimiento as asegurado_direccion_nacimiento',
                    'ah.id as asegurado_historia_id',
                    'ah.nro_historia as numero_historia',
                    'c2.id as servicio_id',
                    'c2.nombre as servicio'
                ])
                ->join('citas as c', 'p.id_cita', '=', 'c.id')
                ->join('asegurados as a', 'p.id_asegurado', '=', 'a.id')
                ->leftJoin('tipo_documento_identidad as tdia', 'tdia.id', '=', 'a.id_tipo_doc_ident')
                ->join('medicos as m', 'c.id_medico', '=', 'm.id')
                ->join('establecimientos as e', 'c.id_establecimiento', '=', 'e.id')
                ->join('asegurado_historias as ah', function($join) {
                  $join->on('ah.id_asegurado', '=', 'a.id')->on('ah.id_establecimiento', '=', 'e.id');
                })
                ->join('sub_consultorios as sc', 'sc.id', '=', 'p.id_sub_consultorio')
                ->join('consultorios as c2', 'c2.id', '=', 'sc.id_consultorio')
                ->join('tipo_beneficiarios as tb', 'tb.id', '=', 'a.id_tipo_beneficiario')
                ->leftJoin('profesiones as p2', 'm.id_profesion', '=', 'p2.id')
                ->join('tipo_documento_identidad as tdi', 'tdi.id', '=', 'm.tipo_documento')
                ->leftJoin('especializaciones as e2', 'e2.id', '=', 'm.id_especializacion');
                if (isset($request->id_tipo_doc_ident) && !empty($request->id_tipo_doc_ident)) {
                  $query->where('a.id_tipo_doc_ident', $request->id_tipo_doc_ident);
                }
                if (isset($request->nro_doc_ident) && !empty($request->nro_doc_ident)) {
                    $query->where('a.nro_doc_ident', $request->nro_doc_ident);
                }
                if (isset($request->consultorio_id) && !empty($request->consultorio_id)) {
                    $query->where('c2.id', $request->consultorio_id);
                }
                if (isset($request->id_establecimiento) && !empty($request->id_establecimiento)) {
                    $query->where('c.id_establecimiento', $request->id_establecimiento);
                }
                // if (isset($request->fecha_atencion) && !empty($request->fecha_atencion)) {
                //   $query->whereRaw("DATE(p.fecha_atencion) = ?", [$request->fecha_atencion]);
                // }

    $prestacion = $query->orderBy('p.id', 'desc')->first();

    if (is_null($prestacion)) {
      return response()->json([
        "message" => "No se encontró ninguna cita médica para el número de documento <b>$request->nro_doc_ident</b>. Asegúrate de ingresar los datos correctos.",
        "code" => "404",
      ], 404);
    }

    // $enfermeria = Enfermeria::where("id_prestacion", $prestacion->prestacion_id)->first();
    // if (isset($enfermeria)) {
    //   return response()->json([
    //     "message" => "Ya hay una nota de enfermería registrada para esta cita médica. Revisa o actualiza la nota existente.",
    //     "code" => "409",
    //   ], 409);
    // }
   
    $aseguradoModel = new Asegurado;
    $nro_historia_clinica = $aseguradoModel->existe_nro_historia_receta_vale($prestacion->asegurado_numero_documento, $prestacion->establecimiento_id);  
    if (isset($nro_historia_clinica)) {
      $prestacion->nro_historia_clinica = strval($nro_historia_clinica);
    }

    $lastAntecedente = antecedentesEnfermeria::join('enfermerias', 'antecedentes_enfermerias.id_enfermeria', '=', 'enfermerias.id')
    ->join('prestaciones', 'enfermerias.id_prestacion', '=', 'prestaciones.id')
    ->where('prestaciones.id_asegurado', $prestacion->asegurado_id)
    ->orderBy('antecedentes_enfermerias.created_at', 'desc')
    ->select('antecedentes_enfermerias.*')
    ->first();

    if (isset($lastAntecedente)) {
      $prestacion->antecente = $lastAntecedente;
    }

    return response()->json([
      "message" => "busqueda exitosa",
      "data" => $prestacion
    ]);
  }

  function buscarAtencionesForEnfermeriaCE(Request $request) {
    // $query = DB::table('prestaciones as p')
    //             ->select([
    //               'p.id as prestacion_id',
    //               'c.id as cita_id',
    //               'e.id as establecimiento_id',
    //               'a.id as asegurado_id',
    //               'tdia.id as tipo_documento_identidad_id',
    //               'sc.id as sub_consultorio_id',
    //               'c2.id as consultorio_id',
    //               'p.fecha_atencion as prestacion_fecha_atencion',
    //               'tdia.descripcion as asegurado_tipo_documento_identidad',
    //               'a.nro_doc_ident as asegurado_nro_doc_ident',
    //               'a.cip as asegurado_cip',
    //               'a.nombre as asegurado_nombre',
    //               'a.paterno as asegurado_paterno',
    //               'a.materno as asegurado_materno',
    //               'a.grado as asegurado_grado',
    //               'a.sexo as asegurado_sexo',
    //               DB::raw("EXTRACT(YEAR FROM AGE(CURRENT_DATE, TO_DATE(a.fecha_nac, 'DD/MM/YYYY'))) as asegurado_edad"),
    //               'c2.nombre as consultorio',
    //               'en.id as enfermeria_id',
    //               'm.dni as medico_dni',
    //               'm.nombre as medico_nombre',
    //               'm.paterno as medico_paterno',
    //               'm.materno as medico_materno',
    //               'u.id as enfermero_id',
    //               'u.dni as enfermero_dni',
    //               'u.name as enfermero_name',
    //               'u.paterno as enfermero_paterno',
    //               'u.materno as enfermero_materno'
    //             ])
    //             ->join('citas as c', 'p.id_cita', '=', 'c.id')
    //             ->join('establecimientos as e', 'c.id_establecimiento', '=', 'e.id')
    //             ->join('asegurados as a', 'p.id_asegurado', '=', 'a.id')
    //             ->join('tipo_documento_identidad as tdia', 'tdia.id', '=', 'a.id_tipo_doc_ident')
    //             ->join('sub_consultorios as sc', 'sc.id', '=', 'p.id_sub_consultorio')
    //             ->join('consultorios as c2', 'c2.id', '=', 'sc.id_consultorio')
    //             ->join('medicos as m', 'p.id_medico', '=', 'm.id')
    //             ->leftJoin('enfermerias as en', function ($join) {
    //               $join->on('en.id_prestacion', '=', 'p.id')->where('en.id_tipo_atencion', '=', 1);
    //             })
    //             ->leftJoin('users as u', 'en.created_us', '=', 'u.id');
    // if (isset($request->id_tipo_doc_ident) && !empty($request->id_tipo_doc_ident)) {
    //   $query->where('a.id_tipo_doc_ident', $request->id_tipo_doc_ident);
    // }
    // if (isset($request->nro_doc_ident) && !empty($request->nro_doc_ident)) {
    //     $query->where('a.nro_doc_ident', $request->nro_doc_ident);
    // }
    // if (isset($request->consultorio_id) && !empty($request->consultorio_id)) {
    //     $query->where('c2.id', $request->consultorio_id);
    // }
    // if (isset($request->id_establecimiento) && !empty($request->id_establecimiento)) {
    //     $query->where('p.id_establecimiento', $request->id_establecimiento);
    // }
    // if (isset($request->fecha_atencion) && !empty($request->fecha_atencion)) {
    //   $query->whereRaw("DATE(p.fecha_atencion) >= ?", [$request->fecha_atencion]);
    // }
    $id_establecimiento = Auth::user()->id_establecimiento;
    $establecimiento = Establecimiento::find($id_establecimiento);
    $establecimiento_nivel = 3;
    if (isset($establecimiento) && isset($establecimiento->nivel) && !empty($establecimiento->nivel)) {
      $establecimiento_nivel = intval($establecimiento->nivel);
    }

    $query = DB::table('citas as c')
    ->select([
        'p.id as prestacion_id',
        'c.id as cita_id',
        'e.id as establecimiento_id',
        'a.id as asegurado_id',
        'tdia.id as tipo_documento_identidad_id',
        'sc.id as sub_consultorio_id',
        'c2.id as consultorio_id',
        'p.fecha_atencion as prestacion_fecha_atencion',
        'c.fecha_hora as cita_fecha_hora',
        'tdia.descripcion as asegurado_tipo_documento_identidad',
        'a.nro_doc_ident as asegurado_nro_doc_ident',
        'a.cip as asegurado_cip',
        'a.nombre as asegurado_nombre',
        'a.paterno as asegurado_paterno',
        'a.materno as asegurado_materno',
        'a.grado as asegurado_grado',
        'a.sexo as asegurado_sexo',
        DB::raw("extract(year from AGE(CURRENT_DATE, TO_DATE(a.fecha_nac, 'DD/MM/YYYY'))) as asegurado_edad"),
        'c2.nombre as consultorio',
        // 'en.id as enfermeria_id',
        'm.dni as medico_dni',
        'm.nombre as medico_nombre',
        'm.paterno as medico_paterno',
        'm.materno as medico_materno',
        // 'u.id as enfermero_id',
        // 'u.dni as enfermero_dni',
        // 'u.name as enfermero_name',
        // 'u.paterno as enfermero_paterno',
        // 'u.materno as enfermero_materno',
        'c.id_estado_cita',
        DB::raw("(SELECT COUNT(*) FROM enfermerias e WHERE e.id_tipo_atencion = 1 AND e.id_ext_enfermeria = c.id LIMIT 1) as count_notas_enfermeria")
    ])
    ->join('establecimientos as e', 'c.id_establecimiento', '=', 'e.id')
    ->join('asegurados as a', 'c.id_asegurado', '=', 'a.id')
    ->join('tipo_documento_identidad as tdia', 'tdia.id', '=', 'a.id_tipo_doc_ident')
    ->join('sub_consultorios as sc', 'c.id_sub_consultorio', '=', 'sc.id')
    ->join('consultorios as c2', 'c2.id', '=', 'sc.id_consultorio');
    if ($establecimiento_nivel == 3) {
      $query->join('prestaciones as p', 'c.id', '=', 'p.id_cita')
            ->join('medicos as m', 'p.id_medico', '=', 'm.id');
    } else {
      $query->leftJoin('prestaciones as p', 'c.id', '=', 'p.id_cita')
            ->leftJoin('medicos as m', 'p.id_medico', '=', 'm.id');
    }
    // ->leftJoin('enfermerias as en', function($join) {
    //     $join->on('en.id_prestacion', '=', 'p.id')->where('en.id_tipo_atencion', '=', 1);
    // })
    // ->leftJoin('users as u', 'en.created_us', '=', 'u.id');
    if (isset($request->id_tipo_doc_ident) && !empty($request->id_tipo_doc_ident)) {
      $query->where('a.id_tipo_doc_ident', $request->id_tipo_doc_ident);
    }
    if (isset($request->nro_doc_ident) && !empty($request->nro_doc_ident)) {
        $query->where('a.nro_doc_ident', $request->nro_doc_ident);
    }
    if (isset($request->consultorio_id) && !empty($request->consultorio_id)) {
        $query->where('c2.id', $request->consultorio_id);
    }
    if (isset($request->id_establecimiento) && !empty($request->id_establecimiento)) {
        $query->where('c.id_establecimiento', $request->id_establecimiento);
    }
    if (isset($request->fecha_atencion) && !empty($request->fecha_atencion)) {
      $query->whereRaw("DATE(c.fecha_hora) >= ?", [$request->fecha_atencion]);
    }
    // Log::info(["query" => $query->toSql(), "bindings" => $query->getBindings()]);
    $atenciones = $query->where('c.id_estado_cita', '<>', 5)->orderBy('p.id', 'desc')->get();

    return response()->json([
      "success" => true,
      "code" => 200,
      "message" => "busqueda exitosa",
      "data" => $atenciones
    ]);
  }

  function getAtentionByPrestacionIdFormEnfermeriaCE($cita_id)  {
    $query = DB::table('citas as c')
                ->join('asegurados as a', 'c.id_asegurado', '=', 'a.id')
                ->leftJoin('tipo_documento_identidad as tdia', 'tdia.id', '=', 'a.id_tipo_doc_ident')
                ->leftJoin('medicos as m', 'c.id_medico', '=', 'm.id')
                ->join('establecimientos as e', 'c.id_establecimiento', '=', 'e.id')
                ->join('asegurado_historias as ah', function ($join) {$join->on('ah.id_asegurado', '=', 'a.id')->on('ah.id_establecimiento', '=', 'e.id');})
                ->join('sub_consultorios as sc', 'sc.id', '=', 'c.id_sub_consultorio')
                ->join('consultorios as c2', 'c2.id', '=', 'sc.id_consultorio')
                ->join('tipo_beneficiarios as tb', 'tb.id', '=', 'a.id_tipo_beneficiario')
                ->leftJoin('profesiones as p2', 'm.id_profesion', '=', 'p2.id')
                ->leftJoin('tipo_documento_identidad as tdi', 'tdi.id', '=', 'm.tipo_documento')
                ->leftJoin('especializaciones as e2', 'e2.id', '=', 'm.id_especializacion')
                ->leftJoin('prestaciones as p', 'p.id_cita', '=', 'c.id')
                ->where('c.id', $cita_id)
                ->select([
                    'p.id as prestacion_id',
                    'p.fecha_firma as fecha_firma_prestacion',
                    'c.id as cita_id',
                    'a.id as asegurado_id',
                    'e.id as establecimiento_id',
                    'sc.id as sub_consultorio_id',
                    'c2.id as consultorio_id',
                    'a.nro_doc_ident as asegurado_numero_documento',
                    'a.nombre as asegurado_nombre',
                    'a.paterno as asegurado_paterno',
                    'a.materno as asegurado_materno',
                    'a.fecha_nac as asegurado_fecha_nacimiento',
                    'a.grado as asegurado_grado',
                    'a.id_tipo_beneficiario',
                    'tdia.descripcion as asegurado_tipo_doc_identidad',
                    'tb.descripcion as tipo_beneficiario',
                    'a.sexo as asegurado_sexo',
                    'p.fecha_atencion as prestacion_fecha_atencion',
                    'p.antecedente as prestacion_antecedente',
                    'p.antecedente_familiar as prestacion_antecedente_familiar',
                    'p.codigo_upss as prestacion_codigo_upss',
                    DB::raw("EXTRACT(YEAR FROM AGE(CURRENT_DATE, TO_DATE(a.fecha_nac, 'DD/MM/YYYY'))) as asegurado_edad"),
                    'm.dni as medico_nro_doc_ident',
                    'm.nombre as medico_nombre',
                    'm.paterno as medico_paterno',
                    'm.materno as medico_materno',
                    'm.rne as medico_rne',
                    'm.colegiatura as medico_colegiatura',
                    'm.id_profesion as medico_profesion_id',
                    'p2.nombre as medico_profesion',
                    'tdi.descripcion as medico_tipo_documento',
                    'e2.id as especializacion_id',
                    'e2.nombre as medico_especializacion',
                    'e.nombre as id_establecimiento_nombre',
                    'e.nivel as establecimiento_nivel',
                    'a.direccion_procedencia as asegurado_direccion_procedencia',
                    'a.direccion_nacimiento as asegurado_direccion_nacimiento',
                    'ah.id as asegurado_historia_id',
                    'ah.nro_historia as numero_historia',
                    'c2.id as servicio_id',
                    'c2.nombre as servicio',
                ]);

    $prestacion = $query->orderBy('p.id', 'desc')->first();

    if (is_null($prestacion)) {
      return response()->json([
        "message" => "No se encontró ninguna cita médica",
        "code" => "404",
      ], 404);
    }

    // $enfermeria = Enfermeria::where("id_prestacion", $prestacion_id)->first();
    // if (isset($enfermeria)) {
    //   return response()->json([
    //     "message" => "Ya hay una nota de enfermería registrada para esta cita médica. Revisa o actualiza la nota existente.",
    //     "code" => "409",
    //   ], 409);
    // }

    $aseguradoModel = new Asegurado;
    $nro_historia_clinica = $aseguradoModel->existe_nro_historia_receta_vale($prestacion->asegurado_numero_documento, $prestacion->establecimiento_id);  
    if (isset($nro_historia_clinica)) {
      $prestacion->nro_historia_clinica = strval($nro_historia_clinica);
    }

    $lastAntecedente = antecedentesEnfermeria::join('enfermerias', 'antecedentes_enfermerias.id_enfermeria', '=', 'enfermerias.id')
    ->join('prestaciones', 'enfermerias.id_prestacion', '=', 'prestaciones.id')
    ->where('prestaciones.id_asegurado', $prestacion->asegurado_id)
    ->orderBy('antecedentes_enfermerias.created_at', 'desc')
    ->select('antecedentes_enfermerias.*')
    ->first();

    if (isset($lastAntecedente)) {
      $prestacion->antecente = $lastAntecedente;
    }

    return response()->json([
      "message" => "busqueda exitosa",
      "data" => $prestacion
    ]);
  }

  public function modal_proxima_cita_cqx($tipodoc_beneficiario,$dni_beneficiario)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $medico_turno = new \App\Models\Medico_turno;
    $horario = $medico_turno->GetCitasByDniFecha(strtoupper($dni_beneficiario));

    $id_user = Auth::user()->id;
    $model_usuario = new User;
    $usuario = $model_usuario->GetById($id_user);
    $id_consultorio = $usuario->id_consultorio;
    $flagservicios = $usuario->flagservicios;
    unset($model_usuario);
    
    return view('frontend.citas.modal_proxima_cita_cqx', compact('horario', 'id_consultorio', 'flagservicios'));
  }

  public function phpinfo(){
    return view('frontend.citas.phpinfo');
  }
}
