<?php
namespace App\Http\Controllers;

use App\Models\antecedentesEnfermeria;
use App\Models\caracterFisico;
use App\Models\Emergencia;
use App\Models\Enfermeria;
use App\Models\enfermeriaDetalle;
use App\Models\Prestacion_procedimiento;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Nota_enfermeria;
use App\Models\Nota_enfermeria_evoluciones;
use App\Models\Nota_enfermeria_evoluciones_detalle;
use App\Models\TipoAtencione;
use App\Models\Turno;
use App\Models\Punto;
use App\Models\Hospitalizacione;
use App\Models\Parametros;
use App\Models\pielDispositivosEnfermerias;
use App\Models\enfermeria_escalas;
use App\Models\Prestaciones;
use App\Models\SignosVitales;
use App\Models\valoracionesEnfermerias;
use Archive7z\Archive7z;
use Auth;
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Nota_enfermeriaController extends Controller
{    
 
	function __construct()
    {
        $this->middleware('auth'); 
    } 
    
    public function formulario_nota_ingreso($id_atencion, $id_tipo_atenciones){ 
		if (!Auth::check()) :
			return redirect('/auth/login');
		endif;

		$modelfarma = new \App\Models\Farmacia;
		$model_receta = new \App\Models\Receta_vale;
		$model_receta_diagnostico = new \App\Models\Receta_diagnostico;
		$model_establecimiento = new \App\Models\Establecimiento;
		$model_hospitalizacion = new  Hospitalizacione; 

		if($id_tipo_atenciones == 3){  /// INGRESA POR HOSPITALIZACION
			$verificar_existe = Hospitalizacione::where('id', $id_atencion)->first();  
			$id_hospitalizacion = $id_atencion;

			if (is_object($verificar_existe)) {
				if($verificar_existe->flag_nota_enfermeria==0){ 
					$farmacia = $modelfarma->GetFarmaByUser(Auth::user()->id); 
					$date = Carbon::now();
					$fecha_actual = $date->format('d-m-Y');
		
					$id_ipress = $farmacia->id_establecimiento;
					$establecimiento = $model_establecimiento->GetById($id_ipress);
					if ($id_ipress == 1) {
						$nivel = 2;
					} else {
						$nivel = $establecimiento[0]->nivel;
					} 
					 
					$codigo_ubig_responsable=$model_receta->getObtener_codigo_ubig_responsable($id_hospitalizacion);
					$codigo_ubig_apoderado=$model_receta->getObtener_codigo_ubig_apoderado($id_hospitalizacion);
					$datos_hospitalizacion = $model_receta->getHospitalizacionById_farmacia_new_hospitalizacion($id_hospitalizacion, $codigo_ubig_responsable, $codigo_ubig_apoderado);

					$diagnosticos = $model_receta_diagnostico->DiagnosticosByIdHospitalizacion($id_hospitalizacion); 

					return view('frontend.hospitalizacion.formulario_nota_ingresos', compact('nivel', 'establecimiento',
					'datos_hospitalizacion', 'id_atencion', 'id_tipo_atenciones', 'diagnosticos', 'id_atencion'));

				}else{
					return redirect('/hospitalizado_by_enfermeria/')->withInput()->withErrors(array('El registro seleccionado ya cuenta con una Nota ya registrada..!'));
				}
			}else {
				return redirect('/hospitalizado_by_enfermeria/')->withInput()->withErrors(array('Elemento no encontrado ..!'));
			}
		}else {
			return redirect('/hospitalizado_by_enfermeria/')->withInput()->withErrors(array('Elemento no encontrado ..!'));
		}
	}

	public function registrar_nota_ingreso_hospitalizado(Request $request){

		if (!Auth::check()) :
			return redirect('/auth/login');
		endif; 
        //dd($request->all());

        $objNota_enfermeria = new Nota_enfermeria;
        $objNota_enfermeria->orientado   = $request->orientado != '' ? $request->orientado : ''; 
        $objNota_enfermeria->emoptisis   = $request->emoptisis != '' ? $request->emoptisis : ''; 
        $objNota_enfermeria->presion_arterial_x = $request->presion_arterial_x != '' ? $request->presion_arterial_x : 0; 
        $objNota_enfermeria->presion_arterial_y = $request->presion_arterial_y != '' ? $request->presion_arterial_y : 0; 
        $objNota_enfermeria->frecuencia_cardiaca = $request->frec_cardiaca != '' ? $request->frec_cardiaca : 0; 
        $objNota_enfermeria->frecuencia_respiratoria = $request->frecuencia_respiratoria != '' ? $request->frecuencia_respiratoria : 0; 
        $objNota_enfermeria->fi02 = $request->oxigenacion != '' ? $request->oxigenacion : 0; 
        $objNota_enfermeria->sa02_porcentaje = $request->sa02_porcentaje != '' ? $request->sa02_porcentaje : 0; 
        $objNota_enfermeria->temperatura = $request->temperatura != '' ? $request->temperatura : 0; 
        $objNota_enfermeria->talla = $request->talla != '' ? $request->talla : 0; 
        $objNota_enfermeria->peso = $request->peso != '' ? $request->peso : 0; 
        $objNota_enfermeria->indice_masa_corporal = $request->imc != '' ? $request->imc : 0; 
        $objNota_enfermeria->peso_x_altura   = $request->peso_x_altura != '' ? $request->peso_x_altura : 0; 
        
        if($request->es_gestante!=null){
            $objNota_enfermeria->es_gestante = $request->es_gestante ;
        }else{
            $objNota_enfermeria->es_gestante = 0 ;
        }
        $objNota_enfermeria->tiene_hta =$request->hta ; 
        $objNota_enfermeria->descripcion_tratamiento_hta = $request->tratamiento_hta;  

        $objNota_enfermeria->diabetes_mellitus =$request->diabetes_mellitus; 
        $objNota_enfermeria->diabetes_mellitus_descripcion = $request->diabetes_mellitus_descripcion ; 
 
 
        $objNota_enfermeria->tiene_alergia =$request->ram;  
        $objNota_enfermeria->descripcion_alergia = $request->detalle_ram_alergia ; 
 

        $objNota_enfermeria->descripcion_antecedente_observacion = $request->descripcion_antecedente_observacion ; 
        $objNota_enfermeria->descripcion_otros_antecedentes = $request->descripcion_otros_antecedentes ; 
 
 
        $objNota_enfermeria->tiene_vacuna_covid =$request->vacuna_covid;  
        $objNota_enfermeria->id_ingresa_por = $request->ingresa_por ; 
 
            if($request->ingresa_por_transferencia != '*' && $request->ingresa_por_transferencia != null){
                $objNota_enfermeria->id_ingresa_por_transferencia = $request->ingresa_por_transferencia ; 
            }else{
                $objNota_enfermeria->id_ingresa_por_transferencia = 0;
            } 


        $objNota_enfermeria->id_condicion_ingreso = $request->condicion_ingreso ; 
        $objNota_enfermeria->id_estado_conciencia = $request->estado_conciencia ; 

        if($request->vehicle1){
            $objNota_enfermeria->valoracion_tiempo = $request->vehicle1 ; 
        }else{
            $objNota_enfermeria->valoracion_tiempo = 'off';
        }
        if($request->vehicle2){
            $objNota_enfermeria->valoracion_espacio = $request->vehicle2 ; 
        }else{
            $objNota_enfermeria->valoracion_espacio = 'off';
        }
        if($request->vehicle3){
            $objNota_enfermeria->valoracion_persona = $request->vehicle3 ; 
        }else{
            $objNota_enfermeria->valoracion_persona = 'off';
        }

        $objNota_enfermeria->grado_dependencia = $request->grado_dependencia ; 
        

        $objNota_enfermeria->id_funcion_motora = $request->funcion_motora ; 
        $objNota_enfermeria->id_respiratorio_duracion = $request->respiratorio_duracion ; 
        $objNota_enfermeria->id_secreaciones_bronquiales = $request->secreaciones_bronquiales ; 
 
        $objNota_enfermeria->tiene_oxigenoterapia =$request->oxigenoterapia;  

        if($request->oxigenoterapia_detalle_seleccion != '*'){
            $objNota_enfermeria->id_oxigenoterapia_detalle_seleccion = $request->oxigenoterapia_detalle_seleccion ; 
        }else{
            $objNota_enfermeria->id_oxigenoterapia_detalle_seleccion = 0;
        }

        
 
        $objNota_enfermeria->tiene_miccion_espontanea =$request->miccion_espontanea; 
        $objNota_enfermeria->miccion_espontanea_tratamiento = $request->miccion_espontanea_tratamiento ; 
        $objNota_enfermeria->miccion_espontanea_hd = $request->miccion_espontanea_hd ; 
        $objNota_enfermeria->miccion_espontanea_fav = $request->miccion_espontanea_fav ; 
        if($request->puntaje_calculado_glasgow==''){
            $objNota_enfermeria->puntaje_calculado_glasgow = 0; 
            $objNota_enfermeria->descripcion_detalle_glasgow = "-"; 
        }else{
            $objNota_enfermeria->puntaje_calculado_glasgow = $request->puntaje_calculado_glasgow ; 
            $objNota_enfermeria->descripcion_detalle_glasgow = $request->descripcion_detalle_Glasgow ; 
        }
        

        $objNota_enfermeria->acompaniado_por = $request->acompaniado_por ;  
        $objNota_enfermeria->descripcion_acompaniado_por = $request->descripcion_acompaniado_por ;  
        $objNota_enfermeria->contacto_acompaniado_por = $request->contacto_acompaniado_por ; 
        
         
        $objNota_enfermeria->tiene_dispositivo_invasivos =$request->dispositivos_invasivos;  
        $objNota_enfermeria->via_periferica = $request->via_periferica ;   
        $objNota_enfermeria->via_central = $request->via_central ;   
        $objNota_enfermeria->caracter_alto_flujo = $request->caracter_alto_flujo ;   
        $objNota_enfermeria->sng_gavedad = $request->sng_gavedad ;
 
        $objNota_enfermeria->tiene_edema =$request->edema; 
 
        $objNota_enfermeria->tiene_sonda =$request->sondas; 
        $objNota_enfermeria->descripcion_sonda = $request->descripcion_sonda ;  
        $objNota_enfermeria->linea_arterial = $request->linea_arterial ;  
 
        $objNota_enfermeria->tiene_drenaje =$request->drenaje;  
        $objNota_enfermeria->descripcion_drenaje = $request->descripcion_drenaje ;  

        if($request->piel_colores != '*'){
            $objNota_enfermeria->id_piel_colores = $request->piel_colores ; 
        }else{
            $objNota_enfermeria->id_piel_colores = 0;
        }
  
        if($request->integridad != '*'){
            $objNota_enfermeria->id_integridad = $request->integridad ;
        }else{
            $objNota_enfermeria->id_integridad = 0;
        }

        if($request->abdomen != '*'){  
            $objNota_enfermeria->id_abdomen = $request->abdomen ;
        }else{
            $objNota_enfermeria->id_abdomen = 0;
        }
 
        $objNota_enfermeria->tiene_evaluacion_mallampaty =$request->evaluacion_mallampaty; 
        $objNota_enfermeria->descripcion_evaluacion_mallampaty = $request->evaluacion_mallampaty_seleccion ;  
 
        if($request->puntaje_calculado_lpp==''){
            $objNota_enfermeria->puntaje_calculado_lpp = 0;  
        }else{
            $objNota_enfermeria->puntaje_calculado_lpp = $request->puntaje_calculado_lpp ;  
        }
        if($request->puntaje_calculado_caidas==''){
            $objNota_enfermeria->puntaje_calculado_caidas = 0;  
        }else{
            $objNota_enfermeria->puntaje_calculado_caidas = $request->puntaje_calculado_caidas ;  
        }

        $objNota_enfermeria->escalas_de_dolor = $request->escalas ;   
        $objNota_enfermeria->tiene_brazalete =$request->brazalete; 
        $objNota_enfermeria->brazalete_seleccion = $request->brazalete_seleccion ;  
        $objNota_enfermeria->observacion_detallada = $request->observacion_detallada ; 
        $objNota_enfermeria->id_atencion = $request->id_atencion ; 
        $objNota_enfermeria->id_tipo_atenciones = $request->id_tipo_atenciones ; 
        
        if($request->peso_x_altura!=null){
            $objNota_enfermeria->peso_x_altura = $request->peso_x_altura ; 
        }else{ 
            $objNota_enfermeria->peso_x_altura = 0.00; 
        }
 
        $objNota_enfermeria->img_puntos_base = $request->img_puntos_base ; 

        // COMBO DE CARDIOLOGICO -- COMBO DE MULTIPLE SELEC
        // COMBO DE CARDIOLOGICO -- COMBO DE MULTIPLE SELEC
        $p_cardiologico = '';
        //$p_diagnostico .= "{";
        //dd($request->cardiologico);
        if (isset($request->cardiologico)) {
        foreach ($request->cardiologico as $key => $value) :
            if ($request->cardiologico[$key] != "") {
            $p_cardiologico .= $request->cardiologico[$key];
            $p_cardiologico .= ',';
            }
        endforeach;

        //dd($p_cardiologico);
        if (strlen($p_cardiologico) > 1) $p_cardiologico = substr($p_cardiologico, 0, -1);
            $p_cardiologico .= '';
        }else{
            $p_cardiologico = '';
        }
        //dd($p_cardiologico);

        $objNota_enfermeria->cardiologico = $p_cardiologico ; 
        // COMBO DE CARDIOLOGICO -- COMBO DE MULTIPLE SELEC
        // COMBO DE CARDIOLOGICO -- COMBO DE MULTIPLE SELEC
        
        $objNota_enfermeria->id_establecimiento = Auth::user()->id_establecimiento;// id_establecimiento = 76 - 1 mdico  
        $objNota_enfermeria->id_usuario_registro = Auth::user()->id ; 
        $objNota_enfermeria->save();  

        
        if($request->id_tipo_atenciones == 3){ ///HOSPITALIZACION
            //verifico si existe la hospitalizacion
            $hospitalizacion_existe = Hospitalizacione::where('id', $request->id_atencion )->first();
            if($hospitalizacion_existe){
                $hospitalizacion_existe->flag_nota_enfermeria = 1 ; 
                $hospitalizacion_existe->id_nota_enfermeria = $objNota_enfermeria->id ; 
                $hospitalizacion_existe->save();  
            }
        }else if($request->id_tipo_atenciones == 2){ ///EMERGENCIA

        }
        //era esto me equiboque
		return redirect('/ver_nota_ingreso_enfermeria/' . $objNota_enfermeria->id.'/' . $request->id_tipo_atenciones); 
	}
    private function crearDetalleEnfermeria($id_enfermeria, $id_caracter_fisico, $descripcion)
    {
        enfermeriaDetalle::create([
            'id' => $id_enfermeria,
            'id_caracter_fisico' => $id_caracter_fisico,
            'descripcion' => $descripcion,
            'created_us' => Auth::id(),
            'updated_us' => Auth::id(),
        ]);
    }

  
    /* public function registrar_nota_ingreso_emergencia(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }
        // Crear un nuevo objeto Enfermeria y asignar valores
        // Crear un nuevo objeto Enfermeria y asignar valores
        $rules = [
            'id_prestacion' => 'nullable|integer',
            'id_atencion' => 'required|integer',
            // Otras reglas de validación según sea necesario
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $objNota_enfermeria = new Enfermeria;
        $objNota_enfermeria->id_prestacion = $request->id_prestacion;
        $objNota_enfermeria->id_tipo_atencion = 2; 
        $objNota_enfermeria->id_estado_reg = 1; 
        $objNota_enfermeria->created_us = Auth::id();
        $objNota_enfermeria->id_ext_enfermeria = $request->has('id_ext_enfermeria') ? $request->id_ext_enfermeria : $request->id_atencion;
        $objNota_enfermeria->img_base = $request->img_puntos_base;
        $objNota_enfermeria->observacion_enfermeria = $request->observacion_detallada;
        $objNota_enfermeria->save();  

        $datopunto = $request->datojhon;
        $arreglojhon = json_decode($datopunto, true);
 
        foreach($arreglojhon as $arreglopuntos){
       
            if(isset($arreglopuntos)){
            
                if($arreglopuntos['color'] == '*'){

                }else{
                    $puntos = new punto;
                    $puntos->id_enfermeria = $objNota_enfermeria->id;
                    $puntos->x = $arreglopuntos['x'];
                    $puntos->y = $arreglopuntos['y'];
                    $puntos->color = $arreglopuntos['color'];
                    if($arreglopuntos['color'] == 'blue'){
                        $puntos->descripcion_otros = $arreglopuntos['detalleotro'];
                    }else{
                        $puntos->descripcion_otros = '';
                    }
                    if($arreglopuntos['color'] == 'blue'){
                        $puntos->descripcion_proceso = 'OTROS';
                        $puntos->descripcion_proceso_b = 'OTROS';
                        
                    }
                    if($arreglopuntos['color'] == 'orange'){
                        $puntos->descripcion_proceso = 'PIE DIABÉTICO';
                        $puntos->descripcion_proceso_b = 'PIE DIABÉTICO';
                    }
                    if($arreglopuntos['color'] == 'red'){
                        $puntos->descripcion_proceso = 'LPP';
                        $puntos->descripcion_proceso_b = 'LPP';
                    }
                    if($arreglopuntos['color'] == 'green'){
                        $puntos->descripcion_proceso = 'DISPOSITIVOS';
                        $puntos->descripcion_proceso_b = 'DISPOSITIVOS';
                    }
                    if($arreglopuntos['color'] == 'yellow'){
                        $puntos->descripcion_proceso = 'DAI';
                        $puntos->descripcion_proceso_b = 'DAI';
                    }
                    $puntos->id_usuario = Auth::id();
                    $puntos->tipo_registro = 'NOTA_ENFERMERIA';
                    $puntos->id_tipo_atenciones = 2;
                    $puntos->tama = 10;
                    $puntos->save();
                }
            }
        }

        $datonota = array();
            $datonota[1] = $request->presion_arterial_x;
            $datonota[2] = $request->presion_arterial_y;
            $datonota[3] = $request->talla;
            $datonota[4] = $request->frec_cardiaca;
            $datonota[5] = $request->peso;
            $datonota[6] = $request->frecuencia_respiratoria;
            $datonota[7] = $request->oxigenacion1;
            $datonota[8] = $request->sa02_porcentaje;
            $datonota[9] = $request->temperatura;
            $datonota[10] = $request->imc;
            $datonota[11] = $request->peso_x_altura;

        for ($i = 1; $i <= 11; $i++) {
            $nota_vitales_enfermeria = new enfermeriaDetalle;
            $nota_vitales_enfermeria->id_caracter_fisico = $i;
            $nota_vitales_enfermeria->id_enfermeria = $objNota_enfermeria->id;
            $nota_vitales_enfermeria->descripcion = $datonota[$i];
            $nota_vitales_enfermeria->created_us = Auth::id();
            $nota_vitales_enfermeria->updated_us = Auth::id();
            $nota_vitales_enfermeria->save();
        }

        $hipertension = $request->hta == 'SI' ? 1 : 0;
        $diabetes_mellitus = $request->diabetes_mellitus == 'SI' ? 1 : 0;
        $ram = $request->ram == 'SI' ? 1 : 0;
        $ram_hemodialisis = $request->ram_hemodialisis == 'SI' ? 1 : 0;
        $descripcion_antecedente_observacion=$request->descripcion_antecedente_observacion;
        $descripcion_otros_antecedentes=$request->descripcion_otros_antecedentes;
        $dispositivos_invasivos = $request->dispositivos_invasivos == 'SI' ? 1 : 0;
        $via_periferica = $request->via_periferica == 'SI' ? 1 : 0;

        if (is_array($request->cardiologico)) {
            $cardio0 = isset($request->cardiologico[0]) ? $request->cardiologico[0] : null;
            $cardio1 = isset($request->cardiologico[1]) ? $request->cardiologico[1] : null;
            $cardio2 = isset($request->cardiologico[2]) ? $request->cardiologico[2] : null;
            $cardio3 = isset($request->cardiologico[3]) ? $request->cardiologico[3] : null;
        } else {
            $cardio0 = $cardio1 = $cardio2 = $cardio3 = null;
        }
    /////
    $antecedentes = new AntecedentesEnfermeria;
    $antecedentes->id_enfermeria = $objNota_enfermeria->id;
    $antecedentes->id_hipertension = $hipertension;
    $antecedentes->descripcion_hipertension = $request->tratamiento_hta;
    $antecedentes->id_diabetes_millitus = $diabetes_mellitus;
    $antecedentes->descripcion_diabetes_millitus = $request->diabetes_mellitus_descripcion;
    $antecedentes->id_alergia=$ram;
    $antecedentes->descripcion_alergia = $request->detalle_ram_alergia;
    $antecedentes->id_hemodialisis=$ram_hemodialisis;
    $antecedentes->descripcion_hemodialisis = $request->detalle_ram_hemo;
    $antecedentes->descripcion_observacion=$descripcion_antecedente_observacion;
    $antecedentes->descripcion_otro=$descripcion_otros_antecedentes;
    $antecedentes->cardio_parametro_1=$cardio0;
    $antecedentes->cardio_parametro_2=$cardio1;
    $antecedentes->cardio_parametro_3=$cardio2;
    $antecedentes->cardio_parametro_4=$cardio3;
    $antecedentes->created_us = Auth::id();
    $antecedentes->created_at = date('Y-m-d H:i:s');
    $antecedentes->save();
    /////
    $valoraciones = new valoracionesEnfermerias;
    $valoraciones->id_enfermeria = $objNota_enfermeria->id;
    $valoraciones->id_ingresa_por = '10';
    $valoraciones->id_funcion_motora = $request->funcion_motora;
    $valoraciones->id_condicion_ingreso = $request->condicion_ingreso;
    $valoraciones->id_respiratorio = $request->respiratorio_duracion;
    $valoraciones->id_estado_conciencia = $request->estado_conciencia;
    $valoraciones->id_secrecionbronquiales = $request->secreaciones_bronquiales_si == 'SI' ? 1 : 0;
    $valoraciones->grado_dependencia = $request->grado_dependencia;
    $valoraciones->id_oxigenoterapia = $request->oxigenoterapia == 'SI' ? 1 : 0;
    if( $request->oxigenoterapia_detalle_seleccion != '*'){
        $valoraciones->oxigenoterapia_parametro = $request->oxigenoterapia_detalle_seleccion;
    }
    $valoraciones->id_miccion_espontanea = $request->miccion_espontanea == 'SI' ? 1 : 0;
    //$valoraciones->descripcion_miccion_espontanea = null; 
    $valoraciones->punto_calculado_glasgow = $request->puntaje_calculado_glasgow;
    $valoraciones->descripcion_calculado_glasgow = $request->descripcion_detalle_Glasgow;
    $valoraciones->id_acompanante = $request->acompaniado_por == 'FAMILIAR' ? 0 : 1;
    $valoraciones->descrip_acompanante = $request->descripcion_acompaniado_por;
    //$valoraciones->numero_acompanante = null; 
    $valoraciones->id_hemoptisis = $request->emoptisis == 'SI' ? 1 : 0;
    //$valoraciones->descripcion_hemoptisis = null; 
    $valoraciones->id_piel = $request->piel_colores;
    $valoraciones->id_edema = $request->edema == 'SI' ? 1 : 0;
    //$valoraciones->descripcion_edema =  null;
    $valoraciones->id_integridad = $request->integridad;
    $valoraciones->id_abdomen = $request->abdomen;
    $valoraciones->id_mallampaty = $request->evaluacion_mallampaty == 'SI' ? 1 : 0;
    $valoraciones->descripcion_mallampaty = $request->evaluacion_mallampaty_seleccion;
    $valoraciones->orientado_detalle_tiempo = $request->vehicle1 == 'on' ? 1 : 0;
    $valoraciones->orientado_detalle_espacio = $request->vehicle2 == 'on' ? 1 : 0;
    $valoraciones->orientado_detalle_persona = $request->vehicle3 == 'on' ? 1 : 0;
    $valoraciones->created_us = Auth::id();
    $valoraciones->created_at = date('Y-m-d H:i:s');
    $valoraciones->save();
    ////////
    $pieldispositivcosenfermeria = new pielDispositivosEnfermerias;
    $pieldispositivcosenfermeria->id_enfermeria =  $objNota_enfermeria->id;
    $pieldispositivcosenfermeria->id_dispositivo_invasivos = $request->dispositivos_invasivos == 'SI' ? 1 : 0;
    $pieldispositivcosenfermeria->id_via_periferica = $request->via_periferica == 'SI' ? 1 : 0;
    $pieldispositivcosenfermeria->id_via_central = $request->via_central == 'SI' ? 1 : 0;
    $pieldispositivcosenfermeria->id_caracter_alto_flujo =  $request->caracter_alto_flujo == 'SI' ? 1 : 0;
    $pieldispositivcosenfermeria->id_sonda = $request->sondas == 'SI' ? 1 : 0;
    $pieldispositivcosenfermeria->descripcion_sonda = $request->descripcion_sonda;
    $pieldispositivcosenfermeria->id_linea_arterial = $request->linea_arterial == 'SI' ? 1 : 0;
    $pieldispositivcosenfermeria->id_tiene_drenaje = $request->drenaje == 'SI' ? 1 : 0;
    $pieldispositivcosenfermeria->descripcion_drenaje = $request->descripcion_drenaje;
    //$pieldispositivcosenfermeria->id_proceso_parametros = $request->descripcion_sonda;
    $pieldispositivcosenfermeria->created_us = Auth::id();
    $pieldispositivcosenfermeria->created_at = date('Y-m-d H:i:s');
    $pieldispositivcosenfermeria->save();
    //////
    $escalas = new enfermeria_escalas;
    $escalas->id_enfermeria =  $objNota_enfermeria->id;
    $escalas->escala_rlp =  $request->puntaje_calculado_lpp;
    $escalas->valoracion_rlp =  $request->descripcion_detalle_riesgolpp;
    $escalas->id_color_brazalete =  $request->brazalete_seleccion;
    if($request->brazalete == 'SI'){
        $escalas->id_brazalete = 1;
    }

    $escalas->escala_rc =  $request->puntaje_calculado_caidas;
    $escalas->valoracion_rc =  $request->descripcion_detalle_caidas;
    $escalas->escala_dolor =  $request->escalas;
    $escalas->created_us = Auth::id();
    $escalas->created_at = date('Y-m-d H:i:s');
    $escalas->save();

        return redirect('/ver_nota_ingreso_enfermeria_emergencia/' . $objNota_enfermeria->id . '/' . $request->id_tipo_atenciones);
    }
    public function registrar_nota_ingreso_hospitalizacion(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }
        // Crear un nuevo objeto Enfermeria y asignar valores
        // Crear un nuevo objeto Enfermeria y asignar valores
        $rules = [
            'id_prestacion' => 'nullable|integer',
            'id_atencion' => 'required|integer',
            // Otras reglas de validación según sea necesario
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $id_ext = $request->id_ext_enfermeria;
        $existeNota = Enfermeria::where('id_ext_enfermeria', $id_ext)
                                ->where('id_tipo_atencion', 3)
                                ->first();
        if ($existeNota != null) {
			return redirect('/hospitalizacion_by_enfermeria/')->withInput()->withErrors(array('Ya existe una nota de enfermeria para el registro..!'));
        }
        
        $objNota_enfermeria = new Enfermeria;
        $objNota_enfermeria->id_prestacion = $request->id_prestacion;
        $objNota_enfermeria->id_tipo_atencion = 3; // HOSPITALIZACION = 3
        $objNota_enfermeria->id_estado_reg = 1; 
        $objNota_enfermeria->created_us = Auth::id();
        $objNota_enfermeria->id_ext_enfermeria = $id_ext;
        $objNota_enfermeria->img_base = $request->img_puntos_base;
        $objNota_enfermeria->observacion_enfermeria = $request->observacion_detallada;
        $objNota_enfermeria->save();  

        $datopunto = $request->datojhon;
        $arreglojhon = json_decode($datopunto, true);

        if (isset($arreglojhon)) {
            foreach($arreglojhon as $arreglopuntos){
           
                if(isset($arreglopuntos)){
                
                    if($arreglopuntos['color'] == '*'){
    
                    }else{
                        $puntos = new punto;
                        $puntos->id_enfermeria = $objNota_enfermeria->id;
                        $puntos->x = $arreglopuntos['x'];
                        $puntos->y = $arreglopuntos['y'];
                        $puntos->color = $arreglopuntos['color'];
                        if($arreglopuntos['color'] == 'blue'){
                            $puntos->descripcion_otros = $arreglopuntos['detalleotro'];
                        }else{
                            $puntos->descripcion_otros = '';
                        }
                        if($arreglopuntos['color'] == 'blue'){
                            $puntos->descripcion_proceso = 'OTROS';
                            $puntos->descripcion_proceso_b = 'OTROS';
                            
                        }
                        if($arreglopuntos['color'] == 'orange'){
                            $puntos->descripcion_proceso = 'PIE DIABÉTICO';
                            $puntos->descripcion_proceso_b = 'PIE DIABÉTICO';
                        }
                        if($arreglopuntos['color'] == 'red'){
                            $puntos->descripcion_proceso = 'LPP';
                            $puntos->descripcion_proceso_b = 'LPP';
                        }
                        if($arreglopuntos['color'] == 'green'){
                            $puntos->descripcion_proceso = 'DISPOSITIVOS';
                            $puntos->descripcion_proceso_b = 'DISPOSITIVOS';
                        }
                        if($arreglopuntos['color'] == 'yellow'){
                            $puntos->descripcion_proceso = 'DAI';
                            $puntos->descripcion_proceso_b = 'DAI';
                        }
                        $puntos->id_usuario = Auth::id();
                        $puntos->tipo_registro = 'NOTA_ENFERMERIA';
                        $puntos->id_tipo_atenciones = 3;
                        $puntos->tama = 10;
                        $puntos->save();
                    }
                }
            }
        }
 
        $datonota = array();
            $datonota[1] = $request->presion_arterial_x;
            $datonota[2] = $request->presion_arterial_y;
            $datonota[3] = $request->talla;
            $datonota[4] = $request->frec_cardiaca;
            $datonota[5] = $request->peso;
            $datonota[6] = $request->frecuencia_respiratoria;
            $datonota[7] = $request->oxigenacion1;
            $datonota[8] = $request->sa02_porcentaje;
            $datonota[9] = $request->temperatura;
            $datonota[10] = $request->imc;
            $datonota[11] = $request->peso_x_altura;

        for ($i = 1; $i <= 11; $i++) {
            $nota_vitales_enfermeria = new enfermeriaDetalle;
            $nota_vitales_enfermeria->id_caracter_fisico = $i;
            $nota_vitales_enfermeria->id_enfermeria = $objNota_enfermeria->id;
            $nota_vitales_enfermeria->descripcion = $datonota[$i];
            $nota_vitales_enfermeria->created_us = Auth::id();
            $nota_vitales_enfermeria->updated_us = Auth::id();
            $nota_vitales_enfermeria->save();
        }

        $hipertension = $request->hta == 'SI' ? 1 : 0;
        $diabetes_mellitus = $request->diabetes_mellitus == 'SI' ? 1 : 0;
        $ram = $request->ram == 'SI' ? 1 : 0;
        $ram_hemodialisis = $request->ram_hemodialisis == 'SI' ? 1 : 0;
        $descripcion_antecedente_observacion=$request->descripcion_antecedente_observacion;
        $descripcion_otros_antecedentes=$request->descripcion_otros_antecedentes;
        $dispositivos_invasivos = $request->dispositivos_invasivos == 'SI' ? 1 : 0;
        $via_periferica = $request->via_periferica == 'SI' ? 1 : 0;

        if (is_array($request->cardiologico)) {
            $cardio0 = isset($request->cardiologico[0]) ? $request->cardiologico[0] : null;
            $cardio1 = isset($request->cardiologico[1]) ? $request->cardiologico[1] : null;
            $cardio2 = isset($request->cardiologico[2]) ? $request->cardiologico[2] : null;
            $cardio3 = isset($request->cardiologico[3]) ? $request->cardiologico[3] : null;
        } else {
            $cardio0 = $cardio1 = $cardio2 = $cardio3 = null;
        }
    /////
    $antecedentes = new AntecedentesEnfermeria;
    $antecedentes->id_enfermeria = $objNota_enfermeria->id;
    $antecedentes->id_hipertension = $hipertension;
    $antecedentes->descripcion_hipertension = $request->tratamiento_hta;
    $antecedentes->id_diabetes_millitus = $diabetes_mellitus;
    $antecedentes->descripcion_diabetes_millitus = $request->diabetes_mellitus_descripcion;
    $antecedentes->id_alergia=$ram;
    $antecedentes->descripcion_alergia = $request->detalle_ram_alergia;
    $antecedentes->id_hemodialisis=$ram_hemodialisis;
    $antecedentes->descripcion_hemodialisis = $request->detalle_ram_hemo;
    $antecedentes->descripcion_observacion=$descripcion_antecedente_observacion;
    $antecedentes->descripcion_otro=$descripcion_otros_antecedentes;
    $antecedentes->cardio_parametro_1=$cardio0;
    $antecedentes->cardio_parametro_2=$cardio1;
    $antecedentes->cardio_parametro_3=$cardio2;
    $antecedentes->cardio_parametro_4=$cardio3;
    $antecedentes->created_us = Auth::id();
    $antecedentes->created_at = date('Y-m-d H:i:s');
    $antecedentes->save();
    /////
    $valoraciones = new valoracionesEnfermerias;
    $valoraciones->id_enfermeria = $objNota_enfermeria->id;
    $valoraciones->id_ingresa_por = '14';
    $valoraciones->id_funcion_motora = $request->funcion_motora;
    $valoraciones->id_condicion_ingreso = $request->condicion_ingreso;
    $valoraciones->id_respiratorio = $request->respiratorio_duracion;
    $valoraciones->id_estado_conciencia = $request->estado_conciencia;
    $valoraciones->id_secrecionbronquiales = $request->secreaciones_bronquiales_si == 'SI' ? 1 : 0;
    $valoraciones->grado_dependencia = $request->grado_dependencia;
    $valoraciones->id_oxigenoterapia = $request->oxigenoterapia == 'SI' ? 1 : 0;
    $valoraciones->oxigenoterapia_fi02 = $request->oxigenoterapia_fi02 != '' ? $request->oxigenoterapia_fi02 : 0;
    if( $request->oxigenoterapia_detalle_seleccion != '*'){
        $valoraciones->oxigenoterapia_parametro = $request->oxigenoterapia_detalle_seleccion;
    }
    $valoraciones->id_miccion_espontanea = $request->miccion_espontanea == 'SI' ? 1 : 0;
    //$valoraciones->descripcion_miccion_espontanea = null; 
    $valoraciones->punto_calculado_glasgow = $request->puntaje_calculado_glasgow != '' ? $request->puntaje_calculado_glasgow : null;
    $valoraciones->descripcion_calculado_glasgow = $request->descripcion_detalle_Glasgow;
    $valoraciones->punto_calculado_rass = $request->puntaje_calculado_rass_memoria != '' ? $request->puntaje_calculado_rass_memoria : null;
    $valoraciones->descripcion_calculado_rass = $request->desc_rass;
    $valoraciones->id_acompanante = intval($request->acompaniado_por);
    $valoraciones->descrip_acompanante = $request->descripcion_acompaniado_por;
    //$valoraciones->numero_acompanante = null; 
    $valoraciones->id_hemoptisis = $request->emoptisis == 'SI' ? 1 : 0;
    //$valoraciones->descripcion_hemoptisis = null; 
    $valoraciones->id_piel = $request->piel_colores == '*' ? null : $request->piel_colores;
    $valoraciones->id_edema = $request->edema == 'SI' ? 1 : 0;
    //$valoraciones->descripcion_edema =  null;
    $valoraciones->id_integridad = $request->integridad;
    $valoraciones->id_abdomen = $request->abdomen;
    $valoraciones->id_mallampaty = $request->evaluacion_mallampaty == 'SI' ? 1 : 0;
    $valoraciones->descripcion_mallampaty = $request->evaluacion_mallampaty_seleccion;
    $valoraciones->orientado_detalle_tiempo = $request->vehicle1 == 'on' ? 1 : 0;
    $valoraciones->orientado_detalle_espacio = $request->vehicle2 == 'on' ? 1 : 0;
    $valoraciones->orientado_detalle_persona = $request->vehicle3 == 'on' ? 1 : 0;
    $valoraciones->created_us = Auth::id();
    $valoraciones->created_at = date('Y-m-d H:i:s');
    $valoraciones->save();
    ////////
    $pieldispositivcosenfermeria = new pielDispositivosEnfermerias;
    $pieldispositivcosenfermeria->id_enfermeria =  $objNota_enfermeria->id;
    $pieldispositivcosenfermeria->id_dispositivo_invasivos = $request->dispositivos_invasivos == 'SI' ? 1 : 0;
    $pieldispositivcosenfermeria->id_via_periferica = $request->via_periferica == 'SI' ? 1 : 0;
    $pieldispositivcosenfermeria->id_via_central = $request->via_central == 'SI' ? 1 : 0;
    $pieldispositivcosenfermeria->id_caracter_alto_flujo =  $request->caracter_alto_flujo == 'SI' ? 1 : 0;
    $pieldispositivcosenfermeria->id_caracter_curacion =  $request->caracter_curacion == 'SI' ? 1 : 0;
    $pieldispositivcosenfermeria->id_sonda = $request->sondas == 'SI' ? 1 : 0;
    $pieldispositivcosenfermeria->descripcion_sonda = $request->descripcion_sonda;
    $pieldispositivcosenfermeria->id_linea_arterial = $request->linea_arterial == 'SI' ? 1 : 0;
    $pieldispositivcosenfermeria->id_tiene_drenaje = $request->drenaje == 'SI' ? 1 : 0;
    $pieldispositivcosenfermeria->descripcion_drenaje = $request->descripcion_drenaje;
    //$pieldispositivcosenfermeria->id_proceso_parametros = $request->descripcion_sonda;
    $pieldispositivcosenfermeria->created_us = Auth::id();
    $pieldispositivcosenfermeria->created_at = date('Y-m-d H:i:s');
    $pieldispositivcosenfermeria->save();
    //////
    $escalas = new enfermeria_escalas;
    $escalas->id_enfermeria =  $objNota_enfermeria->id;
    $escalas->escala_rlp =  $request->puntaje_calculado_lpp;
    $escalas->valoracion_rlp =  $request->descripcion_detalle_riesgolpp;
    $escalas->id_color_brazalete =  $request->brazalete_seleccion;
    if($request->brazalete == 'SI'){
        $escalas->id_brazalete = 1;
    }

    $escalas->escala_rc =  $request->puntaje_calculado_caidas;
    $escalas->valoracion_rc =  $request->descripcion_detalle_caidas;
    $escalas->escala_dolor =  $request->escalas;
    $escalas->created_us = Auth::id();
    $escalas->created_at = date('Y-m-d H:i:s');
    $escalas->save();

        return redirect('/ver_nota_ingreso_enfermeria_hospitalizacion/' . $objNota_enfermeria->id . '/' . $request->id_tipo_atenciones);
    } */

    public function registrar_nota_ingreso_hospitalizacion(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }

        DB::beginTransaction(); // Iniciar una transacción para permitir rollback en caso de error

        try {
            // Crear un nuevo objeto Enfermeria y asignar valores
            // Crear un nuevo objeto Enfermeria y asignar valores
            $rules = [
                'id_prestacion' => 'nullable|integer',
                'id_atencion' => 'required|integer',
                // Otras reglas de validación según sea necesario
            ];
        
            $validator = Validator::make($request->all(), $rules);
        
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Verificar si ya existe una nota de enfermería para este registro
            $id_ext = $request->id_ext_enfermeria;
            $existeNota = Enfermeria::where('id_ext_enfermeria', $id_ext)
                                    ->where('id_tipo_atencion', 3) // 3 es para hospitalización
                                    ->first();

            if ($existeNota != null) {
                return redirect('/hospitalizacion_by_enfermeria/')->withInput()->withErrors(['Ya existe una nota de enfermería para este registro.']);
            }

            // Crear nueva nota de enfermería
            $objNota_enfermeria = new Enfermeria;
            $objNota_enfermeria->id_prestacion = $request->id_prestacion;
            $objNota_enfermeria->id_tipo_atencion = 3; // HOSPITALIZACION
            $objNota_enfermeria->id_estado_reg = 1;
            $objNota_enfermeria->created_us = Auth::id();
            $objNota_enfermeria->id_ext_enfermeria = $id_ext;
            $objNota_enfermeria->img_base = $request->img_puntos_base;
            $objNota_enfermeria->observacion_enfermeria = $request->observacion_detallada;
            $objNota_enfermeria->save();

            // Guardar puntos de enfermería
            $datopunto = json_decode($request->datojhon, true);
            if (isset($datopunto)) {
                foreach ($datopunto as $arreglopuntos) {
                    if ($arreglopuntos && $arreglopuntos['color'] != '*') {
                        $puntos = new Punto;
                        $puntos->id_enfermeria = $objNota_enfermeria->id;
                        $puntos->x = $arreglopuntos['x'];
                        $puntos->y = $arreglopuntos['y'];
                        $puntos->color = $arreglopuntos['color'];
                        $puntos->descripcion_otros = ($arreglopuntos['color'] == 'blue') ? $arreglopuntos['detalleotro'] : '';
                        $puntos->descripcion_proceso = $this->getDescripcionProceso($arreglopuntos['color']);
                        $puntos->id_usuario = Auth::id();
                        $puntos->tipo_registro = 'NOTA_ENFERMERIA';
                        $puntos->id_tipo_atenciones = 3;
                        $puntos->tama = 10;
                        $puntos->save();
                    }
                }
            }

            // Guardar los datos de vitales
            $datonota = $this->getDatosVitales($request);
            foreach ($datonota as $i => $valor) {
                $nota_vitales_enfermeria = new EnfermeriaDetalle;
                $nota_vitales_enfermeria->id_caracter_fisico = $i;
                $nota_vitales_enfermeria->id_enfermeria = $objNota_enfermeria->id;
                $nota_vitales_enfermeria->descripcion = $valor;
                $nota_vitales_enfermeria->created_us = Auth::id();
                $nota_vitales_enfermeria->updated_us = Auth::id();
                $nota_vitales_enfermeria->save();
            }

            $request->merge(['id_tipo_atencion' => 3]);

            // Guardar antecedentes
            $antecedentes = $this->guardarAntecedentes($request, $objNota_enfermeria->id);

            // Guardar valoraciones
            $valoraciones = $this->guardarValoracionesHospi($request, $objNota_enfermeria->id);

            // Guardar piel dispositivos
            $pieldispositivcosenfermeria = $this->guardarPielDispositivos($request, $objNota_enfermeria->id);

            // Guardar escalas
            $escalas = $this->guardarEscalas($request, $objNota_enfermeria->id);

            // Si todo es exitoso, confirmar la transacción
            DB::commit();

            // Redireccionar a la vista de la nota
            return redirect('/ver_nota_ingreso_enfermeria_hospitalizacion/' . $objNota_enfermeria->id . '/' . $request->id_tipo_atenciones);

        } catch (\Exception $e) {
            // En caso de error, revertir la transacción
            DB::rollBack();
            /* dd($e->getMessage(), $e->getFile(), $e->getLine()); */
            return redirect()->back()->withErrors(['error' => 'Hubo un error al registrar la nota de enfermería: ' . $e->getMessage()])->withInput();
        }
    }

    public function registrar_nota_ingreso_emergencia(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }

        DB::beginTransaction(); // Iniciar una transacción para permitir rollback en caso de error

        try {
            /* $rules = [
                'id_prestacion' => 'nullable|integer',
                'id_atencion' => 'required|integer',
            ];
        
            $validator = Validator::make($request->all(), $rules);
        
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } */

            // Verificar si ya existe una nota de enfermería para este registro
            $id_ext = $request->id_atencion;
            $existeNota = Enfermeria::where('id_ext_enfermeria', $id_ext)
                                    ->where('id_tipo_atencion', 2) // 2 es para emergencia
                                    ->first();
            if ($existeNota != null) {
                return redirect('/emergencia_by_enfermeria/')->withInput()->withErrors(['Ya existe una nota de enfermería para este registro.']);
            }

            // Crear nueva nota de enfermería
            $objNota_enfermeria = new Enfermeria;
            $objNota_enfermeria->id_prestacion = $request->id_prestacion ? $request->id_prestacion : null;
            $objNota_enfermeria->id_tipo_atencion = 2; // EMERGENCIA
            $objNota_enfermeria->id_estado_reg = 1;
            $objNota_enfermeria->created_us = Auth::id();
            $objNota_enfermeria->id_ext_enfermeria = $id_ext;
            $objNota_enfermeria->img_base = $request->img_puntos_base;
            $objNota_enfermeria->observacion_enfermeria = $request->observacion_detallada;
            $objNota_enfermeria->save();

            // Guardar puntos de enfermería
            $datopunto = json_decode($request->datojhon, true);
            if (isset($datopunto)) {
                foreach ($datopunto as $arreglopuntos) {
                    if ($arreglopuntos && $arreglopuntos['color'] != '*') {
                        $puntos = new Punto;
                        $puntos->id_enfermeria = $objNota_enfermeria->id;
                        $puntos->x = $arreglopuntos['x'];
                        $puntos->y = $arreglopuntos['y'];
                        $puntos->color = $arreglopuntos['color'];
                        $puntos->descripcion_otros = ($arreglopuntos['color'] == 'blue') ? $arreglopuntos['detalleotro'] : '';
                        $puntos->descripcion_proceso = $this->getDescripcionProceso($arreglopuntos['color']);
                        $puntos->id_usuario = Auth::id();
                        $puntos->tipo_registro = 'NOTA_ENFERMERIA';
                        $puntos->id_tipo_atenciones = 2; // EMERGENCIA
                        $puntos->tama = 10;
                        $puntos->save();
                    }
                }
            }

            // Guardar los datos de vitales
            $datonota = $this->getDatosVitales($request);
            foreach ($datonota as $i => $valor) {
                $nota_vitales_enfermeria = new EnfermeriaDetalle;
                $nota_vitales_enfermeria->id_caracter_fisico = $i;
                $nota_vitales_enfermeria->id_enfermeria = $objNota_enfermeria->id;
                $nota_vitales_enfermeria->descripcion = $valor;
                $nota_vitales_enfermeria->created_us = Auth::id();
                $nota_vitales_enfermeria->updated_us = Auth::id();
                $nota_vitales_enfermeria->save();
            }

            // Guardar antecedentes
            $antecedentes = $this->guardarAntecedentes($request, $objNota_enfermeria->id);

            // Guardar valoraciones
            $valoraciones = $this->guardarValoraciones($request, $objNota_enfermeria->id);

            // Guardar piel dispositivos
            $pieldispositivcosenfermeria = $this->guardarPielDispositivos($request, $objNota_enfermeria->id);

            // Guardar escalas
            $escalas = $this->guardarEscalas($request, $objNota_enfermeria->id);

            // Si todo es exitoso, confirmar la transacción
            DB::commit();

            // Redireccionar a la vista de la nota
            return redirect('/ver_nota_ingreso_enfermeria_emergencia/' . $objNota_enfermeria->id . '/' . $request->id_tipo_atenciones);

        } catch (\Exception $e) {
            // En caso de error, revertir la transacción
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Hubo un error al registrar la nota de enfermería: ' . $e->getMessage()])->withInput();
        }
    }

    private function getDescripcionProceso($color)
    {
        $descripciones = [
            'blue' => 'OTROS',
            'orange' => 'PIE DIABÉTICO',
            'red' => 'LPP',
            'green' => 'DISPOSITIVOS',
            'yellow' => 'DAI'
        ];

        return isset($descripciones[$color]) ? $descripciones[$color] : '';
    }

    private function getDatosVitales($request)
    {
        return [
            1 => $request->presion_arterial_x,
            2 => $request->presion_arterial_y,
            3 => $request->talla,
            4 => $request->frec_cardiaca,
            5 => $request->peso,
            6 => $request->frecuencia_respiratoria,
            7 => $request->oxigenacion1,
            8 => $request->sa02_porcentaje,
            9 => $request->temperatura,
            10 => $request->imc,
            11 => $request->peso_x_altura
        ];
    }

    private function guardarAntecedentes($request, $id_enfermeria)
    {
        $antecedentes = new AntecedentesEnfermeria;
        $antecedentes->id_enfermeria = $id_enfermeria;
        $antecedentes->id_hipertension = $request->hta == 'SI' ? 1 : 0;
        $antecedentes->descripcion_hipertension = $request->tratamiento_hta;
        $antecedentes->id_diabetes_millitus = $request->diabetes_mellitus == 'SI' ? 1 : 0;
        $antecedentes->descripcion_diabetes_millitus = $request->diabetes_mellitus_descripcion;
        $antecedentes->id_alergia = $request->ram == 'SI' ? 1 : 0;
        $antecedentes->descripcion_alergia = $request->detalle_ram_alergia;
        $antecedentes->id_hemodialisis = $request->ram_hemodialisis == 'SI' ? 1 : 0;
        $antecedentes->descripcion_hemodialisis = $request->detalle_ram_hemo;
        $antecedentes->descripcion_observacion = $request->descripcion_antecedente_observacion;
        $antecedentes->descripcion_otro = $request->descripcion_otros_antecedentes;
        $antecedentes->cardio_parametro_1 = isset($request->cardiologico[0]) ? $request->cardiologico[0] : null;
        $antecedentes->cardio_parametro_2 = isset($request->cardiologico[1]) ? $request->cardiologico[1] : null;
        $antecedentes->cardio_parametro_3 = isset($request->cardiologico[2]) ? $request->cardiologico[2] : null;
        $antecedentes->cardio_parametro_4 = isset($request->cardiologico[3]) ? $request->cardiologico[3] : null;
        $antecedentes->created_us = Auth::id();
        $antecedentes->created_at = Carbon::now();
        $antecedentes->save();
        return $antecedentes;
    }

    public function guardarValoraciones(Request $request, $id_enfermeria)
    {
        $valoraciones = new valoracionesEnfermerias;
        $valoraciones->id_enfermeria = $id_enfermeria;
        if($request->id_tipo_atencion == 3) {
            $valoraciones->id_ingresa_por = '14';
        }else {
            $valoraciones->id_ingresa_por = '10';
        }
        $valoraciones->id_funcion_motora = $request->funcion_motora;
        $valoraciones->id_condicion_ingreso = $request->condicion_ingreso;
        $valoraciones->id_respiratorio = $request->respiratorio_duracion;
        $valoraciones->id_estado_conciencia = $request->estado_conciencia;
        $valoraciones->id_secrecionbronquiales = $request->secreaciones_bronquiales_si == 'SI' ? 1 : 0;
        $valoraciones->grado_dependencia = $request->grado_dependencia;
        $valoraciones->id_oxigenoterapia = $request->oxigenoterapia == 'SI' ? 1 : 0;

        // Si hay detalles de oxigenoterapia seleccionados
        if ($request->oxigenoterapia_detalle_seleccion != '*') {
            $valoraciones->oxigenoterapia_parametro = $request->oxigenoterapia_detalle_seleccion;
        }

        $valoraciones->id_miccion_espontanea = $request->miccion_espontanea == 'SI' ? 1 : 0;
        /* $valoraciones->punto_calculado_glasgow = $request->puntaje_calculado_glasgow; */
        $puntaje = isset($request->puntaje_calculado_glasgow) && !empty($request->puntaje_calculado_glasgow) ? $request->puntaje_calculado_glasgow : null;
        if (isset($puntaje) && is_numeric($puntaje)) {
            $valoraciones->punto_calculado_glasgow = (int) $puntaje;
        } else {
            $valoraciones->punto_calculado_glasgow = 0; // o cualquier valor por defecto
        }
        $valoraciones->descripcion_calculado_glasgow = isset($request->descripcion_detalle_Glasgow) && !empty($request->descripcion_detalle_Glasgow) ? $request->descripcion_detalle_Glasgow : null;
        $valoraciones->id_acompanante = $request->acompaniado_por == 'FAMILIAR' ? 0 : 1;
        $valoraciones->descrip_acompanante = $request->descripcion_acompaniado_por;
        $valoraciones->id_hemoptisis = $request->emoptisis == 'SI' ? 1 : 0;
        $valoraciones->id_piel = $request->piel_colores == '*' ? null : $request->piel_colores;
        $valoraciones->id_edema = $request->edema == 'SI' ? 1 : 0;
        $valoraciones->id_integridad = $request->integridad == '*' ? null : $request->integridad;
        $valoraciones->id_abdomen = $request->abdomen == '*' ? null : $request->abdomen;
        $valoraciones->id_mallampaty = $request->evaluacion_mallampaty == 'SI' ? 1 : 0;
        $valoraciones->descripcion_mallampaty = $request->evaluacion_mallampaty_seleccion== '*' ? null : $request->evaluacion_mallampaty_seleccion;
        $valoraciones->numero_acompanante = $request->celular_acompaniado;
        // Evaluación de orientación
        $valoraciones->orientado_detalle_tiempo = $request->vehicle1 == 'on' ? 1 : 0;
        $valoraciones->orientado_detalle_espacio = $request->vehicle2 == 'on' ? 1 : 0;
        $valoraciones->orientado_detalle_persona = $request->vehicle3 == 'on' ? 1 : 0;

        // Datos de auditoría
        $valoraciones->created_us = Auth::id();
        $valoraciones->created_at = Carbon::now(); // Usando Carbon para la fecha actual

        // Guardar los datos
        $valoraciones->save();
    }

    public function guardarValoracionesHospi(Request $request, $id_enfermeria)
    {
        $valoraciones = new valoracionesEnfermerias;
        $valoraciones->id_enfermeria = $id_enfermeria;
        if($request->id_tipo_atencion == 3) {
            $valoraciones->id_ingresa_por = '14';
        }else {
            $valoraciones->id_ingresa_por = '10';
        }
        $valoraciones->id_funcion_motora = $request->funcion_motora;
        $valoraciones->id_condicion_ingreso = $request->condicion_ingreso;
        $valoraciones->id_respiratorio = $request->respiratorio_duracion;
        $valoraciones->id_estado_conciencia = $request->estado_conciencia;
        $valoraciones->id_secrecionbronquiales = $request->secreaciones_bronquiales_si == 'SI' ? 1 : 0;
        $valoraciones->grado_dependencia = $request->grado_dependencia;
        $valoraciones->id_oxigenoterapia = $request->oxigenoterapia == 'SI' ? 1 : 0;
        $valoraciones->id_dolor = $request->reg_dol == 'SI' ? 1 : 0;

        // Si hay detalles de oxigenoterapia seleccionados
        /* if ($request->oxigenoterapia_detalle_seleccion != '*') {
            $valoraciones->oxigenoterapia_parametro = $request->oxigenoterapia_detalle_seleccion;
        } */

        /* if ($request->oxigenoterapia_detalle_seleccion != '*') {
            // Convierte el array en un string separado por comas
            $oxigenoDetalle = implode(',', $request->oxigenoterapia_detalle_seleccion);
            $valoraciones->oxigenoterapia_parametros = $oxigenoDetalle;
        } */

        // Convierte el array en un string separado por comas
            $detalle = is_array($request->oxigenoterapia_detalle_seleccion)
            ? $request->oxigenoterapia_detalle_seleccion
            : [];
            $oxigenoDetalle = implode(',', $detalle);
            $valoraciones->oxigenoterapia_parametros = $oxigenoDetalle;

        $valoraciones->id_miccion_espontanea = $request->miccion_espontanea == 'SI' ? 1 : 0;
        /* $valoraciones->punto_calculado_glasgow = $request->puntaje_calculado_glasgow; */
        $puntaje = $request->puntaje_calculado_glasgow;
        if (is_numeric($puntaje)) {
            $valoraciones->punto_calculado_glasgow = (int) $puntaje;
        } else {
            $valoraciones->punto_calculado_glasgow = 0; // o cualquier valor por defecto
        }
        $valoraciones->descripcion_calculado_glasgow = $request->descripcion_detalle_Glasgow;
        $valoraciones->id_acompanante = intval($request->acompaniado_por);
        $valoraciones->descrip_acompanante = $request->descripcion_acompaniado_por;
        $valoraciones->id_hemoptisis = $request->emoptisis == 'SI' ? 1 : 0;
        $valoraciones->id_piel = $request->piel_colores == '*' ? null : $request->piel_colores;
        $valoraciones->id_edema = $request->edema == 'SI' ? 1 : 0;
        $valoraciones->id_integridad = $request->integridad == '*' ? null : $request->integridad;
        $valoraciones->id_abdomen = $request->abdomen == '*' ? null : $request->abdomen;
        $valoraciones->id_mallampaty = $request->evaluacion_mallampaty == 'SI' ? 1 : 0;
        $valoraciones->descripcion_mallampaty = $request->evaluacion_mallampaty_seleccion== '*' ? null : $request->evaluacion_mallampaty_seleccion;
        $valoraciones->numero_acompanante = $request->celular_acompaniado;
        // Evaluación de orientación
        $valoraciones->orientado_detalle_tiempo = $request->vehicle1 == 'on' ? 1 : 0;
        $valoraciones->orientado_detalle_espacio = $request->vehicle2 == 'on' ? 1 : 0;
        $valoraciones->orientado_detalle_persona = $request->vehicle3 == 'on' ? 1 : 0;

        $valoraciones->punto_calculado_rass = $request->puntaje_calculado_rass != '' ? $request->punto_calculado_rass : 0;
        $valoraciones->descripcion_calculado_rass = $request->desc_rass;

        $valoraciones->oxigenoterapia_fi02 = $request->oxigenoterapia_fi02 != '' ? $request->oxigenoterapia_fi02 : 0;

        // Datos de auditoría
        $valoraciones->created_us = Auth::id();
        $valoraciones->created_at = Carbon::now(); // Usando Carbon para la fecha actual

        // Guardar los datos
        $valoraciones->save();
    }


    public function guardarPielDispositivos(Request $request, $id_enfermeria)
    {
        $pieldispositivcosenfermeria = new pielDispositivosEnfermerias;
        $pieldispositivcosenfermeria->id_enfermeria = $id_enfermeria;

        // Asignar los valores booleanos, 1 si 'SI', 0 en otro caso
        $pieldispositivcosenfermeria->id_dispositivo_invasivos = $request->dispositivos_invasivos == 'SI' ? 1 : 0;
        $pieldispositivcosenfermeria->id_via_periferica = $request->via_periferica == 'SI' ? 1 : 0;
        $pieldispositivcosenfermeria->id_via_central = $request->via_central == 'SI' ? 1 : 0;
        $pieldispositivcosenfermeria->id_caracter_alto_flujo = $request->caracter_alto_flujo == 'SI' ? 1 : 0;
        $pieldispositivcosenfermeria->id_caracter_curacion = $request->caracter_curacion == 'SI' ? 1 : 0;
        $pieldispositivcosenfermeria->id_sonda = $request->sondas == 'SI' ? 1 : 0;
        $pieldispositivcosenfermeria->descripcion_sonda = $request->descripcion_sonda;
        $pieldispositivcosenfermeria->id_linea_arterial = $request->linea_arterial == 'SI' ? 1 : 0;
        $pieldispositivcosenfermeria->id_tiene_drenaje = $request->drenaje == 'SI' ? 1 : 0;
        $pieldispositivcosenfermeria->descripcion_drenaje = $request->descripcion_drenaje;

        // Asignación de datos de auditoría
        $pieldispositivcosenfermeria->created_us = Auth::id();
        $pieldispositivcosenfermeria->created_at = Carbon::now(); // Usando Carbon para la fecha actual

        // Guardar los datos en la base de datos
        $pieldispositivcosenfermeria->save();
    }


    private function guardarEscalas($request, $id_enfermeria)
    {
        
        // Crear un objeto de escalas
        $escalas = new enfermeria_escalas();
        $escalas->id_enfermeria = $id_enfermeria;

        // Asignar puntajes y valoraciones de las escalas
        $escalas->escala_rlp = isset($request->puntaje_calculado_lpp) && !empty($request->puntaje_calculado_lpp) ? $request->puntaje_calculado_lpp : null;
        $escalas->valoracion_rlp = isset($request->descripcion_detalle_riesgolpp) && !empty($request->descripcion_detalle_riesgolpp) ? $request->descripcion_detalle_riesgolpp : null;
        
        $escalas->escala_rc = $request->puntaje_calculado_caidas;
        $escalas->valoracion_rc = $request->descripcion_detalle_caidas;

        // Escala de dolor (si está presente)
        $escalas->escala_dolor = $request->escalas;
        
        // Asignar el color del brazalete y otros detalles relacionados
        $escalas->id_color_brazalete = $request->brazalete_seleccion;
        if ($request->brazalete == 'SI') {
            $escalas->id_brazalete = 1;
        }

        // Asignar usuario y fecha de creación
        $escalas->created_us = Auth::id();
        $escalas->created_at = Carbon::now(); // Usando la función now() de Laravel

        // Guardar la escala
        $escalas->save();

        return $escalas; // Retornar el objeto creado si se necesita para algo más
    }


    protected function crearAntecedenteEnfermeria($id_enfermeria, $hipertension, $tratamiento,$diabetes_mellitus,$tratamiento_diabetes,$ram,$detalle_ram_alergia,$ram_hemodialisis,$detalle_ram_hemo,$descripcion_antecedente_observacion
    ,$descripcion_otros_antecedentes
    ) {
        $antecedentes = new AntecedentesEnfermeria;
        $antecedentes->id_enfermeria = $id_enfermeria;
        $antecedentes->id_hipertension = $hipertension;
        $antecedentes->descripcion_hipertension = $tratamiento;
        $antecedentes->id_diabetes_millitus = $diabetes_mellitus;
        $antecedentes->descripcion_diabetes_millitus = $tratamiento_diabetes;
        $antecedentes->id_alergia=$ram;
        $antecedentes->descripcion_alergia = $detalle_ram_alergia;
        $antecedentes->id_hemodialisis=$ram_hemodialisis;
        $antecedentes->descripcion_hemodialisis = $detalle_ram_hemo;
        // $antecedentes->cardiologico=$cardiologico;
        $antecedentes->descripcion_observacion=$descripcion_antecedente_observacion;
        $antecedentes->descripcion_otro=$descripcion_otros_antecedentes;

        $antecedentes->created_us = Auth::id();
        $antecedentes->created_at = date('Y-m-d H:i:s');
        $antecedentes->save();
    }
    protected function crearValoraciones_Enfermeria($id_enfermeria,$ingresa_por) {
        $valoraciones = new valoracionesEnfermerias;
        $valoraciones->id_enfermeria = $id_enfermeria;

        $valoraciones->id_ingresa_por = $ingresa_por;

        $valoraciones->created_us = Auth::id();
        $valoraciones->created_at = date('Y-m-d H:i:s');
        $valoraciones->save();
    }
    protected function crearPielDispositivosEnfermerias($id_enfermeria,$dispositivos_invasivos,$via_periferica) {
        $valoraciones = new pielDispositivosEnfermerias;
        $valoraciones->id_enfermeria = $id_enfermeria;
        $valoraciones->id_dispositivo_invasivos = $dispositivos_invasivos;
        $valoraciones->id_via_periferica = $via_periferica;
        $valoraciones->id_via_periferica = $via_periferica;

        $valoraciones->created_us = Auth::id();
        $valoraciones->created_at = date('Y-m-d H:i:s');
        $valoraciones->save();
    }

    public function ver_nota_ingreso_enfermeria($id_nota_ingreso, $id_tipo_atenciones){
        if (!Auth::check()) :
			return redirect('/auth/login');
		endif;
  
		$modelfarma = new \App\Models\Farmacia;
		$model_receta = new \App\Models\Receta_vale;
		$model_receta_diagnostico = new \App\Models\Receta_diagnostico;   
		$model_establecimiento = new \App\Models\Establecimiento; 
		$nota_ingreso = new Nota_enfermeria; 
		$datos_nota_ingreso = $nota_ingreso->getObtener_nota_ingreso_by_id($id_nota_ingreso, $id_tipo_atenciones);  
        $valores_cardio='';
		if (is_object($datos_nota_ingreso[0])){  
			$farmacia = $modelfarma->GetFarmaByUser(Auth::user()->id); 
			$id_ipress = $farmacia->id_establecimiento;
			$establecimiento = $model_establecimiento->GetById($id_ipress); 
///========================================================================================================================///
            if($id_tipo_atenciones == 3){ ///HOSPITALIZACION
                $id_hospitalizacion = $datos_nota_ingreso[0]->id_atencion;
                $codigo_ubig_responsable=$model_receta->getObtener_codigo_ubig_responsable($id_hospitalizacion);
                $codigo_ubig_apoderado=$model_receta->getObtener_codigo_ubig_apoderado($id_hospitalizacion);
                $datos_atencion = $model_receta->getHospitalizacionById_farmacia_new_hospitalizacion($id_hospitalizacion, $codigo_ubig_responsable, $codigo_ubig_apoderado);
                $diagnosticos = $model_receta_diagnostico->DiagnosticosByIdHospitalizacion($id_hospitalizacion);  
                $valores_cardio=$nota_ingreso->obtener_descripcion_cardiologico($datos_nota_ingreso[0]->cardiologico); 
                
                if($valores_cardio){ 
                    $valores_cardio=$valores_cardio->descripcion_cardiologico;
                }
                  
                return  view('frontend.hospitalizacion.ver_nota_ingreso_enfermeria', 
                        compact('valores_cardio','datos_nota_ingreso','datos_atencion', 'id_atencion', 'id_tipo_atenciones', 'diagnosticos', 'establecimiento'));

            }else if($id_tipo_atenciones == 2){ ///EMERGENCIA

            }
        }else{
            return redirect('/hospitalizado_by_enfermeria/')->withInput()->withErrors(array('Elemento no encontrado ..!')); 
        }
	}

	public function ver_nota_ingreso_enfermeria_emergencia($id_nota_ingreso, $id_tipo_atenciones){

        if (!Auth::check()) :
			return redirect('/auth/login');
		endif;
  
		$modelfarma = new \App\Models\Farmacia;
		$model_receta = new \App\Models\Receta_vale;  
		$model_receta_diagnostico = new \App\Models\Receta_diagnostico;   
		$model_establecimiento = new \App\Models\Establecimiento; 
		$nota_ingreso = new Nota_enfermeria; 
        $model_user = new User();
		$datos_nota_ingreso = $nota_ingreso->getObtener_nota_ingreso_by_id($id_nota_ingreso, $id_tipo_atenciones);
        $status_token = $model_user->TieneTokenActivo(Auth::id());
        $usuarioRegistradorNota = null;
		if (is_object($datos_nota_ingreso[0])){  
            $usuarioRegistradorNota = User::where('users.id', $datos_nota_ingreso[0]->created_us)
            ->leftJoin('medicos', 'users.dni', '=', 'medicos.dni')
            ->leftJoin('profesiones', 'profesiones.id', '=', 'medicos.id_profesion')
            ->leftJoin('especializaciones', 'especializaciones.id', '=', 'medicos.id_especializacion')
            ->select('users.dni as profesional_dni', 
                    'users.paterno as profesional_paterno', 
                    'users.materno as profesional_materno', 
                    'users.name as profesional_nombre',
                    'medicos.especialidad as especialidad_medico',
                    'profesiones.nombre as profesion_medico',
                    'medicos.colegiatura as colegiatura')
            ->first();
			$farmacia = $modelfarma->GetFarmaByUser(Auth::user()->id); 
			$id_ipress = $farmacia->id_establecimiento;
			$establecimiento = $model_establecimiento->GetById($id_ipress); 
///========================================================================================================================///
               $id_emergencia = $datos_nota_ingreso[0]->id_ext_enfermeria;
                      
                $datos_atencion = $model_receta->getEmergenciaById_farmacia_new($id_emergencia);
                $diagnosticos = $model_receta_diagnostico->DiagnosticosByEmergencia_Enfermeria_formulario_prueba($id_emergencia); 
                $diagnosticos_evaluacion = $model_receta_diagnostico->DiagnosticosByEmergencia_Enfermeria_evaluacion($id_emergencia); 

                $antecedentesenfermeria = DB::table('antecedentes_enfermerias')
                                            ->select('antecedentes_enfermerias.*')
                                            ->where('id_enfermeria',$id_nota_ingreso)
                                            ->first();
                                  
                $valoracionesEnfermeria = DB::table('valoraciones_enfermerias')
                                            ->select('valoraciones_enfermerias.*')
                                            ->where('id_enfermeria',$id_nota_ingreso)
                                            ->first();

                $signos_vitales = "SELECT * FROM notas_vitales_enfermerias where id_enfermeria = '$id_nota_ingreso' order by id_caracter_fisico asc";
                $signos_vitales = DB::select($signos_vitales);

                $valoraciones_enfermerias =  DB::table('valoraciones_enfermerias')
                                                ->select('valoraciones_enfermerias.*')
                                                ->where('id_enfermeria',$id_nota_ingreso)
                                                ->first();

                $pieldispositivo_enfermerias =  DB::table('pieldispositivo_enfermerias')
                                                ->select('pieldispositivo_enfermerias.*')
                                                ->where('id_enfermeria',$id_nota_ingreso)
                                                ->first();
                
                $enfermeria_escalas =  DB::table('enfermeria_escalas')
                                            ->select('enfermeria_escalas.*')
                                            ->where('id_enfermeria',$id_nota_ingreso)
                                            ->first();
                                          
                $nombre = '';
                            if(isset($antecedentesenfermeria->cardio_parametro_1)){
                                $dato1 = DB::table('parametros')
                                                ->select('parametros.*')
                                                ->where('id',$antecedentesenfermeria->cardio_parametro_1)
                                                ->first();
                            $nombre = $nombre .' '. $dato1->descrip_corta;
                            }
                            if(isset($antecedentesenfermeria->cardio_parametro_2)){
                                $dato2 = DB::table('parametros')
                                                ->select('parametros.*')
                                                ->where('id',$antecedentesenfermeria->cardio_parametro_2)
                                                ->first();
                            $nombre = $nombre .' - '. $dato2->descrip_corta;
                            }
                            if(isset($antecedentesenfermeria->cardio_parametro_3)){
                                $dato3 = DB::table('parametros')
                                                ->select('parametros.*')
                                                ->where('id',$antecedentesenfermeria->cardio_parametro_3)
                                                ->first();
                            $nombre = $nombre .' - '. $dato3->descrip_corta;
                            }
                            if(isset($antecedentesenfermeria->cardio_parametro_4)){
                                $dato4 = DB::table('parametros')
                                                ->select('parametros.*')
                                                ->where('id',$antecedentesenfermeria->cardio_parametro_4)
                                                ->first();
                            $nombre = $nombre .' - '. $dato4->descrip_corta ;
                            }
 
                $ingresa_por = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$valoraciones_enfermerias->id_ingresa_por)
                ->first();

                $condicion_ingreso = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$valoraciones_enfermerias->id_condicion_ingreso)
                ->first();

                $estado_conciencia = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$valoraciones_enfermerias->id_estado_conciencia)
                ->first();

                $funcion_motora = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$valoraciones_enfermerias->id_funcion_motora)
                ->first();

                $respiratorio = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$valoraciones_enfermerias->id_respiratorio)
                ->first();
                
                $detalle_oxigeno  = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$valoraciones_enfermerias->oxigenoterapia_parametro)
                ->first();
                
                $piel = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$valoraciones_enfermerias->id_piel)
                ->first();
                
                $integridad = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$valoraciones_enfermerias->id_integridad)
                ->first();
                
                $abdomen = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$valoraciones_enfermerias->id_abdomen)
                ->first();

                   $puntos = "SELECT * FROM puntos where id_enfermeria = '$id_nota_ingreso'";
                   $puntosenviados = DB::select($puntos);      
                 
                   $emergencia_data_jhon = DB::table('emergencias')
                   ->select('emergencias.*')
                   ->where('id',$id_emergencia)
                   ->first();
         
                   if($emergencia_data_jhon->dni_beneficiario != $emergencia_data_jhon->dni_titular){
                       $asegurado_titular = DB::table('asegurados')
                       ->select('asegurados.*')
                       ->where('nro_doc_ident',$emergencia_data_jhon->dni_titular)
                       ->where('id_estado_reg','1')
                       ->first();
                   }else{
                       $asegurado_titular = DB::table('asegurados')
                       ->select('asegurados.*')
                       ->where('nro_doc_ident',$emergencia_data_jhon->dni_beneficiario)
                       ->where('id_estado_reg','1')
                       ->first();
                   }
                         
                return  view('frontend.emergencia_enfermeria.ver_nota_ingreso_enfermeria_emergencia',
                        compact('datos_nota_ingreso','datos_atencion', 'status_token', 'id_tipo_atenciones','antecedentesenfermeria','valoracionesEnfermeria','nombre',
                        'signos_vitales','valoraciones_enfermerias','pieldispositivo_enfermerias','enfermeria_escalas','ingresa_por','condicion_ingreso',
                        'estado_conciencia','funcion_motora','respiratorio','detalle_oxigeno','piel','integridad','abdomen','puntosenviados','asegurado_titular',
                        'establecimiento','diagnosticos','diagnosticos_evaluacion', 'usuarioRegistradorNota')
                        );

        }else{
            return redirect('/emergencia_by_enfermeria/')->withInput()->withErrors(array('Elemento no encontrado ..!')); 
        }
	}
	public function ver_nota_ingreso_enfermeria_hospitalizacion($id_nota_ingreso, $id_tipo_atenciones){

        if (!Auth::check()) :
			return redirect('/auth/login');
		endif;
  
		$modelfarma = new \App\Models\Farmacia;
		$model_receta = new \App\Models\Receta_vale;  
        $model_nota_enfermeria = new Nota_enfermeria();
		$model_receta_diagnostico = new \App\Models\Receta_diagnostico;   
		$model_establecimiento = new \App\Models\Establecimiento; 
		$nota_ingreso = new Nota_enfermeria; 
        $model_user = new User();
		$datos_nota_ingreso = $nota_ingreso->getObtener_nota_ingreso_by_id_hospi($id_nota_ingreso, $id_tipo_atenciones);
        $status_token = $model_user->TieneTokenActivo(Auth::id());
		if (is_object($datos_nota_ingreso[0])){  
			$farmacia = $modelfarma->GetFarmaByUser(Auth::user()->id); 
			$id_ipress = $farmacia->id_establecimiento;
			$establecimiento = $model_establecimiento->GetById($id_ipress); 
///========================================================================================================================///
               $id_hospi = $datos_nota_ingreso[0]->id_ext_enfermeria;
                      
               $hospitalizacion = DB::table('hospitalizaciones')
                ->select('hospitalizaciones.*')
                ->where('id',$id_hospi)
                ->first();
                $datos_atencion = $model_nota_enfermeria->getHospitalizacionById_farmacia_new2($id_hospi);
                $diagnosticos = $model_receta_diagnostico->DiagnosticosByHospitalizacion_Enfermeria($id_hospi); 
                $diagnosticosEvaluacion = $model_receta_diagnostico->DiagnosticosByHospitalizacionEvaluacion_Enfermeria($id_hospi); 
                /* $diagnosticos_evaluacion = $model_receta_diagnostico->DiagnosticosByHospitalizacion_Enfermeria_evaluacion($id_hospi); */
                $existenDiagnosticosDeAtencion = true;
                if (empty($diagnosticos)) {
                    $diagnostico1 = $model_nota_enfermeria->DiagnosticosById($hospitalizacion->id_diagnostico_inicial);
                    $diagnostico2 = $model_nota_enfermeria->DiagnosticosById($hospitalizacion->id_dx_2);
                    $diagnostico3 = $model_nota_enfermeria->DiagnosticosById($hospitalizacion->id_dx_3);
                    $diagnosticos = [$diagnostico1, $diagnostico2, $diagnostico3];
                    $existenDiagnosticosDeAtencion = false;
                }


                $antecedentesenfermeria = DB::table('antecedentes_enfermerias')
                                            ->select('antecedentes_enfermerias.*')
                                            ->where('id_enfermeria',$id_nota_ingreso)
                                            ->first();
                                  
                $valoracionesEnfermeria = DB::table('valoraciones_enfermerias')
                                            ->select('valoraciones_enfermerias.*')
                                            ->where('id_enfermeria',$id_nota_ingreso)
                                            ->first();
                $signos_vitales = "SELECT * FROM notas_vitales_enfermerias where id_enfermeria = '$id_nota_ingreso' order by id_caracter_fisico asc";
                $signos_vitales = DB::select($signos_vitales);

                $valoraciones_enfermerias =  DB::table('valoraciones_enfermerias')
                                                ->select('valoraciones_enfermerias.*')
                                                ->where('id_enfermeria',$id_nota_ingreso)
                                                ->first();


                $pieldispositivo_enfermerias =  DB::table('pieldispositivo_enfermerias')
                                                ->select('pieldispositivo_enfermerias.*')
                                                ->where('id_enfermeria',$id_nota_ingreso)
                                                ->first();
                
                $enfermeria_escalas =  DB::table('enfermeria_escalas')
                                            ->select('enfermeria_escalas.*')
                                            ->where('id_enfermeria',$id_nota_ingreso)
                                            ->first();
                                          
                $nombre = '';
                            if(isset($antecedentesenfermeria->cardio_parametro_1)){
                                $dato1 = DB::table('parametros')
                                                ->select('parametros.*')
                                                ->where('id',$antecedentesenfermeria->cardio_parametro_1)
                                                ->first();
                            $nombre = $nombre .' '. $dato1->descrip_corta;
                            }
                            if(isset($antecedentesenfermeria->cardio_parametro_2)){
                                $dato2 = DB::table('parametros')
                                                ->select('parametros.*')
                                                ->where('id',$antecedentesenfermeria->cardio_parametro_2)
                                                ->first();
                            $nombre = $nombre .' - '. $dato2->descrip_corta;
                            }
                            if(isset($antecedentesenfermeria->cardio_parametro_3)){
                                $dato3 = DB::table('parametros')
                                                ->select('parametros.*')
                                                ->where('id',$antecedentesenfermeria->cardio_parametro_3)
                                                ->first();
                            $nombre = $nombre .' - '. $dato3->descrip_corta;
                            }
                            if(isset($antecedentesenfermeria->cardio_parametro_4)){
                                $dato4 = DB::table('parametros')
                                                ->select('parametros.*')
                                                ->where('id',$antecedentesenfermeria->cardio_parametro_4)
                                                ->first();
                            $nombre = $nombre .' - '. $dato4->descrip_corta ;
                            }
 
                $ingresa_por = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$valoraciones_enfermerias->id_ingresa_por)
                ->first();

                $condicion_ingreso = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$valoraciones_enfermerias->id_condicion_ingreso)
                ->first();

                $estado_conciencia = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$valoraciones_enfermerias->id_estado_conciencia)
                ->first();

                $funcion_motora = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$valoraciones_enfermerias->id_funcion_motora)
                ->first();

                $respiratorio = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$valoraciones_enfermerias->id_respiratorio)
                ->first();
                
                /* $detalle_oxigeno  = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$valoraciones_enfermerias->oxigenoterapia_parametro)
                ->first(); */
                $oxigeno_ids = [];
                $nombres_oxigeno = "";
                if (!empty($valoraciones_enfermerias->oxigenoterapia_parametros)) {
                    $oxigeno_ids = array_map('intval', explode(',', $valoraciones_enfermerias->oxigenoterapia_parametros));
                }
                if (!empty($oxigeno_ids)) {
                    $detalles_oxigeno = DB::table('parametros')
                        ->whereIn('id', $oxigeno_ids)
                        ->get();
                    /* $nombres_oxigeno = implode('<br>', array_map(function ($item) {
                        return $item->descrip_larga;
                    }, $detalles_oxigeno)); */
                    $nombres_oxigeno = array_map(function ($item) {
                        return $item->descrip_larga;
                    }, $detalles_oxigeno);
                }
                
                $piel = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$valoraciones_enfermerias->id_piel)
                ->first();
                
                $integridad = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$valoraciones_enfermerias->id_integridad)
                ->first();
                
                $abdomen = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$valoraciones_enfermerias->id_abdomen)
                ->first();

                   $puntos = "SELECT * FROM puntos where id_enfermeria = '$id_nota_ingreso'";
                   $puntosenviados = DB::select($puntos);      
                 
                   $emergencia_data_jhon = DB::table('hospitalizaciones')
                   ->select('hospitalizaciones.*')
                   ->where('id',$id_hospi)
                   ->first();
         
                   if($emergencia_data_jhon->dni_beneficiario != $emergencia_data_jhon->dni_titular){
                       $asegurado_titular = DB::table('asegurados')
                       ->select('asegurados.*')
                       ->where('nro_doc_ident',$emergencia_data_jhon->dni_titular)
                       ->where('id_estado_reg','1')
                       ->first();
                   }else{
                       $asegurado_titular = DB::table('asegurados')
                       ->select('asegurados.*')
                       ->where('nro_doc_ident',$emergencia_data_jhon->dni_beneficiario)
                       ->where('id_estado_reg','1')
                       ->first();
                   }
                         
                return  view('frontend.hospitalizacion_enfermeria.ver_nota_ingreso_enfermeria_hospitalizacion',
                        compact('status_token', 'datos_nota_ingreso','datos_atencion','id_tipo_atenciones','antecedentesenfermeria','valoracionesEnfermeria','nombre',
                        'signos_vitales','valoraciones_enfermerias','pieldispositivo_enfermerias','enfermeria_escalas','ingresa_por','condicion_ingreso',
                        'estado_conciencia','funcion_motora','respiratorio'/* ,'detalle_oxigeno' */,'piel','integridad','abdomen','puntosenviados','asegurado_titular',
                        'establecimiento','diagnosticos','diagnosticosEvaluacion', 'existenDiagnosticosDeAtencion', 'detalles_oxigeno', 'nombres_oxigeno')
                        );

        }else{
            return redirect('/emergencia_by_enfermeria/')->withInput()->withErrors(array('Elemento no encontrado ..!')); 
        }
	}

    public function pdf_nota_ingreso_enfermeria($id_nota_ingreso, $id_tipo_atenciones){
        if (!Auth::check()) :
			return redirect('/auth/login');
		endif;
   
        $date = Carbon::now();
        $fechaActual = $date->format('d/m/Y');
        $horaActual = $date->format('H:i:s');

        //--- ESTABLECIMIENTO
        $id_establecimiento=Auth::user()->id_establecimiento;// id_establecimiento = 76 - 1 mdico  
        $model_establecimiento = new \App\Models\Establecimiento();
        $establecimiento=  $model_establecimiento->GetById($id_establecimiento);
        $nombre_establecimiento = $establecimiento[0]->nombre;
        //--- -----------

		$model_receta = new \App\Models\Receta_vale;
		$model_receta_diagnostico = new \App\Models\Receta_diagnostico;  
		$nota_ingreso = new Nota_enfermeria; 
		$datos_nota_ingreso = $nota_ingreso->getObtener_nota_ingreso_by_id($id_nota_ingreso, $id_tipo_atenciones);  

		if (is_object($datos_nota_ingreso[0])){   
         
            $id_hospitalizacion='';
            $codigo_ubig_responsable='';
            $codigo_ubig_apoderado='';
            $datos_atencion='';
            $diagnosticos='';
///========================================================================================================================///
            if($id_tipo_atenciones == 3){ ///HOSPITALIZACION
                $id_hospitalizacion = $datos_nota_ingreso[0]->id_atencion;
                $codigo_ubig_responsable = $model_receta->getObtener_codigo_ubig_responsable($id_hospitalizacion);
                $codigo_ubig_apoderado = $model_receta->getObtener_codigo_ubig_apoderado($id_hospitalizacion);
                $datos_atencion = $model_receta->getHospitalizacionById_farmacia_new_hospitalizacion($id_hospitalizacion, $codigo_ubig_responsable, $codigo_ubig_apoderado); 
    
                $diagnosticos = $model_receta_diagnostico->DiagnosticosByIdHospitalizacion($id_hospitalizacion); 

            }else if($id_tipo_atenciones == 2){ ///EMERGENCIA
            
            }
///========================================================================================================================///
            //dd($diagnosticos);
            $valores_Cardio=$nota_ingreso->obtener_descripcion_cardiologico($datos_nota_ingreso[0]->cardiologico);  
            if($valores_Cardio){ 
                $valores_Cardio=$valores_Cardio->descripcion_cardiologico;
            }
            $nota_detalle='';

            $pdf = \PDF::loadView('frontend.hospitalizacion.pdf_nota_ingreso_enfermeria', 
            compact(
                'valores_Cardio',
                'datos_nota_ingreso',  
                'datos_atencion',
                'diagnosticos' ,
                'nota_detalle' ,
                'nombre_establecimiento',
                'fechaActual',
                'horaActual'
            ));
            $pdf->getDomPDF()->set_option("enable_php", true);
            //$pdf->setPaper('A5', 'portrait');
            $pdf->setPaper('A4', 'portrait'); //Horizontal
            return $pdf->stream('nota_ingreso_hospitalizado.pdf'); 

        }else{
            return redirect('/hospitalizado_by_enfermeria/')->withInput()->withErrors(array('Elemento no encontrado ..!')); 
        }
	}


	public function pdf_nota_ingreso_enfermeria_emergencia($id_nota_ingreso, $id_tipo_atenciones){
        //dd($id_nota_ingreso);
        if (!Auth::check()) :
			return redirect('/auth/login');
		endif;
   
        $date = Carbon::now();
        $fechaActual = $date->format('d/m/Y');
        $horaActual = $date->format('H:i:s');

        //--- ESTABLECIMIENTO
        $id_establecimiento=Auth::user()->id_establecimiento;// id_establecimiento = 76 - 1 mdico  
        $model_establecimiento = new \App\Models\Establecimiento();
        $establecimiento=  $model_establecimiento->GetById($id_establecimiento);
        $nombre_establecimiento = $establecimiento[0]->nombre;
        //dd($nombre_establecimiento);
        //--- -----------

		$model_receta = new \App\Models\Receta_vale;
		$model_receta_diagnostico = new \App\Models\Receta_diagnostico;  
		$nota_ingreso = new Nota_enfermeria; 
		$datos_nota_ingreso = $nota_ingreso->getObtener_nota_ingreso_by_id($id_nota_ingreso, $id_tipo_atenciones); 
        $usuario_enfermeria = DB::table('users')
        ->select('users.*')
        ->where('id',$datos_nota_ingreso[0]->created_us)
        ->first();

        $existeAtencionMedica = Prestaciones::where('id_ext', '=', $datos_nota_ingreso[0]->id_ext_enfermeria)
                                ->where('id_tipo_atencion', $id_tipo_atenciones)
                                ->exists();
    
        if ($existeAtencionMedica) {
            $dato_cabecera_jhon = $nota_ingreso->getCabecera_id_withSPforEmergencia($id_nota_ingreso);
        } else {
            $dato_cabecera_jhon = $nota_ingreso->getCabecera_id_withSPforEmergencia_sa($id_nota_ingreso);
        }

        if (is_array($dato_cabecera_jhon) && !empty($dato_cabecera_jhon)) {
            $servicio = $dato_cabecera_jhon[0]->servicio;
            $pabellon = $dato_cabecera_jhon[0]->pabellon;
            $piso = $dato_cabecera_jhon[0]->piso;
            $cuarto = $dato_cabecera_jhon[0]->cuarto;
            $cama = $dato_cabecera_jhon[0]->cama;
        } else {
            $servicio = '';
            $pabellon = '';
            $piso = '';
            $cuarto = '';
            $cama = '';
        }

		if (is_object($datos_nota_ingreso[0])){   
         
            $id_emergencia='';
            $codigo_ubig_responsable='';
            $codigo_ubig_apoderado='';
            $datos_atencion='';
            $diagnosticos='';
            $id_emergencia = $datos_nota_ingreso[0]->id_ext_enfermeria;
            $codigo_ubig_responsable = $model_receta->getObtener_codigo_ubig_responsable_emergencia($id_emergencia);
            $codigo_ubig_apoderado = $model_receta->getObtener_codigo_ubig_apoderado_emergencia($id_emergencia);
            $datos_atencion = $model_receta->getEmergenciaById_farmacia_new($id_emergencia, $codigo_ubig_responsable, $codigo_ubig_apoderado); 
            $diagnosticos = $model_receta_diagnostico->DiagnosticosByEmergencia_Enfermeria($id_emergencia); 

            $nota_detalle='';

            $antecedentesenfermeria = DB::table('antecedentes_enfermerias')
            ->select('antecedentes_enfermerias.*')
            ->where('id_enfermeria',$id_nota_ingreso)
            ->first();
  
            $valoracionesEnfermeria = DB::table('valoraciones_enfermerias')
                        ->select('valoraciones_enfermerias.*')
                        ->where('id_enfermeria',$id_nota_ingreso)
                        ->first();

$signos_vitales = "SELECT * FROM notas_vitales_enfermerias where id_enfermeria = '$id_nota_ingreso' order by id_caracter_fisico asc";
$signos_vitales = DB::select($signos_vitales);

$valoraciones_enfermerias =  DB::table('valoraciones_enfermerias')
                ->select('valoraciones_enfermerias.*')
                ->where('id_enfermeria',$id_nota_ingreso)
                ->first();

$pieldispositivo_enfermerias =  DB::table('pieldispositivo_enfermerias')
                ->select('pieldispositivo_enfermerias.*')
                ->where('id_enfermeria',$id_nota_ingreso)
                ->first();

$enfermeria_escalas =  DB::table('enfermeria_escalas')
            ->select('enfermeria_escalas.*')
            ->where('id_enfermeria',$id_nota_ingreso)
            ->first();
          
$nombre = '';
if(isset($antecedentesenfermeria->cardio_parametro_1)){
$dato1 = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$antecedentesenfermeria->cardio_parametro_1)
                ->first();
$nombre = $nombre .' '. $dato1->descrip_corta;
}
if(isset($antecedentesenfermeria->cardio_parametro_2)){
$dato2 = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$antecedentesenfermeria->cardio_parametro_2)
                ->first();
$nombre = $nombre .' - '. $dato2->descrip_corta;
}
if(isset($antecedentesenfermeria->cardio_parametro_3)){
$dato3 = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$antecedentesenfermeria->cardio_parametro_3)
                ->first();
