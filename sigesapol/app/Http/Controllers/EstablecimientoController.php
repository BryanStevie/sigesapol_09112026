<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Establecimiento;
use Session;
use DB;
use App;
use App\Models\Comisaria;
use App\Models\Departamento;
use App\Models\Distrito;
use App\Models\Consultorio;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

class EstablecimientoController extends Controller
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
    
    public function update_establecimiento(Request $request)
    {            
			if (!Auth::check()):
        return redirect('/auth/login');
      endif;
		
      $id_user = Auth::user()->id;
      $establecimiento = Establecimiento::where('id', '=', intval($request->id_establecimiento))->first();

      if (empty($establecimiento)) {
        return redirect('/listar_establecimientos');  
      }

      $intervalo_cita = $request->intervalo_cita;
      if ($intervalo_cita < 0 || $intervalo_cita > 9) {
        $intervalo_cita = 4;
      }

      $establecimiento->nombre = $request->nombre_establecimiento;
      $establecimiento->codigo = $request->codigo;
      $establecimiento->nivel = $request->id_nivel;
      $establecimiento->red_region = $request->red;
      $establecimiento->direccion_establecimiento = $request->direccion_establecimiento;
      $establecimiento->ubigeo = $request->ubigeo;
      $establecimiento->intervalo_cita = $request->intervalo_cita;
      $establecimiento->flag_firma_electronica = !empty($request->flag_firma_electronica) && $request->flag_firma_electronica == 1 ? true : false;
      $establecimiento->save();
      
      Session::flash('flash_message', 'Actualización exitosa');
      return redirect('/listar_establecimientos');
    }
    
    public function editar_establecimiento($id) 
    {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        
        $establecimiento = DB::table('establecimientos')
          ->where('establecimientos.id', intval($id))
          ->select('establecimientos.*')
          ->first();

        if(empty($establecimiento)) {
          return redirect('listar_establecimientos');
        }
        
        return view('frontend.establecimientos.editar_establecimiento', compact('establecimiento'));
    }

    
    public function formulario_establecimiento() {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        return view('frontend.establecimientos.create_establecimiento');
    }

    
    public function index($id_user = 0) {

        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;

        $id_user = Auth::user()->id;        
        $modelestablecimiento = new Establecimiento();        
        $establecimientos = $modelestablecimiento->ShowAll();
        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1)
            ;$ruta = $ruta[0];
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];


        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

        return view('frontend.establecimientos.all', ['establecimientos' => $establecimientos, 'permiso' => $permiso]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id_user)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    


    public function store(Request $request) {        
        $modelestablecimiento= new Establecimiento();
        $establecimiento_registrado = $modelestablecimiento->establecimiento_yaregistrado($request->codigo);
        if(count($establecimiento_registrado)>0){
            $dato = "El Establecimiento <b>".$establecimiento_registrado->nombre." </b> ya se encuentra registrado, en estado ";
            $dato.= $establecimiento_registrado->id_estado_reg == "1"? "<b>ACTIVO</b>":"<b>INACTIVO</b>";
            return redirect('/listar_establecimientos/' . $request->id_user)->withInput()->withErrors(array($dato));
        } else {
            
            $establecimiento = new Establecimiento;
            $establecimiento->nombre = $request->nombre_establecimiento;
            $establecimiento->codigo = $request->codigo;
            $establecimiento->nivel = $request->id_nivel;
            $establecimiento->red_region = $request->red;
            $establecimiento->ubigeo = $request->ubigeo;
            $establecimiento->id_estado_reg = 1;
            $establecimiento->direccion_establecimiento = $request->direccion_establecimiento;
            $establecimiento->flag_firma_electronica = !empty($request->flag_firma_electronica) && $request->flag_firma_electronica == 1 ? true : false;
            $establecimiento->save();
            $id_establecimiento = $establecimiento->id;
            //-----Registro de la tabla Medico_consultorio ------------------------
            Session::flash('flash_message', 'Registro exitoso');
            return redirect('/listar_establecimientos/');
        }
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
    
	
	public function desactivar($id, $estado){
		
		if (!Auth::check()):
            return redirect('/auth/login');
        endif;
		
            $id_user = Auth::user()->id;
                if($estado == "1"){
                    $update=Establecimiento::where('id','=',$id)->first();
                    $update->id_estado_reg=0;
                    $update->save();
                    
                    Session::flash('flash_message', 'Se desactivo correctamente');
                } else {
                    $update=Establecimiento::where('id','=',$id)->first();
                    $update->id_estado_reg=1;
                    $update->save();
                    Session::flash('flash_message', 'Se activo correctamente');
                }
	}
    public function obtener_departamento_by_unidad_policial($id_establecimiento)
    {
        $establecimiento = Establecimiento::where('id', '=', intval($id_establecimiento))->first();
        $departamento_split = str_split($establecimiento->ubigeo, 2);
        //dd($departamento_split[0]);

        ///departamento
        $departamento = Departamento::where('codigo', '=', ($departamento_split[0]))->first();
        //dd($departamento);
        return $departamento;
    }
	
    public function obtener_ubigeo_by_comisaria($id_comisaria)
    {
        $comisaria = Comisaria::where('id', '=', $id_comisaria)->first();
        $distrito_ubig= new Distrito();
        $ubigeo_comisaria = $distrito_ubig->getObtenerUbigeo_by_id_distrito($comisaria->id_distrito);  
        return $ubigeo_comisaria;
    }

    public function asignar_servicios($id_establecimiento)
    {
        //$model_consultorio = new Consultorio;
        $model_establecimiento = new Establecimiento;
        //$consultorios = $model_consultorio->ShowAllConsultorios($id_establecimiento);
        
        $consultorios_seleccionados = $model_establecimiento->ShowConsultoriosEstablecimientos($id_establecimiento);
        $establecimiento = Establecimiento::where('id', '=', intval($id_establecimiento))->first();

        //si encontramos el establecimiento buscado
        if (empty($establecimiento)) {
            Session::flash('Establecimientos no encontrado');
            return redirect('/listar_establecimientos/');
        }

        return view('frontend.establecimientos.asignar_servicio',compact('establecimiento','id_establecimiento','consultorios_seleccionados'));
    }



    public function store_consultorio_establecimiento_can(Request $request)
    {
        if (!Auth::check()) :
          return redirect('/auth/login');
        endif;
        
        $id_user = Auth::user()->id;
        $id_establecimiento = $request->id_establecimiento;
        
        /***************************************************************************/
        $model_consultorio = new \App\Models\Establecimiento;
        
        if (isset($request->id_cons_est)) :
          $p_consultorios = "";
          $p_consultorios .= "{";
          foreach ($request->id_cons_est as $key => $value) :
            if ($request->id_cons_est[$key] != "") {
              $p_consultorios .= "{";
              $p_consultorios .= $request->id_cons_est[$key];
              $p_consultorios .= "},";
            }
          endforeach;
          if (strlen($p_consultorios) > 1) $p_consultorios = substr($p_consultorios, 0, -1);
          $p_consultorios .= "}"; 

          if($request->id_cons_est[0]!=""):
            $id_ok = $model_consultorio->RegistrarConsultorioEstablecimiento($id_user, $id_establecimiento, $p_consultorios);
          endif;
        endif;
      
        if ($id_ok == 0) {
          Session::flash('flash_message', 'No se pudo registrar los Consultorios a este establecimiento, hubo un problema con la conexión de internet de su establecimiento PNP, vuelva a intentarlo nuevamente');
          return redirect('/listar_establecimientos');
        } else {
          Session::flash('flash_message', 'Registro exitoso');
          return redirect('/establecimiento/asignar_servicios/' . $id_establecimiento);
        }
    }

    public function listar_consultorio_establecimiento(Request $request)
    {

        $model = new Establecimiento();
        $p[] = $request->id_establecimiento;
        $p[] = $request->NumeroPagina;
        $p[] = $request->NumeroRegistros;
        $data = $model->ShowAllConsultoriosEstablecimientos($p);
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
	
    public function servicio_establecimiento_validar_existe($id_servicio, $id_establecimiento)
    {
      $existe = DB::table('consultorio_establecimientos')
        ->where('id_consultorio', $id_servicio)
        ->where('id_establecimiento', $id_establecimiento)
        ->exists();

      return json_encode($existe);
    }

    public function servicio_establecimiento_activar($id)
    {
      if (!Auth::check()) :
        return redirect('/auth/login');
      endif;

      $id_user = Auth::user()->id;
      $actualizado = DB::table('consultorio_establecimientos')
        ->where('id', $id)
        ->update([
          'estado' => 1, 
          'id_user_update' => $id_user, 
          'updated_at' => Carbon::now()
        ]);

      if ($actualizado > 0) {
        Session::flash('flash_message', 'Se activó correctamente el registro.');
      } else {
        Session::flash('flash_warning', 'No se pudo activar, intente de nuevo.');
      }
    }

    public function servicio_establecimiento_desactivar($id)
    {
      if (!Auth::check()) :
        return redirect('/auth/login');
      endif;

      $id_user = Auth::user()->id;
      $actualizado = DB::table('consultorio_establecimientos')
        ->where('id', $id)
        ->update([
          'estado' => 0, 
          'id_user_update' => $id_user, 
          'updated_at' => Carbon::now()
        ]);

      if ($actualizado > 0) {
        Session::flash('flash_message', 'Se desactivó correctamente el registro.');
      } else {
        Session::flash('flash_warning', 'No se pudo desactivar, intente de nuevo.');
      }
    }

    public function servicio_establecimiento_update(Request $request) 
    {
      if (!Auth::check()) :
        return redirect('/auth/login');
      endif;

      $id_user = Auth::user()->id;
      $has_guardia = isset($request->has_guardia) ? 1 : 0;

      $actualizado = DB::table('consultorio_establecimientos')
        ->where('id', $request->id_servicio_establecimiento)
        ->update([
          'id_establecimiento' => $request->id_establecimiento,
          'has_guardia' => $has_guardia,
          'id_user_update' => $id_user,  
          'updated_at' => Carbon::now()
        ]);

      if ($actualizado > 0) {
        Session::flash('flash_message', 'Se actualizó correctamente el registro.');
      } else {
        Session::flash('flash_warning', 'No se pudo actualizar, intente de nuevo.');
      }

      return redirect('/servicio_establecimiento/editar/' . $request->id_servicio_establecimiento);
    }

    public function servicio_establecimiento_store(Request $request) 
    {
      if (!Auth::check()) :
        return redirect('/auth/login');
      endif;

      $id_user = Auth::user()->id;
      $has_guardia = isset($request->has_guardia) ? 1 : 0;

      $existe = DB::table('consultorio_establecimientos')
          ->where('id_consultorio', $request->id_servicio)
          ->where('id_establecimiento', $request->id_establecimiento)
          ->exists();

      $id_nuevo_registro = 0;
      if (!$existe) {
        $id_nuevo_registro = DB::table('consultorio_establecimientos')->insertGetId([
          'id_establecimiento' => $request->id_establecimiento,
          'id_consultorio' => $request->id_servicio,
          'estado' => 1,
          'has_guardia' => $has_guardia,
          'id_user_created' => $id_user,
          'created_at' => Carbon::now()
        ]);

        if ($id_nuevo_registro > 0) {
          Session::flash('flash_message', 'El registro se realizó correctamente.');
        }

      } else {
        Session::flash('flash_warning', 'No se pudo realizar el registro porque el establecimiento ya esta registrado.');
      }

      return redirect('/servicio_establecimiento/' . $request->id_servicio);
    }
    
    public function obtenerSubUnidadXUnidad ($id_unidad) {
      $Subunidad = new Consultorio();
      $Subunidades = $Subunidad->getDepartamentosPorDivision($id_unidad);
      return $Subunidades;
    }
}
