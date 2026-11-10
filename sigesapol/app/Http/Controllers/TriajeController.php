<?php

namespace App\Http\Controllers;

use App\Models\Consultorio;
use App\Models\TipoAtencione;
use App\Models\Totem;

use App\Models\Farmacia;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests;
use App\Models\Tv;
use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Medico;
use App\Models\Ticket;
use App\Models\Triaje;
use App\Models\Prestaciones;
use SoapClient;
use Session;
use DB;
use App;
use App\Models\Coredor;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use App\Models\SubConsultorio;
use Illuminate\Support\Facades\Log;
use Symfony\Component\VarDumper\Cloner\Data;

class TriajeController extends Controller
{
  function __construct()
    {
        $this->middleware('auth'); 
    } 
  
    public function obtener_triaje_por_prioridad($prioridad)
  {

    $TriajePrioridad = new Triaje;
    $Triaje = $TriajePrioridad->ShowTriaje($prioridad) ;
    return $Triaje;
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

    $model = new Triaje();

    $objTriaje = $model->ShowAllTriajes();
    $ruta = Route::getCurrentRoute()->getPath();
    $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
    $permiso = $menu[0];

    if ($permiso->lectura == "") return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

    return view('frontend.triajes.all', ['triajes' => $objTriaje, 'permiso' => $permiso]);
  }