$nombre = $nombre .' - '. $dato3->descrip_corta;
}
if(isset($antecedentesenfermeria->cardio_parametro_4)){
$dato4 = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$antecedentesenfermeria->cardio_parametro_4)
                ->first();
$nombre = $nombre .' - '. $dato4->descrip_corta ;
}

$ingresa_por = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_ingresa_por)
->first();

$condicion_ingreso = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_condicion_ingreso)
->first();

$estado_conciencia = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_estado_conciencia)
->first();

$funcion_motora = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_funcion_motora)
->first();

$respiratorio = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_respiratorio)
->first();

$detalle_oxigeno  = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->oxigenoterapia_parametro)
->first();

$piel = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_piel)
->first();

$integridad = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_integridad)
->first();

$abdomen = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_abdomen)
->first();

$puntos = "SELECT * FROM puntos where id_enfermeria = '$id_nota_ingreso'";
$puntosenviados = DB::select($puntos);      

$emergencia_data_jhon = DB::table('emergencias')
->select('emergencias.*')
->where('id',$id_emergencia)
->first();

if($emergencia_data_jhon->dni_beneficiario != $emergencia_data_jhon->dni_titular){
$asegurado_titular = DB::table('asegurados')
->select('asegurados.*')
->where('nro_doc_ident',$emergencia_data_jhon->dni_titular)
->where('id_estado_reg','1')
->first();
}else{
$asegurado_titular = DB::table('asegurados')
->select('asegurados.*')
->where('nro_doc_ident',$emergencia_data_jhon->dni_beneficiario)
->where('id_estado_reg','1')
->first();
}

            

            $pdf = \PDF::loadView('frontend.emergencia_enfermeria.pdf_nota_ingreso_enfermeria_emergencia',
            compact(
                'valores_Cardio',
                'datos_nota_ingreso',  
                'datos_atencion',
                'diagnosticos' ,
                'nota_detalle' ,
                'nombre_establecimiento',
                'fechaActual',
                'horaActual','usuario_enfermeria',
                'servicio','pabellon','piso','cuarto','cama','dato_cabecera_jhon',
                'datos_nota_ingreso','datos_atencion','id_tipo_atenciones','antecedentesenfermeria','valoracionesEnfermeria','nombre',
                'signos_vitales','valoraciones_enfermerias','pieldispositivo_enfermerias','enfermeria_escalas','ingresa_por','condicion_ingreso',
                'estado_conciencia','funcion_motora','respiratorio','detalle_oxigeno','piel','integridad','abdomen','puntosenviados','asegurado_titular',
                'establecimiento','diagnosticos','diagnosticos_evaluacion'
            

            ));
            $pdf->getDomPDF()->set_option("enable_php", true);
            //$pdf->setPaper('A5', 'portrait');
            $pdf->setPaper('A4', 'portrait'); //Horizontal
            return $pdf->stream('nota_ingreso_emergencia.pdf'); 

        }else{
            return redirect('/emergencia_by_enfermeria/')->withInput()->withErrors(array('Elemento no encontrado ..!')); 
        }
	}
	public function pdf_nota_ingreso_enfermeria_hospitalizacion($id_nota_ingreso, $id_tipo_atenciones){
        //dd($id_nota_ingreso);
        if (!Auth::check()) :
			return redirect('/auth/login');
		endif;
   
        $date = Carbon::now();
        $fechaActual = $date->format('d/m/Y');
        $horaActual = $date->format('H:i:s');

        //--- ESTABLECIMIENTO
        $id_establecimiento=Auth::user()->id_establecimiento;// id_establecimiento = 76 - 1 mdico  
        $model_establecimiento = new \App\Models\Establecimiento();
        $establecimiento=  $model_establecimiento->GetById($id_establecimiento);
        $nombre_establecimiento = $establecimiento[0]->nombre;
        //dd($nombre_establecimiento);
        //--- -----------

		$model_receta = new \App\Models\Receta_vale;
		$model_receta_diagnostico = new \App\Models\Receta_diagnostico;  
		$nota_ingreso = new Nota_enfermeria; 
        $enfermeriaModel = new Enfermeria();
		$datos_nota_ingreso = $nota_ingreso->getObtener_nota_ingreso_by_id_hospi($id_nota_ingreso, $id_tipo_atenciones);
        
        $usuario_enfermeria = DB::table('users')
        ->select('users.*')
        ->where('id',$datos_nota_ingreso[0]->created_us)
        ->first();


        $model_enfermeria = new Nota_enfermeria();
        $dato_cabecera_jhon = $model_enfermeria->getCabecera_id_withSPforHospi($id_nota_ingreso);
        /* dd($dato_cabecera_jhon); */
        if (is_array($dato_cabecera_jhon) && !empty($dato_cabecera_jhon)) {
            $servicio = $dato_cabecera_jhon[0]->servicio;
            $pabellon = $dato_cabecera_jhon[0]->pabellon;
            $piso = $dato_cabecera_jhon[0]->piso;
            $cuarto = $dato_cabecera_jhon[0]->cuarto;
            $cama = $dato_cabecera_jhon[0]->cama;
        } else {
            $servicio = '';
            $pabellon = '';
            $piso = '';
            $cuarto = '';
            $cama = '';
        }

		if (is_object($datos_nota_ingreso[0])){   
         
            $id_emergencia='';
            $codigo_ubig_responsable='';
            $codigo_ubig_apoderado='';
            $datos_atencion='';
            $diagnosticos='';

                $id_emergencia = $datos_nota_ingreso[0]->id_ext_enfermeria;
                $codigo_ubig_responsable = $model_receta->getObtener_codigo_ubig_responsable_emergencia($id_emergencia);
                $codigo_ubig_apoderado = $model_receta->getObtener_codigo_ubig_apoderado_emergencia($id_emergencia);
                $datos_atencion = $model_receta->getHospitalizacionById_farmacia_new($id_emergencia); 
                $diagnosticos = $model_receta_diagnostico->DiagnosticosByHospitalizacion_Enfermeria($id_emergencia); 
                $diagnosticosEvaluacion = $model_receta_diagnostico->DiagnosticosByHospitalizacionEvaluacion_Enfermeria($id_emergencia); 

            $nota_detalle='';

            $antecedentesenfermeria = DB::table('antecedentes_enfermerias')
            ->select('antecedentes_enfermerias.*')
            ->where('id_enfermeria',$id_nota_ingreso)
            ->first();
  
$valoracionesEnfermeria = DB::table('valoraciones_enfermerias')
            ->select('valoraciones_enfermerias.*')
            ->where('id_enfermeria',$id_nota_ingreso)
            ->first();

$signos_vitales = "SELECT * FROM notas_vitales_enfermerias where id_enfermeria = '$id_nota_ingreso' order by id_caracter_fisico asc";
$signos_vitales = DB::select($signos_vitales);

$valoraciones_enfermerias =  DB::table('valoraciones_enfermerias')
                ->select('valoraciones_enfermerias.*')
                ->where('id_enfermeria',$id_nota_ingreso)
                ->first();

$pieldispositivo_enfermerias =  DB::table('pieldispositivo_enfermerias')
                ->select('pieldispositivo_enfermerias.*')
                ->where('id_enfermeria',$id_nota_ingreso)
                ->first();

$enfermeria_escalas =  DB::table('enfermeria_escalas')
            ->select('enfermeria_escalas.*')
            ->where('id_enfermeria',$id_nota_ingreso)
            ->first();
          
$nombre = '';
if(isset($antecedentesenfermeria->cardio_parametro_1)){
$dato1 = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$antecedentesenfermeria->cardio_parametro_1)
                ->first();
$nombre = $nombre .' '. $dato1->descrip_corta;
}
if(isset($antecedentesenfermeria->cardio_parametro_2)){
$dato2 = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$antecedentesenfermeria->cardio_parametro_2)
                ->first();
$nombre = $nombre .' - '. $dato2->descrip_corta;
}
if(isset($antecedentesenfermeria->cardio_parametro_3)){
$dato3 = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$antecedentesenfermeria->cardio_parametro_3)
                ->first();
