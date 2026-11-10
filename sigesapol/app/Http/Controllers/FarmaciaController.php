<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use App\Models\Farmacia;
use App\Models\Establecimiento;
use App\Http\Requests;
use App\Models\Access\User\User;
use App\Http\Controllers\Controller;
use App\Models\Consultorio;
use App\Models\TipoAtencione;
use App\Models\Farmacia_servicio;
//use App\Models\Farmacia_tipo_atencione;
use Illuminate\Support\Facades\Route;
use App\Models\Genera_correlativonea;
use Auth;

// Cristiam 

class FarmaciaController extends Controller
{
    
    function __construct()
    {
        $this->middleware('auth'); 
    }
    
    public function update_datos_user(Request $request) {        
        $update = User::find($request->id);
        $update->dni=$request->dni;
        $update->name=$request->name;
        $update->paterno=$request->paterno;
        $update->materno=$request->materno;
        $update->email=$request->email;
        $update->celular=$request->celular;
        $update->save();
        return redirect('dashboard')->withFlashSuccess("Se actualizaron correctamente los datos");
    }
    
    public function editar_mis_datos($id){
        $usuario=User::where('id','=',$id)->first();
        return view('frontend.farmacias.edit_user',compact('usuario'));
    }


    public function all_usuarios() {
		
		if (!Auth::check()):
			return redirect('/auth/login');
		endif;
		
        $model= new Farmacia;
        $farmacias=$model->SoloFarmacias();
        return view('frontend.direoss.stocks.reporte_usuarios',compact('farmacias'));
    }
    
    public function usuarios_por_ipress(Request $request) {
        $model= new Farmacia;
        $usuarios=$model->UsuariosByFarmacia($request->id_farmacia);
		$farmacia = "";
		if($request->id_farmacia > 0):
        	$farmacia=$model->GetFarmaByIdfarma($request->id_farmacia);
		endif;
        return view('frontend.direoss.stocks.usuarios_por_farmacia',compact('usuarios','farmacia'));
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;
		
        $model= new Farmacia;
        $farmacias=$model->ShowAll();
		$ruta = Route::getCurrentRoute()->getPath();
		$menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id,$ruta);
		$permiso = $menu[0];
		if($permiso->lectura == "")return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        return view('frontend.farmacias.all',['farmacias' => $farmacias,'permiso'=>$permiso]);
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

