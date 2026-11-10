<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Farmacia;
use App\Models\Pabellone;
use App\Models\Piso;
use App\Models\Asignacion_servicio;
use App\Models\Consultorio;
use App\Models\Cuarto;
use App\Models\Cama;
use App\Models\Upss;
use Session;
use DB;
use App;
use Illuminate\Support\Facades\Route;
use Auth;
use Carbon\Carbon;

class CamaController extends Controller
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
    public function index($id_atencion=0)
    {
		if (!Auth::check()) :
          return redirect('/auth/login');
        else:
          if(Auth::user()->actualizo_passw==0):
            return redirect('auth/password/change');
          endif;
        endif;
		
		$id_user = Auth::user()->id;
        $modelfarma= new Farmacia();
        $modelcama= new Cama();
        $farmacias=$modelfarma->GetFarmaByUser($id_user);
        $id_ipress=$farmacias->id_establecimiento;
        $farmacia=$farmacias->nombre;
		
        $camas=$modelcama->ShowAllCamasXIpress($id_ipress,$id_atencion);
		$ruta = Route::getCurrentRoute()->getPath();
		if(count($ruta=explode("/",$ruta)) > 1);$ruta=$ruta[0];
		$menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id,$ruta);
		$permiso = $menu[0];
		if($permiso->lectura == "")return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
        return view('frontend.camas.all',['camas' => $camas,'permiso'=>$permiso,'id_establecimiento'=>$id_ipress,'id_atencion'=>$id_atencion]);
		
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
		
		$id_user = Auth::user()->id;
        $modelfarma= new Farmacia();
        $farmacias=$modelfarma->GetFarmaByUser($id_user);
        $id_establecimiento=$farmacias->id_establecimiento;
        $model= new Pabellone();
        //$pabellon=$model->ShowallByIdIpress($id_establecimiento);
        $model_cama = new Cama();
        $estados_fisico = ""; //$model_cama->ShowEstadosFisico();
        $tipos = $model_cama->ShowTiposCamas();
        $situaciones = $model_cama->ShowSituacionesCamas();
        $clase_camas = $model_cama->ShowClasesCamas();
		
        return view('frontend.camas.create',compact('pabellon','id_establecimiento','estados_fisico','tipos','situaciones','clase_camas'));
    }
	
        public function reporte_d1_cama()
    {
		if (!Auth::check()):
			return redirect('/auth/login');
		endif;
		
		$id_user = Auth::user()->id;
        $modelfarma= new Farmacia();
        $farmacias=$modelfarma->GetFarmaByUser($id_user);
        $id_establecimiento=$farmacias->id_establecimiento;
        $model= new Pabellone();
        //$pabellon=$model->ShowallByIdIpress($id_establecimiento);
        $model_cama = new Cama();
        $estados_fisico = ""; //$model_cama->ShowEstadosFisico();
        $tipos = $model_cama->ShowTiposCamas();
        $situaciones = $model_cama->ShowSituacionesCamas();
        $clase_camas = $model_cama->ShowClasesCamas();
		
        return view('frontend.camas.editar_camas_d1',compact('pabellon','id_establecimiento','estados_fisico','tipos','situaciones','clase_camas'));
    }
	