  public function create()
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $model_prioridad = new \App\Models\Triaje;
    $prioridades = $model_prioridad->ShowPrioridad();
    $model = new \App\Models\Consultorio;
    //$servicios = $model->ShowConsultoriosAll();
    $servicios = $model->ShowConsultorioTipoAtencion(Auth::user()->id_establecimiento, 2);
    return view('frontend.triajes.create', compact('prioridades', 'servicios'));
  }

  public function store(Request $request)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $model_servicios = new \App\Models\Consultorio;
    $servicios = $model_servicios->GetNombreById($request->id_servicio);
    $model_sub_consultorio = new \App\Models\SubConsultorio;
    $sub_consultorio = SubConsultorio::where('id_consultorio', $request->id_servicio)->first();
    $ultimo = Triaje::latest('id')->first();
    $objTriaje = new Triaje();
    $objTriaje->codigo = $ultimo->id + 1;
    $objTriaje->descripcion = $servicios->nombre;
    $objTriaje->id_servicio = $request->id_servicio;
    $objTriaje->id_sub_consultorio = $sub_consultorio->id;
    $objTriaje->estado = 'N';
    $objTriaje->prioridad = $request->id_prioridad;
    $objTriaje->save();


    Session::flash('flash_message', 'Registro exitoso');
    return redirect('/all_triaje_topico');
  }

  public function deshabilitar($id)
  {
    $update = Triaje::where('id', '=', $id)->first();
    $update->estado = 'I';
    $update->save();
    Session::flash('flash_message', 'Se deshabilitó correctamente');
  }

  public function habilitar($id)
  {
    $update = Triaje::where('id', '=', $id)->first();
    $update->estado = 'N';
    $update->save();
    Session::flash('flash_message', 'Se deshabilitó correctamente');
  }

  public function edit($id)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $model_prioridad = new \App\Models\Triaje;
    $prioridades = $model_prioridad->ShowPrioridad();
    $objTriaje = Triaje::findOrFail($id);
    $model_servicios = new \App\Models\Consultorio;
    $servicios = $model_servicios->ShowConsultorioTipoAtencion(Auth::user()->id_establecimiento, 2);
    return view('frontend.triajes.edit', ['triaje' => $objTriaje, 'prioridades' => $prioridades, 'servicios' => $servicios]);
  }

  public function update(Request $request)
  {
    $model_servicios = new \App\Models\Consultorio;
    $servicios = $model_servicios->GetNombreById($request->id_servicio);
    $model_sub_consultorio = new \App\Models\SubConsultorio;
    $sub_consultorio = SubConsultorio::where('id_consultorio', $request->id_servicio)->first();
    $triaje = Triaje::find($request->id);
    $triaje->descripcion = trim($servicios->nombre);
    $triaje->id_servicio = $request->id_servicio;
    $triaje->id_sub_consultorio = $sub_consultorio->id;
    $triaje->prioridad = $request->id_prioridad;
    $triaje->save();

    return redirect('/all_triaje_topico')->withFlashSuccess("Se actualizó correctamente");
  }

  public function triaje_usuario(Request $request)
  {
    // dd($request->presion_arterial_y);
    $model_triaje = new \App\Models\Triaje;
    //$medico = Medico::where('dni', Auth::user()->dni)->where('id_establecimiento', Auth::user()->id_establecimiento)->first();

    if ($request->id_prestacion > 0) :
      $prestacione = Prestaciones::where('id', $request->id_prestacion)->first();
      $prestacione->temperatura = $request->temperatura != '' ? $request->temperatura : 0;
      $prestacione->talla_cm = $request->talla != '' ? $request->talla : 0;
      $prestacione->peso_kg = $request->peso != '' ? $request->peso : 0;
      $prestacione->sa02_porcentaje = $request->sa02 != '' ? $request->sa02 : 0;
      $prestacione->tamano_cintura_cm = $request->tamano_cintura != '' ? $request->tamano_cintura : 0;
      $prestacione->presion_arterial_x = $request->presion_arterial_x != '' ? $request->presion_arterial_x : 0;
      $prestacione->presion_arterial_y = $request->presion_arterial_y != '' ? $request->presion_arterial_y : 0;
      $prestacione->frecuencia_respiratoria = $request->frecuencia_respiratoria != '' ? $request->frecuencia_respiratoria : 0;
      $prestacione->frecuencia_cardiaca = $request->frecuencia_cardiaca != '' ? $request->frecuencia_cardiaca : 0;
      $prestacione->fi02 = $request->fi02 != '' ? $request->fi02 : 0;
      $prestacione->updated_at_triaje = Carbon::now();
      $prestacione->updated_us_triaje = Auth::user()->id;
    else :
      $cita = Cita::where('id', $request->id_cita)->first();
      $prestacione = new Prestaciones();
      $prestacione->id_cita = $request->id_cita;
      $prestacione->id_asegurado = $cita->id_asegurado;
      $prestacione->id_establecimiento = Auth::user()->id_establecimiento;
      $prestacione->temperatura = $request->temperatura != '' ? $request->temperatura : 0;
      $prestacione->talla_cm = $request->talla != '' ? $request->talla : 0;
      $prestacione->peso_kg = $request->peso != '' ? $request->peso : 0;

      $prestacione->sa02_porcentaje = $request->sa02 != '' ? $request->sa02 : 0;
      $prestacione->tamano_cintura_cm = $request->tamano_cintura != '' ? $request->tamano_cintura : 0;
      $prestacione->presion_arterial_x = $request->presion_arterial_x != '' ? $request->presion_arterial_x : 0;
      $prestacione->presion_arterial_y = $request->presion_arterial_y != '' ? $request->presion_arterial_y : 0;
      $prestacione->frecuencia_respiratoria = $request->frecuencia_respiratoria != '' ? $request->frecuencia_respiratoria : 0;
      $prestacione->frecuencia_cardiaca = $request->frecuencia_cardiaca != '' ? $request->frecuencia_cardiaca : 0;
      $prestacione->fi02 = $request->fi02 != '' ? $request->fi02 : 0;
      $prestacione->fecha_triaje = Carbon::now();
      $prestacione->id_profesional_triaje = Auth::user()->id; // Anteriormente guardaba id_medico ahora id_user
      $prestacione->id_estado_reg = 1;
      $prestacione->created_at = Carbon::now();
      $prestacione->created_us = Auth::user()->id;

      $cita->id_estado_cita = 9;
      //$cita->estado_presencia = '2';

      $cita->save();
      
      $cad = "UPDATE citas_llamados SET id_estado_cita = 9, estado_presencia = '2' WHERE id_cita = ? ;";
      $affected = DB::update($cad,array((int)$request->id_cita));
      Log::info('Actualización estado de cita a 9 atendido por triaje:', ['id_cita' => $request->id_cita, 'affected' => $affected]);
    endif;

    $prestacione->save();

    //Cambiamos estado del ticket
    $ticket = Ticket::where('id_cita', $request->id_cita)->where('estado', '1')->first();
		$ticket->id_estado_tv = 3;
    $ticket->atendido_at = Carbon::now();
    $ticket->atendido_us = Auth::user()->id;
    $ticket->llamado_at = null;
    $ticket->llamado_us = null;
    $ticket->anulado_at = null;
    $ticket->anulado_us = null;
    $ticket->seleccionado_at = null;
    $ticket->seleccionado_us = null;
    $ticket->estado_llamada = '1';
		$ticket->save();

    return json_encode(1);  

    //$id_registro_triaje = $model_triaje->registrartriaje($id_establecimiento, $request->id_user, $request->temperatura, $request->talla, $request->peso, $request->sa02, $request->tamano_cintura, $request->presion_arterial_x, $request->presion_arterial_y, $request->frecuencia_respiratoria, $request->frecuencia_cardiaca, $request->fi02);
//    return redirect('/modulo_triaje')->withFlashSuccess("Se registro correctamente");
    //      return redirect('/modulo_triaje?redirect=no')->withFlashSuccess("Se registro correctamente");

  }
  public function triaje_nuevo_totem($id_user = 0)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;
    $model_medico = new \App\Models\Medico();
    $model_usuario = new User;

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
    $id_totem_actual = $usuario_actual->id_totem;
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
      if (Auth::user()->tipo_registro == 0) {
        //	        if (Auth::user()->tipo_registro == 1) {
        $buscamedico = $model_medico->medico_yaregistrado($usuario_actual->dni, Auth::user()->id_establecimiento);
        if (is_object($buscamedico)) :
          $medico_actual = $model_medico->GetMedicoByDniAndEstablecimiento($buscamedico->dni, $farmacia->id_establecimiento);
          $medico = $medico_actual->id;
        else :
          return redirect('/dashboard')->withFlashDanger("Tiene que ser médico de este establecimiento; para entrar a esta opción: coordinar con el administrador del Sistema");
        endif;
      }
    }

    $model_consultorio = new Consultorio();
    //$consultorio = $model_consultorio->ShowAllConsultorios($farmacia->id_establecimiento);
    $consultorio = $model_consultorio->ShowAllConsultoriosSubConsultorio($farmacia->id_establecimiento);

    $model_atencione = new TipoAtencione();
    $atencione = $model_atencione->ShowTipoAtencionAll();
    $corredore = auth()->user()->id_corredor_triaje;

    $totems = Totem::join('corredores', 'corredores.id', '=', 'totems.id_corredor')
      ->select('totems.id', 'totems.descripcion')
      ->where('corredores.id', $corredore)
      ->get();
    //$id = $totems[0]->id;//SE COMENTO ESTA LINEA POR AL NO EXISTIR VALOR SALIA ERROR Y NO SE USA ESA VARIABLE
    $corredor = DB::table('corredores')->where('id', $corredore)->first();


    //$medico_encontrado =   
    //$medico_encontrado = $model_medico->GetDatosMedicoAndEstablecimiento($usuario_actual->dni, $farmacia->id_establecimiento);  

    //if (count($medico_encontrado)>0) {