        $model = new Establecimiento();
        $establecimientos = $model->ShowAll();
        return view('frontend.farmacias.create', ['establecimientos' => $establecimientos]);
    }

    public function create_farmacia_servicios($id_farmacia)
    {
        $model= new Consultorio();
        $consultorios=$model->ShowConsultorios();
        return view('frontend.farmacias.create_farmacia_servicios',['consultorios' => $consultorios],['id_farmacia' => $id_farmacia]);
    }
    
    public function view_farmacia_servicios($id_farmacia)
    {
        $model= new Consultorio();
        $servicios=$model->ShowServiciosByFarmacia($id_farmacia);
        //var_dump($servicios);exit();
        return view('frontend.farmacias.view_farmacia_servicios',['servicios' => $servicios]);
    }
	
	public function obtener_farmacia_por_id_establecimiento($id_establecimiento){
	
		$model = new Farmacia;
		$farmacia = $model->GetFarmaciaByIdEstablecimiento($id_establecimiento);
        return $farmacia;
		
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

        $modelIpress = new Establecimiento();
        $getIpress = $modelIpress->GetById($request->id_establecimiento);
        $nombreIpress = $getIpress[0]->nombre;
        $nombre_farmacia_creada = trim($request->nombre) . " del " . $nombreIpress;

        //$existe=  Farmacia::where('nombre','=',$nombre_farmacia_creada)->first();
        $model_farmacia = new Farmacia;
        $existe = $model_farmacia->ExisteFarmacia($nombre_farmacia_creada, $request->id_establecimiento);
        //var_dump($existe); exit;
        //if(count($existe)>0){
        if ($existe) {
            //return redirect('/all_farmacias')->withFlashSuccess("La ".$nombre_farmacia_creada." ya se encuentra registrada.");
            return redirect('/all_farmacias')
                            ->withInput()
                            ->withErrors(array('No se ha podido registrar, Centro de atención ya existe'));
        } else {
            $farmacia = new Farmacia();
            $farmacia->id_establecimiento = $request->id_establecimiento;
            $farmacia->nombre = $request->nombre . " del " . $nombreIpress;
            $farmacia->rango_registro = '30';
            $farmacia->rango_anulado = '7';
            $farmacia->modo_validacion = '3';
            $farmacia->flag_receta_dias = '1';
			if($request->almacen==1)
                $farmacia->almacen = $request->almacen;            
            $farmacia->save();
            
            $nombrefar = $request->nombre . " del " . $nombreIpress;
            
            $idfarmacian = $model_farmacia->GetIdFarmadByNomFarma($nombrefar);
            $id_farmacia = $idfarmacian->id;
            
            $model_receta_vale = new \App\Models\Receta_vale;
            $nea = new Genera_correlativonea();
            $nea->id_farmacia = $id_farmacia;
            $nea->anio_nea = $model_receta_vale->getAnoServidor();
            $nea->nro_nea = 0;
            $nea->estado = 1;
            $nea->id_user_created = Auth::user()->id;
            $nea->save();
            
            //return redirect('/all_farmacias')->withFlashSuccess("Se registro correctamente el laboratorio");
            Session::flash('flash_message', 'Registro exitoso');
            return redirect('/all_farmacias');
        }
    }

    public function store_farmacia_servicios(Request $request)
    {
        /*
        $modelIpress= new Establecimiento();
        $getIpress=$modelIpress->GetById($request->id_establecimiento);
        $nombreIpress=$getIpress[0]->nombre;
        $nombre_farmacia_creada=trim($request->nombre)." del ".$nombreIpress;            
        $existe=  Farmacia::where('nombre','=',$nombre_farmacia_creada)->first();
        if(count($existe)>0){
            return redirect('/all_farmacias')->withFlashSuccess("La ".$nombre_farmacia_creada." ya se encuentra registrada.");
        }else{        
            $farmacia= new Farmacia();
            $farmacia->id_establecimiento=$request->id_establecimiento;
            $farmacia->save();
            return redirect('/all_farmacias')->withFlashSuccess("Se registro correctamente el laboratorio");
        }
        */
        //var_dump($request->id_farmacia."|".$request->id_servicio);exit();
        
        $farmacia_servicio= new Farmacia_servicio();
        $farmacia_servicio->id_farmacia=$request->id_farmacia;
        $farmacia_servicio->id_servicio=$request->id_servicio;
        $farmacia_servicio->save();
        return redirect('/all_farmacias')->withFlashSuccess("Se registro correctamente el laboratorio");
          
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
		
        $model= new Establecimiento();
        $establecimientos=$model->ShowAll();
        $farmacias = Farmacia::findOrFail($id);
        return view('frontend.farmacias.edit', ['farmacias' => $farmacias],['establecimientos' => $establecimientos]);
    }
	
	public function edit_config($id)
    {
		if (!Auth::check()):
			return redirect('/auth/login');
		endif;
		
        $model= new Establecimiento();
        $establecimientos=$model->ShowAll();
        $farmacias = Farmacia::findOrFail($id);
		$model_tipo_atencion= new TipoAtencione();
        $tipo_atencion = $model_tipo_atencion->ShowTipoAtencionAll();
        //$model_farmacia_tipo_atencion= new Farmacia_tipo_atencione();
        //$farmacia_tipo_atencion = $model_farmacia_tipo_atencion->GetFarmaciaTipoAtencionByIdFarmacia($id);
        $farmacia_tipo_atencion = $farmacias->GetFarmaciaTipoAtencionByIdFarmacia($id);

        //print_r($farmacia_tipo_atencion);exit();
        return view('frontend.farmacias.edit_config', compact('farmacias', 'farmacia_tipo_atencion', 'establecimientos', 'tipo_atencion'));

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
	
        $nombre=trim($request->nombre);
        $farmacias = Farmacia::find($request->id);
		$nombre_farmacia_inicial = trim($farmacias->nombre);
		
        $farmacias->id_establecimiento=$request->id_establecimiento;
        $farmacias->nombre=trim($request->nombre);
		if($request->almacen==1)
            $farmacias->almacen = $request->almacen;            
        else
            $farmacias->almacen = 0;

        if($request->almacen_ipress==1)
            $farmacias->almacen_ipress = $request->almacen_ipress;            
        else
            $farmacias->almacen_ipress = 0;
        //$existe = $farmacias->ExisteFarmacia($nombre, 0);
		
		$model_farmacia=new Farmacia;
		$existe = $model_farmacia->ExisteFarmacia($nombre, $request->id_establecimiento, $request->id);
        if ($existe) {
            return redirect('/all_farmacias')
                            ->withInput()
                            ->withErrors(array('No se ha podido actualizar, Centro de atención ya existe'));
        }else{
        	$farmacias->save();
			DB::table('kardexes')->where('origen',$nombre_farmacia_inicial)->update(array('origen' => $nombre));
			DB::table('kardexes')->where('destino',$nombre_farmacia_inicial)->update(array('destino' => $nombre));
			DB::table('kardexes')->where('nombre_farmacia',$nombre_farmacia_inicial)->update(array('nombre_farmacia' => $nombre));
		}
        return redirect('/all_farmacias')->withFlashSuccess("Se actualizo correctamente");
    }
	
	public function update_config___(Request $request){
	
        $farmacias = Farmacia::find($request->id);
        $farmacias->rango_registro=$request->rango_registro;
        $farmacias->rango_anulado=$request->rango_anulado;
		$farmacias->modo_validacion=$request->modo_validacion;
		$farmacias->modo_registro=$request->modo_registro;
		$farmacias->flag_receta_dias=(isset($request->flag_receta_dias) && $request->flag_receta_dias > 0)?$request->flag_receta_dias:null;
        if($request->id_tiporeceta=='')
		    $farmacias->id_tiporeceta=1;
        else
            $farmacias->id_tiporeceta=$request->id_tiporeceta;
        
        if($request->id_tiporeceta2!='')
            $farmacias->id_tiporeceta2=$request->id_tiporeceta2;

        if($request->id_tiporeceta3!='')
            $farmacias->id_tiporeceta3=$request->id_tiporeceta3;

        if($request->id_nroatenciones!='')
            $farmacias->nro_atenciones=$request->id_nroatenciones;

        if($request->id_nroatenciones2!='')
            $farmacias->nro_atenciones2=$request->id_nroatenciones2;

        if($request->id_nroatenciones3!='')
            $farmacias->nro_atenciones3=$request->id_nroatenciones3;

        if($request->flag_pecosa=='')
		    $farmacias->flag_pecosa=1;
        else
            $farmacias->flag_pecosa=$request->flag_pecosa;

        
		$farmacias->save();

        return redirect('/all_farmacias')->withFlashSuccess("Se actualizo correctamente");
    }

	public function update_config(Request $request){
	
        $farmacias = Farmacia::find($request->id);
        $farmacias->rango_registro=$request->rango_registro;
        $farmacias->rango_anulado=$request->rango_anulado;
		$farmacias->modo_validacion=$request->modo_validacion;
		$farmacias->modo_registro=$request->modo_registro;
		$farmacias->flag_receta_dias=(isset($request->flag_receta_dias) && $request->flag_receta_dias > 0)?$request->flag_receta_dias:null;
		$id_farmacia = $request->id;
		
		if($request->flag_pecosa=='')
            $farmacias->flag_pecosa=1;
        else
            $farmacias->flag_pecosa=$request->flag_pecosa;
			
		$farmacias->save();

		//-----Registro de la tabla farmacia_tipo_atencione ------------------------
        //$model_farmacia_tipo_atencion= new Farmacia_tipo_atencione();
        $model_farmacia_tipo_atencion= new Farmacia();

        if($request->id_tipo_atencion){
            $p_tipo ="";
            $p_tipo.="{";
            foreach ($request->id_tipo_atencion as $key => $value):
                $p_tipo.="{";
                $p_tipo.=$request->id_tipo_atencion[$key];
                $p_tipo.="},";
            endforeach;
            $p_tipo= substr($p_tipo,0,-1);
            $p_tipo.="}";
        }else{
                $p_tipo ="";
                $p_tipo.="{";
                //$p_tipo.="{";
                //$p_tipo.="},";
                //$p_tipo= substr($p_tipo,0,-1);
                $p_tipo.="}";
                } 

        $ok = $model_farmacia_tipo_atencion->postRegistrarTipoAtencion("c", $id_farmacia, Auth::user()->id, $p_tipo);
        return redirect('/all_farmacias')->withFlashSuccess("Se actualizo correctamente");
    }
	
