<?php

namespace App\Http\Controllers;

use DB;
use App;
use App\Models\CorredoresDetalle;
use Auth;
use Session;
use App\Models\Tv;
use Carbon\Carbon;
use App\Models\Cita;
use App\Models\Menu;
use App\Models\Tv_ip;
use App\Http\Requests;
use App\Models\Ticket;
use App\Models\Permiso;
use App\Models\Tv_area;
use App\Models\Farmacia;
use App\Models\Asegurado;
use App\Models\Consultorio;
use App\Models\Tv_servicio;
use Illuminate\Http\Request;
use App\Models\Establecimiento;
use App\Models\Tv_area_detalle;
use App\Http\Controllers\Controller;
use App\Models\Corredore;
use App\Models\Totem;
use App\Models\User;
use Illuminate\Support\Facades\Route;

class TvController extends Controller
{
    function __construct()
    {
        $this->middleware('auth'); 
    } 
    
    public function index() {
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

        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        // dd($permiso);
        if ($permiso->lectura == "" || $permiso->lectura == "0")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

        return view('frontend.tv.all');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $model_establecimiento = new Establecimiento();
        $model_corredore = new Corredore();
        $establecimientos = $model_establecimiento->ShowAll();
        $corredores = $model_corredore->ShowAll();

        return view('frontend.tv.create', compact("establecimientos","corredores"));
    }
	
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        if ($request->id_tv == "") {
            $tv = new Tv;
            $tv->id_establecimiento = $request->cbo_establecimiento;
            $tv->id_corredor = $request->id_corredor;
            $tv->descripcion = strtoupper($request->descripcion);
            $tv->estado = 1;
            $tv->created_us = Auth::user()->id;
            $tv->save();

            $id_tv = $tv->id;
            if ($id_tv > 0) {
                if (!is_null($request->id_consultorio)) {
                    foreach ($request->id_consultorio as $key => $value) {
                        $tv_servicio = new Tv_servicio;
                        $tv_servicio->id_tv = $id_tv;
                        $tv_servicio->id_consultorio = $request->id_consultorio[$key];
                        $tv_servicio->id_estado = 1;
                        $tv_servicio->created_us = Auth::user()->id;
                        $tv_servicio->save();
                    }
                }

                if (!is_null($request->input_ip)) {
                    foreach ($request->input_ip as $key => $value) {
                        $tv_ip = new Tv_ip;
                        $tv_ip->id_tv = $id_tv;
                        $tv_ip->ip = $request->input_ip[$key];
                        $tv_ip->id_estado = 1;
                        $tv_ip->created_us = Auth::user()->id;
                        $tv_ip->save();
                    }
                }

                Session::flash('flash_message', 'Registro exitoso');
            } else {
                Session::flash('flash_message', 'Ha ocurrido un error al registrar el Televisor.');
            }

        } else {
            $id_tv = $request->id_tv;
            $tv = Tv::where('id','=',$id_tv)->first();
            $tv->id_establecimiento = $request->cbo_establecimiento;
            $tv->id_corredor = $request->id_corredor;
            $tv->descripcion = strtoupper($request->descripcion);
            $tv->estado = 1;
            $tv->updated_us = Auth::user()->id;
            $tv->id_totem = $request->id_totem == '' ? NULL : $request->id_totem;
            $tv->save();

            $model_tv = new Tv();
            $servicios_elegidos = $model_tv->getConsultoriosPorTv($id_tv);
            $existe = "no";

            if (!is_null($request->id_consultorio)) {
                // Registrar nuevos subconsultorios
                foreach ($request->id_consultorio as $key => $value) {
                    $existe = "no";
                    foreach ($servicios_elegidos as $rowConsul) :
                        if ($request->id_consultorio[$key] == $rowConsul->id) {
                            $existe = "si";
                        }
                    endforeach;
                    if ($existe == "no") {
                        $tv_servicio = new Tv_servicio;
                        $tv_servicio->id_tv = $id_tv;
                        $tv_servicio->id_consultorio = $request->id_consultorio[$key];
                        $tv_servicio->id_estado = 1;
                        $tv_servicio->created_us = Auth::user()->id;
                        $tv_servicio->save();
                    }
                }

                // Deshabilitar subconsultorios
                foreach ($servicios_elegidos as $rowConsul) :
                    $existe = "no";
                    foreach ($request->id_consultorio as $key => $value) {
                        if ($rowConsul->id == $request->id_consultorio[$key]) {
                            $existe = "si";
                        }
                    }
                    if ($existe == "no") {
                        $tv_servicio = Tv_servicio::where('id_tv', '=', $id_tv)->where('id_consultorio','=',$rowConsul->id)->where('id_estado', '=', 1)->first();
                        $tv_servicio->id_estado = 0;
                        $tv_servicio->updated_us = Auth::user()->id;
                        $tv_servicio->save();
                    }
                endforeach;
            }

            if (!is_null($request->input_ip)) {
                
                $affects = Tv_ip::where('id_tv', '=', $id_tv)->update(['id_estado' => 0]);
                $ip_registrados = Tv_ip::where('id_tv', '=', $id_tv)->get();
                
                foreach ($request->input_ip as $key => $value) {
                    $existe = "no";
                    foreach ($ip_registrados as $rowConsul) :
                        if ($request->input_ip[$key] == $rowConsul->ip) {
                            $activar = Tv_ip::where('id', $rowConsul->id)->first();
                            $activar->id_estado = 1;
                            $activar->save();
                            $existe = "si";
                            break;
                        }
                    endforeach;

                    if ($existe == "no") {
                        $tv_ip = new Tv_ip;
                        $tv_ip->id_tv = $id_tv;
                        $tv_ip->ip = $request->input_ip[$key];
                        $tv_ip->id_estado = 1;
                        $tv_ip->created_us = Auth::user()->id;
                        $tv_ip->save();
                    }
                }
            }

            Session::flash('flash_message', 'Modificación exitosa');
        }

        return redirect('/tv_all');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		if (!Auth::check()):
			return redirect('/auth/login');
		endif;

        $tv = Tv::where('id','=',$id)->first();

        $totems = Totem::where('id_establecimiento','=',$tv->id_establecimiento)
                                    ->where('id_estado','=',1)->get();
        
