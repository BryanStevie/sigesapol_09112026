<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Farmacia;
use App\Models\Diagnostico;
use App\Models\Establecimiento;
use SoapClient;
use Session;
use DB;
use App;
use Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

class DiagnosticoController extends Controller
{
    function __construct()
    {
        $this->middleware('auth'); 
    } 
    
    public function update_diagnostico(Request $request) {
            
			if (!Auth::check()):
            	return redirect('/auth/login');
        	endif;

            $id_user = Auth::user()->id;
            $diagnostico = Diagnostico::where('id', '=', $request->id)->first();
            $diagnostico->codigo = $request->codigo;
            $diagnostico->nombre = $request->nombre;
            $diagnostico->genero = $request->genero;
            $diagnostico->save();
            
            Session::flash('flash_message', 'Actualización exitosa');
            return redirect('/listar_diagnosticos');
    }
    
    public function editar_diagnostico($id) {

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;        
        $id_user = Auth::user()->id;        
        $diagnostico = DB::table('diagnosticos')->where('diagnosticos.id', '=', $id)
                        ->select('diagnosticos.*')->first();

        return view('frontend.diagnosticos.editar_diagnostico', compact('diagnostico'));
    }

    
    public function formulario_diagnostico() {        

        if (!Auth::check()):
            return redirect('/auth/login');
        endif;
        return view('frontend.diagnosticos.create_diagnostico');
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
        $modeldiagnostico = new Diagnostico();        
        $diagnosticos = $modeldiagnostico->getDiagnosticos();
        $ruta = Route::getCurrentRoute()->getPath();
        if (count($ruta = explode("/", $ruta)) > 1)
            ;$ruta = $ruta[0];
        $menu = \App\Models\Menu::getMenuByUsuario(Auth::user()->id, $ruta);
        $permiso = $menu[0];

        if ($permiso->lectura == "")
            return redirect('/inicio')->withFlashSuccess("No tiene Permiso para ingresar a la url ingresada");

        return view('frontend.diagnosticos.all', ['diagnosticos' => $diagnosticos, 'permiso' => $permiso]);
    }

    public function create($id_user)
    {
    
    }

