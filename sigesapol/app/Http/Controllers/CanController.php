<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Producto;
use App\Models\Establecimiento;
use App\Models\Consultorio;
use App\Models\Permiso;
use App\Models\Can;
use App\Models\CanQuimico;
use App\Models\CanMonitoreo;
use App\Models\CanEstablecimiento;
use App\Models\CanResponsable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Auth;
use Session;
use DB;
use App;
use App\Models\Menu;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
//use Illuminate\Support\Facades\Auth;
//use Response;
//use Excel;
//use PHPExcel_Worksheet_Drawing;

class CanController extends Controller
{
    /*Visualizar todos los CANs*/
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
        $permiso = $menu[0];
        //print_r($permiso);
        if ($permiso->lectura == "")
          return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

        if (Auth::user()->flag_can == 1){
            if (Auth::user()->flagestablecimientos == 1){
                $model_can = new Can();
                $cans = $model_can->ShowAll();
                return view('frontend.cans.admin.all', ['cans' => $cans, 'permiso' => $permiso]);
            
            }else{
                switch (Auth::user()->tipo_registro) {
                    case '1': //medico
                        $model_medico_can = new CanMedico();
                        $cans = $model_medico_can->ShowAll(Auth::user()->id_establecimiento);
                        return view('frontend.cans.site.all_servicio', ['cans' => $cans, 'permiso' => $permiso]);
                        break;
                    
                    case '2': //Quimico
                        $model_quimico_can = new CanQuimico();
                        $cans = $model_quimico_can->ShowAll2(Auth::user()->id_establecimiento);// Auth::user()->id_establecimiento);
                        //dd($cans);
                        return view('frontend.cans.site.all', ['cans' => $cans, 'permiso' => $permiso]);
                        break;

                    case '3': //Jefe de Farmacia
                        return redirect('/dashboard')->withFlashDanger("Jefe de Farmacia");
                        break;
                    
                    default: //administrador
                        $model_can = new Can();
                        $cans = $model_can->ShowAll();
                        return view('frontend.cans.admin.all', ['cans' => $cans, 'permiso' => $permiso]);
                        break;
                }
            }
            
        }
        else
        {
            return redirect('/dashboard')->withFlashDanger("No tiene Permiso para ingresar a esta opcion, activese como usuario del can,  coordinar con el administrador del Sistema");
        }

    }

    public function create()
    {   
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;        
        if (Auth::user()->flag_can == 1){
            $id_user = Auth::user()->id;
            return view('frontend.cans.admin.create',compact('id_user'));
        }
        else
        {
            return redirect('/dashboard')->withFlashDanger("No tiene Permiso para ingresar a esta opcion, activese como usuario del can,  coordinar con el administrador del Sistema");
        }
    }

    public function store(Request $request) {
        $nombre = trim($request->nombre_can);
        $model_can = new Can;
        
        $existe = $model_can->ExisteCan($nombre);
        if ($existe) {
            return redirect('/all_cans')
                            ->withInput()
                            ->withErrors(array('No se ha podido registrar, CAN ya existe'));
        } else {
            $p_id = $model_can->RegistrarCAN(0,$nombre,$request->id_user,1);
            Session::flash('flash_message', 'Registro exitoso');
            return redirect('/all_cans');
        }
    }

    public function edit($id_can)
    {   
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;        
        if (Auth::user()->flag_can == 1){
            $id_user = Auth::user()->id;
            $model_can = new Can;
            $can = $model_can->NombreCan($id_can);
            return view('frontend.cans.admin.edit',compact('id_user','can'));
        }
        else
        {
            return redirect('/dashboard')->withFlashDanger("No tiene Permiso para ingresar a esta opcion, activese como usuario del can,  coordinar con el administrador del Sistema");
        }
    }

    public function update(Request $request) {
        $id_can = $request->id_can;
        $nombre = trim($request->nombre_can);
        $model_can = new Can;
        
        $existe = $model_can->ExisteCan($nombre);
        if ($existe) {
            return redirect('/all_cans')
                            ->withInput()
                            ->withErrors(array('No se ha podido actualizar, CAN ya existe con ese nombre'));
        } else {
            $p_id = $model_can->RegistrarCAN($id_can, $nombre,$request->id_user,2);
            Session::flash('flash_message', 'Registro exitoso');
            return redirect('/all_cans');
        }
    }

    public function monitorear_can($id_can) {

        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;

        if (Auth::user()->flag_can == 1){
            $model_can = new Can;
            $can = $model_can->NombreCan($id_can);
            return view('frontend.cans.admin.monitorear_can',compact('id_can','can'));
        }
        else
        {
            return redirect('/dashboard')->withFlashDanger("No tiene Permiso para ingresar a esta opcion, activese como usuario del can,  coordinar con el administrador del Sistema");
        }
    }

    public function monitorear_can_servicio($id_can,$id_establecimiento) {

        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;

        if (Auth::user()->flag_can == 1){
            $id_user = Auth::user()->id;
            $model_can = new Can;
            $can = $model_can->NombreCan($id_can);
            $model_est = new Establecimiento;
            $nombre_establecimiento = $model_est->GetNombre($id_establecimiento);
            
            return view('frontend.cans.admin.monitorear_can_servicio',compact('id_establecimiento','id_can','can','nombre_establecimiento'));
        }
        else
        {
            return redirect('/dashboard')->withFlashDanger("No tiene Permiso para ingresar a esta opcion, activese como usuario del can,  coordinar con el administrador del Sistema");
        }
    }

    public function listar_can_ajax(Request $request){
        $model_monitoreo = new CanMonitoreo();
        $p[]=$request->id_can;
        $p[]=$request->id_establecimiento;
        $p[]=$request->accion;
        $p[]=$request->NumeroPagina;
        $p[]=$request->NumeroRegistros;
        $data = $model_monitoreo->listar_can_servicio_ajax($p);
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

    public function listar_productos_ajax(Request $request){
        $model_producto_can = new CanQuimico();
        $p[]=$request->id_can;
        $p[]=$request->id_establecimiento;
        $p[]=$request->tipo;
        $p[]=$request->NumeroPagina;
        $p[]=$request->NumeroRegistros;
        $data = $model_producto_can->listar_can_productos_ajax($p);
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

    public function activar_establecimiento_servicio($id,$id_can,$id_establecimiento,$id_servicio,$accion) {

        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;
        $servicio = "";
        if (Auth::user()->flag_can == 1){
            $id_user = Auth::user()->id;
            if($id_servicio!=0):
                $model_consultorio = new Consultorio;
                $servicio = $model_consultorio->GetNombreById($id_servicio);
                $retorno = 1 ;
            else:
                $retorno = 2 ;
            endif;
            $model_monitoreo = new CanMonitoreo;
            $activar = $model_monitoreo->BuscarEstablecimientoServicioCan($id);
            $model_est = new Establecimiento;
            $nombre_establecimiento = $model_est->GetNombre($id_establecimiento);
            if($accion==1):
                return view('frontend.cans.admin.modal_activar',compact('id_establecimiento','id_can','id_servicio','activar','servicio','nombre_establecimiento','id_user','retorno','id','accion'));
            else:
                return view('frontend.cans.admin.modal_habilitar',compact('id_establecimiento','id_can','id_servicio','activar','servicio','nombre_establecimiento','id_user','retorno','id','accion'));
            endif;
        }
    }

    public function update_activar(Request $request) {
        $id = $request->id;
        $medicamento = $request->medicamento;
        $dispositivo = $request->dispositivo;
        $id_user = Auth::user()->id;
        $accion = $request->accion;

        $model_monitoreo = new CanMonitoreo;
        $p_id = $model_monitoreo->ActivarCan($id, $medicamento, $dispositivo,$id_user,$accion);
        
        return response()->json([
            "mensaje" => "se cambio correctamente"
        ]);
    }

    public function activar_todos_establecimiento_servicio($id_can,$id_establecimiento) {

        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;
        
        if (Auth::user()->flag_can == 1){
            $id_user = Auth::user()->id;
            $model_est = new Establecimiento;
            $nombre_establecimiento = $model_est->GetNombre($id_establecimiento);
            return view('frontend.cans.admin.modal_activar_todos',compact('id_establecimiento','id_can','nombre_establecimiento','id_user'));
            
        }
        else
        {
            return redirect('/dashboard')->withFlashDanger("No tiene Permiso para ingresar a esta opcion, activese como usuario del can,  coordinar con el administrador del Sistema");
        }
    }

    public function update_activar_todos(Request $request) {
        $id_establecimiento = $request->id_establecimiento;
        $medicamento = $request->medicamento;
        $dispositivo = $request->dispositivo;
        $id_can = $request->id_can;
        $id_user = Auth::user()->id;
        $model_monitoreo = new CanMonitoreo;
        $p_id = $model_monitoreo->ActivarTodosCan($id_establecimiento, $id_can, $medicamento, $dispositivo,$id_user);
        
        return response()->json([
            "mensaje" => "se actualizo correctamente"
        ]);
    }

    public function ampliar_tiempo_establecimiento_servicio($id,$id_can,$id_establecimiento,$id_servicio) {
        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;
        $servicio="";
        if (Auth::user()->flag_can == 1){
            $id_user = Auth::user()->id;
            if($id_servicio!=0):
                $model_consultorio = new Consultorio;
                $servicio = $model_consultorio->GetNombreById($id_servicio);
            endif;
            $model_monitoreo = new CanMonitoreo;
            $activar = $model_monitoreo->BuscarEstablecimientoServicioCan($id);
            $model_est = new Establecimiento;
            $nombre_establecimiento = $model_est->GetNombre($id_establecimiento);

            return view('frontend.cans.admin.modal_ampliar_tiempo',compact('id_establecimiento','id_can','activar','nombre_establecimiento','id_user','id_servicio','servicio','id'));
            
        }
        else
        {
            return redirect('/dashboard')->withFlashDanger("No tiene Permiso para ingresar a esta opcion, activese como usuario del can,  coordinar con el administrador del Sistema");
        }
    }

    public function update_tiempo(Request $request)
    {
        $id = $request->id;
        $id_establecimiento = $request->id_establecimiento;
        $id_servicio = $request->id_servicio;
        $id_can = $request->id_can;
        $id_tiempo = $request->id_tiempo;
        $id_hora = $request->id_hora;
        $accion = $request->accion;
        $id_user = Auth::user()->id;
        $tiempo_porroga = new Carbon;
        $fin_first_login=$tiempo_porroga->addDays($id_tiempo);
        $fin_first_login->modify('+'.$id_hora.'hour');

        $model_monitoreo = new CanMonitoreo;
        $p_id = $model_monitoreo->AmpliarTiempoCan($id,$id_establecimiento, $id_can, $id_servicio, $id_user, $fin_first_login, $accion);
        
        return response()->json([
            "mensaje" => "se actualizo correctamente"
        ]);
    }

    public function ampliar_tiempo_total_establecimiento($id_can,$id_establecimiento) {
        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;
        $servicio="";
        if (Auth::user()->flag_can == 1){
            $id_user = Auth::user()->id;
            $model_est = new Establecimiento;
            $nombre_establecimiento = $model_est->GetNombre($id_establecimiento);

            return view('frontend.cans.admin.modal_ampliar_tiempo_total',compact('id_establecimiento','id_can','nombre_establecimiento','id_user'));
            
        }
        else
        {
            return redirect('/dashboard')->withFlashDanger("No tiene Permiso para ingresar a esta opcion, activese como usuario del can,  coordinar con el administrador del Sistema");
        }
    }

    public function productos_servicios_can($id_can, $id_establecimiento, $id_servicio, $tipo_producto)
    {       
        
        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;

        $model_can_productos = new CanEstablecimiento();
        
        if (Auth::user()->flag_can == 1){
           if($id_servicio!=0):
                $model_consultorio = new Consultorio;
                $servicio = $model_consultorio->GetNombreById($id_servicio);
            endif;
            $model_est = new Establecimiento;
            $nombre_establecimiento = $model_est->GetNombre($id_establecimiento);
            
            if($tipo_producto==1):
                $estimacions = $model_can_productos->GetMedicamentosServicios($id_can,$id_establecimiento,$id_servicio);
                //$num_estimaciones = $model_can_productos->GetCantidadMedicamento($can_id,$establecimiento_id);
                //$descripcion_tipo='Medicamentos';
                //$cerrado=$stock_cerrado[0]->medicamento_cerrado;

                //return view('frontend.cans.admin.productos.med_servicios',compact('id_establecimiento','id_can','id_servicio','monitoreo_servicio','servicio','nombre_establecimiento','can_servicio'));
            else:
                $data = $model_can_productos->GetDispositivos($can_id,$establecimiento_id);
                $num_estimaciones = $model_can_productos->GetCantidadDispositivo($can_id,$establecimiento_id);
                $cerrado=$stock_cerrado[0]->dispositivo_cerrado;
                $descripcion_tipo='Dispositivos';

                $model_monitoreo = new CanMonitoreo();
                $p[]=$id_can;
                $p[]=$id_establecimiento;
                $p[]=$id_servicio;
                $p[]=$tipo_producto;
                $p[]=1;
                $p[]=1000;
                $estimaciones_servicios = $model_monitoreo->listar_can_productos_establecimiento_ajax($p);
                
                return view('frontend.cans.admin.productos.disp_servicios',compact('id_establecimiento','id_can','id_servicio','monitoreo_servicio','servicio','nombre_establecimiento','can_servicio','estimaciones_servicios'));
            endif;
            
            $condicion_boton=2; //no ver
            $servicio_id_cero=0;
            $cerrado_stock=1;
            $actualizado = 1; //$stock_cerrado[0]->actualizacion;

            return view('frontend.cans.admin.productos.producto_establecimiento_servicio',compact('id_establecimiento','id_can','id_servicio','monitoreo_servicio','servicio','nombre_establecimiento','estimacions','cerrado_stock'));       
        }
        else
        {
            return redirect('/dashboard')->withFlashDanger("No tiene Permiso para ingresar a esta opcion, activese como usuario del can,  coordinar con el administrador del Sistema");
        }

        
    }


    public function productos_servicios_can2($id_can, $id_establecimiento, $id_servicio, $tipo_producto)
    {
        
        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;
        if (Auth::user()->flag_can == 1){
           if($id_servicio!=0):
                $model_consultorio = new Consultorio;
                $servicio = $model_consultorio->GetNombreById($id_servicio);
            endif;
            $model_est = new Establecimiento;
            $nombre_establecimiento = $model_est->GetNombre($id_establecimiento);
            
            if($tipo_producto==1):
                return view('frontend.cans.admin.productos.med_servicios',compact('id_establecimiento','id_can','id_servicio','monitoreo_servicio','servicio','nombre_establecimiento','can_servicio'));
            else:
                $model_monitoreo = new CanMonitoreo();
                $p[]=$id_can;
                $p[]=$id_establecimiento;
                $p[]=$id_servicio;
                $p[]=$tipo_producto;
                $p[]=1;
                $p[]=1000;
                $estimaciones_servicios = $model_monitoreo->listar_can_productos_establecimiento_ajax($p);
                
                return view('frontend.cans.admin.productos.disp_servicios',compact('id_establecimiento','id_can','id_servicio','monitoreo_servicio','servicio','nombre_establecimiento','can_servicio','estimaciones_servicios'));
            endif;       
        }
        else
        {
            return redirect('/dashboard')->withFlashDanger("No tiene Permiso para ingresar a esta opcion, activese como usuario del can,  coordinar con el administrador del Sistema");
        }

    }

    public function listar_can_productos_establecimiento_ajax(Request $request){
    
        $model_monitoreo = new CanMonitoreo();
        $p[]=$request->id_can;
        $p[]=$request->id_establecimiento;
        $p[]=$request->id_servicio;
        $p[]=$request->tipo_producto;
        $p[]=$request->NumeroPagina;
        $p[]=$request->NumeroRegistros;
        $data = $model_monitoreo->listar_can_productos_establecimiento_servicio_ajax($p);
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

    public function listar_archivos_can($can_id,$establecimiento_id)
    {
        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;

        $model_can = new Can;
        $cans = $model_can->BuscarCan($can_id);
        
        //Busco datos del establecimiento
        $establecimiento = Establecimiento::find($establecimiento_id);
        
        $nivel_id=$establecimiento->nivel;

        $monitoreo_informacion = new CanMonitoreo;

        $archivos= $monitoreo_informacion->GetArchivos($can_id, $establecimiento_id);
        $observaciones= $monitoreo_informacion->GetObservacion($can_id, $establecimiento_id);

        return view('frontend.cans.admin.listar_archivos_can')
                    ->with('can_id', $can_id)    
                    ->with('nivel_id', $nivel_id)                    
                    ->with('establecimiento_id', $establecimiento_id)  
                    ->with('nombre_establecimiento', $establecimiento->nombre)
                    ->with('observaciones', $observaciones)
                    ->with('archivos', $archivos);
    }

    public function listar_archivos_servicios_can($id_can, $id_establecimiento, $id_servicio, $accion)
    {
        
        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;

        if (Auth::user()->flag_can == 1){
           if($id_servicio!=0):
                $model_consultorio = new Consultorio;
                $servicio = $model_consultorio->GetNombreById($id_servicio);
            endif;
            $model_est = new Establecimiento;
            $nombre_establecimiento = $model_est->GetNombre($id_establecimiento);

            $model_archivo = new CanMonitoreo;
            $archivos = $model_archivo->ListarArchivosCan($id_can,$id_establecimiento,$id_servicio);
            $archivos = $model_archivo->ListarArchivosCan($id_can,$id_establecimiento,$id_servicio);
            $observaciones = $model_archivo->ListarArchivosCan($id_can,$id_establecimiento,$id_servicio);


            /*$observaciones= DB::table('observaciones')
                                ->where('establecimiento_id',$establecimiento_id)
                                ->where('can_id', $can_id)
                                ->where('estado',1)
                                ->get();*/
            
            if($accion==1):
                return view('frontend.cans.admin.listar_archivos_servicios_can',compact('id_establecimiento','id_can','id_servicio','monitoreo_servicio','servicio','nombre_establecimiento','can_servicio','observaciones','archivos'));
            else:
                return view('frontend.cans.admin.listar_archivos_establecimientos_can',compact('id_establecimiento','id_can','id_servicio','monitoreo_servicio','servicio','nombre_establecimiento','can_servicio','observaciones','archivos'));
            endif;       
        }
        else
        {
            return redirect('/dashboard')->withFlashDanger("No tiene Permiso para ingresar a esta opcion, activese como usuario del can,  coordinar con el administrador del Sistema");
        }

    }

    public function productos_establecimientos_can($can_id, $establecimiento_id, $tipo)
    {       
        
        $establecimiento = Establecimiento::find($establecimiento_id);
        $servicio_id=Auth::user()->id_consultorio;
        $nivel=$establecimiento->nivel;

        $model_can = new Can();
        $model_monitoreo = new CanMonitoreo();
        $model_can_productos = new CanEstablecimiento();

        $can = $model_can->BuscarCan($can_id);
        $stock_cerrado = $model_monitoreo->BuscarMonitoreo($can_id,$establecimiento_id);

        if($tipo==1){
            $data = $model_can_productos->GetMedicamentos($can_id,$establecimiento_id);
            $num_estimaciones = $model_can_productos->GetCantidadMedicamento($can_id,$establecimiento_id);
            $descripcion_tipo='Medicamentos';
            $cerrado=$stock_cerrado[0]->medicamento_cerrado;
        }else
            {   if ($tipo==2) {
                    $data = $model_can_productos->GetDispositivos($can_id,$establecimiento_id);
                    $num_estimaciones = $model_can_productos->GetCantidadDispositivo($can_id,$establecimiento_id);
                    $cerrado=$stock_cerrado[0]->dispositivo_cerrado;
                    $descripcion_tipo='Dispositivos';
                }
        }
        
            $condicion_boton=2; //no ver
            $servicio_id_cero=0;
            $cerrado_stock=1;
            $actualizado = 1; //$stock_cerrado[0]->actualizacion;
            
        return view('frontend.cans.admin.productos.producto_establecimiento')
            ->with('condicion_boton', $condicion_boton)
            ->with('servicio_id', $servicio_id_cero)
            ->with('estimacions', $data)
            ->with('cerrado', $cerrado)
            ->with('nivel', $nivel)
            ->with('cerrado_stock', $cerrado_stock)            
            ->with('num_estimaciones', $num_estimaciones)
            ->with('descripcion', $descripcion_tipo)
            ->with('establecimiento_id', $establecimiento_id)
            ->with('tipo', $tipo)
            ->with('actualizado', $actualizado)
            ->with('can_id', $can_id);
    }

    public function productos_establecimientos_can2($id_can, $id_establecimiento, $tipo_producto)
    {
        
        if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;
        if (Auth::user()->flag_can == 1){
            $model_est = new Establecimiento;
            $nombre_establecimiento = $model_est->GetNombre($id_establecimiento);
            
            if($tipo_producto==1):
                return view('frontend.cans.admin.productos.med_establecimiento',compact('id_establecimiento','id_can','id_servicio','monitoreo_servicio','nombre_establecimiento'));
            else:
                $model_monitoreo = new CanMonitoreo();
                $p[]=$id_can;
                $p[]=$id_establecimiento;
                $p[]=0;
                $p[]=$tipo_producto;
                $p[]=1;
                $p[]=1000;
                $estimaciones_servicios = $model_monitoreo->listar_can_productos_establecimiento_servicio_ajax($p);
                
                return view('frontend.cans.admin.productos.disp_establecimiento',compact('id_establecimiento','id_can','id_servicio','monitoreo_servicio','servicio','nombre_establecimiento','can_servicio','estimaciones_servicios'));
            endif;       
        }
        else
        {
            return redirect('/dashboard')->withFlashDanger("No tiene Permiso para ingresar a esta opcion, activese como usuario del can,  coordinar con el administrador del Sistema");
        }

    }

    public function cargar_productos($id_can, $tipo)
    {
        
        $model_monitoreo = new CanMonitoreo();
        $model_establecimiento = new Establecimiento();
        
        $id_establecimiento=Auth::user()->id_establecimiento;
        $id_servicio=Auth::user()->id_servicio;
        $id_user=Auth::user()->id;
        $establecimiento = $model_establecimiento->Get($id_establecimiento);
        $nivel=$establecimiento->nivel;
        
        //$can = Can::find($can_id);

        //$tiempo = $can->tiempo;
        //$modeluser = new User;

        //$nombre_servicio=Auth::user()->nombre_servicio;
        //$estimacions = Estimacion::find($can_id);

        //$establecimiento_cerrado=DB::table('can_establecimiento') ->where('can_id',$can_id)->where('establecimiento_id',$establecimiento_id)->get();  
        $monitoreo = $model_monitoreo->GetById($id_can,$id_establecimiento);
        
        //if($monitoreo->medicamento_cerrado==2 || $monitoreo->dispositivo_cerrado==2 ):
            /*if($nivel==1):            
                $id_monitoreo = $monitoreo->id;
                $medicina_interna = $monitoreo->medicina_interna; 
                $odontologia = $monitoreo->odontologia; 
                $obstetricia = $monitoreo->obstetricia; 

                if($medicina_interna==0 and $odontologia ==0 and $obstetricia==0):
                        $redirigir=2;
                        return view('frontend.cans.site.atenciones.edit',compact('id_establecimiento','id_can','id_monitoreo','medicina_interna','odontologia','obstetricia','redirigir','tipo','id_user'));
                endif;
            endif;*/

            

            $model_estimacion = new CanEstablecimiento();
            $model_petitorio = new Producto();
            $model_can_servicio = new Can();

            if($tipo==1){  //medicamentos

                $descripcion_tipo='Medicamentos';
                //buscamos si existe medicamentos ya ingresados
                $numero_medicamentos=$model_estimacion->ContarProductos($id_can,$id_establecimiento,1);
                
                if($numero_medicamentos->total>0):
                    $data=$model_estimacion->ProductosIngresados($id_can,$id_establecimiento,1);
                else:
                    $data='';
                endif;
                
                if(Auth::user()->tipo_registro==2){
                    
                    if($nivel==1){
                        $total_medicamentos_rubro=$model_petitorio->ContarPetitoriosNivel(1,1); //tipo,nivel
                        if($total_medicamentos_rubro->total>0):
                            $petitorios=$model_petitorio->ProductosFarmaciasSegunNivel(1);
                        else:
                            $petitorios='';
                        endif;
                    }
                    else
                    {   
                        $total_medicamentos_rubro=$model_petitorio->ContarPetitoriosNivel(1,$nivel); //tipo,nivel
                        if($total_medicamentos_rubro->total>0):
                            $petitorios=$model_petitorio->ProductosFarmaciasSegunNivel($nivel);
                        else:
                            $petitorios='';
                        endif;
                    }
                    $medicamento_cerrado_consolidado=$monitoreo->medicamento_cerrado;
                }
                else
                {
                    if(Auth::user()->rol==1){ return redirect(route('cans.index')); }

                }
                $diferencia=$total_medicamentos_rubro->total-$numero_medicamentos->total;
            }
            else
            {
                //buscamos si existe dispositivos ya ingresados
                $descripcion_tipo='Dispositivos Medicos';
                $numero_medicamentos=$model_estimacion->ContarProductos($id_can,$id_establecimiento,2);
                if($numero_medicamentos->total>0):
                    $data=$model_estimacion->ProductosIngresados2y3($id_can,$id_establecimiento,1);
                else:
                    $data='';
                endif;
                if(Auth::user()->tipo_registro==2){
                    if($id_establecimiento==1) //Angamos
                    {
                        $total_medicamentos_rubro=$model_petitorio->ContarPetitoriosNivel1D();
                        if($total_medicamentos_rubro->total>0):
                            $petitorios=$model_petitorio->PetitoriosPorNivel1D();
                        else:
                            $petitorios='';
                        endif;
                        $medicamento_cerrado_consolidado=$monitoreo->medicamento_cerrado;
                    }
                    else
                    {
                        if($nivel==1){

                            $total_medicamentos_rubro=$model_petitorio->ContarPetitoriosNivel1DA();
                            if($total_medicamentos_rubro->total>0):
                                $petitorios=$model_petitorio->PetitoriosPorNivel1DA();
                            else:
                                $petitorios='';
                            endif;
                            
                        }else
                        {
                            $total_medicamentos_rubro=$model_petitorio->ContarPetitoriosN($nivel);

                            if($total_medicamentos_rubro->total>0):
                                $petitorios=$model_petitorio->PetitoriosPorNivel($nivel);
                            else:
                                $petitorios='';
                            endif;
                        }
                        $medicamento_cerrado_consolidado=$monitoreo->medicamento_cerrado;
                    }
                }
                $diferencia=$total_medicamentos_rubro->total-$numero_medicamentos->total;
            }
            $valor=1;
            //Si hay medicamentos asignados
            if ($numero_medicamentos->total==0){ //recien ingresa
                if($id_can>5){
                    //cargar_can_anterior
                    $model_can_nivel_1 = new Can();
                    $producto_asignado = $model_can_nivel_1->CopiarProductosCanAnteriorNivel1($id_can, $tipo, $id_establecimiento);
                    return redirect('/ver_productos_can/' . $id_can . '/' . $tipo);
                }
                else{
                    if($nivel==1):
                        $redireccion = 0; 
                        return view('cans.site.quimico.asignar_medicamentos')->with('nombre_servicio', $nombre_servicio)->with('estimacions', $estimacions)->with('id_can', $id_can)->with('tipo', $tipo)->with('id_establecimiento', $id_establecimiento)->with('establecimiento', $establecimiento)->with('nombre_establecimiento', $establecimiento->nombre_establecimiento)->with('petitorios', $petitorios)->with('redireccion', $redireccion);
                    else:
                        return redirect('/all_cans')->withFlashDanger("Aun no han registrados los servicios");
                    endif;    
                }            
            }
            else
            {
                
                if($nivel!=1){
                    $total_servicio=$model_can_servicio->ContarCanServicio($can_id,$establecimiento_id);
                    $total_medicamentos_cerrado=$model_can_servicio->ContarMedicamentoCerrado($can_id,$establecimiento_id);
                    $total_dispositivos_cerrado=$model_can_servicio->ContarDispositivoCerrado($can_id,$establecimiento_id);
                    
                    $total_servicio=$total_servicio->total*2;
                    $total_cerrado=$total_medicamentos_cerrado->total+$total_dispositivos_cerrado->total;
                    if($total_servicio==$total_cerrado){
                        $valor=1;
                    }
                    else
                    {
                        $valor=1;   
                    }
                }

                $servicio_id=Auth::user()->id_consultorio;            
                return view('frontend.cans.site.quimico.medicamentos')->with('estimacions', $data)->with('nivel', $nivel)->with('valor', $valor)->with('servicio_id', $servicio_id)->with('numero_medicamentos', $diferencia)->with('descripcion_tipo', $descripcion_tipo)->with('establecimiento_id', $id_establecimiento)->with('medicamento_cerrado_consolidado', $medicamento_cerrado_consolidado)->with('tipo', $tipo)->with('can_id', $id_can);
            }
        //else:
        //    return redirect('/dashboard');
        //endif;
        
    }

    public function asignar_servicios($id_establecimiento)
    {
        $model_establecimiento = new Establecimiento;
        $consultorios_seleccionados = $model_establecimiento->ShowConsultoriosEstablecimientos($id_establecimiento);
        $establecimiento = Establecimiento::where('id', '=', intval($id_establecimiento))->first();
        if (empty($establecimiento)) {
            Session::flash('Establecimientos no encontrado');
            return redirect('/listar_establecimientos/');
        }
        return view('frontend.establecimientos.asignar_servicio',compact('establecimiento','id_establecimiento','consultorios_seleccionados'));
    }

    public function listar_productos_establecimiento(Request $request)
    {

        $model = new CanMonitoreo();
        $p[] = $request->id_establecimiento;
        $p[] = $request->NumeroPagina;
        $p[] = $request->NumeroRegistros;
        $data = $model->ShowAllProductosEstablecimientos($p);
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

    public function update_consultorio_can(Request $request) {
        
        $model_monitoreo = new CanMonitoreo;
        $p_id = $model_monitoreo->ActualizarConsultorioCAN($request->id_monitoreo, $request->medicina_interna,$request->odontologia,$request->obstetricia,$request->id_user);
        Session::flash('flash_message', 'Registro exitoso');
        

        if($request->redirigir == 1):
            return redirect('/all_cans');
        else:
            return redirect('/ver_productos_can/' . $request->id_can . '/' . $request->tipo);
        endif;
        
    }

    public function cargar_datos_consolidado($can_id, $establecimiento_id, $tipo)
    {
        //averiguamos el nivel del establecimiento

        $establecimiento = Establecimiento::find($establecimiento_id);
        $nivel=$establecimiento->nivel_id;

        if(Auth::user()->rol==3){
                $consolidado=3; //almacen
            }
            else
            {
                $consolidado=4; //farmacia   
            }    
        
        
        if(Auth::user()->rol==7){

            if($tipo==1){
                $contact=DB::table('estimacions')
                        ->where('can_id',$can_id) //cambiar 22
                        ->where('tipo_dispositivo_id',1)
                        ->where('establecimiento_id',$establecimiento_id)//cambiar 1
                        ->where('estado','<>',2)
                        ->orderby('descripcion','asc')//cambiar desc
                        ->get();
                $descripcion_tipo='Medicamentos';
            }
            else
            {   if ($tipo==2) {
                        $contact=DB::table('estimacions')
                        ->where('can_id',$can_id) //cambiar 22
                        ->where('tipo_dispositivo_id','>',1)
                        ->where('establecimiento_id',$establecimiento_id)//cambiar 1
                        ->where('estado','<>',2)
                        ->orderby('descripcion','asc')//cambiar desc
                        ->get();                    
                        $descripcion_tipo='Dispositivos';
                }
                else
                {
                    Flash::error('Datos no son correctos, error al descargar archivo');
                    return redirect(route('estimacion.index'));  
                }
            }
        }
        else
        {
            if($tipo==1){
                $contact=DB::table('estimacions')
                        ->where('can_id',$can_id) //cambiar 22                        
                        ->where('tipo_dispositivo_id',1)
                        ->where('establecimiento_id',$establecimiento_id)//cambiar 1
                        ->where('estado','<>',2)
                        ->orderby('descripcion','asc')//cambiar desc
                        ->get();
                $descripcion_tipo='Medicamentos';
            }
            else
            {   if ($tipo==2) {
                        $contact=DB::table('estimacions')
                        ->where('can_id',$can_id) //cambiar 22
                        ->where('tipo_dispositivo_id',4)
                        ->where('establecimiento_id',$establecimiento_id)//cambiar 1
                        ->where('estado','<>',2)
                        ->orderby('descripcion','asc')//cambiar desc
                        ->get();                    
                        $descripcion_tipo='Dispositivos';
                }
                else
                {
                    Flash::error('Datos no son correctos, error al descargar archivo');
                    return redirect(route('estimacion.index'));  
                }
            }
        }


        if($nivel==1):
            return Datatables::of($contact)
            ->addColumn('action', function($contact){
                if($contact->petitorio==1):
                    return "<a data-toggle='tooltip' data-original-title='Editar Producto!' onclick='editForm(". $contact->id .")' class='btn btn-primary btn-xs'><i class='glyphicon glyphicon-edit'></i></a>
                   <a data-toggle='tooltip' data-original-title='Editar Producto!' onclick='deleteForm(". $contact->id .")' class='btn btn-danger btn-xs'><i class='glyphicon glyphicon-trash'></i></a>";
                else:
                    return "<font color='red'>NO PETITORIO</font>";
                endif;

            })
            ->rawColumns(['justificacion', 'action'])->make(true);        
        else:
            $total_servicio=DB::table('can_servicio')
                                ->where('can_id',$can_id) 
                                ->where('establecimiento_id',$establecimiento_id)
                                ->count();                    
                                
            $total_medicamentos_cerrado = DB::table('can_servicio')  
                                    ->where('can_id',$can_id) 
                                    ->where('establecimiento_id',$establecimiento_id)
                                    ->where( function ( $query )
                                    {
                                        $query->orWhere('medicamento_cerrado',3)
                                            ->orWhere('medicamento_cerrado',2);
                                    })
                                  ->count();    

            $total_dispositivos_cerrado = DB::table('can_servicio')  
                                        ->where('can_id',$can_id) 
                                        ->where('establecimiento_id',$establecimiento_id)
                                        ->where( function ( $query )
                                        {
                                            $query->orWhere('dispositivo_cerrado',3)
                                                ->orWhere('dispositivo_cerrado',2);
                                        })
                                        ->count();    

            $total_servicio=$total_servicio*2;
            $total_cerrado=$total_servicio;
            
  
            if($total_servicio==$total_cerrado){
                return Datatables::of($contact)
                ->addColumn('action', function($contact){
                return "<a data-toggle='tooltip' data-original-title='Editar Producto!' onclick='editForm(". $contact->id .")' class='btn btn-primary btn-xs'><i class='glyphicon glyphicon-edit'></i></a>
                <a data-toggle='tooltip' data-original-title='Ver Producto!' onclick='ver_datos_avance(\"".$contact->petitorio_id."\" ,\"".$contact->descripcion."\" )' class='btn btn-info btn-xs'><i class='glyphicon glyphicon-eye-open'></i></a>
                ";
                })
            ->rawColumns(['justificacion', 'action'])->make(true);        

            }
            else{
 
                return Datatables::of($contact)
                ->addColumn('action', function($contact){
                  return "<a data-toggle='tooltip' data-original-title='Ver Producto!' onclick='ver_datos_avance(\"".$contact->petitorio_id."\" ,\"".$contact->descripcion."\" )' class='btn btn-info btn-xs'><i class='glyphicon glyphicon-eye-open'></i></a>
                  <a data-toggle='tooltip' data-original-title='Ver Producto!' onclick='ver_datos(\"".$contact->petitorio_id."\" ,\"".$contact->descripcion."\" )' class='btn btn-info btn-xs'><i class='glyphicon glyphicon-eye-open'></i></a>

                  ";


                })
                ->rawColumns(['justificacion', 'action'])->make(true);       

            }

        endif;
        
    }

    public function edit_producto_can2($id)
    {
        $model_quimico_can = new CanQuimico();
        $data = $model_quimico_can->ProductoCan($id);

        return $data;        
    }

    public function edit_producto_can($id) {

        $model_quimico_can = new CanQuimico();
        $data = $model_quimico_can->ProductoCan($id);
        $nivel = 1;

        return view('frontend.cans.site.quimico.form',compact('data','nivel','id'));
    }

    public function grabar_producto_can(Request $request) 
    {
       
       $model_estimacion = new CanMonitoreo();
       
        $grabarndo  = $model_estimacion->ActualizaNewProducto($request->id, $request->necesidad_anual,$request->cpma, $request->mes1,$request->mes2,$request->mes3,$request->mes4,$request->mes5,$request->mes6,$request->mes7,$request->mes8,$request->mes9,$request->mes10,$request->mes11,$request->mes12);
            
        return response()->json([
            'success' => true,
            'message' => 'Actualizado'
        ]);
    }

    public function cerrar_petitorio_can($can_id,$establecimiento_id,$tipo)
    {
            
        //$can = Can::find($can_id);

        

        //$servicio_id=Auth::user()->servicio_id;

        $establecimiento = Establecimiento::where('id', '=', intval($establecimiento_id))->first();
        $nivel=$establecimiento->nivel;

        if(Auth::user()->tipo_registro==2){
            if ($tipo==1){
                $tipo_cerrado='medicamento_cerrado';
            }
            else
            {
                $tipo_cerrado='dispositivo_cerrado';
            }
        }
        
        $model_responsables = new CanResponsable();
        $model_estimacion = new CanMonitoreo();

        if($nivel==1):

            $jefe_ipress = $model_responsables->GetJefeResponsable($can_id, $establecimiento_id, 6, 5); 
            
            if (count($jefe_ipress)>0):

                $jefe_farmacia = $model_responsables->GetJefeResponsable($can_id, $establecimiento_id, 6, 4);

                if (count($jefe_farmacia)>0): 

                    $responsables = $model_responsables->GetBuscaResponsable($can_id, $establecimiento_id);

                    if (count($responsables)>0):

                        if($establecimiento_id!=79 && $tipo==1):
                            $total_medicamentos = $model_estimacion->GetProductosTipoRegistrados($can_id, $establecimiento_id,1);
                            $responsable_medicamentos = $model_responsables->GetJefeResponsable($can_id, $establecimiento_id, 1, 3);
                            if (count($total_medicamentos)>0):
                                if (count($responsable_medicamentos)>0):                        
                                    if(Auth::user()->tipo_registro==2){    
                                        $ok=$model_estimacion->CerrarPetitorioCan($can_id, $establecimiento_id, $tipo_cerrado);
                                    }
                                    return redirect('/ver_consolidado/1/'.$can_id)->withFlashSuccess("Petitorio Cerrado.");
                                else:

                                    return redirect('/responsables/'.$can_id)->withFlashDanger('Falto Ingresar al Personal Responsables de Medicamentos');
                                    
                                endif;
                            else:
                                return redirect('/all_cans/')->withFlashDanger('Debe Ingresar Productos Farmaceuticos');
                            endif;
                        else:
                            
                            $total_biomedico_1 = $model_estimacion->GetProductosTipoRegistrados($can_id, $establecimiento_id,2);
                            $total_biomedico_2 = $model_estimacion->GetProductosTipoRegistrados($can_id, $establecimiento_id,3);
                            $total_biomedico_3 = $model_estimacion->GetProductosTipoRegistrados($can_id, $establecimiento_id,7);
                            
                            $total_biomedico = count($total_biomedico_1) + count($total_biomedico_2) + count($total_biomedico_3);
                            if ($total_biomedico>0):
                                
                                $responsable_biomedicos = $model_responsables->GetJefeResponsable($can_id, $establecimiento_id, 3, 3); //biomedico, rol
                                if (count($responsable_biomedicos)==0):
                                    return redirect('/responsables/'.$can_id)->withFlashDanger('Tiene Productos de Biomedicos, Falto Ingresar al Personal Responsable');
                                endif;
                            endif;

                            $total_dental = $model_estimacion->GetProductosTipoRegistrados($can_id, $establecimiento_id,4);
                            
                            if (count($total_dental)>0):
                                $responsable_dental = $model_responsables->GetJefeResponsable($can_id, $establecimiento_id, 4, 3);
                                if (count($responsable_dental)==0):
                                    return redirect('/responsables/'.$can_id)->withFlashDanger('Tiene Productos Odontológico , Falto Ingresar al Personal Responsable');
                                endif;
                            endif;

                            $total_fotografico = $model_estimacion->GetProductosTipoRegistrados($can_id, $establecimiento_id,6);
                            if (count($total_fotografico)>0):
                                $responsable_fotografico = $model_responsables->GetJefeResponsable($can_id, $establecimiento_id, 5, 3);
                                if (count($responsable_fotografico)==0):
                                    return redirect('/responsables/'.$can_id)->withFlashDanger('Tiene Productos de Fotográficos y Fonotécnicos, falto Ingresar al Personal Responsable');
                                endif;
                            endif;

                            $total_laboratorio_1 = $model_estimacion->GetProductosTipoRegistrados($can_id, $establecimiento_id,5);
                            $total_laboratorio_2 = $model_estimacion->GetProductosTipoRegistrados($can_id, $establecimiento_id,10);
                            $total_laboratorio = count($total_laboratorio_1) + count($total_laboratorio_2);
                            if ($total_laboratorio>0): 
                                $responsable_laboratorio = $model_responsables->GetJefeResponsable($can_id, $establecimiento_id, 2, 3);
                                if (count($responsable_laboratorio)==0):
                                    return redirect('/responsables/'.$can_id)->withFlashDanger('Tiene Productos de Laboratorio, falto Ingresar al Personal Responsable');
                                endif;
                            endif;

                            if(Auth::user()->tipo_registro==2){
                                $ok=$model_estimacion->CerrarPetitorioCan($can_id, $establecimiento_id, $tipo_cerrado);
                            }

                            return redirect('/ver_consolidado/2/'.$can_id)->withFlashSuccess("Petitorio Cerrado.");
                        endif;
                    else:
                        return redirect('/responsables/'.$can_id)->withFlashDanger('Falta Ingresar Responsables');
                    endif;
                else:
                    return redirect('/responsables/'.$can_id)->withFlashDanger('Falta Ingresar al jefe y/o Responsablde de Farmacia');
                endif;
            else:
                return redirect('/responsables/'.$can_id)->withFlashDanger('Falta Ingresar al jefe de la IPRESS O JEFE');
            endif;
        else:
            if(Auth::user()->tipo_registro==2){                
                $ok=$model_estimacion->CerrarPetitorioCan($can_id, $establecimiento_id, $tipo_cerrado);
            }
            return redirect('/ver_consolidado/2/'.$can_id)->withFlashSuccess("Petitorio Cerrado.");
        endif;
    }

    public function eliminar_producto_can($id) 
    {
       
       $model_estimacion = new CanMonitoreo();
       
        $grabando  = $model_estimacion->EliminaProductoCan($id);
            
        return response()->json([
            'success' => true,
            'message' => 'Producto Eliminado'
        ]);
    }

    public function exportEstimacionDataConsolidada($can_id,$establecimiento_id,$opt,$type, $valor){
        
        Excel::create('Exportar Estimacion CAN ', function($excel) use($can_id,$establecimiento_id,$opt,$type, $valor) {
            $excel->sheet('GENERAL', function($sheet) use($can_id,$establecimiento_id,$opt,$type, $valor) {
        
            $establecimiento = Establecimiento::where('id', '=', intval($establecimiento_id))->first();
            
            $nivel=$establecimiento->nivel;
            $nombre_establecimiento=$establecimiento->nombre;

            $servicio_id=0;
            $nombre_servicio='';
            
            if(Auth::user()->id_consultorio!=0):
                $consultorio = Consultorio::findOrFail(Auth::user()->id_consultorio);
                $nombre_servicio=$consultorio->nombre;
                $servicio_id=$consultorio->id;
            endif;

            $rol=Auth::user()->tipo_registro;

            $model_estimacion = new CanMonitoreo();

            if($opt==1){
                $data = $model_estimacion->ConsultaEstimacionFarmaceuticos($can_id, $establecimiento_id, $opt, $valor);
                $nombre_producto='Medicamentos';
            }else
                {   if ($opt==2) {
                        $data = $model_estimacion->ConsultaEstimacionDispositivos($can_id, $establecimiento_id, $opt, $valor);
                        $nombre_producto='Dispositivos';
                    }else
                    {
                        Flash::error('Datos no son correctos, error al descargar archivo');
                        return redirect(route('estimacion.index'));  
                    }
            }
        
            $variable = [];
            $n = 0;
            foreach ($data as $c) {
                $n++;
            }
            
            array_push($variable, array("Estimacion CAN"));
            array_push($variable, array("","PRORRATEO"));
            array_push($variable, array("N","Descripcion", "CPMA","Necesidad","Mes 1", "Mes 2", "Mes 3","Mes 4","Mes 5","Mes 6","Mes 7"," Mes 8","Mes 9","Mes 10","Mes 11","Mes 12"));
            $m = 1;

            foreach ($data as $c) {
                array_push($variable, array($m++, $c->descripcion,$c->cpma, $c->necesidad_anual, $c->mes1, $c->mes2, $c->mes3,  $c->mes4,$c->mes5, $c->mes6, $c->mes7, $c->mes8, $c->mes9, $c->mes10, $c->mes11, $c->mes12));
            }
            $sheet->with($variable)->mergeCells('A2:D2')->mergeCells('A3:D3')->mergeCells('E3:P3');
            });
        })->export('xlsx');
    }


    public function exportEstimacionDataConsolidada4($can_id,$establecimiento_id,$opt,$type, $valor)
    {

        $establecimiento = Establecimiento::where('id', '=', intval($establecimiento_id))->first();
        
        $nivel=$establecimiento->nivel;
        $nombre_establecimiento=$establecimiento->nombre;

        $servicio_id=0;
        $nombre_servicio='';
        
        if(Auth::user()->id_consultorio!=0):
            $consultorio = Consultorio::findOrFail(Auth::user()->id_consultorio);
            $nombre_servicio=$consultorio->nombre;
            $servicio_id=$consultorio->id;
        endif;

        $rol=Auth::user()->tipo_registro;

        //$can = Can::find($can_id);

        $model_estimacion = new CanMonitoreo();

        if($opt==1){
            $data = $model_estimacion->ConsultaEstimacionFarmaceuticos($can_id, $establecimiento_id, $opt, $valor);
            $nombre_producto='Medicamentos';
        }else
            {   if ($opt==2) {
                    $data = $model_estimacion->ConsultaEstimacionDispositivos($can_id, $establecimiento_id, $opt, $valor);
                    $nombre_producto='Dispositivos';
                }else
                {
                    Flash::error('Datos no son correctos, error al descargar archivo');
                    return redirect(route('estimacion.index'));  
                }
        }

        $archivo='CAN_Consolidado_'.$nombre_establecimiento;
        return Excel::create($archivo, function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {   
                
                //6 filas agrupadas con las columnas A,B,C
                $sheet->setMergeColumn(array(
                    'columns' => array('A','Q'),
                    'rows' => array(
                        array(1,6)                        
                    )
                ));

                //INSERTAR LOGOS
                /*$objDrawing = new PHPExcel_Worksheet_Drawing;
                $objDrawing->setPath(public_path('img/logo_sanidad.png')); //your image path
                $objDrawing->setCoordinates('A1');
                $objDrawing->setWorksheet($sheet);
                
                $objDrawing2 = new PHPExcel_Worksheet_Drawing;
                $objDrawing2->setPath(public_path('img/logo_pnp.png')); //your image path
                $objDrawing2->setCoordinates('Q1');
                $objDrawing2->setWorksheet($sheet);
    */
                $sheet->mergeCells('B1:O1');
                $sheet->cell('B1', function($cell) {$cell->setValue('CAN');  $cell->setFontSize(38); $cell->setFontWeight('bold'); $cell->setAlignment('center'); $cell->setValignment('middle');
                });


                $sheet->mergeCells('B2:O2');
                $sheet->mergeCells('B3:O3');
                $sheet->cell('B2', function($cell) {$cell->setValue('CUADRO ANUAL DE NECESIDADES ');  $cell->setFontSize(28); $cell->setFontWeight('bold'); $cell->setAlignment('center'); $cell->setValignment('middle'); });
                
                $nombre_servicio=Auth::user()->nombre_servicio;
                $nombre_establecimiento=Auth::user()->nombre_establecimiento;

                
                $sheet->cell('B3', $nombre_establecimiento);
                $sheet->cell('B3', function($cell) {$cell->setFontSize(18); $cell->setAlignment('center'); $cell->setFontWeight('bold'); });      


                $sheet->setHeight(1, 40);
                $sheet->setHeight(2, 30);
                $sheet->setHeight(3, 20);
                
                //$sheet->mergeCells('R2:Q2');
                $sheet->mergeCells('K4:L5');
                $sheet->cell('K4', function($cell) {$cell->setValue('MES / AÑO');   $cell->setFontSize(18); $cell->setFontWeight('bold'); $cell->setAlignment('center'); $cell->setValignment('middle'); });


                $sheet->setWidth(array(
                    'A'     =>  5,
                    'B'     =>  15,
                    'C'     =>  100,
                    'D'     =>  10,
                    'E'     =>  10,
                    'F'     =>  10,
                    'G'     =>  10,
                    'H'     =>  10,
                    'I'     =>  10,
                    'J'     =>  10,
                    'K'     =>  10,
                    'L'     =>  10,
                    'M'     =>  10,
                    'N'     =>  10,
                    'O'     =>  10,
                    'P'     =>  10,
                    'Q'     =>  10,                                        
                ));

                //fila
                $i=10;
                //ordenar
                $k=1;

                if (!empty($data)) {

                    $tipo_dispositivo_anterior=0;
                    $m=1; $k=6;

                    foreach ($data as $key => $value) {

                        if($tipo_dispositivo_anterior!=$value->id_tipo_dispositivo){

                            $cpma_total = 0; $total_necesidad_anual = 0; $mes1_total = 0; $mes2_total = 0; $mes3_total = 0; $mes4_total = 0; $mes5_total = 0; $mes6_total = 0; $mes7_total = 0; $mes8_total = 0; $mes9_total = 0; $mes10_total = 0; $mes11_total = 0; $mes12_total = 0;
                            $cpma_total_1 = 0; $total_necesidad_anual_1 = 0; $mes1_total_1 = 0; $mes2_total_1 = 0; $mes3_total_1 = 0; $mes4_total_1 = 0; $mes5_total_1 = 0; $mes6_total_1 = 0; $mes7_total_1 = 0; $mes8_total_1 = 0; $mes9_total_1 = 0; $mes10_total_1 = 0; $mes11_total_1 = 0; $mes12_total_1 = 0;
                            $cpma_total_2 = 0; $total_necesidad_anual_2 = 0; $mes1_total_2 = 0; $mes2_total_2 = 0; $mes3_total_2 = 0; $mes4_total_2 = 0; $mes5_total_2 = 0; $mes6_total_2 = 0; $mes7_total_2 = 0; $mes8_total_2 = 0; $mes9_total_2 = 0; $mes10_total_2 = 0; $mes11_total_2 = 0; $mes12_total_2 = 0;

                            
                            $descripcion_tipo_dispositivo=DB::table('tipo_productos')->where('id',$value->id_tipo_dispositivo)->get();
                            if(count($descripcion_tipo_dispositivo)>0):
                                //$nombre_dispositivo=$descripcion_tipo_dispositivo->get(0)->nombre;
                                $nombre_dispositivo=$value->id_tipo_dispositivo;
                            else:
                                $nombre_dispositivo=$value->id_tipo_dispositivo;
                            endif;
                            $i=$k+2;
                            $j=$i+1;   
                            $d=$i-1;     
                            //construimos cabecera
                            $sheet->cell('A'.$i, function($cell) {$cell->setValue('N°'); $cell->setFontSize(10);   $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('B'.$i, function($cell) {$cell->setValue('COD MED'); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setFontSize(10); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('C'.$i, function($cell) {$cell->setValue('NOMBRE DEL PRODUCTO'); $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('D'.$i, function($cell) {$cell->setValue('CPMA'); $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('E'.$i, function($cell) {$cell->setValue('NECESIDAD'); $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            //$sheet->cell('F'.$i, function($cell) {$cell->setValue('OBSERVACIÓN'); $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('F'.$j, function($cell) {$cell->setValue('ENERO');  $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 
                            
                            $sheet->cell('G'.$j, function($cell) {$cell->setValue('FEBRERO'); $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('H'.$j, function($cell) {$cell->setValue('MARZO');  $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('I'.$j, function($cell) {$cell->setValue('ABRIL');  $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('J'.$j, function($cell) {$cell->setValue('MAYO');  $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 
                            
                            $sheet->cell('K'.$j, function($cell) {$cell->setValue('JUNIO'); $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('L'.$j, function($cell) {$cell->setValue('JULIO');  $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('M'.$j, function($cell) {$cell->setValue('AGOSTO');  $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('N'.$j, function($cell) {$cell->setValue('SETIEMBRE');  $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 
                            
                            $sheet->cell('O'.$j, function($cell) {$cell->setValue('OCTUBRE'); $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('P'.$j, function($cell) {$cell->setValue('NOVIEMBRE');  $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('Q'.$j, function($cell) {$cell->setValue('DICIEMBRE');  $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); });

                            $sheet->cell('R'.$i, function($cell) {$cell->setValue('CPMA'); $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('S'.$i, function($cell) {$cell->setValue('NECESIDAD'); $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('T'.$j, function($cell) {$cell->setValue('ENERO');  $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 
                            
                            $sheet->cell('U'.$j, function($cell) {$cell->setValue('FEBRERO'); $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('V'.$j, function($cell) {$cell->setValue('MARZO');  $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('W'.$j, function($cell) {$cell->setValue('ABRIL');  $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('X'.$j, function($cell) {$cell->setValue('MAYO');  $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 
                            
                            $sheet->cell('Y'.$j, function($cell) {$cell->setValue('JUNIO'); $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('Z'.$j, function($cell) {$cell->setValue('JULIO');  $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('AA'.$j, function($cell) {$cell->setValue('AGOSTO');  $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('AB'.$j, function($cell) {$cell->setValue('SETIEMBRE');  $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 
                            
                            $sheet->cell('AC'.$j, function($cell) {$cell->setValue('OCTUBRE'); $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('AD'.$j, function($cell) {$cell->setValue('NOVIEMBRE');  $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('AE'.$j, function($cell) {$cell->setValue('DICIEMBRE');  $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); });

                            $sheet->cell('AF'.$i, function($cell) {$cell->setValue('CPMA'); $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('AG'.$i, function($cell) {$cell->setValue('NECESIDAD'); $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('AH'.$j, function($cell) {$cell->setValue('ENERO');  $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 
                            
                            $sheet->cell('AI'.$j, function($cell) {$cell->setValue('FEBRERO'); $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('AJ'.$j, function($cell) {$cell->setValue('MARZO');  $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('AK'.$j, function($cell) {$cell->setValue('ABRIL');  $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('AL'.$j, function($cell) {$cell->setValue('MAYO');  $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 
                            
                            $sheet->cell('AM'.$j, function($cell) {$cell->setValue('JUNIO'); $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('AN'.$j, function($cell) {$cell->setValue('JULIO');  $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('AO'.$j, function($cell) {$cell->setValue('AGOSTO');  $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('AP'.$j, function($cell) {$cell->setValue('SETIEMBRE');  $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 
                            
                            $sheet->cell('AQ'.$j, function($cell) {$cell->setValue('OCTUBRE'); $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('AR'.$j, function($cell) {$cell->setValue('NOVIEMBRE');  $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); }); 

                            $sheet->cell('AS'.$j, function($cell) {$cell->setValue('DICIEMBRE');  $cell->setFontSize(10); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setAlignment('center'); $cell->setFontWeight('bold'); });

                            

                            $sheet->mergeCells('F'.$i.':Q'.$i);
                            $sheet->cell('F'.$i, function($cell) {$cell->setValue('PRORRATEO AÑO 1'); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setFontSize(13);  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });
                            $sheet->mergeCells('T'.$i.':AE'.$i);
                            $sheet->cell('T'.$i, function($cell) {$cell->setValue('PRORRATEO AÑO 2'); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setFontSize(13);  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });
                            $sheet->mergeCells('AH'.$i.':AS'.$i);
                            $sheet->cell('AH'.$i, function($cell) {$cell->setValue('PRORRATEO AÑO 3'); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setFontSize(13);  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });

                            $sheet->mergeCells('A'.$d.':AS'.$d);
                            $sheet->cell('O'.$d, function($cell) {$cell->setValue(''); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setFontSize(13);  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });
                            //$sheet->mergeCells('R'.$d.':AF'.$d);
                            //$sheet->cell('O'.$d, function($cell) {$cell->setValue(''); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setFontSize(13);  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });
                            //$sheet->mergeCells('AG'.$d.':AT'.$d);
                            //$sheet->cell('O'.$d, function($cell) {$cell->setValue(''); $cell->setBorder('thin', 'thin', 'thin', 'thin'); $cell->setFontSize(13);  $cell->setAlignment('center'); $cell->setFontWeight('bold'); });
                            $sheet->cell('A'.$d, $nombre_dispositivo); //establecimiento 

                            $n=$i+2;
                            
                            $sheet->setMergeColumn(array(
                                'columns' => array('A','B','C'),
                                'rows' => array(
                                    array($i,$n)                        
                                )
                            ));

                            $sheet->setMergeColumn(array(
                                'columns' => array('E','D','R','S','AF','AG'),
                                'rows' => array(
                                    array($i,$j)                        
                                )
                            ));

                            $k=$i+3;
                            $tipo_dispositivo_anterior=$value->id_tipo_dispositivo;
                            $m=1;


                        }


                        
                        $sheet->cell('A'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });    
                        $sheet->cell('B'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });    
                        $sheet->cell('C'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });    
                        $sheet->cell('D'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });    
                        $sheet->cell('E'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });    
                        $sheet->cell('F'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });
                        $sheet->cell('G'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });
                        $sheet->cell('H'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });
                        $sheet->cell('I'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });
                        $sheet->cell('J'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });
                        $sheet->cell('K'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });
                        $sheet->cell('L'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });
                        $sheet->cell('M'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });
                        $sheet->cell('N'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });
                        $sheet->cell('O'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });
                        $sheet->cell('P'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });
                        $sheet->cell('Q'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });
                        $sheet->cell('R'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });    
                        $sheet->cell('S'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });    
                        $sheet->cell('T'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });    
                        $sheet->cell('U'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });    
                        $sheet->cell('V'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });    
                        $sheet->cell('W'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });
                        $sheet->cell('X'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });
                        $sheet->cell('Y'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });
                        $sheet->cell('Z'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });
                        $sheet->cell('AA'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });
                        $sheet->cell('AB'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });
                        $sheet->cell('AC'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });
                        $sheet->cell('AD'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });
                        $sheet->cell('AE'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });
                        $sheet->cell('AF'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });
                        $sheet->cell('AG'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });
                        $sheet->cell('AH'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });
                        $sheet->cell('AI'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });    
                        $sheet->cell('AJ'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });    
                        $sheet->cell('AK'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });    
                        $sheet->cell('AL'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });
                        $sheet->cell('AM'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });
                        $sheet->cell('AN'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });
                        $sheet->cell('AO'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });
                        $sheet->cell('AP'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });
                        $sheet->cell('AQ'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });
                        $sheet->cell('AR'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });
                        $sheet->cell('AS'.$k, function($cell) {$cell->setBorder('thin', 'thin', 'thin', 'thin');  });
                        
                        
                        /*switch ($value->estado) {                                    
                                    case 0: $descripcion_observacion=' Ratificado '; break;
                                    case 1: $descripcion_observacion=' Nuevo '; break;
                                    case 2: $descripcion_observacion=' Eliminado '; break;
                                    case 3: $descripcion_observacion=' Actualizado, cpma_ant='.$value->cpma_anterior.' nec_ant='.$value->necesidad_anterior; break;   
                                }
                        */
                        $sheet->cell('A'.$k, $m); 
                        $sheet->cell('B'.$k, $value->cod_producto); //establecimiento 
                        $sheet->cell('C'.$k, $value->descripcion); //nivel
                        if($value->cpma>0){ $sheet->cell('D'.$k, $value->cpma);}else{$sheet->cell('D'.$k,number_format($value->cpma, 2, '.', ','));}
                        if($value->necesidad_anual>0){ $sheet->cell('E'.$k, $value->necesidad_anual);}else{$sheet->cell('E'.$k, number_format($value->necesidad_anual, 2, '.', ','));}
                        if($value->mes1>0){ $sheet->cell('F'.$k, $value->mes1);}else{$sheet->cell('F'.$k, number_format($value->mes1, 2, '.', ','));}
                        if($value->mes2>0){ $sheet->cell('G'.$k, $value->mes2);}else{$sheet->cell('G'.$k, number_format($value->mes2, 2, '.', ','));}
                        if($value->mes3>0){ $sheet->cell('H'.$k, $value->mes3);}else{$sheet->cell('H'.$k, number_format($value->mes3, 2, '.', ','));}
                        if($value->mes4>0){ $sheet->cell('I'.$k, $value->mes4);}else{$sheet->cell('I'.$k, number_format($value->mes4, 2, '.', ','));}
                        if($value->mes5>0){ $sheet->cell('J'.$k, $value->mes5);}else{$sheet->cell('J'.$k, number_format($value->mes5, 2, '.', ','));}
                        if($value->mes6>0){ $sheet->cell('K'.$k, $value->mes6);}else{$sheet->cell('K'.$k, number_format($value->mes6, 2, '.', ','));}
                        if($value->mes7>0){ $sheet->cell('L'.$k, $value->mes7);}else{$sheet->cell('L'.$k, number_format($value->mes7, 2, '.', ','));}
                        if($value->mes8>0){ $sheet->cell('M'.$k, $value->mes8);}else{$sheet->cell('M'.$k, number_format($value->mes8, 2, '.', ','));}
                        if($value->mes9>0){ $sheet->cell('N'.$k, $value->mes9);}else{$sheet->cell('N'.$k, number_format($value->mes9, 2, '.', ','));}
                        if($value->mes10>0){ $sheet->cell('O'.$k, $value->mes10);}else{$sheet->cell('O'.$k, number_format($value->mes10, 2, '.', ','));}
                        if($value->mes11>0){ $sheet->cell('P'.$k, $value->mes11);}else{$sheet->cell('P'.$k, number_format($value->mes11, 2, '.', ','));}
                        if($value->mes12>0){ $sheet->cell('Q'.$k, $value->mes12);}else{$sheet->cell('Q'.$k, number_format($value->mes12, 2, '.', ','));}

                        if($value->cpma_1>0){ $sheet->cell('R'.$k, $value->cpma_1);}else{$sheet->cell('R'.$k,number_format($value->cpma_1, 2, '.', ','));}
                        if($value->necesidad_anual_1>0){ $sheet->cell('S'.$k, $value->necesidad_anual_1);}else{$sheet->cell('E'.$k, number_format($value->necesidad_anual_1, 2, '.', ','));}
                        if($value->mes1_1>0){ $sheet->cell('T'.$k, $value->mes1_1);}else{$sheet->cell('T'.$k, number_format($value->mes1_1, 2, '.', ','));}
                        if($value->mes2_1>0){ $sheet->cell('U'.$k, $value->mes2_1);}else{$sheet->cell('U'.$k, number_format($value->mes2_1, 2, '.', ','));}
                        if($value->mes3_1>0){ $sheet->cell('V'.$k, $value->mes3_1);}else{$sheet->cell('V'.$k, number_format($value->mes3_1, 2, '.', ','));}
                        if($value->mes4_1>0){ $sheet->cell('W'.$k, $value->mes4_1);}else{$sheet->cell('W'.$k, number_format($value->mes4_1, 2, '.', ','));}
                        if($value->mes5_1>0){ $sheet->cell('X'.$k, $value->mes5_1);}else{$sheet->cell('X'.$k, number_format($value->mes5_1, 2, '.', ','));}
                        if($value->mes6_1>0){ $sheet->cell('Y'.$k, $value->mes6_1);}else{$sheet->cell('Y'.$k, number_format($value->mes6_1, 2, '.', ','));}
                        if($value->mes7_1>0){ $sheet->cell('Z'.$k, $value->mes7_1);}else{$sheet->cell('Z'.$k, number_format($value->mes7_1, 2, '.', ','));}
                        if($value->mes8_1>0){ $sheet->cell('AA'.$k, $value->mes8_1);}else{$sheet->cell('AA'.$k, number_format($value->mes8_1, 2, '.', ','));}
                        if($value->mes9_1>0){ $sheet->cell('AB'.$k, $value->mes9_1);}else{$sheet->cell('AB'.$k, number_format($value->mes9_1, 2, '.', ','));}
                        if($value->mes10_1>0){ $sheet->cell('AC'.$k, $value->mes10_1);}else{$sheet->cell('AC'.$k, number_format($value->mes10_1, 2, '.', ','));}
                        if($value->mes11_1>0){ $sheet->cell('AD'.$k, $value->mes11_1);}else{$sheet->cell('AD'.$k, number_format($value->mes11_1, 2, '.', ','));}
                        if($value->mes12_1>0){ $sheet->cell('AE'.$k, $value->mes12_1);}else{$sheet->cell('AE'.$k, number_format($value->mes12_1, 2, '.', ','));}

                        if($value->cpma_2>0){ $sheet->cell('AF'.$k, $value->cpma_2);}else{$sheet->cell('AF'.$k,number_format($value->cpma_2, 2, '.', ','));}
                        if($value->necesidad_anual_2>0){ $sheet->cell('AG'.$k, $value->necesidad_anual_2);}else{$sheet->cell('E'.$k, number_format($value->necesidad_anual_2, 2, '.', ','));}
                        if($value->mes1_2>0){ $sheet->cell('AH'.$k, $value->mes1_2);}else{$sheet->cell('AH'.$k, number_format($value->mes1_2, 2, '.', ','));}
                        if($value->mes2_2>0){ $sheet->cell('AI'.$k, $value->mes2_2);}else{$sheet->cell('AI'.$k, number_format($value->mes2_2, 2, '.', ','));}
                        if($value->mes3_2>0){ $sheet->cell('AJ'.$k, $value->mes3_2);}else{$sheet->cell('AJ'.$k, number_format($value->mes3_2, 2, '.', ','));}
                        if($value->mes4_2>0){ $sheet->cell('AK'.$k, $value->mes4_2);}else{$sheet->cell('AK'.$k, number_format($value->mes4_2, 2, '.', ','));}
                        if($value->mes5_2>0){ $sheet->cell('AL'.$k, $value->mes5_2);}else{$sheet->cell('AL'.$k, number_format($value->mes5_2, 2, '.', ','));}
                        if($value->mes6_2>0){ $sheet->cell('AM'.$k, $value->mes6_2);}else{$sheet->cell('AM'.$k, number_format($value->mes6_2, 2, '.', ','));}
                        if($value->mes7_2>0){ $sheet->cell('AN'.$k, $value->mes7_2);}else{$sheet->cell('AN'.$k, number_format($value->mes7_2, 2, '.', ','));}
                        if($value->mes8_2>0){ $sheet->cell('AO'.$k, $value->mes8_2);}else{$sheet->cell('AO'.$k, number_format($value->mes8_2, 2, '.', ','));}
                        if($value->mes9_2>0){ $sheet->cell('AP'.$k, $value->mes9_2);}else{$sheet->cell('AP'.$k, number_format($value->mes9_2, 2, '.', ','));}
                        if($value->mes10_2>0){ $sheet->cell('AQ'.$k, $value->mes10_2);}else{$sheet->cell('AQ'.$k, number_format($value->mes10_2, 2, '.', ','));}
                        if($value->mes11_2>0){ $sheet->cell('AR'.$k, $value->mes11_2);}else{$sheet->cell('AR'.$k, number_format($value->mes11_2, 2, '.', ','));}
                        if($value->mes12_2>0){ $sheet->cell('AS'.$k, $value->mes12_2);}else{$sheet->cell('AS'.$k, number_format($value->mes12_2, 2, '.', ','));}

                        $k++;
                        $m++;
                        
                        //////////////Mes - Año   
                        $sheet->mergeCells('M4:N5');
                        
                        
                        //Calcular sumatoria
                        $total_necesidad_anual = $value->necesidad_anual + $total_necesidad_anual; $mes1_total= $value->mes1 + $mes1_total; $mes2_total= $value->mes2 + $mes2_total; $mes3_total= $value->mes3 + $mes3_total; $mes4_total= $value->mes4 + $mes4_total; $mes5_total= $value->mes5 + $mes5_total; $mes6_total= $value->mes6 + $mes6_total; $mes7_total= $value->mes7 + $mes7_total; $mes8_total= $value->mes8 + $mes8_total; $mes9_total= $value->mes9 + $mes9_total; $mes10_total= $value->mes10 + $mes10_total; $mes11_total= $value->mes11 + $mes11_total; $mes12_total= $value->mes12 + $mes12_total; $cpma_total= $value->cpma + $cpma_total;

                        $total_necesidad_anual_1 = $value->necesidad_anual_1 + $total_necesidad_anual_1; $mes1_total_1= $value->mes1_1 + $mes1_total_1; $mes2_total_1= $value->mes2_1 + $mes2_total_1; $mes3_total_1= $value->mes3_1 + $mes3_total_1; $mes4_total_1= $value->mes4_1 + $mes4_total_1; $mes5_total_1= $value->mes5_1 + $mes5_total_1; $mes6_total_1= $value->mes6_1 + $mes6_total_1; $mes7_total_1= $value->mes7_1 + $mes7_total_1; $mes8_total_1= $value->mes8_1 + $mes8_total_1; $mes9_total_1= $value->mes9_1 + $mes9_total_1; $mes10_total_1= $value->mes10_1 + $mes10_total_1; $mes11_total_1= $value->mes11_1 + $mes11_total_1; $mes12_total_1= $value->mes12_1 + $mes12_total_1; $cpma_total_1= $value->cpma_1 + $cpma_total_1;

                        $total_necesidad_anual_2 = $value->necesidad_anual_2 + $total_necesidad_anual_2; $mes1_total_2= $value->mes1_2 + $mes1_total_2; $mes2_total_2= $value->mes2_2 + $mes2_total_2; $mes3_total_2= $value->mes3_2 + $mes3_total_2; $mes4_total_2= $value->mes4_2 + $mes4_total_2; $mes5_total_2= $value->mes5_2 + $mes5_total_2; $mes6_total_2= $value->mes6_2 + $mes6_total_2; $mes7_total_2= $value->mes7_2 + $mes7_total_2; $mes8_total_2= $value->mes8_2 + $mes8_total_2; $mes9_total_2= $value->mes9_2 + $mes9_total_2; $mes10_total_2= $value->mes10_2 + $mes10_total_2; $mes11_total_2= $value->mes11_2 + $mes11_total_2; $mes12_total_2= $value->mes12_2 + $mes12_total_2; $cpma_total_2= $value->cpma_2 + $cpma_total_2;
                        
                    if($cpma_total>0){ $sheet->cell('D'.$n, $cpma_total);}else{$sheet->cell('D'.$n, number_format($cpma_total, 2, '.', ','));}
                    $sheet->cell('D'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($total_necesidad_anual>0){ $sheet->cell('E'.$n, $total_necesidad_anual);}else{$sheet->cell('E'.$n, number_format($total_necesidad_anual, 2, '.', ','));}
                    $sheet->cell('E'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes1_total>0){ $sheet->cell('F'.$n, $mes1_total);}else{$sheet->cell('F'.$n, number_format($mes1_total, 2, '.', ','));}
                    $sheet->cell('F'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes2_total>0){ $sheet->cell('G'.$n, $mes2_total);}else{$sheet->cell('G'.$n, number_format($mes2_total, 2, '.', ','));}
                    $sheet->cell('G'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes3_total>0){ $sheet->cell('H'.$n, $mes3_total);}else{$sheet->cell('H'.$n, number_format($mes3_total, 2, '.', ','));}
                    $sheet->cell('H'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes4_total>0){ $sheet->cell('I'.$n, $mes4_total);}else{$sheet->cell('I'.$n, number_format($mes4_total, 2, '.', ','));}
                    $sheet->cell('I'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes5_total>0){ $sheet->cell('J'.$n, $mes5_total);}else{$sheet->cell('J'.$n, number_format($mes5_total, 2, '.', ','));}
                    $sheet->cell('J'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes6_total>0){ $sheet->cell('K'.$n, $mes6_total);}else{$sheet->cell('K'.$n, number_format($mes6_total, 2, '.', ','));}
                    $sheet->cell('K'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes7_total>0){ $sheet->cell('L'.$n, $mes7_total);}else{$sheet->cell('L'.$n, number_format($mes7_total, 2, '.', ','));}
                    $sheet->cell('L'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes8_total>0){ $sheet->cell('M'.$n, $mes8_total);}else{$sheet->cell('M'.$n, number_format($mes8_total, 2, '.', ','));}
                    $sheet->cell('M'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes9_total>0){ $sheet->cell('N'.$n, $mes9_total);}else{$sheet->cell('N'.$n, number_format($mes9_total, 2, '.', ','));}
                    $sheet->cell('N'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes10_total>0){ $sheet->cell('O'.$n, $mes10_total);}else{$sheet->cell('O'.$n, number_format($mes10_total, 2, '.', ','));}
                    $sheet->cell('O'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes11_total>0){ $sheet->cell('P'.$n, $mes11_total);}else{$sheet->cell('P'.$n, number_format($mes11_total, 2, '.', ','));}
                    $sheet->cell('P'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes12_total>0){ $sheet->cell('Q'.$n, $mes12_total);}else{$sheet->cell('Q'.$n, number_format($mes12_total, 2, '.', ','));}
                    $sheet->cell('Q'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });

                    if($cpma_total_1>0){ $sheet->cell('R'.$n, $cpma_total_1);}else{$sheet->cell('R'.$n, number_format($cpma_total_1, 2, '.', ','));}
                    $sheet->cell('R'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($total_necesidad_anual_1>0){ $sheet->cell('S'.$n, $total_necesidad_anual_1);}else{$sheet->cell('S'.$n, number_format($total_necesidad_anual_1, 2, '.', ','));}
                    $sheet->cell('S'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes1_total_1>0){ $sheet->cell('T'.$n, $mes1_total_1);}else{$sheet->cell('T'.$n, number_format($mes1_total_1, 2, '.', ','));}
                    $sheet->cell('T'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes2_total_1>0){ $sheet->cell('U'.$n, $mes2_total_1);}else{$sheet->cell('U'.$n, number_format($mes2_total_1, 2, '.', ','));}
                    $sheet->cell('U'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes3_total_1>0){ $sheet->cell('V'.$n, $mes3_total_1);}else{$sheet->cell('V'.$n, number_format($mes3_total_1, 2, '.', ','));}
                    $sheet->cell('V'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes4_total_1>0){ $sheet->cell('W'.$n, $mes4_total_1);}else{$sheet->cell('W'.$n, number_format($mes4_total_1, 2, '.', ','));}
                    $sheet->cell('W'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes5_total_1>0){ $sheet->cell('X'.$n, $mes5_total_1);}else{$sheet->cell('X'.$n, number_format($mes5_total_1, 2, '.', ','));}
                    $sheet->cell('X'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes6_total_1>0){ $sheet->cell('Y'.$n, $mes6_total_1);}else{$sheet->cell('Y'.$n, number_format($mes6_total_1, 2, '.', ','));}
                    $sheet->cell('Y'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes7_total_1>0){ $sheet->cell('Z'.$n, $mes7_total_1);}else{$sheet->cell('Z'.$n, number_format($mes7_total_1, 2, '.', ','));}
                    $sheet->cell('Z'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes8_total_1>0){ $sheet->cell('AA'.$n, $mes8_total_1);}else{$sheet->cell('AA'.$n, number_format($mes8_total_1, 2, '.', ','));}
                    $sheet->cell('AA'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes9_total_1>0){ $sheet->cell('AB'.$n, $mes9_total_1);}else{$sheet->cell('AB'.$n, number_format($mes9_total_1, 2, '.', ','));}
                    $sheet->cell('AB'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes10_total_1>0){ $sheet->cell('AC'.$n, $mes10_total_1);}else{$sheet->cell('AC'.$n, number_format($mes10_total_1, 2, '.', ','));}
                    $sheet->cell('AC'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes11_total_1>0){ $sheet->cell('AD'.$n, $mes11_total_1);}else{$sheet->cell('AD'.$n, number_format($mes11_total_1, 2, '.', ','));}
                    $sheet->cell('AD'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes12_total_1>0){ $sheet->cell('AE'.$n, $mes12_total_1);}else{$sheet->cell('AE'.$n, number_format($mes12_total_1, 2, '.', ','));}
                    $sheet->cell('AE'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });

                    if($cpma_total_2>0){ $sheet->cell('AF'.$n, $cpma_total_2);}else{$sheet->cell('AF'.$n, number_format($cpma_total_2, 2, '.', ','));}
                    $sheet->cell('AF'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($total_necesidad_anual_2>0){ $sheet->cell('AG'.$n, $total_necesidad_anual_2);}else{$sheet->cell('AG'.$n, number_format($total_necesidad_anual_2, 2, '.', ','));}
                    $sheet->cell('AG'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes1_total_2>0){ $sheet->cell('AH'.$n, $mes1_total_2);}else{$sheet->cell('AH'.$n, number_format($mes1_total_2, 2, '.', ','));}
                    $sheet->cell('AH'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes2_total_2>0){ $sheet->cell('AI'.$n, $mes2_total_2);}else{$sheet->cell('AI'.$n, number_format($mes2_total_2, 2, '.', ','));}
                    $sheet->cell('AI'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes3_total_2>0){ $sheet->cell('AJ'.$n, $mes3_total_2);}else{$sheet->cell('AJ'.$n, number_format($mes3_total_2, 2, '.', ','));}
                    $sheet->cell('AJ'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes4_total_2>0){ $sheet->cell('AK'.$n, $mes4_total_2);}else{$sheet->cell('AK'.$n, number_format($mes4_total_2, 2, '.', ','));}
                    $sheet->cell('AK'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes5_total_2>0){ $sheet->cell('AL'.$n, $mes5_total_2);}else{$sheet->cell('AL'.$n, number_format($mes5_total_2, 2, '.', ','));}
                    $sheet->cell('AL'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes6_total_2>0){ $sheet->cell('AM'.$n, $mes6_total_2);}else{$sheet->cell('AM'.$n, number_format($mes6_total_2, 2, '.', ','));}
                    $sheet->cell('AM'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes7_total_2>0){ $sheet->cell('AN'.$n, $mes7_total_2);}else{$sheet->cell('AN'.$n, number_format($mes7_total_2, 2, '.', ','));}
                    $sheet->cell('AN'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes8_total_2>0){ $sheet->cell('AO'.$n, $mes8_total_2);}else{$sheet->cell('AO'.$n, number_format($mes8_total_2, 2, '.', ','));}
                    $sheet->cell('AO'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes9_total_2>0){ $sheet->cell('AP'.$n, $mes9_total_2);}else{$sheet->cell('AP'.$n, number_format($mes9_total_2, 2, '.', ','));}
                    $sheet->cell('AP'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes10_total_2>0){ $sheet->cell('AQ'.$n, $mes10_total_2);}else{$sheet->cell('AQ'.$n, number_format($mes10_total_2, 2, '.', ','));}
                    $sheet->cell('AQ'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes11_total_2>0){ $sheet->cell('AR'.$n, $mes11_total_2);}else{$sheet->cell('AR'.$n, number_format($mes11_total_2, 2, '.', ','));}
                    $sheet->cell('AR'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    if($mes12_total_2>0){ $sheet->cell('AS'.$n, $mes12_total_2);}else{$sheet->cell('AS'.$n, number_format($mes12_total_2, 2, '.', ','));}
                    $sheet->cell('AS'.$n, function($cell) {$cell->setFontSize(12); $cell->setBorder('thin', 'thin', 'thin', 'thin');  $cell->setAlignment('right'); $cell->setFontWeight('bold'); });
                    }
                }
                
                //$servicio_id=Auth::user()->id_consultorio;
                //$establecimiento_id=Auth::user()->establecimiento_id;

                //$establecimiento=Establecimiento::find($establecimiento_id);
                //$nivel=$establecimiento->nivel;

                $j=$k+10;
                $m=$j-1;

                $fh=$j+2;

                $now = new \DateTime();

                $sheet->cell('O'.$j, 'Fecha y Hora de la Descarga: '.$now->format('d/m/Y H:i:s'));  
                
            });
        })->download($type);
    }

    public function index_responsable($can_id)
    {

        $users = DB::table('responsables')->where('establecimiento_id',Auth::user()->id_establecimiento)->where('can_id', $can_id)->where('rol','>',2)->where('rol','<',6)->orderby('rol','asc')->where('estado',1)->get();
        
        for($i=0;$i<7;$i++){
            for($j=0;$j<3;$j++){
                $user[$i][$j]="";    
            }
        }
       
        foreach ($users as $key => $usuario) {
            switch ($usuario->rol) {
                case 3:
                    switch ($usuario->servicio_id) {
                        case 1: $user[2][0]=$usuario->nombre;
                                $user[2][1]=$usuario->grado;
                                $user[2][2]=$usuario->id;
                            break;
                        
                        case 2: $user[5][0]=$usuario->nombre;
                                $user[5][1]=$usuario->grado;
                                $user[5][2]=$usuario->id;
                            break;

                        case 3: $user[3][0]=$usuario->nombre;
                                $user[3][1]=$usuario->grado;
                                $user[3][2]=$usuario->id;
                            break;

                        case 4: $user[4][0]=$usuario->nombre;
                                $user[4][1]=$usuario->grado;
                                $user[4][2]=$usuario->id;
                            break;

                        case 5: $user[6][0]=$usuario->nombre;
                                $user[6][1]=$usuario->grado;
                                $user[6][2]=$usuario->id;
                            break;

                    }
                    break;
                
                case 4:
                        $user[1][0]=$usuario->nombre;
                        $user[1][1]=$usuario->grado;
                        $user[1][2]=$usuario->id;
                    break;

                case 5:
                        $user[0][0]=$usuario->nombre;
                        $user[0][1]=$usuario->grado;
                        $user[0][2]=$usuario->id;
                    break;
            }
            
        }
        
        $can_ultimo = DB::table('can_sigesapols')                
                ->where('id',$can_id)
                ->orderby('id','Desc')                
                ->get();    

        $model_quimico_can = new CanQuimico();
        $cans = $model_quimico_can->ShowAll(Auth::user()->id_establecimiento);

        $can_id_ultimo=$can_ultimo[0]->id;

      
        return view('frontend.cans.site.quimico.responsables')
            ->with('user', $user)
            ->with('establecimiento_id', Auth::user()->establecimiento_id)
            ->with('cans', $cans)
            ->with('can_id', $can_id)
            ->with('can_id_ultimo', $can_id_ultimo); 

        
    }

    public function create_responsable($can_id,$valor_id)
    {
        $establecimiento = DB::table('establecimientos')->get();
        $establecimiento_id=Auth::user()->id_establecimiento;
        //$grado=Grado::pluck('descripcion','id')->all();
        $grado = DB::table('tipo_grados')->get();
        $grado_id = 0;

        $tipo=1; /// Crear
        $num_can=DB::table('can_sigesapols')->where('estado_activo',1)->count();
        
        return view('frontend.cans.site.quimico.create_responsable',compact(["establecimiento","establecimiento_id","grado","grado_id","tipo","num_can","valor_id","can_id"]));
    }

    public function create_responsable_servicios($can_id,$servicio_id,$establecimiento_id)
    {
        $establecimiento = DB::table('establecimientos')->get();
        $establecimiento_id=Auth::user()->id_establecimiento;
        //$grado=Grado::pluck('descripcion','id')->all();
        $grado = DB::table('tipo_grados')->get();
        $grado_id = 0;
        $tipo=1; /// Crear
        $num_can=DB::table('can_sigesapols')->where('estado_activo',1)->count();
        
        return view('frontend.cans.site.quimico.create_servicio',compact(["establecimiento","establecimiento_id","servicio_id","grado","tipo","num_can","can_id","grado_id"]));
    }

    public function store_responsable(Request $request)
    {
        
        
        $name=strtoupper( $request->input("nombres")." ".$request->input("apellido_paterno")." ".$request->input("apellido_materno") ) ;
        $dni=$request->input("dni") ;
        $nombres=strtoupper( $request->input("nombres") ) ;
        $apellido_paterno=strtoupper( $request->input("apellido_paterno") ) ;
        $apellido_materno=strtoupper( $request->input("apellido_materno") ) ;
        $establecimiento_id=Auth::user()->id_establecimiento;
        $establecimiento = Establecimiento::findOrFail($establecimiento_id);
        $nombre_establecimiento=$establecimiento->nombre_establecimiento;
        $servicio_id=$request->input("servicio_id");
        $rol=$request->input("rol_id");
        //$grado_id=0;
        //$grado=$request->input("grado");
        $grado_id=$request->input("grado");;
        $grados = DB::table('tipo_grados')
                ->where('id',$grado_id)
                ->get();
        
        $grado=$grados[0]->descripcion; 

        $nivel=$establecimiento->nivel;

        $cans=DB::table('can_sigesapols')->where('estado_activo',1)->first();

        //$cans= Can::latest('id')->first();
        $can_id=$cans->id;
        
        switch ($rol) {
            case '3':
                    switch ($servicio_id) {
                        case '1':  $nombre_servicio='Productos Farmacetuticos';break;
                        case '3':  $nombre_servicio='Material Biomedico, instrumental quirurgico y productos afines';break;
                        case '4':  $nombre_servicio='Material e Insumos dentales';break;
                        case '5':  $nombre_servicio='Material Fotografico y Fonotecnico';break;
                        case '6':  $nombre_servicio='Material e insumos de Laboratorio';break;
                        case '2':  $nombre_servicio='Material e insumos de Laboratorio'; break;
                    }
                    break;
            case '4':$nombre_servicio='JEFE Y/O RESPONSABLE DE FARMACIA'; $servicio_id=6; break;
            case '5':$nombre_servicio='JEFE IPRESS'; $servicio_id=6; break;
        }

        
        DB::table('responsables')
                ->insert([
                        'nombre' => $name,
                        'nombres' => $nombres,
                        'apellido_paterno'=>$apellido_paterno,
                        'apellido_materno'=>$apellido_materno,
                        'dni' => $dni,
                        'telefono'=>'999999999',
                        'establecimiento_id'=>$establecimiento_id,
                        'nombre_establecimiento'=>$nombre_establecimiento,
                        'grado_id'=>$grado_id,                        
                        'grado'=>$grado,
                        'rol'=>$rol,                        
                        'can_id'=> $can_id,
                        'etapa'=>1, 
                        'cip'=>'12345678',                        
                        'servicio_id'=>$servicio_id,
                        'nombre_servicio'=>$nombre_servicio,
                        "created_at"=>Carbon::now(),
                        "updated_at"=>Carbon::now()
                    ]);

        
        return redirect('/responsables/'.$can_id);
    }

    public function edit_responsable($id,$valor_id)
    {
        
        $cans=DB::table('can_sigesapols')->where('estado_activo',1)->first();
        $user=DB::table('responsables')->where('id',$id)->first();
        

        if (empty($user)) {
            Flash::error('Usuario no encontrado');
            return redirect('/responsables/'.$cans->id);
        }
        
        //$establecimiento_id=Establecimiento::pluck('nombre_establecimiento','id');
        $establecimiento = DB::table('establecimientos')->get();
        $establecimiento_id = $user->establecimiento_id;

        $grado = DB::table('tipo_grados')->get();
        $grado_id = $user->grado_id;
                
        $nivel=Establecimiento::find($user->establecimiento_id);
        
        $tipo=2; //editar        

        
        $num_can=DB::table('can_sigesapols')->where('estado_activo',1)->count();
        if($nivel->nivel>1){
            $model_rubros= new Servicio();            
            $servicio_id = $user->servicio_id;           
            $servicio = $model_rubros->getServicio($establecimiento_id);
            $muestra=1;
            $rubro_id = $user->rol;   
        }
        else
        {
            $servicio = collect(['id' => '0','nombre_servicio' => 'NO APLICA']);
            $servicio_id = 0;           
            $muestra=0;
            $rubro_id = 7; 
        }

        $rol_id = $user->rol;  
        $can_id=$user->can_id;
        
        return view('frontend.cans.site.quimico.edit_responsable')->with('user', $user)->with('establecimiento',$establecimiento)->with('servicio',$servicio)->with('tipo',$tipo)->with('servicio_id',$servicio_id)->with('num_can',$num_can)->with('establecimiento_id',$establecimiento_id)->with('muestra',$muestra)->with('rol_id',$rol_id)->with('nivel',$nivel->nivel)->with('valor_id',$valor_id)->with('can_id',$can_id)->with('grado',$grado)->with('grado_id',$grado_id);
    }

    public function edit_responsable_servicio($id,$valor_id)
    {
        $user = $this->userRepository->findWithoutFail($id);
        
        if (empty($user)) {
            Flash::error('Usuario no encontrado');

            return redirect(route('users.index'));
        }
        
        //$establecimiento_id=Establecimiento::pluck('nombre_establecimiento','id');
        $establecimiento = DB::table('establecimientos')->get();
        $establecimiento_id = $user->establecimiento_id;
                
        $nivel=Establecimiento::find($user->establecimiento_id);
        
        $tipo=2; //editar  

        $grado = DB::table('grados')->get();
        $grado_id = $user->grado_id;      

        $num_can=DB::table('cans')->where('active',TRUE)->count();
        if($nivel->nivel_id>1){
            $model_rubros= new Servicio();            
            $servicio_id = $user->servicio_id;           
            $servicio = $model_rubros->getServicio($establecimiento_id);
            $muestra=1;
            $rubro_id = $user->rol;   
        }
        else
        {
            $servicio = collect(['id' => '0','nombre_servicio' => 'NO APLICA']);
            $servicio_id = 0;           
            $muestra=0;
            $rubro_id = 7; 
        }

        $rol_id = $user->rol;  
        $cans= Can::latest('id')->first();
        $can_id=$cans->id;

        

        return view('site.users.edit_responsable_servicio')->with('user', $user)->with('establecimiento',$establecimiento)->with('servicio',$servicio)->with('tipo',$tipo)->with('servicio_id',$servicio_id)->with('num_can',$num_can)->with('establecimiento_id',$establecimiento_id)->with('muestra',$muestra)->with('rol',$rol_id)->with('nivel',$nivel->nivel_id)->with('valor_id',$valor_id)->with('can_id',$can_id)->with('grado',$grado)->with('grado_id',$grado_id);
    }

    public function store_responsable_servicios(Request $request)
    {
        //Concatenamos  el nombre y apellido en mayuscula
        $name=strtoupper( $request->input("nombres")." ".$request->input("apellido_paterno")." ".$request->input("apellido_materno") ) ;
        $dni=$request->input("dni") ;
        $nombres=strtoupper( $request->input("nombres") ) ;
        $apellido_paterno=strtoupper( $request->input("apellido_paterno") ) ;
        $apellido_materno=strtoupper( $request->input("apellido_materno") ) ;
        
        $establecimiento_id=Auth::user()->id_establecimiento;
        $establecimiento = Establecimiento::findOrFail($establecimiento_id);
        $nombre_establecimiento=$establecimiento->nombre_establecimiento;
        //$grado_id=0;
        //$grado=$request->input("grado");
        $grado_id=$request->input("grado");;
        $grados = DB::table('grados')
                ->where('id',$grado_id)
                ->get();
        $grado=$grados[0]->descripcion; 
        
        $cip=$request->input("cip") ;
        $servicio_id=$request->input("servicio_id");    
                 

        $cans=DB::table('can_sigesapols')->where('estado_activo',1)->first();
        $can_id=$cans->id;
        
        //dd($servicio_id);
        if($servicio_id==0){
            $telefono='999999999';
            $email=$dni.'@gmail.com';
            $password= bcrypt( $dni ); 
            $rol=9;
            $nombre_servicio='JEFE IPRESS';
        }
        else
        {
            $telefono=$request->input("telefono");
            $email=$request->input("email");
            $password= bcrypt( $request->input("password") ); 
            $rol=2;
            $rubro = DB::table('consultorios')
                ->where('id',$servicio_id)
                ->get();
            $nombre_servicio=$rubro[0]->nombre;   
        }

        $users = DB::table('users')->where('email',$email)->get();
        
        if(count($users)==0):
                
                DB::table('users') 
                        ->insert([
                                'name' => $name,
                                'dni' => $dni,
                                'nombres' => $nombres,
                                'apellido_paterno'=>$apellido_paterno,
                                'apellido_materno'=>$apellido_materno,
                                'telefono'=>$telefono,
                                'establecimiento_id'=>$establecimiento_id,
                                'nombre_establecimiento'=>$nombre_establecimiento,
                                'grado_id'=>$grado_id,
                                'email'=>$email,
                                'grado'=>$grado,
                                'rol'=>$rol,
                                'cip'=>$cip,
                                'password'=>$password,
                                'servicio_id'=>$servicio_id,
                                'nombre_servicio'=>$nombre_servicio,
                                
                                "created_at"=>Carbon::now(),
                                "updated_at"=>Carbon::now()
                            ]);

                DB::table('responsables')
                        ->insert([
                                'nombre' => $name,
                                'nombres' => $nombres,
                                'apellido_paterno'=>$apellido_paterno,
                                'apellido_materno'=>$apellido_materno,
                                'dni' => $dni,
                                'telefono'=>$telefono,
                                'establecimiento_id'=>$establecimiento_id,
                                'nombre_establecimiento'=>$nombre_establecimiento,
                                'grado_id'=>$grado_id,                        
                                'grado'=>$grado,
                                'rol'=>$rol,                        
                                'can_id'=> $can_id,
                                'etapa'=>1, 
                                'cip'=>$cip,                        
                                'servicio_id'=>$servicio_id,
                                'nombre_servicio'=>$nombre_servicio,
                                "created_at"=>Carbon::now(),
                                "updated_at"=>Carbon::now()
                            ]);
                
                $usuarios= User::latest('id')->first();
                $user_id=$usuarios->id;
        
                Flash::success('Usuario grabado con exito.');
                return redirect(route('farmacia_servicios.listar_responsables_servicios',[$can_id]));
        else:
                $password= bcrypt( $request->input("password") ); 
                        DB::table('users')
                            ->where('id', $users->get(0)->id )
                            ->update([
                                    'telefono'=>$telefono,
                                    'establecimiento_id'=>$establecimiento_id,
                                    'nombre_establecimiento'=>$nombre_establecimiento,
                                    'grado_id'=>$grado_id,
                                    'email'=>$email,
                                    'rol'=>$rol,
                                    'cip'=>$cip,
                                    'grado'=>$grado,
                                    'password'=>$password,
                                    'servicio_id'=>$servicio_id,
                                    'nombre_servicio'=>$nombre_servicio,
                                    'estado'=>1,
                                ]);

                DB::table('responsables')
                        ->insert([
                                'nombre' => $name,
                                'nombres' => $nombres,
                                'apellido_paterno'=>$apellido_paterno,
                                'apellido_materno'=>$apellido_materno,
                                'dni' => $dni,
                                'telefono'=>$telefono,
                                'establecimiento_id'=>$establecimiento_id,
                                'nombre_establecimiento'=>$nombre_establecimiento,
                                'grado_id'=>$grado_id,                        
                                'grado'=>$grado,
                                'rol'=>$rol,                        
                                'can_id'=> $can_id,
                                'etapa'=>1, 
                                'cip'=>$cip,                        
                                'servicio_id'=>$servicio_id,
                                'nombre_servicio'=>$nombre_servicio,
                                "created_at"=>Carbon::now(),
                                "updated_at"=>Carbon::now()
                            ]);
                
                $usuarios= User::latest('id')->first();
                $user_id=$usuarios->id;

                Flash::error('El email del usuario ya ha sido registrado en el sistema.');
                return redirect(route('farmacia_servicios.listar_responsables_servicios',[$can_id]));
        endif;

        
    }

    public function update_responsable(Request $request)
    {
        
        $id = $request->id;

        $cans=DB::table('can_sigesapols')->where('estado_activo',1)->first();
        $user=DB::table('responsables')->where('id',$id)->first();

        if (empty($user)) {
            Flash::error('Usuario no encontrado');
            return redirect(route('users.index'));
        }
        
        $name=strtoupper( $request->input("nombres")." ".$request->input("apellido_paterno")." ".$request->input("apellido_materno") ) ;
        $dni=$request->input("dni") ;
        $nombres=strtoupper( $request->input("nombres") ) ;
        $apellido_paterno=strtoupper( $request->input("apellido_paterno") ) ;
        $apellido_materno=strtoupper( $request->input("apellido_materno") ) ;
        $grado_id=$request->input("grado");;
        $grados = DB::table('tipo_grados')
                ->where('id',$grado_id)
                ->get();
        $grado=$grados[0]->descripcion; 
        $rol=$request->input("rol_id");
        $servicio_id=$request->input("servicio_id");  
        
        
        switch ($rol) {
            case '3':
                    switch ($servicio_id) {
                        case '1':  $nombre_servicio='Productos Farmacetuticos';break;
                        case '3':  $nombre_servicio='Material Biomedico, instrumental quirurgico y productos afines';break;
                        case '4':  $nombre_servicio='Material e Insumos dentales';break;
                        case '5':  $nombre_servicio='Material Fotografico y Fonotecnico';break;
                        case '6':  $nombre_servicio='Material e insumos de Laboratorio';break;
                        case '2':  $nombre_servicio='Material e insumos de Laboratorio'; break;
                    }
                    break;
            case '4':$nombre_servicio='JEFE Y/O RESPONSABLE DE FARMACIA'; $servicio_id=6; break;
            case '5':$nombre_servicio='JEFE IPRESS'; $servicio_id=6; break;

        }
 
        DB::table('responsables')
            ->where('id', $id )
                ->update([
                    'nombre' => $name,
                    'nombres' => $nombres,
                    'apellido_paterno'=>$apellido_paterno,
                    'apellido_materno'=>$apellido_materno,
                    'dni' => $dni,
                    'grado_id'=>$grado_id,
                    'grado'=>$grado,
                    'rol'=>$rol,                    
                    'servicio_id'=>$servicio_id,
                    'nombre_servicio'=>$nombre_servicio,
                    "created_at"=>Carbon::now(),
                    "updated_at"=>Carbon::now()
                ]);
        
        return redirect('/responsables/'.$cans->id);
    }

    public function atencion_consultorios($can_id){

        $establecimiento_id = Auth::user()->id_establecimiento;
        $establecimiento = Establecimiento::find($establecimiento_id);

        $establecimiento_cerrado=DB::table('monitoreo_cans') ->where('id_can',$can_id)->where('id_establecimiento',$establecimiento_id)->get();  

        if($establecimiento_id == 79){
            $tipo = 2; 
        }
        else{
            $tipo = 1;     
        }
        

        $medicina_interna = $establecimiento_cerrado[0]->medicina_interna; 
        $odontologia = $establecimiento_cerrado[0]->odontologia; 
        $obstetricia = $establecimiento_cerrado[0]->obstetricia;
        $id_monitoreo = $establecimiento_cerrado[0]->id; 
        $user = Auth::user()->id;
        $redirigir = 1; //home
       return view('frontend.cans.site.atenciones.edit')->with('id_can', $can_id)->with('tipo', $tipo)->with('id_establecimiento', $establecimiento_id)->with('establecimiento', $establecimiento)->with('medicina_interna', $medicina_interna)->with('odontologia', $odontologia)->with('obstetricia', $obstetricia)->with('id_user', $user)->with('id_monitoreo', $id_monitoreo)->with('redirigir', $redirigir);
    }

    public function ver_consolidado_farmacia($tipo,$can_id)
    {       
        
        $establecimiento_id=Auth::user()->id_establecimiento;

        $establecimiento = Establecimiento::find($establecimiento_id);

        $servicio_id=Auth::user()->id_consultorio;

        $nivel=$establecimiento->nivel;

        $can = DB::table('can_sigesapols')                
                ->where('id',$can_id)
                ->orderby('id','Desc')                
                ->first();    

        $stock_cerrado=DB::table('monitoreo_cans')
                    ->where('id_can',$can_id) //cambiar 22
                    ->where('id_establecimiento',$establecimiento_id)//cambiar 1
                    ->get();  
        
        if($tipo==1){
            $data=DB::table('can_ipress')
                    ->where('necesidad_anual','>',0)
                    ->where('id_can',$can_id) //cambiar 22
                    ->where('id_tipo_dispositivo',1)   
                    ->where('estado','<>',2)        
                    ->where('petitorio','=',1)        
                    ->where('id_establecimiento',$establecimiento_id)//cambiar 1
                    ->orderby ('id_tipo_dispositivo','asc')   
                    ->get();
            
            $num_estimaciones=DB::table('can_ipress')
                    ->where('necesidad_anual','>',0)
                    ->where('id_can',$can_id) //cambiar 22
                    ->where('id_tipo_dispositivo',1)
                    ->where('estado_necesidad',0)                              
                    ->where('id_establecimiento',$establecimiento_id)//cambiar 1
                    ->count();

            $descripcion_tipo='Medicamentos';
            $cerrado=$stock_cerrado[0]->medicamento_cerrado;
        }else
            {   if ($tipo==2) {
                    $data=DB::table('can_ipress')
                    ->where('necesidad_anual','>',0)
                    ->where('id_can',$can_id) //cambiar 22
                    ->where('id_tipo_dispositivo','>',1)
                    ->where('estado','<>',2)  
                    ->where('petitorio','=',1)        
                    ->where('id_establecimiento',$establecimiento_id)//cambiar 1
                    ->orderby ('id_tipo_dispositivo','asc')   
                    ->get();  

                    $num_estimaciones=DB::table('can_ipress')
                    ->where('necesidad_anual','>',0)
                    ->where('id_can',$can_id) //cambiar 22
                    ->where('id_tipo_dispositivo','>',1)
                    ->where('estado','<>',2) 
                    ->where('petitorio','=',1)        
                    ->where('id_establecimiento',$establecimiento_id)//cambiar 1
                    ->count();  

                    $cerrado=$stock_cerrado[0]->dispositivo_cerrado;
                    $descripcion_tipo='Dispositivos';
                }
        }
        
            $condicion_boton=2; //no ver
            $servicio_id_cero=0;
            $cerrado_stock=1;
            $actualizado = 1; //$stock_cerrado[0]->actualizacion;
        return view('frontend.cans.site.quimico.descargar_medicamentos')
            ->with('condicion_boton', $condicion_boton)
            ->with('servicio_id', $servicio_id_cero)
            ->with('estimacions', $data)
            ->with('cerrado', $cerrado)
            ->with('nivel', $nivel)
            ->with('cerrado_stock', $cerrado_stock)            
            ->with('num_estimaciones', $num_estimaciones)
            ->with('descripcion', $descripcion_tipo)
            ->with('establecimiento_id', $establecimiento_id)
            ->with('tipo', $tipo)
            ->with('actualizado', $actualizado)
            ->with('can_id', $can_id);
    }


    public function pdf_estimacion_nivel1($can_id,$establecimiento_id,$tipo,$servicio_id,$ano)
    {
        
        $establecimiento = Establecimiento::find($establecimiento_id);        

        $nivel=$establecimiento->nivel;

        $can = Can::find($can_id);

        if($tipo==1){
            
            $data=DB::table('can_ipress')
                    ->where('necesidad_anual','>',0)
                    ->where('id_can',$can_id) //cambiar 22
                    ->where('id_tipo_dispositivo',1)
                    ->where('estado','<>',2)
                    ->where('petitorio','=',1)
                    ->where('id_establecimiento',$establecimiento_id)//cambiar 1
                    ->orderby ('id_tipo_dispositivo','asc')  
                    ->orderby ('descripcion','asc')  
                    ->get();

            $num_estimaciones=DB::table('can_ipress')
                    ->where('necesidad_anual','>',0)
                    ->where('id_can',$can_id) //cambiar 22
                    ->where('id_tipo_dispositivo',1)                    
                    ->where('estado','<>',2)
                    ->where('petitorio','=',1)
                    ->where('id_establecimiento',$establecimiento_id)//cambiar 1
                    ->count();

            $total_tipo_productos=DB::table('can_ipress')
                    ->select('id_tipo_dispositivo')
                    ->where('id_can',$can_id) //cambiar 22
                    ->where('id_tipo_dispositivo',1)                        
                    ->where('estado','<>',2)
                    ->where('petitorio','=',1)
                    ->where('id_establecimiento',$establecimiento_id)//cambiar 1
                    ->groupby('id_tipo_dispositivo')
                    ->get();

            $descripcion_tipo='Medicamentos';
        }else
            {   if ($tipo==2) {
                    $data=DB::table('can_ipress')
                    ->where('necesidad_anual','>',0)
                    ->where('id_can',$can_id) //cambiar 22
                    ->where('id_tipo_dispositivo','>',1)                    
                    ->where('id_establecimiento',$establecimiento_id)//cambiar 1
                    ->where('estado','<>',2)
                    ->where('petitorio','=',1)
                    ->orderby ('id_tipo_dispositivo','asc')  
                    ->orderby ('descripcion','asc')  
                    ->get();                    

                    $num_estimaciones=DB::table('can_ipress')
                        ->where('necesidad_anual','>',0)
                        ->where('id_can',$can_id) //cambiar 22
                        ->where('id_tipo_dispositivo','>',1)
                        ->where('estado','<>',2)
                        ->where('petitorio','=',1)
                        ->where('id_establecimiento',$establecimiento_id)//cambiar 1
                        ->count();

                    $total_tipo_productos=DB::table('can_ipress')
                        ->select('id_tipo_dispositivo')
                        ->where('id_can',$can_id) //cambiar 22
                        ->where('id_tipo_dispositivo','>',1)                        
                        ->where('id_establecimiento',$establecimiento_id)//cambiar 1
                        ->where('estado','<>',2)
                        ->where('petitorio','=',1)
                        ->groupby('id_tipo_dispositivo')
                        ->orderby ('id_tipo_dispositivo','asc')                           
                        ->get();                    

                    $descripcion_tipo='Dispositivos';
                }
        }

        $responsable[]="";$j=0;$r=0;
        $model_responsables = new CanResponsable();
        //$user=DB::table('responsables')->where('id',$id)->first();
        $model_estimacion = new CanMonitoreo();
                    
                  
        if($nivel==1):
            $jefe_ipress = $model_responsables->GetJefeResponsable($can_id, $establecimiento_id, 6, 5); 
            
            if (count($jefe_ipress)>0):
                
                $responsable[1]=$jefe_ipress[0]->nombre;
                $responsable[4]=$jefe_ipress[0]->grado;

                $jefe_farmacia = $model_responsables->GetJefeResponsable($can_id, $establecimiento_id, 6, 4);

                if (count($jefe_farmacia)>0): 
                    $responsable[0]=$jefe_farmacia[0]->nombre;
                    $responsable[3]=$jefe_farmacia[0]->grado;
                    
                    $registro_farmacia = $model_responsables->GetJefeResponsable($can_id, $establecimiento_id, 0, 7);
                    $name=$responsable[2]=Auth::user()->name;
                    $responsable[5]=Auth::user()->grado;
                    $cip=Auth::user()->cip;
                    $dni=Auth::user()->cip;
                    
                    $responsables = $model_responsables->GetBuscaResponsable($can_id, $establecimiento_id);

                    if (count($responsables)>0):
                        if($establecimiento_id!=79 && $tipo==1):
                            $nombre_rubro='MEDICAMENTOS';
                            $total_medicamentos = $model_estimacion->GetProductosTipoRegistrados($can_id, $establecimiento_id,1);
                            $responsable_medicamentos = $model_responsables->GetJefeResponsable($can_id, $establecimiento_id, 1, 3);
                            
                            if (count($total_medicamentos)>0):
                                if (count($responsable_medicamentos)>0):
                                    $responsable_rubro[0]=$responsable_medicamentos[0]->nombre;
                                    $responsable_rubro[6]=$responsable_medicamentos[0]->grado;
                                    $cierre_rubro=DB::table('monitoreo_cans')->where('id_can',$can_id)->where('id_establecimiento',$establecimiento_id)->get();
                                    $cierre=$cierre_rubro[0]->updated_at;                  
                                     $nombre_archivo=$establecimiento->nombre_establecimiento.'_'.$nombre_rubro.'_CAN_UGPFDMPS';
                                     
                                    $texto='CONSOLIDADO IPRESS';
                                    
                                    if($ano==1):
                                        
                                        $pdf = \PDF::loadView('frontend.cans.pdf.descargar_rubro_servicio_pdf',['estimaciones'=>$data,
                                  'establecimiento_id'=>$establecimiento_id,'descripcion_tipo' =>$descripcion_tipo,
                                  'nombre_establecimiento'=>$establecimiento->nombre,'responsable_name'=>$name,'nombre_rubro'=>$nombre_rubro,'cierre'=>$cierre,'cip'=>$cip,'dni'=>$dni,'texto'=>$texto,'nivel'=>$nivel,'tipo'=>$tipo,'can_id'=>$can_id,'responsables'=>$responsables,'responsable_rubro'=>$responsable_rubro,'responsable'=>$responsable]);

                                    endif;
                                    if($ano==2):
                                        $pdf = \PDF::loadView('frontend.cans.pdf.descargar_rubro_servicio_2_pdf',['estimaciones'=>$data,
                                  'establecimiento_id'=>$establecimiento_id,'descripcion_tipo' =>$descripcion_tipo,
                                  'nombre_establecimiento'=>$establecimiento->nombre_establecimiento,'responsable_name'=>$name,'nombre_rubro'=>$nombre_rubro,'cierre'=>$cierre,'cip'=>$cip,'dni'=>$dni,'texto'=>$texto,'nivel'=>$nivel,'tipo'=>$tipo,'can_id'=>$can_id,'responsables'=>$responsables,'responsable_rubro'=>$responsable_rubro,'responsable'=>$responsable]);
                                    endif;
                                    if($ano==3):
                                        $pdf = \PDF::loadView('frontend.cans.pdf.descargar_rubro_servicio_3_pdf',['estimaciones'=>$data,
                                  'establecimiento_id'=>$establecimiento_id,'descripcion_tipo' =>$descripcion_tipo,
                                  'nombre_establecimiento'=>$establecimiento->nombre_establecimiento,'responsable_name'=>$name,'nombre_rubro'=>$nombre_rubro,'cierre'=>$cierre,'cip'=>$cip,'dni'=>$dni,'texto'=>$texto,'nivel'=>$nivel,'tipo'=>$tipo,'can_id'=>$can_id,'responsables'=>$responsables,'responsable_rubro'=>$responsable_rubro,'responsable'=>$responsable]);

                                    endif;
                                    

                                    $pdf->setPaper('A4', 'landscape');
                                    $pdf->getDomPDF()->set_option("enable_php", true);
                                    return $pdf->stream($nombre_archivo.'.pdf');
                                    
                                    
                                else:
                                    Flash::error('Falto Ingresar al Personal Responsables de Medicamentos');
                                    return redirect(route('users.index_responsable',[$can_id]));
                                endif;
                            else:
                                Flash::error('Debe Ingresar Productos Farmaceuticos');
                                return redirect(route('users.index_responsable',[$can_id]));
                                
                            endif;
                        else:
                            $nombre_rubro='DISPOSITIVOS';
                            $total_biomedico_1 = $model_estimacion->GetProductosTipoRegistrados($can_id, $establecimiento_id,2);
                            $total_biomedico_2 = $model_estimacion->GetProductosTipoRegistrados($can_id, $establecimiento_id,3);
                            $total_biomedico_3 = $model_estimacion->GetProductosTipoRegistrados($can_id, $establecimiento_id,7);

                            $total_biomedico = (count($total_biomedico_1) + count($total_biomedico_2) + count($total_biomedico_3));
                            if ($total_biomedico>0):
                                $responsable_biomedicos = $model_responsables->GetJefeResponsable($can_id, $establecimiento_id, 3, 3);
                                if (count($responsable_biomedicos)==0):
                                    Flash::error('Tiene Productos de Biomedicos, Falto Ingresar al Personal Responsable');
                                    return redirect(route('users.index_responsable',[$can_id]));
                                endif;
                            endif;

                            $total_dental = $model_estimacion->GetProductosTipoRegistrados($can_id, $establecimiento_id,4);
                            if (count($total_dental)>0):
                                $responsable_dental = $model_responsables->GetJefeResponsable($can_id, $establecimiento_id, 4, 3);
                                if (count($responsable_dental)==0):
                                    Flash::error('Tiene Productos Odontológico , Falto Ingresar al Personal Responsable');
                                    return redirect(route('users.index_responsable',[$can_id]));
                                endif;
                            endif;

                            $total_fotografico = $model_estimacion->GetProductosTipoRegistrados($can_id, $establecimiento_id,6);
                            if (count($total_fotografico)>0):
                                $responsable_fotografico = $model_responsables->GetJefeResponsable($can_id, $establecimiento_id, 5, 3);
                                if (count($responsable_fotografico)==0):
                                    Flash::error('Tiene Productos de Fotográficos y Fonotécnicos, falto Ingresar al Personal Responsable');
                                    return redirect(route('users.index_responsable',[$can_id]));
                                endif;
                            endif;

                            $total_laboratorio_1 = $model_estimacion->GetProductosTipoRegistrados($can_id, $establecimiento_id,5);
                            $total_laboratorio_2 = $model_estimacion->GetProductosTipoRegistrados($can_id, $establecimiento_id,10);
                            $total_laboratorio = (count($total_laboratorio_1) + count($total_laboratorio_2));
                            if ($total_laboratorio>0):
                                $responsable_laboratorio = $model_responsables->GetJefeResponsable($can_id, $establecimiento_id, 2, 3);
                                if (count($responsable_laboratorio)==0):
                                    Flash::error('Tiene Productos de Laboratorio, falto Ingresar al Personal Responsable');
                                    return redirect(route('users.index_responsable',[$can_id]));
                                endif;
                            endif;
                            $pf=0; $d=0;
                            $responsables_rub = DB::table('responsables')->where('establecimiento_id',Auth::user()->id_establecimiento)->where('rol','>',2)->where('rol','<=',9)->where('can_id',$can_id)->where('estado',1)->orderby('rol','asc')->get();
                            
                            foreach ($responsables_rub as $key => $resp_rubro) {
                                switch ($resp_rubro->servicio_id) {
                                    case 1: //productos (1)
                                        if($nivel==1):
                                            $responsable_rubro[0]=$resp_rubro->nombre;
                                            $responsable_rubro[6]=$resp_rubro->grado;
                                            $pf++;                        
                                        else:
                                            if($resp_rubro->rol==7):
                                                $responsable_rubro[0]=$resp_rubro->nombre;
                                                $responsable_rubro[6]=$resp_rubro->grado;
                                                $pf++;
                                            endif;
                                        endif;
                                        break;
                                    case 2: //insumos laboratorio (5,10)
                                        if($nivel==1):
                                            $responsable_rubro[1]=$resp_rubro->nombre;
                                            $responsable_rubro[7]=$resp_rubro->grado;
                                            $d++;
                                        else:
                                            if($resp_rubro->rol==3):
                                                $responsable_rubro[1]=$resp_rubro->nombre;
                                                $responsable_rubro[7]=$resp_rubro->grado;
                                                $d++;
                                            endif;
                                        endif;
                                        break;
                                    case 3://biomedico,quirurgico,afines (2,3,7)
                                        if($nivel==1):
                                            $responsable_rubro[2]=$resp_rubro->nombre;
                                            $responsable_rubro[8]=$resp_rubro->grado;
                                            $d++;
                                        else:
                                            if($resp_rubro->rol==3):
                                                $responsable_rubro[2]=$resp_rubro->nombre;
                                                $responsable_rubro[8]=$resp_rubro->grado;
                                                $d++;   
                                            endif;
                                        endif;
                                        break;
                                    case 4://dentales (4)
                                        if($nivel==1):
                                            $responsable_rubro[3]=$resp_rubro->nombre;
                                            $responsable_rubro[9]=$resp_rubro->grado;
                                            $d++;
                                        else:
                                            if($resp_rubro->rol==3):
                                                $responsable_rubro[3]=$resp_rubro->nombre;
                                                $responsable_rubro[9]=$resp_rubro->grado;
                                                $d++;
                                            endif;
                                        endif;
                                        break;
                                    case 5://fotografico (6)
                                        if($nivel==1):
                                            $responsable_rubro[4]=$resp_rubro->nombre;
                                            $responsable_rubro[10]=$resp_rubro->grado;
                                            $d++;
                                        else:
                                            if($resp_rubro->rol==3):
                                                $responsable_rubro[4]=$resp_rubro->nombre;
                                                $responsable_rubro[10]=$resp_rubro->grado;
                                                $d++;
                                            endif;
                                        endif;
                                        break;
                                }
                            }

                            $cierre_rubro=DB::table('monitoreo_cans')->where('id_can',$can_id)->where('id_establecimiento',$establecimiento_id)->get();
                            $cierre=$cierre_rubro[0]->updated_at;                  
                             $nombre_archivo=$establecimiento->nombre_establecimiento.'_'.$nombre_rubro.'_CAN_UGPFDMPS';
                             
                            $texto='CONSOLIDADO IPRESS';
                            if($ano==1):
                                $pdf = \PDF::loadView('frontend.cans.pdf.descargar_rubro_servicio_pdf',['estimaciones'=>$data,
                                'establecimiento_id'=>$establecimiento_id,'descripcion_tipo' =>$descripcion_tipo,
                                'nombre_establecimiento'=>$establecimiento->nombre_establecimiento,'responsable_name'=>$name,'nombre_rubro'=>$nombre_rubro,'cierre'=>$cierre,'cip'=>$cip,'dni'=>$dni,'texto'=>$texto,'nivel'=>$nivel,'tipo'=>$tipo,'can_id'=>$can_id,'responsables'=>$responsables,'responsable_rubro'=>$responsable_rubro,'responsable'=>$responsable]); 
                                
                            endif;
                            if($ano==2):
                                $pdf = \PDF::loadView('frontend.cans.pdf.descargar_rubro_servicio_2_pdf',['estimaciones'=>$data,
                                'establecimiento_id'=>$establecimiento_id,'descripcion_tipo' =>$descripcion_tipo,
                                'nombre_establecimiento'=>$establecimiento->nombre_establecimiento,'responsable_name'=>$name,'nombre_rubro'=>$nombre_rubro,'cierre'=>$cierre,'cip'=>$cip,'dni'=>$dni,'texto'=>$texto,'nivel'=>$nivel,'tipo'=>$tipo,'can_id'=>$can_id,'responsables'=>$responsables,'responsable_rubro'=>$responsable_rubro,'responsable'=>$responsable]); 
                                
                            endif;
                            if($ano==3):
                                $pdf = \PDF::loadView('frontend.cans.pdf.descargar_rubro_servicio_3_pdf',['estimaciones'=>$data,
                                'establecimiento_id'=>$establecimiento_id,'descripcion_tipo' =>$descripcion_tipo,
                                'nombre_establecimiento'=>$establecimiento->nombre_establecimiento,'responsable_name'=>$name,'nombre_rubro'=>$nombre_rubro,'cierre'=>$cierre,'cip'=>$cip,'dni'=>$dni,'texto'=>$texto,'nivel'=>$nivel,'tipo'=>$tipo,'can_id'=>$can_id,'responsables'=>$responsables,'responsable_rubro'=>$responsable_rubro,'responsable'=>$responsable]); 
                            endif;
                            

                            $pdf->setPaper('A4', 'landscape');
                            $pdf->getDomPDF()->set_option("enable_php", true);
                            return $pdf->stream($nombre_archivo.'.pdf');

                        endif;
                    else:
                        Flash::error('Falta Ingresar Responsables');
                        return redirect(route('users.index_responsable',[$can_id]));    
                    endif;
                else:
                    Flash::error('Falta Ingresar al jefe y/o Responsablde de Farmacia');
                    return redirect(route('users.index_responsable',[$can_id]));    
                endif;
            else:
                Flash::error('Falta Ingresar al jefe de la IPRESS O JEFE');
                return redirect(route('users.index_responsable',[$can_id]));
            endif;
        else:
            
            if($tipo==1):
                $nombre_rubro='MEDICAMENTOS';
                $texto='CONSOLIDADO IPRESS';
            else:
                $nombre_rubro='DISPOSITIVOS';
                $texto='CONSOLIDADO IPRESS';
            endif;

            $registro_farmacia = $model_responsables->GetJefeResponsable($can_id, $establecimiento_id, 0, 7);
            $name=$responsable[2]=Auth::user()->name;
            $responsable[5]=Auth::user()->grado;
            $cip=Auth::user()->cip;
            $dni=Auth::user()->cip;

            $cierre_rubro=DB::table('monitoreo_cans')->where('id_can',$can_id)->where('id_establecimiento',$establecimiento_id)->get();
            $cierre=$cierre_rubro[0]->updated_at;                  
            $nombre_archivo=$establecimiento->nombre_establecimiento.'_'.$nombre_rubro.'_CAN_UGPFDMPS';
            
                if($ano==1):
                    $pdf = \PDF::loadView('site.pdf.descargar_consolidado_farmacia_pdf',['estimaciones'=>$data,
              'establecimiento_id'=>$establecimiento_id,'descripcion_tipo' =>$descripcion_tipo,
              'nombre_establecimiento'=>$establecimiento->nombre_establecimiento,'responsable_name'=>$name,'nombre_rubro'=>$nombre_rubro,'cierre'=>$cierre,'cip'=>$cip,'dni'=>$dni,'texto'=>$texto,'nivel'=>$nivel,'tipo'=>$tipo,'can_id'=>$can_id,'responsable'=>$responsable]); 
                    
                endif;

                if($ano==2):
                    $pdf = \PDF::loadView('site.pdf.descargar_consolidado_farmacia_pdf',['estimaciones'=>$data,
              'establecimiento_id'=>$establecimiento_id,'descripcion_tipo' =>$descripcion_tipo,
              'nombre_establecimiento'=>$establecimiento->nombre_establecimiento,'responsable_name'=>$name,'nombre_rubro'=>$nombre_rubro,'cierre'=>$cierre,'cip'=>$cip,'dni'=>$dni,'texto'=>$texto,'nivel'=>$nivel,'tipo'=>$tipo,'can_id'=>$can_id,'responsable'=>$responsable]); 
                    
                endif;
                if($ano==3):
                    $pdf = \PDF::loadView('site.pdf.descargar_consolidado_farmacia_3_pdf',['estimaciones'=>$data,
              'establecimiento_id'=>$establecimiento_id,'descripcion_tipo' =>$descripcion_tipo,
              'nombre_establecimiento'=>$establecimiento->nombre_establecimiento,'responsable_name'=>$name,'nombre_rubro'=>$nombre_rubro,'cierre'=>$cierre,'cip'=>$cip,'dni'=>$dni,'texto'=>$texto,'nivel'=>$nivel,'tipo'=>$tipo,'can_id'=>$can_id,'responsable'=>$responsable]);
                    
                endif;
                
                 $pdf->setPaper('A4', 'landscape');
                $pdf->getDomPDF()->set_option("enable_php", true);
                return $pdf->stream($nombre_archivo.'.pdf');

        endif;
     }

     public function consolidado_nacional_tipo($can_id,$tipo)
    {
       
        $modelCan=new Can();
        $productos=$modelCan->ConsolidadoCANxNivel($can_id, $tipo);
        
        switch ($tipo) {
            case 1: $descripcion_tipo="MEDICAMENTOS"; break;
            case 2: $descripcion_tipo="MATERIAL BIOMEDICO"; break;
            case 3: $descripcion_tipo="INSTRUMENTAL QUIRURGICO"; break;
            case 4: $descripcion_tipo="MATERIAL E INSUMO ODONTOLOGICO"; break;
            case 5: $descripcion_tipo="INSUMO DE LABORATORIO"; break;
            case 6: $descripcion_tipo="MATERIAL FOTOGRAFICO Y FONOTECNICO"; break;
            case 7: $descripcion_tipo="PRODUCTOS AFINES"; break;
            case 8: $descripcion_tipo="NN"; break;
            case 9: $descripcion_tipo="DISPOSITIVO MEDICO DE USO RESTRINGIDO"; break;
            case 10: $descripcion_tipo="MATERIAL DE LABORATORIO"; break;
            case 11: if($can_id>3){ $descripcion_tipo="INSTRUMENTAL TRAUMATOLOGIA";} break;
            case 12: if($can_id>3){ $descripcion_tipo="MATERIAL TRAUMATOLOGIA";} break;
            case 13: if($can_id>3){ $descripcion_tipo="AYUDAS BIOMECANICAS";} break;
        }
    
        
    return view('frontend.cans.admin.consolidado_nacional')->with('descripcion_tipo', $descripcion_tipo)->with('productos', $productos)->with('can_id',$can_id);
    
    }

    public function consolidado_establecimiento_producto_tipo_dispositivo($id,$tipo_dispositivo)
    {
        
        for($i=0;$i<3205;$i++){
            for($j=0;$j<100;$j++){
                $can_productos[$i][$j]=0;
                $descripcion[$j][0]="";
                $descripcion[$j][1]="";
            }
        }
            
        $consulta = DB::table('can_ipress')
                        ->select(DB::raw('sum(necesidad_anual) as necesidad,sum(necesidad_actual) as necesidad_actual,  can_ipress.id_producto,can_ipress.cod_producto,can_ipress.descripcion, establecimientos.nombre as nombre_establecimiento, establecimientos.id as establecimiento_id,productos.precio_trama as precio'))
                        ->join('establecimientos', 'establecimientos.id', '=', 'can_ipress.id_establecimiento')                        
                        ->join('productos', 'can_ipress.id_producto', '=','productos.id')
                        ->join('tipo_productos', 'productos.id_tipo', '=','tipo_productos.id')
                        ->where('can_ipress.id_can',$id)
                        ->where('can_ipress.id_tipo_dispositivo',$tipo_dispositivo)
                        ->where('can_ipress.necesidad_anual','>',0)
                        ->where('can_ipress.estado','<>',2)
                        ->where('can_ipress.id_servicio','=',0)
                        ->where('can_ipress.petitorio','=',1)
                        ->groupby('can_ipress.id_producto','can_ipress.cod_producto','can_ipress.descripcion','establecimientos.nombre','establecimientos.id','productos.precio_trama')
                        ->orderby('can_ipress.id_producto')
                        ->get();
        
                        
        switch ($tipo_dispositivo) {
            case 1: $descripcion_tipo="PRODUCTOS FARMACEUTICOS"; break;
            case 2: $descripcion_tipo="MATERIAL BIOMEDICO"; break;
            case 3: $descripcion_tipo="INSTRUMENTAL QUIRURGICO"; break;
            case 4: $descripcion_tipo="MATERIAL E INSUMO ODONTOLOGICO"; break;
            case 5: $descripcion_tipo="INSUMO DE LABORATORIO"; break;
            case 6: $descripcion_tipo="MATERIAL FOTOGRAFICO Y FONOTECNICO"; break;
            case 7: $descripcion_tipo="PRODUCTOS AFINES"; break;
            case 9: $descripcion_tipo="DISPOSITIVO MEDICO DE USO RESTRINGIDO"; break;
            case 10: $descripcion_tipo="MATERIAL DE LABORATORIO"; break;
            case 11: $descripcion_tipo="INSTRUMENTAL TRAUMATOLOGIA"; break;
            case 12: $descripcion_tipo="MATERIAL TRAUMATOLOGIA"; break;
            case 13: $descripcion_tipo="AYUDAS BIOMECANICAS"; break;
        }/*
            
        $regions_x = DB::table('establecimientos')
                            ->orderby('establecimientos.id','asc')
                            ->get();
*/
                            $regions_x=DB::table('establecimientos') 
                ->select('establecimientos.*')
        ->where('establecimientos.id_estado_reg',1)
        ->where('establecimientos.nombre','!=','ALMACEN LOGISTICO DIRSAPOL')
        ->where('establecimientos.nombre','!=','SALUDPOL')
                ->orderBy('nombre', 'asc')
                ->get();
        $i=0;

        foreach ($regions_x as $key => $value) {
            $descripcion[$i][0]=$value->id;   
            $descripcion[$i][1]=$value->nombre;   
            $i++;
        }
    
        $fila_anterior=5000; $x=0; $y=0;
        
        foreach ($consulta as $key => $value) {
            
            $fila=$value->id_producto;

            if($fila_anterior!=$fila){
                $fila_anterior=$fila;
                $x++;
            }

            
            for($k=0;$k<$i;$k++){
                if($value->establecimiento_id==$descripcion[$k][0]){
                    $can_productos[$x][$k]=$value->necesidad;                    
                    $can_productos[$x][94]=$can_productos[$x][$k]+$can_productos[$x][94];
                    $can_productos[$x][95]=$value->descripcion;
                    $can_productos[$x][96]=$value->precio;
                    $can_productos[$x][97]=$value->cod_producto;
                    $can_productos[$x][98]=$value->id_producto;
                    $can_productos[$x][99]=$value->necesidad_actual;
                }
            }
            $y++;
        }
        $x++;


    
        return view('frontend.cans.admin.establecimiento_show')->with('can_id', $id)
                                      ->with('can_productos', $can_productos)
                                      ->with('fila', $x)
                                      ->with('col', $i)
                                      ->with('y', $y)
                                      ->with('descripcion_tipo', $descripcion_tipo)
                                      ->with('tipo_dispositivo', $tipo_dispositivo)
                                      ->with('descripcion', $descripcion);   
        
  
    }

    public function establecimientos_can_x_servicio($id,$tipo)
    {
        
        for($i=0;$i<3205;$i++){
            for($j=0;$j<420;$j++){ //total servicios
                $can_productos[$i][$j]=0;
                $descripcion[$j][0]="";
                $descripcion[$j][1]="";
            }
        }

        if($tipo==1){
            $contar_nivel = DB::table('estimacion_servicio')
                ->select(DB::raw('sum(necesidad_anual) as necesidad,  estimacion_servicio.petitorio_id,estimacion_servicio.descripcion, servicios.nombre_servicio'))
                ->join('establecimientos', 'establecimientos.id', 'estimacion_servicio.establecimiento_id')
                ->join('can_servicio', 'can_servicio.establecimiento_id', 'establecimientos.id')
                ->join('servicios', function($join)
                    {
                        $join->on('servicios.id', '=', 'can_servicio.servicio_id')
                             ->on('servicios.id', '=', 'estimacion_servicio.servicio_id');
                    })
                ->join('petitorio_servicio', function($join)
                    {
                        $join->on('servicios.id', '=', 'petitorio_servicio.servicio_id')
                             ->on('estimacion_servicio.petitorio_id', '=', 'petitorio_servicio.petitorio_id');
                    })
                ->join('petitorios', function($join)
                    {
                        $join->on('petitorios.id', '=', 'petitorio_servicio.petitorio_id')
                             ->on('petitorios.id', '=', 'estimacion_servicio.petitorio_id');
                    })
                ->where('estimacion_servicio.can_id',$id)
                ->where('estimacion_servicio.estado','<>',2)
                ->where('estimacion_servicio.tipo_dispositivo_id',1)
                ->groupby('estimacion_servicio.petitorio_id','estimacion_servicio.descripcion','servicios.nombre_servicio')
                ->orderby('estimacion_servicio.petitorio_id')
                ->count();
        }
        else
        {
            $contar_nivel = DB::table('can_ipress')
                ->select(DB::raw('sum(necesidad_anual) as necesidad,  estimacion_servicio.petitorio_id,estimacion_servicio.descripcion, servicios.nombre_servicio'))
                ->join('establecimientos', 'establecimientos.id', 'can_ipress.id_establecimiento')
                ->join('can_servicio', 'can_servicio.id_establecimiento', 'establecimientos.id')
                ->join('servicios', function($join)
                    {
                        $join->on('servicios.id', '=', 'can_servicio.servicio_id')
                             ->on('servicios.id', '=', 'estimacion_servicio.servicio_id');
                    })
                ->join('petitorio_servicio', function($join)
                    {
                        $join->on('servicios.id', '=', 'petitorio_servicio.servicio_id')
                             ->on('estimacion_servicio.id_producto', '=', 'petitorio_servicio.petitorio_id');
                    })
                ->join('petitorios', function($join)
                    {
                        $join->on('petitorios.id', '=', 'petitorio_servicio.petitorio_id')
                             ->on('petitorios.id', '=', 'can_ipress.petitorio_id');
                    })
                ->where('can_ipress.id_can',$id)
                ->where('can_ipress.estado','<>',2)
                ->where('can_ipress.id_tipo_dispositivo','>',1)
                ->groupby('estimacion_servicio.id_producto','estimacion_servicio.descripcion','servicios.nombre_servicio')
                ->orderby('estimacion_servicio.id_producto')
                ->count();
        }
        
        if($contar_nivel>0){
            if($tipo==1){
                $consulta = DB::table('can_ipress')
                    ->select(DB::raw('sum(necesidad_anual) as necesidad,  id_producto,descripcion,id_servicio,id_establecimiento, cod_producto'))
                    ->where('can_ipress.necesidad_anual','>',0)
                    ->where('can_ipress.id_can',$id)                                      
                    ->where('can_ipress.estado','<>',2)
                    ->where('can_ipress.id_tipo_dispositivo',1)
                    ->where('can_ipress.id_servicio','<>',0)
                    ->groupby('id_producto','descripcion','id_servicio', 'establecimiento_id', 'cod_petitorio')
                    ->orderby('id_producto','asc')
                    ->orderby('id_servicio','asc')
                    ->get();

                $descripcion_tipo="MEDICAMENTOS";
                
            }
            else
            {
                $consulta = DB::table('can_ipress')
                    ->select(DB::raw('sum(necesidad_anual) as necesidad,  id_producto,descripcion,id_servicio,id_establecimiento, cod_producto'))
                    ->where('can_ipress.necesidad_anual','>',0)
                    ->where('can_ipress.id_can',$id)                                      
                    ->where('can_ipress.estado','<>',2)
                    ->where('can_ipress.id_tipo_dispositivo','>',1)
                    ->where('can_ipress.id_servicio','<>',0)
                    ->groupby('id_producto','descripcion','id_servicio', 'id_establecimiento', 'cod_producto')
                    ->orderby('id_producto','asc')
                    ->orderby('id_servicio','asc')
                    ->get();

                $descripcion_tipo="DISPOSITIVOS";
            }
            
            $servicios_x = DB::table('consultorios')
                               ->orderby('consultorios.id','asc')
                               ->get();
            $i=0;

            foreach ($servicios_x as $key => $value) {
                $descripcion[$i][0]=$value->id;   
                $descripcion[$i][1]=$value->nombre_servicio;                      
                $i++;
            }
            

            $fila_anterior=5000; $x=-1; $y=0; $z=0;
            
            foreach ($consulta as $key => $value) {
                
                $fila=$value->petitorio_id;
                //dd($x);
                if($fila_anterior!=$fila){
                    $fila_anterior=$fila;
                    $x++;
                }
                
                for($k=0;$k<$i;$k++){
                    if($value->servicio_id==$descripcion[$k][0]){
                        switch ($value->establecimiento_id) {
                            case 1: $valor=0;break; //lns
                            case 2: $valor=1;break; //abl
                            case 3: $valor=2;break; //sj
                            case 30: $valor=3;break; //cix
                            case 69: $valor=4;break; //aqa
                        }                        
                        $calculo=$k*5;  //175
                        $m=($calculo+$valor);                        
                        $can_productos[$x][$m]=$value->necesidad;
                        $can_productos[$x][419]=$value->descripcion;
                        $can_productos[$x][418]=$can_productos[$x][418]+$can_productos[$x][$m];
                    }
                }
                $y++;
            }
            $x++;
        }
        else
        {
            $x=0;
            $descripcion_tipo="MEDICAMENTOS";
        }
        
        $total_servicios=$i*5;
        
        return view('admin.cans.rubro_show')->with('can', $can)
                                      ->with('can_productos', $can_productos)
                                      ->with('fila', $x)
                                      ->with('col', $i)
                                      ->with('y', $total_servicios)
                                      ->with('descripcion_tipo', $descripcion_tipo)
                                      ->with('descripcion', $descripcion);
    }

    public function nivel_total($id)
    {
        for($i=0;$i<3;$i++){
            for($j=0;$j<11;$j++){
                $can_productos[$i][$j]=0;
                $descripcion[$j]="";
            }
        }

        $contar_nivel = DB::table('can_ipress')
                ->select(DB::raw('sum(necesidad_anual) as necesidad,tipo_productos.id'))
                ->join('tipo_productos', 'tipo_productos.id','=', 'can_ipress.id_tipo_dispositivo')
                ->join('establecimientos', 'establecimientos.id','=', 'can_ipress.id_establecimiento')
                ->where('can_ipress.id_can', $id)
                ->where('can_ipress.estado','<>',2)
                ->where('can_ipress.necesidad_anual','>',0)
                ->where('can_ipress.id_servicio','=',0)
                ->groupby('tipo_productos.id')
                ->count();

        if($contar_nivel>0){
            $consulta = DB::table('can_ipress')
                ->select(DB::raw('sum(necesidad_anual) as necesidad, tipo_productos.id,tipo_productos.nombre as descripcion, establecimientos.nivel as nivel_id'))
                ->join('tipo_productos', 'tipo_productos.id','=', 'can_ipress.id_tipo_dispositivo')
                ->join('establecimientos', 'establecimientos.id','=', 'can_ipress.id_establecimiento')
                ->where('can_ipress.id_can', $id)
                ->where('can_ipress.estado','<>',2)
                ->where('can_ipress.necesidad_anual','>',0)
                ->groupby('tipo_productos','tipo_productos.id','establecimientos.nivel')
                ->where('can_ipress.id_servicio','=',0)
                ->orderby('tipo_productos.id')
                ->get();
            
            foreach ($consulta as $key => $value) {
                # code...
                $nivel=$value->nivel_id-1;
                $id=$value->id-1;
                $can_productos[$nivel][$id]=$value->necesidad;
                $can_productos[$nivel][10]=$can_productos[$nivel][10]+$value->necesidad; //total
                $descripcion[$id]=$value->descripcion;
            }

        }

        return view('frontend.cans.admin.rubro_nivel')->with('can_id', $id)
                                      ->with('can_productos', $can_productos)
                                      ->with('descripcion', $descripcion);
                                      
    }

    public function establecimientos_can_tipo($id)
    {
       
        for($i=0;$i<90;$i++){
            for($j=0;$j<40;$j++){
                $can_productos[$i][$j]=0;
                $descripcion[$j][0]="";
                $descripcion[$j][1]="";
            }
        }



        $contar_nivel = DB::table('can_ipress')
                ->select(DB::raw('sum(necesidad_anual) as necesidad,establecimientos.id, establecimientos.nombre as nombre_establecimiento, count(*) as cantidad,can_ipress.id_tipo_dispositivo'))
                ->join('tipo_productos', 'tipo_productos.id','=', 'can_ipress.id_tipo_dispositivo')
                ->join('establecimientos', 'establecimientos.id','=', 'can_ipress.id_establecimiento')
                ->where('can_ipress.id_can', $id)
                ->where('can_ipress.necesidad_anual', '>',0)
                ->where('can_ipress.estado', '<>',2)
                ->where('can_ipress.id_servicio', '=',0)
                ->groupby('establecimientos.id','establecimientos.nombre','can_ipress.id')
                ->count();

        
        if($contar_nivel>0){
            $consulta = DB::table('can_ipress')
                ->select(DB::raw('sum(necesidad_anual) as necesidad,establecimientos.id, establecimientos.nombre as nombre_establecimiento, count(*) as cantidad,can_ipress.id_tipo_dispositivo,tipo_productos.nombre as descripcion'))
                ->join('tipo_productos', 'tipo_productos.id', '=' ,'can_ipress.id_tipo_dispositivo')
                ->join('establecimientos', 'establecimientos.id', '=' ,'can_ipress.id_establecimiento')
                ->where('can_ipress.id_can', $id)
                ->where('can_ipress.necesidad_anual', '>',0)
                ->where('can_ipress.estado', '<>',2)
                ->where('can_ipress.id_servicio', '=',0)
                ->groupby('establecimientos.id','establecimientos.nombre','can_ipress.id_tipo_dispositivo','tipo_productos.nombre')
                ->orderby('can_ipress.id_tipo_dispositivo')
                ->get();

            //dd($consulta);

            $tipo_dispositivo_x = DB::table('tipo_productos')
                                      ->orderby('id','asc')
                                      ->get();
            $i=0;

            foreach ($tipo_dispositivo_x as $key => $value) {
                $descripcion[$i][0]=$value->id;   
                $descripcion[$i][1]=$value->nombre;   
                $i++;
            }
            //$fila_anterior=0; $x=-1; $y=0;
            $fila_anterior=0; $x=0; $y=0;
            
            foreach ($consulta as $key => $value) {
                # code...
                $fila=$value->id-1;
                if($fila_anterior!=$fila){
                    $fila_anterior=$fila;
                    $x++;
                }
                //$m=$i*2;
                $k=0; $n=0;
                while ($k<$i){
                    if($value->id_tipo_dispositivo==$descripcion[$k][0]){
                        $n=$k*2;
                        $can_productos[$x][$n]=$value->necesidad;
                        $p=$n+1;
                        $can_productos[$x][$p]=$value->cantidad;
                        $can_productos[$x][35]=$value->nombre_establecimiento;
                    }
                    $k++;
                }
            }
        }
        else
        {
            $x=0;
        }
        
        $x=$x+1;
        //dd($can_productos);
        return view('frontend.cans.admin.establecimientos_cans')->with('can_id', $id)
                                      ->with('can_productos', $can_productos)
                                      ->with('fila', $x)
                                      ->with('col', $i)
                                      ->with('descripcion', $descripcion);        
    }

    public function up_archivos_can($can_id,$establecimiento_id)
    {
        //Busco datos del establecimiento
        $establecimiento = Establecimiento::find($establecimiento_id);
    
        $archivos= DB::table('can_observaciones')
                            ->where('establecimiento_id',$establecimiento_id)
                            ->where('can_id',$can_id)
                            ->where('estado',1)
                            ->get();
        
        return view('frontend.cans.admin.up_archivos')
                    ->with('can_id', $can_id)                    
                    ->with('establecimiento_id', $establecimiento_id)  
                    ->with('nombre_establecimiento', $establecimiento->nombre)
                    ->with('archivos', $archivos);        
    }

    public function subir_archivo_correccion(Request $request)
    {
        $input = $request->all();
        $user_id=Auth::user()->id;
        

        if ($request->hasFile('photo')){
            $name_photo = time().'-'.$request->photo->getClientOriginalName();
            $original_name=$request->photo->getClientOriginalName();
            $input['photo'] = '/upload/observaciones/'.$request->establecimiento_id.'/'.$name_photo;            
            $request->photo->move(public_path('/upload/observaciones/'.$request->establecimiento_id.'/'), $input['photo']);
            $extension_archivo= $request->photo->getClientOriginalExtension();
        }

        DB::table('can_observaciones')
            ->insert([
                'establecimiento_id' => $request->establecimiento_id,                
                'nombre_archivo'=>$original_name,
                'responsable_id'=>$user_id,
                'descarga_archivo'=>$input['photo'],
                'can_id'=>$request->can_id,
                'descripcion_archivo'=>$request->descripcion,
                'extension_archivo'=>$extension_archivo,
                'created_at'=>Carbon::now(),
        ]);       
        
        //return redirect(route('cans.listar_archivos_can',[$can_id,$id]));
        return redirect('/listar_archivos_can/' . $request->can_id . '/' . $request->establecimiento_id)->withFlashSuccess("Archivo subido");
    }

    public function subir_archivo_rubro(Request $request,$id,$can_id, $establecimiento_id, $servicio_id)
    {
        $input = $request->all();
        $user_id=Auth::user()->id;
        

        if ($request->hasFile('photo')){
            $name_photo = time().'-'.$request->photo->getClientOriginalName();
            $original_name=$request->photo->getClientOriginalName();
            $input['photo'] = '/upload/observaciones/'.$id.'/'.$name_photo;            
            $request->photo->move(public_path('/upload/observaciones/'.$id.'/'), $input['photo']);
            $extension_archivo= $request->photo->getClientOriginalExtension();
        }

        DB::table('observaciones')
            ->insert([
                'establecimiento_id' => $establecimiento_id,                
                'nombre_archivo'=>$original_name,
                'rubro_id'=>$servicio_id,
                'responsable_id'=>$user_id,
                'can_id'=>$can_id,
                'descarga_archivo'=>$input['photo'],
                'descripcion_archivo'=>$request->descripcion,
                'extension_archivo'=>$extension_archivo,
                'created_at'=>Carbon::now(),
        ]);       
        
        //return redirect(route('cans.listar_archivos_can_rubro',[$can_id,$establecimiento_id,$servicio_id]));
        return redirect(route('cans.listar_archivos_can_servicio',[$can_id,$establecimiento_id,$servicio_id]));
        
    }

    public function listar_observaciones_nivel1($can_id)
    {
        
        $establecimiento_id=Auth::user()->id_establecimiento;
        
        //Busco datos del establecimiento
        $establecimiento = Establecimiento::find($establecimiento_id); 
        
        $observaciones= DB::table('can_observaciones')
                        ->where('establecimiento_id',$establecimiento_id)   
                        ->where('can_id',$can_id)               
                        ->where('estado',1)
                        ->get();

        return view('frontend.cans.admin.listar_observaciones')
                ->with('can_id', $can_id)
                ->with('nivel', 1)
                ->with('establecimiento_id', $establecimiento_id)  
                ->with('nombre_establecimiento', $establecimiento->nombre)
                ->with('observaciones', $observaciones);
        
    }

    public function listar_archivos_nivel1($can_id)
    {
        $cans = DB::table('cans')                
                ->orderby('id','desc')
                ->first();        
        
        $ultimo_id=$cans->id; 
        
        //Verifico de que establecimiento es el usuario
        $establecimiento_id=Auth::user()->establecimiento_id;
        
        //Busco datos del establecimiento
        $establecimiento = Establecimiento::find($establecimiento_id); 

        $cerrado=DB::table('can_establecimiento')
                    ->where('can_id',$can_id)
                    ->where('establecimiento_id',$establecimiento_id)
                    ->get();  
        
        if($cerrado->get(0)->medicamento_cerrado==2 or $cerrado->get(0)->dispositivo_cerrado==2)
        {
            $archivos= DB::table('archivos')
                    ->where('establecimiento_id',$establecimiento_id)
                    ->where('can_id', $can_id)
                    ->where('estado',1)
                    ->get();

            return view('site.responsable_farmacia_hospital.listar_archivos')
                    ->with('can_id', $can_id)
                    ->with('ultimo_id', $ultimo_id)
                    ->with('nivel', 1)
                    ->with('establecimiento_id', $establecimiento_id)  
                    ->with('nombre_establecimiento', $establecimiento->nombre_establecimiento)
                    ->with('archivos', $archivos);        
        }
        else
        {
            return redirect('/listar_archivos_can/' . $request->can_id . '/' . $request->establecimiento_id)->withFlashSuccess("Debe cerrar su petitorio tanto de medicamentos como de dispositivos para ver esta opción");
        }
    }

}