        $model_establecimiento = new Establecimiento();
        $establecimientos = $model_establecimiento->ShowAll();

        $model_consultorio = new Consultorio();
        //dd($tv->id_establecimiento);
        $servicios = $model_consultorio->GetAllPorIpress($tv->id_establecimiento);

        $model_tv = new Tv();
        $servicios_elegidos = $model_tv->getConsultoriosPorTv($id);
        $tv_sub_consultorios = $model_tv->obtenerSubConsultorioDeTvPorIdTv($id);
        $lista_ips = Tv_ip::where('id_tv','=',$id)->get();
        // dd($servicios_elegidos);
        $corredore = new Corredore();
        $corredores = $corredore->ShowAllByIdEstablecimiento($tv->id_establecimiento);
        return view('frontend.tv.edit', compact("id", "tv", "establecimientos", "servicios", "servicios_elegidos", "lista_ips", "corredores","tv_sub_consultorios", "totems"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
		
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function tv_listar(Request $request) {
        $model_tv = new Tv();
        $draw = $request->draw;
        $start = ($request->start == 0) ? 1 : (int)$request->start / 10 + 1;
        $length = 10;
        $search_value = $request->search["value"];

        $p[] = (string)$start;
        $p[] = (string)$length;
        $p[] = $search_value;
        
        $data = $model_tv->listar($p);

        $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;
        $result["draw"] = $draw;
        $result["recordsTotal"] = $iTotalDisplayRecords;
        $result["recordsFiltered"] = $iTotalDisplayRecords;
        $result["data"] = $data;

        echo json_encode($result);
    }
	
	public function llamar($id_cita) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $cantidad_llamada = 0;
        $model_tv = new Tv();
        $id_cita = $model_tv->llamar_citado_tv($id_cita);
        
        if ($id_cita > 0) {
            $cita = Cita::where('id','=',$id_cita)->first();
            $cantidad_llamada = $cita->cantidad_llamada;
        }
		
        // json_encode
        return $cantidad_llamada;
		// return json_encode($cantidad_llamada);
	}

    public function posponer_llamado($id_cita) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $cita=Cita::where('id','=',$id_cita)->first();
		$cita->id_estado_tv = 0; // 2
        $cita->cantidad_llamada = 0;
        $cita->fecha_llamada = null;
        $cita->orden_llamada = null;
        $cita->estado_llamada = "0";
		$cita->save();
        return "2";
    }

    public function tv_llamado_iterativo($id_tv, $id_area) {
        // if (!Auth::check()):
        //     return redirect('/auth/login');
        // endif;
        $p[] = $id_tv;
        $p[] = $id_area;
        $cita = new Cita();
        $data = $cita->tv_llamados($p);
        return $data;
    }