    public function store(Request $request) {        
        $modeldiagnosticos= new Diagnostico();
        $diagnosticos_registrado = $modeldiagnosticos->diagnostico_yaregistrada($request->codigo);
        if(count($diagnosticos_registrado)>0){
            $dato = "El Diagnostico <b>".$diagnosticos_registrado->codigo." </b> ya se encuentra registrado, en estado ";
            $dato.= $diagnosticos_registrado->estado == "1"? "<b>ACTIVO</b>":"<b>INACTIVO</b>";
            return redirect('/listar_diagnosticos/' . $request->id_user)->withInput()->withErrors(array($dato));
        } else {            
            $diagnostico = new Diagnostico;
            $diagnostico->codigo = $request->codigo;
            $diagnostico->nombre = $request->nombre;
            $diagnostico->genero = $request->genero;
            $diagnostico->estado = 1;            
            $diagnostico->save();
            $id_diagnostico = $diagnostico->id;
            Session::flash('flash_message', 'Registro exitoso');
            return redirect('/listar_diagnosticos/');
        }
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

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
            $update=Diagnostico::where('id','=',$id)->first();
            $update->estado=3;
            $update->save();                    
            Session::flash('flash_message', 'Se desactivo correctamente');
        } else {
            $update=Diagnostico::where('id','=',$id)->first();
            $update->estado=1;                    
            $update->save();
            Session::flash('flash_message', 'Se activo correctamente');
        }
	}

    public function modal_diagnostico_paciente($id_prestacion ){
        $model_prestacion = new \App\Models\Prestaciones;
        $diagnosticos = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
        
        return view('frontend.epicrisis.modal_diagnosticos',compact('diagnosticos'));
    }

    public function listar_diagnosticos_ajax(Request $request)
      {
        
        $model_diagnosticos = new Diagnostico();
        $draw = $request->draw;
        $start = ($request->start == 0) ? 1 : (int)$request->start / 10 + 1;
        $length = 10;
        $search_value = $request->search["value"];

        $p[] = (string)$start;
        $p[] = (string)$length;
        $p[] = $search_value;
        
        $data = $model_diagnosticos->listar_diagnosticos_ajax($p);

        $iTotalDisplayRecords = isset($data[0]->totalrows) ? $data[0]->totalrows : 0;
        $result["draw"] = $draw;
        $result["recordsTotal"] = $iTotalDisplayRecords;
        $result["recordsFiltered"] = $iTotalDisplayRecords;
        $result["data"] = $data;

        echo json_encode($result);
      }

    public function modal_diagnostico_paciente_nuevo($id_prestacion ){
        $model_prestacion = new \App\Models\Prestaciones;
        $diagnosticos = $model_prestacion->GetDiagnosticoByIdPrestacion($id_prestacion);
        
        return view('frontend.epicrisis.modal_diagnosticos_nuevo',compact('diagnosticos'));
    }

    public function modal_diagnostico_paciente_nuevo_ho($id_prestacion){
        $model_prestacion = new \App\Models\Prestaciones;
        $diagnosticos = $model_prestacion->GetDiagnosticoByIdPrestacionHospitalizacion($id_prestacion);
        
        return view('frontend.epicrisis.modal_diagnosticos_nuevo_ho',compact('diagnosticos','id_prestacion'));
    }

    public function modal_diagnostico_paciente_nuevo_ho_evo($id_prestacion){
        $model_prestacion = new \App\Models\Prestaciones;
        $diagnosticos = $model_prestacion->GetDiagnosticoByIdPrestacionHospitalizacionEvolucion($id_prestacion);
        
        return view('frontend.epicrisis.modal_diagnosticos_nuevo_ho_evo',compact('diagnosticos'));
    }


    public function diagnostico_autocomplete(Request $request)
{
    $query = $request->get('q');

        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        $resultados = Diagnostico::where(function($q) use ($query) {
                $q->where('codigo', 'ilike', "%$query%")
                  ->orWhere('nombre', 'ilike', "%$query%");
            })
            ->where('estado', 1)
            ->limit(10)
            ->get(['id', 'codigo', 'nombre']);

        return response()->json($resultados);
}

    public function diagnostico_anulacion_validar_json(Request $request)
    {
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;

        DB::beginTransaction();
        $anulacion_valida = false;
        $mensaje_error = "";

        try {
            $diagnostico_codigo = $request->diagnostico_codigo;
            $evaluacion_id = $request->evaluacion_id;
            $prestacion_id = $request->prestacion_id;

            $diagnostico = Diagnostico::where("codigo", $diagnostico_codigo)->where("estado", 1)->get();
            
            if (!empty($diagnostico) && count($diagnostico) == 1):
                $receta_model = new \App\Models\Receta_vale();
                // Obtienes recetas Pendientes de Dispensacion (estado 2) y Dispensadas (estado 1)
                $recetas = $receta_model->GetDatosRecetasByIdEvaluacion($evaluacion_id);

                if ($recetas && count($recetas) > 0):
                    foreach ($recetas as $receta):
                        // Obtiene medicamentos o productos biomedicos no eliminados
                        $recetas_productos = \App\Models\Producto_receta::where("id_receta_vale", $receta->id)
                                                ->where("id_diagnosticos", $diagnostico[0]->id)
                                                ->whereIn("estado", [1, 2])
                                                ->get();

                        if ($recetas_productos && count($recetas_productos) > 0):
                            if ($receta->estado == 1):
                                throw new \Exception("Hay recetas ya dispensadas que los medicamentos o productos biomédicos están enlazados con el diagnóstico a eliminar");
                            else:
                                throw new \Exception("Hay medicamentos o productos biomédicos que están enlazados con el diagnóstico a eliminar");
                            endif;
                        endif;
                    endforeach;
                endif;

                // if ($rowLaboratorio->estado == 1 && $rowLaboratorio->flag == 0 && is_null($rowLaboratorio->flag_toma_muestra))
                $laboratorio_model = new \App\Models\Laboratorio();
                $laboratorio_ordenes = $laboratorio_model->GetLaboratorioByIdPrestacionEvaluacion($prestacion_id, $evaluacion_id);
                if ($laboratorio_ordenes && count($laboratorio_ordenes) > 0):
                    foreach ($laboratorio_ordenes as $laboratorio_orden):
                        $laboratorio_examenes = \App\Models\Prestacion_procedimiento::where("id_lab_rad", $laboratorio_orden->id)
                                                ->where("tipo_procedimiento", 2)
                                                ->where("id_diagnostico", $diagnostico[0]->id)
                                                ->where("id_estado_reg", 1)
                                                ->get();
                        
                        if ($laboratorio_examenes && count($laboratorio_examenes) > 0):
                            if ($laboratorio_orden->flag == 1 || $laboratorio_orden->flag == 2):
                                throw new \Exception("Hay Orden(es) de laboratorio ya firmadas donde los exámenes estan enlazados con el diagnóstico a eliminar");
                            elseif (!is_null($laboratorio_orden->flag_toma_muestra)):
                                throw new \Exception("Hay Orden(es) de laboratorio que ya se tomaron la muestra donde los exámenes estan enlazados con el diagnóstico a eliminar");
                            endif;
                        endif;
                    endforeach;
                endif;

                // if ($rowRadiologia->estado == 1 && $rowRadiologia->flag == 0)
                $radiologia_model = new \App\Models\Radiologia();
                $radiologia_ordenes = $radiologia_model->GetRadiologiaByIdPrestacionEvaluacion($prestacion_id, $evaluacion_id);
                if ($radiologia_ordenes && count($radiologia_ordenes) > 0):
                    foreach ($radiologia_ordenes as $radiologia_orden):
                        $radiologia_examenes = \App\Models\Prestacion_procedimiento::where("id_lab_rad", $radiologia_orden->id)
                                                ->where("tipo_procedimiento", 3)
                                                ->where("id_diagnostico", $diagnostico[0]->id)
                                                ->where("id_estado_reg", 1)
                                                ->get();

                        if ($radiologia_examenes && count($radiologia_examenes) > 0):
                            if ($radiologia_orden->flag == 1 || $radiologia_orden->flag == 2):
                                throw new \Exception("Hay Orden(es) de Imágenes ya firmadas donde los exámenes estan enlazados con el diagnóstico a eliminar");
                            endif;
                        endif;
                    endforeach;
                endif;

            else:
                throw new \Exception("Hay más de 1 diagnóstico habilitado con el código: " . $diagnostico_codigo);
            endif;

            DB::commit();
            $anulacion_valida = true;

        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Anulacion Diagnostico Evaluacion Emergencia => evaluacion_id: " . $request->evaluacion_id . " Diagnostico: " . $request->diagnostico_codigo . 
                "\n\nmensaje: " . $e->getMessage() . 
                "\n\ndatos del request: " . json_encode($request->all()) . 
                "\n\nstack trace: " . $e->getTraceAsString()
            );
            $mensaje_error = $e->getMessage();
        } catch (\Throwable $th) {
            DB::rollback();
            Log::critical("Anulacion Diagnostico Evaluacion Emergencia => evaluacion_id: " . $request->evaluacion_id . " Diagnostico: " . $request->diagnostico_codigo . 
                "\n\nmensaje: " . $th->getMessage() . 
                "\n\ndatos del request: " . json_encode($request->all()) . 
                "\n\nstack trace: " . $th->getTraceAsString()
            );
            $mensaje_error = $th->getMessage();
        }

        if ($anulacion_valida):
            return response()->json([
                'success' => true,
                'message' => "El Dagnóstico puede ser anulado"
            ]);
        else:
            return response()->json([
                'success' => false,
                'message' => $mensaje_error
            ]);
        endif;
    }
	
}
