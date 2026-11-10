<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Consultorio;
use App\Models\Corredore;
use App\Models\CorredoresDetalle;
use App\Models\Establecimiento;
use App\Models\Modulos;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;


class CorredoresDetalleController extends Controller
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
        if ($menu != null):
            $permiso = $menu[0];
            if ($permiso->lectura == "" || $permiso->lectura == "0"):
                return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
            endif;
        else:
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        endif;
        
        $id_establecimiento = Auth::user()->id_establecimiento;
        $p[] = $id_establecimiento;
        $model = new CorredoresDetalle();
        $listado = $model->listar($p);
        //dd($listado);
        return view('frontend.corredores_detalle.all', compact('listado'));
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

        return view('frontend.corredores_detalle.create', compact("establecimientos"));
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

        if ($request->id_corredores_detalle == "") {
            $corredor_detalle = new CorredoresDetalle();
            $corredor_detalle->id_corredor = $request->id_corredor;
            $corredor_detalle->estado = '1';
            $corredor_detalle->created_at = Carbon::now();
            $corredor_detalle->created_us = auth()->user()->id;
            $corredor_detalle->id_ventanilla = $request->id_ventanilla;
            $corredor_detalle->save();
            Session::flash('flash_message', 'Registro exitoso');

        } else {
            $id_corredores_detalle = $request->id_corredores_detalle;
            $corredor_detalle = CorredoresDetalle::where('id','=',$id_corredores_detalle)->first();
            $corredor_detalle->id_corredor = $request->id_corredor;
            $corredor_detalle->updated_at = Carbon::now();
            $corredor_detalle->updated_us = auth()->user()->id;
            $corredor_detalle->id_ventanilla = $request->id_ventanilla;
            $corredor_detalle->save();
            Session::flash('flash_message', 'Modificación exitosa');
        }

        return redirect('/corredores_detalle_all');
        
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

        $model_corredores_detalle = new CorredoresDetalle();
        $corredores_detalle = $model_corredores_detalle->obtenerRegistroCorredorDetalle($id);
        
        //Lista de Establecimiento
        $model_establecimiento = new Establecimiento();
        $establecimientos = $model_establecimiento->ShowAll();
        
        //Lista de corredores por establecimiento de corredores_detalle
        $corredore = new Corredore();
        $corredores = $corredore->ShowAllByIdEstablecimiento($corredores_detalle->id_establecimiento);

        //Lista de Servicios con estado_servicio_ticket = 1
        $model_consultorio = new Consultorio();
        $ticket_servicios = $model_consultorio->ShowConsultoriosTicketsAll();
        
        //ventanilla configurada del corredor_detalle
        $modulos = $model_corredores_detalle->obtenerModulosPorServicio($corredores_detalle->id_servicio);

    return view('frontend.corredores_detalle.edit', compact("establecimientos", "corredores", "corredores_detalle","ticket_servicios","modulos"));
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

    public function obtenerModulosPorServicio($id_servicio_ticket){
        
        if (!Auth::check()):
			return redirect('/auth/login');
		endif;
        
        $model = new CorredoresDetalle();
        $modulos = $model->obtenerModulosPorServicio($id_servicio_ticket);
        return $modulos;
    }

    public function get_servicio_ticket(){
        
        if (!Auth::check()):
			return redirect('/auth/login');
		endif;
        
        $model_consultorio_tickets = new Consultorio();
        $servicios = $model_consultorio_tickets->ShowConsultoriosTicketsAll();

        return $servicios;
    }

    public function actualizar_estado($id_corredor_detalle, $estado){
        if (!Auth::check()):
			return redirect('/auth/login');
		endif;
        $corredor_detalle = CorredoresDetalle::where('id','=',$id_corredor_detalle)->first();
        $corredor_detalle->estado = $estado;
        $corredor_detalle->save();
        return "Y";
    }

    public function liberar_modulo($id_corredores_detalle)
    {
        if (!Auth::check()):
			return redirect('/auth/login');
		endif;
        $corredores_detalle = CorredoresDetalle::where('id','=',$id_corredores_detalle)->first();
        $id_user = $corredores_detalle->updated_us;
        $user = User::where('id','=',$id_user)->first();
        $limpiarLlamadoTicket = new CorredoresDetalle();
        $user->id_corredores_detalle = null;
        $user->id_ventanilla = null;
        $corredores_detalle->updated_us = null;
        $corredores_detalle->updated_at = null;
        $user->save();
        $corredores_detalle->save();
        $limpiarLlamadoTicket->limpiarLlamadoTicketPorId($id_user);
        Session::flash('flash_message', 'Liberado correctamente');
        return "Y";

    }
}