    public function tv($id_tv = 0, $id_area = 0) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1);
        $ruta = $ruta[0];

        $menu = Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];
        
        if ($permiso->lectura == "" || $permiso->lectura == "0")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

        $id_user = Auth::user()->id;
        $model_farma = new Farmacia();
        $tv = Tv::where('id',$id_tv)->first();
        $model_establecimiento = new Establecimiento();
        $establecimiento = $model_establecimiento->Get($tv->id_establecimiento);

        return view('frontend.tv.colas', compact("id_tv", "id_area", "establecimiento"));
    }

    public function tv_sin_auth($ip = '') {
        // $clientIP = \Request::getClientIp(true);
        $ip = ($ip == '') ? \Request::ip() : $ip;
        $tv_ip = Tv_ip::where('ip',$ip)->where('id_estado',1)->first();
        
        //Voz Default ResponsiveVoice
        $tv_voz = "Spanish Latin American Male";

        if($tv_ip != null) {
            $tv = Tv::where('id',$tv_ip->id_tv)->first();
            $tv_voz = $tv->voz;
            $id_tv = $tv_ip->id_tv;
            $model_establecimiento = new Establecimiento();
            $establecimiento = $model_establecimiento->Get($tv->id_establecimiento);
        } else {
            return view('errors.404', compact('ip'));
        }

        return view('frontend.tv.colas_sin_auth', compact("id_tv", "establecimiento", "tv_voz"));
    }

    public function area_all() {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        return view('frontend.tv.all_areas');
    }

    
    public function tv_listar_area(Request $request) {
        $model_tv = new Tv();
        $draw = $request->draw;
        $start = ($request->start == 0) ? 1 : (int)$request->start / 10 + 1;
        $length = 10;
        $search_value = $request->search["value"];

        $p[] = (string)$start;
        $p[] = (string)$length;
        $p[] = $search_value;
        
        $data = $model_tv->listar_area($p);

        $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;
        $result["draw"] = $draw;
        $result["recordsTotal"] = $iTotalDisplayRecords;
        $result["recordsFiltered"] = $iTotalDisplayRecords;
        $result["data"] = $data;

        echo json_encode($result);
    }

    public function cambiar_estado_llamada($id_cita, $estado, $tipo) {
        if ($tipo == 'citas'):
            $cita=Cita::where('id','=',$id_cita)->first();
            $cita->estado_llamada = $estado;
            $cita->save();
        elseif ($tipo == 'tickets'):
            $ticket=Ticket::where('id','=',$id_cita)->first();
            $ticket->estado_llamada = $estado;
            $ticket->save();
        endif;

        // return true;
        return "Y";
    }

    public function actualizar_estado($id_tv, $estado) {
        $tv = Tv::where('id','=',$id_tv)->first();
        $tv->estado = $estado;
        $tv->save();
        return "Y";
    }

    public function create_area() {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $model_establecimiento = new Establecimiento();
        $establecimientos = $model_establecimiento->ShowAll();

        return view('frontend.tv.create_area', compact("establecimientos"));
    }

    public function get_all() {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $model_establecimiento = new Establecimiento();
        $establecimientos = $model_establecimiento->ShowAll();

        // $model_tv = new Tv();
        // $tvs = $model_tv->showAllPorEstablecimiento($id_ipress);

        return view('frontend.tv.create_area', compact("establecimientos"));
    }

    public function get_tv_por_establecimiento($id_ipress) {
        $model_tv = new Tv();
        $tvs = $model_tv->showAllPorEstablecimiento($id_ipress);
        return $tvs;
    }

    public function get_subconsultorio_por_televisor($id_tv) {
        $tv = Tv::where("id","=",$id_tv)->first();
        $model_tv = new Tv();
        $subconsultorios = $model_tv->showSubConsultoriosPorIpressAndConsultorio($tv->id_establecimiento, $tv->id_consultorio);
        return $subconsultorios;
    }

    public function store_area(Request $request) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        if ($request->id_tv_area == "") {
            $tv_area = new Tv_area;
            $tv_area->descripcion = $request->text_nombre_area;
            $tv_area->id_tv = $request->cbo_televisor;
            $tv_area->id_estado = 1;
            $tv_area->created_us = Auth::user()->id;
            $tv_area->save();
            $id = $tv_area->id;

            if ($id > 0) {
                foreach ($request->id_consultorio as $key => $value) {
                    $tv_area_detalle = new Tv_area_detalle;
                    $tv_area_detalle->id_tv_area = $id;
                    $tv_area_detalle->id_sub_consultorio = $request->id_consultorio[$key];
                    $tv_area_detalle->id_estado = 1;
                    $tv_area_detalle->created_us = Auth::user()->id;
                    $tv_area_detalle->save();
                }

                Session::flash('flash_message', 'Registro exitoso');
            } else {
                Session::flash('flash_message', 'Ha ocurrido un error al registrar el Área.');
            }
            
        } else {
            $id_tv_area = $request->id_tv_area;
            $tv_area = Tv_area::where("id","=",$id_tv_area)->first();
            $tv_area->descripcion = $request->text_nombre_area;
            $tv_area->id_tv = $request->cbo_televisor;
            $tv_area->updated_us = Auth::user()->id;
            $tv_area->save();

            $model_tv_area = new Tv_area();
            $subconsultorios_elegidos = $model_tv_area->getSubConsultoriosPorTvArea($id_tv_area);
            $existe = "no";


            if (!is_null($request->id_consultorio)) {
                // Registrar nuevos subconsultorios
                foreach ($request->id_consultorio as $key => $value) {
                    $existe = "no";
                    foreach ($subconsultorios_elegidos as $rowConsul) :
                        if ($request->id_consultorio[$key] == $rowConsul->id_sub_consultorio) {
                            $existe = "si";
                        }
                    endforeach;
                    if ($existe == "no") {
                        $tv_area_detalle = new Tv_area_detalle;
                        $tv_area_detalle->id_tv_area = $id_tv_area;
                        $tv_area_detalle->id_sub_consultorio = $request->id_consultorio[$key];
                        $tv_area_detalle->id_estado = 1;
                        $tv_area_detalle->created_us = Auth::user()->id;
                        $tv_area_detalle->save();
                    }
                }

                // Deshabilitar subconsultorios
                foreach ($subconsultorios_elegidos as $rowConsul) :
                    $existe = "no";
                    foreach ($request->id_consultorio as $key => $value) {
                        if ($rowConsul->id_sub_consultorio == $request->id_consultorio[$key]) {
                            $existe = "si";
                        }
                    }
                    if ($existe == "no") {
                        $tv_area_detalle = Tv_area_detalle::where('id_tv_area', '=', $id_tv_area)->where('id_sub_consultorio','=',$rowConsul->id_sub_consultorio)->where('id_estado', '=', 1)->first();
                        $tv_area_detalle->id_estado = 2;
                        $tv_area_detalle->updated_us = Auth::user()->id;
                        $tv_area_detalle->save();
                    }
                endforeach;
            }

            Session::flash('flash_message', 'Modificación exitosa');
        }

        
        return redirect('/tv_area_all');
    }

    public function edit_area($id_tv_area)
    {
		if (!Auth::check()):
			return redirect('/auth/login');
		endif;

        $tv_area = Tv_area::where('id','=',$id_tv_area)->first();
        $tv = Tv::where('id','=',$tv_area->id_tv)->first();
        $id_establecimiento = $tv->id_establecimiento;

        $model_establecimiento = new Establecimiento();
        $establecimientos = $model_establecimiento->ShowAll();

        $model_tv = new Tv();
        $tvs = $model_tv->showAllPorEstablecimiento($id_establecimiento);

        $model_tv_area = new Tv_area();
        $subconsultorios = $model_tv->showSubConsultoriosPorIpressAndConsultorio($tv->id_establecimiento, $tv->id_consultorio);

        $subconsultorios_elegidos = $model_tv_area->getSubConsultoriosPorTvArea($id_tv_area);
        // $establecimiento = $model_tv_area->getEstablecimientoPorTvArea($id_tv_area);

        return view('frontend.tv.edit_area', compact("tv_area","id_establecimiento","establecimientos","tvs","subconsultorios","subconsultorios_elegidos"));
    }

    public function actualizar_estado_tv_area($id_tv_area, $estado) {
        $tv = Tv_area::where('id','=',$id_tv_area)->first();
        $tv->id_estado = $estado;
        $tv->save();
        return "Y";
    }

    public function modulo_llamada_tv() {
        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;
        $moduLlamaPerm = '';
        $moduloLlamadoPermiso = '';
        $moduLlamaPerm = Permiso::where('idusuario', auth()->user()->id)
        ->where('idmenu', 113)
        ->where('crear', "1")
        ->where('modificar', "1")
        ->where('eliminar', "1")
        ->where('lectura', "1")->first();
        $moduloLlamadoPermiso = !empty($moduLlamaPerm) ? "true" : "false"; 
        //$id_establecimiento = Auth::user()->id_establecimiento;
        if ($moduloLlamadoPermiso == "true" && auth()->user()->flagmodulollamado == 1) {
            $ventServ = array();
            $fechaActual = date('Y-m-d');
            $pData = array();
            //===============Llamado_us=======================//
            $dato = array();
            $id_user = auth()->user()->id;
            $model_llamadoUs = Ticket::where('llamado_at','=',$fechaActual)->where('llamado_us','=',$id_user)->first();
            if (isset($model_llamadoUs)) {
                $dato['id_ticket'] = $model_llamadoUs->id;
                $dato['turno'] = $model_llamadoUs->letra . '-' . $model_llamadoUs->numero;
                $dato['estado_llamado'] = 'false';
                $dato['opcion'] = '1';
                if ($model_llamadoUs->id_asegurado == 0) {
                    $dato['paciente'] = '';
	    $dato['llamado_seleccionado_us'] = 'true';
                }else {
                    $aModelAsegurado = Asegurado::where('id','=',$model_llamadoUs->id_asegurado)->first();
                    $dato['paciente'] = $aModelAsegurado->paterno . ' ' . $aModelAsegurado->materno . ', ' . $aModelAsegurado->nombre;
                    $dato['llamado_seleccionado_us'] = 'true';
                } 
            } else{
                $dato['id_ticket'] = '';
                $dato['turno'] = '';
                $dato['paciente'] = '';
                $dato['estado_llamado'] = 'true';
                $dato['llamado_seleccionado_us'] = 'false';
                $dato['opcion'] = '0';
            }
            $pData['0'] = $dato;
            //=================================================
            //================Seleccionado_us==================//
            $sdato = array();
            //$sfechaActual = date('Y-m-d');
            $model_seleccionadoUs = Ticket::where('seleccionado_at','=',$fechaActual)->where('seleccionado_us','=',$id_user)->first();
            if (isset($model_seleccionadoUs)) {
                $sdato['id_ticket'] = $model_seleccionadoUs->id;
                $sdato['turno'] = $model_seleccionadoUs->letra . '-' . $model_seleccionadoUs->numero;
                $sdato['estado_llamado'] = 'false';
                $sdato['opcion'] = '2';
                if ($model_seleccionadoUs->id_asegurado == 0) {
                    $sdato['paciente'] = '';
                    $sdato['llamado_seleccionado_us'] = 'true';
                }else {
                    $aModelAsegurado = Asegurado::where('id','=',$model_seleccionadoUs->id_asegurado)->first();
                    $sdato['paciente'] = $aModelAsegurado->paterno . ' ' . $aModelAsegurado->materno . ', ' . $aModelAsegurado->nombre;
                    $sdato['llamado_seleccionado_us'] = 'true';
                } 
            } else{
                $sdato['id_ticket'] = '';
                $sdato['turno'] = '';
                $sdato['paciente'] = '';
                $sdato['estado_llamado'] = 'true';
                $sdato['llamado_seleccionado_us'] = 'false';
                $sdato['opcion'] = '0';
            }
            $pData['1'] = $sdato;
            //=================================================
            if (Auth::user()->flagventanilla == 0 && Auth::user()->id_ventanilla == null) {
                $vent = 0;
                $ventServ['servicio']['flagventanilla'] = Auth::user()->flagventanilla;
                $ventServ['servicio']['id_ventanilla'] = Auth::user()->id_ventanilla;
                $date = Carbon::now();
                $fechaDesde = $date->format('d-m-Y');
                $fechaHasta = $date->format('d-m-Y');
                $estado = "0,1,2";
                $ventanillas['value'] = '0';
                $ventanillas['nombre'] = '-Seleccionar-';
                return view('frontend.tv.modulo_llamado_tv', compact('fechaDesde','fechaHasta','estado','ventServ','ventanillas','vent','pData'));
            }
            if (Auth::user()->flagventanilla == 1 && Auth::user()->id_ventanilla == null) {
                $vent = 0;
                $ventServ['servicio']['flagventanilla'] = Auth::user()->flagventanilla;
                $ventServ['servicio']['id_ventanilla'] = Auth::user()->id_ventanilla;
                $date = Carbon::now();
                $fechaDesde = $date->format('d-m-Y');
                $fechaHasta = $date->format('d-m-Y');
                $estado = "0,1,2"; 
                return view('frontend.tv.modulo_llamado_tv', compact('fechaDesde','fechaHasta','estado','ventServ','vent','pData'));
            }
            $vent = $this->obtenerNombreVentanillaPorServicio(Auth::user()->id_ventanilla);
            $ventServ['servicio']['flagventanilla'] = Auth::user()->flagventanilla;
            $ventServ['servicio']['id_ventanilla'] = Auth::user()->id_ventanilla;
            $date = Carbon::now();
            $fechaDesde = $date->format('d-m-Y');
            $fechaHasta = $date->format('d-m-Y');
            $estado = "0,1,2";

            return view('frontend.tv.modulo_llamado_tv', compact('fechaDesde','fechaHasta','estado','ventServ','vent','pData'));
        } else {
            return redirect('/auth/login');
        }
        
    }

    public function llamar_ticket($id_ticket) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $cantidad_llamada = 0;
        $model_tv = new Tv();
        $model_ticket = Ticket::where('id','=',$id_ticket)->first();
        $model_ticket->seleccionado_at = null;
        $model_ticket->seleccionado_us = null;
        $model_ticket->anulado_at = null;
        $model_ticket->anulado_us = null;
        $model_ticket->atendido_at = null;
        $model_ticket->atendido_us = null;
        $model_ticket->save();
        $ventanilla = $model_ticket->ventanilla;
        $id_ticket = $model_tv->llamar_citado_ticket2($id_ticket, Auth::user()->id, $ventanilla);
        
        if ($id_ticket > 0) {
            $ticket = Ticket::where('id','=',$id_ticket)->first();
            $cantidad_llamada = $ticket->cantidad_llamada;
        }
		
        return $cantidad_llamada;
	}

    public function posponer_llamado_ticket($id_ticket) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $ticket = Ticket::where('id','=',$id_ticket)->first();
		$ticket->id_estado_tv = 2; // 2
        //$ticket->cantidad_llamada = 0;
        // $ticket->fecha_llamada = null;
        $ticket->anulado_at = Carbon::now();
        $ticket->anulado_us = Auth::user()->id;
        $ticket->llamado_at = null;
        $ticket->llamado_us = null;
        $ticket->seleccionado_at = null;
        $ticket->seleccionado_us = null;
        $ticket->atendido_at = null;
        $ticket->atendido_us = null;
        $ticket->ventanilla = null;
        //$ticket->orden_llamada = null;
        $ticket->estado_llamada = "0";
        //$ticket->estado = 1;
		$ticket->save();

        // $cita = Cita::where('id','=',$id_cita)->first();
        // $cita->estado_presencia = null;
        // $cita->fecha_presencia = null;
        // $cita->estado_triaje = null;
        // $cita->save();

        return "2";
    }
    
    public function busca_ticket_totem(Request $request) {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $id_establecimiento = Auth::user()->id_establecimiento;

        if ($request->fechaDesde != "") {
            $fechaDesde = $request->fechaDesde;
            $fechaHasta = $request->fechaHasta;
        } else {
            $date = Carbon::now();
            $fechaHasta = $date->format('d-m-Y');
            $date->modify('first day of this month');
            $fechaDesde = $date->format('d-m-Y');
        }
        $estado = $request->estado;

        $cad = "select 
                t.id,
                case when t.id_estado_tv = 0 then 'Pendiente' when t.id_estado_tv = 1 then 'Llamando' when t.id_estado_tv = 2 then 'Pospuesto' when t.id_estado_tv = 3 then 'Atendido' end AS estado,
                t.letra,
                t.numero,
                to2.nombre totem_opcion,
                coalesce(a.paterno||' '||a.materno||', '||a.nombre, '')::varchar paciente,
                to_char(t.created_at, 'HH24:MI PM')::varchar hora_ticket,
                coalesce(c.id, 0) id_cita,
                coalesce(to_char(c.fecha_hora, 'HH24:MI PM'), '')::varchar AS hora_cita,
                t.id_estado_tv,
                coalesce(t.cantidad_llamada, 0) AS cantidad_llamada,
                CASE WHEN to2.id = 2 THEN 'NO' ELSE 'SI' END::varchar AS citado
                from tickets t 
                left join asegurados a ON t.id_asegurado = a.id 
                left join citas c on t.id_cita = c.id
                inner join totem_opciones to2 on t.id_totem_opcion = to2.id 
	            inner join totems t2 on t.id_totem = t2.id
                where cast(t.created_at as date) between '".$fechaDesde."' and '".$fechaHasta."'
                and t.id_estado_tv in(".$estado.") and t.estado = 1
                and t2.id_establecimiento = ".$id_establecimiento."
                order by t.created_at, t.numero;";
        
        $listado = DB::select($cad);
        return view('frontend.tv.modulo_llamado_tv', compact('listado','fechaDesde','fechaHasta','estado'));
    }
    
    public function llamar_ticket_triaje($id_cita) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $cantidad_llamada = 0;
        $ticket = Ticket::where('id_cita', $id_cita)->first();
        $model_tv = new Tv();
        $id_ticket = $model_tv->llamar_citado_ticket($ticket->id, Auth::user()->id);
        
        if ($id_ticket > 0) {
            $ticket = Ticket::where('id','=',$id_ticket)->first();
            $cantidad_llamada = $ticket->cantidad_llamada;
        }
		
        return $cantidad_llamada;
	}

    public function confirmar_atencion_ticket($id_ticket) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $ticket = Ticket::find($id_ticket);
        /*$model_cita = new Cita();
 
        //Solo para imagenes
        if ($ticket->id_cita !== 0) {
            $cita = $model_cita->obtenerConsultorioPorIdCita($ticket->id_cita);
            $cita2 = Cita::find($ticket->id_cita);
            if ($cita[0]->id_servicio_sc == 114) {
            $cita2->id_estado_cita = 4;
            $cita2->save();
            }
        }*/
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

        return "3";
    }

    public function obtenerPaciente($id_ticket,$ventanilla) {
        if (!Auth::check()):
			return redirect('/auth/login');
		endif;
        $v_ticket = Ticket::where('id','=',$id_ticket)->first();
        $v_ticket->ventanilla = $ventanilla;
        $v_ticket->id_estado_tv = 4;
        $v_ticket->seleccionado_us = auth()->user()->id;
        $v_ticket->seleccionado_at = date("Y-m-d");
        $v_ticket->save();

        $dato = array();
        $aModelTicket = Ticket::where('id','=',$id_ticket)->first();
        $dato['turno'] = $aModelTicket->letra . '-' . $aModelTicket->numero;

        if ($aModelTicket->id_asegurado == 0) {
            $dato['paciente'] = '';
        }else {
            $aModelAsegurado = Asegurado::where('id','=',$aModelTicket->id_asegurado)->first();
            $dato['paciente'] = $aModelAsegurado->paterno . ' ' . $aModelAsegurado->materno . ', ' . $aModelAsegurado->nombre;
        }
        echo json_encode($dato);
    }

    public function obtenerPaciente2($id_ticket,$ventanilla) {
        if (!Auth::check()):
			return redirect('/auth/login');
		endif;
        /*
        $v_ticket = Ticket::where('id','=',$id_ticket)->first();
        $v_ticket->ventanilla = $ventanilla;
        $v_ticket->id_estado_tv = 4;
        $v_ticket->seleccionado_us = auth()->user()->id;
        $v_ticket->seleccionado_at = date("Y-m-d");
        $v_ticket->save();
        */
        $dato = array();
        $aModelTicket = Ticket::where('id','=',$id_ticket)->first();
        $dato['turno'] = $aModelTicket->letra . '-' . $aModelTicket->numero;

        if ($aModelTicket->id_asegurado == 0) {
            $dato['paciente'] = '';
        }else {
            $aModelAsegurado = Asegurado::where('id','=',$aModelTicket->id_asegurado)->first();
            $dato['paciente'] = $aModelAsegurado->paterno . ' ' . $aModelAsegurado->materno . ', ' . $aModelAsegurado->nombre;
        }
        echo json_encode($dato);
    }

    public function busca_ticket_totem2($fechaDesde,$fechaHasta,$estado) {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $id_establecimiento = Auth::user()->id_establecimiento;
        $id_consultorio = auth()->user()->id_consultorio_ticket;

        //Solo cuando es Diagnostico por imagenes
        if ($id_consultorio == 114) {
            $id_sub_cosultorio =auth()->user()->id_sub_consultorio_cita_ticket;
        }

        $id_totem_array = DB::select('select t.id from totems t 
        inner join corredores c on t.id_corredor = c.id 
        inner join corredores_detalle cd on cd.id_corredor = c.id 
        inner join users u on u.id_corredores_detalle = cd.id 
        where u.id ='.auth()->user()->id);
        $id_totem = $id_totem_array[0]->id;
        if ($fechaDesde != "") {
            $fd = $fechaDesde;
            $fh = $fechaHasta;
        } else {
            $date = Carbon::now();
            $fh = $date->format('d-m-Y');
            $date->modify('first day of this month');
            $fd = $date->format('d-m-Y');
        }
        $e = $estado;

        $cad = "select 
                t.id,
                case when t.id_estado_tv = 0 then 'Pendiente' when t.id_estado_tv = 1 then 'Llamando' when t.id_estado_tv = 2 then 'Pospuesto' when t.id_estado_tv = 3 then 'Atendido' end AS estado,
                t.id_estado_tv,
                t.letra,
                t.numero,
                to2.nombre totem_opcion,
                t.id_asegurado,
                case when t.id_asegurado = 0 or t.id_asegurado isnull then 
                split_part(t.nombres_reniec,',',2) ||' '|| split_part(t.nombres_reniec,',',3) ||', '|| split_part(t.nombres_reniec,',',1)
                else coalesce(a.paterno||' '||a.materno||', '||a.nombre, '')end::varchar as paciente,
                to_char(t.created_at, 'HH24:MI PM')::varchar hora_ticket,
                coalesce(c.id, 0) id_cita,
                to_char(t.created_at, 'dd/mm/yyyy')::varchar AS fecha_ticket,
                t.id_estado_tv,
                coalesce(t.cantidad_llamada, 0) AS cantidad_llamada,
                CASE WHEN to2.id = 2 THEN 'NO' ELSE 'SI' END::varchar AS citado, 
                t.ventanilla as id_ventanilla, v.nombre as nombre_ventanilla, to2.id_consultorio, t.llamado_us, t.llamado_at, t.seleccionado_us,
                c.fecha_hora as fecha_hora_cita, c.id_sub_consultorio as id_sc_cita, sc.descripcion as nom_sc_cita, sc.id_consultorio as servicio_cita
                from tickets t 
                left join asegurados a ON t.id_asegurado = a.id 
                left join citas c on t.id_cita = c.id
                left join ventanillas v on t.ventanilla = v.id
                left join sub_consultorios sc on c.id_sub_consultorio = sc.id
                inner join totem_opciones to2 on t.id_totem_opcion = to2.id 
	            inner join totems t2 on t.id_totem = t2.id
                where cast(t.created_at as date) between '".$fd ."' and '".$fh."'
                and t.id_estado_tv in(".$e.") and t.estado = 1
                and t2.id_establecimiento = ".$id_establecimiento." and to2.id_consultorio = ".$id_consultorio." and t.id_totem = ".$id_totem;
                if ($id_consultorio == 114) {
                    $cad.= " and c.id_sub_consultorio = ".$id_sub_cosultorio;
                }  
                $cad.=" order by t.created_at, t.numero;";

        $listado = DB::select($cad);
        if (empty($listado)) {
            return 'No hay registros';
        } else {
            return view('frontend.tv.listado_tickets', compact('listado'));
        }
    }

    public function listado_tickets() {
        if (!Auth::check()):
			return redirect('/auth/login');
		endif;

        $id_establecimiento = Auth::user()->id_establecimiento;
        $id_consultorio = auth()->user()->id_consultorio_ticket;
        //Solo cuando es Diagnostico por imagenes
        if ($id_consultorio == 114) {
            $id_sub_cosultorio =auth()->user()->id_sub_consultorio_cita_ticket;
        }
        $id_totem_array = DB::select('select t.id from totems t 
        inner join corredores c on t.id_corredor = c.id 
        inner join corredores_detalle cd on cd.id_corredor = c.id 
        inner join users u on u.id_corredores_detalle = cd.id 
        where u.id ='.auth()->user()->id);
        $id_totem = $id_totem_array[0]->id;
        $date = Carbon::now();
        $fechaDesde = $date->format('d-m-Y');
        $fechaHasta = $date->format('d-m-Y');
        $estado = "0,1,2,4";

        $cad = "select 
                t.id,
                case when t.id_estado_tv = 0 then 'Pendiente' when t.id_estado_tv = 1 then 'Llamando' when t.id_estado_tv = 2 then 'Pospuesto' when t.id_estado_tv = 3 then 'Atendido' end AS estado,
                t.id_estado_tv,
                t.letra,
                t.numero,
                to2.nombre totem_opcion,
                t.id_asegurado,
                case when t.id_asegurado = 0 or t.id_asegurado isnull then 
                split_part(t.nombres_reniec,',',2) ||' '|| split_part(t.nombres_reniec,',',3) ||', '|| split_part(t.nombres_reniec,',',1)
                else coalesce(a.paterno||' '||a.materno||', '||a.nombre, '')end::varchar as paciente,
                to_char(t.created_at, 'HH24:MI PM')::varchar hora_ticket,
                coalesce(c.id, 0) id_cita,
                to_char(t.created_at, 'dd/mm/yyyy')::varchar AS fecha_ticket,
                t.id_estado_tv,
                coalesce(t.cantidad_llamada, 0) AS cantidad_llamada,
                CASE WHEN to2.id = 2 THEN 'NO' ELSE 'SI' END::varchar AS citado, 
                t.ventanilla as id_ventanilla, v.nombre as nombre_ventanilla, to2.id_consultorio, t.llamado_us, t.llamado_at, t.seleccionado_us,
                c.fecha_hora as fecha_hora_cita, c.id_sub_consultorio as id_sc_cita, sc.descripcion as nom_sc_cita, sc.id_consultorio as servicio_cita
                from tickets t 
                left join asegurados a ON t.id_asegurado = a.id 
                left join citas c on t.id_cita = c.id
                left join ventanillas v on t.ventanilla = v.id
                left join sub_consultorios sc on c.id_sub_consultorio = sc.id
                inner join totem_opciones to2 on t.id_totem_opcion = to2.id 
	            inner join totems t2 on t.id_totem = t2.id
                where cast(t.created_at as date) between '".$fechaDesde."' and '".$fechaHasta."'
                and t.id_estado_tv in(".$estado.") and t.estado = 1
                and t2.id_establecimiento = ".$id_establecimiento." and to2.id_consultorio = ".$id_consultorio." and t.id_totem = ".$id_totem;
        if ($id_consultorio == 114) {
            $cad.= " and c.id_sub_consultorio = ".$id_sub_cosultorio;
        }        
            $cad.=" order by t.created_at, t.numero;";

        $listado = DB::select($cad);
        return view('frontend.tv.listado_tickets', compact('listado'));
    }

    public function obtenerVentanillaPorServicio($id_servicio){
        if (!Auth::check()):
			return redirect('/auth/login');
		endif;

        $cad = "select * from ventanillas v where v.id_servicio = ".$id_servicio.";";

        $ventanilla = DB::select($cad);

        return $ventanilla;
    }
    public function obtenerNombreVentanillaPorServicio($id_ventanilla){
        if (!Auth::check()):
			return redirect('/auth/login');
		endif;

        $cad = "select * from ventanillas v where v.id = ".$id_ventanilla.";";

        $ventanilla = DB::select($cad);

        return $ventanilla;
    }

    public function obtenerPacienteLlamadoUs($id_user)
    {   
        if (!Auth::check()):
			return redirect('/auth/login');
		endif;
        $dato = array();
        $fechaActual = date('Y-m-d');
        $model_llamadoUs = Ticket::where('llamado_at','=',$fechaActual)->where('llamado_us','=',$id_user)->first();
        if (isset($model_llamadoUs)) {
            $dato['id_ticket'] = $model_llamadoUs->id;
            $dato['turno'] = $model_llamadoUs->letra . '-' . $model_llamadoUs->numero;
            if ($model_llamadoUs->id_asegurado == 0) {
                $dato['paciente'] = '';
            }else {
                $aModelAsegurado = Asegurado::where('id','=',$model_llamadoUs->id_asegurado)->first();
                $dato['paciente'] = $aModelAsegurado->paterno . ' ' . $aModelAsegurado->materno . ', ' . $aModelAsegurado->nombre;
            } 
        } else{
            $dato['id_ticket'] = '';
            $dato['turno'] = '';
            $dato['paciente'] = '';
        }
        echo json_encode($dato);
    }

    public function obtenerPacienteSeleccionadoUs($id_user)
    {   
        if (!Auth::check()):
			return redirect('/auth/login');
		endif;
        $dato = array();
        $fechaActual = date('Y-m-d');
        $model_seleccionadoUs = Ticket::where('seleccionado_at','=',$fechaActual)->where('seleccionado_us','=',$id_user)->first();
        if (isset($model_seleccionadoUs)) {
            $dato['id_ticket'] = $model_seleccionadoUs->id;
            $dato['turno'] = $model_seleccionadoUs->letra . '-' . $model_seleccionadoUs->numero;
            if ($model_seleccionadoUs->id_asegurado == 0) {
                $dato['paciente'] = '';
            }else {
                $aModelAsegurado = Asegurado::where('id','=',$model_seleccionadoUs->id_asegurado)->first();
                $dato['paciente'] = $aModelAsegurado->paterno . ' ' . $aModelAsegurado->materno . ', ' . $aModelAsegurado->nombre;
            } 
        } else{
            $dato['id_ticket'] = '';
            $dato['turno'] = '';
            $dato['paciente'] = '';
        }
        echo json_encode($dato);
    }

    public function modal_ventanillas()
    {   
        if (!Auth::check()):
			return redirect('/auth/login');
		endif;
        $card = "select c.id, c.nombre, c.estado from corredores c where c.estado = '1'";
        $corredores = DB::select($card);
        return view('frontend.tv.modal_ventanillas',compact('corredores'));
    }

    public function obtenerCorredores($id_corredor)
    {
        if (!Auth::check()):
			return redirect('/auth/login');
		endif;
        $id_consultorio = auth()->user()->id_consultorio_ticket;
        $card = "select cd.id,  cd.id_corredor, cd.estado, cd.id_ventanilla, v.id_servicio, v.nombre as nombre_ventanilla
        from corredores_detalle cd 
        inner join ventanillas v on v.id = cd.id_ventanilla
        where cd.id_corredor = ".$id_corredor."
        and cd.updated_us is null  and v.estado = '1'  and v.id_servicio = ".$id_consultorio." order by nombre_ventanilla asc";
//	and v.id_servicio = ".$id_consultorio."

        $ventanillas = DB::select($card);
        return $ventanillas;
    }

    public function obtenerVentanillaPorCorredor($id_corredor_detalle)
    {
        if (!Auth::check()):
			return redirect('/auth/login');
		endif;

        $user = User::where('id','=',auth()->user()->id)->first();
        $corredores_detalle = CorredoresDetalle::where('id','=',$id_corredor_detalle)->first();
        if ($corredores_detalle->updated_us == null) {
            $user->id_corredores_detalle = $id_corredor_detalle;
            $user->id_ventanilla = $corredores_detalle->id_ventanilla;
            $corredores_detalle->updated_us = auth()->user()->id;
            $corredores_detalle->updated_at = date("Y-m-d");
            $user->save();
            $corredores_detalle->save();
            return 'true';
        } else {
            return 'false';
        }


    }
    public function posponer_llamado_ticket_logout() {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        $user = auth()->user()->id;
        $ticket = Ticket::Where('seleccionado_us','=',$user)
                        ->orWhere('llamado_us',$user)
                        ->whereIn('id_estado_tv',[1,4])->first();
        if (!empty($ticket)) {
            $ticket->id_estado_tv = 2; // 2
            $ticket->anulado_at = Carbon::now();
            $ticket->anulado_us = Auth::user()->id;
            $ticket->llamado_at = null;
            $ticket->llamado_us = null;
            $ticket->seleccionado_at = null;
            $ticket->seleccionado_us = null;
            $ticket->atendido_at = null;
            $ticket->atendido_us = null;
            $ticket->ventanilla = null;
            //$ticket->orden_llamada = null;
            $ticket->estado_llamada = "0";
            //$ticket->estado = 1;
            $ticket->save();
            //return $ticket;
        } else {
            return 'vacio';
        }
    }

    public function anular_ticket($id_ticket)
    {
        if (!Auth::check()) :
        return redirect('/auth/login');
        endif;

        $ticket = Ticket::where('id', $id_ticket)->first();
        $ticket->estado = 0;
        $ticket->id_estado_tv = 5;  //Código 5 es anulado
        $ticket->updated_at = Carbon::now();
        $ticket->updated_us = auth()->user()->id;
        $ticket->anulado_at = null;
        $ticket->anulado_us = null;
        $ticket->llamado_at = null;
        $ticket->llamado_us = null;
        $ticket->seleccionado_at = null;
        $ticket->seleccionado_us = null;
        $ticket->atendido_at = null;
        $ticket->atendido_us = null;
        $ticket->ventanilla = null;
        $ticket->estado_llamada = "0";
        $ticket->save();

        $array["sw"] = true;
        echo json_encode($array);
    }

    public function obtenerSubConsultorioParaTv($id_establecimiento, $id_servicio, $id_tv)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $model_sub_consultorio = new App\Models\SubConsultorio;
        $res = $model_sub_consultorio->GetSubConsultorioByIdEstablecimientoIdConsultorio($id_establecimiento, $id_servicio, $id_tv);

        return $res;
    }

    public function registrarSubConsultorioParaTv($id_tv, $id_sub_consultorio)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $p[] = $id_tv;
        $p[] = $id_sub_consultorio;
        $p[] = auth()->user()->id;
        $model_tv = new App\Models\Tv;
        $res = $model_tv->insertTvSubConsultorio($p);

        return $res;
    }

    public function deleteTvSubConsultorio($id_tv_sub_consultorio)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $p[] = intval($id_tv_sub_consultorio);
        $model_tv = new App\Models\Tv;
        $res = $model_tv->eliminarTvSubConsultorio($p);

        return $res;
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

    public function all_radiologia_prescritas_by_ipress_ticket($id_user = 0) {

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
        $model_radiologia = new \App\Models\Radiologia;
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
            return view('frontend.radiologias.all_radiologias_prescritas_by_ipress_citados_tickets', compact('radiologias', 'cantidad', 'farmacia', 'fechaoDesde', 'fechaoHasta', 'fechaDesde', 'fechaHasta','fechamDesde', 'fechamHasta', 'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nro_prestacion','tipo_registro', 'nro_examen','id_user','servicio_examen','tipo_atenciones','tipo_perfil','id_establecimiento','id_prioridad_temp','id_servicio_temp','id_servicio_imagenes_temp','id_referido_temp','id_estado_muestra_temp','id_estado_informe_temp','id_tipo_atencion', 'farmacias','listServiciosImagenes'));
        else:
            //====================
            dd('Módulo para nivel 1 y 2 no se encuentra habilitado ...');
            //return view('frontend.radiologias.all_radiologias_prescritas_by_ipress_nivel1y2', compact('radiologias', 'cantidad', 'farmacia', 'fechaoDesde', 'fechaoHasta', 'fechaDesde', 'fechaHasta','fechamDesde', 'fechamHasta', 'modo_validacion', 'id_farmacia', 'dni_beneficiario', 'nro_prestacion','tipo_registro', 'nro_examen','id_user','servicio_examen','tipo_atenciones','tipo_perfil','id_establecimiento','id_prioridad_temp','id_servicio_temp','id_servicio_imagenes_temp','id_referido_temp','id_estado_muestra_temp','id_estado_informe_temp','id_tipo_atencion', 'farmacias','listServiciosImagenes','nivel'));
        endif;
    }

    public function listar_examenes_radiologicos_ticket(Request $request) {


        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        if (!$request->id_user):
            return redirect('/all_radiologia_prescritas_by_ipress_ticket');
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
        $estado_llamado = $request->estado_llamado;

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
        $model_radiologia = new \App\Models\Radiologia();

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
        $radiologias = $model_radiologia->AllExamenRadiologiaAmbulatorioByEstablecimientoAndFechaDesdeHastaTicketCitados(
            $id_establecimiento,$fechaoDesde,$fechaoHasta,$fechaDesde, $fechaHasta,$fechamDesde, $fechamHasta, $dni_beneficiario,$id_servicio_imagenes,$estado_muestra,$estado_informe,$estado_llamado
        );
        $cantidad = count($radiologias);
        /*
        $model_radiologia = new \App\Models\Radiologia;
            if (in_array(Auth::user()->tipo_registro, [6, 7, 1])) {
                foreach ($radiologias as $key) {
                    $key->servicio_examen = $model_radiologia->AllServicioExamenRadiologiaByEstablecimientoAndFechaDesdeHasta($key->id_pp,$dni_beneficiario);
                    $key->informe_adicional = $model_radiologia->obtenerInformesAdicionales($key->id_pp,$dni_beneficiario);
                }
            }*/

        
    
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
            return view('frontend.radiologias.all_radiologias_prescritas_by_ipress_citados_tickets', compact('radiologias', 'cantidad', 'farmacia', 'fechaoDesde', 'fechaoHasta', 'fechaDesde', 'fechaHasta','fechamDesde', 'fechamHasta', 'id_farmacia', 'dni_beneficiario', 'nro_prestacion', 'nro_examen','id_user', 'tipo_atenciones','tipo_perfil','id_establecimiento','id_tipo_atencion','id_prioridad_temp','id_servicio_temp','id_servicio_imagenes_temp','id_referido_temp','id_estado_muestra_temp','id_estado_informe_temp','farmacias','listServiciosImagenes','nivel'));
        else:
            dd('Módulo para nivel 1 y 2 no se encuentra habilitado ...');
        endif;
        
        
    }

    public function llamar_ticket_imagenes($id_ticket) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $cantidad_llamada = 0;
        $model_tv = new Tv();
        $model_ticket = Ticket::where('id','=',$id_ticket)->first();
        $model_ticket->seleccionado_at = null;
        $model_ticket->seleccionado_us = null;
        $model_ticket->anulado_at = null;
        $model_ticket->anulado_us = null;
        $model_ticket->atendido_at = null;
        $model_ticket->atendido_us = null;
        $model_ticket->ventanilla = Auth::user()->id_ventanilla;
        $model_ticket->save();
        $ventanilla = $model_ticket->ventanilla;
        $id_ticket = $model_tv->llamar_citado_ticket2($id_ticket, Auth::user()->id, $ventanilla);
        
        if ($id_ticket > 0) {
            $ticket = Ticket::where('id','=',$id_ticket)->first();
            $cantidad_llamada = $ticket->cantidad_llamada;
        }
		
        return $cantidad_llamada;
	}
}