$nombre = $nombre .' - '. $dato3->descrip_corta;
}
if(isset($antecedentesenfermeria->cardio_parametro_4)){
$dato4 = DB::table('parametros')
                ->select('parametros.*')
                ->where('id',$antecedentesenfermeria->cardio_parametro_4)
                ->first();
$nombre = $nombre .' - '. $dato4->descrip_corta ;
}

$ingresa_por = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_ingresa_por)
->first();

$condicion_ingreso = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_condicion_ingreso)
->first();

$estado_conciencia = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_estado_conciencia)
->first();

$funcion_motora = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_funcion_motora)
->first();

$respiratorio = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_respiratorio)
->first();

/* $detalle_oxigeno  = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->oxigenoterapia_parametro)
->first(); */
$oxigeno_ids = [];
$nombres_oxigeno = "";
if (!empty($valoraciones_enfermerias->oxigenoterapia_parametros)) {
    $oxigeno_ids = array_map('intval', explode(',', $valoraciones_enfermerias->oxigenoterapia_parametros));
}
if (!empty($oxigeno_ids)) {
    $detalles_oxigeno = DB::table('parametros')
        ->whereIn('id', $oxigeno_ids)
        ->get();
    $nombres_oxigeno = array_map(function ($item) {
        return $item->descrip_larga;
    }, $detalles_oxigeno);
}

