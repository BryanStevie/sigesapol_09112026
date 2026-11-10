<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use DB;
use App;
use Auth;
use App\Models\Formato_incidente_evento_adverso;
use App\Models\TipoAtencione;
use Carbon\Carbon;


class FormatoIncidenteEventoAdversoController extends Controller
{ 
  function __construct()
    {
        $this->middleware('auth'); 
    }
  
  public function registrarFormato(Request $request){
		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;

      $valor_response=0;
      try{
         DB::beginTransaction(); 
            $data = $request->all();
            $objecto = new Formato_incidente_evento_adverso();
            $objecto->id_usuario_creacion= Auth::user()->id;
            $objecto->id_establecimiento = Auth::user()->id_establecimiento; 
// Verificar si id_nota_enfermeria2 está definido y no está vacío
            if(isset($request->id_nota_enfermeria2) && !empty($request->id_nota_enfermeria2)) {
              $objecto->id_nota_enfermeria = $request->id_nota_enfermeria2;
            } else {
              $objecto->id_nota_enfermeria = 0; // Asignar 0 si el valor es nulo o está vacío
            }
          $objecto->id_tipo_atencion=$request->tipo_atencion;
            $objecto->id_emergencia=$request->id_emergencia_i;
            $objecto->id_asegurado=$request->id_asegurado_i;
            $objecto->id_servicio=$request->id_servicio_i;
            $objecto->fecha_notificacion=$request->fecha_incidente_notificacion;
            $objecto->fecha_verificacion=$request->fecha_incidente_verificacion;
      
            $objecto->notificador_tipo_personal=$request->notificador_tipo_personal;
            $objecto->notificador_tipo_registro=$request->notificador_tipo_registro;
            $objecto->notificador_tipo_personal_descripcion=$request->notificador_tipo_personal_descripcion;
            $objecto->notificador_fecha_evento=$request->notificador_fecha_evento;
            $objecto->notificador_hora_evento=$request->notificador_hora_evento;
            $objecto->notificador_tipo_evento_a=$request->selecTipoAdv;
            $objecto->notificador_observaciones=$request->notificador_observaciones;

            $id_relacionado_PERS_CONV_EVEAD = array();
            if(!empty($request->id_relacionado_PERS_CONV_EVEAD)){
              foreach ($request->id_relacionado_PERS_CONV_EVEAD as $key => $value) {
                $id_relacionado_PERS_CONV_EVEAD[]=array('personal_convocado'=>$value);
              }
            }
            $objecto->responsable_personal_convocado=json_encode($id_relacionado_PERS_CONV_EVEAD);

            $objecto->responsable_analisis_causas=$request->responsable_analisis_causas;
    
            if($request->categoria_evento_adverso !='*' && $request->categoria_tipo_evento_adverso !='*'){
              $objecto->responsable_categoria_adverso=$request->categoria_evento_adverso;
              $objecto->responsable_tipo_adverso=$request->categoria_tipo_evento_adverso;
            }
            $descripcion_ACCI_CORRECT = array();
           
            if(!empty($request->descripcion_ACCI_CORRECT)){
              foreach ($request->descripcion_ACCI_CORRECT as $key => $value) {
                if(!empty($data['descripcion_detalle_ACCI_CORRECT'.$key])){$implementado=$data['descripcion_detalle_ACCI_CORRECT'.$key];}else{$implementado="";}
                $descripcion_ACCI_CORRECT[]=array('acciones_correctivas'=>$value,'implementado'=>$implementado);
              }
            }
            $objecto->planmejora_acciones_correctivas=json_encode($descripcion_ACCI_CORRECT);
            //dd($objecto);
            $descripcion_ACCI_PREVENT = array();
            if(!empty($request->descripcion_ACCI_PREVENT)){
              foreach ($request->descripcion_ACCI_PREVENT as $key => $value) {
                if(!empty($data['descripcion_detalle_ACCI_PREVENT'.$key])){$implementado=$data['descripcion_detalle_ACCI_PREVENT'.$key];}else{$implementado="";}
                  $descripcion_ACCI_PREVENT[]=array('acciones_preventivas'=>$value,'implementado'=>$implementado);
              }
            }
            $objecto->planmejora_acciones_preventivas=json_encode($descripcion_ACCI_PREVENT);
            $objecto->verificacion_plan_mejora=$request->verificacion_plan_mejora;
           
           $valor_response = $objecto->save();
          DB::commit();

      }catch(\Exception $e) { 
          DB::rollback();
      }

     return json_encode($valor_response); 
  }
  public function actualizarFormato(Request $request){
		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;

      $valor_response=0;
      try{
         DB::beginTransaction(); 
            
            $data = $request->all();
            $formato = new Formato_incidente_evento_adverso; 
            
            $id_relacionado_PERS_CONV_EVEAD = array();
            if(!empty($request->id_relacionado_PERS_CONV_EVEAD)){
              foreach ($request->id_relacionado_PERS_CONV_EVEAD as $key => $value) {
                $id_relacionado_PERS_CONV_EVEAD[]=array('personal_convocado'=>$value);
              }
            }
            // if($request->categoria_evento_adverso !='*' && $request->categoria_tipo_evento_adverso !='*'){
            //   $objecto->responsable_categoria_adverso=$request->categoria_evento_adverso;
            //   $objecto->responsable_tipo_adverso=$request->categoria_tipo_evento_adverso;
            // }
            $descripcion_ACCI_CORRECT = array();
           
            if(!empty($request->descripcion_ACCI_CORRECT)){
              foreach ($request->descripcion_ACCI_CORRECT as $key => $value) {
                if(!empty($data['descripcion_detalle_ACCI_CORRECT'.$key])){$implementado=$data['descripcion_detalle_ACCI_CORRECT'.$key];}else{$implementado="";}
                $descripcion_ACCI_CORRECT[]=array('acciones_correctivas'=>$value,'implementado'=>$implementado);
              }
            }

            $descripcion_ACCI_PREVENT = array();
            if(!empty($request->descripcion_ACCI_PREVENT)){
              foreach ($request->descripcion_ACCI_PREVENT as $key => $value) {
                if(!empty($data['descripcion_detalle_ACCI_PREVENT'.$key])){$implementado=$data['descripcion_detalle_ACCI_PREVENT'.$key];}else{$implementado="";}
                  $descripcion_ACCI_PREVENT[]=array('acciones_preventivas'=>$value,'implementado'=>$implementado);
              }
            }
          
            $valor_response = $formato::where('id',$request->id_incidencia)->update([
              'id_usuario_creacion'=> Auth::user()->id,
              'fecha_notificacion'=>$request->fecha_notificacion,
              'fecha_verificacion'=>$request->fecha_incidente_verificacion,
              'notificador_tipo_personal'=>$request->notificador_tipo_personal,
              'notificador_tipo_registro'=>$request->notificador_tipo_registro,
              'notificador_tipo_personal_descripcion'=>$request->notificador_tipo_personal_descripcion,
              'notificador_fecha_evento'=>$request->notificador_fecha_evento,
              'notificador_hora_evento'=>$request->notificador_hora_evento,
              'notificador_tipo_evento_a'=>$request->selecTipoAdv,
              'notificador_observaciones'=>$request->notificador_observaciones,
              'responsable_analisis_causas'=>$request->responsable_analisis_causas,
              'responsable_personal_convocado'=>json_encode($id_relacionado_PERS_CONV_EVEAD),
              'planmejora_acciones_correctivas'=>json_encode($descripcion_ACCI_CORRECT),
              'responsable_categoria_adverso'=>$request->categoria_evento_adverso,
              'responsable_tipo_adverso'=>$request->categoria_tipo_evento_adverso,
              'planmejora_acciones_preventivas'=>json_encode($descripcion_ACCI_PREVENT),
              'verificacion_plan_mejora'=>$request->verificacion_plan_mejora
            ]);
          DB::commit();

      }catch(\Exception $e) { 
          DB::rollback();
      }

     return json_encode($valor_response); 
  }
  public function listaFormatoIncidenciaEventoA(Request $request){
		if (!Auth::check()) :
			return redirect('/auth/login');
		endif; 
 
    $objFormatoIncidenciaEventoA = new Formato_incidente_evento_adverso; 
		$data = $objFormatoIncidenciaEventoA->listaFormatoIncidenciaEventoA($request->tipo,$request->fecha_inicial,$request->fecha_final); 
    return json_encode($data);   
  }
  