//      return view('frontend.triajes.triaje', compact('corredor', 'totems', 'tipo_registro', 'farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nombre_beneficiario', 'estado', 'flagemergenciareferencia', 'consultorio', 'medico', 'id_consultorio_actual', 'atencione', 'id_atencione', 'id_totem_actual'));
    if ($corredor == null) {
      //dd('No selecciono totem');
      return redirect('/dashboard')->withFlashDanger("Debe seleccionar el corredor para acceder a triaje");
    } else {
      return view('frontend.triajes.triaje', compact('corredor', 'totems', 'tipo_registro', 'farmacia', 'fechaDesde', 'fechaHasta', 'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nombre_beneficiario',  'flagemergenciareferencia', 'consultorio', 'estadosTriajes','estado','medico', 'id_consultorio_actual', 'atencione', 'id_atencione', 'id_totem_actual'));
    }

    //} else {
    //    return redirect('/dashboard')->withFlashDanger("Tiene que ser médico de este establecimiento; o estar activo: coordinar con el administrador del Sistema");
    //}
  }

  function insertar($array, $elemento, $pos)
  {

    if ($pos < 0) {
      return;
    }

    ## si la posicion es mayor que el tamaño de la lista
    ## el nodo se inserta al final
    if ($pos >= count($array)) {
      array_push($array, $elemento);
      return;
    }

    $listaaux = array(); # array auxiliar
    ## buscamos la posicion en el array, para ello las posiciones anteriores
    ## las guardamos en el array auxiliar
    for ($cont = 0; $cont < $pos; $cont++) {
      $listaaux[] = array_shift($array);
    }

    ## ahora se inserta el elemento al principio del array original
    array_unshift($array, $elemento);

    ## ahora recorremos el array auxiliar desde el final y vamos insertando
    ## sus elementos al principio del array original
    if (count($listaaux) > 0) {
      for ($i = count($listaaux) - 1; $i >= 0; $i--) {
        array_unshift($array, $listaaux[$i]);
      }
    }

    return $array;
  }

  public function moverArrayAdelante($arr, $key, $before)
  {
    $keys = array_keys($arr);
    if (in_array($key, $keys) && in_array($before, $keys)) {
      $newArr = [];
      foreach ($arr as $idx => $value) {
        switch ($idx) {
          case $key:
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

  public function obtenerIds($array)
  {
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

 public function triajeActualizacion(Request $request)
{
    if (!Auth::check()) {
        return redirect('/auth/login');
    }
  
    $nombreAsegurado = "";
    $ids = json_decode((json_encode($request->ids)), true);
    $ids_Integer = [];
    $array = explode(",", $ids);
  
    if (isset($array)) {
        $p_ids = $this->obtenerIds($array);
  
        if ($p_ids != "") {
            $data = 0;
            $cad = "Select sp_numeroTickets(?)";
            $data = DB::select($cad, array($p_ids));
        }
    }
  
    $model_tv = new Tv();
    $data = array();
  
    foreach ($array as $index) {
        $id_cita = $model_tv->llamar_citado_tv_tickets($index, auth()->user()->id);
  
        if ($id_cita > 0) {
            $cita = Ticket::where('id_cita', '=', $id_cita)->first();
  
            $cad = "select CONCAT(' ', c2.nombre, ' ', c2.paterno, ' ', c2.materno) as concat
                    from citas_llamados c inner join asegurados c2  
                    on c.id_asegurado = c2.id where c.id_cita = " . $id_cita;
  
            $nombreAsegurado = DB::select($cad);
            $nombreAsegurado2 = $nombreAsegurado[0]->concat;
  
            $cantidad_llamada = $cita->cantidad_llamada;
            $data['cantidadLlamadas'] = $cantidad_llamada;
            $data['ticket'] = $cita->id_cita;
            $data['posicionfinal'] = $cita->indicadorfinal;
            $data['nombreAsegurado'] = $nombreAsegurado2;
  
            $citaApoyo = Cita::where('id', '=', $id_cita)->first();
            $data['id_estado_cita'] = $citaApoyo->id_estado_cita;
            $data['estado_triaje'] = $citaApoyo->estado_triaje;
        }
    }
  
    $data['ids'] = $array;
  
    return $data;
}
  public function listar_cita_ajaxTriaje(Request $request)
  {
    $validadorIpress = Auth::user()->id_establecimiento;
    $tipo_registro = Auth::user()->tipo_registro;
    $idNestor = Auth::user()->id;
    $disabledButton = true;
    $cantidadllamadas = 0;

    $model_citas = new Cita();
    $p[] = $request->fecha_ini;
    $p[] = $request->fecha_fin;
    $p[] = $request->fecha_reg_ini;
    $p[] = $request->fecha_reg_fin;
    $p[] = $request->dni_beneficiario;
    $p[] = $request->nombre_beneficiario;
    $p[] = (isset($request->id_servicio) && $request->id_servicio != "") ? $request->id_servicio : 0;
    //$p[]=$request->id_servicio;
    $p[] = (isset($request->id_estado_cita) && $request->id_estado_cita != "") ? $request->id_estado_cita : 0;
    //$p[]=$request->id_consultorio;
    $p[] = (isset($request->id_consultorio) && $request->id_consultorio != "") ? $request->id_consultorio : 0;
    
    $p[] = $request->id_medico;

    $p[] = Auth::user()->id_establecimiento;
    $p[] = $request->id_condicion_establecimiento;
    $p[] = $request->id_condicion_servicio;
    //$p[]=0;
    //$p[]=0;
    //$p[]=0;
    $p[] = $request->estado;
    

    $p[] = $request->NumeroPagina;
    $p[] = $request->NumeroRegistros;
    $p[] = $request->id_totem;
    //lista original
    $data = $model_citas->listar_cita_ajaxTriaje($p);

    $dataodenada = [];
    //lista nuevo paciente
    $data3 = [];
    $data2 = [];
    $data4 = [];
    $listaYaIniciada = false;
    $ingresoNuevo = false;
    $autollamada = false;
    $retorno = 0;
    $dataFilter = [];
    $idCitas = [];
    $soloIpressLuisNSaes = false;

    if ($validadorIpress == 76 && $tipo_registro == 9) {
      $soloIpressLuisNSaes = true;
      $disabledButton = false;
      foreach ($data as $objr) {

        if ($objr->estado_triaje == "1" && $objr->estado_presencia != null) {

          if ($objr->ticketiniciado == 1) {
            $listaYaIniciada = true;
            break;
          }
        }
      }
    }





    if ($validadorIpress == 76 && $tipo_registro == 9  && $listaYaIniciada == true) {
      $autollamada = true;
      $disabledButton = false;

      foreach ($data as $obj) {

        if ($obj->estado_presencia = "1" && $obj->estado_presencia != null) {
          $ticket = Ticket::where('id_cita', '=', $obj->id)->first();
          $cantidadllamadas = $ticket->cantidad_llamada;

          break;
        }
      }
      foreach ($data as $obj) {

        if ($obj->estado_triaje = "1" && $obj->id_estado_cita != 9) {

          array_push($idCitas, $obj->id);
        }
      }

      foreach ($data as $objrgeneral) {
        array_push($dataFilter, $objrgeneral);
      }


      //  }else if($validadorIpress == 76 && $tipo_registro ==9 && $listaYaIniciada ==false ){
    } else if ($validadorIpress == 76 && $tipo_registro == 9 or $tipo_registro == 3 or $tipo_registro == 1 or $tipo_registro == 2 or $tipo_registro == 4 or $tipo_registro == 8 && $listaYaIniciada == false) {
      $disabledButton = false;
      foreach ($data as $obj) {

        if ($obj->estado_presencia = "1" && $obj->estado_presencia != null) {
          $ticket = Ticket::where('id_cita', '=', $obj->id)->first();
          $cantidadllamadas = $ticket->cantidad_llamada;

          break;
        }
      }
      foreach ($data as $obj) {

        if ($obj->estado_triaje = "1" && $obj->id_estado_cita != 9) {

          array_push($idCitas, $obj->id);
        }
      }
      foreach ($data as $objrgeneral) {
        array_push($dataFilter, $objrgeneral);
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
    if ($validadorIpress == 76 && $tipo_registro == 9 && $listaYaIniciada == true) {
      $result["aaData"] = $dataFilter;
    } else {
      $result["aaData"] = $data;
    }

    //id preparados
    $result["ids"] = $idCitas;
    $result["autollamada"] = $autollamada;

    $result["disabledButton"] = $disabledButton;
    $result["soloIpressLuisNSaes"] = $soloIpressLuisNSaes;
    $result["cantidadllamadas"] = $cantidadllamadas;
    // $result["idNestor"] = $idNestor;
    //lui


    echo json_encode($result);
  }

  public function finalColaTriaje(Request $request)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $listadecodificada = json_decode((json_encode($request->listaPresencial)), true);

    $contador_finalcola = 0;
    // $listarevert = $reques
    $respuesta = [];
    $data = [];
    $cantidad_llamada = 0;
    $indiceFinalCola = (isset($request->indicePosponer) && $request->indicePosponer != "") ? $request->indicePosponer : 0;
    $model_tv = new Tv();
    $ticketpueba = 0;
    $idticket = "";
    $llamadas = "";
    $boleanoLastAlert = false;

    foreach ($listadecodificada as $index) {
      $id_cita = $index;
      if ($id_cita > 0) {
        $ticket = Ticket::where('id_cita', '=', $id_cita)->first();


        $cantidad_llamada = $ticket->cantidad_llamada;
        $data['cantidadLlamadas'] = $cantidad_llamada;
        $data['ticket'] = $ticket->id_cita;
        $idticket = $ticket->id_cita;
        $llamadas = $cantidad_llamada;
      }

      break;
    }


    foreach ($listadecodificada as $key => $value) {
      $posicion = array_search($value, $listadecodificada);
      break;
    }
    $data['posicion'] = $posicion;
    $data['nuevovalorarray'] = $posicion + 1;

    $funcionApoyo = new CitaController;
    $arrayManipulado = [];
    $retorno = 0;

    if ($cantidad_llamada > 1) {
      if ($indiceFinalCola < 2) {

        $arrayManipulado = $funcionApoyo->moverArrayAdelante($listadecodificada, $data['nuevovalorarray'], $posicion);
      } else {
        $boleanoLastAlert = true;
        $array_eliminado = array_shift($listadecodificada);

        //se envia al final  
        array_push($listadecodificada, $array_eliminado);

        $arrayManipulado = $listadecodificada;
        $cita = Ticket::where('id_cita', '=', $array_eliminado)->first();
        $cita->id_estado_tv = 0; // 2
        $cita->indicadorfinal = 0;
        $cita->save();
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
      if ($p_ids != "") :
        $data = 0;
        $cad = "Select sp_numeroTickets(?)";
        $data = DB::select($cad, array($p_ids));

        foreach ($data as $d) {
          $retorno = $data;
        }


      endif;
    endif;





    $CitaAct = $idticket;

    $cita = Ticket::where('id_cita', '=', $CitaAct)->first();
    $cita->id_estado_tv = 0; // 2
    $cita->cantidad_llamada = $llamadas;
    $cita->llamado_at = null;
    //$cita->orden_llamada = null;
    $cita->estado_llamada = "0";
    $cita->indicadorfinal =  $indiceFinalCola + 1;

    $cita->save();
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



   /* public function CancelarTriaje1(Request $request)
{
  if (!Auth::check()) {
    return redirect('/auth/login');
  }

  $cita = Ticket::where('id_cita', '=', $request->ids)->first();
      $cita->id_estado_tv = 0;
      $cita->save();
      $CancelarString = "se cancelo";
      $data["CancelarString"] = $CancelarString;

  return response()->json($data);
}
*/
public function CancelarTriaje(Request $request)
{
  if (!Auth::check()) {
    return redirect('/auth/login');
  }

  $cita = Ticket::where('id_cita', '=', $request->ids)->first();
  $cita->id_estado_tv = 2; // 2
  $cita->anulado_at = Carbon::now();
  $cita->anulado_us = Auth::user()->id;
  $cita->llamado_at = null;
  $cita->llamado_us = null;
  $cita->seleccionado_at = null;
  $cita->seleccionado_us = null;
  $cita->atendido_at = null;
  $cita->atendido_us = null;
  $cita->ventanilla = null;
  $cita->estado_llamada = "0";
  $cita->save();
  $CancelarString = "se cancelo";
  $data["CancelarString"] = $CancelarString;

  return response()->json($data);
}



  public function modal_ficha_triajecontroller($id_cita)
  {
    if (!Auth::check()) :
      return redirect('/auth/login');
    endif;

    $cita = Prestaciones::where('id_cita', $id_cita)->first();
    return view('frontend.atenciones.modal_triaje', compact('id_cita', 'cita'));
  }
}