$piel = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_piel)
->first();

$integridad = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_integridad)
->first();

$abdomen = DB::table('parametros')
->select('parametros.*')
->where('id',$valoraciones_enfermerias->id_abdomen)
->first();

$puntos = "SELECT * FROM puntos where id_enfermeria = '$id_nota_ingreso'";
$puntosenviados = DB::select($puntos);      

$emergencia_data_jhon = DB::table('emergencias')
->select('emergencias.*')
->where('id',$id_emergencia)
->first();

if($emergencia_data_jhon->dni_beneficiario != $emergencia_data_jhon->dni_titular){
$asegurado_titular = DB::table('asegurados')
->select('asegurados.*')
->where('nro_doc_ident',$emergencia_data_jhon->dni_titular)
->where('id_estado_reg','1')
->first();
}else{
$asegurado_titular = DB::table('asegurados')
->select('asegurados.*')
->where('nro_doc_ident',$emergencia_data_jhon->dni_beneficiario)
->where('id_estado_reg','1')
->first();
}

            $pdf = \PDF::loadView('frontend.hospitalizacion_enfermeria.pdf_nota_ingreso_enfermeria_hospitalizacion',
            compact(
                'valores_Cardio',
                'datos_nota_ingreso',  
                /* 'datos_atencion', */
                'nota_detalle' ,
                'nombre_establecimiento',
                'fechaActual',
                'horaActual','usuario_enfermeria',
                'servicio','pabellon','piso','cuarto','cama','dato_cabecera_jhon',
                'datos_nota_ingreso','datos_atencion','id_tipo_atenciones','antecedentesenfermeria','valoracionesEnfermeria','nombre',
                'signos_vitales','valoraciones_enfermerias','pieldispositivo_enfermerias','enfermeria_escalas','ingresa_por','condicion_ingreso',
                'estado_conciencia','funcion_motora','respiratorio'/* ,'detalle_oxigeno' */,'piel','integridad','abdomen','puntosenviados','asegurado_titular',
                'establecimiento','diagnosticos','diagnosticosEvaluacion', 'nombres_oxigeno'
            

            ));
            $pdf->getDomPDF()->set_option("enable_php", true);
            //$pdf->setPaper('A5', 'portrait');
            $pdf->setPaper('A4', 'portrait'); //Horizontal
            return $pdf->stream('nota_ingreso_emergencia.pdf'); 

        }else{
            return redirect('/emergencia_by_enfermeria/')->withInput()->withErrors(array('Elemento no encontrado ..!')); 
        }
	}



    
	// public function registrar_nota_ingreso_evoluciones(Request $request){
	// 	if (!Auth::check()) :
	// 		return redirect('/auth/login');
	// 	endif;
        
  //       //dd($request->all()); 
  //       $valor_response=0;
  //       try{
  //           DB::beginTransaction(); 

  //           $objNota_enfermeria_evoluciones = new Nota_enfermeria_evoluciones;
  //           $objNota_enfermeria_evoluciones->id_usuario_registro = Auth::user()->id;
  //           $objNota_enfermeria_evoluciones->id_atencion = $request->id_atencion_evolucion_registro; 
  //           $objNota_enfermeria_evoluciones->id_tipo_atenciones = $request->id_tipo_atenciones_evolucion_registro; 
  //           $objNota_enfermeria_evoluciones->id_nota_enfermeria = !empty($request->id_nota_enfermeria_evolucion_registro)?$request->id_nota_enfermeria_evolucion_registro: 0; 

  //           $objNota_enfermeria_evoluciones->descripcion_turno = $request->descripcion_turno; 
  //           $objNota_enfermeria_evoluciones->descripcion_subjetivo = $request->descripcion_subjetivo; 
  //           $objNota_enfermeria_evoluciones->descripcion_objetivo = $request->descripcion_objetivo; 

  //           $objNota_enfermeria_evoluciones->descripcion_intervenciones = $request->descripcion_intervenciones;  
  //           $objNota_enfermeria_evoluciones->descripcion_evaluaciones = $request->descripcion_evaluaciones;  
  //           //$objNota_enfermeria_evoluciones->observacion = $request->observacion; 
  //           $objNota_enfermeria_evoluciones->id_establecimiento = Auth::user()->id_establecimiento; 
  //           $objNota_enfermeria_evoluciones->id_asegurado = $request->id_asegurado2;

  //           $objNota_enfermeria_evoluciones->save();  
  //           $id_enfermeria_evoluciones = $objNota_enfermeria_evoluciones->id;

  //           if($request->id_relacionado_DIAGNOSTICO_ENFERMERIA){
  //               //DIAGNOSTICO_ENFERMERIA
  //               foreach ($request->id_relacionado_DIAGNOSTICO_ENFERMERIA as $key => $value) {
  //                   $nota_enfermeria_evoluciones_detalleDE = new Nota_enfermeria_evoluciones_detalle();
  //                   $nota_enfermeria_evoluciones_detalleDE->id_enfermeria_evoluciones = $id_enfermeria_evoluciones;
  //                   $id_de_seleccion = $request->id_relacionado_DIAGNOSTICO_ENFERMERIA[$key];
  //                   $valor_de_seleccion = $request->cantidad_detalle_DIAGNOSTICO_ENFERMERIA[$key];
  //                   $tipo_de_seleccion = 'DIAGNOSTICO_ENFERMERIA';
  //                   $nota_enfermeria_evoluciones_detalleDE->id_de_seleccion = $id_de_seleccion; 
  //                   $nota_enfermeria_evoluciones_detalleDE->valor_de_seleccion = $valor_de_seleccion; 
  //                   $nota_enfermeria_evoluciones_detalleDE->tipo_de_seleccion = $tipo_de_seleccion; 
  //                   $nota_enfermeria_evoluciones_detalleDE->save();
  //               }
  //           }

  //           if($request->id_relacionado_PLAN_CUIDADO_ENFERMERIA){
  //               //PLAN_CUIDADO_ENFERMERIA
  //               foreach ($request->id_relacionado_PLAN_CUIDADO_ENFERMERIA as $key => $value) {
  //                   $nota_enfermeria_evoluciones_detallePE = new Nota_enfermeria_evoluciones_detalle();
  //                   $nota_enfermeria_evoluciones_detallePE->id_enfermeria_evoluciones = $id_enfermeria_evoluciones;
  //                   $id_de_seleccion = $request->id_relacionado_PLAN_CUIDADO_ENFERMERIA[$key];
  //                   $valor_de_seleccion = $request->cantidad_detalle_PLAN_CUIDADO_ENFERMERIA[$key];
  //                   $tipo_de_seleccion = 'PLAN_CUIDADO_ENFERMERIA';
  //                   $nota_enfermeria_evoluciones_detallePE->id_de_seleccion = $id_de_seleccion; 
  //                   $nota_enfermeria_evoluciones_detallePE->valor_de_seleccion = $valor_de_seleccion; 
  //                   $nota_enfermeria_evoluciones_detallePE->tipo_de_seleccion = $tipo_de_seleccion; 
  //                   $nota_enfermeria_evoluciones_detallePE->save();
  //               }
                
  //               //EVALUACIONES_ENFERMERIA
  //               foreach ($request->id_relacionado_PLAN_CUIDADO_ENFERMERIA as $key => $value) {
  //                   $nota_enfermeria_evoluciones_detalleIE = new Nota_enfermeria_evoluciones_detalle();
  //                   $nota_enfermeria_evoluciones_detalleIE->id_enfermeria_evoluciones = $id_enfermeria_evoluciones;
  //                   $id_de_seleccion = $request->id_relacionado_PLAN_CUIDADO_ENFERMERIA[$key];
  //                   $valor_de_seleccion = $request->cantidad_detalle_EVALUACIONES_ENFERMERIA[$key];
  //                   $tipo_de_seleccion = 'EVALUACIONES_ENFERMERIA';
  //                   $nota_enfermeria_evoluciones_detalleIE->id_de_seleccion = $id_de_seleccion; 
  //                   $nota_enfermeria_evoluciones_detalleIE->valor_de_seleccion = $valor_de_seleccion; 
  //                   $nota_enfermeria_evoluciones_detalleIE->tipo_de_seleccion = $tipo_de_seleccion; 
  //                   $nota_enfermeria_evoluciones_detalleIE->save();
  //               }
  //           }

  //           if($request->id_relacionado_INTERVENCIONES_ENFERMERIA){
  //               //INTERVENCIONES_ENFERMERIA
  //               foreach ($request->id_relacionado_INTERVENCIONES_ENFERMERIA as $key => $value) {
  //                   $nota_enfermeria_evoluciones_detalleIE = new Nota_enfermeria_evoluciones_detalle();
  //                   $nota_enfermeria_evoluciones_detalleIE->id_enfermeria_evoluciones = $id_enfermeria_evoluciones;
  //                   $id_de_seleccion = $request->id_relacionado_INTERVENCIONES_ENFERMERIA[$key];
  //                   $valor_de_seleccion = $request->cantidad_detalle_INTERVENCIONES_ENFERMERIA[$key];
  //                   $tipo_de_seleccion = 'INTERVENCIONES_ENFERMERIA';
  //                   $nota_enfermeria_evoluciones_detalleIE->id_de_seleccion = $id_de_seleccion; 
  //                   $nota_enfermeria_evoluciones_detalleIE->valor_de_seleccion = $valor_de_seleccion; 
  //                   $nota_enfermeria_evoluciones_detalleIE->tipo_de_seleccion = $tipo_de_seleccion; 
  //                   $nota_enfermeria_evoluciones_detalleIE->save();
  //               }
  //           }

  //           if($request->id_relacionado_CPMS_ENFERMERIA){
  //               //CPMS_ENFERMERIA
  //               foreach ($request->id_relacionado_CPMS_ENFERMERIA as $key => $value) {
  //                   $nota_enfermeria_evoluciones_detallePE = new Nota_enfermeria_evoluciones_detalle();
  //                   $nota_enfermeria_evoluciones_detallePE->id_enfermeria_evoluciones = $id_enfermeria_evoluciones;
  //                   $id_de_seleccion = $request->id_relacionado_CPMS_ENFERMERIA[$key];
  //                   $valor_de_seleccion = 0;
  //                   $tipo_de_seleccion = 'CPMS_ENFERMERIA';
  //                   $nota_enfermeria_evoluciones_detallePE->id_de_seleccion = $id_de_seleccion; 
  //                   $nota_enfermeria_evoluciones_detallePE->valor_de_seleccion = $valor_de_seleccion; 
  //                   $nota_enfermeria_evoluciones_detallePE->tipo_de_seleccion = $tipo_de_seleccion; 
  //                   $nota_enfermeria_evoluciones_detallePE->save();
  //               }
  //           }
  //           $valor_response= $id_enfermeria_evoluciones;
  //           DB::commit();

  //       }catch(\Exception $e) { 
  //           DB::rollback();
  //       }

  //       return json_encode($valor_response); 
	// }