  public function pdf_formato_incidencia_evento_adverso($id_incidentes){
    if (!Auth::check()) :
			return redirect('/auth/login');
		endif; 
    
    $date = Carbon::now();
    $fechaActual = $date->format('d/m/Y');
    $horaActual = $date->format('H:i:s');
    $data = "";
    $id_establecimiento=Auth::user()->id_establecimiento; 
    $model_establecimiento = new \App\Models\Establecimiento();
    $establecimiento=  $model_establecimiento->GetById($id_establecimiento);
    $nombre_establecimiento=$establecimiento[0]->nombre;

    $objFormatoIncidenciaEventoA = new Formato_incidente_evento_adverso; 
    $data = $objFormatoIncidenciaEventoA->getFormatoIncidenteEventoAdversoById($id_incidentes);

    $pdf = \PDF::loadView('frontend.hospitalizacion.enfermeria.incidentes.pdf_incidentes.pdf_formato_incidentes_eventos_adversos', 
    compact(
      'fechaActual',
      'horaActual',
      'data',
      'nombre_establecimiento'
    ));
    $pdf->getDomPDF()->set_option("enable_php", true);
    $pdf->setPaper('A4', 'portrait'); //Horizontal
    return $pdf->stream('formato_incidentes_eventos_adversos.pdf'); 

  }
  public function pdf_formato_incidencia_evento_adverso_emergencia($id_incidentes){
    if (!Auth::check()) :
			return redirect('/auth/login');
		endif; 
    
    $date = Carbon::now();
    $fechaActual = $date->format('d/m/Y');
    $horaActual = $date->format('H:i:s');
    $data = "";
    $id_establecimiento=Auth::user()->id_establecimiento; 
    $model_establecimiento = new \App\Models\Establecimiento();
    $establecimiento=  $model_establecimiento->GetById($id_establecimiento);
    $nombre_establecimiento=$establecimiento[0]->nombre;

    $objFormatoIncidenciaEventoA = new Formato_incidente_evento_adverso; 
    $data = $objFormatoIncidenciaEventoA->getFormatoIncidenteEventoAdversoById_emergencia($id_incidentes);

    $pdf = \PDF::loadView('frontend.emergencia_enfermeria.incidentes.pdf_incidentes.pdf_formato_incidentes_eventos_adversos', 
    compact(
      'fechaActual',
      'horaActual',
      'data',
      'nombre_establecimiento'
    ));
    $pdf->getDomPDF()->set_option("enable_php", true);
    $pdf->setPaper('A4', 'portrait'); //Horizontal
    return $pdf->stream('formato_incidentes_eventos_adversos.pdf'); 

  }
  public function ver_incidencia(Request $request){
    if (!Auth::check()) :
			return redirect('/auth/login');
		endif;
  
    $data = "";
    $objFormatoIncidenciaEventoA = new Formato_incidente_evento_adverso; 
    $data = $objFormatoIncidenciaEventoA->getFormatoIncidenteEventoAdversoById($request->id_incidencia);
    return  json_encode($data);
  }
  public function ver_incidencia_emergencia(Request $request){
    if (!Auth::check()) :
			return redirect('/auth/login');
		endif;
  
    $data = "";
    $objFormatoIncidenciaEventoA = new Formato_incidente_evento_adverso; 
    $data = $objFormatoIncidenciaEventoA->getFormatoIncidenteEventoAdversoById_emergencia($request->id_incidencia);
    return  json_encode($data);
  }
  public function getDiagnosticosByIdPacienteAndIdHospitalizacion(Request $request){
    $objFormatoIncidenciaEventoA = new Formato_incidente_evento_adverso; 
		$data = $objFormatoIncidenciaEventoA->getDiagnosticosByIdPacienteAndIdHospitalizacion($request->id_hospitalizacion,$request->dni_paciente);
    return  json_encode($data);
  }


  public function listaFormatoIncidenciaEventoA_Emergencias(Request $request){
		if (!Auth::check()) :
			return redirect('/auth/login');
		endif; 
    
    $objFormatoIncidenciaEventoA = new Formato_incidente_evento_adverso; 
		$data = $objFormatoIncidenciaEventoA->listaFormatoIncidenciaEventoA_Emergencia($request->tipo,$request->fecha_inicial,$request->fecha_final); 
    dd($data);
    return json_encode($data);   
  }

  public function getDiagnosticosByIdPacienteAndId_Emergencia(Request $request){
    //dd($request->all());
    $objFormatoIncidenciaEventoA = new Formato_incidente_evento_adverso; 
		$data = $objFormatoIncidenciaEventoA->getDiagnosticosByIdPacienteAndId_Emergencia($request->id_emergencia,$request->dni_paciente);


    return  json_encode($data);
  }
  
}