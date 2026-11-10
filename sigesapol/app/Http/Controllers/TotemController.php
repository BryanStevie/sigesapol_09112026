<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Asegurado;
use App\Models\Cita;
use App\Models\Consultorio;
use App\Models\Corredore;
use App\Models\Establecimiento;
use App\Models\SubConsultorio;
use App\Models\Ticket;
use App\Models\Totem;
use App\Models\Totem_ip;
use App\Models\Totem_ip_configuracione;
use App\Models\Totem_opcione;
use App\Models\TotemServicio;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Session;

class TotemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('auth'); 
    } 
    
    public function index()
    {
        if (!Auth::check()):
            return redirect('/auth/login');
        else:
            if(Auth::user()->actualizo_passw==0):
                return redirect('/auth/password/change/');
            endif;
        endif;

        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1);
        $ruta = $ruta[0];

        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        if ($menu != null):
            $permiso = $menu[0];
            if ($permiso->lectura == "" || $permiso->lectura == "0"):
                return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
            endif;
        else:
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        endif;

        $id_establecimiento = Auth::user()->id_establecimiento;
        $cad = "select 
                t.id,
                e.nombre establecimiento,
                t.descripcion nombre,
                t.id_estado,
                CASE WHEN t.id_estado = 1 THEN 'ACTIVO'
                ELSE 'INACTIVO' END des_estado,
                string_agg(c.nombre,', ') servicios,
                t.ip,
                c2.nombre corredor
                from totems t 
                    inner join establecimientos e on t.id_establecimiento = e.id 
                    inner join totem_servicios ts on ts.id_totem = t.id 
                    inner join consultorios c ON ts.id_consultorio = c.id
                    left join corredores c2 on t.id_corredor = c2.id
                WHERE ts.id_estado = 1 and t.id_establecimiento = ".$id_establecimiento."
                GROUP BY t.id, e.id, c2.nombre;";
        
        $listado = DB::select($cad);

        return view('frontend.totems.all', compact('listado'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $model_establecimiento = new Establecimiento();
        $establecimientos = $model_establecimiento->ShowAll();
        $model_corredore = new Corredore();
        $corredores = $model_corredore->ShowAll();
        $opciones_totem = $this->get_opciones_totem();

        return view('frontend.totems.create', compact("establecimientos", "opciones_totem","corredores"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        //Nuevo Totem
        if ($request->id_totem == "") {
            $totem = new Totem;
            $totem->id_establecimiento = $request->cbo_establecimiento;
            $totem->id_corredor = $request->id_corredor;
            $totem->descripcion = strtoupper($request->descripcion);
            $totem->ip = $request->ip;
            $totem->id_estado = 1;
            $totem->created_us = Auth::user()->id;
            $totem->save();

            $id_totem = $totem->id;
            if ($id_totem > 0) {
                //Configuraciones de servicios
                if (!is_null($request->id_consultorio)) {
                    foreach ($request->id_consultorio as $key => $value) {
                        $totem_servicio = new TotemServicio;
                        $totem_servicio->id_totem = $id_totem;
                        $totem_servicio->id_consultorio = $request->id_consultorio[$key];
                        $totem_servicio->id_estado = 1;
                        $totem_servicio->created_us = Auth::user()->id;
                        $totem_servicio->save();
                    }
                }
                
                // Opciones del totem
                if (!is_null($request->opcion_totem)) {
                    // Registrar nuevas opciones
                    foreach ($request->opcion_totem as $key => $value) {
                        $totem_configuracione = new Totem_ip_configuracione;
                        $totem_configuracione->id_totem = $id_totem;
                        $totem_configuracione->id_totem_opcion = $request->opcion_totem[$key];
                        $totem_configuracione->estado = 1;
                        $totem_configuracione->created_us = Auth::user()->id;
                        $totem_configuracione->save();
                    }
                }

                Session::flash('flash_message', 'Registro exitoso');
            } else {
                Session::flash('flash_message', 'Ha ocurrido un error al registrar el Totem.');
            }
        // Edicion de Totem
        } else {

            $id_totem = $request->id_totem;
            $totem = Totem::where('id','=',$id_totem)->first();
            $totem->id_establecimiento = $request->cbo_establecimiento;
            $totem->id_corredor = $request->id_corredor;
            $totem->descripcion = strtoupper($request->descripcion);
            $totem->ip = $request->ip;
            $totem->id_estado = 1;
            $totem->updated_at = Carbon::now();
            $totem->updated_us = Auth::user()->id;
            $totem->save();

            $model_totem = new Totem();
            $servicios_elegidos = $model_totem->getConsultoriosPorIdTotem($id_totem);
            $opciones_elegidos = $model_totem->getTotemOpcionesPorIdDispositivo($id_totem);
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
                        $totem_servicio = new TotemServicio;
                        $totem_servicio->id_totem = $id_totem;
                        $totem_servicio->id_consultorio = $request->id_consultorio[$key];
                        $totem_servicio->id_estado = 1;
                        $totem_servicio->created_us = Auth::user()->id;
                        $totem_servicio->save();
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
                        $totem_servicio = TotemServicio::where('id_totem', '=', $id_totem)->where('id_consultorio','=',$rowConsul->id)->where('id_estado', '=', 1)->first();
                        $totem_servicio->id_estado = 0;
                        $totem_servicio->updated_us = Auth::user()->id;
                        $totem_servicio->save();
                    }
                endforeach;
            }

            if (!is_null($request->opcion_totem)) {
                // Registrar nuevas opciones
                foreach ($request->opcion_totem as $key => $value) {
                    $existe = "no";
                    foreach ($opciones_elegidos as $rowConsul) :
                        if ($request->opcion_totem[$key] == $rowConsul->id) {
                            $existe = "si";
                        }
                    endforeach;
                    if ($existe == "no") {
                        $totem_configuracione = new Totem_ip_configuracione;
                        $totem_configuracione->id_totem = $id_totem;
                        $totem_configuracione->id_totem_opcion = $request->opcion_totem[$key];
                        $totem_configuracione->estado = 1;
                        $totem_configuracione->created_us = Auth::user()->id;
                        $totem_configuracione->save();
                    }
                }

                // Deshabilitar opciones
                foreach ($opciones_elegidos as $rowConsul) :
                    $existe = "no";
                    foreach ($request->opcion_totem as $key => $value) {
                        if ($rowConsul->id == $request->opcion_totem[$key]) {
                            $existe = "si";
                        }
                    }
                    if ($existe == "no") {
                        $totem_servicio = Totem_ip_configuracione::where('id_totem', '=', $id_totem)->where('id_totem_opcion','=',$rowConsul->id)->where('estado', '=', 1)->first();
                        $totem_servicio->estado = 0;
                        $totem_servicio->updated_us = Auth::user()->id;
                        $totem_servicio->save();
                    }
                endforeach;
            }

            Session::flash('flash_message', 'Modificación exitosa');
        }

        return redirect('/totem_all');
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

        $totem = Totem::where('id','=',$id)->first();
        $model_establecimiento = new Establecimiento();
        $establecimientos = $model_establecimiento->ShowAll();

        $model_consultorio = new Consultorio();
        //dd($tv->id_establecimiento);
        $servicios = $model_consultorio->GetAllPorIpress($totem->id_establecimiento);

        $model_totem = new Totem();
        $servicios_elegidos = $model_totem->getConsultoriosPorIdTotem($id);
        $lista_ips = Totem_ip::where('id_totem','=',$id)->where('id_estado','1')->get();

        $opciones_totem = $this->get_opciones_totem();
        $opciones_elegidos = $model_totem->getTotemOpcionesPorIdDispositivo($id);

        $corredore = new Corredore();
        $corredores = $corredore->ShowAllByIdEstablecimiento($totem->id_establecimiento);
        // dd($totem);
        return view('frontend.totems.edit', compact("id", "totem", "establecimientos", "servicios", "servicios_elegidos", "lista_ips", "opciones_totem", "opciones_elegidos","corredores"));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

    public function totem_sin_auth($ip = '') {

        if (session()->has('totem_idasegurado')) {
            session()->forget('totem_idasegurado');
        }

        $ip = ($ip == '') ? (session()->has('totem_ip') ? session('totem_ip') : '') : $ip;
        if ($ip != '') {
            $totem = Totem::where('ip',$ip)->where('id_estado','1')->first();
            if($totem != null) {
                $id_totem = $totem->id;
                $model_establecimiento = new Establecimiento();
                $establecimiento = $model_establecimiento->Get($totem->id_establecimiento);

                session(['totem_idtotem' => $id_totem]);
                session(['totem_ip' => $ip]);
            } else {
                return view('errors.404', compact('ip'));
            }
        } else return view('errors.404');

        return view('frontend.totems.index', compact('id_totem', 'establecimiento','ip'));
    }

    // public function totem_citas($id_parent) {
    //     if (session()->has('totem_idasegurado')) {
    //         $id_asegurado = session('totem_idasegurado');
    //         $id_dispositivo = session('totem_id_dispositivo');
    //     }
    //     $asegurado = Asegurado::where('id', $id_asegurado)->first();

    //     $totem_ip_configuracione = new Totem_ip_configuracione();
    //     $totem_opciones = $totem_ip_configuracione->get_opciones_totem_by_dispositivo_and_parent($id_dispositivo, $id_parent);
    //     // dd($totem_opciones);
    //     if (count($asegurado) > 0):
    //         $totem = new Totem();
    //         $citas = $totem->buscar_cita_dia($id_asegurado);
    //     endif;

    //     return view('frontend.totems.menu_citas_medicas', compact('asegurado','totem_opciones','citas'));
    // }
	
    public function validar_documento(Request $request) {
        
        $asegurado = Asegurado::where('id_tipo_doc_ident',$request->tipo_documento)
                                ->where('nro_doc_ident',$request->nro_documento)
                                ->first();
        if (count($asegurado) == 1):
            session(['totem_idasegurado' => $asegurado->id]);
            session(['totem_idtotem' => $request->id_totem]);
            // session(['totem_id_dispositivo' => $request->id_dispositivo]);
            // session(['totem_ip' => $request->ip]);
        endif;

        if (count($asegurado) == 1):
            $totem_ip_configuracione = new Totem_ip_configuracione();
            $totem_opciones = $totem_ip_configuracione->get_todos_opciones_totem_by_dispositivo($request->id_totem);

            $totem_servicio = TotemServicio::where('id_totem',$request->id_totem)->where('id_estado','1')->get();

            $totem = new Totem();
            $citas = $totem->buscar_cita_dia($asegurado->id, $totem_servicio);
        else:
            $totem_opciones = "";
            $citas = "";
        endif;

        return view('frontend.totems.menu_principal', compact('asegurado','totem_opciones','citas'));
    }

    public function indicar_presencia_cita(Request $request) {
        $cita = Cita::where('id', $request->id_cita)->first();
        $cita->estado_presencia = '1';
        $cita->fecha_presencia = Carbon::now();
        $registrado = $cita->save();
        // return json_encode($registrado);
        return view('frontend.totems.paso3', compact('registrado'));
    }

    public function buscar_cita_dia() {
        if (session()->has('totem_idasegurado')) {
            $id_asegurado = session('totem_idasegurado');
        }
        $asegurado = Asegurado::where('id', $id_asegurado)->first();
        $totem = new Totem();
        $citas = null;
        if (count($asegurado) > 0):
            $citas = $totem->buscar_cita_dia($id_asegurado);
        endif;
        return view('frontend.totems.indicar_presencia', compact('asegurado','citas'));
    }

    /**
     * Ir al menu principal sin validar porque ya se valido su documento
     */
    public function menu_principal() {
        if (session()->has('totem_idasegurado')) {
            $id_asegurado = session('totem_idasegurado');
            $id_totem = session('totem_idtotem');
            // $id_dispositivo = session('totem_id_dispositivo');
        } else {
            return '';
        }
        
        $totem_ip_configuracione = new Totem_ip_configuracione();
        $totem_opciones = $totem_ip_configuracione->get_todos_opciones_totem_by_dispositivo($id_totem);

        $asegurado = Asegurado::where('id', $id_asegurado)->first();
        $totem_servicio = TotemServicio::where('id_totem',$id_totem)->get();

        $totem = new Totem();
        $citas = $totem->buscar_cita_dia($asegurado->id, $totem_servicio);

        return view('frontend.totems.menu_principal', compact('asegurado','totem_opciones','citas'));
    }

    public function menu_otros_tramites($id_parent) {
        // if (session()->has('totem_idasegurado')) {
        //     $id_asegurado = session('totem_idasegurado');
        //     $id_dispositivo = session('totem_id_dispositivo');
        // }
        $id_asegurado = (session()->has('totem_idasegurado')) ? session('totem_idasegurado') : 0;
        // $id_dispositivo = (session()->has('totem_id_dispositivo')) ? session('totem_id_dispositivo') : 0;
        $id_totem = (session()->has('totem_idtotem')) ? session('totem_idtotem') : 0;

        $totem_ip_configuracione = new Totem_ip_configuracione();
        $totem_opciones = $totem_ip_configuracione->get_opciones_totem_by_dispositivo_and_parent($id_totem, $id_parent);
        
        $asegurado = ($id_asegurado > 0) ? Asegurado::where('id', $id_asegurado)->first() : null;
        return view('frontend.totems.menu_otros_tramites', compact('asegurado','totem_opciones'));
    }

    public function generar_ticket($id_totem_opcion, $id_cita = 0) {     //generar_ticket/2/0
        
        // if (session()->has('totem_idasegurado')) {
        //     $id_asegurado = session('totem_idasegurado');
        //     $id_dispositivo = session('totem_id_dispositivo');
        // }

        $id_asegurado = (session()->has('totem_idasegurado')) ? session('totem_idasegurado') : 0;
        // $id_dispositivo = (session()->has('totem_id_dispositivo')) ? session('totem_id_dispositivo') : 0;
        $id_totem = (session()->has('totem_idtotem')) ? session('totem_idtotem') : 0;

        $cad = "select * from sp_generar_ticket(?,?)";
        $data = DB::select($cad, array($id_totem, $id_totem_opcion)); //--4 , 2
        $numero = $data[0]->sp_generar_ticket; //-- 1

        $totem_opcione = Totem_opcione::where('id',$id_totem_opcion)->first();
        $letra = $totem_opcione->letra; //-- R
        $id_consultorio = $totem_opcione->id_consultorio; //--242

        // $totem_ip = Totem_ip::where('id', $id_dispositivo)->first();
        $totem = Totem::where('id', $id_totem)->first();

        $sub_consultorio = SubConsultorio::where('id_establecimiento', $totem->id_establecimiento)->where('id_consultorio',$id_consultorio)->first();

        $ticket = new Ticket();
        $ticket->id_totem = $id_totem;
        $ticket->id_totem_opcion = $id_totem_opcion;
        $ticket->id_asegurado = $id_asegurado;
        $ticket->letra = $letra;
        $ticket->numero = $numero;
        $ticket->created_at = Carbon::now();
        $ticket->estado = 1;
        $ticket->id_cita = $id_cita;
        $ticket->id_sub_consultorio = $sub_consultorio->id;

        $hora_cita_programada = null;
        if ($id_cita > 0):
            $cita = Cita::find($id_cita);
            $cita->estado_triaje = '1';
            $cita->estado_presencia = '1';
            $cita->fecha_presencia = Carbon::now();

            $hora_cita_programada = $cita->fecha_hora;
            $cita->save();
        endif;
        
        $registrado = $ticket->save();

        $latestId = Ticket::latest('id')->first()->id;
        $ticket2 = Ticket::find($latestId);

        $asegurado = null;
        if ($id_asegurado > 0) {
            $asegurado = Asegurado::find($id_asegurado);
        }
        //dd($data);
        return view('frontend.totems.impresion_ticket', compact('registrado','letra','numero','hora_cita_programada','asegurado','ticket2'));
    }

    public function actualizar_estado($id_totem, $estado) {
        $totem = Totem::where('id','=',$id_totem)->first();
        $totem->id_estado = $estado;
        $totem->save();
        return "Y";
    }

    public function get_opciones_totem() {
        // $totem_opcione = Totem_opcione::where('estado','1')->get();
        // return json_encode($totem_opcione);

        $cad = "select 
                t.id,
                t.nombre
                from totem_opciones t 
                where t.estado = 1
                order by t.id;";
        
        $totem_opcione = DB::select($cad);
        //dd($totem_opcione);
        return $totem_opcione;

        
    }

    public function totem_configuracion($id_dispositivo) {
        $totem_ip = Totem_ip::where('id', $id_dispositivo)->first();
        $totem = Totem::where('id', $totem_ip->id_totem)->first();
        $establecimiento = Establecimiento::where('id', $totem->id_establecimiento)->first();

        $model_totem = new Totem;
        $servicios_elegidos = $model_totem->getConsultoriosPorIdTotem($totem->id);
        $ip = $totem_ip->ip;

        $opciones_totem = $this->get_opciones_totem();
        $opciones_elegidos = $model_totem->getTotemOpcionesPorIdDispositivo($totem_ip->id);
        
        return view('frontend.totems.edit_config_opciones', compact("totem","id_dispositivo", "establecimiento", "servicios_elegidos", "ip", "opciones_totem","opciones_elegidos"));
    }

    public function store_opciones_totem(Request $request)
    {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $id_dispositivo = $request->id_dispositivo;
        $model_totem = new Totem();
        $opciones_elegidos = $model_totem->getTotemOpcionesPorIdDispositivo($id_dispositivo);
        $existe = "no";

        if (!is_null($request->opcion_totem)) {
            // Registrar nuevos subconsultorios
            foreach ($request->opcion_totem as $key => $value) {
                $existe = "no";
                foreach ($opciones_elegidos as $rowConsul) :
                    if ($request->opcion_totem[$key] == $rowConsul->id) {
                        $existe = "si";
                    }
                endforeach;
                if ($existe == "no") {
                    $totem_ip_configuracione = new Totem_ip_configuracione;
                    $totem_ip_configuracione->id_totem_dispositivo = $id_dispositivo;
                    $totem_ip_configuracione->id_totem_opcion = $request->opcion_totem[$key];
                    $totem_ip_configuracione->estado = 1;
                    $totem_ip_configuracione->created_us = Auth::user()->id;
                    $totem_ip_configuracione->save();
                }
            }

            // Deshabilitar subconsultorios
            foreach ($opciones_elegidos as $rowConsul) :
                $existe = "no";
                foreach ($request->opcion_totem as $key => $value) {
                    if ($rowConsul->id == $request->opcion_totem[$key]) {
                        $existe = "si";
                    }
                }
                if ($existe == "no") {
                    $totem_ip_configuracione2 = Totem_ip_configuracione::where('id_totem_dispositivo', '=', $id_dispositivo)->where('id_totem_opcion','=',$rowConsul->id)->where('estado', '=', 1)->first();
                    $totem_ip_configuracione2->estado = 0;
                    $totem_ip_configuracione2->updated_us = Auth::user()->id;
                    $totem_ip_configuracione2->save();
                }
            endforeach;
        }

        Session::flash('flash_message', 'Modificación exitosa');

        return redirect('/totem_configuracion/'.$id_dispositivo);
    }
}