// DESDE ACA EMPIEZA LA SAECCION DE ENFERMERIA PARA EL MODULO PRIMERO EVOLUCIONES
	// public function lista_nota_ingreso_evoluciones(Request $request){
	// 	if (!Auth::check()) :
	// 		return redirect('/auth/login');
	// 	endif; 
 
  //       $objNota_enfermeria_evoluciones = new Nota_enfermeria_evoluciones; 
	// 	$p[] = $request->fecha_evolucion_bus_inicial_listado;  //0
	// 	$p[] = $request->fecha_evolucion_bus_final_listado;  //1 
	// 	$p[] = $request->descripcion_turno_bus_listado;  //2 
  //   $p[] = $request->id_prestacion;  //3 - ID PRESTACION 

	// 	$data = $objNota_enfermeria_evoluciones->getlista_nota_ingreso_evoluciones($p); 
  //       return json_encode($data);   
  //   }



	// public function ver_nota_ingreso_evoluciones(Request $request){
	// 	if (!Auth::check()) :
	// 		return redirect('/auth/login');
	// 	endif; 

  //       $objNota_enfermeria_evoluciones = new Nota_enfermeria_evoluciones; 
	// 	$p[] = $request->id_evoluciones;  //0 
 
  //       $data['nota_enfermeria']= $objNota_enfermeria_evoluciones->getver_nota_ingreso_evoluciones($p); 

  //       $tipo_de_seleccion_diagnostico = 'DIAGNOSTICO_ENFERMERIA';
  //       $data['nota_enfermeria_diagnostico'] = $objNota_enfermeria_evoluciones->getver_nota_ingreso_evoluciones_detalle_diagnostico($p, $tipo_de_seleccion_diagnostico); 

  //       $tipo_de_seleccion_plan_cuidado = 'PLAN_CUIDADO_ENFERMERIA';
  //       $data['nota_enfermeria_plan_cuidado'] = $objNota_enfermeria_evoluciones->getver_nota_ingreso_evoluciones_detalle_diagnostico($p, $tipo_de_seleccion_plan_cuidado); 

  //       $tipo_de_seleccion_intervenciones = 'INTERVENCIONES_ENFERMERIA';
  //       $data['nota_enfermeria_intervenciones'] = $objNota_enfermeria_evoluciones->getver_nota_ingreso_evoluciones_detalle_diagnostico($p, $tipo_de_seleccion_intervenciones); 

  //       $tipo_de_seleccion_evaluaciones= 'EVALUACIONES_ENFERMERIA';
  //       $data['nota_enfermeria_evaluaciones'] = $objNota_enfermeria_evoluciones->getver_nota_ingreso_evoluciones_detalle_diagnostico($p, $tipo_de_seleccion_evaluaciones); 
 
  //       $data['nota_enfermeria_cpms'] = $objNota_enfermeria_evoluciones->getver_nota_ingreso_evoluciones_detalle_cpms($p); 
 
  //       return json_encode($data);   

  //   }

	// public function pdf_nota_ingreso_evoluciones($id_evoluciones){
	// 	if (!Auth::check()) :
	// 		return redirect('/auth/login');
	// 	endif; 
    
  //       $date = Carbon::now();
  //       $fechaActual = $date->format('d/m/Y');
  //       $horaActual = $date->format('H:i:s');

	// 	$nota_ingreso = new Nota_enfermeria; 
	// 	$model_receta = new \App\Models\Receta_vale;
	// 	$model_receta_diagnostico = new \App\Models\Receta_diagnostico;  
  //       //--- ESTABLECIMIENTO
  //       $id_establecimiento=Auth::user()->id_establecimiento;// id_establecimiento = 76 - 1 mdico  
  //       $model_establecimiento = new \App\Models\Establecimiento();
  //       $establecimiento=  $model_establecimiento->GetById($id_establecimiento);
  //       $nombre_establecimiento = $establecimiento[0]->nombre;
  //       //--- -----------

  //       $objNota_enfermeria_evoluciones = new Nota_enfermeria_evoluciones; 
	// 	$p[] = $id_evoluciones;  //0 
 
  //       $nota_enfermeria= $objNota_enfermeria_evoluciones->getver_nota_ingreso_evoluciones($p); 
        
  //       $tipo_de_seleccion_diagnostico = 'DIAGNOSTICO_ENFERMERIA';
  //       $nota_enfermeria_diagnostico= $objNota_enfermeria_evoluciones->getver_nota_ingreso_evoluciones_detalle_diagnostico($p, $tipo_de_seleccion_diagnostico); 
        
  //       $tipo_de_seleccion_plan_cuidado = 'PLAN_CUIDADO_ENFERMERIA';
  //       $nota_enfermeria_plan_cuidado = $objNota_enfermeria_evoluciones->getver_nota_ingreso_evoluciones_detalle_diagnostico($p, $tipo_de_seleccion_plan_cuidado); 

  //       $tipo_de_seleccion_intervenciones = 'INTERVENCIONES_ENFERMERIA';
  //       $nota_enfermeria_intervenciones = $objNota_enfermeria_evoluciones->getver_nota_ingreso_evoluciones_detalle_diagnostico($p, $tipo_de_seleccion_intervenciones); 

  //       $tipo_de_seleccion_evaluaciones= 'EVALUACIONES_ENFERMERIA';
  //       $nota_enfermeria_evaluaciones = $objNota_enfermeria_evoluciones->getver_nota_ingreso_evoluciones_detalle_diagnostico($p, $tipo_de_seleccion_evaluaciones); 
 
  //       $nota_enfermeria_cpms = $objNota_enfermeria_evoluciones->getver_nota_ingreso_evoluciones_detalle_cpms($p); 
  //       $id_atencion='';//PUEDE SER EL ID DE EMERGENCIA O HOSPITALIZACION
  //       $codigo_ubig_responsable='';
  //       $codigo_ubig_apoderado='';
  //       $datos_nota_ingreso='';
  //       $datos_atencion='';
  //       $valores_cardio='';
  //       //if($nota_enfermeria[0]->id_tipo_atenciones == 3){ ///HOSPITALIZACION
  //           $id_atencion = $nota_enfermeria[0]->id_atencion;
            
  //           //$codigo_ubig_responsable = $model_receta->getObtener_codigo_ubig_responsable_emergencia($id_atencion);//PUEDE SER EL ID DE EMERGENCIA O HOSPITALIZACION
  //           //$codigo_ubig_apoderado = $model_receta->getObtener_codigo_ubig_apoderado_emergencia($id_atencion);//PUEDE SER EL ID DE EMERGENCIA O HOSPITALIZACION
  //           $datos_nota_ingreso = $nota_ingreso->getObtener_nota_ingreso_by_id($nota_enfermeria[0]->id_nota_enfermeria, $nota_enfermeria[0]->id_tipo_atenciones); 
  //           $datos_atencion = $model_receta->getEmergenciaById_farmacia_new($id_atencion);
  //           $diagnosticos = $model_receta_diagnostico->DiagnosticosByIdHospitalizacion($id_atencion); 
           
  //       //} 
          
  //       // $valores_Cardio=$nota_ingreso->obtener_descripcion_cardiologico($datos_nota_ingreso[0]->cardiologico);  
  //       // if($valores_Cardio){ 
  //       //     $valores_Cardio=$valores_Cardio->descripcion_cardiologico;
  //       // }

  //       $pdf = \PDF::loadView('frontend.emergencia_enfermeria.evoluciones.pdf_evoluciones.pdf_nota_ingreso_evoluciones', 
  //       compact(
  //           'nota_enfermeria',
  //           'nota_enfermeria_diagnostico',  
  //           'nota_enfermeria_plan_cuidado',
  //           'nota_enfermeria_intervenciones' ,
  //           'nota_enfermeria_evaluaciones' ,
  //           'nota_enfermeria_cpms' ,
  //           'datos_nota_ingreso',
  //           // 'valores_Cardio',
  //           'diagnosticos',
  //           'datos_atencion',
  //           'nombre_establecimiento',
  //           'fechaActual',
  //           'horaActual' 
  //       ));
  //       $pdf->getDomPDF()->set_option("enable_php", true);
  //       //$pdf->setPaper('A5', 'portrait');
  //       $pdf->setPaper('A4', 'portrait'); //Horizontal
  //       return $pdf->stream('nota_ingreso_evoluciones.pdf'); 
 
  //   }


    public function mostrar_nota_enfermeria($id)
    {
		$nota_ingreso = new Nota_enfermeria; 
		$data = $nota_ingreso->getMostrarNotaEnfermeria($id);  
        return json_encode($data); 
    }

    /* public function formulario_nota_ingreso_emergencia($id_atencion,$id_tipo_atenciones){ 
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;
        $modelfarma = new \App\Models\Farmacia;
        $model_receta = new \App\Models\Receta_vale;
        $model_receta_diagnostico = new \App\Models\Receta_diagnostico;
        $model_establecimiento = new \App\Models\Establecimiento;
        
        $caracteres_fisico = caracterFisico::select('id', 'descripcion')->where('flag_nota_enfermeria', '1')->Where('estado', '1')->get();
        $caracteresFisicoArray = $caracteres_fisico->pluck('descripcion', 'id')->toArray();
    
        if($id_tipo_atenciones == 2){ 
            $verificar_existe = Emergencia::where('id', $id_atencion)->first();

            $id_emergencia = $id_atencion;
            if (is_object($verificar_existe)) {
                
                if($verificar_existe->estado == 2 || $verificar_existe->estado == 3) {
                    $farmacia = $modelfarma->GetFarmaByUser(Auth::user()->id); 
                    $date = Carbon::now();
                    $fecha_actual = $date->format('d-m-Y');
        
                    $id_ipress = $farmacia->id_establecimiento;
                    $establecimiento = $model_establecimiento->GetById($id_ipress);
                    if ($id_ipress == 1) {
                        $nivel = 2;
                    } else {
                        $nivel = $establecimiento[0]->nivel;
                    } 
                    
                    $datos_emergencia = $model_receta->getEmergenciaById_farmacia_new($id_emergencia);
                    $emergencia_data_jhon = DB::table('emergencias')
                    ->select('emergencias.*')
                    ->where('id',$id_emergencia)
                    ->first();
        
                    if($emergencia_data_jhon->dni_beneficiario != $emergencia_data_jhon->dni_titular){
                        $asegurado_titular = DB::table('asegurados')
                        ->select('asegurados.*')
                        ->where('nro_doc_ident',$emergencia_data_jhon->dni_titular)
                        ->where('id_estado_reg','1')
                        ->first();
                    }else{
                        $asegurado_titular = DB::table('asegurados')
                        ->select('asegurados.*')
                        ->where('nro_doc_ident',$emergencia_data_jhon->dni_beneficiario)
                        ->where('id_estado_reg','1')
                        ->first();
                    }

                    $ingresapor_emergencia = DB::table('tipo_atenciones')
                    ->select('tipo_atenciones.*')
                    ->where('id','2')
                    ->first();


                $diagnosticos = $model_receta_diagnostico->DiagnosticosByEmergencia_Enfermeria_formulario_prueba($id_emergencia);

                $condicion_ingre = "SELECT * FROM parametros where descrip_tipo = 'CONDICION_INGRESO' ";
                $condicion_ingre  = DB::select($condicion_ingre);

                $estado_conci = "SELECT * FROM parametros where descrip_tipo = 'CONDICION_INGRESO' ";
                $estado_conci  = DB::select($estado_conci);

                    return view('frontend.emergencia_enfermeria.formulario_nota_ingresos_emergencia', compact('caracteresFisicoArray','nivel', 'caracteres_fisico','establecimiento',
                    'datos_emergencia', 'id_atencion','estado_conci','condicion_ingre', 'id_tipo_atenciones','ingresapor_emergencia', 'diagnosticos','diagnosticos_evaluacion','asegurado_titular', 'id_emergencia'));

                }else{
                    return redirect('/emergencia_by_enfermeria/')->withInput()->withErrors(array('El registro seleccionado ya cuenta con una Nota ya registrada..!'));
                }
            }else {
                return redirect('/emergencia_by_enfermeria/')->withInput()->withErrors(array('Elemento no encontrados ..!'));
            }
        }else {
            return redirect('/emergencia_by_enfermeria/')->withInput()->withErrors(array('Elemento no encontradot ..!'));
        }
    } */

    public function formulario_nota_ingreso_emergencia($id_atencion, $id_tipo_atenciones)
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }

        // Instancias de los modelos
        $modelfarma = new \App\Models\Farmacia;
        $model_receta = new \App\Models\Receta_vale;
        $model_receta_diagnostico = new \App\Models\Receta_diagnostico;
        $model_establecimiento = new \App\Models\Establecimiento;

        // Validar tipo de atención
        if ($id_tipo_atenciones != 2) {
            return $this->redirectWithError('/emergencia_by_enfermeria/', 'Elemento no encontrado.');
        }

        // Verificar existencia de emergencia
        $verificar_existe = Emergencia::where('id', $id_atencion)->first();

        if (!$verificar_existe) {
            return $this->redirectWithError('/emergencia_by_enfermeria/', 'Elemento no encontrado.');
        }

        // Validar estado de la emergencia
        if (!in_array($verificar_existe->estado, [2, 3])) {
            return $this->redirectWithError('/emergencia_by_enfermeria/', 'El registro seleccionado ya cuenta con una Nota ya registrada.');
        }

        // Consultar datos requeridos
        $caracteres_fisico = caracterFisico::select('id', 'descripcion')
            ->where('flag_nota_enfermeria', '1')
            ->where('estado', '1')
            ->get();

        $caracteresFisicoArray = $caracteres_fisico->pluck('descripcion', 'id')->toArray();

        $farmacia = $modelfarma->GetFarmaByUser(Auth::user()->id);
        $establecimiento = $model_establecimiento->GetById($farmacia->id_establecimiento);
        $nivel = $farmacia->id_establecimiento == 1 ? 2: (isset($establecimiento[0]->nivel) ? $establecimiento[0]->nivel : null);

        $datos_emergencia = $model_receta->getEmergenciaById_farmacia_new($id_atencion);
        $asegurado_titular = $this->getAseguradoTitular($verificar_existe);

        $prestacion_id = "";
        $buscarPrestacion = Prestaciones::where("id_ext", $id_atencion)->where("id_tipo_atencion", 2)->select("id as prestacion_id")->first();
        if($buscarPrestacion) {
            $prestacion_id = $buscarPrestacion->prestacion_id;
        }

        $ingresapor_emergencia = DB::table('tipo_atenciones')
            ->select('tipo_atenciones.*')
            ->where('id', '2')
            ->first();

        $diagnosticos = $model_receta_diagnostico->DiagnosticosByEmergencia_Enfermeria_formulario_prueba($id_atencion);

        $condicion_ingre = DB::table('parametros')
            ->where('descrip_tipo', 'CONDICION_INGRESO')
            ->get();

        $estado_conci = DB::table('parametros')
            ->where('descrip_tipo', 'CONDICION_INGRESO')
            ->get();

        // Retornar vista con datos
        return view('frontend.emergencia_enfermeria.formulario_nota_ingresos_emergencia', compact(
            'caracteresFisicoArray',
            'prestacion_id',
            'nivel',
            'caracteres_fisico',
            'establecimiento',
            'datos_emergencia',
            'id_atencion',
            'estado_conci',
            'condicion_ingre',
            'id_tipo_atenciones',
            'ingresapor_emergencia',
            'diagnosticos',
            'asegurado_titular',
            'id_atencion'
        ));
    }

    private function redirectWithError(string $route, string $message)
    {
        return redirect($route)->withInput()->withErrors([$message]);
    }

    private function getAseguradoTitular($emergencia)
    {
        $dni = $emergencia->dni_beneficiario !== $emergencia->dni_titular
            ? $emergencia->dni_titular
            : $emergencia->dni_beneficiario;

        return DB::table('asegurados')
            ->where('nro_doc_ident', $dni)
            ->where('id_estado_reg', '1')
            ->first();
    }

    public function formulario_nota_ingreso_hospitalizacion($id_hospitalizacion, $id_tipo_atenciones){ 
        if (!Auth::check()) :
            return redirect('/auth/login');
        endif;
        $modelfarma = new \App\Models\Farmacia;
        $model_receta = new \App\Models\Receta_vale;
        $model_nota_enfermeria = new Nota_enfermeria();
        $model_receta_diagnostico = new \App\Models\Receta_diagnostico;
        $model_establecimiento = new \App\Models\Establecimiento;
        $model_prestacion = new Prestaciones();
        $caracteres_fisico = caracterFisico::select('id', 'descripcion')->where('flag_nota_enfermeria', '1')->Where('estado', '1')->get();
        $caracteresFisicoArray = $caracteres_fisico->pluck('descripcion', 'id')->toArray();
        $id_atencion = $id_hospitalizacion;
    
        $verificar_existe = Hospitalizacione::where('id', $id_hospitalizacion)->first();
        $enfermeriaExistente = Enfermeria::where("id_ext_enfermeria", $verificar_existe->id)->where("id_tipo_atencion", 3)->first();

        if (is_object($verificar_existe)) {
            
            if(/* $verificar_existe->estado == 1 || $verificar_existe->estado == 3 || $verificar_existe->estado == 4 */!$enfermeriaExistente) {
                $farmacia = $modelfarma->GetFarmaByUser(Auth::user()->id); 
                $date = Carbon::now();
                $fecha_actual = $date->format('d-m-Y');
    
                $id_ipress = $farmacia->id_establecimiento;
                $establecimiento = $model_establecimiento->GetById($id_ipress);
                if ($id_ipress == 1) {
                    $nivel = 2;
                } else {
                    $nivel = $establecimiento[0]->nivel;
                } 
                
                $datos_emergencia = $model_nota_enfermeria->getHospitalizacionById_farmacia_new2($id_hospitalizacion);

                $hospitalizacion = DB::table('hospitalizaciones')
                ->select('hospitalizaciones.*')
                ->where('id',$id_hospitalizacion)
                ->first();

                $asegurado_titular = null;
                if($hospitalizacion->dni_beneficiario != $hospitalizacion->dni_titular){
                    $asegurado_titular = DB::table('asegurados')
                    ->select('asegurados.*')
                    ->where('nro_doc_ident',$hospitalizacion->dni_titular)
                    ->where('id_estado_reg','1')
                    ->first();
                }else{
                    $asegurado_titular = DB::table('asegurados')
                    ->select('asegurados.*')
                    ->where('nro_doc_ident',$hospitalizacion->dni_beneficiario)
                    ->where('id_estado_reg','1')
                    ->first();
                }
                

                $ingresapor_emergencia = DB::table('tipo_atenciones')
                ->select('tipo_atenciones.*')
                ->where('id','3')
                ->first();

                $diagnosticos = $model_receta_diagnostico->DiagnosticosByHospitalizacion_Enfermeria($hospitalizacion->id);
                $existenDiagnosticosDeAtencion = true;
                if (empty($diagnosticos)) {
                    $diagnostico1 = $model_nota_enfermeria->DiagnosticosById($hospitalizacion->id_diagnostico_inicial);
                    $diagnostico2 = $model_nota_enfermeria->DiagnosticosById($hospitalizacion->id_dx_2);
                    $diagnostico3 = $model_nota_enfermeria->DiagnosticosById($hospitalizacion->id_dx_3);
                    $diagnosticos = [$diagnostico1, $diagnostico2, $diagnostico3];
                    $existenDiagnosticosDeAtencion = false;
                }

                $condicion_ingre = "SELECT * FROM parametros where descrip_tipo = 'CONDICION_INGRESO' ";
                $condicion_ingre  = DB::select($condicion_ingre);

                $estado_conci = "SELECT * FROM parametros where descrip_tipo = 'CONDICION_INGRESO' ";
                $estado_conci  = DB::select($estado_conci);

                return view('frontend.hospitalizacion_enfermeria.formulario_nota_ingresos_hospitalizacion', compact('caracteresFisicoArray','nivel', 'caracteres_fisico','establecimiento',
                'datos_emergencia', 'id_atencion','estado_conci','condicion_ingre', 'id_tipo_atenciones','ingresapor_emergencia', 'diagnosticos','diagnosticos_evaluacion','asegurado_titular', 'existenDiagnosticosDeAtencion'));

            } else {
                return redirect('/hospitalizacion_by_enfermeria/')->withInput()->withErrors(array('El registro seleccionado ya cuenta con una Nota ya registrada..!'));
            }
        } else {
            return redirect('/hospitalizacion_by_enfermeria/')->withInput()->withErrors(array('Elemento no encontrados ..!'));
        }
    }