#nro_atenciones
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
    
    public function getnombre($cmbest) {
        $model = new Establecimiento();
        $nombre = $model->GetById($cmbest);
        //var_dump($nombre); exit;
        return $nombre;
    }
	
	public function eliminar($id){
		$update=Farmacia::where('id','=',$id)->first();
		$update->estado=3;
		$update->save();
		Session::flash('flash_message', 'Se elimino correctamente');
	}
	
    public function obtener_establecimiento_cita($parentesco,$ubigeo) {
      if (!Auth::check()):
        return [];
      endif;
      $model_usuario = new \App\Models\User();
      $model_establecimiento = new Establecimiento();

      $id_user = Auth::user()->id;
      $usuario_actual = $model_usuario->GetById($id_user);
      if ($usuario_actual->flagestablecimientos == 0) {
        $result = $model_establecimiento->GetByIdEstablecimiento($usuario_actual->id_establecimiento);
        if(!empty($result)) {
          $establecimiento[] = $result;
        }
      } else { 
        //$establecimiento = $model_establecimiento->ShowEstablecimientoByUbigeo($ubigeo);
        $establecimiento = $model_establecimiento->ShowAllEstablecimientos();
      }
      return $establecimiento;
    }

	  public function obtener_establecimiento($parentesco,$ubigeo) {
	
		  $model_establecimiento = new Establecimiento();
      //$establecimiento = $model_establecimiento->ShowEstablecimientoByUbigeo($ubigeo);
		  $establecimiento = $model_establecimiento->ShowAllEstablecimientos(76);
      return $establecimiento;
    }
	
    public function obtener_establecimiento_nivel($nivel) {
    
        $model_establecimiento = new Establecimiento();        
        $establecimiento = $model_establecimiento->ShowEstablecimientoNivel($nivel);
        return $establecimiento;
    }

    public function obtener_farmacia_por_id_establecimiento_atencion($id_establecimiento,$id_tipo_atencion){
    
        $model = new Farmacia;
        $farmacia = $model->GetFarmaciaByIdEstablecimientoAndIdatencion($id_establecimiento,$id_tipo_atencion);
        return $farmacia;
        
    }
	
}