public function reporte_d1_data(Request $request)
{
    $id_establecimiento = \Auth::user()->id_establecimiento;

    $periodo = $request->periodo;

    if ($periodo) {
        $fecha_inicio = \Carbon\Carbon::parse($periodo . '-01')->startOfMonth();
        $fecha_fin = \Carbon\Carbon::parse($periodo . '-01')->endOfMonth();
    } else {
       
        $fecha_inicio = \Carbon\Carbon::now()->startOfMonth();
        $fecha_fin = \Carbon\Carbon::now()->endOfMonth();
    }

    $data = collect(DB::select("
        SELECT * 
        FROM sp_reporte_d1_ps_hospitalizacion(?, ?, ?)
    ", [$fecha_inicio, $fecha_fin, $id_establecimiento]));

    if ($id_establecimiento == 77) {
        $data = $data->reject(function ($item) {
            return trim($item->codigo_upss) == '241900';
        });
    }

    return response()->json($data->values());
}

public function reporte_d1_guardado(Request $request)
{
    $id_establecimiento = Auth::user()->id_establecimiento;

    $periodo = $request->periodo;
    $periodo = str_replace('-', '', $periodo);
    $query = DB::table('reporte_d1_ipress')
        ->where('id_establecimiento', $id_establecimiento)
        ->where('id_estado_reg', 1);
    if ($periodo) {
        $query->where('periodo', $periodo);
    }
    return response()->json($query->orderBy('id', 'desc')->get());
}

  public function exportar_d1_txt(Request $request)
{
    $id_establecimiento = Auth::user()->id_establecimiento;

    $periodo = str_replace('-', '', $request->periodo); // 202605
    $anio = substr($periodo, 0, 4);
    $mes = substr($periodo, 4, 2);
    $letra_tabla = "d1";

    $data = DB::table('reporte_d1_ipress')
        ->where('id_establecimiento', $id_establecimiento)
        ->where('periodo', $periodo)
        ->where('id_estado_reg', 1)
        ->get();

    $lineas = [];
    foreach ($data as $row) {
        $lineas[] =
        trim($row->codigo_ipress ?? '') . '|' .
        trim($row->codigo_ugipress ?? '') . '|' .
        trim($row->codigo_upss ?? '') . '|' .
        (int) ($row->ingresos ?? 0) . '|' .
        (int) ($row->egresos ?? 0) . '|' .
        (int) ($row->estancias ?? 0) . '|' .
        (int) ($row->pacientes_dia ?? 0) . '|' .
        (int) ($row->camas ?? 0) . '|' .
        (int) ($row->dias_cama ?? 0) . '|' .
        (int) ($row->fallecidos ?? 0);
    }

    $contenido = implode("\n", $lineas);

    $ipress = Establecimiento::where("id", $id_establecimiento)->first();

    $nombreArchivo =
        $ipress->codigo . "_" .
        $anio . "_" .
        $mes . "_TA" .
        strtoupper($letra_tabla) . ".txt";

    return Response::make($contenido, 200, [
        'Content-Type' => 'text/plain',
        'Content-Disposition' => 'attachment; filename="' . $nombreArchivo . '"'
    ]);
}

public function anular_reporte_d1($id)
{
    try {

        $id_user = Auth::user()->id;
        $registro = DB::table('reporte_d1_ipress')
            ->where('id', $id)
            ->first();
        if (!$registro) {
            return response()->json([
                'status' => 'error',
                'message' => 'Registro no encontrado'
            ], 404);
        }
        DB::table('reporte_d1_ipress')
            ->where('id', $id)
            ->update([
                'id_estado_reg' => 0,
                'updated_at' => \Carbon\Carbon::now(),
                'updated_us' => $id_user
            ]);
        return response()->json([
            'status' => 'ok',
            'message' => 'Registro anulado correctamente'
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'status' => 'error',
            'message' => 'Error al anular registro',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function guardar_d1_camas(Request $request)
{
    $id_user = Auth::user()->id;
    $id_establecimiento = Auth::user()->id_establecimiento;
    try {

        $data = $request->input('data');
        if (!$data || count($data) == 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'No hay datos para guardar'
            ], 422);
        }
        $insertados = 0;
        foreach ($data as $item) {

            if (
                empty($item['cod_ipress']) ||
                empty($item['periodo'])
            ) {
                continue;
            }
            DB::table('reporte_d1_ipress')->insert([
                'periodo' => $item['periodo'],
                'cod_ipress' => $item['cod_ipress'],
                'descripcion_ipress' => isset($item['descripcion_ipress']) ? $item['descripcion_ipress'] : null,
                'cod_ugipress' => isset($item['cod_ugipress']) ? $item['cod_ugipress'] : null,
                'especialidad' => isset($item['especialidad']) ? $item['especialidad'] : null,
                'ingresos' => isset($item['ingresos']) ? $item['ingresos'] : 0,
                'egresos' => isset($item['egresos']) ? $item['egresos'] : 0,
                'estancias' => isset($item['estancias']) ? $item['estancias'] : 0,
                'pacientes_dia' => isset($item['pacientes_dia']) ? $item['pacientes_dia'] : 0,
                'camas' => isset($item['camas']) ? $item['camas'] : 0,
                'dias_cama' => isset($item['dias_cama']) ? $item['dias_cama'] : 0,
                'fallecidos' => isset($item['fallecidos']) ? $item['fallecidos'] : 0,
                 'id_establecimiento' => $id_establecimiento,
                'created_at' => Carbon::now(),
                'created_us' => $id_user,
   
            ]);
            $insertados++;
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'Datos guardados correctamente',
            'insertados' => $insertados
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'status' => 'error',
            'message' => 'Error al guardar datos',
            'error' => $e->getMessage()
        ], 500);
    }
}
	
	public function obtener_cuarto_por_idasignacionservicio($id_asignacion_servicio){
	
		$model_cama = new Cama;
		$cuarto = $model_cama->GetCuartoByIdAsignacionServicio($id_asignacion_servicio);
        return $cuarto;
	}
	
    public function obtener_cama_upsses($id_asignacion_servicio){
    
        $model_upsses = new Upss();
        $model_asignacion=new Asignacion_servicio;

        $asignacion_servicio = $model_asignacion->GetAsignacionServiciosById($id_asignacion_servicio);

        $upsses = $model_upsses->ShowConsultoriosUpssHospitalizacion($asignacion_servicio->id_consultorio);

        return $upsses;
    }

	public function obtener_cama_por_idcuarto($id_consultorio){
	
		$model_cama = new Cama;
		$cuarto = $model_cama->GetCamaByIdConsultorio($id_consultorio);
        return $cuarto;
	}

    public function obtener_cama_por_idcuarto_todos($id_consultorio){
    
        $model_cama = new Cama;
        $cuarto = $model_cama->GetCamaByIdConsultorio_todos($id_consultorio);
        return $cuarto;
    }
	
	
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$nombre = trim($request->nombre);
        $cama=new Cama;
        $cama->nombre=$request->nombre;
		$cama->id_cuarto=$request->id_cuarto;
        $cama->id_establecimiento=$request->id_establecimiento;
        $cama->nombre_atencion=$request->nombre_atencion;
        $cama->estado=0;
        $cama->camas_upsses=$request->codigo_upss;
        $cama->created_us = Auth::user()->id;

        if ($request->id_establecimiento == 76) {
            // $cama->estado_fisico=$request->estado_fisico;
            $cama->tipo=$request->tipo;
            $cama->situacion=$request->situacion;
            $cama->id_clase_cama = $request->clase;
        }
        
        $model_cama=new Cama;
		$existe = $model_cama->ExisteCama($nombre, $request->id_cuarto, $request->id_establecimiento);
		if ($existe) {
			return redirect('/all_cama')
							->withInput()
							->withErrors(array('No se ha podido registrar, Cama ya existe en este Pabell&oacute;n, Piso, Servicio y Cuarto'));
		}else{
			$cama->save();
			Session::flash('flash_message', 'Registro exitoso');
        	return redirect('/all_cama');
		}
    }

    public function obtener_cama_disponible_sala($id_ipress)
    {
        $model_cama=new Cama;
        $consultorio = $model_cama->GetConsultorioByIpress($id_ipress);
        return $consultorio;
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

    
    public function edit($id)
    {
		if (!Auth::check()):
			return redirect('/auth/login');
		endif;
		
		$model_cama= new Cama();
		$model_cuarto= new Cuarto();
		$model_consultorio = new Consultorio;
		$model_asignacion=new Asignacion_servicio;
		$modelfarma= new Farmacia();
		$model= new Pabellone();
		$model_cama = new Cama();
        $model_upsses = new Upss();

		$id_user = Auth::user()->id;
        $farmacias=$modelfarma->GetFarmaByUser($id_user);
        $id_establecimiento=$farmacias->id_establecimiento;
		
		$cama = $model_cama->GetCamaById($id);
        $pabellon=$model->ShowallById_Ipress($id_establecimiento, $cama->nombre_atencion);

		//$piso = $model_asignacion->GetPisoByIdPabellon($cama->id_piso, $id_establecimiento);
		$piso = $model_asignacion->GetPisoByIdPabellon($cama->id_pabellon, $id_establecimiento);
		$consultorio = $model_cuarto->GetConsultorioByIdPiso($cama->id_piso);
		$cuarto = $model_cama->GetCuartoByIdAsignacionServicio($cama->id_asignacion_servicio);
        $asignacion_servicio = $model_asignacion->GetAsignacionServiciosById($cuarto[0]->id_asignacion_servicio);
        $upsses = $model_upsses->ShowConsultoriosUpssHospitalizacion($asignacion_servicio->id_consultorio);

        $estados_fisico = ""; //$model_cama->ShowEstadosFisico();
        
        $tipos = $model_cama->ShowTiposCamas();
        $situaciones = $model_cama->ShowSituacionesCamas();
        $clase_camas = $model_cama->ShowClasesCamas();


//        $cuarto = $model_cuarto->GetCuartoById($id);


        return view('frontend.camas.edit', compact('cama','pabellon','consultorio','piso_servicio','piso','consultorio','cuarto','id_establecimiento','estados_fisico','tipos','situaciones','clase_camas','upsses'));
    }

    
    public function update(Request $request)
    {
		$nombre = trim($request->nombre);
        $update=Cama::where('id','=',$request->id)->first();
		$update->nombre=$request->nombre;
		$update->id_cuarto=$request->id_cuarto;
		$update->nombre_atencion=$request->nombre_atencion;
        $update->camas_upsses=$request->codigo_upss;
        $update->updated_us =  Auth::user()->id;
     
        if ($request->id_establecimiento == 76) {
            // $update->estado_fisico=$request->estado_fisico;
            $update->tipo=$request->tipo;
            $update->situacion=$request->situacion;
            $update->id_clase_cama = $request->clase;
        }
		

        if($request->estado==1)
            $update->estado = $request->estado;
        else
            $update->estado = 0;
		
		$model_cama=new Cama;
		$existe = $model_cama->ExisteCama($nombre, $request->id_cuarto, $request->id_establecimiento,$request->id);
		if ($existe) {
			return redirect('/all_cama')
							->withInput()
							->withErrors(array('No se ha podido actualizar, Cama ya existe en este Pabell&oacute;n, Piso, Servicio y Cuarto'));
		}else{
			$update->save();
			return redirect('/all_cama')->withFlashSuccess("Se actualizo correctamente");
		}
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
	
	public function eliminar($id, $estado){
		//$update=Cama::where('id','=',$id)->first();
		//$update->estado=3;
		//$update->save();
		//Session::flash('flash_message', 'Se elimino correctamente');
		if($estado == "0"){
			$update=Cama::where('id','=',$id)->first();                    
			$update->estado=2;
			$update->situacion=3;
			$update->save();                    
			Session::flash('flash_message', 'Se desactivo correctamente');
		} else {
			$update=Cama::where('id','=',$id)->first();
			$update->estado=0;
			$update->situacion=4;
			$update->save();
			Session::flash('flash_message', 'Se activo correctamente');
		}

	}

    public function listar_camas_ocupadas_por_cuarto($id_cuarto){
        $data = Cama::where('id_cuarto','=',$id_cuarto)
                    ->where('estado','=','1')
                    ->get();
        return $data;
	}
	
	public function visor() {
    
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        if(Auth::user()->tipo_registro || Auth::user()->flagestablecimientos == 1):

            // $ruta = Route::getCurrentRoute()->getPath();
            // if (count($ruta = explode("/", $ruta)) > 1)
            //     ;$ruta = $ruta[0];
            // $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
            // $permiso = $menu[0];
            // if ($permiso->lectura == "")
            //     return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

            $id_user = Auth::user()->id;
            
            $id_establecimiento_user = Auth::user()->id_establecimiento;
           
            $cama = new Cama();
            $establecimientos = $cama->GetEstablecimientos($id_establecimiento_user);
            
            $tipo_atenciones = $this->obtener_atencion_por_idestablecimiento($id_establecimiento_user);
            
            if (count($tipo_atenciones) > 0) {
              
                $servicios = $this->obtener_servicio_por_idestablecimiento_tipoatencion($id_establecimiento_user, $tipo_atenciones[0]->nombre_atencion);
                
                $total_cama = $cama->GetTotalCamas($id_establecimiento_user, $tipo_atenciones[0]->nombre_atencion);
                $camas_disponibles_cant = $cama->GetCamasDisponibleCant($id_establecimiento_user, $tipo_atenciones[0]->nombre_atencion);
                $camas_ocupadas_cant = $cama->GetCamasOcupadasCant($id_establecimiento_user, $tipo_atenciones[0]->nombre_atencion);
                $camas_libres_cant = $cama->GetCamasLibresCant($id_establecimiento_user, $tipo_atenciones[0]->nombre_atencion);
                $camas_bloqueadas_cant = $cama->GetCamasBloqueadasCant($id_establecimiento_user, $tipo_atenciones[0]->nombre_atencion);
                
                $estadistica_camas_todos_servicios = $cama->GetTodosServiciosConEstadisticaCamas($id_establecimiento_user, $tipo_atenciones[0]->nombre_atencion,"");
                

                $result = [];
                foreach ($estadistica_camas_todos_servicios as $row) {
                    $idpabellon = $row->idpabellon;
        
                        if (!isset($result[$idpabellon])) {
                          // Si el pabellón no existe en el resultado, inicializarlo como un nuevo objeto
                          $result[$idpabellon] = clone $row;
                      } else {
                          // Sumar los valores numéricos
                          $result[$idpabellon]->sin_situacion += $row->sin_situacion;
                          $result[$idpabellon]->total += $row->total;
                          $result[$idpabellon]->c_disponibles += $row->c_disponibles;
                          $result[$idpabellon]->c_ocupadas += $row->c_ocupadas;
                          $result[$idpabellon]->c_libres += $row->c_libres;
                          $result[$idpabellon]->c_bloqueadas += $row->c_bloqueadas;
                      }
                }
                // Convertir el resultado a un array indexado
                $result = array_values($result);
                $estadistica_camas_todos_servicios = $result ;
                
                
                
                $array_camas_visor = [];
                foreach ($estadistica_camas_todos_servicios as $key => $value) {
                  if((int)$value->total == 0){
                    continue;
                  }
                  array_push($array_camas_visor,$value);
                }
                $estadistica_camas_todos_servicios = $array_camas_visor;

                $sumas = [
                  'total' => 0,
                  'c_disponibles' => 0,
                  'c_ocupadas' => 0,
                  'c_libres' => 0,
                  'c_bloqueadas' => 0,
                  'c_sin_situacion' => 0,
              ];
              
              foreach ($estadistica_camas_todos_servicios as $cama) {
                  $sumas['total'] += (int)$cama->total+(int)$cama->sin_situacion;
                  $sumas['c_disponibles'] += (int)$cama->c_disponibles;
                  $sumas['c_ocupadas'] += (int)$cama->c_ocupadas;
                  $sumas['c_libres'] += (int)$cama->c_libres;
                  $sumas['c_bloqueadas'] += (int)$cama->c_bloqueadas;
                  $sumas['c_sin_situacion'] += (int)$cama->sin_situacion;
              }
                $date = Carbon::now();
                $fecha_formato = $date->format('d-m-Y');
                $hora_formato = $date->format('H:i');

                $tipo_atencion_seleccionado = $tipo_atenciones[0]->nombre_atencion;
                $nombre_servicio = "TODOS";

                return view('frontend.camas.visor', compact('id_user', 'id_establecimiento_user', 'fecha_formato', 'hora_formato', 'total_cama', 'camas_disponibles_cant', 'camas_ocupadas_cant', 'camas_libres_cant', 'camas_bloqueadas_cant', 'establecimientos','tipo_atenciones','servicios','estadistica_camas_todos_servicios','tipo_atencion_seleccionado','nombre_servicio','sumas'));
            } else {
                return redirect('/dashboard')
							->withInput()
							->withErrors(array('No existe información de camas en el establecimiento donde se encuentra.'));
            }
        else:
            return redirect('/dashboard');
        endif;
    }
    
    public function obtener_establecimiento_camas() {
        $cama = new Cama;
		$establecimientos = $cama->GetEstablecimientos();
        return $establecimientos;
    }

    public function obtener_atencion_por_idestablecimiento($id_establecimiento) {
        $cama = new Cama;
		$tipo_atenciones = $cama->GetAtencionPorIdEstablecimiento($id_establecimiento);
        return $tipo_atenciones;
    }

    public function obtener_servicio_por_idestablecimiento_tipoatencion($id_establecimiento, $tipo_atencion) {
        $cama = new Cama;
		$servicios = $cama->GetServicioPorIdEstablecimientoYTipoAtencion($id_establecimiento, $tipo_atencion);
        return $servicios;
    }

    public function obtener_pabellon_por_idestablecimiento_tipoatencion_idservicio($id_establecimiento, $tipo_atencion, $id_servicio) {
        $cama = new Cama;
		$pabellones = $cama->GetPabellonIdEstablecimientoYTipoAtencionYIdServicio($id_establecimiento, $tipo_atencion, $id_servicio);
        return $pabellones;
    }

    public function obtener_cuarto_por_idestablecimiento_tipoatencion($id_establecimiento, $tipo_atencion) {
        $model_cuarto = new Cuarto();
        $cuartos = $model_cuarto->GetByIdEstablecimientoYNombreAtencion($id_establecimiento, $tipo_atencion);
        return $cuartos;
    }
    
    public function visor_camas_filtro(Request $request) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        if (!$request->id_user):
            return redirect('/dashboard');
        endif;

        $id_user =$request->id_user;
        $id_establecimiento = $request->establecimiento;
        $tipo_atencion = $request->tipo_atencion;
        $id_servicio = $request->servicio_se;
        $id_pabellon = "";
        //==================== 
        $id_establecimiento_user = Auth::user()->id_establecimiento;
        
        $cama = new Cama();
        $total_cama = $cama->GetTotalCamas($id_establecimiento_user, $tipo_atencion);
        $camas_disponibles_cant = $cama->GetCamasDisponibleCant($id_establecimiento_user, $tipo_atencion);
        $camas_ocupadas_cant = $cama->GetCamasOcupadasCant($id_establecimiento_user, $tipo_atencion);
        $camas_libres_cant = $cama->GetCamasLibresCant($id_establecimiento_user, $tipo_atencion);
        $camas_bloqueadas_cant = $cama->GetCamasBloqueadasCant($id_establecimiento_user, $tipo_atencion);
        $estadistica_camas_todos_servicios = $cama->GetTodosServiciosConEstadisticaCamas($id_establecimiento, $tipo_atencion, $id_servicio);
        //
        
        $result = [];
        foreach ($estadistica_camas_todos_servicios as $row) {
            $idpabellon = $row->idpabellon;

                if (!isset($result[$idpabellon])) {
                  // Si el pabellón no existe en el resultado, inicializarlo como un nuevo objeto
                  $result[$idpabellon] = clone $row;
              } else {
                  // Sumar los valores numéricos
                  $result[$idpabellon]->sin_situacion += $row->sin_situacion;
                  $result[$idpabellon]->total += $row->total;
                  $result[$idpabellon]->c_disponibles += $row->c_disponibles;
                  $result[$idpabellon]->c_ocupadas += $row->c_ocupadas;
                  $result[$idpabellon]->c_libres += $row->c_libres;
                  $result[$idpabellon]->c_bloqueadas += $row->c_bloqueadas;
              }
        }
        // Convertir el resultado a un array indexado
        $result = array_values($result);
        $estadistica_camas_todos_servicios = $result ;
        $array_camas_visor = [];
        foreach ($estadistica_camas_todos_servicios as $key => $value) {
          if((int)$value->total == 0){
            continue;
          }
          array_push($array_camas_visor,$value);
        }

        
        
        $estadistica_camas_todos_servicios = $array_camas_visor;
        $sumas = [
          'total' => 0,
          'c_disponibles' => 0,
          'c_ocupadas' => 0,
          'c_libres' => 0,
          'c_bloqueadas' => 0,
          'c_sin_situacion' => 0,
        ];
      
        foreach ($estadistica_camas_todos_servicios as $camaItem) {
          $sumas['total'] += (int)$camaItem->total+(int)$camaItem->sin_situacion;
          $sumas['c_disponibles'] += (int)$camaItem->c_disponibles;
          $sumas['c_ocupadas'] += (int)$camaItem->c_ocupadas;
          $sumas['c_libres'] += (int)$camaItem->c_libres;
          $sumas['c_bloqueadas'] += (int)$camaItem->c_bloqueadas;
          $sumas['c_sin_situacion'] += (int)$camaItem->sin_situacion;
      }
     
        $date = Carbon::now();
        $fecha_formato = $date->format('d-m-Y');
        $hora_formato = $date->format('H:i');
        
        $establecimientos = $cama->GetEstablecimientos($id_establecimiento_user);
        $tipo_atenciones = $this->obtener_atencion_por_idestablecimiento($id_establecimiento);
        $servicios = $this->obtener_servicio_por_idestablecimiento_tipoatencion($id_establecimiento, $tipo_atencion);
        
        //if ($id_pabellon != '') {
            //$pabellones = $this->obtener_pabellon_por_idestablecimiento_tipoatencion_idservicio($id_establecimiento, $tipo_atencion, $id_servicio);
        //}
        
        $tipo_atencion_seleccionado = $tipo_atencion;
        if ($id_servicio != ""):
            $servicio = new Consultorio();
            $nombre_servicio = $servicio->GetNombreById($id_servicio)->nombre;
        else:
            $nombre_servicio = "TODOS";
        endif;
        
        $tipo_servicios = $cama->GetServicioPorIdEstablecimientoYTipoAtencion($id_establecimiento,$tipo_atencion_seleccionado);
        return view('frontend.camas.visor', compact('id_user', 'id_establecimiento_user', 'fecha_formato', 'hora_formato', 'total_cama', 'camas_disponibles_cant', 'camas_ocupadas_cant', 'camas_libres_cant', 'camas_bloqueadas_cant', 'establecimientos','tipo_atenciones','tipo_servicios',/*'pabellones',*/'estadistica_camas_todos_servicios','id_establecimiento','tipo_atencion','id_servicio','id_pabellon','tipo_atencion_seleccionado','nombre_servicio','sumas'));
    }

    public function modal_cama_masinfo($id_cama, $tipo_atencion) {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        $info = "";
        if (true):
            $cama = new Cama();
            $info = $cama->GetInfoPacientePorIdCama($id_cama,$tipo_atencion);          
            if (count($info) > 0){
              $info = end($info);              
              $titular = null;
              
              if($info->id_asegurado != null){
                $titular = $cama->getTitularForIdAsegurado($info->id_asegurado);
              }
            }
                
            else{
              return $info = "No Hay datos de Hospitalización";
            }
              
            
        endif;

        return view('frontend.camas.modal_masinfo', compact('id_cama','info','tipo_atencion','titular'));
        
    }

    public function visor_camas_detalle2(Request $request) {
      
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        if(Auth::user()->tipo_registro || Auth::user()->flagestablecimientos == 1):

            // $ruta = Route::getCurrentRoute()->getPath();
            // if (count($ruta = explode("/", $ruta)) > 1)
            //     ;$ruta = $ruta[0];
            // $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
            // $permiso = $menu[0];
            // if ($permiso->lectura == "")
            //     return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");
            
            $id_user = Auth::user()->id;
            $id_establecimiento_user = Auth::user()->id_establecimiento;
            
            $id_establecimiento = $request->id_establecimiento;
            $tipo_atencion = $request->nombre_atencion;
            $id_servicio = $request->id_servicio;

            $cama = new Cama();

            $total_cama_detalle =$cama->GetTotalCamasPorIdEstablecimientoYIdServicio($id_establecimiento, $id_servicio, $tipo_atencion);
           
            $camas_ocupadas_cant_detalle = $cama->GetCamasOcupadasCantPorIdEstablecimientoYIdServicio($id_establecimiento, $id_servicio, $tipo_atencion);
            $camas_libres_cant_detalle = $cama->GetCamasLibresCantPorIdEstablecimientoYIdServicio($id_establecimiento, $id_servicio, $tipo_atencion);
            $camas_bloqueadas_cant_detalle = $cama->GetCamasBloqueadasCantPorIdEstablecimientoYIdServicio($id_establecimiento, $id_servicio, $tipo_atencion);
            $camas_disponibles_cant_detalle = $total_cama_detalle->count - $camas_libres_cant_detalle->count -$camas_bloqueadas_cant_detalle->count-$camas_bloqueadas_cant_detalle->count;
            
            
            $reporte_camas = $cama->GetReporteCamasPorIdEstablecimientoYTipoAtencionYIdServicio($id_establecimiento, $tipo_atencion, $id_servicio);
            $reporte_solo_pabellones = $cama->GetSoloPabellonesPorIdEstablecimientoYTipoAtencionYIdServicio($id_establecimiento, $tipo_atencion, $id_servicio);
            $reporte_solo_cuartos = $cama->GetSoloCuartosPorIdEstablecimientoYTipoAtencionYIdServicio($id_establecimiento, $tipo_atencion, $id_servicio);
            $camas_all = [];
            if($tipo_atencion == "EMERGENCIA"){
            $camas_all = $cama->GetReporteCamasPorIdEstablecimientoYTipoAtencionYIdServicio2($id_establecimiento, $tipo_atencion, $id_servicio,$request->nombre_cuarto);

           }else{
            $camas_all = $cama->GetReporteCamasPorIdEstablecimientoYTipoAtencionYIdServicio2($id_establecimiento, $tipo_atencion, $id_servicio,$request->nombre_cuarto,$request->idPiso,$request->idPabellon,$request->idServicio);

           }
            
            $conteoSituaciones = [
              'LIBRE' => 0,
              'EN REPARACION' => 0,
              'MALOGRADO' => 0,
              'OCUPADO' => 0,
              'SIN SITUACIÓN' => 0
          ];
          //dd($camas_all);
          foreach ($camas_all as $cama) {
            // Verificar si la situación es vacía o null, contarla como "SIN SITUACIÓN"
            if ($cama->situacioncamadesc == "" || $cama->situacioncamadesc === null) {
                $conteoSituaciones['SIN SITUACIÓN']++;
            } elseif (isset($conteoSituaciones[$cama->situacioncamadesc])) {
                $conteoSituaciones[$cama->situacioncamadesc]++;
            }
        }
          // Calcular el total de todas las situaciones
          $total = array_sum($conteoSituaciones);

          // Agregar el total al array de conteo
          $conteoSituaciones['TOTAL'] = $total;
          //dd($conteoSituaciones);
            
            // Asignar clases a cada cama
            foreach ($camas_all as $cama) {
              // Clase del panel según la situación de la cama
              $class = "panel-default";
              if ($cama->situacioncama == '4') {
                  $class = "panel-info";
              } elseif ($cama->situacioncama == '2' || $cama->situacioncama == '3') {
                  $class = "panel-danger";
              }

              // Color de fondo del panel de la cama
              if ($cama->situacioncama == '4') {
                  $class .= " bg-celeste";
              } elseif ($cama->situacioncama == '2' || $cama->situacioncama == '3') {
                  $class .= " bg-rojo";
              } elseif ($cama->situacioncama == '5') {
                  $class .= " bg-verde-vomito";
              } elseif ($cama->situacioncama == '' || $cama->situacioncama == '0') {
                  $class .= " bg-plomo";
              } else {
                  $class .= " bg-blanco";
              }

              // Tipo del label para la descripción del tipo de cama
              $tipo_label = "label-default";
              if ($cama->tipocama == '1') {
                  $tipo_label = "label-info";
              } elseif ($cama->tipocama == '2') {
                  $tipo_label = "label-warning";
              } elseif ($cama->tipocama == '3') {
                  $tipo_label = "label-success";
              } elseif ($cama->tipocama == '4') {
                  $tipo_label = "label-primary";
              }

              // Asignar las clases calculadas a cada objeto
              $cama->class = $class;
              $cama->tipo_label = $tipo_label;
          }


            // Agrupamos por nombreservicio y nombrecuarto
            // Agrupar por servicio y luego por cuarto
           

           // Agrupar por servicio y luego por cuarto
            $camasAgrupadas = [];
            foreach ($camas_all as $cama) {
                $servicio = $cama->nombreservicio;
                $cuarto = $cama->nombrecuarto;

                if (!isset($camasAgrupadas[$servicio])) {
                    $camasAgrupadas[$servicio] = [];
                }

                if (!isset($camasAgrupadas[$servicio][$cuarto])) {
                    $camasAgrupadas[$servicio][$cuarto] = [];
                }

                $camasAgrupadas[$servicio][$cuarto][] = $cama;
            }

           // Ordenar por nombreservicio, nombrecuarto y nombrecama
            ksort($camasAgrupadas); // Ordenar los servicios

            foreach ($camasAgrupadas as $servicio => &$cuartos) {
                ksort($cuartos); // Ordenar los cuartos dentro de cada servicio

                foreach ($cuartos as &$camas) {
                    usort($camas, function ($a, $b) {
                        return strcmp($a->nombrecama, $b->nombrecama);
                    });
                }
            }
            $date = Carbon::now();
            $fecha_formato = $date->format('d-m-Y');
            $hora_formato = $date->format('H:i');

            // $establecimientos = $cama->GetEstablecimientos();
            // $tipo_atenciones = $this->obtener_atencion_por_idestablecimiento($id_establecimiento);
            // $servicios = $this->obtener_servicio_por_idestablecimiento_tipoatencion($id_establecimiento, $tipo_atencion);
            // $pabellones = $this->obtener_pabellon_por_idestablecimiento_tipoatencion_idservicio($id_establecimiento, $tipo_atencion, $id_servicio);
           
            $tipo_atencion_seleccionado = $tipo_atencion;
            if ($id_servicio != "0"):
                $servicio = new Consultorio();
                $nombre_servicio = $servicio->GetNombreById($id_servicio)->nombre;
            else:
                $nombre_servicio = "TODOS";
            endif;
            
            return view('frontend.camas.visor_detalle', compact('id_user', 'id_establecimiento_user', 'fecha_formato', 'hora_formato', 'total_cama_detalle', 'camas_disponibles_cant_detalle', 'camas_ocupadas_cant_detalle', 'camas_libres_cant_detalle', 'camas_bloqueadas_cant_detalle', 'reporte_camas','reporte_solo_pabellones','reporte_solo_cuartos','tipo_atencion_seleccionado','nombre_servicio','id_establecimiento','tipo_atencion','id_servicio','camasAgrupadas','conteoSituaciones'));
            
        else:
            return redirect('/dashboard');
        endif;
    }

    public function get_camas_por_id_cuarto($ipress,$id_cuarto,$id_tipo_atencion=0) {
        if (!Auth::check()):
            return redirect('/auth/login');
        endif;

        $model_cama = new Cama();
        $camas = $model_cama->ShowAllCamasXIpressYCuartoYTipoatencion($ipress,$id_cuarto,$id_tipo_atencion);
        return $camas;
    }

    public function obtener_cama_disponible_sala_by_id_consultorio($id_ipress, $id_consultorio)
    {
        $model_cama=new Cama;
        $consultorio = $model_cama->GetConsultorioByIpress_by_id_consultorio($id_ipress, $id_consultorio);
        return $consultorio;
    }

    public function get_camas_by_idestablecimiento_nombretipoatencion_idcuarto($id_establecimiento, $nombre_tipo_atencion, $id_cuarto) {
        $model_cama = new Cama();
        $camas = $model_cama->GetCamasByIdEstablecimientoYNombreTipoAtencionYIdCuarto($id_establecimiento, $nombre_tipo_atencion, $id_cuarto);
        return $camas;
    }

    public function get_camas_by_idestablecimiento_nombretipoatencion_idcuarto_activos($id_establecimiento, $nombre_tipo_atencion, $id_cuarto) {
        $model_cama = new Cama();
        $camas = $model_cama->GetCamasByIdEstablecimientoYNombreTipoAtencionYIdCuartoActivos($id_establecimiento, $nombre_tipo_atencion, $id_cuarto);
        return $camas;
    }

    public function get_servicios_by_idestablecimiento_nombretipoatencion_idpabellon($id_establecimiento, $nombre_tipo_atencion, $id_pabellon) {
        $model_cama = new Cama();
        $camas = $model_cama->GetAllServiciosByIdEstablecimientoYNombreTipoAtencionYIdPabellon($id_establecimiento, $nombre_tipo_atencion, $id_pabellon);
        return $camas;
    }

    public function GetCamaForFiltros(Request $request){
        $request->establecimiento;
        //dd($request->establecimiento,$request->atencion);
        $cama = new Cama();
        $data = $cama->GetServicioPorIdEstablecimientoYTipoAtencion($request->establecimiento,$request->atencion);
        return response()->json($data);
    }

    public function get_camas_by_idestablecimiento_nombretipoatencion_idpabellon($id_establecimiento, $nombre_tipo_atencion, $id_pabellon) {
        $model_cama = new Cama();
        $camas = $model_cama->getCamasByEstablecimientoIdYNombreTipoAtencionYPabellonId($id_establecimiento, $nombre_tipo_atencion, $id_pabellon);
        return $camas;
    }
}