// MODULO EDITAR EMERGENCIA NOTA DE INGRESIS
public function editar_nota_ingreso_enfermeria_emergencia($id_nota_ingreso, $id_tipo_atenciones)
{
    if (!Auth::check()) {
        return redirect('/auth/login');
    }
    $modelfarma = new \App\Models\Farmacia;
    $model_receta = new \App\Models\Receta_vale;
    $model_receta_diagnostico = new \App\Models\Receta_diagnostico;
    $model_establecimiento = new \App\Models\Establecimiento;
    $nota_ingreso = new Nota_enfermeria;
    // $condiciones_parametros = Parametros::where('descrip_tipo','CONDICION_INGRESO')->get();
    $condiciones_parametros = Parametros::select('id','descrip_corta')->where('descrip_tipo','CONDICION_INGRESO')->get();
    $estados_conciencia=Parametros::select('id','descrip_corta')->where('descrip_tipo','ESTADO_CONCIENCIA')->get();
    $funciones_motoras=Parametros::select('id','descrip_corta')->where('descrip_tipo','FUNCION_MOTORA')->get();
    $respiratorios=Parametros::select('id','descrip_corta')->where('descrip_tipo','RESPIRATORIO')->get();
    $detalles_oxigenterapias=Parametros::select('id','descrip_corta')->where('descrip_tipo','OXIGENOTERAPIA')->get();
    $piel_colores=Parametros::select('id','descrip_corta')->where('descrip_tipo','PIEL')->get();
    $integridades=Parametros::select('id','descrip_corta')->where('descrip_tipo','INTEGRIDAD')->get();
    $abdomenes=Parametros::select('id','descrip_corta')->where('descrip_tipo','INTEGRIDAD')->get();
    
    $datos_nota_ingreso = $nota_ingreso->getObtener_nota_ingreso_by_id($id_nota_ingreso, $id_tipo_atenciones);

    $categories = collect($datos_nota_ingreso);

    $id_condicion_ingreso = $categories->pluck('id_condicion_ingreso')[0];

    $id_estado_conciencia=$categories->pluck('id_estado_conciencia')[0];
    $id_funcion_motora=$categories->pluck('id_funcion_motora')[0];
    $id_respiratorio=$categories->pluck('id_repiratorio_duracion')[0];
    $id_oxigenoterapia_detalle_seleccion=$categories->pluck('id_oxigenoterapia_detalle_seleccion')[0];
    $id_piel_colores=$categories->pluck('id_piel_colores')[0];
    $id_integridad=$categories->pluck('id_integridad')[0];
    $id_abdomen=$categories->pluck('id_abdomen')[0];

    if (is_object($datos_nota_ingreso[0])) {
        $farmacia = $modelfarma->GetFarmaByUser(Auth::user()->id);
        $id_ipress = $farmacia->id_establecimiento;
        $establecimiento = $model_establecimiento->GetById($id_ipress);
   
        if($id_tipo_atenciones == 2) {
            $id_emergencia = $datos_nota_ingreso[0]->id_ext_enfermeria;
         
            $codigo_ubig_responsable = $model_receta->getObtener_codigo_ubig_responsable_emergencia($id_emergencia);
            $codigo_ubig_apoderado = $model_receta->getObtener_codigo_ubig_apoderado_emergencia($id_emergencia);
            $datos_atencion = $model_receta->getEmergenciaById_farmacia_new($id_emergencia, $codigo_ubig_responsable, $codigo_ubig_apoderado);

            $diagnosticos = $model_receta_diagnostico->DiagnosticosByEmergencia_Enfermeria_formulario_prueba($id_emergencia);
            $diagnosticos_evaluacion = $model_receta_diagnostico->DiagnosticosByEmergencia_Enfermeria_evaluacion($id_emergencia);
            //dd($valores_cardio);
           
           // dd($valores_cardio);
            return view('frontend.emergencia_enfermeria.editar_nota_ingreso_enfermeria_emergencia',
                compact('id_abdomen','abdomenes','id_integridad','integridades','piel_colores','id_piel_colores','detalles_oxigenterapias','id_oxigenoterapia_detalle_seleccion',
                'respiratorios','id_respiratorio','id_funcion_motora','funciones_motoras','id_estado_conciencia','estados_conciencia','condiciones_parametros','id_condicion_ingreso',
                'plucked','valores_cardio', 'datos_nota_ingreso', 'datos_atencion', 'id_tipo_atenciones', 'diagnosticos', 'establecimiento', 'diagnosticos_evaluacion'));
        } else if($id_tipo_atenciones == 3) {
        }
    } else {
        return redirect('/emergencia_by_enfermeria/')->withInput()->withErrors(['Elemento no encontrado ..!']);
    }
}

public function updateNotaIngreso(Request $request)
{
    if (!Auth::check()) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }
    $id_nota_ingreso = $request->input('id_nota_ingreso');
    $presion_arterial_x = $request->input('presion_arterial_x');
    $presion_arterial_y = $request->input('presion_arterial_y');
    $frecuencia_cardiaca = $request->input('frecuencia_cardiaca');
    $frecuencia_respiratoria = $request->input('frecuencia_respiratoria');
    $fi02 = $request->input('fi02');
    $sa02_porcentaje = $request->input('sa02_porcentaje');
    $temperatura = $request->input('temperatura');
    $talla = $request->input('talla');
    $peso = $request->input('peso');
    $indice_masa_corporal = $request->input('indice_masa_corporal');
    $peso_x_altura = $request->input('peso_x_altura');
    $tiene_hta = $request->input('hta');
    $descripcion_tratamiento_hta = $tiene_hta === 'SI' ? $request->input('descripcion_tratamiento_hta') : null;
    $diabetes_mellitus = $request->input('diabetes_mellitus');
    $diabetes_mellitus_descripcion = $diabetes_mellitus === 'SI' ? $request->input('diabetes_mellitus_descripcion') : null;
    $tiene_alergia = $request->input('ram');
    $descripcion_alergia = $tiene_alergia === 'SI' ? $request->input('detalle_ram_alergia') : null;
    $descripcion_antecedente_observacion = $request->input('descripcion_antecedente_observacion');
    $descripcion_otros_antecedentes = $request->input('descripcion_otros_antecedentes');
    //SECRECIONES BRONQUIALES (tos)
    // $secreaciones_bronquiales_si = $request->input('secreaciones_bronquiales_si');
    $id_condicion_ingreso = $request->input('condicion_ingreso'); // Obtener el valor del select 'condicion_ingreso'
    $id_estado_conciencia=$request->input('estado_conciencia');
    $id_funcion_motora=$request->input('funcion_motora'); // Obtener el valor del select 'funcion_motora'
    $id_respiratorio_duracion=$request->input('respiratorio_duracion');
    $miccion_espontanea = $request->input('miccion_espontanea');
    $tiene_oxigenoterapia = $request->input('oxigenoterapia');
    $id_oxigenoterapia_detalle_seleccion = null;


    // Si oxigenoterapia es "SI", entonces obtener el valor del detalle
    if ($tiene_oxigenoterapia === 'SI') {
        $id_oxigenoterapia_detalle_seleccion = $request->input('oxigenoterapia_detalle_seleccion');
    }


    $acompaniado_por = $request->input('acompaniado_por');
    $descripcion_acompaniado_por = null;

    // Si acompaniado_por es "FAMILIAR", entonces obtener el valor del detalle
    if ($acompaniado_por === 'FAMILIAR') {
        $descripcion_acompaniado_por = $request->input('descripcion_acompaniado_por');
    }


    $emoptisis = $request->input('emoptisis');
    $id_piel_colores = $request->input('piel_colores');
    $id_integridad = $request->input('integridad');
    $id_abdomen = $request->input('abdomen');
    $id_abdomen = $request->input('abdomen');
    $via_periferica = $request->input('via_periferica');
    $via_central = $request->input('via_central');
    $caracter_alto_flujo = $request->input('caracter_alto_flujo');
    $linea_arterial = $request->input('linea_arterial');
    $escalas = $request->input('escalas');
    $brazalete_seleccion = $request->input('brazalete_seleccion');
    $tiene_brazalete = $request->input('brazalete');
    $observacion_detallada = $request->input('observacion_detallada');

    $tiene_evaluacion_mallampaty = $request->input('evaluacion_mallampaty');
    $descripcion_evaluacion_mallampaty = '*';  // Valor predeterminado cuando la evaluación es 'NO'
    
    if ($tiene_evaluacion_mallampaty === 'SI') {
        $descripcion_evaluacion_mallampaty = $request->input('descripcion_evaluacion_mallampaty');
    }
    $puntaje_calculado_glasgow = $request->input('puntaje_calculado_glasgow');
    $descripcion_detalle_glasgow = $request->input('descripcion_detalle_Glasgow');
    $puntaje_calculado_lpp = $request->input('puntaje_calculado_lpp');
    $descripcion_detalle_riesgolpp = $request->input('descripcion_detalle_riesgolpp');
    $puntaje_calculado_caidas = $request->input('puntaje_calculado_caidas');
    $descripcion_detalle_caidas = $request->input('descripcion_detalle_caidas');

    try {
        $nota_ingreso = Nota_enfermeria::find($id_nota_ingreso);
        if (!$nota_ingreso) {
            return response()->json(['success' => false, 'message' => 'Nota de ingreso no encontrada'], 404);
        }
        $nota_ingreso->presion_arterial_x = $presion_arterial_x;
        $nota_ingreso->presion_arterial_y = $presion_arterial_y;
        $nota_ingreso->frecuencia_cardiaca = $frecuencia_cardiaca;
        $nota_ingreso->frecuencia_respiratoria = $frecuencia_respiratoria;
        $nota_ingreso->fi02 = $fi02;
        $nota_ingreso->sa02_porcentaje = $sa02_porcentaje;
        $nota_ingreso->temperatura = $temperatura;
        $nota_ingreso->talla = $talla;
        $nota_ingreso->peso = $peso;
        $nota_ingreso->indice_masa_corporal = $indice_masa_corporal;
        $nota_ingreso->peso_x_altura = $peso_x_altura;
        $nota_ingreso->tiene_hta = $tiene_hta;
        $nota_ingreso->descripcion_tratamiento_hta = $descripcion_tratamiento_hta;
        $nota_ingreso->diabetes_mellitus = $diabetes_mellitus;
        $nota_ingreso->diabetes_mellitus_descripcion = $diabetes_mellitus_descripcion;
        $nota_ingreso->diabetes_mellitus = $diabetes_mellitus;
        $nota_ingreso->tiene_alergia = $tiene_alergia;
        $nota_ingreso->descripcion_alergia = $descripcion_alergia;
        $nota_ingreso->descripcion_antecedente_observacion = $descripcion_antecedente_observacion;
        // //SECRECIONES BRONQUIALES (tos)
        // $nota_ingreso->secreaciones_bronquiales_si = $secreaciones_bronquiales_si;
        $nota_ingreso->id_condicion_ingreso = $id_condicion_ingreso;
        $nota_ingreso->id_estado_conciencia=$id_estado_conciencia;
        $nota_ingreso->id_funcion_motora=$id_funcion_motora;
        $nota_ingreso->id_respiratorio_duracion=$id_respiratorio_duracion;
        $nota_ingreso->tiene_miccion_espontanea = $miccion_espontanea;
        $nota_ingreso->tiene_oxigenoterapia = $tiene_oxigenoterapia;
        $nota_ingreso->id_oxigenoterapia_detalle_seleccion = $id_oxigenoterapia_detalle_seleccion;
        $nota_ingreso->acompaniado_por = $acompaniado_por;
        $nota_ingreso->descripcion_acompaniado_por = $descripcion_acompaniado_por;
        $nota_ingreso->emoptisis = $emoptisis;
        $nota_ingreso->id_piel_colores = $id_piel_colores;
        $nota_ingreso->id_integridad = $id_integridad;
        $nota_ingreso->id_abdomen = $id_abdomen;
        // $nota_ingreso->evaluacion_mallampaty = $evaluacion_mallampaty;
        // $nota_ingreso->descripcion_evaluacion_mallampaty = $descripcion_evaluacion_mallampaty;
            // $nota_ingreso->estado_conciencia = $estado_conciencia;
            $nota_ingreso->via_periferica = $via_periferica;
            $nota_ingreso->via_central = $via_central;
            $nota_ingreso->caracter_alto_flujo = $caracter_alto_flujo;
            $nota_ingreso->linea_arterial = $linea_arterial;
            $nota_ingreso->escalas_de_dolor = $escalas;
            $nota_ingreso->brazalete_seleccion = $brazalete_seleccion;
            $nota_ingreso->tiene_brazalete = $tiene_brazalete;
            $nota_ingreso->observacion_detallada = $observacion_detallada;
            $nota_ingreso->tiene_evaluacion_mallampaty = $tiene_evaluacion_mallampaty;
            $nota_ingreso->descripcion_evaluacion_mallampaty = $descripcion_evaluacion_mallampaty;
            $nota_ingreso->puntaje_calculado_glasgow = $puntaje_calculado_glasgow;
            $nota_ingreso->descripcion_detalle_glasgow = $descripcion_detalle_glasgow;
            $nota_ingreso->puntaje_calculado_lpp = $puntaje_calculado_lpp;
            $nota_ingreso->descripcion_detalle_glasgow = $descripcion_detalle_glasgow;
            $nota_ingreso->descripcion_detalle_riesgolpp = $descripcion_detalle_riesgolpp;
            $nota_ingreso->puntaje_calculado_caidas = $puntaje_calculado_caidas;
            $nota_ingreso->descripcion_detalle_caidas = $descripcion_detalle_caidas;
            $nota_ingreso->save();
        return response()->json(['success' => true, 'message' => 'Nota De Ingreso actualizada correctamente']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Error al actualizar la nota De Ingreso'], 500);
    }
}
  //DATOS GENERALES
  public function tipoAtenciones() {
    $model_atencione = new TipoAtencione;
    $atencione = $model_atencione->ShowTipoAtencionAll();
    return response()->json($atencione);
  }
  public function diagnosticos_pacientes_prestacion($id_prestacion){
    $model = new Nota_enfermeria_evoluciones;
    $diagnosticos = $model->diagnosticos_pacientes_prestacion($id_prestacion);
    return response()->json($diagnosticos);
  }
  public function turnos(){
    $model_turno = new Turno();
    $turno = $model_turno->ShowAll();
    return response()->json($turno);
  }

  public function getDatosPacienteForEnfermeria($id_asegurado){
    $data = DB::table('asegurados')
    ->leftJoin('asegurado_historias', 'asegurado_historias.id_asegurado', '=', 'asegurados.id')
    ->leftJoin('tipo_beneficiarios as tb', 'tb.id', '=', 'asegurados.id_tipo_beneficiario')
    ->leftJoin('tipo_sangres as ts', 'asegurados.id_tipo_sangre', '=', 'ts.id')
    ->select(
        'asegurados.fecha_nac as fecha_nacimiento',
        'ts.descripcion_corta as tipo_sangre',
        'tb.descripcion as parentesgo',
        DB::raw("to_char(age(TO_DATE(asegurados.fecha_nac , 'DD/MM/YYYY')), 'YY \"A\"') as edad_corta"),
        DB::raw("to_char(age(TO_DATE(asegurados.fecha_nac , 'DD/MM/YYYY')), 'YY \"años\" \"con\" MM \"meses\"  \"y\" DD \"dias\"') as fec_nacim_descrip"),
        DB::raw("LTRIM(asegurado_historias.nro_historia) as nro_historia"),
        'asegurados.nro_doc_ident',
        'asegurados.paterno',
        'asegurados.materno',
        'asegurados.nombre',
        'asegurados.sexo',
        DB::raw("CASE
            WHEN EXISTS (
                SELECT 1
                FROM asegurados_alergias aa
                WHERE aa.id_asegurado = asegurados.id
                  AND aa.estado = true
            )
            THEN 1
            ELSE 0
        END AS tiene_alergias_registradas")
    )
    ->where('asegurados.id_estado_reg', 1)
    ->where('asegurados.id', $id_asegurado)
    ->first();
    return response()->json($data);
  }
  //FIN DATOS GENERALES

  public function GenerarPDFFirmarEnfermeria($enfermeria_id, $id_tipo_atencion)
    {
        $path = public_path('tmp/pdf');
		if (!is_dir($path)) {
			mkdir($path);
		}
		$path = public_path('tmp/pdf/sin_firma/');
		if (!is_dir($path)) {
			mkdir($path);
		}

        $enfermeriaModel = new Enfermeria;
        $model_receta_diagnostico = new \App\Models\Receta_diagnostico;
		$model_receta = new \App\Models\Receta_vale;
		$nota_ingreso = new Nota_enfermeria;
        $model_establecimiento = new \App\Models\Establecimiento();
        $usuario = Auth::user();
        $id_establecimiento=$usuario->id_establecimiento;// id_establecimiento = 76 - 1 mdico  
        $establecimiento=  $model_establecimiento->GetById($id_establecimiento);
        $nombre_establecimiento = $establecimiento[0]->nombre; 
        $enfermeria = null;
        $diagnosticos = null;
        $datos_nota_ingreso = null;
        $datos_atencion = null;
        $existenDiagnosticosDeAtencion = true;
        $id_ext = null;
        if ($id_tipo_atencion == 3) {
            $enfermeria = $enfermeriaModel->ObtenerCabeceraPdfByIdHospi($enfermeria_id);
            $hospitalizacion = DB::table('hospitalizaciones')
                ->select('hospitalizaciones.*')
                ->where('id',$enfermeria->id_hospitalizacion)
                ->first();
            $diagnosticos = $model_receta_diagnostico->DiagnosticosByHospitalizacion_Enfermeria($enfermeria->id_hospitalizacion); 
            if (empty($diagnosticos)) {
                $diagnostico1 = $nota_ingreso->DiagnosticosById($hospitalizacion->id_diagnostico_inicial);
                $diagnostico2 = $nota_ingreso->DiagnosticosById($hospitalizacion->id_dx_2);
                $diagnostico3 = $nota_ingreso->DiagnosticosById($hospitalizacion->id_dx_3);
                $diagnosticos = [$diagnostico1, $diagnostico2, $diagnostico3];
                $existenDiagnosticosDeAtencion = false;
            }
            $datos_nota_ingreso = $nota_ingreso->getObtener_nota_ingreso_by_id_hospi($enfermeria_id, $id_tipo_atencion); 
            $datos_atencion = $model_receta->getHospitalizacionById_farmacia_new($enfermeria->id_hospitalizacion);
            $id_ext = $enfermeria->id_hospitalizacion;
        } else if ($id_tipo_atencion == 2) {
            $datos_nota_ingreso = $nota_ingreso->getObtener_nota_ingreso_by_id($enfermeria_id, $id_tipo_atencion); 
            $existeAtencionMedica = Prestaciones::where('id_ext', '=', $datos_nota_ingreso[0]->id_ext_enfermeria)
            ->where('id_tipo_atencion', $id_tipo_atencion)
            ->exists();
            if ($existeAtencionMedica) {
                $enfermeria = $enfermeriaModel->ObtenerCabeceraPdfById($enfermeria_id);
            } else {
                $enfermeria = $enfermeriaModel->ObtenerCabeceraPdfById_sa($enfermeria_id);
            }
            $diagnosticos = $model_receta_diagnostico->DiagnosticosByEmergencia_Enfermeria($enfermeria->id_emergencia); 
            $datos_atencion = $model_receta->getEmergenciaById_farmacia_new($enfermeria->id_emergencia);
            $id_ext = $enfermeria->id_emergencia;
        }

        $signosVitales = enfermeriaDetalle::where('id_enfermeria', $enfermeria_id)->get();

        $codigo_ubig_responsable='';
        $codigo_ubig_apoderado='';
        $codigo_ubig_responsable = $model_receta->getObtener_codigo_ubig_responsable_emergencia($id_ext);
        $codigo_ubig_apoderado = $model_receta->getObtener_codigo_ubig_apoderado_emergencia($id_ext);
        $nota_detalle='';

        $antecedentesenfermeria = DB::table('antecedentes_enfermerias')
        ->select('antecedentes_enfermerias.*')
        ->where('id_enfermeria',$enfermeria_id)
        ->first();
  
        $valoracionesEnfermeria = DB::table('valoraciones_enfermerias')
                    ->select('valoraciones_enfermerias.*')
                    ->where('id_enfermeria',$enfermeria_id)
                    ->first();

        $signos_vitales = "SELECT * FROM notas_vitales_enfermerias where id_enfermeria = '$enfermeria_id' order by id_caracter_fisico asc";
        $signos_vitales = DB::select($signos_vitales);

        $valoraciones_enfermerias =  DB::table('valoraciones_enfermerias')
                        ->select('valoraciones_enfermerias.*')
                        ->where('id_enfermeria',$enfermeria_id)
                        ->first();

        $pieldispositivo_enfermerias =  DB::table('pieldispositivo_enfermerias')
                        ->select('pieldispositivo_enfermerias.*')
                        ->where('id_enfermeria',$enfermeria_id)
                        ->first();

        $enfermeria_escalas =  DB::table('enfermeria_escalas')
                    ->select('enfermeria_escalas.*')
                    ->where('id_enfermeria',$enfermeria_id)
                    ->first();
                
        $nombre = '';
        if(isset($antecedentesenfermeria->cardio_parametro_1)){
        $dato1 = DB::table('parametros')
                        ->select('parametros.*')
                        ->where('id',$antecedentesenfermeria->cardio_parametro_1)
                        ->first();
        $nombre = $nombre .' '. $dato1->descrip_corta;
        }
        if(isset($antecedentesenfermeria->cardio_parametro_2)){
        $dato2 = DB::table('parametros')
                        ->select('parametros.*')
                        ->where('id',$antecedentesenfermeria->cardio_parametro_2)
                        ->first();
        $nombre = $nombre .' - '. $dato2->descrip_corta;
        }
        if(isset($antecedentesenfermeria->cardio_parametro_3)){
        $dato3 = DB::table('parametros')
                        ->select('parametros.*')
                        ->where('id',$antecedentesenfermeria->cardio_parametro_3)
                        ->first();
        $nombre = $nombre .' - '. $dato3->descrip_corta;
        }
        if(isset($antecedentesenfermeria->cardio_parametro_4)){
        $dato4 = DB::table('parametros')
                        ->select('parametros.*')
                        ->where('id',$antecedentesenfermeria->cardio_parametro_4)
                        ->first();
        $nombre = $nombre .' - '. $dato4->descrip_corta ;
        }

        $ingresa_por = DB::table('parametros')
        ->select('parametros.*')
        ->where('id',$valoraciones_enfermerias->id_ingresa_por)
        ->first();

        $condicion_ingreso = DB::table('parametros')
        ->select('parametros.*')
        ->where('id',$valoraciones_enfermerias->id_condicion_ingreso)
        ->first();

        $estado_conciencia = DB::table('parametros')
        ->select('parametros.*')
        ->where('id',$valoraciones_enfermerias->id_estado_conciencia)
        ->first();

        $funcion_motora = DB::table('parametros')
        ->select('parametros.*')
        ->where('id',$valoraciones_enfermerias->id_funcion_motora)
        ->first();

        $respiratorio = DB::table('parametros')
        ->select('parametros.*')
        ->where('id',$valoraciones_enfermerias->id_respiratorio)
        ->first();

        $detalle_oxigeno  = DB::table('parametros')
        ->select('parametros.*')
        ->where('id',$valoraciones_enfermerias->oxigenoterapia_parametro)
        ->first();

        $oxigeno_ids = [];
        $nombres_oxigeno = "";
        if (!empty($valoraciones_enfermerias->oxigenoterapia_parametros)) {
            $oxigeno_ids = array_map('intval', explode(',', $valoraciones_enfermerias->oxigenoterapia_parametros));
        }
        if (!empty($oxigeno_ids)) {
            $detalles_oxigeno = DB::table('parametros')
                ->whereIn('id', $oxigeno_ids)
                ->get();
            /* $nombres_oxigeno = implode('<br>', array_map(function ($item) {
                return $item->descrip_larga;
            }, $detalles_oxigeno)); */
            $nombres_oxigeno = array_map(function ($item) {
                return $item->descrip_larga;
            }, $detalles_oxigeno);
        }

        $piel = DB::table('parametros')
        ->select('parametros.*')
        ->where('id',$valoraciones_enfermerias->id_piel)
        ->first();

        $integridad = DB::table('parametros')
        ->select('parametros.*')
        ->where('id',$valoraciones_enfermerias->id_integridad)
        ->first();

        $abdomen = DB::table('parametros')
        ->select('parametros.*')
        ->where('id',$valoraciones_enfermerias->id_abdomen)
        ->first();

        $puntos = "SELECT * FROM puntos where id_enfermeria = '$enfermeria_id'";
        $puntosenviados = DB::select($puntos);      

        $fechaActual = Carbon::now()->format('d-m-Y');
        $horaActual = Carbon::now()->format('H:i:s');

        $carpeta = date("Y")."/".date("m")."/".date("d");
        
        $nombre_registrador = null;
        $model_usuario = new User;
        $user = $model_usuario->GetById(Auth::user()->id);
        
        $nombre_registrador = $user->name . ' ' . $user->paterno . ' ' . $user->materno;
        $dni_registrador = $user->dni;
        
        $url_descarga = "https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_nota_enfermeria_consulta_externa_".$enfermeria_id.".pdf";
        $codigo_qr = QrCode::format('png')->size(150)->generate("https://sigesapol.dirsapolpnp.gob.pe/".$carpeta."/cf_nota_enfermeria_consulta_externa_" . $enfermeria_id . ".pdf");
        $pdf = \PDF::loadView('frontend.emergencia_enfermeria.pdf_nota_ingreso_enfermeria_parafirmar', compact(
            'datos_nota_ingreso','nombre_establecimiento', 'enfermeria_id', 'datos_atencion', 'id_tipo_atencion', 'enfermeria','signosVitales', 
            'diagnosticos','usuario','fechaActual','horaActual', "url_descarga", "codigo_qr",
            'nota_detalle','nombre_registrador','dni_registrador',
            'existenDiagnosticosDeAtencion',
            'antecedentesenfermeria','valoracionesEnfermeria','nombre',
            'signos_vitales','valoraciones_enfermerias','pieldispositivo_enfermerias','enfermeria_escalas','ingresa_por','condicion_ingreso',
            'estado_conciencia','funcion_motora','respiratorio','detalle_oxigeno','piel','integridad','abdomen','puntosenviados', "nombres_oxigeno"));
        $pdf->setPaper('A4', 'portrait');
        $pdf->getDomPDF()->set_option("enable_php", true);
		file_put_contents($path."nota_enfermeria_".$enfermeria_id. ".pdf", $pdf->output());
    }

    public function FirmarPDFEnfermeria(Request $request)
    {
        $path = public_path('tmp/csv');
        $path_zip = public_path('tmp/zip');
        if (!is_dir($path)) {
            mkdir($path);
        }
        if (!is_dir($path_zip)) {
            mkdir($path_zip);
            mkdir(public_path('tmp/zip/firmados'));
            mkdir(public_path('tmp/zip/sin_firma'));
        }
        $csv = "";
        if (Auth::check()):
            $csv .= Auth::user()->id;
        endif;
        $enfermeria_id = $request->enfermeria_id;
        $id_tipo_atencion = $request->tipo_atencion;
        $csv .= "_".date("YmdHis");
        Excel::create($csv, function($excel) use($enfermeria_id, $id_tipo_atencion){
            $excel->sheet('GENERAL', function($sheet) use($enfermeria_id, $id_tipo_atencion) {
                $variable = [];
                $this->GenerarPDFFirmarEnfermeria($enfermeria_id, $id_tipo_atencion);
                array_push($variable, array(url().'/tmp/pdf/sin_firma/nota_enfermeria_'.$enfermeria_id.'.pdf', url().'/upload_firma/?name=cf_nota_enfermeria_'.$enfermeria_id.'.pdf&id_tipo=enfermeria&id_resol='.$enfermeria_id, 'cf_nota_enfermeria_'.$enfermeria_id.'.pdf','0','45','350','70','Fecha de Firma: <DATE> Firmante: <SIGNER>',url().'/images/escudo.jpg','1','7'));
                $sheet->fromArray($variable, null, '', false, false);
            });
        })->store('csv',public_path('tmp/csv'));
        $obj = new Archive7z(public_path('tmp/zip/sin_firma/'.$csv.'.7z'));
        $obj->addEntry(public_path('/tmp/pdf/sin_firma/nota_enfermeria_'.$enfermeria_id.'.pdf'));
        $resultado["csv"]=$csv;
        $resultado["sw"]=true;
        return response()->json($resultado); 
    }

    public function ActualizarFirmaEnfermeria($enfermeria_id)
    {
        $enfermeriaUpdate = Enfermeria::where("id", $enfermeria_id)->first();
        if (!isset($enfermeriaUpdate)) {
            return response()->json([
                "message" => "No existe registro de la nota de enfermeria"
            ], 404);
        }
        $enfermeriaUpdate->update([
            "ruta_firma" => "cf_nota_enfermeria_{$enfermeria_id}.pdf",
            "fecha_firma" => Carbon::now(),
            "flag_firma" => 1
        ]);
        return response()->json([
            "message" => "Se actualizo la firma de la nota de enfermeria exitosamente"
        ], 200);
    }

    public function registrarNotaEnfermeriaConsultaExterna(Request $request)
    {
        if (!Auth::check()) :
            return response()->json([
                "message" => "no tiene session el usuario"
            ], 401);
        endif;

        $created_at = Carbon::now();
        if (isset($request->fecha_atencion_enfermeria) && !empty($request->fecha_atencion_enfermeria)) {
          $hora_atencion_enfermeria = Carbon::createFromFormat('H:i', $request->hora_atencion_enfermeria)->format('H:i:s');  
          $created_at = $request->fecha_atencion_enfermeria . " " . $hora_atencion_enfermeria;
        }

        $enfermeria = Enfermeria::create([
            'id_prestacion' => isset($request->id_prestacion) && !empty($request->id_prestacion) ? $request->id_prestacion : null,
            'id_tipo_atencion' => $request->id_tipo_atencion,
            'id_estado_reg' => $request->id_estado_reg,
            'created_us' => Auth::user()->id,
            'created_at' => $created_at,
            'updated_us' => Auth::user()->id,
            'updated_at' => Carbon::now(),
            'id_ext_enfermeria' => $request->id_ext_enfermeria,
            'img_base' => null,
            'observacion_enfermeria' => $request->observacion_enfermeria,
            'id_establecimiento_atencion' => Auth::user()->id_establecimiento
        ]);
        if (!isset($enfermeria)) {
            session()->flash('error', "Lamentablemente, no se pudo crear la nota de enfermería en consulta externa. Por favor, intenta nuevamente!");
            return response()->json([
                "message" =>  "Lamentablemente, no se pudo crear la nota de enfermería en consulta externa. Por favor, intenta nuevamente!",
            ], 201);
        }
        foreach ($request->caracter_fisicos as $key => $caracter_fisico) {
            $newCaracterFisico = enfermeriaDetalle::create([
                'id_caracter_fisico' => $caracter_fisico['id'],
                'descripcion' => $caracter_fisico['value'],
                'id_enfermeria' => $enfermeria->id,
                'created_us' => Auth::user()->id,
                'created_at' => Carbon::now(),
                'updated_us' => Auth::user()->id,
                'updated_at' => Carbon::now(),
            ]);
        }
        $arrayCardiologia = $request->antecedentes['cardiologia'];
        
        $antecedenteEnfermeria = AntecedentesEnfermeria::create([
            'id_enfermeria' => $enfermeria->id,
            'id_hipertension' => $request->antecedentes['id_hipertension'],
            'descripcion_hipertension' => $request->antecedentes['descripcion_hipertension'],
            'id_diabetes_millitus' => $request->antecedentes['id_diabetes_millitus'],
            'descripcion_diabetes_millitus' => $request->antecedentes['descripcion_diabetes_millitus'],
            'id_alergia' => $request->antecedentes['id_alergia'],
            'descripcion_alergia' => $request->antecedentes['descripcion_alergia'],
            'id_hemodialisis' => $request->antecedentes['id_hemodialisis'],
            'descripcion_hemodialisis' => $request->antecedentes['descripcion_hemodialisis'],
            'descripcion_observacion' => $request->antecedentes['descripcion_observacion'],
            'descripcion_otro' => $request->antecedentes['descripcion_otro'],
            'cardio_parametro_1' => isset($arrayCardiologia[0]) ? $arrayCardiologia[0] : null,
            'cardio_parametro_2' => isset($arrayCardiologia[1]) ? $arrayCardiologia[1] : null,
            'cardio_parametro_3' => isset($arrayCardiologia[2]) ? $arrayCardiologia[2] : null,
            'cardio_parametro_4' => isset($arrayCardiologia[3]) ? $arrayCardiologia[3] : null,
            'created_us' => Auth::user()->id,
            'created_at' => Carbon::now(),
        ]);

        if (isset($request->procedimientos) && count($request->procedimientos) > 0) {
            foreach ($request->procedimientos as $key => $procedimiento) {
                $newProcedimiento = Prestacion_procedimiento::create([
                    "id_prestacion" => $request->id_prestacion,
                    "id_procedimiento" => $procedimiento["id_procedimiento"],
                    "id_estado_reg" => 1,
                    "created_at" => Carbon::now(),
                    "created_us" => Auth::user()->id,
                    "cantidad" => $procedimiento["procedimiento_cantidad"],
                    "id_diagnostico" => isset($procedimiento["procedimiento_diagnostico"]) ? $procedimiento["procedimiento_diagnostico"] : null,
                    "id_enfermeria" => $enfermeria->id,
                ]);
            }
        }

        session()->flash('success', "La nota de enfermería en consulta externa ha sido creada exitosamente.!");
        return response()->json([
            "message" => "La nota de enfermería en consulta externa ha sido creada exitosamente.!",
        ], 201);
    }
}